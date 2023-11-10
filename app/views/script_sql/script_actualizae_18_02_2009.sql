
ALTER TABLE cnmd07_transacciones_actuales ADD COLUMN dias_horas int4;
COMMENT ON COLUMN cnmd07_transacciones_actuales.dias_horas IS 'Dias o Horas';



ALTER TABLE cnmd07_transacciones_quecobran_incompleto ADD COLUMN dias_horas int4;
COMMENT ON COLUMN cnmd07_transacciones_quecobran_incompleto.dias_horas IS 'Dias o Horas';



ALTER TABLE cnmd07_transacciones_suspendidas ADD COLUMN dias_horas int4;
COMMENT ON COLUMN cnmd07_transacciones_suspendidas.dias_horas IS 'Dias o Horas';



ALTER TABLE cnmd07_transacciones_viadiskette ADD COLUMN dias_horas int4;
COMMENT ON COLUMN cnmd07_transacciones_viadiskette.dias_horas IS 'Dias o Horas';


ALTER TABLE cnmd08_historia_transacciones ADD COLUMN dias_horas int4;
COMMENT ON COLUMN cnmd08_historia_transacciones.dias_horas IS 'Dias o Horas';



