<?php
/*
 * Fecha: 19/07/2007
 *
 * Por Erisk G. Aragol H.
 *
 * Herramienta utilizada: easyEclipse
 * sisap
 */
class Cnmp09CuentasBancariasCancelarNominasController extends AppController {
   var $name = 'cnmp09_cuentas_bancarias_cancelar_nominas';
   var $uses = array('Cnmd01',  'cnmd03_transacciones', 'cnmd09_bancos_cancelan_nominas','cugd01_estados','cugd01_municipios','cugd01_parroquias','cstd01_entidades_bancarias','cstd01_sucursales_bancarias','cstd02_cuentas_bancarias');
   var $helpers = array('Html','Ajax','Javascript', 'Sisap');
//'cnmd10_bolivares_asig',
function checkSession()
    {
        // If the session info hasn't been set...
        if (!$this->Session->check('Usuario'))
        {
            // Force the user to login
            $this->redirect('/salir');
            exit();
        }
    }

 function beforeFilter(){
 	$this->checkSession();

 }





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

		$this->set($nomVar, $cod);

	}
}//fin concatena




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

function index($cod=null){
	$this->layout="ajax";
//echo $cod;
	if(isset($cod)){
		echo $cod."   ";
	}
	$lista = $this->Cnmd01->generateList($conditions = $this->condicion(), $order = 'cod_tipo_nomina', $limit = null, '{n}.Cnmd01.cod_tipo_nomina', '{n}.Cnmd01.denominacion');
	$this->concatenaN($lista, 'nomina');
	//echo $this->condicion();
	//print_r($lista);

	$cnmd03 = $this->cnmd03_transacciones->generateList2($conditions ='cod_tipo_transaccion = 1', $order = null, $limit = null, '{n}.cnmd03_transacciones.cod_transaccion', '{n}.cnmd03_transacciones.denominacion');
	$this->concatenaN($cnmd03, 'transaccion');

	$lista1=  $this->cstd01_entidades_bancarias->generateList(null, 'cod_entidad_bancaria ASC', null, '{n}.cstd01_entidades_bancarias.cod_entidad_bancaria', '{n}.cstd01_entidades_bancarias.denominacion');
	$this->set('banco',$lista1);

	echo "<script>";
		echo "document.getElementById('eliminar_asignacion').disabled='disabled';";
	echo "</script>";
}

function mostrar1($opcion=null,$var=null){
	$this->layout="ajax";//echo "sisinonono";
	if($var!=''){
		switch($opcion){
			case 'nomina':
				$this->Session->write('nomina',$var);
				$this->set('codigo',$opcion);
				$this->set('valor', $var);
                echo "<script>";
                	echo "if(document.getElementById('cod_bancox')) document.getElementById('cod_bancox').value='';";
                	echo "if(document.getElementById('cod_sucursalx')) document.getElementById('cod_sucursalx').value='';";
					echo "if(document.getElementById('deno_bancox')) document.getElementById('deno_bancox').value='';";
					echo "if(document.getElementById('deno_sucursalx')) document.getElementById('deno_sucursalx').value='';";
					echo "if(document.getElementById('cuentas_bancariasx')) document.getElementById('cuentas_bancariasx').value='';";
					echo "document.getElementById('beneficiario').value='';";
				echo "</script>";
			break;
			case 'deno_nomina':
				$deno_nomina = $this->Cnmd01->field('denominacion', $conditions = $this->condicion()." and Cnmd01.cod_tipo_nomina='$var'", $order ="cod_tipo_nomina ASC");
				$this->set('denomi', $deno_nomina);
				$this->set('denominacion',$opcion);
			break;
			case 'banco':
				$this->set('valor', $var);
				$this->set('codigo',$opcion);
				echo "<script>";
                	echo "document.getElementById('cod_sucursalx').value='';";
					echo "document.getElementById('deno_sucursalx').value='';";
					echo "document.getElementById('cuentas_bancariasx').value='';";
					echo "document.getElementById('beneficiario').value='';";
				echo "</script>";
			break;
			case 'deno_banco':
				$deno_banco = $this->cstd01_entidades_bancarias->field('denominacion', $conditions = "cod_entidad_bancaria='$var'", $order ="cod_entidad_bancaria ASC");
				$this->set('denomi', $deno_banco);
				$this->set('denominacion',$opcion);
			break;
			case 'sucursal':
				$this->set('valor', $var);
				$this->set('codigo',$opcion);
			break;
			case 'deno_sucursal':
				$cod3=$this->Session->read('cod3');
				$deno_sucursal = $this->cstd01_sucursales_bancarias->field('denominacion', $conditions = "cod_entidad_bancaria='$cod3' and cod_sucursal='$var'", $order ="cod_sucursal ASC");
				$this->set('denomi', $deno_sucursal);
				$this->set('denominacion',$opcion);
			break;
		}// fin switch
	}else{
		$this->set('si','no');
	}
}// fin mostrar


