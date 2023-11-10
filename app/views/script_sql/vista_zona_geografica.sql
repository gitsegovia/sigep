
CREATE OR REPLACE VIEW v_relacion_zonageografica AS

SELECT
   a.cod_republica,
  (0) as cod_estado,
  (0) as cod_municipio,
  (0) as cod_parroquia,
  (0) as cod_centro,
  (0) as cod_vialidad,
  (0) as cod_vereda,
   a.denominacion
FROM cugd01_republica a

UNION

SELECT
   a.cod_republica,
   a.cod_estado,
  (0) as cod_municipio,
  (0) as cod_parroquia,
  (0) as cod_centro,
  (0) as cod_vialidad,
  (0) as cod_vereda,
   a.denominacion
FROM cugd01_estados a


UNION

SELECT
   a.cod_republica,
   a.cod_estado,
   a.cod_municipio,
  (0) as cod_parroquia,
  (0) as cod_centro,
  (0) as cod_vialidad,
  (0) as cod_vereda,
   a.denominacion
FROM cugd01_municipios a


UNION

SELECT
   a.cod_republica,
   a.cod_estado,
   a.cod_municipio,
   a.cod_parroquia,
  (0) as cod_centro,
  (0) as cod_vialidad,
  (0) as cod_vereda,
   a.denominacion
FROM cugd01_parroquias a


UNION

SELECT
   a.cod_republica,
   a.cod_estado,
   a.cod_municipio,
   a.cod_parroquia,
   a.cod_centro,
  (0) as cod_vialidad,
  (0) as cod_vereda,
   a.denominacion
FROM cugd01_centros_poblados a


UNION

SELECT
   a.cod_republica,
   a.cod_estado,
   a.cod_municipio,
   a.cod_parroquia,
   a.cod_centro,
   a.cod_vialidad,
  (0) as cod_vereda,
   a.denominacion
FROM cugd01_vialidad a


UNION

SELECT
   a.cod_republica,
   a.cod_estado,
   a.cod_municipio,
   a.cod_parroquia,
   a.cod_centro,
   a.cod_vialidad,
   a.cod_vereda,
   a.denominacion
FROM cugd01_vereda a;


ALTER TABLE v_relacion_zonageografica OWNER TO sisap;
