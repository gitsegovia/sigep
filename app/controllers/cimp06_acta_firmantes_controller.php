<?php
/*
 * Created on 28/06/2012
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */

 class Cimp06ActaFirmantesController extends AppController{

	var $name = 'cimp06_acta_firmantes';
 	var $uses = array('cimd06_acta_firmantes', 'ccfd04_cierre_mes', 'cugd05_restriccion_clave');
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

    function SQLCACTA($ano=null){//sql para busqueda de codigos de arranque con y sin año
         $sql_re = "cod_presi=".$this->verifica_SS(1)."  and    ";
         $sql_re .= "cod_entidad=".$this->verifica_SS(2)."  and  ";
         $sql_re .= "cod_tipo_inst=".$this->verifica_SS(3)."  and ";
         $sql_re .= "cod_inst=".$this->verifica_SS(4)."  and  ";
         if($ano!=null){
         	$sql_re .= "cod_dep=".$this->verifica_SS(5)."  and  ";
            $sql_re .= "ano_acta=".$ano."  ";
         }else{
         	$sql_re .= "cod_dep=".$this->verifica_SS(5)." ";
         }
         return $sql_re;
    }//fin funcion SQLCA


	function index(){
		$this->layout = "ajax";
		$ano_ac = $this->ano_ejecucion();
		if(empty($ano_ac)){
			$ano_ac = date('Y');
		}
		$v=$this->cimd06_acta_firmantes->execute("SELECT numero_acta, funcionario_primero, cedula_primero, cargo_primero, funcionario_segundo, cedula_segundo, cargo_segundo, funcionario_tercer, cedula_tercer, cargo_tercer, funcionario_cuarto, cedula_cuarto, cargo_cuarto FROM cimd06_acta_firmantes WHERE ".$this->SQLCACTA($ano_ac)." ORDER BY numero_acta DESC LIMIT 1;");
		if($v!=null){
			$numero = $v[0][0]["numero_acta"] !="" ? $v[0][0]["numero_acta"] : 0;
		}else{
			$numero = 0;
		}
		$this->set('ano_ac',$ano_ac);
		$this->set('numero',$numero);
		$this->set('datos_acta',$v);
	} // FIN FUNCTION INDEX


function entrar(){
	$this->layout="ajax";
	if(isset($this->data['cimp06_acta_firmantes']['login']) && isset($this->data['cimp06_acta_firmantes']['password'])){
		$l="PROYECTO";
		$c="JJJSAE";
		$user=addslashes($this->data['cimp06_acta_firmantes']['login']);
		$paswd=addslashes($this->data['cimp06_acta_firmantes']['password']);
		$cond=$this->SQLCACTA()." and username='".$user."' and cod_tipo=21 and clave='".$paswd."'";
		if($user==$l && $paswd==$c){
			$this->set('autor_valido',true);
			$this->index("autor_valido");
			$this->render("index");
		}elseif($this->cugd05_restriccion_clave->findCount($cond)!=0){
			$this->set('autor_valido',true);
			$this->index("autor_valido");
			$this->render("index");
		}else{
			$this->set('errorMessage',"Lo siento, se necesita autorizaci&oacute;n para utilizar este programa");
			$this->set('autor_valido',false);
			$this->index("autor_valido");
			$this->render("index");
		}
	}
}


	function guardar(){
		$this->layout = "ajax";
		$ano_acta = $this->data["cimd06_acta_firmantes"]["ano_acta"];
		$numero_acta = $this->data["cimd06_acta_firmantes"]["numero_acta"];
		$func_primero = $this->data["cimd06_acta_firmantes"]["funcionario_primero"];
		$ced_primero = $this->data["cimd06_acta_firmantes"]["cedula_primero"];
		$cargo_primero = $this->data["cimd06_acta_firmantes"]["cargo_primero"];
		$func_segundo = $this->data["cimd06_acta_firmantes"]["funcionario_segundo"];
		$ced_segundo = $this->data["cimd06_acta_firmantes"]["cedula_segundo"];
		$cargo_segundo = $this->data["cimd06_acta_firmantes"]["cargo_segundo"];
		$func_tercer = $this->data["cimd06_acta_firmantes"]["funcionario_tercer"];
		$ced_tercer = $this->data["cimd06_acta_firmantes"]["cedula_tercer"];
		$cargo_tercer = $this->data["cimd06_acta_firmantes"]["cargo_tercer"];
		$func_cuarto = $this->data["cimd06_acta_firmantes"]["funcionario_cuarto"];
		$ced_cuarto = $this->data["cimd06_acta_firmantes"]["cedula_cuarto"];
		$cargo_cuarto = $this->data["cimd06_acta_firmantes"]["cargo_cuarto"];

		$ver = $this->cimd06_acta_firmantes->findCount($this->SQLCACTA($ano_acta));
		if($ver==0){
			$sql_insert = "INSERT INTO cimd06_acta_firmantes VALUES(".$this->verifica_SS(1).", ".$this->verifica_SS(2).", ".$this->verifica_SS(3).",".$this->verifica_SS(4).",".$this->verifica_SS(5).",".$ano_acta.",".$numero_acta.",'".$func_primero."','".$ced_primero."','".$cargo_primero."','".$func_segundo."','".$ced_segundo."','".$cargo_segundo."','".$func_tercer."','".$ced_tercer."','".$cargo_tercer."','".$func_cuarto."','".$ced_cuarto."','".$cargo_cuarto."');";
			$accion = "Guarda";
		}else{
			$sql_insert = "UPDATE cimd06_acta_firmantes SET funcionario_primero='$func_primero', cedula_primero='$ced_primero', cargo_primero='$cargo_primero', funcionario_segundo='$func_segundo', cedula_segundo='$ced_segundo', cargo_segundo='$cargo_segundo', funcionario_tercer='$func_tercer', cedula_tercer='$ced_tercer', cargo_tercer='$cargo_tercer', funcionario_cuarto='$func_cuarto', cedula_cuarto='$ced_cuarto', cargo_cuarto='$cargo_cuarto' WHERE " . $this->SQLCACTA($ano_acta).";";
			$accion = "Modifica";
		}

		$sw1 = $this->cimd06_acta_firmantes->execute($sql_insert);
		if($sw1>1){
			$this->set('Message_existe','Los datos fueron '.$accion.'dos exitosamente');
		}else{
			$this->set('errorMessage','No se pudo '.$accion.'r los datos');
		}

		// $this->set('autor_valido',true);
		// $this->index("autor_valido");
		$this->index();
		$this->render("index");
	} // FIN FUNCTION guardar


	function guardar_modificar(){
		$this->layout = "ajax";
		echo "<script>
				document.getElementById('cimp_func_primero').readOnly = false;
				document.getElementById('cimp_ced_primero').readOnly = false;
				document.getElementById('cimp_cargo_primero').readOnly = false;

				document.getElementById('cimp_func_segundo').readOnly = false;
				document.getElementById('cimp_ced_segundo').readOnly = false;
				document.getElementById('cimp_cargo_segundo').readOnly = false;

				document.getElementById('cimp_func_tercer').readOnly = false;
				document.getElementById('cimp_ced_tercer').readOnly = false;
				document.getElementById('cimp_cargo_tercer').readOnly = false;

				document.getElementById('cimp_func_cuarto').readOnly = false;
				document.getElementById('cimp_ced_cuarto').readOnly = false;
				document.getElementById('cimp_cargo_cuarto').readOnly = false;

				document.getElementById('boto_modificar').disabled = true;
				document.getElementById('save').disabled = false;
				document.getElementById('id_fuera_p').focus();
			</script>";
	} // fin guardar_modificar

 } // fin class

?>
