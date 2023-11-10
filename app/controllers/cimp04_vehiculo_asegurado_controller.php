<?php
/*
 * Creado el  30/10/2007 a las 12:03:17 PM
 *
 * Herramienta: EasyEclipse.
 *
 * Proyecto: SIGEP
 *
 */
 class Cimp04VehiculoAseguradoController extends AppController {
   var $name = 'cimp04_vehiculo_asegurado';
   var $uses = array('cimd04_vehiculo_asegurado','v_cimd04_vehiculo_asegurado_todo','v_inventario_muebles_todo','v_buscar_muebles','cugd02_division','cimd03_inventario_muebles','cimd02_tipo_movimiento','cimd01_clasificacion_tipo','cimd01_clasificacion_seccion','cimd01_clasificacion_subgrupo','cimd01_clasificacion_grupo','cugd01_estados','cugd01_municipios','cugd01_parroquias','Cugd01_centropoblados','cugd02_dependencia','cugd02_coordinacion','cugd02_departamento','cugd02_direccion','cugd02_direccionsuperior','cugd02_division','cugd02_oficina','cugd02_institucion','cugd02_secretaria','cugd01_centropoblados');
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
 }

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

		foreach($vector1 as $x => $y){

			$cod[$x] = $this->zero($x).' - '.$y;
		}
		//print_r($cod);

		$this->set($nomVar, $cod);

	}
}//fin concatena


    function AddCeroR($n,$extra=null){
   	  if($n!=null){
   	  	  if($extra==null){
        	if($n<10){
        	   $Var="0".$n;
        	}else{
	           $Var=$n;
        	}
   	  }else{
        	if($n<10){
        	   $Var=$extra.".0".$n;
        	}else{
	           $Var=$extra.".".$n;
        	}
   	  }

   	  $Var = substr($Var, - 2);

   	return $Var;
   	  }else{
   	  	  //return $Var;
   	  }



   }//fin AddCero


     function AddCero($nomVar,$vector=object,$extra=null){
   	  if($vector!=null){
   	  	  if($extra==null){
   	  	foreach($vector as $x){
        	if($x<10){
        	   $Var[$x]="0".$x;
        	}else{
	           $Var[$x]=$x;
        	}
	    }//fin each
   	  }else{
          foreach($vector as $x){
        	if($x<10){
        	   $Var[$x]=$extra.".0".$x;
        	}else{
	           $Var[$x]=$extra.".".$x;
        	}
	    }//fin each
   	  }
   	  $this->set($nomVar,$Var);
   	  }else{
   	  	  $this->set($nomVar,'');
   	  }
     }

function AddCero3($numero,$extra=null){
   	  if($extra==null){
   	  	$numero = ($numero < 10 ? "0".$numero : $numero);
   	  }else{
   	  	$numero = ($numero < 10 ? $extra."0".$numero : $extra.".".$numero);
   	  }
	    return $numero;
   }//fin AddCero


 function index(){
 	$this->layout ="ajax";
 	  $this->data=null;
 	$this->set('entidad_federal', $this->Session->read('entidad_federal'));
  	$tipo = $this->cimd01_clasificacion_tipo->generateList(null,'cod_tipo ASC', null, '{n}.cimd01_clasificacion_tipo.cod_tipo', '{n}.cimd01_clasificacion_tipo.denominacion');
	$this->concatena($tipo, 'tipo');
	$estado = $this->cugd01_estados->generateList(null,'cod_estado ASC', null, '{n}.cugd01_estados.cod_estado', '{n}.cugd01_estados.denominacion');
	$this->concatena($estado, 'estado');
	$institucion = $this->cugd02_institucion->generateList(null,'cod_institucion ASC', null, '{n}.cugd02_institucion.cod_institucion', '{n}.cugd02_institucion.denominacion');
	$this->concatena($institucion, 'institucion');
	$incorporacion = $this->cimd02_tipo_movimiento->generateList(null,'cod_tipo_mov ASC', null, '{n}.cimd02_tipo_movimiento.cod_tipo_mov', '{n}.cimd02_tipo_movimiento.denominacion');
	$this->concatena($incorporacion, 'incorporacion');
 }

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

