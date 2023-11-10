
--ALTER TABLE shd100_patente ADD COLUMN numero_declaracion integer;

--ALTER TABLE shd100_patente RENAME numero_declaracion  TO ultimo_numero_declaracion;
--COMMENT ON COLUMN shd100_patente.ultimo_numero_declaracion IS 'Último Número de Declaración
--';


-- Table: shd100_declaracion_numero

--DROP TABLE shd100_declaracion_numero;

CREATE TABLE shd100_declaracion_numero
(
  cod_presi integer NOT NULL, -- Código de la presidencia
  cod_entidad integer NOT NULL, -- Código de la entidad federal
  cod_tipo_inst integer NOT NULL, -- Código tipo de Institución
  cod_inst integer NOT NULL, -- Código de la Institución
  cod_dep integer NOT NULL, -- Código de la Dependencia
  ano_declaracion integer NOT NULL, -- Año de la Declaración
  numero_declaracion integer NOT NULL, -- Número de la declaración
  situacion integer NOT NULL, -- Situación...
  CONSTRAINT shd100_declaracion_numero_pkey PRIMARY KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, ano_declaracion,numero_declaracion)
)
WITH (OIDS=FALSE);
ALTER TABLE shd100_declaracion_numero OWNER TO sisap;
COMMENT ON TABLE shd100_declaracion_numero IS 'Control de Números de Declaración de Ingresos brutos';
COMMENT ON COLUMN shd100_declaracion_numero.cod_presi IS 'Código de la presidencia';
COMMENT ON COLUMN shd100_declaracion_numero.cod_entidad IS 'Código de la entidad federal';
COMMENT ON COLUMN shd100_declaracion_numero.cod_tipo_inst IS 'Código tipo de Institución';
COMMENT ON COLUMN shd100_declaracion_numero.cod_inst IS 'Código de la Institución';
COMMENT ON COLUMN shd100_declaracion_numero.cod_dep IS 'Código de la Dependencia';
COMMENT ON COLUMN shd100_declaracion_numero.ano_declaracion IS 'Año de la Declaración';
COMMENT ON COLUMN shd100_declaracion_numero.numero_declaracion IS 'Número de la declaración';
COMMENT ON COLUMN shd100_declaracion_numero.situacion IS 'Situación
1.- Sin utilizar
2.- Seleccionado
3.- Emitido
4.- Anulado
5.- Congelado
';

 --DROP VIEW v_declaracion_ingreso_bruto_con_contribuyente;
 DROP TABLE shd100_declaracion_ingresos cascade;

