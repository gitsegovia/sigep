
drop view  v_cfpd97_cargos;
CREATE OR REPLACE VIEW v_cfpd97_cargos AS
 SELECT
 a.cod_presi,
 a.cod_entidad,
 a.cod_tipo_inst,
 a.cod_inst,
 a.cod_dep,
 a.cod_tipo_nomina,
 ( SELECT b.denominacion FROM cnmd01 b WHERE a.cod_presi=b.cod_presi and a.cod_entidad=b.cod_entidad and  a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep and a.cod_tipo_nomina = b.cod_tipo_nomina) AS tipo_nomina,
 ( SELECT b.clasificacion_personal FROM cnmd01 b WHERE a.cod_presi=b.cod_presi and a.cod_entidad=b.cod_entidad and  a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep and a.cod_tipo_nomina = b.cod_tipo_nomina) AS clasificacion_personal,
 a.cod_cargo,
 a.cod_puesto,
 a.cod_dir_superior,
 a.cod_coordinacion,
 a.cod_secretaria,
 a.cod_direccion,
 a.cod_division,
 a.cod_departamento,
 a.cod_oficina,
 ( SELECT b.denominacion_clase FROM v_puestos b WHERE a.cod_puesto=b.cod_puesto) AS denominacion_clase,
 ( SELECT b.denominacion FROM cugd02_direccionsuperior b WHERE a.cod_tipo_inst = b.cod_tipo_institucion AND a.cod_inst = b.cod_institucion AND a.cod_dep = b.cod_dependencia AND a.cod_dir_superior = b.cod_dir_superior) AS dir_superior,
 ( SELECT b.denominacion FROM cugd02_coordinacion b WHERE a.cod_tipo_inst = b.cod_tipo_institucion AND a.cod_inst = b.cod_institucion AND a.cod_dep = b.cod_dependencia AND a.cod_dir_superior = b.cod_dir_superior and a.cod_coordinacion = b.cod_coordinacion) AS coordinacion,
 ( SELECT b.denominacion FROM cugd02_secretaria b WHERE a.cod_tipo_inst = b.cod_tipo_institucion AND a.cod_inst = b.cod_institucion AND a.cod_dep = b.cod_dependencia AND a.cod_dir_superior = b.cod_dir_superior and a.cod_coordinacion = b.cod_coordinacion and a.cod_secretaria=b.cod_secretaria) AS secretaria,
 ( SELECT b.denominacion FROM cugd02_direccion b WHERE a.cod_tipo_inst = b.cod_tipo_institucion AND a.cod_inst = b.cod_institucion AND a.cod_dep = b.cod_dependencia AND a.cod_dir_superior = b.cod_dir_superior and a.cod_coordinacion = b.cod_coordinacion and a.cod_secretaria=b.cod_secretaria and a.cod_direccion=b.cod_direccion) AS direccion,
 ( SELECT b.denominacion FROM cugd02_division b WHERE a.cod_tipo_inst = b.cod_tipo_institucion AND a.cod_inst = b.cod_institucion AND a.cod_dep = b.cod_dependencia AND a.cod_dir_superior = b.cod_dir_superior and a.cod_coordinacion = b.cod_coordinacion and a.cod_secretaria=b.cod_secretaria and a.cod_direccion=b.cod_direccion and a.cod_division=b.cod_division) AS division,
 ( SELECT b.denominacion FROM cugd02_departamento b WHERE a.cod_tipo_inst = b.cod_tipo_institucion AND a.cod_inst = b.cod_institucion AND a.cod_dep = b.cod_dependencia AND a.cod_dir_superior = b.cod_dir_superior and a.cod_coordinacion = b.cod_coordinacion and a.cod_secretaria=b.cod_secretaria and a.cod_direccion=b.cod_direccion and a.cod_division=b.cod_division and a.cod_departamento=b.cod_departamento) AS departamento,
 ( SELECT b.denominacion FROM cugd02_oficina b WHERE a.cod_tipo_inst = b.cod_tipo_institucion AND a.cod_inst = b.cod_institucion AND a.cod_dep = b.cod_dependencia AND a.cod_dir_superior = b.cod_dir_superior and a.cod_coordinacion = b.cod_coordinacion and a.cod_secretaria=b.cod_secretaria and a.cod_direccion=b.cod_direccion and a.cod_division=b.cod_division and a.cod_departamento=b.cod_departamento and a.cod_oficina=b.cod_oficina) AS oficina,
