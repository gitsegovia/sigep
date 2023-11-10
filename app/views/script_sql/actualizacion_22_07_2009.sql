

 DROP VIEW v_deno_dependencia;-- View: v_datos_para_ficha

DROP VIEW v_datos_para_ficha;

DROP VIEW v_csrd01_solicitud_recurso_cuerpo;

DROP VIEW v_csrd01_analitico_solicitud_recurso;

DROP VIEW v_credito_presupuestario_dependencia;

DROP VIEW v_cnmd06_fichas;



ALTER TABLE arrd05 ALTER denominacion TYPE character varying(200);




CREATE OR REPLACE VIEW v_cnmd06_fichas AS
 SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, ( SELECT x.denominacion
           FROM arrd05 x
          WHERE x.cod_presi = a.cod_presi AND x.cod_entidad = a.cod_entidad AND x.cod_tipo_inst = a.cod_tipo_inst AND x.cod_inst = a.cod_inst AND x.cod_dep = a.cod_dep) AS denominacion_dependencia, a.cod_tipo_nomina, ( SELECT x.denominacion
           FROM cnmd01 x
          WHERE x.cod_presi = a.cod_presi AND x.cod_entidad = a.cod_entidad AND x.cod_tipo_inst = a.cod_tipo_inst AND x.cod_inst = a.cod_inst AND x.cod_dep = a.cod_dep AND x.cod_tipo_nomina = a.cod_tipo_nomina) AS denominacion_nomina, a.cod_cargo, a.cod_ficha, a.cedula_identidad, a.fecha_ingreso, a.forma_pago, a.cod_entidad_bancaria, a.cod_sucursal, ( SELECT x.denominacion
           FROM cstd01_entidades_bancarias x
          WHERE x.cod_entidad_bancaria = a.cod_entidad_bancaria) AS denominacion_entidad_bancaria, ( SELECT x.denominacion
           FROM cstd01_sucursales_bancarias x
          WHERE x.cod_entidad_bancaria = a.cod_entidad_bancaria AND x.cod_sucursal = a.cod_sucursal) AS denominacion_sucursal, a.cuenta_bancaria, a.condicion_actividad AS condicion_actividad_ficha, a.funciones_realizar, a.responsabilidad_administrativa, a.horas_laborar, a.porcentaje_jub_pension, a.fecha_terminacion_contrato, a.fecha_retiro, a.motivo_retiro, a.paso, a.tipo_contrato, a.situacion, a.nivel, a.categoria, b.nacionalidad, b.primer_apellido, b.segundo_apellido, b.primer_nombre, b.segundo_nombre, b.fecha_nacimiento, b.sexo, b.estado_civil, b.grupo_sanguineo, b.peso_kilos, b.estatura_metros, b.naturalizado, b.fecha_naturalizacion, b.numero_gaceta, b.idioma, b.cod_profesion, b.cod_especialidad, b.cod_oficio, b.direccion_habitacion, b.telefonos_habitacion, b.otra_direccion_hab, b.otros_telefonos, b.correo_electronico, b.numero_inscripcion_sso, b.numero_inscripcion_lph, b.grado_licencia_conducir, b.numero_licencia_conducir, b.usa_lentes, b.talla_camisa_blusa, b.talla_pantalon_falda, b.talla_calzado, b.talla_keppy, b.deporte_practica, b.religion_pertenece, b.club_pertenece, b.hobby_favorito, b.color_favorito, b.cod_pais_origen, b.cod_estado_origen, b.cod_municipio_origen, b.cod_parroquia_origen, b.cod_centropoblado_origen, b.cod_estado_habitacion, b.cod_municipio_habitacion, b.cod_parroquia_habitacion, b.cod_centropoblado_habitacion, b.condicion_actual, c.cod_puesto, ( SELECT devolver_denominacion_puesto(( SELECT xy.clasificacion_personal
                   FROM cnmd01 xy
                  WHERE xy.cod_presi = c.cod_presi AND xy.cod_entidad = c.cod_entidad AND xy.cod_tipo_inst = c.cod_tipo_inst AND xy.cod_inst = c.cod_inst AND xy.cod_dep = c.cod_dep AND xy.cod_tipo_nomina = c.cod_tipo_nomina), c.cod_puesto) AS devolver_denominacion_puesto) AS demonimacion_puesto, c.sueldo_basico, c.compensaciones, c.primas, c.bonos, c.cod_dir_superior, c.cod_coordinacion, c.cod_secretaria, c.cod_direccion, c.cod_division, c.cod_departamento, c.cod_oficina, ( SELECT xa.denominacion
           FROM cugd02_direccionsuperior xa
          WHERE xa.cod_tipo_institucion = c.cod_tipo_inst AND xa.cod_institucion = c.cod_inst AND xa.cod_dependencia = c.cod_dep AND xa.cod_dir_superior = c.cod_dir_superior
          GROUP BY xa.denominacion) AS deno_cod_dir_superior, ( SELECT xb.denominacion
           FROM cugd02_coordinacion xb
          WHERE xb.cod_tipo_institucion = c.cod_tipo_inst AND xb.cod_institucion = c.cod_inst AND xb.cod_dependencia = c.cod_dep AND xb.cod_dir_superior = c.cod_dir_superior AND xb.cod_coordinacion = c.cod_coordinacion
          GROUP BY xb.denominacion) AS deno_cod_coordinacion, ( SELECT xc.denominacion
           FROM cugd02_secretaria xc
          WHERE xc.cod_tipo_institucion = c.cod_tipo_inst AND xc.cod_institucion = c.cod_inst AND xc.cod_dependencia = c.cod_dep AND xc.cod_dir_superior = c.cod_dir_superior AND xc.cod_coordinacion = c.cod_coordinacion AND xc.cod_secretaria = c.cod_secretaria
          GROUP BY xc.denominacion) AS deno_cod_secretaria, ( SELECT xd.denominacion
           FROM cugd02_direccion xd
          WHERE xd.cod_tipo_institucion = c.cod_tipo_inst AND xd.cod_institucion = c.cod_inst AND xd.cod_dependencia = c.cod_dep AND xd.cod_dir_superior = c.cod_dir_superior AND xd.cod_coordinacion = c.cod_coordinacion AND xd.cod_secretaria = c.cod_secretaria AND xd.cod_direccion = c.cod_direccion
          GROUP BY xd.denominacion) AS deno_cod_direccion, ( SELECT xe.denominacion
           FROM cugd02_division xe
          WHERE xe.cod_tipo_institucion = c.cod_tipo_inst AND xe.cod_institucion = c.cod_inst AND xe.cod_dependencia = c.cod_dep AND xe.cod_dir_superior = c.cod_dir_superior AND xe.cod_coordinacion = c.cod_coordinacion AND xe.cod_secretaria = c.cod_secretaria AND xe.cod_direccion = c.cod_direccion AND xe.cod_division = c.cod_division
          GROUP BY xe.denominacion) AS deno_cod_division, ( SELECT xf.denominacion
           FROM cugd02_departamento xf
          WHERE xf.cod_tipo_institucion = c.cod_tipo_inst AND xf.cod_institucion = c.cod_inst AND xf.cod_dependencia = c.cod_dep AND xf.cod_dir_superior = c.cod_dir_superior AND xf.cod_coordinacion = c.cod_coordinacion AND xf.cod_secretaria = c.cod_secretaria AND xf.cod_direccion = c.cod_direccion AND xf.cod_division = c.cod_division AND xf.cod_departamento = c.cod_departamento
          GROUP BY xf.denominacion) AS deno_cod_departamento, ( SELECT xg.denominacion
           FROM cugd02_oficina xg
          WHERE xg.cod_tipo_institucion = c.cod_tipo_inst AND xg.cod_institucion = c.cod_inst AND xg.cod_dependencia = c.cod_dep AND xg.cod_dir_superior = c.cod_dir_superior AND xg.cod_coordinacion = c.cod_coordinacion AND xg.cod_secretaria = c.cod_secretaria AND xg.cod_direccion = c.cod_direccion AND xg.cod_division = c.cod_division AND xg.cod_departamento = c.cod_departamento AND xg.cod_oficina = c.cod_oficina
          GROUP BY xg.denominacion) AS deno_cod_oficina, c.cod_estado, c.cod_municipio, c.cod_parroquia, c.cod_centro, ( SELECT xya.denominacion
           FROM cugd01_estados xya
          WHERE xya.cod_republica = c.cod_presi AND xya.cod_estado = c.cod_estado
          GROUP BY xya.denominacion) AS deno_cod_estado, ( SELECT xyb.denominacion
           FROM cugd01_municipios xyb
          WHERE xyb.cod_republica = c.cod_presi AND xyb.cod_estado = c.cod_estado AND xyb.cod_municipio = c.cod_municipio
          GROUP BY xyb.denominacion) AS deno_cod_municipio, ( SELECT xyc.denominacion
           FROM cugd01_parroquias xyc
          WHERE xyc.cod_republica = c.cod_presi AND xyc.cod_estado = c.cod_estado AND xyc.cod_municipio = c.cod_municipio AND xyc.cod_parroquia = c.cod_parroquia
          GROUP BY xyc.denominacion) AS deno_cod_parroquia, ( SELECT xyd.denominacion
           FROM cugd01_centros_poblados xyd
          WHERE xyd.cod_republica = c.cod_presi AND xyd.cod_estado = c.cod_estado AND xyd.cod_municipio = c.cod_municipio AND xyd.cod_parroquia = c.cod_parroquia AND xyd.cod_centro = c.cod_centro
          GROUP BY xyd.denominacion) AS deno_cod_centro, ( SELECT xyb.conocido
           FROM cugd01_municipios xyb
          WHERE xyb.cod_republica = c.cod_presi AND xyb.cod_estado = c.cod_estado AND xyb.cod_municipio = c.cod_municipio
          GROUP BY xyb.conocido) AS deno_ciudad, c.condicion_actividad AS condicion_actividad_cnmd05, c.ano, c.cod_sector, c.cod_programa, c.cod_sub_prog, c.cod_proyecto, c.cod_activ_obra, c.cod_partida, c.cod_generica, c.cod_especifica, c.cod_sub_espec, c.cod_auxiliar, c.cod_nivel_i, c.cod_nivel_ii, ( SELECT x.denominacion
           FROM cnmd04_tipo x
          WHERE x.cod_nivel_i = c.cod_nivel_i) AS denominacion_cod_nivel_i, ( SELECT x.denominacion
           FROM cnmd04_ocupacion x
          WHERE x.cod_nivel_i = c.cod_nivel_i AND x.cod_nivel_ii = c.cod_nivel_ii) AS denominacion_cod_nivel_ii
   FROM cnmd06_fichas a, cnmd06_datos_personales b, cnmd05 c
  WHERE b.cedula_identidad = a.cedula_identidad AND
        c.cod_presi = a.cod_presi AND
        c.cod_entidad = a.cod_entidad AND
        c.cod_tipo_inst = a.cod_tipo_inst AND
        c.cod_inst = a.cod_inst AND
        c.cod_dep = a.cod_dep AND
        c.cod_tipo_nomina = a.cod_tipo_nomina AND
        c.cod_cargo = a.cod_cargo;

