DROP VIEW v_ciad01_inventario_productos;
DROP TABLE ciad01_inventario_almacen;
DROP TABLE ciad01_inventario_usuarios;
DROP TABLE ciad01_inventario_productos;
DROP TABLE ciad01_inventario_entradas_numero;
DROP TABLE ciad01_inventario_entradas_detalles;
DROP TABLE ciad01_inventario_entradas_cuerpo;
DROP TABLE ciad01_inventario_salidas_numero;
DROP TABLE ciad01_inventario_salidas_detalles;
DROP TABLE ciad01_inventario_salidas_cuerpo;



-- Table: ciad01_inventario_almacen

-- DROP TABLE ciad01_inventario_almacen;

CREATE TABLE ciad01_inventario_almacen
(
  cod_presi integer NOT NULL, -- Código de la presidencia
  cod_entidad integer NOT NULL, -- Código de la entidad federal
  cod_tipo_inst integer NOT NULL, -- Código tipo de Institución
  cod_inst integer NOT NULL, -- Código de la Institución
  cod_dep integer NOT NULL, -- Código de la dependencia
  cod_almacen integer NOT NULL, -- Código del almacén
  denominacion character varying(200) NOT NULL, -- Denominación
  ubicacion text NOT NULL, -- Ubicación
  principal_secundario integer NOT NULL, -- Almacén Principal o Secundario...
  responsable_almacen character varying(100) NOT NULL, -- Nombres y Apellidos del Responsable del Almacén
  cedula_identidad integer NOT NULL, -- Cédula identidad del Responsable del Almacén
  seleccionar_productos integer, -- Permite seleccionar Productos?...
  CONSTRAINT ciad01_inventario_almacen_pkey PRIMARY KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_almacen)
)
WITH (OIDS=FALSE);
ALTER TABLE ciad01_inventario_almacen OWNER TO sisap;
COMMENT ON TABLE ciad01_inventario_almacen IS 'Registro de Almacenes';
COMMENT ON COLUMN ciad01_inventario_almacen.cod_presi IS 'Código de la presidencia';
COMMENT ON COLUMN ciad01_inventario_almacen.cod_entidad IS 'Código de la entidad federal';
COMMENT ON COLUMN ciad01_inventario_almacen.cod_tipo_inst IS 'Código tipo de Institución';
COMMENT ON COLUMN ciad01_inventario_almacen.cod_inst IS 'Código de la Institución';
COMMENT ON COLUMN ciad01_inventario_almacen.cod_dep IS 'Código de la dependencia';
COMMENT ON COLUMN ciad01_inventario_almacen.cod_almacen IS 'Código del almacén';
COMMENT ON COLUMN ciad01_inventario_almacen.denominacion IS 'Denominación';
COMMENT ON COLUMN ciad01_inventario_almacen.ubicacion IS 'Ubicación';
COMMENT ON COLUMN ciad01_inventario_almacen.principal_secundario IS 'Almacén Principal o Secundario

1.- Principal
2.- Secundario
';
COMMENT ON COLUMN ciad01_inventario_almacen.responsable_almacen IS 'Nombres y Apellidos del Responsable del Almacén
';
COMMENT ON COLUMN ciad01_inventario_almacen.cedula_identidad IS 'Cédula identidad del Responsable del Almacén
';
COMMENT ON COLUMN ciad01_inventario_almacen.seleccionar_productos IS 'Permite seleccionar Productos?
1.- Si
2.- NO
';







-- Table: ciad01_inventario_usuarios

-- DROP TABLE ciad01_inventario_usuarios;

