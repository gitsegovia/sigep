<?php
/*
 * Fecha: 15/06/2007
 *
 * Por Luis Alfredo Diaz Jaramillo
 *
 * Herramienta utilizada: easyEclipse
 * cake
 */

 class Cfpp01Controller extends AppController{


 	var $uses = array('v_clasificador_partidas_ejercicio','cfpd01_ano_grupo', 'cfpd01_ano_partida', 'cfpd01_ano_generica', 'cfpd01_ano_especifica', 'cfpd01_ano_sub_espec', 'cfpd01_ano_auxiliar', 'cfpd01_grupo', 'cfpd01_partida', 'cfpd01_generica', 'cfpd01_especifica', 'cfpd01_sub_espec', 'cfpd01_auxiliar', 'cfpd01_formulacion','clasificador','ccfd01_cuenta','ccfd01_subcuenta','ccfd01_division','ccfd01_subdivision');
 	var $helpers = array('Html','Ajax','Javascript', 'Sisap');


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
 echo'<script>
							 document.getElementById("valida_codigo").innerHTML = "";
							 document.getElementById("valida_codigo").style.display = "none";
							 if(document.getElementById("registro")){document.getElementById("registro").style.visibility = "hidden";}
                             if(document.getElementById("nomina")){document.getElementById("nomina").style.visibility = "hidden";}
                             if(document.getElementById("dependencia")){document.getElementById("dependencia").style.visibility = "hidden";}
                             if(document.getElementById("sistema_general")){document.getElementById("sistema_general").style.visibility = "hidden";}
                          </script>';
}



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






function add_c_c($var){

		if($var<=9 && strlen($var)==1){$codigo = '0'.$var;}else{$codigo = '.'.$var;}

		return $codigo;

}//fin AddCero




function PrintAddCero($nomVar,$var=null){
/*if($var!='otros'){
$tam = strlen($var); //tamaño de la cadena
$n = $tam - 1;
$codigo = substr($var, $n);}
if($var<10 && $var!='otros' && $codigo!=0 && $codigo!=o''){$var="0".$var;
}else{$var=$var;}
$this->set($nomVar,$var);*/
}//fin AddCero





function index(){
    $this->layout = "ajax";
	 $this->set('entidad_federal', $this->Session->read('entidad_federal'));
	 //A partir de aqui esta el codiog para bajar el año presupuestario por defecto
    $cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst;

	$year = $this->cfpd01_formulacion->findAll($condicion, null, 'ano_formular ASC', null);
	$dato = null;
	foreach($year as $year){
		$dato = $year['cfpd01_formulacion']['ano_formular'];
	}

	if(!empty($dato)){

		$this->set('year', $dato);

	}else{
		$this->set('year', '');
	}

	          for($minCount = 2007; $minCount < 2030; $minCount++) {
			    $anos[sprintf('%02d', $minCount)] = sprintf('%02d', $minCount);
			    $this->set('anos',$anos);
			    $this->set('ano_formulacion',$dato);
		       }


// fin del codigo
 }



function clasificador($ejercicio=null){
    $this->layout = "ajax";
	$grupo="";


	if($ejercicio==null){
				$ejercicio="";
				$this->set('ejercicio', $this->data['cfpp01']['ano']);
				$ejercicio = $this->data['cfpp01']['ano'];

	}else{$this->set('ejercicio', $ejercicio);}


	$grupo = $this->cfpd01_ano_grupo->generateList('where ejercicio ='.$ejercicio.'', 'cod_grupo ASC', null, '{n}.cfpd01_ano_grupo.cod_grupo', '{n}.cfpd01_ano_grupo.denominacion');
	$this->concatena_sin_cero($grupo, 'grupo');
	//$this->set('grupo', $grupo);
}


function vaciar(){
    $this->layout = "ajax";
}


function solicitud_traspaso(){
    $this->layout = "ajax";
}




function selec_grupo($ejercicio=null, $var=null){

   $this->layout = "ajax";
   $this->set('ejercicio', $ejercicio);

   if($this->data['cfpp01']['codigo']){$var = $this->data['cfpp01']['codigo'];   $this->set('opcion', $var);

   }else{ $this->set('opcion', $var);  }


   $lista =  $this->cfpd01_ano_grupo->generateList('where ejercicio= '.$ejercicio.'', 'cod_grupo ASC', null, '{n}.cfpd01_ano_grupo.cod_grupo', '{n}.cfpd01_ano_grupo.denominacion');
   $this->concatena($lista, 'grupo');
 }








function selec_partida($ejercicio=null, $var=null, $aux=null){
	$this->layout = "ajax";
	$this->set('ejercicio', $ejercicio);


if($this->data['cfpp01']['codigo'] &&  $var!=null){ $this->set('selecion', $this->data['cfpp01']['codigo']); }
if($var==null){ $var = $this->data['cfpp01']['codigo']; }
if($aux!=null){  $this->set('selecion', $aux);}


$this->set('opcion1', $var);
if($var!=null && $var!='otros'){
	//$this->AddCero('partida', $this->cfpd01_ano_partida->generateList('where ejercicio= '.$ejercicio.' and cod_grupo =  '.$var.' ', ' cod_partida ASC', null, '{n}.cfpd01_ano_partida.cod_partida', '{n}.cfpd01_ano_partida.cod_partida'));

	$partida = $this->cfpd01_ano_partida->generateList('where ejercicio= '.$ejercicio.' and cod_grupo =  '.$var.' ', ' cod_partida ASC', null, '{n}.cfpd01_ano_partida.cod_partida', '{n}.cfpd01_ano_partida.denominacion');
	$this->concatena($partida, 'partida');

}else{   $this->AddCero('partida', '');}

}








function selec_generica($ejercicio=null, $var1=null, $var2=null , $aux=null){
    $this->layout = "ajax";
	$this->set('ejercicio', $ejercicio);

			if($this->data['cfpp01']['codigo']  &&  $var2!=null){ $this->set('selecion', $this->data['cfpp01']['codigo']); }
            if($var2==null){ $var2 = $this->data['cfpp01']['codigo'];}
			if($aux!=null){  $this->set('selecion', $aux);}

	$this->set('opcion1', $var1);
	$this->set('opcion2', $var2);

	if($var2!=null && $var2!='otros'){

	//$this->AddCero('generica', $this->cfpd01_ano_generica->generateList('where ejercicio= '.$ejercicio.' and  cod_grupo =  '.$var1.'  and cod_partida = '.$var2.'', ' cod_generica ASC', null, '{n}.cfpd01_ano_generica.cod_generica', '{n}.cfpd01_ano_generica.cod_generica'));
	$generica = $this->cfpd01_ano_generica->generateList('where ejercicio= '.$ejercicio.' and  cod_grupo =  '.$var1.'  and cod_partida = '.$var2.'', ' cod_generica ASC', null, '{n}.cfpd01_ano_generica.cod_generica', '{n}.cfpd01_ano_generica.denominacion');
	$this->concatena($generica, 'generica');

	}else{   $this->AddCero('generica', ''); }

}









function selec_especifica($ejercicio=null, $var1=null, $var2=null, $var3=null , $aux=null){
	$this->layout = "ajax";
	$this->set('ejercicio', $ejercicio);


if($this->data['cfpp01']['codigo']  &&  $var3!=null){ $this->set('selecion', $this->data['cfpp01']['codigo']); }
if($var3==null){ $var3 = $this->data['cfpp01']['codigo'];}
if($aux!=null){  $this->set('selecion', $aux);}


	$this->set('opcion1', $var1);
	$this->set('opcion2', $var2);
	$this->set('opcion3', $var3);

	if($var3!=null && $var3!='otros'){

    //$this->AddCero('especifica', $this->cfpd01_ano_especifica->generateList('where  ejercicio= '.$ejercicio.' and  cod_grupo =  '.$var1.'  and cod_partida = '.$var2.' and cod_generica = '.$var3.'', ' cod_especifica ASC', null, '{n}.cfpd01_ano_especifica.cod_especifica', '{n}.cfpd01_ano_especifica.cod_especifica'));
    $especifica = $this->cfpd01_ano_especifica->generateList('where  ejercicio= '.$ejercicio.' and  cod_grupo =  '.$var1.'  and cod_partida = '.$var2.' and cod_generica = '.$var3.'', ' cod_especifica ASC', null, '{n}.cfpd01_ano_especifica.cod_especifica', '{n}.cfpd01_ano_especifica.denominacion');
	$this->concatena($especifica, 'especifica');

	}else{   $this->AddCero('especifica', ''); }

}










function selec_sub_especifica($ejercicio=null, $var1=null, $var2=null, $var3=null, $var4=null, $aux=null) {
	$this->layout = "ajax";
	$this->set('ejercicio', $ejercicio);


if($this->data['cfpp01']['codigo']  &&  $var4!=null){ $this->set('selecion', $this->data['cfpp01']['codigo']); }
if($var4==null){ $var4 = $this->data['cfpp01']['codigo'];}
if($aux!=null){  $this->set('selecion', $aux);}


	$this->set('opcion1', $var1);
	$this->set('opcion2', $var2);
	$this->set('opcion3', $var3);
	$this->set('opcion4', $var4);

	if($var4!=null && $var4!='otros'){

	//$this->AddCero('subespecifica', $this->cfpd01_ano_sub_espec->generateList('where ejercicio= '.$ejercicio.' and  cod_grupo =  '.$var1.'  and cod_partida = '.$var2.' and cod_generica = '.$var3.' and cod_especifica = '.$var4.'', ' cod_sub_espec ASC', null, '{n}.cfpd01_ano_sub_espec.cod_sub_espec', '{n}.cfpd01_ano_sub_espec.cod_sub_espec'));
	$subespecifica = $this->cfpd01_ano_sub_espec->generateList('where ejercicio= '.$ejercicio.' and  cod_grupo =  '.$var1.'  and cod_partida = '.$var2.' and cod_generica = '.$var3.' and cod_especifica = '.$var4.'', ' cod_sub_espec ASC', null, '{n}.cfpd01_ano_sub_espec.cod_sub_espec', '{n}.cfpd01_ano_sub_espec.denominacion');
	$this->concatena($subespecifica, 'subespecifica');

	}else{   $this->AddCero('subespecifica', ''); }
}









function selec_auxiliar($ejercicio=null, $var1=null, $var2=null, $var3=null, $var4=null, $var5=null, $aux=null){
	$this->layout = "ajax";
	$this->set('ejercicio', $ejercicio);


if($this->data['cfpp01']['codigo']  &&  $var5!=null){ $this->set('selecion', $this->data['cfpp01']['codigo']); }
if($var5==null){ $var5 = $this->data['cfpp01']['codigo'];}
if($aux!=null){  $this->set('selecion', $aux);}

	$this->set('opcion1', $var1);
	$this->set('opcion2', $var2);
	$this->set('opcion3', $var3);
	$this->set('opcion4', $var4);
	$this->set('opcion5', $var5);

	if($var5!=null && $var5!='otros'){

	//$this->AddCero('auxiliar', $this->cfpd01_ano_auxiliar->generateList('where ejercicio= '.$ejercicio.' and  cod_grupo =  '.$var1.'  and cod_partida = '.$var2.' and cod_generica = '.$var3.' and cod_especifica = '.$var4.' and cod_sub_espec = '.$var5.'', ' cod_auxiliar ASC', null, '{n}.cfpd01_ano_auxiliar.cod_auxiliar', '{n}.cfpd01_ano_auxiliar.cod_auxiliar'));
	$auxiliar = $this->cfpd01_ano_auxiliar->generateList('where ejercicio= '.$ejercicio.' and  cod_grupo =  '.$var1.'  and cod_partida = '.$var2.' and cod_generica = '.$var3.' and cod_especifica = '.$var4.' and cod_sub_espec = '.$var5.'', ' cod_auxiliar ASC', null, '{n}.cfpd01_ano_auxiliar.cod_auxiliar', '{n}.cfpd01_ano_auxiliar.denominacion');
	$this->concatena_cuatro_digitos($auxiliar, 'auxiliar');

	}else{   $this->AddCero('auxiliar', '');}

}








