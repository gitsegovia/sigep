DROP VIEW v_cfpd07_cuerpo_vs_cobd01_cuerpo;


CREATE OR REPLACE VIEW v_cfpd07_cuerpo_vs_cobd01_cuerpo AS


SELECT

      a.cod_presi,
	  a.cod_entidad,
	  a.cod_tipo_inst,
	  a.cod_inst,
	  a.cod_dep,
      a.cod_dep_original,
      (SELECT denominacion FROM cugd02_dependencias dep WHERE dep.cod_tipo_institucion = a.cod_tipo_inst and dep.cod_institucion  =  a.cod_inst and dep.cod_dependencia   =  a.cod_dep) as denominacion_dep,
	  (SELECT denominacion FROM cugd02_dependencias dep WHERE dep.cod_tipo_institucion = a.cod_tipo_inst and dep.cod_institucion  =  a.cod_inst and dep.cod_dependencia   =  a.cod_dep_original) as denominacion_dep_original,

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

      (SELECT dd.denominacion FROM cscd01_catalogo dd WHERE dd.codigo_prod_serv    =  a.codigo_prod_serv ) as deno_codigo_prod_serv,


      (SELECT ddd.denominacion FROM cscd01_snc_tipo ddd WHERE
                                                              ddd.cod_tipo=(SELECT dddd.cod_snc FROM cscd01_catalogo dddd WHERE dddd.codigo_prod_serv=a.codigo_prod_serv)

                                                             ) as deno_cod_snc,

     (SELECT dddd.cod_snc FROM cscd01_catalogo dddd WHERE dddd.codigo_prod_serv=a.codigo_prod_serv) as cod_snc,


	  a.ano_estimacion,
	  a.cod_obra,
	  a.denominacion,
	  a.funcionario_responsable,
	  a.fecha_inicio,
	  a.fecha_conclusion,
	  a.situacion,
	  a.costo_total,
	  a.compro_ano_ante,
	  a.compro_ano_vige,
	  a.ejecuta_ano_ante,
	  a.ejecuta_ano_vige,
	  a.estimado_presu,
	  a.estimado_ano_posterior,
	  a.tipo_recurso,
	  a.clasificacion_recurso,
	  a.situacion_contratacion,
	  a.monto_contratado,
	  a.codigo_prod_serv,
	  a.ano_plan,
	  a.status,
	  a.aumento_obras,
	  a.disminucion_obras,

	  null_cero((SELECT SUM(b.monto_anticipo) FROM cobd01_contratoobras_cuerpo b where
		                                                                       b.cod_presi            =  a.cod_presi                and
																			   b.cod_entidad          =  a.cod_entidad              and
																			   b.cod_tipo_inst        =  a.cod_tipo_inst            and
																			   b.cod_inst             =  a.cod_inst                 and
																		       b.cod_dep              =  a.cod_dep                  and
																			   b.ano_estimacion       =  a.ano_estimacion           and
																			   b.cod_obra             =  a.cod_obra
																			   GROUP BY
																					   b.cod_presi,
																					   b.cod_entidad,
																					   b.cod_tipo_inst,
																					   b.cod_inst,
																				       b.cod_dep,
																					   b.ano_estimacion,
																					   b.cod_obra
																			   )) as cobd01_monto_anticipo,

	  null_cero((SELECT SUM(c.monto_amortizacion) FROM cobd01_contratoobras_cuerpo c where
		                                                                       c.cod_presi            =  a.cod_presi                and
																			   c.cod_entidad          =  a.cod_entidad              and
																			   c.cod_tipo_inst        =  a.cod_tipo_inst            and
																			   c.cod_inst             =  a.cod_inst                 and
																		       c.cod_dep              =  a.cod_dep                  and
																			   c.ano_estimacion       =  a.ano_estimacion           and
																			   c.cod_obra             =  a.cod_obra
																			   GROUP BY
																			           c.cod_presi,
																					   c.cod_entidad,
																					   c.cod_tipo_inst,
																					   c.cod_inst,
																				       c.cod_dep,
																					   c.ano_estimacion,
																					   c.cod_obra
																			   )) as cobd01_monto_amortizacion,

       null_cero((SELECT SUM(d.monto_retencion_laboral) FROM cobd01_contratoobras_cuerpo d where
		                                                                       d.cod_presi            =  a.cod_presi                and
																			   d.cod_entidad          =  a.cod_entidad              and
																			   d.cod_tipo_inst        =  a.cod_tipo_inst            and
																			   d.cod_inst             =  a.cod_inst                 and
																		       d.cod_dep              =  a.cod_dep                  and
																			   d.ano_estimacion       =  a.ano_estimacion           and
																			   d.cod_obra             =  a.cod_obra
																			   GROUP BY
																			           d.cod_presi,
																					   d.cod_entidad,
																					   d.cod_tipo_inst,
																					   d.cod_inst,
																				       d.cod_dep,
																					   d.ano_estimacion,
																					   d.cod_obra
																			   )) as cobd01_monto_retencion_laboral,

       null_cero((SELECT SUM(e.monto_retencion_fielcumplimiento) FROM cobd01_contratoobras_cuerpo e where
		                                                                       e.cod_presi            =  a.cod_presi                and
																			   e.cod_entidad          =  a.cod_entidad              and
																			   e.cod_tipo_inst        =  a.cod_tipo_inst            and
																			   e.cod_inst             =  a.cod_inst                 and
																		       e.cod_dep              =  a.cod_dep                  and
																			   e.ano_estimacion       =  a.ano_estimacion           and
																			   e.cod_obra             =  a.cod_obra
																			   GROUP BY
																			           e.cod_presi,
																					   e.cod_entidad,
																					   e.cod_tipo_inst,
																					   e.cod_inst,
																				       e.cod_dep,
																					   e.ano_estimacion,
																					   e.cod_obra
																			   )) as cobd01_monto_retencion_fielcumplimiento,

       null_cero((SELECT SUM(f.monto_cancelado) FROM cobd01_contratoobras_cuerpo f where
		                                                                       f.cod_presi            =  a.cod_presi                and
																			   f.cod_entidad          =  a.cod_entidad              and
																			   f.cod_tipo_inst        =  a.cod_tipo_inst            and
																			   f.cod_inst             =  a.cod_inst                 and
																		       f.cod_dep              =  a.cod_dep                  and
																			   f.ano_estimacion       =  a.ano_estimacion           and
																			   f.cod_obra             =  a.cod_obra
																			   GROUP BY
																			           f.cod_presi,
																					   f.cod_entidad,
																					   f.cod_tipo_inst,
																					   f.cod_inst,
																				       f.cod_dep,
																					   f.ano_estimacion,
																					   f.cod_obra
																			   )) as cobd01_monto_cancelado,

		 null_cero((SELECT SUM(g.monto_original_contrato) FROM cobd01_contratoobras_cuerpo g where
		                                                                       g.cod_presi            =  a.cod_presi                and
																			   g.cod_entidad          =  a.cod_entidad              and
																			   g.cod_tipo_inst        =  a.cod_tipo_inst            and
																			   g.cod_inst             =  a.cod_inst                 and
																		       g.cod_dep              =  a.cod_dep                  and
																			   g.ano_estimacion       =  a.ano_estimacion           and
																			   g.cod_obra             =  a.cod_obra
																			   GROUP BY
																			           g.cod_presi,
																					   g.cod_entidad,
																					   g.cod_tipo_inst,
																					   g.cod_inst,
																				       g.cod_dep,
																					   g.ano_estimacion,
																					   g.cod_obra
																			   )) as cobd01_monto_original_contrato,

           null_cero((SELECT SUM(h.aumento) FROM cobd01_contratoobras_cuerpo h where
		                                                                       h.cod_presi            =  a.cod_presi                and
																			   h.cod_entidad          =  a.cod_entidad              and
																			   h.cod_tipo_inst        =  a.cod_tipo_inst            and
																			   h.cod_inst             =  a.cod_inst                 and
																		       h.cod_dep              =  a.cod_dep                  and
																			   h.ano_estimacion       =  a.ano_estimacion           and
																			   h.cod_obra             =  a.cod_obra
																			   GROUP BY
																			           h.cod_presi,
																					   h.cod_entidad,
																					   h.cod_tipo_inst,
																					   h.cod_inst,
																				       h.cod_dep,
																					   h.ano_estimacion,
																					   h.cod_obra
																			   )) as cobd01_aumento,

              null_cero((SELECT SUM(i.disminucion) FROM cobd01_contratoobras_cuerpo i where
		                                                                       i.cod_presi            =  a.cod_presi                and
																			   i.cod_entidad          =  a.cod_entidad              and
																			   i.cod_tipo_inst        =  a.cod_tipo_inst            and
																			   i.cod_inst             =  a.cod_inst                 and
																		       i.cod_dep              =  a.cod_dep                  and
																			   i.ano_estimacion       =  a.ano_estimacion           and
																			   i.cod_obra             =  a.cod_obra
																			   GROUP BY
																			           i.cod_presi,
																					   i.cod_entidad,
																					   i.cod_tipo_inst,
																					   i.cod_inst,
																				       i.cod_dep,
																					   i.ano_estimacion,
																					   i.cod_obra
																			   )) as cobd01_disminucion

FROM cfpd07_obras_cuerpo a


ORDER BY

       a.cod_presi,
	   a.cod_entidad,
	   a.cod_tipo_inst,
	   a.cod_inst,
       a.cod_dep,
       a.cod_dep_original,
	   a.ano_estimacion,
	   a.cod_obra;



ALTER TABLE v_cfpd07_cuerpo_vs_cobd01_cuerpo OWNER TO sisap;








