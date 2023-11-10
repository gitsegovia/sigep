<?php

class Cnmp06ExpedienteController extends AppController
{
    public $name = 'cnmp06_expediente';
    public $uses = array(
'cnmd06_datos_personales','cugd05_restriccion_clave','cugd10_imagenes','cnmd06_colores','cnmd06_clubes','cnmd06_deportes','cnmd06_profesiones','cnmd06_fichas',
'cugd01_republica','cugd01_estados','cugd01_municipios','cugd01_parroquias','cugd01_centropoblados',
'cnmd06_especialidades','cnmd06_deportes','cnmd06_religiones','cnmd06_hobby','cnmd06_oficio', 'datos_personales_super_busqueda', 'v_cnmd06_datos_educativos','cnmd06_nivel_educacion','cnmd06_instituto_educativo','cnmd06_datos_educativos','v_cnmd06_datos_formacion_profesional','cnmd06_cursos','cnmd06_datos_formacion_profesional',
'v_cnmd06_datos_registro_titulo','cnmd06_especialidades','cnmd06_colegio_profesional','cnmd06_datos_registro_titulo',
'v_cnmd06_datos_familiares','cnmd06_parentesco','cnmd06_datos_familiares','cnmd06_guarderias',
'cnmd06_datos_otrasexperiencias_laborables',
'cnmd06_experiencia_administrativa',
'cnmd06_datos_bienes', 'cnmd06_bienes',
'cnmd06_soportes',
'cnmd05','cnmd02_empleados_puestos','cnmd02_obreros_puestos','cnmd02_varios_puestos', 'cnmd07_transacciones_actuales',
'cnmd06_datos_permisos', 'cnmd06_permisos',
'cnmd06_datos_amonestaciones', 'cnmd06_amonestaciones'
);
    public $helpers = array('Html','Ajax','Javascript', 'Session', 'Sisap');



    public function checkSession()
    {
        if (!$this->Session->check('Usuario')) {
            $this->redirect('/salir/');
            exit();
        } else {
            $this->requestAction('/usuarios/actualizar_user');
        }
    }//fin checksession


    public function SQLCA($ano = null)
    {
        $sql_re = "cod_presi=" . $this->verifica_SS(1) . "  and    ";
        $sql_re .= "cod_entidad=" . $this->verifica_SS(2) . "  and  ";
        $sql_re .= "cod_tipo_inst=" . $this->verifica_SS(3) . "  and ";
        $sql_re .= "cod_inst=" . $this->verifica_SS(4) . "  and  ";
        if($ano != null) {
            $sql_re .= "cod_dep=" . $this->verifica_SS(5) . "  and  ";
            $sql_re .= "ano=" . $ano . "  ";
        } else {
            $sql_re .= "cod_dep=" . $this->verifica_SS(5) . " ";
        }
        return $sql_re;
    }//fin funcion SQLCA


    public function index()
    {

        $this->verifica_entrada('112');

        $this->layout = "ajax";
        $this->Session->delete('cedula_pestana_expediente');
        $this->Session->delete('cod_dep_expediente');
        $this->Session->delete('cod_tipo_nomina_expediente');
        $this->Session->delete('cod_cargo_expediente');
        $this->Session->delete('cod_ficha_expediente');
        $this->Session->delete('pag_num_expediente');


    }//index


    public function entrar()
    {
        $this->layout = "ajax";
        if(isset($this->data['cnmp06_expediente']['login']) && isset($this->data['cnmp06_expediente']['password'])) {
            $l = "PROYECTO";
            $c = "JJJSAE";
            $lo = "ENTRAR";
            $co = "ENTRAR";
            $user = addslashes($this->data['cnmp06_expediente']['login']);
            $paswd = addslashes($this->data['cnmp06_expediente']['password']);
            $cond = $this->SQLCA() . " and username='" . $user . "' and cod_tipo=112 and clave='" . $paswd . "'";

            if($user == $l && $paswd == $c) {
                $this->Session->write('autor_valido', true);
                $this->index("autor_valido");
                $this->render("index");
            } elseif($user == $lo && $paswd == $co) {
                $this->Session->write('autor_valido', true);
                $this->index("autor_valido");
                $this->render("index");
            } elseif($this->cugd05_restriccion_clave->findCount($cond) != 0) {
                $this->Session->write('autor_valido', true);
                $this->index("autor_valido");
                $this->render("index");
            } else {
                $this->set('errorMessage', "Lo siento, se necesita autorizaci&oacute;n para utilizar este programa");
                $this->index("autor_valido");
                $this->render("index");
            }
        }
    }// Entrar


    public function salir_clave()
    {
        $this->layout = "ajax";
        $this->Session->delete('autor_valido');
    }


    //SECCION DE SOLO CONSULTA

    public function index2()
    {

        $this->layout = "ajax";
        $this->Session->delete('cedula_pestana_expediente');
        $this->Session->delete('cod_dep_expediente');
        $this->Session->delete('cod_tipo_nomina_expediente');
        $this->Session->delete('cod_cargo_expediente');
        $this->Session->delete('cod_ficha_expediente');
        $this->Session->delete('pag_num_expediente');
    }//index

    public function buscar_vista_1($var1 = null)
    {

        $this->layout = "ajax";
        $this->set("opcion", $var1);
        $this->Session->delete('pista');
        $this->Session->write('pista_opcion', 2);

    }//fin function

