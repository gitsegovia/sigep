SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.ano_reformulacion, a.numero_oficio, a.codi_dep, a.ano, a.cod_sector, a.cod_programa, a.cod_sub_prog, a.cod_proyecto, a.cod_activ_obra, a.cod_partida, a.cod_generica, a.cod_especifica, a.cod_sub_espec, a.cod_auxiliar, a.monto_disminucion, a.monto_aumento,
	( SELECT cfpd05.tipo_presupuesto FROM cfpd05 WHERE cfpd05.cod_presi = a.cod_presi AND cfpd05.cod_entidad = a.cod_entidad AND cfpd05.cod_tipo_inst = a.cod_tipo_inst AND cfpd05.cod_inst = a.cod_inst AND cfpd05.cod_dep = a.codi_dep AND cfpd05.ano = a.ano AND cfpd05.cod_sector = a.cod_sector AND cfpd05.cod_programa = a.cod_programa AND cfpd05.cod_sub_prog = a.cod_sub_prog AND cfpd05.cod_proyecto = a.cod_proyecto AND cfpd05.cod_activ_obra = a.cod_activ_obra AND cfpd05.cod_partida = a.cod_partida AND cfpd05.cod_generica = a.cod_generica AND cfpd05.cod_especifica = a.cod_especifica AND cfpd05.cod_sub_espec = a.cod_sub_espec AND cfpd05.cod_auxiliar = a.cod_auxiliar) AS tipo_presupuesto
FROM cfpd10_reformulacion_partidas a;


SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.ano_reformulacion, a.numero_oficio, a.cod_tipo, a.fecha_oficio, a.concepto, a.monto, a.encabezado_oficio, a.pie_oficio, a.nota_final_oficio, a.encabezado_decreto, a.pie_decreto, a.nota_final_decreto, a.numero_decreto, a.fecha_decreto, a.elaborado, a.revisado, a.por_enviar, a.enviado, a.por_remitir, a.remitido, a.por_aprobar, a.aprobado, a.numero_aprobacion, a.fecha_aprobacion, a.decretado,
	( SELECT count(b.tipo_presupuesto) AS count FROM v_cfpd10_reformulacion_partidas_tipopresupuesto b WHERE b.cod_presi = a.cod_presi AND b.cod_entidad = a.cod_entidad AND b.cod_tipo_inst = a.cod_tipo_inst AND b.cod_inst = a.cod_inst AND b.codi_dep = a.cod_dep AND b.ano_reformulacion = a.ano_reformulacion AND b.numero_oficio::text = a.numero_oficio::text AND b.tipo_presupuesto = 1) AS ordinario,
	( SELECT count(b.tipo_presupuesto) AS count FROM v_cfpd10_reformulacion_partidas_tipopresupuesto b WHERE b.cod_presi = a.cod_presi AND b.cod_entidad = a.cod_entidad AND b.cod_tipo_inst = a.cod_tipo_inst AND b.cod_inst = a.cod_inst AND b.codi_dep = a.cod_dep AND b.ano_reformulacion = a.ano_reformulacion AND b.numero_oficio::text = a.numero_oficio::text AND b.tipo_presupuesto = 2) AS coordinado,
	( SELECT count(b.tipo_presupuesto) AS count FROM v_cfpd10_reformulacion_partidas_tipopresupuesto b WHERE b.cod_presi = a.cod_presi AND b.cod_entidad = a.cod_entidad AND b.cod_tipo_inst = a.cod_tipo_inst AND b.cod_inst = a.cod_inst AND b.codi_dep = a.cod_dep AND b.ano_reformulacion = a.ano_reformulacion AND b.numero_oficio::text = a.numero_oficio::text AND b.tipo_presupuesto = 3) AS laee,
	( SELECT count(b.tipo_presupuesto) AS count FROM v_cfpd10_reformulacion_partidas_tipopresupuesto b WHERE b.cod_presi = a.cod_presi AND b.cod_entidad = a.cod_entidad AND b.cod_tipo_inst = a.cod_tipo_inst AND b.cod_inst = a.cod_inst AND b.codi_dep = a.cod_dep AND b.ano_reformulacion = a.ano_reformulacion AND b.numero_oficio::text = a.numero_oficio::text AND b.tipo_presupuesto = 4) AS fides,
	( SELECT count(b.tipo_presupuesto) AS count FROM v_cfpd10_reformulacion_partidas_tipopresupuesto b WHERE b.cod_presi = a.cod_presi AND b.cod_entidad = a.cod_entidad AND b.cod_tipo_inst = a.cod_tipo_inst AND b.cod_inst = a.cod_inst AND b.codi_dep = a.cod_dep AND b.ano_reformulacion = a.ano_reformulacion AND b.numero_oficio::text = a.numero_oficio::text AND b.tipo_presupuesto = 5) AS ingresos_extraordinarios
