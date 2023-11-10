<?php

 class Cnmp03transaccionesController extends AppController{
	var $uses = array('cnmd03_transacciones','cnmd07_transacciones_actuales','cnmd08_historia_transacciones','cugd05_restriccion_clave','v_cnmd03_transa_utilizadas');
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


function beforeFilter(){
    $this->checkSession();

}//fin before filter


function index(){

$this->verifica_entrada('100');

	$this->layout = "ajax";
	$this->data=null;
	$Lista = $this->cnmd03_transacciones->generateList("cod_tipo_transaccion=2", 'cod_transaccion ASC', null, '{n}.cnmd03_transacciones.cod_transaccion', '{n}.cnmd03_transacciones.denominacion');
   	$this->concatena($Lista, 'Listactp');

	//verifico la dependencia para activar los botones necesarios
   	if($this->verifica_SS(5) == 1){
 		$this->set('enable_guardar', 'enable');
 	}else{
 		$this->set('enable_guardar', 'disabled');
 	}


 	$this->Session->delete('cod');
	$this->Session->write('cod',1);
	echo "<script>";
		echo "document.getElementById('cnmp03transaccionesDenominaciont').value='';";
		echo "document.getElementById('cnmp03transaccionesDenominacionp').value='';";
		echo "document.getElementById('tipo_asignacion_1').checked=true;";
        echo "document.getElementById('tipo_asignacion_3').disabled='';";
        echo "document.getElementById('tipo_asignacion_2').disabled='';";
        echo "document.getElementById('tipo_asignacion_1').disabled='';";
        echo "document.getElementById('tipo_asignacion_4').disabled='';";
        echo "document.getElementById('codigo_transaccion').disabled='';";
	    echo "document.getElementById('cnmp03transaccionesDenominaciont').disabled='';";
	    echo "document.getElementById('cnmp03transaccionesDenominacionp').disabled='';";
	    //uso transaccion asignacion
	    echo "document.getElementById('uso_transaccion_1').disabled='';";
	    echo "document.getElementById('uso_transaccion_2').disabled='';";
	    echo "document.getElementById('uso_transaccion_7').disabled='';";
	    echo "document.getElementById('uso_transaccion_77').disabled='disabled';";
	    echo "for(i=3;i<7;i++){";
	    echo "document.getElementById('uso_transaccion_'+i).disabled='disabled';";
	    echo "document.getElementById('uso_transaccion_'+i).checked=false;";
	    echo "}";
	    echo "document.getElementById('uso_transaccion_8').disabled='disabled';";
	    echo "document.getElementById('uso_transaccion_8').checked=false;";
	echo "</script>";
	$this->set('limpio','');




$this->set('accion', $this->cnmd03_transacciones->findAll("cod_tipo_transaccion=1", null, "cod_transaccion ASC"));


        $cod1=$this->Session->read('cod');
		if($cod1!=''){
			$v=$this->cnmd03_transacciones->execute("SELECT * FROM cnmd03_transacciones WHERE  cod_tipo_transaccion=".$cod1." ORDER BY cod_transaccion DESC");
			//print_r($v);
			if($v!=null){
				$codigo=$v[0][0]["cod_transaccion"];
				$codigo = $codigo =="" ? 1 : $codigo+1;
			}else{
				$codigo=1;
			}
		    $this->set('codigo',mascara_tres($codigo));
		}else{
			$this->set('codigo',false);
		}


}//fin index





function index2(){
	$this->layout = "ajax";
	$this->data=null;
	$Lista = $this->cnmd03_transacciones->generateList(null, 'cod_transaccion ASC', null, '{n}.cnmd03_transacciones.cod_transaccion', '{n}.cnmd03_transacciones.denominacion');
   	$this->concatena($Lista, 'Listactp');

	//verifico la dependencia para activar los botones necesarios
   	if($this->verifica_SS(5) == 1){
 		$this->set('enable_guardar', 'enable');
 	}else{
 		$this->set('enable_guardar', 'disabled');
 	}

}//fin index




function funcion(){

$this->layout="ajax";


}//fin function


function eliminar_grilla($var1=null, $var2=null){
	$this->layout = "ajax";

		$tipo_transaccion  =  $var1;
		$codigo            =  $var2;
		$a                 = $tipo_transaccion;
		$b                 = $codigo;

	if (($a==1 && ($b==1 || $b==2 || $b==3 || $b==4 || $b==5)) || ($a==2 && ($b==1 || $b==2 || $b==3 || $b==4 || $b==5 || $b==100 || $b==400 || $b==401 || $b==402 || $b==403|| $b==404))){
		$this->set('errorMessage', 'No puede eliminar !!Transacci&oacute;n reservada por el Sistema!!');
	}else{
		$trans_actuales = $this->cnmd07_transacciones_actuales->findCount("cod_tipo_transaccion=".$tipo_transaccion." and cod_transaccion=".$codigo);
		$trans_historia = $this->cnmd08_historia_transacciones->findCount("cod_tipo_transaccion=".$tipo_transaccion." and cod_transaccion=".$codigo);
		if($trans_actuales == 0 && $trans_historia == 0){
			$this->cnmd03_transacciones->execute("DELETE FROM cnmd03_transacciones WHERE cod_tipo_transaccion=".$tipo_transaccion." and cod_transaccion=".$codigo);
			$this->set('Message_existe', 'Transacción Eliminada con éxito');
		}else{
			$this->set('errorMessage', 'Lo siento esta transacción no puede ser eliminada');
		}
	}
		$this->funcion();
		$this->render('funcion');

}//fin eliminar




function automatico($opc=null,$var=null){
$this->layout="ajax";

echo "<script>if(document.getElementById('observa_transaccion')){
			document.getElementById('carga_observa_trans').innerHTML='';
			document.getElementById('observa_transaccion').value = '';
			}
		</script>";

//////////////////////////////nuevo//////////////////////////////////
if($var==1){
	echo "<script>";
		echo "document.getElementById('enlace_contable').disabled=true;";
		echo "if(document.getElementById('bt_guardar')){document.getElementById('bt_guardar').disabled=false;}";
	echo "</script>";
}else if($var=='2'){
	echo "<script>";
		echo "document.getElementById('enlace_contable').disabled=false;";
	echo "</script>";
}

if($opc=='limpio'){
	$this->Session->delete('cod');
	$this->Session->write('cod',$var);
	$this->set('limpio','');

			        $cod1=$var;
					if($cod1!=''){
						$v=$this->cnmd03_transacciones->execute("SELECT * FROM cnmd03_transacciones WHERE  cod_tipo_transaccion=".$cod1." ORDER BY cod_transaccion DESC");
						//print_r($v);
						if($v!=null){
							$codigo=$v[0][0]["cod_transaccion"];
							$codigo = $codigo =="" ? 1 : $codigo+1;
						}else{
							$codigo=1;
						}
					$this->set('codigo',mascara_tres($codigo));
					}else{
						$this->set('codigo',false);
					}

			   echo "<script>";
					echo "document.getElementById('codigo_transaccion').value='';";
					echo "document.getElementById('cnmp03transaccionesDenominaciont').value='';";
					echo "document.getElementById('cnmp03transaccionesDenominacionp').value='';";
					echo "document.getElementById('radio_si_no_1').checked=true;";
					echo "document.getElementById('codigo_transaccion').value='".mascara_tres($codigo)."';";
				echo "</script>";


$this->set('accion', $this->cnmd03_transacciones->findAll("cod_tipo_transaccion=".$var, null, "cod_transaccion ASC"));
$this->render("grilla");


}else if($opc=='full'){
	if($var!=2){
		$cod1=$this->Session->read('cod');
		if($cod1!=''){
			$v=$this->cnmd03_transacciones->execute("SELECT * FROM cnmd03_transacciones WHERE  cod_tipo_transaccion=".$cod1." ORDER BY cod_transaccion DESC");
			//print_r($v);
			if($v!=null){
				$codigo=$v[0][0]["cod_transaccion"];
				$codigo = $codigo =="" ? 1 : $codigo+1;
			}else{
				$codigo=1;
			}
		$this->set('codigo',mascara_tres($codigo));
		}else{
			$this->set('codigo',false);
		}
	}else{
		//$this->set('codigo',false);
		//$this->Session->delete('cod');
		echo "<script>";
			//echo "document.getElementById('tipo_transaccion_1').checked=false;";
			//echo "document.getElementById('tipo_transaccion_2').checked=false;";
		echo "</script>";
	}

}

}//fin automatico



