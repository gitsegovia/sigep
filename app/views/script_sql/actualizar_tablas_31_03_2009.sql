
ALTER TABLE cstd03_movimientos_manuales ADD COLUMN status int4 DEFAULT 0;
COMMENT ON COLUMN cstd03_movimientos_manuales.status IS 'Status del cheque
1.- Custodia
2.- Transito
3.- Pagado
';



