-- View: v_shd001_registro_contribuyentes


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

 DROP VIEW v_shd001_registro_contribuyentes CASCADE;

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


-- View: v_shd100_declaracion_ingresos

-- DROP VIEW v_shd100_declaracion_ingresos;

CREATE OR REPLACE VIEW v_shd100_declaracion_ingresos AS
 SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.numero_solicitud, a.numero_patente, a.numero_declaracion, a.periodo_desde, a.periodo_hasta, a.capital, a.numero_empleados, a.numero_obreros, a.fecha_declaracion, ( SELECT b.rif_cedula
           FROM shd100_solicitud b
          WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text) AS rif_cedula, ( SELECT b.fecha_solicitud
           FROM shd100_solicitud b
          WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text) AS fecha_solicitud, ( SELECT b.razon_social_nombres
           FROM v_shd001_registro_contribuyentes b
          WHERE ((( SELECT b.rif_cedula
                   FROM shd100_solicitud b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::text) = b.rif_cedula::text) AS nombre_razon, ( SELECT b.cod_pais
           FROM v_shd001_registro_contribuyentes b
          WHERE ((( SELECT b.rif_cedula
                   FROM shd100_solicitud b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::text) = b.rif_cedula::text) AS cod_pais, ( SELECT b.deno_pais
           FROM v_shd001_registro_contribuyentes b
          WHERE ((( SELECT b.rif_cedula
                   FROM shd100_solicitud b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::text) = b.rif_cedula::text) AS deno_pais, ( SELECT b.cod_estado
           FROM v_shd001_registro_contribuyentes b
          WHERE ((( SELECT b.rif_cedula
                   FROM shd100_solicitud b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::text) = b.rif_cedula::text) AS cod_estado, ( SELECT b.deno_estado
           FROM v_shd001_registro_contribuyentes b
          WHERE ((( SELECT b.rif_cedula
                   FROM shd100_solicitud b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::text) = b.rif_cedula::text) AS deno_estado, ( SELECT b.cod_municipio
           FROM v_shd001_registro_contribuyentes b
          WHERE ((( SELECT b.rif_cedula
                   FROM shd100_solicitud b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::text) = b.rif_cedula::text) AS cod_municipio, ( SELECT b.deno_municipio
           FROM v_shd001_registro_contribuyentes b
          WHERE ((( SELECT b.rif_cedula
                   FROM shd100_solicitud b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::text) = b.rif_cedula::text) AS deno_municipio, ( SELECT b.cod_parroquia
           FROM v_shd001_registro_contribuyentes b
          WHERE ((( SELECT b.rif_cedula
                   FROM shd100_solicitud b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::text) = b.rif_cedula::text) AS cod_parroquia, ( SELECT b.deno_parroquia
           FROM v_shd001_registro_contribuyentes b
          WHERE ((( SELECT b.rif_cedula
                   FROM shd100_solicitud b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::text) = b.rif_cedula::text) AS deno_parroquia, ( SELECT b.cod_centro_poblado
           FROM v_shd001_registro_contribuyentes b
          WHERE ((( SELECT b.rif_cedula
                   FROM shd100_solicitud b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::text) = b.rif_cedula::text) AS cod_centro, ( SELECT b.deno_centro
           FROM v_shd001_registro_contribuyentes b
          WHERE ((( SELECT b.rif_cedula
                   FROM shd100_solicitud b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::text) = b.rif_cedula::text) AS deno_centro, ( SELECT b.cod_calle_avenida
           FROM v_shd001_registro_contribuyentes b
          WHERE ((( SELECT b.rif_cedula
                   FROM shd100_solicitud b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::text) = b.rif_cedula::text) AS cod_calle, ( SELECT b.deno_vialidad
           FROM v_shd001_registro_contribuyentes b
          WHERE ((( SELECT b.rif_cedula
                   FROM shd100_solicitud b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::text) = b.rif_cedula::text) AS deno_calle, ( SELECT b.cod_vereda_edificio
           FROM v_shd001_registro_contribuyentes b
          WHERE ((( SELECT b.rif_cedula
                   FROM shd100_solicitud b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::text) = b.rif_cedula::text) AS cod_vereda_edificio, ( SELECT b.deno_vereda
           FROM v_shd001_registro_contribuyentes b
          WHERE ((( SELECT b.rif_cedula
                   FROM shd100_solicitud b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::text) = b.rif_cedula::text) AS deno_vereda, ( SELECT b.numero_vivienda_local
           FROM v_shd001_registro_contribuyentes b
          WHERE ((( SELECT b.rif_cedula
                   FROM shd100_solicitud b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::text) = b.rif_cedula::text) AS numero_casa, ( SELECT b.fecha_inscripcion
           FROM v_shd001_registro_contribuyentes b
          WHERE ((( SELECT b.rif_cedula
                   FROM shd100_solicitud b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::text) = b.rif_cedula::text) AS fecha_inscripcion_cont, ( SELECT b.telefonos_fijos
           FROM v_shd001_registro_contribuyentes b
          WHERE ((( SELECT b.rif_cedula
                   FROM shd100_solicitud b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::text) = b.rif_cedula::text) AS telefonos_fijos, ( SELECT b.telefonos_celulares
           FROM v_shd001_registro_contribuyentes b
          WHERE ((( SELECT b.rif_cedula
                   FROM shd100_solicitud b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::text) = b.rif_cedula::text) AS telefonos_celulares, ( SELECT b.correo_electronico
           FROM v_shd001_registro_contribuyentes b
          WHERE ((( SELECT b.rif_cedula
                   FROM shd100_solicitud b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::text) = b.rif_cedula::text) AS correo_electronico, ( SELECT b.nacionalidad
           FROM v_shd001_registro_contribuyentes b
          WHERE ((( SELECT b.rif_cedula
                   FROM shd100_solicitud b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::text) = b.rif_cedula::text) AS nacionalidad, ( SELECT b.estado_civil
           FROM v_shd001_registro_contribuyentes b
          WHERE ((( SELECT b.rif_cedula
                   FROM shd100_solicitud b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::text) = b.rif_cedula::text) AS estado_civil, ( SELECT b.deno_profesion
           FROM v_shd001_registro_contribuyentes b
          WHERE ((( SELECT b.rif_cedula
                   FROM shd100_solicitud b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::text) = b.rif_cedula::text) AS deno_profesion, ( SELECT b.fecha_patente
           FROM shd100_patente b
          WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_dep = b.cod_dep AND a.numero_patente::text = b.numero_patente::text) AS fecha_patente, ( SELECT b.frecuencia_pago
           FROM shd100_patente b
          WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_dep = b.cod_dep AND a.numero_patente::text = b.numero_patente::text) AS frecuencia_pago, ( SELECT b.fecha_inicio_const
           FROM shd100_solicitud b
          WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text) AS fecha_inicio_const, ( SELECT b.fecha_cierre_const
           FROM shd100_solicitud b
          WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text) AS fecha_cierre_const, ( SELECT b.fecha_inicio_econo
           FROM shd100_solicitud b
          WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text) AS fecha_inicio_econo, ( SELECT b.fecha_cierre_economico
           FROM shd100_solicitud b
          WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text) AS fecha_cierre_economico, ( SELECT b.registro_mercantil
           FROM shd100_solicitud b
          WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text) AS registro_mercantil
   FROM shd100_declaracion_ingresos a;

ALTER TABLE v_shd100_declaracion_ingresos OWNER TO sisap;



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
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::text) = c.rif_cedula::text) AS deno_razon, ( SELECT c.nacionalidad
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


-- View: v_shd100_declaracion_actividades

 DROP VIEW v_shd100_declaracion_actividades;

CREATE OR REPLACE VIEW v_shd100_declaracion_actividades AS
 SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.numero_solicitud, ( SELECT b.rif_cedula
           FROM shd100_solicitud b
          WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text) AS rif_cedula, a.numero_patente, a.numero_declaracion, a.cod_actividad, ( SELECT b.denominacion_actividad
           FROM shd100_actividades b
          WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.cod_actividad::text = b.cod_actividad::text) AS deno_actividad, a.monto_ingresos, a.monto_impuesto
   FROM shd100_declaracion_actividades a;

ALTER TABLE v_shd100_declaracion_actividades OWNER TO sisap;



-- View: v_shd100_patente_actividades

 DROP VIEW v_shd100_patente_actividades;

CREATE OR REPLACE VIEW v_shd100_patente_actividades AS
 SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.numero_solicitud, a.numero_patente, a.cod_actividad, ( SELECT b.denominacion_actividad
           FROM shd100_actividades b
          WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.cod_actividad::text = b.cod_actividad::text) AS deno_actividad, a.numero_aforos, a.monto_aforo_anual, a.total_aforo_anual
   FROM shd100_patente_actividades a;

ALTER TABLE v_shd100_patente_actividades OWNER TO sisap;

-- View: v_shd100_solicitud

 DROP VIEW v_shd100_solicitud;

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

-- View: v_shd100_solicitud_actividades

 DROP VIEW v_shd100_solicitud_actividades;

CREATE OR REPLACE VIEW v_shd100_solicitud_actividades AS
 SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.numero_solicitud, a.cod_actividad, b.denominacion_actividad, b.alicuota, b.unidades_tributarias, b.minimo_tributable
   FROM shd100_solicitud_actividades a, shd100_actividades b
  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.cod_actividad::text = b.cod_actividad::text;

ALTER TABLE v_shd100_solicitud_actividades OWNER TO sisap;

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
 SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.rif_cedula, a.fecha_registro, ( SELECT b.razon_social_nombres
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

 --DROP VIEW v_shd600_compra_terreno;

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

-- View: v_shd600_solicitud_arrendamiento

 --DROP VIEW v_shd600_solicitud_arrendamiento;

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

-- View: v_shd700_credito_vivienda_parentesco

 DROP VIEW v_shd700_credito_vivienda_parentesco;

CREATE OR REPLACE VIEW v_shd700_credito_vivienda_parentesco AS
 SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.rif_cedula, a.cod_parentesco, ( SELECT b.denominacion
           FROM cnmd06_parentesco b
          WHERE a.cod_parentesco = b.cod_parentesco) AS deno_parentesco, a.nombre_apellido, a.sexo, a.fecha_nacimiento
   FROM shd700_credito_vivienda_parentesco a;

ALTER TABLE v_shd700_credito_vivienda_parentesco OWNER TO sisap;



--DROP VIEW v_grilla_constribuyentes;
CREATE OR REPLACE VIEW v_grilla_constribuyentes AS
(((( SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.numero_solicitud, ( SELECT b.rif_cedula
           FROM shd100_solicitud b
          WHERE b.cod_presi = a.cod_presi AND b.cod_entidad = a.cod_entidad AND b.cod_tipo_inst = a.cod_tipo_inst AND b.cod_inst = a.cod_inst AND b.cod_dep = a.cod_dep AND b.numero_solicitud::text = a.numero_solicitud::text) AS rif_cedula, a.frecuencia_pago, a.monto_mensual * 12::numeric AS monto, 1 AS tipo
   FROM shd100_patente a
UNION
 SELECT v_shd200_vehiculos.cod_presi, v_shd200_vehiculos.cod_entidad, v_shd200_vehiculos.cod_tipo_inst, v_shd200_vehiculos.cod_inst, v_shd200_vehiculos.cod_dep, 0::character varying(20) AS numero_solicitud, v_shd200_vehiculos.rif_cedula, v_shd200_vehiculos.frecuencia_pago, v_shd200_vehiculos.monto_anual::numeric AS monto, 2 AS tipo
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