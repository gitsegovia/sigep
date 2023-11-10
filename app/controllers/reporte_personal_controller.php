<?php
/*
 * Created on 18/12/2008
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */

 class ReportePersonalController extends AppController{
	var $name = "reporte_personal";
	var $uses = array('ccfd04_cierre_mes','cnmd06_datos_personales','datos_personales_super_busqueda','Cnmd01','v_cnmd07_transacciones_actuales','cnmd06_fichas','depositos_bancarios_final','v_cnmd03_transacciones','v_cnmd03_partidas','arrd05');
    var $helpers = array('Html', 'Javascript', 'Ajax', 'Sisap', 'Fpdf');

 function checkSession(){
				if (!$this->Session->check('Usuario')){
						$this->redirect('/salir/');
						exit();
				}else{
					$this->requestAction('/usuarios/actualizar_user');
				}
 }//fin checksession

 function beforeFilter(){$this->checkSession();}

 function verifica_SS($i){
	switch ($i){
		case 1:return $this->Session->read('SScodpresi');break;
		case 2:return $this->Session->read('SScodentidad');break;
		case 3:return $this->Session->read('SScodtipoinst');break;
		case 4:return $this->Session->read('SScodinst');break;
		case 5:return $this->Session->read('SScoddep');break;
		case 6:return $this->Session->read('entidad_federal');break;
		default:
		   return "NULO";
	}//fin switch
 }//fin verifica_SS

 function SQLCA($ano=null){//sql para busqueda de codigos de arranque con y sin año
	     $sql_re = "cod_presi=".$this->verifica_SS(1)."  and    ";
	     $sql_re .= "cod_entidad=".$this->verifica_SS(2)."  and  ";
	     $sql_re .= "cod_tipo_inst=".$this->verifica_SS(3)."  and ";
	     $sql_re .= "cod_inst=".$this->verifica_SS(4)."  and  ";
	     if($ano!=null){
	     	$sql_re .= "cod_dep=".$this->verifica_SS(5)."  and  ";
	        $sql_re .= "ano=".$ano."  ";
	     }else{
	     	$sql_re .= "cod_dep=".$this->verifica_SS(5)." ";
	     }
	     return $sql_re;
 }//fin funcion SQLCA

 function SQLCA_Institucion($ano=null){//sql para busqueda de codigos de arranque con y sin año
		$sql_re = "cod_presi=".$this->verifica_SS(1)."  and    ";
		$sql_re .= "cod_entidad=".$this->verifica_SS(2)."  and  ";
		$sql_re .= "cod_tipo_inst=".$this->verifica_SS(3)."  and ";
		$sql_re .= "cod_inst=".$this->verifica_SS(4)." ";
		if($ano!=null){
			$sql_re .= " and ano=".$ano."  ";
		}
		return $sql_re;
	}

 function reporte_resumen_expediente_trabajador($var=null){
	$this->layout="ajax";
	if($var=='no'){
		$this->set('var',$var);
	}elseif($var=='si'){
		$this->layout="pdf";
		echo "<br>".$tipo_busqueda=$this->data['expediente_trabajador']['tipo_busqueda'];
		echo "<br>".$pista_busqueda=$this->data['expediente_trabajador']['pista_busqueda'];
		$this->set('var',$var);
	}
 }

 function radio_busqueda_expediente($opcion_busqueda=null){
 	$this->layout="ajax";
 	$this->set('opcion_busqueda',$opcion_busqueda);
 }

 function pista_busqueda_expediente($opcion_busqueda=null, $pista=null){
 	$this->layout="ajax";

	$pista_busqueda=strtoupper($pista);

 	switch($opcion_busqueda){
 		case '1':
 				if(is_numeric($pista_busqueda)){
 					$sql="SELECT cedula_identidad, primer_apellido, segundo_apellido, primer_nombre, segundo_nombre, fecha_nacimiento FROM cnmd06_datos_personales WHERE cedula_identidad=".$pista_busqueda." ORDER BY cedula_identidad, primer_apellido, segundo_apellido, primer_nombre, segundo_nombre, fecha_nacimiento";
 					$datos=$this->cnmd06_datos_personales->execute($sql);
 					$numero_registros = count($datos);
 				}else{
 					$numerico=false;
 					$datos=null;
 					$numero_registros=0;
 				}
				break;
 		case '2':
				$sql="SELECT cedula_identidad, primer_apellido, segundo_apellido, primer_nombre, segundo_nombre, fecha_nacimiento FROM cnmd06_datos_personales WHERE UPPER(primer_apellido) LIKE '%".$pista_busqueda."%' ORDER BY cedula_identidad, primer_apellido, segundo_apellido, primer_nombre, segundo_nombre, fecha_nacimiento";
				$datos=$this->cnmd06_datos_personales->execute($sql);
				$numero_registros = count($datos);
 				break;
 		case '3':
		 		$sql="SELECT cedula_identidad, primer_apellido, segundo_apellido, primer_nombre, segundo_nombre, fecha_nacimiento FROM cnmd06_datos_personales WHERE UPPER(primer_nombre) LIKE '%".$pista_busqueda."%' ORDER BY cedula_identidad, primer_apellido, segundo_apellido, primer_nombre, segundo_nombre, fecha_nacimiento";
				$datos=$this->cnmd06_datos_personales->execute($sql);
				$numero_registros = count($datos);
		 		break;
		case 'default':
				$datos=null;
		 		$numero_registros=0;
		 		break;
 	}

 	if($numero_registros==0){
 		if(isset($numerico) && $numerico==false){
 			$this->set('mensajeError','Lo siento, si marcó la opción (por cédula) debe ingresar solo datos numericos');
 		}else{
 			$this->set('mensajeError','Lo siento, no se encontrarón coincidencias para su busqueda, intente nuevamente');
 		}
 		$this->set('numero_registros',$numero_registros);
 		$this->set('datos',$datos);
 	}else{
 		$this->set('numero_registros',$numero_registros);
		$this->set('datos',$datos);
 	}
 }//pista_busqueda_expediente


function reporte_expediente_trabajador($cedula=null){
	$this->layout="pdf";
	$sql = "SELECT cedula_identidad, nacionalidad, primer_apellido, segundo_apellido, primer_nombre, segundo_nombre, fecha_nacimiento, sexo, estado_civil, grupo_sanguineo, peso_kilos, estatura_metros, naturalizado, fecha_naturalizacion, numero_gaceta, idioma,
	(SELECT p.denominacion FROM cnmd06_profesiones p WHERE p.cod_profesion=a.cod_profesion) as cod_profesion,
	(SELECT es.denominacion FROM cnmd06_especialidades es WHERE es.cod_profesion=a.cod_profesion AND es.cod_especialidad=a.cod_especialidad) as cod_especialidad,
	(SELECT ofi.denominacion FROM cnmd06_oficio ofi WHERE ofi.cod_oficio=a.cod_oficio) as cod_oficio,
	direccion_habitacion, telefonos_habitacion, otra_direccion_hab, otros_telefonos, correo_electronico, numero_inscripcion_sso, numero_inscripcion_lph, grado_licencia_conducir, numero_licencia_conducir, usa_lentes, talla_camisa_blusa, talla_pantalon_falda, talla_calzado, talla_keppy,
	(SELECT dep.denominacion FROM cnmd06_deportes dep WHERE dep.cod_deporte=a.deporte_practica) as deporte_practica,
	(SELECT re.denominacion FROM cnmd06_religiones re WHERE re.cod_religion=a.religion_pertenece) as religion_pertenece,
	(SELECT club.denominacion FROM cnmd06_clubes club WHERE club.cod_club=a.club_pertenece) as club_pertenece,
	(SELECT hob.denominacion FROM cnmd06_hobby hob WHERE hob.cod_hobby=a.hobby_favorito) as hobby_favorito,
	(SELECT col.denominacion FROM cnmd06_colores col WHERE col.cod_color=a.color_favorito) as color_favorito,
	(SELECT pais.denominacion FROM cugd01_republica pais WHERE pais.cod_republica=a.cod_pais_origen) as cod_pais_origen,
	(SELECT est.denominacion FROM cugd01_estados est WHERE est.cod_republica=a.cod_pais_origen AND est.cod_estado=a.cod_estado_origen) as cod_estado_origen,
	(SELECT mun.denominacion FROM cugd01_municipios mun WHERE mun.cod_republica=a.cod_pais_origen AND mun.cod_estado=a.cod_estado_origen AND mun.cod_municipio=a.cod_municipio_origen) as cod_municipio_origen,
	(SELECT mun.conocido FROM cugd01_municipios mun WHERE mun.cod_republica=a.cod_pais_origen AND mun.cod_estado=a.cod_estado_origen AND mun.cod_municipio=a.cod_municipio_origen) as cod_ciudad_origen,
	(SELECT parr.denominacion FROM cugd01_parroquias parr WHERE parr.cod_republica=a.cod_pais_origen AND parr.cod_estado=a.cod_estado_origen AND parr.cod_municipio=a.cod_municipio_origen AND parr.cod_parroquia=a.cod_parroquia_origen) as cod_parroquia_origen,
	(SELECT centp.denominacion FROM cugd01_centros_poblados centp WHERE centp.cod_republica=a.cod_pais_origen AND centp.cod_estado=a.cod_estado_origen AND centp.cod_municipio=a.cod_municipio_origen AND centp.cod_parroquia=a.cod_parroquia_origen AND centp.cod_centro=a.cod_centropoblado_origen) as cod_centropoblado_origen,
	(SELECT est.denominacion FROM cugd01_estados est WHERE est.cod_republica=1 AND est.cod_estado=a.cod_estado_habitacion) as cod_estado_habitacion,
	(SELECT mun.denominacion FROM cugd01_municipios mun WHERE mun.cod_republica=1 AND mun.cod_estado=a.cod_estado_habitacion AND mun.cod_municipio=a.cod_municipio_habitacion) as cod_municipio_habitacion,
	(SELECT mun.conocido FROM cugd01_municipios mun WHERE mun.cod_republica=1 AND mun.cod_estado=a.cod_estado_habitacion AND mun.cod_municipio=a.cod_municipio_habitacion) as cod_ciudad_habitacion,
	(SELECT parr.denominacion FROM cugd01_parroquias parr WHERE parr.cod_republica=1 AND parr.cod_estado=a.cod_estado_habitacion AND parr.cod_municipio=a.cod_municipio_habitacion AND parr.cod_parroquia=a.cod_parroquia_habitacion) as cod_parroquia_habitacion,
	(SELECT centp.denominacion FROM cugd01_centros_poblados centp WHERE centp.cod_republica=1 AND centp.cod_estado=a.cod_estado_habitacion AND centp.cod_municipio=a.cod_municipio_habitacion AND centp.cod_parroquia=a.cod_parroquia_habitacion AND centp.cod_centro=a.cod_centropoblado_habitacion) as cod_centropoblado_habitacion
	FROM cnmd06_datos_personales a WHERE cedula_identidad='$cedula';";

	$sql_datos_educativos = "SELECT  a.cedula,
	a.cod_nivel_educacion,
	(SELECT nivel.denominacion FROM cnmd06_nivel_educacion nivel WHERE nivel.cod_nivel_educativo=a.cod_nivel_educacion) as nivel_educativo,
	(SELECT i.denominacion FROM cnmd06_instituto_educativo i WHERE i.cod_institucion=a.cod_institucion) as inst_educativa,
	(SELECT i.denominacion FROM cnmd06_instituto_educativo i WHERE i.cod_institucion=a.cod_institucion) as inst_educativa,
	(SELECT r.denominacion FROM cugd01_republica r WHERE r.cod_republica=a.cod_republica) as pais,
	(SELECT e.denominacion FROM cugd01_estados e WHERE e.cod_republica=a.cod_republica AND e.cod_estado=a.cod_estado) as estado,
	(SELECT m.denominacion FROM cugd01_municipios m WHERE m.cod_republica=a.cod_republica AND m.cod_estado=a.cod_estado AND m.cod_municipio=a.cod_municipio) as municipio,
	a.fecha_inicio,
	a.fecha_culminacion,
	a.observaciones
	FROM cnmd06_datos_educativos a WHERE cedula='$cedula' ORDER BY cod_nivel_educacion;";

	$sql_datos_formacion_profesional = "SELECT  a.cedula,
	(SELECT curso2.denominacion FROM cnmd06_cursos curso2 WHERE curso2.cod_curso=a.cod_curso) as curso,
	(SELECT i.denominacion FROM cnmd06_instituto_educativo i WHERE i.cod_institucion=a.cod_institucion) as inst_educativa,
	a.duracion,
	a.desde,
	a.hasta,
	a.observaciones
	FROM cnmd06_datos_formacion_profesional a WHERE cedula='$cedula';";

	$sql_datos_registro_titulo = "SELECT  a.cedula,
	(SELECT p.denominacion FROM cnmd06_profesiones p WHERE p.cod_profesion=a.cod_profesion) as profesion,
	(SELECT esp.denominacion FROM cnmd06_especialidades esp WHERE esp.cod_profesion=a.cod_profesion AND esp.cod_especialidad=a.cod_especialidad) as especialidad,
	a.numero_registro,
	a.tomo,
	a.folios,
	a.fecha_registro,
	a.cod_colegio,
	(SELECT c.denominacion FROM cnmd06_colegio_profesional c WHERE c.cod_colegio=a.cod_colegio) as colegio_profesional,
	a.numero_colegio
	FROM cnmd06_datos_registro_titulo a WHERE cedula='$cedula';";

	$sql_datos_experiencia_administracion = "SELECT 	a.cedula,
	a.consecutivo,
	a.cargo_desempenado,
	a.entidad_federal,
	a.fecha_ingreso,
	a.fecha_egreso,
	a.motivo_salida
	FROM cnmd06_datos_experiencia_administrativa a WHERE cedula='$cedula' ORDER BY consecutivo;";

	$sql_datos_experiencia_otras = "SELECT 	a.cedula,
	a.consecutivo,
	a.cargo_desempenado,
	a.empresa,
	a.fecha_ingreso,
	a.fecha_egreso,
	a.motivo_retiro
	FROM cnmd06_datos_otrasexperiencias_laborables a WHERE cedula='$cedula' ORDER BY consecutivo;";

	$sql_datos_familiares = "SELECT 	a.cedula,
	(SELECT p.denominacion FROM cnmd06_parentesco p WHERE p.cod_parentesco=a.cod_parentesco) as parentesco,
	a.consecutivo,
	a.nombres_apellidos,
	a.numero_cedula,
	a.fecha_nacimiento,
	a.sexo,
	a.afiliado,
	a.cod_guarderia,
	costo_guarderia
	FROM cnmd06_datos_familiares a WHERE cedula='$cedula' ORDER BY a.cod_parentesco, a.consecutivo;";

	$sql_datos_bienes = "SELECT 	a.cedula_identidad,
	(SELECT b.denominacion FROM cnmd06_bienes b WHERE b.cod_bien=a.cod_bien) as bien,
	a.consecutivo,
	a.ano_compra,
	a.costo,
	a.cancelado
	FROM cnmd06_datos_bienes a WHERE cedula_identidad='$cedula' ORDER BY a.ano_compra, a.consecutivo;";

	$datos = $this->cnmd06_datos_personales->execute($sql);
	$datos_educativos = $this->cnmd06_datos_personales->execute($sql_datos_educativos);
	$datos_formacion_profesional = $this->cnmd06_datos_personales->execute($sql_datos_formacion_profesional);
	$datos_registro_titulo = $this->cnmd06_datos_personales->execute($sql_datos_registro_titulo);
	$datos_experiencia_administracion = $this->cnmd06_datos_personales->execute($sql_datos_experiencia_administracion);
	$datos_experiencia_otras = $this->cnmd06_datos_personales->execute($sql_datos_experiencia_otras);
	$datos_familiares = $this->cnmd06_datos_personales->execute($sql_datos_familiares);
	$datos_bienes = $this->cnmd06_datos_personales->execute($sql_datos_bienes);
	$datos_soportes = $this->cnmd06_datos_personales->execute("SELECT a.cod_soporte FROM cnmd06_soportes a WHERE cedula='$cedula' ORDER BY a.cod_soporte;");

	$this->set('datos',$datos);
	$this->set('datos_educativos',$datos_educativos);
	$this->set('datos_formacion_profesional',$datos_formacion_profesional);
	$this->set('datos_registro_titulo',$datos_registro_titulo);
	$this->set('datos_experiencia_administracion',$datos_experiencia_administracion);
	$this->set('datos_experiencia_otras',$datos_experiencia_otras);
	$this->set('datos_familiares',$datos_familiares);
	$this->set('datos_bienes',$datos_bienes);
	$this->set('datos_soportes',$datos_soportes);

	/**
	 * fotos en el reporte
	 */      $rs_img_count=$this->cnmd06_datos_personales->execute("SELECT count(*) as contar FROM cugd10_imagenes where identificacion='$cedula' and cod_campo='11'");
             if($rs_img_count[0][0]['contar']!=0){
             	 $rs_img=$this->cnmd06_datos_personales->execute("SELECT coalesce(imagen,'-1') as imagen_grande,tipo,size FROM cugd10_imagenes where identificacion='$cedula' and cod_campo='11'");
             }else{
             	$rs_img=$this->cnmd06_datos_personales->execute("SELECT coalesce(imagen,'-1') as imagen_grande,tipo,size FROM cugd10_imagenes where identificacion='0' and cod_campo='0'");
             }
             $aleatorio=intval(rand());
	 	     $DATA_IMG_ORIGINAL=pg_unescape_bytea($rs_img[0][0]["imagen_grande"]);
		   	 //echo ROOT;
		   	 $nombre_archivo_crear_ori=ROOT.'/app/tmp/fotos/foto_'.$cedula.'_'.$aleatorio.".".extension($rs_img[0][0]["tipo"]);
		   	 $this->set('url_foto',$nombre_archivo_crear_ori);
		     $fp2=fopen($nombre_archivo_crear_ori,"wb");
		     fwrite($fp2, $DATA_IMG_ORIGINAL);
		     fclose($fp2);


}//reporte_expediente_trabajador


function buscar_persona($var1=null){
	$this->layout="ajax";
	$this->set("opcion",$var1);
	$this->Session->delete('pista');
	$this->Session->write('pista_opcion', 2);
}//buscar_persona


function buscar_por_pista($var1=null, $var2=null, $var3=null){
	$this->layout="ajax";
	$sql_like = "";

    if($var3==null){
		$this->Session->write('pista', $var2);
		$var2 = strtoupper($var2);
		$var_like = $var2;
		$sql_like = $this->sql_like_super_busqueda($var_like, $this->Session->read('pista_opcion'));
		$Tfilas=$this->datos_personales_super_busqueda->findCount($sql_like);
        if($Tfilas!=0){
        	$pagina=1;
        	$Tfilas=(int)ceil($Tfilas/100);
        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
			$this->set('total_paginas',$Tfilas);
			$this->set('pagina_actual',$pagina);
			$this->set('ultimo',$Tfilas);
     	    $datos_filas=$this->datos_personales_super_busqueda->findAll($sql_like,null,"primer_nombre,primer_apellido ASC",100,1,null);
	        $this->set("datosFILAS",$datos_filas);
	        $this->set('siguiente',$pagina+1);
			$this->set('anterior',$pagina-1);
			$this->bt_nav($Tfilas,$pagina);
          }else{
        	$this->set("datosFILAS",'');
          }
	}else{
		$var22 = $this->Session->read('pista');
		$var22 = strtoupper($var22);
		$var_like = $var22;
		$sql_like = $this->sql_like_super_busqueda($var_like, $this->Session->read('pista_opcion'));
		$Tfilas=$this->datos_personales_super_busqueda->findCount($sql_like);
		if($Tfilas!=0){
        	$pagina=$var3;
        	$Tfilas=(int)ceil($Tfilas/100);
        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
			$this->set('total_paginas',$Tfilas);
			$this->set('pagina_actual',$pagina);
			$this->set('ultimo',$Tfilas);
     	    $datos_filas=$this->datos_personales_super_busqueda->findAll($sql_like,null,"primer_nombre,primer_apellido ASC",100,$pagina,null);
	        $this->set("datosFILAS",$datos_filas);
	        $this->set('siguiente',$pagina+1);
			$this->set('anterior',$pagina-1);
			$this->bt_nav($Tfilas,$pagina);
          }else{
        	$this->set("datosFILAS",'');
          }
	}//fin else
$this->set("opcion",$var1);
}//buscar_por_pista


function bt_nav($Tfilas,$pagina){
	if($Tfilas==1){
		$this->set('mostrarS',false);
		$this->set('mostrarA',false);
	}else if($Tfilas==2){
		if($pagina==2){
			$this->set('mostrarS',false);
			$this->set('mostrarA',true);
		}else{
			$this->set('mostrarS',true);
			$this->set('mostrarA',false);
		}
	}else if($Tfilas>=3){
		if($pagina==$Tfilas){
		     $this->set('mostrarS',false);
		     $this->set('mostrarA',true);
		}else if($pagina==1){
			 $this->set('mostrarS',true);
		     $this->set('mostrarA',false);
		}else{
			 $this->set('mostrarS',true);
		     $this->set('mostrarA',true);
		}
	}
}//fin navegacion


function listado_de_pago($var=null){
	$this->layout="ajax";
	$cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	if($var=='no'){
		$condicion = $this->SQLCA();
		$lista = $this->Cnmd01->generateList($condicion, 'cod_tipo_nomina ASC', null, '{n}.Cnmd01.cod_tipo_nomina', '{n}.Cnmd01.denominacion');
		if($lista!=null){
			$this->concatena($lista, 'lista');
		}else{
			$this->set('lista',array('no'=>'no hay registros'));
		}
		$this->set('var',$var);

	}elseif($var=='si'){
		$this->layout="pdf";
		$tipo_nomina = $this->data['reporte_personal']['select_tiponomina'];
		$opcion_ordenar   = $this->data["reporte_juan_nomina"]["opcion_ordenar"];

		// Ordenado por ubicacion administrativa
		      if($opcion_ordenar == 1){
            $order_by = "cod_dir_superior, cod_coordinacion, cod_secretaria, cod_direccion, cod_division, cod_departamento, cod_oficina,  cedula_identidad ASC";
		// Ordenado por ubicacion geografica
		}else if ($opcion_ordenar == 2){
			$order_by = "cod_estado, cod_municipio, cod_parroquia, cod_centro,  cedula_identidad ASC";
		// Ordenado por ubicacion administrativa y geografica
		}else if ($opcion_ordenar == 3){
			$order_by = "cod_dir_superior, cod_coordinacion, cod_secretaria, cod_direccion, cod_division, cod_departamento, cod_oficina,  cod_estado, cod_municipio, cod_parroquia, cod_centro, cedula_identidad ASC";
		// Ordenado por categoria programatica
		}else if ($opcion_ordenar == 4){
			$order_by = "ano, cod_sector, cod_programa, cod_sub_prog, cod_proyecto, cod_activ_obra, cod_partida, cod_generica, cod_especifica, cod_sub_espec, cod_auxiliar,  cedula_identidad ASC";
        // Ordenado por categoria programatica y administrativa
        }else if ($opcion_ordenar == 5){
			$order_by = "ano, cod_sector, cod_programa, cod_sub_prog, cod_proyecto, cod_activ_obra, cod_partida, cod_generica, cod_especifica, cod_sub_espec, cod_auxiliar,  cod_dir_superior, cod_coordinacion, cod_secretaria, cod_direccion, cod_division, cod_departamento, cod_oficina, cedula_identidad ASC";


		}
		$sql_listado_pago = "SELECT a.cod_tipo_nomina,
								a.cod_cargo,
								a.cod_ficha,
								(select devolver_denominacion_puesto(
								   (select xy.clasificacion_personal from cnmd01 xy where
								   xy.cod_presi		=     a.cod_presi       and
								   xy.cod_entidad	=     a.cod_entidad     and
								   xy.cod_tipo_inst	=     a.cod_tipo_inst   and
								   xy.cod_inst		=     a.cod_inst        and
								   xy.cod_dep		=     a.cod_dep         and
								   xy.cod_tipo_nomina	=     a.cod_tipo_nomina
								   ), (SELECT cnmd05.cod_puesto FROM cnmd05 WHERE cnmd05.cod_presi=a.cod_presi AND cnmd05.cod_entidad=a.cod_entidad AND cnmd05.cod_tipo_inst=a.cod_tipo_inst AND cnmd05.cod_inst=a.cod_inst AND cnmd05.cod_dep=a.cod_dep AND cnmd05.cod_tipo_nomina=a.cod_tipo_nomina AND cnmd05.cod_cargo=a.cod_cargo AND cnmd05.cod_ficha=a.cod_ficha))
								) as denominacion_puesto,
								a.cedula_identidad,
								b.primer_apellido,
								b.segundo_apellido,
								b.primer_nombre,
								b.segundo_nombre,

								(SELECT xa.denominacion FROM cugd02_direccionsuperior xa where xa.cod_tipo_institucion=c.cod_tipo_inst  and xa.cod_institucion=c.cod_inst and xa.cod_dependencia=c.cod_dep and xa.cod_dir_superior=c.cod_dir_superior                                                                                                                                                                                                                                        GROUP BY xa.denominacion) as  deno_cod_dir_superior,
								(SELECT xb.denominacion FROM cugd02_coordinacion xb      where xb.cod_tipo_institucion=c.cod_tipo_inst  and xb.cod_institucion=c.cod_inst and xb.cod_dependencia=c.cod_dep and xb.cod_dir_superior=c.cod_dir_superior and xb.cod_coordinacion=c.cod_coordinacion                                                                                                                                                                                             GROUP BY xb.denominacion) as  deno_cod_coordinacion,
								(SELECT xc.denominacion FROM cugd02_secretaria xc        where xc.cod_tipo_institucion=c.cod_tipo_inst  and xc.cod_institucion=c.cod_inst and xc.cod_dependencia=c.cod_dep and xc.cod_dir_superior=c.cod_dir_superior and xc.cod_coordinacion=c.cod_coordinacion and xc.cod_secretaria=c.cod_secretaria                                                                                                                                                      GROUP BY xc.denominacion) as  deno_cod_secretaria,
								(SELECT xd.denominacion FROM cugd02_direccion xd         where xd.cod_tipo_institucion=c.cod_tipo_inst  and xd.cod_institucion=c.cod_inst and xd.cod_dependencia=c.cod_dep and xd.cod_dir_superior=c.cod_dir_superior and xd.cod_coordinacion=c.cod_coordinacion and xd.cod_secretaria=c.cod_secretaria and xd.cod_direccion=c.cod_direccion                                                                                                                 GROUP BY xd.denominacion) as  deno_cod_direccion,
								(SELECT xe.denominacion FROM cugd02_division xe          where xe.cod_tipo_institucion=c.cod_tipo_inst  and xe.cod_institucion=c.cod_inst and xe.cod_dependencia=c.cod_dep and xe.cod_dir_superior=c.cod_dir_superior and xe.cod_coordinacion=c.cod_coordinacion and xe.cod_secretaria=c.cod_secretaria and xe.cod_direccion=c.cod_direccion  and xe.cod_division=c.cod_division                                                                             GROUP BY xe.denominacion) as  deno_cod_division,
								(SELECT xf.denominacion FROM cugd02_departamento xf      where xf.cod_tipo_institucion=c.cod_tipo_inst  and xf.cod_institucion=c.cod_inst and xf.cod_dependencia=c.cod_dep and xf.cod_dir_superior=c.cod_dir_superior and xf.cod_coordinacion=c.cod_coordinacion and xf.cod_secretaria=c.cod_secretaria and xf.cod_direccion=c.cod_direccion  and xf.cod_division=c.cod_division and xf.cod_departamento=c.cod_departamento                                  GROUP BY xf.denominacion) as  deno_cod_departamento,
								(SELECT xg.denominacion FROM cugd02_oficina xg           where xg.cod_tipo_institucion=c.cod_tipo_inst  and xg.cod_institucion=c.cod_inst and xg.cod_dependencia=c.cod_dep and xg.cod_dir_superior=c.cod_dir_superior and xg.cod_coordinacion=c.cod_coordinacion and xg.cod_secretaria=c.cod_secretaria and xg.cod_direccion=c.cod_direccion  and xg.cod_division=c.cod_division and xg.cod_departamento=c.cod_departamento and xg.cod_oficina=c.cod_oficina GROUP BY xg.denominacion) as  deno_cod_oficina,

                                (SELECT xya.denominacion FROM cugd01_estados          xya where xya.cod_republica=c.cod_presi and xya.cod_estado=c.cod_estado                                                                                                                  GROUP BY xya.denominacion) as  deno_cod_estado,
								(SELECT xyb.denominacion FROM cugd01_municipios       xyb where xyb.cod_republica=c.cod_presi and xyb.cod_estado=c.cod_estado  and xyb.cod_municipio=c.cod_municipio                                                                           GROUP BY xyb.denominacion) as  deno_cod_municipio,
							    (SELECT xyc.denominacion FROM cugd01_parroquias       xyc where xyc.cod_republica=c.cod_presi and xyc.cod_estado=c.cod_estado  and xyc.cod_municipio=c.cod_municipio and xyc.cod_parroquia = c.cod_parroquia                                   GROUP BY xyc.denominacion) as  deno_cod_parroquia,
								(SELECT xyd.denominacion FROM cugd01_centros_poblados xyd where xyd.cod_republica=c.cod_presi and xyd.cod_estado=c.cod_estado  and xyd.cod_municipio=c.cod_municipio and xyd.cod_parroquia = c.cod_parroquia and xyd.cod_centro = c.cod_centro GROUP BY xyd.denominacion) as  deno_cod_centro,


								c.cod_estado,
								c.cod_municipio,
								c.cod_parroquia,
								c.cod_centro,
                                c.cod_dir_superior,
                                c.cod_coordinacion,
								c.cod_secretaria,
								c.cod_direccion,
								c.cod_division,
								c.cod_departamento,
								c.cod_oficina,
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

								(select SUM(d.monto_cuota) from v_cnmd07_transacciones_actuales_frecuencias2 d where d.cod_presi	        = '".$cod_presi."'     and
																												d.cod_entidad	    = '".$cod_entidad."'   and
																												d.cod_tipo_inst	    = '".$cod_tipo_inst."' and
																												d.cod_inst	        = '".$cod_inst."'      and
																												d.cod_dep	        = '".$cod_dep."'       and
																												d.cod_tipo_nomina   = '$tipo_nomina'       and
																												d.cod_cargo         = a.cod_cargo          and
									                                                                            d.cod_ficha         = a.cod_ficha          and
									                                                                            d.cod_tipo_transaccion = 1

						         ) as asignacion,
						         (select SUM(d.monto_cuota) from v_cnmd07_transacciones_actuales_frecuencias2 d where d.cod_presi	        = '".$cod_presi."'     and
																												 d.cod_entidad	    = '".$cod_entidad."'   and
																												 d.cod_tipo_inst	    = '".$cod_tipo_inst."' and
																												 d.cod_inst	        = '".$cod_inst."'      and
																												 d.cod_dep	        = '".$cod_dep."'       and
																												 d.cod_tipo_nomina   = '$tipo_nomina'       and
																												 d.cod_cargo         = a.cod_cargo          and
									                                                                             d.cod_ficha         = a.cod_ficha          and
									                                                                             d.cod_tipo_transaccion = 2                 and
									                                                                             (d.uso_transaccion!=6 and d.uso_transaccion!=9)

						         ) as deduciones

							FROM cnmd06_fichas a, cnmd06_datos_personales b, cnmd05 c

							WHERE

									a.cod_presi	        = '".$cod_presi."'     and
									a.cod_entidad	    = '".$cod_entidad."'   and
									a.cod_tipo_inst	    = '".$cod_tipo_inst."' and
									a.cod_inst	        = '".$cod_inst."'      and
									a.cod_dep	        = '".$cod_dep."'       and
									a.cod_tipo_nomina   = '$tipo_nomina'       and
									b.cedula_identidad	= a.cedula_identidad   and
									c.cod_entidad       = a.cod_entidad        and
									c.cod_tipo_inst     = a.cod_tipo_inst      and
									c.cod_inst          = a.cod_inst           and
									c.cod_dep           = a.cod_dep            and
									c.cod_tipo_nomina   = a.cod_tipo_nomina    and
									c.cod_cargo         = a.cod_cargo          and

									(a.condicion_actividad=1) ORDER BY ".$order_by.";";



		$datos_tipo_nomina = $this->Cnmd01->execute("SELECT cod_tipo_nomina, denominacion, correspondiente, numero_nomina, periodo_desde, periodo_hasta FROM cnmd01 WHERE ".$this->SQLCA()." and cod_tipo_nomina='$tipo_nomina'");
		$datos_listado_pago = $this->Cnmd01->execute($sql_listado_pago);
		$this->set('datos_tipo_nomina',$datos_tipo_nomina);
		$this->set('datos_listado_pago',$datos_listado_pago);
		$this->set('var',$var);
		$this->set('opcion_ordenar',$opcion_ordenar);

	}
}























function listado_de_pago_historico($var=null){
	$this->layout="ajax";
	$cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	if($var=='no'){
		$condicion = $this->SQLCA();
		$lista = $this->Cnmd01->generateList($condicion, 'cod_tipo_nomina ASC', null, '{n}.Cnmd01.cod_tipo_nomina', '{n}.Cnmd01.denominacion');
		if($lista!=null){
			$this->concatena($lista, 'lista_nomina');
		}else{
			$this->set('lista',array('no'=>'no hay registros'));
		}
		$this->set('var',$var);

	}elseif($var=='si'){
		$this->layout="pdf";
		$tipo_nomina      = $this->data['cnmp06_diskett_historico']['cod_nomina'];
		$ano              = $this->data['cnmp06_diskett_historico']['ano_nomina'];
		$num_nomina       = $this->data['cnmp06_diskett_historico']['numero_nomina'];
		$opcion_ordenar   = $this->data["reporte_juan_nomina"]["opcion_ordenar"];

		// Ordenado por ubicacion administrativa
		      if($opcion_ordenar == 1){
            $order_by = "cod_dir_superior, cod_coordinacion, cod_secretaria, cod_direccion, cod_division, cod_departamento, cod_oficina,  cedula_identidad ASC";
		// Ordenado por ubicacion geografica
		}else if ($opcion_ordenar == 2){
			$order_by = "cod_estado, cod_municipio, cod_parroquia, cod_centro,  cedula_identidad ASC";
		// Ordenado por ubicacion administrativa y geografica
		}else if ($opcion_ordenar == 3){
			$order_by = "cod_dir_superior, cod_coordinacion, cod_secretaria, cod_direccion, cod_division, cod_departamento, cod_oficina,  cod_estado, cod_municipio, cod_parroquia, cod_centro, cedula_identidad ASC";
		// Ordenado por categoria programatica
		}else if ($opcion_ordenar == 4){
			$order_by = "ano, cod_sector, cod_programa, cod_sub_prog, cod_proyecto, cod_activ_obra, cod_partida, cod_generica, cod_especifica, cod_sub_espec, cod_auxiliar,  cedula_identidad ASC";
        // Ordenado por categoria programatica y administrativa
        }else if ($opcion_ordenar == 5){
			$order_by = "ano, cod_sector, cod_programa, cod_sub_prog, cod_proyecto, cod_activ_obra, cod_partida, cod_generica, cod_especifica, cod_sub_espec, cod_auxiliar,  cod_dir_superior, cod_coordinacion, cod_secretaria, cod_direccion, cod_division, cod_departamento, cod_oficina, cedula_identidad ASC";


		}
		$sql_listado_pago = "SELECT a.cod_tipo_nomina,
								a.cod_cargo,
								a.cod_ficha,
								(select devolver_denominacion_puesto(
								   (select xy.clasificacion_personal from cnmd01 xy where
								   xy.cod_presi		=     a.cod_presi       and
								   xy.cod_entidad	=     a.cod_entidad     and
								   xy.cod_tipo_inst	=     a.cod_tipo_inst   and
								   xy.cod_inst		=     a.cod_inst        and
								   xy.cod_dep		=     a.cod_dep         and
								   xy.cod_tipo_nomina	=     a.cod_tipo_nomina
								   ), (SELECT cnmd05.cod_puesto FROM cnmd05 WHERE cnmd05.cod_presi=a.cod_presi AND cnmd05.cod_entidad=a.cod_entidad AND cnmd05.cod_tipo_inst=a.cod_tipo_inst AND cnmd05.cod_inst=a.cod_inst AND cnmd05.cod_dep=a.cod_dep AND cnmd05.cod_tipo_nomina=a.cod_tipo_nomina AND cnmd05.cod_cargo=a.cod_cargo AND cnmd05.cod_ficha=a.cod_ficha))
								) as denominacion_puesto,
								a.cedula_identidad,
								b.primer_apellido,
								b.segundo_apellido,
								b.primer_nombre,
								b.segundo_nombre,

								(SELECT xa.denominacion FROM cugd02_direccionsuperior xa where xa.cod_tipo_institucion=c.cod_tipo_inst  and xa.cod_institucion=c.cod_inst and xa.cod_dependencia=c.cod_dep and xa.cod_dir_superior=c.cod_dir_superior                                                                                                                                                                                                                                        GROUP BY xa.denominacion) as  deno_cod_dir_superior,
								(SELECT xb.denominacion FROM cugd02_coordinacion xb      where xb.cod_tipo_institucion=c.cod_tipo_inst  and xb.cod_institucion=c.cod_inst and xb.cod_dependencia=c.cod_dep and xb.cod_dir_superior=c.cod_dir_superior and xb.cod_coordinacion=c.cod_coordinacion                                                                                                                                                                                             GROUP BY xb.denominacion) as  deno_cod_coordinacion,
								(SELECT xc.denominacion FROM cugd02_secretaria xc        where xc.cod_tipo_institucion=c.cod_tipo_inst  and xc.cod_institucion=c.cod_inst and xc.cod_dependencia=c.cod_dep and xc.cod_dir_superior=c.cod_dir_superior and xc.cod_coordinacion=c.cod_coordinacion and xc.cod_secretaria=c.cod_secretaria                                                                                                                                                      GROUP BY xc.denominacion) as  deno_cod_secretaria,
								(SELECT xd.denominacion FROM cugd02_direccion xd         where xd.cod_tipo_institucion=c.cod_tipo_inst  and xd.cod_institucion=c.cod_inst and xd.cod_dependencia=c.cod_dep and xd.cod_dir_superior=c.cod_dir_superior and xd.cod_coordinacion=c.cod_coordinacion and xd.cod_secretaria=c.cod_secretaria and xd.cod_direccion=c.cod_direccion                                                                                                                 GROUP BY xd.denominacion) as  deno_cod_direccion,
								(SELECT xe.denominacion FROM cugd02_division xe          where xe.cod_tipo_institucion=c.cod_tipo_inst  and xe.cod_institucion=c.cod_inst and xe.cod_dependencia=c.cod_dep and xe.cod_dir_superior=c.cod_dir_superior and xe.cod_coordinacion=c.cod_coordinacion and xe.cod_secretaria=c.cod_secretaria and xe.cod_direccion=c.cod_direccion  and xe.cod_division=c.cod_division                                                                             GROUP BY xe.denominacion) as  deno_cod_division,
								(SELECT xf.denominacion FROM cugd02_departamento xf      where xf.cod_tipo_institucion=c.cod_tipo_inst  and xf.cod_institucion=c.cod_inst and xf.cod_dependencia=c.cod_dep and xf.cod_dir_superior=c.cod_dir_superior and xf.cod_coordinacion=c.cod_coordinacion and xf.cod_secretaria=c.cod_secretaria and xf.cod_direccion=c.cod_direccion  and xf.cod_division=c.cod_division and xf.cod_departamento=c.cod_departamento                                  GROUP BY xf.denominacion) as  deno_cod_departamento,
								(SELECT xg.denominacion FROM cugd02_oficina xg           where xg.cod_tipo_institucion=c.cod_tipo_inst  and xg.cod_institucion=c.cod_inst and xg.cod_dependencia=c.cod_dep and xg.cod_dir_superior=c.cod_dir_superior and xg.cod_coordinacion=c.cod_coordinacion and xg.cod_secretaria=c.cod_secretaria and xg.cod_direccion=c.cod_direccion  and xg.cod_division=c.cod_division and xg.cod_departamento=c.cod_departamento and xg.cod_oficina=c.cod_oficina GROUP BY xg.denominacion) as  deno_cod_oficina,

                                (SELECT xya.denominacion FROM cugd01_estados          xya where xya.cod_republica=c.cod_presi and xya.cod_estado=c.cod_estado                                                                                                                  GROUP BY xya.denominacion) as  deno_cod_estado,
								(SELECT xyb.denominacion FROM cugd01_municipios       xyb where xyb.cod_republica=c.cod_presi and xyb.cod_estado=c.cod_estado  and xyb.cod_municipio=c.cod_municipio                                                                           GROUP BY xyb.denominacion) as  deno_cod_municipio,
							    (SELECT xyc.denominacion FROM cugd01_parroquias       xyc where xyc.cod_republica=c.cod_presi and xyc.cod_estado=c.cod_estado  and xyc.cod_municipio=c.cod_municipio and xyc.cod_parroquia = c.cod_parroquia                                   GROUP BY xyc.denominacion) as  deno_cod_parroquia,
								(SELECT xyd.denominacion FROM cugd01_centros_poblados xyd where xyd.cod_republica=c.cod_presi and xyd.cod_estado=c.cod_estado  and xyd.cod_municipio=c.cod_municipio and xyd.cod_parroquia = c.cod_parroquia and xyd.cod_centro = c.cod_centro GROUP BY xyd.denominacion) as  deno_cod_centro,


								c.cod_estado,
								c.cod_municipio,
								c.cod_parroquia,
								c.cod_centro,
                                c.cod_dir_superior,
                                c.cod_coordinacion,
								c.cod_secretaria,
								c.cod_direccion,
								c.cod_division,
								c.cod_departamento,
								c.cod_oficina,
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

								(select SUM(d.monto_cuota) from v_cnmd07_transacciones_actuales_frecuencias2 d where d.cod_presi	        = '".$cod_presi."'     and
																												d.cod_entidad	    = '".$cod_entidad."'   and
																												d.cod_tipo_inst	    = '".$cod_tipo_inst."' and
																												d.cod_inst	        = '".$cod_inst."'      and
																												d.cod_dep	        = '".$cod_dep."'       and
																												d.cod_tipo_nomina   = '$tipo_nomina'       and
																												d.cod_cargo         = a.cod_cargo          and
									                                                                            d.cod_ficha         = a.cod_ficha          and
									                                                                            d.cod_tipo_transaccion = 1

						         ) as asignacion,
						         (select SUM(d.monto_cuota) from v_cnmd07_transacciones_actuales_frecuencias2 d where d.cod_presi	        = '".$cod_presi."'     and
																												 d.cod_entidad	    = '".$cod_entidad."'   and
																												 d.cod_tipo_inst	    = '".$cod_tipo_inst."' and
																												 d.cod_inst	        = '".$cod_inst."'      and
																												 d.cod_dep	        = '".$cod_dep."'       and
																												 d.cod_tipo_nomina   = '$tipo_nomina'       and
																												 d.cod_cargo         = a.cod_cargo          and
									                                                                             d.cod_ficha         = a.cod_ficha          and
									                                                                             d.cod_tipo_transaccion = 2                 and
									                                                                             (d.uso_transaccion!=6 and d.uso_transaccion!=9)

						         ) as deduciones

							FROM cnmd08_historia_trabajador a, cnmd06_datos_personales b, cnmd05 c

							WHERE

									a.cod_presi	        = '".$cod_presi."'     and
									a.cod_entidad	    = '".$cod_entidad."'   and
									a.cod_tipo_inst	    = '".$cod_tipo_inst."' and
									a.cod_inst	        = '".$cod_inst."'      and
									a.cod_dep	        = '".$cod_dep."'       and
									a.cod_tipo_nomina   = '$tipo_nomina'       and
									b.cedula_identidad	= a.cedula_identidad   and
									c.cod_entidad       = a.cod_entidad        and
									c.cod_tipo_inst     = a.cod_tipo_inst      and
									c.cod_inst          = a.cod_inst           and
									c.cod_dep           = a.cod_dep            and
									c.cod_tipo_nomina   = a.cod_tipo_nomina    and
									c.cod_cargo         = a.cod_cargo          and
									a.ano='".$ano."' and a.numero_nomina='".$num_nomina."'
									 ORDER BY ".$order_by.";";



		$datos_tipo_nomina = $this->Cnmd01->execute("SELECT cod_tipo_nomina, denominacion_nomina, correspondiente, numero_nomina, periodo_desde, periodo_hasta FROM v_cnmd08_historia_transacciones_condicion WHERE ".$this->SQLCA()." and cod_tipo_nomina='$tipo_nomina' and ano='".$ano."' and numero_nomina='".$num_nomina."' ");
		$datos_listado_pago = $this->Cnmd01->execute($sql_listado_pago);
		$this->set('datos_tipo_nomina',$datos_tipo_nomina);
		$this->set('datos_listado_pago',$datos_listado_pago);
		$this->set('var',$var);
		$this->set('opcion_ordenar',$opcion_ordenar);

	}
}


///PORTAL PATRIA
function depositos_patria($var=null,$cod_tipo_nomina=null,$cod_entidad_bancaria=null,$tipo_ordenamiento=1){
  $this->layout="ajax";
  $nacion=0;
  $nacionalidad=0;
  $cedula=0;
  $cedula_identidad=0;
  $cuenta_bancaria =0;
  $asignaciones=0;
  $deducciones =0;
  $neto_cobrar_1_if =0;
  $codigo_empresa=0;
  if($var=='no'){
    $condicion = $this->SQLCA()." and status_nomina in (4)";
    $lista = $this->Cnmd01->generateListTxt($condicion, 'cod_tipo_nomina ASC', null, '{n}.Cnmd01.cod_tipo_nomina', '{n}.Cnmd01.denominacion');
    if($lista!=null){
      $this->concatenaN($lista, 'lista');
    }else{
      $this->set('lista',array('no'=>'no hay registros'));
    }
    $this->set('var',$var);
  }elseif($var=='txt'){
    $this->layout="txt";
    $cod_presi     = $this->Session->read('SScodpresi');
    $cod_entidad   = $this->Session->read('SScodentidad');
    $cod_tipo_inst = $this->Session->read('SScodtipoinst');
    $cod_inst      = $this->Session->read('SScodinst');
    $cod_dep       = $this->Session->read('SScoddep');
    $this->data['reporte_personal']['select_tiponomina'];
    $data3agurpado=$this->Cnmd01->execute("SELECT cod_entidad_bancaria FROM v_cnmd06_fichas_asig_ded WHERE ".$this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina." GROUP BY cod_entidad_bancaria ORDER BY cod_entidad_bancaria ASC");
    $data3FechaNomina=$this->Cnmd01->execute("SELECT periodo_hasta,periodo_desde FROM cnmd01 WHERE ".$this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina."");
    $tipoDependencia=$this->Cnmd01->execute("SELECT tipo_dependencia FROM cugd02_dependencias WHERE cod_tipo_institucion=$cod_tipo_inst and cod_institucion=$cod_inst and cod_dependencia=$cod_dep");
      //$tipo_dependencia = $tipoDependencia[0][0]['tipo_dependencia'];
    $tipo_dependencia = $cod_dep;
    $fecha_nomina = date('Y-d-m');//$data3FechaNomina[0][0]['periodo_hasta'];
    $fecha_nomina = cambiar_formato_fecha($fecha_nomina);
    $fecha_nomina = str_replace("/","",$fecha_nomina);
    $fecha_nomina2 = $data3FechaNomina[0][0]['periodo_hasta'];
    $fecha_nomina2_desde = $data3FechaNomina[0][0]['periodo_desde'];
    $fecha_nomina2_array = explode('-',$data3FechaNomina[0][0]['periodo_hasta']);
    $fecha_nomina2_array_desde = explode('-',$data3FechaNomina[0][0]['periodo_desde']);
    $fecha_nomina2 = cambiar_formato_fecha($fecha_nomina2);
    $fecha_nomina2 = str_replace("/","",$fecha_nomina2);//AAAAMMDD
    $fecha_nomina3 = substr($fecha_nomina2_array[0],2,2).$fecha_nomina2_array[1].$fecha_nomina2_array[2];///AAMMDD    año mes dia
    $fecha_nomina4 = $fecha_nomina2_array[2].$fecha_nomina2_array[1].substr($fecha_nomina2_array[0],2,2);///DDMMAA    dia mes año
    $fecha_nomina4BOD = $fecha_nomina2_array[0].$fecha_nomina2_array[1].$fecha_nomina2_array[2];///AAAAMMDD    año mes dia
    $fecha_nomina4BOD_nuevo_formato = 'DE'.$fecha_nomina2_array_desde[2].$fecha_nomina2_array_desde[1].substr($fecha_nomina2_array_desde[0],-2).'A'.$fecha_nomina2_array[2].$fecha_nomina2_array[1].substr($fecha_nomina2_array[0],-2);///DE DDMMAA A DDMMAA

    $nombre_archivo = 'Nomina_'.mascara($cod_dep,4).'_'.mascara($cod_tipo_nomina,3).'_'.date('d_m_Y_h:i:sa').'';
    $_SESSION["nombre_txt"]=$nombre_archivo.".txt";


    if($_SESSION['SScodpresi']==1 && $_SESSION['SScodentidad']==12 && $_SESSION['SScodtipoinst']==30 && $_SESSION['SScodinst']==12){// GOBERNACION DEL ESTADO GUARICO

      foreach($data3agurpado as $rsagrupado){
        //$cod_sucursal_agrupada = $rsagrupado[0]['cod_sucursal'];
        $data3CuentaBancariaCancela=$this->Cnmd01->execute("SELECT cuenta_bancaria as cuenta_bancaria_cancela,rif FROM cnmd09_bancos_cancelan_nominas WHERE ".$this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina." ORDER BY cod_sucursal ASC LIMIT 1");
            
        $rif = $data3CuentaBancariaCancela[0][0]['rif'];
        $rif = str_replace("-","",$rif);
        $rif = substr($rif,0,1)."".mascara(substr($rif,1),9);//DEJAR TODO PEGADO 10 caracteres
        // $rif = str_pad($rif, 15 , " ", STR_PAD_RIGHT).'';

        $data3=$this->Cnmd01->execute("SELECT * FROM v_cnmd06_fichas_asig_ded WHERE ".$this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina." ORDER BY cedula_identidad ASC");
        $this->set('data3',$data3);
        $filas_archivo_aux="";
        $c_registros = 0;
        $c_montos = 0;

        foreach($data3 as $rsdata){
          extract($rsdata[0]);
          $nacion = up($nacionalidad); // 1 caracter
          $cedula = mascara($cedula_identidad,8); // 8 caracteres
          
          $cuenta = $cuenta_bancaria; // 20 caracteres

          $neto_cobrar_1 = $asignaciones - $deducciones;
          $neto_cobrar_1_if = $neto_cobrar_1;
          $neto_aux = explode('.',$neto_cobrar_1);

          $nombre_completo = str_replace('  ',' ',trim($nombre_completo));
          $nombre_completo = str_replace("\t",' ',$nombre_completo);
          $nombre_completo = str_replace("Ñ",'@',$nombre_completo);
          $nombre_completo = elimina_acentos($nombre_completo);

          $nombre_completo = str_replace("\“",'   @',$nombre_completo);
          $nombre_completo=substr($nombre_completo,0,40);
          $nombre_completo= str_pad($nombre_completo, 40," ",STR_PAD_RIGHT); // 40 caracteres maximo

          if(isset($neto_aux[1])){
            $decimal=$neto_aux[1];
          }else{
            $decimal='00';
          }
          $decimal= str_pad($decimal, 2 , "0", STR_PAD_RIGHT);
          $neto_cobrar_1 =  mascara($neto_aux[0].$decimal,11); // 11 caracteres => 9 enteros - 2 decimales

          if($neto_cobrar_1!='00000000000'){
            //FORMATO
            // nacionalidad (V-E) - cedula (8) - cuenta (20) - monto (11 - 9 enteros 2 decimales) - nombre completo (40)
            $filas_archivo_aux .= $nacion.$cedula.$cuenta.$neto_cobrar_1.$nombre_completo."\r\n";
            $c_registros++;
            $c_montos += $neto_cobrar_1_if;
          }
        }//fin foreach data3

        $c_registros=mascara($c_registros,7);// 7 caracteres
        $c_montos_aux = explode('.',$c_montos);
        if(isset($c_montos_aux[1])){
          $decimal2=$c_montos_aux[1];
        }else{
          $decimal2=0;
        }
        $decimal2= str_pad($decimal2, 2 , "0", STR_PAD_RIGHT).'';

        $c_montos =  mascara($c_montos_aux[0].$decimal2,15); // 15 => 13 enteros - 2 decimales

        
        //FORMATO
        //ONTNOM - rif (10) - cantidad registros (7 - 0 izquierda) - monto total (15 - 2 decimales, 0 izquierda) - VES - fehca (AAAAMMDD)
        //ONTNOM G200001550 0000050 000030477127450 VES 20210213
        //ONTNOM G200001550 0000001 000001458240000 VES 20210816
        $filas_archivo = 'ONTNOM'.$rif.$c_registros.$c_montos.'VES'.date('Ymd')."\r\n";

        $filas_archivo .= $filas_archivo_aux;
      }//fin foreach $data3agurpado

      $nombre_archivo = "NOM-".mascara($cod_tipo_nomina,2)."-"."VENEZUELA";
      $_SESSION["nombre_txt"]=$nombre_archivo.".txt";

      $this->wFile($nombre_archivo, $filas_archivo);
      $this->set('filas_archivo',$filas_archivo);

    }

  }// FIN DE TXT BANCARIOS
  $this->set('var',$var);
}

function denominacion_tiponomina_patria($var=null){
	$this->layout="ajax";
	if($var=='no' || $var==''){
		$this->set('cod_nomina','');
		$this->set('deno_nomina','');
		$this->set('mensajeError','Lo siento, no se encontrar&oacute;n registros');
	}else{
		$condicion = $this->SQLCA()." and cod_tipo_nomina='$var'";
		$datos=$this->Cnmd01->findAll($condicion, 'cod_tipo_nomina, denominacion', 'cod_tipo_nomina ASC');
		if($datos!=null){
			$this->set('cod_nomina',$this->AddCeroR2($datos[0]['Cnmd01']['cod_tipo_nomina']));
			$this->set('deno_nomina',$datos[0]['Cnmd01']['denominacion']);
		}else{
			$this->set('cod_nomina','');
			$this->set('deno_nomina','');
		}
	}
}

function lista_bancos_ordenamiento_patria($cod_tipo_nomina=null) {
   $this->layout="ajax";
   if(isset($cod_tipo_nomina) && $cod_tipo_nomina!=null){
      $this->set('cod_tipo_nomina',$cod_tipo_nomina);
   }
}

function lista_bancos_patria($cod_tipo_nomina=null,$tipo_ordenamiento=null) {
   $this->layout="ajax";
   if(isset($cod_tipo_nomina) && $cod_tipo_nomina!=null){
   	  $sql ="SELECT a.cod_entidad_bancaria,x.denominacion,count(a.cod_entidad_bancaria) FROM cnmd06_fichas a,cstd01_entidades_bancarias x WHERE ".$this->SQLCA()." and a.cod_tipo_nomina=$cod_tipo_nomina and x.cod_entidad_bancaria=a.cod_entidad_bancaria AND condicion_actividad=1 GROUP BY a.cod_entidad_bancaria,x.denominacion ORDER BY x.denominacion ASC";
      $this->set('bancos',$this->cnmd06_fichas->execute($sql));
      $this->set('cod_tipo_nomina',$cod_tipo_nomina);
      $this->set('tipo_ordenamiento',$tipo_ordenamiento);
   }
}


function fondo_terceros_patria($var=null,$cod_tipo_nomina=null,$cod_aporte=null,$tipo_ordenamiento=1){
  $this->layout="ajax";
  $nacion=0;
  $nacionalidad=0;
  $cedula=0;
  $cedula_identidad=0;
  $cuenta_bancaria =0;
  $asignaciones=0;
  $deducciones =0;
  $neto_cobrar_1_if =0;
  $codigo_empresa=0;
  if($var=='no'){
    $condicion = $this->SQLCA()." and status_nomina in (4)";
    $lista = $this->Cnmd01->generateListTxt($condicion, 'cod_tipo_nomina ASC', null, '{n}.Cnmd01.cod_tipo_nomina', '{n}.Cnmd01.denominacion');
    if($lista!=null){
      $this->concatenaN($lista, 'lista');
    }else{
      $this->set('lista',array('no'=>'no hay registros'));
    }
    $this->set('var',$var);
  }elseif($var=='txt'){
    $this->layout="txt";
    $cod_presi     = $this->Session->read('SScodpresi');
    $cod_entidad   = $this->Session->read('SScodentidad');
    $cod_tipo_inst = $this->Session->read('SScodtipoinst');
    $cod_inst      = $this->Session->read('SScodinst');
    $cod_dep       = $this->Session->read('SScoddep');
    $this->data['reporte_personal']['select_tiponomina'];
    $data3agurpado=$this->Cnmd01->execute("SELECT cod_entidad_bancaria FROM v_cnmd06_fichas_asig_ded WHERE ".$this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina." and ");
    $data3FechaNomina=$this->Cnmd01->execute("SELECT periodo_hasta,periodo_desde FROM cnmd01 WHERE ".$this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina."");
    $tipoDependencia=$this->Cnmd01->execute("SELECT tipo_dependencia FROM cugd02_dependencias WHERE cod_tipo_institucion=$cod_tipo_inst and cod_institucion=$cod_inst and cod_dependencia=$cod_dep");
      //$tipo_dependencia = $tipoDependencia[0][0]['tipo_dependencia'];
    $tipo_dependencia = $cod_dep;
    $fecha_nomina = date('Y-d-m');//$data3FechaNomina[0][0]['periodo_hasta'];
    $fecha_nomina = cambiar_formato_fecha($fecha_nomina);
    $fecha_nomina = str_replace("/","",$fecha_nomina);
    $fecha_nomina2 = $data3FechaNomina[0][0]['periodo_hasta'];
    $fecha_nomina2_desde = $data3FechaNomina[0][0]['periodo_desde'];
    $fecha_nomina2_array = explode('-',$data3FechaNomina[0][0]['periodo_hasta']);
    $fecha_nomina2_array_desde = explode('-',$data3FechaNomina[0][0]['periodo_desde']);
    $fecha_nomina2 = cambiar_formato_fecha($fecha_nomina2);
    $fecha_nomina2 = str_replace("/","",$fecha_nomina2);//AAAAMMDD
    $fecha_nomina3 = substr($fecha_nomina2_array[0],2,2).$fecha_nomina2_array[1].$fecha_nomina2_array[2];///AAMMDD    año mes dia
    $fecha_nomina4 = $fecha_nomina2_array[2].$fecha_nomina2_array[1].substr($fecha_nomina2_array[0],2,2);///DDMMAA    dia mes año
    $fecha_nomina4BOD = $fecha_nomina2_array[0].$fecha_nomina2_array[1].$fecha_nomina2_array[2];///AAAAMMDD    año mes dia
    $fecha_nomina4BOD_nuevo_formato = 'DE'.$fecha_nomina2_array_desde[2].$fecha_nomina2_array_desde[1].substr($fecha_nomina2_array_desde[0],-2).'A'.$fecha_nomina2_array[2].$fecha_nomina2_array[1].substr($fecha_nomina2_array[0],-2);///DE DDMMAA A DDMMAA

    $nombre_archivo = 'Nomina_'.mascara($cod_dep,4).'_'.mascara($cod_tipo_nomina,3).'_'.date('d_m_Y_h:i:sa').'';
    $_SESSION["nombre_txt"]=$nombre_archivo.".txt";


    if($_SESSION['SScodpresi']==1 && $_SESSION['SScodentidad']==12 && $_SESSION['SScodtipoinst']==30 && $_SESSION['SScodinst']==12){// GOBERNACION DEL ESTADO GUARICO

    	//$cod_sucursal_agrupada = $rsagrupado[0]['cod_sucursal'];
        $data3CuentaBancariaCancela=$this->Cnmd01->execute("SELECT cuenta_bancaria as cuenta_bancaria_cancela,rif FROM cnmd09_bancos_cancelan_nominas WHERE ".$this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina." ORDER BY cod_sucursal ASC LIMIT 1");
            
        $rif = $data3CuentaBancariaCancela[0][0]['rif'];
        $rif = str_replace("-","",$rif);
        $rif = substr($rif,0,1)."".mascara(substr($rif,1),9);//DEJAR TODO PEGADO 10 caracteres
        // $rif = str_pad($rif, 15 , " ", STR_PAD_RIGHT).'';

        $data3=$this->Cnmd01->execute("SELECT * FROM v_cnmd07_transacciones_actuales WHERE ".$this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina." and cod_tipo_transaccion=2 and cod_transaccion=".$cod_aporte." and condicion_actividad=2 and (( SELECT count(*) AS count
           FROM cnmd06_fichas zz
          WHERE zz.cod_dep = v_cnmd07_transacciones_actuales.cod_dep AND zz.cod_tipo_nomina = v_cnmd07_transacciones_actuales.cod_tipo_nomina AND zz.cod_cargo = v_cnmd07_transacciones_actuales.cod_cargo AND zz.cod_ficha = v_cnmd07_transacciones_actuales.cod_ficha AND zz.condicion_actividad = 1)) <> 0 ORDER BY cedula_identidad ASC");
        $this->set('data3',$data3);
        $filas_archivo_aux="";
        $c_registros = 0;
        $c_montos = 0;

        foreach($data3 as $rsdata){
          extract($rsdata[0]);
          $nacion = up($nacionalidad); // 1 caracter
          $cedula = mascara($cedula_identidad,8); // 8 caracteres
          

          $neto_aux = explode('.',$monto_cuota);

          if(isset($neto_aux[1])){
            $decimal=$neto_aux[1];
          }else{
            $decimal='00';
          }
          $decimal= str_pad($decimal, 2 , "0", STR_PAD_RIGHT);
          $neto_cobrar_1 =  mascara($neto_aux[0].$decimal,11); // 11 caracteres => 9 enteros - 2 decimales

          if($neto_cobrar_1!='00000000000'){
            //FORMATO CUERPO
            // nacionalidad (V-E) - cedula (8 - 0 izquierda) - monto (11 - 9 enteros 2 decimales)
            $filas_archivo_aux .= $nacion.$cedula.$neto_cobrar_1."\r\n";
            $c_registros++;
            $c_montos += $monto_cuota;
          }
        
        }//fin foreach data3

        

        $c_registros=mascara($c_registros,7);// 7 caracteres
        $c_montos_aux = explode('.',$c_montos);
        if(isset($c_montos_aux[1])){
          $decimal2=$c_montos_aux[1];
        }else{
          $decimal2=0;
        }
        $decimal2= str_pad($decimal2, 2 , "0", STR_PAD_RIGHT).'';

        $c_montos =  mascara($c_montos_aux[0].$decimal2,15); // 15 => 13 enteros - 2 decimales

        

        //FORMATO CABECERA
        // ONT401 - rif(10) - cantidad registros (7 - 0 izquierda) - monto total (15 - 13 enteros 2 decimales, 0 izquierda) - VES
        $filas_archivo = 'ONT401'.$rif.$c_registros.$c_montos."VES\r\n";

        $filas_archivo .= $filas_archivo_aux;

			
      $nombre_archivo = "APORTE_PATRONAL-".mascara($cod_tipo_nomina,2)."-".$cod_aporte."-GUARICO";
      $_SESSION["nombre_txt"]=$nombre_archivo.".txt";

      $this->wFile($nombre_archivo, $filas_archivo);
      $this->set('filas_archivo',$filas_archivo);

    }

  }// FIN DE TXT BANCARIOS
  $this->set('var',$var);
}

