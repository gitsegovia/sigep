DROP VIEW v_inventario_muebles_todo cascade;

CREATE OR REPLACE VIEW v_inventario_muebles_todo AS
 SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cod_tipo, ( SELECT b.denominacion
           FROM cimd01_clasificacion_tipo b
          WHERE a.cod_tipo = b.cod_tipo) AS deno_tipo, a.cod_grupo, ( SELECT c.denominacion
           FROM cimd01_clasificacion_grupo c
          WHERE a.cod_tipo = c.cod_tipo AND a.cod_grupo = c.cod_grupo) AS deno_grupo, a.cod_subgrupo, ( SELECT d.denominacion
           FROM cimd01_clasificacion_subgrupo d
          WHERE a.cod_tipo = d.cod_tipo AND a.cod_grupo = d.cod_grupo AND a.cod_subgrupo = d.cod_subgrupo) AS deno_subgrupo, a.cod_seccion, ( SELECT e.denominacion
           FROM cimd01_clasificacion_seccion e
          WHERE a.cod_tipo = e.cod_tipo AND a.cod_grupo = e.cod_grupo AND a.cod_subgrupo = e.cod_subgrupo AND a.cod_seccion = e.cod_seccion) AS deno_seccion, ( SELECT e.especificaciones
           FROM cimd01_clasificacion_seccion e
          WHERE a.cod_tipo = e.cod_tipo AND a.cod_grupo = e.cod_grupo AND a.cod_subgrupo = e.cod_subgrupo AND a.cod_seccion = e.cod_seccion) AS especificaciones, a.numero_identificacion, a.denominacion, a.cantidad, a.valor_unitario, a.cod_tipo_incorporacion, ( SELECT f.denominacion
           FROM cimd02_tipo_movimiento f
          WHERE a.cod_tipo_incorporacion = f.cod_mov AND f.cod_tipo_mov = 1) AS deno_incorporacion, a.fecha_incorporacion, a.cod_tipo_desincorporacion, ( SELECT f.denominacion
           FROM cimd02_tipo_movimiento f
          WHERE a.cod_tipo_desincorporacion = f.cod_mov AND f.cod_tipo_mov = 2) AS deno_desincorporacion, a.fecha_desincorporacion, a.cod_republica, ( SELECT g.denominacion
           FROM cugd01_republica g
          WHERE a.cod_republica = g.cod_republica) AS deno_republica, a.cod_estado, ( SELECT h.denominacion
           FROM cugd01_estados h
          WHERE a.cod_republica = h.cod_republica AND a.cod_estado = h.cod_estado) AS deno_estado, a.cod_municipio, ( SELECT i.denominacion
           FROM cugd01_municipios i
          WHERE a.cod_republica = i.cod_republica AND a.cod_estado = i.cod_estado AND a.cod_municipio = i.cod_municipio) AS deno_municipio, ( SELECT iii.conocido
           FROM cugd01_municipios iii
          WHERE a.cod_republica = iii.cod_republica AND a.cod_estado = iii.cod_estado AND a.cod_municipio = iii.cod_municipio) AS conocido, a.cod_parroquia, ( SELECT j.denominacion
           FROM cugd01_parroquias j
          WHERE a.cod_republica = j.cod_republica AND a.cod_estado = j.cod_estado AND a.cod_municipio = j.cod_municipio AND a.cod_parroquia = j.cod_parroquia) AS deno_parroquia, a.cod_centro, ( SELECT k.denominacion
           FROM cugd01_centros_poblados k
          WHERE a.cod_republica = k.cod_republica AND a.cod_estado = k.cod_estado AND a.cod_municipio = k.cod_municipio AND a.cod_parroquia = k.cod_parroquia AND a.cod_centro = k.cod_centro) AS deno_centro, a.cod_institucion, ( SELECT l.denominacion
           FROM cugd02_institucion l
          WHERE a.cod_institucion = l.cod_institucion) AS deno_institucion, a.cod_dependencia, ( SELECT m.denominacion
           FROM cugd02_dependencias m
          WHERE a.cod_institucion = m.cod_institucion AND a.cod_dependencia = m.cod_dependencia) AS deno_dependencia, a.cod_dir_superior, ( SELECT n.denominacion
           FROM cugd02_direccionsuperior n
          WHERE a.cod_institucion = n.cod_institucion AND a.cod_dependencia = n.cod_dependencia AND a.cod_dir_superior = n.cod_dir_superior) AS deno_dir_superior, a.cod_coordinacion, ( SELECT o.denominacion
           FROM cugd02_coordinacion o
          WHERE a.cod_institucion = o.cod_institucion AND a.cod_dependencia = o.cod_dependencia AND a.cod_dir_superior = o.cod_dir_superior AND a.cod_coordinacion = o.cod_coordinacion) AS deno_coordinacion, a.cod_secretaria, ( SELECT p.denominacion
           FROM cugd02_secretaria p
          WHERE a.cod_institucion = p.cod_institucion AND a.cod_dependencia = p.cod_dependencia AND a.cod_dir_superior = p.cod_dir_superior AND a.cod_coordinacion = p.cod_coordinacion AND a.cod_secretaria = p.cod_secretaria) AS deno_secretaria, a.cod_direccion, ( SELECT q.denominacion
           FROM cugd02_direccion q
          WHERE a.cod_institucion = q.cod_institucion AND a.cod_dependencia = q.cod_dependencia AND a.cod_dir_superior = q.cod_dir_superior AND a.cod_coordinacion = q.cod_coordinacion AND a.cod_secretaria = q.cod_secretaria AND a.cod_direccion = q.cod_direccion) AS deno_direccion, a.cod_division, ( SELECT r.denominacion
           FROM cugd02_division r
          WHERE a.cod_institucion = r.cod_institucion AND a.cod_dependencia = r.cod_dependencia AND a.cod_dir_superior = r.cod_dir_superior AND a.cod_coordinacion = r.cod_coordinacion AND a.cod_secretaria = r.cod_secretaria AND a.cod_direccion = r.cod_direccion AND a.cod_division = r.cod_division) AS deno_division, a.cod_departamento, ( SELECT s.denominacion
           FROM cugd02_departamento s
          WHERE a.cod_institucion = s.cod_institucion AND a.cod_dependencia = s.cod_dependencia AND a.cod_dir_superior = s.cod_dir_superior AND a.cod_coordinacion = s.cod_coordinacion AND a.cod_secretaria = s.cod_secretaria AND a.cod_direccion = s.cod_direccion AND a.cod_division = s.cod_division AND a.cod_departamento = s.cod_departamento) AS deno_departamento, a.cod_oficina, ( SELECT t.denominacion
           FROM cugd02_oficina t
          WHERE a.cod_institucion = t.cod_institucion AND a.cod_dependencia = t.cod_dependencia AND a.cod_dir_superior = t.cod_dir_superior AND a.cod_coordinacion = t.cod_coordinacion AND a.cod_secretaria = t.cod_secretaria AND a.cod_direccion = t.cod_direccion AND a.cod_division = t.cod_division AND a.cod_departamento = t.cod_departamento AND a.cod_oficina = t.cod_oficina) AS deno_oficina,
           (((((mascara_cero(a.cod_tipo) || mascara_cero(a.cod_grupo)) || mascara_cero(a.cod_subgrupo)) || mascara_cero(a.cod_seccion)) || mascara_cero(a.numero_identificacion) || ' '::text) || a.denominacion AS buscar
   FROM cimd03_inventario_muebles a;
ALTER TABLE v_inventario_muebles_todo OWNER TO sisap;












-- View: v_cimd04_vehiculo_asegurado_todo

-- DROP VIEW v_cimd04_vehiculo_asegurado_todo;

CREATE OR REPLACE VIEW v_cimd04_vehiculo_asegurado_todo AS
 SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cod_tipo, b.deno_tipo, a.cod_grupo, b.deno_grupo, a.cod_subgrupo, b.deno_subgrupo, a.cod_seccion, b.deno_seccion, b.cod_estado, b.deno_estado, b.cod_municipio, b.deno_municipio, b.cod_parroquia, b.deno_parroquia, b.cod_centro, b.deno_centro, b.cod_institucion, b.deno_institucion, b.cod_dependencia, b.deno_dependencia, b.cod_dir_superior, b.deno_dir_superior, b.cod_coordinacion, b.deno_coordinacion, b.cod_secretaria, b.deno_secretaria, b.cod_direccion, b.deno_direccion, b.cod_division, b.deno_division, b.cod_departamento, b.deno_departamento, b.cod_oficina, b.deno_oficina, a.numero_identificacion, b.denominacion, a.placa, a.compania_aseguradora, a.numero_poliza, a.monto_cobertura, a.descripcion_cobertura, a.vehiculo_asignado
   FROM cimd04_vehiculo_asegurado a, v_inventario_muebles_todo b
  WHERE a.numero_identificacion = b.numero_identificacion;

ALTER TABLE v_cimd04_vehiculo_asegurado_todo OWNER TO sisap;











-- View: v_inventario_inmuebles_todo

 DROP VIEW v_inventario_inmuebles_todo;

CREATE OR REPLACE VIEW v_inventario_inmuebles_todo AS
 SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cod_tipo, ( SELECT b.denominacion
           FROM cimd01_clasificacion_tipo b
          WHERE a.cod_tipo = b.cod_tipo) AS deno_tipo, a.cod_grupo, ( SELECT c.denominacion
           FROM cimd01_clasificacion_grupo c
          WHERE a.cod_tipo = c.cod_tipo AND a.cod_grupo = c.cod_grupo) AS deno_grupo, a.cod_subgrupo, ( SELECT d.denominacion
           FROM cimd01_clasificacion_subgrupo d
          WHERE a.cod_tipo = d.cod_tipo AND a.cod_grupo = d.cod_grupo AND a.cod_subgrupo = d.cod_subgrupo) AS deno_subgrupo, a.cod_seccion, ( SELECT e.denominacion
           FROM cimd01_clasificacion_seccion e
          WHERE a.cod_tipo = e.cod_tipo AND a.cod_grupo = e.cod_grupo AND a.cod_subgrupo = e.cod_subgrupo AND a.cod_seccion = e.cod_seccion) AS deno_seccion, ( SELECT e.especificaciones
           FROM cimd01_clasificacion_seccion e
          WHERE a.cod_tipo = e.cod_tipo AND a.cod_grupo = e.cod_grupo AND a.cod_subgrupo = e.cod_subgrupo AND a.cod_seccion = e.cod_seccion) AS especificaciones, a.numero_identificacion, (((((a.cod_tipo::text || a.cod_grupo::text) || a.cod_subgrupo::text) || a.cod_seccion::text) || a.numero_identificacion::text) || ' '::text) || a.denominacion_inmueble::text AS buscar, a.denominacion_inmueble, a.cod_republica, ( SELECT g.denominacion
           FROM cugd01_republica g
          WHERE a.cod_republica = g.cod_republica) AS deno_republica, a.cod_estado, ( SELECT h.denominacion
           FROM cugd01_estados h
          WHERE a.cod_republica = h.cod_republica AND a.cod_estado = h.cod_estado) AS deno_estado, a.cod_municipio, ( SELECT i.denominacion
           FROM cugd01_municipios i
          WHERE a.cod_republica = i.cod_republica AND a.cod_estado = i.cod_estado AND a.cod_municipio = i.cod_municipio) AS deno_municipio, a.cod_parroquia, ( SELECT j.denominacion
           FROM cugd01_parroquias j
          WHERE a.cod_republica = j.cod_republica AND a.cod_estado = j.cod_estado AND a.cod_municipio = j.cod_municipio AND a.cod_parroquia = j.cod_parroquia) AS deno_parroquia, a.cod_centro, ( SELECT k.denominacion
           FROM cugd01_centros_poblados k
          WHERE a.cod_republica = k.cod_republica AND a.cod_estado = k.cod_estado AND a.cod_municipio = k.cod_municipio AND a.cod_parroquia = k.cod_parroquia AND a.cod_centro = k.cod_centro) AS deno_centro, a.cod_vialidad, a.area_total_terreno, a.area_cubierta, a.area_construccion, a.area_otras_instalaciones, a.area_total_construida, a.avaluo_actual, a.descripcion_inmueble, a.linderos, a.estudio_legal_propiedad, a.avaluo_comision, a.cod_tipo_incorporacion, ( SELECT f.denominacion
           FROM cimd02_tipo_movimiento f
          WHERE a.cod_tipo_incorporacion = f.cod_mov AND f.cod_tipo_mov = 1) AS deno_incorporacion, a.fecha_incorporacion, a.cod_tipo_desincorporacion, ( SELECT f.denominacion
           FROM cimd02_tipo_movimiento f
          WHERE a.cod_tipo_desincorporacion = f.cod_mov AND f.cod_tipo_mov = 2) AS deno_desincorporacion, a.fecha_desincorporacion
   FROM cimd03_inventario_inmuebles a;

ALTER TABLE v_inventario_inmuebles_todo OWNER TO sisap;