FROM cfpd10_reformulacion_texto a;


select * from v_cfpd10_reformulacion_texto_tipopresupuesto where ordinario > 0;

-- V -1.1

-- View: v_cfpd10_reformulacion_partidas_tipopresupuesto

-- DROP VIEW v_cfpd10_reformulacion_partidas_tipopresupuesto;

CREATE OR REPLACE VIEW v_cfpd10_reformulacion_partidas_tipopresupuesto AS
 SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.ano_reformulacion, a.numero_oficio, a.codi_dep, a.ano, a.cod_sector, a.cod_programa, a.cod_sub_prog, a.cod_proyecto, a.cod_activ_obra, a.cod_partida, a.cod_generica, a.cod_especifica, a.cod_sub_espec, a.cod_auxiliar, a.monto_disminucion, a.monto_aumento, ( SELECT cfpd05.tipo_presupuesto
           FROM cfpd05
          WHERE cfpd05.cod_presi = a.cod_presi AND cfpd05.cod_entidad = a.cod_entidad AND cfpd05.cod_tipo_inst = a.cod_tipo_inst AND cfpd05.cod_inst = a.cod_inst AND cfpd05.cod_dep = a.codi_dep AND cfpd05.ano = a.ano AND cfpd05.cod_sector = a.cod_sector AND cfpd05.cod_programa = a.cod_programa AND cfpd05.cod_sub_prog = a.cod_sub_prog AND cfpd05.cod_proyecto = a.cod_proyecto AND cfpd05.cod_activ_obra = a.cod_activ_obra AND cfpd05.cod_partida = a.cod_partida AND cfpd05.cod_generica = a.cod_generica AND cfpd05.cod_especifica = a.cod_especifica AND cfpd05.cod_sub_espec = a.cod_sub_espec AND cfpd05.cod_auxiliar = a.cod_auxiliar) AS tipo_presupuesto
   FROM cfpd10_reformulacion_partidas a;

ALTER TABLE v_cfpd10_reformulacion_partidas_tipopresupuesto OWNER TO sisap;
COMMENT ON VIEW v_cfpd10_reformulacion_partidas_tipopresupuesto IS 'vista usada para concatenar el campo del tipo de presupuesto a las partidas de la tabla de reformulacion partidas.';




-- V -1.2

-- View: v_cfpd10_reformulacion_partidas_tipopresupuesto

-- DROP VIEW v_cfpd10_reformulacion_partidas_tipopresupuesto;

