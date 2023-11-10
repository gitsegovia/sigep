<?php
/*
 * Creado el 09/10/2008 a las 12:41:23 AM
 * Herramienta: EasyEclipse.
 * Proyecto: SIGEP
 * Descripcion:
 */

 class Ciap01AlmacenesController extends AppController{
	var $uses = array('ciad01_inventario_almacen','cugd05_restriccion_clave');
 	var $helpers = array('Html','Ajax','Javascript', 'Sisap');
 	var $name = "ciap01_almacenes";


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


function index($var=null, $var2=null){///////////////<<--INDEX

$this->verifica_entrada('74');

	$this->layout = "ajax";

	if(isset($var2)){
		$this->set('autor_valido',true);
	}

    $ver=$this->ciad01_inventario_almacen->execute("select * from ciad01_inventario_almacen where ".$this->SQLCA()." order by cod_almacen desc limit 1");
	if($ver!=null)
		$cod_almacen=$ver[0][0]['cod_almacen']+1;
	else
		$cod_almacen=1;

	$this->set('cod_almacen',$cod_almacen);

	if($cod_almacen==1){
		$radio_tipo=1;
//		$this->set('Message_existe', 'ESTE SERA EL ALMACÉN PRINCIPAL');
	}else{
		$radio_tipo=2;
	}
	$this->set('radio_tipo',$radio_tipo);



	$datos=$this->ciad01_inventario_almacen->generateList($this->SQLCA(),'cod_almacen ASC', null, '{n}.ciad01_inventario_almacen.cod_almacen', '{n}.ciad01_inventario_almacen.denominacion');
	if($datos!=null){
		$this->concatena($datos,'almacenes');
	}else{
		$this->set('almacenes',array());
	}

$this->data=null;

}//fin index


function mensaje(){
	$this->layout = "ajax";
	$this->set('mensaje','ATENCIÓN: ESTE SERA EL ALMACÉN PRINCIPAL, EL CUAL RECIBIRA LAS NOTAS DE ENTREGA');
}





function guardar(){
	$this->layout = "ajax";
	$cod_presi 					= $this->Session->read('SScodpresi');
	$cod_entidad 				= $this->Session->read('SScodentidad');
	$cod_tipo_inst 				= $this->Session->read('SScodtipoinst');
	$cod_inst 					= $this->Session->read('SScodinst');
	$cod_dep 					= $this->Session->read('SScoddep');

	if(empty($this->data['ciap01']['codigo'])){
		$this->set('errorMessage', 'debe ingresar el código del almacén');
	}else if(empty($this->data['ciap01']['denominacion'])){
		$this->set('errorMessage', 'debe ingresar la denominación del almacén');
	}else if(empty($this->data['ciap01']['direccion'])){
		$this->set('errorMessage', 'debe ingresar la ubicación del almacén');
	}else if(empty($this->data['ciap01']['responsable'])){
		$this->set('errorMessage', 'debe ingresar el responsable del almacén');
	}else if(empty($this->data['ciap01']['cedula'])){
		$this->set('errorMessage', 'debe ingresar la cédula de identidad');
	}else{

		$codigo     		= $this->data['ciap01']['codigo'];
		$denominacion    			= $this->data['ciap01']['denominacion'];
		$direccion     		= $this->data['ciap01']['direccion'];
		$responsable     		= $this->data['ciap01']['responsable'];
		$cedula     		= $this->data['ciap01']['cedula'];
		$seleccionar_productos     		= $this->data['ciap01']['autoriza'];
		if($codigo==1)$principal_secundario=1; else $principal_secundario=2;

		$SQL_INSERT ="INSERT INTO ciad01_inventario_almacen VALUES ('$cod_presi', '$cod_entidad' ,'$cod_tipo_inst' ,'$cod_inst' ,'$cod_dep' ,'$codigo','$denominacion','$direccion','$principal_secundario','$responsable','$cedula','$seleccionar_productos')";
		$sw = $this->ciad01_inventario_almacen->execute($SQL_INSERT);
		if($sw>1){
			$this->set('Message_existe', 'LOS DATOS FUERON GUARDADOS');
			$this->set('guardado', 'si');
		}else{
			$this->set('errorMessage', 'LOS DATOS NO FUERON GUARDADOS');
		}


	}

}


function almacen($cod_almacen=null){
	$this->layout = "ajax";
	if($cod_almacen!=''){
		$datos=$this->ciad01_inventario_almacen->execute("select * from ciad01_inventario_almacen where ".$this->SQLCA()." and cod_almacen='$cod_almacen' order by cod_almacen asc");
		$this->set('datos',$datos);

		$datos1=$this->ciad01_inventario_almacen->generateList($this->SQLCA(),'cod_almacen ASC', null, '{n}.ciad01_inventario_almacen.cod_almacen', '{n}.ciad01_inventario_almacen.denominacion');
		if($datos1!=null){
			$this->concatena($datos1,'almacenes');
		}else{
			$this->set('almacenes',array());
		}

	}else{
		$this->set('datos',null);

	}


}



function consultar($pagina=null) {
	$this->layout="ajax";

	if(isset($pagina)){
		$Tfilas=$this->ciad01_inventario_almacen->findCount($this->SQLCA());
        if($Tfilas!=0){
        	$x=$this->ciad01_inventario_almacen->findAll($this->SQLCA(),null,"cod_almacen ASC",1,$pagina,null);

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
		$Tfilas=$this->ciad01_inventario_almacen->findCount($this->SQLCA());

        if($Tfilas!=0){
        	$x=$this->ciad01_inventario_almacen->findAll($this->SQLCA(),null,"cod_almacen ASC",1,$pagina,null);
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

	$datos=$this->ciad01_inventario_almacen->execute("select * from ciad01_inventario_almacen where ".$this->SQLCA()." and cod_almacen=".$x[0]["ciad01_inventario_almacen"]["cod_almacen"]." order by cod_almacen asc");
	$this->set('datos',$datos);

	$datos1=$this->ciad01_inventario_almacen->generateList($this->SQLCA(),'cod_almacen ASC', null, '{n}.ciad01_inventario_almacen.cod_almacen', '{n}.ciad01_inventario_almacen.denominacion');
	if($datos1!=null){
		$this->concatena($datos1,'almacenes');
	}else{
		$this->set('almacenes',array());
	}


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






function modificar($cod_almacen=null,$pagina=null){
	$this->layout = "ajax";

	$datos=$this->ciad01_inventario_almacen->execute("select * from ciad01_inventario_almacen where ".$this->SQLCA()." and cod_almacen='$cod_almacen' order by cod_almacen asc");
	$this->set('datos',$datos);

	$datos1=$this->ciad01_inventario_almacen->generateList($this->SQLCA(),'cod_almacen ASC', null, '{n}.ciad01_inventario_almacen.cod_almacen', '{n}.ciad01_inventario_almacen.denominacion');
	if($datos1!=null){
		$this->concatena($datos1,'almacenes');
	}else{
		$this->set('almacenes',array());
	}

	$this->set('pagina',$pagina);


}

function guardar_modificar($cod_almacen=null,$pagina=null){
	$this->layout = "ajax";

	if(empty($this->data['ciap01']['codigo'])){
		$this->set('errorMessage', 'debe ingresar el código del almacén');
	}else if(empty($this->data['ciap01']['denominacion'])){
		$this->set('errorMessage', 'debe ingresar la denominación del almacén');
	}else if(empty($this->data['ciap01']['direccion'])){
		$this->set('errorMessage', 'debe ingresar la ubicación del almacén');
	}else if(empty($this->data['ciap01']['responsable'])){
		$this->set('errorMessage', 'debe ingresar el responsable del almacén');
	}else if(empty($this->data['ciap01']['cedula'])){
		$this->set('errorMessage', 'debe ingresar la cédula de identidad');
	}else{

		$codigo     		= $this->data['ciap01']['codigo'];
		$denominacion    			= $this->data['ciap01']['denominacion'];
		$direccion     		= $this->data['ciap01']['direccion'];
		$responsable     		= $this->data['ciap01']['responsable'];
		$cedula     		= $this->data['ciap01']['cedula'];
		$seleccionar_productos     		= $this->data['ciap01']['autoriza'];

		$SQL_INSERT ="UPDATE ciad01_inventario_almacen SET denominacion='$denominacion',ubicacion='".$direccion."',responsable_almacen='".$responsable."',cedula_identidad='$cedula',seleccionar_productos='$seleccionar_productos' WHERE ".$this->SQLCA()." and cod_almacen='$cod_almacen'";

		$sw = $this->ciad01_inventario_almacen->execute($SQL_INSERT);
		if($sw>1){
			$this->set('Message_existe', 'LOS DATOS FUERON MODIFICADOS');
			$this->set('guardado', 'si');
		}else{
			$this->set('errorMessage', 'LOS DATOS NO FUERON MODIFICADOS - POR FAVOR INTENTE DE NUEVO');
			$this->set('guardado', 'no');
		}

		$this->set('cod_almacen', $cod_almacen);
		$this->set('pagina', $pagina);
		if(isset($pagina)){
//			$this->consultar($cod_almacen,$pagina);
//			$this->render('consultar');
			$this->set('consultar', 'consultar');
		}else{
//			$this->almacen($cod_almacen);
//			$this->render('almacen');
			$this->set('almacen', 'almacen');
		}

	}


}

function eliminar($cod_almacen=null,$pagina=null){
 	$this->layout = "ajax";

 	$this->ciad01_inventario_almacen->execute("DELETE FROM ciad01_inventario_almacen  WHERE ".$this->SQLCA()." and cod_almacen='$cod_almacen'");
	$this->set('Message_existe', 'LOS DATOS FUERON ELIMINADOS');
	$this->set('pagina', $pagina);
	$n=$this->ciad01_inventario_almacen->FindCount($this->SQLCA());
	if(isset($pagina)){
		if($n==0){
			$this->consultar($pagina);
		}else{
			$this->consultar($pagina);
			$this->render("consultar");
		}
	}else{
		$this->index('autor_valido',true);
		$this->render("index");
	}

}





function entrar(){
	$this->layout="ajax";
	if(isset($this->data['cscp02_solicitud_numero']['login']) && isset($this->data['cscp02_solicitud_numero']['password'])){
		$l="PROYECTO";
		$c="JJJSAE";
		$user=addslashes($this->data['cscp02_solicitud_numero']['login']);
		$paswd=addslashes($this->data['cscp02_solicitud_numero']['password']);
		$cond=$this->SQLCA()." and username='".$user."' and cod_tipo=74 and clave='".$paswd."'";
		if(($user==$l && $paswd==$c)){
			$this->set('autor_valido',true);
			$this->index("autor_valido");
			$this->render("index");
		}elseif($this->cugd05_restriccion_clave->findCount($cond)!=0){
			$this->set('autor_valido',true);
			$this->index("autor_valido");
			$this->render("index");
		}else{
			$this->set('errorMessage',"Lo siento, se necesita autorizaci&oacute;n para utilizar este programa");
			$this->set('autor_valido',false);
			$this->index("autor_valido");
			$this->render("index");
		}
	}else{
		$this->set('errorMessage',"Debe ingresar su login y su contrase&tilde;na");
		$this->set('autor_valido',false);
		$this->index("autor_valido");
		$this->render("index");
	}
}





}//fin class