function guardar(){
	$this->layout = "ajax";
	if(!empty($this->data)){
	 $a=$this->data['cnmp03transacciones']['tipo_transaccion'];
	 $b=$this->data['cnmp03transacciones']['codigo'];
	 $c=$this->data['cnmp03transacciones']['denominaciont'];
	 $d=$this->data['cnmp03transacciones']['denominacionp'];
	 $e=$this->data['cnmp03transacciones']['uso_transaccion'];
	 $e=($e==77 ? 7 : $e);
	 $f=$this->data['cnmp03transacciones']['tipo_asignacion'];
	 $g=$this->data['cnmp03transacciones']['tipo_actualizacion'];
	 $h=($e==6 || $e==8 ? $this->data['cnmp03transacciones']['cod_tipo_transaccion_padre'] : 0);
	 $vvv= isset($this->data['cnmp03transacciones']['cod_tp']) ? $this->data['cnmp03transacciones']['cod_tp'] : 0;
	 $i=(!empty($vvv) ? $vvv : 0);
	 $i=($e==6 || $e==8 ? $i : 0);
	 //$j=$this->data['cnmp03transacciones']['denominaciontp'];

	if($a=='1'){
		$enlace_contable = 0;
	}else{
		$enlace_contable = $this->data['cnmp03transacciones']['enlace_contable'];
	}

	 $SQL_INSERT ="INSERT INTO cnmd03_transacciones (cod_tipo_transaccion,cod_transaccion,denominacion,denominacion_pago,tipo_asignacion,uso_transaccion,tipo_actualizacion,cod_tipo_transaccion_padre,cod_transaccion_padre,enlace_contable)";
	 $SQL_INSERT .=" VALUES ($a,$b,'$c','$d',$f,$e,$g,$h,$i,$enlace_contable)";
	 $condicion="cod_tipo_transaccion=".$a." and cod_transaccion=".$b;
	 if($this->cnmd03_transacciones->findCount($condicion)==0){
         $this->cnmd03_transacciones->execute($SQL_INSERT);
         $this->data=null;
         $this->set('Message_existe', 'Transacción agregada con éxito.');

	 }else{
	 	 $this->set('errorMessage', 'El código de la transacción ya existe');
	 }

	}else{
        $this->set('errorMessage', 'No se realizado la inserción');
	}//fin condicion this data

	if($a==1){
		$this->set('userTable', $this->requestAction('/cnmp03partidas/', array('return')));
	}else if($a==2 && ($e==6 || $e==8)){
		$this->set('userTable', $this->requestAction('/cnmp03partidas/', array('return')));
	}else{
    $this->index();
	$this->render('index');
	}

}//fin guardar

