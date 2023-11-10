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

