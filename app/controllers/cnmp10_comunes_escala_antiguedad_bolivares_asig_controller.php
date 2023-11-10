<?php
/*
 * Creado el  30/10/2007 a las 12:03:17 PM
 *
 * Herramienta: EasyEclipse.
 *
 * Proyecto: SIGEP
 *
 */
 class Cnmp10ComunesEscalaAntiguedadBolivaresAsigController extends AppController {
   var $name = 'cnmp10_comunes_escala_antiguedad_bolivares_asig';
   var $uses = array('cnmd01','cnmd10_comunes_escala_antiguedad_bolivares_asig','cnmd10_comunes_escala_antiguedad_bolivares_asig_2','cnmd10_control_de_escenarios','v_cnmd06','v_cnmd10_comunes_escala_antiguedad_bolivares_asig','Cnmd01', 'cnmd10_bolivares_asig', 'cnmd03_transacciones', 'cnmd10_control_escenarios', 'v_cnmd10_bolivares_asig_trans');
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

 function condicion(){
  $cod_presi = $this->Session->read('SScodpresi');
  $cod_entidad = $this->Session->read('SScodentidad');
  $cod_tipo_inst = $this->Session->read('SScodtipoinst');
  $cod_inst = $this->Session->read('SScodinst');
  $cod_dep = $this->Session->read('SScoddep');
  $modulo = $this->Session->read('Modulo');
  $condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep;

  return $condicion;

}

 function index(){
 	$this->layout ="ajax";
 	$this->set('entidad_federal', $this->Session->read('entidad_federal'));
	$lista = $this->Cnmd01->generateList($conditions = $this->condicion(), $order = 'cod_tipo_nomina', $limit = null, '{n}.Cnmd01.cod_tipo_nomina', '{n}.Cnmd01.denominacion');
	$this->concatenaN($lista, 'nomina');

	$cnmd03 = $this->cnmd03_transacciones->generateList($conditions ='cod_tipo_transaccion = 1', $order = null, $limit = null, '{n}.cnmd03_transacciones.cod_transaccion', '{n}.cnmd03_transacciones.denominacion');
	$this->concatenaN($cnmd03, 'transaccion');

 }
function codigo($var){
 	$this->layout = "ajax";
 	$this->set('var',$var);

}


 function denominacion($var){
 	$this->layout = "ajax";
	$cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$con = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep;
	$con .=" and cod_tipo_nomina=".$var;
	//echo $con;

	$tipo=$this->Cnmd01->findAll($con);//print_r($tipo);
    $this->set('datos',$tipo);



 }

function codigot($var){
 	$this->layout = "ajax";
 	$this->set('var',$var);//echo "saul perez";

}


 function denominaciont($var){
 	$this->layout = "ajax";
	$con=" cod_transaccion=".$var;
	//echo $con;

	$tipo=$this->cnmd03_transacciones->findAll($con);
    $this->set('datos',$tipo);



 }


 function codigotra($var){
 	$this->layout = "ajax";
 	$this->set('var',$var);//echo "saul perez";

}


 function denominaciontra($var){
 	$this->layout = "ajax";
	$con=" cod_transaccion=".$var;
	//echo $con;

	$tipo=$this->cnmd03_transacciones->findAll($con);
    $this->set('datos',$tipo);



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

function agregar_asignaciones(){
	$this->layout = "ajax";

}
function datos($cod_nomina=null){
	$this->layout="ajax";

	if($cod_nomina != null){
		$deno_trans= $this->cnmd03_transacciones->findAll($conditions = 'cod_tipo_transaccion=1', $fields ='cod_transaccion, denominacion', $order = null, $limit = null, $page = null, $recursive = null);
		$this->set('deno_trans', $deno_trans);
		$datos = $this->v_cnmd10_comunes_escala_antiguedad_bolivares_asig->findAll($conditions = $this->condicion()." and cod_tipo_nomina='$cod_nomina'", $fields ='escala,desde_ano,hasta_ano, monto', $order = 'escala', $limit = null, $page = null, $recursive = null);
//print_r($datos);
		$this->set('datos', $datos);
		$this->set('cod_nomina', $cod_nomina);
		foreach($datos as $row){
		$escala = $row['v_cnmd10_comunes_escala_antiguedad_bolivares_asig']['escala'];
		}
		$nueva_escala=$escala + 1;
		$this->set('nueva_escala',$nueva_escala);

	}
	//echo $escala."esta es la escala";
}
function select_trans($cod_nomina=null){
	$this->layout="ajax";
	if($cod_nomina!=null){
		$cnmd03 = $this->cnmd03_transacciones->generateList($conditions ='cod_tipo_transaccion = 1', $order = null, $limit = null, '{n}.cnmd03_transacciones.cod_transaccion', '{n}.cnmd03_transacciones.denominacion');
		$this->concatenaN($cnmd03, 'transaccion');
		$this->set('cod_nomina', $cod_nomina);
	}
}



function verifica($cod_nomina=null, $cod_trans=null){
	$this->layout="ajax";
	if($cod_trans!=null && $cod_trans!=null){
		//$this->cnmd10_control_escenarios->findAll($conditions =$this->condicion()." and cod_nomina='$cod_nomina' and cod_tipo_transaccion=1 and cod_transaccion='$cod_trans'", $fields = null, $order = null, $limit = null, $page = null, $recursive = null);
		$ubicacion = $this->cnmd10_control_escenarios->field('cnmd10_control_escenarios.ubicacion_escenario', $conditions = $this->condicion()." and cod_tipo_nomina='$cod_nomina' and cod_tipo_transaccion=1 and cod_transaccion='$cod_trans'", $order =null);
		$this->set('ubicacion', $ubicacion);
	}

}

function guardar(){
	$this->layout = "ajax";
if(!empty($this->data)){
//  print_r($this->data['cnmp10_comunes_escala_antiguedad_bolivares_asig']);
  $aa[1]=$this->verifica_SS(1);
  $aa[2]=$this->verifica_SS(2);
  $aa[3]=$this->verifica_SS(3);
  $aa[4]=$this->verifica_SS(4);
  $aa[5]=$this->verifica_SS(5);
  $nomina=$this->data['cnmp10_comunes_escala_antiguedad_bolivares_asig']['nomina'];
  $transaccion=$this->data['cnmp10_comunes_escala_antiguedad_bolivares_asig']['transaccion'];
  $frecuencia=$this->data['cnmp10_comunes_escala_antiguedad_bolivares_asig']['frecuencia'];
  $condicion=$this->data['cnmp10_comunes_escala_antiguedad_bolivares_asig']['condicion'];
  $escenario=$this->data['cnmp10_comunes_escala_antiguedad_bolivares_asig']['escenario'];
  if(!isset($escenario)){
  	$escenario=2;
  }echo "el escenario es ".$escenario;
  $tipo_trans=$this->data['cnmp10_comunes_escala_antiguedad_bolivares_asig']['tipo_trans'];
  if(!isset($tipo_trans)){
  	$tipo_trans=0;
  }
  $codi_trans=$this->data['cnmp10_comunes_escala_antiguedad_bolivares_asig']['codi_trans'];
    if(!isset($codi_trans)){
  	$codi_trans=0;
  }
  $escala=$this->data['cnmp10_comunes_escala_antiguedad_bolivares_asig']['escala'];
  $desde_ano=$this->data['cnmp10_comunes_escala_antiguedad_bolivares_asig']['desde_ano'];
  $hasta_ano=$this->data['cnmp10_comunes_escala_antiguedad_bolivares_asig']['hasta_ano'];
  $monto=$this->data['cnmp10_comunes_escala_antiguedad_bolivares_asig']['monto'];
  $ubicacion_escenario = strtoupper('Cancelacion Comun En Bolivares Segun Escala De Años De Servicios');

  $sql1 ="INSERT INTO cnmd10_comunes_escala_antiguedad_bolivares_asig (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep,
  cod_tipo_nomina, cod_tipo_transaccion, cod_transaccion, escala, desde_sueldo,
  hasta_sueldo, monto, cod_frecuencia, cod_condicion, codi_tipo_transaccion, codi_transaccion, activar_frecuencia_eventual)";
  $sql1 .=" VALUES (".$aa[1].",".$aa[2].",".$aa[3].",".$aa[4].",".$aa[5].",
  $nomina,1,$transaccion,$escala,0,0,$monto,$frecuencia,$condicion,$tipo_trans,$codi_trans,$escenario)";

  $sql2= "INSERT INTO cnmd10_comunes_escala_antiguedad_bolivares_asig_2 ( cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep,
  cod_tipo_nomina, cod_tipo_transaccion, cod_transaccion, escala, desde_ano, hasta_ano, monto)";
  $sql2 .=" VALUES (".$aa[1].",".$aa[2].",".$aa[3].",".$aa[4].",".$aa[5].",
  $nomina,1,$transaccion,$escala,$desde_ano,$hasta_ano,$monto)";

  $sql3="INSERT INTO cnmd10_control_de_escenarios ( cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep,
  cod_tipo_nomina, cod_tipo_transaccion, cod_transaccion, ubicacion_escenario)";
  $sql3 .=" VALUES (".$aa[1].",".$aa[2].",".$aa[3].",".$aa[4].",".$aa[5].",
  $nomina,1,$transaccion,'".$ubicacion_escenario."')";

  $resp=$this->cnmd10_comunes_escala_antiguedad_bolivares_asig->execute($sql1);
  if($resp>1)
  {
  $this->cnmd10_comunes_escala_antiguedad_bolivares_asig_2->execute($sql2);
  $this->cnmd10_control_de_escenarios->execute($sql3);
    $this->data=null;
	 $this->set('Message_existe', 'Registro Agregado con exito.');
	 $this->index();
	 $this->render("index");
  }else if ($resp <= 1){
  $this->set('Message_existe', 'Disculpe, El Registro no fue creado.');
  $this->index();
  $this->render("index");
}// fin else
}//fin existe
}//fin guardar

}//fin de la clase controller
?>
