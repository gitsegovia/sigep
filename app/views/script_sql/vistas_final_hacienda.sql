
CREATE OR REPLACE VIEW v_shd900_planillas_deuda_cobro_detalles_cobradores AS

SELECT

  a.cod_presi,
  a.cod_entidad,
  a.cod_tipo_inst,
  a.cod_inst,
  a.cod_dep,
  a.cod_partida,
  a.cod_generica,
  a.cod_especifica,
  a.cod_sub_espec,
  a.cod_auxiliar,
  (SELECT aa.cod_ingreso from shd003_codigo_ingresos aa WHERE  aa.cod_partida      =  a.cod_partida     and
														       aa.cod_generica     =  a.cod_generica    and
														       aa.cod_especifica   =  a.cod_especifica  and
														       aa.cod_subespec     =  a.cod_sub_espec    and
														       aa.cod_auxiliar     =  a.cod_auxiliar       )  as cod_ingreso,
  a.rif_cedula,
  a.cod_numero_catastral_placas,
  a.ano,
  a.mes,
  a.numero_planilla,
  a.deuda_vigente,
  a.monto_recargo,
  a.monto_multa,
  a.monto_intereses,
  a.monto_descuento,
  a.cancelado,
  (SELECT s.rif_ci_cobrador FROM shd100_patente s   WHERE  s.cod_presi        =  a.cod_presi      and
													       s.cod_entidad      =  a.cod_entidad    and
													       s.cod_tipo_inst    =  a.cod_tipo_inst  and
													       s.cod_inst         =  a.cod_inst       and
													       s.cod_dep          =  a.cod_dep        and
													       1                  = (SELECT aa.cod_ingreso from shd003_codigo_ingresos aa  WHERE  aa.cod_partida      =  a.cod_partida     and
																																		      aa.cod_generica     =  a.cod_generica    and
																																		      aa.cod_especifica   =  a.cod_especifica  and
																																		      aa.cod_subespec     =  a.cod_sub_espec   and
																																		      aa.cod_auxiliar     =  a.cod_auxiliar LIMIT 1) and
													       s.numero_solicitud =  (SELECT ss.numero_solicitud FROM shd100_solicitud ss WHERE  ss.cod_presi        =  a.cod_presi      and
																																	         ss.cod_entidad      =  a.cod_entidad    and
																																	         ss.cod_tipo_inst    =  a.cod_tipo_inst  and
																																	         ss.cod_inst         =  a.cod_inst       and
																																	         ss.cod_dep          =  a.cod_dep        and
																																	         ss.rif_cedula       =  a.rif_cedula  LIMIT 1)
                                                     LIMIT 1
   ) as rif_cobrador_1,

   (SELECT s.rif_ci_cobrador FROM shd200_vehiculos s   WHERE   s.cod_presi        =  a.cod_presi      and
														       s.cod_entidad      =  a.cod_entidad    and
														       s.cod_tipo_inst    =  a.cod_tipo_inst  and
														       s.cod_inst         =  a.cod_inst       and
														       s.cod_dep          =  a.cod_dep        and
														       s.rif_cedula       =  a.rif_cedula     and
														       2                  = (SELECT aa.cod_ingreso from shd003_codigo_ingresos aa  WHERE  aa.cod_partida      =  a.cod_partida     and
																																			      aa.cod_generica     =  a.cod_generica    and
																																			      aa.cod_especifica   =  a.cod_especifica  and
																																			      aa.cod_subespec     =  a.cod_sub_espec   and
																																			      aa.cod_auxiliar     =  a.cod_auxiliar LIMIT 1)

   ) as rif_cobrador_2,

   (SELECT s.rif_ci_cobrador FROM shd300_propaganda s   WHERE  s.cod_presi        =  a.cod_presi      and
														       s.cod_entidad      =  a.cod_entidad    and
														       s.cod_tipo_inst    =  a.cod_tipo_inst  and
														       s.cod_inst         =  a.cod_inst       and
														       s.cod_dep          =  a.cod_dep        and
														       s.rif_cedula       =  a.rif_cedula     and
														       3                  = (SELECT aa.cod_ingreso from shd003_codigo_ingresos aa  WHERE  aa.cod_partida      =  a.cod_partida     and
																																			      aa.cod_generica     =  a.cod_generica    and
																																			      aa.cod_especifica   =  a.cod_especifica  and
																																			      aa.cod_subespec     =  a.cod_sub_espec   and
																																			      aa.cod_auxiliar     =  a.cod_auxiliar LIMIT 1)

   ) as rif_cobrador_3,

   (SELECT s.rif_ci_cobrador FROM shd400_propiedad  s   WHERE  s.cod_presi        =  a.cod_presi      and
														       s.cod_entidad      =  a.cod_entidad    and
														       s.cod_tipo_inst    =  a.cod_tipo_inst  and
														       s.cod_inst         =  a.cod_inst       and
														       s.cod_dep          =  a.cod_dep        and
														       s.rif_cedula       =  a.rif_cedula     and
														       4                  = (SELECT aa.cod_ingreso from shd003_codigo_ingresos aa  WHERE  aa.cod_partida      =  a.cod_partida     and
																																			      aa.cod_generica     =  a.cod_generica    and
																																			      aa.cod_especifica   =  a.cod_especifica  and
																																			      aa.cod_subespec     =  a.cod_sub_espec   and
																																			      aa.cod_auxiliar     =  a.cod_auxiliar LIMIT 1)

   ) as rif_cobrador_4,

   (SELECT s.rif_ci_cobrador FROM shd500_aseo_domiciliario  s   WHERE  s.cod_presi        =  a.cod_presi      and
																       s.cod_entidad      =  a.cod_entidad    and
																       s.cod_tipo_inst    =  a.cod_tipo_inst  and
																       s.cod_inst         =  a.cod_inst       and
																       s.cod_dep          =  a.cod_dep        and
																       s.rif_cedula       =  a.rif_cedula     and
																       5                  = (SELECT aa.cod_ingreso from shd003_codigo_ingresos aa  WHERE  aa.cod_partida      =  a.cod_partida     and
																																					      aa.cod_generica     =  a.cod_generica    and
																																					      aa.cod_especifica   =  a.cod_especifica  and
																																					      aa.cod_subespec     =  a.cod_sub_espec   and
																																					      aa.cod_auxiliar     =  a.cod_auxiliar LIMIT 1)

   ) as rif_cobrador_5,

   (SELECT s.rif_ci_cobrador FROM shd100_patente s   WHERE  s.cod_presi        =  a.cod_presi      and
													       s.cod_entidad      =  a.cod_entidad    and
													       s.cod_tipo_inst    =  a.cod_tipo_inst  and
													       s.cod_inst         =  a.cod_inst       and
													       s.cod_dep          =  a.cod_dep        and
													       6                  = (SELECT aa.cod_ingreso from shd003_codigo_ingresos aa  WHERE  aa.cod_partida      =  a.cod_partida     and
																																		      aa.cod_generica     =  a.cod_generica    and
																																		      aa.cod_especifica   =  a.cod_especifica  and
																																		      aa.cod_subespec     =  a.cod_sub_espec   and
																																		      aa.cod_auxiliar     =  a.cod_auxiliar LIMIT 1) and
													       s.numero_solicitud =  (SELECT ss.numero_solicitud FROM shd100_solicitud ss WHERE  ss.cod_presi        =  a.cod_presi      and
																																	         ss.cod_entidad      =  a.cod_entidad    and
																																	         ss.cod_tipo_inst    =  a.cod_tipo_inst  and
																																	         ss.cod_inst         =  a.cod_inst       and
																																	         ss.cod_dep          =  a.cod_dep        and
																																	         ss.rif_cedula       =  a.rif_cedula  LIMIT 1)
                                                     LIMIT 1
   ) as rif_cobrador_6,

   (SELECT s.rif_ci_cobrador FROM shd700_credito_vivienda   s   WHERE  s.cod_presi        =  a.cod_presi      and
																       s.cod_entidad      =  a.cod_entidad    and
																       s.cod_tipo_inst    =  a.cod_tipo_inst  and
																       s.cod_inst         =  a.cod_inst       and
																       s.cod_dep          =  a.cod_dep        and
																       s.rif_cedula       =  a.rif_cedula     and
																       7                  = (SELECT aa.cod_ingreso from shd003_codigo_ingresos aa  WHERE  aa.cod_partida      =  a.cod_partida     and
																																					      aa.cod_generica     =  a.cod_generica    and
																																					      aa.cod_especifica   =  a.cod_especifica  and
																																					      aa.cod_subespec     =  a.cod_sub_espec   and
																																					      aa.cod_auxiliar     =  a.cod_auxiliar LIMIT 1)

   ) as rif_cobrador_7


FROM shd900_planillas_deuda_cobro_detalles a;


ALTER TABLE v_shd900_planillas_deuda_cobro_detalles_cobradores OWNER TO sisap;







CREATE OR REPLACE VIEW v_shd900_planillas_deuda_cobro_detalles_cobradores_2 AS

SELECT

  a.cod_presi,
  a.cod_entidad,
  a.cod_tipo_inst,
  a.cod_inst,
  a.cod_dep,
  a.cod_partida,
  a.cod_generica,
  a.cod_especifica,
  a.cod_sub_espec,
  a.cod_auxiliar,
  a.cod_ingreso,
  (SELECT s.denominacion FROM shd003_codigo_ingresos s WHERE s.cod_ingreso = a.cod_ingreso) AS denominacion_impuesto,
  (select v.descripcion     from cfpd01_partida v     where    v.cod_grupo   =(SUBSTR(a.cod_partida::text, 0, 2))::int and
                                                               v.cod_partida =(SUBSTR(a.cod_partida::text, 2))::int limit 1) as deno_partida,

  (select t.descripcion    from    cfpd01_generica t   where    t.cod_grupo    = (SUBSTR(a.cod_partida::text, 0, 2))::int and
																t.cod_partida  = (SUBSTR(a.cod_partida::text, 2))::int and
																t.cod_generica = a.cod_generica limit 1) as deno_generica,

  (select s.descripcion    from    cfpd01_especifica s     where s.cod_grupo      = (SUBSTR(a.cod_partida::text, 0, 2))::int and
																 s.cod_partida    = (SUBSTR(a.cod_partida::text, 2))::int and
																 s.cod_generica   = a.cod_generica and
																 s.cod_especifica = a.cod_especifica limit 1) as deno_especifica,

  (select r.descripcion    from   cfpd01_sub_espec  r   where   r.cod_grupo       = (SUBSTR(a.cod_partida::text, 0, 2))::int and
                                                                r.cod_partida     = (SUBSTR(a.cod_partida::text, 2))::int and
																r.cod_generica    = a.cod_generica and
																r.cod_especifica  = a.cod_especifica and
																r.cod_sub_espec   = a.cod_sub_espec limit 1) as deno_sub_espe,

  (select o.descripcion  from  cfpd01_auxiliar  o    where      o.cod_grupo       = (SUBSTR(a.cod_partida::text, 0, 2))::int and
																o.cod_partida     = (SUBSTR(a.cod_partida::text, 2))::int and
																o.cod_generica    = a.cod_generica and
																o.cod_especifica  = a.cod_especifica and
																o.cod_sub_espec   = a.cod_sub_espec and
																o.cod_auxiliar    = a.cod_auxiliar limit 1) as deno_auxiliar,
  a.rif_cedula,
  (select bb.personalidad_juridica   from shd001_registro_contribuyentes bb  where bb.rif_cedula = a.rif_cedula) as personalidad_juridica,
  (select bb.razon_social_nombres    from shd001_registro_contribuyentes bb  where bb.rif_cedula = a.rif_cedula) as razon_social_nombres,
  (select bb.personalidad   from shd002_cobradores bb  where bb.cod_presi        =  a.cod_presi      and
													         bb.cod_entidad      =  a.cod_entidad    and
													         bb.cod_tipo_inst    =  a.cod_tipo_inst  and
													         bb.cod_inst         =  a.cod_inst       and
													         bb.cod_dep          =  a.cod_dep        and
													         bb.rif_ci           =  a.rif_cobrador_1     ) as personalidad_cobrador,
  (select bb.nombre_razon   from shd002_cobradores bb  where bb.cod_presi        =  a.cod_presi      and
													         bb.cod_entidad      =  a.cod_entidad    and
													         bb.cod_tipo_inst    =  a.cod_tipo_inst  and
													         bb.cod_inst         =  a.cod_inst       and
													         bb.cod_dep          =  a.cod_dep        and
													         bb.rif_ci           =  a.rif_cobrador_1) as nombre_razon_cobrador,
  (select bb.condicion_actividad   from shd002_cobradores bb   where bb.cod_presi        =  a.cod_presi      and
															         bb.cod_entidad      =  a.cod_entidad    and
															         bb.cod_tipo_inst    =  a.cod_tipo_inst  and
															         bb.cod_inst         =  a.cod_inst       and
															         bb.cod_dep          =  a.cod_dep        and
															         bb.rif_ci           =  a.rif_cobrador_1) as condicion_actividad_cobrador,
  a.cod_numero_catastral_placas,
  a.ano,
  a.mes,
  a.numero_planilla,
  a.deuda_vigente,
  a.monto_recargo,
  a.monto_multa,
  a.monto_intereses,
  a.monto_descuento,
  a.cancelado,
  (a.rif_cobrador_1) as rif_cobrador

FROM v_shd900_planillas_deuda_cobro_detalles_cobradores a

WHERE a.cod_ingreso=1

UNION


SELECT

  a.cod_presi,
  a.cod_entidad,
  a.cod_tipo_inst,
  a.cod_inst,
  a.cod_dep,
  a.cod_partida,
  a.cod_generica,
  a.cod_especifica,
  a.cod_sub_espec,
  a.cod_auxiliar,
  a.cod_ingreso,
  (SELECT s.denominacion FROM shd003_codigo_ingresos s WHERE s.cod_ingreso = a.cod_ingreso) AS denominacion_impuesto,
  (select v.descripcion     from cfpd01_partida v     where    v.cod_grupo   =(SUBSTR(a.cod_partida::text, 0, 2))::int and
                                                               v.cod_partida =(SUBSTR(a.cod_partida::text, 2))::int limit 1) as deno_partida,

  (select t.descripcion    from    cfpd01_generica t   where    t.cod_grupo    = (SUBSTR(a.cod_partida::text, 0, 2))::int and
																t.cod_partida  = (SUBSTR(a.cod_partida::text, 2))::int and
																t.cod_generica = a.cod_generica limit 1) as deno_generica,

  (select s.descripcion    from    cfpd01_especifica s     where s.cod_grupo      = (SUBSTR(a.cod_partida::text, 0, 2))::int and
																 s.cod_partida    = (SUBSTR(a.cod_partida::text, 2))::int and
																 s.cod_generica   = a.cod_generica and
																 s.cod_especifica = a.cod_especifica limit 1) as deno_especifica,

  (select r.descripcion    from   cfpd01_sub_espec  r   where   r.cod_grupo       = (SUBSTR(a.cod_partida::text, 0, 2))::int and
                                                                r.cod_partida     = (SUBSTR(a.cod_partida::text, 2))::int and
																r.cod_generica    = a.cod_generica and
																r.cod_especifica  = a.cod_especifica and
																r.cod_sub_espec   = a.cod_sub_espec limit 1) as deno_sub_espe,

  (select o.descripcion  from  cfpd01_auxiliar  o    where      o.cod_grupo       = (SUBSTR(a.cod_partida::text, 0, 2))::int and
																o.cod_partida     = (SUBSTR(a.cod_partida::text, 2))::int and
																o.cod_generica    = a.cod_generica and
																o.cod_especifica  = a.cod_especifica and
																o.cod_sub_espec   = a.cod_sub_espec and
																o.cod_auxiliar    = a.cod_auxiliar limit 1) as deno_auxiliar,
  a.rif_cedula,
  (select bb.personalidad_juridica   from shd001_registro_contribuyentes bb  where bb.rif_cedula = a.rif_cedula) as personalidad_juridica,
  (select bb.razon_social_nombres    from shd001_registro_contribuyentes bb  where bb.rif_cedula = a.rif_cedula) as razon_social_nombres,
  (select bb.personalidad   from shd002_cobradores bb  where bb.cod_presi        =  a.cod_presi      and
													         bb.cod_entidad      =  a.cod_entidad    and
													         bb.cod_tipo_inst    =  a.cod_tipo_inst  and
													         bb.cod_inst         =  a.cod_inst       and
													         bb.cod_dep          =  a.cod_dep        and
													         bb.rif_ci           =  a.rif_cobrador_2     ) as personalidad_cobrador,
  (select bb.nombre_razon   from shd002_cobradores bb  where bb.cod_presi        =  a.cod_presi      and
													         bb.cod_entidad      =  a.cod_entidad    and
													         bb.cod_tipo_inst    =  a.cod_tipo_inst  and
													         bb.cod_inst         =  a.cod_inst       and
													         bb.cod_dep          =  a.cod_dep        and
													         bb.rif_ci           =  a.rif_cobrador_2) as nombre_razon_cobrador,
  (select bb.condicion_actividad   from shd002_cobradores bb   where bb.cod_presi        =  a.cod_presi      and
															         bb.cod_entidad      =  a.cod_entidad    and
															         bb.cod_tipo_inst    =  a.cod_tipo_inst  and
															         bb.cod_inst         =  a.cod_inst       and
															         bb.cod_dep          =  a.cod_dep        and
															         bb.rif_ci           =  a.rif_cobrador_2) as condicion_actividad_cobrador,
  a.cod_numero_catastral_placas,
  a.ano,
  a.mes,
  a.numero_planilla,
  a.deuda_vigente,
  a.monto_recargo,
  a.monto_multa,
  a.monto_intereses,
  a.monto_descuento,
  a.cancelado,
  (a.rif_cobrador_2) as rif_cobrador

FROM v_shd900_planillas_deuda_cobro_detalles_cobradores a

WHERE a.cod_ingreso=2


UNION


SELECT

  a.cod_presi,
  a.cod_entidad,
  a.cod_tipo_inst,
  a.cod_inst,
  a.cod_dep,
  a.cod_partida,
  a.cod_generica,
  a.cod_especifica,
  a.cod_sub_espec,
  a.cod_auxiliar,
  a.cod_ingreso,
  (SELECT s.denominacion FROM shd003_codigo_ingresos s WHERE s.cod_ingreso = a.cod_ingreso) AS denominacion_impuesto,
  (select v.descripcion     from cfpd01_partida v     where    v.cod_grupo   =(SUBSTR(a.cod_partida::text, 0, 2))::int and
                                                               v.cod_partida =(SUBSTR(a.cod_partida::text, 2))::int limit 1) as deno_partida,

  (select t.descripcion    from    cfpd01_generica t   where    t.cod_grupo    = (SUBSTR(a.cod_partida::text, 0, 2))::int and
																t.cod_partida  = (SUBSTR(a.cod_partida::text, 2))::int and
																t.cod_generica = a.cod_generica limit 1) as deno_generica,

  (select s.descripcion    from    cfpd01_especifica s     where s.cod_grupo      = (SUBSTR(a.cod_partida::text, 0, 2))::int and
																 s.cod_partida    = (SUBSTR(a.cod_partida::text, 2))::int and
																 s.cod_generica   = a.cod_generica and
																 s.cod_especifica = a.cod_especifica limit 1) as deno_especifica,

  (select r.descripcion    from   cfpd01_sub_espec  r   where   r.cod_grupo       = (SUBSTR(a.cod_partida::text, 0, 2))::int and
                                                                r.cod_partida     = (SUBSTR(a.cod_partida::text, 2))::int and
																r.cod_generica    = a.cod_generica and
																r.cod_especifica  = a.cod_especifica and
																r.cod_sub_espec   = a.cod_sub_espec limit 1) as deno_sub_espe,

  (select o.descripcion  from  cfpd01_auxiliar  o    where      o.cod_grupo       = (SUBSTR(a.cod_partida::text, 0, 2))::int and
																o.cod_partida     = (SUBSTR(a.cod_partida::text, 2))::int and
																o.cod_generica    = a.cod_generica and
																o.cod_especifica  = a.cod_especifica and
																o.cod_sub_espec   = a.cod_sub_espec and
																o.cod_auxiliar    = a.cod_auxiliar limit 1) as deno_auxiliar,
  a.rif_cedula,
  (select bb.personalidad_juridica   from shd001_registro_contribuyentes bb  where bb.rif_cedula = a.rif_cedula) as personalidad_juridica,
  (select bb.razon_social_nombres    from shd001_registro_contribuyentes bb  where bb.rif_cedula = a.rif_cedula) as razon_social_nombres,
  (select bb.personalidad   from shd002_cobradores bb  where bb.cod_presi        =  a.cod_presi      and
													         bb.cod_entidad      =  a.cod_entidad    and
													         bb.cod_tipo_inst    =  a.cod_tipo_inst  and
													         bb.cod_inst         =  a.cod_inst       and
													         bb.cod_dep          =  a.cod_dep        and
													         bb.rif_ci           =  a.rif_cobrador_3     ) as personalidad_cobrador,
  (select bb.nombre_razon   from shd002_cobradores bb  where bb.cod_presi        =  a.cod_presi      and
													         bb.cod_entidad      =  a.cod_entidad    and
													         bb.cod_tipo_inst    =  a.cod_tipo_inst  and
													         bb.cod_inst         =  a.cod_inst       and
													         bb.cod_dep          =  a.cod_dep        and
													         bb.rif_ci           =  a.rif_cobrador_3) as nombre_razon_cobrador,
  (select bb.condicion_actividad   from shd002_cobradores bb   where bb.cod_presi        =  a.cod_presi      and
															         bb.cod_entidad      =  a.cod_entidad    and
															         bb.cod_tipo_inst    =  a.cod_tipo_inst  and
															         bb.cod_inst         =  a.cod_inst       and
															         bb.cod_dep          =  a.cod_dep        and
															         bb.rif_ci           =  a.rif_cobrador_3) as condicion_actividad_cobrador,
  a.cod_numero_catastral_placas,
  a.ano,
  a.mes,
  a.numero_planilla,
  a.deuda_vigente,
  a.monto_recargo,
  a.monto_multa,
  a.monto_intereses,
  a.monto_descuento,
  a.cancelado,
  (a.rif_cobrador_3) as rif_cobrador

FROM v_shd900_planillas_deuda_cobro_detalles_cobradores a

WHERE a.cod_ingreso=3

UNION

