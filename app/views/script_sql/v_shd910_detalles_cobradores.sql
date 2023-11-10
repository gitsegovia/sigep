


--DROP VIEW v_shd900_planillas_deuda_cobro_detalles_cobradores;


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
  a.numero_recibo,
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







--DROP VIEW v_shd900_planillas_deuda_cobro_detalles_cobradores_2;


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
  a.numero_recibo,
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
  a.numero_recibo,
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
  a.numero_recibo,
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
  a.numero_recibo,
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
  a.numero_recibo,
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
  a.numero_recibo,
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
  a.numero_recibo,
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








