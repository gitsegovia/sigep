<?php
class Cnmp10aportespatronalesController extends AppController{

	var $name = 'cnmp10_aportes_patronales';
	var $uses = array('Cnmd01','cnmd01', 'cnmd03_transacciones', 'ccfd03_instalacion', 'v_cnmd05', 'cnmd10_aportes_patronales','cnmd10_control_escenarios');
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



function zero($x=null){
	if($x != null){
		if($x<10){
			$x="0".$x;
		}else if($x>=10 && $x<=99){
			$x=$x;
		}
	}
	return $x;
}//fin zero



function concatena($vector1=null, $nomVar=null){
	if($vector1 != null){
		foreach($vector1 as $x => $y){$cod[$x] = $this->zero($x).' - '.$y;}
		$this->set($nomVar, $cod);
	}//fin if
}//fin concatena







 function index($var=null){
 $this->layout = "ajax";

    $cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep;



if($var != null && $var=='1' || $var == '0'){
		$this->set('opc', $var);
		if($var == 0){
			$this->set('enabled','READONLY');
		}else{
			$this->set('enabled','');
		}
		//echo "gloria a Dios ".$var;
	}else{
		$this->set('opc', '1');
		$this->set('enabled','');
	}

 	$lista = $this->Cnmd01->generateList($conditions = $this->condicion(), $order = 'cod_tipo_nomina', $limit = null, '{n}.Cnmd01.cod_tipo_nomina', '{n}.Cnmd01.denominacion');
	$this->concatenaN($lista, 'nomina');
	$this->set('cod_tipo_nomina', null);
 	//$Lista = $this->v_cnmd05->generateList($condicion, 'cod_tipo_nomina ASC', null, '{n}.v_cnmd05.cod_tipo_nomina', '{n}.v_cnmd05.tipo_nomina');
   	//$this->concatena($Lista, 'cod_tipo_nomina');
   	//$this->set('cod_puesto','');

  // 	$this->set('codigo_nomina', '123');
   	$this->set('entidad_federal', 'aaaaaaaaaaaa');

}///fin function


function select3($select=null,$var=null,$var2=null,$var3=null) { //select codigos presupuestarios
	$this->layout = "ajax";
	$cod_dep = $this->Session->read('SScoddep');

	//echo "<br>Select: ".$select."<br>";
	//echo "<br>Var: ".$var."<br>";
	//echo "<br>Var2: ".$var2."<br>";
	//echo "<br>Var3: ".$var3."<br>";

	if($var!=null){
	switch($select){
		case 'nomina':
		  $this->set('SELECT','coordinacion');
		  $this->set('codigo','secretaria');
		  $this->set('seleccion','');
		  $this->set('n',1);
		break;
		case 'trabajador':
		  $this->set('SELECT','patronal');//El parametro que se le pasa para que busque el proximo select (cuando entre en select3)
		  $this->set('codigo','trabajador');//El nombre que se le asigna al select actual cuando se crea
		  $this->set('seleccion','');
		  $this->set('n',2);
		  $this->set('no','');
		  $this->set('codigo_nomina',$var);
		  $this->set('codigo_transaccion',$var2);
		  $this->set('codigo_transaccion2',$var3);
		  //BLOQUEO FUSAMIEBG
		  if($cod_dep!=1){
		  $cond ="cod_tipo_transaccion=2 and cod_transaccion!=103";
		}else{
			$cond ="cod_tipo_transaccion=2";
		}
		  $lista = $this->cnmd03_transacciones->generateList2($cond, 'cod_tipo_transaccion, cod_transaccion ASC', null, '{n}.cnmd03_transacciones.cod_transaccion', '{n}.cnmd03_transacciones.denominacion');
   		  $this->concatenaN($lista, 'vector');
		break;
		case 'patronal':
		  $this->set('SELECT','direccion');
		  $this->set('codigo','patronal');
		  $this->set('seleccion','');
		  $this->set('n',3);
		  $this->set('no','no');
		  $this->set('codigo_nomina',$var);
		  $this->set('valor',$var);
		  $this->set('codigo_transaccion',$var2);
		  $this->set('codigo_transaccion2',$var3);
		  $cond ="cod_tipo_transaccion=2 and cod_transaccion_padre=".$var;
		  $lista = $this->cnmd03_transacciones->findAll($cond);
		  $this->set('valor',$var);
		  if($lista == 0){
		  	    $this->set('valor',$var);
	    		$this->set('vector',array('agregar'=>'agregar'));
		  }else{
                $this->set('vector',$lista);
		  }
          //$this->concatenaN($lista, 'vector');

		break;
	}//fin switch
	}else{
		  $this->set('SELECT','');
		  $this->set('codigo','');
		  $this->set('seleccion','');
		  $this->set('n',10);
		  $this->set('no','no');
		  $this->set('vector','');
		  $this->set('tipo',$select);
	}
}//fin select codigos presupuestarios


function mostrar_codigo($select=null,$var=null,$var2=null,$var3=null){
	$this->layout = "ajax";

	//echo "<br>Select: ".$select."<br>";
	//echo "<br>Var: ".$var."<br>";
	//echo "<br>Var2: ".$var2."<br>";
	//echo "<br>Var3: ".$var3."<br>";

    if($var!=null){
	$this->set('tipo',$select);
	switch($select){
		case 'nomina':
    	  $cond = $this->SQLCA().' and cod_tipo_nomina='.$var;
    	  //$cond='cod_tipo_nomina='.$var;
		  $a=  $this->Cnmd01->findAll($cond);
		  //echo $var;
          echo "<input type='text' name=data[][] id='' value=".$this->mascara_tres($a[0]['Cnmd01']['cod_tipo_nomina'])." readonly='readonly' class='inputtext' style='text-align:center' />";//strtoupper($a[0]['v_cnmd05']['cod_tipo_nomina']);
		  break;
		case 'trabajador':
		  if($var2==''){
		      echo "<input type='text' name=data[][] id='cod_trans' value='' readonly='readonly' class='inputtext' style='text-align:center' />";//strtoupper($a[0]['cnmd03_transacciones']['cod_transaccion']);
		  }else{
		  $cond = "cod_tipo_transaccion=2 and cod_transaccion=".$var2;
		  $a=  $this->cnmd03_transacciones->findAll($cond);
		  $this->set('ver',$a);
          echo "<input type='text' name=data[][] id='cod_trans' value=".$this->mascara_tres($a[0]['cnmd03_transacciones']['cod_transaccion'])." readonly='readonly' class='inputtext' style='text-align:center' />";//strtoupper($a[0]['cnmd03_transacciones']['cod_transaccion']);
		  }
		  break;
		case 'patronal':
		  if($var2=='agregar'){
		      echo "<input type='text' name=data[][] id=''  class='inputtext' />";
		  }else{
		  $cond = "cod_tipo_transaccion=2 and cod_transaccion=".$var2;
		  $a=  $this->cnmd03_transacciones->findAll($cond);
          echo "<input type='text' name=data[][] id='' value=".$this->mascara_tres($a[0]['cnmd03_transacciones']['cod_transaccion'])." readonly='readonly' class='inputtext' style='text-align:center' />";//strtoupper($a[0]['cnmd03_transacciones']['cod_transaccion']);
		  }
		  break;
	  }//fin switch
	}else{
		echo "<input type='text' name=data[][] id='' value='' readonly='readonly' class='inputtext' style='text-align:center' />";//strtoupper($a[0]['cnmd03_transacciones']['cod_transaccion']);
	}
}

function mostrar_deno($select=null,$var=null,$var2=null,$var3=null){
	$this->layout = "ajax";

	//echo "<br>Select: ".$select."<br>";
	//echo "<br>Var: ".$var."<br>";
	//echo "<br>Var2: ".$var2."<br>";
	//echo "<br>Var3: ".$var3."<br>";

	if($var!=null && !empty($var)){
	switch($select){
		case 'nomina':
		  $cond = $this->SQLCA().' and cod_tipo_nomina='.$var;
		  $a=  $this->Cnmd01->findAll($cond);
		  //pr($a);
          echo "<input type='text' name=data[][] id='' value='".$a[0]['Cnmd01']['denominacion']."' readonly='readonly' maxlength='100' class='inputtext' />";//strtoupper($a[0]['v_cnmd05']['tipo_nomina']);
		  break;

		case 'trabajador':

		  if($var2==''){
		      echo "<input type='text' name=data[][] id='deno_trans'  value='' readonly='readonly' maxlength='100' class='inputtext' />";//strtoupper($a[0]['cnmd03_transacciones']['denominacion']);
		  }else{
		  	$this->set('tipo','trabajador');
		  	$sql=$this->cnmd03_transacciones->execute("select * from cnmd03_transacciones where cod_tipo_transaccion=2 and cod_transaccion_padre=".$var2);
		  	if($sql!=null){
		  		$cod_transaccion=$sql[0][0]['cod_transaccion'];
			  	$ubicacion = $this->cnmd10_control_escenarios->field('cnmd10_control_escenarios.ubicacion_escenario', $conditions = $this->condicion()." and cod_tipo_nomina='$var' and cod_tipo_transaccion=2 and cod_transaccion='$cod_transaccion'", $order =null);
				if($ubicacion!=null){
					$this->set('ubicacion', $ubicacion);
				}else{
					$this->set('ubicacion', null);
				}
		  	}else{
		  		$this->set('ubicacion', null);
		  	}
		  $datos=$this->cnmd10_aportes_patronales->FindCount($this->SQLCA()." and cod_tipo_nomina='$var' and cod_tipo_transaccion=2 and cod_transaccion='$var2'");
		  $datos2=$this->cnmd10_aportes_patronales->FindAll($this->SQLCA()." and cod_tipo_nomina='$var' and cod_tipo_transaccion=2 and cod_transaccion='$var2'");
		  $this->set('datos',$datos);
		  $this->set('datos2',$datos2);
		  $cond = "cod_tipo_transaccion=2 and cod_transaccion=".$var2;
		  //echo $cond;
		  $a=  $this->cnmd03_transacciones->findAll($cond);
          echo "<input type='text' name=data[][] id='deno_trans'  value='".$a[0]['cnmd03_transacciones']['denominacion']."' readonly='readonly' maxlength='100' class='inputtext' />";//strtoupper($a[0]['cnmd03_transacciones']['denominacion']);
		  }
		  break;

		case 'patronal':

		  if($var2=='agregar'){
		      echo "<input type='text' name=data[][] id=''  class='inputtext' />";
		  }else{

		  $cond = "cod_tipo_transaccion=2 and cod_transaccion=".$var2;
		  $a=  $this->cnmd03_transacciones->findAll($cond);
          echo "<input type='text' name=data[][] id='' value=".$a[0]['cnmd03_transacciones']['denominacion']." readonly='readonly' class='inputtext' />";//strtoupper($a[0]['cnmd03_transacciones']['denominacion']);
		  }
		  break;
	}//fin switch
	}else{
		echo "<input type='text' name=data[][] id='' value='' readonly='readonly' class='inputtext' />";//strtoupper($a[0]['cnmd03_transacciones']['denominacion']);
	}
}//fin mostrar3 denominaciones



function mostrar_input($campo=null){
	$this->layout="ajax";
	if(isset($campo)){
		$this->set('campo',$campo);
	}
}




function transaccion($var=null){
	$this->layout="ajax";
	if($var!='guarda'){
		$nomina=$var;
	}else{
		 $nomina = $this->data['cnmp10_aportes_patronales']['cod_nomina'];
	}
	$datos=$this->cnmd10_aportes_patronales->findAll($this->SQLCA()." and cod_tipo_transaccion=2 and cod_tipo_nomina=".$nomina,null,null);
			 if($datos){
			 	 $this->set('opciones',$datos);
			 }else{
			 	 $this->set('opciones','');
			 }
	 $deno_trans= $this->cnmd03_transacciones->findAll($conditions = 'cod_tipo_transaccion=2', $fields ='cod_transaccion, denominacion', $order = null, $limit = null, $page = null, $recursive = null);
	 $this->set('deno_trans', $deno_trans);
	 $this->render('transaccion');
}//fin transaccion





function guardar(){
	$this->layout="ajax";
	$cod_presi = $this->Session->read('SScodpresi');
    $cod_entidad = $this->Session->read('SScodentidad');
    $cod_tipo_inst = $this->Session->read('SScodtipoinst');
    $cod_inst = $this->Session->read('SScodinst');
    $cod_dep = $this->Session->read('SScoddep');

	if(!empty($this->data['cnmp10_aportes_patronales'])){
		/*echo "<br>Codigo Nomina: ".$cod_nomina=$this->data['cnmp10_aportes_patronales']['cod_nomina'];
		echo "<br>Codigo Transa Trabajador: ".$cod_transa_trabajador=$this->data['cnmp10_aportes_patronales']['cod_trabajador'];
		echo "<br>Codigo Transa Patrono: ".$cod_transa_patrono=$this->data['cnmp10_aportes_patronales']['cod_patronal'];
		echo "<br>Porcentaje: ".$porcentaje_patrono=$this->data['cnmp10_aportes_patronales']['porcentaje_patronal'];
		echo "<br>Tope Cuarta: ".$tope_cuarta_semana=$this->data['cnmp10_aportes_patronales']['cuarta_semana_patronal'];
		echo "<br>Tope Quinta: ".$tope_quinta_semana=$this->data['cnmp10_aportes_patronales']['quinta_semana_patronal'];*/

		$cod_nomina=$this->data['cnmp10_aportes_patronales']['cod_nomina'];
		$cod_transa_trabajador=$this->data['cnmp10_aportes_patronales']['cod_trabajador'];
		$cod_transa_patrono=$this->data['cnmp10_aportes_patronales']['cod_patronal'];
		$porcentaje_patrono=$this->formato1($this->data['cnmp10_aportes_patronales']['porcentaje_patronal']);
		$tope_cuarta_semana=$this->formato1($this->data['cnmp10_aportes_patronales']['cuarta_semana_patronal']);
		$tope_quinta_semana=$this->formato1($this->data['cnmp10_aportes_patronales']['quinta_semana_patronal']);
		$ubicacion_escenario = strtoupper('DEDUCCIONES APORTES PATRONALES - EN PORCENTAJE CALCULADO SEGÚN EL SUELDO');
		$ver = $this->cnmd10_aportes_patronales->execute("select * from cnmd10_control_de_escenarios where ".$this->SQLCA()." and cod_tipo_nomina='$cod_nomina' and cod_tipo_transaccion='2' and cod_transaccion='$cod_transa_patrono'");
		if($ver==null){
			//Buscamos a ver si la informacion ya se encuentra registrada, sino lo esta procedemos a insertar los datos
			$consulta=$this->SQLCA()." and cod_tipo_nomina=".$cod_nomina." and cod_tipo_transaccion=2 and cod_transaccion=".$cod_transa_trabajador;
			if($this->cnmd10_aportes_patronales->findAll($consulta)){
				$this->set('mensajeError','Atencion, esos c&oacute;digos ya se encuentran registrados');
					//$this->index();
			        //$this->render("index");
			}else{
				$sql="INSERT INTO cnmd10_aportes_patronales VALUES ('".$this->verifica_SS(1)."','".$this->verifica_SS(2)."','".$this->verifica_SS(3)."','".$this->verifica_SS(4)."','".$this->verifica_SS(5)."',".
				"'$cod_nomina','2','$cod_transa_trabajador','2','$cod_transa_patrono','$porcentaje_patrono','$tope_cuarta_semana','$tope_quinta_semana')";
			    if($this->cnmd10_aportes_patronales->execute($sql)>1){
			    	$sql_control = "INSERT INTO cnmd10_control_de_escenarios VALUES('$cod_presi', '$cod_entidad', '$cod_tipo_inst', '$cod_inst', '$cod_dep', '$cod_nomina', '2', '$cod_transa_patrono', '$ubicacion_escenario')";
					$sw = $this->cnmd10_aportes_patronales->execute($sql_control);
			        $this->set('mensaje','Los datos fueron registrados correctamente');
			        $this->data['cnmp10_aportes_patronales']=null;
			        //$this->index();
			        //$this->render("index");
				}else{
			   		$this->set('mensajeError','Los datos no fueron guardados');
				}
		}

		}else{
			$this->set('mensaje','ESTA TRANSACCIÓN YA FUE CREADA EN EL ESCENARIO '.$ver[0][0]['ubicacion_escenario']);
		}

	}else{
		$this->set('mensajeError','Lo siento, los datos no llegaron correctamente');
	}
}//guardar



function consultar($pagina=null, $mensaje=null) {
	$this->layout="ajax";
	$condicion_SQLA = $this->SQLCA();
	if(isset($pagina)){
		$Tfilas=$this->cnmd10_aportes_patronales->findCount($condicion_SQLA);
        if($Tfilas!=0){
        	$data=$this->cnmd10_aportes_patronales->findAll($condicion_SQLA,null,"cod_tipo_nomina, cod_tipo_transaccion, cod_transaccion ASC",1,$pagina,null);

            $this->set('DATA',$data);
            $this->set('pagina',$pagina);
          	$this->set('siguiente',$pagina+1);
          	$this->set('anterior',$pagina-1);
            $this->bt_nav($Tfilas,$pagina);
            $this->set('numT',$Tfilas);
			$this->set('numP',$pagina);
        }else{
	 	       $this->set('mensajeError', 'No se encontrar&oacute;n datos');
	 	       $this->set('noExiste',true);
	 	       $this->index();
			   $this->render("index");
			   return;
        }

	}else{
		$pagina=1;
		$Tfilas=$this->cnmd10_aportes_patronales->findCount($condicion_SQLA);

        if($Tfilas!=0){
        	$data=$this->cnmd10_aportes_patronales->findAll($condicion_SQLA,null,"cod_tipo_nomina, cod_tipo_transaccion, cod_transaccion ASC",1,$pagina,null);
			$this->set('DATA',$data);
			$this->set('pagina',$pagina);
          	$this->set('siguiente',$pagina+1);
          	$this->set('anterior',$pagina-1);
          	$this->bt_nav($Tfilas,$pagina);
          	$this->set('numT',$Tfilas);
			$this->set('numP',$pagina);

        }else{
	 	       $this->set('mensajeError', 'No se encontrar&oacute;n datos');
	 	       $this->set('noExiste',true);
	 	       $this->index();
			   $this->render("index");
			   return;
        }
	}

	 $this->set('cod_tipo_nomina', $this->mascara_tres($data[0]['cnmd10_aportes_patronales']['cod_tipo_nomina']));
	    $this->set('cod_transaccion', $this->mascara_tres($data[0]['cnmd10_aportes_patronales']['cod_transaccion']));
	    $this->set('cod_transa_patrono', $this->mascara_tres($data[0]['cnmd10_aportes_patronales']['cod_transa_patrono']));
	    $this->set('porcentaje_patrono', $data[0]['cnmd10_aportes_patronales']['porcentaje_patrono']);
	    $this->set('tope_cuarta_semana', $data[0]['cnmd10_aportes_patronales']['tope_cuarta_semana']);
	    $this->set('tope_quinta_semana', $data[0]['cnmd10_aportes_patronales']['tope_quinta_semana']);

	    //Buscando las denominaciones del tipo de nomina y el tipo de transaccion
	    $dataR1=$this->v_cnmd05->findAll($this->SQLCA().' and cod_tipo_nomina='.$data[0]['cnmd10_aportes_patronales']['cod_tipo_nomina']);
	    $this->set('deno_nomina', $dataR1[0]['v_cnmd05']['tipo_nomina']);

	    $dataR2=$this->cnmd03_transacciones->findAll('cod_tipo_transaccion=2 and cod_transaccion='.$data[0]['cnmd10_aportes_patronales']['cod_transaccion']);
	    $this->set('deno_transa_trabajador', $dataR2[0]['cnmd03_transacciones']['denominacion']);

	    $dataR3=$this->cnmd03_transacciones->findAll('cod_tipo_transaccion=2 and cod_transaccion='.$data[0]['cnmd10_aportes_patronales']['cod_transa_patrono']);
	    $this->set('deno_transa_patrono', $dataR3[0]['cnmd03_transacciones']['denominacion']);

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


function eliminar($nomina=null, $transacion=null, $transa_patrono=null,$anterior=null){
	$this->layout="ajax";

	$sql="DELETE FROM cnmd10_aportes_patronales WHERE ".$this->SQLCA()." and cod_tipo_nomina=".$nomina." and cod_tipo_transaccion=2 and cod_transaccion=".$transacion." and cod_tipo_transa_patrono=2 and cod_transa_patrono=".$transa_patrono;
	if($this->cnmd10_aportes_patronales->execute($sql)>1){
		$sql_del_control = "DELETE FROM cnmd10_control_de_escenarios WHERE ".$this->condicion()." and cod_tipo_nomina='$nomina' and cod_tipo_transaccion=2 and cod_transaccion='$transa_patrono'";
		$sw = $this->cnmd10_aportes_patronales->execute($sql_del_control);
		$this->set('mensaje','El registro fu&eacute; eliminado correctamente');

	}else{
		$this->set('mensajeError','Error, el registro no pudo ser eliminado');
	}

	if($this->cnmd10_aportes_patronales->FindCount($this->SQLCA())!=0){
		$this->consultar($anterior);
		$this->render("consultar");
	}else{
		$this->index();
		$this->render("index");
	}

}


function eliminar_index(){
	$this->layout="ajax";
	$nomina=$this->data['cnmp10_aportes_patronales']['cod_nomina'];
	$transacion=$this->data['cnmp10_aportes_patronales']['cod_trabajador'];
	$patrono=$this->data['cnmp10_aportes_patronales']['cod_patronal'];

	$sql="DELETE FROM cnmd10_aportes_patronales WHERE ".$this->SQLCA()." and cod_tipo_nomina=".$nomina." and cod_tipo_transaccion=2 and cod_transaccion=".$transacion;
	if($this->cnmd10_aportes_patronales->execute($sql)>1){
		$sql_del_control = "DELETE FROM cnmd10_control_de_escenarios WHERE ".$this->condicion()." and cod_tipo_nomina='$nomina' and cod_tipo_transaccion=2 and cod_transaccion='$patrono'";
		$sw = $this->cnmd10_aportes_patronales->execute($sql_del_control);
		$this->set('mensaje','El registro fu&eacute; eliminado correctamente');

	}else{
		$this->set('mensajeError','Error, el registro no pudo ser eliminado');
	}
	$this->index();
	$this->render("index");
}


function modificar_index($var=null){
	$this->layout="ajax";
	$this->set('tipo',$var);
	if($var==1){
		$this->set('mensaje','proceda a modificar los datos');
	}
}

function guardar_modificar_index($var=null){
	$this->layout="ajax";
	if(!empty($this->data['cnmp10_aportes_patronales'])){
		$nomina=$this->data['cnmp10_aportes_patronales']['cod_nomina'];
		$transacion=$this->data['cnmp10_aportes_patronales']['cod_trabajador'];
		$patrono=$this->data['cnmp10_aportes_patronales']['cod_patronal'];
		$porcentaje_patrono=$this->formato1($this->data['cnmp10_aportes_patronales']['porcentaje_patronal']);
		$tope_cuarta_semana=$this->formato1($this->data['cnmp10_aportes_patronales']['cuarta_semana_patronal']);
		$tope_quinta_semana=$this->formato1($this->data['cnmp10_aportes_patronales']['quinta_semana_patronal']);

		$sql_update="UPDATE cnmd10_aportes_patronales SET porcentaje_patrono='$porcentaje_patrono', tope_cuarta_semana='$tope_cuarta_semana', tope_quinta_semana='$tope_quinta_semana' WHERE ".$this->SQLCA()." and cod_tipo_nomina='$nomina'".
					" and cod_tipo_transaccion=2 and cod_transaccion='$transacion' and cod_tipo_transa_patrono=2 and cod_transa_patrono='$patrono'";
		if($this->cnmd10_aportes_patronales->execute($sql_update)>0){
			$this->set('mensaje','Los datos fuer&oacute;n modificados correctamente');
		}else{
			$this->set('mensajeError','Error, la modificaci&oacute;n no pudo ser realizada');
		}

	}else{
		$this->set('mensajeError','Error, los datos no llegaron correctamente');
	}

}

function modificar($nomina=null, $transacion=null, $transa_patrono=null,$pagina=null){
	$this->layout="ajax";
	$condicion_SQLA = $this->SQLCA();
	$condicion_SQLA .= " and cod_tipo_nomina=".$nomina." and cod_tipo_transaccion=2 and cod_transaccion=".$transacion." and cod_tipo_transa_patrono=2 and cod_transa_patrono=".$transa_patrono;
	$data=$this->cnmd10_aportes_patronales->findAll($condicion_SQLA,null,"cod_tipo_nomina, cod_tipo_transaccion, cod_transaccion ASC",1,null,null);

	$this->set('cod_tipo_nomina', $this->mascara_tres($data[0]['cnmd10_aportes_patronales']['cod_tipo_nomina']));
	$this->set('cod_transaccion', $this->mascara_tres($data[0]['cnmd10_aportes_patronales']['cod_transaccion']));
	$this->set('cod_transa_patrono', $this->mascara_tres($data[0]['cnmd10_aportes_patronales']['cod_transa_patrono']));
	$this->set('porcentaje_patrono', $data[0]['cnmd10_aportes_patronales']['porcentaje_patrono']);
	$this->set('tope_cuarta_semana', $data[0]['cnmd10_aportes_patronales']['tope_cuarta_semana']);
	$this->set('tope_quinta_semana', $data[0]['cnmd10_aportes_patronales']['tope_quinta_semana']);

	//Buscando las denominaciones del tipo de nomina y el tipo de transaccion
	$dataR1=$this->v_cnmd05->findAll('cod_tipo_nomina='.$data[0]['cnmd10_aportes_patronales']['cod_tipo_nomina']);
	$this->set('deno_nomina', $dataR1[0]['v_cnmd05']['tipo_nomina']);

	$dataR2=$this->cnmd03_transacciones->findAll('cod_tipo_transaccion=2 and cod_transaccion='.$data[0]['cnmd10_aportes_patronales']['cod_transaccion']);
	$this->set('deno_transa_trabajador', $dataR2[0]['cnmd03_transacciones']['denominacion']);

	$dataR3=$this->cnmd03_transacciones->findAll('cod_tipo_transaccion=2 and cod_transaccion='.$data[0]['cnmd10_aportes_patronales']['cod_transa_patrono']);
	$this->set('deno_transa_patrono', $dataR3[0]['cnmd03_transacciones']['denominacion']);

	$this->set('anterior',$pagina+1);
}//Modificar


function guardar_modificar($nomina=null, $transacion=null, $cod_transa_patrono=null,$pagina=null){
	$this->layout="ajax";

	if(!empty($this->data['cnmp10_aportes_patronales'])){
		$porcentaje_patrono=$this->formato1($this->data['cnmp10_aportes_patronales']['porcentaje_patrono']);
		$tope_cuarta_semana=$this->formato1($this->data['cnmp10_aportes_patronales']['tope_cuarta_semana']);
		$tope_quinta_semana=$this->formato1($this->data['cnmp10_aportes_patronales']['tope_quinta_semana']);

		$sql_update="UPDATE cnmd10_aportes_patronales SET porcentaje_patrono='$porcentaje_patrono', tope_cuarta_semana='$tope_cuarta_semana', tope_quinta_semana='$tope_quinta_semana' WHERE ".$this->SQLCA()." and cod_tipo_nomina='$nomina'".
					" and cod_tipo_transaccion=2 and cod_transaccion='$transacion' and cod_tipo_transa_patrono=2 and cod_transa_patrono='$cod_transa_patrono'";
		if($this->cnmd10_aportes_patronales->execute($sql_update)>0){
			$this->set('mensaje','Los datos fuer&oacute;n modificados correctamente');
			$this->consultar($pagina);
			$this->render("consultar");
		}else{
			$this->set('mensajeError','Error, la modificaci&oacute;n no pudo ser realizada');
			$this->consultar($pagina);
			$this->render("consultar");
		}

	}else{
		$this->set('mensajeError','Error, los datos no llegaron correctamente');
	}
}




function transferir($var1=null){
	$this->layout="ajax";
		$carga=$this->cnmd10_aportes_patronales->findAll($this->condicion()." and cod_tipo_nomina=".$var1." and cod_tipo_transaccion=2");
		if($carga){
			$lista2 = $this->Cnmd01->generateList($conditions = $this->condicion()." and cod_tipo_nomina!=".$var1, $order = 'cod_tipo_nomina', $limit = null, '{n}.Cnmd01.cod_tipo_nomina', '{n}.Cnmd01.denominacion');
			if($lista2){
			$this->concatenaN($lista2, 'transferir');
			$this->set('cod_nomina', $var1);
			}else{
				$this->set('transferir',array());
			}
		}else{
			$this->set('nada',"");
		}
}//fin transferir


function cod_transferir($cod_nomina=null){
	$this->layout="ajax";
	if($cod_nomina!=null){
		$this->set('cod_trans', $cod_nomina);
	}
}//fin cod_transferencia


function deno_transferir($cod_nomina=null){
	$this->layout="ajax";
	if($cod_nomina!=null){
		$deno_nomina = $this->Cnmd01->field('denominacion', $conditions = $this->condicion()." and Cnmd01.cod_tipo_nomina='$cod_nomina'", $order ="cod_tipo_nomina ASC");
		$this->set('deno_nomina', $deno_nomina);
		echo "<script>";
			echo "document.getElementById('save_transferir').disabled=false;";
		echo "</script>";
	}
}//fin deno_transferencia




function guardar_transferir(){
	$this->layout="ajax";
	  $cod_presi = $this->Session->read('SScodpresi');
	  $cod_entidad = $this->Session->read('SScodentidad');
	  $cod_tipo_inst = $this->Session->read('SScodtipoinst');
	  $cod_inst = $this->Session->read('SScodinst');
	  $cod_dep = $this->Session->read('SScoddep');
	  $cod_tipo_nomina = $this->data['cnmp10_aportes_patronales']['cod_nomina'];
	  $cod_transferir = $this->data['cnmp10_aportes_patronales']['cod_transferir'];
	  $cod_tipo_transaccion=2;
	  $s=0;
	  $bandera=0;
	  $bandera=0;
	 $data=$this->cnmd10_aportes_patronales->findAll($this->condicion()." and cod_tipo_nomina=".$cod_tipo_nomina." and cod_tipo_transaccion=2");
	 $ubicacion_escenario = strtoupper('DEDUCCIONES APORTES PATRONALES - EN PORCENTAJE CALCULADO SEGÚN EL SUELDO');
	  foreach($data as $row){
			$cod_transaccion1 = $row['cnmd10_aportes_patronales']['cod_transaccion'];
			$cod_tipo_transa_patrono = $row['cnmd10_aportes_patronales']['cod_tipo_transa_patrono'];
			$cod_transa_patrono = $row['cnmd10_aportes_patronales']['cod_transa_patrono'];
			$porcentaje_patrono = $row['cnmd10_aportes_patronales']['porcentaje_patrono'];
			$tope_cuarta_semana = $row['cnmd10_aportes_patronales']['tope_cuarta_semana'];
			$tope_quinta_semana = $row['cnmd10_aportes_patronales']['tope_quinta_semana'];
			 $datos=$this->cnmd10_control_escenarios->findAll($this->condicion()." and cod_tipo_nomina=".$cod_transferir." and cod_tipo_transaccion=2 and cod_transaccion=".$cod_transa_patrono);
			if(!$datos){
				$sql_insert = "INSERT INTO cnmd10_aportes_patronales VALUES('$cod_presi', '$cod_entidad', '$cod_tipo_inst', '$cod_inst', '$cod_dep', '$cod_transferir', '$cod_tipo_transaccion', '$cod_transaccion1','$cod_tipo_transa_patrono','$cod_transa_patrono', '$porcentaje_patrono', '$tope_cuarta_semana', '$tope_quinta_semana')";
				$sw = $this->cnmd10_aportes_patronales->execute($sql_insert);
				if($sw>1){
				echo "<script>";
					echo "document.getElementById('save_transferir').disabled='disabled';";
					echo "document.getElementById('select_transferir').options[0].text='';";
					echo "document.getElementById('cod_transferencia').value='';";
					echo "document.getElementById('deno_transferencia').value='';";
				echo "</script>";
				$sql_control = "INSERT INTO cnmd10_control_de_escenarios VALUES('$cod_presi', '$cod_entidad', '$cod_tipo_inst', '$cod_inst', '$cod_dep', '$cod_transferir', '$cod_tipo_transaccion', '$cod_transa_patrono', '$ubicacion_escenario')";
				$sw1 = $this->cnmd10_control_escenarios->execute($sql_control);
				$s=2;
			}//fin $sw
		}else{
			$bandera=2;
		}//fin $datos
			$sw=0;
	  }//fin foreach data

	if($bandera>1 && $s>1){
	  	$this->set('Message_existe', 'Transferencia realizada con exito');
	}else if($bandera==0 && $s>1){
		$this->set('Message_existe', 'Transferencia realizada con exito');
	}else if($bandera>1 && $s==0){
		$this->set('Message_error', 'Transferencia sin exito');
	}

$sql='';
			 if($data){
			 	foreach($data as $x){
			 		$transaccion=$x['cnmd10_aportes_patronales']['cod_transaccion'];
			 		if($sql==''){
			 			$sql.=$transaccion;
			 		}else{
			 			$sql.=",".$transaccion;
			 		}
			 	}
				$query="SELECT distinct a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep,a.cod_tipo_nomina FROM cnmd10_aportes_patronales a where a.cod_presi='$cod_presi' and a.cod_entidad='$cod_entidad' and a.cod_tipo_inst='$cod_tipo_inst' and a.cod_inst='$cod_inst' and a.cod_dep='$cod_dep' and a.cod_tipo_transaccion=2 and cod_tipo_nomina!=".$cod_tipo_nomina." and a.cod_transaccion IN(".$sql.")";
				$opciones=$this->cnmd10_aportes_patronales->execute($query);
				if($opciones){
					$this->set('opciones',$opciones);
				}else{
					 $this->set('opciones','');
				}
			 }else{
			 	 $this->set('opciones','');
			 }
	 $deno_trans= $this->Cnmd01->findAll($this->condicion(), $fields ='cod_tipo_nomina, denominacion', $order = null, $limit = null, $page = null, $recursive = null);
	 $this->set('deno_trans', $deno_trans);

}// fin guardar_transferir




function otras_nominas($var1=null,$var2=null){
	$this->layout="ajax";
	$cod_presi                =       $this->Session->read('SScodpresi');
	$cod_entidad              =       $this->Session->read('SScodentidad');
	$cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
	$cod_inst                 =       $this->Session->read('SScodinst');
	$cod_dep                  =       $this->Session->read('SScoddep');
	$sql='';
	$datos=$this->cnmd10_aportes_patronales->findAll($this->condicion()." and cod_tipo_transaccion=2 and cod_tipo_nomina=".$var1,null,null);
			 if($datos){
			 	foreach($datos as $x){
			 		$transaccion=$x['cnmd10_aportes_patronales']['cod_transaccion'];
			 		if($sql==''){
			 			$sql.=$transaccion;
			 		}else{
			 			$sql.=",".$transaccion;
			 		}
			 	}
				$query="SELECT distinct a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep,a.cod_tipo_nomina FROM cnmd10_aportes_patronales a where a.cod_presi='$cod_presi' and a.cod_entidad='$cod_entidad' and a.cod_tipo_inst='$cod_tipo_inst' and a.cod_inst='$cod_inst' and a.cod_dep='$cod_dep' and a.cod_tipo_transaccion=2 and cod_tipo_nomina!=".$var1." and a.cod_transaccion IN(".$sql.")";
				$opciones=$this->cnmd10_aportes_patronales->execute($query);
				if($opciones){
					$this->set('opciones',$opciones);
				}else{
					 $this->set('opciones','');
				}
			 }else{
			 	 $this->set('opciones','');
			 }
	 $deno_trans= $this->Cnmd01->findAll($this->condicion(), $fields ='cod_tipo_nomina, denominacion', $order = null, $limit = null, $page = null, $recursive = null);
	 $this->set('deno_trans', $deno_trans);
 $this->render('otras_nominas');
}//fin otras nominas



}//fin class
?>