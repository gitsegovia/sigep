<?php
class Cnmp10comunes52semanasporcentajededController extends AppController{

	var $name = 'cnmp10_comunes52_semanas_porcentaje_ded';
	var $uses = array('Cnmd01','cnmd01', 'cnmd03_transacciones', 'ccfd03_instalacion', 'cnmd10_control_de_escenarios' ,'v_cnmd05', 'cnmd05', 'cnmd10_comunes_52semanas_porcentaje_ded','ccfd04_cierre_mes','cnmd10_control_escenarios');
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

	$cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$modulo = $this->Session->read('Modulo');

}

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
		foreach($vector1 as $x => $y){$cod[$x] = $this->zero($x).' - '.$y;}
		$this->set($nomVar, $cod);
	}//fin if
}//fin concatena







 function index($var=null){

  $this->layout = "ajax";


    $cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep;
    $Lista = "";


if($var != null && $var=='1' || $var == '0'){
		$this->set('opc', $var);
		if($var == 0){
			$this->set('enabled','READONLY');
		}else{
			$this->set('enabled','');
		}
		//echo "gloria a Dios ".$var;
	}else{
		$this->set('opc', '1');
		$this->set('enabled','');
	}
 	//$Lista = $this->v_cnmd05->generateList($condicion, 'cod_tipo_nomina ASC', null, '{n}.v_cnmd05.cod_tipo_nomina', '{n}.v_cnmd05.tipo_nomina');
   	//$this->concatena($Lista, 'cod_tipo_nomina');
   	//$this->set('cod_puesto','');
$lista = $this->Cnmd01->generateList($conditions = $this->condicion(), $order = 'cod_tipo_nomina', $limit = null, '{n}.Cnmd01.cod_tipo_nomina', '{n}.Cnmd01.denominacion');
	$this->concatenaN($lista, 'nomina');


}///fin function






function selecion_nomina($var1=null){

    $this->layout = "ajax";


    $cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep;



if($var1==null){

 echo'<script>';
      echo'document.getElementById("codigo_nomina").innerHTML = "<br>"; ';
      echo'document.getElementById("denominacion_nomina").innerHTML = "<br>"; ';
 	  echo'document.getElementById("codigo_transaccion").innerHTML = \'<input type="text" name="data[ccfp01_subdivision][deno_subdivision_contable]" value=""  style="width:98%;text-align:center" readonly="readonly" />\'; ';
      echo'document.getElementById("denominacion_transaccion").innerHTML = \'<input type="text" name="data[ccfp01_subdivision][deno_subdivision_contable]" value=""  style="width:98%;text-align:leght" readonly="readonly" />\'; ';
      echo'document.getElementById("select_transaccion").innerHTML = "<select id=cod_transaccion><option></opction</select>"; ';
      echo'document.getElementById("porcentaje").disabled = true; ';
      echo'document.getElementById("tope_cuarta_semana").disabled = true; ';
      echo'document.getElementById("tope_quinta_semana").disabled = true; ';
      echo'document.getElementById("guardar").disabled = true; ';
 echo'</script>';


}else{


	/*
     $fichas  =   $this->v_cnmd05->findAll($condicion.' and cod_tipo_nomina='.$var1.'');
     foreach($fichas as $aux){

        $denominacion_nomina = $aux['v_cnmd05']['tipo_nomina'];

     }//fin for
     */
     $denominacion_nomina = $this->Cnmd01->field('denominacion', $conditions = $condicion." and Cnmd01.cod_tipo_nomina='$var1'", $order ="cod_tipo_nomina ASC");

if($var1<=9){$var1 = '0'.$var1; }
       echo'<script>';
            echo'document.getElementById("codigo_nomina").innerHTML = \'<input type="text" name="data[ccfp01_subdivision][deno_subdivision_contable]" value="'.$var1.'"  style="width:98%;text-align:center" readonly="readonly" />\'; ';
            echo'document.getElementById("denominacion_nomina").innerHTML = \'<input type="text" name="data[ccfp01_subdivision][deno_subdivision_contable]" value="'.$denominacion_nomina.'"  style="width:98%;text-align:leght" readonly="readonly" />\'; ';
            //echo'document.getElementById("denominacion_nomina").innerHTML = "'.$denominacion_nomina.'"; ';
            echo'document.getElementById("codigo_transaccion").innerHTML = \'<input type="text" name="data[ccfp01_subdivision][deno_subdivision_contable]" value=""  style="width:98%;text-align:center" readonly="readonly" />\'; ';
            echo'document.getElementById("denominacion_transaccion").innerHTML = \'<input type="text" name="data[ccfp01_subdivision][deno_subdivision_contable]" value=""  style="width:98%;text-align:leght" readonly="readonly" />\'; ';
//            echo'document.getElementById("codigo_transaccion").innerHTML = "<br>"; ';
//            echo'document.getElementById("denominacion_transaccion").innerHTML = "<br>"; ';
            echo'document.getElementById("porcentaje").disabled = true; ';
            echo'document.getElementById("tope_cuarta_semana").disabled = true; ';
            echo'document.getElementById("tope_quinta_semana").disabled = true; ';
            echo'document.getElementById("guardar").disabled = true; ';
       echo'</script>';



}//fin else

    $Lista = '';
    //BLOQUEO FUSAMIEBG
    $cod_dep = $this->Session->read('SScoddep');
    if($cod_dep!=1){
      $conditions ="cod_tipo_transaccion=2 and cod_transaccion!=103";
    }else{
      $conditions ="cod_tipo_transaccion=2";
    }
    $Lista = $this->cnmd03_transacciones->generateList2($conditions, 'cod_transaccion ASC', null, '{n}.cnmd03_transacciones.cod_transaccion', '{n}.cnmd03_transacciones.denominacion');

    if($Lista == ""){
        $this->set('lista_transacciones', $Lista);
    }else{
        $this->concatenaN($Lista, 'lista_transacciones');
    }//fin else

     if($var1!=null){$this->set('var1',$var1);}else{$this->set('var1','');}

}//fin function







