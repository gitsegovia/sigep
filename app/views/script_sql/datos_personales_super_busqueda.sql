-- View: datos_personales_super_busqueda

DROP VIEW datos_personales_super_busqueda;

CREATE OR REPLACE VIEW datos_personales_super_busqueda AS

 SELECT

 b.cedula_identidad,
 b.nacionalidad,
 b.primer_apellido,
 b.segundo_apellido,
 b.primer_nombre,
 b.segundo_nombre,
 b.fecha_nacimiento,
 b.sexo,
 b.estado_civil,
 b.grupo_sanguineo,
 b.peso_kilos,
 b.estatura_metros,
 b.naturalizado,
 b.fecha_naturalizacion,
 b.numero_gaceta,
 b.idioma,
 b.cod_profesion,
 b.cod_especialidad,
 b.cod_oficio,
 b.direccion_habitacion,
 b.telefonos_habitacion,
 b.otra_direccion_hab,
 b.otros_telefonos,
 b.correo_electronico,
 b.numero_inscripcion_sso,
 b.numero_inscripcion_lph,
 b.grado_licencia_conducir,
 b.numero_licencia_conducir,
 b.usa_lentes,
 b.talla_camisa_blusa,
 b.talla_pantalon_falda,
 b.talla_calzado,
 b.talla_keppy,
 b.deporte_practica,
 b.religion_pertenece,
 b.club_pertenece,
 b.hobby_favorito,
 b.color_favorito,
 b.cod_pais_origen,
 b.cod_estado_origen,
 b.cod_municipio_origen,
 b.cod_parroquia_origen,
 b.cod_centropoblado_origen,
 b.cod_estado_habitacion,
 b.cod_municipio_habitacion,
 b.cod_parroquia_habitacion,
 b.cod_centropoblado_habitacion,
  (SELECT z.denominacion   FROM cnmd06_profesiones z         WHERE b.cod_profesion = z.cod_profesion) AS denominacion_profesion,
  ( SELECT y.denominacion   FROM cnmd06_especialidades y     WHERE b.cod_profesion = y.cod_profesion AND b.cod_especialidad = y.cod_especialidad) AS denominacion_especialidad,
  ( SELECT x.denominacion   FROM cnmd06_oficio x             WHERE b.cod_oficio = x.cod_oficio) AS denominacion_oficio,
  ( SELECT v.denominacion   FROM cnmd06_deportes v           WHERE b.deporte_practica = v.cod_deporte) AS denominacion_deporte,
  ( SELECT w.denominacion   FROM cnmd06_religiones w         WHERE b.religion_pertenece = w.cod_religion) AS denominacion_religion,
  ( SELECT u.denominacion   FROM cnmd06_clubes u             WHERE b.club_pertenece = u.cod_club) AS denominacion_club,
  ( SELECT t.denominacion   FROM cnmd06_hobby t              WHERE b.hobby_favorito = t.cod_hobby) AS denominacion_hobby,
  ( SELECT s.denominacion   FROM cnmd06_colores s            WHERE b.color_favorito = s.cod_color) AS denominacion_color,
  ( SELECT r.denominacion   FROM cugd01_republica r          WHERE b.cod_pais_origen = r.cod_republica) AS denominacion_pais_origen,
  ( SELECT q.denominacion   FROM cugd01_estados q            WHERE b.cod_pais_origen = q.cod_republica   AND b.cod_estado_origen = q.cod_estado) AS denominacion_estado_origen,
  ( SELECT p.denominacion   FROM cugd01_municipios p         WHERE b.cod_pais_origen = p.cod_republica   AND b.cod_estado_origen = p.cod_estado   AND b.cod_municipio_origen = p.cod_municipio) AS denominacion_municipio_origen,
  ( SELECT pp.denominacion  FROM cugd01_parroquias pp        WHERE b.cod_pais_origen = pp.cod_republica  AND b.cod_estado_origen = pp.cod_estado  AND b.cod_municipio_origen = pp.cod_municipio  AND b.cod_parroquia_origen = pp.cod_parroquia) AS denominacion_parroquia_origen,
  ( SELECT ppp.denominacion FROM cugd01_centros_poblados ppp WHERE b.cod_pais_origen = ppp.cod_republica AND b.cod_estado_origen = ppp.cod_estado AND b.cod_municipio_origen = ppp.cod_municipio AND b.cod_parroquia_origen = ppp.cod_parroquia AND b.cod_centropoblado_origen = ppp.cod_centro) AS denominacion_centropoblado_origen,
  ( SELECT zz.denominacion  FROM cugd01_estados zz           WHERE b.cod_pais_origen = zz.cod_republica  AND b.cod_estado_habitacion = zz.cod_estado) AS denominacion_estado_habitacion,
  ( SELECT yy.denominacion  FROM cugd01_municipios yy        WHERE b.cod_pais_origen = yy.cod_republica  AND b.cod_estado_habitacion = yy.cod_estado AND b.cod_municipio_habitacion = yy.cod_municipio) AS denominacion_municipio_habitacion,
  ( SELECT xx.denominacion  FROM cugd01_parroquias xx        WHERE b.cod_pais_origen = xx.cod_republica  AND b.cod_estado_habitacion = xx.cod_estado AND b.cod_municipio_habitacion = xx.cod_municipio AND b.cod_parroquia_habitacion = xx.cod_parroquia) AS denominacion_parroquia_habitacion,
  ( SELECT ww.denominacion  FROM cugd01_centros_poblados ww  WHERE b.cod_pais_origen = ww.cod_republica  AND b.cod_estado_habitacion = ww.cod_estado AND b.cod_municipio_habitacion = ww.cod_municipio AND b.cod_parroquia_habitacion = ww.cod_parroquia AND b.cod_centropoblado_habitacion = ww.cod_centro) AS denominacion_centropoblado_habitacion,
  b.condicion_actual,
  ((((((((b.cedula_identidad || ' '::text) || b.primer_apellido::text) || ' '::text) || b.segundo_apellido::text) || ' '::text) || b.primer_nombre::text) || ' '::text) || b.segundo_nombre::text) || ' '::text as denominacion_busqueda,

  (((((((((((((((((((((((((((((((((((((((((((

                     (b.cedula_identidad || ' '::text) ||
                      b.primer_apellido::text) || ' '::text) ||
                      b.segundo_apellido::text) || ' '::text) ||
                      b.primer_nombre::text) || ' '::text) ||
                      b.segundo_nombre::text) || ' '::text)) || ' '::text) ||
          text_null_(( SELECT z.denominacion   FROM cnmd06_profesiones z        WHERE b.cod_profesion = z.cod_profesion)::text)) || ' '::text) ||
          text_null_(( SELECT y.denominacion   FROM cnmd06_especialidades y     WHERE b.cod_profesion = y.cod_profesion AND b.cod_especialidad = y.cod_especialidad)::text)) || ' '::text) ||
          text_null_(( SELECT x.denominacion   FROM cnmd06_oficio x             WHERE b.cod_oficio = x.cod_oficio)::text) || ' '::text)) ||
          text_null_(( SELECT v.denominacion   FROM cnmd06_deportes v           WHERE b.deporte_practica = v.cod_deporte)::text) || ' '::text)) ||
          text_null_(( SELECT w.denominacion   FROM cnmd06_religiones w         WHERE b.religion_pertenece = w.cod_religion)::text) || ' '::text)) ||
          text_null_(( SELECT u.denominacion   FROM cnmd06_clubes u             WHERE b.club_pertenece = u.cod_club)::text)) || ' '::text) ||
          text_null_(( SELECT t.denominacion   FROM cnmd06_hobby t              WHERE b.hobby_favorito = t.cod_hobby)::text)) || ' '::text) ||
          text_null_(( SELECT s.denominacion   FROM cnmd06_colores s            WHERE b.color_favorito = s.cod_color)::text)) || ' '::text) ||
          text_null_(( SELECT r.denominacion   FROM cugd01_republica r          WHERE b.cod_pais_origen = r.cod_republica)::text)) || ' '::text) ||
          text_null_(( SELECT q.denominacion   FROM cugd01_estados q            WHERE b.cod_pais_origen = q.cod_republica   AND b.cod_estado_origen = q.cod_estado)::text)) || ' '::text) ||
          text_null_(( SELECT p.denominacion   FROM cugd01_municipios p         WHERE b.cod_pais_origen = p.cod_republica   AND b.cod_estado_origen = p.cod_estado   AND b.cod_municipio_origen = p.cod_municipio)::text)) || ' '::text) ||
          text_null_(( SELECT pp.denominacion  FROM cugd01_parroquias pp        WHERE b.cod_pais_origen = pp.cod_republica  AND b.cod_estado_origen = pp.cod_estado  AND b.cod_municipio_origen = pp.cod_municipio  AND b.cod_parroquia_origen = pp.cod_parroquia)::text)) || ' '::text) ||
          text_null_(( SELECT ppp.denominacion FROM cugd01_centros_poblados ppp WHERE b.cod_pais_origen = ppp.cod_republica AND b.cod_estado_origen = ppp.cod_estado AND b.cod_municipio_origen = ppp.cod_municipio AND b.cod_parroquia_origen = ppp.cod_parroquia AND b.cod_centropoblado_origen = ppp.cod_centro)::text)) || ' '::text) ||
          text_null_(( SELECT zz.denominacion  FROM cugd01_estados zz           WHERE b.cod_pais_origen = zz.cod_republica  AND b.cod_estado_habitacion = zz.cod_estado)::text)) || ' '::text) ||
          text_null_(( SELECT yy.denominacion  FROM cugd01_municipios yy        WHERE b.cod_pais_origen = yy.cod_republica  AND b.cod_estado_habitacion = yy.cod_estado AND b.cod_municipio_habitacion = yy.cod_municipio)::text)) || ' '::text) ||
          text_null_(( SELECT xx.denominacion  FROM cugd01_parroquias xx        WHERE b.cod_pais_origen = xx.cod_republica  AND b.cod_estado_habitacion = xx.cod_estado AND b.cod_municipio_habitacion = xx.cod_municipio AND b.cod_parroquia_habitacion = xx.cod_parroquia)::text)) || ' '::text) ||
          text_null_(( SELECT ww.denominacion  FROM cugd01_centros_poblados ww  WHERE b.cod_pais_origen = ww.cod_republica  AND b.cod_estado_habitacion = ww.cod_estado AND b.cod_municipio_habitacion = ww.cod_municipio AND b.cod_parroquia_habitacion = ww.cod_parroquia AND b.cod_centropoblado_habitacion = ww.cod_centro)::text)) || ' '::text AS super_busqueda








  FROM

        cnmd06_datos_personales b;


ALTER TABLE datos_personales_super_busqueda OWNER TO sisap;