ALTER TABLE v_cnmd06_fichas OWNER TO sisap;












CREATE OR REPLACE VIEW v_credito_presupuestario_dependencia AS



 SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.ano, upper((( SELECT a1.denominacion
           FROM arrd05 a1
          WHERE a1.cod_presi = a.cod_presi AND a1.cod_entidad = a.cod_entidad AND a1.cod_tipo_inst = a.cod_tipo_inst AND a1.cod_inst = a.cod_inst AND a1.cod_dep = a.cod_dep))::text) AS dependencia, sum(a.asignacion_anual) AS asignacion_anual, sum(a.aumento_traslado_ene + a.credito_adicional_ene) AS aumento_ene, sum(a.aumento_traslado_feb + a.credito_adicional_feb) AS aumento_feb, sum(a.aumento_traslado_mar + a.credito_adicional_mar) AS aumento_mar, sum(a.aumento_traslado_abr + a.credito_adicional_abr) AS aumento_abr, sum(a.aumento_traslado_may + a.credito_adicional_may) AS aumento_may, sum(a.aumento_traslado_jun + a.credito_adicional_jun) AS aumento_jun, sum(a.aumento_traslado_jul + a.credito_adicional_jul) AS aumento_jul, sum(a.aumento_traslado_ago + a.credito_adicional_ago) AS aumento_ago, sum(a.aumento_traslado_sep + a.credito_adicional_sep) AS aumento_sep, sum(a.aumento_traslado_oct + a.credito_adicional_oct) AS aumento_oct, sum(a.aumento_traslado_nov + a.credito_adicional_nov) AS aumento_nov, sum(a.aumento_traslado_dic + a.credito_adicional_dic) AS aumento_dic, sum(a.disminucion_traslado_ene + a.rebaja_ene) AS disminucion_ene, sum(a.disminucion_traslado_feb + a.rebaja_feb) AS disminucion_feb, sum(a.disminucion_traslado_mar + a.rebaja_mar) AS disminucion_mar, sum(a.disminucion_traslado_abr + a.rebaja_abr) AS disminucion_abr, sum(a.disminucion_traslado_may + a.rebaja_may) AS disminucion_may, sum(a.disminucion_traslado_jun + a.rebaja_jun) AS disminucion_jun, sum(a.disminucion_traslado_jul + a.rebaja_jul) AS disminucion_jul, sum(a.disminucion_traslado_ago + a.rebaja_ago) AS disminucion_ago, sum(a.disminucion_traslado_sep + a.rebaja_sep) AS disminucion_sep, sum(a.disminucion_traslado_oct + a.rebaja_oct) AS disminucion_oct, sum(a.disminucion_traslado_nov + a.rebaja_nov) AS disminucion_nov, sum(a.disminucion_traslado_dic + a.rebaja_dic) AS disminucion_dic, sum(a.asignacion_anual + a.aumento_traslado_ene + a.credito_adicional_ene - (a.disminucion_traslado_ene + a.rebaja_ene)) AS total_ene, sum(a.asignacion_anual + a.aumento_traslado_ene + a.credito_adicional_ene + a.aumento_traslado_feb + a.credito_adicional_feb - (a.disminucion_traslado_ene + a.rebaja_ene + a.disminucion_traslado_feb + a.rebaja_feb)) AS total_feb, sum(a.asignacion_anual + a.aumento_traslado_ene + a.credito_adicional_ene + a.aumento_traslado_feb + a.credito_adicional_feb + a.aumento_traslado_mar + a.credito_adicional_mar - (a.disminucion_traslado_ene + a.rebaja_ene + a.disminucion_traslado_feb + a.rebaja_feb + a.disminucion_traslado_mar + a.rebaja_mar)) AS total_mar, sum(a.asignacion_anual + a.aumento_traslado_ene + a.credito_adicional_ene + a.aumento_traslado_feb + a.credito_adicional_feb + a.aumento_traslado_mar + a.credito_adicional_mar + a.aumento_traslado_abr + a.credito_adicional_abr - (a.disminucion_traslado_ene + a.rebaja_ene + a.disminucion_traslado_feb + a.rebaja_feb + a.disminucion_traslado_mar + a.rebaja_mar + a.disminucion_traslado_abr + a.rebaja_abr)) AS total_abr, sum(a.asignacion_anual + a.aumento_traslado_ene + a.credito_adicional_ene + a.aumento_traslado_feb + a.credito_adicional_feb + a.aumento_traslado_mar + a.credito_adicional_mar + a.aumento_traslado_abr + a.credito_adicional_abr + a.aumento_traslado_may + a.credito_adicional_may - (a.disminucion_traslado_ene + a.rebaja_ene + a.disminucion_traslado_feb + a.rebaja_feb + a.disminucion_traslado_mar + a.rebaja_mar + a.disminucion_traslado_abr + a.rebaja_abr + a.disminucion_traslado_may + a.rebaja_may)) AS total_may, sum(a.asignacion_anual + a.aumento_traslado_ene + a.credito_adicional_ene + a.aumento_traslado_feb + a.credito_adicional_feb + a.aumento_traslado_mar + a.credito_adicional_mar + a.aumento_traslado_abr + a.credito_adicional_abr + a.aumento_traslado_may + a.credito_adicional_may + a.aumento_traslado_jun + a.credito_adicional_jun - (a.disminucion_traslado_ene + a.rebaja_ene + a.disminucion_traslado_feb + a.rebaja_feb + a.disminucion_traslado_mar + a.rebaja_mar + a.disminucion_traslado_abr + a.rebaja_abr + a.disminucion_traslado_may + a.rebaja_may + a.disminucion_traslado_jun + a.rebaja_jun)) AS total_jun, sum(a.asignacion_anual + a.aumento_traslado_ene + a.credito_adicional_ene + a.aumento_traslado_feb + a.credito_adicional_feb + a.aumento_traslado_mar + a.credito_adicional_mar + a.aumento_traslado_abr + a.credito_adicional_abr + a.aumento_traslado_may + a.credito_adicional_may + a.aumento_traslado_jun + a.credito_adicional_jun + a.aumento_traslado_jul + a.credito_adicional_jul - (a.disminucion_traslado_ene + a.rebaja_ene + a.disminucion_traslado_feb + a.rebaja_feb + a.disminucion_traslado_mar + a.rebaja_mar + a.disminucion_traslado_abr + a.rebaja_abr + a.disminucion_traslado_may + a.rebaja_may + a.disminucion_traslado_jun + a.rebaja_jun + a.disminucion_traslado_jul + a.rebaja_jul)) AS total_jul, sum(a.asignacion_anual + a.aumento_traslado_ene + a.credito_adicional_ene + a.aumento_traslado_feb + a.credito_adicional_feb + a.aumento_traslado_mar + a.credito_adicional_mar + a.aumento_traslado_abr + a.credito_adicional_abr + a.aumento_traslado_may + a.credito_adicional_may + a.aumento_traslado_jun + a.credito_adicional_jun + a.aumento_traslado_jul + a.credito_adicional_jul + a.aumento_traslado_ago + a.credito_adicional_ago - (a.disminucion_traslado_ene + a.rebaja_ene + a.disminucion_traslado_feb + a.rebaja_feb + a.disminucion_traslado_mar + a.rebaja_mar + a.disminucion_traslado_abr + a.rebaja_abr + a.disminucion_traslado_may + a.rebaja_may + a.disminucion_traslado_jun + a.rebaja_jun + a.disminucion_traslado_jul + a.rebaja_jul + a.disminucion_traslado_ago + a.rebaja_ago)) AS total_ago, sum(a.asignacion_anual + a.aumento_traslado_ene + a.credito_adicional_ene + a.aumento_traslado_feb + a.credito_adicional_feb + a.aumento_traslado_mar + a.credito_adicional_mar + a.aumento_traslado_abr + a.credito_adicional_abr + a.aumento_traslado_may + a.credito_adicional_may + a.aumento_traslado_jun + a.credito_adicional_jun + a.aumento_traslado_jul + a.credito_adicional_jul + a.aumento_traslado_ago + a.credito_adicional_ago + a.aumento_traslado_sep + a.credito_adicional_sep - (a.disminucion_traslado_ene + a.rebaja_ene + a.disminucion_traslado_feb + a.rebaja_feb + a.disminucion_traslado_mar + a.rebaja_mar + a.disminucion_traslado_abr + a.rebaja_abr + a.disminucion_traslado_may + a.rebaja_may + a.disminucion_traslado_jun + a.rebaja_jun + a.disminucion_traslado_jul + a.rebaja_jul + a.disminucion_traslado_ago + a.rebaja_ago + a.disminucion_traslado_sep + a.rebaja_sep)) AS total_sep, sum(a.asignacion_anual + a.aumento_traslado_ene + a.credito_adicional_ene + a.aumento_traslado_feb + a.credito_adicional_feb + a.aumento_traslado_mar + a.credito_adicional_mar + a.aumento_traslado_abr + a.credito_adicional_abr + a.aumento_traslado_may + a.credito_adicional_may + a.aumento_traslado_jun + a.credito_adicional_jun + a.aumento_traslado_jul + a.credito_adicional_jul + a.aumento_traslado_ago + a.credito_adicional_ago + a.aumento_traslado_sep + a.credito_adicional_sep + a.aumento_traslado_oct + a.credito_adicional_oct - (a.disminucion_traslado_ene + a.rebaja_ene + a.disminucion_traslado_feb + a.rebaja_feb + a.disminucion_traslado_mar + a.rebaja_mar + a.disminucion_traslado_abr + a.rebaja_abr + a.disminucion_traslado_may + a.rebaja_may + a.disminucion_traslado_jun + a.rebaja_jun + a.disminucion_traslado_jul + a.rebaja_jul + a.disminucion_traslado_ago + a.rebaja_ago + a.disminucion_traslado_sep + a.rebaja_sep + a.disminucion_traslado_oct + a.rebaja_oct)) AS total_oct, sum(a.asignacion_anual + a.aumento_traslado_ene + a.credito_adicional_ene + a.aumento_traslado_feb + a.credito_adicional_feb + a.aumento_traslado_mar + a.credito_adicional_mar + a.aumento_traslado_abr + a.credito_adicional_abr + a.aumento_traslado_may + a.credito_adicional_may + a.aumento_traslado_jun + a.credito_adicional_jun + a.aumento_traslado_jul + a.credito_adicional_jul + a.aumento_traslado_ago + a.credito_adicional_ago + a.aumento_traslado_sep + a.credito_adicional_sep + a.aumento_traslado_oct + a.credito_adicional_oct + a.aumento_traslado_nov + a.credito_adicional_nov - (a.disminucion_traslado_ene + a.rebaja_ene + a.disminucion_traslado_feb + a.rebaja_feb + a.disminucion_traslado_mar + a.rebaja_mar + a.disminucion_traslado_abr + a.rebaja_abr + a.disminucion_traslado_may + a.rebaja_may + a.disminucion_traslado_jun + a.rebaja_jun + a.disminucion_traslado_jul + a.rebaja_jul + a.disminucion_traslado_ago + a.rebaja_ago + a.disminucion_traslado_sep + a.rebaja_sep + a.disminucion_traslado_oct + a.rebaja_oct + a.disminucion_traslado_nov + a.rebaja_nov)) AS total_nov, sum(a.asignacion_anual + a.aumento_traslado_ene + a.credito_adicional_ene + a.aumento_traslado_feb + a.credito_adicional_feb + a.aumento_traslado_mar + a.credito_adicional_mar + a.aumento_traslado_abr + a.credito_adicional_abr + a.aumento_traslado_may + a.credito_adicional_may + a.aumento_traslado_jun + a.credito_adicional_jun + a.aumento_traslado_jul + a.credito_adicional_jul + a.aumento_traslado_ago + a.credito_adicional_ago + a.aumento_traslado_sep + a.credito_adicional_sep + a.aumento_traslado_oct + a.credito_adicional_oct + a.aumento_traslado_nov + a.credito_adicional_nov + a.aumento_traslado_dic + a.credito_adicional_dic - (a.disminucion_traslado_ene + a.rebaja_ene + a.disminucion_traslado_feb + a.rebaja_feb + a.disminucion_traslado_mar + a.rebaja_mar + a.disminucion_traslado_abr + a.rebaja_abr + a.disminucion_traslado_may + a.rebaja_may + a.disminucion_traslado_jun + a.rebaja_jun + a.disminucion_traslado_jul + a.rebaja_jul + a.disminucion_traslado_ago + a.rebaja_ago + a.disminucion_traslado_sep + a.rebaja_sep + a.disminucion_traslado_oct + a.rebaja_oct + a.disminucion_traslado_nov + a.rebaja_nov + a.disminucion_traslado_dic + a.rebaja_dic)) AS total_dic, sum(a.compromiso_ene) AS compromiso_ene, sum(a.compromiso_feb) AS compromiso_feb, sum(a.compromiso_mar) AS compromiso_mar, sum(a.compromiso_abr) AS compromiso_abr, sum(a.compromiso_may) AS compromiso_may, sum(a.compromiso_jun) AS compromiso_jun, sum(a.compromiso_jul) AS compromiso_jul, sum(a.compromiso_ago) AS compromiso_ago, sum(a.compromiso_sep) AS compromiso_sep, sum(a.compromiso_oct) AS compromiso_oct, sum(a.compromiso_nov) AS compromiso_nov, sum(a.compromiso_dic) AS compromiso_dic, sum(a.asignacion_anual + a.aumento_traslado_ene + a.credito_adicional_ene - (a.disminucion_traslado_ene + a.rebaja_ene + a.compromiso_ene)) AS disponibilidad_ene, sum(a.asignacion_anual + a.aumento_traslado_ene + a.credito_adicional_ene + a.aumento_traslado_feb + a.credito_adicional_feb - (a.disminucion_traslado_ene + a.rebaja_ene + a.disminucion_traslado_feb + a.rebaja_feb + a.compromiso_ene + a.compromiso_feb)) AS disponibilidad_feb, sum(a.asignacion_anual + a.aumento_traslado_ene + a.credito_adicional_ene + a.aumento_traslado_feb + a.credito_adicional_feb + a.aumento_traslado_mar + a.credito_adicional_mar - (a.disminucion_traslado_ene + a.rebaja_ene + a.disminucion_traslado_feb + a.rebaja_feb + a.disminucion_traslado_mar + a.rebaja_mar + a.compromiso_ene + a.compromiso_feb + a.compromiso_mar)) AS disponibilidad_mar, sum(a.asignacion_anual + a.aumento_traslado_ene + a.credito_adicional_ene + a.aumento_traslado_feb + a.credito_adicional_feb + a.aumento_traslado_mar + a.credito_adicional_mar + a.aumento_traslado_abr + a.credito_adicional_abr - (a.disminucion_traslado_ene + a.rebaja_ene + a.disminucion_traslado_feb + a.rebaja_feb + a.disminucion_traslado_mar + a.rebaja_mar + a.disminucion_traslado_abr + a.rebaja_abr + a.compromiso_ene + a.compromiso_feb + a.compromiso_mar + a.compromiso_abr)) AS disponibilidad_abr, sum(a.asignacion_anual + a.aumento_traslado_ene + a.credito_adicional_ene + a.aumento_traslado_feb + a.credito_adicional_feb + a.aumento_traslado_mar + a.credito_adicional_mar + a.aumento_traslado_abr + a.credito_adicional_abr + a.aumento_traslado_may + a.credito_adicional_may - (a.disminucion_traslado_ene + a.rebaja_ene + a.disminucion_traslado_feb + a.rebaja_feb + a.disminucion_traslado_mar + a.rebaja_mar + a.disminucion_traslado_abr + a.rebaja_abr + a.disminucion_traslado_may + a.rebaja_may + a.compromiso_ene + a.compromiso_feb + a.compromiso_mar + a.compromiso_abr + a.compromiso_may)) AS disponibilidad_may, sum(a.asignacion_anual + a.aumento_traslado_ene + a.credito_adicional_ene + a.aumento_traslado_feb + a.credito_adicional_feb + a.aumento_traslado_mar + a.credito_adicional_mar + a.aumento_traslado_abr + a.credito_adicional_abr + a.aumento_traslado_may + a.credito_adicional_may + a.aumento_traslado_jun + a.credito_adicional_jun - (a.disminucion_traslado_ene + a.rebaja_ene + a.disminucion_traslado_feb + a.rebaja_feb + a.disminucion_traslado_mar + a.rebaja_mar + a.disminucion_traslado_abr + a.rebaja_abr + a.disminucion_traslado_may + a.rebaja_may + a.disminucion_traslado_jun + a.rebaja_jun + a.compromiso_ene + a.compromiso_feb + a.compromiso_mar + a.compromiso_abr + a.compromiso_may + a.compromiso_jun)) AS disponibilidad_jun, sum(a.asignacion_anual + a.aumento_traslado_ene + a.credito_adicional_ene + a.aumento_traslado_feb + a.credito_adicional_feb + a.aumento_traslado_mar + a.credito_adicional_mar + a.aumento_traslado_abr + a.credito_adicional_abr + a.aumento_traslado_may + a.credito_adicional_may + a.aumento_traslado_jun + a.credito_adicional_jun + a.aumento_traslado_jul + a.credito_adicional_jul - (a.disminucion_traslado_ene + a.rebaja_ene + a.disminucion_traslado_feb + a.rebaja_feb + a.disminucion_traslado_mar + a.rebaja_mar + a.disminucion_traslado_abr + a.rebaja_abr + a.disminucion_traslado_may + a.rebaja_may + a.disminucion_traslado_jun + a.rebaja_jun + a.disminucion_traslado_jul + a.rebaja_jul + a.compromiso_ene + a.compromiso_feb + a.compromiso_mar + a.compromiso_abr + a.compromiso_may + a.compromiso_jun + a.compromiso_jul)) AS disponibilidad_jul, sum(a.asignacion_anual + a.aumento_traslado_ene + a.credito_adicional_ene + a.aumento_traslado_feb + a.credito_adicional_feb + a.aumento_traslado_mar + a.credito_adicional_mar + a.aumento_traslado_abr + a.credito_adicional_abr + a.aumento_traslado_may + a.credito_adicional_may + a.aumento_traslado_jun + a.credito_adicional_jun + a.aumento_traslado_jul + a.credito_adicional_jul + a.aumento_traslado_ago + a.credito_adicional_ago - (a.disminucion_traslado_ene + a.rebaja_ene + a.disminucion_traslado_feb + a.rebaja_feb + a.disminucion_traslado_mar + a.rebaja_mar + a.disminucion_traslado_abr + a.rebaja_abr + a.disminucion_traslado_may + a.rebaja_may + a.disminucion_traslado_jun + a.rebaja_jun + a.disminucion_traslado_jul + a.rebaja_jul + a.disminucion_traslado_ago + a.rebaja_ago + a.compromiso_ene + a.compromiso_feb + a.compromiso_mar + a.compromiso_abr + a.compromiso_may + a.compromiso_jun + a.compromiso_jul + a.compromiso_ago)) AS disponibilidad_ago, sum(a.asignacion_anual + a.aumento_traslado_ene + a.credito_adicional_ene + a.aumento_traslado_feb + a.credito_adicional_feb + a.aumento_traslado_mar + a.credito_adicional_mar + a.aumento_traslado_abr + a.credito_adicional_abr + a.aumento_traslado_may + a.credito_adicional_may + a.aumento_traslado_jun + a.credito_adicional_jun + a.aumento_traslado_jul + a.credito_adicional_jul + a.aumento_traslado_ago + a.credito_adicional_ago + a.aumento_traslado_sep + a.credito_adicional_sep - (a.disminucion_traslado_ene + a.rebaja_ene + a.disminucion_traslado_feb + a.rebaja_feb + a.disminucion_traslado_mar + a.rebaja_mar + a.disminucion_traslado_abr + a.rebaja_abr + a.disminucion_traslado_may + a.rebaja_may + a.disminucion_traslado_jun + a.rebaja_jun + a.disminucion_traslado_jul + a.rebaja_jul + a.disminucion_traslado_ago + a.rebaja_ago + a.disminucion_traslado_sep + a.rebaja_sep + a.compromiso_ene + a.compromiso_feb + a.compromiso_mar + a.compromiso_abr + a.compromiso_may + a.compromiso_jun + a.compromiso_jul + a.compromiso_ago + a.compromiso_sep)) AS disponibilidad_sep, sum(a.asignacion_anual + a.aumento_traslado_ene + a.credito_adicional_ene + a.aumento_traslado_feb + a.credito_adicional_feb + a.aumento_traslado_mar + a.credito_adicional_mar + a.aumento_traslado_abr + a.credito_adicional_abr + a.aumento_traslado_may + a.credito_adicional_may + a.aumento_traslado_jun + a.credito_adicional_jun + a.aumento_traslado_jul + a.credito_adicional_jul + a.aumento_traslado_ago + a.credito_adicional_ago + a.aumento_traslado_sep + a.credito_adicional_sep + a.aumento_traslado_oct + a.credito_adicional_oct - (a.disminucion_traslado_ene + a.rebaja_ene + a.disminucion_traslado_feb + a.rebaja_feb + a.disminucion_traslado_mar + a.rebaja_mar + a.disminucion_traslado_abr + a.rebaja_abr + a.disminucion_traslado_may + a.rebaja_may + a.disminucion_traslado_jun + a.rebaja_jun + a.disminucion_traslado_jul + a.rebaja_jul + a.disminucion_traslado_ago + a.rebaja_ago + a.disminucion_traslado_sep + a.rebaja_sep + a.disminucion_traslado_oct + a.rebaja_oct + a.compromiso_ene + a.compromiso_feb + a.compromiso_mar + a.compromiso_abr + a.compromiso_may + a.compromiso_jun + a.compromiso_jul + a.compromiso_ago + a.compromiso_sep + a.compromiso_oct)) AS disponibilidad_oct, sum(a.asignacion_anual + a.aumento_traslado_ene + a.credito_adicional_ene + a.aumento_traslado_feb + a.credito_adicional_feb + a.aumento_traslado_mar + a.credito_adicional_mar + a.aumento_traslado_abr + a.credito_adicional_abr + a.aumento_traslado_may + a.credito_adicional_may + a.aumento_traslado_jun + a.credito_adicional_jun + a.aumento_traslado_jul + a.credito_adicional_jul + a.aumento_traslado_ago + a.credito_adicional_ago + a.aumento_traslado_sep + a.credito_adicional_sep + a.aumento_traslado_oct + a.credito_adicional_oct + a.aumento_traslado_nov + a.credito_adicional_nov - (a.disminucion_traslado_ene + a.rebaja_ene + a.disminucion_traslado_feb + a.rebaja_feb + a.disminucion_traslado_mar + a.rebaja_mar + a.disminucion_traslado_abr + a.rebaja_abr + a.disminucion_traslado_may + a.rebaja_may + a.disminucion_traslado_jun + a.rebaja_jun + a.disminucion_traslado_jul + a.rebaja_jul + a.disminucion_traslado_ago + a.rebaja_ago + a.disminucion_traslado_sep + a.rebaja_sep + a.disminucion_traslado_oct + a.rebaja_oct + a.disminucion_traslado_nov + a.rebaja_nov + a.compromiso_ene + a.compromiso_feb + a.compromiso_mar + a.compromiso_abr + a.compromiso_may + a.compromiso_jun + a.compromiso_jul + a.compromiso_ago + a.compromiso_sep + a.compromiso_oct + a.compromiso_nov)) AS disponibilidad_nov, sum(a.asignacion_anual + a.aumento_traslado_ene + a.credito_adicional_ene + a.aumento_traslado_feb + a.credito_adicional_feb + a.aumento_traslado_mar + a.credito_adicional_mar + a.aumento_traslado_abr + a.credito_adicional_abr + a.aumento_traslado_may + a.credito_adicional_may + a.aumento_traslado_jun + a.credito_adicional_jun + a.aumento_traslado_jul + a.credito_adicional_jul + a.aumento_traslado_ago + a.credito_adicional_ago + a.aumento_traslado_sep + a.credito_adicional_sep + a.aumento_traslado_oct + a.credito_adicional_oct + a.aumento_traslado_nov + a.credito_adicional_nov + a.aumento_traslado_dic + a.credito_adicional_dic - (a.disminucion_traslado_ene + a.rebaja_ene + a.disminucion_traslado_feb + a.rebaja_feb + a.disminucion_traslado_mar + a.rebaja_mar + a.disminucion_traslado_abr + a.rebaja_abr + a.disminucion_traslado_may + a.rebaja_may + a.disminucion_traslado_jun + a.rebaja_jun + a.disminucion_traslado_jul + a.rebaja_jul + a.disminucion_traslado_ago + a.rebaja_ago + a.disminucion_traslado_sep + a.rebaja_sep + a.disminucion_traslado_oct + a.rebaja_oct + a.disminucion_traslado_nov + a.rebaja_nov + a.disminucion_traslado_dic + a.rebaja_dic + a.compromiso_ene + a.compromiso_feb + a.compromiso_mar + a.compromiso_abr + a.compromiso_may + a.compromiso_jun + a.compromiso_jul + a.compromiso_ago + a.compromiso_sep + a.compromiso_oct + a.compromiso_nov + a.compromiso_dic)) AS disponibilidad_dic
   FROM cfpd05 a
  GROUP BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.ano
  ORDER BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.ano;


