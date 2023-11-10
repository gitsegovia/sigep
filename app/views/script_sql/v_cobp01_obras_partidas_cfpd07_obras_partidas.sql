

DROP VIEW v_cobp01_obras_partidas_cfpd07_obras_partidas;

CREATE OR REPLACE VIEW v_cobp01_obras_partidas_cfpd07_obras_partidas AS 


SELECT

          a.cod_presi,
	  a.cod_entidad,
	  a.cod_tipo_inst,
	  a.cod_inst,
	  a.cod_dep,
          (SELECT 
                     bx.cod_dep_original
              
		FROM cobd01_contratoobras_cuerpo ax, cfpd07_obras_cuerpo bx


		WHERE 
		       
  			   ax.cod_presi            =  a.cod_presi                and
			   ax.cod_entidad          =  a.cod_entidad              and
			   ax.cod_tipo_inst        =  a.cod_tipo_inst            and
			   ax.cod_inst             =  a.cod_inst                 and
			   ax.cod_dep              =  a.cod_dep                  and
		           ax.ano_contrato_obra    =  a.ano_contrato_obra        and
                           ax.numero_contrato_obra =  a.numero_contrato_obra     and
                           bx.cod_presi            =  ax.cod_presi               and
			   bx.cod_entidad          =  ax.cod_entidad             and
			   bx.cod_tipo_inst        =  ax.cod_tipo_inst           and
			   bx.cod_inst             =  ax.cod_inst                and
			   bx.cod_dep              =  ax.cod_dep                 and
			   bx.ano_estimacion       =  ax.ano_estimacion          and
			   bx.cod_obra             =  ax.cod_obra   


          )as cod_dep_original,


          (SELECT 
                     ax.cod_obra
              
		FROM cobd01_contratoobras_cuerpo ax


		WHERE 
		       
  			   ax.cod_presi            =  a.cod_presi                and
			   ax.cod_entidad          =  a.cod_entidad              and
			   ax.cod_tipo_inst        =  a.cod_tipo_inst            and
			   ax.cod_inst             =  a.cod_inst                 and
			   ax.cod_dep              =  a.cod_dep                  and
		           ax.ano_contrato_obra    =  a.ano_contrato_obra        and
                           ax.numero_contrato_obra =  a.numero_contrato_obra     


          )as cod_obra,
          
          (SELECT 
                     ax.condicion_actividad
              
		FROM cobd01_contratoobras_cuerpo ax


		WHERE 
		       
  			   ax.cod_presi            =  a.cod_presi                and
			   ax.cod_entidad          =  a.cod_entidad              and
			   ax.cod_tipo_inst        =  a.cod_tipo_inst            and
			   ax.cod_inst             =  a.cod_inst                 and
			   ax.cod_dep              =  a.cod_dep                  and
		           ax.ano_contrato_obra    =  a.ano_contrato_obra        and
                           ax.numero_contrato_obra =  a.numero_contrato_obra     


          )as condicion_actividad,


          a.ano_contrato_obra,
          a.numero_contrato_obra,
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
	  a.aumento,
	  a.disminucion,
	  a.anticipo,
	  a.amortizacion,
	  a.retencion_laboral,
	  a.retencion_fielcumplimiento,
	  a.cancelacion 
	 
	 


FROM cobd01_contratoobras_partidas a

         

ORDER BY

           a.cod_presi,
	   a.cod_entidad,
	   a.cod_tipo_inst,
	   a.cod_inst,
           a.cod_dep,
           cod_dep_original,
           a.ano_contrato_obra,
	   a.numero_contrato_obra;



ALTER TABLE v_cobp01_obras_partidas_cfpd07_obras_partidas OWNER TO sisap;


                            
          
         



