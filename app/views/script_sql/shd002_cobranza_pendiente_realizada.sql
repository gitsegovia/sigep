-- Table: shd002_cobranza_realizada

-- DROP TABLE shd002_cobranza_realizada;

CREATE TABLE shd002_cobranza_realizada
(
  cod_presi integer NOT NULL, -- Código de la presidencia
  cod_entidad integer NOT NULL, -- Código de la entidad federal
  cod_tipo_inst integer NOT NULL, -- Códito tipo de Institución
  cod_inst integer NOT NULL, -- Código de la Institución
  cod_dep integer NOT NULL, -- Código de la dependencia
  rif_ci character varying(20) NOT NULL, -- Rif o Cédula de identidad del cobrador
  ano integer NOT NULL, -- Año actual
  cobranza_acumulada numeric(26,2) NOT NULL, -- Cobranza acumulada en años anteriores
  enero numeric(26,2) NOT NULL, -- Mes de enero
  febrero numeric(26,2) NOT NULL, -- Mes de febrero
  marzo numeric(26,2) NOT NULL, -- Mes de marzo
  abril numeric(26,2) NOT NULL, -- Mes de abril
  mayo numeric(26,2) NOT NULL, -- Mes de mayo
  junio numeric(26,2) NOT NULL, -- Mes de junio
  julio numeric(26,2) NOT NULL, -- Mes de julio
  agosto numeric(26,2) NOT NULL, -- Mes de agosto
  septiembre numeric(26,2) NOT NULL, -- Mes de septiembre
  octubre numeric(26,2) NOT NULL, -- Mes de octubre
  noviembre numeric(26,2) NOT NULL, -- Mes de noviembre
  diciembre numeric(26,2) NOT NULL, -- Mes de diciembre
  CONSTRAINT shd002_cobranza_realizada_pkey PRIMARY KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, rif_ci, ano)
)
WITH (OIDS=FALSE);
ALTER TABLE shd002_cobranza_realizada OWNER TO sisap;
COMMENT ON TABLE shd002_cobranza_realizada IS 'Registra la cobranza realizada por el cobrador';
COMMENT ON COLUMN shd002_cobranza_realizada.cod_presi IS 'Código de la presidencia';
COMMENT ON COLUMN shd002_cobranza_realizada.cod_entidad IS 'Código de la entidad federal';
COMMENT ON COLUMN shd002_cobranza_realizada.cod_tipo_inst IS 'Códito tipo de Institución';
COMMENT ON COLUMN shd002_cobranza_realizada.cod_inst IS 'Código de la Institución';
COMMENT ON COLUMN shd002_cobranza_realizada.cod_dep IS 'Código de la dependencia';
COMMENT ON COLUMN shd002_cobranza_realizada.rif_ci IS 'Rif o Cédula de identidad del cobrador';
COMMENT ON COLUMN shd002_cobranza_realizada.ano IS 'Año actual';
COMMENT ON COLUMN shd002_cobranza_realizada.cobranza_acumulada IS 'Cobranza acumulada en años anteriores';
COMMENT ON COLUMN shd002_cobranza_realizada.enero IS 'Mes de enero';
COMMENT ON COLUMN shd002_cobranza_realizada.febrero IS 'Mes de febrero';
COMMENT ON COLUMN shd002_cobranza_realizada.marzo IS 'Mes de marzo';
COMMENT ON COLUMN shd002_cobranza_realizada.abril IS 'Mes de abril';
COMMENT ON COLUMN shd002_cobranza_realizada.mayo IS 'Mes de mayo';
COMMENT ON COLUMN shd002_cobranza_realizada.junio IS 'Mes de junio';
COMMENT ON COLUMN shd002_cobranza_realizada.julio IS 'Mes de julio';
COMMENT ON COLUMN shd002_cobranza_realizada.agosto IS 'Mes de agosto';
COMMENT ON COLUMN shd002_cobranza_realizada.septiembre IS 'Mes de septiembre';
COMMENT ON COLUMN shd002_cobranza_realizada.octubre IS 'Mes de octubre';
COMMENT ON COLUMN shd002_cobranza_realizada.noviembre IS 'Mes de noviembre';
COMMENT ON COLUMN shd002_cobranza_realizada.diciembre IS 'Mes de diciembre';