CREATE TABLE shd100_declaracion_ingresos
(
  cod_presi integer NOT NULL, -- Código de la presidencia
  cod_entidad integer NOT NULL, -- Código de la entidad
  cod_tipo_inst integer NOT NULL, -- Código tipo de Institución
  cod_inst integer NOT NULL, -- Código de la Institución
  cod_dep integer NOT NULL, -- Código de la dependencia
  rif_cedula character varying(20) NOT NULL, -- Rif o Cédula de Idendad del Contribuyente
  ano_declaracion integer NOT NULL, -- Año de Declaración
  numero_declaracion integer NOT NULL, -- Número de Declaración
  periodo_desde date NOT NULL, -- Periodo de Declaración desde
  periodo_hasta date NOT NULL, -- Periodo de Declaración hasta
  capital numeric(26,2), -- Capital
  numero_empleados integer NOT NULL, -- Número de Empleados
  numero_obreros integer NOT NULL, -- Número de Obreros
  fecha_declaracion date NOT NULL, -- Fecha de Declaración de ingresos
  ingresos_declarados numeric(26,2) NOT NULL, -- Monto de los Ingresos declarados
  monto_impuesto numeric(26,2) NOT NULL, -- Monto del impuesto que le corresponde de acuerdo a esta declaración
  capital_anterior numeric(26,2) NOT NULL, -- Capital declarado anterior
  numero_empleados_anterior integer NOT NULL, -- Número de empleados declarado anteriormente
  numero_obreros_anterior integer NOT NULL, -- Número de obreros declarados anteriormente
  monto_impuesto_anterior numeric(26,2) NOT NULL, -- Monto del impuesto que le correspondio cancelar por la declaración anterior
  monto_cancelado_anterior numeric(26,2) NOT NULL, -- Monto de los impuestos cancelados año anterior
  monto_por_cancelar_anterior numeric(26,2) NOT NULL, -- Monto por cancelar de los impuestos que le correspondia cancelar anteriormente
  aumento_monto_impuesto numeric(26,2) NOT NULL, -- Aumento Monto del impuesto, representa la diferencia positiva comparando el Monto del impuesto de esta declaración y el Monto de Impuesto anterior
  disminucion_monto_impuesto numeric(26,2) NOT NULL, -- Disminución Monto del impuesto, representa la diferencia negativa comparando el Monto del impuesto de esta declaración y el Monto de Impuesto anterior
  CONSTRAINT shd100_declaracion_ingresos_pkey PRIMARY KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, rif_cedula, ano_declaracion, numero_declaracion),
  CONSTRAINT shd100_declaracion_ingresos_1 FOREIGN KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, rif_cedula)
      REFERENCES shd100_patente (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, rif_cedula) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE CASCADE
)
WITH (OIDS=FALSE);
ALTER TABLE shd100_declaracion_ingresos OWNER TO sisap;
COMMENT ON TABLE shd100_declaracion_ingresos IS 'Registro de la declaración de ingresos brutos de los contribuyentes';
COMMENT ON COLUMN shd100_declaracion_ingresos.cod_presi IS 'Código de la presidencia';
COMMENT ON COLUMN shd100_declaracion_ingresos.cod_entidad IS 'Código de la entidad';
COMMENT ON COLUMN shd100_declaracion_ingresos.cod_tipo_inst IS 'Código tipo de Institución';
COMMENT ON COLUMN shd100_declaracion_ingresos.cod_inst IS 'Código de la Institución';
COMMENT ON COLUMN shd100_declaracion_ingresos.cod_dep IS 'Código de la dependencia';
COMMENT ON COLUMN shd100_declaracion_ingresos.rif_cedula IS 'Rif o Cédula de Idendad del Contribuyente
';
COMMENT ON COLUMN shd100_declaracion_ingresos.ano_declaracion IS 'Año de Declaración';
COMMENT ON COLUMN shd100_declaracion_ingresos.numero_declaracion IS 'Número de Declaración ';
COMMENT ON COLUMN shd100_declaracion_ingresos.periodo_desde IS 'Periodo de Declaración desde';
COMMENT ON COLUMN shd100_declaracion_ingresos.periodo_hasta IS 'Periodo de Declaración hasta';
COMMENT ON COLUMN shd100_declaracion_ingresos.capital IS 'Capital';
COMMENT ON COLUMN shd100_declaracion_ingresos.numero_empleados IS 'Número de Empleados';
COMMENT ON COLUMN shd100_declaracion_ingresos.numero_obreros IS 'Número de Obreros';
COMMENT ON COLUMN shd100_declaracion_ingresos.fecha_declaracion IS 'Fecha de Declaración de ingresos';
COMMENT ON COLUMN shd100_declaracion_ingresos.ingresos_declarados IS 'Monto de los Ingresos declarados';
COMMENT ON COLUMN shd100_declaracion_ingresos.monto_impuesto IS 'Monto del impuesto que le corresponde de acuerdo a esta declaración';
COMMENT ON COLUMN shd100_declaracion_ingresos.capital_anterior IS 'Capital declarado anterior';
COMMENT ON COLUMN shd100_declaracion_ingresos.numero_empleados_anterior IS 'Número de empleados declarado anteriormente';
COMMENT ON COLUMN shd100_declaracion_ingresos.numero_obreros_anterior IS 'Número de obreros declarados anteriormente';
COMMENT ON COLUMN shd100_declaracion_ingresos.monto_impuesto_anterior IS 'Monto del impuesto que le correspondio cancelar por la declaración anterior';
COMMENT ON COLUMN shd100_declaracion_ingresos.monto_cancelado_anterior IS 'Monto de los impuestos cancelados año anterior';
COMMENT ON COLUMN shd100_declaracion_ingresos.monto_por_cancelar_anterior IS 'Monto por cancelar de los impuestos que le correspondia cancelar anteriormente';
COMMENT ON COLUMN shd100_declaracion_ingresos.aumento_monto_impuesto IS 'Aumento Monto del impuesto, representa la diferencia positiva comparando el Monto del impuesto de esta declaración y el Monto de Impuesto anterior
';
COMMENT ON COLUMN shd100_declaracion_ingresos.disminucion_monto_impuesto IS 'Disminución Monto del impuesto, representa la diferencia negativa comparando el Monto del impuesto de esta declaración y el Monto de Impuesto anterior
';

ALTER TABLE shd100_declaracion_ingresos ADD COLUMN cancelado integer DEFAULT 2;

