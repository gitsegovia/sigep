--cheques
select * from cstd03_cheque_cuerpo where ano_movimiento=2008 and fecha_cheque BETWEEN '2009-01-01' AND '2009-12-31'
select * from cstd03_cheque_cuerpo where ano_movimiento=2008 and fecha_proceso_registro BETWEEN '2009-01-01' AND '2009-12-31'
select * from cstd03_cheque_cuerpo where ano_movimiento=2008 and fecha_proceso_anulacion BETWEEN '2009-01-01' AND '2009-12-31'

update cstd03_cheque_cuerpo set fecha_cheque='2008-12-31' WHERE ano_movimiento=2008 and fecha_cheque BETWEEN '2009-01-01' AND '2009-12-31';
update cstd03_cheque_cuerpo set fecha_proceso_registro='2008-12-31' WHERE ano_movimiento=2008 and fecha_proceso_registro BETWEEN '2009-01-01' AND '2009-12-31';
update cstd03_cheque_cuerpo set fecha_proceso_anulacion='2008-12-31' WHERE ano_movimiento=2008 and fecha_proceso_anulacion BETWEEN '2009-01-01' AND '2009-12-31';



--ordenes de pago
select * from cepd03_ordenpago_cuerpo where ano_orden_pago=2008 and fecha_orden_pago BETWEEN '2009-01-01' AND '2009-12-31'
select * from cepd03_ordenpago_cuerpo where ano_orden_pago=2008 and fecha_proceso_registro BETWEEN '2009-01-01' AND '2009-12-31'
select * from cepd03_ordenpago_cuerpo where ano_orden_pago=2008 and fecha_proceso_anulacion BETWEEN '2009-01-01' AND '2009-12-31'

update cepd03_ordenpago_cuerpo set fecha_orden_pago='2008-12-31' WHERE ano_orden_pago=2008 and fecha_orden_pago BETWEEN '2009-01-01' AND '2009-12-31';
update cepd03_ordenpago_cuerpo set fecha_proceso_registro='2008-12-31' WHERE ano_orden_pago=2008 and fecha_proceso_registro BETWEEN '2009-01-01' AND '2009-12-31';
update cepd03_ordenpago_cuerpo set fecha_proceso_anulacion='2008-12-31' WHERE ano_orden_pago=2008 and fecha_proceso_anulacion BETWEEN '2009-01-01' AND '2009-12-31';

-- registro de compromisos
select * from cepd01_compromiso_cuerpo where ano_documento=2008 and fecha_documento BETWEEN '2009-01-01' AND '2009-12-31'
select * from cepd01_compromiso_cuerpo where ano_documento=2008 and fecha_proceso_registro BETWEEN '2009-01-01' AND '2009-12-31'
select * from cepd01_compromiso_cuerpo where ano_documento=2008 and fecha_proceso_anulacion BETWEEN '2009-01-01' AND '2009-12-31'

update cepd01_compromiso_cuerpo set fecha_documento='2008-12-31' WHERE ano_documento=2008 and fecha_documento BETWEEN '2009-01-01' AND '2009-12-31';
update cepd01_compromiso_cuerpo set fecha_proceso_registro='2008-12-31' WHERE ano_documento=2008 and fecha_proceso_registro BETWEEN '2009-01-01' AND '2009-12-31';
update cepd01_compromiso_cuerpo set fecha_proceso_anulacion='2008-12-31' WHERE ano_documento=2008 and fecha_proceso_anulacion BETWEEN '2009-01-01' AND '2009-12-31';

-- contratos de servicios
select * from cepd02_contratoservicio_cuerpo where ano_contrato_servicio=2008 and fecha_contrato_servicio BETWEEN '2009-01-01' AND '2009-12-31'
select * from cepd02_contratoservicio_cuerpo where ano_contrato_servicio=2008 and fecha_proceso_registro BETWEEN '2009-01-01' AND '2009-12-31'
select * from cepd02_contratoservicio_cuerpo where ano_contrato_servicio=2008 and fecha_proceso_anulacion BETWEEN '2009-01-01' AND '2009-12-31'

