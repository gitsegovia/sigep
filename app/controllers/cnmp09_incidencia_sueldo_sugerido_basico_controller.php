<?php
/*
 * Creado el 08/01/2008 a las 03:28:45 PM
 * Herramienta: EasyEclipse.
 * Proyecto: SIGEP
 * Descripcion:
 */
 class Cnmp09IncidenciaSueldoSugeridoBasicoController extends AppController{
 	var $name = 'cnmp09_incidencia_sueldo_sugerido_basico';
 	var $uses = array ('cnmd09_incidencia_sueldo_sugerido','Cnmd01');
 	var $helpers = array ('Html','Ajax','Javascript','Sisap');

   function checkSession()
    {
        // If the session info hasn't been set...
        if (!$this->Session->check('Usuario'))
        {
            // Force the user to login
            $this->redirect('/salir');
            exit();
        }
    }

 function beforeFilter(){
 	$this->checkSession();
 	 echo'				<script>
							 document.getElementById("valida_codigo").innerHTML = "";
							 document.getElementById("valida_codigo").style.display = "none";
							 if(document.getElementById("registro")){document.getElementById("registro").style.visibility = "hidden";}
                             if(document.getElementById("nomina")){document.getElementById("nomina").style.visibility = "hidden";}
                             if(document.getElementById("dependencia")){document.getElementById("dependencia").style.visibility = "hidden";}
                             if(document.getElementById("sistema_general")){document.getElementById("sistema_general").style.visibility = "hidden";}
                         </script>';
 }

 function ss($i){
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
         $sql_re = "cod_presi=".$this->ss(1)."  and    ";
         $sql_re .= "cod_entidad=".$this->ss(2)."  and  ";
         $sql_re .= "cod_tipo_inst=".$this->ss(3)."  and ";
         $sql_re .= "cod_inst=".$this->ss(4)."  and  ";
         if($ano!=null){
         	$sql_re .= "cod_dep=".$this->ss(5)."  and  ";
            $sql_re .= "ano=".$ano."  ";
         }else{
         	$sql_re .= "cod_dep=".$this->ss(5)." ";
         }
         return $sql_re;
    }//fin funcion SQLCA



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



 function index(){

 	$this->layout="ajax";
	$lista = $this->Cnmd01->generateList($conditions = $this->condicion(), $order = 'cod_tipo_nomina', $limit = null, '{n}.Cnmd01.cod_tipo_nomina', '{n}.Cnmd01.denominacion');
	$this->concatenaN($lista, 'nomina');


	$datos1=$this->cnmd09_incidencia_sueldo_sugerido->FindAll($this->SQLCA());
	$this->set('datos',$datos1);

	$datos2=$this->Cnmd01->FindAll($this->SQLCA());
	$this->set('datos2',$datos2);
 }// fin del index


function mostrar($opc=null,$var=null){
	$this->layout="ajax";
	switch ($opc){
		case 'nomina':
				$this->set('codigo',$var);
				$this->set('valor', $var);
			break;
			case 'deno_nomina':
				$deno_nomina = $this->Cnmd01->field('denominacion', $conditions = $this->condicion()." and Cnmd01.cod_tipo_nomina='$var'", $order ="cod_tipo_nomina ASC");
				$this->set('denomi', $deno_nomina);
				$this->set('denominacion',$var);
			break;

	}
}// fin mostrar




 function guardar(){
 	$this->layout="ajax";
 	//pr($this->data);
 	$nomina=$this->data['cnmp09']['select_nomina'];
 	$monto=$this->Formato1($this->data['cnmp09']['sugerido']);

 	 $cod1=$this->verifica_SS(1);
	 $cod2=$this->verifica_SS(2);
	 $cod3=$this->verifica_SS(3);
	 $cod4=$this->verifica_SS(4);
	 $cod5=$this->verifica_SS(5);

if($nomina!="" && $monto!=0){
	$datos=$this->cnmd09_incidencia_sueldo_sugerido->FindAll($this->SQLCA()." and cod_tipo_nomina=".$nomina);
	 if(!$datos){
		$sql = "INSERT INTO cnmd09_incidencia_sueldo_sugerido VALUES('$cod1', '$cod2', '$cod3', '$cod4', '$cod5', '$nomina','$monto')";
		$sw1 = $this->cnmd09_incidencia_sueldo_sugerido->execute($sql);
		if($sw1>1){
			$this->set('Message_existe', 'Registro exitoso');
		}else{
			$this->set('errorMessage', 'no pudo registrarse, intente nuevamente');
		}
	 }else{
		$this->set('errorMessage', 'esta nomina ya se encuentra registrada');
	 }
}else{
	$this->set('errorMessage', 'debe seleccionar e ingresar los datos requeridos para esta operacion');
}//fin empty

echo "<script>";
	echo "document.getElementById('sugerido').value='';";
echo "</script>";

$datos1=$this->cnmd09_incidencia_sueldo_sugerido->FindAll($this->SQLCA());
$this->set('datos',$datos1);

$datos2=$this->Cnmd01->FindAll($this->SQLCA());
$this->set('datos2',$datos2);
}// fin guardar




function modificar_monto($id=null,$nomina=null){
	$this->layout="ajax";

	$this->set('nomina', $nomina);
	$this->set('i', $id);
	$deno_nomina = $this->Cnmd01->field('denominacion', $conditions = $this->condicion()." and Cnmd01.cod_tipo_nomina='$nomina'", $order ="cod_tipo_nomina ASC");
	$this->set('denomi', $deno_nomina);
	$monto = $this->cnmd09_incidencia_sueldo_sugerido->field('sueldo_sugerido', $conditions = $this->condicion()." and cod_tipo_nomina='$nomina'", $order ="cod_tipo_nomina ASC");
	$this->set('monto', $monto);
	$this->set('Message_existe','proceda a modificar el dato');
}//fin modificar_monto




function cancelar($nomina=null){
	$this->layout="ajax";

$datos1=$this->cnmd09_incidencia_sueldo_sugerido->FindAll($this->SQLCA());
$this->set('datos',$datos1);

$datos2=$this->Cnmd01->FindAll($this->SQLCA());
$this->set('datos2',$datos2);
}//fin cancelar




  function guardar_modificar($nomina=null,$id=null){
 	$this->layout="ajax";

		//pr($this->data);
	    $sueldo=$this->Formato1($this->data['cnmp09']['monto_sugerido'.$id]);
  	    $sql="update cnmd09_incidencia_sueldo_sugerido set sueldo_sugerido=".$sueldo." where ".$this->SQLCA()." and cod_tipo_nomina='$nomina'";

		if($this->cnmd09_incidencia_sueldo_sugerido->execute($sql)>1){
		$this->set('Message_existe','Los datos fuer&oacute;n modificados exitosamente');
		}else{
		$this->set('errorMessage','Los datos no fuer&oacute;n modificados');
		}//fin else actualizacion

$datos1=$this->cnmd09_incidencia_sueldo_sugerido->FindAll($this->SQLCA());
$this->set('datos',$datos1);

$datos2=$this->Cnmd01->FindAll($this->SQLCA());
$this->set('datos2',$datos2);
 }//guardar modificar



  function eliminar($nomina=null){
 		$this->layout="ajax";

			$sql = "DELETE FROM cnmd09_incidencia_sueldo_sugerido WHERE ".$this->SQLCA()." and cod_tipo_nomina=".$nomina;
				if($this->cnmd09_incidencia_sueldo_sugerido->execute($sql)>1){
					$this->set('Message_existe', 'EL REGISTRO FUE ELIMINADO EXITOSAMENTE');
				}else{
					$this->set('errorMessage', 'EL REGISTRO NO PUDO SER ELIMINADO');
				}
 }//eliminar


function grilla($var=null){
	$this->layout="ajax";
	$datos1=$this->cnmd09_incidencia_sueldo_sugerido->FindAll($this->SQLCA());
	$this->set('datos',$datos1);

	$datos2=$this->Cnmd01->FindAll($this->SQLCA());
	$this->set('datos2',$datos2);
}


 }//Fin de la clase
?>
