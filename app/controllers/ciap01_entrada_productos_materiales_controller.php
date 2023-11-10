<?php
/*
 * Creado el 09/10/2008 a las 12:41:23 AM
 * Herramienta: EasyEclipse.
 * Proyecto: SIGEP
 * Descripcion:
 */

 class Ciap01EntradaProductosMaterialesController extends AppController{
	var $uses = array('ciad01_inventario_almacen','v_cscd01_snc_grupo','ciad01_inventario_productos','cscd01_catalogo','cscd01_unidad_medida',
					  'ciad01_inventario_usuarios','v_ciad01_inventario_productos','ciad01_inventario_entradas_numero','ccfd04_cierre_mes',
					  'ciad01_inventario_salidas_cuerpo','ciad01_inventario_salidas_detalles','ciad01_inventario_entradas_cuerpo','ciad01_inventario_entradas_detalles',
					  'cscd05_ordencompra_nota_entrega_encabezado','cscd05_ordencompra_nota_entrega_cuerpo');
 	var $helpers = array('Html','Ajax','Javascript', 'Sisap');
 	var $name = "ciap01_entrada_productos_materiales";


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
	$cod_presi 					= $this->Session->read('SScodpresi');
	$cod_entidad 				= $this->Session->read('SScodentidad');
	$cod_tipo_inst 				= $this->Session->read('SScodtipoinst');
	$cod_inst 					= $this->Session->read('SScodinst');
	$cod_dep 					= $this->Session->read('SScoddep');
	$this->limpiar_lista();
	$this->limpiar1();
	$this->set('ano_nota_entrega','');
	$this->set('numero_nota_entrega','');
	$this->set('ano_orden_compra','');
	$this->set('numero_orden_compra','');
	$this->set('proveedor_rif','');
	$this->set('proveedor','');


	$this->set("cscd01_unidad_medida",$this->cscd01_unidad_medida->findAll());

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

		$this->set('manual',$a[0][0]['seleccionar_productos']);

	      $maxi=$this->ciad01_inventario_entradas_numero->findCount($this->SQLCA()." and cod_almacen_entrada=".$a[0][0]['cod_almacen']." and ano_recepcion=".$ano." and situacion=1");
	      //$max=$this->cepd01_compromiso_numero->execute("SELECT numero_compromiso FROM cepd01_compromiso_numero WHERE ".$this->SQLCA()."  ORDER BY numero_compromiso ASC LIMIT 1");
	      if($maxi==0){
	         $this->set("errorMessage","debe registrar nuevos números para el control de entradas de este almacén");
	      	 $this->set("numero_salida","");
	      }else{
	      	///////////////////////////////////////////////////
	      			$max=$this->ciad01_inventario_entradas_numero->execute("SELECT numero_recepcion FROM ciad01_inventario_entradas_numero WHERE ".$this->SQLCA()." and cod_almacen_entrada=".$a[0][0]['cod_almacen']." and ano_recepcion=".$ano." and situacion=1 ORDER BY numero_recepcion ASC LIMIT 1");
		      		if($max!=null){
				      	    $codigo=$max[0][0]["numero_recepcion"];
				            $resultado=$this->ciad01_inventario_entradas_numero->execute("UPDATE  ciad01_inventario_entradas_numero SET situacion=2 WHERE ".$this->SQLCA()." and cod_almacen_entrada=".$a[0][0]['cod_almacen']." and numero_recepcion=".$codigo." and ano_recepcion=".$ano);
					         if($resultado>1){
				                //$this->set("Message_existe","Situacion de solicitud actualizada con exito");
				               $this->set("numero_salida",$codigo);
					         }else{
						        $this->set("errorMessage","Por favor Verifique el n&uacute;mero de control de entradas");
						        $this->set("numero_salida","");
					      }
			      }else{
			      	 $this->set("errorMessage","debe registrar nuevos números para el control de entradas de este almacén");
			      	 $this->set("numero_salida","");
	 			 }






	      }


		if($a[0][0]['principal_secundario']==1){
			$this->set('muestra',$a[0][0]['principal_secundario']);
								////////////////++++++++++++++++++++aqui busco si el almacen tiene productos que recibir por transferencia/////////////////

						      $transferido=$this->ciad01_inventario_salidas_cuerpo->execute("select a.cod_presi,a.cod_entidad,a.cod_tipo_inst,a.cod_inst,a.cod_dep,
																			a.cod_almacen_salida,
																			a.ano_orden_salida,
																			a.numero_orden_salida,
																			a.fecha_orden_salida,
																			(select b.denominacion from arrd05 b where a.cod_presi=b.cod_presi and a.cod_entidad=b.cod_entidad and a.cod_tipo_inst=b.cod_tipo_inst and a.cod_inst=b.cod_inst and a.cod_dep=b.cod_dep) as deno_dependencia,
																			(select b.denominacion from ciad01_inventario_almacen b where a.cod_presi=b.cod_presi and a.cod_entidad=b.cod_entidad and a.cod_tipo_inst=b.cod_tipo_inst and a.cod_inst=b.cod_inst and a.cod_dep=b.cod_dep and a.cod_almacen_salida=b.cod_almacen) as deno_almacen
																			  from ciad01_inventario_salidas_cuerpo a WHERE ".$this->condicionNDEP()." and a.cod_dep_receptora=".$cod_dep." and a.cod_almacen_receptor=".$a[0][0]['cod_almacen']." and a.ano_recepcion=0 and a.numero_recepcion=0 and a.condicion_actividad=1 and (a.cod_almacen_salida!=".$a[0][0]['cod_almacen']." or a.cod_dep!=".$cod_dep.")");


							  if($transferido!=null){
								$this->set('cod_almacen_origen',$a[0][0]['cod_almacen']);
								$this->set('almacen_origen',$a[0][0]['denominacion']);
								$this->set('numero_origen','');
								$this->set('fecha_origen','');


								/* $productos="select
									a.cod_presi,
									a.cod_entidad,
									a.cod_tipo_inst,
									a.cod_inst,
									a.cod_presi,
									a.cod_almacen_salida,
									a.codigo_prod_serv,
									a.cantidad,
									b.denominacion,
									b.cod_medida
									from ciad01_inventario_salidas_detalles a,v_ciad01_inventario_productos b
									where
									a.codigo_prod_serv=b.codigo_prod_serv and
									a.cod_almacen_salida=b.cod_almacen and a.cod_presi='$cod_presi' and a.cod_entidad='$cod_entidad' and a.cod_tipo_inst='$cod_tipo_inst' and a.cod_inst='$cod_inst' and a.cod_dep='$dep_origen' and a.ano_orden_salida=".$ano_origen." and a.cod_almacen_salida=".$almacen_origen." and a.numero_orden_salida=".$numero_origen;
									$productos=$this->ciad01_inventario_salidas_cuerpo->execute($productos);
									*/
									$this->set('productos',$transferido);

							 $this->set("Message_existe","Posee transferencias que recibir de otro almacén");


							  }else{

							  	$this->set('productos',null);
							  }


							  if($this->cscd05_ordencompra_nota_entrega_encabezado->FindCount($this->SQLCA()." and ano_nota_entrega=".$this->ano_ejecucion()." and transferido_almacen=2")!=0){
								///*****************esto de la nota de entrega lo hice tomando en cuenta que la nota de entrega la registra siempre la misma dependencia//////////

								$ver=$this->cscd05_ordencompra_nota_entrega_encabezado->execute("SELECT
																								a.cod_presi,
																								a.cod_entidad,
																								a.cod_tipo_inst,
																								a.cod_inst,
																								a.cod_dep,
																								a.rif,
																								(select b.denominacion from cpcd02 b where b.rif=a.rif) as proveedor,
																								a.ano_nota_entrega,
																								a.numero_nota_entrega,
																								a.ano_orden_compra,
																								a.numero_orden_compra,
																								a.observaciones,
																								a.entrega_completa,
																								a.fecha_nota_entrega,
																								a.transferido_almacen,
																								a.inventariado_bienes from cscd05_ordencompra_nota_entrega_encabezado a where ".$this->SQLCA()." and a.ano_nota_entrega=".$this->ano_ejecucion()." and a.transferido_almacen=2");
								$this->set('notas',$ver);
								$this->set("cod_dependencia", $cod_dep);
								$dep  = $this->ciad01_inventario_almacen->execute("select denominacion from arrd05 where ".$this->condicionNDEP()." and cod_dep=".$cod_dep);
								$this->set("dependencia", $dep[0][0]['denominacion']);
								$this->set('numero_origen','');
								$this->set('fecha_origen','');

								$this->set('cod_almacen_origen','');
								$this->set('almacen_origen','');

								$this->set("Message_existe","Posee notas de entrega que recibir");


							  }else{

							  	$this->set('notas',null);
							  	$this->set("cod_dependencia", $cod_dep);
								$this->set('numero_origen','');
								$this->set('fecha_origen','');

							  }
//'cscd05_ordencompra_nota_entrega_encabezado','cscd05_ordencompra_nota_entrega_cuerpo');

		}else{
			$this->set('muestra',2);
			$this->set('notas',null);

			////////////////++++++++++++++++++++aqui busco si el almacen tiene productos que recibir por transferencia/////////////////

	      $transferido=$this->ciad01_inventario_salidas_cuerpo->execute("select a.cod_presi,a.cod_entidad,a.cod_tipo_inst,a.cod_inst,a.cod_dep,
																			a.cod_almacen_salida,
																			a.ano_orden_salida,
																			a.numero_orden_salida,
																			a.fecha_orden_salida,
																			(select b.denominacion from arrd05 b where a.cod_presi=b.cod_presi and a.cod_entidad=b.cod_entidad and a.cod_tipo_inst=b.cod_tipo_inst and a.cod_inst=b.cod_inst and a.cod_dep=b.cod_dep) as deno_dependencia,
																			(select b.denominacion from ciad01_inventario_almacen b where a.cod_presi=b.cod_presi and a.cod_entidad=b.cod_entidad and a.cod_tipo_inst=b.cod_tipo_inst and a.cod_inst=b.cod_inst and a.cod_dep=b.cod_dep and a.cod_almacen_salida=b.cod_almacen) as deno_almacen
																			  from ciad01_inventario_salidas_cuerpo a WHERE ".$this->condicionNDEP()." and a.cod_dep_receptora=".$cod_dep." and a.cod_almacen_receptor=".$a[0][0]['cod_almacen']." and a.ano_recepcion=0 and a.numero_recepcion=0 and a.condicion_actividad=1 and (a.cod_almacen_salida!=".$a[0][0]['cod_almacen']." or a.cod_dep!=".$cod_dep.")");

		  if($transferido!=null){
			$this->set('cod_almacen_origen',$a[0][0]['cod_almacen']);
			$this->set('almacen_origen',$a[0][0]['denominacion']);
			$this->set('numero_origen','');
			$this->set('fecha_origen','');


			/* $productos="select
				a.cod_presi,
				a.cod_entidad,
				a.cod_tipo_inst,
				a.cod_inst,
				a.cod_presi,
				a.cod_almacen_salida,
				a.codigo_prod_serv,
				a.cantidad,
				b.denominacion,
				b.cod_medida
				from ciad01_inventario_salidas_detalles a,v_ciad01_inventario_productos b
				where
				a.codigo_prod_serv=b.codigo_prod_serv and
				a.cod_almacen_salida=b.cod_almacen and a.cod_presi='$cod_presi' and a.cod_entidad='$cod_entidad' and a.cod_tipo_inst='$cod_tipo_inst' and a.cod_inst='$cod_inst' and a.cod_dep='$dep_origen' and a.ano_orden_salida=".$ano_origen." and a.cod_almacen_salida=".$almacen_origen." and a.numero_orden_salida=".$numero_origen;
				$productos=$this->ciad01_inventario_salidas_cuerpo->execute($productos);
				*/
				$this->set('productos',$transferido);
//pr($productos);

		 $this->set("Message_existe","Posee transferencias que recibir de otro almacén");

		  }else{

			$this->set('cod_almacen_origen',$a[0][0]['cod_almacen']);
			$this->set('almacen_origen',$a[0][0]['denominacion']);
			$this->set('numero_origen','');
			$this->set('fecha_origen','');
			$this->set('productos',null);

		  }


		}


	}else{
		$this->set('muestra',2);
		$this->set('manual',2);
		$this->set("cod_dependencia", $cod_dep);
	  	$dep  = $this->ciad01_inventario_almacen->execute("select denominacion from arrd05 where ".$this->SQLCA());
		$this->set("dependencia", $dep[0][0]['denominacion']);
		$this->set('almacen','');
		$this->set('deno_almacen','');
		$this->set('ubicacion','');
		$this->set('readonly','');
		$this->set("numero_salida","");
		$this->set('cod_almacen_origen','');
		$this->set('almacen_origen','');
		$this->set('numero_origen','');
		$this->set('fecha_origen','');
		$this->set('productos',null);
	}

}//fin index


function verifica($var=null){
	$this->layout = "ajax";
	$cod_presi 					= $this->Session->read('SScodpresi');
	$cod_entidad 				= $this->Session->read('SScodentidad');
	$cod_tipo_inst 				= $this->Session->read('SScodtipoinst');
	$cod_inst 					= $this->Session->read('SScodinst');
	$cod_dep 					= $this->Session->read('SScoddep');

	$this->limpiar_lista();
	$this->limpiar1();
	$this->set("cscd01_unidad_medida",$this->cscd01_unidad_medida->findAll());
	$this->set('ano_nota_entrega','');
	$this->set('numero_nota_entrega','');
	$this->set('ano_orden_compra','');
	$this->set('numero_orden_compra','');
	$this->set('proveedor_rif','');
	$this->set('proveedor','');

	$datos=$this->ciad01_inventario_almacen->generateList($this->SQLCA(),'cod_almacen ASC', null, '{n}.ciad01_inventario_almacen.cod_almacen', '{n}.ciad01_inventario_almacen.denominacion');
	if($datos!=null){
		$this->concatena($datos,'almacenes');
	}else{
		$this->set('almacenes',array());
	}

	$ano=$this->ano_ejecucion();
	$this->set('ano',$ano);

	$this->set('manual',2);

	if($var!=''){
				$a=$this->ciad01_inventario_usuarios->execute("select * from ciad01_inventario_almacen where ".$this->SQLCA()." and cod_almacen=".$var);
				$this->set('almacen',$a[0][0]['cod_almacen']);
				$this->set('deno_almacen',$a[0][0]['denominacion']);
				$this->set('ubicacion',$a[0][0]['ubicacion']);
				$this->set('readonly','readonly');
				$this->Session->delete('cod_almacen');
				$this->Session->write('cod_almacen',$a[0][0]['cod_almacen']);
				$this->set('manual',$a[0][0]['seleccionar_productos']);


				////////////////////////////aqui la parte que verifica el numero del almacen/////////////////////////////////////
			      $maxi=$this->ciad01_inventario_entradas_numero->findCount($this->SQLCA()." and cod_almacen_entrada=".$a[0][0]['cod_almacen']." and ano_recepcion=".$ano." and situacion=1");
			      //$max=$this->cepd01_compromiso_numero->execute("SELECT numero_compromiso FROM cepd01_compromiso_numero WHERE ".$this->SQLCA()."  ORDER BY numero_compromiso ASC LIMIT 1");
			      if($maxi==0){
			         $this->set("errorMessage","debe registrar nuevos números para el control de entradas de este almacén");
			      	 $this->set("numero_salida",0);
			      }else{
			      	///////////////////////////////////////////////////
			      			$max=$this->ciad01_inventario_entradas_numero->execute("SELECT numero_recepcion FROM ciad01_inventario_entradas_numero WHERE ".$this->SQLCA()." and cod_almacen_entrada=".$a[0][0]['cod_almacen']." and ano_recepcion=".$ano." and situacion=1 ORDER BY numero_recepcion ASC LIMIT 1");
				      		if($max!=null){
						      	    $codigo=$max[0][0]["numero_recepcion"];
						            $resultado=$this->ciad01_inventario_entradas_numero->execute("UPDATE  ciad01_inventario_entradas_numero SET situacion=2 WHERE ".$this->SQLCA()." and cod_almacen_entrada=".$a[0][0]['cod_almacen']." and numero_recepcion=".$codigo." and ano_recepcion=".$ano);
							         if($resultado>1){
						                //$this->set("Message_existe","Situacion de solicitud actualizada con exito");
						               $this->set("numero_salida",$codigo);
							         }else{
								        $this->set("errorMessage","Por favor Verifique el n&uacute;mero de control de entradas");
								        $this->set("numero_salida","");
							      }
					      }else{
					      	 $this->set("errorMessage","debe registrar nuevos números para el control de entradas de este almacén");
					      	 $this->set("numero_salida","");
			 			 }

			      }

			   if($a[0][0]['principal_secundario']==1){
			   				$this->set('muestra',$a[0][0]['principal_secundario']);
								////////////////++++++++++++++++++++aqui busco si el almacen tiene productos que recibir por transferencia/////////////////

						     $transferido=$this->ciad01_inventario_salidas_cuerpo->execute("select a.cod_presi,a.cod_entidad,a.cod_tipo_inst,a.cod_inst,a.cod_dep,
																						a.cod_almacen_salida,
																						a.ano_orden_salida,
																						a.numero_orden_salida,
																						a.fecha_orden_salida,
																						(select b.denominacion from arrd05 b where a.cod_presi=b.cod_presi and a.cod_entidad=b.cod_entidad and a.cod_tipo_inst=b.cod_tipo_inst and a.cod_inst=b.cod_inst and a.cod_dep=b.cod_dep) as deno_dependencia,
																						(select b.denominacion from ciad01_inventario_almacen b where a.cod_presi=b.cod_presi and a.cod_entidad=b.cod_entidad and a.cod_tipo_inst=b.cod_tipo_inst and a.cod_inst=b.cod_inst and a.cod_dep=b.cod_dep and a.cod_almacen_salida=b.cod_almacen) as deno_almacen
																						from ciad01_inventario_salidas_cuerpo a WHERE ".$this->condicionNDEP()." and a.cod_dep_receptora=".$cod_dep." and a.cod_almacen_receptor=".$a[0][0]['cod_almacen']." and a.ano_recepcion=0 and a.numero_recepcion=0 and a.condicion_actividad=1 and (a.cod_almacen_salida!=".$a[0][0]['cod_almacen']." or a.cod_dep!=".$cod_dep.")");

							  if($transferido!=null){
									$this->set('cod_almacen_origen',$a[0][0]['cod_almacen']);
									$this->set('almacen_origen',$a[0][0]['denominacion']);
									$this->set('numero_origen','');
									$this->set('fecha_origen','');

									$this->set('productos',$transferido);
							 		$this->set("Message_existe","Posee transferencias que recibir de otro almacén");
							  }else{
							  		$this->set('productos',null);
							  }



							  if($this->cscd05_ordencompra_nota_entrega_encabezado->FindCount($this->SQLCA()." and ano_nota_entrega=".$this->ano_ejecucion()." and transferido_almacen=2")!=0){
									///*****************esto de la nota de entrega lo hice tomando en cuenta que la nota de entrega la registra siempre la misma dependencia//////////

									$ver=$this->cscd05_ordencompra_nota_entrega_encabezado->execute("SELECT
																								a.cod_presi,
																								a.cod_entidad,
																								a.cod_tipo_inst,
																								a.cod_inst,
																								a.cod_dep,
																								a.rif,
																								(select b.denominacion from cpcd02 b where b.rif=a.rif) as proveedor,
																								a.ano_nota_entrega,
																								a.numero_nota_entrega,
																								a.ano_orden_compra,
																								a.numero_orden_compra,
																								a.observaciones,
																								a.entrega_completa,
																								a.fecha_nota_entrega,
																								a.transferido_almacen,
																								a.inventariado_bienes from cscd05_ordencompra_nota_entrega_encabezado a where ".$this->SQLCA()." and a.ano_nota_entrega=".$this->ano_ejecucion()." and a.transferido_almacen=2");

									$this->set('notas',$ver);
									$this->set("cod_dependencia", $cod_dep);
									$dep  = $this->ciad01_inventario_almacen->execute("select denominacion from arrd05 where ".$this->condicionNDEP()." and cod_dep=".$cod_dep);
									$this->set("dependencia", $dep[0][0]['denominacion']);
									$this->set('numero_origen','');
									$this->set('fecha_origen','');

									$this->set('cod_almacen_origen','');
									$this->set('almacen_origen','');

									$this->set("Message_existe","Posee notas de entrega que recibir");

							  }else{
								  	$this->set('notas',null);
								  	$this->set("cod_dependencia", $cod_dep);
									$this->set('numero_origen','');
									$this->set('fecha_origen','');
							  }


			}else{
				 $this->set('muestra',2);

				 $transferido=$this->ciad01_inventario_salidas_cuerpo->execute("select a.cod_presi,a.cod_entidad,a.cod_tipo_inst,a.cod_inst,a.cod_dep,
																				a.cod_almacen_salida,
																				a.ano_orden_salida,
																				a.numero_orden_salida,
																				a.fecha_orden_salida,
																				(select b.denominacion from arrd05 b where a.cod_presi=b.cod_presi and a.cod_entidad=b.cod_entidad and a.cod_tipo_inst=b.cod_tipo_inst and a.cod_inst=b.cod_inst and a.cod_dep=b.cod_dep) as deno_dependencia,
																				(select b.denominacion from ciad01_inventario_almacen b where a.cod_presi=b.cod_presi and a.cod_entidad=b.cod_entidad and a.cod_tipo_inst=b.cod_tipo_inst and a.cod_inst=b.cod_inst and a.cod_dep=b.cod_dep and a.cod_almacen_salida=b.cod_almacen) as deno_almacen
																				  from ciad01_inventario_salidas_cuerpo a WHERE ".$this->condicionNDEP()." and a.cod_dep_receptora=".$cod_dep." and a.cod_almacen_receptor=".$a[0][0]['cod_almacen']." and a.ano_recepcion=0 and a.numero_recepcion=0 and a.condicion_actividad=1 and (a.cod_almacen_salida!=".$a[0][0]['cod_almacen']." or a.cod_dep!=".$cod_dep.")");
				  if($transferido!=null){
						$this->set('cod_almacen_origen',$a[0][0]['cod_almacen']);
						$this->set('almacen_origen',$a[0][0]['denominacion']);
						$this->set('numero_origen','');
						$this->set('fecha_origen','');

						$this->set('productos',$transferido);
					    $this->set("Message_existe","Posee transferencias que recibir de otro almacén");
				  }else{
				  		$this->set('productos',null);

				  }

			}


	}else{
		$this->set("cod_dependencia", $cod_dep);
	  	$dep  = $this->ciad01_inventario_almacen->execute("select denominacion from arrd05 where ".$this->SQLCA());
		$this->set("dependencia", $dep[0][0]['denominacion']);
		$this->set('productos',null);
		$this->Session->delete('cod_almacen');
		$this->set("numero_salida",0);	$this->set('notas',null);
		$this->set('manual',2);

	}




}



function ventana_nota($rif=null,$ano_nota=null,$numero_nota=null,$ano_orden=null,$numero_orden=null){
	$this->layout="ajax";
	$cod_presi 					= $this->Session->read('SScodpresi');
	$cod_entidad 				= $this->Session->read('SScodentidad');
	$cod_tipo_inst 				= $this->Session->read('SScodtipoinst');
	$cod_inst 					= $this->Session->read('SScodinst');
	$cod_dep 					= $this->Session->read('SScoddep');
	$this->set("cscd01_unidad_medida",$this->cscd01_unidad_medida->findAll());
	$this->limpiar_lista();
	$this->limpiar1();

	$ver=$this->cscd05_ordencompra_nota_entrega_encabezado->execute("select * from cscd05_ordencompra_nota_entrega_encabezado where ".$this->SQLCA()." and rif='".$rif."' and ano_nota_entrega=".$ano_nota." and numero_nota_entrega='".$numero_nota."' and transferido_almacen=2");
	$this->set('notas',$ver);

	$ver1=$this->cscd05_ordencompra_nota_entrega_encabezado->execute("select b.denominacion from cpcd02 b where b.rif='".$ver[0][0]['rif']."'");
	$this->set('proveedor',$ver1[0][0]['denominacion']);

	$sql="select * from cscd05_ordencompra_nota_entrega_cuerpo where ".$this->SQLCA()." and rif='".$rif."' and ano_nota_entrega=".$ano_nota." and numero_nota_entrega='".$numero_nota."'";
	$datos=$this->cscd05_ordencompra_nota_entrega_cuerpo->execute($sql);
	for($i=0;$i<count($datos);$i++){
		 $vec[$i][0]=$datos[$i][0]['rif'];
	     $vec[$i][1]=$datos[$i][0]['ano_nota_entrega'];
	     $vec[$i][2]=$datos[$i][0]['numero_nota_entrega'];
	     $vec[$i][3]=$datos[$i][0]['codigo_prod_serv'];
	     $vec[$i][4]=$datos[$i][0]['descripcion'];
	     $vec[$i][5]=$datos[$i][0]['cod_medida'];
	     $vec[$i][6]=$datos[$i][0]['cantidad'];
	     $vec[$i][7]=$datos[$i][0]['precio_unitario'];
	     $vec[$i][8]=$ver[0][0]['ano_orden_compra'];
	     $vec[$i][9]=$ver[0][0]['numero_orden_compra'];
	     $vec[$i][10]=$ver1[0][0]['denominacion'];
		 $vec[$i]["id"]=$i;
	}

	$_SESSION["items2"]=$vec;


}


function eliminar_producto_nota($id){
	$this->layout = "ajax";
	$this->set("cscd01_unidad_medida",$this->cscd01_unidad_medida->findAll());
	$codigos=$_SESSION ["items2"][$id];

	$almacen=$this->Session->read('cod_almacen');
	$verifica=$this->ciad01_inventario_productos->execute("select * from ciad01_inventario_productos where ".$this->SQLCA()." and cod_almacen='$almacen' and cod_prod_serv='".$codigos[3]."'");
	if($verifica!=null){
		$status=1;//existe producto
	}else{
		$status=2;// no existe producto, muestro icono para agregar la ubicacion
	}

	 $vec[$id][0]=$codigos[0];// rif
     $vec[$id][1]=$codigos[1];// año nota entrega
     $vec[$id][2]=$codigos[2];// numero nota entrega
     $vec[$id][3]=$codigos[3];// codigo producto
     $vec[$id][4]=$codigos[4];// descripcion
     $vec[$id][5]=$codigos[5];// cod medida
     $vec[$id][6]=$codigos[6];// cantidad
     $vec[$id][7]=$codigos[7];// precio unitario
     $vec[$id][8]=$codigos[8];// año orden compra
     $vec[$id][9]=$codigos[9];// numero orden compra
     $vec[$id][10]=$codigos[10];// denominacion proveedor
     $vec[$id][11]=0;//estante
     $vec[$id][12]=0;//fila
     $vec[$id][13]=0;//columna
     $vec[$id][14]=0;//complemento sitio ubicacion
     $vec[$id][15]=0;//stock minimo
     $vec[$id][16]=0;//stock maximo
     $vec[$id][17]=0;//punto pedido
     $vec[$id]["status"]=$status;//status
	 $vec[$id]["id"]=$id;

	if(isset($_SESSION["items3"])){
		$_SESSION["items3"]=$_SESSION["items3"]+$vec;
	}else{
		$_SESSION["items3"]=$vec;
	}
	$_SESSION["items2"][$id]=null;


}


function agregar_productos_nota($var=null,$id=null){
	$this->layout = "ajax";
	$this->set("cscd01_unidad_medida",$this->cscd01_unidad_medida->findAll());
	if($var!=null){
				if(isset($this->data['ciap01']['estante'])){
				    $z=0;
					if($this->data['ciap01']['estante']==''){
					$this->set('errorMessage', 'debe seleccionar el estante de ubicación del producto');

					}else if($this->data['ciap01']['estante']!=0 && ($this->data['ciap01']['fila']=='' || $this->data['ciap01']['fila']==0)){
						$this->set('errorMessage', 'debe seleccionar la fila de ubicación del producto');
					}else if($this->data['ciap01']['estante']!=0 && ($this->data['ciap01']['entrepano']=='' || $this->data['ciap01']['entrepano']==0)){
						$this->set('errorMessage', 'debe seleccionar la columna de ubicación del producto');
					}else if(empty($this->data['ciap01']['minimo'])){
						$this->set('errorMessage', 'debe ingresar el stock mínimo');

					}else if(empty($this->data['ciap01']['maximo'])){
						$this->set('errorMessage', 'debe ingresar el stock máximo');

					}else if(empty($this->data['ciap01']['punto_pedido'])){
						$this->set('errorMessage', 'debe ingresar el punto de pedido');

					}else if(str_replace(',', '.', $this->data['ciap01']['minimo'])>str_replace(',', '.', $this->data['ciap01']['maximo'])){
						$this->set('errorMessage', 'el stock mínimo debe ser menor al stock máximo');

					}else if(str_replace(',', '.', $this->data['ciap01']['punto_pedido'])>str_replace(',', '.', $this->data['ciap01']['maximo'])){
						$this->set('errorMessage', 'el punto de pedido debe ser menor al stock máximo');

					}else if(str_replace(',', '.', $this->data['ciap01']['punto_pedido'])<str_replace(',', '.', $this->data['ciap01']['minimo']) ){
						$this->set('errorMessage', 'el punto de pedido debe ser mayor al stock mínimo');

					}else if($this->data['ciap01']['estante']!=0 && ($this->data['ciap01']['fila']==0 || $this->data['ciap01']['entrepano']==0)){
						$this->set('errorMessage', 'indique la fila y la columna de ubicación ');

					}else if($this->data['ciap01']['estante']==0 && empty($this->data['ciap01']['complemento_ubicacion'])){
						$this->set('errorMessage', 'ingrese el complemento del sitio de ubicación del producto');

					}else{
						$this->set('otro',$var);
						$z=1;
						$minimo= str_replace(',', '.', $this->data['ciap01']['minimo']);
						$maximo= str_replace(',', '.', $this->data['ciap01']['maximo']);
						$punto_pedido= str_replace(',', '.', $this->data['ciap01']['punto_pedido']);

						if($this->data['ciap01']['estante']==0){
							$estante=0;
							$fila=0;
							$entrepano=0;
						}else{
							$estante=$this->data['ciap01']['estante'];
							$fila=$this->data['ciap01']['fila'];
							$entrepano=$this->data['ciap01']['entrepano'];
						}

						if(empty($this->data['ciap01']['complemento_ubicacion'])){
							$complemento_ubicacion=0;
						}else{
							$complemento_ubicacion=$this->data['ciap01']['complemento_ubicacion'];
						}

					    $_SESSION ["items3"][$id][11]=$estante;
					    $_SESSION ["items3"][$id][12]=$fila;
					    $_SESSION ["items3"][$id][13]=$entrepano;
					    $_SESSION ["items3"][$id][14]=$complemento_ubicacion;
					    $_SESSION ["items3"][$id][15]=$minimo;
					    $_SESSION ["items3"][$id][16]=$maximo;
					    $_SESSION ["items3"][$id][17]=$punto_pedido;
						$_SESSION ["items3"][$id]["status"]=1;
//						pr($_SESSION ["items3"]);

					}
					if($z!=1){
						$this->set('no','');
						return;
					}

			}
	}

}


function ventana_notas_ubicacion($var=null,$id=null){
	$this->layout = "ajax";
	if($var==1){
		$this->set('datos',$_SESSION["items3"][$id]);
		$this->set("cscd01_unidad_medida",$this->cscd01_unidad_medida->findAll());
	}else{

	}
	$this->set('opcion',$var);

}


function limpiar1(){
	$this->layout = "ajax";
	$this->Session->delete("items2");
	$this->Session->delete("items3");

	$this->Session->delete("items4");

}

function boton_guardar($var){
	$this->layout="ajax";
	$this->set('tipo',$var);
}//boton_guardar


function ver_productos($dep_origen=null,$almacen_origen=null,$ano_origen=null,$numero_origen=null){
	$this->layout="ajax";
	$cod_presi 					= $this->Session->read('SScodpresi');
	$cod_entidad 				= $this->Session->read('SScodentidad');
	$cod_tipo_inst 				= $this->Session->read('SScodtipoinst');
	$cod_inst 					= $this->Session->read('SScodinst');
	$cod_dep 					= $this->Session->read('SScoddep');
	$this->set("cscd01_unidad_medida",$this->cscd01_unidad_medida->findAll());
	$this->limpiar_lista();
	$this->limpiar1();

	$this->set('dep_origen',$dep_origen);
	$this->set('almacen_origen',$almacen_origen);
	$this->set('ano_origen',$ano_origen);
	$this->set('numero_origen',$numero_origen);
	$almacen=$this->Session->read('cod_almacen');

	$productos="select
				a.cod_presi,
				a.cod_entidad,
				a.cod_tipo_inst,
				a.cod_inst,
				a.cod_dep,
				a.cod_almacen_salida,
				a.codigo_prod_serv,
				a.cantidad,
				b.denominacion,
				b.cod_medida,
				b.costo_maximo,
				b.costo_minimo,
				(select c.cod_prod_serv from ciad01_inventario_productos c where c.cod_presi=a.cod_presi and c.cod_entidad=a.cod_entidad and c.cod_tipo_inst=a.cod_tipo_inst and c.cod_inst=a.cod_inst and c.cod_dep='$cod_dep' and c.cod_prod_serv=a.codigo_prod_serv and c.cod_almacen='$almacen') as status
				from ciad01_inventario_salidas_detalles a,v_ciad01_inventario_productos b
				where
				a.cod_presi=b.cod_presi and a.cod_entidad=b.cod_entidad and a.cod_tipo_inst=b.cod_tipo_inst and a.cod_inst=b.cod_inst and a.cod_dep=b.cod_dep and a.codigo_prod_serv=b.cod_prod_serv and
				a.cod_almacen_salida=b.cod_almacen and a.cod_presi='$cod_presi' and a.cod_entidad='$cod_entidad' and a.cod_tipo_inst='$cod_tipo_inst' and a.cod_inst='$cod_inst' and a.cod_dep='$dep_origen' and a.ano_orden_salida=".$ano_origen." and a.cod_almacen_salida=".$almacen_origen." and a.numero_orden_salida=".$numero_origen;
				$productos=$this->ciad01_inventario_salidas_cuerpo->execute($productos);
$this->set('productos',$productos);
}//ver_productos



function ventana_transferir_ubicacion($id=null){
	$this->layout="ajax";
	$this->set('datos',$_SESSION["items4"][$id]);
	$this->set("cscd01_unidad_medida",$this->cscd01_unidad_medida->findAll());
}


function agregar_productos_transferir($id=null){
	$this->layout="ajax";
	$this->set("cscd01_unidad_medida",$this->cscd01_unidad_medida->findAll());
	if(isset($this->data['ciap01']['estante'])){
		    $z=0;
			if($this->data['ciap01']['estante']==''){
				$this->set('errorMessage', 'debe seleccionar el estante de ubicación del producto');

			}else if($this->data['ciap01']['estante']!=0 && ($this->data['ciap01']['fila']=='' || $this->data['ciap01']['fila']==0)){
				$this->set('errorMessage', 'debe seleccionar la fila de ubicación del producto');
			}else if($this->data['ciap01']['estante']!=0 && ($this->data['ciap01']['entrepano']=='' || $this->data['ciap01']['entrepano']==0)){
				$this->set('errorMessage', 'debe seleccionar la columna de ubicación del producto');
			}else if(empty($this->data['ciap01']['minimo'])){
				$this->set('errorMessage', 'debe ingresar el stock mínimo');

			}else if(empty($this->data['ciap01']['maximo'])){
				$this->set('errorMessage', 'debe ingresar el stock máximo');

			}else if(empty($this->data['ciap01']['punto_pedido'])){
				$this->set('errorMessage', 'debe ingresar el punto de pedido');

			}else if(str_replace(',', '.', $this->data['ciap01']['minimo'])>str_replace(',', '.', $this->data['ciap01']['maximo'])){
				$this->set('errorMessage', 'el stock mínimo debe ser menor al stock máximo');

			}else if(str_replace(',', '.', $this->data['ciap01']['punto_pedido'])>str_replace(',', '.', $this->data['ciap01']['maximo'])){
				$this->set('errorMessage', 'el punto de pedido debe ser menor al stock máximo');

			}else if(str_replace(',', '.', $this->data['ciap01']['punto_pedido'])<str_replace(',', '.', $this->data['ciap01']['minimo']) ){
				$this->set('errorMessage', 'el punto de pedido debe ser mayor al stock mínimo');

			}else if($this->data['ciap01']['estante']!=0 && ($this->data['ciap01']['fila']==0 || $this->data['ciap01']['entrepano']==0)){
				$this->set('errorMessage', 'indique la fila y la columna de ubicación ');

			}else if($this->data['ciap01']['estante']==0 && empty($this->data['ciap01']['complemento_ubicacion'])){
				$this->set('errorMessage', 'ingrese el complemento del sitio de ubicación del producto');

			}else{
				$this->set('otro','otro');
				$z=1;
				$minimo= str_replace(',', '.', $this->data['ciap01']['minimo']);
				$maximo= str_replace(',', '.', $this->data['ciap01']['maximo']);
				$punto_pedido= str_replace(',', '.', $this->data['ciap01']['punto_pedido']);

				if($this->data['ciap01']['estante']==0){
					$estante=0;
					$fila=0;
					$entrepano=0;
				}else{
					$estante=$this->data['ciap01']['estante'];
					$fila=$this->data['ciap01']['fila'];
					$entrepano=$this->data['ciap01']['entrepano'];
				}

				if(empty($this->data['ciap01']['complemento_ubicacion'])){
					$complemento_ubicacion=0;
				}else{
					$complemento_ubicacion=$this->data['ciap01']['complemento_ubicacion'];
				}

			    $_SESSION ["items4"][$id][9]=$estante;
			    $_SESSION ["items4"][$id][10]=$fila;
			    $_SESSION ["items4"][$id][11]=$entrepano;
			    $_SESSION ["items4"][$id][12]=$complemento_ubicacion;
			    $_SESSION ["items4"][$id][13]=$minimo;
			    $_SESSION ["items4"][$id][14]=$maximo;
			    $_SESSION ["items4"][$id][15]=$punto_pedido;
				$_SESSION ["items4"][$id]["status"]=1;
//						pr($_SESSION ["items4"]);

			}
			if($z!=1){
				$this->set('no','');
				return;
			}

	}

}


function retorna_numero($almacen=null,$num_rc=null,$var=null){
	$this->layout="ajax";
	$ano=$this->ano_ejecucion();
	if(isset($num_rc) && $num_rc!=0){
		$resultado=$this->ciad01_inventario_entradas_numero->execute("UPDATE  ciad01_inventario_entradas_numero SET situacion=1 WHERE ".$this->SQLCA()." and cod_almacen_entrada=".$almacen." and numero_recepcion=".$num_rc." and ano_recepcion=".$ano." and situacion=2");
	}

	//$this->('index');
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

				$this->Session->delete('cod_almacen');
				$this->Session->write('cod_almacen',$var3);
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


function guardar_notas(){
	$this->layout = "ajax";
	$cod_presi 					= $this->Session->read('SScodpresi');
	$cod_entidad 				= $this->Session->read('SScodentidad');
	$cod_tipo_inst 				= $this->Session->read('SScodtipoinst');
	$cod_inst 					= $this->Session->read('SScodinst');
	$cod_dep 					= $this->Session->read('SScoddep');
	if(empty($this->data['ciap01']['cod_almacen'])){
		$this->set('errorMessage', 'debe seleccionar el almacén receptor de los productos o materiales');

	}else if(empty($this->data['ciap01']['ano'])){
		$this->set('errorMessage', 'Por favor verifique el año de recepción');

	}else if(empty($this->data['ciap01']['numero_entrada'])){
		$this->set('errorMessage', 'por favor verifique al número de recepción');

	}else if(empty($this->data['ciap01']['fecha_entrada'])){
		$this->set('errorMessage', 'por favor verifique la fecha de recepción');

	}else if(!isset($_SESSION ["items3"]) || ($_SESSION ["items3"]==null)){
		$this->set('errorMessage', 'debe agregar los productos de entrada ');

	}else if(isset($this->data['ciap01']['contador']) && $this->data['ciap01']['contador']!=0){
		$this->set('errorMessage', 'Quedan productos por agregarle la ubicación, por favor verifique');

	}else{
		$cod_almacen=$this->data['ciap01']['cod_almacen'];
		$ano=$this->ano_ejecucion();
		$numero_entrada=$this->data['ciap01']['numero_entrada'];
		$fecha_entrada=$this->data['ciap01']['fecha_entrada'];
		$dep_origen=$cod_dep;///debo buscar la dep origen,queda pendiente
		if(empty($this->data['ciap01']['observaciones'])){
			$observaciones='';
		}else{
			$observaciones=$this->data['ciap01']['observaciones'];
		}
		$fecha_registro=date('Y-m-d');
		$usuario_registro=$_SESSION['nom_usuario'];
		$aux=$_SESSION ["items3"];
		foreach($_SESSION ["items3"] as $v){
			$rif=$v[0];
			$ano_nota_entrega=$v[1];
			$numero_nota_entrega=$v[2];
			$ano_orden_compra=$v[8];
			$numero_orden_compra=$v[9];
			break;
		}


		$SQL_INSERT ="BEGIN;INSERT INTO ciad01_inventario_entradas_cuerpo VALUES ('$cod_presi', '$cod_entidad' ,'$cod_tipo_inst' ,'$cod_inst' ,'$cod_dep' ,'$cod_almacen','$ano','$numero_entrada','$fecha_entrada','$dep_origen','$rif','$ano_nota_entrega','$numero_nota_entrega','$ano_orden_compra','$numero_orden_compra','1','$fecha_registro','$usuario_registro','1900-01-01','0','$observaciones','0','0','0','0')";
		$sw = $this->ciad01_inventario_entradas_cuerpo->execute($SQL_INSERT);

		$update=$this->cscd05_ordencompra_nota_entrega_encabezado->execute("update cscd05_ordencompra_nota_entrega_encabezado set transferido_almacen=1 where ".$this->SQLCA()." and rif='".$rif."' and ano_nota_entrega=".$ano_nota_entrega." and numero_nota_entrega='".$numero_nota_entrega."' and ano_orden_compra='$ano_orden_compra' and numero_orden_compra=".$numero_orden_compra);
			///EN EL FOREACH SE ENCUENTRA EL PROCESO DE REGISTRAS LOS DETALLES DE LA ENTRADA Y POR LO TANTO VERIFICAR SI EXISTE O NO EL PRODUCTO EN EL INVENTARIO Y REALIZAR LOS CALCULOS NECESARIOS POR SI EXISTE O NO
		 foreach($_SESSION ["items3"] as $guardar){
					if($guardar!=null){
								if($this->ciad01_inventario_productos->FindCount($this->SQLCA()." and cod_almacen='$cod_almacen' and cod_prod_serv=".$guardar[3])!=0){
												//existe el producto
												$ver=$this->ciad01_inventario_productos->execute("select * from ciad01_inventario_productos where ".$this->SQLCA()." and cod_almacen='$cod_almacen' and cod_prod_serv=".$guardar[3]);
												$entradas=($ver[0][0]['numero_entradas']+$guardar[6]);
												if($guardar[7]>=$ver[0][0]['costo_maximo']){
													$costo_maximo=$guardar[7];
												}else{
													$costo_maximo=$ver[0][0]['costo_maximo'];
												}

												if($guardar[7]<=$ver[0][0]['costo_minimo']){
													$costo_minimo=$guardar[7];
												}else{
													$costo_minimo=$ver[0][0]['costo_minimo'];
												}

												if($ver[0][0]['costo_minimo']==0.00){
													$costo_minimo=$guardar[7];
												}

												$a=$this->ciad01_inventario_entradas_detalles->execute("select sum(cantidad) as total_entrada from ciad01_inventario_entradas_detalles where ".$this->SQLCA()." and cod_almacen_entrada=".$cod_almacen." and codigo_prod_serv=".$guardar[3]);
												$b=$this->ciad01_inventario_salidas_detalles->execute("select sum(cantidad) as total_salida from ciad01_inventario_salidas_detalles where ".$this->SQLCA()." and cod_almacen_salida=".$cod_almacen." and codigo_prod_serv=".$guardar[3]);
												$total_entrada=($a[0][0]['total_entrada']+$guardar[6]);
												$total_salida=($b[0][0]['total_salida']+$guardar[6]);
												if($total_entrada>1){
													$stock_maximo=($total_entrada/2);
												}else{
													$stock_maximo=$total_entrada;
												}

												if($total_salida>1){
													$stock_minimo=($total_salida/2);
												}else{
													$stock_minimo=$total_salida;
												}
												if (is_double($stock_maximo)){
												   $stock_maximo=intval($stock_maximo);
												   $stock_maximo+=1;
												}
												if (is_double($stock_minimo)){
												   $stock_minimo=intval($stock_minimo);
												   $stock_minimo+=1;
												}
												$punto_pedido=($stock_maximo-$stock_minimo);
												$sw1=$this->ciad01_inventario_productos->execute("update ciad01_inventario_productos set numero_entradas='$entradas',costo_maximo='$costo_maximo',costo_minimo='$costo_minimo',stock_maximo='$stock_maximo',stock_minimo='$stock_minimo',punto_pedido='$punto_pedido' where ".$this->SQLCA()." and cod_almacen=".$cod_almacen." and cod_prod_serv=".$guardar[3]);
								}else{
												//no existe el producto hay que crearlo en la tabla inventario
												if($guardar[14]=='0'){
													$campos="(cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_almacen,cod_prod_serv, stock_maximo, stock_minimo, punto_pedido, estante_numero,fila_numero, columna_numero, numero_entradas, numero_salidas, costo_maximo, costo_minimo, fecha_registro, username_registro,cod_snc)";
													$insert="('$cod_presi', '$cod_entidad' ,'$cod_tipo_inst' ,'$cod_inst' ,'$cod_dep' ,'$cod_almacen','$guardar[3]','$guardar[16]','$guardar[15]','$guardar[17]','$guardar[11]','$guardar[12]','$guardar[13]','$guardar[6]','0','$guardar[7]','$guardar[7]','$fecha_registro','$usuario_registro','$cod_snc')";
												}else{
													$campos="(cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_almacen,cod_prod_serv, stock_maximo, stock_minimo, punto_pedido, estante_numero,fila_numero, columna_numero,complemento_sitio_almacenaje, numero_entradas, numero_salidas, costo_maximo, costo_minimo, fecha_registro, username_registro,cod_snc)";
													$insert="('$cod_presi', '$cod_entidad' ,'$cod_tipo_inst' ,'$cod_inst' ,'$cod_dep' ,'$cod_almacen','$guardar[3]','$guardar[16]','$guardar[15]','$guardar[17]','$guardar[11]','$guardar[12]','$guardar[13]','$guardar[14]','$guardar[6]','0','$guardar[7]','$guardar[7]','$fecha_registro','$usuario_registro','$cod_snc')";
												}
												$ver=$this->ciad01_inventario_productos->execute("select cod_snc from cscd01_catalogo where codigo_prod_serv=".$guardar[3]);
												$cod_snc=$ver[0][0]['cod_snc'];

												$SQL_INSERT1 ="INSERT INTO ciad01_inventario_productos ".$campos." VALUES ".$insert;
												$sw1 = $this->ciad01_inventario_productos->execute($SQL_INSERT1);
								}
								/////AQUI GUARDO LOS DETALLES DE LA ENTRADA
								$SQL_INSERT2 ="INSERT INTO ciad01_inventario_entradas_detalles VALUES ('$cod_presi', '$cod_entidad' ,'$cod_tipo_inst' ,'$cod_inst' ,'$cod_dep' ,'$cod_almacen','$ano','$numero_entrada','$guardar[3]','$guardar[6]','$guardar[7]')";
								$sw2 = $this->ciad01_inventario_entradas_detalles->execute($SQL_INSERT2);


					}
	     }

		$this->ciad01_inventario_entradas_cuerpo->execute("update ciad01_inventario_entradas_numero set situacion=3 where ".$this->SQLCA()." and ano_recepcion='$ano' and cod_almacen_entrada='$cod_almacen' and numero_recepcion='$numero_entrada' and situacion=2");
		if($sw>1 && $sw2>1){
		$this->ciad01_inventario_entradas_cuerpo->execute("COMMIT");
		$this->set('Message_existe', 'REGISTRO EXITOSO');
 		$this->set('guardado', 'si');


		}else{
			$this->ciad01_inventario_entradas_cuerpo->execute("ROLLBACK");
			$this->set('errorMessage', 'INSERCI&Oacute;N FALLIDA DE LOS DATOS');
		}


	}


}//guardar_notas





function guardar_transferir(){
	$this->layout = "ajax";
	$cod_presi 					= $this->Session->read('SScodpresi');
	$cod_entidad 				= $this->Session->read('SScodentidad');
	$cod_tipo_inst 				= $this->Session->read('SScodtipoinst');
	$cod_inst 					= $this->Session->read('SScodinst');
	$cod_dep 					= $this->Session->read('SScoddep');

	if(empty($this->data['ciap01']['cod_almacen'])){
		$this->set('errorMessage', 'debe seleccionar el almacén receptor de los productos o materiales');

	}else if(empty($this->data['ciap01']['ano'])){
		$this->set('errorMessage', 'Por favor verifique el año de recepción');

	}else if(empty($this->data['ciap01']['numero_entrada'])){
		$this->set('errorMessage', 'por favor verifique al número de recepción');

	}else if(empty($this->data['ciap01']['fecha_entrada'])){
		$this->set('errorMessage', 'por favor verifique la fecha de recepción');

	}else if(!isset($_SESSION ["items4"]) || ($_SESSION ["items4"]==null)){
		$this->set('errorMessage', 'debe agregar los productos de entrada ');

	}else if(isset($this->data['ciap01']['contador']) && $this->data['ciap01']['contador']!=0){
		$this->set('errorMessage', 'Quedan productos por agregarle la ubicación, por favor verifique');

	}else{
		$cod_almacen=$this->data['ciap01']['cod_almacen'];
		$ano=$this->ano_ejecucion();
		$numero_entrada=$this->data['ciap01']['numero_entrada'];
		$fecha_entrada=$this->data['ciap01']['fecha_entrada'];
		$dep_origen=$cod_dep;///debo buscar la dep origen,queda pendiente
		if(empty($this->data['ciap01']['observaciones'])){
			$observaciones='';
		}else{
			$observaciones=$this->data['ciap01']['observaciones'];
		}
		$fecha_registro=date('Y-m-d');
		$usuario_registro=$_SESSION['nom_usuario'];
		$rif=0;
		$ano_nota_entrega=0;
		$numero_nota_entrega=0;
		$ano_orden_compra=0;
		$numero_orden_compra=0;
		$aux=$_SESSION["items4"];


		$SQL_INSERT ="BEGIN;INSERT INTO ciad01_inventario_entradas_cuerpo VALUES ('$cod_presi', '$cod_entidad' ,'$cod_tipo_inst' ,'$cod_inst' ,'$cod_dep' ,'$cod_almacen','$ano','$numero_entrada','$fecha_entrada','$dep_origen','$rif','$ano_nota_entrega','$numero_nota_entrega','$ano_orden_compra','$numero_orden_compra','1','$fecha_registro','$usuario_registro','1900-01-01','0','$observaciones','".$aux[0][4]."','".$aux[0][5]."','".$aux[0][6]."','".$aux[0][7]."')";
		$sw = $this->ciad01_inventario_entradas_cuerpo->execute($SQL_INSERT);

		$update=$this->ciad01_inventario_salidas_cuerpo->execute("update ciad01_inventario_salidas_cuerpo set ano_recepcion='$ano',numero_recepcion='$numero_entrada',entregado=1 where ".$this->condicionNDEP()." and cod_dep=".$aux[0][4]." and cod_almacen_salida=".$aux[0][5]." and ano_orden_salida=".$aux[0][6]." and numero_orden_salida=".$aux[0][7]);

			///EN EL FOREACH SE ENCUENTRA EL PROCESO DE REGISTRAS LOS DETALLES DE LA ENTRADA Y POR LO TANTO VERIFICAR SI EXISTE O NO EL PRODUCTO EN EL INVENTARIO Y REALIZAR LOS CALCULOS NECESARIOS POR SI EXISTE O NO
		 foreach($_SESSION ["items4"] as $guardar){
					if($guardar!=null){
								if($this->ciad01_inventario_productos->FindCount($this->SQLCA()." and cod_almacen='$cod_almacen' and cod_prod_serv=".$guardar[0])!=0){
												//existe el producto
												$guardar[2]=str_replace(',', '.', $guardar[2]);
												$precio_unitario=$this->Formato1($guardar[3]);
												$ver=$this->ciad01_inventario_productos->execute("select * from ciad01_inventario_productos where ".$this->SQLCA()." and cod_almacen='$cod_almacen' and cod_prod_serv=".$guardar[0]);
												$entradas=($ver[0][0]['numero_entradas']+$guardar[2]);
												if($precio_unitario>=$ver[0][0]['costo_maximo']){
													$costo_maximo=$precio_unitario;
												}else{
													$costo_maximo=$ver[0][0]['costo_maximo'];
												}

												if($precio_unitario<=$ver[0][0]['costo_minimo']){
													$costo_minimo=$precio_unitario;
												}else{
													$costo_minimo=$ver[0][0]['costo_minimo'];
												}

												if($ver[0][0]['costo_minimo']==0.00){
													$costo_minimo=$precio_unitario;
												}

												$a=$this->ciad01_inventario_entradas_detalles->execute("select sum(cantidad) as total_entrada from ciad01_inventario_entradas_detalles where ".$this->SQLCA()." and cod_almacen_entrada=".$cod_almacen." and codigo_prod_serv=".$guardar[0]);
												$b=$this->ciad01_inventario_salidas_detalles->execute("select sum(cantidad) as total_salida from ciad01_inventario_salidas_detalles where ".$this->SQLCA()." and cod_almacen_salida=".$cod_almacen." and codigo_prod_serv=".$guardar[0]);
												echo $total_entrada=($a[0][0]['total_entrada']+$guardar[2]);
												echo $total_salida=($b[0][0]['total_salida']+$guardar[2]);
												if($total_entrada>1){
													$stock_maximo=($total_entrada/2);
												}else{
													$stock_maximo=$total_entrada;
												}

												if($total_salida>1){
													$stock_minimo=($total_salida/2);
												}else{
													$stock_minimo=$total_salida;
												}

												if (is_double($stock_maximo)){
												   $stock_maximo=intval($stock_maximo);
												   $stock_maximo+=1;
												}
												if (is_double($stock_minimo)){
												   $stock_minimo=intval($stock_minimo);
												   $stock_minimo+=1;
												}
												$punto_pedido=($stock_maximo-$stock_minimo);
												$sw1=$this->ciad01_inventario_productos->execute("update ciad01_inventario_productos set numero_entradas='$entradas',costo_maximo='$costo_maximo',costo_minimo='$costo_minimo',stock_maximo='$stock_maximo',stock_minimo='$stock_minimo',punto_pedido='$punto_pedido' where ".$this->SQLCA()." and cod_almacen=".$cod_almacen." and cod_prod_serv=".$guardar[0]);
								}else{
												//no existe el producto hay que crearlo en la tabla inventario
												$guardar[2]=str_replace(',', '.', $guardar[2]);
												$precio_unitario=$this->Formato1($guardar[3]);
	    										if($guardar[12]=='0'){
													$campos="(cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_almacen,cod_prod_serv, stock_maximo, stock_minimo, punto_pedido, estante_numero,fila_numero, columna_numero, numero_entradas, numero_salidas, costo_maximo, costo_minimo, fecha_registro, username_registro,cod_snc)";
													$insert="('$cod_presi', '$cod_entidad' ,'$cod_tipo_inst' ,'$cod_inst' ,'$cod_dep' ,'$cod_almacen','$guardar[0]','$guardar[14]','$guardar[13]','$guardar[15]','$guardar[9]','$guardar[10]','$guardar[11]','$guardar[2]','0','$precio_unitario','$precio_unitario','$fecha_registro','$usuario_registro','$cod_snc')";
												}else{
													$campos="(cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_almacen,cod_prod_serv, stock_maximo, stock_minimo, punto_pedido, estante_numero,fila_numero, columna_numero,complemento_sitio_almacenaje ,numero_entradas, numero_salidas, costo_maximo, costo_minimo, fecha_registro, username_registro,cod_snc)";
													$insert="('$cod_presi', '$cod_entidad' ,'$cod_tipo_inst' ,'$cod_inst' ,'$cod_dep' ,'$cod_almacen','$guardar[0]','$guardar[14]','$guardar[13]','$guardar[15]','$guardar[9]','$guardar[10]','$guardar[11]','$guardar[12]','$guardar[2]','0','$precio_unitario','$precio_unitario','$fecha_registro','$usuario_registro','$cod_snc')";
												}
												$ver=$this->ciad01_inventario_productos->execute("select cod_snc from cscd01_catalogo where codigo_prod_serv=".$guardar[0]);
												$cod_snc=$ver[0][0]['cod_snc'];
												$SQL_INSERT1 ="INSERT INTO ciad01_inventario_productos ".$campos." VALUES ".$insert;
												$sw1 = $this->ciad01_inventario_productos->execute($SQL_INSERT1);
								}
								/////AQUI GUARDO LOS DETALLES DE LA ENTRADA
								$SQL_INSERT2 ="INSERT INTO ciad01_inventario_entradas_detalles VALUES ('$cod_presi', '$cod_entidad' ,'$cod_tipo_inst' ,'$cod_inst' ,'$cod_dep' ,'$cod_almacen','$ano','$numero_entrada','$guardar[0]','$guardar[2]','$precio_unitario')";
								$sw2 = $this->ciad01_inventario_entradas_detalles->execute($SQL_INSERT2);


					}
	     }

		$this->ciad01_inventario_entradas_cuerpo->execute("update ciad01_inventario_entradas_numero set situacion=3 where ".$this->SQLCA()." and ano_recepcion='$ano' and cod_almacen_entrada='$cod_almacen' and numero_recepcion='$numero_entrada' and situacion=2");
		if($sw>1 && $sw2>1){
		$this->ciad01_inventario_entradas_cuerpo->execute("COMMIT");
		$this->set('Message_existe', 'REGISTRO EXITOSO');
 		$this->set('guardado', 'si');


		}else{
			$this->ciad01_inventario_entradas_cuerpo->execute("ROLLBACK");
			$this->set('errorMessage', 'INSERCI&Oacute;N FALLIDA DE LOS DATOS');
		}


	}



}//guardar_transferir

function guardar_manual(){
	$this->layout = "ajax";
	$cod_presi 					= $this->Session->read('SScodpresi');
	$cod_entidad 				= $this->Session->read('SScodentidad');
	$cod_tipo_inst 				= $this->Session->read('SScodtipoinst');
	$cod_inst 					= $this->Session->read('SScodinst');
	$cod_dep 					= $this->Session->read('SScoddep');

	if(empty($this->data['ciap01']['cod_almacen'])){
		$this->set('errorMessage', 'debe seleccionar el almacén receptor de los productos o materiales');

	}else if(empty($this->data['ciap01']['ano'])){
		$this->set('errorMessage', 'Por favor verifique el año de recepción');

	}else if(empty($this->data['ciap01']['numero_entrada'])){
		$this->set('errorMessage', 'por favor verifique al número de recepción');

	}else if(empty($this->data['ciap01']['fecha_entrada'])){
		$this->set('errorMessage', 'por favor verifique la fecha de recepción');

	}else if(!isset($_SESSION ["items1"]) || ($_SESSION ["items1"]==null)){
		$this->set('errorMessage', 'debe agregar los productos de entrada ');

	}else{
		$cod_almacen=$this->data['ciap01']['cod_almacen'];
		$ano=$this->ano_ejecucion();
		$numero_entrada=$this->data['ciap01']['numero_entrada'];
		$fecha_entrada=$this->data['ciap01']['fecha_entrada'];
		$dep_origen=$cod_dep;///debo buscar la dep origen,queda pendiente
		if(empty($this->data['ciap01']['observaciones'])){
			$observaciones='';
		}else{
			$observaciones=$this->data['ciap01']['observaciones'];
		}
		$fecha_registro=date('Y-m-d');
		$usuario_registro=$_SESSION['nom_usuario'];
		$rif=0;
		$ano_nota_entrega=0;
		$numero_nota_entrega=0;
		$ano_orden_compra=0;
		$numero_orden_compra=0;

		$SQL_INSERT ="BEGIN;INSERT INTO ciad01_inventario_entradas_cuerpo VALUES ('$cod_presi', '$cod_entidad' ,'$cod_tipo_inst' ,'$cod_inst' ,'$cod_dep' ,'$cod_almacen','$ano','$numero_entrada','$fecha_entrada','$dep_origen','$rif','$ano_nota_entrega','$numero_nota_entrega','$ano_orden_compra','$numero_orden_compra','1','$fecha_registro','$usuario_registro','1900-01-01','0','$observaciones','0','0','0','0')";
		$sw = $this->ciad01_inventario_entradas_cuerpo->execute($SQL_INSERT);

			///EN EL FOREACH SE ENCUENTRA EL PROCESO DE REGISTRAS LOS DETALLES DE LA ENTRADA Y POR LO TANTO VERIFICAR SI EXISTE O NO EL PRODUCTO EN EL INVENTARIO Y REALIZAR LOS CALCULOS NECESARIOS POR SI EXISTE O NO
		 foreach($_SESSION ["items1"] as $guardar){
					if($guardar!=null){
								if($this->ciad01_inventario_productos->FindCount($this->SQLCA()." and cod_almacen='$cod_almacen' and cod_prod_serv=".$guardar[0])!=0){
												//existe el producto
												$guardar[2]=str_replace(',', '.', $guardar[2]);
												$precio_unitario=$this->Formato1($guardar[3]);
												$ver=$this->ciad01_inventario_productos->execute("select * from ciad01_inventario_productos where ".$this->SQLCA()." and cod_almacen='$cod_almacen' and cod_prod_serv=".$guardar[0]);
												$entradas=($ver[0][0]['numero_entradas']+$guardar[2]);

												if($precio_unitario>=$ver[0][0]['costo_maximo']){
													$costo_maximo=$precio_unitario;
												}else{
													$costo_maximo=$ver[0][0]['costo_maximo'];
												}


												if($precio_unitario<=$ver[0][0]['costo_minimo']){
													$costo_minimo=$precio_unitario;
												}else{
													$costo_minimo=$ver[0][0]['costo_minimo'];
												}

												if($ver[0][0]['costo_minimo']==0.00){
													$costo_minimo=$precio_unitario;
												}


												$a=$this->ciad01_inventario_entradas_detalles->execute("select sum(cantidad) as total_entrada from ciad01_inventario_entradas_detalles where ".$this->SQLCA()." and cod_almacen_entrada=".$cod_almacen." and codigo_prod_serv=".$guardar[0]);
												$b=$this->ciad01_inventario_salidas_detalles->execute("select sum(cantidad) as total_salida from ciad01_inventario_salidas_detalles where ".$this->SQLCA()." and cod_almacen_salida=".$cod_almacen." and codigo_prod_serv=".$guardar[0]);
												$total_entrada=($a[0][0]['total_entrada']+$guardar[2]);
												$total_salida=($b[0][0]['total_salida']+$guardar[2]);
												if($total_entrada>1){
													$stock_maximo=($total_entrada/2);
												}else{
													$stock_maximo=$total_entrada;
												}

												if($total_salida>1){
													$stock_minimo=($total_salida/2);
												}else{
													$stock_minimo=$total_salida;
												}
												if (is_double($stock_maximo)){
												   $stock_maximo=intval($stock_maximo);
												   $stock_maximo+=1;
												}
												if (is_double($stock_minimo)){
												   $stock_minimo=intval($stock_minimo);
												   $stock_minimo+=1;
												}
												$punto_pedido=($stock_maximo-$stock_minimo);
												$sw1=$this->ciad01_inventario_productos->execute("update ciad01_inventario_productos set numero_entradas='$entradas',costo_maximo='$costo_maximo',costo_minimo='$costo_minimo',stock_maximo='$stock_maximo',stock_minimo='$stock_minimo',punto_pedido='$punto_pedido' where ".$this->SQLCA()." and cod_almacen=".$cod_almacen." and cod_prod_serv=".$guardar[0]);
								}else{
												//no existe el producto hay que crearlo en la tabla inventario
												$guardar[2]=str_replace(',', '.', $guardar[2]);
												$precio_unitario=$this->Formato1($guardar[3]);

												$stock_maximo=$guardar[6];
												$stock_minimo=$guardar[5];
												$punto_pedido=$guardar[7];

												$ver=$this->ciad01_inventario_productos->execute("select cod_snc from cscd01_catalogo where codigo_prod_serv=".$guardar[0]);
												$cod_snc=$ver[0][0]['cod_snc'];
												if($guardar[11]=='0'){
													$campos="(cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_almacen,cod_prod_serv, stock_maximo, stock_minimo, punto_pedido, estante_numero,fila_numero, columna_numero, numero_entradas, numero_salidas, costo_maximo, costo_minimo, fecha_registro, username_registro,cod_snc)";
													$insert="('$cod_presi', '$cod_entidad' ,'$cod_tipo_inst' ,'$cod_inst' ,'$cod_dep' ,'$cod_almacen','$guardar[0]','$stock_maximo','$stock_minimo','$punto_pedido','$guardar[8]','$guardar[9]','$guardar[10]','$guardar[2]','0','$precio_unitario','$precio_unitario','$fecha_registro','$usuario_registro','$cod_snc')";
												}else{
													$campos="(cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_almacen,cod_prod_serv, stock_maximo, stock_minimo, punto_pedido, estante_numero,fila_numero, columna_numero,complemento_sitio_almacenaje ,numero_entradas, numero_salidas, costo_maximo, costo_minimo, fecha_registro, username_registro,cod_snc)";
													$insert="('$cod_presi', '$cod_entidad' ,'$cod_tipo_inst' ,'$cod_inst' ,'$cod_dep' ,'$cod_almacen','$guardar[0]','$stock_maximo','$stock_minimo','$punto_pedido','$guardar[8]','$guardar[9]','$guardar[10]','$guardar[11]','$guardar[2]','0','$precio_unitario','$precio_unitario','$fecha_registro','$usuario_registro','$cod_snc')";
												}

												 $SQL_INSERT1 ="INSERT INTO ciad01_inventario_productos ".$campos." VALUES ".$insert;
												$sw1 = $this->ciad01_inventario_productos->execute($SQL_INSERT1);
								}
								/////AQUI GUARDO LOS DETALLES DE LA ENTRADA
								$SQL_INSERT2 ="INSERT INTO ciad01_inventario_entradas_detalles VALUES ('$cod_presi', '$cod_entidad' ,'$cod_tipo_inst' ,'$cod_inst' ,'$cod_dep' ,'$cod_almacen','$ano','$numero_entrada','$guardar[0]','$guardar[2]','$precio_unitario')";
								$sw2 = $this->ciad01_inventario_entradas_detalles->execute($SQL_INSERT2);


					}
	     }

		$this->ciad01_inventario_entradas_cuerpo->execute("update ciad01_inventario_entradas_numero set situacion=3 where ".$this->SQLCA()." and ano_recepcion='$ano' and cod_almacen_entrada='$cod_almacen' and numero_recepcion='$numero_entrada' and situacion=2");
		if($sw>1 && $sw2>1){
		$this->ciad01_inventario_entradas_cuerpo->execute("COMMIT");
		$this->set('Message_existe', 'REGISTRO EXITOSO');
 		$this->set('guardado', 'si');


		}else{
			$this->ciad01_inventario_entradas_cuerpo->execute("ROLLBACK");
			$this->set('errorMessage', 'INSERCI&Oacute;N FALLIDA DE LOS DATOS');
		}


	}



}//guardar_manual



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
					if(is_numeric($var2)){  $sql   = " (codigo_prod_serv::text LIKE '%$var2%')  or   ";}else{ $sql = ""; }
					$Tfilas=$this->cscd01_catalogo->findCount($sql." (mayus_acentos(denominacion)  LIKE mayus_acentos('%$var2%') )  OR  (cod_snc LIKE '%$var2%')   ");
					        if($Tfilas!=0){
					        	$pagina=1;
					        	$Tfilas=(int)ceil($Tfilas/100);
					        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
								$this->set('total_paginas',$Tfilas);
								$this->set('pagina_actual',$pagina);
								$this->set('ultimo',$Tfilas);
					     	    $datos_filas=$this->cscd01_catalogo->findAll($sql." (mayus_acentos(denominacion)  LIKE mayus_acentos('%$var2%') )  OR  (cod_snc LIKE '%$var2%')   ",null,"codigo_prod_serv ASC",100,1,null);
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
						if(is_numeric($var22)){ $sql   = " (codigo_prod_serv::text LIKE '%$var22%')  or   ";}else{ $sql = "";}
						$Tfilas=$this->cscd01_catalogo->findCount($sql." (mayus_acentos(denominacion)  LIKE mayus_acentos('%$var22%') )  OR  (cod_snc LIKE '%$var22%')   ");
						        if($Tfilas!=0){
						        	$pagina=$var3;
						        	$Tfilas=(int)ceil($Tfilas/100);
						        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
									$this->set('total_paginas',$Tfilas);
									$this->set('pagina_actual',$pagina);
									$this->set('ultimo',$Tfilas);
						     	    $datos_filas=$this->cscd01_catalogo->findAll($sql." (mayus_acentos(denominacion)  LIKE mayus_acentos('%$var22%') )  OR  (cod_snc LIKE '%$var22%')   ",null,"codigo_prod_serv ASC",100,$pagina,null);
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
	$sql="select
			a.codigo_prod_serv,
			a.denominacion,
			a.cod_snc,
			a.denominacion_snc,a.cod_medida
			FROM v_cscd01_catalogo_con_snc_denominacion a WHERE a.codigo_prod_serv='".$var2."'
			";

	$almacen=$this->Session->read('cod_almacen');
	$verifica=$this->ciad01_inventario_productos->execute("select * from ciad01_inventario_productos where ".$this->SQLCA()." and cod_almacen='$almacen' and cod_prod_serv='".$var2."'");
	if($verifica!=null){
		$this->set('muestra','no');
	}else{
		$this->set('muestra','si');
	}

	$datos=$this->cscd01_catalogo->execute($sql);
	$this->set('datos',$datos);
	$this->set("cscd01_unidad_medida",$this->cscd01_unidad_medida->findAll());


}//fin function


function ventana_manual($prod=null){
	$this->layout="ajax";
	$sql="select
		a.codigo_prod_serv,
		a.denominacion,
		a.cod_snc,
		a.denominacion_snc,a.cod_medida
		FROM v_cscd01_catalogo_con_snc_denominacion a WHERE a.codigo_prod_serv='".$prod."'
		";
	$datos=$this->cscd01_catalogo->execute($sql);
	$this->set('datos',$datos);
	$this->set("cscd01_unidad_medida",$this->cscd01_unidad_medida->findAll());
}



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


function calcula_total($var=null,$var2=null){
		$this->layout="ajax";
		$var2=$this->Formato1($var2);
		if($var==1){
			$this->Session->delete('cantidad');
			$this->Session->write('cantidad',$var2);
			echo "<script>";
				echo "document.getElementById('precio').value='';";
				echo "document.getElementById('total').value='';";
			echo "</script>";
		}else{
			$var1=$this->Session->read('cantidad');
			echo "<script>";
			echo "if(document.getElementById('cantidad1').value!=''){
							var n=(".$var1."*".$var2.");
							n=redondear(n,2);
							document.getElementById('total1').value=n;
							moneda('total1');
					}";
			echo "</script>";
		}
}



function agregar_producto($var=null,$otro=null) {
	$this->layout="ajax";

	$this->set('otro',$otro);

	if(empty($this->data['ciap01']['cantidad'])){
		$this->set('errorMessage', 'Debe ingresar la cantidad');
		$this->set('no','');
		if(!isset($_SESSION["contador1"])){
 			$this->set('vacio','');
 		}
		return;
	}

	if(empty($this->data['ciap01']['precio'])){
		$this->set('errorMessage', 'Debe ingresar el precio unitario');
		$this->set('no','');
		if(!isset($_SESSION["contador1"])){
 			$this->set('vacio','');
 		}
		return;
	}

	if(isset($this->data['ciap01']['estante'])){
		    $z=0;
			if($this->data['ciap01']['estante']==''){
			$this->set('errorMessage', 'debe seleccionar el estante de ubicación del producto');

			}else if($this->data['ciap01']['estante']!=0 && ($this->data['ciap01']['fila']=='' || $this->data['ciap01']['fila']==0)){
				$this->set('errorMessage', 'debe seleccionar la fila de ubicación del producto');
			}else if($this->data['ciap01']['estante']!=0 && ($this->data['ciap01']['entrepano']=='' || $this->data['ciap01']['entrepano']==0)){
				$this->set('errorMessage', 'debe seleccionar la columna de ubicación del producto');
			}else if(empty($this->data['ciap01']['minimo'])){
				$this->set('errorMessage', 'debe ingresar el stock mínimo');

			}else if(empty($this->data['ciap01']['maximo'])){
				$this->set('errorMessage', 'debe ingresar el stock máximo');

			}else if(empty($this->data['ciap01']['punto_pedido'])){
				$this->set('errorMessage', 'debe ingresar el punto de pedido');

			}else if(str_replace(',', '.', $this->data['ciap01']['minimo'])>str_replace(',', '.', $this->data['ciap01']['maximo'])){
				$this->set('errorMessage', 'el stock mínimo debe ser menor al stock máximo');

			}else if(str_replace(',', '.', $this->data['ciap01']['punto_pedido'])>str_replace(',', '.', $this->data['ciap01']['maximo'])){
				$this->set('errorMessage', 'el punto de pedido debe ser menor al stock máximo');

			}else if(str_replace(',', '.', $this->data['ciap01']['punto_pedido'])<str_replace(',', '.', $this->data['ciap01']['minimo']) ){
				$this->set('errorMessage', 'el punto de pedido debe ser mayor al stock mínimo');

			}else if($this->data['ciap01']['estante']!=0 && ($this->data['ciap01']['fila']==0 || $this->data['ciap01']['entrepano']==0)){
				$this->set('errorMessage', 'indique la fila y la columna de ubicación ');

			}else if($this->data['ciap01']['estante']==0 && empty($this->data['ciap01']['complemento_ubicacion'])){
				$this->set('errorMessage', 'ingrese el complemento del sitio de ubicación del producto');

			}else{
				$z=1;
				$minimo= str_replace(',', '.', $this->data['ciap01']['minimo']);
				$maximo= str_replace(',', '.', $this->data['ciap01']['maximo']);
				$punto_pedido= str_replace(',', '.', $this->data['ciap01']['punto_pedido']);

				if($this->data['ciap01']['estante']==0){
					$estante=0;
					$fila=0;
					$entrepano=0;
				}else{
					$estante=$this->data['ciap01']['estante'];
					$fila=$this->data['ciap01']['fila'];
					$entrepano=$this->data['ciap01']['entrepano'];
				}

				if(empty($this->data['ciap01']['complemento_ubicacion'])){
					$complemento_ubicacion=0;
				}else{
					$complemento_ubicacion=$this->data['ciap01']['complemento_ubicacion'];
				}

			}
			if($z!=1){
				$this->set('no','');
				if(!isset($_SESSION["contador1"])){
		 			$this->set('vacio','');
		 		}
				return;
			}

	}else{
		$minimo= 0;
		$maximo= 0;
		$punto_pedido= 0;
		$estante=0;
		$fila=0;
		$entrepano=0;
		$complemento_ubicacion=0;


	}



	    $producto=$this->data['ciap01']['cod_producto'];
	    $deno_producto=$this->data['ciap01']['deno_producto'];
	    $cantidad=$this->data['ciap01']['cantidad'];
	    $precio=$this->data['ciap01']['precio'];
	    $total=($this->Formato1($this->data['ciap01']['cantidad'])*$this->Formato1($this->data['ciap01']['precio']));



	if(isset($_SESSION["contador1"])){
        $_SESSION["contador1"]=$_SESSION["contador1"]+1;
	}else{
		$_SESSION["contador1"]=1;
	}

	if(isset($var) && !empty($var)){

			$cod[0]=$producto;
			$cod[1]=$deno_producto;
			$cod[2]=$cantidad;
			$cod[3]=$precio;
			$cod[4]=$total;
			$cod[5]=$minimo;
			$cod[6]=$maximo;
			$cod[7]=$punto_pedido;
			$cod[8]=$estante;
			$cod[9]=$fila;
			$cod[10]=$entrepano;
			$cod[11]=$complemento_ubicacion;

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
				     $vec[$i][3]=$precio;
				     $vec[$i][4]=$total;
				     $vec[$i][5]=$minimo;
    				 $vec[$i][6]=$maximo;
					 $vec[$i][7]=$punto_pedido;
					 $vec[$i][8]=$estante;
					 $vec[$i][9]=$fila;
					 $vec[$i][10]=$entrepano;
					 $vec[$i][11]=$complemento_ubicacion;
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
	$datos  = $this->ciad01_inventario_entradas_cuerpo->execute(" SELECT DISTINCT ano_recepcion FROM ciad01_inventario_entradas_cuerpo WHERE ".$this->SQLCA()." and cod_almacen_entrada=".$_SESSION['cod_almacen']." ORDER BY ano_recepcion ASC");
	if(count($datos)!=0){
		foreach($datos as $n){
			$lista[$n[0]['ano_recepcion']]=$n[0]['ano_recepcion'];
	    }
	}else{
		$lista=array();
	}
	$this->set("lista_ano", $lista);

	$num=$this->ciad01_inventario_entradas_cuerpo->generateList($this->SQLCA()." and cod_almacen_entrada=".$_SESSION['cod_almacen']." and ano_recepcion=".$ano,'numero_recepcion ASC', null, '{n}.ciad01_inventario_entradas_cuerpo.numero_recepcion', '{n}.ciad01_inventario_entradas_cuerpo.numero_recepcion');
	if($num!=null){
		$this->concatena_seis_digitos($num,'numero');
//		$this->set('numero',$num);
	}else{
		$this->set('numero',null);
	}

}



function numero($var=null){
	$this->layout = "ajax";
if($var!=''){
	$num=$this->ciad01_inventario_entradas_cuerpo->generateList($this->SQLCA()." and cod_almacen_entrada=".$_SESSION['cod_almacen']." and ano_recepcion=".$var,'numero_recepcion ASC', null, '{n}.ciad01_inventario_entradas_cuerpo.numero_recepcion', '{n}.ciad01_inventario_entradas_cuerpo.numero_recepcion');
	if($num!=null){
		$this->concatena_seis_digitos($num,'numero');
//		$this->set('numero',$num);
	}else{
		$this->set('numero',null);
	}

}else{
	$this->set('numero',null);

}

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
		//print_r($cod);
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

//pr($this->data);
 	if(!empty($this->data['ciap01']['ano'])){
 		$sql=" and ano_recepcion=".$this->data['ciap01']['ano'];
		$ano=$this->data['ciap01']['ano'];
 		if(!empty($this->data['ciap01']['numero'])){
	 		$sql.=" and numero_recepcion=".$this->data['ciap01']['numero'];
	 	}
 	}else{
 		$sql=" and ano_recepcion=".$ano;
 		if(isset($numero)){
			$sql.=" and numero_recepcion=".$numero;
 		}
 	}

 	$almacen=$_SESSION['cod_almacen'];

	if(isset($pagina)){
		$Tfilas=$this->ciad01_inventario_entradas_cuerpo->findCount($this->SQLCA()." and cod_almacen_entrada=".$almacen.$sql);
        if($Tfilas!=0){
        	$x=$this->ciad01_inventario_entradas_cuerpo->findAll($this->SQLCA()." and cod_almacen_entrada=".$almacen.$sql,null,"cod_almacen_entrada,ano_recepcion,numero_recepcion ASC",1,$pagina,null);

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
		$Tfilas=$this->ciad01_inventario_entradas_cuerpo->findCount($this->SQLCA()." and cod_almacen_entrada=".$almacen.$sql);

        if($Tfilas!=0){
        	$x=$this->ciad01_inventario_entradas_cuerpo->findAll($this->SQLCA()." and cod_almacen_entrada=".$almacen.$sql,null,"cod_almacen_entrada,ano_recepcion,numero_recepcion ASC",1,$pagina,null);
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

	$a1="select * from ciad01_inventario_entradas_cuerpo where ".$this->SQLCA()." and ano_recepcion=".$ano." and cod_almacen_entrada=".$almacen." and numero_recepcion=".$x[0]["ciad01_inventario_entradas_cuerpo"]["numero_recepcion"];

	$a2="select
		a.cod_presi,
		a.cod_entidad,
		a.cod_tipo_inst,
		a.cod_inst,
		a.cod_dep,
		a.cod_almacen_entrada,
		a.codigo_prod_serv,
		a.cantidad,a.precio_unitario,
		b.denominacion,
		b.cod_medida
		from ciad01_inventario_entradas_detalles a,v_ciad01_inventario_productos b
		where
		a.cod_presi=b.cod_presi and a.cod_entidad=b.cod_entidad and a.cod_tipo_inst=b.cod_tipo_inst and a.cod_inst=b.cod_inst and a.cod_dep=b.cod_dep and a.codigo_prod_serv=b.cod_prod_serv and
		a.cod_almacen_entrada=b.cod_almacen and a.cod_presi='$cod_presi' and a.cod_entidad='$cod_entidad' and a.cod_tipo_inst='$cod_tipo_inst' and a.cod_inst='$cod_inst' and a.cod_dep='$cod_dep' and a.ano_recepcion=".$ano." and a.cod_almacen_entrada=".$almacen." and a.numero_recepcion=".$x[0]["ciad01_inventario_entradas_cuerpo"]["numero_recepcion"];

	$datos=$this->ciad01_inventario_entradas_cuerpo->execute($a1);
	$datos2=$this->ciad01_inventario_entradas_detalles->execute($a2);
	$this->set('datos',$datos);
	$this->set('datos2',$datos2);

//	$a=$this->ciad01_inventario_almacen->execute("select denominacion from ciad01_inventario_almacen where ".$this->SQLCA()." and cod_almacen=".$almacen);
	$b=$this->ciad01_inventario_almacen->execute("select denominacion from ciad01_inventario_almacen where ".$this->SQLCA()." and cod_almacen=".$x[0]["ciad01_inventario_entradas_cuerpo"]["cod_almacen_entrada"]);
	$this->set('deno_almacen1',$b[0][0]['denominacion']);
//	$this->set('deno_almacen2',$a[0][0]['denominacion']);



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




function eliminar($ano=null,$almacen=null,$numero=null,$pagina=null){
	$this->layout="ajax";

	$a1="select * from ciad01_inventario_entradas_cuerpo where ".$this->SQLCA()." and ano_recepcion=".$ano." and cod_almacen_entrada=".$almacen." and numero_recepcion=".$numero;
	$datos=$this->ciad01_inventario_entradas_cuerpo->execute($a1);

	$a=$this->ciad01_inventario_productos->execute("BEGIN;update ciad01_inventario_entradas_cuerpo set condicion_actividad=2,fecha_anulacion='".date('Y-m-d')."',username_anulacion='".$_SESSION['nom_usuario']."' where ".$this->SQLCA()." and ano_recepcion=".$ano." and cod_almacen_entrada=".$almacen." and numero_recepcion=".$numero);
	if($datos[0][0]['numero_orden_salida']!=0){
		$b=$this->ciad01_inventario_productos->execute("update ciad01_inventario_salidas_cuerpo set ano_recepcion=0,numero_recepcion=0,entregado=2 where ".$this->condicionNDEP()." and cod_dep=".$datos[0][0]['cod_dep_salida']." and cod_almacen_salida=".$datos[0][0]['cod_almacen_salida']." and ano_orden_salida=".$datos[0][0]['ano_orden_salida']." and numero_orden_salida=".$datos[0][0]['numero_orden_salida']);
	}


	$sql=$this->ciad01_inventario_entradas_detalles->execute(" SELECT * from ciad01_inventario_entradas_detalles where ".$this->SQLCA()." and cod_almacen_entrada=".$almacen." and ano_recepcion='$ano' and numero_recepcion=".$numero);
	for($i=0;$i<count($sql);$i++){
		$ver=$this->ciad01_inventario_productos->execute(" SELECT * from ciad01_inventario_productos where ".$this->SQLCA()." and cod_almacen=".$sql[$i][0]['cod_almacen_entrada']." and cod_prod_serv=".$sql[$i][0]['codigo_prod_serv']);
		$cantidad=($ver[0][0]['numero_entradas']-$sql[$i][0]['cantidad']);

		$b=$this->ciad01_inventario_productos->execute("update ciad01_inventario_productos set numero_entradas='$cantidad' where ".$this->SQLCA()." and cod_almacen=".$sql[$i][0]['cod_almacen_entrada']." and cod_prod_serv=".$sql[$i][0]['codigo_prod_serv']);

	}

	$resultado=$this->ciad01_inventario_entradas_numero->execute("UPDATE  ciad01_inventario_entradas_numero SET situacion=4 WHERE ".$this->SQLCA()." and cod_almacen_entrada=".$almacen." and numero_recepcion=".$numero." and ano_recepcion=".$ano);

	if($a>0 && $b>0){
		$this->ciad01_inventario_entradas_detalles->execute("COMMIT");
		$this->set('Message_existe', 'REGISTRO ANULADO');
	}else{
		$this->ciad01_inventario_entradas_detalles->execute("ROLLBACK");
		$this->set('errorMessage', 'NO SE PUDO ELIMINAR ');
	}
$this->set('ano',$ano);
$this->set('pagina',$pagina);
$this->set('numero',$numero);


//$this->consultar($ano,$pagina);
//$this->render('consultar');
//$this->pre_consulta();
//$this->render('pre_consulta');


}






function salir($almacen=null,$num_rc=null,$var=null){
	$this->layout="ajax";
	$ano=$this->ano_ejecucion();
	if(isset($num_rc)){
		$resultado=$this->ciad01_inventario_entradas_numero->execute("UPDATE  ciad01_inventario_entradas_numero SET situacion=1 WHERE ".$this->SQLCA()." and cod_almacen_entrada=".$almacen." and numero_recepcion=".$num_rc." and ano_recepcion=".$ano." and situacion=2");
	}

	//$this->('index');
}



}//fin class