COMMENT ON COLUMN shd100_declaracion_ingresos.cancelado IS 'Cancelado?
1.- Si
2.- No
';

-- Table: shd100_declaracion_actividades

DROP TABLE shd100_declaracion_actividades cascade;

CREATE TABLE shd100_declaracion_actividades
(
  cod_presi integer NOT NULL, -- Código de la presidencia
  cod_entidad integer NOT NULL, -- Código de la entidad
  cod_tipo_inst integer NOT NULL, -- Código tipo de Institución
  cod_inst integer NOT NULL, -- Código de la Institución
  cod_dep integer NOT NULL, -- Código de la depedencia
  rif_cedula character varying(20) NOT NULL,
  ano_declaracion integer NOT NULL,
  numero_declaracion integer NOT NULL, -- Número de declaración
  cod_actividad character varying(20) NOT NULL, -- Código de actividad economica declarada por el contribuyente
  monto_ingresos numeric(26,2) NOT NULL, -- Monto de ingreso declarado por el contribuyente
  monto_impuesto numeric(26,2) NOT NULL, -- Monto del impuesto calculado según los ingresos declarados por el contribuyente
  alicuota_aplicada numeric(3,2), -- Alicuota aplicada
  CONSTRAINT shd100_declaracion_actividades_pkey PRIMARY KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, rif_cedula, ano_declaracion, numero_declaracion, cod_actividad),
  CONSTRAINT shd100_declaracion_actividades_1 FOREIGN KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, rif_cedula, ano_declaracion, numero_declaracion)
      REFERENCES shd100_declaracion_ingresos (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, rif_cedula, ano_declaracion, numero_declaracion) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE CASCADE
)
WITH (OIDS=FALSE);
ALTER TABLE shd100_declaracion_actividades OWNER TO sisap;
COMMENT ON TABLE shd100_declaracion_actividades IS 'Registra las actividades economicas declaradas por el contribuyente';
COMMENT ON COLUMN shd100_declaracion_actividades.cod_presi IS 'Código de la presidencia';
COMMENT ON COLUMN shd100_declaracion_actividades.cod_entidad IS 'Código de la entidad';
COMMENT ON COLUMN shd100_declaracion_actividades.cod_tipo_inst IS 'Código tipo de Institución';
COMMENT ON COLUMN shd100_declaracion_actividades.cod_inst IS 'Código de la Institución';
COMMENT ON COLUMN shd100_declaracion_actividades.cod_dep IS 'Código de la depedencia';
COMMENT ON COLUMN shd100_declaracion_actividades.numero_declaracion IS 'Número de declaración';
COMMENT ON COLUMN shd100_declaracion_actividades.cod_actividad IS 'Código de actividad economica declarada por el contribuyente';
COMMENT ON COLUMN shd100_declaracion_actividades.monto_ingresos IS 'Monto de ingreso declarado por el contribuyente';
COMMENT ON COLUMN shd100_declaracion_actividades.monto_impuesto IS 'Monto del impuesto calculado según los ingresos declarados por el contribuyente';
COMMENT ON COLUMN shd100_declaracion_actividades.alicuota_aplicada IS 'Alicuota aplicada';

CREATE OR REPLACE VIEW v_shd100_declaracion_actividades AS
 SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.rif_cedula,ano_declaracion
         , a.numero_declaracion, a.cod_actividad, ( SELECT b.denominacion_actividad
           FROM shd100_actividades b
          WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.cod_actividad::text = b.cod_actividad::text) AS deno_actividad, a.monto_ingresos, a.monto_impuesto,a.alicuota_aplicada
   FROM shd100_declaracion_actividades a;

ALTER TABLE v_shd100_declaracion_actividades OWNER TO sisap;