function select_cod_ficha($var1=null, $var2=null){

    $this->layout = "ajax";
    $cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep;
    $denominacion_transaccion = "";
    $porcentaje = "";
    $tope_cuarta_semana = "";
    $tope_quinta_semana = "";
    $ubicacion_escanario_aux  = "DEDUCCIÓN COMÚN EN PORCENTAJE (BASE CALCULO 52 SEMANAS)";




if($var2<=9 && $var2!=null){$var2 = '0'.$var2; }



if($var2!=null){


	$fichas  =   $this->cnmd03_transacciones->findAll('cod_tipo_transaccion=2 and cod_transaccion='.$var2);

     foreach($fichas as $aux){
        $denominacion_transaccion = $aux['cnmd03_transacciones']['denominacion'];
     }//fin for



     $fichas2  =   $this->cnmd10_comunes_52semanas_porcentaje_ded->findAll($condicion.' and cod_tipo_nomina='.$var1.' and cod_tipo_transaccion = 2 and cod_transaccion = '.$var2.'');


 foreach($fichas2 as $aux2){
        $porcentaje = $aux2['cnmd10_comunes_52semanas_porcentaje_ded']['porcentaje'];
        $tope_cuarta_semana = $aux2['cnmd10_comunes_52semanas_porcentaje_ded']['tope_cuarta_semana'];
        $tope_quinta_semana = $aux2['cnmd10_comunes_52semanas_porcentaje_ded']['tope_quinta_semana'];
     }//fin for






if($this->cnmd10_control_de_escenarios->findCount($condicion.' and cod_tipo_nomina='.$var1.' and cod_tipo_transaccion = 2 and cod_transaccion = '.$var2.' ')==0){


 echo'<script>';
      echo'document.getElementById("codigo_transaccion").innerHTML = \'<input type="text" name="data[ccfp01_subdivision][deno_subdivision_contable]" value="'.$var2.'"  style="width:98%;text-align:center" readonly="readonly" />\'; ';
 	  echo'document.getElementById("denominacion_transaccion").innerHTML = \'<input type="text" name="data[ccfp01_subdivision][deno_subdivision_contable]" value="'.$denominacion_transaccion.'"  style="width:98%;text-align:leght" readonly="readonly" />\'; ';
      echo'document.getElementById("porcentaje").disabled = false; ';
      echo'document.getElementById("tope_cuarta_semana").disabled = false; ';
      echo'document.getElementById("tope_quinta_semana").disabled = false; ';
      echo'document.getElementById("guardar").disabled = false; ';

      echo'document.getElementById("porcentaje").value = "'.$this->Formato2($porcentaje).'"; ';
      echo'document.getElementById("tope_cuarta_semana").value = "'.$this->Formato2($tope_cuarta_semana).'"; ';
      echo'document.getElementById("tope_quinta_semana").value = "'.$this->Formato2($tope_quinta_semana).'"; ';

   if($porcentaje!="" && $tope_cuarta_semana!="" && $tope_quinta_semana!=""){
      echo'document.getElementById("guardar").disabled = true; ';
//      echo'document.getElementById("modificar").disabled = true; ';
//      echo'document.getElementById("eliminar").disabled = true; ';
   }else{
   	  echo'document.getElementById("guardar").disabled = false; ';
//      echo'document.getElementById("modificar").disabled = true; ';
//      echo'document.getElementById("eliminar").disabled = true; ';
   }//fin else

 echo'</script>';





}else{


//echo $condicion.' and cod_tipo_nomina='.$var1.' and cod_tipo_transaccion = 2 and cod_transaccion = '.$var2.' ';
$fichas3 = $this->cnmd10_control_de_escenarios->findAll($condicion.' and cod_tipo_nomina='.$var1.' and cod_tipo_transaccion = 2 and cod_transaccion = '.$var2.' ');
//echo $fichas3;
foreach($fichas3 as $aux3){$ubicacion_escanario = $aux3['cnmd10_control_de_escenarios']['ubicacion_escenario'];}//fin for

if($ubicacion_escanario == $ubicacion_escanario_aux){

echo'<script>';
      echo'document.getElementById("codigo_transaccion").innerHTML = \'<input type="text" name="data[ccfp01_subdivision][deno_subdivision_contable]" value="'.$var2.'"  style="width:98%;text-align:center" readonly="readonly" />\'; ';
 	  echo'document.getElementById("denominacion_transaccion").innerHTML = \'<input type="text" name="data[ccfp01_subdivision][deno_subdivision_contable]" value="'.$denominacion_transaccion.'"  style="width:98%;text-align:leght" readonly="readonly" />\'; ';
      echo'document.getElementById("porcentaje").disabled = false; ';
      echo'document.getElementById("tope_cuarta_semana").disabled = false; ';
      echo'document.getElementById("tope_quinta_semana").disabled = false; ';
      echo'document.getElementById("guardar").disabled = false; ';

      echo'document.getElementById("porcentaje").value = "'.$this->Formato2($porcentaje).'"; ';
      echo'document.getElementById("tope_cuarta_semana").value = "'.$this->Formato2($tope_cuarta_semana).'"; ';
      echo'document.getElementById("tope_quinta_semana").value = "'.$this->Formato2($tope_quinta_semana).'"; ';

   if($porcentaje!="" && $tope_cuarta_semana!="" && $tope_quinta_semana!=""){
      echo'document.getElementById("guardar").disabled = true; ';
//      echo'document.getElementById("modificar").disabled = true; ';
//      echo'document.getElementById("eliminar").disabled = true; ';
   }else{
   	  echo'document.getElementById("guardar").disabled = false; ';
//      echo'document.getElementById("modificar").disabled = true; ';
//      echo'document.getElementById("eliminar").disabled = true; ';
   }//fin else

 echo'</script>';



}else{$this->set('errorMessage', 'Los datos ya existen en '.$ubicacion_escanario);}





}//fin else






}else{


echo'<script>';
     echo'document.getElementById("codigo_transaccion").innerHTML = \'<input type="text" name="data[ccfp01_subdivision][deno_subdivision_contable]" value=""  style="width:98%;text-align:center" readonly="readonly" />\'; ';
     echo'document.getElementById("denominacion_transaccion").innerHTML = \'<input type="text" name="data[ccfp01_subdivision][deno_subdivision_contable]" value=""  style="width:98%;text-align:leght" readonly="readonly" />\'; ';
      echo'document.getElementById("porcentaje").disabled = true; ';
      echo'document.getElementById("tope_cuarta_semana").disabled = true; ';
      echo'document.getElementById("tope_quinta_semana").disabled = true; ';
      echo'document.getElementById("guardar").disabled = true; ';

      echo'document.getElementById("porcentaje").value = ""; ';
      echo'document.getElementById("tope_cuarta_semana").value = ""; ';
      echo'document.getElementById("tope_quinta_semana").value = ""; ';

      echo'document.getElementById("guardar").disabled = true; ';
//      echo'document.getElementById("modificar").disabled = true; ';
//      echo'document.getElementById("eliminar").disabled = true; ';
 echo'</script>';


}//finj else


if($var1!=null){$this->set('var1',$var1);}else{$this->set('var1','');}
if($var2!=null){$this->set('var2',$var2);}else{$this->set('var2','');}




}//fin function






