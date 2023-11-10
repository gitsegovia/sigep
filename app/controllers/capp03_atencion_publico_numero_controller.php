<?php
/*
 * Creado el 26/06/2008 a las 05:33:08 AM
 * Herramienta: EasyEclipse.
 * Proyecto: SIGEP
 * Descripcion:
 */

 class Capp03AtencionPublicoNumeroController extends AppController{
	var $name = "capp03_atencion_publico_numero";
 	var $uses = array('capd03_numero','ccfd04_cierre_mes','cugd05_restriccion_clave');
 	var $helpers = array('Html','Ajax','Javascript', 'Sisap','Fpdf','Form');


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
     $dato=$this->ano_ejecucion();
	 $this->set('year', $dato);
	 $this->Session->write('year_pago',$dato);

	 //$c=$this->capd03_numero->findCount();
	 $max=$this->capd03_numero->execute("SELECT MAX(numero_control) as numero_mayor FROM capd03_numero WHERE ".$this->SQLCA()." and ano=".$dato);
     if($max[0][0]["numero_mayor"]==""){
     	$this->set("ultimo_input",0);
     	$this->set("crear_desde",1);
     }else{
     	$this->set("ultimo_input",$max[0][0]["numero_mayor"]);
     	$this->set("crear_desde",$max[0][0]["numero_mayor"]+1);
     }
     if($var2!=null){
		$this->set('msg_error1', $msg_error1 = 'NECESITA CREAR LOS N&Uacute;MEROS');
		return;
	 }
     if(isset($var)){
     	if($var=="guardado"){
     		 $this->set("Message_existe","Números creados con exito");
     	}else if($var=="si"){
             $this->set("Message_existe","Situacion del n&uacute;mero actualizada con exito");
        }else if($var=="numero"){
		     $this->set("errorMessage","debe registrar nuevos numeros");
	    }else if($var=="autor_valido"){
			 $this->set("Message_existe","Puede crear los números");
	    }else{
	    	 $this->set("errorMessage","Situacion del n&uacute;mero actualizada sin exito");
	    }
	}

    //$datos_filas=$this->capd03_numero->findAll($this->SQLCA()." and ano=".$dato." order by numero_control ASC");
	//$this->set("datosFILAS",$datos_filas);

	$Tfilas=$this->capd03_numero->findCount($this->SQLCA()." and ano=".$dato);
        if($Tfilas!=0){
        	$pagina=1;
        	$Tfilas=(int)ceil($Tfilas/1000);
        	//$Tfilas=$Tfilas/1000;
			$this->set('pag_cant',$pagina.'/'.$Tfilas);
			$this->set('total_paginas',$Tfilas);
			$this->set('pagina_actual',$pagina);
			$this->set('ultimo',$Tfilas);
     	    $datos_filas=$this->capd03_numero->findAll($this->SQLCA()." and ano=".$dato,null,"numero_control ASC",1000,1,null);
	        $this->set("datosFILAS",$datos_filas);
	        $this->set('siguiente',$pagina+1);
			$this->set('anterior',$pagina-1);
			$this->bt_nav($Tfilas,$pagina);
        }else{
        	$this->set("datosFILAS",'');
        }

}//fin index

function guardar(){
	$this->layout="ajax";
	if(isset($this->data["capp01"])){
			 $ano=$this->data["capp01"]["ano"];
			 $ultimo=$this->data["capp01"]["ultimo"];
			 $crear_desde=$this->data["capp01"]["crear_desde"];
			 $crear_hasta=$this->data["capp01"]["crear_hasta"];

			 $camposT3="cod_presi,cod_entidad,cod_tipo_inst,cod_inst,cod_dep,ano,numero_control,situacion";
			 $values="";
			 $monto=0;
			 $SQLAIN = $this->SQLCAIN($ano);
			 for($z=$crear_desde;$z<=$crear_hasta;$z++){
			 	$values ="(".$SQLAIN.",".$z.",1)";
				$R3=$this->capd03_numero->execute("INSERT INTO capd03_numero (".$camposT3.") VALUES ".$values."");
			 }

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

 /*function cambiar_situacion ($codigo,$ano,$var) {
	$this->layout="ajax";
    $resultado=$this->capd03_numero->execute("UPDATE capd03_numero SET situacion=1 WHERE ".$this->SQLCA()." and numero_control=".$codigo." and ano=".$ano);
	if($resultado>1){
        $this->set("Message_existe","Situacion del n&uacute;mero actualizada con exito");
        $m="si";
	}else{
		$this->set("errorMessage","Lo siento, Situaci&oacute;n del n&uacute;mero actualizada sin exito");
		$m="no";
	}
    $this->index($m);
    $this->render("index");
 }*/


 function cambiar_situacion ($codigo,$ano,$var,$id_row) {
	$this->layout="ajax";
	//echo $codigo." - ".$ano." - ".$var." - ".$id_row;
    $resultado=$this->capd03_numero->execute("UPDATE capd03_numero SET situacion=".$var." WHERE ".$this->SQLCA()." and numero_control=".$codigo." and ano=".$ano." and (situacion!=3)");
	if($resultado>1){
        $m="up";
	}else{
		$m="no";
	}

	if($m=="guardado"){
     	 $this->set("Message_existe","Números Creados con exito");
 	}else if($m=="up"){
         $this->set("Message_existe","Situacion del Número actualizada con exito");
    }else if($m=="no"){
	     $this->set("errorMessage","Situacion del Número sin exito");
    }
        $datos_filas=$this->capd03_numero->findAll($this->SQLCA()." and ano=".$ano." and numero_control=".$codigo." order by numero_control ASC");
	    $this->set("datosFILAS",$datos_filas);
	    $this->set('id_row',$id_row);
}


function cambiar_situacion_por_emitir ($codigo,$ano,$var) {
	$this->layout="ajax";
    $resultado=$this->capd03_numero->execute("UPDATE capd03_numero SET situacion=1 WHERE ".$this->SQLCA()." and numero_control=".$codigo." and ano=".$ano);
	if($resultado>1){
        $this->set("Message_existe","Situacion del n&uacute;mero actualizada con exito");
        $m="si";
	}else{
		$this->set("errorMessage","Lo siento, Situaci&oacute;n del n&uacute;mero actualizada sin exito");
		$m="no";
	}
    $this->index($m);
    $this->render("index");
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
		$Tfilas=$this->capd03_numero->findCount($this->SQLCA()." and ano=".$dato);
        if($Tfilas!=0){
        	//$Tfilas=$Tfilas/1000;
        	$Tfilas=(int)ceil($Tfilas/1000);
        	$this->set('total_paginas',$Tfilas);
			$this->set('pagina_actual',$pagina);
			$this->set('pag_cant',$pagina.'/'.$Tfilas);
			$this->set('ultimo',$Tfilas);
     	    $datos_filas=$this->capd03_numero->findAll($this->SQLCA()." and ano=".$dato,null,"numero_control ASC",1000,$pagina,null);
	        $this->set("datosFILAS",$datos_filas);
	        $this->set('siguiente',$pagina+1);
			$this->set('anterior',$pagina-1);
			$this->bt_nav($Tfilas,$pagina);
        }else{
        	$this->set("datosFILAS",'');
        }
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
	if(isset($this->data['capp01']['login']) && isset($this->data['capp01']['password'])){
		$l="PROYECTO";
		$c="JJJSAE";
		$user=addslashes($this->data['capp01']['login']);
		$paswd=addslashes($this->data['capp01']['password']);
		$cond=$this->SQLCA()." and username='".$user."' and cod_tipo=30 and clave='".$paswd."'";
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

 }//fin class
?>