update cepd02_contratoservicio_cuerpo set fecha_contrato_servicio='2008-12-31' WHERE ano_contrato_servicio=2008 and fecha_contrato_servicio BETWEEN '2009-01-01' AND '2009-12-31';
update cepd02_contratoservicio_cuerpo set fecha_proceso_registro='2008-12-31' WHERE ano_contrato_servicio=2008 and fecha_proceso_registro BETWEEN '2009-01-01' AND '2009-12-31';
update cepd02_contratoservicio_cuerpo set fecha_proceso_anulacion='2008-12-31' WHERE ano_contrato_servicio=2008 and fecha_proceso_anulacion BETWEEN '2009-01-01' AND '2009-12-31';

-- contratos de servicios - anticipos
select * from cepd02_contratoservicio_anticipo_cuerpo where ano_contrato_servicio=2008 and fecha_anticipo BETWEEN '2009-01-01' AND '2009-12-31'
select * from cepd02_contratoservicio_anticipo_cuerpo where ano_contrato_servicio=2008 and fecha_proceso_registro BETWEEN '2009-01-01' AND '2009-12-31'
select * from cepd02_contratoservicio_anticipo_cuerpo where ano_contrato_servicio=2008 and fecha_proceso_anulacion BETWEEN '2009-01-01' AND '2009-12-31'

update cepd02_contratoservicio_anticipo_cuerpo set fecha_anticipo='2008-12-31' WHERE ano_contrato_servicio=2008 and fecha_anticipo BETWEEN '2009-01-01' AND '2009-12-31';
update cepd02_contratoservicio_anticipo_cuerpo set fecha_proceso_registro='2008-12-31' WHERE ano_contrato_servicio=2008 and fecha_proceso_registro BETWEEN '2009-01-01' AND '2009-12-31';
update cepd02_contratoservicio_anticipo_cuerpo set fecha_proceso_anulacion='2008-12-31' WHERE ano_contrato_servicio=2008 and fecha_proceso_anulacion BETWEEN '2009-01-01' AND '2009-12-31';


-- contratos de servicios - modificaciones
select * from cepd02_contratoservicio_modificacion_cuerpo where ano_contrato_servicio=2008 and fecha_modificacion BETWEEN '2009-01-01' AND '2009-12-31'
select * from cepd02_contratoservicio_modificacion_cuerpo where ano_contrato_servicio=2008 and fecha_proceso_registro BETWEEN '2009-01-01' AND '2009-12-31'
select * from cepd02_contratoservicio_modificacion_cuerpo where ano_contrato_servicio=2008 and fecha_proceso_anulacion BETWEEN '2009-01-01' AND '2009-12-31'

update cepd02_contratoservicio_modificacion_cuerpo set fecha_modificacion='2008-12-31' WHERE ano_contrato_servicio=2008 and fecha_modificacion BETWEEN '2009-01-01' AND '2009-12-31';
update cepd02_contratoservicio_modificacion_cuerpo set fecha_proceso_registro='2008-12-31' WHERE ano_contrato_servicio=2008 and fecha_proceso_registro BETWEEN '2009-01-01' AND '2009-12-31';
update cepd02_contratoservicio_modificacion_cuerpo set fecha_proceso_anulacion='2008-12-31' WHERE ano_contrato_servicio=2008 and fecha_proceso_anulacion BETWEEN '2009-01-01' AND '2009-12-31';

-- contratos de servicios - valucacion
select * from cepd02_contratoservicio_valuacion_cuerpo where ano_contrato_servicio=2008 and fecha_valuacion BETWEEN '2009-01-01' AND '2009-12-31'
select * from cepd02_contratoservicio_valuacion_cuerpo where ano_contrato_servicio=2008 and fecha_proceso_registro BETWEEN '2009-01-01' AND '2009-12-31'
select * from cepd02_contratoservicio_valuacion_cuerpo where ano_contrato_servicio=2008 and fecha_proceso_anulacion BETWEEN '2009-01-01' AND '2009-12-31'

update cepd02_contratoservicio_valuacion_cuerpo set fecha_valuacion='2008-12-31' WHERE ano_contrato_servicio=2008 and fecha_valuacion BETWEEN '2009-01-01' AND '2009-12-31';
update cepd02_contratoservicio_valuacion_cuerpo set fecha_proceso_registro='2008-12-31' WHERE ano_contrato_servicio=2008 and fecha_proceso_registro BETWEEN '2009-01-01' AND '2009-12-31';
update cepd02_contratoservicio_valuacion_cuerpo set fecha_proceso_anulacion='2008-12-31' WHERE ano_contrato_servicio=2008 and fecha_proceso_anulacion BETWEEN '2009-01-01' AND '2009-12-31';