function mostrar_datos_griya($var1=null, $var2=null, $var3=null){

	$this->layout = "ajax";
//	echo "hola";
    $cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep;



if($var1=='null' && $var2!=null && $var3!=null){
$this->eliminar($var2, $var3);
$var1 = $var2;

}//fin if



$fichas = "";
$datos_cnmd03_transacciones = "";




if($var1==null){
	if(isset($this->data['cnmp10_comunes52_semanas_porcentaje_ded']['cod_tipo_nomina'])){
		$var1    =    $this->data['cnmp10_comunes52_semanas_porcentaje_ded']['cod_tipo_nomina'];
	}//fin function
}//fin function



if($var1!=null){
    $fichas = "";
    $fichas  =   $this->cnmd10_comunes_52semanas_porcentaje_ded->findAll($condicion.' and cod_tipo_nomina='.$var1.' and cod_tipo_transaccion=2');
    $datos_cnmd03_transacciones =   $this->cnmd03_transacciones->findAll(null, null, ' cod_tipo_transaccion,  cod_transaccion  ASC');

     $this->set('var1', $var1);
  }//fin else

   $this->set('datos_cnmp10_comunes52_semanas_porcentaje_ded', $fichas);
   $this->set('datos_cnmd03_transacciones', $datos_cnmd03_transacciones);


}//fin function








