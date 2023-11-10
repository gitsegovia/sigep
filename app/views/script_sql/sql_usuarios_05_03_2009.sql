
ALTER TABLE usuarios ADD COLUMN condicion_actividad int4 DEFAULT 1;
ALTER TABLE usuarios ADD COLUMN cod_dir_superior int4;
ALTER TABLE usuarios ADD COLUMN cod_coordinacion int4;
ALTER TABLE usuarios ADD COLUMN cod_secretaria int4;
ALTER TABLE usuarios ADD COLUMN cod_direccion int4;
ALTER TABLE usuarios ADD COLUMN cod_division int4;
ALTER TABLE usuarios ADD COLUMN cod_departamento int4;
ALTER TABLE usuarios ADD COLUMN cod_oficina int4;
COMMENT ON COLUMN usuarios.condicion_actividad IS 'Condición de actividad
1.- Activo
2.- Suspendido
';
COMMENT ON COLUMN usuarios.cod_dir_superior IS 'Código de la dirección superior
';
COMMENT ON COLUMN usuarios.cod_coordinacion IS 'Código coordinación
';
COMMENT ON COLUMN usuarios.cod_secretaria IS 'Código de la secretaría';
COMMENT ON COLUMN usuarios.cod_direccion IS 'Código de dirección';
COMMENT ON COLUMN usuarios.cod_division IS 'Código de división';
COMMENT ON COLUMN usuarios.cod_departamento IS 'Código del departamento
';
COMMENT ON COLUMN usuarios.cod_oficina IS 'Código de la oficina';

