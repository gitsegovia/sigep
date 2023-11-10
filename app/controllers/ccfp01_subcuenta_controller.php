<?php
/*
 * Creado el 17/12/2007
 *
 * Miguelangel Cabrera
 * miguel4ngel@gmail.com
 *
 * 11:59:32 AM
 */
 class Ccfp01subcuentaController extends AppController {
   var $name='ccfp01_subcuenta';
   var $uses=array('ccfd01_tipo','ccfd01_cuenta','ccfd01_subcuenta','usuario','v_ccfd01');
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

    function s($i){
    	/**
    	 * Funcion que permite leer las varibles de session. la cual permite capturar los codigos del usuario
    	 * para ser insertados en todas las tablas.
    	 * */
    	switch ($i){
    		case 1:return $this->Session->read('stipo');break;
    		case 2:return $this->Session->read('scuenta');break;
    		case 3:return $this->Session->read('ssubcuenta');break;
    		case 4:return $this->Session->read('sdivision');break;
    		case 5:return $this->Session->read('ssubdivision');break;
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
			$x="0".$x;
		}
	}
	return $x;

}

function concatena($vector1=null, $nomVar=null){

	if($vector1 != null){

		foreach($vector1 as $x => $y){

			$cod[$x] = $this->zero($x).' - '.$y;

		}
		$this->set($nomVar, $cod);
	}
}
function in(){
	$this->concatena($this->ccfd01_tipo->generateList($this->SQLCA(),' cod_tipo_cuenta ASC', null, '{n}.ccfd01_tipo.cod_tipo_cuenta', '{n}.ccfd01_tipo.denominacion'), 'sel_tipo');
}

function v($tipo,$cuenta,$subcuenta,$division,$subdivision){
   $r='cod_tipo_cuenta='.$tipo.' and cod_cuenta='.$cuenta.' and cod_subcuenta='.$subcuenta.' and cod_division='.$division.' and cod_subdivision='.$subdivision;
return $r;
	}


 function index(){

 	$this->layout="ajax";
	$num=$this->ccfd01_tipo->findCount($this->SQLCA());
 	$this->set('num',$num);
 	$tipo_c = $this->ccfd01_tipo->generateList($this->SQLCA(), 'cod_tipo_cuenta ASC', null, '{n}.ccfd01_tipo.cod_tipo_cuenta', '{n}.ccfd01_tipo.denominacion');
   	if($tipo_c){
   		$this->concatena($tipo_c, 'tipo');
   	}else{
   		$this->set('tipo',array());
   	}
   	$this->set('vector','');
   	$this->data["ccfp01_division"]=null;


 }// fin del index