/*function select3($select=null,$var=null) { //select codigos presupuestarios
	$this->layout = "ajax";
	//echo "si llego";
	if($var!=null){
	switch($select){
		case 'tipo':
		  $this->set('SELECT','grupo');
		  $this->set('codigo','tipo');
		  $this->set('seleccion','');
		  $this->set('n',1);
		  $ano =  $this->Session->read('ano');
		  $cond2 ="ejercicio=".$ano." and cod_grupo=".CE."";
		  $lista=  $this->cimd01_clasificacion_tipo->generateList($cond2, 'cod_tipo ASC', null, '{n}.cimd01_clasificacion_tipo.cod_tipo', '{n}.cimd01_clasificacion_tipo.denominacion');
			$this->concatena($lista,'vector');
		break;
		case 'grupo':
		//echo "si generica";
		  $this->set('SELECT','subgrupo');
		  $this->set('codigo','grupo');
		  $this->set('seleccion','');
		  $this->set('n',2);
		 // $ano =  $this->Session->read('ano');
		  $this->Session->write('ctipo',$var);
		  $cond2 ="cod_tipo=".$var;
		  $lista=  $this->cimd01_clasificacion_grupo->generateList($cond2, 'cod_grupo ASC', null, '{n}.cimd01_clasificacion_grupo.cod_grupo', '{n}.cimd01_clasificacion_grupo.denominacion');
          $this->concatena($lista,'vector');
 		break;
		case 'subgrupo':
		//echo"si especifica";
		  $this->set('SELECT','seccion');
		  $this->set('codigo','subgrupo');
		  $this->set('seleccion','');
		  $this->set('n',3);
		//  $ano =  $this->Session->read('ano');
		  $ctipo =  $this->Session->read('ctipo');
		  $this->Session->write('cgru',$var);
		  $cond2 ="cod_tipo=".$ctipo." and cod_grupo=".$var;
		 //echo $cond2;
		  $lista = $this->cimd01_clasificacion_subgrupo->generateList($cond2, 'cod_subgrupo ASC', null, '{n}.cimd01_clasificacion_subgrupo.cod_subgrupo', '{n}.cimd01_clasificacion_subgrupo.denominacion');
          $this->concatena($lista,'vector');
		break;
		case 'seccion':
		  $this->set('SELECT','seccion');
		  $this->set('codigo','seccion');
		  $this->set('seleccion','');
		  $this->set('n',4);
		//  $ano =  $this->Session->read('ano');
		  $ctipo =  $this->Session->read('ctipo');
		  $cgru =  $this->Session->read('cgru');
		  $this->Session->write('csub',$var);
		  $cond2 ="cod_tipo=".$ctipo." and cod_grupo=".$cgru." and cod_subgrupo=".$var;
		  $lista=  $this->cimd01_clasificacion_seccion->generateList($cond2, 'cod_seccion ASC', null, '{n}.cimd01_clasificacion_seccion.cod_seccion', '{n}.cimd01_clasificacion_seccion.denominacion');
          $this->concatena($lista,'vector');
		break;
	}//fin wsitch
	}else{
		  $this->set('SELECT','');
		  $this->set('codigo','');
		  $this->set('seleccion','');
		  $this->set('n',20);
		  $this->set('no','no');
		 $this->set('vector','');
	}
}//fin select codigos presupuestarios


function mostrar3($select=null,$var=null) { //mostrar3 codigos presupuestarios
	$this->layout = "ajax";
	//echo "mostrar";
		if( $var!=null){
  //  $cond = $this->SQLCA();
    //$cond2 = $this->SQLCA();
	switch($select){
		case 'tipo':
		  //$ano =  $this->Session->read('ano');
		  $this->Session->write('dtipo',$var);
		  $cond2 ="cod_tipo=".$var;
		  $a=  $this->cimd01_clasificacion_tipo->findAll($cond2);
          $e=$a[0]['cimd01_clasificacion_tipo']['denominacion'];
         $this->set('var',$e);
		break;
		case 'grupo':
		  //$ano =  $this->Session->read('ano');
		  $dtipo=  $this->Session->read('dtipo');
		  $this->Session->write('dgru',$var);
		  $cond2 ="cod_tipo=".$dtipo." and cod_grupo=".$var;
		  $a=  $this->cimd01_clasificacion_grupo->findAll($cond2);
          $e=$a[0]['cimd01_clasificacion_grupo']['denominacion'];
          $this->set('var',$e);
		break;
		case 'subgrupo':
		  // $ano =  $this->Session->read('ano');
		  $dtipo=  $this->Session->read('dtipo');
		  $dgru =  $this->Session->read('dgru');
		  $this->Session->write('dsub',$var);
		  $cond2 ="cod_tipo=".$dtipo." and cod_grupo=".$dgru." and cod_subgrupo=".$var;
		  //echo $cond2;
		  $a=  $this->cimd01_clasificacion_subgrupo->findAll($cond2);
		  //print_r($a);
          $e= $a[0]['cimd01_clasificacion_subgrupo']['denominacion'];
          $this->set('var',$e);
		break;
		case 'seccion':
		  //$ano =  $this->Session->read('ano');
		  $dtipo=  $this->Session->read('dtipo');
		  $dgru =  $this->Session->read('dgru');
		  $dsub =  $this->Session->read('dsub');
		  $this->Session->write('dsec',$var);
		  $cond2 ="cod_tipo=".$dtipo." and cod_grupo=".$dgru." and cod_subgrupo=".$dsub." and cod_seccion=".$var;
		  $a=  $this->cimd01_clasificacion_seccion->findAll($cond2);
          $e= $a[0]['cimd01_clasificacion_seccion']['denominacion'];
           $this->set('var',$e);
		break;
	}//fin wsitch
	}else{
		echo "";
	}
}//fin mostrar3 codigos presupuestarios

function mostrar4($select=null,$var=null) { //mostrar3 codigos presupuestarios
	$this->layout = "ajax";
	//echo "mostrar";
	//echo $select,$var;
		if( $var!=null){
  //  $cond = $this->SQLCA();
    //$cond2 = $this->SQLCA();
	switch($select){
		case 'tipo':
          	echo "<input type='text' name='data[cimp03_inventario_muebles][cod_tipo]' value='".$this->AddCero3($var)."' id='editar1'  class='inputtext' readonly=readonly/>";
		break;

		case 'grupo':
			 echo "<input type='text' name='data[cimp03_inventario_muebles][cod_grupo]' value='".$this->AddCero3($var)."' id='editar2'  class='inputtext' readonly=readonly/>";
		break;

		case 'subgrupo':
           echo "<input type='text' name='data[cimp03_inventario_muebles][cod_subgrupo]' value='".$this->AddCero3($var)."' id='editar3' class='inputtext' readonly=readonly/>";
		break;

		case 'seccion':
           echo "<input type='text' name='data[cimp03_inventario_muebles][cod_seccion]' value='".$this->AddCero3($var)."' class='inputtext' readonly=readonly/>";
		break;

	}//fin wsitch
	}else{
		echo "";
	}
}//fin mostrar3 codigos presupuestarios
*/


