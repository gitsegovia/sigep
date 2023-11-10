<?php
/*
 * Creado el 14/12/2007
 *
 * Miguelangel Cabrera
 * miguel4ngel@gmail.com
 *
 * 12:02:43 PM
 */
 class Ccfp01tipoController extends AppController {
   var $name='ccfp01_tipo';
   var $uses=array('ccfd01_tipo','usuario','ccfd02', 'cstd02_cuentas_bancarias', 'cpcd02');
   var $helpers=array('Html','Ajax','Javascript', 'Sisap');

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
 	 echo'				<script>
							 document.getElementById("valida_codigo").innerHTML = "";
							 document.getElementById("valida_codigo").style.display = "none";
							 if(document.getElementById("registro")){document.getElementById("registro").style.visibility = "hidden";}
                             if(document.getElementById("nomina")){document.getElementById("nomina").style.visibility = "hidden";}
                             if(document.getElementById("dependencia")){document.getElementById("dependencia").style.visibility = "hidden";}
                             if(document.getElementById("sistema_general")){document.getElementById("sistema_general").style.visibility = "hidden";}
                         </script>';

 }

 function ss($i){
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
         $sql_re = "cod_presi=".$this->ss(1)."  and    ";
         $sql_re .= "cod_entidad=".$this->ss(2)."  and  ";
         $sql_re .= "cod_tipo_inst=".$this->ss(3)."  and ";
         $sql_re .= "cod_inst=".$this->ss(4)."  and  ";
         if($ano!=null){
         	$sql_re .= "cod_dep=".$this->ss(5)."  and  ";
            $sql_re .= "ano=".$ano."  ";
         }else{
         	$sql_re .= "cod_dep=".$this->ss(5)." ";
         }
         return $sql_re;
    }//fin funcion SQLCA

 function concatena_superior($vector1=null, $nomVar=null, $extra=null){

	if($vector1 != null){

		foreach($vector1 as $x => $y){


			if($extra!=null){

             $cod[$x] = $this->zero($x).' - '.$y;

			}else{

             $cod[$x] = $this->zero($x).' - '.$y;

			}
		}
		$this->set($nomVar, $cod);
	}
}

function zero($x=null){
	if($x != null){
		if($x<10){
			$x=$x;
		}
	}
	return $x;

}

