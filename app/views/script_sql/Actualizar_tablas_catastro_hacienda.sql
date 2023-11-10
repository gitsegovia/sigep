--
-- PostgreSQL database dump
--

SET client_encoding = 'UTF8';
SET standard_conforming_strings = off;
SET check_function_bodies = false;
SET client_min_messages = warning;
SET escape_string_warning = off;

SET search_path = public, pg_catalog;

SET default_tablespace = '';

SET default_with_oids = false;

--
-- Name: catd01_complemento_variable_primaria; Type: TABLE; Schema: public; Owner: sisap; Tablespace: 
--

CREATE TABLE catd01_complemento_variable_primaria (
    cod_presi integer NOT NULL,
    cod_entidad integer NOT NULL,
    cod_tipo_inst integer NOT NULL,
    cod_inst integer NOT NULL,
    cod_dep integer NOT NULL,
    ano_ordenanza integer NOT NULL,
    cod_tipo character varying(5) NOT NULL,
    cod_variable_principal integer NOT NULL,
    cod_variable_primaria integer NOT NULL,
    denominacion_primaria character varying(100) NOT NULL
);


ALTER TABLE public.catd01_complemento_variable_primaria OWNER TO sisap;

--
-- Name: TABLE catd01_complemento_variable_primaria; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON TABLE catd01_complemento_variable_primaria IS 'Registra complementos de construcción variable principal';


--
-- Name: COLUMN catd01_complemento_variable_primaria.cod_presi; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd01_complemento_variable_primaria.cod_presi IS 'Código de la presidencia';


--
-- Name: COLUMN catd01_complemento_variable_primaria.cod_entidad; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd01_complemento_variable_primaria.cod_entidad IS 'Código de la entidad';


--
-- Name: COLUMN catd01_complemento_variable_primaria.cod_tipo_inst; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd01_complemento_variable_primaria.cod_tipo_inst IS 'Código tipo de Institución';


--
-- Name: COLUMN catd01_complemento_variable_primaria.cod_inst; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd01_complemento_variable_primaria.cod_inst IS 'Código de Institución';


--
-- Name: COLUMN catd01_complemento_variable_primaria.cod_dep; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd01_complemento_variable_primaria.cod_dep IS 'Código de la dependencia';


--
-- Name: COLUMN catd01_complemento_variable_primaria.ano_ordenanza; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd01_complemento_variable_primaria.ano_ordenanza IS 'Año de la ordenanza';


--
-- Name: COLUMN catd01_complemento_variable_primaria.cod_tipo; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd01_complemento_variable_primaria.cod_tipo IS 'Código tipo de construcción';


--
-- Name: COLUMN catd01_complemento_variable_primaria.cod_variable_principal; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd01_complemento_variable_primaria.cod_variable_principal IS 'Código variable principal';


--
-- Name: COLUMN catd01_complemento_variable_primaria.cod_variable_primaria; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd01_complemento_variable_primaria.cod_variable_primaria IS 'Código variable primaria';


--
-- Name: COLUMN catd01_complemento_variable_primaria.denominacion_primaria; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd01_complemento_variable_primaria.denominacion_primaria IS 'Denominación de la variable primaria';


--
-- Name: catd01_complemento_variable_principal; Type: TABLE; Schema: public; Owner: sisap; Tablespace: 
--

CREATE TABLE catd01_complemento_variable_principal (
    cod_presi integer NOT NULL,
    cod_entidad integer NOT NULL,
    cod_tipo_inst integer NOT NULL,
    cod_inst integer NOT NULL,
    cod_dep integer NOT NULL,
    ano_ordenanza integer NOT NULL,
    cod_tipo character varying(5) NOT NULL,
    cod_variable_principal integer NOT NULL,
    denominacion_principal character varying(100) NOT NULL
);


ALTER TABLE public.catd01_complemento_variable_principal OWNER TO sisap;

--
-- Name: TABLE catd01_complemento_variable_principal; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON TABLE catd01_complemento_variable_principal IS 'Registra complementos de construcción variable principal';


--
-- Name: COLUMN catd01_complemento_variable_principal.cod_presi; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd01_complemento_variable_principal.cod_presi IS 'Código de la presidencia';


--
-- Name: COLUMN catd01_complemento_variable_principal.cod_entidad; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd01_complemento_variable_principal.cod_entidad IS 'Código de la entidad';


--
-- Name: COLUMN catd01_complemento_variable_principal.cod_tipo_inst; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd01_complemento_variable_principal.cod_tipo_inst IS 'Código tipo de Institución';


--
-- Name: COLUMN catd01_complemento_variable_principal.cod_inst; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd01_complemento_variable_principal.cod_inst IS 'Código de Institución';


--
-- Name: COLUMN catd01_complemento_variable_principal.cod_dep; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd01_complemento_variable_principal.cod_dep IS 'Código de la dependencia';


--
-- Name: COLUMN catd01_complemento_variable_principal.ano_ordenanza; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd01_complemento_variable_principal.ano_ordenanza IS 'Año de la ordenanza';


--
-- Name: COLUMN catd01_complemento_variable_principal.cod_tipo; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd01_complemento_variable_principal.cod_tipo IS 'Código tipo de construcción';


--
-- Name: COLUMN catd01_complemento_variable_principal.cod_variable_principal; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd01_complemento_variable_principal.cod_variable_principal IS 'Código variable principal';


--
-- Name: COLUMN catd01_complemento_variable_principal.denominacion_principal; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd01_complemento_variable_principal.denominacion_principal IS 'Denominación de la variable principal';


--
-- Name: catd01_complemento_variable_secundaria; Type: TABLE; Schema: public; Owner: sisap; Tablespace: 
--

CREATE TABLE catd01_complemento_variable_secundaria (
    cod_presi integer NOT NULL,
    cod_entidad integer NOT NULL,
    cod_tipo_inst integer NOT NULL,
    cod_inst integer NOT NULL,
    cod_dep integer NOT NULL,
    ano_ordenanza integer NOT NULL,
    cod_tipo character varying(5) NOT NULL,
    cod_variable_principal integer NOT NULL,
    cod_variable_primaria integer NOT NULL,
    cod_variable_secundaria integer NOT NULL,
    denominacion_secundaria character varying(100) NOT NULL,
    monto numeric(26,2)
);


ALTER TABLE public.catd01_complemento_variable_secundaria OWNER TO sisap;

--
-- Name: TABLE catd01_complemento_variable_secundaria; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON TABLE catd01_complemento_variable_secundaria IS 'Registra complementos de construcción variable principal';


--
-- Name: COLUMN catd01_complemento_variable_secundaria.cod_presi; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd01_complemento_variable_secundaria.cod_presi IS 'Código de la presidencia';


--
-- Name: COLUMN catd01_complemento_variable_secundaria.cod_entidad; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd01_complemento_variable_secundaria.cod_entidad IS 'Código de la entidad';


--
-- Name: COLUMN catd01_complemento_variable_secundaria.cod_tipo_inst; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd01_complemento_variable_secundaria.cod_tipo_inst IS 'Código tipo de Institución';


--
-- Name: COLUMN catd01_complemento_variable_secundaria.cod_inst; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd01_complemento_variable_secundaria.cod_inst IS 'Código de Institución';


--
-- Name: COLUMN catd01_complemento_variable_secundaria.cod_dep; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd01_complemento_variable_secundaria.cod_dep IS 'Código de la dependencia';


--
-- Name: COLUMN catd01_complemento_variable_secundaria.ano_ordenanza; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd01_complemento_variable_secundaria.ano_ordenanza IS 'Año de la ordenanza';


--
-- Name: COLUMN catd01_complemento_variable_secundaria.cod_tipo; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd01_complemento_variable_secundaria.cod_tipo IS 'Código tipo de construcción';


--
-- Name: COLUMN catd01_complemento_variable_secundaria.cod_variable_principal; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd01_complemento_variable_secundaria.cod_variable_principal IS 'Código variable principal';


--
-- Name: COLUMN catd01_complemento_variable_secundaria.cod_variable_primaria; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd01_complemento_variable_secundaria.cod_variable_primaria IS 'Código variable secundaria';


--
-- Name: COLUMN catd01_complemento_variable_secundaria.denominacion_secundaria; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd01_complemento_variable_secundaria.denominacion_secundaria IS 'Denominación de la variable secundaria';


--
-- Name: COLUMN catd01_complemento_variable_secundaria.monto; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd01_complemento_variable_secundaria.monto IS 'Monto (+/-)';


--
-- Name: catd01_depreciacion_edificaciones; Type: TABLE; Schema: public; Owner: sisap; Tablespace: 
--

CREATE TABLE catd01_depreciacion_edificaciones (
    cod_presi integer NOT NULL,
    cod_entidad integer NOT NULL,
    cod_tipo_inst integer NOT NULL,
    cod_inst integer NOT NULL,
    cod_dep integer NOT NULL,
    ano_ordenanza integer NOT NULL,
    edad integer NOT NULL,
    factor_excelente numeric(5,2) NOT NULL,
    factor_bueno numeric(5,2) NOT NULL,
    factor_regular numeric(5,2) NOT NULL,
    factor_malo numeric(5,2) NOT NULL
);


ALTER TABLE public.catd01_depreciacion_edificaciones OWNER TO sisap;

--
-- Name: TABLE catd01_depreciacion_edificaciones; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON TABLE catd01_depreciacion_edificaciones IS 'Registro de la tabla de depreciación de edificaciones';


--
-- Name: COLUMN catd01_depreciacion_edificaciones.cod_presi; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd01_depreciacion_edificaciones.cod_presi IS 'Código de la presidencia';


--
-- Name: COLUMN catd01_depreciacion_edificaciones.cod_entidad; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd01_depreciacion_edificaciones.cod_entidad IS 'Código de la entidad';


--
-- Name: COLUMN catd01_depreciacion_edificaciones.cod_tipo_inst; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd01_depreciacion_edificaciones.cod_tipo_inst IS 'Código tipo de Institución';


--
-- Name: COLUMN catd01_depreciacion_edificaciones.cod_inst; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd01_depreciacion_edificaciones.cod_inst IS 'Código de la Institución';


--
-- Name: COLUMN catd01_depreciacion_edificaciones.cod_dep; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd01_depreciacion_edificaciones.cod_dep IS 'Código de la dependencia';


--
-- Name: COLUMN catd01_depreciacion_edificaciones.ano_ordenanza; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd01_depreciacion_edificaciones.ano_ordenanza IS 'Año de la ordenanza';


--
-- Name: COLUMN catd01_depreciacion_edificaciones.edad; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd01_depreciacion_edificaciones.edad IS 'Edad de la edificación';


--
-- Name: COLUMN catd01_depreciacion_edificaciones.factor_excelente; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd01_depreciacion_edificaciones.factor_excelente IS 'Factor excelente';


--
-- Name: COLUMN catd01_depreciacion_edificaciones.factor_bueno; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd01_depreciacion_edificaciones.factor_bueno IS 'Factor bueno';


--
-- Name: COLUMN catd01_depreciacion_edificaciones.factor_regular; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd01_depreciacion_edificaciones.factor_regular IS 'Factor regular';


--
-- Name: COLUMN catd01_depreciacion_edificaciones.factor_malo; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd01_depreciacion_edificaciones.factor_malo IS 'Factor malo';


--
-- Name: catd01_escala_cobro; Type: TABLE; Schema: public; Owner: sisap; Tablespace: 
--

CREATE TABLE catd01_escala_cobro (
    cod_presi integer NOT NULL,
    cod_entidad integer NOT NULL,
    cod_tipo_inst integer NOT NULL,
    cod_inst integer NOT NULL,
    cod_dep integer NOT NULL,
    ano_ordenanza integer NOT NULL,
    escala integer NOT NULL,
    monto_desde numeric(26,2) NOT NULL,
    monto_hasta numeric(26,2) NOT NULL,
    porcentaje numeric(5,2) NOT NULL,
    sustraendo numeric(26,2) DEFAULT 0
);


ALTER TABLE public.catd01_escala_cobro OWNER TO sisap;

--
-- Name: TABLE catd01_escala_cobro; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON TABLE catd01_escala_cobro IS 'Registra la escala de cobro según la ordenanza';


--
-- Name: COLUMN catd01_escala_cobro.cod_presi; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd01_escala_cobro.cod_presi IS 'Código de la presidencia';


--
-- Name: COLUMN catd01_escala_cobro.cod_entidad; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd01_escala_cobro.cod_entidad IS 'Código de la entidad';


--
-- Name: COLUMN catd01_escala_cobro.cod_tipo_inst; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd01_escala_cobro.cod_tipo_inst IS 'Código tipo de Institución';


--
-- Name: COLUMN catd01_escala_cobro.cod_inst; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd01_escala_cobro.cod_inst IS 'Código de la Institución';


--
-- Name: COLUMN catd01_escala_cobro.cod_dep; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd01_escala_cobro.cod_dep IS 'Código de la dependencia';


--
-- Name: COLUMN catd01_escala_cobro.ano_ordenanza; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd01_escala_cobro.ano_ordenanza IS 'Año de la ordenanza';


--
-- Name: COLUMN catd01_escala_cobro.escala; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd01_escala_cobro.escala IS 'Escala';


--
-- Name: COLUMN catd01_escala_cobro.monto_desde; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd01_escala_cobro.monto_desde IS 'Monto desde';


--
-- Name: COLUMN catd01_escala_cobro.monto_hasta; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd01_escala_cobro.monto_hasta IS 'Monto hasta';


--
-- Name: COLUMN catd01_escala_cobro.porcentaje; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd01_escala_cobro.porcentaje IS 'Porcentaje aplicado';


--
-- Name: COLUMN catd01_escala_cobro.sustraendo; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd01_escala_cobro.sustraendo IS 'Sustraendo';


--
-- Name: catd01_planta_valores_tierra; Type: TABLE; Schema: public; Owner: sisap; Tablespace: 
--

CREATE TABLE catd01_planta_valores_tierra (
    cod_presi integer NOT NULL,
    cod_entidad integer NOT NULL,
    cod_tipo_inst integer NOT NULL,
    cod_inst integer NOT NULL,
    cod_dep integer NOT NULL,
    ano_ordenanza integer NOT NULL,
    cod_republica integer NOT NULL,
    cod_estado integer NOT NULL,
    cod_municipio integer NOT NULL,
    cod_parroquia integer NOT NULL,
    cod_zona integer NOT NULL,
    denominacion_zona character varying(100) NOT NULL,
    valor_m2 numeric(26,2) NOT NULL
);


ALTER TABLE public.catd01_planta_valores_tierra OWNER TO sisap;

--
-- Name: TABLE catd01_planta_valores_tierra; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON TABLE catd01_planta_valores_tierra IS 'Registro de planta de valores de la tierra';


--
-- Name: COLUMN catd01_planta_valores_tierra.cod_presi; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd01_planta_valores_tierra.cod_presi IS 'Código de la presidencia';


--
-- Name: COLUMN catd01_planta_valores_tierra.cod_entidad; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd01_planta_valores_tierra.cod_entidad IS 'Código de la entidad';


--
-- Name: COLUMN catd01_planta_valores_tierra.cod_tipo_inst; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd01_planta_valores_tierra.cod_tipo_inst IS 'Código tipo de Institución';


--
-- Name: COLUMN catd01_planta_valores_tierra.cod_inst; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd01_planta_valores_tierra.cod_inst IS 'Código de la Institución';


--
-- Name: COLUMN catd01_planta_valores_tierra.cod_dep; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd01_planta_valores_tierra.cod_dep IS 'Código de la dependencia';


--
-- Name: COLUMN catd01_planta_valores_tierra.ano_ordenanza; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd01_planta_valores_tierra.ano_ordenanza IS 'año de la ordenanza';


--
-- Name: COLUMN catd01_planta_valores_tierra.cod_republica; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd01_planta_valores_tierra.cod_republica IS 'Código de la república';


--
-- Name: COLUMN catd01_planta_valores_tierra.cod_estado; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd01_planta_valores_tierra.cod_estado IS 'Código del estado';


--
-- Name: COLUMN catd01_planta_valores_tierra.cod_municipio; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd01_planta_valores_tierra.cod_municipio IS 'Código del municipio';


--
-- Name: COLUMN catd01_planta_valores_tierra.cod_parroquia; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd01_planta_valores_tierra.cod_parroquia IS 'Código de la parroquia';


--
-- Name: COLUMN catd01_planta_valores_tierra.cod_zona; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd01_planta_valores_tierra.cod_zona IS 'Código de la zona';


--
-- Name: COLUMN catd01_planta_valores_tierra.denominacion_zona; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd01_planta_valores_tierra.denominacion_zona IS 'Denominación de la zona';


--
-- Name: COLUMN catd01_planta_valores_tierra.valor_m2; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd01_planta_valores_tierra.valor_m2 IS 'Valor en bolivares por metro cuadrado';


--
-- Name: catd01_recargos_catastrales; Type: TABLE; Schema: public; Owner: sisap; Tablespace: 
--

CREATE TABLE catd01_recargos_catastrales (
    cod_presi integer NOT NULL,
    cod_entidad integer NOT NULL,
    cod_tipo_inst integer NOT NULL,
    cod_inst integer NOT NULL,
    cod_dep integer NOT NULL,
    ano_ordenanza integer NOT NULL,
    porcentaje_industria numeric(5,2),
    porcentaje_servicios numeric(5,2),
    porcentaje_comercial numeric(5,2),
    porcentaje_arrendado numeric(5,2),
    porcentaje_otro numeric(5,2)
);


ALTER TABLE public.catd01_recargos_catastrales OWNER TO sisap;

--
-- Name: TABLE catd01_recargos_catastrales; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON TABLE catd01_recargos_catastrales IS 'Registro de los recargos catastrales';


--
-- Name: COLUMN catd01_recargos_catastrales.cod_presi; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd01_recargos_catastrales.cod_presi IS 'Código de la presidencia';


--
-- Name: COLUMN catd01_recargos_catastrales.cod_entidad; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd01_recargos_catastrales.cod_entidad IS 'Código de la entidad';


--
-- Name: COLUMN catd01_recargos_catastrales.cod_tipo_inst; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd01_recargos_catastrales.cod_tipo_inst IS 'Código tipo de Institución';


--
-- Name: COLUMN catd01_recargos_catastrales.cod_inst; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd01_recargos_catastrales.cod_inst IS 'Código de la Institución';


--
-- Name: COLUMN catd01_recargos_catastrales.cod_dep; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd01_recargos_catastrales.cod_dep IS 'Código de la dependencia';


--
-- Name: COLUMN catd01_recargos_catastrales.ano_ordenanza; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd01_recargos_catastrales.ano_ordenanza IS 'Año de la ordenanza';


--
-- Name: COLUMN catd01_recargos_catastrales.porcentaje_industria; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd01_recargos_catastrales.porcentaje_industria IS 'Porcentaje por industria';


--
-- Name: COLUMN catd01_recargos_catastrales.porcentaje_servicios; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd01_recargos_catastrales.porcentaje_servicios IS 'Porcentaje por servicios';


--
-- Name: COLUMN catd01_recargos_catastrales.porcentaje_comercial; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd01_recargos_catastrales.porcentaje_comercial IS 'Porcentaje por comercial';


--
-- Name: COLUMN catd01_recargos_catastrales.porcentaje_arrendado; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd01_recargos_catastrales.porcentaje_arrendado IS 'Porcentaje por arrendado';


--
-- Name: COLUMN catd01_recargos_catastrales.porcentaje_otro; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd01_recargos_catastrales.porcentaje_otro IS 'Porcentaje_otro';


--
-- Name: catd01_valor_construccion; Type: TABLE; Schema: public; Owner: sisap; Tablespace: 
--

CREATE TABLE catd01_valor_construccion (
    cod_presi integer NOT NULL,
    cod_entidad integer NOT NULL,
    cod_tipo_inst integer NOT NULL,
    cod_inst integer NOT NULL,
    cod_dep integer NOT NULL,
    ano_ordenanza integer NOT NULL,
    cod_tipo character varying(5) NOT NULL,
    denominacion_tipo character varying(100) NOT NULL,
    valor_m2 numeric(26,2) NOT NULL,
    caracteristicas_basicas text
);


ALTER TABLE public.catd01_valor_construccion OWNER TO sisap;

--
-- Name: TABLE catd01_valor_construccion; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON TABLE catd01_valor_construccion IS 'Registro el tipo y valor de la construcción';


--
-- Name: COLUMN catd01_valor_construccion.cod_presi; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd01_valor_construccion.cod_presi IS 'Código de la presidencia';


--
-- Name: COLUMN catd01_valor_construccion.cod_entidad; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd01_valor_construccion.cod_entidad IS 'Código de la entidad';


--
-- Name: COLUMN catd01_valor_construccion.cod_tipo_inst; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd01_valor_construccion.cod_tipo_inst IS 'Código tipo de Institución';


--
-- Name: COLUMN catd01_valor_construccion.cod_inst; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd01_valor_construccion.cod_inst IS 'Código de Institución';


--
-- Name: COLUMN catd01_valor_construccion.cod_dep; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd01_valor_construccion.cod_dep IS 'Código de la dependencia';


--
-- Name: COLUMN catd01_valor_construccion.ano_ordenanza; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd01_valor_construccion.ano_ordenanza IS 'Año de la ordenanza';


--
-- Name: COLUMN catd01_valor_construccion.cod_tipo; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd01_valor_construccion.cod_tipo IS 'Código tipo de construcción';


--
-- Name: COLUMN catd01_valor_construccion.denominacion_tipo; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd01_valor_construccion.denominacion_tipo IS 'Denominación del tipo de construcción';


--
-- Name: COLUMN catd01_valor_construccion.valor_m2; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd01_valor_construccion.valor_m2 IS 'Valor en bolivares por metro cuadrado';


--
-- Name: COLUMN catd01_valor_construccion.caracteristicas_basicas; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd01_valor_construccion.caracteristicas_basicas IS 'Caracteristicas básicas de la construcción';


--
-- Name: catd02_ficha_datos; Type: TABLE; Schema: public; Owner: sisap; Tablespace: 
--

CREATE TABLE catd02_ficha_datos (
    cod_presi integer NOT NULL,
    cod_entidad integer NOT NULL,
    cod_tipo_inst integer NOT NULL,
    cod_inst integer NOT NULL,
    cod_dep integer NOT NULL,
    cod_ficha integer NOT NULL,
    cod_inscripcion integer NOT NULL,
    fecha_inscripcion date NOT NULL,
    cod_control_archivo integer NOT NULL,
    ano_ordenanza integer NOT NULL,
    cod_ant_edo integer,
    cod_ant_mun integer,
    cod_ant_prr integer,
    cod_ant_sec integer,
    cod_ant_man integer,
    cod_ant_par integer,
    cod_ant_blo integer,
    cod_ant_piso integer,
    cod_ant_apto integer,
    cod_act_edo integer,
    cod_act_mun integer,
    cod_act_prr integer,
    cod_act_amb_t character varying(1),
    cod_act_amb integer,
    cod_act_sec integer,
    cod_act_man integer,
    cod_act_par integer,
    cod_act_sbp integer,
    cod_act_niv integer,
    cod_act_und integer,
    tilde_ciudad integer,
    tilde_localidad integer,
    tilde_urbanizacion integer,
    tilde_conjunto_residencial integer,
    tilde_barrio integer,
    tilde_sector integer,
    nombre character varying(100),
    tilde_av_uno integer,
    tilde_clle_uno integer,
    tilde_crr_uno integer,
    tilde_trav_uno integer,
    tilde_prol_uno integer,
    tilde_crrt_uno integer,
    tilde_cjn_uno integer,
    tilde_psje_uno integer,
    tilde_blv_uno integer,
    tilde_vda_uno integer,
    tilde_esc_uno integer,
    tilde_snd_uno integer,
    tilde_tcal_uno integer,
    tilde_cno_uno integer,
    direccion_uno text,
    tilde_av_dos integer,
    tilde_clle_dos integer,
    tilde_crr_dos integer,
    tilde_trav_dos integer,
    tilde_prol_dos integer,
    tilde_crrt_dos integer,
    tilde_cjn_dos integer,
    tilde_psje_dos integer,
    tilde_blv_dos integer,
    tilde_vda_dos integer,
    tilde_esc_dos integer,
    tilde_snd_dos integer,
    tilde_tcal_dos integer,
    tilde_cno_dos integer,
    direccion_dos text,
    tilde_av_tres integer,
    tilde_clle_tres integer,
    tilde_crr_tres integer,
    tilde_trav_tres integer,
    tilde_prol_tres integer,
    tilde_crrt_tres integer,
    tilde_cjn_tres integer,
    tilde_psje_tres integer,
    tilde_blv_tres integer,
    tilde_vda_tres integer,
    tilde_esc_tres integer,
    tilde_snd_tres integer,
    tilde_tcal_tres integer,
    tilde_cno_tres integer,
    direccion_tres text,
    tilde_edif_uno integer,
    tilde_apto_uno integer,
    tilde_qta_uno integer,
    tilde_casa_uno integer,
    tilde_rancho_uno integer,
    tilde_cc_uno integer,
    tilde_local_uno integer,
    tilde_oficina_uno integer,
    tilde_otro_uno integer,
    tipo_vivienda text,
    nombre_inmueble text NOT NULL,
    numero_civico character varying(30) NOT NULL,
    telefono_inmueble character varying(50),
    punto_referencia_inmueble text,
    personalidad_juridica integer NOT NULL,
    cedula_rif character varying(12) NOT NULL,
    nombre_ocupante text NOT NULL,
    localidad_ocupante character varying(100) NOT NULL,
    urb_barrio_sector_ocupante character varying(100),
    tilde_av_cuatro integer,
    tilde_clle_cuatro integer,
    tilde_crr_cuatro integer,
    tilde_trav_cuatro integer,
    tilde_prol_cuatro integer,
    tilde_crrt_cuatro integer,
    tilde_cjn_cuatro integer,
    tilde_psje_cuatro integer,
    tilde_blv_cuatro integer,
    tilde_vda_cuatro integer,
    tilde_esc_cuatro integer,
    tilde_snd_cuatro integer,
    tilde_tcal_cuatro integer,
    tilde_cno_cuatro integer,
    direccion_cuatro text,
    tilde_av_cinco integer,
    tilde_clle_cinco integer,
    tilde_crr_cinco integer,
    tilde_trav_cinco integer,
    tilde_prol_cinco integer,
    tilde_crrt_cinco integer,
    tilde_cjn_cinco integer,
    tilde_psje_cinco integer,
    tilde_blv_cinco integer,
    tilde_vda_cinco integer,
    tilde_esc_cinco integer,
    tilde_snd_cinco integer,
    tilde_tcal_cinco integer,
    tilde_cno_cinco integer,
    direccion_cinco text,
    tilde_av_seis integer,
    tilde_clle_seis integer,
    tilde_crr_seis integer,
    tilde_trav_seis integer,
    tilde_prol_seis integer,
    tilde_crrt_seis integer,
    tilde_cjn_seis integer,
    tilde_psje_seis integer,
    tilde_blv_seis integer,
    tilde_vda_seis integer,
    tilde_esc_seis integer,
    tilde_snd_seis integer,
    tilde_tcal_seis integer,
    tilde_cno_seis integer,
    direccion_seis text,
    tilde_edif_dos integer,
    tilde_apto_dos integer,
    tilde_qta_dos integer,
    tilde_casa_dos integer,
    tilde_rancho_dos integer,
    tilde_cc_dos integer,
    tilde_local_dos integer,
    tilde_oficina_dos integer,
    tilde_otro_dos integer,
    nombre_repre character varying(100) NOT NULL,
    numero_civico_repre character varying(30),
    telefono_repre character varying(40),
    punto_referencia_repre text,
    tilde_topo character varying(7),
    tilde_acceso character varying(6),
    tilde_forma character varying(3),
    tilde_ubica character varying(3),
    tilde_entorno character varying(5),
    tilde_mejora character varying(6),
    tilde_tenencia character varying(9),
    tilde_regimen character varying(8),
    tilde_uso character varying(19),
    tilde_servicio character varying(20),
    tilde_tipo character varying(20),
    tilde_descripcionuso character varying(11),
    tilde_tenencia_const character varying(11),
    tilde_regi_prop character varying(6),
    tilde_soporte character varying(7),
    tilde_techo character varying(6),
    tilde_cubierta character varying(10),
    tilde_pared_tipo character varying(11),
    tilde_pared_acaba character varying(5),
    tilde_conserva character varying(4),
    ano_construccion integer,
    porce_refaccion numeric(5,2),
    numero_niveles integer,
    ano_refaccion integer,
    edad_efectiva integer,
    numero_edificaciones integer,
    registro_numero character varying(10),
    registro_folio character varying(10),
    registro_tomo character varying(10),
    registro_protocolo character varying(10),
    registro_fecha date,
    registro_area_terreno numeric(8,2),
    registro_area_construccion numeric(8,2),
    registro_monto numeric(26,2),
    valoracion_area numeric(8,2),
    valoracion_valor_unitario numeric(26,2),
    valoracion_sector integer,
    valoracion_ajuste_area numeric(8,2),
    valoracion_ajuste_forma numeric(8,2),
    valoracion_valor_ajustado numeric(26,2),
    valoracion_valor_total numeric(26,2),
    observaciones_ficha text,
    lindero_norte text,
    lindero_sur text,
    lindero_este text,
    lindero_oeste text,
    coordenada_norte character varying(30),
    coordenada_este character varying(30),
    huso character varying(30),
    observaciones_generales text,
    fecha_primera_visita date,
    fecha_levantamiento date,
    elaborado_nombre character varying(100),
    elaborado_ci integer,
    revisado_nombre character varying(100),
    revisado_ci integer,
    croquis bytea
);


ALTER TABLE public.catd02_ficha_datos OWNER TO sisap;

--
-- Name: TABLE catd02_ficha_datos; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON TABLE catd02_ficha_datos IS 'Registra la ficha catastral';


--
-- Name: COLUMN catd02_ficha_datos.cod_ficha; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_datos.cod_ficha IS 'Número de la ficha catastral';


--
-- Name: COLUMN catd02_ficha_datos.cod_inscripcion; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_datos.cod_inscripcion IS 'Número de Inscripción catastral';


--
-- Name: COLUMN catd02_ficha_datos.fecha_inscripcion; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_datos.fecha_inscripcion IS 'Fecha de Inscripción';


--
-- Name: COLUMN catd02_ficha_datos.cod_control_archivo; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_datos.cod_control_archivo IS 'Número control de archivo';


--
-- Name: COLUMN catd02_ficha_datos.ano_ordenanza; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_datos.ano_ordenanza IS 'Año de la ordenanza';


--
-- Name: COLUMN catd02_ficha_datos.cod_ant_edo; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_datos.cod_ant_edo IS 'Código anterior Estado';


--
-- Name: COLUMN catd02_ficha_datos.cod_ant_mun; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_datos.cod_ant_mun IS 'Código anterior Municipio';


--
-- Name: COLUMN catd02_ficha_datos.cod_ant_prr; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_datos.cod_ant_prr IS 'Código anterior Parroquia';


--
-- Name: COLUMN catd02_ficha_datos.cod_ant_sec; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_datos.cod_ant_sec IS 'Código anterior Sector';


--
-- Name: COLUMN catd02_ficha_datos.cod_ant_man; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_datos.cod_ant_man IS 'Código anterior Manzana';


--
-- Name: COLUMN catd02_ficha_datos.cod_ant_par; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_datos.cod_ant_par IS 'Código anterior Parcela';


--
-- Name: COLUMN catd02_ficha_datos.cod_ant_blo; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_datos.cod_ant_blo IS 'Código anterior Bloque';


--
-- Name: COLUMN catd02_ficha_datos.cod_ant_piso; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_datos.cod_ant_piso IS 'Código anterior Piso';


--
-- Name: COLUMN catd02_ficha_datos.cod_ant_apto; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_datos.cod_ant_apto IS 'Código anterior Apartamento';


--
-- Name: COLUMN catd02_ficha_datos.cod_act_edo; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_datos.cod_act_edo IS 'Código actual Estado';


--
-- Name: COLUMN catd02_ficha_datos.cod_act_mun; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_datos.cod_act_mun IS 'Código actual Municipio';


--
-- Name: COLUMN catd02_ficha_datos.cod_act_prr; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_datos.cod_act_prr IS 'Código actual Parroquia';


--
-- Name: COLUMN catd02_ficha_datos.cod_act_amb_t; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_datos.cod_act_amb_t IS 'Código actual tipo ambito
"U" Urbano
"R" Rural';


--
-- Name: COLUMN catd02_ficha_datos.cod_act_amb; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_datos.cod_act_amb IS 'Código actual Ambito';


--
-- Name: COLUMN catd02_ficha_datos.cod_act_sec; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_datos.cod_act_sec IS 'Código actual Sector';


--
-- Name: COLUMN catd02_ficha_datos.cod_act_man; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_datos.cod_act_man IS 'Código actual Manzana';


--
-- Name: COLUMN catd02_ficha_datos.cod_act_par; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_datos.cod_act_par IS 'Código actual Parcela';


--
-- Name: COLUMN catd02_ficha_datos.cod_act_sbp; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_datos.cod_act_sbp IS 'Código actual Subpartida';


--
-- Name: COLUMN catd02_ficha_datos.cod_act_niv; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_datos.cod_act_niv IS 'Código actual Nivel';


--
-- Name: COLUMN catd02_ficha_datos.cod_act_und; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_datos.cod_act_und IS 'Código actual Unidad';


--
-- Name: COLUMN catd02_ficha_datos.tilde_ciudad; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_datos.tilde_ciudad IS 'Direccion del inmueble - Ciudad';


--
-- Name: COLUMN catd02_ficha_datos.tilde_localidad; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_datos.tilde_localidad IS 'Dirección del inmueble - Localidad';


--
-- Name: COLUMN catd02_ficha_datos.tilde_urbanizacion; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_datos.tilde_urbanizacion IS 'Dirección del Inmueble - Urbanización';


--
-- Name: COLUMN catd02_ficha_datos.tilde_conjunto_residencial; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_datos.tilde_conjunto_residencial IS 'Dirección del Inmueble - Conjunto residencial';


--
-- Name: COLUMN catd02_ficha_datos.tilde_barrio; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_datos.tilde_barrio IS 'Dirección del Inmueble - Barrio';


--
-- Name: COLUMN catd02_ficha_datos.tilde_sector; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_datos.tilde_sector IS 'Dirección del Inmueble - Sector';


--
-- Name: COLUMN catd02_ficha_datos.nombre; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_datos.nombre IS 'Nombre de la Ciudad, Localidad, Urbanización, Conjunto residencial, Barrio o Sector';


--
-- Name: COLUMN catd02_ficha_datos.tilde_av_uno; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_datos.tilde_av_uno IS 'Avenida';


--
-- Name: COLUMN catd02_ficha_datos.tilde_clle_uno; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_datos.tilde_clle_uno IS 'Calle';


--
-- Name: COLUMN catd02_ficha_datos.tilde_crr_uno; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_datos.tilde_crr_uno IS 'Crr';


--
-- Name: COLUMN catd02_ficha_datos.tilde_trav_uno; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_datos.tilde_trav_uno IS 'Trav';


--
-- Name: COLUMN catd02_ficha_datos.tilde_prol_uno; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_datos.tilde_prol_uno IS 'Prol';


--
-- Name: COLUMN catd02_ficha_datos.tilde_crrt_uno; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_datos.tilde_crrt_uno IS 'Crrt';


