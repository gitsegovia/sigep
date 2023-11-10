<?php
 class Shp100FirmantePatenteController extends AppController{
    var $name    = "shp100_firmante_patente";
    var $uses    = array('ccfd04_cierre_mes','cugd07_firmas_oficio_anulacion');
					/*array('ccfd04_cierre_mes','cnmd06_profesiones','cugd01_estados','cugd01_municipios','v_cfpd05_denominaciones','v_shd100_declaracion_ingreso',
    					'cugd01_centropoblados','cugd01_parroquias','arrd05','shd001_registro_contribuyentes','v_shd100_patente','shd100_actividades',
    					'shd002_cobradores','v_shd001_contribuyentes_e_impuestos','v_shd900_cobranza_acumulada_ano_mes_dia','v_shd900_cobranza_acumulada_ano_mes',
						'v_shd900_cobranza_acumulada_ano','v_shd900_cobranza_acumulada','shd000_arranque','v_shd100_solicitud_actividades','v_shd100_solicitud','v_shd001_registro_contribuyentes',
						'shd950_solvencia','v_shd200_vehiculos','v_shd900_planillas_deuda_cobro_detalles','shd000_control_actualizacion','cfpd03','v_cfpd03_denominacion_partida','v_shd100_patente_actividades');
					*/
    var $helpers = array('Html', 'Javascript', 'Ajax', 'Sisap');
    
 function checkSession(){
		if (!$this->Session->check('Usuario')){
				$this->redirect('/salir/');
				exit();
		}else{
			$this->requestAction('/usuarios/actualizar_user');
		}
 }//fin checksession

 
 function beforeFilter(){$this->checkSession();}

 
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
 
 
 function index(){
 	$this->layout="ajax";
 	$condicion = $this->SQLCA();
    $count = $this->cugd07_firmas_oficio_anulacion->findCount($condicion.' AND tipo_documento=1001');
    if($count>0){
		$firmas = $this->cugd07_firmas_oficio_anulacion->findAll($condicion.' AND tipo_documento=1001');
		$this->set('nombre_firmante', $firmas[0]['cugd07_firmas_oficio_anulacion']['nombre_primera_firma']);     
		$this->set('cargo_firmante', $firmas[0]['cugd07_firmas_oficio_anulacion']['cargo_primera_firma']);
		$this->set('resolucion', $firmas[0]['cugd07_firmas_oficio_anulacion']['nombre_segunda_firma']);
    }else{
    	$this->set('nombre_firmante', '');     
		$this->set('cargo_firmante', '');
		$this->set('resolucion', '');
    }
 }
 
 
 function guardar(){
 	$this->layout="ajax";
	$cod_presi=$this->verifica_SS(1);
    $cod_entidad=$this->verifica_SS(2);
    $cod_tipo_inst=$this->verifica_SS(3);
    $cod_inst=$this->verifica_SS(4);
	$cod_dep=$this->verifica_SS(5);
 	
 	$nombre_firmante=$this->data['shp100_firmante_patente']['nombre_firmante']; 
    $cargo_firmante=$this->data['shp100_firmante_patente']['cargo_firmante']; 
    $resolucion=$this->data['shp100_firmante_patente']['segun_resolucion'];
    
    if($nombre_firmante=='' || $cargo_firmante==''){
    	$this->set('errorMessage','No debe dejar campos vacios, ingrese al menos el firmante y su cargo por favor.');
    
	}else{   
	    $condicion = $this->SQLCA();
	    $count = $this->cugd07_firmas_oficio_anulacion->findCount($condicion.' AND tipo_documento=1001');
	    if($count>0){
			$sql = "UPDATE cugd07_firmas_oficio_anulacion SET nombre_primera_firma='$nombre_firmante', cargo_primera_firma='$cargo_firmante', nombre_segunda_firma='$resolucion'";     
	    }elseif($count==0){
			$sql = "INSERT INTO cugd07_firmas_oficio_anulacion (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, tipo_documento, nombre_primera_firma, cargo_primera_firma, nombre_segunda_firma, cargo_segunda_firma, nombre_tercera_firma, cargo_tercera_firma) VALUES ($cod_presi, $cod_entidad, $cod_tipo_inst, $cod_inst, $cod_dep, 1001, '$nombre_firmante', '$cargo_firmante', '$resolucion', '.', '.', '.')";
	    }    
	    
	    $result = $this->cugd07_firmas_oficio_anulacion->execute($sql);
	    if($result>0){
	    	$this->set('Message_existe','La informaci&oacute;n para la firma fu&eacute; modificada correctamente.');
	    }else{
	    	$this->set('errorMessage','Lo siento, no pudo ser modificada la informaci&oacute;n para la firma.');
	    }
	}
     
    $this->index();
    $this->render('index');
 }
 
 
 }// fin class    
?>