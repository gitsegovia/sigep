











CREATE OR REPLACE VIEW v_cscd03_cotizacion AS
 SELECT


 cscd03_cotizacion_encabezado.cod_presi,
 cscd03_cotizacion_encabezado.cod_entidad,
 cscd03_cotizacion_encabezado.cod_tipo_inst,
 cscd03_cotizacion_encabezado.cod_inst,
 cscd03_cotizacion_encabezado.cod_dep,
 cscd03_cotizacion_encabezado.rif,
 cpcd02.denominacion AS rif_denominacion,
 cpcd02.direccion_comercial AS rif_direccion,
 cscd03_cotizacion_encabezado.ano_cotizacion,
 cscd03_cotizacion_encabezado.numero_cotizacion,
 cscd03_cotizacion_encabezado.fecha_cotizacion,
 cscd03_cotizacion_encabezado.ano_solicitud,
 cscd03_cotizacion_encabezado.numero_solicitud,
 cscd03_cotizacion_encabezado.fecha_proceso,
 cscd03_cotizacion_encabezado.ano_ordencompra,
 cscd03_cotizacion_encabezado.numero_ordencompra,
 cscd03_cotizacion_cuerpo.codigo_prod_serv,
 cscd03_cotizacion_cuerpo.descripcion,
 cscd03_cotizacion_cuerpo.cod_medida,
 cscd01_unidad_medida.expresion,
 cscd03_cotizacion_cuerpo.cantidad,
 cscd03_cotizacion_cuerpo.precio_unitario,
 cscd03_cotizacion_cuerpo.cantidad * cscd03_cotizacion_cuerpo.precio_unitario AS total




   FROM cscd03_cotizacion_encabezado, cscd03_cotizacion_cuerpo, cscd01_unidad_medida, cscd01_catalogo, cpcd02



  WHERE

  cscd03_cotizacion_encabezado.cod_presi           = cscd03_cotizacion_cuerpo.cod_presi AND
  cscd03_cotizacion_encabezado.cod_entidad         = cscd03_cotizacion_cuerpo.cod_entidad AND
  cscd03_cotizacion_encabezado.cod_tipo_inst       = cscd03_cotizacion_cuerpo.cod_tipo_inst AND
  cscd03_cotizacion_encabezado.cod_inst            = cscd03_cotizacion_cuerpo.cod_inst AND
  cscd03_cotizacion_encabezado.cod_dep             = cscd03_cotizacion_cuerpo.cod_dep AND
  cscd03_cotizacion_cuerpo.cod_medida              = cscd01_unidad_medida.cod_medida AND
  cscd03_cotizacion_cuerpo.codigo_prod_serv        = cscd01_catalogo.codigo_prod_serv AND
  cscd03_cotizacion_cuerpo.numero_cotizacion::text = cscd03_cotizacion_encabezado.numero_cotizacion::text AND
  cscd03_cotizacion_cuerpo.ano_cotizacion          = cscd03_cotizacion_encabezado.ano_cotizacion AND
  cscd03_cotizacion_encabezado.rif::text           = cpcd02.rif::text AND
  cscd03_cotizacion_encabezado.rif::text           = cscd03_cotizacion_cuerpo.rif::text



  ORDER BY cscd03_cotizacion_encabezado.cod_presi, cscd03_cotizacion_encabezado.cod_entidad, cscd03_cotizacion_encabezado.cod_tipo_inst, cscd03_cotizacion_encabezado.cod_inst, cscd03_cotizacion_encabezado.cod_dep, cscd03_cotizacion_encabezado.rif, cscd03_cotizacion_encabezado.ano_cotizacion, cscd03_cotizacion_encabezado.numero_cotizacion, cscd03_cotizacion_encabezado.fecha_cotizacion, cscd03_cotizacion_encabezado.ano_solicitud, cscd03_cotizacion_encabezado.numero_solicitud, cscd03_cotizacion_encabezado.fecha_proceso, cscd03_cotizacion_encabezado.ano_ordencompra, cscd03_cotizacion_encabezado.numero_ordencompra, cscd03_cotizacion_cuerpo.codigo_prod_serv, cscd03_cotizacion_cuerpo.descripcion, cscd03_cotizacion_cuerpo.cod_medida, cscd01_unidad_medida.expresion, cscd03_cotizacion_cuerpo.cantidad, cscd03_cotizacion_cuerpo.precio_unitario;

ALTER TABLE v_cscd03_cotizacion OWNER TO sisap;