--
-- Name: COLUMN catd02_ficha_datos.tilde_cjn_uno; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_datos.tilde_cjn_uno IS 'Cjn';


--
-- Name: COLUMN catd02_ficha_datos.tilde_psje_uno; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_datos.tilde_psje_uno IS 'Psje';


--
-- Name: COLUMN catd02_ficha_datos.tilde_blv_uno; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_datos.tilde_blv_uno IS 'Blv';


--
-- Name: COLUMN catd02_ficha_datos.tilde_vda_uno; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_datos.tilde_vda_uno IS 'Vda';


--
-- Name: COLUMN catd02_ficha_datos.tilde_esc_uno; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_datos.tilde_esc_uno IS 'Esc';


--
-- Name: COLUMN catd02_ficha_datos.tilde_snd_uno; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_datos.tilde_snd_uno IS 'Snd';


--
-- Name: COLUMN catd02_ficha_datos.tilde_tcal_uno; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_datos.tilde_tcal_uno IS 'Tcal';


--
-- Name: COLUMN catd02_ficha_datos.tilde_cno_uno; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_datos.tilde_cno_uno IS 'Cno';


--
-- Name: COLUMN catd02_ficha_datos.direccion_uno; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_datos.direccion_uno IS 'Primera dirección';


--
-- Name: COLUMN catd02_ficha_datos.tilde_av_dos; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_datos.tilde_av_dos IS 'Avenida';


--
-- Name: COLUMN catd02_ficha_datos.tilde_clle_dos; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_datos.tilde_clle_dos IS 'Calle';


--
-- Name: COLUMN catd02_ficha_datos.tilde_crr_dos; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_datos.tilde_crr_dos IS 'Crr';


--
-- Name: COLUMN catd02_ficha_datos.tilde_trav_dos; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_datos.tilde_trav_dos IS 'Trav';


--
-- Name: COLUMN catd02_ficha_datos.tilde_prol_dos; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_datos.tilde_prol_dos IS 'Prol';


--
-- Name: COLUMN catd02_ficha_datos.tilde_crrt_dos; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_datos.tilde_crrt_dos IS 'Crrt';


--
-- Name: COLUMN catd02_ficha_datos.tilde_cjn_dos; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_datos.tilde_cjn_dos IS 'Cjn';


--
-- Name: COLUMN catd02_ficha_datos.tilde_psje_dos; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_datos.tilde_psje_dos IS 'Psje';


--
-- Name: COLUMN catd02_ficha_datos.tilde_blv_dos; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_datos.tilde_blv_dos IS 'Blv';


--
-- Name: COLUMN catd02_ficha_datos.tilde_vda_dos; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_datos.tilde_vda_dos IS 'Vda';


--
-- Name: COLUMN catd02_ficha_datos.tilde_esc_dos; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_datos.tilde_esc_dos IS 'Esc';


--
-- Name: COLUMN catd02_ficha_datos.tilde_snd_dos; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_datos.tilde_snd_dos IS 'Snd';


--
-- Name: COLUMN catd02_ficha_datos.tilde_tcal_dos; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_datos.tilde_tcal_dos IS 'Tcal';


--
-- Name: COLUMN catd02_ficha_datos.tilde_cno_dos; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_datos.tilde_cno_dos IS 'Cno';


--
-- Name: COLUMN catd02_ficha_datos.direccion_dos; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_datos.direccion_dos IS 'Segunda dirección';


--
-- Name: COLUMN catd02_ficha_datos.tilde_av_tres; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_datos.tilde_av_tres IS 'Avenida';


--
-- Name: COLUMN catd02_ficha_datos.tilde_clle_tres; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_datos.tilde_clle_tres IS 'Calle';


--
-- Name: COLUMN catd02_ficha_datos.tilde_crr_tres; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_datos.tilde_crr_tres IS 'Crr';


--
-- Name: COLUMN catd02_ficha_datos.tilde_trav_tres; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_datos.tilde_trav_tres IS 'Trav';


--
-- Name: COLUMN catd02_ficha_datos.tilde_prol_tres; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_datos.tilde_prol_tres IS 'Prol';


--
-- Name: COLUMN catd02_ficha_datos.tilde_crrt_tres; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_datos.tilde_crrt_tres IS 'Crrt';


--
-- Name: COLUMN catd02_ficha_datos.tilde_cjn_tres; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_datos.tilde_cjn_tres IS 'Cjn';


--
-- Name: COLUMN catd02_ficha_datos.tilde_psje_tres; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_datos.tilde_psje_tres IS 'Psje';


--
-- Name: COLUMN catd02_ficha_datos.tilde_blv_tres; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_datos.tilde_blv_tres IS 'Blv';


--
-- Name: COLUMN catd02_ficha_datos.tilde_vda_tres; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_datos.tilde_vda_tres IS 'Vda';


--
-- Name: COLUMN catd02_ficha_datos.tilde_esc_tres; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_datos.tilde_esc_tres IS 'Esc';


--
-- Name: COLUMN catd02_ficha_datos.tilde_snd_tres; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_datos.tilde_snd_tres IS 'Snd';


--
-- Name: COLUMN catd02_ficha_datos.tilde_tcal_tres; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_datos.tilde_tcal_tres IS 'Tcal';


--
-- Name: COLUMN catd02_ficha_datos.tilde_cno_tres; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_datos.tilde_cno_tres IS 'Cno';


--
-- Name: COLUMN catd02_ficha_datos.direccion_tres; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_datos.direccion_tres IS 'Tercera dirección';


--
-- Name: COLUMN catd02_ficha_datos.tilde_edif_uno; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_datos.tilde_edif_uno IS 'Edificio';


--
-- Name: COLUMN catd02_ficha_datos.tilde_apto_uno; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_datos.tilde_apto_uno IS 'Apartamento';


--
-- Name: COLUMN catd02_ficha_datos.tilde_qta_uno; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_datos.tilde_qta_uno IS 'Quinta';


--
-- Name: COLUMN catd02_ficha_datos.tilde_casa_uno; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_datos.tilde_casa_uno IS 'Casa';


--
-- Name: COLUMN catd02_ficha_datos.tilde_rancho_uno; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_datos.tilde_rancho_uno IS 'Rancho';


--
-- Name: COLUMN catd02_ficha_datos.tilde_cc_uno; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_datos.tilde_cc_uno IS 'Centro comercial';


--
-- Name: COLUMN catd02_ficha_datos.tilde_local_uno; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_datos.tilde_local_uno IS 'Local comercial';


--
-- Name: COLUMN catd02_ficha_datos.tilde_oficina_uno; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_datos.tilde_oficina_uno IS 'Oficina';


--
-- Name: COLUMN catd02_ficha_datos.tilde_otro_uno; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_datos.tilde_otro_uno IS 'Otro tipo de vivivienda';


--
-- Name: COLUMN catd02_ficha_datos.tipo_vivienda; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_datos.tipo_vivienda IS 'Tipo de vivienda';


--
-- Name: COLUMN catd02_ficha_datos.nombre_inmueble; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_datos.nombre_inmueble IS 'Nombre del inmueble';


--
-- Name: COLUMN catd02_ficha_datos.numero_civico; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_datos.numero_civico IS 'Número cívico';


--
-- Name: COLUMN catd02_ficha_datos.telefono_inmueble; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_datos.telefono_inmueble IS 'Teléfono del inmueble';


--
-- Name: COLUMN catd02_ficha_datos.punto_referencia_inmueble; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_datos.punto_referencia_inmueble IS 'Punto de referencia del inmueble';


--
-- Name: COLUMN catd02_ficha_datos.personalidad_juridica; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_datos.personalidad_juridica IS 'Personalidad Juridica
1.- Natural
2.- Juridica';


--
-- Name: COLUMN catd02_ficha_datos.cedula_rif; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_datos.cedula_rif IS 'Cédula de Identidad o Rif';


--
-- Name: COLUMN catd02_ficha_datos.nombre_ocupante; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_datos.nombre_ocupante IS 'Apellidos y Nombres / Razón social';


--
-- Name: COLUMN catd02_ficha_datos.localidad_ocupante; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_datos.localidad_ocupante IS 'Ciudad o Localidad del ocupante';


--
-- Name: COLUMN catd02_ficha_datos.urb_barrio_sector_ocupante; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_datos.urb_barrio_sector_ocupante IS 'Urbanización, barrio, sector del ocupante';


--
-- Name: COLUMN catd02_ficha_datos.tilde_av_cuatro; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_datos.tilde_av_cuatro IS 'Avenida';


--
-- Name: COLUMN catd02_ficha_datos.tilde_clle_cuatro; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_datos.tilde_clle_cuatro IS 'Calle';


--
-- Name: COLUMN catd02_ficha_datos.tilde_crr_cuatro; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_datos.tilde_crr_cuatro IS 'Crr';


--
-- Name: COLUMN catd02_ficha_datos.tilde_trav_cuatro; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_datos.tilde_trav_cuatro IS 'Trav';


--
-- Name: COLUMN catd02_ficha_datos.tilde_prol_cuatro; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_datos.tilde_prol_cuatro IS 'Prol';


--
-- Name: COLUMN catd02_ficha_datos.tilde_crrt_cuatro; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_datos.tilde_crrt_cuatro IS 'Crrt';


--
-- Name: COLUMN catd02_ficha_datos.tilde_cjn_cuatro; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_datos.tilde_cjn_cuatro IS 'Cjn';


--
-- Name: COLUMN catd02_ficha_datos.tilde_psje_cuatro; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_datos.tilde_psje_cuatro IS 'Psje';


--
-- Name: COLUMN catd02_ficha_datos.tilde_blv_cuatro; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_datos.tilde_blv_cuatro IS 'Blv';


--
-- Name: COLUMN catd02_ficha_datos.tilde_vda_cuatro; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_datos.tilde_vda_cuatro IS 'Vda';


--
-- Name: COLUMN catd02_ficha_datos.tilde_esc_cuatro; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_datos.tilde_esc_cuatro IS 'Esc';


--
-- Name: COLUMN catd02_ficha_datos.tilde_snd_cuatro; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_datos.tilde_snd_cuatro IS 'Snd';


--
-- Name: COLUMN catd02_ficha_datos.tilde_tcal_cuatro; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_datos.tilde_tcal_cuatro IS 'Tcal';


--
-- Name: COLUMN catd02_ficha_datos.tilde_cno_cuatro; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_datos.tilde_cno_cuatro IS 'Cno';


--
-- Name: COLUMN catd02_ficha_datos.direccion_cuatro; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_datos.direccion_cuatro IS 'Cuatro dirección del ocupante';


--
-- Name: COLUMN catd02_ficha_datos.tilde_av_cinco; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_datos.tilde_av_cinco IS 'Avenida';


--
-- Name: COLUMN catd02_ficha_datos.tilde_clle_cinco; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_datos.tilde_clle_cinco IS 'Calle';


--
-- Name: COLUMN catd02_ficha_datos.tilde_crr_cinco; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_datos.tilde_crr_cinco IS 'Crr';


--
-- Name: COLUMN catd02_ficha_datos.tilde_trav_cinco; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_datos.tilde_trav_cinco IS 'Trav';


--
-- Name: COLUMN catd02_ficha_datos.tilde_prol_cinco; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_datos.tilde_prol_cinco IS 'Prol';


--
-- Name: COLUMN catd02_ficha_datos.tilde_crrt_cinco; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_datos.tilde_crrt_cinco IS 'Crrt';


--
-- Name: COLUMN catd02_ficha_datos.tilde_cjn_cinco; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_datos.tilde_cjn_cinco IS 'Cjn';


--
-- Name: COLUMN catd02_ficha_datos.tilde_psje_cinco; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_datos.tilde_psje_cinco IS 'Psje';


--
-- Name: COLUMN catd02_ficha_datos.tilde_blv_cinco; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_datos.tilde_blv_cinco IS 'Blv';


--
-- Name: COLUMN catd02_ficha_datos.tilde_vda_cinco; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_datos.tilde_vda_cinco IS 'Vda';


--
-- Name: COLUMN catd02_ficha_datos.tilde_esc_cinco; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_datos.tilde_esc_cinco IS 'Esc';


--
-- Name: COLUMN catd02_ficha_datos.tilde_snd_cinco; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_datos.tilde_snd_cinco IS 'Snd';


--
-- Name: COLUMN catd02_ficha_datos.tilde_tcal_cinco; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_datos.tilde_tcal_cinco IS 'Tcal';


--
-- Name: COLUMN catd02_ficha_datos.tilde_cno_cinco; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_datos.tilde_cno_cinco IS 'Cno';


--
-- Name: COLUMN catd02_ficha_datos.direccion_cinco; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_datos.direccion_cinco IS 'Cinco dirección del ocupante';


--
-- Name: COLUMN catd02_ficha_datos.tilde_av_seis; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_datos.tilde_av_seis IS 'Avenida';


--
-- Name: COLUMN catd02_ficha_datos.tilde_clle_seis; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_datos.tilde_clle_seis IS 'Calle';


--
-- Name: COLUMN catd02_ficha_datos.tilde_crr_seis; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_datos.tilde_crr_seis IS 'Crr';


--
-- Name: COLUMN catd02_ficha_datos.tilde_trav_seis; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_datos.tilde_trav_seis IS 'Trav';


--
-- Name: COLUMN catd02_ficha_datos.tilde_prol_seis; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_datos.tilde_prol_seis IS 'Prol';


--
-- Name: COLUMN catd02_ficha_datos.tilde_crrt_seis; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_datos.tilde_crrt_seis IS 'Crrt';


--
-- Name: COLUMN catd02_ficha_datos.tilde_cjn_seis; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_datos.tilde_cjn_seis IS 'Cjn';


--
-- Name: COLUMN catd02_ficha_datos.tilde_psje_seis; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_datos.tilde_psje_seis IS 'Psje';


--
-- Name: COLUMN catd02_ficha_datos.tilde_blv_seis; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_datos.tilde_blv_seis IS 'Blv';


--
-- Name: COLUMN catd02_ficha_datos.tilde_vda_seis; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_datos.tilde_vda_seis IS 'Vda';


--
-- Name: COLUMN catd02_ficha_datos.tilde_esc_seis; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_datos.tilde_esc_seis IS 'Esc';


--
-- Name: COLUMN catd02_ficha_datos.tilde_snd_seis; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_datos.tilde_snd_seis IS 'Snd';


--
-- Name: COLUMN catd02_ficha_datos.tilde_tcal_seis; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_datos.tilde_tcal_seis IS 'Tcal';


--
-- Name: COLUMN catd02_ficha_datos.tilde_cno_seis; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_datos.tilde_cno_seis IS 'Cno';


--
-- Name: COLUMN catd02_ficha_datos.direccion_seis; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_datos.direccion_seis IS 'Seis dirección del ocupante';


--
-- Name: COLUMN catd02_ficha_datos.tilde_edif_dos; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_datos.tilde_edif_dos IS 'Edificio';


--
-- Name: COLUMN catd02_ficha_datos.tilde_apto_dos; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_datos.tilde_apto_dos IS 'Apartamento';


--
-- Name: COLUMN catd02_ficha_datos.tilde_qta_dos; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_datos.tilde_qta_dos IS 'Quinta';


--
-- Name: COLUMN catd02_ficha_datos.tilde_casa_dos; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_datos.tilde_casa_dos IS 'Casa';


--
-- Name: COLUMN catd02_ficha_datos.tilde_rancho_dos; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_datos.tilde_rancho_dos IS 'Rancho';


--
-- Name: COLUMN catd02_ficha_datos.tilde_cc_dos; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_datos.tilde_cc_dos IS 'Centro comercial';


--
-- Name: COLUMN catd02_ficha_datos.tilde_local_dos; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_datos.tilde_local_dos IS 'Local comercial';


--
-- Name: COLUMN catd02_ficha_datos.tilde_oficina_dos; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_datos.tilde_oficina_dos IS 'Oficina';


--
-- Name: COLUMN catd02_ficha_datos.tilde_otro_dos; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_datos.tilde_otro_dos IS 'Otro tipo de vivivienda';


--
-- Name: COLUMN catd02_ficha_datos.nombre_repre; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_datos.nombre_repre IS 'Nombre del representante legal';


--
-- Name: COLUMN catd02_ficha_datos.numero_civico_repre; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_datos.numero_civico_repre IS 'Número cívico del representante legal';


--
-- Name: COLUMN catd02_ficha_datos.telefono_repre; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_datos.telefono_repre IS 'Teléfonos del representante';


--
-- Name: COLUMN catd02_ficha_datos.punto_referencia_repre; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_datos.punto_referencia_repre IS 'Punto de referencia del representante legal';


--
-- Name: COLUMN catd02_ficha_datos.tilde_topo; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_datos.tilde_topo IS 'Topografia plana, sobre nivel, bajo nivel, corte, relleno, inclinado, irregular';


--
-- Name: COLUMN catd02_ficha_datos.tilde_acceso; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_datos.tilde_acceso IS 'Acceso calle pavimentada, engrazonada, de tierra,escalera pavimentada,escalera de tierra,otro';


--
-- Name: COLUMN catd02_ficha_datos.tilde_forma; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_datos.tilde_forma IS 'Forma regular,irregular,muy irregular';


--
-- Name: COLUMN catd02_ficha_datos.tilde_ubica; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_datos.tilde_ubica IS 'Ubicación convencional,esquina,interior manzana';


--
-- Name: COLUMN catd02_ficha_datos.tilde_entorno; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_datos.tilde_entorno IS 'Entorno fisico zona urbanizada, no urbanizada,rio o quebrada,barranco o talud,otro';


--
-- Name: COLUMN catd02_ficha_datos.tilde_mejora; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_datos.tilde_mejora IS 'Mejoras del terreno muro de contención,nivelación,cercado, pozo séptico,lagunas artificiales,otro';


--
-- Name: COLUMN catd02_ficha_datos.tilde_tenencia; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_datos.tilde_tenencia IS 'Tenencia del terreno propiedad,arrendamiento,comodato,anticresis,enfiteusis,usufructo,derecho de uso,derecho de hab,otro';


--
-- Name: COLUMN catd02_ficha_datos.tilde_regimen; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_datos.tilde_regimen IS 'Regimen de propiedad ejido,municipal propio,nacional,baldio,estatal,privado industrial,privado condominio,otro';


--
-- Name: COLUMN catd02_ficha_datos.tilde_uso; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_datos.tilde_uso IS 'Uso actual residencial,comercial,industrial,recreativo / deportivo,asistencial / salud,educacional,turistico,social / cultural,gubernamental / institucional,religioso,pesquero,agroindustrial,agroforestal,agricola,pecuario,forestal,minero,sin uso,otro';


--
-- Name: COLUMN catd02_ficha_datos.tilde_servicio; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_datos.tilde_servicio IS 'Servicios públicos acueducto,cloacas,drenaje artificial,electricidad residencial,electricidad industrial,alumbrado,vialidad,pavimento,acera,transporte,telefono,cobertura celular,cable tv,correo y telégrafo,gas,aseo,escuelas,medicatura,riego,otro';


--
-- Name: COLUMN catd02_ficha_datos.tilde_tipo; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_datos.tilde_tipo IS 'Tipo quinta,casa quinta,chalet,tonw house,casa tradicional,casa convencional,casa economica,rancho,edificio,apartamento,centro comercial,local comercial,galpón,vaqueras,cochineras,corrales y anexos,bebederos,comederos,tanques,otros';


--
-- Name: COLUMN catd02_ficha_datos.tilde_descripcionuso; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_datos.tilde_descripcionuso IS 'Descripción de uso unifamiliar,bifamiliar,multifamiliar,comercio al detal,comercio al mayor,mercado libre,oficinas,industrial,servicio,agropecuario,otro';


--
-- Name: COLUMN catd02_ficha_datos.tilde_tenencia_const; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_datos.tilde_tenencia_const IS 'Tenencia construcción propiedad,arrendamiento,comodato,anticresis,enfiteusis,usufructo,derecho de uso,derecho de habitación,otro';


--
-- Name: COLUMN catd02_ficha_datos.tilde_regi_prop; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_datos.tilde_regi_prop IS 'Regimen de propiedad municipal propio,nacional,estatal,privada individual,privado condominio,otro';


--
-- Name: COLUMN catd02_ficha_datos.tilde_soporte; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_datos.tilde_soporte IS 'Estructura soporte armado,metalica,madera,paredes de carga,prefabricado,machones,otro';


--
-- Name: COLUMN catd02_ficha_datos.tilde_techo; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_datos.tilde_techo IS 'Estructura techo concreto armado,metalica,madera,varas,cerchas,otro';


--
-- Name: COLUMN catd02_ficha_datos.tilde_cubierta; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_datos.tilde_cubierta IS 'Cubierta madera teja,placa teja,platabanda,caña brava,asbesto,aluminio,zinc,acerolit,palma,otro';


--
-- Name: COLUMN catd02_ficha_datos.tilde_pared_tipo; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_datos.tilde_pared_tipo IS 'Paredes tipo bloque de cemento,bloque de arcilla,ladrillo,adobe,tapia,bahareque,prefabricada,vidrio,madera aserrada,sin paredes,otro';


--
-- Name: COLUMN catd02_ficha_datos.tilde_pared_acaba; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_datos.tilde_pared_acaba IS 'Estructura paredes acabado friso liso,friso rustico,sin friso,obra limpia,otro';


--
-- Name: COLUMN catd02_ficha_datos.tilde_conserva; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_datos.tilde_conserva IS 'Estado de conservacion excelente,buena,regular,malo';


--
-- Name: COLUMN catd02_ficha_datos.ano_construccion; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_datos.ano_construccion IS 'Año de construcción';


--
-- Name: COLUMN catd02_ficha_datos.porce_refaccion; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_datos.porce_refaccion IS 'Porcentaje de refacción';


--
-- Name: COLUMN catd02_ficha_datos.numero_niveles; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_datos.numero_niveles IS 'Número de niveles';


--
-- Name: COLUMN catd02_ficha_datos.ano_refaccion; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_datos.ano_refaccion IS 'Año de refacción';


--
-- Name: COLUMN catd02_ficha_datos.edad_efectiva; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_datos.edad_efectiva IS 'Edad efectiva';


--
-- Name: COLUMN catd02_ficha_datos.numero_edificaciones; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_datos.numero_edificaciones IS 'Número de edificaciones';


--
-- Name: COLUMN catd02_ficha_datos.registro_numero; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_datos.registro_numero IS 'Registro público número';


--
-- Name: COLUMN catd02_ficha_datos.registro_folio; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_datos.registro_folio IS 'Registro público folio';


--
-- Name: COLUMN catd02_ficha_datos.registro_tomo; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_datos.registro_tomo IS 'Registro público tomo';


--
-- Name: COLUMN catd02_ficha_datos.registro_protocolo; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_datos.registro_protocolo IS 'Registro público protocolo';


--
-- Name: COLUMN catd02_ficha_datos.registro_fecha; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_datos.registro_fecha IS 'Registro público fecha';


--
-- Name: COLUMN catd02_ficha_datos.registro_area_terreno; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_datos.registro_area_terreno IS 'Registro público area terreno';


--
-- Name: COLUMN catd02_ficha_datos.registro_area_construccion; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_datos.registro_area_construccion IS 'Registro público area construcción';


--
-- Name: COLUMN catd02_ficha_datos.registro_monto; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_datos.registro_monto IS 'Registro público monto';


--
-- Name: COLUMN catd02_ficha_datos.valoracion_area; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_datos.valoracion_area IS 'Valoración economica area';


--
-- Name: COLUMN catd02_ficha_datos.valoracion_valor_unitario; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_datos.valoracion_valor_unitario IS 'Valoración economica valor unitario';


--
-- Name: COLUMN catd02_ficha_datos.valoracion_sector; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_datos.valoracion_sector IS 'Valoracion economica sector';


--
-- Name: COLUMN catd02_ficha_datos.valoracion_ajuste_area; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_datos.valoracion_ajuste_area IS 'Valoración economica factor ajuste area';


--
-- Name: COLUMN catd02_ficha_datos.valoracion_ajuste_forma; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_datos.valoracion_ajuste_forma IS 'Valoración economica factor ajuste forma';


--
-- Name: COLUMN catd02_ficha_datos.valoracion_valor_ajustado; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_datos.valoracion_valor_ajustado IS 'Valoración economica valor ajustado';


--
-- Name: COLUMN catd02_ficha_datos.valoracion_valor_total; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_datos.valoracion_valor_total IS 'Valoración economica valor total';


--
-- Name: COLUMN catd02_ficha_datos.observaciones_ficha; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_datos.observaciones_ficha IS 'Observaciones frente de la ficha';


--
-- Name: COLUMN catd02_ficha_datos.lindero_norte; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_datos.lindero_norte IS 'Lindero norte';


--
-- Name: COLUMN catd02_ficha_datos.lindero_sur; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_datos.lindero_sur IS 'Lindero sur';


--
-- Name: COLUMN catd02_ficha_datos.lindero_este; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_datos.lindero_este IS 'Lindero este';


--
-- Name: COLUMN catd02_ficha_datos.lindero_oeste; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_datos.lindero_oeste IS 'Lindero oeste';


--
-- Name: COLUMN catd02_ficha_datos.coordenada_norte; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_datos.coordenada_norte IS 'Coordenadas UTM (REGVEN) norte';


--
-- Name: COLUMN catd02_ficha_datos.coordenada_este; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_datos.coordenada_este IS 'Coordenadas UTM (REGVEN) Este';


--
-- Name: COLUMN catd02_ficha_datos.huso; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_datos.huso IS 'HUSO';


--
-- Name: COLUMN catd02_ficha_datos.observaciones_generales; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_datos.observaciones_generales IS 'Observaciones generales';


--
-- Name: COLUMN catd02_ficha_datos.fecha_primera_visita; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_datos.fecha_primera_visita IS 'Fecha primera visita';


--
-- Name: COLUMN catd02_ficha_datos.fecha_levantamiento; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_datos.fecha_levantamiento IS 'Fecha de levantamiento';


--
-- Name: COLUMN catd02_ficha_datos.elaborado_nombre; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_datos.elaborado_nombre IS 'Nombre del elaborador por';


--
-- Name: COLUMN catd02_ficha_datos.elaborado_ci; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_datos.elaborado_ci IS 'Cédula de identidad del elaborado por';


--
-- Name: COLUMN catd02_ficha_datos.revisado_nombre; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_datos.revisado_nombre IS 'Nombre del revisado por';


--
-- Name: COLUMN catd02_ficha_datos.revisado_ci; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_datos.revisado_ci IS 'Cédula de identidad del revisado por';


--
-- Name: COLUMN catd02_ficha_datos.croquis; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_datos.croquis IS 'Imagen del croquis';


--
-- Name: catd02_ficha_tipologia; Type: TABLE; Schema: public; Owner: sisap; Tablespace: 
--

CREATE TABLE catd02_ficha_tipologia (
    cod_presi integer NOT NULL,
    cod_entidad integer NOT NULL,
    cod_tipo_inst integer NOT NULL,
    cod_inst integer NOT NULL,
    cod_dep integer NOT NULL,
    cod_ficha integer NOT NULL,
    consecutivo integer NOT NULL,
    cod_tipo character varying(5) NOT NULL,
    area_m2 numeric(8,2) NOT NULL,
    valor_m2 numeric(26,2) NOT NULL,
    monto_variables numeric(26,2) NOT NULL,
    porcentaje_depre numeric(5,2) NOT NULL,
    valor_actual numeric(26,2)
);


ALTER TABLE public.catd02_ficha_tipologia OWNER TO sisap;

--
-- Name: TABLE catd02_ficha_tipologia; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON TABLE catd02_ficha_tipologia IS 'Registra los tipos de vivienda según el número de niveles o edificaciones que se encuentran en la ficha catastral';


--
-- Name: COLUMN catd02_ficha_tipologia.cod_presi; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_tipologia.cod_presi IS 'Código de la presidencia';


--
-- Name: COLUMN catd02_ficha_tipologia.cod_entidad; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_tipologia.cod_entidad IS 'Código de la entidad';


--
-- Name: COLUMN catd02_ficha_tipologia.cod_tipo_inst; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_tipologia.cod_tipo_inst IS 'Código tipo de Institución';


--
-- Name: COLUMN catd02_ficha_tipologia.cod_inst; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_tipologia.cod_inst IS 'Código de la Institución';


--
-- Name: COLUMN catd02_ficha_tipologia.cod_dep; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_tipologia.cod_dep IS 'Código de la dependencia';


--
-- Name: COLUMN catd02_ficha_tipologia.cod_ficha; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_tipologia.cod_ficha IS 'Código ficha catastral';


--
-- Name: COLUMN catd02_ficha_tipologia.consecutivo; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_tipologia.consecutivo IS 'contador según el número de niveles o edificaciones';


--
-- Name: COLUMN catd02_ficha_tipologia.cod_tipo; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_tipologia.cod_tipo IS 'Código tipo de construcción';


--
-- Name: COLUMN catd02_ficha_tipologia.area_m2; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_tipologia.area_m2 IS 'Metros cuadrados de la construcción';


--
-- Name: COLUMN catd02_ficha_tipologia.valor_m2; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_tipologia.valor_m2 IS 'Valor metro cuadrado de la construcción';


--
-- Name: COLUMN catd02_ficha_tipologia.monto_variables; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_tipologia.monto_variables IS 'Monto de la sumatoria de las variables de construcción';


--
-- Name: COLUMN catd02_ficha_tipologia.porcentaje_depre; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_tipologia.porcentaje_depre IS 'Porcentaje de depreciación';


--
-- Name: COLUMN catd02_ficha_tipologia.valor_actual; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_tipologia.valor_actual IS 'Valor actual de la construcción';


--
-- Name: catd02_ficha_variables; Type: TABLE; Schema: public; Owner: sisap; Tablespace: 
--

CREATE TABLE catd02_ficha_variables (
    cod_presi integer NOT NULL,
    cod_entidad integer NOT NULL,
    cod_tipo_inst integer NOT NULL,
    cod_inst integer NOT NULL,
    cod_dep integer NOT NULL,
    cod_ficha integer NOT NULL,
    cod_tipo character varying(5) NOT NULL,
    cod_variable_principal integer NOT NULL,
    cod_variable_primaria integer NOT NULL,
    cod_variable_secundaria integer NOT NULL,
    monto_variable numeric(26,2) NOT NULL
);


ALTER TABLE public.catd02_ficha_variables OWNER TO sisap;

--
-- Name: TABLE catd02_ficha_variables; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON TABLE catd02_ficha_variables IS 'Registra variables de la construcción';


--
-- Name: COLUMN catd02_ficha_variables.cod_presi; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_variables.cod_presi IS 'Código de la presidencia';


--
-- Name: COLUMN catd02_ficha_variables.cod_entidad; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_variables.cod_entidad IS 'Código de la entidad';


--
-- Name: COLUMN catd02_ficha_variables.cod_tipo_inst; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_variables.cod_tipo_inst IS 'Código tipo de Institución';


--
-- Name: COLUMN catd02_ficha_variables.cod_inst; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_variables.cod_inst IS 'Código de la Institución';


--
-- Name: COLUMN catd02_ficha_variables.cod_dep; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_variables.cod_dep IS 'Código de la dependencia';


--
-- Name: COLUMN catd02_ficha_variables.cod_ficha; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_variables.cod_ficha IS 'Código de la ficha catastral';


--
-- Name: COLUMN catd02_ficha_variables.cod_tipo; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_variables.cod_tipo IS 'Código tipo de construcción';


--
-- Name: COLUMN catd02_ficha_variables.cod_variable_principal; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_variables.cod_variable_principal IS 'Código variable principal';


--
-- Name: COLUMN catd02_ficha_variables.cod_variable_primaria; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_variables.cod_variable_primaria IS 'Código de variable primaria';


--
-- Name: COLUMN catd02_ficha_variables.cod_variable_secundaria; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_variables.cod_variable_secundaria IS 'Código variable secundaria';


--
-- Name: COLUMN catd02_ficha_variables.monto_variable; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_ficha_variables.monto_variable IS 'Monto de variable de la construcción';


--
-- Name: catd02_numero_archivo; Type: TABLE; Schema: public; Owner: sisap; Tablespace: 
--

CREATE TABLE catd02_numero_archivo (
    cod_presi integer NOT NULL,
    cod_entidad integer NOT NULL,
    cod_tipo_inst integer NOT NULL,
    cod_inst integer NOT NULL,
    cod_dep integer NOT NULL,
    numero integer NOT NULL,
    situacion integer NOT NULL
);


ALTER TABLE public.catd02_numero_archivo OWNER TO sisap;

--
-- Name: TABLE catd02_numero_archivo; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON TABLE catd02_numero_archivo IS 'Registro numero de control de archivos';


--
-- Name: COLUMN catd02_numero_archivo.cod_presi; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_numero_archivo.cod_presi IS 'Código de la presidencia';


--
-- Name: COLUMN catd02_numero_archivo.cod_entidad; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_numero_archivo.cod_entidad IS 'Código de la entidad';


--
-- Name: COLUMN catd02_numero_archivo.cod_tipo_inst; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_numero_archivo.cod_tipo_inst IS 'Código tipo de Institución';


--
-- Name: COLUMN catd02_numero_archivo.cod_inst; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_numero_archivo.cod_inst IS 'Código de la Institución';


--
-- Name: COLUMN catd02_numero_archivo.cod_dep; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_numero_archivo.cod_dep IS 'Código de la dependencia';


--
-- Name: COLUMN catd02_numero_archivo.numero; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_numero_archivo.numero IS 'Número control de archivo';


--
-- Name: COLUMN catd02_numero_archivo.situacion; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_numero_archivo.situacion IS 'Situación
1.- Sin utilizar
2.- Seleccionado
3.- Eliminado
';


--
-- Name: catd02_numero_ficha; Type: TABLE; Schema: public; Owner: sisap; Tablespace: 
--

CREATE TABLE catd02_numero_ficha (
    cod_presi integer NOT NULL,
    cod_entidad integer NOT NULL,
    cod_tipo_inst integer NOT NULL,
    cod_inst integer NOT NULL,
    cod_dep integer NOT NULL,
    numero integer NOT NULL,
    situacion integer NOT NULL
);


ALTER TABLE public.catd02_numero_ficha OWNER TO sisap;

--
-- Name: TABLE catd02_numero_ficha; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON TABLE catd02_numero_ficha IS 'Registro numero de control de archivos';


--
-- Name: COLUMN catd02_numero_ficha.cod_presi; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_numero_ficha.cod_presi IS 'Código de la presidencia';


--
-- Name: COLUMN catd02_numero_ficha.cod_entidad; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_numero_ficha.cod_entidad IS 'Código de la entidad';


--
-- Name: COLUMN catd02_numero_ficha.cod_tipo_inst; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_numero_ficha.cod_tipo_inst IS 'Código tipo de Institución';


--
-- Name: COLUMN catd02_numero_ficha.cod_inst; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_numero_ficha.cod_inst IS 'Código de la Institución';


--
-- Name: COLUMN catd02_numero_ficha.cod_dep; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_numero_ficha.cod_dep IS 'Código de la dependencia';


--
-- Name: COLUMN catd02_numero_ficha.numero; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_numero_ficha.numero IS 'Número control de ficha catastral';


--
-- Name: COLUMN catd02_numero_ficha.situacion; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_numero_ficha.situacion IS 'Situación
1.- Sin utilizar
2.- Seleccionado
3.- Eliminado
';


--
-- Name: catd02_numero_inscripcion; Type: TABLE; Schema: public; Owner: sisap; Tablespace: 
--