function buscar_pista ($pagina=null)
{
	$this->layout="ajax";
	//echo 'si entro';
//echo $pagina;

//print_r($this->data);
	if($pagina!=null){
		$pagina=$pagina;
	}else{
		$pagina=1;
	}
//print_r($this->data);
	if(isset($this->data['cimp04_vehiculo_asegurado']['pista']) && !empty($this->data["cimp04_vehiculo_asegurado2"]['pista'])){
         //$this->data["consulta"]["ano"]=$this->data["consulta2"]["ano"];
         $this->data['cimp04_vehiculo_asegurado']['pista']=$this->data['cimp04_vehiculo_asegurado2']['pista'];
         $otra="si";
	}else{
	  $otra="no";
	}
	if((isset($this->data["cimp04_vehiculo_asegurado"]['pista']) && !empty($this->data['cimp04_vehiculo_asegurado']['pista'])) || $otra=="si"){
		//echo 'si entro';
         $pista=strtoupper($this->data['cimp04_vehiculo_asegurado']['pista']);
        // echo 'la pista es '.$pista;
         $cantidad_resultado=$this->v_inventario_muebles_todo->findCount("denominacion like '%".$pista."%'");
       //  echo 'la cantidad es '.$cantidad_resultado;
         $resultado=$this->v_inventario_muebles_todo->findAll("denominacion like '%".$pista."%'",null,null,1,$pagina);
         //print_r($resultado);
         if($cantidad_resultado!=0){
           $this->set("cantidad_resultado",$cantidad_resultado);
           $this->set("resultado",$resultado);
           //print_r($resultado);
           //$this->set("ano",$ano);
           $this->set("pista",$pista);

           $this->set('siguiente',$pagina+1);
		   $this->set('actual',$pagina);
		   $this->bt_nav($cantidad_resultado,$pagina);
         }else{
           $this->set("cantidad_resultado",$cantidad_resultado);
           $this->set("resultado",array(0=>array("v_inventario_muebles_todo"=>array("cod_grupo"=>0,"cod_partida"=>0,"cod_generica"=>0,"cod_especifica"=>0,"cod_sub_espec"=>0,"cod_auxiliar"=>0,"concepto"=>"","denominacion"=>"No se encontraron datos para la pista indicada, ".$pista))));
           //$this->set("ano",$ano);
           $this->set("pista",$pista);
           $this->set('siguiente',$pagina+1);
		   $this->set('actual',$pagina);
           $this->bt_nav(1,1);
         }
         $this->set("MUESTRAME","");


	}else{
		if(!isset($this->data["cimp04_vehiculo_asegurado"]["pista"])){
			//echo 'entro en el segundo';
			echo "<h4>Faltan Datos para las busqueda, por favor indique pista.</h4>";
		}
	}

}//fin buscar_pista

