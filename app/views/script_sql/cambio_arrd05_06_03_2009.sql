ALTER TABLE arrd05 ADD COLUMN condicion_actividad integer DEFAULT 1;

COMMENT ON COLUMN arrd05.condicion_actividad IS 'Condición de actividad
		1.- Activo
		2.- Suspendido';

