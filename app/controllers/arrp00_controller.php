<?php
/*
 * Fecha: 06/07/2007
 *
 * Por Luis Alfredo Diaz Jaramillo
 *
 * Herramienta utilizada: easyEclipse
 * sisap
 */

 class Arrp00Controller extends AppController{

	var $name = 'Arrp00';
	var $uses = array('arrd01', 'arrd02', 'arrd03', 'arrd04', 'arrd05', 'Usuario');
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
    $cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	if($cod_presi == 0 && $cod_entidad == 0 && $cod_tipo_inst == 0 && $cod_inst == 0 && $cod_dep == 0){
		return;
	}else{
		echo "LO SIENTO - NO TIENE PERMISOS PARA ENTRAR A ESTE MODULO!!";
		exit;
	}
 }

function index() {

   	$this->layout = "ajax";
   	$this->set('arr01', $this->arrd01->generateList(null, 'cod_presi ASC', null, '{n}.arrd01.cod_presi', '{n}.arrd01.cod_presi'));
	}

function selec_arr01($var=null){

   $this->layout = "ajax";

   if($this->data['arrp00']['codigo']){
   	$var = $this->data['arrp00']['codigo'];
   	$this->set('opcion', $var);

   }else $this->set('opcion', $var);

   $lista =  $this->arrd01->generateList(null, 'cod_presi ASC', null, '{n}.arrd01.cod_presi', '{n}.arrd01.cod_presi');

	$this->set('arr01', $lista);


}

function selec_arr02($var=null, $aux=null){
	$this->layout = "ajax";

	if($this->data['arrp00']['codigo'] &&  $var!=null) $this->set('selecion', $this->data['arrp00']['codigo']);
	if($var==null) $var = $this->data['arrp00']['codigo'];
	if($aux!=null) $this->set('selecion', $aux);

	$this->set('opcion1', $var);

	if($var!=null && $var!='otros'){

		$this->set('arr02', $this->arrd02->generateList('where cod_presi =  '.$var.' ', ' cod_entidad ASC', null, '{n}.arrd02.cod_entidad', '{n}.arrd02.cod_entidad'));

	}else $this->set('arr02', '');

}

function selec_arr03($var1=null, $var2=null , $aux=null){
    $this->layout = "ajax";

	if($this->data['arrp00']['codigo']  &&  $var2!=null) $this->set('selecion', $this->data['arrp00']['codigo']);
	if($var2==null) $var2 = $this->data['arrp00']['codigo'];
	if($aux!=null) $this->set('selecion', $aux);

	$this->set('opcion1', $var1);
	$this->set('opcion2', $var2);

	if($var2!=null && $var2!='otros'){

	$this->set('arr03', $this->arrd03->generateList('where cod_presi =  '.$var1.'  and cod_entidad = '.$var2.'', ' cod_tipo_inst ASC', null, '{n}.arrd03.cod_tipo_inst', '{n}.arrd03.cod_tipo_inst'));

	}else $this->set('arr03', '');

}









function selec_arr04($var1=null, $var2=null, $var3=null , $aux=null){
	$this->layout = "ajax";

	if($this->data['arrp00']['codigo']  &&  $var3!=null){ $this->set('selecion', $this->data['arrp00']['codigo']); }
	if($var3==null) $var3 = $this->data['arrp00']['codigo'];
	if($aux!=null) $this->set('selecion', $aux);


	$this->set('opcion1', $var1);
	$this->set('opcion2', $var2);
	$this->set('opcion3', $var3);

	if($var3!=null && $var3!='otros'){

    $this->set('arr04', $this->arrd04->generateList('where cod_presi =  '.$var1.'  and cod_entidad = '.$var2.' and cod_tipo_inst = '.$var3.'', ' cod_inst ASC', null, '{n}.arrd04.cod_inst', '{n}.arrd04.cod_inst'));

	}else $this->set('arr04', '');

}








