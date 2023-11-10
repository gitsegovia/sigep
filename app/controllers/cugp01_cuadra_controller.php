<?php

 class Cugp01cuadraController extends AppController{

	var $name = 'cugp01_cuadra';
	var $uses = array('cugd01_republica', 'cugd01_estados', 'cugd01_municipios', 'cugd01_parroquias', 'cugd01_centropoblados',
                      'cugd01_vialidad',  'cugd01_vereda',  'cugd01_cuadra', 'v_cugd01_cuadra','cugd90_municipio_defecto');
 	var $helpers = array('Html','Ajax','Javascript', 'Sisap');

 	
function checkSession(){
	if (!$this->Session->check('Usuario')){
		if (!$this->Session->check('concejo_comunal')){
			$this->redirect('/salir');
			exit();
		}
	}else{
		//$this->set('userTable', $this->requestAction('/cnmp03partidas/', array('return')));
		//echo "H".$this->requestAction('/usuarios/actualizar_user',array('return'));
		$this->requestAction('/usuarios/actualizar_user');
	}
}//fin checksession

function beforeFilter(){
	$this->checkSession();
	$cod_presi = $this->Session->read('cod_presi_geografico');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$modulo = $this->Session->read('Modulo');
}

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

function SQLCA_S($ano=null){//sql para busqueda de codigos de arranque con y sin año
		 $sql_re = "cod_presi=".$this->verifica_SS(1)."  and    ";
		 $sql_re .= "cod_entidad=".$this->verifica_SS(2)."  and  ";
		 $sql_re .= "cod_tipo_inst=".$this->verifica_SS(3)."  and ";
		 $sql_re .= "cod_inst=".$this->verifica_SS(4)."   ";
		 return $sql_re;
}//fin funcion SQLCA_s

function index(){
	$this->layout = "ajax";
	$this->Session->delete('cod_1');
	$this->Session->delete('cod_2');
	$this->Session->delete('cod_3');
	$this->Session->delete('cod_4');
	$this->Session->delete('cod_5');
	$this->Session->delete('cod_6');
	$this->Session->delete('cod_7');
	$this->Session->delete('cod_8');
	
	$this->set('lista', $this->cugd01_republica->generateList(null, 'denominacion ASC', null, '{n}.cugd01_republica.cod_republica', '{n}.cugd01_republica.denominacion'));
	$this->Session->write('cod_1', 1);
	$sql_re = "cod_republica=1";
	$this->set('lista2', $this->cugd01_estados->generateList($sql_re, 'denominacion ASC', null, '{n}.cugd01_estados.cod_estado', '{n}.cugd01_estados.denominacion'));
	
	$can_mun_def=$this->cugd90_municipio_defecto->findCount($this->SQLCA_S());
	if($can_mun_def!=0){
		$mun_defecto=$this->cugd90_municipio_defecto->findAll($this->SQLCA_S());
	    $cod_republica=$mun_defecto[0]["cugd90_municipio_defecto"]["cod_republica"];
	    $cod_estado=$mun_defecto[0]["cugd90_municipio_defecto"]["cod_estado"];
	    $cod_municipio=$mun_defecto[0]["cugd90_municipio_defecto"]["cod_municipio"];
	    
	    $lista_r=  $this->cugd01_republica->generateList(null, 'denominacion ASC', null, '{n}.cugd01_republica.cod_republica', '{n}.cugd01_republica.denominacion');
		$lista_e=  $this->cugd01_estados->generateList("cod_republica=".$cod_republica, 'denominacion ASC', null, '{n}.cugd01_estados.cod_estado', '{n}.cugd01_estados.denominacion');
		$lista_m=  $this->cugd01_municipios->generateList("cod_republica=".$cod_republica." and cod_estado=".$cod_estado, 'denominacion ASC', null, '{n}.cugd01_municipios.cod_municipio', '{n}.cugd01_municipios.denominacion');
		
		$this->set('municipio', $lista_m);
		$this->set('selecion_estado',$cod_estado);
		$this->set('selecion_municipio',$cod_municipio);
		$this->Session->write('cod_2', $cod_estado);
		$this->Session->write('cod_3', $cod_municipio);
		
		$sql_parroquia="cod_republica=".$this->Session->read('cod_presi_geografico')." and cod_estado=".$cod_estado."  and cod_municipio=".$cod_municipio." ";
		$parroquia = $this->cugd01_parroquias->generateList($sql_parroquia, 'denominacion ASC', null, '{n}.cugd01_parroquias.cod_parroquia', '{n}.cugd01_parroquias.denominacion');
		$this->set('parroquia', $parroquia);
	}
	
}//fin function


function select($var1=null, $var2_aux=null){

$this->layout = "ajax";

if($var2_aux==null){$var2_aux=0;}

          if($var1==1){ $var2 = $var1+1;
          	            $var3 = $var1+2;
          	            $this->Session->write('cod_1', $var2_aux);
                        $sql_re = "cod_republica=".$var2_aux;
          	            $this->set('lista', $this->cugd01_estados->generateList($sql_re, 'denominacion ASC', null, '{n}.cugd01_estados.cod_estado', '{n}.cugd01_estados.denominacion'));

    }else if($var1==2){
    					$var2  = $var1+1;
          	            $var3  = $var1+2;
                        $cod_1 = $this->Session->read('cod_1');
          	            $this->Session->write('cod_2', $var2_aux);
          	            $sql_re = "cod_republica=".$cod_1." and cod_estado=".$var2_aux;
          	            $this->set('lista', $this->cugd01_municipios->generateList($sql_re, 'denominacion ASC', null, '{n}.cugd01_municipios.cod_municipio', '{n}.cugd01_municipios.denominacion'));

    }else if($var1==3){ $var2  = $var1+1;
          	            $var3  = $var1+2;
                        $cod_1 = $this->Session->read('cod_1');
                        $cod_2 = $this->Session->read('cod_2');
          	            $this->Session->write('cod_3', $var2_aux);
          	            $sql_re = "cod_republica=".$cod_1." and cod_estado=".$cod_2." and cod_municipio=".$var2_aux;
          	            $this->set('lista', $this->cugd01_parroquias->generateList($sql_re, 'denominacion ASC', null, '{n}.cugd01_parroquias.cod_parroquia', '{n}.cugd01_parroquias.denominacion'));


    }else if($var1==4){ $var2  = $var1+1;
          	            $var3  = $var1+2;
          	            $cod_1 = $this->Session->read('cod_1');
                        $cod_2 = $this->Session->read('cod_2');
                        $cod_3 = $this->Session->read('cod_3');
          	            $this->Session->write('cod_4', $var2_aux);
          	            $sql_re  = "     cod_republica=".$cod_1." and cod_estado=".$cod_2." and cod_municipio=".$cod_3;
          	            $sql_re .= " and cod_parroquia=".$var2_aux;
          	            $this->set('lista', $this->cugd01_centropoblados->generateList($sql_re, 'denominacion ASC', null, '{n}.cugd01_centropoblados.cod_centro', '{n}.cugd01_centropoblados.denominacion'));

    }else if($var1==5){ $var2  = $var1+1;
          	            $var3  = $var1+2;
          	            $cod_1 = $this->Session->read('cod_1');
                        $cod_2 = $this->Session->read('cod_2');
                        $cod_3 = $this->Session->read('cod_3');
                        $cod_4 = $this->Session->read('cod_4');
          	            $this->Session->write('cod_5', $var2_aux);
          	            $sql_re  = "     cod_republica=".$cod_1." and cod_estado=".$cod_2." and cod_municipio=".$cod_3;
          	            $sql_re .= " and cod_parroquia=".$cod_4." and cod_centro=".$var2_aux;
          	            $this->set('lista', $this->cugd01_vialidad->generateList($sql_re, 'denominacion ASC', null, '{n}.cugd01_vialidad.cod_vialidad', '{n}.cugd01_vialidad.denominacion'));

    }else if($var1==6){ $var2  = $var1+1;
          	            $var3  = $var1+2;
          	            $cod_1 = $this->Session->read('cod_1');
                        $cod_2 = $this->Session->read('cod_2');
                        $cod_3 = $this->Session->read('cod_3');
                        $cod_4 = $this->Session->read('cod_4');
                        $cod_5 = $this->Session->read('cod_5');
          	            $this->Session->write('cod_6', $var2_aux);
          	            $sql_re  = "     cod_republica=".$cod_1." and cod_estado=".$cod_2." and cod_municipio=".$cod_3;
          	            $sql_re .= " and cod_parroquia=".$cod_4." and cod_centro=".$cod_5." and cod_vialidad=".$var2_aux;
          	            $this->set('lista', $this->cugd01_vereda->generateList($sql_re, 'denominacion ASC', null, '{n}.cugd01_vereda.cod_vereda', '{n}.cugd01_vereda.denominacion'));


    }else if($var1==7){ $var2  = $var1+1;
          	            $var3  = $var1+2;
          	            $cod_1 = $this->Session->read('cod_1');
                        $cod_2 = $this->Session->read('cod_2');
                        $cod_3 = $this->Session->read('cod_3');
                        $cod_4 = $this->Session->read('cod_4');
                        $cod_5 = $this->Session->read('cod_5');
                        $cod_6 = $this->Session->read('cod_6');
          	            $this->Session->write('cod_7', $var2_aux);
          	            $sql_re  = "     cod_republica=".$cod_1." and cod_estado=".$cod_2." and cod_municipio=".$cod_3;
          	            $sql_re .= " and cod_parroquia=".$cod_4." and cod_centro=".$cod_5." and cod_vialidad=".$cod_6;
          	            $sql_re .= " and cod_vereda=".$var2_aux;
          	            $this->set('lista', $this->cugd01_cuadra->generateList($sql_re, 'cod_cuadra ASC', null, '{n}.cugd01_cuadra.cod_cuadra', '{n}.cugd01_cuadra.denominacion'));

    }else if($var1==8){ $var2  = $var1+1;
          	            $var3  = $var1+2;
          	            $cod_1 = $this->Session->read('cod_1');
                        $cod_2 = $this->Session->read('cod_2');
                        $cod_3 = $this->Session->read('cod_3');
                        $cod_4 = $this->Session->read('cod_4');
                        $cod_5 = $this->Session->read('cod_5');
                        $cod_6 = $this->Session->read('cod_6');
                        $cod_7 = $this->Session->read('cod_7');
          	            $this->Session->write('cod_8', $var2_aux);


    }//fin

if($var2_aux==0){$var2_aux=null;}
$this->set("n1", $var2);
$this->set("n2", $var3);
$this->set("opcion",  $var1);
$this->set("opcion2", $var2_aux);


}//fin function









function seleccion($var1=null, $var2_aux=null){

  $this->layout = "ajax";

                        $cod_1 = $this->Session->read('cod_1');
                        $cod_2 = $this->Session->read('cod_2');
                        $cod_3 = $this->Session->read('cod_3');
                        $cod_4 = $this->Session->read('cod_4');
                        $cod_5 = $this->Session->read('cod_5');
                        $cod_6 = $this->Session->read('cod_6');
                        $cod_7 = $this->Session->read('cod_7');
                        $cod_8 = $var1;

$sql_re  = "     cod_republica=".$cod_1." and cod_estado=".$cod_2." and cod_municipio=".$cod_3;
$sql_re .= " and cod_parroquia=".$cod_4." and cod_centro=".$cod_5." and cod_vialidad=".$cod_6;
$sql_re .= " and cod_vereda=".$cod_7;


if($var1=="OTROS"){
        $datos_1 = $this->cugd01_cuadra->findAll($sql_re, null, " cod_cuadra DESC");
		if(!isset($datos_1[0]["cugd01_cuadra"]["cod_cuadra"])){$datos_1[0]["cugd01_cuadra"]["cod_cuadra"]=0;}
		$this->set('cod_cuadra_aux',$datos_1[0]["cugd01_cuadra"]["cod_cuadra"]+1);
}else{
        $this->set('lista', $this->cugd01_cuadra->generateList($sql_re, 'cod_cuadra ASC', null, '{n}.cugd01_cuadra.cod_cuadra', '{n}.cugd01_cuadra.denominacion'));
}//fin else



$this->set("opcion",  $var1);

}//fin function









function guardar($var1=null, $var2_aux=null){

  $this->layout = "ajax";

$cod_1 = $this->Session->read('cod_1');
$cod_2 = $this->Session->read('cod_2');
$cod_3 = $this->Session->read('cod_3');
$cod_4 = $this->Session->read('cod_4');
$cod_5 = $this->Session->read('cod_5');
$cod_6 = $this->Session->read('cod_6');
$cod_7 = $this->Session->read('cod_7');

if(!empty($this->data['cugp01_cuadra']['codigo_8_deno'])){

	$cod_8      = $this->data['cugp01_cuadra']['codigo_8'];
	$cod_8_deno = $this->data['cugp01_cuadra']['codigo_8_deno'];

    $sql_re  = "     cod_republica=".$cod_1." and cod_estado=".$cod_2." and cod_municipio=".$cod_3;
	$sql_re .= " and cod_parroquia=".$cod_4." and cod_centro=".$cod_5." and cod_vialidad=".$cod_6;
	$sql_re .= " and cod_vereda=".$cod_7."    and cod_cuadra=".$cod_8;

	if($this->cugd01_cuadra->findCount($sql_re) == 0){
		$sql = "INSERT INTO cugd01_cuadra (cod_republica, cod_estado, cod_municipio, cod_parroquia, cod_centro, cod_vialidad, cod_vereda, cod_cuadra, denominacion)   VALUES  ( '".$cod_1."',  '".$cod_2."',  '".$cod_3."',  '".$cod_4."', '".$cod_5."', '".$cod_6."',  '".$cod_7."',  '".$cod_8."',  '".$cod_8_deno."')";
		$this->cugd01_cuadra->execute($sql);
	}else{
		$sql ="UPDATE cugd01_cuadra  SET  denominacion = '".$cod_8_deno."' where ".$sql_re;
		$this->cugd01_cuadra->execute($sql);
	}//fin else
 $this->set("opcion",  $cod_8);
 $this->set("Message_existe", "los datos fueron guardados");
}else{
 $this->set("opcion",  null);
 $this->set("errorMessage",   "los datos no fueron guardados");
}//fin else


    $sql_re  = "     cod_republica=".$cod_1." and cod_estado=".$cod_2." and cod_municipio=".$cod_3;
	$sql_re .= " and cod_parroquia=".$cod_4." and cod_centro=".$cod_5." and cod_vialidad=".$cod_6;
	$sql_re .= " and cod_vereda=".$cod_7;

	$this->set('lista', $this->cugd01_cuadra->generateList($sql_re, 'cod_cuadra ASC', null, '{n}.cugd01_cuadra.cod_cuadra', '{n}.cugd01_cuadra.denominacion'));



$this->data = null;

$this->render("seleccion");


}//fin function












function guardar2($var1=null, $var2_aux=null){

  $this->layout = "ajax";

$cod_1 = $this->Session->read('cod_1');
$cod_2 = $this->Session->read('cod_2');
$cod_3 = $this->Session->read('cod_3');
$cod_4 = $this->Session->read('cod_4');
$cod_5 = $this->Session->read('cod_5');
$cod_6 = $this->Session->read('cod_6');
$cod_7 = $this->Session->read('cod_7');

if(!empty($this->data['cugp01_cuadra']['codigo_8_deno'])){

	$cod_8      = $this->data['cugp01_cuadra']['codigo_8'];
	$cod_8_deno = $this->data['cugp01_cuadra']['codigo_8_deno'];

    $sql_re  = "     cod_republica=".$cod_1." and cod_estado=".$cod_2." and cod_municipio=".$cod_3;
	$sql_re .= " and cod_parroquia=".$cod_4." and cod_centro=".$cod_5." and cod_vialidad=".$cod_6;
	$sql_re .= " and cod_vereda=".$cod_7."    and cod_cuadra=".$cod_8;

	if($this->cugd01_cuadra->findCount($sql_re) == 0){
		$sql = "INSERT INTO cugd01_cuadra (cod_republica, cod_estado, cod_municipio, cod_parroquia, cod_centro, cod_vialidad, cod_vereda, cod_cuadra, denominacion)   VALUES  ( '".$cod_1."',  '".$cod_2."',  '".$cod_3."',  '".$cod_4."', '".$cod_5."', '".$cod_6."',  '".$cod_7."',  '".$cod_8."',  '".$cod_8_deno."')";
		$this->cugd01_cuadra->execute($sql);
	}else{
		$sql ="UPDATE cugd01_cuadra  SET  denominacion = '".$cod_8_deno."' where ".$sql_re;
		$this->cugd01_cuadra->execute($sql);
	}//fin else
 $this->set("opcion",  $cod_8);
 $this->set("Message_existe", "los datos fueron guardados");
}else{
 $this->set("opcion",  null);
 $this->set("errorMessage",   "los datos no fueron guardados");
}//fin else


    $sql_re  = "     cod_republica=".$cod_1." and cod_estado=".$cod_2." and cod_municipio=".$cod_3;
	$sql_re .= " and cod_parroquia=".$cod_4." and cod_centro=".$cod_5." and cod_vialidad=".$cod_6;
	$sql_re .= " and cod_vereda=".$cod_7;

	$this->set('lista', $this->cugd01_cuadra->generateList($sql_re, 'cod_cuadra ASC', null, '{n}.cugd01_cuadra.cod_cuadra', '{n}.cugd01_cuadra.denominacion'));



$this->data = null;

$this->consulta($var1);
$this->render("consulta");


}//fin function




















function eliminar($var1=null, $var2_aux=null){

$this->layout = "ajax";

	$cod_1 = $this->Session->read('cod_1');
	$cod_2 = $this->Session->read('cod_2');
	$cod_3 = $this->Session->read('cod_3');
	$cod_4 = $this->Session->read('cod_4');
	$cod_5 = $this->Session->read('cod_5');
	$cod_6 = $this->Session->read('cod_6');
	$cod_7 = $this->Session->read('cod_7');

	$cod_8      = $this->data['cugp01_cuadra']['codigo_8'];

	$sql_re  = "     cod_republica=".$cod_1." and cod_estado=".$cod_2." and cod_municipio=".$cod_3;
	$sql_re .= " and cod_parroquia=".$cod_4." and cod_centro=".$cod_5." and cod_vialidad=".$cod_6;
	$sql_re .= " and cod_vereda=".$cod_7."    and cod_cuadra=".$cod_8;

	$this->cugd01_cuadra->execute("DELETE FROM cugd01_cuadra  where ".$sql_re);
	$this->set("Message_existe", "los datos fueron eliminados");

	$sql_re  = "     cod_republica=".$cod_1." and cod_estado=".$cod_2." and cod_municipio=".$cod_3;
	$sql_re .= " and cod_parroquia=".$cod_4." and cod_centro=".$cod_5." and cod_vialidad=".$cod_6;
	$sql_re .= " and cod_vereda=".$cod_7;

	$this->set('lista', $this->cugd01_cuadra->generateList($sql_re, 'cod_cuadra ASC', null, '{n}.cugd01_cuadra.cod_cuadra', '{n}.cugd01_cuadra.denominacion'));

	$this->data = null;
	$this->set("opcion",  null);
	$this->render("seleccion");

}//fin function










function eliminar2($var1=null, $var2_aux=null){

$this->layout = "ajax";

	$cod_1 = $this->Session->read('cod_1');
	$cod_2 = $this->Session->read('cod_2');
	$cod_3 = $this->Session->read('cod_3');
	$cod_4 = $this->Session->read('cod_4');
	$cod_5 = $this->Session->read('cod_5');
	$cod_6 = $this->Session->read('cod_6');
	$cod_7 = $this->Session->read('cod_7');

	$cod_8      = $this->data['cugp01_cuadra']['codigo_8'];

	$sql_re  = "     cod_republica=".$cod_1." and cod_estado=".$cod_2." and cod_municipio=".$cod_3;
	$sql_re .= " and cod_parroquia=".$cod_4." and cod_centro=".$cod_5." and cod_vialidad=".$cod_6;
	$sql_re .= " and cod_vereda=".$cod_7."    and cod_cuadra=".$cod_8;

	$this->cugd01_cuadra->execute("DELETE FROM cugd01_cuadra  where ".$sql_re);
	$this->set("Message_existe", "los datos fueron eliminados");

	$sql_re  = "     cod_republica=".$cod_1." and cod_estado=".$cod_2." and cod_municipio=".$cod_3;
	$sql_re .= " and cod_parroquia=".$cod_4." and cod_centro=".$cod_5." and cod_vialidad=".$cod_6;
	$sql_re .= " and cod_vereda=".$cod_7;

	$this->set('lista', $this->cugd01_cuadra->generateList($sql_re, 'cod_cuadra ASC', null, '{n}.cugd01_cuadra.cod_cuadra', '{n}.cugd01_cuadra.denominacion'));

	$this->data = null;
	$this->set("opcion",  null);

	$var1--;
	if($var1<=0){$var1=1;}
	$this->consulta($var1);

}//fin function









function modificar($var1=null, $var2_aux=null){

$this->layout = "ajax";

	$cod_1 = $this->Session->read('cod_1');
	$cod_2 = $this->Session->read('cod_2');
	$cod_3 = $this->Session->read('cod_3');
	$cod_4 = $this->Session->read('cod_4');
	$cod_5 = $this->Session->read('cod_5');
	$cod_6 = $this->Session->read('cod_6');
	$cod_7 = $this->Session->read('cod_7');

	$cod_8      = $this->data['cugp01_cuadra']['codigo_8'];

	$sql_re  = "     cod_republica=".$cod_1." and cod_estado=".$cod_2." and cod_municipio=".$cod_3;
	$sql_re .= " and cod_parroquia=".$cod_4." and cod_centro=".$cod_5." and cod_vialidad=".$cod_6;
	$sql_re .= " and cod_vereda=".$cod_7."    and cod_cuadra=".$cod_8;

	$datos_1 = $this->cugd01_cuadra->findAll($sql_re);
    $this->set('cod_cuadra_deno',  $datos_1[0]["cugd01_cuadra"]["denominacion"]);
    $this->set("cod_cuadra",       $cod_8);


}//fin function







function modificar2($var1=null, $var2_aux=null){

$this->layout = "ajax";

	$cod_1 = $this->Session->read('cod_1');
	$cod_2 = $this->Session->read('cod_2');
	$cod_3 = $this->Session->read('cod_3');
	$cod_4 = $this->Session->read('cod_4');
	$cod_5 = $this->Session->read('cod_5');
	$cod_6 = $this->Session->read('cod_6');
	$cod_7 = $this->Session->read('cod_7');

	$cod_8      = $this->data['cugp01_cuadra']['codigo_8'];

	$sql_re  = "     cod_republica=".$cod_1." and cod_estado=".$cod_2." and cod_municipio=".$cod_3;
	$sql_re .= " and cod_parroquia=".$cod_4." and cod_centro=".$cod_5." and cod_vialidad=".$cod_6;
	$sql_re .= " and cod_vereda=".$cod_7."    and cod_cuadra=".$cod_8;

	$datos_1 = $this->cugd01_cuadra->findAll($sql_re);
    $this->set('cod_cuadra_deno',  $datos_1[0]["cugd01_cuadra"]["denominacion"]);
    $this->set("cod_cuadra",       $cod_8);
    $this->set("pagina",           $var1);


}//fin function









function consulta($pagina=null){
	$this->layout="ajax";
	if(isset($pagina)){
		$Tfilas=$this->v_cugd01_cuadra->findCount();
        if($Tfilas!=0){
        	$data=$this->v_cugd01_cuadra->findAll(null,null,"cod_republica, cod_estado, cod_municipio, cod_parroquia, cod_centro, cod_vialidad, cod_vereda, cod_cuadra ASC",1,$pagina,null);
            $this->set('DATA',$data);
          	$this->set('siguiente',$pagina+1);
          	$this->set('anterior',$pagina-1);
            $this->bt_nav($Tfilas,$pagina);
            $this->set("pagina",$pagina);
            $this->set("ultimo",$Tfilas);
        }else{
	 	       $this->set('errorMessage', 'No se encontrar&oacute;n datos');
	 	       $this->index();
	 	       $this->render("index");
        }

	}else{
		$pagina=1;
		$Tfilas=$this->v_cugd01_cuadra->findCount();
        if($Tfilas!=0){
        	$data=$this->v_cugd01_cuadra->findAll(null,null,"cod_republica, cod_estado, cod_municipio, cod_parroquia, cod_centro, cod_vialidad, cod_vereda, cod_cuadra ASC",1,$pagina,null);
            $this->set('DATA',$data);
          	$this->set('siguiente',$pagina+1);
          	$this->set('anterior',$pagina-1);
          	$this->bt_nav($Tfilas,$pagina);
          	$this->set("pagina",$pagina);
          	$this->set("ultimo",$Tfilas);
        }else{
	 	       $this->set('errorMessage', 'No se encontrar&oacute;n datos');
	 	       $this->index();
	 	       $this->render("index");
        }
	}
}









}//fin class



?>