CREATE TABLE catd02_numero_inscripcion (
    cod_presi integer NOT NULL,
    cod_entidad integer NOT NULL,
    cod_tipo_inst integer NOT NULL,
    cod_inst integer NOT NULL,
    cod_dep integer NOT NULL,
    numero integer NOT NULL,
    situacion integer NOT NULL
);


ALTER TABLE public.catd02_numero_inscripcion OWNER TO sisap;

--
-- Name: TABLE catd02_numero_inscripcion; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON TABLE catd02_numero_inscripcion IS 'Registro numero de control de archivos';


--
-- Name: COLUMN catd02_numero_inscripcion.cod_presi; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_numero_inscripcion.cod_presi IS 'Código de la presidencia';


--
-- Name: COLUMN catd02_numero_inscripcion.cod_entidad; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_numero_inscripcion.cod_entidad IS 'Código de la entidad';


--
-- Name: COLUMN catd02_numero_inscripcion.cod_tipo_inst; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_numero_inscripcion.cod_tipo_inst IS 'Código tipo de Institución';


--
-- Name: COLUMN catd02_numero_inscripcion.cod_inst; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_numero_inscripcion.cod_inst IS 'Código de la Institución';


--
-- Name: COLUMN catd02_numero_inscripcion.cod_dep; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_numero_inscripcion.cod_dep IS 'Código de la dependencia';


--
-- Name: COLUMN catd02_numero_inscripcion.numero; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_numero_inscripcion.numero IS 'Número control de ficha catastral';


--
-- Name: COLUMN catd02_numero_inscripcion.situacion; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN catd02_numero_inscripcion.situacion IS 'Situación
1.- Sin utilizar
2.- Seleccionado
3.- Eliminado
';


--
-- Name: cugd90_municipio_defecto; Type: TABLE; Schema: public; Owner: sisap; Tablespace: 
--

CREATE TABLE cugd90_municipio_defecto (
    cod_presi integer NOT NULL,
    cod_entidad integer NOT NULL,
    cod_tipo_inst integer NOT NULL,
    cod_inst integer NOT NULL,
    cod_dep integer NOT NULL,
    cod_republica integer NOT NULL,
    cod_estado integer NOT NULL,
    cod_municipio integer NOT NULL
);


ALTER TABLE public.cugd90_municipio_defecto OWNER TO sisap;

--
-- Name: TABLE cugd90_municipio_defecto; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON TABLE cugd90_municipio_defecto IS 'Registra el municipio por defecto a fin de evitar repetir esta información';


--
-- Name: COLUMN cugd90_municipio_defecto.cod_presi; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN cugd90_municipio_defecto.cod_presi IS 'Código de la presidencia';


--
-- Name: COLUMN cugd90_municipio_defecto.cod_entidad; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN cugd90_municipio_defecto.cod_entidad IS 'Código de la entidad';


--
-- Name: COLUMN cugd90_municipio_defecto.cod_tipo_inst; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN cugd90_municipio_defecto.cod_tipo_inst IS 'Código tipo de Institución';


--
-- Name: COLUMN cugd90_municipio_defecto.cod_inst; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN cugd90_municipio_defecto.cod_inst IS 'Código de la Institución';


--
-- Name: COLUMN cugd90_municipio_defecto.cod_dep; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN cugd90_municipio_defecto.cod_dep IS 'Código de la depedencia';


--
-- Name: COLUMN cugd90_municipio_defecto.cod_republica; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN cugd90_municipio_defecto.cod_republica IS 'Código de la república';


--
-- Name: COLUMN cugd90_municipio_defecto.cod_estado; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN cugd90_municipio_defecto.cod_estado IS 'Código del estado';


--
-- Name: COLUMN cugd90_municipio_defecto.cod_municipio; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN cugd90_municipio_defecto.cod_municipio IS 'Código del municipio';


--
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
-- Name: TABLE shd001_registro_contribuyentes; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON TABLE shd001_registro_contribuyentes IS 'Registro general de contribuyentes';


--
-- Name: COLUMN shd001_registro_contribuyentes.rif_cedula; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd001_registro_contribuyentes.rif_cedula IS 'Rif o cédela de identidad';


--
-- Name: COLUMN shd001_registro_contribuyentes.personalidad_juridica; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd001_registro_contribuyentes.personalidad_juridica IS 'Personalidad Juridica
1.- Natural
2.- Juridica';


--
-- Name: COLUMN shd001_registro_contribuyentes.razon_social_nombres; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd001_registro_contribuyentes.razon_social_nombres IS 'Razón social o Nombres y Apellidos';


--
-- Name: COLUMN shd001_registro_contribuyentes.fecha_inscripcion; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd001_registro_contribuyentes.fecha_inscripcion IS 'Fecha de Inscripción';


--
-- Name: COLUMN shd001_registro_contribuyentes.nacionalidad; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd001_registro_contribuyentes.nacionalidad IS 'Nacionalidad
1.- Extranjera
2.- Venezolana';


--
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
-- Name: COLUMN shd001_registro_contribuyentes.profesion; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd001_registro_contribuyentes.profesion IS 'Codigo de la profesion (Enlace con personal)';


--
-- Name: COLUMN shd001_registro_contribuyentes.cod_pais; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd001_registro_contribuyentes.cod_pais IS 'Código del pais';


--
-- Name: COLUMN shd001_registro_contribuyentes.cod_estado; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd001_registro_contribuyentes.cod_estado IS 'Código del estado';


--
-- Name: COLUMN shd001_registro_contribuyentes.cod_municipio; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd001_registro_contribuyentes.cod_municipio IS 'Código del municipio';


--
-- Name: COLUMN shd001_registro_contribuyentes.cod_parroquia; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd001_registro_contribuyentes.cod_parroquia IS 'Código de la parroquia';


--
-- Name: COLUMN shd001_registro_contribuyentes.cod_centro_poblado; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd001_registro_contribuyentes.cod_centro_poblado IS 'Código del centro poblado
Urbanizaciones
Barrios
Caserios
Otros
';


--
-- Name: COLUMN shd001_registro_contribuyentes.cod_calle_avenida; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd001_registro_contribuyentes.cod_calle_avenida IS 'Código de la calle o avenida';


--
-- Name: COLUMN shd001_registro_contribuyentes.cod_vereda_edificio; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd001_registro_contribuyentes.cod_vereda_edificio IS 'Código de la vereda o edificio';


--
-- Name: COLUMN shd001_registro_contribuyentes.numero_vivienda_local; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd001_registro_contribuyentes.numero_vivienda_local IS 'Número de la vivienda, local, piso y apartamento';


--
-- Name: COLUMN shd001_registro_contribuyentes.telefonos_fijos; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd001_registro_contribuyentes.telefonos_fijos IS 'Telefonos fijos';


--
-- Name: COLUMN shd001_registro_contribuyentes.telefonos_celulares; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd001_registro_contribuyentes.telefonos_celulares IS 'Telefonos celulares';


--
-- Name: COLUMN shd001_registro_contribuyentes.correo_electronico; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN shd001_registro_contribuyentes.correo_electronico IS 'Correo electrónico';


--
-- Data for Name: catd01_complemento_variable_primaria; Type: TABLE DATA; Schema: public; Owner: sisap
--

COPY catd01_complemento_variable_primaria (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, ano_ordenanza, cod_tipo, cod_variable_principal, cod_variable_primaria, denominacion_primaria) FROM stdin;
1	11	30	11	1	2009	1	3	1	VARIABLE PRIMARIA UNO DE 01 03
1	11	30	11	1	2009	1	3	2	VARIABLE PRIMARIA DOS DE 01 03
1	11	30	11	1	2009	1	1	2	VARIABLE PRIMARIA DOS DE 01 01
1	11	30	11	1	2009	1	1	1	VARIABLE PRIMARIA UNO DE 01 01
1	11	30	11	1	2009	1	1	3	VARIABLE PRIMARIA TRES DE 01 01
1	11	30	11	1	2009	4	1	1	VARIABLE PRIMARIA UNO DE 04 01
1	11	30	11	100	2009	A1	1	1	DE CAUCHO DE PRIMERA
1	11	30	11	100	2009	A1	1	2	DE ACEITE
1	11	30	11	100	2009	A1	2	1	CERAMICA DE LUJO
1	11	30	11	100	2009	A1	2	2	GRANITO DE PRIMERA
1	11	30	11	100	2009	A1	2	3	CERAMICA ECONOMICA
1	11	30	11	100	2009	A1	2	4	VINIL
1	11	30	11	100	2009	A1	3	1	EN VENTANAS
1	11	30	11	100	2009	A1	4	1	DE ALUMINIO
1	11	30	11	100	2009	A1	4	2	DE CELOSIA
1	11	30	11	100	2009	A1	5	1	MADERA MACIZA
1	11	30	11	100	2009	A1	5	2	DE SEGURIDAD
1	11	30	11	100	2009	A1	6	1	A COLOR
1	11	30	11	100	2009	A1	6	2	DE LUJO
1	11	30	11	100	2009	A1	6	3	CON GRIFERIA DE LUJO
1	11	30	11	100	2009	A1	7	1	CON CLOSETS
1	11	30	11	100	2009	A1	8	1	ASCENSOR
1	11	30	11	100	2009	A1	9	1	MALETEROS
1	11	30	11	100	2009	A1	10	1	TECHADO
\.


--
-- Data for Name: catd01_complemento_variable_principal; Type: TABLE DATA; Schema: public; Owner: sisap
--

COPY catd01_complemento_variable_principal (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, ano_ordenanza, cod_tipo, cod_variable_principal, denominacion_principal) FROM stdin;
1	11	30	11	1	2009	1	1	VARIABLE PRINCIPAL UNO
1	11	30	11	1	2009	4	1	VARIABLE PRINCIPAL UNO
1	11	30	11	1	2009	1	3	VARIABLE PRINCIPAL TRES DE TIPO 01
1	11	30	11	1049	2009	2	1	VARIABLE PRINCIPAL UNO
1	11	30	11	1049	2009	2	2	VARIABLE PRINCIPAL DOS
1	11	30	11	1	2009	1	4	PRUEBA
1	11	30	11	1	2009	1	5	PRUEBA
1	11	30	11	1	2009	1	6	PRUEBA PRUEBA
1	11	30	11	1	2009	1	7	PRUEBA
1	11	30	11	1	2009	1	8	PRUEBA VARIABLE
1	11	30	11	1	2009	1	9	PRUEBA VARIABLE
1	11	30	11	1	2009	1	10	PRUEBA VARIABLE
1	11	30	11	1	2009	1	11	PRUEBA VARIABLE
1	11	30	11	1	2009	1	12	VARIABLE PRINCIPAL TRES DE TIPO 01
1	11	30	11	100	2009	A1	1	PINTURAS EN PAREDES
1	11	30	11	100	2009	A1	2	PISOS
1	11	30	11	100	2009	A1	3	REJAS
1	11	30	11	100	2009	A1	4	VENTANAS
1	11	30	11	100	2009	A1	5	PUERTAS
1	11	30	11	100	2009	A1	6	EQUIPOS SANITARIOS
1	11	30	11	100	2009	A1	7	CLOSETS
1	11	30	11	100	2009	A1	8	ASCENSOR
1	11	30	11	100	2009	A1	9	MALETEROS
1	11	30	11	100	2009	A1	10	ESTACIONAMIENTO
1	11	30	11	100	2009	A2	1	PAREDES
1	11	30	11	100	2009	A2	2	PISOS
1	11	30	11	100	2009	A2	3	REJAS
\.


--
-- Data for Name: catd01_complemento_variable_secundaria; Type: TABLE DATA; Schema: public; Owner: sisap
--

COPY catd01_complemento_variable_secundaria (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, ano_ordenanza, cod_tipo, cod_variable_principal, cod_variable_primaria, cod_variable_secundaria, denominacion_secundaria, monto) FROM stdin;
1	11	30	11	1	2009	4	1	1	1	VARIABLE SECUNDARIA UNO DE 04 01 01	33.00
1	11	30	11	1	2009	4	1	1	2	VARIABLE SECUNDARIA DOS DE 04 01 01	45.00
1	11	30	11	1	2009	4	1	1	3	VARIABLE SECUNDARIA TRES DE 04 01 01	98.87
1	11	30	11	1	2009	4	1	1	4	VARIABLE SECUNDARIA CUATRO DE 04 01 01	345.00
1	11	30	11	1	2009	1	1	3	1	VARIABLE SECUNDARIA UNO DE 01 01 03	34.00
1	11	30	11	1	2009	1	1	1	1	PRUEBA	232.00
1	11	30	11	100	2009	A1	1	1	1	DE CAUCHO DE PRIEMRA	0.03
1	11	30	11	100	2009	A1	1	2	1	DE ACEITE	0.04
1	11	30	11	100	2009	A1	2	1	1	CERAMICA DE LUJO	2.99
1	11	30	11	100	2009	A1	2	2	2	GRANITO	1.08
1	11	30	11	100	2009	A1	2	3	1	CERAMICA	1.00
1	11	30	11	100	2009	A1	2	4	1	VINIL	0.80
1	11	30	11	100	2009	A1	3	1	1	EN VENTANAS	1.22
1	11	30	11	100	2009	A1	4	1	1	DE ALUMINIO	0.50
1	11	30	11	100	2009	A1	4	2	1	DE CELOSIA	-2.33
1	11	30	11	100	2009	A1	5	1	1	MEDERA MACIZA	2.40
1	11	30	11	100	2009	A1	5	2	1	DE SEGURIDAD	3.00
1	11	30	11	100	2009	A1	6	1	1	A COLOR	0.58
1	11	30	11	100	2009	A1	6	2	1	DE LUJO	2.40
1	11	30	11	100	2009	A1	6	3	1	CON GRIFERIA DE LUJO	3.50
1	11	30	11	100	2009	A1	7	1	1	CON CLOSETS	0.35
1	11	30	11	100	2009	A1	8	1	1	ASCENSOR	1.00
1	11	30	11	100	2009	A1	9	1	1	MALETEROS	3.00
1	11	30	11	100	2009	A1	10	1	1	TECHADO	1.50
1	11	30	11	100	2009	A1	1	1	2	DE CAUCHO DE SEGUNDA	0.02
1	11	30	11	100	2009	A1	1	1	3	4441SDFSDF	-4.00
\.


--
-- Data for Name: catd01_depreciacion_edificaciones; Type: TABLE DATA; Schema: public; Owner: sisap
--

COPY catd01_depreciacion_edificaciones (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, ano_ordenanza, edad, factor_excelente, factor_bueno, factor_regular, factor_malo) FROM stdin;
1	11	30	11	1	2007	30	100.00	50.00	26.00	14.00
1	11	30	11	1	2009	30	100.00	50.00	26.00	14.00
1	11	30	11	1	2009	45	100.00	50.00	26.00	14.00
1	11	30	11	1	2009	80	10.00	8.00	6.00	3.00
1	11	30	11	1	2007	45	70.00	50.00	40.00	20.00
1	11	30	11	1	2009	50	66.00	18.00	10.00	5.00
1	11	30	11	100	2009	1	100.00	100.00	95.00	90.00
1	11	30	11	100	2009	2	99.00	98.00	93.00	88.00
1	11	30	11	100	2009	3	98.00	97.00	92.00	87.00
1	11	30	11	100	2009	4	97.00	96.00	91.00	86.00
1	11	30	11	100	2009	5	96.00	95.00	90.00	85.00
1	11	30	11	100	2009	6	95.00	94.00	89.00	84.00
1	11	30	11	100	2009	7	95.00	93.00	88.00	83.00
1	11	30	11	100	2009	8	94.00	92.00	87.00	82.00
1	11	30	11	100	2009	9	94.00	91.00	86.00	81.00
1	11	30	11	100	2009	10	94.00	90.00	85.00	80.00
1	11	30	11	100	2009	11	93.00	89.00	84.00	79.00
1	11	30	11	100	2009	12	93.00	88.00	83.00	78.00
1	11	30	11	100	2009	13	92.00	87.00	82.00	77.00
1	11	30	11	100	2009	14	91.00	86.00	81.00	76.00
1	11	30	11	100	2009	15	89.00	85.00	80.00	75.00
1	11	30	11	100	2009	16	88.00	83.00	78.00	73.00
1	11	30	11	100	2009	17	87.00	82.00	77.00	72.00
1	11	30	11	100	2009	18	85.00	80.00	75.00	70.00
1	11	30	11	100	2009	19	84.00	78.00	73.00	68.00
1	11	30	11	100	2009	20	82.00	77.00	72.00	67.00
1	11	30	11	100	2009	21	80.00	76.00	71.00	66.00
1	11	30	11	100	2009	22	79.00	74.00	69.00	64.00
1	11	30	11	100	2009	23	78.00	73.00	68.00	63.00
1	11	30	11	100	2009	24	76.00	71.00	66.00	61.00
1	11	30	11	100	2009	25	75.00	79.00	64.00	59.00
1	11	30	11	100	2009	26	73.00	68.00	63.00	58.00
1	11	30	11	100	2009	27	72.00	67.00	62.00	57.00
1	11	30	11	100	2009	28	70.00	65.00	60.00	55.00
1	11	30	11	100	2009	29	69.00	64.00	59.00	54.00
1	11	30	11	100	2009	30	68.00	63.00	58.00	53.00
\.


--
-- Data for Name: catd01_escala_cobro; Type: TABLE DATA; Schema: public; Owner: sisap
--

COPY catd01_escala_cobro (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, ano_ordenanza, escala, monto_desde, monto_hasta, porcentaje, sustraendo) FROM stdin;
1	11	30	11	100	2009	1	0.01	1000.00	0.50	0.00
1	11	30	11	100	2009	3	5000.01	20000.00	1.50	0.00
1	11	30	11	100	2009	2	1000.01	5000.00	1.00	0.00
1	11	30	11	100	2009	4	20000.01	50000.00	2.00	0.00
1	11	30	11	100	2009	5	50000.01	10000000000000000.00	3.00	0.00
\.


--
-- Data for Name: catd01_planta_valores_tierra; Type: TABLE DATA; Schema: public; Owner: sisap
--

COPY catd01_planta_valores_tierra (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, ano_ordenanza, cod_republica, cod_estado, cod_municipio, cod_parroquia, cod_zona, denominacion_zona, valor_m2) FROM stdin;
1	11	30	11	1	2009	1	12	8	24	2	ZONA DOSDS	545.00
1	11	30	11	1	2009	1	12	8	19	3	ZONA TRES 3	987.00
1	11	30	11	1	2009	1	12	8	19	1	ZONA UNO 3	332.00
1	11	30	11	1	2009	1	12	8	19	2	ZONA DOS2	45.00
1	11	30	11	1	2009	1	12	11	32	1	ZONA UNO	2398.00
1	11	30	11	1	2009	1	12	11	32	3	ZONA TRES	433.00
1	11	30	11	1	2009	1	12	11	32	2	ZONA DOS	232.00
1	11	30	11	1	2009	1	12	11	34	1	PRUEBA PARAPARA 1	3434.00
1	11	30	11	1	2009	1	12	11	34	2	PREUBA PARAPARA 2	22.00
1	11	30	11	1	2009	1	12	11	33	1	PRUEBA CANTAGALLO UNO	123.00
1	11	30	11	1	2009	1	12	11	33	2	PRUEBA CANTAGALLO DOS	456.00
1	11	30	11	100	2009	1	12	11	32	1	ZONA 01	12.00
1	11	30	11	100	2009	1	12	11	32	2	ZONA 02	14.00
1	11	30	11	100	2009	1	12	11	32	3	ZONA 03	16.00
\.


--
-- Data for Name: catd01_recargos_catastrales; Type: TABLE DATA; Schema: public; Owner: sisap
--

COPY catd01_recargos_catastrales (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, ano_ordenanza, porcentaje_industria, porcentaje_servicios, porcentaje_comercial, porcentaje_arrendado, porcentaje_otro) FROM stdin;
1	11	30	11	1	2009	10.00	2.00	10.00	2.00	43.00
1	11	30	11	100	2009	1.00	2.00	3.00	4.00	5.00
\.


--
-- Data for Name: catd01_valor_construccion; Type: TABLE DATA; Schema: public; Owner: sisap
--

COPY catd01_valor_construccion (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, ano_ordenanza, cod_tipo, denominacion_tipo, valor_m2, caracteristicas_basicas) FROM stdin;
1	11	30	11	1049	2009	1	2121	2.00	1212
1	11	30	11	1049	2009	2	DOS	2.00	DOS
1	11	30	11	1	2009	1	CONSTRUCCION UNO	1.00	CONSTRUCCION 
1	11	30	11	1	2009	22	CONSTRUCCION PRUEBA	43.00	PRUEBA CONSTRUCCION
1	11	30	11	1	2009	4	CONSTRUCCION CUATRO	44.00	CONSTRUCCIONDD
1	11	30	11	1	2009	6	CONSTRUCCION SEIS	66.00	CONSTRUCCION
1	11	30	11	100	2009	A2	APARTAMENTO ECONOMICO SIN MALETERO	2000.00	ESTRUCTURA DE CONCRETO ARMADO VIGAS Y COLUMNAS
1	11	30	11	100	2009	C1	LOCAL COMERCIAL  NUEVO DE LUJO	2300.00	ESTRUCTURA DE CONCRETO ARMADO
1	11	30	11	100	2009	C2	LOCAL COMERCIAL ECONOMICO	1800.00	ESTRUCTURA DE CONCRETO ARMADO
1	11	30	11	100	2009	A1	APARTAMENTO DE LUJO	5000.00	ESTRUCTURA DE CONCRETO ARMADO VIGAS.
\.


--
-- Data for Name: catd02_ficha_datos; Type: TABLE DATA; Schema: public; Owner: sisap
--

COPY catd02_ficha_datos (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_ficha, cod_inscripcion, fecha_inscripcion, cod_control_archivo, ano_ordenanza, cod_ant_edo, cod_ant_mun, cod_ant_prr, cod_ant_sec, cod_ant_man, cod_ant_par, cod_ant_blo, cod_ant_piso, cod_ant_apto, cod_act_edo, cod_act_mun, cod_act_prr, cod_act_amb_t, cod_act_amb, cod_act_sec, cod_act_man, cod_act_par, cod_act_sbp, cod_act_niv, cod_act_und, tilde_ciudad, tilde_localidad, tilde_urbanizacion, tilde_conjunto_residencial, tilde_barrio, tilde_sector, nombre, tilde_av_uno, tilde_clle_uno, tilde_crr_uno, tilde_trav_uno, tilde_prol_uno, tilde_crrt_uno, tilde_cjn_uno, tilde_psje_uno, tilde_blv_uno, tilde_vda_uno, tilde_esc_uno, tilde_snd_uno, tilde_tcal_uno, tilde_cno_uno, direccion_uno, tilde_av_dos, tilde_clle_dos, tilde_crr_dos, tilde_trav_dos, tilde_prol_dos, tilde_crrt_dos, tilde_cjn_dos, tilde_psje_dos, tilde_blv_dos, tilde_vda_dos, tilde_esc_dos, tilde_snd_dos, tilde_tcal_dos, tilde_cno_dos, direccion_dos, tilde_av_tres, tilde_clle_tres, tilde_crr_tres, tilde_trav_tres, tilde_prol_tres, tilde_crrt_tres, tilde_cjn_tres, tilde_psje_tres, tilde_blv_tres, tilde_vda_tres, tilde_esc_tres, tilde_snd_tres, tilde_tcal_tres, tilde_cno_tres, direccion_tres, tilde_edif_uno, tilde_apto_uno, tilde_qta_uno, tilde_casa_uno, tilde_rancho_uno, tilde_cc_uno, tilde_local_uno, tilde_oficina_uno, tilde_otro_uno, tipo_vivienda, nombre_inmueble, numero_civico, telefono_inmueble, punto_referencia_inmueble, personalidad_juridica, cedula_rif, nombre_ocupante, localidad_ocupante, urb_barrio_sector_ocupante, tilde_av_cuatro, tilde_clle_cuatro, tilde_crr_cuatro, tilde_trav_cuatro, tilde_prol_cuatro, tilde_crrt_cuatro, tilde_cjn_cuatro, tilde_psje_cuatro, tilde_blv_cuatro, tilde_vda_cuatro, tilde_esc_cuatro, tilde_snd_cuatro, tilde_tcal_cuatro, tilde_cno_cuatro, direccion_cuatro, tilde_av_cinco, tilde_clle_cinco, tilde_crr_cinco, tilde_trav_cinco, tilde_prol_cinco, tilde_crrt_cinco, tilde_cjn_cinco, tilde_psje_cinco, tilde_blv_cinco, tilde_vda_cinco, tilde_esc_cinco, tilde_snd_cinco, tilde_tcal_cinco, tilde_cno_cinco, direccion_cinco, tilde_av_seis, tilde_clle_seis, tilde_crr_seis, tilde_trav_seis, tilde_prol_seis, tilde_crrt_seis, tilde_cjn_seis, tilde_psje_seis, tilde_blv_seis, tilde_vda_seis, tilde_esc_seis, tilde_snd_seis, tilde_tcal_seis, tilde_cno_seis, direccion_seis, tilde_edif_dos, tilde_apto_dos, tilde_qta_dos, tilde_casa_dos, tilde_rancho_dos, tilde_cc_dos, tilde_local_dos, tilde_oficina_dos, tilde_otro_dos, nombre_repre, numero_civico_repre, telefono_repre, punto_referencia_repre, tilde_topo, tilde_acceso, tilde_forma, tilde_ubica, tilde_entorno, tilde_mejora, tilde_tenencia, tilde_regimen, tilde_uso, tilde_servicio, tilde_tipo, tilde_descripcionuso, tilde_tenencia_const, tilde_regi_prop, tilde_soporte, tilde_techo, tilde_cubierta, tilde_pared_tipo, tilde_pared_acaba, tilde_conserva, ano_construccion, porce_refaccion, numero_niveles, ano_refaccion, edad_efectiva, numero_edificaciones, registro_numero, registro_folio, registro_tomo, registro_protocolo, registro_fecha, registro_area_terreno, registro_area_construccion, registro_monto, valoracion_area, valoracion_valor_unitario, valoracion_sector, valoracion_ajuste_area, valoracion_ajuste_forma, valoracion_valor_ajustado, valoracion_valor_total, observaciones_ficha, lindero_norte, lindero_sur, lindero_este, lindero_oeste, coordenada_norte, coordenada_este, huso, observaciones_generales, fecha_primera_visita, fecha_levantamiento, elaborado_nombre, elaborado_ci, revisado_nombre, revisado_ci, croquis) FROM stdin;
1	11	30	11	100	38	38	2009-10-24	39	2009	12	11	32	1	123	456	0	0	0	12	11	32	U	2	1	123	456	789	120	121	0	0	1	0	0	0	BANCO OBRERO	1	0	0	0	0	0	0	0	0	0	0	0	0	0	1	0	1	0	0	0	0	0	0	0	0	0	0	0	0	4	0	0	0	0	0	0	0	0	0	1	0	0	0	0	12	0	0	0	1	0	0	0	0	0	TEXTO DE PRUEBA	SANTA EDUVIGIS	98989	0246-4319867	CERCA DE BURGER	1	16237513	JOSE GREGORIO HERNANDEZ	SAN JUAN DE LOS MORROS	URB. ROMULO GALLEGOS	1	0	0	0	0	0	0	0	0	0	0	0	0	0	1	0	1	0	0	0	0	0	0	0	0	0	0	0	0	3	0	0	0	0	0	0	0	0	0	1	0	0	0	0	12	0	0	0	1	0	0	0	0	0	PROBANDO	233223	0246-4316756	CERCA DE ALGUIEN	1000100	100000	100	010	00000	000000	100000000	01000000	1000000000000000000	11010111111111111100	0000100000000000000	10000010000	10000000000	000100	1000000	100000	0010100000	10000000000	1100	0100	2002	4.90	2	2000	6	1	1223	321	1	7	2009-10-24	200.00	150.00	345.00	150.00	300.00	1	3.00	6.00	287.00	287.00	OBSERVACIONES FICHA AQUI	12	34	45	32	23	34	34	OBSERVACIONES GENERALES AQUI	2009-10-24	2009-10-24	RAMON ORTIZ	12345678	PEDRO PEREZ	98678321	\N
1	11	30	11	100	1	1	2009-10-24	1	2009	12	11	32	1	123	456	0	0	0	12	11	32	U	2	1	345	345	898	0	123	0	0	0	1	0	0	BANCO OBRERO 2	0	1	0	0	0	0	0	0	0	0	0	0	0	0	1	0	1	0	0	0	0	0	0	0	0	0	0	0	0	4	0	0	0	0	0	0	0	0	0	1	0	0	0	0	12	0	0	0	1	0	0	0	0	0	TEXTO DE PRUEBA	LOS MANGOS	98989	0246-4319867	CERCA DE BURGER	1	16237899	CARLOS RIVERA	SAN JUAN DE LOS MORROS	URB. ROMULO GALLEGOS 2	1	0	0	0	0	0	0	0	0	0	0	0	0	0	1	0	1	0	0	0	0	0	0	0	0	0	0	0	0	3	0	0	0	0	0	0	0	0	0	1	0	0	0	0	12	0	0	0	1	0	0	0	0	0	PROBANDO	112	0246-4318989	CERCA DE ALGUIEN	1000100	100000	100	010	00000	000000	100000000	01000000	1000000000000000000	11010111111111111100	0000100000000000000	10000010000	10000000000	000100	1000000	100000	0010100000	10000000000	1100	0100	2002	4.90	2	2000	6	1	1223	321	1	7	2009-10-24	200.00	150.00	345.00	150.00	300.00	1	3.00	6.00	287.00	287.00	OBSERVACIONES FICHA AQUI	12	34	45	32	23	34	34	OBSERVACIONES GENERALES AQUI	2009-10-24	2009-10-24	ALBERTO PEREZ	8787887	ERICK ARAGOL	18778212	\N
1	11	30	11	100	3	3	2009-10-24	3	2009	12	11	32	1	123	345	34	1	1	12	11	32	U	1	1	123	345	456	234	234	0	0	1	0	0	0	EL DIAMANTE	1	0	0	0	0	0	0	0	0	0	0	0	0	0	MIRANDA	0	1	0	0	0	0	0	0	0	0	0	0	0	0	JOSE MARTI	0	1	0	0	0	0	0	0	0	0	0	0	0	0	INFANTE	0	1	0	0	0	0	0	0	0	TEXTO	LOS MANGOS	345674	O246-3456212	SUPER MERCADOS CASA	2	J-12345678-9	J&L SISTEMAS	SAN JUAN DE LOS MORROS		0	0	1	0	0	0	0	0	0	0	0	0	0	0	2	1	0	0	0	0	0	0	0	0	0	0	0	0	0	8	0	1	0	0	0	0	0	0	0	0	0	0	0	0	LAS TIAMITAS	0	0	0	1	0	0	0	0	0	LOS MANGOS	2432223	0246-4316756	CERCA DE CANTV	1000000	100000	100	010	00000	000000	100000000	01000000	0100000000000000000	11010111111110111100	0100000000000000000	00011000000	01000000000	100000	1000000	100000	0100000000	10000000000	1000	1000	2003	3.00	2	2000	5	1	34	34	43	443	2009-10-24	200.00	180.00	345.00	0.00	0.00	0	0.00	0.00	0.00	0.00	OBSERVSACIONES	12	34	45	32	23	34	34	OBSERVACIONES	2009-10-24	2009-10-24	RAMON CASTRO	12345676	PEDRO GOMEZ	4567467	\N
\.


--
-- Data for Name: catd02_ficha_tipologia; Type: TABLE DATA; Schema: public; Owner: sisap
--

COPY catd02_ficha_tipologia (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_ficha, consecutivo, cod_tipo, area_m2, valor_m2, monto_variables, porcentaje_depre, valor_actual) FROM stdin;
1	11	30	11	100	38	1	A1	150.00	3000.00	1.00	94.00	423000.00
1	11	30	11	100	3	1	A1	180.00	3000.00	1.00	96.00	518400.00
\.


--
-- Data for Name: catd02_ficha_variables; Type: TABLE DATA; Schema: public; Owner: sisap
--

COPY catd02_ficha_variables (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_ficha, cod_tipo, cod_variable_principal, cod_variable_primaria, cod_variable_secundaria, monto_variable) FROM stdin;
1	11	30	11	100	38	A1	1	1	1	0.03
1	11	30	11	100	38	A1	2	3	1	2.99
1	11	30	11	100	38	A1	5	1	1	2.40
1	11	30	11	100	3	A1	1	1	1	0.03
1	11	30	11	100	3	A1	2	1	1	2.99
1	11	30	11	100	3	A1	5	1	1	2.40
\.


--
-- Data for Name: catd02_numero_archivo; Type: TABLE DATA; Schema: public; Owner: sisap
--

COPY catd02_numero_archivo (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, numero, situacion) FROM stdin;
1	11	30	11	100	20	1
1	11	30	11	100	21	1
1	11	30	11	100	22	1
1	11	30	11	100	23	1
1	11	30	11	100	24	1
1	11	30	11	100	25	1
1	11	30	11	100	26	1
1	11	30	11	100	27	1
1	11	30	11	100	28	1
1	11	30	11	100	29	1
1	11	30	11	100	30	1
1	11	30	11	100	31	1
1	11	30	11	1	6	1
1	11	30	11	1	7	1
1	11	30	11	1	8	1
1	11	30	11	100	32	1
1	11	30	11	100	33	1
1	11	30	11	1	9	1
1	11	30	11	1	10	1
1	11	30	11	1	11	1
1	11	30	11	1	12	1
1	11	30	11	1	13	1
1	11	30	11	1	14	1
1	11	30	11	1	15	1
1	11	30	11	1	16	1
1	11	30	11	1	17	1
1	11	30	11	1	18	1
1	11	30	11	1	19	1
1	11	30	11	1	20	1
1	11	30	11	1	21	1
1	11	30	11	1	22	1
1	11	30	11	1	23	1
1	11	30	11	1	24	1
1	11	30	11	1	25	1
1	11	30	11	1	33	1
1	11	30	11	1	34	1
1	11	30	11	1	35	1
1	11	30	11	1	36	1
1	11	30	11	1	37	1
1	11	30	11	1	38	1
1	11	30	11	1	39	1
1	11	30	11	1	40	1
1	11	30	11	1	41	1
1	11	30	11	1	42	1
1	11	30	11	1	26	1
1	11	30	11	1	27	1
1	11	30	11	1	28	1
1	11	30	11	1	29	1
1	11	30	11	1	30	1
1	11	30	11	1	31	1
1	11	30	11	1	32	1
1	11	30	11	1	43	1
1	11	30	11	1	44	1
1	11	30	11	1	45	1
1	11	30	11	1	46	1
1	11	30	11	1	47	1
1	11	30	11	1	48	1
1	11	30	11	1	49	1
1	11	30	11	1	50	1
1	11	30	11	1	51	1
1	11	30	11	1	52	1
1	11	30	11	1	53	1
1	11	30	11	1	54	1
1	11	30	11	1	1	3
1	11	30	11	100	1	3
1	11	30	11	100	8	1
1	11	30	11	100	9	1
1	11	30	11	100	10	1
1	11	30	11	100	11	1
1	11	30	11	100	42	1
1	11	30	11	100	43	1
1	11	30	11	100	44	1
1	11	30	11	100	45	1
1	11	30	11	100	46	1
1	11	30	11	100	47	1
1	11	30	11	100	48	1
1	11	30	11	100	49	1
1	11	30	11	100	50	1
1	11	30	11	100	40	1
1	11	30	11	100	41	1
1	11	30	11	1	3	1
1	11	30	11	1	4	1
1	11	30	11	100	12	1
1	11	30	11	1	5	1
1	11	30	11	100	13	1
1	11	30	11	100	14	1
1	11	30	11	100	15	1
1	11	30	11	100	16	1
1	11	30	11	100	17	1
1	11	30	11	100	18	1
1	11	30	11	100	19	1
1	11	30	11	100	34	1
1	11	30	11	100	35	1
1	11	30	11	100	36	1
1	11	30	11	100	37	1
1	11	30	11	100	39	1
1	11	30	11	100	38	3
1	11	30	11	100	2	2
1	11	30	11	100	3	2
1	11	30	11	100	4	2
1	11	30	11	100	5	2
1	11	30	11	100	6	2
1	11	30	11	1	2	2
1	11	30	11	100	7	1
\.