CREATE OR REPLACE VIEW v_cfpd10_reformulacion_partidas_tipopresupuesto AS
 SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.ano_reformulacion, a.numero_oficio, a.codi_dep, a.ano, a.cod_sector, a.cod_programa, a.cod_sub_prog, a.cod_proyecto, a.cod_activ_obra, a.cod_partida, a.cod_generica, a.cod_especifica, a.cod_sub_espec, a.cod_auxiliar, a.monto_disminucion, a.monto_aumento, ( SELECT cfpd05.tipo_presupuesto
           FROM cfpd05
          WHERE cfpd05.cod_presi = a.cod_presi AND cfpd05.cod_entidad = a.cod_entidad AND cfpd05.cod_tipo_inst = a.cod_tipo_inst AND cfpd05.cod_inst = a.cod_inst AND cfpd05.cod_dep = a.codi_dep AND cfpd05.ano = a.ano AND cfpd05.cod_sector = a.cod_sector AND cfpd05.cod_programa = a.cod_programa AND cfpd05.cod_sub_prog = a.cod_sub_prog AND cfpd05.cod_proyecto = a.cod_proyecto AND cfpd05.cod_activ_obra = a.cod_activ_obra AND cfpd05.cod_partida = a.cod_partida AND cfpd05.cod_generica = a.cod_generica AND cfpd05.cod_especifica = a.cod_especifica AND cfpd05.cod_sub_espec = a.cod_sub_espec AND cfpd05.cod_auxiliar = a.cod_auxiliar) AS tipo_presupuesto, ( SELECT b.numero_oficio
           FROM cfpd10_reformulacion_texto b
          WHERE b.cod_presi = a.cod_presi AND b.cod_entidad = a.cod_entidad AND b.cod_tipo_inst = a.cod_tipo_inst AND b.cod_inst = a.cod_inst AND b.cod_dep = a.cod_dep AND b.ano_reformulacion = a.ano_reformulacion AND b.numero_oficio::text = a.numero_oficio::text) AS numero_oficio_texto, ( SELECT b.fecha_oficio
           FROM cfpd10_reformulacion_texto b
          WHERE b.cod_presi = a.cod_presi AND b.cod_entidad = a.cod_entidad AND b.cod_tipo_inst = a.cod_tipo_inst AND b.cod_inst = a.cod_inst AND b.cod_dep = a.cod_dep AND b.ano_reformulacion = a.ano_reformulacion AND b.numero_oficio::text = a.numero_oficio::text) AS fecha_oficio, ( SELECT b.concepto
           FROM cfpd10_reformulacion_texto b
          WHERE b.cod_presi = a.cod_presi AND b.cod_entidad = a.cod_entidad AND b.cod_tipo_inst = a.cod_tipo_inst AND b.cod_inst = a.cod_inst AND b.cod_dep = a.cod_dep AND b.ano_reformulacion = a.ano_reformulacion AND b.numero_oficio::text = a.numero_oficio::text) AS concepto, ( SELECT b.monto
           FROM cfpd10_reformulacion_texto b
          WHERE b.cod_presi = a.cod_presi AND b.cod_entidad = a.cod_entidad AND b.cod_tipo_inst = a.cod_tipo_inst AND b.cod_inst = a.cod_inst AND b.cod_dep = a.cod_dep AND b.ano_reformulacion = a.ano_reformulacion AND b.numero_oficio::text = a.numero_oficio::text) AS monto, ( SELECT b.numero_aprobacion
           FROM cfpd10_reformulacion_texto b
          WHERE b.cod_presi = a.cod_presi AND b.cod_entidad = a.cod_entidad AND b.cod_tipo_inst = a.cod_tipo_inst AND b.cod_inst = a.cod_inst AND b.cod_dep = a.cod_dep AND b.ano_reformulacion = a.ano_reformulacion AND b.numero_oficio::text = a.numero_oficio::text) AS numero_aprobacion, ( SELECT b.fecha_aprobacion
           FROM cfpd10_reformulacion_texto b
          WHERE b.cod_presi = a.cod_presi AND b.cod_entidad = a.cod_entidad AND b.cod_tipo_inst = a.cod_tipo_inst AND b.cod_inst = a.cod_inst AND b.cod_dep = a.cod_dep AND b.ano_reformulacion = a.ano_reformulacion AND b.numero_oficio::text = a.numero_oficio::text) AS fecha_aprobacion, ( SELECT b.numero_decreto
           FROM cfpd10_reformulacion_texto b
          WHERE b.cod_presi = a.cod_presi AND b.cod_entidad = a.cod_entidad AND b.cod_tipo_inst = a.cod_tipo_inst AND b.cod_inst = a.cod_inst AND b.cod_dep = a.cod_dep AND b.ano_reformulacion = a.ano_reformulacion AND b.numero_oficio::text = a.numero_oficio::text) AS numero_decreto, ( SELECT b.fecha_decreto
           FROM cfpd10_reformulacion_texto b
          WHERE b.cod_presi = a.cod_presi AND b.cod_entidad = a.cod_entidad AND b.cod_tipo_inst = a.cod_tipo_inst AND b.cod_inst = a.cod_inst AND b.cod_dep = a.cod_dep AND b.ano_reformulacion = a.ano_reformulacion AND b.numero_oficio::text = a.numero_oficio::text) AS fecha_decreto, ( SELECT b.cod_tipo
           FROM cfpd10_reformulacion_texto b
          WHERE b.cod_presi = a.cod_presi AND b.cod_entidad = a.cod_entidad AND b.cod_tipo_inst = a.cod_tipo_inst AND b.cod_inst = a.cod_inst AND b.cod_dep = a.cod_dep AND b.ano_reformulacion = a.ano_reformulacion AND b.numero_oficio::text = a.numero_oficio::text) AS cod_tipo, ( SELECT b.aprobado
           FROM cfpd10_reformulacion_texto b
          WHERE b.cod_presi = a.cod_presi AND b.cod_entidad = a.cod_entidad AND b.cod_tipo_inst = a.cod_tipo_inst AND b.cod_inst = a.cod_inst AND b.cod_dep = a.cod_dep AND b.ano_reformulacion = a.ano_reformulacion AND b.numero_oficio::text = a.numero_oficio::text) AS aprobado
   FROM cfpd10_reformulacion_partidas a;

ALTER TABLE v_cfpd10_reformulacion_partidas_tipopresupuesto OWNER TO sisap;




