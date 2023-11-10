<?php
 class Cdvp01DatosPersonalesvisController extends AppController {
     var $name = 'cdvp01_datos_personalesvis';
     var $uses = array('cdvd01_datos_personales','cdvd01_visitas','cugd02_direccionsuperior','cugd02_coordinacion','cugd02_secretaria','cugd02_direccion');
     var $helpers = array('Html','Ajax','Javascript', 'Sisap');

	function checkSession(){
		if (!$this->Session->check('Usuario')){
				$this->redirect('/salir/');
				exit();
		}else{
			$this->requestAction('/usuarios/actualizar_user');
		}
	}//fin checksession

	function beforeFilter(){
		$this->checkSession();
	}

 function verifica_SS($i){
    	/**
    	 * Funcion que permite leer las varibles de session. la cual permite capturar los codigos del usuario para ser insertados en todas las tablas.
    	 * */
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

	// Funcion SQL para busqueda de codigos de arranque con y sin año
		function SQLCODIN($ano=null){
			$sql_re = "cod_presi=".$this->verifica_SS(1)." and ";
			$sql_re .= "cod_entidad=".$this->verifica_SS(2)." and ";
			$sql_re .= "cod_tipo_inst=".$this->verifica_SS(3)." and ";
			$sql_re .= "cod_inst=".$this->verifica_SS(4)." and ";
			if($ano!=null){
				$sql_re .= "cod_dep=".$this->verifica_SS(5)." and ";
				$sql_re .= "ano=".$ano." ";
			}else{
				$sql_re .= "cod_dep=".$this->verifica_SS(5)." ";
			}
			return $sql_re;
		}//fin funcion SQLCA




// RECORDAR KITARRRRRRRR DE AKI LA SIG FUNCTION RENE:

	function diseno_imputacion(){
		$this->layout = "ajax";
	}






function index($var = null){
	$this->layout = "ajax";
	$this->set('entidad_federal', $this->Session->read('entidad_federal'));
   	$cond_instituc = "cod_tipo_institucion=".$this->verifica_SS(3)." and cod_institucion=".$this->verifica_SS(4)." and cod_dependencia=".$this->verifica_SS(5);
   	$cod_dirsuperior =  $this->cugd02_direccionsuperior->generateList($cond_instituc, 'cod_dir_superior ASC', null, '{n}.cugd02_direccionsuperior.cod_dir_superior', '{n}.cugd02_direccionsuperior.denominacion');
    $this->concatena($cod_dirsuperior, 'cod_dir_superior');
	if($var!=null){ $this->set('cedula',$var); }
	else $this->set('cedula',"");
} // fin Funcion index


// select para obtener la ubicacion administrativa:
function select($select=null,$var=null) {
	$this->layout = "ajax";
if(isset($var) && !empty($var) && $var!=''){
    $cond ="cod_tipo_institucion=".$this->verifica_SS(3)." and cod_institucion=".$this->verifica_SS(4)." and cod_dependencia=".$this->verifica_SS(5);
	switch($select){
		case 'coordinacion':
		  $this->set('SELECT','secretaria');
		  $this->set('codigo','coordinacion');
		  $this->set('seleccion','');
		  $this->set('n',2);
		  $this->Session->write('cod_1',$var);
		  $cond .= " and cod_dir_superior=".$var;
		  $lista = $this->cugd02_coordinacion->generateList($cond, 'cod_coordinacion ASC', null, '{n}.cugd02_coordinacion.cod_coordinacion', '{n}.cugd02_coordinacion.denominacion');
          if($lista!=null){
          	$this->concatena($lista, 'vector');
          }else{
          	$this->set('vector',array());
          }
		break;
		case 'secretaria':
		  $this->set('SELECT','direccion');
		  $this->set('codigo','secretaria');
		  $this->set('seleccion','');
		  $this->set('n',3);
		  $cod_1 = $this->Session->read('cod_1');
		  $this->Session->write('cod_2',$var);
		  $cond .= " and cod_dir_superior=".$cod_1." and cod_coordinacion=".$var;
		  $lista = $this->cugd02_secretaria->generateList($cond, 'cod_secretaria ASC', null, '{n}.cugd02_secretaria.cod_secretaria', '{n}.cugd02_secretaria.denominacion');
          if($lista!=null){
          	$this->concatena($lista, 'vector');
          }else{
          	$this->set('vector',array());
          }
		break;
		case 'direccion':
		  $this->set('SELECT','division');
		  $this->set('codigo','direccion');
		  $this->set('seleccion','');
		  $this->set('n',4);
		  $cod_1 = $this->Session->read('cod_1');
		  $cod_2 = $this->Session->read('cod_2');
		  $this->Session->write('cod_3',$var);
		  $cond .= " and cod_dir_superior=".$cod_1." and cod_coordinacion=".$cod_2." and  cod_secretaria=".$var;
		  $lista = $this->cugd02_direccion->generateList($cond, 'cod_direccion ASC', null, '{n}.cugd02_direccion.cod_direccion', '{n}.cugd02_direccion.denominacion');
          if($lista!=null){
          	$this->concatena($lista, 'vector');
          }else{
          	$this->set('vector',array());
          }
		break;
		/* case 'division':
		  $this->set('SELECT','departamento');
		  $this->set('codigo','division');
		  $this->set('seleccion','');
		  $this->set('n',5);
		  $cod_1 =  $this->Session->read('cod_1');
		  $cod_2 =  $this->Session->read('cod_2');
		  $cod_3 =  $this->Session->read('cod_3');
		  $this->Session->write('cod_4',$var);

		  $cond .=" and cod_dir_superior=".$cod_1." and cod_coordinacion=".$cod_2." and  cod_secretaria=".$cod_3." and cod_direccion=".$var;
		  $lista=  $this->cugd02_division->generateList($cond, 'cod_division ASC', null, '{n}.cugd02_division.cod_division', '{n}.cugd02_division.denominacion');
          if($lista!=null){
          	$this->concatena($lista, 'vector');
          }else{
          	$this->set('vector',array());
          }
		break;
		case 'departamento':
		  $this->set('SELECT','oficina');
		  $this->set('codigo','departamento');
		  $this->set('seleccion','');
		  $this->set('n',6);
		  $cod_1 =  $this->Session->read('cod_1');
		  $cod_2 =  $this->Session->read('cod_2');
		  $cod_3 =  $this->Session->read('cod_3');
		  $cod_4 =  $this->Session->read('cod_4');
		  $this->Session->write('cod_5',$var);
		  $cond .=" and cod_dir_superior=".$cod_1." and cod_coordinacion=".$cod_2." and  cod_secretaria=".$cod_3." and cod_direccion=".$cod_4." and cod_division=".$var;
		  $lista=  $this->cugd02_departamento->generateList($cond, 'cod_departamento ASC', null, '{n}.cugd02_departamento.cod_departamento', '{n}.cugd02_departamento.denominacion');
          if($lista!=null){
          	$this->concatena($lista, 'vector');
          }else{
          	$this->set('vector',array());
          }
		break;
		case 'oficina':
		  $this->set('SELECT','oficina');
		  $this->set('codigo','oficina');
		  $this->set('seleccion','');
		  $this->set('n',7);
		  $this->set('no','no');
		  $cod_1 =  $this->Session->read('cod_1');
		  $cod_2 =  $this->Session->read('cod_2');
		  $cod_3 =  $this->Session->read('cod_3');
		  $cod_4 =  $this->Session->read('cod_4');
		  $cod_5 =  $this->Session->read('cod_5');
		  $this->Session->write('cod_6',$var);
		  $cond .=" and cod_dir_superior=".$cod_1." and cod_coordinacion=".$cod_2." and  cod_secretaria=".$cod_3." and cod_direccion=".$cod_4." and cod_division=".$cod_5." and cod_departamento=".$var;
		  $lista=  $this->cugd02_oficina->generateList($cond, 'cod_oficina ASC', null, '{n}.cugd02_oficina.cod_oficina', '{n}.cugd02_oficina.denominacion');
          if($lista!=null){
          	$this->concatena($lista, 'vector');
          }else{
          	$this->set('vector',array());
          }
		break; */
	}
	}else{
		echo "";
	}
} //fin Funcion select para ubicacion administrativa


function busca_visitante($var1=null){
	$this->layout = "ajax";
   	$cond_cds ="cod_tipo_institucion=".$this->verifica_SS(3)." and cod_institucion=".$this->verifica_SS(4)." and cod_dependencia=".$this->verifica_SS(5);
   	$cod_dirsup =  $this->cugd02_direccionsuperior->generateList($cond_cds, 'cod_dir_superior ASC', null, '{n}.cugd02_direccionsuperior.cod_dir_superior', '{n}.cugd02_direccionsuperior.denominacion');
    $this->concatena($cod_dirsup, 'cod_dir_superior');
	if($var1!=null){
		$this->set('cedula', $var1);
		$visitante = $this->cdvd01_datos_personales->find('cdvd01_datos_personales.cedula_identidad='.$var1,array('cdvd01_datos_personales.cedula_identidad','cdvd01_datos_personales.nombres_apellidos','cdvd01_datos_personales.sexo','cdvd01_datos_personales.direccion','cdvd01_datos_personales.telefonos','cdvd01_datos_personales.rif','cdvd01_datos_personales.razon_social'), null);
	if(!empty($visitante)){
		$this->set('seteditable', 'readonly');
		$this->set('setenable', 'disabled');
		$this->set('activa', '');
		// $cedula = $visitante['cdvd01_datos_personales']['cedula_identidad'];
		$this->set('nomb', $visitante['cdvd01_datos_personales']['nombres_apellidos']);
		$this->set('sexo_c', $visitante['cdvd01_datos_personales']['sexo']);
		$this->set('direccion', $visitante['cdvd01_datos_personales']['direccion']);
		$this->set('telefonos', $visitante['cdvd01_datos_personales']['telefonos']);
		$this->set('rif', $visitante['cdvd01_datos_personales']['rif']);
		$this->set('razon_soci', $visitante['cdvd01_datos_personales']['razon_social']);
	$sqlv = "SELECT a.observaciones, a.fecha_registro, a.hora, d.denominacion AS dirsuperior, e.denominacion AS coordinacion, s.denominacion AS secretaria, c.denominacion AS direccion
			FROM v_cdvd01_visitas AS a
			INNER JOIN cugd02_direccionsuperior AS d ON (a.cod_tipo_inst=d.cod_tipo_institucion AND a.cod_inst=d.cod_institucion AND a.cod_dep=d.cod_dependencia AND a.cod_dir_superior=d.cod_dir_superior)
			INNER JOIN cugd02_coordinacion AS e ON (a.cod_tipo_inst=e.cod_tipo_institucion AND a.cod_inst=e.cod_institucion AND a.cod_dep=e.cod_dependencia AND a.cod_dir_superior=e.cod_dir_superior AND a.cod_coordinacion=e.cod_coordinacion)
			INNER JOIN cugd02_secretaria AS s ON (a.cod_tipo_inst=s.cod_tipo_institucion AND a.cod_inst=s.cod_institucion AND a.cod_dep=s.cod_dependencia AND a.cod_dir_superior=s.cod_dir_superior AND a.cod_coordinacion=s.cod_coordinacion AND a.cod_secretaria=s.cod_secretaria)
			INNER JOIN cugd02_direccion AS c ON (a.cod_tipo_inst=c.cod_tipo_institucion AND a.cod_inst=c.cod_institucion AND a.cod_dep=c.cod_dependencia AND a.cod_dir_superior=c.cod_dir_superior AND a.cod_coordinacion=c.cod_coordinacion AND a.cod_secretaria=c.cod_secretaria AND a.cod_direccion=c.cod_direccion)
			WHERE ". $this->SQLCODIN() ." AND a.cedula_identidad=".$var1." ORDER BY a.fecha_registro DESC;";
	$datos_historial = $this->cdvd01_visitas->execute($sqlv);
	$this->set('datos_visitas', $datos_historial);
	}else{
		$this->set('seteditable', '');
		$this->set('setenable', '');
		$this->set('activa', 'disabled');
		$this->set('cedula', $var1);
		$this->set('nomb', '');
		$this->set('sexo_c', '');
		$this->set('direccion', '');
		$this->set('telefonos', '');
		$this->set('rif', '');
		$this->set('razon_soci', '');
	}
	}else {
		$this->set('seteditable', '');
		$this->set('setenable', '');
		$this->set('activa', 'disabled');
		$this->set('cedula', $var1);
		$this->set('nomb', '');
		$this->set('sexo_c', '');
		$this->set('direccion', '');
		$this->set('telefonos', '');
		$this->set('rif', '');
		$this->set('razon_soci', '');
	}
} // fin Funcion busca_visitante


function buscar_datos($var1=null) {
	$this->layout="ajax";
	$this->set("opcion",$var1);
	$this->Session->delete('pista');
} // fin Funcion buscar_datos


function buscar_por_pista($var1=null, $var2=null, $var3=null) {
$this->layout="ajax";
    if($var3==null){
    	$var2 = strtoupper($var2);
					$this->Session->write('pista', $var2);
					$Tfilas=$this->cdvd01_datos_personales->findCount("cedula_identidad::text LIKE '%$var2%' or upper(nombres_apellidos::text) LIKE upper('%".$var2."%') ");
					        if($Tfilas!=0){
					        	$pagina=1;
					        	$Tfilas=(int)ceil($Tfilas/100);
					        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
								$this->set('total_paginas',$Tfilas);
								$this->set('pagina_actual',$pagina);
								$this->set('ultimo',$Tfilas);
					     	    $datos_filas=$this->cdvd01_datos_personales->findAll("cedula_identidad::text LIKE '%$var2%' or upper(nombres_apellidos::text) LIKE upper('%".$var2."%')",null,"cedula_identidad ASC",100,1,null);
						        $this->set("datosFILAS",$datos_filas);
						        $this->set('siguiente',$pagina+1);
								$this->set('anterior',$pagina-1);
								$this->bt_nav($Tfilas,$pagina);
					          }else{
					        	$this->set("datosFILAS",'');
								$this->set('total_paginas','');
								$this->set('pagina_actual','');
								$this->set('ultimo','');
						        $this->set('siguiente','');
								$this->set('anterior','');
					          }
            }else{
						$var22 = $this->Session->read('pista');
						$var22 = strtoupper($var22);
						$Tfilas=$this->cdvd01_datos_personales->findCount("cedula_identidad::text LIKE '%$var22%' or upper(nombres_apellidos::text) LIKE upper('%".$var22."%')");
						        if($Tfilas!=0){
						        	$pagina=$var3;
						        	$Tfilas=(int)ceil($Tfilas/100);
						        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
									$this->set('total_paginas',$Tfilas);
									$this->set('pagina_actual',$pagina);
									$this->set('ultimo',$Tfilas);
									$datos_filas=$this->cdvd01_datos_personales->findAll("cedula_identidad::text LIKE '%$var2%' or upper(nombres_apellidos::text) LIKE upper('%".$var22."%')",null,"cedula_identidad ASC",100,$pagina,null);
							        $this->set("datosFILAS",$datos_filas);
							        $this->set('siguiente',$pagina+1);
									$this->set('anterior',$pagina-1);
									$this->bt_nav($Tfilas,$pagina);
						          }else{
						        	$this->set("datosFILAS",'');
									$this->set('total_paginas','');
									$this->set('pagina_actual','');
									$this->set('ultimo','');
						        	$this->set('siguiente','');
									$this->set('anterior','');
						          }
                 }
	$this->set("opcion",$var1);
} // fin Funcion buscar_por_pista


function guardar() {
	$this->layout="ajax";
	if(!empty($this->data['cdvd01_datos_personales'])){
		$cod_presi = $this->Session->read('SScodpresi');
	    $cod_entidad = $this->Session->read('SScodentidad');
	    $cod_tipo_inst = $this->Session->read('SScodtipoinst');
	    $cod_inst = $this->Session->read('SScodinst');
	    $cod_dep = $this->Session->read('SScoddep');
		$cedu = $this->data['cdvd01_datos_personales']['cedula'];
		$nombre = $this->data['cdvd01_datos_personales']['nombre'];
		$sexo = $this->data['cdvd01_datos_personales']['sexo'];
		$direc = $this->data['cdvd01_datos_personales']['direccion'];
		$telef = $this->data['cdvd01_datos_personales']['telefono'];
		$rif = $this->data['cdvd01_datos_personales']['rif'];
		$razon_so = $this->data['cdvd01_datos_personales']['razon_social'];
		$cod_dirsup = $this->data['cdvd01_datos_personales']['cod_dir_superior'];
		$cod_coord = $this->data['cdvd01_datos_personales']['cod_coordinacion'];
		$cod_sec = $this->data['cdvd01_datos_personales']['cod_secretaria'];
		$cod_direc = $this->data['cdvd01_datos_personales']['cod_direccion'];
		$observacion = $this->data['cdvd01_datos_personales']['observaciones'];
		$nom_usua = $this->Session->read('nom_usuario');
		$fecha_re = date("Y-m-d");
		$hora = date("h:i a");
		$valida_ced = $this->cdvd01_datos_personales->findCount('cedula_identidad='.$cedu);
		if($valida_ced!=0){
			$this->set('errorMessage', 'LO SIENTO LA C&Eacute;DULA('.$cedu.') YA SE ENCUENTRA REGISTRADA EN EL SISTEMA');
		}else{
			$sql_guarda = "INSERT INTO cdvd01_datos_personales VALUES('".$cedu."','".$nombre."','".$sexo."','".$direc."','".$telef."','".$rif."','".$razon_so."','".$nom_usua."','".$fecha_re."')";
			$this->cdvd01_datos_personales->execute("BEGIN");
			if($this->cdvd01_datos_personales->execute($sql_guarda)>1){
				$this->cdvd01_datos_personales->execute("COMMIT");
				$num_con = "SELECT MAX(numero_control) as numero_control FROM cdvd01_visitas;";
				$numero = $this->cdvd01_visitas->execute($num_con);
				foreach($numero as $row){
					$num_control = $row[0]['numero_control']+1;
				}
				$sql_visita = "BEGIN; INSERT INTO cdvd01_visitas VALUES('".$cod_presi."','".$cod_entidad."','".$cod_tipo_inst."','".$cod_inst."','".$cod_dep."','".$cedu."','".$num_control."','".$cod_dirsup."','".$cod_coord."','".$cod_sec."','".$cod_direc."','".$observacion."','".$nom_usua."','".$fecha_re."','".$hora."')";
				if($this->cdvd01_visitas->execute($sql_visita)>0){
					$this->cdvd01_visitas->execute("COMMIT");
					$this->set('Message_existe','LOS DATOS DEL VISITANTE FUERON REGISTRADOS EXITOSAMENTE');
				}else{
					$this->cdvd01_visitas->execute("ROLLBACK");
					$this->set('errorMessage','LOS DATOS DEL VISITANTE FUERON REGISTRADOS, PERO NO SE PUDO REGISTRAR LA VISITA EN EL HISTORIAL');
				}
			}
			else{
				$this->cdvd01_datos_personales->execute("ROLLBACK");
				$this->set('errorMessage','NO SE PUDO REGISTRAR LOS DATOS DEL VISITANTE');
				echo "<script type='text/javascript'>
					document.getElementById('vi_cedula').value='';
					document.getElementById('vi_direccion').value='';
				</script>";
			}
		}
	}else{
		$this->set('errorMessage','ATENCI&Oacute;N, DEBE INGRESAR TODOS LOS DATOS REQUERIDOS');
	}
	echo "<script type='text/javascript'>
		document.getElementById('vi_observaciones').value='';
	</script>";
	$this->busca_visitante($cedu);
	$this->render('busca_visitante');
} // fin Funcion guardar


function agregar_visita($cedulaci=null) {
	$this->layout = "ajax";
	if($cedulaci!=null){
		if(!empty($this->data['cdvd01_datos_personales'])){
			$cod_presi = $this->Session->read('SScodpresi');
	    	$cod_entidad = $this->Session->read('SScodentidad');
	    	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	    	$cod_inst = $this->Session->read('SScodinst');
	    	$cod_dep = $this->Session->read('SScoddep');
			$cod_dirsup = $this->data['cdvd01_datos_personales']['cod_dir_superior'];
			$cod_coord = $this->data['cdvd01_datos_personales']['cod_coordinacion'];
			$cod_sec = $this->data['cdvd01_datos_personales']['cod_secretaria'];
			$cod_direc = $this->data['cdvd01_datos_personales']['cod_direccion'];
			$observacion = $this->data['cdvd01_datos_personales']['observaciones'];
			$nom_usua = $this->Session->read('nom_usuario');
			$fecha_re = date("Y-m-d");
			$hora = date("h:i a");
				$num_con = "SELECT MAX(numero_control) as numero_control FROM cdvd01_visitas;";
				$numero = $this->cdvd01_visitas->execute($num_con);
				foreach($numero as $row){
					$num_control = $row[0]['numero_control']+1;
				}
				$sql_visi = "BEGIN; INSERT INTO cdvd01_visitas VALUES('".$cod_presi."','".$cod_entidad."','".$cod_tipo_inst."','".$cod_inst."','".$cod_dep."','".$cedulaci."','".$num_control."','".$cod_dirsup."','".$cod_coord."','".$cod_sec."','".$cod_direc."','".$observacion."','".$nom_usua."','".$fecha_re."','".$hora."')";
				if($this->cdvd01_visitas->execute($sql_visi)>1){
					$this->cdvd01_visitas->execute("COMMIT");
					$this->set('Message_existe','LOS DATOS DE LA VISITA FUERON REGISTRADOS EXITOSAMENTE EN EL HISTORIAL');
				}else{
					$this->cdvd01_visitas->execute("ROLLBACK");
					$this->set('errorMessage','NO SE PUDO REGISTRAR LOS DATOS DE LA VISITA');
				}
		}else{
		$this->set('errorMessage','ATENCI&Oacute;N, DEBE INGRESAR TODOS LOS DATOS REQUERIDOS');
		}
	}else{
		$this->set('errorMessage','NO LLEGARON DATOS COMPLETOS DEL VISITANTE');
	}
	echo "<script type='text/javascript'>
		document.getElementById('vi_observaciones').value='';
	</script>";
	$this->busca_visitante($cedulaci);
	$this->render('busca_visitante');
} // fin Funcion agregar_visita


function modificar($var1=null,$pagina=null) {
	$this->layout = "ajax";
   	$cond_cds ="cod_tipo_institucion=".$this->verifica_SS(3)." and cod_institucion=".$this->verifica_SS(4)." and cod_dependencia=".$this->verifica_SS(5);
   	$cod_dirsup =  $this->cugd02_direccionsuperior->generateList($cond_cds, 'cod_dir_superior ASC', null, '{n}.cugd02_direccionsuperior.cod_dir_superior', '{n}.cugd02_direccionsuperior.denominacion');
    $this->concatena($cod_dirsup, 'cod_dir_superior');
	if($var1!=null){
		$this->set('cedula', $var1);
		$visitante = $this->cdvd01_datos_personales->find('cdvd01_datos_personales.cedula_identidad='.$var1,array('cdvd01_datos_personales.cedula_identidad','cdvd01_datos_personales.nombres_apellidos','cdvd01_datos_personales.sexo','cdvd01_datos_personales.direccion','cdvd01_datos_personales.telefonos','cdvd01_datos_personales.rif','cdvd01_datos_personales.razon_social'), null);
	if(!empty($visitante)){
		$this->set('seteditable', '');
		$this->set('setenable', '');
		// $cedula = $visitante['cdvd01_datos_personales']['cedula_identidad'];
		$this->set('nomb', $visitante['cdvd01_datos_personales']['nombres_apellidos']);
		$this->set('sexo_c', $visitante['cdvd01_datos_personales']['sexo']);
		$this->set('direccion', $visitante['cdvd01_datos_personales']['direccion']);
		$this->set('telefonos', $visitante['cdvd01_datos_personales']['telefonos']);
		$this->set('rif', $visitante['cdvd01_datos_personales']['rif']);
		$this->set('razon_soci', $visitante['cdvd01_datos_personales']['razon_social']);
	$sqlv = "SELECT a.cedula_identidad, a.numero_control, a.observaciones, a.fecha_registro, a.hora, d.denominacion AS dirsuperior, e.denominacion AS coordinacion, s.denominacion AS secretaria, c.denominacion AS direccion
			FROM v_cdvd01_visitas AS a
			INNER JOIN cugd02_direccionsuperior AS d ON (a.cod_tipo_inst=d.cod_tipo_institucion AND a.cod_inst=d.cod_institucion AND a.cod_dep=d.cod_dependencia AND a.cod_dir_superior=d.cod_dir_superior)
			INNER JOIN cugd02_coordinacion AS e ON (a.cod_tipo_inst=e.cod_tipo_institucion AND a.cod_inst=e.cod_institucion AND a.cod_dep=e.cod_dependencia AND a.cod_dir_superior=e.cod_dir_superior AND a.cod_coordinacion=e.cod_coordinacion)
			INNER JOIN cugd02_secretaria AS s ON (a.cod_tipo_inst=s.cod_tipo_institucion AND a.cod_inst=s.cod_institucion AND a.cod_dep=s.cod_dependencia AND a.cod_dir_superior=s.cod_dir_superior AND a.cod_coordinacion=s.cod_coordinacion AND a.cod_secretaria=s.cod_secretaria)
			INNER JOIN cugd02_direccion AS c ON (a.cod_tipo_inst=c.cod_tipo_institucion AND a.cod_inst=c.cod_institucion AND a.cod_dep=c.cod_dependencia AND a.cod_dir_superior=c.cod_dir_superior AND a.cod_coordinacion=c.cod_coordinacion AND a.cod_secretaria=c.cod_secretaria AND a.cod_direccion=c.cod_direccion)
			WHERE ". $this->SQLCODIN() ." AND a.cedula_identidad=".$var1." ORDER BY a.fecha_registro DESC;";
	$datos_historial = $this->cdvd01_visitas->execute($sqlv);
	$this->set('datos_visitas', $datos_historial);
	}else{
		$this->set('seteditable', '');
		$this->set('setenable', '');
		$this->set('cedula', $var1);
		$this->set('nomb', '');
		$this->set('sexo_c', '');
		$this->set('direccion', '');
		$this->set('telefonos', '');
		$this->set('rif', '');
		$this->set('razon_soci', '');
	}
	}else {
		$this->set('seteditable', '');
		$this->set('setenable', '');
		$this->set('cedula', $var1);
		$this->set('nomb', '');
		$this->set('sexo_c', '');
		$this->set('direccion', '');
		$this->set('telefonos', '');
		$this->set('rif', '');
		$this->set('razon_soci', '');
	}
	if($pagina!=null) $this->set('pagina',$pagina);
} // fin Funcion modificar


function guardar_modificar($pagina=null) {
	$this->layout="ajax";
	if(!empty($this->data['cdvd01_datos_personales'])){
		$cedu = $this->data['cdvd01_datos_personales']['cedula'];
		$nombre = $this->data['cdvd01_datos_personales']['nombre'];
		$sexo = $this->data['cdvd01_datos_personales']['sexo'];
		$direc = $this->data['cdvd01_datos_personales']['direccion'];
		$telef = $this->data['cdvd01_datos_personales']['telefono'];
		$rif = $this->data['cdvd01_datos_personales']['rif'];
		$razon_so = $this->data['cdvd01_datos_personales']['razon_social'];
		$nom_usua = $this->Session->read('nom_usuario');
		$fecha_re = date("Y-m-d");
		$sql_modifi = "UPDATE cdvd01_datos_personales SET nombres_apellidos='".$nombre."', sexo='".$sexo."', direccion='".$direc."', telefonos='".$telef."', rif='".$rif."', razon_social='".$razon_so."', username_registro='".$nom_usua."', fecha_registro='".$fecha_re."' WHERE cedula_identidad=$cedu";
		$this->cdvd01_datos_personales->execute("BEGIN");
			if($this->cdvd01_datos_personales->execute($sql_modifi)>1){
				$this->cdvd01_datos_personales->execute("COMMIT");
				$this->set('Message_existe','LOS DATOS DEL VISITANTE FUERON MODIFICADOS EXITOSAMENTE');
			}
			else{
				$this->cdvd01_datos_personales->execute("ROLLBACK");
				$this->set('errorMessage','NO SE PUDO MODIFICAR LOS DATOS DEL VISITANTE');
			}
	}else{
		$this->set('errorMessage','ATENCI&Oacute;N, DEBE INGRESAR TODOS LOS DATOS REQUERIDOS');
	}
	echo "<script type='text/javascript'>
		document.getElementById('vi_observaciones').value='';
	</script>";
	if($pagina!=null){
		$this->consulta($pagina);
		$this->render('consulta');
	}else{
		$this->busca_visitante($cedu);
		$this->render('busca_visitante');
	}
} // fin Funcion guardar_modificar


function eliminar($ced_vis=null) {
	$this->layout = "ajax";
	if($ced_vis!=null){
		$sql_elivi = "DELETE FROM cdvd01_visitas WHERE cedula_identidad=".$ced_vis;
		$this->cdvd01_visitas->execute("BEGIN");
		if($this->cdvd01_visitas->execute($sql_elivi)>1){
			$this->cdvd01_visitas->execute("COMMIT");
			$sql_eli = "BEGIN; DELETE FROM cdvd01_datos_personales WHERE cedula_identidad=".$ced_vis;
			if($this->cdvd01_datos_personales->execute($sql_eli)>1){
				$this->cdvd01_datos_personales->execute("COMMIT");
				$this->set('Message_existe','LOS DATOS DEL VISITANTE FUERON ELIMINADOS EXITOSAMENTE');
			}else{
				$this->cdvd01_datos_personales->execute("ROLLBACK");
				$this->set('errorMessage','EL HISTORIAL FU&Eacute; ELIMINADO, PERO NO SE PUDO ELIMINAR LOS DATOS DEL VISITANTE');
			}
		}else{
				$this->cdvd01_visitas->execute("ROLLBACK");
				$this->set('errorMessage','NO SE PUDO ELIMINAR LOS DATOS E HISTORIAL DEL VISITANTE');
		}
	}else{
		$this->set('errorMessage','NO LLEGARON DATOS COMPLETOS DEL VISITANTE');
	}
	$this->index();
	$this->render('index');
} // fin Funcion eliminar


function consulta($pagina=null) {
	$this->layout="ajax";
	if(isset($pagina)){
		$Tfilas = $this->cdvd01_datos_personales->findCount();
        if($Tfilas!=0){
        	$x=$this->cdvd01_datos_personales->findAll(null,array('cdvd01_datos_personales.cedula_identidad'),"cedula_identidad ASC",1,$pagina,null);
            // $this->set('DATA',$x);
            $this->set('pagina',$pagina);
          	$this->set('siguiente',$pagina+1);
          	$this->set('anterior',$pagina-1);
            $this->bt_nav($Tfilas,$pagina);
            // $this->set('numT',$Tfilas);
			// $this->set('numP',$pagina);
        }else{
	 	       	$this->set('errorMessage', 'NO SE ENCONTRAR&Oacute;N DATOS');
				$this->set('seteditable', '');
				$this->set('setenable', '');
				$this->set('activa', 'disabled');
                $this->set('mostrarS',false);
                $this->set('mostrarA',false);
            	$this->set('pagina','');
          		$this->set('siguiente','');
          		$this->set('anterior','');
				$this->set('cedula', '');
				$this->set('nomb', '');
				$this->set('sexo_c', '');
				$this->set('direccion', '');
				$this->set('telefonos', '');
				$this->set('rif', '');
				$this->set('razon_soci', '');
	 	       	$this->index();
			   	$this->render();
			   	return;
        }
	}else{
		$pagina=1;
		$Tfilas = $this->cdvd01_datos_personales->findCount();
        if($Tfilas!=0){
        	$x=$this->cdvd01_datos_personales->findAll(null,array('cdvd01_datos_personales.cedula_identidad'),"cedula_identidad ASC",1,$pagina,null);
			// $this->set('DATA',$x);
			$this->set('pagina',$pagina);
          	$this->set('siguiente',$pagina+1);
          	$this->set('anterior',$pagina-1);
          	$this->bt_nav($Tfilas,$pagina);
          	// $this->set('numT',$Tfilas);
			// $this->set('numP',$pagina);
        }else{
	 	       $this->set('errorMessage', 'NO SE ENCONTRAR&Oacute;N DATOS');
				$this->set('seteditable', '');
				$this->set('setenable', '');
				$this->set('activa', 'disabled');
                $this->set('mostrarS',false);
                $this->set('mostrarA',false);
            	$this->set('pagina','');
          		$this->set('siguiente','');
          		$this->set('anterior','');
				$this->set('cedula', '');
				$this->set('nomb', '');
				$this->set('sexo_c', '');
				$this->set('direccion', '');
				$this->set('telefonos', '');
				$this->set('rif', '');
				$this->set('razon_soci', '');
	 	       	$this->index();
			   	$this->render();
			   	return;
        }
	}
   	$cond_cds ="cod_tipo_institucion=".$this->verifica_SS(3)." and cod_institucion=".$this->verifica_SS(4)." and cod_dependencia=".$this->verifica_SS(5);
   	$cod_dirsup =  $this->cugd02_direccionsuperior->generateList($cond_cds, 'cod_dir_superior ASC', null, '{n}.cugd02_direccionsuperior.cod_dir_superior', '{n}.cugd02_direccionsuperior.denominacion');
    $this->concatena($cod_dirsup, 'cod_dir_superior');
    $var1="";
	if(isset($x) && !empty($x)){
		$var1 = $x[0]['cdvd01_datos_personales']['cedula_identidad'];
		$this->set('cedula', $var1);
		$visitante = $this->cdvd01_datos_personales->find('cdvd01_datos_personales.cedula_identidad='.$var1,array('cdvd01_datos_personales.cedula_identidad','cdvd01_datos_personales.nombres_apellidos','cdvd01_datos_personales.sexo','cdvd01_datos_personales.direccion','cdvd01_datos_personales.telefonos','cdvd01_datos_personales.rif','cdvd01_datos_personales.razon_social'), null);
	if(!empty($visitante)){
		$this->set('seteditable', 'readonly');
		$this->set('setenable', 'disabled');
		$this->set('activa', '');
		// $cedula = $visitante['cdvd01_datos_personales']['cedula_identidad'];
		$this->set('nomb', $visitante['cdvd01_datos_personales']['nombres_apellidos']);
		$this->set('sexo_c', $visitante['cdvd01_datos_personales']['sexo']);
		$this->set('direccion', $visitante['cdvd01_datos_personales']['direccion']);
		$this->set('telefonos', $visitante['cdvd01_datos_personales']['telefonos']);
		$this->set('rif', $visitante['cdvd01_datos_personales']['rif']);
		$this->set('razon_soci', $visitante['cdvd01_datos_personales']['razon_social']);
	$sqlv = "SELECT a.observaciones, a.fecha_registro, a.hora, d.denominacion AS dirsuperior, e.denominacion AS coordinacion, s.denominacion AS secretaria, c.denominacion AS direccion
			FROM v_cdvd01_visitas AS a
			INNER JOIN cugd02_direccionsuperior AS d ON (a.cod_tipo_inst=d.cod_tipo_institucion AND a.cod_inst=d.cod_institucion AND a.cod_dep=d.cod_dependencia AND a.cod_dir_superior=d.cod_dir_superior)
			INNER JOIN cugd02_coordinacion AS e ON (a.cod_tipo_inst=e.cod_tipo_institucion AND a.cod_inst=e.cod_institucion AND a.cod_dep=e.cod_dependencia AND a.cod_dir_superior=e.cod_dir_superior AND a.cod_coordinacion=e.cod_coordinacion)
			INNER JOIN cugd02_secretaria AS s ON (a.cod_tipo_inst=s.cod_tipo_institucion AND a.cod_inst=s.cod_institucion AND a.cod_dep=s.cod_dependencia AND a.cod_dir_superior=s.cod_dir_superior AND a.cod_coordinacion=s.cod_coordinacion AND a.cod_secretaria=s.cod_secretaria)
			INNER JOIN cugd02_direccion AS c ON (a.cod_tipo_inst=c.cod_tipo_institucion AND a.cod_inst=c.cod_institucion AND a.cod_dep=c.cod_dependencia AND a.cod_dir_superior=c.cod_dir_superior AND a.cod_coordinacion=c.cod_coordinacion AND a.cod_secretaria=c.cod_secretaria AND a.cod_direccion=c.cod_direccion)
			WHERE ". $this->SQLCODIN() ." AND a.cedula_identidad=".$var1." ORDER BY a.fecha_registro DESC;";
	$datos_historial = $this->cdvd01_visitas->execute($sqlv);
	$this->set('datos_visitas', $datos_historial);
	}else{
		$this->set('seteditable', '');
		$this->set('setenable', '');
		$this->set('activa', 'disabled');
		$this->set('cedula', $var1);
		$this->set('nomb', '');
		$this->set('sexo_c', '');
		$this->set('direccion', '');
		$this->set('telefonos', '');
		$this->set('rif', '');
		$this->set('razon_soci', '');
	}
	}else {
		$this->set('seteditable', '');
		$this->set('setenable', '');
		$this->set('activa', 'disabled');
		$this->set('cedula', $var1);
		$this->set('nomb', '');
		$this->set('sexo_c', '');
		$this->set('direccion', '');
		$this->set('telefonos', '');
		$this->set('rif', '');
		$this->set('razon_soci', '');
	}
 } //fin Funcion consulta


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
 } // fin Funcion bt_nav: paginacion


function modificar_items($ceduvi=null,$numcon=null,$fi=null,$npagina=null) {
	$this->layout = "ajax";
	if($ceduvi!=null && $numcon!=null)
		$visita = $this->cdvd01_visitas->find('cdvd01_visitas.cedula_identidad='.$ceduvi.' AND cdvd01_visitas.numero_control='.$numcon,array('cdvd01_visitas.cedula_identidad','cdvd01_visitas.numero_control','cdvd01_visitas.cod_dir_superior','cdvd01_visitas.cod_coordinacion','cdvd01_visitas.cod_secretaria','cdvd01_visitas.cod_direccion','cdvd01_visitas.observaciones','cdvd01_visitas.fecha_registro','cdvd01_visitas.hora'), null);
		if(!empty($visita)){
			$this->set('fechare',$visita['cdvd01_visitas']['fecha_registro']);
			$this->set('hora',$visita['cdvd01_visitas']['hora']);
			$this->set('observacion',$visita['cdvd01_visitas']['observaciones']);
			$this->set('cedu',$visita['cdvd01_visitas']['cedula_identidad']);
			$this->set('num_co',$visita['cdvd01_visitas']['numero_control']);
			$this->set('k',$fi);
			$this->set('mpagin',$npagina);
   	$cond_cdirs ="cod_tipo_institucion=".$this->verifica_SS(3)." and cod_institucion=".$this->verifica_SS(4)." and cod_dependencia=".$this->verifica_SS(5);
   	$lista1 =  $this->cugd02_direccionsuperior->generateList($cond_cdirs, 'cod_dir_superior ASC', null, '{n}.cugd02_direccionsuperior.cod_dir_superior', '{n}.cugd02_direccionsuperior.denominacion');
    $this->concatena($lista1, 'vector_1');
	$codi_dsup = $visita['cdvd01_visitas']['cod_dir_superior'];
    $this->set('direccion_sup',$codi_dsup);
    $this->Session->write('cod_11',$codi_dsup);
    	if($lista1!=null){
    		$codi_coordi = $visita['cdvd01_visitas']['cod_coordinacion'];
    		$cond_cdirs .= " and cod_dir_superior=".$codi_dsup;
			$lista2 = $this->cugd02_coordinacion->generateList($cond_cdirs, 'cod_coordinacion ASC', null, '{n}.cugd02_coordinacion.cod_coordinacion', '{n}.cugd02_coordinacion.denominacion');
			$this->concatena($lista2, 'vector_2');
			$this->set('cod_coordi',$codi_coordi);
			$this->Session->write('cod_22',$codi_coordi);
			if($lista2!=null){
				$codi_secre = $visita['cdvd01_visitas']['cod_secretaria'];
				$cond_cdirs .= " and cod_coordinacion=".$codi_coordi;
				$lista3 = $this->cugd02_secretaria->generateList($cond_cdirs, 'cod_secretaria ASC', null, '{n}.cugd02_secretaria.cod_secretaria', '{n}.cugd02_secretaria.denominacion');
				$this->concatena($lista3, 'vector_3');
				$this->set('cod_secre',$codi_secre);
				$this->Session->write('cod_33',$codi_secre);
				if($lista3!=null){
					$codi_direc = $visita['cdvd01_visitas']['cod_direccion'];
		  			$cond_cdirs .= " and cod_secretaria=".$codi_secre;
		 			$lista4 = $this->cugd02_direccion->generateList($cond_cdirs, 'cod_direccion ASC', null, '{n}.cugd02_direccion.cod_direccion', '{n}.cugd02_direccion.denominacion');
					$this->concatena($lista4, 'vector_4');
					$this->set('cod_dire',$codi_direc);
				}else{
    				$this->set('vector_4',array());
					$this->set('cod_dire','');
				}
			}else{
    			$this->set('vector_3',array());
				$this->set('cod_secre','');
    			$this->set('vector_4',array());
				$this->set('cod_dire','');
			}
    	}else{
			$this->set('fechare','');
			$this->set('hora','');
			$this->set('observacion','');
			$this->set('cedu','');
			$this->set('num_co','');
    		$this->set('vector_2',array());
			$this->set('cod_coordi','');
    		$this->set('vector_3',array());
			$this->set('cod_secre','');
    		$this->set('vector_4',array());
			$this->set('cod_dire','');
			$this->set('k','');
			$this->set('mpagin','');
    	}
		}else{
			$this->set('fechare','');
			$this->set('hora','');
			$this->set('observacion','');
			$this->set('cedu','');
			$this->set('num_co','');
    		$this->set('vector_1',array());
			$this->set('direccion_sup','');
    		$this->set('vector_2',array());
			$this->set('cod_coordi','');
    		$this->set('vector_3',array());
			$this->set('cod_secre','');
    		$this->set('vector_4',array());
			$this->set('cod_dire','');
			$this->set('k','');
			$this->set('mpagin','');
		}
} // fin Funcion modificar_items


function select_item($select=null,$var=null) {
	$this->layout = "ajax";
if(isset($var) && !empty($var) && $var!=''){
    $cond ="cod_tipo_institucion=".$this->verifica_SS(3)." and cod_institucion=".$this->verifica_SS(4)." and cod_dependencia=".$this->verifica_SS(5);
	switch($select){
		case 'coordi':
		  $this->set('SELECT','secre');
		  $this->set('codigo','coordi');
		  $this->set('seleccion','');
		  $this->set('n',2);
		  $this->Session->delete('cod_11');
		  $this->Session->write('cod_11',$var);
		  $cond .= " and cod_dir_superior=".$var;
		  $lista = $this->cugd02_coordinacion->generateList($cond, 'cod_coordinacion ASC', null, '{n}.cugd02_coordinacion.cod_coordinacion', '{n}.cugd02_coordinacion.denominacion');
          if($lista!=null){
          	$this->concatena($lista, 'vector');
          }else{
          	$this->set('vector',array());
          }
		break;
		case 'secre':
		  $this->set('SELECT','direc');
		  $this->set('codigo','secre');
		  $this->set('seleccion','');
		  $this->set('n',3);
		  $cod_1 = $this->Session->read('cod_11');
		  $this->Session->delete('cod_22');
		  $this->Session->write('cod_22',$var);
		  $cond .= " and cod_dir_superior=".$cod_1." and cod_coordinacion=".$var;
		  $lista = $this->cugd02_secretaria->generateList($cond, 'cod_secretaria ASC', null, '{n}.cugd02_secretaria.cod_secretaria', '{n}.cugd02_secretaria.denominacion');
          if($lista!=null){
          	$this->concatena($lista, 'vector');
          }else{
          	$this->set('vector',array());
          }
		break;
		case 'direc':
		  $this->set('SELECT','divi');
		  $this->set('codigo','direc');
		  $this->set('seleccion','');
		  $this->set('n',4);
		  $cod_1 = $this->Session->read('cod_11');
		  $cod_2 = $this->Session->read('cod_22');
		  $this->Session->delete('cod_33');
		  $this->Session->write('cod_33',$var);
		  $cond .= " and cod_dir_superior=".$cod_1." and cod_coordinacion=".$cod_2." and  cod_secretaria=".$var;
		  $lista = $this->cugd02_direccion->generateList($cond, 'cod_direccion ASC', null, '{n}.cugd02_direccion.cod_direccion', '{n}.cugd02_direccion.denominacion');
          if($lista!=null){
          	$this->concatena($lista, 'vector');
          }else{
          	$this->set('vector',array());
          }
		break;
	}
	}else{
		echo "";
	}
} // fin Funcion select_item


function guardar_modificar_items($ceduitem=null,$numcoitem=null,$numpagi=null) {
	$this->layout = "ajax";
	if($ceduitem!=null && $numcoitem!=null){
		if(!empty($this->data['cdvd01_datos_personales'])){
			$cod_presi = $this->Session->read('SScodpresi');
	    	$cod_entidad = $this->Session->read('SScodentidad');
	    	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	    	$cod_inst = $this->Session->read('SScodinst');
	    	$cod_dep = $this->Session->read('SScoddep');
			$cod_dirsup = $this->data['cdvd01_visitas']['cod_direc_superi'];
			$cod_coord = $this->data['cdvd01_visitas']['cod_coordi'];
			$cod_sec = $this->data['cdvd01_visitas']['cod_secre'];
			$cod_direc = $this->data['cdvd01_visitas']['cod_direc'];
			$observacion = $this->data['cdvd01_visitas']['observacion_item'];
			$nom_usua = $this->Session->read('nom_usuario');
			$fecha_re = date("Y-m-d");
			$hora = date("h:i a");
				$sql_guhi = "BEGIN; UPDATE cdvd01_visitas SET cod_presi='".$cod_presi."', cod_entidad='".$cod_entidad."', cod_tipo_inst='".$cod_tipo_inst."', cod_inst='".$cod_inst."', cod_dep='".$cod_dep."', cod_dir_superior='".$cod_dirsup."', cod_coordinacion='".$cod_coord."', cod_secretaria='".$cod_sec."', cod_direccion='".$cod_direc."', observaciones='".$observacion."', username_registro='".$nom_usua."', fecha_registro='".$fecha_re."', hora='".$hora."' WHERE cedula_identidad='".$ceduitem."' AND numero_control='".$numcoitem."'";
				if($this->cdvd01_visitas->execute($sql_guhi)>1){
					$this->cdvd01_visitas->execute("COMMIT");
					$this->set('Message_existe','LOS DATOS DE LA VISITA FUERON MODIFICADOS EXITOSAMENTE');
					$this->Session->delete('cod_11');
					$this->Session->delete('cod_22');
					$this->Session->delete('cod_33');
					$this->modificar($ceduitem,$numpagi);
					$this->render('modificar');
				}else{
					$this->cdvd01_visitas->execute("ROLLBACK");
					$this->set('errorMessage','NO SE PUDO MODIFICAR LOS DATOS DE LA VISITA');
					$this->Session->delete('cod_11');
					$this->Session->delete('cod_22');
					$this->Session->delete('cod_33');
					$this->modificar($ceduitem,$numpagi);
					$this->render('modificar');
				}
		}
	}else{
		$this->set('errorMessage','NO LLEGARON DATOS COMPLETOS DEL VISITANTE');
		$this->busca_visitante($ceduitem);
		$this->render('busca_visitante');
	}
} // fin Funcion guardar_modificar_items


function eliminarvi($cevisi=null,$nucont=null,$numpag=null) {
	$this->layout = "ajax";
	if($cevisi!=null && $nucont!=null){
		$sqleli = "DELETE FROM cdvd01_visitas WHERE cedula_identidad=".$cevisi." AND numero_control=".$nucont." AND ".$this->SQLCODIN();
		$this->cdvd01_visitas->execute("BEGIN");
		if($this->cdvd01_visitas->execute($sqleli)>1){
			$this->cdvd01_visitas->execute("COMMIT");
			$this->set('Message_existe','LOS DATOS DE LA VISITA FUERON ELIMINADOS EXITOSAMENTE');
			$this->modificar($cevisi,$numpag);
			$this->render('modificar');
		}else{
				$this->cdvd01_visitas->execute("ROLLBACK");
				$this->set('errorMessage','NO SE PUDO ELIMINAR LOS DATOS DE LA VISITA');
				$this->modificar($cevisi,$numpag);
				$this->render('modificar');
		}
	}else{
		$this->set('errorMessage','NO SE PUDO ELIMINAR ESTA VISITA, NO LLEGARON DATOS COMPLETOS');
		$this->modificar($cevisi,$numpag);
		$this->render('modificar');
	}
} // fin Funcion eliminarvi


function cancelar($ceducan=null,$numpa=null) {
	$this->layout = "ajax";
	if($ceducan!=null){
		$this->Session->delete('cod_11');
		$this->Session->delete('cod_22');
		$this->Session->delete('cod_33');
		$this->modificar($ceducan,$numpa);
		$this->render('modificar');
	}else{
		$this->Session->delete('cod_11');
		$this->Session->delete('cod_22');
		$this->Session->delete('cod_33');
		$this->modificar($ceducan,$numpa);
		$this->render('modificar');
	}
} // fin Funcion cancelar


function reporte_visita($ceduve=null) {
	$this->layout = "ajax";
	if($ceduve!=null){
		$this->layout="pdf";
		$this->set('permiso',true);
		$davisita = $this->cdvd01_datos_personales->find('cdvd01_datos_personales.cedula_identidad='.$ceduve,array('cdvd01_datos_personales.cedula_identidad','cdvd01_datos_personales.nombres_apellidos','cdvd01_datos_personales.sexo','cdvd01_datos_personales.direccion','cdvd01_datos_personales.telefonos','cdvd01_datos_personales.rif','cdvd01_datos_personales.razon_social'), null);
		if(!empty($davisita)){
			$this->set('visitant',$davisita);
	$sqlvei = "SELECT a.observaciones, a.fecha_registro, a.hora, d.denominacion AS dirsuperior, e.denominacion AS coordinacion, s.denominacion AS secretaria, c.denominacion AS direccion
			FROM v_cdvd01_visitas AS a
			INNER JOIN cugd02_direccionsuperior AS d ON (a.cod_tipo_inst=d.cod_tipo_institucion AND a.cod_inst=d.cod_institucion AND a.cod_dep=d.cod_dependencia AND a.cod_dir_superior=d.cod_dir_superior)
			INNER JOIN cugd02_coordinacion AS e ON (a.cod_tipo_inst=e.cod_tipo_institucion AND a.cod_inst=e.cod_institucion AND a.cod_dep=e.cod_dependencia AND a.cod_dir_superior=e.cod_dir_superior AND a.cod_coordinacion=e.cod_coordinacion)
			INNER JOIN cugd02_secretaria AS s ON (a.cod_tipo_inst=s.cod_tipo_institucion AND a.cod_inst=s.cod_institucion AND a.cod_dep=s.cod_dependencia AND a.cod_dir_superior=s.cod_dir_superior AND a.cod_coordinacion=s.cod_coordinacion AND a.cod_secretaria=s.cod_secretaria)
			INNER JOIN cugd02_direccion AS c ON (a.cod_tipo_inst=c.cod_tipo_institucion AND a.cod_inst=c.cod_institucion AND a.cod_dep=c.cod_dependencia AND a.cod_dir_superior=c.cod_dir_superior AND a.cod_coordinacion=c.cod_coordinacion AND a.cod_secretaria=c.cod_secretaria AND a.cod_direccion=c.cod_direccion)
			WHERE ". $this->SQLCODIN() ." AND a.cedula_identidad=".$ceduve." ORDER BY a.fecha_registro DESC;";
	$datos_his = $this->cdvd01_visitas->execute($sqlvei);
			if(!empty($datos_his)){
				$this->set('dato_vitem', $datos_his);
			}
		}
	}
} // fin Funcion reporte_visita


function relacion_visitas($ir=null) {
   $this->layout="ajax";
   if($ir!=null){
		if($ir=='si'){ // Si va al Formulario
		   	$this->set('ir','si');
   			$cond_cdsup = "cod_tipo_institucion=".$this->verifica_SS(3)." and cod_institucion=".$this->verifica_SS(4)." and cod_dependencia=".$this->verifica_SS(5);
   			$cod_dirsupevi =  $this->cugd02_direccionsuperior->generateList($cond_cdsup, 'cod_dir_superior ASC', null, '{n}.cugd02_direccionsuperior.cod_dir_superior', '{n}.cugd02_direccionsuperior.denominacion');
    		$this->concatena($cod_dirsupevi, 'vicod_dir_superior');
		}else if($ir=='no'){ // No va al Formulario, pero si va al reporte
			$this->layout="pdf";
			$this->set('ir','no');
			$cp  = $this->Session->read('SScodpresi');
			$ce  = $this->Session->read('SScodentidad');
			$cti = $this->Session->read('SScodtipoinst');
			$ci  = $this->Session->read('SScodinst');
			$cd  = $this->Session->read('SScoddep');
			$peticion = $this->data['relacion_visitas']['tipo_peticion'];
			$_SESSION['desde_cao'] = '';
            $_SESSION['hasta_cao'] = '';
			if(isset($this->data['relacion_visitas']['tipo_peticion']) && $this->data['relacion_visitas']['tipo_peticion']==1){
				$this->set('tipo_peticion_reporte',$peticion);
			$sqlistavi = "SELECT a.observaciones, a.fecha_registro, a.hora, d.cedula_identidad AS cedula_identidad, d.nombres_apellidos AS nombres_apellidos, c.denominacion AS direccion
			FROM v_cdvd01_visitas AS a
			INNER JOIN cdvd01_datos_personales AS d ON (a.cedula_identidad=d.cedula_identidad)
			INNER JOIN cugd02_direccion AS c ON (a.cod_tipo_inst=c.cod_tipo_institucion AND a.cod_inst=c.cod_institucion AND a.cod_dep=c.cod_dependencia AND a.cod_dir_superior=c.cod_dir_superior AND a.cod_coordinacion=c.cod_coordinacion AND a.cod_secretaria=c.cod_secretaria AND a.cod_direccion=c.cod_direccion)
			WHERE ". $this->SQLCODIN() ." ORDER BY a.fecha_registro DESC;";
			$data_reporte = $this->cdvd01_visitas->execute($sqlistavi);
            $this->set('data_reporte',$data_reporte);
			}
			else if(isset($this->data['relacion_visitas']['tipo_peticion']) && $this->data['relacion_visitas']['tipo_peticion']==2){
				if($this->data['cdvd01_datos_personales']['cod_dir_superior']!=null && $this->data['cdvd01_datos_personales']['cod_coordinacion']!=null && $this->data['cdvd01_datos_personales']['cod_secretaria']!=null && $this->data['cdvd01_datos_personales']['cod_direccion']!=null){
					$codi_diro = " AND a.cod_dir_superior=".$this->data['cdvd01_datos_personales']['cod_dir_superior'];
					$codi_diro .= " AND a.cod_coordinacion=".$this->data['cdvd01_datos_personales']['cod_coordinacion'];
					$codi_diro .= " AND a.cod_secretaria=".$this->data['cdvd01_datos_personales']['cod_secretaria'];
					$codi_diro .= " AND a.cod_direccion=".$this->data['cdvd01_datos_personales']['cod_direccion'];
				}else{
					$codi_diro = " AND a.cod_dir_superior=-1";
					$codi_diro .= " AND a.cod_coordinacion=-1";
					$codi_diro .= " AND a.cod_secretaria=-1";
					$codi_diro .= " AND a.cod_direccion=-1";
				}
            	$this->set('tipo_peticion_reporte',$peticion);
				$sqlistavi = "SELECT a.observaciones, a.fecha_registro, a.hora, d.cedula_identidad AS cedula_identidad, d.nombres_apellidos AS nombres_apellidos, c.denominacion AS direccion
				FROM v_cdvd01_visitas AS a
				INNER JOIN cdvd01_datos_personales AS d ON (a.cedula_identidad=d.cedula_identidad)
				INNER JOIN cugd02_direccion AS c ON (a.cod_tipo_inst=c.cod_tipo_institucion AND a.cod_inst=c.cod_institucion AND a.cod_dep=c.cod_dependencia AND a.cod_dir_superior=c.cod_dir_superior AND a.cod_coordinacion=c.cod_coordinacion AND a.cod_secretaria=c.cod_secretaria AND a.cod_direccion=c.cod_direccion)
				WHERE ". $this->SQLCODIN() . $codi_diro." ORDER BY a.fecha_registro DESC;";
				$data_reporte = $this->cdvd01_visitas->execute($sqlistavi);
            	$this->set('data_reporte',$data_reporte);
			}
			else if(isset($this->data['relacion_visitas']['tipo_peticion']) && $this->data['relacion_visitas']['tipo_peticion']==3){
					  if(isset($this->data['relacion_visitas']['desde'])){
					  	  $desde = cambiar_formato_fecha($this->data['relacion_visitas']['desde']);
					  	  $hasta = cambiar_formato_fecha($this->data['relacion_visitas']['hasta']);
                          $cond_fecha = " and a.fecha_registro between '$desde' and '$hasta'";
                          $_SESSION['desde_cao'] = $this->data['relacion_visitas']['desde'];
                          $_SESSION['hasta_cao'] = $this->data['relacion_visitas']['hasta'];
                      }else{
                      	$cond_fecha = "";
                      }
            	$this->set('tipo_peticion_reporte',$peticion);
				$sqlistavi = "SELECT a.observaciones, a.fecha_registro, a.hora, d.cedula_identidad AS cedula_identidad, d.nombres_apellidos AS nombres_apellidos, c.denominacion AS direccion
				FROM v_cdvd01_visitas AS a
				INNER JOIN cdvd01_datos_personales AS d ON (a.cedula_identidad=d.cedula_identidad)
				INNER JOIN cugd02_direccion AS c ON (a.cod_tipo_inst=c.cod_tipo_institucion AND a.cod_inst=c.cod_institucion AND a.cod_dep=c.cod_dependencia AND a.cod_dir_superior=c.cod_dir_superior AND a.cod_coordinacion=c.cod_coordinacion AND a.cod_secretaria=c.cod_secretaria AND a.cod_direccion=c.cod_direccion)
				WHERE ". $this->SQLCODIN() . $cond_fecha." ORDER BY a.fecha_registro DESC;";
				$data_reporte = $this->cdvd01_visitas->execute($sqlistavi);
            	$this->set('data_reporte',$data_reporte);
			}
		}//fin else
	}//fin null
} // fin Funcion relacion_visitas


function ventana2($var=null){
	$this->layout="ajax";
	if($var==1 || $var=='1'){
		    echo "<script type='text/javascript'>";
		 	echo "	document.getElementById('seleccion_ubic_adminva').style.display='none';";
		 	echo "	document.getElementById('periodo_fechas_seleccion').style.display='none';";
            echo "</script>";
	}else if($var==2 || $var=='2'){
            echo "<script type='text/javascript'>";
            echo "	document.getElementById('periodo_fechas_seleccion').style.display='none';";
		 	echo "	document.getElementById('seleccion_ubic_adminva').style.display='block';";
            echo "</script>";
	}else if($var==3 || $var=='3'){
            echo "<script type='text/javascript'>";
            echo "	document.getElementById('seleccion_ubic_adminva').style.display='none';";
		 	echo "	document.getElementById('periodo_fechas_seleccion').style.display='block';";
            echo "</script>";
	}else{
		    echo "<script type='text/javascript'>";
		 	echo "	document.getElementById('seleccion_ubic_adminva').style.display='none';";
		 	echo "	document.getElementById('periodo_fechas_seleccion').style.display='none';";
            echo "</script>";
	}
} // fin Funcion ventana2
 }
?>