function selec_arr05($var1=null, $var2=null, $var3=null, $var4=null, $aux=null) {
	$this->layout = "ajax";

	if($this->data['arrp00']['codigo']  &&  $var4!=null){ $this->set('selecion', $this->data['arrp00']['codigo']); }
	if($var4==null) $var4 = $this->data['arrp00']['codigo'];
	if($aux!=null) $this->set('selecion', $aux);

	$this->set('opcion1', $var1);
	$this->set('opcion2', $var2);
	$this->set('opcion3', $var3);
	$this->set('opcion4', $var4);

	if($var4!=null && $var4!='otros'){

	$this->set('arr05', $this->arrd05->generateList('where cod_presi =  '.$var1.'  and cod_entidad = '.$var2.' and cod_tipo_inst = '.$var3.' and cod_inst = '.$var4.'', ' cod_dep ASC', null, '{n}.arrd05.cod_dep', '{n}.arrd05.cod_dep'));

	}else $this->set('arr05', '');

}










function principal($var1=null, $var2=null, $var3=null, $var4=null, $var5=null, $var6=null){

   	$this->layout = "ajax";
	$this->set('opcion1', $var1);
	$this->set('opcion2', $var2);
	$this->set('opcion3', $var3);
	$this->set('opcion4', $var4);
	$this->set('opcion5', $var5);
    $this->set('opcion6', $var6);

	$action='';
	$tabla = '';
	$sql_3 = '';

	if($var1=='otros') $action=$var1;
	if($var2=='otros') $action=$var2;
	if($var3=='otros') $action=$var3;
	if($var4=='otros') $action=$var4;
	if($var5=='otros') $action=$var5;
	if($var6=='otros') $action=$var6;


	if($var1!=null){
		$tabla='arrd01';
		$sql_2 =  $tabla.'.cod_presi =  '.$var1.'  ';
	}
	if($var2!=null){
		$tabla='arrd02';
		$sql_2 =  $tabla.'.cod_presi =  '.$var1.'  '.' and '.$tabla.'.cod_entidad = '.$var2.'  ';
		$sql_3 =  $tabla.'.cod_presi =  '.$var1.'  ';
	}
	if($var3!=null){
		$tabla='arrd03';
		$sql_2 = $tabla.'.cod_presi =  '.$var1.'  '.' and '.$tabla.'.cod_entidad = '.$var2.'  '.' and '.$tabla.'.cod_tipo_inst = '.$var3.'  ';
		$sql_3 = $tabla.'.cod_presi =  '.$var1.'  '.' and '.$tabla.'.cod_entidad = '.$var2.'  ';
	}
	if($var4!=null){
		$tabla='arrd04';
		$sql_2 = $tabla.'.cod_presi =  '.$var1.'  '.' and '.$tabla.'.cod_entidad = '.$var2.'  '.' and '.$tabla.'.cod_tipo_inst = '.$var3.'  '.' and '.$tabla.'.cod_inst = '.$var4.'  ';
		$sql_3 = $tabla.'.cod_presi =  '.$var1.'  '.' and '.$tabla.'.cod_entidad = '.$var2.'  '.' and '.$tabla.'.cod_tipo_inst = '.$var3.'  ';
	}
	if($var5!=null){
		$tabla='arrd05';
		$sql_2 = $tabla.'.cod_presi =  '.$var1.'  '.' and '.$tabla.'.cod_entidad = '.$var2.'  '.' and '.$tabla.'.cod_tipo_inst = '.$var3.'  '.' and '.$tabla.'.cod_inst = '.$var4.'  '.' and '.$tabla.'.cod_dep = '.$var5.'  ';
		$sql_3 = $tabla.'.cod_presi =  '.$var1.'  '.' and '.$tabla.'.cod_entidad = '.$var2.'  '.' and '.$tabla.'.cod_tipo_inst = '.$var3.'  '.' and '.$tabla.'.cod_inst = '.$var4.'  ';
	}

	$this->set('tabla', $tabla);


if($var1!=null && $action!='otros'){

      $sql_re = $sql_2;
	  $data = $this->$tabla->findAll($sql_re, null, null, null);

	  $this->set('datos_cod_arrp00', $data);

}else if($var1!=null){

	  $sql_re = $sql_3;
	  $data = $this->$tabla->findAll($sql_re, null, null, null);

	  $this->set('datos_cod_arrp00', $data);

}//fin else

 }//FIN FUNCTION









