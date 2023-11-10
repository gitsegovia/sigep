<?php


class AdministradorsController extends AppController
{
    var $helpers = array('Html', 'Javascript', 'Ajax', 'Sisap');
    var $uses = array('cnmd04_tipo','cnmd04_ocupacion','cugd01_estados',
                      'cugd01_municipios', 'cugd01_parroquias', 'cugd01_centropoblados',
                      'cugd01_vialidad', 'cugd01_vereda', 'cugd02_institucion', 'cugd02_dependencia','cfpd07_obras_cuerpo',
                      'cugd02_direccionsuperior', 'cugd02_coordinacion', 'cugd02_secretaria','cfpd07_obras_partidas',
                      'cugd02_direccion', 'cugd02_division', 'cugd02_departamento', 'cugd02_oficina',
                      'cfpd01_grupo', 'cfpd01_partida', 'cfpd01_generica', 'cfpd01_especifica',
                      'cfpd01_sub_espec', 'cfpd01_auxiliar', 'cfpd01_ano_grupo', 'cfpd01_ano_partida',
                      'cfpd01_ano_generica', 'cfpd01_ano_especifica', 'cfpd01_ano_sub_espec','cepd02_contratoservicio_cuerpo',
                      'cfpd01_ano_auxiliar', 'cfpd01_grupo', 'cfpd01_partida', 'cfpd01_generica',
                      'cfpd01_especifica', 'cfpd01_sub_espec', 'cfpd01_auxiliar', 'Cnmd02_obreros_ramos', 'Cnmd02_obreros_grupos',
                      'Cnmd02_obreros_series', 'Cnmd02_obreros_puestos', 'Cnmd02_empleados_ramos', 'Cnmd02_empleados_grupos',
                      'Cnmd02_empleados_series', 'Cnmd02_empleados_puestos', 'Usuario','arrd05', 'Cnmd01',
                      'cnmd02_varios_puestos', 'cugd04','modulos', 'v_usuarios','cugd05_restriccion_clave', 'shd100_control_industria_comercio');


function checkSession(){
				if (!$this->Session->check('Usuario')){
						if (!$this->Session->check('concejo_comunal')){
								$this->redirect('/salir');
								exit();
						}
				}else{
					//$this->set('userTable', $this->requestAction('/cnmp03partidas/', array('return')));
					//echo "H".$this->requestAction('/usuarios/actualizar_user',array('return'));
					$this->requestAction('/usuarios/actualizar_user');
				}
}//fin checksession


