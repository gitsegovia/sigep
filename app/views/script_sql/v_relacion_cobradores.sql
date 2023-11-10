DROP VIEW v_relacion_coradores;


CREATE OR REPLACE VIEW v_relacion_coradores AS

select
  a.cod_presi,
  a.cod_entidad,
  a.cod_tipo_inst,
  a.cod_inst,
  a.cod_dep,
  a.rif_ci,
  (SELECT s.rif_cedula FROM shd100_solicitud s  WHERE  s.cod_presi        =  a.cod_presi      and
												       s.cod_entidad      =  a.cod_entidad    and
												       s.cod_tipo_inst    =  a.cod_tipo_inst  and
												       s.cod_inst         =  a.cod_inst       and
												       s.cod_dep          =  a.cod_dep        and
												       s.numero_solicitud =  aa.numero_solicitud) AS rif_cedula,
  a.personalidad,
  a.nombre_razon,
  a.fecha_ingreso,
  a.recurso_cobro,
  a.condicion_actividad,
  (aa.monto_mensual) as monto_mensual,
  (SELECT s.denominacion FROM shd003_codigo_ingresos s WHERE s.cod_ingreso = 1) AS concepto_impuesto,
  (select bb.personalidad_juridica   from shd001_registro_contribuyentes bb  where bb.rif_cedula = (SELECT s.rif_cedula FROM shd100_solicitud s WHERE  s.cod_presi        =  a.cod_presi      and
																																				       s.cod_entidad      =  a.cod_entidad    and
																																				       s.cod_tipo_inst    =  a.cod_tipo_inst  and
																																				       s.cod_inst         =  a.cod_inst       and
																																				       s.cod_dep          =  a.cod_dep        and
																																				       s.numero_solicitud =  aa.numero_solicitud)) as personalidad_juridica,
  (select bb.razon_social_nombres    from shd001_registro_contribuyentes bb  where bb.rif_cedula = (SELECT s.rif_cedula FROM shd100_solicitud s WHERE      s.cod_presi        =  a.cod_presi      and
																																					       s.cod_entidad      =  a.cod_entidad    and
																																					       s.cod_tipo_inst    =  a.cod_tipo_inst  and
																																					       s.cod_inst         =  a.cod_inst       and
																																					       s.cod_dep          =  a.cod_dep        and
																																					       s.numero_solicitud =  aa.numero_solicitud)) as razon_social_nombres,
  (1) as tipo_ingreso
from
    shd002_cobradores a, shd100_patente aa
where aa.cod_presi       =  a.cod_presi       and
      aa.cod_entidad     =  a.cod_entidad    and
      aa.cod_tipo_inst   =  a.cod_tipo_inst  and
      aa.cod_inst        =  a.cod_inst       and
      aa.cod_dep         =  a.cod_dep        and
      aa.rif_ci_cobrador =  a.rif_ci


UNION

select
  a.cod_presi,
  a.cod_entidad,
  a.cod_tipo_inst,
  a.cod_inst,
  a.cod_dep,
  a.rif_ci,
  aa.rif_cedula,
  a.personalidad,
  a.nombre_razon,
  a.fecha_ingreso,
  a.recurso_cobro,
  a.condicion_actividad,
  (aa.monto_mensual) as monto_mensual,
  (SELECT s.denominacion FROM shd003_codigo_ingresos s WHERE s.cod_ingreso = 2) AS concepto_impuesto,
  (select bb.personalidad_juridica   from shd001_registro_contribuyentes bb  where bb.rif_cedula = aa.rif_cedula) as personalidad_juridica,
  (select bb.razon_social_nombres    from shd001_registro_contribuyentes bb  where bb.rif_cedula = aa.rif_cedula) as razon_social_nombres,
  (2) as tipo_ingreso
from
    shd002_cobradores a, shd200_vehiculos aa
where aa.cod_presi       =  a.cod_presi       and
      aa.cod_entidad     =  a.cod_entidad    and
      aa.cod_tipo_inst   =  a.cod_tipo_inst  and
      aa.cod_inst        =  a.cod_inst       and
      aa.cod_dep         =  a.cod_dep        and
      aa.rif_ci_cobrador =  a.rif_ci


