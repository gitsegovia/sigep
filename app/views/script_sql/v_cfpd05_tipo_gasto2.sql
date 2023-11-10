-- View: v_cfpd05_tipo_gasto2

-- DROP VIEW v_cfpd05_tipo_gasto2;

CREATE OR REPLACE VIEW v_cfpd05_tipo_gasto2 AS 

 SELECT 
 a.cod_presi, 
 a.cod_entidad, 
 a.cod_tipo_inst, 
 a.cod_inst, 
 a.cod_dep, 
 a.ano, 
 null_cero(a.gasto_inversion) AS gasto_inversion, 
 null_cero(b.gasto_corriente) AS gasto_corriente, 
 null_cero(a.gasto_inversion) + null_cero(b.gasto_corriente) AS total
 

 FROM v_cfpd05_gasto_inversion2 a, v_cfpd05_gasto_corriente2 b
 
 WHERE 

 a.cod_presi     = b.cod_presi AND 
 a.cod_entidad   = b.cod_entidad AND 
 a.cod_tipo_inst = b.cod_tipo_inst AND 
 a.cod_inst      = b.cod_inst AND 
 a.cod_dep       = b.cod_dep AND
 a.ano           = b.ano;

ALTER TABLE v_cfpd05_tipo_gasto2 OWNER TO sisap;