function guardar(){
$this->layout ="ajax";
//print_r($this->data);
		$cod_presi = $this->verifica_SS(1);
		$cod_entidad = $this->verifica_SS(2);
	 	$cod_tipo_inst = $this->verifica_SS(3);
	 	$cod_inst = $this->verifica_SS(4);
	 	$cod_dep = $this->verifica_SS(5);
	 	$cod_tipo =  $this->Session->read('cod_tipo');
	 	$cod_grupo =  $this->Session->read('cod_grupo');
	 	$cod_subgrupo =  $this->Session->read('cod_subgrupo');
	 	$cod_seccion =  $this->Session->read('cod_seccion');
	 	$numero_identificacion =  $this->Session->read('numero_identificacion');
	 	//$cod_tipo = $this->data['cimp04_vehiculo_asegurado']['cod_tipo'];
	 	//$cod_grupo = $this->data['cimp04_vehiculo_asegurado']['cod_grupo'];
	 	//$cod_subgrupo = $this->data['cimp04_vehiculo_asegurado']['cod_subgrupo'];
	 	//$cod_seccion = $this->data['cimp04_vehiculo_asegurado']['cod_seccion'];
	 	//$numero_identificacion = $this->data['cimp04_vehiculo_asegurado']['numero_identificacion'];
	 	$placa = $this->data['cimp04_vehiculo_asegurado']['placa'];
	 	$compania_aseguradora = $this->data['cimp04_vehiculo_asegurado']['compania_aseguradora'];
	 	$numero_poliza = $this->data['cimp04_vehiculo_asegurado']['numero_poliza'];
	 	$monto_cobertura = $this->Formato1($this->data['cimp04_vehiculo_asegurado']['monto_cobertura']);
	 	$descripcion_cobertura = $this->data['cimp04_vehiculo_asegurado']['descripcion_cobertura'];
	 	$vehiculo_asignado = $this->data['cimp04_vehiculo_asegurado']['vehiculo_asignado'];
	 	$campos="cod_presi,cod_entidad,cod_tipo_inst,cod_inst,cod_dep,cod_tipo,cod_grupo,cod_subgrupo,cod_seccion,
 		numero_identificacion,placa,compania_aseguradora,numero_poliza,monto_cobertura,descripcion_cobertura,vehiculo_asignado";
 		$sql    ="INSERT INTO  cimd04_vehiculo_asegurado ($campos)VALUES (
 				$cod_presi,$cod_entidad,$cod_tipo_inst,$cod_inst,$cod_dep,$cod_tipo,$cod_grupo,$cod_subgrupo,$cod_seccion,
 				$numero_identificacion,'$placa','$compania_aseguradora','$numero_poliza',$monto_cobertura,'$descripcion_cobertura','$vehiculo_asignado')";
		$respuesta=$this->cimd04_vehiculo_asegurado->execute($sql);
		if($respuesta > 1){
			$this->set('Message_existe', 'Registro Agregado con exito.');
		}else{
			$this->set('errorMessage', 'Disculpe, El Registro no fue creado.');
		}
  $this->index();
  $this->render("index");//echo "no entro";


}