function modificar_guardar($pagina=null){

	$this->layout = "ajax";
if(!empty($this->data)){
	 $a=$this->data['cnmp03transacciones']['tipo_transaccion'];
	 $b=$this->data['cnmp03transacciones']['codigo'];
	 $c=$this->data['cnmp03transacciones']['denominaciont'];
	 $d=$this->data['cnmp03transacciones']['denominacionp'];
	 $e=$this->data['cnmp03transacciones']['uso_transaccion'];
	$e=($e==77 ? 7 : $e);

	 $g=$this->data['cnmp03transacciones']['tipo_actualizacion'];
	 $h=($e==6 || $e==8 ? $this->data['cnmp03transacciones']['cod_tipo_transaccion_padre'] : 0);

	 if(isset($this->data['cnmp03transacciones']['tipo_asignacion'])){
	 $f=$this->data['cnmp03transacciones']['tipo_asignacion'];
	 $sql_tipo_asignacion = "tipo_asignacion='".$f."', ";
	 }else{
	 $sql_tipo_asignacion = "";
	 }

	if($a=='1'){
		$enlace_contable = 0;
	}else{
		$enlace_contable = $this->data['cnmp03transacciones']['enlace_contable'];
	}

	 $vvv= isset($this->data['cnmp03transacciones']['cod_tp']) ? $this->data['cnmp03transacciones']['cod_tp'] : 0;
	 $i=(!empty($vvv) ? $vvv : 0);
	 $i=($e==6 || $e==8 ? $i : 0);
     		$condicion="cod_tipo_transaccion=".$a." and cod_transaccion=".$b;
	 		$SQL_UPDATE ="UPDATE cnmd03_transacciones SET cod_tipo_transaccion=$a,denominacion='$c',denominacion_pago='$d', ".$sql_tipo_asignacion." uso_transaccion=$e,tipo_actualizacion=$g,cod_tipo_transaccion_padre=$h,cod_transaccion_padre=$i, enlace_contable=$enlace_contable WHERE ".$condicion;
	 		if($this->cnmd03_transacciones->findCount($condicion)==1){
         		$this->cnmd03_transacciones->execute($SQL_UPDATE);

        		$this->data=null;
        	 	$this->set('Message_existe', 'Transaccion Actualizada con exito.');

         		$this->consulta($pagina);
	     		$this->render('consulta');
	 		}else{
	 		 $this->set('errorMessage', 'Error - Transacci&oacute;n no modificada');
	 		}
	}else{
        $this->set('errorMessage', 'Error - No se realizo la modificaci&oacute;n');
		}//fin condicion this data
		//$this->render('consulta');

}//fin guardar














