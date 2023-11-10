<?php
/*
 * Fecha: 19/07/2007
 *
 * Por Erisk G. Aragol H.
 *
 * Herramienta utilizada: easyEclipse
 * sisap
 */
class Cnmp09RegistroFrecuenciaPagoTransaccionesController extends AppController {
   var $name = 'cnmp09_registro_frecuencia_pago_transacciones';
   var $uses = array('Cnmd01',  'cnmd03_transacciones','v_cnmd09_frecuencia', 'cnmd09_frecuencia','cugd01_estados','cugd01_municipios','cugd01_parroquias','cstd01_entidades_bancarias','cstd01_sucursales_bancarias','cstd02_cuentas_bancarias');
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




		function SQLCAIN($ano=null){//sql para busqueda de codigos de arranque con y sin año
				 $sql_re = $this->verifica_SS(1).",";
				 $sql_re .= $this->verifica_SS(2).",";
				 $sql_re .=  $this->verifica_SS(3).",";
				 $sql_re .= $this->verifica_SS(4).",";
				 if($ano!=null){
					 $sql_re .= $this->verifica_SS(5).",";
						$sql_re .= $ano."";
				 }else{
					 $sql_re .=  $this->verifica_SS(5)."";
				 }
				 return $sql_re;
		}//fin funcion SQLCAIN





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
  $cod_presi = $this->Session->read('SScodpresi');
  $cod_entidad = $this->Session->read('SScodentidad');
  $cod_tipo_inst = $this->Session->read('SScodtipoinst');
  $cod_inst = $this->Session->read('SScodinst');
  $cod_dep = $this->Session->read('SScoddep');
  $modulo = $this->Session->read('Modulo');
  $condicion = "Cnmd01.cod_presi = ".$cod_presi." and Cnmd01.cod_entidad = ".$cod_entidad." and Cnmd01.cod_tipo_inst = ".$cod_tipo_inst." and Cnmd01.cod_inst = ".$cod_inst." and Cnmd01.cod_dep = ".$cod_dep;

	if(isset($cod)){
		$this->set('cod_nomina',$cod);
		$deno_nomina = $this->Cnmd01->field('denominacion', $conditions = $condicion." and Cnmd01.cod_tipo_nomina='$cod'", $order ="cod_tipo_nomina ASC");
		$this->set('denomi', $deno_nomina);
	}
	$lista = $this->Cnmd01->generateList($conditions = $condicion." and (Cnmd01.status_nomina=0 OR Cnmd01.status_nomina=1)", $order = 'cod_tipo_nomina', $limit = null, '{n}.Cnmd01.cod_tipo_nomina', '{n}.Cnmd01.denominacion');
	$this->concatenaN($lista, 'nomina');
	//echo $this->condicion();
	//print_r($lista);

	$cnmd03 = $this->cnmd03_transacciones->generateList2($conditions ='cod_tipo_transaccion = 1', $order = null, $limit = null, '{n}.cnmd03_transacciones.cod_transaccion', '{n}.cnmd03_transacciones.denominacion');
	$this->concatenaN($cnmd03, 'transaccion');


					$this->data['cnmp09']['cod_nomina']=null;
					$this->data['cnmp09']['cod_estado']=null;
					$this->data['cnmp09']['cod_transaccion']=null;
					$this->data['cnmp09']['deno_transaccion']=null;
					$this->data['cnmp09']['frecuencia']=null;
}

function mostrar1($opcion=null,$var=null){
	$this->layout="ajax";//echo "sisinonono";
	if($var!=''){
		switch($opcion){
			case 'nomina':
				$this->set('codigo',$opcion);
				$this->set('valor', $var);
                echo "<script>";
                	echo "document.getElementById('cod_transaccion').value='';";
					echo "document.getElementById('deno_transaccionx').value='';";
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

			break;
			case 'deno_transaccion':
				$cod_tipo=$this->Session->read('cod_tipo');
				$deno_banco = $this->cnmd03_transacciones->field('denominacion', $conditions = "cod_tipo_transaccion='$cod_tipo' and cod_transaccion='$var'", $order ="cod_transaccion ASC");
				$this->set('denomi', $deno_banco);
				$this->set('denominacion',$opcion);
			break;
		}// fin switch
	}else{
		$this->set('si','no');
	}
}// fin mostrar



