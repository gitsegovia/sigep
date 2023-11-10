

DROP VIEW v_usuarios;



CREATE VIEW v_usuarios AS


select

      a.username,
	  a.password,
	  a.cod_presi,
	  a.cod_entidad,
	  a.cod_tipo_inst,
	  a.cod_inst,
	  a.cod_dep,
      (SELECT denominacion FROM cugd02_dependencias b WHERE b.cod_tipo_institucion = a.cod_tipo_inst and b.cod_institucion  =  a.cod_inst and b.cod_dependencia   =  a.cod_dep) as denominacion_dep,
	  a.modulo,
	  a.funcionario,
	  a.cedula_identidad,
	  a.cod_dep_original,
	  a.condicion_actividad,
	  a.cod_dir_superior,
	  a.cod_coordinacion,
	  a.cod_secretaria,
	  a.cod_direccion,
	  a.cod_division,
	  a.cod_departamento,
	  a.cod_oficina,
      (SELECT xa.denominacion FROM cugd02_direccionsuperior xa where xa.cod_tipo_institucion=a.cod_tipo_inst and xa.cod_institucion=a.cod_inst and  xa.cod_dependencia=a.cod_dep and xa.cod_dir_superior=a.cod_dir_superior                                                                                                                                                                                                                                        GROUP BY xa.denominacion) as  deno_cod_dir_superior,
	  (SELECT xb.denominacion FROM cugd02_coordinacion xb      where xb.cod_tipo_institucion=a.cod_tipo_inst and xb.cod_institucion=a.cod_inst and  xb.cod_dependencia=a.cod_dep and xb.cod_dir_superior=a.cod_dir_superior and xb.cod_coordinacion=a.cod_coordinacion                                                                                                                                                                                             GROUP BY xb.denominacion) as  deno_cod_coordinacion,
	  (SELECT xc.denominacion FROM cugd02_secretaria xc        where xc.cod_tipo_institucion=a.cod_tipo_inst and xc.cod_institucion=a.cod_inst and  xc.cod_dependencia=a.cod_dep and xc.cod_dir_superior=a.cod_dir_superior and xc.cod_coordinacion=a.cod_coordinacion and xc.cod_secretaria=a.cod_secretaria                                                                                                                                                      GROUP BY xc.denominacion) as  deno_cod_secretaria,
	  (SELECT xd.denominacion FROM cugd02_direccion xd         where xd.cod_tipo_institucion=a.cod_tipo_inst and xd.cod_institucion=a.cod_inst and  xd.cod_dependencia=a.cod_dep and xd.cod_dir_superior=a.cod_dir_superior and xd.cod_coordinacion=a.cod_coordinacion and xd.cod_secretaria=a.cod_secretaria and xd.cod_direccion=a.cod_direccion                                                                                                                 GROUP BY xd.denominacion) as  deno_cod_direccion,
	  (SELECT xe.denominacion FROM cugd02_division xe          where xe.cod_tipo_institucion=a.cod_tipo_inst and xe.cod_institucion=a.cod_inst and  xe.cod_dependencia=a.cod_dep and xe.cod_dir_superior=a.cod_dir_superior and xe.cod_coordinacion=a.cod_coordinacion and xe.cod_secretaria=a.cod_secretaria and xe.cod_direccion=a.cod_direccion  and xe.cod_division=a.cod_division                                                                             GROUP BY xe.denominacion) as  deno_cod_division,
	  (SELECT xf.denominacion FROM cugd02_departamento xf      where xf.cod_tipo_institucion=a.cod_tipo_inst and xf.cod_institucion=a.cod_inst and  xf.cod_dependencia=a.cod_dep and xf.cod_dir_superior=a.cod_dir_superior and xf.cod_coordinacion=a.cod_coordinacion and xf.cod_secretaria=a.cod_secretaria and xf.cod_direccion=a.cod_direccion  and xf.cod_division=a.cod_division and xf.cod_departamento=a.cod_departamento                                  GROUP BY xf.denominacion) as  deno_cod_departamento,
	  (SELECT xg.denominacion FROM cugd02_oficina xg           where xg.cod_tipo_institucion=a.cod_tipo_inst and xg.cod_institucion=a.cod_inst and  xg.cod_dependencia=a.cod_dep and xg.cod_dir_superior=a.cod_dir_superior and xg.cod_coordinacion=a.cod_coordinacion and xg.cod_secretaria=a.cod_secretaria and xg.cod_direccion=a.cod_direccion  and xg.cod_division=a.cod_division and xg.cod_departamento=a.cod_departamento and xg.cod_oficina=a.cod_oficina GROUP BY xg.denominacion) as  deno_cod_oficina


from

  usuarios a;


ALTER TABLE v_usuarios OWNER TO sisap;