function valida(){

}



function concatena4($vector1=null, $nomVar=null, $extra=null){
	$cod = array();
	if($vector1 != null){
		foreach($vector1 as $x => $y){
			if($x<10){
				$cod[$x] = '000'.$x.' - '.$y;
			}else if($x>=10 && $x<=99){
				$cod[$x] = '00'.$x.' - '.$y;
			}else if($x>=100 && $x<=999){
				$cod[$x] = '0'.$x.' - '.$y;
			}else{
				$cod[$x] = $x.' - '.$y;
			}
		}
		//print_r($cod);
	}

	$this->set($nomVar, $cod);
}//fin function


function select3($opcion=null,$var=null){
	$this->layout="ajax";
	if($var!=''){
		switch($opcion){
			case 'banco':
				$this->set('anula','otros');
				$this->set('no','');
				$this->set('SELECT','sucursal');
				$this->set('codigo','banco');
				$this->set('seleccion','');
				$this->Session->write('parro',$var);
				$this->set('n',2);
				$lista=  $this->cstd01_entidades_bancarias->generateList(null, 'cod_entidad_bancaria ASC', null, '{n}.cstd01_entidades_bancarias.cod_entidad_bancaria', '{n}.cstd01_entidades_bancarias.denominacion');
				$this->set('vector',$lista);
				$nomina=$this->Session->read('nomina');
				$estado=$this->Session->read('cod1');
				$municipio=$this->Session->read('cod2');

			break;
			case 'sucursal':
				$this->set('no','');
				$this->set('SELECT','cuenta');
				$this->set('codigo','sucursal');
				$this->set('seleccion','');
				$this->Session->write('ban',$var);
				$this->set('n',3);
				$this->Session->write('cod3',$var);
				$cond =" cod_entidad_bancaria=".$var;
				$lista=  $this->cstd01_sucursales_bancarias->generateList($cond, 'cod_sucursal ASC', null, '{n}.cstd01_sucursales_bancarias.cod_sucursal', '{n}.cstd01_sucursales_bancarias.denominacion');
				$this->set('vector',$lista);
				$this->set('anula','otros');
			break;
			case 'cuenta':
				$this->set('no','no');
				$this->set('SELECT','cuenta');
				$this->set('codigo','cuenta');
				$this->set('seleccion','');
				$this->set('n',4);
				$cod3=$this->Session->read('cod3');
				$cond =$this->SQLCA()." and cod_entidad_bancaria=".$cod3." and cod_sucursal=".$var;
				$lista=  $this->cstd02_cuentas_bancarias->generateList($cond, 'cuenta_bancaria ASC', null, '{n}.cstd02_cuentas_bancarias.cuenta_bancaria', '{n}.cstd02_cuentas_bancarias.concepto_manejo');
				$this->concatena($lista, 'vector');
			break;
		}//fin switch
	}
}//fin select3




function guardar(){
	$this->layout="ajax";
	$cod_presi = $this->Session->read('SScodpresi');
    $cod_entidad = $this->Session->read('SScodentidad');
    $cod_tipo_inst = $this->Session->read('SScodtipoinst');
    $cod_inst = $this->Session->read('SScodinst');
    $cod_dep = $this->Session->read('SScoddep');
	$nomina=$this->data['cnmp09']['cod_nomina'];
	$banco=$this->data['cnmp09']['cod_banco'];
	$sucursal=$this->data['cnmp09']['cod_sucursal'];
	$cuenta=$this->data['cnmp09']['cod_cuenta'];
	$rif=$this->data['cnmp09']['rif'];
	$beneficiario=$this->data['cnmp09']['beneficiario'];
	$verifica=$this->cnmd09_bancos_cancelan_nominas->FindCount($this->SQLCA()." and cod_tipo_nomina='$nomina' and cod_entidad_bancaria='$banco' and cod_sucursal='$sucursal' and cuenta_bancaria='".$cuenta."'");
	if($verifica==0){
		$sql_insert = "INSERT INTO cnmd09_bancos_cancelan_nominas VALUES('$cod_presi', '$cod_entidad', '$cod_tipo_inst', '$cod_inst', '$cod_dep','$nomina','$banco','$sucursal','$cuenta','$beneficiario','$rif')";
		$sw1 = $this->cnmd09_bancos_cancelan_nominas->execute($sql_insert);
		if($sw1>1){
			$this->set('Message_existe','registro exitoso');
		}else{
			$this->set('errorMessage','los datos no pudieron ser registrados');
		}
	}else{
		$this->set('errorMessage','este registro ya existe');
	}



	$this->data['cnmp09']['cod_nomina']=null;
	$this->data['cnmp09']['cuentas_bancarias']=null;
	$this->data['cnmp09']['beneficiario']=null;
	$this->data['cnmp09']['deno_nomina']=null;
	$this->data['cnmp09']['deno_banco']=null;
	$this->data['cnmp09']['deno_sucursal']=null;
	$this->data['cnmp09']['rif']=null;

	$this->index();
	$this->render('index');
}//fin guardar