function denominacion_tiponomina_fondos_terceros_patria($var=null){
	$this->layout="ajax";
	if($var=='no' || $var==''){
		$this->set('cod_nomina','');
		$this->set('deno_nomina','');
		$this->set('mensajeError','Lo siento, no se encontrar&oacute;n registros');
	}else{
		$condicion = $this->SQLCA()." and cod_tipo_nomina='$var'";
		$datos=$this->Cnmd01->findAll($condicion, 'cod_tipo_nomina, denominacion', 'cod_tipo_nomina ASC');
		if($datos!=null){
			$this->set('cod_nomina',$this->AddCeroR2($datos[0]['Cnmd01']['cod_tipo_nomina']));
			$this->set('deno_nomina',$datos[0]['Cnmd01']['denominacion']);
		}else{
			$this->set('cod_nomina','');
			$this->set('deno_nomina','');
		}
	}
}

function lista_bancos_ordenamiento_fondo_terceros_patria($cod_tipo_nomina=null) {
   $this->layout="ajax";
   if(isset($cod_tipo_nomina) && $cod_tipo_nomina!=null){
      $this->set('cod_tipo_nomina',$cod_tipo_nomina);
   }
}

function lista_bancos_fondos_terceros_patria($cod_tipo_nomina=null,$tipo_ordenamiento=null) {
   $this->layout="ajax";
   if(isset($cod_tipo_nomina) && $cod_tipo_nomina!=null){
   	  $sql ="SELECT a.cod_entidad_bancaria,x.denominacion,count(a.cod_entidad_bancaria) FROM cnmd06_fichas a,cstd01_entidades_bancarias x WHERE ".$this->SQLCA()." and a.cod_tipo_nomina=$cod_tipo_nomina and x.cod_entidad_bancaria=a.cod_entidad_bancaria AND condicion_actividad=1 GROUP BY a.cod_entidad_bancaria,x.denominacion ORDER BY x.denominacion ASC";
      $this->set('bancos',$this->cnmd06_fichas->execute($sql));
      $this->set('cod_tipo_nomina',$cod_tipo_nomina);
      $this->set('tipo_ordenamiento',$tipo_ordenamiento);
   }
}

