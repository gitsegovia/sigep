<?php
/*
 * Creado el 09/10/2008 a las 12:41:23 AM
 * Herramienta: EasyEclipse.
 * Proyecto: SIGEP
 * Descripcion:
 */

 class Ciap01SalidaProductosMaterialesController extends AppController{
	var $uses = array('ciad01_inventario_almacen','v_cscd01_snc_grupo','ciad01_inventario_productos','cscd01_catalogo','cscd01_unidad_medida',
					  'ciad01_inventario_usuarios','v_ciad01_inventario_productos','ciad01_inventario_salidas_numero','ccfd04_cierre_mes',
					  'ciad01_inventario_salidas_cuerpo','ciad01_inventario_salidas_detalles');
 	var $helpers = array('Html','Ajax','Javascript', 'Sisap');
 	var $name = "ciap01_salida_productos_materiales";


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

    function concatena_seis($vector1=null, $nomVar=null, $extra=null){
		$cod = array();
		if($vector1 != null){
			foreach($vector1 as $x => $y){
				$cod[$x] = mascara($x,6).' - '.$y;
			}

		}
		$this->set($nomVar, $cod);
	}//fin function


function index(){///////////////<<--INDEX
	$this->layout = "ajax";
	$cod_presi 					= $this->Session->read('SScodpresi');
	$cod_entidad 				= $this->Session->read('SScodentidad');
	$cod_tipo_inst 				= $this->Session->read('SScodtipoinst');
	$cod_inst 					= $this->Session->read('SScodinst');
	$cod_dep 					= $this->Session->read('SScoddep');
	$this->limpiar_lista();

	$datos=$this->ciad01_inventario_almacen->generateList($this->SQLCA(),'cod_almacen ASC', null, '{n}.ciad01_inventario_almacen.cod_almacen', '{n}.ciad01_inventario_almacen.denominacion');
	if($datos!=null){
		$this->concatena($datos,'almacenes');
	}else{
		$this->set('almacenes',array());
	}

	$ano=$this->ano_ejecucion();
	$this->set('ano',$ano);


	$ver=$this->ciad01_inventario_usuarios->execute("select * from ciad01_inventario_usuarios where ".$this->SQLCA()." and username='".$_SESSION['nom_usuario']."'");
	if($ver!=null){
		$a=$this->ciad01_inventario_usuarios->execute("select * from ciad01_inventario_almacen where ".$this->SQLCA()." and cod_almacen=".$ver[0][0]['cod_almacen']);
		$this->set('almacen',$a[0][0]['cod_almacen']);
		$this->set('deno_almacen',$a[0][0]['denominacion']);
		$this->set('ubicacion',$a[0][0]['ubicacion']);
		$this->set('readonly','readonly');
		$this->Session->delete('cod_almacen');
		$this->Session->write('cod_almacen',$a[0][0]['cod_almacen']);



	      $maxi=$this->ciad01_inventario_salidas_numero->findCount($this->SQLCA()." and cod_almacen_salida=".$a[0][0]['cod_almacen']." and ano_orden_salida=".$ano." and situacion=1");
	      if($maxi==0){
	         $this->set("errorMessage","debe registrar nuevos números para el control de salidas de este almacén");
	      	 $this->set("numero_salida","");
	      }else{
	      	///////////////////////////////////////////////////
	      			$max=$this->ciad01_inventario_salidas_numero->execute("SELECT numero_orden_salida FROM ciad01_inventario_salidas_numero WHERE ".$this->SQLCA()." and cod_almacen_salida=".$a[0][0]['cod_almacen']." and ano_orden_salida=".$ano." and situacion=1 ORDER BY numero_orden_salida ASC LIMIT 1");
		      		if($max!=null){
				      	    $codigo=$max[0][0]["numero_orden_salida"];
				            $resultado=$this->ciad01_inventario_salidas_numero->execute("UPDATE  ciad01_inventario_salidas_numero SET situacion=2 WHERE ".$this->SQLCA()." and cod_almacen_salida=".$a[0][0]['cod_almacen']." and numero_orden_salida=".$codigo." and ano_orden_salida=".$ano);
					         if($resultado>1){
				               $this->set("numero_salida",$codigo);
					         }else{
						        $this->set("errorMessage","Por favor Verifique el n&uacute;mero de control de salidas");
						        $this->set("numero_salida","");
					      }
			      }else{
			      	 $this->set("errorMessage","debe registrar nuevos números para el control de salidas de este almacén");
			      	 $this->set("numero_salida","");
	 			 }






	      }



	}else{
		$this->set('almacen','');
		$this->set('deno_almacen','');
		$this->set('ubicacion','');
		$this->set('readonly','');
		$this->set("numero_salida","");
	}

	$sql="select
		distinct
		a.cod_dep,
		(select b.denominacion from arrd05 b where b.cod_presi='$cod_presi' and b.cod_entidad='$cod_entidad' and b.cod_tipo_inst='$cod_tipo_inst' and b.cod_inst='$cod_inst' and b.cod_dep=a.cod_dep) as denominacion
		from ciad01_inventario_almacen a where a.cod_presi='$cod_presi' and a.cod_entidad='$cod_entidad' and a.cod_tipo_inst='$cod_tipo_inst' and a.cod_inst='$cod_inst'";

		$datos  = $this->ciad01_inventario_almacen->execute($sql);
		if(count($datos)!=0){
		foreach($datos as $n){
			$lista[$n[0]['cod_dep']]=mascara($n[0]['cod_dep'],3)." - ".$n[0]['denominacion'];
	    }
	}else{
		$lista=array();
	}
	$this->set("lista_dependencia", $lista);
	$this->set("cod_dependencia", $cod_dep);

	$dep  = $this->ciad01_inventario_almacen->execute("select denominacion from arrd05 where ".$this->SQLCA());
	$this->set("dependencia", $dep[0][0]['denominacion']);



}//fin index


function verifica($var=null){
	$this->layout = "ajax";
	$cod_presi 					= $this->Session->read('SScodpresi');
	$cod_entidad 				= $this->Session->read('SScodentidad');
	$cod_tipo_inst 				= $this->Session->read('SScodtipoinst');
	$cod_inst 					= $this->Session->read('SScodinst');
	$cod_dep 					= $this->Session->read('SScoddep');

	$this->limpiar_lista();

	$datos=$this->ciad01_inventario_almacen->generateList($this->SQLCA(),'cod_almacen ASC', null, '{n}.ciad01_inventario_almacen.cod_almacen', '{n}.ciad01_inventario_almacen.denominacion');
	if($datos!=null){
		$this->concatena($datos,'almacenes');
	}else{
		$this->set('almacenes',array());
	}

	$ano=$this->ano_ejecucion();
	$this->set('ano',$ano);

	if($var!=''){





				$a=$this->ciad01_inventario_usuarios->execute("select * from ciad01_inventario_almacen where ".$this->SQLCA()." and cod_almacen=".$var);
				$this->set('almacen',$a[0][0]['cod_almacen']);
				$this->set('deno_almacen',$a[0][0]['denominacion']);
				$this->set('ubicacion',$a[0][0]['ubicacion']);
				$this->set('readonly','readonly');
				$this->Session->delete('cod_almacen');
				$this->Session->write('cod_almacen',$a[0][0]['cod_almacen']);


				////////////////////////////aqui la parte que verifica el numero del almacen/////////////////////////////////////
			      $maxi=$this->ciad01_inventario_salidas_numero->findCount($this->SQLCA()." and cod_almacen_salida=".$a[0][0]['cod_almacen']." and ano_orden_salida=".$ano." and situacion=1");
			      if($maxi==0){
			         $this->set("errorMessage","debe registrar nuevos números para el control de salidas de este almacén");
			      	 $this->set("numero_salida",0);
			      }else{
			      	///////////////////////////////////////////////////
			      			$max=$this->ciad01_inventario_salidas_numero->execute("SELECT numero_orden_salida FROM ciad01_inventario_salidas_numero WHERE ".$this->SQLCA()." and cod_almacen_salida=".$a[0][0]['cod_almacen']." and ano_orden_salida=".$ano." and situacion=1 ORDER BY numero_orden_salida ASC LIMIT 1");
				      		if($max!=null){
						      	    $codigo=$max[0][0]["numero_orden_salida"];
						            $resultado=$this->ciad01_inventario_salidas_numero->execute("UPDATE  ciad01_inventario_salidas_numero SET situacion=2 WHERE ".$this->SQLCA()." and cod_almacen_salida=".$a[0][0]['cod_almacen']." and numero_orden_salida=".$codigo." and ano_orden_salida=".$ano);
							         if($resultado>1){
						                //$this->set("Message_existe","Situacion de solicitud actualizada con exito");
						               $this->set("numero_salida",$codigo);
							         }else{
								        $this->set("errorMessage","Por favor Verifique el n&uacute;mero de control de salidas");
								        $this->set("numero_salida","");
							      }
					      }else{
					      	 $this->set("errorMessage","debe registrar nuevos números para el control de salidas de este almacén");
					      	 $this->set("numero_salida","");
			 			 }

			      }

	}else{
		$this->set('almacen','');
		$this->set('deno_almacen','');
		$this->set('ubicacion','');
		$this->set('readonly','');
		$this->Session->delete('cod_almacen');
		$this->set("numero_salida",0);

	}

	 ////////////////////////////aqui la parte de la dependencia/////////////////////////////////////


		  $sql="select
				distinct
				a.cod_dep,
				(select b.denominacion from arrd05 b where b.cod_presi='$cod_presi' and b.cod_entidad='$cod_entidad' and b.cod_tipo_inst='$cod_tipo_inst' and b.cod_inst='$cod_inst' and b.cod_dep=a.cod_dep) as denominacion
				from ciad01_inventario_almacen a where a.cod_presi='$cod_presi' and a.cod_entidad='$cod_entidad' and a.cod_tipo_inst='$cod_tipo_inst' and a.cod_inst='$cod_inst'";

				$datos  = $this->ciad01_inventario_almacen->execute($sql);
				if(count($datos)!=0){
				foreach($datos as $n){
					$lista[$n[0]['cod_dep']]=mascara($n[0]['cod_dep'],3)." - ".$n[0]['denominacion'];
			    }
			}else{
				$lista=array();
			}
			$this->set("lista_dependencia", $lista);
			$this->set("cod_dependencia", $cod_dep);

			$dep  = $this->ciad01_inventario_almacen->execute("select denominacion from arrd05 where ".$this->SQLCA());
			$this->set("dependencia", $dep[0][0]['denominacion']);

}




function retorna_numero($almacen=null,$num_rc=null,$var=null){
	$this->layout="ajax";
	$ano=$this->ano_ejecucion();
	if(isset($num_rc) && $num_rc!=0){
		$resultado=$this->ciad01_inventario_salidas_numero->execute("UPDATE  ciad01_inventario_salidas_numero SET situacion=1 WHERE ".$this->SQLCA()." and cod_almacen_salida=".$almacen." and numero_orden_salida=".$num_rc." and ano_orden_salida=".$ano." and situacion=2");
	}
}



function denominacion($var=null,$var2=null,$var3=null){
	$this->layout = "ajax";
	if($var2!=''){
		if($var==1){
				$datos  = $this->ciad01_inventario_almacen->execute("select denominacion from arrd05 where ".$this->condicionNDEP()." and cod_dep=".$var2);
		}else if($var==2){
				$datos  = $this->v_cscd01_snc_grupo->execute(" SELECT denominacion FROM v_cscd01_snc_grupo where  cod_grupo_5='$var2'  ORDER BY cod_grupo_5 ASC");
		}else if($var==3){
				$datos  = $this->v_cscd01_snc_grupo->execute(" SELECT denominacion_snc FROM v_cscd01_catalogo_con_snc_denominacion where  cod_snc='$var2'  ORDER BY cod_snc ASC");
		}else if($var==4){
				$datos  = $this->v_cscd01_snc_grupo->execute(" SELECT denominacion FROM v_cscd01_catalogo_deno_und where  codigo_prod_serv='$var2'  ORDER BY codigo_prod_serv ASC");
		}else if($var==5){
				$this->Session->delete('cod_almacen');
				$this->Session->write('cod_almacen',$var3);
				$datos  = $this->ciad01_inventario_almacen->execute(" SELECT denominacion FROM ciad01_inventario_almacen where  ".$this->condicionNDEP()." and cod_dep='$var2' and cod_almacen='$var3'  ORDER BY cod_almacen ASC");
		}

		$this->set('datos',$datos);
		$this->set('var',$var);

	}else{
		$this->set('datos',null);
		$this->set('var',$var);

	}

}


function denominacion1($var2=null,$var3=null){
	$this->layout = "ajax";
	if($var3!=''){
		$datos  = $this->ciad01_inventario_almacen->execute(" SELECT denominacion FROM ciad01_inventario_almacen where  ".$this->condicionNDEP()." and cod_dep='$var2' and cod_almacen='$var3'  ORDER BY cod_almacen ASC");
		$this->set('datos',$datos[0][0]['denominacion']);
	}else{
		$this->set('datos','');
	}

}



function select($var=null){
	$this->layout = "ajax";
	if($var!=''){
		$this->set("cod_dependencia", $var);

		$datos=$this->ciad01_inventario_almacen->generateList($this->condicionNDEP()." and cod_dep=".$var,'cod_almacen ASC', null, '{n}.ciad01_inventario_almacen.cod_almacen', '{n}.ciad01_inventario_almacen.denominacion');
		if($datos!=null){
			$this->concatena($datos,'almacenes');
		}else{
			$this->set('almacenes',array());
		}
	}else{
		$this->set('almacenes',array());
		$this->set("cod_dependencia", '');

	}

}



function guardar(){
	$this->layout = "ajax";
	$cod_presi 					= $this->Session->read('SScodpresi');
	$cod_entidad 				= $this->Session->read('SScodentidad');
	$cod_tipo_inst 				= $this->Session->read('SScodtipoinst');
	$cod_inst 					= $this->Session->read('SScodinst');
	$cod_dep 					= $this->Session->read('SScoddep');

	if(empty($this->data['ciap01']['cod_almacen'])){
		$this->set('errorMessage', 'debe seleccionar el código del almacén');

	}else if(empty($this->data['ciap01']['ano'])){
		$this->set('errorMessage', 'debe verificar el año');

	}else if(empty($this->data['ciap01']['numero_salida'])){
		$this->set('errorMessage', 'debe verificar el control de números de salidas para el almacén');

	}else if(empty($this->data['ciap01']['fecha_salida'])){
		$this->set('errorMessage', 'debe ingresar la fecha de salida');

	}else if(empty($this->data['ciap01']['dependencia'])){
		$this->set('errorMessage', 'debe seleccionar la dependencia receptora de los productos o materiales');

	}else if(empty($this->data['ciap01']['almacen_receptor'])){
		$this->set('errorMessage', 'debe seleccionar el almacén receptor de los productos o materiales');

	}else if(!isset($_SESSION ["items1"]) || ($_SESSION ["items1"]==null)){
		$this->set('errorMessage', 'debe agregar los productos de salida ');

	}else if(empty($this->data['ciap01']['titular'])){
		$this->set('errorMessage', 'Ingrese el nombre de la persona titular');

	}else if(empty($this->data['ciap01']['ci_titular'])){
		$this->set('errorMessage', 'Ingrese la cedula de identidad de la persona titular');

	}else if(empty($this->data['ciap01']['beneficiario'])){
		$this->set('errorMessage', 'Ingrese el nombre de la persona beneficiaria');

	}else if(empty($this->data['ciap01']['ci_beneficiario'])){
		$this->set('errorMessage', 'Ingrese la cedula de identidad de la persona beneficiaria');

	}else if(empty($this->data['ciap01']['entregado_por'])){
		$this->set('errorMessage', 'Ingrese el nombre de la persona que entrega');

	}else if(empty($this->data['ciap01']['ci_entregado'])){
		$this->set('errorMessage', 'Ingrese la cedula de identidad de la persona que entrega');

	}else if(empty($this->data['ciap01']['recibido_por'])){
		$this->set('errorMessage', 'Ingrese el nombre de la persona que recibe el producto');

	}else if(empty($this->data['ciap01']['ci_recibido'])){
		$this->set('errorMessage', 'Ingrese la cedula de identidad de la persona que recibe el producto');

	}else if(empty($this->data['ciap01']['acta_entrega'])){
		$this->set('errorMessage', 'seleccione si desea emitir o no el acta de entrega');

	}else{
		$cod_almacen=$this->data['ciap01']['cod_almacen'];
		$ano=$this->ano_ejecucion();
		$numero_salida=$this->data['ciap01']['numero_salida'];
		$fecha_salida=$this->data['ciap01']['fecha_salida'];
		$dependencia_receptora=$this->data['ciap01']['dependencia'];
		$almacen_receptor=$this->data['ciap01']['almacen_receptor'];

		$this->set('emitir',$this->data['ciap01']['acta_entrega']);
		$this->set('cod_almacen',$cod_almacen);
		$this->set('ano',$ano);
		$this->set('numero_salida',$numero_salida);

		if(empty($this->data['ciap01']['observaciones'])){
			$observaciones='';
		}else{
			$observaciones=$this->data['ciap01']['observaciones'];
		}

		/*
		if(empty($this->data['ciap01']['recibido_por'])){
			$recibido_por=0;
		}else{
			$recibido_por=$this->data['ciap01']['recibido_por'];
		}

		if(empty($this->data['ciap01']['cedula'])){
			$cedula=0;
		}else{
			$cedula=$this->data['ciap01']['cedula'];
		}
		*/

		$hora_salida	= date("h:i a");
		$titular 		= $this->data['ciap01']['titular'];
		$ci_titular 	= $this->data['ciap01']['ci_titular'];
		$beneficiario 	= $this->data['ciap01']['beneficiario'];
		$ci_beneficiario= $this->data['ciap01']['ci_beneficiario'];
		$entregado_por 	= $this->data['ciap01']['entregado_por'];
		$ci_entregado 	= $this->data['ciap01']['ci_entregado'];
		$recibido_por 	= $this->data['ciap01']['recibido_por'];
		$ci_recibido 	= $this->data['ciap01']['ci_recibido'];

		$fecha_registro=date('Y-m-d');
		$usuario_registro=$_SESSION['nom_usuario'];

		//$SQL_INSERT ="BEGIN;INSERT INTO ciad01_inventario_salidas_cuerpo VALUES ('$cod_presi', '$cod_entidad' ,'$cod_tipo_inst' ,'$cod_inst' ,'$cod_dep' ,'$cod_almacen','$ano','$numero_salida','$fecha_salida','$observaciones','1','$fecha_registro','$usuario_registro','1900-01-01','0','$dependencia_receptora','$almacen_receptor','0','0','2','$recibido_por','$cedula')";
		$SQL_INSERT ="BEGIN;INSERT INTO ciad01_inventario_salidas_cuerpo VALUES ('$cod_presi', '$cod_entidad' ,'$cod_tipo_inst' ,'$cod_inst' ,'$cod_dep' ,'$cod_almacen','$ano','$numero_salida','$fecha_salida','$observaciones','1','$fecha_registro','$usuario_registro','1900-01-01','0','$dependencia_receptora','$almacen_receptor','0','0','2','$hora_salida','$titular','$ci_titular','$beneficiario','$ci_beneficiario','$entregado_por','$ci_entregado','$recibido_por','$ci_recibido')";
		$sw = $this->ciad01_inventario_salidas_cuerpo->execute($SQL_INSERT);
		 foreach($_SESSION ["items1"] as $guardar){
			if($guardar!=null){
				$guardar[2]=str_replace(',', '.', $guardar[2]);
				$sql_insert1 = "INSERT INTO ciad01_inventario_salidas_detalles VALUES('$cod_presi','$cod_entidad','$cod_tipo_inst','$cod_inst','$cod_dep','$cod_almacen', '$ano','$numero_salida','$guardar[0]','$guardar[2]')";
				$sw2 = $this->ciad01_inventario_salidas_detalles->execute($sql_insert1);

			$sql=$this->ciad01_inventario_productos->execute(" SELECT * from ciad01_inventario_productos where ".$this->SQLCA()." and cod_almacen=".$cod_almacen." and cod_prod_serv=".$guardar[0]);
			$cantidad=($sql[0][0]['numero_salidas']+$guardar[2]);
			$this->ciad01_inventario_productos->execute("update ciad01_inventario_productos set numero_salidas='$cantidad' where ".$this->SQLCA()." and cod_almacen=".$cod_almacen." and cod_prod_serv=".$guardar[0]);
			}
	     }
		$this->ciad01_inventario_salidas_cuerpo->execute("update ciad01_inventario_salidas_numero set situacion=3 where ".$this->SQLCA()." and ano_orden_salida='$ano' and cod_almacen_salida='$cod_almacen' and numero_orden_salida='$numero_salida' and situacion=2");
		if($sw>1 && $sw2>1){
			$this->ciad01_inventario_salidas_cuerpo->execute("COMMIT");
			$this->set('Message_existe', 'REGISTRO EXITOSO');
	 		$this->set('guardado', 'si');

		}else{
			$this->ciad01_inventario_salidas_cuerpo->execute("ROLLBACK");
			$this->set('errorMessage', 'INSERCI&Oacute;N FALLIDA DE LOS DATOS');
		}


	}

}


function vacio($var=null){
	$this->layout="ajax";

	if($var!=''){
		$this->set('disabled','');
	}else{
		$this->set('disabled','disabled');
	}

}


function buscar_producto($var1=null){
	$this->layout="ajax";
	$this->set("opcion",$var1);
	$this->Session->delete('pista');
}//fin function



function buscar_por_pista($var1=null, $var2=null, $var3=null){
$this->layout="ajax";

    if($var3==null){$var2 = strtoupper($var2);
					$this->Session->write('pista', $var2);
					if(is_numeric($var2)){  $sql   = " (cod_prod_serv::text LIKE '%$var2%')  or   ";}else{ $sql = ""; }
					$Tfilas=$this->v_ciad01_inventario_productos->findCount($this->SQLCA()." and cod_almacen=".$_SESSION['cod_almacen']." and ".$sql." (mayus_acentos(denominacion)  LIKE mayus_acentos('%$var2%') )  OR  (cod_snc LIKE '%$var2%')   ");
					        if($Tfilas!=0){
					        	$pagina=1;
					        	$Tfilas=(int)ceil($Tfilas/100);
					        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
								$this->set('total_paginas',$Tfilas);
								$this->set('pagina_actual',$pagina);
								$this->set('ultimo',$Tfilas);
					     	    $datos_filas=$this->v_ciad01_inventario_productos->findAll($this->SQLCA()." and cod_almacen=".$_SESSION['cod_almacen']." and ".$sql." (mayus_acentos(denominacion)  LIKE mayus_acentos('%$var2%') )  OR  (cod_snc LIKE '%$var2%')   ",null,"cod_prod_serv ASC",100,1,null);
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
						if(is_numeric($var22)){ $sql   = " (cod_prod_serv::text LIKE '%$var22%')  or   ";}else{ $sql = "";}
						$Tfilas=$this->v_ciad01_inventario_productos->findCount($this->SQLCA()." and cod_almacen=".$_SESSION['cod_almacen']." and ".$sql." (mayus_acentos(denominacion)  LIKE mayus_acentos('%$var22%') )  OR  (cod_snc LIKE '%$var22%')   ");
						        if($Tfilas!=0){
						        	$pagina=$var3;
						        	$Tfilas=(int)ceil($Tfilas/100);
						        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
									$this->set('total_paginas',$Tfilas);
									$this->set('pagina_actual',$pagina);
									$this->set('ultimo',$Tfilas);
						     	    $datos_filas=$this->v_ciad01_inventario_productos->findAll($this->SQLCA()." and cod_almacen=".$_SESSION['cod_almacen']." and ".$sql." (mayus_acentos(denominacion)  LIKE mayus_acentos('%$var22%') )  OR  (cod_snc LIKE '%$var22%')   ",null,"cod_prod_serv ASC",100,$pagina,null);
							        $this->set("datosFILAS",$datos_filas);
							        $this->set('siguiente',$pagina+1);
									$this->set('anterior',$pagina-1);
									$this->bt_nav($Tfilas,$pagina);
						          }else{
						        	$this->set("datosFILAS",'');
						          }
                 }//fin else

		$this->set("cscd01_unidad_medida",$this->cscd01_unidad_medida->findAll());
		$this->set("opcion",$var1);
}//fin function





function seleccion_busqueda($var1=null, $var2=null, $var3=null){
	$this->layout="ajax";
 	$sql=" SELECT * from v_ciad01_inventario_productos where ".$this->SQLCA()." and cod_prod_serv=".$var2." and cod_almacen=".$var3;

	$datos=$this->ciad01_inventario_productos->execute($sql);
	$this->set('datos2',$datos);

	$this->set("cscd01_unidad_medida",$this->cscd01_unidad_medida->findAll());
}//fin function




function muestra_detalles($producto=null,$almacen=null){
	$this->layout="ajax";
	$sql=" SELECT
			 c.cod_presi,
			 c.cod_entidad,
			 c.cod_tipo_inst,
			 c.cod_inst,
			 c.cod_dep,
			 c.cod_almacen,
			 c.cod_prod_serv,
			 c.stock_maximo,
			 c.stock_minimo,
			 c.punto_pedido,
			 c.estante_numero,
			 c.fila_numero,
			 c.columna_numero,
			 c.complemento_sitio_almacenaje,
			 c.numero_entradas,
			 c.numero_salidas,
			 c.costo_maximo,
			 c.costo_minimo,
			 c.fecha_registro,
			 c.username_registro,
			 a.denominacion AS denominacion_producto,
			 a.cod_snc,
			 a.denominacion_snc,
			 substr(a.cod_snc::text, 1, 3) AS cod_grupo,
			 ( SELECT b.denominacion FROM v_cscd01_snc_grupo b WHERE b.cod_grupo::text = substr(a.cod_snc::text, 1, 3)) AS denominacion_grupo,
			  ( SELECT b.descripcion FROM v_cscd01_snc_grupo b WHERE b.cod_grupo::text = substr(a.cod_snc::text, 1, 3)) AS descripcion_grupo,
			  substr(a.cod_snc::text, 1, 5) AS cod_subgrupo,
			  ( SELECT b.denominacion FROM v_cscd01_snc_grupo b WHERE b.cod_grupo::text = substr(a.cod_snc::text, 1, 5)) AS denominacion_subgrupo,
			  ( SELECT b.descripcion FROM v_cscd01_snc_grupo b WHERE b.cod_grupo::text = substr(a.cod_snc::text, 1, 5)) AS descripcion_subgrupo,
			  ( SELECT b.denominacion FROM ciad01_inventario_almacen b WHERE b.cod_presi = c.cod_presi AND b.cod_entidad = c.cod_entidad AND b.cod_tipo_inst = c.cod_tipo_inst AND b.cod_inst = c.cod_inst AND b.cod_dep = c.cod_dep AND b.cod_almacen = c.cod_almacen) AS denominacion_almacen,
			  ( SELECT b.ubicacion FROM ciad01_inventario_almacen b WHERE b.cod_presi = c.cod_presi AND b.cod_entidad = c.cod_entidad AND b.cod_tipo_inst = c.cod_tipo_inst AND b.cod_inst = c.cod_inst AND b.cod_dep = c.cod_dep AND b.cod_almacen = c.cod_almacen) AS ubicacion_almacen,
			  ( SELECT b.principal_secundario FROM ciad01_inventario_almacen b WHERE b.cod_presi = c.cod_presi AND b.cod_entidad = c.cod_entidad AND b.cod_tipo_inst = c.cod_tipo_inst AND b.cod_inst = c.cod_inst AND b.cod_dep = c.cod_dep AND b.cod_almacen = c.cod_almacen) AS principal_secundario,
			  ( SELECT b.responsable_almacen FROM ciad01_inventario_almacen b WHERE b.cod_presi = c.cod_presi AND b.cod_entidad = c.cod_entidad AND b.cod_tipo_inst = c.cod_tipo_inst AND b.cod_inst = c.cod_inst AND b.cod_dep = c.cod_dep AND b.cod_almacen = c.cod_almacen) AS responsable_almacen,
			  ( SELECT b.cedula_identidad FROM ciad01_inventario_almacen b WHERE b.cod_presi = c.cod_presi AND b.cod_entidad = c.cod_entidad AND b.cod_tipo_inst = c.cod_tipo_inst AND b.cod_inst = c.cod_inst AND b.cod_dep = c.cod_dep AND b.cod_almacen = c.cod_almacen) AS cedula_identidad,
			  ( SELECT b.seleccionar_productos FROM ciad01_inventario_almacen b WHERE b.cod_presi = c.cod_presi AND b.cod_entidad = c.cod_entidad AND b.cod_tipo_inst = c.cod_tipo_inst AND b.cod_inst = c.cod_inst AND b.cod_dep = c.cod_dep AND b.cod_almacen = c.cod_almacen) AS seleccionar_productos
			   FROM v_cscd01_catalogo_con_snc_denominacion a, ciad01_inventario_productos c
			  WHERE ".$this->SQLCA()." and c.cod_prod_serv = a.codigo_prod_serv and c.cod_prod_serv=".$producto." and c.cod_almacen=".$almacen;

	$datos=$this->v_ciad01_inventario_productos->execute($sql);
	$this->set('datos',$datos);


}



function verifica_cantidad($producto=null,$cantidad=null){
		$this->layout="ajax";
		$sql=" SELECT * from ciad01_inventario_productos where ".$this->SQLCA()." and cod_almacen=".$_SESSION['cod_almacen']." and cod_prod_serv=".$producto ;
		$datos=$this->ciad01_inventario_productos->execute($sql);
		$resto=($datos[0][0]['numero_entradas']-$datos[0][0]['numero_salidas']);
		$cantidad=str_replace(',', '.', $cantidad);
		if($cantidad<=$resto){
			$this->set('si','');
			return 'si';
		}else{
			$this->set('no','');
			$this->set('errorMessage', 'el producto no esta disponible en la cantidad ingresada, intente con una cantidad menor ');
			return 'no';
		}



}


function agregar_producto($var=null) {
	$this->layout="ajax";

	if(empty($this->data['ciap01']['cantidad'])){
		$this->set('errorMessage', 'Debe ingresar la cantidad');
		$this->set('no','');
		if(!isset($_SESSION["contador1"])){
 			$this->set('vacio','');
 		}
		return;
	}

	    $producto=$this->data['ciap01']['cod_producto'];
	    $deno_producto=$this->data['ciap01']['deno_producto'];
	    $cantidad=$this->data['ciap01']['cantidad'];

	    $ver=$this->verifica_cantidad($producto,$cantidad);
	    if($ver=='no'){
	    	$this->set('errorMessage', 'el producto no esta disponible en la cantidad ingresada, intente con una cantidad menor ');
			$this->set('no','');
			if(!isset($_SESSION["contador1"])){
	 			$this->set('vacio','');
	 		}
			return;

	    }


	if(isset($_SESSION["contador1"])){
        $_SESSION["contador1"]=$_SESSION["contador1"]+1;
	}else{
		$_SESSION["contador1"]=1;
	}

	if(isset($var) && !empty($var)){

			$cod[0]=$producto;
			$cod[1]=$deno_producto;
			$cod[2]=$cantidad;

		    if(isset($_SESSION["i1"])){
				$i=$this->Session->read("i1")+1;
				$this->Session->write("i1",$i);
	   		 }else{
			   $this->Session->write("i1",0);
				$i=0;
			}
        switch($var){
        	case 'normal':
				     $vec[$i][0]=$producto;
				     $vec[$i][1]=$deno_producto;
				     $vec[$i][2]=$cantidad;
					 $vec[$i]["id"]=$i;
					if(isset($_SESSION["items1"])){
						foreach($_SESSION["items1"] as $codi){
            	           if($codi[0]==$cod[0]){
                              $est=true;
                              break;
            	          }else{
            	          	 $est=false;
            	          }
                        }//fin foreach
                        if($est==true){
            	          	$i=$this->Session->read("i1")-1;
				            $this->Session->write("i1",$i);
				            $this->set('errorMessage', 'Este producto ya existe en la lista');
                        }else{
                        	$_SESSION["items1"]=$_SESSION["items1"]+$vec;
                        }
					 }else{
						$_SESSION["items1"]=$vec;
					 }

        	break;

        }//fin switch
		}//

		echo "<script>";
			echo "document.getElementById('agregar').disabled='disabled';";
		echo "</script>";
}//fin funcu¡ions



function limpiar_lista() {
	$this->layout = "ajax";
	$this->Session->delete("items1");
	$this->Session->delete("i1");
	$this->Session->delete("contador1");
}


function eliminar_items ($id) {
	$this->layout = "ajax";
	$_SESSION["items1"][$id]=null;
    $_SESSION["contador1"]=$_SESSION["contador1"]-1;
}



function pre_consulta(){
	$this->layout = "ajax";
	$ano=$this->ano_ejecucion();
	$this->set('ano',$ano);
	$datos  = $this->ciad01_inventario_salidas_cuerpo->execute(" SELECT DISTINCT ano_orden_salida FROM ciad01_inventario_salidas_cuerpo WHERE ".$this->SQLCA()." and cod_almacen_salida=".$_SESSION['cod_almacen']." ORDER BY ano_orden_salida ASC");
	if(count($datos)!=0){
		foreach($datos as $n){
			$lista[$n[0]['ano_orden_salida']]=$n[0]['ano_orden_salida'];
	    }
	}else{
		$lista=array();
	}
	$this->set("lista_ano", $lista);

	$num=$this->ciad01_inventario_salidas_cuerpo->generateList($this->SQLCA()." and cod_almacen_salida=".$_SESSION['cod_almacen']." and ano_orden_salida=".$ano,'numero_orden_salida ASC', null, '{n}.ciad01_inventario_salidas_cuerpo.numero_orden_salida', '{n}.ciad01_inventario_salidas_cuerpo.beneficiario');
	if($num!=null){
		$this->concatena_seis($num,'numero');
	}else{
		$this->set('numero',null);
	}

}



function numero($var=null){
	$this->layout = "ajax";
	if($var!=''){
		$num=$this->ciad01_inventario_salidas_cuerpo->generateList($this->SQLCA()." and cod_almacen_salida=".$_SESSION['cod_almacen']." and ano_orden_salida=".$var,'numero_orden_salida ASC', null, '{n}.ciad01_inventario_salidas_cuerpo.numero_orden_salida', '{n}.ciad01_inventario_salidas_cuerpo.beneficiario');
		if($num!=null){
			$this->concatena_seis($num,'numero');
		}else{
			$this->set('numero',null);
		}

	}else{
		$this->set('numero',null);

	}

}




function eliminar($ano=null,$almacen=null,$numero=null,$pagina=null){
	$this->layout="ajax";

	$a=$this->ciad01_inventario_productos->execute("BEGIN;update ciad01_inventario_salidas_cuerpo set condicion_actividad=2,fecha_anulacion='".date('Y-m-d')."',username_anulacion='".$_SESSION['nom_usuario']."' where ".$this->SQLCA()." and cod_almacen_salida=".$almacen." and ano_orden_salida=".$ano." and numero_orden_salida=".$numero);
	$sql=$this->ciad01_inventario_salidas_detalles->execute(" SELECT * from ciad01_inventario_salidas_detalles where ".$this->SQLCA()." and cod_almacen_salida=".$almacen." and ano_orden_salida='$ano' and numero_orden_salida=".$numero);
	for($i=0;$i<count($sql);$i++){
		$ver=$this->ciad01_inventario_productos->execute(" SELECT * from ciad01_inventario_productos where ".$this->SQLCA()." and cod_almacen=".$sql[$i][0]['cod_almacen_salida']." and cod_prod_serv=".$sql[$i][0]['codigo_prod_serv']);
		$cantidad=($ver[0][0]['numero_salidas']-$sql[$i][0]['cantidad']);

		$b=$this->ciad01_inventario_productos->execute("update ciad01_inventario_productos set numero_salidas='$cantidad' where ".$this->SQLCA()." and cod_almacen=".$sql[$i][0]['cod_almacen_salida']." and cod_prod_serv=".$sql[$i][0]['codigo_prod_serv']);

	}

	$resultado=$this->ciad01_inventario_productos->execute("UPDATE  ciad01_inventario_salidas_numero SET situacion=4 WHERE ".$this->SQLCA()." and cod_almacen_salida=".$almacen." and numero_orden_salida=".$numero." and ano_orden_salida=".$ano);

	if($a>0 && $b>0){
		$this->ciad01_inventario_salidas_cuerpo->execute("COMMIT");
		$this->set('Message_existe', 'REGISTRO ANULADO');
	}else{
		$this->ciad01_inventario_salidas_cuerpo->execute("ROLLBACK");
		$this->set('errorMessage', 'NO SE PUDO ELIMINAR ');
	}

	$this->pre_consulta();
	$this->render('pre_consulta');


}



function concatena_seis_digitos($vector1=null, $nomVar=null, $extra=null){
	$cod = array();
	if($vector1 != null){
		foreach($vector1 as $x => $y){
			if($extra!=null){

				if($x<10){
					$cod[$x] = $extra.'.'.'00000'.$x.' - '.$y;
				}else if($x>=10 && $x<=99){
					$cod[$x] = $extra.'.'.'0000'.$x.' - '.$y;
				}else if($x>=100 && $x<=999){
					$cod[$x] = $extra.'.'.'000'.$x.' - '.$y;
				}else if($x>=1000 && $x<=9999){
					$cod[$x] = $extra.'.'.'00'.$x.' - '.$y;
				}else if($x>=10000 && $x<=99999){
					$cod[$x] = $extra.'.'.'0'.$x.' - '.$y;
				}

			}else{
				if($x<10){
					$cod[$x] = '00000'.''.$x;
				}else if($x>=10 && $x<=99){
					$cod[$x] = '0000'.''.$x;
				}else if($x>=99 && $x<=999){
					$cod[$x] = '000'.''.$x;
				}else if($x>=999 && $x<=9999){
					$cod[$x] = '00'.''.$x;
				}else if($x>=9999 && $x<=99999){
					$cod[$x] = '0'.''.$x;
				}

			}
		}
	}

	$this->set($nomVar, $cod);
}//fin function



function consultar($ano=null,$pagina=null,$numero=null){//echo 'si llego';
 		$this->layout = "ajax";
		$cod_presi 					= $this->Session->read('SScodpresi');
		$cod_entidad 				= $this->Session->read('SScodentidad');
		$cod_tipo_inst 				= $this->Session->read('SScodtipoinst');
		$cod_inst 					= $this->Session->read('SScodinst');
		$cod_dep 					= $this->Session->read('SScoddep');

 	if(!empty($this->data['ciap01']['ano'])){
 		$sql=" and ano_orden_salida=".$this->data['ciap01']['ano'];
		$ano=$this->data['ciap01']['ano'];
 		if(!empty($this->data['ciap01']['numero'])){
	 		$sql.=" and numero_orden_salida=".$this->data['ciap01']['numero'];
	 	}
 	}else{
 		$sql=" and ano_orden_salida=".$ano;
 		if(isset($numero)){
			$sql.=" and numero_orden_salida=".$numero;
 		}
 	}

 	$almacen=$_SESSION['cod_almacen'];

	if(isset($pagina)){
		$Tfilas=$this->ciad01_inventario_salidas_cuerpo->findCount($this->SQLCA()." and cod_almacen_salida=".$almacen.$sql);
        if($Tfilas!=0){
        	$x=$this->ciad01_inventario_salidas_cuerpo->findAll($this->SQLCA()." and cod_almacen_salida=".$almacen.$sql,null,"numero_orden_salida ASC",1,$pagina,null);

            $this->set('DATA',$x);
            $this->set('pagina',$pagina);
          	$this->set('siguiente',$pagina+1);
          	$this->set('anterior',$pagina-1);
            $this->bt_nav($Tfilas,$pagina);
            $this->set('numT',$Tfilas);
			$this->set('numP',$pagina);
        }else{
	 	       $this->set('errorMessage', 'No se encontrar&oacute;n datos');
	 	       $this->set('noExiste',true);
	 	       $this->index('autor_valido',true);
			   $this->render("index");
			   return;
        }

	}else{
		$pagina=1;
		$Tfilas=$this->ciad01_inventario_salidas_cuerpo->findCount($this->SQLCA()." and cod_almacen_salida=".$almacen.$sql);

        if($Tfilas!=0){
        	$x=$this->ciad01_inventario_salidas_cuerpo->findAll($this->SQLCA()." and cod_almacen_salida=".$almacen.$sql,null,"numero_orden_salida ASC",1,$pagina,null);
			$this->set('DATA',$x);
			$this->set('pagina',$pagina);
          	$this->set('siguiente',$pagina+1);
          	$this->set('anterior',$pagina-1);
          	$this->bt_nav($Tfilas,$pagina);
          	$this->set('numT',$Tfilas);
			$this->set('numP',$pagina);

        }else{
	 	       $this->set('errorMessage', 'No se encontrar&oacute;n datos');
	 	       $this->set('noExiste',true);
	 	       $this->index('autor_valido',true);
			   $this->render("index");
			   return;
        }
	}

	$a1="select * from ciad01_inventario_salidas_cuerpo where ".$this->SQLCA()." and ano_orden_salida=".$ano." and cod_almacen_salida=".$almacen." and numero_orden_salida=".$x[0]["ciad01_inventario_salidas_cuerpo"]["numero_orden_salida"];

	$a2="select
		a.cod_presi,
		a.cod_entidad,
		a.cod_tipo_inst,
		a.cod_inst,
		a.cod_dep,
		a.cod_almacen_salida,
		a.codigo_prod_serv,
		a.cantidad,
		b.denominacion,
		b.cod_medida
		from ciad01_inventario_salidas_detalles a,v_ciad01_inventario_productos b
		where
		a.cod_presi=b.cod_presi and a.cod_entidad=b.cod_entidad and a.cod_tipo_inst=b.cod_tipo_inst and a.cod_inst=b.cod_inst and a.cod_dep=b.cod_dep and a.codigo_prod_serv=b.cod_prod_serv and
		a.cod_almacen_salida=b.cod_almacen and a.cod_presi='$cod_presi' and a.cod_entidad='$cod_entidad' and a.cod_tipo_inst='$cod_tipo_inst' and a.cod_inst='$cod_inst' and a.cod_dep='$cod_dep' and a.ano_orden_salida=".$ano." and a.cod_almacen_salida=".$almacen." and a.numero_orden_salida=".$x[0]["ciad01_inventario_salidas_cuerpo"]["numero_orden_salida"];

	$datos=$this->ciad01_inventario_salidas_cuerpo->execute($a1);
	$datos2=$this->ciad01_inventario_salidas_detalles->execute($a2);
	$this->set('datos',$datos);
	$this->set('datos2',$datos2);

	$a=$this->ciad01_inventario_almacen->execute("select denominacion from ciad01_inventario_almacen where ".$this->SQLCA()." and cod_almacen=".$almacen);
	$b=$this->ciad01_inventario_almacen->execute("select denominacion from ciad01_inventario_almacen where ".$this->condicionNDEP()." and cod_dep=".$x[0]["ciad01_inventario_salidas_cuerpo"]["cod_dep_receptora"]." and cod_almacen=".$x[0]["ciad01_inventario_salidas_cuerpo"]["cod_almacen_receptor"]);
	$this->set('deno_almacen1',$a[0][0]['denominacion']);
	$this->set('deno_almacen2',$b[0][0]['denominacion']);

	$dep  = $this->ciad01_inventario_almacen->execute("select denominacion from arrd05 where ".$this->condicionNDEP()." and cod_dep=".$x[0]["ciad01_inventario_salidas_cuerpo"]["cod_dep_receptora"]);
	$this->set("dependencia", $dep[0][0]['denominacion']);


	$this->set("cscd01_unidad_medida",$this->cscd01_unidad_medida->findAll());

 }//consultar




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




function planilla($cod_almacen=null,$ano=null,$numero=null){
	$this->layout="ajax";

	if(!isset($cod_almacen)){
		$cod_almacen=$this->data['planilla']['cod_almacen'];
		$ano=$this->data['planilla']['ano'];
		$numero=$this->data['planilla']['numero_salida'];
	}

	$sql="SELECT
		a.cod_presi,
		a.cod_entidad,
		a.cod_tipo_inst,
		a.cod_inst,
		a.cod_dep,
		a.cod_almacen_salida,
		a.ano_orden_salida,
		a.numero_orden_salida,
		a.codigo_prod_serv,
		a.cantidad,
		(select b.denominacion from v_ciad01_inventario_productos b where b.cod_presi=a.cod_presi and b.cod_entidad=a.cod_entidad and b.cod_tipo_inst=a.cod_tipo_inst and b.cod_inst=a.cod_inst and b.cod_dep=a.cod_dep and b.cod_prod_serv=a.codigo_prod_serv and b.cod_almacen=a.cod_almacen_salida) as denominacion,
		(select b.observaciones from ciad01_inventario_salidas_cuerpo b where b.cod_presi=a.cod_presi and b.cod_entidad=a.cod_entidad and b.cod_tipo_inst=a.cod_tipo_inst and b.cod_inst=a.cod_inst and b.cod_dep=a.cod_dep and b.cod_almacen_salida=a.cod_almacen_salida and b.ano_orden_salida=a.ano_orden_salida and b.numero_orden_salida=a.numero_orden_salida) as observaciones,
		(select b.fecha_orden_salida from ciad01_inventario_salidas_cuerpo b where b.cod_presi=a.cod_presi and b.cod_entidad=a.cod_entidad and b.cod_tipo_inst=a.cod_tipo_inst and b.cod_inst=a.cod_inst and b.cod_dep=a.cod_dep and b.cod_almacen_salida=a.cod_almacen_salida and b.ano_orden_salida=a.ano_orden_salida and b.numero_orden_salida=a.numero_orden_salida) as fecha_salida,
		(select b.recibido_por from ciad01_inventario_salidas_cuerpo b where b.cod_presi=a.cod_presi and b.cod_entidad=a.cod_entidad and b.cod_tipo_inst=a.cod_tipo_inst and b.cod_inst=a.cod_inst and b.cod_dep=a.cod_dep and b.cod_almacen_salida=a.cod_almacen_salida and b.ano_orden_salida=a.ano_orden_salida and b.numero_orden_salida=a.numero_orden_salida) as recibido_por,
		(select b.cedula_identidad from ciad01_inventario_salidas_cuerpo b where b.cod_presi=a.cod_presi and b.cod_entidad=a.cod_entidad and b.cod_tipo_inst=a.cod_tipo_inst and b.cod_inst=a.cod_inst and b.cod_dep=a.cod_dep and b.cod_almacen_salida=a.cod_almacen_salida and b.ano_orden_salida=a.ano_orden_salida and b.numero_orden_salida=a.numero_orden_salida) as cedula_identidad,
		(select b.denominacion from ciad01_inventario_almacen b where b.cod_presi=a.cod_presi and b.cod_entidad=a.cod_entidad and b.cod_tipo_inst=a.cod_tipo_inst and b.cod_inst=a.cod_inst and b.cod_dep=a.cod_dep and b.cod_almacen=a.cod_almacen_salida) as almacen_deposito,
		(select b.denominacion from ciad01_inventario_almacen b where b.cod_presi=a.cod_presi and b.cod_entidad=a.cod_entidad and b.cod_tipo_inst=a.cod_tipo_inst and b.cod_inst=a.cod_inst and b.cod_dep=a.cod_dep and b.cod_almacen=(select b.cod_almacen_receptor from ciad01_inventario_salidas_cuerpo b where b.cod_presi=a.cod_presi and b.cod_entidad=a.cod_entidad and b.cod_tipo_inst=a.cod_tipo_inst and b.cod_inst=a.cod_inst and b.cod_dep=a.cod_dep and b.cod_almacen_salida=a.cod_almacen_salida and b.ano_orden_salida=a.ano_orden_salida and b.numero_orden_salida=a.numero_orden_salida)) as almacen_receptor,
		(select b.username_registro from ciad01_inventario_salidas_cuerpo b where b.cod_presi=a.cod_presi and b.cod_entidad=a.cod_entidad and b.cod_tipo_inst=a.cod_tipo_inst and b.cod_inst=a.cod_inst and b.cod_dep=a.cod_dep and b.cod_almacen_salida=a.cod_almacen_salida and b.ano_orden_salida=a.ano_orden_salida and b.numero_orden_salida=a.numero_orden_salida) as username_registro,
		(select b.funcionario from usuarios b where b.username=(select b.username_registro from ciad01_inventario_salidas_cuerpo b where b.cod_presi=a.cod_presi and b.cod_entidad=a.cod_entidad and b.cod_tipo_inst=a.cod_tipo_inst and b.cod_inst=a.cod_inst and b.cod_dep=a.cod_dep and b.cod_almacen_salida=a.cod_almacen_salida and b.ano_orden_salida=a.ano_orden_salida and b.numero_orden_salida=a.numero_orden_salida)) as funcionario,
		(select b.cedula_identidad from usuarios b where b.username=(select c.username_registro from ciad01_inventario_salidas_cuerpo c where c.cod_presi=a.cod_presi and c.cod_entidad=a.cod_entidad and c.cod_tipo_inst=a.cod_tipo_inst and c.cod_inst=a.cod_inst and c.cod_dep=a.cod_dep and c.cod_almacen_salida=a.cod_almacen_salida and c.ano_orden_salida=a.ano_orden_salida and c.numero_orden_salida=a.numero_orden_salida)) as funcionario_cedula
		from ciad01_inventario_salidas_detalles a where ".$this->SQLCA()." and a.cod_almacen_salida=".$cod_almacen." and a.ano_orden_salida=".$ano." and a.numero_orden_salida=".$numero;

	$datos  = $this->ciad01_inventario_almacen->execute($sql);
	$this->set('datos', $datos);


//	pr($this->data);

}




function planilla2($cod_almacen=null,$ano=null,$numero=null){
	$this->layout="ajax";

	if(!isset($cod_almacen)){
		$cod_almacen=$this->data['planilla1']['cod_almacen'];
		$ano=$this->data['planilla1']['ano'];
		$numero=$this->data['planilla1']['numero_salida'];
	}

	$sql="SELECT
		a.cod_presi,
		a.cod_entidad,
		a.cod_tipo_inst,
		a.cod_inst,
		a.cod_dep,
		a.cod_almacen_salida,
		a.ano_orden_salida,
		a.numero_orden_salida,
		a.codigo_prod_serv,
		a.cantidad,
		(select b.denominacion from v_ciad01_inventario_productos b where b.cod_presi=a.cod_presi and b.cod_entidad=a.cod_entidad and b.cod_tipo_inst=a.cod_tipo_inst and b.cod_inst=a.cod_inst and b.cod_dep=a.cod_dep and b.cod_prod_serv=a.codigo_prod_serv and b.cod_almacen=a.cod_almacen_salida) as denominacion,
		(select b.observaciones from ciad01_inventario_salidas_cuerpo b where b.cod_presi=a.cod_presi and b.cod_entidad=a.cod_entidad and b.cod_tipo_inst=a.cod_tipo_inst and b.cod_inst=a.cod_inst and b.cod_dep=a.cod_dep and b.cod_almacen_salida=a.cod_almacen_salida and b.ano_orden_salida=a.ano_orden_salida and b.numero_orden_salida=a.numero_orden_salida) as observaciones,
		(select b.fecha_orden_salida from ciad01_inventario_salidas_cuerpo b where b.cod_presi=a.cod_presi and b.cod_entidad=a.cod_entidad and b.cod_tipo_inst=a.cod_tipo_inst and b.cod_inst=a.cod_inst and b.cod_dep=a.cod_dep and b.cod_almacen_salida=a.cod_almacen_salida and b.ano_orden_salida=a.ano_orden_salida and b.numero_orden_salida=a.numero_orden_salida) as fecha_salida,
		(select b.recibido_por from ciad01_inventario_salidas_cuerpo b where b.cod_presi=a.cod_presi and b.cod_entidad=a.cod_entidad and b.cod_tipo_inst=a.cod_tipo_inst and b.cod_inst=a.cod_inst and b.cod_dep=a.cod_dep and b.cod_almacen_salida=a.cod_almacen_salida and b.ano_orden_salida=a.ano_orden_salida and b.numero_orden_salida=a.numero_orden_salida) as recibido_por,
		(select b.cedula_identidad from ciad01_inventario_salidas_cuerpo b where b.cod_presi=a.cod_presi and b.cod_entidad=a.cod_entidad and b.cod_tipo_inst=a.cod_tipo_inst and b.cod_inst=a.cod_inst and b.cod_dep=a.cod_dep and b.cod_almacen_salida=a.cod_almacen_salida and b.ano_orden_salida=a.ano_orden_salida and b.numero_orden_salida=a.numero_orden_salida) as cedula_identidad,
		(select b.denominacion from ciad01_inventario_almacen b where b.cod_presi=a.cod_presi and b.cod_entidad=a.cod_entidad and b.cod_tipo_inst=a.cod_tipo_inst and b.cod_inst=a.cod_inst and b.cod_dep=a.cod_dep and b.cod_almacen=a.cod_almacen_salida) as almacen_deposito,
		(select b.denominacion from ciad01_inventario_almacen b where b.cod_presi=a.cod_presi and b.cod_entidad=a.cod_entidad and b.cod_tipo_inst=a.cod_tipo_inst and b.cod_inst=a.cod_inst and b.cod_dep=a.cod_dep and b.cod_almacen=(select b.cod_almacen_receptor from ciad01_inventario_salidas_cuerpo b where b.cod_presi=a.cod_presi and b.cod_entidad=a.cod_entidad and b.cod_tipo_inst=a.cod_tipo_inst and b.cod_inst=a.cod_inst and b.cod_dep=a.cod_dep and b.cod_almacen_salida=a.cod_almacen_salida and b.ano_orden_salida=a.ano_orden_salida and b.numero_orden_salida=a.numero_orden_salida)) as almacen_receptor,
		(select b.username_registro from ciad01_inventario_salidas_cuerpo b where b.cod_presi=a.cod_presi and b.cod_entidad=a.cod_entidad and b.cod_tipo_inst=a.cod_tipo_inst and b.cod_inst=a.cod_inst and b.cod_dep=a.cod_dep and b.cod_almacen_salida=a.cod_almacen_salida and b.ano_orden_salida=a.ano_orden_salida and b.numero_orden_salida=a.numero_orden_salida) as username_registro,
		(select b.funcionario from usuarios b where b.username=(select b.username_registro from ciad01_inventario_salidas_cuerpo b where b.cod_presi=a.cod_presi and b.cod_entidad=a.cod_entidad and b.cod_tipo_inst=a.cod_tipo_inst and b.cod_inst=a.cod_inst and b.cod_dep=a.cod_dep and b.cod_almacen_salida=a.cod_almacen_salida and b.ano_orden_salida=a.ano_orden_salida and b.numero_orden_salida=a.numero_orden_salida)) as funcionario,
		(select b.cedula_identidad from usuarios b where b.username=(select c.username_registro from ciad01_inventario_salidas_cuerpo c where c.cod_presi=a.cod_presi and c.cod_entidad=a.cod_entidad and c.cod_tipo_inst=a.cod_tipo_inst and c.cod_inst=a.cod_inst and c.cod_dep=a.cod_dep and c.cod_almacen_salida=a.cod_almacen_salida and c.ano_orden_salida=a.ano_orden_salida and c.numero_orden_salida=a.numero_orden_salida)) as funcionario_cedula
		from ciad01_inventario_salidas_detalles a where ".$this->SQLCA()." and a.cod_almacen_salida=".$cod_almacen." and a.ano_orden_salida=".$ano." and a.numero_orden_salida=".$numero;

	$datos  = $this->ciad01_inventario_almacen->execute($sql);
	$this->set('datos', $datos);


//	pr($this->data);

}




function salir($almacen=null,$num_rc=null,$var=null){
	$this->layout="ajax";
	$ano=$this->ano_ejecucion();
	if(isset($num_rc)){
		$resultado=$this->ciad01_inventario_salidas_numero->execute("UPDATE  ciad01_inventario_salidas_numero SET situacion=1 WHERE ".$this->SQLCA()." and cod_almacen_salida=".$almacen." and numero_orden_salida=".$num_rc." and ano_orden_salida=".$ano." and situacion=2");
	}

	//$this->('index');
}


function pdf_nota_entrega($cod_almacen=null, $ano=null, $numero=null){
	$this->layout="pdf";

	$cod_presi 		= $this->Session->read('SScodpresi');
	$cod_entidad 	= $this->Session->read('SScodentidad');
	$cod_tipo_inst 	= $this->Session->read('SScodtipoinst');
	$cod_inst 		= $this->Session->read('SScodinst');
	$cod_dep 		= $this->Session->read('SScoddep');

	if($cod_almacen==null){
		$cod_almacen=$this->data['planilla']['cod_almacen'];
		$ano=$this->data['planilla']['ano'];
		$numero=$this->data['planilla']['numero_salida'];
	}else{
		$cod_almacen=$cod_almacen;
		$ano=$ano;
		$numero=$numero;
	}

	$sql_cuerpo_salida = "SELECT
				a.ano_orden_salida,
				a.numero_orden_salida,
				a.fecha_orden_salida,
				a.observaciones,
				a.cod_dep_receptora,
				a.cod_almacen_receptor,
				(SELECT b.denominacion FROM ciad01_inventario_almacen b WHERE b.cod_presi=a.cod_presi AND b.cod_entidad=a.cod_entidad AND b.cod_tipo_inst=a.cod_tipo_inst AND b.cod_inst=a.cod_inst AND b.cod_dep=a.cod_dep AND b.cod_almacen=a.cod_almacen_salida) AS almacen,
				a.numero_recepcion,
				a.hora_salida,
				a.titular,
				a.ci_titular,
				a.beneficiario,
				a.ci_beneficiario,
				a.entregado_por,
				a.ci_entregado,
				a.recibido_por,
				a.ci_recibido
			FROM
				ciad01_inventario_salidas_cuerpo a
			WHERE
				a.cod_presi = '$cod_presi' AND
				a.cod_entidad = '$cod_entidad' AND
				a.cod_tipo_inst = '$cod_tipo_inst' AND
				a.cod_inst = '$cod_inst' AND
				a.cod_dep = '$cod_dep' AND
				a.cod_almacen_salida = '$cod_almacen' AND
				a.ano_orden_salida = '$ano' AND
				a.numero_orden_salida = '$numero'";

	$sql_detalles_salida = "SELECT
				cod_almacen_salida,
				ano_orden_salida,
				numero_orden_salida,
				codigo_prod_serv,
				(SELECT c.denominacion FROM cscd01_catalogo c WHERE c.codigo_prod_serv=a.codigo_prod_serv) AS producto,
				cantidad,
				(SELECT b.costo_maximo FROM ciad01_inventario_productos b WHERE b.cod_presi=a.cod_presi AND b.cod_entidad=a.cod_entidad AND b.cod_tipo_inst=a.cod_tipo_inst AND b.cod_inst=a.cod_inst AND b.cod_dep=a.cod_dep AND b.cod_almacen=a.cod_almacen_salida AND b.cod_prod_serv=a.codigo_prod_serv) AS costo_maximo,
				(SELECT b.costo_minimo FROM ciad01_inventario_productos b WHERE b.cod_presi=a.cod_presi AND b.cod_entidad=a.cod_entidad AND b.cod_tipo_inst=a.cod_tipo_inst AND b.cod_inst=a.cod_inst AND b.cod_dep=a.cod_dep AND b.cod_almacen=a.cod_almacen_salida AND b.cod_prod_serv=a.codigo_prod_serv) AS costo_minimo,
				(SELECT b.stock_minimo FROM ciad01_inventario_productos b WHERE b.cod_presi=a.cod_presi AND b.cod_entidad=a.cod_entidad AND b.cod_tipo_inst=a.cod_tipo_inst AND b.cod_inst=a.cod_inst AND b.cod_dep=a.cod_dep AND b.cod_almacen=a.cod_almacen_salida AND b.cod_prod_serv=a.codigo_prod_serv) AS stock_minimo,
				(SELECT b.stock_maximo FROM ciad01_inventario_productos b WHERE b.cod_presi=a.cod_presi AND b.cod_entidad=a.cod_entidad AND b.cod_tipo_inst=a.cod_tipo_inst AND b.cod_inst=a.cod_inst AND b.cod_dep=a.cod_dep AND b.cod_almacen=a.cod_almacen_salida AND b.cod_prod_serv=a.codigo_prod_serv) AS stock_maximo
			FROM
				ciad01_inventario_salidas_detalles a
			WHERE
				a.cod_presi = '$cod_presi' AND
				a.cod_entidad = '$cod_entidad' AND
				a.cod_tipo_inst = '$cod_tipo_inst' AND
				a.cod_inst = '$cod_inst' AND
				a.cod_dep = '$cod_dep' AND
				a.cod_almacen_salida = '$cod_almacen' AND
				a.ano_orden_salida = '$ano' AND
				a.numero_orden_salida = '$numero'";

	$cuerpo_nota_ent = $this->ciad01_inventario_salidas_cuerpo->execute($sql_cuerpo_salida);
	$detalles_salida = $this->ciad01_inventario_salidas_cuerpo->execute($sql_detalles_salida);

	$this->set('cuerpo_nota_ent', $cuerpo_nota_ent);
	$this->set('detalles_salida', $detalles_salida);
}


function reimpresion_nota_entrega($var = null){
	$this->layout="ajax";

	if($var=='no'){
		$this->layout="ajax";
		$cod_presi 					= $this->Session->read('SScodpresi');
		$cod_entidad 				= $this->Session->read('SScodentidad');
		$cod_tipo_inst 				= $this->Session->read('SScodtipoinst');
		$cod_inst 					= $this->Session->read('SScodinst');
		$cod_dep 					= $this->Session->read('SScoddep');

		$datos=$this->ciad01_inventario_almacen->generateList($this->SQLCA(),'cod_almacen ASC', null, '{n}.ciad01_inventario_almacen.cod_almacen', '{n}.ciad01_inventario_almacen.denominacion');
		if($datos!=null){
		$this->concatena($datos,'almacenes');
		}else{
			$this->set('almacenes',array());
		}
		$ano=$this->ano_ejecucion();
		$this->Session->write('ano_almacen', $ano);
		$this->set('ano',$ano);

	}else if($var=='si'){
		$this->layout="pdf";

	}
	$this->set('var', $var);
}

function deno_almacen($var = null){
	$this->layout="ajax";

	if($var!=''){
		$datos = $this->ciad01_inventario_almacen->find($this->SQLCA()." and cod_almacen='$var'");
		$this->set('denominacion_almacen', $datos['ciad01_inventario_almacen']['denominacion']);
	}else{
		$var = 0;
		$this->set('denominacion_almacen', '');
	}

	$ano = $this->Session->read('ano_almacen');
	$num = $this->ciad01_inventario_salidas_cuerpo->generateList($this->SQLCA()." and cod_almacen_salida=".$var." and ano_orden_salida=".$ano,'numero_orden_salida ASC', null, '{n}.ciad01_inventario_salidas_cuerpo.numero_orden_salida', '{n}.ciad01_inventario_salidas_cuerpo.beneficiario');
	if($num!=null){
		$this->concatena_seis($num,'numero');
	}else{
		$this->set('numero',null);
	}
}


function buscar_notas_entregas($numero=null) {
	$this->layout="ajax";

	$cod_presi 		= $this->Session->read('SScodpresi');
	$cod_entidad 	= $this->Session->read('SScodentidad');
	$cod_tipo_inst 	= $this->Session->read('SScodtipoinst');
	$cod_inst 		= $this->Session->read('SScodinst');
	$cod_dep 		= $this->Session->read('SScoddep');

	$cod_almacen=$this->data['planilla']['cod_almacen'];
	$ano=$this->data['planilla']['ano'];
	$numero=$this->data['planilla']['numero_salida'];

	$sql_cuerpo_salida = "SELECT
				a.ano_orden_salida,
				a.numero_orden_salida,
				a.fecha_orden_salida,
				a.observaciones,
				a.cod_dep_receptora,
				a.cod_almacen_receptor,
				(SELECT b.denominacion FROM ciad01_inventario_almacen b WHERE b.cod_presi=a.cod_presi AND b.cod_entidad=a.cod_entidad AND b.cod_tipo_inst=a.cod_tipo_inst AND b.cod_inst=a.cod_inst AND b.cod_dep=a.cod_dep AND b.cod_almacen=a.cod_almacen_salida) AS almacen,
				a.numero_recepcion,
				a.hora_salida,
				a.titular,
				a.ci_titular,
				a.beneficiario,
				a.ci_beneficiario,
				a.entregado_por,
				a.ci_entregado,
				a.recibido_por,
				a.ci_recibido
			FROM
				ciad01_inventario_salidas_cuerpo a
			WHERE
				a.cod_presi = '$cod_presi' AND
				a.cod_entidad = '$cod_entidad' AND
				a.cod_tipo_inst = '$cod_tipo_inst' AND
				a.cod_inst = '$cod_inst' AND
				a.cod_dep = '$cod_dep' AND
				a.cod_almacen_salida = '$cod_almacen' AND
				a.ano_orden_salida = '$ano' AND
				a.numero_orden_salida = '$numero'";


	$sql_detalles_salida = "SELECT
				cod_almacen_salida,
				ano_orden_salida,
				numero_orden_salida,
				codigo_prod_serv,
				(SELECT c.denominacion FROM cscd01_catalogo c WHERE c.codigo_prod_serv=a.codigo_prod_serv) AS producto,
				cantidad,
				(SELECT b.costo_maximo FROM ciad01_inventario_productos b WHERE b.cod_presi=a.cod_presi AND b.cod_entidad=a.cod_entidad AND b.cod_tipo_inst=a.cod_tipo_inst AND b.cod_inst=a.cod_inst AND b.cod_dep=a.cod_dep AND b.cod_almacen=a.cod_almacen_salida AND b.cod_prod_serv=a.codigo_prod_serv) AS costo_maximo,
				(SELECT b.costo_minimo FROM ciad01_inventario_productos b WHERE b.cod_presi=a.cod_presi AND b.cod_entidad=a.cod_entidad AND b.cod_tipo_inst=a.cod_tipo_inst AND b.cod_inst=a.cod_inst AND b.cod_dep=a.cod_dep AND b.cod_almacen=a.cod_almacen_salida AND b.cod_prod_serv=a.codigo_prod_serv) AS costo_minimo,
				(SELECT b.stock_minimo FROM ciad01_inventario_productos b WHERE b.cod_presi=a.cod_presi AND b.cod_entidad=a.cod_entidad AND b.cod_tipo_inst=a.cod_tipo_inst AND b.cod_inst=a.cod_inst AND b.cod_dep=a.cod_dep AND b.cod_almacen=a.cod_almacen_salida AND b.cod_prod_serv=a.codigo_prod_serv) AS stock_minimo,
				(SELECT b.stock_maximo FROM ciad01_inventario_productos b WHERE b.cod_presi=a.cod_presi AND b.cod_entidad=a.cod_entidad AND b.cod_tipo_inst=a.cod_tipo_inst AND b.cod_inst=a.cod_inst AND b.cod_dep=a.cod_dep AND b.cod_almacen=a.cod_almacen_salida AND b.cod_prod_serv=a.codigo_prod_serv) AS stock_maximo
			FROM
				ciad01_inventario_salidas_detalles a
			WHERE
				a.cod_presi = '$cod_presi' AND
				a.cod_entidad = '$cod_entidad' AND
				a.cod_tipo_inst = '$cod_tipo_inst' AND
				a.cod_inst = '$cod_inst' AND
				a.cod_dep = '$cod_dep' AND
				a.cod_almacen_salida = '$cod_almacen' AND
				a.ano_orden_salida = '$ano' AND
				a.numero_orden_salida = '$numero'";

	$cuerpo_nota_ent = $this->ciad01_inventario_salidas_cuerpo->execute($sql_cuerpo_salida);
	$detalles_salida = $this->ciad01_inventario_salidas_cuerpo->execute($sql_detalles_salida);

	$this->set('cuerpo_nota_ent', $cuerpo_nota_ent);
	$this->set('detalles_salida', $detalles_salida);
}



}//fin class