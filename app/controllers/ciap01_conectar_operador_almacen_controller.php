<?php
/*
 * Creado el 09/10/2008 a las 12:41:23 AM
 * Herramienta: EasyEclipse.
 * Proyecto: SIGEP
 * Descripcion:
 */

 class Ciap01ConectarOperadorAlmacenController extends AppController{
	var $uses = array('ciad01_inventario_almacen','ciad01_inventario_usuarios','Usuario');
 	var $helpers = array('Html','Ajax','Javascript', 'Sisap');
 	var $name = "ciap01_conectar_operador_almacen";


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


function index(){///////////////<<--INDEX
	$this->layout = "ajax";

	$ver=$this->ciad01_inventario_usuarios->execute("select * from ciad01_inventario_usuarios where ".$this->SQLCA()." order by cod_almacen,username asc");
	if($ver!=null){
		$this->set('datos',$ver);
	}else{
		$this->set('datos',null);
	}
	$datos=$this->ciad01_inventario_almacen->generateList($this->SQLCA(),'cod_almacen ASC', null, '{n}.ciad01_inventario_almacen.cod_almacen', '{n}.ciad01_inventario_almacen.denominacion');
	if($datos!=null){
		$this->concatena($datos,'almacenes');
	}else{
		$this->set('almacenes',array());
	}

	$this->set('alma',$this->ciad01_inventario_almacen->FindAll($this->SQLCA()));

	$this->data=null;

}//fin index


function busqueda_usuario($login=null){
	$this->layout="ajax";
	$login=strtoupper($login);
//	$ver  = $this->ccnd00->findCount("  quitar_acentos(mayus_acentos(username::text)) = quitar_acentos(mayus_acentos('$login')) ");
	$ver1 = $this->Usuario->findCount($this->SQLCA()." and  quitar_acentos(mayus_acentos(username::text)) = quitar_acentos(mayus_acentos('$login')) ");

	if($ver1==0){
		$this->set('errorMessage',"el usuario que ingreso no existe registrado");

	}

}// fin busqueda_usuario






function denominacion($var=null){
	$this->layout="ajax";
	if($var!=''){
		$a=$this->ciad01_inventario_almacen->execute("select * from ciad01_inventario_almacen where ".$this->SQLCA()." and cod_almacen=".$var);
		$this->set('denominacion',$a[0][0]['denominacion']);
	}else{
		$this->set('denominacion',null);
	}
}



function buscar_datos($var1=null){
	$this->layout="ajax";
	$this->set("opcion",$var1);
	$this->Session->delete('pista');
}//fin buscar_ficha



function buscar_por_pista($var1=null, $var2=null, $var3=null){
$this->layout="ajax";
    if($var3==null){
    	$var2 = strtoupper($var2);
					$this->Session->write('pista', $var2);
					$a=$this->busca_separado(array('username'),$var2);
					$Tfilas=$this->Usuario->findCount($this->SQLCA()." and ".$a);
					        if($Tfilas!=0){
					        	$pagina=1;
					        	$Tfilas=(int)ceil($Tfilas/100);
					        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
								$this->set('total_paginas',$Tfilas);
								$this->set('pagina_actual',$pagina);
								$this->set('ultimo',$Tfilas);
					     	    $datos_filas=$this->Usuario->findAll($this->SQLCA()." and ".$a,null,"username ASC",100,1,null);
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
						$a=$this->busca_separado(array('username'),$var2);
						$Tfilas=$this->Usuario->findCount($this->SQLCA()." and ".$a);
						        if($Tfilas!=0){
						        	$pagina=$var3;
						        	$Tfilas=(int)ceil($Tfilas/100);
						        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
									$this->set('total_paginas',$Tfilas);
									$this->set('pagina_actual',$pagina);
									$this->set('ultimo',$Tfilas);
									$datos_filas=$this->Usuario->findAll($this->SQLCA()." and ".$a,null,"username ASC",100,$pagina,null);
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



function seleccion_busqueda($opc=null,$var=null){
	$this->layout = "ajax";
	$this->set('usuario',$var);

}


function guardar(){
	$this->layout = "ajax";
	$cod_presi 					= $this->Session->read('SScodpresi');
	$cod_entidad 				= $this->Session->read('SScodentidad');
	$cod_tipo_inst 				= $this->Session->read('SScodtipoinst');
	$cod_inst 					= $this->Session->read('SScodinst');
	$cod_dep 					= $this->Session->read('SScoddep');

	if(empty($this->data['ciap01']['usuario'])){
		$this->set('errorMessage', 'debe ingresar el usuario');
	}else if(empty($this->data['ciap01']['almacenes'])){
		$this->set('errorMessage', 'debe seleccionar el almacén');
	}else{
		$usuario       = $this->data['ciap01']['usuario'];
		$almacen       = $this->data['ciap01']['almacenes'];
		if($this->ciad01_inventario_usuarios->FindCount($this->SQLCA()." and username='".$usuario."' and cod_almacen=".$almacen)==0){
			$SQL_INSERT ="INSERT INTO ciad01_inventario_usuarios VALUES ('$cod_presi', '$cod_entidad' ,'$cod_tipo_inst' ,'$cod_inst' ,'$cod_dep' ,'$usuario','$almacen')";
			$sw = $this->ciad01_inventario_usuarios->execute($SQL_INSERT);
			if($sw>1){
				$this->set('Message_existe', 'REGISTRO EXITOSO');
				$this->set('guardado', 'si');
			}else{
				$this->set('errorMessage', 'LOS DATOS NO FUERON GUARDADOS');
			}
		}else{
			$this->set('errorMessage', 'estos datos ya existen registrados');
		}


	}



}





function eliminar($user=null,$cod_almacen=null){
 	$this->layout = "ajax";

 	$this->ciad01_inventario_usuarios->execute("DELETE FROM ciad01_inventario_usuarios  WHERE ".$this->SQLCA()." and username='".$user."' and cod_almacen=".$cod_almacen);
	$this->set('Message_existe', 'el dato fue eliminado');


}



}

//fin class