CREATE TABLE ciad01_inventario_usuarios
(
  cod_presi integer NOT NULL, -- Código de la presidencia
  cod_entidad integer NOT NULL, -- Código de la entidad federal
  cod_tipo_inst integer NOT NULL, -- Código tipo de Institución
  cod_inst integer NOT NULL, -- Código de la Institución
  cod_dep integer NOT NULL, -- Código de la Dependencia
  username character varying(60) NOT NULL, -- Operador autorizado para entrar
  cod_almacen integer NOT NULL, -- Código de Almacén conectado con el operador usuario
  CONSTRAINT ciad01_inventario_usuarios_pkey PRIMARY KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, username)
)
WITH (OIDS=FALSE);
ALTER TABLE ciad01_inventario_usuarios OWNER TO sisap;
COMMENT ON TABLE ciad01_inventario_usuarios IS 'Almacén al cual puede acceder este usuario';
COMMENT ON COLUMN ciad01_inventario_usuarios.cod_presi IS 'Código de la presidencia';
COMMENT ON COLUMN ciad01_inventario_usuarios.cod_entidad IS 'Código de la entidad federal';
COMMENT ON COLUMN ciad01_inventario_usuarios.cod_tipo_inst IS 'Código tipo de Institución';
COMMENT ON COLUMN ciad01_inventario_usuarios.cod_inst IS 'Código de la Institución';
COMMENT ON COLUMN ciad01_inventario_usuarios.cod_dep IS 'Código de la Dependencia';
COMMENT ON COLUMN ciad01_inventario_usuarios.username IS 'Operador autorizado para entrar';
COMMENT ON COLUMN ciad01_inventario_usuarios.cod_almacen IS 'Código de Almacén conectado con el operador usuario';








-- Table: ciad01_inventario_productos

-- DROP TABLE ciad01_inventario_productos;

CREATE TABLE ciad01_inventario_productos
(
  cod_presi integer NOT NULL, -- Código de la presidencia
  cod_entidad integer NOT NULL, -- Código de la entidad federal
  cod_tipo_inst integer NOT NULL, -- Código tipo de Institución
  cod_inst integer NOT NULL, -- Código de Institución
  cod_dep integer NOT NULL, -- Código de Dependencia
  cod_almacen integer NOT NULL, -- Código de Almacén
  cod_prod_serv integer NOT NULL, -- Código de producto (Catálogo de Productos)
  stock_maximo numeric(22,6) NOT NULL, -- Stock máximo de productos
  stock_minimo numeric(22,6) NOT NULL, -- Stock Mínimo
  punto_pedido numeric(22,6) NOT NULL, -- Punto de Pedido
  estante_numero integer NOT NULL, -- Número de estante
  fila_numero integer NOT NULL, -- Número de Fila
  columna_numero integer NOT NULL, -- Número de Columna
  complemento_sitio_almacenaje text, -- Complemento de ubicación del sitio de almacenaje
  numero_entradas numeric(22,6) NOT NULL, -- Número de entradas del producto
  numero_salidas numeric(22,6) NOT NULL, -- Número de Salidas del Producto
  costo_maximo numeric(26,2) NOT NULL, -- Costo máximo del producto
  costo_minimo numeric(26,2) NOT NULL, -- Costo minímo del producto
  fecha_registro date NOT NULL, -- Fecha de Registro
  username_registro character varying(60) NOT NULL, -- Operador que registro el producto
  cod_snc character varying(30) NOT NULL, -- Código del SNC
  CONSTRAINT ciad01_inventario_productos_pkey PRIMARY KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_almacen, cod_prod_serv)
)
WITH (OIDS=FALSE);
ALTER TABLE ciad01_inventario_productos OWNER TO sisap;
COMMENT ON TABLE ciad01_inventario_productos IS 'Registra los productos que se deben inventariar';
COMMENT ON COLUMN ciad01_inventario_productos.cod_presi IS 'Código de la presidencia';
COMMENT ON COLUMN ciad01_inventario_productos.cod_entidad IS 'Código de la entidad federal';
COMMENT ON COLUMN ciad01_inventario_productos.cod_tipo_inst IS 'Código tipo de Institución';
COMMENT ON COLUMN ciad01_inventario_productos.cod_inst IS 'Código de Institución';
COMMENT ON COLUMN ciad01_inventario_productos.cod_dep IS 'Código de Dependencia';
COMMENT ON COLUMN ciad01_inventario_productos.cod_almacen IS 'Código de Almacén';
COMMENT ON COLUMN ciad01_inventario_productos.cod_prod_serv IS 'Código de producto (Catálogo de Productos)';
COMMENT ON COLUMN ciad01_inventario_productos.stock_maximo IS 'Stock máximo de productos';
COMMENT ON COLUMN ciad01_inventario_productos.stock_minimo IS 'Stock Mínimo';
COMMENT ON COLUMN ciad01_inventario_productos.punto_pedido IS 'Punto de Pedido';
COMMENT ON COLUMN ciad01_inventario_productos.estante_numero IS 'Número de estante';
COMMENT ON COLUMN ciad01_inventario_productos.fila_numero IS 'Número de Fila';
COMMENT ON COLUMN ciad01_inventario_productos.columna_numero IS 'Número de Columna';
COMMENT ON COLUMN ciad01_inventario_productos.complemento_sitio_almacenaje IS 'Complemento de ubicación del sitio de almacenaje';
COMMENT ON COLUMN ciad01_inventario_productos.numero_entradas IS 'Número de entradas del producto';
COMMENT ON COLUMN ciad01_inventario_productos.numero_salidas IS 'Número de Salidas del Producto';
COMMENT ON COLUMN ciad01_inventario_productos.costo_maximo IS 'Costo máximo del producto';
COMMENT ON COLUMN ciad01_inventario_productos.costo_minimo IS 'Costo minímo del producto';
COMMENT ON COLUMN ciad01_inventario_productos.fecha_registro IS 'Fecha de Registro';
COMMENT ON COLUMN ciad01_inventario_productos.username_registro IS 'Operador que registro el producto';
COMMENT ON COLUMN ciad01_inventario_productos.cod_snc IS 'Código del SNC';









