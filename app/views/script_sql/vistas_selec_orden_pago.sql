-- Function: mascara_4(integer)

-- DROP FUNCTION mascara_4(integer);

CREATE OR REPLACE FUNCTION mascara_4(integer)
  RETURNS text AS
$BODY$
DECLARE
t text;
c integer;
BEGIN
c = (SELECT length($1::text));
if  c=4 then
t = '' || $1;
elsif  c=3 then
t = '0' || $1;
elsif  c=2 then
t = '00' || $1;
elsif  c=1 then
t = '000' || $1;
end if;

RETURN t;
END;
$BODY$
  LANGUAGE 'plpgsql' VOLATILE
  COST 100;
ALTER FUNCTION mascara_4(integer) OWNER TO sisap;


CREATE OR REPLACE VIEW v_cepd03_ordenpago_apc AS
 SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.numero_orden_compra, a.ano_orden_compra, a.condicion_actividad, a.numero_orden_pago, x.rif, (mascara_4(a.numero_orden_compra) || ' - '::text) || y.denominacion::text AS deno_select
   FROM cscd04_ordencompra_autorizacion_pago_cuerpo a, cscd04_ordencompra_encabezado x, cpcd02 y
  WHERE x.cod_presi = a.cod_presi AND x.cod_entidad = a.cod_entidad AND x.cod_tipo_inst = a.cod_tipo_inst AND x.cod_inst = a.cod_inst AND x.cod_dep = a.cod_dep AND x.ano_orden_compra = a.ano_orden_compra AND x.numero_orden_compra = a.numero_orden_compra AND y.rif::text = x.rif::text;

ALTER TABLE v_cepd03_ordenpago_apc OWNER TO sisap;

CREATE OR REPLACE VIEW v_cepd03_ordenpago_coac AS
 SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.numero_contrato_obra, a.ano_contrato_obra, a.condicion_actividad, a.numero_orden_pago, a.saldo_ano_anterior, x.rif, (a.numero_contrato_obra::text || ' - '::text) || y.denominacion::text AS deno_select
   FROM cobd01_contratoobras_anticipo_cuerpo a, cobd01_contratoobras_cuerpo x, cpcd02 y
  WHERE x.cod_presi = a.cod_presi AND x.cod_entidad = a.cod_entidad AND x.cod_tipo_inst = a.cod_tipo_inst AND x.cod_inst = a.cod_inst AND x.cod_dep = a.cod_dep AND x.ano_contrato_obra = a.ano_contrato_obra AND x.numero_contrato_obra::text = a.numero_contrato_obra::text AND y.rif::text = x.rif::text;

ALTER TABLE v_cepd03_ordenpago_coac OWNER TO sisap;

CREATE OR REPLACE VIEW v_cepd03_ordenpago_corc AS
 SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.numero_contrato_obra, a.ano_contrato_obra, a.condicion_actividad, a.numero_orden_pago, x.rif, (a.numero_contrato_obra::text || ' - '::text) || y.denominacion::text AS deno_select
   FROM cobd01_contratoobras_retencion_cuerpo a, cobd01_contratoobras_cuerpo x, cpcd02 y
  WHERE x.cod_presi = a.cod_presi AND x.cod_entidad = a.cod_entidad AND x.cod_tipo_inst = a.cod_tipo_inst AND x.cod_inst = a.cod_inst AND x.cod_dep = a.cod_dep AND x.ano_contrato_obra = a.ano_contrato_obra AND x.numero_contrato_obra::text = a.numero_contrato_obra::text AND y.rif::text = x.rif::text;

ALTER TABLE v_cepd03_ordenpago_corc OWNER TO sisap;

CREATE OR REPLACE VIEW v_cepd03_ordenpago_covc AS
 SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.numero_contrato_obra, a.ano_contrato_obra, a.condicion_actividad, a.numero_orden_pago, x.rif, (a.numero_contrato_obra::text || ' - '::text) || y.denominacion::text AS deno_select
   FROM cobd01_contratoobras_valuacion_cuerpo a, cobd01_contratoobras_cuerpo x, cpcd02 y
  WHERE x.cod_presi = a.cod_presi AND x.cod_entidad = a.cod_entidad AND x.cod_tipo_inst = a.cod_tipo_inst AND x.cod_inst = a.cod_inst AND x.cod_dep = a.cod_dep AND x.ano_contrato_obra = a.ano_contrato_obra AND x.numero_contrato_obra::text = a.numero_contrato_obra::text AND y.rif::text = x.rif::text;