UNION

select
  a.cod_presi,
  a.cod_entidad,
  a.cod_tipo_inst,
  a.cod_inst,
  a.cod_dep,
  a.rif_ci,
  aa.rif_cedula,
  a.personalidad,
  a.nombre_razon,
  a.fecha_ingreso,
  a.recurso_cobro,
  a.condicion_actividad,
  (aa.monto_mensual_general) as    monto_mensual,
  (SELECT s.denominacion FROM shd003_codigo_ingresos s WHERE s.cod_ingreso = 3) AS concepto_impuesto,
  (select bb.personalidad_juridica   from shd001_registro_contribuyentes bb  where bb.rif_cedula = aa.rif_cedula) as personalidad_juridica,
  (select bb.razon_social_nombres    from shd001_registro_contribuyentes bb  where bb.rif_cedula = aa.rif_cedula) as razon_social_nombres,
  (3) as tipo_ingreso
from
    shd002_cobradores a, shd300_propaganda aa
where aa.cod_presi       =  a.cod_presi       and
      aa.cod_entidad     =  a.cod_entidad    and
      aa.cod_tipo_inst   =  a.cod_tipo_inst  and
      aa.cod_inst        =  a.cod_inst       and
      aa.cod_dep         =  a.cod_dep        and
      aa.rif_ci_cobrador =  a.rif_ci


UNION

select
  a.cod_presi,
  a.cod_entidad,
  a.cod_tipo_inst,
  a.cod_inst,
  a.cod_dep,
  a.rif_ci,
  aa.rif_cedula,
  a.personalidad,
  a.nombre_razon,
  a.fecha_ingreso,
  a.recurso_cobro,
  a.condicion_actividad,
  (aa.monto_mensual) as    monto_mensual,
  (SELECT s.denominacion FROM shd003_codigo_ingresos s WHERE s.cod_ingreso = 4) AS concepto_impuesto,
  (select bb.personalidad_juridica   from shd001_registro_contribuyentes bb  where bb.rif_cedula = aa.rif_cedula) as personalidad_juridica,
  (select bb.razon_social_nombres    from shd001_registro_contribuyentes bb  where bb.rif_cedula = aa.rif_cedula) as razon_social_nombres,
  (4) as tipo_ingreso
from
    shd002_cobradores a, shd400_propiedad aa
where aa.cod_presi       =  a.cod_presi       and
      aa.cod_entidad     =  a.cod_entidad    and
      aa.cod_tipo_inst   =  a.cod_tipo_inst  and
      aa.cod_inst        =  a.cod_inst       and
      aa.cod_dep         =  a.cod_dep        and
      aa.rif_ci_cobrador =  a.rif_ci


UNION

select
  a.cod_presi,
  a.cod_entidad,
  a.cod_tipo_inst,
  a.cod_inst,
  a.cod_dep,
  a.rif_ci,
  aa.rif_cedula,
  a.personalidad,
  a.nombre_razon,
  a.fecha_ingreso,
  a.recurso_cobro,
  a.condicion_actividad,
  (aa.monto_mensual) as    monto_mensual,
  (SELECT s.denominacion FROM shd003_codigo_ingresos s WHERE s.cod_ingreso = 5) AS concepto_impuesto,
  (select bb.personalidad_juridica   from shd001_registro_contribuyentes bb  where bb.rif_cedula = aa.rif_cedula) as personalidad_juridica,
  (select bb.razon_social_nombres    from shd001_registro_contribuyentes bb  where bb.rif_cedula = aa.rif_cedula) as razon_social_nombres,
  (5) as tipo_ingreso
from
    shd002_cobradores a, shd500_aseo_domiciliario aa
where aa.cod_presi       =  a.cod_presi       and
      aa.cod_entidad     =  a.cod_entidad    and
      aa.cod_tipo_inst   =  a.cod_tipo_inst  and
      aa.cod_inst        =  a.cod_inst       and
      aa.cod_dep         =  a.cod_dep        and
      aa.rif_ci_cobrador =  a.rif_ci


UNION