-- Table: shd002_cobranza_pendiente

-- DROP TABLE shd002_cobranza_pendiente;

CREATE TABLE shd002_cobranza_pendiente
(
  cod_presi integer NOT NULL, -- Código de la presidencia
  cod_entidad integer NOT NULL, -- Código de la entidad federal
  cod_tipo_inst integer NOT NULL, -- Códito tipo de Institución
  cod_inst integer NOT NULL, -- Código de la Institución
  cod_dep integer NOT NULL, -- Código de la dependencia
  rif_ci character varying(20) NOT NULL, -- Rif o Cédula de identidad del cobrador
  ano integer NOT NULL, -- Año actual
  cobranza_pendiente_acumulada numeric(26,2) NOT NULL, -- Cobranza pendiente acumulada en años anteriores
  enero numeric(26,2) NOT NULL, -- Mes de enero
  febrero numeric(26,2) NOT NULL, -- Mes de febrero
  marzo numeric(26,2) NOT NULL, -- Mes de marzo
  abril numeric(26,2) NOT NULL, -- Mes de abril
  mayo numeric(26,2) NOT NULL, -- Mes de mayo
  junio numeric(26,2) NOT NULL, -- Mes de junio
  julio numeric(26,2) NOT NULL, -- Mes de julio
  agosto numeric(26,2) NOT NULL, -- Mes de agosto
  septiembre numeric(26,2) NOT NULL, -- Mes de septiembre
  octubre numeric(26,2) NOT NULL, -- Mes de octubre
  noviembre numeric(26,2) NOT NULL, -- Mes de noviembre
  diciembre numeric(26,2) NOT NULL, -- Mes de diciembre
  CONSTRAINT shd002_cobranza_pendiente_pkey PRIMARY KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, rif_ci, ano)
)
WITH (OIDS=FALSE);
ALTER TABLE shd002_cobranza_pendiente OWNER TO sisap;
COMMENT ON TABLE shd002_cobranza_pendiente IS 'Registra la cobranza realizada por el cobrador';
COMMENT ON COLUMN shd002_cobranza_pendiente.cod_presi IS 'Código de la presidencia';
COMMENT ON COLUMN shd002_cobranza_pendiente.cod_entidad IS 'Código de la entidad federal';
COMMENT ON COLUMN shd002_cobranza_pendiente.cod_tipo_inst IS 'Códito tipo de Institución';
COMMENT ON COLUMN shd002_cobranza_pendiente.cod_inst IS 'Código de la Institución';
COMMENT ON COLUMN shd002_cobranza_pendiente.cod_dep IS 'Código de la dependencia';
COMMENT ON COLUMN shd002_cobranza_pendiente.rif_ci IS 'Rif o Cédula de identidad del cobrador';
COMMENT ON COLUMN shd002_cobranza_pendiente.ano IS 'Año actual';
COMMENT ON COLUMN shd002_cobranza_pendiente.cobranza_pendiente_acumulada IS 'Cobranza pendiente acumulada en años anteriores';
COMMENT ON COLUMN shd002_cobranza_pendiente.enero IS 'Mes de enero';
COMMENT ON COLUMN shd002_cobranza_pendiente.febrero IS 'Mes de febrero';
COMMENT ON COLUMN shd002_cobranza_pendiente.marzo IS 'Mes de marzo';
COMMENT ON COLUMN shd002_cobranza_pendiente.abril IS 'Mes de abril';
COMMENT ON COLUMN shd002_cobranza_pendiente.mayo IS 'Mes de mayo';
COMMENT ON COLUMN shd002_cobranza_pendiente.junio IS 'Mes de junio';
COMMENT ON COLUMN shd002_cobranza_pendiente.julio IS 'Mes de julio';
COMMENT ON COLUMN shd002_cobranza_pendiente.agosto IS 'Mes de agosto';
COMMENT ON COLUMN shd002_cobranza_pendiente.septiembre IS 'Mes de septiembre';
COMMENT ON COLUMN shd002_cobranza_pendiente.octubre IS 'Mes de octubre';
COMMENT ON COLUMN shd002_cobranza_pendiente.noviembre IS 'Mes de noviembre';
COMMENT ON COLUMN shd002_cobranza_pendiente.diciembre IS 'Mes de diciembre';