ALTER TABLE v_cepd03_ordenpago_covc OWNER TO sisap;

CREATE OR REPLACE VIEW v_cepd03_ordenpago_csac AS
 SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.numero_contrato_servicio, a.ano_contrato_servicio, a.condicion_actividad, a.numero_orden_pago, a.saldo_ano_anterior, x.rif, (a.numero_contrato_servicio::text || ' - '::text) || y.denominacion::text AS deno_select
   FROM cepd02_contratoservicio_anticipo_cuerpo a, cepd02_contratoservicio_cuerpo x, cpcd02 y
  WHERE x.cod_presi = a.cod_presi AND x.cod_entidad = a.cod_entidad AND x.cod_tipo_inst = a.cod_tipo_inst AND x.cod_inst = a.cod_inst AND x.cod_dep = a.cod_dep AND x.ano_contrato_servicio = a.ano_contrato_servicio AND x.numero_contrato_servicio::text = a.numero_contrato_servicio::text AND y.rif::text = x.rif::text;

ALTER TABLE v_cepd03_ordenpago_csac OWNER TO sisap;

CREATE OR REPLACE VIEW v_cepd03_ordenpago_csrc AS
 SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.numero_contrato_servicio, a.ano_contrato_servicio, a.condicion_actividad, a.numero_orden_pago, x.rif, (a.numero_contrato_servicio::text || ' - '::text) || y.denominacion::text AS deno_select
   FROM cepd02_contratoservicio_retencion_cuerpo a, cepd02_contratoservicio_cuerpo x, cpcd02 y
  WHERE x.cod_presi = a.cod_presi AND x.cod_entidad = a.cod_entidad AND x.cod_tipo_inst = a.cod_tipo_inst AND x.cod_inst = a.cod_inst AND x.cod_dep = a.cod_dep AND x.ano_contrato_servicio = a.ano_contrato_servicio AND x.numero_contrato_servicio::text = a.numero_contrato_servicio::text AND y.rif::text = x.rif::text;

ALTER TABLE v_cepd03_ordenpago_csrc OWNER TO sisap;

CREATE OR REPLACE VIEW v_cepd03_ordenpago_csvc AS
 SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.numero_contrato_servicio, a.ano_contrato_servicio, a.condicion_actividad, a.numero_orden_pago, x.rif, (a.numero_contrato_servicio::text || ' - '::text) || y.denominacion::text AS deno_select
   FROM cepd02_contratoservicio_valuacion_cuerpo a, cepd02_contratoservicio_cuerpo x, cpcd02 y
  WHERE x.cod_presi = a.cod_presi AND x.cod_entidad = a.cod_entidad AND x.cod_tipo_inst = a.cod_tipo_inst AND x.cod_inst = a.cod_inst AND x.cod_dep = a.cod_dep AND x.ano_contrato_servicio = a.ano_contrato_servicio AND x.numero_contrato_servicio::text = a.numero_contrato_servicio::text AND y.rif::text = x.rif::text;

ALTER TABLE v_cepd03_ordenpago_csvc OWNER TO sisap;

CREATE OR REPLACE VIEW v_cepd03_ordenpago_oca AS
 SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.numero_orden_compra, a.ano_orden_compra, a.condicion_actividad, a.numero_orden_pago, a.saldo_ano_anterior, x.rif, (mascara_4(a.numero_orden_compra) || ' - '::text) || y.denominacion::text AS deno_select
   FROM cscd04_ordencompra_anticipo_cuerpo a, cscd04_ordencompra_encabezado x, cpcd02 y
  WHERE x.cod_presi = a.cod_presi AND x.cod_entidad = a.cod_entidad AND x.cod_tipo_inst = a.cod_tipo_inst AND x.cod_inst = a.cod_inst AND x.cod_dep = a.cod_dep AND x.ano_orden_compra = a.ano_orden_compra AND x.numero_orden_compra = a.numero_orden_compra AND y.rif::text = x.rif::text;

ALTER TABLE v_cepd03_ordenpago_oca OWNER TO sisap;
