<?php
/*
 * Creado el 17/10/2007 a las 11:58:11 AM por migue
 * Para CakePHP, PostgresSQL
 */
 class ccfp03instalacionController extends AppController {

   var $uses = array('ccfd04_cierre_mes','Usuario', "cugd02_dependencia", 'arrd05', 'ccfd01_tipo', 'ccfd01_cuenta', 'ccfd01_subcuenta', 'ccfd01_division',
                     'ccfd01_subdivision','ccfd04_cuentas_enlace','cfpd05');
   var $helpers = array('Html','Ajax','Javascript','Sisap');

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
		//print_r($cod);
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
	$consulta="select *from ccfd03_instalacion where ".$this->SQLCA();
	if($this->ccfd04_cierre_mes->execute($consulta)){

		//setea la variable para luego examinarla si existe o no
		$this->set('existe',true);

    //if (empty($this->data)){
    	$dato=$this->ccfd04_cierre_mes->findAll($this->SQLCA());
    	foreach($dato as $dato){
    		$ano_ejecucion=$dato['ccfd04_cierre_mes']['ano_arranque'];
    		$mes_ejecucion=$this->AddCeroR($dato['ccfd04_cierre_mes']['mes_arranque'],null);
    	}
    	 $this->set('ano_ejecucion',$ano_ejecucion);
    	 $this->set('mes_ejecucion',$mes_ejecucion);

   // }

	}else{
		$this->set('existe',false);
	}

	$cond= $this->condicionNDEP()." and cod_dep=1";
	$lista = $this->ccfd01_tipo->generateList($cond, 'cod_tipo_cuenta ASC', null, '{n}.ccfd01_tipo.cod_tipo_cuenta', '{n}.ccfd01_tipo.denominacion');
	 $this->concatena0($lista, 'vector');

	$ano=$this->ano_ejecucion();
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

	$this->limpiar_lista();
 }//fin

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
 }//fin function select3


