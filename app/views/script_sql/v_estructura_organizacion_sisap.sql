-- View: v_estructura_organizacion_sisap

-- DROP VIEW v_estructura_organizacion_sisap;

CREATE OR REPLACE VIEW v_estructura_organizacion_sisap AS
 SELECT b.cod_presi, ( SELECT a.denominacion
           FROM arrd01 a
          WHERE a.cod_presi = b.cod_presi) AS denominacion_republica, b.cod_entidad, ( SELECT c.denominacion
           FROM arrd02 c
          WHERE c.cod_presi = b.cod_presi AND c.cod_entidad = b.cod_entidad) AS denominacion_estado, b.cod_tipo_inst, ( SELECT d.denominacion
           FROM arrd03 d
          WHERE d.cod_presi = b.cod_presi AND d.cod_tipo_inst = b.cod_tipo_inst) AS denominacion_tipo_institucion, b.cod_inst, ( SELECT e.denominacion
           FROM arrd04 e
          WHERE e.cod_presi = b.cod_presi AND e.cod_entidad = b.cod_entidad AND e.cod_tipo_inst = b.cod_tipo_inst AND e.cod_inst = b.cod_inst) AS denominacion_institucion, b.cod_dep, ( SELECT f.denominacion
           FROM arrd05 f
          WHERE f.cod_presi = b.cod_presi AND f.cod_entidad = b.cod_entidad AND f.cod_tipo_inst = b.cod_tipo_inst AND f.cod_inst = b.cod_inst AND f.cod_dep = b.cod_dep) AS denominacion_dependencia
   FROM arrd05 b;

ALTER TABLE v_estructura_organizacion_sisap OWNER TO sisap;