--
-- Data for Name: catd02_numero_ficha; Type: TABLE DATA; Schema: public; Owner: sisap
--

COPY catd02_numero_ficha (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, numero, situacion) FROM stdin;
1	11	30	11	1	2	1
1	11	30	11	1	3	1
1	11	30	11	1	4	1
1	11	30	11	1	5	1
1	11	30	11	1	6	1
1	11	30	11	1	7	1
1	11	30	11	1	8	1
1	11	30	11	1	9	1
1	11	30	11	1	10	1
1	11	30	11	1	11	1
1	11	30	11	1	12	1
1	11	30	11	1	13	1
1	11	30	11	1	14	1
1	11	30	11	1	15	1
1	11	30	11	1	16	1
1	11	30	11	1	17	1
1	11	30	11	1	18	1
1	11	30	11	1	19	1
1	11	30	11	1	20	1
1	11	30	11	1	21	1
1	11	30	11	1	22	1
1	11	30	11	1	23	1
1	11	30	11	1	24	1
1	11	30	11	1	25	1
1	11	30	11	1	26	1
1	11	30	11	1	27	1
1	11	30	11	1	28	1
1	11	30	11	1	29	1
1	11	30	11	1	30	1
1	11	30	11	1	31	1
1	11	30	11	1	43	1
1	11	30	11	1	44	1
1	11	30	11	1	45	1
1	11	30	11	1	46	1
1	11	30	11	1	47	1
1	11	30	11	1	48	1
1	11	30	11	1	49	1
1	11	30	11	1	50	1
1	11	30	11	100	42	1
1	11	30	11	100	43	1
1	11	30	11	100	44	1
1	11	30	11	100	45	1
1	11	30	11	100	46	1
1	11	30	11	100	47	1
1	11	30	11	100	48	1
1	11	30	11	100	49	1
1	11	30	11	100	50	1
1	11	30	11	100	1	3
1	11	30	11	100	8	1
1	11	30	11	100	9	1
1	11	30	11	100	10	1
1	11	30	11	100	11	1
1	11	30	11	100	12	1
1	11	30	11	100	13	1
1	11	30	11	100	14	1
1	11	30	11	100	15	1
1	11	30	11	100	16	1
1	11	30	11	100	17	1
1	11	30	11	100	18	1
1	11	30	11	100	19	1
1	11	30	11	100	20	1
1	11	30	11	100	21	11
1	11	30	11	100	22	1
1	11	30	11	100	23	1
1	11	30	11	100	24	1
1	11	30	11	100	25	1
1	11	30	11	100	26	1
1	11	30	11	100	27	1
1	11	30	11	100	28	1
1	11	30	11	100	29	1
1	11	30	11	100	30	1
1	11	30	11	100	31	1
1	11	30	11	100	32	1
1	11	30	11	100	33	1
1	11	30	11	100	34	1
1	11	30	11	100	35	1
1	11	30	11	100	36	1
1	11	30	11	100	37	1
1	11	30	11	100	38	3
1	11	30	11	100	2	2
1	11	30	11	100	3	2
1	11	30	11	100	4	2
1	11	30	11	100	5	2
1	11	30	11	100	6	2
1	11	30	11	100	39	1
1	11	30	11	100	40	1
1	11	30	11	100	41	1
1	11	30	11	1	32	1
1	11	30	11	1	33	1
1	11	30	11	1	34	1
1	11	30	11	1	35	1
1	11	30	11	1	36	1
1	11	30	11	1	37	1
1	11	30	11	1	38	1
1	11	30	11	1	39	1
1	11	30	11	1	40	1
1	11	30	11	1	41	1
1	11	30	11	1	42	1
1	11	30	11	1	51	1
1	11	30	11	1	52	1
1	11	30	11	1	53	1
1	11	30	11	1	54	1
1	11	30	11	1	55	1
1	11	30	11	1	1	2
1	11	30	11	100	7	1
\.


--
-- Data for Name: catd02_numero_inscripcion; Type: TABLE DATA; Schema: public; Owner: sisap
--

COPY catd02_numero_inscripcion (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, numero, situacion) FROM stdin;
1	11	30	11	1	2	1
1	11	30	11	1	3	1
1	11	30	11	1	4	1
1	11	30	11	100	15	1
1	11	30	11	100	16	1
1	11	30	11	100	17	1
1	11	30	11	100	18	1
1	11	30	11	100	19	1
1	11	30	11	100	20	1
1	11	30	11	100	21	1
1	11	30	11	100	22	1
1	11	30	11	100	23	1
1	11	30	11	100	24	1
1	11	30	11	100	25	1
1	11	30	11	100	26	1
1	11	30	11	1	14	1
1	11	30	11	1	15	1
1	11	30	11	1	16	1
1	11	30	11	1	17	1
1	11	30	11	1	18	1
1	11	30	11	1	19	1
1	11	30	11	1	20	1
1	11	30	11	1	21	1
1	11	30	11	1	22	1
1	11	30	11	1	23	1
1	11	30	11	1	24	1
1	11	30	11	1	25	1
1	11	30	11	1	26	1
1	11	30	11	1	27	1
1	11	30	11	1	28	1
1	11	30	11	1	29	1
1	11	30	11	1	30	1
1	11	30	11	1	31	1
1	11	30	11	1	32	1
1	11	30	11	1	33	1
1	11	30	11	1	34	1
1	11	30	11	1	35	1
1	11	30	11	1	36	1
1	11	30	11	1	37	1
1	11	30	11	1	38	1
1	11	30	11	1	39	1
1	11	30	11	1	40	1
1	11	30	11	1	41	1
1	11	30	11	1	43	1
1	11	30	11	1	44	1
1	11	30	11	1	45	1
1	11	30	11	1	46	1
1	11	30	11	1	42	1
1	11	30	11	100	1	3
1	11	30	11	100	8	1
1	11	30	11	100	9	1
1	11	30	11	1	5	1
1	11	30	11	1	6	1
1	11	30	11	1	7	1
1	11	30	11	100	10	1
1	11	30	11	100	11	1
1	11	30	11	1	8	1
1	11	30	11	100	12	1
1	11	30	11	100	13	1
1	11	30	11	100	14	1
1	11	30	11	100	27	1
1	11	30	11	100	28	1
1	11	30	11	100	29	1
1	11	30	11	100	30	1
1	11	30	11	100	31	1
1	11	30	11	100	32	1
1	11	30	11	100	33	1
1	11	30	11	100	34	1
1	11	30	11	100	35	1
1	11	30	11	100	36	1
1	11	30	11	100	37	1
1	11	30	11	100	38	3
1	11	30	11	100	2	2
1	11	30	11	100	3	2
1	11	30	11	100	4	2
1	11	30	11	100	5	2
1	11	30	11	100	6	2
1	11	30	11	100	39	1
1	11	30	11	100	40	1
1	11	30	11	100	41	1
1	11	30	11	100	42	1
1	11	30	11	100	43	1
1	11	30	11	100	44	1
1	11	30	11	100	45	1
1	11	30	11	100	46	1
1	11	30	11	100	47	1
1	11	30	11	100	48	1
1	11	30	11	100	49	1
1	11	30	11	100	50	1
1	11	30	11	1	9	1
1	11	30	11	1	10	1
1	11	30	11	1	11	1
1	11	30	11	1	12	1
1	11	30	11	1	13	1
1	11	30	11	1	47	1
1	11	30	11	1	48	1
1	11	30	11	1	49	1
1	11	30	11	1	50	1
1	11	30	11	1	51	1
1	11	30	11	1	52	1
1	11	30	11	1	53	1
1	11	30	11	1	54	1
1	11	30	11	1	55	1
1	11	30	11	1	1	2
1	11	30	11	100	7	1
\.


--
-- Data for Name: cugd90_municipio_defecto; Type: TABLE DATA; Schema: public; Owner: sisap
--

COPY cugd90_municipio_defecto (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_republica, cod_estado, cod_municipio) FROM stdin;
1	11	30	11	1	1	12	11
\.


--
-- Data for Name: shd001_registro_contribuyentes; Type: TABLE DATA; Schema: public; Owner: sisap
--

COPY shd001_registro_contribuyentes (rif_cedula, personalidad_juridica, razon_social_nombres, fecha_inscripcion, nacionalidad, estado_civil, profesion, cod_pais, cod_estado, cod_municipio, cod_parroquia, cod_centro_poblado, cod_calle_avenida, cod_vereda_edificio, numero_vivienda_local, telefonos_fijos, telefonos_celulares, correo_electronico) FROM stdin;
\.


--
-- Name: catd01_complemento_variable_primaria_pkey; Type: CONSTRAINT; Schema: public; Owner: sisap; Tablespace: 
--

ALTER TABLE ONLY catd01_complemento_variable_primaria
    ADD CONSTRAINT catd01_complemento_variable_primaria_pkey PRIMARY KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, ano_ordenanza, cod_tipo, cod_variable_principal, cod_variable_primaria);


--
-- Name: catd01_complemento_variable_principal_pkey; Type: CONSTRAINT; Schema: public; Owner: sisap; Tablespace: 
--

ALTER TABLE ONLY catd01_complemento_variable_principal
    ADD CONSTRAINT catd01_complemento_variable_principal_pkey PRIMARY KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, ano_ordenanza, cod_tipo, cod_variable_principal);


--
-- Name: catd01_complemento_variable_secundaria_pkey; Type: CONSTRAINT; Schema: public; Owner: sisap; Tablespace: 
--

ALTER TABLE ONLY catd01_complemento_variable_secundaria
    ADD CONSTRAINT catd01_complemento_variable_secundaria_pkey PRIMARY KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, ano_ordenanza, cod_tipo, cod_variable_principal, cod_variable_primaria, cod_variable_secundaria);


--
-- Name: catd01_depreciacion_edificaciones_pkey; Type: CONSTRAINT; Schema: public; Owner: sisap; Tablespace: 
--

ALTER TABLE ONLY catd01_depreciacion_edificaciones
    ADD CONSTRAINT catd01_depreciacion_edificaciones_pkey PRIMARY KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, ano_ordenanza, edad);


--
-- Name: catd01_escala_cobro_pkey; Type: CONSTRAINT; Schema: public; Owner: sisap; Tablespace: 
--

ALTER TABLE ONLY catd01_escala_cobro
    ADD CONSTRAINT catd01_escala_cobro_pkey PRIMARY KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, ano_ordenanza, escala);


--
-- Name: catd01_planta_valores_tierra_pkey; Type: CONSTRAINT; Schema: public; Owner: sisap; Tablespace: 
--

ALTER TABLE ONLY catd01_planta_valores_tierra
    ADD CONSTRAINT catd01_planta_valores_tierra_pkey PRIMARY KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, ano_ordenanza, cod_republica, cod_estado, cod_municipio, cod_parroquia, cod_zona);


--
-- Name: catd01_recargos_catastrales_pkey; Type: CONSTRAINT; Schema: public; Owner: sisap; Tablespace: 
--

ALTER TABLE ONLY catd01_recargos_catastrales
    ADD CONSTRAINT catd01_recargos_catastrales_pkey PRIMARY KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, ano_ordenanza);


--
-- Name: catd01_valor_construccion_pkey; Type: CONSTRAINT; Schema: public; Owner: sisap; Tablespace: 
--

ALTER TABLE ONLY catd01_valor_construccion
    ADD CONSTRAINT catd01_valor_construccion_pkey PRIMARY KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, ano_ordenanza, cod_tipo);


--
-- Name: catd02_ficha_datos_pkey; Type: CONSTRAINT; Schema: public; Owner: sisap; Tablespace: 
--

ALTER TABLE ONLY catd02_ficha_datos
    ADD CONSTRAINT catd02_ficha_datos_pkey PRIMARY KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_ficha);


--
-- Name: catd02_ficha_tipologia_pkey; Type: CONSTRAINT; Schema: public; Owner: sisap; Tablespace: 
--

ALTER TABLE ONLY catd02_ficha_tipologia
    ADD CONSTRAINT catd02_ficha_tipologia_pkey PRIMARY KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_ficha, consecutivo);


--
-- Name: catd02_ficha_variables_pkey; Type: CONSTRAINT; Schema: public; Owner: sisap; Tablespace: 
--

ALTER TABLE ONLY catd02_ficha_variables
    ADD CONSTRAINT catd02_ficha_variables_pkey PRIMARY KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_ficha, cod_tipo, cod_variable_principal, cod_variable_primaria, cod_variable_secundaria);


--
-- Name: catd02_numero_archivo_pkey; Type: CONSTRAINT; Schema: public; Owner: sisap; Tablespace: 
--

ALTER TABLE ONLY catd02_numero_archivo
    ADD CONSTRAINT catd02_numero_archivo_pkey PRIMARY KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, numero);


--
-- Name: catd02_numero_ficha_pkey; Type: CONSTRAINT; Schema: public; Owner: sisap; Tablespace: 
--

ALTER TABLE ONLY catd02_numero_ficha
    ADD CONSTRAINT catd02_numero_ficha_pkey PRIMARY KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, numero);


--
-- Name: catd02_numero_inscripcion_pkey; Type: CONSTRAINT; Schema: public; Owner: sisap; Tablespace: 
--

ALTER TABLE ONLY catd02_numero_inscripcion
    ADD CONSTRAINT catd02_numero_inscripcion_pkey PRIMARY KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, numero);


--
-- Name: cugd90_municipio_defecto_pkey; Type: CONSTRAINT; Schema: public; Owner: sisap; Tablespace: 
--

ALTER TABLE ONLY cugd90_municipio_defecto
    ADD CONSTRAINT cugd90_municipio_defecto_pkey PRIMARY KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_republica, cod_estado, cod_municipio);


--
-- Name: shd001_registro_contribuyentes_pkey; Type: CONSTRAINT; Schema: public; Owner: sisap; Tablespace: 
--

ALTER TABLE ONLY shd001_registro_contribuyentes
    ADD CONSTRAINT shd001_registro_contribuyentes_pkey PRIMARY KEY (rif_cedula);


--
-- Name: catd01_complemento_variable_primaria_1; Type: FK CONSTRAINT; Schema: public; Owner: sisap
--

ALTER TABLE ONLY catd01_complemento_variable_primaria
    ADD CONSTRAINT catd01_complemento_variable_primaria_1 FOREIGN KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, ano_ordenanza, cod_tipo, cod_variable_principal) REFERENCES catd01_complemento_variable_principal(cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, ano_ordenanza, cod_tipo, cod_variable_principal) ON DELETE CASCADE;


--
-- Name: catd01_complemento_variable_principal_1; Type: FK CONSTRAINT; Schema: public; Owner: sisap
--

ALTER TABLE ONLY catd01_complemento_variable_principal
    ADD CONSTRAINT catd01_complemento_variable_principal_1 FOREIGN KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, ano_ordenanza, cod_tipo) REFERENCES catd01_valor_construccion(cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, ano_ordenanza, cod_tipo) ON DELETE CASCADE;


--
-- Name: catd01_complemento_variable_secundaria_1; Type: FK CONSTRAINT; Schema: public; Owner: sisap
--

ALTER TABLE ONLY catd01_complemento_variable_secundaria
    ADD CONSTRAINT catd01_complemento_variable_secundaria_1 FOREIGN KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, ano_ordenanza, cod_tipo, cod_variable_principal, cod_variable_primaria) REFERENCES catd01_complemento_variable_primaria(cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, ano_ordenanza, cod_tipo, cod_variable_principal, cod_variable_primaria) ON DELETE CASCADE;


--
-- PostgreSQL database dump complete
--


CREATE OR REPLACE VIEW v_busqueda_catd02_ficha_datos AS 
 SELECT catd02_ficha_datos.cod_presi, catd02_ficha_datos.cod_entidad, catd02_ficha_datos.cod_tipo_inst, catd02_ficha_datos.cod_inst, catd02_ficha_datos.cod_dep, catd02_ficha_datos.cod_ficha, catd02_ficha_datos.cod_inscripcion, catd02_ficha_datos.fecha_inscripcion, catd02_ficha_datos.cod_control_archivo, catd02_ficha_datos.ano_ordenanza, catd02_ficha_datos.cod_ant_edo, ( SELECT x.denominacion
           FROM cugd01_estados x
          WHERE x.cod_republica = 1 AND x.cod_estado = catd02_ficha_datos.cod_ant_edo) AS deno_estado1, catd02_ficha_datos.cod_ant_mun, ( SELECT x.denominacion
           FROM cugd01_municipios x
          WHERE x.cod_republica = 1 AND x.cod_estado = catd02_ficha_datos.cod_ant_edo AND x.cod_municipio = catd02_ficha_datos.cod_ant_mun) AS deno_municipio1, catd02_ficha_datos.cod_ant_prr, ( SELECT x.denominacion
           FROM cugd01_parroquias x
          WHERE x.cod_republica = 1 AND x.cod_estado = catd02_ficha_datos.cod_ant_edo AND x.cod_municipio = catd02_ficha_datos.cod_ant_mun AND x.cod_parroquia = catd02_ficha_datos.cod_ant_prr) AS deno_parroquia1, catd02_ficha_datos.cod_ant_sec, ( SELECT x.denominacion
           FROM cugd01_centros_poblados x
          WHERE x.cod_republica = 1 AND x.cod_estado = catd02_ficha_datos.cod_ant_edo AND x.cod_municipio = catd02_ficha_datos.cod_ant_mun AND x.cod_parroquia = catd02_ficha_datos.cod_ant_prr AND x.cod_centro = catd02_ficha_datos.cod_ant_sec) AS deno_sector1, catd02_ficha_datos.cod_ant_man, catd02_ficha_datos.cod_ant_par, catd02_ficha_datos.cod_ant_blo, catd02_ficha_datos.cod_ant_piso, catd02_ficha_datos.cod_ant_apto, catd02_ficha_datos.cod_act_edo, ( SELECT x.denominacion
           FROM cugd01_estados x
          WHERE x.cod_republica = 1 AND x.cod_estado = catd02_ficha_datos.cod_act_edo) AS deno_estado2, catd02_ficha_datos.cod_act_mun, ( SELECT x.denominacion
           FROM cugd01_municipios x
          WHERE x.cod_republica = 1 AND x.cod_estado = catd02_ficha_datos.cod_act_edo AND x.cod_municipio = catd02_ficha_datos.cod_act_mun) AS deno_municipio2, catd02_ficha_datos.cod_act_prr, ( SELECT x.denominacion
           FROM cugd01_parroquias x
          WHERE x.cod_republica = 1 AND x.cod_estado = catd02_ficha_datos.cod_act_edo AND x.cod_municipio = catd02_ficha_datos.cod_act_mun AND x.cod_parroquia = catd02_ficha_datos.cod_act_prr) AS deno_parroquia2, catd02_ficha_datos.cod_act_amb_t, catd02_ficha_datos.cod_act_amb, catd02_ficha_datos.cod_act_sec, ( SELECT x.denominacion
           FROM cugd01_centros_poblados x
          WHERE x.cod_republica = 1 AND x.cod_estado = catd02_ficha_datos.cod_act_edo AND x.cod_municipio = catd02_ficha_datos.cod_act_mun AND x.cod_parroquia = catd02_ficha_datos.cod_act_prr AND x.cod_centro = catd02_ficha_datos.cod_act_sec) AS deno_sector2, catd02_ficha_datos.cod_act_man, catd02_ficha_datos.cod_act_par, catd02_ficha_datos.cod_act_sbp, catd02_ficha_datos.cod_act_niv, catd02_ficha_datos.cod_act_und, catd02_ficha_datos.tilde_ciudad, catd02_ficha_datos.tilde_localidad, catd02_ficha_datos.tilde_urbanizacion, catd02_ficha_datos.tilde_conjunto_residencial, catd02_ficha_datos.tilde_barrio, catd02_ficha_datos.tilde_sector, catd02_ficha_datos.nombre, catd02_ficha_datos.tilde_av_uno, catd02_ficha_datos.tilde_clle_uno, catd02_ficha_datos.tilde_crr_uno, catd02_ficha_datos.tilde_trav_uno, catd02_ficha_datos.tilde_prol_uno, catd02_ficha_datos.tilde_crrt_uno, catd02_ficha_datos.tilde_cjn_uno, catd02_ficha_datos.tilde_psje_uno, catd02_ficha_datos.tilde_blv_uno, catd02_ficha_datos.tilde_vda_uno, catd02_ficha_datos.tilde_esc_uno, catd02_ficha_datos.tilde_snd_uno, catd02_ficha_datos.tilde_tcal_uno, catd02_ficha_datos.tilde_cno_uno, catd02_ficha_datos.direccion_uno, catd02_ficha_datos.tilde_av_dos, catd02_ficha_datos.tilde_clle_dos, catd02_ficha_datos.tilde_crr_dos, catd02_ficha_datos.tilde_trav_dos, catd02_ficha_datos.tilde_prol_dos, catd02_ficha_datos.tilde_crrt_dos, catd02_ficha_datos.tilde_cjn_dos, catd02_ficha_datos.tilde_psje_dos, catd02_ficha_datos.tilde_blv_dos, catd02_ficha_datos.tilde_vda_dos, catd02_ficha_datos.tilde_esc_dos, catd02_ficha_datos.tilde_snd_dos, catd02_ficha_datos.tilde_tcal_dos, catd02_ficha_datos.tilde_cno_dos, catd02_ficha_datos.direccion_dos, catd02_ficha_datos.tilde_av_tres, catd02_ficha_datos.tilde_clle_tres, catd02_ficha_datos.tilde_crr_tres, catd02_ficha_datos.tilde_trav_tres, catd02_ficha_datos.tilde_prol_tres, catd02_ficha_datos.tilde_crrt_tres, catd02_ficha_datos.tilde_cjn_tres, catd02_ficha_datos.tilde_psje_tres, catd02_ficha_datos.tilde_blv_tres, catd02_ficha_datos.tilde_vda_tres, catd02_ficha_datos.tilde_esc_tres, catd02_ficha_datos.tilde_snd_tres, catd02_ficha_datos.tilde_tcal_tres, catd02_ficha_datos.tilde_cno_tres, catd02_ficha_datos.direccion_tres, catd02_ficha_datos.tilde_edif_uno, catd02_ficha_datos.tilde_apto_uno, catd02_ficha_datos.tilde_qta_uno, catd02_ficha_datos.tilde_casa_uno, catd02_ficha_datos.tilde_rancho_uno, catd02_ficha_datos.tilde_cc_uno, catd02_ficha_datos.tilde_local_uno, catd02_ficha_datos.tilde_oficina_uno, catd02_ficha_datos.tilde_otro_uno, catd02_ficha_datos.tipo_vivienda, catd02_ficha_datos.nombre_inmueble, catd02_ficha_datos.numero_civico, catd02_ficha_datos.telefono_inmueble, catd02_ficha_datos.punto_referencia_inmueble, catd02_ficha_datos.personalidad_juridica, catd02_ficha_datos.cedula_rif, catd02_ficha_datos.nombre_ocupante, catd02_ficha_datos.localidad_ocupante, catd02_ficha_datos.urb_barrio_sector_ocupante, catd02_ficha_datos.tilde_av_cuatro, catd02_ficha_datos.tilde_clle_cuatro, catd02_ficha_datos.tilde_crr_cuatro, catd02_ficha_datos.tilde_trav_cuatro, catd02_ficha_datos.tilde_prol_cuatro, catd02_ficha_datos.tilde_crrt_cuatro, catd02_ficha_datos.tilde_cjn_cuatro, catd02_ficha_datos.tilde_psje_cuatro, catd02_ficha_datos.tilde_blv_cuatro, catd02_ficha_datos.tilde_vda_cuatro, catd02_ficha_datos.tilde_esc_cuatro, catd02_ficha_datos.tilde_snd_cuatro, catd02_ficha_datos.tilde_tcal_cuatro, catd02_ficha_datos.tilde_cno_cuatro, catd02_ficha_datos.direccion_cuatro, catd02_ficha_datos.tilde_av_cinco, catd02_ficha_datos.tilde_clle_cinco, catd02_ficha_datos.tilde_crr_cinco, catd02_ficha_datos.tilde_trav_cinco, catd02_ficha_datos.tilde_prol_cinco, catd02_ficha_datos.tilde_crrt_cinco, catd02_ficha_datos.tilde_cjn_cinco, catd02_ficha_datos.tilde_psje_cinco, catd02_ficha_datos.tilde_blv_cinco, catd02_ficha_datos.tilde_vda_cinco, catd02_ficha_datos.tilde_esc_cinco, catd02_ficha_datos.tilde_snd_cinco, catd02_ficha_datos.tilde_tcal_cinco, catd02_ficha_datos.tilde_cno_cinco, catd02_ficha_datos.direccion_cinco, catd02_ficha_datos.tilde_av_seis, catd02_ficha_datos.tilde_clle_seis, catd02_ficha_datos.tilde_crr_seis, catd02_ficha_datos.tilde_trav_seis, catd02_ficha_datos.tilde_prol_seis, catd02_ficha_datos.tilde_crrt_seis, catd02_ficha_datos.tilde_cjn_seis, catd02_ficha_datos.tilde_psje_seis, catd02_ficha_datos.tilde_blv_seis, catd02_ficha_datos.tilde_vda_seis, catd02_ficha_datos.tilde_esc_seis, catd02_ficha_datos.tilde_snd_seis, catd02_ficha_datos.tilde_tcal_seis, catd02_ficha_datos.tilde_cno_seis, catd02_ficha_datos.direccion_seis, catd02_ficha_datos.tilde_edif_dos, catd02_ficha_datos.tilde_apto_dos, catd02_ficha_datos.tilde_qta_dos, catd02_ficha_datos.tilde_casa_dos, catd02_ficha_datos.tilde_rancho_dos, catd02_ficha_datos.tilde_cc_dos, catd02_ficha_datos.tilde_local_dos, catd02_ficha_datos.tilde_oficina_dos, catd02_ficha_datos.tilde_otro_dos, catd02_ficha_datos.nombre_repre, catd02_ficha_datos.numero_civico_repre, catd02_ficha_datos.telefono_repre, catd02_ficha_datos.punto_referencia_repre, catd02_ficha_datos.tilde_topo, catd02_ficha_datos.tilde_acceso, catd02_ficha_datos.tilde_forma, catd02_ficha_datos.tilde_ubica, catd02_ficha_datos.tilde_entorno, catd02_ficha_datos.tilde_mejora, catd02_ficha_datos.tilde_tenencia, catd02_ficha_datos.tilde_regimen, catd02_ficha_datos.tilde_uso, catd02_ficha_datos.tilde_servicio, catd02_ficha_datos.tilde_tipo, catd02_ficha_datos.tilde_descripcionuso, catd02_ficha_datos.tilde_tenencia_const, catd02_ficha_datos.tilde_regi_prop, catd02_ficha_datos.tilde_soporte, catd02_ficha_datos.tilde_techo, catd02_ficha_datos.tilde_cubierta, catd02_ficha_datos.tilde_pared_tipo, catd02_ficha_datos.tilde_pared_acaba, catd02_ficha_datos.tilde_conserva, catd02_ficha_datos.ano_construccion, catd02_ficha_datos.porce_refaccion, catd02_ficha_datos.numero_niveles, catd02_ficha_datos.ano_refaccion, catd02_ficha_datos.edad_efectiva, catd02_ficha_datos.numero_edificaciones, catd02_ficha_datos.registro_numero, catd02_ficha_datos.registro_folio, catd02_ficha_datos.registro_tomo, catd02_ficha_datos.registro_protocolo, catd02_ficha_datos.registro_fecha, catd02_ficha_datos.registro_area_terreno, catd02_ficha_datos.registro_area_construccion, catd02_ficha_datos.registro_monto, catd02_ficha_datos.valoracion_area, catd02_ficha_datos.valoracion_valor_unitario, catd02_ficha_datos.valoracion_sector, catd02_ficha_datos.valoracion_ajuste_area, catd02_ficha_datos.valoracion_ajuste_forma, catd02_ficha_datos.valoracion_valor_ajustado, catd02_ficha_datos.valoracion_valor_total, catd02_ficha_datos.observaciones_ficha, catd02_ficha_datos.lindero_norte, catd02_ficha_datos.lindero_sur, catd02_ficha_datos.lindero_este, catd02_ficha_datos.lindero_oeste, catd02_ficha_datos.coordenada_norte, catd02_ficha_datos.coordenada_este, catd02_ficha_datos.huso, catd02_ficha_datos.observaciones_generales, catd02_ficha_datos.fecha_primera_visita, catd02_ficha_datos.fecha_levantamiento, catd02_ficha_datos.elaborado_nombre, catd02_ficha_datos.elaborado_ci, catd02_ficha_datos.revisado_nombre, catd02_ficha_datos.revisado_ci, catd02_ficha_datos.croquis, (((catd02_ficha_datos.nombre_inmueble || ' '::text) || catd02_ficha_datos.cedula_rif::text) || ' '::text) || catd02_ficha_datos.nombre_ocupante AS denominacion_busqueda
   FROM catd02_ficha_datos;

ALTER TABLE v_busqueda_catd02_ficha_datos OWNER TO sisap;



CREATE TABLE shd003_codigo_ingresos
(
  cod_ingreso integer NOT NULL, -- Código de ingreso
  denominacion character varying(100) NOT NULL, -- Denominación de ingreso
  cod_partida integer NOT NULL, -- Partida
  cod_generica integer NOT NULL, -- Código de la generica
  cod_especifica integer NOT NULL, -- Código de la especifica
  cod_subespec integer NOT NULL, -- Código de la Subespecifica
  cod_auxiliar integer NOT NULL, -- Código del auxiliar
  CONSTRAINT shd003_codigo_ingresos_pkey PRIMARY KEY (cod_ingreso)
)
WITH (OIDS=FALSE);
ALTER TABLE shd003_codigo_ingresos OWNER TO sisap;
COMMENT ON TABLE shd003_codigo_ingresos IS 'Registro de código de ingresos';
COMMENT ON COLUMN shd003_codigo_ingresos.cod_ingreso IS 'Código de ingreso';
COMMENT ON COLUMN shd003_codigo_ingresos.denominacion IS 'Denominación de ingreso';
COMMENT ON COLUMN shd003_codigo_ingresos.cod_partida IS 'Partida';
COMMENT ON COLUMN shd003_codigo_ingresos.cod_generica IS 'Código de la generica';
COMMENT ON COLUMN shd003_codigo_ingresos.cod_especifica IS 'Código de la especifica';
COMMENT ON COLUMN shd003_codigo_ingresos.cod_subespec IS 'Código de la Subespecifica';
COMMENT ON COLUMN shd003_codigo_ingresos.cod_auxiliar IS 'Código del auxiliar';

-- Table: shd001_registro_contribuyentes

-- DROP TABLE shd001_registro_contribuyentes;

CREATE TABLE shd001_registro_contribuyentes
(
  rif_cedula character varying(20) NOT NULL, -- Rif o cédela de identidad
  personalidad_juridica integer NOT NULL, -- Personalidad Juridica...
  razon_social_nombres character varying(100) NOT NULL, -- Razón social o Nombres y Apellidos
  fecha_inscripcion date NOT NULL, -- Fecha de Inscripción
  nacionalidad integer, -- Nacionalidad...
  estado_civil integer, -- Estado civil...
  profesion integer, -- Codigo de la profesion (Enlace con personal)
  cod_pais integer NOT NULL, -- Código del pais
  cod_estado integer NOT NULL, -- Código del estado
  cod_municipio integer NOT NULL, -- Código del municipio
  cod_parroquia integer NOT NULL, -- Código de la parroquia
  cod_centro_poblado integer NOT NULL, -- Código del centro poblado...
  cod_calle_avenida integer NOT NULL, -- Código de la calle o avenida
  cod_vereda_edificio integer NOT NULL, -- Código de la vereda o edificio
  numero_vivienda_local character varying(30) NOT NULL, -- Número de la vivienda, local, piso y apartamento
  telefonos_fijos character varying(30), -- Telefonos fijos
  telefonos_celulares character varying(30), -- Telefonos celulares
  correo_electronico character varying(30), -- Correo electrónico
  CONSTRAINT shd001_registro_contribuyentes_pkey PRIMARY KEY (rif_cedula)
)
WITH (OIDS=FALSE);
ALTER TABLE shd001_registro_contribuyentes OWNER TO sisap;
COMMENT ON TABLE shd001_registro_contribuyentes IS 'Registro general de contribuyentes';
COMMENT ON COLUMN shd001_registro_contribuyentes.rif_cedula IS 'Rif o cédela de identidad';
COMMENT ON COLUMN shd001_registro_contribuyentes.personalidad_juridica IS 'Personalidad Juridica
1.- Natural
2.- Juridica';
COMMENT ON COLUMN shd001_registro_contribuyentes.razon_social_nombres IS 'Razón social o Nombres y Apellidos';
COMMENT ON COLUMN shd001_registro_contribuyentes.fecha_inscripcion IS 'Fecha de Inscripción';
COMMENT ON COLUMN shd001_registro_contribuyentes.nacionalidad IS 'Nacionalidad
1.- Extranjera
2.- Venezolana';
COMMENT ON COLUMN shd001_registro_contribuyentes.estado_civil IS 'Estado civil
1.- Soltero
2.- Casado
3.- Divorciado
4.- Viudo
5.- Otro
';
COMMENT ON COLUMN shd001_registro_contribuyentes.profesion IS 'Codigo de la profesion (Enlace con personal)';
COMMENT ON COLUMN shd001_registro_contribuyentes.cod_pais IS 'Código del pais';
COMMENT ON COLUMN shd001_registro_contribuyentes.cod_estado IS 'Código del estado';
COMMENT ON COLUMN shd001_registro_contribuyentes.cod_municipio IS 'Código del municipio';
COMMENT ON COLUMN shd001_registro_contribuyentes.cod_parroquia IS 'Código de la parroquia';
COMMENT ON COLUMN shd001_registro_contribuyentes.cod_centro_poblado IS 'Código del centro poblado
Urbanizaciones
Barrios
Caserios
Otros
';
COMMENT ON COLUMN shd001_registro_contribuyentes.cod_calle_avenida IS 'Código de la calle o avenida';
COMMENT ON COLUMN shd001_registro_contribuyentes.cod_vereda_edificio IS 'Código de la vereda o edificio';
COMMENT ON COLUMN shd001_registro_contribuyentes.numero_vivienda_local IS 'Número de la vivienda, local, piso y apartamento';
COMMENT ON COLUMN shd001_registro_contribuyentes.telefonos_fijos IS 'Telefonos fijos';
COMMENT ON COLUMN shd001_registro_contribuyentes.telefonos_celulares IS 'Telefonos celulares';
COMMENT ON COLUMN shd001_registro_contribuyentes.correo_electronico IS 'Correo electrónico';



