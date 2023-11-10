--//todo fino aqui con esta tabla
UPDATE casd01_solicitud_ayuda SET cod_tipo_ayuda=2 WHERE cod_tipo_ayuda in (26,31,43,44,45,46,49,57,78);
UPDATE casd01_solicitud_ayuda SET cod_tipo_ayuda=3 WHERE cod_tipo_ayuda in (56,67);
UPDATE casd01_solicitud_ayuda SET cod_tipo_ayuda=4 WHERE cod_tipo_ayuda in (74);
UPDATE casd01_solicitud_ayuda SET cod_tipo_ayuda=5 WHERE cod_tipo_ayuda in (63,65,76);
UPDATE casd01_solicitud_ayuda SET cod_tipo_ayuda=6 WHERE cod_tipo_ayuda in (25,50);
UPDATE casd01_solicitud_ayuda SET cod_tipo_ayuda=16 WHERE cod_tipo_ayuda in (17,22,27,33,35,38);
UPDATE casd01_solicitud_ayuda SET cod_tipo_ayuda=20 WHERE cod_tipo_ayuda in (40,41);
UPDATE casd01_solicitud_ayuda SET cod_tipo_ayuda=24 WHERE cod_tipo_ayuda in (42,75);
UPDATE casd01_solicitud_ayuda SET cod_tipo_ayuda=30 WHERE cod_tipo_ayuda in (59,60);
UPDATE casd01_solicitud_ayuda SET cod_tipo_ayuda=47 WHERE cod_tipo_ayuda in (52,69,70,80,81,82,58);
UPDATE casd01_solicitud_ayuda SET cod_tipo_ayuda=51 WHERE cod_tipo_ayuda in (53);
UPDATE casd01_solicitud_ayuda SET cod_tipo_ayuda=54 WHERE cod_tipo_ayuda in (66);
UPDATE casd01_solicitud_ayuda SET cod_tipo_ayuda=61 WHERE cod_tipo_ayuda in (62);

UPDATE casd01_evaluacion_ayuda SET cod_tipo_ayuda=2 WHERE cod_tipo_ayuda in (26,31,43,44,45,46,49,57,78);
UPDATE casd01_evaluacion_ayuda SET cod_tipo_ayuda=3 WHERE cod_tipo_ayuda in (56,67);
UPDATE casd01_evaluacion_ayuda SET cod_tipo_ayuda=4 WHERE cod_tipo_ayuda in (74);
UPDATE casd01_evaluacion_ayuda SET cod_tipo_ayuda=5 WHERE cod_tipo_ayuda in (63,65,76);
UPDATE casd01_evaluacion_ayuda SET cod_tipo_ayuda=6 WHERE cod_tipo_ayuda in (25,50);
UPDATE casd01_evaluacion_ayuda SET cod_tipo_ayuda=16 WHERE cod_tipo_ayuda in (17,22,27,33,35,38);
UPDATE casd01_evaluacion_ayuda SET cod_tipo_ayuda=20 WHERE cod_tipo_ayuda in (40,41);
UPDATE casd01_evaluacion_ayuda SET cod_tipo_ayuda=24 WHERE cod_tipo_ayuda in (42,75);
UPDATE casd01_evaluacion_ayuda SET cod_tipo_ayuda=30 WHERE cod_tipo_ayuda in (59,60);
UPDATE casd01_evaluacion_ayuda SET cod_tipo_ayuda=47 WHERE cod_tipo_ayuda in (52,69,70,80,81,82,58);
UPDATE casd01_evaluacion_ayuda SET cod_tipo_ayuda=51 WHERE cod_tipo_ayuda in (53);
UPDATE casd01_evaluacion_ayuda SET cod_tipo_ayuda=54 WHERE cod_tipo_ayuda in (66);
UPDATE casd01_evaluacion_ayuda SET cod_tipo_ayuda=61 WHERE cod_tipo_ayuda in (62);

ALTER TABLE casd01_ayuda_detalles DROP CONSTRAINT cscd01_ayuda_detalles_1;



