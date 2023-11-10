


-- DROP VIEW v_cscd01_snc_grupo;

CREATE OR REPLACE VIEW v_cscd01_snc_grupo AS

select
  a.cod_grupo,
  substr(a.cod_grupo, 1, 3) as cod_grupo_3,
  substr(a.cod_grupo, 1, 5) as cod_grupo_5,
  a.denominacion,
  a.descripcion


from

          cscd01_snc_grupo a


  ORDER BY

          a.cod_grupo ASC;


ALTER TABLE v_cscd01_snc_grupo OWNER TO sisap;