-- contratos de servicios - retencion
select * from cepd02_contratoservicio_retencion_cuerpo where ano_contrato_servicio=2008 and fecha_retencion BETWEEN '2009-01-01' AND '2009-12-31'
select * from cepd02_contratoservicio_retencion_cuerpo where ano_contrato_servicio=2008 and fecha_proceso_registro BETWEEN '2009-01-01' AND '2009-12-31'
select * from cepd02_contratoservicio_retencion_cuerpo where ano_contrato_servicio=2008 and fecha_proceso_anulacion BETWEEN '2009-01-01' AND '2009-12-31'

update cepd02_contratoservicio_retencion_cuerpo set fecha_retencion='2008-12-31' WHERE ano_contrato_servicio=2008 and fecha_retencion BETWEEN '2009-01-01' AND '2009-12-31';
update cepd02_contratoservicio_retencion_cuerpo set fecha_proceso_registro='2008-12-31' WHERE ano_contrato_servicio=2008 and fecha_proceso_registro BETWEEN '2009-01-01' AND '2009-12-31';
update cepd02_contratoservicio_retencion_cuerpo set fecha_proceso_anulacion='2008-12-31' WHERE ano_contrato_servicio=2008 and fecha_proceso_anulacion BETWEEN '2009-01-01' AND '2009-12-31';



#################################3


-- contratos de obras
select * from cobd01_contratoobras_cuerpo where ano_contrato_obra=2008 and fecha_contrato_obra BETWEEN '2009-01-01' AND '2009-12-31'
select * from cobd01_contratoobras_cuerpo where ano_contrato_obra=2008 and fecha_proceso_registro BETWEEN '2009-01-01' AND '2009-12-31'
select * from cobd01_contratoobras_cuerpo where ano_contrato_obra=2008 and fecha_proceso_anulacion BETWEEN '2009-01-01' AND '2009-12-31'

update cobd01_contratoobras_cuerpo set fecha_contrato_obra='2008-12-31' WHERE ano_contrato_obra=2008 and fecha_contrato_obra BETWEEN '2009-01-01' AND '2009-12-31';
update cobd01_contratoobras_cuerpo set fecha_proceso_registro='2008-12-31' WHERE ano_contrato_obra=2008 and fecha_proceso_registro BETWEEN '2009-01-01' AND '2009-12-31';
update cobd01_contratoobras_cuerpo set fecha_proceso_anulacion='2008-12-31' WHERE ano_contrato_obra=2008 and fecha_proceso_anulacion BETWEEN '2009-01-01' AND '2009-12-31';

-- contratos de obras - anticipos
select * from cobd01_contratoobras_anticipo_cuerpo where ano_contrato_obra=2008 and fecha_anticipo BETWEEN '2009-01-01' AND '2009-12-31'
select * from cobd01_contratoobras_anticipo_cuerpo where ano_contrato_obra=2008 and fecha_proceso_registro BETWEEN '2009-01-01' AND '2009-12-31'
select * from cobd01_contratoobras_anticipo_cuerpo where ano_contrato_obra=2008 and fecha_proceso_anulacion BETWEEN '2009-01-01' AND '2009-12-31'

update cobd01_contratoobras_anticipo_cuerpo set fecha_anticipo='2008-12-31' WHERE ano_contrato_obra=2008 and fecha_anticipo BETWEEN '2009-01-01' AND '2009-12-31';
update cobd01_contratoobras_anticipo_cuerpo set fecha_proceso_registro='2008-12-31' WHERE ano_contrato_obra=2008 and fecha_proceso_registro BETWEEN '2009-01-01' AND '2009-12-31';
update cobd01_contratoobras_anticipo_cuerpo set fecha_proceso_anulacion='2008-12-31' WHERE ano_contrato_obra=2008 and fecha_proceso_anulacion BETWEEN '2009-01-01' AND '2009-12-31';


-- contratos de obras - modificaciones
select * from cobd01_contratoobras_modificacion_cuerpo where ano_contrato_obra=2008 and fecha_modificacion BETWEEN '2009-01-01' AND '2009-12-31'
select * from cobd01_contratoobras_modificacion_cuerpo where ano_contrato_obra=2008 and fecha_proceso_registro BETWEEN '2009-01-01' AND '2009-12-31'
select * from cobd01_contratoobras_modificacion_cuerpo where ano_contrato_obra=2008 and fecha_proceso_anulacion BETWEEN '2009-01-01' AND '2009-12-31'

