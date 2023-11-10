<?php
 class Cstp02ActivarCuentasBancariasController extends AppController {

	var $name = 'cstp02_activar_cuentas_bancarias';
	var $uses = array('ccfd04_cierre_mes', 'v_cuentas_bancarias', 'cstd01_sucursales_bancarias','cstd01_entidades_bancarias','cstd02_cuentas_bancarias');
	var $helpers = array('Html', 'Javascript', 'Ajax', 'Sisap', 'Fpdf','Form','Fck');

	function checkSession(){
		if (!$this->Session->check('Usuario')){
			$this->redirect('/salir/');
			exit();
		}else{
			$this->requestAction('/usuarios/actualizar_user');
		}
	}

	function beforeFilter(){
		$this->checkSession();
		if($this->ano_ejecucion()!=""){
			return;
		}else{
			echo "<h3>Por Favor, Registre el Año de Ejecuci&oacute;n de Presupuesto<br>Ingrese al M&oacute;dulo de Uso General</h3>";
			exit();
		}
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
			default: return "NULO";
		}
	}

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
	}

	function index() {
		$this->layout ="ajax";
 		$this->set('lista', $this->cstd01_entidades_bancarias->generateList(null,' cod_entidad_bancaria ASC', null, '{n}.cstd01_entidades_bancarias.cod_entidad_bancaria', '{n}.cstd01_entidades_bancarias.denominacion'));
	}

	function select_cod_entidad_bancaria($cod_ent=null) {
		$this->layout ="ajax";
		$this->set('datos',$this->cstd01_entidades_bancarias->findAll("cod_entidad_bancaria='$cod_ent'"));
		$this->set('lista',$this->cstd01_sucursales_bancarias->generateList("cod_entidad_bancaria='$cod_ent'",' cod_entidad_bancaria, cod_sucursal ASC', null, '{n}.cstd01_sucursales_bancarias.cod_sucursal', '{n}.cstd01_sucursales_bancarias.denominacion'));
		$this->set('cod_ent',$cod_ent);
	}

	function select_cod_sucursal($cod_ent=null, $cod_sucursal=null) {
		$this->layout ="ajax";
		$this->set('datos',$this->cstd01_sucursales_bancarias->findAll("cod_entidad_bancaria='$cod_ent' and cod_sucursal='$cod_sucursal'"));
		$this->concatena($this->cstd02_cuentas_bancarias->generateListTodas($this->SQLCA()."and cod_entidad_bancaria='$cod_ent' and cod_sucursal='$cod_sucursal'",' cod_entidad_bancaria, cod_sucursal, cuenta_bancaria ASC', null, '{n}.cstd02_cuentas_bancarias.cuenta_bancaria', '{n}.cstd02_cuentas_bancarias.concepto_manejo'), 'lista');
		$this->set('cod_ent',$cod_ent);
		$this->set('cod_sucursal',$cod_sucursal);
	}

	function select_cuenta_bancaria($cod_ent=null, $cod_sucursal=null, $cuenta_bancaria=null) {
		$this->layout ="ajax";
		$this->set('datos',$this->cstd02_cuentas_bancarias->findAll($this->SQLCA()." and cod_entidad_bancaria='$cod_ent' and cod_sucursal='$cod_sucursal' and cuenta_bancaria='$cuenta_bancaria'", 'responsable_manejo, status_actividad'));
	}

	function modificar ($pagina=null){
		$this->layout ="ajax";
		$this->set('pagina',$pagina);
	}

	function guardar($pagina=null) {
		$this->layout ="ajax";
		$cod_ent = $this->data['cstp02_activar_cuentas_bancarias']['codigo_entidad_bancaria'];
		$cod_sucursal = $this->data['cstp02_activar_cuentas_bancarias']['codigo_sucursal'];
		$cuenta_bancaria = $this->data['cstp02_activar_cuentas_bancarias']['cuenta_bancaria'];
		$status = $this->data['cstp02_activar_cuentas_bancarias']['status'];
		$sql_update = "UPDATE cstd02_cuentas_bancarias SET status_actividad='$status' WHERE ".$this->SQLCA()." and cod_entidad_bancaria='$cod_ent' and cod_sucursal='$cod_sucursal' and cuenta_bancaria='$cuenta_bancaria'";
		if ($this->cstd02_cuentas_bancarias->execute($sql_update)>0) {
            $this->set('Message_existe','El status de la cuenta fu&eacute; modificado correctamente');
		}else{
        	$this->set('errorMessage','El status de la cuenta no pudo ser modificado');
		}
		if ($pagina!=null) {
        	$this->consultar($pagina);
        	$this->render('consultar');
        }
	}

	function consultar($pagina=null){//echo 'pagina es '.$pagina.' y ano2 es '.$ano2;
		$this->layout = "ajax";

		if(isset($pagina)){
			$Tfilas=$this->v_cuentas_bancarias->findCount($this->SQLCA());
        	if($Tfilas!=0){
	        	$data=$this->v_cuentas_bancarias->findAll($this->SQLCA(), null, "cod_entidad_bancaria, cod_sucursal, cuenta_bancaria ASC", 1, $pagina, null);
	            $this->set('datos',$data);
	          	$this->set('siguiente',$pagina+1);
	          	$this->set('anterior',$pagina-1);
	            $this->bt_nav($Tfilas,$pagina);
	            $this->set('numT',$Tfilas);
				$this->set('numP',$pagina);
				$this->set('ultimo',$Tfilas);
	        }else{
	 	       $this->set('errorMessage', 'No se encontrar&oacute;n datos');
	 	       $this->set('noExiste',true);
	        }
		}else{
			$pagina=1;
			$Tfilas=$this->v_cuentas_bancarias->findCount($this->SQLCA());
	        if($Tfilas!=0){
	        	$data=$this->v_cuentas_bancarias->findAll($this->SQLCA(), null, "cod_entidad_bancaria, cod_sucursal, cuenta_bancaria ASC",1,$pagina,null);
				$this->set('datos',$data);
	          	$this->set('siguiente',$pagina+1);
	          	$this->set('anterior',$pagina-1);
	          	$this->bt_nav($Tfilas,$pagina);
	          	$this->set('numT',$Tfilas);
				$this->set('numP',$pagina);
				$this->set('ultimo',$Tfilas);
	        }else{
		 	       $this->set('mensajeError', 'No se encontrar&oacute;n datos');
		 	       $this->set('noExiste',true);
	        }
		}
	}

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
	}

	function ventana_busqueda(){
		$this->layout="ajax";
	}

	function buscar($var1=null, $var2=null){
		$this->layout="ajax";
		$pista = strtoupper($var1);
		$sql_like = $this->SQLCA()." and (upper(concepto_manejo) LIKE upper('%$pista%') or upper(responsable_manejo) LIKE upper('%$pista%'))";
		if($var2==null){
				$Tfilas = $this->v_cuentas_bancarias->findCount($sql_like);
				if($Tfilas!=0){
					$pagina=1;
					$Tfilas=(int)ceil($Tfilas/100);
					$this->set('pag_cant',$pagina.'/'.$Tfilas);
					$this->set('total_paginas',$Tfilas);
					$this->set('pagina_actual',$pagina);
					$this->set('ultimo',$Tfilas);
					$datos_filas=$this->v_cuentas_bancarias->findAll($sql_like, null, "cod_entidad_bancaria, cod_sucursal, cuenta_bancaria ASC", 100, $pagina, null);
					$this->set('datos',$datos_filas);
					$this->set('siguiente',$pagina+1);
					$this->set('anterior',$pagina-1);
					$this->bt_nav($Tfilas,$pagina);
				}else{
					$this->set("datos",'');
				}
		}else{
				$Tfilas   = $this->v_cuentas_bancarias->findCount($sql_like);
				if($Tfilas!=0){
					$pagina=$var2;
					$Tfilas=(int)ceil($Tfilas/100);
					$this->set('pag_cant',$pagina.'/'.$Tfilas);
					$this->set('total_paginas',$Tfilas);
					$this->set('pagina_actual',$pagina);
					$this->set('ultimo',$Tfilas);
					$datos_filas=$this->v_cuentas_bancarias->findAll($sql_like, null, "cod_entidad_bancaria, cod_sucursal, cuenta_bancaria ASC", 100, $pagina, null);
					$this->set('datos',$datos_filas);
					$this->set('siguiente',$pagina+1);
					$this->set('anterior',$pagina-1);
					$this->bt_nav($Tfilas,$pagina);
				}else{
					$this->set("datos",'');
				}
		}
		$this->set("pista",$var1);
		$this->set("pagina",$var2);
	}

	function ver($cod_entidad_bancaria=null, $cod_sucursal=null, $cuenta_bancaria=null){
		$this->layout="ajax";
		$data=$this->v_cuentas_bancarias->findAll($this->SQLCA()." and cod_entidad_bancaria='$cod_entidad_bancaria' and cod_sucursal='$cod_sucursal' and cuenta_bancaria='$cuenta_bancaria'");
		$this->set('datos',$data);
	}

 }
?>