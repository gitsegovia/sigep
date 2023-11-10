DROP VIEW v_cfpd01_especifica_concatenado;

CREATE OR REPLACE VIEW v_cfpd01_especifica_concatenado AS

  SELECT

		  a.cod_grupo,
		  a.cod_partida,
		  a.cod_generica,
		  a.cod_especifica,
		  a.descripcion,
		  a.concepto,
		  ((a.cod_grupo::text) || (mascara_cero(a.cod_partida)::text) || (mascara_cero(a.cod_generica)::text) || (mascara_cero(a.cod_especifica)::text)) as partida_presupuestaria



  FROM

        cfpd01_especifica a;

ALTER TABLE v_cfpd01_especifica_concatenado OWNER TO sisap;


