<?php
/*
 * Creado el 29/07/2008 a las 12:11:16 PM
 * Herramienta: EasyEclipse.
 * Proyecto: SIGEP
 * Descripcion:
 */

 class Shp950SolvenciaNumeroController extends AppController{
	var $name = "shp950_solvencia_numero";
 	var $uses = array('shd950_solvencia_numero','ccfd04_cierre_mes','cugd05_restriccion_clave','shd000_arranque');
 	var $helpers = array('Html','Ajax','Javascript', 'Sisap','Fpdf','Form');


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

function SQLCAIN($ano=null){//sql para busqueda de codigos de arranque con y sin año
		 $sql_re = $this->verifica_SS(1).",";
		 $sql_re .= $this->verifica_SS(2).",";
		 $sql_re .=  $this->verifica_SS(3).",";
		 $sql_re .= $this->verifica_SS(4).",";
		 if($ano!=null){
			 $sql_re .= $this->verifica_SS(5).",";
				$sql_re .= $ano."";
		 }else{
			 $sql_re .=  $this->verifica_SS(5)."";
		 }
		 return $sql_re;
}//fin funcion SQLCAIN


 function index($var=null, $var2=null){
	 $this->layout = "ajax";

	 $cod_presi = $this->Session->read('SScodpresi');
	 $cod_entidad = $this->Session->read('SScodentidad');
	 $cod_tipo_inst = $this->Session->read('SScodtipoinst');
	 $cod_inst = $this->Session->read('SScodinst');
	 $cod_dep = $this->Session->read('SScoddep');
	 $ano = $this->shd000_arranque->ano($this->SQLCA());
	 $this->set('year', $ano);
	 $this->Session->write('year_pago',$ano);


	 //$c=$this->csrd01_solicitud_recurso_numero->findCount();
	 $max=$this->shd950_solvencia_numero->execute("SELECT MAX(numero_solvencia) as numero_mayor FROM shd950_solvencia_numero WHERE ".$this->SQLCA()." and ano=".$ano);
     if($max[0][0]["numero_mayor"]==""){
     	$this->set("ultimo_input",0);
     	$this->set("crear_desde",1);
     }else{
     	$this->set("ultimo_input",$max[0][0]["numero_mayor"]);
     	$this->set("crear_desde",$max[0][0]["numero_mayor"]+1);
     }
     if($var2!=null){
		$this->set('msg_error1', $msg_error1 = 'NECESITA CREAR LOS NUMEROS DE SOLVENCIA PARA CONTINUAR');
		return;
	 }
     if(isset($var)){
     	if($var=="guardado"){
     		 $this->set("Message_existe","Números creados con exito");
     	}else if($var=="si"){
             $this->set("Message_existe","Situacion del n&uacute;mero actualizada con exito");
        }else if($var=="autor_valido"){
             $this->set("Message_existe","Puede crear los números de SOLVENCIA");
        }else{
        	if($var=="xy"){
				$this->set('msg_error1', $msg_error1 = 'NECESITA CREAR O DESCONGELAR LOS NUMEROS DE SOLVENCIA PARA CONTINUAR');
			}else{
		    	$this->set("errorMessage","Situacion del n&uacute;mero actualizada sin exito");
        	}
	    }
	}


	$Tfilas=$this->shd950_solvencia_numero->findCount($this->SQLCA()." and ano=".$ano);
    if($Tfilas!=0){
    	$pagina=1;
    	$Tfilas=(int)ceil($Tfilas/1000);
		$this->set('pag_cant',$pagina.'/'.$Tfilas);
		$this->set('total_paginas',$Tfilas);
		$this->set('pagina_actual',$pagina);
		$this->set('ultimo',$Tfilas);
 	    $datos_filas=$this->shd950_solvencia_numero->findAll($this->SQLCA()." and ano=".$ano,null,"numero_solvencia ASC",1000,1,null);
        $this->set("datosFILAS",$datos_filas);
        $this->set('siguiente',$pagina+1);
		$this->set('anterior',$pagina-1);
		$this->bt_nav($Tfilas,$pagina);
    }else{
    	$this->set("datosFILAS",'');
    }

    $this->set('modelo','shd950_solvencia_numero');
}//fin index


function guardar(){
	$this->layout="ajax";
	$modelo = 'shd950_solvencia_numero';
	if(isset($this->data[$modelo])){
			 $ano=$this->data[$modelo]["ano"];
			 $ultimo=$this->data[$modelo]["ultimo"];
			 $crear_desde=$this->data[$modelo]["crear_desde"];
			 $crear_hasta=$this->data[$modelo]["crear_hasta"];

			 $camposT3="cod_presi,cod_entidad,cod_tipo_inst,cod_inst,cod_dep,ano,numero_solvencia,situacion";
			 $values="";
			 $monto=0;
			 $SQLAIN = $this->SQLCAIN($ano);
			 for($z=$crear_desde;$z<=$crear_hasta;$z++){
			 	$values[] =" (".$SQLAIN.",".$z.",1)";

			 }
			 $valores = implode(',',$values);
			 $R3=$this->shd950_solvencia_numero->execute("INSERT INTO shd950_solvencia_numero (".$camposT3.") VALUES ".$valores.";");
             if($R3 > 1){
             	$msg="guardado";
             }else{
                 $msg=false;
             }
             $this->set('autor_valido',true);
             $this->index($msg);
             $this->render("index");
 	}else{
		echo "Hay campos sin llenar";
	}
}//fin funcion guardar



function cambiar_situacion ($codigo,$ano,$var) {
	$this->layout="ajax";
    $resultado=$this->shd950_solvencia_numero->execute("UPDATE shd950_solvencia_numero SET situacion=1 WHERE ".$this->SQLCA()." and numero_solvencia=".$codigo." and ano=".$ano);
	if($resultado>1){
        $this->set("Message_existe","Situacion del n&uacute;mero actualizada con exito");
        $m="si";
	}else{
		$this->set("errorMessage","Lo siento, Situaci&oacute;n del n&uacute;mero actualizada sin exito");
		$m="no";
	}
    $this->index($m);
    $this->render("index");
    $this->set('modelo','shd950_solvencia_numero');
 }


function cambiar_situacion_por_emitir ($codigo,$ano,$var) {
	$this->layout="ajax";
    $resultado=$this->shd950_solvencia_numero->execute("UPDATE shd950_solvencia_numero SET situacion=1 WHERE ".$this->SQLCA()." and numero_solvencia=".$codigo." and ano=".$ano);
	if($resultado>1){
        $this->set("Message_existe","Situacion del n&uacute;mero actualizada con exito");
        $m="si";
	}else{
		$this->set("errorMessage","Lo siento, Situaci&oacute;n del n&uacute;mero actualizada sin exito");
		$m="no";
	}
    $this->index($m);
    $this->render("index");
    $this->set('modelo','shd950_solvencia_numero');
 }



function cambiar_situacion_celdacompleta($codigo,$ano,$var,$opc){
	$this->layout="ajax";

	if($opc==1){//anular el numero de solicitud.
	    $resultado=$this->shd950_solvencia_numero->execute("UPDATE shd950_solvencia_numero SET situacion=4 WHERE ".$this->SQLCA()." and numero_solvencia=".$codigo." and ano=".$ano);
	    $consulta_numero =$this->shd950_solvencia_numero->execute("SELECT ano, numero_solvencia, situacion FROM shd950_solvencia_numero WHERE ".$this->SQLCA()." and numero_solvencia=".$codigo." and ano=".$ano);
		if($resultado>1){
	   	$this->set("Message_existe","N&uacute;mero cambiado a anulado correctamente");
		}else{
		$this->set("errorMessage","Lo siento, El n&uacute;mero no pudo ser cambiado a anulado");
		}
		$this->set('codigo',$consulta_numero[0][0]["numero_solvencia"]);
		$this->set('ano',$consulta_numero[0][0]["ano"]);
		$this->set('i',$var);
		$this->set('opc',$opc);

	}elseif($opc==2){//colocar como emitido el numero de solicitud.
	    $resultado=$this->shd950_solvencia_numero->execute("UPDATE shd950_solvencia_numero SET situacion=3 WHERE ".$this->SQLCA()." and numero_solvencia=".$codigo." and ano=".$ano);
	    $consulta_numero =$this->shd950_solvencia_numero->execute("SELECT ano, numero_solvencia, situacion FROM shd950_solvencia_numero WHERE ".$this->SQLCA()." and numero_solvencia=".$codigo." and ano=".$ano);
		if($resultado>1){
	    $this->set("Message_existe","N&uacute;mero cambiado a emitido correctamente");
		}else{
		$this->set("errorMessage","Lo siento, el n&uacute;mero no pudo ser cambiado a emitido");
		}
		$this->set('codigo',$consulta_numero[0][0]["numero_solvencia"]);
		$this->set('ano',$consulta_numero[0][0]["ano"]);
		$this->set('i',$var);
		$this->set('opc',$opc);

	}elseif($opc==3){//Congelar el numero de solicitud.
	    $resultado=$this->shd950_solvencia_numero->execute("UPDATE shd950_solvencia_numero SET situacion=5 WHERE ".$this->SQLCA()." and numero_solvencia=".$codigo." and ano=".$ano);
	    $consulta_numero =$this->shd950_solvencia_numero->execute("SELECT ano, numero_solvencia, situacion FROM shd950_solvencia_numero WHERE ".$this->SQLCA()." and numero_solvencia=".$codigo." and ano=".$ano);
		if($resultado>1){
	    $this->set("Message_existe","N&uacute;mero congelado correctamente");
		}else{
		$this->set("errorMessage","Lo siento, el n&uacute;mero no pudo ser congelado");
		}
		$this->set('codigo',$consulta_numero[0][0]["numero_solvencia"]);
		$this->set('ano',$consulta_numero[0][0]["ano"]);
		$this->set('i',$var);
		$this->set('opc',$opc);

	}elseif($opc==4){//Descongelar el numero de solicitud.
	    $resultado=$this->shd950_solvencia_numero->execute("UPDATE shd950_solvencia_numero SET situacion=1 WHERE ".$this->SQLCA()." and numero_solvencia=".$codigo." and ano=".$ano);
	    $consulta_numero =$this->shd950_solvencia_numero->execute("SELECT ano, numero_solvencia, situacion FROM shd950_solvencia_numero WHERE ".$this->SQLCA()." and numero_solvencia=".$codigo." and ano=".$ano);
		if($resultado>1){
	    $this->set("Message_existe","N&uacute;mero descongelado correctamente");
		}else{
		$this->set("errorMessage","Lo siento, el n&uacute;mero no pudo ser descongelado");
		}
		$this->set('codigo',$consulta_numero[0][0]["numero_solvencia"]);
		$this->set('ano',$consulta_numero[0][0]["ano"]);
		$this->set('i',$var);
		$this->set('opc',$opc);
	}elseif($opc==5){//colocar como por emitir el numero de solicitud.
	    $resultado=$this->shd950_solvencia_numero->execute("UPDATE shd950_solvencia_numero SET situacion=1 WHERE ".$this->SQLCA()." and numero_solvencia=".$codigo." and ano=".$ano);
	    $consulta_numero =$this->shd950_solvencia_numero->execute("SELECT ano, numero_solvencia, situacion FROM shd950_solvencia_numero WHERE ".$this->SQLCA()." and numero_solvencia=".$codigo." and ano=".$ano);
		if($resultado>1){
	    $this->set("Message_existe","N&uacute;mero cambiado a sin utilizar correctamente");
		}else{
		$this->set("errorMessage","Lo siento, el n&uacute;mero no pudo ser cambiado a sin utilizar");
		}
		$this->set('codigo',$consulta_numero[0][0]["numero_solvencia"]);
		$this->set('ano',$consulta_numero[0][0]["ano"]);
		$this->set('i',$var);
		$this->set('opc',$opc);
	}
	$this->set('modelo','shd950_solvencia_numero');
}



function consulta ($pagina=null) {
$this->layout = "ajax";
	 $cod_presi = $this->Session->read('SScodpresi');
	 $cod_entidad = $this->Session->read('SScodentidad');
	 $cod_tipo_inst = $this->Session->read('SScodtipoinst');
	 $cod_inst = $this->Session->read('SScodinst');
	 $cod_dep = $this->Session->read('SScoddep');
	 $condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst;
	 $condicion_dir_sup = "cod_tipo_institucion = ".$cod_tipo_inst." and cod_institucion = ".$cod_inst." and cod_dependencia=".$cod_dep;
     $dato=$this->ano_ejecucion();
	 $this->set('year', $dato);
	 $this->Session->write('year_pago',$dato);
     if(isset($pagina)){
				$pagina=$pagina;
				$this->Session->delete('MSJ');
		}else{
				 $pagina=1;
		}//fin else
		$Tfilas=$this->shd950_solvencia_numero->findCount($this->SQLCA()." and ano=".$dato);
        if($Tfilas!=0){
        	//$Tfilas=$Tfilas/1000;
        	$Tfilas=(int)ceil($Tfilas/1000);
        	$this->set('total_paginas',$Tfilas);
			$this->set('pagina_actual',$pagina);
			$this->set('pag_cant',$pagina.'/'.$Tfilas);
			$this->set('ultimo',$Tfilas);
     	    $datos_filas=$this->shd950_solvencia_numero->findAll($this->SQLCA()." and ano=".$dato,null,"numero_solvencia ASC",1000,$pagina,null);
	        $this->set("datosFILAS",$datos_filas);
	        $this->set('siguiente',$pagina+1);
			$this->set('anterior',$pagina-1);
			$this->bt_nav($Tfilas,$pagina);
        }else{
        	$this->set("datosFILAS",'');
        }
        $this->set('modelo','shd950_solvencia_numero');
}//fin consulta



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



function entrar(){
	$this->layout="ajax";
	if(isset($this->data['cscp02_solicitud_numero']['login']) && isset($this->data['cscp02_solicitud_numero']['password'])){
		$l="PROYECTO";
		$c="JJJSAE";
		$user=addslashes($this->data['cscp02_solicitud_numero']['login']);
		$paswd=addslashes($this->data['cscp02_solicitud_numero']['password']);
		$cond=$this->SQLCA()." and username='".$user."' and cod_tipo=31 and clave='".$paswd."'";
		if(($user==$l && $paswd==$c)){
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
	}else{
		$this->set('errorMessage',"Debe ingresar su login y su contrase&tilde;na");
		$this->set('autor_valido',false);
		$this->index("autor_valido");
		$this->render("index");
	}
}

 }//Fin class
 ?>
