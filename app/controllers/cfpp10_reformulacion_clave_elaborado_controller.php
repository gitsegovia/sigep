<?php
/*
 * Creado el 01/02/2008 a las 04:08:42 AM
 * Herramienta: EasyEclipse.
 * Proyecto: SIGEP
 * Descripcion:
 */
class Cfpp10ReformulacionClaveElaboradoController extends AppController {
 	var $name = 'cfpp10_reformulacion_clave_elaborado';
 	var $uses = array ('cfpd10_reformulacion_clave_elaborado', 'cstd02_cuentas_bancarias', 'cstd01_entidades_bancarias', 'cstd01_sucursales_bancarias','ccfd03_instalacion','cstd03_cheque_numero');
 	var $helpers = array ('Html','Ajax','Javascript','Sisap');


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
 	 echo'<script>
							 document.getElementById("valida_codigo").innerHTML = "";
							 document.getElementById("valida_codigo").style.display = "none";
							 if(document.getElementById("registro")){document.getElementById("registro").style.visibility = "hidden";}
                             if(document.getElementById("nomina")){document.getElementById("nomina").style.visibility = "hidden";}
                             if(document.getElementById("dependencia")){document.getElementById("dependencia").style.visibility = "hidden";}
                             if(document.getElementById("sistema_general")){document.getElementById("sistema_general").style.visibility = "hidden";}
                          </script>';
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


function index(){
	$this->layout="ajax";

	$this->set('entidad_federal', $this->Session->read('entidad_federal'));
	$datos=$this->cfpd10_reformulacion_clave_elaborado->findAll($this->SQLCA(),'username, clave');
	$this->set('datos',$datos);
}


function guardar(){
	$this->layout="ajax";

	if($this->data['cfpp10_reformulacion_clave_elaborado']['usuario'] !="" && $this->data['cfpp10_reformulacion_clave_elaborado']['clave'] !=""){
		$consulta=$this->SQLCA()." and username='".$this->data['cfpp10_reformulacion_clave_elaborado']['usuario']."'";
		if($this->cfpd10_reformulacion_clave_elaborado->findAll($consulta)){
			$this->set('mensajeError','LO SIENTO, EL LOGIN ('.$this->data['cfpp10_reformulacion_clave_elaborado']['usuario'].') YA SE ENCUENTRA REGISTRADO');
			$datos=$this->cfpd10_reformulacion_clave_elaborado->findAll($this->SQLCA(),'username, clave');
			$this->set('datos',$datos);
		}else{
		   $sql="INSERT INTO cfpd10_reformulacion_clave_elaborado VALUES ('".$this->verifica_SS(1)."','".$this->verifica_SS(2)."','".$this->verifica_SS(3)."','".$this->verifica_SS(4)."','".$this->verifica_SS(5)."','".$this->data['cfpp10_reformulacion_clave_elaborado']['usuario']."','".$this->data['cfpp10_reformulacion_clave_elaborado']['clave']."')";
	 	   if($this->cfpd10_reformulacion_clave_elaborado->execute($sql)>1){
			  $this->set('mensaje','EL USUARIO FUE AGREGADO CORRECTAMENTE');
			  $datos=$this->cfpd10_reformulacion_clave_elaborado->findAll($this->SQLCA(),'username, clave');
			  $this->set('datos',$datos);
		   }else{
			  $this->set('mensajeError','LO SIENTO, EL USUARIO NO PUDO SER AGREGADO');
		   }
		}//fin else consulta
	}else{
		$this->set('mensajeError','ATENCION, DEBE INGRESAR EL NOMBRE DE USUARIO Y LA CLAVE POR FAVOR');
		$this->set('datos',null);
	}
}//guardar


function eliminar($user=null, $pass=null){
	$this->layout="ajax";

    if($user!=null && $pass!=null){
		$sql="DELETE FROM cfpd10_reformulacion_clave_elaborado WHERE ".$this->SQLCA()." and username='$user' and clave='$pass'";
		if($this->cfpd10_reformulacion_clave_elaborado->execute($sql)>1){
			$this->set('mensaje','EL USUARIO FUE ELIMINADO CORRECTAMENTE');
		}else{
			$this->set('mensajeError','LO SIENTO, EL USUARIO NO PUDO SER ELIMINADO');
		}
    }else{
    	$this->set('mensajeError','LO SIENTO, LOS DATOS NO LLEGARON CORRECTAMENTE Y NO SE PUDO PROCESAR');
    }
}//eliminar


function modificar($user=null, $pass=null){
	$this->layout="ajax";
	$this->set('mensaje','PUEDE PROCEDER A MODIFICAR EL USUARIO');
	$datos=$this->cfpd10_reformulacion_clave_elaborado->findAll($this->SQLCA()." and username='$user' and clave='$pass'",null,null,1);
	$this->set('datos',$datos);
}//modificar



function cancelar(){
	$this->layout="ajax";
	$datos=$this->cfpd10_reformulacion_clave_elaborado->findAll($this->SQLCA(),'username, clave');
	$this->set('datos',$datos);
	$this->index();
	$this->render("index");
}//cancelar


function guardar_modificar($username){
	$this->layout="ajax";

	if($this->data['cfpp10_reformulacion_clave_elaborado']['usuario'] !="" && $this->data['cfpp10_reformulacion_clave_elaborado']['clave'] !=""){
		$user=$this->data['cfpp10_reformulacion_clave_elaborado']['usuario'];
		$clave=$this->data['cfpp10_reformulacion_clave_elaborado']['clave'];
		$sql_update="UPDATE cfpd10_reformulacion_clave_elaborado SET username='".$this->data['cfpp10_reformulacion_clave_elaborado']['usuario']."', clave='".$this->data['cfpp10_reformulacion_clave_elaborado']['clave']."' WHERE ".$this->SQLCA()." and username='".$username."'";
		if($this->cfpd10_reformulacion_clave_elaborado->execute($sql_update)>0){
			$this->set('mensaje','EL USUARIO FUE MODIFICADO CORRECTAMENTE');
			$datos=$this->cfpd10_reformulacion_clave_elaborado->findAll($this->SQLCA(),'username, clave');
			$this->set('datos',$datos);
		}else{
			$this->set('mensajeError','LO SIENTO, EL USUARIO NO PUDO SER MODIFICADO');
		}
	}else{
		$this->set('mensajeError','ATENCION, DEBE INGRESAR EL NOMBRE DE USUARIO Y LA CLAVE POR FAVOR');
		$this->set('datos',null);
	}

}//guardar modificar

}
?>