UPDATE casd01_ayuda_detalles SET cod_tipo_ayuda=2 WHERE cod_tipo_ayuda in (26,31,43,44,45,46,49,57,78);
UPDATE casd01_ayuda_detalles SET cod_tipo_ayuda=3 WHERE cod_tipo_ayuda in (56,67);
UPDATE casd01_ayuda_detalles SET cod_tipo_ayuda=4 WHERE cod_tipo_ayuda in (74);
UPDATE casd01_ayuda_detalles SET cod_tipo_ayuda=5 WHERE cod_tipo_ayuda in (63,65,76);
UPDATE casd01_ayuda_detalles SET cod_tipo_ayuda=6 WHERE cod_tipo_ayuda in (25,50);
UPDATE casd01_ayuda_detalles SET cod_tipo_ayuda=16 WHERE cod_tipo_ayuda in (17,22,27,33,35,38);
UPDATE casd01_ayuda_detalles SET cod_tipo_ayuda=20 WHERE cod_tipo_ayuda in (40,41);
UPDATE casd01_ayuda_detalles SET cod_tipo_ayuda=24 WHERE cod_tipo_ayuda in (42,75);
UPDATE casd01_ayuda_detalles SET cod_tipo_ayuda=30 WHERE cod_tipo_ayuda in (59,60);
UPDATE casd01_ayuda_detalles SET cod_tipo_ayuda=47 WHERE cod_tipo_ayuda in (52,69,70,80,81,82,58);
UPDATE casd01_ayuda_detalles SET cod_tipo_ayuda=51 WHERE cod_tipo_ayuda in (53);
UPDATE casd01_ayuda_detalles SET cod_tipo_ayuda=54 WHERE cod_tipo_ayuda in (66);
UPDATE casd01_ayuda_detalles SET cod_tipo_ayuda=61 WHERE cod_tipo_ayuda in (62);


UPDATE casd01_ayudas_cuerpo SET cod_tipo_ayuda=2 WHERE cod_tipo_ayuda in (26,31,43,44,45,46,49,57,78);
UPDATE casd01_ayudas_cuerpo SET cod_tipo_ayuda=3 WHERE cod_tipo_ayuda in (56,67);
UPDATE casd01_ayudas_cuerpo SET cod_tipo_ayuda=4 WHERE cod_tipo_ayuda in (74);
UPDATE casd01_ayudas_cuerpo SET cod_tipo_ayuda=5 WHERE cod_tipo_ayuda in (63,65,76);
UPDATE casd01_ayudas_cuerpo SET cod_tipo_ayuda=6 WHERE cod_tipo_ayuda in (25,50);
UPDATE casd01_ayudas_cuerpo SET cod_tipo_ayuda=16 WHERE cod_tipo_ayuda in (17,22,27,33,35,38);
UPDATE casd01_ayudas_cuerpo SET cod_tipo_ayuda=20 WHERE cod_tipo_ayuda in (40,41);
UPDATE casd01_ayudas_cuerpo SET cod_tipo_ayuda=24 WHERE cod_tipo_ayuda in (42,75);
UPDATE casd01_ayudas_cuerpo SET cod_tipo_ayuda=30 WHERE cod_tipo_ayuda in (59,60);
UPDATE casd01_ayudas_cuerpo SET cod_tipo_ayuda=47 WHERE cod_tipo_ayuda in (52,69,70,80,81,82,58);
UPDATE casd01_ayudas_cuerpo SET cod_tipo_ayuda=51 WHERE cod_tipo_ayuda in (53);
UPDATE casd01_ayudas_cuerpo SET cod_tipo_ayuda=54 WHERE cod_tipo_ayuda in (66);
UPDATE casd01_ayudas_cuerpo SET cod_tipo_ayuda=61 WHERE cod_tipo_ayuda in (62);

ALTER TABLE casd01_ayuda_detalles
  ADD CONSTRAINT cscd01_ayuda_detalles_1 FOREIGN KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cedula_identidad, cod_tipo_ayuda, numero_ocacion, numero_documento_evaluacion, numero_documento_ayuda)
      REFERENCES casd01_ayudas_cuerpo (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cedula_identidad, cod_tipo_ayuda, numero_ocacion, numero_documento_evaluacion, numero_documento_ayuda) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE CASCADE;


DELETE FROM casd01_tipo_ayuda
 WHERE cod_tipo_ayuda in
 (26,31,43,44,45,46,49,57,78,
 56,67,
 74,
 63,65,76,
 25,50,
 17,22,27,33,35,38,
 40,41,
 42,75,
 59,60,
 52,69,70,80,81,82,58,
 53,66,62);
