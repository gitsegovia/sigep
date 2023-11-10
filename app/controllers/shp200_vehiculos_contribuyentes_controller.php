<?php
/*
 * Creado el 09/10/2008 a las 12:41:23 AM
 * Herramienta: EasyEclipse.
 * Proyecto: SIGEP
 * Descripcion:
 */

 class Shp200VehiculosContribuyentesController extends AppController{
	var $uses = array('shd002_cobradores','shd200_vehiculos_clasificacion','shd200_vehiculos_marcas','shd200_vehiculos_modelos','shd200_vehiculos_colores','shd200_vehiculos_tipos','shd200_vehiculos_usos','shd200_vehiculos_clases','cnmd06_profesiones','cugd01_vereda','cugd01_vialidad','v_shd001_registro_contribuyentes','shd200_vehiculos_usos', 'ccfd04_cierre_mes','cugd01_republica','cugd01_estados','cugd01_municipios','cugd01_parroquias','cugd01_centropoblados',
					  'shd001_registro_contribuyentes','shd200_vehiculos','v_shd200_vehiculos');
 	var $helpers = array('Html','Ajax','Javascript', 'Sisap');
 	var $name = "shp200_vehiculos_contribuyentes";


 function checkSession(){
 	if (!$this->Session->check('Usuario')){
 		$this->redirect('/salir/');
		exit();
	}else{
		//$this->set('userTable', $this->requestAction('/cnmp03partidas/', array('return')));
		//echo "H".$this->requestAction('/usuarios/actualizar_user',array('return'));
		$this->requestAction('/usuarios/actualizar_user');
	}
 }//fin checkSession



 function beforeFilter(){
 	$this->checkSession();
 }//fin before filter

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

function index($var=null, $var_cont=null){
	$this->layout = "ajax";

	$marca	= $this->shd200_vehiculos_marcas->generateList(null, 'codigo_marca ASC', null, '{n}.shd200_vehiculos_marcas.codigo_marca', '{n}.shd200_vehiculos_marcas.denominacion');
	$modelo = $this->shd200_vehiculos_modelos->generateList(null, 'codigo_modelo ASC', null, '{n}.shd200_vehiculos_modelos.codigo_modelo', '{n}.shd200_vehiculos_modelos.denominacion');
	$color 	= $this->shd200_vehiculos_colores->generateList(null, 'codigo_color ASC', null, '{n}.shd200_vehiculos_colores.codigo_color', '{n}.shd200_vehiculos_colores.denominacion');
	$clase 	= $this->shd200_vehiculos_clases->generateList(null, 'codigo_clase ASC', null, '{n}.shd200_vehiculos_clases.codigo_clase', '{n}.shd200_vehiculos_clases.denominacion');
	$tipo 	= $this->shd200_vehiculos_tipos->generateList(null, 'codigo_tipo ASC', null, '{n}.shd200_vehiculos_tipos.codigo_tipo', '{n}.shd200_vehiculos_tipos.denominacion');
	$uso 	= $this->shd200_vehiculos_usos->generateList(null, 'codigo_uso ASC', null, '{n}.shd200_vehiculos_usos.codigo_uso', '{n}.shd200_vehiculos_usos.denominacion');
	$clasificacion 	= $this->shd200_vehiculos_clasificacion->generateList($this->SQLCA() , 'cod_clasificacion ASC', null, '{n}.shd200_vehiculos_clasificacion.cod_clasificacion', '{n}.shd200_vehiculos_clasificacion.denominacion');
	$rif_cedula 	= $this->shd002_cobradores->generateList($this->SQLCA(), 'nombre_razon ASC', null, '{n}.shd002_cobradores.rif_ci', '{n}.shd002_cobradores.nombre_razon');
	$this->set("rif_cedula", $this->shd002_cobradores->generateList($this->SQLCA(), "nombre_razon ASC", null, '{n}.shd002_cobradores.rif_ci', '{n}.shd002_cobradores.nombre_razon'));
	$this->concatena($marca, 'marca');
	$this->concatena($modelo, 'modelo');
	$this->concatena($color, 'color');
	$this->concatena($clase, 'clase');
	$this->concatena($tipo, 'tipo');
	$this->concatena($uso, 'uso');
	$this->concatena($clasificacion, 'clasificacion');

}//fin index


function nombre_contribuyente(){
	$this->layout="ajax";
	echo "fdasfa".$this->data['shp200_vehiculos_contribuyentes']['select_rif_ci'];

}


function datos_contribuyente(){
	$this->layout="ajax";
	echo "fdasfa".$this->data['shp200_vehiculos_contribuyentes']['select_rif_ci'];

}
















function select($select=null,$var=null) { //select de ubicacion administrativa
	$this->layout = "ajax";
	/**
	 * cod_1 : direccion superior
	 * cod_2 : coordinacion
	 * cod_3 : secretaria
	 * cod_4 : direccion
	 * cod_5 : division
	 * cod_6 : departamento
	 */
if(isset($var) && !empty($var) && $var!=''){
	//$cond ="cod_tipo_institucion=".$this->verifica_SS(3)." and cod_institucion=".$this->verifica_SS(4)." and cod_dependencia=".$this->verifica_SS(5);
	switch($select){
		case 'estado':
			$this->set('SELECT','municipio');
			$this->set('codigo','estado');
			$this->set('seleccion','');
			$this->set('n',2);
			$this->Session->write('cod_1',$var);
			$lista= $this->cugd01_estados->generateList('cod_republica='.$var, 'cod_estado ASC', null, '{n}.cugd01_estados.cod_estado', '{n}.cugd01_estados.denominacion');
			$this->concatena($lista, 'vector');
		break;
		case 'municipio':
			$this->set('SELECT','parroquia');
			$this->set('codigo','municipio');
			$this->set('seleccion','');
			$this->set('n',3);
			$cod_1 = $this->Session->read('cod_1');
			$this->Session->write('cod_2',$var);
			$cond = "cod_republica=".$cod_1." and cod_estado=".$var;
			$lista= $this->cugd01_municipios->generateList($cond, 'cod_municipio ASC', null, '{n}.cugd01_municipios.cod_municipio', '{n}.cugd01_municipios.denominacion');
			$this->concatena($lista, 'vector');
		break;
		case 'parroquia':
			$this->set('SELECT','centro_poblado');
			$this->set('codigo','parroquia');
			$this->set('seleccion','');
			$this->set('n',4);
			$cod_1 =  $this->Session->read('cod_1');
			$cod_2 =  $this->Session->read('cod_2');
			$this->Session->write('cod_3',$var);
			$cond = "cod_republica=".$cod_1." and cod_estado=".$cod_2." and cod_municipio=".$var;
			$lista= $this->cugd01_parroquias->generateList($cond, 'cod_parroquia ASC', null, '{n}.cugd01_parroquias.cod_parroquia', '{n}.cugd01_parroquias.denominacion');
			$this->concatena($lista, 'vector');
		break;
		case 'centro_poblado':
			$cod_1 =  $this->Session->read('cod_1');//republica
			$cod_2 =  $this->Session->read('cod_2');//estado
			$cod_3 =  $this->Session->read('cod_3');//municipio
			$this->Session->write('cod_4',$var);
			$cond = "cod_republica=".$cod_1." and cod_estado=".$cod_2." and cod_municipio=".$cod_3." and cod_parroquia=".$var;
			$this->set('SELECT','catalogo');
			$this->set('codigo','centro_poblado');
			$this->set('seleccion','');
			$this->set('n',5);
			//$this->set('no','no');
			//$this->set('otro','otro');
	       $lista= $this->cugd01_centropoblados->generateList($cond, 'cod_centro ASC', null, '{n}.cugd01_centropoblados.cod_centro', '{n}.cugd01_centropoblados.denominacion');
		   $this->concatena($lista, 'vector');
		break;
	}
	}else{
		echo "";
	}
}//fin select ubicacion administrativa



function mostrar($select=null,$var=null) {
  $this->layout = "ajax";
  	if(isset($var) && !empty($var) && $var!=''){
		//$dirsup =  $this->Session->read('dirsup');
		//$cond = "cod_tipo_institucion=".$this->verifica_SS(3)." and cod_institucion=".$this->verifica_SS(4)." and cod_dependencia=".$this->verifica_SS(5);
			switch($select){
				case 'republica':
				$this->Session->write('republica_ss_vehiculos',$var);
				$a= $this->cugd01_republica->findAll('cod_republica='.$var);
				$x= $a[0]['cugd01_republica']['denominacion'];
				$this->set("deno",$x);
	 		break;
			case 'estado':
				$republica= $this->Session->read('republica_ss_vehiculos');
				$this->Session->write('estado_ss_vehiculos',$var);
				$cond ="cod_republica=".$republica." and cod_estado=".$var;
				$a=  $this->cugd01_estados->findAll($cond);
				$x= $a[0]['cugd01_estados']['denominacion'];
				$this->set("deno",$x);
	 		break;
			case 'municipio':
				$republica= $this->Session->read('republica_ss_vehiculos');
				$estado =  $this->Session->read('estado_ss_vehiculos');
				$this->Session->write('municipio_ss_vehiculos',$var);
				$cond ="cod_republica=".$republica." and cod_estado=".$estado." and cod_municipio=".$var;
				$a=  $this->cugd01_municipios->findAll($cond);
				$x= $a[0]['cugd01_municipios']['denominacion'];
				$this->set("deno",$x);
	 		break;
			case 'parroquia':
				$republica = $this->Session->read('republica_ss_vehiculos');
				$estado = $this->Session->read('estado_ss_vehiculos');
				$municipio =  $this->Session->read('municipio_ss_vehiculos');
				$this->Session->write('parroquia_ss_vehiculos',$var);
				$cond ="cod_republica=".$republica." and cod_estado=".$estado." and cod_municipio=".$municipio." and cod_parroquia=".$var;
				$a=  $this->cugd01_parroquias->findAll($cond);
				$x= $a[0]['cugd01_parroquias']['denominacion'];
				$this->set("deno",$x);
			break;
			case 'centro_poblado':
			    $republica = $this->Session->read('republica_ss_vehiculos');
				$estado = $this->Session->read('estado_ss_vehiculos');
				$municipio =  $this->Session->read('municipio_ss_vehiculos');
				$parroquia =  $this->Session->read('parroquia_ss_vehiculos');
				$this->Session->write('centro_poblado_ss_vehiculos',$var);
				$cond ="cod_republica=".$republica." and cod_estado=".$estado." and cod_municipio=".$municipio." and cod_parroquia=".$parroquia." and cod_centro=".$var;
				$a=  $this->cugd01_centropoblados->findAll($cond);
				$x= $a[0]['cugd01_centropoblados']['denominacion'];
				$this->set("deno",$x);
			break;
		}//fin switch
	}else{
		echo "";
		$this->set("deno","");
	}
//$oart=$var<9?CE."0".$var:CE.$var;
}//fin mostrar cod dir superior



function buscar_constribuyente($var1=null){
	$this->layout="ajax";
	$this->set("opcion",$var1);
	$this->Session->delete('pista');
}//fin function

function buscar_por_pista($var1=null, $var2=null, $var3=null){
$this->layout="ajax";

    if($var3==null){$var2 = strtoupper($var2);
					$this->Session->write('pista', $var2);
					//if(is_int($var2)){$sql   = " ((rif_cedula LIKE '%$var2%') or (razon_social LIKE '%$var2%'))  or   ";}else{ $sql = "";}
					$Tfilas=$this->v_shd001_registro_contribuyentes->findCount("((rif_cedula LIKE '%$var2%') or (razon_social_nombres LIKE '%$var2%'))");
					        if($Tfilas!=0){
					        	$pagina=1;
					        	$Tfilas=(int)ceil($Tfilas/50);
					        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
								$this->set('total_paginas',$Tfilas);
								$this->set('pagina_actual',$pagina);
								$this->set('ultimo',$Tfilas);
					     	    $datos_filas=$this->v_shd001_registro_contribuyentes->findAll("((rif_cedula LIKE '%$var2%') or (razon_social_nombres LIKE '%$var2%'))   ",null,"rif_cedula ASC",50,1,null);
						        $this->set("datosFILAS",$datos_filas);
						        $this->set('siguiente',$pagina+1);
								$this->set('anterior',$pagina-1);
								$this->bt_nav($Tfilas,$pagina);
					          }else{
					        	$this->set("datosFILAS",'');
					          }
            }else{
						$var22 = $this->Session->read('pista');
						$var22 = strtoupper($var22);
						//if(is_int($var22)){$sql   = " (codigo_prod_serv LIKE '%$var22%')  or   ";}else{ $sql = "";}
						$Tfilas=$this->v_shd001_registro_contribuyentes->findCount("((rif_cedula LIKE '%$var22%') or (razon_social_nombres LIKE '%$var22%'))");
						        if($Tfilas!=0){
						        	$pagina=$var3;
						        	$Tfilas=(int)ceil($Tfilas/50);
						        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
									$this->set('total_paginas',$Tfilas);
									$this->set('pagina_actual',$pagina);
									$this->set('ultimo',$Tfilas);
						     	    $datos_filas=$this->v_shd001_registro_contribuyentes->findAll("((rif_cedula LIKE '%$var22%') or (razon_social_nombres LIKE '%$var22%'))  ",null,"rif_cedula ASC",50,$pagina,null);
							        $this->set("datosFILAS",$datos_filas);
							        $this->set('siguiente',$pagina+1);
									$this->set('anterior',$pagina-1);
									$this->bt_nav($Tfilas,$pagina);
						          }else{
						        	$this->set("datosFILAS",'');
						          }
                 }//fin else
$this->set("opcion",$var1);
}//fin function


function seleccion_busqueda_venta($var1=null){
$this->layout="ajax";
$datos=$this->shd001_registro_contribuyentes->findAll("rif_cedula='".$var1."'");
	if($datos != null){
	$cod_profesion=$datos[0]["shd001_registro_contribuyentes"]["profesion"];
	$cod_pais=$datos[0]["shd001_registro_contribuyentes"]["cod_pais"];
	$cod_estado=$datos[0]["shd001_registro_contribuyentes"]["cod_estado"];
	$cod_municipio=$datos[0]["shd001_registro_contribuyentes"]["cod_municipio"];
	$cod_parroquia=$datos[0]["shd001_registro_contribuyentes"]["cod_parroquia"];
	$cod_centro_poblado=$datos[0]["shd001_registro_contribuyentes"]["cod_centro_poblado"];
	$cod_calle_avenida=$datos[0]["shd001_registro_contribuyentes"]["cod_calle_avenida"];
	$cod_vereda_edificio=$datos[0]["shd001_registro_contribuyentes"]["cod_vereda_edificio"];
	$pais=$this->cugd01_republica->findAll('cod_republica='.$cod_pais);
	$estados=$this->cugd01_estados->findAll('cod_republica='.$cod_pais.' and cod_estado='.$cod_estado);
	$municipios=$this->cugd01_municipios->findAll('cod_republica='.$cod_pais.' and cod_estado='.$cod_estado.' and cod_municipio='.$cod_municipio);
	$parroquias=$this->cugd01_parroquias->findAll('cod_republica='.$cod_pais.' and cod_estado='.$cod_estado.' and cod_municipio='.$cod_municipio.' and cod_parroquia='.$cod_parroquia);
	$centros=$this->cugd01_centropoblados->findAll('cod_republica='.$cod_pais.' and cod_estado='.$cod_estado.' and cod_municipio='.$cod_municipio.' and cod_parroquia='.$cod_parroquia.' and cod_centro='.$cod_centro_poblado);
	$vialidad=$this->cugd01_vialidad->findAll('cod_republica='.$cod_pais.' and cod_estado='.$cod_estado.' and cod_municipio='.$cod_municipio.' and cod_parroquia='.$cod_parroquia.' and cod_centro='.$cod_centro_poblado.' and cod_vialidad='.$cod_calle_avenida);
	$vereda=$this->cugd01_vereda->findAll('cod_republica='.$cod_pais.' and cod_estado='.$cod_estado.' and cod_municipio='.$cod_municipio.' and cod_parroquia='.$cod_parroquia.' and cod_centro='.$cod_centro_poblado.' and cod_vialidad='.$cod_calle_avenida.' and cod_vereda='.$cod_vereda_edificio);
	$profesiones=$this->cnmd06_profesiones->findAll('cod_profesion='.$cod_profesion);
	$this->set('profesion',$profesiones);
	$this->set('pais',$pais);
	$this->set('estados',$estados);
	$this->set('municipios',$municipios);
	$this->set('parroquias',$parroquias);
	$this->set('centros',$centros);
	$this->set('vialidad',$vialidad);
	$this->set('vereda',$vereda);
	$this->set('datos',$datos);



$this->set('datos',$datos);
$resul = javascript_encode($datos[0]['shd001_registro_contribuyentes']['razon_social_nombres'], 1);
   echo'<script>';
		  echo"document.getElementById('deno_rif').value = \"$resul\"; ";
		  echo "document.getElementById('rif_constribuyente').value='".$datos[0]['shd001_registro_contribuyentes']['rif_cedula']."';   ";
   echo'</script>';
					/*echo "<script>";
					    echo "document.getElementById('deno_rif').value='".$datos[0]['shd001_registro_contribuyentes']['razon_social_nombres']."';   ";
					    echo "document.getElementById('rif_constribuyente').value='".$datos[0]['shd001_registro_contribuyentes']['rif_cedula']."';   ";
					echo "</script>";*/
}else{
	$vacio='';
					echo "<script>";
						echo "document.getElementById('deno_rif').value='".$vacio."';   ";
						echo "document.getElementById('rif_constribuyente').value='".$vacio."';   ";
					echo "</script>";
}

}//fin function
function codigo_marca($codigo){
	$this->layout = "ajax";
    $this->set("a",$codigo);
}//fin cpcp02_codigo

function denominacion_marca($codigo){
	$this->layout = "ajax";
	$b = $this->shd200_vehiculos_marcas->findAll("codigo_marca=".$codigo,array('codigo_marca','denominacion'));
	$this->set("b",$b[0]['shd200_vehiculos_marcas']['denominacion']);


}//fin cpcp02_denominacion

function codigo_modelo($codigo){
	$this->layout = "ajax";
    $this->set("a",$codigo);
}//fin cpcp02_codigo

function denominacion_modelo($codigo){
	$this->layout = "ajax";
	$b = $this->shd200_vehiculos_modelos->findAll("codigo_modelo=".$codigo,array('codigo_modelo','denominacion'));
	$this->set("b",$b[0]['shd200_vehiculos_modelos']['denominacion']);


}//fin cpcp02_denominacion

function codigo_color($codigo){
	$this->layout = "ajax";
    $this->set("a",$codigo);
}//fin cpcp02_codigo

function denominacion_color($codigo){
	$this->layout = "ajax";
	$b = $this->shd200_vehiculos_colores->findAll("codigo_color=".$codigo,array('codigo_color','denominacion'));
	$this->set("b",$b[0]['shd200_vehiculos_colores']['denominacion']);


}//fin cpcp02_denominacion

function codigo_clase($codigo){
	$this->layout = "ajax";
    $this->set("a",$codigo);
}//fin cpcp02_codigo

function denominacion_clase($codigo){
	$this->layout = "ajax";
	$b = $this->shd200_vehiculos_clases->findAll("codigo_clase=".$codigo,array('codigo_clase','denominacion'));
	$this->set("b",$b[0]['shd200_vehiculos_clases']['denominacion']);


}//fin cpcp02_denominacion

function codigo_tipo($codigo){
	$this->layout = "ajax";
    $this->set("a",$codigo);
}//fin cpcp02_codigo

function denominacion_tipo($codigo){
	$this->layout = "ajax";
	$b = $this->shd200_vehiculos_tipos->findAll("codigo_tipo=".$codigo,array('codigo_tipo','denominacion'));
	$this->set("b",$b[0]['shd200_vehiculos_tipos']['denominacion']);


}//fin cpcp02_denominacion

function codigo_uso($codigo){
	$this->layout = "ajax";
    $this->set("a",$codigo);
}//fin cpcp02_codigo

function denominacion_uso($codigo){
	$this->layout = "ajax";
	$b = $this->shd200_vehiculos_usos->findAll("codigo_uso=".$codigo,array('codigo_uso','denominacion'));
	$this->set("b",$b[0]['shd200_vehiculos_usos']['denominacion']);


}//fin cpcp02_denominacion

function codigo_clasificacion($codigo){
	$this->layout = "ajax";
    $this->set("a",$codigo);
}//fin cpcp02_codigo

function denominacion_clasificacion($codigo){
	$this->layout = "ajax";
	$b = $this->shd200_vehiculos_clasificacion->findAll("cod_clasificacion='".$codigo."'",array('cod_clasificacion','denominacion'));
	$this->set("b",$b[0]['shd200_vehiculos_clasificacion']['denominacion']);


}//fin cpcp02_denominacion

function men_clasificacion($codigo){
	$this->layout = "ajax";
	$b = $this->shd200_vehiculos_clasificacion->findAll("cod_clasificacion='".$codigo."'",array('monto_anual','denominacion'));
	$bx=($b[0]['shd200_vehiculos_clasificacion']['monto_anual']) / 12;
	//echo  $b[0]['shd200_vehiculos_clasificacion']['monto_anual']. ' y el '.$bx;
	$this->set("a",$bx);


}//fin cpcp02_denominacion

function anu_clasificacion($codigo){
	$this->layout = "ajax";
	$b = $this->shd200_vehiculos_clasificacion->findAll("cod_clasificacion='".$codigo."'",array('monto_anual','denominacion'));
	$this->set("a",$b[0]['shd200_vehiculos_clasificacion']['monto_anual']);


}//fin cpcp02_denominacion

function codigo_rif($codigo){
	$this->layout = "ajax";
    $this->set("a",$codigo);
}//fin cpcp02_codigo

function denominacion_rif($codigo){
	$this->layout = "ajax";
	$b = $this->shd002_cobradores->findAll($this->SQLCA()." and rif_ci='".$codigo."'",array('rif_ci','nombre_razon'));
	$this->set("b",$b[0]['shd002_cobradores']['nombre_razon']);
}//fin cpcp02_denominacion

function guardar(){
	$this->layout = "ajax";
	$cod_presi 					= $this->Session->read('SScodpresi');
	$cod_entidad 				= $this->Session->read('SScodentidad');
	$cod_tipo_inst 				= $this->Session->read('SScodtipoinst');
	$cod_inst 					= $this->Session->read('SScodinst');
	$cod_dep 					= $this->Session->read('SScoddep');
	$rif_cedula    		= $this->data['shp200_vehiculos_contribuyentes']['rif_constribuyente'];
	$placa_vehiculo     		= $this->data['shp200_vehiculos_contribuyentes']['numero_placa'];
	$fecha_registro     		= $this->data['shp200_vehiculos_contribuyentes']['fecha_registro'];
	$cod_marca    		= $this->data['shp200_vehiculos_contribuyentes']['cod_marca'];
	$cod_modelo     		= $this->data['shp200_vehiculos_contribuyentes']['cod_modelo'];
	$cod_color    		= $this->data['shp200_vehiculos_contribuyentes']['cod_color'];
	$cod_clase     		= $this->data['shp200_vehiculos_contribuyentes']['cod_clase'];
	$cod_tipo    		= $this->data['shp200_vehiculos_contribuyentes']['cod_tipo'];
	$cod_uso     		= $this->data['shp200_vehiculos_contribuyentes']['cod_uso'];
	$serial_carroceria    		= $this->data['shp200_vehiculos_contribuyentes']['seria_carroceria'];
	$serial_motor     		= $this->data['shp200_vehiculos_contribuyentes']['serial_motor'];
	$ano_adquisicion    		= $this->data['shp200_vehiculos_contribuyentes']['ano_adquisicion'];
	$valor_vehiculo     		= $this->data['shp200_vehiculos_contribuyentes']['valor_adquisicion'];
	$fecha_adquisicion    		= $this->data['shp200_vehiculos_contribuyentes']['fecha_adquisicion'];
	$cod_clasificacion     		= $this->data['shp200_vehiculos_contribuyentes']['cod_clasificacion'];
	$frecuencia_pago    		= $this->data['shp200_vehiculos_contribuyentes']['frecuencia_pago'];
	$monto_mensual     		= $this->data['shp200_vehiculos_contribuyentes']['monto_mensual'];
	$pago_todo    		= $this->data['shp200_vehiculos_contribuyentes']['pago_todo'];
	$suspendido     			= $this->data['shp200_vehiculos_contribuyentes']['suspendido'];
	$rif_ci_cobrador    		= $this->data['shp200_vehiculos_contribuyentes']['rif_cedula'];
	$ultimo_ano_facturado     		= 0;
	$ultimo_mes_facturado    		= 0;
	$valor_vehiculo=$this->Formato1($valor_vehiculo);
	$monto_mensual=$this->Formato1($monto_mensual);

$SQL_INSERT ="INSERT INTO shd200_vehiculos (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep,rif_cedula, placa_vehiculo, fecha_registro, cod_marca, cod_modelo, cod_color, cod_clase, cod_tipo, cod_uso,
  serial_carroceria, serial_motor, ano_adquisicion, valor_vehiculo, fecha_adquisicion, cod_clasificacion,
  frecuencia_pago, monto_mensual, pago_todo, suspendido, rif_ci_cobrador, ultimo_ano_facturado, ultimo_mes_facturado)";

$SQL_INSERT .=" VALUES ($cod_presi, $cod_entidad, $cod_tipo_inst, $cod_inst, $cod_dep, '".$rif_cedula."', '".$placa_vehiculo."', '".$fecha_registro."', $cod_marca, $cod_modelo, $cod_color, $cod_clase, $cod_tipo, $cod_uso,
  '".$serial_carroceria."', '".$serial_motor."', $ano_adquisicion, $valor_vehiculo, '".$fecha_adquisicion."', '".$cod_clasificacion."',
  '".$frecuencia_pago."', $monto_mensual, $pago_todo, $suspendido, '".$rif_ci_cobrador."', $ultimo_ano_facturado, $ultimo_mes_facturado)";

