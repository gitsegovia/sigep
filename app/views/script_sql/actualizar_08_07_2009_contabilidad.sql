ALTER TABLE cepd02_contratoservicio_anticipo_cuerpo RENAME numero_asiento_anticipo  TO numero_asiento_anulacion;
ALTER TABLE ccfd10_descripcion ALTER numero_documento TYPE character varying(30);
ALTER TABLE ccfd02 DROP CONSTRAINT ccfd02_1;


ALTER TABLE cfpd30_rendiciones_cuerpo ADD COLUMN cod_entidad_bancaria integer;
ALTER TABLE cfpd30_rendiciones_cuerpo ADD COLUMN cod_sucursal integer;
ALTER TABLE cfpd30_rendiciones_cuerpo ADD COLUMN cuenta_bancaria character varying(20);

COMMENT ON COLUMN cfpd30_rendiciones_cuerpo.cod_entidad_bancaria IS 'Entidades bancarias';
COMMENT ON COLUMN cfpd30_rendiciones_cuerpo.cod_sucursal IS 'Sucursales bancarias';
COMMENT ON COLUMN cfpd30_rendiciones_cuerpo.cuenta_bancaria IS 'Cuentas bancarias';


ALTER TABLE cfpd30_reintegro_cuerpo ADD COLUMN cod_entidad_bancaria integer;
ALTER TABLE cfpd30_reintegro_cuerpo ADD COLUMN cod_sucursal integer;
ALTER TABLE cfpd30_reintegro_cuerpo ADD COLUMN cuenta_bancaria character varying(20);
ALTER TABLE cfpd30_reintegro_cuerpo ADD COLUMN tipo_documento integer;
ALTER TABLE cfpd30_reintegro_cuerpo ADD COLUMN numero_documento integer;

COMMENT ON COLUMN cfpd30_reintegro_cuerpo.cod_entidad_bancaria IS 'Entidad bancaria';
COMMENT ON COLUMN cfpd30_reintegro_cuerpo.cod_sucursal IS 'Sucursal bancaria';
COMMENT ON COLUMN cfpd30_reintegro_cuerpo.cuenta_bancaria IS 'Cuenta bancaria';
COMMENT ON COLUMN cfpd30_reintegro_cuerpo.tipo_documento IS 'Tipo de documento 4.- Cheque';
COMMENT ON COLUMN cfpd30_reintegro_cuerpo.numero_documento IS 'Número de documento';