////FIN PORTAL PATRIA



function depositos_bancarios($var=null,$cod_tipo_nomina=null,$cod_entidad_bancaria=null,$tipo_ordenamiento=1){
	$this->layout="ajax";
	$nacion=0;
	$nacionalidad=0;
	$cedula=0;
	$cedula_identidad=0;
	$cuenta_bancaria =0;
	$asignaciones=0;
	$deducciones =0;
	$neto_cobrar_1_if =0;
	$codigo_empresa=0;
	if($var=='no'){
		$condicion = $this->SQLCA()." and status_nomina in (4)";
		$lista = $this->Cnmd01->generateListTxt($condicion, 'cod_tipo_nomina ASC', null, '{n}.Cnmd01.cod_tipo_nomina', '{n}.Cnmd01.denominacion');
		if($lista!=null){
			$this->concatenaN($lista, 'lista');
		}else{
			$this->set('lista',array('no'=>'no hay registros'));
		}
    $this->set('var',$var);
	}elseif($var=='si'){
		$this->layout="pdf";
		$this->data['reporte_personal']['select_tiponomina'];
        $this->set('banco',$this->Cnmd01->execute("SELECT denominacion FROM cstd01_entidades_bancarias WHERE cod_entidad_bancaria=$cod_entidad_bancaria"));
        $this->set('data1',$this->Cnmd01->findAll($this->SQLCA()." and cod_tipo_nomina=$cod_tipo_nomina"),'denominacion,correspondiente,periodo_desde,periodo_hasta,numero_nomina');
		$this->set('codip_banco',$cod_entidad_bancaria);

        if($tipo_ordenamiento==2){
			$this->set('data3grupo',$this->Cnmd01->execute("SELECT cod_estado, cod_municipio, deno_cod_estado, deno_cod_municipio FROM v_cnmd06_fichas_asig_ded2 WHERE ".$this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina." and cod_entidad_bancaria=$cod_entidad_bancaria GROUP BY cod_estado, cod_municipio, deno_cod_estado, deno_cod_municipio ORDER BY cod_estado,cod_municipio ASC"));
			$this->set('data3',$this->Cnmd01->execute("SELECT * FROM v_cnmd06_fichas_asig_ded2 WHERE ".$this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina." and cod_entidad_bancaria=$cod_entidad_bancaria ORDER BY cod_estado,cod_municipio,cedula_identidad ASC"));
            $this->set('var',$var);
            $this->render('depositos_bancarios_geografico');
        }else{
        	$this->set('data3',$this->Cnmd01->execute("SELECT * FROM v_cnmd06_fichas_asig_ded WHERE ".$this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina." and cod_entidad_bancaria=$cod_entidad_bancaria ORDER BY cedula_identidad ASC"));
            $this->set('var',$var);
            $this->render('depositos_bancarios');
        }
	}elseif($var=='txt'){
		$this->layout="txt";
		$cod_presi     = $this->Session->read('SScodpresi');
		$cod_entidad   = $this->Session->read('SScodentidad');
		$cod_tipo_inst = $this->Session->read('SScodtipoinst');
		$cod_inst      = $this->Session->read('SScodinst');
		$cod_dep       = $this->Session->read('SScoddep');
		$this->data['reporte_personal']['select_tiponomina'];
	    $data3agurpado=$this->Cnmd01->execute("SELECT cod_entidad_bancaria FROM v_cnmd06_fichas_asig_ded WHERE ".$this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina." and cod_entidad_bancaria=$cod_entidad_bancaria GROUP BY cod_entidad_bancaria ORDER BY cod_entidad_bancaria ASC");
	    $data3FechaNomina=$this->Cnmd01->execute("SELECT periodo_hasta,periodo_desde FROM cnmd01 WHERE ".$this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina."");
	    $tipoDependencia=$this->Cnmd01->execute("SELECT tipo_dependencia FROM cugd02_dependencias WHERE cod_tipo_institucion=$cod_tipo_inst and cod_institucion=$cod_inst and cod_dependencia=$cod_dep");
      //$tipo_dependencia = $tipoDependencia[0][0]['tipo_dependencia'];
        $tipo_dependencia = $cod_dep;
        $fecha_nomina = date('Y-d-m');//$data3FechaNomina[0][0]['periodo_hasta'];
        $fecha_nomina = cambiar_formato_fecha($fecha_nomina);
        $fecha_nomina = str_replace("/","",$fecha_nomina);
        $fecha_nomina2 = $data3FechaNomina[0][0]['periodo_hasta'];
        $fecha_nomina2_desde = $data3FechaNomina[0][0]['periodo_desde'];
        $fecha_nomina2_array = explode('-',$data3FechaNomina[0][0]['periodo_hasta']);
        $fecha_nomina2_array_desde = explode('-',$data3FechaNomina[0][0]['periodo_desde']);
        $fecha_nomina2 = cambiar_formato_fecha($fecha_nomina2);
        $fecha_nomina2 = str_replace("/","",$fecha_nomina2);//AAAAMMDD
        $fecha_nomina3 = substr($fecha_nomina2_array[0],2,2).$fecha_nomina2_array[1].$fecha_nomina2_array[2];///AAMMDD    año mes dia
        $fecha_nomina4 = $fecha_nomina2_array[2].$fecha_nomina2_array[1].substr($fecha_nomina2_array[0],2,2);///DDMMAA    dia mes año
        $fecha_nomina4BOD = $fecha_nomina2_array[0].$fecha_nomina2_array[1].$fecha_nomina2_array[2];///AAAAMMDD    año mes dia
        $fecha_nomina4BOD_nuevo_formato = 'DE'.$fecha_nomina2_array_desde[2].$fecha_nomina2_array_desde[1].substr($fecha_nomina2_array_desde[0],-2).'A'.$fecha_nomina2_array[2].$fecha_nomina2_array[1].substr($fecha_nomina2_array[0],-2);///DE DDMMAA A DDMMAA

	    $nombre_archivo = 'Nomina_'.mascara($cod_dep,4).'_'.mascara($cod_tipo_nomina,3).'_'.date('d_m_Y_h:i:sa').'';
	    $_SESSION["nombre_txt"]=$nombre_archivo.".txt";


	if($_SESSION['SScodpresi']==1 && $_SESSION['SScodentidad']==12 && $_SESSION['SScodtipoinst']==30 && $_SESSION['SScodinst']==12){// GOBERNACION DEL ESTADO GUARICO


		// BANCO BICENTENARIO
		if($cod_entidad_bancaria==175 ){// BANCO BICENTENARIO y BANOPR
			foreach($data3agurpado as $rsagrupado){
				//$cod_sucursal_agrupada = $rsagrupado[0]['cod_sucursal'];
				$data3CuentaBancariaCancela=$this->Cnmd01->execute("SELECT cuenta_bancaria as cuenta_bancaria_cancela FROM cnmd09_bancos_cancelan_nominas WHERE ".$this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina." and cod_entidad_bancaria=$cod_entidad_bancaria  ORDER BY cod_sucursal ASC LIMIT 1");

				$data3=$this->Cnmd01->execute("SELECT * FROM v_cnmd06_fichas_asig_ded WHERE ".$this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina." and cod_entidad_bancaria=$cod_entidad_bancaria ORDER BY cedula_identidad ASC");
				$this->set('data3',$data3);
				$filas_archivo_aux="";
				$c_registros = 0;
				$c_montos = 0;

				foreach($data3 as $rsdata){
					extract($rsdata[0]);
					$codigo_empresa = '0000';//Asignar Codigo Empresa
					// AGREGAR AQUI EN EN LA SECCION DEL BANCO DEL PUEBLO SOBERANO
					// buscar por SECCION DEL BANCO DEL PUEBLO SOBERANO
					if($tipo_dependencia==1){$codigo_empresa = '0732';} //PARA ADMINISTRACIÓN CENTRAL
					if($tipo_dependencia==1000){$codigo_empresa = '8731';} // FUNDACULGUA
					if($tipo_dependencia==1001){$codigo_empresa = '0073';} // FUSAMIG
					if($tipo_dependencia==1003){$codigo_empresa = '0827';} // I.R.D.E.G
					if($tipo_dependencia==1005){$codigo_empresa = '3037';} // INCITEG
					if($tipo_dependencia==1012){$codigo_empresa = '8739';} // INJUVEG
					if($tipo_dependencia==1019){$codigo_empresa = '8845';} // IMUGUA
					if($tipo_dependencia==1022){$codigo_empresa = '1231';} // SIBCI
					if($tipo_dependencia==1023){$codigo_empresa = '0477';} // IPMEBG
					if($tipo_dependencia==1025){$codigo_empresa = '8745';} // CEFOPOL
					if($tipo_dependencia==1038){$codigo_empresa = '6884';} // 

					$cedula = mascara($cedula_identidad,10);
					$cuenta = $cuenta_bancaria;
					$neto_cobrar_1 = $asignaciones - $deducciones;
					$neto_cobrar_1_if = $neto_cobrar_1;
					$neto_aux = explode('.',$neto_cobrar_1);
		
					if(isset($neto_aux[1])){
						$decimal=$neto_aux[1];
					}else{
						$decimal=0;
					}
					$decimal= str_pad($decimal, 2 , "0", STR_PAD_RIGHT).'';
					$neto_cobrar_1 =  mascara($neto_aux[0],10).''.$decimal;
					$neto_cobrar_2 = $neto_cobrar_1;

					if($neto_cobrar_2!= '000000000000'){
						$filas_archivo_aux .= $codigo_empresa.$neto_cobrar_2.$cuenta.$cedula."00000000"."\r\n";
						$c_registros++;
						$c_montos += $neto_cobrar_1_if;
						//$filas_archivo .= $cedula."\n";
					}

				}//fin foreach data3

				$c_registros=mascara($c_registros,4);
				$c_montos_aux = explode('.',$c_montos);
				
				if(isset($c_montos_aux[1])){
					$decimal2=$c_montos_aux[1];
				}else{
					$decimal2=0;
				}

				//ENCABEZADO
				$decimal2= str_pad($decimal2, 2 , "0", STR_PAD_RIGHT).'';
				$c_montos =  mascara($c_montos_aux[0],15).''.$decimal2;
				$data3CuentaBancariaCancela[0][0]['cuenta_bancaria_cancela'] = isset($data3CuentaBancariaCancela[0][0]['cuenta_bancaria_cancela'])?$data3CuentaBancariaCancela[0][0]['cuenta_bancaria_cancela']:str_pad('NO EXISTE CUENTA', 20 , "0", STR_PAD_RIGHT);
				//$filas_archivo .= $data3CuentaBancariaCancela[0][0]['cuenta_bancaria_cancela'].$fecha_nomina.$c_montos.$c_registros."\n";
				$filas_archivo .= $data3CuentaBancariaCancela[0][0]['cuenta_bancaria_cancela'].date('Ymd').$c_montos.$c_registros."\r\n";
				$filas_archivo .= $filas_archivo_aux;

			}//fin foreach $data3agurpado
			
			$nombre_archivo = "NOM-".mascara($cod_tipo_nomina,2)."-"."BICENTENARIO";

			$_SESSION["nombre_txt"]=$nombre_archivo.".txt";

			$this->wFile($nombre_archivo, $filas_archivo);
			$this->set('filas_archivo',$filas_archivo);

		}else if($cod_entidad_bancaria==7 ){//  BANFONADES
			foreach($data3agurpado as $rsagrupado){
				//$cod_sucursal_agrupada = $rsagrupado[0]['cod_sucursal'];
				$data3CuentaBancariaCancela=$this->Cnmd01->execute("SELECT cuenta_bancaria as cuenta_bancaria_cancela FROM cnmd09_bancos_cancelan_nominas WHERE ".$this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina." and cod_entidad_bancaria=175  ORDER BY cod_sucursal ASC LIMIT 1");

				$data3=$this->Cnmd01->execute("SELECT * FROM v_cnmd06_fichas_asig_ded WHERE ".$this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina." and cod_entidad_bancaria=$cod_entidad_bancaria ORDER BY cedula_identidad ASC");
				$this->set('data3',$data3);
				$filas_archivo_aux="";
				$c_registros = 0;
				$c_montos = 0;
				
				foreach($data3 as $rsdata){
					extract($rsdata[0]);
					$codigo_empresa = '0000';//Asignar Codigo Empresa
					if($tipo_dependencia==1){$codigo_empresa = '0732';} //PARA ADMINISTRACION CENTRAL
					if($tipo_dependencia==1005){$codigo_empresa = '3037';}
					if($tipo_dependencia==1023){$codigo_empresa = '0477';}

					$cedula = mascara($cedula_identidad,10);
					$cuenta = $cuenta_bancaria;
					$neto_cobrar_1 = $asignaciones - $deducciones;
					$neto_cobrar_1_if = $neto_cobrar_1;
					$neto_aux = explode('.',$neto_cobrar_1);

					if(isset($neto_aux[1])){
					$decimal=$neto_aux[1];
					}else{
						$decimal=0;
					}
				
					$decimal= str_pad($decimal, 2 , "0", STR_PAD_RIGHT).'';
					$neto_cobrar_1 =  mascara($neto_aux[0],10).''.$decimal;
					$neto_cobrar_2 = $neto_cobrar_1;

					if($neto_cobrar_2!= '000000000000'){
						$filas_archivo_aux .= $codigo_empresa.$neto_cobrar_2.$cuenta.$cedula."00000000"."\r\n";
						$c_registros++;
						$c_montos += $neto_cobrar_1_if;
						//$filas_archivo .= $cedula."\n";
					}

				}//fin foreach data3

				$c_registros=mascara($c_registros,4);
				$c_montos_aux = explode('.',$c_montos);
				
				if(isset($c_montos_aux[1])){
					$decimal2=$c_montos_aux[1];
				}else{
					$decimal2=0;
				}

				//ENCABEZADO
				$decimal2= str_pad($decimal2, 2 , "0", STR_PAD_RIGHT).'';
				$c_montos =  mascara($c_montos_aux[0],15).''.$decimal2;
				$data3CuentaBancariaCancela[0][0]['cuenta_bancaria_cancela'] = isset($data3CuentaBancariaCancela[0][0]['cuenta_bancaria_cancela'])?$data3CuentaBancariaCancela[0][0]['cuenta_bancaria_cancela']:str_pad('NO EXISTE CUENTA', 20 , "0", STR_PAD_RIGHT);
				//$filas_archivo .= $data3CuentaBancariaCancela[0][0]['cuenta_bancaria_cancela'].$fecha_nomina.$c_montos.$c_registros."\n";
				$filas_archivo .= $data3CuentaBancariaCancela[0][0]['cuenta_bancaria_cancela'].date('Ymd').$c_montos.$c_registros."\r\n";
				$filas_archivo .= $filas_archivo_aux;

			}//fin foreach $data3agurpado
			$nombre_archivo = "NOM-".mascara($cod_tipo_nomina,2)."-"."BANFOANDES";
			$_SESSION["nombre_txt"]=$nombre_archivo.".txt";
			$this->wFile($nombre_archivo, $filas_archivo);
			$this->set('filas_archivo',$filas_archivo);

		}else if($cod_entidad_bancaria==147 ){//  BANCO BANORTE
    	foreach($data3agurpado as $rsagrupado){
		        	//$cod_sucursal_agrupada = $rsagrupado[0]['cod_sucursal'];
		            $data3CuentaBancariaCancela=$this->Cnmd01->execute("SELECT cuenta_bancaria as cuenta_bancaria_cancela FROM cnmd09_bancos_cancelan_nominas WHERE ".$this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina." and cod_entidad_bancaria=175  ORDER BY cod_sucursal ASC LIMIT 1");

		        	$data3=$this->Cnmd01->execute("SELECT * FROM v_cnmd06_fichas_asig_ded WHERE ".$this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina." and cod_entidad_bancaria=$cod_entidad_bancaria ORDER BY cedula_identidad ASC");
			        $this->set('data3',$data3);
			        $filas_archivo_aux="";
			        $c_registros = 0;
			        $c_montos = 0;
					foreach($data3 as $rsdata){
						extract($rsdata[0]);
						$codigo_empresa = '0000';//Asignar Codigo Empresa
						    if($tipo_dependencia==1){$codigo_empresa = '0732';} //PARA ADMINISTRACION CENTRAL
						    if($tipo_dependencia==1005){$codigo_empresa = '3037';}
							if($tipo_dependencia==1023){$codigo_empresa = '0477';}
			                $cedula = mascara($cedula_identidad,10);
							$cuenta = $cuenta_bancaria;
							$neto_cobrar_1 = $asignaciones - $deducciones;
							$neto_cobrar_1_if = $neto_cobrar_1;
							$neto_aux = explode('.',$neto_cobrar_1);
							if(isset($neto_aux[1])){
								$decimal=$neto_aux[1];
							}else{$decimal=0;}
							$decimal= str_pad($decimal, 2 , "0", STR_PAD_RIGHT).'';
							$neto_cobrar_1 =  mascara($neto_aux[0],10).''.$decimal;
							$neto_cobrar_2 = $neto_cobrar_1;
							if($neto_cobrar_2!= '000000000000'){
				                $filas_archivo_aux .= $codigo_empresa.$neto_cobrar_2.$cuenta.$cedula."00000000"."\r\n";
				                $c_registros++;
				                $c_montos += $neto_cobrar_1_if;
				                //$filas_archivo .= $cedula."\n";
							}

					}//fin foreach data3

		            	$c_registros=mascara($c_registros,4);
				        $c_montos_aux = explode('.',$c_montos);
						if(isset($c_montos_aux[1])){
							$decimal2=$c_montos_aux[1];
						}else{
							$decimal2=0;
						}

						//ENCABEZADO
				        $decimal2= str_pad($decimal2, 2 , "0", STR_PAD_RIGHT).'';
						$c_montos =  mascara($c_montos_aux[0],15).''.$decimal2;
						$data3CuentaBancariaCancela[0][0]['cuenta_bancaria_cancela'] = isset($data3CuentaBancariaCancela[0][0]['cuenta_bancaria_cancela'])?$data3CuentaBancariaCancela[0][0]['cuenta_bancaria_cancela']:str_pad('NO EXISTE CUENTA', 20 , "0", STR_PAD_RIGHT);
					//$filas_archivo .= $data3CuentaBancariaCancela[0][0]['cuenta_bancaria_cancela'].$fecha_nomina.$c_montos.$c_registros."\n";
		            	$filas_archivo .= $data3CuentaBancariaCancela[0][0]['cuenta_bancaria_cancela'].date('Ymd').$c_montos.$c_registros."\r\n";
		                $filas_archivo .= $filas_archivo_aux;

				}//fin foreach $data3agurpado
				$nombre_archivo = "NOM-".mascara($cod_tipo_nomina,2)."-"."BANORTE";

				$_SESSION["nombre_txt"]=$nombre_archivo.".txt";
					$this->wFile($nombre_archivo, $filas_archivo);
					$this->set('filas_archivo',$filas_archivo);


		}else if($cod_entidad_bancaria==102){// BANCO DE VENEZUELA
			foreach($data3agurpado as $rsagrupado){
		        	//$cod_sucursal_agrupada = $rsagrupado[0]['cod_sucursal'];
		    		$data3CuentaBancariaCancela=$this->Cnmd01->execute("SELECT cuenta_bancaria as cuenta_bancaria_cancela,beneficiario FROM cnmd09_bancos_cancelan_nominas WHERE ".$this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina." and cod_entidad_bancaria=$cod_entidad_bancaria  ORDER BY cod_sucursal ASC LIMIT 1");

                    $beneficiario = $data3CuentaBancariaCancela[0][0]['beneficiario'];
                    $beneficiario = str_pad($beneficiario, 40 , " ", STR_PAD_RIGHT).'';


                    $rif = $data3CuentaBancariaCancela[0][0]['rif'];
					$rif = str_replace("-","",$rif);
                    $rif = substr($rif,0,1)."".mascara(substr($rif,1),9);
                    $rif = str_pad($rif, 15 , " ", STR_PAD_RIGHT).'';


		        	$data3=$this->Cnmd01->execute("SELECT * FROM v_cnmd06_fichas_asig_ded WHERE ".$this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina." and cod_entidad_bancaria=$cod_entidad_bancaria ORDER BY cedula_identidad ASC");
			        $this->set('data3',$data3);
			        $filas_archivo_aux="";
			        $c_registros = 0;
			        $c_montos = 0;
                                $codigo_empresa = '03291';//Asignar Codigo Empresa

					foreach($data3 as $rsdata){
						extract($rsdata[0]);
			                $cedula = mascara($cedula_identidad,9);
			                $cedula = str_pad($cedula, 10 , "0", STR_PAD_LEFT).'';
							$cuenta = $cuenta_bancaria;
							$neto_cobrar_1 = $asignaciones - $deducciones;
							$neto_cobrar_1_if = $neto_cobrar_1;
							$neto_aux = explode('.',$neto_cobrar_1);

							$nombre_completo = str_replace('  ',' ',trim($nombre_completo));
							$nombre_completo = str_replace("\t",' ',$nombre_completo);
							$nombre_completo = str_replace("Ñ",'@',$nombre_completo);
							$nombre_completo = elimina_acentos($nombre_completo);

							$nombre_completo = str_replace("\“",'   @',$nombre_completo);
							$nombre_completo=substr($nombre_completo,0,40);

							if(isset($neto_aux[1])){
								$decimal=$neto_aux[1];
							}else{
								$decimal='00';
							}
							$decimal= str_pad($decimal, 2 , "0", STR_PAD_RIGHT);
							$neto_cobrar_1 =  mascara($neto_aux[0].$decimal,11);
							$neto_cobrar_2 = $neto_cobrar_1;

							if($neto_cobrar_2!='000000000000000'){
                               $nombre_completo= str_pad($nombre_completo, 40," ",STR_PAD_RIGHT);
                               $filas_archivo_aux .= '0'.$cuenta.$neto_cobrar_2."0770".elimina_acentos($nombre_completo).$cedula."003291"."  "."\r\n";
                               $c_registros++;
							   $c_montos += $neto_cobrar_1_if;
							}

					}//fin foreach data3

		            	$c_registros=mascara($c_registros,5);
				        $c_montos_aux = explode('.',$c_montos);
						if(isset($c_montos_aux[1])){
							$decimal2=$c_montos_aux[1];
						}else{
							$decimal2=0;
						}
					$decimal2= str_pad($decimal2, 2 , "0", STR_PAD_RIGHT).'';

	                $c_montos =  mascara($c_montos_aux[0].$decimal2,13);

					$data3CuentaBancariaCancela[0][0]['cuenta_bancaria_cancela'] = isset($data3CuentaBancariaCancela[0][0]['cuenta_bancaria_cancela'])?$data3CuentaBancariaCancela[0][0]['cuenta_bancaria_cancela']:str_pad('NO EXISTE CUENTA', 20 , "0", STR_PAD_RIGHT);

						$beneficiario = str_replace(",,,,,,,,,",'         ',$beneficiario);
						$beneficiario = str_replace(",,,,",'    ',$beneficiario);
						$beneficiario = str_replace(",,,",'   ',$beneficiario);
						$beneficiario = str_replace("...",'   ',$beneficiario);
						$beneficiario = str_replace(".",' ',$beneficiario);

						$beneficiario= str_pad(substr($beneficiario,0,40), 40," ",STR_PAD_RIGHT);

						$filas_archivo .= 'H'.$beneficiario.$data3CuentaBancariaCancela[0][0]['cuenta_bancaria_cancela']."01".(date('d/m/y')).$c_montos.'03291'." "."\r\n";

						$filas_archivo .= $filas_archivo_aux;



				}//fin foreach $data3agurpado

				$nombre_archivo = "NOM-".mascara($cod_tipo_nomina,2)."-"."VENEZUELA";
				$_SESSION["nombre_txt"]=$nombre_archivo.".txt";

					$this->wFile($nombre_archivo, $filas_archivo);
					$this->set('filas_archivo',$filas_archivo);

    }else if($cod_entidad_bancaria==116){// BANCO BOD
			foreach($data3agurpado as $rsagrupado){
		            $data3CuentaBancariaCancela=$this->Cnmd01->execute("SELECT cuenta_bancaria as cuenta_bancaria_cancela,rif FROM cnmd09_bancos_cancelan_nominas WHERE ".$this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina." and cod_entidad_bancaria=$cod_entidad_bancaria  ORDER BY cod_sucursal ASC LIMIT 1");
                    $rif = $data3CuentaBancariaCancela[0][0]['rif'];
                    $rif = str_replace("-","",$rif);
                    $xrif = substr($rif,1);
                    $x2rif = substr($xrif,-1);
                    $x3rif = substr($xrif,0,-1);
                    $rif = substr($rif,0,1)."".mascara($x3rif,12)."-".$x2rif;
                    $nombre_empresa = $_SESSION['entidad_federal'];
                    $nombre_empresa = str_replace('  ',' ',trim($nombre_empresa));
					$nombre_empresa = str_replace("\t",' ',$nombre_empresa);
					$nombre_empresa = str_replace("@",'Ñ',$nombre_empresa);
					$nombre_empresa = cortar_cadena_diskette(elimina_acentos($nombre_empresa), 30);
					$nombre_empresa = str_replace("@",'Ñ',$nombre_empresa);

					$encabezado=$this->Cnmd01->findAll($this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina);

					if($encabezado[0]['Cnmd01']['clasificacion_personal']==13){//BECAS
						$num_contrato="40040200001550002";
						$modalidad="EFE";
					}else if($encabezado[0]['Cnmd01']['clasificacion_personal']==14){//AYUDAS
						$num_contrato="40040200001550003";
						$modalidad="EFE";
					}else{
						$num_contrato="40040200001550001";// OTROS
	    				 $modalidad="CTA";
					}

					$num_nomina=$encabezado[0]['Cnmd01']['numero_nomina'];
					$fecha_envio=str_replace('-', '', $encabezado[0]['Cnmd01']['periodo_hasta']);
		        	$data3=$this->Cnmd01->execute("SELECT * FROM v_cnmd06_fichas_asig_ded WHERE ".$this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina." and cod_entidad_bancaria=$cod_entidad_bancaria ORDER BY cedula_identidad ASC");
			        $total = 0;
					$num_personas=0;

					foreach($data3 as $rsdata){
						extract($rsdata[0]);
							$total=  $total + ($asignaciones - $deducciones);
							if(($asignaciones - $deducciones)>0)$num_personas=$num_personas+1;
					}//fin foreach data3

							$neto_aux = explode('.',$total);
							if(isset($neto_aux[1])){
								$decimal=$neto_aux[1];
							}else{
								$decimal=0;
							}
							$decimal= str_pad($decimal, 2 , "0", STR_PAD_RIGHT).'';
							$total =  mascara($neto_aux[0],15).$decimal;

					$filtro='';

					for($i=1;$i<=158;$i++){
						$filtro.=' ';
					}

					$filtro2='';

					for($i=1;$i<=20;$i++){
						$filtro2.=' ';
					}

					$email='';

					for($i=1;$i<=40;$i++){
						$email.=' ';
					}

				    $filas_archivo_aux = "01NOMINA              G200001550".$num_contrato.mascara($num_nomina,9).$fecha_envio.mascara($num_personas,6).$total."VEB".$filtro."\r\n";
			        $this->set('data3',$data3);
			        $c_registros = 0;
			        $c_montos = 0;

					foreach($data3 as $rsdata){
						extract($rsdata[0]);
			            $nacion = up($nacionalidad);
			            $cedula = mascara($cedula_identidad,9);

						$nombre_completo = str_replace('  ',' ',trim($nombre_completo));
						$nombre_completo = str_replace("\t",' ',$nombre_completo);
						$nombre_completo = str_replace("Ñ",'@',$nombre_completo);
						$nombre = cortar_cadena_diskette(elimina_acentos($nombre_completo), 30);

						for($i=1;strlen($nombre)<=59;$i++){
							$nombre.=' ';
						}

							$neto_cobrar_1 = $asignaciones - $deducciones;
							$neto_cobrar_1_if = $neto_cobrar_1;
							$neto_aux = explode('.',$neto_cobrar_1);
							if(isset($neto_aux[1])){
								$decimal=$neto_aux[1];
							}else{
								$decimal=0;
							}
							$decimal= str_pad($decimal, 2 , "0", STR_PAD_RIGHT).'';
							$neto_cobrar_1 =  mascara($neto_aux[0],13).$decimal;
							$neto_cobrar_2 = $neto_cobrar_1;
							if($neto_cobrar_2!= '000000000000000'){
				                $filas_archivo_aux .= "02".$nacion.$cedula.$nombre."230213465QUINCENA                      ".$modalidad.mascara($cuenta_bancaria,20).mascara($cod_entidad_bancaria,4).$fecha_envio.mascara($neto_cobrar_1,15)."VEB"."000000000000000".$email."00000000000".$filtro2."\r\n";
				              //  $prueba=strlen("02".$nacion.$cedula.$nombre.mascara($num_nomina,9)."TIPO DE NOMINA".mascara($cod_tipo_nomina,3)." NUMERO".mascara($num_nomina,6).$modalidad.mascara($cuenta_bancaria,20).mascara($cod_entidad_bancaria,4).$fecha_envio.mascara($neto_cobrar_1,15)."VEB"."000000000000000".$email."00000000000".$filtro2);
				                $c_registros++;
				                $c_montos += $neto_cobrar_1_if;
							}






					}//fin foreach data3


				}//fin foreach $data3agurpado
				
				$filas_archivo .= $filas_archivo_aux;

				$nombre_archivo = "NOM-".mascara($cod_tipo_nomina,2)."-"."BOD";
				$_SESSION["nombre_txt"]=$nombre_archivo.".txt";
				$this->wFile($nombre_archivo, $filas_archivo);
				$this->set('filas_archivo',$filas_archivo);

	    }else if($cod_entidad_bancaria==134){// BANESCO


			$data3=$this->Cnmd01->execute("SELECT * FROM v_cnmd06_fichas_asig_ded WHERE ".$this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina." and cod_entidad_bancaria=$cod_entidad_bancaria ORDER BY cedula_identidad ASC");
			foreach($data3 as $rsdata){

				extract($rsdata[0]);
				$cedula = up($nacionalidad).mascara($cedula_identidad,8);
				$nombre_completo = str_replace('  ',' ',trim($nombre_completo));
				$nombre_completo = str_replace("\t",' ',$nombre_completo);
				$nombre_completo = str_replace("Ñ",'@',$nombre_completo);
				$nombre = cortar_cadena_diskette(elimina_acentos($nombre_completo), 30);
				$nombre = str_replace("Ñ",'N',$nombre);
			    //$cuenta = formato_cuenta_diskette($cuenta_bancaria);
				$cuenta = $cuenta_bancaria;
				$neto_cobrar_1 = $asignaciones - $deducciones;
				$neto_cobrar_1_if = $neto_cobrar_1;
				$neto_cobrar_1_if = (int) $neto_cobrar_1_if;
				$neto_aux = explode('.',$neto_cobrar_1);
				if(isset($neto_aux[1])){
					$decimal=$neto_aux[1];
				}else{
					$decimal=0;
				}
				$decimal= str_pad($decimal, 2 , "0", STR_PAD_RIGHT).'';
				$neto_cobrar_1 = $neto_aux[0].','.$decimal;
				//$neto_cobrar_2 = mascara($neto_cobrar_1,15);
				$neto_cobrar_2 = $neto_cobrar_1;
				if($neto_cobrar_1_if!=0){
	                $filas_archivo .= $neto_cobrar_2."\t".$cuenta."\t".$cedula."\t".$nombre."\r\n";
				}
			}

			$nombre_archivo = "NOM-".mascara($cod_tipo_nomina,2)."-"."BANESCO";
			$_SESSION["nombre_txt"]=$nombre_archivo.".txt";
			$this->wFile($nombre_archivo, $filas_archivo);
			$this->set('filas_archivo',$filas_archivo);

		}else if($cod_entidad_bancaria==149){//BANCO DEL PUEBLO SOBERANO
			foreach($data3agurpado as $rsagrupado){
		        	//$cod_sucursal_agrupada = $rsagrupado[0]['cod_sucursal'];
		            $data3CuentaBancariaCancela=$this->Cnmd01->execute("SELECT cuenta_bancaria as cuenta_bancaria_cancela FROM cnmd09_bancos_cancelan_nominas WHERE ".$this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina." and cod_entidad_bancaria=$cod_entidad_bancaria  ORDER BY cod_sucursal ASC LIMIT 1");

		        	$data3=$this->Cnmd01->execute("SELECT * FROM v_cnmd06_fichas_asig_ded WHERE ".$this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina." and cod_entidad_bancaria=$cod_entidad_bancaria ORDER BY cedula_identidad ASC");
			        $this->set('data3',$data3);
			        $filas_archivo_aux="";
			        $c_registros = 0;
			        $c_montos = 0;
					foreach($data3 as $rsdata){
						extract($rsdata[0]);
						// SECCION DEL BANCO DEL PUEBLO SOBERANO
						$codigo_empresa = '0000';//Asignar Codigo Empresa
						   if($tipo_dependencia==1){$codigo_empresa = '0732';} //PARA ADMINISTRACIÓN CENTRAL
						   if($tipo_dependencia==1000){$codigo_empresa = '8731';} // FUNDACULGUA
						   if($tipo_dependencia==1001){$codigo_empresa = '0073';} // FUSAMIG
						   if($tipo_dependencia==1003){$codigo_empresa = '0827';} // I.R.D.E.G
			         if($tipo_dependencia==1005){$codigo_empresa = '3037';} // INCITEG
			         if($tipo_dependencia==1012){$codigo_empresa = '8739';} // INJUVEG
			         if($tipo_dependencia==1019){$codigo_empresa = '8845';} // IMUGUA
				       if($tipo_dependencia==1022){$codigo_empresa = '1231';} // SIBCI
				       if($tipo_dependencia==1023){$codigo_empresa = '0477';} // IPMEBG
				       if($tipo_dependencia==1025){$codigo_empresa = '8745';} // CEFOPOL

			                $cedula = mascara($cedula_identidad,10);
							$cuenta = $cuenta_bancaria;
							$neto_cobrar_1 = $asignaciones - $deducciones;
							$neto_cobrar_1_if = $neto_cobrar_1;
							$neto_aux = explode('.',$neto_cobrar_1);
							if(isset($neto_aux[1])){
								$decimal=$neto_aux[1];
							}else{$decimal=0;}
							$decimal= str_pad($decimal, 2 , "0", STR_PAD_RIGHT).'';
							$neto_cobrar_1 =  mascara($neto_aux[0],10).''.$decimal;
							$neto_cobrar_2 = $neto_cobrar_1;
							if($neto_cobrar_2!= '000000000000'){
				                $filas_archivo_aux .= $codigo_empresa.$neto_cobrar_2.$cuenta.$cedula."00000000"."\r\n";
				                $c_registros++;
				                $c_montos += $neto_cobrar_1_if;
				                //$filas_archivo .= $cedula."\n";
							}

					}//fin foreach data3

		            	$c_registros=mascara($c_registros,4);
				        $c_montos_aux = explode('.',$c_montos);
						if(isset($c_montos_aux[1])){
							$decimal2=$c_montos_aux[1];
						}else{
							$decimal2=0;
						}

						//ENCABEZADO
						$filas_archivo = '';
				        $decimal2= str_pad($decimal2, 2 , "0", STR_PAD_RIGHT).'';
						$c_montos =  mascara($c_montos_aux[0],15).''.$decimal2;
						$data3CuentaBancariaCancela[0][0]['cuenta_bancaria_cancela'] = isset($data3CuentaBancariaCancela[0][0]['cuenta_bancaria_cancela'])?$data3CuentaBancariaCancela[0][0]['cuenta_bancaria_cancela']:str_pad('NO EXISTE CUENTA', 20 , "0", STR_PAD_RIGHT);
					//$filas_archivo .= $data3CuentaBancariaCancela[0][0]['cuenta_bancaria_cancela'].$fecha_nomina.$c_montos.$c_registros."\n";
		            $filas_archivo .= $data3CuentaBancariaCancela[0][0]['cuenta_bancaria_cancela'].date('Ymd').$c_montos.$c_registros."\r\n";
		            $filas_archivo .= $filas_archivo_aux;

				}//fin foreach $data3agurpado
				$nombre_archivo = "NOM-".mascara($cod_tipo_nomina,2)."-"."BICENTENARIO";

				$_SESSION["nombre_txt"]=$nombre_archivo.".txt";

				$this->wFile($nombre_archivo, $filas_archivo);
				$this->set('filas_archivo',$filas_archivo);


		}else if($cod_entidad_bancaria==166){// AGRICOLA
			$data3=$this->Cnmd01->execute("SELECT * FROM v_cnmd06_fichas_asig_ded WHERE ".$this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina." and cod_entidad_bancaria=$cod_entidad_bancaria ORDER BY cedula_identidad ASC");

			foreach($data3 as $rsdata){

				extract($rsdata[0]);
				$cedula = mascara($cedula_identidad,10);
				$nombre_completo = str_replace('  ',' ',trim($nombre_completo));
				$nombre_completo = str_replace("\t",' ',$nombre_completo);
				$nombre_completo = str_replace("Ñ",'@',$nombre_completo);
				$nombre = cortar_cadena_diskette(elimina_acentos($nombre_completo), 30);
				$nombre = str_replace("@",'N',$nombre);
				$nacion = up($nacionalidad);
				//$cuenta = formato_cuenta_diskette($cuenta_bancaria);
				//$cuenta= str_pad($cuenta_bancaria, 10 , "0", STR_PAD_RIGHT).'';

				$neto_cobrar_1 = $asignaciones - $deducciones;
				$neto_cobrar_1_if = $neto_cobrar_1;
				$neto_cobrar_1_if = (int) $neto_cobrar_1_if;
				$neto_aux = explode('.',$neto_cobrar_1);
				$cuenta = substr($cuenta_bancaria,-12);

				if(isset($neto_aux[1])){
					$decimal=$neto_aux[1];
				}else{
					$decimal=0;
				}
				$decimal= str_pad($decimal, 2 , "0", STR_PAD_RIGHT).'';
				$neto_cobrar_1 = $neto_aux[0].$decimal;
				$neto_cobrar_1 = mascara($neto_cobrar_1,15);

				if($neto_cobrar_1_if!=0){
	                $filas_archivo .= $nacion."0000".$cedula.$cuenta.$neto_cobrar_1."0"."\r\n";
				}
			}

			$nombre_archivo = "NOM-".mascara($cod_tipo_nomina,2)."-"."AGRICOLA";
			$_SESSION["nombre_txt"]=$nombre_archivo.".txt";
			$this->wFile($nombre_archivo, $filas_archivo);
			$this->set('filas_archivo',$filas_archivo);

		}else if($cod_entidad_bancaria==128){// CARONI
			$data3=$this->Cnmd01->execute("SELECT * FROM v_cnmd06_fichas_asig_ded WHERE ".$this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina." and cod_entidad_bancaria=$cod_entidad_bancaria ORDER BY cedula_identidad ASC");
			foreach($data3 as $rsdata){

				extract($rsdata[0]);
				$cedula = mascara($cedula_identidad,9);
				$nombre_completo = str_replace('  ',' ',trim($nombre_completo));
				$nombre_completo = str_replace("\t",' ',$nombre_completo);
				$nombre_completo = str_replace("Ñ",'@',$nombre_completo);
				$nombre = cortar_cadena_diskette(elimina_acentos($nombre_completo), 40);
				$nombre = str_replace("Ñ",'N',$nombre);
				$nacion = up($nacionalidad);
				//$cuenta = formato_cuenta_diskette($cuenta_bancaria);

				$cuenta = $cuenta_bancaria;

				$neto_cobrar_1 = $asignaciones - $deducciones;
				$neto_cobrar_1_if = $neto_cobrar_1;
				$neto_cobrar_1_if = (int) $neto_cobrar_1_if;
				$neto_aux = explode('.',$neto_cobrar_1);
				if(isset($neto_aux[1])){
					$decimal=$neto_aux[1];
				}else{
					$decimal=0;
				}
				$decimal= str_pad($decimal, 2 , "0", STR_PAD_RIGHT).'';
				$neto_cobrar_1 = $neto_aux[0].$decimal;
				//$neto_cobrar_2 = mascara($neto_cobrar_1,15);
				$neto_cobrar_2 = mascara($neto_cobrar_1,10);

				if($neto_cobrar_1_if!=0){
	          $filas_archivo .= $nacion.$cedula.$nombre.$cuenta.$neto_cobrar_2."\r\n";
				}
			}

			$nombre_archivo = "NOM-".mascara($cod_tipo_nomina,2)."-"."CARONI";
			$_SESSION["nombre_txt"]=$nombre_archivo.".txt";

			$this->wFile($nombre_archivo, $filas_archivo);
			$this->set('filas_archivo',$filas_archivo);

   	} else if ($cod_entidad_bancaria == 105) {// BANCO MERCANTIL
    	foreach ($data3agurpado as $rsagrupado) {
        $data3CuentaBancariaCancela = $this->Cnmd01->execute("SELECT cuenta_bancaria as cuenta_bancaria_cancela FROM cnmd09_bancos_cancelan_nominas WHERE " . $this->SQLCA() . " and cod_tipo_nomina=" . $cod_tipo_nomina . " and cod_entidad_bancaria=$cod_entidad_bancaria  ORDER BY cod_sucursal ASC LIMIT 1");

        $data3 = $this->Cnmd01->execute("SELECT * FROM v_cnmd06_fichas_asig_ded WHERE " . $this->SQLCA() . " and cod_tipo_nomina=" . $cod_tipo_nomina . " and cod_entidad_bancaria=$cod_entidad_bancaria ORDER BY cedula_identidad ASC");
        $this->set('data3', $data3);
        $filas_archivo_aux = "";
        $c_registros = 0;
        $c_montos = 0;

				//ESPACIO PARA CONDIONAL DE TIPO DE PAGO SI EXISTIESE
        $tipo_pago = mascara(222, 10);

        foreach ($data3 as $rsdata) {
            extract($rsdata[0]);
				//Asignar Codigo Empresa
            $codigo_empresa = '0000';
				//PARA ADINISTRACION CENTRAL
            if ($tipo_dependencia == 1) {
                $codigo_empresa = '770';
            }
				//PARA LAS DEPENDENCIAS
            if ($tipo_dependencia == 1004) {
                $codigo_empresa = '785';
            }
            $cedula = mascara($cedula_identidad, 15);
            $cuenta = $cuenta_bancaria;
				//$cuenta = substr($cuenta_bancaria,-12);
            $neto_cobrar_1 = $asignaciones - $deducciones;
            $neto_cobrar_1_if = $neto_cobrar_1;
            $neto_aux = explode('.', $neto_cobrar_1);
            if (isset($neto_aux[1])) {
                $decimal = $neto_aux[1];
            } else {
                $decimal = 0;
            }
            $decimal = str_pad($decimal, 2, "0", STR_PAD_RIGHT) . '';
            $neto_cobrar_1 = mascara($neto_aux[0], 15) . $decimal;
            $neto_cobrar_2 = $neto_cobrar_1;

            if ($neto_cobrar_2 != '000000000000') {

                $filas_archivo_aux .= "2" . $nacionalidad . $cedula . "1" . mascara(0, 12) . mascara_espacio("", 30) . mascara($cuenta, 20) . $neto_cobrar_2 . mascara_espacio('', 16) . $tipo_pago . mascara(0, 3) . mascara_espacio('', 60) . mascara(0, 15) . mascara_espacio("", 50) . mascara(0, 4) . mascara_espacio("", 30) . mascara_espacio("", 80) . mascara(0, 35) . "\r\n";
                $c_registros++;
                $c_montos += $neto_cobrar_1_if;
				//$filas_archivo .= $cedula."\n";
            }
        }//fin foreach data3
        $c_registros = mascara($c_registros, 4);
        $c_montos_aux = explode('.', $c_montos);
        if (isset($c_montos_aux[1])) {
            $decimal2 = $c_montos_aux[1];
        } else {
            $decimal2 = 0;
        }
				//ENCABEZADO

        $decimal2 = str_pad($decimal2, 2, "0", STR_PAD_RIGHT) . '';
        $c_montos = mascara($c_montos_aux[0], 15) . $decimal2;
        $data3CuentaBancariaCancela[0][0]['cuenta_bancaria_cancela'] = isset($data3CuentaBancariaCancela[0][0]['cuenta_bancaria_cancela']) ? $data3CuentaBancariaCancela[0][0]['cuenta_bancaria_cancela'] : str_pad('NO EXISTE CUENTA', 20, "0", STR_PAD_RIGHT);
				// $cuenta_gob = substr($data3CuentaBancariaCancela[0][0]['cuenta_bancaria_cancela'], -10);
				// $cuenta_gobernacion = $cuenta_gob;
        $cuenta_gobernacion = $data3CuentaBancariaCancela[0][0]['cuenta_bancaria_cancela'];


				//mascara_espacio($var=0,$cantidad_relleno=1)
        $min = 1000000;
        $max = 9999999;
        $numero_aleatorio = mt_rand($min, $max);
        $fecha_sin_0 = date('jnY');
        $numero_archivo_cliente_conc = $fecha_sin_0.$numero_aleatorio;
        $numero_archivo_cliente = mascara($numero_archivo_cliente_conc, 15);
        $rif = mascara(200012870, 15);

        $filas_archivo .= "1" . mascara_espacio("BAMRVECA", 12) . $numero_archivo_cliente . "NOMIN" . $tipo_pago . "G" . $rif . mascara($c_registros, 8) . $c_montos . date('Ymd') . $cuenta_gobernacion . mascara(0, 7) . mascara(0, 281) . "\r\n";
        $filas_archivo .= $filas_archivo_aux;
    	}//fin foreach $data3agurpado

	    $nombre_archivo = "BSF000W";
	    $_SESSION["nombre_txt"] = $nombre_archivo . ".txt";
	    $this->set('filas_archivo', $filas_archivo);
	    $this->wFile($nombre_archivo, $filas_archivo);

		}else if($cod_entidad_bancaria==191){//BANCO NACIONAL DE CREDITO BNC
			$data3CuentaBancariaCancela=$this->Cnmd01->execute("SELECT cuenta_bancaria as cuenta_bancaria_cancela,rif FROM cnmd09_bancos_cancelan_nominas WHERE ".$this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina." and cod_entidad_bancaria=$cod_entidad_bancaria  ORDER BY cod_sucursal ASC LIMIT 1");
            $rif = $data3CuentaBancariaCancela[0][0]['rif'];
            $rif = str_replace("-", "", $rif);
            $cuenta_bancaria_cancela = $data3CuentaBancariaCancela[0][0]['cuenta_bancaria_cancela'];

            $data3=$this->Cnmd01->execute("SELECT * FROM v_cnmd06_fichas_asig_ded WHERE ".$this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina." and cod_entidad_bancaria=$cod_entidad_bancaria ORDER BY cedula_identidad ASC");
	        $this->set('data3',$data3);
	        $filas_archivo_aux="";
	        $c_registros = 0;
	        $c_montos = 0;
            $monto_nomina=0;

			foreach($data3 as $rsdata){
				extract($rsdata[0]);
	            $nacion = up($nacionalidad);
	            $cedula = mascara($cedula_identidad,9);

				$nombre_completo = str_replace('  ',' ',trim($nombre_completo));
				$nombre_completo = str_replace("\t",' ',$nombre_completo);
				$nombre_completo = str_replace("Ñ",'@',$nombre_completo);
				$nombre = cortar_cadena_diskette(elimina_acentos($nombre_completo), 30);
				$nombre = str_replace("@",'Ñ',$nombre);
				//$cuenta = formato_cuenta_diskette($cuenta_bancaria);
				$neto_cobrar_1 = $asignaciones - $deducciones;
				$neto_cobrar_1_if = $neto_cobrar_1;
				//$neto_cobrar_1_if = (int) $neto_cobrar_1_if;
				$neto_aux = explode('.',$neto_cobrar_1);
				if(isset($neto_aux[1])){
					$decimal=$neto_aux[1];
				}else{
					$decimal=0;
				}
				$decimal= str_pad($decimal, 2 , "0", STR_PAD_RIGHT).'';
				$neto_cobrar_1 = $neto_aux[0].$decimal;
				$neto_cobrar_2 = mascara($neto_cobrar_1,13);
				if($neto_cobrar_1_if!= 0){
	                $filas_archivo .= "NC".$cuenta_bancaria.$neto_cobrar_2.$nacion.$cedula."\r\n";
                    $monto_nomina = $monto_nomina + $neto_cobrar_2;
				}
			}//fin foreach data3

            $nd="ND".$cuenta_bancaria_cancela.mascara($monto_nomina,13).$rif."\r\n";
            $filas_archivo=$nd.$filas_archivo;

			$nombre_archivo = "NOM-".mascara($cod_tipo_nomina,2)."-"."BNC";
			$_SESSION["nombre_txt"]=$nombre_archivo.".txt";
			$this->wFile($nombre_archivo, $filas_archivo);
			$this->set('filas_archivo',$filas_archivo);

		}

	} else {
	   // INSTITUCIONES POR DEFECTO

	    $filas_archivo="";

	    if($cod_entidad_bancaria==134){// BANESCO

		$data3=$this->Cnmd01->execute("SELECT * FROM v_cnmd06_fichas_asig_ded WHERE ".$this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina." and cod_entidad_bancaria=$cod_entidad_bancaria ORDER BY cedula_identidad ASC");
		foreach($data3 as $rsdata){
			extract($rsdata[0]);

			$cedula = up($nacionalidad).mascara($cedula_identidad,8);
			$nombre_completo = str_replace('  ',' ',trim($nombre_completo));
			$nombre_completo = str_replace("\t",' ',$nombre_completo);
			$nombre_completo = str_replace("Ñ",'@',$nombre_completo);
			$nombre = cortar_cadena_diskette(elimina_acentos($nombre_completo), 30);
			$nombre = str_replace("@",'Ñ',$nombre);
			//$cuenta = formato_cuenta_diskette($cuenta_bancaria);
			$cuenta = $cuenta_bancaria;
			$neto_cobrar_1 = $asignaciones - $deducciones;
			$neto_cobrar_1_if = $neto_cobrar_1;
			$neto_cobrar_1_if = (int) $neto_cobrar_1_if;
			$neto_aux = explode('.',$neto_cobrar_1);
			if(isset($neto_aux[1])){
				$decimal=$neto_aux[1];
			}else{
				$decimal=0;
			}
			$decimal= str_pad($decimal, 2 , "0", STR_PAD_RIGHT).'';
			$neto_cobrar_1 = $neto_aux[0].','.$decimal;
			//$neto_cobrar_2 = mascara($neto_cobrar_1,15);
			$neto_cobrar_2 = $neto_cobrar_1;
			if($neto_cobrar_1_if!=0){
                $filas_archivo .= $neto_cobrar_2."\t".$cuenta."\t".$cedula."\t".$nombre."\n";
			}
		}
		$this->wFile($nombre_archivo, $filas_archivo);
		$this->set('filas_archivo',$filas_archivo);



		}else if($cod_entidad_bancaria==116){// BANCO BOD


                    foreach($data3agurpado as $rsagrupado){
		            $data3CuentaBancariaCancela=$this->Cnmd01->execute("SELECT cuenta_bancaria as cuenta_bancaria_cancela,rif FROM cnmd09_bancos_cancelan_nominas WHERE ".$this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina." and cod_entidad_bancaria=$cod_entidad_bancaria  ORDER BY cod_sucursal ASC LIMIT 1");
                    $rif = $data3CuentaBancariaCancela[0][0]['rif'];
                    $rif = str_replace("-","",$rif);
                    $xrif = substr($rif,1);
                    $x2rif = substr($xrif,-1);
                    $x3rif = substr($xrif,0,-1);
                    $rif = substr($rif,0,1)."".mascara($x3rif,12)."-".$x2rif;
                    $nombre_empresa = $_SESSION['entidad_federal'];
                    $nombre_empresa = str_replace('  ',' ',trim($nombre_empresa));
					$nombre_empresa = str_replace("\t",' ',$nombre_empresa);
					$nombre_empresa = str_replace("Ñ",'N',$nombre_empresa);
					$nombre_empresa = cortar_cadena_diskette(elimina_acentos($nombre_empresa), 30);
					$nombre_empresa = str_replace("@",'Ñ',$nombre_empresa);

					$encabezado=$this->Cnmd01->findAll($this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina);

					if($encabezado[0]['Cnmd01']['clasificacion_personal']==13){//BECAS
						$num_contrato="40040200001542002";
						$modalidad="EFE";
					}else if($encabezado[0]['Cnmd01']['clasificacion_personal']==14){//AYUDAS
						$num_contrato="40040200001542003";
						$modalidad="EFE";
					}else{
						 $num_contrato="40040200001542001";// OTROS
	    				 $modalidad="CTA";
					}

					$num_nomina=$encabezado[0]['Cnmd01']['numero_nomina'];
					$fecha_envio=str_replace('-', '', $encabezado[0]['Cnmd01']['periodo_hasta']);
		        	$data3=$this->Cnmd01->execute("SELECT * FROM v_cnmd06_fichas_asig_ded WHERE ".$this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina." and cod_entidad_bancaria=$cod_entidad_bancaria ORDER BY cedula_identidad ASC");
			        $total = 0;
					$num_personas=0;

					foreach($data3 as $rsdata){
						extract($rsdata[0]);
							$total=  $total + ($asignaciones - $deducciones);
							if(($asignaciones - $deducciones)>0)$num_personas=$num_personas+1;
					}//fin foreach data3

							$neto_aux = explode('.',$total);
							if(isset($neto_aux[1])){
								$decimal=$neto_aux[1];
							}else{
								$decimal=0;
							}
							$decimal= str_pad($decimal, 2 , "0", STR_PAD_RIGHT).'';
							$total =  mascara($neto_aux[0],15).$decimal;

					$filtro='';

					for($i=1;$i<=158;$i++){
						$filtro.=' ';
					}

					$filtro2='';

					for($i=1;$i<=20;$i++){
						$filtro2.=' ';
					}

					$email='';

					for($i=1;$i<=40;$i++){
						$email.=' ';
					}

				    $filas_archivo_aux = "01NOMINA              G200001542".$num_contrato.mascara($num_nomina,9).$fecha_envio.mascara($num_personas,6).$total."VEB".$filtro."\n";
			        $this->set('data3',$data3);
			        $c_registros = 0;
			        $c_montos = 0;

					foreach($data3 as $rsdata){
						extract($rsdata[0]);
			            $nacion = up($nacionalidad);
			            $cedula = mascara($cedula_identidad,9);

						$nombre_completo = str_replace('  ',' ',trim($nombre_completo));
						$nombre_completo = str_replace("\t",' ',$nombre_completo);
						$nombre_completo = str_replace("Ñ",'@',$nombre_completo);
						$nombre = cortar_cadena_diskette(elimina_acentos($nombre_completo), 30);
						$nombre = str_replace("@",'Ñ',$nombre);

						for($i=1;strlen($nombre)<=59;$i++){
							$nombre.=' ';
						}

							$neto_cobrar_1 = $asignaciones - $deducciones;
							$neto_cobrar_1_if = $neto_cobrar_1;
							$neto_aux = explode('.',$neto_cobrar_1);
							if(isset($neto_aux[1])){
								$decimal=$neto_aux[1];
							}else{
								$decimal=0;
							}
							$decimal= str_pad($decimal, 2 , "0", STR_PAD_RIGHT).'';
							$neto_cobrar_1 =  mascara($neto_aux[0],13).$decimal;
							$neto_cobrar_2 = $neto_cobrar_1;
							if($neto_cobrar_2!= '000000000000000'){
				                $filas_archivo_aux .= "02".$nacion.$cedula.$nombre.mascara($num_nomina,9)."REEMPLAZO".mascara($cod_tipo_nomina,3)." NUMERO".mascara($num_nomina,6).$modalidad.mascara($cuenta_bancaria,20).mascara($cod_entidad_bancaria,4).$fecha_envio.mascara($neto_cobrar_1,15)."VEB"."000000000000000".$email."00000000000".$filtro2."\n";
				              //  $prueba=strlen("02".$nacion.$cedula.$nombre.mascara($num_nomina,9)."TIPO DE NOMINA".mascara($cod_tipo_nomina,3)." NUMERO".mascara($num_nomina,6).$modalidad.mascara($cuenta_bancaria,20).mascara($cod_entidad_bancaria,4).$fecha_envio.mascara($neto_cobrar_1,15)."VEB"."000000000000000".$email."00000000000".$filtro2);
				                $c_registros++;
				                $c_montos += $neto_cobrar_1_if;
							}

					}//fin foreach data3

				}//fin foreach $data3agurpado

                        $filas_archivo .= $filas_archivo_aux;



               }else if($cod_entidad_bancaria==149){//BANCO DEL PUEBLO SOBERANO

                    $data3=$this->Cnmd01->execute("SELECT * FROM v_cnmd06_fichas_asig_ded WHERE ".$this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina." and cod_entidad_bancaria=$cod_entidad_bancaria ORDER BY cedula_identidad ASC");
			        $this->set('data3',$data3);
			        $filas_archivo_aux="";
			        $c_registros = 0;
			        $c_montos = 0;
					foreach($data3 as $rsdata){
						extract($rsdata[0]);
			            $nacion = up($nacionalidad);
			            $cedula = mascara($cedula_identidad,9);
						$nombre_completo = str_replace('  ',' ',trim($nombre_completo));
						$nombre_completo = str_replace("\t",' ',$nombre_completo);
						$nombre_completo = str_replace("Ñ",'@',$nombre_completo);
						$nombre = cortar_cadena_diskette(elimina_acentos($nombre_completo),35);
						$nombre = str_replace("@",'Ñ',$nombre);
						$cuenta=$cuenta_bancaria;
					  //$cuenta=substr($cuenta_bancaria,0,4).' '.substr($cuenta_bancaria,4,4).' '.substr($cuenta_bancaria,8, 2).' '.substr($cuenta_bancaria,10,10);
					  //$cuenta=substr($cuenta_bancaria,1,4).' '.substr($cuenta_bancaria,5,8).' '.substr($cuenta_bancaria,9,10).' '.substr($cuenta_bancaria,11,20).' ';
					  //$cuenta = formato_cuenta_diskette($cuenta_bancaria);
						$neto_cobrar_1 = $asignaciones - $deducciones;
						$neto_cobrar_1_if = $neto_cobrar_1;
						//$neto_cobrar_1_if = (int) $neto_cobrar_1_if;
						$neto_aux = explode('.',$neto_cobrar_1);
						if(isset($neto_aux[1])){
							$decimal=$neto_aux[1];
						}else{$decimal=0;}

						$decimal= str_pad($decimal, 2 , "0", STR_PAD_RIGHT).'';
						$neto_cobrar_1 = $neto_aux[0].$decimal;
						$neto_cobrar_2 = mascara($neto_cobrar_1,15);
						$neto_cobrar_2 = mascara($neto_cobrar_1,15);
						if($neto_cobrar_1_if!= 0){
							  $filas_archivo .= $nacion.$cedula.$cuenta_bancaria.$neto_cobrar_2.$nombre."\r\n";
			                //$filas_archivo .= $nacion.$cedula.mascara(substr($cuenta_bancaria,1,3),4).substr($cuenta_bancaria,4,4).substr($cuenta_bancaria,8,2).substr($cuenta_bancaria,10,10).$neto_cobrar_2.$nombre."\r\n";
			                //$filas_archivo .= $cedula."\n";
						}
					}//fin foreach data3


				$nombre_archivo = "NOM-".mascara($cod_tipo_nomina,2)."-"."BPS";
				$_SESSION["nombre_txt"]=$nombre_archivo.".txt";
					$this->wFile($nombre_archivo, $filas_archivo);
					$this->set('filas_archivo',$filas_archivo);


	}// FIN INSTITUCIONES POR DEFECTO


}// FIN DE TXT BANCARIOS


	$this->set('var',$var);
}else if($var=='hojac'){
         $this->layout="ajax";
		$cod_presi     = $this->Session->read('SScodpresi');
		$cod_entidad   = $this->Session->read('SScodentidad');
		$cod_tipo_inst = $this->Session->read('SScodtipoinst');
		$cod_inst      = $this->Session->read('SScodinst');
		$cod_dep       = $this->Session->read('SScoddep');
		$this->data['reporte_personal']['select_tiponomina'];
	    $data3=$this->Cnmd01->execute("SELECT * FROM v_cnmd06_fichas_asig_ded WHERE ".$this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina." and cod_entidad_bancaria=$cod_entidad_bancaria ORDER BY cedula_identidad ASC");
	    $this->set('data3',$data3);
	    $this->set('var',$var);
}///fin hojac



}