function muestra($opcion=null,$var=null){
	$this->layout = "ajax";
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
			break;

		}
		$this->set('denominacion',$denominacion);
	}else{
		$this->set('vacio','');
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

	$this->set('entidad_federal', $this->Session->read('entidad_federal'));
	$ano_ejecucion=$this->data['ccfd03_instalacion']['ano_arranque'];
	$mes_ejecucion=$this->data['ccfd03_instalacion']['mes_arranque'];

	$cod_presi=$this->verifica_SS(1);
	$cod_entidad=$this->verifica_SS(2);
	$cod_tipo_inst=$this->verifica_SS(3);
	$cod_inst=$this->verifica_SS(4);
	$cod_dep=$this->verifica_SS(5);

	$cod_sql  = "cod_presi=".$cod_presi." and cod_entidad=".$cod_entidad." and cod_tipo_inst=".$cod_tipo_inst." and cod_inst=".$cod_inst;
	$cod_sql2 = " cod_tipo_institucion=".$cod_tipo_inst." and cod_institucion=".$cod_inst;




   $cod_sql_aux = "cod_presi=".$cod_presi." and cod_entidad=".$cod_entidad." and cod_tipo_inst=".$cod_tipo_inst." and cod_inst=".$cod_inst." and ano=".$ano_ejecucion;

                                       $sql = " cod_ramo     IS NULL   and
											    cod_subramo  IS NULL   and
											    cod_esp      IS NULL   and
											    cod_subesp   IS NULL   and
											    cod_aux      IS NULL       ";

      $dato1 = $this->cfpd05->findCount($cod_sql_aux." and ".$sql);
//      $dato1 = 0; //se esta desactivando la validación hasta que se habilite de nuevo
      if($dato1==0){

					                           $dependencia=$cod_dep;
					                           $cod_sql3 = "cod_presi=".$cod_presi." and cod_entidad=".$cod_entidad." and cod_tipo_inst=".$cod_tipo_inst." and cod_inst=".$cod_inst." and cod_dep=".$dependencia;
					                           if($this->ccfd04_cierre_mes->findCount($cod_sql3)==0){
					                             $sql="BEGIN;insert into ccfd03_instalacion values ($cod_presi,$cod_entidad,$cod_tipo_inst,$cod_inst,$dependencia,$ano_ejecucion,$mes_ejecucion,'llenar')";
					                   	         $sw = $this->ccfd04_cierre_mes->execute($sql);
					                   	       }else{
					                             $sql="BEGIN;update ccfd03_instalacion set ano_arranque=".$ano_ejecucion." , mes_arranque=".$mes_ejecucion." where cod_presi=".$cod_presi." and cod_entidad=".$cod_entidad." and cod_tipo_inst=".$cod_tipo_inst." and cod_inst=".$cod_inst." and cod_dep=".$dependencia;
					                             $sw = $this->ccfd04_cierre_mes->execute($sql);
					                   	       }//fin else

					    				// }//fin foreach
							if($sw>1){
								$swdel = $this->ccfd04_cierre_mes->execute("DELETE FROM ccfd03_instalacion WHERE cod_presi=".$cod_presi." and cod_entidad=".$cod_entidad." and cod_tipo_inst=".$cod_tipo_inst." and cod_inst=".$cod_inst." and cod_dep != 1;");
					        	if($swdel>1){
					        		$datos_dep = $this->arrd05->findAll("cod_presi=".$cod_presi." and cod_entidad=".$cod_entidad." and cod_tipo_inst=".$cod_tipo_inst." and cod_inst=".$cod_inst." and cod_dep != 1",'cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep');
					               	if(!empty($datos_dep)){
					               		$sql_insert_dep = "INSERT INTO ccfd03_instalacion VALUES ";
					               		foreach($datos_dep as $datod){
											$valores[] = " ('".$datod['arrd05']['cod_presi']."', '".$datod['arrd05']['cod_entidad']."', '".$datod['arrd05']['cod_tipo_inst']."', '".$datod['arrd05']['cod_inst']."', '".$datod['arrd05']['cod_dep']."', '".$ano_ejecucion."','".$mes_ejecucion."','LLENAR')";
										}
										$sql_insert_dep .= " ".implode(',', $valores).";";
										$swin = $this->ccfd04_cierre_mes->execute($sql_insert_dep);
										if($swin>1){
											$this->Session->write('ANO_CERRADO_EJECUCION', $ano_ejecucion);
											$this->Session->write('MES_CERRADO_EJECUCION', $mes_ejecucion);
											echo "<script>if(document.getElementById('ANO_CERRADO_EJECUCION')) document.getElementById('ANO_CERRADO_EJECUCION').value='".$ano_ejecucion."';
														  if(document.getElementById('MES_CERRADO_EJECUCION')) document.getElementById('MES_CERRADO_EJECUCION').value='".$mes_ejecucion."';
												</script>";
											$this->ccfd04_cierre_mes->execute("COMMIT");
											$this->set('Message_existe', 'Los Datos fueron Almacenados');
										}else{
											$this->Session->write('ANO_CERRADO_EJECUCION', $ano_ejecucion);
											$this->Session->write('MES_CERRADO_EJECUCION', $mes_ejecucion);
											echo "<script>if(document.getElementById('ANO_CERRADO_EJECUCION')) document.getElementById('ANO_CERRADO_EJECUCION').value='".$ano_ejecucion."';
														  if(document.getElementById('MES_CERRADO_EJECUCION')) document.getElementById('MES_CERRADO_EJECUCION').value='".$mes_ejecucion."';
												</script>";
											$this->ccfd04_cierre_mes->execute("COMMIT");
											$this->set('errorMessage', 'Los Datos fueron Almacenados... - Pero no en instalacion');
										}
					               	}else{
											$this->Session->write('ANO_CERRADO_EJECUCION', $ano_ejecucion);
											$this->Session->write('MES_CERRADO_EJECUCION', $mes_ejecucion);
											echo "<script>if(document.getElementById('ANO_CERRADO_EJECUCION')) document.getElementById('ANO_CERRADO_EJECUCION').value='".$ano_ejecucion."';
														  if(document.getElementById('MES_CERRADO_EJECUCION')) document.getElementById('MES_CERRADO_EJECUCION').value='".$mes_ejecucion."';
												</script>";
										$this->ccfd04_cierre_mes->execute("COMMIT");
										$this->set('Message_existe', 'Los Datos fueron Almacenados');
					               	}
					        	}

								$this->set('existe',true);
				    	 		$this->set('ano_ejecucion',$ano_ejecucion);
				    	 		$this->set('mes_ejecucion',$mes_ejecucion);

						   }else{
						   	$this->ccfd04_cierre_mes->execute("ROLLBACK");
						   	$this->set('errorMessage', 'Los Datos no fueron Almacenados');
						   	$this->set('existe',false);
						   	}

		}else{
			    $this->set('errorMessage', 'Faltan partidas por conectar en la relación de ingresos');
		     }

$this->index();
$this->render('index');

 }//fin function

 function modificar () {
 	$this->layout = "ajax";
 	//pr($this->data);
	$this->set('entidad_federal', $this->Session->read('entidad_federal'));
	$ano_ejecucion=$this->data['ccfd03_instalacion']['ano_arranque'];
	$mes_ejecucion=$this->data['ccfd03_instalacion']['mes_arranque'];
	$this->set('ano_ejecucion',$ano_ejecucion);
    $this->set('mes_ejecucion',$mes_ejecucion);


	$cond= $this->condicionNDEP()." and cod_dep=1";
	$lista = $this->ccfd01_tipo->generateList($cond, 'cod_tipo_cuenta ASC', null, '{n}.ccfd01_tipo.cod_tipo_cuenta', '{n}.ccfd01_tipo.denominacion');
	 $this->concatena0($lista, 'vector');

    $ano=$this->ano_ejecucion();
	$dato=$this->ccfd04_cuentas_enlace->execute("select * from ccfd04_cuentas_enlace where ano_fiscal=".$ano);
	if($dato!=null){
		$this->set('datos',$dato);
	}else{
		$this->set('datos',null);
	}

}