function boton($var1=null,$var2=null,$var3=null,$var4=null){
	$this->layout="ajax";
	//echo $var1." ".$var2." ".$var3." "." ".$var4;
	$this->set('tipo',$var1);
	$this->set('cuenta',$var2);
}//fin boton

 function select3($select=null,$var=null,$var2=null,$var3=null,$var4=null) {
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
			  		 	echo "vacio";
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
					 $cond = $cond2." and cod_tipo_cuenta=".$var;
					 $lista = $this->ccfd01_cuenta->generateList($cond, 'cod_cuenta ASC', null, '{n}.ccfd01_cuenta.cod_cuenta', '{n}.ccfd01_cuenta.denominacion');
			   		 $this->concatena($lista, 'vector');
			   		 if($lista){
			         $this->concatena($lista, 'vector');
			         }else{
			        	$this->set('vector','vector');
			         }
			 		 }//fin vacio
			  break;

		case 'subcuenta_contable':
		$this->Session->write('cod',$var);
		$this->Session->write('cod2',$var2);
	//	echo $var2;
			  		 if($var2==""){
						$this->set('vector','');
			  		 }else{
					 $this->set('SELECT','div_estadistica_contable');
					 $this->set('codigo','subcuenta_contable');
					 $this->set('seleccion','');
					 $this->set('n',3);
					 $this->set('var',$var);
					 $this->set('no','no');
					 $this->set('var2',$var2);
					 $this->set('var3',$var3);
					 $this->set('var4',$var4);
					 $cond = $cond2." and cod_tipo_cuenta=".$var." and cod_cuenta=".$var2;$lista=null;
					 $lista=  $this->ccfd01_subcuenta->generateList($cond, 'cod_subcuenta ASC', null, '{n}.ccfd01_subcuenta.cod_subcuenta', '{n}.ccfd01_subcuenta.denominacion');
			         if($lista){
			         $this->concatena($lista, 'vector');
			         }else{
			        	$this->set('vector',array());
			         }

				}// fin vacio
			    break;

	  /*   case 'div_estadistica_contable':
					if($var3==""){
					    $this->set('vector','');
					}else{
				  		$this->set('SELECT','direccion');
				  		$this->set('codigo','div_estadistica_contable');
				  		$this->set('seleccion','');
				  		$this->set('no','no');
				  		$this->set('n',4);
				   		$this->set('var',$var);
				   		$this->set('var2',$var2);
				   		$this->set('var3',$var3);
				   		$this->set('var4',$var4);
				  		$cond = $cond2." and cod_tipo_cuenta=".$var." and cod_cuenta=".$var2." and cod_subcuenta=".$var3;
				  		$lista = $this->ccfd01_division->generateList($cond, 'cod_division ASC', null, '{n}.ccfd01_division.cod_division', '{n}.ccfd01_division.denominacion');
		   		  		$this->concatena($lista, 'vector');
						}
			  		break;*/
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




 //Muestra los codigos
 function mostrar4($select=null,$var=null,$var2=null,$var3=null,$var4=null) {
	$this->layout = "ajax";

    if( $var!=null){
    $cond2 = $this->SQLCA();
    if($var2 == "agregar"){
    	echo "<input type='text' name='data[ccfp01_subcuenta][cod_cuenta_contable]' value=''  maxlength='4' id='cod_cuenta_contable' readonly='readonly' style='text-align:center;width:98%' />";
    }else{

	switch($select){
		   case 'tipo':
		  // echo $var;
					  if($var==""){
						  echo "<input type='text' name='data[ccfp01_subcuenta][cod_tipo_cuenta]' value=''  maxlength='4' id='cod_tipo_cuenta' readonly='readonly' style='text-align:center;width:98%' />";
					  }else{
	       			  $this->set('var',$var);
		        	  $this->set('var2',$var2);
			          $this->set('var3',$var3);
			          $this->set('var4',$var4);
//echo "hola";
			  		  $cond = $cond2." and cod_tipo_cuenta=".$var;
				      $a=  $this->ccfd01_tipo->findAll($cond);
		              echo "<input type='text' name='data[ccfp01_subcuenta][cod_tipo_cuenta]' value='".$a[0]['ccfd01_tipo']['cod_tipo_cuenta']."' id='cod_tipo_cuenta' readonly='readonly' style='text-align:center;width:98%' />";
					  echo "<script>";
					  	echo "document.getElementById('cod_cuenta_contable').value='';";
					  	echo "document.getElementById('cod_subcuenta_contable').value='';";
					  	echo "document.getElementById('deno_cuenta_contable').value='';";
					  	echo "document.getElementById('deno_subcuenta_contable').value='';";
					  	echo "document.getElementById('concepto_subcuentacontable').value='';";
					  echo "</script>";
				}
				break;

			case 'contable':
			//echo "contable";
					  if($var2==""){
					      echo "<input type='text' name='data[ccfp01_subcuenta][cod_cuenta_contable]' value='' maxlength='4' id='cod_cuenta_contable' readonly='readonly' style='text-align:center;width:98%' />";
					  }else{
					  $this->set('var',$var);
				      $this->set('var2',$var2);
			  		  $this->set('var3',$var3);
			  		  $this->set('var4',$var4);

				      $cond = $cond2." and cod_tipo_cuenta=".$var." and cod_cuenta=".$var2;
					  $a=  $this->ccfd01_cuenta->findAll($cond);
					 // echo "<br>".$this->AddCeroR2($a[0]['ccfd01_cuenta']['cod_cuenta']);
					 // echo "<br>".$a[0]['ccfd01_cuenta']['cod_cuenta'];
			          echo "<input type='text' name='data[ccfp01_subcuenta][cod_cuenta_contable]' value='".$this->m2($a[0]['ccfd01_cuenta']['cod_cuenta'])."'  id='cod_cuenta_contable' readonly='readonly' style='text-align:center;width:98%' />";
					   echo "<script>";
					  	//echo "document.getElementById('cod_cuenta_contable').value='';";
					  	echo "document.getElementById('cod_subcuenta_contable').value='';";
					  	//echo "document.getElementById('deno_cuenta_contable').value='';";
					  	echo "document.getElementById('deno_subcuenta_contable').value='';";
					  	echo "document.getElementById('concepto_subcuentacontable').value='';";
					  echo "</script>";
					  }
					  break;

			case 'subcuenta_contable':
			//echo "hola";
;			if($var3!='agregar'){
					 if($var3==""){
							echo "<input type='text' name='data[ccfp01_subcuenta][cod_subcuenta_contable]' value='' maxlength='4' id='cod_subcuenta_contable' style='text-align:center;width:98%' />";
					  }else{
				      $this->set('var',$var);
			  		  $this->set('var2',$var2);
			  		  $this->set('var3',$var3);
			  		  $this->set('var4',$var4);

					  $cond = $cond2." and cod_tipo_cuenta=".$var." and cod_cuenta=".$var2." and cod_subcuenta=".$var3;
					  $a=  $this->ccfd01_subcuenta->findAll($cond);
			          echo "<input type='text' name='data[ccfp01_subcuenta][cod_subcuenta_contable]' value='".$this->m3($a[0]['ccfd01_subcuenta']['cod_subcuenta'])."' maxlength='4' id='cod_subcuenta_contable' readonly='readonly' style='text-align:center;width:98%' />";
				}
			}else{
				//echo "hola2";
				echo "<script>;";
					echo "document.getElementById('boton_guarda').disabled=false;";
				echo "</script>";

				echo "<input type='text' name='data[ccfp01_subcuenta][cod_subcuenta_contable]' value='' size='10'  maxlength='4' onKeyPress='return solonumeros(event);' id='cod_subcuenta_contable' style='text-align:center;width:98%' />";
			}

				break;

			/*case 'div_estadistica_contable':
					  if($var4==""){
					 	    echo "<input type='text' name='data[ccfp01_subcuenta][cod_div_contable]' value='' size='10'  maxlength='4' id='cod_div_contable' readonly='readonly' style='text-align:center' />";
					  }else{
					  	  if($var4=="otros"){
					 		    echo "<input type='text' name='data[ccfp01_subcuenta][cod_div_contable]' value='' size='10'  maxlength='4' id='cod_div_contable' style='text-align:center' />";
						  }else{
		 			      $this->set('var',$var);
				  		  $this->set('var2',$var2);
				  		  $this->set('var3',$var3);
				  		  $this->set('var4',$var4);

						  $cond = $cond2." and cod_tipo_cuenta=".$var." and cod_cuenta=".$var2." and cod_subcuenta=".$var3." and cod_division=".$var4;
						  $a=  $this->ccfd01_division->findAll($cond);
				          echo "<input type='text' name='data[ccfp01_subcuenta][cod_div_contable]' value='".$a[0]['ccfd01_division']['cod_division']."' size='10'  maxlength='4' id='cod_div_contable' style='text-align:center' />";
					    }
					  }
					  break;*/
	   }// ------------- fin switch
       }

	}else{
         echo "<input type='text' name='data[ccfp01_cuenta]' size='10' maxlength='4' id='cod_entidad_bancaria' style='text-align:center;width:98%' readonly='readonly' />";
}
}// fin function mostrar4



 //Muestra la denominacion
 function mostrar3($select=null,$var=null,$var2=null,$var3=null,$var4=null) {
 $this->layout = "ajax";
//echo $var;
    if( $var!=null && !empty($var)){
   		$cond2 = $this->SQLCA();
   		if($var2 == "agregar"){
    		echo "<input type='text' name='data[ccfp01_cuenta][deno_cuenta_contable]' value=''  maxlength='100' id='deno_cuenta_contable' style='width:98%' />";
    	}else{

		switch($select){
			case 'tipo':
				 	  if($var==""){
						  echo "<input type='text' name='data[ccfp01_subcuenta][deno_tipo_cuenta]' value=''  maxlength='100' id='deno_tipo_cuenta' readonly='readonly' style='width:98%'  />";
					  }else{
					  $this->set('var',$var);
					  $this->set('var2',$var2);
					  $this->set('var3',$var3);
					  $this->set('var4',$var4);

				      $cond = $cond2." and cod_tipo_cuenta=".$var;
				      $a=  $this->ccfd01_tipo->findAll($cond);
		         	  echo "<input type='text' name='data[ccfp01_subcuenta][deno_tipo_cuenta]' value='".$a[0]['ccfd01_tipo']['denominacion']."' maxlength='100' id='deno_tipo_cuenta' readonly='readonly' style='width:98%'  />";
				 	  }
				      break;

			case 'contable':
					  if($var2==""){
						  echo "<input type='text' name='data[ccfp01_subcuenta][deno_cuenta_contable]' value='' maxlength='100' id='deno_cuenta_contable' readonly='readonly' style='width:98%'  />";
					  }else{
					  $this->set('var',$var);
			          $this->set('var2',$var2);
			  		  $this->set('var3',$var3);
			  		  $this->set('var4',$var4);

					  $cond = $cond2." and cod_tipo_cuenta=".$var." and cod_cuenta=".$var2;
				      $a=  $this->ccfd01_cuenta->findAll($cond);
			          echo "<input type='text' name='data[ccfp01_subcuenta][deno_cuenta_contable]' value='".$a[0]['ccfd01_cuenta']['denominacion']."'  maxlength='100' id='deno_cuenta_contable' readonly='readonly' style='width:98%'  />";
					  }
				      break;

			case 'subcuenta_contable':
			if($var3!='agregar'){
				//echo "hooooooola";
					  if($var3==""){
							echo "<input type='text' name='data[ccfp01_subcuenta][deno_subcuenta_contable]' value='' maxlength='100' id='deno_subcuenta_contable' style='width:98%'  />";
					  }else{
					  $this->set('var',$var);
			  		  $this->set('var2',$var2);
			  		  $this->set('var3',$var3);
					  $this->set('var4',$var4);

				   	  $cond = $cond2." and cod_tipo_cuenta=".$var." and cod_cuenta=".$var2." and cod_subcuenta=".$var3;
			       	  $a=  $this->ccfd01_subcuenta->findAll($cond);
			       	  echo "<input type='text' name='data[ccfp01_subcuenta][deno_subcuenta_contable]' value='".$a[0]['ccfd01_subcuenta']['denominacion']."' maxlength='100' id='deno_subcuenta_contable' readonly='readonly' style='width:98%'  />";
					 //echo "<textarea name='data[ccfp01_cuenta][concepto_subcuentacontable]' value='' row='3' class='inputtext' maxlength='100' id='concepto_subcuentacontable' > ".$a[0]['ccfd01_subcuenta']['concepto']." </textarea>";
					  }
			}else{
				//echo "hoooooola2";
				echo "<input type='text' name='data[ccfp01_subcuenta][deno_subcuenta_contable]' value='' maxlength='100' id='deno_subcuenta_contable' style='width:98%'   />";
			}

			      	  break;

			/*case 'div_estadistica_contable':
					  if($var4==""){
						  echo "<input type='text' name='data[ccfp01_subcuenta][deno_div_contable]' value='' size='49'  maxlength='100' id='deno_div_contable' readonly='readonly' />";
					  }else{
					  	  if($var4=="otros"){
					 		    echo "<input type='text' name='data[ccfp01_subcuenta][deno_div_contable]' value='' size='49'  maxlength='100' id='deno_div_contable' />";
						  }else{
					  $this->set('var',$var);
					  $this->set('var2',$var2);
			  		  $this->set('var3',$var3);
					  $this->set('var4',$var4);

				   	  $cond = $cond2." and cod_tipo_cuenta=".$var." and cod_cuenta=".$var2." and cod_subcuenta=".$var3." and cod_division=".$var4;
				   	  $a=  $this->ccfd01_division->findAll($cond);
		           	  echo "<input type='text' name='data[ccfp01_subcuenta][deno_div_contable]' value='".$a[0]['ccfd01_division']['denominacion']."' size='49'  maxlength='100' id='deno_div_contable' />";
					  }
					  }
			       	  break;*/
		}//fin switch
   		}

	}else{
		  echo "<input type='text' name='data[ccfp01_cuenta] value='' maxlength='100' readonly='readonly' style='width:98%'  />";
	}
 }// fin function mostrar3 denominaciones

