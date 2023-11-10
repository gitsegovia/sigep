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
-- Name: modulos; Type: TABLE; Schema: public; Owner: sisap; Tablespace:
--
DROP TABLE modulos;
CREATE TABLE modulos (
    cod_tipo_inst integer NOT NULL,
    cod_inst integer NOT NULL,
    cod_dep integer NOT NULL,
    cod_modulo text NOT NULL,
    denominacion text NOT NULL,
    status integer DEFAULT 1 NOT NULL,
    orden_ubicacion integer
);


ALTER TABLE public.modulos OWNER TO sisap;

--
-- Name: TABLE modulos; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON TABLE modulos IS 'Registra los módulos que componen al sistema';


--
-- Name: COLUMN modulos.status; Type: COMMENT; Schema: public; Owner: sisap
--

COMMENT ON COLUMN modulos.status IS '1.- Activar Modulo
2.- Desactivar Modulo';

COMMENT ON COLUMN modulos.status IS 'Activa o desactiva módulos
1.- Activado
2.- Desactivado
';
COMMENT ON COLUMN modulos.orden_ubicacion IS 'Ordena iconos
';
COMMENT ON COLUMN modulos.cod_inst IS 'Código Institución';
COMMENT ON COLUMN modulos.cod_dep IS 'Código de dependencia

=0 Cuando dependencia es es cero la desactivacion es para toda la institucion

codigo dependencia= Cuando el campo contiene el valor de codigo de dependencia la desactivación es para la depedencia marcada';


--
-- Data for Name: modulos; Type: TABLE DATA; Schema: public; Owner: sisap
--

COPY modulos (cod_tipo_inst, cod_inst,cod_dep,cod_modulo, denominacion, status, orden_ubicacion) FROM stdin;
30	11	1	CONP00	CONSULTAS GERENCIALES	2	18
30	11	1	CGPP00	CONTABILIDAD GENERAL	1	12
30	11	1	CFPP00	CONTABILIDAD FISCAL	1	11
30	11	1	CIPP00	BIENES MUEBLES E INMUEBLES	1	10
30	11	1	CNP000	CONTROL DE PERSONAL Y NÓMINAS DE PAGOS	1	9
30	11	1	CSTP00	TESORERÍA	1	8
30	11	1	CEPP00	ORDENACIÓN DE PAGOS	1	7
30	11	1	CEP000	CONTROL DE CONTRATOS DE SERVICIO	1	6
30	11	1	COBP00	CONTROL DE CONTRATOS DE OBRAS	1	5
30	11	1	CSCP00	CONTROL DE COMPRAS	1	4
30	11	1	CSRP00	SOLICITUD Y ASIGNACION DE RECURSOS	1	3
30	11	1	CSIP00	REGISTRO Y CONTROL DE PROVEEDORES Y CONTRATISTA	1	2
30	11	1	CATP00	CATASTRO	2	13
30	11	1	SHPP00	HACIENDA	2	14
30	11	1	CFP000	FORMULACIÓN, CONTROL Y EVALUACIÓN PRESUPUESTARIA	1	1
30	11	1	CUGP00	SISTEMA DE USO GENERAL	1	0
30	11	1	CAP000	ATENCIÓN E INFORMACIÓN AL PÚBLICO	1	15
30	11	1	CATSP0	ATENCIÓN SOCIAL	1	16
30	11	1	CMCP00	MEMORIA Y CUENTA	1	17
\.


--
-- Name: clave_primaria; Type: CONSTRAINT; Schema: public; Owner: sisap; Tablespace:
--

ALTER TABLE ONLY modulos
    ADD CONSTRAINT clave_primaria PRIMARY KEY (cod_tipo_inst,cod_inst,cod_dep,cod_modulo);


--
-- PostgreSQL database dump complete
--




COPY modulos (cod_tipo_inst, cod_inst,cod_dep,cod_modulo, denominacion, status, orden_ubicacion) FROM stdin;
50	11	1	CONP00	CONSULTAS GERENCIALES	2	18
50	11	1	CGPP00	CONTABILIDAD GENERAL	1	12
50	11	1	CFPP00	CONTABILIDAD FISCAL	1	11
50	11	1	CIPP00	BIENES MUEBLES E INMUEBLES	1	10
50	11	1	CNP000	CONTROL DE PERSONAL Y NÓMINAS DE PAGOS	1	9
50	11	1	CSTP00	TESORERÍA	1	8
50	11	1	CEPP00	ORDENACIÓN DE PAGOS	1	7
50	11	1	CEP000	CONTROL DE CONTRATOS DE SERVICIO	1	6
50	11	1	COBP00	CONTROL DE CONTRATOS DE OBRAS	1	5
50	11	1	CSCP00	CONTROL DE COMPRAS	1	4
50	11	1	CSRP00	SOLICITUD Y ASIGNACION DE RECURSOS	1	3
50	11	1	CSIP00	REGISTRO Y CONTROL DE PROVEEDORES Y CONTRATISTA	1	2
50	11	1	CATP00	CATASTRO	2	13
50	11	1	SHPP00	HACIENDA	2	14
50	11	1	CFP000	FORMULACIÓN, CONTROL Y EVALUACIÓN PRESUPUESTARIA	1	1
50	11	1	CUGP00	SISTEMA DE USO GENERAL	1	0
50	11	1	CAP000	ATENCIÓN E INFORMACIÓN AL PÚBLICO	1	15
50	11	1	CATSP0	ATENCIÓN SOCIAL	1	16
50	11	1	CMCP00	MEMORIA Y CUENTA	1	17
\.
