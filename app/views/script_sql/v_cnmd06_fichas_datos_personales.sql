-- View: v_cnmd06_fichas_datos_personales

 DROP VIEW v_cnmd06_fichas_datos_personales;

CREATE OR REPLACE VIEW v_cnmd06_fichas_datos_personales AS

 SELECT

    a.cod_presi,
    a.cod_entidad,
    a.cod_tipo_inst,
    a.cod_inst,
    a.cod_dep,
    a.cod_tipo_nomina,
    c.tipo_nomina,
    a.cod_cargo,
    c.denominacion_clase,
    a.cod_ficha,
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
    ( SELECT z.denominacion FROM cnmd06_profesiones z WHERE b.cod_profesion = z.cod_profesion) AS denominacion_profesion,
    b.cod_especialidad,
    ( SELECT y.denominacion FROM cnmd06_especialidades y WHERE b.cod_profesion = y.cod_profesion AND b.cod_especialidad = y.cod_especialidad) AS denominacion_especialidad,
    b.cod_oficio,
    ( SELECT x.denominacion FROM cnmd06_oficio x WHERE b.cod_oficio = x.cod_oficio) AS denominacion_oficio,
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
    ( SELECT v.denominacion FROM cnmd06_deportes v WHERE b.deporte_practica = v.cod_deporte) AS denominacion_deporte,
    b.religion_pertenece,
    ( SELECT w.denominacion FROM cnmd06_religiones w WHERE b.religion_pertenece = w.cod_religion) AS denominacion_religion,
    b.club_pertenece,
    ( SELECT u.denominacion FROM cnmd06_clubes u WHERE b.club_pertenece = u.cod_club) AS denominacion_club,
    b.hobby_favorito,
    ( SELECT t.denominacion FROM cnmd06_hobby t WHERE b.hobby_favorito = t.cod_hobby) AS denominacion_hobby,
    b.color_favorito,
    ( SELECT s.denominacion FROM cnmd06_colores s WHERE b.color_favorito = s.cod_color) AS denominacion_color,
    b.cod_pais_origen,
    ( SELECT r.denominacion FROM cugd01_republica r WHERE b.cod_pais_origen = r.cod_republica) AS denominacion_pais_origen,
    b.cod_estado_origen,
    ( SELECT q.denominacion FROM cugd01_estados q WHERE b.cod_pais_origen = q.cod_republica AND b.cod_estado_origen = q.cod_estado) AS denominacion_estado_origen,
    b.cod_municipio_origen,
    ( SELECT p.denominacion FROM cugd01_municipios p WHERE b.cod_pais_origen = p.cod_republica AND b.cod_estado_origen = p.cod_estado AND b.cod_municipio_origen = p.cod_municipio) AS denominacion_municipio_origen,
    b.cod_parroquia_origen,
    ( SELECT pp.denominacion FROM cugd01_parroquias pp WHERE b.cod_pais_origen = pp.cod_republica AND b.cod_estado_origen = pp.cod_estado AND b.cod_municipio_origen = pp.cod_municipio AND b.cod_parroquia_origen = pp.cod_parroquia) AS denominacion_parroquia_origen,
    b.cod_centropoblado_origen,
    ( SELECT ppp.denominacion FROM cugd01_centros_poblados ppp WHERE b.cod_pais_origen = ppp.cod_republica AND b.cod_estado_origen = ppp.cod_estado AND b.cod_municipio_origen = ppp.cod_municipio AND b.cod_parroquia_origen = ppp.cod_parroquia AND b.cod_centropoblado_origen = ppp.cod_centro) AS denominacion_centropoblado_origen,
    b.cod_estado_habitacion,
    ( SELECT zz.denominacion FROM cugd01_estados zz WHERE b.cod_pais_origen = zz.cod_republica AND b.cod_estado_habitacion = zz.cod_estado) AS denominacion_estado_habitacion,
    b.cod_municipio_habitacion,
    ( SELECT yy.denominacion FROM cugd01_municipios yy  WHERE b.cod_pais_origen = yy.cod_republica AND b.cod_estado_habitacion = yy.cod_estado AND b.cod_municipio_habitacion = yy.cod_municipio) AS denominacion_municipio_habitacion,
    b.cod_parroquia_habitacion,
    ( SELECT xx.denominacion FROM cugd01_parroquias xx WHERE b.cod_pais_origen = xx.cod_republica AND b.cod_estado_habitacion = xx.cod_estado AND b.cod_municipio_habitacion = xx.cod_municipio AND b.cod_parroquia_habitacion = xx.cod_parroquia) AS denominacion_parroquia_habitacion,
    b.cod_centropoblado_habitacion,
    ( SELECT ww.denominacion FROM cugd01_centros_poblados ww WHERE b.cod_pais_origen = ww.cod_republica AND b.cod_estado_habitacion = ww.cod_estado AND b.cod_municipio_habitacion = ww.cod_municipio AND b.cod_parroquia_habitacion = ww.cod_parroquia AND b.cod_centropoblado_habitacion = ww.cod_centro) AS denominacion_centropoblado_habitacion,
          b.condicion_actual,
          a.fecha_ingreso,
          a.forma_pago,
          a.cod_entidad_bancaria,
          a.cod_sucursal,
          a.cuenta_bancaria,
          a.condicion_actividad,
          a.funciones_realizar,
          a.responsabilidad_administrativa,
          a.horas_laborar,
          a.porcentaje_jub_pension,
          a.fecha_terminacion_contrato,
          a.fecha_retiro,
          a.motivo_retiro,
          ((((((((b.cedula_identidad || ' '::text) || b.primer_apellido::text) || ' '::text) || b.segundo_apellido::text) || ' '::text) || b.primer_nombre::text) || ' '::text) || b.segundo_nombre::text) || ' '::text as denominacion_busqueda,

          ((((((((((((((((((((((((((((((((((((((((((((b.cedula_identidad || ' '::text) || b.primer_apellido::text) || ' '::text) || b.segundo_apellido::text) || ' '::text) || b.primer_nombre::text) || ' '::text) || b.segundo_nombre::text) || ' '::text)) || ' '::text) ||
          text_null_(( SELECT z.denominacion FROM cnmd06_profesiones z WHERE b.cod_profesion = z.cod_profesion)::text)) || ' '::text) ||
          text_null_(( SELECT y.denominacion FROM cnmd06_especialidades y WHERE b.cod_profesion = y.cod_profesion AND b.cod_especialidad = y.cod_especialidad)::text)) || ' '::text) ||
          text_null_(( SELECT x.denominacion FROM cnmd06_oficio x WHERE b.cod_oficio = x.cod_oficio)::text) || ' '::text)) ||
          text_null_(( SELECT v.denominacion FROM cnmd06_deportes v WHERE b.deporte_practica = v.cod_deporte)::text) || ' '::text)) ||
          text_null_(( SELECT w.denominacion FROM cnmd06_religiones w WHERE b.religion_pertenece = w.cod_religion)::text) || ' '::text)) ||
          text_null_(( SELECT u.denominacion FROM cnmd06_clubes u WHERE b.club_pertenece = u.cod_club)::text)) || ' '::text) ||
          text_null_(( SELECT t.denominacion FROM cnmd06_hobby t WHERE b.hobby_favorito = t.cod_hobby)::text)) || ' '::text) ||
          text_null_(( SELECT s.denominacion FROM cnmd06_colores s WHERE b.color_favorito = s.cod_color)::text)) || ' '::text) ||
          text_null_(( SELECT r.denominacion FROM cugd01_republica r WHERE b.cod_pais_origen = r.cod_republica)::text)) || ' '::text) ||
          text_null_(( SELECT q.denominacion FROM cugd01_estados q WHERE b.cod_pais_origen = q.cod_republica AND b.cod_estado_origen = q.cod_estado)::text)) || ' '::text) ||
          text_null_(( SELECT p.denominacion FROM cugd01_municipios p WHERE b.cod_pais_origen = p.cod_republica AND b.cod_estado_origen = p.cod_estado AND b.cod_municipio_origen = p.cod_municipio)::text)) || ' '::text) ||
          text_null_(( SELECT pp.denominacion FROM cugd01_parroquias pp WHERE b.cod_pais_origen = pp.cod_republica AND b.cod_estado_origen = pp.cod_estado AND b.cod_municipio_origen = pp.cod_municipio AND b.cod_parroquia_origen = pp.cod_parroquia)::text)) || ' '::text) ||
          text_null_(( SELECT ppp.denominacion FROM cugd01_centros_poblados ppp WHERE b.cod_pais_origen = ppp.cod_republica AND b.cod_estado_origen = ppp.cod_estado AND b.cod_municipio_origen = ppp.cod_municipio AND b.cod_parroquia_origen = ppp.cod_parroquia AND b.cod_centropoblado_origen = ppp.cod_centro)::text)) || ' '::text) ||
          text_null_(( SELECT zz.denominacion FROM cugd01_estados zz WHERE b.cod_pais_origen = zz.cod_republica AND b.cod_estado_habitacion = zz.cod_estado)::text)) || ' '::text) ||
          text_null_(( SELECT yy.denominacion FROM cugd01_municipios yy WHERE b.cod_pais_origen = yy.cod_republica AND b.cod_estado_habitacion = yy.cod_estado AND b.cod_municipio_habitacion = yy.cod_municipio)::text)) || ' '::text) ||
          text_null_(( SELECT xx.denominacion FROM cugd01_parroquias xx WHERE b.cod_pais_origen = xx.cod_republica AND b.cod_estado_habitacion = xx.cod_estado AND b.cod_municipio_habitacion = xx.cod_municipio AND b.cod_parroquia_habitacion = xx.cod_parroquia)::text)) || ' '::text) ||
          text_null_(( SELECT ww.denominacion  FROM cugd01_centros_poblados ww  WHERE b.cod_pais_origen = ww.cod_republica AND b.cod_estado_habitacion = ww.cod_estado AND b.cod_municipio_habitacion = ww.cod_municipio AND b.cod_parroquia_habitacion = ww.cod_parroquia AND b.cod_centropoblado_habitacion = ww.cod_centro)::text)) || ' '::text AS super_busqueda


FROM cnmd06_fichas a, cnmd06_datos_personales b, v_cnmd05 c


  WHERE c.cod_presi         =  a.cod_presi AND
        c.cod_entidad       =  a.cod_entidad AND
        c.cod_tipo_inst     =  a.cod_tipo_inst AND
        c.cod_inst          =  a.cod_inst AND
        c.cod_dep           =  a.cod_dep AND
        c.cod_tipo_nomina   =  a.cod_tipo_nomina AND
        c.cod_cargo         =  a.cod_cargo AND
        b.cedula_identidad  =  a.cedula_identidad;

ALTER TABLE v_cnmd06_fichas_datos_personales OWNER TO sisap;

