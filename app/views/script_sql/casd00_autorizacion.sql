-- Table: casd00_autorizacion

-- DROP TABLE casd00_autorizacion;

CREATE TABLE casd00_autorizacion
(
  username character varying(60) NOT NULL, -- Lógin del usuario
  datos_personales integer DEFAULT 2, -- Datos personales?...
  solicitudes integer DEFAULT 2, -- Solicitudes?...
  evaluaciones integer DEFAULT 2, -- Evaluaciones?...
  ayudas integer DEFAULT 2, -- Ayudas?...
  tipo_ayuda integer DEFAULT 2, -- Tipo de ayudas?...
  graficos integer DEFAULT 2, -- Gráficos?...
  reportes integer DEFAULT 2, -- Reportes?...
  CONSTRAINT casd00_autorizacion_pkey PRIMARY KEY (username)
)
WITH (OIDS=FALSE);
ALTER TABLE casd00_autorizacion OWNER TO sisap;
COMMENT ON TABLE casd00_autorizacion IS 'Se registra autorizaciones para entrar a los programa';
COMMENT ON COLUMN casd00_autorizacion.username IS 'Lógin del usuario
';
COMMENT ON COLUMN casd00_autorizacion.datos_personales IS 'Datos personales?
1.-Si
2.-No
';
COMMENT ON COLUMN casd00_autorizacion.solicitudes IS 'Solicitudes?
1.-Si
2.-No';
COMMENT ON COLUMN casd00_autorizacion.evaluaciones IS 'Evaluaciones?
1.-Si
2.-No';
COMMENT ON COLUMN casd00_autorizacion.ayudas IS 'Ayudas?
1.-Si
2.-No';
COMMENT ON COLUMN casd00_autorizacion.tipo_ayuda IS 'Tipo de ayudas?
1.-Si
2.-No';
COMMENT ON COLUMN casd00_autorizacion.graficos IS 'Gráficos?
1.-Si
2.-No';
COMMENT ON COLUMN casd00_autorizacion.reportes IS 'Reportes?
1.-Si
2.-No';