function guardar($tabla=null, $var1=null, $var2=null, $var3=null, $var4=null, $var5=null){

 	$this->layout = "ajax";

    if($var1==null){
    	$this->data['arrp00']['cod_presi'] = $this->data['arrp00']['codigo'];
    	$var1 = $this->data['arrp00']['codigo'];

	}else if($var2==null){
		$this->data['arrp00']['cod_entidad'] =$this->data['arrp00']['codigo'];
		$var2 = $this->data['arrp00']['codigo'];

	} else if($var3==null){
		$this->data['arrp00']['cod_tipo_inst'] = $this->data['arrp00']['codigo'];
		$var3 = $this->data['arrp00']['codigo'];

	}else if($var4==null){
		$this->data['arrp00']['cod_inst'] = $this-> data['arrp00']['codigo'];
		$var4 = $this->data['arrp00']['codigo'];

	}else if($var5==null){
		$this->data['arrp00']['cod_dep'] = $this->data['arrp00']['codigo'];
		$var5 = $this->data['arrp00']['codigo'];

	}


	 $denominacion = $this->data['arrp00']['denominacion'];

	 //CODIGOS DE ARRANQUE DE LA SESION
	 $cod_presi = $this->Session->read('SScodpresi');
	 $cod_entidad = $this->Session->read('SScodentidad');
	 $cod_tipo_inst = $this->Session->read('SScodtipoinst');
	 $cod_inst = $this->Session->read('SScodinst');
	 $cod_dep = $this->Session->read('SScoddep');

    $codigos = "";
	$values = "";

	if($var1!=null){
		        $codigos .= "cod_presi, ";
				$values .=  " '".$var1."',  " ;
				$tabla='arrd01';
	}

	if($var2!=null){
                 $codigos .= "cod_entidad, ";
				$values .=  " '".$var2."',  ";
		        $tabla='arrd02';
	}

	if($var3!=null){
		$codigos .= "cod_tipo_inst, ";
		$values .=  " '".$var3."',  ";  $tabla='arrd03';
	}

	if($var4!=null){
		$codigos .= "cod_inst, ";
		$values .=  " '".$var4."',  ";
		$tabla='arrd04';
	}

	if($var5!=null){
		$codigos .= "cod_dep, ";
		$values .=  " '".$var5."',  ";
		$tabla='arrd05';
	}

	$sql_1 = "INSERT INTO  ".$tabla." VALUES  ($values '$denominacion')  ";

	$sql = $sql_1;

	$this->set('opcion1', $var1);
	$this->set('opcion2', $var2);
	$this->set('opcion3', $var3);
	$this->set('opcion4', $var4);
	$this->set('opcion5', $var5);

	$tabla = '';
	$sql_2 = '';

	if($var1!=null){
		$tabla='arrd01';
		$sql_2 =  $tabla.'.cod_presi =  '.$var1.'  ';
	}
	if($var2!=null){
		$tabla='arrd02';
		$sql_2 =  $tabla.'.cod_presi =  '.$var1.'  '.' and '.$tabla.'.cod_entidad = '.$var2.'  ';
	}
	if($var3!=null){
		$tabla='arrd03';
		$sql_2 = $tabla.'.cod_presi =  '.$var1.'  '.' and '.$tabla.'.cod_entidad = '.$var2.'  '.' and '.$tabla.'.cod_tipo_inst = '.$var3.'  ';
	}
	if($var4!=null){
		$tabla='arrd04';
		$sql_2 = $tabla.'.cod_presi =  '.$var1.'  '.' and '.$tabla.'.cod_entidad = '.$var2.'  '.' and '.$tabla.'.cod_tipo_inst = '.$var3.'  '.' and '.$tabla.'.cod_inst = '.$var4.'  ';
	}
	if($var5!=null){
		$tabla='arrd05';
		$sql_2 = $tabla.'.cod_presi =  '.$var1.'  '.' and '.$tabla.'.cod_entidad = '.$var2.'  '.' and '.$tabla.'.cod_tipo_inst = '.$var3.'  '.' and '.$tabla.'.cod_inst = '.$var4.'  '.' and '.$tabla.'.cod_dep = '.$var5.'  ';
	}

if($tabla!=''){

  if ($this->$tabla->validates($this->data['arrp00'])){

	  if($this->$tabla->findCount($sql_2) == 0){

	   		$this->$tabla->execute($sql);

			$this->set('errorMessage', 'Los Datos Fueron Guardados ');

	   }else{
	   	$this->set('Message_existe', 'Este registro no fue almacenado porque ya existe');
	   }

   }else{}


    $datos = $this->$tabla->findAll($sql_2, null, null, null);

	$this->set('datos_cod_arrp00', $datos);

	$this->set('tabla', $tabla);

 }//fin if tabla



 }//FIN FUNCTION











 function editar($var1=null, $var2=null, $var3=null, $var4=null, $var5=null){

 	$this->layout = "ajax";
	$this->set('opcion1', $var1);
	$this->set('opcion2', $var2);
	$this->set('opcion3', $var3);
	$this->set('opcion4', $var4);
	$this->set('opcion5', $var5);

	$action='';
	$tabla = '';
	$sql_2 = '';


	if($var1!=null){
		$tabla='arrd01';
		$sql_2 =  $tabla.'.cod_presi =  '.$var1.'  ';
	}
	if($var2!=null){
		$tabla='arrd02';
		$sql_2 =  $tabla.'.cod_presi =  '.$var1.'  '.' and '.$tabla.'.cod_entidad = '.$var2.'  ';
	}
	if($var3!=null){
		$tabla='arrd03';
		$sql_2 = $tabla.'.cod_presi =  '.$var1.'  '.' and '.$tabla.'.cod_entidad = '.$var2.'  '.' and '.$tabla.'.cod_tipo_inst = '.$var3.'  ';
	}
	if($var4!=null){
		$tabla='arrd04';
		$sql_2 = $tabla.'.cod_presi =  '.$var1.'  '.' and '.$tabla.'.cod_entidad = '.$var2.'  '.' and '.$tabla.'.cod_tipo_inst = '.$var3.'  '.' and '.$tabla.'.cod_inst = '.$var4.'  ';
	}
	if($var5!=null){
		$tabla='arrd05';
		$sql_2 = $tabla.'.cod_presi =  '.$var1.'  '.' and '.$tabla.'.cod_entidad = '.$var2.'  '.' and '.$tabla.'.cod_tipo_inst = '.$var3.'  '.' and '.$tabla.'.cod_inst = '.$var4.'  '.' and '.$tabla.'.cod_dep = '.$var5.'  ';
	}

	$this->set('tabla', $tabla);

	  $data = $this->$tabla->findAll($sql_2, null, null, null);

	  $this->set('datos_cod_arrp00', $data);



 }