function modificar_guardar_index(){
	$this->layout = "ajax";
	if(!empty($this->data)){
	 $a=$this->data['cnmp03transacciones']['tipo_transaccion'];
	 $b=$this->data['cnmp03transacciones']['codigo'];
	 if (($a==1 && ($b==1 || $b==2 || $b==3 || $b==4)) || ($a==2 && ($b==1 || $b==2 || $b==3 || $b==4 || $b==5 || $b==100 || $b==400 || $b==401 || $b==402 || $b==403|| $b==404))){
		$this->set('errorMessage', 'No puede modificar !!Transacci&oacute;n reservada por el Sistema!!');
	}else{
	 $c=$this->data['cnmp03transacciones']['denominaciont'];
	 $d=$this->data['cnmp03transacciones']['denominacionp'];
	 $e=$this->data['cnmp03transacciones']['uso_transaccion'];
	$e=($e==77 ? 7 : $e);
	 $f=$this->data['cnmp03transacciones']['tipo_asignacion'];
	 $g=$this->data['cnmp03transacciones']['tipo_actualizacion'];
	 $h=($e==6 || $e==8 ? $this->data['cnmp03transacciones']['cod_tipo_transaccion_padre'] : 0);
	 $vvv= isset($this->data['cnmp03transacciones']['cod_tp']) ? $this->data['cnmp03transacciones']['cod_tp'] : 0;
	 $i=(!empty($vvv) ? $vvv : 0);
	 $i=($e==6 || $e==8 ? $i : 0);
	// $j=$this->data['cnmp03transacciones']['denominaciontp'];

	if($a=='1'){
		$enlace_contable = 0;
	}else{
		$enlace_contable = $this->data['cnmp03transacciones']['enlace_contable'];
	}

     $condicion="cod_tipo_transaccion=".$a." and cod_transaccion=".$b;
	 $SQL_UPDATE ="UPDATE cnmd03_transacciones SET cod_tipo_transaccion=$a,denominacion='$c',denominacion_pago='$d',tipo_asignacion=$f,uso_transaccion=$e,tipo_actualizacion=$g,cod_tipo_transaccion_padre=$h,cod_transaccion_padre=$i, enlace_contable=$enlace_contable WHERE ".$condicion;
	 if($this->cnmd03_transacciones->findCount($condicion)==1){
         $this->cnmd03_transacciones->execute($SQL_UPDATE);


         //echo $SQL_UPDATE;
         $this->data=null;
         $this->set('Message_existe', 'Transaccion Actualizada con exito.');
         //echo "actualizado";

	 	}else{
	 	 	$this->set('errorMessage', 'Error - Transacci&oacute;n no modificada');
		 }

	 	}

		}else{
        	$this->set('errorMessage', 'Error - No se realiazo la modificaci&oacute;n');
		}//fin condicion this data
			//$this->render('consulta');

		if($this->verifica_SS(5) == 1){
 			$this->set('enable_guardar', 'enable');
 		}else{
 			$this->set('enable_guardar', 'disabled');
 		}



    $this->set('tipo_transaccion', $a);
	$this->set('accion', $this->cnmd03_transacciones->findAll("cod_tipo_transaccion=".$a, null, "cod_transaccion ASC"));


}//fin guardar


