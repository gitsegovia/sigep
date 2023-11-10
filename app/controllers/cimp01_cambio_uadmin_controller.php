<?php
/*
 * Created on 19/07/2007
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */

 class Cimp01CambioUadminController extends AppController{
	 var $name = 'cimp01_cambio_uadmin';
     var $uses = array('v_inventario_muebles_todo','v_buscar_muebles','cugd02_division','cimd03_inventario_muebles','cimd02_tipo_movimiento','cimd01_clasificacion_tipo','cimd01_clasificacion_seccion','cimd01_clasificacion_subgrupo','cimd01_clasificacion_grupo','cugd01_estados','cugd01_municipios','cugd01_parroquias','Cugd01_centropoblados','cugd02_dependencia','cugd02_coordinacion','cugd02_departamento','cugd02_direccion','cugd02_direccionsuperior','cugd02_division','cugd02_oficina','cugd02_institucion','cugd02_secretaria','cugd01_centropoblados');
     var $helpers = array('Html','Ajax','Javascript', 'Sisap');
    // var $paginate = array('limit' => 3, 'page' => 1);
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

        function SQLCX($ano=null){//sql para busqueda de codigos de arranque con y sin año
         $sql_re = "cod_presi=".$this->verifica_SS(1)."  and    ";
         $sql_re .= "cod_entidad=".$this->verifica_SS(2)."  and  ";
         $sql_re .= "cod_tipo_inst=".$this->verifica_SS(3)."  and ";
         $sql_re .= "cod_inst=".$this->verifica_SS(4)." ";
		 return $sql_re;


    }//fin funcion SQLX

function concatena4($vector1=null, $nomVar=null, $extra=null){
	$cod = array();
	if($vector1 != null){
		foreach($vector1 as $x => $y){
			if($extra!=null){
				if($x<10){
					$cod[$x] = $extra.'.0'.$x.' - '.$y;
				}else if($x>=10 && $x<=99){
					$cod[$x] = $extra.'.'.$x.' - '.$y;
				}
			}else{

				if($x<10){
					$cod[$x] = '0'.$x.' - '.$y;
				}else if($x>=10 && $x<=9999){
					$cod[$x] = $x.' - '.$y;
				}
			}
		}
		//print_r($cod);
	}

	$this->set($nomVar, $cod);
}//fin function


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



   }//fin AddCero


   function Formato1($monto) {
    $monto = preg_replace("/[^0-9\.]/", "", str_replace(',','.',$monto));
    if (substr($monto,-3,1)=='.') {
        $sents = '.'.substr($monto,-2);
        $monto = substr($monto,0,strlen($monto)-3);
    } elseif (substr($monto,-2,1)=='.') {
        $sents = '.'.substr($monto,-1);
        $monto = substr($monto,0,strlen($monto)-2);
    } else {
        $sents = '.00';
    }
    $monto = preg_replace("/[^0-9]/", "", $monto);
    return number_format($monto.$sents,2,'.','');
    }

function Formato2($monto){
    	return number_format($monto,2,",",".");
    }



    function index(){
    $this->layout = "ajax";
	$institucion = $this->cugd02_institucion->generateList(null,'cod_institucion ASC', null, '{n}.cugd02_institucion.cod_institucion', '{n}.cugd02_institucion.denominacion');
	$this->concatena($institucion, 'institucion');

    }