function valida_ano($var=null){
	$this->layout = "ajax";

	echo "<script>document.getElementById('carga_grilla').innerHTML='';</script>";
	$dato=$this->ccfd04_cuentas_enlace->execute("select * from ccfd04_cuentas_enlace where ano_fiscal=".$var);
	if($dato!=null){
		$this->set('datos',$dato);
	}else{
		$this->set('datos',null);
	}

	$cond= $this->condicionNDEP()." and cod_dep=1";
	$lista = $this->ccfd01_tipo->generateList($cond, 'cod_tipo_cuenta ASC', null, '{n}.ccfd01_tipo.cod_tipo_cuenta', '{n}.ccfd01_tipo.denominacion');
	 $this->concatena0($lista, 'vector');
}

function guardar_modificar(){
	$this->layout = "ajax";
	$ano_ejecucion=$this->data['ccfd03_instalacion']['ano_arranque'];
	$mes_ejecucion=$this->data['ccfd03_instalacion']['mes_arranque'];
	$cod_presi=$this->verifica_SS(1);
	$cod_entidad=$this->verifica_SS(2);
	$cod_tipo_inst=$this->verifica_SS(3);
	$cod_inst=$this->verifica_SS(4);
	$cod_dep=$this->verifica_SS(5);
	$sql="update ccfd03_instalacion set ano_arranque=".$ano_ejecucion." , mes_arranque=".$mes_ejecucion." where cod_presi=".$cod_presi." and cod_entidad=".$cod_entidad." and cod_tipo_inst=".$cod_tipo_inst." and cod_inst=".$cod_inst;

    $cod_sql_aux = "cod_presi=".$cod_presi." and cod_entidad=".$cod_entidad." and cod_tipo_inst=".$cod_tipo_inst." and cod_inst=".$cod_inst." and ano=".$ano_ejecucion;

                                       $sql = " cod_ramo     IS NULL   and
											    cod_subramo  IS NULL   and
											    cod_esp      IS NULL   and
											    cod_subesp   IS NULL   and
											    cod_aux      IS NULL       ";

        $dato1 = $this->cfpd05->findCount($cod_sql_aux." and ".$sql);
        $dato1 = 0; //se esta desactivando la validación hasta que se habilite de nuevo
      if($dato1==0){



								    $cod_sql2 = " cod_tipo_institucion=".$cod_tipo_inst." and cod_institucion=".$cod_inst;
								    // $dato2 = $this->cugd02_dependencia->findAll($cod_sql2);
								                   // foreach($dato2 as $dato11){
								                           // $dependencia=$dato11['cugd02_dependencia']['cod_dependencia'];
								                           $dependencia=$cod_dep;
								                           $cod_sql3 = "cod_presi=".$cod_presi." and cod_entidad=".$cod_entidad." and cod_tipo_inst=".$cod_tipo_inst." and cod_inst=".$cod_inst." and cod_dep=".$dependencia;
								                           if($this->ccfd04_cierre_mes->findCount($cod_sql3)==0){
								                             $sql="BEGIN;insert into ccfd03_instalacion values ($cod_presi,$cod_entidad,$cod_tipo_inst,$cod_inst,$dependencia,$ano_ejecucion,$mes_ejecucion,'llenar')";
								                   	         $sw = $this->ccfd04_cierre_mes->execute($sql);

								                   	       }else{
								                             $sql="BEGIN;update ccfd03_instalacion set ano_arranque=".$ano_ejecucion." , mes_arranque=".$mes_ejecucion." where cod_presi=".$cod_presi." and cod_entidad=".$cod_entidad." and cod_tipo_inst=".$cod_tipo_inst." and cod_inst=".$cod_inst." and cod_dep=".$dependencia;
								                             $sw = $this->ccfd04_cierre_mes->execute($sql);

								                   	       }//fin else

								    				// }//fin foreach

										if($sw>1){

											$this->set('existe',true);
											$dato=$this->ccfd04_cierre_mes->findAll($this->SQLCA());
								    				foreach($dato as $dato){
								    				$ano_ejecucion=$dato['ccfd04_cierre_mes']['ano_arranque'];
								    				$mes_ejecucion=$this->AddCeroR($dato['ccfd04_cierre_mes']['mes_arranque'],null);
								    				}

								$swdel = $this->ccfd04_cierre_mes->execute("DELETE FROM ccfd03_instalacion WHERE cod_presi=".$cod_presi." and cod_entidad=".$cod_entidad." and cod_tipo_inst=".$cod_tipo_inst." and cod_inst=".$cod_inst." and cod_dep != 1;");
					        	if($swdel>1){
					        		$datos_dep = $this->arrd05->findAll("cod_presi=".$cod_presi." and cod_entidad=".$cod_entidad." and cod_tipo_inst=".$cod_tipo_inst." and cod_inst=".$cod_inst." and cod_dep != 1",'cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep');
					               	if(!empty($datos_dep)){
					               		$sql_insert_dep = "INSERT INTO ccfd03_instalacion VALUES ";
					               		foreach($datos_dep as $datod){
											$valores[] = " ('".$datod['arrd05']['cod_presi']."', '".$datod['arrd05']['cod_entidad']."', '".$datod['arrd05']['cod_tipo_inst']."', '".$datod['arrd05']['cod_inst']."', '".$datod['arrd05']['cod_dep']."', '".$ano_ejecucion."','".$mes_ejecucion."','LLENAR')";
										}
										$sql_insert_dep .= " ".implode(',', $valores).";";
										$swin = $this->ccfd04_cierre_mes->execute($sql_insert_dep);
										if($swin>1){
											$this->Session->write('ANO_CERRADO_EJECUCION', $ano_ejecucion);
											$this->Session->write('MES_CERRADO_EJECUCION', $mes_ejecucion);
											echo "<script>if(document.getElementById('ANO_CERRADO_EJECUCION')) document.getElementById('ANO_CERRADO_EJECUCION').value='".$ano_ejecucion."';
														  if(document.getElementById('MES_CERRADO_EJECUCION')) document.getElementById('MES_CERRADO_EJECUCION').value='".$mes_ejecucion."';
												</script>";
											$this->ccfd04_cierre_mes->execute("COMMIT");
											$this->set('Message_existe', 'Los datos fueron actualizados');
										}else{
											$this->Session->write('ANO_CERRADO_EJECUCION', $ano_ejecucion);
											$this->Session->write('MES_CERRADO_EJECUCION', $mes_ejecucion);
											echo "<script>if(document.getElementById('ANO_CERRADO_EJECUCION')) document.getElementById('ANO_CERRADO_EJECUCION').value='".$ano_ejecucion."';
														  if(document.getElementById('MES_CERRADO_EJECUCION')) document.getElementById('MES_CERRADO_EJECUCION').value='".$mes_ejecucion."';
												</script>";
											$this->ccfd04_cierre_mes->execute("COMMIT");
											$this->set('errorMessage', 'Los Datos fueron Actualizados... - Pero no en instalacion');
										}
					               	}else{
											$this->Session->write('ANO_CERRADO_EJECUCION', $ano_ejecucion);
											$this->Session->write('MES_CERRADO_EJECUCION', $mes_ejecucion);
											echo "<script>if(document.getElementById('ANO_CERRADO_EJECUCION')) document.getElementById('ANO_CERRADO_EJECUCION').value='".$ano_ejecucion."';
														  if(document.getElementById('MES_CERRADO_EJECUCION')) document.getElementById('MES_CERRADO_EJECUCION').value='".$mes_ejecucion."';
												</script>";
											$this->ccfd04_cierre_mes->execute("COMMIT");
											$this->set('Message_existe', 'Los datos fueron actualizados');
					               	}
					        	}

							    	 		$this->set('ano_ejecucion',$ano_ejecucion);
							    	 		$this->set('mes_ejecucion',$mes_ejecucion);
										}else{
										$this->ccfd04_cierre_mes->execute("ROLLBACK");
									   	$this->set('errorMessage', 'Los datos no fueron actualizados');
									   	}

		}else{
			    $this->set('errorMessage', 'Faltan partidas por conectar en la relación de ingresos');

		     }

 $this->index();
$this->render('index');
}//fin function




function eliminar() {
	$this->layout = "ajax";
	$ano_ejecucion=$this->data['ccfd03_instalacion']['ano_arranque'];
	$mes_ejecucion=$this->data['ccfd03_instalacion']['mes_arranque'];
	$cod_presi=$this->verifica_SS(1);
	$cod_entidad=$this->verifica_SS(2);
	$cod_tipo_inst=$this->verifica_SS(3);
	$cod_inst=$this->verifica_SS(4);
	$cod_dep=$this->verifica_SS(5);
	$cod_sql = "cod_presi=".$cod_presi." and cod_entidad=".$cod_entidad." and cod_tipo_inst=".$cod_tipo_inst." and cod_inst=".$cod_inst;
	$sql="delete from ccfd03_instalacion where ".$cod_sql;

		if($this->ccfd04_cierre_mes->execute($sql)>1){
			$this->set('Message_existe', 'Los datos fueron Eliminados');
			$this->set('existe',false);
			$this->render('index');
		}else{
	   	$this->set('errorMessage', 'Error, Los datos no fueron eliminados');
	   	}
	}
 }
?>