CREATE OR REPLACE VIEW v_shd100_declaracion_ingresos AS
 SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.rif_cedula,a.ano_declaracion, a.numero_declaracion, a.periodo_desde, a.periodo_hasta, a.capital, a.numero_empleados, a.numero_obreros, a.fecha_declaracion , ( SELECT b.fecha_solicitud
           FROM shd100_solicitud b
          WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_dep = b.cod_dep AND a.rif_cedula::text = b.rif_cedula::text) AS fecha_solicitud, ( SELECT b.razon_social_nombres
           FROM v_shd001_registro_contribuyentes b
          WHERE a.rif_cedula::text = b.rif_cedula::text) AS nombre_razon, ( SELECT b.cod_pais
           FROM v_shd001_registro_contribuyentes b
          WHERE b.rif_cedula::text = a.rif_cedula::text) AS cod_pais, ( SELECT b.deno_pais
           FROM v_shd001_registro_contribuyentes b
          WHERE b.rif_cedula::text = a.rif_cedula::text) AS deno_pais, ( SELECT b.cod_estado
           FROM v_shd001_registro_contribuyentes b
          WHERE a.rif_cedula::text = b.rif_cedula::text) AS cod_estado, ( SELECT b.deno_estado
           FROM v_shd001_registro_contribuyentes b
          WHERE a.rif_cedula::text = b.rif_cedula::text) AS deno_estado, ( SELECT b.cod_municipio
           FROM v_shd001_registro_contribuyentes b
          WHERE a.rif_cedula::text = b.rif_cedula::text) AS cod_municipio, ( SELECT b.deno_municipio
           FROM v_shd001_registro_contribuyentes b
          WHERE a.rif_cedula::text = b.rif_cedula::text) AS deno_municipio, ( SELECT b.cod_parroquia
           FROM v_shd001_registro_contribuyentes b
          WHERE a.rif_cedula::text = b.rif_cedula::text) AS cod_parroquia, ( SELECT b.deno_parroquia
           FROM v_shd001_registro_contribuyentes b
          WHERE a.rif_cedula::text = b.rif_cedula::text) AS deno_parroquia, ( SELECT b.cod_centro_poblado
           FROM v_shd001_registro_contribuyentes b
          WHERE a.rif_cedula::text = b.rif_cedula::text) AS cod_centro, ( SELECT b.deno_centro
           FROM v_shd001_registro_contribuyentes b
          WHERE a.rif_cedula::text = b.rif_cedula::text) AS deno_centro, ( SELECT b.cod_calle_avenida
           FROM v_shd001_registro_contribuyentes b
          WHERE a.rif_cedula::text = b.rif_cedula::text) AS cod_calle, ( SELECT b.deno_vialidad
           FROM v_shd001_registro_contribuyentes b
          WHERE a.rif_cedula::text = b.rif_cedula::text) AS deno_calle, ( SELECT b.cod_vereda_edificio
           FROM v_shd001_registro_contribuyentes b
          WHERE a.rif_cedula::text = b.rif_cedula::text) AS cod_vereda_edificio, ( SELECT b.deno_vereda
           FROM v_shd001_registro_contribuyentes b
          WHERE a.rif_cedula::text = b.rif_cedula::text) AS deno_vereda, ( SELECT b.numero_vivienda_local
           FROM v_shd001_registro_contribuyentes b
          WHERE a.rif_cedula::text = b.rif_cedula::text) AS numero_casa, ( SELECT b.fecha_inscripcion
           FROM v_shd001_registro_contribuyentes b
          WHERE a.rif_cedula::text = b.rif_cedula::text) AS fecha_inscripcion_cont, ( SELECT b.telefonos_fijos
           FROM v_shd001_registro_contribuyentes b
          WHERE a.rif_cedula::text = b.rif_cedula::text) AS telefonos_fijos, ( SELECT b.telefonos_celulares
           FROM v_shd001_registro_contribuyentes b
          WHERE a.rif_cedula::text = b.rif_cedula::text) AS telefonos_celulares, ( SELECT b.correo_electronico
           FROM v_shd001_registro_contribuyentes b
          WHERE a.rif_cedula::text = b.rif_cedula::text) AS correo_electronico, ( SELECT b.nacionalidad
           FROM v_shd001_registro_contribuyentes b
          WHERE a.rif_cedula::text = b.rif_cedula::text) AS nacionalidad, ( SELECT b.estado_civil
           FROM v_shd001_registro_contribuyentes b
          WHERE a.rif_cedula::text = b.rif_cedula::text) AS estado_civil, ( SELECT b.deno_profesion
           FROM v_shd001_registro_contribuyentes b
          WHERE a.rif_cedula::text = b.rif_cedula::text) AS deno_profesion, ( SELECT b.numero_solicitud
           FROM shd100_patente b
          WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_dep = b.cod_dep AND a.rif_cedula::text = b.rif_cedula::text) AS numero_solicitud, ( SELECT b.numero_patente
           FROM shd100_patente b
          WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_dep = b.cod_dep AND a.rif_cedula::text = b.rif_cedula::text) AS numero_patente, ( SELECT b.fecha_patente
           FROM shd100_patente b
          WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_dep = b.cod_dep AND a.rif_cedula::text = b.rif_cedula::text) AS fecha_patente, ( SELECT b.frecuencia_pago
           FROM shd100_patente b
          WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_dep = b.cod_dep AND a.rif_cedula::text = b.rif_cedula::text) AS frecuencia_pago, ( SELECT b.fecha_inicio_const
           FROM shd100_solicitud b
          WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_dep = b.cod_dep AND a.rif_cedula::text = b.rif_cedula::text) AS fecha_inicio_const, ( SELECT b.fecha_cierre_const
           FROM shd100_solicitud b
          WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_dep = b.cod_dep AND a.rif_cedula::text = b.rif_cedula::text) AS fecha_cierre_const, ( SELECT b.fecha_inicio_econo
           FROM shd100_solicitud b
          WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_dep = b.cod_dep AND a.rif_cedula::text = b.rif_cedula::text) AS fecha_inicio_econo, ( SELECT b.fecha_cierre_economico
           FROM shd100_solicitud b
          WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_dep = b.cod_dep AND a.rif_cedula::text = b.rif_cedula::text) AS fecha_cierre_economico, ( SELECT b.registro_mercantil
           FROM shd100_solicitud b
          WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_dep = b.cod_dep AND a.rif_cedula::text = b.rif_cedula::text) AS registro_mercantil
   FROM shd100_declaracion_ingresos a;