function select1($select=null,$var=null) { //select codigos presupuestarios
	$this->layout = "ajax";
	//echo "si llego";
	if($var!=null){
	switch($select){
		case 'institucion':
		  $this->set('SELECT','dependencia');
		  $this->set('codigo','institucion');
		  $this->set('seleccion','');
		  $this->set('n',1);
		  $ano =  $this->Session->read('ano');
		  $cond2 ="ejercicio=".$ano." and cod_grupo=".CE."";
		  $lista=  $this->Cugd02_dependencia->generateList($cond2, 'cod_institucion ASC', null, '{n}.Cugd02_dependencia.cod_institucion', '{n}.Cugd02_dependencia.denominacion');
			$this->concatena($lista,'vector');
		break;
		case 'dependencia':
		//echo "si generica";
		  $this->set('SELECT','direccions');
		  $this->set('codigo','dependencia');
		  $this->set('seleccion','');
		  $this->set('n',2);
		 // $ano =  $this->Session->read('ano');
		  $this->Session->write('cinst',$var);
		  $cond2 ="cod_institucion=".$var;
		  $lista=  $this->cugd02_dependencia->generateList($cond2, 'cod_dependencia ASC', null, '{n}.cugd02_dependencia.cod_dependencia', '{n}.cugd02_dependencia.denominacion');
          $this->concatena($lista,'vector');
 		break;
		case 'direccions':
		//echo"si especifica";
		  $this->set('SELECT','coordinacion');
		  $this->set('codigo','direccions');
		  $this->set('seleccion','');
		  $this->set('n',3);
		//  $ano =  $this->Session->read('ano');
		  $cinst =  $this->Session->read('cinst');
		  $this->Session->write('cdepe',$var);
		  $cond2 ="cod_institucion=".$cinst." and cod_dependencia=".$var;
		 //echo $cond2;
		  $lista = $this->cugd02_direccionsuperior->generateList($cond2, 'cod_dir_superior ASC', null, '{n}.cugd02_direccionsuperior.cod_dir_superior', '{n}.cugd02_direccionsuperior.denominacion');
          $this->concatena($lista,'vector');
		break;
		case 'coordinacion':
		  $this->set('SELECT','secretaria');
		  $this->set('codigo','coordinacion');
		  $this->set('seleccion','');
		  $this->set('n',4);
		//  $ano =  $this->Session->read('ano');
		  $cinst =  $this->Session->read('cinst');
		  $cdepe =  $this->Session->read('cdepe');
		  $this->Session->write('cdirs',$var);
		  $cond2 ="cod_institucion=".$cinst." and cod_dependencia=".$cdepe." and cod_dir_superior=".$var;
		  $lista=  $this->cugd02_coordinacion->generateList($cond2, 'cod_coordinacion ASC', null, '{n}.cugd02_coordinacion.cod_coordinacion', '{n}.cugd02_coordinacion.denominacion');
          $this->concatena($lista,'vector');
		break;
		case 'secretaria':
		  $this->set('SELECT','direccion');
		  $this->set('codigo','secretaria');
		  $this->set('seleccion','');
		  $this->set('n',5);
		  $cinst =  $this->Session->read('cinst');
		  $cdepe =  $this->Session->read('cdepe');
		  $cdirs =  $this->Session->read('cdirs');
		  $this->Session->write('ccoor',$var);
		  $cond2 ="cod_institucion=".$cinst." and cod_dependencia=".$cdepe." and cod_dir_superior=".$cdirs." and cod_coordinacion=".$var;
		  $lista=  $this->cugd02_secretaria->generateList($cond2, 'cod_secretaria ASC', null, '{n}.cugd02_secretaria.cod_secretaria', '{n}.cugd02_secretaria.denominacion');
			$this->concatena($lista,'vector');
		break;
		case 'direccion':
		//echo 'si entrp';
		//echo "si generica";
		  $this->set('SELECT','division');
		  $this->set('codigo','direccion');
		  $this->set('seleccion','');
		  $this->set('n',6);
		 // $ano =  $this->Session->read('ano');
		  $cinst =  $this->Session->read('cinst');
		  $cdepe =  $this->Session->read('cdepe');
		  $cdirs =  $this->Session->read('cdirs');
		  $ccoor =  $this->Session->read('ccoor');
		  $this->Session->write('csecr',$var);
		  $cond2 ="cod_institucion=".$cinst." and cod_dependencia=".$cdepe." and cod_dir_superior=".$cdirs." and cod_coordinacion=".$ccoor." and cod_secretaria=".$var;
		  $lista=  $this->cugd02_direccion->generateList($cond2, 'cod_direccion ASC', null, '{n}.cugd02_direccion.cod_direccion', '{n}.cugd02_direccion.denominacion');
          $this->concatena($lista,'vector');
 		break;
 		case 'division':
		//echo 'si entrp';
		//echo "si generica";
		  $this->set('SELECT','departamento');
		  $this->set('codigo','division');
		  $this->set('seleccion','');
		  $this->set('n',7);
		 // $ano =  $this->Session->read('ano');
		  $cinst =  $this->Session->read('cinst');
		  $cdepe =  $this->Session->read('cdepe');
		  $cdirs =  $this->Session->read('cdirs');
		  $ccoor =  $this->Session->read('ccoor');
		  $csecr =  $this->Session->read('csecr');
		  $this->Session->write('cdire',$var);
		  $cond2 ="cod_institucion=".$cinst." and cod_dependencia=".$cdepe." and cod_dir_superior=".$cdirs." and cod_coordinacion=".$ccoor." and cod_secretaria=".$csecr." and cod_direccion=".$var;
		  $lista=  $this->cugd02_division->generateList($cond2, 'cod_division ASC', null, '{n}.cugd02_division.cod_division', '{n}.cugd02_division.denominacion');
          $this->concatena($lista,'vector');
 		break;
		case 'departamento':
		//echo"si especifica";
		  $this->set('SELECT','oficina');
		  $this->set('codigo','departamento');
		  $this->set('seleccion','');
		  $this->set('n',8);
		  $cinst =  $this->Session->read('cinst');
		  $cdepe =  $this->Session->read('cdepe');
		  $cdirs =  $this->Session->read('cdirs');
		  $ccoor =  $this->Session->read('ccoor');
		  $csecr =  $this->Session->read('csecr');
		  $cdire =  $this->Session->read('cdire');
		  $this->Session->write('cdivi',$var);
		  $cond2 ="cod_institucion=".$cinst." and cod_dependencia=".$cdepe." and cod_dir_superior=".$cdirs." and cod_coordinacion=".$ccoor." and cod_secretaria=".$csecr." and cod_direccion=".$cdire." and cod_division=".$var;
		  $lista = $this->cugd02_departamento->generateList($cond2, 'cod_departamento ASC', null, '{n}.cugd02_departamento.cod_departamento', '{n}.cugd02_departamento.denominacion');
          $this->concatena($lista,'vector');
		break;
		case 'oficina':
		  $this->set('SELECT','oficina');
		  $this->set('codigo','oficina');
		  $this->set('seleccion','');
		  $this->set('n',9);
		  $cinst =  $this->Session->read('cinst');
		  $cdepe =  $this->Session->read('cdepe');
		  $cdirs =  $this->Session->read('cdirs');
		  $ccoor =  $this->Session->read('ccoor');
		  $csecr =  $this->Session->read('csecr');
		  $cdire =  $this->Session->read('cdire');
		  $cdivi =  $this->Session->read('cdivi');
		  $this->Session->write('cdepa',$var);
		  $cond2 ="cod_institucion=".$cinst." and cod_dependencia=".$cdepe." and cod_dir_superior=".$cdirs." and cod_coordinacion=".$ccoor." and cod_secretaria=".$csecr." and cod_direccion=".$cdire." and cod_division=".$cdivi." and cod_departamento=".$var;
		  $lista=  $this->cugd02_oficina->generateList($cond2, 'cod_oficina ASC', null, '{n}.cugd02_oficina.cod_oficina', '{n}.cugd02_oficina.denominacion');
          $this->concatena($lista,'vector');
		break;
	}//fin wsitch
	}else{
		  $this->set('SELECT','');
		  $this->set('codigo','');
		  $this->set('seleccion','');
		  $this->set('n',21);
		  $this->set('no','no');
		 $this->set('vector','');
	}
}//fin select codigos presupuestarios