function consultar($pagina=null){
 		$this->layout = "ajax";
         if($pagina!=null){
          	 $pagina=$pagina;
          	 $this->set('pagina',$pagina);
          	  $Tfilas=$this->v_cimd04_vehiculo_asegurado_todo->findCount();
          	 if($Tfilas==0){
          	 	$this->set('errorMessage', 'No se encontrar&oacute;n datos');
          	 	$this->index();
          		$this->render("index");
          	 }
          	 if($Tfilas!=0){
          	 $this->set('pag_cant',$pagina.'/'.$Tfilas);
          	 $datacpcp01=$this->v_cimd04_vehiculo_asegurado_todo->findAll(null,null,'numero_identificacion ASC',1,$pagina,null);
          	// $cd01=$this->cpcd01->findAll();
          	 $this->set('datos',$datacpcp01);
          	 $this->set('siguiente',$pagina+1);
          	 $this->set('anterior',$pagina-1);
             $this->bt_nav($Tfilas,$pagina);
             }
 }else{
 	$pagina=1;
 	$this->set('pagina',$pagina);
          	 $Tfilas=$this->v_cimd04_vehiculo_asegurado_todo->findCount();
          	 if($Tfilas==0){
          	 	$this->set('errorMessage', 'No se encontrar&oacute;n datos');
          	 	$this->index();
          		$this->render("index");
          	 }
          	 if($Tfilas!=0){
          	 $this->set('pag_cant',$pagina.'/'.$Tfilas);
          	 $datacpcp01=$this->v_cimd04_vehiculo_asegurado_todo->findAll(null,null,'numero_identificacion ASC',1,$pagina,null);
          	 $this->set('datos',$datacpcp01);
          	 $this->set('siguiente',$pagina+1);
          	 $this->set('anterior',$pagina-1);
             $this->bt_nav($Tfilas,$pagina);
			 }
        }
}//fin function consultar2

function query($var=null){
	$this->layout="ajax";
	$this->set('tipo', $var);
}

function datos($tipo = null, $pista=null){
	$this->layout="ajax";
	$cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep;

	$pista2=strtoupper($pista);
	if($tipo=='numero_identificacion'){
		$busq="numero_identificacion=".$pista2;
	}else if($tipo=='denominacion'){
		$busq="upper($tipo) LIKE '%$pista2%'";
	}


	if($tipo!=null && $pista!=null){
				$exe="select * from v_inventario_muebles_todo where ".$busq;
				$datos=$this->v_inventario_muebles_todo->execute($exe);
				$this->set('datos', $datos);

	}

}

function preconsulta(){
	$this->layout="ajax";
	$opciones = array('numero_identificacion'=>'Numero de Identificacion', 'denominacion'=>'Denominacion del Bien');
	$this->set('opcion', $opciones);
}
function buscar_rif() {
	$this->layout="ajax";


}
function lista_encontrados($rif){
 		$this->layout = "ajax";
 		 $cond ="numero_identificacion='".$rif."'";//echo $cond;
 		 $num=$this->v_inventario_muebles_todo->findCount($cond);
 		 if($num==1){

          	 $datacpcp01=$this->v_inventario_muebles_todo->findAll($cond,null,'numero_identificacion ASC');
          	 $this->set('datos',$datacpcp01);
          	 print_r($datacpcp01);
 		 }else{//echo "no hay dato";
 		 	$this->set('errorMessage', 'No se encontrar&oacute;n datos');
 		 	$this->preconsulta();
 		 	$this->render("preconsulta");

	    	  // }
      }//fin function consultar2



}

function modificar($numero_identificacion=null,$pagina=null){
	$this->layout = "ajax";
	 $data=$this->v_cimd04_vehiculo_asegurado_todo->findAll('numero_identificacion='.$numero_identificacion);
     $this->set('datos',$data);
     $this->set('pagina',$pagina);
}

function guardar_modificar($numero_identificacion=null,$pagina=null){
	$this->layout = "ajax";
	$placa = $this->data['cimp04_vehiculo_asegurado']['placa'];
	 	$compania_aseguradora = $this->data['cimp04_vehiculo_asegurado']['compania_aseguradora'];
	 	$numero_poliza = $this->data['cimp04_vehiculo_asegurado']['numero_poliza'];
	 	$monto_cobertura = $this->data['cimp04_vehiculo_asegurado']['monto_cobertura'];
	 	$descripcion_cobertura = $this->data['cimp04_vehiculo_asegurado']['descripcion_cobertura'];
	 	$vehiculo_asignado = $this->data['cimp04_vehiculo_asegurado']['vehiculo_asignado'];


	 $sql="update cimd04_vehiculo_asegurado set placa='".$placa."', compania_aseguradora='".$compania_aseguradora."', numero_poliza='".$numero_poliza."', monto_cobertura='".$monto_cobertura."', descripcion_cobertura='".$descripcion_cobertura."', vehiculo_asignado='".$vehiculo_asignado."' where numero_identificacion=".$numero_identificacion."";
     $vvv=$this->cimd04_vehiculo_asegurado->execute($sql);
     $this->data=null;
     $this->set('Message_existe', 'Registro Modificado con exito.');
	 $this->consultar($pagina);
	 $this->render("consultar");
}
function buscar_vehiculo($var1=null){
	$this->layout="ajax";
	$this->set("opcion",$var1);
	$this->Session->delete('pista');
}//fin function

