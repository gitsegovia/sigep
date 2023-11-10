
--CONTRATO DE OBRAS


ALTER TABLE cobd01_contratoobras_cuerpo ADD COLUMN saldo_ano_anterior int4 DEFAULT 2;
COMMENT ON COLUMN cobd01_contratoobras_cuerpo.saldo_ano_anterior IS 'Saldo de año anterior
Representa el registro del contrato como continuación del saldo de uno anterior
1.- Si
2.- No
';


--CONTRATO DE SERVICIOS

ALTER TABLE cepd02_contratoservicio_cuerpo ADD COLUMN saldo_ano_anterior int4 DEFAULT 2;
COMMENT ON COLUMN cepd02_contratoservicio_cuerpo.saldo_ano_anterior IS 'Saldo de año anterior
Representa el registro del convenio como continuación del saldo de uno anterior
1.- Si
2.- No';


--ORDEN DE COMPRA

ALTER TABLE cscd04_ordencompra_encabezado ADD COLUMN saldo_ano_anterior int4 DEFAULT 2;
COMMENT ON COLUMN cscd04_ordencompra_encabezado.saldo_ano_anterior IS 'Saldo de año anterior
Representa el registro de la Orden de compra como continuación del saldo de una anterior
1.- Si
2.- No

';





ALTER TABLE cstd03_movimientos_manuales ADD COLUMN colocacion int4 DEFAULT 2;
COMMENT ON COLUMN cstd03_movimientos_manuales.colocacion IS 'Colocación, representa los depositos destinados para colocar dinero y las notas de créditos que permiten registrar las rendimientos o intereses
1.- Si
2.- No
';