ALTER TABLE v_credito_presupuestario_dependencia OWNER TO sisap;


COMMENT ON VIEW v_credito_presupuestario_dependencia IS 'vista de creditos presupuestario agrupados por dependencias';





















CREATE OR REPLACE VIEW v_csrd01_analitico_solicitud_recurso AS

 SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.ano_solicitud, a.numero_solicitud, a.ano, ( SELECT b.fecha_solicitud
           FROM csrd01_solicitud_recurso_cuerpo b
          WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.ano_solicitud = b.ano_solicitud AND a.numero_solicitud = b.numero_solicitud) AS fecha_solicitud, a.monto_entregado AS monto_entregado_partidas, ( SELECT d.denominacion
           FROM csrd01_tipo_solicitud d
          WHERE d.cod_tipo_solicitud = (( SELECT c.tipo_solicitud_recurso
                   FROM csrd01_solicitud_recurso_cuerpo c
                  WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND a.ano_solicitud = c.ano_solicitud AND a.numero_solicitud = c.numero_solicitud))) AS deno_recurso, ( SELECT e.denominacion
           FROM cstd01_entidades_bancarias e
          WHERE e.cod_entidad_bancaria = (( SELECT f.cod_entidad_bancaria
                   FROM csrd01_solicitud_recurso_cuerpo f
                  WHERE a.cod_presi = f.cod_presi AND a.cod_entidad = f.cod_entidad AND a.cod_tipo_inst = f.cod_tipo_inst AND a.cod_inst = f.cod_inst AND a.cod_dep = f.cod_dep AND a.ano_solicitud = f.ano_solicitud AND a.numero_solicitud = f.numero_solicitud))) AS deno_banco, ( SELECT z.denominacion
           FROM cstd01_sucursales_bancarias z
          WHERE z.cod_entidad_bancaria = (( SELECT zz.cod_entidad_bancaria
                   FROM csrd01_solicitud_recurso_cuerpo zz
                  WHERE a.cod_presi = zz.cod_presi AND a.cod_entidad = zz.cod_entidad AND a.cod_tipo_inst = zz.cod_tipo_inst AND a.cod_inst = zz.cod_inst AND a.cod_dep = zz.cod_dep AND a.ano_solicitud = zz.ano_solicitud AND a.numero_solicitud = zz.numero_solicitud)) AND z.cod_sucursal = (( SELECT zzz.cod_sucursal
                   FROM csrd01_solicitud_recurso_cuerpo zzz
                  WHERE a.cod_presi = zzz.cod_presi AND a.cod_entidad = zzz.cod_entidad AND a.cod_tipo_inst = zzz.cod_tipo_inst AND a.cod_inst = zzz.cod_inst AND a.cod_dep = zzz.cod_dep AND a.ano_solicitud = zzz.ano_solicitud AND a.numero_solicitud = zzz.numero_solicitud))) AS deno_sucursal, ( SELECT g.cuenta_bancaria
           FROM csrd01_solicitud_recurso_cuerpo g
          WHERE a.cod_presi = g.cod_presi AND a.cod_entidad = g.cod_entidad AND a.cod_tipo_inst = g.cod_tipo_inst AND a.cod_inst = g.cod_inst AND a.cod_dep = g.cod_dep AND a.ano_solicitud = g.ano_solicitud AND a.numero_solicitud = g.numero_solicitud) AS cuenta_bancaria, ( SELECT y.numero_cheque
           FROM csrd01_solicitud_recurso_cuerpo y
          WHERE a.cod_presi = y.cod_presi AND a.cod_entidad = y.cod_entidad AND a.cod_tipo_inst = y.cod_tipo_inst AND a.cod_inst = y.cod_inst AND a.cod_dep = y.cod_dep AND a.ano_solicitud = y.ano_solicitud AND a.numero_solicitud = y.numero_solicitud) AS numero_cheque, ( SELECT x.fecha_cheque
           FROM csrd01_solicitud_recurso_cuerpo x
          WHERE a.cod_presi = x.cod_presi AND a.cod_entidad = x.cod_entidad AND a.cod_tipo_inst = x.cod_tipo_inst AND a.cod_inst = x.cod_inst AND a.cod_dep = x.cod_dep AND a.ano_solicitud = x.ano_solicitud AND a.numero_solicitud = x.numero_solicitud) AS fecha_cheque, ( SELECT v.monto_entregado
           FROM csrd01_solicitud_recurso_cuerpo v
          WHERE a.cod_presi = v.cod_presi AND a.cod_entidad = v.cod_entidad AND a.cod_tipo_inst = v.cod_tipo_inst AND a.cod_inst = v.cod_inst AND a.cod_dep = v.cod_dep AND a.ano_solicitud = v.ano_solicitud AND a.numero_solicitud = v.numero_solicitud) AS monto_entregado, ( SELECT p.forma_solicitud
           FROM csrd01_solicitud_recurso_cuerpo p
          WHERE a.cod_presi = p.cod_presi AND a.cod_entidad = p.cod_entidad AND a.cod_tipo_inst = p.cod_tipo_inst AND a.cod_inst = p.cod_inst AND a.cod_dep = p.cod_dep AND a.ano_solicitud = p.ano_solicitud AND a.numero_solicitud = p.numero_solicitud) AS forma_solicitud, ( SELECT bbb.mes_solicitado
           FROM csrd01_solicitud_recurso_cuerpo bbb
          WHERE a.cod_presi = bbb.cod_presi AND a.cod_entidad = bbb.cod_entidad AND a.cod_tipo_inst = bbb.cod_tipo_inst AND a.cod_inst = bbb.cod_inst AND a.cod_dep = bbb.cod_dep AND a.ano_solicitud = bbb.ano_solicitud AND a.numero_solicitud = bbb.numero_solicitud) AS mes_solicitud, a.cod_sector, a.cod_programa, a.cod_sub_prog, a.cod_proyecto, a.cod_activ_obra, a.cod_partida, a.cod_generica, a.cod_especifica, a.cod_sub_espec, a.cod_auxiliar, ( SELECT ss.denominacion
           FROM arrd05 ss
          WHERE a.cod_presi = ss.cod_presi AND a.cod_entidad = ss.cod_entidad AND a.cod_tipo_inst = ss.cod_tipo_inst AND a.cod_inst = ss.cod_inst AND a.cod_dep = ss.cod_dep) AS denominacion_dep
   FROM csrd01_solicitud_recurso_partidas a;




