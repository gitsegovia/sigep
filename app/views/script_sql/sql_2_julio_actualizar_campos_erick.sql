ALTER TABLE cnmd08_historia_trabajador DROP COLUMN sueldo_salario;

ALTER TABLE cnmd08_historia_trabajador RENAME dias_cancelar  TO dias_cobro;

ALTER TABLE cnmd06_fichas ADD COLUMN mensaje_personal text;