update cobd01_contratoobras_modificacion_cuerpo set fecha_modificacion='2008-12-31' WHERE ano_contrato_obra=2008 and fecha_modificacion BETWEEN '2009-01-01' AND '2009-12-31';
update cobd01_contratoobras_modificacion_cuerpo set fecha_proceso_registro='2008-12-31' WHERE ano_contrato_obra=2008 and fecha_proceso_registro BETWEEN '2009-01-01' AND '2009-12-31';
update cobd01_contratoobras_modificacion_cuerpo set fecha_proceso_anulacion='2008-12-31' WHERE ano_contrato_obra=2008 and fecha_proceso_anulacion BETWEEN '2009-01-01' AND '2009-12-31';

-- contratos de obras - valucacion
select * from cobd01_contratoobras_valuacion_cuerpo where ano_contrato_obra=2008 and fecha_valuacion BETWEEN '2009-01-01' AND '2009-12-31'
select * from cobd01_contratoobras_valuacion_cuerpo where ano_contrato_obra=2008 and fecha_proceso_registro BETWEEN '2009-01-01' AND '2009-12-31'
select * from cobd01_contratoobras_valuacion_cuerpo where ano_contrato_obra=2008 and fecha_proceso_anulacion BETWEEN '2009-01-01' AND '2009-12-31'

update cobd01_contratoobras_valuacion_cuerpo set fecha_valuacion='2008-12-31' WHERE ano_contrato_obra=2008 and fecha_valuacion BETWEEN '2009-01-01' AND '2009-12-31';
update cobd01_contratoobras_valuacion_cuerpo set fecha_proceso_registro='2008-12-31' WHERE ano_contrato_obra=2008 and fecha_proceso_registro BETWEEN '2009-01-01' AND '2009-12-31';
update cobd01_contratoobras_valuacion_cuerpo set fecha_proceso_anulacion='2008-12-31' WHERE ano_contrato_obra=2008 and fecha_proceso_anulacion BETWEEN '2009-01-01' AND '2009-12-31';

-- contratos de obras - retencion
select * from cobd01_contratoobras_retencion_cuerpo where ano_contrato_obra=2008 and fecha_retencion BETWEEN '2009-01-01' AND '2009-12-31'
select * from cobd01_contratoobras_retencion_cuerpo where ano_contrato_obra=2008 and fecha_proceso_registro BETWEEN '2009-01-01' AND '2009-12-31'
select * from cobd01_contratoobras_retencion_cuerpo where ano_contrato_obra=2008 and fecha_proceso_anulacion BETWEEN '2009-01-01' AND '2009-12-31'

update cobd01_contratoobras_retencion_cuerpo set fecha_retencion='2008-12-31' WHERE ano_contrato_obra=2008 and fecha_retencion BETWEEN '2009-01-01' AND '2009-12-31';
update cobd01_contratoobras_retencion_cuerpo set fecha_proceso_registro='2008-12-31' WHERE ano_contrato_obra=2008 and fecha_proceso_registro BETWEEN '2009-01-01' AND '2009-12-31';
update cobd01_contratoobras_retencion_cuerpo set fecha_proceso_anulacion='2008-12-31' WHERE ano_contrato_obra=2008 and fecha_proceso_anulacion BETWEEN '2009-01-01' AND '2009-12-31';


-- solicitud
select * from cscd02_solicitud_encabezado where ano_solicitud=2008 and fecha_solicitud BETWEEN '2009-01-01' AND '2009-12-31'
select * from cscd02_solicitud_encabezado where ano_solicitud=2008 and fecha_proceso BETWEEN '2009-01-01' AND '2009-12-31'

update cscd02_solicitud_encabezado set fecha_solicitud='2008-12-31' WHERE ano_solicitud=2008 and fecha_solicitud BETWEEN '2009-01-01' AND '2009-12-31';
update cscd02_solicitud_encabezado set fecha_proceso='2008-12-31' WHERE ano_solicitud=2008 and fecha_proceso BETWEEN '2009-01-01' AND '2009-12-31';

