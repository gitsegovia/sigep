<?php
/*
 * Creado el 09/10/2008 a las 12:41:23 AM
 * Herramienta: EasyEclipse.
 * Proyecto: SIGEP
 * Descripcion:
 */

 class Ciap01ProductosMaterialesController extends AppController{
	var $uses = array('ciad01_inventario_almacen','v_cscd01_snc_grupo','ciad01_inventario_productos','cscd01_catalogo','cscd01_unidad_medida',
					  'ciad01_inventario_usuarios','v_ciad01_inventario_productos','ccfd04_cierre_mes');
 	var $helpers = array('Html','Ajax','Javascript', 'Sisap');
 	var $name = "ciap01_productos_materiales";


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

	$datos=$this->ciad01_inventario_almacen->generateList($this->SQLCA(),'cod_almacen ASC', null, '{n}.ciad01_inventario_almacen.cod_almacen', '{n}.ciad01_inventario_almacen.denominacion');
	if($datos!=null){
		$this->concatena($datos,'almacenes');
	}else{
		$this->set('almacenes',array());
	}


	$ver=$this->ciad01_inventario_usuarios->execute("select * from ciad01_inventario_usuarios where ".$this->SQLCA()." and username='".$_SESSION['nom_usuario']."'");
	if($ver!=null){
		$a=$this->ciad01_inventario_usuarios->execute("select * from ciad01_inventario_almacen where ".$this->SQLCA()." and cod_almacen=".$ver[0][0]['cod_almacen']);
		$this->set('almacen',$a[0][0]['cod_almacen']);
		$this->set('deno_almacen',$a[0][0]['denominacion']);
		$this->set('ubicacion',$a[0][0]['ubicacion']);
		$this->set('readonly','readonly');
		$this->Session->delete('cod_almacen');
		$this->Session->write('cod_almacen',$a[0][0]['cod_almacen']);
	}else{
		$this->set('almacen','');
		$this->set('deno_almacen','');
		$this->set('ubicacion','');
		$this->set('readonly','');
	}


}//fin index



function denominacion($var=null,$var2=null){
	$this->layout = "ajax";
	if($var2!=''){
		if($var==1){
				$datos  = $this->v_cscd01_snc_grupo->execute(" SELECT denominacion FROM v_cscd01_snc_grupo where  cod_grupo='$var2'  ORDER BY cod_grupo ASC");
		}else if($var==2){
				$datos  = $this->v_cscd01_snc_grupo->execute(" SELECT denominacion FROM v_cscd01_snc_grupo where  cod_grupo_5='$var2'  ORDER BY cod_grupo_5 ASC");
		}else if($var==3){
				$datos  = $this->v_cscd01_snc_grupo->execute(" SELECT denominacion_snc FROM v_cscd01_catalogo_con_snc_denominacion where  cod_snc='$var2'  ORDER BY cod_snc ASC");
		}else if($var==4){
				$datos  = $this->v_cscd01_snc_grupo->execute(" SELECT denominacion FROM v_cscd01_catalogo_deno_und where  codigo_prod_serv='$var2'  ORDER BY codigo_prod_serv ASC");
		}else if($var==5){
				$this->Session->delete('cod_almacen');
				$this->Session->write('cod_almacen',$var2);
				$datos  = $this->ciad01_inventario_almacen->execute(" SELECT denominacion FROM ciad01_inventario_almacen where  ".$this->SQLCA()." and cod_almacen='$var2'  ORDER BY cod_almacen ASC");
		}

		$this->set('datos',$datos);
		$this->set('var',$var);

	}else{
		$this->set('datos',null);

	}

}


function descripcion($var=null,$var2=null){
	$this->layout = "ajax";
	if($var2!=''){
		if($var==1){
				$datos  = $this->v_cscd01_snc_grupo->execute(" SELECT descripcion FROM v_cscd01_snc_grupo where  cod_grupo='$var2'  ORDER BY cod_grupo ASC");
		}else if($var==2){
				$datos  = $this->v_cscd01_snc_grupo->execute(" SELECT descripcion FROM v_cscd01_snc_grupo where  cod_grupo_5='$var2'  ORDER BY cod_grupo_5 ASC");
		}else if($var==3){
				$datos  = $this->ciad01_inventario_almacen->execute(" SELECT ubicacion FROM ciad01_inventario_almacen where  ".$this->SQLCA()." and cod_almacen='$var2'  ORDER BY cod_almacen ASC");
		}

		$this->set('datos',$datos);
		$this->set('var',$var);

	}else{
		$this->set('datos',null);

	}

}



