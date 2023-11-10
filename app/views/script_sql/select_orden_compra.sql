-- Function: mascara_3(integer)

-- DROP FUNCTION mascara_3(integer);

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



CREATE OR REPLACE VIEW select_orden_compra AS 
 SELECT 
 a.cod_presi, 
 a.cod_entidad, 
 a.cod_tipo_inst, 
 a.cod_inst, 
 a.cod_dep, 
 a.ano_orden_compra, 
 a.numero_orden_compra, 
 a.rif, 
 a.condicion_actividad, 
 (mascara_2(a.numero_orden_compra) || ' - '::text) || ((( SELECT x.denominacion FROM cpcd02 x
          WHERE x.rif::text = a.rif::text))::text) AS beneficiario,
 ((a.monto_orden+(a.modificacion_aumento-a.modificacion_disminucion))-(a.monto_cancelado+a.monto_amortizacion)) as monto_verificar,
 a.monto_cancelado,
 a.tipo_orden,
 a.entrega_completa
   FROM cscd04_ordencompra_encabezado a;

ALTER TABLE select_orden_compra OWNER TO sisap;




CREATE OR REPLACE VIEW select_autorizacion_pago AS 
 SELECT 
 a.cod_presi, 
 a.cod_entidad, 
 a.cod_tipo_inst, 
 a.cod_inst, 
 a.cod_dep, 
 a.ano_orden_compra, 
 a.numero_orden_compra, 
 a.condicion_actividad,
 (mascara_2(a.numero_orden_compra) || ' - '::text) || ((( SELECT x.denominacion
           FROM cpcd02 x
          WHERE x.rif::text = (select z.rif from cscd04_ordencompra_encabezado z where
          z.cod_presi=a.cod_presi and
          z.cod_entidad=a.cod_entidad and
          z.cod_tipo_inst=a.cod_tipo_inst and
          z.cod_inst=a.cod_inst and
          z.cod_dep=a.cod_dep and 
          z.ano_orden_compra=a.ano_orden_compra and
          z.numero_orden_compra=a.numero_orden_compra
          )))::text) AS beneficiario
   FROM cscd04_ordencompra_autorizacion_pago_cuerpo a;

ALTER TABLE select_autorizacion_pago OWNER TO sisap;


CREATE OR REPLACE VIEW select_anticipo_compra AS 
 SELECT 
 a.cod_presi, 
 a.cod_entidad, 
 a.cod_tipo_inst, 
 a.cod_inst, 
 a.cod_dep, 
 a.ano_orden_compra, 
 a.numero_orden_compra, 
 a.condicion_actividad,
 (mascara_2(a.numero_orden_compra) || ' - '::text) || ((( SELECT x.denominacion
           FROM cpcd02 x
          WHERE x.rif::text = (select z.rif from cscd04_ordencompra_encabezado z where
          z.cod_presi=a.cod_presi and
          z.cod_entidad=a.cod_entidad and
          z.cod_tipo_inst=a.cod_tipo_inst and
          z.cod_inst=a.cod_inst and
          z.cod_dep=a.cod_dep and 
          z.ano_orden_compra=a.ano_orden_compra and
          z.numero_orden_compra=a.numero_orden_compra
          )))::text) AS beneficiario
   FROM cscd04_ordencompra_anticipo_cuerpo a;

ALTER TABLE select_anticipo_compra OWNER TO sisap;


CREATE OR REPLACE VIEW select_modificacion_compra AS 
 SELECT 
 a.cod_presi, 
 a.cod_entidad, 
 a.cod_tipo_inst, 
 a.cod_inst, 
 a.cod_dep, 
 a.ano_orden_compra, 
 a.numero_orden_compra, 
 a.condicion_actividad,
 (mascara_2(a.numero_orden_compra) || ' - '::text) || ((( SELECT x.denominacion
           FROM cpcd02 x
          WHERE x.rif::text = (select z.rif from cscd04_ordencompra_encabezado z where
          z.cod_presi=a.cod_presi and
          z.cod_entidad=a.cod_entidad and
          z.cod_tipo_inst=a.cod_tipo_inst and
          z.cod_inst=a.cod_inst and
          z.cod_dep=a.cod_dep and 
          z.ano_orden_compra=a.ano_orden_compra and
          z.numero_orden_compra=a.numero_orden_compra
          )))::text) AS beneficiario
   FROM cscd04_ordencompra_modificacion_cuerpo a;

ALTER TABLE select_modificacion_compra OWNER TO sisap;


CREATE OR REPLACE VIEW select_nota_entrega AS 
 SELECT 
 a.cod_presi, 
 a.cod_entidad, 
 a.cod_tipo_inst, 
 a.cod_inst, 
 a.cod_dep, 
 a.ano_orden_compra, 
 a.numero_orden_compra, 
 a.rif, 
 (mascara_2(a.numero_orden_compra) || ' - '::text) || ((( SELECT x.denominacion FROM cpcd02 x
          WHERE x.rif::text = a.rif::text))::text) AS beneficiario,
          a.ano_nota_entrega,
          a.numero_nota_entrega
   FROM cscd05_ordencompra_nota_entrega_encabezado a;

ALTER TABLE select_nota_entrega OWNER TO sisap;



