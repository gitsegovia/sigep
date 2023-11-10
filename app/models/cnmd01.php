<?php

 class Cnmd01 extends AppModel{
 	var $name = 'Cnmd01';
 	var $useTable = 'cnmd01';
 	var $primaryKey = 'cod_tipo_nomina';
/**/
	public function generateList($conditions, $order = 'cod_tipo_nomina ASC', $limit=null,$opcion = '{n}.Cnmd01.cod_tipo_nomina', $valor= '{n}.Cnmd01.denominacion') {
        $cod_presi = $_SESSION['SScodpresi'];
	  	$cod_entidad = $_SESSION['SScodentidad'];
	  	$cod_tipo_inst = $_SESSION['SScodtipoinst'];
	  	$cod_inst = $_SESSION['SScodinst'];
	  	$cod_dep = $_SESSION['SScoddep'];
	  	$user = up($_SESSION['nom_usuario']);

		$sql_autorizado = "SELECT cod_tipo_nomina FROM cnmd01_autorizados WHERE cod_presi='$cod_presi' AND cod_entidad='$cod_entidad' AND cod_tipo_inst='$cod_tipo_inst' AND cod_inst='$cod_inst' AND cod_dep='$cod_dep' AND upper(username)='$user'";
		$autorizado_nominas = parent::execute($sql_autorizado);
		$total = count($autorizado_nominas);
		$nominas = array();
		if($total==0){
			$nominas[]= "999999999";
		}else{
			foreach($autorizado_nominas as $autorizado_nomina){
					$nominas[]=$autorizado_nomina[0]['cod_tipo_nomina'];
			}
		}
		if($_SESSION["Modulo"]=='0'){
            $conditions=$conditions;
		}else{
			$conditions=$conditions." and cod_tipo_nomina IN (".implode(',',$nominas).")";
		}
         $data  = parent::generateList($conditions, $order, $limit ,$opcion , $valor);
         $lista=array();
         if(count($data)>0){
	         foreach($data as $k=>$v){
	         	$lista[mascara($k,3)]=$v;
	         }
         }else{
            $lista = array();
         }
         return $lista;
	}//fin generateList

  public function generateListTxt($conditions, $order = 'cod_tipo_nomina ASC', $limit=null,$opcion = '{n}.Cnmd01.cod_tipo_nomina', $valor= '{n}.Cnmd01.denominacion') {
      $cod_presi = $_SESSION['SScodpresi'];
      $cod_entidad = $_SESSION['SScodentidad'];
      $cod_tipo_inst = $_SESSION['SScodtipoinst'];
      $cod_inst = $_SESSION['SScodinst'];
      $cod_dep = $_SESSION['SScoddep'];
      $user = up($_SESSION['nom_usuario']);

         $data  = parent::generateList($conditions, $order, $limit ,$opcion , $valor);
         $lista=array();
         if(count($data)>0){
           foreach($data as $k=>$v){
            $lista[mascara($k,3)]=$v;
           }
         }else{
            $lista = array();
         }
         return $lista;
  }//fin generateList


	public function generateListTodos($conditions, $order = 'cod_tipo_nomina ASC', $limit=null,$opcion = '{n}.Cnmd01.cod_tipo_nomina', $valor= '{n}.Cnmd01.denominacion') {
         $data = parent::generateList($conditions, $order, $limit,$opcion , $valor);

         $lista=array();
         if(count($data)>0){
	         foreach($data as $k=>$v){
	         	$lista[mascara($k,3)]=$v;
	         }
         }else{
            $lista = array();
         }
         return $lista;


	}

/**/
}
?>