function guardar(){


$this->layout = "ajax";
    $cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep;
    $select_cod_transaccion = "";


if($this->data['cnmp10_comunes52_semanas_porcentaje_ded']['cod_tipo_nomina']!=""){
     $select_cod_transaccion        =    $this->data['cnmp10_comunes52_semanas_porcentaje_ded']['select_cod_transaccion'];
}//fin if

 if($select_cod_transaccion==""){
     $select_cod_transaccion        =    $this->data['cnmp10_comunes52_semanas_porcentaje_ded']['aux_cod_transaccion'];
}//fin if

  $cod_tipo_nomina               =    $this->data['cnmp10_comunes52_semanas_porcentaje_ded']['cod_tipo_nomina'];
  $porcentaje                    =    $this->Formato1($this->data['cnmp10_comunes52_semanas_porcentaje_ded']['porcentaje']);
  $tope_cuarta_semana            =    $this->Formato1($this->data['cnmp10_comunes52_semanas_porcentaje_ded']['tope_cuarta_semana']);
  $tope_quinta_semana            =    $this->Formato1($this->data['cnmp10_comunes52_semanas_porcentaje_ded']['tope_quinta_semana']);





//echo $condicion.' and cod_tipo_nomina='.$cod_tipo_nomina.' and cod_tipo_transaccion=2 and cod_transaccion='.$select_cod_transaccion.'';

 if($this->cnmd10_comunes_52semanas_porcentaje_ded->findCount($condicion.' and cod_tipo_nomina='.$cod_tipo_nomina.' and cod_tipo_transaccion = 2 and cod_transaccion = '.$select_cod_transaccion.'')==0){


$sql = "INSERT INTO cnmd10_comunes_52semanas_porcentaje_ded (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, cod_tipo_transaccion, cod_transaccion,  porcentaje, tope_cuarta_semana,  tope_quinta_semana)";
$sql.="VALUES ('".$cod_presi."', '".$cod_entidad."', '".$cod_tipo_inst."', '".$cod_inst."', '".$cod_dep."', '".$cod_tipo_nomina."', '2', '".$select_cod_transaccion."', '".$porcentaje."', '".$tope_cuarta_semana."', '".$tope_quinta_semana."')";

if($this->cnmd10_comunes_52semanas_porcentaje_ded->execute($sql)>=1){

    $ubicacion_escanario  = "DEDUCCIONES COMUNES - EN PORCENTAJE (BASE DE CÁLCULO DE 52 SEMANAS)";
	$sql = "INSERT INTO cnmd10_control_de_escenarios (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, cod_tipo_transaccion, cod_transaccion, ubicacion_escenario)";
    $sql.= "VALUES ('".$cod_presi."', '".$cod_entidad."', '".$cod_tipo_inst."', '".$cod_inst."', '".$cod_dep."', '".$cod_tipo_nomina."', '2', '".$select_cod_transaccion."', '".$ubicacion_escanario."') ";

  if($this->cnmd10_control_de_escenarios->execute($sql)>=1){
  	  $this->set('Message_existe', 'Los datos fueron guardados correctamente');
  	}else{
     $this->set('errorMessage', 'Los datos no pudieron ser guardados en control de escenarios');
  	}//fin else


}else{

  	  $this->set('errorMessage', 'Los datos no pudieron ser guardados correctamente');

     }//fin else


echo'<script>';
    echo'document.getElementById("guardar").disabled = true; ';
//    echo'document.getElementById("modificar").disabled = true; ';
//    echo'document.getElementById("eliminar").disabled = true; ';

    echo'document.getElementById("porcentaje").disabled = true; ';
    echo'document.getElementById("tope_cuarta_semana").disabled = true; ';
    echo'document.getElementById("tope_quinta_semana").disabled = true; ';

     echo'document.getElementById("codigo_transaccion").innerHTML = \'<input type="text" name="data[ccfp01_subdivision][deno_subdivision_contable]" value=""  style="width:98%;text-align:center" readonly="readonly" />\'; ';
     echo'document.getElementById("denominacion_transaccion").innerHTML = \'<input type="text" name="data[ccfp01_subdivision][deno_subdivision_contable]" value=""  style="width:98%;text-align:leght" readonly="readonly" />\'; ';
    echo'document.getElementById("porcentaje").value = ""; ';
    echo'document.getElementById("tope_cuarta_semana").value = ""; ';
    echo'document.getElementById("tope_quinta_semana").value = ""; ';

    echo'document.getElementById("cod_transaccion").options[0].value = ""; ';
    echo'document.getElementById("cod_transaccion").options[0].text = ""; ';
    echo' document.getElementById("cod_transaccion").options[1].selected = true; ';

    echo' document.getElementById("aux_cod_transaccion").value = ""; ';

echo'</script>';


 }else{



$sql ="UPDATE cnmd10_comunes_52semanas_porcentaje_ded SET porcentaje='".$porcentaje."',  tope_cuarta_semana='".$tope_cuarta_semana."', tope_quinta_semana='".$tope_quinta_semana."'  where ".$condicion.' and cod_tipo_nomina='.$cod_tipo_nomina.' and cod_tipo_transaccion = 2 and cod_transaccion = '.$select_cod_transaccion.' ';

if($this->cnmd10_comunes_52semanas_porcentaje_ded->execute($sql) >=1){
  	$this->set('Message_existe', 'Los datos fueron modificados');
  }else{
  	$this->set('errorMessage', 'Los datos no pudieron ser eliminados');
  }//fin else



 echo'<script>';
    echo'document.getElementById("guardar").disabled = true; ';
//    echo'document.getElementById("modificar").disabled = true; ';
//     echo'document.getElementById("eliminar").disabled = true; ';

    echo'document.getElementById("porcentaje").disabled = true; ';
    echo'document.getElementById("tope_cuarta_semana").disabled = true; ';
    echo'document.getElementById("tope_quinta_semana").disabled = true; ';

     echo'document.getElementById("codigo_transaccion").innerHTML = \'<input type="text" name="data[ccfp01_subdivision][deno_subdivision_contable]" value=""  style="width:98%;text-align:center" readonly="readonly" />\'; ';
     echo'document.getElementById("denominacion_transaccion").innerHTML = \'<input type="text" name="data[ccfp01_subdivision][deno_subdivision_contable]" value=""  style="width:98%;text-align:leght" readonly="readonly" />\'; ';

    echo'document.getElementById("porcentaje").value = ""; ';
    echo'document.getElementById("tope_cuarta_semana").value = ""; ';
    echo'document.getElementById("tope_quinta_semana").value = ""; ';

    echo'document.getElementById("cod_transaccion").options[0].value = ""; ';
    echo'document.getElementById("cod_transaccion").options[0].text = ""; ';

    echo'if(document.getElementById("cod_transaccion").options[1].text == "----"){';
      echo' document.getElementById("cod_transaccion").options[1].selected = true; ';
    echo'}else{';
      echo' document.getElementById("cod_transaccion").options[0].selected = true; ';
    echo'}';

echo'</script>';


 }//fin else
$this->set('var1', $cod_tipo_nomina);
    $fichas  =   $this->cnmd10_comunes_52semanas_porcentaje_ded->findAll($condicion.' and cod_tipo_nomina='.$cod_tipo_nomina.' and cod_tipo_transaccion=2');
    $datos_cnmd03_transacciones =   $this->cnmd03_transacciones->findAll(null, null, ' cod_tipo_transaccion,  cod_transaccion  ASC');
   $this->set('datos_cnmp10_comunes52_semanas_porcentaje_ded', $fichas);
   $this->set('datos_cnmd03_transacciones', $datos_cnmd03_transacciones);



}//function guardar







