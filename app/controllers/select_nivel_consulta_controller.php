<?php

class SelectNivelConsultaController extends AppController
{
	var $name = "select_nivel_consulta";
    var $uses = array("arrd01", "arrd02", "arrd03", "arrd04", "arrd05");
    var $helpers = array('Html', 'Javascript', 'Ajax','Sisap');





function index($var1=null, $var2=null){

$this->layout="ajax";

$cond = "cod_presi=1";


        $lista=  $this->arrd01->generateList(null, 'denominacion ASC', null, '{n}.arrd01.cod_presi', '{n}.arrd01.denominacion');
		$this->set('vector', $lista);



		      if($var1==1){

            $lista=  $this->arrd02->generateList($cond, 'denominacion ASC', null, '{n}.arrd02.cod_entidad', '{n}.arrd02.denominacion');
			$this->set('vector2', $lista);

		}else if($var1==2){

			$lista=  $this->arrd02->generateList($cond, 'denominacion ASC', null, '{n}.arrd02.cod_entidad', '{n}.arrd02.denominacion');
			$this->set('vector2', $lista);


		}else if($var1==3){

			$lista=  $this->arrd03->generateList($cond, 'cod_tipo_inst ASC', null, '{n}.arrd03.cod_tipo_inst', '{n}.arrd03.denominacion');
			$this->set('vector2', $lista);


		}else if($var1==4){

            $lista=  $this->arrd02->generateList($cond, 'denominacion ASC', null, '{n}.arrd02.cod_entidad', '{n}.arrd02.denominacion');
			$this->set('vector2', $lista);

		}//fin else


$this->set("cod_presi", 1);
$this->set("var", $var1);


}//fin functions










function select_institucion($var1=null, $var2=null, $var3=null){

$this->layout="ajax";

if($var1!=null){


	$cond = "cod_presi=".$var1;


	      if($var3!=null){

	        $lista=  $this->arrd04->generateList($cond." and cod_entidad='".$var2."' and cod_tipo_inst='".$var3."'  ", 'denominacion ASC', null, '{n}.arrd04.cod_inst', '{n}.arrd04.denominacion');
			$this->set('vector', $lista);
			$this->set("var", 3);

	}else if($var2!=null){

	        $lista=  $this->arrd03->generateList($cond, 'cod_tipo_inst ASC', null, '{n}.arrd03.cod_tipo_inst', '{n}.arrd03.denominacion');
			$this->set('vector', $lista);
	        $this->set("var", 2);


	}else if($var1!=null){

	        $lista=  $this->arrd02->generateList($cond, 'denominacion ASC', null, '{n}.arrd02.cod_entidad', '{n}.arrd02.denominacion');
			$this->set('vector', $lista);
			$this->set("var", 1);


	}


}//fin if


$this->set("var_1", $var1);
$this->set("var_2", $var2);
$this->set("var_3", $var3);



}//fin function








function select_estado($var1=null, $var2=null, $var3=null){

$this->layout="ajax";

if($var1!=null){

        $cond = "cod_presi=".$var1;
        $lista=  $this->arrd02->generateList($cond, 'denominacion ASC', null, '{n}.arrd02.cod_entidad', '{n}.arrd02.denominacion');
		$this->set('vector', $lista);

}

}//fin function









function select_tipo_institucion($var1=null, $var2=null, $var3=null){

$this->layout="ajax";

if($var1!=null){

	$cond = "cod_presi=".$var1;

	        $lista=  $this->arrd03->generateList($cond, 'cod_tipo_inst ASC', null, '{n}.arrd03.cod_tipo_inst', '{n}.arrd03.denominacion');
			$this->set('vector', $lista);


	$this->set("var_1", $var1);
	$this->set("var_2", $var2);
	$this->set("var_3", $var3);

}

}//fin function









}//fin class

?>