function mostrar2($opcion=null,$var=null){
	$this->layout="ajax";//echo "sisinonono";
	if($var!=''){
		switch($opcion){
			case 'nomina':
				$this->set('codigo',$opcion);
				$this->set('valor', mascara_tres($var));
                $deno_nomina = $this->Cnmd01->field('denominacion', $conditions = $this->condicion()." and Cnmd01.cod_tipo_nomina='$var'", $order ="cod_tipo_nomina ASC");
				$this->set('deno_nomina', $deno_nomina);
			break;
		}// fin switch
	}else{
		$this->set('si','no');
	}
}// fin mostrar2



function operacion_frecuencia($tipo_ope = null){
	$this->layout="ajax";
	$this->set('tipo_ope', $tipo_ope);
	if($tipo_ope == '2'){
		echo "<script language='JavaScript' type='text/javascript'>
				ver_documento('/cnmp09_registro_frecuencia_pago_transacciones/operacion_frecuencia/999','lista_frecuencias');
			</script>";

		$datos_frec = $this->v_cnmd09_frecuencia->execute("SELECT * FROM v_cnmp99_prenomina_frecuencia WHERE " . $this->SQLCA(), null, "cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, nomina, cod_tipo_transaccion, tipo, cod_transaccion, transaccion, frecuencia ASC;");
		if(!empty($datos_frec)){
			$this->set('Message_existe', "Presione sobre el bot&oacute;n <u>Generar Frecuencia Automatica</u> para registrar las transacciones de las n&oacute;minas...");
		}else{
			$this->set('errorMessage', "No hay transacciones en n&oacute;minas para registrar...");
		}
		$this->set('datos_frec', $datos_frec);
	}
} // fin function operacion_frecuencia, sirve para registrar las transacciones de forma manual:(1) o automatica en todas las nominas:(2) de la dep.


function generar_frecuencias(){
	$this->layout="ajax";
	$tipo_ope = $this->data['cnmp09']['tipo_ope_frecuencia'];
	if($tipo_ope == '2'){
		$datos_frec = $this->v_cnmd09_frecuencia->execute("SELECT * FROM v_cnmp99_prenomina_frecuencia WHERE " . $this->SQLCA(), null, "cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, nomina, cod_tipo_transaccion, tipo, cod_transaccion, transaccion, frecuencia ASC;");
		if(!empty($datos_frec)){
			foreach($datos_frec as $rdatos_frec){
				$FRECUENCIAS[]="(".$this->SQLCAIN().",".$rdatos_frec[0]['cod_tipo_nomina'].",".$rdatos_frec[0]['cod_tipo_transaccion'].",".$rdatos_frec[0]['cod_transaccion'].",".$rdatos_frec[0]['frecuencia'].")";
			} // fin foreach
			$VALUES_FRECUENCIA = implode(',', $FRECUENCIAS);
			$rsw = $this->v_cnmd09_frecuencia->execute("INSERT INTO cnmd09_frecuencia VALUES ".$VALUES_FRECUENCIA.";");
			if($rsw > 1){
				$this->set('Message_existe','Los datos fueron procesados exitosamente.');
			}else{
				$this->set('errorMessage','Los datos no fueron procesados. Intente Nuevamente...');
			}
		}else{
			$this->set('errorMessage','No hay datos para procesar en este momento...');
		}
	}else{
		$this->set('errorMessage','Opcion Invalida... Seleccione Frecuencia de Forma Automatica...');
	}

	$this->index();
	$this->render('index');

} // fin function generar_frecuencias



function select3($opcion=null,$cod_nomina,$var=null){
	$this->layout="ajax";
	if($var!=''){
		switch($opcion){
			case 'transaccion':
				$this->set('no','no');
				$this->set('SELECT','banco');
				$this->set('codigo','transaccion');
				$this->set('seleccion','');
				$this->set('n',2);
				$this->Session->write('cod_tipo',$var);
				$cond =" cod_tipo_transaccion=".$var." and cod_transaccion not in (SELECT cod_transaccion FROM cnmd09_frecuencia WHERE ".$this->SQLCA()." and cod_tipo_nomina=".$cod_nomina." and cod_tipo_transaccion=".$var.")";
                $lista=  $this->cnmd03_transacciones->generateList2($cond, 'cod_transaccion ASC', null, '{n}.cnmd03_transacciones.cod_transaccion', '{n}.cnmd03_transacciones.denominacion');
				$this->concatenaN($lista, 'vector');
				echo "<script>
						document.getElementById('cod_transaccion').value='';
						document.getElementById('deno_transaccionx').value='';
					 </script>";
			break;

		}//fin switch
	}
}//fin select3