$sw = $this->shd200_vehiculos->execute($SQL_INSERT);
		if($sw>1){
			$this->set('Message_existe', 'LOS DATOS FUERON GUARDADOS');
		}else{
			$this->set('errorMessage', 'LOS DATOS NO FUERON GUARDADOS - POR FAVOR INTENTE DE NUEVO');
		}
		$this->index();
		$this->render("index");
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
}//fin navegacion

function consultar($pagina=null){//echo 'si llego';
 		$this->layout = "ajax";
         if(isset($pagina) && $pagina!=null){
          	 $pagina=$pagina;
          	 $this->set('pagina',$pagina);
          	  $Tfilas=$this->v_shd200_vehiculos->findCount($this->SQLCA());
          	 if($Tfilas==0){
          	 	$this->set('errorMessage', 'NO SE ENCONTRARON DATOS');
          	 	$this->index();
          		$this->render("index");
          	 }
          	 if($Tfilas!=0){
          	 $this->set('pag_cant',$pagina.'/'.$Tfilas);
          	 $datos=$this->v_shd200_vehiculos->findAll($this->SQLCA(),null,'rif_cedula ASC',1,$pagina,null);
          	 $this->set('datos',$datos);
          	 //$this->set('datos2',$datos2);
          	 $this->set('siguiente',$pagina+1);
          	 $this->set('anterior',$pagina-1);
             $this->bt_nav($Tfilas,$pagina);
             }
            // echo"hola";
 }else{
 	$pagina=1;
 			$this->set('pagina',$pagina);
          	 $Tfilas=$this->v_shd200_vehiculos->findCount($this->SQLCA());
          	 if($Tfilas==0){
          	 	$this->set('errorMessage', 'NO SE ENCONTRARON DATOS');
          	 	$this->index();
          		$this->render("index");
          	 }
          	 if($Tfilas!=0){
          	 $this->set('pag_cant',$pagina.'/'.$Tfilas);
          	 $datos=$this->v_shd200_vehiculos->findAll($this->SQLCA(),null,'rif_cedula ASC',1,$pagina,null);
          	 $this->set('datos',$datos);
          	 $this->set('siguiente',$pagina+1);
          	 $this->set('anterior',$pagina-1);
             $this->bt_nav($Tfilas,$pagina);
			 }
}//fin function consultar2

}