ALTER TABLE v_csrd01_analitico_solicitud_recurso OWNER TO sisap;









CREATE OR REPLACE VIEW v_csrd01_solicitud_recurso_cuerpo AS

SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, b.denominacion, a.ano_solicitud, a.numero_solicitud, a.fecha_solicitud, a.monto_solicitado, a.monto_entregado, a.cod_entidad_bancaria, a.cod_sucursal, a.cuenta_bancaria, a.numero_cheque, a.fecha_cheque, a.concepto, a.frecuencia_solicitud
   FROM csrd01_solicitud_recurso_cuerpo a, arrd05 b
  WHERE a.cod_dep = b.cod_dep AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep;

ALTER TABLE v_csrd01_solicitud_recurso_cuerpo OWNER TO sisap;















CREATE OR REPLACE VIEW v_deno_dependencia AS


 SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, b.denominacion AS deno_dependencia, a.ano, a.cod_sector, a.cod_programa, a.cod_sub_prog, a.cod_proyecto, a.cod_activ_obra, a.cod_partida, a.cod_generica, a.cod_especifica, a.cod_sub_espec, a.cod_auxiliar
   FROM cfpd05 a, arrd05 b
  WHERE b.cod_presi = a.cod_presi AND b.cod_entidad = a.cod_entidad AND b.cod_tipo_inst = a.cod_tipo_inst AND b.cod_inst = a.cod_inst AND b.cod_dep = a.cod_dep;