function principal($ejercicio=null, $var1=null, $var2=null, $var3=null, $var4=null, $var5=null, $var6=null){

   	$this->layout = "ajax";
	$this->set('opcion1', $var1);
	$this->set('opcion2', $var2);
	$this->set('opcion3', $var3);
	$this->set('opcion4', $var4);
    $this->set('opcion6', $var6);
	$this->set('ejercicio', $ejercicio);


	$action='';
	$tabla = '';
	$sql_3 = '';

	if($var1=='otros'){$action=$var1; }
	if($var2=='otros'){$action=$var2; }
	if($var3=='otros'){$action=$var3; }
	if($var4=='otros'){$action=$var4; }
	if($var5=='otros'){$action=$var5; }
	if($var6=='otros'){$action=$var6; }

	$sql_2 = " ejercicio = ".$ejercicio." and ";


	if($var1!=null){ $sql_2 .=  ' cod_grupo =  '.$var1.'  ';                    $tabla='cfpd01_ano_grupo';    																							}
	if($var2!=null){ $sql_2 .= 'and cod_partida = '.$var2.'  ';             $tabla='cfpd01_ano_partida';     			    $sql_3 =  ' cod_grupo =  '.$var1.'  ';				}
	if($var3!=null){ $sql_2 .= 'and cod_generica = '.$var3.'  ';          $tabla='cfpd01_ano_generica';   			$sql_3 .= 'and cod_partida = '.$var2.'  ';			}
	if($var4!=null){ $sql_2 .= 'and cod_especifica = '.$var4.'  ';        $tabla='cfpd01_ano_especifica';            $sql_3 .= 'and cod_generica = '.$var3.'  ';		}



	if($var5!=null){

//	             if($var5==0){

							  $sql_2.= 'and cod_sub_espec = '.$var5.'  ';
							  $tabla='cfpd01_ano_sub_espec';
							  $sql_3 .= 'and cod_especifica = '.$var4.'  ';
							  $this->set('opcion5', $var5);


//	        }else if($var5==0 && $var5!='otros'){

//							  $sql_2 .= 'and cod_especifica = '.$var4.'  ';
//							  $tabla='cfpd01_ano_especifica';
//							  $sql_3 .= 'and cod_generica = '.$var3.'  ';
//							  $this->set('opcion5', 'vacio');

//			}else{$this->set('opcion5', $var5);}

	}else{$this->set('opcion5', $var5);}




	if($var6!=null){ $sql_2 .='and cod_auxiliar = '.$var6.'  ';               $tabla='cfpd01_ano_auxiliar';                  $sql_3.= 'and cod_sub_espec = '.$var5.' '; 	}

	$this->set('tabla', $tabla);


if($var1!=null && $action!='otros'){

       $sql_re = $sql_2;
	  $data = $this->$tabla->findAll($sql_re, null, null, null);

	  $this->set('datos_cod_cfpp01', $data);

}else if($var1!=null){

	  $sql_re = $sql_3;
	  $data = $this->$tabla->findAll($sql_re, null, null, null);

	  $this->set('datos_cod_cfpp01', $data);

}//fin else






}//FIN FUNCTION







 function guardar($ejercicio=null, $tabla=null, $var1=null, $var2=null, $var3=null, $var4=null, $var5=null, $var6=null){

 	$this->layout = "ajax";
	$this->set('ejercicio', $ejercicio);

	$cp = $this->Session->read('SScodpresi');
	$ce = $this->Session->read('SScodentidad');
	$cti = $this->Session->read('SScodtipoinst');
	$ci = $this->Session->read('SScodinst');
	$cd = $this->Session->read('SScoddep');
	$condicion = "cod_presi = ".$cp." and cod_entidad = ".$ce." and cod_tipo_inst = ".$cti." and cod_inst = ".$ci." and cod_dep=1";

    if($var1==null){
    	$this->data['cfpp01']['cod_grupo'] = $this->data['cfpp01']['codigo'];
    	$var1 = $this->data['cfpp01']['codigo'];
	}else if($var2==null){
		$this->data['cfpp01']['cod_partida'] =$this->data['cfpp01']['codigo'];
		$var2 = $this->data['cfpp01']['codigo'];
	} else if($var3==null){
		$this->data['cfpd01']['cod_generica'] = $this->data['cfpp01']['codigo'];
		$var3 = $this->data['cfpp01']['codigo'];
	}else if($var4==null){
		$this->data['cfpd01']['cod_especifica'] = $this-> data['cfpp01']['codigo'];
		$var4 = $this->data['cfpp01']['codigo'];
	}else if($var5==null){
		$this->data['cfpd01']['cod_sub_espec'] = $this->data['cfpp01']['codigo'];
		$var5 = $this->data['cfpp01']['codigo'];
	}else if($var6==null){
		$this->data['cfpd01']['cod_auxiliar'] = $this->data['cfpp01']['codigo'];
		$var6 = $this->data['cfpp01']['codigo'];
	}


	 $descripcion = $this->data['cfpp01']['descripcion'];
	 $concepto = $this->data['cfpp01']['concepto'];

    $codigos = "";
	$values = "";



	if($var1!=null){
	        $codigos .= "cod_grupo, ";
			$values .=  " '".$var1."',  " ;
			$tabla='cfpd01_ano_1_grupo';

			if($var1==3){// Insercion en la tabla de cuenta contable, la cuenta 301.
				$cod_tipo_cuenta = 2;
				$cod_grupo = $var1."01";
				$condicion_2 = $condicion." and cod_tipo_cuenta='$cod_tipo_cuenta' and cod_cuenta='$cod_grupo'";
				$count = $this->ccfd01_cuenta->findCount($condicion_2);
				if($count==0){
					$sql = "INSERT INTO ccfd01_cuenta VALUES ('$cp', '$ce', '$cti', '$ci', 1, '$cod_tipo_cuenta', '$cod_grupo', '$descripcion', '$concepto');";
					if($this->ccfd01_cuenta->execute($sql) > 1){
						//echo "<br />La cuenta del grupo se creo de manera correcta";
					}else{
						//echo "<br />La cuenta del grupo no pudo ser creada";
					}

					//CREACION DE LAS CUENTAS 1 - 122 CONTRAPARTE DE LAS 2 - 301 EN EL PLAN DE CUENTAS CONTABLES.
					$cod_tipo_cuenta = 1;
					$cod_grupo = 122;//$var1."01";
					$sql = "INSERT INTO ccfd01_cuenta VALUES ('$cp', '$ce', '$cti', '$ci', 1, '$cod_tipo_cuenta', '$cod_grupo', '$descripcion', '$concepto');";
					if($this->ccfd01_cuenta->execute($sql) > 1){
						//echo "<br />La cuenta del grupo se creo de manera correcta";
					}else{
						//echo "<br />La cuenta del grupo no pudo ser creada";
					}
				}else{
					//echo "<br />No se creo la cuenta del grupo, ya existe en el plan de cuentas";
				}
			}
	}

	if($var2!=null){
	         $codigos .= "cod_partida, ";
			$values .=  " '".$var2."',  ";
	        $tabla='cfpd01_ano_2_partida';

			if($var1==3){// Insercion en la tabla de subcuenta contable para la cuenta 301.
		        $cod_tipo_cuenta = 2;
				$cod_cuenta = $var1."01";
				$cod_subcuenta = $var2;
				$condicion_2 = $condicion." and cod_tipo_cuenta='$cod_tipo_cuenta' and cod_cuenta='$cod_cuenta' and cod_subcuenta='$cod_subcuenta'";
				$count = $this->ccfd01_subcuenta->findCount($condicion_2);
				if($count==0){
					$sql = "INSERT INTO ccfd01_subcuenta VALUES ('$cp', '$ce', '$cti', '$ci', 1, '$cod_tipo_cuenta', '$cod_cuenta', '$cod_subcuenta', '$descripcion', '$concepto');";
					if($this->ccfd01_subcuenta->execute($sql) > 1){
						//echo "<br />La cuenta de la partida se creo de manera correcta";
					}else{
						//echo "<br />La cuenta de la partida no pudo ser creada";
					}

					//CREACION DE LAS CUENTAS 1 - 122 CONTRAPARTE DE LAS 2 - 301 EN EL PLAN DE CUENTAS CONTABLES.
					$cod_tipo_cuenta = 1;
					$cod_cuenta = 122;//$var1."01";
					$sql = "INSERT INTO ccfd01_subcuenta VALUES ('$cp', '$ce', '$cti', '$ci', 1, '$cod_tipo_cuenta', '$cod_cuenta', '$cod_subcuenta', '$descripcion', '$concepto');";
					if($this->ccfd01_subcuenta->execute($sql) > 1){
						//echo "<br />La cuenta de la partida se creo de manera correcta";
					}else{
						//echo "<br />La cuenta de la partida no pudo ser creada";
					}
				}else{
					//echo "<br />No se creo la cuenta de la partida ya existe en el plan de cuentas";
				}
			}
	}

	if($var3!=null){    $codigos .= "cod_generica, ";   $values .=  " '".$var3."',  ";  $tabla='cfpd01_ano_3_generica';

			if($var1==3){// Insercion en la tabla de division contable para la cuenta 301.
				$cod_tipo_cuenta = 2;
				$cod_cuenta = $var1."01";
				$cod_subcuenta = $var2;
				$cod_division = $var3;
				$condicion_2 = $condicion." and cod_tipo_cuenta='$cod_tipo_cuenta' and cod_cuenta='$cod_cuenta' and cod_subcuenta='$cod_subcuenta' and cod_division='$cod_division'";
				$count = $this->ccfd01_division->findCount($condicion_2);
				if($count==0){
					$sql = "INSERT INTO ccfd01_division VALUES ('$cp', '$ce', '$cti', '$ci', 1, '$cod_tipo_cuenta', '$cod_cuenta', '$cod_subcuenta', '$cod_division', '$descripcion', '$concepto');";
					if($this->ccfd01_division->execute($sql) > 1){
						//echo "<br />La cuenta de la generica se creo de manera correcta";
					}else{
						//echo "<br />La cuenta de la generica no pudo ser creada";
					}

					//CREACION DE LAS CUENTAS 1 - 122 CONTRAPARTE DE LAS 2 - 301 EN EL PLAN DE CUENTAS CONTABLES.
					$cod_tipo_cuenta = 1;
					$cod_cuenta = 122;//$var1."01";
					$sql = "INSERT INTO ccfd01_division VALUES ('$cp', '$ce', '$cti', '$ci', 1, '$cod_tipo_cuenta', '$cod_cuenta', '$cod_subcuenta', '$cod_division', '$descripcion', '$concepto');";
					if($this->ccfd01_division->execute($sql) > 1){
						//echo "<br />La cuenta de la generica se creo de manera correcta";
					}else{
						//echo "<br />La cuenta de la generica no pudo ser creada";
					}
				}else{
					//echo "<br />No se creo la cuenta de la generica ya existe en el plan de cuentas";
				}
			}
	}

	$inserta='no';
	$tabla_insertar="";

	if($var4!=null){

			$codigos .= "cod_especifica, ";
			$values .=  " '".$var4."',  ";
			$tabla='cfpd01_ano_4_especifica';

			$inserta='si';
			$tabla_inserta = "cfpd01_ano_5_sub_espec";
			$codigo_inserta =   $codigos."cod_sub_espec, ";
			$values_inserta = $values. " '0',  ";

			if($var1==3){// Insercion en la tabla de subdivision contable para la cuenta 301.
				//echo "<br />".$var1;
				//echo "<br />".$var2;
				//echo "<br />".$var3;
				//echo "<br />".$var4;
				$cod_tipo_cuenta=2;
				$cod_cuenta = $var1."01";
				$cod_subcuenta = $var2;
				$cod_division = $var3;
				$cod_subdivision = $var4;
				$condicion_2 = $condicion." and cod_tipo_cuenta='$cod_tipo_cuenta' and cod_cuenta='$cod_cuenta' and cod_subcuenta='$cod_subcuenta' and cod_division='$cod_division' and cod_subdivision='$cod_subdivision'";
				$count = $this->ccfd01_subdivision->findCount($condicion_2);
				if($count==0){
					$sql = "INSERT INTO ccfd01_subdivision VALUES ('$cp', '$ce', '$cti', '$ci', 1, '$cod_tipo_cuenta', '$cod_cuenta', '$cod_subcuenta', '$cod_division', '$cod_subdivision', '$descripcion', '$concepto');";
					if($this->ccfd01_subdivision->execute($sql) > 1){
						//echo "<br />La cuenta de la especifica se creo de manera correcta";
					}else{
						//echo "<br />La cuenta de la especifica no pudo ser creada";
					}

					//CREACION DE LAS CUENTAS 1 - 122 CONTRAPARTE DE LAS 2 - 301 EN EL PLAN DE CUENTAS CONTABLES.
					$cod_tipo_cuenta=1;
					$cod_cuenta = 122;//$var1."01";
					$sql = "INSERT INTO ccfd01_subdivision VALUES ('$cp', '$ce', '$cti', '$ci', 1, '$cod_tipo_cuenta', '$cod_cuenta', '$cod_subcuenta', '$cod_division', '$cod_subdivision', '$descripcion', '$concepto');";
					if($this->ccfd01_subdivision->execute($sql) > 1){
						//echo "<br />La cuenta de la especifica se creo de manera correcta";
					}else{
						//echo "<br />La cuenta de la especifica no pudo ser creada";
					}
				}else{
					//echo "<br />No se creo la cuenta de la especifica ya existe en el plan de cuentas";
				}
			}
	}

	if($var5!=null){    $codigos .= "cod_sub_espec, ";   $values .=  " '".$var5."',  ";   $tabla='cfpd01_ano_5_sub_espec';         $inserta='no';  }

	if($var6!=null){     $codigos .= "cod_auxiliar, ";   $values .=  " '".$var6."',  ";    $tabla='cfpd01_ano_6_auxiliar';                      $inserta='no';    }


	$sql_1 = "INSERT INTO  ".$tabla."   (ejercicio,  ".$codigos."  concepto, denominacion)   VALUES  ( '".$ejercicio."',  ".$values."   '$concepto', '$descripcion' )  ";


	$sql = $sql_1;



	$this->set('opcion1', $var1);
	$this->set('opcion2', $var2);
	$this->set('opcion3', $var3);
	$this->set('opcion4', $var4);
    $this->set('opcion6', $var6);


	$tabla = '';
	$sql_2 = '';

  $sql_2 = " ejercicio = ".$ejercicio." and ";

	if($var1!=null){ $sql_2 .=  ' cod_grupo =  '.$var1.'  ';                       $tabla='cfpd01_ano_grupo';                             }
	if($var2!=null){ $sql_2 .= 'and cod_partida = '.$var2.'  ';              $tabla='cfpd01_ano_partida';                            }
	if($var3!=null){ $sql_2 .= 'and cod_generica = '.$var3.'  ';           $tabla='cfpd01_ano_generica';                         }
	if($var4!=null){ $sql_2 .= 'and cod_especifica = '.$var4.'  ';        $tabla='cfpd01_ano_especifica';                       }
	if($var5!=null){ $sql_2.= 'and cod_sub_espec = '.$var5.'  ';  $tabla='cfpd01_ano_sub_espec';                           }
	if($var6!=null){ $sql_2 .='and cod_auxiliar = '.$var6.'  ';                $tabla='cfpd01_ano_auxiliar';                            }


$this->set('tabla', $tabla);


if($tabla!=''){

  if ($this->$tabla->validates($this->data['cfpp01'])){

	  if($this->$tabla->findCount($sql_2) == 0){

	   		$this->$tabla->execute($sql);

			   if($inserta=='si'){
					$sql_inserta = "INSERT INTO  ".$tabla_inserta."   (ejercicio,  ".$codigo_inserta."  concepto, denominacion)   VALUES  ( '".$ejercicio."',  ".$values_inserta."   '$concepto', '$descripcion' )  ";
//	 				echo $sql_inserta;
	 				$this->$tabla->execute($sql_inserta);
			   }//fin

			  if($var4!=null  && $var5==null){

//			  	$sql_1 = "INSERT INTO  cfpd01_ano_5_sub_espec   ( ".$codigos." cod_sub_espec,  concepto, denominacion)   VALUES  ( ".$values." '0' ,  '$concepto', '$descripcion' )  ";
//			    $this->cfpd01_ano_sub_espec->execute($sql_1);
			  }

			$this->set('errorMessage', 'Los Datos Fueron Guardados ');

	   }else{ $this->set('Message_existe', 'Este registro no fue almacenado porque ya existe');}

   }else{}


    $datos = $this->$tabla->findAll($sql_2, null, null, null);

	  $this->set('datos_cod_cfpp01', $datos);

	   if($var5 == 0  && $var5 != null){$var5 = 'nuevo'; }$this->set('opcion5', $var5);



 }//fin if tabla






 }//FIN FUNCTION











 function editar($ejercicio=null, $var1=null, $var2=null, $var3=null, $var4=null, $var5=null, $var6=null){

 	$this->layout = "ajax";
	$this->set('opcion1', $var1);
	$this->set('opcion2', $var2);
	$this->set('opcion3', $var3);
	$this->set('opcion4', $var4);
	$this->set('opcion5', $var5);
    $this->set('opcion6', $var6);
	$this->set('ejercicio', $ejercicio);

 // echo $var5;


	$action='';
	$tabla = '';
	$sql_2 = '';

	$sql_2 = " ejercicio = ".$ejercicio." and ";

	if($var1!=null){ $sql_2 .=  ' cod_grupo =  '.$var1.'  ';                     $tabla='cfpd01_ano_grupo';                             }
	if($var2!=null){ $sql_2 .= 'and cod_partida = '.$var2.'  ';              $tabla='cfpd01_ano_partida';                           }
	if($var3!=null){ $sql_2 .= 'and cod_generica = '.$var3.'  ';           $tabla='cfpd01_ano_generica';                        }
	if($var4!=null){ $sql_2 .= 'and cod_especifica = '.$var4.'  ';         $tabla='cfpd01_ano_especifica';                     }
	if($var5!=null){ $sql_2.= 'and cod_sub_espec = '.$var5.'  ';        $tabla='cfpd01_ano_sub_espec';                   }
	if($var6!=null){ $sql_2 .='and cod_auxiliar = '.$var6.'  ';                $tabla='cfpd01_ano_auxiliar';                          }

	$this->set('tabla', $tabla);

	  $data = $this->$tabla->findAll($sql_2, null, null, null);

	  $this->set('datos_cod_cfpp01', $data);

	   if($var5 == 0  && $var5 != null){$var5 = 'nuevo'; }$this->set('opcion5', $var5);



 }