-- cotizacion
select * from cscd03_cotizacion_encabezado where ano_cotizacion=2008 and fecha_cotizacion BETWEEN '2009-01-01' AND '2009-12-31'
select * from cscd03_cotizacion_encabezado where ano_cotizacion=2008 and fecha_proceso BETWEEN '2009-01-01' AND '2009-12-31'

update cscd03_cotizacion_encabezado set fecha_cotizacion='2008-12-31' WHERE ano_cotizacion=2008 and fecha_cotizacion BETWEEN '2009-01-01' AND '2009-12-31';
update cscd03_cotizacion_encabezado set fecha_proceso='2008-12-31' WHERE ano_cotizacion=2008 and fecha_proceso BETWEEN '2009-01-01' AND '2009-12-31';

-- solicitud - anulados
select * from cscd02_solicitud_encabezado_anulado where ano_solicitud=2008 and fecha_solicitud BETWEEN '2009-01-01' AND '2009-12-31'
select * from cscd02_solicitud_encabezado_anulado where ano_solicitud=2008 and fecha_proceso BETWEEN '2009-01-01' AND '2009-12-31'

update cscd02_solicitud_encabezado_anulado set fecha_solicitud='2008-12-31' WHERE ano_solicitud=2008 and fecha_solicitud BETWEEN '2009-01-01' AND '2009-12-31';
update cscd02_solicitud_encabezado_anulado set fecha_proceso='2008-12-31' WHERE ano_solicitud=2008 and fecha_proceso BETWEEN '2009-01-01' AND '2009-12-31';


-- cotizacion - anulados
select * from cscd03_cotizacion_encabezado_anulado where ano_cotizacion=2008 and fecha_cotizacion BETWEEN '2009-01-01' AND '2009-12-31'
select * from cscd03_cotizacion_encabezado_anulado where ano_cotizacion=2008 and fecha_proceso BETWEEN '2009-01-01' AND '2009-12-31'

update cscd03_cotizacion_encabezado_anulado set fecha_cotizacion='2008-12-31' WHERE ano_cotizacion=2008 and fecha_cotizacion BETWEEN '2009-01-01' AND '2009-12-31';
update cscd03_cotizacion_encabezado_anulado set fecha_proceso='2008-12-31' WHERE ano_cotizacion=2008 and fecha_proceso BETWEEN '2009-01-01' AND '2009-12-31';



-- ordenes de compra
select * from cscd04_ordencompra_encabezado where ano_orden_compra=2008 and fecha_orden_compra BETWEEN '2009-01-01' AND '2009-12-31'
select * from cscd04_ordencompra_encabezado where ano_orden_compra=2008 and fecha_proceso_registro BETWEEN '2009-01-01' AND '2009-12-31'
select * from cscd04_ordencompra_encabezado where ano_orden_compra=2008 and fecha_proceso_anulacion BETWEEN '2009-01-01' AND '2009-12-31'

update cscd04_ordencompra_encabezado set fecha_orden_compra='2008-12-31' WHERE ano_orden_compra=2008 and fecha_orden_compra BETWEEN '2009-01-01' AND '2009-12-31';
update cscd04_ordencompra_encabezado set fecha_proceso_registro='2008-12-31' WHERE ano_orden_compra=2008 and fecha_proceso_registro BETWEEN '2009-01-01' AND '2009-12-31';
update cscd04_ordencompra_encabezado set fecha_proceso_anulacion='2008-12-31' WHERE ano_orden_compra=2008 and fecha_proceso_anulacion BETWEEN '2009-01-01' AND '2009-12-31';


-- ordenes de compra modificaciones
select * from cscd04_ordencompra_modificacion_cuerpo where ano_orden_compra=2008 and fecha_modificacion BETWEEN '2009-01-01' AND '2009-12-31'
select * from cscd04_ordencompra_modificacion_cuerpo where ano_orden_compra=2008 and fecha_proceso_registro BETWEEN '2009-01-01' AND '2009-12-31'
select * from cscd04_ordencompra_modificacion_cuerpo where ano_orden_compra=2008 and fecha_proceso_anulacion BETWEEN '2009-01-01' AND '2009-12-31'

