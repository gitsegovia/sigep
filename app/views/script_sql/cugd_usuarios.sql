CREATE TABLE cugd_usuarios
(
  correo_electronico character varying(200) NOT NULL,
  "password" character varying(60) NOT NULL,
  apellidos character varying(60) NOT NULL,
  nombres character varying(60) NOT NULL,
  cedula_identidad integer NOT NULL,
  CONSTRAINT cugd_usuarios_pkey PRIMARY KEY (correo_electronico)
)
WITH (OIDS=FALSE);
ALTER TABLE cugd_usuarios OWNER TO sisap;
