--
-- PostgreSQL database dump
--

-- Started on 2009-09-22 08:25:04 VET

SET client_encoding = 'UTF8';
SET standard_conforming_strings = off;
SET check_function_bodies = false;
SET client_min_messages = warning;
SET escape_string_warning = off;

SET search_path = public, pg_catalog;

SET default_tablespace = '';

SET default_with_oids = false;

--
-- TOC entry 4152 (class 1259 OID 502028)
-- Dependencies: 3
-- Name: shd000_arranque; Type: TABLE; Schema: public; Owner: sisap; Tablespace:
--


CREATE OR REPLACE FUNCTION mascara_2(integer)
  RETURNS text AS
$BODY$
DECLARE
t text;
c integer;
BEGIN
c = (SELECT length($1::text));
if  c=2 then
t = '' || $1;
elsif  c=1 then
t = '0' || $1;
else
t = $1;
end if;

RETURN t;
END;
$BODY$
  LANGUAGE 'plpgsql' VOLATILE
  COST 100;
ALTER FUNCTION mascara_2(integer) OWNER TO sisap;







CREATE TABLE shd000_arranque (
    cod_presi integer NOT NULL,
    cod_entidad integer NOT NULL,
    cod_tipo_inst integer NOT NULL,
    cod_inst integer NOT NULL,
    cod_dep integer NOT NULL,
    ano_arranque integer NOT NULL,
    mes_arranque integer NOT NULL
);


ALTER TABLE public.shd000_arranque OWNER TO sisap;

--
-- TOC entry 5006 (class 0 OID 0)
-- Dependencies: 4152
-- Name: TABLE shd000_arranque; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON TABLE shd000_arranque IS 'Control de arranque del módulo de hacienda';


--
-- TOC entry 5007 (class 0 OID 0)
-- Dependencies: 4152
-- Name: COLUMN shd000_arranque.cod_presi; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd000_arranque.cod_presi IS 'Código de la presidencia';


--
-- TOC entry 5008 (class 0 OID 0)
-- Dependencies: 4152
-- Name: COLUMN shd000_arranque.cod_entidad; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd000_arranque.cod_entidad IS 'Código de la entidad federal
';


--
-- TOC entry 5009 (class 0 OID 0)
-- Dependencies: 4152
-- Name: COLUMN shd000_arranque.cod_tipo_inst; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd000_arranque.cod_tipo_inst IS 'Código tipo de Institución';


--
-- TOC entry 5010 (class 0 OID 0)
-- Dependencies: 4152
-- Name: COLUMN shd000_arranque.cod_inst; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd000_arranque.cod_inst IS 'Código de Institución';


--
-- TOC entry 5011 (class 0 OID 0)
-- Dependencies: 4152
-- Name: COLUMN shd000_arranque.cod_dep; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd000_arranque.cod_dep IS 'Código dependencia';


--
-- TOC entry 5012 (class 0 OID 0)
-- Dependencies: 4152
-- Name: COLUMN shd000_arranque.ano_arranque; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd000_arranque.ano_arranque IS 'Año de arranque';


--
-- TOC entry 5013 (class 0 OID 0)
-- Dependencies: 4152
-- Name: COLUMN shd000_arranque.mes_arranque; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd000_arranque.mes_arranque IS 'Mes de arranque
';


--
-- TOC entry 4153 (class 1259 OID 502033)
-- Dependencies: 4847 3
-- Name: shd000_control_actualizacion; Type: TABLE; Schema: public; Owner: sisap; Tablespace:
--

CREATE TABLE shd000_control_actualizacion (
    cod_presi integer NOT NULL,
    cod_entidad integer NOT NULL,
    cod_tipo_inst integer NOT NULL,
    cod_inst integer NOT NULL,
    cod_dep integer NOT NULL,
    cod_ingreso integer NOT NULL,
    ano_actualizado integer NOT NULL,
    mes_actualizado integer NOT NULL,
    condicion integer DEFAULT 0 NOT NULL
);


ALTER TABLE public.shd000_control_actualizacion OWNER TO sisap;

--
-- TOC entry 5014 (class 0 OID 0)
-- Dependencies: 4153
-- Name: TABLE shd000_control_actualizacion; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON TABLE shd000_control_actualizacion IS 'Controla la actualización de las planillas y la emisón';


--
-- TOC entry 5015 (class 0 OID 0)
-- Dependencies: 4153
-- Name: COLUMN shd000_control_actualizacion.cod_presi; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd000_control_actualizacion.cod_presi IS 'Código de la presidencia';


--
-- TOC entry 5016 (class 0 OID 0)
-- Dependencies: 4153
-- Name: COLUMN shd000_control_actualizacion.cod_entidad; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd000_control_actualizacion.cod_entidad IS 'Código de la entidad federal';


--
-- TOC entry 5017 (class 0 OID 0)
-- Dependencies: 4153
-- Name: COLUMN shd000_control_actualizacion.cod_tipo_inst; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd000_control_actualizacion.cod_tipo_inst IS 'Código tipo de Institución';


--
-- TOC entry 5018 (class 0 OID 0)
-- Dependencies: 4153
-- Name: COLUMN shd000_control_actualizacion.cod_inst; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd000_control_actualizacion.cod_inst IS 'Código de la Institución';


--
-- TOC entry 5019 (class 0 OID 0)
-- Dependencies: 4153
-- Name: COLUMN shd000_control_actualizacion.cod_dep; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd000_control_actualizacion.cod_dep IS 'Código de la dependencia';


--
-- TOC entry 5020 (class 0 OID 0)
-- Dependencies: 4153
-- Name: COLUMN shd000_control_actualizacion.cod_ingreso; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd000_control_actualizacion.cod_ingreso IS 'Código de ingreso:
01 = Industria y comercio
02 = Vehículos
03 = Propaganda comercial
04 = Inmuebles urbanos
05 = Aseo domiciliario
06 = Arrendamiento de tierras
07 = Crédito de vivienda

';


--
-- TOC entry 5021 (class 0 OID 0)
-- Dependencies: 4153
-- Name: COLUMN shd000_control_actualizacion.ano_actualizado; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd000_control_actualizacion.ano_actualizado IS 'Año actualizado';


--
-- TOC entry 5022 (class 0 OID 0)
-- Dependencies: 4153
-- Name: COLUMN shd000_control_actualizacion.mes_actualizado; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd000_control_actualizacion.mes_actualizado IS 'Mes actualizado';


--
-- TOC entry 5023 (class 0 OID 0)
-- Dependencies: 4153
-- Name: COLUMN shd000_control_actualizacion.condicion; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd000_control_actualizacion.condicion IS 'Condición
0 = Sin actualizar
1 = Actualizado
2 = Emitido
';


--
-- TOC entry 4154 (class 1259 OID 502039)
-- Dependencies: 3
-- Name: shd000_control_numero; Type: TABLE; Schema: public; Owner: sisap; Tablespace:
--

CREATE TABLE shd000_control_numero (
    cod_presi integer NOT NULL,
    cod_entidad integer NOT NULL,
    cod_tipo_inst integer NOT NULL,
    cod_inst integer NOT NULL,
    cod_dep integer NOT NULL,
    ano integer NOT NULL,
    cod_ingreso integer NOT NULL,
    numero_planilla integer NOT NULL
);


ALTER TABLE public.shd000_control_numero OWNER TO sisap;

--
-- TOC entry 5024 (class 0 OID 0)
-- Dependencies: 4154
-- Name: TABLE shd000_control_numero; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON TABLE shd000_control_numero IS 'Control de número de las planillas';


--
-- TOC entry 5025 (class 0 OID 0)
-- Dependencies: 4154
-- Name: COLUMN shd000_control_numero.cod_presi; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd000_control_numero.cod_presi IS 'Código de la presidencia';


--
-- TOC entry 5026 (class 0 OID 0)
-- Dependencies: 4154
-- Name: COLUMN shd000_control_numero.cod_entidad; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd000_control_numero.cod_entidad IS 'Código de la entidad federal';


--
-- TOC entry 5027 (class 0 OID 0)
-- Dependencies: 4154
-- Name: COLUMN shd000_control_numero.cod_tipo_inst; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd000_control_numero.cod_tipo_inst IS 'Código tipo de Institución';


--
-- TOC entry 5028 (class 0 OID 0)
-- Dependencies: 4154
-- Name: COLUMN shd000_control_numero.cod_inst; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd000_control_numero.cod_inst IS 'Código de la Institución';


--
-- TOC entry 5029 (class 0 OID 0)
-- Dependencies: 4154
-- Name: COLUMN shd000_control_numero.cod_dep; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd000_control_numero.cod_dep IS 'Código de la dependencia';


--
-- TOC entry 5030 (class 0 OID 0)
-- Dependencies: 4154
-- Name: COLUMN shd000_control_numero.ano; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd000_control_numero.ano IS 'Año';


--
-- TOC entry 5031 (class 0 OID 0)
-- Dependencies: 4154
-- Name: COLUMN shd000_control_numero.cod_ingreso; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd000_control_numero.cod_ingreso IS 'Código de ingreso:
01 = Industria y comercio
02 = Vehículos
03 = Propaganda comercial
04 = Inmuebles urbanos
05 = Aseo domiciliario
06 = Arrendamiento de tierras
07 = Crédito de vivienda

';


--
-- TOC entry 5032 (class 0 OID 0)
-- Dependencies: 4154
-- Name: COLUMN shd000_control_numero.numero_planilla; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd000_control_numero.numero_planilla IS 'Número de planilla de liquidación previa';


--
-- TOC entry 4155 (class 1259 OID 502044)
-- Dependencies: 3
-- Name: shd000_ordenanzas; Type: TABLE; Schema: public; Owner: sisap; Tablespace:
--

CREATE TABLE shd000_ordenanzas (
    cod_presi integer NOT NULL,
    cod_entidad integer NOT NULL,
    cod_tipo_inst integer NOT NULL,
    cod_inst integer NOT NULL,
    cod_dep integer NOT NULL,
    cod_ingreso integer NOT NULL,
    porcentaje_descuento numeric(5,2),
    porcentaje_multa numeric(5,2),
    porcentaje_recargo numeric(5,2),
    porcentaje_interes numeric(5,2)
);


ALTER TABLE public.shd000_ordenanzas OWNER TO sisap;

--
-- TOC entry 5033 (class 0 OID 0)
-- Dependencies: 4155
-- Name: TABLE shd000_ordenanzas; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON TABLE shd000_ordenanzas IS 'Porcentajes de descuento o sanciones según la ordenanza';


--
-- TOC entry 5034 (class 0 OID 0)
-- Dependencies: 4155
-- Name: COLUMN shd000_ordenanzas.cod_presi; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd000_ordenanzas.cod_presi IS 'Código de la presidencia';


--
-- TOC entry 5035 (class 0 OID 0)
-- Dependencies: 4155
-- Name: COLUMN shd000_ordenanzas.cod_entidad; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd000_ordenanzas.cod_entidad IS 'Código de la entidad';


--
-- TOC entry 5036 (class 0 OID 0)
-- Dependencies: 4155
-- Name: COLUMN shd000_ordenanzas.cod_tipo_inst; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd000_ordenanzas.cod_tipo_inst IS 'Código tipo de la Institución';


--
-- TOC entry 5037 (class 0 OID 0)
-- Dependencies: 4155
-- Name: COLUMN shd000_ordenanzas.cod_inst; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd000_ordenanzas.cod_inst IS 'Código de la Institución';


--
-- TOC entry 5038 (class 0 OID 0)
-- Dependencies: 4155
-- Name: COLUMN shd000_ordenanzas.cod_dep; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd000_ordenanzas.cod_dep IS 'Código de la dependencia';


--
-- TOC entry 5039 (class 0 OID 0)
-- Dependencies: 4155
-- Name: COLUMN shd000_ordenanzas.cod_ingreso; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd000_ordenanzas.cod_ingreso IS 'Código de ingreso:
01 = Industria y comercio
02 = Vehículos
03 = Propaganda comercial
04 = Inmuebles urbanos
05 = Aseo domiciliario
06 = Arrendamiento de tierras
07 = Crédito de vivienda
';


--
-- TOC entry 5040 (class 0 OID 0)
-- Dependencies: 4155
-- Name: COLUMN shd000_ordenanzas.porcentaje_descuento; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd000_ordenanzas.porcentaje_descuento IS 'Porcentaje de descuento';


--
-- TOC entry 5041 (class 0 OID 0)
-- Dependencies: 4155
-- Name: COLUMN shd000_ordenanzas.porcentaje_multa; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd000_ordenanzas.porcentaje_multa IS 'Porcentaje por multa';


--
-- TOC entry 5042 (class 0 OID 0)
-- Dependencies: 4155
-- Name: COLUMN shd000_ordenanzas.porcentaje_recargo; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd000_ordenanzas.porcentaje_recargo IS 'Porcentaje de recargos';


--
-- TOC entry 5043 (class 0 OID 0)
-- Dependencies: 4155
-- Name: COLUMN shd000_ordenanzas.porcentaje_interes; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd000_ordenanzas.porcentaje_interes IS 'Porcentaje de interes';


--
-- TOC entry 4156 (class 1259 OID 502049)
-- Dependencies: 3
-- Name: shd001_registro_contribuyentes; Type: TABLE; Schema: public; Owner: sisap; Tablespace:
--

CREATE TABLE shd001_registro_contribuyentes (
    rif_cedula character varying(20) NOT NULL,
    personalidad_juridica integer NOT NULL,
    razon_social_nombres character varying(100) NOT NULL,
    fecha_inscripcion date NOT NULL,
    nacionalidad integer,
    estado_civil integer,
    profesion integer,
    cod_pais integer NOT NULL,
    cod_estado integer NOT NULL,
    cod_municipio integer NOT NULL,
    cod_parroquia integer NOT NULL,
    cod_centro_poblado integer NOT NULL,
    cod_calle_avenida integer NOT NULL,
    cod_vereda_edificio integer NOT NULL,
    numero_vivienda_local character varying(30) NOT NULL,
    telefonos_fijos character varying(30),
    telefonos_celulares character varying(30),
    correo_electronico character varying(30)
);


ALTER TABLE public.shd001_registro_contribuyentes OWNER TO sisap;

--
-- TOC entry 5044 (class 0 OID 0)
-- Dependencies: 4156
-- Name: TABLE shd001_registro_contribuyentes; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON TABLE shd001_registro_contribuyentes IS 'Registro general de contribuyentes';


--
-- TOC entry 5045 (class 0 OID 0)
-- Dependencies: 4156
-- Name: COLUMN shd001_registro_contribuyentes.rif_cedula; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd001_registro_contribuyentes.rif_cedula IS 'Rif o cédula de identidad';


--
-- TOC entry 5046 (class 0 OID 0)
-- Dependencies: 4156
-- Name: COLUMN shd001_registro_contribuyentes.personalidad_juridica; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd001_registro_contribuyentes.personalidad_juridica IS 'Personalidad Juridica
1.- Natural
2.- Juridica';


--
-- TOC entry 5047 (class 0 OID 0)
-- Dependencies: 4156
-- Name: COLUMN shd001_registro_contribuyentes.razon_social_nombres; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd001_registro_contribuyentes.razon_social_nombres IS 'Razón social o Nombres y Apellidos';


--
-- TOC entry 5048 (class 0 OID 0)
-- Dependencies: 4156
-- Name: COLUMN shd001_registro_contribuyentes.fecha_inscripcion; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd001_registro_contribuyentes.fecha_inscripcion IS 'Fecha de Inscripción';


--
-- TOC entry 5049 (class 0 OID 0)
-- Dependencies: 4156
-- Name: COLUMN shd001_registro_contribuyentes.nacionalidad; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd001_registro_contribuyentes.nacionalidad IS 'Nacionalidad
1.- Venezolana
2.- Extranjera';


--
-- TOC entry 5050 (class 0 OID 0)
-- Dependencies: 4156
-- Name: COLUMN shd001_registro_contribuyentes.estado_civil; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd001_registro_contribuyentes.estado_civil IS 'Estado civil
1.- Soltero
2.- Casado
3.- Divorciado
4.- Viudo
5.- Otro
';


--
-- TOC entry 5051 (class 0 OID 0)
-- Dependencies: 4156
-- Name: COLUMN shd001_registro_contribuyentes.profesion; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd001_registro_contribuyentes.profesion IS 'Codigo de la profesion (Enlace con personal)';


--
-- TOC entry 5052 (class 0 OID 0)
-- Dependencies: 4156
-- Name: COLUMN shd001_registro_contribuyentes.cod_pais; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd001_registro_contribuyentes.cod_pais IS 'Código del pais';


--
-- TOC entry 5053 (class 0 OID 0)
-- Dependencies: 4156
-- Name: COLUMN shd001_registro_contribuyentes.cod_estado; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd001_registro_contribuyentes.cod_estado IS 'Código del estado';


--
-- TOC entry 5054 (class 0 OID 0)
-- Dependencies: 4156
-- Name: COLUMN shd001_registro_contribuyentes.cod_municipio; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd001_registro_contribuyentes.cod_municipio IS 'Código del municipio';


--
-- TOC entry 5055 (class 0 OID 0)
-- Dependencies: 4156
-- Name: COLUMN shd001_registro_contribuyentes.cod_parroquia; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd001_registro_contribuyentes.cod_parroquia IS 'Código de la parroquia';


--
-- TOC entry 5056 (class 0 OID 0)
-- Dependencies: 4156
-- Name: COLUMN shd001_registro_contribuyentes.cod_centro_poblado; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd001_registro_contribuyentes.cod_centro_poblado IS 'Código del centro poblado
Urbanizaciones
Barrios
Caserios
Otros
';


--
-- TOC entry 5057 (class 0 OID 0)
-- Dependencies: 4156
-- Name: COLUMN shd001_registro_contribuyentes.cod_calle_avenida; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd001_registro_contribuyentes.cod_calle_avenida IS 'Código de la calle o avenida';


--
-- TOC entry 5058 (class 0 OID 0)
-- Dependencies: 4156
-- Name: COLUMN shd001_registro_contribuyentes.cod_vereda_edificio; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd001_registro_contribuyentes.cod_vereda_edificio IS 'Código de la vereda o edificio';


--
-- TOC entry 5059 (class 0 OID 0)
-- Dependencies: 4156
-- Name: COLUMN shd001_registro_contribuyentes.numero_vivienda_local; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd001_registro_contribuyentes.numero_vivienda_local IS 'Número de la vivienda, local, piso y apartamento';


--
-- TOC entry 5060 (class 0 OID 0)
-- Dependencies: 4156
-- Name: COLUMN shd001_registro_contribuyentes.telefonos_fijos; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd001_registro_contribuyentes.telefonos_fijos IS 'Telefonos fijos';


--
-- TOC entry 5061 (class 0 OID 0)
-- Dependencies: 4156
-- Name: COLUMN shd001_registro_contribuyentes.telefonos_celulares; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd001_registro_contribuyentes.telefonos_celulares IS 'Telefonos celulares';


--
-- TOC entry 5062 (class 0 OID 0)
-- Dependencies: 4156
-- Name: COLUMN shd001_registro_contribuyentes.correo_electronico; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd001_registro_contribuyentes.correo_electronico IS 'Correo electrónico';


--
-- TOC entry 4157 (class 1259 OID 502054)
-- Dependencies: 3
-- Name: shd002_cobradores; Type: TABLE; Schema: public; Owner: sisap; Tablespace:
--

CREATE TABLE shd002_cobradores (
    cod_presi integer NOT NULL,
    cod_entidad integer NOT NULL,
    cod_tipo_inst integer NOT NULL,
    cod_inst integer NOT NULL,
    cod_dep integer NOT NULL,
    rif_ci character varying(20) NOT NULL,
    personalidad integer NOT NULL,
    nombre_razon character varying(100) NOT NULL,
    fecha_ingreso date NOT NULL,
    recurso_cobro integer NOT NULL,
    condicion_actividad integer NOT NULL
);


ALTER TABLE public.shd002_cobradores OWNER TO sisap;

--
-- TOC entry 5063 (class 0 OID 0)
-- Dependencies: 4157
-- Name: TABLE shd002_cobradores; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON TABLE shd002_cobradores IS 'Registro de cobradores';


--
-- TOC entry 5064 (class 0 OID 0)
-- Dependencies: 4157
-- Name: COLUMN shd002_cobradores.cod_presi; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd002_cobradores.cod_presi IS 'Código de la presidencia';


--
-- TOC entry 5065 (class 0 OID 0)
-- Dependencies: 4157
-- Name: COLUMN shd002_cobradores.cod_entidad; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd002_cobradores.cod_entidad IS 'Código de la entidad';


--
-- TOC entry 5066 (class 0 OID 0)
-- Dependencies: 4157
-- Name: COLUMN shd002_cobradores.cod_tipo_inst; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd002_cobradores.cod_tipo_inst IS 'Código tipo de la institución';


--
-- TOC entry 5067 (class 0 OID 0)
-- Dependencies: 4157
-- Name: COLUMN shd002_cobradores.cod_inst; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd002_cobradores.cod_inst IS 'Código de la Institución';


--
-- TOC entry 5068 (class 0 OID 0)
-- Dependencies: 4157
-- Name: COLUMN shd002_cobradores.cod_dep; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd002_cobradores.cod_dep IS 'Código de la dependencia';


--
-- TOC entry 5069 (class 0 OID 0)
-- Dependencies: 4157
-- Name: COLUMN shd002_cobradores.rif_ci; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd002_cobradores.rif_ci IS 'Rif o Cédula de Identidad';


--
-- TOC entry 5070 (class 0 OID 0)
-- Dependencies: 4157
-- Name: COLUMN shd002_cobradores.personalidad; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd002_cobradores.personalidad IS 'Personalidad Juridica
1.- Natural
2.- Juridica';


--
-- TOC entry 5071 (class 0 OID 0)
-- Dependencies: 4157
-- Name: COLUMN shd002_cobradores.nombre_razon; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd002_cobradores.nombre_razon IS 'Nombre y apellidos / Razón social';


--
-- TOC entry 5072 (class 0 OID 0)
-- Dependencies: 4157
-- Name: COLUMN shd002_cobradores.fecha_ingreso; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd002_cobradores.fecha_ingreso IS 'Fecha de ingreso';


--
-- TOC entry 5073 (class 0 OID 0)
-- Dependencies: 4157
-- Name: COLUMN shd002_cobradores.recurso_cobro; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd002_cobradores.recurso_cobro IS 'Recurso para el cobro
1.- Ninguno
2.- Bicicleta
3.- Moto
4.- Vehiculo';


--
-- TOC entry 5074 (class 0 OID 0)
-- Dependencies: 4157
-- Name: COLUMN shd002_cobradores.condicion_actividad; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd002_cobradores.condicion_actividad IS 'Condición de actividad
1.- Activo
2.- Retirado
';


--
-- TOC entry 4158 (class 1259 OID 502059)
-- Dependencies: 3
-- Name: shd002_cobranza_pendiente; Type: TABLE; Schema: public; Owner: sisap; Tablespace:
--

CREATE TABLE shd002_cobranza_pendiente (
    cod_presi integer NOT NULL,
    cod_entidad integer NOT NULL,
    cod_tipo_inst integer NOT NULL,
    cod_inst integer NOT NULL,
    cod_dep integer NOT NULL,
    rif_ci character varying(20) NOT NULL,
    ano integer NOT NULL,
    cobranza_pendiente_acumulada numeric(26,2) NOT NULL,
    enero numeric(26,2) NOT NULL,
    febrero numeric(26,2) NOT NULL,
    marzo numeric(26,2) NOT NULL,
    abril numeric(26,2) NOT NULL,
    mayo numeric(26,2) NOT NULL,
    junio numeric(26,2) NOT NULL,
    julio numeric(26,2) NOT NULL,
    agosto numeric(26,2) NOT NULL,
    septiembre numeric(26,2) NOT NULL,
    octubre numeric(26,2) NOT NULL,
    noviembre numeric(26,2) NOT NULL,
    diciembre numeric(26,2) NOT NULL
);


ALTER TABLE public.shd002_cobranza_pendiente OWNER TO sisap;

--
-- TOC entry 5075 (class 0 OID 0)
-- Dependencies: 4158
-- Name: TABLE shd002_cobranza_pendiente; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON TABLE shd002_cobranza_pendiente IS 'Registra la cobranza realizada por el cobrador';


--
-- TOC entry 5076 (class 0 OID 0)
-- Dependencies: 4158
-- Name: COLUMN shd002_cobranza_pendiente.cod_presi; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd002_cobranza_pendiente.cod_presi IS 'Código de la presidencia';


--
-- TOC entry 5077 (class 0 OID 0)
-- Dependencies: 4158
-- Name: COLUMN shd002_cobranza_pendiente.cod_entidad; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd002_cobranza_pendiente.cod_entidad IS 'Código de la entidad federal';


--
-- TOC entry 5078 (class 0 OID 0)
-- Dependencies: 4158
-- Name: COLUMN shd002_cobranza_pendiente.cod_tipo_inst; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd002_cobranza_pendiente.cod_tipo_inst IS 'Códito tipo de Institución';


--
-- TOC entry 5079 (class 0 OID 0)
-- Dependencies: 4158
-- Name: COLUMN shd002_cobranza_pendiente.cod_inst; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd002_cobranza_pendiente.cod_inst IS 'Código de la Institución';


--
-- TOC entry 5080 (class 0 OID 0)
-- Dependencies: 4158
-- Name: COLUMN shd002_cobranza_pendiente.cod_dep; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd002_cobranza_pendiente.cod_dep IS 'Código de la dependencia';


--
-- TOC entry 5081 (class 0 OID 0)
-- Dependencies: 4158
-- Name: COLUMN shd002_cobranza_pendiente.rif_ci; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd002_cobranza_pendiente.rif_ci IS 'Rif o Cédula de identidad del cobrador';


--
-- TOC entry 5082 (class 0 OID 0)
-- Dependencies: 4158
-- Name: COLUMN shd002_cobranza_pendiente.ano; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd002_cobranza_pendiente.ano IS 'Año actual';


--
-- TOC entry 5083 (class 0 OID 0)
-- Dependencies: 4158
-- Name: COLUMN shd002_cobranza_pendiente.cobranza_pendiente_acumulada; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd002_cobranza_pendiente.cobranza_pendiente_acumulada IS 'Cobranza pendiente acumulada en años anteriores';


--
-- TOC entry 5084 (class 0 OID 0)
-- Dependencies: 4158
-- Name: COLUMN shd002_cobranza_pendiente.enero; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd002_cobranza_pendiente.enero IS 'Mes de enero';


--
-- TOC entry 5085 (class 0 OID 0)
-- Dependencies: 4158
-- Name: COLUMN shd002_cobranza_pendiente.febrero; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd002_cobranza_pendiente.febrero IS 'Mes de febrero';


--
-- TOC entry 5086 (class 0 OID 0)
-- Dependencies: 4158
-- Name: COLUMN shd002_cobranza_pendiente.marzo; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd002_cobranza_pendiente.marzo IS 'Mes de marzo';


--
-- TOC entry 5087 (class 0 OID 0)
-- Dependencies: 4158
-- Name: COLUMN shd002_cobranza_pendiente.abril; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd002_cobranza_pendiente.abril IS 'Mes de abril';


--
-- TOC entry 5088 (class 0 OID 0)
-- Dependencies: 4158
-- Name: COLUMN shd002_cobranza_pendiente.mayo; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd002_cobranza_pendiente.mayo IS 'Mes de mayo';


--
-- TOC entry 5089 (class 0 OID 0)
-- Dependencies: 4158
-- Name: COLUMN shd002_cobranza_pendiente.junio; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd002_cobranza_pendiente.junio IS 'Mes de junio';


--
-- TOC entry 5090 (class 0 OID 0)
-- Dependencies: 4158
-- Name: COLUMN shd002_cobranza_pendiente.julio; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd002_cobranza_pendiente.julio IS 'Mes de julio';


--
-- TOC entry 5091 (class 0 OID 0)
-- Dependencies: 4158
-- Name: COLUMN shd002_cobranza_pendiente.agosto; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd002_cobranza_pendiente.agosto IS 'Mes de agosto';


--
-- TOC entry 5092 (class 0 OID 0)
-- Dependencies: 4158
-- Name: COLUMN shd002_cobranza_pendiente.septiembre; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd002_cobranza_pendiente.septiembre IS 'Mes de septiembre';


--
-- TOC entry 5093 (class 0 OID 0)
-- Dependencies: 4158
-- Name: COLUMN shd002_cobranza_pendiente.octubre; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd002_cobranza_pendiente.octubre IS 'Mes de octubre';


--
-- TOC entry 5094 (class 0 OID 0)
-- Dependencies: 4158
-- Name: COLUMN shd002_cobranza_pendiente.noviembre; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd002_cobranza_pendiente.noviembre IS 'Mes de noviembre';


--
-- TOC entry 5095 (class 0 OID 0)
-- Dependencies: 4158
-- Name: COLUMN shd002_cobranza_pendiente.diciembre; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd002_cobranza_pendiente.diciembre IS 'Mes de diciembre';


--
-- TOC entry 4159 (class 1259 OID 502064)
-- Dependencies: 3
-- Name: shd002_cobranza_realizada; Type: TABLE; Schema: public; Owner: sisap; Tablespace:
--

CREATE TABLE shd002_cobranza_realizada (
    cod_presi integer NOT NULL,
    cod_entidad integer NOT NULL,
    cod_tipo_inst integer NOT NULL,
    cod_inst integer NOT NULL,
    cod_dep integer NOT NULL,
    rif_ci character varying(20) NOT NULL,
    ano integer NOT NULL,
    cobranza_acumulada numeric(26,2) NOT NULL,
    enero numeric(26,2) NOT NULL,
    febrero numeric(26,2) NOT NULL,
    marzo numeric(26,2) NOT NULL,
    abril numeric(26,2) NOT NULL,
    mayo numeric(26,2) NOT NULL,
    junio numeric(26,2) NOT NULL,
    julio numeric(26,2) NOT NULL,
    agosto numeric(26,2) NOT NULL,
    septiembre numeric(26,2) NOT NULL,
    octubre numeric(26,2) NOT NULL,
    noviembre numeric(26,2) NOT NULL,
    diciembre numeric(26,2) NOT NULL
);


ALTER TABLE public.shd002_cobranza_realizada OWNER TO sisap;

--
-- TOC entry 5096 (class 0 OID 0)
-- Dependencies: 4159
-- Name: TABLE shd002_cobranza_realizada; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON TABLE shd002_cobranza_realizada IS 'Registra la cobranza realizada por el cobrador';


--
-- TOC entry 5097 (class 0 OID 0)
-- Dependencies: 4159
-- Name: COLUMN shd002_cobranza_realizada.cod_presi; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd002_cobranza_realizada.cod_presi IS 'Código de la presidencia';


--
-- TOC entry 5098 (class 0 OID 0)
-- Dependencies: 4159
-- Name: COLUMN shd002_cobranza_realizada.cod_entidad; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd002_cobranza_realizada.cod_entidad IS 'Código de la entidad federal';


--
-- TOC entry 5099 (class 0 OID 0)
-- Dependencies: 4159
-- Name: COLUMN shd002_cobranza_realizada.cod_tipo_inst; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd002_cobranza_realizada.cod_tipo_inst IS 'Códito tipo de Institución';


--
-- TOC entry 5100 (class 0 OID 0)
-- Dependencies: 4159
-- Name: COLUMN shd002_cobranza_realizada.cod_inst; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd002_cobranza_realizada.cod_inst IS 'Código de la Institución';


--
-- TOC entry 5101 (class 0 OID 0)
-- Dependencies: 4159
-- Name: COLUMN shd002_cobranza_realizada.cod_dep; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd002_cobranza_realizada.cod_dep IS 'Código de la dependencia';


--
-- TOC entry 5102 (class 0 OID 0)
-- Dependencies: 4159
-- Name: COLUMN shd002_cobranza_realizada.rif_ci; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd002_cobranza_realizada.rif_ci IS 'Rif o Cédula de identidad del cobrador';


--
-- TOC entry 5103 (class 0 OID 0)
-- Dependencies: 4159
-- Name: COLUMN shd002_cobranza_realizada.ano; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd002_cobranza_realizada.ano IS 'Año actual';


--
-- TOC entry 5104 (class 0 OID 0)
-- Dependencies: 4159
-- Name: COLUMN shd002_cobranza_realizada.cobranza_acumulada; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd002_cobranza_realizada.cobranza_acumulada IS 'Cobranza acumulada en años anteriores';


--
-- TOC entry 5105 (class 0 OID 0)
-- Dependencies: 4159
-- Name: COLUMN shd002_cobranza_realizada.enero; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd002_cobranza_realizada.enero IS 'Mes de enero';


--
-- TOC entry 5106 (class 0 OID 0)
-- Dependencies: 4159
-- Name: COLUMN shd002_cobranza_realizada.febrero; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd002_cobranza_realizada.febrero IS 'Mes de febrero';


--
-- TOC entry 5107 (class 0 OID 0)
-- Dependencies: 4159
-- Name: COLUMN shd002_cobranza_realizada.marzo; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd002_cobranza_realizada.marzo IS 'Mes de marzo';


--
-- TOC entry 5108 (class 0 OID 0)
-- Dependencies: 4159
-- Name: COLUMN shd002_cobranza_realizada.abril; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd002_cobranza_realizada.abril IS 'Mes de abril';


--
-- TOC entry 5109 (class 0 OID 0)
-- Dependencies: 4159
-- Name: COLUMN shd002_cobranza_realizada.mayo; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd002_cobranza_realizada.mayo IS 'Mes de mayo';


--
-- TOC entry 5110 (class 0 OID 0)
-- Dependencies: 4159
-- Name: COLUMN shd002_cobranza_realizada.junio; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd002_cobranza_realizada.junio IS 'Mes de junio';


--
-- TOC entry 5111 (class 0 OID 0)
-- Dependencies: 4159
-- Name: COLUMN shd002_cobranza_realizada.julio; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd002_cobranza_realizada.julio IS 'Mes de julio';


--
-- TOC entry 5112 (class 0 OID 0)
-- Dependencies: 4159
-- Name: COLUMN shd002_cobranza_realizada.agosto; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd002_cobranza_realizada.agosto IS 'Mes de agosto';


--
-- TOC entry 5113 (class 0 OID 0)
-- Dependencies: 4159
-- Name: COLUMN shd002_cobranza_realizada.septiembre; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd002_cobranza_realizada.septiembre IS 'Mes de septiembre';


--
-- TOC entry 5114 (class 0 OID 0)
-- Dependencies: 4159
-- Name: COLUMN shd002_cobranza_realizada.octubre; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd002_cobranza_realizada.octubre IS 'Mes de octubre';


--
-- TOC entry 5115 (class 0 OID 0)
-- Dependencies: 4159
-- Name: COLUMN shd002_cobranza_realizada.noviembre; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd002_cobranza_realizada.noviembre IS 'Mes de noviembre';


--
-- TOC entry 5116 (class 0 OID 0)
-- Dependencies: 4159
-- Name: COLUMN shd002_cobranza_realizada.diciembre; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd002_cobranza_realizada.diciembre IS 'Mes de diciembre';


--
-- TOC entry 4160 (class 1259 OID 502069)
-- Dependencies: 3
-- Name: shd003_codigo_ingresos; Type: TABLE; Schema: public; Owner: sisap; Tablespace:
--

CREATE TABLE shd003_codigo_ingresos (
    cod_ingreso integer NOT NULL,
    denominacion character varying(100) NOT NULL,
    cod_partida integer NOT NULL,
    cod_generica integer NOT NULL,
    cod_especifica integer NOT NULL,
    cod_subespec integer NOT NULL,
    cod_auxiliar integer NOT NULL
);


ALTER TABLE public.shd003_codigo_ingresos OWNER TO sisap;

--
-- TOC entry 5117 (class 0 OID 0)
-- Dependencies: 4160
-- Name: TABLE shd003_codigo_ingresos; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON TABLE shd003_codigo_ingresos IS 'Registro de código de ingresos';


--
-- TOC entry 5118 (class 0 OID 0)
-- Dependencies: 4160
-- Name: COLUMN shd003_codigo_ingresos.cod_ingreso; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd003_codigo_ingresos.cod_ingreso IS 'Código de ingreso
01 = Industria y comercio
02 = Vehículos
03 = Propaganda comercial
04 = Inmuebles urbanos
05 = Aseo domiciliario
06 = Arrendamiento de tierras
07 = Crédito de vivienda

';


--
-- TOC entry 5119 (class 0 OID 0)
-- Dependencies: 4160
-- Name: COLUMN shd003_codigo_ingresos.denominacion; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd003_codigo_ingresos.denominacion IS 'Denominación de ingreso';


--
-- TOC entry 5120 (class 0 OID 0)
-- Dependencies: 4160
-- Name: COLUMN shd003_codigo_ingresos.cod_partida; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd003_codigo_ingresos.cod_partida IS 'Partida';


--
-- TOC entry 5121 (class 0 OID 0)
-- Dependencies: 4160
-- Name: COLUMN shd003_codigo_ingresos.cod_generica; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd003_codigo_ingresos.cod_generica IS 'Código de la generica';


--
-- TOC entry 5122 (class 0 OID 0)
-- Dependencies: 4160
-- Name: COLUMN shd003_codigo_ingresos.cod_especifica; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd003_codigo_ingresos.cod_especifica IS 'Código de la especifica';


--
-- TOC entry 5123 (class 0 OID 0)
-- Dependencies: 4160
-- Name: COLUMN shd003_codigo_ingresos.cod_subespec; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd003_codigo_ingresos.cod_subespec IS 'Código de la Subespecifica';


--
-- TOC entry 5124 (class 0 OID 0)
-- Dependencies: 4160
-- Name: COLUMN shd003_codigo_ingresos.cod_auxiliar; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd003_codigo_ingresos.cod_auxiliar IS 'Código del auxiliar';


--
-- TOC entry 4161 (class 1259 OID 502074)
-- Dependencies: 4848 3
-- Name: shd100_actividades; Type: TABLE; Schema: public; Owner: sisap; Tablespace:
--

CREATE TABLE shd100_actividades (
    cod_presi integer NOT NULL,
    cod_entidad integer NOT NULL,
    cod_tipo_inst integer NOT NULL,
    cod_inst integer NOT NULL,
    cod_dep integer NOT NULL,
    cod_actividad character varying(20) NOT NULL,
    denominacion_actividad text NOT NULL,
    alicuota numeric(5,2) DEFAULT 0,
    unidades_tributarias numeric(5,2),
    minimo_tributable numeric(26,2)
);


ALTER TABLE public.shd100_actividades OWNER TO sisap;

--
-- TOC entry 5125 (class 0 OID 0)
-- Dependencies: 4161
-- Name: TABLE shd100_actividades; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON TABLE shd100_actividades IS 'Registra de la ordenanza de patente de industria y comercio las actividades economicas';


--
-- TOC entry 5126 (class 0 OID 0)
-- Dependencies: 4161
-- Name: COLUMN shd100_actividades.cod_presi; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd100_actividades.cod_presi IS 'Código de la presidencia';


--
-- TOC entry 5127 (class 0 OID 0)
-- Dependencies: 4161
-- Name: COLUMN shd100_actividades.cod_entidad; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd100_actividades.cod_entidad IS 'Código de la entidad';


--
-- TOC entry 5128 (class 0 OID 0)
-- Dependencies: 4161
-- Name: COLUMN shd100_actividades.cod_tipo_inst; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd100_actividades.cod_tipo_inst IS 'Código tipo de Institución';


--
-- TOC entry 5129 (class 0 OID 0)
-- Dependencies: 4161
-- Name: COLUMN shd100_actividades.cod_inst; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd100_actividades.cod_inst IS 'Código de la Institución';


--
-- TOC entry 5130 (class 0 OID 0)
-- Dependencies: 4161
-- Name: COLUMN shd100_actividades.cod_dep; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd100_actividades.cod_dep IS 'Código de la dependencia';


--
-- TOC entry 5131 (class 0 OID 0)
-- Dependencies: 4161
-- Name: COLUMN shd100_actividades.cod_actividad; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd100_actividades.cod_actividad IS 'Código de la actividad economica';


--
-- TOC entry 5132 (class 0 OID 0)
-- Dependencies: 4161
-- Name: COLUMN shd100_actividades.denominacion_actividad; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd100_actividades.denominacion_actividad IS 'Denominación de la actividad economica';


--
-- TOC entry 5133 (class 0 OID 0)
-- Dependencies: 4161
-- Name: COLUMN shd100_actividades.alicuota; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd100_actividades.alicuota IS 'Porcentaje alicuota';


--
-- TOC entry 5134 (class 0 OID 0)
-- Dependencies: 4161
-- Name: COLUMN shd100_actividades.unidades_tributarias; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd100_actividades.unidades_tributarias IS 'Unidades tributarias';


--
-- TOC entry 5135 (class 0 OID 0)
-- Dependencies: 4161
-- Name: COLUMN shd100_actividades.minimo_tributable; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd100_actividades.minimo_tributable IS 'Monto minimo tributable';


--
-- TOC entry 4162 (class 1259 OID 502083)
-- Dependencies: 3
-- Name: shd100_articulos; Type: TABLE; Schema: public; Owner: sisap; Tablespace:
--

CREATE TABLE shd100_articulos (
    cod_presi integer NOT NULL,
    cod_entidad integer NOT NULL,
    cod_tipo_inst integer NOT NULL,
    cod_inst integer NOT NULL,
    cod_dep integer NOT NULL,
    articulos_patente text NOT NULL
);


ALTER TABLE public.shd100_articulos OWNER TO sisap;

--
-- TOC entry 5136 (class 0 OID 0)
-- Dependencies: 4162
-- Name: TABLE shd100_articulos; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON TABLE shd100_articulos IS 'Registra los artículos para ser impresos en el boletín de notificación';


--
-- TOC entry 5137 (class 0 OID 0)
-- Dependencies: 4162
-- Name: COLUMN shd100_articulos.cod_presi; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd100_articulos.cod_presi IS 'Código de la presidencia';


--
-- TOC entry 5138 (class 0 OID 0)
-- Dependencies: 4162
-- Name: COLUMN shd100_articulos.cod_entidad; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd100_articulos.cod_entidad IS 'Código de la entidad federal';


--
-- TOC entry 5139 (class 0 OID 0)
-- Dependencies: 4162
-- Name: COLUMN shd100_articulos.cod_tipo_inst; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd100_articulos.cod_tipo_inst IS 'Código tipo de Institución';


--
-- TOC entry 5140 (class 0 OID 0)
-- Dependencies: 4162
-- Name: COLUMN shd100_articulos.cod_inst; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd100_articulos.cod_inst IS 'Código de Institución';


--
-- TOC entry 5141 (class 0 OID 0)
-- Dependencies: 4162
-- Name: COLUMN shd100_articulos.cod_dep; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd100_articulos.cod_dep IS 'Código de la dependencia';


--
-- TOC entry 5142 (class 0 OID 0)
-- Dependencies: 4162
-- Name: COLUMN shd100_articulos.articulos_patente; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd100_articulos.articulos_patente IS 'Artículos de la ordenanza de patente de industria y comercio';


--
-- TOC entry 4165 (class 1259 OID 502106)
-- Dependencies: 3
-- Name: shd100_declaracion_actividades; Type: TABLE; Schema: public; Owner: sisap; Tablespace:
--

CREATE TABLE shd100_declaracion_actividades (
    cod_presi integer NOT NULL,
    cod_entidad integer NOT NULL,
    cod_tipo_inst integer NOT NULL,
    cod_inst integer NOT NULL,
    cod_dep integer NOT NULL,
    rif_cedula character varying(20) NOT NULL,
    numero_declaracion character varying(20) NOT NULL,
    cod_actividad character varying(20) NOT NULL,
    monto_ingresos numeric(26,2) NOT NULL,
    monto_impuesto numeric(26,2) NOT NULL,
    alicuota_aplicada numeric(3,2)
);


ALTER TABLE public.shd100_declaracion_actividades OWNER TO sisap;

--
-- TOC entry 5143 (class 0 OID 0)
-- Dependencies: 4165
-- Name: TABLE shd100_declaracion_actividades; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON TABLE shd100_declaracion_actividades IS 'Registra las actividades economicas declaradas por el contribuyente';


--
-- TOC entry 5144 (class 0 OID 0)
-- Dependencies: 4165
-- Name: COLUMN shd100_declaracion_actividades.cod_presi; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd100_declaracion_actividades.cod_presi IS 'Código de la presidencia';


--
-- TOC entry 5145 (class 0 OID 0)
-- Dependencies: 4165
-- Name: COLUMN shd100_declaracion_actividades.cod_entidad; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd100_declaracion_actividades.cod_entidad IS 'Código de la entidad';


--
-- TOC entry 5146 (class 0 OID 0)
-- Dependencies: 4165
-- Name: COLUMN shd100_declaracion_actividades.cod_tipo_inst; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd100_declaracion_actividades.cod_tipo_inst IS 'Código tipo de Institución';


--
-- TOC entry 5147 (class 0 OID 0)
-- Dependencies: 4165
-- Name: COLUMN shd100_declaracion_actividades.cod_inst; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd100_declaracion_actividades.cod_inst IS 'Código de la Institución';


--
-- TOC entry 5148 (class 0 OID 0)
-- Dependencies: 4165
-- Name: COLUMN shd100_declaracion_actividades.cod_dep; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd100_declaracion_actividades.cod_dep IS 'Código de la depedencia';


--
-- TOC entry 5149 (class 0 OID 0)
-- Dependencies: 4165
-- Name: COLUMN shd100_declaracion_actividades.numero_declaracion; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd100_declaracion_actividades.numero_declaracion IS 'Número de declaración';


--
-- TOC entry 5150 (class 0 OID 0)
-- Dependencies: 4165
-- Name: COLUMN shd100_declaracion_actividades.cod_actividad; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd100_declaracion_actividades.cod_actividad IS 'Código de actividad economica declarada por el contribuyente';


--
-- TOC entry 5151 (class 0 OID 0)
-- Dependencies: 4165
-- Name: COLUMN shd100_declaracion_actividades.monto_ingresos; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd100_declaracion_actividades.monto_ingresos IS 'Monto de ingreso declarado por el contribuyente';


--
-- TOC entry 5152 (class 0 OID 0)
-- Dependencies: 4165
-- Name: COLUMN shd100_declaracion_actividades.monto_impuesto; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd100_declaracion_actividades.monto_impuesto IS 'Monto del impuesto calculado según los ingresos declarados por el contribuyente';


--
-- TOC entry 5153 (class 0 OID 0)
-- Dependencies: 4165
-- Name: COLUMN shd100_declaracion_actividades.alicuota_aplicada; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd100_declaracion_actividades.alicuota_aplicada IS 'Alicuota aplicada';


--
-- TOC entry 4164 (class 1259 OID 502096)
-- Dependencies: 3
-- Name: shd100_declaracion_ingresos; Type: TABLE; Schema: public; Owner: sisap; Tablespace:
--

CREATE TABLE shd100_declaracion_ingresos (
    cod_presi integer NOT NULL,
    cod_entidad integer NOT NULL,
    cod_tipo_inst integer NOT NULL,
    cod_inst integer NOT NULL,
    cod_dep integer NOT NULL,
    rif_cedula character varying(20) NOT NULL,
    numero_declaracion character varying(20) NOT NULL,
    periodo_desde date NOT NULL,
    periodo_hasta date NOT NULL,
    capital numeric(26,2),
    numero_empleados integer,
    numero_obreros integer,
    fecha_declaracion date NOT NULL
);


ALTER TABLE public.shd100_declaracion_ingresos OWNER TO sisap;

--
-- TOC entry 5154 (class 0 OID 0)
-- Dependencies: 4164
-- Name: TABLE shd100_declaracion_ingresos; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON TABLE shd100_declaracion_ingresos IS 'Registro de la declaración de ingresos brutos de los contribuyentes';


--
-- TOC entry 5155 (class 0 OID 0)
-- Dependencies: 4164
-- Name: COLUMN shd100_declaracion_ingresos.cod_presi; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd100_declaracion_ingresos.cod_presi IS 'Código de la presidencia';


--
-- TOC entry 5156 (class 0 OID 0)
-- Dependencies: 4164
-- Name: COLUMN shd100_declaracion_ingresos.cod_entidad; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd100_declaracion_ingresos.cod_entidad IS 'Código de la entidad';


--
-- TOC entry 5157 (class 0 OID 0)
-- Dependencies: 4164
-- Name: COLUMN shd100_declaracion_ingresos.cod_tipo_inst; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd100_declaracion_ingresos.cod_tipo_inst IS 'Código tipo de Institución';


--
-- TOC entry 5158 (class 0 OID 0)
-- Dependencies: 4164
-- Name: COLUMN shd100_declaracion_ingresos.cod_inst; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd100_declaracion_ingresos.cod_inst IS 'Código de la Institución';


--
-- TOC entry 5159 (class 0 OID 0)
-- Dependencies: 4164
-- Name: COLUMN shd100_declaracion_ingresos.cod_dep; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd100_declaracion_ingresos.cod_dep IS 'Código de la dependencia';


--
-- TOC entry 5160 (class 0 OID 0)
-- Dependencies: 4164
-- Name: COLUMN shd100_declaracion_ingresos.numero_declaracion; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd100_declaracion_ingresos.numero_declaracion IS 'Número de declaración ';


--
-- TOC entry 5161 (class 0 OID 0)
-- Dependencies: 4164
-- Name: COLUMN shd100_declaracion_ingresos.periodo_desde; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd100_declaracion_ingresos.periodo_desde IS 'Periodo de declaración desde';


--
-- TOC entry 5162 (class 0 OID 0)
-- Dependencies: 4164
-- Name: COLUMN shd100_declaracion_ingresos.periodo_hasta; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd100_declaracion_ingresos.periodo_hasta IS 'Periodo de declaración hasta';


--
-- TOC entry 5163 (class 0 OID 0)
-- Dependencies: 4164
-- Name: COLUMN shd100_declaracion_ingresos.capital; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd100_declaracion_ingresos.capital IS 'Capital';


--
-- TOC entry 5164 (class 0 OID 0)
-- Dependencies: 4164
-- Name: COLUMN shd100_declaracion_ingresos.numero_empleados; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd100_declaracion_ingresos.numero_empleados IS 'Número de empleados';


--
-- TOC entry 5165 (class 0 OID 0)
-- Dependencies: 4164
-- Name: COLUMN shd100_declaracion_ingresos.numero_obreros; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd100_declaracion_ingresos.numero_obreros IS 'Número de obreros';


--
-- TOC entry 5166 (class 0 OID 0)
-- Dependencies: 4164
-- Name: COLUMN shd100_declaracion_ingresos.fecha_declaracion; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd100_declaracion_ingresos.fecha_declaracion IS 'Fecha de declaración de ingresos';


--
-- TOC entry 4163 (class 1259 OID 502091)
-- Dependencies: 3
-- Name: shd100_patente; Type: TABLE; Schema: public; Owner: sisap; Tablespace:
--

CREATE TABLE shd100_patente (
    cod_presi integer NOT NULL,
    cod_entidad integer NOT NULL,
    cod_tipo_inst integer NOT NULL,
    cod_inst integer NOT NULL,
    cod_dep integer NOT NULL,
    rif_cedula character varying(20) NOT NULL,
    numero_solicitud character varying(20) NOT NULL,
    numero_patente character varying(20) NOT NULL,
    frecuencia_pago integer NOT NULL,
    monto_mensual numeric(26,2) NOT NULL,
    pago_todo integer NOT NULL,
    suspendido integer NOT NULL,
    rif_ci_cobrador character varying(20) NOT NULL,
    ultimo_ano_facturado integer,
    ultimo_mes_facturado integer,
    fecha_ultima_decla date,
    ingresos_declarados numeric(26,2),
    ultimo_ejercicio_decla integer,
    periodo_desde date,
    periodo_hasta date,
    fecha_patente date NOT NULL,
    numero_expediente integer
);


ALTER TABLE public.shd100_patente OWNER TO sisap;

--
-- TOC entry 5167 (class 0 OID 0)
-- Dependencies: 4163
-- Name: TABLE shd100_patente; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON TABLE shd100_patente IS 'Registro de otorgamiento de licencia de industria y comercio';


--
-- TOC entry 5168 (class 0 OID 0)
-- Dependencies: 4163
-- Name: COLUMN shd100_patente.cod_presi; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd100_patente.cod_presi IS 'Código de la presidencia';


--
-- TOC entry 5169 (class 0 OID 0)
-- Dependencies: 4163
-- Name: COLUMN shd100_patente.cod_entidad; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd100_patente.cod_entidad IS 'Código de la entidad';


--
-- TOC entry 5170 (class 0 OID 0)
-- Dependencies: 4163
-- Name: COLUMN shd100_patente.cod_tipo_inst; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd100_patente.cod_tipo_inst IS 'Código tipo de Institución';


--
-- TOC entry 5171 (class 0 OID 0)
-- Dependencies: 4163
-- Name: COLUMN shd100_patente.cod_inst; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd100_patente.cod_inst IS 'Código de la Institución';


--
-- TOC entry 5172 (class 0 OID 0)
-- Dependencies: 4163
-- Name: COLUMN shd100_patente.cod_dep; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd100_patente.cod_dep IS 'Código de la dependencia';


--
-- TOC entry 5173 (class 0 OID 0)
-- Dependencies: 4163
-- Name: COLUMN shd100_patente.numero_solicitud; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd100_patente.numero_solicitud IS 'número de solicitud de patente de industria y comercio';


--
-- TOC entry 5174 (class 0 OID 0)
-- Dependencies: 4163
-- Name: COLUMN shd100_patente.numero_patente; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd100_patente.numero_patente IS 'Número de patente de industria y comercio';


--
-- TOC entry 5175 (class 0 OID 0)
-- Dependencies: 4163
-- Name: COLUMN shd100_patente.frecuencia_pago; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd100_patente.frecuencia_pago IS 'Frecuencia de pago
1.- Mensual
2.- Bimestral
3.- Trimestral
4.- Semestral
5.- Anual';


--
-- TOC entry 5176 (class 0 OID 0)
-- Dependencies: 4163
-- Name: COLUMN shd100_patente.monto_mensual; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd100_patente.monto_mensual IS 'Monto mensual';


--
-- TOC entry 5177 (class 0 OID 0)
-- Dependencies: 4163
-- Name: COLUMN shd100_patente.pago_todo; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd100_patente.pago_todo IS 'Pago todo el año ?
1.- Si
2.- No
Por defecto "2"';


--
-- TOC entry 5178 (class 0 OID 0)
-- Dependencies: 4163
-- Name: COLUMN shd100_patente.suspendido; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd100_patente.suspendido IS 'Contribuyente suspendido ?
1.- Si
2.- No
Por defecto "2"';


--
-- TOC entry 5179 (class 0 OID 0)
-- Dependencies: 4163
-- Name: COLUMN shd100_patente.rif_ci_cobrador; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd100_patente.rif_ci_cobrador IS 'Rif o Cédula de identidad del cobrador';


--
-- TOC entry 5180 (class 0 OID 0)
-- Dependencies: 4163
-- Name: COLUMN shd100_patente.ultimo_ano_facturado; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd100_patente.ultimo_ano_facturado IS 'Ultimo año facturado';


--
-- TOC entry 5181 (class 0 OID 0)
-- Dependencies: 4163
-- Name: COLUMN shd100_patente.ultimo_mes_facturado; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd100_patente.ultimo_mes_facturado IS 'Ultimo mes facturado';


--
-- TOC entry 5182 (class 0 OID 0)
-- Dependencies: 4163
-- Name: COLUMN shd100_patente.fecha_ultima_decla; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd100_patente.fecha_ultima_decla IS 'Fecha ultima declaración';


--
-- TOC entry 5183 (class 0 OID 0)
-- Dependencies: 4163
-- Name: COLUMN shd100_patente.ingresos_declarados; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd100_patente.ingresos_declarados IS 'Monto ingresos declarados';


--
-- TOC entry 5184 (class 0 OID 0)
-- Dependencies: 4163
-- Name: COLUMN shd100_patente.ultimo_ejercicio_decla; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd100_patente.ultimo_ejercicio_decla IS 'Ultimo ejercicio declarado';


--
-- TOC entry 5185 (class 0 OID 0)
-- Dependencies: 4163
-- Name: COLUMN shd100_patente.periodo_desde; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd100_patente.periodo_desde IS 'Periodo declarado desde';


--
-- TOC entry 5186 (class 0 OID 0)
-- Dependencies: 4163
-- Name: COLUMN shd100_patente.periodo_hasta; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd100_patente.periodo_hasta IS 'Periodo declarado hasta';


--
-- TOC entry 5187 (class 0 OID 0)
-- Dependencies: 4163
-- Name: COLUMN shd100_patente.fecha_patente; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd100_patente.fecha_patente IS 'Fecha de otorgamiento de la patente';


--
-- TOC entry 5188 (class 0 OID 0)
-- Dependencies: 4163
-- Name: COLUMN shd100_patente.numero_expediente; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd100_patente.numero_expediente IS 'Número de expediente';


--
-- TOC entry 4166 (class 1259 OID 502116)
-- Dependencies: 3
-- Name: shd100_patente_actividades; Type: TABLE; Schema: public; Owner: sisap; Tablespace:
--

CREATE TABLE shd100_patente_actividades (
    cod_presi integer NOT NULL,
    cod_entidad integer NOT NULL,
    cod_tipo_inst integer NOT NULL,
    cod_inst integer NOT NULL,
    cod_dep integer NOT NULL,
    rif_cedula character varying(20) NOT NULL,
    cod_actividad character varying(20) NOT NULL,
    numero_aforos integer NOT NULL,
    monto_aforo_anual numeric(26,2) NOT NULL,
    total_aforo_anual numeric(26,2) NOT NULL
);


ALTER TABLE public.shd100_patente_actividades OWNER TO sisap;

--
-- TOC entry 5189 (class 0 OID 0)
-- Dependencies: 4166
-- Name: TABLE shd100_patente_actividades; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON TABLE shd100_patente_actividades IS 'Registro de monto de aforos de las actividades economicas del contribuyentes';


--
-- TOC entry 5190 (class 0 OID 0)
-- Dependencies: 4166
-- Name: COLUMN shd100_patente_actividades.cod_presi; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd100_patente_actividades.cod_presi IS 'Código de la presidencia';


--
-- TOC entry 5191 (class 0 OID 0)
-- Dependencies: 4166
-- Name: COLUMN shd100_patente_actividades.cod_entidad; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd100_patente_actividades.cod_entidad IS 'Código de la entidad';


--
-- TOC entry 5192 (class 0 OID 0)
-- Dependencies: 4166
-- Name: COLUMN shd100_patente_actividades.cod_tipo_inst; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd100_patente_actividades.cod_tipo_inst IS 'Código tipo de Institución';


--
-- TOC entry 5193 (class 0 OID 0)
-- Dependencies: 4166
-- Name: COLUMN shd100_patente_actividades.cod_inst; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd100_patente_actividades.cod_inst IS 'Código de la Institución';


--
-- TOC entry 5194 (class 0 OID 0)
-- Dependencies: 4166
-- Name: COLUMN shd100_patente_actividades.cod_dep; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd100_patente_actividades.cod_dep IS 'Código de la dependencia';


--
-- TOC entry 5195 (class 0 OID 0)
-- Dependencies: 4166
-- Name: COLUMN shd100_patente_actividades.cod_actividad; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd100_patente_actividades.cod_actividad IS 'Código de la actividad economica';


--
-- TOC entry 5196 (class 0 OID 0)
-- Dependencies: 4166
-- Name: COLUMN shd100_patente_actividades.numero_aforos; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd100_patente_actividades.numero_aforos IS 'Número de aforos';


--
-- TOC entry 5197 (class 0 OID 0)
-- Dependencies: 4166
-- Name: COLUMN shd100_patente_actividades.monto_aforo_anual; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd100_patente_actividades.monto_aforo_anual IS 'Monto aforo anual';


--
-- TOC entry 5198 (class 0 OID 0)
-- Dependencies: 4166
-- Name: COLUMN shd100_patente_actividades.total_aforo_anual; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd100_patente_actividades.total_aforo_anual IS 'Total aforo anual';


--
-- TOC entry 4167 (class 1259 OID 502126)
-- Dependencies: 3
-- Name: shd100_solicitud; Type: TABLE; Schema: public; Owner: sisap; Tablespace:
--

CREATE TABLE shd100_solicitud (
    cod_presi integer NOT NULL,
    cod_entidad integer NOT NULL,
    cod_tipo_inst integer NOT NULL,
    cod_inst integer NOT NULL,
    cod_dep integer NOT NULL,
    numero_solicitud character varying(20) NOT NULL,
    fecha_solicitud date NOT NULL,
    rif_cedula character varying(20) NOT NULL,
    numero_ficha_catastral integer NOT NULL,
    capital numeric(26,2) NOT NULL,
    tipo_establecimiento integer NOT NULL,
    tipo_local integer NOT NULL,
    nacionalidad integer NOT NULL,
    cedula_identidad integer NOT NULL,
    nombres_apellidos character varying(100) NOT NULL,
    cod_pais integer NOT NULL,
    cod_estado integer NOT NULL,
    cod_municipio integer NOT NULL,
    cod_parroquia integer NOT NULL,
    cod_centro integer NOT NULL,
    cod_vialidad integer NOT NULL,
    cod_vereda integer,
    numero_casa_local character varying(30) NOT NULL,
    telefonos_fijos character varying(50),
    telefonos_celulares character varying(50),
    correo_electronico character varying(50),
    fecha_inicio_const date NOT NULL,
    fecha_cierre_const date NOT NULL,
    fecha_inicio_econo date NOT NULL,
    fecha_cierre_economico date NOT NULL,
    registro_mercantil text,
    tiene_sucursal integer NOT NULL,
    es_fabricante integer NOT NULL,
    numero_empleado integer,
    numero_obreros integer,
    tilde_reg_mercantil integer,
    tilde_fotoco_ci integer,
    tilde_acta_const integer,
    tilde_uso_conforme integer,
    tilde_croquis integer,
    tilde_bomberos integer,
    tilde_rif integer,
    tilde_solvencia integer,
    tilde_concejo integer,
    tilde_recibo integer,
    tilde_planilla integer,
    tilde_permiso integer,
    numero_patente character varying(20),
    categoria_comercial integer,
    mercado_cubre integer,
    horario_trab_desde character varying(7),
    horario_trab_hasta character varying(7),
    distancia_bar numeric(8,3),
    distancia_hospital numeric(8,3),
    distancia_educativo numeric(8,3),
    distancia_funeraria numeric(8,3),
    distancia_estacion numeric(8,3),
    distancia_gubernam numeric(8,3)
);


ALTER TABLE public.shd100_solicitud OWNER TO sisap;

--
-- TOC entry 5199 (class 0 OID 0)
-- Dependencies: 4167
-- Name: TABLE shd100_solicitud; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON TABLE shd100_solicitud IS 'Registro de las solicitudes de licencia de patente de industria y comercio';


--
-- TOC entry 5200 (class 0 OID 0)
-- Dependencies: 4167
-- Name: COLUMN shd100_solicitud.cod_presi; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd100_solicitud.cod_presi IS 'Código de la presidencia';


--
-- TOC entry 5201 (class 0 OID 0)
-- Dependencies: 4167
-- Name: COLUMN shd100_solicitud.cod_entidad; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd100_solicitud.cod_entidad IS 'Código de la entidad';


--
-- TOC entry 5202 (class 0 OID 0)
-- Dependencies: 4167
-- Name: COLUMN shd100_solicitud.cod_tipo_inst; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd100_solicitud.cod_tipo_inst IS 'Código tipo de Institución';


--
-- TOC entry 5203 (class 0 OID 0)
-- Dependencies: 4167
-- Name: COLUMN shd100_solicitud.cod_inst; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd100_solicitud.cod_inst IS 'Código de la Institución';


--
-- TOC entry 5204 (class 0 OID 0)
-- Dependencies: 4167
-- Name: COLUMN shd100_solicitud.cod_dep; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd100_solicitud.cod_dep IS 'Código de la dependencia';


--
-- TOC entry 5205 (class 0 OID 0)
-- Dependencies: 4167
-- Name: COLUMN shd100_solicitud.numero_solicitud; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd100_solicitud.numero_solicitud IS 'Número de solicitud de licencia de patente de industria y comercio';


--
-- TOC entry 5206 (class 0 OID 0)
-- Dependencies: 4167
-- Name: COLUMN shd100_solicitud.fecha_solicitud; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd100_solicitud.fecha_solicitud IS 'Fecha de la solicitud';


--
-- TOC entry 5207 (class 0 OID 0)
-- Dependencies: 4167
-- Name: COLUMN shd100_solicitud.rif_cedula; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd100_solicitud.rif_cedula IS 'Rif o Cédula de Identidad';


--
-- TOC entry 5208 (class 0 OID 0)
-- Dependencies: 4167
-- Name: COLUMN shd100_solicitud.numero_ficha_catastral; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd100_solicitud.numero_ficha_catastral IS 'Número de ficha catastral';


--
-- TOC entry 5209 (class 0 OID 0)
-- Dependencies: 4167
-- Name: COLUMN shd100_solicitud.capital; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd100_solicitud.capital IS 'Capital de la empresa';


--
-- TOC entry 5210 (class 0 OID 0)
-- Dependencies: 4167
-- Name: COLUMN shd100_solicitud.tipo_establecimiento; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd100_solicitud.tipo_establecimiento IS 'Tipo de establecimiento
1.- Industrial
2.- Comercial
3.- Similar indole';


--
-- TOC entry 5211 (class 0 OID 0)
-- Dependencies: 4167
-- Name: COLUMN shd100_solicitud.tipo_local; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd100_solicitud.tipo_local IS 'Tipo de local
1.- Un inmueble
2.- Mas de un Inmueble
3.- Parte de un inmueble';


--
-- TOC entry 5212 (class 0 OID 0)
-- Dependencies: 4167
-- Name: COLUMN shd100_solicitud.nacionalidad; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd100_solicitud.nacionalidad IS 'Nacionalidad
1.- Venezolana
2.- Extranjera';


--
-- TOC entry 5213 (class 0 OID 0)
-- Dependencies: 4167
-- Name: COLUMN shd100_solicitud.cedula_identidad; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd100_solicitud.cedula_identidad IS 'Cédula de Identidad';


--
-- TOC entry 5214 (class 0 OID 0)
-- Dependencies: 4167
-- Name: COLUMN shd100_solicitud.nombres_apellidos; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd100_solicitud.nombres_apellidos IS 'Nombres y Apellidos';


--
-- TOC entry 5215 (class 0 OID 0)
-- Dependencies: 4167
-- Name: COLUMN shd100_solicitud.cod_pais; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd100_solicitud.cod_pais IS 'Código del pais';


--
-- TOC entry 5216 (class 0 OID 0)
-- Dependencies: 4167
-- Name: COLUMN shd100_solicitud.cod_estado; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd100_solicitud.cod_estado IS 'Código del estado';


--
-- TOC entry 5217 (class 0 OID 0)
-- Dependencies: 4167
-- Name: COLUMN shd100_solicitud.cod_municipio; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd100_solicitud.cod_municipio IS 'Código del municipio';


--
-- TOC entry 5218 (class 0 OID 0)
-- Dependencies: 4167
-- Name: COLUMN shd100_solicitud.cod_parroquia; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd100_solicitud.cod_parroquia IS 'Código de la parroquia';


--
-- TOC entry 5219 (class 0 OID 0)
-- Dependencies: 4167
-- Name: COLUMN shd100_solicitud.cod_centro; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd100_solicitud.cod_centro IS 'Código del centro poblado';


--
-- TOC entry 5220 (class 0 OID 0)
-- Dependencies: 4167
-- Name: COLUMN shd100_solicitud.cod_vialidad; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd100_solicitud.cod_vialidad IS 'Código de calle o avenida';


--
-- TOC entry 5221 (class 0 OID 0)
-- Dependencies: 4167
-- Name: COLUMN shd100_solicitud.cod_vereda; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd100_solicitud.cod_vereda IS 'Código de la vereda o edificio';


--
-- TOC entry 5222 (class 0 OID 0)
-- Dependencies: 4167
-- Name: COLUMN shd100_solicitud.numero_casa_local; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd100_solicitud.numero_casa_local IS 'Número de la casa o local';


--
-- TOC entry 5223 (class 0 OID 0)
-- Dependencies: 4167
-- Name: COLUMN shd100_solicitud.telefonos_fijos; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd100_solicitud.telefonos_fijos IS 'Teléfonos fijos';


--
-- TOC entry 5224 (class 0 OID 0)
-- Dependencies: 4167
-- Name: COLUMN shd100_solicitud.telefonos_celulares; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd100_solicitud.telefonos_celulares IS 'Teléfonos celulares';


--
-- TOC entry 5225 (class 0 OID 0)
-- Dependencies: 4167
-- Name: COLUMN shd100_solicitud.correo_electronico; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd100_solicitud.correo_electronico IS 'Correo electrónico';


--
-- TOC entry 5226 (class 0 OID 0)
-- Dependencies: 4167
-- Name: COLUMN shd100_solicitud.fecha_inicio_const; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd100_solicitud.fecha_inicio_const IS 'Fecha de Inicio de la constitución';


--
-- TOC entry 5227 (class 0 OID 0)
-- Dependencies: 4167
-- Name: COLUMN shd100_solicitud.fecha_cierre_const; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd100_solicitud.fecha_cierre_const IS 'Fecha de cierre de la constitución';


--
-- TOC entry 5228 (class 0 OID 0)
-- Dependencies: 4167
-- Name: COLUMN shd100_solicitud.fecha_inicio_econo; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd100_solicitud.fecha_inicio_econo IS 'Fecha de inicio economico';


--
-- TOC entry 5229 (class 0 OID 0)
-- Dependencies: 4167
-- Name: COLUMN shd100_solicitud.fecha_cierre_economico; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd100_solicitud.fecha_cierre_economico IS 'fecha de cierre economico';


--
-- TOC entry 5230 (class 0 OID 0)
-- Dependencies: 4167
-- Name: COLUMN shd100_solicitud.registro_mercantil; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd100_solicitud.registro_mercantil IS 'Registro mercantil de la empresa';


--
-- TOC entry 5231 (class 0 OID 0)
-- Dependencies: 4167
-- Name: COLUMN shd100_solicitud.tiene_sucursal; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd100_solicitud.tiene_sucursal IS 'Tiene sucursal ?
1.- Si
2.- No';


--
-- TOC entry 5232 (class 0 OID 0)
-- Dependencies: 4167
-- Name: COLUMN shd100_solicitud.es_fabricante; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd100_solicitud.es_fabricante IS 'Es fabricante de algún producto
1.- Si
2.- No';


--
-- TOC entry 5233 (class 0 OID 0)
-- Dependencies: 4167
-- Name: COLUMN shd100_solicitud.numero_empleado; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd100_solicitud.numero_empleado IS 'Número de empleados
';


--
-- TOC entry 5234 (class 0 OID 0)
-- Dependencies: 4167
-- Name: COLUMN shd100_solicitud.numero_obreros; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd100_solicitud.numero_obreros IS 'Número de obreros';


--
-- TOC entry 5235 (class 0 OID 0)
-- Dependencies: 4167
-- Name: COLUMN shd100_solicitud.tilde_reg_mercantil; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd100_solicitud.tilde_reg_mercantil IS 'Registro mercantil';


--
-- TOC entry 5236 (class 0 OID 0)
-- Dependencies: 4167
-- Name: COLUMN shd100_solicitud.tilde_fotoco_ci; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd100_solicitud.tilde_fotoco_ci IS 'Fotocopia de la cédula de identidad';


--
-- TOC entry 5237 (class 0 OID 0)
-- Dependencies: 4167
-- Name: COLUMN shd100_solicitud.tilde_acta_const; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd100_solicitud.tilde_acta_const IS 'Acta constitutiva';


--
-- TOC entry 5238 (class 0 OID 0)
-- Dependencies: 4167
-- Name: COLUMN shd100_solicitud.tilde_uso_conforme; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd100_solicitud.tilde_uso_conforme IS 'Uso conforme';


--
-- TOC entry 5239 (class 0 OID 0)
-- Dependencies: 4167
-- Name: COLUMN shd100_solicitud.tilde_croquis; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd100_solicitud.tilde_croquis IS 'Croquis elaborado por el contribuyente';


--
-- TOC entry 5240 (class 0 OID 0)
-- Dependencies: 4167
-- Name: COLUMN shd100_solicitud.tilde_bomberos; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd100_solicitud.tilde_bomberos IS 'Autorización de los bomberos';


--
-- TOC entry 5241 (class 0 OID 0)
-- Dependencies: 4167
-- Name: COLUMN shd100_solicitud.tilde_rif; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd100_solicitud.tilde_rif IS 'Certificado del R.I.F.';


--
-- TOC entry 5242 (class 0 OID 0)
-- Dependencies: 4167
-- Name: COLUMN shd100_solicitud.tilde_solvencia; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd100_solicitud.tilde_solvencia IS 'Solvencia de propiedad inmobiliaria';


--
-- TOC entry 5243 (class 0 OID 0)
-- Dependencies: 4167
-- Name: COLUMN shd100_solicitud.tilde_concejo; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd100_solicitud.tilde_concejo IS 'Constancia del concejo comunal';


--
-- TOC entry 5244 (class 0 OID 0)
-- Dependencies: 4167
-- Name: COLUMN shd100_solicitud.tilde_recibo; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd100_solicitud.tilde_recibo IS 'Recibo de cancelación de la solvencia';


--
-- TOC entry 5245 (class 0 OID 0)
-- Dependencies: 4167
-- Name: COLUMN shd100_solicitud.tilde_planilla; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd100_solicitud.tilde_planilla IS 'Planilla de solicitud';


--
-- TOC entry 5246 (class 0 OID 0)
-- Dependencies: 4167
-- Name: COLUMN shd100_solicitud.tilde_permiso; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd100_solicitud.tilde_permiso IS 'Permiso otorgado por organismo nacionales';


--
-- TOC entry 5247 (class 0 OID 0)
-- Dependencies: 4167
-- Name: COLUMN shd100_solicitud.numero_patente; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd100_solicitud.numero_patente IS 'Número de patente';


--
-- TOC entry 4168 (class 1259 OID 502134)
-- Dependencies: 3
-- Name: shd100_solicitud_actividades; Type: TABLE; Schema: public; Owner: sisap; Tablespace:
--

CREATE TABLE shd100_solicitud_actividades (
    cod_presi integer NOT NULL,
    cod_entidad integer NOT NULL,
    cod_tipo_inst integer NOT NULL,
    cod_inst integer NOT NULL,
    cod_dep integer NOT NULL,
    numero_solicitud character varying(20) NOT NULL,
    cod_actividad character varying(20) NOT NULL
);


ALTER TABLE public.shd100_solicitud_actividades OWNER TO sisap;

--
-- TOC entry 5248 (class 0 OID 0)
-- Dependencies: 4168
-- Name: TABLE shd100_solicitud_actividades; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON TABLE shd100_solicitud_actividades IS 'Registra las actiividades economicas a la cual se dedica';


--
-- TOC entry 5249 (class 0 OID 0)
-- Dependencies: 4168
-- Name: COLUMN shd100_solicitud_actividades.cod_presi; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd100_solicitud_actividades.cod_presi IS 'Código de la presidencia';


--
-- TOC entry 5250 (class 0 OID 0)
-- Dependencies: 4168
-- Name: COLUMN shd100_solicitud_actividades.cod_entidad; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd100_solicitud_actividades.cod_entidad IS 'Código de la entidad';


--
-- TOC entry 5251 (class 0 OID 0)
-- Dependencies: 4168
-- Name: COLUMN shd100_solicitud_actividades.cod_tipo_inst; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd100_solicitud_actividades.cod_tipo_inst IS 'Código tipo de Institución';


--
-- TOC entry 5252 (class 0 OID 0)
-- Dependencies: 4168
-- Name: COLUMN shd100_solicitud_actividades.cod_inst; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd100_solicitud_actividades.cod_inst IS 'Código de la Institución';


--
-- TOC entry 5253 (class 0 OID 0)
-- Dependencies: 4168
-- Name: COLUMN shd100_solicitud_actividades.cod_dep; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd100_solicitud_actividades.cod_dep IS 'Código de la dependencia';


--
-- TOC entry 5254 (class 0 OID 0)
-- Dependencies: 4168
-- Name: COLUMN shd100_solicitud_actividades.numero_solicitud; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd100_solicitud_actividades.numero_solicitud IS 'Número de solicitud';


--
-- TOC entry 5255 (class 0 OID 0)
-- Dependencies: 4168
-- Name: COLUMN shd100_solicitud_actividades.cod_actividad; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd100_solicitud_actividades.cod_actividad IS 'Código de actividad';


--
-- TOC entry 4169 (class 1259 OID 502139)
-- Dependencies: 3
-- Name: shd200_vehiculos; Type: TABLE; Schema: public; Owner: sisap; Tablespace:
--

CREATE TABLE shd200_vehiculos (
    cod_presi integer NOT NULL,
    cod_entidad integer NOT NULL,
    cod_tipo_inst integer NOT NULL,
    cod_inst integer NOT NULL,
    cod_dep integer NOT NULL,
    rif_cedula character varying(20) NOT NULL,
    placa_vehiculo character varying(10) NOT NULL,
    fecha_registro date NOT NULL,
    cod_marca integer NOT NULL,
    cod_modelo integer NOT NULL,
    cod_color integer NOT NULL,
    cod_clase integer NOT NULL,
    cod_tipo integer NOT NULL,
    cod_uso integer NOT NULL,
    serial_carroceria character varying(25),
    serial_motor character varying(25),
    ano_adquisicion integer NOT NULL,
    valor_vehiculo numeric(26,2) NOT NULL,
    fecha_adquisicion date NOT NULL,
    cod_clasificacion character varying(10) NOT NULL,
    frecuencia_pago integer NOT NULL,
    monto_mensual numeric(26,2) NOT NULL,
    pago_todo integer NOT NULL,
    suspendido integer NOT NULL,
    rif_ci_cobrador character varying(20) NOT NULL,
    ultimo_ano_facturado integer,
    ultimo_mes_facturado integer
);


ALTER TABLE public.shd200_vehiculos OWNER TO sisap;

--
-- TOC entry 5256 (class 0 OID 0)
-- Dependencies: 4169
-- Name: TABLE shd200_vehiculos; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON TABLE shd200_vehiculos IS 'Registro vehiculos de los contribuyentes';


--
-- TOC entry 5257 (class 0 OID 0)
-- Dependencies: 4169
-- Name: COLUMN shd200_vehiculos.cod_presi; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd200_vehiculos.cod_presi IS 'Código de la presidencia';


--
-- TOC entry 5258 (class 0 OID 0)
-- Dependencies: 4169
-- Name: COLUMN shd200_vehiculos.cod_entidad; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd200_vehiculos.cod_entidad IS 'Código de la entidad';


--
-- TOC entry 5259 (class 0 OID 0)
-- Dependencies: 4169
-- Name: COLUMN shd200_vehiculos.cod_tipo_inst; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd200_vehiculos.cod_tipo_inst IS 'Código tipo de Institución';


--
-- TOC entry 5260 (class 0 OID 0)
-- Dependencies: 4169
-- Name: COLUMN shd200_vehiculos.cod_inst; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd200_vehiculos.cod_inst IS 'Código de la Institución';


--
-- TOC entry 5261 (class 0 OID 0)
-- Dependencies: 4169
-- Name: COLUMN shd200_vehiculos.cod_dep; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd200_vehiculos.cod_dep IS 'Código de la dependencia';


--
-- TOC entry 5262 (class 0 OID 0)
-- Dependencies: 4169
-- Name: COLUMN shd200_vehiculos.rif_cedula; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd200_vehiculos.rif_cedula IS 'Rif o Cédula de identidad del contribuyente';


--
-- TOC entry 5263 (class 0 OID 0)
-- Dependencies: 4169
-- Name: COLUMN shd200_vehiculos.placa_vehiculo; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd200_vehiculos.placa_vehiculo IS 'Placa del Vehículo';


--
-- TOC entry 5264 (class 0 OID 0)
-- Dependencies: 4169
-- Name: COLUMN shd200_vehiculos.fecha_registro; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd200_vehiculos.fecha_registro IS 'Fecha de registro';


--
-- TOC entry 5265 (class 0 OID 0)
-- Dependencies: 4169
-- Name: COLUMN shd200_vehiculos.cod_marca; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd200_vehiculos.cod_marca IS 'Código de la marca del vehículo';


--
-- TOC entry 5266 (class 0 OID 0)
-- Dependencies: 4169
-- Name: COLUMN shd200_vehiculos.cod_modelo; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd200_vehiculos.cod_modelo IS 'Código del modelo del vehículo';


--
-- TOC entry 5267 (class 0 OID 0)
-- Dependencies: 4169
-- Name: COLUMN shd200_vehiculos.cod_color; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd200_vehiculos.cod_color IS 'Código del color del vehículo';


--
-- TOC entry 5268 (class 0 OID 0)
-- Dependencies: 4169
-- Name: COLUMN shd200_vehiculos.cod_clase; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd200_vehiculos.cod_clase IS 'Código de la clase de vehículo';


--
-- TOC entry 5269 (class 0 OID 0)
-- Dependencies: 4169
-- Name: COLUMN shd200_vehiculos.cod_tipo; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd200_vehiculos.cod_tipo IS 'Código tipo del vehículo';


--
-- TOC entry 5270 (class 0 OID 0)
-- Dependencies: 4169
-- Name: COLUMN shd200_vehiculos.cod_uso; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd200_vehiculos.cod_uso IS 'Código de uso de vehículo';


--
-- TOC entry 5271 (class 0 OID 0)
-- Dependencies: 4169
-- Name: COLUMN shd200_vehiculos.serial_carroceria; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd200_vehiculos.serial_carroceria IS 'Serial de carroceria';


--
-- TOC entry 5272 (class 0 OID 0)
-- Dependencies: 4169
-- Name: COLUMN shd200_vehiculos.serial_motor; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd200_vehiculos.serial_motor IS 'Serial del motor';


--
-- TOC entry 5273 (class 0 OID 0)
-- Dependencies: 4169
-- Name: COLUMN shd200_vehiculos.ano_adquisicion; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd200_vehiculos.ano_adquisicion IS 'Año de adquisición';


--
-- TOC entry 5274 (class 0 OID 0)
-- Dependencies: 4169
-- Name: COLUMN shd200_vehiculos.valor_vehiculo; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd200_vehiculos.valor_vehiculo IS 'Valor del vehículo';


--
-- TOC entry 5275 (class 0 OID 0)
-- Dependencies: 4169
-- Name: COLUMN shd200_vehiculos.fecha_adquisicion; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd200_vehiculos.fecha_adquisicion IS 'Fecha de adquisición del vehículo';


--
-- TOC entry 5276 (class 0 OID 0)
-- Dependencies: 4169
-- Name: COLUMN shd200_vehiculos.cod_clasificacion; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd200_vehiculos.cod_clasificacion IS 'Código de la clasificación automotríz';


--
-- TOC entry 5277 (class 0 OID 0)
-- Dependencies: 4169
-- Name: COLUMN shd200_vehiculos.frecuencia_pago; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd200_vehiculos.frecuencia_pago IS 'Frecuencia de pago
1.- Mensual
2.- Bimestral
3.- Trimestral
4.- Semestral
5.- Anual';


--
-- TOC entry 5278 (class 0 OID 0)
-- Dependencies: 4169
-- Name: COLUMN shd200_vehiculos.monto_mensual; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd200_vehiculos.monto_mensual IS 'Monto pago mensual';


--
-- TOC entry 5279 (class 0 OID 0)
-- Dependencies: 4169
-- Name: COLUMN shd200_vehiculos.pago_todo; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd200_vehiculos.pago_todo IS 'Pago todo el año ?
1.- Si
2.- No
Por defecto "2"';


--
-- TOC entry 5280 (class 0 OID 0)
-- Dependencies: 4169
-- Name: COLUMN shd200_vehiculos.suspendido; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd200_vehiculos.suspendido IS 'Contribuyente suspendido ?
1.- Si
2.- No
Por defecto "2"';


--
-- TOC entry 5281 (class 0 OID 0)
-- Dependencies: 4169
-- Name: COLUMN shd200_vehiculos.rif_ci_cobrador; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd200_vehiculos.rif_ci_cobrador IS 'Rif o Cédula de identidad del cobrador';


--
-- TOC entry 5282 (class 0 OID 0)
-- Dependencies: 4169
-- Name: COLUMN shd200_vehiculos.ultimo_ano_facturado; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd200_vehiculos.ultimo_ano_facturado IS 'Ultimo año facturado';


--
-- TOC entry 5283 (class 0 OID 0)
-- Dependencies: 4169
-- Name: COLUMN shd200_vehiculos.ultimo_mes_facturado; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd200_vehiculos.ultimo_mes_facturado IS 'Ultimo mes facturado';


--
-- TOC entry 4171 (class 1259 OID 502146)
-- Dependencies: 3
-- Name: shd200_vehiculos_clases; Type: TABLE; Schema: public; Owner: sisap; Tablespace:
--

CREATE TABLE shd200_vehiculos_clases (
    codigo_clase integer NOT NULL,
    denominacion character varying(100) NOT NULL
);


ALTER TABLE public.shd200_vehiculos_clases OWNER TO sisap;

--
-- TOC entry 5284 (class 0 OID 0)
-- Dependencies: 4171
-- Name: TABLE shd200_vehiculos_clases; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON TABLE shd200_vehiculos_clases IS 'Registro de la clase de Vehículos';


--
-- TOC entry 5285 (class 0 OID 0)
-- Dependencies: 4171
-- Name: COLUMN shd200_vehiculos_clases.codigo_clase; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd200_vehiculos_clases.codigo_clase IS 'Código de la clase';


--
-- TOC entry 5286 (class 0 OID 0)
-- Dependencies: 4171
-- Name: COLUMN shd200_vehiculos_clases.denominacion; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd200_vehiculos_clases.denominacion IS 'Denominación de la clase';


--
-- TOC entry 4170 (class 1259 OID 502144)
-- Dependencies: 4171 3
-- Name: shd200_vehiculos_clases_codigo_clase_seq; Type: SEQUENCE; Schema: public; Owner: sisap
--

CREATE SEQUENCE shd200_vehiculos_clases_codigo_clase_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.shd200_vehiculos_clases_codigo_clase_seq OWNER TO sisap;

--
-- TOC entry 5287 (class 0 OID 0)
-- Dependencies: 4170
-- Name: shd200_vehiculos_clases_codigo_clase_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: sisap
--

ALTER SEQUENCE shd200_vehiculos_clases_codigo_clase_seq OWNED BY shd200_vehiculos_clases.codigo_clase;


--
-- TOC entry 5288 (class 0 OID 0)
-- Dependencies: 4170
-- Name: shd200_vehiculos_clases_codigo_clase_seq; Type: SEQUENCE SET; Schema: public; Owner: sisap
--

SELECT pg_catalog.setval('shd200_vehiculos_clases_codigo_clase_seq', 1, false);


--
-- TOC entry 4172 (class 1259 OID 502152)
-- Dependencies: 3
-- Name: shd200_vehiculos_clasificacion; Type: TABLE; Schema: public; Owner: sisap; Tablespace:
--

CREATE TABLE shd200_vehiculos_clasificacion (
    cod_presi integer NOT NULL,
    cod_entidad integer NOT NULL,
    cod_tipo_inst integer NOT NULL,
    cod_inst integer NOT NULL,
    cod_dep integer NOT NULL,
    cod_clasificacion character varying(10) NOT NULL,
    porcentaje numeric(5,2) NOT NULL,
    monto_anual numeric(26,2) NOT NULL,
    denominacion character varying(200)
);


ALTER TABLE public.shd200_vehiculos_clasificacion OWNER TO sisap;

--
-- TOC entry 5289 (class 0 OID 0)
-- Dependencies: 4172
-- Name: TABLE shd200_vehiculos_clasificacion; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON TABLE shd200_vehiculos_clasificacion IS 'Registra la clasificación automotriz de acuerdo a la ordenanza';


--
-- TOC entry 5290 (class 0 OID 0)
-- Dependencies: 4172
-- Name: COLUMN shd200_vehiculos_clasificacion.cod_presi; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd200_vehiculos_clasificacion.cod_presi IS 'Código de la presidencia';


--
-- TOC entry 5291 (class 0 OID 0)
-- Dependencies: 4172
-- Name: COLUMN shd200_vehiculos_clasificacion.cod_entidad; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd200_vehiculos_clasificacion.cod_entidad IS 'Código de la entidad';


--
-- TOC entry 5292 (class 0 OID 0)
-- Dependencies: 4172
-- Name: COLUMN shd200_vehiculos_clasificacion.cod_tipo_inst; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd200_vehiculos_clasificacion.cod_tipo_inst IS 'Código tipo de la Institución';


--
-- TOC entry 5293 (class 0 OID 0)
-- Dependencies: 4172
-- Name: COLUMN shd200_vehiculos_clasificacion.cod_inst; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd200_vehiculos_clasificacion.cod_inst IS 'Código de la Institución';


--
-- TOC entry 5294 (class 0 OID 0)
-- Dependencies: 4172
-- Name: COLUMN shd200_vehiculos_clasificacion.cod_dep; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd200_vehiculos_clasificacion.cod_dep IS 'Código de la dependencia';


--
-- TOC entry 5295 (class 0 OID 0)
-- Dependencies: 4172
-- Name: COLUMN shd200_vehiculos_clasificacion.cod_clasificacion; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd200_vehiculos_clasificacion.cod_clasificacion IS 'Código de la clasificación automotríz';


--
-- TOC entry 5296 (class 0 OID 0)
-- Dependencies: 4172
-- Name: COLUMN shd200_vehiculos_clasificacion.porcentaje; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd200_vehiculos_clasificacion.porcentaje IS 'Porcentaje a aplicar';


--
-- TOC entry 5297 (class 0 OID 0)
-- Dependencies: 4172
-- Name: COLUMN shd200_vehiculos_clasificacion.monto_anual; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd200_vehiculos_clasificacion.monto_anual IS 'Monto minimo anual';


--
-- TOC entry 4174 (class 1259 OID 502159)
-- Dependencies: 3
-- Name: shd200_vehiculos_colores; Type: TABLE; Schema: public; Owner: sisap; Tablespace:
--

CREATE TABLE shd200_vehiculos_colores (
    codigo_color integer NOT NULL,
    denominacion character varying(100) NOT NULL
);


ALTER TABLE public.shd200_vehiculos_colores OWNER TO sisap;

--
-- TOC entry 5298 (class 0 OID 0)
-- Dependencies: 4174
-- Name: TABLE shd200_vehiculos_colores; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON TABLE shd200_vehiculos_colores IS 'Registro de color de los Vehículos';


--
-- TOC entry 5299 (class 0 OID 0)
-- Dependencies: 4174
-- Name: COLUMN shd200_vehiculos_colores.codigo_color; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd200_vehiculos_colores.codigo_color IS 'Código del color';


--
-- TOC entry 5300 (class 0 OID 0)
-- Dependencies: 4174
-- Name: COLUMN shd200_vehiculos_colores.denominacion; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd200_vehiculos_colores.denominacion IS 'Denominación del color';


--
-- TOC entry 4173 (class 1259 OID 502157)
-- Dependencies: 4174 3
-- Name: shd200_vehiculos_colores_codigo_color_seq; Type: SEQUENCE; Schema: public; Owner: sisap
--

CREATE SEQUENCE shd200_vehiculos_colores_codigo_color_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.shd200_vehiculos_colores_codigo_color_seq OWNER TO sisap;

--
-- TOC entry 5301 (class 0 OID 0)
-- Dependencies: 4173
-- Name: shd200_vehiculos_colores_codigo_color_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: sisap
--

ALTER SEQUENCE shd200_vehiculos_colores_codigo_color_seq OWNED BY shd200_vehiculos_colores.codigo_color;


--
-- TOC entry 5302 (class 0 OID 0)
-- Dependencies: 4173
-- Name: shd200_vehiculos_colores_codigo_color_seq; Type: SEQUENCE SET; Schema: public; Owner: sisap
--

SELECT pg_catalog.setval('shd200_vehiculos_colores_codigo_color_seq', 1, false);


--
-- TOC entry 4176 (class 1259 OID 502167)
-- Dependencies: 3
-- Name: shd200_vehiculos_marcas; Type: TABLE; Schema: public; Owner: sisap; Tablespace:
--

CREATE TABLE shd200_vehiculos_marcas (
    codigo_marca integer NOT NULL,
    denominacion character varying(100) NOT NULL
);


ALTER TABLE public.shd200_vehiculos_marcas OWNER TO sisap;

--
-- TOC entry 5303 (class 0 OID 0)
-- Dependencies: 4176
-- Name: TABLE shd200_vehiculos_marcas; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON TABLE shd200_vehiculos_marcas IS 'Registro de marcas de Vehículos';


--
-- TOC entry 5304 (class 0 OID 0)
-- Dependencies: 4176
-- Name: COLUMN shd200_vehiculos_marcas.codigo_marca; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd200_vehiculos_marcas.codigo_marca IS 'Código de marcas';


--
-- TOC entry 5305 (class 0 OID 0)
-- Dependencies: 4176
-- Name: COLUMN shd200_vehiculos_marcas.denominacion; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd200_vehiculos_marcas.denominacion IS 'Denominación de la marca';


--
-- TOC entry 4175 (class 1259 OID 502165)
-- Dependencies: 4176 3
-- Name: shd200_vehiculos_marcas_codigo_marca_seq; Type: SEQUENCE; Schema: public; Owner: sisap
--

CREATE SEQUENCE shd200_vehiculos_marcas_codigo_marca_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.shd200_vehiculos_marcas_codigo_marca_seq OWNER TO sisap;

--
-- TOC entry 5306 (class 0 OID 0)
-- Dependencies: 4175
-- Name: shd200_vehiculos_marcas_codigo_marca_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: sisap
--

ALTER SEQUENCE shd200_vehiculos_marcas_codigo_marca_seq OWNED BY shd200_vehiculos_marcas.codigo_marca;


--
-- TOC entry 5307 (class 0 OID 0)
-- Dependencies: 4175
-- Name: shd200_vehiculos_marcas_codigo_marca_seq; Type: SEQUENCE SET; Schema: public; Owner: sisap
--

SELECT pg_catalog.setval('shd200_vehiculos_marcas_codigo_marca_seq', 1, false);


--
-- TOC entry 4178 (class 1259 OID 502175)
-- Dependencies: 3
-- Name: shd200_vehiculos_modelos; Type: TABLE; Schema: public; Owner: sisap; Tablespace:
--

CREATE TABLE shd200_vehiculos_modelos (
    codigo_modelo integer NOT NULL,
    denominacion character varying(100) NOT NULL
);


ALTER TABLE public.shd200_vehiculos_modelos OWNER TO sisap;

--
-- TOC entry 5308 (class 0 OID 0)
-- Dependencies: 4178
-- Name: TABLE shd200_vehiculos_modelos; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON TABLE shd200_vehiculos_modelos IS 'Registro de modelos de Vehículos';


--
-- TOC entry 5309 (class 0 OID 0)
-- Dependencies: 4178
-- Name: COLUMN shd200_vehiculos_modelos.codigo_modelo; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd200_vehiculos_modelos.codigo_modelo IS 'Código del modelo';


--
-- TOC entry 5310 (class 0 OID 0)
-- Dependencies: 4178
-- Name: COLUMN shd200_vehiculos_modelos.denominacion; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd200_vehiculos_modelos.denominacion IS 'Denominación del modelo';


--
-- TOC entry 4177 (class 1259 OID 502173)
-- Dependencies: 3 4178
-- Name: shd200_vehiculos_modelos_codigo_modelo_seq; Type: SEQUENCE; Schema: public; Owner: sisap
--

CREATE SEQUENCE shd200_vehiculos_modelos_codigo_modelo_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.shd200_vehiculos_modelos_codigo_modelo_seq OWNER TO sisap;

--
-- TOC entry 5311 (class 0 OID 0)
-- Dependencies: 4177
-- Name: shd200_vehiculos_modelos_codigo_modelo_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: sisap
--

ALTER SEQUENCE shd200_vehiculos_modelos_codigo_modelo_seq OWNED BY shd200_vehiculos_modelos.codigo_modelo;


--
-- TOC entry 5312 (class 0 OID 0)
-- Dependencies: 4177
-- Name: shd200_vehiculos_modelos_codigo_modelo_seq; Type: SEQUENCE SET; Schema: public; Owner: sisap
--

SELECT pg_catalog.setval('shd200_vehiculos_modelos_codigo_modelo_seq', 1, false);


--
-- TOC entry 4180 (class 1259 OID 502183)
-- Dependencies: 3
-- Name: shd200_vehiculos_tipos; Type: TABLE; Schema: public; Owner: sisap; Tablespace:
--

CREATE TABLE shd200_vehiculos_tipos (
    codigo_tipo integer NOT NULL,
    denominacion character varying(100) NOT NULL
);


ALTER TABLE public.shd200_vehiculos_tipos OWNER TO sisap;

--
-- TOC entry 5313 (class 0 OID 0)
-- Dependencies: 4180
-- Name: TABLE shd200_vehiculos_tipos; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON TABLE shd200_vehiculos_tipos IS 'Registro del de Vehículos';


--
-- TOC entry 5314 (class 0 OID 0)
-- Dependencies: 4180
-- Name: COLUMN shd200_vehiculos_tipos.codigo_tipo; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd200_vehiculos_tipos.codigo_tipo IS 'Código del tipo de Vehículo';


--
-- TOC entry 5315 (class 0 OID 0)
-- Dependencies: 4180
-- Name: COLUMN shd200_vehiculos_tipos.denominacion; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd200_vehiculos_tipos.denominacion IS 'Denominación del tipo de Vehículo';


--
-- TOC entry 4179 (class 1259 OID 502181)
-- Dependencies: 4180 3
-- Name: shd200_vehiculos_tipos_codigo_tipo_seq; Type: SEQUENCE; Schema: public; Owner: sisap
--

CREATE SEQUENCE shd200_vehiculos_tipos_codigo_tipo_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.shd200_vehiculos_tipos_codigo_tipo_seq OWNER TO sisap;

--
-- TOC entry 5316 (class 0 OID 0)
-- Dependencies: 4179
-- Name: shd200_vehiculos_tipos_codigo_tipo_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: sisap
--

ALTER SEQUENCE shd200_vehiculos_tipos_codigo_tipo_seq OWNED BY shd200_vehiculos_tipos.codigo_tipo;


--
-- TOC entry 5317 (class 0 OID 0)
-- Dependencies: 4179
-- Name: shd200_vehiculos_tipos_codigo_tipo_seq; Type: SEQUENCE SET; Schema: public; Owner: sisap
--

SELECT pg_catalog.setval('shd200_vehiculos_tipos_codigo_tipo_seq', 1, false);


--
-- TOC entry 4182 (class 1259 OID 502191)
-- Dependencies: 3
-- Name: shd200_vehiculos_usos; Type: TABLE; Schema: public; Owner: sisap; Tablespace:
--

CREATE TABLE shd200_vehiculos_usos (
    codigo_uso integer NOT NULL,
    denominacion character varying(100) NOT NULL
);


ALTER TABLE public.shd200_vehiculos_usos OWNER TO sisap;

--
-- TOC entry 5318 (class 0 OID 0)
-- Dependencies: 4182
-- Name: TABLE shd200_vehiculos_usos; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON TABLE shd200_vehiculos_usos IS 'Registro del uso Vehículos';


--
-- TOC entry 5319 (class 0 OID 0)
-- Dependencies: 4182
-- Name: COLUMN shd200_vehiculos_usos.codigo_uso; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd200_vehiculos_usos.codigo_uso IS 'Código del uso del Vehículo';


--
-- TOC entry 5320 (class 0 OID 0)
-- Dependencies: 4182
-- Name: COLUMN shd200_vehiculos_usos.denominacion; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd200_vehiculos_usos.denominacion IS 'Denominación del uso del Vehículo';


--
-- TOC entry 4181 (class 1259 OID 502189)
-- Dependencies: 4182 3
-- Name: shd200_vehiculos_usos_codigo_uso_seq; Type: SEQUENCE; Schema: public; Owner: sisap
--

CREATE SEQUENCE shd200_vehiculos_usos_codigo_uso_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.shd200_vehiculos_usos_codigo_uso_seq OWNER TO sisap;

--
-- TOC entry 5321 (class 0 OID 0)
-- Dependencies: 4181
-- Name: shd200_vehiculos_usos_codigo_uso_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: sisap
--

ALTER SEQUENCE shd200_vehiculos_usos_codigo_uso_seq OWNED BY shd200_vehiculos_usos.codigo_uso;


--
-- TOC entry 5322 (class 0 OID 0)
-- Dependencies: 4181
-- Name: shd200_vehiculos_usos_codigo_uso_seq; Type: SEQUENCE SET; Schema: public; Owner: sisap
--

SELECT pg_catalog.setval('shd200_vehiculos_usos_codigo_uso_seq', 1, false);


--
-- TOC entry 4183 (class 1259 OID 502197)
-- Dependencies: 3
-- Name: shd300_detalles_adicional; Type: TABLE; Schema: public; Owner: sisap; Tablespace:
--

CREATE TABLE shd300_detalles_adicional (
    cod_presi integer NOT NULL,
    cod_entidad integer NOT NULL,
    cod_tipo_inst integer NOT NULL,
    cod_inst integer NOT NULL,
    cod_dep integer NOT NULL,
    rif_cedula character varying(20) NOT NULL,
    cod_tipo integer NOT NULL,
    numero integer NOT NULL,
    cod_recargo integer NOT NULL,
    monto numeric(26,2) NOT NULL
);


ALTER TABLE public.shd300_detalles_adicional OWNER TO sisap;

--
-- TOC entry 5323 (class 0 OID 0)
-- Dependencies: 4183
-- Name: TABLE shd300_detalles_adicional; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON TABLE shd300_detalles_adicional IS 'Registra los montos adicionales por tipo de propaganda';


--
-- TOC entry 5324 (class 0 OID 0)
-- Dependencies: 4183
-- Name: COLUMN shd300_detalles_adicional.cod_presi; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd300_detalles_adicional.cod_presi IS 'Código de la presidencia';


--
-- TOC entry 5325 (class 0 OID 0)
-- Dependencies: 4183
-- Name: COLUMN shd300_detalles_adicional.cod_entidad; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd300_detalles_adicional.cod_entidad IS 'Código de la entidad';


--
-- TOC entry 5326 (class 0 OID 0)
-- Dependencies: 4183
-- Name: COLUMN shd300_detalles_adicional.cod_tipo_inst; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd300_detalles_adicional.cod_tipo_inst IS 'Código tipo de Institución';


--
-- TOC entry 5327 (class 0 OID 0)
-- Dependencies: 4183
-- Name: COLUMN shd300_detalles_adicional.cod_inst; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd300_detalles_adicional.cod_inst IS 'Código de la Institución';


--
-- TOC entry 5328 (class 0 OID 0)
-- Dependencies: 4183
-- Name: COLUMN shd300_detalles_adicional.cod_dep; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd300_detalles_adicional.cod_dep IS 'Código de la dependencia';


--
-- TOC entry 5329 (class 0 OID 0)
-- Dependencies: 4183
-- Name: COLUMN shd300_detalles_adicional.rif_cedula; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd300_detalles_adicional.rif_cedula IS 'Rif o Cédula de identidad del contribuyente';


--
-- TOC entry 5330 (class 0 OID 0)
-- Dependencies: 4183
-- Name: COLUMN shd300_detalles_adicional.cod_tipo; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd300_detalles_adicional.cod_tipo IS 'Código tipo de propaganda';


--
-- TOC entry 5331 (class 0 OID 0)
-- Dependencies: 4183
-- Name: COLUMN shd300_detalles_adicional.numero; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd300_detalles_adicional.numero IS 'Número consecutivo clave de la tabla shd300-detalles-propaganda';


--
-- TOC entry 5332 (class 0 OID 0)
-- Dependencies: 4183
-- Name: COLUMN shd300_detalles_adicional.cod_recargo; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd300_detalles_adicional.cod_recargo IS 'Código del recargo';


--
-- TOC entry 5333 (class 0 OID 0)
-- Dependencies: 4183
-- Name: COLUMN shd300_detalles_adicional.monto; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd300_detalles_adicional.monto IS 'Monto';


--
-- TOC entry 4184 (class 1259 OID 502202)
-- Dependencies: 3
-- Name: shd300_detalles_propaganda; Type: TABLE; Schema: public; Owner: sisap; Tablespace:
--

CREATE TABLE shd300_detalles_propaganda (
    cod_presi integer NOT NULL,
    cod_entidad integer NOT NULL,
    cod_tipo_inst integer NOT NULL,
    cod_inst integer NOT NULL,
    cod_dep integer NOT NULL,
    rif_cedula character varying(20) NOT NULL,
    cod_tipo integer NOT NULL,
    numero integer NOT NULL,
    largo numeric(10,3),
    alto numeric(10,3),
    area numeric(10,3),
    espesor numeric(10,3),
    cantidad numeric(10,3) NOT NULL,
    monto numeric(26,2) NOT NULL,
    monto_adicional numeric(26,2),
    monto_mensual numeric(26,2) NOT NULL,
    ubicacion text,
    fecha_registro date NOT NULL
);


ALTER TABLE public.shd300_detalles_propaganda OWNER TO sisap;

--
-- TOC entry 5334 (class 0 OID 0)
-- Dependencies: 4184
-- Name: TABLE shd300_detalles_propaganda; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON TABLE shd300_detalles_propaganda IS 'Registro de los diferentes tipos de propaganda comercial que cancela el contribuyente';


--
-- TOC entry 5335 (class 0 OID 0)
-- Dependencies: 4184
-- Name: COLUMN shd300_detalles_propaganda.cod_presi; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd300_detalles_propaganda.cod_presi IS 'Código de la presidencia';


--
-- TOC entry 5336 (class 0 OID 0)
-- Dependencies: 4184
-- Name: COLUMN shd300_detalles_propaganda.cod_entidad; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd300_detalles_propaganda.cod_entidad IS 'Código de la entidad';


--
-- TOC entry 5337 (class 0 OID 0)
-- Dependencies: 4184
-- Name: COLUMN shd300_detalles_propaganda.cod_tipo_inst; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd300_detalles_propaganda.cod_tipo_inst IS 'Código tipo de Institución';


--
-- TOC entry 5338 (class 0 OID 0)
-- Dependencies: 4184
-- Name: COLUMN shd300_detalles_propaganda.cod_inst; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd300_detalles_propaganda.cod_inst IS 'Código de la Institución';


--
-- TOC entry 5339 (class 0 OID 0)
-- Dependencies: 4184
-- Name: COLUMN shd300_detalles_propaganda.cod_dep; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd300_detalles_propaganda.cod_dep IS 'Código de la dependencia';


--
-- TOC entry 5340 (class 0 OID 0)
-- Dependencies: 4184
-- Name: COLUMN shd300_detalles_propaganda.rif_cedula; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd300_detalles_propaganda.rif_cedula IS 'Rif o Cédula de identidad del contribuyente';


--
-- TOC entry 5341 (class 0 OID 0)
-- Dependencies: 4184
-- Name: COLUMN shd300_detalles_propaganda.cod_tipo; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd300_detalles_propaganda.cod_tipo IS 'Código tipo de propaganda comercial';


--
-- TOC entry 5342 (class 0 OID 0)
-- Dependencies: 4184
-- Name: COLUMN shd300_detalles_propaganda.numero; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd300_detalles_propaganda.numero IS 'Número consecutivo para registrar varias propagandas de un solo tipo';


--
-- TOC entry 5343 (class 0 OID 0)
-- Dependencies: 4184
-- Name: COLUMN shd300_detalles_propaganda.largo; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd300_detalles_propaganda.largo IS 'Largo en metros lineales';


--
-- TOC entry 5344 (class 0 OID 0)
-- Dependencies: 4184
-- Name: COLUMN shd300_detalles_propaganda.alto; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd300_detalles_propaganda.alto IS 'Alto en metros lineales';


--
-- TOC entry 5345 (class 0 OID 0)
-- Dependencies: 4184
-- Name: COLUMN shd300_detalles_propaganda.area; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd300_detalles_propaganda.area IS 'Area en metros cuadrados';


--
-- TOC entry 5346 (class 0 OID 0)
-- Dependencies: 4184
-- Name: COLUMN shd300_detalles_propaganda.espesor; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd300_detalles_propaganda.espesor IS 'Espesor';


--
-- TOC entry 5347 (class 0 OID 0)
-- Dependencies: 4184
-- Name: COLUMN shd300_detalles_propaganda.cantidad; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd300_detalles_propaganda.cantidad IS 'Cantidad en metros m2 o unidades';


--
-- TOC entry 5348 (class 0 OID 0)
-- Dependencies: 4184
-- Name: COLUMN shd300_detalles_propaganda.monto; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd300_detalles_propaganda.monto IS 'Monto resultante de calcular la Cantidad de metros o unidades por el valor del metro cuadrado o valor en unidad';


--
-- TOC entry 5349 (class 0 OID 0)
-- Dependencies: 4184
-- Name: COLUMN shd300_detalles_propaganda.monto_adicional; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd300_detalles_propaganda.monto_adicional IS 'Monto adicional resultado de el calculo en porcentaje del monto bruto';


--
-- TOC entry 5350 (class 0 OID 0)
-- Dependencies: 4184
-- Name: COLUMN shd300_detalles_propaganda.monto_mensual; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd300_detalles_propaganda.monto_mensual IS 'Monto mensual a cobrar por este tipo de propaganda';


--
-- TOC entry 5351 (class 0 OID 0)
-- Dependencies: 4184
-- Name: COLUMN shd300_detalles_propaganda.ubicacion; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd300_detalles_propaganda.ubicacion IS 'Ubicación de la valla o cualquier otro tipo de propaganda';


--
-- TOC entry 5352 (class 0 OID 0)
-- Dependencies: 4184
-- Name: COLUMN shd300_detalles_propaganda.fecha_registro; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd300_detalles_propaganda.fecha_registro IS 'Fecha de registro';


--
-- TOC entry 4185 (class 1259 OID 502210)
-- Dependencies: 3
-- Name: shd300_propaganda; Type: TABLE; Schema: public; Owner: sisap; Tablespace:
--

CREATE TABLE shd300_propaganda (
    cod_presi integer NOT NULL,
    cod_entidad integer NOT NULL,
    cod_tipo_inst integer NOT NULL,
    cod_inst integer NOT NULL,
    cod_dep integer NOT NULL,
    rif_cedula character varying(20) NOT NULL,
    frecuencia_pago integer NOT NULL,
    monto_mensual_general numeric(26,2) NOT NULL,
    pago_todo integer NOT NULL,
    suspendido integer NOT NULL,
    rif_ci_cobrador character varying(20) NOT NULL,
    ultimo_ano_facturado integer,
    ultimo_mes_facturado integer
);


ALTER TABLE public.shd300_propaganda OWNER TO sisap;

--
-- TOC entry 5353 (class 0 OID 0)
-- Dependencies: 4185
-- Name: TABLE shd300_propaganda; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON TABLE shd300_propaganda IS 'Registra los contribuyentes de propaganda comercial, totalizando todos los tipos de propaganda';


--
-- TOC entry 5354 (class 0 OID 0)
-- Dependencies: 4185
-- Name: COLUMN shd300_propaganda.cod_presi; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd300_propaganda.cod_presi IS 'Código de la presidencia';


--
-- TOC entry 5355 (class 0 OID 0)
-- Dependencies: 4185
-- Name: COLUMN shd300_propaganda.cod_entidad; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd300_propaganda.cod_entidad IS 'Código de la entidad';


--
-- TOC entry 5356 (class 0 OID 0)
-- Dependencies: 4185
-- Name: COLUMN shd300_propaganda.cod_tipo_inst; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd300_propaganda.cod_tipo_inst IS 'Código tipo de Institución';


--
-- TOC entry 5357 (class 0 OID 0)
-- Dependencies: 4185
-- Name: COLUMN shd300_propaganda.cod_inst; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd300_propaganda.cod_inst IS 'Código de la Institución';


--
-- TOC entry 5358 (class 0 OID 0)
-- Dependencies: 4185
-- Name: COLUMN shd300_propaganda.cod_dep; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd300_propaganda.cod_dep IS 'Código de la dependencia';


--
-- TOC entry 5359 (class 0 OID 0)
-- Dependencies: 4185
-- Name: COLUMN shd300_propaganda.rif_cedula; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd300_propaganda.rif_cedula IS 'Rif o Cédula de Identidad';


--
-- TOC entry 5360 (class 0 OID 0)
-- Dependencies: 4185
-- Name: COLUMN shd300_propaganda.frecuencia_pago; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd300_propaganda.frecuencia_pago IS 'Frecuencia de pago
1.- Mensual
2.- Bimestral
3.- Trimestral
4.- Semestral
5.- Anual';


--
-- TOC entry 5361 (class 0 OID 0)
-- Dependencies: 4185
-- Name: COLUMN shd300_propaganda.monto_mensual_general; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd300_propaganda.monto_mensual_general IS 'Monto mensual general a cancelar';


--
-- TOC entry 5362 (class 0 OID 0)
-- Dependencies: 4185
-- Name: COLUMN shd300_propaganda.pago_todo; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd300_propaganda.pago_todo IS 'Pago todo ?
1.- Si
2.- No';


--
-- TOC entry 5363 (class 0 OID 0)
-- Dependencies: 4185
-- Name: COLUMN shd300_propaganda.suspendido; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd300_propaganda.suspendido IS 'Contribuyente suspendido ?
1.- Si
2.- No';


--
-- TOC entry 5364 (class 0 OID 0)
-- Dependencies: 4185
-- Name: COLUMN shd300_propaganda.rif_ci_cobrador; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd300_propaganda.rif_ci_cobrador IS 'Rif o Cédula de identidad del cobrador';


--
-- TOC entry 5365 (class 0 OID 0)
-- Dependencies: 4185
-- Name: COLUMN shd300_propaganda.ultimo_ano_facturado; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd300_propaganda.ultimo_ano_facturado IS 'Ultimo año facturado';


--
-- TOC entry 5366 (class 0 OID 0)
-- Dependencies: 4185
-- Name: COLUMN shd300_propaganda.ultimo_mes_facturado; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd300_propaganda.ultimo_mes_facturado IS 'Ultimo mes facturado';


--
-- TOC entry 4186 (class 1259 OID 502215)
-- Dependencies: 3
-- Name: shd300_recargos; Type: TABLE; Schema: public; Owner: sisap; Tablespace:
--

CREATE TABLE shd300_recargos (
    cod_presi integer NOT NULL,
    cod_entidad integer NOT NULL,
    cod_tipo_inst integer NOT NULL,
    cod_inst integer NOT NULL,
    cod_dep integer NOT NULL,
    cod_recargo integer NOT NULL,
    denominacion character varying(100) NOT NULL,
    porcentaje numeric(5,2) NOT NULL
);


ALTER TABLE public.shd300_recargos OWNER TO sisap;

--
-- TOC entry 5367 (class 0 OID 0)
-- Dependencies: 4186
-- Name: TABLE shd300_recargos; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON TABLE shd300_recargos IS 'Registra los recargos adicionales según la ordenanza y el tipo de propaganda';


--
-- TOC entry 5368 (class 0 OID 0)
-- Dependencies: 4186
-- Name: COLUMN shd300_recargos.cod_presi; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd300_recargos.cod_presi IS 'Código de la presidencia';


--
-- TOC entry 5369 (class 0 OID 0)
-- Dependencies: 4186
-- Name: COLUMN shd300_recargos.cod_entidad; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd300_recargos.cod_entidad IS 'Código de la entidad';


--
-- TOC entry 5370 (class 0 OID 0)
-- Dependencies: 4186
-- Name: COLUMN shd300_recargos.cod_tipo_inst; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd300_recargos.cod_tipo_inst IS 'Código tipo de Institución';


--
-- TOC entry 5371 (class 0 OID 0)
-- Dependencies: 4186
-- Name: COLUMN shd300_recargos.cod_inst; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd300_recargos.cod_inst IS 'Código de Institución';


--
-- TOC entry 5372 (class 0 OID 0)
-- Dependencies: 4186
-- Name: COLUMN shd300_recargos.cod_dep; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd300_recargos.cod_dep IS 'Código de la dependencia';


--
-- TOC entry 5373 (class 0 OID 0)
-- Dependencies: 4186
-- Name: COLUMN shd300_recargos.cod_recargo; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd300_recargos.cod_recargo IS 'Código de recargo';


--
-- TOC entry 5374 (class 0 OID 0)
-- Dependencies: 4186
-- Name: COLUMN shd300_recargos.denominacion; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd300_recargos.denominacion IS 'Denominación del recargo';


--
-- TOC entry 5375 (class 0 OID 0)
-- Dependencies: 4186
-- Name: COLUMN shd300_recargos.porcentaje; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd300_recargos.porcentaje IS 'Porcentaje del recargo';


--
-- TOC entry 4187 (class 1259 OID 502220)
-- Dependencies: 3
-- Name: shd300_tipo_propaganda; Type: TABLE; Schema: public; Owner: sisap; Tablespace:
--

CREATE TABLE shd300_tipo_propaganda (
    cod_presi integer NOT NULL,
    cod_entidad integer NOT NULL,
    cod_tipo_inst integer NOT NULL,
    cod_inst integer NOT NULL,
    cod_dep integer NOT NULL,
    cod_tipo integer NOT NULL,
    denominacion character varying(100) NOT NULL,
    articulo character varying(10) NOT NULL,
    monto numeric(26,2) NOT NULL,
    tipo_unidad integer NOT NULL
);


ALTER TABLE public.shd300_tipo_propaganda OWNER TO sisap;

--
-- TOC entry 5376 (class 0 OID 0)
-- Dependencies: 4187
-- Name: TABLE shd300_tipo_propaganda; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON TABLE shd300_tipo_propaganda IS 'Registrael monto por m2 o unidad según los tipos de propaganda';


--
-- TOC entry 5377 (class 0 OID 0)
-- Dependencies: 4187
-- Name: COLUMN shd300_tipo_propaganda.cod_presi; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd300_tipo_propaganda.cod_presi IS 'Código de la presidencia';


--
-- TOC entry 5378 (class 0 OID 0)
-- Dependencies: 4187
-- Name: COLUMN shd300_tipo_propaganda.cod_entidad; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd300_tipo_propaganda.cod_entidad IS 'Código de la entidad';


--
-- TOC entry 5379 (class 0 OID 0)
-- Dependencies: 4187
-- Name: COLUMN shd300_tipo_propaganda.cod_tipo_inst; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd300_tipo_propaganda.cod_tipo_inst IS 'Código tipo de Institución';


--
-- TOC entry 5380 (class 0 OID 0)
-- Dependencies: 4187
-- Name: COLUMN shd300_tipo_propaganda.cod_inst; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd300_tipo_propaganda.cod_inst IS 'Código de la Institución';


--
-- TOC entry 5381 (class 0 OID 0)
-- Dependencies: 4187
-- Name: COLUMN shd300_tipo_propaganda.cod_dep; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd300_tipo_propaganda.cod_dep IS 'Código de la dependencia';


--
-- TOC entry 5382 (class 0 OID 0)
-- Dependencies: 4187
-- Name: COLUMN shd300_tipo_propaganda.cod_tipo; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd300_tipo_propaganda.cod_tipo IS 'Código tipo de públicidad';


--
-- TOC entry 5383 (class 0 OID 0)
-- Dependencies: 4187
-- Name: COLUMN shd300_tipo_propaganda.denominacion; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd300_tipo_propaganda.denominacion IS 'Denominación tipo de publicidad';


--
-- TOC entry 5384 (class 0 OID 0)
-- Dependencies: 4187
-- Name: COLUMN shd300_tipo_propaganda.articulo; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd300_tipo_propaganda.articulo IS 'Articulo de la ordenanza';


--
-- TOC entry 5385 (class 0 OID 0)
-- Dependencies: 4187
-- Name: COLUMN shd300_tipo_propaganda.monto; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd300_tipo_propaganda.monto IS 'Monto en bolivares por m2 o unidad';


--
-- TOC entry 5386 (class 0 OID 0)
-- Dependencies: 4187
-- Name: COLUMN shd300_tipo_propaganda.tipo_unidad; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd300_tipo_propaganda.tipo_unidad IS 'Tipo de unidad
1.- Unidad
2.- Metros';


--
-- TOC entry 4188 (class 1259 OID 502225)
-- Dependencies: 3
-- Name: shd400_propiedad; Type: TABLE; Schema: public; Owner: sisap; Tablespace:
--

CREATE TABLE shd400_propiedad (
    cod_presi integer NOT NULL,
    cod_entidad integer NOT NULL,
    cod_tipo_inst integer NOT NULL,
    cod_inst integer NOT NULL,
    cod_dep integer NOT NULL,
    rif_cedula character varying(20) NOT NULL,
    cod_ficha character varying(20) NOT NULL,
    frecuencia_pago integer NOT NULL,
    monto_mensual numeric(26,2) NOT NULL,
    pago_todo integer NOT NULL,
    suspendido integer NOT NULL,
    rif_ci_cobrador character varying(20) NOT NULL,
    ultimo_ano_facturado integer,
    ultimo_mes_facturado integer
);


ALTER TABLE public.shd400_propiedad OWNER TO sisap;

--
-- TOC entry 5387 (class 0 OID 0)
-- Dependencies: 4188
-- Name: TABLE shd400_propiedad; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON TABLE shd400_propiedad IS 'Registra la propiedad inmobiliaria de los contribuyentes';


--
-- TOC entry 5388 (class 0 OID 0)
-- Dependencies: 4188
-- Name: COLUMN shd400_propiedad.cod_presi; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd400_propiedad.cod_presi IS 'Código de la presidencia';


--
-- TOC entry 5389 (class 0 OID 0)
-- Dependencies: 4188
-- Name: COLUMN shd400_propiedad.cod_entidad; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd400_propiedad.cod_entidad IS 'Código de la entidad';


--
-- TOC entry 5390 (class 0 OID 0)
-- Dependencies: 4188
-- Name: COLUMN shd400_propiedad.cod_tipo_inst; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd400_propiedad.cod_tipo_inst IS 'Código tipo de Institución';


--
-- TOC entry 5391 (class 0 OID 0)
-- Dependencies: 4188
-- Name: COLUMN shd400_propiedad.cod_inst; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd400_propiedad.cod_inst IS 'Código de la Institución';


--
-- TOC entry 5392 (class 0 OID 0)
-- Dependencies: 4188
-- Name: COLUMN shd400_propiedad.cod_dep; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd400_propiedad.cod_dep IS 'Código de la dependencia';


--
-- TOC entry 5393 (class 0 OID 0)
-- Dependencies: 4188
-- Name: COLUMN shd400_propiedad.rif_cedula; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd400_propiedad.rif_cedula IS 'Rif o Cédula de identidad del contribuyente';


--
-- TOC entry 5394 (class 0 OID 0)
-- Dependencies: 4188
-- Name: COLUMN shd400_propiedad.cod_ficha; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd400_propiedad.cod_ficha IS 'Código ficha catastral';


--
-- TOC entry 5395 (class 0 OID 0)
-- Dependencies: 4188
-- Name: COLUMN shd400_propiedad.frecuencia_pago; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd400_propiedad.frecuencia_pago IS 'Frecuencia de pago
1.- Mensual
2.- Bimestral
3.- Trimestral
4.- Semestral
5.- Anual';


--
-- TOC entry 5396 (class 0 OID 0)
-- Dependencies: 4188
-- Name: COLUMN shd400_propiedad.monto_mensual; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd400_propiedad.monto_mensual IS 'Monto mensual';


--
-- TOC entry 5397 (class 0 OID 0)
-- Dependencies: 4188
-- Name: COLUMN shd400_propiedad.pago_todo; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd400_propiedad.pago_todo IS 'Pago todo el año ?
1.- Si
2.- No';


--
-- TOC entry 5398 (class 0 OID 0)
-- Dependencies: 4188
-- Name: COLUMN shd400_propiedad.suspendido; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd400_propiedad.suspendido IS 'Contribuyente suspendido ?
1.- Si
2.- No';


--
-- TOC entry 5399 (class 0 OID 0)
-- Dependencies: 4188
-- Name: COLUMN shd400_propiedad.rif_ci_cobrador; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd400_propiedad.rif_ci_cobrador IS 'Rif o Cédula de identidad del cobrador';


--
-- TOC entry 5400 (class 0 OID 0)
-- Dependencies: 4188
-- Name: COLUMN shd400_propiedad.ultimo_ano_facturado; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd400_propiedad.ultimo_ano_facturado IS 'Ultimo año facturado';


--
-- TOC entry 5401 (class 0 OID 0)
-- Dependencies: 4188
-- Name: COLUMN shd400_propiedad.ultimo_mes_facturado; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd400_propiedad.ultimo_mes_facturado IS 'Ultimo mes facturado';


--
-- TOC entry 4189 (class 1259 OID 502230)
-- Dependencies: 3
-- Name: shd500_aseo_clasificacion; Type: TABLE; Schema: public; Owner: sisap; Tablespace:
--

CREATE TABLE shd500_aseo_clasificacion (
    cod_presi integer NOT NULL,
    cod_entidad integer NOT NULL,
    cod_tipo_inst integer NOT NULL,
    cod_inst integer NOT NULL,
    cod_dep integer NOT NULL,
    cod_clasificacion integer NOT NULL,
    denominacion character varying(100) NOT NULL,
    monto_mensual numeric(26,2) NOT NULL
);


ALTER TABLE public.shd500_aseo_clasificacion OWNER TO sisap;

--
-- TOC entry 5402 (class 0 OID 0)
-- Dependencies: 4189
-- Name: TABLE shd500_aseo_clasificacion; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON TABLE shd500_aseo_clasificacion IS 'Registra la clasificación del servicio de aseo domiciliario';


--
-- TOC entry 5403 (class 0 OID 0)
-- Dependencies: 4189
-- Name: COLUMN shd500_aseo_clasificacion.cod_presi; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd500_aseo_clasificacion.cod_presi IS 'Código de la presidencia';


--
-- TOC entry 5404 (class 0 OID 0)
-- Dependencies: 4189
-- Name: COLUMN shd500_aseo_clasificacion.cod_entidad; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd500_aseo_clasificacion.cod_entidad IS 'Código de la entidad';


--
-- TOC entry 5405 (class 0 OID 0)
-- Dependencies: 4189
-- Name: COLUMN shd500_aseo_clasificacion.cod_tipo_inst; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd500_aseo_clasificacion.cod_tipo_inst IS 'Código tipo de Institución';


--
-- TOC entry 5406 (class 0 OID 0)
-- Dependencies: 4189
-- Name: COLUMN shd500_aseo_clasificacion.cod_inst; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd500_aseo_clasificacion.cod_inst IS 'Código de la Institución';


--
-- TOC entry 5407 (class 0 OID 0)
-- Dependencies: 4189
-- Name: COLUMN shd500_aseo_clasificacion.cod_dep; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd500_aseo_clasificacion.cod_dep IS 'Código de la dependencia';


--
-- TOC entry 5408 (class 0 OID 0)
-- Dependencies: 4189
-- Name: COLUMN shd500_aseo_clasificacion.cod_clasificacion; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd500_aseo_clasificacion.cod_clasificacion IS 'Código clasificacion del servicio';


--
-- TOC entry 5409 (class 0 OID 0)
-- Dependencies: 4189
-- Name: COLUMN shd500_aseo_clasificacion.denominacion; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd500_aseo_clasificacion.denominacion IS 'Denominación de la clasificación del servicio de aseo domiciliario';


--
-- TOC entry 5410 (class 0 OID 0)
-- Dependencies: 4189
-- Name: COLUMN shd500_aseo_clasificacion.monto_mensual; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd500_aseo_clasificacion.monto_mensual IS 'Monto mensual';


--
-- TOC entry 4190 (class 1259 OID 502235)
-- Dependencies: 3
-- Name: shd500_aseo_domiciliario; Type: TABLE; Schema: public; Owner: sisap; Tablespace:
--

CREATE TABLE shd500_aseo_domiciliario (
    cod_presi integer NOT NULL,
    cod_entidad integer NOT NULL,
    cod_tipo_inst integer NOT NULL,
    cod_inst integer NOT NULL,
    cod_dep integer NOT NULL,
    rif_cedula character varying(20) NOT NULL,
    cod_clasificacion integer NOT NULL,
    frecuencia_pago integer NOT NULL,
    fecha_registro date NOT NULL,
    monto_mensual numeric(26,2) NOT NULL,
    pago_todo integer NOT NULL,
    suspendido integer NOT NULL,
    rif_ci_cobrador character varying(20) NOT NULL,
    ultimo_ano_facturado integer,
    ultimo_mes_facturado integer
);


ALTER TABLE public.shd500_aseo_domiciliario OWNER TO sisap;

--
-- TOC entry 5411 (class 0 OID 0)
-- Dependencies: 4190
-- Name: TABLE shd500_aseo_domiciliario; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON TABLE shd500_aseo_domiciliario IS 'Registra los contribuyentes de aseo domiciliario';


--
-- TOC entry 5412 (class 0 OID 0)
-- Dependencies: 4190
-- Name: COLUMN shd500_aseo_domiciliario.cod_presi; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd500_aseo_domiciliario.cod_presi IS 'Código de la presidencia';


--
-- TOC entry 5413 (class 0 OID 0)
-- Dependencies: 4190
-- Name: COLUMN shd500_aseo_domiciliario.cod_entidad; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd500_aseo_domiciliario.cod_entidad IS 'Código de la entidad';


--
-- TOC entry 5414 (class 0 OID 0)
-- Dependencies: 4190
-- Name: COLUMN shd500_aseo_domiciliario.cod_tipo_inst; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd500_aseo_domiciliario.cod_tipo_inst IS 'Código tipo de Institución';


--
-- TOC entry 5415 (class 0 OID 0)
-- Dependencies: 4190
-- Name: COLUMN shd500_aseo_domiciliario.cod_inst; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd500_aseo_domiciliario.cod_inst IS 'Código de la Institución';


--
-- TOC entry 5416 (class 0 OID 0)
-- Dependencies: 4190
-- Name: COLUMN shd500_aseo_domiciliario.cod_dep; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd500_aseo_domiciliario.cod_dep IS 'Código de la dependencia';


--
-- TOC entry 5417 (class 0 OID 0)
-- Dependencies: 4190
-- Name: COLUMN shd500_aseo_domiciliario.rif_cedula; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd500_aseo_domiciliario.rif_cedula IS 'Rif o Cédula de identidad';


--
-- TOC entry 5418 (class 0 OID 0)
-- Dependencies: 4190
-- Name: COLUMN shd500_aseo_domiciliario.cod_clasificacion; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd500_aseo_domiciliario.cod_clasificacion IS 'Código de la clasificación del servicio';


--
-- TOC entry 5419 (class 0 OID 0)
-- Dependencies: 4190
-- Name: COLUMN shd500_aseo_domiciliario.frecuencia_pago; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd500_aseo_domiciliario.frecuencia_pago IS 'Frecuencia de pago
1.- Mensual
2.- Bimestral
3.- Trimestral
4.- Semestral
5.- Anual';


--
-- TOC entry 5420 (class 0 OID 0)
-- Dependencies: 4190
-- Name: COLUMN shd500_aseo_domiciliario.fecha_registro; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd500_aseo_domiciliario.fecha_registro IS 'Fecha del registro';


--
-- TOC entry 5421 (class 0 OID 0)
-- Dependencies: 4190
-- Name: COLUMN shd500_aseo_domiciliario.monto_mensual; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd500_aseo_domiciliario.monto_mensual IS 'Monto mensual';


--
-- TOC entry 5422 (class 0 OID 0)
-- Dependencies: 4190
-- Name: COLUMN shd500_aseo_domiciliario.pago_todo; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd500_aseo_domiciliario.pago_todo IS 'Pago todo el año ?
1.- Si
2.- No';


--
-- TOC entry 5423 (class 0 OID 0)
-- Dependencies: 4190
-- Name: COLUMN shd500_aseo_domiciliario.suspendido; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd500_aseo_domiciliario.suspendido IS 'Contribuyente suspendido ?
1.- Si
2.- No';


--
-- TOC entry 5424 (class 0 OID 0)
-- Dependencies: 4190
-- Name: COLUMN shd500_aseo_domiciliario.rif_ci_cobrador; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd500_aseo_domiciliario.rif_ci_cobrador IS 'Rif o Cédula de identidad del cobrador';


--
-- TOC entry 5425 (class 0 OID 0)
-- Dependencies: 4190
-- Name: COLUMN shd500_aseo_domiciliario.ultimo_ano_facturado; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd500_aseo_domiciliario.ultimo_ano_facturado IS 'Ultimo año facturado';


--
-- TOC entry 5426 (class 0 OID 0)
-- Dependencies: 4190
-- Name: COLUMN shd500_aseo_domiciliario.ultimo_mes_facturado; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd500_aseo_domiciliario.ultimo_mes_facturado IS 'Ultimo mes facturado';


--
-- TOC entry 4240 (class 1259 OID 502898)
-- Dependencies: 3
-- Name: shd600_aprobacion_arrendamiento; Type: TABLE; Schema: public; Owner: sisap; Tablespace:
--

CREATE TABLE shd600_aprobacion_arrendamiento (
    cod_presi integer NOT NULL,
    cod_entidad integer NOT NULL,
    cod_tipo_inst integer NOT NULL,
    cod_inst integer NOT NULL,
    cod_dep integer NOT NULL,
    rif_cedula character varying(20) NOT NULL,
    numero_solicitud character varying(20) NOT NULL,
    fecha_aprobacion date NOT NULL,
    frecuencia_pago integer NOT NULL,
    datos_registro_arrendamiento text NOT NULL,
    monto_mensual numeric(26,2) NOT NULL,
    pago_todo integer,
    suspendido integer NOT NULL,
    rif_ci_cobrador character varying(20) NOT NULL,
    ultimo_ano_facturado integer,
    ultimo_mes_facturado integer,
    terreno_vendido integer
);


ALTER TABLE public.shd600_aprobacion_arrendamiento OWNER TO sisap;

--
-- TOC entry 5427 (class 0 OID 0)
-- Dependencies: 4240
-- Name: TABLE shd600_aprobacion_arrendamiento; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON TABLE shd600_aprobacion_arrendamiento IS 'Registro de la aprobación a la solicitud de arrendamiento';


--
-- TOC entry 5428 (class 0 OID 0)
-- Dependencies: 4240
-- Name: COLUMN shd600_aprobacion_arrendamiento.cod_presi; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd600_aprobacion_arrendamiento.cod_presi IS 'Código de la presidencia';


--
-- TOC entry 5429 (class 0 OID 0)
-- Dependencies: 4240
-- Name: COLUMN shd600_aprobacion_arrendamiento.cod_entidad; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd600_aprobacion_arrendamiento.cod_entidad IS 'Código de la entidad';


--
-- TOC entry 5430 (class 0 OID 0)
-- Dependencies: 4240
-- Name: COLUMN shd600_aprobacion_arrendamiento.cod_tipo_inst; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd600_aprobacion_arrendamiento.cod_tipo_inst IS 'Código tipo de Institución';


--
-- TOC entry 5431 (class 0 OID 0)
-- Dependencies: 4240
-- Name: COLUMN shd600_aprobacion_arrendamiento.cod_inst; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd600_aprobacion_arrendamiento.cod_inst IS 'Código de la Institución';


--
-- TOC entry 5432 (class 0 OID 0)
-- Dependencies: 4240
-- Name: COLUMN shd600_aprobacion_arrendamiento.cod_dep; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd600_aprobacion_arrendamiento.cod_dep IS 'Código de la dependencia';


--
-- TOC entry 5433 (class 0 OID 0)
-- Dependencies: 4240
-- Name: COLUMN shd600_aprobacion_arrendamiento.numero_solicitud; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd600_aprobacion_arrendamiento.numero_solicitud IS 'Número de solicitud de arrendamiento';


--
-- TOC entry 5434 (class 0 OID 0)
-- Dependencies: 4240
-- Name: COLUMN shd600_aprobacion_arrendamiento.fecha_aprobacion; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd600_aprobacion_arrendamiento.fecha_aprobacion IS 'Fecha de aprobacion';


--
-- TOC entry 5435 (class 0 OID 0)
-- Dependencies: 4240
-- Name: COLUMN shd600_aprobacion_arrendamiento.frecuencia_pago; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd600_aprobacion_arrendamiento.frecuencia_pago IS 'Frecuencia de pago
1.- Mensual
2.- Bimestral
3.- Trimestral
4.- Semestral
5.- Anual';


--
-- TOC entry 5436 (class 0 OID 0)
-- Dependencies: 4240
-- Name: COLUMN shd600_aprobacion_arrendamiento.datos_registro_arrendamiento; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd600_aprobacion_arrendamiento.datos_registro_arrendamiento IS 'Datos registro de arrendamiento';


--
-- TOC entry 5437 (class 0 OID 0)
-- Dependencies: 4240
-- Name: COLUMN shd600_aprobacion_arrendamiento.monto_mensual; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd600_aprobacion_arrendamiento.monto_mensual IS 'Monto mensual';


--
-- TOC entry 5438 (class 0 OID 0)
-- Dependencies: 4240
-- Name: COLUMN shd600_aprobacion_arrendamiento.pago_todo; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd600_aprobacion_arrendamiento.pago_todo IS 'Contribuyente pago todo el año ?
1.- Si
2.- No';


--
-- TOC entry 5439 (class 0 OID 0)
-- Dependencies: 4240
-- Name: COLUMN shd600_aprobacion_arrendamiento.suspendido; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd600_aprobacion_arrendamiento.suspendido IS 'Pago del contribuyente esta suspendido ?
1.- Si
2.- No';


--
-- TOC entry 5440 (class 0 OID 0)
-- Dependencies: 4240
-- Name: COLUMN shd600_aprobacion_arrendamiento.rif_ci_cobrador; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd600_aprobacion_arrendamiento.rif_ci_cobrador IS 'Rif o Cédula de identidad del cobrador';


--
-- TOC entry 5441 (class 0 OID 0)
-- Dependencies: 4240
-- Name: COLUMN shd600_aprobacion_arrendamiento.ultimo_ano_facturado; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd600_aprobacion_arrendamiento.ultimo_ano_facturado IS 'Ultimo año facturado';


--
-- TOC entry 5442 (class 0 OID 0)
-- Dependencies: 4240
-- Name: COLUMN shd600_aprobacion_arrendamiento.ultimo_mes_facturado; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd600_aprobacion_arrendamiento.ultimo_mes_facturado IS 'Ultimo mes facturado';


--
-- TOC entry 5443 (class 0 OID 0)
-- Dependencies: 4240
-- Name: COLUMN shd600_aprobacion_arrendamiento.terreno_vendido; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd600_aprobacion_arrendamiento.terreno_vendido IS 'Terreno vendido ?
1.- Si
2.- No';


--
-- TOC entry 4241 (class 1259 OID 502911)
-- Dependencies: 3
-- Name: shd600_compra_terreno; Type: TABLE; Schema: public; Owner: sisap; Tablespace:
--

CREATE TABLE shd600_compra_terreno (
    cod_presi integer NOT NULL,
    cod_entidad integer NOT NULL,
    cod_tipo_inst integer NOT NULL,
    cod_inst integer NOT NULL,
    cod_dep integer NOT NULL,
    rif_cedula character varying(20) NOT NULL,
    numero_solicitud character varying(20) NOT NULL,
    fecha_compra date NOT NULL,
    datos_compra text NOT NULL,
    monto numeric(26,2) NOT NULL
);


ALTER TABLE public.shd600_compra_terreno OWNER TO sisap;

--
-- TOC entry 5444 (class 0 OID 0)
-- Dependencies: 4241
-- Name: TABLE shd600_compra_terreno; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON TABLE shd600_compra_terreno IS 'Registra la compra del terreno, previo arrendamiento';


--
-- TOC entry 5445 (class 0 OID 0)
-- Dependencies: 4241
-- Name: COLUMN shd600_compra_terreno.cod_presi; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd600_compra_terreno.cod_presi IS 'Código de la presidencia';


--
-- TOC entry 5446 (class 0 OID 0)
-- Dependencies: 4241
-- Name: COLUMN shd600_compra_terreno.cod_entidad; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd600_compra_terreno.cod_entidad IS 'Código de la entidad';


--
-- TOC entry 5447 (class 0 OID 0)
-- Dependencies: 4241
-- Name: COLUMN shd600_compra_terreno.cod_tipo_inst; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd600_compra_terreno.cod_tipo_inst IS 'Código tipo de Institución';


--
-- TOC entry 5448 (class 0 OID 0)
-- Dependencies: 4241
-- Name: COLUMN shd600_compra_terreno.cod_inst; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd600_compra_terreno.cod_inst IS 'Código de la Institución';


--
-- TOC entry 5449 (class 0 OID 0)
-- Dependencies: 4241
-- Name: COLUMN shd600_compra_terreno.cod_dep; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd600_compra_terreno.cod_dep IS 'Código de la dependencia';


--
-- TOC entry 5450 (class 0 OID 0)
-- Dependencies: 4241
-- Name: COLUMN shd600_compra_terreno.numero_solicitud; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd600_compra_terreno.numero_solicitud IS 'Número de solicitud';


--
-- TOC entry 5451 (class 0 OID 0)
-- Dependencies: 4241
-- Name: COLUMN shd600_compra_terreno.fecha_compra; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd600_compra_terreno.fecha_compra IS 'Fecha de compra';


--
-- TOC entry 5452 (class 0 OID 0)
-- Dependencies: 4241
-- Name: COLUMN shd600_compra_terreno.datos_compra; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd600_compra_terreno.datos_compra IS 'Datos de la compra';


--
-- TOC entry 5453 (class 0 OID 0)
-- Dependencies: 4241
-- Name: COLUMN shd600_compra_terreno.monto; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd600_compra_terreno.monto IS 'Monto de compra';


--
-- TOC entry 4239 (class 1259 OID 502890)
-- Dependencies: 3
-- Name: shd600_solicitud_arrendamiento; Type: TABLE; Schema: public; Owner: sisap; Tablespace:
--

CREATE TABLE shd600_solicitud_arrendamiento (
    cod_presi integer NOT NULL,
    cod_entidad integer NOT NULL,
    cod_tipo_inst integer NOT NULL,
    cod_inst integer NOT NULL,
    cod_dep integer NOT NULL,
    rif_cedula character varying(20) NOT NULL,
    numero_solicitud character varying(20) NOT NULL,
    fecha_solicitud date NOT NULL,
    opcion integer NOT NULL,
    cod_ficha character varying(20) NOT NULL,
    expectativa_construccion text
);


ALTER TABLE public.shd600_solicitud_arrendamiento OWNER TO sisap;

--
-- TOC entry 5454 (class 0 OID 0)
-- Dependencies: 4239
-- Name: TABLE shd600_solicitud_arrendamiento; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON TABLE shd600_solicitud_arrendamiento IS 'Registra la solicitud de arrendamiento';


--
-- TOC entry 5455 (class 0 OID 0)
-- Dependencies: 4239
-- Name: COLUMN shd600_solicitud_arrendamiento.cod_presi; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd600_solicitud_arrendamiento.cod_presi IS 'Código de la presidencia';


--
-- TOC entry 5456 (class 0 OID 0)
-- Dependencies: 4239
-- Name: COLUMN shd600_solicitud_arrendamiento.cod_entidad; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd600_solicitud_arrendamiento.cod_entidad IS 'Código de la entidad';


--
-- TOC entry 5457 (class 0 OID 0)
-- Dependencies: 4239
-- Name: COLUMN shd600_solicitud_arrendamiento.cod_tipo_inst; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd600_solicitud_arrendamiento.cod_tipo_inst IS 'Código tipo de la Institución';


--
-- TOC entry 5458 (class 0 OID 0)
-- Dependencies: 4239
-- Name: COLUMN shd600_solicitud_arrendamiento.cod_inst; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd600_solicitud_arrendamiento.cod_inst IS 'Código de la Institución';


--
-- TOC entry 5459 (class 0 OID 0)
-- Dependencies: 4239
-- Name: COLUMN shd600_solicitud_arrendamiento.cod_dep; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd600_solicitud_arrendamiento.cod_dep IS 'Código de la dependencia';


--
-- TOC entry 5460 (class 0 OID 0)
-- Dependencies: 4239
-- Name: COLUMN shd600_solicitud_arrendamiento.rif_cedula; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd600_solicitud_arrendamiento.rif_cedula IS 'Rif o Cédula de identidad';


--
-- TOC entry 5461 (class 0 OID 0)
-- Dependencies: 4239
-- Name: COLUMN shd600_solicitud_arrendamiento.numero_solicitud; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd600_solicitud_arrendamiento.numero_solicitud IS 'Número de solicitud';


--
-- TOC entry 5462 (class 0 OID 0)
-- Dependencies: 4239
-- Name: COLUMN shd600_solicitud_arrendamiento.fecha_solicitud; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd600_solicitud_arrendamiento.fecha_solicitud IS 'Fecha de la solicitud de arrendamiento';


--
-- TOC entry 5463 (class 0 OID 0)
-- Dependencies: 4239
-- Name: COLUMN shd600_solicitud_arrendamiento.opcion; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd600_solicitud_arrendamiento.opcion IS 'Opción
1.- Simple
2.- Compra';


--
-- TOC entry 5464 (class 0 OID 0)
-- Dependencies: 4239
-- Name: COLUMN shd600_solicitud_arrendamiento.cod_ficha; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd600_solicitud_arrendamiento.cod_ficha IS 'Código de la ficha catastral';


--
-- TOC entry 5465 (class 0 OID 0)
-- Dependencies: 4239
-- Name: COLUMN shd600_solicitud_arrendamiento.expectativa_construccion; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd600_solicitud_arrendamiento.expectativa_construccion IS 'Expectativa de construcción que tiene el solicitante';


--
-- TOC entry 4191 (class 1259 OID 502274)
-- Dependencies: 3
-- Name: shd700_credito_vivienda; Type: TABLE; Schema: public; Owner: sisap; Tablespace:
--

CREATE TABLE shd700_credito_vivienda (
    cod_presi integer NOT NULL,
    cod_entidad integer NOT NULL,
    cod_tipo_inst integer NOT NULL,
    cod_inst integer NOT NULL,
    cod_dep integer NOT NULL,
    rif_cedula character varying(20) NOT NULL,
    numero_solicitud character varying(20) NOT NULL,
    fecha_solicitud date NOT NULL,
    nombre_conyugue character varying(100),
    cedula_conyugue integer,
    nombre_empresa character varying(100),
    tiempo_empresa character varying(20),
    telefonos_empresas character varying(50),
    direccion_empresa text,
    grupo_familiar integer,
    ingreso_mensual numeric(26,2),
    vivienda_actual integer,
    tipo_vivienda character varying(10),
    direccion_vivienda_credito text,
    costo_vivienda numeric(26,2) NOT NULL,
    monto_cuota_inicial numeric(26,2) NOT NULL,
    monto_restante numeric(26,2) NOT NULL,
    factor numeric(14,12),
    plazo_anos integer NOT NULL,
    numero_cuotas integer NOT NULL,
    monto_mensual numeric(26,2) NOT NULL,
    numero_contrato character varying(20),
    fecha_contrato date,
    frecuencia_pago integer NOT NULL,
    pago_todo integer,
    suspendido integer,
    rif_ci_cobrador character varying(20) NOT NULL,
    ultimo_ano_facturado integer NOT NULL,
    ultimo_mes_facturado integer,
    area_construccion numeric(10,3),
    area_terreno numeric(10,3),
    norte text,
    sur text,
    este text,
    oeste text,
    tasa_interes numeric(5,2),
    fecha_entrega_contrato date
);


ALTER TABLE public.shd700_credito_vivienda OWNER TO sisap;

--
-- TOC entry 5466 (class 0 OID 0)
-- Dependencies: 4191
-- Name: TABLE shd700_credito_vivienda; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON TABLE shd700_credito_vivienda IS 'Registro de solicitantes de créditos de vivienda';


--
-- TOC entry 5467 (class 0 OID 0)
-- Dependencies: 4191
-- Name: COLUMN shd700_credito_vivienda.cod_presi; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd700_credito_vivienda.cod_presi IS 'Código de la presidencia';


--
-- TOC entry 5468 (class 0 OID 0)
-- Dependencies: 4191
-- Name: COLUMN shd700_credito_vivienda.cod_entidad; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd700_credito_vivienda.cod_entidad IS 'Código de la entidad';


--
-- TOC entry 5469 (class 0 OID 0)
-- Dependencies: 4191
-- Name: COLUMN shd700_credito_vivienda.cod_tipo_inst; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd700_credito_vivienda.cod_tipo_inst IS 'Código tipo de Institución';


--
-- TOC entry 5470 (class 0 OID 0)
-- Dependencies: 4191
-- Name: COLUMN shd700_credito_vivienda.cod_inst; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd700_credito_vivienda.cod_inst IS 'Código de la Institución';


--
-- TOC entry 5471 (class 0 OID 0)
-- Dependencies: 4191
-- Name: COLUMN shd700_credito_vivienda.cod_dep; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd700_credito_vivienda.cod_dep IS 'Código de la dependencia';


--
-- TOC entry 5472 (class 0 OID 0)
-- Dependencies: 4191
-- Name: COLUMN shd700_credito_vivienda.rif_cedula; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd700_credito_vivienda.rif_cedula IS 'Rif o Cédula de identidad';


--
-- TOC entry 5473 (class 0 OID 0)
-- Dependencies: 4191
-- Name: COLUMN shd700_credito_vivienda.fecha_solicitud; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd700_credito_vivienda.fecha_solicitud IS 'Fecha de solicitud';


--
-- TOC entry 5474 (class 0 OID 0)
-- Dependencies: 4191
-- Name: COLUMN shd700_credito_vivienda.nombre_conyugue; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd700_credito_vivienda.nombre_conyugue IS 'Nombre y apellidos del conyugue';


--
-- TOC entry 5475 (class 0 OID 0)
-- Dependencies: 4191
-- Name: COLUMN shd700_credito_vivienda.cedula_conyugue; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd700_credito_vivienda.cedula_conyugue IS 'Cédula del conyugue';


--
-- TOC entry 5476 (class 0 OID 0)
-- Dependencies: 4191
-- Name: COLUMN shd700_credito_vivienda.nombre_empresa; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd700_credito_vivienda.nombre_empresa IS 'Nombre de la empresa donde trabaja el solicitante';


--
-- TOC entry 5477 (class 0 OID 0)
-- Dependencies: 4191
-- Name: COLUMN shd700_credito_vivienda.tiempo_empresa; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd700_credito_vivienda.tiempo_empresa IS 'Tiempo de trabajo en la empresa';


--
-- TOC entry 5478 (class 0 OID 0)
-- Dependencies: 4191
-- Name: COLUMN shd700_credito_vivienda.telefonos_empresas; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd700_credito_vivienda.telefonos_empresas IS 'Teléfonos de la empresa';


--
-- TOC entry 5479 (class 0 OID 0)
-- Dependencies: 4191
-- Name: COLUMN shd700_credito_vivienda.direccion_empresa; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd700_credito_vivienda.direccion_empresa IS 'Dirección de la empresa';


--
-- TOC entry 5480 (class 0 OID 0)
-- Dependencies: 4191
-- Name: COLUMN shd700_credito_vivienda.grupo_familiar; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd700_credito_vivienda.grupo_familiar IS 'Grupo familiar';


--
-- TOC entry 5481 (class 0 OID 0)
-- Dependencies: 4191
-- Name: COLUMN shd700_credito_vivienda.ingreso_mensual; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd700_credito_vivienda.ingreso_mensual IS 'Ingreso mensual familiar';


--
-- TOC entry 5482 (class 0 OID 0)
-- Dependencies: 4191
-- Name: COLUMN shd700_credito_vivienda.vivienda_actual; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd700_credito_vivienda.vivienda_actual IS 'Vivienda actual
1.- Propia
2.- Alquilada
3.- Familiar';


--
-- TOC entry 5483 (class 0 OID 0)
-- Dependencies: 4191
-- Name: COLUMN shd700_credito_vivienda.tipo_vivienda; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd700_credito_vivienda.tipo_vivienda IS 'Tipo de vivienda
';


--
-- TOC entry 5484 (class 0 OID 0)
-- Dependencies: 4191
-- Name: COLUMN shd700_credito_vivienda.direccion_vivienda_credito; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd700_credito_vivienda.direccion_vivienda_credito IS 'Dirección de la vivienda sujeta al crédito';


--
-- TOC entry 5485 (class 0 OID 0)
-- Dependencies: 4191
-- Name: COLUMN shd700_credito_vivienda.costo_vivienda; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd700_credito_vivienda.costo_vivienda IS 'Costo de la vivienda sujeta al crédito';


--
-- TOC entry 5486 (class 0 OID 0)
-- Dependencies: 4191
-- Name: COLUMN shd700_credito_vivienda.monto_cuota_inicial; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd700_credito_vivienda.monto_cuota_inicial IS 'Monto de la cuota inicial';


--
-- TOC entry 5487 (class 0 OID 0)
-- Dependencies: 4191
-- Name: COLUMN shd700_credito_vivienda.monto_restante; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd700_credito_vivienda.monto_restante IS 'Monto restante del crédito';


--
-- TOC entry 5488 (class 0 OID 0)
-- Dependencies: 4191
-- Name: COLUMN shd700_credito_vivienda.factor; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd700_credito_vivienda.factor IS 'Factor de cálculo';


--
-- TOC entry 5489 (class 0 OID 0)
-- Dependencies: 4191
-- Name: COLUMN shd700_credito_vivienda.plazo_anos; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd700_credito_vivienda.plazo_anos IS 'Plazo años';


--
-- TOC entry 5490 (class 0 OID 0)
-- Dependencies: 4191
-- Name: COLUMN shd700_credito_vivienda.numero_cuotas; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd700_credito_vivienda.numero_cuotas IS 'Número de cuotas';


--
-- TOC entry 5491 (class 0 OID 0)
-- Dependencies: 4191
-- Name: COLUMN shd700_credito_vivienda.monto_mensual; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd700_credito_vivienda.monto_mensual IS 'Monto a cancelar mensual';


--
-- TOC entry 5492 (class 0 OID 0)
-- Dependencies: 4191
-- Name: COLUMN shd700_credito_vivienda.numero_contrato; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd700_credito_vivienda.numero_contrato IS 'Número de contrato';


--
-- TOC entry 5493 (class 0 OID 0)
-- Dependencies: 4191
-- Name: COLUMN shd700_credito_vivienda.fecha_contrato; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd700_credito_vivienda.fecha_contrato IS 'Fecha de contrato';


--
-- TOC entry 5494 (class 0 OID 0)
-- Dependencies: 4191
-- Name: COLUMN shd700_credito_vivienda.frecuencia_pago; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd700_credito_vivienda.frecuencia_pago IS 'Frecuencia de pago
1.- Mensual
2.- Bimestral
3.- Trimestral
4.- Semestral
5.- Anual';


--
-- TOC entry 5495 (class 0 OID 0)
-- Dependencies: 4191
-- Name: COLUMN shd700_credito_vivienda.pago_todo; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd700_credito_vivienda.pago_todo IS 'Contribuyente pago todo el año?
1.- Si
2.- No
';


--
-- TOC entry 5496 (class 0 OID 0)
-- Dependencies: 4191
-- Name: COLUMN shd700_credito_vivienda.suspendido; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd700_credito_vivienda.suspendido IS 'Suspendido el cobro a este contribuyente';


--
-- TOC entry 5497 (class 0 OID 0)
-- Dependencies: 4191
-- Name: COLUMN shd700_credito_vivienda.rif_ci_cobrador; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd700_credito_vivienda.rif_ci_cobrador IS 'Rif o cédula de identidad del cobrador';


--
-- TOC entry 5498 (class 0 OID 0)
-- Dependencies: 4191
-- Name: COLUMN shd700_credito_vivienda.ultimo_ano_facturado; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd700_credito_vivienda.ultimo_ano_facturado IS 'Ultimo año facturado';


--
-- TOC entry 5499 (class 0 OID 0)
-- Dependencies: 4191
-- Name: COLUMN shd700_credito_vivienda.ultimo_mes_facturado; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd700_credito_vivienda.ultimo_mes_facturado IS 'Ultimo mes facturado';


--
-- TOC entry 4192 (class 1259 OID 502282)
-- Dependencies: 3
-- Name: shd700_credito_vivienda_parentesco; Type: TABLE; Schema: public; Owner: sisap; Tablespace:
--

CREATE TABLE shd700_credito_vivienda_parentesco (
    cod_presi integer NOT NULL,
    cod_entidad integer NOT NULL,
    cod_tipo_inst integer NOT NULL,
    cod_inst integer NOT NULL,
    cod_dep integer NOT NULL,
    numero_solicitud character varying(20) NOT NULL,
    rif_cedula character varying(20) NOT NULL,
    cod_parentesco integer NOT NULL,
    nombre_apellido character varying(100) NOT NULL,
    sexo integer NOT NULL,
    fecha_nacimiento date NOT NULL
);


ALTER TABLE public.shd700_credito_vivienda_parentesco OWNER TO sisap;

--
-- TOC entry 5500 (class 0 OID 0)
-- Dependencies: 4192
-- Name: TABLE shd700_credito_vivienda_parentesco; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON TABLE shd700_credito_vivienda_parentesco IS 'Registro de parentesco de las solicitudes de crédito de vivienda';


--
-- TOC entry 5501 (class 0 OID 0)
-- Dependencies: 4192
-- Name: COLUMN shd700_credito_vivienda_parentesco.cod_presi; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd700_credito_vivienda_parentesco.cod_presi IS 'Código de la presidencia';


--
-- TOC entry 5502 (class 0 OID 0)
-- Dependencies: 4192
-- Name: COLUMN shd700_credito_vivienda_parentesco.cod_entidad; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd700_credito_vivienda_parentesco.cod_entidad IS 'Código de la entidad';


--
-- TOC entry 5503 (class 0 OID 0)
-- Dependencies: 4192
-- Name: COLUMN shd700_credito_vivienda_parentesco.cod_tipo_inst; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd700_credito_vivienda_parentesco.cod_tipo_inst IS 'Código tipo de Institución';


--
-- TOC entry 5504 (class 0 OID 0)
-- Dependencies: 4192
-- Name: COLUMN shd700_credito_vivienda_parentesco.cod_inst; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd700_credito_vivienda_parentesco.cod_inst IS 'Código de la Institución';


--
-- TOC entry 5505 (class 0 OID 0)
-- Dependencies: 4192
-- Name: COLUMN shd700_credito_vivienda_parentesco.cod_dep; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd700_credito_vivienda_parentesco.cod_dep IS 'Código de la dependencia';


--
-- TOC entry 5506 (class 0 OID 0)
-- Dependencies: 4192
-- Name: COLUMN shd700_credito_vivienda_parentesco.rif_cedula; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd700_credito_vivienda_parentesco.rif_cedula IS 'Rif o cédula del solicitante';


--
-- TOC entry 5507 (class 0 OID 0)
-- Dependencies: 4192
-- Name: COLUMN shd700_credito_vivienda_parentesco.cod_parentesco; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd700_credito_vivienda_parentesco.cod_parentesco IS 'Código de parentesco';


--
-- TOC entry 5508 (class 0 OID 0)
-- Dependencies: 4192
-- Name: COLUMN shd700_credito_vivienda_parentesco.nombre_apellido; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd700_credito_vivienda_parentesco.nombre_apellido IS 'Nombre del apellido de pariente';


--
-- TOC entry 5509 (class 0 OID 0)
-- Dependencies: 4192
-- Name: COLUMN shd700_credito_vivienda_parentesco.sexo; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd700_credito_vivienda_parentesco.sexo IS 'Sexo
1.- Masculino
2.- Femenino';


--
-- TOC entry 5510 (class 0 OID 0)
-- Dependencies: 4192
-- Name: COLUMN shd700_credito_vivienda_parentesco.fecha_nacimiento; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd700_credito_vivienda_parentesco.fecha_nacimiento IS 'Fecha de nacimiento';


--
-- TOC entry 4193 (class 1259 OID 502292)
-- Dependencies: 3
-- Name: shd900_cobranza_acumulada; Type: TABLE; Schema: public; Owner: sisap; Tablespace:
--

CREATE TABLE shd900_cobranza_acumulada (
    cod_presi integer NOT NULL,
    cod_entidad integer NOT NULL,
    cod_tipo_inst integer NOT NULL,
    cod_inst integer NOT NULL,
    cod_dep integer NOT NULL,
    ano integer NOT NULL,
    mes integer NOT NULL,
    dia integer NOT NULL,
    cod_partida integer NOT NULL,
    cod_generica integer NOT NULL,
    cod_especifica integer NOT NULL,
    cod_sub_espec integer NOT NULL,
    cod_auxiliar integer NOT NULL,
    deuda_vigente numeric(26,2),
    deuda_anterior numeric(26,2),
    monto_recargo numeric(26,2),
    monto_multa numeric(26,2),
    monto_intereses numeric(26,2),
    monto_descuento numeric(26,2),
    cantidad_depositos integer,
    monto_depositos numeric(26,2),
    cantidad_notas_credito integer,
    monto_notas_credito numeric(26,2),
    cantidad_cheques integer,
    monto_cheques numeric(26,2),
    cantidad_descuento integer,
    cantidad_pagos_efectivo integer,
    monto_pagos_efectivo numeric(26,2)
);


ALTER TABLE public.shd900_cobranza_acumulada OWNER TO sisap;

--
-- TOC entry 5511 (class 0 OID 0)
-- Dependencies: 4193
-- Name: TABLE shd900_cobranza_acumulada; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON TABLE shd900_cobranza_acumulada IS 'Registra cobranza acumulada';


--
-- TOC entry 5512 (class 0 OID 0)
-- Dependencies: 4193
-- Name: COLUMN shd900_cobranza_acumulada.cod_presi; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd900_cobranza_acumulada.cod_presi IS 'Código de la presidencia';


--
-- TOC entry 5513 (class 0 OID 0)
-- Dependencies: 4193
-- Name: COLUMN shd900_cobranza_acumulada.cod_entidad; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd900_cobranza_acumulada.cod_entidad IS 'Código de la entidad federal';


--
-- TOC entry 5514 (class 0 OID 0)
-- Dependencies: 4193
-- Name: COLUMN shd900_cobranza_acumulada.cod_tipo_inst; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd900_cobranza_acumulada.cod_tipo_inst IS 'Código tipo de Institución';


--
-- TOC entry 5515 (class 0 OID 0)
-- Dependencies: 4193
-- Name: COLUMN shd900_cobranza_acumulada.cod_inst; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd900_cobranza_acumulada.cod_inst IS 'Código de la Institución';


--
-- TOC entry 5516 (class 0 OID 0)
-- Dependencies: 4193
-- Name: COLUMN shd900_cobranza_acumulada.cod_dep; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd900_cobranza_acumulada.cod_dep IS 'Código de la dependencia';


--
-- TOC entry 5517 (class 0 OID 0)
-- Dependencies: 4193
-- Name: COLUMN shd900_cobranza_acumulada.ano; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd900_cobranza_acumulada.ano IS 'Año';


--
-- TOC entry 5518 (class 0 OID 0)
-- Dependencies: 4193
-- Name: COLUMN shd900_cobranza_acumulada.mes; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd900_cobranza_acumulada.mes IS 'Mes';


--
-- TOC entry 5519 (class 0 OID 0)
-- Dependencies: 4193
-- Name: COLUMN shd900_cobranza_acumulada.dia; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd900_cobranza_acumulada.dia IS 'Dia';


--
-- TOC entry 5520 (class 0 OID 0)
-- Dependencies: 4193
-- Name: COLUMN shd900_cobranza_acumulada.cod_partida; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd900_cobranza_acumulada.cod_partida IS 'Código de partida';


--
-- TOC entry 5521 (class 0 OID 0)
-- Dependencies: 4193
-- Name: COLUMN shd900_cobranza_acumulada.cod_generica; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd900_cobranza_acumulada.cod_generica IS 'Código de generica';


--
-- TOC entry 5522 (class 0 OID 0)
-- Dependencies: 4193
-- Name: COLUMN shd900_cobranza_acumulada.cod_especifica; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd900_cobranza_acumulada.cod_especifica IS 'Código de especifica';


--
-- TOC entry 5523 (class 0 OID 0)
-- Dependencies: 4193
-- Name: COLUMN shd900_cobranza_acumulada.cod_sub_espec; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd900_cobranza_acumulada.cod_sub_espec IS 'Código de subespecifica';


--
-- TOC entry 5524 (class 0 OID 0)
-- Dependencies: 4193
-- Name: COLUMN shd900_cobranza_acumulada.cod_auxiliar; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd900_cobranza_acumulada.cod_auxiliar IS 'Código de auxiliar';


--
-- TOC entry 5525 (class 0 OID 0)
-- Dependencies: 4193
-- Name: COLUMN shd900_cobranza_acumulada.deuda_vigente; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd900_cobranza_acumulada.deuda_vigente IS 'Deuda vigente';


--
-- TOC entry 5526 (class 0 OID 0)
-- Dependencies: 4193
-- Name: COLUMN shd900_cobranza_acumulada.deuda_anterior; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd900_cobranza_acumulada.deuda_anterior IS 'Deuda anterior';


--
-- TOC entry 5527 (class 0 OID 0)
-- Dependencies: 4193
-- Name: COLUMN shd900_cobranza_acumulada.monto_recargo; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd900_cobranza_acumulada.monto_recargo IS 'Monto recargo';


--
-- TOC entry 5528 (class 0 OID 0)
-- Dependencies: 4193
-- Name: COLUMN shd900_cobranza_acumulada.monto_multa; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd900_cobranza_acumulada.monto_multa IS 'Monto multa';


--
-- TOC entry 5529 (class 0 OID 0)
-- Dependencies: 4193
-- Name: COLUMN shd900_cobranza_acumulada.monto_intereses; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd900_cobranza_acumulada.monto_intereses IS 'Monto intereses';


--
-- TOC entry 5530 (class 0 OID 0)
-- Dependencies: 4193
-- Name: COLUMN shd900_cobranza_acumulada.monto_descuento; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd900_cobranza_acumulada.monto_descuento IS 'Monto descuento';


--
-- TOC entry 5531 (class 0 OID 0)
-- Dependencies: 4193
-- Name: COLUMN shd900_cobranza_acumulada.cantidad_depositos; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd900_cobranza_acumulada.cantidad_depositos IS 'Cantidad de depositos';


--
-- TOC entry 5532 (class 0 OID 0)
-- Dependencies: 4193
-- Name: COLUMN shd900_cobranza_acumulada.monto_depositos; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd900_cobranza_acumulada.monto_depositos IS 'Monto de depositos';


--
-- TOC entry 5533 (class 0 OID 0)
-- Dependencies: 4193
-- Name: COLUMN shd900_cobranza_acumulada.cantidad_notas_credito; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd900_cobranza_acumulada.cantidad_notas_credito IS 'Cantidad notas de crédito';


--
-- TOC entry 5534 (class 0 OID 0)
-- Dependencies: 4193
-- Name: COLUMN shd900_cobranza_acumulada.monto_notas_credito; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd900_cobranza_acumulada.monto_notas_credito IS 'Monto notas de crédito';


--
-- TOC entry 5535 (class 0 OID 0)
-- Dependencies: 4193
-- Name: COLUMN shd900_cobranza_acumulada.cantidad_cheques; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd900_cobranza_acumulada.cantidad_cheques IS 'Cantidad de cheques';


--
-- TOC entry 5536 (class 0 OID 0)
-- Dependencies: 4193
-- Name: COLUMN shd900_cobranza_acumulada.monto_cheques; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd900_cobranza_acumulada.monto_cheques IS 'Monto de cheques';


--
-- TOC entry 5537 (class 0 OID 0)
-- Dependencies: 4193
-- Name: COLUMN shd900_cobranza_acumulada.cantidad_descuento; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd900_cobranza_acumulada.cantidad_descuento IS 'Cantidad de descuentos';


--
-- TOC entry 5538 (class 0 OID 0)
-- Dependencies: 4193
-- Name: COLUMN shd900_cobranza_acumulada.cantidad_pagos_efectivo; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd900_cobranza_acumulada.cantidad_pagos_efectivo IS 'Cantidad de pagos en efectivo';


--
-- TOC entry 5539 (class 0 OID 0)
-- Dependencies: 4193
-- Name: COLUMN shd900_cobranza_acumulada.monto_pagos_efectivo; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd900_cobranza_acumulada.monto_pagos_efectivo IS 'Monto de pagos en efectivo';


--
-- TOC entry 4197 (class 1259 OID 502318)
-- Dependencies: 3
-- Name: shd900_cobranza_diaria; Type: TABLE; Schema: public; Owner: sisap; Tablespace:
--

CREATE TABLE shd900_cobranza_diaria (
    cod_presi integer NOT NULL,
    cod_entidad integer NOT NULL,
    cod_tipo_inst integer NOT NULL,
    cod_inst integer NOT NULL,
    cod_dep integer NOT NULL,
    ano_comprobante integer NOT NULL,
    numero_comprobante integer NOT NULL,
    cod_partida integer NOT NULL,
    cod_generica integer NOT NULL,
    cod_especifica integer NOT NULL,
    cod_sub_espec integer NOT NULL,
    cod_auxiliar integer NOT NULL,
    fecha_comprobante date NOT NULL,
    rif_cedula character varying(20) NOT NULL,
    concepto_comprobante text NOT NULL,
    deuda_anterior numeric(26,2),
    deuda_vigente numeric(26,2),
    monto_recargo numeric(26,2),
    monto_multa numeric(26,2),
    monto_intereses numeric(26,2),
    monto_descuento numeric(26,2),
    cod_entidad_deposito integer,
    cod_sucursal_deposito integer,
    cuenta_bancaria_deposito character varying(20),
    numero_deposito character varying(20),
    monto_deposito numeric(26,2),
    fecha_deposito date,
    cod_entidad_credito integer,
    cod_sucursal_credito integer,
    cuenta_bancaria_credito character varying(20),
    numero_nota_credito character varying(20),
    monto_nota_credito numeric(26,2),
    fecha_nota_credito date,
    cod_entidad_cheque integer,
    cod_sucursal_cheque integer,
    cuenta_bancaria_cheque character varying(20),
    numero_cheque integer,
    monto_cheque numeric(26,2),
    fecha_cheque date,
    monto_efectivo numeric(26,2),
    condicion_documento integer NOT NULL,
    fecha_registro date NOT NULL,
    username_registro character varying(60),
    ano_anulacion integer,
    numero_anulacion integer,
    fecha_anulacion date,
    username_anulacion character varying(60),
    rif_ci_cobrador character varying(20) NOT NULL
);


ALTER TABLE public.shd900_cobranza_diaria OWNER TO sisap;

--
-- TOC entry 5540 (class 0 OID 0)
-- Dependencies: 4197
-- Name: TABLE shd900_cobranza_diaria; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON TABLE shd900_cobranza_diaria IS 'Registro de cobros efectuados por razón a otros ingresos';


--
-- TOC entry 5541 (class 0 OID 0)
-- Dependencies: 4197
-- Name: COLUMN shd900_cobranza_diaria.cod_presi; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd900_cobranza_diaria.cod_presi IS 'Código de la presidencia
';


--
-- TOC entry 5542 (class 0 OID 0)
-- Dependencies: 4197
-- Name: COLUMN shd900_cobranza_diaria.cod_entidad; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd900_cobranza_diaria.cod_entidad IS 'Código de la entidad federal';


--
-- TOC entry 5543 (class 0 OID 0)
-- Dependencies: 4197
-- Name: COLUMN shd900_cobranza_diaria.cod_tipo_inst; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd900_cobranza_diaria.cod_tipo_inst IS 'Código tipo de Institución';


--
-- TOC entry 5544 (class 0 OID 0)
-- Dependencies: 4197
-- Name: COLUMN shd900_cobranza_diaria.cod_inst; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd900_cobranza_diaria.cod_inst IS 'Código de la Institución';


--
-- TOC entry 5545 (class 0 OID 0)
-- Dependencies: 4197
-- Name: COLUMN shd900_cobranza_diaria.cod_dep; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd900_cobranza_diaria.cod_dep IS 'Código de la dependencia';


--
-- TOC entry 5546 (class 0 OID 0)
-- Dependencies: 4197
-- Name: COLUMN shd900_cobranza_diaria.ano_comprobante; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd900_cobranza_diaria.ano_comprobante IS 'Año del comprobante';


--
-- TOC entry 5547 (class 0 OID 0)
-- Dependencies: 4197
-- Name: COLUMN shd900_cobranza_diaria.numero_comprobante; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd900_cobranza_diaria.numero_comprobante IS 'Número del comprobante';


--
-- TOC entry 5548 (class 0 OID 0)
-- Dependencies: 4197
-- Name: COLUMN shd900_cobranza_diaria.cod_partida; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd900_cobranza_diaria.cod_partida IS 'Código de partida';


--
-- TOC entry 5549 (class 0 OID 0)
-- Dependencies: 4197
-- Name: COLUMN shd900_cobranza_diaria.cod_generica; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd900_cobranza_diaria.cod_generica IS 'Código de generica';


--
-- TOC entry 5550 (class 0 OID 0)
-- Dependencies: 4197
-- Name: COLUMN shd900_cobranza_diaria.cod_especifica; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd900_cobranza_diaria.cod_especifica IS 'Código de especifica';


--
-- TOC entry 5551 (class 0 OID 0)
-- Dependencies: 4197
-- Name: COLUMN shd900_cobranza_diaria.cod_sub_espec; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd900_cobranza_diaria.cod_sub_espec IS 'Código de subespecifica';


--
-- TOC entry 5552 (class 0 OID 0)
-- Dependencies: 4197
-- Name: COLUMN shd900_cobranza_diaria.cod_auxiliar; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd900_cobranza_diaria.cod_auxiliar IS 'Código de auxiliar';


--
-- TOC entry 5553 (class 0 OID 0)
-- Dependencies: 4197
-- Name: COLUMN shd900_cobranza_diaria.fecha_comprobante; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd900_cobranza_diaria.fecha_comprobante IS 'Fecha de comprobante';


--
-- TOC entry 5554 (class 0 OID 0)
-- Dependencies: 4197
-- Name: COLUMN shd900_cobranza_diaria.rif_cedula; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd900_cobranza_diaria.rif_cedula IS 'Rif o Cédula de identidad';


--
-- TOC entry 5555 (class 0 OID 0)
-- Dependencies: 4197
-- Name: COLUMN shd900_cobranza_diaria.concepto_comprobante; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd900_cobranza_diaria.concepto_comprobante IS 'Concepto del comprobante
';


--
-- TOC entry 5556 (class 0 OID 0)
-- Dependencies: 4197
-- Name: COLUMN shd900_cobranza_diaria.deuda_anterior; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd900_cobranza_diaria.deuda_anterior IS 'Deuda años anteriores';


--
-- TOC entry 5557 (class 0 OID 0)
-- Dependencies: 4197
-- Name: COLUMN shd900_cobranza_diaria.deuda_vigente; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd900_cobranza_diaria.deuda_vigente IS 'Deuda vigente';


--
-- TOC entry 5558 (class 0 OID 0)
-- Dependencies: 4197
-- Name: COLUMN shd900_cobranza_diaria.monto_recargo; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd900_cobranza_diaria.monto_recargo IS 'Monto de recargo';


--
-- TOC entry 5559 (class 0 OID 0)
-- Dependencies: 4197
-- Name: COLUMN shd900_cobranza_diaria.monto_multa; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd900_cobranza_diaria.monto_multa IS 'Monto multa';


--
-- TOC entry 5560 (class 0 OID 0)
-- Dependencies: 4197
-- Name: COLUMN shd900_cobranza_diaria.monto_intereses; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd900_cobranza_diaria.monto_intereses IS 'Monto intereses';


--
-- TOC entry 5561 (class 0 OID 0)
-- Dependencies: 4197
-- Name: COLUMN shd900_cobranza_diaria.monto_descuento; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd900_cobranza_diaria.monto_descuento IS 'Monto descuento';


--
-- TOC entry 5562 (class 0 OID 0)
-- Dependencies: 4197
-- Name: COLUMN shd900_cobranza_diaria.cod_entidad_deposito; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd900_cobranza_diaria.cod_entidad_deposito IS 'Código de la entidad bancaria por deposito';


--
-- TOC entry 5563 (class 0 OID 0)
-- Dependencies: 4197
-- Name: COLUMN shd900_cobranza_diaria.cod_sucursal_deposito; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd900_cobranza_diaria.cod_sucursal_deposito IS 'Código sucursal bancaria por deposito';


--
-- TOC entry 5564 (class 0 OID 0)
-- Dependencies: 4197
-- Name: COLUMN shd900_cobranza_diaria.cuenta_bancaria_deposito; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd900_cobranza_diaria.cuenta_bancaria_deposito IS 'Cuenta bancaria por deposito';


--
-- TOC entry 5565 (class 0 OID 0)
-- Dependencies: 4197
-- Name: COLUMN shd900_cobranza_diaria.numero_deposito; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd900_cobranza_diaria.numero_deposito IS 'Número de deposito';


--
-- TOC entry 5566 (class 0 OID 0)
-- Dependencies: 4197
-- Name: COLUMN shd900_cobranza_diaria.monto_deposito; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd900_cobranza_diaria.monto_deposito IS 'Monto de deposito';


--
-- TOC entry 5567 (class 0 OID 0)
-- Dependencies: 4197
-- Name: COLUMN shd900_cobranza_diaria.fecha_deposito; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd900_cobranza_diaria.fecha_deposito IS 'Fecha de deposito';


--
-- TOC entry 5568 (class 0 OID 0)
-- Dependencies: 4197
-- Name: COLUMN shd900_cobranza_diaria.cod_entidad_credito; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd900_cobranza_diaria.cod_entidad_credito IS 'Código entidad bancaria por nota de crédito';


--
-- TOC entry 5569 (class 0 OID 0)
-- Dependencies: 4197
-- Name: COLUMN shd900_cobranza_diaria.cod_sucursal_credito; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd900_cobranza_diaria.cod_sucursal_credito IS 'Código sucursal bancaria por nota de crédito';


--
-- TOC entry 5570 (class 0 OID 0)
-- Dependencies: 4197
-- Name: COLUMN shd900_cobranza_diaria.cuenta_bancaria_credito; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd900_cobranza_diaria.cuenta_bancaria_credito IS 'Cuenta bancaria por nota de crédito';


--
-- TOC entry 5571 (class 0 OID 0)
-- Dependencies: 4197
-- Name: COLUMN shd900_cobranza_diaria.numero_nota_credito; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd900_cobranza_diaria.numero_nota_credito IS 'Número de nota de crédito';


--
-- TOC entry 5572 (class 0 OID 0)
-- Dependencies: 4197
-- Name: COLUMN shd900_cobranza_diaria.monto_nota_credito; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd900_cobranza_diaria.monto_nota_credito IS 'Monto nota de crédito';


--
-- TOC entry 5573 (class 0 OID 0)
-- Dependencies: 4197
-- Name: COLUMN shd900_cobranza_diaria.fecha_nota_credito; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd900_cobranza_diaria.fecha_nota_credito IS 'Fecha nota de crédito';


--
-- TOC entry 5574 (class 0 OID 0)
-- Dependencies: 4197
-- Name: COLUMN shd900_cobranza_diaria.cod_entidad_cheque; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd900_cobranza_diaria.cod_entidad_cheque IS 'Código entidad bancaria por cheque';


--
-- TOC entry 5575 (class 0 OID 0)
-- Dependencies: 4197
-- Name: COLUMN shd900_cobranza_diaria.cod_sucursal_cheque; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd900_cobranza_diaria.cod_sucursal_cheque IS 'Código sucursal bancaria por cheque';


--
-- TOC entry 5576 (class 0 OID 0)
-- Dependencies: 4197
-- Name: COLUMN shd900_cobranza_diaria.cuenta_bancaria_cheque; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd900_cobranza_diaria.cuenta_bancaria_cheque IS 'Cuenta bancaria de cheque';


--
-- TOC entry 5577 (class 0 OID 0)
-- Dependencies: 4197
-- Name: COLUMN shd900_cobranza_diaria.numero_cheque; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd900_cobranza_diaria.numero_cheque IS 'Número de cheque';


--
-- TOC entry 5578 (class 0 OID 0)
-- Dependencies: 4197
-- Name: COLUMN shd900_cobranza_diaria.monto_cheque; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd900_cobranza_diaria.monto_cheque IS 'Monto del cheque';


--
-- TOC entry 5579 (class 0 OID 0)
-- Dependencies: 4197
-- Name: COLUMN shd900_cobranza_diaria.fecha_cheque; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd900_cobranza_diaria.fecha_cheque IS 'Fecha de cheque';


--
-- TOC entry 5580 (class 0 OID 0)
-- Dependencies: 4197
-- Name: COLUMN shd900_cobranza_diaria.monto_efectivo; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd900_cobranza_diaria.monto_efectivo IS 'Monto efectivo';


--
-- TOC entry 5581 (class 0 OID 0)
-- Dependencies: 4197
-- Name: COLUMN shd900_cobranza_diaria.condicion_documento; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd900_cobranza_diaria.condicion_documento IS 'Condición del documento
1.- Activo
2.- Anulado
';


--
-- TOC entry 5582 (class 0 OID 0)
-- Dependencies: 4197
-- Name: COLUMN shd900_cobranza_diaria.fecha_registro; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd900_cobranza_diaria.fecha_registro IS 'Fecha de registro
';


--
-- TOC entry 5583 (class 0 OID 0)
-- Dependencies: 4197
-- Name: COLUMN shd900_cobranza_diaria.username_registro; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd900_cobranza_diaria.username_registro IS 'Operador que registro el cobro';


--
-- TOC entry 5584 (class 0 OID 0)
-- Dependencies: 4197
-- Name: COLUMN shd900_cobranza_diaria.ano_anulacion; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd900_cobranza_diaria.ano_anulacion IS 'año de anulación';


--
-- TOC entry 5585 (class 0 OID 0)
-- Dependencies: 4197
-- Name: COLUMN shd900_cobranza_diaria.numero_anulacion; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd900_cobranza_diaria.numero_anulacion IS 'Número de anulación
';


--
-- TOC entry 5586 (class 0 OID 0)
-- Dependencies: 4197
-- Name: COLUMN shd900_cobranza_diaria.fecha_anulacion; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd900_cobranza_diaria.fecha_anulacion IS 'Fecha de anulación
';


--
-- TOC entry 5587 (class 0 OID 0)
-- Dependencies: 4197
-- Name: COLUMN shd900_cobranza_diaria.username_anulacion; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd900_cobranza_diaria.username_anulacion IS 'Operador que anulo el cobro';


--
-- TOC entry 5588 (class 0 OID 0)
-- Dependencies: 4197
-- Name: COLUMN shd900_cobranza_diaria.rif_ci_cobrador; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd900_cobranza_diaria.rif_ci_cobrador IS 'Rif o Cédula de identidad';


--
-- TOC entry 4245 (class 1259 OID 502976)
-- Dependencies: 3
-- Name: shd900_cobranza_diaria_planillas; Type: TABLE; Schema: public; Owner: sisap; Tablespace:
--

CREATE TABLE shd900_cobranza_diaria_planillas (
    cod_presi integer NOT NULL,
    cod_entidad integer NOT NULL,
    cod_tipo_inst integer NOT NULL,
    cod_inst integer NOT NULL,
    cod_dep integer NOT NULL,
    ano_comprobante integer NOT NULL,
    numero_comprobante integer NOT NULL,
    ano integer NOT NULL,
    mes integer NOT NULL,
    numero_planilla integer NOT NULL
);


ALTER TABLE public.shd900_cobranza_diaria_planillas OWNER TO sisap;

--
-- TOC entry 5589 (class 0 OID 0)
-- Dependencies: 4245
-- Name: TABLE shd900_cobranza_diaria_planillas; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON TABLE shd900_cobranza_diaria_planillas IS 'Registra las planillas canceladas según el comprobante de ingreso';


--
-- TOC entry 5590 (class 0 OID 0)
-- Dependencies: 4245
-- Name: COLUMN shd900_cobranza_diaria_planillas.cod_presi; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd900_cobranza_diaria_planillas.cod_presi IS 'Código de la presidencia';


--
-- TOC entry 5591 (class 0 OID 0)
-- Dependencies: 4245
-- Name: COLUMN shd900_cobranza_diaria_planillas.cod_entidad; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd900_cobranza_diaria_planillas.cod_entidad IS 'Código de la entidad';


--
-- TOC entry 5592 (class 0 OID 0)
-- Dependencies: 4245
-- Name: COLUMN shd900_cobranza_diaria_planillas.cod_tipo_inst; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd900_cobranza_diaria_planillas.cod_tipo_inst IS 'Código tipo de Institución';


--
-- TOC entry 5593 (class 0 OID 0)
-- Dependencies: 4245
-- Name: COLUMN shd900_cobranza_diaria_planillas.cod_inst; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd900_cobranza_diaria_planillas.cod_inst IS 'Código de Institución';


--
-- TOC entry 5594 (class 0 OID 0)
-- Dependencies: 4245
-- Name: COLUMN shd900_cobranza_diaria_planillas.cod_dep; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd900_cobranza_diaria_planillas.cod_dep IS 'Código de dependencia';


--
-- TOC entry 5595 (class 0 OID 0)
-- Dependencies: 4245
-- Name: COLUMN shd900_cobranza_diaria_planillas.ano_comprobante; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd900_cobranza_diaria_planillas.ano_comprobante IS 'Año de comprobante';


--
-- TOC entry 5596 (class 0 OID 0)
-- Dependencies: 4245
-- Name: COLUMN shd900_cobranza_diaria_planillas.numero_comprobante; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd900_cobranza_diaria_planillas.numero_comprobante IS 'Número de comprobante';


--
-- TOC entry 5597 (class 0 OID 0)
-- Dependencies: 4245
-- Name: COLUMN shd900_cobranza_diaria_planillas.ano; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd900_cobranza_diaria_planillas.ano IS 'Año de la planilla';


--
-- TOC entry 5598 (class 0 OID 0)
-- Dependencies: 4245
-- Name: COLUMN shd900_cobranza_diaria_planillas.mes; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd900_cobranza_diaria_planillas.mes IS 'Mes de planilla';


--
-- TOC entry 5599 (class 0 OID 0)
-- Dependencies: 4245
-- Name: COLUMN shd900_cobranza_diaria_planillas.numero_planilla; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd900_cobranza_diaria_planillas.numero_planilla IS 'Número de planilla';


--
-- TOC entry 4196 (class 1259 OID 502313)
-- Dependencies: 3
-- Name: shd900_cobranza_numero; Type: TABLE; Schema: public; Owner: sisap; Tablespace:
--

CREATE TABLE shd900_cobranza_numero (
    cod_presi integer NOT NULL,
    cod_entidad integer NOT NULL,
    cod_tipo_inst integer NOT NULL,
    cod_inst integer NOT NULL,
    cod_dep integer NOT NULL,
    ano_comprobante integer NOT NULL,
    numero_comprobante integer NOT NULL,
    situacion integer NOT NULL
);


ALTER TABLE public.shd900_cobranza_numero OWNER TO sisap;

--
-- TOC entry 5600 (class 0 OID 0)
-- Dependencies: 4196
-- Name: TABLE shd900_cobranza_numero; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON TABLE shd900_cobranza_numero IS 'Control de número de comprobantes de otros ingresos';


--
-- TOC entry 5601 (class 0 OID 0)
-- Dependencies: 4196
-- Name: COLUMN shd900_cobranza_numero.cod_presi; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd900_cobranza_numero.cod_presi IS 'Código de la presidencia';


--
-- TOC entry 5602 (class 0 OID 0)
-- Dependencies: 4196
-- Name: COLUMN shd900_cobranza_numero.cod_entidad; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd900_cobranza_numero.cod_entidad IS 'Código de la entidad';


--
-- TOC entry 5603 (class 0 OID 0)
-- Dependencies: 4196
-- Name: COLUMN shd900_cobranza_numero.cod_tipo_inst; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd900_cobranza_numero.cod_tipo_inst IS 'Código tipo de Institución';


--
-- TOC entry 5604 (class 0 OID 0)
-- Dependencies: 4196
-- Name: COLUMN shd900_cobranza_numero.cod_inst; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd900_cobranza_numero.cod_inst IS 'Código de Institución';


--
-- TOC entry 5605 (class 0 OID 0)
-- Dependencies: 4196
-- Name: COLUMN shd900_cobranza_numero.cod_dep; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd900_cobranza_numero.cod_dep IS 'Código de dependencia';


--
-- TOC entry 5606 (class 0 OID 0)
-- Dependencies: 4196
-- Name: COLUMN shd900_cobranza_numero.ano_comprobante; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd900_cobranza_numero.ano_comprobante IS 'Año del comprobante';


--
-- TOC entry 5607 (class 0 OID 0)
-- Dependencies: 4196
-- Name: COLUMN shd900_cobranza_numero.numero_comprobante; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd900_cobranza_numero.numero_comprobante IS 'Número de comprobante';


--
-- TOC entry 5608 (class 0 OID 0)
-- Dependencies: 4196
-- Name: COLUMN shd900_cobranza_numero.situacion; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd900_cobranza_numero.situacion IS 'Situación:
1.- Sin utilizar
2.- Seleccionado
3.- Emitida
4.- Anulada
5.- Congelada';


--
-- TOC entry 4194 (class 1259 OID 502297)
-- Dependencies: 3
-- Name: shd900_planillas_deuda_cobro_cuerpo; Type: TABLE; Schema: public; Owner: sisap; Tablespace:
--

CREATE TABLE shd900_planillas_deuda_cobro_cuerpo (
    cod_presi integer NOT NULL,
    cod_entidad integer NOT NULL,
    cod_tipo_inst integer NOT NULL,
    cod_inst integer NOT NULL,
    cod_dep integer NOT NULL,
    cod_partida integer NOT NULL,
    cod_generica integer NOT NULL,
    cod_especifica integer NOT NULL,
    cod_sub_espec integer NOT NULL,
    cod_auxiliar integer NOT NULL,
    rif_cedula character varying(20) NOT NULL,
    cod_numero_catastral_placas character varying(20) NOT NULL,
    deuda_ano_anterior numeric(26,2)
);


ALTER TABLE public.shd900_planillas_deuda_cobro_cuerpo OWNER TO sisap;

--
-- TOC entry 5609 (class 0 OID 0)
-- Dependencies: 4194
-- Name: TABLE shd900_planillas_deuda_cobro_cuerpo; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON TABLE shd900_planillas_deuda_cobro_cuerpo IS 'Registro estadistico de Deudas y Cobros';


--
-- TOC entry 5610 (class 0 OID 0)
-- Dependencies: 4194
-- Name: COLUMN shd900_planillas_deuda_cobro_cuerpo.cod_presi; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd900_planillas_deuda_cobro_cuerpo.cod_presi IS 'Código de la presidencia';


--
-- TOC entry 5611 (class 0 OID 0)
-- Dependencies: 4194
-- Name: COLUMN shd900_planillas_deuda_cobro_cuerpo.cod_entidad; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd900_planillas_deuda_cobro_cuerpo.cod_entidad IS 'Código de la entidad federal';


--
-- TOC entry 5612 (class 0 OID 0)
-- Dependencies: 4194
-- Name: COLUMN shd900_planillas_deuda_cobro_cuerpo.cod_tipo_inst; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd900_planillas_deuda_cobro_cuerpo.cod_tipo_inst IS 'Código tipo de Institución';


--
-- TOC entry 5613 (class 0 OID 0)
-- Dependencies: 4194
-- Name: COLUMN shd900_planillas_deuda_cobro_cuerpo.cod_inst; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd900_planillas_deuda_cobro_cuerpo.cod_inst IS 'Código de Institución';


--
-- TOC entry 5614 (class 0 OID 0)
-- Dependencies: 4194
-- Name: COLUMN shd900_planillas_deuda_cobro_cuerpo.cod_dep; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd900_planillas_deuda_cobro_cuerpo.cod_dep IS 'Código de la dependencia';


--
-- TOC entry 5615 (class 0 OID 0)
-- Dependencies: 4194
-- Name: COLUMN shd900_planillas_deuda_cobro_cuerpo.cod_partida; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd900_planillas_deuda_cobro_cuerpo.cod_partida IS 'Código de la partida';


--
-- TOC entry 5616 (class 0 OID 0)
-- Dependencies: 4194
-- Name: COLUMN shd900_planillas_deuda_cobro_cuerpo.cod_generica; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd900_planillas_deuda_cobro_cuerpo.cod_generica IS 'Código de genérica';


--
-- TOC entry 5617 (class 0 OID 0)
-- Dependencies: 4194
-- Name: COLUMN shd900_planillas_deuda_cobro_cuerpo.cod_especifica; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd900_planillas_deuda_cobro_cuerpo.cod_especifica IS 'Código de especifica';


--
-- TOC entry 5618 (class 0 OID 0)
-- Dependencies: 4194
-- Name: COLUMN shd900_planillas_deuda_cobro_cuerpo.cod_sub_espec; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd900_planillas_deuda_cobro_cuerpo.cod_sub_espec IS 'Código Subespecifica';


--
-- TOC entry 5619 (class 0 OID 0)
-- Dependencies: 4194
-- Name: COLUMN shd900_planillas_deuda_cobro_cuerpo.cod_auxiliar; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd900_planillas_deuda_cobro_cuerpo.cod_auxiliar IS 'Código de auxiliar';


--
-- TOC entry 5620 (class 0 OID 0)
-- Dependencies: 4194
-- Name: COLUMN shd900_planillas_deuda_cobro_cuerpo.rif_cedula; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd900_planillas_deuda_cobro_cuerpo.rif_cedula IS 'RIF o Cédula de Identidad';


--
-- TOC entry 5621 (class 0 OID 0)
-- Dependencies: 4194
-- Name: COLUMN shd900_planillas_deuda_cobro_cuerpo.cod_numero_catastral_placas; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd900_planillas_deuda_cobro_cuerpo.cod_numero_catastral_placas IS 'Código número de ficha catastra o Placas del Vehículo

Se utiliza solamente en estos casos los demás tipos de ingresos no lo requieren';


--
-- TOC entry 4195 (class 1259 OID 502302)
-- Dependencies: 4855 3
-- Name: shd900_planillas_deuda_cobro_detalles; Type: TABLE; Schema: public; Owner: sisap; Tablespace:
--

CREATE TABLE shd900_planillas_deuda_cobro_detalles (
    cod_presi integer NOT NULL,
    cod_entidad integer NOT NULL,
    cod_tipo_inst integer NOT NULL,
    cod_inst integer NOT NULL,
    cod_dep integer NOT NULL,
    cod_partida integer NOT NULL,
    cod_generica integer NOT NULL,
    cod_especifica integer NOT NULL,
    cod_sub_espec integer NOT NULL,
    cod_auxiliar integer NOT NULL,
    rif_cedula character varying(20) NOT NULL,
    cod_numero_catastral_placas character varying(20) NOT NULL,
    ano integer NOT NULL,
    mes integer NOT NULL,
    numero_planilla integer NOT NULL,
    deuda_vigente numeric(26,2),
    monto_recargo numeric(26,2),
    monto_multa numeric(26,2),
    monto_intereses numeric(26,2),
    monto_descuento numeric(26,2),
    cancelado integer DEFAULT 2 NOT NULL,
    fecha_emision date
);


ALTER TABLE public.shd900_planillas_deuda_cobro_detalles OWNER TO sisap;

--
-- TOC entry 5622 (class 0 OID 0)
-- Dependencies: 4195
-- Name: TABLE shd900_planillas_deuda_cobro_detalles; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON TABLE shd900_planillas_deuda_cobro_detalles IS 'Registro estadistico de Deudas y Cobros';


--
-- TOC entry 5623 (class 0 OID 0)
-- Dependencies: 4195
-- Name: COLUMN shd900_planillas_deuda_cobro_detalles.cod_presi; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd900_planillas_deuda_cobro_detalles.cod_presi IS 'Código de la presidencia';


--
-- TOC entry 5624 (class 0 OID 0)
-- Dependencies: 4195
-- Name: COLUMN shd900_planillas_deuda_cobro_detalles.cod_entidad; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd900_planillas_deuda_cobro_detalles.cod_entidad IS 'Código de la entidad federal';


--
-- TOC entry 5625 (class 0 OID 0)
-- Dependencies: 4195
-- Name: COLUMN shd900_planillas_deuda_cobro_detalles.cod_tipo_inst; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd900_planillas_deuda_cobro_detalles.cod_tipo_inst IS 'Código tipo de Institución';


--
-- TOC entry 5626 (class 0 OID 0)
-- Dependencies: 4195
-- Name: COLUMN shd900_planillas_deuda_cobro_detalles.cod_inst; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd900_planillas_deuda_cobro_detalles.cod_inst IS 'Código de Institución';


--
-- TOC entry 5627 (class 0 OID 0)
-- Dependencies: 4195
-- Name: COLUMN shd900_planillas_deuda_cobro_detalles.cod_dep; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd900_planillas_deuda_cobro_detalles.cod_dep IS 'Código de la dependencia';


--
-- TOC entry 5628 (class 0 OID 0)
-- Dependencies: 4195
-- Name: COLUMN shd900_planillas_deuda_cobro_detalles.cod_partida; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd900_planillas_deuda_cobro_detalles.cod_partida IS 'Código de la partida';


--
-- TOC entry 5629 (class 0 OID 0)
-- Dependencies: 4195
-- Name: COLUMN shd900_planillas_deuda_cobro_detalles.cod_generica; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd900_planillas_deuda_cobro_detalles.cod_generica IS 'Código de genérica';


--
-- TOC entry 5630 (class 0 OID 0)
-- Dependencies: 4195
-- Name: COLUMN shd900_planillas_deuda_cobro_detalles.cod_especifica; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd900_planillas_deuda_cobro_detalles.cod_especifica IS 'Código de especifica';


--
-- TOC entry 5631 (class 0 OID 0)
-- Dependencies: 4195
-- Name: COLUMN shd900_planillas_deuda_cobro_detalles.cod_sub_espec; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd900_planillas_deuda_cobro_detalles.cod_sub_espec IS 'Código Subespecifica';


--
-- TOC entry 5632 (class 0 OID 0)
-- Dependencies: 4195
-- Name: COLUMN shd900_planillas_deuda_cobro_detalles.cod_auxiliar; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd900_planillas_deuda_cobro_detalles.cod_auxiliar IS 'Código de auxiliar';


--
-- TOC entry 5633 (class 0 OID 0)
-- Dependencies: 4195
-- Name: COLUMN shd900_planillas_deuda_cobro_detalles.rif_cedula; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd900_planillas_deuda_cobro_detalles.rif_cedula IS 'RIF o Cédula de Identidad';


--
-- TOC entry 5634 (class 0 OID 0)
-- Dependencies: 4195
-- Name: COLUMN shd900_planillas_deuda_cobro_detalles.cod_numero_catastral_placas; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd900_planillas_deuda_cobro_detalles.cod_numero_catastral_placas IS 'Código número de ficha catastra o Placas del Vehículo

Se utiliza solamente en estos casos los demás tipos de ingresos no lo requieren';


--
-- TOC entry 5635 (class 0 OID 0)
-- Dependencies: 4195
-- Name: COLUMN shd900_planillas_deuda_cobro_detalles.ano; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd900_planillas_deuda_cobro_detalles.ano IS 'Año';


--
-- TOC entry 5636 (class 0 OID 0)
-- Dependencies: 4195
-- Name: COLUMN shd900_planillas_deuda_cobro_detalles.mes; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd900_planillas_deuda_cobro_detalles.mes IS 'Mes';


--
-- TOC entry 5637 (class 0 OID 0)
-- Dependencies: 4195
-- Name: COLUMN shd900_planillas_deuda_cobro_detalles.numero_planilla; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd900_planillas_deuda_cobro_detalles.numero_planilla IS 'Número de planilla de liquidación previa';


--
-- TOC entry 5638 (class 0 OID 0)
-- Dependencies: 4195
-- Name: COLUMN shd900_planillas_deuda_cobro_detalles.deuda_vigente; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd900_planillas_deuda_cobro_detalles.deuda_vigente IS 'Deuda vigente';


--
-- TOC entry 5639 (class 0 OID 0)
-- Dependencies: 4195
-- Name: COLUMN shd900_planillas_deuda_cobro_detalles.monto_recargo; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd900_planillas_deuda_cobro_detalles.monto_recargo IS 'Monto por recargo';


--
-- TOC entry 5640 (class 0 OID 0)
-- Dependencies: 4195
-- Name: COLUMN shd900_planillas_deuda_cobro_detalles.monto_multa; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd900_planillas_deuda_cobro_detalles.monto_multa IS 'Monto por multa';


--
-- TOC entry 5641 (class 0 OID 0)
-- Dependencies: 4195
-- Name: COLUMN shd900_planillas_deuda_cobro_detalles.monto_intereses; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd900_planillas_deuda_cobro_detalles.monto_intereses IS 'Moonto por intereses';


--
-- TOC entry 5642 (class 0 OID 0)
-- Dependencies: 4195
-- Name: COLUMN shd900_planillas_deuda_cobro_detalles.monto_descuento; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd900_planillas_deuda_cobro_detalles.monto_descuento IS 'Descuento';


--
-- TOC entry 5643 (class 0 OID 0)
-- Dependencies: 4195
-- Name: COLUMN shd900_planillas_deuda_cobro_detalles.cancelado; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd900_planillas_deuda_cobro_detalles.cancelado IS 'Cancelado ?
1.- Si
2.- No
Pregunta si el recibo esta cancelado o no esta cancelado';


--
-- TOC entry 5644 (class 0 OID 0)
-- Dependencies: 4195
-- Name: COLUMN shd900_planillas_deuda_cobro_detalles.fecha_emision; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd900_planillas_deuda_cobro_detalles.fecha_emision IS 'Fecha de emisión es igual a la fecha de actualización de las planillas';


--
-- TOC entry 4198 (class 1259 OID 502326)
-- Dependencies: 4856 3
-- Name: shd950_solvencia; Type: TABLE; Schema: public; Owner: sisap; Tablespace:
--

CREATE TABLE shd950_solvencia (
    cod_presi integer NOT NULL,
    cod_entidad integer NOT NULL,
    cod_tipo_inst integer NOT NULL,
    cod_inst integer NOT NULL,
    cod_dep integer NOT NULL,
    ano integer NOT NULL,
    numero_solvencia integer NOT NULL,
    rif_cedula character varying(20) NOT NULL,
    fecha_expedicion date NOT NULL,
    valida_hasta date NOT NULL,
    objeto_solvencia integer NOT NULL,
    monto_solvencia numeric(26,2) NOT NULL,
    observaciones text,
    condicion_actividad integer DEFAULT 1,
    fecha_registro date,
    username_registro character varying(60),
    fecha_anulacion date,
    username_anulacion character varying(60)
);


ALTER TABLE public.shd950_solvencia OWNER TO sisap;

--
-- TOC entry 5645 (class 0 OID 0)
-- Dependencies: 4198
-- Name: TABLE shd950_solvencia; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON TABLE shd950_solvencia IS 'Registro de solvencias';


--
-- TOC entry 5646 (class 0 OID 0)
-- Dependencies: 4198
-- Name: COLUMN shd950_solvencia.cod_presi; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd950_solvencia.cod_presi IS 'Código de la presidencia
';


--
-- TOC entry 5647 (class 0 OID 0)
-- Dependencies: 4198
-- Name: COLUMN shd950_solvencia.cod_entidad; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd950_solvencia.cod_entidad IS 'Código de la entidad federal';


--
-- TOC entry 5648 (class 0 OID 0)
-- Dependencies: 4198
-- Name: COLUMN shd950_solvencia.cod_tipo_inst; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd950_solvencia.cod_tipo_inst IS 'Código tipo institución';


--
-- TOC entry 5649 (class 0 OID 0)
-- Dependencies: 4198
-- Name: COLUMN shd950_solvencia.cod_inst; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd950_solvencia.cod_inst IS 'Código de la Institución';


--
-- TOC entry 5650 (class 0 OID 0)
-- Dependencies: 4198
-- Name: COLUMN shd950_solvencia.cod_dep; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd950_solvencia.cod_dep IS 'Código de la dependencia';


--
-- TOC entry 5651 (class 0 OID 0)
-- Dependencies: 4198
-- Name: COLUMN shd950_solvencia.ano; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd950_solvencia.ano IS 'Año de solvencia';


--
-- TOC entry 5652 (class 0 OID 0)
-- Dependencies: 4198
-- Name: COLUMN shd950_solvencia.numero_solvencia; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd950_solvencia.numero_solvencia IS 'Número de solvencia';


--
-- TOC entry 5653 (class 0 OID 0)
-- Dependencies: 4198
-- Name: COLUMN shd950_solvencia.rif_cedula; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd950_solvencia.rif_cedula IS 'Rif o Cédula de identidad del contribuyente';


--
-- TOC entry 5654 (class 0 OID 0)
-- Dependencies: 4198
-- Name: COLUMN shd950_solvencia.fecha_expedicion; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd950_solvencia.fecha_expedicion IS 'Fecha de expedición';


--
-- TOC entry 5655 (class 0 OID 0)
-- Dependencies: 4198
-- Name: COLUMN shd950_solvencia.valida_hasta; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd950_solvencia.valida_hasta IS 'Fecha de expiración de la solvencia';


--
-- TOC entry 5656 (class 0 OID 0)
-- Dependencies: 4198
-- Name: COLUMN shd950_solvencia.objeto_solvencia; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd950_solvencia.objeto_solvencia IS 'Objeto de la solvencia
';


--
-- TOC entry 5657 (class 0 OID 0)
-- Dependencies: 4198
-- Name: COLUMN shd950_solvencia.monto_solvencia; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd950_solvencia.monto_solvencia IS 'Monto de la solvencia';


--
-- TOC entry 5658 (class 0 OID 0)
-- Dependencies: 4198
-- Name: COLUMN shd950_solvencia.observaciones; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd950_solvencia.observaciones IS 'Observaciones
';


--
-- TOC entry 5659 (class 0 OID 0)
-- Dependencies: 4198
-- Name: COLUMN shd950_solvencia.condicion_actividad; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd950_solvencia.condicion_actividad IS 'Condición de actividad
1.- Activa
2.- Anulada

';


--
-- TOC entry 5660 (class 0 OID 0)
-- Dependencies: 4198
-- Name: COLUMN shd950_solvencia.fecha_registro; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd950_solvencia.fecha_registro IS 'Fecha de registro';


--
-- TOC entry 5661 (class 0 OID 0)
-- Dependencies: 4198
-- Name: COLUMN shd950_solvencia.username_registro; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd950_solvencia.username_registro IS 'Operdor que registro la solvencia';


--
-- TOC entry 5662 (class 0 OID 0)
-- Dependencies: 4198
-- Name: COLUMN shd950_solvencia.fecha_anulacion; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd950_solvencia.fecha_anulacion IS 'Fecha de anulación
';


--
-- TOC entry 5663 (class 0 OID 0)
-- Dependencies: 4198
-- Name: COLUMN shd950_solvencia.username_anulacion; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd950_solvencia.username_anulacion IS 'Operador que anulo la solvencia';


--
-- TOC entry 4199 (class 1259 OID 502334)
-- Dependencies: 3
-- Name: shd950_solvencia_monto; Type: TABLE; Schema: public; Owner: sisap; Tablespace:
--

CREATE TABLE shd950_solvencia_monto (
    cod_presi integer NOT NULL,
    cod_entidad integer NOT NULL,
    cod_tipo_inst integer NOT NULL,
    cod_inst integer NOT NULL,
    cod_dep integer NOT NULL,
    monto_solvencia numeric(26,2) NOT NULL
);


ALTER TABLE public.shd950_solvencia_monto OWNER TO sisap;

--
-- TOC entry 5664 (class 0 OID 0)
-- Dependencies: 4199
-- Name: TABLE shd950_solvencia_monto; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON TABLE shd950_solvencia_monto IS 'Registra el monto de la solvencia';


--
-- TOC entry 5665 (class 0 OID 0)
-- Dependencies: 4199
-- Name: COLUMN shd950_solvencia_monto.cod_presi; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd950_solvencia_monto.cod_presi IS 'Código de la presidencia';


--
-- TOC entry 5666 (class 0 OID 0)
-- Dependencies: 4199
-- Name: COLUMN shd950_solvencia_monto.cod_entidad; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd950_solvencia_monto.cod_entidad IS 'Código de la entidad federal';


--
-- TOC entry 5667 (class 0 OID 0)
-- Dependencies: 4199
-- Name: COLUMN shd950_solvencia_monto.cod_tipo_inst; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd950_solvencia_monto.cod_tipo_inst IS 'Código tipo de Institución';


--
-- TOC entry 5668 (class 0 OID 0)
-- Dependencies: 4199
-- Name: COLUMN shd950_solvencia_monto.cod_inst; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd950_solvencia_monto.cod_inst IS 'Código de la Institución';


--
-- TOC entry 5669 (class 0 OID 0)
-- Dependencies: 4199
-- Name: COLUMN shd950_solvencia_monto.cod_dep; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd950_solvencia_monto.cod_dep IS 'Código de la dependencia';


--
-- TOC entry 5670 (class 0 OID 0)
-- Dependencies: 4199
-- Name: COLUMN shd950_solvencia_monto.monto_solvencia; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd950_solvencia_monto.monto_solvencia IS 'Monto fijo de la solvencia';


--
-- TOC entry 4200 (class 1259 OID 502339)
-- Dependencies: 3
-- Name: shd950_solvencia_numero; Type: TABLE; Schema: public; Owner: sisap; Tablespace:
--

CREATE TABLE shd950_solvencia_numero (
    cod_presi integer NOT NULL,
    cod_entidad integer NOT NULL,
    cod_tipo_inst integer NOT NULL,
    cod_inst integer NOT NULL,
    cod_dep integer NOT NULL,
    ano integer NOT NULL,
    numero_solvencia integer NOT NULL,
    situacion integer NOT NULL
);


ALTER TABLE public.shd950_solvencia_numero OWNER TO sisap;

--
-- TOC entry 5671 (class 0 OID 0)
-- Dependencies: 4200
-- Name: TABLE shd950_solvencia_numero; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON TABLE shd950_solvencia_numero IS 'Controla el número de las solvencias
';


--
-- TOC entry 5672 (class 0 OID 0)
-- Dependencies: 4200
-- Name: COLUMN shd950_solvencia_numero.cod_presi; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd950_solvencia_numero.cod_presi IS 'Código de la presidencia';


--
-- TOC entry 5673 (class 0 OID 0)
-- Dependencies: 4200
-- Name: COLUMN shd950_solvencia_numero.cod_entidad; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd950_solvencia_numero.cod_entidad IS 'Código de la entidad federal';


--
-- TOC entry 5674 (class 0 OID 0)
-- Dependencies: 4200
-- Name: COLUMN shd950_solvencia_numero.cod_tipo_inst; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd950_solvencia_numero.cod_tipo_inst IS 'Código tipo de Institución';


--
-- TOC entry 5675 (class 0 OID 0)
-- Dependencies: 4200
-- Name: COLUMN shd950_solvencia_numero.cod_inst; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd950_solvencia_numero.cod_inst IS 'Código de la Institución';


--
-- TOC entry 5676 (class 0 OID 0)
-- Dependencies: 4200
-- Name: COLUMN shd950_solvencia_numero.cod_dep; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd950_solvencia_numero.cod_dep IS 'Código de la dependencia';


--
-- TOC entry 5677 (class 0 OID 0)
-- Dependencies: 4200
-- Name: COLUMN shd950_solvencia_numero.ano; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd950_solvencia_numero.ano IS 'Año';


--
-- TOC entry 5678 (class 0 OID 0)
-- Dependencies: 4200
-- Name: COLUMN shd950_solvencia_numero.numero_solvencia; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd950_solvencia_numero.numero_solvencia IS 'Número de solvencia';


--
-- TOC entry 5679 (class 0 OID 0)
-- Dependencies: 4200
-- Name: COLUMN shd950_solvencia_numero.situacion; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd950_solvencia_numero.situacion IS 'Situación
1.- Sin utilizar
2.- Seleccionado
3.- Emitida
4.- Anulada
5.- Congelada
';


--
-- TOC entry 4208 (class 1259 OID 502411)
-- Dependencies: 4598 3
-- Name: v_consulta_ingreso; Type: VIEW; Schema: public; Owner: sisap
--

CREATE VIEW v_consulta_ingreso AS
    ((SELECT (((a.cod_grupo)::text || mascara_2(a.cod_partida)))::integer AS cod_partida_completo, a.cod_grupo, a.cod_partida, a.cod_generica, a.cod_especifica, a.cod_sub_espec, a.cod_auxiliar, quitar_acentos(a.descripcion) AS denominacion, quitar_acentos(a.concepto) AS concepto, ((((((((((a.cod_grupo)::text || mascara_2(a.cod_partida)) || mascara_2(a.cod_generica)) || mascara_2(a.cod_especifica)) || mascara_2(a.cod_sub_espec)) || mascara_2(a.cod_auxiliar)) || ', '::text) || quitar_acentos(a.descripcion)) || ' '::text) || quitar_acentos(a.concepto)) AS denominacion_busqueda FROM cfpd01_auxiliar a WHERE (a.cod_grupo = 3) GROUP BY a.cod_grupo, a.cod_partida, a.cod_generica, a.cod_especifica, a.cod_sub_espec, a.cod_auxiliar, a.descripcion, a.concepto ORDER BY a.cod_grupo, a.cod_partida, a.cod_generica, a.cod_especifica, a.cod_sub_espec, a.cod_auxiliar) UNION (SELECT (((b.cod_grupo)::text || mascara_2(b.cod_partida)))::integer AS cod_partida_completo, b.cod_grupo, b.cod_partida, b.cod_generica, b.cod_especifica, b.cod_sub_espec, 0 AS cod_auxiliar, quitar_acentos(b.descripcion) AS denominacion, quitar_acentos(b.concepto) AS concepto, (((((((((b.cod_grupo || mascara_2(b.cod_partida)) || mascara_2(b.cod_generica)) || mascara_2(b.cod_especifica)) || mascara_2(b.cod_sub_espec)) || mascara_2(0)) || ', '::text) || quitar_acentos(b.descripcion)) || ', '::text) || quitar_acentos(b.concepto)) AS denominacion_busqueda FROM cfpd01_sub_espec b WHERE (b.cod_grupo = 3) GROUP BY b.cod_grupo, b.cod_partida, b.cod_generica, b.cod_especifica, b.cod_sub_espec, b.descripcion, b.concepto ORDER BY b.cod_grupo, b.cod_partida, b.cod_generica, b.cod_especifica, b.cod_sub_espec)) UNION (SELECT (((c.cod_grupo)::text || mascara_2(c.cod_partida)))::integer AS cod_partida_completo, c.cod_grupo, c.cod_partida, c.cod_generica, c.cod_especifica, 0 AS cod_sub_espec, 0 AS cod_auxiliar, quitar_acentos(c.descripcion) AS denominacion, quitar_acentos(c.concepto) AS concepto, ((((((((c.cod_grupo || mascara_2(c.cod_partida)) || mascara_2(c.cod_generica)) || mascara_2(c.cod_especifica)) || '0000'::text) || ', '::text) || quitar_acentos(c.descripcion)) || ', '::text) || quitar_acentos(c.concepto)) AS denominacion_busqueda FROM cfpd01_especifica c WHERE (c.cod_grupo = 3) GROUP BY c.cod_grupo, c.cod_partida, c.cod_generica, c.cod_especifica, c.descripcion, c.concepto ORDER BY c.cod_grupo, c.cod_partida, c.cod_generica, c.cod_especifica);


ALTER TABLE public.v_consulta_ingreso OWNER TO sisap;

--
-- TOC entry 4201 (class 1259 OID 502354)
-- Dependencies: 4591 3
-- Name: v_shd001_registro_contribuyentes; Type: VIEW; Schema: public; Owner: sisap
--

CREATE VIEW v_shd001_registro_contribuyentes AS
    SELECT a.rif_cedula, a.personalidad_juridica, a.razon_social_nombres, a.fecha_inscripcion, a.nacionalidad, a.estado_civil, a.profesion, (SELECT b.denominacion FROM cnmd06_profesiones b WHERE (a.profesion = b.cod_profesion)) AS deno_profesion, a.cod_pais, (SELECT c.denominacion FROM cugd01_republica c WHERE (a.cod_pais = c.cod_republica)) AS deno_pais, a.cod_estado, (SELECT d.denominacion FROM cugd01_estados d WHERE ((a.cod_pais = d.cod_republica) AND (a.cod_estado = d.cod_estado))) AS deno_estado, a.cod_municipio, (SELECT e.denominacion FROM cugd01_municipios e WHERE (((a.cod_pais = e.cod_republica) AND (a.cod_estado = e.cod_estado)) AND (a.cod_municipio = e.cod_municipio))) AS deno_municipio, a.cod_parroquia, (SELECT f.denominacion FROM cugd01_parroquias f WHERE ((((a.cod_pais = f.cod_republica) AND (a.cod_estado = f.cod_estado)) AND (a.cod_municipio = f.cod_municipio)) AND (a.cod_parroquia = f.cod_parroquia))) AS deno_parroquia, a.cod_centro_poblado, (SELECT g.denominacion FROM cugd01_centros_poblados g WHERE (((((a.cod_pais = g.cod_republica) AND (a.cod_estado = g.cod_estado)) AND (a.cod_municipio = g.cod_municipio)) AND (a.cod_parroquia = g.cod_parroquia)) AND (a.cod_centro_poblado = g.cod_centro))) AS deno_centro, a.cod_calle_avenida, (SELECT h.denominacion FROM cugd01_vialidad h WHERE ((((((a.cod_pais = h.cod_republica) AND (a.cod_estado = h.cod_estado)) AND (a.cod_municipio = h.cod_municipio)) AND (a.cod_parroquia = h.cod_parroquia)) AND (a.cod_centro_poblado = h.cod_centro)) AND (a.cod_calle_avenida = h.cod_vialidad))) AS deno_vialidad, a.cod_vereda_edificio, (SELECT i.denominacion FROM cugd01_vereda i WHERE (((((((a.cod_pais = i.cod_republica) AND (a.cod_estado = i.cod_estado)) AND (a.cod_municipio = i.cod_municipio)) AND (a.cod_parroquia = i.cod_parroquia)) AND (a.cod_centro_poblado = i.cod_centro)) AND (a.cod_calle_avenida = i.cod_vialidad)) AND (a.cod_vereda_edificio = i.cod_vereda))) AS deno_vereda, a.numero_vivienda_local, a.telefonos_fijos, a.telefonos_celulares, a.correo_electronico FROM shd001_registro_contribuyentes a;


ALTER TABLE public.v_shd001_registro_contribuyentes OWNER TO sisap;

--
-- TOC entry 4209 (class 1259 OID 502416)
-- Dependencies: 4599 3
-- Name: v_shd900_cobranza_diaria; Type: VIEW; Schema: public; Owner: sisap
--

CREATE VIEW v_shd900_cobranza_diaria AS
    SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.ano_comprobante, a.numero_comprobante, a.cod_partida, a.cod_generica, a.cod_especifica, a.cod_sub_espec, a.cod_auxiliar, a.fecha_comprobante, a.rif_cedula, a.concepto_comprobante, a.deuda_vigente, a.deuda_anterior, a.monto_recargo, a.monto_multa, a.monto_intereses, a.monto_descuento, a.cod_entidad_deposito, a.cod_sucursal_deposito, a.cuenta_bancaria_deposito, a.numero_deposito, a.monto_deposito, a.fecha_deposito, a.cod_entidad_credito, a.cod_sucursal_credito, a.cuenta_bancaria_credito, a.numero_nota_credito, a.monto_nota_credito, a.fecha_nota_credito, a.cod_entidad_cheque, a.cod_sucursal_cheque, a.cuenta_bancaria_cheque, a.numero_cheque, a.monto_cheque, a.fecha_cheque, a.monto_efectivo, a.condicion_documento, a.fecha_registro, a.username_registro, a.ano_anulacion, a.numero_anulacion, a.fecha_anulacion, a.username_anulacion, a.rif_ci_cobrador, (SELECT b.denominacion FROM cstd01_entidades_bancarias b WHERE (b.cod_entidad_bancaria = a.cod_entidad_deposito)) AS banco_deposito, (SELECT b.denominacion FROM cstd01_entidades_bancarias b WHERE (b.cod_entidad_bancaria = a.cod_entidad_credito)) AS banco_nota_credito, (SELECT b.denominacion FROM cstd01_entidades_bancarias b WHERE (b.cod_entidad_bancaria = a.cod_entidad_cheque)) AS banco_cheque, (SELECT b.denominacion FROM cstd01_sucursales_bancarias b WHERE ((b.cod_entidad_bancaria = a.cod_entidad_deposito) AND (b.cod_sucursal = a.cod_sucursal_deposito))) AS sucursal_deposito, (SELECT b.denominacion FROM cstd01_sucursales_bancarias b WHERE ((b.cod_entidad_bancaria = a.cod_entidad_credito) AND (b.cod_sucursal = a.cod_sucursal_credito))) AS sucursal_nota_credito, (SELECT b.denominacion FROM cstd01_sucursales_bancarias b WHERE ((b.cod_entidad_bancaria = a.cod_entidad_cheque) AND (b.cod_sucursal = a.cod_sucursal_cheque))) AS sucursal_cheque, (SELECT b.denominacion FROM v_consulta_ingreso b WHERE (((((b.cod_partida_completo = a.cod_partida) AND (b.cod_generica = a.cod_generica)) AND (b.cod_especifica = a.cod_especifica)) AND (b.cod_sub_espec = a.cod_sub_espec)) AND (b.cod_auxiliar = a.cod_auxiliar)) LIMIT 1) AS denominacion_ingreso FROM shd900_cobranza_diaria a;


ALTER TABLE public.v_shd900_cobranza_diaria OWNER TO sisap;

--
-- TOC entry 4246 (class 1259 OID 502981)
-- Dependencies: 4632 3
-- Name: v_cobranza; Type: VIEW; Schema: public; Owner: sisap
--

CREATE VIEW v_cobranza AS
    SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.ano_comprobante, a.numero_comprobante, a.cod_partida, a.cod_generica, a.cod_especifica, a.cod_sub_espec, a.cod_auxiliar, a.fecha_comprobante, a.rif_cedula, a.concepto_comprobante, a.deuda_vigente, a.deuda_anterior, a.monto_recargo, a.monto_multa, a.monto_intereses, a.monto_descuento, a.cod_entidad_deposito, a.cod_sucursal_deposito, a.cuenta_bancaria_deposito, a.numero_deposito, a.monto_deposito, a.fecha_deposito, a.cod_entidad_credito, a.cod_sucursal_credito, a.cuenta_bancaria_credito, a.numero_nota_credito, a.monto_nota_credito, a.fecha_nota_credito, a.cod_entidad_cheque, a.cod_sucursal_cheque, a.cuenta_bancaria_cheque, a.numero_cheque, a.monto_cheque, a.fecha_cheque, a.monto_efectivo, a.condicion_documento, a.fecha_registro, a.username_registro, a.ano_anulacion, a.numero_anulacion, a.fecha_anulacion, a.username_anulacion, a.rif_ci_cobrador, a.banco_deposito, a.banco_nota_credito, a.banco_cheque, a.sucursal_deposito, a.sucursal_nota_credito, a.sucursal_cheque, a.denominacion_ingreso, b.personalidad_juridica, b.razon_social_nombres, b.fecha_inscripcion, b.nacionalidad, b.estado_civil, b.profesion, b.deno_profesion, b.cod_pais, b.deno_pais, b.cod_estado, b.deno_estado, b.cod_municipio, b.deno_municipio, b.cod_parroquia, b.deno_parroquia, b.cod_centro_poblado, b.deno_centro, b.cod_calle_avenida, b.deno_vialidad, b.cod_vereda_edificio, b.deno_vereda, b.numero_vivienda_local, b.telefonos_fijos, b.telefonos_celulares, b.correo_electronico, (((((((a.numero_comprobante)::text || ' '::text) || (a.rif_cedula)::text) || ' '::text) || quitar_acentos((b.razon_social_nombres)::text)) || ' '::text) || quitar_acentos(a.denominacion_ingreso)) AS denominacion_busqueda FROM v_shd900_cobranza_diaria a, v_shd001_registro_contribuyentes b WHERE (((b.rif_cedula)::text = (a.rif_cedula)::text) AND ((SELECT count(*) AS count FROM shd900_cobranza_diaria_planillas c WHERE (((((((c.cod_presi = a.cod_presi) AND (c.cod_entidad = a.cod_entidad)) AND (c.cod_tipo_inst = a.cod_tipo_inst)) AND (c.cod_inst = a.cod_inst)) AND (c.cod_dep = a.cod_dep)) AND (c.ano_comprobante = a.ano_comprobante)) AND (c.numero_comprobante = a.numero_comprobante))) <> 0));


ALTER TABLE public.v_cobranza OWNER TO sisap;

--
-- TOC entry 4210 (class 1259 OID 502421)
-- Dependencies: 4600 3
-- Name: v_cobranza_diaria; Type: VIEW; Schema: public; Owner: sisap
--

CREATE VIEW v_cobranza_diaria AS
    SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.ano_comprobante, a.numero_comprobante, a.cod_partida, a.cod_generica, a.cod_especifica, a.cod_sub_espec, a.cod_auxiliar, a.fecha_comprobante, a.rif_cedula, a.concepto_comprobante, a.deuda_vigente, a.deuda_anterior, a.monto_recargo, a.monto_multa, a.monto_intereses, a.monto_descuento, a.cod_entidad_deposito, a.cod_sucursal_deposito, a.cuenta_bancaria_deposito, a.numero_deposito, a.monto_deposito, a.fecha_deposito, a.cod_entidad_credito, a.cod_sucursal_credito, a.cuenta_bancaria_credito, a.numero_nota_credito, a.monto_nota_credito, a.fecha_nota_credito, a.cod_entidad_cheque, a.cod_sucursal_cheque, a.cuenta_bancaria_cheque, a.numero_cheque, a.monto_cheque, a.fecha_cheque, a.monto_efectivo, a.condicion_documento, a.fecha_registro, a.username_registro, a.ano_anulacion, a.numero_anulacion, a.fecha_anulacion, a.username_anulacion, a.rif_ci_cobrador, a.banco_deposito, a.banco_nota_credito, a.banco_cheque, a.sucursal_deposito, a.sucursal_nota_credito, a.sucursal_cheque, a.denominacion_ingreso, b.personalidad_juridica, b.razon_social_nombres, b.fecha_inscripcion, b.nacionalidad, b.estado_civil, b.profesion, b.deno_profesion, b.cod_pais, b.deno_pais, b.cod_estado, b.deno_estado, b.cod_municipio, b.deno_municipio, b.cod_parroquia, b.deno_parroquia, b.cod_centro_poblado, b.deno_centro, b.cod_calle_avenida, b.deno_vialidad, b.cod_vereda_edificio, b.deno_vereda, b.numero_vivienda_local, b.telefonos_fijos, b.telefonos_celulares, b.correo_electronico, (((((((a.numero_comprobante)::text || ' '::text) || (a.rif_cedula)::text) || ' '::text) || quitar_acentos((b.razon_social_nombres)::text)) || ' '::text) || quitar_acentos(a.denominacion_ingreso)) AS denominacion_busqueda FROM v_shd900_cobranza_diaria a, v_shd001_registro_contribuyentes b WHERE (((b.rif_cedula)::text = (a.rif_cedula)::text) AND ((SELECT count(*) AS count FROM shd900_cobranza_diaria_planillas c WHERE (((((((c.cod_presi = a.cod_presi) AND (c.cod_entidad = a.cod_entidad)) AND (c.cod_tipo_inst = a.cod_tipo_inst)) AND (c.cod_inst = a.cod_inst)) AND (c.cod_dep = a.cod_dep)) AND (c.ano_comprobante = a.ano_comprobante)) AND (c.numero_comprobante = a.numero_comprobante))) = 0));


ALTER TABLE public.v_cobranza_diaria OWNER TO sisap;

--
-- TOC entry 4260 (class 1259 OID 503052)
-- Dependencies: 4646 3
-- Name: v_shd000_control_actua_partida; Type: VIEW; Schema: public; Owner: sisap
--

CREATE VIEW v_shd000_control_actua_partida AS
    SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cod_ingreso, a.ano_actualizado, a.mes_actualizado, a.condicion, b.cod_partida, b.cod_generica, b.cod_especifica, b.cod_subespec AS cod_sub_espec, b.cod_auxiliar FROM shd000_control_actualizacion a, shd003_codigo_ingresos b WHERE (a.cod_ingreso = b.cod_ingreso);


ALTER TABLE public.v_shd000_control_actua_partida OWNER TO sisap;

--
-- TOC entry 5680 (class 0 OID 0)
-- Dependencies: 4260
-- Name: VIEW v_shd000_control_actua_partida; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON VIEW v_shd000_control_actua_partida IS 'vista de la union de control actualizacion con los codigos de ingresos para traerse los codigos de las partidas';


--
-- TOC entry 4237 (class 1259 OID 502877)
-- Dependencies: 4627 3
-- Name: v_shd000_control_arranque_cierre; Type: VIEW; Schema: public; Owner: sisap
--

CREATE VIEW v_shd000_control_arranque_cierre AS
    SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cod_ingreso, a.ano_actualizado, a.mes_actualizado, a.condicion, b.cod_partida, b.cod_generica, b.cod_especifica, b.cod_subespec, b.cod_auxiliar, b.denominacion FROM shd000_control_actualizacion a, shd003_codigo_ingresos b WHERE (a.cod_ingreso = b.cod_ingreso);


ALTER TABLE public.v_shd000_control_arranque_cierre OWNER TO sisap;

--
-- TOC entry 4269 (class 1259 OID 511301)
-- Dependencies: 4655 3
-- Name: v_shd001_contribuyentes_e_impuestos; Type: VIEW; Schema: public; Owner: sisap
--

CREATE VIEW v_shd001_contribuyentes_e_impuestos AS
    (((((SELECT a.rif_cedula, a.personalidad_juridica, a.razon_social_nombres, a.fecha_inscripcion, b.cod_presi, b.cod_entidad, b.cod_tipo_inst, b.cod_inst, b.cod_dep, b.monto_mensual, b.numero_solicitud, (1)::character varying AS pertenece_tabla, (SELECT s.denominacion FROM shd003_codigo_ingresos s WHERE (s.cod_ingreso = 1)) AS concepto_impuesto FROM shd001_registro_contribuyentes a, shd100_patente b WHERE ((a.rif_cedula)::text = (b.rif_cedula)::text) UNION SELECT a.rif_cedula, a.personalidad_juridica, a.razon_social_nombres, a.fecha_inscripcion, b.cod_presi, b.cod_entidad, b.cod_tipo_inst, b.cod_inst, b.cod_dep, b.monto_mensual, b.placa_vehiculo AS numero_solicitud, (2)::character varying AS pertenece_tabla, (SELECT s.denominacion FROM shd003_codigo_ingresos s WHERE (s.cod_ingreso = 2)) AS concepto_impuesto FROM shd001_registro_contribuyentes a, shd200_vehiculos b WHERE ((a.rif_cedula)::text = (b.rif_cedula)::text)) UNION SELECT a.rif_cedula, a.personalidad_juridica, a.razon_social_nombres, a.fecha_inscripcion, b.cod_presi, b.cod_entidad, b.cod_tipo_inst, b.cod_inst, b.cod_dep, b.monto_mensual_general AS monto_mensual, (0)::character varying AS numero_solicitud, (3)::character varying AS pertenece_tabla, (SELECT s.denominacion FROM shd003_codigo_ingresos s WHERE (s.cod_ingreso = 3)) AS concepto_impuesto FROM shd001_registro_contribuyentes a, shd300_propaganda b WHERE ((a.rif_cedula)::text = (b.rif_cedula)::text)) UNION SELECT a.rif_cedula, a.personalidad_juridica, a.razon_social_nombres, a.fecha_inscripcion, b.cod_presi, b.cod_entidad, b.cod_tipo_inst, b.cod_inst, b.cod_dep, b.monto_mensual, b.cod_ficha AS numero_solicitud, (4)::character varying AS pertenece_tabla, (SELECT s.denominacion FROM shd003_codigo_ingresos s WHERE (s.cod_ingreso = 4)) AS concepto_impuesto FROM shd001_registro_contribuyentes a, shd400_propiedad b WHERE ((a.rif_cedula)::text = (b.rif_cedula)::text)) UNION SELECT a.rif_cedula, a.personalidad_juridica, a.razon_social_nombres, a.fecha_inscripcion, b.cod_presi, b.cod_entidad, b.cod_tipo_inst, b.cod_inst, b.cod_dep, b.monto_mensual, (0)::character varying AS numero_solicitud, (5)::character varying AS pertenece_tabla, (SELECT s.denominacion FROM shd003_codigo_ingresos s WHERE (s.cod_ingreso = 5)) AS concepto_impuesto FROM shd001_registro_contribuyentes a, shd500_aseo_domiciliario b WHERE ((a.rif_cedula)::text = (b.rif_cedula)::text)) UNION SELECT a.rif_cedula, a.personalidad_juridica, a.razon_social_nombres, a.fecha_inscripcion, b.cod_presi, b.cod_entidad, b.cod_tipo_inst, b.cod_inst, b.cod_dep, b.monto_mensual, b.numero_solicitud, (6)::character varying AS pertenece_tabla, (SELECT s.denominacion FROM shd003_codigo_ingresos s WHERE (s.cod_ingreso = 6)) AS concepto_impuesto FROM shd001_registro_contribuyentes a, shd600_aprobacion_arrendamiento b WHERE ((a.rif_cedula)::text = (b.rif_cedula)::text)) UNION SELECT a.rif_cedula, a.personalidad_juridica, a.razon_social_nombres, a.fecha_inscripcion, b.cod_presi, b.cod_entidad, b.cod_tipo_inst, b.cod_inst, b.cod_dep, b.monto_mensual, b.numero_solicitud, (7)::character varying AS pertenece_tabla, (SELECT s.denominacion FROM shd003_codigo_ingresos s WHERE (s.cod_ingreso = 7)) AS concepto_impuesto FROM shd001_registro_contribuyentes a, shd700_credito_vivienda b WHERE ((a.rif_cedula)::text = (b.rif_cedula)::text);


ALTER TABLE public.v_shd001_contribuyentes_e_impuestos OWNER TO sisap;

--
-- TOC entry 4204 (class 1259 OID 502378)
-- Dependencies: 4594 3
-- Name: v_shd002_cobranza_pendiente; Type: VIEW; Schema: public; Owner: sisap
--

CREATE VIEW v_shd002_cobranza_pendiente AS
    SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.rif_ci, b.personalidad, b.nombre_razon, b.fecha_ingreso, b.recurso_cobro, b.condicion_actividad, a.ano, a.cobranza_pendiente_acumulada, a.enero, a.febrero, a.marzo, a.abril, a.mayo, a.junio, a.julio, a.agosto, a.septiembre, a.octubre, a.noviembre, a.diciembre FROM shd002_cobranza_pendiente a, shd002_cobradores b WHERE ((((((b.cod_presi = a.cod_presi) AND (b.cod_entidad = a.cod_entidad)) AND (b.cod_tipo_inst = a.cod_tipo_inst)) AND (b.cod_inst = a.cod_inst)) AND (b.cod_dep = a.cod_dep)) AND ((b.rif_ci)::text = (a.rif_ci)::text));


ALTER TABLE public.v_shd002_cobranza_pendiente OWNER TO sisap;

--
-- TOC entry 4203 (class 1259 OID 502374)
-- Dependencies: 4593 3
-- Name: v_shd002_cobranza_realizada; Type: VIEW; Schema: public; Owner: sisap
--

CREATE VIEW v_shd002_cobranza_realizada AS
    SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.rif_ci, b.personalidad, b.nombre_razon, b.fecha_ingreso, b.recurso_cobro, b.condicion_actividad, a.ano, a.cobranza_acumulada, a.enero, a.febrero, a.marzo, a.abril, a.mayo, a.junio, a.julio, a.agosto, a.septiembre, a.octubre, a.noviembre, a.diciembre FROM shd002_cobranza_realizada a, shd002_cobradores b WHERE ((((((b.cod_presi = a.cod_presi) AND (b.cod_entidad = a.cod_entidad)) AND (b.cod_tipo_inst = a.cod_tipo_inst)) AND (b.cod_inst = a.cod_inst)) AND (b.cod_dep = a.cod_dep)) AND ((b.rif_ci)::text = (a.rif_ci)::text));


ALTER TABLE public.v_shd002_cobranza_realizada OWNER TO sisap;

--
-- TOC entry 4216 (class 1259 OID 502457)
-- Dependencies: 4606 3
-- Name: v_shd100_declaracion_actividades; Type: VIEW; Schema: public; Owner: sisap
--

CREATE VIEW v_shd100_declaracion_actividades AS
    SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.rif_cedula, a.numero_declaracion, a.cod_actividad, (SELECT b.denominacion_actividad FROM shd100_actividades b WHERE ((((((a.cod_presi = b.cod_presi) AND (a.cod_entidad = b.cod_entidad)) AND (a.cod_tipo_inst = b.cod_tipo_inst)) AND (a.cod_inst = b.cod_inst)) AND (a.cod_dep = b.cod_dep)) AND ((a.cod_actividad)::text = (b.cod_actividad)::text))) AS deno_actividad, a.monto_ingresos, a.monto_impuesto, a.alicuota_aplicada FROM shd100_declaracion_actividades a;


ALTER TABLE public.v_shd100_declaracion_actividades OWNER TO sisap;

--
-- TOC entry 4207 (class 1259 OID 502391)
-- Dependencies: 4597 3
-- Name: v_shd100_declaracion_ingreso; Type: VIEW; Schema: public; Owner: sisap
--

CREATE VIEW v_shd100_declaracion_ingreso AS
    SELECT x.cod_presi, x.cod_entidad, x.cod_tipo_inst, x.cod_inst, x.cod_dep, x.numero_solicitud, x.numero_patente, x.fecha_solicitud, a.rif_cedula, a.razon_social_nombres, a.cod_pais, (SELECT b.denominacion FROM cugd01_republica b WHERE (b.cod_republica = a.cod_pais)) AS denominacion_pais, a.cod_estado, (SELECT c.denominacion FROM cugd01_estados c WHERE ((c.cod_republica = a.cod_pais) AND (c.cod_estado = a.cod_estado))) AS denominacion_estado, a.cod_municipio, (SELECT d.denominacion FROM cugd01_municipios d WHERE (((d.cod_republica = a.cod_pais) AND (d.cod_estado = a.cod_estado)) AND (d.cod_municipio = a.cod_municipio))) AS denominacion_municipio, a.cod_parroquia, (SELECT e.denominacion FROM cugd01_parroquias e WHERE ((((e.cod_republica = a.cod_pais) AND (e.cod_estado = a.cod_estado)) AND (e.cod_municipio = a.cod_municipio)) AND (e.cod_parroquia = a.cod_parroquia))) AS denominacion_parroquia, a.cod_centro_poblado, (SELECT f.denominacion FROM cugd01_centros_poblados f WHERE (((((f.cod_republica = a.cod_pais) AND (f.cod_estado = a.cod_estado)) AND (f.cod_municipio = a.cod_municipio)) AND (f.cod_parroquia = a.cod_parroquia)) AND (f.cod_centro = a.cod_centro_poblado))) AS denominacion_centro, a.cod_calle_avenida, (SELECT g.denominacion FROM cugd01_vialidad g WHERE ((((((g.cod_republica = a.cod_pais) AND (g.cod_estado = a.cod_estado)) AND (g.cod_municipio = a.cod_municipio)) AND (g.cod_parroquia = a.cod_parroquia)) AND (g.cod_centro = a.cod_centro_poblado)) AND (g.cod_vialidad = a.cod_calle_avenida))) AS denominacion_vialidad, a.cod_vereda_edificio, (SELECT h.denominacion FROM cugd01_vereda h WHERE (((((((h.cod_republica = a.cod_pais) AND (h.cod_estado = a.cod_estado)) AND (h.cod_municipio = a.cod_municipio)) AND (h.cod_parroquia = a.cod_parroquia)) AND (h.cod_centro = a.cod_centro_poblado)) AND (h.cod_vialidad = a.cod_calle_avenida)) AND (h.cod_vereda = a.cod_vereda_edificio))) AS denominacion_vereda, a.numero_vivienda_local, a.telefonos_fijos, a.telefonos_celulares, a.correo_electronico FROM shd001_registro_contribuyentes a, shd100_solicitud x WHERE ((x.rif_cedula)::text = (a.rif_cedula)::text);


ALTER TABLE public.v_shd100_declaracion_ingreso OWNER TO sisap;

--
-- TOC entry 4249 (class 1259 OID 502996)
-- Dependencies: 4635 3
-- Name: v_shd100_declaracion_ingresos; Type: VIEW; Schema: public; Owner: sisap
--

CREATE VIEW v_shd100_declaracion_ingresos AS
    SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.rif_cedula, a.numero_declaracion, a.periodo_desde, a.periodo_hasta, a.capital, a.numero_empleados, a.numero_obreros, a.fecha_declaracion, (substr((a.fecha_declaracion)::text, 0, 5))::integer AS ano_declaracion, (SELECT b.fecha_solicitud FROM shd100_solicitud b WHERE (((((a.cod_presi = b.cod_presi) AND (a.cod_entidad = b.cod_entidad)) AND (a.cod_tipo_inst = b.cod_tipo_inst)) AND (a.cod_dep = b.cod_dep)) AND ((a.rif_cedula)::text = (b.rif_cedula)::text))) AS fecha_solicitud, (SELECT b.razon_social_nombres FROM v_shd001_registro_contribuyentes b WHERE ((a.rif_cedula)::text = (b.rif_cedula)::text)) AS nombre_razon, (SELECT b.cod_pais FROM v_shd001_registro_contribuyentes b WHERE ((b.rif_cedula)::text = (a.rif_cedula)::text)) AS cod_pais, (SELECT b.deno_pais FROM v_shd001_registro_contribuyentes b WHERE ((b.rif_cedula)::text = (a.rif_cedula)::text)) AS deno_pais, (SELECT b.cod_estado FROM v_shd001_registro_contribuyentes b WHERE ((a.rif_cedula)::text = (b.rif_cedula)::text)) AS cod_estado, (SELECT b.deno_estado FROM v_shd001_registro_contribuyentes b WHERE ((a.rif_cedula)::text = (b.rif_cedula)::text)) AS deno_estado, (SELECT b.cod_municipio FROM v_shd001_registro_contribuyentes b WHERE ((a.rif_cedula)::text = (b.rif_cedula)::text)) AS cod_municipio, (SELECT b.deno_municipio FROM v_shd001_registro_contribuyentes b WHERE ((a.rif_cedula)::text = (b.rif_cedula)::text)) AS deno_municipio, (SELECT b.cod_parroquia FROM v_shd001_registro_contribuyentes b WHERE ((a.rif_cedula)::text = (b.rif_cedula)::text)) AS cod_parroquia, (SELECT b.deno_parroquia FROM v_shd001_registro_contribuyentes b WHERE ((a.rif_cedula)::text = (b.rif_cedula)::text)) AS deno_parroquia, (SELECT b.cod_centro_poblado FROM v_shd001_registro_contribuyentes b WHERE ((a.rif_cedula)::text = (b.rif_cedula)::text)) AS cod_centro, (SELECT b.deno_centro FROM v_shd001_registro_contribuyentes b WHERE ((a.rif_cedula)::text = (b.rif_cedula)::text)) AS deno_centro, (SELECT b.cod_calle_avenida FROM v_shd001_registro_contribuyentes b WHERE ((a.rif_cedula)::text = (b.rif_cedula)::text)) AS cod_calle, (SELECT b.deno_vialidad FROM v_shd001_registro_contribuyentes b WHERE ((a.rif_cedula)::text = (b.rif_cedula)::text)) AS deno_calle, (SELECT b.cod_vereda_edificio FROM v_shd001_registro_contribuyentes b WHERE ((a.rif_cedula)::text = (b.rif_cedula)::text)) AS cod_vereda_edificio, (SELECT b.deno_vereda FROM v_shd001_registro_contribuyentes b WHERE ((a.rif_cedula)::text = (b.rif_cedula)::text)) AS deno_vereda, (SELECT b.numero_vivienda_local FROM v_shd001_registro_contribuyentes b WHERE ((a.rif_cedula)::text = (b.rif_cedula)::text)) AS numero_casa, (SELECT b.fecha_inscripcion FROM v_shd001_registro_contribuyentes b WHERE ((a.rif_cedula)::text = (b.rif_cedula)::text)) AS fecha_inscripcion_cont, (SELECT b.telefonos_fijos FROM v_shd001_registro_contribuyentes b WHERE ((a.rif_cedula)::text = (b.rif_cedula)::text)) AS telefonos_fijos, (SELECT b.telefonos_celulares FROM v_shd001_registro_contribuyentes b WHERE ((a.rif_cedula)::text = (b.rif_cedula)::text)) AS telefonos_celulares, (SELECT b.correo_electronico FROM v_shd001_registro_contribuyentes b WHERE ((a.rif_cedula)::text = (b.rif_cedula)::text)) AS correo_electronico, (SELECT b.nacionalidad FROM v_shd001_registro_contribuyentes b WHERE ((a.rif_cedula)::text = (b.rif_cedula)::text)) AS nacionalidad, (SELECT b.estado_civil FROM v_shd001_registro_contribuyentes b WHERE ((a.rif_cedula)::text = (b.rif_cedula)::text)) AS estado_civil, (SELECT b.deno_profesion FROM v_shd001_registro_contribuyentes b WHERE ((a.rif_cedula)::text = (b.rif_cedula)::text)) AS deno_profesion, (SELECT b.numero_solicitud FROM shd100_patente b WHERE (((((a.cod_presi = b.cod_presi) AND (a.cod_entidad = b.cod_entidad)) AND (a.cod_tipo_inst = b.cod_tipo_inst)) AND (a.cod_dep = b.cod_dep)) AND ((a.rif_cedula)::text = (b.rif_cedula)::text))) AS numero_solicitud, (SELECT b.numero_patente FROM shd100_patente b WHERE (((((a.cod_presi = b.cod_presi) AND (a.cod_entidad = b.cod_entidad)) AND (a.cod_tipo_inst = b.cod_tipo_inst)) AND (a.cod_dep = b.cod_dep)) AND ((a.rif_cedula)::text = (b.rif_cedula)::text))) AS numero_patente, (SELECT b.fecha_patente FROM shd100_patente b WHERE (((((a.cod_presi = b.cod_presi) AND (a.cod_entidad = b.cod_entidad)) AND (a.cod_tipo_inst = b.cod_tipo_inst)) AND (a.cod_dep = b.cod_dep)) AND ((a.rif_cedula)::text = (b.rif_cedula)::text))) AS fecha_patente, (SELECT b.frecuencia_pago FROM shd100_patente b WHERE (((((a.cod_presi = b.cod_presi) AND (a.cod_entidad = b.cod_entidad)) AND (a.cod_tipo_inst = b.cod_tipo_inst)) AND (a.cod_dep = b.cod_dep)) AND ((a.rif_cedula)::text = (b.rif_cedula)::text))) AS frecuencia_pago, (SELECT b.fecha_inicio_const FROM shd100_solicitud b WHERE (((((a.cod_presi = b.cod_presi) AND (a.cod_entidad = b.cod_entidad)) AND (a.cod_tipo_inst = b.cod_tipo_inst)) AND (a.cod_dep = b.cod_dep)) AND ((a.rif_cedula)::text = (b.rif_cedula)::text))) AS fecha_inicio_const, (SELECT b.fecha_cierre_const FROM shd100_solicitud b WHERE (((((a.cod_presi = b.cod_presi) AND (a.cod_entidad = b.cod_entidad)) AND (a.cod_tipo_inst = b.cod_tipo_inst)) AND (a.cod_dep = b.cod_dep)) AND ((a.rif_cedula)::text = (b.rif_cedula)::text))) AS fecha_cierre_const, (SELECT b.fecha_inicio_econo FROM shd100_solicitud b WHERE (((((a.cod_presi = b.cod_presi) AND (a.cod_entidad = b.cod_entidad)) AND (a.cod_tipo_inst = b.cod_tipo_inst)) AND (a.cod_dep = b.cod_dep)) AND ((a.rif_cedula)::text = (b.rif_cedula)::text))) AS fecha_inicio_econo, (SELECT b.fecha_cierre_economico FROM shd100_solicitud b WHERE (((((a.cod_presi = b.cod_presi) AND (a.cod_entidad = b.cod_entidad)) AND (a.cod_tipo_inst = b.cod_tipo_inst)) AND (a.cod_dep = b.cod_dep)) AND ((a.rif_cedula)::text = (b.rif_cedula)::text))) AS fecha_cierre_economico, (SELECT b.registro_mercantil FROM shd100_solicitud b WHERE (((((a.cod_presi = b.cod_presi) AND (a.cod_entidad = b.cod_entidad)) AND (a.cod_tipo_inst = b.cod_tipo_inst)) AND (a.cod_dep = b.cod_dep)) AND ((a.rif_cedula)::text = (b.rif_cedula)::text))) AS registro_mercantil FROM shd100_declaracion_ingresos a;


ALTER TABLE public.v_shd100_declaracion_ingresos OWNER TO sisap;

--
-- TOC entry 4230 (class 1259 OID 502767)
-- Dependencies: 4620 3
-- Name: v_shd100_patente; Type: VIEW; Schema: public; Owner: sisap
--

CREATE VIEW v_shd100_patente AS
    SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.numero_solicitud, a.numero_expediente, (SELECT (sum(((((xx.deuda_vigente + xx.monto_recargo) + xx.monto_multa) + xx.monto_intereses) - xx.monto_descuento)))::numeric(26,2) AS sum FROM shd900_planillas_deuda_cobro_detalles xx WHERE ((((((((xx.cancelado = 2) AND (xx.ano < (SELECT xy.ano_arranque FROM shd000_arranque xy WHERE (((((xy.cod_presi = a.cod_presi) AND (xy.cod_entidad = a.cod_entidad)) AND (xy.cod_tipo_inst = a.cod_tipo_inst)) AND (xy.cod_inst = a.cod_inst)) AND (xy.cod_dep = a.cod_dep))))) AND (xx.cod_presi = a.cod_presi)) AND (xx.cod_entidad = a.cod_entidad)) AND (xx.cod_tipo_inst = a.cod_tipo_inst)) AND (xx.cod_inst = a.cod_inst)) AND (xx.cod_dep = a.cod_dep)) AND ((xx.rif_cedula)::text = (a.rif_cedula)::text))) AS deudada_ano_anterior_total, (SELECT (sum(((((xx.deuda_vigente + xx.monto_recargo) + xx.monto_multa) + xx.monto_intereses) - xx.monto_descuento)))::numeric(26,2) AS sum FROM shd900_planillas_deuda_cobro_detalles xx WHERE (((((((((xx.cancelado = 2) AND (xx.ano < (SELECT xy.ano_arranque FROM shd000_arranque xy WHERE (((((xy.cod_presi = a.cod_presi) AND (xy.cod_entidad = a.cod_entidad)) AND (xy.cod_tipo_inst = a.cod_tipo_inst)) AND (xy.cod_inst = a.cod_inst)) AND (xy.cod_dep = a.cod_dep))))) AND (xx.cod_presi = a.cod_presi)) AND (xx.cod_entidad = a.cod_entidad)) AND (xx.cod_tipo_inst = a.cod_tipo_inst)) AND (xx.cod_inst = a.cod_inst)) AND (xx.cod_dep = a.cod_dep)) AND ((xx.rif_cedula)::text = (a.rif_cedula)::text)) AND (1 = (SELECT aa.cod_ingreso FROM shd003_codigo_ingresos aa WHERE (((((aa.cod_partida = xx.cod_partida) AND (aa.cod_generica = xx.cod_generica)) AND (aa.cod_especifica = xx.cod_especifica)) AND (aa.cod_subespec = xx.cod_sub_espec)) AND (aa.cod_auxiliar = xx.cod_auxiliar)) LIMIT 1)))) AS deudada_ano_anterior_1, (SELECT sum(b.deuda_vigente) AS sum FROM shd900_planillas_deuda_cobro_detalles b WHERE (((((((a.cod_presi = b.cod_presi) AND (a.cod_entidad = b.cod_entidad)) AND (a.cod_tipo_inst = b.cod_tipo_inst)) AND (a.cod_inst = b.cod_inst)) AND (a.cod_dep = b.cod_dep)) AND ((a.rif_cedula)::text = (b.rif_cedula)::text)) AND (b.cancelado = 2))) AS deuda_vigente_total, (SELECT sum(b.monto_recargo) AS sum FROM shd900_planillas_deuda_cobro_detalles b WHERE (((((((a.cod_presi = b.cod_presi) AND (a.cod_entidad = b.cod_entidad)) AND (a.cod_tipo_inst = b.cod_tipo_inst)) AND (a.cod_inst = b.cod_inst)) AND (a.cod_dep = b.cod_dep)) AND ((a.rif_cedula)::text = (b.rif_cedula)::text)) AND (b.cancelado = 2))) AS monto_recargo_total, (SELECT sum(b.monto_multa) AS sum FROM shd900_planillas_deuda_cobro_detalles b WHERE (((((((a.cod_presi = b.cod_presi) AND (a.cod_entidad = b.cod_entidad)) AND (a.cod_tipo_inst = b.cod_tipo_inst)) AND (a.cod_inst = b.cod_inst)) AND (a.cod_dep = b.cod_dep)) AND ((a.rif_cedula)::text = (b.rif_cedula)::text)) AND (b.cancelado = 2))) AS monto_multa_total, (SELECT sum(b.monto_intereses) AS sum FROM shd900_planillas_deuda_cobro_detalles b WHERE (((((((a.cod_presi = b.cod_presi) AND (a.cod_entidad = b.cod_entidad)) AND (a.cod_tipo_inst = b.cod_tipo_inst)) AND (a.cod_inst = b.cod_inst)) AND (a.cod_dep = b.cod_dep)) AND ((a.rif_cedula)::text = (b.rif_cedula)::text)) AND (b.cancelado = 2))) AS monto_intereses_total, (SELECT sum(b.monto_descuento) AS sum FROM shd900_planillas_deuda_cobro_detalles b WHERE (((((((a.cod_presi = b.cod_presi) AND (a.cod_entidad = b.cod_entidad)) AND (a.cod_tipo_inst = b.cod_tipo_inst)) AND (a.cod_inst = b.cod_inst)) AND (a.cod_dep = b.cod_dep)) AND ((a.rif_cedula)::text = (b.rif_cedula)::text)) AND (b.cancelado = 2))) AS monto_descuento_total, (SELECT sum(b.cancelado) AS sum FROM shd900_planillas_deuda_cobro_detalles b WHERE (((((((a.cod_presi = b.cod_presi) AND (a.cod_entidad = b.cod_entidad)) AND (a.cod_tipo_inst = b.cod_tipo_inst)) AND (a.cod_inst = b.cod_inst)) AND (a.cod_dep = b.cod_dep)) AND ((a.rif_cedula)::text = (b.rif_cedula)::text)) AND (b.cancelado = 2))) AS cancelado_total, (SELECT b.fecha_solicitud FROM shd100_solicitud b WHERE ((((((a.cod_presi = b.cod_presi) AND (a.cod_entidad = b.cod_entidad)) AND (a.cod_tipo_inst = b.cod_tipo_inst)) AND (a.cod_inst = b.cod_inst)) AND (a.cod_dep = b.cod_dep)) AND ((a.numero_solicitud)::text = (b.numero_solicitud)::text))) AS fecha_solicitud, (SELECT b.rif_cedula FROM shd100_solicitud b WHERE ((((((a.cod_presi = b.cod_presi) AND (a.cod_entidad = b.cod_entidad)) AND (a.cod_tipo_inst = b.cod_tipo_inst)) AND (a.cod_inst = b.cod_inst)) AND (a.cod_dep = b.cod_dep)) AND ((a.numero_solicitud)::text = (b.numero_solicitud)::text))) AS rif_cedula, (SELECT c.razon_social_nombres FROM v_shd001_registro_contribuyentes c WHERE (((SELECT b.rif_cedula FROM shd100_solicitud b WHERE ((((((a.cod_presi = b.cod_presi) AND (a.cod_entidad = b.cod_entidad)) AND (a.cod_tipo_inst = b.cod_tipo_inst)) AND (a.cod_inst = b.cod_inst)) AND (a.cod_dep = b.cod_dep)) AND ((a.numero_solicitud)::text = (b.numero_solicitud)::text))))::text = (c.rif_cedula)::text)) AS deno_razon, (SELECT c.personalidad_juridica FROM v_shd001_registro_contribuyentes c WHERE (((SELECT b.rif_cedula FROM shd100_solicitud b WHERE ((((((a.cod_presi = b.cod_presi) AND (a.cod_entidad = b.cod_entidad)) AND (a.cod_tipo_inst = b.cod_tipo_inst)) AND (a.cod_inst = b.cod_inst)) AND (a.cod_dep = b.cod_dep)) AND ((a.numero_solicitud)::text = (b.numero_solicitud)::text))))::text = (c.rif_cedula)::text)) AS personalidad_juridica, (SELECT c.nacionalidad FROM v_shd001_registro_contribuyentes c WHERE (((SELECT b.rif_cedula FROM shd100_solicitud b WHERE ((((((a.cod_presi = b.cod_presi) AND (a.cod_entidad = b.cod_entidad)) AND (a.cod_tipo_inst = b.cod_tipo_inst)) AND (a.cod_inst = b.cod_inst)) AND (a.cod_dep = b.cod_dep)) AND ((a.numero_solicitud)::text = (b.numero_solicitud)::text))))::text = (c.rif_cedula)::text)) AS nacionalidad, (SELECT c.correo_electronico FROM v_shd001_registro_contribuyentes c WHERE (((SELECT b.rif_cedula FROM shd100_solicitud b WHERE ((((((a.cod_presi = b.cod_presi) AND (a.cod_entidad = b.cod_entidad)) AND (a.cod_tipo_inst = b.cod_tipo_inst)) AND (a.cod_inst = b.cod_inst)) AND (a.cod_dep = b.cod_dep)) AND ((a.numero_solicitud)::text = (b.numero_solicitud)::text))))::text = (c.rif_cedula)::text)) AS correo, (SELECT c.telefonos_celulares FROM v_shd001_registro_contribuyentes c WHERE (((SELECT b.rif_cedula FROM shd100_solicitud b WHERE ((((((a.cod_presi = b.cod_presi) AND (a.cod_entidad = b.cod_entidad)) AND (a.cod_tipo_inst = b.cod_tipo_inst)) AND (a.cod_inst = b.cod_inst)) AND (a.cod_dep = b.cod_dep)) AND ((a.numero_solicitud)::text = (b.numero_solicitud)::text))))::text = (c.rif_cedula)::text)) AS telefonos_celulares, (SELECT c.telefonos_fijos FROM v_shd001_registro_contribuyentes c WHERE (((SELECT b.rif_cedula FROM shd100_solicitud b WHERE ((((((a.cod_presi = b.cod_presi) AND (a.cod_entidad = b.cod_entidad)) AND (a.cod_tipo_inst = b.cod_tipo_inst)) AND (a.cod_inst = b.cod_inst)) AND (a.cod_dep = b.cod_dep)) AND ((a.numero_solicitud)::text = (b.numero_solicitud)::text))))::text = (c.rif_cedula)::text)) AS telefonos_fijos, (SELECT c.fecha_inscripcion FROM v_shd001_registro_contribuyentes c WHERE (((SELECT b.rif_cedula FROM shd100_solicitud b WHERE ((((((a.cod_presi = b.cod_presi) AND (a.cod_entidad = b.cod_entidad)) AND (a.cod_tipo_inst = b.cod_tipo_inst)) AND (a.cod_inst = b.cod_inst)) AND (a.cod_dep = b.cod_dep)) AND ((a.numero_solicitud)::text = (b.numero_solicitud)::text))))::text = (c.rif_cedula)::text)) AS fecha_inscripcion, (SELECT c.cod_pais FROM v_shd001_registro_contribuyentes c WHERE (((SELECT b.rif_cedula FROM shd100_solicitud b WHERE ((((((a.cod_presi = b.cod_presi) AND (a.cod_entidad = b.cod_entidad)) AND (a.cod_tipo_inst = b.cod_tipo_inst)) AND (a.cod_inst = b.cod_inst)) AND (a.cod_dep = b.cod_dep)) AND ((a.numero_solicitud)::text = (b.numero_solicitud)::text))))::text = (c.rif_cedula)::text)) AS cod_pais, (SELECT c.deno_pais FROM v_shd001_registro_contribuyentes c WHERE (((SELECT b.rif_cedula FROM shd100_solicitud b WHERE ((((((a.cod_presi = b.cod_presi) AND (a.cod_entidad = b.cod_entidad)) AND (a.cod_tipo_inst = b.cod_tipo_inst)) AND (a.cod_inst = b.cod_inst)) AND (a.cod_dep = b.cod_dep)) AND ((a.numero_solicitud)::text = (b.numero_solicitud)::text))))::text = (c.rif_cedula)::text)) AS deno_pais, (SELECT c.cod_estado FROM v_shd001_registro_contribuyentes c WHERE (((SELECT b.rif_cedula FROM shd100_solicitud b WHERE ((((((a.cod_presi = b.cod_presi) AND (a.cod_entidad = b.cod_entidad)) AND (a.cod_tipo_inst = b.cod_tipo_inst)) AND (a.cod_inst = b.cod_inst)) AND (a.cod_dep = b.cod_dep)) AND ((a.numero_solicitud)::text = (b.numero_solicitud)::text))))::text = (c.rif_cedula)::text)) AS cod_estado, (SELECT c.deno_estado FROM v_shd001_registro_contribuyentes c WHERE (((SELECT b.rif_cedula FROM shd100_solicitud b WHERE ((((((a.cod_presi = b.cod_presi) AND (a.cod_entidad = b.cod_entidad)) AND (a.cod_tipo_inst = b.cod_tipo_inst)) AND (a.cod_inst = b.cod_inst)) AND (a.cod_dep = b.cod_dep)) AND ((a.numero_solicitud)::text = (b.numero_solicitud)::text))))::text = (c.rif_cedula)::text)) AS deno_estado, (SELECT c.cod_municipio FROM v_shd001_registro_contribuyentes c WHERE (((SELECT b.rif_cedula FROM shd100_solicitud b WHERE ((((((a.cod_presi = b.cod_presi) AND (a.cod_entidad = b.cod_entidad)) AND (a.cod_tipo_inst = b.cod_tipo_inst)) AND (a.cod_inst = b.cod_inst)) AND (a.cod_dep = b.cod_dep)) AND ((a.numero_solicitud)::text = (b.numero_solicitud)::text))))::text = (c.rif_cedula)::text)) AS cod_municipio, (SELECT c.deno_municipio FROM v_shd001_registro_contribuyentes c WHERE (((SELECT b.rif_cedula FROM shd100_solicitud b WHERE ((((((a.cod_presi = b.cod_presi) AND (a.cod_entidad = b.cod_entidad)) AND (a.cod_tipo_inst = b.cod_tipo_inst)) AND (a.cod_inst = b.cod_inst)) AND (a.cod_dep = b.cod_dep)) AND ((a.numero_solicitud)::text = (b.numero_solicitud)::text))))::text = (c.rif_cedula)::text)) AS deno_municipio, (SELECT c.cod_parroquia FROM v_shd001_registro_contribuyentes c WHERE (((SELECT b.rif_cedula FROM shd100_solicitud b WHERE ((((((a.cod_presi = b.cod_presi) AND (a.cod_entidad = b.cod_entidad)) AND (a.cod_tipo_inst = b.cod_tipo_inst)) AND (a.cod_inst = b.cod_inst)) AND (a.cod_dep = b.cod_dep)) AND ((a.numero_solicitud)::text = (b.numero_solicitud)::text))))::text = (c.rif_cedula)::text)) AS cod_parroquia, (SELECT c.deno_parroquia FROM v_shd001_registro_contribuyentes c WHERE (((SELECT b.rif_cedula FROM shd100_solicitud b WHERE ((((((a.cod_presi = b.cod_presi) AND (a.cod_entidad = b.cod_entidad)) AND (a.cod_tipo_inst = b.cod_tipo_inst)) AND (a.cod_inst = b.cod_inst)) AND (a.cod_dep = b.cod_dep)) AND ((a.numero_solicitud)::text = (b.numero_solicitud)::text))))::text = (c.rif_cedula)::text)) AS deno_parroquia, (SELECT c.cod_centro_poblado FROM v_shd001_registro_contribuyentes c WHERE (((SELECT b.rif_cedula FROM shd100_solicitud b WHERE ((((((a.cod_presi = b.cod_presi) AND (a.cod_entidad = b.cod_entidad)) AND (a.cod_tipo_inst = b.cod_tipo_inst)) AND (a.cod_inst = b.cod_inst)) AND (a.cod_dep = b.cod_dep)) AND ((a.numero_solicitud)::text = (b.numero_solicitud)::text))))::text = (c.rif_cedula)::text)) AS cod_centro_poblado, (SELECT c.deno_centro FROM v_shd001_registro_contribuyentes c WHERE (((SELECT b.rif_cedula FROM shd100_solicitud b WHERE ((((((a.cod_presi = b.cod_presi) AND (a.cod_entidad = b.cod_entidad)) AND (a.cod_tipo_inst = b.cod_tipo_inst)) AND (a.cod_inst = b.cod_inst)) AND (a.cod_dep = b.cod_dep)) AND ((a.numero_solicitud)::text = (b.numero_solicitud)::text))))::text = (c.rif_cedula)::text)) AS deno_centro_poblado, (SELECT c.cod_calle_avenida FROM v_shd001_registro_contribuyentes c WHERE (((SELECT b.rif_cedula FROM shd100_solicitud b WHERE ((((((a.cod_presi = b.cod_presi) AND (a.cod_entidad = b.cod_entidad)) AND (a.cod_tipo_inst = b.cod_tipo_inst)) AND (a.cod_inst = b.cod_inst)) AND (a.cod_dep = b.cod_dep)) AND ((a.numero_solicitud)::text = (b.numero_solicitud)::text))))::text = (c.rif_cedula)::text)) AS cod_calle, (SELECT c.deno_vialidad FROM v_shd001_registro_contribuyentes c WHERE (((SELECT b.rif_cedula FROM shd100_solicitud b WHERE ((((((a.cod_presi = b.cod_presi) AND (a.cod_entidad = b.cod_entidad)) AND (a.cod_tipo_inst = b.cod_tipo_inst)) AND (a.cod_inst = b.cod_inst)) AND (a.cod_dep = b.cod_dep)) AND ((a.numero_solicitud)::text = (b.numero_solicitud)::text))))::text = (c.rif_cedula)::text)) AS deno_calle, (SELECT c.cod_vereda_edificio FROM v_shd001_registro_contribuyentes c WHERE (((SELECT b.rif_cedula FROM shd100_solicitud b WHERE ((((((a.cod_presi = b.cod_presi) AND (a.cod_entidad = b.cod_entidad)) AND (a.cod_tipo_inst = b.cod_tipo_inst)) AND (a.cod_inst = b.cod_inst)) AND (a.cod_dep = b.cod_dep)) AND ((a.numero_solicitud)::text = (b.numero_solicitud)::text))))::text = (c.rif_cedula)::text)) AS cod_vereda, (SELECT c.deno_vereda FROM v_shd001_registro_contribuyentes c WHERE (((SELECT b.rif_cedula FROM shd100_solicitud b WHERE ((((((a.cod_presi = b.cod_presi) AND (a.cod_entidad = b.cod_entidad)) AND (a.cod_tipo_inst = b.cod_tipo_inst)) AND (a.cod_inst = b.cod_inst)) AND (a.cod_dep = b.cod_dep)) AND ((a.numero_solicitud)::text = (b.numero_solicitud)::text))))::text = (c.rif_cedula)::text)) AS deno_vereda, (SELECT c.profesion FROM v_shd001_registro_contribuyentes c WHERE (((SELECT b.rif_cedula FROM shd100_solicitud b WHERE ((((((a.cod_presi = b.cod_presi) AND (a.cod_entidad = b.cod_entidad)) AND (a.cod_tipo_inst = b.cod_tipo_inst)) AND (a.cod_inst = b.cod_inst)) AND (a.cod_dep = b.cod_dep)) AND ((a.numero_solicitud)::text = (b.numero_solicitud)::text))))::text = (c.rif_cedula)::text)) AS cod_profesion, (SELECT c.deno_profesion FROM v_shd001_registro_contribuyentes c WHERE (((SELECT b.rif_cedula FROM shd100_solicitud b WHERE ((((((a.cod_presi = b.cod_presi) AND (a.cod_entidad = b.cod_entidad)) AND (a.cod_tipo_inst = b.cod_tipo_inst)) AND (a.cod_inst = b.cod_inst)) AND (a.cod_dep = b.cod_dep)) AND ((a.numero_solicitud)::text = (b.numero_solicitud)::text))))::text = (c.rif_cedula)::text)) AS deno_profesion, (SELECT c.numero_vivienda_local FROM v_shd001_registro_contribuyentes c WHERE (((SELECT b.rif_cedula FROM shd100_solicitud b WHERE ((((((a.cod_presi = b.cod_presi) AND (a.cod_entidad = b.cod_entidad)) AND (a.cod_tipo_inst = b.cod_tipo_inst)) AND (a.cod_inst = b.cod_inst)) AND (a.cod_dep = b.cod_dep)) AND ((a.numero_solicitud)::text = (b.numero_solicitud)::text))))::text = (c.rif_cedula)::text)) AS numero_casa, (SELECT c.estado_civil FROM v_shd001_registro_contribuyentes c WHERE (((SELECT b.rif_cedula FROM shd100_solicitud b WHERE ((((((a.cod_presi = b.cod_presi) AND (a.cod_entidad = b.cod_entidad)) AND (a.cod_tipo_inst = b.cod_tipo_inst)) AND (a.cod_inst = b.cod_inst)) AND (a.cod_dep = b.cod_dep)) AND ((a.numero_solicitud)::text = (b.numero_solicitud)::text))))::text = (c.rif_cedula)::text)) AS estado_civil, a.numero_patente, a.frecuencia_pago, a.monto_mensual, a.pago_todo, a.suspendido, a.rif_ci_cobrador, (SELECT d.nombre_razon FROM shd002_cobradores d WHERE ((((((a.cod_presi = d.cod_presi) AND (a.cod_entidad = d.cod_entidad)) AND (a.cod_tipo_inst = d.cod_tipo_inst)) AND (a.cod_inst = d.cod_inst)) AND (a.cod_dep = d.cod_dep)) AND ((a.rif_ci_cobrador)::text = (d.rif_ci)::text))) AS deno_cobrador, a.ultimo_ano_facturado, a.ultimo_mes_facturado, a.fecha_ultima_decla, a.ingresos_declarados, a.ultimo_ejercicio_decla, a.periodo_desde, a.periodo_hasta, a.fecha_patente FROM shd100_patente a;


ALTER TABLE public.v_shd100_patente OWNER TO sisap;

--
-- TOC entry 4225 (class 1259 OID 502666)
-- Dependencies: 4615 3
-- Name: v_shd100_patente_actividades; Type: VIEW; Schema: public; Owner: sisap
--

CREATE VIEW v_shd100_patente_actividades AS
    SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cod_actividad, a.rif_cedula, (SELECT b.denominacion_actividad FROM shd100_actividades b WHERE ((((((a.cod_presi = b.cod_presi) AND (a.cod_entidad = b.cod_entidad)) AND (a.cod_tipo_inst = b.cod_tipo_inst)) AND (a.cod_inst = b.cod_inst)) AND (a.cod_dep = b.cod_dep)) AND ((a.cod_actividad)::text = (b.cod_actividad)::text))) AS deno_actividad, (SELECT b.alicuota FROM shd100_actividades b WHERE ((((((a.cod_presi = b.cod_presi) AND (a.cod_entidad = b.cod_entidad)) AND (a.cod_tipo_inst = b.cod_tipo_inst)) AND (a.cod_inst = b.cod_inst)) AND (a.cod_dep = b.cod_dep)) AND ((a.cod_actividad)::text = (b.cod_actividad)::text))) AS alicuota, a.numero_aforos, a.monto_aforo_anual, a.total_aforo_anual FROM shd100_patente_actividades a;


ALTER TABLE public.v_shd100_patente_actividades OWNER TO sisap;

--
-- TOC entry 4224 (class 1259 OID 502648)
-- Dependencies: 4614 3
-- Name: v_shd100_solicitud; Type: VIEW; Schema: public; Owner: sisap
--

CREATE VIEW v_shd100_solicitud AS
    SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.numero_solicitud, a.fecha_solicitud, a.rif_cedula, b.razon_social_nombres, a.numero_ficha_catastral, a.capital, a.horario_trab_desde, a.horario_trab_hasta, a.tipo_establecimiento, a.tipo_local, a.nacionalidad AS nacionalidad_repre, a.cedula_identidad, a.nombres_apellidos, a.cod_pais AS pais_repre, (SELECT c.denominacion FROM cugd01_republica c WHERE (a.cod_pais = c.cod_republica)) AS deno_pais_repre, a.cod_estado AS estado_repre, (SELECT d.denominacion FROM cugd01_estados d WHERE ((a.cod_pais = d.cod_republica) AND (a.cod_estado = d.cod_estado))) AS deno_estado_repre, a.cod_municipio AS municipio_repre, (SELECT e.denominacion FROM cugd01_municipios e WHERE (((a.cod_pais = e.cod_republica) AND (a.cod_estado = e.cod_estado)) AND (a.cod_municipio = e.cod_municipio))) AS deno_municipio_repre, a.cod_parroquia AS parroquia_repre, (SELECT f.denominacion FROM cugd01_parroquias f WHERE ((((a.cod_pais = f.cod_republica) AND (a.cod_estado = f.cod_estado)) AND (a.cod_municipio = f.cod_municipio)) AND (a.cod_parroquia = f.cod_parroquia))) AS deno_parroquia_repre, a.cod_centro AS centro_repre, (SELECT g.denominacion FROM cugd01_centros_poblados g WHERE (((((a.cod_pais = g.cod_republica) AND (a.cod_estado = g.cod_estado)) AND (a.cod_municipio = g.cod_municipio)) AND (a.cod_parroquia = g.cod_parroquia)) AND (a.cod_centro = g.cod_centro))) AS deno_centro_repre, a.cod_vialidad AS vialidad_repre, (SELECT h.denominacion FROM cugd01_vialidad h WHERE ((((((a.cod_pais = h.cod_republica) AND (a.cod_estado = h.cod_estado)) AND (a.cod_municipio = h.cod_municipio)) AND (a.cod_parroquia = h.cod_parroquia)) AND (a.cod_centro = h.cod_centro)) AND (a.cod_vialidad = h.cod_vialidad))) AS deno_vialidad_repre, a.cod_vereda AS vereda_repre, (SELECT i.denominacion FROM cugd01_vereda i WHERE (((((((a.cod_pais = i.cod_republica) AND (a.cod_estado = i.cod_estado)) AND (a.cod_municipio = i.cod_municipio)) AND (a.cod_parroquia = i.cod_parroquia)) AND (a.cod_centro = i.cod_centro)) AND (a.cod_vialidad = i.cod_vialidad)) AND (a.cod_vereda = i.cod_vereda))) AS deno_vereda_repre, a.numero_casa_local AS numero_local_repre, a.telefonos_fijos AS telefonos_fijos_repre, a.telefonos_celulares AS telefonos_celulares_repre, a.correo_electronico AS correo_electronico_repre, a.fecha_inicio_const, a.fecha_cierre_const, a.fecha_inicio_econo, a.fecha_cierre_economico, a.registro_mercantil, a.tiene_sucursal, a.es_fabricante, a.numero_empleado, a.numero_obreros, a.distancia_bar, a.distancia_hospital, a.distancia_educativo, a.distancia_funeraria, a.distancia_estacion, a.distancia_gubernam, a.tilde_reg_mercantil, a.tilde_fotoco_ci, a.tilde_acta_const, a.tilde_uso_conforme, a.tilde_croquis, a.tilde_bomberos, a.tilde_rif, a.tilde_solvencia, a.tilde_concejo, a.tilde_recibo, a.tilde_planilla, a.tilde_permiso, a.numero_patente, b.cod_pais AS pais_razon, (SELECT c.denominacion FROM cugd01_republica c WHERE (b.cod_pais = c.cod_republica)) AS deno_pais_razon, b.cod_estado AS estado_razon, (SELECT d.denominacion FROM cugd01_estados d WHERE ((b.cod_pais = d.cod_republica) AND (b.cod_estado = d.cod_estado))) AS deno_estado_razon, b.cod_municipio AS municipio_razon, (SELECT e.denominacion FROM cugd01_municipios e WHERE (((b.cod_pais = e.cod_republica) AND (b.cod_estado = e.cod_estado)) AND (b.cod_municipio = e.cod_municipio))) AS deno_municipio_razon, b.cod_parroquia AS parroquia_razon, (SELECT f.denominacion FROM cugd01_parroquias f WHERE ((((b.cod_pais = f.cod_republica) AND (b.cod_estado = f.cod_estado)) AND (b.cod_municipio = f.cod_municipio)) AND (b.cod_parroquia = f.cod_parroquia))) AS deno_parroquia_razon, b.cod_centro_poblado AS centro_razon, (SELECT g.denominacion FROM cugd01_centros_poblados g WHERE (((((b.cod_pais = g.cod_republica) AND (b.cod_estado = g.cod_estado)) AND (b.cod_municipio = g.cod_municipio)) AND (b.cod_parroquia = g.cod_parroquia)) AND (b.cod_centro_poblado = g.cod_centro))) AS deno_centro_razon, b.cod_calle_avenida AS calle_razon, (SELECT h.denominacion FROM cugd01_vialidad h WHERE ((((((b.cod_pais = h.cod_republica) AND (b.cod_estado = h.cod_estado)) AND (b.cod_municipio = h.cod_municipio)) AND (b.cod_parroquia = h.cod_parroquia)) AND (b.cod_centro_poblado = h.cod_centro)) AND (b.cod_calle_avenida = h.cod_vialidad))) AS deno_vialidad_razon, b.cod_vereda_edificio AS vereda_razon, (SELECT i.denominacion FROM cugd01_vereda i WHERE (((((((b.cod_pais = i.cod_republica) AND (b.cod_estado = i.cod_estado)) AND (b.cod_municipio = i.cod_municipio)) AND (b.cod_parroquia = i.cod_parroquia)) AND (b.cod_centro_poblado = i.cod_centro)) AND (b.cod_calle_avenida = i.cod_vialidad)) AND (b.cod_vereda_edificio = i.cod_vereda))) AS deno_vereda_razon, b.fecha_inscripcion, b.telefonos_fijos AS telefonos_fijos_razon, b.telefonos_celulares AS telefonos_celulares_razon, b.correo_electronico AS correo_electronico_razon, b.nacionalidad AS nacionalidad_razon, b.estado_civil, b.numero_vivienda_local AS numero_local_razon, b.profesion, (SELECT j.denominacion FROM cnmd06_profesiones j WHERE (b.profesion = j.cod_profesion)) AS deno_profesion, a.categoria_comercial, a.mercado_cubre, (SELECT x.fecha_patente FROM shd100_patente x WHERE (((((((x.rif_cedula)::text = (a.rif_cedula)::text) AND ((a.numero_patente)::text = (x.numero_patente)::text)) AND (a.cod_presi = x.cod_presi)) AND (a.cod_entidad = x.cod_entidad)) AND (a.cod_tipo_inst = x.cod_tipo_inst)) AND (a.cod_dep = x.cod_dep))) AS fecha_patente, (SELECT x.frecuencia_pago FROM shd100_patente x WHERE (((((((x.rif_cedula)::text = (a.rif_cedula)::text) AND ((a.numero_patente)::text = (x.numero_patente)::text)) AND (a.cod_presi = x.cod_presi)) AND (a.cod_entidad = x.cod_entidad)) AND (a.cod_tipo_inst = x.cod_tipo_inst)) AND (a.cod_dep = x.cod_dep))) AS frecuencia_pago FROM shd100_solicitud a, shd001_registro_contribuyentes b WHERE ((a.rif_cedula)::text = (b.rif_cedula)::text);


ALTER TABLE public.v_shd100_solicitud OWNER TO sisap;

--
-- TOC entry 4215 (class 1259 OID 502443)
-- Dependencies: 4605 3
-- Name: v_shd100_solicitud_actividades; Type: VIEW; Schema: public; Owner: sisap
--

CREATE VIEW v_shd100_solicitud_actividades AS
    SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.numero_solicitud, a.cod_actividad, b.denominacion_actividad, b.alicuota, b.unidades_tributarias, b.minimo_tributable FROM shd100_solicitud_actividades a, shd100_actividades b WHERE ((((((a.cod_presi = b.cod_presi) AND (a.cod_entidad = b.cod_entidad)) AND (a.cod_tipo_inst = b.cod_tipo_inst)) AND (a.cod_inst = b.cod_inst)) AND (a.cod_dep = b.cod_dep)) AND ((a.cod_actividad)::text = (b.cod_actividad)::text));


ALTER TABLE public.v_shd100_solicitud_actividades OWNER TO sisap;

--
-- TOC entry 4202 (class 1259 OID 502359)
-- Dependencies: 4592 3
-- Name: v_shd200_vehiculos; Type: VIEW; Schema: public; Owner: sisap
--

CREATE VIEW v_shd200_vehiculos AS
    SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.rif_cedula, (SELECT b.razon_social_nombres FROM v_shd001_registro_contribuyentes b WHERE ((a.rif_cedula)::text = (b.rif_cedula)::text)) AS nombre_razon, (SELECT b.cod_pais FROM v_shd001_registro_contribuyentes b WHERE ((a.rif_cedula)::text = (b.rif_cedula)::text)) AS cod_pais, (SELECT b.deno_pais FROM v_shd001_registro_contribuyentes b WHERE ((a.rif_cedula)::text = (b.rif_cedula)::text)) AS deno_pais, (SELECT b.cod_estado FROM v_shd001_registro_contribuyentes b WHERE ((a.rif_cedula)::text = (b.rif_cedula)::text)) AS cod_estado, (SELECT b.deno_estado FROM v_shd001_registro_contribuyentes b WHERE ((a.rif_cedula)::text = (b.rif_cedula)::text)) AS deno_estado, (SELECT b.cod_municipio FROM v_shd001_registro_contribuyentes b WHERE ((a.rif_cedula)::text = (b.rif_cedula)::text)) AS cod_municipio, (SELECT b.deno_municipio FROM v_shd001_registro_contribuyentes b WHERE ((a.rif_cedula)::text = (b.rif_cedula)::text)) AS deno_municipio, (SELECT b.cod_parroquia FROM v_shd001_registro_contribuyentes b WHERE ((a.rif_cedula)::text = (b.rif_cedula)::text)) AS cod_parroquia, (SELECT b.deno_parroquia FROM v_shd001_registro_contribuyentes b WHERE ((a.rif_cedula)::text = (b.rif_cedula)::text)) AS deno_parroquia, (SELECT b.cod_centro_poblado FROM v_shd001_registro_contribuyentes b WHERE ((a.rif_cedula)::text = (b.rif_cedula)::text)) AS cod_centro, (SELECT b.deno_centro FROM v_shd001_registro_contribuyentes b WHERE ((a.rif_cedula)::text = (b.rif_cedula)::text)) AS deno_centro, (SELECT b.cod_calle_avenida FROM v_shd001_registro_contribuyentes b WHERE ((a.rif_cedula)::text = (b.rif_cedula)::text)) AS cod_calle, (SELECT b.deno_vialidad FROM v_shd001_registro_contribuyentes b WHERE ((a.rif_cedula)::text = (b.rif_cedula)::text)) AS deno_calle, (SELECT b.cod_vereda_edificio FROM v_shd001_registro_contribuyentes b WHERE ((a.rif_cedula)::text = (b.rif_cedula)::text)) AS cod_vereda_edificio, (SELECT b.deno_vereda FROM v_shd001_registro_contribuyentes b WHERE ((a.rif_cedula)::text = (b.rif_cedula)::text)) AS deno_vereda, (SELECT b.numero_vivienda_local FROM v_shd001_registro_contribuyentes b WHERE ((a.rif_cedula)::text = (b.rif_cedula)::text)) AS numero_casa, (SELECT b.fecha_inscripcion FROM v_shd001_registro_contribuyentes b WHERE ((a.rif_cedula)::text = (b.rif_cedula)::text)) AS fecha_inscripcion, (SELECT b.telefonos_fijos FROM v_shd001_registro_contribuyentes b WHERE ((a.rif_cedula)::text = (b.rif_cedula)::text)) AS telefonos_fijos, (SELECT b.telefonos_celulares FROM v_shd001_registro_contribuyentes b WHERE ((a.rif_cedula)::text = (b.rif_cedula)::text)) AS telefonos_celulares, (SELECT b.correo_electronico FROM v_shd001_registro_contribuyentes b WHERE ((a.rif_cedula)::text = (b.rif_cedula)::text)) AS correo_electronico, (SELECT b.nacionalidad FROM v_shd001_registro_contribuyentes b WHERE ((a.rif_cedula)::text = (b.rif_cedula)::text)) AS nacionalidad, (SELECT b.estado_civil FROM v_shd001_registro_contribuyentes b WHERE ((a.rif_cedula)::text = (b.rif_cedula)::text)) AS estado_civil, (SELECT b.deno_profesion FROM v_shd001_registro_contribuyentes b WHERE ((a.rif_cedula)::text = (b.rif_cedula)::text)) AS deno_profesion, a.placa_vehiculo, a.fecha_registro, a.cod_marca, (SELECT e.denominacion FROM shd200_vehiculos_marcas e WHERE (a.cod_marca = e.codigo_marca)) AS deno_marca, a.cod_modelo, (SELECT f.denominacion FROM shd200_vehiculos_modelos f WHERE (a.cod_modelo = f.codigo_modelo)) AS deno_modelo, a.cod_color, (SELECT g.denominacion FROM shd200_vehiculos_colores g WHERE (a.cod_color = g.codigo_color)) AS deno_color, a.cod_clase, (SELECT h.denominacion FROM shd200_vehiculos_clases h WHERE (a.cod_clase = h.codigo_clase)) AS deno_clase, a.cod_tipo, (SELECT i.denominacion FROM shd200_vehiculos_tipos i WHERE (a.cod_tipo = i.codigo_tipo)) AS deno_tipo, a.cod_uso, (SELECT j.denominacion FROM shd200_vehiculos_usos j WHERE (a.cod_uso = j.codigo_uso)) AS deno_uso, a.serial_carroceria, a.serial_motor, a.ano_adquisicion, a.valor_vehiculo, a.fecha_adquisicion, a.cod_clasificacion, (SELECT c.denominacion FROM shd200_vehiculos_clasificacion c WHERE ((((((a.cod_presi = c.cod_presi) AND (a.cod_entidad = c.cod_entidad)) AND (a.cod_tipo_inst = c.cod_tipo_inst)) AND (a.cod_inst = c.cod_inst)) AND (a.cod_dep = c.cod_dep)) AND ((a.cod_clasificacion)::text = (c.cod_clasificacion)::text))) AS deno_clasificacion, (SELECT c.monto_anual FROM shd200_vehiculos_clasificacion c WHERE ((((((a.cod_presi = c.cod_presi) AND (a.cod_entidad = c.cod_entidad)) AND (a.cod_tipo_inst = c.cod_tipo_inst)) AND (a.cod_inst = c.cod_inst)) AND (a.cod_dep = c.cod_dep)) AND ((a.cod_clasificacion)::text = (c.cod_clasificacion)::text))) AS monto_anual, a.frecuencia_pago, a.monto_mensual, a.pago_todo, a.suspendido, a.rif_ci_cobrador, (SELECT d.nombre_razon FROM shd002_cobradores d WHERE ((((((a.cod_presi = d.cod_presi) AND (a.cod_entidad = d.cod_entidad)) AND (a.cod_tipo_inst = d.cod_tipo_inst)) AND (a.cod_inst = d.cod_inst)) AND (a.cod_dep = d.cod_dep)) AND ((a.rif_ci_cobrador)::text = (d.rif_ci)::text))) AS deno_cobrador, a.ultimo_ano_facturado, a.ultimo_mes_facturado FROM shd200_vehiculos a;


ALTER TABLE public.v_shd200_vehiculos OWNER TO sisap;

--
-- TOC entry 4218 (class 1259 OID 502467)
-- Dependencies: 4608 3
-- Name: v_shd300_propaganda; Type: VIEW; Schema: public; Owner: sisap
--

CREATE VIEW v_shd300_propaganda AS
    SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.rif_cedula, (SELECT b.razon_social_nombres FROM v_shd001_registro_contribuyentes b WHERE ((a.rif_cedula)::text = (b.rif_cedula)::text)) AS nombre_razon, (SELECT b.cod_pais FROM v_shd001_registro_contribuyentes b WHERE ((a.rif_cedula)::text = (b.rif_cedula)::text)) AS cod_pais, (SELECT b.deno_pais FROM v_shd001_registro_contribuyentes b WHERE ((a.rif_cedula)::text = (b.rif_cedula)::text)) AS deno_pais, (SELECT b.cod_estado FROM v_shd001_registro_contribuyentes b WHERE ((a.rif_cedula)::text = (b.rif_cedula)::text)) AS cod_estado, (SELECT b.deno_estado FROM v_shd001_registro_contribuyentes b WHERE ((a.rif_cedula)::text = (b.rif_cedula)::text)) AS deno_estado, (SELECT b.cod_municipio FROM v_shd001_registro_contribuyentes b WHERE ((a.rif_cedula)::text = (b.rif_cedula)::text)) AS cod_municipio, (SELECT b.deno_municipio FROM v_shd001_registro_contribuyentes b WHERE ((a.rif_cedula)::text = (b.rif_cedula)::text)) AS deno_municipio, (SELECT b.cod_parroquia FROM v_shd001_registro_contribuyentes b WHERE ((a.rif_cedula)::text = (b.rif_cedula)::text)) AS cod_parroquia, (SELECT b.deno_parroquia FROM v_shd001_registro_contribuyentes b WHERE ((a.rif_cedula)::text = (b.rif_cedula)::text)) AS deno_parroquia, (SELECT b.cod_centro_poblado FROM v_shd001_registro_contribuyentes b WHERE ((a.rif_cedula)::text = (b.rif_cedula)::text)) AS cod_centro, (SELECT b.deno_centro FROM v_shd001_registro_contribuyentes b WHERE ((a.rif_cedula)::text = (b.rif_cedula)::text)) AS deno_centro, (SELECT b.cod_calle_avenida FROM v_shd001_registro_contribuyentes b WHERE ((a.rif_cedula)::text = (b.rif_cedula)::text)) AS cod_calle, (SELECT b.deno_vialidad FROM v_shd001_registro_contribuyentes b WHERE ((a.rif_cedula)::text = (b.rif_cedula)::text)) AS deno_calle, (SELECT b.cod_vereda_edificio FROM v_shd001_registro_contribuyentes b WHERE ((a.rif_cedula)::text = (b.rif_cedula)::text)) AS cod_vereda_edificio, (SELECT b.deno_vereda FROM v_shd001_registro_contribuyentes b WHERE ((a.rif_cedula)::text = (b.rif_cedula)::text)) AS deno_vereda, (SELECT b.numero_vivienda_local FROM v_shd001_registro_contribuyentes b WHERE ((a.rif_cedula)::text = (b.rif_cedula)::text)) AS numero_casa, (SELECT b.fecha_inscripcion FROM v_shd001_registro_contribuyentes b WHERE ((a.rif_cedula)::text = (b.rif_cedula)::text)) AS fecha_inscripcion_cont, (SELECT b.telefonos_fijos FROM v_shd001_registro_contribuyentes b WHERE ((a.rif_cedula)::text = (b.rif_cedula)::text)) AS telefonos_fijos, (SELECT b.telefonos_celulares FROM v_shd001_registro_contribuyentes b WHERE ((a.rif_cedula)::text = (b.rif_cedula)::text)) AS telefonos_celulares, (SELECT b.correo_electronico FROM v_shd001_registro_contribuyentes b WHERE ((a.rif_cedula)::text = (b.rif_cedula)::text)) AS correo_electronico, (SELECT b.nacionalidad FROM v_shd001_registro_contribuyentes b WHERE ((a.rif_cedula)::text = (b.rif_cedula)::text)) AS nacionalidad, (SELECT b.estado_civil FROM v_shd001_registro_contribuyentes b WHERE ((a.rif_cedula)::text = (b.rif_cedula)::text)) AS estado_civil, (SELECT b.deno_profesion FROM v_shd001_registro_contribuyentes b WHERE ((a.rif_cedula)::text = (b.rif_cedula)::text)) AS deno_profesion, a.frecuencia_pago, a.monto_mensual_general, a.pago_todo, a.suspendido, a.rif_ci_cobrador, (SELECT d.nombre_razon FROM shd002_cobradores d WHERE ((((((a.cod_presi = d.cod_presi) AND (a.cod_entidad = d.cod_entidad)) AND (a.cod_tipo_inst = d.cod_tipo_inst)) AND (a.cod_inst = d.cod_inst)) AND (a.cod_dep = d.cod_dep)) AND ((a.rif_ci_cobrador)::text = (d.rif_ci)::text))) AS deno_cobrador, a.ultimo_ano_facturado, a.ultimo_mes_facturado FROM shd300_propaganda a;


ALTER TABLE public.v_shd300_propaganda OWNER TO sisap;

--
-- TOC entry 4263 (class 1259 OID 503115)
-- Dependencies: 4649 3
-- Name: v_shd400_propiedad; Type: VIEW; Schema: public; Owner: sisap
--

CREATE VIEW v_shd400_propiedad AS
    SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.rif_cedula, (SELECT b.razon_social_nombres FROM v_shd001_registro_contribuyentes b WHERE ((a.rif_cedula)::text = (b.rif_cedula)::text)) AS nombre_razon, (SELECT b.cod_pais FROM v_shd001_registro_contribuyentes b WHERE ((a.rif_cedula)::text = (b.rif_cedula)::text)) AS cod_pais, (SELECT b.deno_pais FROM v_shd001_registro_contribuyentes b WHERE ((a.rif_cedula)::text = (b.rif_cedula)::text)) AS deno_pais, (SELECT b.cod_estado FROM v_shd001_registro_contribuyentes b WHERE ((a.rif_cedula)::text = (b.rif_cedula)::text)) AS cod_estado, (SELECT b.deno_estado FROM v_shd001_registro_contribuyentes b WHERE ((a.rif_cedula)::text = (b.rif_cedula)::text)) AS deno_estado, (SELECT b.cod_municipio FROM v_shd001_registro_contribuyentes b WHERE ((a.rif_cedula)::text = (b.rif_cedula)::text)) AS cod_municipio, (SELECT b.deno_municipio FROM v_shd001_registro_contribuyentes b WHERE ((a.rif_cedula)::text = (b.rif_cedula)::text)) AS deno_municipio, (SELECT b.cod_parroquia FROM v_shd001_registro_contribuyentes b WHERE ((a.rif_cedula)::text = (b.rif_cedula)::text)) AS cod_parroquia, (SELECT b.deno_parroquia FROM v_shd001_registro_contribuyentes b WHERE ((a.rif_cedula)::text = (b.rif_cedula)::text)) AS deno_parroquia, (SELECT b.cod_centro_poblado FROM v_shd001_registro_contribuyentes b WHERE ((a.rif_cedula)::text = (b.rif_cedula)::text)) AS cod_centro, (SELECT b.deno_centro FROM v_shd001_registro_contribuyentes b WHERE ((a.rif_cedula)::text = (b.rif_cedula)::text)) AS deno_centro, (SELECT b.cod_calle_avenida FROM v_shd001_registro_contribuyentes b WHERE ((a.rif_cedula)::text = (b.rif_cedula)::text)) AS cod_calle, (SELECT b.deno_vialidad FROM v_shd001_registro_contribuyentes b WHERE ((a.rif_cedula)::text = (b.rif_cedula)::text)) AS deno_calle, (SELECT b.cod_vereda_edificio FROM v_shd001_registro_contribuyentes b WHERE ((a.rif_cedula)::text = (b.rif_cedula)::text)) AS cod_vereda_edificio, (SELECT b.deno_vereda FROM v_shd001_registro_contribuyentes b WHERE ((a.rif_cedula)::text = (b.rif_cedula)::text)) AS deno_vereda, (SELECT b.numero_vivienda_local FROM v_shd001_registro_contribuyentes b WHERE ((a.rif_cedula)::text = (b.rif_cedula)::text)) AS numero_casa, (SELECT b.fecha_inscripcion FROM v_shd001_registro_contribuyentes b WHERE ((a.rif_cedula)::text = (b.rif_cedula)::text)) AS fecha_inscripcion_cont, (SELECT b.telefonos_fijos FROM v_shd001_registro_contribuyentes b WHERE ((a.rif_cedula)::text = (b.rif_cedula)::text)) AS telefonos_fijos, (SELECT b.telefonos_celulares FROM v_shd001_registro_contribuyentes b WHERE ((a.rif_cedula)::text = (b.rif_cedula)::text)) AS telefonos_celulares, (SELECT b.correo_electronico FROM v_shd001_registro_contribuyentes b WHERE ((a.rif_cedula)::text = (b.rif_cedula)::text)) AS correo_electronico, (SELECT b.nacionalidad FROM v_shd001_registro_contribuyentes b WHERE ((a.rif_cedula)::text = (b.rif_cedula)::text)) AS nacionalidad, (SELECT b.estado_civil FROM v_shd001_registro_contribuyentes b WHERE ((a.rif_cedula)::text = (b.rif_cedula)::text)) AS estado_civil, (SELECT b.deno_profesion FROM v_shd001_registro_contribuyentes b WHERE ((a.rif_cedula)::text = (b.rif_cedula)::text)) AS deno_profesion, a.cod_ficha, a.frecuencia_pago, a.monto_mensual, a.pago_todo, a.suspendido, a.rif_ci_cobrador, (SELECT e.nombre_razon FROM shd002_cobradores e WHERE ((((((a.cod_presi = e.cod_presi) AND (a.cod_entidad = e.cod_entidad)) AND (a.cod_tipo_inst = e.cod_tipo_inst)) AND (a.cod_inst = e.cod_inst)) AND (a.cod_dep = e.cod_dep)) AND ((a.rif_ci_cobrador)::text = (e.rif_ci)::text))) AS deno_cobrador, a.ultimo_ano_facturado, a.ultimo_mes_facturado FROM shd400_propiedad a;


ALTER TABLE public.v_shd400_propiedad OWNER TO sisap;

--
-- TOC entry 4217 (class 1259 OID 502462)
-- Dependencies: 4607 3
-- Name: v_shd500_aseo_domiciliario; Type: VIEW; Schema: public; Owner: sisap
--

CREATE VIEW v_shd500_aseo_domiciliario AS
    SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.rif_cedula, (SELECT b.razon_social_nombres FROM v_shd001_registro_contribuyentes b WHERE ((a.rif_cedula)::text = (b.rif_cedula)::text)) AS nombre_razon, (SELECT b.cod_pais FROM v_shd001_registro_contribuyentes b WHERE ((a.rif_cedula)::text = (b.rif_cedula)::text)) AS cod_pais, (SELECT b.deno_pais FROM v_shd001_registro_contribuyentes b WHERE ((a.rif_cedula)::text = (b.rif_cedula)::text)) AS deno_pais, (SELECT b.cod_estado FROM v_shd001_registro_contribuyentes b WHERE ((a.rif_cedula)::text = (b.rif_cedula)::text)) AS cod_estado, (SELECT b.deno_estado FROM v_shd001_registro_contribuyentes b WHERE ((a.rif_cedula)::text = (b.rif_cedula)::text)) AS deno_estado, (SELECT b.cod_municipio FROM v_shd001_registro_contribuyentes b WHERE ((a.rif_cedula)::text = (b.rif_cedula)::text)) AS cod_municipio, (SELECT b.deno_municipio FROM v_shd001_registro_contribuyentes b WHERE ((a.rif_cedula)::text = (b.rif_cedula)::text)) AS deno_municipio, (SELECT b.cod_parroquia FROM v_shd001_registro_contribuyentes b WHERE ((a.rif_cedula)::text = (b.rif_cedula)::text)) AS cod_parroquia, (SELECT b.deno_parroquia FROM v_shd001_registro_contribuyentes b WHERE ((a.rif_cedula)::text = (b.rif_cedula)::text)) AS deno_parroquia, (SELECT b.cod_centro_poblado FROM v_shd001_registro_contribuyentes b WHERE ((a.rif_cedula)::text = (b.rif_cedula)::text)) AS cod_centro, (SELECT b.deno_centro FROM v_shd001_registro_contribuyentes b WHERE ((a.rif_cedula)::text = (b.rif_cedula)::text)) AS deno_centro, (SELECT b.cod_calle_avenida FROM v_shd001_registro_contribuyentes b WHERE ((a.rif_cedula)::text = (b.rif_cedula)::text)) AS cod_calle, (SELECT b.deno_vialidad FROM v_shd001_registro_contribuyentes b WHERE ((a.rif_cedula)::text = (b.rif_cedula)::text)) AS deno_calle, (SELECT b.cod_vereda_edificio FROM v_shd001_registro_contribuyentes b WHERE ((a.rif_cedula)::text = (b.rif_cedula)::text)) AS cod_vereda_edificio, (SELECT b.deno_vereda FROM v_shd001_registro_contribuyentes b WHERE ((a.rif_cedula)::text = (b.rif_cedula)::text)) AS deno_vereda, (SELECT b.numero_vivienda_local FROM v_shd001_registro_contribuyentes b WHERE ((a.rif_cedula)::text = (b.rif_cedula)::text)) AS numero_casa, (SELECT b.fecha_inscripcion FROM v_shd001_registro_contribuyentes b WHERE ((a.rif_cedula)::text = (b.rif_cedula)::text)) AS fecha_inscripcion, (SELECT b.telefonos_fijos FROM v_shd001_registro_contribuyentes b WHERE ((a.rif_cedula)::text = (b.rif_cedula)::text)) AS telefonos_fijos, (SELECT b.telefonos_celulares FROM v_shd001_registro_contribuyentes b WHERE ((a.rif_cedula)::text = (b.rif_cedula)::text)) AS telefonos_celulares, (SELECT b.correo_electronico FROM v_shd001_registro_contribuyentes b WHERE ((a.rif_cedula)::text = (b.rif_cedula)::text)) AS correo_electronico, (SELECT b.nacionalidad FROM v_shd001_registro_contribuyentes b WHERE ((a.rif_cedula)::text = (b.rif_cedula)::text)) AS nacionalidad, (SELECT b.estado_civil FROM v_shd001_registro_contribuyentes b WHERE ((a.rif_cedula)::text = (b.rif_cedula)::text)) AS estado_civil, (SELECT b.deno_profesion FROM v_shd001_registro_contribuyentes b WHERE ((a.rif_cedula)::text = (b.rif_cedula)::text)) AS deno_profesion, a.fecha_registro, a.cod_clasificacion, (SELECT c.denominacion FROM shd500_aseo_clasificacion c WHERE ((((((a.cod_presi = c.cod_presi) AND (a.cod_entidad = c.cod_entidad)) AND (a.cod_tipo_inst = c.cod_tipo_inst)) AND (a.cod_inst = c.cod_inst)) AND (a.cod_dep = c.cod_dep)) AND ((a.cod_clasificacion)::text = (c.cod_clasificacion)::text))) AS deno_clasificacion, a.frecuencia_pago, a.monto_mensual, a.pago_todo, a.suspendido, a.rif_ci_cobrador, (SELECT d.nombre_razon FROM shd002_cobradores d WHERE ((((((a.cod_presi = d.cod_presi) AND (a.cod_entidad = d.cod_entidad)) AND (a.cod_tipo_inst = d.cod_tipo_inst)) AND (a.cod_inst = d.cod_inst)) AND (a.cod_dep = d.cod_dep)) AND ((a.rif_ci_cobrador)::text = (d.rif_ci)::text))) AS deno_cobrador, a.ultimo_ano_facturado, a.ultimo_mes_facturado FROM shd500_aseo_domiciliario a;


ALTER TABLE public.v_shd500_aseo_domiciliario OWNER TO sisap;

--
-- TOC entry 4265 (class 1259 OID 503125)
-- Dependencies: 4651 3
-- Name: v_shd600_aprobacion_arrendamiento; Type: VIEW; Schema: public; Owner: sisap
--

CREATE VIEW v_shd600_aprobacion_arrendamiento AS
    SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.numero_solicitud, a.rif_cedula, (SELECT b.razon_social_nombres FROM v_shd001_registro_contribuyentes b WHERE ((a.rif_cedula)::text = (b.rif_cedula)::text)) AS nombre_razon, (SELECT b.cod_pais FROM v_shd001_registro_contribuyentes b WHERE ((a.rif_cedula)::text = (b.rif_cedula)::text)) AS cod_pais, (SELECT b.deno_pais FROM v_shd001_registro_contribuyentes b WHERE ((a.rif_cedula)::text = (b.rif_cedula)::text)) AS deno_pais, (SELECT b.cod_estado FROM v_shd001_registro_contribuyentes b WHERE ((a.rif_cedula)::text = (b.rif_cedula)::text)) AS cod_estado, (SELECT b.deno_estado FROM v_shd001_registro_contribuyentes b WHERE ((a.rif_cedula)::text = (b.rif_cedula)::text)) AS deno_estado, (SELECT b.cod_municipio FROM v_shd001_registro_contribuyentes b WHERE ((a.rif_cedula)::text = (b.rif_cedula)::text)) AS cod_municipio, (SELECT b.deno_municipio FROM v_shd001_registro_contribuyentes b WHERE ((a.rif_cedula)::text = (b.rif_cedula)::text)) AS deno_municipio, (SELECT b.cod_parroquia FROM v_shd001_registro_contribuyentes b WHERE ((a.rif_cedula)::text = (b.rif_cedula)::text)) AS cod_parroquia, (SELECT b.deno_parroquia FROM v_shd001_registro_contribuyentes b WHERE ((a.rif_cedula)::text = (b.rif_cedula)::text)) AS deno_parroquia, (SELECT b.cod_centro_poblado FROM v_shd001_registro_contribuyentes b WHERE ((a.rif_cedula)::text = (b.rif_cedula)::text)) AS cod_centro, (SELECT b.deno_centro FROM v_shd001_registro_contribuyentes b WHERE ((a.rif_cedula)::text = (b.rif_cedula)::text)) AS deno_centro, (SELECT b.cod_calle_avenida FROM v_shd001_registro_contribuyentes b WHERE ((a.rif_cedula)::text = (b.rif_cedula)::text)) AS cod_calle, (SELECT b.deno_vialidad FROM v_shd001_registro_contribuyentes b WHERE ((a.rif_cedula)::text = (b.rif_cedula)::text)) AS deno_calle, (SELECT b.cod_vereda_edificio FROM v_shd001_registro_contribuyentes b WHERE ((a.rif_cedula)::text = (b.rif_cedula)::text)) AS cod_vereda_edificio, (SELECT b.deno_vereda FROM v_shd001_registro_contribuyentes b WHERE ((a.rif_cedula)::text = (b.rif_cedula)::text)) AS deno_vereda, (SELECT b.numero_vivienda_local FROM v_shd001_registro_contribuyentes b WHERE ((a.rif_cedula)::text = (b.rif_cedula)::text)) AS numero_casa, (SELECT b.fecha_inscripcion FROM v_shd001_registro_contribuyentes b WHERE ((a.rif_cedula)::text = (b.rif_cedula)::text)) AS fecha_inscripcion_cont, (SELECT b.telefonos_fijos FROM v_shd001_registro_contribuyentes b WHERE ((a.rif_cedula)::text = (b.rif_cedula)::text)) AS telefonos_fijos, (SELECT b.telefonos_celulares FROM v_shd001_registro_contribuyentes b WHERE ((a.rif_cedula)::text = (b.rif_cedula)::text)) AS telefonos_celulares, (SELECT b.correo_electronico FROM v_shd001_registro_contribuyentes b WHERE ((a.rif_cedula)::text = (b.rif_cedula)::text)) AS correo_electronico, (SELECT b.nacionalidad FROM v_shd001_registro_contribuyentes b WHERE ((a.rif_cedula)::text = (b.rif_cedula)::text)) AS nacionalidad, (SELECT b.estado_civil FROM v_shd001_registro_contribuyentes b WHERE ((a.rif_cedula)::text = (b.rif_cedula)::text)) AS estado_civil, (SELECT b.deno_profesion FROM v_shd001_registro_contribuyentes b WHERE ((a.rif_cedula)::text = (b.rif_cedula)::text)) AS deno_profesion, (SELECT b.expectativa_construccion FROM shd600_solicitud_arrendamiento b WHERE ((((((a.cod_presi = b.cod_presi) AND (a.cod_entidad = b.cod_entidad)) AND (a.cod_tipo_inst = b.cod_tipo_inst)) AND (a.cod_inst = b.cod_inst)) AND (a.cod_dep = b.cod_dep)) AND ((a.numero_solicitud)::text = (b.numero_solicitud)::text))) AS expectativa_construccion, a.fecha_aprobacion, a.frecuencia_pago, a.datos_registro_arrendamiento, a.monto_mensual, a.pago_todo, a.suspendido, a.rif_ci_cobrador, (SELECT e.nombre_razon FROM shd002_cobradores e WHERE ((((((a.cod_presi = e.cod_presi) AND (a.cod_entidad = e.cod_entidad)) AND (a.cod_tipo_inst = e.cod_tipo_inst)) AND (a.cod_inst = e.cod_inst)) AND (a.cod_dep = e.cod_dep)) AND ((a.rif_ci_cobrador)::text = (e.rif_ci)::text))) AS deno_cobrador, a.ultimo_ano_facturado, a.ultimo_mes_facturado, a.terreno_vendido, (SELECT b.monto FROM shd600_compra_terreno b WHERE ((((((a.cod_presi = b.cod_presi) AND (a.cod_entidad = b.cod_entidad)) AND (a.cod_tipo_inst = b.cod_tipo_inst)) AND (a.cod_inst = b.cod_inst)) AND (a.cod_dep = b.cod_dep)) AND ((a.numero_solicitud)::text = (b.numero_solicitud)::text))) AS monto, (SELECT b.fecha_compra FROM shd600_compra_terreno b WHERE ((((((a.cod_presi = b.cod_presi) AND (a.cod_entidad = b.cod_entidad)) AND (a.cod_tipo_inst = b.cod_tipo_inst)) AND (a.cod_inst = b.cod_inst)) AND (a.cod_dep = b.cod_dep)) AND ((a.numero_solicitud)::text = (b.numero_solicitud)::text))) AS fecha_venta, (SELECT b.cod_ficha FROM shd600_solicitud_arrendamiento b WHERE ((((((a.cod_presi = b.cod_presi) AND (a.cod_entidad = b.cod_entidad)) AND (a.cod_tipo_inst = b.cod_tipo_inst)) AND (a.cod_inst = b.cod_inst)) AND (a.cod_dep = b.cod_dep)) AND ((a.numero_solicitud)::text = (b.numero_solicitud)::text))) AS cod_ficha, (SELECT b.opcion FROM shd600_solicitud_arrendamiento b WHERE ((((((a.cod_presi = b.cod_presi) AND (a.cod_entidad = b.cod_entidad)) AND (a.cod_tipo_inst = b.cod_tipo_inst)) AND (a.cod_inst = b.cod_inst)) AND (a.cod_dep = b.cod_dep)) AND ((a.numero_solicitud)::text = (b.numero_solicitud)::text))) AS opcion FROM shd600_aprobacion_arrendamiento a;


ALTER TABLE public.v_shd600_aprobacion_arrendamiento OWNER TO sisap;

--
-- TOC entry 4266 (class 1259 OID 503130)
-- Dependencies: 4652 3
-- Name: v_shd600_compra_terreno; Type: VIEW; Schema: public; Owner: sisap
--

CREATE VIEW v_shd600_compra_terreno AS
    SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.numero_solicitud, a.rif_cedula, (SELECT b.terreno_vendido FROM shd600_aprobacion_arrendamiento b WHERE (((((((a.cod_presi = b.cod_presi) AND (a.cod_entidad = b.cod_entidad)) AND (a.cod_tipo_inst = b.cod_tipo_inst)) AND (a.cod_inst = b.cod_inst)) AND (a.cod_dep = b.cod_dep)) AND ((a.numero_solicitud)::text = (b.numero_solicitud)::text)) AND ((a.rif_cedula)::text = (b.rif_cedula)::text))) AS terreno_vendido, a.fecha_compra, a.datos_compra, a.monto, (SELECT b.opcion FROM shd600_solicitud_arrendamiento b WHERE (((((((a.cod_presi = b.cod_presi) AND (a.cod_entidad = b.cod_entidad)) AND (a.cod_tipo_inst = b.cod_tipo_inst)) AND (a.cod_inst = b.cod_inst)) AND (a.cod_dep = b.cod_dep)) AND ((a.numero_solicitud)::text = (b.numero_solicitud)::text)) AND ((a.rif_cedula)::text = (b.rif_cedula)::text))) AS opcion, (SELECT b.razon_social_nombres FROM v_shd001_registro_contribuyentes b WHERE ((a.rif_cedula)::text = (b.rif_cedula)::text)) AS nombre_razon, (SELECT b.cod_pais FROM v_shd001_registro_contribuyentes b WHERE ((a.rif_cedula)::text = (b.rif_cedula)::text)) AS cod_pais, (SELECT b.deno_pais FROM v_shd001_registro_contribuyentes b WHERE ((a.rif_cedula)::text = (b.rif_cedula)::text)) AS deno_pais, (SELECT b.cod_estado FROM v_shd001_registro_contribuyentes b WHERE ((a.rif_cedula)::text = (b.rif_cedula)::text)) AS cod_estado, (SELECT b.deno_estado FROM v_shd001_registro_contribuyentes b WHERE ((a.rif_cedula)::text = (b.rif_cedula)::text)) AS deno_estado, (SELECT b.cod_municipio FROM v_shd001_registro_contribuyentes b WHERE ((a.rif_cedula)::text = (b.rif_cedula)::text)) AS cod_municipio, (SELECT b.deno_municipio FROM v_shd001_registro_contribuyentes b WHERE ((a.rif_cedula)::text = (b.rif_cedula)::text)) AS deno_municipio, (SELECT b.cod_parroquia FROM v_shd001_registro_contribuyentes b WHERE ((a.rif_cedula)::text = (b.rif_cedula)::text)) AS cod_parroquia, (SELECT b.deno_parroquia FROM v_shd001_registro_contribuyentes b WHERE ((a.rif_cedula)::text = (b.rif_cedula)::text)) AS deno_parroquia, (SELECT b.cod_centro_poblado FROM v_shd001_registro_contribuyentes b WHERE ((a.rif_cedula)::text = (b.rif_cedula)::text)) AS cod_centro, (SELECT b.deno_centro FROM v_shd001_registro_contribuyentes b WHERE ((a.rif_cedula)::text = (b.rif_cedula)::text)) AS deno_centro, (SELECT b.cod_calle_avenida FROM v_shd001_registro_contribuyentes b WHERE ((a.rif_cedula)::text = (b.rif_cedula)::text)) AS cod_calle, (SELECT b.deno_vialidad FROM v_shd001_registro_contribuyentes b WHERE ((a.rif_cedula)::text = (b.rif_cedula)::text)) AS deno_calle, (SELECT b.cod_vereda_edificio FROM v_shd001_registro_contribuyentes b WHERE ((a.rif_cedula)::text = (b.rif_cedula)::text)) AS cod_vereda_edificio, (SELECT b.deno_vereda FROM v_shd001_registro_contribuyentes b WHERE ((a.rif_cedula)::text = (b.rif_cedula)::text)) AS deno_vereda, (SELECT b.numero_vivienda_local FROM v_shd001_registro_contribuyentes b WHERE ((a.rif_cedula)::text = (b.rif_cedula)::text)) AS numero_casa, (SELECT b.fecha_inscripcion FROM v_shd001_registro_contribuyentes b WHERE ((a.rif_cedula)::text = (b.rif_cedula)::text)) AS fecha_inscripcion_cont, (SELECT b.telefonos_fijos FROM v_shd001_registro_contribuyentes b WHERE ((a.rif_cedula)::text = (b.rif_cedula)::text)) AS telefonos_fijos, (SELECT b.telefonos_celulares FROM v_shd001_registro_contribuyentes b WHERE ((a.rif_cedula)::text = (b.rif_cedula)::text)) AS telefonos_celulares, (SELECT b.correo_electronico FROM v_shd001_registro_contribuyentes b WHERE ((a.rif_cedula)::text = (b.rif_cedula)::text)) AS correo_electronico, (SELECT b.nacionalidad FROM v_shd001_registro_contribuyentes b WHERE ((a.rif_cedula)::text = (b.rif_cedula)::text)) AS nacionalidad, (SELECT b.estado_civil FROM v_shd001_registro_contribuyentes b WHERE ((a.rif_cedula)::text = (b.rif_cedula)::text)) AS estado_civil, (SELECT b.deno_profesion FROM v_shd001_registro_contribuyentes b WHERE ((a.rif_cedula)::text = (b.rif_cedula)::text)) AS deno_profesion, (SELECT b.cod_ficha FROM shd600_solicitud_arrendamiento b WHERE ((((((a.cod_presi = b.cod_presi) AND (a.cod_entidad = b.cod_entidad)) AND (a.cod_tipo_inst = b.cod_tipo_inst)) AND (a.cod_inst = b.cod_inst)) AND (a.cod_dep = b.cod_dep)) AND ((a.numero_solicitud)::text = (b.numero_solicitud)::text))) AS cod_ficha, (SELECT b.expectativa_construccion FROM shd600_solicitud_arrendamiento b WHERE (((((((a.cod_presi = b.cod_presi) AND (a.cod_entidad = b.cod_entidad)) AND (a.cod_tipo_inst = b.cod_tipo_inst)) AND (a.cod_inst = b.cod_inst)) AND (a.cod_dep = b.cod_dep)) AND ((a.numero_solicitud)::text = (b.numero_solicitud)::text)) AND ((a.rif_cedula)::text = (b.rif_cedula)::text))) AS expectativa_construccion, (SELECT b.datos_registro_arrendamiento FROM shd600_aprobacion_arrendamiento b WHERE (((((((a.cod_presi = b.cod_presi) AND (a.cod_entidad = b.cod_entidad)) AND (a.cod_tipo_inst = b.cod_tipo_inst)) AND (a.cod_inst = b.cod_inst)) AND (a.cod_dep = b.cod_dep)) AND ((a.numero_solicitud)::text = (b.numero_solicitud)::text)) AND ((a.rif_cedula)::text = (b.rif_cedula)::text))) AS datos_registro_arrendamiento, (SELECT b.rif_ci_cobrador FROM shd600_aprobacion_arrendamiento b WHERE (((((((a.cod_presi = b.cod_presi) AND (a.cod_entidad = b.cod_entidad)) AND (a.cod_tipo_inst = b.cod_tipo_inst)) AND (a.cod_inst = b.cod_inst)) AND (a.cod_dep = b.cod_dep)) AND ((a.numero_solicitud)::text = (b.numero_solicitud)::text)) AND ((a.rif_cedula)::text = (b.rif_cedula)::text))) AS rif_ci_cobrador, (SELECT e.nombre_razon FROM shd002_cobradores e WHERE ((((((a.cod_presi = e.cod_presi) AND (a.cod_entidad = e.cod_entidad)) AND (a.cod_tipo_inst = e.cod_tipo_inst)) AND (a.cod_inst = e.cod_inst)) AND (a.cod_dep = e.cod_dep)) AND (((SELECT b.rif_ci_cobrador FROM shd600_aprobacion_arrendamiento b WHERE (((((((a.cod_presi = b.cod_presi) AND (a.cod_entidad = b.cod_entidad)) AND (a.cod_tipo_inst = b.cod_tipo_inst)) AND (a.cod_inst = b.cod_inst)) AND (a.cod_dep = b.cod_dep)) AND ((a.numero_solicitud)::text = (b.numero_solicitud)::text)) AND ((a.rif_cedula)::text = (b.rif_cedula)::text))))::text = (e.rif_ci)::text))) AS deno_cobrador, (SELECT b.frecuencia_pago FROM shd600_aprobacion_arrendamiento b WHERE (((((((a.cod_presi = b.cod_presi) AND (a.cod_entidad = b.cod_entidad)) AND (a.cod_tipo_inst = b.cod_tipo_inst)) AND (a.cod_inst = b.cod_inst)) AND (a.cod_dep = b.cod_dep)) AND ((a.numero_solicitud)::text = (b.numero_solicitud)::text)) AND ((a.rif_cedula)::text = (b.rif_cedula)::text))) AS frecuencia_pago, (SELECT b.monto_mensual FROM shd600_aprobacion_arrendamiento b WHERE (((((((a.cod_presi = b.cod_presi) AND (a.cod_entidad = b.cod_entidad)) AND (a.cod_tipo_inst = b.cod_tipo_inst)) AND (a.cod_inst = b.cod_inst)) AND (a.cod_dep = b.cod_dep)) AND ((a.numero_solicitud)::text = (b.numero_solicitud)::text)) AND ((a.rif_cedula)::text = (b.rif_cedula)::text))) AS monto_mensual, (SELECT b.pago_todo FROM shd600_aprobacion_arrendamiento b WHERE (((((((a.cod_presi = b.cod_presi) AND (a.cod_entidad = b.cod_entidad)) AND (a.cod_tipo_inst = b.cod_tipo_inst)) AND (a.cod_inst = b.cod_inst)) AND (a.cod_dep = b.cod_dep)) AND ((a.numero_solicitud)::text = (b.numero_solicitud)::text)) AND ((a.rif_cedula)::text = (b.rif_cedula)::text))) AS pago_todo, (SELECT b.suspendido FROM shd600_aprobacion_arrendamiento b WHERE (((((((a.cod_presi = b.cod_presi) AND (a.cod_entidad = b.cod_entidad)) AND (a.cod_tipo_inst = b.cod_tipo_inst)) AND (a.cod_inst = b.cod_inst)) AND (a.cod_dep = b.cod_dep)) AND ((a.numero_solicitud)::text = (b.numero_solicitud)::text)) AND ((a.rif_cedula)::text = (b.rif_cedula)::text))) AS suspendido, (SELECT b.ultimo_ano_facturado FROM shd600_aprobacion_arrendamiento b WHERE (((((((a.cod_presi = b.cod_presi) AND (a.cod_entidad = b.cod_entidad)) AND (a.cod_tipo_inst = b.cod_tipo_inst)) AND (a.cod_inst = b.cod_inst)) AND (a.cod_dep = b.cod_dep)) AND ((a.numero_solicitud)::text = (b.numero_solicitud)::text)) AND ((a.rif_cedula)::text = (b.rif_cedula)::text))) AS ultimo_ano_facturado, (SELECT b.ultimo_mes_facturado FROM shd600_aprobacion_arrendamiento b WHERE (((((((a.cod_presi = b.cod_presi) AND (a.cod_entidad = b.cod_entidad)) AND (a.cod_tipo_inst = b.cod_tipo_inst)) AND (a.cod_inst = b.cod_inst)) AND (a.cod_dep = b.cod_dep)) AND ((a.numero_solicitud)::text = (b.numero_solicitud)::text)) AND ((a.rif_cedula)::text = (b.rif_cedula)::text))) AS ultimo_mes_facturado FROM shd600_compra_terreno a;


ALTER TABLE public.v_shd600_compra_terreno OWNER TO sisap;

--
-- TOC entry 4247 (class 1259 OID 502986)
-- Dependencies: 4633 3
-- Name: v_shd900_planillas_deuda_cobro_detalles; Type: VIEW; Schema: public; Owner: sisap
--

CREATE VIEW v_shd900_planillas_deuda_cobro_detalles AS
    SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cod_partida, a.cod_generica, a.cod_especifica, a.cod_sub_espec, a.cod_auxiliar, a.rif_cedula, a.cod_numero_catastral_placas, a.ano, a.mes, a.numero_planilla, a.deuda_vigente, a.monto_recargo, a.monto_multa, a.monto_intereses, a.monto_descuento, a.cancelado, a.fecha_emision, (SELECT (sum(((((xx.deuda_vigente + xx.monto_recargo) + xx.monto_multa) + xx.monto_intereses) - xx.monto_descuento)))::numeric(26,2) AS sum FROM shd900_planillas_deuda_cobro_detalles xx WHERE ((((((((xx.cancelado = 2) AND (xx.ano < (SELECT xy.ano_arranque FROM shd000_arranque xy WHERE (((((xy.cod_presi = a.cod_presi) AND (xy.cod_entidad = a.cod_entidad)) AND (xy.cod_tipo_inst = a.cod_tipo_inst)) AND (xy.cod_inst = a.cod_inst)) AND (xy.cod_dep = a.cod_dep))))) AND (xx.cod_presi = a.cod_presi)) AND (xx.cod_entidad = a.cod_entidad)) AND (xx.cod_tipo_inst = a.cod_tipo_inst)) AND (xx.cod_inst = a.cod_inst)) AND (xx.cod_dep = a.cod_dep)) AND ((xx.rif_cedula)::text = (a.rif_cedula)::text))) AS deudada_ano_anterior_total, (SELECT (sum(((((xx.deuda_vigente + xx.monto_recargo) + xx.monto_multa) + xx.monto_intereses) - xx.monto_descuento)))::numeric(26,2) AS sum FROM shd900_planillas_deuda_cobro_detalles xx WHERE (((((((((((((((xx.cancelado = 2) AND (xx.ano < (SELECT xy.ano_arranque FROM shd000_arranque xy WHERE (((((xy.cod_presi = a.cod_presi) AND (xy.cod_entidad = a.cod_entidad)) AND (xy.cod_tipo_inst = a.cod_tipo_inst)) AND (xy.cod_inst = a.cod_inst)) AND (xy.cod_dep = a.cod_dep))))) AND (xx.cod_presi = a.cod_presi)) AND (xx.cod_entidad = a.cod_entidad)) AND (xx.cod_tipo_inst = a.cod_tipo_inst)) AND (xx.cod_inst = a.cod_inst)) AND (xx.cod_dep = a.cod_dep)) AND (xx.cod_partida = a.cod_partida)) AND (xx.cod_generica = a.cod_generica)) AND (xx.cod_especifica = a.cod_especifica)) AND (xx.cod_sub_espec = a.cod_sub_espec)) AND (xx.cod_auxiliar = a.cod_auxiliar)) AND ((xx.rif_cedula)::text = (a.rif_cedula)::text)) AND ((xx.cod_numero_catastral_placas)::text = (a.cod_numero_catastral_placas)::text)) AND (1 = (SELECT aa.cod_ingreso FROM shd003_codigo_ingresos aa WHERE (((((aa.cod_partida = a.cod_partida) AND (aa.cod_generica = a.cod_generica)) AND (aa.cod_especifica = a.cod_especifica)) AND (aa.cod_subespec = a.cod_sub_espec)) AND (aa.cod_auxiliar = a.cod_auxiliar)) LIMIT 1)))) AS deuda_ano_anterior_1, (SELECT (sum(((((xx.deuda_vigente + xx.monto_recargo) + xx.monto_multa) + xx.monto_intereses) - xx.monto_descuento)))::numeric(26,2) AS sum FROM shd900_planillas_deuda_cobro_detalles xx WHERE (((((((((((((((xx.cancelado = 2) AND (xx.ano < (SELECT xy.ano_arranque FROM shd000_arranque xy WHERE (((((xy.cod_presi = a.cod_presi) AND (xy.cod_entidad = a.cod_entidad)) AND (xy.cod_tipo_inst = a.cod_tipo_inst)) AND (xy.cod_inst = a.cod_inst)) AND (xy.cod_dep = a.cod_dep))))) AND (xx.cod_presi = a.cod_presi)) AND (xx.cod_entidad = a.cod_entidad)) AND (xx.cod_tipo_inst = a.cod_tipo_inst)) AND (xx.cod_inst = a.cod_inst)) AND (xx.cod_dep = a.cod_dep)) AND (xx.cod_partida = a.cod_partida)) AND (xx.cod_generica = a.cod_generica)) AND (xx.cod_especifica = a.cod_especifica)) AND (xx.cod_sub_espec = a.cod_sub_espec)) AND (xx.cod_auxiliar = a.cod_auxiliar)) AND ((xx.rif_cedula)::text = (a.rif_cedula)::text)) AND ((xx.cod_numero_catastral_placas)::text = (a.cod_numero_catastral_placas)::text)) AND (2 = (SELECT aa.cod_ingreso FROM shd003_codigo_ingresos aa WHERE (((((aa.cod_partida = a.cod_partida) AND (aa.cod_generica = a.cod_generica)) AND (aa.cod_especifica = a.cod_especifica)) AND (aa.cod_subespec = a.cod_sub_espec)) AND (aa.cod_auxiliar = a.cod_auxiliar)) LIMIT 1)))) AS deuda_ano_anterior_2, (SELECT (sum(((((xx.deuda_vigente + xx.monto_recargo) + xx.monto_multa) + xx.monto_intereses) - xx.monto_descuento)))::numeric(26,2) AS sum FROM shd900_planillas_deuda_cobro_detalles xx WHERE (((((((((((((((xx.cancelado = 2) AND (xx.ano < (SELECT xy.ano_arranque FROM shd000_arranque xy WHERE (((((xy.cod_presi = a.cod_presi) AND (xy.cod_entidad = a.cod_entidad)) AND (xy.cod_tipo_inst = a.cod_tipo_inst)) AND (xy.cod_inst = a.cod_inst)) AND (xy.cod_dep = a.cod_dep))))) AND (xx.cod_presi = a.cod_presi)) AND (xx.cod_entidad = a.cod_entidad)) AND (xx.cod_tipo_inst = a.cod_tipo_inst)) AND (xx.cod_inst = a.cod_inst)) AND (xx.cod_dep = a.cod_dep)) AND (xx.cod_partida = a.cod_partida)) AND (xx.cod_generica = a.cod_generica)) AND (xx.cod_especifica = a.cod_especifica)) AND (xx.cod_sub_espec = a.cod_sub_espec)) AND (xx.cod_auxiliar = a.cod_auxiliar)) AND ((xx.rif_cedula)::text = (a.rif_cedula)::text)) AND ((xx.cod_numero_catastral_placas)::text = (a.cod_numero_catastral_placas)::text)) AND (3 = (SELECT aa.cod_ingreso FROM shd003_codigo_ingresos aa WHERE (((((aa.cod_partida = a.cod_partida) AND (aa.cod_generica = a.cod_generica)) AND (aa.cod_especifica = a.cod_especifica)) AND (aa.cod_subespec = a.cod_sub_espec)) AND (aa.cod_auxiliar = a.cod_auxiliar)) LIMIT 1)))) AS deuda_ano_anterior_3, (SELECT (sum(((((xx.deuda_vigente + xx.monto_recargo) + xx.monto_multa) + xx.monto_intereses) - xx.monto_descuento)))::numeric(26,2) AS sum FROM shd900_planillas_deuda_cobro_detalles xx WHERE (((((((((((((((xx.cancelado = 2) AND (xx.ano < (SELECT xy.ano_arranque FROM shd000_arranque xy WHERE (((((xy.cod_presi = a.cod_presi) AND (xy.cod_entidad = a.cod_entidad)) AND (xy.cod_tipo_inst = a.cod_tipo_inst)) AND (xy.cod_inst = a.cod_inst)) AND (xy.cod_dep = a.cod_dep))))) AND (xx.cod_presi = a.cod_presi)) AND (xx.cod_entidad = a.cod_entidad)) AND (xx.cod_tipo_inst = a.cod_tipo_inst)) AND (xx.cod_inst = a.cod_inst)) AND (xx.cod_dep = a.cod_dep)) AND (xx.cod_partida = a.cod_partida)) AND (xx.cod_generica = a.cod_generica)) AND (xx.cod_especifica = a.cod_especifica)) AND (xx.cod_sub_espec = a.cod_sub_espec)) AND (xx.cod_auxiliar = a.cod_auxiliar)) AND ((xx.rif_cedula)::text = (a.rif_cedula)::text)) AND ((xx.cod_numero_catastral_placas)::text = (a.cod_numero_catastral_placas)::text)) AND (4 = (SELECT aa.cod_ingreso FROM shd003_codigo_ingresos aa WHERE (((((aa.cod_partida = a.cod_partida) AND (aa.cod_generica = a.cod_generica)) AND (aa.cod_especifica = a.cod_especifica)) AND (aa.cod_subespec = a.cod_sub_espec)) AND (aa.cod_auxiliar = a.cod_auxiliar)) LIMIT 1)))) AS deuda_ano_anterior_4, (SELECT (sum(((((xx.deuda_vigente + xx.monto_recargo) + xx.monto_multa) + xx.monto_intereses) - xx.monto_descuento)))::numeric(26,2) AS sum FROM shd900_planillas_deuda_cobro_detalles xx WHERE (((((((((((((((xx.cancelado = 2) AND (xx.ano < (SELECT xy.ano_arranque FROM shd000_arranque xy WHERE (((((xy.cod_presi = a.cod_presi) AND (xy.cod_entidad = a.cod_entidad)) AND (xy.cod_tipo_inst = a.cod_tipo_inst)) AND (xy.cod_inst = a.cod_inst)) AND (xy.cod_dep = a.cod_dep))))) AND (xx.cod_presi = a.cod_presi)) AND (xx.cod_entidad = a.cod_entidad)) AND (xx.cod_tipo_inst = a.cod_tipo_inst)) AND (xx.cod_inst = a.cod_inst)) AND (xx.cod_dep = a.cod_dep)) AND (xx.cod_partida = a.cod_partida)) AND (xx.cod_generica = a.cod_generica)) AND (xx.cod_especifica = a.cod_especifica)) AND (xx.cod_sub_espec = a.cod_sub_espec)) AND (xx.cod_auxiliar = a.cod_auxiliar)) AND ((xx.rif_cedula)::text = (a.rif_cedula)::text)) AND ((xx.cod_numero_catastral_placas)::text = (a.cod_numero_catastral_placas)::text)) AND (5 = (SELECT aa.cod_ingreso FROM shd003_codigo_ingresos aa WHERE (((((aa.cod_partida = a.cod_partida) AND (aa.cod_generica = a.cod_generica)) AND (aa.cod_especifica = a.cod_especifica)) AND (aa.cod_subespec = a.cod_sub_espec)) AND (aa.cod_auxiliar = a.cod_auxiliar)) LIMIT 1)))) AS deuda_ano_anterior_5, (SELECT (sum(((((xx.deuda_vigente + xx.monto_recargo) + xx.monto_multa) + xx.monto_intereses) - xx.monto_descuento)))::numeric(26,2) AS sum FROM shd900_planillas_deuda_cobro_detalles xx WHERE (((((((((((((((xx.cancelado = 2) AND (xx.ano < (SELECT xy.ano_arranque FROM shd000_arranque xy WHERE (((((xy.cod_presi = a.cod_presi) AND (xy.cod_entidad = a.cod_entidad)) AND (xy.cod_tipo_inst = a.cod_tipo_inst)) AND (xy.cod_inst = a.cod_inst)) AND (xy.cod_dep = a.cod_dep))))) AND (xx.cod_presi = a.cod_presi)) AND (xx.cod_entidad = a.cod_entidad)) AND (xx.cod_tipo_inst = a.cod_tipo_inst)) AND (xx.cod_inst = a.cod_inst)) AND (xx.cod_dep = a.cod_dep)) AND (xx.cod_partida = a.cod_partida)) AND (xx.cod_generica = a.cod_generica)) AND (xx.cod_especifica = a.cod_especifica)) AND (xx.cod_sub_espec = a.cod_sub_espec)) AND (xx.cod_auxiliar = a.cod_auxiliar)) AND ((xx.rif_cedula)::text = (a.rif_cedula)::text)) AND ((xx.cod_numero_catastral_placas)::text = (a.cod_numero_catastral_placas)::text)) AND (6 = (SELECT aa.cod_ingreso FROM shd003_codigo_ingresos aa WHERE (((((aa.cod_partida = a.cod_partida) AND (aa.cod_generica = a.cod_generica)) AND (aa.cod_especifica = a.cod_especifica)) AND (aa.cod_subespec = a.cod_sub_espec)) AND (aa.cod_auxiliar = a.cod_auxiliar)) LIMIT 1)))) AS deuda_ano_anterior_6, (SELECT (sum(((((xx.deuda_vigente + xx.monto_recargo) + xx.monto_multa) + xx.monto_intereses) - xx.monto_descuento)))::numeric(26,2) AS sum FROM shd900_planillas_deuda_cobro_detalles xx WHERE (((((((((((((((xx.cancelado = 2) AND (xx.ano < (SELECT xy.ano_arranque FROM shd000_arranque xy WHERE (((((xy.cod_presi = a.cod_presi) AND (xy.cod_entidad = a.cod_entidad)) AND (xy.cod_tipo_inst = a.cod_tipo_inst)) AND (xy.cod_inst = a.cod_inst)) AND (xy.cod_dep = a.cod_dep))))) AND (xx.cod_presi = a.cod_presi)) AND (xx.cod_entidad = a.cod_entidad)) AND (xx.cod_tipo_inst = a.cod_tipo_inst)) AND (xx.cod_inst = a.cod_inst)) AND (xx.cod_dep = a.cod_dep)) AND (xx.cod_partida = a.cod_partida)) AND (xx.cod_generica = a.cod_generica)) AND (xx.cod_especifica = a.cod_especifica)) AND (xx.cod_sub_espec = a.cod_sub_espec)) AND (xx.cod_auxiliar = a.cod_auxiliar)) AND ((xx.rif_cedula)::text = (a.rif_cedula)::text)) AND ((xx.cod_numero_catastral_placas)::text = (a.cod_numero_catastral_placas)::text)) AND (7 = (SELECT aa.cod_ingreso FROM shd003_codigo_ingresos aa WHERE (((((aa.cod_partida = a.cod_partida) AND (aa.cod_generica = a.cod_generica)) AND (aa.cod_especifica = a.cod_especifica)) AND (aa.cod_subespec = a.cod_sub_espec)) AND (aa.cod_auxiliar = a.cod_auxiliar)) LIMIT 1)))) AS deuda_ano_anterior_7, (SELECT (sum(((((xx.deuda_vigente + xx.monto_recargo) + xx.monto_multa) + xx.monto_intereses) - xx.monto_descuento)))::numeric(26,2) AS sum FROM shd900_planillas_deuda_cobro_detalles xx WHERE ((((((((((((((xx.cancelado = 2) AND (xx.ano < (SELECT xy.ano_arranque FROM shd000_arranque xy WHERE (((((xy.cod_presi = a.cod_presi) AND (xy.cod_entidad = a.cod_entidad)) AND (xy.cod_tipo_inst = a.cod_tipo_inst)) AND (xy.cod_inst = a.cod_inst)) AND (xy.cod_dep = a.cod_dep))))) AND (xx.cod_presi = a.cod_presi)) AND (xx.cod_entidad = a.cod_entidad)) AND (xx.cod_tipo_inst = a.cod_tipo_inst)) AND (xx.cod_inst = a.cod_inst)) AND (xx.cod_dep = a.cod_dep)) AND (xx.cod_partida = a.cod_partida)) AND (xx.cod_generica = a.cod_generica)) AND (xx.cod_especifica = a.cod_especifica)) AND (xx.cod_sub_espec = a.cod_sub_espec)) AND (xx.cod_auxiliar = a.cod_auxiliar)) AND ((xx.rif_cedula)::text = (a.rif_cedula)::text)) AND (1 = (SELECT aa.cod_ingreso FROM shd003_codigo_ingresos aa WHERE (((((aa.cod_partida = a.cod_partida) AND (aa.cod_generica = a.cod_generica)) AND (aa.cod_especifica = a.cod_especifica)) AND (aa.cod_subespec = a.cod_sub_espec)) AND (aa.cod_auxiliar = a.cod_auxiliar)) LIMIT 1)))) AS deuda_ano_anterior_por_impuesto_1, (SELECT (sum(((((xx.deuda_vigente + xx.monto_recargo) + xx.monto_multa) + xx.monto_intereses) - xx.monto_descuento)))::numeric(26,2) AS sum FROM shd900_planillas_deuda_cobro_detalles xx WHERE ((((((((((((((xx.cancelado = 2) AND (xx.ano < (SELECT xy.ano_arranque FROM shd000_arranque xy WHERE (((((xy.cod_presi = a.cod_presi) AND (xy.cod_entidad = a.cod_entidad)) AND (xy.cod_tipo_inst = a.cod_tipo_inst)) AND (xy.cod_inst = a.cod_inst)) AND (xy.cod_dep = a.cod_dep))))) AND (xx.cod_presi = a.cod_presi)) AND (xx.cod_entidad = a.cod_entidad)) AND (xx.cod_tipo_inst = a.cod_tipo_inst)) AND (xx.cod_inst = a.cod_inst)) AND (xx.cod_dep = a.cod_dep)) AND (xx.cod_partida = a.cod_partida)) AND (xx.cod_generica = a.cod_generica)) AND (xx.cod_especifica = a.cod_especifica)) AND (xx.cod_sub_espec = a.cod_sub_espec)) AND (xx.cod_auxiliar = a.cod_auxiliar)) AND ((xx.rif_cedula)::text = (a.rif_cedula)::text)) AND (2 = (SELECT aa.cod_ingreso FROM shd003_codigo_ingresos aa WHERE (((((aa.cod_partida = a.cod_partida) AND (aa.cod_generica = a.cod_generica)) AND (aa.cod_especifica = a.cod_especifica)) AND (aa.cod_subespec = a.cod_sub_espec)) AND (aa.cod_auxiliar = a.cod_auxiliar)) LIMIT 1)))) AS deuda_ano_anterior_por_impuesto_2, (SELECT (sum(((((xx.deuda_vigente + xx.monto_recargo) + xx.monto_multa) + xx.monto_intereses) - xx.monto_descuento)))::numeric(26,2) AS sum FROM shd900_planillas_deuda_cobro_detalles xx WHERE ((((((((((((((xx.cancelado = 2) AND (xx.ano < (SELECT xy.ano_arranque FROM shd000_arranque xy WHERE (((((xy.cod_presi = a.cod_presi) AND (xy.cod_entidad = a.cod_entidad)) AND (xy.cod_tipo_inst = a.cod_tipo_inst)) AND (xy.cod_inst = a.cod_inst)) AND (xy.cod_dep = a.cod_dep))))) AND (xx.cod_presi = a.cod_presi)) AND (xx.cod_entidad = a.cod_entidad)) AND (xx.cod_tipo_inst = a.cod_tipo_inst)) AND (xx.cod_inst = a.cod_inst)) AND (xx.cod_dep = a.cod_dep)) AND (xx.cod_partida = a.cod_partida)) AND (xx.cod_generica = a.cod_generica)) AND (xx.cod_especifica = a.cod_especifica)) AND (xx.cod_sub_espec = a.cod_sub_espec)) AND (xx.cod_auxiliar = a.cod_auxiliar)) AND ((xx.rif_cedula)::text = (a.rif_cedula)::text)) AND (3 = (SELECT aa.cod_ingreso FROM shd003_codigo_ingresos aa WHERE (((((aa.cod_partida = a.cod_partida) AND (aa.cod_generica = a.cod_generica)) AND (aa.cod_especifica = a.cod_especifica)) AND (aa.cod_subespec = a.cod_sub_espec)) AND (aa.cod_auxiliar = a.cod_auxiliar)) LIMIT 1)))) AS deuda_ano_anterior_por_impuesto_3, (SELECT (sum(((((xx.deuda_vigente + xx.monto_recargo) + xx.monto_multa) + xx.monto_intereses) - xx.monto_descuento)))::numeric(26,2) AS sum FROM shd900_planillas_deuda_cobro_detalles xx WHERE ((((((((((((((xx.cancelado = 2) AND (xx.ano < (SELECT xy.ano_arranque FROM shd000_arranque xy WHERE (((((xy.cod_presi = a.cod_presi) AND (xy.cod_entidad = a.cod_entidad)) AND (xy.cod_tipo_inst = a.cod_tipo_inst)) AND (xy.cod_inst = a.cod_inst)) AND (xy.cod_dep = a.cod_dep))))) AND (xx.cod_presi = a.cod_presi)) AND (xx.cod_entidad = a.cod_entidad)) AND (xx.cod_tipo_inst = a.cod_tipo_inst)) AND (xx.cod_inst = a.cod_inst)) AND (xx.cod_dep = a.cod_dep)) AND (xx.cod_partida = a.cod_partida)) AND (xx.cod_generica = a.cod_generica)) AND (xx.cod_especifica = a.cod_especifica)) AND (xx.cod_sub_espec = a.cod_sub_espec)) AND (xx.cod_auxiliar = a.cod_auxiliar)) AND ((xx.rif_cedula)::text = (a.rif_cedula)::text)) AND (4 = (SELECT aa.cod_ingreso FROM shd003_codigo_ingresos aa WHERE (((((aa.cod_partida = a.cod_partida) AND (aa.cod_generica = a.cod_generica)) AND (aa.cod_especifica = a.cod_especifica)) AND (aa.cod_subespec = a.cod_sub_espec)) AND (aa.cod_auxiliar = a.cod_auxiliar)) LIMIT 1)))) AS deuda_ano_anterior_por_impuesto_4, (SELECT (sum(((((xx.deuda_vigente + xx.monto_recargo) + xx.monto_multa) + xx.monto_intereses) - xx.monto_descuento)))::numeric(26,2) AS sum FROM shd900_planillas_deuda_cobro_detalles xx WHERE ((((((((((((((xx.cancelado = 2) AND (xx.ano < (SELECT xy.ano_arranque FROM shd000_arranque xy WHERE (((((xy.cod_presi = a.cod_presi) AND (xy.cod_entidad = a.cod_entidad)) AND (xy.cod_tipo_inst = a.cod_tipo_inst)) AND (xy.cod_inst = a.cod_inst)) AND (xy.cod_dep = a.cod_dep))))) AND (xx.cod_presi = a.cod_presi)) AND (xx.cod_entidad = a.cod_entidad)) AND (xx.cod_tipo_inst = a.cod_tipo_inst)) AND (xx.cod_inst = a.cod_inst)) AND (xx.cod_dep = a.cod_dep)) AND (xx.cod_partida = a.cod_partida)) AND (xx.cod_generica = a.cod_generica)) AND (xx.cod_especifica = a.cod_especifica)) AND (xx.cod_sub_espec = a.cod_sub_espec)) AND (xx.cod_auxiliar = a.cod_auxiliar)) AND ((xx.rif_cedula)::text = (a.rif_cedula)::text)) AND (5 = (SELECT aa.cod_ingreso FROM shd003_codigo_ingresos aa WHERE (((((aa.cod_partida = a.cod_partida) AND (aa.cod_generica = a.cod_generica)) AND (aa.cod_especifica = a.cod_especifica)) AND (aa.cod_subespec = a.cod_sub_espec)) AND (aa.cod_auxiliar = a.cod_auxiliar)) LIMIT 1)))) AS deuda_ano_anterior_por_impuesto_5, (SELECT (sum(((((xx.deuda_vigente + xx.monto_recargo) + xx.monto_multa) + xx.monto_intereses) - xx.monto_descuento)))::numeric(26,2) AS sum FROM shd900_planillas_deuda_cobro_detalles xx WHERE ((((((((((((((xx.cancelado = 2) AND (xx.ano < (SELECT xy.ano_arranque FROM shd000_arranque xy WHERE (((((xy.cod_presi = a.cod_presi) AND (xy.cod_entidad = a.cod_entidad)) AND (xy.cod_tipo_inst = a.cod_tipo_inst)) AND (xy.cod_inst = a.cod_inst)) AND (xy.cod_dep = a.cod_dep))))) AND (xx.cod_presi = a.cod_presi)) AND (xx.cod_entidad = a.cod_entidad)) AND (xx.cod_tipo_inst = a.cod_tipo_inst)) AND (xx.cod_inst = a.cod_inst)) AND (xx.cod_dep = a.cod_dep)) AND (xx.cod_partida = a.cod_partida)) AND (xx.cod_generica = a.cod_generica)) AND (xx.cod_especifica = a.cod_especifica)) AND (xx.cod_sub_espec = a.cod_sub_espec)) AND (xx.cod_auxiliar = a.cod_auxiliar)) AND ((xx.rif_cedula)::text = (a.rif_cedula)::text)) AND (6 = (SELECT aa.cod_ingreso FROM shd003_codigo_ingresos aa WHERE (((((aa.cod_partida = a.cod_partida) AND (aa.cod_generica = a.cod_generica)) AND (aa.cod_especifica = a.cod_especifica)) AND (aa.cod_subespec = a.cod_sub_espec)) AND (aa.cod_auxiliar = a.cod_auxiliar)) LIMIT 1)))) AS deuda_ano_anterior_por_impuesto_6, (SELECT (sum(((((xx.deuda_vigente + xx.monto_recargo) + xx.monto_multa) + xx.monto_intereses) - xx.monto_descuento)))::numeric(26,2) AS sum FROM shd900_planillas_deuda_cobro_detalles xx WHERE ((((((((((((((xx.cancelado = 2) AND (xx.ano < (SELECT xy.ano_arranque FROM shd000_arranque xy WHERE (((((xy.cod_presi = a.cod_presi) AND (xy.cod_entidad = a.cod_entidad)) AND (xy.cod_tipo_inst = a.cod_tipo_inst)) AND (xy.cod_inst = a.cod_inst)) AND (xy.cod_dep = a.cod_dep))))) AND (xx.cod_presi = a.cod_presi)) AND (xx.cod_entidad = a.cod_entidad)) AND (xx.cod_tipo_inst = a.cod_tipo_inst)) AND (xx.cod_inst = a.cod_inst)) AND (xx.cod_dep = a.cod_dep)) AND (xx.cod_partida = a.cod_partida)) AND (xx.cod_generica = a.cod_generica)) AND (xx.cod_especifica = a.cod_especifica)) AND (xx.cod_sub_espec = a.cod_sub_espec)) AND (xx.cod_auxiliar = a.cod_auxiliar)) AND ((xx.rif_cedula)::text = (a.rif_cedula)::text)) AND (7 = (SELECT aa.cod_ingreso FROM shd003_codigo_ingresos aa WHERE (((((aa.cod_partida = a.cod_partida) AND (aa.cod_generica = a.cod_generica)) AND (aa.cod_especifica = a.cod_especifica)) AND (aa.cod_subespec = a.cod_sub_espec)) AND (aa.cod_auxiliar = a.cod_auxiliar)) LIMIT 1)))) AS deuda_ano_anterior_por_impuesto_7, (SELECT h.razon_social_nombres FROM shd001_registro_contribuyentes h WHERE ((h.rif_cedula)::text = (a.rif_cedula)::text)) AS razon_social_nombres, (SELECT c.cod_ingreso FROM shd003_codigo_ingresos c WHERE (((((c.cod_partida = c.cod_partida) AND (c.cod_generica = a.cod_generica)) AND (c.cod_especifica = a.cod_especifica)) AND (c.cod_subespec = a.cod_sub_espec)) AND (c.cod_auxiliar = a.cod_auxiliar))) AS cod_ingreso, (SELECT b.denominacion FROM shd003_codigo_ingresos b WHERE (((((b.cod_partida = b.cod_partida) AND (b.cod_generica = a.cod_generica)) AND (b.cod_especifica = a.cod_especifica)) AND (b.cod_subespec = a.cod_sub_espec)) AND (b.cod_auxiliar = a.cod_auxiliar))) AS denominacion, (((((a.deuda_vigente + a.monto_recargo) + a.monto_multa) + a.monto_intereses) - a.monto_descuento))::numeric(26,2) AS total FROM shd900_planillas_deuda_cobro_detalles a;


ALTER TABLE public.v_shd900_planillas_deuda_cobro_detalles OWNER TO sisap;

--
-- TOC entry 4264 (class 1259 OID 503120)
-- Dependencies: 4650 3
-- Name: v_shd600_solicitud_arrendamiento; Type: VIEW; Schema: public; Owner: sisap
--

CREATE VIEW v_shd600_solicitud_arrendamiento AS
    SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.numero_solicitud, a.fecha_solicitud, a.rif_cedula, (SELECT b.razon_social_nombres FROM v_shd001_registro_contribuyentes b WHERE ((a.rif_cedula)::text = (b.rif_cedula)::text)) AS nombre_razon, (SELECT b.cod_pais FROM v_shd001_registro_contribuyentes b WHERE ((a.rif_cedula)::text = (b.rif_cedula)::text)) AS cod_pais, (SELECT b.deno_pais FROM v_shd001_registro_contribuyentes b WHERE ((a.rif_cedula)::text = (b.rif_cedula)::text)) AS deno_pais, (SELECT b.cod_estado FROM v_shd001_registro_contribuyentes b WHERE ((a.rif_cedula)::text = (b.rif_cedula)::text)) AS cod_estado, (SELECT b.deno_estado FROM v_shd001_registro_contribuyentes b WHERE ((a.rif_cedula)::text = (b.rif_cedula)::text)) AS deno_estado, (SELECT b.cod_municipio FROM v_shd001_registro_contribuyentes b WHERE ((a.rif_cedula)::text = (b.rif_cedula)::text)) AS cod_municipio, (SELECT b.deno_municipio FROM v_shd001_registro_contribuyentes b WHERE ((a.rif_cedula)::text = (b.rif_cedula)::text)) AS deno_municipio, (SELECT b.cod_parroquia FROM v_shd001_registro_contribuyentes b WHERE ((a.rif_cedula)::text = (b.rif_cedula)::text)) AS cod_parroquia, (SELECT b.deno_parroquia FROM v_shd001_registro_contribuyentes b WHERE ((a.rif_cedula)::text = (b.rif_cedula)::text)) AS deno_parroquia, (SELECT b.cod_centro_poblado FROM v_shd001_registro_contribuyentes b WHERE ((a.rif_cedula)::text = (b.rif_cedula)::text)) AS cod_centro, (SELECT b.deno_centro FROM v_shd001_registro_contribuyentes b WHERE ((a.rif_cedula)::text = (b.rif_cedula)::text)) AS deno_centro, (SELECT b.cod_calle_avenida FROM v_shd001_registro_contribuyentes b WHERE ((a.rif_cedula)::text = (b.rif_cedula)::text)) AS cod_calle, (SELECT b.deno_vialidad FROM v_shd001_registro_contribuyentes b WHERE ((a.rif_cedula)::text = (b.rif_cedula)::text)) AS deno_calle, (SELECT b.cod_vereda_edificio FROM v_shd001_registro_contribuyentes b WHERE ((a.rif_cedula)::text = (b.rif_cedula)::text)) AS cod_vereda_edificio, (SELECT b.deno_vereda FROM v_shd001_registro_contribuyentes b WHERE ((a.rif_cedula)::text = (b.rif_cedula)::text)) AS deno_vereda, (SELECT b.numero_vivienda_local FROM v_shd001_registro_contribuyentes b WHERE ((a.rif_cedula)::text = (b.rif_cedula)::text)) AS numero_casa, (SELECT b.fecha_inscripcion FROM v_shd001_registro_contribuyentes b WHERE ((a.rif_cedula)::text = (b.rif_cedula)::text)) AS fecha_inscripcion_cont, (SELECT b.telefonos_fijos FROM v_shd001_registro_contribuyentes b WHERE ((a.rif_cedula)::text = (b.rif_cedula)::text)) AS telefonos_fijos, (SELECT b.telefonos_celulares FROM v_shd001_registro_contribuyentes b WHERE ((a.rif_cedula)::text = (b.rif_cedula)::text)) AS telefonos_celulares, (SELECT b.correo_electronico FROM v_shd001_registro_contribuyentes b WHERE ((a.rif_cedula)::text = (b.rif_cedula)::text)) AS correo_electronico, (SELECT b.nacionalidad FROM v_shd001_registro_contribuyentes b WHERE ((a.rif_cedula)::text = (b.rif_cedula)::text)) AS nacionalidad, (SELECT b.estado_civil FROM v_shd001_registro_contribuyentes b WHERE ((a.rif_cedula)::text = (b.rif_cedula)::text)) AS estado_civil, (SELECT b.deno_profesion FROM v_shd001_registro_contribuyentes b WHERE ((a.rif_cedula)::text = (b.rif_cedula)::text)) AS deno_profesion, a.opcion, a.cod_ficha, a.expectativa_construccion FROM shd600_solicitud_arrendamiento a;


ALTER TABLE public.v_shd600_solicitud_arrendamiento OWNER TO sisap;

--
-- TOC entry 4267 (class 1259 OID 503135)
-- Dependencies: 4653 3
-- Name: v_shd700_credito_vivienda; Type: VIEW; Schema: public; Owner: sisap
--

CREATE VIEW v_shd700_credito_vivienda AS
    SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.rif_cedula, a.numero_solicitud, (SELECT b.razon_social_nombres FROM v_shd001_registro_contribuyentes b WHERE ((a.rif_cedula)::text = (b.rif_cedula)::text)) AS nombre_razon, (SELECT b.cod_pais FROM v_shd001_registro_contribuyentes b WHERE ((a.rif_cedula)::text = (b.rif_cedula)::text)) AS cod_pais, (SELECT b.deno_pais FROM v_shd001_registro_contribuyentes b WHERE ((a.rif_cedula)::text = (b.rif_cedula)::text)) AS deno_pais, (SELECT b.cod_estado FROM v_shd001_registro_contribuyentes b WHERE ((a.rif_cedula)::text = (b.rif_cedula)::text)) AS cod_estado, (SELECT b.deno_estado FROM v_shd001_registro_contribuyentes b WHERE ((a.rif_cedula)::text = (b.rif_cedula)::text)) AS deno_estado, (SELECT b.cod_municipio FROM v_shd001_registro_contribuyentes b WHERE ((a.rif_cedula)::text = (b.rif_cedula)::text)) AS cod_municipio, (SELECT b.deno_municipio FROM v_shd001_registro_contribuyentes b WHERE ((a.rif_cedula)::text = (b.rif_cedula)::text)) AS deno_municipio, (SELECT b.cod_parroquia FROM v_shd001_registro_contribuyentes b WHERE ((a.rif_cedula)::text = (b.rif_cedula)::text)) AS cod_parroquia, (SELECT b.deno_parroquia FROM v_shd001_registro_contribuyentes b WHERE ((a.rif_cedula)::text = (b.rif_cedula)::text)) AS deno_parroquia, (SELECT b.cod_centro_poblado FROM v_shd001_registro_contribuyentes b WHERE ((a.rif_cedula)::text = (b.rif_cedula)::text)) AS cod_centro, (SELECT b.deno_centro FROM v_shd001_registro_contribuyentes b WHERE ((a.rif_cedula)::text = (b.rif_cedula)::text)) AS deno_centro, (SELECT b.cod_calle_avenida FROM v_shd001_registro_contribuyentes b WHERE ((a.rif_cedula)::text = (b.rif_cedula)::text)) AS cod_calle, (SELECT b.deno_vialidad FROM v_shd001_registro_contribuyentes b WHERE ((a.rif_cedula)::text = (b.rif_cedula)::text)) AS deno_calle, (SELECT b.cod_vereda_edificio FROM v_shd001_registro_contribuyentes b WHERE ((a.rif_cedula)::text = (b.rif_cedula)::text)) AS cod_vereda_edificio, (SELECT b.deno_vereda FROM v_shd001_registro_contribuyentes b WHERE ((a.rif_cedula)::text = (b.rif_cedula)::text)) AS deno_vereda, (SELECT b.numero_vivienda_local FROM v_shd001_registro_contribuyentes b WHERE ((a.rif_cedula)::text = (b.rif_cedula)::text)) AS numero_casa, (SELECT b.fecha_inscripcion FROM v_shd001_registro_contribuyentes b WHERE ((a.rif_cedula)::text = (b.rif_cedula)::text)) AS fecha_inscripcion_cont, (SELECT b.telefonos_fijos FROM v_shd001_registro_contribuyentes b WHERE ((a.rif_cedula)::text = (b.rif_cedula)::text)) AS telefonos_fijos, (SELECT b.telefonos_celulares FROM v_shd001_registro_contribuyentes b WHERE ((a.rif_cedula)::text = (b.rif_cedula)::text)) AS telefonos_celulares, (SELECT b.correo_electronico FROM v_shd001_registro_contribuyentes b WHERE ((a.rif_cedula)::text = (b.rif_cedula)::text)) AS correo_electronico, (SELECT b.nacionalidad FROM v_shd001_registro_contribuyentes b WHERE ((a.rif_cedula)::text = (b.rif_cedula)::text)) AS nacionalidad, (SELECT b.estado_civil FROM v_shd001_registro_contribuyentes b WHERE ((a.rif_cedula)::text = (b.rif_cedula)::text)) AS estado_civil, (SELECT b.deno_profesion FROM v_shd001_registro_contribuyentes b WHERE ((a.rif_cedula)::text = (b.rif_cedula)::text)) AS deno_profesion, a.fecha_solicitud, a.nombre_conyugue, a.cedula_conyugue, a.nombre_empresa, a.tiempo_empresa, a.telefonos_empresas, a.direccion_empresa, a.grupo_familiar, a.ingreso_mensual, a.vivienda_actual, a.tipo_vivienda, a.direccion_vivienda_credito, a.costo_vivienda, a.monto_cuota_inicial, a.monto_restante, a.factor, a.plazo_anos, a.numero_cuotas, a.monto_mensual, a.numero_contrato, a.fecha_contrato, a.frecuencia_pago, a.pago_todo, a.suspendido, a.rif_ci_cobrador, (SELECT d.nombre_razon FROM shd002_cobradores d WHERE ((((((a.cod_presi = d.cod_presi) AND (a.cod_entidad = d.cod_entidad)) AND (a.cod_tipo_inst = d.cod_tipo_inst)) AND (a.cod_inst = d.cod_inst)) AND (a.cod_dep = d.cod_dep)) AND ((a.rif_ci_cobrador)::text = (d.rif_ci)::text))) AS deno_cobrador, a.ultimo_ano_facturado, a.ultimo_mes_facturado, a.area_construccion, a.area_terreno, a.norte, a.sur, a.este, a.oeste, a.tasa_interes, a.fecha_entrega_contrato FROM shd700_credito_vivienda a;


ALTER TABLE public.v_shd700_credito_vivienda OWNER TO sisap;

--
-- TOC entry 4268 (class 1259 OID 503140)
-- Dependencies: 4654 3
-- Name: v_shd700_credito_vivienda_parentesco; Type: VIEW; Schema: public; Owner: sisap
--

CREATE VIEW v_shd700_credito_vivienda_parentesco AS
    SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.rif_cedula, a.numero_solicitud, a.cod_parentesco, (SELECT b.denominacion FROM cnmd06_parentesco b WHERE (a.cod_parentesco = b.cod_parentesco)) AS deno_parentesco, a.nombre_apellido, a.sexo, a.fecha_nacimiento FROM shd700_credito_vivienda_parentesco a;


ALTER TABLE public.v_shd700_credito_vivienda_parentesco OWNER TO sisap;

--
-- TOC entry 4211 (class 1259 OID 502426)
-- Dependencies: 4601 3
-- Name: v_shd900_cobranza_acumulada; Type: VIEW; Schema: public; Owner: sisap
--

CREATE VIEW v_shd900_cobranza_acumulada AS
    SELECT b.cod_presi, b.cod_entidad, b.cod_tipo_inst, b.cod_inst, b.cod_dep, b.ano, b.mes, b.dia, b.cod_partida, b.cod_generica, b.cod_especifica, b.cod_sub_espec, b.cod_auxiliar, b.deuda_vigente, b.deuda_anterior, b.monto_recargo, b.monto_multa, b.monto_intereses, b.monto_descuento, b.cantidad_depositos, b.monto_depositos, b.cantidad_notas_credito, b.monto_notas_credito, b.cantidad_cheques, b.monto_cheques, b.cantidad_descuento, b.cantidad_pagos_efectivo, b.monto_pagos_efectivo, (SELECT a.denominacion FROM v_consulta_ingreso a WHERE (((((a.cod_partida_completo = b.cod_partida) AND (a.cod_generica = b.cod_generica)) AND (a.cod_especifica = b.cod_especifica)) AND (a.cod_sub_espec = b.cod_sub_espec)) AND (a.cod_auxiliar = b.cod_auxiliar)) ORDER BY a.denominacion DESC LIMIT 1) AS denominacion_ingreso FROM shd900_cobranza_acumulada b;


ALTER TABLE public.v_shd900_cobranza_acumulada OWNER TO sisap;

--
-- TOC entry 4212 (class 1259 OID 502431)
-- Dependencies: 4602 3
-- Name: v_shd900_cobranza_acumulada_ano; Type: VIEW; Schema: public; Owner: sisap
--

CREATE VIEW v_shd900_cobranza_acumulada_ano AS
    SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.ano, a.cod_partida, a.cod_generica, a.cod_especifica, a.cod_sub_espec, a.cod_auxiliar, sum(a.deuda_vigente) AS deuda_vigente, sum(a.deuda_anterior) AS deuda_anterior, sum(a.monto_recargo) AS monto_recargo, sum(a.monto_multa) AS monto_multa, sum(a.monto_intereses) AS monto_intereses, sum(a.monto_descuento) AS monto_descuento, sum(a.cantidad_depositos) AS cantidad_depositos, sum(a.monto_depositos) AS monto_depositos, sum(a.cantidad_notas_credito) AS cantidad_notas_credito, sum(a.monto_notas_credito) AS monto_notas_credito, sum(a.cantidad_cheques) AS cantidad_cheques, sum(a.monto_cheques) AS monto_cheques, sum(a.cantidad_descuento) AS cantidad_descuento, sum(a.cantidad_pagos_efectivo) AS cantidad_pagos_efectivo, sum(a.monto_pagos_efectivo) AS monto_pagos_efectivo, a.denominacion_ingreso FROM v_shd900_cobranza_acumulada a GROUP BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.ano, a.cod_partida, a.cod_generica, a.cod_especifica, a.cod_sub_espec, a.cod_auxiliar, a.denominacion_ingreso;


ALTER TABLE public.v_shd900_cobranza_acumulada_ano OWNER TO sisap;

--
-- TOC entry 4213 (class 1259 OID 502435)
-- Dependencies: 4603 3
-- Name: v_shd900_cobranza_acumulada_ano_mes; Type: VIEW; Schema: public; Owner: sisap
--

CREATE VIEW v_shd900_cobranza_acumulada_ano_mes AS
    SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.ano, a.mes, a.cod_partida, a.cod_generica, a.cod_especifica, a.cod_sub_espec, a.cod_auxiliar, sum(a.deuda_vigente) AS deuda_vigente, sum(a.deuda_anterior) AS deuda_anterior, sum(a.monto_recargo) AS monto_recargo, sum(a.monto_multa) AS monto_multa, sum(a.monto_intereses) AS monto_intereses, sum(a.monto_descuento) AS monto_descuento, sum(a.cantidad_depositos) AS cantidad_depositos, sum(a.monto_depositos) AS monto_depositos, sum(a.cantidad_notas_credito) AS cantidad_notas_credito, sum(a.monto_notas_credito) AS monto_notas_credito, sum(a.cantidad_cheques) AS cantidad_cheques, sum(a.monto_cheques) AS monto_cheques, sum(a.cantidad_descuento) AS cantidad_descuento, sum(a.cantidad_pagos_efectivo) AS cantidad_pagos_efectivo, sum(a.monto_pagos_efectivo) AS monto_pagos_efectivo, a.denominacion_ingreso FROM v_shd900_cobranza_acumulada a GROUP BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.ano, a.mes, a.cod_partida, a.cod_generica, a.cod_especifica, a.cod_sub_espec, a.cod_auxiliar, a.denominacion_ingreso;


ALTER TABLE public.v_shd900_cobranza_acumulada_ano_mes OWNER TO sisap;

--
-- TOC entry 4214 (class 1259 OID 502439)
-- Dependencies: 4604 3
-- Name: v_shd900_cobranza_acumulada_ano_mes_dia; Type: VIEW; Schema: public; Owner: sisap
--

CREATE VIEW v_shd900_cobranza_acumulada_ano_mes_dia AS
    SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.ano, a.mes, a.dia, a.cod_partida, a.cod_generica, a.cod_especifica, a.cod_sub_espec, a.cod_auxiliar, sum(a.deuda_vigente) AS deuda_vigente, sum(a.deuda_anterior) AS deuda_anterior, sum(a.monto_recargo) AS monto_recargo, sum(a.monto_multa) AS monto_multa, sum(a.monto_intereses) AS monto_intereses, sum(a.monto_descuento) AS monto_descuento, sum(a.cantidad_depositos) AS cantidad_depositos, sum(a.monto_depositos) AS monto_depositos, sum(a.cantidad_notas_credito) AS cantidad_notas_credito, sum(a.monto_notas_credito) AS monto_notas_credito, sum(a.cantidad_cheques) AS cantidad_cheques, sum(a.monto_cheques) AS monto_cheques, sum(a.cantidad_descuento) AS cantidad_descuento, sum(a.cantidad_pagos_efectivo) AS cantidad_pagos_efectivo, sum(a.monto_pagos_efectivo) AS monto_pagos_efectivo, a.denominacion_ingreso FROM v_shd900_cobranza_acumulada a GROUP BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.ano, a.mes, a.dia, a.cod_partida, a.cod_generica, a.cod_especifica, a.cod_sub_espec, a.cod_auxiliar, a.denominacion_ingreso;


ALTER TABLE public.v_shd900_cobranza_acumulada_ano_mes_dia OWNER TO sisap;

--
-- TOC entry 4261 (class 1259 OID 503056)
-- Dependencies: 4647 3
-- Name: v_shd900_pdpcdc; Type: VIEW; Schema: public; Owner: sisap
--

CREATE VIEW v_shd900_pdpcdc AS
    SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cod_partida, a.cod_generica, a.cod_especifica, a.cod_sub_espec, a.cod_auxiliar, a.rif_cedula, a.cod_numero_catastral_placas, a.ano, a.mes, a.numero_planilla, a.deuda_vigente, a.monto_recargo, a.monto_multa, a.monto_intereses, a.monto_descuento, a.cancelado, a.fecha_emision, b.condicion FROM shd900_planillas_deuda_cobro_detalles a, v_shd000_control_actua_partida b WHERE ((((((((((((b.cod_presi = a.cod_presi) AND (b.cod_entidad = a.cod_entidad)) AND (b.cod_tipo_inst = a.cod_tipo_inst)) AND (b.cod_inst = a.cod_inst)) AND (b.cod_dep = a.cod_dep)) AND (b.ano_actualizado = a.ano)) AND (b.mes_actualizado = a.mes)) AND (b.cod_partida = a.cod_partida)) AND (b.cod_generica = a.cod_generica)) AND (b.cod_especifica = a.cod_especifica)) AND (b.cod_sub_espec = a.cod_sub_espec)) AND (b.cod_auxiliar = a.cod_auxiliar));


ALTER TABLE public.v_shd900_pdpcdc OWNER TO sisap;

--
-- TOC entry 5681 (class 0 OID 0)
-- Dependencies: 4261
-- Name: VIEW v_shd900_pdpcdc; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON VIEW v_shd900_pdpcdc IS 'vista de la tabla shd900_planilla_deuda_cobro_detalles
la cual se trae la condicion de la vista v_shd000_control_actua_partida';


--
-- TOC entry 4258 (class 1259 OID 503042)
-- Dependencies: 4644 3
-- Name: v_shd900_planillas_deuda_cobro_detalles_cobradores; Type: VIEW; Schema: public; Owner: sisap
--

CREATE VIEW v_shd900_planillas_deuda_cobro_detalles_cobradores AS
    SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cod_partida, a.cod_generica, a.cod_especifica, a.cod_sub_espec, a.cod_auxiliar, (SELECT aa.cod_ingreso FROM shd003_codigo_ingresos aa WHERE (((((aa.cod_partida = a.cod_partida) AND (aa.cod_generica = a.cod_generica)) AND (aa.cod_especifica = a.cod_especifica)) AND (aa.cod_subespec = a.cod_sub_espec)) AND (aa.cod_auxiliar = a.cod_auxiliar))) AS cod_ingreso, a.rif_cedula, a.cod_numero_catastral_placas, a.ano, a.mes, a.numero_planilla, a.deuda_vigente, a.monto_recargo, a.monto_multa, a.monto_intereses, a.monto_descuento, a.cancelado, (SELECT s.rif_ci_cobrador FROM shd100_patente s WHERE (((((((s.cod_presi = a.cod_presi) AND (s.cod_entidad = a.cod_entidad)) AND (s.cod_tipo_inst = a.cod_tipo_inst)) AND (s.cod_inst = a.cod_inst)) AND (s.cod_dep = a.cod_dep)) AND (1 = (SELECT aa.cod_ingreso FROM shd003_codigo_ingresos aa WHERE (((((aa.cod_partida = a.cod_partida) AND (aa.cod_generica = a.cod_generica)) AND (aa.cod_especifica = a.cod_especifica)) AND (aa.cod_subespec = a.cod_sub_espec)) AND (aa.cod_auxiliar = a.cod_auxiliar))))) AND ((s.rif_cedula)::text = (a.rif_cedula)::text)) LIMIT 1) AS rif_cobrador_1, (SELECT s.rif_ci_cobrador FROM shd200_vehiculos s WHERE ((((((((s.cod_presi = a.cod_presi) AND (s.cod_entidad = a.cod_entidad)) AND (s.cod_tipo_inst = a.cod_tipo_inst)) AND (s.cod_inst = a.cod_inst)) AND (s.cod_dep = a.cod_dep)) AND ((s.rif_cedula)::text = (a.rif_cedula)::text)) AND ((s.placa_vehiculo)::text = (a.cod_numero_catastral_placas)::text)) AND (2 = (SELECT aa.cod_ingreso FROM shd003_codigo_ingresos aa WHERE (((((aa.cod_partida = a.cod_partida) AND (aa.cod_generica = a.cod_generica)) AND (aa.cod_especifica = a.cod_especifica)) AND (aa.cod_subespec = a.cod_sub_espec)) AND (aa.cod_auxiliar = a.cod_auxiliar)) LIMIT 1)))) AS rif_cobrador_2, (SELECT s.rif_ci_cobrador FROM shd300_propaganda s WHERE (((((((s.cod_presi = a.cod_presi) AND (s.cod_entidad = a.cod_entidad)) AND (s.cod_tipo_inst = a.cod_tipo_inst)) AND (s.cod_inst = a.cod_inst)) AND (s.cod_dep = a.cod_dep)) AND ((s.rif_cedula)::text = (a.rif_cedula)::text)) AND (3 = (SELECT aa.cod_ingreso FROM shd003_codigo_ingresos aa WHERE (((((aa.cod_partida = a.cod_partida) AND (aa.cod_generica = a.cod_generica)) AND (aa.cod_especifica = a.cod_especifica)) AND (aa.cod_subespec = a.cod_sub_espec)) AND (aa.cod_auxiliar = a.cod_auxiliar)) LIMIT 1)))) AS rif_cobrador_3, (SELECT s.rif_ci_cobrador FROM shd400_propiedad s WHERE ((((((((s.cod_presi = a.cod_presi) AND (s.cod_entidad = a.cod_entidad)) AND (s.cod_tipo_inst = a.cod_tipo_inst)) AND (s.cod_inst = a.cod_inst)) AND (s.cod_dep = a.cod_dep)) AND ((s.rif_cedula)::text = (a.rif_cedula)::text)) AND ((s.cod_ficha)::text = (a.cod_numero_catastral_placas)::text)) AND (4 = (SELECT aa.cod_ingreso FROM shd003_codigo_ingresos aa WHERE (((((aa.cod_partida = a.cod_partida) AND (aa.cod_generica = a.cod_generica)) AND (aa.cod_especifica = a.cod_especifica)) AND (aa.cod_subespec = a.cod_sub_espec)) AND (aa.cod_auxiliar = a.cod_auxiliar)) LIMIT 1)))) AS rif_cobrador_4, (SELECT s.rif_ci_cobrador FROM shd500_aseo_domiciliario s WHERE (((((((s.cod_presi = a.cod_presi) AND (s.cod_entidad = a.cod_entidad)) AND (s.cod_tipo_inst = a.cod_tipo_inst)) AND (s.cod_inst = a.cod_inst)) AND (s.cod_dep = a.cod_dep)) AND ((s.rif_cedula)::text = (a.rif_cedula)::text)) AND (5 = (SELECT aa.cod_ingreso FROM shd003_codigo_ingresos aa WHERE (((((aa.cod_partida = a.cod_partida) AND (aa.cod_generica = a.cod_generica)) AND (aa.cod_especifica = a.cod_especifica)) AND (aa.cod_subespec = a.cod_sub_espec)) AND (aa.cod_auxiliar = a.cod_auxiliar)) LIMIT 1)))) AS rif_cobrador_5, (SELECT s.rif_ci_cobrador FROM shd600_aprobacion_arrendamiento s WHERE ((((((((s.cod_presi = a.cod_presi) AND (s.cod_entidad = a.cod_entidad)) AND (s.cod_tipo_inst = a.cod_tipo_inst)) AND (s.cod_inst = a.cod_inst)) AND (s.cod_dep = a.cod_dep)) AND ((s.numero_solicitud)::text = (a.cod_numero_catastral_placas)::text)) AND (6 = (SELECT aa.cod_ingreso FROM shd003_codigo_ingresos aa WHERE (((((aa.cod_partida = a.cod_partida) AND (aa.cod_generica = a.cod_generica)) AND (aa.cod_especifica = a.cod_especifica)) AND (aa.cod_subespec = a.cod_sub_espec)) AND (aa.cod_auxiliar = a.cod_auxiliar)) LIMIT 1))) AND ((s.rif_cedula)::text = (a.rif_cedula)::text)) LIMIT 1) AS rif_cobrador_6, (SELECT s.rif_ci_cobrador FROM shd700_credito_vivienda s WHERE ((((((((s.cod_presi = a.cod_presi) AND (s.cod_entidad = a.cod_entidad)) AND (s.cod_tipo_inst = a.cod_tipo_inst)) AND (s.cod_inst = a.cod_inst)) AND (s.cod_dep = a.cod_dep)) AND ((s.rif_cedula)::text = (a.rif_cedula)::text)) AND ((s.numero_solicitud)::text = (a.cod_numero_catastral_placas)::text)) AND (7 = (SELECT aa.cod_ingreso FROM shd003_codigo_ingresos aa WHERE (((((aa.cod_partida = a.cod_partida) AND (aa.cod_generica = a.cod_generica)) AND (aa.cod_especifica = a.cod_especifica)) AND (aa.cod_subespec = a.cod_sub_espec)) AND (aa.cod_auxiliar = a.cod_auxiliar)) LIMIT 1)))) AS rif_cobrador_7 FROM shd900_planillas_deuda_cobro_detalles a;


ALTER TABLE public.v_shd900_planillas_deuda_cobro_detalles_cobradores OWNER TO sisap;

--
-- TOC entry 4259 (class 1259 OID 503047)
-- Dependencies: 4645 3
-- Name: v_shd900_planillas_deuda_cobro_detalles_cobradores_2; Type: VIEW; Schema: public; Owner: sisap
--

CREATE VIEW v_shd900_planillas_deuda_cobro_detalles_cobradores_2 AS
    (((((SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cod_partida, a.cod_generica, a.cod_especifica, a.cod_sub_espec, a.cod_auxiliar, a.cod_ingreso, (SELECT s.denominacion FROM shd003_codigo_ingresos s WHERE (s.cod_ingreso = a.cod_ingreso)) AS denominacion_impuesto, (SELECT v.descripcion FROM cfpd01_partida v WHERE ((v.cod_grupo = (substr((a.cod_partida)::text, 0, 2))::integer) AND (v.cod_partida = (substr((a.cod_partida)::text, 2))::integer)) LIMIT 1) AS deno_partida, (SELECT t.descripcion FROM cfpd01_generica t WHERE (((t.cod_grupo = (substr((a.cod_partida)::text, 0, 2))::integer) AND (t.cod_partida = (substr((a.cod_partida)::text, 2))::integer)) AND (t.cod_generica = a.cod_generica)) LIMIT 1) AS deno_generica, (SELECT s.descripcion FROM cfpd01_especifica s WHERE ((((s.cod_grupo = (substr((a.cod_partida)::text, 0, 2))::integer) AND (s.cod_partida = (substr((a.cod_partida)::text, 2))::integer)) AND (s.cod_generica = a.cod_generica)) AND (s.cod_especifica = a.cod_especifica)) LIMIT 1) AS deno_especifica, (SELECT r.descripcion FROM cfpd01_sub_espec r WHERE (((((r.cod_grupo = (substr((a.cod_partida)::text, 0, 2))::integer) AND (r.cod_partida = (substr((a.cod_partida)::text, 2))::integer)) AND (r.cod_generica = a.cod_generica)) AND (r.cod_especifica = a.cod_especifica)) AND (r.cod_sub_espec = a.cod_sub_espec)) LIMIT 1) AS deno_sub_espe, (SELECT o.descripcion FROM cfpd01_auxiliar o WHERE ((((((o.cod_grupo = (substr((a.cod_partida)::text, 0, 2))::integer) AND (o.cod_partida = (substr((a.cod_partida)::text, 2))::integer)) AND (o.cod_generica = a.cod_generica)) AND (o.cod_especifica = a.cod_especifica)) AND (o.cod_sub_espec = a.cod_sub_espec)) AND (o.cod_auxiliar = a.cod_auxiliar)) LIMIT 1) AS deno_auxiliar, a.rif_cedula, (SELECT bb.personalidad_juridica FROM shd001_registro_contribuyentes bb WHERE ((bb.rif_cedula)::text = (a.rif_cedula)::text)) AS personalidad_juridica, (SELECT bb.razon_social_nombres FROM shd001_registro_contribuyentes bb WHERE ((bb.rif_cedula)::text = (a.rif_cedula)::text)) AS razon_social_nombres, (SELECT bb.personalidad FROM shd002_cobradores bb WHERE ((((((bb.cod_presi = a.cod_presi) AND (bb.cod_entidad = a.cod_entidad)) AND (bb.cod_tipo_inst = a.cod_tipo_inst)) AND (bb.cod_inst = a.cod_inst)) AND (bb.cod_dep = a.cod_dep)) AND ((bb.rif_ci)::text = (a.rif_cobrador_1)::text))) AS personalidad_cobrador, (SELECT bb.nombre_razon FROM shd002_cobradores bb WHERE ((((((bb.cod_presi = a.cod_presi) AND (bb.cod_entidad = a.cod_entidad)) AND (bb.cod_tipo_inst = a.cod_tipo_inst)) AND (bb.cod_inst = a.cod_inst)) AND (bb.cod_dep = a.cod_dep)) AND ((bb.rif_ci)::text = (a.rif_cobrador_1)::text))) AS nombre_razon_cobrador, (SELECT bb.condicion_actividad FROM shd002_cobradores bb WHERE ((((((bb.cod_presi = a.cod_presi) AND (bb.cod_entidad = a.cod_entidad)) AND (bb.cod_tipo_inst = a.cod_tipo_inst)) AND (bb.cod_inst = a.cod_inst)) AND (bb.cod_dep = a.cod_dep)) AND ((bb.rif_ci)::text = (a.rif_cobrador_1)::text))) AS condicion_actividad_cobrador, a.cod_numero_catastral_placas, a.ano, a.mes, a.numero_planilla, a.deuda_vigente, a.monto_recargo, a.monto_multa, a.monto_intereses, a.monto_descuento, a.cancelado, a.rif_cobrador_1 AS rif_cobrador FROM v_shd900_planillas_deuda_cobro_detalles_cobradores a WHERE (a.cod_ingreso = 1) UNION SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cod_partida, a.cod_generica, a.cod_especifica, a.cod_sub_espec, a.cod_auxiliar, a.cod_ingreso, (SELECT s.denominacion FROM shd003_codigo_ingresos s WHERE (s.cod_ingreso = a.cod_ingreso)) AS denominacion_impuesto, (SELECT v.descripcion FROM cfpd01_partida v WHERE ((v.cod_grupo = (substr((a.cod_partida)::text, 0, 2))::integer) AND (v.cod_partida = (substr((a.cod_partida)::text, 2))::integer)) LIMIT 1) AS deno_partida, (SELECT t.descripcion FROM cfpd01_generica t WHERE (((t.cod_grupo = (substr((a.cod_partida)::text, 0, 2))::integer) AND (t.cod_partida = (substr((a.cod_partida)::text, 2))::integer)) AND (t.cod_generica = a.cod_generica)) LIMIT 1) AS deno_generica, (SELECT s.descripcion FROM cfpd01_especifica s WHERE ((((s.cod_grupo = (substr((a.cod_partida)::text, 0, 2))::integer) AND (s.cod_partida = (substr((a.cod_partida)::text, 2))::integer)) AND (s.cod_generica = a.cod_generica)) AND (s.cod_especifica = a.cod_especifica)) LIMIT 1) AS deno_especifica, (SELECT r.descripcion FROM cfpd01_sub_espec r WHERE (((((r.cod_grupo = (substr((a.cod_partida)::text, 0, 2))::integer) AND (r.cod_partida = (substr((a.cod_partida)::text, 2))::integer)) AND (r.cod_generica = a.cod_generica)) AND (r.cod_especifica = a.cod_especifica)) AND (r.cod_sub_espec = a.cod_sub_espec)) LIMIT 1) AS deno_sub_espe, (SELECT o.descripcion FROM cfpd01_auxiliar o WHERE ((((((o.cod_grupo = (substr((a.cod_partida)::text, 0, 2))::integer) AND (o.cod_partida = (substr((a.cod_partida)::text, 2))::integer)) AND (o.cod_generica = a.cod_generica)) AND (o.cod_especifica = a.cod_especifica)) AND (o.cod_sub_espec = a.cod_sub_espec)) AND (o.cod_auxiliar = a.cod_auxiliar)) LIMIT 1) AS deno_auxiliar, a.rif_cedula, (SELECT bb.personalidad_juridica FROM shd001_registro_contribuyentes bb WHERE ((bb.rif_cedula)::text = (a.rif_cedula)::text)) AS personalidad_juridica, (SELECT bb.razon_social_nombres FROM shd001_registro_contribuyentes bb WHERE ((bb.rif_cedula)::text = (a.rif_cedula)::text)) AS razon_social_nombres, (SELECT bb.personalidad FROM shd002_cobradores bb WHERE ((((((bb.cod_presi = a.cod_presi) AND (bb.cod_entidad = a.cod_entidad)) AND (bb.cod_tipo_inst = a.cod_tipo_inst)) AND (bb.cod_inst = a.cod_inst)) AND (bb.cod_dep = a.cod_dep)) AND ((bb.rif_ci)::text = (a.rif_cobrador_2)::text))) AS personalidad_cobrador, (SELECT bb.nombre_razon FROM shd002_cobradores bb WHERE ((((((bb.cod_presi = a.cod_presi) AND (bb.cod_entidad = a.cod_entidad)) AND (bb.cod_tipo_inst = a.cod_tipo_inst)) AND (bb.cod_inst = a.cod_inst)) AND (bb.cod_dep = a.cod_dep)) AND ((bb.rif_ci)::text = (a.rif_cobrador_2)::text))) AS nombre_razon_cobrador, (SELECT bb.condicion_actividad FROM shd002_cobradores bb WHERE ((((((bb.cod_presi = a.cod_presi) AND (bb.cod_entidad = a.cod_entidad)) AND (bb.cod_tipo_inst = a.cod_tipo_inst)) AND (bb.cod_inst = a.cod_inst)) AND (bb.cod_dep = a.cod_dep)) AND ((bb.rif_ci)::text = (a.rif_cobrador_2)::text))) AS condicion_actividad_cobrador, a.cod_numero_catastral_placas, a.ano, a.mes, a.numero_planilla, a.deuda_vigente, a.monto_recargo, a.monto_multa, a.monto_intereses, a.monto_descuento, a.cancelado, a.rif_cobrador_2 AS rif_cobrador FROM v_shd900_planillas_deuda_cobro_detalles_cobradores a WHERE (a.cod_ingreso = 2)) UNION SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cod_partida, a.cod_generica, a.cod_especifica, a.cod_sub_espec, a.cod_auxiliar, a.cod_ingreso, (SELECT s.denominacion FROM shd003_codigo_ingresos s WHERE (s.cod_ingreso = a.cod_ingreso)) AS denominacion_impuesto, (SELECT v.descripcion FROM cfpd01_partida v WHERE ((v.cod_grupo = (substr((a.cod_partida)::text, 0, 2))::integer) AND (v.cod_partida = (substr((a.cod_partida)::text, 2))::integer)) LIMIT 1) AS deno_partida, (SELECT t.descripcion FROM cfpd01_generica t WHERE (((t.cod_grupo = (substr((a.cod_partida)::text, 0, 2))::integer) AND (t.cod_partida = (substr((a.cod_partida)::text, 2))::integer)) AND (t.cod_generica = a.cod_generica)) LIMIT 1) AS deno_generica, (SELECT s.descripcion FROM cfpd01_especifica s WHERE ((((s.cod_grupo = (substr((a.cod_partida)::text, 0, 2))::integer) AND (s.cod_partida = (substr((a.cod_partida)::text, 2))::integer)) AND (s.cod_generica = a.cod_generica)) AND (s.cod_especifica = a.cod_especifica)) LIMIT 1) AS deno_especifica, (SELECT r.descripcion FROM cfpd01_sub_espec r WHERE (((((r.cod_grupo = (substr((a.cod_partida)::text, 0, 2))::integer) AND (r.cod_partida = (substr((a.cod_partida)::text, 2))::integer)) AND (r.cod_generica = a.cod_generica)) AND (r.cod_especifica = a.cod_especifica)) AND (r.cod_sub_espec = a.cod_sub_espec)) LIMIT 1) AS deno_sub_espe, (SELECT o.descripcion FROM cfpd01_auxiliar o WHERE ((((((o.cod_grupo = (substr((a.cod_partida)::text, 0, 2))::integer) AND (o.cod_partida = (substr((a.cod_partida)::text, 2))::integer)) AND (o.cod_generica = a.cod_generica)) AND (o.cod_especifica = a.cod_especifica)) AND (o.cod_sub_espec = a.cod_sub_espec)) AND (o.cod_auxiliar = a.cod_auxiliar)) LIMIT 1) AS deno_auxiliar, a.rif_cedula, (SELECT bb.personalidad_juridica FROM shd001_registro_contribuyentes bb WHERE ((bb.rif_cedula)::text = (a.rif_cedula)::text)) AS personalidad_juridica, (SELECT bb.razon_social_nombres FROM shd001_registro_contribuyentes bb WHERE ((bb.rif_cedula)::text = (a.rif_cedula)::text)) AS razon_social_nombres, (SELECT bb.personalidad FROM shd002_cobradores bb WHERE ((((((bb.cod_presi = a.cod_presi) AND (bb.cod_entidad = a.cod_entidad)) AND (bb.cod_tipo_inst = a.cod_tipo_inst)) AND (bb.cod_inst = a.cod_inst)) AND (bb.cod_dep = a.cod_dep)) AND ((bb.rif_ci)::text = (a.rif_cobrador_3)::text))) AS personalidad_cobrador, (SELECT bb.nombre_razon FROM shd002_cobradores bb WHERE ((((((bb.cod_presi = a.cod_presi) AND (bb.cod_entidad = a.cod_entidad)) AND (bb.cod_tipo_inst = a.cod_tipo_inst)) AND (bb.cod_inst = a.cod_inst)) AND (bb.cod_dep = a.cod_dep)) AND ((bb.rif_ci)::text = (a.rif_cobrador_3)::text))) AS nombre_razon_cobrador, (SELECT bb.condicion_actividad FROM shd002_cobradores bb WHERE ((((((bb.cod_presi = a.cod_presi) AND (bb.cod_entidad = a.cod_entidad)) AND (bb.cod_tipo_inst = a.cod_tipo_inst)) AND (bb.cod_inst = a.cod_inst)) AND (bb.cod_dep = a.cod_dep)) AND ((bb.rif_ci)::text = (a.rif_cobrador_3)::text))) AS condicion_actividad_cobrador, a.cod_numero_catastral_placas, a.ano, a.mes, a.numero_planilla, a.deuda_vigente, a.monto_recargo, a.monto_multa, a.monto_intereses, a.monto_descuento, a.cancelado, a.rif_cobrador_3 AS rif_cobrador FROM v_shd900_planillas_deuda_cobro_detalles_cobradores a WHERE (a.cod_ingreso = 3)) UNION SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cod_partida, a.cod_generica, a.cod_especifica, a.cod_sub_espec, a.cod_auxiliar, a.cod_ingreso, (SELECT s.denominacion FROM shd003_codigo_ingresos s WHERE (s.cod_ingreso = a.cod_ingreso)) AS denominacion_impuesto, (SELECT v.descripcion FROM cfpd01_partida v WHERE ((v.cod_grupo = (substr((a.cod_partida)::text, 0, 2))::integer) AND (v.cod_partida = (substr((a.cod_partida)::text, 2))::integer)) LIMIT 1) AS deno_partida, (SELECT t.descripcion FROM cfpd01_generica t WHERE (((t.cod_grupo = (substr((a.cod_partida)::text, 0, 2))::integer) AND (t.cod_partida = (substr((a.cod_partida)::text, 2))::integer)) AND (t.cod_generica = a.cod_generica)) LIMIT 1) AS deno_generica, (SELECT s.descripcion FROM cfpd01_especifica s WHERE ((((s.cod_grupo = (substr((a.cod_partida)::text, 0, 2))::integer) AND (s.cod_partida = (substr((a.cod_partida)::text, 2))::integer)) AND (s.cod_generica = a.cod_generica)) AND (s.cod_especifica = a.cod_especifica)) LIMIT 1) AS deno_especifica, (SELECT r.descripcion FROM cfpd01_sub_espec r WHERE (((((r.cod_grupo = (substr((a.cod_partida)::text, 0, 2))::integer) AND (r.cod_partida = (substr((a.cod_partida)::text, 2))::integer)) AND (r.cod_generica = a.cod_generica)) AND (r.cod_especifica = a.cod_especifica)) AND (r.cod_sub_espec = a.cod_sub_espec)) LIMIT 1) AS deno_sub_espe, (SELECT o.descripcion FROM cfpd01_auxiliar o WHERE ((((((o.cod_grupo = (substr((a.cod_partida)::text, 0, 2))::integer) AND (o.cod_partida = (substr((a.cod_partida)::text, 2))::integer)) AND (o.cod_generica = a.cod_generica)) AND (o.cod_especifica = a.cod_especifica)) AND (o.cod_sub_espec = a.cod_sub_espec)) AND (o.cod_auxiliar = a.cod_auxiliar)) LIMIT 1) AS deno_auxiliar, a.rif_cedula, (SELECT bb.personalidad_juridica FROM shd001_registro_contribuyentes bb WHERE ((bb.rif_cedula)::text = (a.rif_cedula)::text)) AS personalidad_juridica, (SELECT bb.razon_social_nombres FROM shd001_registro_contribuyentes bb WHERE ((bb.rif_cedula)::text = (a.rif_cedula)::text)) AS razon_social_nombres, (SELECT bb.personalidad FROM shd002_cobradores bb WHERE ((((((bb.cod_presi = a.cod_presi) AND (bb.cod_entidad = a.cod_entidad)) AND (bb.cod_tipo_inst = a.cod_tipo_inst)) AND (bb.cod_inst = a.cod_inst)) AND (bb.cod_dep = a.cod_dep)) AND ((bb.rif_ci)::text = (a.rif_cobrador_4)::text))) AS personalidad_cobrador, (SELECT bb.nombre_razon FROM shd002_cobradores bb WHERE ((((((bb.cod_presi = a.cod_presi) AND (bb.cod_entidad = a.cod_entidad)) AND (bb.cod_tipo_inst = a.cod_tipo_inst)) AND (bb.cod_inst = a.cod_inst)) AND (bb.cod_dep = a.cod_dep)) AND ((bb.rif_ci)::text = (a.rif_cobrador_4)::text))) AS nombre_razon_cobrador, (SELECT bb.condicion_actividad FROM shd002_cobradores bb WHERE ((((((bb.cod_presi = a.cod_presi) AND (bb.cod_entidad = a.cod_entidad)) AND (bb.cod_tipo_inst = a.cod_tipo_inst)) AND (bb.cod_inst = a.cod_inst)) AND (bb.cod_dep = a.cod_dep)) AND ((bb.rif_ci)::text = (a.rif_cobrador_4)::text))) AS condicion_actividad_cobrador, a.cod_numero_catastral_placas, a.ano, a.mes, a.numero_planilla, a.deuda_vigente, a.monto_recargo, a.monto_multa, a.monto_intereses, a.monto_descuento, a.cancelado, a.rif_cobrador_4 AS rif_cobrador FROM v_shd900_planillas_deuda_cobro_detalles_cobradores a WHERE (a.cod_ingreso = 4)) UNION SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cod_partida, a.cod_generica, a.cod_especifica, a.cod_sub_espec, a.cod_auxiliar, a.cod_ingreso, (SELECT s.denominacion FROM shd003_codigo_ingresos s WHERE (s.cod_ingreso = a.cod_ingreso)) AS denominacion_impuesto, (SELECT v.descripcion FROM cfpd01_partida v WHERE ((v.cod_grupo = (substr((a.cod_partida)::text, 0, 2))::integer) AND (v.cod_partida = (substr((a.cod_partida)::text, 2))::integer)) LIMIT 1) AS deno_partida, (SELECT t.descripcion FROM cfpd01_generica t WHERE (((t.cod_grupo = (substr((a.cod_partida)::text, 0, 2))::integer) AND (t.cod_partida = (substr((a.cod_partida)::text, 2))::integer)) AND (t.cod_generica = a.cod_generica)) LIMIT 1) AS deno_generica, (SELECT s.descripcion FROM cfpd01_especifica s WHERE ((((s.cod_grupo = (substr((a.cod_partida)::text, 0, 2))::integer) AND (s.cod_partida = (substr((a.cod_partida)::text, 2))::integer)) AND (s.cod_generica = a.cod_generica)) AND (s.cod_especifica = a.cod_especifica)) LIMIT 1) AS deno_especifica, (SELECT r.descripcion FROM cfpd01_sub_espec r WHERE (((((r.cod_grupo = (substr((a.cod_partida)::text, 0, 2))::integer) AND (r.cod_partida = (substr((a.cod_partida)::text, 2))::integer)) AND (r.cod_generica = a.cod_generica)) AND (r.cod_especifica = a.cod_especifica)) AND (r.cod_sub_espec = a.cod_sub_espec)) LIMIT 1) AS deno_sub_espe, (SELECT o.descripcion FROM cfpd01_auxiliar o WHERE ((((((o.cod_grupo = (substr((a.cod_partida)::text, 0, 2))::integer) AND (o.cod_partida = (substr((a.cod_partida)::text, 2))::integer)) AND (o.cod_generica = a.cod_generica)) AND (o.cod_especifica = a.cod_especifica)) AND (o.cod_sub_espec = a.cod_sub_espec)) AND (o.cod_auxiliar = a.cod_auxiliar)) LIMIT 1) AS deno_auxiliar, a.rif_cedula, (SELECT bb.personalidad_juridica FROM shd001_registro_contribuyentes bb WHERE ((bb.rif_cedula)::text = (a.rif_cedula)::text)) AS personalidad_juridica, (SELECT bb.razon_social_nombres FROM shd001_registro_contribuyentes bb WHERE ((bb.rif_cedula)::text = (a.rif_cedula)::text)) AS razon_social_nombres, (SELECT bb.personalidad FROM shd002_cobradores bb WHERE ((((((bb.cod_presi = a.cod_presi) AND (bb.cod_entidad = a.cod_entidad)) AND (bb.cod_tipo_inst = a.cod_tipo_inst)) AND (bb.cod_inst = a.cod_inst)) AND (bb.cod_dep = a.cod_dep)) AND ((bb.rif_ci)::text = (a.rif_cobrador_5)::text))) AS personalidad_cobrador, (SELECT bb.nombre_razon FROM shd002_cobradores bb WHERE ((((((bb.cod_presi = a.cod_presi) AND (bb.cod_entidad = a.cod_entidad)) AND (bb.cod_tipo_inst = a.cod_tipo_inst)) AND (bb.cod_inst = a.cod_inst)) AND (bb.cod_dep = a.cod_dep)) AND ((bb.rif_ci)::text = (a.rif_cobrador_5)::text))) AS nombre_razon_cobrador, (SELECT bb.condicion_actividad FROM shd002_cobradores bb WHERE ((((((bb.cod_presi = a.cod_presi) AND (bb.cod_entidad = a.cod_entidad)) AND (bb.cod_tipo_inst = a.cod_tipo_inst)) AND (bb.cod_inst = a.cod_inst)) AND (bb.cod_dep = a.cod_dep)) AND ((bb.rif_ci)::text = (a.rif_cobrador_5)::text))) AS condicion_actividad_cobrador, a.cod_numero_catastral_placas, a.ano, a.mes, a.numero_planilla, a.deuda_vigente, a.monto_recargo, a.monto_multa, a.monto_intereses, a.monto_descuento, a.cancelado, a.rif_cobrador_5 AS rif_cobrador FROM v_shd900_planillas_deuda_cobro_detalles_cobradores a WHERE (a.cod_ingreso = 5)) UNION SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cod_partida, a.cod_generica, a.cod_especifica, a.cod_sub_espec, a.cod_auxiliar, a.cod_ingreso, (SELECT s.denominacion FROM shd003_codigo_ingresos s WHERE (s.cod_ingreso = a.cod_ingreso)) AS denominacion_impuesto, (SELECT v.descripcion FROM cfpd01_partida v WHERE ((v.cod_grupo = (substr((a.cod_partida)::text, 0, 2))::integer) AND (v.cod_partida = (substr((a.cod_partida)::text, 2))::integer)) LIMIT 1) AS deno_partida, (SELECT t.descripcion FROM cfpd01_generica t WHERE (((t.cod_grupo = (substr((a.cod_partida)::text, 0, 2))::integer) AND (t.cod_partida = (substr((a.cod_partida)::text, 2))::integer)) AND (t.cod_generica = a.cod_generica)) LIMIT 1) AS deno_generica, (SELECT s.descripcion FROM cfpd01_especifica s WHERE ((((s.cod_grupo = (substr((a.cod_partida)::text, 0, 2))::integer) AND (s.cod_partida = (substr((a.cod_partida)::text, 2))::integer)) AND (s.cod_generica = a.cod_generica)) AND (s.cod_especifica = a.cod_especifica)) LIMIT 1) AS deno_especifica, (SELECT r.descripcion FROM cfpd01_sub_espec r WHERE (((((r.cod_grupo = (substr((a.cod_partida)::text, 0, 2))::integer) AND (r.cod_partida = (substr((a.cod_partida)::text, 2))::integer)) AND (r.cod_generica = a.cod_generica)) AND (r.cod_especifica = a.cod_especifica)) AND (r.cod_sub_espec = a.cod_sub_espec)) LIMIT 1) AS deno_sub_espe, (SELECT o.descripcion FROM cfpd01_auxiliar o WHERE ((((((o.cod_grupo = (substr((a.cod_partida)::text, 0, 2))::integer) AND (o.cod_partida = (substr((a.cod_partida)::text, 2))::integer)) AND (o.cod_generica = a.cod_generica)) AND (o.cod_especifica = a.cod_especifica)) AND (o.cod_sub_espec = a.cod_sub_espec)) AND (o.cod_auxiliar = a.cod_auxiliar)) LIMIT 1) AS deno_auxiliar, a.rif_cedula, (SELECT bb.personalidad_juridica FROM shd001_registro_contribuyentes bb WHERE ((bb.rif_cedula)::text = (a.rif_cedula)::text)) AS personalidad_juridica, (SELECT bb.razon_social_nombres FROM shd001_registro_contribuyentes bb WHERE ((bb.rif_cedula)::text = (a.rif_cedula)::text)) AS razon_social_nombres, (SELECT bb.personalidad FROM shd002_cobradores bb WHERE ((((((bb.cod_presi = a.cod_presi) AND (bb.cod_entidad = a.cod_entidad)) AND (bb.cod_tipo_inst = a.cod_tipo_inst)) AND (bb.cod_inst = a.cod_inst)) AND (bb.cod_dep = a.cod_dep)) AND ((bb.rif_ci)::text = (a.rif_cobrador_6)::text))) AS personalidad_cobrador, (SELECT bb.nombre_razon FROM shd002_cobradores bb WHERE ((((((bb.cod_presi = a.cod_presi) AND (bb.cod_entidad = a.cod_entidad)) AND (bb.cod_tipo_inst = a.cod_tipo_inst)) AND (bb.cod_inst = a.cod_inst)) AND (bb.cod_dep = a.cod_dep)) AND ((bb.rif_ci)::text = (a.rif_cobrador_6)::text))) AS nombre_razon_cobrador, (SELECT bb.condicion_actividad FROM shd002_cobradores bb WHERE ((((((bb.cod_presi = a.cod_presi) AND (bb.cod_entidad = a.cod_entidad)) AND (bb.cod_tipo_inst = a.cod_tipo_inst)) AND (bb.cod_inst = a.cod_inst)) AND (bb.cod_dep = a.cod_dep)) AND ((bb.rif_ci)::text = (a.rif_cobrador_6)::text))) AS condicion_actividad_cobrador, a.cod_numero_catastral_placas, a.ano, a.mes, a.numero_planilla, a.deuda_vigente, a.monto_recargo, a.monto_multa, a.monto_intereses, a.monto_descuento, a.cancelado, a.rif_cobrador_6 AS rif_cobrador FROM v_shd900_planillas_deuda_cobro_detalles_cobradores a WHERE (a.cod_ingreso = 6)) UNION SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cod_partida, a.cod_generica, a.cod_especifica, a.cod_sub_espec, a.cod_auxiliar, a.cod_ingreso, (SELECT s.denominacion FROM shd003_codigo_ingresos s WHERE (s.cod_ingreso = a.cod_ingreso)) AS denominacion_impuesto, (SELECT v.descripcion FROM cfpd01_partida v WHERE ((v.cod_grupo = (substr((a.cod_partida)::text, 0, 2))::integer) AND (v.cod_partida = (substr((a.cod_partida)::text, 2))::integer)) LIMIT 1) AS deno_partida, (SELECT t.descripcion FROM cfpd01_generica t WHERE (((t.cod_grupo = (substr((a.cod_partida)::text, 0, 2))::integer) AND (t.cod_partida = (substr((a.cod_partida)::text, 2))::integer)) AND (t.cod_generica = a.cod_generica)) LIMIT 1) AS deno_generica, (SELECT s.descripcion FROM cfpd01_especifica s WHERE ((((s.cod_grupo = (substr((a.cod_partida)::text, 0, 2))::integer) AND (s.cod_partida = (substr((a.cod_partida)::text, 2))::integer)) AND (s.cod_generica = a.cod_generica)) AND (s.cod_especifica = a.cod_especifica)) LIMIT 1) AS deno_especifica, (SELECT r.descripcion FROM cfpd01_sub_espec r WHERE (((((r.cod_grupo = (substr((a.cod_partida)::text, 0, 2))::integer) AND (r.cod_partida = (substr((a.cod_partida)::text, 2))::integer)) AND (r.cod_generica = a.cod_generica)) AND (r.cod_especifica = a.cod_especifica)) AND (r.cod_sub_espec = a.cod_sub_espec)) LIMIT 1) AS deno_sub_espe, (SELECT o.descripcion FROM cfpd01_auxiliar o WHERE ((((((o.cod_grupo = (substr((a.cod_partida)::text, 0, 2))::integer) AND (o.cod_partida = (substr((a.cod_partida)::text, 2))::integer)) AND (o.cod_generica = a.cod_generica)) AND (o.cod_especifica = a.cod_especifica)) AND (o.cod_sub_espec = a.cod_sub_espec)) AND (o.cod_auxiliar = a.cod_auxiliar)) LIMIT 1) AS deno_auxiliar, a.rif_cedula, (SELECT bb.personalidad_juridica FROM shd001_registro_contribuyentes bb WHERE ((bb.rif_cedula)::text = (a.rif_cedula)::text)) AS personalidad_juridica, (SELECT bb.razon_social_nombres FROM shd001_registro_contribuyentes bb WHERE ((bb.rif_cedula)::text = (a.rif_cedula)::text)) AS razon_social_nombres, (SELECT bb.personalidad FROM shd002_cobradores bb WHERE ((((((bb.cod_presi = a.cod_presi) AND (bb.cod_entidad = a.cod_entidad)) AND (bb.cod_tipo_inst = a.cod_tipo_inst)) AND (bb.cod_inst = a.cod_inst)) AND (bb.cod_dep = a.cod_dep)) AND ((bb.rif_ci)::text = (a.rif_cobrador_7)::text))) AS personalidad_cobrador, (SELECT bb.nombre_razon FROM shd002_cobradores bb WHERE ((((((bb.cod_presi = a.cod_presi) AND (bb.cod_entidad = a.cod_entidad)) AND (bb.cod_tipo_inst = a.cod_tipo_inst)) AND (bb.cod_inst = a.cod_inst)) AND (bb.cod_dep = a.cod_dep)) AND ((bb.rif_ci)::text = (a.rif_cobrador_7)::text))) AS nombre_razon_cobrador, (SELECT bb.condicion_actividad FROM shd002_cobradores bb WHERE ((((((bb.cod_presi = a.cod_presi) AND (bb.cod_entidad = a.cod_entidad)) AND (bb.cod_tipo_inst = a.cod_tipo_inst)) AND (bb.cod_inst = a.cod_inst)) AND (bb.cod_dep = a.cod_dep)) AND ((bb.rif_ci)::text = (a.rif_cobrador_7)::text))) AS condicion_actividad_cobrador, a.cod_numero_catastral_placas, a.ano, a.mes, a.numero_planilla, a.deuda_vigente, a.monto_recargo, a.monto_multa, a.monto_intereses, a.monto_descuento, a.cancelado, a.rif_cobrador_7 AS rif_cobrador FROM v_shd900_planillas_deuda_cobro_detalles_cobradores a WHERE (a.cod_ingreso = 7);


ALTER TABLE public.v_shd900_planillas_deuda_cobro_detalles_cobradores_2 OWNER TO sisap;

--
-- TOC entry 4229 (class 1259 OID 502753)
-- Dependencies: 4619 3
-- Name: v_shd950_solvencia_detalles; Type: VIEW; Schema: public; Owner: sisap
--

CREATE VIEW v_shd950_solvencia_detalles AS
    SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cod_partida, a.cod_generica, a.cod_especifica, a.cod_sub_espec, a.cod_auxiliar, a.rif_cedula, (SELECT b.denominacion FROM shd003_codigo_ingresos b WHERE (((((b.cod_partida = a.cod_partida) AND (b.cod_generica = a.cod_generica)) AND (b.cod_especifica = a.cod_especifica)) AND (b.cod_subespec = a.cod_sub_espec)) AND (b.cod_auxiliar = a.cod_auxiliar))) AS denominacion_ingreso, (SELECT b.cod_ingreso FROM shd003_codigo_ingresos b WHERE (((((b.cod_partida = a.cod_partida) AND (b.cod_generica = a.cod_generica)) AND (b.cod_especifica = a.cod_especifica)) AND (b.cod_subespec = a.cod_sub_espec)) AND (b.cod_auxiliar = a.cod_auxiliar))) AS codigo_ingreso, a.cancelado FROM shd900_planillas_deuda_cobro_detalles a;


ALTER TABLE public.v_shd950_solvencia_detalles OWNER TO sisap;

--
-- TOC entry 4205 (class 1259 OID 502382)
-- Dependencies: 4595 3
-- Name: v_shp002_cobranza_estado_cuenta_1; Type: VIEW; Schema: public; Owner: sisap
--

CREATE VIEW v_shp002_cobranza_estado_cuenta_1 AS
    SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.rif_ci, a.personalidad, a.nombre_razon, a.fecha_ingreso, a.recurso_cobro, a.condicion_actividad, b.ano AS ano_cobranza, b.cobranza_pendiente_acumulada, 0 AS cobranza_realizada_acumulada FROM shd002_cobradores a, shd002_cobranza_pendiente b WHERE ((((((b.cod_presi = a.cod_presi) AND (b.cod_entidad = a.cod_entidad)) AND (b.cod_tipo_inst = a.cod_tipo_inst)) AND (b.cod_inst = a.cod_inst)) AND (b.cod_dep = a.cod_dep)) AND ((b.rif_ci)::text = (a.rif_ci)::text)) UNION SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.rif_ci, a.personalidad, a.nombre_razon, a.fecha_ingreso, a.recurso_cobro, a.condicion_actividad, c.ano AS ano_cobranza, 0 AS cobranza_pendiente_acumulada, c.cobranza_acumulada AS cobranza_realizada_acumulada FROM shd002_cobradores a, shd002_cobranza_realizada c WHERE ((((((c.cod_presi = a.cod_presi) AND (c.cod_entidad = a.cod_entidad)) AND (c.cod_tipo_inst = a.cod_tipo_inst)) AND (c.cod_inst = a.cod_inst)) AND (c.cod_dep = a.cod_dep)) AND ((c.rif_ci)::text = (a.rif_ci)::text));


ALTER TABLE public.v_shp002_cobranza_estado_cuenta_1 OWNER TO sisap;

--
-- TOC entry 4206 (class 1259 OID 502387)
-- Dependencies: 4596 3
-- Name: v_shp002_cobranza_estado_cuenta_2; Type: VIEW; Schema: public; Owner: sisap
--

CREATE VIEW v_shp002_cobranza_estado_cuenta_2 AS
    SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.rif_ci, a.personalidad, a.nombre_razon, a.fecha_ingreso, a.recurso_cobro, a.condicion_actividad, a.ano_cobranza FROM v_shp002_cobranza_estado_cuenta_1 a GROUP BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.rif_ci, a.ano_cobranza, a.personalidad, a.nombre_razon, a.fecha_ingreso, a.recurso_cobro, a.condicion_actividad ORDER BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.rif_ci, a.ano_cobranza;


ALTER TABLE public.v_shp002_cobranza_estado_cuenta_2 OWNER TO sisap;

--
-- TOC entry 4849 (class 2604 OID 502149)
-- Dependencies: 4170 4171 4171
-- Name: codigo_clase; Type: DEFAULT; Schema: public; Owner: sisap
--

ALTER TABLE shd200_vehiculos_clases ALTER COLUMN codigo_clase SET DEFAULT nextval('shd200_vehiculos_clases_codigo_clase_seq'::regclass);


--
-- TOC entry 4850 (class 2604 OID 502162)
-- Dependencies: 4173 4174 4174
-- Name: codigo_color; Type: DEFAULT; Schema: public; Owner: sisap
--

ALTER TABLE shd200_vehiculos_colores ALTER COLUMN codigo_color SET DEFAULT nextval('shd200_vehiculos_colores_codigo_color_seq'::regclass);


--
-- TOC entry 4851 (class 2604 OID 502170)
-- Dependencies: 4175 4176 4176
-- Name: codigo_marca; Type: DEFAULT; Schema: public; Owner: sisap
--

ALTER TABLE shd200_vehiculos_marcas ALTER COLUMN codigo_marca SET DEFAULT nextval('shd200_vehiculos_marcas_codigo_marca_seq'::regclass);


--
-- TOC entry 4852 (class 2604 OID 502178)
-- Dependencies: 4177 4178 4178
-- Name: codigo_modelo; Type: DEFAULT; Schema: public; Owner: sisap
--

ALTER TABLE shd200_vehiculos_modelos ALTER COLUMN codigo_modelo SET DEFAULT nextval('shd200_vehiculos_modelos_codigo_modelo_seq'::regclass);


--
-- TOC entry 4853 (class 2604 OID 502186)
-- Dependencies: 4179 4180 4180
-- Name: codigo_tipo; Type: DEFAULT; Schema: public; Owner: sisap
--

ALTER TABLE shd200_vehiculos_tipos ALTER COLUMN codigo_tipo SET DEFAULT nextval('shd200_vehiculos_tipos_codigo_tipo_seq'::regclass);


--
-- TOC entry 4854 (class 2604 OID 502194)
-- Dependencies: 4181 4182 4182
-- Name: codigo_uso; Type: DEFAULT; Schema: public; Owner: sisap
--

ALTER TABLE shd200_vehiculos_usos ALTER COLUMN codigo_uso SET DEFAULT nextval('shd200_vehiculos_usos_codigo_uso_seq'::regclass);


--
-- TOC entry 4957 (class 0 OID 502028)
-- Dependencies: 4152
-- Data for Name: shd000_arranque; Type: TABLE DATA; Schema: public; Owner: sisap
--

COPY shd000_arranque (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, ano_arranque, mes_arranque) FROM stdin;
1	11	30	11	1	2009	5
\.


--
-- TOC entry 4958 (class 0 OID 502033)
-- Dependencies: 4153
-- Data for Name: shd000_control_actualizacion; Type: TABLE DATA; Schema: public; Owner: sisap
--

COPY shd000_control_actualizacion (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_ingreso, ano_actualizado, mes_actualizado, condicion) FROM stdin;
1	11	30	11	1	1	2009	1	2
1	11	30	11	1	2	2009	1	2
1	11	30	11	1	1	2009	2	2
1	11	30	11	1	2	2009	2	2
1	11	30	11	1	5	2009	1	1
1	11	30	11	1	5	2009	2	1
1	11	30	11	1	1	2009	3	2
1	11	30	11	1	2	2009	3	2
1	11	30	11	1	5	2009	3	2
1	11	30	11	1	1	2009	4	2
1	11	30	11	1	2	2009	4	2
1	11	30	11	1	5	2009	4	2
1	11	30	11	1	1	2009	5	2
1	11	30	11	1	2	2009	5	2
1	11	30	11	1	5	2009	5	2
\.


--
-- TOC entry 4959 (class 0 OID 502039)
-- Dependencies: 4154
-- Data for Name: shd000_control_numero; Type: TABLE DATA; Schema: public; Owner: sisap
--

COPY shd000_control_numero (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, ano, cod_ingreso, numero_planilla) FROM stdin;
1	11	30	11	1	2009	1	23
1	11	30	11	1	2009	2	15
1	11	30	11	1	2009	5	5
\.


--
-- TOC entry 4960 (class 0 OID 502044)
-- Dependencies: 4155
-- Data for Name: shd000_ordenanzas; Type: TABLE DATA; Schema: public; Owner: sisap
--

COPY shd000_ordenanzas (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_ingreso, porcentaje_descuento, porcentaje_multa, porcentaje_recargo, porcentaje_interes) FROM stdin;
\.


--
-- TOC entry 4961 (class 0 OID 502049)
-- Dependencies: 4156
-- Data for Name: shd001_registro_contribuyentes; Type: TABLE DATA; Schema: public; Owner: sisap
--

COPY shd001_registro_contribuyentes (rif_cedula, personalidad_juridica, razon_social_nombres, fecha_inscripcion, nacionalidad, estado_civil, profesion, cod_pais, cod_estado, cod_municipio, cod_parroquia, cod_centro_poblado, cod_calle_avenida, cod_vereda_edificio, numero_vivienda_local, telefonos_fijos, telefonos_celulares, correo_electronico) FROM stdin;
J-89564721-1	2	CARPITERIA SAMÁN VIEJO, C.A.	2009-01-02	3	6	9999	1	4	7	23	2	1	0	GALPÓN 5-A	0247 34 27 512	0414 545 12 05	CARPINVIEJO@HOTMAIL.COM
J-78932165-1	2	PESCADERIA SAN FERNANDO	2009-01-02	3	6	9999	1	4	7	23	20	1	0	LOCAL 5-J PLANTA BAJA	0247 34 28 564	0416 698 65 32	PESCADITO87@YAHOO.COM
J-68945752-1	2	CARNICERIA EL TORO, C.A.	2009-01-02	3	6	9999	1	4	7	23	1	1	0	LOCAL PLANTA BAJA	0247 34 29 511	0412 897 56 55	ELTORO_CARNE@YAHOO.COM
J-63245798-1	2	J & L VISIÓN INTEGRAL, C.A.	2009-01-02	3	6	9999	1	4	7	23	20	1	0	CASA Nº 15-1	0247 34 27 360	0412 769 81 20	JBVARGAS495@YAHOO.COM
7014495	1	JOSÉ BENJAMÍN VARGAS	2009-01-02	1	1	4	1	4	7	23	20	1	0	CASA Nº 15-1	0247 34 27 360	0412 769 81 20	JBVARGAS495@YAHOO.COM
J-07654331-9	2	EMPRESAS MATA RICA, C.A.	2009-01-02	3	6	9999	1	4	7	23	20	2	0	LOCAL B	0247 34 28 580	0414 547 12 05	MATA_RICA@HOTMAIL.COM
J-12385678-1	2	DISTRIBUIDORA MARTINEZ, C.A.	2009-01-02	3	6	9999	1	4	7	23	1	2	0	LOCAL 657	0	0	0
\.


--
-- TOC entry 4962 (class 0 OID 502054)
-- Dependencies: 4157
-- Data for Name: shd002_cobradores; Type: TABLE DATA; Schema: public; Owner: sisap
--

COPY shd002_cobradores (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, rif_ci, personalidad, nombre_razon, fecha_ingreso, recurso_cobro, condicion_actividad) FROM stdin;
1	11	30	11	1	14562859	1	VICTOR VERGARA	2009-01-03	4	1
1	11	30	11	1	8456987	1	JOSE ALBERTO ZAMBRANO	2009-01-03	4	1
1	11	30	11	1	G-2000012-1	1	OFICINA DE RECAUDACIÓN	2009-01-01	1	1
\.


--
-- TOC entry 4963 (class 0 OID 502059)
-- Dependencies: 4158
-- Data for Name: shd002_cobranza_pendiente; Type: TABLE DATA; Schema: public; Owner: sisap
--

COPY shd002_cobranza_pendiente (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, rif_ci, ano, cobranza_pendiente_acumulada, enero, febrero, marzo, abril, mayo, junio, julio, agosto, septiembre, octubre, noviembre, diciembre) FROM stdin;
1	11	30	11	1	14562859	2009	0.00	69.83	70.41	151.30	154.69	164.36	0.00	0.00	0.00	0.00	0.00	0.00	0.00
1	11	30	11	1	8456987	2009	0.00	284.33	456.38	484.20	483.82	545.68	0.00	0.00	0.00	0.00	0.00	0.00	0.00
\.


--
-- TOC entry 4964 (class 0 OID 502064)
-- Dependencies: 4159
-- Data for Name: shd002_cobranza_realizada; Type: TABLE DATA; Schema: public; Owner: sisap
--

COPY shd002_cobranza_realizada (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, rif_ci, ano, cobranza_acumulada, enero, febrero, marzo, abril, mayo, junio, julio, agosto, septiembre, octubre, noviembre, diciembre) FROM stdin;
1	11	30	11	1	8456987	2009	0.00	166.67	0.00	0.00	0.00	0.00	0.00	0.00	0.00	0.00	0.00	0.00	0.00
\.


--
-- TOC entry 4965 (class 0 OID 502069)
-- Dependencies: 4160
-- Data for Name: shd003_codigo_ingresos; Type: TABLE DATA; Schema: public; Owner: sisap
--

COPY shd003_codigo_ingresos (cod_ingreso, denominacion, cod_partida, cod_generica, cod_especifica, cod_subespec, cod_auxiliar) FROM stdin;
2	VEHÍCULOS	301	2	8	0	0
3	PROPAGANDA COMERCIAL	301	2	9	0	0
4	INMUEBLES URBANOS	301	2	5	0	0
5	ASEO DOMICILIARIO	301	3	54	0	0
6	ARRENDAMIENTO DE TIERRAS	301	10	8	2	0
7	CRÉDITOS DE VIVIENDA	310	1	1	0	0
1	PATENTE DE INDUSTRIA Y COMERCIO	301	2	7	0	0
\.


--
-- TOC entry 4966 (class 0 OID 502074)
-- Dependencies: 4161
-- Data for Name: shd100_actividades; Type: TABLE DATA; Schema: public; Owner: sisap
--

COPY shd100_actividades (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_actividad, denominacion_actividad, alicuota, unidades_tributarias, minimo_tributable) FROM stdin;
1	11	30	11	1	A-001	VIVERES	0.00	0.00	0.00
1	11	30	11	1	A-001-001	CARNES	0.00	0.00	0.00
1	11	30	11	1	A-001-001-001	CARNICERIAS	0.80	2.00	92.00
1	11	30	11	1	A-001-002	PESCADO	0.00	0.00	0.00
1	11	30	11	1	A	ALIMENTOS Y BEBIDAS	0.00	0.00	0.00
1	11	30	11	1	B	TECNOLOGÍAS	0.00	0.00	0.00
1	11	30	11	1	B-001	FABRICAS	0.00	0.00	0.00
1	11	30	11	1	A-001-002-001	PESCADERIAS	0.90	3.00	138.00
1	11	30	11	1	B-001-001	FABRICANTES DE SOFTWARE	0.00	0.00	0.00
1	11	30	11	1	B-001-001-001	VENTA DE SOFTWARE	0.95	0.00	2000.00
1	11	30	11	1	B-001-002	FABRICANTE DE MUEBLES	0.00	0.00	0.00
1	11	30	11	1	B-001-002-001	CARPINTERIAS	0.70	0.00	1200.00
1	11	30	11	1	B-001-001-002	FABRICANTES DE COMPUTADORAS	0.95	0.00	3000.00
\.


--
-- TOC entry 4967 (class 0 OID 502083)
-- Dependencies: 4162
-- Data for Name: shd100_articulos; Type: TABLE DATA; Schema: public; Owner: sisap
--

COPY shd100_articulos (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, articulos_patente) FROM stdin;
1	11	30	11	1	<p style="text-align: center;"><meta http-equiv="CONTENT-TYPE" content="text/html; charset=utf-8"> \t<title></title> \t<meta name="GENERATOR" content="OpenOffice.org 3.0  (Linux)"> \t<style type="text/css">\n\t<!--\n\t\t@page { margin: 2cm }\n\t\tTD P { margin-bottom: 0cm }\n\t\tP { margin-bottom: 0.21cm }\n\t-->\n\t</style>  </meta></meta><font style="font-size: 9pt;"><b>Ordenanza de Impuesto Sobre Patente de Industria y Comercio</b></font></p><p align="justify"><span style="font-family: Arial;"><strong><font style="font-size: 9pt;">ARTICULO: 41</font></strong><font style="font-size: 9pt;"> El periodo de cobranza ser&aacute; de treinta (30) d&iacute;as continuos, contados a partir de la fecha que comienza el Trimestre vencido, &eacute;ste se abrir&aacute; un segundo periodo por igual tiempo durante el cual, los contribuyentes deber&aacute;n cancelar sus obligaciones en las oficinas de Administraci&oacute;n de Rentas con un recargo de diez por ciento (10%) sobre el monto adeudado al Municipio.</font></span></p><p align="justify"><span style="font-family: Arial;"><strong><font style="font-size: 9pt;">ARTICULO: 42</font></strong><font style="font-size: 9pt;"> Transcurrido el segundo periodo de pago al que se refiere el Art&iacute;culo anterior, o sea, al comenzar el tercer mes del respectivo trimestre, los contribuyentes que no hayan cancelado sus obligaciones, adem&aacute;s del impuesto y el recargo del Diez por ciento (10%) establecido, deber&aacute;n pagar sobre dicha cantidad, intereses moratorios a la taza del uno por ciento (1%) mensual, y se indicar&aacute; el procedimiento del apremio para el cobro de dichos adeudos, el cual se cesar&aacute; en el momento en que el contribuyente pague la deuda m&aacute;s los gastos de procedimiento.</font></span></p><p align="justify"><span style="font-family: Arial;"><strong><font style="font-size: 9pt;">ARTICULO: 43 </font></strong><font style="font-size: 9pt;">En caso de que el contribuyente pague el impuesto correspondiente a todo ejercicio en el mes de Enero, gozar&aacute; de una rebaja del Diez por ciento (10%) del monto del Impuesto restante exigible a partir del segundo trimestre.</font></span></p>  <p>&nbsp;</p>
\.


--
-- TOC entry 4970 (class 0 OID 502106)
-- Dependencies: 4165
-- Data for Name: shd100_declaracion_actividades; Type: TABLE DATA; Schema: public; Owner: sisap
--

COPY shd100_declaracion_actividades (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, rif_cedula, numero_declaracion, cod_actividad, monto_ingresos, monto_impuesto, alicuota_aplicada) FROM stdin;
1	11	30	11	1	J-07654331-9	000001	B-001-001-002	100000.00	950.00	0.95
\.


--
-- TOC entry 4969 (class 0 OID 502096)
-- Dependencies: 4164
-- Data for Name: shd100_declaracion_ingresos; Type: TABLE DATA; Schema: public; Owner: sisap
--

COPY shd100_declaracion_ingresos (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, rif_cedula, numero_declaracion, periodo_desde, periodo_hasta, capital, numero_empleados, numero_obreros, fecha_declaracion) FROM stdin;
1	11	30	11	1	J-07654331-9	000001	2009-01-05	2009-09-18	250000.00	25	5	2009-09-21
\.


--
-- TOC entry 4968 (class 0 OID 502091)
-- Dependencies: 4163
-- Data for Name: shd100_patente; Type: TABLE DATA; Schema: public; Owner: sisap
--

COPY shd100_patente (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, rif_cedula, numero_solicitud, numero_patente, frecuencia_pago, monto_mensual, pago_todo, suspendido, rif_ci_cobrador, ultimo_ano_facturado, ultimo_mes_facturado, fecha_ultima_decla, ingresos_declarados, ultimo_ejercicio_decla, periodo_desde, periodo_hasta, fecha_patente, numero_expediente) FROM stdin;
1	11	30	11	1	J-07654331-9	7634	6893	1	79.17	2	2	14562859	2009	5	2009-09-21	100000.00	2009	2009-01-05	2009-09-18	2009-01-02	5
1	11	30	11	1	J-78932165-1	8965	54235	1	11.50	2	2	14562859	2009	5	1900-01-01	0.00	0	1900-01-01	1900-01-01	2009-01-02	3
1	11	30	11	1	J-63245798-1	5621	7894	1	166.67	2	2	8456987	2009	5	1900-01-01	0.00	0	1900-01-01	1900-01-01	2009-01-03	1
1	11	30	11	1	J-68945752-1	8954	5789	1	7.67	2	2	8456987	2009	5	1900-01-01	0.00	0	1900-01-01	1900-01-01	2009-01-02	2
1	11	30	11	1	J-89564721-1	3658	564	1	100.00	2	2	8456987	2009	5	1900-01-01	0.00	0	1900-01-01	1900-01-01	2009-01-02	4
\.


--
-- TOC entry 4971 (class 0 OID 502116)
-- Dependencies: 4166
-- Data for Name: shd100_patente_actividades; Type: TABLE DATA; Schema: public; Owner: sisap
--

COPY shd100_patente_actividades (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, rif_cedula, cod_actividad, numero_aforos, monto_aforo_anual, total_aforo_anual) FROM stdin;
1	11	30	11	1	J-63245798-1	B-001-001-001	1	2000.00	2000.00
1	11	30	11	1	J-68945752-1	A-001-001-001	1	92.00	92.00
1	11	30	11	1	J-78932165-1	A-001-002-001	1	138.00	138.00
1	11	30	11	1	J-89564721-1	B-001-002-001	1	1200.00	1200.00
1	11	30	11	1	J-07654331-9	B-001-001-002	1	950.00	950.00
\.


--
-- TOC entry 4972 (class 0 OID 502126)
-- Dependencies: 4167
-- Data for Name: shd100_solicitud; Type: TABLE DATA; Schema: public; Owner: sisap
--

COPY shd100_solicitud (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, numero_solicitud, fecha_solicitud, rif_cedula, numero_ficha_catastral, capital, tipo_establecimiento, tipo_local, nacionalidad, cedula_identidad, nombres_apellidos, cod_pais, cod_estado, cod_municipio, cod_parroquia, cod_centro, cod_vialidad, cod_vereda, numero_casa_local, telefonos_fijos, telefonos_celulares, correo_electronico, fecha_inicio_const, fecha_cierre_const, fecha_inicio_econo, fecha_cierre_economico, registro_mercantil, tiene_sucursal, es_fabricante, numero_empleado, numero_obreros, tilde_reg_mercantil, tilde_fotoco_ci, tilde_acta_const, tilde_uso_conforme, tilde_croquis, tilde_bomberos, tilde_rif, tilde_solvencia, tilde_concejo, tilde_recibo, tilde_planilla, tilde_permiso, numero_patente, categoria_comercial, mercado_cubre, horario_trab_desde, horario_trab_hasta, distancia_bar, distancia_hospital, distancia_educativo, distancia_funeraria, distancia_estacion, distancia_gubernam) FROM stdin;
1	11	30	11	1	5621	2009-01-02	J-63245798-1	0	500000.00	1	1	1	7014495	JOSÉ BENJAMÍN VARGAS	1	4	7	23	20	1	0	CASA Nº 15-1	0247 34 27 360	0412 769 81 20	JBVARGAS495@YAHOO.COM	2009-01-01	2009-12-31	2009-01-01	2009-12-31	REGISTRO MERCANTIL Nº 789 FOLIOS 89 AL 96 1ER. TRIMESTRE	2	1	18	2	1	1	1	1	1	1	1	1	1	1	1	1	7894	1	3	7	19	0.200	0.400	0.100	0.500	0.600	0.700
1	11	30	11	1	8954	2009-01-02	J-68945752-1	0	100000.00	2	1	1	12365897	PEDRO JOSE ALVAREZ	1	4	7	23	1	1	0	CASA Nº 56	0	0	0	2009-01-01	2009-12-31	2009-01-01	2009-12-31	REGISTRO MERCANITL DE SAN FERNANDO, NÚMERO 124 FOLIOS 45 AL 48	2	2	1	3	1	1	1	1	1	1	1	1	1	1	1	1	5789	2	1	8	5	0.600	0.500	0.400	0.300	0.200	0.100
1	11	30	11	1	8965	2009-01-02	J-78932165-1	0	200000.00	1	1	1	8956752	FERNANDO JOSE PEREZ	1	4	7	23	20	1	0	CASANº 18-B	0247 34 27 589	0414 158 15 16	0	2009-01-01	2009-12-31	2009-01-01	2009-12-31	NUMERO Nª 76 FOLIOS 56 AL 61 1ER. TRIMESTRE	2	2	1	4	1	1	1	1	1	1	1	1	1	1	1	1	54235	2	1	7	3	0.100	0.200	0.300	0.400	0.500	0.600
1	11	30	11	1	3658	2009-01-02	J-89564721-1	0	400000.00	1	1	1	8563215	EUGENIO LOPEZ	1	4	7	23	2	1	0	CASA Nº 78	0	0	0	2009-01-01	2009-12-31	2009-01-01	2009-12-31	REGISTRO NÚMERO 15 FOLIOS 45 AL 48 2DO. TRIMESTRE	2	2	1	2	0	0	0	0	0	0	0	0	0	0	0	0	564	1	1	8	15	1.000	2.000	3.000	1.000	1.500	0.700
1	11	30	11	1	7634	2009-01-02	J-07654331-9	0	250000.00	1	1	1	8569875	PEDRO ANTONIO MARTINEZ	1	4	7	23	20	2	0	CASA Nº 67	0	0	0	2009-01-01	2009-12-31	2009-01-01	2009-12-31	REGISTRO Nº 14 FOLIOS 65 AL 68	2	2	25	5	0	0	0	0	0	0	0	0	0	0	0	0	6893	1	1	7	19	0.000	0.000	0.000	0.000	0.000	0.000
1	11	30	11	1	9875	2009-01-02	J-12385678-1	0	900000.00	2	1	1	9865425	RICARDO MARTINEZ	1	4	7	23	1	2	0	LOCAL 20	0	0	0	2009-01-01	2009-12-31	2009-01-01	2009-12-31	REGISTRO Nº 90 FOLIOS DEL 7 AL 12	2	2	1	5	0	0	0	0	0	0	0	0	0	0	0	0	0	1	1	7	16	0.000	0.000	0.000	0.000	0.000	0.000
\.


--
-- TOC entry 4973 (class 0 OID 502134)
-- Dependencies: 4168
-- Data for Name: shd100_solicitud_actividades; Type: TABLE DATA; Schema: public; Owner: sisap
--

COPY shd100_solicitud_actividades (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, numero_solicitud, cod_actividad) FROM stdin;
1	11	30	11	1	8965	A-001-002-001
1	11	30	11	1	8954	A-001-001-001
1	11	30	11	1	5621	B-001-001-001
1	11	30	11	1	3658	B-001-002-001
1	11	30	11	1	7634	B-001-001-002
1	11	30	11	1	9875	A-001-001-001
\.


--
-- TOC entry 4974 (class 0 OID 502139)
-- Dependencies: 4169
-- Data for Name: shd200_vehiculos; Type: TABLE DATA; Schema: public; Owner: sisap
--

COPY shd200_vehiculos (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, rif_cedula, placa_vehiculo, fecha_registro, cod_marca, cod_modelo, cod_color, cod_clase, cod_tipo, cod_uso, serial_carroceria, serial_motor, ano_adquisicion, valor_vehiculo, fecha_adquisicion, cod_clasificacion, frecuencia_pago, monto_mensual, pago_todo, suspendido, rif_ci_cobrador, ultimo_ano_facturado, ultimo_mes_facturado) FROM stdin;
1	11	30	11	1	7014495	ZIE-60T	2009-01-02	2	4	3	2	1	1	7RT7Y47568	7R7YW78W45	2008	60000.00	2009-09-11	01	1	58.33	2	2	14562859	2009	5
1	11	30	11	1	J-63245798-1	GCK-39F	2009-01-02	1	1	1	1	1	1	9T9Y89T9TY89YT	98TY8IUE877	2008	230000.00	2009-03-11	01	1	58.33	2	2	8456987	2009	5
1	11	30	11	1	J-63245798-1	CAB-15T	2009-01-02	3	3	2	2	1	1	DKY768JW88	4U5K5U6J39D	2008	160000.00	2009-05-07	01	1	58.33	2	2	8456987	2009	5
\.


--
-- TOC entry 4975 (class 0 OID 502146)
-- Dependencies: 4171
-- Data for Name: shd200_vehiculos_clases; Type: TABLE DATA; Schema: public; Owner: sisap
--

COPY shd200_vehiculos_clases (codigo_clase, denominacion) FROM stdin;
1	DEPORTIVO
2	SEDAN
\.


--
-- TOC entry 4976 (class 0 OID 502152)
-- Dependencies: 4172
-- Data for Name: shd200_vehiculos_clasificacion; Type: TABLE DATA; Schema: public; Owner: sisap
--

COPY shd200_vehiculos_clasificacion (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_clasificacion, porcentaje, monto_anual, denominacion) FROM stdin;
1	11	30	11	1	01	2.00	700.00	PARTICULAR
1	11	30	11	1	02	3.00	1000.00	CAMIONES
1	11	30	11	1	03	2.50	900.00	CAMIONETAS PICK-UP
\.


--
-- TOC entry 4977 (class 0 OID 502159)
-- Dependencies: 4174
-- Data for Name: shd200_vehiculos_colores; Type: TABLE DATA; Schema: public; Owner: sisap
--

COPY shd200_vehiculos_colores (codigo_color, denominacion) FROM stdin;
1	BLANCO
2	NEGRO
3	AZUL
4	VERDE
5	AMARILLO
\.


--
-- TOC entry 4978 (class 0 OID 502167)
-- Dependencies: 4176
-- Data for Name: shd200_vehiculos_marcas; Type: TABLE DATA; Schema: public; Owner: sisap
--

COPY shd200_vehiculos_marcas (codigo_marca, denominacion) FROM stdin;
1	FORD
2	CHEVROLET
3	TOYOTA
\.


--
-- TOC entry 4979 (class 0 OID 502175)
-- Dependencies: 4178
-- Data for Name: shd200_vehiculos_modelos; Type: TABLE DATA; Schema: public; Owner: sisap
--

COPY shd200_vehiculos_modelos (codigo_modelo, denominacion) FROM stdin;
1	MUSTANG
2	FIESTA
3	COROLLA
4	CORSA
5	MALIBU
\.


--
-- TOC entry 4980 (class 0 OID 502183)
-- Dependencies: 4180
-- Data for Name: shd200_vehiculos_tipos; Type: TABLE DATA; Schema: public; Owner: sisap
--

COPY shd200_vehiculos_tipos (codigo_tipo, denominacion) FROM stdin;
1	PASAJERO
3	CARGA
\.


--
-- TOC entry 4981 (class 0 OID 502191)
-- Dependencies: 4182
-- Data for Name: shd200_vehiculos_usos; Type: TABLE DATA; Schema: public; Owner: sisap
--

COPY shd200_vehiculos_usos (codigo_uso, denominacion) FROM stdin;
1	PARTICULAR
2	TRANSPORTE
\.


--
-- TOC entry 4982 (class 0 OID 502197)
-- Dependencies: 4183
-- Data for Name: shd300_detalles_adicional; Type: TABLE DATA; Schema: public; Owner: sisap
--

COPY shd300_detalles_adicional (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, rif_cedula, cod_tipo, numero, cod_recargo, monto) FROM stdin;
\.


--
-- TOC entry 4983 (class 0 OID 502202)
-- Dependencies: 4184
-- Data for Name: shd300_detalles_propaganda; Type: TABLE DATA; Schema: public; Owner: sisap
--

COPY shd300_detalles_propaganda (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, rif_cedula, cod_tipo, numero, largo, alto, area, espesor, cantidad, monto, monto_adicional, monto_mensual, ubicacion, fecha_registro) FROM stdin;
\.


--
-- TOC entry 4984 (class 0 OID 502210)
-- Dependencies: 4185
-- Data for Name: shd300_propaganda; Type: TABLE DATA; Schema: public; Owner: sisap
--

COPY shd300_propaganda (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, rif_cedula, frecuencia_pago, monto_mensual_general, pago_todo, suspendido, rif_ci_cobrador, ultimo_ano_facturado, ultimo_mes_facturado) FROM stdin;
\.


--
-- TOC entry 4985 (class 0 OID 502215)
-- Dependencies: 4186
-- Data for Name: shd300_recargos; Type: TABLE DATA; Schema: public; Owner: sisap
--

COPY shd300_recargos (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_recargo, denominacion, porcentaje) FROM stdin;
\.


--
-- TOC entry 4986 (class 0 OID 502220)
-- Dependencies: 4187
-- Data for Name: shd300_tipo_propaganda; Type: TABLE DATA; Schema: public; Owner: sisap
--

COPY shd300_tipo_propaganda (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo, denominacion, articulo, monto, tipo_unidad) FROM stdin;
\.


--
-- TOC entry 4987 (class 0 OID 502225)
-- Dependencies: 4188
-- Data for Name: shd400_propiedad; Type: TABLE DATA; Schema: public; Owner: sisap
--

COPY shd400_propiedad (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, rif_cedula, cod_ficha, frecuencia_pago, monto_mensual, pago_todo, suspendido, rif_ci_cobrador, ultimo_ano_facturado, ultimo_mes_facturado) FROM stdin;
\.


--
-- TOC entry 4988 (class 0 OID 502230)
-- Dependencies: 4189
-- Data for Name: shd500_aseo_clasificacion; Type: TABLE DATA; Schema: public; Owner: sisap
--

COPY shd500_aseo_clasificacion (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_clasificacion, denominacion, monto_mensual) FROM stdin;
1	11	30	11	1	1	RECOLECCIÓN	60.00
\.


--
-- TOC entry 4989 (class 0 OID 502235)
-- Dependencies: 4190
-- Data for Name: shd500_aseo_domiciliario; Type: TABLE DATA; Schema: public; Owner: sisap
--

COPY shd500_aseo_domiciliario (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, rif_cedula, cod_clasificacion, frecuencia_pago, fecha_registro, monto_mensual, pago_todo, suspendido, rif_ci_cobrador, ultimo_ano_facturado, ultimo_mes_facturado) FROM stdin;
1	11	30	11	1	7014495	1	1	2009-01-02	60.00	2	2	8456987	2009	5
\.


--
-- TOC entry 5001 (class 0 OID 502898)
-- Dependencies: 4240
-- Data for Name: shd600_aprobacion_arrendamiento; Type: TABLE DATA; Schema: public; Owner: sisap
--

COPY shd600_aprobacion_arrendamiento (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, rif_cedula, numero_solicitud, fecha_aprobacion, frecuencia_pago, datos_registro_arrendamiento, monto_mensual, pago_todo, suspendido, rif_ci_cobrador, ultimo_ano_facturado, ultimo_mes_facturado, terreno_vendido) FROM stdin;
\.


--
-- TOC entry 5002 (class 0 OID 502911)
-- Dependencies: 4241
-- Data for Name: shd600_compra_terreno; Type: TABLE DATA; Schema: public; Owner: sisap
--

COPY shd600_compra_terreno (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, rif_cedula, numero_solicitud, fecha_compra, datos_compra, monto) FROM stdin;
\.


--
-- TOC entry 5000 (class 0 OID 502890)
-- Dependencies: 4239
-- Data for Name: shd600_solicitud_arrendamiento; Type: TABLE DATA; Schema: public; Owner: sisap
--

COPY shd600_solicitud_arrendamiento (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, rif_cedula, numero_solicitud, fecha_solicitud, opcion, cod_ficha, expectativa_construccion) FROM stdin;
\.


--
-- TOC entry 4990 (class 0 OID 502274)
-- Dependencies: 4191
-- Data for Name: shd700_credito_vivienda; Type: TABLE DATA; Schema: public; Owner: sisap
--

COPY shd700_credito_vivienda (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, rif_cedula, numero_solicitud, fecha_solicitud, nombre_conyugue, cedula_conyugue, nombre_empresa, tiempo_empresa, telefonos_empresas, direccion_empresa, grupo_familiar, ingreso_mensual, vivienda_actual, tipo_vivienda, direccion_vivienda_credito, costo_vivienda, monto_cuota_inicial, monto_restante, factor, plazo_anos, numero_cuotas, monto_mensual, numero_contrato, fecha_contrato, frecuencia_pago, pago_todo, suspendido, rif_ci_cobrador, ultimo_ano_facturado, ultimo_mes_facturado, area_construccion, area_terreno, norte, sur, este, oeste, tasa_interes, fecha_entrega_contrato) FROM stdin;
\.


--
-- TOC entry 4991 (class 0 OID 502282)
-- Dependencies: 4192
-- Data for Name: shd700_credito_vivienda_parentesco; Type: TABLE DATA; Schema: public; Owner: sisap
--

COPY shd700_credito_vivienda_parentesco (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, numero_solicitud, rif_cedula, cod_parentesco, nombre_apellido, sexo, fecha_nacimiento) FROM stdin;
\.


--
-- TOC entry 4992 (class 0 OID 502292)
-- Dependencies: 4193
-- Data for Name: shd900_cobranza_acumulada; Type: TABLE DATA; Schema: public; Owner: sisap
--

COPY shd900_cobranza_acumulada (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, ano, mes, dia, cod_partida, cod_generica, cod_especifica, cod_sub_espec, cod_auxiliar, deuda_vigente, deuda_anterior, monto_recargo, monto_multa, monto_intereses, monto_descuento, cantidad_depositos, monto_depositos, cantidad_notas_credito, monto_notas_credito, cantidad_cheques, monto_cheques, cantidad_descuento, cantidad_pagos_efectivo, monto_pagos_efectivo) FROM stdin;
1	11	30	11	1	2009	9	21	301	3	48	0	0	100.00	0.00	0.00	0.00	0.00	0.00	0	0.00	0	0.00	0	0.00	0	1	100.00
1	11	30	11	1	2009	9	21	301	2	7	0	0	166.67	0.00	0.00	0.00	0.00	0.00	0	0.00	0	0.00	0	0.00	0	1	166.67
\.


--
-- TOC entry 4996 (class 0 OID 502318)
-- Dependencies: 4197
-- Data for Name: shd900_cobranza_diaria; Type: TABLE DATA; Schema: public; Owner: sisap
--

COPY shd900_cobranza_diaria (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, ano_comprobante, numero_comprobante, cod_partida, cod_generica, cod_especifica, cod_sub_espec, cod_auxiliar, fecha_comprobante, rif_cedula, concepto_comprobante, deuda_anterior, deuda_vigente, monto_recargo, monto_multa, monto_intereses, monto_descuento, cod_entidad_deposito, cod_sucursal_deposito, cuenta_bancaria_deposito, numero_deposito, monto_deposito, fecha_deposito, cod_entidad_credito, cod_sucursal_credito, cuenta_bancaria_credito, numero_nota_credito, monto_nota_credito, fecha_nota_credito, cod_entidad_cheque, cod_sucursal_cheque, cuenta_bancaria_cheque, numero_cheque, monto_cheque, fecha_cheque, monto_efectivo, condicion_documento, fecha_registro, username_registro, ano_anulacion, numero_anulacion, fecha_anulacion, username_anulacion, rif_ci_cobrador) FROM stdin;
1	11	30	11	1	2009	1	301	3	48	0	0	2009-09-21	J-63245798-1	CANCELACIÓN DE PERMISO MUNICIPAL PARA CONSTRUIR VIVIENDA	0.00	100.00	0.00	0.00	0.00	0.00	0	0	0	0	0.00	1900-01-01	0	0	0	0	0.00	1900-01-01	0	0	0	0	0.00	1900-01-01	100.00	1	2009-09-21	DEMO	0	0	1900-01-01	0	G-2000012-1
1	11	30	11	1	2009	2	301	2	7	0	0	2009-09-21	J-63245798-1	CANCELACIÓN CORRESPONDIENTE:  PLANILLA Nº:000002 MES:ENERO AÑO:2009 	0.00	166.67	0.00	0.00	0.00	0.00	0	0	0	0	0.00	1900-01-01	0	0	0	0	0.00	1900-01-01	0	0	0	0	0.00	1900-01-01	166.67	1	2009-09-21	DEMO	0	0	1900-01-01	0	8456987
\.


--
-- TOC entry 5003 (class 0 OID 502976)
-- Dependencies: 4245
-- Data for Name: shd900_cobranza_diaria_planillas; Type: TABLE DATA; Schema: public; Owner: sisap
--

COPY shd900_cobranza_diaria_planillas (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, ano_comprobante, numero_comprobante, ano, mes, numero_planilla) FROM stdin;
1	11	30	11	1	2009	2	2009	1	2
\.


--
-- TOC entry 4995 (class 0 OID 502313)
-- Dependencies: 4196
-- Data for Name: shd900_cobranza_numero; Type: TABLE DATA; Schema: public; Owner: sisap
--

COPY shd900_cobranza_numero (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, ano_comprobante, numero_comprobante, situacion) FROM stdin;
1	11	30	11	1	2009	4	1
1	11	30	11	1	2009	5	1
1	11	30	11	1	2009	6	1
1	11	30	11	1	2009	7	1
1	11	30	11	1	2009	8	1
1	11	30	11	1	2009	9	1
1	11	30	11	1	2009	10	1
1	11	30	11	1	2009	11	1
1	11	30	11	1	2009	12	1
1	11	30	11	1	2009	13	1
1	11	30	11	1	2009	14	1
1	11	30	11	1	2009	15	1
1	11	30	11	1	2009	16	1
1	11	30	11	1	2009	17	1
1	11	30	11	1	2009	18	1
1	11	30	11	1	2009	19	1
1	11	30	11	1	2009	20	1
1	11	30	11	1	2009	21	1
1	11	30	11	1	2009	22	1
1	11	30	11	1	2009	23	1
1	11	30	11	1	2009	24	1
1	11	30	11	1	2009	25	1
1	11	30	11	1	2009	26	1
1	11	30	11	1	2009	27	1
1	11	30	11	1	2009	28	1
1	11	30	11	1	2009	29	1
1	11	30	11	1	2009	30	1
1	11	30	11	1	2009	31	1
1	11	30	11	1	2009	32	1
1	11	30	11	1	2009	33	1
1	11	30	11	1	2009	34	1
1	11	30	11	1	2009	35	1
1	11	30	11	1	2009	36	1
1	11	30	11	1	2009	37	1
1	11	30	11	1	2009	38	1
1	11	30	11	1	2009	39	1
1	11	30	11	1	2009	40	1
1	11	30	11	1	2009	41	1
1	11	30	11	1	2009	42	1
1	11	30	11	1	2009	43	1
1	11	30	11	1	2009	44	1
1	11	30	11	1	2009	45	1
1	11	30	11	1	2009	46	1
1	11	30	11	1	2009	47	1
1	11	30	11	1	2009	48	1
1	11	30	11	1	2009	49	1
1	11	30	11	1	2009	50	1
1	11	30	11	1	2009	51	1
1	11	30	11	1	2009	52	1
1	11	30	11	1	2009	53	1
1	11	30	11	1	2009	54	1
1	11	30	11	1	2009	55	1
1	11	30	11	1	2009	56	1
1	11	30	11	1	2009	57	1
1	11	30	11	1	2009	58	1
1	11	30	11	1	2009	59	1
1	11	30	11	1	2009	60	1
1	11	30	11	1	2009	61	1
1	11	30	11	1	2009	62	1
1	11	30	11	1	2009	63	1
1	11	30	11	1	2009	64	1
1	11	30	11	1	2009	65	1
1	11	30	11	1	2009	66	1
1	11	30	11	1	2009	67	1
1	11	30	11	1	2009	68	1
1	11	30	11	1	2009	69	1
1	11	30	11	1	2009	70	1
1	11	30	11	1	2009	71	1
1	11	30	11	1	2009	72	1
1	11	30	11	1	2009	73	1
1	11	30	11	1	2009	74	1
1	11	30	11	1	2009	75	1
1	11	30	11	1	2009	76	1
1	11	30	11	1	2009	77	1
1	11	30	11	1	2009	78	1
1	11	30	11	1	2009	79	1
1	11	30	11	1	2009	80	1
1	11	30	11	1	2009	81	1
1	11	30	11	1	2009	82	1
1	11	30	11	1	2009	83	1
1	11	30	11	1	2009	84	1
1	11	30	11	1	2009	85	1
1	11	30	11	1	2009	86	1
1	11	30	11	1	2009	87	1
1	11	30	11	1	2009	88	1
1	11	30	11	1	2009	89	1
1	11	30	11	1	2009	90	1
1	11	30	11	1	2009	91	1
1	11	30	11	1	2009	92	1
1	11	30	11	1	2009	93	1
1	11	30	11	1	2009	94	1
1	11	30	11	1	2009	95	1
1	11	30	11	1	2009	96	1
1	11	30	11	1	2009	97	1
1	11	30	11	1	2009	98	1
1	11	30	11	1	2009	99	1
1	11	30	11	1	2009	100	1
1	11	30	11	1	2009	101	1
1	11	30	11	1	2009	102	1
1	11	30	11	1	2009	103	1
1	11	30	11	1	2009	104	1
1	11	30	11	1	2009	105	1
1	11	30	11	1	2009	106	1
1	11	30	11	1	2009	107	1
1	11	30	11	1	2009	108	1
1	11	30	11	1	2009	109	1
1	11	30	11	1	2009	110	1
1	11	30	11	1	2009	111	1
1	11	30	11	1	2009	112	1
1	11	30	11	1	2009	113	1
1	11	30	11	1	2009	114	1
1	11	30	11	1	2009	115	1
1	11	30	11	1	2009	116	1
1	11	30	11	1	2009	117	1
1	11	30	11	1	2009	118	1
1	11	30	11	1	2009	119	1
1	11	30	11	1	2009	120	1
1	11	30	11	1	2009	121	1
1	11	30	11	1	2009	122	1
1	11	30	11	1	2009	123	1
1	11	30	11	1	2009	124	1
1	11	30	11	1	2009	125	1
1	11	30	11	1	2009	126	1
1	11	30	11	1	2009	127	1
1	11	30	11	1	2009	128	1
1	11	30	11	1	2009	129	1
1	11	30	11	1	2009	130	1
1	11	30	11	1	2009	131	1
1	11	30	11	1	2009	132	1
1	11	30	11	1	2009	133	1
1	11	30	11	1	2009	134	1
1	11	30	11	1	2009	135	1
1	11	30	11	1	2009	136	1
1	11	30	11	1	2009	2	3
1	11	30	11	1	2009	137	1
1	11	30	11	1	2009	138	1
1	11	30	11	1	2009	139	1
1	11	30	11	1	2009	140	1
1	11	30	11	1	2009	141	1
1	11	30	11	1	2009	142	1
1	11	30	11	1	2009	143	1
1	11	30	11	1	2009	144	1
1	11	30	11	1	2009	145	1
1	11	30	11	1	2009	146	1
1	11	30	11	1	2009	147	1
1	11	30	11	1	2009	148	1
1	11	30	11	1	2009	149	1
1	11	30	11	1	2009	150	1
1	11	30	11	1	2009	151	1
1	11	30	11	1	2009	152	1
1	11	30	11	1	2009	153	1
1	11	30	11	1	2009	154	1
1	11	30	11	1	2009	155	1
1	11	30	11	1	2009	156	1
1	11	30	11	1	2009	157	1
1	11	30	11	1	2009	158	1
1	11	30	11	1	2009	159	1
1	11	30	11	1	2009	160	1
1	11	30	11	1	2009	161	1
1	11	30	11	1	2009	162	1
1	11	30	11	1	2009	163	1
1	11	30	11	1	2009	164	1
1	11	30	11	1	2009	165	1
1	11	30	11	1	2009	166	1
1	11	30	11	1	2009	167	1
1	11	30	11	1	2009	168	1
1	11	30	11	1	2009	169	1
1	11	30	11	1	2009	170	1
1	11	30	11	1	2009	171	1
1	11	30	11	1	2009	172	1
1	11	30	11	1	2009	173	1
1	11	30	11	1	2009	174	1
1	11	30	11	1	2009	175	1
1	11	30	11	1	2009	176	1
1	11	30	11	1	2009	177	1
1	11	30	11	1	2009	178	1
1	11	30	11	1	2009	179	1
1	11	30	11	1	2009	180	1
1	11	30	11	1	2009	181	1
1	11	30	11	1	2009	182	1
1	11	30	11	1	2009	183	1
1	11	30	11	1	2009	184	1
1	11	30	11	1	2009	185	1
1	11	30	11	1	2009	186	1
1	11	30	11	1	2009	187	1
1	11	30	11	1	2009	188	1
1	11	30	11	1	2009	189	1
1	11	30	11	1	2009	190	1
1	11	30	11	1	2009	191	1
1	11	30	11	1	2009	192	1
1	11	30	11	1	2009	193	1
1	11	30	11	1	2009	194	1
1	11	30	11	1	2009	195	1
1	11	30	11	1	2009	196	1
1	11	30	11	1	2009	197	1
1	11	30	11	1	2009	198	1
1	11	30	11	1	2009	199	1
1	11	30	11	1	2009	200	1
1	11	30	11	1	2009	201	1
1	11	30	11	1	2009	202	1
1	11	30	11	1	2009	203	1
1	11	30	11	1	2009	204	1
1	11	30	11	1	2009	205	1
1	11	30	11	1	2009	206	1
1	11	30	11	1	2009	207	1
1	11	30	11	1	2009	208	1
1	11	30	11	1	2009	209	1
1	11	30	11	1	2009	210	1
1	11	30	11	1	2009	211	1
1	11	30	11	1	2009	212	1
1	11	30	11	1	2009	213	1
1	11	30	11	1	2009	214	1
1	11	30	11	1	2009	215	1
1	11	30	11	1	2009	216	1
1	11	30	11	1	2009	217	1
1	11	30	11	1	2009	218	1
1	11	30	11	1	2009	219	1
1	11	30	11	1	2009	220	1
1	11	30	11	1	2009	221	1
1	11	30	11	1	2009	222	1
1	11	30	11	1	2009	223	1
1	11	30	11	1	2009	224	1
1	11	30	11	1	2009	225	1
1	11	30	11	1	2009	226	1
1	11	30	11	1	2009	227	1
1	11	30	11	1	2009	228	1
1	11	30	11	1	2009	229	1
1	11	30	11	1	2009	230	1
1	11	30	11	1	2009	231	1
1	11	30	11	1	2009	232	1
1	11	30	11	1	2009	233	1
1	11	30	11	1	2009	234	1
1	11	30	11	1	2009	235	1
1	11	30	11	1	2009	236	1
1	11	30	11	1	2009	237	1
1	11	30	11	1	2009	238	1
1	11	30	11	1	2009	239	1
1	11	30	11	1	2009	240	1
1	11	30	11	1	2009	241	1
1	11	30	11	1	2009	242	1
1	11	30	11	1	2009	243	1
1	11	30	11	1	2009	244	1
1	11	30	11	1	2009	245	1
1	11	30	11	1	2009	246	1
1	11	30	11	1	2009	247	1
1	11	30	11	1	2009	248	1
1	11	30	11	1	2009	249	1
1	11	30	11	1	2009	250	1
1	11	30	11	1	2009	251	1
1	11	30	11	1	2009	252	1
1	11	30	11	1	2009	253	1
1	11	30	11	1	2009	254	1
1	11	30	11	1	2009	255	1
1	11	30	11	1	2009	256	1
1	11	30	11	1	2009	257	1
1	11	30	11	1	2009	258	1
1	11	30	11	1	2009	259	1
1	11	30	11	1	2009	260	1
1	11	30	11	1	2009	261	1
1	11	30	11	1	2009	262	1
1	11	30	11	1	2009	263	1
1	11	30	11	1	2009	264	1
1	11	30	11	1	2009	265	1
1	11	30	11	1	2009	266	1
1	11	30	11	1	2009	267	1
1	11	30	11	1	2009	268	1
1	11	30	11	1	2009	269	1
1	11	30	11	1	2009	270	1
1	11	30	11	1	2009	271	1
1	11	30	11	1	2009	272	1
1	11	30	11	1	2009	273	1
1	11	30	11	1	2009	274	1
1	11	30	11	1	2009	275	1
1	11	30	11	1	2009	276	1
1	11	30	11	1	2009	277	1
1	11	30	11	1	2009	278	1
1	11	30	11	1	2009	279	1
1	11	30	11	1	2009	280	1
1	11	30	11	1	2009	281	1
1	11	30	11	1	2009	282	1
1	11	30	11	1	2009	283	1
1	11	30	11	1	2009	284	1
1	11	30	11	1	2009	285	1
1	11	30	11	1	2009	286	1
1	11	30	11	1	2009	287	1
1	11	30	11	1	2009	288	1
1	11	30	11	1	2009	289	1
1	11	30	11	1	2009	290	1
1	11	30	11	1	2009	291	1
1	11	30	11	1	2009	292	1
1	11	30	11	1	2009	293	1
1	11	30	11	1	2009	294	1
1	11	30	11	1	2009	295	1
1	11	30	11	1	2009	296	1
1	11	30	11	1	2009	297	1
1	11	30	11	1	2009	298	1
1	11	30	11	1	2009	299	1
1	11	30	11	1	2009	300	1
1	11	30	11	1	2009	301	1
1	11	30	11	1	2009	302	1
1	11	30	11	1	2009	303	1
1	11	30	11	1	2009	304	1
1	11	30	11	1	2009	305	1
1	11	30	11	1	2009	306	1
1	11	30	11	1	2009	307	1
1	11	30	11	1	2009	308	1
1	11	30	11	1	2009	309	1
1	11	30	11	1	2009	310	1
1	11	30	11	1	2009	311	1
1	11	30	11	1	2009	312	1
1	11	30	11	1	2009	313	1
1	11	30	11	1	2009	314	1
1	11	30	11	1	2009	315	1
1	11	30	11	1	2009	316	1
1	11	30	11	1	2009	317	1
1	11	30	11	1	2009	318	1
1	11	30	11	1	2009	319	1
1	11	30	11	1	2009	320	1
1	11	30	11	1	2009	321	1
1	11	30	11	1	2009	322	1
1	11	30	11	1	2009	323	1
1	11	30	11	1	2009	324	1
1	11	30	11	1	2009	325	1
1	11	30	11	1	2009	326	1
1	11	30	11	1	2009	327	1
1	11	30	11	1	2009	328	1
1	11	30	11	1	2009	329	1
1	11	30	11	1	2009	330	1
1	11	30	11	1	2009	331	1
1	11	30	11	1	2009	332	1
1	11	30	11	1	2009	333	1
1	11	30	11	1	2009	334	1
1	11	30	11	1	2009	335	1
1	11	30	11	1	2009	336	1
1	11	30	11	1	2009	337	1
1	11	30	11	1	2009	338	1
1	11	30	11	1	2009	339	1
1	11	30	11	1	2009	340	1
1	11	30	11	1	2009	341	1
1	11	30	11	1	2009	342	1
1	11	30	11	1	2009	343	1
1	11	30	11	1	2009	344	1
1	11	30	11	1	2009	345	1
1	11	30	11	1	2009	346	1
1	11	30	11	1	2009	347	1
1	11	30	11	1	2009	348	1
1	11	30	11	1	2009	349	1
1	11	30	11	1	2009	350	1
1	11	30	11	1	2009	351	1
1	11	30	11	1	2009	352	1
1	11	30	11	1	2009	353	1
1	11	30	11	1	2009	354	1
1	11	30	11	1	2009	355	1
1	11	30	11	1	2009	356	1
1	11	30	11	1	2009	357	1
1	11	30	11	1	2009	358	1
1	11	30	11	1	2009	359	1
1	11	30	11	1	2009	360	1
1	11	30	11	1	2009	361	1
1	11	30	11	1	2009	362	1
1	11	30	11	1	2009	363	1
1	11	30	11	1	2009	364	1
1	11	30	11	1	2009	365	1
1	11	30	11	1	2009	366	1
1	11	30	11	1	2009	367	1
1	11	30	11	1	2009	368	1
1	11	30	11	1	2009	369	1
1	11	30	11	1	2009	370	1
1	11	30	11	1	2009	371	1
1	11	30	11	1	2009	372	1
1	11	30	11	1	2009	373	1
1	11	30	11	1	2009	374	1
1	11	30	11	1	2009	375	1
1	11	30	11	1	2009	376	1
1	11	30	11	1	2009	377	1
1	11	30	11	1	2009	378	1
1	11	30	11	1	2009	379	1
1	11	30	11	1	2009	380	1
1	11	30	11	1	2009	381	1
1	11	30	11	1	2009	382	1
1	11	30	11	1	2009	383	1
1	11	30	11	1	2009	384	1
1	11	30	11	1	2009	385	1
1	11	30	11	1	2009	386	1
1	11	30	11	1	2009	387	1
1	11	30	11	1	2009	388	1
1	11	30	11	1	2009	389	1
1	11	30	11	1	2009	390	1
1	11	30	11	1	2009	391	1
1	11	30	11	1	2009	392	1
1	11	30	11	1	2009	393	1
1	11	30	11	1	2009	394	1
1	11	30	11	1	2009	395	1
1	11	30	11	1	2009	396	1
1	11	30	11	1	2009	397	1
1	11	30	11	1	2009	398	1
1	11	30	11	1	2009	399	1
1	11	30	11	1	2009	400	1
1	11	30	11	1	2009	401	1
1	11	30	11	1	2009	402	1
1	11	30	11	1	2009	403	1
1	11	30	11	1	2009	404	1
1	11	30	11	1	2009	405	1
1	11	30	11	1	2009	406	1
1	11	30	11	1	2009	407	1
1	11	30	11	1	2009	408	1
1	11	30	11	1	2009	409	1
1	11	30	11	1	2009	410	1
1	11	30	11	1	2009	411	1
1	11	30	11	1	2009	412	1
1	11	30	11	1	2009	413	1
1	11	30	11	1	2009	414	1
1	11	30	11	1	2009	415	1
1	11	30	11	1	2009	416	1
1	11	30	11	1	2009	417	1
1	11	30	11	1	2009	418	1
1	11	30	11	1	2009	419	1
1	11	30	11	1	2009	420	1
1	11	30	11	1	2009	421	1
1	11	30	11	1	2009	422	1
1	11	30	11	1	2009	423	1
1	11	30	11	1	2009	424	1
1	11	30	11	1	2009	425	1
1	11	30	11	1	2009	426	1
1	11	30	11	1	2009	427	1
1	11	30	11	1	2009	428	1
1	11	30	11	1	2009	429	1
1	11	30	11	1	2009	430	1
1	11	30	11	1	2009	431	1
1	11	30	11	1	2009	432	1
1	11	30	11	1	2009	433	1
1	11	30	11	1	2009	434	1
1	11	30	11	1	2009	435	1
1	11	30	11	1	2009	436	1
1	11	30	11	1	2009	437	1
1	11	30	11	1	2009	438	1
1	11	30	11	1	2009	439	1
1	11	30	11	1	2009	440	1
1	11	30	11	1	2009	441	1
1	11	30	11	1	2009	442	1
1	11	30	11	1	2009	443	1
1	11	30	11	1	2009	444	1
1	11	30	11	1	2009	445	1
1	11	30	11	1	2009	446	1
1	11	30	11	1	2009	447	1
1	11	30	11	1	2009	448	1
1	11	30	11	1	2009	449	1
1	11	30	11	1	2009	450	1
1	11	30	11	1	2009	451	1
1	11	30	11	1	2009	452	1
1	11	30	11	1	2009	453	1
1	11	30	11	1	2009	454	1
1	11	30	11	1	2009	455	1
1	11	30	11	1	2009	456	1
1	11	30	11	1	2009	457	1
1	11	30	11	1	2009	458	1
1	11	30	11	1	2009	459	1
1	11	30	11	1	2009	460	1
1	11	30	11	1	2009	461	1
1	11	30	11	1	2009	462	1
1	11	30	11	1	2009	463	1
1	11	30	11	1	2009	464	1
1	11	30	11	1	2009	465	1
1	11	30	11	1	2009	466	1
1	11	30	11	1	2009	467	1
1	11	30	11	1	2009	468	1
1	11	30	11	1	2009	469	1
1	11	30	11	1	2009	470	1
1	11	30	11	1	2009	471	1
1	11	30	11	1	2009	472	1
1	11	30	11	1	2009	473	1
1	11	30	11	1	2009	474	1
1	11	30	11	1	2009	475	1
1	11	30	11	1	2009	476	1
1	11	30	11	1	2009	477	1
1	11	30	11	1	2009	478	1
1	11	30	11	1	2009	479	1
1	11	30	11	1	2009	480	1
1	11	30	11	1	2009	481	1
1	11	30	11	1	2009	482	1
1	11	30	11	1	2009	483	1
1	11	30	11	1	2009	484	1
1	11	30	11	1	2009	485	1
1	11	30	11	1	2009	486	1
1	11	30	11	1	2009	487	1
1	11	30	11	1	2009	488	1
1	11	30	11	1	2009	489	1
1	11	30	11	1	2009	490	1
1	11	30	11	1	2009	491	1
1	11	30	11	1	2009	492	1
1	11	30	11	1	2009	493	1
1	11	30	11	1	2009	494	1
1	11	30	11	1	2009	495	1
1	11	30	11	1	2009	496	1
1	11	30	11	1	2009	497	1
1	11	30	11	1	2009	498	1
1	11	30	11	1	2009	499	1
1	11	30	11	1	2009	500	1
1	11	30	11	1	2009	501	1
1	11	30	11	1	2009	502	1
1	11	30	11	1	2009	503	1
1	11	30	11	1	2009	504	1
1	11	30	11	1	2009	505	1
1	11	30	11	1	2009	506	1
1	11	30	11	1	2009	507	1
1	11	30	11	1	2009	508	1
1	11	30	11	1	2009	509	1
1	11	30	11	1	2009	510	1
1	11	30	11	1	2009	511	1
1	11	30	11	1	2009	512	1
1	11	30	11	1	2009	513	1
1	11	30	11	1	2009	514	1
1	11	30	11	1	2009	515	1
1	11	30	11	1	2009	516	1
1	11	30	11	1	2009	517	1
1	11	30	11	1	2009	518	1
1	11	30	11	1	2009	519	1
1	11	30	11	1	2009	520	1
1	11	30	11	1	2009	521	1
1	11	30	11	1	2009	522	1
1	11	30	11	1	2009	523	1
1	11	30	11	1	2009	524	1
1	11	30	11	1	2009	525	1
1	11	30	11	1	2009	526	1
1	11	30	11	1	2009	527	1
1	11	30	11	1	2009	528	1
1	11	30	11	1	2009	529	1
1	11	30	11	1	2009	530	1
1	11	30	11	1	2009	531	1
1	11	30	11	1	2009	532	1
1	11	30	11	1	2009	533	1
1	11	30	11	1	2009	534	1
1	11	30	11	1	2009	535	1
1	11	30	11	1	2009	536	1
1	11	30	11	1	2009	537	1
1	11	30	11	1	2009	538	1
1	11	30	11	1	2009	539	1
1	11	30	11	1	2009	540	1
1	11	30	11	1	2009	541	1
1	11	30	11	1	2009	542	1
1	11	30	11	1	2009	543	1
1	11	30	11	1	2009	544	1
1	11	30	11	1	2009	545	1
1	11	30	11	1	2009	546	1
1	11	30	11	1	2009	547	1
1	11	30	11	1	2009	548	1
1	11	30	11	1	2009	549	1
1	11	30	11	1	2009	550	1
1	11	30	11	1	2009	551	1
1	11	30	11	1	2009	552	1
1	11	30	11	1	2009	553	1
1	11	30	11	1	2009	554	1
1	11	30	11	1	2009	555	1
1	11	30	11	1	2009	556	1
1	11	30	11	1	2009	557	1
1	11	30	11	1	2009	558	1
1	11	30	11	1	2009	559	1
1	11	30	11	1	2009	560	1
1	11	30	11	1	2009	561	1
1	11	30	11	1	2009	562	1
1	11	30	11	1	2009	563	1
1	11	30	11	1	2009	564	1
1	11	30	11	1	2009	565	1
1	11	30	11	1	2009	566	1
1	11	30	11	1	2009	567	1
1	11	30	11	1	2009	568	1
1	11	30	11	1	2009	569	1
1	11	30	11	1	2009	570	1
1	11	30	11	1	2009	571	1
1	11	30	11	1	2009	572	1
1	11	30	11	1	2009	573	1
1	11	30	11	1	2009	574	1
1	11	30	11	1	2009	575	1
1	11	30	11	1	2009	576	1
1	11	30	11	1	2009	577	1
1	11	30	11	1	2009	578	1
1	11	30	11	1	2009	579	1
1	11	30	11	1	2009	580	1
1	11	30	11	1	2009	581	1
1	11	30	11	1	2009	582	1
1	11	30	11	1	2009	583	1
1	11	30	11	1	2009	584	1
1	11	30	11	1	2009	585	1
1	11	30	11	1	2009	586	1
1	11	30	11	1	2009	587	1
1	11	30	11	1	2009	588	1
1	11	30	11	1	2009	589	1
1	11	30	11	1	2009	590	1
1	11	30	11	1	2009	591	1
1	11	30	11	1	2009	592	1
1	11	30	11	1	2009	593	1
1	11	30	11	1	2009	594	1
1	11	30	11	1	2009	595	1
1	11	30	11	1	2009	596	1
1	11	30	11	1	2009	597	1
1	11	30	11	1	2009	598	1
1	11	30	11	1	2009	599	1
1	11	30	11	1	2009	600	1
1	11	30	11	1	2009	601	1
1	11	30	11	1	2009	602	1
1	11	30	11	1	2009	603	1
1	11	30	11	1	2009	604	1
1	11	30	11	1	2009	605	1
1	11	30	11	1	2009	606	1
1	11	30	11	1	2009	607	1
1	11	30	11	1	2009	608	1
1	11	30	11	1	2009	609	1
1	11	30	11	1	2009	610	1
1	11	30	11	1	2009	611	1
1	11	30	11	1	2009	612	1
1	11	30	11	1	2009	613	1
1	11	30	11	1	2009	614	1
1	11	30	11	1	2009	615	1
1	11	30	11	1	2009	616	1
1	11	30	11	1	2009	617	1
1	11	30	11	1	2009	618	1
1	11	30	11	1	2009	619	1
1	11	30	11	1	2009	620	1
1	11	30	11	1	2009	621	1
1	11	30	11	1	2009	622	1
1	11	30	11	1	2009	623	1
1	11	30	11	1	2009	624	1
1	11	30	11	1	2009	625	1
1	11	30	11	1	2009	626	1
1	11	30	11	1	2009	627	1
1	11	30	11	1	2009	628	1
1	11	30	11	1	2009	629	1
1	11	30	11	1	2009	630	1
1	11	30	11	1	2009	631	1
1	11	30	11	1	2009	632	1
1	11	30	11	1	2009	633	1
1	11	30	11	1	2009	634	1
1	11	30	11	1	2009	635	1
1	11	30	11	1	2009	636	1
1	11	30	11	1	2009	637	1
1	11	30	11	1	2009	638	1
1	11	30	11	1	2009	639	1
1	11	30	11	1	2009	640	1
1	11	30	11	1	2009	641	1
1	11	30	11	1	2009	642	1
1	11	30	11	1	2009	643	1
1	11	30	11	1	2009	644	1
1	11	30	11	1	2009	645	1
1	11	30	11	1	2009	646	1
1	11	30	11	1	2009	647	1
1	11	30	11	1	2009	648	1
1	11	30	11	1	2009	649	1
1	11	30	11	1	2009	650	1
1	11	30	11	1	2009	651	1
1	11	30	11	1	2009	652	1
1	11	30	11	1	2009	653	1
1	11	30	11	1	2009	654	1
1	11	30	11	1	2009	655	1
1	11	30	11	1	2009	656	1
1	11	30	11	1	2009	657	1
1	11	30	11	1	2009	658	1
1	11	30	11	1	2009	659	1
1	11	30	11	1	2009	660	1
1	11	30	11	1	2009	661	1
1	11	30	11	1	2009	662	1
1	11	30	11	1	2009	663	1
1	11	30	11	1	2009	664	1
1	11	30	11	1	2009	665	1
1	11	30	11	1	2009	666	1
1	11	30	11	1	2009	667	1
1	11	30	11	1	2009	668	1
1	11	30	11	1	2009	669	1
1	11	30	11	1	2009	670	1
1	11	30	11	1	2009	671	1
1	11	30	11	1	2009	672	1
1	11	30	11	1	2009	673	1
1	11	30	11	1	2009	674	1
1	11	30	11	1	2009	675	1
1	11	30	11	1	2009	676	1
1	11	30	11	1	2009	677	1
1	11	30	11	1	2009	678	1
1	11	30	11	1	2009	679	1
1	11	30	11	1	2009	680	1
1	11	30	11	1	2009	681	1
1	11	30	11	1	2009	682	1
1	11	30	11	1	2009	683	1
1	11	30	11	1	2009	684	1
1	11	30	11	1	2009	685	1
1	11	30	11	1	2009	686	1
1	11	30	11	1	2009	687	1
1	11	30	11	1	2009	688	1
1	11	30	11	1	2009	689	1
1	11	30	11	1	2009	690	1
1	11	30	11	1	2009	691	1
1	11	30	11	1	2009	692	1
1	11	30	11	1	2009	693	1
1	11	30	11	1	2009	694	1
1	11	30	11	1	2009	695	1
1	11	30	11	1	2009	696	1
1	11	30	11	1	2009	697	1
1	11	30	11	1	2009	698	1
1	11	30	11	1	2009	699	1
1	11	30	11	1	2009	700	1
1	11	30	11	1	2009	701	1
1	11	30	11	1	2009	702	1
1	11	30	11	1	2009	703	1
1	11	30	11	1	2009	704	1
1	11	30	11	1	2009	705	1
1	11	30	11	1	2009	706	1
1	11	30	11	1	2009	707	1
1	11	30	11	1	2009	708	1
1	11	30	11	1	2009	709	1
1	11	30	11	1	2009	710	1
1	11	30	11	1	2009	711	1
1	11	30	11	1	2009	712	1
1	11	30	11	1	2009	713	1
1	11	30	11	1	2009	714	1
1	11	30	11	1	2009	715	1
1	11	30	11	1	2009	716	1
1	11	30	11	1	2009	717	1
1	11	30	11	1	2009	718	1
1	11	30	11	1	2009	719	1
1	11	30	11	1	2009	720	1
1	11	30	11	1	2009	721	1
1	11	30	11	1	2009	722	1
1	11	30	11	1	2009	723	1
1	11	30	11	1	2009	724	1
1	11	30	11	1	2009	725	1
1	11	30	11	1	2009	726	1
1	11	30	11	1	2009	727	1
1	11	30	11	1	2009	728	1
1	11	30	11	1	2009	729	1
1	11	30	11	1	2009	730	1
1	11	30	11	1	2009	731	1
1	11	30	11	1	2009	732	1
1	11	30	11	1	2009	733	1
1	11	30	11	1	2009	734	1
1	11	30	11	1	2009	735	1
1	11	30	11	1	2009	736	1
1	11	30	11	1	2009	737	1
1	11	30	11	1	2009	738	1
1	11	30	11	1	2009	739	1
1	11	30	11	1	2009	740	1
1	11	30	11	1	2009	741	1
1	11	30	11	1	2009	742	1
1	11	30	11	1	2009	743	1
1	11	30	11	1	2009	744	1
1	11	30	11	1	2009	745	1
1	11	30	11	1	2009	746	1
1	11	30	11	1	2009	747	1
1	11	30	11	1	2009	748	1
1	11	30	11	1	2009	749	1
1	11	30	11	1	2009	750	1
1	11	30	11	1	2009	751	1
1	11	30	11	1	2009	752	1
1	11	30	11	1	2009	753	1
1	11	30	11	1	2009	754	1
1	11	30	11	1	2009	755	1
1	11	30	11	1	2009	756	1
1	11	30	11	1	2009	757	1
1	11	30	11	1	2009	758	1
1	11	30	11	1	2009	759	1
1	11	30	11	1	2009	760	1
1	11	30	11	1	2009	761	1
1	11	30	11	1	2009	762	1
1	11	30	11	1	2009	763	1
1	11	30	11	1	2009	764	1
1	11	30	11	1	2009	765	1
1	11	30	11	1	2009	766	1
1	11	30	11	1	2009	767	1
1	11	30	11	1	2009	768	1
1	11	30	11	1	2009	769	1
1	11	30	11	1	2009	770	1
1	11	30	11	1	2009	771	1
1	11	30	11	1	2009	772	1
1	11	30	11	1	2009	773	1
1	11	30	11	1	2009	774	1
1	11	30	11	1	2009	775	1
1	11	30	11	1	2009	776	1
1	11	30	11	1	2009	777	1
1	11	30	11	1	2009	778	1
1	11	30	11	1	2009	779	1
1	11	30	11	1	2009	780	1
1	11	30	11	1	2009	781	1
1	11	30	11	1	2009	782	1
1	11	30	11	1	2009	783	1
1	11	30	11	1	2009	784	1
1	11	30	11	1	2009	785	1
1	11	30	11	1	2009	786	1
1	11	30	11	1	2009	787	1
1	11	30	11	1	2009	788	1
1	11	30	11	1	2009	789	1
1	11	30	11	1	2009	790	1
1	11	30	11	1	2009	791	1
1	11	30	11	1	2009	792	1
1	11	30	11	1	2009	793	1
1	11	30	11	1	2009	794	1
1	11	30	11	1	2009	795	1
1	11	30	11	1	2009	796	1
1	11	30	11	1	2009	797	1
1	11	30	11	1	2009	798	1
1	11	30	11	1	2009	799	1
1	11	30	11	1	2009	800	1
1	11	30	11	1	2009	801	1
1	11	30	11	1	2009	802	1
1	11	30	11	1	2009	803	1
1	11	30	11	1	2009	804	1
1	11	30	11	1	2009	805	1
1	11	30	11	1	2009	806	1
1	11	30	11	1	2009	807	1
1	11	30	11	1	2009	808	1
1	11	30	11	1	2009	809	1
1	11	30	11	1	2009	810	1
1	11	30	11	1	2009	811	1
1	11	30	11	1	2009	812	1
1	11	30	11	1	2009	813	1
1	11	30	11	1	2009	814	1
1	11	30	11	1	2009	815	1
1	11	30	11	1	2009	816	1
1	11	30	11	1	2009	817	1
1	11	30	11	1	2009	818	1
1	11	30	11	1	2009	819	1
1	11	30	11	1	2009	820	1
1	11	30	11	1	2009	821	1
1	11	30	11	1	2009	822	1
1	11	30	11	1	2009	823	1
1	11	30	11	1	2009	824	1
1	11	30	11	1	2009	825	1
1	11	30	11	1	2009	826	1
1	11	30	11	1	2009	827	1
1	11	30	11	1	2009	828	1
1	11	30	11	1	2009	829	1
1	11	30	11	1	2009	830	1
1	11	30	11	1	2009	831	1
1	11	30	11	1	2009	832	1
1	11	30	11	1	2009	833	1
1	11	30	11	1	2009	834	1
1	11	30	11	1	2009	835	1
1	11	30	11	1	2009	836	1
1	11	30	11	1	2009	837	1
1	11	30	11	1	2009	838	1
1	11	30	11	1	2009	839	1
1	11	30	11	1	2009	840	1
1	11	30	11	1	2009	841	1
1	11	30	11	1	2009	842	1
1	11	30	11	1	2009	843	1
1	11	30	11	1	2009	844	1
1	11	30	11	1	2009	845	1
1	11	30	11	1	2009	846	1
1	11	30	11	1	2009	847	1
1	11	30	11	1	2009	848	1
1	11	30	11	1	2009	849	1
1	11	30	11	1	2009	850	1
1	11	30	11	1	2009	851	1
1	11	30	11	1	2009	852	1
1	11	30	11	1	2009	853	1
1	11	30	11	1	2009	854	1
1	11	30	11	1	2009	855	1
1	11	30	11	1	2009	856	1
1	11	30	11	1	2009	857	1
1	11	30	11	1	2009	858	1
1	11	30	11	1	2009	859	1
1	11	30	11	1	2009	860	1
1	11	30	11	1	2009	861	1
1	11	30	11	1	2009	862	1
1	11	30	11	1	2009	863	1
1	11	30	11	1	2009	864	1
1	11	30	11	1	2009	865	1
1	11	30	11	1	2009	866	1
1	11	30	11	1	2009	867	1
1	11	30	11	1	2009	868	1
1	11	30	11	1	2009	869	1
1	11	30	11	1	2009	870	1
1	11	30	11	1	2009	871	1
1	11	30	11	1	2009	872	1
1	11	30	11	1	2009	873	1
1	11	30	11	1	2009	874	1
1	11	30	11	1	2009	875	1
1	11	30	11	1	2009	876	1
1	11	30	11	1	2009	877	1
1	11	30	11	1	2009	878	1
1	11	30	11	1	2009	879	1
1	11	30	11	1	2009	880	1
1	11	30	11	1	2009	881	1
1	11	30	11	1	2009	882	1
1	11	30	11	1	2009	883	1
1	11	30	11	1	2009	884	1
1	11	30	11	1	2009	885	1
1	11	30	11	1	2009	886	1
1	11	30	11	1	2009	887	1
1	11	30	11	1	2009	888	1
1	11	30	11	1	2009	889	1
1	11	30	11	1	2009	890	1
1	11	30	11	1	2009	891	1
1	11	30	11	1	2009	892	1
1	11	30	11	1	2009	893	1
1	11	30	11	1	2009	894	1
1	11	30	11	1	2009	895	1
1	11	30	11	1	2009	896	1
1	11	30	11	1	2009	897	1
1	11	30	11	1	2009	898	1
1	11	30	11	1	2009	899	1
1	11	30	11	1	2009	900	1
1	11	30	11	1	2009	901	1
1	11	30	11	1	2009	902	1
1	11	30	11	1	2009	903	1
1	11	30	11	1	2009	904	1
1	11	30	11	1	2009	905	1
1	11	30	11	1	2009	906	1
1	11	30	11	1	2009	907	1
1	11	30	11	1	2009	908	1
1	11	30	11	1	2009	909	1
1	11	30	11	1	2009	910	1
1	11	30	11	1	2009	911	1
1	11	30	11	1	2009	912	1
1	11	30	11	1	2009	913	1
1	11	30	11	1	2009	914	1
1	11	30	11	1	2009	915	1
1	11	30	11	1	2009	916	1
1	11	30	11	1	2009	917	1
1	11	30	11	1	2009	918	1
1	11	30	11	1	2009	919	1
1	11	30	11	1	2009	920	1
1	11	30	11	1	2009	921	1
1	11	30	11	1	2009	922	1
1	11	30	11	1	2009	923	1
1	11	30	11	1	2009	924	1
1	11	30	11	1	2009	925	1
1	11	30	11	1	2009	926	1
1	11	30	11	1	2009	927	1
1	11	30	11	1	2009	928	1
1	11	30	11	1	2009	929	1
1	11	30	11	1	2009	930	1
1	11	30	11	1	2009	931	1
1	11	30	11	1	2009	932	1
1	11	30	11	1	2009	933	1
1	11	30	11	1	2009	934	1
1	11	30	11	1	2009	935	1
1	11	30	11	1	2009	936	1
1	11	30	11	1	2009	937	1
1	11	30	11	1	2009	938	1
1	11	30	11	1	2009	939	1
1	11	30	11	1	2009	940	1
1	11	30	11	1	2009	941	1
1	11	30	11	1	2009	942	1
1	11	30	11	1	2009	943	1
1	11	30	11	1	2009	944	1
1	11	30	11	1	2009	945	1
1	11	30	11	1	2009	946	1
1	11	30	11	1	2009	947	1
1	11	30	11	1	2009	948	1
1	11	30	11	1	2009	949	1
1	11	30	11	1	2009	950	1
1	11	30	11	1	2009	951	1
1	11	30	11	1	2009	952	1
1	11	30	11	1	2009	953	1
1	11	30	11	1	2009	954	1
1	11	30	11	1	2009	955	1
1	11	30	11	1	2009	956	1
1	11	30	11	1	2009	957	1
1	11	30	11	1	2009	958	1
1	11	30	11	1	2009	959	1
1	11	30	11	1	2009	960	1
1	11	30	11	1	2009	961	1
1	11	30	11	1	2009	962	1
1	11	30	11	1	2009	963	1
1	11	30	11	1	2009	964	1
1	11	30	11	1	2009	965	1
1	11	30	11	1	2009	966	1
1	11	30	11	1	2009	967	1
1	11	30	11	1	2009	968	1
1	11	30	11	1	2009	969	1
1	11	30	11	1	2009	970	1
1	11	30	11	1	2009	971	1
1	11	30	11	1	2009	972	1
1	11	30	11	1	2009	973	1
1	11	30	11	1	2009	974	1
1	11	30	11	1	2009	975	1
1	11	30	11	1	2009	976	1
1	11	30	11	1	2009	977	1
1	11	30	11	1	2009	978	1
1	11	30	11	1	2009	979	1
1	11	30	11	1	2009	980	1
1	11	30	11	1	2009	981	1
1	11	30	11	1	2009	982	1
1	11	30	11	1	2009	983	1
1	11	30	11	1	2009	984	1
1	11	30	11	1	2009	985	1
1	11	30	11	1	2009	986	1
1	11	30	11	1	2009	987	1
1	11	30	11	1	2009	988	1
1	11	30	11	1	2009	989	1
1	11	30	11	1	2009	990	1
1	11	30	11	1	2009	991	1
1	11	30	11	1	2009	992	1
1	11	30	11	1	2009	993	1
1	11	30	11	1	2009	994	1
1	11	30	11	1	2009	995	1
1	11	30	11	1	2009	996	1
1	11	30	11	1	2009	997	1
1	11	30	11	1	2009	998	1
1	11	30	11	1	2009	999	1
1	11	30	11	1	2009	1000	1
1	11	30	11	1	2009	1	3
1	11	30	11	1	2009	3	1
\.


--
-- TOC entry 4993 (class 0 OID 502297)
-- Dependencies: 4194
-- Data for Name: shd900_planillas_deuda_cobro_cuerpo; Type: TABLE DATA; Schema: public; Owner: sisap
--

COPY shd900_planillas_deuda_cobro_cuerpo (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_partida, cod_generica, cod_especifica, cod_sub_espec, cod_auxiliar, rif_cedula, cod_numero_catastral_placas, deuda_ano_anterior) FROM stdin;
\.


--
-- TOC entry 4994 (class 0 OID 502302)
-- Dependencies: 4195
-- Data for Name: shd900_planillas_deuda_cobro_detalles; Type: TABLE DATA; Schema: public; Owner: sisap
--

COPY shd900_planillas_deuda_cobro_detalles (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_partida, cod_generica, cod_especifica, cod_sub_espec, cod_auxiliar, rif_cedula, cod_numero_catastral_placas, ano, mes, numero_planilla, deuda_vigente, monto_recargo, monto_multa, monto_intereses, monto_descuento, cancelado, fecha_emision) FROM stdin;
1	11	30	11	1	301	2	7	0	0	J-78932165-1	0	2009	1	1	11.50	0.00	0.00	0.00	0.00	2	2009-09-21
1	11	30	11	1	301	2	7	0	0	J-68945752-1	0	2009	1	3	7.67	0.00	0.00	0.00	0.00	2	2009-09-21
1	11	30	11	1	301	2	7	0	0	J-89564721-1	0	2009	1	4	100.00	0.00	0.00	0.00	0.00	2	2009-09-21
1	11	30	11	1	301	2	8	0	0	7014495	ZIE-60T	2009	1	1	58.33	0.00	0.00	0.00	0.00	2	2009-09-21
1	11	30	11	1	301	2	8	0	0	J-63245798-1	GCK-39F	2009	1	2	58.33	0.00	0.00	0.00	0.00	2	2009-09-21
1	11	30	11	1	301	2	8	0	0	J-63245798-1	CAB-15T	2009	1	3	58.33	0.00	0.00	0.00	0.00	2	2009-09-21
1	11	30	11	1	301	3	54	0	0	7014495	0	2009	1	1	60.00	0.00	0.00	0.00	0.00	2	2009-09-21
1	11	30	11	1	301	2	7	0	0	J-63245798-1	0	2009	1	2	166.67	0.00	0.00	0.00	0.00	1	2009-09-21
1	11	30	11	1	301	2	7	0	0	J-78932165-1	0	2009	2	5	11.50	0.00	0.58	0.00	0.00	2	2009-09-21
1	11	30	11	1	301	2	7	0	0	J-63245798-1	0	2009	2	6	166.67	0.00	0.00	0.00	0.00	2	2009-09-21
1	11	30	11	1	301	2	7	0	0	J-68945752-1	0	2009	2	7	7.67	0.00	0.38	0.00	0.00	2	2009-09-21
1	11	30	11	1	301	2	7	0	0	J-89564721-1	0	2009	2	8	100.00	0.00	5.00	0.00	0.00	2	2009-09-21
1	11	30	11	1	301	2	8	0	0	7014495	ZIE-60T	2009	2	4	58.33	0.00	0.00	0.00	0.00	2	2009-09-21
1	11	30	11	1	301	2	8	0	0	J-63245798-1	GCK-39F	2009	2	5	58.33	0.00	0.00	0.00	0.00	2	2009-09-21
1	11	30	11	1	301	2	8	0	0	J-63245798-1	CAB-15T	2009	2	6	58.33	0.00	0.00	0.00	0.00	2	2009-09-21
1	11	30	11	1	301	3	54	0	0	7014495	0	2009	2	2	60.00	0.00	0.00	0.00	0.00	2	2009-09-21
1	11	30	11	1	301	2	7	0	0	J-07654331-9	0	2009	3	9	79.17	0.00	0.00	0.00	0.00	2	2009-09-21
1	11	30	11	1	301	2	7	0	0	J-78932165-1	0	2009	3	10	11.50	0.00	1.15	1.15	0.00	2	2009-09-21
1	11	30	11	1	301	2	7	0	0	J-63245798-1	0	2009	3	11	166.67	0.00	8.33	3.33	0.00	2	2009-09-21
1	11	30	11	1	301	2	7	0	0	J-68945752-1	0	2009	3	12	7.67	0.00	0.77	0.77	0.00	2	2009-09-21
1	11	30	11	1	301	2	7	0	0	J-89564721-1	0	2009	3	13	100.00	0.00	10.00	10.00	0.00	2	2009-09-21
1	11	30	11	1	301	2	8	0	0	7014495	ZIE-60T	2009	3	7	58.33	0.00	0.00	0.00	0.00	2	2009-09-21
1	11	30	11	1	301	2	8	0	0	J-63245798-1	GCK-39F	2009	3	8	58.33	0.00	0.00	0.00	0.00	2	2009-09-21
1	11	30	11	1	301	2	8	0	0	J-63245798-1	CAB-15T	2009	3	9	58.33	0.00	0.00	0.00	0.00	2	2009-09-21
1	11	30	11	1	301	3	54	0	0	7014495	0	2009	3	3	60.00	0.00	0.00	0.00	0.00	2	2009-09-21
1	11	30	11	1	301	2	7	0	0	J-07654331-9	0	2009	4	14	79.17	0.00	3.96	0.00	0.00	2	2009-09-21
1	11	30	11	1	301	2	7	0	0	J-78932165-1	0	2009	4	15	11.50	0.00	1.73	0.00	0.00	2	2009-09-21
1	11	30	11	1	301	2	7	0	0	J-63245798-1	0	2009	4	16	166.67	0.00	16.67	0.00	0.00	2	2009-09-21
1	11	30	11	1	301	2	7	0	0	J-68945752-1	0	2009	4	17	7.67	0.00	1.15	0.00	0.00	2	2009-09-21
1	11	30	11	1	301	2	7	0	0	J-89564721-1	0	2009	4	18	100.00	0.00	15.00	0.00	0.00	2	2009-09-21
1	11	30	11	1	301	2	8	0	0	7014495	ZIE-60T	2009	4	10	58.33	0.00	0.00	0.00	0.00	2	2009-09-21
1	11	30	11	1	301	2	8	0	0	J-63245798-1	GCK-39F	2009	4	11	58.33	0.00	0.00	0.00	0.00	2	2009-09-21
1	11	30	11	1	301	2	8	0	0	J-63245798-1	CAB-15T	2009	4	12	58.33	0.00	0.00	0.00	0.00	2	2009-09-21
1	11	30	11	1	301	3	54	0	0	7014495	0	2009	4	4	60.00	0.00	0.00	0.00	0.00	2	2009-09-21
1	11	30	11	1	301	2	7	0	0	J-07654331-9	0	2009	5	19	79.17	0.00	7.92	0.00	0.00	2	2009-09-21
1	11	30	11	1	301	2	7	0	0	J-78932165-1	0	2009	5	20	11.50	0.92	2.30	4.22	0.00	2	2009-09-21
1	11	30	11	1	301	2	7	0	0	J-63245798-1	0	2009	5	21	166.67	0.00	25.00	0.00	0.00	2	2009-09-21
1	11	30	11	1	301	2	7	0	0	J-68945752-1	0	2009	5	22	7.67	0.61	1.53	2.82	0.00	2	2009-09-21
1	11	30	11	1	301	2	7	0	0	J-89564721-1	0	2009	5	23	100.00	8.00	20.00	36.72	0.00	2	2009-09-21
1	11	30	11	1	301	2	8	0	0	7014495	ZIE-60T	2009	5	13	58.33	0.00	0.00	0.00	0.00	2	2009-09-21
1	11	30	11	1	301	2	8	0	0	J-63245798-1	GCK-39F	2009	5	14	58.33	0.00	0.00	0.00	0.00	2	2009-09-21
1	11	30	11	1	301	2	8	0	0	J-63245798-1	CAB-15T	2009	5	15	58.33	0.00	0.00	0.00	0.00	2	2009-09-21
1	11	30	11	1	301	3	54	0	0	7014495	0	2009	5	5	60.00	0.00	0.00	0.00	0.00	2	2009-09-21
\.


--
-- TOC entry 4997 (class 0 OID 502326)
-- Dependencies: 4198
-- Data for Name: shd950_solvencia; Type: TABLE DATA; Schema: public; Owner: sisap
--

COPY shd950_solvencia (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, ano, numero_solvencia, rif_cedula, fecha_expedicion, valida_hasta, objeto_solvencia, monto_solvencia, observaciones, condicion_actividad, fecha_registro, username_registro, fecha_anulacion, username_anulacion) FROM stdin;
\.


--
-- TOC entry 4998 (class 0 OID 502334)
-- Dependencies: 4199
-- Data for Name: shd950_solvencia_monto; Type: TABLE DATA; Schema: public; Owner: sisap
--

COPY shd950_solvencia_monto (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, monto_solvencia) FROM stdin;
1	11	30	11	1	200.00
\.


--
-- TOC entry 4999 (class 0 OID 502339)
-- Dependencies: 4200
-- Data for Name: shd950_solvencia_numero; Type: TABLE DATA; Schema: public; Owner: sisap
--

COPY shd950_solvencia_numero (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, ano, numero_solvencia, situacion) FROM stdin;
1	11	30	11	1	2009	2	1
1	11	30	11	1	2009	3	1
1	11	30	11	1	2009	4	1
1	11	30	11	1	2009	5	1
1	11	30	11	1	2009	6	1
1	11	30	11	1	2009	7	1
1	11	30	11	1	2009	8	1
1	11	30	11	1	2009	9	1
1	11	30	11	1	2009	10	1
1	11	30	11	1	2009	11	1
1	11	30	11	1	2009	12	1
1	11	30	11	1	2009	13	1
1	11	30	11	1	2009	14	1
1	11	30	11	1	2009	15	1
1	11	30	11	1	2009	16	1
1	11	30	11	1	2009	17	1
1	11	30	11	1	2009	18	1
1	11	30	11	1	2009	19	1
1	11	30	11	1	2009	20	1
1	11	30	11	1	2009	21	1
1	11	30	11	1	2009	22	1
1	11	30	11	1	2009	23	1
1	11	30	11	1	2009	24	1
1	11	30	11	1	2009	25	1
1	11	30	11	1	2009	26	1
1	11	30	11	1	2009	27	1
1	11	30	11	1	2009	28	1
1	11	30	11	1	2009	29	1
1	11	30	11	1	2009	30	1
1	11	30	11	1	2009	31	1
1	11	30	11	1	2009	32	1
1	11	30	11	1	2009	33	1
1	11	30	11	1	2009	34	1
1	11	30	11	1	2009	35	1
1	11	30	11	1	2009	36	1
1	11	30	11	1	2009	37	1
1	11	30	11	1	2009	38	1
1	11	30	11	1	2009	39	1
1	11	30	11	1	2009	40	1
1	11	30	11	1	2009	41	1
1	11	30	11	1	2009	42	1
1	11	30	11	1	2009	43	1
1	11	30	11	1	2009	44	1
1	11	30	11	1	2009	45	1
1	11	30	11	1	2009	46	1
1	11	30	11	1	2009	47	1
1	11	30	11	1	2009	48	1
1	11	30	11	1	2009	49	1
1	11	30	11	1	2009	50	1
1	11	30	11	1	2009	51	1
1	11	30	11	1	2009	52	1
1	11	30	11	1	2009	53	1
1	11	30	11	1	2009	54	1
1	11	30	11	1	2009	55	1
1	11	30	11	1	2009	56	1
1	11	30	11	1	2009	57	1
1	11	30	11	1	2009	58	1
1	11	30	11	1	2009	59	1
1	11	30	11	1	2009	60	1
1	11	30	11	1	2009	61	1
1	11	30	11	1	2009	62	1
1	11	30	11	1	2009	63	1
1	11	30	11	1	2009	64	1
1	11	30	11	1	2009	65	1
1	11	30	11	1	2009	66	1
1	11	30	11	1	2009	67	1
1	11	30	11	1	2009	68	1
1	11	30	11	1	2009	69	1
1	11	30	11	1	2009	70	1
1	11	30	11	1	2009	71	1
1	11	30	11	1	2009	72	1
1	11	30	11	1	2009	73	1
1	11	30	11	1	2009	74	1
1	11	30	11	1	2009	75	1
1	11	30	11	1	2009	76	1
1	11	30	11	1	2009	77	1
1	11	30	11	1	2009	78	1
1	11	30	11	1	2009	79	1
1	11	30	11	1	2009	80	1
1	11	30	11	1	2009	81	1
1	11	30	11	1	2009	82	1
1	11	30	11	1	2009	83	1
1	11	30	11	1	2009	84	1
1	11	30	11	1	2009	85	1
1	11	30	11	1	2009	86	1
1	11	30	11	1	2009	87	1
1	11	30	11	1	2009	88	1
1	11	30	11	1	2009	89	1
1	11	30	11	1	2009	90	1
1	11	30	11	1	2009	91	1
1	11	30	11	1	2009	92	1
1	11	30	11	1	2009	93	1
1	11	30	11	1	2009	94	1
1	11	30	11	1	2009	95	1
1	11	30	11	1	2009	96	1
1	11	30	11	1	2009	97	1
1	11	30	11	1	2009	98	1
1	11	30	11	1	2009	99	1
1	11	30	11	1	2009	100	1
1	11	30	11	1	2009	101	1
1	11	30	11	1	2009	102	1
1	11	30	11	1	2009	103	1
1	11	30	11	1	2009	104	1
1	11	30	11	1	2009	105	1
1	11	30	11	1	2009	106	1
1	11	30	11	1	2009	107	1
1	11	30	11	1	2009	108	1
1	11	30	11	1	2009	109	1
1	11	30	11	1	2009	110	1
1	11	30	11	1	2009	111	1
1	11	30	11	1	2009	112	1
1	11	30	11	1	2009	113	1
1	11	30	11	1	2009	114	1
1	11	30	11	1	2009	115	1
1	11	30	11	1	2009	116	1
1	11	30	11	1	2009	117	1
1	11	30	11	1	2009	118	1
1	11	30	11	1	2009	119	1
1	11	30	11	1	2009	120	1
1	11	30	11	1	2009	121	1
1	11	30	11	1	2009	122	1
1	11	30	11	1	2009	123	1
1	11	30	11	1	2009	124	1
1	11	30	11	1	2009	125	1
1	11	30	11	1	2009	126	1
1	11	30	11	1	2009	127	1
1	11	30	11	1	2009	128	1
1	11	30	11	1	2009	129	1
1	11	30	11	1	2009	130	1
1	11	30	11	1	2009	131	1
1	11	30	11	1	2009	132	1
1	11	30	11	1	2009	133	1
1	11	30	11	1	2009	134	1
1	11	30	11	1	2009	135	1
1	11	30	11	1	2009	136	1
1	11	30	11	1	2009	137	1
1	11	30	11	1	2009	138	1
1	11	30	11	1	2009	139	1
1	11	30	11	1	2009	140	1
1	11	30	11	1	2009	141	1
1	11	30	11	1	2009	142	1
1	11	30	11	1	2009	143	1
1	11	30	11	1	2009	144	1
1	11	30	11	1	2009	145	1
1	11	30	11	1	2009	146	1
1	11	30	11	1	2009	147	1
1	11	30	11	1	2009	148	1
1	11	30	11	1	2009	149	1
1	11	30	11	1	2009	150	1
1	11	30	11	1	2009	151	1
1	11	30	11	1	2009	152	1
1	11	30	11	1	2009	153	1
1	11	30	11	1	2009	154	1
1	11	30	11	1	2009	155	1
1	11	30	11	1	2009	156	1
1	11	30	11	1	2009	157	1
1	11	30	11	1	2009	158	1
1	11	30	11	1	2009	159	1
1	11	30	11	1	2009	160	1
1	11	30	11	1	2009	161	1
1	11	30	11	1	2009	162	1
1	11	30	11	1	2009	163	1
1	11	30	11	1	2009	164	1
1	11	30	11	1	2009	165	1
1	11	30	11	1	2009	166	1
1	11	30	11	1	2009	167	1
1	11	30	11	1	2009	168	1
1	11	30	11	1	2009	169	1
1	11	30	11	1	2009	170	1
1	11	30	11	1	2009	171	1
1	11	30	11	1	2009	172	1
1	11	30	11	1	2009	173	1
1	11	30	11	1	2009	174	1
1	11	30	11	1	2009	175	1
1	11	30	11	1	2009	176	1
1	11	30	11	1	2009	177	1
1	11	30	11	1	2009	178	1
1	11	30	11	1	2009	179	1
1	11	30	11	1	2009	180	1
1	11	30	11	1	2009	181	1
1	11	30	11	1	2009	182	1
1	11	30	11	1	2009	183	1
1	11	30	11	1	2009	184	1
1	11	30	11	1	2009	185	1
1	11	30	11	1	2009	186	1
1	11	30	11	1	2009	187	1
1	11	30	11	1	2009	188	1
1	11	30	11	1	2009	189	1
1	11	30	11	1	2009	190	1
1	11	30	11	1	2009	191	1
1	11	30	11	1	2009	192	1
1	11	30	11	1	2009	193	1
1	11	30	11	1	2009	194	1
1	11	30	11	1	2009	195	1
1	11	30	11	1	2009	196	1
1	11	30	11	1	2009	197	1
1	11	30	11	1	2009	198	1
1	11	30	11	1	2009	199	1
1	11	30	11	1	2009	200	1
1	11	30	11	1	2009	201	1
1	11	30	11	1	2009	202	1
1	11	30	11	1	2009	203	1
1	11	30	11	1	2009	204	1
1	11	30	11	1	2009	205	1
1	11	30	11	1	2009	206	1
1	11	30	11	1	2009	207	1
1	11	30	11	1	2009	208	1
1	11	30	11	1	2009	209	1
1	11	30	11	1	2009	210	1
1	11	30	11	1	2009	211	1
1	11	30	11	1	2009	212	1
1	11	30	11	1	2009	213	1
1	11	30	11	1	2009	214	1
1	11	30	11	1	2009	215	1
1	11	30	11	1	2009	216	1
1	11	30	11	1	2009	217	1
1	11	30	11	1	2009	218	1
1	11	30	11	1	2009	219	1
1	11	30	11	1	2009	220	1
1	11	30	11	1	2009	221	1
1	11	30	11	1	2009	222	1
1	11	30	11	1	2009	223	1
1	11	30	11	1	2009	224	1
1	11	30	11	1	2009	225	1
1	11	30	11	1	2009	226	1
1	11	30	11	1	2009	227	1
1	11	30	11	1	2009	228	1
1	11	30	11	1	2009	229	1
1	11	30	11	1	2009	230	1
1	11	30	11	1	2009	231	1
1	11	30	11	1	2009	232	1
1	11	30	11	1	2009	233	1
1	11	30	11	1	2009	234	1
1	11	30	11	1	2009	235	1
1	11	30	11	1	2009	236	1
1	11	30	11	1	2009	237	1
1	11	30	11	1	2009	238	1
1	11	30	11	1	2009	239	1
1	11	30	11	1	2009	240	1
1	11	30	11	1	2009	241	1
1	11	30	11	1	2009	242	1
1	11	30	11	1	2009	243	1
1	11	30	11	1	2009	244	1
1	11	30	11	1	2009	245	1
1	11	30	11	1	2009	246	1
1	11	30	11	1	2009	247	1
1	11	30	11	1	2009	248	1
1	11	30	11	1	2009	249	1
1	11	30	11	1	2009	250	1
1	11	30	11	1	2009	251	1
1	11	30	11	1	2009	252	1
1	11	30	11	1	2009	253	1
1	11	30	11	1	2009	254	1
1	11	30	11	1	2009	255	1
1	11	30	11	1	2009	256	1
1	11	30	11	1	2009	257	1
1	11	30	11	1	2009	258	1
1	11	30	11	1	2009	259	1
1	11	30	11	1	2009	260	1
1	11	30	11	1	2009	261	1
1	11	30	11	1	2009	262	1
1	11	30	11	1	2009	263	1
1	11	30	11	1	2009	264	1
1	11	30	11	1	2009	265	1
1	11	30	11	1	2009	266	1
1	11	30	11	1	2009	267	1
1	11	30	11	1	2009	268	1
1	11	30	11	1	2009	269	1
1	11	30	11	1	2009	270	1
1	11	30	11	1	2009	271	1
1	11	30	11	1	2009	272	1
1	11	30	11	1	2009	273	1
1	11	30	11	1	2009	274	1
1	11	30	11	1	2009	275	1
1	11	30	11	1	2009	276	1
1	11	30	11	1	2009	277	1
1	11	30	11	1	2009	278	1
1	11	30	11	1	2009	279	1
1	11	30	11	1	2009	280	1
1	11	30	11	1	2009	281	1
1	11	30	11	1	2009	282	1
1	11	30	11	1	2009	283	1
1	11	30	11	1	2009	284	1
1	11	30	11	1	2009	285	1
1	11	30	11	1	2009	286	1
1	11	30	11	1	2009	287	1
1	11	30	11	1	2009	288	1
1	11	30	11	1	2009	289	1
1	11	30	11	1	2009	290	1
1	11	30	11	1	2009	291	1
1	11	30	11	1	2009	292	1
1	11	30	11	1	2009	293	1
1	11	30	11	1	2009	294	1
1	11	30	11	1	2009	295	1
1	11	30	11	1	2009	296	1
1	11	30	11	1	2009	297	1
1	11	30	11	1	2009	298	1
1	11	30	11	1	2009	299	1
1	11	30	11	1	2009	300	1
1	11	30	11	1	2009	301	1
1	11	30	11	1	2009	302	1
1	11	30	11	1	2009	303	1
1	11	30	11	1	2009	304	1
1	11	30	11	1	2009	305	1
1	11	30	11	1	2009	306	1
1	11	30	11	1	2009	307	1
1	11	30	11	1	2009	308	1
1	11	30	11	1	2009	309	1
1	11	30	11	1	2009	310	1
1	11	30	11	1	2009	311	1
1	11	30	11	1	2009	312	1
1	11	30	11	1	2009	313	1
1	11	30	11	1	2009	314	1
1	11	30	11	1	2009	315	1
1	11	30	11	1	2009	316	1
1	11	30	11	1	2009	317	1
1	11	30	11	1	2009	318	1
1	11	30	11	1	2009	319	1
1	11	30	11	1	2009	320	1
1	11	30	11	1	2009	321	1
1	11	30	11	1	2009	322	1
1	11	30	11	1	2009	323	1
1	11	30	11	1	2009	324	1
1	11	30	11	1	2009	325	1
1	11	30	11	1	2009	326	1
1	11	30	11	1	2009	327	1
1	11	30	11	1	2009	328	1
1	11	30	11	1	2009	329	1
1	11	30	11	1	2009	330	1
1	11	30	11	1	2009	331	1
1	11	30	11	1	2009	332	1
1	11	30	11	1	2009	333	1
1	11	30	11	1	2009	334	1
1	11	30	11	1	2009	335	1
1	11	30	11	1	2009	336	1
1	11	30	11	1	2009	337	1
1	11	30	11	1	2009	338	1
1	11	30	11	1	2009	339	1
1	11	30	11	1	2009	340	1
1	11	30	11	1	2009	341	1
1	11	30	11	1	2009	342	1
1	11	30	11	1	2009	343	1
1	11	30	11	1	2009	344	1
1	11	30	11	1	2009	345	1
1	11	30	11	1	2009	346	1
1	11	30	11	1	2009	347	1
1	11	30	11	1	2009	348	1
1	11	30	11	1	2009	349	1
1	11	30	11	1	2009	350	1
1	11	30	11	1	2009	351	1
1	11	30	11	1	2009	352	1
1	11	30	11	1	2009	353	1
1	11	30	11	1	2009	354	1
1	11	30	11	1	2009	355	1
1	11	30	11	1	2009	356	1
1	11	30	11	1	2009	357	1
1	11	30	11	1	2009	358	1
1	11	30	11	1	2009	359	1
1	11	30	11	1	2009	360	1
1	11	30	11	1	2009	361	1
1	11	30	11	1	2009	362	1
1	11	30	11	1	2009	363	1
1	11	30	11	1	2009	364	1
1	11	30	11	1	2009	365	1
1	11	30	11	1	2009	366	1
1	11	30	11	1	2009	367	1
1	11	30	11	1	2009	368	1
1	11	30	11	1	2009	369	1
1	11	30	11	1	2009	370	1
1	11	30	11	1	2009	371	1
1	11	30	11	1	2009	372	1
1	11	30	11	1	2009	373	1
1	11	30	11	1	2009	374	1
1	11	30	11	1	2009	375	1
1	11	30	11	1	2009	376	1
1	11	30	11	1	2009	377	1
1	11	30	11	1	2009	378	1
1	11	30	11	1	2009	379	1
1	11	30	11	1	2009	380	1
1	11	30	11	1	2009	381	1
1	11	30	11	1	2009	382	1
1	11	30	11	1	2009	383	1
1	11	30	11	1	2009	384	1
1	11	30	11	1	2009	385	1
1	11	30	11	1	2009	386	1
1	11	30	11	1	2009	387	1
1	11	30	11	1	2009	388	1
1	11	30	11	1	2009	389	1
1	11	30	11	1	2009	390	1
1	11	30	11	1	2009	391	1
1	11	30	11	1	2009	392	1
1	11	30	11	1	2009	393	1
1	11	30	11	1	2009	394	1
1	11	30	11	1	2009	395	1
1	11	30	11	1	2009	396	1
1	11	30	11	1	2009	397	1
1	11	30	11	1	2009	398	1
1	11	30	11	1	2009	399	1
1	11	30	11	1	2009	400	1
1	11	30	11	1	2009	401	1
1	11	30	11	1	2009	402	1
1	11	30	11	1	2009	403	1
1	11	30	11	1	2009	404	1
1	11	30	11	1	2009	405	1
1	11	30	11	1	2009	406	1
1	11	30	11	1	2009	407	1
1	11	30	11	1	2009	408	1
1	11	30	11	1	2009	409	1
1	11	30	11	1	2009	410	1
1	11	30	11	1	2009	411	1
1	11	30	11	1	2009	412	1
1	11	30	11	1	2009	413	1
1	11	30	11	1	2009	414	1
1	11	30	11	1	2009	415	1
1	11	30	11	1	2009	416	1
1	11	30	11	1	2009	417	1
1	11	30	11	1	2009	418	1
1	11	30	11	1	2009	419	1
1	11	30	11	1	2009	420	1
1	11	30	11	1	2009	421	1
1	11	30	11	1	2009	422	1
1	11	30	11	1	2009	423	1
1	11	30	11	1	2009	424	1
1	11	30	11	1	2009	425	1
1	11	30	11	1	2009	426	1
1	11	30	11	1	2009	427	1
1	11	30	11	1	2009	428	1
1	11	30	11	1	2009	429	1
1	11	30	11	1	2009	430	1
1	11	30	11	1	2009	431	1
1	11	30	11	1	2009	432	1
1	11	30	11	1	2009	433	1
1	11	30	11	1	2009	434	1
1	11	30	11	1	2009	435	1
1	11	30	11	1	2009	436	1
1	11	30	11	1	2009	437	1
1	11	30	11	1	2009	438	1
1	11	30	11	1	2009	439	1
1	11	30	11	1	2009	440	1
1	11	30	11	1	2009	441	1
1	11	30	11	1	2009	442	1
1	11	30	11	1	2009	443	1
1	11	30	11	1	2009	444	1
1	11	30	11	1	2009	445	1
1	11	30	11	1	2009	446	1
1	11	30	11	1	2009	447	1
1	11	30	11	1	2009	448	1
1	11	30	11	1	2009	449	1
1	11	30	11	1	2009	450	1
1	11	30	11	1	2009	451	1
1	11	30	11	1	2009	452	1
1	11	30	11	1	2009	453	1
1	11	30	11	1	2009	454	1
1	11	30	11	1	2009	455	1
1	11	30	11	1	2009	456	1
1	11	30	11	1	2009	457	1
1	11	30	11	1	2009	458	1
1	11	30	11	1	2009	459	1
1	11	30	11	1	2009	460	1
1	11	30	11	1	2009	461	1
1	11	30	11	1	2009	462	1
1	11	30	11	1	2009	463	1
1	11	30	11	1	2009	464	1
1	11	30	11	1	2009	465	1
1	11	30	11	1	2009	466	1
1	11	30	11	1	2009	467	1
1	11	30	11	1	2009	468	1
1	11	30	11	1	2009	469	1
1	11	30	11	1	2009	470	1
1	11	30	11	1	2009	471	1
1	11	30	11	1	2009	472	1
1	11	30	11	1	2009	473	1
1	11	30	11	1	2009	474	1
1	11	30	11	1	2009	475	1
1	11	30	11	1	2009	476	1
1	11	30	11	1	2009	477	1
1	11	30	11	1	2009	478	1
1	11	30	11	1	2009	479	1
1	11	30	11	1	2009	480	1
1	11	30	11	1	2009	481	1
1	11	30	11	1	2009	482	1
1	11	30	11	1	2009	483	1
1	11	30	11	1	2009	484	1
1	11	30	11	1	2009	485	1
1	11	30	11	1	2009	486	1
1	11	30	11	1	2009	487	1
1	11	30	11	1	2009	488	1
1	11	30	11	1	2009	489	1
1	11	30	11	1	2009	490	1
1	11	30	11	1	2009	491	1
1	11	30	11	1	2009	492	1
1	11	30	11	1	2009	493	1
1	11	30	11	1	2009	494	1
1	11	30	11	1	2009	495	1
1	11	30	11	1	2009	496	1
1	11	30	11	1	2009	497	1
1	11	30	11	1	2009	498	1
1	11	30	11	1	2009	499	1
1	11	30	11	1	2009	500	1
1	11	30	11	1	2009	1	1
\.


--
-- TOC entry 4858 (class 2606 OID 502032)
-- Dependencies: 4152 4152 4152 4152 4152 4152 4152
-- Name: shd000_arranque_pkey; Type: CONSTRAINT; Schema: public; Owner: sisap; Tablespace:
--

ALTER TABLE ONLY shd000_arranque
    ADD CONSTRAINT shd000_arranque_pkey PRIMARY KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, ano_arranque);


--
-- TOC entry 4860 (class 2606 OID 502038)
-- Dependencies: 4153 4153 4153 4153 4153 4153 4153 4153 4153
-- Name: shd000_control_actualizacion_pkey; Type: CONSTRAINT; Schema: public; Owner: sisap; Tablespace:
--

ALTER TABLE ONLY shd000_control_actualizacion
    ADD CONSTRAINT shd000_control_actualizacion_pkey PRIMARY KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_ingreso, ano_actualizado, mes_actualizado);


--
-- TOC entry 4862 (class 2606 OID 502043)
-- Dependencies: 4154 4154 4154 4154 4154 4154 4154 4154
-- Name: shd000_control_numero_pkey; Type: CONSTRAINT; Schema: public; Owner: sisap; Tablespace:
--

ALTER TABLE ONLY shd000_control_numero
    ADD CONSTRAINT shd000_control_numero_pkey PRIMARY KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, ano, cod_ingreso);


--
-- TOC entry 4864 (class 2606 OID 502048)
-- Dependencies: 4155 4155 4155 4155 4155 4155 4155
-- Name: shd000_ordenanzas_pkey; Type: CONSTRAINT; Schema: public; Owner: sisap; Tablespace:
--

ALTER TABLE ONLY shd000_ordenanzas
    ADD CONSTRAINT shd000_ordenanzas_pkey PRIMARY KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_ingreso);


--
-- TOC entry 4866 (class 2606 OID 502053)
-- Dependencies: 4156 4156
-- Name: shd001_registro_contribuyentes_pkey; Type: CONSTRAINT; Schema: public; Owner: sisap; Tablespace:
--

ALTER TABLE ONLY shd001_registro_contribuyentes
    ADD CONSTRAINT shd001_registro_contribuyentes_pkey PRIMARY KEY (rif_cedula);


--
-- TOC entry 4868 (class 2606 OID 502058)
-- Dependencies: 4157 4157 4157 4157 4157 4157 4157
-- Name: shd002_cobradores_pkey; Type: CONSTRAINT; Schema: public; Owner: sisap; Tablespace:
--

ALTER TABLE ONLY shd002_cobradores
    ADD CONSTRAINT shd002_cobradores_pkey PRIMARY KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, rif_ci);


--
-- TOC entry 4870 (class 2606 OID 502063)
-- Dependencies: 4158 4158 4158 4158 4158 4158 4158 4158
-- Name: shd002_cobranza_pendiente_pkey; Type: CONSTRAINT; Schema: public; Owner: sisap; Tablespace:
--

ALTER TABLE ONLY shd002_cobranza_pendiente
    ADD CONSTRAINT shd002_cobranza_pendiente_pkey PRIMARY KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, rif_ci, ano);


--
-- TOC entry 4872 (class 2606 OID 502068)
-- Dependencies: 4159 4159 4159 4159 4159 4159 4159 4159
-- Name: shd002_cobranza_realizada_pkey; Type: CONSTRAINT; Schema: public; Owner: sisap; Tablespace:
--

ALTER TABLE ONLY shd002_cobranza_realizada
    ADD CONSTRAINT shd002_cobranza_realizada_pkey PRIMARY KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, rif_ci, ano);


--
-- TOC entry 4874 (class 2606 OID 502073)
-- Dependencies: 4160 4160
-- Name: shd003_codigo_ingresos_pkey; Type: CONSTRAINT; Schema: public; Owner: sisap; Tablespace:
--

ALTER TABLE ONLY shd003_codigo_ingresos
    ADD CONSTRAINT shd003_codigo_ingresos_pkey PRIMARY KEY (cod_ingreso);


--
-- TOC entry 4876 (class 2606 OID 502082)
-- Dependencies: 4161 4161 4161 4161 4161 4161 4161
-- Name: shd100_actividades_pkey; Type: CONSTRAINT; Schema: public; Owner: sisap; Tablespace:
--

ALTER TABLE ONLY shd100_actividades
    ADD CONSTRAINT shd100_actividades_pkey PRIMARY KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_actividad);


--
-- TOC entry 4878 (class 2606 OID 502090)
-- Dependencies: 4162 4162 4162 4162 4162 4162
-- Name: shd100_articulos_pkey; Type: CONSTRAINT; Schema: public; Owner: sisap; Tablespace:
--

ALTER TABLE ONLY shd100_articulos
    ADD CONSTRAINT shd100_articulos_pkey PRIMARY KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep);


--
-- TOC entry 4884 (class 2606 OID 502110)
-- Dependencies: 4165 4165 4165 4165 4165 4165 4165 4165 4165
-- Name: shd100_declaracion_actividades_pkey; Type: CONSTRAINT; Schema: public; Owner: sisap; Tablespace:
--

ALTER TABLE ONLY shd100_declaracion_actividades
    ADD CONSTRAINT shd100_declaracion_actividades_pkey PRIMARY KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, rif_cedula, numero_declaracion, cod_actividad);


--
-- TOC entry 4882 (class 2606 OID 502100)
-- Dependencies: 4164 4164 4164 4164 4164 4164 4164 4164
-- Name: shd100_declaracion_ingresos_pkey; Type: CONSTRAINT; Schema: public; Owner: sisap; Tablespace:
--

ALTER TABLE ONLY shd100_declaracion_ingresos
    ADD CONSTRAINT shd100_declaracion_ingresos_pkey PRIMARY KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, rif_cedula, numero_declaracion);


--
-- TOC entry 4886 (class 2606 OID 502120)
-- Dependencies: 4166 4166 4166 4166 4166 4166 4166 4166
-- Name: shd100_patente_actividades_pkey; Type: CONSTRAINT; Schema: public; Owner: sisap; Tablespace:
--

ALTER TABLE ONLY shd100_patente_actividades
    ADD CONSTRAINT shd100_patente_actividades_pkey PRIMARY KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, rif_cedula, cod_actividad);


--
-- TOC entry 4880 (class 2606 OID 502095)
-- Dependencies: 4163 4163 4163 4163 4163 4163 4163
-- Name: shd100_patente_pkey; Type: CONSTRAINT; Schema: public; Owner: sisap; Tablespace:
--

ALTER TABLE ONLY shd100_patente
    ADD CONSTRAINT shd100_patente_pkey PRIMARY KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, rif_cedula);


--
-- TOC entry 4890 (class 2606 OID 502138)
-- Dependencies: 4168 4168 4168 4168 4168 4168 4168 4168
-- Name: shd100_solicitud_actividades_pkey; Type: CONSTRAINT; Schema: public; Owner: sisap; Tablespace:
--

ALTER TABLE ONLY shd100_solicitud_actividades
    ADD CONSTRAINT shd100_solicitud_actividades_pkey PRIMARY KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, numero_solicitud, cod_actividad);


--
-- TOC entry 4888 (class 2606 OID 502133)
-- Dependencies: 4167 4167 4167 4167 4167 4167 4167
-- Name: shd100_solicitud_pkey; Type: CONSTRAINT; Schema: public; Owner: sisap; Tablespace:
--

ALTER TABLE ONLY shd100_solicitud
    ADD CONSTRAINT shd100_solicitud_pkey PRIMARY KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, numero_solicitud);


--
-- TOC entry 4894 (class 2606 OID 502151)
-- Dependencies: 4171 4171
-- Name: shd200_vehiculos_clases_pkey; Type: CONSTRAINT; Schema: public; Owner: sisap; Tablespace:
--

ALTER TABLE ONLY shd200_vehiculos_clases
    ADD CONSTRAINT shd200_vehiculos_clases_pkey PRIMARY KEY (codigo_clase);


--
-- TOC entry 4896 (class 2606 OID 502156)
-- Dependencies: 4172 4172 4172 4172 4172 4172 4172
-- Name: shd200_vehiculos_clasificacion_pkey; Type: CONSTRAINT; Schema: public; Owner: sisap; Tablespace:
--

ALTER TABLE ONLY shd200_vehiculos_clasificacion
    ADD CONSTRAINT shd200_vehiculos_clasificacion_pkey PRIMARY KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_clasificacion);


--
-- TOC entry 4898 (class 2606 OID 502164)
-- Dependencies: 4174 4174
-- Name: shd200_vehiculos_colores_pkey; Type: CONSTRAINT; Schema: public; Owner: sisap; Tablespace:
--

ALTER TABLE ONLY shd200_vehiculos_colores
    ADD CONSTRAINT shd200_vehiculos_colores_pkey PRIMARY KEY (codigo_color);


--
-- TOC entry 4900 (class 2606 OID 502172)
-- Dependencies: 4176 4176
-- Name: shd200_vehiculos_marcas_pkey; Type: CONSTRAINT; Schema: public; Owner: sisap; Tablespace:
--

ALTER TABLE ONLY shd200_vehiculos_marcas
    ADD CONSTRAINT shd200_vehiculos_marcas_pkey PRIMARY KEY (codigo_marca);


--
-- TOC entry 4902 (class 2606 OID 502180)
-- Dependencies: 4178 4178
-- Name: shd200_vehiculos_modelos_pkey; Type: CONSTRAINT; Schema: public; Owner: sisap; Tablespace:
--

ALTER TABLE ONLY shd200_vehiculos_modelos
    ADD CONSTRAINT shd200_vehiculos_modelos_pkey PRIMARY KEY (codigo_modelo);


--
-- TOC entry 4892 (class 2606 OID 502143)
-- Dependencies: 4169 4169 4169 4169 4169 4169 4169 4169
-- Name: shd200_vehiculos_pkey; Type: CONSTRAINT; Schema: public; Owner: sisap; Tablespace:
--

ALTER TABLE ONLY shd200_vehiculos
    ADD CONSTRAINT shd200_vehiculos_pkey PRIMARY KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, rif_cedula, placa_vehiculo);


--
-- TOC entry 4904 (class 2606 OID 502188)
-- Dependencies: 4180 4180
-- Name: shd200_vehiculos_tipos_pkey; Type: CONSTRAINT; Schema: public; Owner: sisap; Tablespace:
--

ALTER TABLE ONLY shd200_vehiculos_tipos
    ADD CONSTRAINT shd200_vehiculos_tipos_pkey PRIMARY KEY (codigo_tipo);


--
-- TOC entry 4906 (class 2606 OID 502196)
-- Dependencies: 4182 4182
-- Name: shd200_vehiculos_usos_pkey; Type: CONSTRAINT; Schema: public; Owner: sisap; Tablespace:
--

ALTER TABLE ONLY shd200_vehiculos_usos
    ADD CONSTRAINT shd200_vehiculos_usos_pkey PRIMARY KEY (codigo_uso);


--
-- TOC entry 4908 (class 2606 OID 502201)
-- Dependencies: 4183 4183 4183 4183 4183 4183 4183 4183 4183 4183
-- Name: shd300_detalles_adicional_pkey; Type: CONSTRAINT; Schema: public; Owner: sisap; Tablespace:
--

ALTER TABLE ONLY shd300_detalles_adicional
    ADD CONSTRAINT shd300_detalles_adicional_pkey PRIMARY KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, rif_cedula, cod_tipo, numero, cod_recargo);


--
-- TOC entry 4910 (class 2606 OID 502209)
-- Dependencies: 4184 4184 4184 4184 4184 4184 4184 4184 4184
-- Name: shd300_detalles_propaganda_pkey; Type: CONSTRAINT; Schema: public; Owner: sisap; Tablespace:
--

ALTER TABLE ONLY shd300_detalles_propaganda
    ADD CONSTRAINT shd300_detalles_propaganda_pkey PRIMARY KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, rif_cedula, cod_tipo, numero);


--
-- TOC entry 4912 (class 2606 OID 502214)
-- Dependencies: 4185 4185 4185 4185 4185 4185 4185
-- Name: shd300_propaganda_pkey; Type: CONSTRAINT; Schema: public; Owner: sisap; Tablespace:
--

ALTER TABLE ONLY shd300_propaganda
    ADD CONSTRAINT shd300_propaganda_pkey PRIMARY KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, rif_cedula);


--
-- TOC entry 4914 (class 2606 OID 502219)
-- Dependencies: 4186 4186 4186 4186 4186 4186 4186
-- Name: shd300_recargos_pkey; Type: CONSTRAINT; Schema: public; Owner: sisap; Tablespace:
--

ALTER TABLE ONLY shd300_recargos
    ADD CONSTRAINT shd300_recargos_pkey PRIMARY KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_recargo);


--
-- TOC entry 4916 (class 2606 OID 502224)
-- Dependencies: 4187 4187 4187 4187 4187 4187 4187
-- Name: shd300_tipo_publicidad_pkey; Type: CONSTRAINT; Schema: public; Owner: sisap; Tablespace:
--

ALTER TABLE ONLY shd300_tipo_propaganda
    ADD CONSTRAINT shd300_tipo_publicidad_pkey PRIMARY KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo);


--
-- TOC entry 4918 (class 2606 OID 502229)
-- Dependencies: 4188 4188 4188 4188 4188 4188 4188 4188
-- Name: shd400_propiedad_pkey; Type: CONSTRAINT; Schema: public; Owner: sisap; Tablespace:
--

ALTER TABLE ONLY shd400_propiedad
    ADD CONSTRAINT shd400_propiedad_pkey PRIMARY KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, rif_cedula, cod_ficha);


--
-- TOC entry 4920 (class 2606 OID 502234)
-- Dependencies: 4189 4189 4189 4189 4189 4189 4189
-- Name: shd500_aseo_clasificacion_pkey; Type: CONSTRAINT; Schema: public; Owner: sisap; Tablespace:
--

ALTER TABLE ONLY shd500_aseo_clasificacion
    ADD CONSTRAINT shd500_aseo_clasificacion_pkey PRIMARY KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_clasificacion);


--
-- TOC entry 4922 (class 2606 OID 502239)
-- Dependencies: 4190 4190 4190 4190 4190 4190 4190
-- Name: shd500_aseo_domiciliario_pkey; Type: CONSTRAINT; Schema: public; Owner: sisap; Tablespace:
--

ALTER TABLE ONLY shd500_aseo_domiciliario
    ADD CONSTRAINT shd500_aseo_domiciliario_pkey PRIMARY KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, rif_cedula);


--
-- TOC entry 4946 (class 2606 OID 502905)
-- Dependencies: 4240 4240 4240 4240 4240 4240 4240 4240
-- Name: shd600_aprobacion_arrendamiento_pkey; Type: CONSTRAINT; Schema: public; Owner: sisap; Tablespace:
--

ALTER TABLE ONLY shd600_aprobacion_arrendamiento
    ADD CONSTRAINT shd600_aprobacion_arrendamiento_pkey PRIMARY KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, rif_cedula, numero_solicitud);


--
-- TOC entry 4948 (class 2606 OID 502918)
-- Dependencies: 4241 4241 4241 4241 4241 4241 4241 4241
-- Name: shd600_compra_terreno_pkey; Type: CONSTRAINT; Schema: public; Owner: sisap; Tablespace:
--

ALTER TABLE ONLY shd600_compra_terreno
    ADD CONSTRAINT shd600_compra_terreno_pkey PRIMARY KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, rif_cedula, numero_solicitud);


--
-- TOC entry 4944 (class 2606 OID 502897)
-- Dependencies: 4239 4239 4239 4239 4239 4239 4239 4239
-- Name: shd600_solicitud_arrendamiento_pkey; Type: CONSTRAINT; Schema: public; Owner: sisap; Tablespace:
--

ALTER TABLE ONLY shd600_solicitud_arrendamiento
    ADD CONSTRAINT shd600_solicitud_arrendamiento_pkey PRIMARY KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, rif_cedula, numero_solicitud);


--
-- TOC entry 4926 (class 2606 OID 502286)
-- Dependencies: 4192 4192 4192 4192 4192 4192 4192 4192 4192
-- Name: shd700_credito_vivienda_parentesco_pkey; Type: CONSTRAINT; Schema: public; Owner: sisap; Tablespace:
--

ALTER TABLE ONLY shd700_credito_vivienda_parentesco
    ADD CONSTRAINT shd700_credito_vivienda_parentesco_pkey PRIMARY KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, rif_cedula, numero_solicitud, cod_parentesco);


--
-- TOC entry 4924 (class 2606 OID 502281)
-- Dependencies: 4191 4191 4191 4191 4191 4191 4191 4191
-- Name: shd700_credito_vivienda_pkey; Type: CONSTRAINT; Schema: public; Owner: sisap; Tablespace:
--

ALTER TABLE ONLY shd700_credito_vivienda
    ADD CONSTRAINT shd700_credito_vivienda_pkey PRIMARY KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, rif_cedula, numero_solicitud);


--
-- TOC entry 4928 (class 2606 OID 502296)
-- Dependencies: 4193 4193 4193 4193 4193 4193 4193 4193 4193 4193 4193 4193 4193 4193
-- Name: shd900_cobranza_acumulada_pkey; Type: CONSTRAINT; Schema: public; Owner: sisap; Tablespace:
--

ALTER TABLE ONLY shd900_cobranza_acumulada
    ADD CONSTRAINT shd900_cobranza_acumulada_pkey PRIMARY KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, ano, mes, dia, cod_partida, cod_generica, cod_especifica, cod_sub_espec, cod_auxiliar);


--
-- TOC entry 4936 (class 2606 OID 502325)
-- Dependencies: 4197 4197 4197 4197 4197 4197 4197 4197
-- Name: shd900_cobranza_diaria_pkey; Type: CONSTRAINT; Schema: public; Owner: sisap; Tablespace:
--

ALTER TABLE ONLY shd900_cobranza_diaria
    ADD CONSTRAINT shd900_cobranza_diaria_pkey PRIMARY KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, ano_comprobante, numero_comprobante);


--
-- TOC entry 4950 (class 2606 OID 502980)
-- Dependencies: 4245 4245 4245 4245 4245 4245 4245 4245 4245 4245 4245
-- Name: shd900_cobranza_diaria_planillas_pkey; Type: CONSTRAINT; Schema: public; Owner: sisap; Tablespace:
--

ALTER TABLE ONLY shd900_cobranza_diaria_planillas
    ADD CONSTRAINT shd900_cobranza_diaria_planillas_pkey PRIMARY KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, ano_comprobante, numero_comprobante, ano, mes, numero_planilla);


--
-- TOC entry 4934 (class 2606 OID 502317)
-- Dependencies: 4196 4196 4196 4196 4196 4196 4196 4196
-- Name: shd900_cobranza_numero_pkey; Type: CONSTRAINT; Schema: public; Owner: sisap; Tablespace:
--

ALTER TABLE ONLY shd900_cobranza_numero
    ADD CONSTRAINT shd900_cobranza_numero_pkey PRIMARY KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, ano_comprobante, numero_comprobante);


--
-- TOC entry 4930 (class 2606 OID 502301)
-- Dependencies: 4194 4194 4194 4194 4194 4194 4194 4194 4194 4194 4194 4194 4194
-- Name: shd900_planillas_deuda_cobro_cuerpo_pkey; Type: CONSTRAINT; Schema: public; Owner: sisap; Tablespace:
--

ALTER TABLE ONLY shd900_planillas_deuda_cobro_cuerpo
    ADD CONSTRAINT shd900_planillas_deuda_cobro_cuerpo_pkey PRIMARY KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_partida, cod_generica, cod_especifica, cod_sub_espec, cod_auxiliar, rif_cedula, cod_numero_catastral_placas);


--
-- TOC entry 4932 (class 2606 OID 502307)
-- Dependencies: 4195 4195 4195 4195 4195 4195 4195 4195 4195 4195 4195 4195 4195 4195 4195 4195
-- Name: shd900_planillas_deuda_cobro_detalles_pkey; Type: CONSTRAINT; Schema: public; Owner: sisap; Tablespace:
--

ALTER TABLE ONLY shd900_planillas_deuda_cobro_detalles
    ADD CONSTRAINT shd900_planillas_deuda_cobro_detalles_pkey PRIMARY KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_partida, cod_generica, cod_especifica, cod_sub_espec, cod_auxiliar, rif_cedula, cod_numero_catastral_placas, ano, mes, numero_planilla);


--
-- TOC entry 4940 (class 2606 OID 502338)
-- Dependencies: 4199 4199 4199 4199 4199 4199
-- Name: shd950_solvencia_monto_pkey; Type: CONSTRAINT; Schema: public; Owner: sisap; Tablespace:
--

ALTER TABLE ONLY shd950_solvencia_monto
    ADD CONSTRAINT shd950_solvencia_monto_pkey PRIMARY KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep);


--
-- TOC entry 4938 (class 2606 OID 502333)
-- Dependencies: 4198 4198 4198 4198 4198 4198 4198 4198
-- Name: shd950_solvencia_pkey; Type: CONSTRAINT; Schema: public; Owner: sisap; Tablespace:
--

ALTER TABLE ONLY shd950_solvencia
    ADD CONSTRAINT shd950_solvencia_pkey PRIMARY KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, ano, numero_solvencia);


--
-- TOC entry 4942 (class 2606 OID 502343)
-- Dependencies: 4200 4200 4200 4200 4200 4200 4200 4200
-- Name: shd950_solvencias_numero_pkey; Type: CONSTRAINT; Schema: public; Owner: sisap; Tablespace:
--

ALTER TABLE ONLY shd950_solvencia_numero
    ADD CONSTRAINT shd950_solvencias_numero_pkey PRIMARY KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, ano, numero_solvencia);


--
-- TOC entry 4952 (class 2606 OID 502111)
-- Dependencies: 4881 4164 4164 4164 4164 4164 4164 4164 4165 4165 4165 4165 4165 4165 4165
-- Name: shd100_declaracion_actividades_1; Type: FK CONSTRAINT; Schema: public; Owner: sisap
--

ALTER TABLE ONLY shd100_declaracion_actividades
    ADD CONSTRAINT shd100_declaracion_actividades_1 FOREIGN KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, rif_cedula, numero_declaracion) REFERENCES shd100_declaracion_ingresos(cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, rif_cedula, numero_declaracion) ON DELETE CASCADE;


--
-- TOC entry 4951 (class 2606 OID 502101)
-- Dependencies: 4163 4163 4163 4163 4164 4164 4164 4164 4164 4164 4879 4163 4163
-- Name: shd100_declaracion_ingresos_1; Type: FK CONSTRAINT; Schema: public; Owner: sisap
--

ALTER TABLE ONLY shd100_declaracion_ingresos
    ADD CONSTRAINT shd100_declaracion_ingresos_1 FOREIGN KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, rif_cedula) REFERENCES shd100_patente(cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, rif_cedula) ON DELETE CASCADE;


--
-- TOC entry 4953 (class 2606 OID 502121)
-- Dependencies: 4879 4163 4163 4163 4163 4163 4163 4166 4166 4166 4166 4166 4166
-- Name: shd100_patente_actividades_1; Type: FK CONSTRAINT; Schema: public; Owner: sisap
--

ALTER TABLE ONLY shd100_patente_actividades
    ADD CONSTRAINT shd100_patente_actividades_1 FOREIGN KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, rif_cedula) REFERENCES shd100_patente(cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, rif_cedula) ON DELETE CASCADE;


--
-- TOC entry 4955 (class 2606 OID 502906)
-- Dependencies: 4943 4239 4239 4239 4239 4239 4239 4239 4240 4240 4240 4240 4240 4240 4240
-- Name: shd600_aprobacion_arrendamiento_1; Type: FK CONSTRAINT; Schema: public; Owner: sisap
--

ALTER TABLE ONLY shd600_aprobacion_arrendamiento
    ADD CONSTRAINT shd600_aprobacion_arrendamiento_1 FOREIGN KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, rif_cedula, numero_solicitud) REFERENCES shd600_solicitud_arrendamiento(cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, rif_cedula, numero_solicitud) ON DELETE CASCADE;


--
-- TOC entry 4956 (class 2606 OID 502919)
-- Dependencies: 4945 4240 4240 4240 4240 4240 4240 4240 4241 4241 4241 4241 4241 4241 4241
-- Name: shd600_compra_terreno_1; Type: FK CONSTRAINT; Schema: public; Owner: sisap
--

ALTER TABLE ONLY shd600_compra_terreno
    ADD CONSTRAINT shd600_compra_terreno_1 FOREIGN KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, rif_cedula, numero_solicitud) REFERENCES shd600_aprobacion_arrendamiento(cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, rif_cedula, numero_solicitud) ON DELETE CASCADE;


--
-- TOC entry 4954 (class 2606 OID 502287)
-- Dependencies: 4923 4191 4191 4191 4191 4191 4191 4191 4192 4192 4192 4192 4192 4192 4192
-- Name: shd700_credito_vivienda_parentesco_1; Type: FK CONSTRAINT; Schema: public; Owner: sisap
--

ALTER TABLE ONLY shd700_credito_vivienda_parentesco
    ADD CONSTRAINT shd700_credito_vivienda_parentesco_1 FOREIGN KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, rif_cedula, numero_solicitud) REFERENCES shd700_credito_vivienda(cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, rif_cedula, numero_solicitud) ON DELETE CASCADE;


-- Completed on 2009-09-22 08:25:09 VET

--
-- PostgreSQL database dump complete
--

