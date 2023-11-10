

ALTER TABLE cstd03_cheque_cuerpo ADD COLUMN ano_anterior int4 DEFAULT 2;
COMMENT ON COLUMN cstd03_cheque_cuerpo.ano_anterior IS 'Año anterior
1.-Si
2.-No';



ALTER TABLE cstd07_retenciones_cuerpo_islr ADD COLUMN ano_anterior int4 DEFAULT 2;
COMMENT ON COLUMN cstd07_retenciones_cuerpo_islr.ano_anterior IS 'Año anterior
1.- Si
2.- No
';



ALTER TABLE cstd07_retenciones_cuerpo_iva ADD COLUMN ano_anterior int4 DEFAULT 2;
COMMENT ON COLUMN cstd07_retenciones_cuerpo_iva.ano_anterior IS 'Año anterior
1.- Si
2.- No
';



ALTER TABLE cstd07_retenciones_cuerpo_municipal ADD COLUMN ano_anterior int4 DEFAULT 2;
COMMENT ON COLUMN cstd07_retenciones_cuerpo_municipal.ano_anterior IS 'Año anterior
1.- Si
2.- No
';


ALTER TABLE cstd07_retenciones_cuerpo_timbre ADD COLUMN ano_anterior int4 DEFAULT 2;
COMMENT ON COLUMN cstd07_retenciones_cuerpo_timbre.ano_anterior IS 'Año anterior
1.- Si
2.- No
';





ALTER TABLE cstd09_notadebito_cuerpo ADD COLUMN ano_anterior int4 DEFAULT 2;
COMMENT ON COLUMN cstd09_notadebito_cuerpo.ano_anterior IS 'Año anterior
1.- Si
2.- No
';



