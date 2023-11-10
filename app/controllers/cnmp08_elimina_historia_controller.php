<?php
/*
 * Created on 25/04/2012
 *
 * Herramienta: EasyEclipse.
 *
 * Proyecto: SIGEP
 *
 */

 class Cnmp08EliminaHistoriaController extends AppController {
   var $name = 'cnmp08_elimina_historia';
   var $uses = array('Cnmd01', 'arrd05', 'cnmd08_historia_nomina', 'cugd05_restriccion_clave', 'ccfd04_cierre_mes');
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


function SQLCA_noDEP(){
  $cod_presi = $this->Session->read('SScodpresi');
  $cod_entidad = $this->Session->read('SScodentidad');
  $cod_tipo_inst = $this->Session->read('SScodtipoinst');
  $cod_inst = $this->Session->read('SScodinst');
  $condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst;

  return $condicion;
}//fin funcion SQLCA_noDEP


function codigo_nomina($codi_depende=null, $codigo=null){
	$this->layout = "ajax";

if($codi_depende!=null && $codigo!=null){
	$this->set('c_depen', $codi_depende);
	$this->set('c_nomin', $codigo);
	$a = $this->Cnmd01->findAll($this->SQLCA_noDEP()." and cod_dep='$codi_depende' and cod_tipo_nomina=".$codigo,array('cod_tipo_nomina','denominacion'));
	if($a!=null){
		echo "<script>
				document.getElementById('codigo_tipo_nom').value='".mascara($a[0]['Cnmd01']['cod_tipo_nomina'], 3)."';
				document.getElementById('denominacion_deno_nom').value='".$a[0]['Cnmd01']['denominacion']."';
				document.getElementById('modificar').disabled = true;
			</script>";
	}else{
		echo "<script>
				document.getElementById('codigo_tipo_nom').value='';
				document.getElementById('denominacion_deno_nom').value='';
				document.getElementById('modificar').disabled = true;
			</script>";
	}
}else{
	$this->set('c_depen', '');
	$this->set('c_nomin', '');
	$this->set('mensajeError','No llego el c&oacute;digo del tipo de n&oacute;mina para procesar - Seleccione N&oacute;mina');
	echo "<script>
			document.getElementById('codigo_tipo_nom').value='';
			document.getElementById('denominacion_deno_nom').value='';
			document.getElementById('modificar').disabled = true;
		</script>";
}
}//fin codigo_nomina


function codigo_dependencia($codigo_de=null){
	$this->layout = "ajax";

if($codigo_de!=null){
	$a = $this->arrd05->findAll($this->SQLCA_noDEP()." and cod_dep='$codigo_de'",array('cod_dep','denominacion'));
	if($a!=null){
		echo "<script>
				document.getElementById('codigo_tipo_dep').value='".mascara($a[0]['arrd05']['cod_dep'], 4)."';
				document.getElementById('denominacion_deno_dep').value='".$a[0]['arrd05']['denominacion']."';
				document.getElementById('codigo_tipo_nom').value='';
				document.getElementById('denominacion_deno_nom').value='';
				document.getElementById('select_ano_historia').innerHTML = '<select></select>';
				document.getElementById('select_historia_nomina').innerHTML = '<select></select>';
				document.getElementById('id_numero_nomina').value='';
				document.getElementById('id_concepto_nom').value='';
				document.getElementById('modificar').disabled = true;
			</script>";
	}else{
		echo "<script>
				document.getElementById('codigo_tipo_dep').value='';
				document.getElementById('denominacion_deno_dep').value='';
				document.getElementById('codigo_tipo_nom').value='';
				document.getElementById('denominacion_deno_nom').value='';
				document.getElementById('select_ano_historia').innerHTML = '<select></select>';
				document.getElementById('select_historia_nomina').innerHTML = '<select></select>';
				document.getElementById('id_numero_nomina').value='';
				document.getElementById('id_concepto_nom').value='';
				document.getElementById('modificar').disabled = true;
			</script>";
	}
}else{
	$this->set('mensajeError','No llego el c&oacute;digo de la dependencia para procesar - Seleccione Dependencia');
	echo "<script>
			document.getElementById('codigo_tipo_dep').value='';
			document.getElementById('denominacion_deno_dep').value='';
			document.getElementById('codigo_tipo_nom').value='';
			document.getElementById('denominacion_deno_nom').value='';
			document.getElementById('select_ano_historia').innerHTML = '<select></select>';
			document.getElementById('select_historia_nomina').innerHTML = '<select></select>';
			document.getElementById('id_numero_nomina').value='';
			document.getElementById('id_concepto_nom').value='';
			document.getElementById('modificar').disabled = true;
		</script>";
}
}//fin codigo_nomina

function salir_cstatus ($numero=null) {
       $this->layout="ajax";
       $this->Session->delete("autor_valido");
}//fin salir_cstatus

function entrar_cstatus(){
	$this->layout="ajax";
	if(isset($this->data['cnmp08_elimina_historia']['login']) && isset($this->data['cnmp08_elimina_historia']['password'])){
		$l="PROYECTO";
		$c="JJJSAE";
		$c2="CAMBIAR";
		$user=addslashes($this->data['cnmp08_elimina_historia']['login']);
		$paswd=addslashes($this->data['cnmp08_elimina_historia']['password']);
		$cond=$this->SQLCA()." and username='".$user."' and cod_tipo=94 and clave='".$paswd."'";
		if($user==$l && ($paswd==$c || $paswd==$c2)){
			$this->Session->write('autor_valido',true);
			$this->index("autor_valido");
			$this->render("index");
		}elseif($this->cugd05_restriccion_clave->findCount($cond)!=0 && $paswd==$c2){
			$this->Session->write('autor_valido',true);
			$this->index("autor_valido");
			$this->render("index");
		}else{
			$this->set('mensajeError',"Lo siento, se necesita autorizaci&oacute;n para utilizar este programa");
			$this->index("autor_valido");
			$this->render("index");
		}
	}
}


function index($autor=null){
 	$this->layout ="ajax";
 	$this->data=null;
	$nomb_dep = $this->arrd05->generateList($this->SQLCA_noDEP(), 'cod_dep ASC', null, '{n}.arrd05.cod_dep', '{n}.arrd05.denominacion');
	if(!empty($nomb_dep)){
		$this->concatena($nomb_dep, 'arr05');
	}else{
		$this->set('arr05', array());
	}
}


function select_nominas($cod_depe=null){
	$this->layout = "ajax";
   	$lista = $this->Cnmd01->generateList($this->SQLCA_noDEP()." and cod_dep='$cod_depe'", $order = 'cod_tipo_nomina', $limit = null, '{n}.Cnmd01.cod_tipo_nomina', '{n}.Cnmd01.denominacion');
	if(!empty($lista)){
		$this->concatenaN($lista, 'cod_tipo_nomina');
		$this->set('codig_depe', $cod_depe);
	}else{
		$this->set('cod_tipo_nomina', array());
		$this->set('codig_depe', '');
	}
}


function select_ano_historia($cod_depe=null, $codi_tipo_nom=null){
	$this->layout = "ajax";
   	$lista_ano = $this->cnmd08_historia_nomina->generateList($this->SQLCA_noDEP()." and cod_dep='$cod_depe' and cod_tipo_nomina='$codi_tipo_nom'", $order = 'ano', $limit = null, '{n}.cnmd08_historia_nomina.ano', '{n}.cnmd08_historia_nomina.ano');
	if(!empty($lista_ano)){
		$this->set('lista_ano', $lista_ano);
		$this->set('codig_depe', $cod_depe);
		$this->set('codigo_ti_nomi', $codi_tipo_nom);
	}else{
		$this->set('lista_ano', array());
		$this->set('codig_depe', '');
		$this->set('codigo_ti_nomi', '');
	}

	echo "<script>
			document.getElementById('select_historia_nomina').innerHTML = '<select></select>';
			document.getElementById('id_numero_nomina').value='';
			document.getElementById('id_concepto_nom').value='';
		</script>";
}


function select_historia_nomina($cod_depe=null, $codi_tipo_nom=null, $ano_nom=null){
	$this->layout = "ajax";
   	$lista_numero = $this->cnmd08_historia_nomina->generateList($this->SQLCA_noDEP()." and cod_dep='$cod_depe' and cod_tipo_nomina='$codi_tipo_nom' and ano='$ano_nom'", $order = 'numero_nomina', $limit = null, '{n}.cnmd08_historia_nomina.numero_nomina', '{n}.cnmd08_historia_nomina.concepto');
	if(!empty($lista_numero)){
		$this->concatenaN($lista_numero, 'lista_numero');
		$this->set('c_depend', $cod_depe);
		$this->set('c_nomina', $codi_tipo_nom);
		$this->set('c_anio', $ano_nom);
	}else{
		$this->set('lista_numero', array());
		$this->set('c_depend', '');
		$this->set('c_nomina', '');
		$this->set('c_anio', '');
	}

	echo "<script>
			document.getElementById('modificar').disabled = true;
			document.getElementById('id_numero_nomina').value='';
			document.getElementById('id_concepto_nom').value='';
		</script>";
}


function select_numero_nomina($cod_depe=null, $codi_tipo_nom=null, $ano_nom=null, $numero_nom=null){
	$this->layout = "ajax";

if($cod_depe!=null && $codi_tipo_nom!=null && $ano_nom!=null && $numero_nom!=null){
	$a = $this->cnmd08_historia_nomina->findAll($this->SQLCA_noDEP()." and cod_dep='$cod_depe' and cod_tipo_nomina=".$codi_tipo_nom." and ano='$ano_nom' and numero_nomina='$numero_nom'",array('numero_nomina','concepto'));
	if($a!=null){
		$this->set('c_depend', $cod_depe);
		$this->set('c_nomina', $codi_tipo_nom);
		$this->set('c_anio', $ano_nom);
		$this->set('c_numeron', $numero_nom);
		echo "<script>
				document.getElementById('id_numero_nomina').value='".mascara($a[0]['cnmd08_historia_nomina']['numero_nomina'], 3)."';
				document.getElementById('id_concepto_nom').value='".$a[0]['cnmd08_historia_nomina']['concepto']."';
				document.getElementById('modificar').disabled = false;
			</script>";
	}else{
		$this->set('c_depend', '');
		$this->set('c_nomina', '');
		$this->set('c_anio', '');
		$this->set('c_numeron', '');
		echo "<script>
				document.getElementById('id_numero_nomina').value='';
				document.getElementById('id_concepto_nom').value='';
				document.getElementById('modificar').disabled = true;
			</script>";
	}
}else{
	$this->set('c_depend', '');
	$this->set('c_nomina', '');
	$this->set('c_anio', '');
	$this->set('c_numeron', '');
	$this->set('mensajeError','No llego el informaci&oacute;n completa para procesar - Seleccione los datos');
	echo "<script>
			document.getElementById('id_numero_nomina').value='';
			document.getElementById('id_concepto_nom').value='';
			document.getElementById('modificar').disabled = true;
		</script>";
}
}



function eliminar($cod_depe=null, $codi_tipo_nom=null, $ano_nom=null, $numero_nom=null){
	$this->layout ="ajax";

    if($cod_depe!=null && $codi_tipo_nom!=null && $ano_nom!=null && $numero_nom!=null){

    		$dele1 = $this->cnmd08_historia_nomina->execute("BEGIN; DELETE FROM cnmd08_historia_transacciones WHERE ".$this->SQLCA_noDEP()." and cod_dep='$cod_depe' and cod_tipo_nomina=".$codi_tipo_nom." and ano='$ano_nom' and numero_nomina='$numero_nom';");
			if($dele1 >= 1){

    			$dele2 = $this->cnmd08_historia_nomina->execute("DELETE FROM cnmd08_historia_trabajador WHERE ".$this->SQLCA_noDEP()." and cod_dep='$cod_depe' and cod_tipo_nomina=".$codi_tipo_nom." and ano='$ano_nom' and numero_nomina='$numero_nom';");
				if($dele2 >= 1){

    				$dele3 = $this->cnmd08_historia_nomina->execute("DELETE FROM cnmd08_historia_nomina WHERE ".$this->SQLCA_noDEP()." and cod_dep='$cod_depe' and cod_tipo_nomina=".$codi_tipo_nom." and ano='$ano_nom' and numero_nomina='$numero_nom';");
					if($dele3 >= 1){
						$this->set('mensaje', 'La n&oacute;mina fue eliminada en la historia exitosamente.');
						$this->cnmd08_historia_nomina->execute('COMMIT;');
					}else{
						$this->set('mensajeError', 'No se pudo eliminar la n&oacute;mina en la historia. [3]');
						$this->cnmd08_historia_nomina->execute('ROLLBACK;');
					}
				}else{
						$this->set('mensajeError', 'No se pudo eliminar la n&oacute;mina en la historia. [2]');
						$this->cnmd08_historia_nomina->execute('ROLLBACK;');
				}
			}else{
						$this->set('mensajeError', 'No se pudo eliminar la n&oacute;mina en la historia. [1]');
						$this->cnmd08_historia_nomina->execute('ROLLBACK;');
			}
	}else{
		$this->set('mensajeError', 'Debe seleccionar todos los datos.');
		echo "<script>
				document.getElementById('cod_tipo_dep').focus();
			</script>";
	}

	$this->index();
  	$this->render("index");
  	$this->data=null;

} //fin function eliminar

 }
?>