SELECT

  a.cod_presi,
  a.cod_entidad,
  a.cod_tipo_inst,
  a.cod_inst,
  a.cod_dep,
  a.cod_partida,
  a.cod_generica,
  a.cod_especifica,
  a.cod_sub_espec,
  a.cod_auxiliar,
  a.cod_ingreso,
  (SELECT s.denominacion FROM shd003_codigo_ingresos s WHERE s.cod_ingreso = a.cod_ingreso) AS denominacion_impuesto,
  (select v.descripcion     from cfpd01_partida v     where    v.cod_grupo   =(SUBSTR(a.cod_partida::text, 0, 2))::int and
                                                               v.cod_partida =(SUBSTR(a.cod_partida::text, 2))::int limit 1) as deno_partida,

  (select t.descripcion    from    cfpd01_generica t   where    t.cod_grupo    = (SUBSTR(a.cod_partida::text, 0, 2))::int and
																t.cod_partida  = (SUBSTR(a.cod_partida::text, 2))::int and
																t.cod_generica = a.cod_generica limit 1) as deno_generica,

  (select s.descripcion    from    cfpd01_especifica s     where s.cod_grupo      = (SUBSTR(a.cod_partida::text, 0, 2))::int and
																 s.cod_partida    = (SUBSTR(a.cod_partida::text, 2))::int and
																 s.cod_generica   = a.cod_generica and
																 s.cod_especifica = a.cod_especifica limit 1) as deno_especifica,

  (select r.descripcion    from   cfpd01_sub_espec  r   where   r.cod_grupo       = (SUBSTR(a.cod_partida::text, 0, 2))::int and
                                                                r.cod_partida     = (SUBSTR(a.cod_partida::text, 2))::int and
																r.cod_generica    = a.cod_generica and
																r.cod_especifica  = a.cod_especifica and
																r.cod_sub_espec   = a.cod_sub_espec limit 1) as deno_sub_espe,

  (select o.descripcion  from  cfpd01_auxiliar  o    where      o.cod_grupo       = (SUBSTR(a.cod_partida::text, 0, 2))::int and
																o.cod_partida     = (SUBSTR(a.cod_partida::text, 2))::int and
																o.cod_generica    = a.cod_generica and
																o.cod_especifica  = a.cod_especifica and
																o.cod_sub_espec   = a.cod_sub_espec and
																o.cod_auxiliar    = a.cod_auxiliar limit 1) as deno_auxiliar,
  a.rif_cedula,
  (select bb.personalidad_juridica   from shd001_registro_contribuyentes bb  where bb.rif_cedula = a.rif_cedula) as personalidad_juridica,
  (select bb.razon_social_nombres    from shd001_registro_contribuyentes bb  where bb.rif_cedula = a.rif_cedula) as razon_social_nombres,
  (select bb.personalidad   from shd002_cobradores bb  where bb.cod_presi        =  a.cod_presi      and
													         bb.cod_entidad      =  a.cod_entidad    and
													         bb.cod_tipo_inst    =  a.cod_tipo_inst  and
													         bb.cod_inst         =  a.cod_inst       and
													         bb.cod_dep          =  a.cod_dep        and
													         bb.rif_ci           =  a.rif_cobrador_4     ) as personalidad_cobrador,
  (select bb.nombre_razon   from shd002_cobradores bb  where bb.cod_presi        =  a.cod_presi      and
													         bb.cod_entidad      =  a.cod_entidad    and
													         bb.cod_tipo_inst    =  a.cod_tipo_inst  and
													         bb.cod_inst         =  a.cod_inst       and
													         bb.cod_dep          =  a.cod_dep        and
													         bb.rif_ci           =  a.rif_cobrador_4) as nombre_razon_cobrador,
  (select bb.condicion_actividad   from shd002_cobradores bb   where bb.cod_presi        =  a.cod_presi      and
															         bb.cod_entidad      =  a.cod_entidad    and
															         bb.cod_tipo_inst    =  a.cod_tipo_inst  and
															         bb.cod_inst         =  a.cod_inst       and
															         bb.cod_dep          =  a.cod_dep        and
															         bb.rif_ci           =  a.rif_cobrador_4) as condicion_actividad_cobrador,
  a.cod_numero_catastral_placas,
  a.ano,
  a.mes,
  a.numero_planilla,
  a.deuda_vigente,
  a.monto_recargo,
  a.monto_multa,
  a.monto_intereses,
  a.monto_descuento,
  a.cancelado,
  (a.rif_cobrador_4) as rif_cobrador

FROM v_shd900_planillas_deuda_cobro_detalles_cobradores a

WHERE a.cod_ingreso=4


UNION


SELECT

  a.cod_presi,
  a.cod_entidad,
  a.cod_tipo_inst,
  a.cod_inst,
  a.cod_dep,
  a.cod_partida,
  a.cod_generica,
  a.cod_especifica,
  a.cod_sub_espec,
  a.cod_auxiliar,
  a.cod_ingreso,
  (SELECT s.denominacion FROM shd003_codigo_ingresos s WHERE s.cod_ingreso = a.cod_ingreso) AS denominacion_impuesto,
  (select v.descripcion     from cfpd01_partida v     where    v.cod_grupo   =(SUBSTR(a.cod_partida::text, 0, 2))::int and
                                                               v.cod_partida =(SUBSTR(a.cod_partida::text, 2))::int limit 1) as deno_partida,

  (select t.descripcion    from    cfpd01_generica t   where    t.cod_grupo    = (SUBSTR(a.cod_partida::text, 0, 2))::int and
																t.cod_partida  = (SUBSTR(a.cod_partida::text, 2))::int and
																t.cod_generica = a.cod_generica limit 1) as deno_generica,

  (select s.descripcion    from    cfpd01_especifica s     where s.cod_grupo      = (SUBSTR(a.cod_partida::text, 0, 2))::int and
																 s.cod_partida    = (SUBSTR(a.cod_partida::text, 2))::int and
																 s.cod_generica   = a.cod_generica and
																 s.cod_especifica = a.cod_especifica limit 1) as deno_especifica,

  (select r.descripcion    from   cfpd01_sub_espec  r   where   r.cod_grupo       = (SUBSTR(a.cod_partida::text, 0, 2))::int and
                                                                r.cod_partida     = (SUBSTR(a.cod_partida::text, 2))::int and
																r.cod_generica    = a.cod_generica and
																r.cod_especifica  = a.cod_especifica and
																r.cod_sub_espec   = a.cod_sub_espec limit 1) as deno_sub_espe,

  (select o.descripcion  from  cfpd01_auxiliar  o    where      o.cod_grupo       = (SUBSTR(a.cod_partida::text, 0, 2))::int and
																o.cod_partida     = (SUBSTR(a.cod_partida::text, 2))::int and
																o.cod_generica    = a.cod_generica and
																o.cod_especifica  = a.cod_especifica and
																o.cod_sub_espec   = a.cod_sub_espec and
																o.cod_auxiliar    = a.cod_auxiliar limit 1) as deno_auxiliar,
  a.rif_cedula,
  (select bb.personalidad_juridica   from shd001_registro_contribuyentes bb  where bb.rif_cedula = a.rif_cedula) as personalidad_juridica,
  (select bb.razon_social_nombres    from shd001_registro_contribuyentes bb  where bb.rif_cedula = a.rif_cedula) as razon_social_nombres,
  (select bb.personalidad   from shd002_cobradores bb  where bb.cod_presi        =  a.cod_presi      and
													         bb.cod_entidad      =  a.cod_entidad    and
													         bb.cod_tipo_inst    =  a.cod_tipo_inst  and
													         bb.cod_inst         =  a.cod_inst       and
													         bb.cod_dep          =  a.cod_dep        and
													         bb.rif_ci           =  a.rif_cobrador_5     ) as personalidad_cobrador,
  (select bb.nombre_razon   from shd002_cobradores bb  where bb.cod_presi        =  a.cod_presi      and
													         bb.cod_entidad      =  a.cod_entidad    and
													         bb.cod_tipo_inst    =  a.cod_tipo_inst  and
													         bb.cod_inst         =  a.cod_inst       and
													         bb.cod_dep          =  a.cod_dep        and
													         bb.rif_ci           =  a.rif_cobrador_5) as nombre_razon_cobrador,
  (select bb.condicion_actividad   from shd002_cobradores bb   where bb.cod_presi        =  a.cod_presi      and
															         bb.cod_entidad      =  a.cod_entidad    and
															         bb.cod_tipo_inst    =  a.cod_tipo_inst  and
															         bb.cod_inst         =  a.cod_inst       and
															         bb.cod_dep          =  a.cod_dep        and
															         bb.rif_ci           =  a.rif_cobrador_5) as condicion_actividad_cobrador,
  a.cod_numero_catastral_placas,
  a.ano,
  a.mes,
  a.numero_planilla,
  a.deuda_vigente,
  a.monto_recargo,
  a.monto_multa,
  a.monto_intereses,
  a.monto_descuento,
  a.cancelado,
  (a.rif_cobrador_5) as rif_cobrador

FROM v_shd900_planillas_deuda_cobro_detalles_cobradores a

WHERE a.cod_ingreso=5



UNION


SELECT

  a.cod_presi,
  a.cod_entidad,
  a.cod_tipo_inst,
  a.cod_inst,
  a.cod_dep,
  a.cod_partida,
  a.cod_generica,
  a.cod_especifica,
  a.cod_sub_espec,
  a.cod_auxiliar,
  a.cod_ingreso,
  (SELECT s.denominacion FROM shd003_codigo_ingresos s WHERE s.cod_ingreso = a.cod_ingreso) AS denominacion_impuesto,
  (select v.descripcion     from cfpd01_partida v     where    v.cod_grupo   =(SUBSTR(a.cod_partida::text, 0, 2))::int and
                                                               v.cod_partida =(SUBSTR(a.cod_partida::text, 2))::int limit 1) as deno_partida,

  (select t.descripcion    from    cfpd01_generica t   where    t.cod_grupo    = (SUBSTR(a.cod_partida::text, 0, 2))::int and
																t.cod_partida  = (SUBSTR(a.cod_partida::text, 2))::int and
																t.cod_generica = a.cod_generica limit 1) as deno_generica,

  (select s.descripcion    from    cfpd01_especifica s     where s.cod_grupo      = (SUBSTR(a.cod_partida::text, 0, 2))::int and
																 s.cod_partida    = (SUBSTR(a.cod_partida::text, 2))::int and
																 s.cod_generica   = a.cod_generica and
																 s.cod_especifica = a.cod_especifica limit 1) as deno_especifica,

  (select r.descripcion    from   cfpd01_sub_espec  r   where   r.cod_grupo       = (SUBSTR(a.cod_partida::text, 0, 2))::int and
                                                                r.cod_partida     = (SUBSTR(a.cod_partida::text, 2))::int and
																r.cod_generica    = a.cod_generica and
																r.cod_especifica  = a.cod_especifica and
																r.cod_sub_espec   = a.cod_sub_espec limit 1) as deno_sub_espe,

  (select o.descripcion  from  cfpd01_auxiliar  o    where      o.cod_grupo       = (SUBSTR(a.cod_partida::text, 0, 2))::int and
																o.cod_partida     = (SUBSTR(a.cod_partida::text, 2))::int and
																o.cod_generica    = a.cod_generica and
																o.cod_especifica  = a.cod_especifica and
																o.cod_sub_espec   = a.cod_sub_espec and
																o.cod_auxiliar    = a.cod_auxiliar limit 1) as deno_auxiliar,
  a.rif_cedula,
  (select bb.personalidad_juridica   from shd001_registro_contribuyentes bb  where bb.rif_cedula = a.rif_cedula) as personalidad_juridica,
  (select bb.razon_social_nombres    from shd001_registro_contribuyentes bb  where bb.rif_cedula = a.rif_cedula) as razon_social_nombres,
  (select bb.personalidad   from shd002_cobradores bb  where bb.cod_presi        =  a.cod_presi      and
													         bb.cod_entidad      =  a.cod_entidad    and
													         bb.cod_tipo_inst    =  a.cod_tipo_inst  and
													         bb.cod_inst         =  a.cod_inst       and
													         bb.cod_dep          =  a.cod_dep        and
													         bb.rif_ci           =  a.rif_cobrador_6     ) as personalidad_cobrador,
  (select bb.nombre_razon   from shd002_cobradores bb  where bb.cod_presi        =  a.cod_presi      and
													         bb.cod_entidad      =  a.cod_entidad    and
													         bb.cod_tipo_inst    =  a.cod_tipo_inst  and
													         bb.cod_inst         =  a.cod_inst       and
													         bb.cod_dep          =  a.cod_dep        and
													         bb.rif_ci           =  a.rif_cobrador_6) as nombre_razon_cobrador,
  (select bb.condicion_actividad   from shd002_cobradores bb   where bb.cod_presi        =  a.cod_presi      and
															         bb.cod_entidad      =  a.cod_entidad    and
															         bb.cod_tipo_inst    =  a.cod_tipo_inst  and
															         bb.cod_inst         =  a.cod_inst       and
															         bb.cod_dep          =  a.cod_dep        and
															         bb.rif_ci           =  a.rif_cobrador_6) as condicion_actividad_cobrador,
  a.cod_numero_catastral_placas,
  a.ano,
  a.mes,
  a.numero_planilla,
  a.deuda_vigente,
  a.monto_recargo,
  a.monto_multa,
  a.monto_intereses,
  a.monto_descuento,
  a.cancelado,
  (a.rif_cobrador_6) as rif_cobrador

FROM v_shd900_planillas_deuda_cobro_detalles_cobradores a

WHERE a.cod_ingreso=6


UNION


SELECT

  a.cod_presi,
  a.cod_entidad,
  a.cod_tipo_inst,
  a.cod_inst,
  a.cod_dep,
  a.cod_partida,
  a.cod_generica,
  a.cod_especifica,
  a.cod_sub_espec,
  a.cod_auxiliar,
  a.cod_ingreso,
  (SELECT s.denominacion FROM shd003_codigo_ingresos s WHERE s.cod_ingreso = a.cod_ingreso) AS denominacion_impuesto,
  (select v.descripcion     from cfpd01_partida v     where    v.cod_grupo   =(SUBSTR(a.cod_partida::text, 0, 2))::int and
                                                               v.cod_partida =(SUBSTR(a.cod_partida::text, 2))::int limit 1) as deno_partida,

  (select t.descripcion    from    cfpd01_generica t   where    t.cod_grupo    = (SUBSTR(a.cod_partida::text, 0, 2))::int and
																t.cod_partida  = (SUBSTR(a.cod_partida::text, 2))::int and
																t.cod_generica = a.cod_generica limit 1) as deno_generica,

  (select s.descripcion    from    cfpd01_especifica s     where s.cod_grupo      = (SUBSTR(a.cod_partida::text, 0, 2))::int and
																 s.cod_partida    = (SUBSTR(a.cod_partida::text, 2))::int and
																 s.cod_generica   = a.cod_generica and
																 s.cod_especifica = a.cod_especifica limit 1) as deno_especifica,

  (select r.descripcion    from   cfpd01_sub_espec  r   where   r.cod_grupo       = (SUBSTR(a.cod_partida::text, 0, 2))::int and
                                                                r.cod_partida     = (SUBSTR(a.cod_partida::text, 2))::int and
																r.cod_generica    = a.cod_generica and
																r.cod_especifica  = a.cod_especifica and
																r.cod_sub_espec   = a.cod_sub_espec limit 1) as deno_sub_espe,

  (select o.descripcion  from  cfpd01_auxiliar  o    where      o.cod_grupo       = (SUBSTR(a.cod_partida::text, 0, 2))::int and
																o.cod_partida     = (SUBSTR(a.cod_partida::text, 2))::int and
																o.cod_generica    = a.cod_generica and
																o.cod_especifica  = a.cod_especifica and
																o.cod_sub_espec   = a.cod_sub_espec and
																o.cod_auxiliar    = a.cod_auxiliar limit 1) as deno_auxiliar,
  a.rif_cedula,
  (select bb.personalidad_juridica   from shd001_registro_contribuyentes bb  where bb.rif_cedula = a.rif_cedula) as personalidad_juridica,
  (select bb.razon_social_nombres    from shd001_registro_contribuyentes bb  where bb.rif_cedula = a.rif_cedula) as razon_social_nombres,
  (select bb.personalidad   from shd002_cobradores bb  where bb.cod_presi        =  a.cod_presi      and
													         bb.cod_entidad      =  a.cod_entidad    and
													         bb.cod_tipo_inst    =  a.cod_tipo_inst  and
													         bb.cod_inst         =  a.cod_inst       and
													         bb.cod_dep          =  a.cod_dep        and
													         bb.rif_ci           =  a.rif_cobrador_7     ) as personalidad_cobrador,
  (select bb.nombre_razon   from shd002_cobradores bb  where bb.cod_presi        =  a.cod_presi      and
													         bb.cod_entidad      =  a.cod_entidad    and
													         bb.cod_tipo_inst    =  a.cod_tipo_inst  and
													         bb.cod_inst         =  a.cod_inst       and
													         bb.cod_dep          =  a.cod_dep        and
													         bb.rif_ci           =  a.rif_cobrador_7) as nombre_razon_cobrador,
  (select bb.condicion_actividad   from shd002_cobradores bb   where bb.cod_presi        =  a.cod_presi      and
															         bb.cod_entidad      =  a.cod_entidad    and
															         bb.cod_tipo_inst    =  a.cod_tipo_inst  and
															         bb.cod_inst         =  a.cod_inst       and
															         bb.cod_dep          =  a.cod_dep        and
															         bb.rif_ci           =  a.rif_cobrador_7) as condicion_actividad_cobrador,
  a.cod_numero_catastral_placas,
  a.ano,
  a.mes,
  a.numero_planilla,
  a.deuda_vigente,
  a.monto_recargo,
  a.monto_multa,
  a.monto_intereses,
  a.monto_descuento,
  a.cancelado,
  (a.rif_cobrador_7) as rif_cobrador

FROM v_shd900_planillas_deuda_cobro_detalles_cobradores a

WHERE a.cod_ingreso=7;


ALTER TABLE v_shd900_planillas_deuda_cobro_detalles_cobradores_2 OWNER TO sisap;





-- View: v_shd001_registro_contribuyentes

-- DROP VIEW v_shd001_registro_contribuyentes;

CREATE OR REPLACE VIEW v_shd001_registro_contribuyentes AS
 SELECT a.rif_cedula, a.personalidad_juridica, a.razon_social_nombres, a.fecha_inscripcion, a.nacionalidad, a.estado_civil, a.profesion, ( SELECT b.denominacion
           FROM cnmd06_profesiones b
          WHERE a.profesion = b.cod_profesion) AS deno_profesion, a.cod_pais, ( SELECT c.denominacion
           FROM cugd01_republica c
          WHERE a.cod_pais = c.cod_republica) AS deno_pais, a.cod_estado, ( SELECT d.denominacion
           FROM cugd01_estados d
          WHERE a.cod_pais = d.cod_republica AND a.cod_estado = d.cod_estado) AS deno_estado, a.cod_municipio, ( SELECT e.denominacion
           FROM cugd01_municipios e
          WHERE a.cod_pais = e.cod_republica AND a.cod_estado = e.cod_estado AND a.cod_municipio = e.cod_municipio) AS deno_municipio, a.cod_parroquia, ( SELECT f.denominacion
           FROM cugd01_parroquias f
          WHERE a.cod_pais = f.cod_republica AND a.cod_estado = f.cod_estado AND a.cod_municipio = f.cod_municipio AND a.cod_parroquia = f.cod_parroquia) AS deno_parroquia, a.cod_centro_poblado, ( SELECT g.denominacion
           FROM cugd01_centros_poblados g
          WHERE a.cod_pais = g.cod_republica AND a.cod_estado = g.cod_estado AND a.cod_municipio = g.cod_municipio AND a.cod_parroquia = g.cod_parroquia AND a.cod_centro_poblado = g.cod_centro) AS deno_centro, a.cod_calle_avenida, ( SELECT h.denominacion
           FROM cugd01_vialidad h
          WHERE a.cod_pais = h.cod_republica AND a.cod_estado = h.cod_estado AND a.cod_municipio = h.cod_municipio AND a.cod_parroquia = h.cod_parroquia AND a.cod_centro_poblado = h.cod_centro AND a.cod_calle_avenida = h.cod_vialidad) AS deno_vialidad, a.cod_vereda_edificio, ( SELECT i.denominacion
           FROM cugd01_vereda i
          WHERE a.cod_pais = i.cod_republica AND a.cod_estado = i.cod_estado AND a.cod_municipio = i.cod_municipio AND a.cod_parroquia = i.cod_parroquia AND a.cod_centro_poblado = i.cod_centro AND a.cod_calle_avenida = i.cod_vialidad AND a.cod_vereda_edificio = i.cod_vereda) AS deno_vereda, a.numero_vivienda_local, a.telefonos_fijos, a.telefonos_celulares, a.correo_electronico
   FROM shd001_registro_contribuyentes a;

ALTER TABLE v_shd001_registro_contribuyentes OWNER TO sisap;

-- View: v_shd200_vehiculos

-- DROP VIEW v_shd200_vehiculos;