function editar2($ejercicio=null, $pagina=null, $var1=null, $var2=null, $var3=null, $var4=null, $var5=null, $var6=null){

 	$this->layout = "ajax";
	$this->set('opcion1', $var1);
	$this->set('opcion2', $var2);
	$this->set('opcion3', $var3);
	$this->set('opcion4', $var4);
	$this->set('opcion5', $var5);
    $this->set('opcion6', $var6);
	$this->set('ejercicio', $ejercicio);

 // echo $var5;


	$action='';
	$tabla = '';
	$sql_2 = '';

	$sql_2 = " ejercicio = ".$ejercicio." and ";

	if($var1!=null){ $sql_2 .=  ' cod_grupo =  '.$var1.'  ';                     $tabla='cfpd01_ano_grupo';                             }
	if($var2!=null){ $sql_2 .= 'and cod_partida = '.$var2.'  ';              $tabla='cfpd01_ano_partida';                           }
	if($var3!=null){ $sql_2 .= 'and cod_generica = '.$var3.'  ';           $tabla='cfpd01_ano_generica';                        }
	if($var4!=null){ $sql_2 .= 'and cod_especifica = '.$var4.'  ';         $tabla='cfpd01_ano_especifica';                     }
	if($var5!=null){ $sql_2.= 'and cod_sub_espec = '.$var5.'  ';        $tabla='cfpd01_ano_sub_espec';                   }
	if($var6!=null){ $sql_2 .='and cod_auxiliar = '.$var6.'  ';                $tabla='cfpd01_ano_auxiliar';                          }

	$this->set('tabla', $tabla);
	$this->set('pagina_actual', $pagina);

	  $data = $this->$tabla->findAll($sql_2, null, null, null);

	  $this->set('datos_cod_cfpp01', $data);

	   if($var5 == 0  && $var5 != null){$var5 = 'nuevo'; }$this->set('opcion5', $var5);



 }





 function  guardar_editar($ejercicio=null, $tabla=null, $var1=null, $var2=null, $var3=null, $var4=null, $var5=null, $var6=null, $aux=null){

 	$this->layout = "ajax";
	$this->set('ejercicio', $ejercicio);


	 $descripcion = $this->data['cfpp01']['descripcion'];
	 $concepto = $this->data['cfpp01']['concepto'];


//echo $tabla;

	$sql_1 = 'UPDATE '.$tabla.'   SET  concepto = \''.$concepto.'\', denominacion = \''.$descripcion.'\' WHERE ejercicio= '.$ejercicio.' and  ';

	if($var1!=null){
		$sql_2 =  ' cod_grupo =  '.$var1.'  ';
                $tabla='cfpd01_ano_grupo';
	}
	if($var2!=null){
		$sql_2 .= 'and cod_partida = '.$var2.'  ';
		$tabla='cfpd01_ano_partida';
	}


	if($var3!=null){ $sql_2 .= 'and cod_generica = '.$var3.'  ';            $tabla='cfpd01_ano_generica';                       }
	if($var4!=null){ $sql_2 .= 'and cod_especifica = '.$var4.'  ';         $tabla='cfpd01_ano_especifica';                    }
	if($var5!=null){ $sql_2.= 'and cod_sub_espec = '.$var5.'  ';  		$tabla='cfpd01_ano_sub_espec';            }
	if($var6!=null){ $sql_2 .='and cod_auxiliar = '.$var6.'  ';                $tabla='cfpd01_ano_auxiliar';                      }

	$this->set('tabla', $tabla);


	$sql = $sql_1.$sql_2.';';


	$this->set('errorMessage', 'Los Datos Fueron Modificados');


	$this->set('opcion1', $var1);
	$this->set('opcion2', $var2);
	$this->set('opcion3', $var3);
	$this->set('opcion4', $var4);
	$this->set('opcion5', $var5);
    $this->set('opcion6', $var6);


	$tabla = '';
	$sql_2 = '';


	$sql_2 = " ejercicio = ".$ejercicio." and ";

	if($var1!=null){ $sql_2 =  ' cod_grupo =  '.$var1.'  ';                       $tabla='cfpd01_ano_grupo';   }
	if($var2!=null){ $sql_2 .= 'and cod_partida = '.$var2.'  ';              $tabla='cfpd01_ano_partida';                            }
	if($var3!=null){ $sql_2 .= 'and cod_generica = '.$var3.'  ';           $tabla='cfpd01_ano_generica';                        }
	if($var4!=null){ $sql_2 .= 'and cod_especifica = '.$var4.'  ';        $tabla='cfpd01_ano_especifica';                      }
	if($var5!=null){ $sql_2.= 'and cod_sub_espec = '.$var5.'  ';  $tabla='cfpd01_ano_sub_espec';              }
	if($var5==null  && $var6!=null){ $sql_2.= 'and cod_sub_espec = '.$var5.'  ';  $tabla='cfpd01_ano_sub_espec';              }
	if($var6!=null){ $sql_2 .='and cod_auxiliar = '.$var6.'  ';                $tabla='cfpd01_ano_auxiliar';                            }

   $this->$tabla->execute($sql);

	  $data = $this->$tabla->findAll($sql_2, null, null, null);

	  $this->set('datos_cod_cfpp01', $data);

	   if($var5 == 0  && $var5 != null){$var5 = 'nuevo'; }$this->set('opcion5', $var5);




 }//FIN FUNCTION










 function  guardar_editar2($ejercicio=null, $pagina=null, $tabla=null, $var1=null, $var2=null, $var3=null, $var4=null, $var5=null, $var6=null, $aux=null){

 	$this->layout = "ajax";
	$this->set('ejercicio', $ejercicio);


	 $descripcion = $this->data['cfpp01']['descripcion'];
	 $concepto = $this->data['cfpp01']['concepto'];


//echo $tabla;

	$sql_1 = 'UPDATE '.$tabla.'   SET  concepto = \''.$concepto.'\', denominacion = \''.$descripcion.'\' WHERE ejercicio= '.$ejercicio.' and  ';

	if($var1!=null){
		$sql_2 =  ' cod_grupo =  '.$var1.'  ';
                $tabla='cfpd01_ano_grupo';
	}
	if($var2!=null){
		$sql_2 .= 'and cod_partida = '.$var2.'  ';
		$tabla='cfpd01_ano_partida';
	}


	if($var3!=null){ $sql_2 .= 'and cod_generica = '.$var3.'  ';            $tabla='cfpd01_ano_generica';                       }
	if($var4!=null){ $sql_2 .= 'and cod_especifica = '.$var4.'  ';         $tabla='cfpd01_ano_especifica';                    }
	if($var5!=null){ $sql_2.= 'and cod_sub_espec = '.$var5.'  ';  		$tabla='cfpd01_ano_sub_espec';            }
	if($var6!=null){ $sql_2 .='and cod_auxiliar = '.$var6.'  ';                $tabla='cfpd01_ano_auxiliar';                      }

	$this->set('tabla', $tabla);


	$sql = $sql_1.$sql_2.';';


	$this->set('errorMessage', 'Los Datos Fueron Modificados');


	$this->set('opcion1', $var1);
	$this->set('opcion2', $var2);
	$this->set('opcion3', $var3);
	$this->set('opcion4', $var4);
	$this->set('opcion5', $var5);
    $this->set('opcion6', $var6);


	$tabla = '';
	$sql_2 = '';


	$sql_2 = " ejercicio = ".$ejercicio." and ";

	if($var1!=null){ $sql_2 =  ' cod_grupo =  '.$var1.'  ';                       $tabla='cfpd01_ano_grupo';   }
	if($var2!=null){ $sql_2 .= 'and cod_partida = '.$var2.'  ';              $tabla='cfpd01_ano_partida';                            }
	if($var3!=null){ $sql_2 .= 'and cod_generica = '.$var3.'  ';           $tabla='cfpd01_ano_generica';                        }
	if($var4!=null){ $sql_2 .= 'and cod_especifica = '.$var4.'  ';        $tabla='cfpd01_ano_especifica';                      }
	if($var5!=null){ $sql_2.= 'and cod_sub_espec = '.$var5.'  ';  $tabla='cfpd01_ano_sub_espec';              }
	if($var5==null  && $var6!=null){ $sql_2.= 'and cod_sub_espec = '.$var5.'  ';  $tabla='cfpd01_ano_sub_espec';              }
	if($var6!=null){ $sql_2 .='and cod_auxiliar = '.$var6.'  ';                $tabla='cfpd01_ano_auxiliar';                            }

   $this->$tabla->execute($sql);

	  $data = $this->$tabla->findAll($sql_2, null, null, null);

	  $this->set('datos_cod_cfpp01', $data);

	   if($var5 == 0  && $var5 != null){$var5 = 'nuevo'; }$this->set('opcion5', $var5);

	$this->consulta2($ejercicio, $pagina);
	$this->render("consulta2");


 }//FIN FUNCTION




 function eliminar($ejercicio=null, $var1=null, $var2=null, $var3=null, $var4=null, $var5=null, $var6=null){

 	$this->layout = "ajax";
	$this->set('ejercicio', $ejercicio);


	 $tabla[1]='cfpd01_ano_1_grupo';
	 $tabla[2]='cfpd01_ano_2_partida';
	 $tabla[3]='cfpd01_ano_3_generica';
	 $tabla[4]='cfpd01_ano_4_especifica';
	 $tabla[5]='cfpd01_ano_5_sub_espec';
	 $tabla[6]='cfpd01_ano_6_auxiliar';


	 $tablas[1]='cfpd01_ano_grupo';
	 $tablas[2]='cfpd01_ano_partida';
	 $tablas[3]='cfpd01_ano_generica';
	 $tablas[4]='cfpd01_ano_especifica';
	 $tablas[5]='cfpd01_ano_sub_espec';
	 $tablas[6]='cfpd01_ano_auxiliar';

   $n_tabla = 0;
   $sql_2 = '';
;

	if($var1!=null){ $sql_2 =  'ejercicio = '.$ejercicio.' and cod_grupo =  '.$var1.'  ';      $n_tabla++;}
	if($var2!=null){ $sql_2 .= ' and cod_partida = '.$var2.'  ';     $n_tabla++;}
	if($var3!=null){ $sql_2 .= ' and cod_generica = '.$var3.'  ';   $n_tabla++;}
	if($var4!=null){ $sql_2 .= ' and cod_especifica = '.$var4.'  ';      $n_tabla++;}
	if($var5!=null){ $sql_2  .= ' and cod_sub_espec = '.$var5.'  ';    $n_tabla++;}
	if($var6!=null){ $sql_2 .=' and cod_auxiliar = '.$var6.'  ';                $n_tabla++; }



	//for($i=$n_tabla; $i<=6; $i++){

   					$sql_1 = 'DELETE  FROM '.$tabla[$n_tabla].'   WHERE ';
					$sql = $sql_1.$sql_2.' ;';

					$this->$tablas[$n_tabla]->execute($sql);
	//}//fin for

	$this->set('errorMessage', 'Los Datos Fueron Eliminados ');


	$this->set('opcion1', $var1);
	$this->set('opcion2', $var2);
	$this->set('opcion3', $var3);
	$this->set('opcion4', $var4);
	$this->set('opcion5', $var5);
    $this->set('opcion6', $var6);


	$tabla = '';
	$sql_2 = '';


	if($var2!=null){ $sql_2 =  'ejercicio = '.$ejercicio.' and cod_grupo =  '.$var1.'  ';                       $tabla='cfpd01_ano_grupo';                             }
	if($var3!=null){ $sql_2 .= 'and cod_partida = '.$var2.'  ';              												   $tabla='cfpd01_ano_partida';                           }
	if($var4!=null){ $sql_2 .= 'and cod_generica = '.$var3.'  ';          												   $tabla='cfpd01_ano_generica';                        }
	if($var5!=null){ $sql_2 .= 'and cod_especifica = '.$var4.'  ';        												   $tabla='cfpd01_ano_especifica';                      }
	if($var6!=null){ $sql_2.= 'and cod_sub_espec = '.$var5.'  '; 													   $tabla='cfpd01_ano_sub_espec';                    }
	//if($var7!=null){ $sql_2 .='and cod_auxiliar = '.$var6.'  ';                $tabla='cfpd01_ano_auxiliar';                            }

	  if($sql_2 != ''){
	  	$data = $this->$tabla->findAll($sql_2, null, null, null);
	  	$this->set('datos_cod_cfpp01', $data);
	  	$this->set('tabla', $tabla);
	  }




 }//FIN FUNCTION






