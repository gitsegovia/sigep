<?php
/*
 * Created on 25/04/2012
 *
 * Herramienta: EasyEclipse.
 *
 * Proyecto: SIGEP
 *
 */

 class Cscp06ActaRecepcionBmController extends AppController {
   var $name = 'cscp06_acta_recepcion_bm';
   var $uses = array('v_cscd05_ordencompra_nota_entrega', 'v_cscd05_ordencompra_nota_entrega_detalles', 'cugd01_estados', 'ccfd04_cierre_mes');
   var $helpers = array('Html','Ajax','Javascript', 'Sisap');

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



function SQLCA($ano=null){ //sql para busqueda de codigos de arranque con y sin año
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
 	$this->layout ="ajax";
 	$this->Session->delete('da_pasoabc');
 	$this->Session->delete('nota_entreg');
	$anos_ordenc = $this->v_cscd05_ordencompra_nota_entrega->generateList($this->SQLCA(), 'ano_orden_compra ASC', null, '{n}.v_cscd05_ordencompra_nota_entrega.ano_orden_compra', '{n}.v_cscd05_ordencompra_nota_entrega.ano_orden_compra');
	if(!empty($anos_ordenc)){
		$this->set('anos_ordenc', $anos_ordenc);
	}else{
		$this->set('anos_ordenc', array());
	}
}


function orden_compra($anio_ord=null){
 	$this->layout ="ajax";
	$num_ordenc = $this->v_cscd05_ordencompra_nota_entrega->generateList($this->SQLCA()." and ano_orden_compra='$anio_ord'", 'numero_orden_compra ASC', null, '{n}.v_cscd05_ordencompra_nota_entrega.numero_orden_compra', '{n}.v_cscd05_ordencompra_nota_entrega.deno_rif');
	if(!empty($num_ordenc)){
		$this->concatenaN($num_ordenc, 'num_ordenc');
		$this->set('anio_or', $anio_ord);
	}else{
		$this->set('num_ordenc', array());
		$this->set('anio_or', '');
	}

	echo '<script>document.getElementById("notas_entrega").innerHTML="<select></select>";</script>';

}


function notas_entrega($anio_orde=null,$n_orden=null){
 	$this->layout ="ajax";
	$num_nota = $this->v_cscd05_ordencompra_nota_entrega->generateList($this->SQLCA()." and ano_orden_compra='$anio_orde' and numero_orden_compra='$n_orden'", 'numero_nota_entrega ASC', null, '{n}.v_cscd05_ordencompra_nota_entrega.numero_nota_entrega', '{n}.v_cscd05_ordencompra_nota_entrega.numero_nota_entrega');
	if(!empty($num_nota)){
		$this->set('num_nota', $num_nota);
	}else{
		$this->set('num_nota', array());
	}
}



function acta_bienes_materiales(){
	set_time_limit(0);
	ini_set("memory_limit","2560M");
	$this->layout = "pdf";

	$datos=$this->data["acta_recepcion_bm"];
	$ano_orden = $datos["ano_orden"];
	$numero_orden = $datos["numero_orden"];
	$nota_entrega = $datos["nota_entrega"];

	$condicion = 'cod_presi='.$this->Session->read('SScodpresi').' and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and cod_inst='.$this->Session->read('SScodinst').'';
	$cod_estado = $this->cugd01_estados->execute("SELECT cod_estado FROM cugd90_municipio_defecto WHERE ".$condicion." LIMIT 1;");
	if($cod_estado!=null)
		$cod_edo = $cod_estado[0][0]["cod_estado"];
	else
		$cod_edo = $this->verifica_SS(2);

	$estado = $this->cugd01_estados->execute("SELECT denominacion FROM cugd01_estados WHERE cod_republica='".$this->verifica_SS(1)."' and cod_estado='".$cod_edo."';");
	$_SESSION['estado'] = $estado[0][0]['denominacion'];
	$institucion = $this->cugd01_estados->execute("SELECT denominacion FROM cugd02_institucion WHERE cod_tipo_institucion='".$this->verifica_SS(3)."' and cod_institucion='".$this->verifica_SS(4)."';");
	$_SESSION['institucion'] = $institucion[0][0]['denominacion'];
	$dependencia = $this->cugd01_estados->execute("SELECT denominacion FROM cugd02_dependencias WHERE cod_tipo_institucion='".$this->verifica_SS(3)."' and cod_institucion='".$this->verifica_SS(4)."' and cod_dependencia='".$this->verifica_SS(5)."';");
	$_SESSION['dependencia'] = $dependencia[0][0]['denominacion'];

if($ano_orden!=null && $numero_orden!=null && $nota_entrega!=null){
	$datos_bm = $this->v_cscd05_ordencompra_nota_entrega_detalles->findAll($this->SQLCA()." and ano_orden_compra='$ano_orden' and numero_orden_compra='$numero_orden' and numero_nota_entrega='$nota_entrega'", 'codigo_prod_serv, descripcion, unidad_medida, cantidad, precio_unitario, costo_total', null);
	if(!empty($datos_bm)){
		$this->set('datos_bm', $datos_bm);
		$this->set('nota_ent', $nota_entrega);
	}else{
		$this->set('datos_bm', array());
	}

	$datos_bm2 = $this->v_cscd05_ordencompra_nota_entrega->findAll($this->SQLCA()." and ano_orden_compra='$ano_orden' and numero_orden_compra='$numero_orden' and numero_nota_entrega='$nota_entrega'", 'rif, deno_rif, ano_orden_compra, fecha_orden_compra, numero_orden_compra, observaciones', null);
	if(!empty($datos_bm2)){
		$this->set('datos_bm2', $datos_bm2);
		$this->set('nota_ent', $nota_entrega);
	}else{
		$this->set('datos_bm', array());
		$this->set('datos_bm2', array());
	}
}else{
	$this->set('datos_bm', array());
	$this->set('datos_bm2', array());
}

}

}

?>