function eliminar($var1=null, $var2=null){

    $cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep;

    $this->layout = "ajax";

  $cod_tipo_nomina               =    $this->data['cnmp10_comunes52_semanas_porcentaje_ded']['cod_tipo_nomina'];
  $select_cod_transaccion        =    $this->data['cnmp10_comunes52_semanas_porcentaje_ded']['select_cod_transaccion'];
  $porcentaje                    =    $this->data['cnmp10_comunes52_semanas_porcentaje_ded']['porcentaje'];
  $tope_cuarta_semana            =    $this->data['cnmp10_comunes52_semanas_porcentaje_ded']['tope_cuarta_semana'];
  $tope_quinta_semana            =    $this->data['cnmp10_comunes52_semanas_porcentaje_ded']['tope_quinta_semana'];




if($var1!=null && $var2!=null){

$cod_tipo_nomina        =  $var1;
$select_cod_transaccion =  $var2;


}else{

  $cod_tipo_nomina               =    $this->data['cnmp10_comunes52_semanas_porcentaje_ded']['cod_tipo_nomina'];
  $select_cod_transaccion        =    $this->data['cnmp10_comunes52_semanas_porcentaje_ded']['select_cod_transaccion'];
  $porcentaje                    =    $this->data['cnmp10_comunes52_semanas_porcentaje_ded']['porcentaje'];
  $tope_cuarta_semana            =    $this->data['cnmp10_comunes52_semanas_porcentaje_ded']['tope_cuarta_semana'];
  $tope_quinta_semana            =    $this->data['cnmp10_comunes52_semanas_porcentaje_ded']['tope_quinta_semana'];

}///fin else


 if($this->cnmd10_comunes_52semanas_porcentaje_ded->findCount($condicion.' and cod_tipo_nomina='.$cod_tipo_nomina.' and cod_tipo_transaccion = 2 and cod_transaccion = '.$select_cod_transaccion.'')!=0){

$sql = "DELETE FROM cnmd10_comunes_52semanas_porcentaje_ded WHERE ".$condicion.' and cod_tipo_nomina='.$cod_tipo_nomina.' and cod_tipo_transaccion = 2 and cod_transaccion = '.$select_cod_transaccion.' ';


  if($this->cnmd10_comunes_52semanas_porcentaje_ded->execute($sql) >=1){

  $sql = "DELETE FROM cnmd10_control_de_escenarios WHERE ".$condicion.' and cod_tipo_nomina='.$cod_tipo_nomina.' and cod_tipo_transaccion = 2 and cod_transaccion = '.$select_cod_transaccion.' ';

  if($this->cnmd10_control_de_escenarios->execute($sql)>=1){
  	$this->set('errorMessage', 'Los datos fueron eliminados correctamente');
  }else{
  	$this->set('errorMessage', 'Los datos no pudieron ser eliminados en control de esenarios');
  }//fin else



echo'<script>';
      //echo'document.getElementById("porcentaje").value = ""; ';
      //echo'document.getElementById("tope_cuarta_semana").value = ""; ';
      //echo'document.getElementById("tope_quinta_semana").value = ""; ';

      //echo'document.getElementById("guardar").disabled = true; ';
//      echo'document.getElementById("modificar").disabled = true; ';
//      echo'document.getElementById("eliminar").disabled = true; ';

 echo'</script>';


  }else{
  	$this->set('errorMessage', 'Los datos no pudieron ser eliminados');
  }//fin else

 }//fin if




}//funcition