function eliminar2($ejercicio=null, $pagina=null, $var1=null, $var2=null, $var3=null, $var4=null, $var5=null, $var6=null){

 	$this->layout = "ajax";
	$this->set('ejercicio', $ejercicio);


	 $tabla[1]='cfpd01_ano_1_grupo';
	 $tabla[2]='cfpd01_ano_2_partida';
	 $tabla[3]='cfpd01_ano_3_generica';
	 $tabla[4]='cfpd01_ano_4_especifica';
	 $tabla[5]='cfpd01_ano_5_sub_espec';
	 $tabla[6]='cfpd01_ano_6_auxiliar';


	 $tablas[1]='cfpd01_ano_grupo';
	 $tablas[2]='cfpd01_ano_partida';
	 $tablas[3]='cfpd01_ano_generica';
	 $tablas[4]='cfpd01_ano_especifica';
	 $tablas[5]='cfpd01_ano_sub_espec';
	 $tablas[6]='cfpd01_ano_auxiliar';

   $n_tabla = 0;
   $sql_2 = '';
;

	if($var1!=null){ $sql_2 =  'ejercicio = '.$ejercicio.' and cod_grupo =  '.$var1.'  ';      $n_tabla++;}
	if($var2!=null){ $sql_2 .= ' and cod_partida = '.$var2.'  ';     $n_tabla++;}
	if($var3!=null){ $sql_2 .= ' and cod_generica = '.$var3.'  ';   $n_tabla++;}
	if($var4!=null){ $sql_2 .= ' and cod_especifica = '.$var4.'  ';      $n_tabla++;}
	if($var5!=null){ $sql_2  .= ' and cod_sub_espec = '.$var5.'  ';    $n_tabla++;}
	if($var6!=null){ $sql_2 .=' and cod_auxiliar = '.$var6.'  ';                $n_tabla++; }



	//for($i=$n_tabla; $i<=6; $i++){

   					$sql_1 = 'DELETE  FROM '.$tabla[$n_tabla].'   WHERE ';
					$sql = $sql_1.$sql_2.' ;';

					$this->$tablas[$n_tabla]->execute($sql);
	//}//fin for

	$this->set('Message', 'Los Datos Fueron Eliminados ');


	$this->set('opcion1', $var1);
	$this->set('opcion2', $var2);
	$this->set('opcion3', $var3);
	$this->set('opcion4', $var4);
	$this->set('opcion5', $var5);
    $this->set('opcion6', $var6);


	$tabla = '';
	$sql_2 = '';


	if($var2!=null){ $sql_2 =  'ejercicio = '.$ejercicio.' and cod_grupo =  '.$var1.'  ';                       $tabla='cfpd01_ano_grupo';                             }
	if($var3!=null){ $sql_2 .= 'and cod_partida = '.$var2.'  ';              												   $tabla='cfpd01_ano_partida';                           }
	if($var4!=null){ $sql_2 .= 'and cod_generica = '.$var3.'  ';          												   $tabla='cfpd01_ano_generica';                        }
	if($var5!=null){ $sql_2 .= 'and cod_especifica = '.$var4.'  ';        												   $tabla='cfpd01_ano_especifica';                      }
	if($var6!=null){ $sql_2.= 'and cod_sub_espec = '.$var5.'  '; 													   $tabla='cfpd01_ano_sub_espec';                    }
	//if($var7!=null){ $sql_2 .='and cod_auxiliar = '.$var6.'  ';                $tabla='cfpd01_ano_auxiliar';                            }

	  if($sql_2 != ''){
	  	$data = $this->$tabla->findAll($sql_2, null, null, null);
	  	$this->set('datos_cod_cfpp01', $data);
	  	$this->set('tabla', $tabla);
	  }



if($pagina<=0){$pagina=1;}

$this->consulta2($ejercicio, $pagina);
$this->render("consulta2");

 }//FIN FUNCTION






