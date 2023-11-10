



CREATE OR REPLACE VIEW v_ccnd01_directiva AS


SELECT

  a.cod_republica,
  a.cod_estado,
  a.cod_municipio,
  a.cod_parroquia,
  a.cod_centro,
  a.cod_concejo,
  a.cod_tipo,
  (select x.denominacion from ccnd01_tipo_directivo x where x.cod_tipo = a.cod_tipo) as deno_cod_tipo,
  a.cod_cargo,
  (select xx.denominacion from ccnd01_cargos_directivos xx where xx.cod_tipo = a.cod_tipo and xx.cod_cargo = a.cod_cargo) as deno_cod_cargo,
  a.cedula_identidad,
  a.nacionalidad,
  a.apellidos_nombres,
  a.fecha_nacimiento,
  a.sexo,
  a.estado_civil,
  a.peso,
  a.estatura,
  a.grupo_sanguineo,
  a.cod_profesion,
  a.cod_ocupacion,
  a.cod_vivienda,
  a.cod_tenencia_vivienda,
  a.anos_residencia,
  a.monto_alquiler_hipoteca ,
  a.cod_mision,
  a.direccion_habitacion,
  a.telefonos_fijos,
  a.telefonos_moviles,
  a.estado_conservacion_vivienda

FROM ccnd01_directiva a;

ALTER TABLE v_ccnd01_directiva OWNER TO sisap;