function eliminar($cod_nomina=null,$banco=null,$cod_sucursal=null,$cuenta=null,$anterior=null){
 		$this->layout="ajax";
		$sql = "DELETE FROM cnmd09_bancos_cancelan_nominas WHERE ".$this->SQLCA()." and cod_tipo_nomina=".$cod_nomina." and cod_entidad_bancaria=".$banco." and cod_sucursal=".$cod_sucursal." and cuenta_bancaria='".$cuenta."'";
		if($this->cnmd09_bancos_cancelan_nominas->execute($sql)>1){
			$this->set('Message_existe', 'EL REGISTRO FUE ELIMINADO EXITOSAMENTE');
			$Tfilas=$this->cnmd09_bancos_cancelan_nominas->findCount($this->SQLCA());
				if($Tfilas!=0){
				$this->consultar($anterior);
				$this->render("consultar");
				}else{
					$this->data['cnmp09']['cod_nomina']=null;
					$this->data['cnmp09']['bancario']=null;
					$this->data['cnmp09']['beneficiario']=null;
					$this->data['cnmp09']['deno_nomina']=null;
					$this->data['cnmp09']['deno_banco']=null;
					$this->data['cnmp09']['deno_sucursal']=null;
					$this->index();
					$this->render("index");

				}/////HASTA AQUI
		}else{
			$this->set('errorMessage', 'EL REGISTRO NO PUDO SER ELIMINADO');
			$this->consultar($anterior);
			$this->render("consultar");
		}
 }//eliminar