function modificar($rif_cedula=null,$placa=null,$pagina=null){
	$this->layout = "ajax";
	$datos=$this->v_shd200_vehiculos->findAll($this->SQLCA()." and rif_cedula='".$rif_cedula."' and placa_vehiculo='".$placa."'",null,'rif_cedula ASC',null,null,null);
    $marca	= $this->shd200_vehiculos_marcas->generateList(null, 'codigo_marca ASC', null, '{n}.shd200_vehiculos_marcas.codigo_marca', '{n}.shd200_vehiculos_marcas.denominacion');
	$modelo = $this->shd200_vehiculos_modelos->generateList(null, 'codigo_modelo ASC', null, '{n}.shd200_vehiculos_modelos.codigo_modelo', '{n}.shd200_vehiculos_modelos.denominacion');
	$color 	= $this->shd200_vehiculos_colores->generateList(null, 'codigo_color ASC', null, '{n}.shd200_vehiculos_colores.codigo_color', '{n}.shd200_vehiculos_colores.denominacion');
	$clase 	= $this->shd200_vehiculos_clases->generateList(null, 'codigo_clase ASC', null, '{n}.shd200_vehiculos_clases.codigo_clase', '{n}.shd200_vehiculos_clases.denominacion');
	$tipo 	= $this->shd200_vehiculos_tipos->generateList(null, 'codigo_tipo ASC', null, '{n}.shd200_vehiculos_tipos.codigo_tipo', '{n}.shd200_vehiculos_tipos.denominacion');
	$uso 	= $this->shd200_vehiculos_usos->generateList(null, 'codigo_uso ASC', null, '{n}.shd200_vehiculos_usos.codigo_uso', '{n}.shd200_vehiculos_usos.denominacion');
	$clasificacion 	= $this->shd200_vehiculos_clasificacion->generateList($this->SQLCA() , 'cod_clasificacion ASC', null, '{n}.shd200_vehiculos_clasificacion.cod_clasificacion', '{n}.shd200_vehiculos_clasificacion.denominacion');
	$this->set("rif_cedula", $this->shd002_cobradores->generateList($this->SQLCA(), "nombre_razon ASC", null, '{n}.shd002_cobradores.rif_ci', '{n}.shd002_cobradores.nombre_razon'));
	$this->concatena($marca, 'marca');
	$this->concatena($modelo, 'modelo');
	$this->concatena($color, 'color');
	$this->concatena($clase, 'clase');
	$this->concatena($tipo, 'tipo');
	$this->concatena($uso, 'uso');
	$this->concatena($clasificacion, 'clasificacion');
	$this->set('datos',$datos);
    $this->set('pagina',$pagina);
}