function concepto_subcuenta($var=null){
	$this->layout = "ajax";
	//echo $var;
	if($var!="" || $var=="agregar"){
			$cond2 = $this->SQLCA();
		if($var=='agregar'){
				echo "<textarea name='data[ccfp01_subcuenta][concepto_subcuentacontable]' value='' rows='6' class='inputtext' maxlength='100' id='concepto_subcuentacontable' > </textarea>";
				echo "<script>;";
					echo "document.getElementById('boton_guarda').disabled=false;";
				echo "</script>";
		}else if($var!='agregar'){
			 $codi1=$this->Session->read('cod');
			 $codi2=$this->Session->read('cod2');
			$cond2.=" and cod_tipo_cuenta=".$codi1." and cod_cuenta=".$codi2." and cod_subcuenta=".$var;
			 $a=  $this->ccfd01_subcuenta->findAll($cond2);
				echo "<textarea name='data[ccfp01_subcuenta][concepto_subcuentacontable]' value='' rows='6' class='inputtext' maxlength='100' id='concepto_subcuentacontable' > ".$a[0]['ccfd01_subcuenta']['concepto']."</textarea>";
		}
	}else{
		echo "<textarea name='data[ccfp01_subcuenta][concepto_subcuentacontable]' value='' rows='6' class='inputtext' maxlength='100' id='concepto_subcuentacontable' readonly='readonly' > </textarea>";
	}


}//FIN CONCEPTO_SUBCUENTA