-- Table: ciad01_inventario_entradas_numero

-- DROP TABLE ciad01_inventario_entradas_numero;

CREATE TABLE ciad01_inventario_entradas_numero
(
  cod_presi integer NOT NULL, -- Código de presidencia
  cod_entidad integer NOT NULL, -- Código de entidad
  cod_tipo_inst integer NOT NULL, -- Código tipo de institución
  cod_inst integer NOT NULL, -- Código de institución
  cod_dep integer NOT NULL, -- Código de dependencia
  cod_almacen_entrada integer NOT NULL, -- Código de Almacen para la entrada de los productos
  ano_recepcion integer NOT NULL, -- Año o ejercicio fiscal, donde comienza el control del número de recepción
  numero_recepcion integer NOT NULL, -- Número de Recepción
  situacion integer, -- 1.- Sin utilizar...
  CONSTRAINT ciad01_inventario_entradas_numero_pkey PRIMARY KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_almacen_entrada, ano_recepcion, numero_recepcion)
)
WITH (OIDS=FALSE);
ALTER TABLE ciad01_inventario_entradas_numero OWNER TO sisap;
COMMENT ON TABLE ciad01_inventario_entradas_numero IS 'Registra el número de la solicitud de acuerdo al ejercicio fiscal';
COMMENT ON COLUMN ciad01_inventario_entradas_numero.cod_presi IS 'Código de presidencia';
COMMENT ON COLUMN ciad01_inventario_entradas_numero.cod_entidad IS 'Código de entidad';
COMMENT ON COLUMN ciad01_inventario_entradas_numero.cod_tipo_inst IS 'Código tipo de institución';
COMMENT ON COLUMN ciad01_inventario_entradas_numero.cod_inst IS 'Código de institución';
COMMENT ON COLUMN ciad01_inventario_entradas_numero.cod_dep IS 'Código de dependencia';
COMMENT ON COLUMN ciad01_inventario_entradas_numero.cod_almacen_entrada IS 'Código de Almacen para la entrada de los productos';
COMMENT ON COLUMN ciad01_inventario_entradas_numero.ano_recepcion IS 'Año o ejercicio fiscal, donde comienza el control del número de recepción';
COMMENT ON COLUMN ciad01_inventario_entradas_numero.numero_recepcion IS 'Número de Recepción';
COMMENT ON COLUMN ciad01_inventario_entradas_numero.situacion IS '1.- Sin utilizar
2.- Seleccionado
3.- Emitido
4.- Anulado
5.- Congelado';









-- Table: ciad01_inventario_entradas_cuerpo

-- DROP TABLE ciad01_inventario_entradas_cuerpo;