-- Table: shd002_cobradores

-- DROP TABLE shd002_cobradores;

CREATE TABLE shd002_cobradores
(
  cod_presi integer NOT NULL, -- Código de la presidencia
  cod_entidad integer NOT NULL, -- Código de la entidad
  cod_tipo_inst integer NOT NULL, -- Código tipo de la institución
  cod_inst integer NOT NULL, -- Código de la Institución
  cod_dep integer NOT NULL, -- Código de la dependencia
  rif_ci character varying(20) NOT NULL, -- Rif o Cédula de Identidad
  personalidad integer NOT NULL, -- Personalidad Juridica...
  nombre_razon character varying(100) NOT NULL, -- Nombre y apellidos / Razón social
  fecha_ingreso date NOT NULL, -- Fecha de ingreso
  recurso_cobro integer NOT NULL, -- Recurso para el cobro...
  condicion_actividad integer NOT NULL, -- Condición de actividad...
  CONSTRAINT shd002_cobradores_pkey PRIMARY KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, rif_ci)
)
WITH (OIDS=FALSE);
ALTER TABLE shd002_cobradores OWNER TO sisap;
COMMENT ON TABLE shd002_cobradores IS 'Registro de cobradores';
COMMENT ON COLUMN shd002_cobradores.cod_presi IS 'Código de la presidencia';
COMMENT ON COLUMN shd002_cobradores.cod_entidad IS 'Código de la entidad';
COMMENT ON COLUMN shd002_cobradores.cod_tipo_inst IS 'Código tipo de la institución';
COMMENT ON COLUMN shd002_cobradores.cod_inst IS 'Código de la Institución';
COMMENT ON COLUMN shd002_cobradores.cod_dep IS 'Código de la dependencia';
COMMENT ON COLUMN shd002_cobradores.rif_ci IS 'Rif o Cédula de Identidad';
COMMENT ON COLUMN shd002_cobradores.personalidad IS 'Personalidad Juridica
1.- Natural
2.- Juridica';
COMMENT ON COLUMN shd002_cobradores.nombre_razon IS 'Nombre y apellidos / Razón social';
COMMENT ON COLUMN shd002_cobradores.fecha_ingreso IS 'Fecha de ingreso';
COMMENT ON COLUMN shd002_cobradores.recurso_cobro IS 'Recurso para el cobro
1.- Ninguno
2.- Bicicleta
3.- Moto
4.- Vehiculo';
COMMENT ON COLUMN shd002_cobradores.condicion_actividad IS 'Condición de actividad
1.- Activo
2.- Retirado
';



-- Table: shd100_actividades

-- DROP TABLE shd100_actividades;

CREATE TABLE shd100_actividades
(
  cod_presi integer NOT NULL, -- Código de la presidencia
  cod_entidad integer NOT NULL, -- Código de la entidad
  cod_tipo_inst integer NOT NULL, -- Código tipo de Institución
  cod_inst integer NOT NULL, -- Código de la Institución
  cod_dep integer NOT NULL, -- Código de la dependencia
  cod_actividad character varying(20) NOT NULL, -- Código de la actividad economica
  denominacion_actividad text NOT NULL, -- Denominación de la actividad economica
  alicuota numeric(5,2) DEFAULT 0, -- Porcentaje alicuota
  unidades_tributarias numeric(5,2), -- Unidades tributarias
  minimo_tributable numeric(26,2), -- Monto minimo tributable
  CONSTRAINT shd100_actividades_pkey PRIMARY KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_actividad)
)
WITH (OIDS=FALSE);
ALTER TABLE shd100_actividades OWNER TO sisap;
COMMENT ON TABLE shd100_actividades IS 'Registra de la ordenanza de patente de industria y comercio las actividades economicas';
COMMENT ON COLUMN shd100_actividades.cod_presi IS 'Código de la presidencia';
COMMENT ON COLUMN shd100_actividades.cod_entidad IS 'Código de la entidad';
COMMENT ON COLUMN shd100_actividades.cod_tipo_inst IS 'Código tipo de Institución';
COMMENT ON COLUMN shd100_actividades.cod_inst IS 'Código de la Institución';
COMMENT ON COLUMN shd100_actividades.cod_dep IS 'Código de la dependencia';
COMMENT ON COLUMN shd100_actividades.cod_actividad IS 'Código de la actividad economica';
COMMENT ON COLUMN shd100_actividades.denominacion_actividad IS 'Denominación de la actividad economica';
COMMENT ON COLUMN shd100_actividades.alicuota IS 'Porcentaje alicuota';
COMMENT ON COLUMN shd100_actividades.unidades_tributarias IS 'Unidades tributarias';
COMMENT ON COLUMN shd100_actividades.minimo_tributable IS 'Monto minimo tributable';

-- Table: shd100_solicitud

-- DROP TABLE shd100_solicitud;

CREATE TABLE shd100_solicitud
(
  cod_presi integer NOT NULL, -- Código de la presidencia
  cod_entidad integer NOT NULL, -- Código de la entidad
  cod_tipo_inst integer NOT NULL, -- Código tipo de Institución
  cod_inst integer NOT NULL, -- Código de la Institución
  cod_dep integer NOT NULL, -- Código de la dependencia
  numero_solicitud character varying(20) NOT NULL, -- Número de solicitud de licencia de patente de industria y comercio
  fecha_solicitud date NOT NULL, -- Fecha de la solicitud
  rif_cedula character varying(20) NOT NULL, -- Rif o Cédula de Identidad
  numero_ficha_catastral integer NOT NULL, -- Número de ficha catastral
  capital numeric(26,2) NOT NULL, -- Capital de la empresa
  horario_trab_desde numeric(4,2), -- Horario de trabajo desde
  horario_trab_hasta numeric(4,2), -- Horario de trabajo hasta
  tipo_establecimiento integer NOT NULL, -- Tipo de establecimiento...
  tipo_local integer NOT NULL, -- Tipo de local...
  nacionalidad integer NOT NULL, -- Nacionalidad...
  cedula_identidad integer NOT NULL, -- Cédula de Identidad
  nombres_apellidos character varying(100) NOT NULL, -- Nombres y Apellidos
  cod_pais integer NOT NULL, -- Código del pais
  cod_estado integer NOT NULL, -- Código del estado
  cod_municipio integer NOT NULL, -- Código del municipio
  cod_parroquia integer NOT NULL, -- Código de la parroquia
  cod_centro integer NOT NULL, -- Código del centro poblado
  cod_vialidad integer NOT NULL, -- Código de calle o avenida
  cod_vereda integer, -- Código de la vereda o edificio
  numero_casa_local character varying(30) NOT NULL, -- Número de la casa o local
  telefonos_fijos character varying(50), -- Teléfonos fijos
  telefonos_celulares character varying(50), -- Teléfonos celulares
  correo_electronico character varying(50), -- Correo electrónico
  fecha_inicio_const date NOT NULL, -- Fecha de Inicio de la constitución
  fecha_cierre_const date NOT NULL, -- Fecha de cierre de la constitución
  fecha_inicio_econo date NOT NULL, -- Fecha de inicio economico
  fecha_cierre_economico date NOT NULL, -- fecha de cierre economico
  registro_mercantil text, -- Registro mercantil de la empresa
  tiene_sucursal integer NOT NULL, -- Tiene sucursal ?...
  es_fabricante integer NOT NULL, -- Es fabricante de algún producto...
  numero_empleado integer, -- Número de empleados
  numero_obreros integer, -- Número de obreros
  distancia_bar numeric(8,2), -- Distancia de un bar
  distancia_hospital numeric(8,2), -- Distancia de un hospital, clinica o dispensario (Centro de salud)
  distancia_educativo numeric(8,2), -- Distancia de una establecimiento educativo
  distancia_funeraria numeric(8,2), -- Distancia de una feneraria
  distancia_estacion numeric(8,2), -- Distancia de una estación de servicio
  distancia_gubernam numeric(8,2), -- Distancia de una entidad gubernamental
  tilde_reg_mercantil integer, -- Registro mercantil
  tilde_fotoco_ci integer, -- Fotocopia de la cédula de identidad
  tilde_acta_const integer, -- Acta constitutiva
  tilde_uso_conforme integer, -- Uso conforme
  tilde_croquis integer, -- Croquis elaborado por el contribuyente
  tilde_bomberos integer, -- Autorización de los bomberos
  tilde_rif integer, -- Certificado del R.I.F.
  tilde_solvencia integer, -- Solvencia de propiedad inmobiliaria
  tilde_concejo integer, -- Constancia del concejo comunal
  tilde_recibo integer, -- Recibo de cancelación de la solvencia
  tilde_planilla integer, -- Planilla de solicitud
  tilde_permiso integer, -- Permiso otorgado por organismo nacionales
  numero_patente character varying(20), -- Número de patente
  categoria_comercial integer,
  mercado_cubre integer,
  CONSTRAINT shd100_solicitud_pkey PRIMARY KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, numero_solicitud)
)
WITH (OIDS=FALSE);
ALTER TABLE shd100_solicitud OWNER TO sisap;
COMMENT ON TABLE shd100_solicitud IS 'Registro de las solicitudes de licencia de patente de industria y comercio';
COMMENT ON COLUMN shd100_solicitud.cod_presi IS 'Código de la presidencia';
COMMENT ON COLUMN shd100_solicitud.cod_entidad IS 'Código de la entidad';
COMMENT ON COLUMN shd100_solicitud.cod_tipo_inst IS 'Código tipo de Institución';
COMMENT ON COLUMN shd100_solicitud.cod_inst IS 'Código de la Institución';
COMMENT ON COLUMN shd100_solicitud.cod_dep IS 'Código de la dependencia';
COMMENT ON COLUMN shd100_solicitud.numero_solicitud IS 'Número de solicitud de licencia de patente de industria y comercio';
COMMENT ON COLUMN shd100_solicitud.fecha_solicitud IS 'Fecha de la solicitud';
COMMENT ON COLUMN shd100_solicitud.rif_cedula IS 'Rif o Cédula de Identidad';
COMMENT ON COLUMN shd100_solicitud.numero_ficha_catastral IS 'Número de ficha catastral';
COMMENT ON COLUMN shd100_solicitud.capital IS 'Capital de la empresa';
COMMENT ON COLUMN shd100_solicitud.horario_trab_desde IS 'Horario de trabajo desde';
COMMENT ON COLUMN shd100_solicitud.horario_trab_hasta IS 'Horario de trabajo hasta';
COMMENT ON COLUMN shd100_solicitud.tipo_establecimiento IS 'Tipo de establecimiento
1.- Industrial
2.- Comercial
3.- Similar indole';
COMMENT ON COLUMN shd100_solicitud.tipo_local IS 'Tipo de local
1.- Un inmueble
2.- Mas de un Inmueble
3.- Parte de un inmueble';
COMMENT ON COLUMN shd100_solicitud.nacionalidad IS 'Nacionalidad
1.- Venezolana
2.- Extranjera';
COMMENT ON COLUMN shd100_solicitud.cedula_identidad IS 'Cédula de Identidad';
COMMENT ON COLUMN shd100_solicitud.nombres_apellidos IS 'Nombres y Apellidos';
COMMENT ON COLUMN shd100_solicitud.cod_pais IS 'Código del pais';
COMMENT ON COLUMN shd100_solicitud.cod_estado IS 'Código del estado';
COMMENT ON COLUMN shd100_solicitud.cod_municipio IS 'Código del municipio';
COMMENT ON COLUMN shd100_solicitud.cod_parroquia IS 'Código de la parroquia';
COMMENT ON COLUMN shd100_solicitud.cod_centro IS 'Código del centro poblado';
COMMENT ON COLUMN shd100_solicitud.cod_vialidad IS 'Código de calle o avenida';
COMMENT ON COLUMN shd100_solicitud.cod_vereda IS 'Código de la vereda o edificio';
COMMENT ON COLUMN shd100_solicitud.numero_casa_local IS 'Número de la casa o local';
COMMENT ON COLUMN shd100_solicitud.telefonos_fijos IS 'Teléfonos fijos';
COMMENT ON COLUMN shd100_solicitud.telefonos_celulares IS 'Teléfonos celulares';
COMMENT ON COLUMN shd100_solicitud.correo_electronico IS 'Correo electrónico';
COMMENT ON COLUMN shd100_solicitud.fecha_inicio_const IS 'Fecha de Inicio de la constitución';
COMMENT ON COLUMN shd100_solicitud.fecha_cierre_const IS 'Fecha de cierre de la constitución';
COMMENT ON COLUMN shd100_solicitud.fecha_inicio_econo IS 'Fecha de inicio economico';
COMMENT ON COLUMN shd100_solicitud.fecha_cierre_economico IS 'fecha de cierre economico';
COMMENT ON COLUMN shd100_solicitud.registro_mercantil IS 'Registro mercantil de la empresa';
COMMENT ON COLUMN shd100_solicitud.tiene_sucursal IS 'Tiene sucursal ?
1.- Si
2.- No';
COMMENT ON COLUMN shd100_solicitud.es_fabricante IS 'Es fabricante de algún producto
1.- Si
2.- No';
COMMENT ON COLUMN shd100_solicitud.numero_empleado IS 'Número de empleados
';
COMMENT ON COLUMN shd100_solicitud.numero_obreros IS 'Número de obreros';
COMMENT ON COLUMN shd100_solicitud.distancia_bar IS 'Distancia de un bar';
COMMENT ON COLUMN shd100_solicitud.distancia_hospital IS 'Distancia de un hospital, clinica o dispensario (Centro de salud)';
COMMENT ON COLUMN shd100_solicitud.distancia_educativo IS 'Distancia de una establecimiento educativo';
COMMENT ON COLUMN shd100_solicitud.distancia_funeraria IS 'Distancia de una feneraria';
COMMENT ON COLUMN shd100_solicitud.distancia_estacion IS 'Distancia de una estación de servicio';
COMMENT ON COLUMN shd100_solicitud.distancia_gubernam IS 'Distancia de una entidad gubernamental';
COMMENT ON COLUMN shd100_solicitud.tilde_reg_mercantil IS 'Registro mercantil';
COMMENT ON COLUMN shd100_solicitud.tilde_fotoco_ci IS 'Fotocopia de la cédula de identidad';
COMMENT ON COLUMN shd100_solicitud.tilde_acta_const IS 'Acta constitutiva';
COMMENT ON COLUMN shd100_solicitud.tilde_uso_conforme IS 'Uso conforme';
COMMENT ON COLUMN shd100_solicitud.tilde_croquis IS 'Croquis elaborado por el contribuyente';
COMMENT ON COLUMN shd100_solicitud.tilde_bomberos IS 'Autorización de los bomberos';
COMMENT ON COLUMN shd100_solicitud.tilde_rif IS 'Certificado del R.I.F.';
COMMENT ON COLUMN shd100_solicitud.tilde_solvencia IS 'Solvencia de propiedad inmobiliaria';
COMMENT ON COLUMN shd100_solicitud.tilde_concejo IS 'Constancia del concejo comunal';
COMMENT ON COLUMN shd100_solicitud.tilde_recibo IS 'Recibo de cancelación de la solvencia';
COMMENT ON COLUMN shd100_solicitud.tilde_planilla IS 'Planilla de solicitud';
COMMENT ON COLUMN shd100_solicitud.tilde_permiso IS 'Permiso otorgado por organismo nacionales';
COMMENT ON COLUMN shd100_solicitud.numero_patente IS 'Número de patente';

-- Table: shd100_solicitud_actividades

-- DROP TABLE shd100_solicitud_actividades;

CREATE TABLE shd100_solicitud_actividades
(
  cod_presi integer NOT NULL, -- Código de la presidencia
  cod_entidad integer NOT NULL, -- Código de la entidad
  cod_tipo_inst integer NOT NULL, -- Código tipo de Institución
  cod_inst integer NOT NULL, -- Código de la Institución
  cod_dep integer NOT NULL, -- Código de la dependencia
  numero_solicitud character varying(20) NOT NULL, -- Número de solicitud
  cod_actividad character varying(20) NOT NULL, -- Código de actividad
  CONSTRAINT shd100_solicitud_actividades_pkey PRIMARY KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, numero_solicitud, cod_actividad)
)
WITH (OIDS=FALSE);
ALTER TABLE shd100_solicitud_actividades OWNER TO sisap;
COMMENT ON TABLE shd100_solicitud_actividades IS 'Registra las actiividades economicas a la cual se dedica';
COMMENT ON COLUMN shd100_solicitud_actividades.cod_presi IS 'Código de la presidencia';
COMMENT ON COLUMN shd100_solicitud_actividades.cod_entidad IS 'Código de la entidad';
COMMENT ON COLUMN shd100_solicitud_actividades.cod_tipo_inst IS 'Código tipo de Institución';
COMMENT ON COLUMN shd100_solicitud_actividades.cod_inst IS 'Código de la Institución';
COMMENT ON COLUMN shd100_solicitud_actividades.cod_dep IS 'Código de la dependencia';
COMMENT ON COLUMN shd100_solicitud_actividades.numero_solicitud IS 'Número de solicitud';
COMMENT ON COLUMN shd100_solicitud_actividades.cod_actividad IS 'Código de actividad';

-- Table: shd100_patente

-- DROP TABLE shd100_patente;

CREATE TABLE shd100_patente
(
  cod_presi integer NOT NULL, -- Código de la presidencia
  cod_entidad integer NOT NULL, -- Código de la entidad
  cod_tipo_inst integer NOT NULL, -- Código tipo de Institución
  cod_inst integer NOT NULL, -- Código de la Institución
  cod_dep integer NOT NULL, -- Código de la dependencia
  numero_solicitud character varying(20) NOT NULL, -- número de solicitud de patente de industria y comercio
  numero_patente character varying(20) NOT NULL, -- Número de patente de industria y comercio
  frecuencia_pago integer NOT NULL, -- Frecuencia de pago...
  monto_mensual numeric(26,2) NOT NULL, -- Monto mensual
  pago_todo integer NOT NULL, -- Pago todo el año ?...
  suspendido integer NOT NULL, -- Contribuyente suspendido ?...
  rif_ci_cobrador character varying(20) NOT NULL, -- Rif o Cédula de identidad del cobrador
  ultimo_ano_facturado integer, -- Ultimo año cancelado
  ultimo_mes_facturado integer, -- Ultimo mes cancelado
  fecha_ultima_decla date, -- Fecha ultima declaración
  ingresos_declarados numeric(26,2), -- Monto ingresos declarados
  ultimo_ejercicio_decla integer, -- Ultimo ejercicio declarado
  periodo_desde date, -- Periodo declarado desde
  periodo_hasta date, -- Periodo declarado hasta
  fecha_patente date NOT NULL, -- Fecha de otorgamiento de la patente
  CONSTRAINT shd100_patente_pkey PRIMARY KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, numero_solicitud, numero_patente)
)
WITH (OIDS=FALSE);
ALTER TABLE shd100_patente OWNER TO sisap;
COMMENT ON TABLE shd100_patente IS 'Registro de otorgamiento de licencia de industria y comercio';
COMMENT ON COLUMN shd100_patente.cod_presi IS 'Código de la presidencia';
COMMENT ON COLUMN shd100_patente.cod_entidad IS 'Código de la entidad';
COMMENT ON COLUMN shd100_patente.cod_tipo_inst IS 'Código tipo de Institución';
COMMENT ON COLUMN shd100_patente.cod_inst IS 'Código de la Institución';
COMMENT ON COLUMN shd100_patente.cod_dep IS 'Código de la dependencia';
COMMENT ON COLUMN shd100_patente.numero_solicitud IS 'número de solicitud de patente de industria y comercio';
COMMENT ON COLUMN shd100_patente.numero_patente IS 'Número de patente de industria y comercio';
COMMENT ON COLUMN shd100_patente.frecuencia_pago IS 'Frecuencia de pago
1.- Mensual
2.- Bimestral
3.- Trimestral
4.- Semestral
5.- Anual';
COMMENT ON COLUMN shd100_patente.monto_mensual IS 'Monto mensual';
COMMENT ON COLUMN shd100_patente.pago_todo IS 'Pago todo el año ?
1.- Si
2.- No
Por defecto "2"';
COMMENT ON COLUMN shd100_patente.suspendido IS 'Contribuyente suspendido ?
1.- Si
2.- No
Por defecto "2"';
COMMENT ON COLUMN shd100_patente.rif_ci_cobrador IS 'Rif o Cédula de identidad del cobrador';
COMMENT ON COLUMN shd100_patente.ultimo_ano_facturado IS 'Ultimo año cancelado';
COMMENT ON COLUMN shd100_patente.ultimo_mes_facturado IS 'Ultimo mes cancelado';
COMMENT ON COLUMN shd100_patente.fecha_ultima_decla IS 'Fecha ultima declaración';
COMMENT ON COLUMN shd100_patente.ingresos_declarados IS 'Monto ingresos declarados';
COMMENT ON COLUMN shd100_patente.ultimo_ejercicio_decla IS 'Ultimo ejercicio declarado';
COMMENT ON COLUMN shd100_patente.periodo_desde IS 'Periodo declarado desde';
COMMENT ON COLUMN shd100_patente.periodo_hasta IS 'Periodo declarado hasta';
COMMENT ON COLUMN shd100_patente.fecha_patente IS 'Fecha de otorgamiento de la patente';

-- Table: shd100_patente_actividades

-- DROP TABLE shd100_patente_actividades;

CREATE TABLE shd100_patente_actividades
(
  cod_presi integer NOT NULL, -- Código de la presidencia
  cod_entidad integer NOT NULL, -- Código de la entidad
  cod_tipo_inst integer NOT NULL, -- Código tipo de Institución
  cod_inst integer NOT NULL, -- Código de la Institución
  cod_dep integer NOT NULL, -- Código de la dependencia
  numero_solicitud character varying(20) NOT NULL, -- Número de solicitud de patente
  numero_patente character varying(20) NOT NULL, -- Número de la patente de industria y comercioi
  cod_actividad character varying(20) NOT NULL, -- Código de la actividad economica
  numero_aforos integer NOT NULL, -- Número de aforos
  monto_aforo_anual numeric(26,2) NOT NULL, -- Monto aforo anual
  total_aforo_anual numeric(26,2) NOT NULL, -- Total aforo anual
  CONSTRAINT shd100_patente_actividades_pkey PRIMARY KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, numero_solicitud, numero_patente, cod_actividad),
  CONSTRAINT shd100_patente_actividades_1 FOREIGN KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, numero_solicitud, numero_patente)
      REFERENCES shd100_patente (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, numero_solicitud, numero_patente) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE CASCADE
)
WITH (OIDS=FALSE);
ALTER TABLE shd100_patente_actividades OWNER TO sisap;
COMMENT ON TABLE shd100_patente_actividades IS 'Registro de monto de aforos de las actividades economicas del contribuyentes';
COMMENT ON COLUMN shd100_patente_actividades.cod_presi IS 'Código de la presidencia';
COMMENT ON COLUMN shd100_patente_actividades.cod_entidad IS 'Código de la entidad';
COMMENT ON COLUMN shd100_patente_actividades.cod_tipo_inst IS 'Código tipo de Institución';
COMMENT ON COLUMN shd100_patente_actividades.cod_inst IS 'Código de la Institución';
COMMENT ON COLUMN shd100_patente_actividades.cod_dep IS 'Código de la dependencia';
COMMENT ON COLUMN shd100_patente_actividades.numero_solicitud IS 'Número de solicitud de patente';
COMMENT ON COLUMN shd100_patente_actividades.numero_patente IS 'Número de la patente de industria y comercioi';
COMMENT ON COLUMN shd100_patente_actividades.cod_actividad IS 'Código de la actividad economica';
COMMENT ON COLUMN shd100_patente_actividades.numero_aforos IS 'Número de aforos';
COMMENT ON COLUMN shd100_patente_actividades.monto_aforo_anual IS 'Monto aforo anual';
COMMENT ON COLUMN shd100_patente_actividades.total_aforo_anual IS 'Total aforo anual';

-- Table: shd100_ordenanza

-- DROP TABLE shd100_ordenanza;

CREATE TABLE shd100_ordenanza
(
  cod_presi integer NOT NULL, -- Código de la presidencia
  cod_entidad integer NOT NULL, -- Código de la entidad
  cod_tipo_inst integer NOT NULL, -- Código tipo de la Institución
  cod_inst integer NOT NULL, -- Código de la Institución
  cod_dep integer NOT NULL, -- Código de la dependencia
  porcentaje_descuento numeric(5,2), -- Porcentaje de descuento
  porcentaje_multa numeric(5,2), -- Porcentaje por multa
  porcentaje_recargo numeric(5,2), -- Porcentaje de recargos
  porcentaje_interes numeric(5,2), -- Porcentaje de interes
  CONSTRAINT shd100_ordenanza_pkey PRIMARY KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep)
)
WITH (OIDS=FALSE);
ALTER TABLE shd100_ordenanza OWNER TO sisap;
COMMENT ON TABLE shd100_ordenanza IS 'Porcentajes de descuento o sanciones según la ordenanza';
COMMENT ON COLUMN shd100_ordenanza.cod_presi IS 'Código de la presidencia';
COMMENT ON COLUMN shd100_ordenanza.cod_entidad IS 'Código de la entidad';
COMMENT ON COLUMN shd100_ordenanza.cod_tipo_inst IS 'Código tipo de la Institución';
COMMENT ON COLUMN shd100_ordenanza.cod_inst IS 'Código de la Institución';
COMMENT ON COLUMN shd100_ordenanza.cod_dep IS 'Código de la dependencia';
COMMENT ON COLUMN shd100_ordenanza.porcentaje_descuento IS 'Porcentaje de descuento';
COMMENT ON COLUMN shd100_ordenanza.porcentaje_multa IS 'Porcentaje por multa';
COMMENT ON COLUMN shd100_ordenanza.porcentaje_recargo IS 'Porcentaje de recargos';
COMMENT ON COLUMN shd100_ordenanza.porcentaje_interes IS 'Porcentaje de interes';

-- Table: shd100_declaracion_ingresos

-- DROP TABLE shd100_declaracion_ingresos;

CREATE TABLE shd100_declaracion_ingresos
(
  cod_presi integer NOT NULL, -- Código de la presidencia
  cod_entidad integer NOT NULL, -- Código de la entidad
  cod_tipo_inst integer NOT NULL, -- Código tipo de Institución
  cod_inst integer NOT NULL, -- Código de la Institución
  cod_dep integer NOT NULL, -- Código de la dependencia
  numero_solicitud character varying(20) NOT NULL, -- Número de la solicitud de patente de industria y comercio
  numero_patente character varying(20) NOT NULL, -- Número de patente otorgado al contribuyente
  numero_declaracion character varying(20) NOT NULL, -- Número de declaración
  periodo_desde date NOT NULL, -- Periodo de declaración desde
  periodo_hasta date NOT NULL, -- Periodo de declaración hasta
  capital numeric(26,2), -- Capital
  numero_empleados integer, -- Número de empleados
  numero_obreros integer, -- Número de obreros
  fecha_declaracion date NOT NULL, -- Fecha de declaración de ingresos
  CONSTRAINT shd100_declaracion_ingresos_pkey PRIMARY KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, numero_solicitud, numero_patente, numero_declaracion),
  CONSTRAINT shd100_declaracion_ingresos_1 FOREIGN KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, numero_solicitud, numero_patente)
      REFERENCES shd100_patente (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, numero_solicitud, numero_patente) MATCH SIMPLE
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
COMMENT ON COLUMN shd100_declaracion_ingresos.numero_solicitud IS 'Número de la solicitud de patente de industria y comercio';
COMMENT ON COLUMN shd100_declaracion_ingresos.numero_patente IS 'Número de patente otorgado al contribuyente';
COMMENT ON COLUMN shd100_declaracion_ingresos.numero_declaracion IS 'Número de declaración ';
COMMENT ON COLUMN shd100_declaracion_ingresos.periodo_desde IS 'Periodo de declaración desde';
COMMENT ON COLUMN shd100_declaracion_ingresos.periodo_hasta IS 'Periodo de declaración hasta';
COMMENT ON COLUMN shd100_declaracion_ingresos.capital IS 'Capital';
COMMENT ON COLUMN shd100_declaracion_ingresos.numero_empleados IS 'Número de empleados';
COMMENT ON COLUMN shd100_declaracion_ingresos.numero_obreros IS 'Número de obreros';
COMMENT ON COLUMN shd100_declaracion_ingresos.fecha_declaracion IS 'Fecha de declaración de ingresos';

-- Table: shd100_declaracion_actividades

-- DROP TABLE shd100_declaracion_actividades;

CREATE TABLE shd100_declaracion_actividades
(
  cod_presi integer NOT NULL, -- Código de la presidencia
  cod_entidad integer NOT NULL, -- Código de la entidad
  cod_tipo_inst integer NOT NULL, -- Código tipo de Institución
  cod_inst integer NOT NULL, -- Código de la Institución
  cod_dep integer NOT NULL, -- Código de la depedencia
  numero_solicitud character varying(20) NOT NULL, -- Número de la solicitud de patente de industria y comercio
  numero_patente character varying(20) NOT NULL, -- Número de patente otorgada al contribuyente
  numero_declaracion character varying(20) NOT NULL, -- Número de declaración
  cod_actividad character varying(20) NOT NULL, -- Código de actividad economica declarada por el contribuyente
  monto_ingresos numeric(26,2) NOT NULL, -- Monto de ingreso declarado por el contribuyente
  monto_impuesto numeric(26,2) NOT NULL, -- Monto del impuesto calculado según los ingresos declarados por el contribuyente
  CONSTRAINT shd100_declaracion_actividades_pkey PRIMARY KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, numero_solicitud, numero_patente, numero_declaracion, cod_actividad),
  CONSTRAINT shd100_declaracion_actividades_1 FOREIGN KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, numero_solicitud, numero_patente, numero_declaracion)
      REFERENCES shd100_declaracion_ingresos (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, numero_solicitud, numero_patente, numero_declaracion) MATCH SIMPLE
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
COMMENT ON COLUMN shd100_declaracion_actividades.numero_solicitud IS 'Número de la solicitud de patente de industria y comercio';
COMMENT ON COLUMN shd100_declaracion_actividades.numero_patente IS 'Número de patente otorgada al contribuyente';
COMMENT ON COLUMN shd100_declaracion_actividades.numero_declaracion IS 'Número de declaración';
COMMENT ON COLUMN shd100_declaracion_actividades.cod_actividad IS 'Código de actividad economica declarada por el contribuyente';
COMMENT ON COLUMN shd100_declaracion_actividades.monto_ingresos IS 'Monto de ingreso declarado por el contribuyente';
COMMENT ON COLUMN shd100_declaracion_actividades.monto_impuesto IS 'Monto del impuesto calculado según los ingresos declarados por el contribuyente';

-- Table: shd200_vehiculos_clases

-- DROP TABLE shd200_vehiculos_clases;

CREATE TABLE shd200_vehiculos_clases
(
  codigo_clase serial NOT NULL, -- Código de la clase
  denominacion character varying(100) NOT NULL, -- Denominación de la clase
  CONSTRAINT shd200_vehiculos_clases_pkey PRIMARY KEY (codigo_clase)
)
WITH (OIDS=FALSE);
ALTER TABLE shd200_vehiculos_clases OWNER TO sisap;
COMMENT ON TABLE shd200_vehiculos_clases IS 'Registro de la clase de Vehículos';
COMMENT ON COLUMN shd200_vehiculos_clases.codigo_clase IS 'Código de la clase';
COMMENT ON COLUMN shd200_vehiculos_clases.denominacion IS 'Denominación de la clase';

-- Table: shd200_vehiculos_clasificacion

-- DROP TABLE shd200_vehiculos_clasificacion;

CREATE TABLE shd200_vehiculos_clasificacion
(
  cod_presi integer NOT NULL, -- Código de la presidencia
  cod_entidad integer NOT NULL, -- Código de la entidad
  cod_tipo_inst integer NOT NULL, -- Código tipo de la Institución
  cod_inst integer NOT NULL, -- Código de la Institución
  cod_dep integer NOT NULL, -- Código de la dependencia
  cod_clasificacion character varying(10) NOT NULL, -- Código de la clasificación automotríz
  porcentaje numeric(5,2) NOT NULL, -- Porcentaje a aplicar
  monto_anual numeric(26,2) NOT NULL, -- Monto minimo anual
  denominacion character varying(200),
  CONSTRAINT shd200_vehiculos_clasificacion_pkey PRIMARY KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_clasificacion)
)
WITH (OIDS=FALSE);
ALTER TABLE shd200_vehiculos_clasificacion OWNER TO sisap;
COMMENT ON TABLE shd200_vehiculos_clasificacion IS 'Registra la clasificación automotriz de acuerdo a la ordenanza';
COMMENT ON COLUMN shd200_vehiculos_clasificacion.cod_presi IS 'Código de la presidencia';
COMMENT ON COLUMN shd200_vehiculos_clasificacion.cod_entidad IS 'Código de la entidad';
COMMENT ON COLUMN shd200_vehiculos_clasificacion.cod_tipo_inst IS 'Código tipo de la Institución';
COMMENT ON COLUMN shd200_vehiculos_clasificacion.cod_inst IS 'Código de la Institución';
COMMENT ON COLUMN shd200_vehiculos_clasificacion.cod_dep IS 'Código de la dependencia';
COMMENT ON COLUMN shd200_vehiculos_clasificacion.cod_clasificacion IS 'Código de la clasificación automotríz';
COMMENT ON COLUMN shd200_vehiculos_clasificacion.porcentaje IS 'Porcentaje a aplicar';
COMMENT ON COLUMN shd200_vehiculos_clasificacion.monto_anual IS 'Monto minimo anual';

-- Table: shd200_vehiculos_colores

-- DROP TABLE shd200_vehiculos_colores;

CREATE TABLE shd200_vehiculos_colores
(
  codigo_color serial NOT NULL, -- Código del color
  denominacion character varying(100) NOT NULL, -- Denominación del color
  CONSTRAINT shd200_vehiculos_colores_pkey PRIMARY KEY (codigo_color)
)
WITH (OIDS=FALSE);
ALTER TABLE shd200_vehiculos_colores OWNER TO sisap;
COMMENT ON TABLE shd200_vehiculos_colores IS 'Registro de color de los Vehículos';
COMMENT ON COLUMN shd200_vehiculos_colores.codigo_color IS 'Código del color';
COMMENT ON COLUMN shd200_vehiculos_colores.denominacion IS 'Denominación del color';

-- Table: shd200_vehiculos_marcas

-- DROP TABLE shd200_vehiculos_marcas;

CREATE TABLE shd200_vehiculos_marcas
(
  codigo_marca serial NOT NULL, -- Código de marcas
  denominacion character varying(100) NOT NULL, -- Denominación de la marca
  CONSTRAINT shd200_vehiculos_marcas_pkey PRIMARY KEY (codigo_marca)
)
WITH (OIDS=FALSE);
ALTER TABLE shd200_vehiculos_marcas OWNER TO sisap;
COMMENT ON TABLE shd200_vehiculos_marcas IS 'Registro de marcas de Vehículos';
COMMENT ON COLUMN shd200_vehiculos_marcas.codigo_marca IS 'Código de marcas';
COMMENT ON COLUMN shd200_vehiculos_marcas.denominacion IS 'Denominación de la marca';

-- Table: shd200_vehiculos_modelos

-- DROP TABLE shd200_vehiculos_modelos;