update cscd04_ordencompra_modificacion_cuerpo set fecha_modificacion='2008-12-31' WHERE ano_orden_compra=2008 and fecha_modificacion BETWEEN '2009-01-01' AND '2009-12-31';
update cscd04_ordencompra_modificacion_cuerpo set fecha_proceso_registro='2008-12-31' WHERE ano_orden_compra=2008 and fecha_proceso_registro BETWEEN '2009-01-01' AND '2009-12-31';
update cscd04_ordencompra_modificacion_cuerpo set fecha_proceso_anulacion='2008-12-31' WHERE ano_orden_compra=2008 and fecha_proceso_anulacion BETWEEN '2009-01-01' AND '2009-12-31';


-- ordenes de compra anticipos
select * from cscd04_ordencompra_anticipo_cuerpo where ano_orden_compra=2008 and fecha_anticipo BETWEEN '2009-01-01' AND '2009-12-31'
select * from cscd04_ordencompra_anticipo_cuerpo where ano_orden_compra=2008 and fecha_proceso_registro BETWEEN '2009-01-01' AND '2009-12-31'
select * from cscd04_ordencompra_anticipo_cuerpo where ano_orden_compra=2008 and fecha_proceso_anulacion BETWEEN '2009-01-01' AND '2009-12-31'

update cscd04_ordencompra_anticipo_cuerpo set fecha_anticipo='2008-12-31' WHERE ano_orden_compra=2008 and fecha_anticipo BETWEEN '2009-01-01' AND '2009-12-31';
update cscd04_ordencompra_anticipo_cuerpo set fecha_proceso_registro='2008-12-31' WHERE ano_orden_compra=2008 and fecha_proceso_registro BETWEEN '2009-01-01' AND '2009-12-31';
update cscd04_ordencompra_anticipo_cuerpo set fecha_proceso_anulacion='2008-12-31' WHERE ano_orden_compra=2008 and fecha_proceso_anulacion BETWEEN '2009-01-01' AND '2009-12-31';


-- ordenes de compra autorizacion
select * from cscd04_ordencompra_autorizacion_pago_cuerpo where ano_orden_compra=2008 and fecha_autorizacion BETWEEN '2009-01-01' AND '2009-12-31'
select * from cscd04_ordencompra_autorizacion_pago_cuerpo where ano_orden_compra=2008 and fecha_proceso_registro BETWEEN '2009-01-01' AND '2009-12-31'
select * from cscd04_ordencompra_autorizacion_pago_cuerpo where ano_orden_compra=2008 and fecha_proceso_anulacion BETWEEN '2009-01-01' AND '2009-12-31'

update cscd04_ordencompra_autorizacion_pago_cuerpo set fecha_autorizacion='2008-12-31' WHERE ano_orden_compra=2008 and fecha_autorizacion BETWEEN '2009-01-01' AND '2009-12-31';
update cscd04_ordencompra_autorizacion_pago_cuerpo set fecha_proceso_registro='2008-12-31' WHERE ano_orden_compra=2008 and fecha_proceso_registro BETWEEN '2009-01-01' AND '2009-12-31';
update cscd04_ordencompra_autorizacion_pago_cuerpo set fecha_proceso_anulacion='2008-12-31' WHERE ano_orden_compra=2008 and fecha_proceso_anulacion BETWEEN '2009-01-01' AND '2009-12-31';


-- ordenes de compra nota entrega
select * from cscd05_ordencompra_nota_entrega_encabezado where ano_nota_entrega=2008 and fecha_nota_entrega BETWEEN '2009-01-01' AND '2009-12-31'

update cscd05_ordencompra_nota_entrega_encabezado set fecha_nota_entrega='2008-12-31' WHERE ano_nota_entrega=2008 and fecha_nota_entrega BETWEEN '2009-01-01' AND '2009-12-31';


-- solicitud de recurso
select * from csrd01_solicitud_recurso_cuerpo where ano_solicitud=2008 and fecha_solicitud BETWEEN '2009-01-01' AND '2009-12-31'

update csrd01_solicitud_recurso_cuerpo set fecha_solicitud='2008-12-31' WHERE ano_solicitud=2008 and fecha_solicitud BETWEEN '2009-01-01' AND '2009-12-31';


-- nota de debito
select * from cstd09_notadebito_especial_cuerpo where ano_movimiento=2008 and fecha_nota_debito BETWEEN '2009-01-01' AND '2009-12-31'
select * from cstd09_notadebito_especial_cuerpo where ano_movimiento=2008 and fecha_proceso_registro BETWEEN '2009-01-01' AND '2009-12-31'