CREATE TABLE ciad01_inventario_entradas_cuerpo
(
  cod_presi integer NOT NULL, -- Código de la presidencia
  cod_entidad integer NOT NULL, -- Código de la entidad federal
  cod_tipo_inst integer NOT NULL, -- Código tipo de Institución
  cod_inst integer NOT NULL, -- Código de Institución
  cod_dep integer NOT NULL, -- Código de Dependencia
  cod_almacen_entrada integer NOT NULL, -- Código de almacen
  ano_recepcion integer NOT NULL, -- Año de recepción
  numero_recepcion integer NOT NULL, -- Número de recepción
  fecha_recepcion date NOT NULL, -- Fecha de recepción
  cod_dep_origen integer NOT NULL, -- Código de la dependencia de origen
  rif character varying(20), -- Rif del proveedor
  ano_nota_entrega integer, -- Año nota de entrega
  numero_nota_entrega character varying(30), -- Número de nota de entrega
  ano_orden_compra integer, -- Año orden de compra
  numero_orden_compra integer, -- Número de orden de compra
  condicion_actividad integer NOT NULL, -- Condición de actividad...
  fecha_registro date NOT NULL, -- Fecha de registro
  username_registro character varying(60) NOT NULL, -- Operador que realizo el registro
  fecha_anulacion date, -- Fecha de anulación
  username_anulacion character varying(60), -- Operador que realizo la anulación
  observaciones text, -- Observaciones
  cod_dep_salida integer,
  cod_almacen_salida integer, -- Código de Almacén de la Salida...
  ano_orden_salida integer, -- Año orden salida...
  numero_orden_salida integer, -- Número orden de salida...
  CONSTRAINT ciad01_inventario_entradas_cuerpo_pkey PRIMARY KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_almacen_entrada, ano_recepcion, numero_recepcion)
)
WITH (OIDS=FALSE);
ALTER TABLE ciad01_inventario_entradas_cuerpo OWNER TO sisap;
COMMENT ON TABLE ciad01_inventario_entradas_cuerpo IS 'Registra las entregas de Productos y Materiales mediante una orden de recepción';
COMMENT ON COLUMN ciad01_inventario_entradas_cuerpo.cod_presi IS 'Código de la presidencia';
COMMENT ON COLUMN ciad01_inventario_entradas_cuerpo.cod_entidad IS 'Código de la entidad federal';
COMMENT ON COLUMN ciad01_inventario_entradas_cuerpo.cod_tipo_inst IS 'Código tipo de Institución';
COMMENT ON COLUMN ciad01_inventario_entradas_cuerpo.cod_inst IS 'Código de Institución';
COMMENT ON COLUMN ciad01_inventario_entradas_cuerpo.cod_dep IS 'Código de Dependencia';
COMMENT ON COLUMN ciad01_inventario_entradas_cuerpo.cod_almacen_entrada IS 'Código de almacen';
COMMENT ON COLUMN ciad01_inventario_entradas_cuerpo.ano_recepcion IS 'Año de recepción';
COMMENT ON COLUMN ciad01_inventario_entradas_cuerpo.numero_recepcion IS 'Número de recepción';
COMMENT ON COLUMN ciad01_inventario_entradas_cuerpo.fecha_recepcion IS 'Fecha de recepción';
COMMENT ON COLUMN ciad01_inventario_entradas_cuerpo.cod_dep_origen IS 'Código de la dependencia de origen';
COMMENT ON COLUMN ciad01_inventario_entradas_cuerpo.rif IS 'Rif del proveedor';
COMMENT ON COLUMN ciad01_inventario_entradas_cuerpo.ano_nota_entrega IS 'Año nota de entrega';
COMMENT ON COLUMN ciad01_inventario_entradas_cuerpo.numero_nota_entrega IS 'Número de nota de entrega';
COMMENT ON COLUMN ciad01_inventario_entradas_cuerpo.ano_orden_compra IS 'Año orden de compra';
COMMENT ON COLUMN ciad01_inventario_entradas_cuerpo.numero_orden_compra IS 'Número de orden de compra';
COMMENT ON COLUMN ciad01_inventario_entradas_cuerpo.condicion_actividad IS 'Condición de actividad
1.- Activa
2.- Anulada';
COMMENT ON COLUMN ciad01_inventario_entradas_cuerpo.fecha_registro IS 'Fecha de registro';
COMMENT ON COLUMN ciad01_inventario_entradas_cuerpo.username_registro IS 'Operador que realizo el registro';
COMMENT ON COLUMN ciad01_inventario_entradas_cuerpo.fecha_anulacion IS 'Fecha de anulación';
COMMENT ON COLUMN ciad01_inventario_entradas_cuerpo.username_anulacion IS 'Operador que realizo la anulación';
COMMENT ON COLUMN ciad01_inventario_entradas_cuerpo.observaciones IS 'Observaciones';
COMMENT ON COLUMN ciad01_inventario_entradas_cuerpo.cod_almacen_salida IS 'Código de Almacén de la Salida
Se registra cuando le dan entrada en la Dependencia receptora';
COMMENT ON COLUMN ciad01_inventario_entradas_cuerpo.ano_orden_salida IS 'Año orden salida
Se registra cuando le dan entrada en la Dependencia receptora';
COMMENT ON COLUMN ciad01_inventario_entradas_cuerpo.numero_orden_salida IS 'Número orden de salida
Se registra cuando le dan entrada en la Dependencia receptora';





