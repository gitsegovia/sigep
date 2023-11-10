DROP VIEW v_cfpd01_sub_espec_concatenado;

CREATE OR REPLACE VIEW v_cfpd01_sub_espec_concatenado AS

  SELECT

		  a.cod_grupo,
		  a.cod_partida,
		  a.cod_generica,
		  a.cod_especifica,
		  a.cod_sub_espec,
		  a.descripcion,
		  a.concepto,
		  ((a.cod_grupo::text) || (mascara_cero(a.cod_partida)::text) || (mascara_cero(a.cod_generica)::text) || (mascara_cero(a.cod_especifica)::text) || (mascara_cero(a.cod_sub_espec)::text)) as partida_presupuestaria



  FROM

        cfpd01_sub_espec a;

ALTER TABLE v_cfpd01_sub_espec_concatenado OWNER TO sisap;