function denominacion_tiponomina($var=null){
	$this->layout="ajax";
	if($var=='no' || $var==''){
		$this->set('cod_nomina','');
		$this->set('deno_nomina','');
		$this->set('mensajeError','Lo siento, no se encontrar&oacute;n registros');
	}else{
		$condicion = $this->SQLCA()." and cod_tipo_nomina='$var'";
		$datos=$this->Cnmd01->findAll($condicion, 'cod_tipo_nomina, denominacion', 'cod_tipo_nomina ASC');
		if($datos!=null){
			$this->set('cod_nomina',$this->AddCeroR2($datos[0]['Cnmd01']['cod_tipo_nomina']));
			$this->set('deno_nomina',$datos[0]['Cnmd01']['denominacion']);
		}else{
			$this->set('cod_nomina','');
			$this->set('deno_nomina','');
		}
	}
}

function lista_bancos ($cod_tipo_nomina=null,$tipo_ordenamiento=null) {
   $this->layout="ajax";
   if(isset($cod_tipo_nomina) && $cod_tipo_nomina!=null){
   	  $sql ="SELECT a.cod_entidad_bancaria,x.denominacion,count(a.cod_entidad_bancaria) FROM cnmd06_fichas a,cstd01_entidades_bancarias x WHERE ".$this->SQLCA()." and a.cod_tipo_nomina=$cod_tipo_nomina and x.cod_entidad_bancaria=a.cod_entidad_bancaria AND condicion_actividad=1 GROUP BY a.cod_entidad_bancaria,x.denominacion ORDER BY x.denominacion ASC";
      $this->set('bancos',$this->cnmd06_fichas->execute($sql));
      $this->set('cod_tipo_nomina',$cod_tipo_nomina);
      $this->set('tipo_ordenamiento',$tipo_ordenamiento);

   }
}//fin lista_bancos