CREATE TABLE shd200_vehiculos_modelos
(
  codigo_modelo serial NOT NULL, -- Código del modelo
  denominacion character varying(100) NOT NULL, -- Denominación del modelo
  CONSTRAINT shd200_vehiculos_modelos_pkey PRIMARY KEY (codigo_modelo)
)
WITH (OIDS=FALSE);
ALTER TABLE shd200_vehiculos_modelos OWNER TO sisap;
COMMENT ON TABLE shd200_vehiculos_modelos IS 'Registro de modelos de Vehículos';
COMMENT ON COLUMN shd200_vehiculos_modelos.codigo_modelo IS 'Código del modelo';
COMMENT ON COLUMN shd200_vehiculos_modelos.denominacion IS 'Denominación del modelo';

-- Table: shd200_vehiculos_tipos

-- DROP TABLE shd200_vehiculos_tipos;

CREATE TABLE shd200_vehiculos_tipos
(
  codigo_tipo serial NOT NULL, -- Código del tipo de Vehículo
  denominacion character varying(100) NOT NULL, -- Denominación del tipo de Vehículo
  CONSTRAINT shd200_vehiculos_tipos_pkey PRIMARY KEY (codigo_tipo)
)
WITH (OIDS=FALSE);
ALTER TABLE shd200_vehiculos_tipos OWNER TO sisap;
COMMENT ON TABLE shd200_vehiculos_tipos IS 'Registro del de Vehículos';
COMMENT ON COLUMN shd200_vehiculos_tipos.codigo_tipo IS 'Código del tipo de Vehículo';
COMMENT ON COLUMN shd200_vehiculos_tipos.denominacion IS 'Denominación del tipo de Vehículo';

-- Table: shd200_vehiculos_usos

-- DROP TABLE shd200_vehiculos_usos;

CREATE TABLE shd200_vehiculos_usos
(
  codigo_uso serial NOT NULL, -- Código del uso del Vehículo
  denominacion character varying(100) NOT NULL, -- Denominación del uso del Vehículo
  CONSTRAINT shd200_vehiculos_usos_pkey PRIMARY KEY (codigo_uso)
)
WITH (OIDS=FALSE);
ALTER TABLE shd200_vehiculos_usos OWNER TO sisap;
COMMENT ON TABLE shd200_vehiculos_usos IS 'Registro del uso Vehículos';
COMMENT ON COLUMN shd200_vehiculos_usos.codigo_uso IS 'Código del uso del Vehículo';
COMMENT ON COLUMN shd200_vehiculos_usos.denominacion IS 'Denominación del uso del Vehículo';

-- Table: shd200_vehiculos

-- DROP TABLE shd200_vehiculos;

CREATE TABLE shd200_vehiculos
(
  cod_presi integer NOT NULL, -- Código de la presidencia
  cod_entidad integer NOT NULL, -- Código de la entidad
  cod_tipo_inst integer NOT NULL, -- Código tipo de Institución
  cod_inst integer NOT NULL, -- Código de la Institución
  cod_dep integer NOT NULL, -- Código de la dependencia
  rif_cedula character varying(20) NOT NULL, -- Rif o Cédula de identidad del contribuyente
  placa_vehiculo character varying(10) NOT NULL, -- Placa del Vehículo
  fecha_registro date NOT NULL, -- Fecha de registro
  cod_marca integer NOT NULL, -- Código de la marca del vehículo
  cod_modelo integer NOT NULL, -- Código del modelo del vehículo
  cod_color integer NOT NULL, -- Código del color del vehículo
  cod_clase integer NOT NULL, -- Código de la clase de vehículo
  cod_tipo integer NOT NULL, -- Código tipo del vehículo
  cod_uso integer NOT NULL, -- Código de uso de vehículo
  serial_carroceria character varying(25), -- Serial de carroceria
  serial_motor character varying(25), -- Serial del motor
  ano_adquisicion integer NOT NULL, -- Año de adquisición
  valor_vehiculo numeric(26,2) NOT NULL, -- Valor del vehículo
  fecha_adquisicion date NOT NULL, -- Fecha de adquisición del vehículo
  cod_clasificacion character varying(10) NOT NULL, -- Código de la clasificación automotríz
  frecuencia_pago integer NOT NULL, -- Frecuencia de pago...
  monto_mensual numeric(26,2) NOT NULL, -- Monto pago mensual
  pago_todo integer NOT NULL, -- Pago todo el año ?...
  suspendido integer NOT NULL, -- Contribuyente suspendido ?...
  rif_ci_cobrador character varying(20) NOT NULL, -- Rif o Cédula de identidad del cobrador
  ultimo_ano_facturado integer, -- Ultimo año cancelado
  ultimo_mes_facturado integer -- Ultimo mes cancelado
)
WITH (OIDS=FALSE);
ALTER TABLE shd200_vehiculos OWNER TO sisap;
COMMENT ON TABLE shd200_vehiculos IS 'Registro vehiculos de los contribuyentes';
COMMENT ON COLUMN shd200_vehiculos.cod_presi IS 'Código de la presidencia';
COMMENT ON COLUMN shd200_vehiculos.cod_entidad IS 'Código de la entidad';
COMMENT ON COLUMN shd200_vehiculos.cod_tipo_inst IS 'Código tipo de Institución';
COMMENT ON COLUMN shd200_vehiculos.cod_inst IS 'Código de la Institución';
COMMENT ON COLUMN shd200_vehiculos.cod_dep IS 'Código de la dependencia';
COMMENT ON COLUMN shd200_vehiculos.rif_cedula IS 'Rif o Cédula de identidad del contribuyente';
COMMENT ON COLUMN shd200_vehiculos.placa_vehiculo IS 'Placa del Vehículo';
COMMENT ON COLUMN shd200_vehiculos.fecha_registro IS 'Fecha de registro';
COMMENT ON COLUMN shd200_vehiculos.cod_marca IS 'Código de la marca del vehículo';
COMMENT ON COLUMN shd200_vehiculos.cod_modelo IS 'Código del modelo del vehículo';
COMMENT ON COLUMN shd200_vehiculos.cod_color IS 'Código del color del vehículo';
COMMENT ON COLUMN shd200_vehiculos.cod_clase IS 'Código de la clase de vehículo';
COMMENT ON COLUMN shd200_vehiculos.cod_tipo IS 'Código tipo del vehículo';
COMMENT ON COLUMN shd200_vehiculos.cod_uso IS 'Código de uso de vehículo';
COMMENT ON COLUMN shd200_vehiculos.serial_carroceria IS 'Serial de carroceria';
COMMENT ON COLUMN shd200_vehiculos.serial_motor IS 'Serial del motor';
COMMENT ON COLUMN shd200_vehiculos.ano_adquisicion IS 'Año de adquisición';
COMMENT ON COLUMN shd200_vehiculos.valor_vehiculo IS 'Valor del vehículo';
COMMENT ON COLUMN shd200_vehiculos.fecha_adquisicion IS 'Fecha de adquisición del vehículo';
COMMENT ON COLUMN shd200_vehiculos.cod_clasificacion IS 'Código de la clasificación automotríz';
COMMENT ON COLUMN shd200_vehiculos.frecuencia_pago IS 'Frecuencia de pago
1.- Mensual
2.- Bimestral
3.- Trimestral
4.- Semestral
5.- Anual';
COMMENT ON COLUMN shd200_vehiculos.monto_mensual IS 'Monto pago mensual';
COMMENT ON COLUMN shd200_vehiculos.pago_todo IS 'Pago todo el año ?
1.- Si
2.- No
Por defecto "2"';
COMMENT ON COLUMN shd200_vehiculos.suspendido IS 'Contribuyente suspendido ?
1.- Si
2.- No
Por defecto "2"';
COMMENT ON COLUMN shd200_vehiculos.rif_ci_cobrador IS 'Rif o Cédula de identidad del cobrador';
COMMENT ON COLUMN shd200_vehiculos.ultimo_ano_facturado IS 'Ultimo año cancelado';
COMMENT ON COLUMN shd200_vehiculos.ultimo_mes_facturado IS 'Ultimo mes cancelado';

-- Table: shd300_tipo_propaganda

-- DROP TABLE shd300_tipo_propaganda;

CREATE TABLE shd300_tipo_propaganda
(
  cod_presi integer NOT NULL, -- Código de la presidencia
  cod_entidad integer NOT NULL, -- Código de la entidad
  cod_tipo_inst integer NOT NULL, -- Código tipo de Institución
  cod_inst integer NOT NULL, -- Código de la Institución
  cod_dep integer NOT NULL, -- Código de la dependencia
  cod_tipo integer NOT NULL, -- Código tipo de públicidad
  denominacion character varying(100) NOT NULL, -- Denominación tipo de publicidad
  articulo character varying(10) NOT NULL, -- Articulo de la ordenanza
  monto numeric(26,2) NOT NULL, -- Monto en bolivares por m2 o unidad
  tipo_unidad integer NOT NULL, -- Tipo de unidad...
  CONSTRAINT shd300_tipo_publicidad_pkey PRIMARY KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo)
)
WITH (OIDS=FALSE);
ALTER TABLE shd300_tipo_propaganda OWNER TO sisap;
COMMENT ON TABLE shd300_tipo_propaganda IS 'Registrael monto por m2 o unidad según los tipos de propaganda';
COMMENT ON COLUMN shd300_tipo_propaganda.cod_presi IS 'Código de la presidencia';
COMMENT ON COLUMN shd300_tipo_propaganda.cod_entidad IS 'Código de la entidad';
COMMENT ON COLUMN shd300_tipo_propaganda.cod_tipo_inst IS 'Código tipo de Institución';
COMMENT ON COLUMN shd300_tipo_propaganda.cod_inst IS 'Código de la Institución';
COMMENT ON COLUMN shd300_tipo_propaganda.cod_dep IS 'Código de la dependencia';
COMMENT ON COLUMN shd300_tipo_propaganda.cod_tipo IS 'Código tipo de públicidad';
COMMENT ON COLUMN shd300_tipo_propaganda.denominacion IS 'Denominación tipo de publicidad';
COMMENT ON COLUMN shd300_tipo_propaganda.articulo IS 'Articulo de la ordenanza';
COMMENT ON COLUMN shd300_tipo_propaganda.monto IS 'Monto en bolivares por m2 o unidad';
COMMENT ON COLUMN shd300_tipo_propaganda.tipo_unidad IS 'Tipo de unidad
1.- Unidad
2.- Metros';

-- Table: shd300_recargos

-- DROP TABLE shd300_recargos;

CREATE TABLE shd300_recargos
(
  cod_presi integer NOT NULL, -- Código de la presidencia
  cod_entidad integer NOT NULL, -- Código de la entidad
  cod_tipo_inst integer NOT NULL, -- Código tipo de Institución
  cod_inst integer NOT NULL, -- Código de Institución
  cod_dep integer NOT NULL, -- Código de la dependencia
  cod_recargo integer NOT NULL, -- Código de recargo
  denominacion character varying(100) NOT NULL, -- Denominación del recargo
  porcentaje numeric(5,2) NOT NULL, -- Porcentaje del recargo
  CONSTRAINT shd300_recargos_pkey PRIMARY KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_recargo)
)
WITH (OIDS=FALSE);
ALTER TABLE shd300_recargos OWNER TO sisap;
COMMENT ON TABLE shd300_recargos IS 'Registra los recargos adicionales según la ordenanza y el tipo de propaganda';
COMMENT ON COLUMN shd300_recargos.cod_presi IS 'Código de la presidencia';
COMMENT ON COLUMN shd300_recargos.cod_entidad IS 'Código de la entidad';
COMMENT ON COLUMN shd300_recargos.cod_tipo_inst IS 'Código tipo de Institución';
COMMENT ON COLUMN shd300_recargos.cod_inst IS 'Código de Institución';
COMMENT ON COLUMN shd300_recargos.cod_dep IS 'Código de la dependencia';
COMMENT ON COLUMN shd300_recargos.cod_recargo IS 'Código de recargo';
COMMENT ON COLUMN shd300_recargos.denominacion IS 'Denominación del recargo';
COMMENT ON COLUMN shd300_recargos.porcentaje IS 'Porcentaje del recargo';

-- Table: shd300_propaganda

-- DROP TABLE shd300_propaganda;

CREATE TABLE shd300_propaganda
(
  cod_presi integer NOT NULL, -- Código de la presidencia
  cod_entidad integer NOT NULL, -- Código de la entidad
  cod_tipo_inst integer NOT NULL, -- Código tipo de Institución
  cod_inst integer NOT NULL, -- Código de la Institución
  cod_dep integer NOT NULL, -- Código de la dependencia
  rif_cedula character varying(20) NOT NULL, -- Rif o Cédula de Identidad
  frecuencia_pago integer NOT NULL, -- Frecuencia de pago...
  monto_mensual_general numeric(26,2) NOT NULL, -- Monto mensual general a cancelar
  pago_todo integer NOT NULL, -- Pago todo ?...
  suspendido integer NOT NULL, -- Contribuyente suspendido ?...
  rif_ci_cobrador character varying(20) NOT NULL, -- Rif o Cédula de identidad del cobrador
  ultimo_ano_facturado integer, -- Ultimo año cancelado
  ultimo_mes_facturado integer, -- Ultimo mes cancelado
  CONSTRAINT shd300_propaganda_pkey PRIMARY KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, rif_cedula)
)
WITH (OIDS=FALSE);
ALTER TABLE shd300_propaganda OWNER TO sisap;
COMMENT ON TABLE shd300_propaganda IS 'Registra los contribuyentes de propaganda comercial, totalizando todos los tipos de propaganda';
COMMENT ON COLUMN shd300_propaganda.cod_presi IS 'Código de la presidencia';
COMMENT ON COLUMN shd300_propaganda.cod_entidad IS 'Código de la entidad';
COMMENT ON COLUMN shd300_propaganda.cod_tipo_inst IS 'Código tipo de Institución';
COMMENT ON COLUMN shd300_propaganda.cod_inst IS 'Código de la Institución';
COMMENT ON COLUMN shd300_propaganda.cod_dep IS 'Código de la dependencia';
COMMENT ON COLUMN shd300_propaganda.rif_cedula IS 'Rif o Cédula de Identidad';
COMMENT ON COLUMN shd300_propaganda.frecuencia_pago IS 'Frecuencia de pago
1.- Mensual
2.- Bimestral
3.- Trimestral
4.- Semestral
5.- Anual';
COMMENT ON COLUMN shd300_propaganda.monto_mensual_general IS 'Monto mensual general a cancelar';
COMMENT ON COLUMN shd300_propaganda.pago_todo IS 'Pago todo ?
1.- Si
2.- No';
COMMENT ON COLUMN shd300_propaganda.suspendido IS 'Contribuyente suspendido ?
1.- Si
2.- No';
COMMENT ON COLUMN shd300_propaganda.rif_ci_cobrador IS 'Rif o Cédula de identidad del cobrador';
COMMENT ON COLUMN shd300_propaganda.ultimo_ano_facturado IS 'Ultimo año cancelado';
COMMENT ON COLUMN shd300_propaganda.ultimo_mes_facturado IS 'Ultimo mes cancelado';

-- Table: shd300_detalles_propaganda

-- DROP TABLE shd300_detalles_propaganda;

CREATE TABLE shd300_detalles_propaganda
(
  cod_presi integer NOT NULL, -- Código de la presidencia
  cod_entidad integer NOT NULL, -- Código de la entidad
  cod_tipo_inst integer NOT NULL, -- Código tipo de Institución
  cod_inst integer NOT NULL, -- Código de la Institución
  cod_dep integer NOT NULL, -- Código de la dependencia
  rif_cedula character varying(20) NOT NULL, -- Rif o Cédula de identidad del contribuyente
  cod_tipo integer NOT NULL, -- Código tipo de propaganda comercial
  numero integer NOT NULL, -- Número consecutivo para registrar varias propagandas de un solo tipo
  largo numeric(10,3), -- Largo en metros lineales
  alto numeric(10,3), -- Alto en metros lineales
  area numeric(10,3), -- Area en metros cuadrados
  espesor numeric(10,3), -- Espesor
  cantidad numeric(10,3) NOT NULL, -- Cantidad en metros m2 o unidades
  monto numeric(26,2) NOT NULL, -- Monto resultante de calcular la Cantidad de metros o unidades por el valor del metro cuadrado o valor en unidad
  monto_adicional numeric(26,2), -- Monto adicional resultado de el calculo en porcentaje del monto bruto
  monto_mensual numeric(26,2) NOT NULL, -- Monto mensual a cobrar por este tipo de propaganda
  ubicacion text, -- Ubicación de la valla o cualquier otro tipo de propaganda
  fecha_registro date NOT NULL, -- Fecha de registro
  CONSTRAINT shd300_detalles_propaganda_pkey PRIMARY KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, rif_cedula, cod_tipo, numero)
)
WITH (OIDS=FALSE);
ALTER TABLE shd300_detalles_propaganda OWNER TO sisap;
COMMENT ON TABLE shd300_detalles_propaganda IS 'Registro de los diferentes tipos de propaganda comercial que cancela el contribuyente';
COMMENT ON COLUMN shd300_detalles_propaganda.cod_presi IS 'Código de la presidencia';
COMMENT ON COLUMN shd300_detalles_propaganda.cod_entidad IS 'Código de la entidad';
COMMENT ON COLUMN shd300_detalles_propaganda.cod_tipo_inst IS 'Código tipo de Institución';
COMMENT ON COLUMN shd300_detalles_propaganda.cod_inst IS 'Código de la Institución';
COMMENT ON COLUMN shd300_detalles_propaganda.cod_dep IS 'Código de la dependencia';
COMMENT ON COLUMN shd300_detalles_propaganda.rif_cedula IS 'Rif o Cédula de identidad del contribuyente';
COMMENT ON COLUMN shd300_detalles_propaganda.cod_tipo IS 'Código tipo de propaganda comercial';
COMMENT ON COLUMN shd300_detalles_propaganda.numero IS 'Número consecutivo para registrar varias propagandas de un solo tipo';
COMMENT ON COLUMN shd300_detalles_propaganda.largo IS 'Largo en metros lineales';
COMMENT ON COLUMN shd300_detalles_propaganda.alto IS 'Alto en metros lineales';
COMMENT ON COLUMN shd300_detalles_propaganda.area IS 'Area en metros cuadrados';
COMMENT ON COLUMN shd300_detalles_propaganda.espesor IS 'Espesor';
COMMENT ON COLUMN shd300_detalles_propaganda.cantidad IS 'Cantidad en metros m2 o unidades';
COMMENT ON COLUMN shd300_detalles_propaganda.monto IS 'Monto resultante de calcular la Cantidad de metros o unidades por el valor del metro cuadrado o valor en unidad';
COMMENT ON COLUMN shd300_detalles_propaganda.monto_adicional IS 'Monto adicional resultado de el calculo en porcentaje del monto bruto';
COMMENT ON COLUMN shd300_detalles_propaganda.monto_mensual IS 'Monto mensual a cobrar por este tipo de propaganda';
COMMENT ON COLUMN shd300_detalles_propaganda.ubicacion IS 'Ubicación de la valla o cualquier otro tipo de propaganda';
COMMENT ON COLUMN shd300_detalles_propaganda.fecha_registro IS 'Fecha de registro';

-- Table: shd300_detalles_adicional

-- DROP TABLE shd300_detalles_adicional;

CREATE TABLE shd300_detalles_adicional
(
  cod_presi integer NOT NULL, -- Código de la presidencia
  cod_entidad integer NOT NULL, -- Código de la entidad
  cod_tipo_inst integer NOT NULL, -- Código tipo de Institución
  cod_inst integer NOT NULL, -- Código de la Institución
  cod_dep integer NOT NULL, -- Código de la dependencia
  rif_cedula character varying(20) NOT NULL, -- Rif o Cédula de identidad del contribuyente
  cod_tipo integer NOT NULL, -- Código tipo de propaganda
  numero integer NOT NULL, -- Número consecutivo clave de la tabla shd300-detalles-propaganda
  cod_recargo integer NOT NULL, -- Código del recargo
  monto numeric(26,2) NOT NULL, -- Monto
  CONSTRAINT shd300_detalles_adicional_pkey PRIMARY KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, rif_cedula, cod_tipo, numero, cod_recargo)
)
WITH (OIDS=FALSE);
ALTER TABLE shd300_detalles_adicional OWNER TO sisap;
COMMENT ON TABLE shd300_detalles_adicional IS 'Registra los montos adicionales por tipo de propaganda';
COMMENT ON COLUMN shd300_detalles_adicional.cod_presi IS 'Código de la presidencia';
COMMENT ON COLUMN shd300_detalles_adicional.cod_entidad IS 'Código de la entidad';
COMMENT ON COLUMN shd300_detalles_adicional.cod_tipo_inst IS 'Código tipo de Institución';
COMMENT ON COLUMN shd300_detalles_adicional.cod_inst IS 'Código de la Institución';
COMMENT ON COLUMN shd300_detalles_adicional.cod_dep IS 'Código de la dependencia';
COMMENT ON COLUMN shd300_detalles_adicional.rif_cedula IS 'Rif o Cédula de identidad del contribuyente';
COMMENT ON COLUMN shd300_detalles_adicional.cod_tipo IS 'Código tipo de propaganda';
COMMENT ON COLUMN shd300_detalles_adicional.numero IS 'Número consecutivo clave de la tabla shd300-detalles-propaganda';
COMMENT ON COLUMN shd300_detalles_adicional.cod_recargo IS 'Código del recargo';
COMMENT ON COLUMN shd300_detalles_adicional.monto IS 'Monto';

-- Table: shd400_propiedad

-- DROP TABLE shd400_propiedad;

CREATE TABLE shd400_propiedad
(
  cod_presi integer NOT NULL, -- Código de la presidencia
  cod_entidad integer NOT NULL, -- Código de la entidad
  cod_tipo_inst integer NOT NULL, -- Código tipo de Institución
  cod_inst integer NOT NULL, -- Código de la Institución
  cod_dep integer NOT NULL, -- Código de la dependencia
  rif_cedula character varying(20) NOT NULL, -- Rif o Cédula de identidad del contribuyente
  cod_ficha character varying(20) NOT NULL, -- Código ficha catastral
  frecuencia_pago integer NOT NULL, -- Frecuencia de pago...
  monto_mensual numeric(26,2) NOT NULL, -- Monto mensual
  pago_todo integer NOT NULL, -- Pago todo el año ?...
  suspendido integer NOT NULL, -- Contribuyente suspendido ?...
  rif_ci_cobrador character varying(20) NOT NULL, -- Rif o Cédula de identidad del cobrador
  ultimo_ano_facturado integer, -- Ultimo año cancelado
  ultimo_mes_facturado integer, -- Ultimo mes cancelado
  CONSTRAINT shd400_propiedad_pkey PRIMARY KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, rif_cedula, cod_ficha)
)
WITH (OIDS=FALSE);
ALTER TABLE shd400_propiedad OWNER TO sisap;
COMMENT ON TABLE shd400_propiedad IS 'Registra la propiedad inmobiliaria de los contribuyentes';
COMMENT ON COLUMN shd400_propiedad.cod_presi IS 'Código de la presidencia';
COMMENT ON COLUMN shd400_propiedad.cod_entidad IS 'Código de la entidad';
COMMENT ON COLUMN shd400_propiedad.cod_tipo_inst IS 'Código tipo de Institución';
COMMENT ON COLUMN shd400_propiedad.cod_inst IS 'Código de la Institución';
COMMENT ON COLUMN shd400_propiedad.cod_dep IS 'Código de la dependencia';
COMMENT ON COLUMN shd400_propiedad.rif_cedula IS 'Rif o Cédula de identidad del contribuyente';
COMMENT ON COLUMN shd400_propiedad.cod_ficha IS 'Código ficha catastral';
COMMENT ON COLUMN shd400_propiedad.frecuencia_pago IS 'Frecuencia de pago
1.- Mensual
2.- Bimestral
3.- Trimestral
4.- Semestral
5.- Anual';
COMMENT ON COLUMN shd400_propiedad.monto_mensual IS 'Monto mensual';
COMMENT ON COLUMN shd400_propiedad.pago_todo IS 'Pago todo el año ?
1.- Si
2.- No';
COMMENT ON COLUMN shd400_propiedad.suspendido IS 'Contribuyente suspendido ?
1.- Si
2.- No';
COMMENT ON COLUMN shd400_propiedad.rif_ci_cobrador IS 'Rif o Cédula de identidad del cobrador';
COMMENT ON COLUMN shd400_propiedad.ultimo_ano_facturado IS 'Ultimo año cancelado';
COMMENT ON COLUMN shd400_propiedad.ultimo_mes_facturado IS 'Ultimo mes cancelado';

-- Table: shd500_aseo_clasificacion

-- DROP TABLE shd500_aseo_clasificacion;

CREATE TABLE shd500_aseo_clasificacion
(
  cod_presi integer NOT NULL, -- Código de la presidencia
  cod_entidad integer NOT NULL, -- Código de la entidad
  cod_tipo_inst integer NOT NULL, -- Código tipo de Institución
  cod_inst integer NOT NULL, -- Código de la Institución
  cod_dep integer NOT NULL, -- Código de la dependencia
  cod_clasificacion integer NOT NULL, -- Código clasificacion del servicio
  denominacion character varying(100) NOT NULL, -- Denominación de la clasificación del servicio de aseo domiciliario
  monto_mensual numeric(26,2) NOT NULL, -- Monto mensual
  CONSTRAINT shd500_aseo_clasificacion_pkey PRIMARY KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_clasificacion)
)
WITH (OIDS=FALSE);
ALTER TABLE shd500_aseo_clasificacion OWNER TO sisap;
COMMENT ON TABLE shd500_aseo_clasificacion IS 'Registra la clasificación del servicio de aseo domiciliario';
COMMENT ON COLUMN shd500_aseo_clasificacion.cod_presi IS 'Código de la presidencia';
COMMENT ON COLUMN shd500_aseo_clasificacion.cod_entidad IS 'Código de la entidad';
COMMENT ON COLUMN shd500_aseo_clasificacion.cod_tipo_inst IS 'Código tipo de Institución';
COMMENT ON COLUMN shd500_aseo_clasificacion.cod_inst IS 'Código de la Institución';
COMMENT ON COLUMN shd500_aseo_clasificacion.cod_dep IS 'Código de la dependencia';
COMMENT ON COLUMN shd500_aseo_clasificacion.cod_clasificacion IS 'Código clasificacion del servicio';
COMMENT ON COLUMN shd500_aseo_clasificacion.denominacion IS 'Denominación de la clasificación del servicio de aseo domiciliario';
COMMENT ON COLUMN shd500_aseo_clasificacion.monto_mensual IS 'Monto mensual';

-- Table: shd500_aseo_domiciliario

-- DROP TABLE shd500_aseo_domiciliario;

CREATE TABLE shd500_aseo_domiciliario
(
  cod_presi integer NOT NULL, -- Código de la presidencia
  cod_entidad integer NOT NULL, -- Código de la entidad
  cod_tipo_inst integer NOT NULL, -- Código tipo de Institución
  cod_inst integer NOT NULL, -- Código de la Institución
  cod_dep integer NOT NULL, -- Código de la dependencia
  rif_cedula character varying(20) NOT NULL, -- Rif o Cédula de identidad
  cod_clasificacion integer NOT NULL, -- Código de la clasificación del servicio
  frecuencia_pago integer NOT NULL, -- Frecuencia de pago...
  fecha_registro date NOT NULL, -- Fecha del registro
  monto_mensual numeric(26,2) NOT NULL, -- Monto mensual
  pago_todo integer NOT NULL, -- Pago todo el año ?...
  suspendido integer NOT NULL, -- Contribuyente suspendido ?...
  rif_ci_cobrador character varying(20) NOT NULL, -- Rif o Cédula de identidad del cobrador
  ultimo_ano_facturado integer, -- Ultimo año cancelado
  ultimo_mes_facturado integer, -- Ultimo mes cancelado
  CONSTRAINT shd500_aseo_domiciliario_pkey PRIMARY KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, rif_cedula)
)
WITH (OIDS=FALSE);
ALTER TABLE shd500_aseo_domiciliario OWNER TO sisap;
COMMENT ON TABLE shd500_aseo_domiciliario IS 'Registra los contribuyentes de aseo domiciliario';
COMMENT ON COLUMN shd500_aseo_domiciliario.cod_presi IS 'Código de la presidencia';
COMMENT ON COLUMN shd500_aseo_domiciliario.cod_entidad IS 'Código de la entidad';
COMMENT ON COLUMN shd500_aseo_domiciliario.cod_tipo_inst IS 'Código tipo de Institución';
COMMENT ON COLUMN shd500_aseo_domiciliario.cod_inst IS 'Código de la Institución';
COMMENT ON COLUMN shd500_aseo_domiciliario.cod_dep IS 'Código de la dependencia';
COMMENT ON COLUMN shd500_aseo_domiciliario.rif_cedula IS 'Rif o Cédula de identidad';
COMMENT ON COLUMN shd500_aseo_domiciliario.cod_clasificacion IS 'Código de la clasificación del servicio';
COMMENT ON COLUMN shd500_aseo_domiciliario.frecuencia_pago IS 'Frecuencia de pago
1.- Mensual
2.- Bimestral
3.- Trimestral
4.- Semestral
5.- Anual';
COMMENT ON COLUMN shd500_aseo_domiciliario.fecha_registro IS 'Fecha del registro';
COMMENT ON COLUMN shd500_aseo_domiciliario.monto_mensual IS 'Monto mensual';
COMMENT ON COLUMN shd500_aseo_domiciliario.pago_todo IS 'Pago todo el año ?
1.- Si
2.- No';
COMMENT ON COLUMN shd500_aseo_domiciliario.suspendido IS 'Contribuyente suspendido ?
1.- Si
2.- No';
COMMENT ON COLUMN shd500_aseo_domiciliario.rif_ci_cobrador IS 'Rif o Cédula de identidad del cobrador';
COMMENT ON COLUMN shd500_aseo_domiciliario.ultimo_ano_facturado IS 'Ultimo año cancelado';
COMMENT ON COLUMN shd500_aseo_domiciliario.ultimo_mes_facturado IS 'Ultimo mes cancelado';

-- Table: shd600_solicitud_arrendamiento

-- DROP TABLE shd600_solicitud_arrendamiento;

CREATE TABLE shd600_solicitud_arrendamiento
(
  cod_presi integer NOT NULL, -- Código de la presidencia
  cod_entidad integer NOT NULL, -- Código de la entidad
  cod_tipo_inst integer NOT NULL, -- Código tipo de la Institución
  cod_inst integer NOT NULL, -- Código de la Institución
  cod_dep integer NOT NULL, -- Código de la dependencia
  numero_solicitud character varying(20) NOT NULL, -- Número de solicitud
  fecha_solicitud date NOT NULL, -- Fecha de la solicitud de arrendamiento
  rif_cedula character varying(20) NOT NULL, -- Rif o Cédula de identidad
  opcion integer NOT NULL, -- Opción...
  cod_ficha character varying(20) NOT NULL, -- Código de la ficha catastral
  expectativa_construccion text, -- Expectativa de construcción que tiene el solicitante
  CONSTRAINT shd600_solicitud_arrendamiento_pkey PRIMARY KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, numero_solicitud)
)
WITH (OIDS=FALSE);
ALTER TABLE shd600_solicitud_arrendamiento OWNER TO sisap;
COMMENT ON TABLE shd600_solicitud_arrendamiento IS 'Registra la solicitud de arrendamiento';
COMMENT ON COLUMN shd600_solicitud_arrendamiento.cod_presi IS 'Código de la presidencia';
COMMENT ON COLUMN shd600_solicitud_arrendamiento.cod_entidad IS 'Código de la entidad';
COMMENT ON COLUMN shd600_solicitud_arrendamiento.cod_tipo_inst IS 'Código tipo de la Institución';
COMMENT ON COLUMN shd600_solicitud_arrendamiento.cod_inst IS 'Código de la Institución';
COMMENT ON COLUMN shd600_solicitud_arrendamiento.cod_dep IS 'Código de la dependencia';
COMMENT ON COLUMN shd600_solicitud_arrendamiento.numero_solicitud IS 'Número de solicitud';
COMMENT ON COLUMN shd600_solicitud_arrendamiento.fecha_solicitud IS 'Fecha de la solicitud de arrendamiento';
COMMENT ON COLUMN shd600_solicitud_arrendamiento.rif_cedula IS 'Rif o Cédula de identidad';
COMMENT ON COLUMN shd600_solicitud_arrendamiento.opcion IS 'Opción
1.- Simple
2.- Compra';
COMMENT ON COLUMN shd600_solicitud_arrendamiento.cod_ficha IS 'Código de la ficha catastral';
COMMENT ON COLUMN shd600_solicitud_arrendamiento.expectativa_construccion IS 'Expectativa de construcción que tiene el solicitante';

-- Table: shd600_aprobacion_arrendamiento

-- DROP TABLE shd600_aprobacion_arrendamiento;

CREATE TABLE shd600_aprobacion_arrendamiento
(
  cod_presi integer NOT NULL, -- Código de la presidencia
  cod_entidad integer NOT NULL, -- Código de la entidad
  cod_tipo_inst integer NOT NULL, -- Código tipo de Institución
  cod_inst integer NOT NULL, -- Código de la Institución
  cod_dep integer NOT NULL, -- Código de la dependencia
  numero_solicitud character varying(20) NOT NULL, -- Número de solicitud de arrendamiento
  fecha_aprobacion date NOT NULL, -- Fecha de aprobacion
  frecuencia_pago integer NOT NULL, -- Frecuencia de pago...
  datos_registro_arrendamiento text NOT NULL, -- Datos registro de arrendamiento
  monto_mensual numeric(26,2) NOT NULL, -- Monto mensual
  pago_todo integer, -- Contribuyente pago todo el año ?...
  suspendido integer NOT NULL, -- Pago del contribuyente esta suspendido ?...
  rif_ci_cobrador character varying(20) NOT NULL, -- Rif o Cédula de identidad del cobrador
  ultimo_ano_facturado integer, -- Ultimo año cancelado
  ultimo_mes_facturado integer, -- Ultimo mes cancelado
  terreno_vendido integer, -- Terreno vendido ?...
  CONSTRAINT shd600_aprobacion_arrendamiento_pkey PRIMARY KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, numero_solicitud),
  CONSTRAINT shd600_aprobacion_arrendamiento_1 FOREIGN KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, numero_solicitud)
      REFERENCES shd600_solicitud_arrendamiento (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, numero_solicitud) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE CASCADE
)
WITH (OIDS=FALSE);
ALTER TABLE shd600_aprobacion_arrendamiento OWNER TO sisap;
COMMENT ON TABLE shd600_aprobacion_arrendamiento IS 'Registro de la aprobación a la solicitud de arrendamiento';
COMMENT ON COLUMN shd600_aprobacion_arrendamiento.cod_presi IS 'Código de la presidencia';
COMMENT ON COLUMN shd600_aprobacion_arrendamiento.cod_entidad IS 'Código de la entidad';
COMMENT ON COLUMN shd600_aprobacion_arrendamiento.cod_tipo_inst IS 'Código tipo de Institución';
COMMENT ON COLUMN shd600_aprobacion_arrendamiento.cod_inst IS 'Código de la Institución';
COMMENT ON COLUMN shd600_aprobacion_arrendamiento.cod_dep IS 'Código de la dependencia';
COMMENT ON COLUMN shd600_aprobacion_arrendamiento.numero_solicitud IS 'Número de solicitud de arrendamiento';
COMMENT ON COLUMN shd600_aprobacion_arrendamiento.fecha_aprobacion IS 'Fecha de aprobacion';
COMMENT ON COLUMN shd600_aprobacion_arrendamiento.frecuencia_pago IS 'Frecuencia de pago
1.- Mensual
2.- Bimestral
3.- Trimestral
4.- Semestral
5.- Anual';
COMMENT ON COLUMN shd600_aprobacion_arrendamiento.datos_registro_arrendamiento IS 'Datos registro de arrendamiento';
COMMENT ON COLUMN shd600_aprobacion_arrendamiento.monto_mensual IS 'Monto mensual';
COMMENT ON COLUMN shd600_aprobacion_arrendamiento.pago_todo IS 'Contribuyente pago todo el año ?
1.- Si
2.- No';
COMMENT ON COLUMN shd600_aprobacion_arrendamiento.suspendido IS 'Pago del contribuyente esta suspendido ?
1.- Si
2.- No';
COMMENT ON COLUMN shd600_aprobacion_arrendamiento.rif_ci_cobrador IS 'Rif o Cédula de identidad del cobrador';
COMMENT ON COLUMN shd600_aprobacion_arrendamiento.ultimo_ano_facturado IS 'Ultimo año cancelado';
COMMENT ON COLUMN shd600_aprobacion_arrendamiento.ultimo_mes_facturado IS 'Ultimo mes cancelado';
COMMENT ON COLUMN shd600_aprobacion_arrendamiento.terreno_vendido IS 'Terreno vendido ?
1.- Si
2.- No';