function guardar(){
	$this->layout = "ajax";
	$cod_presi 					= $this->Session->read('SScodpresi');
	$cod_entidad 				= $this->Session->read('SScodentidad');
	$cod_tipo_inst 				= $this->Session->read('SScodtipoinst');
	$cod_inst 					= $this->Session->read('SScodinst');
	$cod_dep 					= $this->Session->read('SScoddep');
//pr($this->data);

	if(empty($this->data['ciap01']['cod_almacen'])){
		$this->set('errorMessage', 'debe seleccionar el código del almacén');

	}else if(empty($this->data['ciap01']['cod_producto'])){
		$this->set('errorMessage', 'debe ingresar el producto');

	}else if($this->data['ciap01']['estante']==''){
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
		$cod_almacen=$this->data['ciap01']['cod_almacen'];
		$cod_producto=$this->data['ciap01']['cod_producto'];
		$minimo= str_replace(',', '.', $this->data['ciap01']['minimo']);
		$maximo= str_replace(',', '.', $this->data['ciap01']['maximo']);
		$punto_pedido= str_replace(',', '.', $this->data['ciap01']['punto_pedido']);
		$entradas= str_replace(',', '.', $this->data['ciap01']['entradas']);
		$fecha_registro=date('Y-m-d');
		$usuario=$_SESSION['nom_usuario'];
		$cod_snc=$this->data['ciap01']['cod_snc'];

		if($this->data['ciap01']['estante']==0){
			$estante=0;
			$fila=0;
			$entrepano=0;
		}else{
			$estante=$this->data['ciap01']['estante'];
			$fila=$this->data['ciap01']['fila'];
			$entrepano=$this->data['ciap01']['entrepano'];

		}

		if(empty($this->data['ciap01']['entradas'])){
			$entradas=0;
		}else{
			$entradas=$this->data['ciap01']['entradas'];
		}

		if(empty($this->data['ciap01']['complemento_ubicacion'])){
			$campos="(cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_almacen,cod_prod_serv, stock_maximo, stock_minimo, punto_pedido, estante_numero,fila_numero, columna_numero, numero_entradas, numero_salidas, costo_maximo, costo_minimo, fecha_registro, username_registro,cod_snc)";
			$insert="('$cod_presi', '$cod_entidad' ,'$cod_tipo_inst' ,'$cod_inst' ,'$cod_dep' ,'$cod_almacen','$cod_producto','$maximo','$minimo','$punto_pedido','$estante','$fila','$entrepano','$entradas','0','0','0','$fecha_registro','$usuario','$cod_snc')";
		}else{
			$complemento_ubicacion=$this->data['ciap01']['complemento_ubicacion'];
			$campos="(cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_almacen,cod_prod_serv, stock_maximo, stock_minimo, punto_pedido, estante_numero,fila_numero, columna_numero, complemento_sitio_almacenaje, numero_entradas, numero_salidas, costo_maximo, costo_minimo, fecha_registro, username_registro,cod_snc)";
			$insert="('$cod_presi', '$cod_entidad' ,'$cod_tipo_inst' ,'$cod_inst' ,'$cod_dep' ,'$cod_almacen','$cod_producto','$maximo','$minimo','$punto_pedido','$estante','$fila','$entrepano','$complemento_ubicacion','$entradas','0','0','0','$fecha_registro','$usuario','$cod_snc')";
		}


		$SQL_INSERT ="INSERT INTO ciad01_inventario_productos ".$campos." VALUES ".$insert;
		$sw = $this->ciad01_inventario_productos->execute($SQL_INSERT);
		if($sw>1){
			$this->set('Message_existe', 'LOS DATOS FUERON GUARDADOS');
			$this->set('guardado', 'si');
		}else{
			$this->set('errorMessage', 'LOS DATOS NO FUERON GUARDADOS');
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
		a.denominacion as denominacion_producto,
		a.cod_snc,
		a.denominacion_snc,
		(substr(a.cod_snc::text,1,3)) as cod_grupo,
		(select b.denominacion from v_cscd01_snc_grupo b where b.cod_grupo=(substr(a.cod_snc::text,1,3))) as denominacion_grupo,
		(select b.descripcion from v_cscd01_snc_grupo b where b.cod_grupo=(substr(a.cod_snc::text,1,3))) as descripcion_grupo,
		(substr(a.cod_snc::text,1,5)) as cod_subgrupo,
		(select b.denominacion from v_cscd01_snc_grupo b where b.cod_grupo=(substr(a.cod_snc::text,1,5))) as denominacion_subgrupo,
		(select b.descripcion from v_cscd01_snc_grupo b where b.cod_grupo=(substr(a.cod_snc::text,1,5))) as descripcion_subgrupo

		FROM v_cscd01_catalogo_con_snc_denominacion a WHERE a.codigo_prod_serv='".$var2."'
		";

$datos=$this->cscd01_catalogo->execute($sql);
$this->set('datos',$datos);

$datos2=$this->cscd01_catalogo->execute("select * from ciad01_inventario_productos where ".$this->SQLCA()." and cod_almacen=".$_SESSION['cod_almacen']." and cod_prod_serv=".$var2);
if($datos2!=null){
	$this->set('errorMessage', 'este producto ya existe registrado en el almacén');
	$this->set('datos2',$datos2);
}else{
	$this->set('datos2',null);
}




}//fin function




function consultar($almacen=null,$pagina=null) {
	$this->layout="ajax";

if(!isset($almacen)){
	$almacen=$this->data['ciap01']['cod_almacen'];
}

	if(isset($pagina)){
		$Tfilas=$this->ciad01_inventario_productos->findCount($this->SQLCA()." and cod_almacen=".$almacen);
        if($Tfilas!=0){
        	$x=$this->ciad01_inventario_productos->findAll($this->SQLCA()." and cod_almacen=".$almacen,null,"cod_almacen,cod_prod_serv ASC",1,$pagina,null);

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
		$Tfilas=$this->ciad01_inventario_productos->findCount($this->SQLCA()." and cod_almacen=".$almacen);

        if($Tfilas!=0){
        	$x=$this->ciad01_inventario_productos->findAll($this->SQLCA()." and cod_almacen=".$almacen,null,"cod_almacen,cod_prod_serv ASC",1,$pagina,null);
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
			  WHERE ".$this->SQLCA()." and c.cod_prod_serv = a.codigo_prod_serv and c.cod_prod_serv=".$x[0]["ciad01_inventario_productos"]["cod_prod_serv"]." and c.cod_almacen=".$x[0]["ciad01_inventario_productos"]["cod_almacen"];

	$datos=$this->v_ciad01_inventario_productos->execute($sql);
	$this->set('datos2',$datos);

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






function modificar($cod_almacen=null,$producto=null,$pagina=null){
	$this->layout = "ajax";

	$this->set('pagina',$pagina);

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
			  WHERE ".$this->SQLCA()." and c.cod_prod_serv = a.codigo_prod_serv and c.cod_prod_serv=".$producto." and c.cod_almacen=".$cod_almacen;

	$datos=$this->ciad01_inventario_productos->execute($sql);
	$this->set('datos2',$datos);

}



function guardar_modificar($cod_almacen=null,$producto=null,$pagina=null){
	$this->layout = "ajax";

	$this->set('cod_almacen',$cod_almacen);
	$this->set('producto',$producto);
	$this->set('pagina',$pagina);

	if(isset($pagina)){
		$this->set('consultar','');
	}else{
		$this->set('busqueda','');
	}

	$cod_presi 					= $this->Session->read('SScodpresi');
	$cod_entidad 				= $this->Session->read('SScodentidad');
	$cod_tipo_inst 				= $this->Session->read('SScodtipoinst');
	$cod_inst 					= $this->Session->read('SScodinst');
	$cod_dep 					= $this->Session->read('SScoddep');

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
	}else if(str_replace(',', '.', $this->data['ciap01']['minimo'])>=str_replace(',', '.', $this->data['ciap01']['maximo'])){
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

		$minimo= str_replace(',', '.', $this->data['ciap01']['minimo']);
		$maximo= str_replace(',', '.', $this->data['ciap01']['maximo']);
		$punto_pedido= str_replace(',', '.', $this->data['ciap01']['punto_pedido']);
		$entradas= str_replace(',', '.', $this->data['ciap01']['entradas']);
		$fecha_registro=date('Y-m-d');
		$usuario=$_SESSION['nom_usuario'];
		$cod_snc=$this->data['ciap01']['cod_snc'];

		if($this->data['ciap01']['estante']==0){
			$estante=0;
			$fila=0;
			$entrepano=0;
		}else{
			$estante=$this->data['ciap01']['estante'];
			$fila=$this->data['ciap01']['fila'];
			$entrepano=$this->data['ciap01']['entrepano'];

		}

		if(empty($this->data['ciap01']['entradas'])){
			$entradas=0;
		}else{
			$entradas=$this->data['ciap01']['entradas'];
		}

		if(empty($this->data['ciap01']['complemento_ubicacion'])){
			$SQL_INSERT="UPDATE ciad01_inventario_productos SET stock_maximo='$maximo', stock_minimo='$minimo', punto_pedido='$punto_pedido', estante_numero='$estante',fila_numero='$fila', columna_numero='$entrepano',complemento_sitio_almacenaje=null where ".$this->SQLCA()." and cod_almacen='$cod_almacen' and cod_prod_serv=".$producto;
		}else{
			$complemento_ubicacion=$this->data['ciap01']['complemento_ubicacion'];
			$SQL_INSERT="UPDATE ciad01_inventario_productos SET stock_maximo='$maximo', stock_minimo='$minimo', punto_pedido='$punto_pedido', estante_numero='$estante',fila_numero='$fila', columna_numero='$entrepano',complemento_sitio_almacenaje='$complemento_ubicacion' where ".$this->SQLCA()." and cod_almacen='$cod_almacen' and cod_prod_serv=".$producto;
		}

		$sw = $this->ciad01_inventario_productos->execute($SQL_INSERT);
		if($sw>1){
			$this->set('Message_existe', 'LOS DATOS FUERON MODIFICADOS');
			$this->set('guardado', 'si');
		}else{
			$this->set('errorMessage', 'LOS DATOS NO FUERON MODIFICADOS');
			$this->set('guardado', 'no');
		}

	}


}





function buscar($var1=null){
	$this->layout="ajax";
	$this->set("opcion",$var1);
	$this->Session->delete('pista');
}//fin function



function buscar_pista($var1=null, $var2=null, $var3=null){
$this->layout="ajax";

    if($var3==null){$var2 = strtoupper($var2);
					$this->Session->write('pista', $var2);
					if(is_numeric($var2)){  $sql   = " (codigo_prod_serv::text LIKE '%$var2%')  or   ";}else{ $sql = ""; }
					$Tfilas=$this->v_ciad01_inventario_productos->findCount($this->SQLCA()." and cod_almacen=".$_SESSION['cod_almacen']." and ".$sql." (mayus_acentos(denominacion)  LIKE mayus_acentos('%$var2%') )  OR  (cod_snc LIKE '%$var2%')   ");
					        if($Tfilas!=0){
					        	$pagina=1;
					        	$Tfilas=(int)ceil($Tfilas/100);
					        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
								$this->set('total_paginas',$Tfilas);
								$this->set('pagina_actual',$pagina);
								$this->set('ultimo',$Tfilas);
					     	    $datos_filas=$this->v_ciad01_inventario_productos->findAll($this->SQLCA()." and cod_almacen=".$_SESSION['cod_almacen']." and ".$sql." (mayus_acentos(denominacion)  LIKE mayus_acentos('%$var2%') )  OR  (cod_snc LIKE '%$var2%')   ",null,"codigo_prod_serv ASC",100,1,null);
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
						$Tfilas=$this->v_ciad01_inventario_productos->findCount($this->SQLCA()." and cod_almacen=".$_SESSION['cod_almacen']." and ".$sql." (mayus_acentos(denominacion)  LIKE mayus_acentos('%$var22%') )  OR  (cod_snc LIKE '%$var22%')   ");
						        if($Tfilas!=0){
						        	$pagina=$var3;
						        	$Tfilas=(int)ceil($Tfilas/100);
						        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
									$this->set('total_paginas',$Tfilas);
									$this->set('pagina_actual',$pagina);
									$this->set('ultimo',$Tfilas);
						     	    $datos_filas=$this->v_ciad01_inventario_productos->findAll($this->SQLCA()." and cod_almacen=".$_SESSION['cod_almacen']." and ".$sql." (mayus_acentos(denominacion)  LIKE mayus_acentos('%$var22%') )  OR  (cod_snc LIKE '%$var22%')   ",null,"codigo_prod_serv ASC",100,$pagina,null);
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





function seleccion($var1=null, $var2=null, $var3=null){

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
			  WHERE ".$this->SQLCA()." and  c.cod_prod_serv = a.codigo_prod_serv and c.cod_prod_serv=".$var2." and c.cod_almacen=".$var3;

	$datos=$this->ciad01_inventario_productos->execute($sql);
	$this->set('datos2',$datos);



}//fin function



function reporte_inventario($var=null){
	$cod_presi 					= $this->Session->read('SScodpresi');
	$cod_entidad 				= $this->Session->read('SScodentidad');
	$cod_tipo_inst 				= $this->Session->read('SScodtipoinst');
	$cod_inst 					= $this->Session->read('SScodinst');
	$cod_dep 					= $this->Session->read('SScoddep');
	if($var==1){
		$this->layout="ajax";

			if($cod_dep!=1){
					$datos=$this->ciad01_inventario_almacen->generateList($this->SQLCA(),'cod_almacen ASC', null, '{n}.ciad01_inventario_almacen.cod_almacen', '{n}.ciad01_inventario_almacen.denominacion');
					if($datos!=null){
						$this->concatena($datos,'almacenes');
					}else{
						$this->set('almacenes',array());
					}

					$ver=$this->ciad01_inventario_usuarios->execute("select * from ciad01_inventario_usuarios where ".$this->SQLCA()." and username='".$_SESSION['nom_usuario']."'");
					if($ver!=null){
						$a=$this->ciad01_inventario_usuarios->execute("select * from ciad01_inventario_almacen where ".$this->SQLCA()." and cod_almacen=".$ver[0][0]['cod_almacen']);
						$this->set('almacen',$a[0][0]['cod_almacen']);
						$this->set('readonly','readonly');
					}else{
						$this->set('almacen','');
						$this->set('readonly','');
					}

			}

	}else if($var==2){
		$this->layout="pdf";
		$_SESSION['top_ano']=$this->ano_ejecucion();

		if(isset($this->data['inventario']['tipo_busqueda'])){
			if(!empty($this->data['inventario']['tipo_busqueda'])){
				$tipo=$this->data['inventario']['tipo_busqueda'];
				if($tipo==1){
						$filtro=$this->condicionNDEP();
				}else if($tipo==2){
						if(!empty($this->data['inventario']['dependencia'])){
							$dep=$this->data['inventario']['dependencia'];
							$filtro=$this->condicionNDEP()." and cod_dep=".$dep;
						}else{
							$filtro=$this->SQLCA();
						}
				}else{
						if(!empty($this->data['inventario']['cod_almacen'])){
							$almacen=$this->data['inventario']['cod_almacen'];
							$filtro=$this->SQLCA()." and cod_almacen=".$almacen;
						}else{
							$filtro=$this->SQLCA();
						}
				}
			}else{

			}
		}else{
			if(!empty($this->data['inventario']['cod_almacen'])){
				$almacen=$this->data['inventario']['cod_almacen'];
				$filtro=$this->SQLCA()." and cod_almacen=".$almacen;
			}else{
				$filtro=$this->SQLCA();
			}

		}
		////////////////////////////////////////////////////////////////

		$orden1=$this->data['inventario']['tipo_orden'];
		if($orden1==1){
			$orden=" cod_dep,cod_almacen,denominacion";
		}else if($orden1==2){
			$orden=" cod_dep,cod_almacen,cod_prod_serv";
		}else{
			$orden=" cod_dep,cod_almacen,cod_snc,cod_prod_serv";
		}

		$sql="select * from v_ciad01_inventario_productos where ".$filtro." order by ".$orden;
		$datos=$this->ciad01_inventario_almacen->execute($sql);
		if($datos!=null){
			$this->set('datos',$datos);
		}else{
			$this->set('datos',null);
		}


	}
	$this->set('opcion',$var);

}//fin reporte_inventario


function reporte_radio($var=null){
	$this->layout="ajax";
	$cod_presi 					= $this->Session->read('SScodpresi');
	$cod_entidad 				= $this->Session->read('SScodentidad');
	$cod_tipo_inst 				= $this->Session->read('SScodtipoinst');
	$cod_inst 					= $this->Session->read('SScodinst');
	$cod_dep 					= $this->Session->read('SScoddep');
	if($var==2){
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
				$this->set('lista_dependencia',$lista);
				$this->set('cod_dependencia',$cod_dep);
	}else if($var==3){
				$datos=$this->ciad01_inventario_almacen->generateList($this->SQLCA(),'cod_almacen ASC', null, '{n}.ciad01_inventario_almacen.cod_almacen', '{n}.ciad01_inventario_almacen.denominacion');
				if($datos!=null){
					$this->concatena($datos,'almacenes');
				}else{
					$this->set('almacenes',array());
				}

				$ver=$this->ciad01_inventario_usuarios->execute("select * from ciad01_inventario_usuarios where ".$this->SQLCA()." and username='".$_SESSION['nom_usuario']."'");
				if($ver!=null){
					$a=$this->ciad01_inventario_usuarios->execute("select * from ciad01_inventario_almacen where ".$this->SQLCA()." and cod_almacen=".$ver[0][0]['cod_almacen']);
					$this->set('almacen',$a[0][0]['cod_almacen']);
					$this->set('readonly','readonly');
				}else{
					$this->set('almacen','');
					$this->set('readonly','');
				}

	}
	$this->set('opcion',$var);

}// fin reporte_radio




function reporte_entradas_inventario ($var=null) {
	$cod_presi 					= $this->Session->read('SScodpresi');
	$cod_entidad 				= $this->Session->read('SScodentidad');
	$cod_tipo_inst 				= $this->Session->read('SScodtipoinst');
	$cod_inst 					= $this->Session->read('SScodinst');
	$cod_dep 					= $this->Session->read('SScoddep');
	if($var=="no"){
		$this->layout="ajax";

		$datos=$this->ciad01_inventario_almacen->generateList($this->SQLCA(),'cod_almacen ASC', null, '{n}.ciad01_inventario_almacen.cod_almacen', '{n}.ciad01_inventario_almacen.denominacion');
		if($datos!=null){
			$this->concatena($datos,'almacenes');
		}else{
			$this->set('almacenes',array());
		}

		$ver=$this->ciad01_inventario_usuarios->execute("select * from ciad01_inventario_usuarios where ".$this->SQLCA()." and username='".$_SESSION['nom_usuario']."'");
		if($ver!=null){
			$a=$this->ciad01_inventario_usuarios->execute("select * from ciad01_inventario_almacen where ".$this->SQLCA()." and cod_almacen=".$ver[0][0]['cod_almacen']);
			$this->set('almacen',$a[0][0]['cod_almacen']);
			$this->set('deno_almacen',$a[0][0]['denominacion']);
			$this->set('ubicacion',$a[0][0]['ubicacion']);
			$this->set('readonly','readonly');
			$this->Session->delete('cod_almacen');
			$this->Session->write('cod_almacen',$a[0][0]['cod_almacen']);
		}else{
			$this->set('almacen','');
			$this->set('deno_almacen','');
			$this->set('ubicacion','');
			$this->set('readonly','');
		}

	}else if($var=="si"){
		$this->layout="pdf";
		$_SESSION['top_ano']=$this->ano_ejecucion();

		$cod_almacen = $this->data['reporte']['cod_almacen'];
		$fecha_desde = $this->data['reporte']['fecha_desde'];
		$fecha_hasta = $this->data['reporte']['fecha_hasta'];

		$a=$this->ciad01_inventario_usuarios->execute("select * from ciad01_inventario_almacen where ".$this->SQLCA()." and cod_almacen=".$cod_almacen);
		$this->set('almacen','Almacén: '.$a[0][0]['denominacion']);
		$this->set('fechas', 'Desde: '.$fecha_desde.' Hasta: '.$fecha_hasta);

		$sql = "SELECT
					a.cod_presi,
					a.cod_entidad,
					a.cod_tipo_inst,
					a.cod_inst,
					a.cod_dep,
					( SELECT c.denominacion FROM arrd05 c WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep) AS deno_dependencia,
					a.cod_almacen_entrada,
					( SELECT al.denominacion FROM ciad01_inventario_almacen al WHERE al.cod_presi = a.cod_presi AND al.cod_entidad = a.cod_entidad AND al.cod_tipo_inst = a.cod_tipo_inst AND al.cod_inst = a.cod_inst AND al.cod_dep = a.cod_dep AND al.cod_almacen = a.cod_almacen_entrada) AS deno_almacen_entrada,
					a.ano_recepcion,
					a.numero_recepcion,
					a.fecha_recepcion,
					a.cod_dep_origen,
					( SELECT c.denominacion FROM arrd05 c WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep_origen = c.cod_dep) AS deno_dep_origen,
					a.ano_nota_entrega,
					a.numero_nota_entrega,
					a.ano_orden_compra,
					a.numero_orden_compra,
					a.cod_dep_salida,
					( SELECT c.denominacion FROM arrd05 c WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep_salida = c.cod_dep) AS deno_dependencia_salida,
					a.cod_almacen_salida,
					( SELECT al.denominacion FROM ciad01_inventario_almacen al WHERE al.cod_presi = a.cod_presi AND al.cod_entidad = a.cod_entidad AND al.cod_tipo_inst = a.cod_tipo_inst AND al.cod_inst = a.cod_inst AND al.cod_dep = a.cod_dep AND al.cod_almacen = a.cod_almacen_salida) AS deno_almacen_salida,
					a.ano_orden_salida,
					a.numero_orden_salida,

					b.codigo_prod_serv,
					( SELECT c.denominacion FROM cscd01_catalogo c WHERE c.codigo_prod_serv = b.codigo_prod_serv) AS denominacion_producto,
					b.cantidad,
					b.precio_unitario

				FROM
					ciad01_inventario_entradas_cuerpo a,
					ciad01_inventario_entradas_detalles b
				WHERE
					b.cod_presi = '$cod_presi' AND
					b.cod_entidad = '$cod_entidad' AND
					b.cod_tipo_inst = '$cod_tipo_inst' AND
					b.cod_inst = '$cod_inst' AND
					b.cod_dep = '$cod_dep' AND
					b.cod_almacen_entrada = '$cod_almacen' AND
					a.cod_presi = b.cod_presi AND
					a.cod_entidad = b.cod_entidad AND
					a.cod_tipo_inst = b.cod_tipo_inst AND
					a.cod_inst = b.cod_inst AND
					a.cod_dep = b.cod_dep AND
					a.cod_almacen_entrada = b.cod_almacen_entrada AND
					a.ano_recepcion = b.ano_recepcion AND
					a.numero_recepcion = b.numero_recepcion AND
					a.fecha_recepcion between '$fecha_desde' AND '$fecha_hasta'
				ORDER BY
					a.cod_almacen_entrada,
					a.ano_recepcion,
					a.numero_recepcion,
					a.fecha_recepcion;";

			$datos  = $this->ciad01_inventario_almacen->execute($sql);
			$this->set('datos', $datos);

	}
	$this->set('opcion',$var);

}//fin reporte_entradas_inventario


function reporte_salidas_inventario ($var=null) {
	$cod_presi 					= $this->Session->read('SScodpresi');
	$cod_entidad 				= $this->Session->read('SScodentidad');
	$cod_tipo_inst 				= $this->Session->read('SScodtipoinst');
	$cod_inst 					= $this->Session->read('SScodinst');
	$cod_dep 					= $this->Session->read('SScoddep');
	if($var=="no"){
		$this->layout="ajax";

		$datos=$this->ciad01_inventario_almacen->generateList($this->SQLCA(),'cod_almacen ASC', null, '{n}.ciad01_inventario_almacen.cod_almacen', '{n}.ciad01_inventario_almacen.denominacion');
		if($datos!=null){
			$this->concatena($datos,'almacenes');
		}else{
			$this->set('almacenes',array());
		}

		$ver=$this->ciad01_inventario_usuarios->execute("select * from ciad01_inventario_usuarios where ".$this->SQLCA()." and username='".$_SESSION['nom_usuario']."'");
		if($ver!=null){
			$a=$this->ciad01_inventario_usuarios->execute("select * from ciad01_inventario_almacen where ".$this->SQLCA()." and cod_almacen=".$ver[0][0]['cod_almacen']);
			$this->set('almacen',$a[0][0]['cod_almacen']);
			$this->set('deno_almacen',$a[0][0]['denominacion']);
			$this->set('ubicacion',$a[0][0]['ubicacion']);
			$this->set('readonly','readonly');
			$this->Session->delete('cod_almacen');
			$this->Session->write('cod_almacen',$a[0][0]['cod_almacen']);
		}else{
			$this->set('almacen','');
			$this->set('deno_almacen','');
			$this->set('ubicacion','');
			$this->set('readonly','');
		}

	}else if($var=="si"){
		$this->layout="pdf";
		$_SESSION['top_ano']=$this->ano_ejecucion();

		$cod_almacen = $this->data['reporte']['cod_almacen'];
		$fecha_desde = $this->data['reporte']['fecha_desde'];
		$fecha_hasta = $this->data['reporte']['fecha_hasta'];

		$a=$this->ciad01_inventario_usuarios->execute("select * from ciad01_inventario_almacen where ".$this->SQLCA()." and cod_almacen=".$cod_almacen);
		$this->set('almacen','Almacén: '.$a[0][0]['denominacion']);
		$this->set('fechas', 'Desde: '.$fecha_desde.' Hasta: '.$fecha_hasta);

		$sql = "SELECT
				a.cod_presi,
				a.cod_entidad,
				a.cod_tipo_inst,
				a.cod_inst,
				a.cod_dep,
				( SELECT c.denominacion FROM arrd05 c WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep = c.cod_dep) AS deno_dependencia,
				a.cod_almacen_salida,
				( SELECT al.denominacion FROM ciad01_inventario_almacen al WHERE al.cod_presi = a.cod_presi AND al.cod_entidad = a.cod_entidad AND al.cod_tipo_inst = a.cod_tipo_inst AND al.cod_inst = a.cod_inst AND al.cod_dep = a.cod_dep AND al.cod_almacen = a.cod_almacen_salida) AS deno_almacen_salida,
				a.ano_orden_salida,
				a.numero_orden_salida,
				a.fecha_orden_salida,
				a.cod_dep_receptora,
				( SELECT c.denominacion FROM arrd05 c WHERE a.cod_presi = c.cod_presi AND a.cod_entidad = c.cod_entidad AND a.cod_tipo_inst = c.cod_tipo_inst AND a.cod_inst = c.cod_inst AND a.cod_dep_receptora = c.cod_dep) AS deno_dep_receptora,
				a.cod_almacen_receptor,
				( SELECT al.denominacion FROM ciad01_inventario_almacen al WHERE al.cod_presi = a.cod_presi AND al.cod_entidad = a.cod_entidad AND al.cod_tipo_inst = a.cod_tipo_inst AND al.cod_inst = a.cod_inst AND al.cod_dep = a.cod_dep AND al.cod_almacen = a.cod_almacen_receptor) AS deno_almacen_receptor,
				a.ano_recepcion,
				a.numero_recepcion,
				a.entregado,
				a.recibido_por,
				b.codigo_prod_serv,
				( SELECT c.denominacion FROM cscd01_catalogo c WHERE c.codigo_prod_serv = b.codigo_prod_serv) AS denominacion_producto,
				b.cantidad
			FROM
				ciad01_inventario_salidas_cuerpo a,
				ciad01_inventario_salidas_detalles b
			WHERE
				b.cod_presi = '$cod_presi' AND
				b.cod_entidad = '$cod_entidad' AND
				b.cod_tipo_inst = '$cod_tipo_inst' AND
				b.cod_inst = '$cod_inst' AND
				b.cod_dep = '$cod_dep' AND
				b.cod_almacen_salida = '$cod_almacen' AND
				a.cod_presi = b.cod_presi AND
				a.cod_entidad = b.cod_entidad AND
				a.cod_tipo_inst = b.cod_tipo_inst AND
				a.cod_inst = b.cod_inst AND
				a.cod_dep = b.cod_dep AND
				a.cod_almacen_salida = b.cod_almacen_salida AND
				a.ano_orden_salida = b.ano_orden_salida AND
				a.numero_orden_salida = b.numero_orden_salida AND
				a.fecha_orden_salida between '$fecha_desde' AND '$fecha_hasta'
			ORDER BY
				a.cod_almacen_salida,
				a.numero_orden_salida,
				a.fecha_orden_salida;";

			$datos  = $this->ciad01_inventario_almacen->execute($sql);
			//pr($datos);
			$this->set('datos', $datos);

	}
	$this->set('opcion',$var);

}//fin reporte_salidas_inventario



function reporte_entradas_salidas_inventario ($var=null) {
	$cod_presi 					= $this->Session->read('SScodpresi');
	$cod_entidad 				= $this->Session->read('SScodentidad');
	$cod_tipo_inst 				= $this->Session->read('SScodtipoinst');
	$cod_inst 					= $this->Session->read('SScodinst');
	$cod_dep 					= $this->Session->read('SScoddep');
	if($var=="no"){
		$this->layout="ajax";

		$datos=$this->ciad01_inventario_almacen->generateList($this->SQLCA(),'cod_almacen ASC', null, '{n}.ciad01_inventario_almacen.cod_almacen', '{n}.ciad01_inventario_almacen.denominacion');
		if($datos!=null){
			$this->concatena($datos,'almacenes');
		}else{
			$this->set('almacenes',array());
		}

		$ver=$this->ciad01_inventario_usuarios->execute("select * from ciad01_inventario_usuarios where ".$this->SQLCA()." and username='".$_SESSION['nom_usuario']."'");
		if($ver!=null){
			$a=$this->ciad01_inventario_usuarios->execute("select * from ciad01_inventario_almacen where ".$this->SQLCA()." and cod_almacen=".$ver[0][0]['cod_almacen']);
			$this->set('almacen',$a[0][0]['cod_almacen']);
			$this->set('deno_almacen',$a[0][0]['denominacion']);
			$this->set('ubicacion',$a[0][0]['ubicacion']);
			$this->set('readonly','readonly');
			$this->Session->delete('cod_almacen');
			$this->Session->write('cod_almacen',$a[0][0]['cod_almacen']);
		}else{
			$this->set('almacen','');
			$this->set('deno_almacen','');
			$this->set('ubicacion','');
			$this->set('readonly','');
		}

	}else if($var=="si"){
		$this->layout="pdf";
		$_SESSION['top_ano']=$this->ano_ejecucion();

		$cod_almacen 	 = $this->data['reporte']['cod_almacen'];
		$fecha_desde 	 = $this->data['reporte']['fecha_desde'];
		$fecha_hasta 	 = $this->data['reporte']['fecha_hasta'];

		$opcion_productos = $this->data['reporte']['opcion_productos'];
		if($opcion_productos == 2){
			$codigo_producto = $this->data['reporte']['codigo_producto'];
			if($codigo_producto != ''){
				$sql_prod = " a.codigo_prod_serv = '$codigo_producto' AND ";
			}else{
				$sql_prod = " ";
			}
		}else{
			$sql_prod = " ";
		}

		$a=$this->ciad01_inventario_usuarios->execute("select * from ciad01_inventario_almacen where ".$this->SQLCA()." and cod_almacen=".$cod_almacen);
		$this->set('almacen','Almacén: '.$a[0][0]['denominacion']);
		$this->set('fechas', 'Desde: '.$fecha_desde.' Hasta: '.$fecha_hasta);

		$sql = "SELECT
					a.*,
					(SELECT e.numero_entradas FROM ciad01_inventario_productos e WHERE e.cod_presi = a.cod_presi AND e.cod_entidad = a.cod_entidad AND e.cod_tipo_inst = a.cod_tipo_inst AND e.cod_inst = a.cod_inst AND e.cod_dep = a.cod_dep AND e.cod_almacen = a.cod_almacen AND e.cod_prod_serv = a.codigo_prod_serv) as numero_entradas,
					(SELECT e.numero_salidas FROM ciad01_inventario_productos e WHERE e.cod_presi = a.cod_presi AND e.cod_entidad = a.cod_entidad AND e.cod_tipo_inst = a.cod_tipo_inst AND e.cod_inst = a.cod_inst AND e.cod_dep = a.cod_dep AND e.cod_almacen = a.cod_almacen AND e.cod_prod_serv = a.codigo_prod_serv) as numero_salidas,
					(SELECT SUM(e.cantidad) FROM v_ciad01_entradas_salidas_inventario e WHERE e.cod_presi = a.cod_presi AND e.cod_entidad = a.cod_entidad AND e.cod_tipo_inst = a.cod_tipo_inst AND e.cod_inst = a.cod_inst AND e.cod_dep = a.cod_dep AND e.cod_almacen = a.cod_almacen AND e.codigo_prod_serv = a.codigo_prod_serv AND e.tipo_movimiento=1 AND e.condicion_actividad=1 AND e.fecha < '$fecha_desde') AS entradas_anteriores,
					(SELECT SUM(e.cantidad) FROM v_ciad01_entradas_salidas_inventario e WHERE e.cod_presi = a.cod_presi AND e.cod_entidad = a.cod_entidad AND e.cod_tipo_inst = a.cod_tipo_inst AND e.cod_inst = a.cod_inst AND e.cod_dep = a.cod_dep AND e.cod_almacen = a.cod_almacen AND e.codigo_prod_serv = a.codigo_prod_serv AND e.tipo_movimiento=1 AND e.condicion_actividad=1 AND (e.fecha BETWEEN '$fecha_desde' AND '$fecha_hasta')) AS entradas_actuales,
					(SELECT SUM(e.cantidad) FROM v_ciad01_entradas_salidas_inventario e WHERE e.cod_presi = a.cod_presi AND e.cod_entidad = a.cod_entidad AND e.cod_tipo_inst = a.cod_tipo_inst AND e.cod_inst = a.cod_inst AND e.cod_dep = a.cod_dep AND e.cod_almacen = a.cod_almacen AND e.codigo_prod_serv = a.codigo_prod_serv AND e.tipo_movimiento=1 AND e.condicion_actividad=1 AND e.fecha > '$fecha_hasta') AS entradas_posteriores,
					(SELECT SUM(s.cantidad) FROM v_ciad01_entradas_salidas_inventario s WHERE s.cod_presi = a.cod_presi AND s.cod_entidad = a.cod_entidad AND s.cod_tipo_inst = a.cod_tipo_inst AND s.cod_inst = a.cod_inst AND s.cod_dep = a.cod_dep AND s.cod_almacen = a.cod_almacen AND s.codigo_prod_serv = a.codigo_prod_serv AND s.tipo_movimiento=2 AND s.condicion_actividad=1 AND s.fecha < '$fecha_desde') AS salidas_anteriores
				FROM v_ciad01_entradas_salidas_inventario a
				WHERE
				a.cod_presi = '$cod_presi' AND
				a.cod_entidad = '$cod_entidad' AND
				a.cod_tipo_inst = '$cod_tipo_inst' AND
				a.cod_inst = '$cod_inst' AND
				a.cod_dep = '$cod_dep' AND
				a.cod_almacen = '$cod_almacen' AND
				".$sql_prod."
				a.fecha BETWEEN '$fecha_desde' AND '$fecha_hasta'
				ORDER BY a.cod_almacen, a.denominacion_producto, a.fecha, a.tipo_movimiento, a.numero ASC;";

			$datos  = $this->ciad01_inventario_almacen->execute($sql);
			//pr($datos);
			$this->set('datos', $datos);

	}
	$this->set('opcion',$var);

}//fin reporte_entradas_salidas_inventario

function opcion_productos_reporte($var=null){
	$this->layout="ajax";
	$this->set('opcion', $var);
}

function select_productos($pista=null){
	$this->layout="ajax";
	$pista = strtoupper($pista);
	$productos = $this->v_ciad01_inventario_productos->generateList($this->SQLCA()." AND UPPER(denominacion) like '%".$pista."%'", 'denominacion ASC', null, '{n}.v_ciad01_inventario_productos.cod_prod_serv', '{n}.v_ciad01_inventario_productos.denominacion');
	if($productos == null){
		$this->set('productos', array());
		$this->set('errorMessage', 'NO SE ENCONTRO NINGÚN PRODUCTO');
	}else{
		$this->set('productos', $productos);
		$this->set('Message_existe', 'SELECCIONE EL PRODUCTO');
	}
}




}//fin class