function lista_bancos_ordenamiento ($cod_tipo_nomina=null) {
   $this->layout="ajax";
   if(isset($cod_tipo_nomina) && $cod_tipo_nomina!=null){
      $this->set('cod_tipo_nomina',$cod_tipo_nomina);
   }
}//fin lista_bancos



function emision_recibos_genericos($var=null){
	$this->layout="ajax";
	if($var=='no'){
		$condicion = $this->SQLCA();
		$lista = $this->Cnmd01->generateList($condicion, 'cod_tipo_nomina ASC', null, '{n}.Cnmd01.cod_tipo_nomina', '{n}.Cnmd01.denominacion');
		if($lista!=null){
			$this->concatena($lista, 'lista');
		}else{
			$this->set('lista',array('no'=>'no hay registros'));
		}
		$this->set('var',$var);

	}elseif($var=='si'){
		ini_set("memory_limit","2560M");
		$this->layout="ajax";
		$tipo_nomina   = $this->data['reporte_personal']['select_tiponomina'];
		$orden_reporte = $this->data['reporte_personal']['ordenado'];
		$rango_recibos = $this->data['reporte_personal']['rango_recibos'];
		$condicion = $this->SQLCA()." AND cod_tipo_nomina='$tipo_nomina'";

 		if($tipo_nomina=='' || $orden_reporte=='' || $rango_recibos==''){
			echo '<script>history.back(1)</script>';
		}

		if($rango_recibos==2){
			$recibos_desde = $this->data['reporte_personal']['recibo_desde'];
			$recibos_hasta = $this->data['reporte_personal']['recibo_hasta'];
			if(empty($recibos_desde) || empty($recibos_hasta)){
				echo '<script>history.back(1)</script>';
			}else{
				$condicion .= " AND a.ultimo_recibo >= '$recibos_desde' AND a.ultimo_recibo <= '$recibos_hasta'";
			}
		}

		switch($orden_reporte){
			case '1': $order_by= " a.ultimo_recibo, a.cedula_identidad"; break;
			case '2': $order_by= " a.cod_dir_superior, a.cod_coordinacion, a.cod_secretaria, a.cod_direccion, a.cod_division, a.cod_departamento, a.cod_oficina, a.cedula_identidad"; break;
			case '3': $order_by= " a.cod_estado, a.cod_municipio, a.cod_parroquia, a.cod_centro, a.cedula_identidad"; break;
			case '4': $order_by= " a.cod_sector, a.cod_programa, a.cod_sub_prog, a.cod_proyecto, a.cod_activ_obra, a.cod_partida, a.cod_generica, a.cod_especifica, a.cod_sub_espec, a.cod_auxiliar, a.cedula_identidad"; break;
			default : $order_by= " a.ultimo_recibo, a.cedula_identidad"; break;
		}

		$sql = "SELECT a.cod_tipo_nomina, a.denominacion_nomina, a.cod_coordinacion, a.deno_cod_coordinacion, a.cod_secretaria, a.deno_cod_secretaria,
		a.cod_direccion, a.deno_cod_direccion, a.cod_division, a.deno_cod_division, a.cod_departamento, a.deno_cod_departamento, a.cod_oficina, a.deno_cod_oficina,
		a.cod_municipio, a.deno_cod_municipio, a.cod_parroquia, a.deno_cod_parroquia, a.cod_centro, a.deno_cod_centro,
		a.cedula_identidad, a.primer_apellido, a.segundo_apellido, a.primer_nombre, a.segundo_nombre, a.cod_puesto, a.denominacion_puesto, a.cod_cargo, a.cod_ficha, a.sueldo_basico,
		a.fecha_ingreso, a.numero_nomina, a.correspondiente, a.periodo_desde, a.periodo_hasta,
		a.cod_tipo_transaccion, a.cod_transaccion, a.deno_transaccion, a.dias_horas, a.frecuencia_cobro, a.monto_cuota, a.saldo, a.ultimo_recibo,
		a.cod_entidad_bancaria, a.deno_entidades_bancarias, a.cod_sucursal, a.cuenta_bancaria, a.uso_transaccion,
		(SELECT b.denominacion_devengado FROM cnmd01 b WHERE b.cod_presi=a.cod_presi AND b.cod_entidad=a.cod_entidad AND b.cod_tipo_inst=a.cod_tipo_inst AND b.cod_inst=a.cod_inst AND b.cod_dep=a.cod_dep AND b.cod_tipo_nomina=a.cod_tipo_nomina) as devengado
		FROM v_cnmd07_transacciones_actuales_condicion a
		WHERE ".$condicion."  AND a.condicion_actividad_ficha=1 AND a.uso_transaccion!=6 AND a.uso_transaccion!=9 ORDER BY ".$order_by;// AND a.ultimo_recibo!=0

		$datos = $this->v_cnmd07_transacciones_actuales->execute($sql);

		$this->set('datos',$datos);
		$this->set('var',$var);
	}
}//emision_recibos_genericos