-- Table: ciad01_inventario_entradas_detalles

-- DROP TABLE ciad01_inventario_entradas_detalles;

CREATE TABLE ciad01_inventario_entradas_detalles
(
  cod_presi integer NOT NULL, -- Código de la presidencia
  cod_entidad integer NOT NULL, -- Código de la entidad federal
  cod_tipo_inst integer NOT NULL, -- Código tipo de Institución
  cod_inst integer NOT NULL, -- Código de la Institución
  cod_dep integer NOT NULL, -- Código de la dependencia
  cod_almacen_entrada integer NOT NULL,
  ano_recepcion integer NOT NULL, -- Año de la recepción
  numero_recepcion integer NOT NULL, -- Número de recepción
  codigo_prod_serv integer NOT NULL, -- Código de producto
  cantidad numeric(22,6) NOT NULL, -- Cantidad de productos
  precio_unitario numeric(26,3) NOT NULL, -- Precio unitario por producto
  CONSTRAINT ciad01_inventario_entradas_detalles_pkey PRIMARY KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_almacen_entrada, ano_recepcion, numero_recepcion, codigo_prod_serv),
  CONSTRAINT ciad01_inventario_entradas_detalles_1 FOREIGN KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_almacen_entrada, ano_recepcion, numero_recepcion)
      REFERENCES ciad01_inventario_entradas_cuerpo (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_almacen_entrada, ano_recepcion, numero_recepcion) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE CASCADE
)
WITH (OIDS=FALSE);
ALTER TABLE ciad01_inventario_entradas_detalles OWNER TO sisap;
COMMENT ON TABLE ciad01_inventario_entradas_detalles IS 'Registra los Productos y Materiales de las entrega';
COMMENT ON COLUMN ciad01_inventario_entradas_detalles.cod_presi IS 'Código de la presidencia';
COMMENT ON COLUMN ciad01_inventario_entradas_detalles.cod_entidad IS 'Código de la entidad federal';
COMMENT ON COLUMN ciad01_inventario_entradas_detalles.cod_tipo_inst IS 'Código tipo de Institución';
COMMENT ON COLUMN ciad01_inventario_entradas_detalles.cod_inst IS 'Código de la Institución';
COMMENT ON COLUMN ciad01_inventario_entradas_detalles.cod_dep IS 'Código de la dependencia';
COMMENT ON COLUMN ciad01_inventario_entradas_detalles.ano_recepcion IS 'Año de la recepción';
COMMENT ON COLUMN ciad01_inventario_entradas_detalles.numero_recepcion IS 'Número de recepción';
COMMENT ON COLUMN ciad01_inventario_entradas_detalles.codigo_prod_serv IS 'Código de producto';
COMMENT ON COLUMN ciad01_inventario_entradas_detalles.cantidad IS 'Cantidad de productos';
COMMENT ON COLUMN ciad01_inventario_entradas_detalles.precio_unitario IS 'Precio unitario por producto';














-- Table: ciad01_inventario_salidas_numero

-- DROP TABLE ciad01_inventario_salidas_numero;

