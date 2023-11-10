

ALTER TABLE cugd02_dependencias ADD COLUMN cargo varchar(100);
COMMENT ON COLUMN cugd02_dependencias.cargo IS 'Cargo del funcionario responsable';



