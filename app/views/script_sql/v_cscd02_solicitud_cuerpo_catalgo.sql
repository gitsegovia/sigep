

-- DROP VIEW v_cscd02_solicitud_cuerpo_catalgo;

CREATE OR REPLACE VIEW v_cscd02_solicitud_cuerpo_catalgo AS

SELECT

      a.cod_presi,
	  a.cod_entidad,
	  a.cod_tipo_inst,
	  a.cod_inst,
	  a.cod_dep,
	  a.ano_solicitud,
	  a.numero_solicitud,
	  a.codigo_prod_serv,
	  a.descripcion,
	  a.cod_medida,
	  a.cantidad,
	  a.cod_sector,
	  a.cod_programa,
	  a.cod_sub_prog,
	  a.cod_proyecto,
	  a.cod_partida,
	  a.cod_generica,
	  a.cod_especifica,
	  a.cod_sub_espec,
	  (select b.exento_iva   from cscd01_catalogo b where b.codigo_prod_serv = a.codigo_prod_serv) as exento_iva,
      (select c.alicuota_iva from cscd01_catalogo c where c.codigo_prod_serv = a.codigo_prod_serv) as alicuota_iva


FROM cscd02_solicitud_cuerpo a;


ALTER TABLE v_cscd02_solicitud_cuerpo_catalgo OWNER TO sisap;