function observar2transaccion($tipo_transaccion = null, $pista = null){
	$this->layout = "ajax";
	if($tipo_transaccion != null && $pista != null){
		$pista = strtoupper($pista);
		$this->set('accion', $this->cnmd03_transacciones->findAll("cod_tipo_transaccion=$tipo_transaccion and (cod_transaccion::text LIKE '%$pista%' or (UPPER(denominacion) LIKE '%$pista%'))", "cod_tipo_transaccion, cod_transaccion, denominacion", "denominacion ASC"));
	}else{
		$this->set('errorMessage', 'No se puede buscar. . . faltan datos!! ingrese transacci&oacute;n a buscar!!');
		echo "<script>document.getElementById('observa_transaccion').focus();</script>";
	}
}


function modificar_index($tipo_transaccion=null, $codigo=null){
	$this->layout = "ajax";
     $alea=rand();
	$con="cod_tipo_transaccion='".$tipo_transaccion."' and cod_transaccion=".$codigo." and $alea=$alea";
	$Tfilas=$this->cnmd03_transacciones->findCount($con);
	if($Tfilas!=0){
            $datacnmd03t=$this->cnmd03_transacciones->findAll($con);
          	$this->set('DATACNMP03T',$datacnmd03t);
          	$datacnmd03t2=$this->cnmd03_transacciones->findAll();
          	$this->set('DATACNMP03T2',$datacnmd03t2);
          	if($datacnmd03t[0]['cnmd03_transacciones']['uso_transaccion']==6){
          		$b=2;
          	}else if($datacnmd03t[0]['cnmd03_transacciones']['uso_transaccion']==8){
          		$b=1;
          	}else{
          		$b=0;
          	}
          	$Lista = $this->cnmd03_transacciones->generateList("cod_tipo_transaccion=".$b, 'cod_transaccion ASC', null, '{n}.cnmd03_transacciones.cod_transaccion', '{n}.cnmd03_transacciones.denominacion');
   	        $this->concatenaN($Lista, 'Listactp');
   	        $this->set('accion', $this->cnmd03_transacciones->findAll("cod_tipo_transaccion=".$tipo_transaccion, null, "cod_transaccion ASC"));
	}else{
       $this->set('errorMessage', 'Error - No se encontraron datos');
       $this->data=null;
       //$this->render('index2');
	}
}//fin modificar