ALTER TABLE v_shd100_declaracion_ingresos OWNER TO sisap;


-- Table: shd100_declaracion_ingresos_facturado

-- DROP TABLE shd100_declaracion_ingresos_facturado;

CREATE TABLE shd100_declaracion_ingresos_facturado
(
  cod_presi integer NOT NULL, -- Código de la presidencia
  cod_entidad integer NOT NULL, -- Código de la entidad
  cod_tipo_inst integer NOT NULL, -- Código tipo de Institución
  cod_inst integer NOT NULL, -- Código de la Institución
  cod_dep integer NOT NULL, -- Código de la dependencia
  rif_cedula character varying(20) NOT NULL, -- Rif o Cédula de Idendad del Contribuyente
  ano_comprobante integer NOT NULL, -- Año de Comprobante
  numero_comprobante integer NOT NULL, -- Número de Comprobante
  ano_declaracion integer NOT NULL, -- Año de Declaración
  numero_declaracion integer NOT NULL, -- Número de Declaración
  CONSTRAINT shd100_declaracion_ingresos_facturado_pkey PRIMARY KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, rif_cedula, ano_comprobante, numero_comprobante, ano_declaracion, numero_declaracion)
)
WITH (OIDS=FALSE);
ALTER TABLE shd100_declaracion_ingresos_facturado OWNER TO sisap;
COMMENT ON TABLE shd100_declaracion_ingresos_facturado IS 'Registro de la declaración de ingresos brutos de los contribuyentes';
COMMENT ON COLUMN shd100_declaracion_ingresos_facturado.cod_presi IS 'Código de la presidencia';
COMMENT ON COLUMN shd100_declaracion_ingresos_facturado.cod_entidad IS 'Código de la entidad';
COMMENT ON COLUMN shd100_declaracion_ingresos_facturado.cod_tipo_inst IS 'Código tipo de Institución';
COMMENT ON COLUMN shd100_declaracion_ingresos_facturado.cod_inst IS 'Código de la Institución';
COMMENT ON COLUMN shd100_declaracion_ingresos_facturado.cod_dep IS 'Código de la dependencia';
COMMENT ON COLUMN shd100_declaracion_ingresos_facturado.rif_cedula IS 'Rif o Cédula de Idendad del Contribuyente';
COMMENT ON COLUMN shd100_declaracion_ingresos_facturado.ano_comprobante IS 'Año de Comprobante';
COMMENT ON COLUMN shd100_declaracion_ingresos_facturado.numero_comprobante IS 'Número de Comprobante';
COMMENT ON COLUMN shd100_declaracion_ingresos_facturado.ano_declaracion IS 'Año de Declaración';
COMMENT ON COLUMN shd100_declaracion_ingresos_facturado.numero_declaracion IS 'Número de Declaración';




ALTER TABLE shd100_declaracion_ingresos ADD COLUMN monto_exonerado numeric(26,2);
ALTER TABLE shd100_declaracion_ingresos ADD COLUMN observacion_exoneracion text;


