<?php
/*
 * Creado el 17/10/2007 a las 11:58:11 AM por migue
 * Para CakePHP, PostgresSQL
 */
 class Ccfp04CuentasEnlaceController extends AppController {

   var $name='ccfp04_cuentas_enlace';
   var $uses = array('ccfd04_cierre_mes','Usuario', "cugd02_dependencia",'ccfd01_tipo', 'ccfd01_cuenta', 'cstd02_cuentas_bancarias',
                     'ccfd01_subcuenta', 'ccfd01_division', 'ccfd01_subdivision','ccfd04_cuentas_enlace');
   var $helpers = array('Html','Ajax','Javascript','Sisap');

function checkSession(){
				if (!$this->Session->check('Usuario')){
						$this->redirect('/salir/');
						exit();
				}else{
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
	$condicion2 = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep;
	$opc = $this->Usuario->findCount($condicion2);

	if($cod_dep == '01' || ($cod_dep != '01' && $modulo==0)){
		return;
	}else{
 		echo "<h3>LO SIENTO - UD. NO TIENE PERMISOS PARA ESTE PROCESO!!</h3>";
		exit;
	}
 }
 function verifica_SS($i){
    	/*******************************************
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
         $sql_re .= "cod_inst=".$this->verifica_SS(4)." and ";
         $sql_re .= "cod_dep=".$this->verifica_SS(5);
         return $sql_re;
    }//fin funcion SQLCA

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
		if($monto<10){
			return number_format($monto);
		}
    	return number_format($monto,2,",",".");
    }

function concatena0($vector1=null, $nomVar=null, $extra=null){
	$cod = array();
	if($vector1 != null){
		foreach($vector1 as $x => $y){
			if($extra!=null){
				if($x<10){
					$cod[$x] = $extra.'0'.$x.' - '.$y;
				}else if($x>=10 && $x<=99){
					$cod[$x] = $extra.$x.' - '.$y;
				}else if($x>=100 && $x<=999){
					$cod[$x] = $x.' - '.$y;
				}
			}else{

				if($x<10){
					$cod[$x] = $x.' - '.$y;
				}else if($x>=10){
					$cod[$x] = $x.' - '.$y;
				}
			}
		}
	}

	$this->set($nomVar, $cod);
}//fin function

function concatena4($vector1=null, $nomVar=null, $extra=null){
	$cod = array();
	if($vector1 != null){
		foreach($vector1 as $x => $y){
			if($extra!=null){
				if($x<10){
					$cod[$x] = $extra.'0'.$x.' - '.$y;
				}else if($x>=10 && $x<=99){
					$cod[$x] = $extra.$x.' - '.$y;
				}else if($x>=100 && $x<=999){
					$cod[$x] = '0'.$x.' - '.$y;
				}else if($x>=1000){
					$cod[$x] = $x.' - '.$y;
				}
			}else{

				if($x<10){
					$cod[$x] = $x.' - '.$y;
				}else if($x>=10){
					$cod[$x] = $x.' - '.$y;
				}
			}
		}
		//print_r($cod);
	}

	$this->set($nomVar, $cod);
}//fin function

function index($id=null){
    $this->layout = "ajax";
	$this->set('entidad_federal', $this->Session->read('entidad_federal'));
	$this->set('formulacion', $this->ccfd04_cierre_mes->findAll($this->SQLCA()));

	//Este ciclo hace una consula antes de agregar para validar si ya el dato fue agregado con anterioridad
	$consulta="select * from ccfd03_instalacion where ".$this->SQLCA();
	if($this->ccfd04_cierre_mes->execute($consulta)){

		//setea la variable para luego examinarla si existe o no
		$this->set('existe',true);

    if (empty($this->data)){
    	$dato=$this->ccfd04_cierre_mes->findAll($this->SQLCA());
    	foreach($dato as $dato){
    		$ano_ejecucion=$dato['ccfd04_cierre_mes']['ano_arranque'];
    		$mes_ejecucion=$this->AddCeroR($dato['ccfd04_cierre_mes']['mes_arranque'],null);
    	}
    	 $this->set('ano_ejecucion',$ano_ejecucion);
    	 $this->set('mes_ejecucion',$mes_ejecucion);

    }

	}else{
		$this->set('existe',false);
	}


	$ano=$this->ano_ejecucion();
	$datos=$this->ccfd04_cuentas_enlace->execute("select * from ccfd04_cuentas_enlace where ".$this->SQLCA()." and ano_fiscal='$ano' order by cod_tipo_enlace asc");
 	if($datos!=null){
		$this->set('datos',$datos);
 	}else{
		$this->set('datos',null);
 	}

 	 $cond= $this->condicionNDEP()." and cod_dep=1";
 	 $lista = $this->ccfd01_tipo->generateList($cond, 'cod_tipo_cuenta ASC', null, '{n}.ccfd01_tipo.cod_tipo_cuenta', '{n}.ccfd01_tipo.denominacion');
 	 $this->concatena_sin_cero($lista, 'vector1');

 	 $cond.=" and cod_tipo_cuenta=1";
	 $lista1 = $this->ccfd01_cuenta->generateList($cond, 'cod_cuenta ASC', null, '{n}.ccfd01_cuenta.cod_cuenta', '{n}.ccfd01_cuenta.denominacion');
	 $this->concatena_tres_digitos($lista1, 'vector5');

 	 $cond.=" and cod_cuenta=132";
	 $lista=  $this->ccfd01_subcuenta->generateList($cond, 'cod_subcuenta ASC', null, '{n}.ccfd01_subcuenta.cod_subcuenta', '{n}.ccfd01_subcuenta.denominacion');
	 $this->concatena_tres_digitos($lista, 'vector3');

	 $a= $this->ccfd01_subcuenta->execute("select denominacion from ccfd01_subcuenta where cod_tipo_cuenta=1 and cod_cuenta=102 and cod_subcuenta=1");
	$this->set('denominacion',$a[0][0]['denominacion']);

    $lista =  $this->cstd02_cuentas_bancarias->findAll($this->condicion()." and  tipo_cuenta=2 ");

    if(count($lista)>0){
        $cont = " and cod_division IN (";
        $i=0;
        foreach($lista as $ve){
        	   $i++;
        	if($i==1){
        		$cont .= $ve["cstd02_cuentas_bancarias"]["tesoro_division"];
        	}else{
                $cont .= ", ".$ve["cstd02_cuentas_bancarias"]["tesoro_division"];
        	}
        }
        $cont .=")";
    }else{
    	$cont = "";
    }

	$cond.=" and cod_subcuenta=1";
  //$lista = $this->ccfd01_division->generateList($cond." ".$cont, 'cod_division ASC', null, '{n}.ccfd01_division.cod_division', '{n}.ccfd01_division.denominacion');
    $lista = $this->ccfd01_division->generateList($cond, 'cod_division ASC', null, '{n}.ccfd01_division.cod_division', '{n}.ccfd01_division.denominacion');
	$this->concatena_cuatro_digitos($lista, 'vector4');


	 $tipo=$this->Session->write('tipo',1);
	 $cuenta=$this->Session->write('cuenta',132);
	 $subcuenta=$this->Session->write('subcuenta',1);

 	$vec=array('01'=>'SEGURO SOCIAL OBLIGATORIO',
               '02'=>'PARO FORZOSO',
               '03'=>'LEY DE POLÍTICA HABITACIONAL',
               '04'=>'FONDO DE PENSIÓN Y JUBILACIÓN',
 			   '05'=>'CAJA DE AHORROS',
 			   '06'=>'SINDICATOS Y GREMIOS',
 			   '07'=>'JUZGADOS Y TRIBUNALES',
 			   '08'=>'CASAS COMERCIALES',
               '50'=>'RETENCIÓN DE IVA',
               '51'=>'RETENCIÓN DE ISLR',
               '52'=>'RETENCIÓN DE TIMBRE FISCAL',
               '53'=>'RETENCIÓN DE IMPUESTO MUNICIPAL',
 			   '54'=>'RETENCIÓN POR RESPONSABILIDAD CIVIL',
               '55'=>'RETENCIÓN POR RESPONSABILIDAD SOCIAL',
               '99'=>'OTRAS RETENCIONES');
 	$this->set('vec',$vec);
 }//fin

 function mostrar($var=null){
 	$this->layout = "ajax";

 	$vec=array('01'=>'SEGURO SOCIAL OBLIGATORIO',
               '02'=>'PARO FORZOSO',
               '03'=>'LEY DE POLÍTICA HABITACIONAL',
               '04'=>'FONDO DE PENSIÓN Y JUBILACIÓN',
 			   '05'=>'CAJA DE AHORROS',
 			   '06'=>'SINDICATOS Y GREMIOS',
 			   '07'=>'JUZGADOS Y TRIBUNALES',
 			   '08'=>'CASAS COMERCIALES',
               '50'=>'RETENCIÓN DE IVA',
               '51'=>'RETENCIÓN DE ISLR',
               '52'=>'RETENCIÓN DE TIMBRE FISCAL',
               '53'=>'RETENCIÓN DE IMPUESTO MUNICIPAL',
 			   '54'=>'RETENCIÓN POR RESPONSABILIDAD CIVIL',
               '55'=>'RETENCIÓN POR RESPONSABILIDAD SOCIAL',
               '99'=>'OTRAS RETENCIONES');
 	if($var!=null){
 		echo "<script>document.getElementById('deno_tipo_enlace').value='".$vec[$var]."';</script>";
 	}else{
		echo "<script>document.getElementById('deno_tipo_enlace').value='';</script>";
 	}
 }

   function index2() {
   	$this->layout = "ajax";

   	$cond= $this->condicionNDEP()." and cod_dep=1";
	$lista = $this->ccfd01_tipo->generateList($cond, 'cod_tipo_cuenta ASC', null, '{n}.ccfd01_tipo.cod_tipo_cuenta', '{n}.ccfd01_tipo.denominacion');
	 $this->concatena0($lista, 'vector');

	$ano=$this->ano_ejecucion();
	if($ano!=null){
		$dato=$this->ccfd04_cuentas_enlace->execute("select * from ccfd04_cuentas_enlace where ano_fiscal=".$ano);
		if($dato!=null){
			$this->set('datos',$dato);
		}else{
			$this->set('datos',null);
		}
	}else{
		$this->set('datos',null);
	}


   }//fin


   function select3($select=null,$var=null) {
 $this->layout = "ajax";
 	if($var!=null){
 	$cond= $this->condicionNDEP()." and cod_dep=1";
 	switch($select){
 		case 'tipo':
					 $this->set('SELECT','contable');//El parametro que se le pasa para que busque el proximo select (cuando entre en select3)
					 $this->set('codigo','tipo_cuenta');
					 $this->set('seleccion','');
					 $this->set('n',1);
					 $this->Session->write('radio',$var);
					 $lista = $this->ccfd01_tipo->generateList($cond, 'cod_tipo_cuenta ASC', null, '{n}.ccfd01_tipo.cod_tipo_cuenta', '{n}.ccfd01_tipo.denominacion');
			   		 $this->concatena($lista, 'vector');
			  break;
		case 'contable':
					 $this->set('SELECT','subcuenta');//El parametro que se le pasa para que busque el proximo select (cuando entre en select3)
					 $this->set('codigo','contable');
					 $this->set('seleccion','');
					 $this->set('n',2);
					 $this->Session->write('tipo',$var);
					 $cond.=" and cod_tipo_cuenta=".$var;
					 $lista = $this->ccfd01_cuenta->generateList($cond, 'cod_cuenta ASC', null, '{n}.ccfd01_cuenta.cod_cuenta', '{n}.ccfd01_cuenta.denominacion');
			   		 $this->concatena($lista, 'vector');
			  break;
		case 'subcuenta':
					 $this->set('SELECT','div_contable');
					 $this->set('codigo','subcuenta');
					 $this->set('seleccion','');
					 $this->set('n',3);
					 $this->Session->write('cuenta',$var);
					 $tipo=$this->Session->read('tipo');
					 $cond.=" and cod_tipo_cuenta=".$tipo." and cod_cuenta=".$var;
					 $lista=  $this->ccfd01_subcuenta->generateList($cond, 'cod_subcuenta ASC', null, '{n}.ccfd01_subcuenta.cod_subcuenta', '{n}.ccfd01_subcuenta.denominacion');
//			         $this->concatena0($lista, 'vector','0');
			          if($lista){
			          	 	$this->concatena_tres_digitos($lista, 'vector');
							echo "<script>";
								echo "document.getElementById('st_select_4').innerHTML='<select></select>';";
			   		 			echo "document.getElementById('st_select_5').innerHTML='<select></select>';";
			   		 			echo "document.getElementById('agregar').disabled='disabled';";
							echo "</script>";
			   		 }else{
			   		 	echo "<script>";
			   		 			echo "document.getElementById('st_select_3').innerHTML='<select><option value=000>000</option></select>';";
			   		 			echo "document.getElementById('st_select_4').innerHTML='<select><option value=0000>0000</option></select>';";
			   		 			echo "document.getElementById('st_select_5').innerHTML='<select><option value=000>000</option></select>';";
								echo "document.getElementById('agregar').disabled=false;";
						echo "</script>";
			          }
			    break;
	     case 'div_contable':
				  		$this->set('SELECT','subdiv_estadistica_contable');
				  		$this->set('codigo','div_estadistica_contable');
				  		$this->set('seleccion','');
				  		$this->set('n',4);
				  		$this->Session->write('subcuenta',$var);
				  		$tipo=$this->Session->read('tipo');
				  		$cuenta=$this->Session->read('cuenta');
				  		$cond.=" and cod_tipo_cuenta=".$tipo." and cod_cuenta=".$cuenta." and cod_subcuenta=".$var;
				  		$lista = $this->ccfd01_division->generateList($cond, 'cod_division ASC', null, '{n}.ccfd01_division.cod_division', '{n}.ccfd01_division.denominacion');
//		   		  		$this->concatena4($lista, 'vector','00');
						 if($lista){
				          	 	$this->concatena_cuatro_digitos($lista, 'vector');
								echo "<script>";
									echo "document.getElementById('st_select_5').innerHTML='<select></select>';";
									echo "document.getElementById('agregar').disabled='disabled';";
								echo "</script>";
				   		 }else{
				   		 	echo "<script>";
				   		 			echo "document.getElementById('st_select_4').innerHTML='<select><option value=0000>0000</option></select>';";
				   		 			echo "document.getElementById('st_select_5').innerHTML='<select><option value=000>000</option></select>';";
									echo "document.getElementById('agregar').disabled=false;";
							echo "</script>";
						 }
			  		break;
		 case 'subdiv_estadistica_contable':
				  		$this->set('SELECT','direccion');
				  		$this->set('codigo','subdiv_estadistica_contable');
				  		$this->set('seleccion','');
				  		$this->set('no','no');
				  		$this->set('n',5);
				  		$tipo=$this->Session->read('tipo');
				  		$cuenta=$this->Session->read('cuenta');
				  		$subcuenta=$this->Session->read('subcuenta');
				  		$this->Session->write('div_contable',$var);

				  		$lista =  $this->cstd02_cuentas_bancarias->findAll($this->condicion()." and  tipo_cuenta=2 and tesoro_division='".$var."'");

					    if(count($lista)>0){
					        $cont = " and cod_subdivision IN (";
					        $i=0;
					        foreach($lista as $ve){
					        	   $i++;
					        	if($i==1){
					        		$cont .= $ve["cstd02_cuentas_bancarias"]["tesoro_subdivision"];
					        	}else{
					                $cont .= ", ".$ve["cstd02_cuentas_bancarias"]["tesoro_subdivision"];
					        	}
					        }
					        $cont .=")";
					    }else{
					    	$cont = "";
    }

				  		$cond.=" and cod_tipo_cuenta=".$tipo." and cod_cuenta=".$cuenta." and cod_subcuenta=".$subcuenta." and cod_division=".$var;
				  	  //$lista = $this->ccfd01_subdivision->generateList($cond." ".$cont, 'cod_division ASC', null, '{n}.ccfd01_subdivision.cod_subdivision', '{n}.ccfd01_subdivision.denominacion');
				  	     $lista = $this->ccfd01_subdivision->generateList($cond, 'cod_division ASC', null, '{n}.ccfd01_subdivision.cod_subdivision', '{n}.ccfd01_subdivision.denominacion');

						if($lista){
				          	 	$this->concatena_tres_digitos($lista, 'vector');
								echo "<script>";
									echo "document.getElementById('agregar').disabled='disabled';";
								echo "</script>";
				   		 }else{
				   		 	echo "<script>";
				   		 			echo "document.getElementById('st_select_5').innerHTML='<select><option value=000>000</option></select>';";
				   		 			echo "document.getElementById('agregar').disabled=false;";
							echo "</script>";
						}
			  		break;
			  		 case 'direccion':
				  		$this->set('ver','');
			  		break;
	}//fin switch
	}else{
		  $this->set('SELECT','');
		  $this->set('codigo','');
		  $this->set('seleccion','');
		  $this->set('n',10);
		  $this->set('no','no');
		  $this->set('vector','');
		  echo "<script>";
	 			echo "document.getElementById('agregar').disabled='disabled';";
		echo "</script>";
	}
 }//fin function select3


function muestra($opcion=null,$var=null){
	$this->layout = "ajax";
	//$cond= $this->condicionNDEP()." and cod_dep=1";
	$cond= $this->condicionNDEP()." and cod_dep=1";
	if($var!=''){
		switch($opcion){
			case 'tipo':
				$cond= $this->condicionNDEP()." and cod_dep=1 and cod_tipo_cuenta=".$var;
				$sql=$this->ccfd01_tipo->execute("select denominacion from ccfd01_tipo where ".$cond);
				$denominacion=$sql[0][0]['denominacion'];
			break;
			case 'contable':
				$tipo=$this->Session->read('tipo');
				$cond= $this->condicionNDEP()." and cod_dep=1 and cod_tipo_cuenta=".$tipo." and cod_cuenta=".$var;
				$sql=$this->ccfd01_cuenta->execute("select denominacion from ccfd01_cuenta where ".$cond);
				$denominacion=$sql[0][0]['denominacion'];
			break;
			case 'subcuenta':
				$tipo=$this->Session->read('tipo');
				$cuenta=$this->Session->read('cuenta');
				$cond= $this->condicionNDEP()." and cod_dep=1 and cod_tipo_cuenta=".$tipo." and cod_cuenta=".$cuenta." and cod_subcuenta=".$var;
				$sql=$this->ccfd01_subcuenta->execute("select denominacion from ccfd01_subcuenta where ".$cond);
				$denominacion=$sql[0][0]['denominacion'];
			break;
			case 'div_estadistica_contable':
				$tipo=$this->Session->read('tipo');
				$cuenta=$this->Session->read('cuenta');
				$subcuenta=$this->Session->read('subcuenta');
				$cond= $this->condicionNDEP()." and cod_dep=1 and cod_tipo_cuenta=".$tipo." and cod_cuenta=".$cuenta." and cod_subcuenta=".$subcuenta." and cod_division=".$var;
				$sql=$this->ccfd01_division->execute("select denominacion from ccfd01_division where ".$cond);
				$denominacion=$sql[0][0]['denominacion'];
			break;
			case 'subdiv_estadistica_contable':
				$tipo=$this->Session->read('tipo');
				$cuenta=$this->Session->read('cuenta');
				$subcuenta=$this->Session->read('subcuenta');
				$div_contable=$this->Session->read('div_contable');
				$cond= $this->condicionNDEP()." and cod_dep=1 and cod_tipo_cuenta=".$tipo." and cod_cuenta=".$cuenta." and cod_subcuenta=".$subcuenta." and cod_division=".$div_contable." and cod_subdivision=".$var;
				$sql=$this->ccfd01_subdivision->execute("select denominacion from ccfd01_subdivision where ".$cond);
				$denominacion=$sql[0][0]['denominacion'];
				echo "<script>";
	   		 			echo "document.getElementById('agregar').disabled=false;";
				echo "</script>";
			break;

		}
		$this->set('denominacion',$denominacion);
	}else{
		$this->set('vacio','');
		echo "<script>";
	 			echo "document.getElementById('agregar').disabled='disabled';";
		echo "</script>";
	}



}



function m2($var=null){
	switch (strlen($var)){
		case 1:
			return '0'.$var;
		break;
		case 2:
			return $var;
		break;
		case 3:
			return $var;
		break;
		case 4:
			return $var;
		break;
	}
}// fin m4


function m3($var=null){
	switch (strlen($var)){
		case 1:
			return '00'.$var;
		break;
		case 2:
			return '0'.$var;
		break;
		case 3:
			return $var;
		break;
	}
}// fin m4


function m4($var=null){
	switch (strlen($var)){
		case 1:
			return '000'.$var;
		break;
		case 2:
			return '00'.$var;
		break;
		case 3:
			return '0'.$var;
		break;
		case 4:
			return $var;
		break;
	}
}// fin m4



function agregar_grilla($var=null) {
	$this->layout="ajax";
//	pr($this->data);
	$enlace=$this->data['cnmp09']['tipo_enlace'];
	$tipo=$this->data['cnmp09']['tipo_cuenta'];

	if(isset($this->data['cnmp09']['cod_contable']) && !empty($this->data['cnmp09']['cod_contable'])){
		$cuenta=$this->data['cnmp09']['cod_contable'];
	}else{
		$cuenta=0;
	}
//////////////////////////////////////////////////////////////////////////////////////////////////
	if(isset($this->data['cnmp09']['cod_subcuenta']) && !empty($this->data['cnmp09']['cod_subcuenta'])){
		$subcuenta=$this->data['cnmp09']['cod_subcuenta'];
	}else{
		$subcuenta=0;
	}
/////////////////////////////////////////////////////////////////////////////////////////////////
	if(isset($this->data['cnmp09']['cod_div_estadistica_contable']) && !empty($this->data['cnmp09']['cod_div_estadistica_contable'])){
		$division=$this->data['cnmp09']['cod_div_estadistica_contable'];
	}else{
		$division=0;
	}
////////////////////////////////////////////////////////////////////////////////////////////////
	if(isset($this->data['cnmp09']['cod_subdiv_estadistica_contable']) && !empty($this->data['cnmp09']['cod_subdiv_estadistica_contable'])){
		$subdivision=$this->data['cnmp09']['cod_subdiv_estadistica_contable'];
	}else{
		$subdivision=0;
	}
///////////////////////////////////////////////////////////////////////////////////////////////



	if(isset($_SESSION["contador"])){
        $_SESSION["contador"]=$_SESSION["contador"]+1;
	}else{
		$_SESSION["contador"]=1;
	}

	if(isset($var) && !empty($var)){

			$cod[0]=$enlace;
			$cod[1]=$tipo;
			$cod[2]=$cuenta;
			$cod[3]=$subcuenta;
			$cod[4]=$division;
			$cod[5]=$subdivision;

		    if(isset($_SESSION["i"])){
				$i=$this->Session->read("i")+1;
				$this->Session->write("i",$i);
	   		 }else{
			   $this->Session->write("i",0);
				$i=0;
			}
        switch($var){
        	case 'normal':
					 $vec[$i][0]=$enlace;
					 $vec[$i][1]=$tipo;
					 $vec[$i][2]=$this->m3($cuenta);
					 $vec[$i][3]=$this->m3($subcuenta);
					 $vec[$i][4]=$this->m4($division);
					 $vec[$i][5]=$this->m3($subdivision);
					 //echo $vec[$i][6];
					 $vec[$i]["id"]=$i;
					 if(isset($_SESSION["items1"])){
						foreach($_SESSION["items1"] as $codi){
							//echo $codi[0].$cod[0].$codi[1].$cod[1].$codi[2].$cod[2].$codi[3].$cod[3].$codi[4].$cod[4].$codi[5].$cod[5].$codi[6].$cod[6].$codi[7].$cod[7]. $codi[8].$cod[8].$codi[9].$cod[9].$codi[10].$cod[10];
            	           if($codi[0]==$cod[0]){
                              $est=true;
                              break;
            	          }else{
            	          	 $est=false;
            	          }
                        }//fin foreach
                        if($est==true){
                           //	echo "no";
            	          	$i=$this->Session->read("i")-1;
				            $this->Session->write("i",$i);
				            $this->set('errorMessage', 'el tipo de enlace ya existe en la lista');
                        }else{
                        	$_SESSION["items1"]=$_SESSION["items1"]+$vec;
                          //  echo "si";
                        }
					 }else{
						$_SESSION["items1"]=$vec;
					 }

        	break;
        	/*case 'nuevos':
                     $vec[$i][0]=$cod[0];
					 $vec[$i][1]=$this->AddCeroR($cod[1]);
					 $vec[$i][2]=$this->AddCeroR($cod[2]);
					 $vec[$i][3]=$this->AddCeroR($cod[3]);
					 $vec[$i][4]=$this->AddCeroR($cod[4]);
					 $vec[$i][5]=$this->AddCeroR($cod[5]);
					 $vec[$i][6]=$cod[6];
					 $vec[$i][7]=$this->AddCeroR($cod[7]);
					 $vec[$i][8]=$this->AddCeroR($cod[8]);
					 $vec[$i][9]=$this->AddCeroR($cod[9]);
					 $vec[$i][10]=$this->AddCeroR($cod[10]);
					 $vec[$i][11]=$cod[11];
					 $vec[$i]["id"]=$i;
					 if(isset($_SESSION["items"])){
						foreach($_SESSION["items"] as $codi){
							//echo $codi[0].$cod[0].$codi[1].$cod[1].$codi[2].$cod[2].$codi[3].$cod[3].$codi[4].$cod[4].$codi[5].$cod[5].$codi[6].$cod[6].$codi[7].$cod[7]. $codi[8].$cod[8].$codi[9].$cod[9].$codi[10].$cod[10];
            	           if($codi[0]==$cod[0] && $codi[1]==$cod[1] && $codi[2]==$cod[2] && $codi[3]==$cod[3] && $codi[4]==$cod[4] && $codi[5]==$cod[5] && $codi[6]==$cod[6] && $codi[7]==$cod[7] && $codi[8]==$cod[8] && $codi[9]==$cod[9] && $codi[10]==$cod[10]){
                              $est=true;
                              break;
            	          }else{
            	          	 $est=false;
            	          }
                        }//fin foreach
                        if($est==true){
                           //	echo "no";
            	          	$i=$this->Session->read("i")-1;
				            $this->Session->write("i",$i);
				            $this->set('errorMessage', 'Los codigos seleccionados ya existen en la lista');
                        }else{
                        	$_SESSION["items"]=$_SESSION["items"]+$vec;
                          //  echo "si";
                        }
					 }else{
						$_SESSION["items"]=$vec;
					 }
        	break;*/

        }//fin switch
		}//

	echo'<script>';
		echo "document.getElementById('agregar').disabled='';";
        echo "if(document.getElementById('select_2'))document.getElementById('select_2').options[1].selected = true; ";
        echo "if(document.getElementById('select_3'))document.getElementById('select_3').innerHTML='<select></select>';  ";
        echo "if(document.getElementById('select_4')) document.getElementById('select_4').innerHTML='<select></select>';  ";
        echo "if(document.getElementById('select_5')) document.getElementById('select_5').innerHTML='<select></select>';  ";
 	echo'</script>';



}//fin funcu¡ions



