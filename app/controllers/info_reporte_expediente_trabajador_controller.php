<?php
class InfoReporteExpedienteTrabajadorController extends AppController {
   var $name = 'info_reporte_expediente_trabajador';
   var $uses = array('cnmd06_datos_personales', 'datos_personales_super_busqueda');
   var $helpers = array('Html','Ajax','Javascript', 'Sisap','Infogob');


 function checkSession(){
	if (!$this->Session->check('infogobierno')){
	$this->redirect('/infogobierno/salir_todo');
	exit();
	}else{
		            $c1=substr_count (strtoupper($_SESSION['infogobierno']['cedula_identidad']), 'J');
					$c2=substr_count (strtoupper($_SESSION['infogobierno']['cedula_identidad']), 'G');
					if($c1!=0 || $c2!=0){
						echo "<script type=\"text/javascript\" language=\"javascript\">error('Por favor registrese cómo persona natural para acceder a la información');</script>";
                        exit();
					}
	}
 }//fin checksession


 function beforeFilter(){
 	$this->checkSession();
 }


 function index(){
 	$this->layout="ajax";
 	$cedula = $_SESSION['infogobierno']['cedula_identidad'];
    $datos_filas=$this->datos_personales_super_busqueda->findAll("cedula_identidad=".$cedula,null,"primer_nombre,primer_apellido ASC",100,1,null);
    $this->set("datosFILAS",$datos_filas);
 }


 function buscar_por_pista(){
	$this->layout="ajax";
	$cedula = $_SESSION['infogobierno']['cedula_identidad'];
    $datos_filas=$this->datos_personales_super_busqueda->findAll("cedula_identidad=".$cedula,null,"primer_nombre,primer_apellido ASC",100,1,null);
    $this->set("datosFILAS",$datos_filas);
 }//buscar_por_pista


 function reporte_expediente_trabajador($cedula=null){
	$this->layout="pdf";
	$cedula = $_SESSION['infogobierno']['cedula_identidad'];
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
	(SELECT curso.denominacion FROM cnmd06_cursos curso WHERE curso.cod_curso=a.cod_curso) as curso,
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





}//fin class
?>