function guardar(){
	 $this->layout = "ajax";

	if(!empty($this->data['ccfp01_subcuenta'])){

  		 $cod_tipo_cuenta = $this->data['ccfp01_subcuenta']['cod_tipo_cuenta'];
  		 $cod_cuenta_contable= $this->data['ccfp01_subcuenta']['cod_cuenta_contable'];
  		 $cod_subcuenta_contable= $this->data['ccfp01_subcuenta']['cod_subcuenta_contable'];
  		 $deno_subcuenta_contable= $this->data['ccfp01_subcuenta']['deno_subcuenta_contable'];
		 $concepto_subcuentacontable = $this->data['ccfp01_subcuenta']['concepto_subcuentacontable'];

		$consulta = "SELECT * FROM ccfd01_subcuenta WHERE ".$this->SQLCA()." and cod_tipo_cuenta=".$cod_tipo_cuenta." and cod_cuenta=".$cod_cuenta_contable." and cod_subcuenta=".$cod_subcuenta_contable;
   		if($this->ccfd01_subcuenta->execute($consulta)){
  			$sql="update ccfd01_subcuenta set denominacion='$deno_subcuenta_contable', concepto='$concepto_subcuentacontable' where ".$this->SQLCA()." and cod_tipo_cuenta=".$cod_tipo_cuenta." and cod_cuenta=".$cod_cuenta_contable." and cod_subcuenta=".$cod_subcuenta_contable;
  			$this->ccfd01_subcuenta->execute($sql);
  			$this->set('mensaje','Los datos fuer&oacute;n Actualizados');
  		//	$this->index();
		//	$this->render("index");
				echo "<script>;";
							echo "document.getElementById('cod_subcuenta_contable').value='';";
							echo "document.getElementById('deno_subcuenta_contable').value='';";
							echo "document.getElementById('concepto_subcuentacontable').value='';";
							echo "document.getElementById('boton_guarda').disabled=false;";
							echo "document.getElementById('consultar').disabled=false;";
				echo "</script>";
  		}else{

	  		$sql= "INSERT INTO ccfd01_subcuenta values ('".$this->ss('1')."','".$this->ss('2')."','".$this->ss('3')."','".$this->ss('4')."','".$this->ss('5')."','$cod_tipo_cuenta','$cod_cuenta_contable','$cod_subcuenta_contable','$deno_subcuenta_contable','$concepto_subcuentacontable')";

	  		if($this->ccfd01_subcuenta->execute($sql)>1){
	  			$this->set('mensaje','Los datos fuer&oacute;n insertados correctamente');
	  			//$this->index();
				//$this->render("index");
				echo "<script>;";
				//	echo "document.getElementById('select_1').options[0].text='';";
					//echo "document.getElementById('select_2').options[0].text='';";
					//echo "document.getElementById('select_3').options[0].text='';";
					echo "document.getElementById('cod_subcuenta_contable').value='';";
					echo "document.getElementById('deno_subcuenta_contable').value='';";
					//echo "document.getElementById('cod_cuenta_contable').value='';";
					//echo "document.getElementById('deno_cuenta_contable').value='';";
					//echo "document.getElementById('cod_tipo_cuenta').value='';";
					//echo "document.getElementById('deno_tipo_cuenta').value='';";
					echo "document.getElementById('concepto_subcuentacontable').value='';";
					echo "document.getElementById('boton_guarda').disabled=false;";
					echo "document.getElementById('consultar').disabled=false;";
				echo "</script>";
				//$this->index();
				//$this->render("index");

	  		}else{
	  			$this->set('mensajeError','Los datos no pudier&oacute;n ser insertados');
	  			//$this->index();
				//$this->render("index");
	  		}
  		}
	}else{
		$this->set('mensajeError','debe ingresar todos los datos');
	}

}//FIN GUARDAR