function modificar($var1=null, $var2=null, $var3=null, $var4=null, $var5=null){

	$this->layout = "ajax";


echo'<script>';
    echo'document.getElementById("guardar").disabled = false; ';
//    echo'document.getElementById("modificar").disabled = true; ';
//    echo'document.getElementById("eliminar").disabled = true; ';


    echo'document.getElementById("porcentaje").disabled = false; ';
    echo'document.getElementById("tope_cuarta_semana").disabled = false; ';
    echo'document.getElementById("tope_quinta_semana").disabled = false; ';

    if($var1<=9 && $var1!=null){$var1 = '0'.$var1; }
 echo'document.getElementById("codigo_transaccion").innerHTML = \'<input type="text" name="data[ccfp01_subdivision][deno_subdivision_contable]" value="'.$var1.'"  style="width:98%;text-align:center" readonly="readonly" />\'; ';
     echo'document.getElementById("denominacion_transaccion").innerHTML = \'<input type="text" name="data[ccfp01_subdivision][deno_subdivision_contable]" value="'.$var2.'"  style="width:98%;text-align:leght" readonly="readonly" />\'; ';


    echo'document.getElementById("porcentaje").value = "'.$this->Formato2($var3).'"; ';
    echo'document.getElementById("tope_cuarta_semana").value = "'.$this->Formato2($var4).'"; ';
    echo'document.getElementById("tope_quinta_semana").value = "'.$this->Formato2($var5).'"; ';

    echo'document.getElementById("aux_cod_transaccion").value = "'.$var1.'"; ';

    echo'if(document.getElementById("cod_transaccion").options[1].text == "----"){';
      echo' document.getElementById("cod_transaccion").options[1].selected = true; ';
    echo'}else{';
      echo' document.getElementById("cod_transaccion").options[0].selected = true; ';
    echo'}';

 echo'</script>';



}//function







function consulta($pag_num=null){

	$this->layout = "ajax";
    $cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep;

/*$year = $this->ccfd03_instalacion->findAll($condicion, null, 'ano_arranque ASC', null);
  $ano = null;
  foreach($year as $year){$ano = $year['ccfd03_instalacion']['ano_arranque'];}
 */
 $ano=$this->ano_ejecucion();


   $array = $this->cnmd10_comunes_52semanas_porcentaje_ded->findAll($condicion, 'DISTINCT cod_tipo_nomina, cod_tipo_transaccion',  'cod_tipo_nomina, cod_tipo_transaccion ASC', null);

    $i = 0;
 if($pag_num==null){$pag_num=0;}

 foreach($array as $aux){

 	$numero[$i]['cod_tipo_nomina'] =   $aux['cnmd10_comunes_52semanas_porcentaje_ded']['cod_tipo_nomina'];
 	$numero[$i]['cod_tipo_transaccion']       =   $aux['cnmd10_comunes_52semanas_porcentaje_ded']['cod_tipo_transaccion'];

 	$i++;

} $i--;


if(isset($numero[$pag_num]['cod_tipo_nomina'])){

 $datos_cnmp10_comunes52_semanas_porcentaje_ded = $this->cnmd10_comunes_52semanas_porcentaje_ded->findAll($condicion.' and cod_tipo_nomina='.$numero[$pag_num]['cod_tipo_nomina'].' and cod_tipo_transaccion = 2 ');

 $datos_cnmd03_transacciones =   $this->cnmd03_transacciones->findAll(null, null, ' cod_tipo_transaccion,  cod_transaccion  ASC');
 $this->set('datos_cnmd03_transacciones', $datos_cnmd03_transacciones);


 $fichas  =   $this->v_cnmd05->findAll($condicion.' and cod_tipo_nomina='.$numero[$pag_num]['cod_tipo_nomina'].'');
     foreach($fichas as $aux){
        $denominacion_nomina = $aux['v_cnmd05']['tipo_nomina'];
     }//fin for

 $this->set('datos_cnmp10_comunes52_semanas_porcentaje_ded', $datos_cnmp10_comunes52_semanas_porcentaje_ded);
 $this->set('datos_cnmd03_transacciones', $datos_cnmd03_transacciones);
 $this->set('pag_num', $pag_num);
 $this->set('totalPages_Recordset1', $i);
 $this->set('codigo_nomina', $numero[$pag_num]['cod_tipo_nomina']);
 $this->set('denominacion_nomina', $denominacion_nomina);


}else{

 $this->set('pag_num', 0);
 $this->set('totalPages_Recordset1', '');
 $this->set('errorMessage', 'No existen datos');

}//fin else



//$this->mostrar_datos_griya();


}//fin function