function mostrar1($select=null,$var=null) { //mostrar3 codigos presupuestarios
	$this->layout = "ajax";
	//echo "mostrar";
		if( $var!=null){
  //  $cond = $this->SQLCA();
    //$cond2 = $this->SQLCA();
	switch($select){
		case 'institucion':
		  //$ano =  $this->Session->read('ano');
		  $this->Session->write('dinst',$var);
		   $cond2 ="cod_institucion=".$var;
		  $a=  $this->cugd02_dependencia->findAll($cond2);
          $e=$a[0]['cugd02_dependencia']['denominacion'];
         $this->set('var',$e);
		break;
		case 'dependencia':
		  //$ano =  $this->Session->read('ano');
		  $dinst=  $this->Session->read('dinst');
		  $this->Session->write('ddepe',$var);
		  $cond2 ="cod_institucion=".$dinst." and cod_dependencia=".$var;
		  $a=  $this->cugd02_dependencia->findAll($cond2);
          $e=$a[0]['cugd02_dependencia']['denominacion'];
          $this->set('var',$e);
		break;
		case 'direccions':
		  // $ano =  $this->Session->read('ano');
		  $dinst=  $this->Session->read('dinst');
		  $ddepe =  $this->Session->read('ddepe');
		  $this->Session->write('ddirs',$var);
		  $cond2 ="cod_institucion=".$dinst." and cod_dependencia=".$ddepe." and cod_dir_superior=".$var;
		  //echo $cond2;
		  $a=  $this->cugd02_direccionsuperior->findAll($cond2);
		  //print_r($a);
          $e= $a[0]['cugd02_direccionsuperior']['denominacion'];
          $this->set('var',$e);
		break;
		case 'coordinacion':
		  //$ano =  $this->Session->read('ano');
		  $dinst=  $this->Session->read('dinst');
		  $ddepe =  $this->Session->read('ddepe');
		  $ddirs =  $this->Session->read('ddirs');
		  $this->Session->write('dcoor',$var);
		   $cond2 ="cod_institucion=".$dinst." and cod_dependencia=".$ddepe." and cod_dir_superior=".$ddirs." and cod_coordinacion=".$var;
		  $a=  $this->cugd02_coordinacion->findAll($cond2);
          $e= $a[0]['cugd02_coordinacion']['denominacion'];
           $this->set('var',$e);
		break;
		case 'secretaria':
		  //$ano =  $this->Session->read('ano');
		  $dinst=  $this->Session->read('dinst');
		  $ddepe =  $this->Session->read('ddepe');
		  $ddirs =  $this->Session->read('ddirs');
		  $dcoor =  $this->Session->read('dcoor');
		  $this->Session->write('dsecr',$var);
		   $cond2 ="cod_institucion=".$dinst." and cod_dependencia=".$ddepe." and cod_dir_superior=".$ddirs." and cod_coordinacion=".$dcoor." and cod_secretaria=".$var;
		  $a=  $this->cugd02_secretaria->findAll($cond2);
          $e=$a[0]['cugd02_secretaria']['denominacion'];
         $this->set('var',$e);
		break;
		case 'direccion':
		  //$ano =  $this->Session->read('ano');
		  $dinst=  $this->Session->read('dinst');
		  $ddepe =  $this->Session->read('ddepe');
		  $ddirs =  $this->Session->read('ddirs');
		  $dcoor =  $this->Session->read('dcoor');
		  $dsecr =  $this->Session->read('dsecr');
		  $this->Session->write('ddire',$var);
		  $cond2 ="cod_institucion=".$dinst." and cod_dependencia=".$ddepe." and cod_dir_superior=".$ddirs." and cod_coordinacion=".$dcoor." and cod_secretaria=".$dsecr." and cod_direccion=".$var;
		  $a=  $this->cugd02_direccion->findAll($cond2);
          $e=$a[0]['cugd02_direccion']['denominacion'];
          $this->set('var',$e);
		break;
		case 'division':
		  //$ano =  $this->Session->read('ano');
		  $dinst=  $this->Session->read('dinst');
		  $ddepe =  $this->Session->read('ddepe');
		  $ddirs =  $this->Session->read('ddirs');
		  $dcoor =  $this->Session->read('dcoor');
		  $dsecr =  $this->Session->read('dsecr');
		  $ddire =  $this->Session->read('ddire');
		  $this->Session->write('ddivi',$var);
		  $cond2 ="cod_institucion=".$dinst." and cod_dependencia=".$ddepe." and cod_dir_superior=".$ddirs." and cod_coordinacion=".$dcoor." and cod_secretaria=".$dsecr." and cod_direccion=".$ddire." and cod_division=".$var;
		  $a=  $this->cugd02_division->findAll($cond2);
          $e=$a[0]['cugd02_division']['denominacion'];
          $this->set('var',$e);
		break;
		case 'departamento':
		  // $ano =  $this->Session->read('ano');
		  $dinst=   $this->Session->read('dinst');
		  $ddepe =  $this->Session->read('ddepe');
		  $ddirs =  $this->Session->read('ddirs');
		  $dcoor =  $this->Session->read('dcoor');
		  $dsecr =  $this->Session->read('dsecr');
		  $ddire =  $this->Session->read('ddire');
		  $this->Session->write('ddepa',$var);
		  $cond2 ="cod_institucion=".$dinst." and cod_dependencia=".$ddepe." and cod_dir_superior=".$ddirs." and cod_coordinacion=".$dcoor." and cod_secretaria=".$dsecr." and cod_direccion=".$ddire." and cod_departamento=".$var;
		  //echo $cond2;
		  $a=  $this->cugd02_departamento->findAll($cond2);
		  //print_r($a);
          $e= $a[0]['cugd02_departamento']['denominacion'];
          $this->set('var',$e);
		break;
		case 'oficina':
		  //$ano =  $this->Session->read('ano');
		  $dinst=   $this->Session->read('dinst');
		  $ddepe =  $this->Session->read('ddepe');
		  $ddirs =  $this->Session->read('ddirs');
		  $dcoor =  $this->Session->read('dcoor');
		  $dsecr =  $this->Session->read('dsecr');
		  $ddire =  $this->Session->read('ddire');
		  $ddepa =  $this->Session->read('ddepa');
		  $this->Session->write('dofic',$var);
		  $cond2 ="cod_institucion=".$dinst." and cod_dependencia=".$ddepe." and cod_dir_superior=".$ddirs." and cod_coordinacion=".$dcoor." and cod_secretaria=".$dsecr." and cod_direccion=".$ddire." and cod_departamento=".$ddepa." and cod_oficina=".$var;
		  $a=  $this->cugd02_oficina->findAll($cond2);
          $e= $a[0]['cugd02_oficina']['denominacion'];
           $this->set('var',$e);
		break;
	}//fin wsitch
	}else{
		echo "";
	}
}//fin mostrar3 codigos presupuestarios