function  guardar_editar($tabla=null, $var1=null, $var2=null, $var3=null, $var4=null, $var5=null, $aux=null){

 	$this->layout = "ajax";

	 $denominacion = $this->data['arrp00']['denominacion'];
	 $cod_presi = $this->Session->read('SScodpresi');
	 $cod_entidad = $this->Session->read('SScodentidad');
	 $cod_tipo_inst = $this->Session->read('SScodtipoinst');
	 $cod_inst = $this->Session->read('SScodinst');
	 $cod_dep = $this->Session->read('SScoddep');
	 $ano = date('Y');


	$sql_1 = 'UPDATE '.$tabla.'   SET  denominacion = \''.$denominacion.'\' WHERE ';

	if($var1!=null){
		$tabla='arrd01';
		$sql_2 =  $tabla.'.cod_presi =  '.$var1.'  ';
	}
	if($var2!=null){
		$tabla='arrd02';
		$sql_2 =  $tabla.'.cod_presi =  '.$var1.'  '.' and '.$tabla.'.cod_entidad = '.$var2.'  ';
	}
	if($var3!=null){
		$tabla='arrd03';
		$sql_2 = $tabla.'.cod_presi =  '.$var1.'  '.' and '.$tabla.'.cod_entidad = '.$var2.'  '.' and '.$tabla.'.cod_tipo_inst = '.$var3.'  ';
	}
	if($var4!=null){
		$tabla='arrd04';
		$sql_2 = $tabla.'.cod_presi =  '.$var1.'  '.' and '.$tabla.'.cod_entidad = '.$var2.'  '.' and '.$tabla.'.cod_tipo_inst = '.$var3.'  '.' and '.$tabla.'.cod_inst = '.$var4.'  ';
	}
	if($var5!=null){
		$tabla='arrd05';
		$sql_2 = $tabla.'.cod_presi =  '.$var1.'  '.' and '.$tabla.'.cod_entidad = '.$var2.'  '.' and '.$tabla.'.cod_tipo_inst = '.$var3.'  '.' and '.$tabla.'.cod_inst = '.$var4.'  '.' and '.$tabla.'.cod_dep = '.$var5.'  ';
	}



	$sql = $sql_1.$sql_2;

    $this->$tabla->execute($sql);

	$this->set('errorMessage', 'Los Datos Fueron Modificados');


	$this->set('opcion1', $var1);
	$this->set('opcion2', $var2);
	$this->set('opcion3', $var3);
	$this->set('opcion4', $var4);
	$this->set('opcion5', $var5);


	$tabla = '';
	$sql_2 = '';



	if($var1!=null){
		$tabla='arrd01';
		$sql_2 =  $tabla.'.cod_presi =  '.$var1.'  ';
	}
	if($var2!=null){
		$tabla='arrd02';
		$sql_2 =  $tabla.'.cod_presi =  '.$var1.'  '.' and '.$tabla.'.cod_entidad = '.$var2.'  ';
	}
	if($var3!=null){
		$tabla='arrd03';
		$sql_2 = $tabla.'.cod_presi =  '.$var1.'  '.' and '.$tabla.'.cod_entidad = '.$var2.'  '.' and '.$tabla.'.cod_tipo_inst = '.$var3.'  ';
	}
	if($var4!=null){
		$tabla='arrd04';
		$sql_2 = $tabla.'.cod_presi =  '.$var1.'  '.' and '.$tabla.'.cod_entidad = '.$var2.'  '.' and '.$tabla.'.cod_tipo_inst = '.$var3.'  '.' and '.$tabla.'.cod_inst = '.$var4.'  ';
	}
	if($var5!=null){
		$tabla='arrd05';
		$sql_2 = $tabla.'.cod_presi =  '.$var1.'  '.' and '.$tabla.'.cod_entidad = '.$var2.'  '.' and '.$tabla.'.cod_tipo_inst = '.$var3.'  '.' and '.$tabla.'.cod_inst = '.$var4.'  '.' and '.$tabla.'.cod_dep = '.$var5.'  ';
	}


	  $data = $this->$tabla->findAll($sql_2, null, null, null);

	  $this->set('datos_cod_arrp00', $data);

	  $this->set('tabla', $tabla);

}//FIN FUNCTION





















