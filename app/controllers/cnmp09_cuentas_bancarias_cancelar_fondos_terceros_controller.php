<?php
/*
 * Fecha: 19/07/2007
 *
 * Por Erisk G. Aragol H.
 *
 * Herramienta utilizada: easyEclipse
 * sisap
 */
class Cnmp09CuentasBancariasCancelarFondosTercerosController extends AppController {
   var $name = 'cnmp09_cuentas_bancarias_cancelar_fondos_terceros';
   var $uses = array('Cnmd01',  'cnmd03_transacciones', 'cnmd09_bancos_cancelan_fondos_terceros','cugd01_estados','cugd01_municipios','cugd01_parroquias','cstd01_entidades_bancarias','cstd01_sucursales_bancarias','cstd02_cuentas_bancarias');
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
	if(isset($cod)){
		echo $cod."   ";
	}
	$lista = $this->Cnmd01->generateList($conditions = $this->condicion(), $order = 'cod_tipo_nomina', $limit = null, '{n}.Cnmd01.cod_tipo_nomina', '{n}.Cnmd01.denominacion');
	$this->concatenaN($lista, 'nomina');

	$cnmd03 = $this->cnmd03_transacciones->generateList2($conditions ='cod_tipo_transaccion = 1', $order = null, $limit = null, '{n}.cnmd03_transacciones.cod_transaccion', '{n}.cnmd03_transacciones.denominacion');
	$this->concatenaN($cnmd03, 'transaccion');

	echo "<script>";
		echo "document.getElementById('eliminar_asignacion').disabled='disabled';";
	echo "</script>";

					$this->data['cnmp09']['cod_nomina']=null;
					$this->data['cnmp09']['cod_estado']=null;
					$this->data['cnmp09']['cod_transaccion']=null;
					$this->data['cnmp09']['deno_transaccion']=null;
					$this->data['cnmp09']['cod_banco']=null;
					$this->data['cnmp09']['cod_sucursal']=null;
					$this->data['cnmp09']['bancario']=null;
					$this->data['cnmp09']['beneficiario']=null;
					$this->data['cnmp09']['deno_nomina']=null;
					$this->data['cnmp09']['deno_banco']=null;
					$this->data['cnmp09']['deno_sucursal']=null;
					$this->data['cnmp09']['cod_banco_autor']=null;
					$this->data['cnmp09']['deno_banco_autor']=null;
					$this->data['cnmp09']['cod_sucursal_autor']=null;
					$this->data['cnmp09']['deno_sucursal_autor']=null;

	$entidades=$this->cstd01_entidades_bancarias->generateList('',' cod_entidad_bancaria ASC', null, '{n}.cstd01_entidades_bancarias.cod_entidad_bancaria', '{n}.cstd01_entidades_bancarias.denominacion');
	$entidades = $entidades != null ? $entidades : array();
	$this->set('entidades', $entidades);

}

function mostrar1($opcion=null,$var=null){
	$this->layout="ajax";
	if($var!=''){
		switch($opcion){
			case 'nomina':
				$this->set('codigo',$opcion);
				$this->set('valor', $var);
                echo "<script>";
//                	echo "document.getElementById('radio_si_no_1').checked=false;";
//                	echo "document.getElementById('radio_si_no_2').checked=false;";
                	echo "document.getElementById('cod_transaccion').value='';";
					echo "document.getElementById('deno_transaccionx').value='';";
					echo "document.getElementById('cod_banco').value='';";
					echo "document.getElementById('deno_bancox').value='';";
					echo "document.getElementById('cod_sucursal').value='';";
					echo "document.getElementById('deno_sucursalx').value='';";
					echo "document.getElementById('bancariox').value='';";
					echo "document.getElementById('beneficiario').value='';";
				echo "</script>";
			break;
			case 'deno_nomina':
				$deno_nomina = $this->Cnmd01->field('denominacion', $conditions = $this->condicion()." and Cnmd01.cod_tipo_nomina='$var'", $order ="cod_tipo_nomina ASC");
				$this->set('denomi', $deno_nomina);
				$this->set('denominacion',$opcion);
			break;
			case 'transaccion':
				$this->set('valor', $var);
				$this->set('codigo',$opcion);
				echo "<script>";
					echo "document.getElementById('cod_banco').value='';";
					echo "document.getElementById('deno_bancox').value='';";
					echo "document.getElementById('cod_sucursal').value='';";
					echo "document.getElementById('deno_sucursalx').value='';";
					echo "document.getElementById('bancariox').value='';";
					echo "document.getElementById('beneficiario').value='';";
				echo "</script>";
			break;
			case 'deno_transaccion':

				/*if(!isset($_SESSION['cod_tipo'])){
					$cod_tipo=2;
				}else{
					$cod_tipo=$this->Session->read('cod_tipo');
				}*/
				$cod_tipo=2;
				$deno_banco = $this->cnmd03_transacciones->field('denominacion', $conditions = "cod_tipo_transaccion='$cod_tipo' and cod_transaccion='$var'", $order ="cod_transaccion ASC");
				$this->set('denomi', $deno_banco);
				$this->set('denominacion',$opcion);
			break;
			case 'banco':
				$this->set('valor', $var);
				$this->set('codigo',$opcion);
				echo "<script>";
					echo "document.getElementById('cod_sucursal').value='';";
					echo "document.getElementById('deno_sucursalx').value='';";
					echo "document.getElementById('bancariox').value='';";
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
			case 'cuentas_bancarias':
				$cod3=$this->Session->read('cod3');
				$cuenta = $this->cstd02_cuentas_bancarias->field('cuenta_bancaria', $conditions = $this->SQLCA()." and cod_entidad_bancaria='$cod3' and cod_sucursal='$var'", $order ="cuenta_bancaria ASC");
				if($cuenta){
					echo "<script>";
						echo "document.getElementById('save').disabled=false;";
					echo "</script>";
				}else{
					echo "<script>";
						echo "document.getElementById('save').disabled='disabled';";
					echo "</script>";
				}
				$this->set('denomi', $cuenta);
				$this->set('denominacion','bancario');
			break;

		}// fin switch
	}else{
		$this->set('si','no');
	}
}// fin mostrar




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
			case 'transaccion':
				$this->set('no','');
				$this->set('SELECT','banco');
				$this->set('codigo','transaccion');
				$this->set('seleccion','');
				$this->set('n',2);
				$this->Session->write('cod_tipo',$var);
				$cond =" cod_tipo_transaccion=2";
				$lista=  $this->cnmd03_transacciones->generateList2($cond, 'cod_transaccion ASC', null, '{n}.cnmd03_transacciones.cod_transaccion', '{n}.cnmd03_transacciones.denominacion');
				$this->concatenaN($lista, 'vector');
			break;
			case 'banco':
				$this->set('no','');
				$this->set('SELECT','sucursal');
				$this->set('codigo','banco');
				$this->set('seleccion','');
				$this->set('n',3);
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
				$this->set('n',4);
				$this->Session->write('cod3',$var);
				$cond =" cod_entidad_bancaria=".$var;
				$lista=  $this->cstd01_sucursales_bancarias->generateList($cond, 'cod_sucursal ASC', null, '{n}.cstd01_sucursales_bancarias.cod_sucursal', '{n}.cstd01_sucursales_bancarias.denominacion');
				$this->set('vector',$lista);
			break;
			case 'cuenta':
				$this->set('no','no');
				$this->set('SELECT','cuenta');
				$this->set('codigo','cuenta');
				$this->set('seleccion','');
				$this->set('n',5);
				$cod3=$this->Session->read('cod3');
				$cond =$this->SQLCA()." and cod_entidad_bancaria=".$cod3." and cod_sucursal=".$var;
				$lista=  $this->cstd02_cuentas_bancarias->generateList($cond, 'cuenta_bancaria ASC', null, '{n}.cstd02_cuentas_bancarias.cuenta_bancaria', '{n}.cstd02_cuentas_bancarias.concepto_manejo');
				$this->concatena($lista, 'vector');
			break;
		}//fin switch
	}
}//fin select3