function otras_nominas($var1=null){
	$this->layout="ajax";
//	echo $var1;
	$cod_presi                =       $this->Session->read('SScodpresi');
	$cod_entidad              =       $this->Session->read('SScodentidad');
	$cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
	$cod_inst                 =       $this->Session->read('SScodinst');
	$cod_dep                  =       $this->Session->read('SScoddep');
//	echo "dsbhgdsfjkgbfdsjk";
	//////////////////// AGREGAR A PARTIR DE AQUI////////////////////////////
	$sql='';
	$datos=$this->cnmd10_comunes_52semanas_porcentaje_ded->findAll($this->condicion()." and cod_tipo_transaccion=2 and cod_tipo_nomina=".$var1,null,null);
			 if($datos){
			 	foreach($datos as $x){
			 		$transaccion=$x['cnmd10_comunes_52semanas_porcentaje_ded']['cod_transaccion'];
			 		if($sql==''){
			 			$sql.=$transaccion;
			 		}else{
			 			$sql.=",".$transaccion;
			 		}
			 	}
				$query="SELECT distinct a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep,a.cod_tipo_nomina FROM cnmd10_comunes_52semanas_porcentaje_ded a where a.cod_presi='$cod_presi' and a.cod_entidad='$cod_entidad' and a.cod_tipo_inst='$cod_tipo_inst' and a.cod_inst='$cod_inst' and a.cod_dep='$cod_dep' and a.cod_tipo_transaccion=2 and cod_tipo_nomina!=".$var1." and a.cod_transaccion IN(".$sql.")";
				$opciones=$this->cnmd10_comunes_52semanas_porcentaje_ded->execute($query);
				if($opciones){
					$this->set('opciones',$opciones);
				}else{
					 $this->set('opciones','');
				}
			 }else{
			 	 $this->set('opciones','');
			 }
//print_r($datos);
	 $deno_trans= $this->Cnmd01->findAll($this->condicion(), $fields ='cod_tipo_nomina, denominacion', $order = null, $limit = null, $page = null, $recursive = null);
	 $this->set('deno_trans', $deno_trans);
 $this->render('otras_nominas');
}//fin otras nominas



function cod_transferir($cod_nomina=null){
	$this->layout="ajax";
	if($cod_nomina!=null){
		$this->set('cod_trans', $cod_nomina);
		// $cod_trans=$this->Session->read('trans');
		//$ubicacion = $this->cnmd10_control_escenarios->field('cnmd10_control_escenarios.ubicacion_escenario', $conditions = $this->condicion()." and cod_tipo_nomina='$cod_nomina' and cod_tipo_transaccion=1 and cod_transaccion='$cod_trans'", $order =null);
		//$this->set('ubicacion', $ubicacion);

	}


}//fin cod_transferencia




function deno_transferir($cod_nomina=null){
	$this->layout="ajax";
	if($cod_nomina!=null){
		$deno_nomina = $this->Cnmd01->field('denominacion', $conditions = $this->condicion()." and Cnmd01.cod_tipo_nomina='$cod_nomina'", $order ="cod_tipo_nomina ASC");
		$this->set('deno_nomina', $deno_nomina);
		echo "<script>";
			echo "document.getElementById('save_transferir').disabled=false;";
		echo "</script>";
	}


}//fin deno_transferencia





function transferir($var1=null){
	$this->layout="ajax";
//	    $var1=$this->Session->read('nomina');
		// $var2=$this->Session->read('trans');
//		$var1=$var2;
		$carga=$this->cnmd10_comunes_52semanas_porcentaje_ded->findAll($this->condicion()." and cod_tipo_nomina=".$var1." and cod_tipo_transaccion=2");
		if($carga){
			$lista2 = $this->Cnmd01->generateList($conditions = $this->condicion()." and cod_tipo_nomina!=".$var1, $order = 'cod_tipo_nomina', $limit = null, '{n}.Cnmd01.cod_tipo_nomina', '{n}.Cnmd01.denominacion');
			//$lista2 = $this->Cnmd01->generateList($conditions =$this->condicion().' and cod_tipo_nomina!='.$var1, $order = null, $limit = null, '{n}.Cnmd01.cod_tipo_nomina','{n}.Cnmd01.denominacion');
			if($lista2){
			$this->concatenaN($lista2, 'transferir');
			$this->set('cod_nomina', $var1);
			}else{
				$this->set('transferir',array());
			}
		}else{
			$this->set('nada',"");
		}



}//fin transferir


