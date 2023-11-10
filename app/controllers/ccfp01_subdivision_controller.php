<?php
/*
 * Creado el 08/01/2008 a las 03:28:45 PM
 * Herramienta: EasyEclipse.
 * Proyecto: SIGEP
 * Descripcion:
 */
 class Ccfp01SubdivisionController extends AppController{
 	var $name = 'ccfp01_subdivision';
 	var $uses = array ('ccfd01_tipo', 'ccfd01_cuenta', 'ccfd01_subcuenta', 'ccfd01_division', 'ccfd01_subdivision');
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



 /*function concatena($vector1=null, $nomVar=null){

	if($vector1 != null){

		foreach($vector1 as $x => $y){

			$cod[$x] = $this->zero($x).' - '.$y;

		}
		$this->set($nomVar, $cod);
	}
 }*/


 function index(){

 	$this->layout="ajax";
	$num=$this->ccfd01_tipo->findCount($this->SQLCA());
 	$this->set('num',$num);
 	$tipo_c = $this->ccfd01_tipo->generateList($this->SQLCA(), 'cod_tipo_cuenta ASC', null, '{n}.ccfd01_tipo.cod_tipo_cuenta', '{n}.ccfd01_tipo.denominacion');
   	$this->concatena($tipo_c, 'tipo');
   	$this->set('vector','');
   	$this->data["ccfp01_subdivision"]=null;

 }// fin del index



function boton($var1=null,$var2=null,$var3=null,$var4=null){
	$this->layout="ajax";
	//echo $var1." ".$var2." ".$var3." "." ".$var4;
	$this->set('tipo',$var1);
	$this->set('cuenta',$var2);
	$this->set('sub_cuenta',$var3);
	$this->set('division',$var4);
}//fin boton


  function select3($select=null,$var=null,$var2=null,$var3=null,$var4=null,$var5=null) {
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
			  		 if($var==""){
			         	$this->set('vector','');
			  		 }else{
					 $this->set('SELECT','subcuenta_contable');//El parametro que se le pasa para que busque el proximo select (cuando entre en select3)
					 $this->set('codigo','contable');//El nombre que se le asigna al select actual cuando se crea
					 $this->set('cod_tipocuenta',$var);//cod_tipocuenta es para mantener el valor de la variable que llega y pasarselo al paso que viene en select3
					 $this->set('seleccion','');
					 $this->set('n',2);
					 $this->set('var',$var);
					 $this->set('var2',$var2);
					 $this->set('var3',$var3);
					 $this->set('var4',$var4);
					 $this->set('var5',$var5);
					 $cond = $cond2." and cod_tipo_cuenta=".$var;
					 $lista = $this->ccfd01_cuenta->generateList($cond, 'cod_cuenta ASC', null, '{n}.ccfd01_cuenta.cod_cuenta', '{n}.ccfd01_cuenta.denominacion');
			   		 $this->concatena($lista, 'vector');
			 		 }//fin vacio
			  break;

		case 'subcuenta_contable':
			  		 if($var2==""){
						$this->set('vector','');
			  		 }else{
					 $this->set('SELECT','div_estadistica_contable');
					 $this->set('codigo','subcuenta_contable');
					 $this->set('seleccion','');
					 $this->set('n',3);
					 $this->set('var',$var);
					 $this->set('var2',$var2);
					 $this->set('var3',$var3);
					 $this->set('var4',$var4);
					 $this->set('var5',$var5);
					 $cond = $cond2." and cod_tipo_cuenta=".$var." and cod_cuenta=".$var2;
					 $lista=  $this->ccfd01_subcuenta->generateList($cond, 'cod_subcuenta ASC', null, '{n}.ccfd01_subcuenta.cod_subcuenta', '{n}.ccfd01_subcuenta.denominacion');
			         $this->concatena($lista, 'vector');
				}// fin vacio
			    break;

	     case 'div_estadistica_contable':
					if($var3==""){
					    $this->set('vector','');
					}else{
				  		$this->set('SELECT','subdiv_estadistica_contable');
				  		$this->set('codigo','div_estadistica_contable');
				  		$this->set('seleccion','');
				  		//$this->set('no','no');
				  		$this->set('n',4);
				   		$this->set('var',$var);
				   		$this->set('var2',$var2);
				   		$this->set('var3',$var3);
				   		$this->set('var4',$var4);
				   		$this->set('var5',$var5);
				  		$cond = $cond2." and cod_tipo_cuenta=".$var." and cod_cuenta=".$var2." and cod_subcuenta=".$var3;
				  		$lista = $this->ccfd01_division->generateList($cond, 'cod_division ASC', null, '{n}.ccfd01_division.cod_division', '{n}.ccfd01_division.denominacion');
		   		  		$this->concatena($lista, 'vector');
						}
			  		break;

		 case 'subdiv_estadistica_contable':
					if($var4==""){
					    $this->set('vector','');
					}else{
				  		$this->set('SELECT','direccion');
				  		$this->set('codigo','subdiv_estadistica_contable');
				  		$this->set('seleccion','');
				  		$this->set('no','no');
				  		$this->set('n',5);
				   		$this->set('var',$var);
				   		$this->set('var2',$var2);
				   		$this->set('var3',$var3);
				   		$this->set('var4',$var4);
				   		$this->set('var5',$var5);
				  		$cond = $cond2." and cod_tipo_cuenta=".$var." and cod_cuenta=".$var2." and cod_subcuenta=".$var3." and cod_division=".$var4;
				  		$lista = $this->ccfd01_subdivision->generateList($cond, 'cod_division ASC', null, '{n}.ccfd01_subdivision.cod_subdivision', '{n}.ccfd01_subdivision.denominacion');
		   		  		$this->concatena($lista, 'vector');
						}
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


 /*Muestra los codigos*/
 function mostrar4($select=null,$var=null,$var2=null,$var3=null,$var4=null,$var5=null) {
	$this->layout = "ajax";

    if( $var!=null){
    $cond2 = $this->SQLCA();
    if($var2 == "agregar"){
    	echo "<input type='text' name='data[ccfp01_cuenta][cod_cuenta_contable]' value='' size='10'  maxlength='4' id='cod_cuenta_contable' readonly='readonly' style='text-align:center' />";
    }else{

	switch($select){
		   case 'tipo':
					  if($var==""){
						  echo "<input type='text' name='data[ccfp01_subdivision][cod_tipo_cuenta]' value='' size='10'  maxlength='4' id='cod_tipo_cuenta' readonly='readonly' style='text-align:center' />";
					  }else{
	       			  $this->set('var',$var);
		        	  $this->set('var2',$var2);
			          $this->set('var3',$var3);
			          $this->set('var4',$var4);
					  /*
					  echo $select." --- > 1<br>";
					  echo $var." ---  > 2.<br>";
					  echo $var2." --- > 3.<br>";
					  echo $var3." --- > 4.<br>";
					  echo $var4." --- > 5.<br>";
			  		  */
			  		  $cond = $cond2." and cod_tipo_cuenta=".$var;
				      $a=  $this->ccfd01_tipo->findAll($cond);
		              echo "<input type='text' name='data[ccfp01_subdivision][cod_tipo_cuenta]' value='".$a[0]['ccfd01_tipo']['cod_tipo_cuenta']."' size='10'  maxlength='4' id='cod_tipo_cuenta' readonly='readonly' style='text-align:center' />";
						 echo "<script>";
					  	echo "document.getElementById('cod_cuenta_contable').value='';";
					  	echo "document.getElementById('cod_subcuenta_contable').value='';";
					  	echo "document.getElementById('cod_div_contable').value='';";
					  	echo "document.getElementById('cod_subdivision_contable').value='';";
					  	echo "document.getElementById('deno_cuenta_contable').value='';";
					  	echo "document.getElementById('deno_subcuenta_contable').value='';";
					  	echo "document.getElementById('deno_div_contable').value='';";
					  	echo "document.getElementById('deno_subdivision_contable').value='';";
					  	echo "document.getElementById('concepto_subdivision_contable').value='';";
					  echo "</script>";
				}
				break;

			case 'contable':
					  if($var2==""){
					      echo "<input type='text' name='data[ccfp01_subdivision][cod_cuenta_contable]' value='' size='10'  maxlength='4' id='cod_cuenta_contable' readonly='readonly' style='text-align:center' />";
					  }else{
					  $this->set('var',$var);
				      $this->set('var2',$var2);
			  		  $this->set('var3',$var3);
			  		  $this->set('var4',$var4);
					  /*
					  echo $select." --- > 1<br>";
					  echo $var." ---  > 2.<br>";
					  echo $var2." --- > 3.<br>";
					  echo $var3." --- > 4.<br>";
					  echo $var4." --- > 5.<br>";
				      */
				      $cond = $cond2." and cod_tipo_cuenta=".$var." and cod_cuenta=".$var2;
					  $a=  $this->ccfd01_cuenta->findAll($cond);
			          echo "<input type='text' name='data[ccfp01_subdivision][cod_cuenta_contable]' value='".$this->m2($a[0]['ccfd01_cuenta']['cod_cuenta'])."' size='10'  maxlength='4' id='cod_cuenta_contable' readonly='readonly' style='text-align:center' />";
					   echo "<script>";
					  	//echo "document.getElementById('cod_cuenta_contable').value='';";
					  	echo "document.getElementById('cod_subcuenta_contable').value='';";
					  	echo "document.getElementById('cod_div_contable').value='';";
					  	echo "document.getElementById('cod_subdivision_contable').value='';";
					  	//echo "document.getElementById('deno_cuenta_contable').value='';";
					  	echo "document.getElementById('deno_subcuenta_contable').value='';";
					  	echo "document.getElementById('deno_div_contable').value='';";
					  	echo "document.getElementById('deno_subdivision_contable').value='';";
					  	echo "document.getElementById('concepto_subdivision_contable').value='';";
					  echo "</script>";

					  }
					  break;

			case 'subcuenta_contable':
					 if($var3==""){
							echo "<input type='text' name='data[ccfp01_subdivision][cod_subcuenta_contable]' value='' size='10'  maxlength='4' id='cod_subcuenta_contable' readonly='readonly' style='text-align:center' />";
					  }else{
				      $this->set('var',$var);
			  		  $this->set('var2',$var2);
			  		  $this->set('var3',$var3);
			  		  $this->set('var4',$var4);
					  /*
					  echo $select." --- > 1<br>";
					  echo $var." ---  > 2.<br>";
					  echo $var2." --- > 3.<br>";
					  echo $var3." --- > 4.<br>";
					  echo $var4." --- > 5.<br>";
					  */
					  $cond = $cond2." and cod_tipo_cuenta=".$var." and cod_cuenta=".$var2." and cod_subcuenta=".$var3;
					  $a=  $this->ccfd01_subcuenta->findAll($cond);
			          echo "<input type='text' name='data[ccfp01_subdivision][cod_subcuenta_contable]' value='".$this->m3($a[0]['ccfd01_subcuenta']['cod_subcuenta'])."' size='10'  maxlength='4' id='cod_subcuenta_contable' readonly='readonly' style='text-align:center' />";
					  echo "<script>";
					  	//echo "document.getElementById('cod_cuenta_contable').value='';";
					  	//echo "document.getElementById('cod_subcuenta_contable').value='';";
					  	echo "document.getElementById('cod_div_contable').value='';";
					  	echo "document.getElementById('cod_subdivision_contable').value='';";
					  	//echo "document.getElementById('deno_cuenta_contable').value='';";
					  	//echo "document.getElementById('deno_subcuenta_contable').value='';";
					  	echo "document.getElementById('deno_div_contable').value='';";
					  	echo "document.getElementById('deno_subdivision_contable').value='';";
					  	echo "document.getElementById('concepto_subdivision_contable').value='';";
					  echo "</script>";

				}
				break;

			case 'div_estadistica_contable':
					  if($var4==""){
					 	    echo "<input type='text' name='data[ccfp01_subdivision][cod_div_contable]' value='' size='10'  maxlength='4' id='cod_div_contable' readonly='readonly' style='text-align:center' />";
					  }else{
		 			      $this->set('var',$var);
				  		  $this->set('var2',$var2);
				  		  $this->set('var3',$var3);
				  		  $this->set('var4',$var4);
						  /*
						  echo $select." --- > 1<br>";
						  echo $var." ---  > 2.<br>";
						  echo $var2." --- > 3.<br>";
						  echo $var3." --- > 4.<br>";
						  echo $var4." --- > 5.<br>";
						  */
						  $cond = $cond2." and cod_tipo_cuenta=".$var." and cod_cuenta=".$var2." and cod_subcuenta=".$var3." and cod_division=".$var4;
						  $a=  $this->ccfd01_division->findAll($cond);
				          echo "<input type='text' name='data[ccfp01_subdivision][cod_div_contable]' value='".$this->m4($a[0]['ccfd01_division']['cod_division'])."' size='10'  maxlength='4' id='cod_div_contable' readonly='readonly' style='text-align:center' />";
					  	   echo "<script>";
					  	//echo "document.getElementById('cod_cuenta_contable').value='';";
					  	//echo "document.getElementById('cod_subcuenta_contable').value='';";
					  	//echo "document.getElementById('cod_div_contable').value='';";
					  	echo "document.getElementById('cod_subdivision_contable').value='';";
					  	//echo "document.getElementById('deno_cuenta_contable').value='';";
					  	//echo "document.getElementById('deno_subcuenta_contable').value='';";
					  	//echo "document.getElementById('deno_div_contable').value='';";
					  	echo "document.getElementById('deno_subdivision_contable').value='';";
					  	echo "document.getElementById('concepto_subdivision_contable').value='';";
					  echo "</script>";

					  }
					  break;

			case 'subdiv_estadistica_contable':
					  if($var5==""){
					 	    echo "<input type='text' name='data[ccfp01_subdivision][cod_subdivision_contable]' value='' size='10'  maxlength='4' id='cod_subdivision_contable' readonly='readonly' style='text-align:center' />";
					  }else{
					  	  if($var5=="agregar"){
					 		    echo "<input type='text' name='data[ccfp01_subdivision][cod_subdivision_contable]' value='' size='10'  onKeyPress='return solonumeros(event);' maxlength='5' id='cod_subdivision_contable' style='text-align:center' />";
						  }else{
		 			      $this->set('var',$var);
				  		  $this->set('var2',$var2);
				  		  $this->set('var3',$var3);
				  		  $this->set('var4',$var4);
						  /*
						  echo $select." --- > 1<br>";
						  echo $var." ---  > 2.<br>";
						  echo $var2." --- > 3.<br>";
						  echo $var3." --- > 4.<br>";
						  echo $var4." --- > 5.<br>";
						  */
						  $cond = $cond2." and cod_tipo_cuenta=".$var." and cod_cuenta=".$var2." and cod_subcuenta=".$var3." and cod_division=".$var4." and cod_subdivision=".$var5;
						  $a=  $this->ccfd01_subdivision->findAll($cond);
				          echo "<input type='text' name='data[ccfp01_subdivision][cod_subdivision_contable]' value='".$this->m2($a[0]['ccfd01_subdivision']['cod_subdivision'])."' size='10'  maxlength='4' id='cod_subdivision_contable' readonly='readonly' style='text-align:center' />";
					    }
					  }
					  break;
	   }// ------------- fin switch
       }

	}else{
         echo "<input type='text' name='data[ccfp01_cuenta]' size='10' maxlength='4' id='cod_entidad_bancaria' style='text-align:center' readonly='readonly' />";
}
}// fin function mostrar4



 /*Muestra la denominacion*/
 function mostrar3($select=null,$var=null,$var2=null,$var3=null,$var4=null,$var5=null) {
 $this->layout = "ajax";

    if( $var!=null && !empty($var)){
   		$cond2 = $this->SQLCA();
   		if($var2 == "agregar"){
    		echo "<input type='text' name='data[ccfp01_cuenta][deno_cuenta_contable]' value='' size='65'  class='inputtext' maxlength='100' id='deno_cuenta_contable' />";
    	}else{

		switch($select){
			case 'tipo':
				 	  if($var==""){
						  echo "<input type='text' name='data[ccfp01_subdivision][deno_tipo_cuenta]' value='' size='65'  class='inputtext' maxlength='100' id='deno_tipo_cuenta' readonly='readonly' />";
					  }else{
					  $this->set('var',$var);
					  $this->set('var2',$var2);
					  $this->set('var3',$var3);
					  $this->set('var4',$var4);
					  $this->set('var5',$var5);
				      /*
				      echo $select." --- > 1<br>";
				      echo $var." ---  > 2.<br>";
				      echo $var2." --- > 3.<br>";
					  echo $var3." --- > 4.<br>";
					  echo $var4." --- > 5.<br>";
					  */
				      $cond = $cond2." and cod_tipo_cuenta=".$var;
				      $a=  $this->ccfd01_tipo->findAll($cond);
		         	  echo "<input type='text' name='data[ccfp01_subdivision][deno_tipo_cuenta]' value='".$a[0]['ccfd01_tipo']['denominacion']."' size='65'  class='inputtext' maxlength='100' id='deno_tipo_cuenta' readonly='readonly' />";
				 	  }
				      break;

			case 'contable':
					  if($var2==""){
						  echo "<input type='text' name='data[ccfp01_subdivision][deno_cuenta_contable]' value='' size='65'  class='inputtext' maxlength='100' id='deno_cuenta_contable' readonly='readonly' />";
					  }else{
					  $this->set('var',$var);
			          $this->set('var2',$var2);
			  		  $this->set('var3',$var3);
			  		  $this->set('var4',$var4);
			  		  $this->set('var5',$var5);
				      /*
				      echo $select." --- > 1<br>";
				   	  echo $var." ---  > 2.<br>";
				      echo $var2." --- > 3.<br>";
				      echo $var3." --- > 4.<br>";
				      echo $var4." --- > 5.<br>";
				      */
					  $cond = $cond2." and cod_tipo_cuenta=".$var." and cod_cuenta=".$var2;
				      $a=  $this->ccfd01_cuenta->findAll($cond);
			          echo "<input type='text' name='data[ccfp01_subdivision][deno_cuenta_contable]' value='".$a[0]['ccfd01_cuenta']['denominacion']."' size='65'  class='inputtext' maxlength='100' id='deno_cuenta_contable' readonly='readonly' />";
					  }
				      break;

			case 'subcuenta_contable':
					  if($var3==""){
						  echo "<input type='text' name='data[ccfp01_subdivision][deno_subcuenta_contable]' value='' size='65'  class='inputtext' maxlength='100' id='deno_subcuenta_contable' readonly='readonly' />";
					  }else{
					  $this->set('var',$var);
			  		  $this->set('var2',$var2);
			  		  $this->set('var3',$var3);
					  $this->set('var4',$var4);
					  $this->set('var5',$var5);
				   	  /*
				   	  echo $select." --- > 1<br>";
				   	  echo $var." ---  > 2.<br>";
				   	  echo $var2." --- > 3.<br>";
				   	  echo $var3." --- > 4.<br>";
				   	  echo $var4." --- > 5.<br>";
				   	  */
				   	  $cond = $cond2." and cod_tipo_cuenta=".$var." and cod_cuenta=".$var2." and cod_subcuenta=".$var3;
			       	  $a=  $this->ccfd01_subcuenta->findAll($cond);
			       	  echo "<input type='text' name='data[ccfp01_subdivision][deno_subcuenta_contable]' value='".$a[0]['ccfd01_subcuenta']['denominacion']."' size='65'  class='inputtext' maxlength='100' id='deno_subcuenta_contable' readonly='readonly' />";
					  }
			      	  break;

			case 'div_estadistica_contable':
					  if($var4==""){
						  echo "<input type='text' name='data[ccfp01_subdivision][deno_div_contable]' value='' size='65'  class='inputtext' maxlength='100' id='deno_div_contable' readonly='readonly' />";
					  }else{
					  $this->set('var',$var);
					  $this->set('var2',$var2);
			  		  $this->set('var3',$var3);
					  $this->set('var4',$var4);
					  $this->set('var5',$var5);
				   	  /*
				   	  echo $select." --- > 1<br>";
				   	  echo $var." ---  > 2.<br>";
				      echo $var2." --- > 3.<br>";
				   	  echo $var3." --- > 4.<br>";
				   	  echo $var4." --- > 5.<br>";
				   	  */
				   	  $cond = $cond2." and cod_tipo_cuenta=".$var." and cod_cuenta=".$var2." and cod_subcuenta=".$var3." and cod_division=".$var4;
				   	  $a=  $this->ccfd01_division->findAll($cond);
		           	  echo "<input type='text' name='data[ccfp01_subdivision][deno_div_contable]' value='".$a[0]['ccfd01_division']['denominacion']."' size='65'  class='inputtext' maxlength='100' id='deno_div_contable' readonly='readonly' />";
					  }
			       	  break;
            case 'subdiv_estadistica_contable':
					  if($var5==""){
						  echo "<input type='text' name='data[ccfp01_subdivision][deno_subdivision_contable]' value='' size='65'  class='inputtext' maxlength='100' id='deno_subdivision_contable' readonly='readonly' />";
					  }else{
					  	  if($var5=="agregar"){
					 		    echo "<input type='text' name='data[ccfp01_subdivision][deno_subdivision_contable]' value='' size='65'  class='inputtext' maxlength='100' id='deno_subdivision_contable' />";
						  }else{
					  $this->set('var',$var);
					  $this->set('var2',$var2);
			  		  $this->set('var3',$var3);
					  $this->set('var4',$var4);
					  $this->set('var5',$var5);
				   	  /*
				   	  echo $select." --- > 1<br>";
				   	  echo $var." ---  > 2.<br>";
				      echo $var2." --- > 3.<br>";
				   	  echo $var3." --- > 4.<br>";
				   	  echo $var4." --- > 5.<br>";
				   	  */
				   	  $cond = $cond2." and cod_tipo_cuenta=".$var." and cod_cuenta=".$var2." and cod_subcuenta=".$var3." and cod_division=".$var4." and cod_subdivision=".$var5;
				   	  $a=  $this->ccfd01_subdivision->findAll($cond);
		           	  echo "<input type='text' name='data[ccfp01_subdivision][deno_subdivision_contable]' value='".$a[0]['ccfd01_subdivision']['denominacion']."' size='65' class='inputtext'  maxlength='100' id='deno_subdivision_contable' readonly='readonly' />";
					  }
					  }
			       	  break;
		}//fin switch
   		}

	}else{
		  echo "<input type='text' name='data[ccfp01_cuenta] value='' size='65'  class='inputtext' maxlength='100' readonly='readonly' />";
	}
 }// fin function mostrar3 denominaciones



 /*Muestra los conceptos*/
 function mostrar5($select=null,$var=null,$var2=null,$var3=null,$var4=null,$var5=null) {
	$this->layout = "ajax";
	if( $var!=null){
		$cond2 = $this->SQLCA();
		if($var2 == "agregar"){
    		echo "<input type='text' name='data[ccfp01_cuenta][concepto_cuentacontable]' value='' rows='6'  maxlength='100' id='concepto_cuentacontable' />";
    	}else{

		switch($select){
			case 'tipo':
					  if($var==""){
						  echo "<input type='text' name='data[ccfp01_subdivision][concepto_tipo_cuenta]' value='' rows='6'  maxlength='100' id='concepto_tipo_cuenta' readonly='readonly' />";
					  }else{
			          $this->set('var',$var);
					  $this->set('var2',$var2);
			  	      $this->set('var3',$var3);
			  		  $this->set('var4',$var4);
			  		  $this->set('var5',$var5);
			   		  /*
			   		  echo $select." --- > 1<br>";
					  echo $var." ---  > 2.<br>";
					  echo $var2." --- > 3.<br>";
					  echo $var3." --- > 4.<br>";
					  echo $var4." --- > 4.<br>";
					  */
               		  $cond = $cond2." and cod_tipo_cuenta=".$var;
				      $a=  $this->ccfd01_tipo->findAll($cond);
		              echo "<input type='text' name='data[ccfp01_subdivision][concepto_tipo_cuenta]' value='".$a[0]['ccfd01_tipo']['concepto']."' rows='6'  maxlength='100' id='concepto_tipo_cuenta' readonly='readonly' />";
					  }
					  break;

			case 'contable':
					  if($var2==""){
						  echo "<input type='text' name='data[ccfp01_subdivision][concepto_cuentacontable]' value='' rows='6'  maxlength='100' id='concepto_cuentacontable' readonly='readonly' />";
					  }else{
					  $this->set('var',$var);
			  		  $this->set('var2',$var2);
			  		  $this->set('var3',$var3);
			  		  $this->set('var4',$var4);
			  		  $this->set('var5',$var5);
					  /*
					  echo $select." --- > 1<br>";
					  echo $var." ---  > 2.<br>";
					  echo $var2." --- > 3.<br>";
					  echo $var3." --- > 4.<br>";
					  echo $var4." --- > 5.<br>";
					  */
			          $cond = $cond2." and cod_tipo_cuenta=".$var." and cod_cuenta=".$var2;
				      $a=  $this->ccfd01_cuenta->findAll($cond);
		              echo "<input type='text' name='data[ccfp01_subdivision][concepto_cuentacontable]' value='".$a[0]['ccfd01_cuenta']['concepto']."' rows='6'  maxlength='100' id='concepto_cuentacontable' readonly='readonly' />";
					  }
				      break;

		    case 'subcuenta_contable':
					  if($var3==""){
						  echo "<input type='text' name='data[ccfp01_subdivision][concepto_subcuentacontable]' value='' rows='6'  maxlength='100' id='concepto_subcuentacontable' readonly='readonly' />";
					  }else{
		    		  $this->set('var',$var);
			  	      $this->set('var2',$var2);
			  		  $this->set('var3',$var3);
			  		  $this->set('var4',$var4);
			  		  $this->set('var5',$var5);
					  /*
					  echo $select." --- > 1<br>";
					  echo $var." ---  > 2.<br>";
					  echo $var2." --- > 3.<br>";
					  echo $var3." --- > 4.<br>";
					  echo $var4." --- > 5.<br>";
					  */
			 		  $cond = $cond2." and cod_tipo_cuenta=".$var." and cod_cuenta=".$var2." and cod_subcuenta=".$var3;
				      $a=  $this->ccfd01_subcuenta->findAll($cond);
		              echo "<input type='text' name='data[ccfp01_subdivision][concepto_subcuentacontable]' value='".$a[0]['ccfd01_subcuenta']['concepto']."' rows='6'  maxlength='100' id='concepto_subcuentacontable' readonly='readonly' />";
					  }
					  break;

			case 'div_estadistica_contable':
					  if($var4==""){
						 echo "<input type='text' name='data[ccfp01_subdivision][concepto_div_contable]' value='' rows='6'  maxlength='100' id='concepto_div_contable' readonly='readonly' />";
					  }else{
					  $this->set('var',$var);
			  		  $this->set('var2',$var2);
			  		  $this->set('var3',$var3);
			  		  $this->set('var4',$var4);
			  		  $this->set('var5',$var5);
				      /*
				      echo $select." --- > 1<br>";
				      echo $var." ---  > 2.<br>";
				      echo $var2." --- > 3.<br>";
				      echo $var3." --- > 4.<br>";
				      echo $var4." --- > 5.<br>";
				      */
				      $cond = $cond2." and cod_tipo_cuenta=".$var." and cod_cuenta=".$var2." and cod_subcuenta=".$var3." and cod_division=".$var4;
			     	  $a=  $this->ccfd01_division->findAll($cond);
	                  echo "<input type='text' name='data[ccfp01_subdivision][concepto_div_contable]' value='".$a[0]['ccfd01_division']['concepto']."' rows='6'  maxlength='100' id='concepto_div_contable' readonly='readonly' />";
					  }
				      break;
            case 'subdiv_estadistica_contable':
					  if($var5==""){
					  	echo "<textarea name='data[ccfp01_subdivision][concepto_subdivision_contable]' value='' rows='6'  class='inputtext' maxlength='100' id='concepto_subdivision_contable' readonly='readonly' ></textarea>";
						//echo "<input type='text' name='data[ccfp01_subdivision][concepto_subdivision_contable]' value='' size='55'  maxlength='100' id='concepto_subdivision_contable' readonly='readonly' />";
					  }else{
					  	  if($var5=="agregar"){
					  	  		echo "<textarea name='data[ccfp01_subdivision][concepto_subdivision_contable]' value='' rows='6'  class='inputtext' maxlength='100' id='concepto_subdivision_contable'  ></textarea>";
					 		   // echo "<input type='text' name='data[ccfp01_subdivision][concepto_subdivision_contable]' value='' size='55'  maxlength='100' id='concepto_subdivision_contable' />";
						  }else{
					  $this->set('var',$var);
			  		  $this->set('var2',$var2);
			  		  $this->set('var3',$var3);
			  		  $this->set('var4',$var4);
			  		  $this->set('var5',$var5);
				      /*
				      echo $select." --- > 1<br>";
				      echo $var." ---  > 2.<br>";
				      echo $var2." --- > 3.<br>";
				      echo $var3." --- > 4.<br>";
				      echo $var4." --- > 5.<br>";
				      */
				      $cond = $cond2." and cod_tipo_cuenta=".$var." and cod_cuenta=".$var2." and cod_subcuenta=".$var3." and cod_division=".$var4." and cod_subdivision=".$var5;
			     	  $a=  $this->ccfd01_subdivision->findAll($cond);
			     	  echo "<textarea name='data[ccfp01_subdivision][concepto_subdivision_contable]' value='' rows='6'  class='inputtext' maxlength='100' id='concepto_subdivision_contable' readonly='readonly' >".$a[0]['ccfd01_subdivision']['concepto']."</textarea>";
	                 // echo "<input type='text' name='data[ccfp01_subdivision][concepto_subdivision_contable]' value='".$a[0]['ccfd01_subdivision']['concepto']."' size='55'  maxlength='100' id='concepto_subdivision_contable' readonly='readonly' />";
					  }
					  }
				      break;
		}//fin switch
		}

    }else{
    	   echo "<textarea name='data[ccfp01_subdivision][concepto_subdivision_contable]' value='' rows='6'  class='inputtext' maxlength='100' id='concepto_div_contable' readonly='readonly' ></textarea>";
    	  //echo "<input type='text' name='data[ccfp01_cuenta][concepto_tipo_cuenta]' value='' size='55'  maxlength='100' id='deno_sucursal_bancaria' readonly='readonly' />";
    }
 }//fin function mostrar5


 function guardar(){
 	$this->layout="ajax";
 	if(!empty($this->data['ccfp01_subdivision'])){
		$cod_tipo = $this->data['ccfp01_subdivision']['codigo_tipo'];
 		$cod_cuenta_contable = $this->data['ccfp01_subdivision']['cod_contable'];
 		$cod_subcuenta_contable = $this->data['ccfp01_subdivision']['cod_subcuenta_contable'];
 		//$cod_divisionEstad_contable = $this->data['ccfp01_subdivision']['cod_div_estadistica_contable'];

 		$cod_div_contable = $this->data['ccfp01_subdivision']['cod_div_contable'];// el que me llega por el input no por el select_4
 		$deno_div_contable = $this->data['ccfp01_subdivision']['deno_div_contable'];
 	//	$concepto_div_contable = $this->data['ccfp01_subdivision']['concepto_div_contable'];

 		$cod_subdivision_contable = $this->data['ccfp01_subdivision']['cod_subdivision_contable'];
 		$deno_subdivision_contable = $this->data['ccfp01_subdivision']['deno_subdivision_contable'];
 		$concepto_subdivision_contable = $this->data['ccfp01_subdivision']['concepto_subdivision_contable'];


		$consulta = "SELECT * FROM ccfd01_subdivision WHERE ".$this->SQLCA()." and cod_tipo_cuenta=".$cod_tipo." and cod_cuenta=".$cod_cuenta_contable." and cod_subcuenta=".$cod_subcuenta_contable." and cod_division=".$cod_div_contable." and cod_subdivision=".$cod_subdivision_contable;

		if($this->ccfd01_subdivision->execute($consulta)){
//			$this->set('mensajeError','este codigo ya se encuentra registrado, revise por favor');
			$sql="update ccfd01_subdivision set denominacion='$deno_subdivision_contable', concepto='$concepto_subdivision_contable' where ".$this->SQLCA()." and cod_tipo_cuenta=".$cod_tipo." and cod_cuenta=".$cod_cuenta_contable." and cod_subcuenta=".$cod_subcuenta_contable." and cod_division=".$cod_div_contable." and cod_subdivision=".$cod_subdivision_contable;
			$this->ccfd01_subdivision->execute($sql);
			$this->set('mensaje','Los datos fuer&oacute;n Actualizados');
			//$this->index();
			//$this->render("index");
		}else{

			$sql = "INSERT INTO ccfd01_subdivision values('".$this->ss(1)."','".$this->ss(2)."','".$this->ss(3)."','".$this->ss(4)."','".$this->ss(5)."','$cod_tipo','$cod_cuenta_contable','$cod_subcuenta_contable','$cod_div_contable','$cod_subdivision_contable','$deno_subdivision_contable','$concepto_subdivision_contable')";
			if($this->ccfd01_subdivision->execute($sql)>1){
			$this->set('mensaje','Los datos fuer&oacute;n registrados correctamente');
			//$this->index();
			//$this->render("index");

			}else{
			$this->set('mensajeError','los datos no pudieron ser registrados');
			//$this->index();
			//$this->render("index");
			}
		}
	}
				echo "<script>;";
					echo "document.getElementById('concepto_subdivision_contable').value='';";
					echo "document.getElementById('cod_subdivision_contable').value='';";
					echo "document.getElementById('deno_subdivision_contable').value='';";
					echo "document.getElementById('save').disabled=false;";
					echo "document.getElementById('consultar').disabled=false;";
				echo "</script>";
 }// fin guardar



  function consultar($pagina=null) {
	$this->layout="ajax";
	$this->set('entidad_federal', $this->Session->read('entidad_federal'));
	//$this->set('enable2', 'enabled');
 	//$this->set('enable', 'disabled');
 	//$this->set('read', 'readonly');

	$consulta1=$this->SQLCA();

	if(isset($pagina)){
		$Tfilas=$this->ccfd01_subdivision->findCount();
        if($Tfilas!=0){
        	$data=$this->ccfd01_subdivision->findAll($consulta1,null,"cod_tipo_cuenta,cod_cuenta,cod_subcuenta,cod_division, cod_subdivision ASC",1,$pagina,null);

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

	}else{
		$pagina=1;
		$Tfilas=$this->ccfd01_subdivision->findCount();

        if($Tfilas!=0){
        	$data=$this->ccfd01_subdivision->findAll($consulta1,null,"cod_tipo_cuenta,cod_cuenta,cod_subcuenta,cod_division, cod_subdivision ASC",1,$pagina,null);
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

	$this->set('cod_tipo_cuenta', $data[0]['ccfd01_subdivision']['cod_tipo_cuenta']);
	$this->set('cod_cuenta', $data[0]['ccfd01_subdivision']['cod_cuenta']);
	$this->set('cod_subcuenta', $data[0]['ccfd01_subdivision']['cod_subcuenta']);
    $this->set('cod_division', $data[0]['ccfd01_subdivision']['cod_division']);

    $this->set('cod_subdivision', $data[0]['ccfd01_subdivision']['cod_subdivision']);
    $this->set('deno_subdivision_estadistica', $data[0]['ccfd01_subdivision']['denominacion']);
    $this->set('concepto_subdivision_estadistica', $data[0]['ccfd01_subdivision']['concepto']);

	/* El siguiente bloque es para obtener las denominaciones y los conceptos */
	$tipo_cuenta=$this->ccfd01_tipo->findAll($consulta1.' and cod_tipo_cuenta='.$data[0]['ccfd01_subdivision']['cod_tipo_cuenta']);
	$cuenta_contable=$this->ccfd01_cuenta->findAll($consulta1.' and cod_tipo_cuenta='.$data[0]['ccfd01_subdivision']['cod_tipo_cuenta'].' and cod_cuenta='.$data[0]['ccfd01_subdivision']['cod_cuenta']);
	$cuenta_subcontable=$this->ccfd01_subcuenta->findAll($consulta1.' and cod_tipo_cuenta='.$data[0]['ccfd01_subdivision']['cod_tipo_cuenta'].' and cod_cuenta='.$data[0]['ccfd01_subdivision']['cod_cuenta'].' and cod_subcuenta='.$data[0]['ccfd01_subdivision']['cod_subcuenta']);
	$cuenta_division=$this->ccfd01_division->findAll($consulta1.' and cod_tipo_cuenta='.$data[0]['ccfd01_subdivision']['cod_tipo_cuenta'].' and cod_cuenta='.$data[0]['ccfd01_subdivision']['cod_cuenta'].' and cod_subcuenta='.$data[0]['ccfd01_subdivision']['cod_subcuenta'].' and cod_division='.$data[0]['ccfd01_subdivision']['cod_division']);

	$this->set('deno_tipo_cuenta',$tipo_cuenta[0]['ccfd01_tipo']['denominacion']);
	$this->set('deno_contable',$cuenta_contable[0]['ccfd01_cuenta']['denominacion']);
	$this->set('deno_subcontable',$cuenta_subcontable[0]['ccfd01_subcuenta']['denominacion']);
	$this->set('deno_div_estadistica',$cuenta_division[0]['ccfd01_division']['denominacion']);

	$this->set('concepto_tipo_cuenta',$tipo_cuenta[0]['ccfd01_tipo']['concepto']);
	$this->set('concepto_contable',$cuenta_contable[0]['ccfd01_cuenta']['concepto']);
	$this->set('concepto_subcontable',$cuenta_subcontable[0]['ccfd01_subcuenta']['concepto']);
	$this->set('concepto_div_estadistica',$cuenta_division[0]['ccfd01_division']['concepto']);
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



  function modificar($cod_tipo=null, $cod_contable=null, $cod_subcuenta_contable=null, $cod_division=null, $cod_subdivision=null, $pagina_regreso=null){
 	$this->layout="ajax";

 	$cond1 = $this->SQLCA();

if(isset($cod_tipo) && isset($cod_contable) && isset($cod_subcuenta_contable) && isset($cod_division) && isset($cod_subdivision)){
 	$sql = "SELECT * FROM ccfd01_subdivision WHERE ".$cond1." and cod_tipo_cuenta=".$cod_tipo." and cod_cuenta=".$cod_contable." and cod_subcuenta=".$cod_subcuenta_contable." and cod_division=".$cod_division." and cod_subdivision=".$cod_subdivision;
 	$sql2 = $cond1." and cod_tipo_cuenta=".$cod_tipo." and cod_cuenta=".$cod_contable." and cod_subcuenta=".$cod_subcuenta_contable." and cod_division=".$cod_division." and cod_subdivision=".$cod_subdivision;
 	//$data = $this->ccfd01_division->execute($sql);
 	$data = $this->ccfd01_subdivision->findAll($sql2);//* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * quedamos con un error en la parte de modifica se debe revisar
 	if($data){
 		$this->set('mensaje','Puede proceder a Modificar los datos');
		$tipo_cuenta=$this->ccfd01_tipo->findAll($cond1.' and cod_tipo_cuenta='.$data[0]['ccfd01_subdivision']['cod_tipo_cuenta'], null, null, 1);
		$cuenta_contable=$this->ccfd01_cuenta->findAll($cond1.' and cod_tipo_cuenta='.$data[0]['ccfd01_subdivision']['cod_tipo_cuenta'].' and cod_cuenta='.$data[0]['ccfd01_subdivision']['cod_cuenta'], null, null, 1);
		$cuenta_subcontable=$this->ccfd01_subcuenta->findAll($cond1.' and cod_tipo_cuenta='.$data[0]['ccfd01_subdivision']['cod_tipo_cuenta'].' and cod_cuenta='.$data[0]['ccfd01_subdivision']['cod_cuenta'].' and cod_subcuenta='.$data[0]['ccfd01_subdivision']['cod_subcuenta'], null, null, 1);
		$cuenta_division=$this->ccfd01_division->findAll($cond1.' and cod_tipo_cuenta='.$data[0]['ccfd01_subdivision']['cod_tipo_cuenta'].' and cod_cuenta='.$data[0]['ccfd01_subdivision']['cod_cuenta'].' and cod_subcuenta='.$data[0]['ccfd01_subdivision']['cod_subcuenta'].' and cod_division='.$data[0]['ccfd01_subdivision']['cod_division'], null, null, 1);

		$this->set('cod_tipo_cuenta', $data[0]['ccfd01_subdivision']['cod_tipo_cuenta']);
		$this->set('cod_cuenta', $data[0]['ccfd01_subdivision']['cod_cuenta']);
		$this->set('cod_subcuenta', $data[0]['ccfd01_subdivision']['cod_subcuenta']);
		$this->set('cod_division', $data[0]['ccfd01_subdivision']['cod_division']);

   		$this->set('cod_suddivision', $data[0]['ccfd01_subdivision']['cod_subdivision']);
    	$this->set('deno_suddivision_estadistica', $data[0]['ccfd01_subdivision']['denominacion']);
    	$this->set('concepto_suddivision_estadistica', $data[0]['ccfd01_subdivision']['concepto']);

		$this->set('deno_tipo_cuenta',$tipo_cuenta[0]['ccfd01_tipo']['denominacion']);
		$this->set('deno_contable',$cuenta_contable[0]['ccfd01_cuenta']['denominacion']);
		$this->set('deno_subcontable',$cuenta_subcontable[0]['ccfd01_subcuenta']['denominacion']);
		$this->set('deno_div_estadistica',$cuenta_division[0]['ccfd01_division']['denominacion']);

		$this->set('concepto_tipo_cuenta',$tipo_cuenta[0]['ccfd01_tipo']['concepto']);
		$this->set('concepto_contable',$cuenta_contable[0]['ccfd01_cuenta']['concepto']);
		$this->set('concepto_subcontable',$cuenta_subcontable[0]['ccfd01_subcuenta']['concepto']);
		$this->set('concepto_div_estadistica',$cuenta_division[0]['ccfd01_division']['concepto']);

		$this->set('pagina_actual', $pagina_regreso+1);
 	}else{
 		$this->set('mensajeError','Error no se encontraron los datos a Modificar');
 	}
  }else{
  		$cod_tipo = $this->data['ccfp01_subdivision']['codigo_tipo'];
 		$cod_contable = $this->data['ccfp01_subdivision']['cod_contable'];
 		$cod_subcuenta_contable = $this->data['ccfp01_subdivision']['cod_subcuenta_contable'];
 		$cod_division = $this->data['ccfp01_subdivision']['cod_div_contable'];
 		$cod_subdivision = $this->data['ccfp01_subdivision']['cod_subdivision_contable'];


 		$sql = "SELECT * FROM ccfd01_subdivision WHERE ".$cond1." and cod_tipo_cuenta=".$cod_tipo." and cod_cuenta=".$cod_contable." and cod_subcuenta=".$cod_subcuenta_contable." and cod_division=".$cod_division." and cod_subdivision=".$cod_subdivision;
 	$sql2 = $cond1." and cod_tipo_cuenta=".$cod_tipo." and cod_cuenta=".$cod_contable." and cod_subcuenta=".$cod_subcuenta_contable." and cod_division=".$cod_division." and cod_subdivision=".$cod_subdivision;
 	//$data = $this->ccfd01_division->execute($sql);
 	$data = $this->ccfd01_subdivision->findAll($sql2);//* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * quedamos con un error en la parte de modifica se debe revisar
 	if($data){
 		$this->set('mensaje','Puede proceder a Modificar los datos');
		$tipo_cuenta=$this->ccfd01_tipo->findAll($cond1.' and cod_tipo_cuenta='.$data[0]['ccfd01_subdivision']['cod_tipo_cuenta'], null, null, 1);
		$cuenta_contable=$this->ccfd01_cuenta->findAll($cond1.' and cod_tipo_cuenta='.$data[0]['ccfd01_subdivision']['cod_tipo_cuenta'].' and cod_cuenta='.$data[0]['ccfd01_subdivision']['cod_cuenta'], null, null, 1);
		$cuenta_subcontable=$this->ccfd01_subcuenta->findAll($cond1.' and cod_tipo_cuenta='.$data[0]['ccfd01_subdivision']['cod_tipo_cuenta'].' and cod_cuenta='.$data[0]['ccfd01_subdivision']['cod_cuenta'].' and cod_subcuenta='.$data[0]['ccfd01_subdivision']['cod_subcuenta'], null, null, 1);
		$cuenta_division=$this->ccfd01_division->findAll($cond1.' and cod_tipo_cuenta='.$data[0]['ccfd01_subdivision']['cod_tipo_cuenta'].' and cod_cuenta='.$data[0]['ccfd01_subdivision']['cod_cuenta'].' and cod_subcuenta='.$data[0]['ccfd01_subdivision']['cod_subcuenta'].' and cod_division='.$data[0]['ccfd01_subdivision']['cod_division'], null, null, 1);

		$this->set('cod_tipo_cuenta', $data[0]['ccfd01_subdivision']['cod_tipo_cuenta']);
		$this->set('cod_cuenta', $data[0]['ccfd01_subdivision']['cod_cuenta']);
		$this->set('cod_subcuenta', $data[0]['ccfd01_subdivision']['cod_subcuenta']);
		$this->set('cod_division', $data[0]['ccfd01_subdivision']['cod_division']);

   		$this->set('cod_suddivision', $data[0]['ccfd01_subdivision']['cod_subdivision']);
    	$this->set('deno_suddivision_estadistica', $data[0]['ccfd01_subdivision']['denominacion']);
    	$this->set('concepto_suddivision_estadistica', $data[0]['ccfd01_subdivision']['concepto']);

		$this->set('deno_tipo_cuenta',$tipo_cuenta[0]['ccfd01_tipo']['denominacion']);
		$this->set('deno_contable',$cuenta_contable[0]['ccfd01_cuenta']['denominacion']);
		$this->set('deno_subcontable',$cuenta_subcontable[0]['ccfd01_subcuenta']['denominacion']);
		$this->set('deno_div_estadistica',$cuenta_division[0]['ccfd01_division']['denominacion']);

		$this->set('concepto_tipo_cuenta',$tipo_cuenta[0]['ccfd01_tipo']['concepto']);
		$this->set('concepto_contable',$cuenta_contable[0]['ccfd01_cuenta']['concepto']);
		$this->set('concepto_subcontable',$cuenta_subcontable[0]['ccfd01_subcuenta']['concepto']);
		$this->set('concepto_div_estadistica',$cuenta_division[0]['ccfd01_division']['concepto']);

		$this->set('pagina_actual','index');
 	}else{
 		$this->set('mensajeError','Error no se encontraron los datos a Modificar');
 	}

  }//fin isset

 }//fin modificar



  function guardar_modificar($cod_tipo_2, $cod_cuenta_contable_2, $cod_subcuenta_contable_2, $cod_division_2, $cod_subdivision_2, $pagina_regreso=null){
 	$this->layout="ajax";

 	if(!empty($this->data['ccfp01_subdivision'])){
		$cod_tipo = $this->data['ccfp01_subdivision']['cod_tipo_cuenta'];
 		$cod_cuenta_contable = $this->data['ccfp01_subdivision']['cod_cuenta_contable'];
 		$cod_subcuenta_contable = $this->data['ccfp01_subdivision']['cod_subcuenta_contable'];
 		$cod_div_contable = $this->data['ccfp01_subdivision']['cod_div_contable'];// el que me llega por el input no por el select_4
 		$cod_subdivision_contable = $this->data['ccfp01_subdivision']['cod_subdivision_contable'];
 		$deno_subdivision_contable = $this->data['ccfp01_subdivision']['deno_subdivision_contable'];
 		$concepto_subdivision_contable = $this->data['ccfp01_subdivision']['concepto_subdivision_contable'];

  	    $sql="update ccfd01_subdivision set cod_subdivision='$cod_subdivision_2', denominacion='$deno_subdivision_contable', concepto='$concepto_subdivision_contable' where ".$this->SQLCA()." and cod_tipo_cuenta='$cod_tipo_2' and cod_cuenta='$cod_cuenta_contable_2' and cod_subcuenta='$cod_subcuenta_contable_2' and cod_division='$cod_division_2' and cod_subdivision='$cod_subdivision_2'";
		if($pagina_regreso!='index'){
			if($this->ccfd01_subdivision->execute($sql)>1){
				$this->set('mensaje','Los datos fuer&oacute;n modificados exitosamente');
				$this->consultar($pagina_regreso);
				$this->render("consultar");
			}else{
				$this->set('mensajeError','Los datos no fuer&oacute;n modificados');
				$this->consultar($pagina_regreso);
				$this->render("consultar");
			}//fin else actualizacion
		}else{
			if($this->ccfd01_subdivision->execute($sql)>1){
				$this->set('mensaje','Los datos fuer&oacute;n modificados exitosamente');
				$this->index();
				$this->render("index");
			}else{
				$this->set('mensajeError','Los datos no fuer&oacute;n modificados');
				$this->index();
				$this->render("index");
			}//fin else actualizacion

		}//fin pagina regreso
 	}
 }//guardar modificar


function modificar1(){
	$this->layout="ajax";
	$this->set('mensaje','Puede proceder a Modificar los datos');

	echo "<script>";
		echo "document.getElementById('deno_subdivision_contable').readOnly=false;";
		echo "document.getElementById('concepto_subdivision_contable').readOnly=false;";
	echo "</script>";
}


  function eliminar($cod_tipo_2=null, $cod_cuenta_contable_2=null, $cod_subcuenta_contable_2=null, $cod_division_2=null, $cod_subdivision_2=null, $pagina_regreso=null){
 		$this->layout="ajax";

	if(isset($cod_tipo_2) && isset($cod_cuenta_contable_2) && isset($cod_subcuenta_contable_2) && isset($cod_division_2) && isset($cod_subdivision_2)){

	    $row_cuenta=$this->ccfd01_cuenta->execute("SELECT cod_subdivision FROM ccfd02 WHERE ".$this->SQLCA()." and cod_tipo_cuenta= ".$cod_tipo_2." and cod_cuenta=".$cod_cuenta_contable_2." and cod_subcuenta=".$cod_subcuenta_contable_2." and cod_division=".$cod_division_2." and cod_subdivision=".$cod_subdivision_2." LIMIT 1;");
		if(!empty($row_cuenta)){
			$this->set('mensajeError', 'Lo siento no se puede eliminar la subdivisi&oacute;n estadistica, debido a que esta siendo utilizada');
				$this->consultar($pagina_regreso);
				$this->render("consultar");
		}else{

			$sql = "DELETE FROM ccfd01_subdivision WHERE ".$this->SQLCA()." and cod_tipo_cuenta= ".$cod_tipo_2." and cod_cuenta=".$cod_cuenta_contable_2." and cod_subcuenta=".$cod_subcuenta_contable_2." and cod_division=".$cod_division_2." and cod_subdivision=".$cod_subdivision_2;
			if($this->ccfd01_subdivision->execute($sql)>1){
				$this->set('mensaje', 'EL REGISTRO FUE ELIMINADO EXITOSAMENTE');
				/////DE AQUI AGREGO ERICK
			$Tfilas=$this->ccfd01_subdivision->findCount();
				if($Tfilas!=0){
				$this->consultar($pagina_regreso);
				$this->render("consultar");
				}else{
					$this->index();
					$this->render("index");
					echo "";
				}/////HASTA AQUI AGREGO ERICK
				//$this->consultar($pagina_regreso);
				//$this->render("consultar");
			}else{
				$this->set('mensajeError', 'EL REGISTRO NO PUDO SER ELIMINADO');
				$this->consultar($pagina_regreso);
				$this->render("consultar");
			}
		}
 		}else{
 			$cod_tipo = $this->data['ccfp01_subdivision']['codigo_tipo'];
	 		$cod_contable = $this->data['ccfp01_subdivision']['cod_contable'];
	 		$cod_subcuenta_contable = $this->data['ccfp01_subdivision']['cod_subcuenta_contable'];
	 		$cod_division = $this->data['ccfp01_subdivision']['cod_div_contable'];
	 		$cod_subdivision = $this->data['ccfp01_subdivision']['cod_subdivision_contable'];

	    $row_cuenta=$this->ccfd01_cuenta->execute("SELECT cod_subdivision FROM ccfd02 WHERE ".$this->SQLCA()." and cod_tipo_cuenta= ".$cod_tipo." and cod_cuenta=".$cod_contable." and cod_subcuenta=".$cod_subcuenta_contable." and cod_division=".$cod_division." and cod_subdivision=".$cod_subdivision." LIMIT 1;");
		if(!empty($row_cuenta)){
			$this->set('mensajeError', 'Lo siento no se puede eliminar la subdivisi&oacute;n estadistica, debido a que esta siendo utilizada');
		}else{

 			$sql = "DELETE FROM ccfd01_subdivision WHERE ".$this->SQLCA()." and cod_tipo_cuenta= ".$cod_tipo." and cod_cuenta=".$cod_contable." and cod_subcuenta=".$cod_subcuenta_contable." and cod_division=".$cod_division." and cod_subdivision=".$cod_subdivision;
			if($this->ccfd01_subdivision->execute($sql)>1){
				$this->set('mensaje', 'EL REGISTRO FUE ELIMINADO EXITOSAMENTE');
//				$this->index();
//				$this->render("index");
			}else{
				$this->set('mensajeError', 'EL REGISTRO NO PUDO SER ELIMINADO');
//				$this->index();
//				$this->render("index");
			}

			echo "<script>";
			  	echo "document.getElementById('cod_subdivision_contable').value='';";
			  	echo "document.getElementById('deno_subdivision_contable').value='';";
			  	echo "document.getElementById('concepto_subdivision_contable').value='';";
			  echo "</script>";

 		}
	}

 }//eliminar





 }//Fin de la clase
?>
