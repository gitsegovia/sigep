-- View: v_restaurar_causados_op

-- DROP VIEW v_restaurar_causados_op;

CREATE OR REPLACE VIEW v_restaurar_causados_op AS

SELECT

a.cod_presi,
a.cod_entidad,
a.cod_tipo_inst,
a.cod_inst,
a.cod_dep,
a.ano_orden_pago,
a.numero_orden_pago,
b.numero_documento_origen,
b.numero_documento_adjunto,
b.cod_tipo_documento,
b.fecha_orden_pago,
b.fecha_documento,
a.ano,
a.cod_sector,
a.cod_programa,
a.cod_sub_prog,
a.cod_proyecto,
a.cod_activ_obra,
a.cod_partida,
a.cod_generica,
a.cod_especifica,
a.cod_sub_espec,
a.cod_auxiliar,
a.monto,
a.numero_control_compromiso,
a.numero_control_causado,
b.concepto,
b.condicion_actividad,
b.numero_anulacion,
b.cod_entidad_bancaria,
b.cod_sucursal,
b.cuenta_bancaria,
b.numero_cheque,
b.fecha_cheque,
b.fecha_proceso_registro,
b.fecha_proceso_anulacion,

( SELECT x.motivo_anulacion FROM cugd03_acta_anulacion_cuerpo x
          WHERE x.cod_presi = a.cod_presi AND
                x.cod_entidad = a.cod_entidad AND
                x.cod_tipo_inst = a.cod_tipo_inst AND
                x.cod_inst = a.cod_inst AND
                x.cod_dep = a.cod_dep AND
                x.ano_documento = a.ano_orden_pago AND
                x.numero_acta_anulacion = b.numero_anulacion) AS concepto_anulacion


FROM cepd03_ordenpago_partidas a, cepd03_ordenpago_cuerpo b

WHERE
a.cod_presi = b.cod_presi AND
a.cod_entidad = b.cod_entidad AND
a.cod_tipo_inst = b.cod_tipo_inst AND
a.cod_inst = b.cod_inst AND
a.cod_dep = b.cod_dep AND
a.ano_orden_pago = b.ano_orden_pago AND
a.numero_orden_pago = b.numero_orden_pago;

ALTER TABLE v_restaurar_causados_op OWNER TO sisap;