CREATE TABLE ciad01_inventario_salidas_numero
(
  cod_presi integer NOT NULL, -- Código de presidencia
  cod_entidad integer NOT NULL, -- Código de entidad
  cod_tipo_inst integer NOT NULL, -- Código tipo de institución
  cod_inst integer NOT NULL, -- Código de institución
  cod_dep integer NOT NULL, -- Código de dependencia
  cod_almacen_salida integer NOT NULL, -- Código de Almacén salida
  ano_orden_salida integer NOT NULL, -- Año o ejercicio fiscal, donde comienza el control del número de la orden de salida
  numero_orden_salida integer NOT NULL, -- Número orden de salida
  situacion integer, -- 1.- Sin utilizar...
  CONSTRAINT ciad01_inventario_salidas_numero_pkey PRIMARY KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_almacen_salida, ano_orden_salida, numero_orden_salida)
)
WITH (OIDS=FALSE);
ALTER TABLE ciad01_inventario_salidas_numero OWNER TO sisap;
COMMENT ON TABLE ciad01_inventario_salidas_numero IS 'Registra el número de la solicitud de acuerdo al ejercicio fiscal';
COMMENT ON COLUMN ciad01_inventario_salidas_numero.cod_presi IS 'Código de presidencia';
COMMENT ON COLUMN ciad01_inventario_salidas_numero.cod_entidad IS 'Código de entidad';
COMMENT ON COLUMN ciad01_inventario_salidas_numero.cod_tipo_inst IS 'Código tipo de institución';
COMMENT ON COLUMN ciad01_inventario_salidas_numero.cod_inst IS 'Código de institución';
COMMENT ON COLUMN ciad01_inventario_salidas_numero.cod_dep IS 'Código de dependencia';
COMMENT ON COLUMN ciad01_inventario_salidas_numero.cod_almacen_salida IS 'Código de Almacén salida';
COMMENT ON COLUMN ciad01_inventario_salidas_numero.ano_orden_salida IS 'Año o ejercicio fiscal, donde comienza el control del número de la orden de salida';
COMMENT ON COLUMN ciad01_inventario_salidas_numero.numero_orden_salida IS 'Número orden de salida';
COMMENT ON COLUMN ciad01_inventario_salidas_numero.situacion IS '1.- Sin utilizar
2.- Seleccionado
3.- Emitido
4.- Anulado
5.- Congelado';







-- Table: ciad01_inventario_salidas_cuerpo

-- DROP TABLE ciad01_inventario_salidas_cuerpo;

