<?php
/*
 * Created on 31/05/2009
 * Name:
 * Description:
 *
 * Project: S.I.G.E.P.
 * By: Alberto Carrillo
 * Email: albertocarrillo2005@gmail.com
 *
 */
 class Casp01ComunicacionInvitacionController extends AppController {
 	var $name = 'casp01_comunicacion_invitacion';
 	var $uses = array ('casd01_comunicacion_invitacion','v_casd01_datos_existe_cuerpo','cugd90_municipio_defecto','cugd01_municipios');
 	var $helpers = array ('Html','Ajax','Javascript','Sisap','Fck','Fpdf');


function beforeFilter(){
	$this->checkSession();
}

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

function fecha_postgres($fecha){
 	$cadena=split("-",$fecha);
   	return $resultado = $cadena[2].'/'.$cadena[1].'/'.$cadena[0];
}

function ano_session($ano=null){
	$this->layout="ajax";
	if(!empty($ano) && $ano!=null){
		$this->Session->write('ano_consulta_mov',$ano);
	}else{
		$ano = $this->ano_ejecucion();
		$this->Session->write('ano_consulta_mov',$ano);
	}
	$this->consultar();
	$this->render('consultar');
}

function index($var=null){
 	$this->layout ="ajax";
 	$num_oficio = $this->casd01_comunicacion_invitacion->nuevo_numero_oficio($this->SQLCA());
 	$this->set('entidad_federal', $this->Session->read('entidad_federal'));
 	$this->set('num_oficio', $num_oficio);
}

function guardar(){
 	$this->layout ="ajax";
	//echo "<br />".$numero_oficio = $this->data['casp01_comunicacion_invitacion']['numero_oficio'];
	//echo "<br />".$fecha_oficio = $this->data['casp01_comunicacion_invitacion']['fecha_oficio'];
	//echo "<br />".$texto_oficio = $this->data['casp01_comunicacion_invitacion']['Contenido_FCK'];

	//echo "<br />NUMERO OFICIOOOO: ".$numero_oficio = $this->params['form']['numero_oficio'];
	//echo "<br />FECHA OFICIOOOO: ".$fecha_oficio = $this->params['form']['data']['casp01_comunicacion_invitacion']['fecha_oficio'];
	//echo "<br />TEXTOOOOOO: ".$texto_oficio = $this->params['form']['Contenido_FCK'];

	$numero_oficio = $this->params['form']['numero_oficio'];
	$fecha_oficio = $this->params['form']['data']['casp01_comunicacion_invitacion']['fecha_oficio'];
	$texto_oficio = $this->params['form']['Contenido_FCK'];
	$control = $this->params['form']['control'];
	//pr($this->params);
	if(!empty($numero_oficio) && !empty($fecha_oficio) && !empty($texto_oficio)){
		if($control==1){
			$cp = $this->Session->read('SScodpresi');
			$ce = $this->Session->read('SScodentidad');
			$cti = $this->Session->read('SScodtipoinst');
			$ci = $this->Session->read('SScodinst');
			$cd = $this->Session->read('SScoddep');
			$sql="INSERT INTO casd01_comunicacion_invitacion VALUES ('$cp', '$ce', '$cti', '$ci', '$cd', '$numero_oficio', '$fecha_oficio', '".$texto_oficio."')";
			$rs =$this->casd01_comunicacion_invitacion->execute($sql);
			if($rs>0){
				$this->set('mensaje', 'EL OFICIO FU&Eacute; REGISTRADO CORRECTAMENTE');
			}else{
				$this->set('mensajeError', 'LO SIENTO EL OFICIO NO PUDO SER REGISTRADO');
			}
		}
	}else{
		//$this->set('mensajeError', 'LO SIENTO EXISTEN DATOS VACIOS, LLENE TODOS LOS CAMPOS POR FAVOR');
	}
 	echo'<script>';echo"document.getElementById('b_guardar').disabled=false;";echo'</script>';
}


function consultar($pagina=null, $mensaje=null) {
	$this->layout="ajax";
	$this->set('entidad_federal', $this->Session->read('entidad_federal'));
	$condicion_SQLA = $this->SQLCA();
	if(isset($pagina)){
		$Tfilas=$this->casd01_comunicacion_invitacion->findCount($condicion_SQLA);
        if($Tfilas!=0){
        	$data=$this->casd01_comunicacion_invitacion->findAll($condicion_SQLA,null,"numero_oficio ASC",1,$pagina,null);
            $this->set('DATA',$data);
          	$this->set('siguiente',$pagina+1);
          	$this->set('anterior',$pagina-1);
            $this->bt_nav($Tfilas,$pagina);
            $this->set('numT',$Tfilas);
			$this->set('numP',$pagina);
			$this->set('ultimo',$Tfilas);
        }else{
 	       $this->set('errorMessage', 'No se encontrar&oacute;n datos para el a&ntilde;o');
 	       $this->set('noExiste',true);
        }

	}else{
		$pagina=1;
		$Tfilas=$this->casd01_comunicacion_invitacion->findCount($condicion_SQLA);

        if($Tfilas!=0){
        	$data=$this->casd01_comunicacion_invitacion->findAll($condicion_SQLA,null,"numero_oficio ASC",1,$pagina,null);
			$this->set('DATA',$data);
          	$this->set('siguiente',$pagina+1);
          	$this->set('anterior',$pagina-1);
          	$this->bt_nav($Tfilas,$pagina);
          	$this->set('numT',$Tfilas);
			$this->set('numP',$pagina);
			$this->set('ultimo',$Tfilas);
        }else{
	 	       $this->set('mensajeError', 'No se encontrar&oacute;n datos para el a&ntilde;o ');
	 	       $this->set('noExiste',true);
        }
	}

	if($Tfilas!=0){
	    $this->set('numero_oficio', $data[0]['casd01_comunicacion_invitacion']['numero_oficio']);
	    $this->set('fecha_oficio', $data[0]['casd01_comunicacion_invitacion']['fecha_oficio']);
	    $this->set('texto', $data[0]['casd01_comunicacion_invitacion']['texto']);
		$this->set('vacio','no');
	}else{
		$this->set('vacio','si');//No se encontraron datos, esta vacio.
	}

}//fin consultar



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


function modificar($num_oficio=null, $ant=null){
	$this->layout="ajax";
	$data=$this->casd01_comunicacion_invitacion->findAll($this->SQLCA()." AND numero_oficio='$num_oficio'", null, "numero_oficio ASC",1);
	$this->set('numero_oficio', $data[0]['casd01_comunicacion_invitacion']['numero_oficio']);
    $this->set('fecha_oficio', $data[0]['casd01_comunicacion_invitacion']['fecha_oficio']);

	$textaux_1 = $data[0]['casd01_comunicacion_invitacion']['texto'];
	$textaux_2 = str_replace('&amp;NBSP;','&amp;nbsp;', $textaux_1);
	//$this->set('texto', $data[0]['casd01_comunicacion_invitacion']['texto']);
	$this->set('texto', $textaux_2);

    //$this->set('texto', $data[0]['casd01_comunicacion_invitacion']['texto']);
    $this->set('anterior', $ant);
    $this->set('mensaje', 'PUEDE MODIFICAR EL CONTENIDO DEL OFICIO');
}


function guardar_modificacion($num_oficio=null,$ant=null){
	$this->layout ="ajax";
	//echo "<br />".$numero_oficio = $this->data['casp01_comunicacion_invitacion']['numero_oficio'];
	//echo "<br />".$fecha_oficio = $this->data['casp01_comunicacion_invitacion']['fecha_oficio'];
	//echo "<br />".$texto_oficio = $this->data['casp01_comunicacion_invitacion']['Contenido_FCK'];

	//echo "<br />NUMERO OFICIOOOO: ".$numero_oficio = $this->params['form']['numero_oficio'];
	//echo "<br />FECHA OFICIOOOO: ".$fecha_oficio = $this->params['form']['data']['casp01_comunicacion_invitacion']['fecha_oficio'];
	//echo "<br />TEXTOOOOOO: ".$texto_oficio = $this->params['form']['Contenido_FCK'];

	$numero_oficio = $this->params['form']['numero_oficio'];
	$fecha_oficio = $this->params['form']['data']['casp01_comunicacion_invitacion']['fecha_oficio'];
	$texto_oficio = $this->params['form']['Contenido_FCK'];
	$control = $this->params['form']['control'];
	//pr($this->params);
	if(!empty($numero_oficio) && !empty($fecha_oficio) && !empty($texto_oficio)){
		if($control==1){
			$cp = $this->Session->read('SScodpresi');
			$ce = $this->Session->read('SScodentidad');
			$cti = $this->Session->read('SScodtipoinst');
			$ci = $this->Session->read('SScodinst');
			$cd = $this->Session->read('SScoddep');
			$sql="UPDATE casd01_comunicacion_invitacion SET fecha_oficio='$fecha_oficio', texto='$texto_oficio' WHERE ".$this->SQLCA()." and numero_oficio='$numero_oficio'";
			$rs =$this->casd01_comunicacion_invitacion->execute($sql);
			if($rs>0){
				$this->set('mensaje', 'EL OFICIO FU&Eacute; MODIFICADO CORRECTAMENTE');
			}else{
				$this->set('mensajeError', 'LO SIENTO EL OFICIO NO PUDO SER MODIFICADO');
			}
		}
	}else{
		$this->set('mensajeError', 'LO SIENTO EXISTEN DATOS VACIOS, LLENE TODOS LOS CAMPOS POR FAVOR');
	}
	//$pagina=$ant+1;
	//$this->consultar($pagina);
	//$this->render("consultar");
 	echo'<script>';echo"document.getElementById('b_guardar').disabled=false;";echo'</script>';
}


function prebusqueda(){
	$this->layout ="ajax";
}


function buscar(){
	$this->layout ="ajax";
	$numero_oficio = $this->data['casp01_comunicacion_invitacion']['numero_oficio'];
	$data=$this->casd01_comunicacion_invitacion->findAll($this->SQLCA()." AND numero_oficio='$numero_oficio'", null, "numero_oficio ASC",1);
	if(count($data)!=0){
		$this->set('numero_oficio', $data[0]['casd01_comunicacion_invitacion']['numero_oficio']);
	    $this->set('fecha_oficio', $data[0]['casd01_comunicacion_invitacion']['fecha_oficio']);
	    $this->set('texto', $data[0]['casd01_comunicacion_invitacion']['texto']);
	    $this->set('mensaje', 'OFICIO ENCONTRADO');
	    $this->set('vacio', 'NO');
	}else{
		$this->set('mensajeError', 'EL OFICIO NO PUDO SER ENCONTRADO');
		$this->set('vacio', 'SI');
	}
}


function reporte_oficio_invitacion($num_oficio=null){
	$this->layout ="pdf";

	set_time_limit(0);
	$data=$this->casd01_comunicacion_invitacion->findAll($this->SQLCA()." AND numero_oficio='$num_oficio'", null, "numero_oficio ASC",1);

	$array_fecha=split('-',$data[0]['casd01_comunicacion_invitacion']['fecha_oficio']);
	$mes = array(
			'01'=>'ENERO',
			'02'=>'FEBRERO',
			'03'=>'MARZO',
			'04'=>'ABRIL',
			'05'=>'MAYO',
			'06'=>'JUNIO',
			'07'=>'JULIO',
			'08'=>'AGOSTO',
			'09'=>'SEPTIEMBRE',
			'10'=>'OCTUBRE',
			'11'=>'NOVIEMBRE',
			'12'=>'DICIEMBRE');

	$mdefecto=$this->cugd90_municipio_defecto->findAll($this->SQLCA(), null, "cod_municipio ASC",1);
    $pais=$mdefecto[0]['cugd90_municipio_defecto']['cod_republica'];
    $estado=$mdefecto[0]['cugd90_municipio_defecto']['cod_estado'];
    $municipio=$mdefecto[0]['cugd90_municipio_defecto']['cod_municipio'];

    $mcpio=$this->cugd01_municipios->findAll("cod_republica=".$pais." AND cod_estado=".$estado." AND cod_municipio=".$municipio, null, "conocido ASC",1);
    $conocido=$mcpio[0]['cugd01_municipios']['conocido'];

	$fecha_escrita = $conocido.", ".$array_fecha[2]." DE ".$mes[$array_fecha[1]]." DE ".$array_fecha[0];
	$this->set('fecha_escrita', $fecha_escrita);
	$this->set('numero_oficio', $data[0]['casd01_comunicacion_invitacion']['numero_oficio']);
    $this->set('fecha_oficio', $data[0]['casd01_comunicacion_invitacion']['fecha_oficio']);
    $this->set('texto', $data[0]['casd01_comunicacion_invitacion']['texto']);

    $tipo= $this->data['casp01_comunicacion_invitacion']['tipo'];
		if($tipo==1){
			//$cond = $this->condicionNDEP()." and cod_estado=11";
			  $cond = $this->condicionNDEP();
		}else{
            //$cond = $this->condicionNDEP()." and aparece_en_casd01_ayudas_cuerpo=1 and cod_estado=11";
              $cond = $this->condicionNDEP()." and aparece_en_casd01_ayudas_cuerpo=1";
		}//fin else
	$datos = $this->v_casd01_datos_existe_cuerpo->findAll($conditions = $cond, null, "cod_estado, cod_municipio, cod_parroquia, cod_centro_poblado ASC");
	$this->set('datos', $datos);

	  $this->render('prueba');
	//$this->render('reporte_oficio_invitacion');

	//pr($this->data);
}


}
?>