function select4($opcion=null,$var=null){
	$this->layout="ajax";
	if($var!=''){
		switch($opcion){
			case 'banco':
				// $this->Session->write('coda1',$var);
				$this->set('no','');
				$this->set('SELECT','sucursal');
				$this->set('codigo','banco_autor');
				$this->set('seleccion','');
				$this->set('n',1);
				$lista = $this->cstd01_entidades_bancarias->generateList(null, 'cod_entidad_bancaria ASC', null, '{n}.cstd01_entidades_bancarias.cod_entidad_bancaria', '{n}.cstd01_entidades_bancarias.denominacion');
				$this->set('vector',$lista);
			break;
			case 'sucursal':
				$this->Session->write('coda2',$var);
				$this->set('no','');
				$this->set('SELECT','defecto');
				$this->set('codigo','sucursal_autor');
				$this->set('seleccion','');
				$this->set('n',2);
				$cond="cod_entidad_bancaria=".$var;
				$deno_banco = $this->cstd01_entidades_bancarias->field('denominacion', $conditions = "cod_entidad_bancaria='$var'", $order ="cod_entidad_bancaria ASC");
				$lista = $this->cstd01_sucursales_bancarias->generateList($cond, 'cod_sucursal ASC', null, '{n}.cstd01_sucursales_bancarias.cod_sucursal', '{n}.cstd01_sucursales_bancarias.denominacion');
				$this->set('vector',$lista);

					echo "<script>";
						echo "document.getElementById('cod_banco_autor').value='".mascara($var, 4)."';";
						echo "document.getElementById('deno_banco_autor').value='".$deno_banco."';";
						echo "document.getElementById('cod_sucursal_autor').value='';";
						echo "document.getElementById('deno_sucursal_autor').value='';";
						echo "document.getElementById('cod_banco_cb').value='".mascara($var, 4)."';";
						echo "document.getElementById('cod_sucursal_cb').value='';";
					echo "</script>";

			break;
			case 'cuenta':
				// $this->Session->write('coda3',$var);
				$this->set('no','no');
				$this->set('SELECT','cuenta');
				$this->set('codigo','cuenta_autor');
				$this->set('seleccion','');
				$this->set('n',3);
				// $cod1=$this->Session->read('coda1');
				$cod2=$this->Session->read('coda2');
				$cond=$this->SQLCA()." and cod_entidad_bancaria=".$cod2." and cod_sucursal=".$var;
				$deno_sucursal = $this->cstd01_sucursales_bancarias->field('denominacion', $conditions = "cod_entidad_bancaria='$cod2' and cod_sucursal='$var'", $order ="cod_sucursal ASC");
				$lista = $this->cstd02_cuentas_bancarias->generateList($cond, 'cuenta_bancaria ASC', null, '{n}.cstd02_cuentas_bancarias.cuenta_bancaria', '{n}.cstd02_cuentas_bancarias.concepto_manejo');
				$this->set('vector',$lista);
				// $this->concatena($lista, 'vector');

					echo "<script>";
						echo "document.getElementById('cod_sucursal_autor').value='".mascara($var, 4)."';";
						echo "document.getElementById('deno_sucursal_autor').value='".$deno_sucursal."';";
						echo "document.getElementById('cod_sucursal_cb').value='".mascara($var, 4)."';";
					echo "</script>";

			break;
			case 'defecto':
				// $this->Session->write('coda3',$var);
				$this->set('no','defecto');
				// $this->set('SELECT','');
				$this->set('codigo','cuenta_autor');
				// $this->set('seleccion','');
				$this->set('n',4);
				// $cod1=$this->Session->read('coda1');
				$cod2=$this->Session->read('coda2');
				// $cond=$this->SQLCA()." and cod_entidad_bancaria=".$cod2." and cod_sucursal=".$var;
				$deno_sucursal = $this->cstd01_sucursales_bancarias->field('denominacion', $conditions = "cod_entidad_bancaria='$cod2' and cod_sucursal='$var'", $order ="cod_sucursal ASC");
				// $lista = $this->cstd02_cuentas_bancarias->generateList($cond, 'cuenta_bancaria ASC', null, '{n}.cstd02_cuentas_bancarias.cuenta_bancaria', '{n}.cstd02_cuentas_bancarias.concepto_manejo');
				// $this->set('vector',$lista);
				// $this->concatena($lista, 'vector');

					echo "<script>";
						echo "document.getElementById('cod_sucursal_autor').value='".mascara($var, 4)."';";
						echo "document.getElementById('deno_sucursal_autor').value='".$deno_sucursal."';";
						echo "document.getElementById('cod_sucursal_cb').value='".mascara($var, 4)."';";
					echo "</script>";

			break;
		}//fin switch
	}else{

		if($opcion == 'sucursal'){
					echo "<script>";
						echo "document.getElementById('cod_banco_autor').value='';";
						echo "document.getElementById('deno_banco_autor').value='';";
						echo "document.getElementById('cod_sucursal_autor').value='';";
						echo "document.getElementById('deno_sucursal_autor').value='';";
						echo "document.getElementById('cod_banco_cb').value='';";
						echo "document.getElementById('cod_sucursal_cb').value='';";
					echo "</script>";

		}else if($opcion == 'defecto'){

					echo "<script>";
						echo "document.getElementById('cod_sucursal_autor').value='';";
						echo "document.getElementById('deno_sucursal_autor').value='';";
						echo "document.getElementById('cod_sucursal_cb').value='';";
					echo "</script>";
		}
	}
}//fin select4