function guardar_modificar($rif_cedula=null,$placa=null,$pagina=null){
	$this->layout = "ajax";
	//pr($this->data);
	$placa_vehiculo     		= $this->data['shp200_vehiculos_contribuyentes']['numero_placa'];
	$fecha_registro     		= $this->data['shp200_vehiculos_contribuyentes']['fecha_registro'];
	$cod_marca    				= $this->data['shp200_vehiculos_contribuyentes']['cod_marca'];
	$cod_modelo     			= $this->data['shp200_vehiculos_contribuyentes']['cod_modelo'];
	$cod_color    				= $this->data['shp200_vehiculos_contribuyentes']['cod_color'];
	$cod_clase     				= $this->data['shp200_vehiculos_contribuyentes']['cod_clase'];
	$cod_tipo    				= $this->data['shp200_vehiculos_contribuyentes']['cod_tipo'];
	$cod_uso     				= $this->data['shp200_vehiculos_contribuyentes']['cod_uso'];
	$serial_carroceria    		= $this->data['shp200_vehiculos_contribuyentes']['seria_carroceria'];
	$serial_motor     			= $this->data['shp200_vehiculos_contribuyentes']['serial_motor'];
	$ano_adquisicion    		= $this->data['shp200_vehiculos_contribuyentes']['ano_adquisicion'];
	$valor_vehiculo     		= $this->data['shp200_vehiculos_contribuyentes']['valor_adquisicion'];
	$fecha_adquisicion    		= $this->data['shp200_vehiculos_contribuyentes']['fecha_adquisicion'];
	$cod_clasificacion     		= $this->data['shp200_vehiculos_contribuyentes']['cod_clasificacion'];
	$frecuencia_pago    		= $this->data['shp200_vehiculos_contribuyentes']['frecuencia_pago'];
	$monto_mensual     			= $this->data['shp200_vehiculos_contribuyentes']['monto_mensual'];
	$pago_todo    				= $this->data['shp200_vehiculos_contribuyentes']['pago_todo'];
	$suspendido     			= $this->data['shp200_vehiculos_contribuyentes']['suspendido'];
	$rif_ci_cobrador    		= $this->data['shp200_vehiculos_contribuyentes']['rif_cedula'];
	$ultimo_ano_facturado     	= 0;
	$ultimo_mes_facturado    	= 0;
	$valor_vehiculo=$this->Formato1($valor_vehiculo);
	$monto_mensual=$this->Formato1($monto_mensual);
	$cond=$this->SQLCA();
	$guardar="update shd200_vehiculos set fecha_registro='".$fecha_registro."', cod_marca=$cod_marca, cod_modelo=$cod_modelo, cod_color=$cod_color, cod_clase=$cod_clase, cod_tipo=$cod_tipo, cod_uso=$cod_uso,
  serial_carroceria='".$serial_carroceria."', serial_motor='".$serial_motor."', ano_adquisicion=$ano_adquisicion, valor_vehiculo=$valor_vehiculo, fecha_adquisicion='".$fecha_adquisicion."', cod_clasificacion='".$cod_clasificacion."',
  frecuencia_pago=$frecuencia_pago, monto_mensual=$monto_mensual, pago_todo=$pago_todo, suspendido=$suspendido, rif_ci_cobrador='".$rif_ci_cobrador."' where rif_cedula='".$rif_cedula."' and placa_vehiculo='".$placa_vehiculo."' and $cond";
	$sw = $this->shd200_vehiculos->execute($guardar);
		if($sw>1){
			$this->set('Message_existe', 'LOS DATOS FUERON GUARDADOS');
		}else{
			$this->set('errorMessage', 'LOS DATOS NO FUERON GUARDADOS - POR FAVOR INTENTE DE NUEVO');
		}
		$this->consultar($pagina);
		$this->render("consultar");
}

