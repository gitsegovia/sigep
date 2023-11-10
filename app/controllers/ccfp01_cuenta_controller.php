<?php
/*
 * Creado el  17/12/2007 a las 12:46:21 PM
 * Herramienta: EasyEclipse.
 * Proyecto: SIGEP
 * Descripcion:
 */
 class Ccfp01CuentaController extends AppController{
 	var $name = 'ccfp01_cuenta';
 	var $uses = array ('ccfd01_tipo', 'ccfd01_cuenta');
 	var $helpers = array ('Html','Ajax','Javascript','Sisap');



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



 function concatena_uno($vector1=null, $nomVar=null){
	if($vector1 != null){

		foreach($vector1 as $x => $y){

			$cod[$x] = $x.' - '.$y;

		}
		$this->set($nomVar, $cod);
	}

 }



 function index(){

 	$this->layout="ajax";
	$num=$this->ccfd01_tipo->findCount($this->SQLCA());
 	$this->set('num',$num);
 	$tipo_c = $this->ccfd01_tipo->generateList($this->SQLCA(), 'cod_tipo_cuenta ASC', null, '{n}.ccfd01_tipo.cod_tipo_cuenta', '{n}.ccfd01_tipo.denominacion');
   	$this->concatena_uno($tipo_c, 'tipo');
   	$this->set('vector','');
   	$this->data["ccfp01_cuenta"]=null;
   	$data=$this->ccfd01_cuenta->findAll(null,null,null);


 }// fin del index



function boton($var=null){
	$this->layout="ajax";
	$this->set('tipo',$var);
}//fin boton

 function select3($select=null,$var=null,$var2=null) {
	$this->layout = "ajax";
	if($var!=null){
	$cond2 = $this->SQLCA();
    switch($select){
		case 'tipo':
			  $this->set('SELECT','coordinacion');
			  $this->set('codigo','secretaria');
			  $this->set('seleccion','');
			  $this->set('n',1);
		break;
		case 'contable':
			  $this->Session->write('tipo',$var);
			  $this->set('SELECT','secretaria');//El parametro que se le pasa para que busque el proximo select (cuando entre en select3)
			  $this->set('codigo','contable');//El nombre que se le asigna al select actual cuando se crea
			  $this->set('cod_tipocuenta',$var);//cod_tipocuenta es para mantener el valor de la variable que llega y pasarselo al paso que viene en select3
			  $this->set('seleccion','');
			  $this->set('n',2);
			  $this->set('no','no');
			  $cond = $cond2." and cod_tipo_cuenta=".$var;
			  $lista = $this->ccfd01_cuenta->generateList($cond, 'cod_cuenta ASC', null, '{n}.ccfd01_cuenta.cod_cuenta', '{n}.ccfd01_cuenta.denominacion');
	   		  $this->concatena($lista, 'vector');
		break;
		case 'secretaria':
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
 }// fin function select3


function m2($var=null){
	switch (strlen($var)){
		case 1:
			return $var;
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



 function mostrar4($select=null,$var=null,$var2=null) {
	$this->layout = "ajax";
    if( $var!=null){
    $cond2 = $this->SQLCA();

    	if($var2 == "agregar"){
    		echo "<input type='text' name='data[ccfp01_cuenta][cod_cuenta_contable]' value='' size='10'  maxlength='4' onKeyPress='return solonumeros(event);' id='cod_cuenta_contable'  style='text-align:center' />";
    	}else{

			switch($select){
				case 'tipo':
				      $cond = $cond2." and cod_tipo_cuenta=".$var;
				      $a =  $this->ccfd01_tipo->findAll($cond);
		              echo "<input type='text' name='data[ccfp01_cuenta][cod_tipo_cuenta]' value='".$a[0]['ccfd01_tipo']['cod_tipo_cuenta']."' size='10'  readonly='readonly' maxlength='4' id='cod_tipo_cuenta' style='text-align:center' />";
					  echo "<script>";
					  	echo "document.getElementById('cod_cuenta_contable').value='';";
					  	echo "document.getElementById('deno_cuenta_contable').value='';";
					  	echo "document.getElementById('concepto_cuentacontable').value='';";
					  echo "</script>";
				break;
				case 'contable':
					   // if(!isset($var) || !isset($var2)){
					  	//    echo "<input type='text' name='data[ccfp01_cuenta][cod_tipo_cuenta]' value=''  size='10'  maxlength='4' id='cod_sucursal_bancaria' readonly='readonly' style='text-align:center' />";
					   // }else{
					   $cond = $cond2." and cod_tipo_cuenta=".$var." and cod_cuenta=".$var2;
					  $a = $this->ccfd01_cuenta->findAll($cond);
			           echo "<input type='text' name='data[ccfp01_cuenta][cod_cuenta_contable]' value='".$this->m2($a[0]['ccfd01_cuenta']['cod_cuenta'])."' size='10' readonly='readonly' maxlength='4' id='cod_cuenta_contable' style='text-align:center' />";
			           echo "<script>";
					  	echo "document.getElementById('modi').disabled=false;";
					  echo "</script>";
				       // }
				    break;
				case 'secretaria':

				break;
			  }//fin switch
         }

	}else{
	 echo "<input type='text' name='data[ccfp01_cuenta]' size='10' maxlength='4' id='cod_entidad_bancaria' readonly='readonly' style='text-align:center' />";
	}
 }// fin function mostrar4




 function mostrar3($select=null,$var=null,$var2=null) {
   $this->layout = "ajax";
   if( $var!=null && !empty($var)){
   $cond2 = $this->SQLCA();
   		if($var2 == "agregar"){
    		echo "<input type='text' name='data[ccfp01_cuenta][deno_cuenta_contable]' value='' size='65' class='inputtext' maxlength='100'  id='deno_cuenta_contable'  />";
    	}else{
			switch($select){
				case 'tipo':
				      $cond = $cond2." and cod_tipo_cuenta=".$var;
				      $a=  $this->ccfd01_tipo->findAll($cond);
		         	  echo "<input type='text' name='data[ccfp01_cuenta][deno_tipo_cuenta]' value='".$a[0]['ccfd01_tipo']['denominacion']."' size='65' readonly='readonly' class='inputtext' maxlength='100' id='deno_tipo_cuenta' />";
				    break;
				case 'contable':
					   $cond = $cond2." and cod_tipo_cuenta=".$var." and cod_cuenta=".$var2;
				       $a=  $this->ccfd01_cuenta->findAll($cond);
			           echo "<input type='text' name='data[ccfp01_cuenta][deno_cuenta_contable]' value='".$a[0]['ccfd01_cuenta']['denominacion']."' size='65' class='inputtext' readonly='readonly' maxlength='100' id='deno_cuenta_contable' />";
				       // }
				     break;
				case 'secretaria':

				break;
			}//fin switch
   		}

	}else{
		echo "<input type='text' name='data[ccfp01_cuenta] value=''  size='65' readonly='readonly' class='inputtext' maxlength='100' />";
	}
 }// fin function mostrar3 denominaciones


 function mostrar5($select=null,$var=null,$var2=null) {
	$this->layout = "ajax";
	if( $var!=null){
	$cond2 = $this->SQLCA();

	//echo $cond2;
		if($var2 == "agregar"){
    		echo "<textarea name='data[ccfp01_cuenta][concepto_cuentacontable]' value=''  rows='6' class='inputtext' maxlength='100' id='concepto_cuentacontable' ></textarea>";
    	}else{
			switch($select){
				case 'tipo':
				     $cond = $cond2." and cod_tipo_cuenta=".$var;
				     $a=  $this->ccfd01_tipo->findAll($cond);
		             echo "<textarea name='data[ccfp01_cuenta][concepto_tipo_cuenta]' value='".$a[0]['ccfd01_tipo']['concepto']."' rows='6' class='inputtext' maxlength='100' id='concepto_tipo_cuenta' ></textarea>";
				break;
				case 'contable':
				//echo "aqui";
		             //if(!isset($var2)){
				  	 //    echo "<input type='text' name='data[ccfp01_cuenta][deno_sucursal_bancaria]' value='' size='37'  maxlength='100' id='deno_sucursal_bancaria' />";
				     //}else{
				   	 $cond = $cond2." and cod_tipo_cuenta=".$var." and cod_cuenta=".$var2;
				     $a=  $this->ccfd01_cuenta->findAll($cond);
				     //print_r($a);
		             echo "<textarea name='data[ccfp01_cuenta][concepto_cuentacontable]' value='' rows='6' class='inputtext' maxlength='100' id='concepto_cuentacontable' > ".$a[0]['ccfd01_cuenta']['concepto']." </textarea>";
				     //}
				   break;
				case 'secretaria':
		             // $ano =  $this->Session->read('ano');
					 // $ddirs =  $this->Session->read('ddirs');
					 // $dcoor =  $this->Session->read('dcoor');
					 // $this->Session->write('dsecr',$var);
					 // $cond .=" and cod_dir_superior=".$ddirs." and cod_coordinacion=".$dcoor." and cod_secretaria=".$var;
					 // $a=  $this->cugd02_secretaria->findAll($cond);
			         // echo $a[0]['cugd02_secretaria']['denominacion'];
				break;
			}//fin switch
		}

    }else{
    	echo "<textarea name='data[ccfp01_cuenta][concepto_tipo_cuenta]' value='' row='3' class='inputtext'  maxlength='100' id='deno_sucursal_bancaria' ></textarea>";
    }
 }// fin function mostrar5

function modificar1(){
	$this->layout="ajax";
	$this->set('mensaje','Puede proceder a Modificar los datos');

	echo "<script>";
		echo "document.getElementById('deno_cuenta_contable').readOnly=false;";
		echo "document.getElementById('concepto_cuentacontable').readOnly=false;";
	echo "</script>";
}


 function guardar(){
 	$this->layout="ajax";
	if(!empty($this->data['ccfp01_cuenta'])){

  		$cod_tipo_cuenta = $this->data['ccfp01_cuenta']['cod_tipo_cuenta'];   // Código tipo de la cuenta;
  		$cod_cuenta = $this->data['ccfp01_cuenta']['cod_cuenta_contable'];    // Código de la cuenta;
  		$denominacion = $this->data['ccfp01_cuenta']['deno_cuenta_contable']; // Denominación;
  		$concepto = $this->data['ccfp01_cuenta']['concepto_cuentacontable'];  // Concepto

  		$consulta = "SELECT * FROM ccfd01_cuenta WHERE ".$this->SQLCA()." and cod_tipo_cuenta=".$cod_tipo_cuenta." and cod_cuenta=".$cod_cuenta;
   		if($this->ccfd01_cuenta->execute($consulta)){
//  			$this->set('mensajeError','estos datos ya se encuentran registrados');
				$sql="update ccfd01_cuenta set denominacion='$denominacion', concepto='$concepto' where ".$this->SQLCA()." and cod_tipo_cuenta=".$cod_tipo_cuenta." and cod_cuenta=".$cod_cuenta;
				$this->ccfd01_cuenta->execute($sql);
				$this->set('mensaje','Los datos fuer&oacute;n Actualizados');
//  			$this->index();
//			$this->render("index");
  		}else{

	  		$sql= "INSERT INTO ccfd01_cuenta values ('".$this->ss('1')."','".$this->ss('2')."','".$this->ss('3')."','".$this->ss('4')."','".$this->ss('5')."','$cod_tipo_cuenta','$cod_cuenta','$denominacion','$concepto')";

	  		if($this->ccfd01_cuenta->execute($sql)>1){
	  			$this->set('mensaje','Los datos fuer&oacute;n insertados correctamente');
	  			//$this->index();
				//$this->render("index");
				echo "<script>";
					echo "document.getElementById('consulta').disabled=false;";
					//echo "document.getElementById('elimina').disabled=false;";
				echo "</script>";

	  		}else{
	  			$this->set('mensajeError','Los datos no pudier&oacute;n ser insertados');
	  			//$this->index();
				//$this->render("index");
	  		}
  		}
	}
	echo "<script>";
		echo "document.getElementById('cod_cuenta_contable').value='';";
		echo "document.getElementById('deno_cuenta_contable').value='';";
		echo "document.getElementById('concepto_cuentacontable').value='';";
		echo "document.getElementById('save').disabled=false;";
	echo "</script>";
 }// fin guardar


function eliminar($cod_tipo_cuenta=null, $cod_cuenta=null, $pagina_regreso=null){
	$this->layout ="ajax";

if(isset($cod_tipo_cuenta) && isset($cod_cuenta)){
	if($cod_cuenta != null){

	    $row_cuenta=$this->ccfd01_cuenta->execute("SELECT cod_cuenta FROM ccfd02 WHERE ".$this->SQLCA()." and cod_tipo_cuenta= ".$cod_tipo_cuenta." and cod_cuenta=".$cod_cuenta." LIMIT 1;");
		if(!empty($row_cuenta)){
			$this->set('mensajeError', 'Lo siento no se puede eliminar la cuenta, debido a que esta siendo utilizada');
				$this->consultar($pagina_regreso);
				$this->render("consultar");
		}else{

			$sql = "DELETE FROM ccfd01_cuenta WHERE ".$this->SQLCA()." and cod_tipo_cuenta= ".$cod_tipo_cuenta." and cod_cuenta=".$cod_cuenta;

			if($this->ccfd01_cuenta->execute($sql)>1){
				$this->set('mensaje', 'EL REGISTRO FUE ELIMINADO EXITOSAMENTE');
				$Tfilas=$this->ccfd01_cuenta->findCount();
				if($Tfilas!=0){
				$this->consultar($pagina_regreso);
				$this->render("consultar");
				}else{
					$this->index();
					$this->render("index");
					echo "";
				}
			}else{
				$this->set('mensajeError', 'EL REGISTRO NO PUDO SER ELIMINADO');
				$this->consultar($pagina_regreso);
				$this->render("consultar");
			}
		}
	}
}else{//fin isset
		$cod_tipo_cuenta = $this->data['ccfp01_cuenta']['cod_tipo_cuenta'];   // Código tipo de la cuenta;
  		$cod_cuenta = $this->data['ccfp01_cuenta']['cod_cuenta_contable'];    // Código de la cuenta;
  		$denominacion = $this->data['ccfp01_cuenta']['deno_cuenta_contable']; // Denominación;
  		$concepto = $this->data['ccfp01_cuenta']['concepto_cuentacontable'];  // Concepto

	    $row_cuenta=$this->ccfd01_cuenta->execute("SELECT cod_cuenta FROM ccfd02 WHERE ".$this->SQLCA()." and cod_tipo_cuenta= ".$cod_tipo_cuenta." and cod_cuenta=".$cod_cuenta." LIMIT 1;");
		if(!empty($row_cuenta)){
			$this->set('mensajeError', 'Lo siento no se puede eliminar la cuenta, debido a que esta siendo utilizada');
		}else{

  			$sql = "DELETE FROM ccfd01_cuenta WHERE ".$this->SQLCA()." and cod_tipo_cuenta= ".$cod_tipo_cuenta." and cod_cuenta=".$cod_cuenta;

			if($this->ccfd01_cuenta->execute($sql)>1){
				$this->set('mensaje', 'EL REGISTRO FUE ELIMINADO EXITOSAMENTE');

			}else{
				$this->set('mensajeError', 'EL REGISTRO NO PUDO SER ELIMINADO');

			}

			  echo "<script>";
			  	echo "document.getElementById('cod_cuenta_contable').value='';";
			  	echo "document.getElementById('deno_cuenta_contable').value='';";
			  	echo "document.getElementById('concepto_cuentacontable').value='';";
			  	echo "document.getElementById('elimi').disabled=false;";
			  echo "</script>";
		}
}

}//fin eliminar




 function consultar($pagina=null) {
	$this->layout="ajax";
	$this->set('entidad_federal', $this->Session->read('entidad_federal'));
	$this->set('enable2', 'enabled');
 	$this->set('enable', 'disabled');
 	$this->set('read', 'readonly');

	$consulta1=$this->SQLCA();

	if(isset($pagina)){
		$Tfilas=$this->ccfd01_cuenta->findCount();
        if($Tfilas!=0){
        	//echo "hola";
        	$data=$this->ccfd01_cuenta->findAll($consulta1,null,"cod_tipo_cuenta, cod_cuenta ASC",1,$pagina,null);

            $this->set('DATA',$data);
          	$this->set('siguiente',$pagina+1);
          	$this->set('anterior',$pagina-1);
            $this->bt_nav($Tfilas,$pagina);
            $this->set('numT',$Tfilas);
			$this->set('numP',$pagina);
        }else{
        	//echo "hola 2";
	 	       $this->set('errorMessage', 'No se encontrar&oacute;n datos');
	 	       $this->set('noExiste',true);
	 	      // $this->index();
	 	       //$this->render('index');
	 	       return;
        }

	}else{
		$pagina=1;
		$Tfilas=$this->ccfd01_cuenta->findCount();

        if($Tfilas!=0){
        	$data=$this->ccfd01_cuenta->findAll($consulta1,null,"cod_tipo_cuenta, cod_cuenta ASC",1,$pagina,null);
			$this->set('DATA',$data);
          	$this->set('siguiente',$pagina+1);
          	$this->set('anterior',$pagina-1);
          	$this->bt_nav($Tfilas,$pagina);
          	$this->set('numT',$Tfilas);
			$this->set('numP',$pagina);

        }else{
	 	       $this->set('errorMessage', 'No se encontrar&oacute;n datos');
	 	       $this->set('noExiste',true);
        }
	}

		//foreach($data as $datos){
		$cod_tipo_cuenta=$data[0]['ccfd01_cuenta']['cod_tipo_cuenta'];
	    $this->set('cod_tipo_cuenta', $cod_tipo_cuenta);

	    $cod_cuenta=$data[0]['ccfd01_cuenta']['cod_cuenta'];
	    $this->set('cod_cuenta', $cod_cuenta);

		$deno_cod_cuenta=$data[0]['ccfd01_cuenta']['denominacion'];
	    $this->set('deno_cod_cuenta', $deno_cod_cuenta);

	    $concepto_cod_cuenta=$data[0]['ccfd01_cuenta']['concepto'];
	    $this->set('concepto_cod_cuenta', $concepto_cod_cuenta);
	   	//}

	// El siguiente bloque es para obtener la denominacion del codigo del tipo de cuenta
	$dataR=$this->ccfd01_tipo->findAll($consulta1.' and cod_tipo_cuenta='.$cod_tipo_cuenta);
	foreach($dataR as $dataR1){
	    	$deno_tipo_cuenta=  $dataR1['ccfd01_tipo']['denominacion'];
	    	$this->set('deno_tipo_cuenta',$deno_tipo_cuenta);

	    	$concepto_tipo_cuenta=  $dataR1['ccfd01_tipo']['concepto'];
	    	$this->set('concepto_tipo_cuenta',$concepto_tipo_cuenta);
			}

 }//fin consultar


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


 function modificar($cod_tipo_cuenta=null, $cod_cuenta=null, $pagina_regreso=null){
 	$this->layout="ajax";
if(isset($cod_tipo_cuenta)  && isset($cod_cuenta)){
 	if($cod_cuenta != null){
 		$condicion=$this->SQLCA()." and cod_tipo_cuenta=".$cod_tipo_cuenta." and cod_cuenta=".$cod_cuenta;
 		$data=$this->ccfd01_cuenta->findAll($condicion);
 		if($data>1){
	    $this->set('cod_tipo_cuenta', $data[0]['ccfd01_cuenta']['cod_tipo_cuenta']);
	    $this->set('cod_cuenta', $data[0]['ccfd01_cuenta']['cod_cuenta']);
	    $this->set('deno_cod_cuenta', $data[0]['ccfd01_cuenta']['denominacion']);
	    $this->set('concepto_cod_cuenta', $data[0]['ccfd01_cuenta']['concepto']);

		//acontinuacion buscamos la informacion del tipo de cuenta
	    $condicion2=$this->SQLCA()." and cod_tipo_cuenta=".$cod_tipo_cuenta;
	    $data_tipo_cuenta=$this->ccfd01_tipo->findAll($condicion2);
	    $this->set('deno_tipo_cuenta', $data_tipo_cuenta[0]['ccfd01_tipo']['denominacion']);
	    $this->set('concepto_tipo_cuenta', $data_tipo_cuenta[0]['ccfd01_tipo']['concepto']);
	    $this->set('mensaje', 'INGRESE LOS DATOS A MODIFICAR');
	    $this->set('pagina_actual', $pagina_regreso+1);
 		}else{
 			$this->set('mensajeError', 'NO SE PUEDEN OBTENER LOS DATOS');
 		}
 	}
}else{
	    $cod_tipo_cuenta = $this->data['ccfp01_cuenta']['cod_tipo_cuenta'];   // Código tipo de la cuenta;
  		$cod_cuenta = $this->data['ccfp01_cuenta']['cod_cuenta_contable'];    // Código de la cuenta;
  		$denominacion = $this->data['ccfp01_cuenta']['deno_cuenta_contable']; // Denominación;
  		$concepto = $this->data['ccfp01_cuenta']['concepto_cuentacontable'];  // Concepto

  		$condicion=$this->SQLCA()." and cod_tipo_cuenta=".$cod_tipo_cuenta." and cod_cuenta=".$cod_cuenta;
 		$data=$this->ccfd01_cuenta->findAll($condicion);
 		if($data>1){
		    $this->set('cod_tipo_cuenta', $data[0]['ccfd01_cuenta']['cod_tipo_cuenta']);
		    $this->set('cod_cuenta', $data[0]['ccfd01_cuenta']['cod_cuenta']);
		    $this->set('deno_cod_cuenta', $data[0]['ccfd01_cuenta']['denominacion']);
		    $this->set('concepto_cod_cuenta', $data[0]['ccfd01_cuenta']['concepto']);

			//acontinuacion buscamos la informacion del tipo de cuenta
		    $condicion2=$this->SQLCA()." and cod_tipo_cuenta=".$cod_tipo_cuenta;
		    $data_tipo_cuenta=$this->ccfd01_tipo->findAll($condicion2);
		    $this->set('deno_tipo_cuenta', $data_tipo_cuenta[0]['ccfd01_tipo']['denominacion']);
		    $this->set('concepto_tipo_cuenta', $data_tipo_cuenta[0]['ccfd01_tipo']['concepto']);
		    $this->set('mensaje', 'INGRESE LOS DATOS A MODIFICAR');
		    $this->set('pagina_actual', $pagina_regreso+1);
			$this->set('pagina_actual', 'index');
 		}

}
 }//fin modificar

 function guardar_modificar($codigo_cuenta=null, $pagina_regreso=null){
 	$this->layout="ajax";

	/** La variable $codigo_cuenta que se recibe en la funcion guardar_modificar(), es para mantener el codigo de la cuenta permanetemente
	 *  para ser usada en la consulta de actualizacion (UPDATE) y esta consulta no sea afectada en el caso que el usuario la haya cambiado,
	 *  es decir, para hacer el Update buscando con el valor viejo de la columna cod_cuenta
	 */

  	$cod_tipo_cuenta = $this->data['ccfp01_cuenta']['cod_tipo_cuenta'];   // Código tipo de la cuenta;
	$cod_cuenta = $this->data['ccfp01_cuenta']['cod_cuenta_contable'];    // Código de la cuenta;
  	$denominacion = $this->data['ccfp01_cuenta']['deno_cuenta_contable']; // Denominación;
  	$concepto = $this->data['ccfp01_cuenta']['concepto_cuentacontable'];  // Concepto

	$condicion=$this->SQLCA();
  	$sql="update ccfd01_cuenta set cod_cuenta='$cod_cuenta', denominacion='$denominacion', concepto='$concepto' where $condicion and cod_tipo_cuenta='$cod_tipo_cuenta' and cod_cuenta='$codigo_cuenta'";
if($pagina_regreso!='index'){
 	if($this->ccfd01_cuenta->execute($sql)>1){
		$this->set('mensaje','Los datos fuer&oacute;n modificados exitosamente');
		$this->consultar($pagina_regreso);
		$this->render("consultar");
	}else{
		$this->set('mensajeError','Los datos no fuer&oacute;n modificados');
		$this->consultar($pagina_regreso);
		$this->render("consultar");
	}//fin else actualizacion
}else{
	if($this->ccfd01_cuenta->execute($sql)>1){
		$this->set('mensaje','Los datos fuer&oacute;n modificados exitosamente');
		$this->index();
		$this->render("index");
	}else{
		$this->set('mensajeError','Los datos no fuer&oacute;n modificados');
		$this->index();
		$this->render("index");
	}//fin else actualizacion

}
 }//fin guardar modificar




 }// fin de la clase
?>
