-- Function: reemplazar_rif(character varying, character varying, integer)

-- DROP FUNCTION reemplazar_rif(character varying, character varying, integer);

CREATE OR REPLACE FUNCTION reemplazar_rif(character varying, character varying, integer)
  RETURNS text AS
$BODY$

DECLARE
t text;
c integer=1;


BEGIN

ALTER TABLE cscd03_cotizacion_encabezado DROP CONSTRAINT cscd03_cotizacion_encabezado_3;
ALTER TABLE cscd03_cotizacion_cuerpo DROP CONSTRAINT cscd03_cotizacion_cuerpo_1;


         t = 'si';

            c=(select count(*) from cpcd02 where rif=$1);
         if c=0 then
	           UPDATE cpcd02 set                                           rif=$1                      where rif=$2;
	     else
               DELETE from cpcd02 where rif=$2;
	     end if;

	                if $3 = 1 then


	                        c=(select count(*) from cepd01_compromiso_beneficiario_rif where rif=$1);
				         if c=0 then
					           UPDATE cepd01_compromiso_beneficiario_rif set                                           rif=$1                      where rif=$2;
					     else
				               DELETE from cepd01_compromiso_beneficiario_rif where rif=$2;
					     end if;
	                    UPDATE cepd01_compromiso_cuerpo set                         rif=$1 where rif=$2;
			            UPDATE cepd03_ordenpago_cuerpo set                          rif=$1 where rif=$2;



                    else



                            c=(select count(*) from cepd01_compromiso_beneficiario_cedula where cedula=$1::text);
				         if c=0 then
					           UPDATE cepd01_compromiso_beneficiario_cedula set            cedula=$1::text             where cedula=$2::text;
					     else
				               DELETE from cepd01_compromiso_beneficiario_cedula where cedula=$2::text;
					     end if;

             		    UPDATE cepd01_compromiso_cuerpo set                         cedula_identidad=$1::text      where cedula_identidad=$2::text;
			            UPDATE cepd03_ordenpago_cuerpo set                          cedula_identidad=$1::integer   where cedula_identidad=$2::integer;

                    end if;

	UPDATE cepd02_contratoservicio_cuerpo set                   rif=$1                      where rif=$2;
	UPDATE cepd03_ordenpago_cuerpo set                          rif=$1                      where rif=$2;
	UPDATE cscd02_solicitud_cuerpo_anulado set                  rif=$1                      where rif=$2;
	UPDATE cscd02_solicitud_encabezado set                      rif=$1                      where rif=$2;
	UPDATE cscd02_solicitud_encabezado_anulado set              rif=$1                      where rif=$2;
	UPDATE cscd03_cotizacion_cuerpo set                         rif=$1                      where rif=$2;
	UPDATE cscd03_cotizacion_cuerpo_anulado set                 rif=$1                      where rif=$2;
	UPDATE cscd03_cotizacion_encabezado set                     rif=$1                      where rif=$2;
	UPDATE cscd03_cotizacion_encabezado_anulado set             rif=$1                      where rif=$2;
	UPDATE cscd04_ordencompra_encabezado set                    rif=$1                      where rif=$2;
	UPDATE cscd05_ordencompra_nota_entrega_cuerpo set           rif=$1                      where rif=$2;
	UPDATE cscd05_ordencompra_nota_entrega_encabezado set       rif=$1                      where rif=$2;
	UPDATE cstd03_cheque_cuerpo set                      rif_cedula=$1                      where rif_cedula=$2;
	UPDATE cstd09_notadebito_cuerpo set                  rif_cedula=$1                      where rif_cedula=$2;
	UPDATE cstd30_debito_cuerpo set                      rif_cedula=$1                      where rif_cedula=$2;



ALTER TABLE cscd03_cotizacion_encabezado
  ADD CONSTRAINT cscd03_cotizacion_encabezado_3 FOREIGN KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, ano_solicitud, numero_solicitud)
      REFERENCES cscd02_solicitud_encabezado (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, ano_solicitud, numero_solicitud) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE CASCADE;

ALTER TABLE cscd03_cotizacion_cuerpo
  ADD CONSTRAINT cscd03_cotizacion_cuerpo_1 FOREIGN KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, rif, ano_cotizacion, numero_cotizacion)
      REFERENCES cscd03_cotizacion_encabezado (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, rif, ano_cotizacion, numero_cotizacion) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE CASCADE;

 return t;
END;

$BODY$
  LANGUAGE 'plpgsql' VOLATILE
  COST 100;
ALTER FUNCTION reemplazar_rif(character varying, character varying, integer) OWNER TO sisap;
