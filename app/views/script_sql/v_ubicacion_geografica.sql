-- View: v_casd01_ubicacion_geografica

-- DROP VIEW v_casd01_ubicacion_geografica;

CREATE OR REPLACE VIEW v_casd01_ubicacion_geografica AS
(( SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cod_estado, 0 AS cod_municipio, 0 AS cod_parroquia, 0 AS cod_centro_poblado, a.denominacion_estado AS denominacion, sum(a.numero_solicitudes) AS numero_solicitudes, sum(a.numero_ayudas) AS numero_ayudas, sum(a.monto_ayudas) AS monto_ayudas
   FROM v_casd01_relacion_solicitantes a
  GROUP BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cod_estado, a.denominacion_estado
UNION
 SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cod_estado, a.cod_municipio, 0 AS cod_parroquia, 0 AS cod_centro_poblado, a.denominacion_municipio AS denominacion, sum(a.numero_solicitudes) AS numero_solicitudes, sum(a.numero_ayudas) AS numero_ayudas, sum(a.monto_ayudas) AS monto_ayudas
   FROM v_casd01_relacion_solicitantes a
  GROUP BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cod_estado, a.cod_municipio, a.denominacion_municipio)
UNION
 SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cod_estado, a.cod_municipio, a.cod_parroquia, 0 AS cod_centro_poblado, a.denominacion_parroquia AS denominacion, sum(a.numero_solicitudes) AS numero_solicitudes, sum(a.numero_ayudas) AS numero_ayudas, sum(a.monto_ayudas) AS monto_ayudas
   FROM v_casd01_relacion_solicitantes a
  GROUP BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cod_estado, a.cod_municipio, a.cod_parroquia, a.denominacion_parroquia)
UNION
 SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cod_estado, a.cod_municipio, a.cod_parroquia, a.cod_centro_poblado, a.denominacion_centro AS denominacion, sum(a.numero_solicitudes) AS numero_solicitudes, sum(a.numero_ayudas) AS numero_ayudas, sum(a.monto_ayudas) AS monto_ayudas
   FROM v_casd01_relacion_solicitantes a
  GROUP BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cod_estado, a.cod_municipio, a.cod_parroquia, a.cod_centro_poblado, a.denominacion_centro;

ALTER TABLE v_casd01_ubicacion_geografica OWNER TO sisap;

