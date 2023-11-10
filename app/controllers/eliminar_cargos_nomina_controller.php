<?php
/*
 * Created on 25/04/2012
 *
 * Herramienta: EasyEclipse.
 *
 * Proyecto: SIGEP
 *
 */

 class EliminarCargosNominaController extends AppController {
   var $name = 'eliminar_cargos_nomina';
   var $uses = array('Cnmd01', 'arrd05', 'cfpd97', 'cugd05_restriccion_clave', 'ccfd04_cierre_mes');
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
				document.getElementById('modificar').disabled = false;
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
				document.getElementById('modificar').disabled = true;
			</script>";
	}else{
		echo "<script>
				document.getElementById('codigo_tipo_dep').value='';
				document.getElementById('denominacion_deno_dep').value='';
				document.getElementById('codigo_tipo_nom').value='';
				document.getElementById('denominacion_deno_nom').value='';
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
	if(isset($this->data['eliminar_cargos_nomina']['login']) && isset($this->data['eliminar_cargos_nomina']['password'])){
		$l="PROYECTO";
		$c="JJJSAE";
		$c2="CAMBIAR";
		$user=addslashes($this->data['eliminar_cargos_nomina']['login']);
		$paswd=addslashes($this->data['eliminar_cargos_nomina']['password']);
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



function eliminar($codi_dep=null, $codi_nom=null){
	$this->layout ="ajax";

    if($codi_nom!=null && $codi_dep!=null){
    	if($this->cfpd97->findCount($this->SQLCA_noDEP()." and cod_dep='$codi_dep' and cod_tipo_nomina='$codi_nom'")!=0){
    		$dele = $this->Cnmd01->execute("BEGIN; DELETE FROM cfpd97 WHERE ".$this->SQLCA_noDEP()." and cod_dep='$codi_dep' and cod_tipo_nomina='$codi_nom';");
			if($dele >= 1){
				$this->set('mensaje', 'Los cargos de la n&oacute;mina fueron eliminados con exito.');
				$this->Cnmd01->execute('COMMIT;');
			}else{
				$this->set('mensajeError', 'No se pudo eliminar los cargos de la n&oacute;mina.');
				$this->Cnmd01->execute('ROLLBACK;');
			}
    	}else{
    		$this->set('mensajeError', 'En esta n&oacute;mina no hay cargos');
    	}
	}else{
		$this->set('mensajeError', 'Debe seleccionar la dependencia y la n&oacute;mina');
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