CREATE OR REPLACE VIEW v_shd200_vehiculos AS
 SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.rif_cedula, ( SELECT b.razon_social_nombres
           FROM v_shd001_registro_contribuyentes b
          WHERE a.rif_cedula::text = b.rif_cedula::text) AS nombre_razon, ( SELECT b.cod_pais
           FROM v_shd001_registro_contribuyentes b
          WHERE a.rif_cedula::text = b.rif_cedula::text) AS cod_pais, ( SELECT b.deno_pais
           FROM v_shd001_registro_contribuyentes b
          WHERE a.rif_cedula::text = b.rif_cedula::text) AS deno_pais, ( SELECT b.cod_estado
           FROM v_shd001_registro_contribuyentes b
          WHERE a.rif_cedula::text = b.rif_cedula::text) AS cod_estado, ( SELECT b.deno_estado
           FROM v_shd001_registro_contribuyentes b
          WHERE a.rif_cedula::text = b.rif_cedula::text) AS deno_estado, ( SELECT b.cod_municipio
           FROM v_shd001_registro_contribuyentes b
          WHERE a.rif_cedula::text = b.rif_cedula::text) AS cod_municipio, ( SELECT b.deno_municipio
           FROM v_shd001_registro_contribuyentes b
          WHERE a.rif_cedula::text = b.rif_cedula::text) AS deno_municipio, ( SELECT b.cod_parroquia
           FROM v_shd001_registro_contribuyentes b
          WHERE a.rif_cedula::text = b.rif_cedula::text) AS cod_parroquia, ( SELECT b.deno_parroquia
           FROM v_shd001_registro_contribuyentes b
          WHERE a.rif_cedula::text = b.rif_cedula::text) AS deno_parroquia, ( SELECT b.cod_centro_poblado
           FROM v_shd001_registro_contribuyentes b
          WHERE a.rif_cedula::text = b.rif_cedula::text) AS cod_centro, ( SELECT b.deno_centro
           FROM v_shd001_registro_contribuyentes b
          WHERE a.rif_cedula::text = b.rif_cedula::text) AS deno_centro, ( SELECT b.cod_calle_avenida
           FROM v_shd001_registro_contribuyentes b
          WHERE a.rif_cedula::text = b.rif_cedula::text) AS cod_calle, ( SELECT b.deno_vialidad
           FROM v_shd001_registro_contribuyentes b
          WHERE a.rif_cedula::text = b.rif_cedula::text) AS deno_calle, ( SELECT b.cod_vereda_edificio
           FROM v_shd001_registro_contribuyentes b
          WHERE a.rif_cedula::text = b.rif_cedula::text) AS cod_vereda_edificio, ( SELECT b.deno_vereda
           FROM v_shd001_registro_contribuyentes b
          WHERE a.rif_cedula::text = b.rif_cedula::text) AS deno_vereda, ( SELECT b.numero_vivienda_local
           FROM v_shd001_registro_contribuyentes b
          WHERE a.rif_cedula::text = b.rif_cedula::text) AS numero_casa, ( SELECT b.fecha_inscripcion
           FROM v_shd001_registro_contribuyentes b
          WHERE a.rif_cedula::text = b.rif_cedula::text) AS fecha_inscripcion, ( SELECT b.telefonos_fijos
           FROM v_shd001_registro_contribuyentes b
          WHERE a.rif_cedula::text = b.rif_cedula::text) AS telefonos_fijos, ( SELECT b.telefonos_celulares
           FROM v_shd001_registro_contribuyentes b
          WHERE a.rif_cedula::text = b.rif_cedula::text) AS telefonos_celulares, ( SELECT b.correo_electronico
           FROM v_shd001_registro_contribuyentes b
          WHERE a.rif_cedula::text = b.rif_cedula::text) AS correo_electronico, ( SELECT b.nacionalidad
           FROM v_shd001_registro_contribuyentes b
          WHERE a.rif_cedula::text = b.rif_cedula::text) AS nacionalidad, ( SELECT b.estado_civil
           FROM v_shd001_registro_contribuyentes b
          WHERE a.rif_cedula::text = b.rif_cedula::text) AS estado_civil, ( SELECT b.deno_profesion
           FROM v_shd001_registro_contribuyentes b
          WHERE a.rif_cedula::text = b.rif_cedula::text) AS deno_profesion, a.placa_vehiculo, a.fecha_registro, a.cod_marca, ( SELECT e.denominacion
           FROM shd200_vehiculos_marcas e
          WHERE a.cod_marca = e.codigo_marca) AS deno_marca, a.cod_modelo, ( SELECT f.denominacion
           FROM shd200_vehiculos_modelos f
          WHERE a.cod_modelo = f.codigo_modelo) AS deno_modelo, a.cod_color, ( SELECT g.denominacion
           FROM shd200_vehiculos_colores g
          WHERE a.cod_color = g.codigo_color) AS deno_color, a.cod_clase, ( SELECT h.denominacion
           FROM shd200_vehiculos_clases h
          WHERE a.cod_clase = h.codigo_clase) AS deno_clase, a.cod_tipo, ( SELECT i.denominacion
           FROM shd200_vehiculos_tipos i
          WHERE a.cod_tipo = i.codigo_tipo) AS deno_tipo, a.cod_uso, ( SELECT j.denominacion
           FROM shd200_vehiculos_usos j
          WHERE a.cod_uso = j.codigo_uso) AS deno_uso, a.serial_carroceria, a.serial_motor, a.ano_adquisicion, a.valor_vehiculo, a.fecha_adquisicion, a.cod_clasificacion, ( SELECT c.denominacion
           FROM shd200_vehiculos_clasificacion c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND a.cod_clasificacion::text = c.cod_clasificacion::text) AS deno_clasificacion, ( SELECT c.monto_anual
           FROM shd200_vehiculos_clasificacion c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND a.cod_clasificacion::text = c.cod_clasificacion::text) AS monto_anual, a.frecuencia_pago, a.monto_mensual, a.pago_todo, a.suspendido, a.rif_ci_cobrador, ( SELECT d.nombre_razon
           FROM shd002_cobradores d
          WHERE a.cod_presi = d.cod_presi AND a.cod_entidad = d.cod_entidad AND a.cod_tipo_inst = d.cod_tipo_inst AND a.cod_inst = d.cod_inst AND a.cod_dep = d.cod_dep AND a.rif_ci_cobrador::text = d.rif_ci::text) AS deno_cobrador, a.ultimo_ano_facturado, a.ultimo_mes_facturado
   FROM shd200_vehiculos a;

ALTER TABLE v_shd200_vehiculos OWNER TO sisap;

-- View: v_grilla_constribuyentes

-- DROP VIEW v_grilla_constribuyentes;

CREATE OR REPLACE VIEW v_grilla_constribuyentes AS
(((( SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.numero_solicitud, ( SELECT b.rif_cedula
           FROM shd100_solicitud b
          WHERE b.cod_presi = a.cod_presi AND b.cod_entidad = a.cod_entidad AND b.cod_tipo_inst = a.cod_tipo_inst AND b.cod_inst = a.cod_inst AND b.cod_dep = a.cod_dep AND b.numero_solicitud::text = a.numero_solicitud::text) AS rif_cedula, a.frecuencia_pago, a.monto_mensual * 12::numeric AS monto, 1 AS tipo
   FROM shd100_patente a
UNION
 SELECT v_shd200_vehiculos.cod_presi, v_shd200_vehiculos.cod_entidad, v_shd200_vehiculos.cod_tipo_inst, v_shd200_vehiculos.cod_inst, v_shd200_vehiculos.cod_dep, 0::character varying(20) AS numero_solicitud, v_shd200_vehiculos.rif_cedula, v_shd200_vehiculos.frecuencia_pago, v_shd200_vehiculos.monto_anual AS monto, 2 AS tipo
   FROM v_shd200_vehiculos)
UNION
 SELECT shd300_propaganda.cod_presi, shd300_propaganda.cod_entidad, shd300_propaganda.cod_tipo_inst, shd300_propaganda.cod_inst, shd300_propaganda.cod_dep, 0::character varying(20) AS numero_solicitud, shd300_propaganda.rif_cedula, shd300_propaganda.frecuencia_pago, shd300_propaganda.monto_mensual_general * 12::numeric AS monto, 3 AS tipo
   FROM shd300_propaganda)
UNION
 SELECT shd400_propiedad.cod_presi, shd400_propiedad.cod_entidad, shd400_propiedad.cod_tipo_inst, shd400_propiedad.cod_inst, shd400_propiedad.cod_dep, 0::character varying(20) AS numero_solicitud, shd400_propiedad.rif_cedula, shd400_propiedad.frecuencia_pago, shd400_propiedad.monto_mensual * 12::numeric AS monto, 4 AS tipo
   FROM shd400_propiedad)
UNION
 SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.numero_solicitud, ( SELECT b.rif_cedula
           FROM shd600_solicitud_arrendamiento b
          WHERE b.cod_presi = a.cod_presi AND b.cod_entidad = a.cod_entidad AND b.cod_tipo_inst = a.cod_tipo_inst AND b.cod_inst = a.cod_inst AND b.cod_dep = a.cod_dep AND upper(b.numero_solicitud::text) = upper(a.numero_solicitud::text)) AS rif_cedula, a.frecuencia_pago, a.monto_mensual * 12::numeric AS monto, 5 AS tipo
   FROM shd600_aprobacion_arrendamiento a)
UNION
 SELECT shd700_credito_vivienda.cod_presi, shd700_credito_vivienda.cod_entidad, shd700_credito_vivienda.cod_tipo_inst, shd700_credito_vivienda.cod_inst, shd700_credito_vivienda.cod_dep, 0::character varying(20) AS numero_solicitud, shd700_credito_vivienda.rif_cedula, shd700_credito_vivienda.frecuencia_pago, shd700_credito_vivienda.monto_mensual * 12::numeric AS monto, 6 AS tipo
   FROM shd700_credito_vivienda;

ALTER TABLE v_grilla_constribuyentes OWNER TO sisap;




-- DROP VIEW v_relacion_coradores;


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




--DROP VIEW v_shd002_cobranza_realizada;

CREATE OR REPLACE VIEW v_shd002_cobranza_realizada AS

SELECT

  a.cod_presi,
  a.cod_entidad,
  a.cod_tipo_inst,
  a.cod_inst,
  a.cod_dep,
  a.rif_ci,
  b.personalidad,
  b.nombre_razon,
  b.fecha_ingreso,
  b.recurso_cobro,
  b.condicion_actividad,
  a.ano,
  a.cobranza_acumulada,
  a.enero,
  a.febrero,
  a.marzo,
  a.abril,
  a.mayo,
  a.junio,
  a.julio,
  a.agosto,
  a.septiembre,
  a.octubre,
  a.noviembre,
  a.diciembre

from
    shd002_cobranza_realizada a, shd002_cobradores b

where b.cod_presi       =  a.cod_presi       and
      b.cod_entidad     =  a.cod_entidad    and
      b.cod_tipo_inst   =  a.cod_tipo_inst  and
      b.cod_inst        =  a.cod_inst       and
      b.cod_dep         =  a.cod_dep        and
      b.rif_ci          =  a.rif_ci;

ALTER TABLE v_shd002_cobranza_realizada OWNER TO sisap;






--DROP VIEW v_shd002_cobranza_pendiente;

CREATE OR REPLACE VIEW v_shd002_cobranza_pendiente AS

SELECT

  a.cod_presi,
  a.cod_entidad,
  a.cod_tipo_inst,
  a.cod_inst,
  a.cod_dep,
  a.rif_ci,
  b.personalidad,
  b.nombre_razon,
  b.fecha_ingreso,
  b.recurso_cobro,
  b.condicion_actividad,
  a.ano,
  a.cobranza_pendiente_acumulada,
  a.enero,
  a.febrero,
  a.marzo,
  a.abril,
  a.mayo,
  a.junio,
  a.julio,
  a.agosto,
  a.septiembre,
  a.octubre,
  a.noviembre,
  a.diciembre

from
    shd002_cobranza_pendiente a, shd002_cobradores b

where b.cod_presi       =  a.cod_presi       and
      b.cod_entidad     =  a.cod_entidad    and
      b.cod_tipo_inst   =  a.cod_tipo_inst  and
      b.cod_inst        =  a.cod_inst       and
      b.cod_dep         =  a.cod_dep        and
      b.rif_ci          =  a.rif_ci;

ALTER TABLE v_shd002_cobranza_pendiente OWNER TO sisap;






--DROP VIEW v_shp002_cobranza_estado_cuenta_1;

CREATE OR REPLACE VIEW v_shp002_cobranza_estado_cuenta_1 AS

SELECT


  a.cod_presi,
  a.cod_entidad,
  a.cod_tipo_inst,
  a.cod_inst,
  a.cod_dep,
  a.rif_ci,
  a.personalidad,
  a.nombre_razon,
  a.fecha_ingreso,
  a.recurso_cobro,
  a.condicion_actividad,
  (b.ano) as ano_cobranza,
   b.cobranza_pendiente_acumulada,
  (0) as cobranza_realizada_acumulada


from shd002_cobradores a, shd002_cobranza_pendiente b

where b.cod_presi       =  a.cod_presi      and
      b.cod_entidad     =  a.cod_entidad    and
      b.cod_tipo_inst   =  a.cod_tipo_inst  and
      b.cod_inst        =  a.cod_inst       and
      b.cod_dep         =  a.cod_dep        and
      b.rif_ci          =  a.rif_ci


UNION



SELECT


  a.cod_presi,
  a.cod_entidad,
  a.cod_tipo_inst,
  a.cod_inst,
  a.cod_dep,
  a.rif_ci,
  a.personalidad,
  a.nombre_razon,
  a.fecha_ingreso,
  a.recurso_cobro,
  a.condicion_actividad,
  (c.ano) as ano_cobranza,
  (0) as cobranza_pendiente_acumulada,
  (c.cobranza_acumulada) as cobranza_realizada_acumulada


from shd002_cobradores a, shd002_cobranza_realizada c

where c.cod_presi       =  a.cod_presi       and
      c.cod_entidad     =  a.cod_entidad    and
      c.cod_tipo_inst   =  a.cod_tipo_inst  and
      c.cod_inst        =  a.cod_inst       and
      c.cod_dep         =  a.cod_dep        and
      c.rif_ci          =  a.rif_ci;


ALTER TABLE v_shp002_cobranza_estado_cuenta_1 OWNER TO sisap;







--DROP VIEW v_shp002_cobranza_estado_cuenta_2;

CREATE OR REPLACE VIEW v_shp002_cobranza_estado_cuenta_2 AS

SELECT

  a.cod_presi,
  a.cod_entidad,
  a.cod_tipo_inst,
  a.cod_inst,
  a.cod_dep,
  a.rif_ci,
  a.personalidad,
  a.nombre_razon,
  a.fecha_ingreso,
  a.recurso_cobro,
  a.condicion_actividad,
  a.ano_cobranza

from v_shp002_cobranza_estado_cuenta_1 a

GROUP BY  a.cod_presi,
		  a.cod_entidad,
		  a.cod_tipo_inst,
		  a.cod_inst,
		  a.cod_dep,
		  a.rif_ci,
		  a.personalidad,
		  a.nombre_razon,
		  a.fecha_ingreso,
		  a.recurso_cobro,
		  a.condicion_actividad,
		  a.ano_cobranza

ORDER BY  a.cod_presi,
		  a.cod_entidad,
		  a.cod_tipo_inst,
		  a.cod_inst,
		  a.cod_dep,
		  a.rif_ci,
		  a.ano_cobranza ASC;

ALTER TABLE v_shp002_cobranza_estado_cuenta_2 OWNER TO sisap;



-- View: v_shd100_declaracion_ingreso

-- DROP VIEW v_shd100_declaracion_ingreso;

CREATE OR REPLACE VIEW v_shd100_declaracion_ingreso AS
 SELECT x.cod_presi, x.cod_entidad, x.cod_tipo_inst, x.cod_inst, x.cod_dep, x.numero_solicitud, x.numero_patente, x.fecha_solicitud, a.rif_cedula, a.razon_social_nombres, a.cod_pais, ( SELECT b.denominacion
           FROM cugd01_republica b
          WHERE b.cod_republica = a.cod_pais) AS denominacion_pais, a.cod_estado, ( SELECT c.denominacion
           FROM cugd01_estados c
          WHERE c.cod_republica = a.cod_pais AND c.cod_estado = a.cod_estado) AS denominacion_estado, a.cod_municipio, ( SELECT d.denominacion
           FROM cugd01_municipios d
          WHERE d.cod_republica = a.cod_pais AND d.cod_estado = a.cod_estado AND d.cod_municipio = a.cod_municipio) AS denominacion_municipio, a.cod_parroquia, ( SELECT e.denominacion
           FROM cugd01_parroquias e
          WHERE e.cod_republica = a.cod_pais AND e.cod_estado = a.cod_estado AND e.cod_municipio = a.cod_municipio AND e.cod_parroquia = a.cod_parroquia) AS denominacion_parroquia, a.cod_centro_poblado, ( SELECT f.denominacion
           FROM cugd01_centros_poblados f
          WHERE f.cod_republica = a.cod_pais AND f.cod_estado = a.cod_estado AND f.cod_municipio = a.cod_municipio AND f.cod_parroquia = a.cod_parroquia AND f.cod_centro = a.cod_centro_poblado) AS denominacion_centro, a.cod_calle_avenida, ( SELECT g.denominacion
           FROM cugd01_vialidad g
          WHERE g.cod_republica = a.cod_pais AND g.cod_estado = a.cod_estado AND g.cod_municipio = a.cod_municipio AND g.cod_parroquia = a.cod_parroquia AND g.cod_centro = a.cod_centro_poblado AND g.cod_vialidad = a.cod_calle_avenida) AS denominacion_vialidad, a.cod_vereda_edificio, ( SELECT h.denominacion
           FROM cugd01_vereda h
          WHERE h.cod_republica = a.cod_pais AND h.cod_estado = a.cod_estado AND h.cod_municipio = a.cod_municipio AND h.cod_parroquia = a.cod_parroquia AND h.cod_centro = a.cod_centro_poblado AND h.cod_vialidad = a.cod_calle_avenida AND h.cod_vereda = a.cod_vereda_edificio) AS denominacion_vereda, a.numero_vivienda_local, a.telefonos_fijos, a.telefonos_celulares, a.correo_electronico
   FROM shd001_registro_contribuyentes a, shd100_solicitud x
  WHERE x.rif_cedula::text = a.rif_cedula::text;

ALTER TABLE v_shd100_declaracion_ingreso OWNER TO sisap;

-- View: v_shd950_solvencia_detalles

-- DROP VIEW v_shd950_solvencia_detalles;

CREATE OR REPLACE VIEW v_shd950_solvencia_detalles AS
 SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cod_partida, a.cod_generica, a.cod_especifica, a.cod_sub_espec, a.cod_auxiliar, a.rif_cedula, ( SELECT b.denominacion
           FROM shd003_codigo_ingresos b
          WHERE a.cod_partida = b.cod_partida AND a.cod_generica = b.cod_generica AND a.cod_especifica = b.cod_especifica AND a.cod_sub_espec = b.cod_subespec AND a.cod_auxiliar = b.cod_auxiliar) AS denominacion_ingreso, ( SELECT b.cod_ingreso
           FROM shd003_codigo_ingresos b
          WHERE a.cod_partida = b.cod_partida AND a.cod_generica = b.cod_generica AND a.cod_especifica = b.cod_especifica AND a.cod_sub_espec = b.cod_subespec AND a.cod_auxiliar = b.cod_auxiliar) AS codigo_ingreso, c.cancelado
   FROM shd900_planillas_deuda_cobro_cuerpo a, shd900_planillas_deuda_cobro_detalles c
  WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND a.cod_partida = c.cod_partida AND a.cod_generica = c.cod_generica AND a.cod_especifica = c.cod_especifica AND a.cod_sub_espec = c.cod_sub_espec AND a.cod_auxiliar = c.cod_auxiliar AND a.rif_cedula = c.rif_cedula;

ALTER TABLE v_shd950_solvencia_detalles OWNER TO sisap;

-- View: v_shd100_patente

-- DROP VIEW v_shd100_patente;

