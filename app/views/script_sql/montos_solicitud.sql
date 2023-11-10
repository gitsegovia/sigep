SELECT b.*,
       (SELECT  sum(monto)
  FROM csrd01_solicitud_recurso_partidas a where a.cod_presi=b.cod_presi and a.cod_entidad=b.cod_entidad and a.cod_tipo_inst=b.cod_tipo_inst and a.cod_inst=b.cod_inst and a.cod_dep=b.cod_dep and a.ano_solicitud=b.ano_solicitud
       and a.numero_solicitud=b.numero_solicitud) as suma_partidas
  FROM csrd01_solicitud_recurso_cuerpo b where b.monto_solicitado!=(SELECT  sum(monto)
  FROM csrd01_solicitud_recurso_partidas a where a.cod_presi=b.cod_presi and a.cod_entidad=b.cod_entidad and a.cod_tipo_inst=b.cod_tipo_inst and a.cod_inst=b.cod_inst and a.cod_dep=b.cod_dep and a.ano_solicitud=b.ano_solicitud
       and a.numero_solicitud=b.numero_solicitud);


UPDATE csrd01_solicitud_recurso_cuerpo b SET monto_solicitado=(SELECT  sum(a.monto)
  FROM csrd01_solicitud_recurso_partidas a where a.cod_presi=b.cod_presi and a.cod_entidad=b.cod_entidad and a.cod_tipo_inst=b.cod_tipo_inst and a.cod_inst=b.cod_inst and a.cod_dep=b.cod_dep and a.ano_solicitud=b.ano_solicitud
       and a.numero_solicitud=b.numero_solicitud) where b.monto_solicitado!=(SELECT  sum(monto)
  FROM csrd01_solicitud_recurso_partidas a where a.cod_presi=b.cod_presi and a.cod_entidad=b.cod_entidad and a.cod_tipo_inst=b.cod_tipo_inst and a.cod_inst=b.cod_inst and a.cod_dep=b.cod_dep and a.ano_solicitud=b.ano_solicitud
       and a.numero_solicitud=b.numero_solicitud);