function consultar($pagina=null) {
	$this->layout="ajax";
	$this->set('entidad_federal', $this->Session->read('entidad_federal'));
	//echo "coooonsultar";
	$consulta1=$this->SQLCA();

	if(isset($pagina)){
		$Tfilas=$this->ccfd01_subcuenta->findCount();
        if($Tfilas!=0){
        	$data=$this->ccfd01_subcuenta->findAll($consulta1,null,"cod_tipo_cuenta,cod_cuenta, cod_subcuenta ASC",1,$pagina,null);

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
		$Tfilas=$this->ccfd01_subcuenta->findCount();

        if($Tfilas!=0){
        	$data=$this->ccfd01_subcuenta->findAll($consulta1,null,"cod_tipo_cuenta,cod_cuenta, cod_subcuenta ASC",1,$pagina,null);
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

	$this->set('cod_tipo_cuenta', $data[0]['ccfd01_subcuenta']['cod_tipo_cuenta']);
	$this->set('cod_cuenta', $data[0]['ccfd01_subcuenta']['cod_cuenta']);
	$this->set('cod_subcuenta', $data[0]['ccfd01_subcuenta']['cod_subcuenta']);

   /* $this->set('cod_division', $data[0]['ccfd01_division']['cod_division']);
    $this->set('deno_div_estadistica', $data[0]['ccfd01_division']['denominacion']);
    $this->set('concepto_div_estadistica', $data[0]['ccfd01_division']['concepto']);
*/
	//El siguiente bloque es para obtener la denominacion del codigo del tipo de cuenta
	/*$tipo_cuenta=$this->ccfd01_tipo->findAll($consulta1.' and cod_tipo_cuenta='.$data[0]['ccfd01_subcuenta']['cod_tipo_cuenta']);
	$cuenta_contable=$this->ccfd01_cuenta->findAll($consulta1.' and cod_cuenta='.$data[0]['ccfd01_subcuenta']['cod_cuenta']);
	$cuenta_subcontable=$this->ccfd01_subcuenta->findAll($consulta1.' and cod_subcuenta='.$data[0]['ccfd01_subcuenta']['cod_subcuenta']);*/

	$tipo_cuenta=$this->ccfd01_tipo->findAll($consulta1.' and cod_tipo_cuenta='.$data[0]['ccfd01_subcuenta']['cod_tipo_cuenta']);
	//$cuenta_contable=$this->ccfd01_cuenta->findAll($consulta1.' and cod_cuenta='.$data[0]['ccfd01_subcuenta']['cod_cuenta']);
	$cuenta_contable=$this->ccfd01_cuenta->findAll($consulta1.' and cod_tipo_cuenta='.$data[0]['ccfd01_subcuenta']['cod_tipo_cuenta'].' and cod_cuenta='.$data[0]['ccfd01_subcuenta']['cod_cuenta']);
	$cuenta_subcontable=$this->ccfd01_subcuenta->findAll($consulta1.' and cod_tipo_cuenta='.$data[0]['ccfd01_subcuenta']['cod_tipo_cuenta'].' and cod_cuenta='.$data[0]['ccfd01_subcuenta']['cod_cuenta'].' and cod_subcuenta='.$data[0]['ccfd01_subcuenta']['cod_subcuenta']);

	$this->set('deno_tipo_cuenta',$tipo_cuenta[0]['ccfd01_tipo']['denominacion']);
	$this->set('deno_contable',$cuenta_contable[0]['ccfd01_cuenta']['denominacion']);
	$this->set('deno_subcontable',$cuenta_subcontable[0]['ccfd01_subcuenta']['denominacion']);

	//El siguiente bloque es para obtener la denominacion del codigo del tipo de cuenta
	/*$this->set('concepto_tipo_cuenta',$tipo_cuenta[0]['ccfd01_tipo']['concepto']);
	$this->set('concepto_contable',$cuenta_contable[0]['ccfd01_cuenta']['concepto']);*/
	$this->set('concepto_subcontable',$cuenta_subcontable[0]['ccfd01_subcuenta']['concepto']);

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



function eliminar($cod_tipo_2=null, $cod_cuenta_contable_2=null, $cod_subcuenta_contable_2=null, $pagina_regreso=null){
 		$this->layout="ajax";

if(isset($cod_tipo_2) && isset($cod_cuenta_contable_2) && isset($cod_subcuenta_contable_2)){

	    $row_cuenta=$this->ccfd01_cuenta->execute("SELECT cod_subcuenta FROM ccfd02 WHERE ".$this->SQLCA()." and cod_tipo_cuenta= ".$cod_tipo_2." and cod_cuenta=".$cod_cuenta_contable_2." and cod_subcuenta=".$cod_subcuenta_contable_2." LIMIT 1;");
		if(!empty($row_cuenta)){
			$this->set('mensajeError', 'Lo siento no se puede eliminar la subcuenta, debido a que esta siendo utilizada');
				$this->consultar($pagina_regreso);
				$this->render("consultar");
		}else{

		$sql = "DELETE FROM ccfd01_subcuenta WHERE ".$this->SQLCA()." and cod_tipo_cuenta= ".$cod_tipo_2." and cod_cuenta=".$cod_cuenta_contable_2." and cod_subcuenta=".$cod_subcuenta_contable_2;
		if($this->ccfd01_subcuenta->execute($sql)>1){
			$this->set('mensaje', 'EL REGISTRO FUE ELIMINADO EXITOSAMENTE');
			$Tfilas=$this->ccfd01_subcuenta->findCount();
				if($Tfilas!=0){
				$this->consultar($pagina_regreso);
				$this->render("consultar");
				}else{
					$this->index();
					$this->render("index");
					echo "";
				}
			//$this->consultar($pagina_regreso);
			//$this->render("consultar");
		}else{
			$this->set('mensajeError', 'EL REGISTRO NO PUDO SER ELIMINADO');
			$this->consultar($pagina_regreso);
			$this->render("consultar");
		}
		}
}else{
	$cod_tipo_cuenta = $this->data['ccfp01_subcuenta']['cod_tipo_cuenta'];
  	$cod_cuenta_contable= $this->data['ccfp01_subcuenta']['cod_cuenta_contable'];
  	$cod_subcuenta_contable= $this->data['ccfp01_subcuenta']['cod_subcuenta_contable'];

	    $row_cuenta=$this->ccfd01_cuenta->execute("SELECT cod_subcuenta FROM ccfd02 WHERE ".$this->SQLCA()." and cod_tipo_cuenta= ".$cod_tipo_cuenta." and cod_cuenta=".$cod_cuenta_contable." and cod_subcuenta=".$cod_subcuenta_contable." LIMIT 1;");
		if(!empty($row_cuenta)){
			$this->set('mensajeError', 'Lo siento no se puede eliminar la subcuenta, debido a que esta siendo utilizada');
		}else{

	$sql = "DELETE FROM ccfd01_subcuenta WHERE ".$this->SQLCA()." and cod_tipo_cuenta= ".$cod_tipo_cuenta." and cod_cuenta=".$cod_cuenta_contable." and cod_subcuenta=".$cod_subcuenta_contable;

	if($this->ccfd01_subcuenta->execute($sql)>1){
			$this->set('mensaje', 'EL REGISTRO FUE ELIMINADO EXITOSAMENTE');
//			$this->index();
//			$this->render("index");
	}else{
			$this->set('mensajeError', 'EL REGISTRO NO PUDO SER ELIMINADO');
//			$this->index();
//			$this->render("index");
	}

	 echo "<script>";
	  	echo "document.getElementById('cod_subcuenta_contable').value='';";
	  	echo "document.getElementById('deno_subcuenta_contable').value='';";
	  	echo "document.getElementById('concepto_subcuentacontable').value='';";
	  echo "</script>";
		}
}

 }//eliminar


function modificar1(){
	$this->layout="ajax";
	$this->set('mensaje','Puede proceder a Modificar los datos');

	echo "<script>";
		echo "document.getElementById('deno_subcuenta_contable').readOnly=false;";
		echo "document.getElementById('concepto_subcuentacontable').readOnly=false;";
	echo "</script>";
}




 function modificar($cod_tipo=null, $cod_contable=null, $cod_subcuenta_contable=null, $pagina_regreso=null){
 	$this->layout="ajax";

 	$cond1 = $this->SQLCA();
if(isset($cod_tipo) && isset($cod_contable) && isset($cod_subcuenta_contable)){
 	$sql = "SELECT * FROM ccfd01_subcuenta WHERE ".$cond1." and cod_tipo_cuenta=".$cod_tipo." and cod_cuenta=".$cod_contable." and cod_subcuenta=".$cod_subcuenta_contable;
 	$sql2 = $cond1." and cod_tipo_cuenta=".$cod_tipo." and cod_cuenta=".$cod_contable." and cod_subcuenta=".$cod_subcuenta_contable;
 	//$data = $this->ccfd01_division->execute($sql);
 	$data = $this->ccfd01_subcuenta->findAll($sql2);//quedamos en la parte de modifica se debe revisar
 	if($data){
 		$this->set('mensaje','Puede proceder a Modificar los datos');
		$tipo_cuenta=$this->ccfd01_tipo->findAll($cond1.' and cod_tipo_cuenta='.$data[0]['ccfd01_subcuenta']['cod_tipo_cuenta'], null, null, 1);
		$cuenta_contable=$this->ccfd01_cuenta->findAll($cond1.' and cod_tipo_cuenta='.$data[0]['ccfd01_subcuenta']['cod_tipo_cuenta'].' and cod_cuenta='.$data[0]['ccfd01_subcuenta']['cod_cuenta'], null, null, 1);
		$cuenta_subcontable=$this->ccfd01_subcuenta->findAll($cond1.' and cod_tipo_cuenta='.$data[0]['ccfd01_subcuenta']['cod_tipo_cuenta'].' and cod_cuenta='.$data[0]['ccfd01_subcuenta']['cod_cuenta'].' and cod_subcuenta='.$data[0]['ccfd01_subcuenta']['cod_subcuenta'], null, null, 1);

		$this->set('cod_tipo_cuenta', $data[0]['ccfd01_subcuenta']['cod_tipo_cuenta']);
		$this->set('cod_cuenta', $data[0]['ccfd01_subcuenta']['cod_cuenta']);
		$this->set('cod_subcuenta', $data[0]['ccfd01_subcuenta']['cod_subcuenta']);

		$this->set('deno_tipo_cuenta',$tipo_cuenta[0]['ccfd01_tipo']['denominacion']);
		$this->set('deno_contable',$cuenta_contable[0]['ccfd01_cuenta']['denominacion']);
		$this->set('deno_subcontable',$cuenta_subcontable[0]['ccfd01_subcuenta']['denominacion']);

		$this->set('concepto_tipo_cuenta',$tipo_cuenta[0]['ccfd01_tipo']['concepto']);
		$this->set('concepto_contable',$cuenta_contable[0]['ccfd01_cuenta']['concepto']);
		$this->set('concepto_subcontable',$cuenta_subcontable[0]['ccfd01_subcuenta']['concepto']);

		$this->set('pagina_actual', $pagina_regreso+1);

 	}else{
 		$this->set('mensajeError','Error no se encontraron los datos a Modificar');
 	}

 }else{
 	//pr($this->data);
 		 $cod_tipo = $this->data['ccfp01_subcuenta']['cod_tipo_cuenta'];
  		 $cod_contable= $this->data['ccfp01_subcuenta']['cod_cuenta_contable'];
  		 $cod_subcuenta_contable= $this->data['ccfp01_subcuenta']['cod_subcuenta_contable'];


		$sql = "SELECT * FROM ccfd01_subcuenta WHERE ".$cond1." and cod_tipo_cuenta=".$cod_tipo." and cod_cuenta=".$cod_contable." and cod_subcuenta=".$cod_subcuenta_contable;
 	$sql2 = $cond1." and cod_tipo_cuenta=".$cod_tipo." and cod_cuenta=".$cod_contable." and cod_subcuenta=".$cod_subcuenta_contable;
 	//$data = $this->ccfd01_division->execute($sql);
 	$data = $this->ccfd01_subcuenta->findAll($sql2);//quedamos en la parte de modifica se debe revisar
 	if($data){
 		$this->set('mensaje','Puede proceder a Modificar los datos');
		$tipo_cuenta=$this->ccfd01_tipo->findAll($cond1.' and cod_tipo_cuenta='.$data[0]['ccfd01_subcuenta']['cod_tipo_cuenta'], null, null, 1);
		$cuenta_contable=$this->ccfd01_cuenta->findAll($cond1.' and cod_tipo_cuenta='.$data[0]['ccfd01_subcuenta']['cod_tipo_cuenta'].' and cod_cuenta='.$data[0]['ccfd01_subcuenta']['cod_cuenta'], null, null, 1);
		$cuenta_subcontable=$this->ccfd01_subcuenta->findAll($cond1.' and cod_tipo_cuenta='.$data[0]['ccfd01_subcuenta']['cod_tipo_cuenta'].' and cod_cuenta='.$data[0]['ccfd01_subcuenta']['cod_cuenta'].' and cod_subcuenta='.$data[0]['ccfd01_subcuenta']['cod_subcuenta'], null, null, 1);

		$this->set('cod_tipo_cuenta', $data[0]['ccfd01_subcuenta']['cod_tipo_cuenta']);
		$this->set('cod_cuenta', $data[0]['ccfd01_subcuenta']['cod_cuenta']);
		$this->set('cod_subcuenta', $data[0]['ccfd01_subcuenta']['cod_subcuenta']);

		$this->set('deno_tipo_cuenta',$tipo_cuenta[0]['ccfd01_tipo']['denominacion']);
		$this->set('deno_contable',$cuenta_contable[0]['ccfd01_cuenta']['denominacion']);
		$this->set('deno_subcontable',$cuenta_subcontable[0]['ccfd01_subcuenta']['denominacion']);

		$this->set('concepto_tipo_cuenta',$tipo_cuenta[0]['ccfd01_tipo']['concepto']);
		$this->set('concepto_contable',$cuenta_contable[0]['ccfd01_cuenta']['concepto']);
		$this->set('concepto_subcontable',$cuenta_subcontable[0]['ccfd01_subcuenta']['concepto']);

		$this->set('pagina_actual','index');

 	}else{
 		$this->set('mensajeError','Error no se encontraron los datos a Modificar');
 	}


 }//fin isset


 }//fin modificar


 function guardar_modificar($cod_tipo_2=null, $cod_cuenta_contable_2=null, $cod_subcuenta_contable_2=null, $pagina_regreso=null){
 	$this->layout="ajax";



 	if(!empty($this->data['ccfp01_subcuenta'])){
		 $cod_tipo = $this->data['ccfp01_subcuenta']['cod_tipo_cuenta'];
 		 $cod_cuenta_contable = $this->data['ccfp01_subcuenta']['cod_cuenta_contable'];
 		 $cod_subcuenta_contable = $this->data['ccfp01_subcuenta']['cod_subcuenta_contable'];
 		//$cod_sub_contable = $this->data['ccfd01_subcuenta']['cod_div_contable'];// el que me llega por el input no por el select_4
 		 $deno_subcontable = $this->data['ccfp01_subcuenta']['deno_subcuenta_contable'];
 		 $concepto_subcontable = $this->data['ccfp01_subcuenta']['concepto_subcuentacontable'];

  	    $sql="update ccfd01_subcuenta set cod_subcuenta='$cod_subcuenta_contable_2', denominacion='$deno_subcontable', concepto='$concepto_subcontable' where ".$this->SQLCA()." and cod_tipo_cuenta='$cod_tipo_2' and cod_cuenta='$cod_cuenta_contable_2' and cod_subcuenta='$cod_subcuenta_contable_2'";
if($pagina_regreso!='index'){
		if($this->ccfd01_subcuenta->execute($sql)>1){
			$this->set('mensaje','Los datos fuer&oacute;n modificados exitosamente');
			$this->consultar($pagina_regreso);
			$this->render("consultar");
		}else{
			$this->set('mensajeError','Los datos no fuer&oacute;n modificados');
			$this->consultar($pagina_regreso);
			$this->render("consultar");
		}
 }else{
		if($this->ccfd01_subcuenta->execute($sql)>1){
			$this->set('mensaje','Los datos fuer&oacute;n modificados exitosamente');
			$this->index();
			$this->render("index");
		}else{
			$this->set('mensajeError','Los datos no fuer&oacute;n modificados');
			$this->index();
			$this->render("index");
		}
}//fin else actualizacion
 	}else{
		echo "no llegan los datos completos";
 	}
 }//guardar modificar






}
?>