-- Table: shd600_compra_terreno

-- DROP TABLE shd600_compra_terreno;

CREATE TABLE shd600_compra_terreno
(
  cod_presi integer NOT NULL, -- Código de la presidencia
  cod_entidad integer NOT NULL, -- Código de la entidad
  cod_tipo_inst integer NOT NULL, -- Código tipo de Institución
  cod_inst integer NOT NULL, -- Código de la Institución
  cod_dep integer NOT NULL, -- Código de la dependencia
  numero_solicitud character varying(20) NOT NULL, -- Número de solicitud
  fecha_compra date NOT NULL, -- Fecha de compra
  datos_compra text NOT NULL, -- Datos de la compra
  monto numeric(26,2) NOT NULL, -- Monto de compra
  CONSTRAINT shd600_compra_terreno_pkey PRIMARY KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, numero_solicitud),
  CONSTRAINT shd600_compra_terreno_1 FOREIGN KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, numero_solicitud)
      REFERENCES shd600_aprobacion_arrendamiento (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, numero_solicitud) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE CASCADE
)
WITH (OIDS=FALSE);
ALTER TABLE shd600_compra_terreno OWNER TO sisap;
COMMENT ON TABLE shd600_compra_terreno IS 'Registra la compra del terreno, previo arrendamiento';
COMMENT ON COLUMN shd600_compra_terreno.cod_presi IS 'Código de la presidencia';
COMMENT ON COLUMN shd600_compra_terreno.cod_entidad IS 'Código de la entidad';
COMMENT ON COLUMN shd600_compra_terreno.cod_tipo_inst IS 'Código tipo de Institución';
COMMENT ON COLUMN shd600_compra_terreno.cod_inst IS 'Código de la Institución';
COMMENT ON COLUMN shd600_compra_terreno.cod_dep IS 'Código de la dependencia';
COMMENT ON COLUMN shd600_compra_terreno.numero_solicitud IS 'Número de solicitud';
COMMENT ON COLUMN shd600_compra_terreno.fecha_compra IS 'Fecha de compra';
COMMENT ON COLUMN shd600_compra_terreno.datos_compra IS 'Datos de la compra';
COMMENT ON COLUMN shd600_compra_terreno.monto IS 'Monto de compra';

-- Table: shd700_credito_vivienda

-- DROP TABLE shd700_credito_vivienda;

CREATE TABLE shd700_credito_vivienda
(
  cod_presi integer NOT NULL, -- Código de la presidencia
  cod_entidad integer NOT NULL, -- Código de la entidad
  cod_tipo_inst integer NOT NULL, -- Código tipo de Institución
  cod_inst integer NOT NULL, -- Código de la Institución
  cod_dep integer NOT NULL, -- Código de la dependencia
  rif_cedula character varying(20) NOT NULL, -- Rif o Cédula de identidad
  fecha_solicitud date NOT NULL, -- Fecha de solicitud
  nombre_conyugue character varying(100), -- Nombre y apellidos del conyugue
  cedula_conyugue integer, -- Cédula del conyugue
  nombre_empresa character varying(100), -- Nombre de la empresa donde trabaja el solicitante
  tiempo_empresa character varying(20), -- Tiempo de trabajo en la empresa
  telefonos_empresas character varying(50), -- Teléfonos de la empresa
  direccion_empresa text, -- Dirección de la empresa
  grupo_familiar integer, -- Grupo familiar
  ingreso_mensual numeric(26,2), -- Ingreso mensual familiar
  vivienda_actual integer, -- Vivienda actual...
  tipo_vivienda character varying(10), -- Tipo de vivienda
  direccion_vivienda_credito text, -- Dirección de la vivienda sujeta al crédito
  costo_vivienda numeric(26,2) NOT NULL, -- Costo de la vivienda sujeta al crédito
  monto_cuota_inicial numeric(26,2) NOT NULL, -- Monto de la cuota inicial
  monto_restante numeric(26,2) NOT NULL, -- Monto restante del crédito
  factor numeric(14,12), -- Factor de cálculo
  plazo_anos integer NOT NULL, -- Plazo años
  numero_cuotas integer NOT NULL, -- Número de cuotas
  monto_mensual numeric(26,2) NOT NULL, -- Monto a cancelar mensual
  numero_contrato character varying(20), -- Número de contrato
  fecha_contrato date, -- Fecha de contrato
  frecuencia_pago integer NOT NULL, -- Frecuencia de pago...
  pago_todo integer, -- Contribuyente pago todo el año?...
  suspendido integer, -- Suspendido el cobro a este contribuyente
  rif_ci_cobrador character varying(20) NOT NULL, -- Rif o cédula de identidad del cobrador
  ultimo_ano_facturado integer NOT NULL, -- Ultimo año cancelado
  ultimo_mes_facturado integer, -- Ultimo mes cancelado
  area_construccion numeric(10,3),
  area_terreno numeric(10,3),
  norte text,
  sur text,
  este text,
  oeste text,
  tasa_interes numeric(5,2),
  fecha_entrega_contrato date,
  CONSTRAINT shd700_credito_vivienda_pkey PRIMARY KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, rif_cedula)
)
WITH (OIDS=FALSE);
ALTER TABLE shd700_credito_vivienda OWNER TO sisap;
COMMENT ON TABLE shd700_credito_vivienda IS 'Registro de solicitantes de créditos de vivienda';
COMMENT ON COLUMN shd700_credito_vivienda.cod_presi IS 'Código de la presidencia';
COMMENT ON COLUMN shd700_credito_vivienda.cod_entidad IS 'Código de la entidad';
COMMENT ON COLUMN shd700_credito_vivienda.cod_tipo_inst IS 'Código tipo de Institución';
COMMENT ON COLUMN shd700_credito_vivienda.cod_inst IS 'Código de la Institución';
COMMENT ON COLUMN shd700_credito_vivienda.cod_dep IS 'Código de la dependencia';
COMMENT ON COLUMN shd700_credito_vivienda.rif_cedula IS 'Rif o Cédula de identidad';
COMMENT ON COLUMN shd700_credito_vivienda.fecha_solicitud IS 'Fecha de solicitud';
COMMENT ON COLUMN shd700_credito_vivienda.nombre_conyugue IS 'Nombre y apellidos del conyugue';
COMMENT ON COLUMN shd700_credito_vivienda.cedula_conyugue IS 'Cédula del conyugue';
COMMENT ON COLUMN shd700_credito_vivienda.nombre_empresa IS 'Nombre de la empresa donde trabaja el solicitante';
COMMENT ON COLUMN shd700_credito_vivienda.tiempo_empresa IS 'Tiempo de trabajo en la empresa';
COMMENT ON COLUMN shd700_credito_vivienda.telefonos_empresas IS 'Teléfonos de la empresa';
COMMENT ON COLUMN shd700_credito_vivienda.direccion_empresa IS 'Dirección de la empresa';
COMMENT ON COLUMN shd700_credito_vivienda.grupo_familiar IS 'Grupo familiar';
COMMENT ON COLUMN shd700_credito_vivienda.ingreso_mensual IS 'Ingreso mensual familiar';
COMMENT ON COLUMN shd700_credito_vivienda.vivienda_actual IS 'Vivienda actual
1.- Propia
2.- Alquilada
3.- Familiar';
COMMENT ON COLUMN shd700_credito_vivienda.tipo_vivienda IS 'Tipo de vivienda
';
COMMENT ON COLUMN shd700_credito_vivienda.direccion_vivienda_credito IS 'Dirección de la vivienda sujeta al crédito';
COMMENT ON COLUMN shd700_credito_vivienda.costo_vivienda IS 'Costo de la vivienda sujeta al crédito';
COMMENT ON COLUMN shd700_credito_vivienda.monto_cuota_inicial IS 'Monto de la cuota inicial';
COMMENT ON COLUMN shd700_credito_vivienda.monto_restante IS 'Monto restante del crédito';
COMMENT ON COLUMN shd700_credito_vivienda.factor IS 'Factor de cálculo';
COMMENT ON COLUMN shd700_credito_vivienda.plazo_anos IS 'Plazo años';
COMMENT ON COLUMN shd700_credito_vivienda.numero_cuotas IS 'Número de cuotas';
COMMENT ON COLUMN shd700_credito_vivienda.monto_mensual IS 'Monto a cancelar mensual';
COMMENT ON COLUMN shd700_credito_vivienda.numero_contrato IS 'Número de contrato';
COMMENT ON COLUMN shd700_credito_vivienda.fecha_contrato IS 'Fecha de contrato';
COMMENT ON COLUMN shd700_credito_vivienda.frecuencia_pago IS 'Frecuencia de pago
1.- Mensual
2.- Bimestral
3.- Trimestral
4.- Semestral
5.- Anual';
COMMENT ON COLUMN shd700_credito_vivienda.pago_todo IS 'Contribuyente pago todo el año?
1.- Si
2.- No
';
COMMENT ON COLUMN shd700_credito_vivienda.suspendido IS 'Suspendido el cobro a este contribuyente';
COMMENT ON COLUMN shd700_credito_vivienda.rif_ci_cobrador IS 'Rif o cédula de identidad del cobrador';
COMMENT ON COLUMN shd700_credito_vivienda.ultimo_ano_facturado IS 'Ultimo año cancelado';
COMMENT ON COLUMN shd700_credito_vivienda.ultimo_mes_facturado IS 'Ultimo mes cancelado';

-- Table: shd700_credito_vivienda_parentesco

-- DROP TABLE shd700_credito_vivienda_parentesco;

CREATE TABLE shd700_credito_vivienda_parentesco
(
  cod_presi integer NOT NULL, -- Código de la presidencia
  cod_entidad integer NOT NULL, -- Código de la entidad
  cod_tipo_inst integer NOT NULL, -- Código tipo de Institución
  cod_inst integer NOT NULL, -- Código de la Institución
  cod_dep integer NOT NULL, -- Código de la dependencia
  rif_cedula character varying(20) NOT NULL, -- Rif o cédula del solicitante
  cod_parentesco integer NOT NULL, -- Código de parentesco
  nombre_apellido character varying(100) NOT NULL, -- Nombre del apellido de pariente
  sexo integer NOT NULL, -- Sexo...
  fecha_nacimiento date NOT NULL, -- Fecha de nacimiento
  CONSTRAINT shd700_credito_vivienda_parentesco_pkey PRIMARY KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, rif_cedula, cod_parentesco),
  CONSTRAINT shd700_credito_vivienda_parentesco_1 FOREIGN KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, rif_cedula)
      REFERENCES shd700_credito_vivienda (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, rif_cedula) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE CASCADE
)
WITH (OIDS=FALSE);
ALTER TABLE shd700_credito_vivienda_parentesco OWNER TO sisap;
COMMENT ON TABLE shd700_credito_vivienda_parentesco IS 'Registro de parentesco de las solicitudes de crédito de vivienda';
COMMENT ON COLUMN shd700_credito_vivienda_parentesco.cod_presi IS 'Código de la presidencia';
COMMENT ON COLUMN shd700_credito_vivienda_parentesco.cod_entidad IS 'Código de la entidad';
COMMENT ON COLUMN shd700_credito_vivienda_parentesco.cod_tipo_inst IS 'Código tipo de Institución';
COMMENT ON COLUMN shd700_credito_vivienda_parentesco.cod_inst IS 'Código de la Institución';
COMMENT ON COLUMN shd700_credito_vivienda_parentesco.cod_dep IS 'Código de la dependencia';
COMMENT ON COLUMN shd700_credito_vivienda_parentesco.rif_cedula IS 'Rif o cédula del solicitante';
COMMENT ON COLUMN shd700_credito_vivienda_parentesco.cod_parentesco IS 'Código de parentesco';
COMMENT ON COLUMN shd700_credito_vivienda_parentesco.nombre_apellido IS 'Nombre del apellido de pariente';
COMMENT ON COLUMN shd700_credito_vivienda_parentesco.sexo IS 'Sexo
1.- Masculino
2.- Femenino';
COMMENT ON COLUMN shd700_credito_vivienda_parentesco.fecha_nacimiento IS 'Fecha de nacimiento';

-- View: v_shd001_registro_contribuyentes

-- DROP VIEW v_shd001_registro_contribuyentes;

CREATE OR REPLACE VIEW v_shd001_registro_contribuyentes AS 
 SELECT a.rif_cedula, a.personalidad_juridica, a.razon_social_nombres, a.fecha_inscripcion, a.nacionalidad, a.estado_civil, a.profesion, ( SELECT b.denominacion
           FROM cnmd06_profesiones b
          WHERE a.profesion = b.cod_profesion) AS deno_profesion, a.cod_pais, ( SELECT c.denominacion
           FROM cugd01_republica c
          WHERE a.cod_pais = c.cod_republica) AS deno_pais, a.cod_estado, ( SELECT d.denominacion
           FROM cugd01_estados d
          WHERE a.cod_pais = d.cod_republica AND a.cod_estado = d.cod_estado) AS deno_estado, a.cod_municipio, ( SELECT e.denominacion
           FROM cugd01_municipios e
          WHERE a.cod_pais = e.cod_republica AND a.cod_estado = e.cod_estado AND a.cod_municipio = e.cod_municipio) AS deno_municipio, a.cod_parroquia, ( SELECT f.denominacion
           FROM cugd01_parroquias f
          WHERE a.cod_pais = f.cod_republica AND a.cod_estado = f.cod_estado AND a.cod_municipio = f.cod_municipio AND a.cod_parroquia = f.cod_parroquia) AS deno_parroquia, a.cod_centro_poblado, ( SELECT g.denominacion
           FROM cugd01_centros_poblados g
          WHERE a.cod_pais = g.cod_republica AND a.cod_estado = g.cod_estado AND a.cod_municipio = g.cod_municipio AND a.cod_parroquia = g.cod_parroquia AND a.cod_centro_poblado = g.cod_centro) AS deno_centro, a.cod_calle_avenida, ( SELECT h.denominacion
           FROM cugd01_vialidad h
          WHERE a.cod_pais = h.cod_republica AND a.cod_estado = h.cod_estado AND a.cod_municipio = h.cod_municipio AND a.cod_parroquia = h.cod_parroquia AND a.cod_centro_poblado = h.cod_centro AND a.cod_calle_avenida = h.cod_vialidad) AS deno_vialidad, a.cod_vereda_edificio, ( SELECT i.denominacion
           FROM cugd01_vereda i
          WHERE a.cod_pais = i.cod_republica AND a.cod_estado = i.cod_estado AND a.cod_municipio = i.cod_municipio AND a.cod_parroquia = i.cod_parroquia AND a.cod_centro_poblado = i.cod_centro AND a.cod_calle_avenida = i.cod_vialidad AND a.cod_vereda_edificio = i.cod_vereda) AS deno_vereda, a.numero_vivienda_local, a.telefonos_fijos, a.telefonos_celulares, a.correo_electronico
   FROM shd001_registro_contribuyentes a;

ALTER TABLE v_shd001_registro_contribuyentes OWNER TO sisap;

-- View: v_shd100_declaracion_ingreso

-- DROP VIEW v_shd100_declaracion_ingreso;

CREATE OR REPLACE VIEW v_shd100_declaracion_ingreso AS 
 SELECT x.cod_presi, x.cod_entidad, x.cod_tipo_inst, x.cod_inst, x.cod_dep, x.numero_solicitud, x.numero_patente, x.fecha_solicitud, a.rif_cedula, a.razon_social_nombres, a.cod_pais, ( SELECT b.denominacion
           FROM cugd01_republica b
          WHERE b.cod_republica = a.cod_pais) AS denominacion_pais, a.cod_estado, ( SELECT c.denominacion
           FROM cugd01_estados c
          WHERE c.cod_republica = a.cod_pais AND c.cod_estado = a.cod_estado) AS denominacion_estado, a.cod_municipio, ( SELECT d.denominacion
           FROM cugd01_municipios d
          WHERE d.cod_republica = a.cod_pais AND d.cod_estado = a.cod_estado AND d.cod_municipio = a.cod_municipio) AS denominacion_municipio, a.cod_parroquia, ( SELECT e.denominacion
           FROM cugd01_parroquias e
          WHERE e.cod_republica = a.cod_pais AND e.cod_estado = a.cod_estado AND e.cod_municipio = a.cod_municipio AND e.cod_parroquia = a.cod_parroquia) AS denominacion_parroquia, a.cod_centro_poblado, ( SELECT f.denominacion
           FROM cugd01_centros_poblados f
          WHERE f.cod_republica = a.cod_pais AND f.cod_estado = a.cod_estado AND f.cod_municipio = a.cod_municipio AND f.cod_parroquia = a.cod_parroquia AND f.cod_centro = a.cod_centro_poblado) AS denominacion_centro, a.cod_calle_avenida, ( SELECT g.denominacion
           FROM cugd01_vialidad g
          WHERE g.cod_republica = a.cod_pais AND g.cod_estado = a.cod_estado AND g.cod_municipio = a.cod_municipio AND g.cod_parroquia = a.cod_parroquia AND g.cod_centro = a.cod_centro_poblado AND g.cod_vialidad = a.cod_calle_avenida) AS denominacion_vialidad, a.cod_vereda_edificio, ( SELECT h.denominacion
           FROM cugd01_vereda h
          WHERE h.cod_republica = a.cod_pais AND h.cod_estado = a.cod_estado AND h.cod_municipio = a.cod_municipio AND h.cod_parroquia = a.cod_parroquia AND h.cod_centro = a.cod_centro_poblado AND h.cod_vialidad = a.cod_calle_avenida AND h.cod_vereda = a.cod_vereda_edificio) AS denominacion_vereda, a.numero_vivienda_local, a.telefonos_fijos, a.telefonos_celulares, a.correo_electronico
   FROM shd001_registro_contribuyentes a, shd100_solicitud x
  WHERE x.rif_cedula::text = a.rif_cedula::text;

ALTER TABLE v_shd100_declaracion_ingreso OWNER TO sisap;

-- View: v_shd100_patente

-- DROP VIEW v_shd100_patente;