function emision_recibos_genericos_2($var=null){

$this->layout="ajax";
	if($var=='no'){
		$condicion = $this->SQLCA();
		$lista = $this->Cnmd01->generateList($condicion, 'cod_tipo_nomina ASC', null, '{n}.Cnmd01.cod_tipo_nomina', '{n}.Cnmd01.denominacion');
		if($lista!=null){
			$this->concatena($lista, 'lista');
		}else{
			$this->set('lista',array('no'=>'no hay registros'));
		}
		$this->set('var',$var);

	}elseif($var=='si'){
		ini_set("memory_limit","2560M");
		$this->layout="ajax";
		//$this->layout="pdf";
		$tipo_nomina   = $this->data['reporte_personal']['select_tiponomina'];
		$orden_reporte = $this->data['reporte_personal']['ordenado'];
		$rango_recibos = $this->data['reporte_personal']['rango_recibos'];
		$condicion = $this->SQLCA()." AND cod_tipo_nomina='$tipo_nomina'";

 		if($tipo_nomina=='' || $orden_reporte=='' || $rango_recibos==''){
			echo '<script>history.back(1)</script>';
		}

		if($rango_recibos==2){
			$recibos_desde = $this->data['reporte_personal']['recibo_desde'];
			$recibos_hasta = $this->data['reporte_personal']['recibo_hasta'];
			if(empty($recibos_desde) || empty($recibos_hasta)){
				echo '<script>history.back(1)</script>';
			}else{
				$condicion .= " AND a.ultimo_recibo >= '$recibos_desde' AND a.ultimo_recibo <= '$recibos_hasta'";
			}
		}

		switch($orden_reporte){
			case '1': $order_by= " a.ultimo_recibo, a.cedula_identidad"; break;
			case '2': $order_by= " a.cod_dir_superior, a.cod_coordinacion, a.cod_secretaria, a.cod_direccion, a.cod_division, a.cod_departamento, a.cod_oficina, a.cedula_identidad, a.ultimo_recibo"; break;
			case '3': $order_by= " a.cod_estado, a.cod_municipio, a.cod_parroquia, a.cod_centro, a.cedula_identidad, a.ultimo_recibo"; break;
			case '4': $order_by= " a.cod_sector, a.cod_programa, a.cod_sub_prog, a.cod_proyecto, a.cod_activ_obra, a.cod_partida, a.cod_generica, a.cod_especifica, a.cod_sub_espec, a.cod_auxiliar, a.cedula_identidad, a.ultimo_recibo"; break;
			default : $order_by= " a.ultimo_recibo, a.cedula_identidad"; break;
		}

		$sql = "SELECT a.cod_tipo_nomina, a.denominacion_nomina, a.cod_coordinacion, a.deno_cod_coordinacion, a.cod_secretaria, a.deno_cod_secretaria,
		a.cod_direccion, a.deno_cod_direccion, a.cod_division, a.deno_cod_division, a.cod_departamento, a.deno_cod_departamento, a.cod_oficina, a.deno_cod_oficina,
		a.cod_municipio, a.deno_cod_municipio, a.cod_parroquia, a.deno_cod_parroquia, a.cod_centro, a.deno_cod_centro,
		a.cedula_identidad, a.primer_apellido, a.segundo_apellido, a.primer_nombre, a.segundo_nombre, a.cod_puesto, a.denominacion_puesto, a.cod_cargo, a.cod_ficha, a.sueldo_basico,
		a.fecha_ingreso, a.numero_nomina, a.correspondiente, a.periodo_desde, a.periodo_hasta, devolver_edad(a.periodo_hasta, a.fecha_ingreso, 'ANO') as anos_servicio, devolver_edad(a.periodo_hasta, a.fecha_ingreso, 'MES') as mes_servicio, devolver_edad(a.periodo_hasta, a.fecha_ingreso, 'DIA') as dias_servicio,
		a.cod_tipo_transaccion, a.cod_transaccion, a.deno_transaccion, a.dias_horas, a.frecuencia_cobro, a.monto_cuota, a.saldo, a.ultimo_recibo,
		a.cod_entidad_bancaria, a.deno_entidades_bancarias, a.cod_sucursal, a.cuenta_bancaria, a.uso_transaccion,
		(SELECT b.denominacion_devengado FROM cnmd01 b WHERE b.cod_presi=a.cod_presi AND b.cod_entidad=a.cod_entidad AND b.cod_tipo_inst=a.cod_tipo_inst AND b.cod_inst=a.cod_inst AND b.cod_dep=a.cod_dep AND b.cod_tipo_nomina=a.cod_tipo_nomina) as devengado
		FROM v_cnmd07_transacciones_actuales_condicion a
		WHERE ".$condicion."  AND a.condicion_actividad_ficha=1 AND a.ultimo_recibo!=0 AND a.uso_transaccion!=6 AND a.uso_transaccion!=9 ORDER BY ".$order_by;// AND a.ultimo_recibo!=0

		$datos = $this->v_cnmd07_transacciones_actuales->execute($sql);

		$this->set('datos',$datos);
		$this->set('var',$var);
	}
}//emision_recibos_genericos_2



