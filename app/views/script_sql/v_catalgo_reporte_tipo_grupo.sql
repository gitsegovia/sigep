

-- DROP VIEW v_catalgo_reporte_tipo_grupo;

CREATE OR REPLACE VIEW v_catalgo_reporte_tipo_grupo AS





select

	  a.codigo_prod_serv,
	  a.cod_tipo,
	  a.denominacion,
	  a.especificaciones,
	  a.cod_medida,
          (select x.expresion    from cscd01_unidad_medida x where x.cod_medida = a.cod_medida     ) as expresion_medida,
          (select x.denominacion from cscd01_unidad_medida x where x.cod_medida = a.cod_medida     ) as denominacion_medida,
	  a.cod_partida,
	  a.cod_generica,
	  a.cod_especifica,
	  a.cod_sub_espec,
	  a.cod_auxiliar,
	  a.exento_iva,
	  a.alicuota_iva,
	  a.cod_snc,
          substr(a.cod_snc, 1, 3) as cod_grupo_3,
          substr(a.cod_snc, 1, 5) as cod_grupo_5,
          mayus_acentos((select x.denominacion from cscd01_snc_grupo x where cod_grupo=substr(a.cod_snc,1, 3))) as denominacion_cod_grupo_3,
          mayus_acentos((select x.denominacion from cscd01_snc_grupo x where cod_grupo=substr(a.cod_snc,1, 5))) as denominacion_cod_grupo_5,
          mayus_acentos((select x.denominacion from cscd01_snc_tipo  x where cod_tipo=a.cod_snc              )) as denominacion_cod_tipo

from

          cscd01_catalogo a


  ORDER BY

          a.codigo_prod_serv;


ALTER TABLE v_catalgo_reporte_tipo_grupo OWNER TO sisap;




update cnmd06_datos_personales set primer_apellido  = mayus_acentos(primer_apellido);
update cnmd06_datos_personales set segundo_apellido = mayus_acentos(segundo_apellido);
update cnmd06_datos_personales set primer_nombre    = mayus_acentos(primer_nombre);
update cnmd06_datos_personales set segundo_nombre   = mayus_acentos(segundo_nombre);