CREATE TABLE ciad01_inventario_salidas_cuerpo
(
  cod_presi integer NOT NULL, -- Código de la presidencia
  cod_entidad integer NOT NULL, -- Código de la entidad federal
  cod_tipo_inst integer NOT NULL, -- Código tipo de Institución
  cod_inst integer NOT NULL, -- Código de Institución
  cod_dep integer NOT NULL, -- Código de Dependencia
  cod_almacen_salida integer NOT NULL,
  ano_orden_salida integer NOT NULL, -- Año orden de salida
  numero_orden_salida integer NOT NULL, -- Número orden de salida
  fecha_orden_salida date NOT NULL, -- Fecha orden salida
  observaciones text NOT NULL, -- observaciones
  condicion_actividad integer NOT NULL, -- Condición de actividad...
  fecha_registro date NOT NULL, -- Fecha de registro
  username_registro character varying(60) NOT NULL, -- Operador que realizo el registro
  fecha_anulacion date, -- Fecha de anulación
  username_anulacion character varying(60), -- Operador que realizo la anulación
  cod_dep_receptora integer, -- Código de Dependencia receptora
  cod_almacen_receptor integer, -- Código de Almacén receptor...
  ano_recepcion integer, -- Año de recepcion...
  numero_recepcion integer, -- Número recepción...
  entregado integer DEFAULT 2, -- Los productos fueròn entregados?...
  CONSTRAINT ciad01_inventario_salidas_cuerpo_pkey PRIMARY KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_almacen_salida, ano_orden_salida, numero_orden_salida)
)
WITH (OIDS=FALSE);
ALTER TABLE ciad01_inventario_salidas_cuerpo OWNER TO sisap;
COMMENT ON TABLE ciad01_inventario_salidas_cuerpo IS 'Registra las entregas de Productos y Materiales mediante una orden de recepción';
COMMENT ON COLUMN ciad01_inventario_salidas_cuerpo.cod_presi IS 'Código de la presidencia';
COMMENT ON COLUMN ciad01_inventario_salidas_cuerpo.cod_entidad IS 'Código de la entidad federal';
COMMENT ON COLUMN ciad01_inventario_salidas_cuerpo.cod_tipo_inst IS 'Código tipo de Institución';
COMMENT ON COLUMN ciad01_inventario_salidas_cuerpo.cod_inst IS 'Código de Institución';
COMMENT ON COLUMN ciad01_inventario_salidas_cuerpo.cod_dep IS 'Código de Dependencia';
COMMENT ON COLUMN ciad01_inventario_salidas_cuerpo.ano_orden_salida IS 'Año orden de salida';
COMMENT ON COLUMN ciad01_inventario_salidas_cuerpo.numero_orden_salida IS 'Número orden de salida';
COMMENT ON COLUMN ciad01_inventario_salidas_cuerpo.fecha_orden_salida IS 'Fecha orden salida';
COMMENT ON COLUMN ciad01_inventario_salidas_cuerpo.observaciones IS 'observaciones';
COMMENT ON COLUMN ciad01_inventario_salidas_cuerpo.condicion_actividad IS 'Condición de actividad
1.- Activa
2.- Anulada';
COMMENT ON COLUMN ciad01_inventario_salidas_cuerpo.fecha_registro IS 'Fecha de registro';
COMMENT ON COLUMN ciad01_inventario_salidas_cuerpo.username_registro IS 'Operador que realizo el registro';
COMMENT ON COLUMN ciad01_inventario_salidas_cuerpo.fecha_anulacion IS 'Fecha de anulación';
COMMENT ON COLUMN ciad01_inventario_salidas_cuerpo.username_anulacion IS 'Operador que realizo la anulación';
COMMENT ON COLUMN ciad01_inventario_salidas_cuerpo.cod_dep_receptora IS 'Código de Dependencia receptora
';
COMMENT ON COLUMN ciad01_inventario_salidas_cuerpo.cod_almacen_receptor IS 'Código de Almacén receptor

Se registra cuando le dan entrada en la Dependencia receptora';
COMMENT ON COLUMN ciad01_inventario_salidas_cuerpo.ano_recepcion IS 'Año de recepcion

Se registra cuando le dan entrada en la Dependencia receptora';
COMMENT ON COLUMN ciad01_inventario_salidas_cuerpo.numero_recepcion IS 'Número recepción

Se registra cuando le dan entrada en la Dependencia receptora';
COMMENT ON COLUMN ciad01_inventario_salidas_cuerpo.entregado IS 'Los productos fueròn entregados?
1.- Si
2.- No
';







-- Table: ciad01_inventario_salidas_detalles

-- DROP TABLE ciad01_inventario_salidas_detalles;

