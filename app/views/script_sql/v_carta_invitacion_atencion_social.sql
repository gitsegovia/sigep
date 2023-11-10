








CREATE OR REPLACE VIEW v_casd01_datos_personales_existe_casd01_ayudas_cuerpo AS


SELECT


          a.cedula_identidad,
		  a.nacionalidad,
		  a.apellidos_nombres,
		  a.fecha_nacimiento,
		  a.sexo,
		  a.estado_civil,
		  a.peso,
		  a.estatura,
		  a.grupo_sanguineo,
		  a.cod_profesion,
		  a.cod_oficio,
		  a.cod_ambito,
		  a.cod_zona,
		  a.cod_vivienda,
		  a.cod_estado,
		  a.cod_municipio,
		  a.cod_parroquia,
		  a.cod_centro_poblado,

		  (SELECT xya.denominacion FROM cugd01_estados          xya where xya.cod_republica=a.cod_presi and xya.cod_estado=a.cod_estado                                                                                                                          GROUP BY xya.denominacion) as  deno_cod_estado,
		  (SELECT xyb.denominacion FROM cugd01_municipios       xyb where xyb.cod_republica=a.cod_presi and xyb.cod_estado=a.cod_estado  and xyb.cod_municipio=a.cod_municipio                                                                                   GROUP BY xyb.denominacion) as  deno_cod_municipio,
		  (SELECT xyc.denominacion FROM cugd01_parroquias       xyc where xyc.cod_republica=a.cod_presi and xyc.cod_estado=a.cod_estado  and xyc.cod_municipio=a.cod_municipio and xyc.cod_parroquia = a.cod_parroquia                                           GROUP BY xyc.denominacion) as  deno_cod_parroquia,
		  (SELECT xyd.denominacion FROM cugd01_centros_poblados xyd where xyd.cod_republica=a.cod_presi and xyd.cod_estado=a.cod_estado  and xyd.cod_municipio=a.cod_municipio and xyd.cod_parroquia = a.cod_parroquia and xyd.cod_centro = a.cod_centro_poblado GROUP BY xyd.denominacion) as  deno_cod_centro,


		  a.direccion_habitacion,
		  a.telefonos_fijos,
		  a.telefonos_movil,
		  a.fecha_inscripcion,
		  a.cod_presi,
		  a.cod_entidad,
		  a.cod_tipo_inst,
		  a.cod_inst,
		  a.cod_dep,
		  a.cod_tenencia_vivienda,
		  a.anos_residencia,
		  a.monto_alquiler_hipoteca,
		  a.cod_mision,
		  a.username,
		  a.cedula_usuario,
		  a.nombre_usuario,
		  (SELECT
			          COUNT( ba.cedula_identidad ) as partidas

			    from casd01_ayudas_cuerpo ba

			    WHERE

			          ba.cod_presi          =  a.cod_presi  and
					  ba.cod_entidad        =  a.cod_entidad  and
					  ba.cod_tipo_inst      =  a.cod_tipo_inst  and
					  ba.cod_inst           =  a.cod_inst  and
					  ba.cod_dep            =  a.cod_dep  and
					  ba.cedula_identidad   =  a.cedula_identidad

			  ) as  aparece_en_casd01_ayudas_cuerpo



FROM casd01_datos_personales a;




ALTER TABLE v_casd01_datos_personales_existe_casd01_ayudas_cuerpo OWNER TO sisap;