update cstd09_notadebito_especial_cuerpo set fecha_nota_debito='2008-12-31' WHERE ano_movimiento=2008 and fecha_nota_debito BETWEEN '2009-01-01' AND '2009-12-31';
update cstd09_notadebito_especial_cuerpo set fecha_proceso_registro='2008-12-31' WHERE ano_movimiento=2008 and fecha_proceso_registro BETWEEN '2009-01-01' AND '2009-12-31';


-- rendiciones
select * from cfpd30_rendiciones_cuerpo where ano_rendicion=2008 and fecha_rendicion BETWEEN '2009-01-01' AND '2009-12-31'
select * from cfpd30_rendiciones_cuerpo where ano_rendicion=2008 and fecha_proceso_registro BETWEEN '2009-01-01' AND '2009-12-31'
select * from cfpd30_rendiciones_cuerpo where ano_rendicion=2008 and fecha_proceso_anulacion BETWEEN '2009-01-01' AND '2009-12-31'

update cfpd30_rendiciones_cuerpo set fecha_rendicion='2008-12-31' WHERE ano_rendicion=2008 and fecha_rendicion BETWEEN '2009-01-01' AND '2009-12-31';
update cfpd30_rendiciones_cuerpo set fecha_proceso_registro='2008-12-31' WHERE ano_rendicion=2008 and fecha_proceso_registro BETWEEN '2009-01-01' AND '2009-12-31';
update cfpd30_rendiciones_cuerpo set fecha_proceso_anulacion='2008-12-31' WHERE ano_rendicion=2008 and fecha_proceso_anulacion BETWEEN '2009-01-01' AND '2009-12-31';




-- reintegro
select * from cfpd30_reintegro_cuerpo where ano_reintegro=2008 and fecha_reintegro BETWEEN '2009-01-01' AND '2009-12-31'
select * from cfpd30_reintegro_cuerpo where ano_reintegro=2008 and fecha_proceso_registro BETWEEN '2009-01-01' AND '2009-12-31'
select * from cfpd30_reintegro_cuerpo where ano_reintegro=2008 and fecha_proceso_anulacion BETWEEN '2009-01-01' AND '2009-12-31'

update cfpd30_reintegro_cuerpo set fecha_reintegro='2008-12-31' WHERE ano_reintegro=2008 and fecha_reintegro BETWEEN '2009-01-01' AND '2009-12-31';
update cfpd30_reintegro_cuerpo set fecha_proceso_registro='2008-12-31' WHERE ano_reintegro=2008 and fecha_proceso_registro BETWEEN '2009-01-01' AND '2009-12-31';
update cfpd30_reintegro_cuerpo set fecha_proceso_anulacion='2008-12-31' WHERE ano_reintegro=2008 and fecha_proceso_anulacion BETWEEN '2009-01-01' AND '2009-12-31';




-- nota de debito
select * from cstd30_debito_cuerpo where ano_movimiento=2008 and fecha_debito BETWEEN '2009-01-01' AND '2009-12-31'
select * from cstd30_debito_cuerpo where ano_movimiento=2008 and fecha_proceso_registro BETWEEN '2009-01-01' AND '2009-12-31'
select * from cstd30_debito_cuerpo where ano_movimiento=2008 and fecha_proceso_anulacion BETWEEN '2009-01-01' AND '2009-12-31'

update cstd30_debito_cuerpo set fecha_debito='2008-12-31' WHERE ano_movimiento=2008 and fecha_debito BETWEEN '2009-01-01' AND '2009-12-31';
update cstd30_debito_cuerpo set fecha_proceso_registro='2008-12-31' WHERE ano_movimiento=2008 and fecha_proceso_registro BETWEEN '2009-01-01' AND '2009-12-31';
update cstd30_debito_cuerpo set fecha_proceso_anulacion='2008-12-31' WHERE ano_movimiento=2008 and fecha_proceso_anulacion BETWEEN '2009-01-01' AND '2009-12-31';



-- rentenciones islr
select * from cstd07_retenciones_cuerpo_islr where ano_orden_pago=2008 and fecha_proceso_registro BETWEEN '2009-01-01' AND '2009-12-31'
select * from cstd07_retenciones_cuerpo_islr where ano_orden_pago=2008 and fecha_proceso_anulacion BETWEEN '2009-01-01' AND '2009-12-31'

