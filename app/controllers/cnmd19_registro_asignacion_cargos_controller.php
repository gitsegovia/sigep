<?php
/**
 * Controlador encargado de realizar todo el proceso relacionado a la asignación de cargos
 * Registro de Asignación de Cargos (RAC)
 * 
 * @author Alberto Veliz (velizweb@gmail.com)
 * @version 1.0
 */
class Cnmd19RegistroAsignacionCargosController extends AppController
{
	var $name = 'cnmd19_registro_asignacion_cargos';

	var $uses = array('v_cfpd05_denominaciones','arrd05');

  	var $helpers = array('Html','Ajax','Javascript', 'Sisap');
	
	function checkSession(){
		if (!$this->Session->check('Usuario')){
			$this->redirect('/salir/');
			exit();
		}else{
			$this->requestAction('/usuarios/actualizar_user');
		}
	}

	/**
	 * Funcion que permite leer las varibles de session. 
	 * la cual permite capturar los codigos del usuario
	 *  para ser insertados en todas las tablas.
	 *  
	 * @param  Integer $i Variable session a verificar
	 * @return Interger   Valor relacionado a la variable sesion consultada
	 */
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

		}
	}

	/**
	 * sql para busqueda de codigos de arranque con y sin año
	 * @param Integer $ano Año por el cual se va a realizar la consulta
	 */
	function SQLCA($ano=null){
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
	}

	/**
	 * Metodo encargado de verifcar si la sesion ha sido iniciada
	 */

	function beforeFilter(){
		$this->checkSession();
	}

	public function index()
	{
		$this->layout="ajax";
		$data =  $this->v_cfpd05_denominaciones->execute("select * from cnmd19_registro_asignacion_cargos where ". $this->SQLCA() .' order by descripcion_cargo ASC');
		$this->set('data', $data);
	}

	public function create()
	{
		
	}

	public function store()
	{	
		$this->layout = "ajax";
		$d1 = $this->verifica_SS(1);
		$d2 = $this->verifica_SS(2);
	 	$d3 = $this->verifica_SS(3);
	 	$d4 = $this->verifica_SS(4);
	 	$d5 = $this->verifica_SS(5);
	 	$ano = date('Y');
		$tipo = $this->data['tipo'];
		$desc = $this->data['descripcion'];
		$cedula = $this->data['cedula'];
		$nom1 = $this->data['nombre1'];
		$nom2 = $this->data['nombre2'];
		$ape1 = $this->data['apellido1'];
		$ape2 = $this->data['apellido2'];
		$genero = $this->data['genero'];
		$grado = $this->data['grado'];
		$clase = $this->data['clase'];
		$fi=$this->data['cnmd19']['fecha_ingreso'];
		$fi=$fi==""?"01/01/1900":$fi;
		$fechaI=$fi;
		//$fechaI = date('Y-m-d', strtotime($this->data['cnmd19']['fecha_ingreso']));
		$fa=$this->data['cnmd19']['fecha_ascenso'];
		$fa=$fa==""?"01/01/1900":$fa;
		$fechaA=$fa;
		//$fechaA = date('Y-m-d', strtotime($this->data['cnmd19']['fecha_ascenso']));
		$anoS = $this->data['ano_servicio'];
		$anoG = $this->data['ano_grado'];
		$paso = $this->data['paso'];
		$numero_cargo = $this->data['numero_cargo'];
		$su_ba_men =  $this->Formato1($this->data['su_ba_men']);
		$mon_anu_su_ba =  $this->Formato1($this->data['mon_anu_su_ba']);
		$con_men =  $this->Formato1($this->data['con_men']);
		$com_anu =  $this->Formato1($this->data['com_anu']);

		

		$campos = "cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, ano, tipo_cargo, descripcion_cargo, codigo_clase, grado, paso, numero_cargos, sueldo_basico_mensual, monto_anual_sueldo_basico, cedula_identidad, primer_apellido, segundo_apellido, primer_nombre, segundo_nombre, genero, fecha_ingreso, anos_servicios, fecha_ascenso, anos_grado, compensacion_mensual, compensacion_anual";
  		

  		$sql = "INSERT INTO cnmd19_registro_asignacion_cargos ($campos) VALUES ($d1, $d2, $d3, $d4, $d5, $ano, $tipo,'$desc', '$clase', $grado, $paso, $numero_cargo, $su_ba_men, $mon_anu_su_ba, $cedula, '$ape1', '$ape2', '$nom1', '$nom2', '$genero', '".$fechaI."', $anoS, '".$fechaA."', $anoG, $con_men, $com_anu)";

  		$resp = $this->v_cfpd05_denominaciones->execute($sql);

  		if (empty($resp)) {
  			$this->set('Message_existe', 'Los datos fueron  guardados');
  		} else {
  			$this->set('errorMessage', 'Los datos no fueron guardados');
  		}
  		$this->create();
        $this->render('create');
	}

	public function edit($ced, $tipo)
	{
		$this->layout="ajax";
		if($tipo == 1){
			$data =  $this->v_cfpd05_denominaciones->execute("SELECT * FROM cnmd19_registro_asignacion_cargos WHERE " . $this->SQLCA() . " and cedula_identidad = ".$ced);
			$this->set('data', array_pop($data));
			$this->set('types', $this->types());
		} else {
			$tipo = $this->data['tipo_cargo'];
			$desc = $this->data['descripcion_cargo'];
			$cedula = $this->data['cedula_identidad'];
			$nom1 = $this->data['primer_nombre'];
			$nom2 = $this->data['segundo_nombre'];
			$ape1 = $this->data['primer_apellido'];
			$ape2 = $this->data['segundo_apellido'];
			$genero = $this->data['genero'];
			$grado = $this->data['grado'];
			$clase = $this->data['codigo_clase'];
			$fechaI=$this->data['cnmd19']['fecha_ingreso'];
			$fechaA=$this->data['cnmd19']['fecha_ascenso'];
			$anoS = $this->data['anos_servicios'];
			$anoG = $this->data['anos_grado'];
			$paso = $this->data['paso'];
			$numero_cargos = $this->data['numero_cargos'];
			$su_ba_men =  $this->Formato1($this->data['sueldo_basico_mensual']);
			$mon_anu_su_ba =  $this->Formato1($this->data['monto_anual_sueldo_basico']);
			$con_men =  $this->Formato1($this->data['compensacion_mensual']);
			$com_anu =  $this->Formato1($this->data['compensacion_anual']);			
			

			$sql = "UPDATE cnmd19_registro_asignacion_cargos 
				SET tipo_cargo = {$tipo}, 
					descripcion_cargo = '{$desc}', 
					codigo_clase = '{$clase}', 
					grado = {$grado}, 
					paso = {$paso}, 
					numero_cargos = {$numero_cargos}, 
					sueldo_basico_mensual = {$su_ba_men}, 
					monto_anual_sueldo_basico = {$mon_anu_su_ba}, 
					cedula_identidad = {$cedula}, 
					primer_apellido = '{$ape1}', 
					segundo_apellido = '{$ape2}', 
					primer_nombre = '{$nom1}', 
					segundo_nombre = '{$nom2}', 
					genero = '{$genero}', 
					fecha_ingreso = '{$fechaI}', 
					anos_servicios = {$anoS}, 
					fecha_ascenso = '{$fechaA}', 
					anos_grado = {$anoG}, 
					compensacion_mensual = {$con_men}, 
					compensacion_anual = {$com_anu} 
				WHERE " . $this->SQLCA() . " 
					and cedula_identidad = {$ced}";
			
			$resp = $this->v_cfpd05_denominaciones->execute($sql);
		
			$data =  $this->v_cfpd05_denominaciones->execute("SELECT * FROM cnmd19_registro_asignacion_cargos WHERE " . $this->SQLCA() . " and cedula_identidad = ".$ced);
			$this->set('data', array_pop($data));
			$this->set('types', $this->types());
			
			if(empty($resp)){
				$this->set('Message_existe', 'Operaci&oacute;n realizada con exito.');
			} else {
	  			$this->set('errorMessage', 'No se ralizao la operaci&oacute;n con exito.');
			}
			
		}
	}

	public function delete($ced, $tipo)
	{
		$resp = $this->v_cfpd05_denominaciones->execute("DELETE FROM cnmd19_registro_asignacion_cargos WHERE " . $this->SQLCA() . " AND tipo_cargo = $tipo AND  cedula_identidad = $ced");
		if(empty($resp)){
			$this->set('Message_existe', 'Operaci&oacute;n realizada con exito.');
			$this->set('ir', 'no');
		} else {
			$this->set('errorMessage', 'No se ralizo la operaci&oacute;n con exito.');
		}

		$this->index();
		$this->render("index");
	}
	
	public function report()
	{
		$this->layout="pdf";
		$unid = $this->arrd05->findAll('cod_dep = '.$this->verifica_SS(5), 'denominacion', null, null);
		$deno = $this->v_cfpd05_denominaciones->execute("SELECT deno_sector, deno_programa, deno_activ_obra FROM v_cfpd05_denominaciones WHERE " . $this->SQLCA() . " LIMIT 1");
		$data =  $this->v_cfpd05_denominaciones->execute("select * from cnmd19_registro_asignacion_cargos where ". $this->SQLCA() .' order by descripcion_cargo ASC');
		$this->set('unid', $unid);
		$this->set('deno', $deno);
		$this->set('data', $data);
	}

	public function types()
	{
		return array(
			"0" => "Seleccione el Tipo",
			"1" => "Contratado",
			"2" => "Fijo",
			"3" => "Libre Nombramiento",
			"4" => "Obrero Clasificado",
			"5" => "Obrero no Clasificado"
		);
	}
}