function consulta($ejercicio=null, $pag_num=null) {
 		$this->layout = "ajax";
set_time_limit(0);

		  if($ejercicio!=null){$this->set('ejercicio', $ejercicio);

		}else if($this->data['cfpp01']['ano']){

							$this->set('ejercicio', $this->data['cfpp01']['ano']);
							$ejercicio = $this->data['cfpp01']['ano'];

							}


		 $grupo = $this->cfpd01_ano_grupo->findAll('ejercicio='.$ejercicio.' and cod_grupo=3', null, 'cod_grupo ASC', null, null, null);
		 $partida = $this->cfpd01_ano_partida->findAll('ejercicio='.$ejercicio.' and cod_grupo=3', null, 'cod_partida ASC', null, null, null);
		 $generica = $this->cfpd01_ano_generica->findAll('ejercicio='.$ejercicio.' and cod_grupo=3', null, 'cod_generica ASC', null, null, null);
		 $especifica = $this->cfpd01_ano_especifica->findAll('ejercicio='.$ejercicio.' and cod_grupo=3', null, 'cod_especifica ASC', null, null, null);
		 $subespecifica = $this->cfpd01_ano_sub_espec->findAll('ejercicio='.$ejercicio.' and cod_grupo=3', null, 'cod_sub_espec ASC', null, null, null);
		 $auxiliar = $this->cfpd01_ano_auxiliar->findAll('ejercicio='.$ejercicio.' and cod_grupo=3', null, 'cod_auxiliar ASC', null, null, null);




$grupo_ver = '';
$partida_ver = '';
$generica_ver = '';
$especifica_ver = '';
$subespecifica_ver = '';
$auxiliar_ver = '';


$grupo_ver_aux = '';
$partida_ver_aux = '';
$generica_ver_aux = '';
$especifica_ver_aux = '';
$subespecifica_ver_aux = '';
$auxiliar_ver_aux = '';




$consulta = '';
$index = 0;


$i = 0;
$j = 0;
$k = 0;
$l = 0;
$n = 0;
$o = 0;


 foreach($grupo as $row){   $i++;

$grupo_ver[$i]  = $row['cfpd01_ano_grupo']['cod_grupo'];

$grupo_ver_aux[$i] = $row['cfpd01_ano_grupo']['cod_grupo'];


   $grupo_descripcion[$i] = $row['cfpd01_ano_grupo']['denominacion'];
   $grupo_concepto[$i] =  $row['cfpd01_ano_grupo']['concepto'];

}



 foreach($partida as $row){ $j++;


$partida_ver[$j] = $row['cfpd01_ano_partida']['cod_grupo'].".".$this->add_c_c($row['cfpd01_ano_partida']['cod_partida']);

$partida_ver_aux[$j] = $this->add_c_c($row['cfpd01_ano_partida']['cod_partida']);

  $partida_descripcion[$j]=$row['cfpd01_ano_partida']['denominacion'];
   $partida_concepto[$j] =  $row['cfpd01_ano_partida']['concepto'];

 }

 foreach($generica as $row){ $k++;

$generica_ver[$k] =  $row['cfpd01_ano_generica']['cod_grupo'].".".$this->add_c_c($row['cfpd01_ano_generica']['cod_partida']).".".$this->add_c_c($row['cfpd01_ano_generica']['cod_generica']);

$generica_ver_aux[$k] = $this->add_c_c($row['cfpd01_ano_generica']['cod_generica']);

  $generica_descripcion[$k] =$row['cfpd01_ano_generica']['denominacion'];
  $generica_concepto[$k] =  $row['cfpd01_ano_generica']['concepto'] ;
 }


foreach($especifica as $row){ $l++;

$especifica_ver [$l]=  $row['cfpd01_ano_especifica']['cod_grupo'].".".$this->add_c_c($row['cfpd01_ano_especifica']['cod_partida']).".".$this->add_c_c($row['cfpd01_ano_especifica']['cod_generica']).".".$this->add_c_c($row['cfpd01_ano_especifica']['cod_especifica']);

 $especifica_ver_aux[$l] = $this->add_c_c($row['cfpd01_ano_especifica']['cod_especifica']);

 $especifica_descripcion[$l] = $row['cfpd01_ano_especifica']['denominacion'];
 $especifica_concepto[$l] = $row['cfpd01_ano_especifica']['concepto'];

 }


 foreach($subespecifica as $row){ $n++;


$subespecifica_ver[$n] =  $row['cfpd01_ano_sub_espec']['cod_grupo'].".".$this->add_c_c($row['cfpd01_ano_sub_espec']['cod_partida']).".".$this->add_c_c($row['cfpd01_ano_sub_espec']['cod_generica']).".".$this->add_c_c($row['cfpd01_ano_sub_espec']['cod_especifica']).".".$this->add_c_c($row['cfpd01_ano_sub_espec']['cod_sub_espec']);

 $subespecifica_ver_aux[$n] = $this->add_c_c($row['cfpd01_ano_sub_espec']['cod_sub_espec']);

$subespecifica_descripcion[$n] =$row['cfpd01_ano_sub_espec']['denominacion'];
$subespecifica_concepto[$n] =$row['cfpd01_ano_sub_espec']['concepto'];

 }



 foreach($auxiliar as $row){ $o++;

$auxiliar_ver[$o] =  $row['cfpd01_ano_auxiliar']['cod_grupo'].".".$this->add_c_c($row['cfpd01_ano_auxiliar']['cod_partida']).".".$this->add_c_c($row['cfpd01_ano_auxiliar']['cod_generica']).".".$this->add_c_c($row['cfpd01_ano_auxiliar']['cod_especifica']).".".$this->add_c_c($row['cfpd01_ano_auxiliar']['cod_sub_espec']).".".$this->add_c_c($row['cfpd01_ano_auxiliar']['cod_auxiliar']);

$auxiliar_ver_aux[$o] = $this->add_c_c($row['cfpd01_ano_auxiliar']['cod_auxiliar']);

$auxiliar_descripcion[$o] = $row['cfpd01_ano_auxiliar']['denominacion'];
$auxiliar_concepto[$o] = $row['cfpd01_ano_auxiliar']['concepto'];



 }




for($a=1; $a<=$i; $a++){

  						if($grupo_ver[$a]!=''){

						         $index++;
								 $consulta[$index]['codigo'] =  $grupo_ver[$a].'.00.00.00.00.00';
								 $consulta[$index]['denominacion'] =  $grupo_descripcion[$a];
                                 $consulta[$index]['concepto'] =   $grupo_concepto[$a];

								 }//fin if

   							$grupo_ver[$a]='';





	for($b=1; $b<=$j; $b++){

	     $aux  = $grupo_ver_aux[$a].'.'.$partida_ver_aux[$b];


	         if($aux == $partida_ver[$b] ){

						if($partida_ver[$b]!=''){

						     $index++;
							 $consulta[$index]['codigo'] =   $partida_ver[$b].'.00.00.00.00' ;
							 $consulta[$index]['denominacion'] =  $partida_descripcion[$b];
                             $consulta[$index]['concepto'] =   $partida_concepto[$b];

								  }//fin if


						$partida_ver[$b] = '';

						}


		for($c=1; $c<=$k; $c++){


			$aux  = $grupo_ver_aux[$a].'.'.$partida_ver_aux[$b].'.'.$generica_ver_aux[$c];

		if($aux == $generica_ver[$c] ){

						if($generica_ver[$c]!=''){

						      $index++;
							  $consulta[$index]['codigo'] =   $generica_ver[$c].'.00.00.00' ;
							  $consulta[$index]['denominacion'] =  $generica_descripcion[$c];
                              $consulta[$index]['concepto'] =   $generica_concepto[$c];

							}//fin if

						$generica_ver[$c] = '';


						}


			for($d=1; $d<=$l; $d++){

			$aux  = $grupo_ver_aux[$a].'.'.$partida_ver_aux[$b].'.'.$generica_ver_aux[$c].'.'.$especifica_ver_aux[$d];

			if($aux == $especifica_ver[$d] ){

					 if($especifica_ver[$d]!=''){

					      $index++;
						  $consulta[$index]['codigo'] =   $especifica_ver[$d].'.00.00';
						  $consulta[$index]['denominacion'] =  $especifica_descripcion[$d];
                          $consulta[$index]['concepto'] =   $especifica_concepto[$d];

						   }//fin if

  						 $especifica_ver[$d] = '';


						 }





				for($e=1; $e<=$n; $e++){

				$aux  = $grupo_ver_aux[$a].'.'.$partida_ver_aux[$b].'.'.$generica_ver_aux[$c].'.'.$especifica_ver_aux[$d].'.'.$subespecifica_ver_aux[$e];

				if($aux == $subespecifica_ver[$e] ){

						if($subespecifica_ver[$e]!= '') {

							   $index++;
							   $consulta[$index]['codigo'] =   $subespecifica_ver[$e].'.00' ;
							   $consulta[$index]['denominacion'] =  $subespecifica_descripcion[$e];
                               $consulta[$index]['concepto'] =   $subespecifica_concepto[$e];

							    }//fin if

						$subespecifica_ver[$e] = '';


						}





					for($f=1; $f<=$o; $f++){

					$aux  = $grupo_ver_aux[$a].'.'.$partida_ver_aux[$b].'.'.$generica_ver_aux[$c].'.'.$especifica_ver_aux[$d].'.'.$subespecifica_ver_aux[$e].'.'.$auxiliar_ver_aux[$f];

					if($aux == $auxiliar_ver[$f] ){

						if($auxiliar_ver[$f]!= '') {

						        $index++;
								$consulta[$index]['codigo'] =  $auxiliar_ver[$f];
								$consulta[$index]['denominacion'] =  $auxiliar_descripcion[$f];
                                $consulta[$index]['concepto'] =   $auxiliar_concepto[$f];

								 }//fin if

   							$auxiliar_ver[$f] = '';


							}


					}
				}
			}
		}
	}
}


$this->set('consulta', $consulta);
$this->set('ejercicio', $ejercicio);
if($pag_num!=null){$this->set('pagina_actual', $pag_num);  }



 }//fin function consultar