function eliminar($rif=null,$placa=null,$pagina=null){
 	$this->layout = "ajax";
 	$ca=$this->SQLCA();
 	$cond="rif_cedula='".$rif."' and placa_vehiculo='".$placa."' and $ca";
 	//echo $cond;
 	$this->shd200_vehiculos->execute("DELETE FROM shd200_vehiculos  WHERE ".$cond);
 	$y=$this->shd200_vehiculos->findCount($this->SQLCA());
 	if($pagina>$y){
 		$pagina=$pagina-1;
 	}
 	if($y!=0){
	  	 $this->set('Message_existe', 'Registro Eliminado con exito.');
      	 $this->consultar($pagina);//si es el primero solamente
      $this->render("consultar");

		}else if($y==0){
			$this->set('Message_existe', 'Registro Eliminado con exito.');
			$this->index();
      		$this->render("index");
		}//fin if
}

function buscar($var1=null){
	$this->layout="ajax";
	$this->set("opcion",$var1);
	$this->Session->delete('pista');
}//fin function

function buscar_por_pista2($var1=null, $var2=null, $var3=null){
$this->layout="ajax";
    if($var3==null){$var2 = strtoupper($var2);
					$this->Session->write('pista', $var2);//echo "(rif_cedula LIKE '%$var2%' or razon_social_nombres LIKE '%$var2%')";
					$Tfilas=$this->v_shd200_vehiculos->findCount($this->SQLCA()." and (rif_cedula LIKE '%$var2%' or nombre_razon LIKE '%$var2%')");
					        if($Tfilas!=0){
					        	$pagina=1;
					        	$Tfilas=(int)ceil($Tfilas/50);
					        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
								$this->set('total_paginas',$Tfilas);
								$this->set('pagina_actual',$pagina);
								$this->set('ultimo',$Tfilas);
					     	    $datos_filas=$this->v_shd200_vehiculos->findAll($this->SQLCA()." and (rif_cedula LIKE '%$var2%' or nombre_razon LIKE '%$var2%')",null,"rif_cedula ASC",50,1,null);
						        $this->set("datosFILAS",$datos_filas);
						        $this->set('siguiente',$pagina+1);
								$this->set('anterior',$pagina-1);
								$this->bt_nav($Tfilas,$pagina);
					          }else{
					        	$this->set("datosFILAS",'');
					          }
            }else{
						$var22 = $this->Session->read('pista');
						$var22 = strtoupper($var22);//echo "(rif_cedula LIKE '%$var22%' or razon_social_nombres LIKE '%$var22%')";
						$Tfilas=$this->v_shd200_vehiculos->findCount($this->SQLCA()." and (rif_cedula LIKE '%$var22%' or nombre_razon LIKE '%$var22%')");
						        if($Tfilas!=0){
						        	$pagina=$var3;
						        	$Tfilas=(int)ceil($Tfilas/50);
						        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
									$this->set('total_paginas',$Tfilas);
									$this->set('pagina_actual',$pagina);
									$this->set('ultimo',$Tfilas);
						     	    $datos_filas=$this->v_shd200_vehiculos->findAll($this->SQLCA()." and (rif_cedula LIKE '%$var22%' or nombre_razon LIKE '%$var22%')",null,"rif_cedula ASC",50,$pagina,null);
							        $this->set("datosFILAS",$datos_filas);
							        $this->set('siguiente',$pagina+1);
									$this->set('anterior',$pagina-1);
									$this->bt_nav($Tfilas,$pagina);
						          }else{
						        	$this->set("datosFILAS",'');
						          }
                 }//fin else
$this->set("opcion",$var1);
}//fin function