COMMENT ON COLUMN shd100_declaracion_ingresos.monto_exonerado IS 'Monto exonerado';
COMMENT ON COLUMN shd100_declaracion_ingresos.observacion_exoneracion IS 'Observaciones de la exoneración';

ALTER TABLE shd100_declaracion_ingresos ADD COLUMN condicion_actividad integer;
ALTER TABLE shd100_declaracion_ingresos ADD COLUMN fecha_registro date;
ALTER TABLE shd100_declaracion_ingresos ADD COLUMN username_registro character varying(60);
ALTER TABLE shd100_declaracion_ingresos ADD COLUMN fecha_anulacion date;
ALTER TABLE shd100_declaracion_ingresos ADD COLUMN username_anulacion character varying(60);



COMMENT ON COLUMN shd100_declaracion_ingresos.condicion_actividad IS 'Condición de actividad
1.- Activo
2.- Anulada
';
COMMENT ON COLUMN shd100_declaracion_ingresos.fecha_registro IS 'Fecha de registro';
COMMENT ON COLUMN shd100_declaracion_ingresos.username_registro IS 'Operador que registro la declaración';
COMMENT ON COLUMN shd100_declaracion_ingresos.fecha_anulacion IS 'Fecha de anulación';
COMMENT ON COLUMN shd100_declaracion_ingresos.username_anulacion IS 'Operador que registro la anulación';





--DROP VIEW v_declaracion_ingreso_bruto_con_contribuyente;

CREATE OR REPLACE VIEW v_declaracion_ingreso_bruto_con_contribuyente AS


SELECT

  a.cod_presi,
  a.cod_entidad,
  a.cod_tipo_inst,
  a.cod_inst,
  a.cod_dep,
  a.rif_cedula,
  a.ano_declaracion,
  a.numero_declaracion,
  (SUBSTR(a.fecha_declaracion::text, 0, 5)) as ano,
  a.periodo_desde,
  a.periodo_hasta,
  a.capital,
  a.numero_empleados,
  a.numero_obreros,
  a.fecha_declaracion,
  a.ingresos_declarados,
  (a.monto_impuesto) as monto_impuesto_declaracion_ingreso,
  a.capital_anterior,
  a.numero_empleados_anterior,
  a.numero_obreros_anterior,
  a.monto_impuesto_anterior,
  a.monto_cancelado_anterior,
  a.monto_por_cancelar_anterior,
  a.aumento_monto_impuesto,
  a.disminucion_monto_impuesto,
  a.cancelado,
  a.monto_exonerado,
  a.observacion_exoneracion,
  a.condicion_actividad,
  a.fecha_registro,
  a.username_registro,
  a.fecha_anulacion,
  a.username_anulacion,
  b.cod_actividad,
  b.monto_ingresos,
  b.monto_impuesto,
  b.alicuota_aplicada,
  c.personalidad_juridica,
  c.razon_social_nombres,
  c.fecha_inscripcion,
  c.nacionalidad,
  c.estado_civil,
  c.profesion,
  c.cod_pais,
  c.cod_estado,
  c.cod_municipio,
  c.cod_parroquia,
  c.cod_centro_poblado,
  c.cod_calle_avenida,
  c.cod_vereda_edificio,
  c.numero_vivienda_local,
  c.telefonos_fijos,
  c.telefonos_celulares,
  c.correo_electronico,
  (SELECT xya.denominacion FROM cugd01_estados          xya where xya.cod_republica=c.cod_pais and xya.cod_estado=c.cod_estado                                                                                                                           											   												 GROUP BY xya.denominacion) as  deno_cod_estado,
  (SELECT xyb.denominacion FROM cugd01_municipios       xyb where xyb.cod_republica=c.cod_pais and xyb.cod_estado=c.cod_estado  and xyb.cod_municipio=c.cod_municipio                                                                                   										       											     GROUP BY xyb.denominacion) as  deno_cod_municipio,
  (SELECT xyb.conocido     FROM cugd01_municipios       xyb where xyb.cod_republica=c.cod_pais and xyb.cod_estado=c.cod_estado  and xyb.cod_municipio=c.cod_municipio                                                                                   										       											     GROUP BY xyb.conocido) as  conocido,
  (SELECT xyc.denominacion FROM cugd01_parroquias       xyc where xyc.cod_republica=c.cod_pais and xyc.cod_estado=c.cod_estado  and xyc.cod_municipio=c.cod_municipio and xyc.cod_parroquia = c.cod_parroquia                                                                                        											     GROUP BY xyc.denominacion) as  deno_cod_parroquia,
  (SELECT xyd.denominacion FROM cugd01_centros_poblados xyd where xyd.cod_republica=c.cod_pais and xyd.cod_estado=c.cod_estado  and xyd.cod_municipio=c.cod_municipio and xyd.cod_parroquia = c.cod_parroquia and xyd.cod_centro = c.cod_centro_poblado                                               								                 GROUP BY xyd.denominacion) as  deno_cod_centro,
  (SELECT xyd.denominacion FROM cugd01_vialidad         xyd where xyd.cod_republica=c.cod_pais and xyd.cod_estado=c.cod_estado  and xyd.cod_municipio=c.cod_municipio and xyd.cod_parroquia = c.cod_parroquia and xyd.cod_centro = c.cod_centro_poblado and  xyd.cod_vialidad  = c.cod_calle_avenida   											 GROUP BY xyd.denominacion) as  deno_cod_calle_avd,
  (SELECT xyd.denominacion FROM cugd01_vereda           xyd where xyd.cod_republica=c.cod_pais and xyd.cod_estado=c.cod_estado  and xyd.cod_municipio=c.cod_municipio and xyd.cod_parroquia = c.cod_parroquia and xyd.cod_centro = c.cod_centro_poblado and  xyd.cod_vialidad  = c.cod_calle_avenida  and  xyd.cod_vereda  = c.cod_vereda_edificio  GROUP BY xyd.denominacion) as  deno_cod_verenda,
   d.denominacion_actividad,
   d.alicuota,
   d.unidades_tributarias,
   d.minimo_tributable