a.ano,
a.cod_sector,
a.cod_programa,
a.cod_sub_prog,
a.cod_proyecto,
a.cod_activ_obra,
a.cod_partida,
a.cod_generica,
a.cod_especifica,
a.cod_sub_espec,
a.cod_auxiliar,
a.cod_nivel_i,
a.cod_nivel_ii,
( SELECT b.denominacion FROM cnmd04_ocupacion b WHERE b.cod_nivel_i = a.cod_nivel_i AND b.cod_nivel_ii = a.cod_nivel_ii) AS deno_nivel,
a.cod_ficha,
( SELECT b.denominacion FROM cfpd02_sector b WHERE b.cod_presi = a.cod_presi AND b.cod_entidad = a.cod_entidad AND b.cod_tipo_inst = a.cod_tipo_inst AND b.cod_inst = a.cod_inst AND b.cod_dep = a.cod_dep AND b.ano = a.ano AND b.cod_sector = a.cod_sector) AS deno_sector,
( SELECT b.denominacion FROM cfpd02_programa b WHERE b.cod_presi = a.cod_presi AND b.cod_entidad = a.cod_entidad AND b.cod_tipo_inst = a.cod_tipo_inst AND b.cod_inst = a.cod_inst AND b.cod_dep = a.cod_dep AND b.ano = a.ano AND b.cod_sector = a.cod_sector AND b.cod_programa = a.cod_programa) AS deno_programa,
( SELECT b.denominacion FROM cfpd02_sub_prog b WHERE b.cod_presi = a.cod_presi AND b.cod_entidad = a.cod_entidad AND b.cod_tipo_inst = a.cod_tipo_inst AND b.cod_inst = a.cod_inst AND b.cod_dep = a.cod_dep AND b.ano = a.ano AND b.cod_sector = a.cod_sector AND b.cod_programa = a.cod_programa AND b.cod_sub_prog = a.cod_sub_prog) AS deno_sub_prog,
( SELECT b.denominacion FROM cfpd02_proyecto b WHERE b.cod_presi = a.cod_presi AND b.cod_entidad = a.cod_entidad AND b.cod_tipo_inst = a.cod_tipo_inst AND b.cod_inst = a.cod_inst AND b.cod_dep = a.cod_dep AND b.ano = a.ano AND b.cod_sector = a.cod_sector AND b.cod_programa = a.cod_programa AND b.cod_sub_prog = a.cod_sub_prog AND b.cod_proyecto = a.cod_proyecto) AS deno_proyecto,
( SELECT b.denominacion FROM cfpd02_activ_obra b WHERE b.cod_presi = a.cod_presi AND b.cod_entidad = a.cod_entidad AND b.cod_tipo_inst = a.cod_tipo_inst AND b.cod_inst = a.cod_inst AND b.cod_dep = a.cod_dep AND b.ano = a.ano AND b.cod_sector = a.cod_sector AND b.cod_programa = a.cod_programa AND b.cod_sub_prog = a.cod_sub_prog AND b.cod_proyecto = a.cod_proyecto AND b.cod_activ_obra = a.cod_activ_obra) AS deno_activ_obra,
( SELECT x.denominacion FROM cfpd01_ano_2_partida x WHERE x.ejercicio = a.ano AND x.cod_grupo = substr(a.cod_partida::text, 0, 2)::integer AND x.cod_partida = substr(a.cod_partida::text, 2)::integer) AS deno_partida,
( SELECT x.denominacion FROM cfpd01_ano_3_generica x WHERE x.ejercicio = a.ano AND x.cod_grupo = substr(a.cod_partida::text, 0, 2)::integer AND x.cod_partida = substr(a.cod_partida::text, 2)::integer AND x.cod_generica = a.cod_generica) AS deno_generica,
( SELECT x.denominacion FROM cfpd01_ano_4_especifica x WHERE x.ejercicio = a.ano AND x.cod_grupo = substr(a.cod_partida::text, 0, 2)::integer AND x.cod_partida = substr(a.cod_partida::text, 2)::integer AND x.cod_generica = a.cod_generica AND x.cod_especifica = a.cod_especifica) AS deno_especifica,
( SELECT x.denominacion FROM cfpd01_ano_5_sub_espec x WHERE x.ejercicio = a.ano AND x.cod_grupo = substr(a.cod_partida::text, 0, 2)::integer AND x.cod_partida = substr(a.cod_partida::text, 2)::integer AND x.cod_generica = a.cod_generica AND x.cod_especifica = a.cod_especifica AND x.cod_sub_espec = a.cod_sub_espec) AS deno_sub_espec,
( SELECT b.denominacion FROM cfpd05_auxiliar b WHERE b.cod_presi = a.cod_presi AND b.cod_entidad = a.cod_entidad AND b.cod_tipo_inst = a.cod_tipo_inst AND b.cod_inst = a.cod_inst AND b.cod_dep = a.cod_dep AND b.ano = a.ano AND b.cod_sector = a.cod_sector AND b.cod_programa = a.cod_programa AND b.cod_sub_prog = a.cod_sub_prog AND b.cod_proyecto = a.cod_proyecto AND b.cod_activ_obra = a.cod_activ_obra AND b.cod_partida = a.cod_partida and b.cod_generica=a.cod_generica and b.cod_especifica=a.cod_especifica and b.cod_sub_espec=a.cod_sub_espec and b.cod_auxiliar=a.cod_auxiliar) AS deno_auxiliar,
 a.cod_estado,
 a.cod_municipio,
 a.cod_parroquia,
 a.cod_centro,
 ( SELECT b.denominacion FROM cugd01_estados b WHERE b.cod_estado = a.cod_estado) AS deno_estado,
 ( SELECT c.denominacion FROM cugd01_municipios c WHERE c.cod_estado = a.cod_estado AND c.cod_municipio = a.cod_municipio) AS deno_municipio,
 ( SELECT d.denominacion FROM cugd01_parroquias d WHERE d.cod_estado = a.cod_estado AND d.cod_municipio = a.cod_municipio AND d.cod_parroquia = a.cod_parroquia) AS deno_parroquia,
 ( SELECT e.denominacion FROM cugd01_centros_poblados e WHERE e.cod_estado = a.cod_estado AND e.cod_municipio = a.cod_municipio AND e.cod_parroquia = a.cod_parroquia AND e.cod_centro = a.cod_centro) AS deno_centro,
 a.condicion_actividad,
 a.sueldo_basico,
 a.compensaciones,
 a.primas,
 a.bonos
 FROM cfpd97 a
ORDER BY
a.cod_presi,
a.cod_entidad,
a.cod_tipo_inst,
a.cod_inst,
a.cod_dep,
a.cod_tipo_nomina,
a.cod_cargo;

ALTER TABLE v_cfpd97_cargos OWNER TO sisap;






