function guardar(){
	$this->layout="ajax";
	//pr($this->data);
	$nomina=$this->data['cnmp09']['cod_nomina'];
	$tipo_transaccion=$this->data['cnmp09']['cod_tipo_transaccion'];
	$transaccion=$this->data['cnmp09']['cod_transaccion'];
	$frecuencia=$this->data['cnmp09']['frecuencia'];
	//echo "es: ".$frecuencia;
if(!$this->cnmd09_frecuencia->FindAll($this->SQLCA()." and cod_tipo_nomina=".$nomina." and cod_tipo_transaccion=".$tipo_transaccion." and cod_transaccion=".$transaccion)){
	if($frecuencia!=""){
			$sql_insert = "INSERT INTO cnmd09_frecuencia VALUES(".$this->verifica_SS(1).", ".$this->verifica_SS(2).", ".$this->verifica_SS(3).",".$this->verifica_SS(4).",".$this->verifica_SS(5).",".$nomina.",".$tipo_transaccion.",".$transaccion.",".$frecuencia.")";
			$sw1 = $this->cnmd09_frecuencia->execute($sql_insert);
			if($sw1>1){
				$this->set('Message_existe','registro exitoso');
			}
	}else{
		$this->set('errorMessage','seleccione la frecuencia de pago');
	}//fin beneficiario
}else{
	$this->set('errorMessage','este registro ya existe');
}



	$this->index($nomina);
	$this->render('index');
}//fin guardar




function eliminar($cod_nomina=null,$cod_tipo_transaccion=null,$cod_transaccion=null){
 		$this->layout="ajax";
		$sql = "DELETE FROM cnmd09_frecuencia WHERE ".$this->SQLCA()." and cod_tipo_nomina=".$cod_nomina." and cod_tipo_transaccion=".$cod_tipo_transaccion." and cod_transaccion=".$cod_transaccion;
		if($this->cnmd09_frecuencia->execute($sql)>1){
			$this->set('Message_existe', 'EL REGISTRO FUE ELIMINADO EXITOSAMENTE');
		}else{
			$this->set('errorMessage', 'EL REGISTRO NO PUDO SER ELIMINADO');
		}
 }//eliminar


function listar_transacciones ($cod_tipo_nomina,$cod_tipo_transaccion=null) {
	 $this->layout="ajax";
	 $this->set('ctt',$cod_tipo_transaccion);
     $data=$this->v_cnmd09_frecuencia->findAll($this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina." and cod_tipo_transaccion=".$cod_tipo_transaccion,null,"cod_transaccion ASC");
     $this->set('DATA',$data);
}


