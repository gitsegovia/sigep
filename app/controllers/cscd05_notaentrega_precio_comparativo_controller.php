<?php

class cscd05notaentregapreciocomparativocontroller extends AppController {

   var $name ='cscd05_notaentrega_precio_comparativo';
   var $uses = array('cscd05_notaentrega_precio_comparativo','ccfd04_cierre_mes');
   var $helpers = array('Html','Ajax','Javascript', 'Sisap');

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

function index($var=null){

	if($var!=null){
		if($var=='si'){// Se muestra la vista del formulario.
			$this->layout="ajax";
			$this->set('var',$var);
			$this->set('ano_vigente', $this->ano_ejecucion()!=null ? $this->ano_ejecucion() : date('Y'));

		}elseif($var=='no'){// Se muestra la vista del reporte.
			$this->set('var',$var);
			$valor_radio = $this->data['cscd05_notaentrega_precio_comparativo']['opcion_filtro'];
			if($valor_radio==1){
				$con_filtro = "";
			}else{
			 if(isset($this->data['cscd05_notaentrega_precio_comparativo']['cod_prod']))
			 	if($this->data['cscd05_notaentrega_precio_comparativo']['cod_prod']!=null){
					$con_filtro = " and codigo_prod_serv='".$this->data['cscd05_notaentrega_precio_comparativo']['cod_prod']."'";
			 	}else{}
			}

	if(isset($con_filtro)){
			ini_set("memory_limit","2024M");
			$this->layout = "pdf";
			 $sql = "select * from v_cscd05_nota_entrega_productos_vision where ".$this->SQLCA_report($this->verifica_SS(5))." and ano_nota_entrega=". $this->data['cscd05_notaentrega_precio_comparativo']['ano_vigente'].$con_filtro.";";
             $datos=$this->cscd05_notaentrega_precio_comparativo->execute($sql);
			 $this->set('datos',$datos);
	}
		}
	}
}


function cargar_select($opc=null,$patron=null,$var=null){

	$this->layout="ajax";

	$this->set('opc',$opc);
	$this->set('patron_c',$patron);
	$this->set('opcion_radio',$var);
	if($var==1){
		echo "<script>document.getElementById('select_cargado').innerHTML='';</script>";
	}
	if($opc==2){
			 $sql = "select codigo_prod_serv, denominacion_del_producto from v_cscd05_nota_entrega_productos_vision WHERE cod_entidad=".$this->Session->read('SScodentidad')." and upper(denominacion_del_producto) like upper('%". $var ."%') GROUP BY codigo_prod_serv, denominacion_del_producto";
             $datos=$this->cscd05_notaentrega_precio_comparativo->execute($sql);
			 $i=0;
			if($datos!=null){
			 foreach($datos as $x){
			 $i++;
			 $x1[]=$x[0]['codigo_prod_serv'];
			 $x2[]=$x[0]['denominacion_del_producto'];
			 $x3=array_combine($x1,$x2);
			 }
			 $this->set('datos',$x3);
			}else{
			 $this->set('datos',array());
			}
	}

}

}//fin class
?>