FROM shd100_declaracion_ingresos a, shd100_declaracion_actividades b, shd001_registro_contribuyentes c, shd100_actividades d


WHERE b.cod_presi          = a.cod_presi          and
	  b.cod_entidad        = a.cod_entidad        and
	  b.cod_tipo_inst      = a.cod_tipo_inst      and
	  b.cod_inst           = a.cod_inst           and
	  b.cod_dep            = a.cod_dep            and
	  b.rif_cedula         = a.rif_cedula         and
	  b.ano_declaracion    = a.ano_declaracion and
	  b.numero_declaracion = a.numero_declaracion and
	  c.rif_cedula         =  a.rif_cedula        and
	  d.cod_presi          =  b.cod_presi      and
      d.cod_entidad        =  b.cod_entidad    and
      d.cod_tipo_inst      =  b.cod_tipo_inst  and
      d.cod_inst           =  b.cod_inst       and
      d.cod_dep            =  b.cod_dep        and
      d.cod_actividad      =  b.cod_actividad;

ALTER TABLE v_declaracion_ingreso_bruto_con_contribuyente OWNER TO sisap;



CREATE OR REPLACE VIEW v_shd100_ingresos_rif AS
 SELECT shd100_declaracion_ingresos.cod_presi, shd100_declaracion_ingresos.cod_entidad, shd100_declaracion_ingresos.cod_tipo_inst, shd100_declaracion_ingresos.cod_inst, shd100_declaracion_ingresos.cod_dep, shd100_declaracion_ingresos.rif_cedula
   FROM shd100_declaracion_ingresos
  GROUP BY shd100_declaracion_ingresos.cod_presi, shd100_declaracion_ingresos.cod_entidad, shd100_declaracion_ingresos.cod_tipo_inst, shd100_declaracion_ingresos.cod_inst, shd100_declaracion_ingresos.cod_dep, shd100_declaracion_ingresos.rif_cedula
  ORDER BY shd100_declaracion_ingresos.cod_presi, shd100_declaracion_ingresos.cod_entidad, shd100_declaracion_ingresos.cod_tipo_inst, shd100_declaracion_ingresos.cod_inst, shd100_declaracion_ingresos.cod_dep, shd100_declaracion_ingresos.rif_cedula;

ALTER TABLE v_shd100_ingresos_rif OWNER TO sisap;









ALTER TABLE shd100_declaracion_ingresos ADD COLUMN monto_intereses numeric(26,2);
ALTER TABLE shd100_declaracion_ingresos ADD COLUMN acumulado_pagos_parciales numeric(26,2);
COMMENT ON COLUMN shd100_declaracion_ingresos.monto_intereses IS 'Monto de intereses generados en esta declaración';
COMMENT ON COLUMN shd100_declaracion_ingresos.acumulado_pagos_parciales IS 'Monto acumulado de pagos parciales por convenimiento de pago';

-- Table: shd100_declaracion_ingresos_convenimientos