function concatena($vector1=null, $nomVar=null){

	if($vector1 != null){

		foreach($vector1 as $x => $y){

			$cod[$x] = $x.' - '.$y;

		}
		$this->set($nomVar, $cod);
	}
}
function in(){
	$this->concatena($this->ccfd01_tipo->generateList($this->SQLCA(),' cod_tipo_cuenta ASC', null, '{n}.ccfd01_tipo.cod_tipo_cuenta', '{n}.ccfd01_tipo.denominacion'), 'tipo_');
}


 function index(){
 	$this->layout ="ajax";
		echo "<script>";
			echo "document.getElementById('codigo_tipo').value='';";
			echo "document.getElementById('denominacion').value='';";
			echo "document.getElementById('concepto').value='';";
		echo "</script>";

 	$this->set('tipo',array());
 	$num=$this->ccfd01_tipo->findCount($this->SQLCA());
 	$this->set('num',$num);
 	$this->concatena($this->ccfd01_tipo->generateList($this->SQLCA(),' cod_tipo_cuenta ASC', null, '{n}.ccfd01_tipo.cod_tipo_cuenta', '{n}.ccfd01_tipo.denominacion'), 'tipo');
	$this->set('enable', 'disabled');
	$data=$this->ccfd01_tipo->findAll($this->SQLCA(),null,null);
		if(!$data){
   		echo "<script>";
   			echo "document.getElementById('consultar').disabled='disabled';";
   		echo "</script>";
   		//echo "no consulto";
   	}else{
   		echo "<script>";
   			echo "document.getElementById('consultar').disabled=false;";
   		echo "</script>";
   		//echo "consulto";
   	}


 	}//fin index


 function select_cuenta($id = null){
 	$this->layout ="ajax";
	$num=$this->ccfd01_tipo->findCount($this->SQLCA());
 	$this->set('num',$num);
 	$this->set('tipo',array());
 	$this->set('sel',$id);
 	$this->concatena($this->ccfd01_tipo->generateList($this->SQLCA(),' cod_tipo_cuenta ASC', null, '{n}.ccfd01_tipo.cod_tipo_cuenta', '{n}.ccfd01_tipo.denominacion'), 'tipo');

 	if($id != 'otros' && $id!=""){
 			$this->set('otros', false);
 			$datos=$this->ccfd01_tipo->findAll($this->SQLCA().' and cod_tipo_cuenta='.$id);
 			if($datos){
 				$this->set('datos',$datos);
 			}else{
 				$this->set('datos','');
 			}

 	 		$this->set('enable2', 'enabled');
 	 		$this->set('enable', 'disabled');
 	 		$this->set('read', 'readonly');

 	}else{//echo "aqui;";
 		$this->set('otros', true);
 		//$this->set('Message_existe', 'POR FAVOR INGRESE EL C&Oacute;DIGO');
 		$this->set('enable2', 'disabled');
 		$this->set('enable', 'enable');
 		$this->set('read', '');
 	}
 }

  function guardar(){
 	$this->layout ="ajax";
 //	echo "erick";
 	$this->set('tipo',array());
 	$this->set('enable2', 'disabled');
 	$this->set('enable', 'enable');
 	$this->set('enable', 'disabled');
	$cod_tipo = $this->data['ccfp01_tipo']['codigo_tipo'];
	$denominacion = $this->data['ccfp01_tipo']['denominacion'];
	$concepto = $this->data['ccfp01_tipo']['concepto'];
	$consulta="select * from ccfd01_tipo where ".$this->SQLCA()." and cod_tipo_cuenta=$cod_tipo";
	$sql="insert into ccfd01_tipo values('".$this->ss(1)."','".$this->ss(2)."','".$this->ss(3)."','".$this->ss(4)."','".$this->ss(5)."','$cod_tipo','$denominacion','$concepto')";

	if($this->ccfd01_tipo->execute($consulta)){
	//	echo "erick1";
		$this->set('errorMessage','El c&oacute;digo del tipo de cuenta '.$cod_tipo.' ya existe');
		$this->concatena($this->ccfd01_tipo->generateList($this->SQLCA(),' cod_tipo_cuenta ASC', null, '{n}.ccfd01_tipo.cod_tipo_cuenta', '{n}.ccfd01_tipo.denominacion'), 'tipo');
		$this->set('enable2', 'disabled');
 		$this->set('enable', 'enable');
 		$this->set('read', 'readonly');
	}else{
		//echo "erick2";
		if($this->ccfd01_tipo->execute($sql)>1){
		//	echo "erick3";
		$this->set('Message_existe','Los datos fueron guardados exitosamente');
		$this->concatena($this->ccfd01_tipo->generateList($this->SQLCA(),' cod_tipo_cuenta ASC', null, '{n}.ccfd01_tipo.cod_tipo_cuenta', '{n}.ccfd01_tipo.denominacion'), 'tipo');
		$this->set('sel',$cod_tipo);
		$this->set('read', 'readonly');

		$this->index();
		$this->render('index');

		//$this->in();
		$num=$this->ccfd01_tipo->findCount($this->SQLCA());
 		$this->set('num',$num);
 		$this->set('enable2', 'disabled');
	}else{
		//echo "erick4";
		$this->set('errorMessage','Los datos no fueron guardados');
		$num=$this->ccfd01_tipo->findCount($this->SQLCA());
 		$this->set('num',$num);
	}//fin else insersion

	}//fin else consulta

	echo "<script>";
		echo "document.getElementById('codigo_tipo').value=''";
		echo "document.getElementById('denominacion').value=''";
		echo "document.getElementById('concepto').value=''";
	echo "</script>";

	}//fin guardar

	 function modificar($id=null){
	$num=$this->ccfd01_tipo->findCount($this->SQLCA());
 	$this->set('num',$num);
	$this->set('tipo',array());
 	$this->layout ="ajax";
 	$this->concatena($this->ccfd01_tipo->generateList($this->SQLCA(),' cod_tipo_cuenta ASC', null, '{n}.ccfd01_tipo.cod_tipo_cuenta', '{n}.ccfd01_tipo.denominacion'), 'tipo');
	$this->set('datos', $this->ccfd01_tipo->findAll($this->SQLCA().' and cod_tipo_cuenta='.$id));
 	$this->set('enable2', 'enabled');
 	$this->set('enable', 'disabled');
 	$this->set('read', 'readonly');
 	$this->set('sel', $id);

 	}//fin modificar

 	function modificar_consultar($id=null){
 	$num=$this->ccfd01_tipo->findCount($this->SQLCA());
 	$this->set('num',$num);
 	$this->set('tipo',array());
 	$this->layout ="ajax";
 	$this->concatena($this->ccfd01_tipo->generateList($this->SQLCA(),' cod_tipo_cuenta ASC', null, '{n}.ccfd01_tipo.cod_tipo_cuenta', '{n}.ccfd01_tipo.denominacion'), 'tipo');
	$this->set('datos', $this->ccfd01_tipo->findAll($this->SQLCA().' and cod_tipo_cuenta='.$id));
 	$this->set('enable2', 'enabled');
 	$this->set('enable', 'disabled');
 	$this->set('read', 'readonly');
 	$this->set('sel', $id);

 	}//fin modificar

 	function guardar_modificar($id=null){
 	$num=$this->ccfd01_tipo->findCount($this->SQLCA());
 	$this->set('num',$num);
 	$this->layout ="ajax";
	$this->set('tipo',array());
 	$this->set('enable2', 'enabled');
 	$this->set('enable', 'disabled');
 	$this->set('read', 'readonly');
 	$this->set('sel', $id);
 	$cod_tipo = $this->data['ccfp01_tipo']['codigo_tipo'];
	$denominacion = $this->data['ccfp01_tipo']['denominacion'];
	$concepto = $this->data['ccfp01_tipo']['concepto'];
	$sql="update ccfd01_tipo set denominacion='$denominacion',concepto='$concepto' where ".$this->SQLCA()." and cod_tipo_cuenta='$cod_tipo'";
	if($this->ccfd01_tipo->execute($sql)>1){
		$this->set('Message_existe','Los datos fueron modificados exitosamente');
		$this->concatena($this->ccfd01_tipo->generateList($this->SQLCA(),' cod_tipo_cuenta ASC', null, '{n}.ccfd01_tipo.cod_tipo_cuenta', '{n}.ccfd01_tipo.denominacion'), 'tipo');
		$this->set('read', 'readonly');
		$this->in();
	}else{
		$this->set('errorMessage','Los datos no fueron modificados');
	}//fin else actualizacion

 	}//fin guardar modificar

 	function guardar_modificar_consultar($id=null){
	$this->guardar_modificar($id);
	$this->consultar();
	$this->render('consultar');
 	}//fin guardar modificar