function limpiar_lista () {
	$this->layout = "ajax";
	$this->Session->delete("items1");
	$this->Session->delete("i");
	$this->Session->delete("contador");
}




function eliminar_items ($id) {
	$this->layout = "ajax";
	$_SESSION["items1"][$id]=null;
	$monto_total=0;
	$NL=1;
	$codigos1=array();
	foreach($_SESSION ["items1"] as $codigos){
       if($codigos[$NL]!=null){

       		$codigos1[$NL][0]=$NL;
       		$codigos1[$NL][1]=$codigos[1];
       		$codigos1[$NL][2]=$codigos[2];
       		$codigos1[$NL][3]=$codigos[3];
       		$codigos1[$NL][4]=$codigos[4];
       		$codigos1[$NL][5]=$codigos[5];
       		$codigos1[$NL][6]=$codigos[6];
       		$codigos1[$NL]['id']=$NL;
			$NL++;
       }

	}
	//print_r($codigos1);
	$this->set('total_partidas_rc',$monto_total);
    $_SESSION["contador"]=$_SESSION["contador"]-1;
    $_SESSION["items1"]=array();
    $_SESSION["items1"]=$codigos1;
    //print_r($_SESSION["items1"]);
}




 function guardar($valor=null){
    $this->layout = "ajax";
//	pr($this->data);
	$ano=$this->ano_ejecucion();

		if(!isset($this->data['cnmp09']['tipo_enlace']) || empty($this->data['cnmp09']['tipo_enlace'])){
			$this->set('errorMessage', 'seleccione el tipo de enlace');
		}else{
			if(!isset($this->data['cnmp09']['tipo_cuenta']) || empty($this->data['cnmp09']['tipo_cuenta'])){
				$this->set('errorMessage', 'seleccione el tipo de cuenta');
			}else{
				$enlace=$this->data['cnmp09']['tipo_enlace'];
				$tipo=$this->data['cnmp09']['tipo_cuenta'];
				if(isset($this->data['cnmp09']['cod_contable']) && !empty($this->data['cnmp09']['cod_contable'])){
					$cuenta=$this->data['cnmp09']['cod_contable'];
				}else{
					$cuenta=0;
				}
				//////////////////////////////////////////////////////////////////////////////////////////////////
				if(isset($this->data['cnmp09']['cod_subcuenta']) && !empty($this->data['cnmp09']['cod_subcuenta'])){
					$subcuenta=$this->data['cnmp09']['cod_subcuenta'];
				}else{
					$subcuenta=0;
				}
				/////////////////////////////////////////////////////////////////////////////////////////////////
				if(isset($this->data['cnmp09']['cod_div_estadistica_contable']) && !empty($this->data['cnmp09']['cod_div_estadistica_contable'])){
					$division=$this->data['cnmp09']['cod_div_estadistica_contable'];
				}else{
					$division=0;
				}
				////////////////////////////////////////////////////////////////////////////////////////////////
				if(isset($this->data['cnmp09']['cod_subdiv_estadistica_contable']) && !empty($this->data['cnmp09']['cod_subdiv_estadistica_contable'])){
					$subdivision=$this->data['cnmp09']['cod_subdiv_estadistica_contable'];
				}else{
					$subdivision=0;
				}
				///////////////////////////////////////////////////////////////////////////////////////////////
				$cod_presi=$this->verifica_SS(1);
				$cod_entidad=$this->verifica_SS(2);
				$cod_tipo_inst=$this->verifica_SS(3);
				$cod_inst=$this->verifica_SS(4);
				$cod_dep=$this->verifica_SS(5);
				$ver=$this->ccfd04_cuentas_enlace->FindCount($this->SQLCA()." and ano_fiscal='$ano' and cod_tipo_enlace='$enlace'");
				if($ver==0){
					$sql_insert = "INSERT INTO ccfd04_cuentas_enlace VALUES($cod_presi,$cod_entidad,$cod_tipo_inst,$cod_inst,$cod_dep,$ano,'$enlace', '$tipo', '$cuenta', '$subcuenta','$division','$subdivision')";
					$sw = $this->ccfd04_cuentas_enlace->execute($sql_insert);
					if($sw>1){
						$this->set('Message_existe', 'Los Datos fueron Almacenados');
				    }else{
				   		$this->set('errorMessage', 'Los Datos no fueron Almacenados');
				   	}
				}else{
					$this->set('errorMessage', 'el tipo de enlace ya existe registrado');
				}

				echo'<script>';
					echo "document.getElementById('agregar').disabled='';";
					echo "document.getElementById('debe').value='';";
       				echo "if(document.getElementById('st_select_4')) document.getElementById('select_4').value='';  ";
        			echo "if(document.getElementById('st_select_5')) document.getElementById('st_select_5').innerHTML='<select></select>';  ";
			 	echo'</script>';
			}
			echo "<script>";
				echo "document.getElementById('agregar').disabled='disabled';";
			echo "</script>";
		}



	$datos=$this->ccfd04_cuentas_enlace->execute("select * from ccfd04_cuentas_enlace where ".$this->SQLCA()." and ano_fiscal='$ano' order by cod_tipo_enlace asc");
 	if($datos!=null){
		$this->set('datos',$datos);
 	}else{
		$this->set('datos',null);
 	}

 }//fin


   function select4($select=null,$var=null) {
 $this->layout = "ajax";
 	if($var!=null){
 	$cond= $this->condicionNDEP()." and cod_dep=1";
 	switch($select){
 		case 'tipo':
					 $this->set('SELECT','contable');//El parametro que se le pasa para que busque el proximo select (cuando entre en select3)
					 $this->set('codigo','tipo_cuenta');
					 $this->set('seleccion','');
					 $this->set('n',1);
					 $this->Session->write('radio',$var);
					 $lista = $this->ccfd01_tipo->generateList($cond, 'cod_tipo_cuenta ASC', null, '{n}.ccfd01_tipo.cod_tipo_cuenta', '{n}.ccfd01_tipo.denominacion');
			   		 $this->concatena($lista, 'vector');
			  break;
		case 'contable':
					 $this->set('SELECT','subcuenta');//El parametro que se le pasa para que busque el proximo select (cuando entre en select3)
					 $this->set('codigo','contable');
					 $this->set('seleccion','');
					 $this->set('n',2);
					 $this->Session->write('tipo',$var);
					 $cond.=" and cod_tipo_cuenta=".$var;
					 $lista = $this->ccfd01_cuenta->generateList($cond, 'cod_cuenta ASC', null, '{n}.ccfd01_cuenta.cod_cuenta', '{n}.ccfd01_cuenta.denominacion');
			   		 $this->concatena($lista, 'vector');
			  break;
		case 'subcuenta':
					 $this->set('SELECT','div_contable');
					 $this->set('codigo','subcuenta');
					 $this->set('seleccion','');
					 $this->set('n',3);
					 $this->Session->write('cuenta',$var);
					 $tipo=$this->Session->read('tipo');
					 $cond.=" and cod_tipo_cuenta=".$tipo." and cod_cuenta=".$var;
					 $lista=  $this->ccfd01_subcuenta->generateList($cond, 'cod_subcuenta ASC', null, '{n}.ccfd01_subcuenta.cod_subcuenta', '{n}.ccfd01_subcuenta.denominacion');
			         $this->concatena0($lista, 'vector','0');
			    break;
	     case 'div_contable':
				  		$this->set('SELECT','subdiv_estadistica_contable');
				  		$this->set('codigo','div_estadistica_contable');
				  		$this->set('seleccion','');
				  		$this->set('n',4);
				  		$this->Session->write('subcuenta',$var);
				  		$tipo=$this->Session->read('tipo');
				  		$cuenta=$this->Session->read('cuenta');
				  		$cond.=" and cod_tipo_cuenta=".$tipo." and cod_cuenta=".$cuenta." and cod_subcuenta=".$var;
				  		$lista = $this->ccfd01_division->generateList($cond, 'cod_division ASC', null, '{n}.ccfd01_division.cod_division', '{n}.ccfd01_division.denominacion');
		   		  		$this->concatena4($lista, 'vector','00');
			  		break;
		 case 'subdiv_estadistica_contable':
				  		$this->set('SELECT','direccion');
				  		$this->set('codigo','subdiv_estadistica_contable');
				  		$this->set('seleccion','');
				  		$this->set('no','no');
				  		$this->set('n',5);
				  		$tipo=$this->Session->read('tipo');
				  		$cuenta=$this->Session->read('cuenta');
				  		$subcuenta=$this->Session->read('subcuenta');
				  		$this->Session->write('div_contable',$var);
				  		$cond.=" and cod_tipo_cuenta=".$tipo." and cod_cuenta=".$cuenta." and cod_subcuenta=".$subcuenta." and cod_division=".$var;
				  		$lista = $this->ccfd01_subdivision->generateList($cond, 'cod_division ASC', null, '{n}.ccfd01_subdivision.cod_subdivision', '{n}.ccfd01_subdivision.denominacion');
		   		  		$this->concatena0($lista, 'vector','0');
			  		break;
	}//fin switch
	}else{
		  $this->set('SELECT','');
		  $this->set('codigo','');
		  $this->set('seleccion','');
		  $this->set('n',10);
		  $this->set('no','no');
		  $this->set('vector','');
	}
	$this->set('k',$this->Session->read('k'));
 }//fin function select3




 function modificar ($enlace=null,$tipo=null,$cuenta=null,$subcuenta=null,$division=null,$subdivision=null,$k=null) {
 	$this->layout = "ajax";
 	//pr($this->data);
 	$ano=$this->ano_ejecucion();
	$this->set('ano_ejecucion',$ano);

	 $this->Session->write('k',$k);
	 $this->Session->write('tipo',$tipo);
	 $this->Session->write('cuenta',$cuenta);
	 $this->Session->write('subcuenta',$subcuenta);
	 $this->Session->write('div_contable',$division);

	 $cond= $this->condicionNDEP()." and cod_dep=1";
	 $tipos = $this->ccfd01_tipo->generateList($cond, 'cod_tipo_cuenta ASC', null, '{n}.ccfd01_tipo.cod_tipo_cuenta', '{n}.ccfd01_tipo.denominacion');
	 $this->concatena0($tipos, 'tipos');

	 $this->set('k',$k);
	 $this->set('enlace',$enlace);
	 $this->set('tipo',$tipo);

	 $cond.=" and cod_tipo_cuenta=".$tipo;
	 $cuentas = $this->ccfd01_cuenta->generateList($cond, 'cod_cuenta ASC', null, '{n}.ccfd01_cuenta.cod_cuenta', '{n}.ccfd01_cuenta.denominacion');
	 $this->concatena($cuentas, 'cuentas');
//	 pr($cuentas);
	 if($cuenta!=0){
	 	 $this->set('cuenta',$cuenta);

	 	 $cond.=" and cod_tipo_cuenta=".$tipo." and cod_cuenta=".$cuenta;
		 $subcuentas=  $this->ccfd01_subcuenta->generateList($cond, 'cod_subcuenta ASC', null, '{n}.ccfd01_subcuenta.cod_subcuenta', '{n}.ccfd01_subcuenta.denominacion');
		 $this->concatena0($subcuentas, 'subcuentas','0');

		 if($subcuenta!=0){
		 	 $this->set('subcuenta',$subcuenta);

		 	 $cond.=" and cod_tipo_cuenta=".$tipo." and cod_cuenta=".$cuenta." and cod_subcuenta=".$subcuenta;
			 $div = $this->ccfd01_division->generateList($cond, 'cod_division ASC', null, '{n}.ccfd01_division.cod_division', '{n}.ccfd01_division.denominacion');
			 $this->concatena4($div, 'div','00');

			 if($division!=0){
			 	 $this->set('division',$division);

			 	 $cond.=" and cod_tipo_cuenta=".$tipo." and cod_cuenta=".$cuenta." and cod_subcuenta=".$subcuenta." and cod_division=".$division;
				 $subdiv = $this->ccfd01_subdivision->generateList($cond, 'cod_division ASC', null, '{n}.ccfd01_subdivision.cod_subdivision', '{n}.ccfd01_subdivision.denominacion');
				 $this->concatena0($subdiv, 'subdiv','0');
				 if($subdivision){
				 	 $this->set('subdivision',$subdivision);
				 }else{
					 $this->set('subdivision','');
				 }
			 }else{
			 	 $this->set('subdiv',array());
			 	 $this->set('division','');
		 	     $this->set('subdivision','');
			 }
		 }else{
		 	 $this->set('div',array());
	 		 $this->set('subdiv',array());
			 $this->set('subcuenta','');
			 $this->set('division','');
	 		 $this->set('subdivision','');
		 }
	 }else{
	 	$this->set('subcuentas',array());
	 	$this->set('div',array());
	 	$this->set('subdiv',array());
	 	$this->set('cuenta','');
	 	$this->set('subcuenta','');
	 	$this->set('division','');
	 	$this->set('subdivision','');
	 }

}//fin modificar