function emision_recibos_genericos_23($var=null){
	$this->layout="ajax";
	if($var=='no'){
		$condicion = $this->SQLCA();
		$lista = $this->Cnmd01->generateList($condicion, 'cod_tipo_nomina ASC', null, '{n}.Cnmd01.cod_tipo_nomina', '{n}.Cnmd01.denominacion');
		if($lista!=null){
			$this->concatena($lista, 'lista');
		}else{
			$this->set('lista',array('no'=>'no hay registros'));
		}
		$this->set('var',$var);

	}elseif($var=='si'){
		$this->layout="pdf";
		$tipo_nomina   = $this->data['reporte_personal']['select_tiponomina'];
		$orden_reporte = $this->data['reporte_personal']['ordenado'];
		$rango_recibos = $this->data['reporte_personal']['rango_recibos'];
		$condicion = $this->SQLCA()." AND cod_tipo_nomina='$tipo_nomina'";

 		if($tipo_nomina=='' || $orden_reporte=='' || $rango_recibos==''){
			echo '<script>history.back(1)</script>';
		}

		if($rango_recibos==2){
			$recibos_desde = $this->data['reporte_personal']['recibo_desde'];
			$recibos_hasta = $this->data['reporte_personal']['recibo_hasta'];
			if(empty($recibos_desde) || empty($recibos_hasta)){
				echo '<script>history.back(1)</script>';
			}else{
				$condicion .= " AND a.ultimo_recibo >= '$recibos_desde' AND a.ultimo_recibo <= '$recibos_hasta'";
			}
		}

		switch($orden_reporte){
			case '1': $order_by= " a.ultimo_recibo, a.cedula_identidad"; break;
			case '2': $order_by= " a.cod_dir_superior, a.cod_coordinacion, a.cod_secretaria, a.cod_direccion, a.cod_division, a.cod_departamento, a.cod_oficina, a.ultimo_recibo, a.cedula_identidad"; break;
			case '3': $order_by= " a.cod_estado, a.cod_municipio, a.cod_parroquia, a.cod_centro, a.ultimo_recibo, a.cedula_identidad"; break;
			case '4': $order_by= " a.cod_sector, a.cod_programa, a.cod_sub_prog, a.cod_proyecto, a.cod_activ_obra, a.cod_partida, a.cod_generica, a.cod_especifica, a.cod_sub_espec, a.cod_auxiliar, a.ultimo_recibo, a.cedula_identidad"; break;
			default : $order_by= " a.ultimo_recibo, a.cedula_identidad"; break;
		}

		$sql = "SELECT a.cod_tipo_nomina, a.denominacion_nomina, a.cod_coordinacion, a.deno_cod_coordinacion, a.cod_secretaria, a.deno_cod_secretaria,
		a.cod_direccion, a.deno_cod_direccion, a.cod_division, a.deno_cod_division, a.cod_departamento, a.deno_cod_departamento, a.cod_oficina, a.deno_cod_oficina,
		a.cod_municipio, a.deno_cod_municipio, a.cod_parroquia, a.deno_cod_parroquia, a.cod_centro, a.deno_cod_centro,
		a.cedula_identidad, a.primer_apellido, a.segundo_apellido, a.primer_nombre, a.segundo_nombre, a.cod_puesto, a.denominacion_puesto, a.cod_cargo, a.cod_ficha, a.sueldo_basico,
		a.fecha_ingreso, a.numero_nomina, a.correspondiente, a.periodo_desde, a.periodo_hasta,
		a.cod_tipo_transaccion, a.cod_transaccion, a.deno_transaccion, a.dias_horas, a.frecuencia_cobro, a.monto_cuota, a.saldo, a.ultimo_recibo,
		a.cod_entidad_bancaria, a.deno_entidades_bancarias, a.cod_sucursal, a.cuenta_bancaria, a.uso_transaccion,
		(SELECT b.denominacion_devengado FROM cnmd01 b WHERE b.cod_presi=a.cod_presi AND b.cod_entidad=a.cod_entidad AND b.cod_tipo_inst=a.cod_tipo_inst AND b.cod_inst=a.cod_inst AND b.cod_dep=a.cod_dep AND b.cod_tipo_nomina=a.cod_tipo_nomina) as devengado
		FROM v_cnmd07_transacciones_actuales_condicion a
		WHERE ".$condicion."  AND a.condicion_actividad_ficha=1 AND a.uso_transaccion!=6 AND a.uso_transaccion!=9 ORDER BY ".$order_by;// AND a.ultimo_recibo!=0

		$datos = $this->v_cnmd07_transacciones_actuales->execute($sql);

		$this->set('datos',$datos);
		$this->set('var',$var);
	}
}//emision_recibos_genericos_22


function emision_recibos_preimpresos_formato1($var=null){
	$this->layout="ajax";
	if($var=='no'){
		$condicion = $this->SQLCA();
		$lista = $this->Cnmd01->generateList($condicion, 'cod_tipo_nomina ASC', null, '{n}.Cnmd01.cod_tipo_nomina', '{n}.Cnmd01.denominacion');
		if($lista!=null){
			$this->concatena($lista, 'lista');
		}else{
			$this->set('lista',array('no'=>'no hay registros'));
		}
		$this->set('var',$var);

	}elseif($var=='si'){
		$this->layout="pdf";
		$tipo_nomina   = $this->data['reporte_personal']['select_tiponomina'];
		$orden_reporte = $this->data['reporte_personal']['ordenado'];
		$rango_recibos = $this->data['reporte_personal']['rango_recibos'];
		$condicion = $this->SQLCA()." AND cod_tipo_nomina='$tipo_nomina'";
 		if($tipo_nomina=='' || $orden_reporte=='' || $rango_recibos==''){
			echo '<script>history.back(1)</script>';
		}
		if($rango_recibos==2){
			$recibos_desde = $this->data['reporte_personal']['recibo_desde'];
			$recibos_hasta = $this->data['reporte_personal']['recibo_hasta'];
			if(empty($recibos_desde) || empty($recibos_hasta)){
				echo '<script>history.back(1)</script>';
			}else{
				$condicion .= " AND a.ultimo_recibo >= '$recibos_desde' AND a.ultimo_recibo <= '$recibos_hasta'";
			}
		}
		switch($orden_reporte){
			case '1': $order_by= " a.ultimo_recibo, a.cedula_identidad"; break;
			case '2': $order_by= " a.cod_dir_superior, a.cod_coordinacion, a.cod_secretaria, a.cod_direccion, a.cod_division, a.cod_departamento, a.cod_oficina, a.ultimo_recibo, a.cedula_identidad"; break;
			case '3': $order_by= " a.cod_estado, a.cod_municipio, a.cod_parroquia, a.cod_centro, a.ultimo_recibo, a.cedula_identidad"; break;
			case '4': $order_by= " a.cod_sector, a.cod_programa, a.cod_sub_prog, a.cod_proyecto, a.cod_activ_obra, a.cod_partida, a.cod_generica, a.cod_especifica, a.cod_sub_espec, a.cod_auxiliar, a.ultimo_recibo, a.cedula_identidad"; break;
			default : $order_by= " a.ultimo_recibo, a.cedula_identidad"; break;
		}

		$sql = "SELECT a.cod_tipo_nomina, a.denominacion_nomina, a.cod_coordinacion, a.deno_cod_coordinacion, a.cod_secretaria, a.deno_cod_secretaria,
		a.cod_direccion, a.deno_cod_direccion, a.cod_division, a.deno_cod_division, a.cod_departamento, a.deno_cod_departamento, a.cod_oficina, a.deno_cod_oficina,
		a.cedula_identidad, a.primer_apellido, a.segundo_apellido, a.primer_nombre, a.segundo_nombre, a.cod_puesto, a.denominacion_puesto, a.cod_cargo, a.cod_ficha, a.sueldo_basico,
		a.fecha_ingreso, a.numero_nomina, a.correspondiente, a.periodo_desde, a.periodo_hasta,
		a.cod_tipo_transaccion, a.cod_transaccion, a.deno_transaccion, a.dias_horas, a.frecuencia_cobro, a.monto_cuota, a.saldo, a.ultimo_recibo,
		a.cod_entidad_bancaria, a.deno_entidades_bancarias, a.cod_sucursal, a.cuenta_bancaria
		FROM v_cnmd07_transacciones_actuales_condicion a
		WHERE ".$condicion." and  a.condicion_actividad_ficha=1 ORDER BY ".$order_by;// AND a.ultimo_recibo!=0

		$datos = $this->v_cnmd07_transacciones_actuales->execute($sql);

		$h=0;
		$k=0;
		$cedula_2 = '';
		$personal = array();// Vector que se llena y envia a la vista ya formateado.
		$vector_datos = array();// Vector auxiliar, se usa para almacenar los datos de cada persona temporalmente, hasta que cambie su cedula de identidad.
		$cantidad_registros=count($datos);
		for($i=0; $i<$cantidad_registros; $i++){
			$cedula_1 = $datos[$i][0]['cedula_identidad'];
			if($cedula_1 != $cedula_2){
				if($i != 0){
					$personal[$h] = $vector_datos;
					$h++;
					$k=0;
					$vector_datos = array();// Se limpia el vector.
				}
			}
			$vector_datos[$k]['apellidos_nombres']   = $datos[$i][0]['primer_apellido']." ".$datos[$i][0]['segundo_apellido']." ".$datos[$i][0]['primer_nombre']." ".$datos[$i][0]['segundo_nombre'];
			$vector_datos[$k]['cedula_identidad']    = $datos[$i][0]['cedula_identidad'];
			$vector_datos[$k]['cod_puesto']          = $datos[$i][0]['cod_puesto'];
			$vector_datos[$k]['denominacion_puesto'] = $datos[$i][0]['denominacion_puesto'];
			$cedula_2 = $datos[$i][0]['cedula_identidad'];
			$k++;
		}
		$personal[$h] = $vector_datos;
		//pr($personal);
		$this->set('datos',$datos);
		$this->set('var',$var);
	}
}//emision_recibos_preimpresos_formato1



function parametros($opcion=null,$ir=null){
	$this->set('opcion',$opcion);
    if($opcion==1){
      $this->set('titulo_reporte_top','Institutos Educativos');
      $tabla = "SELECT cod_institucion as codigo, denominacion FROM cnmd06_instituto_educativo ORDER BY upper(trim(quitar_acentos(denominacion))) ASC;";
    }else if($opcion==2){
      $this->set('titulo_reporte_top','Niveles Educativos');
      $tabla = "SELECT cod_nivel_educativo as codigo, denominacion FROM cnmd06_nivel_educacion ORDER BY upper(trim(quitar_acentos(denominacion))) ASC;";
    }else if($opcion==3){
      $this->set('titulo_reporte_top','Profesiones');
      $tabla = "SELECT cod_profesion as codigo, denominacion FROM cnmd06_profesiones ORDER BY upper(trim(quitar_acentos(denominacion))) ASC;";
    }else if($opcion==4){
      $this->set('titulo_reporte_top','Colegios Profesionales');
      $tabla = "SELECT cod_colegio as codigo, denominacion FROM cnmd06_colegio_profesional ORDER BY upper(trim(quitar_acentos(denominacion))) ASC;";
    }else if($opcion==5){
      $this->set('titulo_reporte_top','Cursos, talleres y entrenamientos');
      $tabla = "SELECT cod_curso as codigo, denominacion FROM cnmd06_cursos ORDER BY upper(trim(quitar_acentos(denominacion))) ASC;";
    }else if($opcion==6){
      $this->set('titulo_reporte_top','Oficios y destrezas');
      $tabla = "SELECT cod_oficio as codigo, denominacion FROM cnmd06_oficio ORDER BY upper(trim(quitar_acentos(denominacion))) ASC;";
    }else if($opcion==7){
      $this->set('titulo_reporte_top','Religiones');
      $tabla = "SELECT cod_religion as codigo, denominacion FROM cnmd06_religiones ORDER BY upper(trim(quitar_acentos(denominacion))) ASC;";
    }else if($opcion==8){
      $this->set('titulo_reporte_top','Clubes');
      $tabla = "SELECT cod_club as codigo, denominacion FROM cnmd06_clubes ORDER BY upper(trim(quitar_acentos(denominacion))) ASC;";
    }else if($opcion==9){
      $this->set('titulo_reporte_top','Deportes');
      $tabla = "SELECT cod_deporte as codigo, denominacion FROM cnmd06_deportes ORDER BY upper(trim(quitar_acentos(denominacion))) ASC;";
    }else if($opcion==10){
      $this->set('titulo_reporte_top','Hobby');
      $tabla = "SELECT cod_hobby as codigo, denominacion FROM cnmd06_hobby ORDER BY upper(trim(quitar_acentos(denominacion))) ASC;";
    }else if($opcion==11){
      $this->set('titulo_reporte_top','Colores');
      $tabla = "SELECT cod_color as codigo, denominacion FROM cnmd06_colores ORDER BY upper(trim(quitar_acentos(denominacion))) ASC;";
    }else if($opcion==12){
      $this->set('titulo_reporte_top','Parentescos');
      $tabla = "SELECT cod_parentesco as codigo, denominacion FROM cnmd06_parentesco ORDER BY upper(trim(quitar_acentos(denominacion))) ASC;";
    }else if($opcion==13){
      $this->set('titulo_reporte_top','Guarderias');
      $tabla = "SELECT cod_guarderia as codigo, denominacion FROM cnmd06_guarderias ORDER BY upper(trim(quitar_acentos(denominacion))) ASC;";
    }else if($opcion==14){
      $this->set('titulo_reporte_top','Tipos de permisos');
      $tabla = "SELECT cod_permiso as codigo, denominacion FROM cnmd06_permisos ORDER BY upper(trim(quitar_acentos(denominacion))) ASC;";
    }else if($opcion==15){
      $this->set('titulo_reporte_top','Tipos de amonestaciones');
      $tabla = "SELECT cod_amonestacion as codigo, denominacion FROM cnmd06_amonestaciones ORDER BY upper(trim(quitar_acentos(denominacion))) ASC;";
    }else if($opcion==16){
      $this->set('titulo_reporte_top','Tipos de bienes');
      $tabla = "SELECT cod_bien as codigo, denominacion FROM cnmd06_bienes ORDER BY upper(trim(quitar_acentos(denominacion))) ASC;";
    }
	if($ir=='no'){
		$this->layout="ajax";
		$this->set('ir','no');

	}else if($ir=='si'){
		$this->layout="pdf";
		$this->set('ir','si');
        $this->set('data',$this->Cnmd01->execute($tabla));
	}


}//fin institutos


function especialidades_profesionales($ir=null){

      $this->set('titulo_reporte_top','Especialidades profesionales');
      $profesion = "SELECT cod_profesion as cod_profesion1, denominacion FROM cnmd06_profesiones ORDER BY upper(trim(quitar_acentos(denominacion))) ASC;";
      $especialidades = "SELECT cod_profesion as cod_profesion2,cod_especialidad,denominacion FROM cnmd06_especialidades ORDER BY upper(trim(quitar_acentos(denominacion))),cod_profesion,cod_especialidad ASC;";
	if($ir=='no'){
		$this->layout="ajax";
		$this->set('ir','no');

	}else if($ir=='si'){
		$this->layout="pdf";
		$this->set('ir','si');
        $this->set('profesion',$this->Cnmd01->execute($profesion));
        $this->set('especialidades',$this->Cnmd01->execute($especialidades));
	}


}//fin especialidades profesionales


function tipo_transacciones($var=null){
	$this->layout="ajax";
     if($var=='si'){
		$this->layout="pdf";
		$this->set('data1',$this->v_cnmd03_transacciones->findAll('cod_tipo_transaccion=1',null,'cod_transaccion ASC'));
		$this->set('data2',$this->v_cnmd03_transacciones->findAll('cod_tipo_transaccion=2',null,'cod_transaccion ASC'));
	}
	$this->set('var',$var);
}

function cnmd03_partidas($var=null){
	$this->layout="ajax";
     if($var=='si'){
		$this->layout="pdf";
		$this->set('transacciones1',$this->v_cnmd03_partidas->execute("SELECT cod_tipo_transaccion,cod_transaccion,deno_transaccion from v_cnmd03_partidas WHERE cod_tipo_transaccion=1 group by cod_tipo_transaccion,cod_transaccion,deno_transaccion order by cod_tipo_transaccion,cod_transaccion ASC"));
		$this->set('transacciones2',$this->v_cnmd03_partidas->execute("SELECT cod_tipo_transaccion,cod_transaccion,deno_transaccion from v_cnmd03_partidas WHERE cod_tipo_transaccion=2 group by cod_tipo_transaccion,cod_transaccion,deno_transaccion order by cod_tipo_transaccion,cod_transaccion ASC"));
		$this->set('data1',$this->v_cnmd03_partidas->findAll('cod_tipo_transaccion=1',null,'cod_tipo_transaccion, cod_transaccion, clasificacion_personal,cod_partida, cod_generica, cod_especifica, cod_sub_espec, cod_auxiliar ASC'));
		$this->set('data2',$this->v_cnmd03_partidas->findAll('cod_tipo_transaccion=2',null,'cod_tipo_transaccion, cod_transaccion, clasificacion_personal,cod_partida, cod_generica, cod_especifica, cod_sub_espec, cod_auxiliar ASC'));
	}
	$this->set('var',$var);
}





function asignacion_cargos($ir=null,$var=null){
	if($ir=='no'){
			$this->layout="ajax";
			$this->set('ir','no');
			$Lista = $this->Cnmd01->generateList($this->SQLCA(), 'cod_tipo_nomina ASC', null, '{n}.Cnmd01.cod_tipo_nomina', '{n}.Cnmd01.denominacion');
   			$this->concatenaN($Lista, 'cod_tipo_nomina');
	}else{
			$this->layout="pdf";
			$this->set('ir','si');
			if(!empty($this->data['cfpp97']['cod_tipo_nomina'])){
				$nomina=$this->data['cfpp97']['cod_tipo_nomina'];
				$sql = "SELECT * FROM v_cnmd06_ficha_frecuencia_conexiones where ".$this->SQLCA()." and cod_tipo_nomina='$nomina' ORDER BY cod_dir_superior, cod_coordinacion, cod_secretaria, cod_direccion, cod_division,cod_departamento, cod_oficina,cod_cargo,cedula,cod_tipo_transaccion,cod_transaccion ASC";
				$this->set('datos',$this->Cnmd01->execute($sql));
			}else{
				$this->set('datos',null);
			}
	}

}// fin asignacion_cargos

function trabajadores_repetidos($ir=null,$var=null){
	if($ir=='no'){
			$this->layout="ajax";
			$this->set('ir','no');

	}else{
			$this->layout="pdf";
			$this->set('ir','si');
			$sql = "SELECT * FROM v_cnmd06_trabajadores_repetidos where ".$this->condicionNDEP()." ORDER BY cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cedula_identidad,cod_dep ASC";
			$this->set('datos',$this->Cnmd01->execute($sql));
//			$this->set('datos',null);

	}

}// fin trabajadores_repetidos



function emision_recibos_preimpresos_1($var=null){
	$this->layout="ajax";

	if($var=='no'){
		$condicion = $this->SQLCA();
		$lista = $this->Cnmd01->generateList($condicion, 'cod_tipo_nomina ASC', null, '{n}.Cnmd01.cod_tipo_nomina', '{n}.Cnmd01.denominacion');
		if($lista!=null){
			$this->concatena($lista, 'lista');
		}else{
			$this->set('lista',array('no'=>'no hay registros'));
		}
		$this->set('var',$var);

	}elseif($var=='si'){
		ini_set("memory_limit","2560M");
		$this->layout="ajax";
		//$this->layout="pdf";
		$tipo_nomina   = $this->data['reporte_personal']['select_tiponomina'];
		$orden_reporte = $this->data['reporte_personal']['ordenado'];
		$rango_recibos = $this->data['reporte_personal']['rango_recibos'];
		$condicion = $this->SQLCA()." AND cod_tipo_nomina='$tipo_nomina'";

 		if($tipo_nomina=='' || $orden_reporte=='' || $rango_recibos==''){
			echo '<script>history.back(1)</script>';
		}

		if($rango_recibos==2){
			$recibos_desde = $this->data['reporte_personal']['recibo_desde'];
			$recibos_hasta = $this->data['reporte_personal']['recibo_hasta'];
			if(empty($recibos_desde) || empty($recibos_hasta)){
				echo '<script>history.back(1)</script>';
			}else{
				$condicion .= " AND a.ultimo_recibo >= '$recibos_desde' AND a.ultimo_recibo <= '$recibos_hasta'";
			}
		}

		switch($orden_reporte){
			case '1': $order_by= " a.ultimo_recibo, a.cedula_identidad"; break;
			case '2': $order_by= " a.cod_dir_superior, a.cod_coordinacion, a.cod_secretaria, a.cod_direccion, a.cod_division, a.cod_departamento, a.cod_oficina, a.cedula_identidad, a.ultimo_recibo"; break;
			case '3': $order_by= " a.cod_estado, a.cod_municipio, a.cod_parroquia, a.cod_centro, a.cedula_identidad, a.ultimo_recibo"; break;
			case '4': $order_by= " a.cod_sector, a.cod_programa, a.cod_sub_prog, a.cod_proyecto, a.cod_activ_obra, a.cod_partida, a.cod_generica, a.cod_especifica, a.cod_sub_espec, a.cod_auxiliar, a.cedula_identidad, a.ultimo_recibo"; break;
			default : $order_by= " a.ultimo_recibo, a.cedula_identidad"; break;
		}

		$sql = "SELECT a.cod_tipo_nomina, a.denominacion_nomina, a.cod_coordinacion, a.deno_cod_coordinacion, a.cod_secretaria, a.deno_cod_secretaria,
		a.cod_direccion, a.deno_cod_direccion, a.cod_division, a.deno_cod_division, a.cod_departamento, a.deno_cod_departamento, a.cod_oficina, a.deno_cod_oficina,
		a.cod_municipio, a.deno_cod_municipio, a.cod_parroquia, a.deno_cod_parroquia, a.cod_centro, a.deno_cod_centro,
		a.cod_sector, a.cod_programa, a.cod_sub_prog, a.cod_activ_obra,
		a.cedula_identidad, a.primer_apellido, a.segundo_apellido, a.primer_nombre, a.segundo_nombre, a.cod_puesto, a.denominacion_puesto, a.cod_cargo, a.cod_ficha, a.sueldo_basico,
		a.fecha_ingreso, a.numero_nomina, a.correspondiente, a.periodo_desde, a.periodo_hasta,
		a.cod_tipo_transaccion, a.cod_transaccion, a.deno_transaccion, a.dias_horas, a.frecuencia_cobro, a.monto_cuota, a.saldo, a.ultimo_recibo,
		a.cod_entidad_bancaria, a.deno_entidades_bancarias, a.cod_sucursal, a.cuenta_bancaria, a.uso_transaccion,
		(SELECT b.denominacion_devengado FROM cnmd01 b WHERE b.cod_presi=a.cod_presi AND b.cod_entidad=a.cod_entidad AND b.cod_tipo_inst=a.cod_tipo_inst AND b.cod_inst=a.cod_inst AND b.cod_dep=a.cod_dep AND b.cod_tipo_nomina=a.cod_tipo_nomina) as devengado
		FROM v_cnmd07_transacciones_actuales_condicion a
		WHERE ".$condicion."  AND a.condicion_actividad_ficha=1 AND a.ultimo_recibo!=0 AND a.uso_transaccion!=6 AND a.uso_transaccion!=9 ORDER BY ".$order_by;// AND a.ultimo_recibo!=0

		$datos = $this->v_cnmd07_transacciones_actuales->execute($sql);

		$this->set('datos',$datos);
		$this->set('var',$var);
	}

}// fin emision_recibos_preimpresos_1