CREATE OR REPLACE VIEW v_shd100_patente AS 
 SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.numero_solicitud, ( SELECT b.fecha_solicitud
           FROM shd100_solicitud b
          WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text) AS fecha_solicitud, ( SELECT b.rif_cedula
           FROM shd100_solicitud b
          WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text) AS rif_cedula, ( SELECT c.razon_social_nombres
           FROM v_shd001_registro_contribuyentes c
          WHERE ((( SELECT b.rif_cedula
                   FROM shd100_solicitud b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::text) = c.rif_cedula::text) AS deno_razon, ( SELECT c.nacionalidad
           FROM v_shd001_registro_contribuyentes c
          WHERE ((( SELECT b.rif_cedula
                   FROM shd100_solicitud b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::text) = c.rif_cedula::text) AS nacionalidad, ( SELECT c.correo_electronico
           FROM v_shd001_registro_contribuyentes c
          WHERE ((( SELECT b.rif_cedula
                   FROM shd100_solicitud b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::text) = c.rif_cedula::text) AS correo, ( SELECT c.telefonos_celulares
           FROM v_shd001_registro_contribuyentes c
          WHERE ((( SELECT b.rif_cedula
                   FROM shd100_solicitud b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::text) = c.rif_cedula::text) AS telefonos_celulares, ( SELECT c.telefonos_fijos
           FROM v_shd001_registro_contribuyentes c
          WHERE ((( SELECT b.rif_cedula
                   FROM shd100_solicitud b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::text) = c.rif_cedula::text) AS telefonos_fijos, ( SELECT c.fecha_inscripcion
           FROM v_shd001_registro_contribuyentes c
          WHERE ((( SELECT b.rif_cedula
                   FROM shd100_solicitud b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::text) = c.rif_cedula::text) AS fecha_inscripcion, ( SELECT c.cod_pais
           FROM v_shd001_registro_contribuyentes c
          WHERE ((( SELECT b.rif_cedula
                   FROM shd100_solicitud b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::text) = c.rif_cedula::text) AS cod_pais, ( SELECT c.deno_pais
           FROM v_shd001_registro_contribuyentes c
          WHERE ((( SELECT b.rif_cedula
                   FROM shd100_solicitud b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::text) = c.rif_cedula::text) AS deno_pais, ( SELECT c.cod_estado
           FROM v_shd001_registro_contribuyentes c
          WHERE ((( SELECT b.rif_cedula
                   FROM shd100_solicitud b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::text) = c.rif_cedula::text) AS cod_estado, ( SELECT c.deno_estado
           FROM v_shd001_registro_contribuyentes c
          WHERE ((( SELECT b.rif_cedula
                   FROM shd100_solicitud b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::text) = c.rif_cedula::text) AS deno_estado, ( SELECT c.cod_municipio
           FROM v_shd001_registro_contribuyentes c
          WHERE ((( SELECT b.rif_cedula
                   FROM shd100_solicitud b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::text) = c.rif_cedula::text) AS cod_municipio, ( SELECT c.deno_municipio
           FROM v_shd001_registro_contribuyentes c
          WHERE ((( SELECT b.rif_cedula
                   FROM shd100_solicitud b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::text) = c.rif_cedula::text) AS deno_municipio, ( SELECT c.cod_parroquia
           FROM v_shd001_registro_contribuyentes c
          WHERE ((( SELECT b.rif_cedula
                   FROM shd100_solicitud b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::text) = c.rif_cedula::text) AS cod_parroquia, ( SELECT c.deno_parroquia
           FROM v_shd001_registro_contribuyentes c
          WHERE ((( SELECT b.rif_cedula
                   FROM shd100_solicitud b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::text) = c.rif_cedula::text) AS deno_parroquia, ( SELECT c.cod_centro_poblado
           FROM v_shd001_registro_contribuyentes c
          WHERE ((( SELECT b.rif_cedula
                   FROM shd100_solicitud b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::text) = c.rif_cedula::text) AS cod_centro_poblado, ( SELECT c.deno_centro
           FROM v_shd001_registro_contribuyentes c
          WHERE ((( SELECT b.rif_cedula
                   FROM shd100_solicitud b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::text) = c.rif_cedula::text) AS deno_centro_poblado, ( SELECT c.cod_calle_avenida
           FROM v_shd001_registro_contribuyentes c
          WHERE ((( SELECT b.rif_cedula
                   FROM shd100_solicitud b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::text) = c.rif_cedula::text) AS cod_calle, ( SELECT c.deno_vialidad
           FROM v_shd001_registro_contribuyentes c
          WHERE ((( SELECT b.rif_cedula
                   FROM shd100_solicitud b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::text) = c.rif_cedula::text) AS deno_calle, ( SELECT c.cod_vereda_edificio
           FROM v_shd001_registro_contribuyentes c
          WHERE ((( SELECT b.rif_cedula
                   FROM shd100_solicitud b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::text) = c.rif_cedula::text) AS cod_vereda, ( SELECT c.deno_vereda
           FROM v_shd001_registro_contribuyentes c
          WHERE ((( SELECT b.rif_cedula
                   FROM shd100_solicitud b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::text) = c.rif_cedula::text) AS deno_vereda, ( SELECT c.profesion
           FROM v_shd001_registro_contribuyentes c
          WHERE ((( SELECT b.rif_cedula
                   FROM shd100_solicitud b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::text) = c.rif_cedula::text) AS cod_profesion, ( SELECT c.deno_profesion
           FROM v_shd001_registro_contribuyentes c
          WHERE ((( SELECT b.rif_cedula
                   FROM shd100_solicitud b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::text) = c.rif_cedula::text) AS deno_profesion, ( SELECT c.numero_vivienda_local
           FROM v_shd001_registro_contribuyentes c
          WHERE ((( SELECT b.rif_cedula
                   FROM shd100_solicitud b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::text) = c.rif_cedula::text) AS numero_casa, ( SELECT c.estado_civil
           FROM v_shd001_registro_contribuyentes c
          WHERE ((( SELECT b.rif_cedula
                   FROM shd100_solicitud b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::text) = c.rif_cedula::text) AS estado_civil, a.numero_patente, a.frecuencia_pago, a.monto_mensual, a.pago_todo, a.suspendido, a.rif_ci_cobrador, ( SELECT d.nombre_razon
           FROM shd002_cobradores d
          WHERE a.cod_presi = d.cod_presi AND a.cod_entidad = d.cod_entidad AND a.cod_tipo_inst = d.cod_tipo_inst AND a.cod_inst = d.cod_inst AND a.cod_dep = d.cod_dep AND a.rif_ci_cobrador::text = d.rif_ci::text) AS deno_cobrador, a.ultimo_ano_facturado, a.ultimo_mes_facturado, a.fecha_ultima_decla, a.ingresos_declarados, a.ultimo_ejercicio_decla, a.periodo_desde, a.periodo_hasta, a.fecha_patente
   FROM shd100_patente a;

ALTER TABLE v_shd100_patente OWNER TO sisap;

-- View: v_shd100_patente_actividades

-- DROP VIEW v_shd100_patente_actividades;

CREATE OR REPLACE VIEW v_shd100_patente_actividades AS 
 SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.numero_solicitud, a.numero_patente, a.cod_actividad, ( SELECT b.denominacion_actividad
           FROM shd100_actividades b
          WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.cod_actividad::text = b.cod_actividad::text) AS deno_actividad, a.numero_aforos, a.monto_aforo_anual, a.total_aforo_anual
   FROM shd100_patente_actividades a;

ALTER TABLE v_shd100_patente_actividades OWNER TO sisap;

-- View: v_shd100_solicitud

-- DROP VIEW v_shd100_solicitud;

CREATE OR REPLACE VIEW v_shd100_solicitud AS 
 SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.numero_solicitud, a.fecha_solicitud, a.rif_cedula, b.razon_social_nombres, a.numero_ficha_catastral, a.capital, a.horario_trab_desde, a.horario_trab_hasta, a.tipo_establecimiento, a.tipo_local, a.nacionalidad AS nacionalidad_repre, a.cedula_identidad, a.nombres_apellidos, a.cod_pais AS pais_repre, ( SELECT c.denominacion
           FROM cugd01_republica c
          WHERE a.cod_pais = c.cod_republica) AS deno_pais_repre, a.cod_estado AS estado_repre, ( SELECT d.denominacion
           FROM cugd01_estados d
          WHERE a.cod_pais = d.cod_republica AND a.cod_estado = d.cod_estado) AS deno_estado_repre, a.cod_municipio AS municipio_repre, ( SELECT e.denominacion
           FROM cugd01_municipios e
          WHERE a.cod_pais = e.cod_republica AND a.cod_estado = e.cod_estado AND a.cod_municipio = e.cod_municipio) AS deno_municipio_repre, a.cod_parroquia AS parroquia_repre, ( SELECT f.denominacion
           FROM cugd01_parroquias f
          WHERE a.cod_pais = f.cod_republica AND a.cod_estado = f.cod_estado AND a.cod_municipio = f.cod_municipio AND a.cod_parroquia = f.cod_parroquia) AS deno_parroquia_repre, a.cod_centro AS centro_repre, ( SELECT g.denominacion
           FROM cugd01_centros_poblados g
          WHERE a.cod_pais = g.cod_republica AND a.cod_estado = g.cod_estado AND a.cod_municipio = g.cod_municipio AND a.cod_parroquia = g.cod_parroquia AND a.cod_centro = g.cod_centro) AS deno_centro_repre, a.cod_vialidad AS vialidad_repre, ( SELECT h.denominacion
           FROM cugd01_vialidad h
          WHERE a.cod_pais = h.cod_republica AND a.cod_estado = h.cod_estado AND a.cod_municipio = h.cod_municipio AND a.cod_parroquia = h.cod_parroquia AND a.cod_centro = h.cod_centro AND a.cod_vialidad = h.cod_vialidad) AS deno_vialidad_repre, a.cod_vereda AS vereda_repre, ( SELECT i.denominacion
           FROM cugd01_vereda i
          WHERE a.cod_pais = i.cod_republica AND a.cod_estado = i.cod_estado AND a.cod_municipio = i.cod_municipio AND a.cod_parroquia = i.cod_parroquia AND a.cod_centro = i.cod_centro AND a.cod_vialidad = i.cod_vialidad AND a.cod_vereda = i.cod_vereda) AS deno_vereda_repre, a.numero_casa_local AS numero_local_repre, a.telefonos_fijos AS telefonos_fijos_repre, a.telefonos_celulares AS telefonos_celulares_repre, a.correo_electronico AS correo_electronico_repre, a.fecha_inicio_const, a.fecha_cierre_const, a.fecha_inicio_econo, a.fecha_cierre_economico, a.registro_mercantil, a.tiene_sucursal, a.es_fabricante, a.numero_empleado, a.numero_obreros, a.distancia_bar, a.distancia_hospital, a.distancia_educativo, a.distancia_funeraria, a.distancia_estacion, a.distancia_gubernam, a.tilde_reg_mercantil, a.tilde_fotoco_ci, a.tilde_acta_const, a.tilde_uso_conforme, a.tilde_croquis, a.tilde_bomberos, a.tilde_rif, a.tilde_solvencia, a.tilde_concejo, a.tilde_recibo, a.tilde_planilla, a.tilde_permiso, a.numero_patente, b.cod_pais AS pais_razon, ( SELECT c.denominacion
           FROM cugd01_republica c
          WHERE b.cod_pais = c.cod_republica) AS deno_pais_razon, b.cod_estado AS estado_razon, ( SELECT d.denominacion
           FROM cugd01_estados d
          WHERE b.cod_pais = d.cod_republica AND b.cod_estado = d.cod_estado) AS deno_estado_razon, b.cod_municipio AS municipio_razon, ( SELECT e.denominacion
           FROM cugd01_municipios e
          WHERE b.cod_pais = e.cod_republica AND b.cod_estado = e.cod_estado AND b.cod_municipio = e.cod_municipio) AS deno_municipio_razon, b.cod_parroquia AS parroquia_razon, ( SELECT f.denominacion
           FROM cugd01_parroquias f
          WHERE b.cod_pais = f.cod_republica AND b.cod_estado = f.cod_estado AND b.cod_municipio = f.cod_municipio AND b.cod_parroquia = f.cod_parroquia) AS deno_parroquia_razon, b.cod_centro_poblado AS centro_razon, ( SELECT g.denominacion
           FROM cugd01_centros_poblados g
          WHERE b.cod_pais = g.cod_republica AND b.cod_estado = g.cod_estado AND b.cod_municipio = g.cod_municipio AND b.cod_parroquia = g.cod_parroquia AND b.cod_centro_poblado = g.cod_centro) AS deno_centro_razon, b.cod_calle_avenida AS calle_razon, ( SELECT h.denominacion
           FROM cugd01_vialidad h
          WHERE b.cod_pais = h.cod_republica AND b.cod_estado = h.cod_estado AND b.cod_municipio = h.cod_municipio AND b.cod_parroquia = h.cod_parroquia AND b.cod_centro_poblado = h.cod_centro AND b.cod_calle_avenida = h.cod_vialidad) AS deno_vialidad_razon, b.cod_vereda_edificio AS vereda_razon, ( SELECT i.denominacion
           FROM cugd01_vereda i
          WHERE b.cod_pais = i.cod_republica AND b.cod_estado = i.cod_estado AND b.cod_municipio = i.cod_municipio AND b.cod_parroquia = i.cod_parroquia AND b.cod_centro_poblado = i.cod_centro AND b.cod_calle_avenida = i.cod_vialidad AND b.cod_vereda_edificio = i.cod_vereda) AS deno_vereda_razon, b.fecha_inscripcion, b.telefonos_fijos AS telefonos_fijos_razon, b.telefonos_celulares AS telefonos_celulares_razon, b.correo_electronico AS correo_electronico_razon, b.nacionalidad AS nacionalidad_razon, b.estado_civil, b.numero_vivienda_local AS numero_local_razon, b.profesion, ( SELECT j.denominacion
           FROM cnmd06_profesiones j
          WHERE b.profesion = j.cod_profesion) AS deno_profesion, a.categoria_comercial, a.mercado_cubre, ( SELECT x.fecha_patente
           FROM shd100_patente x
          WHERE a.numero_patente::text = x.numero_patente::text AND a.cod_presi = x.cod_presi AND a.cod_entidad = x.cod_entidad AND a.cod_tipo_inst = x.cod_tipo_inst AND a.cod_dep = x.cod_dep) AS fecha_patente, ( SELECT x.frecuencia_pago
           FROM shd100_patente x
          WHERE a.numero_patente::text = x.numero_patente::text AND a.cod_presi = x.cod_presi AND a.cod_entidad = x.cod_entidad AND a.cod_tipo_inst = x.cod_tipo_inst AND a.cod_dep = x.cod_dep) AS frecuencia_pago
   FROM shd100_solicitud a, shd001_registro_contribuyentes b
  WHERE a.rif_cedula::text = b.rif_cedula::text;

ALTER TABLE v_shd100_solicitud OWNER TO sisap;

-- View: v_shd100_solicitud_actividades

-- DROP VIEW v_shd100_solicitud_actividades;

CREATE OR REPLACE VIEW v_shd100_solicitud_actividades AS 
 SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.numero_solicitud, a.cod_actividad, b.denominacion_actividad, b.alicuota, b.unidades_tributarias, b.minimo_tributable
   FROM shd100_solicitud_actividades a, shd100_actividades b
  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.cod_actividad::text = b.cod_actividad::text;

ALTER TABLE v_shd100_solicitud_actividades OWNER TO sisap;

-- View: v_shd200_vehiculos

-- DROP VIEW v_shd200_vehiculos;

CREATE OR REPLACE VIEW v_shd200_vehiculos AS 
 SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.rif_cedula, ( SELECT b.razon_social_nombres
           FROM v_shd001_registro_contribuyentes b
          WHERE a.rif_cedula::text = b.rif_cedula::text) AS nombre_razon, ( SELECT b.cod_pais
           FROM v_shd001_registro_contribuyentes b
          WHERE a.rif_cedula::text = b.rif_cedula::text) AS cod_pais, ( SELECT b.deno_pais
           FROM v_shd001_registro_contribuyentes b
          WHERE a.rif_cedula::text = b.rif_cedula::text) AS deno_pais, ( SELECT b.cod_estado
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
          WHERE a.rif_cedula::text = b.rif_cedula::text) AS fecha_inscripcion, ( SELECT b.telefonos_fijos
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
          WHERE a.rif_cedula::text = b.rif_cedula::text) AS deno_profesion, a.placa_vehiculo, a.fecha_registro, a.cod_marca, ( SELECT e.denominacion
           FROM shd200_vehiculos_marcas e
          WHERE a.cod_marca = e.codigo_marca) AS deno_marca, a.cod_modelo, ( SELECT f.denominacion
           FROM shd200_vehiculos_modelos f
          WHERE a.cod_modelo = f.codigo_modelo) AS deno_modelo, a.cod_color, ( SELECT g.denominacion
           FROM shd200_vehiculos_colores g
          WHERE a.cod_color = g.codigo_color) AS deno_color, a.cod_clase, ( SELECT h.denominacion
           FROM shd200_vehiculos_clases h
          WHERE a.cod_clase = h.codigo_clase) AS deno_clase, a.cod_tipo, ( SELECT i.denominacion
           FROM shd200_vehiculos_tipos i
          WHERE a.cod_tipo = i.codigo_tipo) AS deno_tipo, a.cod_uso, ( SELECT j.denominacion
           FROM shd200_vehiculos_usos j
          WHERE a.cod_uso = j.codigo_uso) AS deno_uso, a.serial_carroceria, a.serial_motor, a.ano_adquisicion, a.valor_vehiculo, a.fecha_adquisicion, a.cod_clasificacion, ( SELECT c.denominacion
           FROM shd200_vehiculos_clasificacion c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND a.cod_clasificacion::text = c.cod_clasificacion::text) AS deno_clasificacion, ( SELECT c.monto_anual
           FROM shd200_vehiculos_clasificacion c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND a.cod_clasificacion::text = c.cod_clasificacion::text) AS monto_anual, a.frecuencia_pago, a.monto_mensual, a.pago_todo, a.suspendido, a.rif_ci_cobrador, ( SELECT d.nombre_razon
           FROM shd002_cobradores d
          WHERE a.cod_presi = d.cod_presi AND a.cod_entidad = d.cod_entidad AND a.cod_tipo_inst = d.cod_tipo_inst AND a.cod_inst = d.cod_inst AND a.cod_dep = d.cod_dep AND a.rif_ci_cobrador::text = d.rif_ci::text) AS deno_cobrador, a.ultimo_ano_facturado, a.ultimo_mes_facturado
   FROM shd200_vehiculos a;

ALTER TABLE v_shd200_vehiculos OWNER TO sisap;

-- View: v_shd400_propiedad

-- DROP VIEW v_shd400_propiedad;

CREATE OR REPLACE VIEW v_shd400_propiedad AS 
 SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.rif_cedula, ( SELECT b.razon_social_nombres
           FROM v_shd001_registro_contribuyentes b
          WHERE a.rif_cedula::text = b.rif_cedula::text) AS nombre_razon, ( SELECT b.cod_pais
           FROM v_shd001_registro_contribuyentes b
          WHERE a.rif_cedula::text = b.rif_cedula::text) AS cod_pais, ( SELECT b.deno_pais
           FROM v_shd001_registro_contribuyentes b
          WHERE a.rif_cedula::text = b.rif_cedula::text) AS deno_pais, ( SELECT b.cod_estado
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
          WHERE a.rif_cedula::text = b.rif_cedula::text) AS deno_profesion, a.cod_ficha, ( SELECT c.cod_inscripcion
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND a.cod_ficha::integer = c.cod_ficha) AS cod_inscripcion, ( SELECT c.fecha_inscripcion
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND a.cod_ficha::integer = c.cod_ficha) AS fecha_inscripcion_cat, ( SELECT c.cod_control_archivo
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND a.cod_ficha::integer = c.cod_ficha) AS cod_control_archivo, ( SELECT c.ano_ordenanza
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND a.cod_ficha::integer = c.cod_ficha) AS ano_ordenanza, ( SELECT c.cod_act_edo
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND a.cod_ficha::integer = c.cod_ficha) AS cod_act_edo, ( SELECT c.cod_act_mun
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND a.cod_ficha::integer = c.cod_ficha) AS cod_act_mun, ( SELECT c.cod_act_prr
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND a.cod_ficha::integer = c.cod_ficha) AS cod_act_prr, ( SELECT c.cod_act_amb_t
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND a.cod_ficha::integer = c.cod_ficha) AS cod_act_amb_t, ( SELECT c.cod_act_amb
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND a.cod_ficha::integer = c.cod_ficha) AS cod_act_amb, ( SELECT c.cod_act_sec
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND a.cod_ficha::integer = c.cod_ficha) AS cod_act_sec, ( SELECT c.cod_act_man
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND a.cod_ficha::integer = c.cod_ficha) AS cod_act_man, ( SELECT c.cod_act_par
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND a.cod_ficha::integer = c.cod_ficha) AS cod_act_par, ( SELECT c.cod_act_sbp
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND a.cod_ficha::integer = c.cod_ficha) AS cod_act_sbp, ( SELECT c.cod_act_niv
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND a.cod_ficha::integer = c.cod_ficha) AS cod_act_niv, ( SELECT c.cod_act_und
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND a.cod_ficha::integer = c.cod_ficha) AS cod_act_und, ( SELECT c.tilde_tenencia
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND a.cod_ficha::integer = c.cod_ficha) AS tilde_tenencia, ( SELECT c.tilde_tenencia_const
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND a.cod_ficha::integer = c.cod_ficha) AS tilde_tenencia_const, ( SELECT sum(d.valor_actual) AS r2
           FROM catd02_ficha_tipologia d
          WHERE a.cod_presi = d.cod_presi AND a.cod_entidad = d.cod_entidad AND a.cod_tipo_inst = d.cod_tipo_inst AND a.cod_inst = d.cod_inst AND a.cod_dep = d.cod_dep AND a.cod_ficha::integer = d.cod_ficha) AS valor_construccion, a.frecuencia_pago, a.monto_mensual, a.pago_todo, a.suspendido, a.rif_ci_cobrador, ( SELECT e.nombre_razon
           FROM shd002_cobradores e
          WHERE a.cod_presi = e.cod_presi AND a.cod_entidad = e.cod_entidad AND a.cod_tipo_inst = e.cod_tipo_inst AND a.cod_inst = e.cod_inst AND a.cod_dep = e.cod_dep AND a.rif_ci_cobrador::text = e.rif_ci::text) AS deno_cobrador, a.ultimo_ano_facturado, a.ultimo_mes_facturado
   FROM shd400_propiedad a;

ALTER TABLE v_shd400_propiedad OWNER TO sisap;

-- View: v_shd600_aprobacion_arrendamiento

-- DROP VIEW v_shd600_aprobacion_arrendamiento;

CREATE OR REPLACE VIEW v_shd600_aprobacion_arrendamiento AS 
 SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.numero_solicitud, ( SELECT b.opcion
           FROM shd600_solicitud_arrendamiento b
          WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text) AS opcion, ( SELECT b.rif_cedula
           FROM shd600_solicitud_arrendamiento b
          WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text) AS rif_cedula, ( SELECT b.razon_social_nombres
           FROM v_shd001_registro_contribuyentes b
          WHERE ((( SELECT b.rif_cedula
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::text) = b.rif_cedula::text) AS nombre_razon, ( SELECT b.cod_pais
           FROM v_shd001_registro_contribuyentes b
          WHERE ((( SELECT b.rif_cedula
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::text) = b.rif_cedula::text) AS cod_pais, ( SELECT b.deno_pais
           FROM v_shd001_registro_contribuyentes b
          WHERE ((( SELECT b.rif_cedula
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::text) = b.rif_cedula::text) AS deno_pais, ( SELECT b.cod_estado
           FROM v_shd001_registro_contribuyentes b
          WHERE ((( SELECT b.rif_cedula
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::text) = b.rif_cedula::text) AS cod_estado, ( SELECT b.deno_estado
           FROM v_shd001_registro_contribuyentes b
          WHERE ((( SELECT b.rif_cedula
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::text) = b.rif_cedula::text) AS deno_estado, ( SELECT b.cod_municipio
           FROM v_shd001_registro_contribuyentes b
          WHERE ((( SELECT b.rif_cedula
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::text) = b.rif_cedula::text) AS cod_municipio, ( SELECT b.deno_municipio
           FROM v_shd001_registro_contribuyentes b
          WHERE ((( SELECT b.rif_cedula
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::text) = b.rif_cedula::text) AS deno_municipio, ( SELECT b.cod_parroquia
           FROM v_shd001_registro_contribuyentes b
          WHERE ((( SELECT b.rif_cedula
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::text) = b.rif_cedula::text) AS cod_parroquia, ( SELECT b.deno_parroquia
           FROM v_shd001_registro_contribuyentes b
          WHERE ((( SELECT b.rif_cedula
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::text) = b.rif_cedula::text) AS deno_parroquia, ( SELECT b.cod_centro_poblado
           FROM v_shd001_registro_contribuyentes b
          WHERE ((( SELECT b.rif_cedula
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::text) = b.rif_cedula::text) AS cod_centro, ( SELECT b.deno_centro
           FROM v_shd001_registro_contribuyentes b
          WHERE ((( SELECT b.rif_cedula
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::text) = b.rif_cedula::text) AS deno_centro, ( SELECT b.cod_calle_avenida
           FROM v_shd001_registro_contribuyentes b
          WHERE ((( SELECT b.rif_cedula
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::text) = b.rif_cedula::text) AS cod_calle, ( SELECT b.deno_vialidad
           FROM v_shd001_registro_contribuyentes b
          WHERE ((( SELECT b.rif_cedula
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::text) = b.rif_cedula::text) AS deno_calle, ( SELECT b.cod_vereda_edificio
           FROM v_shd001_registro_contribuyentes b
          WHERE ((( SELECT b.rif_cedula
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::text) = b.rif_cedula::text) AS cod_vereda_edificio, ( SELECT b.deno_vereda
           FROM v_shd001_registro_contribuyentes b
          WHERE ((( SELECT b.rif_cedula
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::text) = b.rif_cedula::text) AS deno_vereda, ( SELECT b.numero_vivienda_local
           FROM v_shd001_registro_contribuyentes b
          WHERE ((( SELECT b.rif_cedula
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::text) = b.rif_cedula::text) AS numero_casa, ( SELECT b.fecha_inscripcion
           FROM v_shd001_registro_contribuyentes b
          WHERE ((( SELECT b.rif_cedula
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::text) = b.rif_cedula::text) AS fecha_inscripcion_cont, ( SELECT b.telefonos_fijos
           FROM v_shd001_registro_contribuyentes b
          WHERE ((( SELECT b.rif_cedula
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::text) = b.rif_cedula::text) AS telefonos_fijos, ( SELECT b.telefonos_celulares
           FROM v_shd001_registro_contribuyentes b
          WHERE ((( SELECT b.rif_cedula
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::text) = b.rif_cedula::text) AS telefonos_celulares, ( SELECT b.correo_electronico
           FROM v_shd001_registro_contribuyentes b
          WHERE ((( SELECT b.rif_cedula
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::text) = b.rif_cedula::text) AS correo_electronico, ( SELECT b.nacionalidad
           FROM v_shd001_registro_contribuyentes b
          WHERE ((( SELECT b.rif_cedula
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::text) = b.rif_cedula::text) AS nacionalidad, ( SELECT b.estado_civil
           FROM v_shd001_registro_contribuyentes b
          WHERE ((( SELECT b.rif_cedula
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::text) = b.rif_cedula::text) AS estado_civil, ( SELECT b.deno_profesion
           FROM v_shd001_registro_contribuyentes b
          WHERE ((( SELECT b.rif_cedula
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::text) = b.rif_cedula::text) AS deno_profesion, ( SELECT b.cod_ficha
           FROM shd600_solicitud_arrendamiento b
          WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text) AS cod_ficha, ( SELECT c.cod_inscripcion
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND ((( SELECT b.cod_ficha
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::integer) = c.cod_ficha) AS cod_inscripcion, ( SELECT c.fecha_inscripcion
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND ((( SELECT b.cod_ficha
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::integer) = c.cod_ficha) AS fecha_inscripcion_cat, ( SELECT c.cod_control_archivo
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND ((( SELECT b.cod_ficha
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::integer) = c.cod_ficha) AS cod_control_archivo, ( SELECT c.ano_ordenanza
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND ((( SELECT b.cod_ficha
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::integer) = c.cod_ficha) AS ano_ordenanza, ( SELECT c.cod_act_edo
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND ((( SELECT b.cod_ficha
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::integer) = c.cod_ficha) AS cod_act_edo, ( SELECT c.cod_act_mun
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND ((( SELECT b.cod_ficha
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::integer) = c.cod_ficha) AS cod_act_mun, ( SELECT c.cod_act_prr
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND ((( SELECT b.cod_ficha
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::integer) = c.cod_ficha) AS cod_act_prr, ( SELECT c.cod_act_amb_t
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND ((( SELECT b.cod_ficha
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::integer) = c.cod_ficha) AS cod_act_amb_t, ( SELECT c.cod_act_amb
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND ((( SELECT b.cod_ficha
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::integer) = c.cod_ficha) AS cod_act_amb, ( SELECT c.cod_act_sec
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND ((( SELECT b.cod_ficha
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::integer) = c.cod_ficha) AS cod_act_sec, ( SELECT c.cod_act_man
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND ((( SELECT b.cod_ficha
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::integer) = c.cod_ficha) AS cod_act_man, ( SELECT c.cod_act_par
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND ((( SELECT b.cod_ficha
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::integer) = c.cod_ficha) AS cod_act_par, ( SELECT c.cod_act_sbp
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND ((( SELECT b.cod_ficha
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::integer) = c.cod_ficha) AS cod_act_sbp, ( SELECT c.cod_act_niv
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND ((( SELECT b.cod_ficha
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::integer) = c.cod_ficha) AS cod_act_niv, ( SELECT c.cod_act_und
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND ((( SELECT b.cod_ficha
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::integer) = c.cod_ficha) AS cod_act_und, ( SELECT c.lindero_norte
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND ((( SELECT b.cod_ficha
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::integer) = c.cod_ficha) AS lindero_norte, ( SELECT c.lindero_sur
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND ((( SELECT b.cod_ficha
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::integer) = c.cod_ficha) AS lindero_sur, ( SELECT c.lindero_este
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND ((( SELECT b.cod_ficha
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::integer) = c.cod_ficha) AS lindero_este, ( SELECT c.lindero_oeste
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND ((( SELECT b.cod_ficha
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::integer) = c.cod_ficha) AS lindero_oeste, ( SELECT c.valoracion_area
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND ((( SELECT b.cod_ficha
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::integer) = c.cod_ficha) AS valoracion_area, ( SELECT c.valoracion_valor_unitario
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND ((( SELECT b.cod_ficha
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::integer) = c.cod_ficha) AS valoracion_valor_unitario, ( SELECT c.valoracion_sector
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND ((( SELECT b.cod_ficha
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::integer) = c.cod_ficha) AS valoracion_sector, ( SELECT c.valoracion_ajuste_area
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND ((( SELECT b.cod_ficha
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::integer) = c.cod_ficha) AS valoracion_ajuste_area, ( SELECT c.valoracion_ajuste_forma
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND ((( SELECT b.cod_ficha
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::integer) = c.cod_ficha) AS valoracion_ajuste_forma, ( SELECT c.valoracion_valor_ajustado
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND ((( SELECT b.cod_ficha
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::integer) = c.cod_ficha) AS valoracion_valor_ajustado, ( SELECT c.valoracion_valor_total
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND ((( SELECT b.cod_ficha
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::integer) = c.cod_ficha) AS valoracion_valor_total, ( SELECT b.expectativa_construccion
           FROM shd600_solicitud_arrendamiento b
          WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text) AS expectativa_construccion, a.fecha_aprobacion, a.frecuencia_pago, a.datos_registro_arrendamiento, a.monto_mensual, a.pago_todo, a.suspendido, a.rif_ci_cobrador, ( SELECT e.nombre_razon
           FROM shd002_cobradores e
          WHERE a.cod_presi = e.cod_presi AND a.cod_entidad = e.cod_entidad AND a.cod_tipo_inst = e.cod_tipo_inst AND a.cod_inst = e.cod_inst AND a.cod_dep = e.cod_dep AND a.rif_ci_cobrador::text = e.rif_ci::text) AS deno_cobrador, a.ultimo_ano_facturado, a.ultimo_mes_facturado, a.terreno_vendido, ( SELECT b.monto
           FROM shd600_compra_terreno b
          WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text) AS monto, ( SELECT b.fecha_compra
           FROM shd600_compra_terreno b
          WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text) AS fecha_venta
   FROM shd600_aprobacion_arrendamiento a;

ALTER TABLE v_shd600_aprobacion_arrendamiento OWNER TO sisap;

-- View: v_shd600_compra_terreno

-- DROP VIEW v_shd600_compra_terreno;

CREATE OR REPLACE VIEW v_shd600_compra_terreno AS 
 SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.numero_solicitud, ( SELECT b.terreno_vendido
           FROM shd600_aprobacion_arrendamiento b
          WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text) AS terreno_vendido, a.fecha_compra, a.datos_compra, a.monto, ( SELECT b.opcion
           FROM shd600_solicitud_arrendamiento b
          WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text) AS opcion, ( SELECT b.rif_cedula
           FROM shd600_solicitud_arrendamiento b
          WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text) AS rif_cedula, ( SELECT b.razon_social_nombres
           FROM v_shd001_registro_contribuyentes b
          WHERE ((( SELECT b.rif_cedula
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::text) = b.rif_cedula::text) AS nombre_razon, ( SELECT b.cod_pais
           FROM v_shd001_registro_contribuyentes b
          WHERE ((( SELECT b.rif_cedula
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::text) = b.rif_cedula::text) AS cod_pais, ( SELECT b.deno_pais
           FROM v_shd001_registro_contribuyentes b
          WHERE ((( SELECT b.rif_cedula
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::text) = b.rif_cedula::text) AS deno_pais, ( SELECT b.cod_estado
           FROM v_shd001_registro_contribuyentes b
          WHERE ((( SELECT b.rif_cedula
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::text) = b.rif_cedula::text) AS cod_estado, ( SELECT b.deno_estado
           FROM v_shd001_registro_contribuyentes b
          WHERE ((( SELECT b.rif_cedula
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::text) = b.rif_cedula::text) AS deno_estado, ( SELECT b.cod_municipio
           FROM v_shd001_registro_contribuyentes b
          WHERE ((( SELECT b.rif_cedula
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::text) = b.rif_cedula::text) AS cod_municipio, ( SELECT b.deno_municipio
           FROM v_shd001_registro_contribuyentes b
          WHERE ((( SELECT b.rif_cedula
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::text) = b.rif_cedula::text) AS deno_municipio, ( SELECT b.cod_parroquia
           FROM v_shd001_registro_contribuyentes b
          WHERE ((( SELECT b.rif_cedula
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::text) = b.rif_cedula::text) AS cod_parroquia, ( SELECT b.deno_parroquia
           FROM v_shd001_registro_contribuyentes b
          WHERE ((( SELECT b.rif_cedula
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::text) = b.rif_cedula::text) AS deno_parroquia, ( SELECT b.cod_centro_poblado
           FROM v_shd001_registro_contribuyentes b
          WHERE ((( SELECT b.rif_cedula
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::text) = b.rif_cedula::text) AS cod_centro, ( SELECT b.deno_centro
           FROM v_shd001_registro_contribuyentes b
          WHERE ((( SELECT b.rif_cedula
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::text) = b.rif_cedula::text) AS deno_centro, ( SELECT b.cod_calle_avenida
           FROM v_shd001_registro_contribuyentes b
          WHERE ((( SELECT b.rif_cedula
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::text) = b.rif_cedula::text) AS cod_calle, ( SELECT b.deno_vialidad
           FROM v_shd001_registro_contribuyentes b
          WHERE ((( SELECT b.rif_cedula
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::text) = b.rif_cedula::text) AS deno_calle, ( SELECT b.cod_vereda_edificio
           FROM v_shd001_registro_contribuyentes b
          WHERE ((( SELECT b.rif_cedula
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::text) = b.rif_cedula::text) AS cod_vereda_edificio, ( SELECT b.deno_vereda
           FROM v_shd001_registro_contribuyentes b
          WHERE ((( SELECT b.rif_cedula
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::text) = b.rif_cedula::text) AS deno_vereda, ( SELECT b.numero_vivienda_local
           FROM v_shd001_registro_contribuyentes b
          WHERE ((( SELECT b.rif_cedula
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::text) = b.rif_cedula::text) AS numero_casa, ( SELECT b.fecha_inscripcion
           FROM v_shd001_registro_contribuyentes b
          WHERE ((( SELECT b.rif_cedula
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::text) = b.rif_cedula::text) AS fecha_inscripcion_cont, ( SELECT b.telefonos_fijos
           FROM v_shd001_registro_contribuyentes b
          WHERE ((( SELECT b.rif_cedula
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::text) = b.rif_cedula::text) AS telefonos_fijos, ( SELECT b.telefonos_celulares
           FROM v_shd001_registro_contribuyentes b
          WHERE ((( SELECT b.rif_cedula
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::text) = b.rif_cedula::text) AS telefonos_celulares, ( SELECT b.correo_electronico
           FROM v_shd001_registro_contribuyentes b
          WHERE ((( SELECT b.rif_cedula
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::text) = b.rif_cedula::text) AS correo_electronico, ( SELECT b.nacionalidad
           FROM v_shd001_registro_contribuyentes b
          WHERE ((( SELECT b.rif_cedula
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::text) = b.rif_cedula::text) AS nacionalidad, ( SELECT b.estado_civil
           FROM v_shd001_registro_contribuyentes b
          WHERE ((( SELECT b.rif_cedula
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::text) = b.rif_cedula::text) AS estado_civil, ( SELECT b.deno_profesion
           FROM v_shd001_registro_contribuyentes b
          WHERE ((( SELECT b.rif_cedula
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::text) = b.rif_cedula::text) AS deno_profesion, ( SELECT b.cod_ficha
           FROM shd600_solicitud_arrendamiento b
          WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text) AS cod_ficha, ( SELECT c.cod_inscripcion
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND ((( SELECT b.cod_ficha
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::integer) = c.cod_ficha) AS cod_inscripcion, ( SELECT c.fecha_inscripcion
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND ((( SELECT b.cod_ficha
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::integer) = c.cod_ficha) AS fecha_inscripcion_cat, ( SELECT c.cod_control_archivo
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND ((( SELECT b.cod_ficha
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::integer) = c.cod_ficha) AS cod_control_archivo, ( SELECT c.ano_ordenanza
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND ((( SELECT b.cod_ficha
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::integer) = c.cod_ficha) AS ano_ordenanza, ( SELECT c.cod_act_edo
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND ((( SELECT b.cod_ficha
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::integer) = c.cod_ficha) AS cod_act_edo, ( SELECT c.cod_act_mun
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND ((( SELECT b.cod_ficha
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::integer) = c.cod_ficha) AS cod_act_mun, ( SELECT c.cod_act_prr
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND ((( SELECT b.cod_ficha
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::integer) = c.cod_ficha) AS cod_act_prr, ( SELECT c.cod_act_amb_t
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND ((( SELECT b.cod_ficha
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::integer) = c.cod_ficha) AS cod_act_amb_t, ( SELECT c.cod_act_amb
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND ((( SELECT b.cod_ficha
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::integer) = c.cod_ficha) AS cod_act_amb, ( SELECT c.cod_act_sec
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND ((( SELECT b.cod_ficha
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::integer) = c.cod_ficha) AS cod_act_sec, ( SELECT c.cod_act_man
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND ((( SELECT b.cod_ficha
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::integer) = c.cod_ficha) AS cod_act_man, ( SELECT c.cod_act_par
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND ((( SELECT b.cod_ficha
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::integer) = c.cod_ficha) AS cod_act_par, ( SELECT c.cod_act_sbp
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND ((( SELECT b.cod_ficha
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::integer) = c.cod_ficha) AS cod_act_sbp, ( SELECT c.cod_act_niv
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND ((( SELECT b.cod_ficha
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::integer) = c.cod_ficha) AS cod_act_niv, ( SELECT c.cod_act_und
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND ((( SELECT b.cod_ficha
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::integer) = c.cod_ficha) AS cod_act_und, ( SELECT c.lindero_norte
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND ((( SELECT b.cod_ficha
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::integer) = c.cod_ficha) AS lindero_norte, ( SELECT c.lindero_sur
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND ((( SELECT b.cod_ficha
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::integer) = c.cod_ficha) AS lindero_sur, ( SELECT c.lindero_este
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND ((( SELECT b.cod_ficha
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::integer) = c.cod_ficha) AS lindero_este, ( SELECT c.lindero_oeste
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND ((( SELECT b.cod_ficha
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::integer) = c.cod_ficha) AS lindero_oeste, ( SELECT c.valoracion_area
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND ((( SELECT b.cod_ficha
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::integer) = c.cod_ficha) AS valoracion_area, ( SELECT c.valoracion_valor_unitario
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND ((( SELECT b.cod_ficha
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::integer) = c.cod_ficha) AS valoracion_valor_unitario, ( SELECT c.valoracion_sector
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND ((( SELECT b.cod_ficha
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::integer) = c.cod_ficha) AS valoracion_sector, ( SELECT c.valoracion_ajuste_area
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND ((( SELECT b.cod_ficha
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::integer) = c.cod_ficha) AS valoracion_ajuste_area, ( SELECT c.valoracion_ajuste_forma
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND ((( SELECT b.cod_ficha
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::integer) = c.cod_ficha) AS valoracion_ajuste_forma, ( SELECT c.valoracion_valor_ajustado
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND ((( SELECT b.cod_ficha
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::integer) = c.cod_ficha) AS valoracion_valor_ajustado, ( SELECT c.valoracion_valor_total
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND ((( SELECT b.cod_ficha
                   FROM shd600_solicitud_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::integer) = c.cod_ficha) AS valoracion_valor_total, ( SELECT b.expectativa_construccion
           FROM shd600_solicitud_arrendamiento b
          WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text) AS expectativa_construccion, ( SELECT b.datos_registro_arrendamiento
           FROM shd600_aprobacion_arrendamiento b
          WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text) AS datos_registro_arrendamiento, ( SELECT b.rif_ci_cobrador
           FROM shd600_aprobacion_arrendamiento b
          WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text) AS rif_ci_cobrador, ( SELECT e.nombre_razon
           FROM shd002_cobradores e
          WHERE a.cod_presi = e.cod_presi AND a.cod_entidad = e.cod_entidad AND a.cod_tipo_inst = e.cod_tipo_inst AND a.cod_inst = e.cod_inst AND a.cod_dep = e.cod_dep AND ((( SELECT b.rif_ci_cobrador
                   FROM shd600_aprobacion_arrendamiento b
                  WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text))::text) = e.rif_ci::text) AS deno_cobrador, ( SELECT b.frecuencia_pago
           FROM shd600_aprobacion_arrendamiento b
          WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text) AS frecuencia_pago, ( SELECT b.monto_mensual
           FROM shd600_aprobacion_arrendamiento b
          WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text) AS monto_mensual, ( SELECT b.pago_todo
           FROM shd600_aprobacion_arrendamiento b
          WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text) AS pago_todo, ( SELECT b.suspendido
           FROM shd600_aprobacion_arrendamiento b
          WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text) AS suspendido, ( SELECT b.ultimo_ano_facturado
           FROM shd600_aprobacion_arrendamiento b
          WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text) AS ultimo_ano_facturado, ( SELECT b.ultimo_mes_facturado
           FROM shd600_aprobacion_arrendamiento b
          WHERE a.cod_presi = b.cod_presi AND a.cod_entidad = b.cod_entidad AND a.cod_tipo_inst = b.cod_tipo_inst AND a.cod_inst = b.cod_inst AND a.cod_dep = b.cod_dep AND a.numero_solicitud::text = b.numero_solicitud::text) AS ultimo_mes_facturado
   FROM shd600_compra_terreno a;

ALTER TABLE v_shd600_compra_terreno OWNER TO sisap;

-- View: v_shd600_solicitud_arrendamiento

-- DROP VIEW v_shd600_solicitud_arrendamiento;

CREATE OR REPLACE VIEW v_shd600_solicitud_arrendamiento AS 
 SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.numero_solicitud, a.fecha_solicitud, a.rif_cedula, ( SELECT b.razon_social_nombres
           FROM v_shd001_registro_contribuyentes b
          WHERE a.rif_cedula::text = b.rif_cedula::text) AS nombre_razon, ( SELECT b.cod_pais
           FROM v_shd001_registro_contribuyentes b
          WHERE a.rif_cedula::text = b.rif_cedula::text) AS cod_pais, ( SELECT b.deno_pais
           FROM v_shd001_registro_contribuyentes b
          WHERE a.rif_cedula::text = b.rif_cedula::text) AS deno_pais, ( SELECT b.cod_estado
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
          WHERE a.rif_cedula::text = b.rif_cedula::text) AS deno_profesion, a.opcion, a.cod_ficha, ( SELECT c.cod_inscripcion
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND a.cod_ficha::integer = c.cod_ficha) AS cod_inscripcion, ( SELECT c.fecha_inscripcion
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND a.cod_ficha::integer = c.cod_ficha) AS fecha_inscripcion_cat, ( SELECT c.cod_control_archivo
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND a.cod_ficha::integer = c.cod_ficha) AS cod_control_archivo, ( SELECT c.ano_ordenanza
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND a.cod_ficha::integer = c.cod_ficha) AS ano_ordenanza, ( SELECT c.cod_act_edo
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND a.cod_ficha::integer = c.cod_ficha) AS cod_act_edo, ( SELECT c.cod_act_mun
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND a.cod_ficha::integer = c.cod_ficha) AS cod_act_mun, ( SELECT c.cod_act_prr
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND a.cod_ficha::integer = c.cod_ficha) AS cod_act_prr, ( SELECT c.cod_act_amb_t
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND a.cod_ficha::integer = c.cod_ficha) AS cod_act_amb_t, ( SELECT c.cod_act_amb
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND a.cod_ficha::integer = c.cod_ficha) AS cod_act_amb, ( SELECT c.cod_act_sec
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND a.cod_ficha::integer = c.cod_ficha) AS cod_act_sec, ( SELECT c.cod_act_man
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND a.cod_ficha::integer = c.cod_ficha) AS cod_act_man, ( SELECT c.cod_act_par
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND a.cod_ficha::integer = c.cod_ficha) AS cod_act_par, ( SELECT c.cod_act_sbp
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND a.cod_ficha::integer = c.cod_ficha) AS cod_act_sbp, ( SELECT c.cod_act_niv
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND a.cod_ficha::integer = c.cod_ficha) AS cod_act_niv, ( SELECT c.cod_act_und
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND a.cod_ficha::integer = c.cod_ficha) AS cod_act_und, ( SELECT c.lindero_norte
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND a.cod_ficha::integer = c.cod_ficha) AS lindero_norte, ( SELECT c.lindero_sur
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND a.cod_ficha::integer = c.cod_ficha) AS lindero_sur, ( SELECT c.lindero_este
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND a.cod_ficha::integer = c.cod_ficha) AS lindero_este, ( SELECT c.lindero_oeste
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND a.cod_ficha::integer = c.cod_ficha) AS lindero_oeste, ( SELECT c.valoracion_area
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND a.cod_ficha::integer = c.cod_ficha) AS valoracion_area, ( SELECT c.valoracion_valor_unitario
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND a.cod_ficha::integer = c.cod_ficha) AS valoracion_valor_unitario, ( SELECT c.valoracion_sector
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND a.cod_ficha::integer = c.cod_ficha) AS valoracion_sector, ( SELECT c.valoracion_ajuste_area
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND a.cod_ficha::integer = c.cod_ficha) AS valoracion_ajuste_area, ( SELECT c.valoracion_ajuste_forma
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND a.cod_ficha::integer = c.cod_ficha) AS valoracion_ajuste_forma, ( SELECT c.valoracion_valor_ajustado
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND a.cod_ficha::integer = c.cod_ficha) AS valoracion_valor_ajustado, ( SELECT c.valoracion_valor_total
           FROM catd02_ficha_datos c
          WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep AND a.cod_ficha::integer = c.cod_ficha) AS valoracion_valor_total, a.expectativa_construccion
   FROM shd600_solicitud_arrendamiento a;

ALTER TABLE v_shd600_solicitud_arrendamiento OWNER TO sisap;

-- View: v_shd700_credito_vivienda

-- DROP VIEW v_shd700_credito_vivienda;

CREATE OR REPLACE VIEW v_shd700_credito_vivienda AS 
 SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.rif_cedula, ( SELECT b.razon_social_nombres
           FROM v_shd001_registro_contribuyentes b
          WHERE a.rif_cedula::text = b.rif_cedula::text) AS nombre_razon, ( SELECT b.cod_pais
           FROM v_shd001_registro_contribuyentes b
          WHERE a.rif_cedula::text = b.rif_cedula::text) AS cod_pais, ( SELECT b.deno_pais
           FROM v_shd001_registro_contribuyentes b
          WHERE a.rif_cedula::text = b.rif_cedula::text) AS deno_pais, ( SELECT b.cod_estado
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
          WHERE a.rif_cedula::text = b.rif_cedula::text) AS deno_profesion, a.fecha_solicitud, a.nombre_conyugue, a.cedula_conyugue, a.nombre_empresa, a.tiempo_empresa, a.telefonos_empresas, a.direccion_empresa, a.grupo_familiar, a.ingreso_mensual, a.vivienda_actual, a.tipo_vivienda, a.direccion_vivienda_credito, a.costo_vivienda, a.monto_cuota_inicial, a.monto_restante, a.factor, a.plazo_anos, a.numero_cuotas, a.monto_mensual, a.numero_contrato, a.fecha_contrato, a.frecuencia_pago, a.pago_todo, a.suspendido, a.rif_ci_cobrador, ( SELECT d.nombre_razon
           FROM shd002_cobradores d
          WHERE a.cod_presi = d.cod_presi AND a.cod_entidad = d.cod_entidad AND a.cod_tipo_inst = d.cod_tipo_inst AND a.cod_inst = d.cod_inst AND a.cod_dep = d.cod_dep AND a.rif_ci_cobrador::text = d.rif_ci::text) AS deno_cobrador, a.ultimo_ano_facturado, a.ultimo_mes_facturado, a.area_construccion, a.area_terreno, a.norte, a.sur, a.este, a.oeste, a.tasa_interes, a.fecha_entrega_contrato
   FROM shd700_credito_vivienda a;

ALTER TABLE v_shd700_credito_vivienda OWNER TO sisap;

-- View: v_shd700_credito_vivienda_parentesco

-- DROP VIEW v_shd700_credito_vivienda_parentesco;

CREATE OR REPLACE VIEW v_shd700_credito_vivienda_parentesco AS 
 SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.rif_cedula, a.cod_parentesco, ( SELECT b.denominacion
           FROM cnmd06_parentesco b
          WHERE a.cod_parentesco = b.cod_parentesco) AS deno_parentesco, a.nombre_apellido, a.sexo, a.fecha_nacimiento
   FROM shd700_credito_vivienda_parentesco a;

ALTER TABLE v_shd700_credito_vivienda_parentesco OWNER TO sisap;
