ALTER TABLE v_deno_dependencia OWNER TO sisap;













CREATE OR REPLACE VIEW v_datos_para_ficha AS


  SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, ( SELECT x.denominacion
           FROM arrd05 x
          WHERE x.cod_presi = a.cod_presi AND x.cod_entidad = a.cod_entidad AND x.cod_tipo_inst = a.cod_tipo_inst AND x.cod_inst = a.cod_inst AND x.cod_dep = a.cod_dep) AS denominacion_dependencia, a.cod_tipo_nomina, ( SELECT x.denominacion
           FROM cnmd01 x
          WHERE x.cod_presi = a.cod_presi AND x.cod_entidad = a.cod_entidad AND x.cod_tipo_inst = a.cod_tipo_inst AND x.cod_inst = a.cod_inst AND x.cod_dep = a.cod_dep AND x.cod_tipo_nomina = a.cod_tipo_nomina) AS denominacion_nomina, a.cod_cargo, a.cod_puesto, ( SELECT devolver_denominacion_puesto(( SELECT xy.clasificacion_personal
                   FROM cnmd01 xy
                  WHERE xy.cod_presi = a.cod_presi AND xy.cod_entidad = a.cod_entidad AND xy.cod_tipo_inst = a.cod_tipo_inst AND xy.cod_inst = a.cod_inst AND xy.cod_dep = a.cod_dep AND xy.cod_tipo_nomina = a.cod_tipo_nomina), a.cod_puesto) AS devolver_denominacion_puesto) AS demonimacion_puesto, a.sueldo_basico, a.compensaciones, a.primas, a.bonos, a.cod_dir_superior, a.cod_coordinacion, a.cod_secretaria, a.cod_direccion, a.cod_division, a.cod_departamento, a.cod_oficina, ( SELECT xa.denominacion
           FROM cugd02_direccionsuperior xa
          WHERE xa.cod_tipo_institucion = a.cod_tipo_inst AND xa.cod_institucion = a.cod_inst AND xa.cod_dependencia = a.cod_dep AND xa.cod_dir_superior = a.cod_dir_superior
          GROUP BY xa.denominacion) AS deno_cod_dir_superior, ( SELECT xb.denominacion
           FROM cugd02_coordinacion xb
          WHERE xb.cod_tipo_institucion = a.cod_tipo_inst AND xb.cod_institucion = a.cod_inst AND xb.cod_dependencia = a.cod_dep AND xb.cod_dir_superior = a.cod_dir_superior AND xb.cod_coordinacion = a.cod_coordinacion
          GROUP BY xb.denominacion) AS deno_cod_coordinacion, ( SELECT xc.denominacion
           FROM cugd02_secretaria xc
          WHERE xc.cod_tipo_institucion = a.cod_tipo_inst AND xc.cod_institucion = a.cod_inst AND xc.cod_dependencia = a.cod_dep AND xc.cod_dir_superior = a.cod_dir_superior AND xc.cod_coordinacion = a.cod_coordinacion AND xc.cod_secretaria = a.cod_secretaria
          GROUP BY xc.denominacion) AS deno_cod_secretaria, ( SELECT xd.denominacion
           FROM cugd02_direccion xd
          WHERE xd.cod_tipo_institucion = a.cod_tipo_inst AND xd.cod_institucion = a.cod_inst AND xd.cod_dependencia = a.cod_dep AND xd.cod_dir_superior = a.cod_dir_superior AND xd.cod_coordinacion = a.cod_coordinacion AND xd.cod_secretaria = a.cod_secretaria AND xd.cod_direccion = a.cod_direccion
          GROUP BY xd.denominacion) AS deno_cod_direccion, ( SELECT xe.denominacion
           FROM cugd02_division xe
          WHERE xe.cod_tipo_institucion = a.cod_tipo_inst AND xe.cod_institucion = a.cod_inst AND xe.cod_dependencia = a.cod_dep AND xe.cod_dir_superior = a.cod_dir_superior AND xe.cod_coordinacion = a.cod_coordinacion AND xe.cod_secretaria = a.cod_secretaria AND xe.cod_direccion = a.cod_direccion AND xe.cod_division = a.cod_division
          GROUP BY xe.denominacion) AS deno_cod_division, ( SELECT xf.denominacion
           FROM cugd02_departamento xf
          WHERE xf.cod_tipo_institucion = a.cod_tipo_inst AND xf.cod_institucion = a.cod_inst AND xf.cod_dependencia = a.cod_dep AND xf.cod_dir_superior = a.cod_dir_superior AND xf.cod_coordinacion = a.cod_coordinacion AND xf.cod_secretaria = a.cod_secretaria AND xf.cod_direccion = a.cod_direccion AND xf.cod_division = a.cod_division AND xf.cod_departamento = a.cod_departamento
          GROUP BY xf.denominacion) AS deno_cod_departamento, ( SELECT xg.denominacion
           FROM cugd02_oficina xg
          WHERE xg.cod_tipo_institucion = a.cod_tipo_inst AND xg.cod_institucion = a.cod_inst AND xg.cod_dependencia = a.cod_dep AND xg.cod_dir_superior = a.cod_dir_superior AND xg.cod_coordinacion = a.cod_coordinacion AND xg.cod_secretaria = a.cod_secretaria AND xg.cod_direccion = a.cod_direccion AND xg.cod_division = a.cod_division AND xg.cod_departamento = a.cod_departamento AND xg.cod_oficina = a.cod_oficina
          GROUP BY xg.denominacion) AS deno_cod_oficina, a.cod_estado, a.cod_municipio, a.cod_parroquia, a.cod_centro, ( SELECT xya.denominacion
           FROM cugd01_estados xya
          WHERE xya.cod_republica = a.cod_presi AND xya.cod_estado = a.cod_estado
          GROUP BY xya.denominacion) AS deno_cod_estado, ( SELECT xyb.denominacion
           FROM cugd01_municipios xyb
          WHERE xyb.cod_republica = a.cod_presi AND xyb.cod_estado = a.cod_estado AND xyb.cod_municipio = a.cod_municipio
          GROUP BY xyb.denominacion) AS deno_cod_municipio, ( SELECT xyc.denominacion
           FROM cugd01_parroquias xyc
          WHERE xyc.cod_republica = a.cod_presi AND xyc.cod_estado = a.cod_estado AND xyc.cod_municipio = a.cod_municipio AND xyc.cod_parroquia = a.cod_parroquia
          GROUP BY xyc.denominacion) AS deno_cod_parroquia, ( SELECT xyd.denominacion
           FROM cugd01_centros_poblados xyd
          WHERE xyd.cod_republica = a.cod_presi AND xyd.cod_estado = a.cod_estado AND xyd.cod_municipio = a.cod_municipio AND xyd.cod_parroquia = a.cod_parroquia AND xyd.cod_centro = a.cod_centro
          GROUP BY xyd.denominacion) AS deno_cod_centro, ( SELECT xyb.conocido
           FROM cugd01_municipios xyb
          WHERE xyb.cod_republica = a.cod_presi AND xyb.cod_estado = a.cod_estado AND xyb.cod_municipio = a.cod_municipio
          GROUP BY xyb.conocido) AS deno_ciudad, a.condicion_actividad, a.ano, a.cod_sector, a.cod_programa, a.cod_sub_prog, a.cod_proyecto, a.cod_activ_obra, a.cod_partida, a.cod_generica, a.cod_especifica, a.cod_sub_espec, a.cod_auxiliar, a.cod_nivel_i, a.cod_nivel_ii, ( SELECT x.denominacion
           FROM cnmd04_tipo x
          WHERE x.cod_nivel_i = a.cod_nivel_i) AS denominacion_cod_nivel_i, ( SELECT x.denominacion
           FROM cnmd04_ocupacion x
          WHERE x.cod_nivel_i = a.cod_nivel_i AND x.cod_nivel_ii = a.cod_nivel_ii) AS denominacion_cod_nivel_ii, a.cod_ficha
   FROM cnmd05 a;

ALTER TABLE v_datos_para_ficha OWNER TO sisap;
