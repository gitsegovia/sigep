CREATE OR REPLACE VIEW v_f6_gr_bcv AS
 SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.ano, a.cod_partida, a.cod_generica, 0 AS cod_especifica, 0 AS cod_sub_espec, ((((substr(a.cod_partida::text, 1, 1) || '.'::text) || substr(a.cod_partida::text, 2, 2)) || '.'::text) || mascara_cero(a.cod_generica)) || '.00.00'::text AS campo_excel, sum(a.compromiso_ene + a.compromiso_feb + a.compromiso_mar) AS primer_trimestre, sum(a.compromiso_abr + a.compromiso_may + a.compromiso_jun) AS segundo_trimestre, sum(a.compromiso_jul + a.compromiso_ago + a.compromiso_sep) AS tercer_trimestre, sum(a.compromiso_oct + a.compromiso_nov + a.compromiso_dic) AS cuarto_trimestre
   FROM v_balance_ejecucion2 a
  WHERE a.cod_partida = ANY (ARRAY[401, 402, 403, 404])
  GROUP BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.ano, a.cod_partida, a.cod_generica
  ORDER BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.ano, a.cod_partida, a.cod_generica;

ALTER TABLE v_f6_gr_bcv OWNER TO sisap;