update cstd07_retenciones_cuerpo_islr set fecha_proceso_registro='2008-12-31' WHERE ano_orden_pago=2008 and fecha_proceso_registro BETWEEN '2009-01-01' AND '2009-12-31';
update cstd07_retenciones_cuerpo_islr set fecha_proceso_anulacion='2008-12-31' WHERE ano_orden_pago=2008 and fecha_proceso_anulacion BETWEEN '2009-01-01' AND '2009-12-31';

-- rentenciones iva
select * from cstd07_retenciones_cuerpo_iva where ano_orden_pago=2008 and fecha_proceso_registro BETWEEN '2009-01-01' AND '2009-12-31'
select * from cstd07_retenciones_cuerpo_iva where ano_orden_pago=2008 and fecha_proceso_anulacion BETWEEN '2009-01-01' AND '2009-12-31'

update cstd07_retenciones_cuerpo_iva set fecha_proceso_registro='2008-12-31' WHERE ano_orden_pago=2008 and fecha_proceso_registro BETWEEN '2009-01-01' AND '2009-12-31';
update cstd07_retenciones_cuerpo_iva set fecha_proceso_anulacion='2008-12-31' WHERE ano_orden_pago=2008 and fecha_proceso_anulacion BETWEEN '2009-01-01' AND '2009-12-31';


-- rentenciones municipal
select * from cstd07_retenciones_cuerpo_municipal where ano_orden_pago=2008 and fecha_proceso_registro BETWEEN '2009-01-01' AND '2009-12-31'
select * from cstd07_retenciones_cuerpo_municipal where ano_orden_pago=2008 and fecha_proceso_anulacion BETWEEN '2009-01-01' AND '2009-12-31'

update cstd07_retenciones_cuerpo_municipal set fecha_proceso_registro='2008-12-31' WHERE ano_orden_pago=2008 and fecha_proceso_registro BETWEEN '2009-01-01' AND '2009-12-31';
update cstd07_retenciones_cuerpo_municipal set fecha_proceso_anulacion='2008-12-31' WHERE ano_orden_pago=2008 and fecha_proceso_anulacion BETWEEN '2009-01-01' AND '2009-12-31';

-- rentenciones timbre
select * from cstd07_retenciones_cuerpo_timbre where ano_orden_pago=2008 and fecha_proceso_registro BETWEEN '2009-01-01' AND '2009-12-31'
select * from cstd07_retenciones_cuerpo_timbre where ano_orden_pago=2008 and fecha_proceso_anulacion BETWEEN '2009-01-01' AND '2009-12-31'

update cstd07_retenciones_cuerpo_timbre set fecha_proceso_registro='2008-12-31' WHERE ano_orden_pago=2008 and fecha_proceso_registro BETWEEN '2009-01-01' AND '2009-12-31';
update cstd07_retenciones_cuerpo_timbre set fecha_proceso_anulacion='2008-12-31' WHERE ano_orden_pago=2008 and fecha_proceso_anulacion BETWEEN '2009-01-01' AND '2009-12-31';


-- nota de debitocstd09_notadebito_cuerpo
select * from cstd09_notadebito_cuerpo where ano_movimiento=2008 and fecha_debito BETWEEN '2009-01-01' AND '2009-12-31'
select * from cstd09_notadebito_cuerpo where ano_movimiento=2008 and fecha_proceso_registro BETWEEN '2009-01-01' AND '2009-12-31'
select * from cstd09_notadebito_cuerpo where ano_movimiento=2008 and fecha_proceso_anulacion BETWEEN '2009-01-01' AND '2009-12-31'

update cstd09_notadebito_cuerpo set fecha_debito='2008-12-31' WHERE ano_movimiento=2008 and fecha_debito BETWEEN '2009-01-01' AND '2009-12-31';
update cstd09_notadebito_cuerpo set fecha_proceso_registro='2008-12-31' WHERE ano_movimiento=2008 and fecha_proceso_registro BETWEEN '2009-01-01' AND '2009-12-31';
update cstd09_notadebito_cuerpo set fecha_proceso_anulacion='2008-12-31' WHERE ano_movimiento=2008 and fecha_proceso_anulacion BETWEEN '2009-01-01' AND '2009-12-31';




