function guardar(){
	$this->layout="ajax";
	$nomina=$this->data['cnmp09']['cod_nomina'];
//	$tipo_transaccion=$this->data['cnmp09']['radio_tipo'];
	$tipo_transaccion=2;
	$transaccion=$this->data['cnmp09']['cod_transaccion'];
	$banco=$this->data['cnmp09']['cod_banco'];
	$sucursal=$this->data['cnmp09']['cod_sucursal'];
	$cuenta=$this->data['cnmp09']['cod_cuenta'];
	$personalidad=$this->data['cnmp09']['persona'];
	$rif=$this->data['cnmp09']['rif'];
	$beneficiario=$this->data['cnmp09']['beneficiario'];
	$cedula_autorizado=$this->data['cnmp09']['cedula_autorizado'];
	$banco_autor=$this->data['cnmp09']['scod_banco_autor'];
	$sucursal_autor=$this->data['cnmp09']['scod_sucursal_autor'];
	$cuenta_autor=$banco_autor.$sucursal_autor.$this->data['cnmp09']['scod_cuenta_autor'];

	if($cedula_autorizado == "" || $cedula_autorizado == null){
		$cedula_autorizado = 0;
	}

	$autorizado_cobrar=$this->data['cnmp09']['autorizado_cobrar'];

	if($banco_autor == "" || $banco_autor == null){
		$banco_autor = 0;
		$sucursal_autor = 0;
		$cuenta_autor = '';
	}

if(!$this->cnmd09_bancos_cancelan_fondos_terceros->FindAll($this->SQLCA()." and cod_tipo_nomina=".$nomina." and cod_tipo_transaccion=".$tipo_transaccion." and cod_transaccion=".$transaccion." and cod_entidad_bancaria=".$banco." and cod_sucursal=".$sucursal)){
	if($beneficiario!=""){
			$sql_insert = "INSERT INTO cnmd09_bancos_cancela_fondos_terceros VALUES(".$this->verifica_SS(1).", ".$this->verifica_SS(2).", ".$this->verifica_SS(3).",".$this->verifica_SS(4).",".$this->verifica_SS(5).",".$nomina.",".$tipo_transaccion.",".$transaccion.",".$banco.",".$sucursal.",'".$cuenta."','".$beneficiario."','".$personalidad."','".$rif."', '".$autorizado_cobrar."', '".$cedula_autorizado."', '".$banco_autor."', '".$sucursal_autor."', '".$cuenta_autor."')";
			$sw1 = $this->cnmd09_bancos_cancelan_fondos_terceros->execute($sql_insert);
			if($sw1>1){
				$this->set('Message_existe','registro exitoso');
			}
	}else{
		$this->set('errorMessage','El beneficiario no puede estar vacio');
	}//fin beneficiario
}else{
	$this->set('errorMessage','este registro ya existe');
}

	$this->data['cnmp09']['cod_nomina']=null;
	$this->data['cnmp09']['cod_transaccion']=null;
	$this->data['cnmp09']['cod_banco']=null;
	$this->data['cnmp09']['cod_sucursal']=null;
	$this->data['cnmp09']['bancario']=null;
	$this->data['cnmp09']['beneficiario']=null;
	$this->data['cnmp09']['deno_nomina']=null;
	$this->data['cnmp09']['deno_transaccion']=null;
	$this->data['cnmp09']['deno_banco']=null;
	$this->data['cnmp09']['deno_sucursal']=null;
	$this->data['cnmp09']['rif']=null;
	$this->data['cnmp09']['cod_banco_autor']=null;
	$this->data['cnmp09']['deno_banco_autor']=null;
	$this->data['cnmp09']['cod_sucursal_autor']=null;
	$this->data['cnmp09']['deno_sucursal_autor']=null;

	$this->index();
	$this->render('index');
}//fin guardar




