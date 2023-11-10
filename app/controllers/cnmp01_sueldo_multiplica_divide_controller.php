<?php
class Cnmp01SueldoMultiplicaDivideController extends AppController {
   var $name = 'cnmp01_sueldo_multiplica_divide';
   var $uses = array('Cnmd01', 'cnmd05', 'cfpd02_sub_prog', 'cfpd02_proyecto', 'cfpd02_activ_obra','ccfd04_cierre_mes');
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
function AddCero($nomVar,$vector=object,$extra=null){
   	  if($vector != null){
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
   }//fin AddCero

   function zero($x=null){
	if($x != null){
		if($x<10){
			$x="0".$x;
		}else if($x>=10 && $x<=99){
			$x=$x;
		}
	}
	return $x;

}

function concatena($vector1=null, $nomVar=null){

	if($vector1 != null){

		foreach($vector1 as $x => $y){

			$cod[$x] = $this->zero($x).' - '.$y;
		}
		//print_r($cod);

		$this->set($nomVar, $cod);

	}
}


function index(){
	$this->layout = "ajax";
	$this->data=null;
	$cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep=".$cod_dep;
	$lista = $this->Cnmd01->generateList($condicion, 'cod_tipo_nomina ASC', null, '{n}.Cnmd01.cod_tipo_nomina', '{n}.Cnmd01.denominacion');
	if($this->Cnmd01->findCount($condicion)!=0){
		$this->concatenaN($lista, 'nomina');
	}else{
		$this->set('nomina', array());
	}
 }

function codigo_nomina($codigo){
	$this->layout = "ajax";
	echo "<script>";
 		echo "document.getElementById('bt_guardar').disabled='true';  ";
 	echo "</script>";
	$a = $this->Cnmd01->findAll($this->SQLCA()." and cod_tipo_nomina=".$codigo,array('cod_tipo_nomina','denominacion'));
    $this->set("a",$a[0]['Cnmd01']['cod_tipo_nomina']);
}//fin cpcp02_codigo

function denominacion_nomina($codigo){
	$this->layout = "ajax";
	$b = $this->Cnmd01->findAll($this->SQLCA()." and cod_tipo_nomina=".$codigo,array('cod_tipo_nomina','denominacion'));
	$this->set("b",$b[0]['Cnmd01']['denominacion']);


}//fin cpcp02_denominacion

function grilla($codigo){
	$this->layout = "ajax";
	$cod=$this->SQLCA();
	$buscar='select	c.cod_tipo_nomina, c.cod_cargo,	c.cod_puesto,c.sueldo_basico,(select devolver_denominacion_puesto((select xy.clasificacion_personal
			from cnmd01 xy where
			xy.cod_presi           =     c.cod_presi       and
			xy.cod_entidad         =     c.cod_entidad     and
			xy.cod_tipo_inst       =     c.cod_tipo_inst   and
			xy.cod_inst            =     c.cod_inst        and
			xy.cod_dep             =     c.cod_dep         and
			xy.cod_tipo_nomina     =     c.cod_tipo_nomina), c.cod_puesto )) as denominacion_puesto
			from cnmd05 c where '.$cod.' and cod_tipo_nomina='.$codigo.' order by cod_cargo,cod_puesto ASC';
	$b = $this->cnmd05->execute($buscar);
	$this->set('datos',$b);
}

function procesar(){
	$this->layout = "ajax";
	$codigo = $this->data['cnmp01_sueldo_multiplica_divide']['cod_tipo_nomina'];
	$operacion = $this->data['cnmp01_sueldo_multiplica_divide']['operacion'];
	$cuanto = $this->data['cnmp01_sueldo_multiplica_divide']['cuanto'];
	if($codigo==null){
		$codigo=0;
	}
	$cod=$this->SQLCA();
	//echo $cod;
	$buscar='select	c.cod_tipo_nomina, c.cod_cargo,	c.cod_puesto,c.sueldo_basico,(select devolver_denominacion_puesto((select xy.clasificacion_personal
			from cnmd01 xy where
			xy.cod_presi           =     c.cod_presi       and
			xy.cod_entidad         =     c.cod_entidad     and
			xy.cod_tipo_inst       =     c.cod_tipo_inst   and
			xy.cod_inst            =     c.cod_inst        and
			xy.cod_dep             =     c.cod_dep         and
			xy.cod_tipo_nomina     =     c.cod_tipo_nomina), c.cod_puesto )) as denominacion_puesto
			from cnmd05 c where '.$cod.' and cod_tipo_nomina='.$codigo.' order by cod_cargo,cod_puesto ASC';
	$b = $this->cnmd05->execute($buscar);
	$this->set('datos',$b);
	$this->set('operacion',$operacion);
	$this->set('cuanto',$cuanto);
}
function guardar(){
	$this->layout = "ajax";
	$codigo = $this->data['cnmp01_sueldo_multiplica_divide']['cod_tipo_nomina'];
	$operacion = $this->data['cnmp01_sueldo_multiplica_divide']['operacion'];
	$cuanto = $this->data['cnmp01_sueldo_multiplica_divide']['cuanto'];
	$cod=$this->SQLCA();
	$username = $this->Session->read('nom_usuario');
	if($operacion == 1){
	$actualizar='update cnmd05 set sueldo_basico= sueldo_basico * '.$cuanto.' where '.$cod.' and cod_tipo_nomina='.$codigo;
	}else if($operacion == 2){
	$actualizar='update cnmd05 set sueldo_basico= sueldo_basico / '.$cuanto.' where '.$cod.' and cod_tipo_nomina='.$codigo;
	//echo $actualizar;
	}
	$b = $this->cnmd05->execute($actualizar);
	$this->set('Message_existe', 'Operaci&oacute;n realizada con exito.');
  	$this->index();
  	$this->render("index");


}

function operacion($ope=null){
	$this->layout = "ajax";
	$this->set('ope',$ope);
}

}