function consulta2($ejercicio=null, $pagina=null) {
 		$this->layout = "ajax";
        //set_time_limit(0);
		if($ejercicio!=null){
			$this->set('ejercicio', $ejercicio);
		}else if($this->data['cfpp01']['ano']){
			$this->set('ejercicio', $this->data['cfpp01']['ano']);
			$ejercicio = $this->data['cfpp01']['ano'];
        }
        if(!isset($pagina)){
        	$pagina=1;
        }
         $Tfilas=$this->v_clasificador_partidas_ejercicio->findCount('ejercicio='.$ejercicio);
         if($Tfilas!=0){
			 $this->set('pag_cant',$pagina.'/'.$Tfilas);
			 $this->set('ultimo',$Tfilas);
             $datos_consulta=$this->v_clasificador_partidas_ejercicio->findAll('ejercicio='.$ejercicio,null,'ejercicio,cod_grupo,cod_partida,cod_generica,cod_especifica,cod_sub_espec,cod_auxiliar, tabla ASC',1,$pagina,null);
             $this->set('datos_consulta',$datos_consulta);
             $this->set('siguiente',$pagina+1);
			 $this->set('anterior',$pagina-1);
			 $this->set('pagina_actual',$pagina);
			 $this->bt_nav($Tfilas,$pagina);
         }
 }//fin function consultar2

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




 function traspaso($ejercicio=null){

    $this->layout = "ajax";
	$this->set('ejercicio', $ejercicio);


	//$ejercicio = $this->data['cfpp01']['ano'];

	$codigos = "";
	$values = "";


	 	 $grupo = $this->cfpd01_grupo->findAll(null, null, 'cod_grupo ASC', null, null, null);


		 foreach($grupo as $datos){

		     $var1 = $datos['cfpd01_grupo']['cod_grupo'];
			 $concepto = $datos['cfpd01_grupo']['concepto'];
			 $descripcion = $datos['cfpd01_grupo']['descripcion'];

		 		$codigos = "cod_grupo, ";
				$values =  " '".$var1."',  " ;
				$tabla='cfpd01_ano_grupo';

$sql_aux = "ejercicio = ".$ejercicio." and cod_grupo='".$var1."'  ";
if($this->cfpd01_ano_grupo->findCount($sql_aux) == 0){
$sql_1 = "INSERT INTO  cfpd01_ano_1_grupo  (ejercicio,  ".$codigos."  concepto, denominacion)   VALUES  ( '".$ejercicio."',  ".$values."   '$concepto', '$descripcion' )  ";
$this->$tabla->execute($sql_1);}

		 }//fin foreach


		 $partida = $this->cfpd01_partida->findAll(null, null, 'cod_partida ASC', null, null, null);

		  foreach($partida as $datos){

		     $var1 = $datos['cfpd01_partida']['cod_grupo'];
			 $var2 = $datos['cfpd01_partida']['cod_partida'];
			 $concepto = $datos['cfpd01_partida']['concepto'];
			 $descripcion = $datos['cfpd01_partida']['descripcion'];


				 $codigos = "cod_grupo, cod_partida, ";
				 $values =  " '".$var1."', '".$var2."',  ";
				 $tabla='cfpd01_ano_partida';

$sql_aux = "ejercicio = ".$ejercicio." and cod_grupo='".$var1."' and  cod_partida='".$var2."'  ";
if($this->cfpd01_ano_partida->findCount($sql_aux) == 0){
 $sql_1 = "INSERT INTO  cfpd01_ano_2_partida   (ejercicio,  ".$codigos."  concepto, denominacion)   VALUES  ( '".$ejercicio."',  ".$values."   '$concepto', '$descripcion' )  ";
$this->$tabla->execute($sql_1);}

		 }//fin foreach






		 $generica = $this->cfpd01_generica->findAll(null, null, 'cod_generica ASC', null, null, null);

		 foreach($generica as $datos){

		     $var1 = $datos['cfpd01_generica']['cod_grupo'];
			 $var2 = $datos['cfpd01_generica']['cod_partida'];
			 $var3 = $datos['cfpd01_generica']['cod_generica'];
			 $concepto = $datos['cfpd01_generica']['concepto'];
			 $descripcion = $datos['cfpd01_generica']['descripcion'];

		 	  	 $codigos = "cod_grupo,  cod_partida,  cod_generica, ";
				 $values =  " '".$var1."', '".$var2."', '".$var3."', ";
				 $tabla='cfpd01_ano_generica';

$sql_aux = "ejercicio = ".$ejercicio." and cod_grupo='".$var1."' and  cod_partida='".$var2."' and  cod_generica='".$var3."' ";
if($this->cfpd01_ano_generica->findCount($sql_aux) == 0){
$sql_1 = "INSERT INTO  cfpd01_ano_3_generica   (ejercicio,  ".$codigos."  concepto, denominacion)   VALUES  ( '".$ejercicio."',  ".$values."   '$concepto', '$descripcion' )  ";
$this->$tabla->execute($sql_1);}

		 }//fin foreach





		  $especifica = $this->cfpd01_especifica->findAll(null, null, 'cod_especifica ASC', null, null, null);

		   foreach($especifica as $datos){

		     $var1 = $datos['cfpd01_especifica']['cod_grupo'];
			 $var2 = $datos['cfpd01_especifica']['cod_partida'];
			 $var3 = $datos['cfpd01_especifica']['cod_generica'];
			 $var4 = $datos['cfpd01_especifica']['cod_especifica'];
			 $concepto = $datos['cfpd01_especifica']['concepto'];
			 $descripcion = $datos['cfpd01_especifica']['descripcion'];

		  $codigos = "cod_grupo, cod_partida, cod_generica, cod_especifica,";
		  $values =  " '".$var1."',  '".$var2."',    '".$var3."', '".$var4."', ";
	      $tabla='cfpd01_ano_especifica';

$sql_aux = "ejercicio = ".$ejercicio." and cod_grupo='".$var1."' and  cod_partida='".$var2."' and  cod_generica='".$var3."' and  cod_especifica='".$var4."'  ";
if($this->cfpd01_ano_especifica->findCount($sql_aux) == 0){
$sql_1 = "INSERT INTO  cfpd01_ano_4_especifica   (ejercicio,  ".$codigos."  concepto, denominacion)   VALUES  ( '".$ejercicio."',  ".$values."   '$concepto', '$descripcion' )  ";
 $this->$tabla->execute($sql_1); }



 $sql_aux = "ejercicio = ".$ejercicio." and cod_grupo='".$var1."' and  cod_partida='".$var2."' and  cod_generica='".$var3."' and  cod_especifica='".$var4."' and cod_sub_espec='0'  ";
if($this->cfpd01_ano_sub_espec->findCount($sql_aux) == 0){
$codigos_aux = "cod_grupo, cod_partida, cod_generica, cod_especifica, cod_sub_espec,";
$values_aux =  " '".$var1."',  '".$var2."',   '".$var3."', '".$var4."', '0', ";
 $sql_2_aux = "INSERT INTO  cfpd01_ano_5_sub_espec   (ejercicio,  ".$codigos_aux."  concepto, denominacion)   VALUES  ( '".$ejercicio."',  ".$values_aux."   '$concepto', '$descripcion' )  ";
$this->cfpd01_ano_sub_espec->execute($sql_2_aux);}

		}//fin foreach







		  $subespecifica = $this->cfpd01_sub_espec->findAll(null, null, 'cod_grupo,cod_partida,cod_generica,cod_especifica,cod_sub_espec ASC', null, null, null);

		   foreach($subespecifica as $datos){

		     $var1 = $datos['cfpd01_sub_espec']['cod_grupo'];
			 $var2 = $datos['cfpd01_sub_espec']['cod_partida'];
			 $var3 = $datos['cfpd01_sub_espec']['cod_generica'];
			 $var4 = $datos['cfpd01_sub_espec']['cod_especifica'];
			 $var5 = $datos['cfpd01_sub_espec']['cod_sub_espec'];
			 $concepto = $datos['cfpd01_sub_espec']['concepto'];
			 $descripcion = $datos['cfpd01_sub_espec']['descripcion'];

		  $codigos = "cod_grupo, cod_partida, cod_generica, cod_especifica, cod_sub_espec,";
		  $values =  " '".$var1."',  '".$var2."',    '".$var3."', '".$var4."', '".$var5."', ";
		  $tabla='cfpd01_ano_sub_espec';
$dif = rand();
$sql_aux = "ejercicio = ".$ejercicio." and cod_grupo='".$var1."' and  cod_partida='".$var2."' and  cod_generica='".$var3."' and  cod_especifica='".$var4."' and cod_sub_espec='".$var5."' and $dif=$dif";
$xc = $this->cfpd01_ano_sub_espec->findCount($sql_aux);
	if($xc == 0){
		$todo []="( '".$ejercicio."',  ".$values."   '$concepto', '$descripcion' )";
 	}

   }///fin foreach
	if(isset($todo) && count($todo)>0){
	$sql_1 = "INSERT INTO  cfpd01_ano_5_sub_espec   (ejercicio,  ".$codigos."  concepto, denominacion)   VALUES    ".implode(',',$todo);
	$this->$tabla->execute($sql_1);
	}





		 $auxiliar = $this->cfpd01_auxiliar->findAll(null, null, 'cod_auxiliar ASC', null, null, null);

		  foreach($auxiliar as $datos){

		     $var1 = $datos['cfpd01_auxiliar']['cod_grupo'];
			 $var2 = $datos['cfpd01_auxiliar']['cod_partida'];
			 $var3 = $datos['cfpd01_auxiliar']['cod_generica'];
			 $var4 = $datos['cfpd01_auxiliar']['cod_especifica'];
			 $var5 = $datos['cfpd01_auxiliar']['cod_sub_espec'];
			 $var6 = $datos['cfpd01_auxiliar']['cod_auxiliar'];
			 $concepto = $datos['cfpd01_auxiliar']['concepto'];
			 $descripcion = $datos['cfpd01_auxiliar']['descripcion'];

		 $codigos = "cod_grupo, cod_partida, cod_generica, cod_especifica, cod_sub_espec, cod_auxiliar,";
		 $values =  " '".$var1."',  '".$var2."',    '".$var3."', '".$var4."', '".$var5."', '".$var6."', ";
		 $tabla='cfpd01_ano_auxiliar';

$sql_aux = "ejercicio = ".$ejercicio." and cod_grupo='".$var1."' and  cod_partida='".$var2."' and  cod_generica='".$var3."' and  cod_especifica='".$var4."' and cod_sub_espec='".$var5."'  and cod_auxiliar='".$var6."'";
if($this->cfpd01_ano_auxiliar->findCount($sql_aux) == 0){
$sql_1 = "INSERT INTO cfpd01_ano_6_auxiliar   (ejercicio,  ".$codigos."  concepto, denominacion)   VALUES  ( '".$ejercicio."',  ".$values."   '$concepto', '$descripcion' )  ";
$this->$tabla->execute($sql_1); }

		 }//fin foreach



    $this->layout = "ajax";
	$grupo="";

	$grupo = $this->cfpd01_ano_grupo->generateList('ejercicio ='.$ejercicio.'', 'cod_grupo ASC', null, '{n}.cfpd01_ano_grupo.cod_grupo', '{n}.cfpd01_ano_grupo.denominacion');
   	$this->concatena($grupo,'grupo');

    $this->set('Message', 'Fue traspasado del clasificador nacional para el del ejercicio actual');

   if($grupo==""){$this->set('traspaso', 'si');}else{$this->set('traspaso', 'no');}


}//fin function











 function traspaso_a_otros($ejercicio_desde=null, $ejercicio_hasta=null){

    $this->layout = "ajax";
	//$this->set('ejercicio', $ejercicio);


	//$ejercicio = $this->data['cfpp01']['ano'];

	$codigos = "";
	$values = "";

	$ejercicio_aux = $this->data['cfpd01']['al'];
	$ejercicio = $this->data['cfpd01']['de'];;
	$this->set('ejercicio', $ejercicio_aux);


	 	 $grupo = $this->cfpd01_ano_grupo->findAll('ejercicio='.$ejercicio.'', null, 'cod_grupo ASC', null, null, null);


		 foreach($grupo as $datos){

		     $var1 = $datos['cfpd01_ano_grupo']['cod_grupo'];
			 $concepto = $datos['cfpd01_ano_grupo']['concepto'];
			 $descripcion = $datos['cfpd01_ano_grupo']['denominacion'];

		 		$codigos = "cod_grupo, ";
				$values =  " '".$var1."',  " ;
				$tabla='cfpd01_ano_grupo';

$sql_aux = "ejercicio = ".$ejercicio_aux." and cod_grupo='".$var1."'  ";
if($this->cfpd01_ano_grupo->findCount($sql_aux) == 0){
$sql_1 = "INSERT INTO  cfpd01_ano_1_grupo  (ejercicio,  ".$codigos."  concepto, denominacion)   VALUES  ( '".$ejercicio_aux."',  ".$values."   '$concepto', '$descripcion' )  ";
$this->$tabla->execute($sql_1);}

		 }//fin foreach


		 $partida = $this->cfpd01_ano_partida->findAll('ejercicio='.$ejercicio.'', null, 'cod_partida ASC', null, null, null);

		  foreach($partida as $datos){

		     $var1 = $datos['cfpd01_ano_partida']['cod_grupo'];
			 $var2 = $datos['cfpd01_ano_partida']['cod_partida'];
			 $concepto = $datos['cfpd01_ano_partida']['concepto'];
			 $descripcion = $datos['cfpd01_ano_partida']['denominacion'];


				 $codigos = "cod_grupo, cod_partida, ";
				 $values =  " '".$var1."', '".$var2."',  ";
				 $tabla='cfpd01_ano_partida';

$sql_aux = "ejercicio = ".$ejercicio_aux." and cod_grupo='".$var1."' and  cod_partida='".$var2."'  ";
 if($this->cfpd01_ano_partida->findCount($sql_aux) == 0){
$sql_1 = "INSERT INTO  cfpd01_ano_2_partida   (ejercicio,  ".$codigos."  concepto, denominacion)   VALUES  ( '".$ejercicio_aux."',  ".$values."   '$concepto', '$descripcion' )  ";
$this->$tabla->execute($sql_1);}

		 }//fin foreach






		 $generica = $this->cfpd01_ano_generica->findAll('ejercicio='.$ejercicio.'', null, 'cod_generica ASC', null, null, null);

		 foreach($generica as $datos){

		     $var1 = $datos['cfpd01_ano_generica']['cod_grupo'];
			 $var2 = $datos['cfpd01_ano_generica']['cod_partida'];
			 $var3 = $datos['cfpd01_ano_generica']['cod_generica'];
			 $concepto = $datos['cfpd01_ano_generica']['concepto'];
			 $descripcion = $datos['cfpd01_ano_generica']['denominacion'];

		 	  	 $codigos = "cod_grupo,  cod_partida,  cod_generica, ";
				 $values =  " '".$var1."', '".$var2."', '".$var3."', ";
				 $tabla='cfpd01_ano_generica';

$sql_aux = "ejercicio = ".$ejercicio_aux." and cod_grupo='".$var1."' and  cod_partida='".$var2."' and  cod_generica='".$var3."'  ";
if($this->cfpd01_ano_generica->findCount($sql_aux) == 0){
 $sql_1 = "INSERT INTO  cfpd01_ano_3_generica   (ejercicio,  ".$codigos."  concepto, denominacion)   VALUES  ( '".$ejercicio_aux."',  ".$values."   '$concepto', '$descripcion' )  ";
 $this->$tabla->execute($sql_1);}


		 }//fin foreach





		  $especifica = $this->cfpd01_ano_especifica->findAll('ejercicio='.$ejercicio.'', null, 'cod_especifica ASC', null, null, null);

		   foreach($especifica as $datos){

		     $var1 = $datos['cfpd01_ano_especifica']['cod_grupo'];
			 $var2 = $datos['cfpd01_ano_especifica']['cod_partida'];
			 $var3 = $datos['cfpd01_ano_especifica']['cod_generica'];
			 $var4 = $datos['cfpd01_ano_especifica']['cod_especifica'];
			 $concepto = $datos['cfpd01_ano_especifica']['concepto'];
			 $descripcion = $datos['cfpd01_ano_especifica']['denominacion'];

		  $codigos = "cod_grupo, cod_partida, cod_generica, cod_especifica,";
		  $values =  " '".$var1."',  '".$var2."',    '".$var3."', '".$var4."', ";
	      $tabla='cfpd01_ano_especifica';

$sql_aux = "ejercicio = ".$ejercicio_aux." and cod_grupo='".$var1."' and  cod_partida='".$var2."' and  cod_generica='".$var3."' and  cod_especifica='".$var4."'   ";
 if($this->cfpd01_ano_especifica->findCount($sql_aux) == 0){
 $sql_1 = "INSERT INTO  cfpd01_ano_4_especifica   (ejercicio,  ".$codigos."  concepto, denominacion)   VALUES  ( '".$ejercicio_aux."',  ".$values."   '$concepto', '$descripcion' )  ";
$this->$tabla->execute($sql_1);}


$sql_aux = "where ejercicio = ".$ejercicio_aux." and cod_grupo='".$var1."' and  cod_partida='".$var2."' and  cod_generica='".$var3."' and  cod_especifica='".$var4."' and cod_sub_espec='0'  ";
if($this->cfpd01_ano_sub_espec->findCount($sql_aux) == 0){
$codigos_aux = "cod_grupo, cod_partida, cod_generica, cod_especifica, cod_sub_espec,";
$values_aux =  " '".$var1."',  '".$var2."',   '".$var3."', '".$var4."', '0', ";
$sql_2_aux = "INSERT INTO  cfpd01_ano_5_sub_espec   (ejercicio,  ".$codigos_aux."  concepto, denominacion)   VALUES  ( '".$ejercicio_aux."',  ".$values_aux."   '$concepto', '$descripcion' )  ";
$this->cfpd01_ano_sub_espec->execute($sql_2_aux);}



 }//fin foreach







		  $subespecifica = $this->cfpd01_ano_sub_espec->findAll('ejercicio='.$ejercicio.'', null, 'cod_sub_espec ASC', null, null, null);

		   foreach($subespecifica as $datos){

		     $var1 = $datos['cfpd01_ano_sub_espec']['cod_grupo'];
			 $var2 = $datos['cfpd01_ano_sub_espec']['cod_partida'];
			 $var3 = $datos['cfpd01_ano_sub_espec']['cod_generica'];
			 $var4 = $datos['cfpd01_ano_sub_espec']['cod_especifica'];
			 $var5 = $datos['cfpd01_ano_sub_espec']['cod_sub_espec'];
			 $concepto = $datos['cfpd01_ano_sub_espec']['concepto'];
			 $descripcion = $datos['cfpd01_ano_sub_espec']['denominacion'];

		  $codigos = "cod_grupo, cod_partida, cod_generica, cod_especifica, cod_sub_espec,";
		  $values =  " '".$var1."',  '".$var2."',    '".$var3."', '".$var4."', '".$var5."', ";
		  $tabla='cfpd01_ano_sub_espec';


$sql_aux = "ejercicio = ".$ejercicio_aux." and cod_grupo='".$var1."' and  cod_partida='".$var2."' and  cod_generica='".$var3."' and  cod_especifica='".$var4."' and cod_sub_espec='".$var5."'  ";
if($this->cfpd01_ano_sub_espec->findCount($sql_aux) == 0){
$sql_1 = "INSERT INTO  cfpd01_ano_5_sub_espec   (ejercicio,  ".$codigos."  concepto, denominacion)   VALUES  ( '".$ejercicio_aux."',  ".$values."   '$concepto', '$descripcion' )  ";
$this->$tabla->execute($sql_1);}

 }//fin foreach







		 $auxiliar = $this->cfpd01_ano_auxiliar->findAll('ejercicio='.$ejercicio.'', null, 'cod_auxiliar ASC', null, null, null);

		  foreach($auxiliar as $datos){

		     $var1 = $datos['cfpd01_ano_auxiliar']['cod_grupo'];
			 $var2 = $datos['cfpd01_ano_auxiliar']['cod_partida'];
			 $var3 = $datos['cfpd01_ano_auxiliar']['cod_generica'];
			 $var4 = $datos['cfpd01_ano_auxiliar']['cod_especifica'];
			 $var5 = $datos['cfpd01_ano_auxiliar']['cod_sub_espec'];
			 $var6 = $datos['cfpd01_ano_auxiliar']['cod_auxiliar'];
			 $concepto = $datos['cfpd01_ano_auxiliar']['concepto'];
			 $descripcion = $datos['cfpd01_ano_auxiliar']['denominacion'];

		 $codigos = "cod_grupo, cod_partida, cod_generica, cod_especifica, cod_sub_espec, cod_auxiliar,";
		 $values =  " '".$var1."',  '".$var2."',    '".$var3."', '".$var4."', '".$var5."', '".$var6."', ";
		 $tabla='cfpd01_ano_auxiliar';

$sql_aux = "where ejercicio = ".$ejercicio_aux." and cod_grupo='".$var1."' and  cod_partida='".$var2."' and  cod_generica='".$var3."' and  cod_especifica='".$var4."' and cod_sub_espec='".$var5."'  and cod_auxiliar='".$var6."'";
if($this->cfpd01_ano_auxiliar->findCount($sql_aux) == 0){
 $sql_1 = "INSERT INTO cfpd01_ano_6_auxiliar   (ejercicio,  ".$codigos."  concepto, denominacion)   VALUES  ( '".$ejercicio_aux."',  ".$values."   '$concepto', '$descripcion' )  ";
$this->$tabla->execute($sql_1);}

}//fin foreach


    $this->layout = "ajax";
	$grupo="";

	$grupo = $this->cfpd01_ano_grupo->generateList('ejercicio ='.$ejercicio_aux.'', 'cod_grupo ASC', null, '{n}.cfpd01_ano_grupo.cod_grupo', '{n}.cfpd01_ano_grupo.denominacion');
   	$this->concatena($grupo, 'grupo');

    $this->set('Message', 'Fue traspasado el clasificador del ejercicio '.$ejercicio.'  al ejercicio  '.$ejercicio_aux.' ');

   if($grupo==""){$this->set('traspaso', 'si');}else{$this->set('traspaso', 'no');}


}//fin function






