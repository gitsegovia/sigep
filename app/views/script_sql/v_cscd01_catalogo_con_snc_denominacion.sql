DROP VIEW v_cscd01_catalogo_con_snc_denominacion;

CREATE OR REPLACE VIEW v_cscd01_catalogo_con_snc_denominacion AS



  SELECT

      a.codigo_prod_serv,
      a.cod_tipo,
       mayus_acentos(a.denominacion) as denominacion,
      a.especificaciones,
      a.cod_medida,
      a.cod_partida,
      a.cod_generica,
      a.cod_especifica,
      a.cod_sub_espec,
      a.cod_auxiliar,
      a.exento_iva,
      a.alicuota_iva,
      a.cod_snc,
      (select x.expresion    from cscd01_unidad_medida x where x.cod_medida = a.cod_medida                    ) as expresion_medida,
      (select x.denominacion from cscd01_unidad_medida x where x.cod_medida = a.cod_medida                    ) as denominacion_medida,
       mayus_acentos((select y.denominacion from cscd01_snc_tipo y where y.cod_tipo = a.cod_snc               )) as denominacion_snc,
      ((mascara_cero(a.cod_partida)::text) || (mascara_cero(a.cod_generica)::text) || (mascara_cero(a.cod_especifica)::text) || (mascara_cero(a.cod_sub_espec)::text) || (mascara_cero(a.cod_auxiliar)::text)) as partida_presupuestaria



  FROM

        cscd01_catalogo a




  ORDER BY

          a.codigo_prod_serv;





ALTER TABLE v_cscd01_catalogo_con_snc_denominacion OWNER TO sisap;