function consulta2($numero=null,$placa=null){
	$this->layout = "ajax";
 	$cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$modulo = $this->Session->read('Modulo');
	$c = "rif_cedula='".$numero."' and placa_vehiculo='".$placa."' and ".$this->SQLCA();
    $veri=$this->v_shd200_vehiculos->findCount($c);
      if($veri > 0){
      	$datacpcp01=$this->v_shd200_vehiculos->findAll($c);
      	$this->set('datos',$datacpcp01);
      	$this->set('rif_cedula',$numero);
      }else{
	  			$this->index();
				$this->render("index");
          	 }
}//fin function consultar2




function consulta3($pagina=null){//echo 'si llego';
 		$this->layout = "ajax";
         if(isset($pagina) && $pagina!=null){
          	 $pagina=$pagina;
          	 $this->set('pagina',$pagina);
          	  $Tfilas=$this->v_shd200_vehiculos->findCount($this->SQLCA());
          	 if($Tfilas==0){
          	 	$this->set('errorMessage', 'NO SE ENCONTRARON DATOS');
          	 	$this->vacio('NO SE ENCONTRARÓN DATOS','error');
          		$this->render("vacio");
          	 }
          	 if($Tfilas!=0){
          	 $this->set('pag_cant',$pagina.'/'.$Tfilas);
          	 $datos=$this->v_shd200_vehiculos->findAll($this->SQLCA(),null,'rif_cedula ASC',1,$pagina,null);
          	 $this->set('datos',$datos);
          	 //$this->set('datos2',$datos2);
          	 $this->set('siguiente',$pagina+1);
          	 $this->set('anterior',$pagina-1);
             $this->bt_nav($Tfilas,$pagina);
             }
            // echo"hola";
 }else{
 	$pagina=1;
 			$this->set('pagina',$pagina);
          	 $Tfilas=$this->v_shd200_vehiculos->findCount($this->SQLCA());
          	 if($Tfilas==0){
          	 	$this->set('errorMessage', 'NO SE ENCONTRARON DATOS');
          	 	$this->vacio('NO SE ENCONTRARÓN DATOS','error');
          		$this->render("vacio");;
          	 }
          	 if($Tfilas!=0){
          	 $this->set('pag_cant',$pagina.'/'.$Tfilas);
          	 $datos=$this->v_shd200_vehiculos->findAll($this->SQLCA(),null,'rif_cedula ASC',1,$pagina,null);
          	 $this->set('datos',$datos);
          	 $this->set('siguiente',$pagina+1);
          	 $this->set('anterior',$pagina-1);
             $this->bt_nav($Tfilas,$pagina);
			 }
}//fin function consultar2

}

