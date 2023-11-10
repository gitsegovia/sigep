-- Function: usuarios_dep(integer)

-- DROP FUNCTION usuarios_dep(integer);

CREATE OR REPLACE FUNCTION usuarios_dep(pcod_dep integer)
  RETURNS SETOF record AS
$BODY$

/* Ej. De como invocar esta funcion:

  SELECT * FROM usuarios_dep(1) as usuarios_j (usuario character varying(60),modulo text);

*/

DECLARE
  recConsulta RECORD;

BEGIN
  FOR recConsulta IN
    SELECT username as usuario,modulo
      FROM  usuarios WHERE cod_dep=pcod_dep
  LOOP
    RETURN NEXT recConsulta;
  END LOOP;
END;

$BODY$
  LANGUAGE 'plpgsql' VOLATILE
  COST 100
  ROWS 1000;
ALTER FUNCTION usuarios_dep(integer) OWNER TO sisap;



CREATE OR REPLACE FUNCTION tabla_campos(text)
  RETURNS SETOF record AS
$BODY$

/* Ej. De como invocar esta funcion:

  SELECT * FROM tabla_campos('arrd05') as campos_tabla (nombre_tabla name,nombre_campo name,tipo_dato text,comentario_campo text);

*/

DECLARE
  recConsulta RECORD;

BEGIN
  FOR recConsulta IN
     SELECT   z.relname as nombre_tabla,  a.attname as nombre_campo,    pg_catalog.format_type(a.atttypid, a.atttypmod) as tipo_dato,
   pg_catalog.col_description(a.attrelid, a.attnum) AS comentario_campo
FROM
    pg_catalog.pg_attribute a LEFT JOIN pg_catalog.pg_attrdef adef
    ON a.attrelid=adef.adrelid
    AND a.attnum=adef.adnum
    LEFT JOIN pg_catalog.pg_type t ON a.atttypid=t.oid
    join pg_catalog.pg_class z on a.attrelid = z.oid and z.relnamespace = (SELECT oid FROM pg_catalog.pg_namespace WHERE
        nspname = 'public')
WHERE
a.attnum > 0 AND NOT a.attisdropped and (z.relname not ilike '%_seq' and z.relname not ilike '%_pkey' and z.relname not ilike '%_index') and z.relname ilike lower('%'||$1||'%')
ORDER BY z.relname, a.attnum
  LOOP
    RETURN NEXT recConsulta;
  END LOOP;
END;

$BODY$
  LANGUAGE 'plpgsql' VOLATILE
  COST 100
  ROWS 1000;
ALTER FUNCTION tabla_campos(text) OWNER TO sisap;