function emision_recibos_preimpresos_2($var=null){
	$this->layout="ajax";

	if($var=='no'){
		$condicion = $this->SQLCA();
		$lista = $this->Cnmd01->generateList($condicion, 'cod_tipo_nomina ASC', null, '{n}.Cnmd01.cod_tipo_nomina', '{n}.Cnmd01.denominacion');
		if($lista!=null){
			$this->concatena($lista, 'lista');
		}else{
			$this->set('lista',array('no'=>'no hay registros'));
		}
		$this->set('var',$var);

	}elseif($var=='si'){
		ini_set("memory_limit","2560M");
		$this->layout="ajax";
		//$this->layout="pdf";
		$tipo_nomina   = $this->data['reporte_personal']['select_tiponomina'];
		$orden_reporte = $this->data['reporte_personal']['ordenado'];
		$rango_recibos = $this->data['reporte_personal']['rango_recibos'];
		$condicion = $this->SQLCA()." AND cod_tipo_nomina='$tipo_nomina'";

 		if($tipo_nomina=='' || $orden_reporte=='' || $rango_recibos==''){
			echo '<script>history.back(1)</script>';
		}

		if($rango_recibos==2){
			$recibos_desde = $this->data['reporte_personal']['recibo_desde'];
			$recibos_hasta = $this->data['reporte_personal']['recibo_hasta'];
			if(empty($recibos_desde) || empty($recibos_hasta)){
				echo '<script>history.back(1)</script>';
			}else{
				$condicion .= " AND a.ultimo_recibo >= '$recibos_desde' AND a.ultimo_recibo <= '$recibos_hasta'";
			}
		}

		switch($orden_reporte){
			case '1': $order_by= " a.ultimo_recibo, a.cedula_identidad"; break;
			case '2': $order_by= " a.cod_dir_superior, a.cod_coordinacion, a.cod_secretaria, a.cod_direccion, a.cod_division, a.cod_departamento, a.cod_oficina, a.cedula_identidad, a.ultimo_recibo"; break;
			case '3': $order_by= " a.cod_estado, a.cod_municipio, a.cod_parroquia, a.cod_centro, a.cedula_identidad, a.ultimo_recibo"; break;
			case '4': $order_by= " a.cod_sector, a.cod_programa, a.cod_sub_prog, a.cod_proyecto, a.cod_activ_obra, a.cod_partida, a.cod_generica, a.cod_especifica, a.cod_sub_espec, a.cod_auxiliar, a.cedula_identidad, a.ultimo_recibo"; break;
			default : $order_by= " a.ultimo_recibo, a.cedula_identidad"; break;
		}

		$sql = "SELECT a.cod_tipo_nomina, a.denominacion_nomina, a.cod_coordinacion, a.deno_cod_coordinacion, a.cod_secretaria, a.deno_cod_secretaria,
		a.cod_direccion, a.deno_cod_direccion, a.cod_division, a.deno_cod_division, a.cod_departamento, a.deno_cod_departamento, a.cod_oficina, a.deno_cod_oficina,
		a.cod_municipio, a.deno_cod_municipio, a.cod_parroquia, a.deno_cod_parroquia, a.cod_centro, a.deno_cod_centro,
		a.cod_sector, a.cod_programa, a.cod_sub_prog, a.cod_activ_obra,
		a.cedula_identidad, a.primer_apellido, a.segundo_apellido, a.primer_nombre, a.segundo_nombre, a.cod_puesto, a.denominacion_puesto, a.cod_cargo, a.cod_ficha, a.sueldo_basico,
		a.fecha_ingreso, a.numero_nomina, a.correspondiente, a.periodo_desde, a.periodo_hasta,
		a.cod_tipo_transaccion, a.cod_transaccion, a.deno_transaccion, a.dias_horas, a.frecuencia_cobro, a.monto_cuota, a.saldo, a.ultimo_recibo,
		a.cod_entidad_bancaria, a.deno_entidades_bancarias, a.cod_sucursal, a.cuenta_bancaria, a.uso_transaccion,
		(SELECT b.denominacion_devengado FROM cnmd01 b WHERE b.cod_presi=a.cod_presi AND b.cod_entidad=a.cod_entidad AND b.cod_tipo_inst=a.cod_tipo_inst AND b.cod_inst=a.cod_inst AND b.cod_dep=a.cod_dep AND b.cod_tipo_nomina=a.cod_tipo_nomina) as devengado
		FROM v_cnmd07_transacciones_actuales_condicion_vision a
		WHERE ".$condicion."  AND a.condicion_actividad_ficha=1 AND a.ultimo_recibo!=0 AND a.uso_transaccion!=6 AND a.uso_transaccion!=9 ORDER BY ".$order_by;// AND a.ultimo_recibo!=0

		$datos = $this->v_cnmd07_transacciones_actuales->execute($sql);

		$this->set('datos',$datos);
		$this->set('var',$var);
	}

}// fin emision_recibos_preimpresos_2


	function manual_desc_cargos_emp($var_dr=null){
		if($var_dr=='no'){
			$this->layout="ajax";
			$this->set('ir','no');
		}else{
			set_time_limit(0);
			ini_set("memory_limit","2560M");
			$this->layout="pdf";
			$this->set('ir','si');
			$sql = "SELECT * FROM v_cnmd02_empleados_rap ORDER BY cod_ramo, cod_grupo, cod_serie, cod_puesto, grado ASC;";
			$datos = $this->Cnmd01->execute($sql);
			$this->set('datos',$datos);
	}
	}

	function manual_desc_cargos_obre($var_dr=null){
		if($var_dr=='no'){
			$this->layout="ajax";
			$this->set('ir','no');
		}else{
			set_time_limit(0);
			ini_set("memory_limit","2560M");
			$this->layout="pdf";
			$this->set('ir','si');
			$sql = "SELECT * FROM v_cnmd02_obreros_rap ORDER BY cod_ramo, cod_grupo, cod_serie, cod_puesto, grado ASC;";
			$datos = $this->Cnmd01->execute($sql);
			$this->set('datos',$datos);
	}
	}

	function listado_alimentacion($var=null){

		if($var==null){
			$this->layout="ajax";
			$meses=array('01'=>'ENERO', '02'=>'FEBRERO','03'=>'MARZO','04'=>'ABRIL','05'=>'MAYO','06'=>'JUNIO','07'=>'JULIO','08'=>'AGOSTO','09'=>'SEPTIEMBRE','10'=>'OCTUBRE','11'=>'NOVIEMBRE','12'=>'DICIEMBRE');
			$this->concatena($meses, 'meses');
			$this->set('pdf','no');
			$this->set('cod_dep',$this->Session->read('SScoddep'));
			$lista = $this->arrd05->generateList('where cod_presi = 1  and cod_entidad =12 and cod_tipo_inst =30 and cod_inst = 12', ' cod_dep ASC', null, '{n}.arrd05.cod_dep', '{n}.arrd05.denominacion');
      if($lista !=null){
          $this->concatena($lista, 'listadependencia');
      }else{
          $this->set('listadependencia','');
      }
			
		}else{
			$ano = $this->data['reporte_personal']['ano'];
			$mes = $this->data['reporte_personal']['mes'];
			if (isset($this->data['reporte_personal']['select_dependencia'])){
        if ($this->data['reporte_personal']['select_dependencia']=="") {
          $cod_dep_set=1;
        }else{
        	$cod_dep_set=$this->data['reporte_personal']['select_dependencia'];
        }
      }else{
        $cod_dep_set=$this->Session->read('SScoddep');
      }

			$condicion=$this->SQLCA_Institucion($ano)." and cod_dep=".$cod_dep_set." and cod_tipo_transaccion=1 and cod_transaccion=200 and estado_laboral!=6";
		
			switch ($mes) {
				case '01':
					$condicion.="and fecha_transaccion between '".$ano."-".$mes."-01' and '".$ano."-".$mes."-31' ";
					break;
				case '02':
					$condicion.="and fecha_transaccion between '".$ano."-".$mes."-01' and '".$ano."-".$mes."-28' ";
					break;
				case '03':
					$condicion.="and fecha_transaccion between '".$ano."-".$mes."-01' and '".$ano."-".$mes."-31' ";
					break;
				case '04':
					$condicion.="and fecha_transaccion between '".$ano."-".$mes."-01' and '".$ano."-".$mes."-30' ";
					break;
				case '05':
					$condicion.="and fecha_transaccion between '".$ano."-".$mes."-01' and '".$ano."-".$mes."-31' ";
					break;
				case '06':
					$condicion.="and fecha_transaccion between '".$ano."-".$mes."-01' and '".$ano."-".$mes."-30' ";
					break;
				case '07':
					$condicion.="and fecha_transaccion between '".$ano."-".$mes."-01' and '".$ano."-".$mes."-31' ";
					break;
				case '08':
					$condicion.="and fecha_transaccion between '".$ano."-".$mes."-01' and '".$ano."-".$mes."-31' ";
					break;
				case '09':
					$condicion.="and fecha_transaccion between '".$ano."-".$mes."-01' and '".$ano."-".$mes."-30' ";
					break;
				case '10':
					$condicion.="and fecha_transaccion between '".$ano."-".$mes."-01' and '".$ano."-".$mes."-31' ";
					break;
				case '11':
					$condicion.="and fecha_transaccion between '".$ano."-".$mes."-01' and '".$ano."-".$mes."-30' ";
					break;
				case '12':
					$condicion.="and fecha_transaccion between '".$ano."-".$mes."-01' and '".$ano."-".$mes."-31' ";
					break;
			}


			set_time_limit(0);
			ini_set("memory_limit","2560M");
			$this->layout="pdf";
			$this->set('pdf','si');
			//cod_dep=1 and cedula_identidad=19760800 and fecha_transaccion>='2021-01-01' and cod_tipo_transaccion=1 and cod_transaccion=200
			$sql = "SELECT cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, primer_nombre || ' ' || segundo_nombre || ' ' || primer_apellido || ' ' || segundo_apellido as nombre_completo, cedula_identidad, cod_tipo_nomina, denominacion_nomina, denominacion_puesto, deno_cod_secretaria, deno_cod_direccion, deno_cod_division, deno_cod_departamento, deno_cod_oficina, condicion, justificacion, clasificacion_personal FROM v_cnmd08_historia_transacciones WHERE ".$condicion." ORDER BY deno_cod_secretaria ASC, deno_cod_direccion ASC, cod_tipo_nomina ASC, cedula_identidad ASC;";
			$permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyz';
			$codigo_certificacion=strtoupper(substr(str_shuffle($permitted_chars), 0, 24));

			$datos = $this->Cnmd01->execute($sql);
			

			$datos_imgs=$this->Cnmd01->execute("SELECT tipo_logo_derecho, tipo_logo_izquierdo, tipo_imagen_sello, tipo_imagen_sello_firma, tipo_imagen_firma FROM cnmd06_constancia_firmante WHERE cod_dep = ".$cod_dep_set);

			$cod_imagen = $this->verifica_SS(1)."_".$this->verifica_SS(2)."_".$this->verifica_SS(3)."_".$this->verifica_SS(4)."_".$cod_dep_set;


			$this->set("cod_imagen", $cod_imagen);
			$this->set('codigo_certificacion',$codigo_certificacion);
			$this->set('datos',$datos);			
	  		$this->set("datos_imgs", $datos_imgs);
	  		$this->set("cod_dep_set", $cod_dep_set);
			$this->set('ano',$ano);
			$this->set('mes',$mes);
		}
	}

	function listado_alimentacion_servicio($var=null){

		if($var==null){
			$this->layout="ajax";
			$meses=array('01'=>'ENERO', '02'=>'FEBRERO','03'=>'MARZO','04'=>'ABRIL','05'=>'MAYO','06'=>'JUNIO','07'=>'JULIO','08'=>'AGOSTO','09'=>'SEPTIEMBRE','10'=>'OCTUBRE','11'=>'NOVIEMBRE','12'=>'DICIEMBRE');
			$this->concatena($meses, 'meses');
			$this->set('pdf','no');
			$this->set('cod_dep',$this->Session->read('SScoddep'));
			$lista = $this->arrd05->generateList('where cod_presi = 1  and cod_entidad =12 and cod_tipo_inst =30 and cod_inst = 12', ' cod_dep ASC', null, '{n}.arrd05.cod_dep', '{n}.arrd05.denominacion');
      if($lista !=null){
          $this->concatena($lista, 'listadependencia');
      }else{
          $this->set('listadependencia','');
      }
			
		}else{
			$ano = $this->data['reporte_personal']['ano'];
			$mes = $this->data['reporte_personal']['mes'];
			if (isset($this->data['reporte_personal']['select_dependencia'])){
        if ($this->data['reporte_personal']['select_dependencia']=="") {
          $cod_dep_set=1;
        }else{
        	$cod_dep_set=$this->data['reporte_personal']['select_dependencia'];
        }
      }else{
        $cod_dep_set=$this->Session->read('SScoddep');
      }

			$condicion=$this->SQLCA_Institucion($ano)." and cod_dep=".$cod_dep_set." and cod_tipo_transaccion=1 and cod_transaccion=255 and estado_laboral=6 ";
		
			switch ($mes) {
				case '01':
					$condicion.="and fecha_transaccion between '".$ano."-".$mes."-01' and '".$ano."-".$mes."-31' ";
					break;
				case '02':
					$condicion.="and fecha_transaccion between '".$ano."-".$mes."-01' and '".$ano."-".$mes."-28' ";
					break;
				case '03':
					$condicion.="and fecha_transaccion between '".$ano."-".$mes."-01' and '".$ano."-".$mes."-31' ";
					break;
				case '04':
					$condicion.="and fecha_transaccion between '".$ano."-".$mes."-01' and '".$ano."-".$mes."-30' ";
					break;
				case '05':
					$condicion.="and fecha_transaccion between '".$ano."-".$mes."-01' and '".$ano."-".$mes."-31' ";
					break;
				case '06':
					$condicion.="and fecha_transaccion between '".$ano."-".$mes."-01' and '".$ano."-".$mes."-30' ";
					break;
				case '07':
					$condicion.="and fecha_transaccion between '".$ano."-".$mes."-01' and '".$ano."-".$mes."-31' ";
					break;
				case '08':
					$condicion.="and fecha_transaccion between '".$ano."-".$mes."-01' and '".$ano."-".$mes."-31' ";
					break;
				case '09':
					$condicion.="and fecha_transaccion between '".$ano."-".$mes."-01' and '".$ano."-".$mes."-30' ";
					break;
				case '10':
					$condicion.="and fecha_transaccion between '".$ano."-".$mes."-01' and '".$ano."-".$mes."-31' ";
					break;
				case '11':
					$condicion.="and fecha_transaccion between '".$ano."-".$mes."-01' and '".$ano."-".$mes."-31' ";
					break;
				case '12':
					$condicion.="and fecha_transaccion between '".$ano."-".$mes."-01' and '".$ano."-".$mes."-31' ";
					break;
			}


			set_time_limit(0);
			ini_set("memory_limit","2560M");
			$this->layout="pdf";
			$this->set('pdf','si');
			//cod_dep=1 and cedula_identidad=19760800 and fecha_transaccion>='2021-01-01' and cod_tipo_transaccion=1 and cod_transaccion=200
			$sql = "SELECT * FROM (SELECT DISTINCT ON (cedula_identidad) cedula_identidad, cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, primer_nombre || ' ' || segundo_nombre || ' ' || primer_apellido || ' ' || segundo_apellido as nombre_completo, cod_tipo_nomina, denominacion_nomina, denominacion_puesto, deno_cod_secretaria, deno_cod_direccion, deno_cod_division, deno_cod_departamento, deno_cod_oficina, condicion, justificacion, clasificacion_personal FROM v_cnmd08_historia_transacciones WHERE ".$condicion." ORDER BY cedula_identidad ASC) as temp ORDER BY deno_cod_secretaria ASC, deno_cod_direccion ASC, cod_tipo_nomina ASC, cedula_identidad ASC;";
			$permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyz';
			$codigo_certificacion=strtoupper(substr(str_shuffle($permitted_chars), 0, 24));
		//var_dump($sql);exit();
			$datos = $this->Cnmd01->execute($sql);
			

			$datos_imgs=$this->Cnmd01->execute("SELECT tipo_logo_derecho, tipo_logo_izquierdo, tipo_imagen_sello, tipo_imagen_sello_firma, tipo_imagen_firma FROM cnmd06_constancia_firmante WHERE cod_dep = ".$cod_dep_set);

			$cod_imagen = $this->verifica_SS(1)."_".$this->verifica_SS(2)."_".$this->verifica_SS(3)."_".$this->verifica_SS(4)."_".$this->verifica_SS(5);


			$this->set("cod_imagen", $cod_imagen);
			$this->set('codigo_certificacion',$codigo_certificacion);
			$this->set('datos',$datos);			
	  		$this->set("datos_imgs", $datos_imgs);
	  		$this->set("cod_dep_set", $cod_dep_set);
			$this->set('ano',$ano);
			$this->set('mes',$mes);
		}
	}

	function listado_personal_genero($var=null){

		if($var==null){
			$this->layout="ajax";
			$this->set('pdf','no');
		}else{
			$tipo_busqueda = $this->data['reporte_personal']['tipo_busqueda'];
			
			$condicion=$this->SQLCA()." and condicion_actividad_ficha=1 ";
		
			switch ($tipo_busqueda) {
				case '1':
					$condicion.="";
					break;
				case '2':
					$condicion.="and sexo='M' ";
					break;
				case '3':
					$condicion.="and sexo='F' ";
					break;
			}


			set_time_limit(0);
			ini_set("memory_limit","2560M");
			$this->layout="pdf";
			$this->set('pdf','si');
			//cod_dep=1 and cedula_identidad=19760800 and fecha_transaccion>='2021-01-01' and cod_tipo_transaccion=1 and cod_transaccion=200
			$sql = "SELECT cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, primer_nombre || ' ' || segundo_nombre || ' ' || primer_apellido || ' ' || segundo_apellido as nombre_completo, cedula_identidad, cod_tipo_nomina, denominacion_nomina, demonimacion_puesto, deno_cod_secretaria, deno_cod_direccion, sexo FROM v_cnmd06_fichas WHERE ".$condicion." ORDER BY deno_cod_secretaria ASC, deno_cod_direccion ASC, sexo ASC, cod_tipo_nomina ASC, cedula_identidad ASC;";
			$permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyz';
			$codigo_certificacion=strtoupper(substr(str_shuffle($permitted_chars), 0, 24));

			$datos = $this->Cnmd01->execute($sql);

			$datos_imgs=$this->Cnmd01->execute("SELECT tipo_logo_derecho, tipo_logo_izquierdo, tipo_imagen_sello, tipo_imagen_sello_firma, tipo_imagen_firma FROM cnmd06_constancia_firmante WHERE cod_dep = ".$this->verifica_SS(5));

			$cod_imagen = $this->verifica_SS(1)."_".$this->verifica_SS(2)."_".$this->verifica_SS(3)."_".$this->verifica_SS(4)."_".$this->verifica_SS(5);

			$this->set("cod_imagen", $cod_imagen);
			$this->set('codigo_certificacion',$codigo_certificacion);
			$this->set('datos',$datos);			
  		$this->set("datos_imgs", $datos_imgs);
			$this->set('tipo_busqueda',$tipo_busqueda);
		}
	}

	function listado_alimentacion_apoyo($var=null){

		if($var==null){
			$this->layout="ajax";
			$meses=array('01'=>'ENERO', '02'=>'FEBRERO','03'=>'MARZO','04'=>'ABRIL','05'=>'MAYO','06'=>'JUNIO','07'=>'JULIO','08'=>'AGOSTO','09'=>'SEPTIEMBRE','10'=>'OCTUBRE','11'=>'NOVIEMBRE','12'=>'DICIEMBRE');
			$this->concatena($meses, 'meses');
			$this->set('pdf','no');
			$this->set('cod_dep',$this->Session->read('SScoddep'));
			$lista = $this->arrd05->generateList('where cod_presi = 1  and cod_entidad =12 and cod_tipo_inst =30 and cod_inst = 12', ' cod_dep ASC', null, '{n}.arrd05.cod_dep', '{n}.arrd05.denominacion');
      if($lista !=null){
          $this->concatena($lista, 'listadependencia');
      }else{
          $this->set('listadependencia','');
      }
			
		}else{
			$ano = $this->data['reporte_personal']['ano'];
			$mes = $this->data['reporte_personal']['mes'];
			if (isset($this->data['reporte_personal']['select_dependencia'])){
        if ($this->data['reporte_personal']['select_dependencia']=="") {
          $cod_dep_set=1;
        }else{
        	$cod_dep_set=$this->data['reporte_personal']['select_dependencia'];
        }
      }else{
        $cod_dep_set=$this->Session->read('SScoddep');
      }

			$condicion=$this->SQLCA_Institucion()." and cod_dep=".$cod_dep_set;
		
			set_time_limit(0);
			ini_set("memory_limit","2560M");
			$this->layout="pdf";
			$this->set('pdf','si');
			
			$sql = "SELECT cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, primer_nombre || ' ' || segundo_nombre || ' ' || primer_apellido || ' ' || segundo_apellido as nombre_completo, cedula_identidad, funcion FROM cnmd20_alimentacion_apoyo_institucional WHERE ".$condicion." ORDER BY cedula_identidad ASC;";
			$permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyz';
			$codigo_certificacion=strtoupper(substr(str_shuffle($permitted_chars), 0, 24));

			$datos = $this->Cnmd01->execute($sql);
			$dependencia = $this->Cnmd01->execute("SELECT * FROM WHERE cod_presi = 1  and cod_entidad =12 and cod_tipo_inst =30 and cod_inst = 12 and cod_dep=".$cod_dep_set);
			

			$datos_imgs=$this->Cnmd01->execute("SELECT tipo_logo_derecho, tipo_logo_izquierdo, tipo_imagen_sello, tipo_imagen_sello_firma, tipo_imagen_firma FROM cnmd06_constancia_firmante WHERE cod_dep = ".$cod_dep_set);

			$cod_imagen = $this->verifica_SS(1)."_".$this->verifica_SS(2)."_".$this->verifica_SS(3)."_".$this->verifica_SS(4)."_".$this->verifica_SS(5);


			$this->set("cod_imagen", $cod_imagen);
			$this->set('codigo_certificacion',$codigo_certificacion);
			$this->set('datos',$datos);			
  		$this->set("datos_imgs", $datos_imgs);
	  		$this->set("cod_dep_set", $cod_dep_set);
			$this->set('ano',$ano);
			$this->set('mes',$mes);
			$this->set('dependencia',$dependencia[0][0]['denominacion']);			
		}
	}

 }//fin class
?>