function cancelar(){
	$this->layout="ajax";

	$ano=$this->ano_ejecucion();
	$datos=$this->ccfd04_cuentas_enlace->execute("select * from ccfd04_cuentas_enlace where ".$this->SQLCA()." and ano_fiscal='$ano' order by cod_tipo_enlace asc");
 	if($datos!=null){
		$this->set('datos',$datos);
 	}else{
		$this->set('datos',null);
 	}

}//fin


function guardar_modificar($enlace=null,$k=null){
	$this->layout = "ajax";
//	pr($this->data);
	$ano=$this->ano_ejecucion();

	if(!isset($this->data['cnmp09']['tipo_cuenta'.$k]) || empty($this->data['cnmp09']['tipo_cuenta'.$k])){
		$this->set('errorMessage', 'seleccione el tipo de cuenta');
	}else{
		$tipo=$this->data['cnmp09']['tipo_cuenta'.$k];
		if(isset($this->data['cnmp09']['cod_contable'.$k]) && !empty($this->data['cnmp09']['cod_contable'.$k])){
			$cuenta=$this->data['cnmp09']['cod_contable'.$k];
		}else{
			$cuenta=0;
		}
	//////////////////////////////////////////////////////////////////////////////////////////////////
		if(isset($this->data['cnmp09']['cod_subcuenta'.$k]) && !empty($this->data['cnmp09']['cod_subcuenta'.$k])){
			$subcuenta=$this->data['cnmp09']['cod_subcuenta'.$k];
		}else{
			$subcuenta=0;
		}
	/////////////////////////////////////////////////////////////////////////////////////////////////
		if(isset($this->data['cnmp09']['cod_div_estadistica_contable'.$k]) && !empty($this->data['cnmp09']['cod_div_estadistica_contable'.$k])){
			$division=$this->data['cnmp09']['cod_div_estadistica_contable'.$k];
		}else{
			$division=0;
		}
	////////////////////////////////////////////////////////////////////////////////////////////////
		if(isset($this->data['cnmp09']['cod_subdiv_estadistica_contable'.$k]) && !empty($this->data['cnmp09']['cod_subdiv_estadistica_contable'.$k])){
			$subdivision=$this->data['cnmp09']['cod_subdiv_estadistica_contable'.$k];
		}else{
			$subdivision=0;
		}
	///////////////////////////////////////////////////////////////////////////////////////////////

		$sql="update ccfd04_cuentas_enlace set cod_tipo_cuenta='$tipo',cod_cuenta='$cuenta',cod_subcuenta='$subcuenta',cod_division='$division',cod_subdivision='$subdivision' where ".$this->SQLCA()." and ano_fiscal='$ano' and cod_tipo_enlace='$enlace'";
		$sw = $this->ccfd04_cuentas_enlace->execute($sql);
		if($sw>1){
			$this->set('Message_existe', 'Los datos fueron actualizados');
		}else{
	   		$this->set('errorMessage', 'Los datos no fueron actualizados');
	   	}

	}

	$datos=$this->ccfd04_cuentas_enlace->execute("select * from ccfd04_cuentas_enlace where ".$this->SQLCA()." and ano_fiscal='$ano' order by cod_tipo_enlace asc");
 	if($datos!=null){
		$this->set('datos',$datos);
 	}else{
		$this->set('datos',null);
 	}


}//fin



function eliminar($enlace=null) {
	$this->layout = "ajax";

		$ano=$this->ano_ejecucion();
		$ver=$this->ccfd04_cuentas_enlace->execute("delete from ccfd04_cuentas_enlace where ".$this->SQLCA()." and ano_fiscal='$ano' and cod_tipo_enlace='$enlace'");
		if($ver>1){
			$this->set('Message_existe', 'Los datos fueron Eliminados');
		}else{
	   		$this->set('errorMessage', 'Error, Los datos no fueron eliminados');
	   	}

	   	$datos=$this->ccfd04_cuentas_enlace->execute("select * from ccfd04_cuentas_enlace where ".$this->SQLCA()." and ano_fiscal='$ano' order by cod_tipo_enlace asc");
	 	if($datos!=null){
			$this->set('datos',$datos);
	 	}else{
			$this->set('datos',null);
	 	}

 }//fin

 }
?>