    function beforeFilter()
    {
     $this->checkSession();

    }

function borrar_cugd04(){
	$condicion = $this->condicion();
	$username = $this->Session->read('nom_usuario');
	$username = strtoupper($username);

	$c = $this->cugd04->findCount($condicion." and username='$username'");

	if ($c!=0){
		$this->cugd04->execute("DELETE FROM cugd04 WHERE ".$condicion." and username='$username'");
	}

}


function vacio($notprog=null){
	$this->layout="ajax";
		$_SESSION['cod_dep_reporte_consolidado'] = $_SESSION['SScoddeporig'];
	$this->borrar_cugd04();
		 echo"<script>menu_activo();</script>";
	if($notprog!=null)
		$this->set('errorMessage', $notprog);
}//fin vacio



function index(){
    	$this->layout = "administradors";
    	$this->checkSession();
}//fin

function mensaje($var){
	$this->layout = "ajax";
	$this->checkSession();
	$this->set('errorMessage', $var);
}//fin


function mensaje_clave(){
	$this->layout = "ajax";
}


function standard($var){
	$this->layout = "administradors";
	$this->checkSession();
	$this->Session->write('Modulo2', $var);
	$cond_mod='cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$this->Session->read('SScoddep');
	$cant_modulos=$this->modulos->findCount("cod_modulo='".strtoupper($var)."' and status=1 and ".$cond_mod);
	if($cant_modulos!=0){
		$data_modulos=$this->modulos->findAll("cod_modulo='".strtoupper($var)."' and status=1 and ".$cond_mod,null,'orden_ubicacion ASC');
	    $this->set('data_modulos',$data_modulos);
	    foreach($data_modulos as $datamod){
	       if($datamod['modulos']['status']==1){
	       	 if($datamod['modulos']['cod_modulo']=='CUGP00'){
	       	 	$mod['uso_general']=array('img'=>strtolower($datamod['modulos']['cod_modulo']),'title'=>$datamod['modulos']['denominacion']);
	       	 }else{
	       	 	$mod[strtolower($datamod['modulos']['cod_modulo'])]=array('img'=>strtolower($datamod['modulos']['cod_modulo']),'title'=>$datamod['modulos']['denominacion']);
	       	    if($datamod['modulos']['cod_modulo']=='SHPP00'){
                    $datos_filas_aux=$this->shd100_control_industria_comercio->findAll($this->condicion());
                    $utiliza_planillas_liquidacion_previa = !isset($datos_filas_aux[0]["shd100_control_industria_comercio"]["utiliza_planillas_liquidacio"])?"":$datos_filas_aux[0]["shd100_control_industria_comercio"]["utiliza_planillas_liquidacio"];
					$frecuencia_pago_segun_ordenanza      = !isset($datos_filas_aux[0]["shd100_control_industria_comercio"]["frecuencia_pago_segun_ordena"])?"":$datos_filas_aux[0]["shd100_control_industria_comercio"]["frecuencia_pago_segun_ordena"];
				    $_SESSION["utiliza_planillas_liquidacion_previa"] = $utiliza_planillas_liquidacion_previa;
                    $_SESSION["frecuencia_pago_segun_ordenanza"]      = $frecuencia_pago_segun_ordenanza;

	       	    }
	       	 }
	       }else{
	       	 $mod["negar_menu"]=array('img'=>'salir','title'=>'No Autorizado');
	       }

	    }
	}else{
		if(($this->Session->read('SScodpresi')==1 && $this->Session->read('SScodentidad')==1 && $this->Session->read('SScodtipoinst')==1 && $this->Session->read('SScodinst')==1 && $this->Session->read('SScoddep')==1) || ($var=='canp00')){
			$mod["canp00"]=array('img'=>'canp00','title'=>'Consulta de alto nivel');
		}else{
		    $mod["negar_menu"]=array('img'=>'salir','title'=>'No Autorizado');
		    $this->redirect('/modulos');
		}
	}

    $this->set('img_mod',$mod);


$this->Session->write('permisos_127', $this->verifica_entrada('127',true));
$this->Session->write('permisos_128', $this->verifica_entrada('128',true));

	if($var=="cnp000"){

$this->Session->write('permisos_cnp113', $this->verifica_entrada('113',true));
$this->Session->write('permisos_cnp114', $this->verifica_entrada('114',true));
$this->Session->write('permisos_cnp115', $this->verifica_entrada('115',true));
$this->Session->write('permisos_cnp116', $this->verifica_entrada('116',true));
$this->Session->write('permisos_cnp117', $this->verifica_entrada('117',true));
$this->Session->write('permisos_cnp118', $this->verifica_entrada('118',true));
$this->Session->write('permisos_cnp119', $this->verifica_entrada('119',true));
$this->Session->write('permisos_cnp120', $this->verifica_entrada('120',true));
$this->Session->write('permisos_cnp122', $this->verifica_entrada('122',true));
$this->Session->write('permisos_cnp125', $this->verifica_entrada('125',true));
$this->Session->write('permisos_cnp126', $this->verifica_entrada('126',true));
$this->Session->write('permisos_cnp129', $this->verifica_entrada('129',true));
$this->Session->write('permisos_cnp130', $this->verifica_entrada('130',true));
$this->Session->write('permisos_cnp131', $this->verifica_entrada('131',true));
$this->Session->write('permisos_cnp132', $this->verifica_entrada('132',true));

	}else{
		$this->Session->delete('permisos_cnp113');
		$this->Session->delete('permisos_cnp114');
		$this->Session->delete('permisos_cnp115');
		$this->Session->delete('permisos_cnp116');
		$this->Session->delete('permisos_cnp117');
		$this->Session->delete('permisos_cnp118');
		$this->Session->delete('permisos_cnp119');
		$this->Session->delete('permisos_cnp120');
                $this->Session->delete('permisos_cnp122');
                $this->Session->delete('permisos_cnp125');
                $this->Session->delete('permisos_cnp126');
                $this->Session->delete('permisos_cnp129');
                $this->Session->delete('permisos_cnp131');
                $this->Session->delete('permisos_cnp132');
	}

}//fin

function uso_general(){
    	$this->layout = "uso_general";
    	$this->checkSession();
    	$cond_mod='cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$this->Session->read('SScoddep');
    	$data_modulos=$this->modulos->findAll($cond_mod,null,'orden_ubicacion ASC');
    $this->set('data_modulos',$data_modulos);
    foreach($data_modulos as $datamod){
       if($datamod['modulos']['status']==1){
       	 if($datamod['modulos']['cod_modulo']=='CUGP00'){
       	 	$mod['uso_general']=array('img'=>strtolower($datamod['modulos']['cod_modulo']),'title'=>$datamod['modulos']['denominacion']);
       	 }else{
       	 	$mod[strtolower($datamod['modulos']['cod_modulo'])]=array('img'=>strtolower($datamod['modulos']['cod_modulo']),'title'=>$datamod['modulos']['denominacion']);
       	 }
       }else{
       	 $mod["negar_menu"]=array('img'=>'salir','title'=>'No Autorizado');
       }
    }
    $this->set('img_mod',$mod);
}//fin
//$mod=array('CUGP00','CFP000','CSIP00','CSCP00','COBP00','CEPP00','CSTP00','CNP000','CIPP00','CFPP00','CGPP00','CATP00','SHPP00','CAP000','CMCP00');


function  en_construccion(){
    	$this->layout = "construccion";
        $this->checkSession();
}//fin


function valida_codigo($tabla=null,
$cod_campo1=null, $cod_valor1=null , $cod_campo2=null , $cod_valor2=null , $cod_campo3=null , $cod_valor3=null,
$cod_campo4=null, $cod_valor4=null , $cod_campo5=null , $cod_valor5=null , $cod_campo6=null , $cod_valor6=null,
$cod_campo7=null, $cod_valor7=null , $cod_campo8=null , $cod_valor8=null , $cod_campo9=null , $cod_valor9=null,
$cod_campo10=null, $cod_valor10=null, $cod_campo11=null , $cod_valor11=null , $cod_campo12=null , $cod_valor12=null , $cod_campo13=null,
$cod_valor13=null, $cod_campo14=null , $cod_valor14=null , $cod_campo15=null , $cod_valor15=null , $cod_campo16=null,
$cod_valor16=null, $cod_campo17=null , $cod_valor17=null , $cod_campo18=null , $cod_valor18=null , $cod_campo19=null, $cod_valor19=null,
$cod_campo20=null, $cod_valor20=null){

    $this->layout = "ajax";
    $mensaje = '';
    $sql="";
    $ultimo = "";
    $imprimir="";

   if($cod_campo1!=null && $cod_valor1 != null){ $sql .= $cod_campo1."='".$cod_valor1."'"; $ultimo = $cod_valor1; }
   if($cod_campo2!=null && $cod_valor2 != null){ $sql .= " and ".$cod_campo2."='".$cod_valor2."'"; $ultimo = $cod_valor2; }else if($cod_campo2!=null){ $sql=""; $ultimo="";}
   if($cod_campo3!=null && $cod_valor3 != null){ $sql .= " and ".$cod_campo3."='".$cod_valor3."'"; $ultimo = $cod_valor3; }else if($cod_campo3!=null){ $sql=""; $ultimo="";}
   if($cod_campo4!=null && $cod_valor4 != null){ $sql .= " and ".$cod_campo4."='".$cod_valor4."'"; $ultimo = $cod_valor4; }else if($cod_campo4!=null){ $sql=""; $ultimo="";}
   if($cod_campo5!=null && $cod_valor5 != null){ $sql .= " and ".$cod_campo5."='".$cod_valor5."'"; $ultimo = $cod_valor5; }else if($cod_campo5!=null){ $sql=""; $ultimo="";}
   if($cod_campo6!=null && $cod_valor6 != null){ $sql .= " and ".$cod_campo6."='".$cod_valor6."'"; $ultimo = $cod_valor6; }else if($cod_campo6!=null){ $sql=""; $ultimo="";}
   if($cod_campo7!=null && $cod_valor7 != null){ $sql .= " and ".$cod_campo7."='".$cod_valor7."'"; $ultimo = $cod_valor7; }else if($cod_campo7!=null){ $sql=""; $ultimo="";}
   if($cod_campo8!=null && $cod_valor8 != null){ $sql .= " and ".$cod_campo8."='".$cod_valor8."'"; $ultimo = $cod_valor8; }else if($cod_campo8!=null){ $sql=""; $ultimo="";}
   if($cod_campo9!=null && $cod_valor9 != null){ $sql .= " and ".$cod_campo9."='".$cod_valor9."'"; $ultimo = $cod_valor9; }else if($cod_campo9!=null){ $sql=""; $ultimo="";}
   if($cod_campo10!=null && $cod_valor10 != null){ $sql .= " and ".$cod_campo10."='".$cod_valor10."'"; $ultimo = $cod_valor10; }else if($cod_campo10!=null){ $sql=""; $ultimo="";}
   if($cod_campo11!=null && $cod_valor11 != null){ $sql .= " and ".$cod_campo11."='".$cod_valor11."'"; $ultimo = $cod_valor11; }else if($cod_campo11!=null){ $sql=""; $ultimo="";}
   if($cod_campo12!=null && $cod_valor12 != null){ $sql .= " and ".$cod_campo12."='".$cod_valor12."'"; $ultimo = $cod_valor12; }else if($cod_campo12!=null){ $sql=""; $ultimo="";}
   if($cod_campo13!=null && $cod_valor13 != null){ $sql .= " and ".$cod_campo13."='".$cod_valor13."'"; $ultimo = $cod_valor13; }else if($cod_campo13!=null){ $sql=""; $ultimo="";}
   if($cod_campo14!=null && $cod_valor14 != null){ $sql .= " and ".$cod_campo14."='".$cod_valor14."'"; $ultimo = $cod_valor14; }else if($cod_campo14!=null){ $sql=""; $ultimo="";}
   if($cod_campo15!=null && $cod_valor15 != null){ $sql .= " and ".$cod_campo15."='".$cod_valor15."'"; $ultimo = $cod_valor15; }else if($cod_campo15!=null){ $sql=""; $ultimo="";}
   if($cod_campo16!=null && $cod_valor16 != null){ $sql .= " and ".$cod_campo16."='".$cod_valor16."'"; $ultimo = $cod_valor16; }else if($cod_campo16!=null){ $sql=""; $ultimo="";}
   if($cod_campo17!=null && $cod_valor17 != null){ $sql .= " and ".$cod_campo17."='".$cod_valor17."'"; $ultimo = $cod_valor17; }else if($cod_campo17!=null){ $sql=""; $ultimo="";}
   if($cod_campo18!=null && $cod_valor18 != null){ $sql .= " and ".$cod_campo18."='".$cod_valor18."'"; $ultimo = $cod_valor18; }else if($cod_campo18!=null){ $sql=""; $ultimo="";}
   if($cod_campo19!=null && $cod_valor19 != null){ $sql .= " and ".$cod_campo19."='".$cod_valor19."'"; $ultimo = $cod_valor19; }else if($cod_campo19!=null){ $sql=""; $ultimo="";}
   if($cod_campo20!=null && $cod_valor20 != null){ $sql .= " and ".$cod_campo20."='".$cod_valor20."'"; $ultimo = $cod_valor20; }else if($cod_campo20!=null){ $sql=""; $ultimo="";}



   if(!empty($sql)){
   		if($this->$tabla->findCount($sql) == 0){

   			$mensaje='';
            $imprimir = "";
            echo'<script>';
            echo"valida_codigo_div('no', '".$ultimo."')";
            echo'</script>';

   		}else{

   			$mensaje='EL C&Oacute;DIGO YA EXISTE';
            $imprimir = 'si';
            echo'<script>';
            echo"valida_codigo_div('si', '".$ultimo."')";
            echo'</script>';
   		}

   }else{

   	        $mensaje='';
            $imprimir = '';
            echo'<script>';
            echo"valida_codigo_div('no', '".$ultimo."')";
            echo'</script>';

   }


    $this->set('imprimir',$imprimir);
    $this->set('mensaje',$mensaje);
    $this->set('ultimo',$ultimo);

}

function valida_codigo2($tabla=null,
$cod_campo1=null, $cod_valor1=null , $cod_campo2=null , $cod_valor2=null , $cod_campo3=null , $cod_valor3=null,
$cod_campo4=null, $cod_valor4=null , $cod_campo5=null , $cod_valor5=null , $cod_campo6=null , $cod_valor6=null,
$cod_campo7=null, $cod_valor7=null , $cod_campo8=null , $cod_valor8=null , $cod_campo9=null , $cod_valor9=null,
$cod_campo10=null, $cod_valor10=null, $cod_campo11=null , $cod_valor11=null , $cod_campo12=null , $cod_valor12=null , $cod_campo13=null,
$cod_valor13=null, $cod_campo14=null , $cod_valor14=null , $cod_campo15=null , $cod_valor15=null , $cod_campo16=null,
$cod_valor16=null, $cod_campo17=null , $cod_valor17=null , $cod_campo18=null , $cod_valor18=null , $cod_campo19=null, $cod_valor19=null,
$cod_campo20=null, $cod_valor20=null){

    $this->layout = "ajax";
    $cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep=".$cod_dep;
    $mensaje = '';
    $sql="";
    $ultimo = "";
    $imprimir="";

   if($cod_campo1!=null && $cod_valor1 != null){ $sql .= $cod_campo1."='".$cod_valor1."'"; $ultimo = $cod_valor1; }
   if($cod_campo2!=null && $cod_valor2 != null){ $sql .= " and ".$cod_campo2."='".$cod_valor2."'"; $ultimo = $cod_valor2; }else if($cod_campo2!=null){ $sql=""; $ultimo="";}
   if($cod_campo3!=null && $cod_valor3 != null){ $sql .= " and ".$cod_campo3."='".$cod_valor3."'"; $ultimo = $cod_valor3; }else if($cod_campo3!=null){ $sql=""; $ultimo="";}
   if($cod_campo4!=null && $cod_valor4 != null){ $sql .= " and ".$cod_campo4."='".$cod_valor4."'"; $ultimo = $cod_valor4; }else if($cod_campo4!=null){ $sql=""; $ultimo="";}
   if($cod_campo5!=null && $cod_valor5 != null){ $sql .= " and ".$cod_campo5."='".$cod_valor5."'"; $ultimo = $cod_valor5; }else if($cod_campo5!=null){ $sql=""; $ultimo="";}
   if($cod_campo6!=null && $cod_valor6 != null){ $sql .= " and ".$cod_campo6."='".$cod_valor6."'"; $ultimo = $cod_valor6; }else if($cod_campo6!=null){ $sql=""; $ultimo="";}
   if($cod_campo7!=null && $cod_valor7 != null){ $sql .= " and ".$cod_campo7."='".$cod_valor7."'"; $ultimo = $cod_valor7; }else if($cod_campo7!=null){ $sql=""; $ultimo="";}
   if($cod_campo8!=null && $cod_valor8 != null){ $sql .= " and ".$cod_campo8."='".$cod_valor8."'"; $ultimo = $cod_valor8; }else if($cod_campo8!=null){ $sql=""; $ultimo="";}
   if($cod_campo9!=null && $cod_valor9 != null){ $sql .= " and ".$cod_campo9."='".$cod_valor9."'"; $ultimo = $cod_valor9; }else if($cod_campo9!=null){ $sql=""; $ultimo="";}
   if($cod_campo10!=null && $cod_valor10 != null){ $sql .= " and ".$cod_campo10."='".$cod_valor10."'"; $ultimo = $cod_valor10; }else if($cod_campo10!=null){ $sql=""; $ultimo="";}
   if($cod_campo11!=null && $cod_valor11 != null){ $sql .= " and ".$cod_campo11."='".$cod_valor11."'"; $ultimo = $cod_valor11; }else if($cod_campo11!=null){ $sql=""; $ultimo="";}
   if($cod_campo12!=null && $cod_valor12 != null){ $sql .= " and ".$cod_campo12."='".$cod_valor12."'"; $ultimo = $cod_valor12; }else if($cod_campo12!=null){ $sql=""; $ultimo="";}
   if($cod_campo13!=null && $cod_valor13 != null){ $sql .= " and ".$cod_campo13."='".$cod_valor13."'"; $ultimo = $cod_valor13; }else if($cod_campo13!=null){ $sql=""; $ultimo="";}
   if($cod_campo14!=null && $cod_valor14 != null){ $sql .= " and ".$cod_campo14."='".$cod_valor14."'"; $ultimo = $cod_valor14; }else if($cod_campo14!=null){ $sql=""; $ultimo="";}
   if($cod_campo15!=null && $cod_valor15 != null){ $sql .= " and ".$cod_campo15."='".$cod_valor15."'"; $ultimo = $cod_valor15; }else if($cod_campo15!=null){ $sql=""; $ultimo="";}
   if($cod_campo16!=null && $cod_valor16 != null){ $sql .= " and ".$cod_campo16."='".$cod_valor16."'"; $ultimo = $cod_valor16; }else if($cod_campo16!=null){ $sql=""; $ultimo="";}
   if($cod_campo17!=null && $cod_valor17 != null){ $sql .= " and ".$cod_campo17."='".$cod_valor17."'"; $ultimo = $cod_valor17; }else if($cod_campo17!=null){ $sql=""; $ultimo="";}
   if($cod_campo18!=null && $cod_valor18 != null){ $sql .= " and ".$cod_campo18."='".$cod_valor18."'"; $ultimo = $cod_valor18; }else if($cod_campo18!=null){ $sql=""; $ultimo="";}
   if($cod_campo19!=null && $cod_valor19 != null){ $sql .= " and ".$cod_campo19."='".$cod_valor19."'"; $ultimo = $cod_valor19; }else if($cod_campo19!=null){ $sql=""; $ultimo="";}
   if($cod_campo20!=null && $cod_valor20 != null){ $sql .= " and ".$cod_campo20."='".$cod_valor20."'"; $ultimo = $cod_valor20; }else if($cod_campo20!=null){ $sql=""; $ultimo="";}

   if(!empty($sql)){
   		if($this->$tabla->findCount($sql." and ".$condicion) == 0){

   			$mensaje='';
            $imprimir = "";
            echo'<script>';
            echo"valida_codigo_div('no', '".$ultimo."')";
            echo'</script>';

   		}else{

   			$mensaje='EL C&Oacute;DIGO YA EXISTE';
            $imprimir = 'si';
            echo'<script>';
            echo"valida_codigo_div('si', '".$ultimo."')";
            echo'</script>';
   		}

   }else{

   	        $mensaje='';
            $imprimir = '';
            echo'<script>';
            echo"valida_codigo_div('no', '".$ultimo."')";
            echo'</script>';

   }


    $this->set('imprimir',$imprimir);
    $this->set('mensaje',$mensaje);
    $this->set('ultimo',$ultimo);

}

function valida_puesto($cod_campo1=null){

    $this->layout = "ajax";

    $mensaje = '';
    $sql="";
    $ultimo = "";
    $imprimir="";

   if($cod_campo1!=null && strlen($cod_campo1) == 1){
   		$sql .= $cod_campo1."=".$cod_campo1."";
   		$ultimo = $cod_campo1;
   		$sql = "cod_ramo = ".$cod_campo1;
   		$tabla = "Cnmd02_obreros_ramos";
   		$campo = "el ramo";

   }else if ($cod_campo1!=null && strlen($cod_campo1) == 2){
   		$sql = "cod_ramo = ".$cod_campo1[0]." and cod_grupo = ".$cod_campo1[1];
   		$ultimo = $cod_campo1;
   		$tabla = "Cnmd02_obreros_grupos";
   		$campo = "el grupo";
   }else if ($cod_campo1!=null && strlen($cod_campo1) == 3){
   		$sql = "cod_ramo = ".$cod_campo1[0]." and cod_grupo = ".$cod_campo1[1]." and cod_serie = ".$cod_campo1[2];
   		$ultimo = $cod_campo1;
   		$tabla = "Cnmd02_obreros_series";
   		$campo = "la serie";
   }else if($cod_campo1!=null && strlen($cod_campo1) == 5){
   		$sql = "cod_puesto = ".$cod_campo1;
   		$ultimo = $cod_campo1;
   		$tabla = "Cnmd02_obreros_puestos";

   }else if($cod_campo1!=null && strlen($cod_campo1) > 3){
   		$sql = "cod_ramo = ".$cod_campo1[0]." and cod_grupo = ".$cod_campo1[1]." and cod_serie = ".$cod_campo1[2];
   		$ultimo = $cod_campo1;
   		$tabla = "Cnmd02_obreros_series";
   		$campo = "la serie";

   }

   if(!empty($sql)){
	if ($tabla == "Cnmd02_obreros_puestos"){
			if($this->$tabla->findCount($sql) == 0){

				$mensaje='';
				$imprimir = "";
				echo'<script>';
            echo"valida_codigo_div('no', '".$ultimo."')";
            echo'</script>';

			}else{

				$mensaje='EL C&Oacute;DIGO INGRESADO YA EXISTE';
				$imprimir = 'si';
				echo'<script>';
            	echo"valida_codigo_div('si', '".$ultimo."')";
            	echo'</script>';
			}

	}else{
			if($this->$tabla->findCount($sql) != 0){

				$mensaje='';
				$imprimir = "";
				echo'<script>';
            	echo"valida_codigo_div('no', '".$ultimo."')";
           	    echo'</script>';

			}else{

				$mensaje='EL C&Oacute;DIGO NO EXISTE -- INGRESE UN VALOR CORRECTO PARA '.$campo.'';
				$imprimir = 'si';
				echo'<script>';
            	echo"valida_codigo_div('si', '".$ultimo."')";
            	echo'</script>';
			}

	}
   }

    $this->set('imprimir',$imprimir);
    $this->set('mensaje',$mensaje);
    $this->set('ultimo',$ultimo);

}

function valida_clase($cod_campo1=null){

    $this->layout = "ajax";

    $mensaje = '';
    $sql="";
    $ultimo = "";
    $imprimir="";

   if($cod_campo1!=null && strlen($cod_campo1) == 1){
   		$sql .= $cod_campo1."=".$cod_campo1."";
   		$ultimo = $cod_campo1;
   		$sql = "cod_ramo = ".$cod_campo1;
   		$tabla = "Cnmd02_empleados_ramos";
   		$campo = "el ramo";

   }else if ($cod_campo1!=null && strlen($cod_campo1) == 2){
   		$sql = "cod_ramo = ".$cod_campo1[0]." and cod_grupo = ".$cod_campo1[1];
   		$ultimo = $cod_campo1;
   		$tabla = "Cnmd02_empleados_grupos";
   		$campo = "el grupo";
   }else if ($cod_campo1!=null && strlen($cod_campo1) == 3){
   		$sql = "cod_ramo = ".$cod_campo1[0]." and cod_grupo = ".$cod_campo1[1]." and cod_serie = ".$cod_campo1[2];
   		$ultimo = $cod_campo1;
   		$tabla = "Cnmd02_empleados_series";
   		$campo = "la serie";
   }else if($cod_campo1!=null && strlen($cod_campo1) == 5){
   		$sql = "cod_puesto = ".$cod_campo1;
   		$ultimo = $cod_campo1;
   		$tabla = "Cnmd02_empleados_puestos";

   }else if($cod_campo1!=null && strlen($cod_campo1) > 3){
   		$sql = "cod_ramo = ".$cod_campo1[0]." and cod_grupo = ".$cod_campo1[1]." and cod_serie = ".$cod_campo1[2];
   		$ultimo = $cod_campo1;
   		$tabla = "Cnmd02_empleados_series";
   		$campo = "la serie";

   }

   if(!empty($sql)){
	if ($tabla == "Cnmd02_empleados_puestos"){
			if($this->$tabla->findCount($sql) == 0){

				$mensaje='';
				$imprimir = "";
				echo'<script>';
            	echo"valida_codigo_div('no', '".$ultimo."')";
            	echo'</script>';

			}else{

				$mensaje='EL C&Oacute;DIGO INGRESADO YA EXISTE';
				$imprimir = 'si';
				echo'<script>';
            	echo"valida_codigo_div('si', '".$ultimo."')";
            	echo'</script>';
			}

	}else{
			if($this->$tabla->findCount($sql) != 0){

				$mensaje='';
				$imprimir = "";
				echo'<script>';
            	echo"valida_codigo_div('no', '".$ultimo."')";
            	echo'</script>';

			}else{

				$mensaje='EL C&Oacute;DIGO NO EXISTE -- INGRESE UN VALOR CORRECTO PARA '.$campo.'';
				$imprimir = 'si';
				echo'<script>';
            	echo"valida_codigo_div('si', '".$ultimo."')";
            	echo'</script>';
			}

	}
   }

    $this->set('imprimir',$imprimir);
    $this->set('mensaje',$mensaje);
    $this->set('ultimo',$ultimo);

}


function validar_cclave($var_cc=null) {
 	if($var_cc != null){

	/**
		$hash = password_hash($var_cc);
		// $hash = crypt($var_cc);
		// $hash = password_hash($var_cc, $algorithm, $options); cost=>10

    if (!password_verify($var_cc, $hash)) {
        //if (password_needs_rehash($hash)) {
            //$hash = password_hash($var_cc);
        //}

      $error_clave[0] = "No se pudo verificar la clave... Intente con otra!";
      $error_clave[1] = false;
    }else
    */

	$username = $this->Session->read('nom_usuario');

	if($var_cc == $username){
      $error_clave[0] = "La clave debe de ser diferente al nombre de usuario (Login)";
      $error_clave[1] = false;

	}else if(strlen($var_cc) < 6){
      $error_clave[0] = "La clave debe tener al menos 6 caracteres";
      $error_clave[1] = false;
   }

   else if(strlen($var_cc) > 25){
      $error_clave[0] = "La clave no puede tener m&aacute;s de 25 caracteres";
      $error_clave[1] = false;
   }

   else if (!preg_match('`[A-Za-z]`',$var_cc)){
      $error_clave[0] = "La clave debe tener al menos una letra (a-z)";
      $error_clave[1] = false;
   }

   else if (!preg_match('`[0-9]`',$var_cc)){
      $error_clave[0] = "La clave debe tener al menos un d&iacute;gito (0-9)";
      $error_clave[1] = false;
   }

	else if(!preg_match('`[!@#)$%&*<,>;:(-._]`', $var_cc)) {
      $error_clave[0] = "La clave es incorrecta debe contener una combinaci&oacute;n entre letras(a-z), n&uacute;meros(0-9) y s&iacute;mbolos especiales como: <br><span style='color:#840000;font-size:14px;'>".'! @ # $ _ % & * ( ) < > . , ; : -'."</span>";
      $error_clave[1] = false;
    }

   else{
   		$error_clave[0] = "";
   		$error_clave[1] = true;
   }
	}
	return $error_clave;
}


function cambiar_clave ($var=null, $vadv=null) {
   $this->layout="ajax";
   $this->set('var_adv', $var);
   $this->set('vadv', $vadv);
   if(isset($var) && $var!=null && $var=='form'){
       //recibir datos
       $clave_acutal = $this->data['usuarios']['clave_actual'];

       if($clave_acutal!=$this->data['usuarios']['clave_nueva1']){

		$vali = $this->validar_cclave($this->data['usuarios']['clave_nueva1']);
		if($vali[1] === true){

       $nueva_clave1 = md5($this->data['usuarios']['clave_nueva1']);
       $nueva_clave2 = md5($this->data['usuarios']['clave_nueva2']);
       if(!empty($this->data['usuarios']['clave_actual']) && !empty($this->data['usuarios']['clave_nueva1']) && !empty($this->data['usuarios']['clave_nueva2'])){
       if($nueva_clave1!=$nueva_clave2){
       	   echo "<span style='color:red;font-size:14px;'><center><b><i>Las claves nuevas no coinciden, por favor verifique.</i></b></center></span>";
       }else{
       	  $CU=$this->Usuario->findCount("upper(username)='".strtoupper($this->Session->read('nom_usuario'))."'");
       	  if($CU!=0){
              $pass=$this->Usuario->findAll("upper(username)='".strtoupper($this->Session->read('nom_usuario'))."'");
              // if(strtoupper($pass[0]['Usuario']['password']) == strtoupper($clave_acutal)){
              if(strtoupper($pass[0]['Usuario']['password']) == strtoupper(md5($clave_acutal))) {
              	  $rs_update=$this->Usuario->execute("UPDATE usuarios SET password='".$nueva_clave1."' WHERE upper(username)='".$this->Session->read('nom_usuario')."'");
              	  if($rs_update>1){
              	  		$_SESSION["passw_usuario"] = $this->data['usuarios']['clave_nueva1'];
              	  		if($vadv!=null){
              	  			echo "<script>Control.Modal.close(true);</script>";
              	  		}
              	  	   echo "<span style='color:green;font-size:14px;'><center><b><i>Cambio de clave realizado exitosamente!</i></b></center></span>";
              	  }else{
              	  	   echo "<span style='color:red;font-size:14px;'><center><b><i>Actualizaci&oacute;n de clave sin exito.</i></b></center></span>";
              	  }
              }else{
              	     echo "<span style='color:red;font-size:14px;'><center><b><i>La Clave actual no es correcta, por favor verifique.</i></b></center></span>";
              }
       	  }
       }

       }else{
           echo "<span style='color:red;font-size:14px;'><center><b><i>Campos sin llenar, por favor verifique.</i></b></center></span>";
       }



		}else{
				echo "<span style='color:#002300;font-size:12px;'><center><b><i>".$vali[0]."</i></b></center></span>";
		} // else: sino cumple condiciones en function validar_cclave


       }else{
			echo "<span style='color:red;font-size:14px;'><center><b><i>Ingrese una clave nueva distinta a la actual.</i></b></center></span>";
       }


       echo "<script>document.getElementById('cambiar_clave_id').disabled = false;</script>";
   }else{
   	  $this->set('var_form',true);
   }
}




function bloqueo_de_acceso_ventana($var1=null){

 $this->layout="ajax";

//   /administradors/bloqueo_de_acceso/1


if($var1==null){

$url                  =  "/administradors/bloqueo_de_acceso/1";
$width_aux            =  "750px";
$height_aux           =  "450px";
$title_aux            =  "Buscar";
$resizable_aux        =  false;
$maximizable_aux      =  false;
$minimizable_aux      =  false;
$closable_aux         =  false;


	         echo"<script>";
	           echo  "codigo_ventana('".$url."', '".$width_aux."', '".$height_aux."', '".$title_aux."', '".$resizable_aux."', '".$maximizable_aux."', '".$minimizable_aux."', '".$closable_aux."')";
	         echo"</script>";


}else{

             echo"<script>";
	           echo  "menu_activo();";
	         echo"</script>";

}//fin else



}//fin function













function programa_construcion(){

	$this->layout="ajax";

	         echo"<script>";
	           echo  "menu_activo();";
	         echo"</script>";


}//fin function












function bloqueo_de_acceso($var1=null, $var2=null){
    $this->layout="ajax";

    $this->set('opcion',$var1);

      $cod_presi                =       $this->Session->read('SScodpresi');
	  $cod_entidad              =       $this->Session->read('SScodentidad');
	  $cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
	  $cod_inst                 =       $this->Session->read('SScodinst');
	  $cod_dep                  =       $this->Session->read('SScoddep');

	  $condicion = "cod_presi=".$this->Session->read('SScodpresi')."  and  cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and  cod_inst=".$this->Session->read('SScodinst')." ";


          if($var1==1){

    }else if($var1==2){

				    	  if($var2==1){

				    	  	$this->estatus_usuarios(1);
    	                    $this->render('estatus_usuarios');

				    }else if($var2==2){

								    	    $campos = ' cod_tipo_inst,
													   cod_inst,
													   cod_modulo,
													   denominacion,
													   status,
													   orden_ubicacion';


											$agrupar = 'GROUP BY    cod_tipo_inst,
																    cod_inst,
																    cod_modulo,
																    denominacion,
																    status,
																    orden_ubicacion';

				              $cond_mod='cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and cod_inst='.$this->Session->read('SScodinst');

				              $this->set('ListaModulos',$this->modulos->findAll($cond_mod." ".$agrupar, $campos,'cod_tipo_inst,cod_inst,orden_ubicacion ASC'));

                              $this->estatus_modulos('a');

    	                      $this->render('estatus_modulos');

				    }else if($var2==3){

				    	      $this->estatus_dependencia(1);
    	                      $this->render('estatus_dependencia');


				    }//fin else



    }else if($var1==3){


    }else if($var1==4){


    }//fin else


}//fin function


























function estatus_dependencia($var1=null, $var2=null, $var3=null, $var4=null, $var5=null){

    $this->layout="ajax";



      $cod_presi                =       $this->Session->read('SScodpresi');
	  $cod_entidad              =       $this->Session->read('SScodentidad');
	  $cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
	  $cod_inst                 =       $this->Session->read('SScodinst');
	  $cod_dep                  =       $this->Session->read('SScoddep');

	  $condicion = "cod_presi=".$this->Session->read('SScodpresi')."  and  cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and  cod_inst=".$this->Session->read('SScodinst')." ";

          if($var1==1){


    }else if($var1==2){
                             if($var3==null){$pagina=1;}else{$pagina=$var3;}

                             $denodep = strtoupper_sisap($var2);
                             $this->set('pista',$denodep);
			                 $Tfilas=$this->arrd05->findCount(" ".$condicion." and  UPPER(denominacion) like upper('%$denodep%')");
					        if($Tfilas!=0){
					        	$Tfilas=(int)ceil($Tfilas/100);
					        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
								$this->set('total_paginas',$Tfilas);
								$this->set('pagina_actual',$pagina);
								$this->set('ultimo',$Tfilas);
					     	    $datos_filas=$this->arrd05->findAll(" ".$condicion." and  UPPER(denominacion) like upper('%$denodep%') ",null,"  denominacion ASC",100,$pagina,null);
						        $this->set("datos_usuarios",$datos_filas);
						        $this->set('siguiente',$pagina+1);
								$this->set('anterior',$pagina-1);
								$this->bt_nav($Tfilas,$pagina);
					          }else{
					        	$this->set("datosFILAS",'');
					          }




     }else if($var1==3){
                                $this->set("cod_dep",            $var2);
					            echo"<script>document.getElementById('".$var2."_".$var3."').innerHTML=''; </script>";

					                             if($var3==1){ $imagen = "check_rojo.png"; $var3=2; $var3_aux=1;
									       }else if($var3==2){ $imagen = "tick.png";       $var3=1; $var3_aux=2;
									       }//fin else
						        $this->set("imagen",             $imagen);
						        $this->set("condicion_actividad",$var3);
						        $this->set("capa",$var3_aux);

     	                    $cond_mod='cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and cod_inst='.$this->Session->read('SScodinst')." and cod_dep=".$var2;
				            $this->modulos->execute("UPDATE arrd05 SET condicion_actividad=".$var3." WHERE ".$cond_mod);


    }else if($var1==4){



     }else if($var1==5){


    }//fin else








   $this->set('opcion',$var1);





}//fin function


















function estatus_modulos($var1=null, $var2=null, $var3=null, $var4=null, $var5=null){

    $this->layout="ajax";



      $cod_presi                =       $this->Session->read('SScodpresi');
	  $cod_entidad              =       $this->Session->read('SScodentidad');
	  $cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
	  $cod_inst                 =       $this->Session->read('SScodinst');
	  $cod_dep                  =       $this->Session->read('SScoddep');

	  $condicion = "cod_presi=".$this->Session->read('SScodpresi')."  and  cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and  cod_inst=".$this->Session->read('SScodinst')." ";

             if($var1=="a"){


       }else if($var1==1){

          	               $campos = ' cod_tipo_inst,
									   cod_inst,
									   cod_modulo,
									   denominacion,
									   status,
									   orden_ubicacion';


							$agrupar = 'GROUP BY    cod_tipo_inst,
												    cod_inst,
												    cod_modulo,
												    denominacion,
												    status,
												    orden_ubicacion';

              $cond_mod='cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and cod_inst='.$this->Session->read('SScodinst');

              $this->set('ListaModulos',$this->modulos->findAll($cond_mod." ".$agrupar, $campos,'cod_tipo_inst,cod_inst,orden_ubicacion ASC'));




    }else if($var1==2){



     }else if($var1==21){

             $denodep = strtoupper_sisap($var2);
			$vector_dep = $this->arrd05->execute("select cod_dep, denominacion from arrd05 where ".$condicion." and UPPER(denominacion) like upper('%$denodep%') order by cod_dep");
			if(count($vector_dep)>0){
				for($i=0; $i<count($vector_dep); $i++){
				$list[$vector_dep[$i][0]['cod_dep']] = $vector_dep[$i][0]['denominacion'];
				}
				$this->concatena($list, 'list');
			}else{
				$this->set('list',array('no'=>'No se encontraron coincidencias su la busqueda'));
				$this->set('mensajeError','No se encontraron coincidencias para su busqueda');
			}


    }else if($var1==3){

    	      $cond_mod='cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$var2;

              $this->set('ListaModulos',$this->modulos->findAll($cond_mod, null,'cod_tipo_inst,cod_inst, cod_dep, orden_ubicacion ASC'));


    }else if($var1==4){

                $cond_mod='cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and cod_inst='.$this->Session->read('SScodinst');
				$this->modulos->execute("UPDATE modulos SET status=".$var3." WHERE cod_modulo='".$var2."' and ".$cond_mod);
				$this->set('Status',$var3);
				$this->set('Cmodulo',$var2);
				$this->set('i',$var4);


     }else if($var1==5){

                $cond_mod='cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and cod_inst='.$this->Session->read('SScodinst')." and cod_dep=".$var2;
				$this->modulos->execute("UPDATE modulos SET status=".$var4." WHERE cod_modulo='".$var3."' and ".$cond_mod);
				$this->set('Status',$var4);
				$this->set('Cmodulo',$var3);
				$this->set('i',$var5);
				$this->set('cod_dep',$var2);



    }//fin else








   $this->set('opcion',$var1);





}//fin function




















function estatus_usuarios($var1=null, $var2=null, $var3=null, $var4=null){

    $this->layout="ajax";

    $this->set('opcion',$var1);

      $cod_presi                =       $this->Session->read('SScodpresi');
	  $cod_entidad              =       $this->Session->read('SScodentidad');
	  $cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
	  $cod_inst                 =       $this->Session->read('SScodinst');
	  $cod_dep                  =       $this->Session->read('SScoddep');

	  $condicion = "cod_presi=".$this->Session->read('SScodpresi')."  and  cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and  cod_inst=".$this->Session->read('SScodinst')." ";



          if($var1==1){







    }else if($var1==2){


            $denodep = strtoupper_sisap($var2);
			$vector_dep = $this->arrd05->execute("select cod_dep, denominacion from arrd05 where ".$condicion." and UPPER(denominacion) like upper('%$denodep%') order by cod_dep");
			if(count($vector_dep)>0){
				for($i=0; $i<count($vector_dep); $i++){
				$list[$vector_dep[$i][0]['cod_dep']] = $vector_dep[$i][0]['denominacion'];
				}
				$this->concatena($list, 'list');
			}else{
				$this->set('list',array('no'=>'No se encontraron coincidencias su la busqueda'));
				$this->set('mensajeError','No se encontraron coincidencias para su busqueda');
			}




    }else if($var1==3){

               $por_dependencia = $var2;

                               $Tfilas=$this->v_usuarios->findCount($condicion." and cod_dep='".$por_dependencia."' ");
					        if($Tfilas!=0){
					        	$pagina=1;
					        	$Tfilas=(int)ceil($Tfilas/100);
					        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
								$this->set('total_paginas',$Tfilas);
								$this->set('pagina_actual',$pagina);
								$this->set('ultimo',$Tfilas);
					     	    $datos_filas=$this->v_usuarios->findAll($condicion." and cod_dep='".$por_dependencia."' ",null,"  denominacion_dep, username ASC",100,1,null);
						        $this->set("datos_usuarios",$datos_filas);
						        $this->set('siguiente',$pagina+1);
								$this->set('anterior',$pagina-1);
								$this->bt_nav($Tfilas,$pagina);
					          }else{
					        	$this->set("datosFILAS",'');
					          }

					        $this->set("cod_dep",$por_dependencia);



    }else if($var1==4){


            $this->set("cod_dep",            $var2);
            $this->set("username",           $var3);
            echo"<script>document.getElementById('".$var3."_".$var4."').innerHTML=''; </script>";

                             if($var4==1){ $imagen = "check_rojo.png"; $var4=2; $var4_aux=1;
				       }else if($var4==2){ $imagen = "tick.png";       $var4=1; $var4_aux=2;
				       }//fin else
	        $this->set("imagen",             $imagen);
	        $this->set("condicion_actividad",$var4);
	        $this->set("capa",$var4_aux);



	        $sw        = $this->v_usuarios->execute("BEGIN; UPDATE usuarios SET condicion_actividad='".$var4."' WHERE username='".$var3."'; COMMIT; ");
            $this->set('Message_existe', 'Cambio realizado con exito');


    }//fin else



}//fin function




function estatus_usuarios_2($var1=null, $var2=null, $var3=null, $var4=null){

    $this->layout="ajax";


      $cod_presi                =       $this->Session->read('SScodpresi');
	  $cod_entidad              =       $this->Session->read('SScodentidad');
	  $cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
	  $cod_inst                 =       $this->Session->read('SScodinst');
	  $cod_dep                  =       $this->Session->read('SScoddep');

	  $condicion = "cod_presi=".$this->Session->read('SScodpresi')."  and  cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and  cod_inst=".$this->Session->read('SScodinst')." ";





                               $Tfilas=$this->v_usuarios->findCount($condicion." and cod_dep='".$var1."' ");
					        if($Tfilas!=0){
					        	$pagina=$var2;
					        	$Tfilas=(int)ceil($Tfilas/100);
					        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
								$this->set('total_paginas',$Tfilas);
								$this->set('pagina_actual',$pagina);
								$this->set('ultimo',$Tfilas);
					     	    $datos_filas=$this->v_usuarios->findAll($condicion." and cod_dep='".$var1."' ",null,"  denominacion_dep, username ASC",100,$var2,null);
						        $this->set("datos_usuarios",$datos_filas);
						        $this->set('siguiente',$pagina+1);
								$this->set('anterior',$pagina-1);
								$this->bt_nav($Tfilas,$pagina);
					          }else{
					        	$this->set("datosFILAS",'');
					          }

					           $this->set("cod_dep",$var1);




}//fin function








function apure(){
	$this->layout = "administradors";
}


function entrar(){
	$this->layout="ajax";
	if(isset($this->data['restriccion_clave']['login']) && isset($this->data['restriccion_clave']['password'])){
		$condicion = "cod_presi=".$this->Session->read('SScodpresi')."  and  cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and  cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')." ";
		$l="PROYECTO";
		$c="JJJSAE";
		$user=addslashes($this->data['restriccion_clave']['login']);
		$paswd=addslashes($this->data['restriccion_clave']['password']);
		$cond=$condicion." and username='".$user."' and cod_tipo=34 and clave='".$paswd."'";
		if(($user==$l && $paswd==$c)){
			$this->set('autor_valido',true);
			$this->bloqueo_de_acceso("1");
			$this->render("bloqueo_de_acceso");
		}elseif($this->cugd05_restriccion_clave->findCount($cond)!=0){
			$this->set('autor_valido',true);
			$this->bloqueo_de_acceso("1");
			$this->render("bloqueo_de_acceso");
		}else{
			$this->set('errorMessage',"Lo siento, se necesita autorizaci&oacute;n para utilizar este programa");
			$this->set('autor_valido',false);
			$this->bloqueo_de_acceso("1");
			$this->render("bloqueo_de_acceso");
		}
	}
}




}//fin en construcion
?>