function mostrar2($select=null,$var=null) { //mostrar3 codigos presupuestarios
	$this->layout = "ajax";
	//echo "mostrar";
	//echo $select,$var;
		if( $var!=null){
  //  $cond = $this->SQLCA();
    //$cond2 = $this->SQLCA();
	switch($select){
		case 'institucion':
          	echo "<input type='text' name='data[cimp01_cambio_uadmin][cod_institucion]' value='".$this->AddCero3($var)."' id='editar1'  class='inputtext' readonly=readonly/>";
		break;

		case 'dependencia':
			 echo "<input type='text' name='data[cimp01_cambio_uadmin][cod_dependencia]' value='".$this->AddCero3($var)."' id='editar2'  class='inputtext' readonly=readonly/>";
		break;

		case 'direccions':
           echo "<input type='text' name='data[cimp01_cambio_uadmin][cod_direccions]' value='".$this->AddCero3($var)."' id='editar3' class='inputtext' readonly=readonly/>";
		break;

		case 'coordinacion':
           echo "<input type='text' name='data[cimp01_cambio_uadmin][cod_coordinacion]' value='".$this->AddCero3($var)."' class='inputtext' readonly=readonly/>";
		break;
		case 'secretaria':
          	echo "<input type='text' name='data[cimp01_cambio_uadmin][cod_secretaria]' value='".$this->AddCero3($var)."' id='editar1'  class='inputtext' readonly=readonly/>";
		break;

		case 'direccion':
			 echo "<input type='text' name='data[cimp01_cambio_uadmin][cod_direccion]' value='".$this->AddCero3($var)."' id='editar2'  class='inputtext' readonly=readonly/>";
		break;

		case 'division':
			 echo "<input type='text' name='data[cimp01_cambio_uadmin][cod_division]' value='".$this->AddCero3($var)."' id='editar2'  class='inputtext' readonly=readonly/>";
		break;

		case 'departamento':
           echo "<input type='text' name='data[cimp01_cambio_uadmin][cod_departamento]' value='".$this->AddCero3($var)."' id='editar3' class='inputtext' readonly=readonly/>";
		break;

		case 'oficina':
           echo "<input type='text' name='data[cimp01_cambio_uadmin][cod_oficina]' value='".$this->AddCero3($var)."' class='inputtext' readonly=readonly/>";
		break;

	}//fin wsitch
	}else{
		echo "";
	}
}//fin mostrar3 codigos presupuestarios