function buscar_por_pista($var1=null, $var2=null, $var3=null){
$this->layout="ajax";

    if($var3==null){$var2 = strtoupper($var2);
					$this->Session->write('pista', $var2);
					if(is_int($var2)){$sql   = " (codigo_prod_serv LIKE '%$var2%')  or   ";}else{ $sql = "";}
					$Tfilas=$this->v_inventario_muebles_todo->findCount($sql." (denominacion LIKE '%$var2%')");
					        if($Tfilas!=0){
					        	$pagina=1;
					        	$Tfilas=(int)ceil($Tfilas/100);
					        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
								$this->set('total_paginas',$Tfilas);
								$this->set('pagina_actual',$pagina);
								$this->set('ultimo',$Tfilas);
					     	    $datos_filas=$this->v_inventario_muebles_todo->findAll($sql." (denominacion LIKE '%$var2%')   ",null,"numero_identificacion ASC",100,1,null);
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
						if(is_int($var22)){$sql   = " (codigo_prod_serv LIKE '%$var22%')  or   ";}else{ $sql = "";}
						$Tfilas=$this->v_inventario_muebles_todo->findCount($sql." (denominacion LIKE '%$var22%')");
						        if($Tfilas!=0){
						        	$pagina=$var3;
						        	$Tfilas=(int)ceil($Tfilas/100);
						        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
									$this->set('total_paginas',$Tfilas);
									$this->set('pagina_actual',$pagina);
									$this->set('ultimo',$Tfilas);
						     	    $datos_filas=$this->v_inventario_muebles_todo->findAll($sql." (denominacion LIKE '%$var22%')  ",null,"numero_identificacion ASC",100,$pagina,null);
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
function seleccion_busqueda_venta($var1=null){
$this->layout="ajax";
$this->Session->write('cod_tipo1',$var1);
$numero_id = $this->Session->read('numero_id');
$resultado=$this->v_inventario_muebles_todo->findAll('numero_identificacion='.$var1);
//pr($resultado);
$a=$resultado[0]['v_inventario_muebles_todo']['cod_tipo'];
$b=$this->AddCeroR($resultado[0]['v_inventario_muebles_todo']['cod_grupo']);
$c=$this->AddCeroR($resultado[0]['v_inventario_muebles_todo']['cod_subgrupo']);
$d=mascara_tres($resultado[0]['v_inventario_muebles_todo']['cod_seccion']);
$this->Session->write('cod_tipo',$a);
$this->Session->write('cod_grupo',$b);
$this->Session->write('cod_subgrupo',$c);
$this->Session->write('cod_seccion',$d);
$e=$this->mascara_ocho($resultado[0]['v_inventario_muebles_todo']['numero_identificacion']);
$this->Session->write('numero_identificacion',$e);
$f=$this->AddCeroR($resultado[0]['v_inventario_muebles_todo']['cod_estado']);
$g=$this->AddCeroR($resultado[0]['v_inventario_muebles_todo']['cod_municipio']);
$h=$this->AddCeroR($resultado[0]['v_inventario_muebles_todo']['cod_parroquia']);
$i=$this->AddCeroR($resultado[0]['v_inventario_muebles_todo']['cod_centro']);

$j=$this->AddCeroR($resultado[0]['v_inventario_muebles_todo']['cod_institucion']);
$k=$this->AddCeroR($resultado[0]['v_inventario_muebles_todo']['cod_dependencia']);
$l=$this->AddCeroR($resultado[0]['v_inventario_muebles_todo']['cod_dir_superior']);
$m=$this->AddCeroR($resultado[0]['v_inventario_muebles_todo']['cod_coordinacion']);

$n=$this->AddCeroR($resultado[0]['v_inventario_muebles_todo']['cod_secretaria']);
$o=$this->AddCeroR($resultado[0]['v_inventario_muebles_todo']['cod_direccion']);
$p=$this->AddCeroR($resultado[0]['v_inventario_muebles_todo']['cod_division']);
$pp=$resultado[0]['v_inventario_muebles_todo']['deno_departamento'];
if($pp==''){
	$pp='N/A';
}
$q=$this->AddCeroR($resultado[0]['v_inventario_muebles_todo']['cod_departamento']);
$r=$this->AddCeroR($resultado[0]['v_inventario_muebles_todo']['cod_oficina']);
$s=$resultado[0]['v_inventario_muebles_todo']['deno_departamento'];
if($s==''){
	$s='N/A';
}
$t=$resultado[0]['v_inventario_muebles_todo']['deno_oficina'];
if($t==''){
	$t='N/A';
}
$xb=$resultado[0]['v_inventario_muebles_todo']['denominacion'];
$xa=javascript_encode($resultado[0]['v_inventario_muebles_todo']['denominacion']);
//echo $xb.'a';
//echo $xa;
					echo "<script>";
						echo "document.getElementById('denominacion').value='$xa'; ";
					    echo "document.getElementById('cod_tipo').value='".$a."';   ";
					    echo "document.getElementById('deno_tipo').value='".$resultado[0]['v_inventario_muebles_todo']['deno_tipo']."';   ";
					    echo "document.getElementById('cod_grupo').value='".$b."';   ";
					    echo "document.getElementById('deno_grupo').value='".$resultado[0]['v_inventario_muebles_todo']['deno_grupo']."';   ";
					    echo "document.getElementById('cod_subgrupo').value='".$c."';   ";
					    echo "document.getElementById('deno_subgrupo').value='".$resultado[0]['v_inventario_muebles_todo']['deno_subgrupo']."';   ";
					    echo "document.getElementById('cod_seccion').value='".$d."';   ";
					    echo "document.getElementById('deno_seccion').value='".$resultado[0]['v_inventario_muebles_todo']['deno_seccion']."';   ";
					    echo "document.getElementById('numero_identificacion').value='".$e."';   ";
					    echo "document.getElementById('cod_estado').value='".$f."';   ";
					    echo "document.getElementById('cod_municipio').value='".$g."';   ";
					    echo "document.getElementById('cod_parroquia').value='".$h."';   ";
					    echo "document.getElementById('cod_centro').value='".$i."';   ";
					    echo "document.getElementById('deno_estado').value='".$resultado[0]['v_inventario_muebles_todo']['deno_estado']."';   ";
					    echo "document.getElementById('deno_municipio').value='".$resultado[0]['v_inventario_muebles_todo']['deno_municipio']."';   ";
					    echo "document.getElementById('deno_parroquia').value='".$resultado[0]['v_inventario_muebles_todo']['deno_parroquia']."';   ";
					    echo "document.getElementById('deno_centro').value='".$resultado[0]['v_inventario_muebles_todo']['deno_centro']."';   ";

					    echo "document.getElementById('cod_institucion').value='".$j."';   ";
					    echo "document.getElementById('cod_dependencia').value='".$k."';   ";
					    echo "document.getElementById('cod_dir_superior').value='".$l."';   ";
					    echo "document.getElementById('cod_coordinacion').value='".$m."';   ";
					    echo "document.getElementById('cod_secretaria').value='".$n."';   ";
					    echo "document.getElementById('cod_direccion').value='".$o."';   ";
					    echo "document.getElementById('cod_division').value='".$p."';   ";
					    echo "document.getElementById('cod_departamento').value='".$q."';   ";
					    echo "document.getElementById('cod_oficina').value='".$r."';   ";

					    echo "document.getElementById('deno_institucion').value='".$resultado[0]['v_inventario_muebles_todo']['deno_institucion']."';   ";
					    echo "document.getElementById('deno_dependencia').value='".$resultado[0]['v_inventario_muebles_todo']['deno_dependencia']."';   ";
					    echo "document.getElementById('deno_dir_superior').value='".$resultado[0]['v_inventario_muebles_todo']['deno_dir_superior']."';   ";
					    echo "document.getElementById('deno_coordinacion').value='".$resultado[0]['v_inventario_muebles_todo']['deno_coordinacion']."';   ";
					    echo "document.getElementById('deno_secretaria').value='".$resultado[0]['v_inventario_muebles_todo']['deno_secretaria']."';   ";
					    echo "document.getElementById('deno_direccion').value='".$resultado[0]['v_inventario_muebles_todo']['deno_direccion']."';   ";
					    echo "document.getElementById('deno_division').value='".$pp."';   ";
					    echo "document.getElementById('deno_departamento').value='".$s."';   ";
					    echo "document.getElementById('deno_oficina').value='".$t."';   ";
					    //echo "document.getElementById('segunda_ventana').disabled=false";
					echo "</script>";
$this->funcion($var1);
$this->render("funcion");



}//fin function

 function eliminar($identificacion=null,$pagina=null){
 	$this->layout = "ajax";
        $cod_presi = $this->Session->read('SScodpresi');
	  $cod_entidad = $this->Session->read('SScodentidad');
	  $cod_tipo_inst = $this->Session->read('SScodtipoinst');
	  $cod_inst = $this->Session->read('SScodinst');
	  $cod_dep = $this->Session->read('SScoddep');
	  $modulo = $this->Session->read('Modulo');
	  $c = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep;
       if(isset($identificacion)){
 	$cond=" numero_identificacion =".$identificacion." and ".$c;
 	//echo $cond;
 	$this->cimd04_vehiculo_asegurado->execute("DELETE FROM cimd04_vehiculo_asegurado  WHERE ".$cond);
 	$y=$this->cimd04_vehiculo_asegurado->findCount($this->SQLCA());
 	if($pagina>$y){
 		$pagina=$pagina-1;
 	}



	  if($y!=0){
	  	 $this->set('Message_existe', 'Registro Eliminado con exito.');
      	 $this->consultar($pagina);//si es el primero solamente
      $this->render("consultar");

		}else if($y==0){
			$this->set('Message_existe', 'Registro Eliminado con exito.');
			$this->index();
      		$this->render("index");
		}//fin if
 }

 }

function funcion($var1=null, $var2=null, $var3=null){

$this->layout="ajax";
$this->set('numero',$var1);

}//fin function

function buscar($var1=null){
	$this->layout="ajax";
	$this->set("opcion",$var1);
	$this->Session->delete('pista');
}//fin function

function buscar_por_pista2($var1=null, $var2=null, $var3=null){
$this->layout="ajax";

    if($var3==null){$var2 = strtoupper($var2);
					$this->Session->write('pista', $var2);
					if(is_int($var2)){$sql   = " (codigo_prod_serv LIKE '%$var2%')  or   ";}else{ $sql = "";}
					$Tfilas=$this->v_cimd04_vehiculo_asegurado_todo->findCount($sql." (denominacion LIKE '%$var2%')");
					        if($Tfilas!=0){
					        	$pagina=1;
					        	$Tfilas=(int)ceil($Tfilas/100);
					        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
								$this->set('total_paginas',$Tfilas);
								$this->set('pagina_actual',$pagina);
								$this->set('ultimo',$Tfilas);
					     	    $datos_filas=$this->v_cimd04_vehiculo_asegurado_todo->findAll($sql." (denominacion LIKE '%$var2%')   ",null,"numero_identificacion ASC",100,1,null);
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
						if(is_int($var22)){$sql   = " (codigo_prod_serv LIKE '%$var22%')  or   ";}else{ $sql = "";}
						$Tfilas=$this->v_cimd04_vehiculo_asegurado_todo->findCount($sql." (denominacion LIKE '%$var22%')");
						        if($Tfilas!=0){
						        	$pagina=$var3;
						        	$Tfilas=(int)ceil($Tfilas/100);
						        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
									$this->set('total_paginas',$Tfilas);
									$this->set('pagina_actual',$pagina);
									$this->set('ultimo',$Tfilas);
						     	    $datos_filas=$this->v_cimd04_vehiculo_asegurado_todo->findAll($sql." (denominacion LIKE '%$var22%')  ",null,"numero_identificacion ASC",100,$pagina,null);
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
function consulta2($numero=null){
 		$this->layout = "ajax";
 	  $cod_presi = $this->Session->read('SScodpresi');
	  $cod_entidad = $this->Session->read('SScodentidad');
	  $cod_tipo_inst = $this->Session->read('SScodtipoinst');
	  $cod_inst = $this->Session->read('SScodinst');
	  $cod_dep = $this->Session->read('SScoddep');
	  $modulo = $this->Session->read('Modulo');
	  $c = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep." and numero_identificacion=".$numero;
          	 $veri=$this->v_cimd04_vehiculo_asegurado_todo->findCount($c);
          	 if($veri > 0){
          	 $datacpcp01=$this->v_cimd04_vehiculo_asegurado_todo->findAll($c);
          	 $this->set('datos',$datacpcp01);
          	 }else{
				$this->index($numero);
				$this->render("index");
				//$this->set('numero',$numero);
          	 }
}//fin function consultar2
function imagen($numero){
	$this->layout = "ajax";
	$this->set('numero_identificacion',$numero);
}

}//fin de la clase controller
?>
