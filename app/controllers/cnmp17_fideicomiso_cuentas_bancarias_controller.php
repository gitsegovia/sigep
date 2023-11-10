<?php

 class Cnmp17FideicomisoCuentasBancariasController extends AppController{

	var $name = 'cnmp17_fideicomiso_cuentas_bancarias';
	var $uses = array('cnmd17_fideicomiso_cuentas_bancarias', 'cstd01_entidades_bancarias', 'v_cuentas_bancarias', 'cnmd17_fideicomiso_control_trimestre', 'cstd01_sucursales_bancarias','cnmd15_datos_personales', 'cnmd06_fichas', 'Cnmd01', 'v_cnmp17_fideicomiso_cuentas_vision', 'cnmd15_dias_antiguedad', 'cnmd08_historia_nomina', 'cnmd15_depo_fideicomiso', 'ccfd04_cierre_mes', 'cugd01_estados', 'v_cnmp17_fideicomiso_tipo_nomina');
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
    	 * Funcion que permite leer las varibles de session. la cual permite capturar los codigos del usuario
    	 * para ser insertados en todas las tablas.
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



function SQLCA($ano=null){ //sql para busqueda de codigos de arranque con y sin año
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


function codigo_nomina($codigo=null){
	$this->layout = "ajax";
    $cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');

if($codigo!=null){
	$a = $this->v_cnmp17_fideicomiso_tipo_nomina->findAll($this->SQLCA()." and cod_tipo_nomina=".$codigo,array('cod_tipo_nomina','denominacion'));
    $this->Session->write('codf_tipo_nomina',$codigo);

	if($a!=null){
        $cod_tipo_nomina=$a[0]['v_cnmp17_fideicomiso_tipo_nomina']['cod_tipo_nomina'];
        $parametros=$cod_presi.",".$cod_entidad.",".$cod_tipo_inst.",".$cod_inst.",".$cod_dep.",".$cod_tipo_nomina;
        $swp=$this->Cnmd01->execute("SELECT pasar_trabajador_fide_eliminar($parametros);");
		$swp=$this->Cnmd01->execute("SELECT pasar_trabajador_fide($parametros);");

		echo "<script>
				document.getElementById('codigo_tipo_nom').value='".mascara($a[0]['v_cnmp17_fideicomiso_tipo_nomina']['cod_tipo_nomina'], 3)."';
				document.getElementById('denominacion_deno_nom').value='".$a[0]['v_cnmp17_fideicomiso_tipo_nomina']['denominacion']."';
			</script>";

       echo "<script>
			document.getElementById('tipo_proceso_1').disabled = false;
			document.getElementById('tipo_proceso_2').disabled = false;
			document.getElementById('tipo_proceso_3').disabled = false;
			document.getElementById('tipo_proceso_4').disabled = false;
			document.getElementById('tipo_proceso_1').checked = false;
			document.getElementById('tipo_proceso_2').checked = false;
			document.getElementById('tipo_proceso_3').checked = false;
			document.getElementById('tipo_proceso_4').checked = false;
          </script>";

	echo '<script>
			document.getElementById("distribuir_cuentas_banc").innerHTML="";
		</script>';

	echo "<script>
			document.getElementById('id_cedula_part').value='';
			document.getElementById('input_cedu_part').style.display = 'none';
		</script>";
	}else{
		echo "<script>
				document.getElementById('codigo_tipo_nom').value='';
				document.getElementById('denominacion_deno_nom').value='';
			</script>";

       echo "<script>
			document.getElementById('tipo_proceso_1').disabled = true;
			document.getElementById('tipo_proceso_2').disabled = true;
			document.getElementById('tipo_proceso_3').disabled = true;
			document.getElementById('tipo_proceso_4').disabled = true;
			document.getElementById('tipo_proceso_1').checked = false;
			document.getElementById('tipo_proceso_2').checked = false;
			document.getElementById('tipo_proceso_3').checked = false;
			document.getElementById('tipo_proceso_4').checked = false;
          </script>";

	echo '<script>
			document.getElementById("distribuir_cuentas_banc").innerHTML="";
		</script>';

	echo "<script>
			document.getElementById('id_cedula_part').value='';
			document.getElementById('input_cedu_part').style.display = 'none';
		</script>";
	}
}else{
	$this->set('mensajeError','No llego el c&oacute;digo del tipo de n&oacute;mina para procesar - Seleccione N&oacute;mina');
	echo "<script>
			document.getElementById('codigo_tipo_nom').value='';
			document.getElementById('denominacion_deno_nom').value='';
		</script>";

       echo "<script>
			document.getElementById('tipo_proceso_1').disabled = true;
			document.getElementById('tipo_proceso_2').disabled = true;
			document.getElementById('tipo_proceso_3').disabled = true;
			document.getElementById('tipo_proceso_4').disabled = true;
			document.getElementById('tipo_proceso_1').checked = false;
			document.getElementById('tipo_proceso_2').checked = false;
			document.getElementById('tipo_proceso_3').checked = false;
			document.getElementById('tipo_proceso_4').checked = false;
          </script>";

	echo '<script>
			document.getElementById("distribuir_cuentas_banc").innerHTML="";
		</script>';

	echo "<script>
			document.getElementById('id_cedula_part').value='';
			document.getElementById('input_cedu_part').style.display = 'none';
		</script>";
}
}//fin codigo_nomina



function index(){
 	$this->layout ="ajax";
 	$this->Session->delete('codf_tipo_nomina');
 	$this->Session->delete('codf_ent_bancaria');
 	$this->Session->delete('codf_suc_bancaria');
   	$lista = $this->v_cnmp17_fideicomiso_tipo_nomina->generateList($this->SQLCA(), $order = 'cod_tipo_nomina', $limit = null, '{n}.v_cnmp17_fideicomiso_tipo_nomina.cod_tipo_nomina', '{n}.v_cnmp17_fideicomiso_tipo_nomina.denominacion');
	if(!empty($lista)){
		$this->concatenaN($lista, 'cod_tipo_nomina');
	}else{
		$this->set('cod_tipo_nomina', array());
	}
 	$entidades=$this->cstd01_entidades_bancarias->generateList(null,'cod_entidad_bancaria ASC', null, '{n}.cstd01_entidades_bancarias.cod_entidad_bancaria', '{n}.cstd01_entidades_bancarias.denominacion');
	$entidades = $entidades != null ? $entidades : array();
	$this->set('entidades', $entidades);
}




function mostrar1($cod_ent=null){
	$this->layout = "ajax";
	$this->Session->write('codf_ent_bancaria',$cod_ent);
if($cod_ent!=null){
	$entidades=$this->cstd01_entidades_bancarias->findAll('cod_entidad_bancaria='.$cod_ent);
	if(!empty($entidades)){
		echo "<script>
				document.getElementById('codigo_entidad').value='".mascara($entidades[0]['cstd01_entidades_bancarias']['cod_entidad_bancaria'], 4)."';
				document.getElementById('denominacion_entidad').value='".$entidades[0]['cstd01_entidades_bancarias']['denominacion']."';
			</script>";
	}else{
		echo "<script>
				document.getElementById('codigo_entidad').value='';
				document.getElementById('denominacion_entidad').value='';
			</script>";
	}
}else{
	$this->set('mensajeError','No llego el c&oacute;digo de la entidad bancaria para procesar - Seleccione ALGUNA ENTIDAD BANCARIA');
	echo "<script>
			document.getElementById('codigo_entidad').value='';
			document.getElementById('denominacion_entidad').value='';
		</script>";
}

	echo "<script>
			document.getElementById('codigo_sucursal').value='';
			document.getElementById('denominacion_sucursal').value='';
		</script>";
}


function select_sucursales($cod_ent=null){
	$this->layout = "ajax";

	if($cod_ent!=null){
		    $sucursales = $this->cstd01_sucursales_bancarias->generateList('cod_entidad_bancaria='.$cod_ent, 'cod_entidad_bancaria, cod_sucursal ASC', null, '{n}.cstd01_sucursales_bancarias.cod_sucursal', '{n}.cstd01_sucursales_bancarias.denominacion');
		    if($sucursales == 0){
			   $this->set('sucursales','');
			   $this->set('cod_entidad','');
		    }else{
			   $this->set('sucursales',$sucursales);
			   $this->set('cod_entidad',$cod_ent);
		    }
	}else{
	   $this->set('sucursales','');
 	   $this->set('cod_entidad','');
	}
}


function mostrar2($cod_ent=null,$cod_sucursal=null){
	$this->layout = "ajax";
	$this->Session->write('codf_suc_bancaria',$cod_sucursal);
if($cod_sucursal!=null){
	$sucursales=$this->cstd01_sucursales_bancarias->findAll('cod_entidad_bancaria='.$cod_ent." and cod_sucursal=".$cod_sucursal);
	if(!empty($sucursales)){
		echo "<script>
				document.getElementById('codigo_sucursal').value='".mascara($sucursales[0]['cstd01_sucursales_bancarias']['cod_sucursal'], 4)."';
				document.getElementById('denominacion_sucursal').value='".$sucursales[0]['cstd01_sucursales_bancarias']['denominacion']."';
			</script>";
	}else{
		echo "<script>
				document.getElementById('codigo_sucursal').value='';
				document.getElementById('denominacion_sucursal').value='';
			</script>";
	}
}else{
	$this->set('mensajeError','No llego el c&oacute;digo de la sucursal bancaria para procesar - Seleccione ALGUNA SUCURSAL BANCARIA');
	echo "<script>
			document.getElementById('codigo_sucursal').value='';
			document.getElementById('denominacion_sucursal').value='';
		</script>";
}
}


function func_tipo_proceso($tipo_ope=null, $var3=null){
	$this->layout = "ajax";
    $cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$cod_tipo_nom = $this->Session->read('codf_tipo_nomina');
	$condic_cods = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep;
	$this->set('t_ope',$tipo_ope);


if($tipo_ope!=null){

	if($var3==null){

       	$pagina=1;

		if($tipo_ope==1){
			echo "<script>
					document.getElementById('id_cedula_part').value='';
					document.getElementById('input_cedu_part').style.display = 'none';
				</script>";
			$Tfilas=$this->v_cnmp17_fideicomiso_cuentas_vision->findCount($condic_cods." and cod_tipo_nomina='$cod_tipo_nom'");
			$cuentas = $this->v_cnmp17_fideicomiso_cuentas_vision->findAll($condic_cods." and cod_tipo_nomina='$cod_tipo_nom'", 'cod_cargo, cod_ficha, cedula_identidad, primer_nombre, segundo_nombre, primer_apellido, segundo_apellido, cuenta_bancaria', "cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, cedula_identidad ASC",150,$pagina,null);
		}else if($tipo_ope==2){
			echo "<script>
					document.getElementById('id_cedula_part').value='';
					document.getElementById('input_cedu_part').style.display = 'none';
				</script>";
			$Tfilas=$this->v_cnmp17_fideicomiso_cuentas_vision->findCount($condic_cods." and cod_tipo_nomina='$cod_tipo_nom' and cuenta_bancaria is not null");
			$cuentas = $this->v_cnmp17_fideicomiso_cuentas_vision->findAll($condic_cods." and cod_tipo_nomina='$cod_tipo_nom' and cuenta_bancaria is not null", 'cod_cargo, cod_ficha, cedula_identidad, primer_nombre, segundo_nombre, primer_apellido, segundo_apellido, cuenta_bancaria', "cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, cedula_identidad ASC",150,$pagina,null);
		}else if($tipo_ope==3){
			echo "<script>
					document.getElementById('id_cedula_part').value='';
					document.getElementById('input_cedu_part').style.display = 'none';
				</script>";
			$Tfilas=$this->v_cnmp17_fideicomiso_cuentas_vision->findCount($condic_cods." and cod_tipo_nomina='$cod_tipo_nom' and cuenta_bancaria is null");
			$cuentas = $this->v_cnmp17_fideicomiso_cuentas_vision->findAll($condic_cods." and cod_tipo_nomina='$cod_tipo_nom' and cuenta_bancaria is null", 'cod_cargo, cod_ficha, cedula_identidad, primer_nombre, segundo_nombre, primer_apellido, segundo_apellido, cuenta_bancaria', "cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, cedula_identidad ASC",150,$pagina,null);
		}else if($tipo_ope==4){
			echo "<script>
					document.getElementById('id_cedula_part').value='';
					document.getElementById('input_cedu_part').style.display = 'block';
					document.getElementById('id_cedula_part').focus();
				</script>";
			$Tfilas=1;
			$cuentas = array(1=>4);

		}else{
			$cuentas = array();
		}

						        	$Tfilas=(int)ceil($Tfilas/150);
						        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
									$this->set('total_paginas',$Tfilas);
									$this->set('pagina_actual',$pagina);
									$this->set('ultimo',$Tfilas);
							        $this->set('siguiente',$pagina+1);
									$this->set('anterior',$pagina-1);
									$this->bt_nav($Tfilas,$pagina);


						if(!empty($cuentas)){
							$this->set('cuentas',$cuentas);
						}else{
							$this->set('cuentas',array());
							$this->set('mensajeError','No se encontraron datos, ......Favor Seleccionar Nómina....');
						}

}else{

       	$pagina=$var3;

		if($tipo_ope==1){
			echo "<script>
					document.getElementById('id_cedula_part').value='';
					document.getElementById('input_cedu_part').style.display = 'none';
				</script>";
			$Tfilas=$this->v_cnmp17_fideicomiso_cuentas_vision->findCount($condic_cods." and cod_tipo_nomina='$cod_tipo_nom'");
			$cuentas = $this->v_cnmp17_fideicomiso_cuentas_vision->findAll($condic_cods." and cod_tipo_nomina='$cod_tipo_nom'", 'cod_cargo, cod_ficha, cedula_identidad, primer_nombre, segundo_nombre, primer_apellido, segundo_apellido, cuenta_bancaria', "cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, cedula_identidad ASC",150,$pagina,null);
		}else if($tipo_ope==2){
			echo "<script>
					document.getElementById('id_cedula_part').value='';
					document.getElementById('input_cedu_part').style.display = 'none';
				</script>";
			$Tfilas=$this->v_cnmp17_fideicomiso_cuentas_vision->findCount($condic_cods." and cod_tipo_nomina='$cod_tipo_nom' and cuenta_bancaria is not null");
			$cuentas = $this->v_cnmp17_fideicomiso_cuentas_vision->findAll($condic_cods." and cod_tipo_nomina='$cod_tipo_nom' and cuenta_bancaria is not null", 'cod_cargo, cod_ficha, cedula_identidad, primer_nombre, segundo_nombre, primer_apellido, segundo_apellido, cuenta_bancaria', "cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, cedula_identidad ASC",150,$pagina,null);
		}else if($tipo_ope==3){
			echo "<script>
					document.getElementById('id_cedula_part').value='';
					document.getElementById('input_cedu_part').style.display = 'none';
				</script>";
			$Tfilas=$this->v_cnmp17_fideicomiso_cuentas_vision->findCount($condic_cods." and cod_tipo_nomina='$cod_tipo_nom' and cuenta_bancaria is null");
			$cuentas = $this->v_cnmp17_fideicomiso_cuentas_vision->findAll($condic_cods." and cod_tipo_nomina='$cod_tipo_nom' and cuenta_bancaria is null", 'cod_cargo, cod_ficha, cedula_identidad, primer_nombre, segundo_nombre, primer_apellido, segundo_apellido, cuenta_bancaria', "cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, cedula_identidad ASC",150,$pagina,null);
				if(!empty($cuentas)){$veri=1;}
		}else if($tipo_ope==4){
			echo "<script>
					document.getElementById('id_cedula_part').value='';
					document.getElementById('input_cedu_part').style.display = 'block';
					document.getElementById('id_cedula_part').focus();
				</script>";
			$Tfilas=1;
			$cuentas = array(1=>4);
		}else{
			$cuentas = array();
		}

						        	$Tfilas=(int)ceil($Tfilas/150);
						        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
									$this->set('total_paginas',$Tfilas);
									$this->set('pagina_actual',$pagina);
									$this->set('ultimo',$Tfilas);
							        $this->set('siguiente',$pagina+1);
									$this->set('anterior',$pagina-1);
									$this->bt_nav($Tfilas,$pagina);

						if(!empty($cuentas)){
							$this->set('cuentas',$cuentas);
						}else{
							$this->set('cuentas',array());
							$this->set('mensajeError','No se encontraron datos, ......Favor Seleccionar Nómina....');
						}
	} // FIN $var3

	}// FIN tipo operacion

}// FIN function func_tipo_proceso



function cuenta_xced($v_cedula=null){
	$this->layout = "ajax";
    $cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$cod_tipo_nom = $this->Session->read('codf_tipo_nomina');
	$condic_cods = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep;

	if($v_cedula!=null){
		$cuentas = $this->v_cnmp17_fideicomiso_cuentas_vision->findAll($condic_cods." and cod_tipo_nomina='$cod_tipo_nom' and cedula_identidad='$v_cedula'", 'cod_cargo, cod_ficha, cedula_identidad, primer_nombre, segundo_nombre, primer_apellido, segundo_apellido, cuenta_bancaria', "cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, cedula_identidad ASC", null);
		if(!empty($cuentas)){
			$this->set('cuentas',$cuentas);
		}else{
			$this->set('mensajeError','No se encontraron datos con la C&eacute;dula de Identidad: '.$v_cedula);
		}
	}else{
		$this->set('mensajeError','La C&eacute;dula de Identidad es Inv&aacute;lida');
	}
}


function registrar_cuenta($id_t=null, $cc_cargo=null, $cc_ficha=null, $cced=null){
	$this->layout = "ajax";
	$this->set('id_t',$id_t);
	$this->set('cc_cargo',$cc_cargo);
	$this->set('cc_ficha',$cc_ficha);
	$this->set('cced',$cced);
	$cod_ent_banc = $this->Session->read('codf_ent_bancaria');
	$cod_suc_banc = $this->Session->read('codf_suc_bancaria');
	if($cod_ent_banc!=null && $cod_suc_banc!=null){
		$this->set('cod_ent_banc',$cod_ent_banc);
		$this->set('cod_suc_banc',$cod_suc_banc);
	}else{
		$this->set('cod_ent_banc','');
		$this->set('cod_suc_banc','');
		$this->set('mensajeError','El c&oacute;digo de la entidad y/o sucursal son inv&aacute;lidos - Seleccione la entidad y sucursal bancaria');
	}
}


function guardar_cuenta($id_tg=null){
	$this->layout = "ajax";
    $cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$condic_cods = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep;

if($id_tg!=null){
	$c_tipo_nomi = $this->data['cnmd17_fideicomiso_cuentas_bancarias']['codigo_tipo_nomina'];
	$cod_entidad_b = $this->data['cnmd17_fideicomiso_cuentas_bancarias']['codigo_entidad'];
	$cod_sucursal_b = $this->data['cnmd17_fideicomiso_cuentas_bancarias']['codigo_sucursal'];
	$codigo_ca = $this->data['cnmd17_fideicomiso_cuentas_bancarias']["codigo_cargo_t$id_tg"];
	$codigo_fi = $this->data['cnmd17_fideicomiso_cuentas_bancarias']["codigo_ficha_t$id_tg"];
	$cedula_ide = $this->data['cnmd17_fideicomiso_cuentas_bancarias']["cedula_t$id_tg"];
	$numero_cuent = $this->data['cnmd17_fideicomiso_cuentas_bancarias']["cuenta_bancaria_t$id_tg"];
	$cuenta_bancaria = $cod_entidad_b.$cod_sucursal_b.$numero_cuent;
	$formato_cuenta=$cod_entidad_b." ".$cod_sucursal_b." ".substr($numero_cuent, 0, 2)." ".substr($numero_cuent, 2, 10);

if($cod_entidad_b==''){
	$this->set('mensajeError','Seleccione la entidad bancaria');
	echo "<script>
			document.getElementById('select_1').focus();
		</script>";
}else if($cod_sucursal_b==''){
	$this->set('mensajeError','Seleccione la sucursal bancaria');
	echo "<script>
			document.getElementById('select_2').focus();
		</script>";
}else if(strlen($numero_cuent)<12){
	$this->set('mensajeError','El n&uacute;mero de la cuenta debe tener doce (12) d&iacute;gitos');
	echo "<script>
			document.getElementById('cuenta_bancaria_$id_tg').focus();
		</script>";
}else{

	if($this->v_cnmp17_fideicomiso_cuentas_vision->findCount($condic_cods." and cod_tipo_nomina=$c_tipo_nomi and cuenta_bancaria='$cuenta_bancaria'")!=0){
		$this->set('mensajeError','El n&uacute;mero de cuenta '.$formato_cuenta.' ya existe - verifique');
		echo "<script>
				document.getElementById('cuenta_bancaria_$id_tg').focus();
			</script>";
	}else{
    $sql_slect_cuenta = "SELECT * FROM cnmd17_fideicomiso_cuentas_bancarias WHERE ".$condic_cods."  and cedula_identidad=$cedula_ide;";
    $countSelect = $this->cnmd17_fideicomiso_cuentas_bancarias->execute($sql_slect_cuenta);

  if( count($countSelect)!=0){

		if($this->v_cnmp17_fideicomiso_cuentas_vision->findCount($condic_cods." and cedula_identidad=$cedula_ide")!=0){
			$sql_in_cuenta = "UPDATE cnmd17_fideicomiso_cuentas_bancarias SET cod_tipo_nomina=$c_tipo_nomi, cod_cargo=$codigo_ca, cod_ficha=$codigo_fi, cuenta_bancaria='$cuenta_bancaria' WHERE ".$condic_cods."  and cedula_identidad=$cedula_ide;";
		}else{
			$sql_in_cuenta = "INSERT INTO cnmd17_fideicomiso_cuentas_bancarias VALUES ($cod_presi, $cod_entidad, $cod_tipo_inst, $cod_inst, $cod_dep, $c_tipo_nomi, $codigo_ca, $codigo_fi, $cedula_ide, '$cuenta_bancaria', 0, 0);";
		}
  }else{
      $sql_in_cuenta = "INSERT INTO cnmd17_fideicomiso_cuentas_bancarias VALUES ($cod_presi, $cod_entidad, $cod_tipo_inst, $cod_inst, $cod_dep, $c_tipo_nomi, $codigo_ca, $codigo_fi, $cedula_ide, '$cuenta_bancaria', 0, 0);";    
  }
		$swi = $this->cnmd17_fideicomiso_cuentas_bancarias->execute($sql_in_cuenta);

		if($swi>1){
			$this->set('mensaje','La cuenta bancaria: # '.$formato_cuenta.' fue registrada exitosamente');
			echo "<script>
					document.getElementById('cuenta_$id_tg').innerHTML='$formato_cuenta';
					document.getElementById('fi_$id_tg').style.display = 'none';
					document.getElementById('cance_$id_tg').style.display = 'none';
					document.getElementById('fiedi_$id_tg').style.display = 'block';
					document.getElementById('fieli_$id_tg').style.display = 'block';
				</script>";
		}else{
			$this->set('mensajeError','La cuenta bancaria no pudo ser registrada');
		}
	}
}
}else{
	$this->set('mensajeError','Lo siento no llego el identificador de la cuenta - Intente Nuevamente');
}
}


function cancela_reg_cuenta($id_can=null, $cc_cargo=null, $cc_ficha=null, $cced=null){
	$this->layout = "ajax";
	$this->set('id_can',$id_can);
	$this->set('cc_cargo',$cc_cargo);
	$this->set('cc_ficha',$cc_ficha);
	$this->set('cced',$cced);
}


function eliminar($idel=null, $cce_cargo=null, $cce_ficha=null, $cceed=null, $num_cuenta=null){
	$this->layout = "ajax";
    $cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$condic_cods = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep;

	$nomina = $this->Session->read('codf_tipo_nomina');
	if($nomina!=""){
			$sw1=$this->cnmd17_fideicomiso_cuentas_bancarias->execute("delete from cnmd17_fideicomiso_cuentas_bancarias where ".$condic_cods." and cod_tipo_nomina=$nomina and cod_cargo=$cce_cargo and cod_ficha=$cce_ficha and cedula_identidad=$cceed and cuenta_bancaria='$num_cuenta'");
			if($sw1>1){
				$this->set('mensaje','la cuenta bancaria se elimin&oacute; exitosamente');
				$this->set('id_can',$idel);
				$this->set('cc_cargo',$cce_cargo);
				$this->set('cc_ficha',$cce_ficha);
				$this->set('cced',$cceed);
			}else{
				$this->set('mensajeError','no se pudo eliminar la cuenta bancaria');
			}
	}else{
		$this->set('mensajeError','debe seleccionar el codigo de la nomina');
	}
}


function modificar($id_t=null, $cc_cargo=null, $cc_ficha=null, $cced=null, $num_cuenta=null){
	$this->layout = "ajax";
	$cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$cod_tipo_nom = $this->Session->read('codf_tipo_nomina');
	$condic_cods = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep;

	$this->set('id_t',$id_t);
	$this->set('cc_cargo',$cc_cargo);
	$this->set('cc_ficha',$cc_ficha);
	$this->set('cced',$cced);

	if($num_cuenta!=null){
		$this->set('cod_ent_banc',substr($num_cuenta, 0, 4));
		$this->set('cod_suc_banc',substr($num_cuenta, 4, 4));
		$this->set('numero_cuenta',substr($num_cuenta, 8, 12));
	}else{
		$this->set('cod_ent_banc','');
		$this->set('cod_suc_banc','');
		$this->set('numero_cuenta','');
		$this->set('mensajeError','no se encontro la cuenta bancaria');
	}
}


function modificar_cuenta($id_tg=null, $nume_cuenta=null){
	$this->layout = "ajax";
    $cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$cod_tipo_nom = $this->Session->read('codf_tipo_nomina');
	$condic_cods = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep;

if($id_tg!=null){
	$c_tipo_nomi = $this->data['cnmd17_fideicomiso_cuentas_bancarias']['codigo_tipo_nomina'];
	$cod_entidad_b = $this->data['cnmd17_fideicomiso_cuentas_bancarias']["codigo_entidad_t$id_tg"];
	$cod_sucursal_b = $this->data['cnmd17_fideicomiso_cuentas_bancarias']["codigo_sucursal_t$id_tg"];
	$codigo_ca = $this->data['cnmd17_fideicomiso_cuentas_bancarias']["codigo_cargo_t$id_tg"];
	$codigo_fi = $this->data['cnmd17_fideicomiso_cuentas_bancarias']["codigo_ficha_t$id_tg"];
	$cedula_ide = $this->data['cnmd17_fideicomiso_cuentas_bancarias']["cedula_t$id_tg"];
	$numero_cuent = $this->data['cnmd17_fideicomiso_cuentas_bancarias']["cuenta_bancaria_t$id_tg"];
	$cuenta_bancaria = $cod_entidad_b.$cod_sucursal_b.$numero_cuent;
	$formato_cuenta=$cod_entidad_b." ".$cod_sucursal_b." ".substr($numero_cuent, 0, 2)." ".substr($numero_cuent, 2, 10);

if(strlen($numero_cuent)<12){
	$this->set('mensajeError','El n&uacute;mero de la cuenta debe tener doce (12) d&iacute;gitos');
	echo "<script>
			document.getElementById('cuenta_bancaria_$id_tg').focus();
		</script>";
}else{

	if($this->v_cnmp17_fideicomiso_cuentas_vision->findCount($condic_cods." and cod_tipo_nomina=$cod_tipo_nom and cod_cargo!=$codigo_ca and cod_ficha!=$codigo_fi and cedula_identidad!=$cedula_ide and cuenta_bancaria='$cuenta_bancaria'")!=0){
		$this->set('mensajeError','El n&uacute;mero de cuenta '.$formato_cuenta.' ya existe - verifique');
		echo "<script>
				document.getElementById('cuenta_bancaria_$id_tg').focus();
			</script>";
	}else{
		$sql_up_cuenta = "UPDATE cnmd17_fideicomiso_cuentas_bancarias SET cuenta_bancaria='$cuenta_bancaria' where ".$condic_cods." and cod_tipo_nomina=$cod_tipo_nom and cod_cargo=$codigo_ca and cod_ficha=$codigo_fi and cedula_identidad=$cedula_ide and cuenta_bancaria='$nume_cuenta'";
		$swi2 = $this->cnmd17_fideicomiso_cuentas_bancarias->execute($sql_up_cuenta);

		if($swi2>1){
			$this->set('mensaje','La cuenta bancaria: # '.$nume_cuenta.' fue modificada por: '.$formato_cuenta.' exitosamente');
			echo "<script>
					document.getElementById('save_$id_tg').style.display = 'none';
					document.getElementById('cance_save_$id_tg').style.display = 'none';
					document.getElementById('cuenta_$id_tg').innerHTML='$formato_cuenta';
					document.getElementById('fiedi_$id_tg').style.display = 'block';
					document.getElementById('fieli_$id_tg').style.display = 'block';
				</script>";
		}else{
			$this->set('mensajeError','La cuenta bancaria no pudo ser modificada');
		}
	}
}
}else{
	$this->set('mensajeError','Lo siento no llego el identificador de la cuenta - Intente Nuevamente');
}
}


function cancela_mod_cuenta($id_tg=null, $cadena_cuenta=null){
	$this->layout = "ajax";
	$formato_cuenta=substr($cadena_cuenta, 0, 4)." ".substr($cadena_cuenta, 4, 4)." ".substr($cadena_cuenta, 8, 2)." ".substr($cadena_cuenta, 10, 10);
			echo "<script>
					document.getElementById('save_$id_tg').style.display = 'none';
					document.getElementById('cance_save_$id_tg').style.display = 'none';
					document.getElementById('cuenta_$id_tg').innerHTML='$formato_cuenta';
					document.getElementById('fiedi_$id_tg').style.display = 'block';
					document.getElementById('fieli_$id_tg').style.display = 'block';
				</script>";
}






function codigo_nomina2($codigo=null){
	$this->layout = "ajax";

if($codigo!=null){
	$a = $this->v_cnmp17_fideicomiso_tipo_nomina->findAll($this->SQLCA()." and cod_tipo_nomina=".$codigo,array('cod_tipo_nomina','denominacion'));
    $this->Session->write('codfi_tipo_nomina',$codigo);

	if($a!=null){
		echo "<script>
				document.getElementById('codigo_tipo_nom').value='".mascara($a[0]['v_cnmp17_fideicomiso_tipo_nomina']['cod_tipo_nomina'], 3)."';
				document.getElementById('denominacion_deno_nom').value='".$a[0]['v_cnmp17_fideicomiso_tipo_nomina']['denominacion']."';
			</script>";
	}else{
		echo "<script>
				document.getElementById('codigo_tipo_nom').value='';
				document.getElementById('denominacion_deno_nom').value='';
			</script>";
	}
}else{
	$this->set('mensajeError','No llego el c&oacute;digo del tipo de n&oacute;mina para procesar - Seleccione N&oacute;mina');
	echo "<script>
			document.getElementById('codigo_tipo_nom').value='';
			document.getElementById('denominacion_deno_nom').value='';
		</script>";
}
}//fin codigo_nomina2




function index_calcular_fideicomiso(){
 	$this->layout ="ajax";
 	$this->Session->delete('codf_tipo_nomina');
 	$this->Session->delete('codf_ent_bancaria');
 	$this->Session->delete('codf_suc_bancaria');
   	$lista = $this->v_cnmp17_fideicomiso_tipo_nomina->generateList($this->SQLCA(), $order = 'cod_tipo_nomina', $limit = null, '{n}.v_cnmp17_fideicomiso_tipo_nomina.cod_tipo_nomina', '{n}.v_cnmp17_fideicomiso_tipo_nomina.denominacion');
	if(!empty($lista)){
		$this->concatenaN($lista, 'cod_tipo_nomina');
	}else{
		$this->set('cod_tipo_nomina', array());
	}
 	$entidades=$this->cstd01_entidades_bancarias->generateList(null,'cod_entidad_bancaria ASC', null, '{n}.cstd01_entidades_bancarias.cod_entidad_bancaria', '{n}.cstd01_entidades_bancarias.denominacion');
	$entidades = $entidades != null ? $entidades : array();
	$this->set('entidades', $entidades);
}



function index_cerrar_fideicomiso(){
 	$this->layout ="ajax";
 	$this->Session->delete('codf_tipo_nomina');
 	$this->Session->delete('codf_ent_bancaria');
 	$this->Session->delete('codf_suc_bancaria');
   	$lista = $this->v_cnmp17_fideicomiso_tipo_nomina->generateList($this->SQLCA(), $order = 'cod_tipo_nomina', $limit = null, '{n}.v_cnmp17_fideicomiso_tipo_nomina.cod_tipo_nomina', '{n}.v_cnmp17_fideicomiso_tipo_nomina.denominacion');
	if(!empty($lista)){
		$this->concatenaN($lista, 'cod_tipo_nomina');
	}else{
		$this->set('cod_tipo_nomina', array());
	}
 	$entidades=$this->cstd01_entidades_bancarias->generateList(null,'cod_entidad_bancaria ASC', null, '{n}.cstd01_entidades_bancarias.cod_entidad_bancaria', '{n}.cstd01_entidades_bancarias.denominacion');
	$entidades = $entidades != null ? $entidades : array();
	$this->set('entidades', $entidades);
}





function select_ano_trimestre($co_nomi=null){
 	$this->layout ="ajax";
 if($co_nomi!=null){
	$ano_trimestre = $this->cnmd17_fideicomiso_control_trimestre->findAll($this->SQLCA()." and cod_tipo_nomina='$co_nomi'", 'cnmd17_fideicomiso_control_trimestre.ano, cnmd17_fideicomiso_control_trimestre.trimestre', 'ano DESC, trimestre DESC');
	//var_dump($this->SQLCA()." and cod_tipo_nomina='$co_nomi'");exit();
	if(!empty($ano_trimestre)){
		$ano = $ano_trimestre[0]['cnmd17_fideicomiso_control_trimestre']['ano'];
		if($ano_trimestre[0]['cnmd17_fideicomiso_control_trimestre']['trimestre']<4){
			$trimestre = $ano_trimestre[0]['cnmd17_fideicomiso_control_trimestre']['trimestre']+1;
		}else{
			$ano = $ano_trimestre[0]['cnmd17_fideicomiso_control_trimestre']['ano']+1;
			$trimestre =1;
		}
	}else{
		$ano = $this->ano_ejecucion();
		$trimestre =1;
	}
	 		if ($trimestre==1){
				$periodo_desde = $ano."-01-01";
				$periodo_hasta = $ano."-03-31";
	 		}

	 		if ($trimestre==2){
				$periodo_desde = $ano."-04-01";
				$periodo_hasta = $ano."-06-30";
	 		}

	 		if ($trimestre==3){
	 			$periodo_desde = $ano."-07-01";
				$periodo_hasta = $ano."-09-30";
	 		}

	 		if ($trimestre==4){
	 			$periodo_desde = $ano."-10-01";
				$periodo_hasta = $ano."-12-31";
	 		}
	 		$perio_desde = cambia_fecha($periodo_desde);
	 		$perio_hasta = cambia_fecha($periodo_hasta);

 }else{
		$ano =0;
		$trimestre =0;
 }

		echo "<script>
				document.getElementById('ano').readOnly = true;
				document.getElementById('periodo_desde').readOnly = true;
				document.getElementById('periodo_hasta').readOnly = true;
				document.getElementById('trimestre').disabled = true;
				document.getElementById('ano').value = '".$ano."';
				document.getElementById('periodo_desde').value = '".$perio_desde."';
				document.getElementById('periodo_hasta').value = '".$perio_hasta."';
				document.getElementById('trimestre').options[$trimestre].selected = true;
			</script>";

$this->Session->write('cod_tipo_nomina',$co_nomi);
$this->Session->write('ano',$ano);
$this->Session->write('trimestre',$trimestre);
$this->Session->write('periodo_desde',$periodo_desde);
$this->Session->write('periodo_hasta',$periodo_hasta);

}



function select_ano_trimestre_2($tipo_nomina=null){

 	if($tipo_nomina!=null){
		$ano_trimestre = $this->cnmd17_fideicomiso_control_trimestre->findAll($this->SQLCA()." and cod_tipo_nomina='$tipo_nomina'", 'cnmd17_fideicomiso_control_trimestre.ano, cnmd17_fideicomiso_control_trimestre.trimestre', 'ano, trimestre DESC');
		if(!empty($ano_trimestre)){
			$ano = $ano_trimestre[0]['cnmd17_fideicomiso_control_trimestre']['ano'];
			$trimestre = $ano_trimestre[0]['cnmd17_fideicomiso_control_trimestre']['trimestre'];
		}else{
			$ano = $this->ano_ejecucion();
			$trimestre =1;
		}

		echo "<script>
				document.getElementById('ano').readOnly = false;
				document.getElementById('trimestre').disabled = false;
				document.getElementById('ano').value = '".$ano."';
				//document.getElementById('trimestre').options[$trimestre].selected = true;
			</script>";

 	}

}// FIN select_ano_trimestre_2





function calcular_fideicomiso(){
	$this->layout = "ajax";

	$cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep;

	$cod_tipo_nomina = $this->Session->read('cod_tipo_nomina');
	$ano = $this->Session->read('ano');
	$trimestre = $this->Session->read('trimestre');
	$periodo_desde = $this->Session->read('periodo_desde');
	$periodo_hasta = $this->Session->read('periodo_hasta');

	$sql_control_permanente = $this->Cnmd01->execute("select a.ano, a.trimestre from v_cnmp17_fideicomiso_control_perma a where ".$condicion.' and cod_tipo_nomina='.$cod_tipo_nomina.' and ano='.$ano.' and trimestre='.$trimestre."; ");

	if(isset($sql_control_permanente[0][0]["ano"]) && isset($sql_control_permanente[0][0]["trimestre"])){
  		$ano_perma = $sql_control_permanente[0][0]["ano"];
  		$trimestre_perma = $sql_control_permanente[0][0]["trimestre"];
  	}else{
		$ano_perma = 0;
		$trimestre_perma = 0;
  	}

	if($ano_perma==$ano && $trimestre_perma==$trimestre){

		echo "<script> fun_msj('ESTE TRIMESTRE YA FUE PROCESADO ANTERIORMENTE');</script>";

			   echo "<script>
        			$('procesar').value='Proceso no realizado';
        			$('procesar').disabled='true';
       		 		</script>";

	}else{

	$sql_busca_temporal = "SELECT count(*) AS suma from cnmd17_fideicomiso_trimestral_temporal WHERE cod_presi=$cod_presi and cod_entidad=$cod_entidad and cod_tipo_inst=$cod_tipo_inst and cod_inst=$cod_inst and cod_dep=$cod_dep and cod_tipo_nomina=$cod_tipo_nomina and ano=$ano and trimestre=$trimestre";
    $sql_busca = $this->Cnmd01->execute($sql_busca_temporal);
    if (isset($sql_busca)){
	$sql_elimina_temporal = "DELETE from cnmd17_fideicomiso_trimestral_temporal WHERE cod_presi=$cod_presi and cod_entidad=$cod_entidad and cod_tipo_inst=$cod_tipo_inst and cod_inst=$cod_inst and cod_dep=$cod_dep and cod_tipo_nomina=$cod_tipo_nomina and ano=$ano and trimestre=$trimestre";
    $sql_temporal = $this->Cnmd01->execute($sql_elimina_temporal);
    }


	$cuentas_trabajadores = $this->v_cnmp17_fideicomiso_cuentas_vision->findAll($condicion." and cod_tipo_nomina='$cod_tipo_nomina' and cuenta_bancaria is not null");
	if(!empty($cuentas_trabajadores)){

		    $sql_clasificacion = $this->Cnmd01->execute("select a.clasificacion_personal, a.dias_cobro  from cnmd01 a where ".$condicion.' and cod_tipo_nomina='.$cod_tipo_nomina.";");
 		    $clasifica  = isset($sql_clasificacion[0][0]["clasificacion_personal"])?$sql_clasificacion[0][0]["clasificacion_personal"]:0;
 		    $dias_cobro = isset($sql_clasificacion[0][0]["dias_cobro"])?$sql_clasificacion[0][0]["dias_cobro"]:0;

				if ($clasifica==2){
					$dias_ano=365;
				}else{
					$dias_ano=360;
				}

					if ($trimestre==1){
						$mes=3;
					}

						if ($trimestre==2){
							$mes=6;
						}

							if ($trimestre==3){
								$mes=9;
							}

								if ($trimestre==4){
								$mes=12;
								}

			    $mes_periodo=substr($periodo_hasta, 5, 2);
     			$ano_periodo=substr($periodo_hasta, 0, 4);
     			$mes_periodo_vige=mascara_dos($mes_periodo);

     			if ($mes_periodo==1){
     				$mes_periodo=12;
     				$ano_periodo=($ano_periodo-1);
     			}else{
     				$mes_periodo=mascara_dos($mes_periodo-1);
     			}
     			$periodo_mes_actual=$ano_periodo.$mes_periodo;
     			$periodo_mes_vigente=$ano_periodo.$mes_periodo_vige;



	foreach($cuentas_trabajadores as $trabajadores){

			$cod_cargo = $trabajadores['v_cnmp17_fideicomiso_cuentas_vision']['cod_cargo'];
			$cod_ficha = $trabajadores['v_cnmp17_fideicomiso_cuentas_vision']['cod_ficha'];
			$cedula_identidad = $trabajadores['v_cnmp17_fideicomiso_cuentas_vision']['cedula_identidad'];
			$cuenta_bancaria = $trabajadores['v_cnmp17_fideicomiso_cuentas_vision']['cuenta_bancaria'];
			$fecha_ingreso = $trabajadores['v_cnmp17_fideicomiso_cuentas_vision']['fecha_ingreso'];


			//ANTIGUEDAD ANTERIOR ADMINISTRACIÓN PÚBLICA
			$parametros = $cod_presi . "," . $cod_entidad . "," . $cod_tipo_inst . "," . $cod_inst . "," . $cod_dep . "," . $cod_tipo_nomina . "," . $cod_cargo . "," . $cod_ficha;
            $sql_admin_pub = $this->Cnmd01->execute("SELECT devolver_anos_experiencia($parametros) as anos;");
            $anos_admin = $sql_admin_pub[0][0]['anos'];


			//ANTIGUEDAD EN EL CARGO
			$antiguedad_trabajador = $this->Cnmd01->execute("select devolver_edad('".$periodo_hasta."', '".$fecha_ingreso."', 'ANO') as ano,devolver_edad('".$periodo_hasta."', '".$fecha_ingreso."', 'MES') as mes,devolver_edad('".$periodo_hasta."', '".$fecha_ingreso."', 'DIA') as dia");
			$dia_antig = $antiguedad_trabajador[0][0]['dia'];
            $mes_antig = $antiguedad_trabajador[0][0]['mes'];
            $ano_antig = $antiguedad_trabajador[0][0]['ano'];

            if ($ano_antig!=0 && $mes_antig>=1){$ano_antig_bv=($ano_antig+1);}else{$ano_antig_bv=$ano_antig;}

            $anos_total= ($ano_antig+$anos_admin);
            $anos_total_bv=($ano_antig_bv+$anos_admin);


		if($ano_antig!=0 || $mes_antig!=0){

/*
			// DEVOLVER SALARIO INTEGRAL FIDEICOMISO
			$parametros = $cod_presi . "," . $cod_entidad . "," . $cod_tipo_inst . "," . $cod_inst . "," . $cod_dep . "," . $cod_tipo_nomina . "," . $cod_cargo . "," . $cod_ficha . "," . 1 . "," . 5;
            $sql_salario_fide = $this->Cnmd01->execute("SELECT devolver_calculo_monto2_asig_escenario($parametros) as salario;");
            $salario_integral = $sql_salario_fide[0][0]['salario'];
            $salario_diario_integral=($salario_integral/$dias_cobro);
*/

            // MONTO COBRADO MES PERIODO HASTA VIGENTE
            $sql_salario_fide = $this->cnmd15_datos_personales->execute("SELECT sum(a.monto_cuota) as monto from v_cnmp17_fideicomiso_transa_histo_noin a where ".$condicion.' and cod_tipo_nomina='.$cod_tipo_nomina.' and cod_cargo='.$cod_cargo.' and cod_ficha='.$cod_ficha." and (a.desde >= $periodo_mes_vigente and a.desde <= $periodo_mes_vigente);");
  			$salario_integral = isset($sql_salario_fide[0][0]["monto"])?$sql_salario_fide[0][0]["monto"]:0;
			$salario_diario_integral=($salario_integral/30);


			// MONTO COBRADO MES ANTERIOR
            $sql_mes_anterior = $this->cnmd15_datos_personales->execute("SELECT sum(a.monto_cuota) as monto from v_cnmp17_fideicomiso_transa_histo_noin a where ".$condicion.' and cod_tipo_nomina='.$cod_tipo_nomina.' and cod_cargo='.$cod_cargo.' and cod_ficha='.$cod_ficha." and (a.desde >= $periodo_mes_actual and a.desde <= $periodo_mes_actual);");
  			$salario_mes_anterior = isset($sql_mes_anterior[0][0]["monto"])?$sql_mes_anterior[0][0]["monto"]:0;
			$salario_dia_mes_anterior=($salario_mes_anterior/30);


			if ($salario_dia_mes_anterior>$salario_diario_integral){$salario_diario_integral=$salario_dia_mes_anterior;}
			if ($salario_diario_integral>$salario_dia_mes_anterior){$salario_dia_mes_anterior=$salario_diario_integral;}


			// DIAS BONO VACACIONAL
			$sql_bono_vaca = $this->cnmd15_datos_personales->execute("select a.dias from v_cnmd15_bono_vaca a where ".$condicion.' and cod_tipo_nomina='.$cod_tipo_nomina." and ('$periodo_hasta' >= a.fecha_desde_bono_vaca::date and '$periodo_hasta' <= a.fecha_hasta_bono_vaca::date) and ('$anos_total_bv' >= a.desde_antiguedad and '$anos_total_bv' <= a.hasta_antiguedad)  order BY dias ASC limit 1; ");

 		    $dias_bova = isset($sql_bono_vaca[0][0]["dias"])?$sql_bono_vaca[0][0]["dias"]:0;

				if ($dias_bova!=0){
					$salario_diario_bova=$salario_diario_integral;
					$monto_bova=(($salario_diario_bova*$dias_bova)/$dias_ano);
				}else{
					$salario_diario_bova=0;
					$monto_bova=0;
				}

			// DIAS SEMANA SALARIAL
			$sql_sema_salarial = $this->cnmd15_datos_personales->execute("select a.dias from v_cnmd15_semana_salarial a where ".$condicion.' and cod_tipo_nomina='.$cod_tipo_nomina." and ('$periodo_hasta' >= a.fecha_desde_bono_vaca::date and '$periodo_hasta' <= a.fecha_hasta_bono_vaca::date) and ('$anos_total' >= a.desde_antiguedad and '$anos_total' <= a.hasta_antiguedad)  order BY dias ASC limit 1; ");
            $dias_sem = isset($sql_sema_salarial[0][0]["dias"])?$sql_sema_salarial[0][0]["dias"]:0;

				if ($dias_sem!=0){
					$salario_diario_sem=$salario_diario_integral;
					$monto_sem=(($salario_diario_sem*$dias_sem)/$dias_ano);
				}else{
					$salario_diario_sem=0;
					$monto_sem=0;
				}

            // DIAS AGUINALDO
            $sql_aguinaldo = $this->cnmd15_datos_personales->execute("select a.dias from v_cnmd15_aguinaldo a where ".$condicion.' and cod_tipo_nomina='.$cod_tipo_nomina." and ('$periodo_hasta' >= a.fecha_desde_aguinaldo::date and '$periodo_hasta' <= a.fecha_hasta_aguinaldo::date) and ('$anos_total' >= a.desde_antiguedad and '$anos_total' <= a.hasta_antiguedad)  order BY dias ASC limit 1; ");
  			$dias_agui = isset($sql_aguinaldo[0][0]["dias"])?$sql_aguinaldo[0][0]["dias"]:0;

				if ($dias_agui!=0){
					$salario_diario_agui=$salario_diario_integral;
					$salario_total_agui=($salario_dia_mes_anterior+$monto_bova+$monto_sem);
					$monto_agui=(($salario_total_agui*$dias_agui)/$dias_ano);
				}else{
					$salario_diario_agui=0;
					$salario_total_agui=0;
					$monto_agui=0;
				}

			$salario_diario_pago=($salario_dia_mes_anterior+$monto_bova+$monto_sem+$monto_agui);

			$salario_dia_mes_anterior=$this->redondeo($salario_dia_mes_anterior);
			$salario_diario_integral=$this->redondeo($salario_diario_integral);
			$salario_diario_bova=$this->redondeo($salario_diario_bova);
			$salario_diario_sem=$this->redondeo($salario_diario_sem);
			$salario_diario_agui=$this->redondeo($salario_diario_agui);
			$salario_total_agui=$this->redondeo($salario_total_agui);
			$monto_bova=$this->redondeo($monto_bova);
			$monto_sem=$this->redondeo($monto_sem);
			$monto_agui=$this->redondeo($monto_agui);

     		$ano_ingreso=substr($fecha_ingreso, 0, 4);
     		$mes_ingreso=substr($fecha_ingreso, 5, 2);
     		$dia_ingreso=substr($fecha_ingreso, 8, 2);

				if(($ano_ingreso<1997) || ($ano_ingreso==1997 && $mes_ingreso<6) || ($ano_ingreso==1997 && $mes_ingreso==6 && $dia_ingreso<=18)){
					$fecha_ingreso="1997-06-19";
				}

					//ANTIGUEDAD DEL RÉGIMEN ANTERIOR AL NUEVO RÉGIMEN
					$antiguedad_trabajador = $this->Cnmd01->execute("select devolver_edad('".$periodo_hasta."', '".$fecha_ingreso."', 'ANO') as ano,devolver_edad('".$periodo_hasta."', '".$fecha_ingreso."', 'MES') as mes,devolver_edad('".$periodo_hasta."', '".$fecha_ingreso."', 'DIA') as dia");
					$dia_antig = $antiguedad_trabajador[0][0]['dia'];
            		$mes_antig = $antiguedad_trabajador[0][0]['mes'];
           		    $ano_antig = $antiguedad_trabajador[0][0]['ano'];


			 $dias_fideicomiso=15;

			 		if ($ano_antig==0 && $mes_antig==1){
			 			$dias_fideicomiso=5;
			 		}
			 			if ($ano_antig==0 && $mes_antig==2){
			 			$dias_fideicomiso=10;
			 			}

			$dias_adicionales=0;
			$monto_dias_adicionales=0;
			$salario_anual_anterior=0;
			$salario_diario_anual_anterior=0;
			$me=1;
			$mes_periodo=mascara_dos($mes);

						if ($mes_ingreso==$mes){
							$me=0;
						}

							if(($mes_ingreso+1)==$mes){
								$me=0;
								$mes_periodo=mascara_dos($mes-1);
							}

								if(($mes_ingreso+2)==$mes){
								$me=0;
								$mes_periodo=mascara_dos($mes-2);
								}

				if($ano_antig>=1 && $me==0){
					$dias_adicionales=(($ano_antig-1)*2);
					if($dias_adicionales>30){
						$dias_adicionales=30;
					}

     					$ano_periodo=substr($periodo_hasta, 0, 4);
     					$periodo_ano_actual=$ano_periodo.$mes_periodo;

     						if ($mes_periodo==12){
     							$ano_periodo_anual=$ano_periodo;
     							$mes_periodo_anual=mascara_dos($mes_periodo_anual=1);
     						}else{
     							$ano_periodo_anual=($ano_periodo-1);
     							$mes_periodo_anual=mascara_dos($mes_periodo+1);
     						}
     						$periodo_ano_anterior=$ano_periodo_anual.$mes_periodo_anual;

						// MONTO COBRADO AÑO ANTERIOR
            			$sql_ano_anterior = $this->cnmd15_datos_personales->execute("SELECT sum(a.monto_cuota) as monto from v_cnmp17_fideicomiso_transa_histo_inclu a where ".$condicion.' and cod_tipo_nomina='.$cod_tipo_nomina.' and cod_cargo='.$cod_cargo.' and cod_ficha='.$cod_ficha." and (desde >= $periodo_ano_anterior and desde <= $periodo_ano_actual);");
  						$salario_anual_anterior = isset($sql_ano_anterior[0][0]["monto"])?$sql_ano_anterior[0][0]["monto"]:0;
						$salario_diario_anual_anterior=($salario_anual_anterior/$dias_ano);

						$monto_dias_adicionales=$this->redondeo(($dias_adicionales*$salario_diario_anual_anterior));
						$salario_diario_anual_anterior=$this->redondeo($salario_diario_anual_anterior);
				}

						$monto_fideicomiso=$this->redondeo(($salario_diario_pago*$dias_fideicomiso));


						$observaciones = "Pago del Trimestre Nº ".$trimestre." del año ".$ano;

					 $sql_insert_depo_tempo = "INSERT INTO cnmd17_fideicomiso_trimestral_temporal (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, cod_cargo, cod_ficha, cedula_identidad, ano, trimestre, fecha_ingreso, ano_antiguedad, ano_antig_inst_pub, dias_ano, salario_mensual_integral, salario_diario_integral, salario_mes_anterior, salario_dia_mes_anterior, dias_bova, salario_diario_bova, monto_bova, dias_sem, salario_diario_sem, monto_sem, dias_agui,  salario_diario_agui, salario_total_agui, monto_agui, salario_anual_anterior, salario_diario_anual_anterior, dias_adicionales, monto_dias_adicionales, salario_diario_pago, dias_fideicomiso, monto_fideicomiso, periodo_desde, periodo_hasta, depositado_cuenta, observaciones) VALUES ('".$cod_presi."','".$cod_entidad."','".$cod_tipo_inst."','".$cod_inst."','".$cod_dep."','".$cod_tipo_nomina."','".$cod_cargo."','".$cod_ficha."','".$cedula_identidad."','".$ano."','".$trimestre."','".$fecha_ingreso."','".$ano_antig."','".$anos_admin."','".$dias_ano."','".$salario_integral."','".$salario_diario_integral."','".$salario_mes_anterior."','".$salario_dia_mes_anterior."','".$dias_bova."','".$salario_diario_bova."','".$monto_bova."','".$dias_sem."','".$salario_diario_sem."','".$monto_sem."','".$dias_agui."','".$salario_diario_agui."','".$salario_total_agui."','".$monto_agui."','".$salario_anual_anterior."','".$salario_diario_anual_anterior."','".$dias_adicionales."','".$monto_dias_adicionales."','".$salario_diario_pago."','".$dias_fideicomiso."','".$monto_fideicomiso."','".$periodo_desde."','".$periodo_hasta."','".$cuenta_bancaria."','".$observaciones."');";
					//var_dump($sql_insert_depo_tempo); exit();
					 $temporal = $this->cnmd15_datos_personales->execute($sql_insert_depo_tempo);


				}// FIN ANTIGUEDAD

			}// FIN FOREACH

	   echo "<script>
        	$('procesar').value='Proceso Finalizado';
        	$('procesar').disabled='true';
       		 </script>";

		}// FIN IF

	}// FIN VERIFICA TRIMESTRE


}// FIN calcular_fideicomiso






function cerrar_fideicomiso(){
	$this->layout = "ajax";

	$cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep;

	$cod_tipo_nomina = $this->Session->read('cod_tipo_nomina');
	$ano = $this->Session->read('ano');
	$trimestre = $this->Session->read('trimestre');
	$periodo_desde = $this->Session->read('periodo_desde');
	$periodo_hasta = $this->Session->read('periodo_hasta');

	$sql_busca_temporal = "SELECT * from cnmd17_fideicomiso_trimestral_temporal WHERE cod_presi=$cod_presi and cod_entidad=$cod_entidad and cod_tipo_inst=$cod_tipo_inst and cod_inst=$cod_inst and cod_dep=$cod_dep and cod_tipo_nomina=$cod_tipo_nomina and ano=$ano and trimestre=$trimestre";
    $sql_temporal = $this->Cnmd01->execute($sql_busca_temporal);
    if(!empty($sql_temporal)){

    	foreach($sql_temporal as $temporal){
    		$cod_presi = $temporal[0]['cod_presi'];
    		$cod_entidad = $temporal[0]['cod_entidad'];
    		$cod_tipo_inst = $temporal[0]['cod_tipo_inst'];
    		$cod_inst = $temporal[0]['cod_inst'];
    		$cod_dep = $temporal[0]['cod_dep'];
    		$cod_tipo_nomina = $temporal[0]['cod_tipo_nomina'];
    		$cod_cargo = $temporal[0]['cod_cargo'];
    		$cod_ficha = $temporal[0]['cod_ficha'];
    		$cedula_identidad = $temporal[0]['cedula_identidad'];
    		$fecha_ingreso = $temporal[0]['fecha_ingreso'];
    		$ano_antig = $temporal[0]['ano_antiguedad'];
    		$anos_admin = $temporal[0]['ano_antig_inst_pub'];
    		$dias_ano = $temporal[0]['dias_ano'];
    		$salario_integral = $temporal[0]['salario_mensual_integral'];
    		$salario_diario_integral = $temporal[0]['salario_diario_integral'];
    		$salario_mes_anterior = $temporal[0]['salario_mes_anterior'];
    		$salario_dia_mes_anterior = $temporal[0]['salario_dia_mes_anterior'];
    		$dias_bova = $temporal[0]['dias_bova'];
    		$salario_diario_bova = $temporal[0]['salario_diario_bova'];
    		$monto_bova = $temporal[0]['monto_bova'];
    		$dias_sem = $temporal[0]['dias_sem'];
    		$salario_diario_sem = $temporal[0]['salario_diario_sem'];
    		$monto_sem = $temporal[0]['monto_sem'];
    		$dias_agui = $temporal[0]['dias_agui'];
    		$salario_diario_agui = $temporal[0]['salario_diario_agui'];
    		$salario_total_agui = $temporal[0]['salario_total_agui'];
    		$monto_agui = $temporal[0]['monto_agui'];
    		$salario_anual_anterior = $temporal[0]['salario_anual_anterior'];
    		$salario_diario_anual_anterior = $temporal[0]['salario_diario_anual_anterior'];
    		$dias_adicionales = $temporal[0]['dias_adicionales'];
    		$monto_dias_adicionales = $temporal[0]['monto_dias_adicionales'];
    		$salario_diario_pago = $temporal[0]['salario_diario_pago'];
    		$dias_fideicomiso = $temporal[0]['dias_fideicomiso'];
    		$monto_fideicomiso = $temporal[0]['monto_fideicomiso'];
    		$periodo_desde = $temporal[0]['periodo_desde'];
    		$periodo_hasta = $temporal[0]['periodo_hasta'];
    		$cuenta_bancaria = $temporal[0]['depositado_cuenta'];

    		$sql_insert_depo_perma = "INSERT INTO cnmd17_fideicomiso_trimestral_perma (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, cod_cargo, cod_ficha, cedula_identidad, ano, trimestre, fecha_ingreso, ano_antiguedad, ano_antig_inst_pub, dias_ano, salario_mensual_integral, salario_diario_integral, salario_mes_anterior, salario_dia_mes_anterior, dias_bova, salario_diario_bova, monto_bova, dias_sem, salario_diario_sem, monto_sem, dias_agui,  salario_diario_agui, salario_total_agui, monto_agui, salario_anual_anterior, salario_diario_anual_anterior, dias_adicionales, monto_dias_adicionales, salario_diario_pago, dias_fideicomiso, monto_fideicomiso, periodo_desde, periodo_hasta, depositado_cuenta) VALUES ('".$cod_presi."','".$cod_entidad."','".$cod_tipo_inst."','".$cod_inst."','".$cod_dep."','".$cod_tipo_nomina."','".$cod_cargo."','".$cod_ficha."','".$cedula_identidad."','".$ano."','".$trimestre."','".$fecha_ingreso."','".$ano_antig."','".$anos_admin."','".$dias_ano."','".$salario_integral."','".$salario_diario_integral."','".$salario_mes_anterior."','".$salario_dia_mes_anterior."','".$dias_bova."','".$salario_diario_bova."','".$monto_bova."','".$dias_sem."','".$salario_diario_sem."','".$monto_sem."','".$dias_agui."','".$salario_diario_agui."','".$salario_total_agui."','".$monto_agui."','".$salario_anual_anterior."','".$salario_diario_anual_anterior."','".$dias_adicionales."','".$monto_dias_adicionales."','".$salario_diario_pago."','".$dias_fideicomiso."','".$monto_fideicomiso."','".$periodo_desde."','".$periodo_hasta."','".$cuenta_bancaria."');";
			$permanente = $this->Cnmd01->execute($sql_insert_depo_perma);

    	}// FIN FOREACH



			$sql_busca_control_trimestre = $this->Cnmd01->execute("SELECT a.ano, a.trimestre from cnmd17_fideicomiso_control_trimestre a where ".$condicion.' and cod_tipo_nomina='.$cod_tipo_nomina."; ");
  				if(!empty($sql_busca_control_trimestre)){
  					$sql_update_control_trimestre = $this->Cnmd01->execute("UPDATE cnmd17_fideicomiso_control_trimestre SET ano=$ano, trimestre=$trimestre where ".$condicion.' and cod_tipo_nomina='.$cod_tipo_nomina."; ");
  				}else{
					$sql_insert_control_trimestre = "INSERT INTO cnmd17_fideicomiso_control_trimestre (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, ano, trimestre) VALUES ('".$cod_presi."','".$cod_entidad."','".$cod_tipo_inst."','".$cod_inst."','".$cod_dep."','".$cod_tipo_nomina."','".$ano."','".$trimestre."');";
					$control_trimestre = $this->Cnmd01->execute($sql_insert_control_trimestre);
  				}
			$sql_elimina_temporal = "DELETE from cnmd17_fideicomiso_trimestral_temporal WHERE cod_presi=$cod_presi and cod_entidad=$cod_entidad and cod_tipo_inst=$cod_tipo_inst and cod_inst=$cod_inst and cod_dep=$cod_dep and cod_tipo_nomina=$cod_tipo_nomina and ano=$ano and trimestre=$trimestre";
    		$sql_temporal = $this->Cnmd01->execute($sql_elimina_temporal);

					$x="1";
					$ene='2';
 					$feb='2';
 					$mar='2';
 					$abr='2';
 					$may='2';
 					$jun='2';
 					$jul='2';
 					$ago='2';
 					$sep='2';
 					$oct='2';
 					$nov='2';
 					$dic='2';

 					if ($trimestre==1){
 						$mes_a="ene";
 						$mes_b="feb";
 						$mes_c="mar";
 						$ene='1';
 						$feb='1';
 						$mar='1';
 					}

 						if ($trimestre==2){
 							$mes_a="abr";
 							$mes_b="may";
 							$mes_c="jun";
 							$abr='1';
 							$may='1';
 							$jun='1';
 						}

 							if ($trimestre==3){
 								$mes_a="jul";
 								$mes_b="ago";
 								$mes_c="sep";
 								$jul='1';
 								$ago='1';
 								$sep='1';
 							}

 								if ($trimestre==4){
 									$mes_a="oct";
 									$mes_b="nov";
 									$mes_c="dic";
 									$oct='1';
 									$nov='1';
 						    		$dic='1';
 								}



 				 $sql_busca_deposito = $this->Cnmd01->execute("SELECT a.ano from cnmd15_depo_fideicomiso a where ".$condicion.' and cod_tipo_nomina='.$cod_tipo_nomina.' and ano='.$ano."; ");
  				if(!empty($sql_busca_deposito)){
  					$sql_update_deposito = $this->Cnmd01->execute("UPDATE cnmd15_depo_fideicomiso SET ".$mes_a."='".$x."', ".$mes_b."='".$x."', ".$mes_c."='".$x."'  where ".$condicion.' and cod_tipo_nomina='.$cod_tipo_nomina.' and ano='.$ano."; ");
  				}else{
					$sql_insert_deposito = "INSERT INTO cnmd15_depo_fideicomiso (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, ano, ene, feb, mar, abr, may, jun, jul, ago, sep, oct, nov, dic) VALUES ('".$cod_presi."','".$cod_entidad."','".$cod_tipo_inst."','".$cod_inst."','".$cod_dep."','".$cod_tipo_nomina."','".$ano."','".$ene."','".$feb."','".$mar."','".$abr."','".$may."','".$jun."','".$jul."','".$ago."','".$sep."','".$oct."','".$nov."','".$dic."');";
					$control_control_deposito = $this->Cnmd01->execute($sql_insert_deposito);
  				}

		echo "<script>
        		$('procesar').value='Proceso Finalizado';
        		$('procesar').disabled='true';
        	 </script>";


    }else{

		echo "<script> fun_msj('!!NO PUEDE CERRAR!! ANTES DEBE CALCULAR EL FIDEICOMISO DE ESTE TRIMESTRE');</script>";

			   echo "<script>
        			$('procesar').value='Proceso no realizado';
        			$('procesar').disabled='true';
        			</script>";

    }

}// FIN cerrar_fideicomiso


function deno_nomina2 ($cod_tipo_nomina=null) {
     $this->layout="ajax";
     if (isset($cod_tipo_nomina)) {
         $a = $this->v_cnmp17_fideicomiso_tipo_nomina->findAll($this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina,'cod_tipo_nomina, denominacion');
		if(!empty($a)){
		echo "<script>
				document.getElementById('in_cod_tipo_nomina').value='".mascara($a[0]['v_cnmp17_fideicomiso_tipo_nomina']['cod_tipo_nomina'], 3)."';
				document.getElementById('in_denominacion_tipo_nomina').value='".$a[0]['v_cnmp17_fideicomiso_tipo_nomina']['denominacion']."';
			</script>";
		}

		if($this->cnmd17_fideicomiso_control_trimestre->findCount($this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina)==0){
			echo "<script> fun_msj('NO SE ENCONTRAR&Oacute;N DATOS PARA PROCESAR');
  				document.getElementById('id_enviar_generar_rp').disabled=true;
			</script>";
		}else{
			echo "<script>
  				document.getElementById('id_enviar_generar_rp').disabled=false;
  			</script>";

		}
	}else{
		echo "<script> fun_msj('No lleg&oacute; el c&oacute;digo del tipo de n&oacute;mina a procesar, Seleccione N&oacute;mina');
				document.getElementById('in_cod_tipo_nomina').value='';
				document.getElementById('in_denominacion_tipo_nomina').value='';
				document.getElementById('id_enviar_generar_rp').disabled=true;
			</script>";
	}
}



function index_fideicomiso_resumido(){
	$this->layout = "ajax";
	$this->Session->delete('da_pasoa');
	$this->Session->delete('datos_nomina');
	$this->Session->delete('denom_nomina');
	$cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep;

    $lista = $this->v_cnmp17_fideicomiso_tipo_nomina->generateList($condicion, 'cod_tipo_nomina ASC', null, '{n}.v_cnmp17_fideicomiso_tipo_nomina.cod_tipo_nomina', '{n}.v_cnmp17_fideicomiso_tipo_nomina.denominacion');
	if(!empty($lista)){
		$this->concatenaN($lista, 'tipo_nomina');
	}else{
		$this->set('tipo_nomina', array());
	}

} // fin function index_fideicomiso_resumido




function resumen_fideicomisos(){
	set_time_limit(0);
	ini_set("memory_limit","2560M");
	$this->layout = "pdf";

	$datos=$this->data["cnmd17_fideicomiso_cuentas_bancarias"];
	$cod_tipo_nomina = $datos["cod_tipo_nomina"];
	$denominacion = $datos["denominacion_tipo_nomina"];
	$ano_pe = $datos["ano_fide"];
	$mes_pe = $datos["trimestre"];

	$estado = $this->cugd01_estados->execute("SELECT denominacion FROM cugd01_estados WHERE cod_republica='".$this->verifica_SS(1)."' and cod_estado='".$this->verifica_SS(2)."';");
	$_SESSION['estado'] = $estado[0][0]['denominacion'];
	$institucion = $this->cugd01_estados->execute("SELECT denominacion FROM cugd02_institucion WHERE cod_tipo_institucion='".$this->verifica_SS(3)."' and cod_institucion='".$this->verifica_SS(4)."';");
	$_SESSION['institucion'] = $institucion[0][0]['denominacion'];
	$dependencia = $this->cugd01_estados->execute("SELECT denominacion FROM cugd02_dependencias WHERE cod_tipo_institucion='".$this->verifica_SS(3)."' and cod_institucion='".$this->verifica_SS(4)."' and cod_dependencia='".$this->verifica_SS(5)."';");
	$_SESSION['dependencia'] = $dependencia[0][0]['denominacion'];

if($cod_tipo_nomina!=null && $ano_pe!=null && $mes_pe!=null){

	$datos_fnom = $this->v_cnmp17_fideicomiso_tipo_nomina->findAll($this->SQLCA()." AND cod_tipo_nomina='$cod_tipo_nomina'", null, null);
	!empty($datos_fnom) ? $this->set('datos_fnom', $datos_fnom) : $this->set('datos_fnom', array());
	$this->set('denominacion_nom', $denominacion);

	$datos_resumen = $this->cnmd16_vacaciones_bonos_temporal->findAll($this->SQLCA()." AND cod_tipo_nomina='$cod_tipo_nomina' AND ano='$ano_pe' AND mes='$mes_pe'", null, null);
	if(!empty($datos_resumen)){
		$this->set('datos_resumen', $datos_resumen);
	}else{
		$this->set('datos_resumen', array());
	}
}else{
	$this->set('denominacion_nom', '');
	$this->set('datos_fnom', array());
	$this->set('datos_resumen', array());
}

}


function deno_nomina_d ($cod_tipo_nomina=null) {
     $this->layout="ajax";
     if (isset($cod_tipo_nomina)) {
         $a = $this->v_cnmp17_fideicomiso_tipo_nomina->findAll($this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina,'cod_tipo_nomina, denominacion');
		if(!empty($a)){
			$this->Session->write('codigo_tipo_nominar', $a[0]['v_cnmp17_fideicomiso_tipo_nomina']['cod_tipo_nomina']);
		echo "<script>
				document.getElementById('in_cod_tipo_nomina').value='".mascara($a[0]['v_cnmp17_fideicomiso_tipo_nomina']['cod_tipo_nomina'], 3)."';
				document.getElementById('in_denominacion_tipo_nomina').value='".$a[0]['v_cnmp17_fideicomiso_tipo_nomina']['denominacion']."';
			</script>";
		}else{
			$this->Session->write('codigo_tipo_nominar', 0);
		}

		if($this->v_cnmp17_fideicomiso_trimestral_perma->findCount($this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina)==0){
			echo "<script> fun_msj('NO SE ENCONTRAR&Oacute;N DATOS PARA PROCESAR');
  				document.getElementById('id_enviar_generar_rp').disabled=true;
			</script>";
			echo '<script>document.getElementById("empleado_ide").style.visibility="hidden";</script>';
			echo "<script> document.getElementById('empleado_ide').innerHTML='';
							if(document.getElementById('id_cedula_identidad')){ document.getElementById('id_cedula_identidad').value=''; }
						   	if(document.getElementById('id_nombre_empleado')){ document.getElementById('id_nombre_empleado').value=''; }
						   	document.getElementById('id_enviar_generar_rp').disabled=true;
				</script>";
		}else{
			$this->tipo_proceso_envio(1);
		}
	}else{
		echo "<script> fun_msj('No lleg&oacute; el c&oacute;digo del tipo de n&oacute;mina a procesar, Seleccione N&oacute;mina');
				document.getElementById('in_cod_tipo_nomina').value='';
				document.getElementById('in_denominacion_tipo_nomina').value='';
				document.getElementById('id_enviar_generar_rp').disabled=true;
			</script>";
	}
}


function tipo_proceso_envio($vari=null) {
	$this->layout="ajax";
	if($vari!=null){
		switch($vari){
			case 1: $tipo_ventana_xenvio = 'buscar_datos_personales';
				break;
			case 2: $tipo_ventana_xenvio = '';
				break;
			case 3: $tipo_ventana_xenvio = '';
				break;
			case 4: $tipo_ventana_xenvio = '';
				break;
			default: $tipo_ventana_xenvio = '';
				break;
		}

		if($vari!=1){
			echo '<script>document.getElementById("empleado_ide").style.visibility="hidden";</script>';
			echo "<script> document.getElementById('empleado_ide').innerHTML='';
							if(document.getElementById('id_cedula_identidad')){ document.getElementById('id_cedula_identidad').value=''; }
						   	if(document.getElementById('id_nombre_empleado')){ document.getElementById('id_nombre_empleado').value=''; }
				</script>";
		}else{
				$url                  =  "/cnmp17_fideicomiso_cuentas_bancarias/$tipo_ventana_xenvio/$vari";
				$width_aux            =  "750px";
				$height_aux           =  "450px";
				$title_aux            =  "Buscar";
				$resizable_aux        =  false;
				$maximizable_aux      =  false;
				$minimizable_aux      =  false;
				$closable_aux         =  false;

			 echo "<script>";
	           echo "codigo_ventana('".$url."', '".$width_aux."', '".$height_aux."', '".$title_aux."', '".$resizable_aux."', '".$maximizable_aux."', '".$minimizable_aux."', '".$closable_aux."')";
	         echo "</script>";
		}
	}else{
		$this->set('errorMessage','NO LLEG&Oacute; INFORMACI&Oacute;N COMPLETA PARA PROCESAR');
	}
} // fin funcion




function buscar_datos_personales($var1=null, $cod=null){
	$this->layout="ajax";
	$this->set("opcion",$var1);
	$this->Session->delete('pista');
	echo "<script>$('select_obra_cod_obra').focus();</script>";
}//fin function




function buscar_datos_porpista($var1=null, $var2=null, $var3=null){
	$this->layout="ajax";
	$modelo='v_cnmp17_fideicomiso_cuentas_vision';
	$cod_nomina = $this->Session->read('codigo_tipo_nominar');
    if($var3==null){ $var2 = strtoupper($var2);
					 $this->Session->write('pista', $var2);
					 $Tfilas=$this->$modelo->findCount($this->SQLCA()." and cod_tipo_nomina=$cod_nomina and ((cedula_identidad::text LIKE '%$var2%') or (quitar_acentos(primer_nombre) LIKE quitar_acentos('%$var2%')) or (quitar_acentos(segundo_nombre) LIKE quitar_acentos('%$var2%')) or (quitar_acentos(primer_apellido) LIKE quitar_acentos('%$var2%')) or (quitar_acentos(segundo_apellido) LIKE quitar_acentos('%$var2%')))");
					        if($Tfilas!=0){
					        	$pagina=1;
					        	$Tfilas=(int)ceil($Tfilas/50);
					        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
								$this->set('total_paginas',$Tfilas);
								$this->set('pagina_actual',$pagina);
								$this->set('ultimo',$Tfilas);
								$datos_filas=$this->$modelo->findAll($this->SQLCA()." and cod_tipo_nomina=$cod_nomina and ((cedula_identidad::text LIKE '%$var2%') or (quitar_acentos(primer_nombre) LIKE quitar_acentos('%$var2%')) or (quitar_acentos(segundo_nombre) LIKE quitar_acentos('%$var2%')) or (quitar_acentos(primer_apellido) LIKE quitar_acentos('%$var2%')) or (quitar_acentos(segundo_apellido) LIKE quitar_acentos('%$var2%')))",null,"cedula_identidad ASC",50,1,null);
						        $this->set("datosFILAS",$datos_filas);
						        $this->set('siguiente',$pagina+1);
								$this->set('anterior',$pagina-1);
								$this->bt_nav($Tfilas,$pagina);
					          }else{
					          	echo '<script>document.getElementById("empleado_ide").style.visibility="hidden";</script>';
					          	echo "<script> if(document.getElementById('id_cedula_identidad')){ document.getElementById('id_cedula_identidad').value=''; }
  										if(document.getElementById('id_nombre_empleado')){ document.getElementById('id_nombre_empleado').value=''; }  </script>";
					        	$this->set("datosFILAS",'');
								$this->set('total_paginas','');
								$this->set('pagina_actual','');
							    $this->set('siguiente','');
								$this->set('anterior','');
								$this->set('ultimo','');
					          }
            }else{
						$var22 = $this->Session->read('pista');
						$var22 = strtoupper($var22);
						$Tfilas=$this->$modelo->findCount($this->SQLCA()." and cod_tipo_nomina=$cod_nomina and ((cedula_identidad::text LIKE '%$var2%') or (quitar_acentos(primer_nombre) LIKE quitar_acentos('%$var2%')) or (quitar_acentos(segundo_nombre) LIKE quitar_acentos('%$var2%')) or (quitar_acentos(primer_apellido) LIKE quitar_acentos('%$var2%')) or (quitar_acentos(segundo_apellido) LIKE quitar_acentos('%$var2%')))");
						        if($Tfilas!=0){
						        	$pagina=$var3;
						        	$Tfilas=(int)ceil($Tfilas/50);
						        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
									$this->set('total_paginas',$Tfilas);
									$this->set('pagina_actual',$pagina);
									$this->set('ultimo',$Tfilas);
									$datos_filas=$this->$modelo->findAll($this->SQLCA()." and cod_tipo_nomina=$cod_nomina and ((cedula_identidad::text LIKE '%$var2%') or (quitar_acentos(primer_nombre) LIKE quitar_acentos('%$var2%')) or (quitar_acentos(segundo_nombre) LIKE quitar_acentos('%$var2%')) or (quitar_acentos(primer_apellido) LIKE quitar_acentos('%$var2%')) or (quitar_acentos(segundo_apellido) LIKE quitar_acentos('%$var2%')))",null,"cedula_identidad ASC",50,$pagina,null);
							        $this->set("datosFILAS",$datos_filas);
							        $this->set('siguiente',$pagina+1);
									$this->set('anterior',$pagina-1);
									$this->bt_nav($Tfilas,$pagina);
						          }else{
						          	echo '<script>document.getElementById("empleado_ide").style.visibility="hidden";</script>';
					          		echo "<script> if(document.getElementById('id_cedula_identidad')){ document.getElementById('id_cedula_identidad').value=''; }
  										if(document.getElementById('id_nombre_empleado')){ document.getElementById('id_nombre_empleado').value=''; }  </script>";
						        	$this->set("datosFILAS",'');
									$this->set('total_paginas','');
									$this->set('pagina_actual','');
							    	$this->set('siguiente','');
									$this->set('anterior','');
									$this->set('ultimo','');
						          }
   		}//fin else
$this->set("opcion",$var1);
$this->set("cod_nomi",$cod_nomina);
} //fin funcion


function seleccion($opci=null,$cedula=null,$codi_nomina=null,$cod_cargo=null,$cod_ficha=null) {
	$this->layout="ajax";
     if ($cedula!=null && $codi_nomina!=null) {
         $empleado = $this->v_cnmp17_fideicomiso_cuentas_vision->find($this->SQLCA()." and cod_tipo_nomina=".$codi_nomina." and cod_cargo=".$cod_cargo." and cod_ficha=".$cod_ficha." and cedula_identidad=".$cedula,'cod_cargo, cod_ficha, cedula_identidad, primer_nombre, segundo_nombre, primer_apellido, segundo_apellido');
		if($empleado!=null || $empleado!=""){
			$this->set('datos_empleado',$empleado);
			echo "<script> document.getElementById('id_enviar_generar_rp').disabled=false;
				</script>";
		}else{
			$this->set('datos_empleado', array());
			echo '<script>document.getElementById("empleado_ide").style.visibility="hidden";</script>';
			echo "<script> document.getElementById('empleado_ide').innerHTML='';
							if(document.getElementById('id_cedula_identidad')){ document.getElementById('id_cedula_identidad').value=''; }
						   	if(document.getElementById('id_nombre_empleado')){ document.getElementById('id_nombre_empleado').value=''; }
				</script>";
		}
	}
}




function funcrat_procesor($tipo_oper=null){
	$this->layout = "ajax";

	if($tipo_oper==1 || $tipo_oper=='1'){

			echo "<script>
					document.getElementById('select_1').disabled = false;
					document.getElementById('select_2').disabled = false;
					document.getElementById('codigo_entidad').disabled = false;
					document.getElementById('denominacion_entidad').disabled = false;
					document.getElementById('codigo_sucursal').disabled = false;
					document.getElementById('denominacion_sucursal').disabled = false;
				</script>";
	}else{

			echo "<script>
					document.getElementById('select_1').options[0].selected = true;
					document.getElementById('codigo_entidad').value='';
					document.getElementById('denominacion_entidad').value='';
					document.getElementById('codigo_entidad').disabled = true;
					document.getElementById('denominacion_entidad').disabled = true;
					document.getElementById('select_2').options[0].selected = true;
					document.getElementById('codigo_sucursal').value='';
					document.getElementById('denominacion_sucursal').value='';
					document.getElementById('codigo_sucursal').disabled = true;
					document.getElementById('denominacion_sucursal').disabled = true;
					document.getElementById('select_1').disabled = true;
					document.getElementById('select_2').disabled = true;
				</script>";
	}

} // fin funcrat_procesor

 } // fin class

?>