function eliminar($cod_nomina=null,$cod_tipo_transaccion=null,$cod_transaccion=null,$banco=null,$cod_sucursal=null,$anterior=null){
 		$this->layout="ajax";

		$sql = "DELETE FROM cnmd09_bancos_cancela_fondos_terceros WHERE ".$this->SQLCA()." and cod_tipo_nomina=".$cod_nomina." and cod_tipo_transaccion=".$cod_tipo_transaccion." and cod_transaccion=".$cod_transaccion." and cod_entidad_bancaria=".$banco." and cod_sucursal=".$cod_sucursal;
		if($this->cnmd09_bancos_cancelan_fondos_terceros->execute($sql)>1){
			$this->set('Message_existe', 'EL REGISTRO FUE ELIMINADO EXITOSAMENTE');
			$Tfilas=$this->cnmd09_bancos_cancelan_fondos_terceros->findCount($this->SQLCA());
				if($Tfilas!=0){
				$this->consultar($anterior);
				$this->render("consultar");
				}else{
					$this->data['cnmp09']['cod_nomina']=null;
					$this->data['cnmp09']['cod_banco']=null;
					$this->data['cnmp09']['cod_sucursal']=null;
					$this->data['cnmp09']['bancario']=null;
					$this->data['cnmp09']['beneficiario']=null;
					$this->data['cnmp09']['deno_parroquia']=null;
					$this->data['cnmp09']['deno_banco']=null;
					$this->data['cnmp09']['deno_sucursal']=null;
					$this->data['cnmp09']['rif']=null;
					$this->data['cnmp09']['cod_banco_autor']=null;
					$this->data['cnmp09']['deno_banco_autor']=null;
					$this->data['cnmp09']['cod_sucursal_autor']=null;
					$this->data['cnmp09']['deno_sucursal_autor']=null;
					$this->index();
					$this->render("index");

				}/////HASTA AQUI
		}else{
			$this->set('errorMessage', 'EL REGISTRO NO PUDO SER ELIMINADO');
			$this->consultar($anterior);
			$this->render("consultar");
		}
 }//eliminar