function eliminar($var){
	$this->layout ="ajax";

$verifica = $this->ccfd02->findCount("cod_tipo_cuenta= ".$var);
$verifica1 = $this->cstd02_cuentas_bancarias->findCount("tesoro_tipo= ".$var);
$verifica2 = $this->cpcd02->findCount("gasto_presu_tipo= ".$var);
$verifica3 = $this->cpcd02->findCount("gasto_pagar_tipo= ".$var);
$verifica4 = $this->cpcd02->findCount("orden_pago_tipo= ".$var);
$verifica5 = $this->cpcd02->findCount("anticipo_prov_tipo= ".$var);

if ($verifica==0 && $verifica1==0 && $verifica2==0 && $verifica3==0 && $verifica4==0 && $verifica5==0){

	if($var != null){
		$sql = "DELETE FROM ccfd01_tipo WHERE ".$this->SQLCA()." and cod_tipo_cuenta= ".$var;

			if($this->ccfd01_tipo->execute($sql)>1){
				//echo "aqui";
				$this->set('mensaje', 'EL REGISTRO FUE ELIMINADO EXITOSAMENTE');
				$Tfilas=$this->ccfd01_tipo->findCount($this->SQLCA());
				if($Tfilas!=0){
				$this->consultar();
				$this->render("consultar");
				}else{
					$this->index();
					$this->render("index");
				}
			}else{
				$this->set('mensajeError', 'EL REGISTRO NO PUDO SER ELIMINADO');
				$this->consultar();
				$this->render("consultar");
			}
		}
}else{
 	$this->set('mensaje', 'NO PUEDE ELIMINAR, TIENE MOVIMIENTOS');
 	$this->consultar();
	$this->render("consultar");
 }

 }





 	function eliminar_consultar($id=null){
 	$this->eliminar($id);
	$this->consultar();
	$this->render('consultar');
 	}//fin guardar modificar





 function consultar ($pagina=null) {
 	$num=$this->ccfd01_tipo->findCount($this->SQLCA());
 	$this->set('num',$num);
 	$this->set('tipo',array());
	$this->layout="ajax";
	$this->set('enable2', 'enabled');
 	$this->set('enable', 'disabled');
 	$this->set('read', 'readonly');
	if(isset($pagina)){
		$Tfilas=$this->ccfd01_tipo->findCount($this->SQLCA());
        if($Tfilas!=0){
        	$data=$this->ccfd01_tipo->findAll($this->SQLCA(),null,"cod_tipo_cuenta ASC",1,$pagina,null);
			$this->set('noExiste',false);
            $this->set('DATA',$data);
          	$this->set('siguiente',$pagina+1);
          	$this->set('anterior',$pagina-1);
            $this->bt_nav($Tfilas,$pagina);
            $this->set('numT',$Tfilas);
			$this->set('numP',$pagina);
			$this->Session->delete('pagina');
			$this->Session->write('pagina',$pagina);
        }else{
	 	       $this->set('errorMessage', 'No se encontrar&oacute;n datos');
	 	       $this->set('noExiste',true);
	 	       $this->index();
	 	       $this->render('index');
        }

	}else{
		$pagina=1;
		$Tfilas=$this->ccfd01_tipo->findCount($this->SQLCA());
        if($Tfilas!=0){
        	$data=$this->ccfd01_tipo->findAll($this->SQLCA(),null,"cod_tipo_cuenta ASC",1,$pagina,null);
        	$this->set('noExiste',false);
			$this->set('DATA',$data);
          	$this->set('siguiente',$pagina+1);
          	$this->set('anterior',$pagina-1);
          	$this->bt_nav($Tfilas,$pagina);
          	$this->set('numT',$Tfilas);
			$this->set('numP',$pagina);
			$this->Session->delete('pagina');
			$this->Session->write('pagina',$pagina);
        }else{
	 	       $this->set('errorMessage', 'No se encontrar&oacute;n datos');
	 	       $this->set('noExiste',true);
	 	       $this->index();
	 	       $this->render('index');
        }

	}
		if(isset($data)){
			foreach($data as $datos){
	    $cod_tipo=$datos['ccfd01_tipo']['cod_tipo_cuenta'];
	    $this->set('cod_tipo', $cod_tipo);
		$denominacion=$datos['ccfd01_tipo']['denominacion'];
		$this->set('denominacion', $denominacion);
		$concepto=$datos['ccfd01_tipo']['concepto'];
		$this->set('concepto', $concepto);
		}
	}

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

 function salir(){
 	$this->layout ="ajax";
 	$num=$this->ccfd01_tipo->findCount($this->SQLCA());
 	$this->set('num',$num);
 	$this->set('tipo',array());
 	$this->concatena($this->ccfd01_tipo->generateList($this->SQLCA(),' cod_tipo_cuenta ASC', null, '{n}.ccfd01_tipo.cod_tipo_cuenta', '{n}.ccfd01_tipo.denominacion'), 'tipo');
	$this->set('enable', 'disabled');
 	}

 }
?>