function crear_grupo_plancuentas(){
	$this->layout="ajax";
	echo "CREANDO EL GRUPO<br />";

	$cp = $this->Session->read('SScodpresi');
	$ce = $this->Session->read('SScodentidad');
	$cti = $this->Session->read('SScodtipoinst');
	$ci = $this->Session->read('SScodinst');
	$cd = $this->Session->read('SScoddep');
	$condicion = "cod_presi = ".$cp." and cod_entidad = ".$ce." and cod_tipo_inst = ".$cti." and cod_inst = ".$ci." and cod_dep=1";

	$datos = $this->cfpd01_grupo->findAll('cod_grupo=3');

	$cod_tipo_cuenta = 2;
	foreach($datos as $d){
		$cod_grupo = $d['cfpd01_grupo']['cod_grupo']."01";
		$descripcion = $d['cfpd01_grupo']['descripcion'];
		$concepto = $d['cfpd01_grupo']['concepto'];

		$condicion_2 = $condicion." and cod_tipo_cuenta='$cod_tipo_cuenta' and cod_cuenta='$cod_grupo'";
		$count = $this->ccfd01_cuenta->findCount($condicion_2);
		if($count==0){
			$sql = "INSERT INTO ccfd01_cuenta VALUES ('$cp', '$ce', '$cti', '$ci', 1, '$cod_tipo_cuenta', '$cod_grupo', '$descripcion', '$concepto');";
			if($this->ccfd01_cuenta->execute($sql) > 1){
				echo "<br />La cuenta se creo de manera correcta";
			}else{
				echo "<br />La cuenta pudo ser creada";
			}
		}else{
			echo "<br />no se creo ya existe en el plan de cuentas";
		}
	}

	echo "<br /><br />CREACION DE LAS CUENTAS 1 - 122 CONTRAPARTE DE LAS 2 - 301<br />";

	$cod_tipo_cuenta = 1;
	foreach($datos as $d){
		$cod_grupo = 122;//$d['cfpd01_grupo']['cod_grupo']."01";
		$descripcion = $d['cfpd01_grupo']['descripcion'];
		$concepto = $d['cfpd01_grupo']['concepto'];

		$condicion_2 = $condicion." and cod_tipo_cuenta='$cod_tipo_cuenta' and cod_cuenta='$cod_grupo'";
		$count = $this->ccfd01_cuenta->findCount($condicion_2);
		if($count==0){
			$sql = "INSERT INTO ccfd01_cuenta VALUES ('$cp', '$ce', '$cti', '$ci', 1, '$cod_tipo_cuenta', '$cod_grupo', '$descripcion', '$concepto');";
			if($this->ccfd01_cuenta->execute($sql) > 1){
				echo "<br />La cuenta se creo de manera correcta";
			}else{
				echo "<br />La cuenta pudo ser creada";
			}
		}else{
			echo "<br />no se creo ya existe en el plan de cuentas";
		}
	}
	$this->render('vaciar');

}