function select_tipo_trans($tipo_transaccion=null){

$this->layout = "ajax";
			$Lista = $this->cnmd03_transacciones->generateList("cod_tipo_transaccion=".$tipo_transaccion, 'cod_transaccion ASC', null, '{n}.cnmd03_transacciones.cod_transaccion', '{n}.cnmd03_transacciones.denominacion');
   	        $this->concatenaN($Lista, 'Listactp');

   	        $this->set("tipo_transaccion", $tipo_transaccion);

}//fin function




function mostrar_ctp($var1=null,$var2=null) {
    $this->layout = "ajax";
    if(isset($var1) && !empty($var1)){
        $a=$this->cnmd03_transacciones->findAll("cod_tipo_transaccion=".$var1." and cod_transaccion=".$var2);
        echo $a[0]['cnmd03_transacciones']['denominacion'];

        $ver=$this->cnmd03_transacciones->FindCount("cod_tipo_transaccion_padre=".$var1." and cod_transaccion_padre=".$var2);
        if($ver!=0){
			 $this->set('errorMessage', 'esta transacción ya se encuentra registrada como transacción padre de un aporte patronal o abono a cuenta');
			 echo "<script>document.getElementById('bt_guardar').disabled='disabled';</script>";
        }else{
			echo "<script>document.getElementById('bt_guardar').disabled=false;</script>";
        }
    }else{
      echo "";
    }

}



function modificar($pagina=null){
	$this->layout = "ajax";
	$codigo            =  $this->data['cnmp03transacciones']['codigo'];
    $tipo_transaccion  =  $this->data['cnmp03transacciones']['tipo_transaccion'];

	$con="cod_tipo_transaccion='".$tipo_transaccion."' and cod_transaccion=".$this->data['cnmp03transacciones']['codigo'];
	$Tfilas=$this->cnmd03_transacciones->findCount($con);
	if($Tfilas!=0){
            $datacnmd03t=$this->cnmd03_transacciones->findAll($con);
          	$this->set('DATACNMP03T',$datacnmd03t);
          	$datacnmd03t2=$this->cnmd03_transacciones->findAll();
          	$this->set('DATACNMP03T2',$datacnmd03t2);


          	if($datacnmd03t2[0]['cnmd03_transacciones']['cod_tipo_transaccion_padre']==0){$datacnmd03t2[0]['cnmd03_transacciones']['cod_tipo_transaccion_padre']=2;}

             $this->set('pagina',$pagina);

          	$Lista = $this->cnmd03_transacciones->generateList("cod_tipo_transaccion=".$datacnmd03t2[0]['cnmd03_transacciones']['cod_tipo_transaccion_padre'], 'cod_transaccion ASC', null, '{n}.cnmd03_transacciones.cod_transaccion', '{n}.cnmd03_transacciones.denominacion');
   	        $this->concatena($Lista, 'Listactp');
	}else{
       $this->set('errorMessage', 'Error - No se encontraron datos');
       $this->data=null;
       //$this->render('index2');
	}
}//fin modificar









function eliminar($pagina){
	$this->layout = "ajax";
	if(!empty($this->data['cnmp03transacciones']['codigo'])){
		$codigo            = $this->data['cnmp03transacciones']['codigo'];
		$tipo_transaccion  = $this->data['cnmp03transacciones']['tipo_transaccion'];

			$trans_utilizada = $this->v_cnmd03_transa_utilizadas->findCount("cod_tipo_transaccion=".$tipo_transaccion." and cod_transaccion=".$codigo);

			if($trans_utilizada == 0){
				$this->cnmd03_transacciones->execute("DELETE FROM cnmd03_transacciones WHERE cod_tipo_transaccion=".$tipo_transaccion." and cod_transaccion=".$codigo);
				$this->set('Message_existe', 'Transacción Eliminada con éxito');
			}else{
				$this->set('errorMessage', 'Lo siento esta transacción no puede ser eliminada');
			}
	}else{
        $this->set('errorMessage', 'Error - Transacci&oacute;n no eliminada');
	}//fin condicion this data
	$this->consulta($pagina);
	$this->render('consulta');

}//fin eliminar