function eliminar($var1=null, $var2=null, $var3=null, $var4=null, $var5=null){

 	$this->layout = "ajax";

	 $tabla[1]='arrd01';
	 $tabla[2]='arrd02';
	 $tabla[3]='arrd03';
	 $tabla[4]='arrd04';
	 $tabla[5]='arrd05';

   $n_tabla = 0;
   $sql_2 = '';

	if($var1!=null){
		$sql_2 .=  ' cod_presi =  '.$var1.'  ';
		$n_tabla++;
	}
	if($var2!=null){
		$sql_2 .= ' and cod_entidad = '.$var2.'  ';
		$n_tabla++;
	}
	if($var3!=null){
		$sql_2 .= ' and cod_tipo_inst = '.$var3.'  ';
		$n_tabla++;
	}
	if($var4!=null){
		$sql_2 .= ' and cod_inst = '.$var4.'  ';
		$n_tabla++;
	}
	if($var5!=null){
		$sql_2  .= ' and cod_dep = '.$var5.'  ';
		$n_tabla++;
	}



	for($i=$n_tabla; $i<=5; $i++){

   					$sql_1 = 'DELETE  FROM '.$tabla[$i].'   WHERE ';
					$sql = $sql_1.$sql_2.' ;';

					$this->$tabla[$i]->execute($sql);

	//	}//fin if

	}//fin for


	$this->set('errorMessage', 'Los Datos Fueron Eliminados ');


	$this->set('opcion1', $var1);
	$this->set('opcion2', $var2);
	$this->set('opcion3', $var3);
	$this->set('opcion4', $var4);
	$this->set('opcion5', $var5);



	$tabla = '';
	$sql_2 = '';



	if($var2!=null){
		$sql_2 =  ' cod_presi =  '.$var1.'  ';
		$tabla='arrd01';
	}
	if($var3!=null){
		$sql_2 .= 'and cod_entidad = '.$var2.'  ';
		$tabla='arrd02';
	}
	if($var4!=null){
		$sql_2 .= 'and cod_tipo_inst = '.$var3.'  ';
		$tabla='arrd03';
	}
	if($var5!=null){
		$sql_2 .= 'and cod_inst = '.$var4.'  ';
		$tabla='arrd04';
	}

	  if($sql_2 != ''){
	  	$data = $this->$tabla->findAll($sql_2, null, null, null);
	  	$this->set('datos_cod_arrp00', $data);
$this->set('tabla', $tabla);
	  }




 }//FIN FUNCTION





