function consultar_xnom($codt_nomina = null, $pagina=null) {
	$this->layout="ajax";
	$this->set('entidad_federal', $this->Session->read('entidad_federal'));
if($codt_nomina != null){
	if(isset($pagina)){
		$Tfilas=$this->cnmd09_bancos_cancelan_fondos_terceros->findCount($this->SQLCA(). " and cod_tipo_nomina=$codt_nomina");
        if($Tfilas!=0){
        	$data=$this->cnmd09_bancos_cancelan_fondos_terceros->findAll($this->SQLCA(). " and cod_tipo_nomina=$codt_nomina",null,"cod_tipo_nomina,cod_tipo_transaccion,cod_transaccion,cod_entidad_bancaria,cod_sucursal ASC",1,$pagina,null);

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
		$Tfilas=$this->cnmd09_bancos_cancelan_fondos_terceros->findCount($this->SQLCA(). " and cod_tipo_nomina=$codt_nomina");

        if($Tfilas!=0){
        	$data=$this->cnmd09_bancos_cancelan_fondos_terceros->findAll($this->SQLCA(). " and cod_tipo_nomina=$codt_nomina",null,"cod_tipo_nomina,cod_tipo_transaccion,cod_transaccion,cod_entidad_bancaria,cod_sucursal ASC",1,$pagina,null);
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
					$this->data['cnmp09']['cod_estado']=null;
					$this->data['cnmp09']['cod_municipio']=null;
					$this->data['cnmp09']['cod_parroquia']=null;
					$this->data['cnmp09']['cod_banco']=null;
					$this->data['cnmp09']['cod_sucursal']=null;
					$this->data['cnmp09']['bancario']=null;
					$this->data['cnmp09']['beneficiario']=null;
					$this->data['cnmp09']['deno_nomina']=null;
					$this->data['cnmp09']['deno_estado']=null;
					$this->data['cnmp09']['deno_municipio']=null;
					$this->data['cnmp09']['deno_parroquia']=null;
					$this->data['cnmp09']['deno_banco']=null;
					$this->data['cnmp09']['deno_sucursal']=null;
					$this->data['cnmp09']['cod_banco_autor']=null;
					$this->data['cnmp09']['deno_banco_autor']=null;
					$this->data['cnmp09']['cod_sucursal_autor']=null;
					$this->data['cnmp09']['deno_sucursal_autor']=null;
	 	       $this->index();
			   $this->render("index");
			   return;
        }
	}

	$this->set('cod_nomina', $data[0]['cnmd09_bancos_cancelan_fondos_terceros']['cod_tipo_nomina']);
	$this->set('cod_tipo_transaccion', $data[0]['cnmd09_bancos_cancelan_fondos_terceros']['cod_tipo_transaccion']);
	$this->set('cod_transaccion', $data[0]['cnmd09_bancos_cancelan_fondos_terceros']['cod_transaccion']);
	$this->set('banco', $data[0]['cnmd09_bancos_cancelan_fondos_terceros']['cod_entidad_bancaria']);
	$this->set('cod_sucursal', $data[0]['cnmd09_bancos_cancelan_fondos_terceros']['cod_sucursal']);
	$this->set('cuenta', $data[0]['cnmd09_bancos_cancelan_fondos_terceros']['cuenta_bancaria']);
	$this->set('beneficiario', $data[0]['cnmd09_bancos_cancelan_fondos_terceros']['beneficiario']);
	$this->set('rif', $data[0]['cnmd09_bancos_cancelan_fondos_terceros']['rif_cedula']);
	$this->set('personalidad', $data[0]['cnmd09_bancos_cancelan_fondos_terceros']['personalidad']);
	$this->set('autorizado_cobrar', $data[0]['cnmd09_bancos_cancelan_fondos_terceros']['autorizado_a_cobrar']);
	$this->set('cedula_autorizado', $data[0]['cnmd09_bancos_cancelan_fondos_terceros']['cedula_ident_autorizado']);

	$deno_nomina = $this->Cnmd01->field('denominacion', $conditions = $this->condicion()." and Cnmd01.cod_tipo_nomina=".$data[0]['cnmd09_bancos_cancelan_fondos_terceros']['cod_tipo_nomina']);
	$this->set('deno_nomina', $deno_nomina);
	$deno_banco = $this->cstd01_entidades_bancarias->field('denominacion', $conditions = "cod_entidad_bancaria=".$data[0]['cnmd09_bancos_cancelan_fondos_terceros']['cod_entidad_bancaria']);
	$this->set('deno_banco', $deno_banco);
	$deno_sucursal = $this->cstd01_sucursales_bancarias->field('denominacion', $conditions = "cod_entidad_bancaria=".$data[0]['cnmd09_bancos_cancelan_fondos_terceros']['cod_entidad_bancaria']." and cod_sucursal=".$data[0]['cnmd09_bancos_cancelan_fondos_terceros']['cod_sucursal']);
	$this->set('deno_sucursal', $deno_sucursal);
	$deno_transaccion = $this->cnmd03_transacciones->field('denominacion', $conditions = "cod_tipo_transaccion=".$data[0]['cnmd09_bancos_cancelan_fondos_terceros']['cod_tipo_transaccion']." and cod_transaccion=".$data[0]['cnmd09_bancos_cancelan_fondos_terceros']['cod_transaccion']);
	$this->set('deno_transaccion', $deno_transaccion);



if($data[0]['cnmd09_bancos_cancelan_fondos_terceros']['cod_banco_autorizado'] != "" || $data[0]['cnmd09_bancos_cancelan_fondos_terceros']['cod_banco_autorizado'] != null){

$cod_banco_autor = $data[0]['cnmd09_bancos_cancelan_fondos_terceros']['cod_banco_autorizado'];
$cod_sucursal_autor = $data[0]['cnmd09_bancos_cancelan_fondos_terceros']['cod_sucursal_autorizado'];
$cond=$this->SQLCA()." and cod_entidad_bancaria=".$cod_banco_autor." and cod_sucursal=".$cod_sucursal_autor;

$deno_banco = $this->cstd01_entidades_bancarias->field('denominacion', $conditions = "cod_entidad_bancaria='$cod_banco_autor'", $order ="cod_entidad_bancaria ASC");
$deno_sucursal = $this->cstd01_sucursales_bancarias->field('denominacion', $conditions = "cod_entidad_bancaria='$cod_banco_autor' and cod_sucursal='$cod_sucursal_autor'", $order ="cod_sucursal ASC");
// $cuenta_banc_autorizado = $this->cstd02_cuentas_bancarias->field('cuenta_bancaria', $conditions = $cond." and cuenta_bancaria='".$data[0]['cnmd09_bancos_cancelan_fondos_terceros']['cuenta_bancaria_autorizado']."'", $order ="cuenta_bancaria ASC");

	$this->set('cod_banco_autorizado', $cod_banco_autor);
	$this->set('deno_banco_autor', $deno_banco);
	$this->set('cod_sucursal_autorizado', $cod_sucursal_autor);
	$this->set('deno_sucursal_autor', $deno_sucursal);
	$this->set('cuenta_bancaria_autorizado', $data[0]['cnmd09_bancos_cancelan_fondos_terceros']['cuenta_banc_autorizado']);
}else{

	$this->set('cod_banco_autorizado', '');
	$this->set('deno_banco_autor', '');
	$this->set('cod_sucursal_autorizado', '');
	$this->set('deno_sucursal_autor', '');
	$this->set('cuenta_bancaria_autorizado', '');
}




}else{
	$lista = $this->Cnmd01->generateList($conditions = $this->condicion(), $order = 'cod_tipo_nomina', $limit = null, '{n}.Cnmd01.cod_tipo_nomina', '{n}.Cnmd01.denominacion');
	$this->concatenaN($lista, 'nomina');

	$this->set('cod_nomina', '');
	$this->set('cod_tipo_transaccion', '');
	$this->set('cod_transaccion', '');
	$this->set('banco', '');
	$this->set('cod_sucursal', '');
	$this->set('cuenta', '');
	$this->set('beneficiario', '');
	$this->set('rif', '');
	$this->set('personalidad', '');

	$this->set('deno_nomina', '');
	$this->set('deno_banco', '');
	$this->set('deno_sucursal', '');
	$this->set('deno_transaccion', '');

	$this->set('cod_banco_autorizado', '');
	$this->set('deno_banco_autor', '');
	$this->set('cod_sucursal_autorizado', '');
	$this->set('deno_sucursal_autor', '');
	$this->set('cuenta_bancaria_autorizado', '');

	$this->set('siguiente',null);
	$this->set('anterior',null);
	$this->set('mostrarA',null);
	$this->set('mostrarS',null);
	$this->set('numT',null);
	$this->set('numP',null);
	$this->set('pagina',null);
}
} // fin function consultar_xnom





function consultar($pagina=null) {
	$this->layout="ajax";
	$this->set('entidad_federal', $this->Session->read('entidad_federal'));

	if(isset($pagina)){
		$Tfilas=$this->cnmd09_bancos_cancelan_fondos_terceros->findCount($this->SQLCA());
        if($Tfilas!=0){
        	$data=$this->cnmd09_bancos_cancelan_fondos_terceros->findAll($this->SQLCA(),null,"cod_tipo_nomina,cod_tipo_transaccion,cod_transaccion,cod_entidad_bancaria,cod_sucursal ASC",1,$pagina,null);

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
		$Tfilas=$this->cnmd09_bancos_cancelan_fondos_terceros->findCount($this->SQLCA());

        if($Tfilas!=0){
        	$data=$this->cnmd09_bancos_cancelan_fondos_terceros->findAll($this->SQLCA(),null,"cod_tipo_nomina,cod_tipo_transaccion,cod_transaccion,cod_entidad_bancaria,cod_sucursal ASC",1,$pagina,null);
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
					$this->data['cnmp09']['cod_estado']=null;
					$this->data['cnmp09']['cod_municipio']=null;
					$this->data['cnmp09']['cod_parroquia']=null;
					$this->data['cnmp09']['cod_banco']=null;
					$this->data['cnmp09']['cod_sucursal']=null;
					$this->data['cnmp09']['bancario']=null;
					$this->data['cnmp09']['beneficiario']=null;
					$this->data['cnmp09']['deno_nomina']=null;
					$this->data['cnmp09']['deno_estado']=null;
					$this->data['cnmp09']['deno_municipio']=null;
					$this->data['cnmp09']['deno_parroquia']=null;
					$this->data['cnmp09']['deno_banco']=null;
					$this->data['cnmp09']['deno_sucursal']=null;
					$this->data['cnmp09']['cod_banco_autor']=null;
					$this->data['cnmp09']['deno_banco_autor']=null;
					$this->data['cnmp09']['cod_sucursal_autor']=null;
					$this->data['cnmp09']['deno_sucursal_autor']=null;
	 	       $this->index();
			   $this->render("index");
			   return;
        }
	}

	$this->set('cod_nomina', $data[0]['cnmd09_bancos_cancelan_fondos_terceros']['cod_tipo_nomina']);
	$this->set('cod_tipo_transaccion', $data[0]['cnmd09_bancos_cancelan_fondos_terceros']['cod_tipo_transaccion']);
	$this->set('cod_transaccion', $data[0]['cnmd09_bancos_cancelan_fondos_terceros']['cod_transaccion']);
	$this->set('banco', $data[0]['cnmd09_bancos_cancelan_fondos_terceros']['cod_entidad_bancaria']);
	$this->set('cod_sucursal', $data[0]['cnmd09_bancos_cancelan_fondos_terceros']['cod_sucursal']);
	$this->set('cuenta', $data[0]['cnmd09_bancos_cancelan_fondos_terceros']['cuenta_bancaria']);
	$this->set('beneficiario', $data[0]['cnmd09_bancos_cancelan_fondos_terceros']['beneficiario']);
	$this->set('rif', $data[0]['cnmd09_bancos_cancelan_fondos_terceros']['rif_cedula']);
	$this->set('personalidad', $data[0]['cnmd09_bancos_cancelan_fondos_terceros']['personalidad']);
	$this->set('autorizado_cobrar', $data[0]['cnmd09_bancos_cancelan_fondos_terceros']['autorizado_a_cobrar']);
	$this->set('cedula_autorizado', $data[0]['cnmd09_bancos_cancelan_fondos_terceros']['cedula_ident_autorizado']);

	$deno_nomina = $this->Cnmd01->field('denominacion', $conditions = $this->condicion()." and Cnmd01.cod_tipo_nomina=".$data[0]['cnmd09_bancos_cancelan_fondos_terceros']['cod_tipo_nomina']);
	$this->set('deno_nomina', $deno_nomina);
	$deno_banco = $this->cstd01_entidades_bancarias->field('denominacion', $conditions = "cod_entidad_bancaria=".$data[0]['cnmd09_bancos_cancelan_fondos_terceros']['cod_entidad_bancaria']);
	$this->set('deno_banco', $deno_banco);
	$deno_sucursal = $this->cstd01_sucursales_bancarias->field('denominacion', $conditions = "cod_entidad_bancaria=".$data[0]['cnmd09_bancos_cancelan_fondos_terceros']['cod_entidad_bancaria']." and cod_sucursal=".$data[0]['cnmd09_bancos_cancelan_fondos_terceros']['cod_sucursal']);
	$this->set('deno_sucursal', $deno_sucursal);
	$deno_transaccion = $this->cnmd03_transacciones->field('denominacion', $conditions = "cod_tipo_transaccion=".$data[0]['cnmd09_bancos_cancelan_fondos_terceros']['cod_tipo_transaccion']." and cod_transaccion=".$data[0]['cnmd09_bancos_cancelan_fondos_terceros']['cod_transaccion']);
	$this->set('deno_transaccion', $deno_transaccion);



if($data[0]['cnmd09_bancos_cancelan_fondos_terceros']['cod_banco_autorizado'] != "" || $data[0]['cnmd09_bancos_cancelan_fondos_terceros']['cod_banco_autorizado'] != null){

$cod_banco_autor = $data[0]['cnmd09_bancos_cancelan_fondos_terceros']['cod_banco_autorizado'];
$cod_sucursal_autor = $data[0]['cnmd09_bancos_cancelan_fondos_terceros']['cod_sucursal_autorizado'];
$cond=$this->SQLCA()." and cod_entidad_bancaria=".$cod_banco_autor." and cod_sucursal=".$cod_sucursal_autor;

$deno_banco = $this->cstd01_entidades_bancarias->field('denominacion', $conditions = "cod_entidad_bancaria='$cod_banco_autor'", $order ="cod_entidad_bancaria ASC");
$deno_sucursal = $this->cstd01_sucursales_bancarias->field('denominacion', $conditions = "cod_entidad_bancaria='$cod_banco_autor' and cod_sucursal='$cod_sucursal_autor'", $order ="cod_sucursal ASC");
// $cuenta_banc_autorizado = $this->cstd02_cuentas_bancarias->field('cuenta_bancaria', $conditions = $cond." and cuenta_bancaria='".$data[0]['cnmd09_bancos_cancelan_fondos_terceros']['cuenta_bancaria_autorizado']."'", $order ="cuenta_bancaria ASC");

	$this->set('cod_banco_autorizado', $cod_banco_autor);
	$this->set('deno_banco_autor', $deno_banco);
	$this->set('cod_sucursal_autorizado', $cod_sucursal_autor);
	$this->set('deno_sucursal_autor', $deno_sucursal);
	$this->set('cuenta_bancaria_autorizado', $data[0]['cnmd09_bancos_cancelan_fondos_terceros']['cuenta_banc_autorizado']);
}else{

	$this->set('cod_banco_autorizado', '');
	$this->set('deno_banco_autor', '');
	$this->set('cod_sucursal_autorizado', '');
	$this->set('deno_sucursal_autor', '');
	$this->set('cuenta_bancaria_autorizado', '');
}

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


 function modificar($cod_nomina=null,$cod_tipo_transaccion=null,$cod_transaccion=null,$banco=null,$cod_sucursal=null,$pagina=null){
 	$this->layout="ajax";

 	$data=$this->cnmd09_bancos_cancelan_fondos_terceros->findAll($this->SQLCA()." and cod_tipo_nomina=".$cod_nomina."and cod_tipo_transaccion=".$cod_tipo_transaccion." and cod_transaccion=".$cod_transaccion."and cod_entidad_bancaria=".$banco." and cod_sucursal=".$cod_sucursal);
	$this->set('cod_nomina', $data[0]['cnmd09_bancos_cancelan_fondos_terceros']['cod_tipo_nomina']);
	$this->set('cod_tipo_transaccion', $data[0]['cnmd09_bancos_cancelan_fondos_terceros']['cod_tipo_transaccion']);
	$this->set('cod_transaccion', $data[0]['cnmd09_bancos_cancelan_fondos_terceros']['cod_transaccion']);
	$this->set('banco', $data[0]['cnmd09_bancos_cancelan_fondos_terceros']['cod_entidad_bancaria']);
	$this->set('cod_sucursal', $data[0]['cnmd09_bancos_cancelan_fondos_terceros']['cod_sucursal']);
	$this->set('cuenta', $data[0]['cnmd09_bancos_cancelan_fondos_terceros']['cuenta_bancaria']);
	$this->set('beneficiario', $data[0]['cnmd09_bancos_cancelan_fondos_terceros']['beneficiario']);
	$this->set('rif', $data[0]['cnmd09_bancos_cancelan_fondos_terceros']['rif_cedula']);
	$this->set('personalidad', $data[0]['cnmd09_bancos_cancelan_fondos_terceros']['personalidad']);
	$this->set('autorizado_cobrar', $data[0]['cnmd09_bancos_cancelan_fondos_terceros']['autorizado_a_cobrar']);
	$this->set('cedula_autorizado', $data[0]['cnmd09_bancos_cancelan_fondos_terceros']['cedula_ident_autorizado']);
	$this->set('pagina', $pagina);

	$deno_nomina = $this->Cnmd01->field('denominacion', $conditions = $this->condicion()." and Cnmd01.cod_tipo_nomina=".$data[0]['cnmd09_bancos_cancelan_fondos_terceros']['cod_tipo_nomina']);
	$this->set('deno_nomina', $deno_nomina);
	$deno_banco = $this->cstd01_entidades_bancarias->field('denominacion', $conditions = "cod_entidad_bancaria=".$data[0]['cnmd09_bancos_cancelan_fondos_terceros']['cod_entidad_bancaria']);
	$this->set('deno_banco', $deno_banco);
	$deno_sucursal = $this->cstd01_sucursales_bancarias->field('denominacion', $conditions = "cod_entidad_bancaria=".$data[0]['cnmd09_bancos_cancelan_fondos_terceros']['cod_entidad_bancaria']." and cod_sucursal=".$data[0]['cnmd09_bancos_cancelan_fondos_terceros']['cod_sucursal']);
	$this->set('deno_sucursal', $deno_sucursal);
	$deno_transaccion = $this->cnmd03_transacciones->field('denominacion', $conditions = "cod_tipo_transaccion=".$data[0]['cnmd09_bancos_cancelan_fondos_terceros']['cod_tipo_transaccion']." and cod_transaccion=".$data[0]['cnmd09_bancos_cancelan_fondos_terceros']['cod_transaccion']);
	$this->set('deno_transaccion', $deno_transaccion);


if($data[0]['cnmd09_bancos_cancelan_fondos_terceros']['cod_banco_autorizado'] != "" || $data[0]['cnmd09_bancos_cancelan_fondos_terceros']['cod_banco_autorizado'] != null){

$cod_banco_autor = $data[0]['cnmd09_bancos_cancelan_fondos_terceros']['cod_banco_autorizado'];
$cod_sucursal_autor = $data[0]['cnmd09_bancos_cancelan_fondos_terceros']['cod_sucursal_autorizado'];
$cond=$this->SQLCA()." and cod_entidad_bancaria=".$cod_banco_autor." and cod_sucursal=".$cod_sucursal_autor;

	$lista = $this->cstd01_entidades_bancarias->generateList(null, 'cod_entidad_bancaria ASC', null, '{n}.cstd01_entidades_bancarias.cod_entidad_bancaria', '{n}.cstd01_entidades_bancarias.denominacion');
	$this->set('vector_bancos',$lista);

	$lista2 = $this->cstd01_sucursales_bancarias->generateList("cod_entidad_bancaria=".$cod_banco_autor, 'cod_sucursal ASC', null, '{n}.cstd01_sucursales_bancarias.cod_sucursal', '{n}.cstd01_sucursales_bancarias.denominacion');
	$this->set('vector_sucursales',$lista2);

	/*
	$lista3 = $this->cstd02_cuentas_bancarias->generateList($cond, 'cuenta_bancaria ASC', null, '{n}.cstd02_cuentas_bancarias.cuenta_bancaria', '{n}.cstd02_cuentas_bancarias.concepto_manejo');
	$this->set('vector_cuentas',$lista3);
	*/
$deno_banco = $this->cstd01_entidades_bancarias->field('denominacion', $conditions = "cod_entidad_bancaria='$cod_banco_autor'", $order ="cod_entidad_bancaria ASC");
$deno_sucursal = $this->cstd01_sucursales_bancarias->field('denominacion', $conditions = "cod_entidad_bancaria='$cod_banco_autor' and cod_sucursal='$cod_sucursal_autor'", $order ="cod_sucursal ASC");
// $cuenta_banc_autorizado = $this->cstd02_cuentas_bancarias->field('cuenta_bancaria', $conditions = $cond." and cuenta_bancaria='".$data[0]['cnmd09_bancos_cancelan_fondos_terceros']['cuenta_banc_autorizado']."'", $order ="cuenta_bancaria ASC");

	$this->set('cod_banco_autorizado', $cod_banco_autor);
	$this->set('deno_banco_autor', $deno_banco);
	$this->set('cod_sucursal_autorizado', $cod_sucursal_autor);
	$this->set('deno_sucursal_autor', $deno_sucursal);
	$this->set('cuenta_bancaria_autorizado', $data[0]['cnmd09_bancos_cancelan_fondos_terceros']['cuenta_banc_autorizado']);
}else{

	$lista = $this->cstd01_entidades_bancarias->generateList(null, 'cod_entidad_bancaria ASC', null, '{n}.cstd01_entidades_bancarias.cod_entidad_bancaria', '{n}.cstd01_entidades_bancarias.denominacion');
	$this->set('vector_bancos',$lista);
	$this->set('vector_sucursales', array());
	// $this->set('vector_cuentas', array());

	$this->set('cod_banco_autorizado', '');
	$this->set('deno_banco_autor', '');
	$this->set('cod_sucursal_autorizado', '');
	$this->set('deno_sucursal_autor', '');
	$this->set('cuenta_bancaria_autorizado', '');
}


 }//fin modificar


function guardar_modificar($cod=null,$pagina=null){
	$this->layout="ajax";
	    $nomina=$this->data['cnmp09']['cod_nomina'];
		$transaccion=$this->data['cnmp09']['cod_transaccion'];
		$banco=$this->data['cnmp09']['cod_banco'];
		$sucursal=$this->data['cnmp09']['cod_sucursal'];
		$cuenta=$this->data['cnmp09']['bancario'];
		$rif=$this->data['cnmp09']['rif'];
		$personalidad=$this->data['cnmp09']['persona'];
		$beneficiario=$this->data['cnmp09']['beneficiario'];
		$autorizado_cobrar=$this->data['cnmp09']['autorizado_cobrar'];
		$cedula_autorizado=$this->data['cnmp09']['cedula_autorizado'];
		$banco_autor=$this->data['cnmp09']['scod_banco_autor'];
		$sucursal_autor=$this->data['cnmp09']['scod_sucursal_autor'];
		$cuenta_autor=$banco_autor.$sucursal_autor.$this->data['cnmp09']['scod_cuenta_autor'];

		if($cedula_autorizado == "" || $cedula_autorizado == null){
			$cedula_autorizado = 0;
		}

	if($banco_autor == "" || $banco_autor == null){
		$banco_autor = 0;
		$sucursal_autor = 0;
		$cuenta_autor = '';
	}

			$v=$this->cnmd09_bancos_cancelan_fondos_terceros->execute("update cnmd09_bancos_cancela_fondos_terceros set personalidad='".$personalidad."',rif_cedula='".$rif."',beneficiario='".$beneficiario."', autorizado_a_cobrar='".$autorizado_cobrar."', cedula_ident_autorizado='".$cedula_autorizado."', cod_banco_autorizado='".$banco_autor."', cod_sucursal_autorizado='".$sucursal_autor."', cuenta_banc_autorizado='".$cuenta_autor."' where ".$this->SQLCA()." and cod_tipo_nomina=".$nomina." and cod_tipo_transaccion=".$cod." and cod_transaccion=".$transaccion." and cod_entidad_bancaria=".$banco." and cod_sucursal=".$sucursal);
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