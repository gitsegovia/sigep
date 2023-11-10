<?php
/*
 * Creado el 07/02/2008 a las 04:03:45 PM
 * Herramienta: EasyEclipse.
 * Proyecto: SIGEP
 * Descripcion:
 */
 class Cugp05RestriccionTipoController extends AppController{
 	var $name="cugp05_restriccion_tipo";
 	var $uses=array('cugd05_restriccion_tipo');
 	var $helpers=array('Html','Ajax','Javascript','Sisap');

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


 function index(){
 	$this->layout ="ajax";
 	$this->set('entidad_federal', $this->Session->read('entidad_federal'));

	$num=$this->cugd05_restriccion_tipo->findCount();
	$tipo_restric = $this->cugd05_restriccion_tipo->generateList(null,'cod_tipo ASC', null, '{n}.cugd05_restriccion_tipo.cod_tipo', '{n}.cugd05_restriccion_tipo.denominacion');
	$tipo_restric = $tipo_restric != null ? $tipo_restric : array();
	$this->concatena($tipo_restric, 'tipo');
	$this->data["cugp05_restriccion_tipo"]=null;
 	$this->set('enable', 'disabled');
 	$this->set('num',$num);

 	$datos=$this->cugd05_restriccion_tipo->findAll(null,null,'cod_tipo ASC');
 	$this->set('datos',$datos);
 }


 function selec_tipo($var = null){
 	$this->layout ="ajax";
  	$this->set('action', $var);

	$num=$this->cugd05_restriccion_tipo->findCount();
	$this->set('num',$num);

 	$tipo_restric = $this->cugd05_restriccion_tipo->generateList(null,'cod_tipo ASC', null, '{n}.cugd05_restriccion_tipo.cod_tipo', '{n}.cugd05_restriccion_tipo.denominacion');
	$tipo_restric = $tipo_restric != null ? $tipo_restric : array();
	$this->concatena($tipo_restric, 'tipo');
 	if($var != 'otros'){
		$this->set('datos', $this->cugd05_restriccion_tipo->findAll('cod_tipo = '.$var));
 	}else{
 		$this->data["cugp05_restriccion_tipo"] = array();
 	}
	$this->set('enable', 'disabled');
}


function guardar(){
 	$this->layout ="ajax";

 	if($this->data["cugp05_restriccion_tipo"]["denominacion"] !=''){
		$denominacion = $this->data["cugp05_restriccion_tipo"]['denominacion'];
		$maximo=$this->cugd05_restriccion_tipo->execute("SELECT MAX(cod_tipo) AS num FROM cugd05_restriccion_tipo");
		$codigo=$maximo[0][0]['num']+1;//incremento el cod_tipo en uno y lo guardo en la tabla

		$sql ="INSERT INTO cugd05_restriccion_tipo (cod_tipo,denominacion) values ('$codigo','".$denominacion."')";
		$x=$this->cugd05_restriccion_tipo->execute($sql);
		if($x>1){
			$this->set('mensaje', 'LA INFORMACION FUE REGISTRADA CORRECTAMENTE');
			$this->index();
			$this->render("index");
		}else{
		$this->set('mensajeError', 'ERROR, LA INFORMACION NO PUDO SER REGISTRADA');
		$this->index();
		$this->render("index");
		}
 	}else{
 		$this->set('datos', array());
 		$this->set('mensajeError', 'DEBE INGRESAR LA DENOMINACION POR FAVOR');
 		$this->index();
		$this->render("index");
 	}
 }

function consultar($pagina=null) {
	$this->layout="ajax";

	if(isset($pagina)){
		$Tfilas=$this->cugd05_restriccion_tipo->findCount();
        if($Tfilas!=0){
        	$data=$this->cugd05_restriccion_tipo->findAll(null,null,"cod_tipo, denominacion ASC",1,$pagina,null);

            $this->set('DATA',$data);
          	$this->set('siguiente',$pagina+1);
          	$this->set('anterior',$pagina-1);
            $this->bt_nav($Tfilas,$pagina);
            $this->set('numT',$Tfilas);
			$this->set('numP',$pagina);
        }else{
	 	    $this->set('errorMessage', 'No se encontrar&oacute;n datos');
	 	    $this->set('noExiste',true);
        }

	}else{
		$pagina=1;
		$Tfilas=$this->cugd05_restriccion_tipo->findCount();

        if($Tfilas!=0){
        	$data=$this->cugd05_restriccion_tipo->findAll(null,null,"cod_tipo, denominacion ASC",1,$pagina,null);
			$this->set('DATA',$data);
          	$this->set('siguiente',$pagina+1);
          	$this->set('anterior',$pagina-1);
          	$this->bt_nav($Tfilas,$pagina);
          	$this->set('numT',$Tfilas);
			$this->set('numP',$pagina);

        }else{
	 	       $this->set('errorMessage', 'No se encontrar&oacute;n datos');
	 	       $this->set('noExiste',true);
        }
	}

	$this->set('cod_restriccion', $data[0]['cugd05_restriccion_tipo']['cod_tipo']);
	$this->set('denominacion', $data[0]['cugd05_restriccion_tipo']['denominacion']);
 }//consultar


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



function modificar($cod_tipo_restriccion=null){
 	$this->layout ="ajax";
 	$datos=$this->cugd05_restriccion_tipo->findAll('cod_tipo = '.$cod_tipo_restriccion);
 	$this->set('cod_restriccion',$datos[0]['cugd05_restriccion_tipo']['cod_tipo']);
 	$this->set('denominacion',$datos[0]['cugd05_restriccion_tipo']['denominacion']);
 	$this->set('Message_existe', 'INGRESE LOS DATOS A MODIFICAR');
}


function guardar_modificar($var=null){
	$this->layout = "ajax";
	//valido la denominacion
	if(!empty($this->data['cugp05_restriccion_tipo']['denominacion'])){

	$b=$this->data['cugp05_restriccion_tipo']['denominacion'];
	$sql3="update cugd05_restriccion_tipo set denominacion='".$b."' where cod_tipo=".$var;
		if($this->cugd05_restriccion_tipo->execute($sql3)>0){
		$this->set('mensaje', 'LOS DATOS FUER&Oacute;N MODIFICADOS CORRECTAMENTE');
		$this->index();
	    $this->render("index");
		}
	}else{
    	$this->set('datos', array());
 		$this->set('mensajeError', 'LA DENOMINACI&Oacute;N NO PUEDE ESTAR VAC&Iacute;A');
		$this->index();
        $this->render("index");
	}
}


function eliminar($cod_tipo=null){
	$this->layout="ajax";

    if($cod_tipo!=null){
	   $sql="DELETE FROM cugd05_restriccion_tipo WHERE cod_tipo=".$cod_tipo;
	   if($this->cugd05_restriccion_tipo->execute($sql)>1){
	      $this->set('mensaje','LA RESTRICCION FUE ELIMINADA CORRECTAMENTE');
	   }else{
	      $this->set('mensajeError','LO SIENTO, LA RESTRICCION NO PUDO SER ELIMINADA');
	   }
    }else{
    	  $this->set('mensajeError','LO SIENTO, NO LLEGO EL CODIGO DE LA RESTRICCION');
    }
}//eliminar

 }//fin clase
?>