function newUser($var1=null, $var2=null, $var3=null, $var4=null, $var5=null){

 	$this->layout = "ajax";

	$this->set('opcion1', $var1);
	$this->set('opcion2', $var2);
	$this->set('opcion3', $var3);
	$this->set('opcion4', $var4);
	$this->set('opcion5', $var5);

	$action='';
	$tabla = '';
	$sql_2 = '';


	if($var1!=null){
		$tabla='arrd01';
		$sql_2 =  $tabla.'.cod_presi =  '.$var1.'  ';
	}
	if($var2!=null){
		$tabla='arrd02';
		$sql_2 =  $tabla.'.cod_presi =  '.$var1.'  '.' and '.$tabla.'.cod_entidad = '.$var2.'  ';
	}
	if($var3!=null){
		$tabla='arrd03';
		$sql_2 = $tabla.'.cod_presi =  '.$var1.'  '.' and '.$tabla.'.cod_entidad = '.$var2.'  '.' and '.$tabla.'.cod_tipo_inst = '.$var3.'  ';
	}
	if($var4!=null){
		$tabla='arrd04';
		$sql_2 = $tabla.'.cod_presi =  '.$var1.'  '.' and '.$tabla.'.cod_entidad = '.$var2.'  '.' and '.$tabla.'.cod_tipo_inst = '.$var3.'  '.' and '.$tabla.'.cod_inst = '.$var4.'  ';
	}
	if($var5!=null){
		$tabla='arrd05';
		$sql_2 = $tabla.'.cod_presi =  '.$var1.'  '.' and '.$tabla.'.cod_entidad = '.$var2.'  '.' and '.$tabla.'.cod_tipo_inst = '.$var3.'  '.' and '.$tabla.'.cod_inst = '.$var4.'  '.' and '.$tabla.'.cod_dep = '.$var5.'  ';
	}

	$this->set('tabla', $tabla);

	  $data = $this->$tabla->findAll($sql_2, null, null, null);
	  $this->set('datos_cod_arrp00', $data);


 }