function consulta($pagina=null){
	$this->layout = "ajax";
	$Tfilas=$this->cnmd03_transacciones->findCount();
	if($Tfilas!=0){
	if($pagina!=null){
	          	$pagina=$pagina;
	          	if($pagina<=0){$pagina=1;}

	          	$this->set('resultado','Resultado: '.$pagina.'/'.$Tfilas.' ');
	          	$this->set('pag',$pagina);
	            $datacnmd03t=$this->cnmd03_transacciones->findAll(null,null,'cod_tipo_transaccion, cod_transaccion ASC',1,$pagina,null);
	          	$this->set('DATACNMP03T',$datacnmd03t);
	          	$datacnmd03tp=$this->cnmd03_transacciones->findAll(null,null,'cod_tipo_transaccion, cod_transaccion ASC',null,null,null);
	          	$this->set('DENOTP',$datacnmd03tp);
	           // print_r($datacnmd03tp);
	          	$this->set('siguiente',$pagina+1);
	          	$this->set('anterior',$pagina-1);
	            $this->bt_nav($Tfilas,$pagina);
          }else{
	          	$pagina=1;
	          	$this->set('resultado','Resultado: '.$pagina.'/'.$Tfilas.' ');
	          	$this->set('pag',$pagina);
	            $datacnmd03t=$this->cnmd03_transacciones->findAll(null,null,'cod_tipo_transaccion, cod_transaccion ASC',1,$pagina,null);
	          	$this->set('DATACNMP03T',$datacnmd03t);
	          	$datacnmd03tp=$this->cnmd03_transacciones->findAll(null,null,'cod_tipo_transaccion, cod_transaccion ASC',null,null,null);
	          	$this->set('DENOTP',$datacnmd03tp);
	         //	print_r($datacnmd03tp);
	          	$this->set('siguiente',$pagina+1);
	          	$this->set('anterior',$pagina-1);
	            $this->bt_nav($Tfilas,$pagina);
          }
	}else{
       $this->set('errorMessage', 'Error - No se encontraron datos');
       $this->data=null;
       $this->index();
       $this->render('index2');
	}

	//verifico la dependencia para activar los botones necesarios
    if($this->verifica_SS(5) == 1){
 		$this->set('enable', 'enable');
 	}else{
 		$this->set('enable', 'disabled');
 	}
}//fin consulta




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


 function salir(){
 	$this->layout="ajax";
 	$this->Session->delete('autor_valido');
 	echo"<script>menu_activo();</script>";
 }


 function entrar(){
	$this->layout="ajax";
	if(isset($this->data['cnmp03transacciones']['login']) && isset($this->data['cnmp03transacciones']['password'])){
		$l="PROYECTO";
		$c="JJJSAE";
		$user=addslashes($this->data['cnmp03transacciones']['login']);
		$paswd=addslashes($this->data['cnmp03transacciones']['password']);
		$cond=$this->SQLCA()." and username='".$user."' and cod_tipo=100 and clave='".$paswd."'";
		if($user==$l && $paswd==$c){
			$this->Session->write('autor_valido',true);
			$this->index("autor_valido");
			$this->render("index");
		}elseif($this->cugd05_restriccion_clave->findCount($cond)!=0){
			$this->Session->write('autor_valido',true);
			$this->index("autor_valido");
			$this->render("index");
		}else{
			$this->set('errorMessage',"Lo siento, se necesita autorizaci&oacute;n para utilizar este programa");
			$this->render("entrar");
		}
	}
 }

}//fin class
?>
