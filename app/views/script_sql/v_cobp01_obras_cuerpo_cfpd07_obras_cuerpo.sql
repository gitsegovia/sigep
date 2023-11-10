
CREATE OR REPLACE VIEW v_cobp01_obras_cuerpo_cfpd07_obras_cuerpo AS


SELECT

      b.cod_presi,
	  b.cod_entidad,
	  b.cod_tipo_inst,
	  b.cod_inst,
	  b.cod_dep,
      b.cod_dep_original,
      (SELECT denominacion FROM cugd02_dependencias dep WHERE dep.cod_tipo_institucion = b.cod_tipo_inst and dep.cod_institucion  =  b.cod_inst and dep.cod_dependencia   =  b.cod_dep) as denominacion_dep,
	  (SELECT denominacion FROM cugd02_dependencias dep WHERE dep.cod_tipo_institucion = b.cod_tipo_inst and dep.cod_institucion  =  b.cod_inst and dep.cod_dependencia   =  b.cod_dep_original) as denominacion_dep_original,
	  b.ano_estimacion,
	  b.cod_obra,
	  b.denominacion,
	  b.funcionario_responsable,
	  b.fecha_inicio,
	  b.fecha_conclusion,
	  b.situacion,
	  b.costo_total,
	  b.compro_ano_ante,
	  b.compro_ano_vige,
	  b.ejecuta_ano_ante,
	  b.ejecuta_ano_vige,
	  b.estimado_presu,
	  b.estimado_ano_posterior,
	  b.tipo_recurso,
	  b.clasificacion_recurso,
	  b.situacion_contratacion,
	  b.monto_contratado,
	  b.codigo_prod_serv,
	  b.ano_plan,
	  b.status,
	  b.aumento_obras,
	  b.disminucion_obras,
	  b.pertenece_plan_inversion,
	  a.ano_contrato_obra,
	  a.numero_contrato_obra,
	  a.cod_estado,
	  a.cod_municipio,
	  a.cod_parroquia,
	  a.cod_centro,
      (SELECT xya.denominacion FROM cugd01_estados          xya where xya.cod_republica=a.cod_presi and xya.cod_estado=a.cod_estado                                                                                                                  GROUP BY xya.denominacion) as  deno_cod_estado,
	  (SELECT xyb.denominacion FROM cugd01_municipios       xyb where xyb.cod_republica=a.cod_presi and xyb.cod_estado=a.cod_estado  and xyb.cod_municipio=a.cod_municipio                                                                           GROUP BY xyb.denominacion) as  deno_cod_municipio,
	  (SELECT xyc.denominacion FROM cugd01_parroquias       xyc where xyc.cod_republica=a.cod_presi and xyc.cod_estado=a.cod_estado  and xyc.cod_municipio=a.cod_municipio and xyc.cod_parroquia = a.cod_parroquia                                   GROUP BY xyc.denominacion) as  deno_cod_parroquia,
	  (SELECT xyd.denominacion FROM cugd01_centros_poblados xyd where xyd.cod_republica=a.cod_presi and xyd.cod_estado=a.cod_estado  and xyd.cod_municipio=a.cod_municipio and xyd.cod_parroquia = a.cod_parroquia and xyd.cod_centro = a.cod_centro GROUP BY xyd.denominacion) as  deno_cod_centro,
	  a.especifique_ubicacion,
	  a.otorgamiento,
	  a.denominacion_obra,
	  a.rif,
      (SELECT xb.denominacion   FROM cpcd02 xb WHERE xb.rif = a.rif ) as denominacion_rif,
	  a.fecha_contrato_obra,
	  a.fecha_inicio_contrato,
	  a.fecha_terminacion_contrato,
	  a.monto_original_contrato,
	  a.aumento,
	  a.disminucion,
	  a.monto_anticipo,
	  a.monto_amortizacion,
	  a.monto_retencion_laboral,
	  a.monto_retencion_fielcumplimiento,
	  a.monto_cancelado,
	  a.porcentaje_iva,
	  a.porcentaje_anticipo,
	  a.anticipo_con_iva,
	  a.fecha_proceso_registro,
	  a.dia_asiento_registro,
	  a.mes_asiento_registro,
	  a.ano_asiento_registro,
	  a.numero_asiento_registro,
	  a.username_registro,
	  a.condicion_actividad,
	  a.ano_anulacion,
	  a.numero_anulacion,
	  a.dia_asiento_anulacion,
	  a.mes_asiento_anulacion,
	  a.ano_asiento_anulacion,
	  a.fecha_proceso_anulacion,
	  a.username_anulacion,
	  a.fielcumplimiento_cancelado,
	  a.laboral_cancelado,
	  a.numero_buenapro,
	  a.fecha_buenapro,
	  a.numero_fianza_anticipo,
	  a.fecha_fianza_anticipo,
	  a.numero_fianza_fielcumplimiento,
	  a.fecha_fianza_fielcumplimiento,
	  a.numero_fianza_calidad,
	  a.fecha_fianza_calidad,
	  a.numero_asiento_anulacion


FROM cobd01_contratoobras_cuerpo a, cfpd07_obras_cuerpo b


WHERE

       b.cod_presi            =  a.cod_presi                and
	   b.cod_entidad          =  a.cod_entidad              and
	   b.cod_tipo_inst        =  a.cod_tipo_inst            and
	   b.cod_inst             =  a.cod_inst                 and
       b.cod_dep              =  a.cod_dep                  and
	   b.ano_estimacion       =  a.ano_estimacion           and
	   b.cod_obra             =  a.cod_obra

ORDER BY

       a.cod_presi,
	   a.cod_entidad,
	   a.cod_tipo_inst,
	   a.cod_inst,
       a.cod_dep,
       b.cod_dep_original,
	   a.ano_estimacion,
	   a.cod_obra;



ALTER TABLE v_cobp01_obras_cuerpo_cfpd07_obras_cuerpo OWNER TO sisap;








