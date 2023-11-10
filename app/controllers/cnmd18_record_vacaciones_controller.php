<?php
class Cnmd18RecordVacacionesController extends AppController
{
	var $uses = array('v_cnmd06_fichas_2', 'cnmd18_record_vacaciones', 'cugd02_institucion', 'cnmd06_constancia_firmante', 'v_cnmd06_fichas_datos_personales');
    var $helpers = array('Html', 'Javascript', 'Ajax', 'Sisap', 'Fpdf', 'Infogob');

	
	
	function beforeFilter(){
		$this->checkSession();
	}

	function checkSession(){
		if (!$this->Session->check('infogobierno')){
			$this->redirect('/infogobierno/');
			exit();
		}else{
			$c1=substr_count (strtoupper($_SESSION['infogobierno']['cedula_identidad']), 'J');
			$c2=substr_count (strtoupper($_SESSION['infogobierno']['cedula_identidad']), 'G');
			if($c1!=0 || $c2!=0){
				echo "<script type=\"text/javascript\" language=\"javascript\">error('Por favor registrese cómo persona natural para acceder a la información');</script>";
                exit();
			}
		}
	}

	public function index(){
		$this->layout="ajax";
		$cedula = $_SESSION['infogobierno']['cedula_identidad'];
		$nominas = $this->v_cnmd06_fichas_2->findAll("cedula_identidad=$cedula",'denominacion_dependencia, cod_dep, cod_tipo_nomina, denominacion_nomina, primer_apellido, primer_nombre');
	
		if(count($nominas)!=0){
			foreach($nominas as $n){
				$cod[] = $n['v_cnmd06_fichas_2']['cod_dep'];
				$deno[] = mascara_tres($n['v_cnmd06_fichas_2']['cod_tipo_nomina']).' - '.strtoupper($n['v_cnmd06_fichas_2']['denominacion_dependencia']).' - '.strtoupper($n['v_cnmd06_fichas_2']['denominacion_nomina']);
			}
			$lista=array_combine($cod, $deno);
		}else{
			$lista=array('0'=>'No se encuentra presente en ninguna nomina');
		}
		$this->set('lista',$lista);
		$this->set('nominas_trabajador',$nominas);
	}

