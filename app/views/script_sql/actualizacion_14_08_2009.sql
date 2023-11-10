
ALTER TABLE cfpd05 ADD COLUMN compromiso_ano_anterior integer DEFAULT 0;
COMMENT ON COLUMN cfpd05.compromiso_ano_anterior IS 'Partida proveniente de compromiso de año anterior?
0.- No
1.- Si
';

ALTER TABLE cfpd05 ADD COLUMN cod_ramo integer;
ALTER TABLE cfpd05 ADD COLUMN cod_subramo integer;
ALTER TABLE cfpd05 ADD COLUMN cod_esp integer;
ALTER TABLE cfpd05 ADD COLUMN cod_subesp integer;
ALTER TABLE cfpd05 ADD COLUMN cod_aux integer;


COMMENT ON COLUMN cfpd05.cod_ramo IS 'cod_ramo = cod_partida';
COMMENT ON COLUMN cfpd05.cod_subramo IS 'cod_subramo = cod_generica';
COMMENT ON COLUMN cfpd05.cod_esp IS 'cod_esp = cod_especifica';
COMMENT ON COLUMN cfpd05.cod_subesp IS 'cod_subesp = cod_sub_espec';
COMMENT ON COLUMN cfpd05.cod_aux IS 'cod_aux = cod_auxiliar';