CREATE OR REPLACE VIEW v_shd100_patente AS
 SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.numero_solicitud, ( SELECT b.fecha_solicitud
           FROM shd100_solicitud b
          WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text) AS fecha_solicitud, ( SELECT b.rif_cedula
           FROM shd100_solicitud b
          WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text) AS rif_cedula, ( SELECT c.razon_social_nombres
           FROM v_shd001_registro_contribuyentes c
          WHERE ((( SELECT b.rif_cedula
                   FROM shd100_solicitud b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::text) = c.rif_cedula::text) AS deno_razon, ( SELECT c.personalidad_juridica
           FROM v_shd001_registro_contribuyentes c
          WHERE ((( SELECT b.rif_cedula
                   FROM shd100_solicitud b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::text) = c.rif_cedula::text) AS personalidad_juridica, ( SELECT c.nacionalidad
           FROM v_shd001_registro_contribuyentes c
          WHERE ((( SELECT b.rif_cedula
                   FROM shd100_solicitud b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::text) = c.rif_cedula::text) AS nacionalidad, ( SELECT c.correo_electronico
           FROM v_shd001_registro_contribuyentes c
          WHERE ((( SELECT b.rif_cedula
                   FROM shd100_solicitud b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::text) = c.rif_cedula::text) AS correo, ( SELECT c.telefonos_celulares
           FROM v_shd001_registro_contribuyentes c
          WHERE ((( SELECT b.rif_cedula
                   FROM shd100_solicitud b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::text) = c.rif_cedula::text) AS telefonos_celulares, ( SELECT c.telefonos_fijos
           FROM v_shd001_registro_contribuyentes c
          WHERE ((( SELECT b.rif_cedula
                   FROM shd100_solicitud b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::text) = c.rif_cedula::text) AS telefonos_fijos, ( SELECT c.fecha_inscripcion
           FROM v_shd001_registro_contribuyentes c
          WHERE ((( SELECT b.rif_cedula
                   FROM shd100_solicitud b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::text) = c.rif_cedula::text) AS fecha_inscripcion, ( SELECT c.cod_pais
           FROM v_shd001_registro_contribuyentes c
          WHERE ((( SELECT b.rif_cedula
                   FROM shd100_solicitud b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::text) = c.rif_cedula::text) AS cod_pais, ( SELECT c.deno_pais
           FROM v_shd001_registro_contribuyentes c
          WHERE ((( SELECT b.rif_cedula
                   FROM shd100_solicitud b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::text) = c.rif_cedula::text) AS deno_pais, ( SELECT c.cod_estado
           FROM v_shd001_registro_contribuyentes c
          WHERE ((( SELECT b.rif_cedula
                   FROM shd100_solicitud b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::text) = c.rif_cedula::text) AS cod_estado, ( SELECT c.deno_estado
           FROM v_shd001_registro_contribuyentes c
          WHERE ((( SELECT b.rif_cedula
                   FROM shd100_solicitud b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::text) = c.rif_cedula::text) AS deno_estado, ( SELECT c.cod_municipio
           FROM v_shd001_registro_contribuyentes c
          WHERE ((( SELECT b.rif_cedula
                   FROM shd100_solicitud b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::text) = c.rif_cedula::text) AS cod_municipio, ( SELECT c.deno_municipio
           FROM v_shd001_registro_contribuyentes c
          WHERE ((( SELECT b.rif_cedula
                   FROM shd100_solicitud b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::text) = c.rif_cedula::text) AS deno_municipio, ( SELECT c.cod_parroquia
           FROM v_shd001_registro_contribuyentes c
          WHERE ((( SELECT b.rif_cedula
                   FROM shd100_solicitud b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::text) = c.rif_cedula::text) AS cod_parroquia, ( SELECT c.deno_parroquia
           FROM v_shd001_registro_contribuyentes c
          WHERE ((( SELECT b.rif_cedula
                   FROM shd100_solicitud b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::text) = c.rif_cedula::text) AS deno_parroquia, ( SELECT c.cod_centro_poblado
           FROM v_shd001_registro_contribuyentes c
          WHERE ((( SELECT b.rif_cedula
                   FROM shd100_solicitud b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::text) = c.rif_cedula::text) AS cod_centro_poblado, ( SELECT c.deno_centro
           FROM v_shd001_registro_contribuyentes c
          WHERE ((( SELECT b.rif_cedula
                   FROM shd100_solicitud b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::text) = c.rif_cedula::text) AS deno_centro_poblado, ( SELECT c.cod_calle_avenida
           FROM v_shd001_registro_contribuyentes c
          WHERE ((( SELECT b.rif_cedula
                   FROM shd100_solicitud b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::text) = c.rif_cedula::text) AS cod_calle, ( SELECT c.deno_vialidad
           FROM v_shd001_registro_contribuyentes c
          WHERE ((( SELECT b.rif_cedula
                   FROM shd100_solicitud b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::text) = c.rif_cedula::text) AS deno_calle, ( SELECT c.cod_vereda_edificio
           FROM v_shd001_registro_contribuyentes c
          WHERE ((( SELECT b.rif_cedula
                   FROM shd100_solicitud b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::text) = c.rif_cedula::text) AS cod_vereda, ( SELECT c.deno_vereda
           FROM v_shd001_registro_contribuyentes c
          WHERE ((( SELECT b.rif_cedula
                   FROM shd100_solicitud b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::text) = c.rif_cedula::text) AS deno_vereda, ( SELECT c.profesion
           FROM v_shd001_registro_contribuyentes c
          WHERE ((( SELECT b.rif_cedula
                   FROM shd100_solicitud b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::text) = c.rif_cedula::text) AS cod_profesion, ( SELECT c.deno_profesion
           FROM v_shd001_registro_contribuyentes c
          WHERE ((( SELECT b.rif_cedula
                   FROM shd100_solicitud b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::text) = c.rif_cedula::text) AS deno_profesion, ( SELECT c.numero_vivienda_local
           FROM v_shd001_registro_contribuyentes c
          WHERE ((( SELECT b.rif_cedula
                   FROM shd100_solicitud b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::text) = c.rif_cedula::text) AS numero_casa, ( SELECT c.estado_civil
           FROM v_shd001_registro_contribuyentes c
          WHERE ((( SELECT b.rif_cedula
                   FROM shd100_solicitud b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::text) = c.rif_cedula::text) AS estado_civil, a.numero_patente, a.frecuencia_pago, a.monto_mensual, a.pago_todo, a.suspendido, a.rif_ci_cobrador, ( SELECT d.nombre_razon
           FROM shd002_cobradores d
          WHERE a.cod_presi = d.cod_presi AND a.cod_entidad = d.cod_entidad AND a.cod_tipo_inst = d.cod_tipo_inst AND a.cod_inst = d.cod_inst AND a.cod_dep = d.cod_dep AND a.rif_ci_cobrador::text = d.rif_ci::text) AS deno_cobrador, a.ultimo_ano_facturado, a.ultimo_mes_facturado, a.fecha_ultima_decla, a.ingresos_declarados, a.ultimo_ejercicio_decla, a.periodo_desde, a.periodo_hasta, a.fecha_patente
   FROM shd100_patente a;

ALTER TABLE v_shd100_patente OWNER TO sisap;

-- View: v_shd001_contribuyentes_e_impuestos

-- DROP VIEW v_shd001_contribuyentes_e_impuestos;

CREATE OR REPLACE VIEW v_shd001_contribuyentes_e_impuestos AS
((((( SELECT a.rif_cedula, a.personalidad_juridica, a.razon_social_nombres, a.fecha_inscripcion, b.cod_presi, b.cod_entidad, b.cod_tipo_inst, b.cod_inst, b.cod_dep, b.monto_mensual, 1::character varying AS pertenece_tabla, ( SELECT s.denominacion
           FROM shd003_codigo_ingresos s
          WHERE s.cod_ingreso = 1) AS concepto_impuesto
   FROM shd001_registro_contribuyentes a, shd100_patente b, shd100_solicitud c
  WHERE b.cod_presi = c.cod_presi AND b.cod_entidad = c.cod_entidad AND b.cod_tipo_inst = c.cod_tipo_inst AND b.cod_inst = c.cod_inst AND b.cod_dep = c.cod_dep AND b.numero_solicitud::text = c.numero_solicitud::text AND b.numero_patente::text = c.numero_patente::text AND a.rif_cedula::text = c.rif_cedula::text
UNION
 SELECT a.rif_cedula, a.personalidad_juridica, a.razon_social_nombres, a.fecha_inscripcion, b.cod_presi, b.cod_entidad, b.cod_tipo_inst, b.cod_inst, b.cod_dep, b.monto_mensual, 2::character varying AS pertenece_tabla, ( SELECT s.denominacion
           FROM shd003_codigo_ingresos s
          WHERE s.cod_ingreso = 2) AS concepto_impuesto
   FROM shd001_registro_contribuyentes a, shd200_vehiculos b
  WHERE a.rif_cedula::text = b.rif_cedula::text)
UNION
 SELECT a.rif_cedula, a.personalidad_juridica, a.razon_social_nombres, a.fecha_inscripcion, b.cod_presi, b.cod_entidad, b.cod_tipo_inst, b.cod_inst, b.cod_dep, b.monto_mensual_general AS monto_mensual, 3::character varying AS pertenece_tabla, ( SELECT s.denominacion
           FROM shd003_codigo_ingresos s
          WHERE s.cod_ingreso = 3) AS concepto_impuesto
   FROM shd001_registro_contribuyentes a, shd300_propaganda b
  WHERE a.rif_cedula::text = b.rif_cedula::text)
UNION
 SELECT a.rif_cedula, a.personalidad_juridica, a.razon_social_nombres, a.fecha_inscripcion, b.cod_presi, b.cod_entidad, b.cod_tipo_inst, b.cod_inst, b.cod_dep, b.monto_mensual, 4::character varying AS pertenece_tabla, ( SELECT s.denominacion
           FROM shd003_codigo_ingresos s
          WHERE s.cod_ingreso = 4) AS concepto_impuesto
   FROM shd001_registro_contribuyentes a, shd400_propiedad b
  WHERE a.rif_cedula::text = b.rif_cedula::text)
UNION
 SELECT a.rif_cedula, a.personalidad_juridica, a.razon_social_nombres, a.fecha_inscripcion, b.cod_presi, b.cod_entidad, b.cod_tipo_inst, b.cod_inst, b.cod_dep, b.monto_mensual, 5::character varying AS pertenece_tabla, ( SELECT s.denominacion
           FROM shd003_codigo_ingresos s
          WHERE s.cod_ingreso = 5) AS concepto_impuesto
   FROM shd001_registro_contribuyentes a, shd500_aseo_domiciliario b
  WHERE a.rif_cedula::text = b.rif_cedula::text)
UNION
 SELECT a.rif_cedula, a.personalidad_juridica, a.razon_social_nombres, a.fecha_inscripcion, b.cod_presi, b.cod_entidad, b.cod_tipo_inst, b.cod_inst, b.cod_dep, b.monto_mensual, 6::character varying AS pertenece_tabla, ( SELECT s.denominacion
           FROM shd003_codigo_ingresos s
          WHERE s.cod_ingreso = 6) AS concepto_impuesto
   FROM shd001_registro_contribuyentes a, shd600_aprobacion_arrendamiento b, shd600_solicitud_arrendamiento c
  WHERE b.cod_presi = c.cod_presi AND b.cod_entidad = c.cod_entidad AND b.cod_tipo_inst = c.cod_tipo_inst AND b.cod_inst = c.cod_inst AND b.cod_dep = c.cod_dep AND b.numero_solicitud::text = c.numero_solicitud::text AND a.rif_cedula::text = c.rif_cedula::text)
UNION
 SELECT a.rif_cedula, a.personalidad_juridica, a.razon_social_nombres, a.fecha_inscripcion, b.cod_presi, b.cod_entidad, b.cod_tipo_inst, b.cod_inst, b.cod_dep, b.monto_mensual, 7::character varying AS pertenece_tabla, ( SELECT s.denominacion
           FROM shd003_codigo_ingresos s
          WHERE s.cod_ingreso = 7) AS concepto_impuesto
   FROM shd001_registro_contribuyentes a, shd700_credito_vivienda b
  WHERE a.rif_cedula::text = b.rif_cedula::text;

ALTER TABLE v_shd001_contribuyentes_e_impuestos OWNER TO sisap;



--DROP VIEW v_consulta_ingreso;
-- View: v_consulta_ingreso
DROP VIEW v_consulta_ingreso CASCADE;

CREATE OR REPLACE VIEW v_consulta_ingreso AS
(( SELECT (a.cod_grupo::text || mascara_2(a.cod_partida))::integer AS cod_partida_completo, a.cod_grupo, a.cod_partida, a.cod_generica, a.cod_especifica, a.cod_sub_espec, a.cod_auxiliar, quitar_acentos(a.descripcion) AS denominacion, quitar_acentos(a.concepto) AS concepto, ((((((((a.cod_grupo::text || mascara_2(a.cod_partida)) || mascara_2(a.cod_generica)) || mascara_2(a.cod_especifica)) || mascara_2(a.cod_sub_espec)) || mascara_2(a.cod_auxiliar)) || ', '::text) || quitar_acentos(a.descripcion)) || ' '::text) || quitar_acentos(a.concepto) AS denominacion_busqueda
   FROM cfpd01_auxiliar a
  WHERE a.cod_grupo = 3
  GROUP BY a.cod_grupo, a.cod_partida, a.cod_generica, a.cod_especifica, a.cod_sub_espec, a.cod_auxiliar, a.descripcion, a.concepto
  ORDER BY a.cod_grupo, a.cod_partida, a.cod_generica, a.cod_especifica, a.cod_sub_espec, a.cod_auxiliar)
UNION
( SELECT (b.cod_grupo::text || mascara_2(b.cod_partida))::integer AS cod_partida_completo, b.cod_grupo, b.cod_partida, b.cod_generica, b.cod_especifica, b.cod_sub_espec, 0 AS cod_auxiliar, quitar_acentos(b.descripcion) AS denominacion, quitar_acentos(b.concepto) AS concepto, ((((((((b.cod_grupo || mascara_2(b.cod_partida)) || mascara_2(b.cod_generica)) || mascara_2(b.cod_especifica)) || mascara_2(b.cod_sub_espec)) || mascara_2(0)) || ', '::text) || quitar_acentos(b.descripcion)) || ', '::text) || quitar_acentos(b.concepto) AS denominacion_busqueda
   FROM cfpd01_sub_espec b
  WHERE b.cod_grupo = 3
  GROUP BY b.cod_grupo, b.cod_partida, b.cod_generica, b.cod_especifica, b.cod_sub_espec, b.descripcion, b.concepto
  ORDER BY b.cod_grupo, b.cod_partida, b.cod_generica, b.cod_especifica, b.cod_sub_espec))
UNION
( SELECT (c.cod_grupo::text || mascara_2(c.cod_partida))::integer AS cod_partida_completo, c.cod_grupo, c.cod_partida, c.cod_generica, c.cod_especifica, 0 AS cod_sub_espec, 0 AS cod_auxiliar, quitar_acentos(c.descripcion) AS denominacion, quitar_acentos(c.concepto) AS concepto, (((((((c.cod_grupo || mascara_2(c.cod_partida)) || mascara_2(c.cod_generica)) || mascara_2(c.cod_especifica)) || '0000'::text) || ', '::text) || quitar_acentos(c.descripcion)) || ', '::text) || quitar_acentos(c.concepto) AS denominacion_busqueda
   FROM cfpd01_especifica c
  WHERE c.cod_grupo = 3
  GROUP BY c.cod_grupo, c.cod_partida, c.cod_generica, c.cod_especifica, c.descripcion, c.concepto
  ORDER BY c.cod_grupo, c.cod_partida, c.cod_generica, c.cod_especifica);

ALTER TABLE v_consulta_ingreso OWNER TO sisap;

-- View: v_shd901_ingresos_cobro

-- DROP VIEW v_shd901_ingresos_cobro;

-- View: v_shd901_ingresos_cobro

CREATE OR REPLACE VIEW v_shd900_cobranza_diaria AS
 SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.ano_comprobante, a.numero_comprobante, a.cod_partida, a.cod_generica, a.cod_especifica, a.cod_sub_espec, a.cod_auxiliar, a.fecha_comprobante, a.rif_cedula, a.concepto_comprobante, a.deuda_vigente, a.deuda_anterior, a.monto_recargo, a.monto_multa, a.monto_intereses, a.monto_descuento, a.cod_entidad_deposito, a.cod_sucursal_deposito, a.cuenta_bancaria_deposito, a.numero_deposito, a.monto_deposito, a.fecha_deposito, a.cod_entidad_credito, a.cod_sucursal_credito, a.cuenta_bancaria_credito, a.numero_nota_credito, a.monto_nota_credito, a.fecha_nota_credito, a.cod_entidad_cheque, a.cod_sucursal_cheque, a.cuenta_bancaria_cheque, a.numero_cheque, a.monto_cheque, a.fecha_cheque, a.monto_efectivo, a.condicion_documento, a.fecha_registro, a.username_registro, a.ano_anulacion, a.numero_anulacion, a.fecha_anulacion, a.username_anulacion,a.rif_ci_cobrador, ( SELECT b.denominacion
           FROM cstd01_entidades_bancarias b
          WHERE b.cod_entidad_bancaria = a.cod_entidad_deposito) AS banco_deposito, ( SELECT b.denominacion
           FROM cstd01_entidades_bancarias b
          WHERE b.cod_entidad_bancaria = a.cod_entidad_credito) AS banco_nota_credito, ( SELECT b.denominacion
           FROM cstd01_entidades_bancarias b
          WHERE b.cod_entidad_bancaria = a.cod_entidad_cheque) AS banco_cheque, ( SELECT b.denominacion
           FROM cstd01_sucursales_bancarias b
          WHERE b.cod_entidad_bancaria = a.cod_entidad_deposito AND b.cod_sucursal = a.cod_sucursal_deposito) AS sucursal_deposito, ( SELECT b.denominacion
           FROM cstd01_sucursales_bancarias b
          WHERE b.cod_entidad_bancaria = a.cod_entidad_credito AND b.cod_sucursal = a.cod_sucursal_credito) AS sucursal_nota_credito, ( SELECT b.denominacion
           FROM cstd01_sucursales_bancarias b
          WHERE b.cod_entidad_bancaria = a.cod_entidad_cheque AND b.cod_sucursal = a.cod_sucursal_cheque) AS sucursal_cheque, ( SELECT b.denominacion
           FROM v_consulta_ingreso b
          WHERE b.cod_partida_completo = a.cod_partida AND b.cod_generica = a.cod_generica AND b.cod_especifica = a.cod_especifica AND b.cod_sub_espec = a.cod_sub_espec AND b.cod_auxiliar = a.cod_auxiliar limit 1) AS denominacion_ingreso
   FROM shd900_cobranza_diaria a;

ALTER TABLE v_shd900_cobranza_diaria OWNER TO sisap;

CREATE OR REPLACE VIEW v_cobranza_diaria AS
 SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.ano_comprobante, a.numero_comprobante, a.cod_partida, a.cod_generica, a.cod_especifica, a.cod_sub_espec, a.cod_auxiliar, a.fecha_comprobante, a.rif_cedula, a.concepto_comprobante, a.deuda_vigente, a.deuda_anterior, a.monto_recargo, a.monto_multa, a.monto_intereses, a.monto_descuento, a.cod_entidad_deposito, a.cod_sucursal_deposito, a.cuenta_bancaria_deposito, a.numero_deposito, a.monto_deposito, a.fecha_deposito, a.cod_entidad_credito, a.cod_sucursal_credito, a.cuenta_bancaria_credito, a.numero_nota_credito, a.monto_nota_credito, a.fecha_nota_credito, a.cod_entidad_cheque, a.cod_sucursal_cheque, a.cuenta_bancaria_cheque, a.numero_cheque, a.monto_cheque, a.fecha_cheque, a.monto_efectivo, a.condicion_documento, a.fecha_registro, a.username_registro, a.ano_anulacion, a.numero_anulacion, a.fecha_anulacion, a.username_anulacion,a.rif_ci_cobrador, a.banco_deposito, a.banco_nota_credito, a.banco_cheque, a.sucursal_deposito, a.sucursal_nota_credito, a.sucursal_cheque, a.denominacion_ingreso, b.personalidad_juridica, b.razon_social_nombres, b.fecha_inscripcion, b.nacionalidad, b.estado_civil, b.profesion, b.deno_profesion, b.cod_pais, b.deno_pais, b.cod_estado, b.deno_estado, b.cod_municipio, b.deno_municipio, b.cod_parroquia, b.deno_parroquia, b.cod_centro_poblado, b.deno_centro, b.cod_calle_avenida, b.deno_vialidad, b.cod_vereda_edificio, b.deno_vereda, b.numero_vivienda_local, b.telefonos_fijos, b.telefonos_celulares, b.correo_electronico, (((((a.numero_comprobante::text || ' '::text) || a.rif_cedula::text) || ' '::text) || quitar_acentos(b.razon_social_nombres::text)) || ' '::text) || quitar_acentos(a.denominacion_ingreso) AS denominacion_busqueda
   FROM v_shd900_cobranza_diaria a, v_shd001_registro_contribuyentes b
  WHERE b.rif_cedula::text = a.rif_cedula::text;

ALTER TABLE v_cobranza_diaria OWNER TO sisap;

CREATE OR REPLACE VIEW v_shd900_cobranza_acumulada AS
 SELECT b.cod_presi, b.cod_entidad, b.cod_tipo_inst, b.cod_inst, b.cod_dep, b.ano, b.mes, b.dia, b.cod_partida, b.cod_generica, b.cod_especifica, b.cod_sub_espec, b.cod_auxiliar, b.deuda_vigente, b.deuda_anterior, b.monto_recargo, b.monto_multa, b.monto_intereses, b.monto_descuento, b.cantidad_depositos, b.monto_depositos, b.cantidad_notas_credito, b.monto_notas_credito, b.cantidad_cheques, b.monto_cheques, b.cantidad_descuento, b.cantidad_pagos_efectivo, b.monto_pagos_efectivo, ( SELECT a.denominacion
           FROM v_consulta_ingreso a
          WHERE a.cod_partida_completo = b.cod_partida AND a.cod_generica = b.cod_generica AND a.cod_especifica = b.cod_especifica AND a.cod_sub_espec = b.cod_sub_espec AND a.cod_auxiliar = b.cod_auxiliar
          ORDER BY a.denominacion DESC
         LIMIT 1) AS denominacion_ingreso
   FROM shd900_cobranza_acumulada b;

ALTER TABLE v_shd900_cobranza_acumulada OWNER TO sisap;

CREATE OR REPLACE VIEW v_shd900_cobranza_acumulada_ano AS
 SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.ano, a.cod_partida, a.cod_generica, a.cod_especifica, a.cod_sub_espec, a.cod_auxiliar, sum(a.deuda_vigente) AS deuda_vigente, sum(a.deuda_anterior) AS deuda_anterior, sum(a.monto_recargo) AS monto_recargo, sum(a.monto_multa) AS monto_multa, sum(a.monto_intereses) AS monto_intereses, sum(a.monto_descuento) AS monto_descuento, sum(a.cantidad_depositos) AS cantidad_depositos, sum(a.monto_depositos) AS monto_depositos, sum(a.cantidad_notas_credito) AS cantidad_notas_credito, sum(a.monto_notas_credito) AS monto_notas_credito, sum(a.cantidad_cheques) AS cantidad_cheques, sum(a.monto_cheques) AS monto_cheques, sum(a.cantidad_descuento) AS cantidad_descuento, sum(a.cantidad_pagos_efectivo) AS cantidad_pagos_efectivo, sum(a.monto_pagos_efectivo) AS monto_pagos_efectivo, a.denominacion_ingreso
   FROM v_shd900_cobranza_acumulada a
  GROUP BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.ano, a.cod_partida, a.cod_generica, a.cod_especifica, a.cod_sub_espec, a.cod_auxiliar, a.denominacion_ingreso;

ALTER TABLE v_shd900_cobranza_acumulada_ano OWNER TO sisap;

CREATE OR REPLACE VIEW v_shd900_cobranza_acumulada_ano_mes AS
 SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.ano, a.mes, a.cod_partida, a.cod_generica, a.cod_especifica, a.cod_sub_espec, a.cod_auxiliar, sum(a.deuda_vigente) AS deuda_vigente, sum(a.deuda_anterior) AS deuda_anterior, sum(a.monto_recargo) AS monto_recargo, sum(a.monto_multa) AS monto_multa, sum(a.monto_intereses) AS monto_intereses, sum(a.monto_descuento) AS monto_descuento, sum(a.cantidad_depositos) AS cantidad_depositos, sum(a.monto_depositos) AS monto_depositos, sum(a.cantidad_notas_credito) AS cantidad_notas_credito, sum(a.monto_notas_credito) AS monto_notas_credito, sum(a.cantidad_cheques) AS cantidad_cheques, sum(a.monto_cheques) AS monto_cheques, sum(a.cantidad_descuento) AS cantidad_descuento, sum(a.cantidad_pagos_efectivo) AS cantidad_pagos_efectivo, sum(a.monto_pagos_efectivo) AS monto_pagos_efectivo, a.denominacion_ingreso
   FROM v_shd900_cobranza_acumulada a
  GROUP BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.ano, a.mes, a.cod_partida, a.cod_generica, a.cod_especifica, a.cod_sub_espec, a.cod_auxiliar, a.denominacion_ingreso;

ALTER TABLE v_shd900_cobranza_acumulada_ano_mes OWNER TO sisap;

CREATE OR REPLACE VIEW v_shd900_cobranza_acumulada_ano_mes_dia AS
 SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.ano, a.mes, a.dia, a.cod_partida, a.cod_generica, a.cod_especifica, a.cod_sub_espec, a.cod_auxiliar, sum(a.deuda_vigente) AS deuda_vigente, sum(a.deuda_anterior) AS deuda_anterior, sum(a.monto_recargo) AS monto_recargo, sum(a.monto_multa) AS monto_multa, sum(a.monto_intereses) AS monto_intereses, sum(a.monto_descuento) AS monto_descuento, sum(a.cantidad_depositos) AS cantidad_depositos, sum(a.monto_depositos) AS monto_depositos, sum(a.cantidad_notas_credito) AS cantidad_notas_credito, sum(a.monto_notas_credito) AS monto_notas_credito, sum(a.cantidad_cheques) AS cantidad_cheques, sum(a.monto_cheques) AS monto_cheques, sum(a.cantidad_descuento) AS cantidad_descuento, sum(a.cantidad_pagos_efectivo) AS cantidad_pagos_efectivo, sum(a.monto_pagos_efectivo) AS monto_pagos_efectivo, a.denominacion_ingreso
   FROM v_shd900_cobranza_acumulada a
  GROUP BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.ano, a.mes, a.dia, a.cod_partida, a.cod_generica, a.cod_especifica, a.cod_sub_espec, a.cod_auxiliar, a.denominacion_ingreso;

ALTER TABLE v_shd900_cobranza_acumulada_ano_mes_dia OWNER TO sisap;


-- View: v_shd100_solicitud_actividades

-- DROP VIEW v_shd100_solicitud_actividades;

CREATE OR REPLACE VIEW v_shd100_solicitud_actividades AS
 SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.numero_solicitud, a.cod_actividad, b.denominacion_actividad, b.alicuota, b.unidades_tributarias, b.minimo_tributable
   FROM shd100_solicitud_actividades a, shd100_actividades b
  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.cod_actividad::text = b.cod_actividad::text;

ALTER TABLE v_shd100_solicitud_actividades OWNER TO sisap;

-- View: v_shd100_solicitud

-- DROP VIEW v_shd100_solicitud;

CREATE OR REPLACE VIEW v_shd100_solicitud AS
 SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.numero_solicitud, a.fecha_solicitud, a.rif_cedula, b.razon_social_nombres, a.numero_ficha_catastral, a.capital, a.horario_trab_desde, a.horario_trab_hasta, a.tipo_establecimiento, a.tipo_local, a.nacionalidad AS nacionalidad_repre, a.cedula_identidad, a.nombres_apellidos, a.cod_pais AS pais_repre, ( SELECT c.denominacion
           FROM cugd01_republica c
          WHERE a.cod_pais = c.cod_republica) AS deno_pais_repre, a.cod_estado AS estado_repre, ( SELECT d.denominacion
           FROM cugd01_estados d
          WHERE a.cod_pais = d.cod_republica AND a.cod_estado = d.cod_estado) AS deno_estado_repre, a.cod_municipio AS municipio_repre, ( SELECT e.denominacion
           FROM cugd01_municipios e
          WHERE a.cod_pais = e.cod_republica AND a.cod_estado = e.cod_estado AND a.cod_municipio = e.cod_municipio) AS deno_municipio_repre, a.cod_parroquia AS parroquia_repre, ( SELECT f.denominacion
           FROM cugd01_parroquias f
          WHERE a.cod_pais = f.cod_republica AND a.cod_estado = f.cod_estado AND a.cod_municipio = f.cod_municipio AND a.cod_parroquia = f.cod_parroquia) AS deno_parroquia_repre, a.cod_centro AS centro_repre, ( SELECT g.denominacion
           FROM cugd01_centros_poblados g
          WHERE a.cod_pais = g.cod_republica AND a.cod_estado = g.cod_estado AND a.cod_municipio = g.cod_municipio AND a.cod_parroquia = g.cod_parroquia AND a.cod_centro = g.cod_centro) AS deno_centro_repre, a.cod_vialidad AS vialidad_repre, ( SELECT h.denominacion
           FROM cugd01_vialidad h
          WHERE a.cod_pais = h.cod_republica AND a.cod_estado = h.cod_estado AND a.cod_municipio = h.cod_municipio AND a.cod_parroquia = h.cod_parroquia AND a.cod_centro = h.cod_centro AND a.cod_vialidad = h.cod_vialidad) AS deno_vialidad_repre, a.cod_vereda AS vereda_repre, ( SELECT i.denominacion
           FROM cugd01_vereda i
          WHERE a.cod_pais = i.cod_republica AND a.cod_estado = i.cod_estado AND a.cod_municipio = i.cod_municipio AND a.cod_parroquia = i.cod_parroquia AND a.cod_centro = i.cod_centro AND a.cod_vialidad = i.cod_vialidad AND a.cod_vereda = i.cod_vereda) AS deno_vereda_repre, a.numero_casa_local AS numero_local_repre, a.telefonos_fijos AS telefonos_fijos_repre, a.telefonos_celulares AS telefonos_celulares_repre, a.correo_electronico AS correo_electronico_repre, a.fecha_inicio_const, a.fecha_cierre_const, a.fecha_inicio_econo, a.fecha_cierre_economico, a.registro_mercantil, a.tiene_sucursal, a.es_fabricante, a.numero_empleado, a.numero_obreros, a.distancia_bar, a.distancia_hospital, a.distancia_educativo, a.distancia_funeraria, a.distancia_estacion, a.distancia_gubernam, a.tilde_reg_mercantil, a.tilde_fotoco_ci, a.tilde_acta_const, a.tilde_uso_conforme, a.tilde_croquis, a.tilde_bomberos, a.tilde_rif, a.tilde_solvencia, a.tilde_concejo, a.tilde_recibo, a.tilde_planilla, a.tilde_permiso, a.numero_patente, b.cod_pais AS pais_razon, ( SELECT c.denominacion
           FROM cugd01_republica c
          WHERE b.cod_pais = c.cod_republica) AS deno_pais_razon, b.cod_estado AS estado_razon, ( SELECT d.denominacion
           FROM cugd01_estados d
          WHERE b.cod_pais = d.cod_republica AND b.cod_estado = d.cod_estado) AS deno_estado_razon, b.cod_municipio AS municipio_razon, ( SELECT e.denominacion
           FROM cugd01_municipios e
          WHERE b.cod_pais = e.cod_republica AND b.cod_estado = e.cod_estado AND b.cod_municipio = e.cod_municipio) AS deno_municipio_razon, b.cod_parroquia AS parroquia_razon, ( SELECT f.denominacion
           FROM cugd01_parroquias f
          WHERE b.cod_pais = f.cod_republica AND b.cod_estado = f.cod_estado AND b.cod_municipio = f.cod_municipio AND b.cod_parroquia = f.cod_parroquia) AS deno_parroquia_razon, b.cod_centro_poblado AS centro_razon, ( SELECT g.denominacion
           FROM cugd01_centros_poblados g
          WHERE b.cod_pais = g.cod_republica AND b.cod_estado = g.cod_estado AND b.cod_municipio = g.cod_municipio AND b.cod_parroquia = g.cod_parroquia AND b.cod_centro_poblado = g.cod_centro) AS deno_centro_razon, b.cod_calle_avenida AS calle_razon, ( SELECT h.denominacion
           FROM cugd01_vialidad h
          WHERE b.cod_pais = h.cod_republica AND b.cod_estado = h.cod_estado AND b.cod_municipio = h.cod_municipio AND b.cod_parroquia = h.cod_parroquia AND b.cod_centro_poblado = h.cod_centro AND b.cod_calle_avenida = h.cod_vialidad) AS deno_vialidad_razon, b.cod_vereda_edificio AS vereda_razon, ( SELECT i.denominacion
           FROM cugd01_vereda i
          WHERE b.cod_pais = i.cod_republica AND b.cod_estado = i.cod_estado AND b.cod_municipio = i.cod_municipio AND b.cod_parroquia = i.cod_parroquia AND b.cod_centro_poblado = i.cod_centro AND b.cod_calle_avenida = i.cod_vialidad AND b.cod_vereda_edificio = i.cod_vereda) AS deno_vereda_razon, b.fecha_inscripcion, b.telefonos_fijos AS telefonos_fijos_razon, b.telefonos_celulares AS telefonos_celulares_razon, b.correo_electronico AS correo_electronico_razon, b.nacionalidad AS nacionalidad_razon, b.estado_civil, b.numero_vivienda_local AS numero_local_razon, b.profesion, ( SELECT j.denominacion
           FROM cnmd06_profesiones j
          WHERE b.profesion = j.cod_profesion) AS deno_profesion, a.categoria_comercial, a.mercado_cubre, ( SELECT x.fecha_patente
           FROM shd100_patente x
          WHERE a.numero_patente::text = x.numero_patente::text AND a.cod_presi = x.cod_presi AND a.cod_entidad = x.cod_entidad AND a.cod_tipo_inst = x.cod_tipo_inst AND a.cod_dep = x.cod_dep) AS fecha_patente, ( SELECT x.frecuencia_pago
           FROM shd100_patente x
          WHERE a.numero_patente::text = x.numero_patente::text AND a.cod_presi = x.cod_presi AND a.cod_entidad = x.cod_entidad AND a.cod_tipo_inst = x.cod_tipo_inst AND a.cod_dep = x.cod_dep) AS frecuencia_pago
   FROM shd100_solicitud a, shd001_registro_contribuyentes b
  WHERE a.rif_cedula::text = b.rif_cedula::text;

ALTER TABLE v_shd100_solicitud OWNER TO sisap;

-- View: v_shd100_patente_actividades

-- DROP VIEW v_shd100_patente_actividades;

CREATE OR REPLACE VIEW v_shd100_patente_actividades AS
 SELECT
	a.cod_presi,
	a.cod_entidad,
	a.cod_tipo_inst,
	a.cod_inst,
	a.cod_dep,
	a.cod_actividad,
	( SELECT b.denominacion_actividad
           FROM shd100_actividades b
          WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.cod_actividad::text = b.cod_actividad::text) AS deno_actividad,
           a.numero_aforos, a.monto_aforo_anual, a.total_aforo_anual
   FROM shd100_patente_actividades a;

ALTER TABLE v_shd100_patente_actividades OWNER TO sisap;


-- View: v_shd100_solicitud

-- DROP VIEW v_shd100_solicitud;

CREATE OR REPLACE VIEW v_shd100_solicitud AS
 SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.numero_solicitud, a.fecha_solicitud, a.rif_cedula, b.razon_social_nombres, a.numero_ficha_catastral, a.capital, a.horario_trab_desde, a.horario_trab_hasta, a.tipo_establecimiento, a.tipo_local, a.nacionalidad AS nacionalidad_repre, a.cedula_identidad, a.nombres_apellidos, a.cod_pais AS pais_repre, ( SELECT c.denominacion
           FROM cugd01_republica c
          WHERE a.cod_pais = c.cod_republica) AS deno_pais_repre, a.cod_estado AS estado_repre, ( SELECT d.denominacion
           FROM cugd01_estados d
          WHERE a.cod_pais = d.cod_republica AND a.cod_estado = d.cod_estado) AS deno_estado_repre, a.cod_municipio AS municipio_repre, ( SELECT e.denominacion
           FROM cugd01_municipios e
          WHERE a.cod_pais = e.cod_republica AND a.cod_estado = e.cod_estado AND a.cod_municipio = e.cod_municipio) AS deno_municipio_repre, a.cod_parroquia AS parroquia_repre, ( SELECT f.denominacion
           FROM cugd01_parroquias f
          WHERE a.cod_pais = f.cod_republica AND a.cod_estado = f.cod_estado AND a.cod_municipio = f.cod_municipio AND a.cod_parroquia = f.cod_parroquia) AS deno_parroquia_repre, a.cod_centro AS centro_repre, ( SELECT g.denominacion
           FROM cugd01_centros_poblados g
          WHERE a.cod_pais = g.cod_republica AND a.cod_estado = g.cod_estado AND a.cod_municipio = g.cod_municipio AND a.cod_parroquia = g.cod_parroquia AND a.cod_centro = g.cod_centro) AS deno_centro_repre, a.cod_vialidad AS vialidad_repre, ( SELECT h.denominacion
           FROM cugd01_vialidad h
          WHERE a.cod_pais = h.cod_republica AND a.cod_estado = h.cod_estado AND a.cod_municipio = h.cod_municipio AND a.cod_parroquia = h.cod_parroquia AND a.cod_centro = h.cod_centro AND a.cod_vialidad = h.cod_vialidad) AS deno_vialidad_repre, a.cod_vereda AS vereda_repre, ( SELECT i.denominacion
           FROM cugd01_vereda i
          WHERE a.cod_pais = i.cod_republica AND a.cod_estado = i.cod_estado AND a.cod_municipio = i.cod_municipio AND a.cod_parroquia = i.cod_parroquia AND a.cod_centro = i.cod_centro AND a.cod_vialidad = i.cod_vialidad AND a.cod_vereda = i.cod_vereda) AS deno_vereda_repre, a.numero_casa_local AS numero_local_repre, a.telefonos_fijos AS telefonos_fijos_repre, a.telefonos_celulares AS telefonos_celulares_repre, a.correo_electronico AS correo_electronico_repre, a.fecha_inicio_const, a.fecha_cierre_const, a.fecha_inicio_econo, a.fecha_cierre_economico, a.registro_mercantil, a.tiene_sucursal, a.es_fabricante, a.numero_empleado, a.numero_obreros, a.distancia_bar, a.distancia_hospital, a.distancia_educativo, a.distancia_funeraria, a.distancia_estacion, a.distancia_gubernam, a.tilde_reg_mercantil, a.tilde_fotoco_ci, a.tilde_acta_const, a.tilde_uso_conforme, a.tilde_croquis, a.tilde_bomberos, a.tilde_rif, a.tilde_solvencia, a.tilde_concejo, a.tilde_recibo, a.tilde_planilla, a.tilde_permiso, a.numero_patente, b.cod_pais AS pais_razon, ( SELECT c.denominacion
           FROM cugd01_republica c
          WHERE b.cod_pais = c.cod_republica) AS deno_pais_razon, b.cod_estado AS estado_razon, ( SELECT d.denominacion
           FROM cugd01_estados d
          WHERE b.cod_pais = d.cod_republica AND b.cod_estado = d.cod_estado) AS deno_estado_razon, b.cod_municipio AS municipio_razon, ( SELECT e.denominacion
           FROM cugd01_municipios e
          WHERE b.cod_pais = e.cod_republica AND b.cod_estado = e.cod_estado AND b.cod_municipio = e.cod_municipio) AS deno_municipio_razon, b.cod_parroquia AS parroquia_razon, ( SELECT f.denominacion
           FROM cugd01_parroquias f
          WHERE b.cod_pais = f.cod_republica AND b.cod_estado = f.cod_estado AND b.cod_municipio = f.cod_municipio AND b.cod_parroquia = f.cod_parroquia) AS deno_parroquia_razon, b.cod_centro_poblado AS centro_razon, ( SELECT g.denominacion
           FROM cugd01_centros_poblados g
          WHERE b.cod_pais = g.cod_republica AND b.cod_estado = g.cod_estado AND b.cod_municipio = g.cod_municipio AND b.cod_parroquia = g.cod_parroquia AND b.cod_centro_poblado = g.cod_centro) AS deno_centro_razon, b.cod_calle_avenida AS calle_razon, ( SELECT h.denominacion
           FROM cugd01_vialidad h
          WHERE b.cod_pais = h.cod_republica AND b.cod_estado = h.cod_estado AND b.cod_municipio = h.cod_municipio AND b.cod_parroquia = h.cod_parroquia AND b.cod_centro_poblado = h.cod_centro AND b.cod_calle_avenida = h.cod_vialidad) AS deno_vialidad_razon, b.cod_vereda_edificio AS vereda_razon, ( SELECT i.denominacion
           FROM cugd01_vereda i
          WHERE b.cod_pais = i.cod_republica AND b.cod_estado = i.cod_estado AND b.cod_municipio = i.cod_municipio AND b.cod_parroquia = i.cod_parroquia AND b.cod_centro_poblado = i.cod_centro AND b.cod_calle_avenida = i.cod_vialidad AND b.cod_vereda_edificio = i.cod_vereda) AS deno_vereda_razon, b.fecha_inscripcion, b.telefonos_fijos AS telefonos_fijos_razon, b.telefonos_celulares AS telefonos_celulares_razon, b.correo_electronico AS correo_electronico_razon, b.nacionalidad AS nacionalidad_razon, b.estado_civil, b.numero_vivienda_local AS numero_local_razon, b.profesion, ( SELECT j.denominacion
           FROM cnmd06_profesiones j
          WHERE b.profesion = j.cod_profesion) AS deno_profesion, a.categoria_comercial, a.mercado_cubre, ( SELECT x.fecha_patente
           FROM shd100_patente x
          WHERE a.numero_patente::text = x.numero_patente::text AND a.cod_presi = x.cod_presi AND a.cod_entidad = x.cod_entidad AND a.cod_tipo_inst = x.cod_tipo_inst AND a.cod_dep = x.cod_dep) AS fecha_patente, ( SELECT x.frecuencia_pago
           FROM shd100_patente x
          WHERE a.numero_patente::text = x.numero_patente::text AND a.cod_presi = x.cod_presi AND a.cod_entidad = x.cod_entidad AND a.cod_tipo_inst = x.cod_tipo_inst AND a.cod_dep = x.cod_dep) AS frecuencia_pago
   FROM shd100_solicitud a, shd001_registro_contribuyentes b
  WHERE a.rif_cedula::text = b.rif_cedula::text;

ALTER TABLE v_shd100_solicitud OWNER TO sisap;


-- View: v_shd100_declaracion_actividades

-- DROP VIEW v_shd100_declaracion_actividades;

CREATE OR REPLACE VIEW v_shd100_declaracion_actividades AS
 SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.rif_cedula
         , a.numero_declaracion, a.cod_actividad, ( SELECT b.denominacion_actividad
           FROM shd100_actividades b
          WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.cod_actividad::text = b.cod_actividad::text) AS deno_actividad, a.monto_ingresos, a.monto_impuesto,a.alicuota_aplicada
   FROM shd100_declaracion_actividades a;

ALTER TABLE v_shd100_declaracion_actividades OWNER TO sisap;

-- View: v_shd100_declaracion_ingreso

-- DROP VIEW v_shd100_declaracion_ingreso;

CREATE OR REPLACE VIEW v_shd100_declaracion_ingreso AS
 SELECT x.cod_presi, x.cod_entidad, x.cod_tipo_inst, x.cod_inst, x.cod_dep, x.numero_solicitud, x.numero_patente, x.fecha_solicitud, a.rif_cedula, a.razon_social_nombres, a.cod_pais, ( SELECT b.denominacion
           FROM cugd01_republica b
          WHERE b.cod_republica = a.cod_pais) AS denominacion_pais, a.cod_estado, ( SELECT c.denominacion
           FROM cugd01_estados c
          WHERE c.cod_republica = a.cod_pais AND c.cod_estado = a.cod_estado) AS denominacion_estado, a.cod_municipio, ( SELECT d.denominacion
           FROM cugd01_municipios d
          WHERE d.cod_republica = a.cod_pais AND d.cod_estado = a.cod_estado AND d.cod_municipio = a.cod_municipio) AS denominacion_municipio, a.cod_parroquia, ( SELECT e.denominacion
           FROM cugd01_parroquias e
          WHERE e.cod_republica = a.cod_pais AND e.cod_estado = a.cod_estado AND e.cod_municipio = a.cod_municipio AND e.cod_parroquia = a.cod_parroquia) AS denominacion_parroquia, a.cod_centro_poblado, ( SELECT f.denominacion
           FROM cugd01_centros_poblados f
          WHERE f.cod_republica = a.cod_pais AND f.cod_estado = a.cod_estado AND f.cod_municipio = a.cod_municipio AND f.cod_parroquia = a.cod_parroquia AND f.cod_centro = a.cod_centro_poblado) AS denominacion_centro, a.cod_calle_avenida, ( SELECT g.denominacion
           FROM cugd01_vialidad g
          WHERE g.cod_republica = a.cod_pais AND g.cod_estado = a.cod_estado AND g.cod_municipio = a.cod_municipio AND g.cod_parroquia = a.cod_parroquia AND g.cod_centro = a.cod_centro_poblado AND g.cod_vialidad = a.cod_calle_avenida) AS denominacion_vialidad, a.cod_vereda_edificio, ( SELECT h.denominacion
           FROM cugd01_vereda h
          WHERE h.cod_republica = a.cod_pais AND h.cod_estado = a.cod_estado AND h.cod_municipio = a.cod_municipio AND h.cod_parroquia = a.cod_parroquia AND h.cod_centro = a.cod_centro_poblado AND h.cod_vialidad = a.cod_calle_avenida AND h.cod_vereda = a.cod_vereda_edificio) AS denominacion_vereda, a.numero_vivienda_local, a.telefonos_fijos, a.telefonos_celulares, a.correo_electronico
   FROM shd001_registro_contribuyentes a, shd100_solicitud x
  WHERE x.rif_cedula::text = a.rif_cedula::text;

ALTER TABLE v_shd100_declaracion_ingreso OWNER TO sisap;

-- View: v_shd500_aseo_domiciliario

-- DROP VIEW v_shd500_aseo_domiciliario;

CREATE OR REPLACE VIEW v_shd500_aseo_domiciliario AS
 SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.rif_cedula, ( SELECT b.razon_social_nombres
           FROM v_shd001_registro_contribuyentes b
          WHERE a.rif_cedula::text = b.rif_cedula::text) AS nombre_razon, ( SELECT b.cod_pais
           FROM v_shd001_registro_contribuyentes b
          WHERE a.rif_cedula::text = b.rif_cedula::text) AS cod_pais, ( SELECT b.deno_pais
           FROM v_shd001_registro_contribuyentes b
          WHERE a.rif_cedula::text = b.rif_cedula::text) AS deno_pais, ( SELECT b.cod_estado
           FROM v_shd001_registro_contribuyentes b
          WHERE a.rif_cedula::text = b.rif_cedula::text) AS cod_estado, ( SELECT b.deno_estado
           FROM v_shd001_registro_contribuyentes b
          WHERE a.rif_cedula::text = b.rif_cedula::text) AS deno_estado, ( SELECT b.cod_municipio
           FROM v_shd001_registro_contribuyentes b
          WHERE a.rif_cedula::text = b.rif_cedula::text) AS cod_municipio, ( SELECT b.deno_municipio
           FROM v_shd001_registro_contribuyentes b
          WHERE a.rif_cedula::text = b.rif_cedula::text) AS deno_municipio, ( SELECT b.cod_parroquia
           FROM v_shd001_registro_contribuyentes b
          WHERE a.rif_cedula::text = b.rif_cedula::text) AS cod_parroquia, ( SELECT b.deno_parroquia
           FROM v_shd001_registro_contribuyentes b
          WHERE a.rif_cedula::text = b.rif_cedula::text) AS deno_parroquia, ( SELECT b.cod_centro_poblado
           FROM v_shd001_registro_contribuyentes b
          WHERE a.rif_cedula::text = b.rif_cedula::text) AS cod_centro, ( SELECT b.deno_centro
           FROM v_shd001_registro_contribuyentes b
          WHERE a.rif_cedula::text = b.rif_cedula::text) AS deno_centro, ( SELECT b.cod_calle_avenida
           FROM v_shd001_registro_contribuyentes b
          WHERE a.rif_cedula::text = b.rif_cedula::text) AS cod_calle, ( SELECT b.deno_vialidad
           FROM v_shd001_registro_contribuyentes b
          WHERE a.rif_cedula::text = b.rif_cedula::text) AS deno_calle, ( SELECT b.cod_vereda_edificio
           FROM v_shd001_registro_contribuyentes b
          WHERE a.rif_cedula::text = b.rif_cedula::text) AS cod_vereda_edificio, ( SELECT b.deno_vereda
           FROM v_shd001_registro_contribuyentes b
          WHERE a.rif_cedula::text = b.rif_cedula::text) AS deno_vereda, ( SELECT b.numero_vivienda_local
           FROM v_shd001_registro_contribuyentes b
          WHERE a.rif_cedula::text = b.rif_cedula::text) AS numero_casa, ( SELECT b.fecha_inscripcion
           FROM v_shd001_registro_contribuyentes b
          WHERE a.rif_cedula::text = b.rif_cedula::text) AS fecha_inscripcion, ( SELECT b.telefonos_fijos
           FROM v_shd001_registro_contribuyentes b
          WHERE a.rif_cedula::text = b.rif_cedula::text) AS telefonos_fijos, ( SELECT b.telefonos_celulares
           FROM v_shd001_registro_contribuyentes b
          WHERE a.rif_cedula::text = b.rif_cedula::text) AS telefonos_celulares, ( SELECT b.correo_electronico
           FROM v_shd001_registro_contribuyentes b
          WHERE a.rif_cedula::text = b.rif_cedula::text) AS correo_electronico, ( SELECT b.nacionalidad
           FROM v_shd001_registro_contribuyentes b
          WHERE a.rif_cedula::text = b.rif_cedula::text) AS nacionalidad, ( SELECT b.estado_civil
           FROM v_shd001_registro_contribuyentes b
          WHERE a.rif_cedula::text = b.rif_cedula::text) AS estado_civil, ( SELECT b.deno_profesion
           FROM v_shd001_registro_contribuyentes b
          WHERE a.rif_cedula::text = b.rif_cedula::text) AS deno_profesion, a.fecha_registro, a.cod_clasificacion, ( SELECT c.denominacion
           FROM shd500_aseo_clasificacion c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND a.cod_clasificacion::text = c.cod_clasificacion::text) AS deno_clasificacion, a.frecuencia_pago, a.monto_mensual, a.pago_todo, a.suspendido, a.rif_ci_cobrador, ( SELECT d.nombre_razon
           FROM shd002_cobradores d
          WHERE a.cod_presi = d.cod_presi AND a.cod_entidad = d.cod_entidad AND a.cod_tipo_inst = d.cod_tipo_inst AND a.cod_inst = d.cod_inst AND a.cod_dep = d.cod_dep AND a.rif_ci_cobrador::text = d.rif_ci::text) AS deno_cobrador, a.ultimo_ano_facturado, a.ultimo_mes_facturado
   FROM shd500_aseo_domiciliario a;

ALTER TABLE v_shd500_aseo_domiciliario OWNER TO sisap;

-- View: v_shd300_propaganda

-- DROP VIEW v_shd300_propaganda;

CREATE OR REPLACE VIEW v_shd300_propaganda AS
 SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.rif_cedula, ( SELECT b.razon_social_nombres
           FROM v_shd001_registro_contribuyentes b
          WHERE a.rif_cedula::text = b.rif_cedula::text) AS nombre_razon, ( SELECT b.cod_pais
           FROM v_shd001_registro_contribuyentes b
          WHERE a.rif_cedula::text = b.rif_cedula::text) AS cod_pais, ( SELECT b.deno_pais
           FROM v_shd001_registro_contribuyentes b
          WHERE a.rif_cedula::text = b.rif_cedula::text) AS deno_pais, ( SELECT b.cod_estado
           FROM v_shd001_registro_contribuyentes b
          WHERE a.rif_cedula::text = b.rif_cedula::text) AS cod_estado, ( SELECT b.deno_estado
           FROM v_shd001_registro_contribuyentes b
          WHERE a.rif_cedula::text = b.rif_cedula::text) AS deno_estado, ( SELECT b.cod_municipio
           FROM v_shd001_registro_contribuyentes b
          WHERE a.rif_cedula::text = b.rif_cedula::text) AS cod_municipio, ( SELECT b.deno_municipio
           FROM v_shd001_registro_contribuyentes b
          WHERE a.rif_cedula::text = b.rif_cedula::text) AS deno_municipio, ( SELECT b.cod_parroquia
           FROM v_shd001_registro_contribuyentes b
          WHERE a.rif_cedula::text = b.rif_cedula::text) AS cod_parroquia, ( SELECT b.deno_parroquia
           FROM v_shd001_registro_contribuyentes b
          WHERE a.rif_cedula::text = b.rif_cedula::text) AS deno_parroquia, ( SELECT b.cod_centro_poblado
           FROM v_shd001_registro_contribuyentes b
          WHERE a.rif_cedula::text = b.rif_cedula::text) AS cod_centro, ( SELECT b.deno_centro
           FROM v_shd001_registro_contribuyentes b
          WHERE a.rif_cedula::text = b.rif_cedula::text) AS deno_centro, ( SELECT b.cod_calle_avenida
           FROM v_shd001_registro_contribuyentes b
          WHERE a.rif_cedula::text = b.rif_cedula::text) AS cod_calle, ( SELECT b.deno_vialidad
           FROM v_shd001_registro_contribuyentes b
          WHERE a.rif_cedula::text = b.rif_cedula::text) AS deno_calle, ( SELECT b.cod_vereda_edificio
           FROM v_shd001_registro_contribuyentes b
          WHERE a.rif_cedula::text = b.rif_cedula::text) AS cod_vereda_edificio, ( SELECT b.deno_vereda
           FROM v_shd001_registro_contribuyentes b
          WHERE a.rif_cedula::text = b.rif_cedula::text) AS deno_vereda, ( SELECT b.numero_vivienda_local
           FROM v_shd001_registro_contribuyentes b
          WHERE a.rif_cedula::text = b.rif_cedula::text) AS numero_casa, ( SELECT b.fecha_inscripcion
           FROM v_shd001_registro_contribuyentes b
          WHERE a.rif_cedula::text = b.rif_cedula::text) AS fecha_inscripcion_cont, ( SELECT b.telefonos_fijos
           FROM v_shd001_registro_contribuyentes b
          WHERE a.rif_cedula::text = b.rif_cedula::text) AS telefonos_fijos, ( SELECT b.telefonos_celulares
           FROM v_shd001_registro_contribuyentes b
          WHERE a.rif_cedula::text = b.rif_cedula::text) AS telefonos_celulares, ( SELECT b.correo_electronico
           FROM v_shd001_registro_contribuyentes b
          WHERE a.rif_cedula::text = b.rif_cedula::text) AS correo_electronico, ( SELECT b.nacionalidad
           FROM v_shd001_registro_contribuyentes b
          WHERE a.rif_cedula::text = b.rif_cedula::text) AS nacionalidad, ( SELECT b.estado_civil
           FROM v_shd001_registro_contribuyentes b
          WHERE a.rif_cedula::text = b.rif_cedula::text) AS estado_civil, ( SELECT b.deno_profesion
           FROM v_shd001_registro_contribuyentes b
          WHERE a.rif_cedula::text = b.rif_cedula::text) AS deno_profesion, a.frecuencia_pago, a.monto_mensual_general, a.pago_todo, a.suspendido, a.rif_ci_cobrador, ( SELECT d.nombre_razon
           FROM shd002_cobradores d
          WHERE a.cod_presi = d.cod_presi AND a.cod_entidad = d.cod_entidad AND a.cod_tipo_inst = d.cod_tipo_inst AND a.cod_inst = d.cod_inst AND a.cod_dep = d.cod_dep AND a.rif_ci_cobrador::text = d.rif_ci::text) AS deno_cobrador, a.ultimo_ano_facturado, a.ultimo_mes_facturado
   FROM shd300_propaganda a;

ALTER TABLE v_shd300_propaganda OWNER TO sisap;

-- View: v_shd400_propiedad

-- DROP VIEW v_shd400_propiedad;

CREATE OR REPLACE VIEW v_shd400_propiedad AS
 SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.rif_cedula, ( SELECT b.razon_social_nombres
           FROM v_shd001_registro_contribuyentes b
          WHERE a.rif_cedula::text = b.rif_cedula::text) AS nombre_razon, ( SELECT b.cod_pais
           FROM v_shd001_registro_contribuyentes b
          WHERE a.rif_cedula::text = b.rif_cedula::text) AS cod_pais, ( SELECT b.deno_pais
           FROM v_shd001_registro_contribuyentes b
          WHERE a.rif_cedula::text = b.rif_cedula::text) AS deno_pais, ( SELECT b.cod_estado
           FROM v_shd001_registro_contribuyentes b
          WHERE a.rif_cedula::text = b.rif_cedula::text) AS cod_estado, ( SELECT b.deno_estado
           FROM v_shd001_registro_contribuyentes b
          WHERE a.rif_cedula::text = b.rif_cedula::text) AS deno_estado, ( SELECT b.cod_municipio
           FROM v_shd001_registro_contribuyentes b
          WHERE a.rif_cedula::text = b.rif_cedula::text) AS cod_municipio, ( SELECT b.deno_municipio
           FROM v_shd001_registro_contribuyentes b
          WHERE a.rif_cedula::text = b.rif_cedula::text) AS deno_municipio, ( SELECT b.cod_parroquia
           FROM v_shd001_registro_contribuyentes b
          WHERE a.rif_cedula::text = b.rif_cedula::text) AS cod_parroquia, ( SELECT b.deno_parroquia
           FROM v_shd001_registro_contribuyentes b
          WHERE a.rif_cedula::text = b.rif_cedula::text) AS deno_parroquia, ( SELECT b.cod_centro_poblado
           FROM v_shd001_registro_contribuyentes b
          WHERE a.rif_cedula::text = b.rif_cedula::text) AS cod_centro, ( SELECT b.deno_centro
           FROM v_shd001_registro_contribuyentes b
          WHERE a.rif_cedula::text = b.rif_cedula::text) AS deno_centro, ( SELECT b.cod_calle_avenida
           FROM v_shd001_registro_contribuyentes b
          WHERE a.rif_cedula::text = b.rif_cedula::text) AS cod_calle, ( SELECT b.deno_vialidad
           FROM v_shd001_registro_contribuyentes b
          WHERE a.rif_cedula::text = b.rif_cedula::text) AS deno_calle, ( SELECT b.cod_vereda_edificio
           FROM v_shd001_registro_contribuyentes b
          WHERE a.rif_cedula::text = b.rif_cedula::text) AS cod_vereda_edificio, ( SELECT b.deno_vereda
           FROM v_shd001_registro_contribuyentes b
          WHERE a.rif_cedula::text = b.rif_cedula::text) AS deno_vereda, ( SELECT b.numero_vivienda_local
           FROM v_shd001_registro_contribuyentes b
          WHERE a.rif_cedula::text = b.rif_cedula::text) AS numero_casa, ( SELECT b.fecha_inscripcion
           FROM v_shd001_registro_contribuyentes b
          WHERE a.rif_cedula::text = b.rif_cedula::text) AS fecha_inscripcion_cont, ( SELECT b.telefonos_fijos
           FROM v_shd001_registro_contribuyentes b
          WHERE a.rif_cedula::text = b.rif_cedula::text) AS telefonos_fijos, ( SELECT b.telefonos_celulares
           FROM v_shd001_registro_contribuyentes b
          WHERE a.rif_cedula::text = b.rif_cedula::text) AS telefonos_celulares, ( SELECT b.correo_electronico
           FROM v_shd001_registro_contribuyentes b
          WHERE a.rif_cedula::text = b.rif_cedula::text) AS correo_electronico, ( SELECT b.nacionalidad
           FROM v_shd001_registro_contribuyentes b
          WHERE a.rif_cedula::text = b.rif_cedula::text) AS nacionalidad, ( SELECT b.estado_civil
           FROM v_shd001_registro_contribuyentes b
          WHERE a.rif_cedula::text = b.rif_cedula::text) AS estado_civil, ( SELECT b.deno_profesion
           FROM v_shd001_registro_contribuyentes b
          WHERE a.rif_cedula::text = b.rif_cedula::text) AS deno_profesion, a.cod_ficha, ( SELECT c.cod_inscripcion
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND a.cod_ficha::integer = c.cod_ficha) AS cod_inscripcion, ( SELECT c.fecha_inscripcion
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND a.cod_ficha::integer = c.cod_ficha) AS fecha_inscripcion_cat, ( SELECT c.cod_control_archivo
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND a.cod_ficha::integer = c.cod_ficha) AS cod_control_archivo, ( SELECT c.ano_ordenanza
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND a.cod_ficha::integer = c.cod_ficha) AS ano_ordenanza, ( SELECT c.cod_act_edo
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND a.cod_ficha::integer = c.cod_ficha) AS cod_act_edo, ( SELECT c.cod_act_mun
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND a.cod_ficha::integer = c.cod_ficha) AS cod_act_mun, ( SELECT c.cod_act_prr
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND a.cod_ficha::integer = c.cod_ficha) AS cod_act_prr, ( SELECT c.cod_act_amb_t
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND a.cod_ficha::integer = c.cod_ficha) AS cod_act_amb_t, ( SELECT c.cod_act_amb
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND a.cod_ficha::integer = c.cod_ficha) AS cod_act_amb, ( SELECT c.cod_act_sec
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND a.cod_ficha::integer = c.cod_ficha) AS cod_act_sec, ( SELECT c.cod_act_man
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND a.cod_ficha::integer = c.cod_ficha) AS cod_act_man, ( SELECT c.cod_act_par
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND a.cod_ficha::integer = c.cod_ficha) AS cod_act_par, ( SELECT c.cod_act_sbp
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND a.cod_ficha::integer = c.cod_ficha) AS cod_act_sbp, ( SELECT c.cod_act_niv
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND a.cod_ficha::integer = c.cod_ficha) AS cod_act_niv, ( SELECT c.cod_act_und
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND a.cod_ficha::integer = c.cod_ficha) AS cod_act_und, ( SELECT c.tilde_tenencia
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND a.cod_ficha::integer = c.cod_ficha) AS tilde_tenencia, ( SELECT c.tilde_tenencia_const
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND a.cod_ficha::integer = c.cod_ficha) AS tilde_tenencia_const, ( SELECT sum(d.valor_actual) AS r2
           FROM catd02_ficha_tipologia d
          WHERE a.cod_presi = d.cod_presi AND a.cod_entidad = d.cod_entidad AND a.cod_tipo_inst = d.cod_tipo_inst AND a.cod_inst = d.cod_inst AND a.cod_dep = d.cod_dep AND a.cod_ficha::integer = d.cod_ficha) AS valor_construccion, a.frecuencia_pago, a.monto_mensual, a.pago_todo, a.suspendido, a.rif_ci_cobrador, ( SELECT e.nombre_razon
           FROM shd002_cobradores e
          WHERE a.cod_presi = e.cod_presi AND a.cod_entidad = e.cod_entidad AND a.cod_tipo_inst = e.cod_tipo_inst AND a.cod_inst = e.cod_inst AND a.cod_dep = e.cod_dep AND a.rif_ci_cobrador::text = e.rif_ci::text) AS deno_cobrador, a.ultimo_ano_facturado, a.ultimo_mes_facturado
   FROM shd400_propiedad a;

ALTER TABLE v_shd400_propiedad OWNER TO sisap;

-- View: v_shd600_solicitud_arrendamiento

-- DROP VIEW v_shd600_solicitud_arrendamiento;

CREATE OR REPLACE VIEW v_shd600_solicitud_arrendamiento AS
 SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.numero_solicitud, a.fecha_solicitud, a.rif_cedula, ( SELECT b.razon_social_nombres
           FROM v_shd001_registro_contribuyentes b
          WHERE a.rif_cedula::text = b.rif_cedula::text) AS nombre_razon, ( SELECT b.cod_pais
           FROM v_shd001_registro_contribuyentes b
          WHERE a.rif_cedula::text = b.rif_cedula::text) AS cod_pais, ( SELECT b.deno_pais
           FROM v_shd001_registro_contribuyentes b
          WHERE a.rif_cedula::text = b.rif_cedula::text) AS deno_pais, ( SELECT b.cod_estado
           FROM v_shd001_registro_contribuyentes b
          WHERE a.rif_cedula::text = b.rif_cedula::text) AS cod_estado, ( SELECT b.deno_estado
           FROM v_shd001_registro_contribuyentes b
          WHERE a.rif_cedula::text = b.rif_cedula::text) AS deno_estado, ( SELECT b.cod_municipio
           FROM v_shd001_registro_contribuyentes b
          WHERE a.rif_cedula::text = b.rif_cedula::text) AS cod_municipio, ( SELECT b.deno_municipio
           FROM v_shd001_registro_contribuyentes b
          WHERE a.rif_cedula::text = b.rif_cedula::text) AS deno_municipio, ( SELECT b.cod_parroquia
           FROM v_shd001_registro_contribuyentes b
          WHERE a.rif_cedula::text = b.rif_cedula::text) AS cod_parroquia, ( SELECT b.deno_parroquia
           FROM v_shd001_registro_contribuyentes b
          WHERE a.rif_cedula::text = b.rif_cedula::text) AS deno_parroquia, ( SELECT b.cod_centro_poblado
           FROM v_shd001_registro_contribuyentes b
          WHERE a.rif_cedula::text = b.rif_cedula::text) AS cod_centro, ( SELECT b.deno_centro
           FROM v_shd001_registro_contribuyentes b
          WHERE a.rif_cedula::text = b.rif_cedula::text) AS deno_centro, ( SELECT b.cod_calle_avenida
           FROM v_shd001_registro_contribuyentes b
          WHERE a.rif_cedula::text = b.rif_cedula::text) AS cod_calle, ( SELECT b.deno_vialidad
           FROM v_shd001_registro_contribuyentes b
          WHERE a.rif_cedula::text = b.rif_cedula::text) AS deno_calle, ( SELECT b.cod_vereda_edificio
           FROM v_shd001_registro_contribuyentes b
          WHERE a.rif_cedula::text = b.rif_cedula::text) AS cod_vereda_edificio, ( SELECT b.deno_vereda
           FROM v_shd001_registro_contribuyentes b
          WHERE a.rif_cedula::text = b.rif_cedula::text) AS deno_vereda, ( SELECT b.numero_vivienda_local
           FROM v_shd001_registro_contribuyentes b
          WHERE a.rif_cedula::text = b.rif_cedula::text) AS numero_casa, ( SELECT b.fecha_inscripcion
           FROM v_shd001_registro_contribuyentes b
          WHERE a.rif_cedula::text = b.rif_cedula::text) AS fecha_inscripcion_cont, ( SELECT b.telefonos_fijos
           FROM v_shd001_registro_contribuyentes b
          WHERE a.rif_cedula::text = b.rif_cedula::text) AS telefonos_fijos, ( SELECT b.telefonos_celulares
           FROM v_shd001_registro_contribuyentes b
          WHERE a.rif_cedula::text = b.rif_cedula::text) AS telefonos_celulares, ( SELECT b.correo_electronico
           FROM v_shd001_registro_contribuyentes b
          WHERE a.rif_cedula::text = b.rif_cedula::text) AS correo_electronico, ( SELECT b.nacionalidad
           FROM v_shd001_registro_contribuyentes b
          WHERE a.rif_cedula::text = b.rif_cedula::text) AS nacionalidad, ( SELECT b.estado_civil
           FROM v_shd001_registro_contribuyentes b
          WHERE a.rif_cedula::text = b.rif_cedula::text) AS estado_civil, ( SELECT b.deno_profesion
           FROM v_shd001_registro_contribuyentes b
          WHERE a.rif_cedula::text = b.rif_cedula::text) AS deno_profesion, a.opcion, a.cod_ficha, ( SELECT c.cod_inscripcion
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND a.cod_ficha::integer = c.cod_ficha) AS cod_inscripcion, ( SELECT c.fecha_inscripcion
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND a.cod_ficha::integer = c.cod_ficha) AS fecha_inscripcion_cat, ( SELECT c.cod_control_archivo
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND a.cod_ficha::integer = c.cod_ficha) AS cod_control_archivo, ( SELECT c.ano_ordenanza
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND a.cod_ficha::integer = c.cod_ficha) AS ano_ordenanza, ( SELECT c.cod_act_edo
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND a.cod_ficha::integer = c.cod_ficha) AS cod_act_edo, ( SELECT c.cod_act_mun
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND a.cod_ficha::integer = c.cod_ficha) AS cod_act_mun, ( SELECT c.cod_act_prr
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND a.cod_ficha::integer = c.cod_ficha) AS cod_act_prr, ( SELECT c.cod_act_amb_t
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND a.cod_ficha::integer = c.cod_ficha) AS cod_act_amb_t, ( SELECT c.cod_act_amb
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND a.cod_ficha::integer = c.cod_ficha) AS cod_act_amb, ( SELECT c.cod_act_sec
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND a.cod_ficha::integer = c.cod_ficha) AS cod_act_sec, ( SELECT c.cod_act_man
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND a.cod_ficha::integer = c.cod_ficha) AS cod_act_man, ( SELECT c.cod_act_par
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND a.cod_ficha::integer = c.cod_ficha) AS cod_act_par, ( SELECT c.cod_act_sbp
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND a.cod_ficha::integer = c.cod_ficha) AS cod_act_sbp, ( SELECT c.cod_act_niv
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND a.cod_ficha::integer = c.cod_ficha) AS cod_act_niv, ( SELECT c.cod_act_und
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND a.cod_ficha::integer = c.cod_ficha) AS cod_act_und, ( SELECT c.lindero_norte
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND a.cod_ficha::integer = c.cod_ficha) AS lindero_norte, ( SELECT c.lindero_sur
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND a.cod_ficha::integer = c.cod_ficha) AS lindero_sur, ( SELECT c.lindero_este
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND a.cod_ficha::integer = c.cod_ficha) AS lindero_este, ( SELECT c.lindero_oeste
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND a.cod_ficha::integer = c.cod_ficha) AS lindero_oeste, ( SELECT c.valoracion_area
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND a.cod_ficha::integer = c.cod_ficha) AS valoracion_area, ( SELECT c.valoracion_valor_unitario
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND a.cod_ficha::integer = c.cod_ficha) AS valoracion_valor_unitario, ( SELECT c.valoracion_sector
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND a.cod_ficha::integer = c.cod_ficha) AS valoracion_sector, ( SELECT c.valoracion_ajuste_area
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND a.cod_ficha::integer = c.cod_ficha) AS valoracion_ajuste_area, ( SELECT c.valoracion_ajuste_forma
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND a.cod_ficha::integer = c.cod_ficha) AS valoracion_ajuste_forma, ( SELECT c.valoracion_valor_ajustado
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND a.cod_ficha::integer = c.cod_ficha) AS valoracion_valor_ajustado, ( SELECT c.valoracion_valor_total
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND a.cod_ficha::integer = c.cod_ficha) AS valoracion_valor_total, a.expectativa_construccion
   FROM shd600_solicitud_arrendamiento a;

ALTER TABLE v_shd600_solicitud_arrendamiento OWNER TO sisap;

-- View: v_shd600_aprobacion_arrendamiento

-- DROP VIEW v_shd600_aprobacion_arrendamiento;

CREATE OR REPLACE VIEW v_shd600_aprobacion_arrendamiento AS
 SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.numero_solicitud, ( SELECT b.opcion
           FROM shd600_solicitud_arrendamiento b
          WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text) AS opcion, ( SELECT b.rif_cedula
           FROM shd600_solicitud_arrendamiento b
          WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text) AS rif_cedula, ( SELECT b.razon_social_nombres
           FROM v_shd001_registro_contribuyentes b
          WHERE ((( SELECT b.rif_cedula
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::text) = b.rif_cedula::text) AS nombre_razon, ( SELECT b.cod_pais
           FROM v_shd001_registro_contribuyentes b
          WHERE ((( SELECT b.rif_cedula
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::text) = b.rif_cedula::text) AS cod_pais, ( SELECT b.deno_pais
           FROM v_shd001_registro_contribuyentes b
          WHERE ((( SELECT b.rif_cedula
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::text) = b.rif_cedula::text) AS deno_pais, ( SELECT b.cod_estado
           FROM v_shd001_registro_contribuyentes b
          WHERE ((( SELECT b.rif_cedula
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::text) = b.rif_cedula::text) AS cod_estado, ( SELECT b.deno_estado
           FROM v_shd001_registro_contribuyentes b
          WHERE ((( SELECT b.rif_cedula
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::text) = b.rif_cedula::text) AS deno_estado, ( SELECT b.cod_municipio
           FROM v_shd001_registro_contribuyentes b
          WHERE ((( SELECT b.rif_cedula
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::text) = b.rif_cedula::text) AS cod_municipio, ( SELECT b.deno_municipio
           FROM v_shd001_registro_contribuyentes b
          WHERE ((( SELECT b.rif_cedula
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::text) = b.rif_cedula::text) AS deno_municipio, ( SELECT b.cod_parroquia
           FROM v_shd001_registro_contribuyentes b
          WHERE ((( SELECT b.rif_cedula
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::text) = b.rif_cedula::text) AS cod_parroquia, ( SELECT b.deno_parroquia
           FROM v_shd001_registro_contribuyentes b
          WHERE ((( SELECT b.rif_cedula
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::text) = b.rif_cedula::text) AS deno_parroquia, ( SELECT b.cod_centro_poblado
           FROM v_shd001_registro_contribuyentes b
          WHERE ((( SELECT b.rif_cedula
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::text) = b.rif_cedula::text) AS cod_centro, ( SELECT b.deno_centro
           FROM v_shd001_registro_contribuyentes b
          WHERE ((( SELECT b.rif_cedula
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::text) = b.rif_cedula::text) AS deno_centro, ( SELECT b.cod_calle_avenida
           FROM v_shd001_registro_contribuyentes b
          WHERE ((( SELECT b.rif_cedula
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::text) = b.rif_cedula::text) AS cod_calle, ( SELECT b.deno_vialidad
           FROM v_shd001_registro_contribuyentes b
          WHERE ((( SELECT b.rif_cedula
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::text) = b.rif_cedula::text) AS deno_calle, ( SELECT b.cod_vereda_edificio
           FROM v_shd001_registro_contribuyentes b
          WHERE ((( SELECT b.rif_cedula
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::text) = b.rif_cedula::text) AS cod_vereda_edificio, ( SELECT b.deno_vereda
           FROM v_shd001_registro_contribuyentes b
          WHERE ((( SELECT b.rif_cedula
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::text) = b.rif_cedula::text) AS deno_vereda, ( SELECT b.numero_vivienda_local
           FROM v_shd001_registro_contribuyentes b
          WHERE ((( SELECT b.rif_cedula
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::text) = b.rif_cedula::text) AS numero_casa, ( SELECT b.fecha_inscripcion
           FROM v_shd001_registro_contribuyentes b
          WHERE ((( SELECT b.rif_cedula
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::text) = b.rif_cedula::text) AS fecha_inscripcion_cont, ( SELECT b.telefonos_fijos
           FROM v_shd001_registro_contribuyentes b
          WHERE ((( SELECT b.rif_cedula
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::text) = b.rif_cedula::text) AS telefonos_fijos, ( SELECT b.telefonos_celulares
           FROM v_shd001_registro_contribuyentes b
          WHERE ((( SELECT b.rif_cedula
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::text) = b.rif_cedula::text) AS telefonos_celulares, ( SELECT b.correo_electronico
           FROM v_shd001_registro_contribuyentes b
          WHERE ((( SELECT b.rif_cedula
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::text) = b.rif_cedula::text) AS correo_electronico, ( SELECT b.nacionalidad
           FROM v_shd001_registro_contribuyentes b
          WHERE ((( SELECT b.rif_cedula
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::text) = b.rif_cedula::text) AS nacionalidad, ( SELECT b.estado_civil
           FROM v_shd001_registro_contribuyentes b
          WHERE ((( SELECT b.rif_cedula
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::text) = b.rif_cedula::text) AS estado_civil, ( SELECT b.deno_profesion
           FROM v_shd001_registro_contribuyentes b
          WHERE ((( SELECT b.rif_cedula
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::text) = b.rif_cedula::text) AS deno_profesion, ( SELECT b.cod_ficha
           FROM shd600_solicitud_arrendamiento b
          WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text) AS cod_ficha, ( SELECT c.cod_inscripcion
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND ((( SELECT b.cod_ficha
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::integer) = c.cod_ficha) AS cod_inscripcion, ( SELECT c.fecha_inscripcion
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND ((( SELECT b.cod_ficha
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::integer) = c.cod_ficha) AS fecha_inscripcion_cat, ( SELECT c.cod_control_archivo
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND ((( SELECT b.cod_ficha
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::integer) = c.cod_ficha) AS cod_control_archivo, ( SELECT c.ano_ordenanza
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND ((( SELECT b.cod_ficha
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::integer) = c.cod_ficha) AS ano_ordenanza, ( SELECT c.cod_act_edo
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND ((( SELECT b.cod_ficha
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::integer) = c.cod_ficha) AS cod_act_edo, ( SELECT c.cod_act_mun
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND ((( SELECT b.cod_ficha
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::integer) = c.cod_ficha) AS cod_act_mun, ( SELECT c.cod_act_prr
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND ((( SELECT b.cod_ficha
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::integer) = c.cod_ficha) AS cod_act_prr, ( SELECT c.cod_act_amb_t
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND ((( SELECT b.cod_ficha
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::integer) = c.cod_ficha) AS cod_act_amb_t, ( SELECT c.cod_act_amb
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND ((( SELECT b.cod_ficha
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::integer) = c.cod_ficha) AS cod_act_amb, ( SELECT c.cod_act_sec
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND ((( SELECT b.cod_ficha
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::integer) = c.cod_ficha) AS cod_act_sec, ( SELECT c.cod_act_man
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND ((( SELECT b.cod_ficha
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::integer) = c.cod_ficha) AS cod_act_man, ( SELECT c.cod_act_par
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND ((( SELECT b.cod_ficha
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::integer) = c.cod_ficha) AS cod_act_par, ( SELECT c.cod_act_sbp
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND ((( SELECT b.cod_ficha
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::integer) = c.cod_ficha) AS cod_act_sbp, ( SELECT c.cod_act_niv
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND ((( SELECT b.cod_ficha
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::integer) = c.cod_ficha) AS cod_act_niv, ( SELECT c.cod_act_und
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND ((( SELECT b.cod_ficha
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::integer) = c.cod_ficha) AS cod_act_und, ( SELECT c.lindero_norte
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND ((( SELECT b.cod_ficha
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::integer) = c.cod_ficha) AS lindero_norte, ( SELECT c.lindero_sur
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND ((( SELECT b.cod_ficha
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::integer) = c.cod_ficha) AS lindero_sur, ( SELECT c.lindero_este
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND ((( SELECT b.cod_ficha
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::integer) = c.cod_ficha) AS lindero_este, ( SELECT c.lindero_oeste
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND ((( SELECT b.cod_ficha
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::integer) = c.cod_ficha) AS lindero_oeste, ( SELECT c.valoracion_area
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND ((( SELECT b.cod_ficha
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::integer) = c.cod_ficha) AS valoracion_area, ( SELECT c.valoracion_valor_unitario
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND ((( SELECT b.cod_ficha
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::integer) = c.cod_ficha) AS valoracion_valor_unitario, ( SELECT c.valoracion_sector
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND ((( SELECT b.cod_ficha
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::integer) = c.cod_ficha) AS valoracion_sector, ( SELECT c.valoracion_ajuste_area
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND ((( SELECT b.cod_ficha
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::integer) = c.cod_ficha) AS valoracion_ajuste_area, ( SELECT c.valoracion_ajuste_forma
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND ((( SELECT b.cod_ficha
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::integer) = c.cod_ficha) AS valoracion_ajuste_forma, ( SELECT c.valoracion_valor_ajustado
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND ((( SELECT b.cod_ficha
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::integer) = c.cod_ficha) AS valoracion_valor_ajustado, ( SELECT c.valoracion_valor_total
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND ((( SELECT b.cod_ficha
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::integer) = c.cod_ficha) AS valoracion_valor_total, ( SELECT b.expectativa_construccion
           FROM shd600_solicitud_arrendamiento b
          WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text) AS expectativa_construccion, a.fecha_aprobacion, a.frecuencia_pago, a.datos_registro_arrendamiento, a.monto_mensual, a.pago_todo, a.suspendido, a.rif_ci_cobrador, ( SELECT e.nombre_razon
           FROM shd002_cobradores e
          WHERE a.cod_presi = e.cod_presi AND a.cod_entidad = e.cod_entidad AND a.cod_tipo_inst = e.cod_tipo_inst AND a.cod_inst = e.cod_inst AND a.cod_dep = e.cod_dep AND a.rif_ci_cobrador::text = e.rif_ci::text) AS deno_cobrador, a.ultimo_ano_facturado, a.ultimo_mes_facturado, a.terreno_vendido, ( SELECT b.monto
           FROM shd600_compra_terreno b
          WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text) AS monto, ( SELECT b.fecha_compra
           FROM shd600_compra_terreno b
          WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text) AS fecha_venta
   FROM shd600_aprobacion_arrendamiento a;

ALTER TABLE v_shd600_aprobacion_arrendamiento OWNER TO sisap;

-- View: v_shd600_compra_terreno

-- DROP VIEW v_shd600_compra_terreno;

CREATE OR REPLACE VIEW v_shd600_compra_terreno AS
 SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.numero_solicitud, ( SELECT b.terreno_vendido
           FROM shd600_aprobacion_arrendamiento b
          WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text) AS terreno_vendido, a.fecha_compra, a.datos_compra, a.monto, ( SELECT b.opcion
           FROM shd600_solicitud_arrendamiento b
          WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text) AS opcion, ( SELECT b.rif_cedula
           FROM shd600_solicitud_arrendamiento b
          WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text) AS rif_cedula, ( SELECT b.razon_social_nombres
           FROM v_shd001_registro_contribuyentes b
          WHERE ((( SELECT b.rif_cedula
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::text) = b.rif_cedula::text) AS nombre_razon, ( SELECT b.cod_pais
           FROM v_shd001_registro_contribuyentes b
          WHERE ((( SELECT b.rif_cedula
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::text) = b.rif_cedula::text) AS cod_pais, ( SELECT b.deno_pais
           FROM v_shd001_registro_contribuyentes b
          WHERE ((( SELECT b.rif_cedula
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::text) = b.rif_cedula::text) AS deno_pais, ( SELECT b.cod_estado
           FROM v_shd001_registro_contribuyentes b
          WHERE ((( SELECT b.rif_cedula
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::text) = b.rif_cedula::text) AS cod_estado, ( SELECT b.deno_estado
           FROM v_shd001_registro_contribuyentes b
          WHERE ((( SELECT b.rif_cedula
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::text) = b.rif_cedula::text) AS deno_estado, ( SELECT b.cod_municipio
           FROM v_shd001_registro_contribuyentes b
          WHERE ((( SELECT b.rif_cedula
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::text) = b.rif_cedula::text) AS cod_municipio, ( SELECT b.deno_municipio
           FROM v_shd001_registro_contribuyentes b
          WHERE ((( SELECT b.rif_cedula
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::text) = b.rif_cedula::text) AS deno_municipio, ( SELECT b.cod_parroquia
           FROM v_shd001_registro_contribuyentes b
          WHERE ((( SELECT b.rif_cedula
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::text) = b.rif_cedula::text) AS cod_parroquia, ( SELECT b.deno_parroquia
           FROM v_shd001_registro_contribuyentes b
          WHERE ((( SELECT b.rif_cedula
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::text) = b.rif_cedula::text) AS deno_parroquia, ( SELECT b.cod_centro_poblado
           FROM v_shd001_registro_contribuyentes b
          WHERE ((( SELECT b.rif_cedula
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::text) = b.rif_cedula::text) AS cod_centro, ( SELECT b.deno_centro
           FROM v_shd001_registro_contribuyentes b
          WHERE ((( SELECT b.rif_cedula
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::text) = b.rif_cedula::text) AS deno_centro, ( SELECT b.cod_calle_avenida
           FROM v_shd001_registro_contribuyentes b
          WHERE ((( SELECT b.rif_cedula
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::text) = b.rif_cedula::text) AS cod_calle, ( SELECT b.deno_vialidad
           FROM v_shd001_registro_contribuyentes b
          WHERE ((( SELECT b.rif_cedula
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::text) = b.rif_cedula::text) AS deno_calle, ( SELECT b.cod_vereda_edificio
           FROM v_shd001_registro_contribuyentes b
          WHERE ((( SELECT b.rif_cedula
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::text) = b.rif_cedula::text) AS cod_vereda_edificio, ( SELECT b.deno_vereda
           FROM v_shd001_registro_contribuyentes b
          WHERE ((( SELECT b.rif_cedula
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::text) = b.rif_cedula::text) AS deno_vereda, ( SELECT b.numero_vivienda_local
           FROM v_shd001_registro_contribuyentes b
          WHERE ((( SELECT b.rif_cedula
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::text) = b.rif_cedula::text) AS numero_casa, ( SELECT b.fecha_inscripcion
           FROM v_shd001_registro_contribuyentes b
          WHERE ((( SELECT b.rif_cedula
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::text) = b.rif_cedula::text) AS fecha_inscripcion_cont, ( SELECT b.telefonos_fijos
           FROM v_shd001_registro_contribuyentes b
          WHERE ((( SELECT b.rif_cedula
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::text) = b.rif_cedula::text) AS telefonos_fijos, ( SELECT b.telefonos_celulares
           FROM v_shd001_registro_contribuyentes b
          WHERE ((( SELECT b.rif_cedula
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::text) = b.rif_cedula::text) AS telefonos_celulares, ( SELECT b.correo_electronico
           FROM v_shd001_registro_contribuyentes b
          WHERE ((( SELECT b.rif_cedula
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::text) = b.rif_cedula::text) AS correo_electronico, ( SELECT b.nacionalidad
           FROM v_shd001_registro_contribuyentes b
          WHERE ((( SELECT b.rif_cedula
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::text) = b.rif_cedula::text) AS nacionalidad, ( SELECT b.estado_civil
           FROM v_shd001_registro_contribuyentes b
          WHERE ((( SELECT b.rif_cedula
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::text) = b.rif_cedula::text) AS estado_civil, ( SELECT b.deno_profesion
           FROM v_shd001_registro_contribuyentes b
          WHERE ((( SELECT b.rif_cedula
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::text) = b.rif_cedula::text) AS deno_profesion, ( SELECT b.cod_ficha
           FROM shd600_solicitud_arrendamiento b
          WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text) AS cod_ficha, ( SELECT c.cod_inscripcion
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND ((( SELECT b.cod_ficha
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::integer) = c.cod_ficha) AS cod_inscripcion, ( SELECT c.fecha_inscripcion
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND ((( SELECT b.cod_ficha
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::integer) = c.cod_ficha) AS fecha_inscripcion_cat, ( SELECT c.cod_control_archivo
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND ((( SELECT b.cod_ficha
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::integer) = c.cod_ficha) AS cod_control_archivo, ( SELECT c.ano_ordenanza
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND ((( SELECT b.cod_ficha
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::integer) = c.cod_ficha) AS ano_ordenanza, ( SELECT c.cod_act_edo
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND ((( SELECT b.cod_ficha
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::integer) = c.cod_ficha) AS cod_act_edo, ( SELECT c.cod_act_mun
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND ((( SELECT b.cod_ficha
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::integer) = c.cod_ficha) AS cod_act_mun, ( SELECT c.cod_act_prr
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND ((( SELECT b.cod_ficha
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::integer) = c.cod_ficha) AS cod_act_prr, ( SELECT c.cod_act_amb_t
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND ((( SELECT b.cod_ficha
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::integer) = c.cod_ficha) AS cod_act_amb_t, ( SELECT c.cod_act_amb
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND ((( SELECT b.cod_ficha
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::integer) = c.cod_ficha) AS cod_act_amb, ( SELECT c.cod_act_sec
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND ((( SELECT b.cod_ficha
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::integer) = c.cod_ficha) AS cod_act_sec, ( SELECT c.cod_act_man
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND ((( SELECT b.cod_ficha
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::integer) = c.cod_ficha) AS cod_act_man, ( SELECT c.cod_act_par
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND ((( SELECT b.cod_ficha
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::integer) = c.cod_ficha) AS cod_act_par, ( SELECT c.cod_act_sbp
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND ((( SELECT b.cod_ficha
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::integer) = c.cod_ficha) AS cod_act_sbp, ( SELECT c.cod_act_niv
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND ((( SELECT b.cod_ficha
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::integer) = c.cod_ficha) AS cod_act_niv, ( SELECT c.cod_act_und
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND ((( SELECT b.cod_ficha
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::integer) = c.cod_ficha) AS cod_act_und, ( SELECT c.lindero_norte
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND ((( SELECT b.cod_ficha
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::integer) = c.cod_ficha) AS lindero_norte, ( SELECT c.lindero_sur
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND ((( SELECT b.cod_ficha
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::integer) = c.cod_ficha) AS lindero_sur, ( SELECT c.lindero_este
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND ((( SELECT b.cod_ficha
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::integer) = c.cod_ficha) AS lindero_este, ( SELECT c.lindero_oeste
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND ((( SELECT b.cod_ficha
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::integer) = c.cod_ficha) AS lindero_oeste, ( SELECT c.valoracion_area
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND ((( SELECT b.cod_ficha
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::integer) = c.cod_ficha) AS valoracion_area, ( SELECT c.valoracion_valor_unitario
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND ((( SELECT b.cod_ficha
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::integer) = c.cod_ficha) AS valoracion_valor_unitario, ( SELECT c.valoracion_sector
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND ((( SELECT b.cod_ficha
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::integer) = c.cod_ficha) AS valoracion_sector, ( SELECT c.valoracion_ajuste_area
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND ((( SELECT b.cod_ficha
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::integer) = c.cod_ficha) AS valoracion_ajuste_area, ( SELECT c.valoracion_ajuste_forma
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND ((( SELECT b.cod_ficha
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::integer) = c.cod_ficha) AS valoracion_ajuste_forma, ( SELECT c.valoracion_valor_ajustado
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND ((( SELECT b.cod_ficha
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::integer) = c.cod_ficha) AS valoracion_valor_ajustado, ( SELECT c.valoracion_valor_total
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND ((( SELECT b.cod_ficha
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::integer) = c.cod_ficha) AS valoracion_valor_total, ( SELECT b.expectativa_construccion
           FROM shd600_solicitud_arrendamiento b
          WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text) AS expectativa_construccion, ( SELECT b.datos_registro_arrendamiento
           FROM shd600_aprobacion_arrendamiento b
          WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text) AS datos_registro_arrendamiento, ( SELECT b.rif_ci_cobrador
           FROM shd600_aprobacion_arrendamiento b
          WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text) AS rif_ci_cobrador, ( SELECT e.nombre_razon
           FROM shd002_cobradores e
          WHERE a.cod_presi = e.cod_presi AND a.cod_entidad = e.cod_entidad AND a.cod_tipo_inst = e.cod_tipo_inst AND a.cod_inst = e.cod_inst AND a.cod_dep = e.cod_dep AND ((( SELECT b.rif_ci_cobrador
                   FROM shd600_aprobacion_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::text) = e.rif_ci::text) AS deno_cobrador, ( SELECT b.frecuencia_pago
           FROM shd600_aprobacion_arrendamiento b
          WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text) AS frecuencia_pago, ( SELECT b.monto_mensual
           FROM shd600_aprobacion_arrendamiento b
          WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text) AS monto_mensual, ( SELECT b.pago_todo
           FROM shd600_aprobacion_arrendamiento b
          WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text) AS pago_todo, ( SELECT b.suspendido
           FROM shd600_aprobacion_arrendamiento b
          WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text) AS suspendido, ( SELECT b.ultimo_ano_facturado
           FROM shd600_aprobacion_arrendamiento b
          WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text) AS ultimo_ano_facturado, ( SELECT b.ultimo_mes_facturado
           FROM shd600_aprobacion_arrendamiento b
          WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text) AS ultimo_mes_facturado
   FROM shd600_compra_terreno a;

ALTER TABLE v_shd600_compra_terreno OWNER TO sisap;

-- View: v_shd700_credito_vivienda_parentesco

-- DROP VIEW v_shd700_credito_vivienda_parentesco;

CREATE OR REPLACE VIEW v_shd700_credito_vivienda_parentesco AS
 SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.rif_cedula, a.cod_parentesco, ( SELECT b.denominacion
           FROM cnmd06_parentesco b
          WHERE a.cod_parentesco = b.cod_parentesco) AS deno_parentesco, a.nombre_apellido, a.sexo, a.fecha_nacimiento
   FROM shd700_credito_vivienda_parentesco a;

ALTER TABLE v_shd700_credito_vivienda_parentesco OWNER TO sisap;

-- View: v_shd700_credito_vivienda

-- DROP VIEW v_shd700_credito_vivienda;

CREATE OR REPLACE VIEW v_shd700_credito_vivienda AS
 SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.rif_cedula, ( SELECT b.razon_social_nombres
           FROM v_shd001_registro_contribuyentes b
          WHERE a.rif_cedula::text = b.rif_cedula::text) AS nombre_razon, ( SELECT b.cod_pais
           FROM v_shd001_registro_contribuyentes b
          WHERE a.rif_cedula::text = b.rif_cedula::text) AS cod_pais, ( SELECT b.deno_pais
           FROM v_shd001_registro_contribuyentes b
          WHERE a.rif_cedula::text = b.rif_cedula::text) AS deno_pais, ( SELECT b.cod_estado
           FROM v_shd001_registro_contribuyentes b
          WHERE a.rif_cedula::text = b.rif_cedula::text) AS cod_estado, ( SELECT b.deno_estado
           FROM v_shd001_registro_contribuyentes b
          WHERE a.rif_cedula::text = b.rif_cedula::text) AS deno_estado, ( SELECT b.cod_municipio
           FROM v_shd001_registro_contribuyentes b
          WHERE a.rif_cedula::text = b.rif_cedula::text) AS cod_municipio, ( SELECT b.deno_municipio
           FROM v_shd001_registro_contribuyentes b
          WHERE a.rif_cedula::text = b.rif_cedula::text) AS deno_municipio, ( SELECT b.cod_parroquia
           FROM v_shd001_registro_contribuyentes b
          WHERE a.rif_cedula::text = b.rif_cedula::text) AS cod_parroquia, ( SELECT b.deno_parroquia
           FROM v_shd001_registro_contribuyentes b
          WHERE a.rif_cedula::text = b.rif_cedula::text) AS deno_parroquia, ( SELECT b.cod_centro_poblado
           FROM v_shd001_registro_contribuyentes b
          WHERE a.rif_cedula::text = b.rif_cedula::text) AS cod_centro, ( SELECT b.deno_centro
           FROM v_shd001_registro_contribuyentes b
          WHERE a.rif_cedula::text = b.rif_cedula::text) AS deno_centro, ( SELECT b.cod_calle_avenida
           FROM v_shd001_registro_contribuyentes b
          WHERE a.rif_cedula::text = b.rif_cedula::text) AS cod_calle, ( SELECT b.deno_vialidad
           FROM v_shd001_registro_contribuyentes b
          WHERE a.rif_cedula::text = b.rif_cedula::text) AS deno_calle, ( SELECT b.cod_vereda_edificio
           FROM v_shd001_registro_contribuyentes b
          WHERE a.rif_cedula::text = b.rif_cedula::text) AS cod_vereda_edificio, ( SELECT b.deno_vereda
           FROM v_shd001_registro_contribuyentes b
          WHERE a.rif_cedula::text = b.rif_cedula::text) AS deno_vereda, ( SELECT b.numero_vivienda_local
           FROM v_shd001_registro_contribuyentes b
          WHERE a.rif_cedula::text = b.rif_cedula::text) AS numero_casa, ( SELECT b.fecha_inscripcion
           FROM v_shd001_registro_contribuyentes b
          WHERE a.rif_cedula::text = b.rif_cedula::text) AS fecha_inscripcion_cont, ( SELECT b.telefonos_fijos
           FROM v_shd001_registro_contribuyentes b
          WHERE a.rif_cedula::text = b.rif_cedula::text) AS telefonos_fijos, ( SELECT b.telefonos_celulares
           FROM v_shd001_registro_contribuyentes b
          WHERE a.rif_cedula::text = b.rif_cedula::text) AS telefonos_celulares, ( SELECT b.correo_electronico
           FROM v_shd001_registro_contribuyentes b
          WHERE a.rif_cedula::text = b.rif_cedula::text) AS correo_electronico, ( SELECT b.nacionalidad
           FROM v_shd001_registro_contribuyentes b
          WHERE a.rif_cedula::text = b.rif_cedula::text) AS nacionalidad, ( SELECT b.estado_civil
           FROM v_shd001_registro_contribuyentes b
          WHERE a.rif_cedula::text = b.rif_cedula::text) AS estado_civil, ( SELECT b.deno_profesion
           FROM v_shd001_registro_contribuyentes b
          WHERE a.rif_cedula::text = b.rif_cedula::text) AS deno_profesion, a.fecha_solicitud, a.nombre_conyugue, a.cedula_conyugue, a.nombre_empresa, a.tiempo_empresa, a.telefonos_empresas, a.direccion_empresa, a.grupo_familiar, a.ingreso_mensual, a.vivienda_actual, a.tipo_vivienda, a.direccion_vivienda_credito, a.costo_vivienda, a.monto_cuota_inicial, a.monto_restante, a.factor, a.plazo_anos, a.numero_cuotas, a.monto_mensual, a.numero_contrato, a.fecha_contrato, a.frecuencia_pago, a.pago_todo, a.suspendido, a.rif_ci_cobrador, ( SELECT d.nombre_razon
           FROM shd002_cobradores d
          WHERE a.cod_presi = d.cod_presi AND a.cod_entidad = d.cod_entidad AND a.cod_tipo_inst = d.cod_tipo_inst AND a.cod_inst = d.cod_inst AND a.cod_dep = d.cod_dep AND a.rif_ci_cobrador::text = d.rif_ci::text) AS deno_cobrador, a.ultimo_ano_facturado, a.ultimo_mes_facturado, a.area_construccion, a.area_terreno, a.norte, a.sur, a.este, a.oeste, a.tasa_interes, a.fecha_entrega_contrato
   FROM shd700_credito_vivienda a;

ALTER TABLE v_shd700_credito_vivienda OWNER TO sisap;


-- View: v_shd100_declaracion_ingresos

-- DROP VIEW v_shd100_declaracion_ingresos;

CREATE OR REPLACE VIEW v_shd100_declaracion_ingresos AS
 SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep,
  a.rif_cedula, a.numero_declaracion, a.periodo_desde, a.periodo_hasta, a.capital, a.numero_empleados, a.numero_obreros, a.fecha_declaracion,
           ( SELECT b.fecha_solicitud
           FROM shd100_solicitud b
          WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_dep = b.cod_dep AND a.rif_cedula::text = b.numero_solicitud::text) AS fecha_solicitud, ( SELECT b.razon_social_nombres
           FROM v_shd001_registro_contribuyentes b
          WHERE ((( SELECT b.rif_cedula
                   FROM shd100_solicitud b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_dep = b.cod_dep AND a.rif_cedula::text = b.numero_solicitud::text))::text) = b.rif_cedula::text) AS nombre_razon, ( SELECT b.cod_pais
           FROM v_shd001_registro_contribuyentes b
          WHERE ((( SELECT b.rif_cedula
                   FROM shd100_solicitud b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_dep = b.cod_dep AND a.rif_cedula::text = b.numero_solicitud::text))::text) = b.rif_cedula::text) AS cod_pais, ( SELECT b.deno_pais
           FROM v_shd001_registro_contribuyentes b
          WHERE ((( SELECT b.rif_cedula
                   FROM shd100_solicitud b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_dep = b.cod_dep AND a.rif_cedula::text = b.numero_solicitud::text))::text) = b.rif_cedula::text) AS deno_pais, ( SELECT b.cod_estado
           FROM v_shd001_registro_contribuyentes b
          WHERE ((( SELECT b.rif_cedula
                   FROM shd100_solicitud b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_dep = b.cod_dep AND a.rif_cedula::text = b.numero_solicitud::text))::text) = b.rif_cedula::text) AS cod_estado, ( SELECT b.deno_estado
           FROM v_shd001_registro_contribuyentes b
          WHERE ((( SELECT b.rif_cedula
                   FROM shd100_solicitud b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_dep = b.cod_dep AND a.rif_cedula::text = b.numero_solicitud::text))::text) = b.rif_cedula::text) AS deno_estado, ( SELECT b.cod_municipio
           FROM v_shd001_registro_contribuyentes b
          WHERE ((( SELECT b.rif_cedula
                   FROM shd100_solicitud b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_dep = b.cod_dep AND a.rif_cedula::text = b.numero_solicitud::text))::text) = b.rif_cedula::text) AS cod_municipio, ( SELECT b.deno_municipio
           FROM v_shd001_registro_contribuyentes b
          WHERE ((( SELECT b.rif_cedula
                   FROM shd100_solicitud b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_dep = b.cod_dep AND a.rif_cedula::text = b.numero_solicitud::text))::text) = b.rif_cedula::text) AS deno_municipio, ( SELECT b.cod_parroquia
           FROM v_shd001_registro_contribuyentes b
          WHERE ((( SELECT b.rif_cedula
                   FROM shd100_solicitud b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_dep = b.cod_dep AND a.rif_cedula::text = b.numero_solicitud::text))::text) = b.rif_cedula::text) AS cod_parroquia, ( SELECT b.deno_parroquia
           FROM v_shd001_registro_contribuyentes b
          WHERE ((( SELECT b.rif_cedula
                   FROM shd100_solicitud b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_dep = b.cod_dep AND a.rif_cedula::text = b.numero_solicitud::text))::text) = b.rif_cedula::text) AS deno_parroquia, ( SELECT b.cod_centro_poblado
           FROM v_shd001_registro_contribuyentes b
          WHERE ((( SELECT b.rif_cedula
                   FROM shd100_solicitud b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_dep = b.cod_dep AND a.rif_cedula::text = b.numero_solicitud::text))::text) = b.rif_cedula::text) AS cod_centro, ( SELECT b.deno_centro
           FROM v_shd001_registro_contribuyentes b
          WHERE ((( SELECT b.rif_cedula
                   FROM shd100_solicitud b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_dep = b.cod_dep AND a.rif_cedula::text = b.numero_solicitud::text))::text) = b.rif_cedula::text) AS deno_centro, ( SELECT b.cod_calle_avenida
           FROM v_shd001_registro_contribuyentes b
          WHERE ((( SELECT b.rif_cedula
                   FROM shd100_solicitud b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_dep = b.cod_dep AND a.rif_cedula::text = b.numero_solicitud::text))::text) = b.rif_cedula::text) AS cod_calle, ( SELECT b.deno_vialidad
           FROM v_shd001_registro_contribuyentes b
          WHERE ((( SELECT b.rif_cedula
                   FROM shd100_solicitud b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_dep = b.cod_dep AND a.rif_cedula::text = b.numero_solicitud::text))::text) = b.rif_cedula::text) AS deno_calle, ( SELECT b.cod_vereda_edificio
           FROM v_shd001_registro_contribuyentes b
          WHERE ((( SELECT b.rif_cedula
                   FROM shd100_solicitud b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_dep = b.cod_dep AND a.rif_cedula::text = b.numero_solicitud::text))::text) = b.rif_cedula::text) AS cod_vereda_edificio, ( SELECT b.deno_vereda
           FROM v_shd001_registro_contribuyentes b
          WHERE ((( SELECT b.rif_cedula
                   FROM shd100_solicitud b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_dep = b.cod_dep AND a.rif_cedula::text = b.numero_solicitud::text))::text) = b.rif_cedula::text) AS deno_vereda, ( SELECT b.numero_vivienda_local
           FROM v_shd001_registro_contribuyentes b
          WHERE ((( SELECT b.rif_cedula
                   FROM shd100_solicitud b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_dep = b.cod_dep AND a.rif_cedula::text = b.numero_solicitud::text))::text) = b.rif_cedula::text) AS numero_casa, ( SELECT b.fecha_inscripcion
           FROM v_shd001_registro_contribuyentes b
          WHERE ((( SELECT b.rif_cedula
                   FROM shd100_solicitud b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_dep = b.cod_dep AND a.rif_cedula::text = b.numero_solicitud::text))::text) = b.rif_cedula::text) AS fecha_inscripcion_cont, ( SELECT b.telefonos_fijos
           FROM v_shd001_registro_contribuyentes b
          WHERE ((( SELECT b.rif_cedula
                   FROM shd100_solicitud b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_dep = b.cod_dep AND a.rif_cedula::text = b.numero_solicitud::text))::text) = b.rif_cedula::text) AS telefonos_fijos, ( SELECT b.telefonos_celulares
           FROM v_shd001_registro_contribuyentes b
          WHERE ((( SELECT b.rif_cedula
                   FROM shd100_solicitud b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_dep = b.cod_dep AND a.rif_cedula::text = b.numero_solicitud::text))::text) = b.rif_cedula::text) AS telefonos_celulares, ( SELECT b.correo_electronico
           FROM v_shd001_registro_contribuyentes b
          WHERE ((( SELECT b.rif_cedula
                   FROM shd100_solicitud b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_dep = b.cod_dep AND a.rif_cedula::text = b.numero_solicitud::text))::text) = b.rif_cedula::text) AS correo_electronico, ( SELECT b.nacionalidad
           FROM v_shd001_registro_contribuyentes b
          WHERE ((( SELECT b.rif_cedula
                   FROM shd100_solicitud b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_dep = b.cod_dep AND a.rif_cedula::text = b.numero_solicitud::text))::text) = b.rif_cedula::text) AS nacionalidad, ( SELECT b.estado_civil
           FROM v_shd001_registro_contribuyentes b
          WHERE ((( SELECT b.rif_cedula
                   FROM shd100_solicitud b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_dep = b.cod_dep AND a.rif_cedula::text = b.numero_solicitud::text))::text) = b.rif_cedula::text) AS estado_civil, ( SELECT b.deno_profesion
           FROM v_shd001_registro_contribuyentes b
          WHERE ((( SELECT b.rif_cedula
                   FROM shd100_solicitud b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_dep = b.cod_dep AND a.rif_cedula::text = b.numero_solicitud::text))::text) = b.rif_cedula::text) AS deno_profesion, ( SELECT b.fecha_patente
           FROM shd100_patente b
          WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_dep = b.cod_dep AND a.rif_cedula::text = b.numero_patente::text) AS fecha_patente, ( SELECT b.frecuencia_pago
           FROM shd100_patente b
          WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_dep = b.cod_dep AND a.rif_cedula::text = b.numero_patente::text) AS frecuencia_pago, ( SELECT b.fecha_inicio_const
           FROM shd100_solicitud b
          WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_dep = b.cod_dep AND a.rif_cedula::text = b.numero_solicitud::text) AS fecha_inicio_const, ( SELECT b.fecha_cierre_const
           FROM shd100_solicitud b
          WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_dep = b.cod_dep AND a.rif_cedula::text = b.numero_solicitud::text) AS fecha_cierre_const, ( SELECT b.fecha_inicio_econo
           FROM shd100_solicitud b
          WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_dep = b.cod_dep AND a.rif_cedula::text = b.numero_solicitud::text) AS fecha_inicio_econo, ( SELECT b.fecha_cierre_economico
           FROM shd100_solicitud b
          WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_dep = b.cod_dep AND a.rif_cedula::text = b.numero_solicitud::text) AS fecha_cierre_economico, ( SELECT b.registro_mercantil
           FROM shd100_solicitud b
          WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_dep = b.cod_dep AND a.rif_cedula::text = b.numero_solicitud::text) AS registro_mercantil
   FROM shd100_declaracion_ingresos a;

ALTER TABLE v_shd100_declaracion_ingresos OWNER TO sisap;


CREATE OR REPLACE VIEW v_cobranza AS
 SELECT
 a.cod_presi,
 a.cod_entidad,
 a.cod_tipo_inst,
 a.cod_inst,
 a.cod_dep,
 a.ano_comprobante,
 a.numero_comprobante,
 a.cod_partida,
 a.cod_generica,
 a.cod_especifica,
 a.cod_sub_espec,
 a.cod_auxiliar,
 a.fecha_comprobante,
 a.rif_cedula,
 a.concepto_comprobante,
 a.deuda_vigente,
 a.deuda_anterior,
 a.monto_recargo,
 a.monto_multa,
 a.monto_intereses,
 a.monto_descuento,
 a.cod_entidad_deposito,
 a.cod_sucursal_deposito,
 a.cuenta_bancaria_deposito,
 a.numero_deposito,
 a.monto_deposito,
 a.fecha_deposito,
 a.cod_entidad_credito,
 a.cod_sucursal_credito,
 a.cuenta_bancaria_credito,
 a.numero_nota_credito,
 a.monto_nota_credito,
 a.fecha_nota_credito,
 a.cod_entidad_cheque,
 a.cod_sucursal_cheque,
 a.cuenta_bancaria_cheque,
 a.numero_cheque,
 a.monto_cheque,
 a.fecha_cheque,
 a.monto_efectivo,
 a.condicion_documento,
 a.fecha_registro,
 a.username_registro,
 a.ano_anulacion,
 a.numero_anulacion,
 a.fecha_anulacion,
 a.username_anulacion,
 a.rif_ci_cobrador,
 a.banco_deposito,
 a.banco_nota_credito,
 a.banco_cheque,
 a.sucursal_deposito,
 a.sucursal_nota_credito,
 a.sucursal_cheque,
 a.denominacion_ingreso,
 b.personalidad_juridica,
 b.razon_social_nombres,
 b.fecha_inscripcion,
 b.nacionalidad,
 b.estado_civil,
 b.profesion,
 b.deno_profesion,
 b.cod_pais,
 b.deno_pais,
 b.cod_estado,
 b.deno_estado,
 b.cod_municipio,
 b.deno_municipio,
 b.cod_parroquia,
 b.deno_parroquia,
 b.cod_centro_poblado,
 b.deno_centro,
 b.cod_calle_avenida,
 b.deno_vialidad,
 b.cod_vereda_edificio,
 b.deno_vereda,
 b.numero_vivienda_local,
 b.telefonos_fijos,
 b.telefonos_celulares,
 b.correo_electronico,
 (((((a.numero_comprobante::text || ' '::text) || a.rif_cedula::text) || ' '::text) || quitar_acentos(b.razon_social_nombres::text)) || ' '::text) || quitar_acentos(a.denominacion_ingreso) AS denominacion_busqueda
   FROM v_shd900_cobranza_diaria a, v_shd001_registro_contribuyentes b
  WHERE b.rif_cedula::text = a.rif_cedula::text and (SELECT count(*) FROM shd900_cobranza_diaria_planillas c WHERE c.cod_presi=a.cod_presi and c.cod_entidad=a.cod_entidad and c.cod_tipo_inst=a.cod_tipo_inst and c.cod_inst=a.cod_inst and c.cod_dep=a.cod_dep and c.ano_comprobante=a.ano_comprobante and c.numero_comprobante=a.numero_comprobante) != 0;

ALTER TABLE v_cobranza OWNER TO sisap;


CREATE OR REPLACE VIEW v_cobranza_diaria AS
 SELECT
 a.cod_presi, a.cod_entidad,
 a.cod_tipo_inst,
 a.cod_inst,
 a.cod_dep,
 a.ano_comprobante,
 a.numero_comprobante,
 a.cod_partida,
 a.cod_generica,
 a.cod_especifica,
 a.cod_sub_espec,
 a.cod_auxiliar,
 a.fecha_comprobante,
 a.rif_cedula,
 a.concepto_comprobante,
 a.deuda_vigente,
 a.deuda_anterior,
 a.monto_recargo,
 a.monto_multa,
 a.monto_intereses,
 a.monto_descuento,
 a.cod_entidad_deposito,
 a.cod_sucursal_deposito,
 a.cuenta_bancaria_deposito,
 a.numero_deposito,
 a.monto_deposito,
 a.fecha_deposito,
 a.cod_entidad_credito,
 a.cod_sucursal_credito,
 a.cuenta_bancaria_credito,
 a.numero_nota_credito,
 a.monto_nota_credito,
 a.fecha_nota_credito,
 a.cod_entidad_cheque,
 a.cod_sucursal_cheque,
 a.cuenta_bancaria_cheque,
 a.numero_cheque,
 a.monto_cheque,
 a.fecha_cheque,
 a.monto_efectivo,
 a.condicion_documento,
 a.fecha_registro,
 a.username_registro,
 a.ano_anulacion,
 a.numero_anulacion,
 a.fecha_anulacion,
 a.username_anulacion,
 a.rif_ci_cobrador,
 a.banco_deposito,
 a.banco_nota_credito,
 a.banco_cheque,
 a.sucursal_deposito,
 a.sucursal_nota_credito,
 a.sucursal_cheque,
 a.denominacion_ingreso,
 b.personalidad_juridica,
b.razon_social_nombres,
b.fecha_inscripcion,
b.nacionalidad,
b.estado_civil,
b.profesion,
b.deno_profesion,
b.cod_pais,
b.deno_pais,
b.cod_estado,
b.deno_estado,
b.cod_municipio,
b.deno_municipio,
b.cod_parroquia,
b.deno_parroquia,
b.cod_centro_poblado,
b.deno_centro,
b.cod_calle_avenida,
b.deno_vialidad,
b.cod_vereda_edificio,
b.deno_vereda,
b.numero_vivienda_local,
b.telefonos_fijos,
b.telefonos_celulares,
b.correo_electronico,
(((((a.numero_comprobante::text || ' '::text) || a.rif_cedula::text) || ' '::text) || quitar_acentos(b.razon_social_nombres::text)) || ' '::text) || quitar_acentos(a.denominacion_ingreso) AS denominacion_busqueda
   FROM v_shd900_cobranza_diaria a, v_shd001_registro_contribuyentes b
  WHERE b.rif_cedula::text = a.rif_cedula::text
   AND (( SELECT count(*) AS count
           FROM shd900_cobranza_diaria_planillas c
          WHERE c.cod_presi = a.cod_presi AND c.cod_entidad = a.cod_entidad AND c.cod_tipo_inst = a.cod_tipo_inst AND c.cod_inst = a.cod_inst AND c.cod_dep = a.cod_dep AND c.ano_comprobante = a.ano_comprobante AND c.numero_comprobante = a.numero_comprobante)) = 0;

ALTER TABLE v_cobranza_diaria OWNER TO sisap;


-- View: v_shd000_control_actua_partida

-- DROP VIEW v_shd000_control_actua_partida;



CREATE OR REPLACE VIEW v_shd000_control_actua_partida AS
 SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cod_ingreso, a.ano_actualizado, a.mes_actualizado, a.condicion, b.cod_partida, b.cod_generica, b.cod_especifica, b.cod_subespec AS cod_sub_espec, b.cod_auxiliar
   FROM shd000_control_actualizacion a, shd003_codigo_ingresos b
  WHERE a.cod_ingreso = b.cod_ingreso;

ALTER TABLE v_shd000_control_actua_partida OWNER TO sisap;
COMMENT ON VIEW v_shd000_control_actua_partida IS 'vista de la union de control actualizacion con los codigos de ingresos para traerse los codigos de las partidas';


-- View: v_shd900_pdpcdc

-- DROP VIEW v_shd900_pdpcdc;

CREATE OR REPLACE VIEW v_shd900_pdpcdc AS
 SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cod_partida, a.cod_generica, a.cod_especifica, a.cod_sub_espec, a.cod_auxiliar, a.rif_cedula, a.cod_numero_catastral_placas, a.ano, a.mes, a.numero_planilla, a.deuda_vigente, a.monto_recargo, a.monto_multa, a.monto_intereses, a.monto_descuento, a.cancelado, a.fecha_emision, b.condicion
   FROM shd900_planillas_deuda_cobro_detalles a, v_shd000_control_actua_partida b
  WHERE b.cod_presi = a.cod_presi AND b.cod_entidad = a.cod_entidad AND b.cod_tipo_inst = a.cod_tipo_inst AND b.cod_inst = a.cod_inst AND b.cod_dep = a.cod_dep AND b.ano_actualizado = a.ano AND b.mes_actualizado = a.mes AND b.cod_partida = a.cod_partida AND b.cod_generica = a.cod_generica AND b.cod_especifica = a.cod_especifica AND b.cod_sub_espec = a.cod_sub_espec AND b.cod_auxiliar = a.cod_auxiliar;

ALTER TABLE v_shd900_pdpcdc OWNER TO sisap;
COMMENT ON VIEW v_shd900_pdpcdc IS 'vista de la tabla shd900_planilla_deuda_cobro_detalles
la cual se trae la condicion de la vista v_shd000_control_actua_partida';