function consultar($pagina=null) {
	$this->layout="ajax";
	$this->set('entidad_federal', $this->Session->read('entidad_federal'));
	if(isset($pagina)){
		$Tfilas=$this->cnmd09_bancos_cancelan_nominas->findCount($this->SQLCA());
        if($Tfilas!=0){
        	$data=$this->cnmd09_bancos_cancelan_nominas->findAll($this->SQLCA(),null,"cod_tipo_nomina,cod_entidad,cod_sucursal ASC",1,$pagina,null);

            $this->set('DATA',$data);
          	$this->set('siguiente',$pagina+1);
          	$this->set('anterior',$pagina-1);
            $this->bt_nav($Tfilas,$pagina);
            $this->set('numT',$Tfilas);
			$this->set('numP',$pagina);
			$this->set('pagina',$pagina);
        }else{
	 	    $this->set('errorMessage', 'No se encontrar&oacute;n datos');
	 	    $this->set('noExiste',true);
        }

	}else{
		$pagina=1;
		$Tfilas=$this->cnmd09_bancos_cancelan_nominas->findCount($this->SQLCA());

        if($Tfilas!=0){
        	$data=$this->cnmd09_bancos_cancelan_nominas->findAll($this->SQLCA(),null,"cod_tipo_nomina,cod_entidad,cod_sucursal ASC",1,$pagina,null);
			$this->set('DATA',$data);
          	$this->set('siguiente',$pagina+1);
          	$this->set('anterior',$pagina-1);
          	$this->bt_nav($Tfilas,$pagina);
          	$this->set('numT',$Tfilas);
			$this->set('numP',$pagina);
			$this->set('pagina',$pagina);

        }else{
	 	       $this->set('errorMessage', 'No se encontrar&oacute;n datos');
	 	       $this->set('noExiste',true);
	 	       $this->data['cnmp09']['cod_nomina']=null;
				$this->data['cnmp09']['bancario']=null;
				$this->data['cnmp09']['beneficiario']=null;
				$this->data['cnmp09']['deno_nomina']=null;
				$this->data['cnmp09']['deno_banco']=null;
				$this->data['cnmp09']['deno_sucursal']=null;
	 	       $this->index();
			   $this->render("index");
			   return;
        }
	}

	$this->set('cod_nomina', $data[0]['cnmd09_bancos_cancelan_nominas']['cod_tipo_nomina']);
	$this->set('banco', $data[0]['cnmd09_bancos_cancelan_nominas']['cod_entidad_bancaria']);
	$this->set('cod_sucursal', $data[0]['cnmd09_bancos_cancelan_nominas']['cod_sucursal']);
	$this->set('cuenta', $data[0]['cnmd09_bancos_cancelan_nominas']['cuenta_bancaria']);
	$this->set('beneficiario', $data[0]['cnmd09_bancos_cancelan_nominas']['beneficiario']);
	$this->set('rif', $data[0]['cnmd09_bancos_cancelan_nominas']['rif']);

	$deno_nomina = $this->Cnmd01->field('denominacion', $conditions = $this->condicion()." and Cnmd01.cod_tipo_nomina=".$data[0]['cnmd09_bancos_cancelan_nominas']['cod_tipo_nomina']);
	$this->set('deno_nomina', $deno_nomina);
	$deno_banco = $this->cstd01_entidades_bancarias->field('denominacion', $conditions = "cod_entidad_bancaria=".$data[0]['cnmd09_bancos_cancelan_nominas']['cod_entidad_bancaria']);
	$this->set('deno_banco', $deno_banco);
	$deno_sucursal = $this->cstd01_sucursales_bancarias->field('denominacion', $conditions = "cod_entidad_bancaria=".$data[0]['cnmd09_bancos_cancelan_nominas']['cod_entidad_bancaria']." and cod_sucursal=".$data[0]['cnmd09_bancos_cancelan_nominas']['cod_sucursal']);
	$this->set('deno_sucursal', $deno_sucursal);

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


 function modificar($cod_nomina=null,$banco=null,$cod_sucursal=null,$cuenta=null,$pagina=null){
 	$this->layout="ajax";

 	$data=$this->cnmd09_bancos_cancelan_nominas->findAll($this->SQLCA()." and cod_tipo_nomina=".$cod_nomina." and cod_entidad_bancaria=".$banco." and cod_sucursal=".$cod_sucursal." and cuenta_bancaria='".$cuenta."'");
	$this->set('cod_nomina', $data[0]['cnmd09_bancos_cancelan_nominas']['cod_tipo_nomina']);
	$this->set('banco', $data[0]['cnmd09_bancos_cancelan_nominas']['cod_entidad_bancaria']);
	$this->set('cod_sucursal', $data[0]['cnmd09_bancos_cancelan_nominas']['cod_sucursal']);
	$this->set('cuenta', $data[0]['cnmd09_bancos_cancelan_nominas']['cuenta_bancaria']);
	$this->set('beneficiario', $data[0]['cnmd09_bancos_cancelan_nominas']['beneficiario']);
	$this->set('rif', $data[0]['cnmd09_bancos_cancelan_nominas']['rif']);
	$this->set('pagina', $pagina);

	$deno_nomina = $this->Cnmd01->field('denominacion', $conditions = $this->condicion()." and Cnmd01.cod_tipo_nomina=".$data[0]['cnmd09_bancos_cancelan_nominas']['cod_tipo_nomina']);
	$this->set('deno_nomina', $deno_nomina);
	$deno_banco = $this->cstd01_entidades_bancarias->field('denominacion', $conditions = "cod_entidad_bancaria=".$data[0]['cnmd09_bancos_cancelan_nominas']['cod_entidad_bancaria']);
	$this->set('deno_banco', $deno_banco);
	$deno_sucursal = $this->cstd01_sucursales_bancarias->field('denominacion', $conditions = "cod_entidad_bancaria=".$data[0]['cnmd09_bancos_cancelan_nominas']['cod_entidad_bancaria']." and cod_sucursal=".$data[0]['cnmd09_bancos_cancelan_nominas']['cod_sucursal']);
	$this->set('deno_sucursal', $deno_sucursal);

 }//fin modificar




function guardar_modificar($nomina=null,$banco=null,$sucursal=null,$cuenta=null,$pagina=null){
	$this->layout="ajax";
		$rif=$this->data['cnmp09']['rif'];
		$beneficiario=$this->data['cnmp09']['beneficiario'];
			$v=$this->cnmd09_bancos_cancelan_nominas->execute("update cnmd09_bancos_cancelan_nominas set rif='".$rif."',beneficiario='".$beneficiario."' where ".$this->SQLCA()." and cod_tipo_nomina=".$nomina." and cod_entidad_bancaria=".$banco." and cod_sucursal=".$sucursal." and cuenta_bancaria='".$cuenta."'");
			if($v > 0){
				$this->set('Message_existe','EL REGISTRO SE MODIFICO EXITOSAMENTE');
			}else{
				$this->set('errorMessage','NO SE PUDO MODIFICAR');
			}
		$this->consultar($pagina);
		$this->render('consultar');


 }//guardar modificar



}//FIN CONTROLADOR
?>