function guardar_user($tabla=null, $var1=null, $var2=null, $var3=null, $var4=null, $var5=null){

 	$this->layout = "ajax";
 	if($var1==null){
    	$this->data['arrp00']['cod_presi'] = $this->data['arrp00']['codigo'];

	}else if($var2==null){
		$this->data['arrp00']['cod_entidad'] =$this->data['arrp00']['codigo'];
		$var1 = $this->data['arrp00']['codigo'];

	} else if($var3==null){
		$this->data['arrp00']['cod_tipo_inst'] = $this->data['arrp00']['codigo'];
		$var2 = $this->data['arrp00']['codigo'];

	}else if($var4==null){
		$this->data['arrp00']['cod_inst'] = $this-> data['arrp00']['codigo'];
		$var3 = $this->data['arrp00']['codigo'];

	}else if($var5==null){
		$this->data['arrp00']['cod_dep'] = $this->data['arrp00']['codigo'];
		$var4 = $this->data['arrp00']['codigo'];

	}

	 $username = $this->data['arrp00']['username'];
	 $password = $this->data['arrp00']['password'];

    $codigos = "";
	$values = "";

	if($var1!=null){
		        $codigos .= "cod_presi, ";
				$values =  " '".$var1."', '0', '0', '0', '0' " ;
	}

	if($var2!=null){
                 $codigos .= "cod_entidad, ";
				$values =  " '".$var1."', '".$var2."', '0', '0', '0' ";
	}

	if($var3!=null){
		$codigos .= "cod_tipo_inst, ";
		$values =  " '".$var1."', '".$var2."', '".$var3."', '0', '0' ";
	}

	if($var4!=null){
		$codigos .= "cod_inst, ";
		$values =  " '".$var1."', '".$var2."', '".$var3."', '".$var4."', '0' ";
	}

	if($var5!=null){
		$codigos .= "cod_dep, ";
		$values =  " '".$var1."', '".$var2."', '".$var3."', '".$var4."', '".$var5."'  ";
	}

	$sql_1 = "INSERT INTO usuarios VALUES  ('$username','$password',".$values.", '0')  ";

	$sql = $sql_1;

	$this->set('opcion1', $var1);
	$this->set('opcion2', $var2);
	$this->set('opcion3', $var3);
	$this->set('opcion4', $var4);
	$this->set('opcion5', $var5);

	$tabla = '';
	$sql_2 = '';

	if($var1!=null){
		$tabla='arrd01';
		$sql_2 =  $tabla.'.cod_presi =  '.$var1.'  ';
	}
	if($var2!=null){
		$tabla='arrd02';
		$sql_2 =  $tabla.'.cod_presi =  '.$var1.'  '.' and '.$tabla.'.cod_entidad = '.$var2.'  ';
	}
	if($var3!=null){
		$tabla='arrd03';
		$sql_2 = $tabla.'.cod_presi =  '.$var1.'  '.' and '.$tabla.'.cod_entidad = '.$var2.'  '.' and '.$tabla.'.cod_tipo_inst = '.$var3.'  ';
	}
	if($var4!=null){
		$tabla='arrd04';
		$sql_2 = $tabla.'.cod_presi =  '.$var1.'  '.' and '.$tabla.'.cod_entidad = '.$var2.'  '.' and '.$tabla.'.cod_tipo_inst = '.$var3.'  '.' and '.$tabla.'.cod_inst = '.$var4.'  ';
	}
	if($var5!=null){
		$tabla='arrd05';
		$sql_2 = $tabla.'.cod_presi =  '.$var1.'  '.' and '.$tabla.'.cod_entidad = '.$var2.'  '.' and '.$tabla.'.cod_tipo_inst = '.$var3.'  '.' and '.$tabla.'.cod_inst = '.$var4.'  '.' and '.$tabla.'.cod_dep = '.$var5.'  ';
	}

  if ($this->Usuario->validates($this->data['arrp00'])){

  	$cond = "username = '".$this->data['arrp00']['username']."'";

  	if($this->Usuario->findCount($cond) == 0){

	   	$this->$tabla->execute($sql);

		$this->set('errorMessage', 'EL USUARIO FUE CREADO EXITOSAMENTE!!');
  	}else{
  		$this->set('Message_existe', 'LO SIENTO YA EXISTE UN USUARIO EN ESTA ENTIDAD CON ESE LOGIN');
  	}
  }else{
   	$this->set('Message_existe', 'Error en la validacion');
   }



    $datos = $this->$tabla->findAll($sql_2, null, null, null);

	$this->set('datos_cod_arrp00', $datos);

	$this->set('tabla', $tabla);



 }//FIN FUNCTION
}//FIN DEL CONTROLADOR

?>