function AddCero3($numero,$extra=null){
   	  if($extra==null){
   	  	$numero = ($numero < 10 ? "0".$numero : $numero);
   	  }else{
   	  	$numero = ($numero < 10 ? $extra."0".$numero : $extra.".".$numero);
   	  }
	    return $numero;
   }//fin AddCero

function select2($select=null,$var=null) { //select codigos presupuestarios
	$this->layout = "ajax";
	//echo "si llego";
	if($var!=null){
	switch($select){
		case 'institucion':
		  $this->set('SELECT','dependencia');
		  $this->set('codigo','institucion');
		  $this->set('seleccion','');
		  $this->set('n',10);
		  $ano =  $this->Session->read('ano');
		  $cond2 ="ejercicio=".$ano." and cod_grupo=".CE."";
		  $lista=  $this->Cugd02_dependencia->generateList($cond2, 'cod_institucion ASC', null, '{n}.Cugd02_dependencia.cod_institucion', '{n}.Cugd02_dependencia.denominacion');
			$this->concatena($lista,'vector');
		break;
		case 'dependencia':
		//echo "si generica";
		  $this->set('SELECT','direccions');
		  $this->set('codigo','dependencia');
		  $this->set('seleccion','');
		  $this->set('n',11);
		 // $ano =  $this->Session->read('ano');
		  $this->Session->write('cinst',$var);
		  $cond2 ="cod_institucion=".$var;
		  $lista=  $this->cugd02_dependencia->generateList($cond2, 'cod_dependencia ASC', null, '{n}.cugd02_dependencia.cod_dependencia', '{n}.cugd02_dependencia.denominacion');
          $this->concatena($lista,'vector');
 		break;
		case 'direccions':
		//echo"si especifica";
		  $this->set('SELECT','coordinacion');
		  $this->set('codigo','direccions');
		  $this->set('seleccion','');
		  $this->set('n',12);
		//  $ano =  $this->Session->read('ano');
		  $cinst =  $this->Session->read('cinst');
		  $this->Session->write('cdepe',$var);
		  $cond2 ="cod_institucion=".$cinst." and cod_dependencia=".$var;
		 //echo $cond2;
		  $lista = $this->cugd02_direccionsuperior->generateList($cond2, 'cod_dir_superior ASC', null, '{n}.cugd02_direccionsuperior.cod_dir_superior', '{n}.cugd02_direccionsuperior.denominacion');
          $this->concatena($lista,'vector');
		break;
		case 'coordinacion':
		  $this->set('SELECT','secretaria');
		  $this->set('codigo','coordinacion');
		  $this->set('seleccion','');
		  $this->set('n',13);
		//  $ano =  $this->Session->read('ano');
		  $cinst =  $this->Session->read('cinst');
		  $cdepe =  $this->Session->read('cdepe');
		  $this->Session->write('cdirs',$var);
		  $cond2 ="cod_institucion=".$cinst." and cod_dependencia=".$cdepe." and cod_dir_superior=".$var;
		  $lista=  $this->cugd02_coordinacion->generateList($cond2, 'cod_coordinacion ASC', null, '{n}.cugd02_coordinacion.cod_coordinacion', '{n}.cugd02_coordinacion.denominacion');
          $this->concatena($lista,'vector');
		break;
		case 'secretaria':
		  $this->set('SELECT','direccion');
		  $this->set('codigo','secretaria');
		  $this->set('seleccion','');
		  $this->set('n',14);
		  $cinst =  $this->Session->read('cinst');
		  $cdepe =  $this->Session->read('cdepe');
		  $cdirs =  $this->Session->read('cdirs');
		  $this->Session->write('ccoor',$var);
		  $cond2 ="cod_institucion=".$cinst." and cod_dependencia=".$cdepe." and cod_dir_superior=".$cdirs." and cod_coordinacion=".$var;
		  $lista=  $this->cugd02_secretaria->generateList($cond2, 'cod_secretaria ASC', null, '{n}.cugd02_secretaria.cod_secretaria', '{n}.cugd02_secretaria.denominacion');
			$this->concatena($lista,'vector');
		break;
		case 'direccion':
		//echo 'si entrp';
		//echo "si generica";
		  $this->set('SELECT','division');
		  $this->set('codigo','direccion');
		  $this->set('seleccion','');
		  $this->set('n',15);
		 // $ano =  $this->Session->read('ano');
		  $cinst =  $this->Session->read('cinst');
		  $cdepe =  $this->Session->read('cdepe');
		  $cdirs =  $this->Session->read('cdirs');
		  $ccoor =  $this->Session->read('ccoor');
		  $this->Session->write('csecr',$var);
		  $cond2 ="cod_institucion=".$cinst." and cod_dependencia=".$cdepe." and cod_dir_superior=".$cdirs." and cod_coordinacion=".$ccoor." and cod_secretaria=".$var;
		  $lista=  $this->cugd02_direccion->generateList($cond2, 'cod_direccion ASC', null, '{n}.cugd02_direccion.cod_direccion', '{n}.cugd02_direccion.denominacion');
          $this->concatena($lista,'vector');
 		break;
 		case 'division':
		//echo 'si entrp';
		//echo "si generica";
		  $this->set('SELECT','departamento');
		  $this->set('codigo','division');
		  $this->set('seleccion','');
		  $this->set('n',16);
		 // $ano =  $this->Session->read('ano');
		  $cinst =  $this->Session->read('cinst');
		  $cdepe =  $this->Session->read('cdepe');
		  $cdirs =  $this->Session->read('cdirs');
		  $ccoor =  $this->Session->read('ccoor');
		  $csecr =  $this->Session->read('csecr');
		  $this->Session->write('cdire',$var);
		  $cond2 ="cod_institucion=".$cinst." and cod_dependencia=".$cdepe." and cod_dir_superior=".$cdirs." and cod_coordinacion=".$ccoor." and cod_secretaria=".$csecr." and cod_direccion=".$var;
		  $lista=  $this->cugd02_division->generateList($cond2, 'cod_division ASC', null, '{n}.cugd02_division.cod_division', '{n}.cugd02_division.denominacion');
          $this->concatena($lista,'vector');
 		break;
		case 'departamento':
		//echo"si especifica";
		  $this->set('SELECT','oficina');
		  $this->set('codigo','departamento');
		  $this->set('seleccion','');
		  $this->set('n',17);
		  $cinst =  $this->Session->read('cinst');
		  $cdepe =  $this->Session->read('cdepe');
		  $cdirs =  $this->Session->read('cdirs');
		  $ccoor =  $this->Session->read('ccoor');
		  $csecr =  $this->Session->read('csecr');
		  $cdire =  $this->Session->read('cdire');
		  $this->Session->write('cdivi',$var);
		  $cond2 ="cod_institucion=".$cinst." and cod_dependencia=".$cdepe." and cod_dir_superior=".$cdirs." and cod_coordinacion=".$ccoor." and cod_secretaria=".$csecr." and cod_direccion=".$cdire." and cod_division=".$var;
		  $lista = $this->cugd02_departamento->generateList($cond2, 'cod_departamento ASC', null, '{n}.cugd02_departamento.cod_departamento', '{n}.cugd02_departamento.denominacion');
          $this->concatena($lista,'vector');
		break;
		case 'oficina':
		  $this->set('SELECT','oficina');
		  $this->set('codigo','oficina');
		  $this->set('seleccion','');
		  $this->set('n',18);
		  $cinst =  $this->Session->read('cinst');
		  $cdepe =  $this->Session->read('cdepe');
		  $cdirs =  $this->Session->read('cdirs');
		  $ccoor =  $this->Session->read('ccoor');
		  $csecr =  $this->Session->read('csecr');
		  $cdire =  $this->Session->read('cdire');
		  $cdivi =  $this->Session->read('cdivi');
		  $this->Session->write('cdepa',$var);
		  $cond2 ="cod_institucion=".$cinst." and cod_dependencia=".$cdepe." and cod_dir_superior=".$cdirs." and cod_coordinacion=".$ccoor." and cod_secretaria=".$csecr." and cod_direccion=".$cdire." and cod_division=".$cdivi." and cod_departamento=".$var;
		  $lista=  $this->cugd02_oficina->generateList($cond2, 'cod_oficina ASC', null, '{n}.cugd02_oficina.cod_oficina', '{n}.cugd02_oficina.denominacion');
          $this->concatena($lista,'vector');
		break;
	}//fin wsitch
	}else{
		  $this->set('SELECT','');
		  $this->set('codigo','');
		  $this->set('seleccion','');
		  $this->set('n',22);
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
		case 'institucion':
		  //$ano =  $this->Session->read('ano');
		  $this->Session->write('dinst',$var);
		   $cond2 ="cod_institucion=".$var;
		  $a=  $this->cugd02_dependencia->findAll($cond2);
          $e=$a[0]['cugd02_dependencia']['denominacion'];
         $this->set('var',$e);
		break;
		case 'dependencia':
		  //$ano =  $this->Session->read('ano');
		  $dinst=  $this->Session->read('dinst');
		  $this->Session->write('ddepe',$var);
		  $cond2 ="cod_institucion=".$dinst." and cod_dependencia=".$var;
		  $a=  $this->cugd02_dependencia->findAll($cond2);
          $e=$a[0]['cugd02_dependencia']['denominacion'];
          $this->set('var',$e);
		break;
		case 'direccions':
		  // $ano =  $this->Session->read('ano');
		  $dinst=  $this->Session->read('dinst');
		  $ddepe =  $this->Session->read('ddepe');
		  $this->Session->write('ddirs',$var);
		  $cond2 ="cod_institucion=".$dinst." and cod_dependencia=".$ddepe." and cod_dir_superior=".$var;
		  //echo $cond2;
		  $a=  $this->cugd02_direccionsuperior->findAll($cond2);
		  //print_r($a);
          $e= $a[0]['cugd02_direccionsuperior']['denominacion'];
          $this->set('var',$e);
		break;
		case 'coordinacion':
		  //$ano =  $this->Session->read('ano');
		  $dinst=  $this->Session->read('dinst');
		  $ddepe =  $this->Session->read('ddepe');
		  $ddirs =  $this->Session->read('ddirs');
		  $this->Session->write('dcoor',$var);
		   $cond2 ="cod_institucion=".$dinst." and cod_dependencia=".$ddepe." and cod_dir_superior=".$ddirs." and cod_coordinacion=".$var;
		  $a=  $this->cugd02_coordinacion->findAll($cond2);
          $e= $a[0]['cugd02_coordinacion']['denominacion'];
           $this->set('var',$e);
		break;
		case 'secretaria':
		  //$ano =  $this->Session->read('ano');
		  $dinst=  $this->Session->read('dinst');
		  $ddepe =  $this->Session->read('ddepe');
		  $ddirs =  $this->Session->read('ddirs');
		  $dcoor =  $this->Session->read('dcoor');
		  $this->Session->write('dsecr',$var);
		   $cond2 ="cod_institucion=".$dinst." and cod_dependencia=".$ddepe." and cod_dir_superior=".$ddirs." and cod_coordinacion=".$dcoor." and cod_secretaria=".$var;
		  $a=  $this->cugd02_secretaria->findAll($cond2);
          $e=$a[0]['cugd02_secretaria']['denominacion'];
         $this->set('var',$e);
		break;
		case 'direccion':
		  //$ano =  $this->Session->read('ano');
		  $dinst=  $this->Session->read('dinst');
		  $ddepe =  $this->Session->read('ddepe');
		  $ddirs =  $this->Session->read('ddirs');
		  $dcoor =  $this->Session->read('dcoor');
		  $dsecr =  $this->Session->read('dsecr');
		  $this->Session->write('ddire',$var);
		  $cond2 ="cod_institucion=".$dinst." and cod_dependencia=".$ddepe." and cod_dir_superior=".$ddirs." and cod_coordinacion=".$dcoor." and cod_secretaria=".$dsecr." and cod_direccion=".$var;
		  $a=  $this->cugd02_direccion->findAll($cond2);
          $e=$a[0]['cugd02_direccion']['denominacion'];
          $this->set('var',$e);
		break;
		case 'division':
		  //$ano =  $this->Session->read('ano');
		  $dinst=  $this->Session->read('dinst');
		  $ddepe =  $this->Session->read('ddepe');
		  $ddirs =  $this->Session->read('ddirs');
		  $dcoor =  $this->Session->read('dcoor');
		  $dsecr =  $this->Session->read('dsecr');
		  $ddire =  $this->Session->read('ddire');
		  $this->Session->write('ddivi',$var);
		  $cond2 ="cod_institucion=".$dinst." and cod_dependencia=".$ddepe." and cod_dir_superior=".$ddirs." and cod_coordinacion=".$dcoor." and cod_secretaria=".$dsecr." and cod_direccion=".$ddire." and cod_division=".$var;
		  $a=  $this->cugd02_division->findAll($cond2);
          $e=$a[0]['cugd02_division']['denominacion'];
          $this->set('var',$e);
		break;
		case 'departamento':
		  // $ano =  $this->Session->read('ano');
		  $dinst=   $this->Session->read('dinst');
		  $ddepe =  $this->Session->read('ddepe');
		  $ddirs =  $this->Session->read('ddirs');
		  $dcoor =  $this->Session->read('dcoor');
		  $dsecr =  $this->Session->read('dsecr');
		  $ddire =  $this->Session->read('ddire');
		  $this->Session->write('ddepa',$var);
		  $cond2 ="cod_institucion=".$dinst." and cod_dependencia=".$ddepe." and cod_dir_superior=".$ddirs." and cod_coordinacion=".$dcoor." and cod_secretaria=".$dsecr." and cod_direccion=".$ddire." and cod_departamento=".$var;
		  //echo $cond2;
		  $a=  $this->cugd02_departamento->findAll($cond2);
		  //print_r($a);
          $e= $a[0]['cugd02_departamento']['denominacion'];
          $this->set('var',$e);
		break;
		case 'oficina':
		  //$ano =  $this->Session->read('ano');
		  $dinst=   $this->Session->read('dinst');
		  $ddepe =  $this->Session->read('ddepe');
		  $ddirs =  $this->Session->read('ddirs');
		  $dcoor =  $this->Session->read('dcoor');
		  $dsecr =  $this->Session->read('dsecr');
		  $ddire =  $this->Session->read('ddire');
		  $ddepa =  $this->Session->read('ddepa');
		  $this->Session->write('dofic',$var);
		  $cond2 ="cod_institucion=".$dinst." and cod_dependencia=".$ddepe." and cod_dir_superior=".$ddirs." and cod_coordinacion=".$dcoor." and cod_secretaria=".$dsecr." and cod_direccion=".$ddire." and cod_departamento=".$ddepa." and cod_oficina=".$var;
		  $a=  $this->cugd02_oficina->findAll($cond2);
          $e= $a[0]['cugd02_oficina']['denominacion'];
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
		case 'institucion':
          	echo "<input type='text' name='data[cimp01_cambio_uadmin][cod_institucion]' value='".$this->AddCero3($var)."' id='editar1'  class='inputtext' readonly=readonly/>";
		break;

		case 'dependencia':
			 echo "<input type='text' name='data[cimp01_cambio_uadmin][cod_dependencia]' value='".$this->AddCero3($var)."' id='editar2'  class='inputtext' readonly=readonly/>";
		break;

		case 'direccions':
           echo "<input type='text' name='data[cimp01_cambio_uadmin][cod_direccions]' value='".$this->AddCero3($var)."' id='editar3' class='inputtext' readonly=readonly/>";
		break;

		case 'coordinacion':
           echo "<input type='text' name='data[cimp01_cambio_uadmin][cod_coordinacion]' value='".$this->AddCero3($var)."' class='inputtext' readonly=readonly/>";
		break;
		case 'secretaria':
          	echo "<input type='text' name='data[cimp01_cambio_uadmin][cod_secretaria]' value='".$this->AddCero3($var)."' id='editar1'  class='inputtext' readonly=readonly/>";
		break;

		case 'direccion':
			 echo "<input type='text' name='data[cimp01_cambio_uadmin][cod_direccion]' value='".$this->AddCero3($var)."' id='editar2'  class='inputtext' readonly=readonly/>";
		break;

		case 'division':
			 echo "<input type='text' name='data[cimp01_cambio_uadmin][cod_division]' value='".$this->AddCero3($var)."' id='editar2'  class='inputtext' readonly=readonly/>";
		break;

		case 'departamento':
           echo "<input type='text' name='data[cimp01_cambio_uadmin][cod_departamento]' value='".$this->AddCero3($var)."' id='editar3' class='inputtext' readonly=readonly/>";
		break;

		case 'oficina':
           echo "<input type='text' name='data[cimp01_cambio_uadmin][cod_oficina]' value='".$this->AddCero3($var)."' class='inputtext' readonly=readonly/>";
		break;

	}//fin wsitch
	}else{
		echo "";
	}
}//fin mostrar3 codigos presupuestarios

}