	public function consulta_records(){
		/**
		*  Consulta para obtener informacion relacionada a datos personales y ficha del trabajador
		**/
		$data = $this->cnmd18_record_vacaciones->execute(
								"SELECT * FROM v_cnmd06_fichas_2 
								WHERE 
									cedula_identidad= " . $_SESSION['infogobierno']['cedula_identidad']. " 
									and cod_dep = ".$this->data['cnmd18_record_vacaciones']['cod_dep']." 
									ORDER BY fecha_ingreso DESC, fecha_condicion ASC;");

		/**
		*  Consulta para obtener el record de vacaciones del trabajador
		**/

		$records = $this->cnmd18_record_vacaciones->findAll("cedula_identidad = " . $_SESSION['infogobierno']['cedula_identidad'] . " and cod_dep = ".$this->data['cnmd18_record_vacaciones']['cod_dep'], null, "periodo_inicio ASC");

		
		$this->set('records', $records);
		$this->set('data', $data);
	}

	public function generar_record_vacaciones(){

		$this->layout = "pdf";

		$aleatorio=intval(rand());

		/**
		*  Consulta para obtener informacion relacionada a datos personales y ficha del trabajador
		**/

		$data = $this->cnmd18_record_vacaciones->execute(
                                "SELECT * FROM v_cnmd06_fichas_2 
                                WHERE cedula_identidad = " . $_SESSION['infogobierno']['cedula_identidad']." and 
                                cod_dep = ".$this->data['cnmd18_record_vacaciones']['cod_dep']."
                                ORDER BY fecha_ingreso DESC, fecha_condicion ASC;");

		//$data = $this->v_cnmd06_fichas_2->findAll("cedula_identidad= " . $_SESSION['infogobierno']['cedula_identidad'] . " and cod_dep = ".$this->data['cnmd18_record_vacaciones']['cod_dep']);

		/**
		*  Consulta para obtener el record de vacaciones del trabajador
		**/

		$records = $this->cnmd18_record_vacaciones->findAll("cedula_identidad = " . $_SESSION['infogobierno']['cedula_identidad'] . " and cod_dep = ".$this->data['cnmd18_record_vacaciones']['cod_dep'], null, "periodo_inicio ASC");

		/**
		* Consulta para obtener denominacion de la institucion
		**/
		
		if($this->data['cnmd18_record_vacaciones']['cod_dep'] != '1'){
			$deno_inst = $this->cugd02_institucion->execute("SELECT denominacion, direccion, cod_area, telefonos FROM cugd02_dependencias WHERE cod_tipo_institucion = ".$data[0][0]['cod_tipo_inst']." and cod_institucion = ".$data[0][0]['cod_inst']." and cod_dependencia = ".$data[0][0]['cod_dep']." LIMIT 1;");
		}else{
			$deno_inst = $this->cugd02_institucion->execute("SELECT denominacion, direccion, cod_area, telefonos FROM cugd02_institucion WHERE cod_tipo_institucion = ".$data[0][0]['cod_tipo_inst']." and cod_institucion = ".$data[0][0]['cod_inst']." LIMIT 1;");
		}

		/**
		* Consulta para saber quien es Director(a) de RRHH
		**/

		$datos_firma = $this->cnmd06_constancia_firmante->findAll("cod_dep = ".$data[0][0]['cod_dep'],'funcionario_firmante, cargo_firmante, resolucion', null, 1);
		

		$cod_defecto = $this->cugd02_institucion->execute("SELECT cod_republica, cod_estado, cod_municipio FROM cugd90_municipio_defecto WHERE cod_dep = ".$data[0][0]['cod_dep']." LIMIT 1;");

		$republica = $this->cugd02_institucion->execute("SELECT denominacion FROM cugd01_republica WHERE cod_republica=".$cod_defecto[0][0]['cod_republica']." LIMIT 1;");
		$estado = $this->cugd02_institucion->execute("SELECT denominacion FROM cugd01_estados WHERE cod_republica=".$cod_defecto[0][0]['cod_republica']." and cod_estado=".$cod_defecto[0][0]['cod_estado']." LIMIT 1;");
		$cod_zona = $this->cugd02_institucion->execute("SELECT conocido, zona_postal FROM cugd01_municipios WHERE cod_republica=".$cod_defecto[0][0]['cod_republica']." and cod_estado=".$cod_defecto[0][0]['cod_estado']." and cod_municipio=".$cod_defecto[0][0]['cod_municipio']." LIMIT 1;");

		$datos_constancia = $this->v_cnmd06_fichas_datos_personales->findAll(" cod_dep = ".$data[0][0]['cod_dep']." and cedula_identidad='".$_SESSION['infogobierno']['cedula_identidad']."'",'cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, tipo_nomina, cedula_identidad, nacionalidad, primer_apellido, segundo_apellido, primer_nombre, segundo_nombre, denominacion_clase, fecha_ingreso, sexo', null, 1);

	  	$datos_imgs=$this->cnmd06_constancia_firmante->execute("SELECT tipo_logo_derecho, tipo_logo_izquierdo, tipo_imagen_sello, tipo_imagen_sello_firma, tipo_imagen_firma FROM cnmd06_constancia_firmante WHERE cod_dep = ".$this->data['cnmd18_record_vacaciones']['cod_dep']. " and ".$aleatorio."=".$aleatorio);

		$cod_imagen = $datos_constancia[0]['v_cnmd06_fichas_datos_personales']['cod_presi']."_".$datos_constancia[0]['v_cnmd06_fichas_datos_personales']['cod_entidad']."_".$datos_constancia[0]['v_cnmd06_fichas_datos_personales']['cod_tipo_inst']."_".$datos_constancia[0]['v_cnmd06_fichas_datos_personales']['cod_inst']."_".$datos_constancia[0]['v_cnmd06_fichas_datos_personales']['cod_dep'];


		$this->set("cod_imagen", $cod_imagen);
	  	$this->set("datos_imgs", $datos_imgs);
		$this->set("deno_inst", $deno_inst);
		$this->set("cod_zona", $cod_zona);
		$this->set("republica", $republica);
		$this->set("estado", $estado);
		$this->set("datos_firma", $datos_firma);

		$this->set('records', $records);
		$this->set('data', $data);
	}
}