function consultar($pagina=null) {
	$this->layout="ajax";
	$this->set('entidad_federal', $this->Session->read('entidad_federal'));
	//echo "coooonsultar";
    $lista = $this->Cnmd01->generateList($conditions = $this->condicion(), $order = 'cod_tipo_nomina', $limit = null, '{n}.Cnmd01.cod_tipo_nomina', '{n}.Cnmd01.denominacion');
	$this->concatenaN($lista, 'nomina');
	/*if(isset($pagina)){
		$Tfilas=$this->cnmd09_frecuencia->findCount($this->SQLCA());
        if($Tfilas!=0){
        	$data=$this->cnmd09_frecuencia->findAll($this->SQLCA(),null,"cod_tipo_nomina ASC",1,$pagina,null);


          	$this->set('siguiente',$pagina+1);
          	$this->set('anterior',$pagina-1);
            $this->bt_nav($Tfilas,$pagina);
            $this->set('numT',$Tfilas);
			$this->set('numP',$pagina);
        }else{
	 	    $this->set('errorMessage', 'No se encontrar&oacute;n datos');
	 	    $this->set('noExiste',true);
        }

	}else{
		$pagina=1;
		$Tfilas=$this->cnmd09_frecuencia->findCount($this->SQLCA());

        if($Tfilas!=0){
        	$data=$this->cnmd09_frecuencia->findAll($this->SQLCA(),null,"cod_tipo_nomina ASC",1,$pagina,null);
			$this->set('DATA',$data);
          	$this->set('siguiente',$pagina+1);
          	$this->set('anterior',$pagina-1);
          	$this->bt_nav($Tfilas,$pagina);
          	$this->set('numT',$Tfilas);
			$this->set('numP',$pagina);

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
	 	       $this->index();
			   $this->render("index");
			   return;
        }
	}

	$this->set('cod_nomina', $data[0]['cnmd09_frecuencia']['cod_tipo_nomina']);
	$this->set('cod_tipo_transaccion', $data[0]['cnmd09_frecuencia']['cod_tipo_transaccion']);
	$this->set('cod_transaccion', $data[0]['cnmd09_frecuencia']['cod_transaccion']);
	$this->set('cod_frecuencia', $data[0]['cnmd09_frecuencia']['cod_frecuencia']);

	$deno_nomina = $this->Cnmd01->field('denominacion', $conditions = $this->condicion()." and Cnmd01.cod_tipo_nomina=".$data[0]['cnmd09_frecuencia']['cod_tipo_nomina']);
	$this->set('deno_nomina', $deno_nomina);
	$deno_transaccion = $this->cnmd03_transacciones->field('denominacion', $conditions = "cod_tipo_transaccion=".$data[0]['cnmd09_frecuencia']['cod_tipo_transaccion']." and cod_transaccion=".$data[0]['cnmd09_frecuencia']['cod_transaccion']);
	$this->set('deno_transaccion', $deno_transaccion);
    */
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


 function modificar($cod_nomina=null,$cod_tipo_transaccion=null,$cod_transaccion=null,$cod_frecuencia=null,$anterior=null){
 	$this->layout="ajax";

 	$data=$this->cnmd09_frecuencia->findAll($this->SQLCA()." and cod_tipo_nomina=".$cod_nomina."and cod_tipo_transaccion=".$cod_tipo_transaccion." and cod_transaccion=".$cod_transaccion."and cod_frecuencia=".$cod_frecuencia);
	$this->set('cod_nomina', $data[0]['cnmd09_frecuencia']['cod_tipo_nomina']);
	$this->set('cod_tipo_transaccion', $data[0]['cnmd09_frecuencia']['cod_tipo_transaccion']);
	$this->set('cod_transaccion', $data[0]['cnmd09_frecuencia']['cod_transaccion']);
	$this->set('cod_frecuencia', $data[0]['cnmd09_frecuencia']['cod_frecuencia']);

	$deno_nomina = $this->Cnmd01->field('denominacion', $conditions = $this->condicion()." and Cnmd01.cod_tipo_nomina=".$data[0]['cnmd09_frecuencia']['cod_tipo_nomina']);
	$this->set('deno_nomina', $deno_nomina);
	$deno_transaccion = $this->cnmd03_transacciones->field('denominacion', $conditions = "cod_tipo_transaccion=".$data[0]['cnmd09_frecuencia']['cod_tipo_transaccion']." and cod_transaccion=".$data[0]['cnmd09_frecuencia']['cod_transaccion']);
	$this->set('deno_transaccion', $deno_transaccion);

 }//fin modificar




function guardar_modificar(){
	$this->layout="ajax";
	//pr($this->data);
	    $nomina=$this->data['cnmp09']['cod_nomina'];
	    $tipo_transaccion=$this->data['cnmp09']['cod_tipo_transaccion'];
		$transaccion=$this->data['cnmp09']['cod_transaccion'];
		$cod_frecuencia=$this->data['cnmp09']['frecuencia'];
			$v=$this->cnmd09_frecuencia->execute("update cnmd09_frecuencia set cod_frecuencia=".$cod_frecuencia." where ".$this->SQLCA()." and cod_tipo_nomina=".$nomina." and cod_tipo_transaccion=".$tipo_transaccion." and cod_transaccion=".$transaccion);
			if($v > 0){
				$this->set('Message_existe','EL REGISTRO SE MODIFICO EXITOSAMENTE');
				//$this->consultar();
				//$this->render('consultar');
			}else{
				$this->set('errorMessage','NO SE PUDO MODIFICAR');
				//$this->modificar($nomina,$cod,$transaccion,$cod,$transaccion);
				//$this->render('modificar');
			}

			$this->listar_transacciones ($nomina,$tipo_transaccion);
			$this->render('listar_transacciones');


 }//guardar modificar



}//FIN CONTROLADOR
?>