select
  a.cod_presi,
  a.cod_entidad,
  a.cod_tipo_inst,
  a.cod_inst,
  a.cod_dep,
  a.rif_ci,
  (SELECT s.rif_cedula FROM shd600_solicitud_arrendamiento s  WHERE  s.cod_presi        =  a.cod_presi      and
															         s.cod_entidad      =  a.cod_entidad    and
															         s.cod_tipo_inst    =  a.cod_tipo_inst  and
															         s.cod_inst         =  a.cod_inst       and
															         s.cod_dep          =  a.cod_dep        and
															         s.numero_solicitud =  aa.numero_solicitud) AS rif_cedula,
  a.personalidad,
  a.nombre_razon,
  a.fecha_ingreso,
  a.recurso_cobro,
  a.condicion_actividad,
  (aa.monto_mensual) as    monto_mensual,
  (SELECT s.denominacion FROM shd003_codigo_ingresos s WHERE s.cod_ingreso = 6) AS concepto_impuesto,
  (select bb.personalidad_juridica   from shd001_registro_contribuyentes bb  where bb.rif_cedula = (SELECT s.rif_cedula FROM shd600_solicitud_arrendamiento s WHERE  s.cod_presi        =  a.cod_presi      and
																																							         s.cod_entidad      =  a.cod_entidad    and
																																							         s.cod_tipo_inst    =  a.cod_tipo_inst  and
																																							         s.cod_inst         =  a.cod_inst       and
																																							         s.cod_dep          =  a.cod_dep        and
																																							         s.numero_solicitud =  aa.numero_solicitud)) as personalidad_juridica,
  (select bb.razon_social_nombres    from shd001_registro_contribuyentes bb  where bb.rif_cedula = (SELECT s.rif_cedula FROM shd600_solicitud_arrendamiento s WHERE  s.cod_presi        =  a.cod_presi      and
																																							         s.cod_entidad      =  a.cod_entidad    and
																																							         s.cod_tipo_inst    =  a.cod_tipo_inst  and
																																							         s.cod_inst         =  a.cod_inst       and
																																							         s.cod_dep          =  a.cod_dep        and
																																							         s.numero_solicitud =  aa.numero_solicitud)) as razon_social_nombres,
  (6) as tipo_ingreso
from
    shd002_cobradores a, shd600_aprobacion_arrendamiento aa
where aa.cod_presi       =  a.cod_presi       and
      aa.cod_entidad     =  a.cod_entidad    and
      aa.cod_tipo_inst   =  a.cod_tipo_inst  and
      aa.cod_inst        =  a.cod_inst       and
      aa.cod_dep         =  a.cod_dep        and
      aa.rif_ci_cobrador =  a.rif_ci

UNION

select
  a.cod_presi,
  a.cod_entidad,
  a.cod_tipo_inst,
  a.cod_inst,
  a.cod_dep,
  a.rif_ci,
  aa.rif_cedula,
  a.personalidad,
  a.nombre_razon,
  a.fecha_ingreso,
  a.recurso_cobro,
  a.condicion_actividad,
  (aa.monto_mensual) as   monto_mensual,
  (SELECT s.denominacion FROM shd003_codigo_ingresos s WHERE s.cod_ingreso = 7) AS concepto_impuesto,
  (select bb.personalidad_juridica   from shd001_registro_contribuyentes bb  where bb.rif_cedula = aa.rif_cedula) as personalidad_juridica,
  (select bb.razon_social_nombres    from shd001_registro_contribuyentes bb  where bb.rif_cedula = aa.rif_cedula) as razon_social_nombres,
  (7) as tipo_ingreso
from
    shd002_cobradores a, shd700_credito_vivienda aa
where aa.cod_presi       =  a.cod_presi       and
      aa.cod_entidad     =  a.cod_entidad    and
      aa.cod_tipo_inst   =  a.cod_tipo_inst  and
      aa.cod_inst        =  a.cod_inst       and
      aa.cod_dep         =  a.cod_dep        and
      aa.rif_ci_cobrador =  a.rif_ci;




ALTER TABLE v_relacion_coradores OWNER TO sisap;