function guardar_transferir(){
	$this->layout="ajax";

	  $cod_presi = $this->Session->read('SScodpresi');
	  $cod_entidad = $this->Session->read('SScodentidad');
	  $cod_tipo_inst = $this->Session->read('SScodtipoinst');
	  $cod_inst = $this->Session->read('SScodinst');
	  $cod_dep = $this->Session->read('SScoddep');

  $cod_tipo_nomina = $this->data['cnmp10_comunes52_semanas_porcentaje_ded']['cod_tipo_nomina'];
	  $cod_transaccion = $this->data['cnmp10_comunes52_semanas_porcentaje_ded']['select_cod_transaccion'];
	  $cod_transferir = $this->data['cnmp10_comunes52_semanas_porcentaje_ded']['select_transferir'];
	  $cod_tipo_transaccion=2;

	 /* $cod_tipo_nomina = $this->data['cnmp10_comunes_bolivares_deduccion']['cod_nomina'];
	 // $cod_transaccion = $this->data['cnmp10_comunes_bolivares_deduccion']['cod_trans'];
	  $cod_transferir = $this->data['cnmp10_comunes_bolivares_deduccion']['cod_transferir'];*/

	  $s=0;
	  $bandera=0;
	  $bandera=0;
	 $data=$this->cnmd10_comunes_52semanas_porcentaje_ded->findAll($this->condicion()." and cod_tipo_nomina=".$cod_tipo_nomina." and cod_tipo_transaccion=2");
	 //$data2=$this->cnmd10_comunes_puestos_porcentaje_ded_2->findAll($this->condicion()." and cod_tipo_nomina=".$cod_tipo_nomina." and cod_tipo_transaccion=2 and cod_transaccion=".$cod_transaccion);
	 $ubicacion_escenario = strtoupper('DEDUCCIONES COMUNES - EN PORCENTAJE (BASE DE CÁLCULO DE 52 SEMANAS)');
		//print_r($data);
	  foreach($data as $row){
	  		$cod_transaccion1 = $row['cnmd10_comunes_52semanas_porcentaje_ded']['cod_transaccion'];
	  		$porcentaje = $row['cnmd10_comunes_52semanas_porcentaje_ded']['porcentaje'];
			$tope_cuarta_semana = $row['cnmd10_comunes_52semanas_porcentaje_ded']['tope_cuarta_semana'];
			$tope_quinta_semana = $row['cnmd10_comunes_52semanas_porcentaje_ded']['tope_quinta_semana'];
			$datos=$this->cnmd10_control_escenarios->findAll($this->condicion()." and cod_tipo_nomina=".$cod_transferir." and cod_tipo_transaccion=2 and cod_transaccion=".$cod_transaccion1);
			if(!$datos){
				$sql_insert = "INSERT INTO cnmd10_comunes_52semanas_porcentaje_ded VALUES('$cod_presi', '$cod_entidad', '$cod_tipo_inst', '$cod_inst', '$cod_dep', '$cod_transferir', '$cod_tipo_transaccion', '$cod_transaccion1','$porcentaje',$tope_cuarta_semana,'$tope_quinta_semana')";
				$sw = $this->cnmd10_comunes_52semanas_porcentaje_ded->execute($sql_insert);
				if($sw>1){
				echo "<script>";
					echo "document.getElementById('save_transferir').disabled='disabled';";
					echo "document.getElementById('select_transferir').options[0].text='';";
					echo "document.getElementById('cod_transferencia').value='';";
					echo "document.getElementById('deno_transferencia').value='';";
				echo "</script>";
				$sql_control = "INSERT INTO cnmd10_control_de_escenarios VALUES('$cod_presi', '$cod_entidad', '$cod_tipo_inst', '$cod_inst', '$cod_dep', '$cod_transferir', '$cod_tipo_transaccion', '$cod_transaccion1', '$ubicacion_escenario')";
				$sw1 = $this->cnmd10_control_escenarios->execute($sql_control);
				$s=2;
			}//fin $sw
		}else{
			$bandera=2;
		}//fin $datos
			$sw=0;
	  }//fin foreach data

	 /* if($s>1){
	  	$this->set('Message_existe', 'Transferencia realizada con exito');
	}//fin $s*/

	if($bandera>1 && $s>1){
	  	$this->set('Message_existe', 'Transferencia realizada con exito');
	}else if($bandera==0 && $s>1){
		$this->set('Message_existe', 'Transferencia realizada con exito');
	}else if($bandera>1 && $s==0){
		$this->set('Message_error', 'Transferencia sin exito');
	}

	$sql='';
			 if($data){
			 	foreach($data as $x){
			 		$transaccion=$x['cnmd10_comunes_52semanas_porcentaje_ded']['cod_transaccion'];
			 		if($sql==''){
			 			$sql.=$transaccion;
			 		}else{
			 			$sql.=",".$transaccion;
			 		}
			 	}
				$query="SELECT distinct a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep,a.cod_tipo_nomina FROM cnmd10_comunes_52semanas_porcentaje_ded a where a.cod_presi='$cod_presi' and a.cod_entidad='$cod_entidad' and a.cod_tipo_inst='$cod_tipo_inst' and a.cod_inst='$cod_inst' and a.cod_dep='$cod_dep' and a.cod_tipo_transaccion=2 and cod_tipo_nomina!=".$cod_tipo_nomina." and a.cod_transaccion IN(".$sql.")";
				$opciones=$this->cnmd10_comunes_52semanas_porcentaje_ded->execute($query);
				if($opciones){
					$this->set('opciones',$opciones);
				}else{
					 $this->set('opciones','');
				}
			 }else{
			 	 $this->set('opciones','');
			 }
//print_r($datos);
	 $deno_trans= $this->Cnmd01->findAll($this->condicion(), $fields ='cod_tipo_nomina, denominacion', $order = null, $limit = null, $page = null, $recursive = null);
	 $this->set('deno_trans', $deno_trans);


}// fin guardar_transferir



}//fin class
?>