function buscar3($var1=null){
	$this->layout="ajax";
	$this->set("opcion",$var1);
	$this->Session->delete('pista');
}//fin function

function buscar_por_pista3($var1=null, $var2=null, $var3=null){
$this->layout="ajax";
    if($var3==null){$var2 = strtoupper($var2);
					$this->Session->write('pista', $var2);//echo "(rif_cedula LIKE '%$var2%' or razon_social_nombres LIKE '%$var2%')";
					$Tfilas=$this->v_shd200_vehiculos->findCount($this->SQLCA()." and (rif_cedula LIKE '%$var2%' or nombre_razon LIKE '%$var2%')");
					        if($Tfilas!=0){
					        	$pagina=1;
					        	$Tfilas=(int)ceil($Tfilas/50);
					        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
								$this->set('total_paginas',$Tfilas);
								$this->set('pagina_actual',$pagina);
								$this->set('ultimo',$Tfilas);
					     	    $datos_filas=$this->v_shd200_vehiculos->findAll($this->SQLCA()." and (rif_cedula LIKE '%$var2%' or nombre_razon LIKE '%$var2%')",null,"rif_cedula ASC",50,1,null);
						        $this->set("datosFILAS",$datos_filas);
						        $this->set('siguiente',$pagina+1);
								$this->set('anterior',$pagina-1);
								$this->bt_nav($Tfilas,$pagina);
					          }else{
					        	$this->set("datosFILAS",'');
					          }
            }else{
						$var22 = $this->Session->read('pista');
						$var22 = strtoupper($var22);//echo "(rif_cedula LIKE '%$var22%' or razon_social_nombres LIKE '%$var22%')";
						$Tfilas=$this->v_shd200_vehiculos->findCount($this->SQLCA()." and (rif_cedula LIKE '%$var22%' or nombre_razon LIKE '%$var22%')");
						        if($Tfilas!=0){
						        	$pagina=$var3;
						        	$Tfilas=(int)ceil($Tfilas/50);
						        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
									$this->set('total_paginas',$Tfilas);
									$this->set('pagina_actual',$pagina);
									$this->set('ultimo',$Tfilas);
						     	    $datos_filas=$this->v_shd200_vehiculos->findAll($this->SQLCA()." and (rif_cedula LIKE '%$var22%' or nombre_razon LIKE '%$var22%')",null,"rif_cedula ASC",50,$pagina,null);
							        $this->set("datosFILAS",$datos_filas);
							        $this->set('siguiente',$pagina+1);
									$this->set('anterior',$pagina-1);
									$this->bt_nav($Tfilas,$pagina);
						          }else{
						        	$this->set("datosFILAS",'');
						          }
                 }//fin else
$this->set("opcion",$var1);
}//fin function

function consulta4($numero=null,$placa=null){
	$this->layout = "ajax";
 	$cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$modulo = $this->Session->read('Modulo');
	$c = "rif_cedula='".$numero."' and placa_vehiculo='".$placa."' and ".$this->SQLCA();
    $veri=$this->v_shd200_vehiculos->findCount($c);
      if($veri > 0){
      	$datacpcp01=$this->v_shd200_vehiculos->findAll($c);
      	$this->set('datos',$datacpcp01);
      	$this->set('rif_cedula',$numero);
      }else{
          	 	$this->set('errorMessage', 'NO SE ENCONTRARON DATOS');
          	 	$this->vacio('NO SE ENCONTRARÓN DATOS','error');
          		$this->render("vacio");;
          	 }
}//fin function consultar2

function vacio ($msj,$tipo) {
   $this->layout="ajax";
   $this->set($tipo,$msj);

}//fin funcion vacio



 }
?>
