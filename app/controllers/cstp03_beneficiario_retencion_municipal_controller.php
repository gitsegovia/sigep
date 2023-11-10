<?php
/*
 * Creado el 06/02/2008 a las 11:18:12 PM
 * Herramienta: EasyEclipse.
 * Proyecto: SIGEP
 * Descripcion:
 */
 class Cstp03BeneficiarioRetencionMunicipalController extends AppController {
   var $name = 'cstp03_beneficiario_retencion_municipal';
   var $uses = array('cstd03_beneficiario_retencion_municipal');
   var $helpers = array('Html','Ajax','Javascript', 'Sisap');

function checkSession(){
				if (!$this->Session->check('Usuario')){
						$this->redirect('/salir/');
						exit();
				}else{
					//$this->set('userTable', $this->requestAction('/cnmp03partidas/', array('return')));
					//echo "H".$this->requestAction('/usuarios/actualizar_user',array('return'));
					$this->requestAction('/usuarios/actualizar_user');
				}
}//fin checksession

function beforeFilter(){
 	$this->checkSession();
 	 /*echo'<script>
							 document.getElementById("valida_codigo").innerHTML = "";
							 document.getElementById("valida_codigo").style.display = "none";
							 if(document.getElementById("registro")){document.getElementById("registro").style.visibility = "hidden";}
                             if(document.getElementById("nomina")){document.getElementById("nomina").style.visibility = "hidden";}
                             if(document.getElementById("dependencia")){document.getElementById("dependencia").style.visibility = "hidden";}
                             if(document.getElementById("sistema_general")){document.getElementById("sistema_general").style.visibility = "hidden";}
                          </script>';
	*/
}

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


function index(){
	$this->layout="ajax";
	$this->set('entidad_federal', $this->Session->read('entidad_federal'));
	$bene=$this->cstd03_beneficiario_retencion_municipal->findAll($this->SQLCA(),'beneficiario');
	if($bene){
		$this->set('beneficiario',$bene[0]['cstd03_beneficiario_retencion_municipal']['beneficiario']);
	}
}

function guardar(){
	$this->layout="ajax";

	if($this->data['cstp03_beneficiario_retencion_municipal']['beneficiario']==''){
		$this->set('mensajeError','Debe ingresar el Beneficiario');
		$this->index();
		$this->render("index");
	}else{
		$consulta="SELECT * FROM cstd03_beneficiario_retencion_municipal WHERE ".$this->SQLCA();
		if($this->cstd03_beneficiario_retencion_municipal->execute($consulta)){
			$this->set('mensajeError','Lo siento ya existe un Beneficiario registrado para esta dependencia');
			$this->index();
			$this->render("index");
		}else{
			$sql = "INSERT INTO cstd03_beneficiario_retencion_municipal VALUES ('".$this->verifica_SS(1)."','".$this->verifica_SS(2)."','".$this->verifica_SS(3)."','".$this->verifica_SS(4)."','".$this->verifica_SS(5)."','".$this->data['cstp03_beneficiario_retencion_municipal']['beneficiario']."')";
			if($this->cstd03_beneficiario_retencion_municipal->execute($sql)>1){
			$this->set('mensaje','El Beneficiario fue guardado correctamente');
			$this->index();
			$this->render("index");
			}else{
				$this->set('mensajeError','Lo siento el Beneficiario no pudo ser guardado');
				$this->index();
				$this->render("index");
			}
		}
	}
}

function modificar($var=null){
	$this->layout="ajax";

	$sql=$this->SQLCA();
	$bene=$this->cstd03_beneficiario_retencion_municipal->findAll($sql,'beneficiario');
	if($bene){
		$this->set('mensaje','Puede modificar el Beneficiario');
		$this->set('beneficiario',$bene[0]['cstd03_beneficiario_retencion_municipal']['beneficiario']);
	}else{
		$this->set('mensajeError','Lo siento el Beneficiario no pudo ser encontrado');
	}
}

function guardar_modificar($var=null){
	$this->layout="ajax";

	if($this->data['cstp03_beneficiario_retencion_municipal']['beneficiario']==''){
		$this->set('mensajeError','Debe ingresar el Beneficiario');
		$this->index();
		$this->render("index");
	}else{
		$sql="UPDATE cstd03_beneficiario_retencion_municipal SET beneficiario='".$this->data['cstp03_beneficiario_retencion_municipal']['beneficiario']."' WHERE ".$this->SQLCA();
		if($this->cstd03_beneficiario_retencion_municipal->execute($sql)>0){
			$this->set('mensaje','El Beneficiario fue modificado correctamente');
			$this->index();
			$this->render("index");
		}else{
			$this->set('mensajeError','Lo siento, el Beneficiario no pudo ser modificado');
			$this->index();
			$this->render("index");
		}
	}
}

}//fin clase
?>
