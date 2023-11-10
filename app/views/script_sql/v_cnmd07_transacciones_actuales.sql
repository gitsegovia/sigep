


-- DROP VIEW v_cnmd07_transacciones_actuales;

CREATE OR REPLACE VIEW v_cnmd07_transacciones_actuales AS


SELECT

      a.cod_presi,
	  a.cod_entidad,
	  a.cod_tipo_inst,
	  a.cod_inst,
	  a.cod_dep,

      (SELECT denominacion FROM cugd02_dependencias b WHERE b.cod_tipo_institucion = a.cod_tipo_inst and b.cod_institucion      = a.cod_inst      and b.cod_dependencia      = a.cod_dep ) as denominacion_dep,

	  a.cod_tipo_nomina,

      (SELECT b.correspondiente        FROM cnmd01 b WHERE	  b.cod_presi         =      a.cod_presi        and b.cod_entidad       =      a.cod_entidad      and b.cod_tipo_inst     =      a.cod_tipo_inst    and b.cod_inst          =      a.cod_inst         and b.cod_dep           =      a.cod_dep          and b.cod_tipo_nomina   =      a.cod_tipo_nomina ) as   correspondiente,
      (SELECT b.denominacion           FROM cnmd01 b WHERE	  b.cod_presi         =      a.cod_presi        and b.cod_entidad       =      a.cod_entidad      and b.cod_tipo_inst     =      a.cod_tipo_inst    and b.cod_inst          =      a.cod_inst         and b.cod_dep           =      a.cod_dep          and b.cod_tipo_nomina   =      a.cod_tipo_nomina ) as   denominacion_nomina,
      (SELECT b.numero_nomina          FROM cnmd01 b WHERE	  b.cod_presi         =      a.cod_presi        and b.cod_entidad       =      a.cod_entidad      and b.cod_tipo_inst     =      a.cod_tipo_inst    and b.cod_inst          =      a.cod_inst         and b.cod_dep           =      a.cod_dep          and b.cod_tipo_nomina   =      a.cod_tipo_nomina ) as   numero_nomina,
      (SELECT b.periodo_desde          FROM cnmd01 b WHERE	  b.cod_presi         =      a.cod_presi        and b.cod_entidad       =      a.cod_entidad      and b.cod_tipo_inst     =      a.cod_tipo_inst    and b.cod_inst          =      a.cod_inst         and b.cod_dep           =      a.cod_dep          and b.cod_tipo_nomina   =      a.cod_tipo_nomina ) as   periodo_desde,
      (SELECT b.periodo_hasta          FROM cnmd01 b WHERE	  b.cod_presi         =      a.cod_presi        and b.cod_entidad       =      a.cod_entidad      and b.cod_tipo_inst     =      a.cod_tipo_inst    and b.cod_inst          =      a.cod_inst         and b.cod_dep           =      a.cod_dep          and b.cod_tipo_nomina   =      a.cod_tipo_nomina ) as   periodo_hasta,
      (SELECT b.frecuencia_cobro       FROM cnmd01 b WHERE	  b.cod_presi         =      a.cod_presi        and b.cod_entidad       =      a.cod_entidad      and b.cod_tipo_inst     =      a.cod_tipo_inst    and b.cod_inst          =      a.cod_inst         and b.cod_dep           =      a.cod_dep          and b.cod_tipo_nomina   =      a.cod_tipo_nomina ) as   frecuencia_cobro,
      (SELECT b.clasificacion_personal FROM cnmd01 b WHERE	  b.cod_presi         =      a.cod_presi        and b.cod_entidad       =      a.cod_entidad      and b.cod_tipo_inst     =      a.cod_tipo_inst    and b.cod_inst          =      a.cod_inst         and b.cod_dep           =      a.cod_dep          and b.cod_tipo_nomina   =      a.cod_tipo_nomina ) as   clasificacion_personal,
      (SELECT b.frecuencia_pago        FROM cnmd01 b WHERE	  b.cod_presi         =      a.cod_presi        and b.cod_entidad       =      a.cod_entidad      and b.cod_tipo_inst     =      a.cod_tipo_inst    and b.cod_inst          =      a.cod_inst         and b.cod_dep           =      a.cod_dep          and b.cod_tipo_nomina   =      a.cod_tipo_nomina ) as   frecuencia_pago,


	  a.cod_cargo,
	  a.cod_ficha,
      (SELECT b.cedula_identidad
       FROM cnmd06_fichas b
       WHERE	  b.cod_presi         =      a.cod_presi        and
		  b.cod_entidad       =      a.cod_entidad      and
		  b.cod_tipo_inst     =      a.cod_tipo_inst    and
		  b.cod_inst          =      a.cod_inst         and
		  b.cod_dep           =      a.cod_dep          and
		  b.cod_tipo_nomina   =      a.cod_tipo_nomina  and
		  b.cod_cargo         =      a.cod_cargo        and
		  b.cod_ficha         =      a.cod_ficha
       ) as   cedula_identidad,




       (SELECT b.fecha_ingreso
       FROM cnmd06_fichas b
       WHERE	  b.cod_presi         =      a.cod_presi        and
		  b.cod_entidad       =      a.cod_entidad      and
		  b.cod_tipo_inst     =      a.cod_tipo_inst    and
		  b.cod_inst          =      a.cod_inst         and
		  b.cod_dep           =      a.cod_dep          and
		  b.cod_tipo_nomina   =      a.cod_tipo_nomina  and
		  b.cod_cargo         =      a.cod_cargo        and
		  b.cod_ficha         =      a.cod_ficha
       ) as   fecha_ingreso,



      (SELECT b.ultimo_recibo
           FROM cnmd06_fichas b
           WHERE	  b.cod_presi         =      a.cod_presi        and
			  b.cod_entidad       =      a.cod_entidad      and
			  b.cod_tipo_inst     =      a.cod_tipo_inst    and
			  b.cod_inst          =      a.cod_inst         and
			  b.cod_dep           =      a.cod_dep          and
			  b.cod_tipo_nomina   =      a.cod_tipo_nomina  and
			  b.cod_cargo         =      a.cod_cargo        and
			  b.cod_ficha         =      a.cod_ficha
        ) as   ultimo_recibo,



        (SELECT b.cod_entidad_bancaria
           FROM cnmd06_fichas b
           WHERE	  b.cod_presi         =      a.cod_presi        and
			  b.cod_entidad       =      a.cod_entidad      and
			  b.cod_tipo_inst     =      a.cod_tipo_inst    and
			  b.cod_inst          =      a.cod_inst         and
			  b.cod_dep           =      a.cod_dep          and
			  b.cod_tipo_nomina   =      a.cod_tipo_nomina  and
			  b.cod_cargo         =      a.cod_cargo        and
			  b.cod_ficha         =      a.cod_ficha
        ) as   cod_entidad_bancaria,

        (SELECT x.denominacion FROM cstd01_entidades_bancarias x WHERE
            x.cod_entidad_bancaria = (SELECT b.cod_entidad_bancaria FROM cnmd06_fichas b WHERE
                                          b.cod_presi         =      a.cod_presi        and
										  b.cod_entidad       =      a.cod_entidad      and
										  b.cod_tipo_inst     =      a.cod_tipo_inst    and
										  b.cod_inst          =      a.cod_inst         and
										  b.cod_dep           =      a.cod_dep          and
										  b.cod_tipo_nomina   =      a.cod_tipo_nomina  and
										  b.cod_cargo         =      a.cod_cargo        and
										  b.cod_ficha         =      a.cod_ficha
							           )
         ) as deno_entidades_bancarias,



        (SELECT b.cod_sucursal
           FROM cnmd06_fichas b
           WHERE	  b.cod_presi         =      a.cod_presi        and
			  b.cod_entidad       =      a.cod_entidad      and
			  b.cod_tipo_inst     =      a.cod_tipo_inst    and
			  b.cod_inst          =      a.cod_inst         and
			  b.cod_dep           =      a.cod_dep          and
			  b.cod_tipo_nomina   =      a.cod_tipo_nomina  and
			  b.cod_cargo         =      a.cod_cargo        and
			  b.cod_ficha         =      a.cod_ficha
        ) as   cod_sucursal,


        (SELECT x.denominacion FROM cstd01_sucursales_bancarias x WHERE
            x.cod_entidad_bancaria = (SELECT b.cod_entidad_bancaria FROM cnmd06_fichas b WHERE
                                          b.cod_presi         =      a.cod_presi        and
										  b.cod_entidad       =      a.cod_entidad      and
										  b.cod_tipo_inst     =      a.cod_tipo_inst    and
										  b.cod_inst          =      a.cod_inst         and
										  b.cod_dep           =      a.cod_dep          and
										  b.cod_tipo_nomina   =      a.cod_tipo_nomina  and
										  b.cod_cargo         =      a.cod_cargo        and
										  b.cod_ficha         =      a.cod_ficha
							           ) and
		    x.cod_sucursal         = (SELECT b.cod_sucursal FROM cnmd06_fichas b WHERE
		                                      b.cod_presi         =      a.cod_presi        and
											  b.cod_entidad       =      a.cod_entidad      and
											  b.cod_tipo_inst     =      a.cod_tipo_inst    and
											  b.cod_inst          =      a.cod_inst         and
											  b.cod_dep           =      a.cod_dep          and
											  b.cod_tipo_nomina   =      a.cod_tipo_nomina  and
											  b.cod_cargo         =      a.cod_cargo        and
											  b.cod_ficha         =      a.cod_ficha
								       )
         ) as deno_sucursales_bancarias,



        (SELECT b.cuenta_bancaria
           FROM cnmd06_fichas b
           WHERE	  b.cod_presi         =      a.cod_presi        and
			  b.cod_entidad       =      a.cod_entidad      and
			  b.cod_tipo_inst     =      a.cod_tipo_inst    and
			  b.cod_inst          =      a.cod_inst         and
			  b.cod_dep           =      a.cod_dep          and
			  b.cod_tipo_nomina   =      a.cod_tipo_nomina  and
			  b.cod_cargo         =      a.cod_cargo        and
			  b.cod_ficha         =      a.cod_ficha
        ) as   cuenta_bancaria,





       (SELECT e.primer_apellido
        FROM   cnmd06_datos_personales e
        WHERE  e.cedula_identidad=(SELECT d.cedula_identidad
                                   FROM cnmd06_fichas d
                                   WHERE
				   d.cod_presi            =  a.cod_presi                and
				   d.cod_entidad          =  a.cod_entidad              and
				   d.cod_tipo_inst        =  a.cod_tipo_inst            and
				   d.cod_inst             =  a.cod_inst                 and
				   d.cod_dep              =  a.cod_dep                  and
				   d.cod_tipo_nomina      =  a.cod_tipo_nomina          and
				   d.cod_cargo            =  a.cod_cargo                and
				   d.cod_ficha            =  a.cod_ficha)
        )as  primer_apellido,

        (SELECT e.segundo_apellido
         FROM   cnmd06_datos_personales e
         WHERE  e.cedula_identidad=(SELECT d.cedula_identidad
                                   FROM cnmd06_fichas d
                                   WHERE
				   d.cod_presi            =  a.cod_presi                and
				   d.cod_entidad          =  a.cod_entidad              and
				   d.cod_tipo_inst        =  a.cod_tipo_inst            and
				   d.cod_inst             =  a.cod_inst                 and
				   d.cod_dep              =  a.cod_dep                  and
				   d.cod_tipo_nomina      =  a.cod_tipo_nomina          and
				   d.cod_cargo            =  a.cod_cargo                and
				   d.cod_ficha            =  a.cod_ficha)
        )as  segundo_apellido,

        (SELECT e.primer_nombre
         FROM   cnmd06_datos_personales e
         WHERE  e.cedula_identidad=(SELECT d.cedula_identidad
                                   FROM cnmd06_fichas d
                                   WHERE
				   d.cod_presi            =  a.cod_presi                and
				   d.cod_entidad          =  a.cod_entidad              and
				   d.cod_tipo_inst        =  a.cod_tipo_inst            and
				   d.cod_inst             =  a.cod_inst                 and
				   d.cod_dep              =  a.cod_dep                  and
				   d.cod_tipo_nomina      =  a.cod_tipo_nomina          and
				   d.cod_cargo            =  a.cod_cargo                and
				   d.cod_ficha            =  a.cod_ficha)
        )as  primer_nombre,

        (SELECT e.segundo_nombre
         FROM   cnmd06_datos_personales e
         WHERE  e.cedula_identidad=(SELECT d.cedula_identidad
                                   FROM cnmd06_fichas d
                                   WHERE
				   d.cod_presi            =  a.cod_presi                and
				   d.cod_entidad          =  a.cod_entidad              and
				   d.cod_tipo_inst        =  a.cod_tipo_inst            and
				   d.cod_inst             =  a.cod_inst                 and
				   d.cod_dep              =  a.cod_dep                  and
				   d.cod_tipo_nomina      =  a.cod_tipo_nomina          and
				   d.cod_cargo            =  a.cod_cargo                and
				   d.cod_ficha            =  a.cod_ficha)
        )as  segundo_nombre,



        (SELECT devolver_denominacion_puesto(   (SELECT xy.clasificacion_personal
                                                    FROM cnmd01 xy
                                                    WHERE
                                                           xy.cod_presi     = a.cod_presi     AND
                                                           xy.cod_entidad   = a.cod_entidad   AND
                                                           xy.cod_tipo_inst = a.cod_tipo_inst AND
                                                           xy.cod_inst = a.cod_inst           AND
                                                           xy.cod_dep = a.cod_dep             AND
                                                           xy.cod_tipo_nomina = a.cod_tipo_nomina)
                                                ,
                                                    (SELECT  c.cod_puesto
						    FROM cnmd05 c
						    WHERE     c.cod_presi       =  a.cod_presi AND
							   c.cod_entidad     =  a.cod_entidad AND
							   c.cod_tipo_inst   =  a.cod_tipo_inst AND
							   c.cod_inst        =  a.cod_inst AND
							   c.cod_dep         =  a.cod_dep AND
							   c.cod_tipo_nomina =  a.cod_tipo_nomina AND
							   c.cod_cargo       =  a.cod_cargo)
                                               )
      ) AS denominacion_puesto,

	  a.cod_tipo_transaccion,
	  a.cod_transaccion,
      (SELECT b.denominacion  FROM cnmd03_transacciones b WHERE b.cod_tipo_transaccion = a.cod_tipo_transaccion AND b.cod_transaccion      = a.cod_transaccion) AS deno_transaccion,
	  a.fecha_transaccion,
	  a.monto_original,
	  a.numero_cuotas_descontar,
	  a.numero_cuotas_cancelar,
	  a.numero_cuotas_canceladas,
	  a.monto_cuota,
	  a.saldo,
	  a.marca_fin_descuento,
	  a.fecha_proceso,
	  a.username,
	  a.dias_horas,


        (SELECT c.bonos          FROM cnmd05 c  WHERE     c.cod_presi       =  a.cod_presi AND c.cod_entidad     =  a.cod_entidad AND c.cod_tipo_inst   =  a.cod_tipo_inst AND  c.cod_inst        =  a.cod_inst AND  c.cod_dep         =  a.cod_dep AND c.cod_tipo_nomina =  a.cod_tipo_nomina AND c.cod_cargo       =  a.cod_cargo) as   bonos,
        (SELECT c.primas         FROM cnmd05 c  WHERE     c.cod_presi       =  a.cod_presi AND c.cod_entidad     =  a.cod_entidad AND c.cod_tipo_inst   =  a.cod_tipo_inst AND  c.cod_inst        =  a.cod_inst AND  c.cod_dep         =  a.cod_dep AND c.cod_tipo_nomina =  a.cod_tipo_nomina AND c.cod_cargo       =  a.cod_cargo) as   primas,
        (SELECT c.compensaciones FROM cnmd05 c  WHERE     c.cod_presi       =  a.cod_presi AND c.cod_entidad     =  a.cod_entidad AND c.cod_tipo_inst   =  a.cod_tipo_inst AND  c.cod_inst        =  a.cod_inst AND  c.cod_dep         =  a.cod_dep AND c.cod_tipo_nomina =  a.cod_tipo_nomina AND c.cod_cargo       =  a.cod_cargo) as   compensaciones,
        (SELECT c.sueldo_basico  FROM cnmd05 c  WHERE     c.cod_presi       =  a.cod_presi AND c.cod_entidad     =  a.cod_entidad AND c.cod_tipo_inst   =  a.cod_tipo_inst AND  c.cod_inst        =  a.cod_inst AND  c.cod_dep         =  a.cod_dep AND c.cod_tipo_nomina =  a.cod_tipo_nomina AND c.cod_cargo       =  a.cod_cargo) as   sueldo_basico,
        (SELECT c.cod_puesto     FROM cnmd05 c  WHERE     c.cod_presi       =  a.cod_presi AND c.cod_entidad     =  a.cod_entidad AND c.cod_tipo_inst   =  a.cod_tipo_inst AND  c.cod_inst        =  a.cod_inst AND  c.cod_dep         =  a.cod_dep AND c.cod_tipo_nomina =  a.cod_tipo_nomina AND c.cod_cargo       =  a.cod_cargo) as   cod_puesto,

	    (SELECT c.cod_dir_superior FROM cnmd05 c  WHERE     c.cod_presi       =  a.cod_presi AND c.cod_entidad     =  a.cod_entidad AND c.cod_tipo_inst   =  a.cod_tipo_inst AND  c.cod_inst        =  a.cod_inst AND  c.cod_dep         =  a.cod_dep AND c.cod_tipo_nomina =  a.cod_tipo_nomina AND c.cod_cargo       =  a.cod_cargo) as   cod_dir_superior,
	    (SELECT xa.denominacion    FROM cugd02_direccionsuperior xa where
	            xa.cod_tipo_institucion = a.cod_tipo_inst  and
	            xa.cod_institucion      = a.cod_inst       and
	            xa.cod_dependencia      = a.cod_dep        and
	            xa.cod_dir_superior     = (SELECT  c.cod_dir_superior FROM cnmd05 c  WHERE     c.cod_presi       =  a.cod_presi AND c.cod_entidad     =  a.cod_entidad AND c.cod_tipo_inst   =  a.cod_tipo_inst AND  c.cod_inst        =  a.cod_inst AND  c.cod_dep         =  a.cod_dep AND c.cod_tipo_nomina =  a.cod_tipo_nomina AND c.cod_cargo       =  a.cod_cargo)
         GROUP BY xa.denominacion) as  deno_cod_dir_superior,



        (SELECT  c.cod_coordinacion FROM cnmd05 c WHERE     c.cod_presi       =  a.cod_presi AND c.cod_entidad     =  a.cod_entidad AND c.cod_tipo_inst   =  a.cod_tipo_inst AND c.cod_inst        =  a.cod_inst AND c.cod_dep         =  a.cod_dep AND c.cod_tipo_nomina =  a.cod_tipo_nomina AND c.cod_cargo       =  a.cod_cargo ) as   cod_coordinacion,
	    (SELECT  xb.denominacion    FROM cugd02_coordinacion xb      where
	             xb.cod_tipo_institucion=a.cod_tipo_inst  and
	             xb.cod_institucion=a.cod_inst            and
	             xb.cod_dependencia=a.cod_dep             and
	             xb.cod_dir_superior=(SELECT  c.cod_dir_superior FROM cnmd05 c  WHERE     c.cod_presi       =  a.cod_presi AND c.cod_entidad     =  a.cod_entidad AND c.cod_tipo_inst   =  a.cod_tipo_inst AND  c.cod_inst        =  a.cod_inst AND  c.cod_dep         =  a.cod_dep AND c.cod_tipo_nomina =  a.cod_tipo_nomina AND c.cod_cargo       =  a.cod_cargo) and
	             xb.cod_coordinacion=(SELECT  c.cod_coordinacion FROM cnmd05 c  WHERE     c.cod_presi       =  a.cod_presi AND c.cod_entidad     =  a.cod_entidad AND c.cod_tipo_inst   =  a.cod_tipo_inst AND  c.cod_inst        =  a.cod_inst AND  c.cod_dep         =  a.cod_dep AND c.cod_tipo_nomina =  a.cod_tipo_nomina AND c.cod_cargo       =  a.cod_cargo)
	     GROUP BY xb.denominacion) as  deno_cod_coordinacion,



	    (SELECT  c.cod_secretaria   FROM cnmd05 c WHERE     c.cod_presi       =  a.cod_presi AND c.cod_entidad     =  a.cod_entidad AND c.cod_tipo_inst   =  a.cod_tipo_inst AND c.cod_inst        =  a.cod_inst AND c.cod_dep         =  a.cod_dep AND c.cod_tipo_nomina =  a.cod_tipo_nomina AND c.cod_cargo       =  a.cod_cargo ) as   cod_secretaria,
        (SELECT  xc.denominacion   FROM cugd02_secretaria xc        where
                 xc.cod_tipo_institucion = a.cod_tipo_inst  and
                 xc.cod_institucion      = a.cod_inst       and
                 xc.cod_dependencia      = a.cod_dep        and
                 xc.cod_dir_superior     = (SELECT c.cod_dir_superior FROM cnmd05 c  WHERE     c.cod_presi       =  a.cod_presi AND c.cod_entidad     =  a.cod_entidad AND c.cod_tipo_inst   =  a.cod_tipo_inst AND  c.cod_inst        =  a.cod_inst AND  c.cod_dep         =  a.cod_dep AND c.cod_tipo_nomina =  a.cod_tipo_nomina AND c.cod_cargo       =  a.cod_cargo) and
                 xc.cod_coordinacion     = (SELECT c.cod_coordinacion FROM cnmd05 c  WHERE     c.cod_presi       =  a.cod_presi AND c.cod_entidad     =  a.cod_entidad AND c.cod_tipo_inst   =  a.cod_tipo_inst AND  c.cod_inst        =  a.cod_inst AND c.cod_dep         =   a.cod_dep AND c.cod_tipo_nomina =  a.cod_tipo_nomina AND c.cod_cargo       =  a.cod_cargo) and
                 xc.cod_secretaria       = (SELECT c.cod_secretaria   FROM cnmd05 c  WHERE     c.cod_presi       =  a.cod_presi AND c.cod_entidad     =  a.cod_entidad AND c.cod_tipo_inst   =  a.cod_tipo_inst AND  c.cod_inst        =  a.cod_inst AND c.cod_dep         =   a.cod_dep AND c.cod_tipo_nomina =  a.cod_tipo_nomina AND c.cod_cargo       =  a.cod_cargo)
         GROUP BY xc.denominacion) as  deno_cod_secretaria,



        (SELECT  c.cod_direccion    FROM cnmd05 c WHERE     c.cod_presi       =  a.cod_presi AND c.cod_entidad     =  a.cod_entidad AND c.cod_tipo_inst   =  a.cod_tipo_inst AND c.cod_inst        =  a.cod_inst AND c.cod_dep         =  a.cod_dep AND c.cod_tipo_nomina =  a.cod_tipo_nomina AND c.cod_cargo       =  a.cod_cargo ) as   cod_direccion,
        (SELECT  xc.denominacion   FROM cugd02_direccion xc        where
                 xc.cod_tipo_institucion = a.cod_tipo_inst  and
                 xc.cod_institucion      = a.cod_inst       and
                 xc.cod_dependencia      = a.cod_dep        and
                 xc.cod_dir_superior     = (SELECT c.cod_dir_superior FROM cnmd05 c  WHERE     c.cod_presi       =  a.cod_presi AND c.cod_entidad     =  a.cod_entidad AND c.cod_tipo_inst   =  a.cod_tipo_inst AND  c.cod_inst        =  a.cod_inst AND c.cod_dep         =  a.cod_dep AND c.cod_tipo_nomina =  a.cod_tipo_nomina AND c.cod_cargo       =  a.cod_cargo) and
                 xc.cod_coordinacion     = (SELECT c.cod_coordinacion FROM cnmd05 c  WHERE     c.cod_presi       =  a.cod_presi AND c.cod_entidad     =  a.cod_entidad AND c.cod_tipo_inst   =  a.cod_tipo_inst AND  c.cod_inst        =  a.cod_inst AND c.cod_dep         =  a.cod_dep AND c.cod_tipo_nomina =  a.cod_tipo_nomina AND c.cod_cargo       =  a.cod_cargo) and
                 xc.cod_secretaria       = (SELECT c.cod_secretaria   FROM cnmd05 c  WHERE     c.cod_presi       =  a.cod_presi AND c.cod_entidad     =  a.cod_entidad AND c.cod_tipo_inst   =  a.cod_tipo_inst AND  c.cod_inst        =  a.cod_inst AND c.cod_dep         =  a.cod_dep AND c.cod_tipo_nomina =  a.cod_tipo_nomina AND c.cod_cargo       =  a.cod_cargo) and
                 xc.cod_direccion        = (SELECT c.cod_direccion    FROM cnmd05 c  WHERE     c.cod_presi       =  a.cod_presi AND c.cod_entidad     =  a.cod_entidad AND c.cod_tipo_inst   =  a.cod_tipo_inst AND  c.cod_inst        =  a.cod_inst AND c.cod_dep         =  a.cod_dep AND c.cod_tipo_nomina =  a.cod_tipo_nomina AND c.cod_cargo       =  a.cod_cargo)
         GROUP BY xc.denominacion) as  deno_cod_direccion,



        (SELECT  c.cod_division     FROM cnmd05 c WHERE     c.cod_presi       =  a.cod_presi AND c.cod_entidad     =  a.cod_entidad AND c.cod_tipo_inst   =  a.cod_tipo_inst AND c.cod_inst        =  a.cod_inst AND c.cod_dep         =  a.cod_dep AND c.cod_tipo_nomina =  a.cod_tipo_nomina AND c.cod_cargo       =  a.cod_cargo ) as   cod_division,
        (SELECT  xc.denominacion    FROM cugd02_division xc        where
                 xc.cod_tipo_institucion = a.cod_tipo_inst  and
                 xc.cod_institucion      = a.cod_inst       and
                 xc.cod_dependencia      = a.cod_dep        and
                 xc.cod_dir_superior     = (SELECT c.cod_dir_superior FROM cnmd05 c  WHERE     c.cod_presi       =  a.cod_presi AND c.cod_entidad     =  a.cod_entidad AND c.cod_tipo_inst   =  a.cod_tipo_inst AND  c.cod_inst        =  a.cod_inst AND c.cod_dep         =  a.cod_dep AND c.cod_tipo_nomina =  a.cod_tipo_nomina AND c.cod_cargo       =  a.cod_cargo) and
                 xc.cod_coordinacion     = (SELECT c.cod_coordinacion FROM cnmd05 c  WHERE     c.cod_presi       =  a.cod_presi AND c.cod_entidad     =  a.cod_entidad AND c.cod_tipo_inst   =  a.cod_tipo_inst AND  c.cod_inst        =  a.cod_inst AND c.cod_dep         =  a.cod_dep AND c.cod_tipo_nomina =  a.cod_tipo_nomina AND c.cod_cargo       =  a.cod_cargo) and
                 xc.cod_secretaria       = (SELECT c.cod_secretaria   FROM cnmd05 c  WHERE     c.cod_presi       =  a.cod_presi AND c.cod_entidad     =  a.cod_entidad AND c.cod_tipo_inst   =  a.cod_tipo_inst AND  c.cod_inst        =  a.cod_inst AND c.cod_dep         =  a.cod_dep AND c.cod_tipo_nomina =  a.cod_tipo_nomina AND c.cod_cargo       =  a.cod_cargo) and
                 xc.cod_direccion        = (SELECT c.cod_direccion    FROM cnmd05 c  WHERE     c.cod_presi       =  a.cod_presi AND c.cod_entidad     =  a.cod_entidad AND c.cod_tipo_inst   =  a.cod_tipo_inst AND  c.cod_inst        =  a.cod_inst AND c.cod_dep         =  a.cod_dep AND c.cod_tipo_nomina =  a.cod_tipo_nomina AND c.cod_cargo       =  a.cod_cargo) and
                 xc.cod_division         = (SELECT c.cod_division     FROM cnmd05 c  WHERE     c.cod_presi       =  a.cod_presi AND c.cod_entidad     =  a.cod_entidad AND c.cod_tipo_inst   =  a.cod_tipo_inst AND  c.cod_inst        =  a.cod_inst AND c.cod_dep         =  a.cod_dep AND c.cod_tipo_nomina =  a.cod_tipo_nomina AND c.cod_cargo       =  a.cod_cargo)
         GROUP BY xc.denominacion) as  deno_cod_division,



        (SELECT  c.cod_departamento FROM cnmd05 c WHERE     c.cod_presi       =  a.cod_presi AND c.cod_entidad     =  a.cod_entidad AND c.cod_tipo_inst   =  a.cod_tipo_inst AND c.cod_inst        =  a.cod_inst AND c.cod_dep         =  a.cod_dep AND c.cod_tipo_nomina =  a.cod_tipo_nomina AND c.cod_cargo       =  a.cod_cargo ) as   cod_departamento,
        (SELECT  xc.denominacion    FROM cugd02_departamento xc        where
                 xc.cod_tipo_institucion = a.cod_tipo_inst  and
                 xc.cod_institucion      = a.cod_inst       and
                 xc.cod_dependencia      = a.cod_dep        and
                 xc.cod_dir_superior     = (SELECT c.cod_dir_superior FROM cnmd05 c  WHERE     c.cod_presi       =  a.cod_presi AND c.cod_entidad     =  a.cod_entidad AND c.cod_tipo_inst   =  a.cod_tipo_inst AND  c.cod_inst        =  a.cod_inst AND c.cod_dep         =  a.cod_dep AND c.cod_tipo_nomina =  a.cod_tipo_nomina AND c.cod_cargo       =  a.cod_cargo) and
                 xc.cod_coordinacion     = (SELECT c.cod_coordinacion FROM cnmd05 c  WHERE     c.cod_presi       =  a.cod_presi AND c.cod_entidad     =  a.cod_entidad AND c.cod_tipo_inst   =  a.cod_tipo_inst AND  c.cod_inst        =  a.cod_inst AND c.cod_dep         =  a.cod_dep AND c.cod_tipo_nomina =  a.cod_tipo_nomina AND c.cod_cargo       =  a.cod_cargo) and
                 xc.cod_secretaria       = (SELECT c.cod_secretaria   FROM cnmd05 c  WHERE     c.cod_presi       =  a.cod_presi AND c.cod_entidad     =  a.cod_entidad AND c.cod_tipo_inst   =  a.cod_tipo_inst AND  c.cod_inst        =  a.cod_inst AND c.cod_dep         =  a.cod_dep AND c.cod_tipo_nomina =  a.cod_tipo_nomina AND c.cod_cargo       =  a.cod_cargo) and
                 xc.cod_direccion        = (SELECT c.cod_direccion    FROM cnmd05 c  WHERE     c.cod_presi       =  a.cod_presi AND c.cod_entidad     =  a.cod_entidad AND c.cod_tipo_inst   =  a.cod_tipo_inst AND  c.cod_inst        =  a.cod_inst AND c.cod_dep         =  a.cod_dep AND c.cod_tipo_nomina =  a.cod_tipo_nomina AND c.cod_cargo       =  a.cod_cargo) and
                 xc.cod_division         = (SELECT c.cod_division     FROM cnmd05 c  WHERE     c.cod_presi       =  a.cod_presi AND c.cod_entidad     =  a.cod_entidad AND c.cod_tipo_inst   =  a.cod_tipo_inst AND  c.cod_inst        =  a.cod_inst AND c.cod_dep         =  a.cod_dep AND c.cod_tipo_nomina =  a.cod_tipo_nomina AND c.cod_cargo       =  a.cod_cargo) and
                 xc.cod_departamento     = (SELECT c.cod_departamento FROM cnmd05 c  WHERE     c.cod_presi       =  a.cod_presi AND c.cod_entidad     =  a.cod_entidad AND c.cod_tipo_inst   =  a.cod_tipo_inst AND  c.cod_inst        =  a.cod_inst AND c.cod_dep         =  a.cod_dep AND c.cod_tipo_nomina =  a.cod_tipo_nomina AND c.cod_cargo       =  a.cod_cargo)
         GROUP BY xc.denominacion) as  deno_cod_departamento,



        (SELECT  c.cod_oficina      FROM cnmd05 c WHERE     c.cod_presi       =  a.cod_presi AND c.cod_entidad     =  a.cod_entidad AND c.cod_tipo_inst   =  a.cod_tipo_inst AND c.cod_inst        =  a.cod_inst AND c.cod_dep         =  a.cod_dep AND c.cod_tipo_nomina =  a.cod_tipo_nomina AND c.cod_cargo       =  a.cod_cargo ) as   cod_oficina,
        (SELECT  xc.denominacion    FROM cugd02_oficina xc        where
                 xc.cod_tipo_institucion = a.cod_tipo_inst  and
                 xc.cod_institucion      = a.cod_inst       and
                 xc.cod_dependencia      = a.cod_dep        and
                 xc.cod_dir_superior     = (SELECT c.cod_dir_superior FROM cnmd05 c  WHERE     c.cod_presi       =  a.cod_presi AND c.cod_entidad     =  a.cod_entidad AND c.cod_tipo_inst   =  a.cod_tipo_inst AND  c.cod_inst        =  a.cod_inst AND c.cod_dep         =  a.cod_dep AND c.cod_tipo_nomina =  a.cod_tipo_nomina AND c.cod_cargo       =  a.cod_cargo) and
                 xc.cod_coordinacion     = (SELECT c.cod_coordinacion FROM cnmd05 c  WHERE     c.cod_presi       =  a.cod_presi AND c.cod_entidad     =  a.cod_entidad AND c.cod_tipo_inst   =  a.cod_tipo_inst AND  c.cod_inst        =  a.cod_inst AND c.cod_dep         =  a.cod_dep AND c.cod_tipo_nomina =  a.cod_tipo_nomina AND c.cod_cargo       =  a.cod_cargo) and
                 xc.cod_secretaria       = (SELECT c.cod_secretaria   FROM cnmd05 c  WHERE     c.cod_presi       =  a.cod_presi AND c.cod_entidad     =  a.cod_entidad AND c.cod_tipo_inst   =  a.cod_tipo_inst AND  c.cod_inst        =  a.cod_inst AND c.cod_dep         =  a.cod_dep AND c.cod_tipo_nomina =  a.cod_tipo_nomina AND c.cod_cargo       =  a.cod_cargo) and
                 xc.cod_direccion        = (SELECT c.cod_direccion    FROM cnmd05 c  WHERE     c.cod_presi       =  a.cod_presi AND c.cod_entidad     =  a.cod_entidad AND c.cod_tipo_inst   =  a.cod_tipo_inst AND  c.cod_inst        =  a.cod_inst AND c.cod_dep         =  a.cod_dep AND c.cod_tipo_nomina =  a.cod_tipo_nomina AND c.cod_cargo       =  a.cod_cargo) and
                 xc.cod_division         = (SELECT c.cod_division     FROM cnmd05 c  WHERE     c.cod_presi       =  a.cod_presi AND c.cod_entidad     =  a.cod_entidad AND c.cod_tipo_inst   =  a.cod_tipo_inst AND  c.cod_inst        =  a.cod_inst AND c.cod_dep         =  a.cod_dep AND c.cod_tipo_nomina =  a.cod_tipo_nomina AND c.cod_cargo       =  a.cod_cargo) and
                 xc.cod_departamento     = (SELECT c.cod_departamento FROM cnmd05 c  WHERE     c.cod_presi       =  a.cod_presi AND c.cod_entidad     =  a.cod_entidad AND c.cod_tipo_inst   =  a.cod_tipo_inst AND  c.cod_inst        =  a.cod_inst AND c.cod_dep         =  a.cod_dep AND c.cod_tipo_nomina =  a.cod_tipo_nomina AND c.cod_cargo       =  a.cod_cargo) and
                 xc.cod_oficina          = (SELECT c.cod_oficina      FROM cnmd05 c  WHERE     c.cod_presi       =  a.cod_presi AND c.cod_entidad     =  a.cod_entidad AND c.cod_tipo_inst   =  a.cod_tipo_inst AND  c.cod_inst        =  a.cod_inst AND c.cod_dep         =  a.cod_dep AND c.cod_tipo_nomina =  a.cod_tipo_nomina AND c.cod_cargo       =  a.cod_cargo)
         GROUP BY xc.denominacion) as  deno_cod_oficina,





        (SELECT  c.cod_estado      FROM cnmd05 c WHERE     c.cod_presi       =  a.cod_presi AND c.cod_entidad     =  a.cod_entidad AND c.cod_tipo_inst   =  a.cod_tipo_inst AND c.cod_inst        =  a.cod_inst AND c.cod_dep         =  a.cod_dep AND c.cod_tipo_nomina =  a.cod_tipo_nomina AND c.cod_cargo       =  a.cod_cargo ) as   cod_estado,
        (SELECT  xya.denominacion FROM cugd01_estados          xya where
                 xya.cod_republica = a.cod_presi and
                 xya.cod_estado    = (SELECT  c.cod_estado      FROM cnmd05 c WHERE     c.cod_presi       =  a.cod_presi AND c.cod_entidad     =  a.cod_entidad AND c.cod_tipo_inst   =  a.cod_tipo_inst AND c.cod_inst        =  a.cod_inst AND c.cod_dep         =  a.cod_dep AND c.cod_tipo_nomina =  a.cod_tipo_nomina AND c.cod_cargo       =  a.cod_cargo )
         GROUP BY xya.denominacion) as  deno_cod_estado,


        (SELECT  c.cod_municipio   FROM cnmd05 c WHERE     c.cod_presi       =  a.cod_presi AND c.cod_entidad     =  a.cod_entidad AND c.cod_tipo_inst   =  a.cod_tipo_inst AND c.cod_inst        =  a.cod_inst AND c.cod_dep         =  a.cod_dep AND c.cod_tipo_nomina =  a.cod_tipo_nomina AND c.cod_cargo       =  a.cod_cargo ) as   cod_municipio,
        (SELECT  xya.denominacion  FROM cugd01_municipios          xya where
                 xya.cod_republica = a.cod_presi and
                 xya.cod_estado    = (SELECT  c.cod_estado      FROM cnmd05 c WHERE     c.cod_presi       =  a.cod_presi AND c.cod_entidad     =  a.cod_entidad AND c.cod_tipo_inst   =  a.cod_tipo_inst AND c.cod_inst        =  a.cod_inst AND c.cod_dep         =  a.cod_dep AND c.cod_tipo_nomina =  a.cod_tipo_nomina AND c.cod_cargo       =  a.cod_cargo ) and
                 xya.cod_municipio = (SELECT  c.cod_municipio   FROM cnmd05 c WHERE     c.cod_presi       =  a.cod_presi AND c.cod_entidad     =  a.cod_entidad AND c.cod_tipo_inst   =  a.cod_tipo_inst AND c.cod_inst        =  a.cod_inst AND c.cod_dep         =  a.cod_dep AND c.cod_tipo_nomina =  a.cod_tipo_nomina AND c.cod_cargo       =  a.cod_cargo )
         GROUP BY xya.denominacion) as  deno_cod_municipio,


        (SELECT  c.cod_parroquia   FROM cnmd05 c WHERE     c.cod_presi       =  a.cod_presi AND c.cod_entidad     =  a.cod_entidad AND c.cod_tipo_inst   =  a.cod_tipo_inst AND c.cod_inst        =  a.cod_inst AND c.cod_dep         =  a.cod_dep AND c.cod_tipo_nomina =  a.cod_tipo_nomina AND c.cod_cargo       =  a.cod_cargo ) as   cod_parroquia,
        (SELECT  xya.denominacion  FROM cugd01_parroquias          xya where
                 xya.cod_republica = a.cod_presi and
                 xya.cod_estado    = (SELECT  c.cod_estado      FROM cnmd05 c WHERE     c.cod_presi       =  a.cod_presi AND c.cod_entidad     =  a.cod_entidad AND c.cod_tipo_inst   =  a.cod_tipo_inst AND c.cod_inst        =  a.cod_inst AND c.cod_dep         =  a.cod_dep AND c.cod_tipo_nomina =  a.cod_tipo_nomina AND c.cod_cargo       =  a.cod_cargo ) and
                 xya.cod_municipio = (SELECT  c.cod_municipio   FROM cnmd05 c WHERE     c.cod_presi       =  a.cod_presi AND c.cod_entidad     =  a.cod_entidad AND c.cod_tipo_inst   =  a.cod_tipo_inst AND c.cod_inst        =  a.cod_inst AND c.cod_dep         =  a.cod_dep AND c.cod_tipo_nomina =  a.cod_tipo_nomina AND c.cod_cargo       =  a.cod_cargo ) and
                 xya.cod_parroquia = (SELECT  c.cod_parroquia   FROM cnmd05 c WHERE     c.cod_presi       =  a.cod_presi AND c.cod_entidad     =  a.cod_entidad AND c.cod_tipo_inst   =  a.cod_tipo_inst AND c.cod_inst        =  a.cod_inst AND c.cod_dep         =  a.cod_dep AND c.cod_tipo_nomina =  a.cod_tipo_nomina AND c.cod_cargo       =  a.cod_cargo )
         GROUP BY xya.denominacion) as  deno_cod_parroquia,


        (SELECT  c.cod_centro      FROM cnmd05 c WHERE     c.cod_presi       =  a.cod_presi AND c.cod_entidad     =  a.cod_entidad AND c.cod_tipo_inst   =  a.cod_tipo_inst AND c.cod_inst        =  a.cod_inst AND c.cod_dep         =  a.cod_dep AND c.cod_tipo_nomina =  a.cod_tipo_nomina AND c.cod_cargo       =  a.cod_cargo ) as   cod_centro,
        (SELECT  xya.denominacion  FROM cugd01_centros_poblados          xya where
                 xya.cod_republica = a.cod_presi and
                 xya.cod_estado    = (SELECT  c.cod_estado      FROM cnmd05 c WHERE     c.cod_presi       =  a.cod_presi AND c.cod_entidad     =  a.cod_entidad AND c.cod_tipo_inst   =  a.cod_tipo_inst AND c.cod_inst        =  a.cod_inst AND c.cod_dep         =  a.cod_dep AND c.cod_tipo_nomina =  a.cod_tipo_nomina AND c.cod_cargo       =  a.cod_cargo ) and
                 xya.cod_municipio = (SELECT  c.cod_municipio   FROM cnmd05 c WHERE     c.cod_presi       =  a.cod_presi AND c.cod_entidad     =  a.cod_entidad AND c.cod_tipo_inst   =  a.cod_tipo_inst AND c.cod_inst        =  a.cod_inst AND c.cod_dep         =  a.cod_dep AND c.cod_tipo_nomina =  a.cod_tipo_nomina AND c.cod_cargo       =  a.cod_cargo ) and
                 xya.cod_parroquia = (SELECT  c.cod_parroquia   FROM cnmd05 c WHERE     c.cod_presi       =  a.cod_presi AND c.cod_entidad     =  a.cod_entidad AND c.cod_tipo_inst   =  a.cod_tipo_inst AND c.cod_inst        =  a.cod_inst AND c.cod_dep         =  a.cod_dep AND c.cod_tipo_nomina =  a.cod_tipo_nomina AND c.cod_cargo       =  a.cod_cargo ) and
                 xya.cod_centro    = (SELECT  c.cod_centro      FROM cnmd05 c WHERE     c.cod_presi       =  a.cod_presi AND c.cod_entidad     =  a.cod_entidad AND c.cod_tipo_inst   =  a.cod_tipo_inst AND c.cod_inst        =  a.cod_inst AND c.cod_dep         =  a.cod_dep AND c.cod_tipo_nomina =  a.cod_tipo_nomina AND c.cod_cargo       =  a.cod_cargo )
         GROUP BY xya.denominacion) as  deno_cod_centro,



        (SELECT  c.condicion_actividad      FROM cnmd05 c WHERE     c.cod_presi       =  a.cod_presi AND c.cod_entidad     =  a.cod_entidad AND c.cod_tipo_inst   =  a.cod_tipo_inst AND c.cod_inst        =  a.cod_inst AND c.cod_dep         =  a.cod_dep AND c.cod_tipo_nomina =  a.cod_tipo_nomina AND c.cod_cargo       =  a.cod_cargo ) as   condicion_actividad,
        (SELECT  c.ano                      FROM cnmd05 c WHERE     c.cod_presi       =  a.cod_presi AND c.cod_entidad     =  a.cod_entidad AND c.cod_tipo_inst   =  a.cod_tipo_inst AND c.cod_inst        =  a.cod_inst AND c.cod_dep         =  a.cod_dep AND c.cod_tipo_nomina =  a.cod_tipo_nomina AND c.cod_cargo       =  a.cod_cargo ) as   ano,
        (SELECT  c.cod_sector               FROM cnmd05 c WHERE     c.cod_presi       =  a.cod_presi AND c.cod_entidad     =  a.cod_entidad AND c.cod_tipo_inst   =  a.cod_tipo_inst AND c.cod_inst        =  a.cod_inst AND c.cod_dep         =  a.cod_dep AND c.cod_tipo_nomina =  a.cod_tipo_nomina AND c.cod_cargo       =  a.cod_cargo ) as   cod_sector,
        (SELECT  c.cod_programa             FROM cnmd05 c WHERE     c.cod_presi       =  a.cod_presi AND c.cod_entidad     =  a.cod_entidad AND c.cod_tipo_inst   =  a.cod_tipo_inst AND c.cod_inst        =  a.cod_inst AND c.cod_dep         =  a.cod_dep AND c.cod_tipo_nomina =  a.cod_tipo_nomina AND c.cod_cargo       =  a.cod_cargo ) as   cod_programa,
        (SELECT  c.cod_sub_prog             FROM cnmd05 c WHERE     c.cod_presi       =  a.cod_presi AND c.cod_entidad     =  a.cod_entidad AND c.cod_tipo_inst   =  a.cod_tipo_inst AND c.cod_inst        =  a.cod_inst AND c.cod_dep         =  a.cod_dep AND c.cod_tipo_nomina =  a.cod_tipo_nomina AND c.cod_cargo       =  a.cod_cargo ) as   cod_sub_prog,
        (SELECT  c.cod_proyecto             FROM cnmd05 c WHERE     c.cod_presi       =  a.cod_presi AND c.cod_entidad     =  a.cod_entidad AND c.cod_tipo_inst   =  a.cod_tipo_inst AND c.cod_inst        =  a.cod_inst AND c.cod_dep         =  a.cod_dep AND c.cod_tipo_nomina =  a.cod_tipo_nomina AND c.cod_cargo       =  a.cod_cargo ) as   cod_proyecto,
        (SELECT  c.cod_activ_obra           FROM cnmd05 c WHERE     c.cod_presi       =  a.cod_presi AND c.cod_entidad     =  a.cod_entidad AND c.cod_tipo_inst   =  a.cod_tipo_inst AND c.cod_inst        =  a.cod_inst AND c.cod_dep         =  a.cod_dep AND c.cod_tipo_nomina =  a.cod_tipo_nomina AND c.cod_cargo       =  a.cod_cargo ) as   cod_activ_obra,
        (SELECT  c.cod_partida              FROM cnmd05 c WHERE     c.cod_presi       =  a.cod_presi AND c.cod_entidad     =  a.cod_entidad AND c.cod_tipo_inst   =  a.cod_tipo_inst AND c.cod_inst        =  a.cod_inst AND c.cod_dep         =  a.cod_dep AND c.cod_tipo_nomina =  a.cod_tipo_nomina AND c.cod_cargo       =  a.cod_cargo ) as   cod_partida,
        (SELECT  c.cod_generica             FROM cnmd05 c WHERE     c.cod_presi       =  a.cod_presi AND c.cod_entidad     =  a.cod_entidad AND c.cod_tipo_inst   =  a.cod_tipo_inst AND c.cod_inst        =  a.cod_inst AND c.cod_dep         =  a.cod_dep AND c.cod_tipo_nomina =  a.cod_tipo_nomina AND c.cod_cargo       =  a.cod_cargo ) as   cod_generica,
        (SELECT  c.cod_especifica           FROM cnmd05 c WHERE     c.cod_presi       =  a.cod_presi AND c.cod_entidad     =  a.cod_entidad AND c.cod_tipo_inst   =  a.cod_tipo_inst AND c.cod_inst        =  a.cod_inst AND c.cod_dep         =  a.cod_dep AND c.cod_tipo_nomina =  a.cod_tipo_nomina AND c.cod_cargo       =  a.cod_cargo ) as   cod_especifica,
        (SELECT  c.cod_sub_espec            FROM cnmd05 c WHERE     c.cod_presi       =  a.cod_presi AND c.cod_entidad     =  a.cod_entidad AND c.cod_tipo_inst   =  a.cod_tipo_inst AND c.cod_inst        =  a.cod_inst AND c.cod_dep         =  a.cod_dep AND c.cod_tipo_nomina =  a.cod_tipo_nomina AND c.cod_cargo       =  a.cod_cargo ) as   cod_sub_espec,
        (SELECT  c.cod_auxiliar             FROM cnmd05 c WHERE     c.cod_presi       =  a.cod_presi AND c.cod_entidad     =  a.cod_entidad AND c.cod_tipo_inst   =  a.cod_tipo_inst AND c.cod_inst        =  a.cod_inst AND c.cod_dep         =  a.cod_dep AND c.cod_tipo_nomina =  a.cod_tipo_nomina AND c.cod_cargo       =  a.cod_cargo ) as   cod_auxiliar,

        (SELECT b.dias_cobro        FROM cnmd01 b WHERE	  b.cod_presi         =      a.cod_presi        and b.cod_entidad       =      a.cod_entidad      and b.cod_tipo_inst     =      a.cod_tipo_inst    and b.cod_inst          =      a.cod_inst         and b.cod_dep           =      a.cod_dep          and b.cod_tipo_nomina   =      a.cod_tipo_nomina ) as   dias_cobro_cnmd01,

        (SELECT b.dias
           FROM cnmd09_dias_trabajados_falta b
           WHERE	  b.cod_presi         =      a.cod_presi        and
			  b.cod_entidad       =      a.cod_entidad      and
			  b.cod_tipo_inst     =      a.cod_tipo_inst    and
			  b.cod_inst          =      a.cod_inst         and
			  b.cod_dep           =      a.cod_dep          and
			  b.cod_tipo_nomina   =      a.cod_tipo_nomina  and
			  b.cod_cargo         =      a.cod_cargo        and
			  b.cod_ficha         =      a.cod_ficha
        ) as   dias_cnmd09_dias_trabajados_falta,


        (SELECT b.dias
           FROM cnmd09_dias_trabajados_ingreso_egreso b
           WHERE	  b.cod_presi         =      a.cod_presi        and
			  b.cod_entidad       =      a.cod_entidad      and
			  b.cod_tipo_inst     =      a.cod_tipo_inst    and
			  b.cod_inst          =      a.cod_inst         and
			  b.cod_dep           =      a.cod_dep          and
			  b.cod_tipo_nomina   =      a.cod_tipo_nomina  and
			  b.cod_cargo         =      a.cod_cargo        and
			  b.cod_ficha         =      a.cod_ficha
        ) as   dias_cnmd09_dias_trabajados_ingreso_egreso







FROM  cnmd07_transacciones_actuales a;




ALTER TABLE v_cnmd07_transacciones_actuales OWNER TO sisap;