CREATE TABLE ciad01_inventario_salidas_detalles
(
  cod_presi integer NOT NULL, -- Código de la presidencia
  cod_entidad integer NOT NULL, -- Código de la entidad federal
  cod_tipo_inst integer NOT NULL, -- Código tipo de Institución
  cod_inst integer NOT NULL, -- Código de la Institución
  cod_dep integer NOT NULL, -- Código de la dependencia
  cod_almacen_salida integer NOT NULL,
  ano_orden_salida integer NOT NULL, -- Año orden de salida
  numero_orden_salida integer NOT NULL, -- Número orden de salida
  codigo_prod_serv integer NOT NULL, -- Código de producto
  cantidad numeric(22,6) NOT NULL, -- Cantidad de productos
  CONSTRAINT ciad01_inventario_salidas_detalles_pkey PRIMARY KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_almacen_salida, ano_orden_salida, numero_orden_salida, codigo_prod_serv),
  CONSTRAINT ciad01_inventario_salidas_detalles_1 FOREIGN KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_almacen_salida, ano_orden_salida, numero_orden_salida)
      REFERENCES ciad01_inventario_salidas_cuerpo (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_almacen_salida, ano_orden_salida, numero_orden_salida) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE CASCADE
)
WITH (OIDS=FALSE);
ALTER TABLE ciad01_inventario_salidas_detalles OWNER TO sisap;
COMMENT ON TABLE ciad01_inventario_salidas_detalles IS 'Registra los Productos y Materiales de las entrega';
COMMENT ON COLUMN ciad01_inventario_salidas_detalles.cod_presi IS 'Código de la presidencia';
COMMENT ON COLUMN ciad01_inventario_salidas_detalles.cod_entidad IS 'Código de la entidad federal';
COMMENT ON COLUMN ciad01_inventario_salidas_detalles.cod_tipo_inst IS 'Código tipo de Institución';
COMMENT ON COLUMN ciad01_inventario_salidas_detalles.cod_inst IS 'Código de la Institución';
COMMENT ON COLUMN ciad01_inventario_salidas_detalles.cod_dep IS 'Código de la dependencia';
COMMENT ON COLUMN ciad01_inventario_salidas_detalles.ano_orden_salida IS 'Año orden de salida';
COMMENT ON COLUMN ciad01_inventario_salidas_detalles.numero_orden_salida IS 'Número orden de salida';
COMMENT ON COLUMN ciad01_inventario_salidas_detalles.codigo_prod_serv IS 'Código de producto';
COMMENT ON COLUMN ciad01_inventario_salidas_detalles.cantidad IS 'Cantidad de productos';



-- View: v_ciad01_inventario_productos

-- DROP VIEW v_ciad01_inventario_productos;

CREATE OR REPLACE VIEW v_ciad01_inventario_productos AS
 SELECT
 c.cod_presi,
 c.cod_entidad,
 c.cod_tipo_inst,
 c.cod_inst,
 c.cod_dep,
 c.cod_almacen,
 (select a.denominacion from ciad01_inventario_almacen a where  a.cod_presi=c.cod_presi and  a.cod_entidad=c.cod_entidad and a.cod_tipo_inst=c.cod_tipo_inst and a.cod_inst=c.cod_inst and a.cod_dep=c.cod_dep and  a.cod_almacen=c.cod_almacen) as deno_almacen,
 (select a.denominacion from arrd05 a where  a.cod_presi=c.cod_presi and  a.cod_entidad=c.cod_entidad and a.cod_tipo_inst=c.cod_tipo_inst and a.cod_inst=c.cod_inst and a.cod_dep=c.cod_dep) as deno_dependencia,
 c.stock_minimo,
 c.stock_maximo,
 c.punto_pedido,
 c.estante_numero,
 c.fila_numero,
 c.columna_numero,
 c.complemento_sitio_almacenaje,
 c.numero_entradas,
 c.numero_salidas,
 c.costo_maximo,
 c.costo_minimo,
 c.fecha_registro,
 c.username_registro,
 c.cod_prod_serv,
 (select  a.denominacion from cscd01_catalogo a where a.codigo_prod_serv=c.cod_prod_serv) as denominacion,
 c.cod_snc,
  (select  a.cod_medida from cscd01_catalogo a where a.codigo_prod_serv=c.cod_prod_serv) as cod_medida
   FROM ciad01_inventario_productos c
   order by
 c.cod_presi,
 c.cod_entidad,
 c.cod_tipo_inst,
 c.cod_inst,
 c.cod_dep,
 c.cod_almacen,
 c.cod_prod_serv;

ALTER TABLE v_ciad01_inventario_productos OWNER TO sisap;



ALTER TABLE cscd05_ordencompra_nota_entrega_encabezado ADD COLUMN
transferido_almacen integer DEFAULT 2;
ALTER TABLE cscd05_ordencompra_nota_entrega_encabezado ADD COLUMN
inventariado_bienes integer DEFAULT 2;
COMMENT                                  ON                         COLUMN
cscd05_ordencompra_nota_entrega_encabezado.transferido_almacen IS 'Transferido
a un almacén?
1.- Si
2.- No
';
COMMENT                                  ON                         COLUMN
cscd05_ordencompra_nota_entrega_encabezado.inventariado_bienes IS 'Registrado
en Inventario de Bienes Mubles e Inmuebles?
1.- Si
2.- No
';