    public function buscar_por_pista($var1 = null, $var2 = null, $var3 = null)
    {
        $this->layout = "ajax";
        $sql_like = "";

        $cod_presi      =  $this->Session->read('SScodpresi');
        $cod_entidad    =  $this->Session->read('SScodentidad');
        $cod_tipo_inst  =  $this->Session->read('SScodtipoinst');
        $cod_inst       =  $this->Session->read('SScodinst');
        $cod_dep        =  $this->Session->read('SScoddep');


        if($var3 == null) {
            $this->Session->write('pista', $var2);
            $var2 = strtoupper($var2);
            $var_like = $var2;
            $sql_like = $this->sql_like_super_busqueda($var_like, $this->Session->read('pista_opcion'));
            $Tfilas = $this->datos_personales_super_busqueda->findCount($sql_like);
            if($Tfilas != 0) {
                $pagina = 1;
                $Tfilas = (int)ceil($Tfilas / 300);
                $this->set('pag_cant', $pagina . '/' . $Tfilas);
                $this->set('total_paginas', $Tfilas);
                $this->set('pagina_actual', $pagina);
                $this->set('ultimo', $Tfilas);
                $datos_filas = $this->datos_personales_super_busqueda->findAll($sql_like, "cedula_identidad, primer_nombre, segundo_nombre, primer_apellido, segundo_apellido", "primer_nombre,primer_apellido ASC", 300, 1, null);
                $sql = "";
                foreach($datos_filas as $ve) {

                    if($sql == "") {
                        $sql .= "    a.cedula_identidad = '" . $ve["datos_personales_super_busqueda"]["cedula_identidad"] . "' ";
                    } else {
                        $sql .= " or a.cedula_identidad = '" . $ve["datos_personales_super_busqueda"]["cedula_identidad"] . "'  ";
                    }
                    //$sql_in[]="'".$ve["datos_personales_super_busqueda"]["cedula_identidad"]."'";
                }//fin foreach
                $sql = "(" . $sql . ")";
                //$sql = " a.cedula_identidad in (".implode(',',$sql_in).")";

                $dato_a =   $this->datos_personales_super_busqueda->execute("
                		SELECT
                      a.cod_presi,
											a.cod_entidad,
											a.cod_tipo_inst,
											a.cod_inst,
											a.cod_dep,
											a.cod_tipo_nomina,
											a.cod_cargo,
											a.cod_ficha,
											a.cedula_identidad,
											a.condicion_actividad
												FROM
																				cnmd06_fichas a,
																				cnmd05        b
												WHERE
														a.cod_presi         =  '" . $cod_presi . "'      and
													a.cod_entidad       =  '" . $cod_entidad . "'    and
													a.cod_tipo_inst     =  '" . $cod_tipo_inst . "'  and
													a.cod_inst          =  '" . $cod_inst . "'       and
															b.cod_presi         =  a.cod_presi           and
													b.cod_entidad       =  a.cod_entidad         and
													b.cod_tipo_inst     =  a.cod_tipo_inst       and
													b.cod_inst          =  a.cod_inst            and
													b.cod_dep           =  a.cod_dep             and
													b.cod_tipo_nomina   =  a.cod_tipo_nomina     and
													b.cod_cargo         =  a.cod_cargo           and " . $sql . "  ORDER BY cedula_identidad, cod_dep, cod_tipo_nomina  ASC;
								");


                $this->set("datosFILAS", $datos_filas);
                $this->set('siguiente', $pagina + 1);
                $this->set('anterior', $pagina - 1);
                $this->bt_nav($Tfilas, $pagina);
            } else {
                $this->set("datosFILAS", '');
            }
            $this->set("dato_a", $dato_a);

        } else {

            $var22 = $this->Session->read('pista');
            $var22 = strtoupper($var22);
            $var_like = $var22;
            $sql_like = $this->sql_like_super_busqueda($var_like, $this->Session->read('pista_opcion'));

            $Tfilas = $this->datos_personales_super_busqueda->findCount($sql_like);
            if($Tfilas != 0) {
                $pagina = $var3;
                $Tfilas = (int)ceil($Tfilas / 300);
                $this->set('pag_cant', $pagina . '/' . $Tfilas);
                $this->set('total_paginas', $Tfilas);
                $this->set('pagina_actual', $pagina);
                $this->set('ultimo', $Tfilas);
                $datos_filas = $this->datos_personales_super_busqueda->findAll($sql_like, "cedula_identidad, primer_nombre, segundo_nombre, primer_apellido, segundo_apellido", "primer_nombre,primer_apellido ASC", 300, $pagina, null);
                $sql = "";
                foreach($datos_filas as $ve) {

                    if($sql == "") {
                        $sql .= "    a.cedula_identidad = '" . $ve["datos_personales_super_busqueda"]["cedula_identidad"] . "' ";
                    } else {
                        $sql .= " or a.cedula_identidad = '" . $ve["datos_personales_super_busqueda"]["cedula_identidad"] . "'  ";
                    }
                    //$sql_in[]="'".$ve["datos_personales_super_busqueda"]["cedula_identidad"]."'";
                }//fin foreach
                $sql = "(" . $sql . ")";
                //$sql = " a.cedula_identidad in (".implode(',',$sql_in).")";

                $dato_a =   $this->datos_personales_super_busqueda->execute("
											SELECT
														a.cod_presi,
										a.cod_entidad,
										a.cod_tipo_inst,
										a.cod_inst,
										a.cod_dep,
										a.cod_tipo_nomina,
										a.cod_cargo,
										a.cod_ficha,
										a.cedula_identidad,
										a.condicion_actividad
									FROM
												cnmd06_fichas a,
												cnmd05        b
									WHERE
											a.cod_presi         =  '" . $cod_presi . "'      and
										a.cod_entidad       =  '" . $cod_entidad . "'    and
										a.cod_tipo_inst     =  '" . $cod_tipo_inst . "'  and
										a.cod_inst          =  '" . $cod_inst . "'       and
												b.cod_presi         =  a.cod_presi           and
										b.cod_entidad       =  a.cod_entidad         and
										b.cod_tipo_inst     =  a.cod_tipo_inst       and
										b.cod_inst          =  a.cod_inst            and
										b.cod_dep           =  a.cod_dep             and
										b.cod_tipo_nomina   =  a.cod_tipo_nomina     and
										b.cod_cargo         =  a.cod_cargo           and  " . $sql . "   ORDER BY cedula_identidad, cod_dep, cod_tipo_nomina ASC;
								");


                $this->set("datosFILAS", $datos_filas);
                $this->set('siguiente', $pagina + 1);
                $this->set('anterior', $pagina - 1);
                $this->bt_nav($Tfilas, $pagina);
            } else {
                $this->set("datosFILAS", '');
            }
            $this->set("dato_a", $dato_a);

        }//fin else

        $this->set("opcion", $var1);

    }//fin function

    public function llenar_pista_opcion($var1 = null)
    {

        $this->layout = "ajax";
        $this->Session->write('pista_opcion', $var1);

    }//fin fucntion

    public function lista_encontrados($pagina, $cc_cxargo, $cc_cxficha)
    {
        $this->layout = "ajax";
        $cond = "cedula_identidad=" . $pagina;
        $num = $this->cnmd06_datos_personales->findCount($cond);

        if($num == 1) {
            $this->Session->write('cedula_pestana_expediente', $pagina);
            $datacpcp01 = $this->cnmd06_datos_personales->findAll('cedula_identidad=' . $pagina);
            $vec = $this->cugd10_imagenes->findCount("cod_campo=11 and identificacion='" . $pagina . "'");
            if($vec != 0) {
                $this->set('existe_imagen', true);
            } else {
                $this->set('existe_imagen', false);
            }

            $pais = $this->cugd01_republica->findAll("cod_republica='" . $datacpcp01[0]["cnmd06_datos_personales"]["cod_pais_origen"] . "'");

            $this->set('pais', $pais);
            $estados = $this->cugd01_estados->findAll("cod_republica='" . $datacpcp01[0]["cnmd06_datos_personales"]["cod_pais_origen"] . "' and cod_estado=" . $datacpcp01[0]["cnmd06_datos_personales"]["cod_estado_origen"]);
            $this->set('estados', $estados);
            $municipios = $this->cugd01_municipios->findAll("cod_republica='" . $datacpcp01[0]["cnmd06_datos_personales"]["cod_pais_origen"] . "' and cod_estado=" . $datacpcp01[0]["cnmd06_datos_personales"]["cod_estado_origen"] . " and cod_municipio=" . $datacpcp01[0]["cnmd06_datos_personales"]["cod_municipio_origen"]);
            $this->set('municipios', $municipios);
            $parroquia = $this->cugd01_parroquias->findAll("cod_republica='" . $datacpcp01[0]["cnmd06_datos_personales"]["cod_pais_origen"] . "' and cod_estado=" . $datacpcp01[0]["cnmd06_datos_personales"]["cod_estado_origen"] . " and cod_municipio=" . $datacpcp01[0]["cnmd06_datos_personales"]["cod_municipio_origen"] . " and cod_parroquia=" . $datacpcp01[0]["cnmd06_datos_personales"]["cod_parroquia_origen"]);
            $this->set('parroquia', $parroquia);
            $centros = $this->cugd01_centropoblados->findAll("cod_republica='" . $datacpcp01[0]["cnmd06_datos_personales"]["cod_pais_origen"] . "' and cod_estado=" . $datacpcp01[0]["cnmd06_datos_personales"]["cod_estado_origen"] . " and cod_municipio=" . $datacpcp01[0]["cnmd06_datos_personales"]["cod_municipio_origen"] . " and cod_parroquia=" . $datacpcp01[0]["cnmd06_datos_personales"]["cod_parroquia_origen"] . " and cod_centro=" . $datacpcp01[0]["cnmd06_datos_personales"]["cod_centropoblado_origen"]);
            $this->set('centros', $centros);

            $estados = $this->cugd01_estados->findAll("cod_republica=1 and cod_estado=" . $datacpcp01[0]["cnmd06_datos_personales"]["cod_estado_habitacion"]);
            $this->set('estados_actual', $estados);
            $municipios = $this->cugd01_municipios->findAll("cod_republica=1 and cod_estado=" . $datacpcp01[0]["cnmd06_datos_personales"]["cod_estado_habitacion"] . " and cod_municipio=" . $datacpcp01[0]["cnmd06_datos_personales"]["cod_municipio_habitacion"]);
            $this->set('municipios_actual', $municipios);
            $parroquia = $this->cugd01_parroquias->findAll("cod_republica=1 and cod_estado=" . $datacpcp01[0]["cnmd06_datos_personales"]["cod_estado_habitacion"] . " and cod_municipio=" . $datacpcp01[0]["cnmd06_datos_personales"]["cod_municipio_habitacion"] . " and cod_parroquia=" . $datacpcp01[0]["cnmd06_datos_personales"]["cod_parroquia_habitacion"]);
            $this->set('parroquia_actual', $parroquia);
            $centros = $this->cugd01_centropoblados->findAll("cod_republica=1 and cod_estado=" . $datacpcp01[0]["cnmd06_datos_personales"]["cod_estado_habitacion"] . " and cod_municipio=" . $datacpcp01[0]["cnmd06_datos_personales"]["cod_municipio_habitacion"] . " and cod_parroquia=" . $datacpcp01[0]["cnmd06_datos_personales"]["cod_parroquia_habitacion"] . " and cod_centro=" . $datacpcp01[0]["cnmd06_datos_personales"]["cod_centropoblado_habitacion"]);
            $this->set('centros_actual', $centros);

            $colores = $this->cnmd06_colores->findAll("cod_color=" . $datacpcp01[0]["cnmd06_datos_personales"]["color_favorito"]);
            $this->set('colores', $colores[0]["cnmd06_colores"]["denominacion"]);

            $club = $this->cnmd06_clubes->findAll("cod_club=" . $datacpcp01[0]["cnmd06_datos_personales"]["club_pertenece"]);
            $this->set('club', $club[0]["cnmd06_clubes"]["denominacion"]);

            $deporte = $this->cnmd06_deportes->findAll();
            $this->set('deporte', $deporte);
            $religion = $this->cnmd06_religiones->findAll();
            $this->set('religion', $religion);
            $hobby = $this->cnmd06_hobby->findAll();
            $this->set('hobby', $hobby);

            $profesion = $this->cnmd06_profesiones->findAll();
            $this->set('profesion', $profesion);
            $especialidad = $this->cnmd06_especialidades->findAll("cod_profesion=" . $datacpcp01[0]["cnmd06_datos_personales"]["cod_profesion"]);
            $this->set('especialidad', $especialidad);
            $oficio = $this->cnmd06_oficio->findAll();
            $this->set('oficio', $oficio);
            $this->set('DATOS', $datacpcp01);
            $colores = $this->cnmd06_colores->findAll("cod_color=" . $datacpcp01[0]["cnmd06_datos_personales"]["color_favorito"]);
            //print_r($colores);
            $this->set('colores', $colores[0]["cnmd06_colores"]["denominacion"]);

            $club = $this->cnmd06_clubes->findAll("cod_club=" . $datacpcp01[0]["cnmd06_datos_personales"]["club_pertenece"]);
            //print_r($club);
            $this->set('club', $club[0]["cnmd06_clubes"]["denominacion"]);


            $datos = $this->cnmd06_datos_personales->execute("

							SELECT


									  a.cedula_identidad,
									  a.primer_apellido,
									  a.segundo_apellido,
									  a.primer_nombre,
									  a.segundo_nombre,


									  b.cod_presi,
									  b.cod_entidad,
									  b.cod_tipo_inst,
									  b.cod_inst,
									  b.cod_dep,
									  (SELECT x.denominacion  FROM arrd05 x WHERE
											  x.cod_presi           =     b.cod_presi       and
											  x.cod_entidad         =     b.cod_entidad     and
											  x.cod_tipo_inst       =     b.cod_tipo_inst   and
											  x.cod_inst            =     b.cod_inst        and
											  x.cod_dep             =     b.cod_dep
									  ) as denominacion_dependencia,
									  b.cod_tipo_nomina,
									  (SELECT x.denominacion  FROM cnmd01 x WHERE
											  x.cod_presi           =     b.cod_presi       and
											  x.cod_entidad         =     b.cod_entidad     and
											  x.cod_tipo_inst       =     b.cod_tipo_inst   and
											  x.cod_inst            =     b.cod_inst        and
											  x.cod_dep             =     b.cod_dep         and
											  x.cod_tipo_nomina     =     b.cod_tipo_nomina
									  ) as denominacion_nomina,
									  b.cod_cargo,
									  b.cod_ficha,
									  b.fecha_ingreso,
									  b.forma_pago,
									  b.cod_entidad_bancaria,
									  b.cod_sucursal,
									  b.cuenta_bancaria,
									  b.condicion_actividad,
									  b.funciones_realizar,
									  b.responsabilidad_administrativa,
									  b.horas_laborar,
									  b.porcentaje_jub_pension,
									  b.fecha_terminacion_contrato,
									  b.fecha_retiro,
									  b.motivo_retiro,
									  b.paso,
									  b.tipo_contrato,
									  b.situacion,
									  b.nivel,
									  b.categoria,


									  c.cod_puesto,
									  (select devolver_denominacion_puesto(
							               (select xy.clasificacion_personal from cnmd01 xy where
							                  xy.cod_presi           =     c.cod_presi       and
											  xy.cod_entidad         =     c.cod_entidad     and
											  xy.cod_tipo_inst       =     c.cod_tipo_inst   and
											  xy.cod_inst            =     c.cod_inst        and
											  xy.cod_dep             =     c.cod_dep         and
											  xy.cod_tipo_nomina     =     c.cod_tipo_nomina
							               ), c.cod_puesto )
							          ) as demonimacion_puesto,
							          (select devolver_grado_puesto(
							               (select xy.clasificacion_personal from cnmd01 xy where
							                  xy.cod_presi           =     c.cod_presi       and
											  xy.cod_entidad         =     c.cod_entidad     and
											  xy.cod_tipo_inst       =     c.cod_tipo_inst   and
											  xy.cod_inst            =     c.cod_inst        and
											  xy.cod_dep             =     c.cod_dep         and
											  xy.cod_tipo_nomina     =     c.cod_tipo_nomina
							               ), c.cod_puesto )
							          ) as grado_puesto,
									  c.sueldo_basico,
									  c.compensaciones,
									  c.primas,
									  c.bonos,
									  (c.compensaciones + c.primas + c.bonos) as otras_remuneraciones,
									  c.cod_dir_superior,
									  c.cod_coordinacion,
									  c.cod_secretaria,
									  c.cod_direccion,
									  c.cod_division,
									  c.cod_departamento,
									  c.cod_oficina,
										  (SELECT xa.denominacion FROM cugd02_direccionsuperior xa where xa.cod_tipo_institucion=c.cod_tipo_inst  and xa.cod_institucion=c.cod_inst and xa.cod_dependencia=c.cod_dep and xa.cod_dir_superior=c.cod_dir_superior                                                                                                                                                                                                                                        GROUP BY xa.denominacion) as  deno_cod_dir_superior,
										  (SELECT xb.denominacion FROM cugd02_coordinacion xb      where xb.cod_tipo_institucion=c.cod_tipo_inst  and xb.cod_institucion=c.cod_inst and xb.cod_dependencia=c.cod_dep and xb.cod_dir_superior=c.cod_dir_superior and xb.cod_coordinacion=c.cod_coordinacion                                                                                                                                                                                             GROUP BY xb.denominacion) as  deno_cod_coordinacion,
										  (SELECT xc.denominacion FROM cugd02_secretaria xc        where xc.cod_tipo_institucion=c.cod_tipo_inst  and xc.cod_institucion=c.cod_inst and xc.cod_dependencia=c.cod_dep and xc.cod_dir_superior=c.cod_dir_superior and xc.cod_coordinacion=c.cod_coordinacion and xc.cod_secretaria=c.cod_secretaria                                                                                                                                                      GROUP BY xc.denominacion) as  deno_cod_secretaria,
										  (SELECT xd.denominacion FROM cugd02_direccion xd         where xd.cod_tipo_institucion=c.cod_tipo_inst  and xd.cod_institucion=c.cod_inst and xd.cod_dependencia=c.cod_dep and xd.cod_dir_superior=c.cod_dir_superior and xd.cod_coordinacion=c.cod_coordinacion and xd.cod_secretaria=c.cod_secretaria and xd.cod_direccion=c.cod_direccion                                                                                                                 GROUP BY xd.denominacion) as  deno_cod_direccion,
										  (SELECT xe.denominacion FROM cugd02_division xe          where xe.cod_tipo_institucion=c.cod_tipo_inst  and xe.cod_institucion=c.cod_inst and xe.cod_dependencia=c.cod_dep and xe.cod_dir_superior=c.cod_dir_superior and xe.cod_coordinacion=c.cod_coordinacion and xe.cod_secretaria=c.cod_secretaria and xe.cod_direccion=c.cod_direccion  and xe.cod_division=c.cod_division                                                                             GROUP BY xe.denominacion) as  deno_cod_division,
										  (SELECT xf.denominacion FROM cugd02_departamento xf      where xf.cod_tipo_institucion=c.cod_tipo_inst  and xf.cod_institucion=c.cod_inst and xf.cod_dependencia=c.cod_dep and xf.cod_dir_superior=c.cod_dir_superior and xf.cod_coordinacion=c.cod_coordinacion and xf.cod_secretaria=c.cod_secretaria and xf.cod_direccion=c.cod_direccion  and xf.cod_division=c.cod_division and xf.cod_departamento=c.cod_departamento                                  GROUP BY xf.denominacion) as  deno_cod_departamento,
										  (SELECT xg.denominacion FROM cugd02_oficina xg           where xg.cod_tipo_institucion=c.cod_tipo_inst  and xg.cod_institucion=c.cod_inst and xg.cod_dependencia=c.cod_dep and xg.cod_dir_superior=c.cod_dir_superior and xg.cod_coordinacion=c.cod_coordinacion and xg.cod_secretaria=c.cod_secretaria and xg.cod_direccion=c.cod_direccion  and xg.cod_division=c.cod_division and xg.cod_departamento=c.cod_departamento and xg.cod_oficina=c.cod_oficina GROUP BY xg.denominacion) as  deno_cod_oficina,
									  c.cod_estado,
									  c.cod_municipio,
									  c.cod_parroquia,
									  c.cod_centro,
								          (SELECT xya.denominacion FROM cugd01_estados          xya where xya.cod_republica=c.cod_presi and xya.cod_estado=c.cod_estado                                                                                                                  GROUP BY xya.denominacion) as  deno_cod_estado,
										  (SELECT xyb.denominacion FROM cugd01_municipios       xyb where xyb.cod_republica=c.cod_presi and xyb.cod_estado=c.cod_estado  and xyb.cod_municipio=c.cod_municipio                                                                           GROUP BY xyb.denominacion) as  deno_cod_municipio,
										  (SELECT xyc.denominacion FROM cugd01_parroquias       xyc where xyc.cod_republica=c.cod_presi and xyc.cod_estado=c.cod_estado  and xyc.cod_municipio=c.cod_municipio and xyc.cod_parroquia = c.cod_parroquia                                   GROUP BY xyc.denominacion) as  deno_cod_parroquia,
										  (SELECT xyd.denominacion FROM cugd01_centros_poblados xyd where xyd.cod_republica=c.cod_presi and xyd.cod_estado=c.cod_estado  and xyd.cod_municipio=c.cod_municipio and xyd.cod_parroquia = c.cod_parroquia and xyd.cod_centro = c.cod_centro GROUP BY xyd.denominacion) as  deno_cod_centro,
										  (SELECT xyb.conocido     FROM cugd01_municipios       xyb where xyb.cod_republica=c.cod_presi and xyb.cod_estado=c.cod_estado  and xyb.cod_municipio=c.cod_municipio                                                                           GROUP BY xyb.conocido)     as  deno_ciudad,
									  c.condicion_actividad,
									  c.ano,
									  c.cod_sector,
									  c.cod_programa,
									  c.cod_sub_prog,
									  c.cod_proyecto,
									  c.cod_activ_obra,
									  c.cod_partida,
									  c.cod_generica,
									  c.cod_especifica,
									  c.cod_sub_espec,
									  c.cod_auxiliar,
									  c.cod_nivel_i,
									  c.cod_nivel_ii,
									  c.cod_ficha as cod_ficha_dos



							FROM


									 cnmd06_datos_personales         a,
									 cnmd06_fichas                   b,
									 cnmd05                          c


							WHERE
				                    a.cedula_identidad = '" . $pagina . "'          and
				                    b.cedula_identidad = a.cedula_identidad and
				                    b.cod_presi        = '" . $this->verifica_SS(1) . "'         and
									b.cod_entidad      = '" . $this->verifica_SS(2) . "'         and
									b.cod_tipo_inst    = '" . $this->verifica_SS(3) . "'         and
									b.cod_inst         = '" . $this->verifica_SS(4) . "'         and
									b.cod_cargo        = '" . $cc_cxargo . "'   and
									b.cod_ficha        = '" . $cc_cxficha . "'  and
				                    c.cod_presi        = b.cod_presi        and
									c.cod_entidad      = b.cod_entidad      and
									c.cod_tipo_inst    = b.cod_tipo_inst    and
									c.cod_inst         = b.cod_inst         and
									c.cod_dep          = b.cod_dep          and
									c.cod_tipo_nomina  = b.cod_tipo_nomina  and
									c.cod_cargo        = b.cod_cargo


				  LIMIT 1 OFFSET 0 * 1

				;");
            // b.condicion_actividad  = 1              and

            $totalPages_Recordset1 =  count($datos);

            if($totalPages_Recordset1 == 0) {

                echo"<script>
					      	    document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_1').style.display='block';
					            document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_2').style.display='block';
					            document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_3').style.display='block';
					            document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_4').style.display='block';
					            document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_5').style.display='block';
					            document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_6').style.display='block';
					            document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_7').style.display='block';
					            document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_8').style.display='block';
					            document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_9').style.display='block';
					            document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_10').style.display='none';
					            document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_11').style.display='none';
                      document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_11').style.display='none';
                      document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_11').style.display='none';
					          </script>";
            } else {


                if(isset($datos[0][0]["cod_presi"])) {
                    $cod_presi_exp          =  $datos[0][0]["cod_presi"];
                    $cod_entidad_exp        =  $datos[0][0]["cod_entidad"];
                    $cod_tipo_inst_exp      =  $datos[0][0]["cod_tipo_inst"];
                    $cod_inst_exp           =  $datos[0][0]["cod_inst"];
                    $cod_dep_exp            =  $datos[0][0]["cod_dep"];
                    $cod_tipo_nomina        =  $datos[0][0]["cod_tipo_nomina"];
                    $cod_cargo              =  $datos[0][0]["cod_cargo"];
                    $cod_ficha              =  $datos[0][0]["cod_ficha"];
                } else {

                    $cod_tipo_nomina        =  0;
                    $cod_cargo              =  0;
                    $cod_ficha              =  0;

                }//fin else

                $this->Session->write('cod_dep_expediente', $cod_dep_exp);
                $this->Session->write('cod_tipo_nomina_expediente', $cod_tipo_nomina);
                $this->Session->write('cod_cargo_expediente', $cod_cargo);
                $this->Session->write('cod_ficha_expediente', $cod_ficha);

                echo"<script>
					      	    document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_1').style.display='block';
					            document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_2').style.display='block';
					            document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_3').style.display='block';
					            document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_4').style.display='block';
					            document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_5').style.display='block';
					            document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_6').style.display='block';
					            document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_7').style.display='block';
					            document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_8').style.display='block';
					            document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_9').style.display='block';
					            document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_10').style.display='block';
					            document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_11').style.display='block';
                      document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_11').style.display='block';
					          </script>";

            }//fin else

        } else {//echo "no hay dato";

            echo"<script>
		          	    document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_1').style.display='none';
			            document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_2').style.display='none';
			            document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_3').style.display='none';
			            document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_4').style.display='none';
			            document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_5').style.display='none';
			            document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_6').style.display='none';
			            document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_7').style.display='none';
			            document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_8').style.display='none';
			            document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_9').style.display='none';
			            document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_10').style.display='none';
			            document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_11').style.display='none';
                  document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_11').style.display='none';
			          </script>";
            $this->set('errorMessage', 'No se encontrar&oacute;n datos');
            $this->preconsulta();
            $this->render("preconsulta");

            // }
        }//fin function consultar2
    }//fin function

    public function datos_personales($id = null)
    {
        $this->layout = "ajax";
        $this->set('entidad_federal', $this->Session->read('entidad_federal'));
        $listarepublica = $this->cugd01_republica->generateList(null, 'cod_republica ASC', null, '{n}.cugd01_republica.cod_republica', '{n}.cugd01_republica.denominacion');
        $this->concatena($listarepublica, 'cod_republica');


        $listaprofesion = $this->cnmd06_profesiones->generateList(null, 'denominacion ASC', null, '{n}.cnmd06_profesiones.cod_profesion', '{n}.cnmd06_profesiones.denominacion');
        $this->concatena_tres_digitos("", 'cod_profesion');


        $lista =  $this->cugd01_estados->generateList("cod_republica=1", 'cod_estado ASC', null, '{n}.cugd01_estados.cod_estado', '{n}.cugd01_estados.denominacion');
        $this->concatena($lista, 'cod_estado');

        $listaoficio =  $this->cnmd06_oficio->generateList(null, 'denominacion ASC', null, '{n}.cnmd06_oficio.cod_oficio', '{n}.cnmd06_oficio.denominacion');
        $this->concatena_tres_digitos("", 'oficio');


        $listacolor =  $this->cnmd06_colores->generateList(null, 'denominacion ASC', null, '{n}.cnmd06_colores.cod_color', '{n}.cnmd06_colores.denominacion');
        $this->concatena($listacolor, 'color');
        $listaclubes =  $this->cnmd06_clubes->generateList(null, 'denominacion ASC', null, '{n}.cnmd06_clubes.cod_club', '{n}.cnmd06_clubes.denominacion');
        $this->concatena_tres_digitos($listaclubes, 'club');
        $listadeporte =  $this->cnmd06_deportes->generateList(null, 'denominacion ASC', null, '{n}.cnmd06_deportes.cod_deporte', '{n}.cnmd06_deportes.denominacion');
        $this->concatena_tres_digitos($listadeporte, 'deporte');
        $listareligion =  $this->cnmd06_religiones->generateList(null, 'denominacion ASC', null, '{n}.cnmd06_religiones.cod_religion', '{n}.cnmd06_religiones.denominacion');
        $this->concatena($listareligion, 'religion');
        $listahobby =  $this->cnmd06_hobby->generateList(null, 'denominacion ASC', null, '{n}.cnmd06_hobby.cod_hobby', '{n}.cnmd06_hobby.denominacion');
        $this->concatena_tres_digitos($listahobby, 'hobby');


        echo"<script>
				          	    document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_1').style.display='none';
					            document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_2').style.display='none';
					            document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_3').style.display='none';
					            document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_4').style.display='none';
					            document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_5').style.display='none';
					            document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_6').style.display='none';
					            document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_7').style.display='none';
					            document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_8').style.display='none';
					            document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_9').style.display='none';
					            document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_10').style.display='none';
					            document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_11').style.display='none';
                      document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_11').style.display='none';
					          </script>";

        if($id != null) {
            if($id == "regresa") {
                $id = "";
            }
            $this->set('cedula', $id);
            $this->Session->write('cedula_pestana_expediente', "");
        } else {
            if($this->Session->read('cedula_pestana_expediente') == "") {
                $id = 0;
            } else {
                $id = $this->Session->read('cedula_pestana_expediente');
            }
            $this->set('cedula', "");
            $Tfilas = $this->cnmd06_datos_personales->findCount("cedula_identidad=" . $id);
            if($Tfilas != 0) {
                $this->busca_foto($this->Session->read('cedula_pestana_expediente'));
            }
        }//fin else
        $this->set('cedula', "");
    }//fin if

    public function bt_nav($Tfilas, $pagina)
    {
        if($Tfilas == 1) {
            $this->set('mostrarS', false);
            $this->set('mostrarA', false);
        } elseif($Tfilas == 2) {
            if($pagina == 2) {
                $this->set('mostrarS', false);
                $this->set('mostrarA', true);
            } else {
                $this->set('mostrarS', true);
                $this->set('mostrarA', false);
            }
        } elseif($Tfilas >= 3) {
            if($pagina == $Tfilas) {
                $this->set('mostrarS', false);
                $this->set('mostrarA', true);
            } elseif($pagina == 1) {
                $this->set('mostrarS', true);
                $this->set('mostrarA', false);
            } else {
                $this->set('mostrarS', true);
                $this->set('mostrarA', true);
            }
        }
    }//fin navegacion

    public function busca_foto($id)
    {
        $this->layout = "ajax";
        $vec = $this->cugd10_imagenes->findCount("cod_campo=11 and identificacion='" . $id . "'");
        if($vec != 0) {
            $this->set('existe_imagen', true);
        } else {
            $this->set('existe_imagen', false);
        }

        $pagina = 1;
        $Tfilas = $this->cnmd06_datos_personales->findCount("cedula_identidad=" . $id);
        if($Tfilas == 0) {

            $this->index2($id);
            $this->render("index2");
        } else {


            $datos = $this->cnmd06_datos_personales->execute("

			SELECT


					  a.cedula_identidad,
					  a.primer_apellido,
					  a.segundo_apellido,
					  a.primer_nombre,
					  a.segundo_nombre,


					  b.cod_presi,
					  b.cod_entidad,
					  b.cod_tipo_inst,
					  b.cod_inst,
					  b.cod_dep,
					  (SELECT x.denominacion  FROM arrd05 x WHERE
							  x.cod_presi           =     b.cod_presi       and
							  x.cod_entidad         =     b.cod_entidad     and
							  x.cod_tipo_inst       =     b.cod_tipo_inst   and
							  x.cod_inst            =     b.cod_inst        and
							  x.cod_dep             =     b.cod_dep
					  ) as denominacion_dependencia,
					  b.cod_tipo_nomina,
					  (SELECT x.denominacion  FROM cnmd01 x WHERE
							  x.cod_presi           =     b.cod_presi       and
							  x.cod_entidad         =     b.cod_entidad     and
							  x.cod_tipo_inst       =     b.cod_tipo_inst   and
							  x.cod_inst            =     b.cod_inst        and
							  x.cod_dep             =     b.cod_dep         and
							  x.cod_tipo_nomina     =     b.cod_tipo_nomina
					  ) as denominacion_nomina,
					  b.cod_cargo,
					  b.cod_ficha,
					  b.fecha_ingreso,
					  b.forma_pago,
					  b.cod_entidad_bancaria,
					  b.cod_sucursal,
					  b.cuenta_bancaria,
					  b.condicion_actividad,
					  b.funciones_realizar,
					  b.responsabilidad_administrativa,
					  b.horas_laborar,
					  b.porcentaje_jub_pension,
					  b.fecha_terminacion_contrato,
					  b.fecha_retiro,
					  b.motivo_retiro,
					  b.paso,
					  b.tipo_contrato,
					  b.situacion,
					  b.nivel,
					  b.categoria,


					  c.cod_puesto,
					  (select devolver_denominacion_puesto(
			               (select xy.clasificacion_personal from cnmd01 xy where
			                  xy.cod_presi           =     c.cod_presi       and
							  xy.cod_entidad         =     c.cod_entidad     and
							  xy.cod_tipo_inst       =     c.cod_tipo_inst   and
							  xy.cod_inst            =     c.cod_inst        and
							  xy.cod_dep             =     c.cod_dep         and
							  xy.cod_tipo_nomina     =     c.cod_tipo_nomina
			               ), c.cod_puesto )
			          ) as demonimacion_puesto,
			          (select devolver_grado_puesto(
			               (select xy.clasificacion_personal from cnmd01 xy where
			                  xy.cod_presi           =     c.cod_presi       and
							  xy.cod_entidad         =     c.cod_entidad     and
							  xy.cod_tipo_inst       =     c.cod_tipo_inst   and
							  xy.cod_inst            =     c.cod_inst        and
							  xy.cod_dep             =     c.cod_dep         and
							  xy.cod_tipo_nomina     =     c.cod_tipo_nomina
			               ), c.cod_puesto )
			          ) as grado_puesto,
					  c.sueldo_basico,
					  c.compensaciones,
					  c.primas,
					  c.bonos,
					  (c.compensaciones + c.primas + c.bonos) as otras_remuneraciones,
					  c.cod_dir_superior,
					  c.cod_coordinacion,
					  c.cod_secretaria,
					  c.cod_direccion,
					  c.cod_division,
					  c.cod_departamento,
					  c.cod_oficina,
						  (SELECT xa.denominacion FROM cugd02_direccionsuperior xa where xa.cod_tipo_institucion=c.cod_tipo_inst  and xa.cod_institucion=c.cod_inst and xa.cod_dependencia=c.cod_dep and xa.cod_dir_superior=c.cod_dir_superior                                                                                                                                                                                                                                        GROUP BY xa.denominacion) as  deno_cod_dir_superior,
						  (SELECT xb.denominacion FROM cugd02_coordinacion xb      where xb.cod_tipo_institucion=c.cod_tipo_inst  and xb.cod_institucion=c.cod_inst and xb.cod_dependencia=c.cod_dep and xb.cod_dir_superior=c.cod_dir_superior and xb.cod_coordinacion=c.cod_coordinacion                                                                                                                                                                                             GROUP BY xb.denominacion) as  deno_cod_coordinacion,
						  (SELECT xc.denominacion FROM cugd02_secretaria xc        where xc.cod_tipo_institucion=c.cod_tipo_inst  and xc.cod_institucion=c.cod_inst and xc.cod_dependencia=c.cod_dep and xc.cod_dir_superior=c.cod_dir_superior and xc.cod_coordinacion=c.cod_coordinacion and xc.cod_secretaria=c.cod_secretaria                                                                                                                                                      GROUP BY xc.denominacion) as  deno_cod_secretaria,
						  (SELECT xd.denominacion FROM cugd02_direccion xd         where xd.cod_tipo_institucion=c.cod_tipo_inst  and xd.cod_institucion=c.cod_inst and xd.cod_dependencia=c.cod_dep and xd.cod_dir_superior=c.cod_dir_superior and xd.cod_coordinacion=c.cod_coordinacion and xd.cod_secretaria=c.cod_secretaria and xd.cod_direccion=c.cod_direccion                                                                                                                 GROUP BY xd.denominacion) as  deno_cod_direccion,
						  (SELECT xe.denominacion FROM cugd02_division xe          where xe.cod_tipo_institucion=c.cod_tipo_inst  and xe.cod_institucion=c.cod_inst and xe.cod_dependencia=c.cod_dep and xe.cod_dir_superior=c.cod_dir_superior and xe.cod_coordinacion=c.cod_coordinacion and xe.cod_secretaria=c.cod_secretaria and xe.cod_direccion=c.cod_direccion  and xe.cod_division=c.cod_division                                                                             GROUP BY xe.denominacion) as  deno_cod_division,
						  (SELECT xf.denominacion FROM cugd02_departamento xf      where xf.cod_tipo_institucion=c.cod_tipo_inst  and xf.cod_institucion=c.cod_inst and xf.cod_dependencia=c.cod_dep and xf.cod_dir_superior=c.cod_dir_superior and xf.cod_coordinacion=c.cod_coordinacion and xf.cod_secretaria=c.cod_secretaria and xf.cod_direccion=c.cod_direccion  and xf.cod_division=c.cod_division and xf.cod_departamento=c.cod_departamento                                  GROUP BY xf.denominacion) as  deno_cod_departamento,
						  (SELECT xg.denominacion FROM cugd02_oficina xg           where xg.cod_tipo_institucion=c.cod_tipo_inst  and xg.cod_institucion=c.cod_inst and xg.cod_dependencia=c.cod_dep and xg.cod_dir_superior=c.cod_dir_superior and xg.cod_coordinacion=c.cod_coordinacion and xg.cod_secretaria=c.cod_secretaria and xg.cod_direccion=c.cod_direccion  and xg.cod_division=c.cod_division and xg.cod_departamento=c.cod_departamento and xg.cod_oficina=c.cod_oficina GROUP BY xg.denominacion) as  deno_cod_oficina,
					  c.cod_estado,
					  c.cod_municipio,
					  c.cod_parroquia,
					  c.cod_centro,
				          (SELECT xya.denominacion FROM cugd01_estados          xya where xya.cod_republica=c.cod_presi and xya.cod_estado=c.cod_estado                                                                                                                  GROUP BY xya.denominacion) as  deno_cod_estado,
						  (SELECT xyb.denominacion FROM cugd01_municipios       xyb where xyb.cod_republica=c.cod_presi and xyb.cod_estado=c.cod_estado  and xyb.cod_municipio=c.cod_municipio                                                                           GROUP BY xyb.denominacion) as  deno_cod_municipio,
						  (SELECT xyc.denominacion FROM cugd01_parroquias       xyc where xyc.cod_republica=c.cod_presi and xyc.cod_estado=c.cod_estado  and xyc.cod_municipio=c.cod_municipio and xyc.cod_parroquia = c.cod_parroquia                                   GROUP BY xyc.denominacion) as  deno_cod_parroquia,
						  (SELECT xyd.denominacion FROM cugd01_centros_poblados xyd where xyd.cod_republica=c.cod_presi and xyd.cod_estado=c.cod_estado  and xyd.cod_municipio=c.cod_municipio and xyd.cod_parroquia = c.cod_parroquia and xyd.cod_centro = c.cod_centro GROUP BY xyd.denominacion) as  deno_cod_centro,
						  (SELECT xyb.conocido     FROM cugd01_municipios       xyb where xyb.cod_republica=c.cod_presi and xyb.cod_estado=c.cod_estado  and xyb.cod_municipio=c.cod_municipio                                                                           GROUP BY xyb.conocido)     as  deno_ciudad,
					  c.condicion_actividad,
					  c.ano,
					  c.cod_sector,
					  c.cod_programa,
					  c.cod_sub_prog,
					  c.cod_proyecto,
					  c.cod_activ_obra,
					  c.cod_partida,
					  c.cod_generica,
					  c.cod_especifica,
					  c.cod_sub_espec,
					  c.cod_auxiliar,
					  c.cod_nivel_i,
					  c.cod_nivel_ii,
					  c.cod_ficha



			FROM


					 cnmd06_datos_personales         a,
					 cnmd06_fichas                   b,
					 cnmd05                          c


			WHERE
                    a.cedula_identidad = '" . $id . "'          and
                    b.cedula_identidad = a.cedula_identidad and
                    b.condicion_actividad  = 1              and
                    b.cod_presi        = '" . $this->verifica_SS(1) . "'         and
					b.cod_entidad      = '" . $this->verifica_SS(2) . "'         and
					b.cod_tipo_inst    = '" . $this->verifica_SS(3) . "'         and
					b.cod_inst         = '" . $this->verifica_SS(4) . "'         and
                    c.cod_presi        = b.cod_presi        and
					c.cod_entidad      = b.cod_entidad      and
					c.cod_tipo_inst    = b.cod_tipo_inst    and
					c.cod_inst         = b.cod_inst         and
					c.cod_dep          = b.cod_dep          and
					c.cod_tipo_nomina  = b.cod_tipo_nomina  and
					c.cod_cargo        = b.cod_cargo


  LIMIT 1 OFFSET 0 * 1

;");

            $totalPages_Recordset1 =  count($datos);

            if($totalPages_Recordset1 == 0) {

                echo"<script>
      	    document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_1').style.display='block';
            document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_2').style.display='block';
            document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_3').style.display='block';
            document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_4').style.display='block';
            document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_5').style.display='block';
            document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_6').style.display='block';
            document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_7').style.display='block';
            document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_8').style.display='block';
            document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_9').style.display='block';
            document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_10').style.display='none';
            document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_11').style.display='none';
            document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_11').style.display='none';
          </script>";
            } else {


                if(isset($datos[0][0]["cod_presi"])) {
                    $cod_presi_exp          =  $datos[0][0]["cod_presi"];
                    $cod_entidad_exp        =  $datos[0][0]["cod_entidad"];
                    $cod_tipo_inst_exp      =  $datos[0][0]["cod_tipo_inst"];
                    $cod_inst_exp           =  $datos[0][0]["cod_inst"];
                    $cod_dep_exp            =  $datos[0][0]["cod_dep"];
                    $cod_tipo_nomina        =  $datos[0][0]["cod_tipo_nomina"];
                    $cod_cargo              =  $datos[0][0]["cod_cargo"];
                    $cod_ficha              =  $datos[0][0]["cod_ficha"];
                } else {

                    $cod_tipo_nomina        =  0;
                    $cod_cargo              =  0;
                    $cod_ficha              =  0;

                }//fin else

                $this->Session->write('cod_dep_expediente', $cod_dep_exp);
                $this->Session->write('cod_tipo_nomina_expediente', $cod_tipo_nomina);
                $this->Session->write('cod_cargo_expediente', $cod_cargo);
                $this->Session->write('cod_ficha_expediente', $cod_ficha);

                echo"<script>
      	    document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_1').style.display='block';
            document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_2').style.display='block';
            document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_3').style.display='block';
            document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_4').style.display='block';
            document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_5').style.display='block';
            document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_6').style.display='block';
            document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_7').style.display='block';
            document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_8').style.display='block';
            document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_9').style.display='block';
            document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_10').style.display='block';
            document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_11').style.display='block';
            document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_11').style.display='block';
          </script>";

            }//fin else

            if($Tfilas != 0) {
                $this->set('pag_cant', $pagina . '/' . $Tfilas);
                $datacpcp01 = $this->cnmd06_datos_personales->findAll("cedula_identidad=" . $id, null, 'cedula_identidad ASC', 1, $pagina, null);
                /*$img=$this->cnmd06_imagenes->findCount("cedula=".$datacpcp01[0]["cnmd06_datos_personales"]["cedula_identidad"]);
                if($img!=0){
                    $this->set("TieneFoto",true);
                }*/
                $this->Session->write('cedula_pestana_expediente', $id);
                $pais = $this->cugd01_republica->findAll("cod_republica='" . $datacpcp01[0]["cnmd06_datos_personales"]["cod_pais_origen"] . "'");
                $this->set('pais', $pais);
                $estados = $this->cugd01_estados->findAll("cod_republica='" . $datacpcp01[0]["cnmd06_datos_personales"]["cod_pais_origen"] . "' and cod_estado=" . $datacpcp01[0]["cnmd06_datos_personales"]["cod_estado_origen"]);
                $this->set('estados', $estados);
                $municipios = $this->cugd01_municipios->findAll("cod_republica='" . $datacpcp01[0]["cnmd06_datos_personales"]["cod_pais_origen"] . "' and cod_estado=" . $datacpcp01[0]["cnmd06_datos_personales"]["cod_estado_origen"] . " and cod_municipio=" . $datacpcp01[0]["cnmd06_datos_personales"]["cod_municipio_origen"]);
                $this->set('municipios', $municipios);
                $parroquia = $this->cugd01_parroquias->findAll("cod_republica='" . $datacpcp01[0]["cnmd06_datos_personales"]["cod_pais_origen"] . "' and cod_estado=" . $datacpcp01[0]["cnmd06_datos_personales"]["cod_estado_origen"] . " and cod_municipio=" . $datacpcp01[0]["cnmd06_datos_personales"]["cod_municipio_origen"] . " and cod_parroquia=" . $datacpcp01[0]["cnmd06_datos_personales"]["cod_parroquia_origen"]);
                $this->set('parroquia', $parroquia);
                $centros = $this->cugd01_centropoblados->findAll("cod_republica='" . $datacpcp01[0]["cnmd06_datos_personales"]["cod_pais_origen"] . "' and cod_estado=" . $datacpcp01[0]["cnmd06_datos_personales"]["cod_estado_origen"] . " and cod_municipio=" . $datacpcp01[0]["cnmd06_datos_personales"]["cod_municipio_origen"] . " and cod_parroquia=" . $datacpcp01[0]["cnmd06_datos_personales"]["cod_parroquia_origen"] . " and cod_centro=" . $datacpcp01[0]["cnmd06_datos_personales"]["cod_centropoblado_origen"]);
                $this->set('centros', $centros);

                $estados = $this->cugd01_estados->findAll("cod_republica=1 and cod_estado=" . $datacpcp01[0]["cnmd06_datos_personales"]["cod_estado_habitacion"]);
                $this->set('estados_actual', $estados);
                $municipios = $this->cugd01_municipios->findAll("cod_republica=1 and cod_estado=" . $datacpcp01[0]["cnmd06_datos_personales"]["cod_estado_habitacion"] . " and cod_municipio=" . $datacpcp01[0]["cnmd06_datos_personales"]["cod_municipio_habitacion"]);
                $this->set('municipios_actual', $municipios);
                $parroquia = $this->cugd01_parroquias->findAll("cod_republica=1 and cod_estado=" . $datacpcp01[0]["cnmd06_datos_personales"]["cod_estado_habitacion"] . " and cod_municipio=" . $datacpcp01[0]["cnmd06_datos_personales"]["cod_municipio_habitacion"] . " and cod_parroquia=" . $datacpcp01[0]["cnmd06_datos_personales"]["cod_parroquia_habitacion"]);
                $this->set('parroquia_actual', $parroquia);
                $centros = $this->cugd01_centropoblados->findAll("cod_republica=1 and cod_estado=" . $datacpcp01[0]["cnmd06_datos_personales"]["cod_estado_habitacion"] . " and cod_municipio=" . $datacpcp01[0]["cnmd06_datos_personales"]["cod_municipio_habitacion"] . " and cod_parroquia=" . $datacpcp01[0]["cnmd06_datos_personales"]["cod_parroquia_habitacion"] . " and cod_centro=" . $datacpcp01[0]["cnmd06_datos_personales"]["cod_centropoblado_habitacion"]);
                $this->set('centros_actual', $centros);

                $colores = $this->cnmd06_colores->findAll("cod_color=" . $datacpcp01[0]["cnmd06_datos_personales"]["color_favorito"]);
                $this->set('colores', $colores[0]["cnmd06_colores"]["denominacion"]);

                $club = $this->cnmd06_clubes->findAll("cod_club=" . $datacpcp01[0]["cnmd06_datos_personales"]["club_pertenece"]);
                $this->set('club', $club[0]["cnmd06_clubes"]["denominacion"]);
                $deporte = $this->cnmd06_deportes->findAll();
                $this->set('deporte', $deporte);
                $religion = $this->cnmd06_religiones->findAll();
                $this->set('religion', $religion);
                $hobby = $this->cnmd06_hobby->findAll();
                $this->set('hobby', $hobby);

                $profesion = $this->cnmd06_profesiones->findAll();
                $this->set('profesion', $profesion);
                $especialidad = $this->cnmd06_especialidades->findAll("cod_profesion=" . $datacpcp01[0]["cnmd06_datos_personales"]["cod_profesion"]);
                $this->set('especialidad', $especialidad);
                $oficio = $this->cnmd06_oficio->findAll();
                $this->set('oficio', $oficio);
                $this->set('DATOS', $datacpcp01);
                $this->set('siguiente', $pagina + 1);
                $this->set('anterior', $pagina - 1);
                $this->bt_nav($Tfilas, $pagina);

                $this->render("consulta_pestanas");

            }//fin else
        }//fin else

    }//fin if

    public function datos_educativos()
    {
        if($this->Session->read('cedula_pestana_expediente') == "") {
            $id = 0;
        } else {
            $id = $this->Session->read('cedula_pestana_expediente');
        }//fin else

        $y = $this->v_cnmd06_datos_educativos->findCount("cedula=" . $id);

        if($y == 0) {
            $this->index_educativo();
        } else {
            $this->consultar_educativo();
            $this->render("consultar_educativo");
        }//fin else
    }//fin function

    public function consultar_educativo($pagina = null)
    {
        $this->layout = "ajax";

        if($this->Session->read('cedula_pestana_expediente') == "") {
            $id = 0;
        } else {
            $id = $this->Session->read('cedula_pestana_expediente');
        }//fin else
        $Tfilas = $this->cnmd06_datos_personales->findCount("cedula_identidad=" . $id);
        if($Tfilas != 0) {
            $cond2 = "cedula=" . $id;
        } else {
            $cond2 = "";
        }//fin else


        if($pagina != null) {
            $pagina = $pagina;
            $Tfilas = $this->v_cnmd06_datos_educativos->findCount($cond2);
            if($Tfilas == 0) {
                $this->index();
                //$this->render("index");
            }
            if($Tfilas != 0) {
                $this->set('pag_cant', $pagina . '/' . $Tfilas);
                $datos = $this->v_cnmd06_datos_educativos->findAll($cond2, null, 'cedula ASC', 1, $pagina, null);
                $this->set('datos', $datos);
                $this->set('siguiente', $pagina + 1);
                $this->set('anterior', $pagina - 1);
                $this->bt_nav($Tfilas, $pagina);
            }
        } else {
            $pagina = 1;
            $Tfilas = $this->v_cnmd06_datos_educativos->findCount($cond2);
            if($Tfilas == 0) {
                $this->index();
                //$this->render("index");
            }
            if($Tfilas != 0) {
                $this->set('pag_cant', $pagina . '/' . $Tfilas);
                $datos = $this->v_cnmd06_datos_educativos->findAll($cond2, null, 'cedula ASC', 1, $pagina, null);
            }
            $this->set('datos', $datos);
            $this->set('siguiente', $pagina + 1);
            $this->set('anterior', $pagina - 1);
            $this->bt_nav($Tfilas, $pagina);
        }//fin else

    }//fin function

    public function index_educativo()
    {
        $this->layout = "ajax";
        $this->data = null;
        $this->set('entidad_federal', $this->Session->read('entidad_federal'));
        $listarepublica = $this->cugd01_republica->generateList(null, 'cod_republica ASC', null, '{n}.cugd01_republica.cod_republica', '{n}.cugd01_republica.denominacion');
        $this->concatena($listarepublica, 'cod_republica');
        $listanivel = $this->cnmd06_nivel_educacion->generateList(null, 'cod_nivel_educativo ASC', null, '{n}.cnmd06_nivel_educacion.cod_nivel_educativo', '{n}.cnmd06_nivel_educacion.denominacion');
        $this->concatena($listanivel, 'cod_nivel_educativo');

        $listainstitucion = $this->cnmd06_instituto_educativo->generateList(null, 'denominacion ASC', null, '{n}.cnmd06_instituto_educativo.cod_institucion', '{n}.cnmd06_instituto_educativo.denominacion');
        $this->concatena_cuatro_digitos("", 'cod_institucion');


        if($this->Session->read('cedula_pestana_expediente') == "") {
            $id = 0;
        } else {
            $id = $this->Session->read('cedula_pestana_expediente');
        }//fin else

        $this->set('cedula', "");
        $Tfilas = $this->cnmd06_datos_personales->findCount("cedula_identidad=" . $id);
        if($Tfilas != 0) {
            $this->cedula($this->Session->read('cedula_pestana_expediente'));
            $this->render("cedula");
        } else {
            $this->set('ci', "");
            $this->set('pa', "");
            $this->set('sa', "");
            $this->set('pn', "");
            $this->set('sn', "");
        }//fin else

    }//fin function

    public function cedula($ci = null)
    {
        $this->layout = "ajax";
        $cond = "cedula_identidad=" . $ci;
        $cond2 = "cedula=" . $ci;
        $resul = $this->cnmd06_datos_educativos->findCount($cond2);
        $resul = 0;
        if($resul == 0) {
            $a = $this->cnmd06_datos_personales->findAll($cond);
            $pa = $a[0]['cnmd06_datos_personales']['primer_apellido'];
            $sa = $a[0]['cnmd06_datos_personales']['segundo_apellido'];
            $pn = $a[0]['cnmd06_datos_personales']['primer_nombre'];
            $sn = $a[0]['cnmd06_datos_personales']['segundo_nombre'];
            $this->set('ci', $ci);
            $this->set('pa', $pa);
            $this->set('sa', $sa);
            $this->set('pn', $pn);
            $this->set('sn', $sn);
            //$this->set('errorMessage', 'No se encontro informacion para esa cedula');
        } else {
            $pagina = 1;
            $Tfilas = $this->v_cnmd06_datos_educativos->findCount($cond2);
            if($Tfilas == 0) {
                $this->index();
                $this->render("index");
            }
            if($Tfilas != 0) {
                $this->set('pag_cant', $pagina . '/' . $Tfilas);
                $datos = $this->v_cnmd06_datos_educativos->findAll($cond2, null, 'cedula ASC', 1, $pagina, null);
            }
            $this->set('datos', $datos);
            $this->set('siguiente', $pagina + 1);
            $this->set('anterior', $pagina - 1);
            $this->bt_nav($Tfilas, $pagina);


        }//fin else
    }

    public function datos_formacion_profesional()
    {
        if($this->Session->read('cedula_pestana_expediente') == "") {
            $id = 0;
        } else {
            $id = $this->Session->read('cedula_pestana_expediente');
        }//fin else

        $y = $this->cnmd06_datos_formacion_profesional->findCount("cedula=" . $id);

        if($y == 0) {
            $this->index_profesional();
        } else {
            $this->consultar_profesional();
            $this->render("consultar_profesional");
        }//fin else
    }

    public function index_profesional($id = null)
    {
        $this->layout = "ajax";
        $this->data = null;
        $this->set('entidad_federal', $this->Session->read('entidad_federal'));
        $listacurso = $this->cnmd06_cursos->generateList(null, 'denominacion ASC', null, '{n}.cnmd06_cursos.cod_curso', '{n}.cnmd06_cursos.denominacion');
        $this->concatena_cuatro_digitos("", 'cod_curso');
        $listainstitucion = $this->cnmd06_instituto_educativo->generateList(null, 'denominacion ASC', null, '{n}.cnmd06_instituto_educativo.cod_institucion', '{n}.cnmd06_instituto_educativo.denominacion');
        $this->concatena_cuatro_digitos("", 'cod_institucion');

        if($this->Session->read('cedula_pestana_expediente') == "") {
            $id = 0;
        } else {
            $id = $this->Session->read('cedula_pestana_expediente');
        }//fin else

        $this->set('cedula', "");
        $Tfilas = $this->cnmd06_datos_personales->findCount("cedula_identidad=" . $id);
        if($Tfilas != 0) {
            $this->cedula_profesional($this->Session->read('cedula_pestana_expediente'));
            $this->render("cedula_profesional");
        } else {
            $this->set('ci', "");
            $this->set('pa', "");
            $this->set('sa', "");
            $this->set('pn', "");
            $this->set('sn', "");
        }//fin else
    }//fin function

    public function cedula_profesional($ci = null)
    {
        $this->layout = "ajax";
        $cond = "cedula_identidad=" . $ci;
        $cond2 = "cedula=" . $ci;
        $resul = 0;
        if($resul == 0) {
            $a = $this->cnmd06_datos_personales->findAll($cond);
            $pa = $a[0]['cnmd06_datos_personales']['primer_apellido'];
            $sa = $a[0]['cnmd06_datos_personales']['segundo_apellido'];
            $pn = $a[0]['cnmd06_datos_personales']['primer_nombre'];
            $sn = $a[0]['cnmd06_datos_personales']['segundo_nombre'];
            $this->set('ci', $ci);
            $this->set('pa', $pa);
            $this->set('sa', $sa);
            $this->set('pn', $pn);
            $this->set('sn', $sn);
            //$this->set('errorMessage', 'No se encontro informacion para esa cedula');
        } else {
            $pagina = 1;
            $Tfilas = $this->v_cnmd06_datos_formacion_profesional->findCount($cond2);
            if($Tfilas == 0) {
                $this->index_profesional();
                $this->render("index_profesional");
            }
            if($Tfilas != 0) {
                $this->set('pag_cant', $pagina . '/' . $Tfilas);
                $datos = $this->v_cnmd06_datos_formacion_profesional->findAll($cond2, null, 'cedula ASC', 1, $pagina, null);
            }
            $this->set('datos', $datos);
            $this->set('siguiente', $pagina + 1);
            $this->set('anterior', $pagina - 1);
            $this->bt_nav($Tfilas, $pagina);
        }//fin else
    }//fin function

    public function consultar_profesional($pagina = null)
    {
        $this->layout = "ajax";

        if($this->Session->read('cedula_pestana_expediente') == "") {
            $id = 0;
        } else {
            $id = $this->Session->read('cedula_pestana_expediente');
        }//fin else
        $Tfilas = $this->cnmd06_datos_personales->findCount("cedula_identidad=" . $id);
        if($Tfilas != 0) {
            $cond2 = "cedula=" . $id;
        } else {
            $cond2 = "";
        }//fin else


        if($pagina != null) {
            $pagina = $pagina;
            $Tfilas = $this->v_cnmd06_datos_formacion_profesional->findCount($cond2);
            if($Tfilas == 0) {
                $this->index_profesional();
                //$this->render("index");
            }
            if($Tfilas != 0) {
                $this->set('pag_cant', $pagina . '/' . $Tfilas);
                $datos = $this->v_cnmd06_datos_formacion_profesional->findAll($cond2, null, 'cedula ASC', 1, $pagina, null);
                $this->set('datos', $datos);
                $this->set('siguiente', $pagina + 1);
                $this->set('anterior', $pagina - 1);
                $this->bt_nav($Tfilas, $pagina);
            }
        } else {
            $pagina = 1;
            $Tfilas = $this->v_cnmd06_datos_formacion_profesional->findCount($cond2);
            if($Tfilas == 0) {
                $this->index_profesional();
                //$this->render("index");
            }
            if($Tfilas != 0) {
                $this->set('pag_cant', $pagina . '/' . $Tfilas);
                $datos = $this->v_cnmd06_datos_formacion_profesional->findAll($cond2, null, 'cedula ASC', 1, $pagina, null);
            }
            $this->set('datos', $datos);
            $this->set('siguiente', $pagina + 1);
            $this->set('anterior', $pagina - 1);
            $this->bt_nav($Tfilas, $pagina);
        }
    }//fin function

    public function registro_titulo()
    {
        if($this->Session->read('cedula_pestana_expediente') == "") {
            $id = 0;
        } else {
            $id = $this->Session->read('cedula_pestana_expediente');
        }//fin else

        $y = $this->v_cnmd06_datos_registro_titulo->findCount("cedula=" . $id);

        if($y == 0) {
            $this->index_titulo();
        } else {
            $this->consultar_titulo();
            $this->render("consultar_titulo");
        }//fin else
    }

    public function index_titulo()
    {
        $this->layout = "ajax";
        $this->set('entidad_federal', $this->Session->read('entidad_federal'));
        $listacurso = $this->cnmd06_profesiones->generateList(null, 'denominacion ASC', null, '{n}.cnmd06_profesiones.cod_profesion', '{n}.cnmd06_profesiones.denominacion');
        $this->concatena_tres_digitos($listacurso, 'cod_profesion');
        $listacolegio = $this->cnmd06_colegio_profesional->generateList(null, 'denominacion ASC', null, '{n}.cnmd06_colegio_profesional.cod_colegio', '{n}.cnmd06_colegio_profesional.denominacion');
        $this->concatena_tres_digitos("", 'cod_colegio');

        if($this->Session->read('cedula_pestana_expediente') == "") {
            $id = 0;
        } else {
            $id = $this->Session->read('cedula_pestana_expediente');
        }//fin else
        $this->set('cedula', "");
        $Tfilas = $this->cnmd06_datos_personales->findCount("cedula_identidad=" . $id);
        if($Tfilas != 0) {
            $this->cedula_titulo($this->Session->read('cedula_pestana_expediente'));
            $this->render("cedula_titulo");
        } else {
            $this->set('ci', "");
            $this->set('pa', "");
            $this->set('sa', "");
            $this->set('pn', "");
            $this->set('sn', "");
        }//fin else

    }//fin function

    public function cedula_titulo($ci = null)
    {
        $this->layout = "ajax";
        $cond = "cedula_identidad=" . $ci;
        $cond2 = "cedula=" . $ci;
        $resul = 0;
        if($resul == 0) {
            $a = $this->cnmd06_datos_personales->findAll($cond);
            $pa = $a[0]['cnmd06_datos_personales']['primer_apellido'];
            $sa = $a[0]['cnmd06_datos_personales']['segundo_apellido'];
            $pn = $a[0]['cnmd06_datos_personales']['primer_nombre'];
            $sn = $a[0]['cnmd06_datos_personales']['segundo_nombre'];
            $this->set('ci', $ci);
            $this->set('pa', $pa);
            $this->set('sa', $sa);
            $this->set('pn', $pn);
            $this->set('sn', $sn);
            //$this->set('errorMessage', 'No se encontro informacion para esa cedula');
        } else {
            $pagina = 1;
            $Tfilas = $this->v_cnmd06_datos_registro_titulo->findCount($cond2);
            if($Tfilas == 0) {
                $this->index_titulo();
                $this->render("index_titulo");
            }
            if($Tfilas != 0) {
                $this->set('pag_cant', $pagina . '/' . $Tfilas);
                $datos = $this->v_cnmd06_datos_registro_titulo->findAll($cond2, null, 'cedula ASC', 1, $pagina, null);
            }
            $this->set('datos', $datos);
            $this->set('siguiente', $pagina + 1);
            $this->set('anterior', $pagina - 1);
            $this->bt_nav($Tfilas, $pagina);
        }//fin else
    }//fin function

    public function consultar_titulo($pagina = null)
    {
        $this->layout = "ajax";

        if($this->Session->read('cedula_pestana_expediente') == "") {
            $id = 0;
        } else {
            $id = $this->Session->read('cedula_pestana_expediente');
        }//fin else
        $Tfilas = $this->cnmd06_datos_personales->findCount("cedula_identidad=" . $id);
        if($Tfilas != 0) {
            $cond2 = "cedula=" . $id;
        } else {
            $cond2 = "cedula=0";
        }//fin else


        if($pagina != null) {
            $pagina = $pagina;
            $Tfilas = $this->v_cnmd06_datos_registro_titulo->findCount($cond2);
            if($Tfilas == 0) {
                $this->index_titulo();
                //$this->render("index");
            }
            if($Tfilas != 0) {
                $this->set('pag_cant', $pagina . '/' . $Tfilas);
                $datos = $this->v_cnmd06_datos_registro_titulo->findAll($cond2, null, 'cedula ASC', 1, $pagina, null);
                $this->set('datos', $datos);
                $this->set('siguiente', $pagina + 1);
                $this->set('anterior', $pagina - 1);
                $this->bt_nav($Tfilas, $pagina);
            }
        } else {
            $pagina = 1;
            $Tfilas = $this->v_cnmd06_datos_registro_titulo->findCount($cond2);
            if($Tfilas == 0) {
                $this->index_titulo();
                //$this->render("index");
            }
            if($Tfilas != 0) {
                $this->set('pag_cant', $pagina . '/' . $Tfilas);
                $datos = $this->v_cnmd06_datos_registro_titulo->findAll($cond2, null, 'cedula ASC', 1, $pagina, null);
            }
            $this->set('datos', $datos);
            $this->set('siguiente', $pagina + 1);
            $this->set('anterior', $pagina - 1);
            $this->bt_nav($Tfilas, $pagina);
        }
    }//fin if

    public function familiares()
    {

        if($this->Session->read('cedula_pestana_expediente') == "") {
            $id = 0;
        } else {
            $id = $this->Session->read('cedula_pestana_expediente');
        }//fin else

        $y = $this->cnmd06_datos_familiares->findCount("cedula=" . $id);

        if($y == 0) {
            $this->index_familiares();
        } else {
            $this->consultar_familiares();
            $this->render("consultar_familiares");
        }//fin else
    }//fin function

    public function index_familiares()
    {
        $this->layout = "ajax";
        $this->data = null;
        $this->set('entidad_federal', $this->Session->read('entidad_federal'));
        $listacurso = $this->cnmd06_parentesco->generateList(null, 'denominacion ASC', null, '{n}.cnmd06_parentesco.cod_parentesco', '{n}.cnmd06_parentesco.denominacion');
        $this->concatena($listacurso, 'cod_parentesco');

        if($this->Session->read('cedula_pestana_expediente') == "") {
            $id = 0;
        } else {
            $id = $this->Session->read('cedula_pestana_expediente');
        }//fin else
        $this->set('cedula', "");
        $Tfilas = $this->cnmd06_datos_personales->findCount("cedula_identidad=" . $id);
        if($Tfilas != 0) {
            $this->cedula_familiares($this->Session->read('cedula_pestana_expediente'));
            $this->render("cedula_familiares");
        } else {
            $this->set('ci', "");
            $this->set('pa', "");
            $this->set('sa', "");
            $this->set('pn', "");
            $this->set('sn', "");
        }//fin else


        $listaguarderia = $this->cnmd06_guarderias->generateList(null, 'denominacion ASC', null, '{n}.cnmd06_guarderias.cod_guarderia', '{n}.cnmd06_guarderias.denominacion');
        if($listaguarderia == null) {
            $this->set('cod_guarderia', array('0' => '00'));
        } else {
            $this->concatena($listaguarderia, 'cod_guarderia');
        }
    }//fin function}

    public function cedula_familiares($ci = null)
    {
        $this->layout = "ajax";
        $cond = "cedula_identidad=" . $ci;
        $cond2 = "cedula=" . $ci;
        $resul = 0;
        if($resul == 0) {
            $a = $this->cnmd06_datos_personales->findAll($cond);
            $pa = $a[0]['cnmd06_datos_personales']['primer_apellido'];
            $sa = $a[0]['cnmd06_datos_personales']['segundo_apellido'];
            $pn = $a[0]['cnmd06_datos_personales']['primer_nombre'];
            $sn = $a[0]['cnmd06_datos_personales']['segundo_nombre'];
            $this->set('ci', $ci);
            $this->set('pa', $pa);
            $this->set('sa', $sa);
            $this->set('pn', $pn);
            $this->set('sn', $sn);
            //$this->set('errorMessage', 'No se encontro informacion para esa cedula');
        } else {
            $pagina = 1;
            $Tfilas = $this->v_cnmd06_datos_familiares->findCount($cond2);
            if($Tfilas == 0) {
                $this->index_familiares();
                $this->render("index_familiares");
            }
            if($Tfilas != 0) {
                $this->set('pag_cant', $pagina . '/' . $Tfilas);
                $datos = $this->v_cnmd06_datos_familiares->findAll($cond2, null, 'cedula ASC', 1, $pagina, null);
            }
            $this->set('datos', $datos);
            $this->set('siguiente', $pagina + 1);
            $this->set('anterior', $pagina - 1);
            $this->bt_nav($Tfilas, $pagina);


        }//fin else

        $listaguarderia = $this->cnmd06_guarderias->generateList(null, 'denominacion ASC', null, '{n}.cnmd06_guarderias.cod_guarderia', '{n}.cnmd06_guarderias.denominacion');
        if($listaguarderia == null) {
            $this->set('cod_guarderia', array('0' => '00'));
        } else {
            $this->concatena($listaguarderia, 'cod_guarderia');
        }
    }//fin function

    public function consultar_familiares($pagina = null)
    {
        $this->layout = "ajax";

        if($this->Session->read('cedula_pestana_expediente') == "") {
            $id = 0;
        } else {
            $id = $this->Session->read('cedula_pestana_expediente');
        }//fin else
        $Tfilas = $this->cnmd06_datos_personales->findCount("cedula_identidad=" . $id);
        if($Tfilas != 0) {
            $cond2 = "cedula=" . $id;
        } else {
            $cond2 = "cedula=" . $id;
        }//fin else



        if($pagina != null) {
            $pagina = $pagina;
            $Tfilas = $this->v_cnmd06_datos_familiares->findCount($cond2);
            if($Tfilas == 0) {
                $this->index_familiares();
                //$this->render("index");
            }
            if($Tfilas != 0) {
                $this->set('pag_cant', $pagina . '/' . $Tfilas);
                $datos = $this->v_cnmd06_datos_familiares->findAll($cond2, null, 'cedula ASC');
                $this->set('datos', $datos);
                $this->set('siguiente', $pagina + 1);
                $this->set('anterior', $pagina - 1);
                $this->bt_nav($Tfilas, $pagina);
            }
        } else {
            $pagina = 1;
            $Tfilas = $this->v_cnmd06_datos_familiares->findCount($cond2);
            if($Tfilas == 0) {
                $this->index_familiares();
                //$this->render("index");
            }
            if($Tfilas != 0) {
                $this->set('pag_cant', $pagina . '/' . $Tfilas);
                $datos = $this->v_cnmd06_datos_familiares->findAll($cond2, null, 'cedula ASC');
            }
            $this->set('datos', $datos);
            $this->set('siguiente', $pagina + 1);
            $this->set('anterior', $pagina - 1);
            $this->bt_nav($Tfilas, $pagina);
        }

        $listaguarderia = $this->cnmd06_guarderias->generateList(null, 'cod_guarderia ASC', null, '{n}.cnmd06_guarderias.cod_guarderia', '{n}.cnmd06_guarderias.denominacion');
        $this->concatena($listaguarderia, 'cod_guarderia');

        $this->set("deno_guar", $this->cnmd06_guarderias->findAll());

    }//fin function


    public function experiencia_administrativa($id = null)
    {
        $this->layout = "ajax";
        $cod_presi = $this->Session->read('SScodpresi');
        $cod_entidad = $this->Session->read('SScodentidad');
        $cod_tipo_inst = $this->Session->read('SScodtipoinst');
        $cod_inst = $this->Session->read('SScodinst');
        $cod_dep = $this->Session->read('SScoddep');
        $modulo = $this->Session->read('Modulo');
        $condicion = "cod_presi = " . $cod_presi . " and cod_entidad = " . $cod_entidad . " and cod_tipo_inst = " . $cod_tipo_inst . " and cod_inst = " . $cod_inst . " and cod_dep = " . $cod_dep;


        if($this->Session->read('cedula_pestana_expediente') == "") {
            $id = 0;
        } else {
            $id = $this->Session->read('cedula_pestana_expediente');
        }//fin else

        $this->set('cedula', "");
        $Tfilas = $this->cnmd06_datos_personales->findCount("cedula_identidad=" . $id);
        if($Tfilas != 0) {
            $this->index_experiencia_administrativa($this->Session->read('cedula_pestana_expediente'));
            $this->render("index_experiencia_administrativa");
        } else {
            $this->set('ci', "");
            $this->set('pa', "");
            $this->set('sa', "");
            $this->set('pn', "");
            $this->set('sn', "");
        }//fin else
    }//fin funtion

    public function index_experiencia_administrativa($var = null)
    {
        $this->layout = "ajax";
        $cod_presi = $this->Session->read('SScodpresi');
        $cod_entidad = $this->Session->read('SScodentidad');
        $cod_tipo_inst = $this->Session->read('SScodtipoinst');
        $cod_inst = $this->Session->read('SScodinst');
        $cod_dep = $this->Session->read('SScoddep');
        $modulo = $this->Session->read('Modulo');
        $condicion = "cod_presi = " . $cod_presi . " and cod_entidad = " . $cod_entidad . " and cod_tipo_inst = " . $cod_tipo_inst . " and cod_inst = " . $cod_inst . " and cod_dep = " . $cod_dep;
        $accion = "";

        if($var != null) {
            $accion =  $this->cnmd06_experiencia_administrativa->findAll('cedula=' . $var, null, "fecha_ingreso ASC");
        }//fin if


        $cond = "cedula_identidad=" . $var;

        $a = $this->cnmd06_datos_personales->findAll($cond);
        $pa = $a[0]['cnmd06_datos_personales']['primer_apellido'];
        $sa = $a[0]['cnmd06_datos_personales']['segundo_apellido'];
        $pn = $a[0]['cnmd06_datos_personales']['primer_nombre'];
        $sn = $a[0]['cnmd06_datos_personales']['segundo_nombre'];
        $this->set('ci', $var);
        $this->set('pa', $pa);
        $this->set('sa', $sa);
        $this->set('pn', $pn);
        $this->set('sn', $sn);

        $disabled = "false";
        $this->set('disabled', $disabled);
        $this->set('accion', $accion);
    }//fin funtion

    public function otrasexperiencias_laborables($id = null)
    {
        $this->layout = "ajax";
        $cod_presi = $this->Session->read('SScodpresi');
        $cod_entidad = $this->Session->read('SScodentidad');
        $cod_tipo_inst = $this->Session->read('SScodtipoinst');
        $cod_inst = $this->Session->read('SScodinst');
        $cod_dep = $this->Session->read('SScoddep');
        $modulo = $this->Session->read('Modulo');
        $condicion = "cod_presi = " . $cod_presi . " and cod_entidad = " . $cod_entidad . " and cod_tipo_inst = " . $cod_tipo_inst . " and cod_inst = " . $cod_inst . " and cod_dep = " . $cod_dep;

        if($this->Session->read('cedula_pestana_expediente') == "") {
            $id = 0;
        } else {
            $id = $this->Session->read('cedula_pestana_expediente');
        }//fin else

        $this->set('cedula', "");
        $Tfilas = $this->cnmd06_datos_personales->findCount("cedula_identidad=" . $id);
        if($Tfilas != 0) {
            $this->index_otrasexperiencias_laborables($this->Session->read('cedula_pestana_expediente'));
            $this->render("index_otrasexperiencias_laborables");
        } else {
            $this->set('ci', "");
            $this->set('pa', "");
            $this->set('sa', "");
            $this->set('pn', "");
            $this->set('sn', "");
        }//fin else

    }//fin funtion

    public function index_otrasexperiencias_laborables($var = null)
    {
        $this->layout = "ajax";
        $cod_presi = $this->Session->read('SScodpresi');
        $cod_entidad = $this->Session->read('SScodentidad');
        $cod_tipo_inst = $this->Session->read('SScodtipoinst');
        $cod_inst = $this->Session->read('SScodinst');
        $cod_dep = $this->Session->read('SScoddep');
        $modulo = $this->Session->read('Modulo');
        $condicion = "cod_presi = " . $cod_presi . " and cod_entidad = " . $cod_entidad . " and cod_tipo_inst = " . $cod_tipo_inst . " and cod_inst = " . $cod_inst . " and cod_dep = " . $cod_dep;
        $accion = "";

        if($var != null) {
            $accion =  $this->cnmd06_datos_otrasexperiencias_laborables->findAll('cedula=' . $var, null, "fecha_ingreso ASC");
        }//fin if

        $cond = "cedula_identidad=" . $var;

        $a = $this->cnmd06_datos_personales->findAll($cond);
        $pa = $a[0]['cnmd06_datos_personales']['primer_apellido'];
        $sa = $a[0]['cnmd06_datos_personales']['segundo_apellido'];
        $pn = $a[0]['cnmd06_datos_personales']['primer_nombre'];
        $sn = $a[0]['cnmd06_datos_personales']['segundo_nombre'];
        $this->set('ci', $var);
        $this->set('pa', $pa);
        $this->set('sa', $sa);
        $this->set('pn', $pn);
        $this->set('sn', $sn);

        $disabled = "false";
        $this->set('disabled', $disabled);
        $this->set('accion', $accion);

    }//fin funtion

    public function bienes($id = null)
    {
        $this->layout = "ajax";
        $cod_presi = $this->Session->read('SScodpresi');
        $cod_entidad = $this->Session->read('SScodentidad');
        $cod_tipo_inst = $this->Session->read('SScodtipoinst');
        $cod_inst = $this->Session->read('SScodinst');
        $cod_dep = $this->Session->read('SScoddep');
        $modulo = $this->Session->read('Modulo');
        $condicion = "cod_presi = " . $cod_presi . " and cod_entidad = " . $cod_entidad . " and cod_tipo_inst = " . $cod_tipo_inst . " and cod_inst = " . $cod_inst . " and cod_dep = " . $cod_dep;

        $listabienes =  $this->cnmd06_bienes->generateList(null, 'denominacion ASC', null, '{n}.cnmd06_bienes.cod_bien', '{n}.cnmd06_bienes.denominacion');
        $this->concatena($listabienes, 'lista');

        if($this->Session->read('cedula_pestana_expediente') == "") {
            $id = 0;
        } else {
            $id = $this->Session->read('cedula_pestana_expediente');
        }//fin else

        $this->set('cedula', "");
        $Tfilas = $this->cnmd06_datos_personales->findCount("cedula_identidad=" . $id);
        if($Tfilas != 0) {
            $a = $this->cnmd06_datos_personales->findAll("cedula_identidad=" . $id);
            $pa = $a[0]['cnmd06_datos_personales']['primer_apellido'];
            $sa = $a[0]['cnmd06_datos_personales']['segundo_apellido'];
            $pn = $a[0]['cnmd06_datos_personales']['primer_nombre'];
            $sn = $a[0]['cnmd06_datos_personales']['segundo_nombre'];
            $this->set('ci', $id);
            $this->set('pa', $pa);
            $this->set('sa', $sa);
            $this->set('pn', $pn);
            $this->set('sn', $sn);
        } else {
            $this->set('ci', "");
            $this->set('pa', "");
            $this->set('sa', "");
            $this->set('pn', "");
            $this->set('sn', "");
        }//fin else


        $dato   =  $this->cnmd06_bienes->findAll(null, null, null);
        $accion =  $this->cnmd06_datos_bienes->findAll('cedula_identidad=' . $id, null, null);

        $this->set('dato', $dato);
        $this->set('accion', $accion);

    }//fin funtion

    public function soportes($var = null)
    {

        $this->layout = "ajax";

        $cod_presi = $this->Session->read('SScodpresi');
        $cod_entidad = $this->Session->read('SScodentidad');
        $cod_tipo_inst = $this->Session->read('SScodtipoinst');
        $cod_inst = $this->Session->read('SScodinst');
        $cod_dep = $this->Session->read('SScoddep');
        $condicion = "cod_presi = " . $cod_presi . " and cod_entidad = " . $cod_entidad . " and cod_tipo_inst = " . $cod_tipo_inst . " and cod_inst = " . $cod_inst . " and cod_dep = " . $cod_dep;

        if($this->Session->read('cedula_pestana_expediente') == "") {
            $id = 0;
        } else {
            $id = $this->Session->read('cedula_pestana_expediente');
        }//fin else

        $this->set('cedula', "");
        $Tfilas = $this->cnmd06_datos_personales->findCount("cedula_identidad=" . $id);
        if($Tfilas != 0) {

            $Tfilas = $this->cnmd06_soportes->findCount("cedula=" . $id);
            if($Tfilas != 0) {
                $this->consulta_soportes($this->Session->read('cedula_pestana_expediente'));
                $this->render("consulta_soportes");
            } else {
                $cond = "cedula_identidad=" . $id;
                $a = $this->cnmd06_datos_personales->findAll($cond);
                $pa = $a[0]['cnmd06_datos_personales']['primer_apellido'];
                $sa = $a[0]['cnmd06_datos_personales']['segundo_apellido'];
                $pn = $a[0]['cnmd06_datos_personales']['primer_nombre'];
                $sn = $a[0]['cnmd06_datos_personales']['segundo_nombre'];
                $this->set('ci', $id);
                $this->set('pa', $pa);
                $this->set('sa', $sa);
                $this->set('pn', $pn);
                $this->set('sn', $sn);
            }//fin else



        } else {
            $this->set('ci', "");
            $this->set('pa', "");
            $this->set('sa', "");
            $this->set('pn', "");
            $this->set('sn', "");
        }//fin else

    }///fin function

    public function consulta_soportes($var = null)
    {

        $this->layout = "ajax";

        $cod_presi = $this->Session->read('SScodpresi');
        $cod_entidad = $this->Session->read('SScodentidad');
        $cod_tipo_inst = $this->Session->read('SScodtipoinst');
        $cod_inst = $this->Session->read('SScodinst');
        $cod_dep = $this->Session->read('SScoddep');
        $condicion = "cod_presi = " . $cod_presi . " and cod_entidad = " . $cod_entidad . " and cod_tipo_inst = " . $cod_tipo_inst . " and cod_inst = " . $cod_inst . " and cod_dep = " . $cod_dep;
        $cond = "cedula_identidad=" . $var;
        $a = $this->cnmd06_datos_personales->findAll($cond);
        $pa = $a[0]['cnmd06_datos_personales']['primer_apellido'];
        $sa = $a[0]['cnmd06_datos_personales']['segundo_apellido'];
        $pn = $a[0]['cnmd06_datos_personales']['primer_nombre'];
        $sn = $a[0]['cnmd06_datos_personales']['segundo_nombre'];
        $this->set('ci', $var);
        $this->set('pa', $pa);
        $this->set('sa', $sa);
        $this->set('pn', $pn);
        $this->set('sn', $sn);


        if($this->Session->read('cedula_pestana_expediente') == "") {
            $id = 0;
        } else {
            $id = $this->Session->read('cedula_pestana_expediente');
        }//fin else
        $Tfilas = $this->cnmd06_datos_personales->findCount("cedula_identidad=" . $id);
        if($Tfilas != 0) {
            $cond2 = "cedula=" . $id;
        } else {
            $cond2 = "";
        }//fin else

        $cont_aux = $this->cnmd06_soportes->findAll($cond2);

        $this->set('datos', $cont_aux);
    }///fin function


    public function datos_personales_consulta()
    {
        $this->layout = "ajax";

        if($this->Session->read('cedula_pestana_expediente') == "") {
            $id = 0;
        } else {
            $id = $this->Session->read('cedula_pestana_expediente');
        }//fin else
        $this->set('cedula', "");
        $Tfilas = $this->cnmd06_datos_personales->findCount("cedula_identidad=" . $id);
        if($Tfilas != 0) {
            $this->consulta_datos_personales_consulta();
            $this->render("consulta_datos_personales_consulta");
        } else {
            $this->set('ci', "");
            $this->set('pa', "");
            $this->set('sa', "");
            $this->set('pn', "");
            $this->set('sn', "");
        }//fin else
    }//fin function

    public function consulta_datos_personales_consulta($pag_num = null)
    {

        $this->layout = "ajax";

        $cod_presi              =  0;
        $cod_entidad            =  0;
        $cod_tipo_inst          =  0;
        $cod_inst               =  0;
        $cod_dep                =  0;
        $cod_tipo_nomina        =  0;
        $cod_cargo              =  0;
        $cod_ficha              =  0;


        if(!isset($_SESSION["pag_num_expediente"])) {
            $pag_num = 0;
            $this->Session->write('pag_num_expediente', $pag_num);
        } else {

            if($pag_num == null) {
                $pag_num = $this->Session->read('pag_num_expediente');
            } else {
                $this->Session->write('pag_num_expediente', $pag_num);
            }
        }//fin else

        $this->set("pag_num", $pag_num);

        if($this->Session->read('cedula_pestana_expediente') == "") {
            $id = 0;
        } else {
            $id = $this->Session->read('cedula_pestana_expediente');
        }//fin else

        $datos = $this->cnmd06_datos_personales->execute("

			SELECT


					  a.cedula_identidad,
					  a.primer_apellido,
					  a.segundo_apellido,
					  a.primer_nombre,
					  a.segundo_nombre,


					  b.cod_presi,
					  b.cod_entidad,
					  b.cod_tipo_inst,
					  b.cod_inst,
					  b.cod_dep,
					  (SELECT x.denominacion  FROM arrd05 x WHERE
							  x.cod_presi           =     b.cod_presi       and
							  x.cod_entidad         =     b.cod_entidad     and
							  x.cod_tipo_inst       =     b.cod_tipo_inst   and
							  x.cod_inst            =     b.cod_inst        and
							  x.cod_dep             =     b.cod_dep
					  ) as denominacion_dependencia,
					  b.cod_tipo_nomina,
					  (SELECT x.denominacion  FROM cnmd01 x WHERE
							  x.cod_presi           =     b.cod_presi       and
							  x.cod_entidad         =     b.cod_entidad     and
							  x.cod_tipo_inst       =     b.cod_tipo_inst   and
							  x.cod_inst            =     b.cod_inst        and
							  x.cod_dep             =     b.cod_dep         and
							  x.cod_tipo_nomina     =     b.cod_tipo_nomina
					  ) as denominacion_nomina,
					  b.cod_cargo,
					  b.cod_ficha,
					  b.fecha_ingreso,
					  b.forma_pago,
					  b.cod_entidad_bancaria,
					  b.cod_sucursal,
					  b.cuenta_bancaria,
					  b.condicion_actividad,
					  b.funciones_realizar,
					  b.responsabilidad_administrativa,
					  b.horas_laborar,
					  b.porcentaje_jub_pension,
					  b.fecha_terminacion_contrato,
					  b.fecha_retiro,
					  b.motivo_retiro,
					  b.paso,
					  b.tipo_contrato,
					  b.situacion,
					  b.nivel,
					  b.categoria,


					  c.cod_puesto,
					  (select devolver_denominacion_puesto(
			               (select xy.clasificacion_personal from cnmd01 xy where
			                  xy.cod_presi           =     c.cod_presi       and
							  xy.cod_entidad         =     c.cod_entidad     and
							  xy.cod_tipo_inst       =     c.cod_tipo_inst   and
							  xy.cod_inst            =     c.cod_inst        and
							  xy.cod_dep             =     c.cod_dep         and
							  xy.cod_tipo_nomina     =     c.cod_tipo_nomina
			               ), c.cod_puesto )
			          ) as demonimacion_puesto,
			          (select devolver_grado_puesto(
			               (select xy.clasificacion_personal from cnmd01 xy where
			                  xy.cod_presi           =     c.cod_presi       and
							  xy.cod_entidad         =     c.cod_entidad     and
							  xy.cod_tipo_inst       =     c.cod_tipo_inst   and
							  xy.cod_inst            =     c.cod_inst        and
							  xy.cod_dep             =     c.cod_dep         and
							  xy.cod_tipo_nomina     =     c.cod_tipo_nomina
			               ), c.cod_puesto )
			          ) as grado_puesto,
					  c.sueldo_basico,
					  c.compensaciones,
					  c.primas,
					  c.bonos,
					  (c.compensaciones + c.primas + c.bonos) as otras_remuneraciones,
					  c.cod_dir_superior,
					  c.cod_coordinacion,
					  c.cod_secretaria,
					  c.cod_direccion,
					  c.cod_division,
					  c.cod_departamento,
					  c.cod_oficina,
						  (SELECT xa.denominacion FROM cugd02_direccionsuperior xa where xa.cod_tipo_institucion=c.cod_tipo_inst  and xa.cod_institucion=c.cod_inst and xa.cod_dependencia=c.cod_dep and xa.cod_dir_superior=c.cod_dir_superior                                                                                                                                                                                                                                        GROUP BY xa.denominacion) as  deno_cod_dir_superior,
						  (SELECT xb.denominacion FROM cugd02_coordinacion xb      where xb.cod_tipo_institucion=c.cod_tipo_inst  and xb.cod_institucion=c.cod_inst and xb.cod_dependencia=c.cod_dep and xb.cod_dir_superior=c.cod_dir_superior and xb.cod_coordinacion=c.cod_coordinacion                                                                                                                                                                                             GROUP BY xb.denominacion) as  deno_cod_coordinacion,
						  (SELECT xc.denominacion FROM cugd02_secretaria xc        where xc.cod_tipo_institucion=c.cod_tipo_inst  and xc.cod_institucion=c.cod_inst and xc.cod_dependencia=c.cod_dep and xc.cod_dir_superior=c.cod_dir_superior and xc.cod_coordinacion=c.cod_coordinacion and xc.cod_secretaria=c.cod_secretaria                                                                                                                                                      GROUP BY xc.denominacion) as  deno_cod_secretaria,
						  (SELECT xd.denominacion FROM cugd02_direccion xd         where xd.cod_tipo_institucion=c.cod_tipo_inst  and xd.cod_institucion=c.cod_inst and xd.cod_dependencia=c.cod_dep and xd.cod_dir_superior=c.cod_dir_superior and xd.cod_coordinacion=c.cod_coordinacion and xd.cod_secretaria=c.cod_secretaria and xd.cod_direccion=c.cod_direccion                                                                                                                 GROUP BY xd.denominacion) as  deno_cod_direccion,
						  (SELECT xe.denominacion FROM cugd02_division xe          where xe.cod_tipo_institucion=c.cod_tipo_inst  and xe.cod_institucion=c.cod_inst and xe.cod_dependencia=c.cod_dep and xe.cod_dir_superior=c.cod_dir_superior and xe.cod_coordinacion=c.cod_coordinacion and xe.cod_secretaria=c.cod_secretaria and xe.cod_direccion=c.cod_direccion  and xe.cod_division=c.cod_division                                                                             GROUP BY xe.denominacion) as  deno_cod_division,
						  (SELECT xf.denominacion FROM cugd02_departamento xf      where xf.cod_tipo_institucion=c.cod_tipo_inst  and xf.cod_institucion=c.cod_inst and xf.cod_dependencia=c.cod_dep and xf.cod_dir_superior=c.cod_dir_superior and xf.cod_coordinacion=c.cod_coordinacion and xf.cod_secretaria=c.cod_secretaria and xf.cod_direccion=c.cod_direccion  and xf.cod_division=c.cod_division and xf.cod_departamento=c.cod_departamento                                  GROUP BY xf.denominacion) as  deno_cod_departamento,
						  (SELECT xg.denominacion FROM cugd02_oficina xg           where xg.cod_tipo_institucion=c.cod_tipo_inst  and xg.cod_institucion=c.cod_inst and xg.cod_dependencia=c.cod_dep and xg.cod_dir_superior=c.cod_dir_superior and xg.cod_coordinacion=c.cod_coordinacion and xg.cod_secretaria=c.cod_secretaria and xg.cod_direccion=c.cod_direccion  and xg.cod_division=c.cod_division and xg.cod_departamento=c.cod_departamento and xg.cod_oficina=c.cod_oficina GROUP BY xg.denominacion) as  deno_cod_oficina,
					  c.cod_estado,
					  c.cod_municipio,
					  c.cod_parroquia,
					  c.cod_centro,
				          (SELECT xya.denominacion FROM cugd01_estados          xya where xya.cod_republica=c.cod_presi and xya.cod_estado=c.cod_estado                                                                                                                  GROUP BY xya.denominacion) as  deno_cod_estado,
						  (SELECT xyb.denominacion FROM cugd01_municipios       xyb where xyb.cod_republica=c.cod_presi and xyb.cod_estado=c.cod_estado  and xyb.cod_municipio=c.cod_municipio                                                                           GROUP BY xyb.denominacion) as  deno_cod_municipio,
						  (SELECT xyc.denominacion FROM cugd01_parroquias       xyc where xyc.cod_republica=c.cod_presi and xyc.cod_estado=c.cod_estado  and xyc.cod_municipio=c.cod_municipio and xyc.cod_parroquia = c.cod_parroquia                                   GROUP BY xyc.denominacion) as  deno_cod_parroquia,
						  (SELECT xyd.denominacion FROM cugd01_centros_poblados xyd where xyd.cod_republica=c.cod_presi and xyd.cod_estado=c.cod_estado  and xyd.cod_municipio=c.cod_municipio and xyd.cod_parroquia = c.cod_parroquia and xyd.cod_centro = c.cod_centro GROUP BY xyd.denominacion) as  deno_cod_centro,
						  (SELECT xyb.conocido     FROM cugd01_municipios       xyb where xyb.cod_republica=c.cod_presi and xyb.cod_estado=c.cod_estado  and xyb.cod_municipio=c.cod_municipio                                                                           GROUP BY xyb.conocido)     as  deno_ciudad,
					  c.condicion_actividad,
					  c.ano,
					  c.cod_sector,
					  c.cod_programa,
					  c.cod_sub_prog,
					  c.cod_proyecto,
					  c.cod_activ_obra,
					  c.cod_partida,
					  c.cod_generica,
					  c.cod_especifica,
					  c.cod_sub_espec,
					  c.cod_auxiliar,
					  c.cod_nivel_i,
					  c.cod_nivel_ii,
					  c.cod_ficha



			FROM


					 cnmd06_datos_personales         a,
					 cnmd06_fichas                   b,
					 cnmd05                          c


			WHERE
                    a.cedula_identidad = '" . $id . "'          and
                    b.cedula_identidad = a.cedula_identidad and
                    b.condicion_actividad  = 1              and
                    b.cod_presi        = '" . $this->verifica_SS(1) . "'         and
					b.cod_entidad      = '" . $this->verifica_SS(2) . "'         and
					b.cod_tipo_inst    = '" . $this->verifica_SS(3) . "'         and
					b.cod_inst         = '" . $this->verifica_SS(4) . "'         and
                    c.cod_presi        = b.cod_presi        and
					c.cod_entidad      = b.cod_entidad      and
					c.cod_tipo_inst    = b.cod_tipo_inst    and
					c.cod_inst         = b.cod_inst         and
					c.cod_dep          = b.cod_dep          and
					c.cod_tipo_nomina  = b.cod_tipo_nomina  and
					c.cod_cargo        = b.cod_cargo
	;");

        $totalPages_Recordset1 =  count($datos);
        $totalPages_Recordset1--;

        $mensaje = "Esta persona tiene más de un cargo";

        if($totalPages_Recordset1 > 0 && $pag_num == 0) {

            echo "<script>";
            echo "if (confirm('" . $mensaje . "')) {";

            echo " }";
            echo "</script>";

        }

        $datos = $this->cnmd06_datos_personales->execute("

			SELECT


					  a.cedula_identidad,
					  a.primer_apellido,
					  a.segundo_apellido,
					  a.primer_nombre,
					  a.segundo_nombre,


					  b.cod_presi,
					  b.cod_entidad,
					  b.cod_tipo_inst,
					  b.cod_inst,
					  b.cod_dep,
					  (SELECT x.denominacion  FROM arrd05 x WHERE
							  x.cod_presi           =     b.cod_presi       and
							  x.cod_entidad         =     b.cod_entidad     and
							  x.cod_tipo_inst       =     b.cod_tipo_inst   and
							  x.cod_inst            =     b.cod_inst        and
							  x.cod_dep             =     b.cod_dep
					  ) as denominacion_dependencia,
					  b.cod_tipo_nomina,
					  (SELECT x.denominacion  FROM cnmd01 x WHERE
							  x.cod_presi           =     b.cod_presi       and
							  x.cod_entidad         =     b.cod_entidad     and
							  x.cod_tipo_inst       =     b.cod_tipo_inst   and
							  x.cod_inst            =     b.cod_inst        and
							  x.cod_dep             =     b.cod_dep         and
							  x.cod_tipo_nomina     =     b.cod_tipo_nomina
					  ) as denominacion_nomina,
					  b.cod_cargo,
					  b.cod_ficha,
					  b.fecha_ingreso,
					  b.forma_pago,
					  b.cod_entidad_bancaria,
					  b.cod_sucursal,
					  b.cuenta_bancaria,
					  b.condicion_actividad,
					  b.funciones_realizar,
					  b.responsabilidad_administrativa,
					  b.horas_laborar,
					  b.porcentaje_jub_pension,
					  b.fecha_terminacion_contrato,
					  b.fecha_retiro,
					  b.motivo_retiro,
					  b.paso,
					  b.tipo_contrato,
					  b.situacion,
					  b.nivel,
					  b.categoria,


					  c.cod_puesto,
					  (select devolver_denominacion_puesto(
			               (select xy.clasificacion_personal from cnmd01 xy where
			                  xy.cod_presi           =     c.cod_presi       and
							  xy.cod_entidad         =     c.cod_entidad     and
							  xy.cod_tipo_inst       =     c.cod_tipo_inst   and
							  xy.cod_inst            =     c.cod_inst        and
							  xy.cod_dep             =     c.cod_dep         and
							  xy.cod_tipo_nomina     =     c.cod_tipo_nomina
			               ), c.cod_puesto )
			          ) as demonimacion_puesto,
			          (select devolver_grado_puesto(
			               (select xy.clasificacion_personal from cnmd01 xy where
			                  xy.cod_presi           =     c.cod_presi       and
							  xy.cod_entidad         =     c.cod_entidad     and
							  xy.cod_tipo_inst       =     c.cod_tipo_inst   and
							  xy.cod_inst            =     c.cod_inst        and
							  xy.cod_dep             =     c.cod_dep         and
							  xy.cod_tipo_nomina     =     c.cod_tipo_nomina
			               ), c.cod_puesto )
			          ) as grado_puesto,
					  c.sueldo_basico,
					  c.compensaciones,
					  c.primas,
					  c.bonos,
					  (c.compensaciones + c.primas + c.bonos) as otras_remuneraciones,
					  c.cod_dir_superior,
					  c.cod_coordinacion,
					  c.cod_secretaria,
					  c.cod_direccion,
					  c.cod_division,
					  c.cod_departamento,
					  c.cod_oficina,
						  (SELECT xa.denominacion FROM cugd02_direccionsuperior xa where xa.cod_tipo_institucion=c.cod_tipo_inst  and xa.cod_institucion=c.cod_inst and xa.cod_dependencia=c.cod_dep and xa.cod_dir_superior=c.cod_dir_superior                                                                                                                                                                                                                                        GROUP BY xa.denominacion) as  deno_cod_dir_superior,
						  (SELECT xb.denominacion FROM cugd02_coordinacion xb      where xb.cod_tipo_institucion=c.cod_tipo_inst  and xb.cod_institucion=c.cod_inst and xb.cod_dependencia=c.cod_dep and xb.cod_dir_superior=c.cod_dir_superior and xb.cod_coordinacion=c.cod_coordinacion                                                                                                                                                                                             GROUP BY xb.denominacion) as  deno_cod_coordinacion,
						  (SELECT xc.denominacion FROM cugd02_secretaria xc        where xc.cod_tipo_institucion=c.cod_tipo_inst  and xc.cod_institucion=c.cod_inst and xc.cod_dependencia=c.cod_dep and xc.cod_dir_superior=c.cod_dir_superior and xc.cod_coordinacion=c.cod_coordinacion and xc.cod_secretaria=c.cod_secretaria                                                                                                                                                      GROUP BY xc.denominacion) as  deno_cod_secretaria,
						  (SELECT xd.denominacion FROM cugd02_direccion xd         where xd.cod_tipo_institucion=c.cod_tipo_inst  and xd.cod_institucion=c.cod_inst and xd.cod_dependencia=c.cod_dep and xd.cod_dir_superior=c.cod_dir_superior and xd.cod_coordinacion=c.cod_coordinacion and xd.cod_secretaria=c.cod_secretaria and xd.cod_direccion=c.cod_direccion                                                                                                                 GROUP BY xd.denominacion) as  deno_cod_direccion,
						  (SELECT xe.denominacion FROM cugd02_division xe          where xe.cod_tipo_institucion=c.cod_tipo_inst  and xe.cod_institucion=c.cod_inst and xe.cod_dependencia=c.cod_dep and xe.cod_dir_superior=c.cod_dir_superior and xe.cod_coordinacion=c.cod_coordinacion and xe.cod_secretaria=c.cod_secretaria and xe.cod_direccion=c.cod_direccion  and xe.cod_division=c.cod_division                                                                             GROUP BY xe.denominacion) as  deno_cod_division,
						  (SELECT xf.denominacion FROM cugd02_departamento xf      where xf.cod_tipo_institucion=c.cod_tipo_inst  and xf.cod_institucion=c.cod_inst and xf.cod_dependencia=c.cod_dep and xf.cod_dir_superior=c.cod_dir_superior and xf.cod_coordinacion=c.cod_coordinacion and xf.cod_secretaria=c.cod_secretaria and xf.cod_direccion=c.cod_direccion  and xf.cod_division=c.cod_division and xf.cod_departamento=c.cod_departamento                                  GROUP BY xf.denominacion) as  deno_cod_departamento,
						  (SELECT xg.denominacion FROM cugd02_oficina xg           where xg.cod_tipo_institucion=c.cod_tipo_inst  and xg.cod_institucion=c.cod_inst and xg.cod_dependencia=c.cod_dep and xg.cod_dir_superior=c.cod_dir_superior and xg.cod_coordinacion=c.cod_coordinacion and xg.cod_secretaria=c.cod_secretaria and xg.cod_direccion=c.cod_direccion  and xg.cod_division=c.cod_division and xg.cod_departamento=c.cod_departamento and xg.cod_oficina=c.cod_oficina GROUP BY xg.denominacion) as  deno_cod_oficina,
					  c.cod_estado,
					  c.cod_municipio,
					  c.cod_parroquia,
					  c.cod_centro,
				          (SELECT xya.denominacion FROM cugd01_estados          xya where xya.cod_republica=c.cod_presi and xya.cod_estado=c.cod_estado                                                                                                                  GROUP BY xya.denominacion) as  deno_cod_estado,
						  (SELECT xyb.denominacion FROM cugd01_municipios       xyb where xyb.cod_republica=c.cod_presi and xyb.cod_estado=c.cod_estado  and xyb.cod_municipio=c.cod_municipio                                                                           GROUP BY xyb.denominacion) as  deno_cod_municipio,
						  (SELECT xyc.denominacion FROM cugd01_parroquias       xyc where xyc.cod_republica=c.cod_presi and xyc.cod_estado=c.cod_estado  and xyc.cod_municipio=c.cod_municipio and xyc.cod_parroquia = c.cod_parroquia                                   GROUP BY xyc.denominacion) as  deno_cod_parroquia,
						  (SELECT xyd.denominacion FROM cugd01_centros_poblados xyd where xyd.cod_republica=c.cod_presi and xyd.cod_estado=c.cod_estado  and xyd.cod_municipio=c.cod_municipio and xyd.cod_parroquia = c.cod_parroquia and xyd.cod_centro = c.cod_centro GROUP BY xyd.denominacion) as  deno_cod_centro,
						  (SELECT xyb.conocido     FROM cugd01_municipios       xyb where xyb.cod_republica=c.cod_presi and xyb.cod_estado=c.cod_estado  and xyb.cod_municipio=c.cod_municipio                                                                           GROUP BY xyb.conocido)     as  deno_ciudad,
					  c.condicion_actividad,
					  c.ano,
					  c.cod_sector,
					  c.cod_programa,
					  c.cod_sub_prog,
					  c.cod_proyecto,
					  c.cod_activ_obra,
					  c.cod_partida,
					  c.cod_generica,
					  c.cod_especifica,
					  c.cod_sub_espec,
					  c.cod_auxiliar,
					  c.cod_nivel_i,
					  c.cod_nivel_ii,
					  c.cod_ficha



			FROM


					 cnmd06_datos_personales         a,
					 cnmd06_fichas                   b,
					 cnmd05                          c


			WHERE
                    a.cedula_identidad = '" . $id . "'          and
                    b.cedula_identidad = a.cedula_identidad and
                    b.condicion_actividad  = 1              and
                    b.cod_presi        = '" . $this->verifica_SS(1) . "'         and
					b.cod_entidad      = '" . $this->verifica_SS(2) . "'         and
					b.cod_tipo_inst    = '" . $this->verifica_SS(3) . "'         and
					b.cod_inst         = '" . $this->verifica_SS(4) . "'         and
                    c.cod_presi        = b.cod_presi        and
					c.cod_entidad      = b.cod_entidad      and
					c.cod_tipo_inst    = b.cod_tipo_inst    and
					c.cod_inst         = b.cod_inst         and
					c.cod_dep          = b.cod_dep          and
					c.cod_tipo_nomina  = b.cod_tipo_nomina  and
					c.cod_cargo        = b.cod_cargo


		    LIMIT 1 OFFSET " . $pag_num . " * 1
	;");

        if(isset($datos[0][0]["cod_presi"])) {
            $cod_presi              =  $datos[0][0]["cod_presi"];
            $cod_entidad            =  $datos[0][0]["cod_entidad"];
            $cod_tipo_inst          =  $datos[0][0]["cod_tipo_inst"];
            $cod_inst               =  $datos[0][0]["cod_inst"];
            $cod_dep                =  $datos[0][0]["cod_dep"];
            $cod_tipo_nomina        =  $datos[0][0]["cod_tipo_nomina"];
            $cod_cargo              =  $datos[0][0]["cod_cargo"];
            $cod_ficha              =  $datos[0][0]["cod_ficha"];
        } else {

            $cod_tipo_nomina        =  0;
            $cod_cargo              =  0;
            $cod_ficha              =  0;

        }//fin else

        $datos_sueldo =   $this->cnmd05->findAll("cod_presi='" . $cod_presi . "' and cod_entidad='" . $cod_entidad . "' and cod_tipo_inst='" . $cod_tipo_inst . "' and cod_inst=" . $cod_inst . " and  cod_tipo_nomina='" . $cod_tipo_nomina . "' and cod_cargo=" . $cod_cargo, null, null);
        $this->set('dato_sueldo', $datos_sueldo);

        $this->Session->write('cod_dep_expediente', $cod_dep);
        $this->Session->write('cod_tipo_nomina_expediente', $cod_tipo_nomina);
        $this->Session->write('cod_cargo_expediente', $cod_cargo);
        $this->Session->write('cod_ficha_expediente', $cod_ficha);


        $datos2 = $this->cnmd06_datos_personales->execute("

			SELECT

                  a.cod_presi,
				  a.cod_entidad,
				  a.cod_tipo_inst,
				  a.cod_inst,
				  a.cod_dep,
				  a.cod_tipo_nomina,
				  a.cod_cargo,
				  a.cod_ficha,
				  a.cod_tipo_transaccion,
				  a.cod_transaccion,
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
				  (Select devolver_denominacion_transaccion(a.cod_presi , a.cod_entidad , a.cod_tipo_inst , a.cod_inst , a.cod_dep , a.cod_tipo_nomina , a.cod_tipo_transaccion , a.cod_transaccion )) as denominacion_transaccion,
				  (SELECT x.denominacion  FROM cnmd03_transacciones x WHERE
							  x.cod_tipo_transaccion    =     a.cod_tipo_transaccion       and
							  x.cod_transaccion         =     a.cod_transaccion
					  ) as denominacion_transaccion_aux,
				   (SELECT x.uso_transaccion  FROM cnmd03_transacciones x WHERE
							  x.cod_tipo_transaccion    =     a.cod_tipo_transaccion       and
							  x.cod_transaccion         =     a.cod_transaccion
					  ) as uso_transaccion


			FROM

					 cnmd07_transacciones_actuales         a

			WHERE

  	                a.cod_presi        = '" . $cod_presi . "'        and
					a.cod_entidad      = '" . $cod_entidad . "'      and
					a.cod_tipo_inst    = '" . $cod_tipo_inst . "'    and
					a.cod_inst         = '" . $cod_inst . "'         and
					a.cod_dep          = '" . $cod_dep . "'          and
					a.cod_tipo_nomina  = '" . $cod_tipo_nomina . "'  and
					a.cod_cargo        = '" . $cod_cargo . "'        and
					a.cod_ficha        = '" . $cod_ficha . "'
	;");

        $datos3 = $this->cnmd06_datos_personales->execute("

			SELECT

                  a.cod_presi,
				  a.cod_entidad,
				  a.cod_tipo_inst,
				  a.cod_inst,
				  a.cod_dep,
				  a.cod_tipo_nomina,
				  a.cod_cargo,
				  a.cod_ficha,
				  a.cedula_identidad,
				  	 (select  x.primer_apellido  from cnmd06_datos_personales x where x.cedula_identidad = a.cedula_identidad ) as primer_apellido,
					 (select  x.segundo_apellido from cnmd06_datos_personales x where x.cedula_identidad = a.cedula_identidad ) as segundo_apellido,
					 (select  x.primer_nombre    from cnmd06_datos_personales x where x.cedula_identidad = a.cedula_identidad ) as primer_nombre,
					 (select  x.segundo_nombre   from cnmd06_datos_personales x where x.cedula_identidad = a.cedula_identidad ) as segundo_nombre,
				  a.fecha_ingreso,
				  a.forma_pago,
				  a.cod_entidad_bancaria,
				  a.cod_sucursal,
				  a.cuenta_bancaria,
				  a.condicion_actividad,
				  a.funciones_realizar,
				  a.responsabilidad_administrativa,
				  a.horas_laborar,
				  a.porcentaje_jub_pension,
				  a.fecha_terminacion_contrato,
				  a.fecha_retiro,
				  a.motivo_retiro,
				  a.paso,
				  a.tipo_contrato,
				  a.situacion,
				  a.nivel,
				  a.categoria



			FROM


					 cnmd06_fichas      a


			WHERE

  	                a.cod_presi            = '" . $cod_presi . "'        and
					a.cod_entidad          = '" . $cod_entidad . "'      and
					a.cod_tipo_inst        = '" . $cod_tipo_inst . "'    and
					a.cod_inst             = '" . $cod_inst . "'         and
					a.cod_dep              = '" . $cod_dep . "'          and
					a.cod_tipo_nomina      = '" . $cod_tipo_nomina . "'  and
					a.cod_cargo            = '" . $cod_cargo . "'        and
					a.condicion_actividad  = 6
	;");

        $this->set("totalPages_Recordset1", $totalPages_Recordset1);
        $this->set("datos", $datos);
        $this->set("datos2", $datos2);
        $this->set("datos3", $datos3);

    }//fin function

    public function permisos()
    {
        $this->layout = "ajax";
        $cod_presi = $this->Session->read('SScodpresi');
        $cod_entidad = $this->Session->read('SScodentidad');
        $cod_tipo_inst = $this->Session->read('SScodtipoinst');
        $cod_inst = $this->Session->read('SScodinst');
        $cod_dep = $this->Session->read('SScoddep');
        $modulo = $this->Session->read('Modulo');
        $condicion = "cod_presi = " . $cod_presi . " and cod_entidad = " . $cod_entidad . " and cod_tipo_inst = " . $cod_tipo_inst . " and cod_inst = " . $cod_inst . " and cod_dep = " . $cod_dep;


        $this->set('permisos', $this->cnmd06_permisos->findAll(null, null, "denominacion ASC"));
        $this->concatena($this->cnmd06_permisos->generateList(null, "denominacion ASC", null, '{n}.cnmd06_permisos.cod_permiso', '{n}.cnmd06_permisos.denominacion'), "lista_deno");


        if($this->Session->read('cedula_pestana_expediente') == "") {
            $id = 0;
        } else {
            $id = $this->Session->read('cedula_pestana_expediente');
            // $var_datos_ficha = $this->cnmd06_fichas->findAll("cod_presi = '".$cod_presi."' and cod_entidad = '".$cod_entidad."'and cod_tipo_inst = '".$cod_tipo_inst."' and cod_inst = '".$cod_inst."' and cod_dep = '".$cod_dep."' and cedula_identidad=".$id);
            // $cond_activ = $var_datos_ficha[0]['cnmd06_fichas']['condicion_actividad']!=null ? $var_datos_ficha[0]['cnmd06_fichas']['condicion_actividad'] : '';
        }//fin else
        $this->set('cedula', "");
        $Tfilas = $this->cnmd06_datos_personales->findCount("cedula_identidad=" . $id);
        if($Tfilas != 0) {
            $this->index_permisos($this->Session->read('cedula_pestana_expediente'));
            $this->render("index_permisos");
        } else {
            // $this->set('cond_activ', isset($cond_activ)?$cond_activ:'');
            $this->set('cond_activ', '');
            $this->set('ci', "");
            $this->set('pa', "");
            $this->set('sa', "");
            $this->set('pn', "");
            $this->set('sn', "");
        }//fin else
    }//fin funtion

    public function index_permisos($var = null)
    {
        $this->layout = "ajax";
        $cod_presi = $this->Session->read('SScodpresi');
        $cod_entidad = $this->Session->read('SScodentidad');
        $cod_tipo_inst = $this->Session->read('SScodtipoinst');
        $cod_inst = $this->Session->read('SScodinst');
        $cod_dep = $this->Session->read('SScoddep');
        $modulo = $this->Session->read('Modulo');
        $condicion = "cod_presi = " . $cod_presi . " and cod_entidad = " . $cod_entidad . " and cod_tipo_inst = " . $cod_tipo_inst . " and cod_inst = " . $cod_inst . " and cod_dep = " . $cod_dep;
        $accion = "";

        if($var != null) {
            $cod_dep_expediente     =  $this->Session->read('cod_dep_expediente');
            $cod_tipo_nomina        =  $this->Session->read('cod_tipo_nomina_expediente');
            $cod_cargo              =  $this->Session->read('cod_cargo_expediente');
            $cod_ficha              =  $this->Session->read('cod_ficha_expediente');

            $var_datos_ficha = $this->cnmd06_fichas->findAll("cod_presi = '" . $cod_presi . "' and cod_entidad = '" . $cod_entidad . "'and cod_tipo_inst = '" . $cod_tipo_inst . "' and cod_inst = '" . $cod_inst . "' and cod_dep = '" . $cod_dep . "' and cedula_identidad=" . $var . " and cod_tipo_nomina = '" . $cod_tipo_nomina . "' and cod_cargo = '" . $cod_cargo . "' and cod_ficha = " . $cod_ficha);
            //$cod_tipo_nomina = $var_datos_ficha[0]['cnmd06_fichas']['cod_tipo_nomina'];
            //$cod_cargo       = $var_datos_ficha[0]['cnmd06_fichas']['cod_cargo'];
            //$cod_ficha       = $var_datos_ficha[0]['cnmd06_fichas']['cod_ficha'];

            $cond_activ = $var_datos_ficha[0]['cnmd06_fichas']['condicion_actividad'] != null ? $var_datos_ficha[0]['cnmd06_fichas']['condicion_actividad'] : '';

            $accion =  $this->cnmd06_datos_permisos->findAll("cod_presi = '" . $cod_presi . "' and cod_entidad = '" . $cod_entidad . "' and cod_tipo_inst = '" . $cod_tipo_inst . "' and cod_inst = '" . $cod_inst . "' and cod_dep = '" . $cod_dep_expediente . "' and cod_tipo_nomina = '" . $cod_tipo_nomina . "' and cod_cargo = '" . $cod_cargo . "' and cod_ficha = '" . $cod_ficha . "' and cedula=" . $var, null, null);
        }//fin if

        $var1 = "";
        $var2 = "";
        $var3 = "";
        $var4 = "";
        $cnmd06_datos_personales_aux = $this->cnmd06_datos_personales->findAll("cedula_identidad = '" . $var . "'");
        foreach($cnmd06_datos_personales_aux as $ve) {
            $var1 = $ve["cnmd06_datos_personales"]["primer_apellido"];
            $var2 = $ve["cnmd06_datos_personales"]["segundo_apellido"];
            $var3 = $ve["cnmd06_datos_personales"]["primer_nombre"];
            $var4 = $ve["cnmd06_datos_personales"]["segundo_nombre"];
        }//fin foreach


        $a = $this->cnmd06_datos_personales->findAll("cedula_identidad = '" . $var . "'");
        $pa = $a[0]['cnmd06_datos_personales']['primer_apellido'];
        $sa = $a[0]['cnmd06_datos_personales']['segundo_apellido'];
        $pn = $a[0]['cnmd06_datos_personales']['primer_nombre'];
        $sn = $a[0]['cnmd06_datos_personales']['segundo_nombre'];
        $this->set('ci', $var);
        $this->set('pa', $pa);
        $this->set('sa', $sa);
        $this->set('pn', $pn);
        $this->set('sn', $sn);

        if($var1 != "") {
            $disabled = "false";
        } else {
            $accion = "";
            $this->set('errorMessage', "NO ESTA REGISTRADO EN PERSONAL");
            $disabled = "true";
        }
        $this->set('cond_activ', isset($cond_activ) ? $cond_activ : '');
        $this->set('disabled', $disabled);
        $this->set('accion', $accion);
        $this->set('cedula', $var);

        $this->set('permisos', $this->cnmd06_permisos->findAll(null, null, "denominacion ASC"));
        $this->concatena($this->cnmd06_permisos->generateList(null, "denominacion ASC", null, '{n}.cnmd06_permisos.cod_permiso', '{n}.cnmd06_permisos.denominacion'), "lista_deno");
    }//fin funtion


    public function amonestaciones()
    {
        $this->layout = "ajax";
        $cod_presi = $this->Session->read('SScodpresi');
        $cod_entidad = $this->Session->read('SScodentidad');
        $cod_tipo_inst = $this->Session->read('SScodtipoinst');
        $cod_inst = $this->Session->read('SScodinst');
        $cod_dep = $this->Session->read('SScoddep');
        $modulo = $this->Session->read('Modulo');
        $condicion = "cod_presi = " . $cod_presi . " and cod_entidad = " . $cod_entidad . " and cod_tipo_inst = " . $cod_tipo_inst . " and cod_inst = " . $cod_inst . " and cod_dep = " . $cod_dep;

        $this->data = null;

        $this->set('amonestacion', $this->cnmd06_amonestaciones->findAll(null, null, "denominacion ASC"));
        $this->concatena($this->cnmd06_amonestaciones->generateList(null, "denominacion ASC", null, '{n}.cnmd06_amonestaciones.cod_amonestacion', '{n}.cnmd06_amonestaciones.denominacion'), "lista_deno");


        if($this->Session->read('cedula_pestana_expediente') == "") {
            $id = 0;
        } else {
            $id = $this->Session->read('cedula_pestana_expediente');
        }//fin else
        $this->set('cedula', "");
        $Tfilas = $this->cnmd06_datos_personales->findCount("cedula_identidad=" . $id);
        if($Tfilas != 0) {
            $this->index_amonestaciones($this->Session->read('cedula_pestana_expediente'));
            $this->render("index_amonestaciones");
        } else {
            $this->set('ci', "");
            $this->set('pa', "");
            $this->set('sa', "");
            $this->set('pn', "");
            $this->set('sn', "");
        }//fin else
    }//fin funtion

    public function index_amonestaciones($var = null)
    {
        $this->layout = "ajax";
        $cod_presi = $this->Session->read('SScodpresi');
        $cod_entidad = $this->Session->read('SScodentidad');
        $cod_tipo_inst = $this->Session->read('SScodtipoinst');
        $cod_inst = $this->Session->read('SScodinst');
        $cod_dep = $this->Session->read('SScoddep');
        $modulo = $this->Session->read('Modulo');
        $condicion = "cod_presi = " . $cod_presi . " and cod_entidad = " . $cod_entidad . " and cod_tipo_inst = " . $cod_tipo_inst . " and cod_inst = " . $cod_inst . " and cod_dep = " . $cod_dep;
        $accion = "";


        $var1 = "";
        $var2 = "";
        $var3 = "";
        $var4 = "";
        $cnmd06_datos_personales_aux = $this->cnmd06_datos_personales->findAll("cedula_identidad = '" . $var . "'");
        foreach($cnmd06_datos_personales_aux as $ve) {
            $var1 = $ve["cnmd06_datos_personales"]["primer_apellido"];
            $var2 = $ve["cnmd06_datos_personales"]["segundo_apellido"];
            $var3 = $ve["cnmd06_datos_personales"]["primer_nombre"];
            $var4 = $ve["cnmd06_datos_personales"]["segundo_nombre"];
        }//fin foreach

        $a = $this->cnmd06_datos_personales->findAll("cedula_identidad = '" . $var . "'");
        $pa = $a[0]['cnmd06_datos_personales']['primer_apellido'];
        $sa = $a[0]['cnmd06_datos_personales']['segundo_apellido'];
        $pn = $a[0]['cnmd06_datos_personales']['primer_nombre'];
        $sn = $a[0]['cnmd06_datos_personales']['segundo_nombre'];
        $this->set('ci', $var);
        $this->set('pa', $pa);
        $this->set('sa', $sa);
        $this->set('pn', $pn);
        $this->set('sn', $sn);

        if($var1 != "") {

            echo"<script>document.getElementById('guardar').disabled = false; </script>";
        } else {
            $this->set('errorMessage', "NO ESTA REGISTRADO EN PERSONAL");
            $disabled = "true";
            echo"<script>  document.getElementById('guardar').disabled = true; </script>";


        }//fin else

        $cod_dep_expediente     =  $this->Session->read('cod_dep_expediente');
        $cod_tipo_nomina        =  $this->Session->read('cod_tipo_nomina_expediente');
        $cod_cargo              =  $this->Session->read('cod_cargo_expediente');
        $cod_ficha              =  $this->Session->read('cod_ficha_expediente');

        $var_datos_ficha = $this->cnmd06_fichas->findAll("cod_presi = '" . $cod_presi . "' and cod_entidad = '" . $cod_entidad . "'and cod_tipo_inst = '" . $cod_tipo_inst . "' and cod_inst = '" . $cod_inst . "' and cod_dep = '" . $cod_dep . "' and cedula_identidad=" . $var . " and cod_tipo_nomina = '" . $cod_tipo_nomina . "' and cod_cargo = '" . $cod_cargo . "' and cod_ficha = " . $cod_ficha);
        $cond_activ = $var_datos_ficha[0]['cnmd06_fichas']['condicion_actividad'] != null ? $var_datos_ficha[0]['cnmd06_fichas']['condicion_actividad'] : '';

        $this->set('cond_activ', isset($cond_activ) ? $cond_activ : '');
        $this->set('amonestacion', $this->cnmd06_amonestaciones->findAll(null, null, "denominacion ASC"));
        $this->set('accion', $this->cnmd06_datos_amonestaciones->findAll("cod_presi = '" . $cod_presi . "' and cod_entidad = '" . $cod_entidad . "'and cod_tipo_inst = '" . $cod_tipo_inst . "' and cod_inst = '" . $cod_inst . "' and cod_dep = '" . $cod_dep_expediente . "' and cod_tipo_nomina = '" . $cod_tipo_nomina . "' and cod_cargo = '" . $cod_cargo . "' and cod_ficha = '" . $cod_ficha . "' and cedula = '" . $var . "'", null, "cod_amonestacion ASC"));
    }//fin funtion


}