function crear_partida_plancuentas(){
	$this->layout="ajax";
	echo "CREANDO LAS PARTIDAS<br />";

	$cp = $this->Session->read('SScodpresi');
	$ce = $this->Session->read('SScodentidad');
	$cti = $this->Session->read('SScodtipoinst');
	$ci = $this->Session->read('SScodinst');
	$cd = $this->Session->read('SScoddep');
	$condicion = "cod_presi = ".$cp." and cod_entidad = ".$ce." and cod_tipo_inst = ".$cti." and cod_inst = ".$ci." and cod_dep=1";

	$datos = $this->cfpd01_partida->findAll('cod_grupo=3');

	$cod_tipo_cuenta = 2;
	foreach($datos as $d){
		$cod_cuenta = $d['cfpd01_partida']['cod_grupo']."01";
		$cod_subcuenta = $d['cfpd01_partida']['cod_partida'];
		$descripcion = $d['cfpd01_partida']['descripcion'];
		$concepto = $d['cfpd01_partida']['concepto'];

		$condicion_2 = $condicion." and cod_tipo_cuenta='$cod_tipo_cuenta' and cod_cuenta='$cod_cuenta' and cod_subcuenta='$cod_subcuenta'";
		$count = $this->ccfd01_subcuenta->findCount($condicion_2);
		if($count==0){
			$sql = "INSERT INTO ccfd01_subcuenta VALUES ('$cp', '$ce', '$cti', '$ci', 1, '$cod_tipo_cuenta', '$cod_cuenta', '$cod_subcuenta', '$descripcion', '$concepto');";
			if($this->ccfd01_subcuenta->execute($sql) > 1){
				echo "<br />La cuenta se creo de manera correcta";
			}else{
				echo "<br />La cuenta pudo ser creada";
			}
		}else{
			echo "<br />no se creo ya existe en el plan de cuentas";
		}
	}

	echo "<br /><br />CREACION DE LAS CUENTAS 1 - 122 CONTRAPARTE DE LAS 2 - 301<br />";

	$cod_tipo_cuenta = 1;
	foreach($datos as $d){
		$cod_cuenta = 122;//$d['cfpd01_partida']['cod_grupo']."01";
		$cod_subcuenta = $d['cfpd01_partida']['cod_partida'];
		$descripcion = $d['cfpd01_partida']['descripcion'];
		$concepto = $d['cfpd01_partida']['concepto'];

		$condicion_2 = $condicion." and cod_tipo_cuenta='$cod_tipo_cuenta' and cod_cuenta='$cod_cuenta' and cod_subcuenta='$cod_subcuenta'";
		$count = $this->ccfd01_subcuenta->findCount($condicion_2);
		if($count==0){
			$sql = "INSERT INTO ccfd01_subcuenta VALUES ('$cp', '$ce', '$cti', '$ci', 1, '$cod_tipo_cuenta', '$cod_cuenta', '$cod_subcuenta', '$descripcion', '$concepto');";
			if($this->ccfd01_subcuenta->execute($sql) > 1){
				echo "<br />La cuenta se creo de manera correcta";
			}else{
				echo "<br />La cuenta pudo ser creada";
			}
		}else{
			echo "<br />no se creo ya existe en el plan de cuentas";
		}
	}

	$this->render('vaciar');
}

function crear_generica_plancuentas(){
	$this->layout="ajax";
	echo "CREANDO LAS GENERICAS<br />";

	$cp = $this->Session->read('SScodpresi');
	$ce = $this->Session->read('SScodentidad');
	$cti = $this->Session->read('SScodtipoinst');
	$ci = $this->Session->read('SScodinst');
	$cd = $this->Session->read('SScoddep');
	$condicion = "cod_presi = ".$cp." and cod_entidad = ".$ce." and cod_tipo_inst = ".$cti." and cod_inst = ".$ci." and cod_dep=1";

	$datos = $this->cfpd01_generica->findAll('cod_grupo=3');

	$cod_tipo_cuenta = 2;
	foreach($datos as $d){
		$cod_cuenta = $d['cfpd01_generica']['cod_grupo']."01";
		$cod_subcuenta = $d['cfpd01_generica']['cod_partida'];
		$cod_division = $d['cfpd01_generica']['cod_generica'];
		$descripcion = $d['cfpd01_generica']['descripcion'];
		$concepto = $d['cfpd01_generica']['concepto'];

		$condicion_2 = $condicion." and cod_tipo_cuenta='$cod_tipo_cuenta' and cod_cuenta='$cod_cuenta' and cod_subcuenta='$cod_subcuenta' and cod_division='$cod_division'";
		$count = $this->ccfd01_division->findCount($condicion_2);
		if($count==0){
			$sql = "INSERT INTO ccfd01_division VALUES ('$cp', '$ce', '$cti', '$ci', 1, '$cod_tipo_cuenta', '$cod_cuenta', '$cod_subcuenta', '$cod_division', '$descripcion', '$concepto');";
			if($this->ccfd01_division->execute($sql) > 1){
				echo "<br />La cuenta se creo de manera correcta";
			}else{
				echo "<br />La cuenta pudo ser creada";
			}
		}else{
			echo "<br />no se creo ya existe en el plan de cuentas";
		}
	}

	echo "<br /><br />CREACION DE LAS CUENTAS 1 - 122 CONTRAPARTE DE LAS 2 - 301<br />";

	$cod_tipo_cuenta = 1;
	foreach($datos as $d){
		$cod_cuenta = 122;//$d['cfpd01_generica']['cod_grupo']."01";
		$cod_subcuenta = $d['cfpd01_generica']['cod_partida'];
		$cod_division = $d['cfpd01_generica']['cod_generica'];
		$descripcion = $d['cfpd01_generica']['descripcion'];
		$concepto = $d['cfpd01_generica']['concepto'];

		$condicion_2 = $condicion." and cod_tipo_cuenta='$cod_tipo_cuenta' and cod_cuenta='$cod_cuenta' and cod_subcuenta='$cod_subcuenta' and cod_division='$cod_division'";
		$count = $this->ccfd01_division->findCount($condicion_2);
		if($count==0){
			$sql = "INSERT INTO ccfd01_division VALUES ('$cp', '$ce', '$cti', '$ci', 1, '$cod_tipo_cuenta', '$cod_cuenta', '$cod_subcuenta', '$cod_division', '$descripcion', '$concepto');";
			if($this->ccfd01_division->execute($sql) > 1){
				echo "<br />La cuenta se creo de manera correcta";
			}else{
				echo "<br />La cuenta pudo ser creada";
			}
		}else{
			echo "<br />no se creo ya existe en el plan de cuentas";
		}
	}
	$this->render('vaciar');
}

function crear_especifica_plancuentas(){
	$this->layout="ajax";
	echo "CREANDO LAS ESPECIFICAS<br />";

	$cp = $this->Session->read('SScodpresi');
	$ce = $this->Session->read('SScodentidad');
	$cti = $this->Session->read('SScodtipoinst');
	$ci = $this->Session->read('SScodinst');
	$cd = $this->Session->read('SScoddep');
	$condicion = "cod_presi = ".$cp." and cod_entidad = ".$ce." and cod_tipo_inst = ".$cti." and cod_inst = ".$ci." and cod_dep=1";

	$datos = $this->cfpd01_especifica->findAll('cod_grupo=3');

	$cod_tipo_cuenta = 2;
	foreach($datos as $d){
		$cod_cuenta = $d['cfpd01_especifica']['cod_grupo']."01";
		$cod_subcuenta = $d['cfpd01_especifica']['cod_partida'];
		$cod_division = $d['cfpd01_especifica']['cod_generica'];
		$cod_subdivision = $d['cfpd01_especifica']['cod_especifica'];
		$descripcion = $d['cfpd01_especifica']['descripcion'];
		$concepto = $d['cfpd01_especifica']['concepto'];

		$condicion_2 = $condicion." and cod_tipo_cuenta='$cod_tipo_cuenta' and cod_cuenta='$cod_cuenta' and cod_subcuenta='$cod_subcuenta' and cod_division='$cod_division' and cod_subdivision='$cod_subdivision'";
		$count = $this->ccfd01_subdivision->findCount($condicion_2);
		if($count==0){
			$sql = "INSERT INTO ccfd01_subdivision VALUES ('$cp', '$ce', '$cti', '$ci', 1, '$cod_tipo_cuenta', '$cod_cuenta', '$cod_subcuenta', '$cod_division', '$cod_subdivision', '$descripcion', '$concepto');";
			if($this->ccfd01_subdivision->execute($sql) > 1){
				echo "<br />La cuenta se creo de manera correcta";
			}else{
				echo "<br />La cuenta pudo ser creada";
			}
		}else{
			echo "<br />no se creo ya existe en el plan de cuentas";
		}
	}

	echo "<br /><br />CREACION DE LAS CUENTAS 1 - 122 CONTRAPARTE DE LAS 2 - 301<br />";

	$cod_tipo_cuenta = 1;
	foreach($datos as $d){
		$cod_cuenta = 122;//$d['cfpd01_especifica']['cod_grupo']."01";
		$cod_subcuenta = $d['cfpd01_especifica']['cod_partida'];
		$cod_division = $d['cfpd01_especifica']['cod_generica'];
		$cod_subdivision = $d['cfpd01_especifica']['cod_especifica'];
		$descripcion = $d['cfpd01_especifica']['descripcion'];
		$concepto = $d['cfpd01_especifica']['concepto'];

		$condicion_2 = $condicion." and cod_tipo_cuenta='$cod_tipo_cuenta' and cod_cuenta='$cod_cuenta' and cod_subcuenta='$cod_subcuenta' and cod_division='$cod_division' and cod_subdivision='$cod_subdivision'";
		$count = $this->ccfd01_subdivision->findCount($condicion_2);
		if($count==0){
			$sql = "INSERT INTO ccfd01_subdivision VALUES ('$cp', '$ce', '$cti', '$ci', 1, '$cod_tipo_cuenta', '$cod_cuenta', '$cod_subcuenta', '$cod_division', '$cod_subdivision', '$descripcion', '$concepto');";
			if($this->ccfd01_subdivision->execute($sql) > 1){
				echo "<br />La cuenta se creo de manera correcta";
			}else{
				echo "<br />La cuenta pudo ser creada";
			}
		}else{
			echo "<br />no se creo ya existe en el plan de cuentas";
		}
	}
	$this->render('vaciar');
}










 }//FIN CLASS



?>