-- DROP TABLE shd100_declaracion_ingresos_convenimientos;

CREATE TABLE shd100_declaracion_ingresos_convenimientos
(
  cod_presi integer NOT NULL, -- Código de la presidencia
  cod_entidad integer NOT NULL, -- Código de la entidad federal
  cod_tipo_inst integer NOT NULL, -- Código tipo de Institución
  cod_inst integer NOT NULL, -- Código de la Institución
  cod_dep integer NOT NULL, -- Código de la Dependencia
  rif_cedula character varying(20) NOT NULL, -- Rif o Cédula del contribuyente
  ano_declaracion integer NOT NULL, -- Año de la declaración de ingresos brutos
  numero_declaracion integer NOT NULL, -- Número de declaración de ingresos brutos
  ano_convenimiento integer NOT NULL, -- Año de convenimiento
  numero_convenimiento integer NOT NULL, -- Número de convenimiento
  monto_deuda numeric(26,2) NOT NULL, -- Monto de la deuda de este convenimiento
  fecha_acordada_pago date NOT NULL, -- Fecha acordada para el pago
  monto_convenido numeric(26,2) NOT NULL, -- Monto convenido a pagar
  deuda_pendiente numeric(26,2) NOT NULL, -- Deuda pendiente después de cancelar este convenimiento
  fecha_cancelacion date, -- Fecha cuando se cancelo el convenimiento
  cancelado integer DEFAULT 2, -- Cancelado?...
  CONSTRAINT shd100_declaracion_ingresos_convenimientos_pkey PRIMARY KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, rif_cedula, ano_declaracion, numero_declaracion, ano_convenimiento, numero_convenimiento),
  CONSTRAINT shd100_declaracion_ingresos_convenimiento_1 FOREIGN KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, rif_cedula, ano_declaracion, numero_declaracion)
      REFERENCES shd100_declaracion_ingresos (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, rif_cedula, ano_declaracion, numero_declaracion) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE CASCADE
)
WITH (OIDS=FALSE);
ALTER TABLE shd100_declaracion_ingresos_convenimientos OWNER TO sisap;
COMMENT ON TABLE shd100_declaracion_ingresos_convenimientos IS 'Registra los convenimientos de pago';
COMMENT ON COLUMN shd100_declaracion_ingresos_convenimientos.cod_presi IS 'Código de la presidencia';
COMMENT ON COLUMN shd100_declaracion_ingresos_convenimientos.cod_entidad IS 'Código de la entidad federal';
COMMENT ON COLUMN shd100_declaracion_ingresos_convenimientos.cod_tipo_inst IS 'Código tipo de Institución';
COMMENT ON COLUMN shd100_declaracion_ingresos_convenimientos.cod_inst IS 'Código de la Institución';
COMMENT ON COLUMN shd100_declaracion_ingresos_convenimientos.cod_dep IS 'Código de la Dependencia';
COMMENT ON COLUMN shd100_declaracion_ingresos_convenimientos.rif_cedula IS 'Rif o Cédula del contribuyente';
COMMENT ON COLUMN shd100_declaracion_ingresos_convenimientos.ano_declaracion IS 'Año de la declaración de ingresos brutos';
COMMENT ON COLUMN shd100_declaracion_ingresos_convenimientos.numero_declaracion IS 'Número de declaración de ingresos brutos';
COMMENT ON COLUMN shd100_declaracion_ingresos_convenimientos.ano_convenimiento IS 'Año de convenimiento';
COMMENT ON COLUMN shd100_declaracion_ingresos_convenimientos.numero_convenimiento IS 'Número de convenimiento';
COMMENT ON COLUMN shd100_declaracion_ingresos_convenimientos.monto_deuda IS 'Monto de la deuda de este convenimiento';
COMMENT ON COLUMN shd100_declaracion_ingresos_convenimientos.fecha_acordada_pago IS 'Fecha acordada para el pago';
COMMENT ON COLUMN shd100_declaracion_ingresos_convenimientos.monto_convenido IS 'Monto convenido a pagar
';
COMMENT ON COLUMN shd100_declaracion_ingresos_convenimientos.deuda_pendiente IS 'Deuda pendiente después de cancelar este convenimiento';
COMMENT ON COLUMN shd100_declaracion_ingresos_convenimientos.fecha_cancelacion IS 'Fecha cuando se cancelo el convenimiento';
COMMENT ON COLUMN shd100_declaracion_ingresos_convenimientos.cancelado IS 'Cancelado?
1.- SI
2.- NO';



