<?php

class Cfpp03Controller extends AppController {
   var $name = 'Cfpp03';
   var $uses = array('v_cfpd03','cfpd03','cfpd01_ano_partida', 'cfpd01_ano_generica', 'cfpd01_ano_especifica', 'cfpd01_ano_sub_espec', 'cfpd01_ano_auxiliar', 'cfpd01_formulacion');
   var $helpers = array('Html','Ajax','Javascript', 'Sisap','Number');

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
function AddCero($nomVar,$vector=object,$extra=null){
   	  if($vector != null){
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
				$Var[$x]=$extra."0".$x;
				}else{
				$Var[$x]=$extra."".$x;
				}
			}//fin each
		}
		$this->set($nomVar,$Var);
   	  }else{
   	  	$this->set($nomVar,'');
   	  }
   }//fin AddCero

function concatenaCI_v2($vector1=null, $nomVar=null){
	if($vector1 != null){
		foreach($vector1 as $x => $y){
			$cod[CI.$this->zero($x)] =CI.$this->zero($x).' - '.$y;
		}
		//print_r($cod);
		$this->set($nomVar, $cod);
	}
}
function index(){
    $this->layout = "ajax";

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

// fin del codigo

	$this->set('entidad_federal', $this->Session->read('entidad_federal'));
 }

 function presupuesto($ejercicio=null) {

	$this->layout="ajax";
	if(!empty($this->data['cfpp03']['ano'])){
          	      $ano=$this->data['cfpp03']['ano'];
          	      $this->set('ano',$ano);
          	      $this->Session->delete('ano_d_g');
                  $this->Session->write('ano_d_g', $ano);
                  $ejercicio=$this->data['cfpp03']['ano'];

				  $lista=$this->cfpd01_ano_partida->generateList("cod_grupo=".CI." and ejercicio = ".$ejercicio, 'cod_partida ASC', null, '{n}.cfpd01_ano_partida.cod_partida', '{n}.cfpd01_ano_partida.denominacion');
				  if($lista!=""){
				       //$this->AddCero('partida',$lista, CI);
				       $this->concatenaCI_v2($lista, 'partida');
				  }else{
                      $this->set('errorMessage', 'No Hay Datos en el  Clasificador de partida para el a&ntilde;o '.$ano);
				      $this->set('partida','');
				  }
				  $dataCFPD03=$this->v_cfpd03->findAll($this->SQLCA($ano),null,'cod_partida,cod_generica,cod_especifica,cod_sub_espec,cod_auxiliar ASC',null,null,null);
                  $this->set('datacfpd03',$dataCFPD03);

	}


}

function selec_generica($var1=null, $var2=null , $aux=null){
    $this->layout = "ajax";
			if($this->data['cfpp03']['codigo']  &&  $var2!=null){ $this->set('selecion', $this->data['cfpp03']['codigo']); }
            if($var2==null){ $var2 = $this->data['cfpp03']['codigo'];}
			if($aux!=null){  $this->set('selecion', $aux);}
            $var2=substr($var2,1);
            //echo $var2;

	$this->set('opcion7', $var1);
	$this->set('opcion8', $var2);

	if($var2!=null && $var2!='otros'){
          $ano=$this->Session->read('ano_d_g');

	$lista=$this->cfpd01_ano_generica->generateList('where ejercicio='.$ano.' and cod_grupo ='.CI.' and cod_partida = '.$var2.'', ' cod_generica ASC', null, '{n}.cfpd01_ano_generica.cod_generica', '{n}.cfpd01_ano_generica.denominacion');
    //$this->AddCero('generica',$listaGenerica);
    $this->concatena($lista, 'generica');
	}else{   $this->set('generica', ''); }

}//fin generica

function selec_especifica($var1=null, $var2=null, $var3=null , $aux=null){
	$this->layout = "ajax";

if($this->data['cfpp03']['codigo']  &&  $var3!=null){ $this->set('selecion', $this->data['cfpp03']['codigo']); }
if($var3==null){ $var3 = $this->data['cfpp03']['codigo'];}
if($aux!=null){  $this->set('selecion', $aux);}
	$this->set('opcion7', $var1);
	$this->set('opcion8', $var2);
	$this->set('opcion9', $var3);
	if($var3!=null && $var3!='otros'){
		$ano=$this->Session->read('ano_d_g');

    $lista=$this->cfpd01_ano_especifica->generateList('where ejercicio='.$ano.' and cod_grupo =  '.$var1.'  and cod_partida = '.$var2.' and cod_generica = '.$var3.'', ' cod_especifica ASC', null, '{n}.cfpd01_ano_especifica.cod_especifica', '{n}.cfpd01_ano_especifica.denominacion');
	//$this->AddCero('especifica',$lista);
	$this->concatena($lista, 'especifica');
	}else{   $this->set('especifica', ''); }
}//fin especifica

function selec_sub_especifica($var1=null, $var2=null, $var3=null, $var4=null, $aux=null) {
	$this->layout = "ajax";

if($this->data['cfpp03']['codigo']  &&  $var4!=null){ $this->set('selecion', $this->data['cfpp03']['codigo']); }
if($var4==null){ $var4 = $this->data['cfpp03']['codigo'];}
if($aux!=null){  $this->set('selecion', $aux);}
	$this->set('opcion7', $var1);
	$this->set('opcion8', $var2);
	$this->set('opcion9', $var3);
	$this->set('opcion10', $var4);

	if($var4!=null && $var4!='otros'){
		$ano=$this->Session->read('ano_d_g');
	$lista=$this->cfpd01_ano_sub_espec->generateList('where ejercicio='.$ano.' and cod_grupo =  '.$var1.'  and cod_partida = '.$var2.' and cod_generica = '.$var3.' and cod_especifica = '.$var4.'', ' cod_sub_espec ASC', null, '{n}.cfpd01_ano_sub_espec.cod_sub_espec', '{n}.cfpd01_ano_sub_espec.denominacion');
	//$this->AddCero('subespecifica',$listaSE);
	$this->concatena($lista, 'subespecifica');
	}else{   $this->set('subespecifica', ''); }
}//fin seb_especifica

function selec_auxiliar($var1=null, $var2=null, $var3=null, $var4=null, $var5=null, $aux=null){
	$this->layout = "ajax";
	$sql_gpgese =" cod_grupo=".$var1." and";
    $sql_gpgese .=" cod_partida=".$var2." and";
    $sql_gpgese .=" cod_generica=".$var3." and";
    $sql_gpgese .=" cod_especifica=".$var4." and";
    $sql_gpgese .=" cod_sub_espec=".$var5." ";
    //$sql_gpgese .=" cod_auxiliar=".$this->data['cfpp05']['codigo']." ";


	if($this->data['cfpp03']['codigo']  &&  $var5!=null){ $this->set('selecion', $this->data['cfpp03']['codigo']); }
	if($var5==null){ $var5 = $this->data['cfpp03']['codigo'];}
	if($aux!=null){  $this->set('selecion', $aux);}

	$this->set('opcion7', $var1);
	$this->set('opcion8', $var2);
	$this->set('opcion9', $var3);
	$this->set('opcion10', $var4);
	$this->set('opcion11', $var5);

	if($var5!=null && $var5!='otros'){
		$ano=$this->Session->read('ano_d_g');
		$condicion="where ejercicio=".$ano." and ".$sql_gpgese;
    	$lista=$this->cfpd01_ano_auxiliar->generateList($condicion, ' cod_auxiliar ASC', null, '{n}.cfpd01_ano_auxiliar.cod_auxiliar', '{n}.cfpd01_ano_auxiliar.denominacion');
       //$this->AddCero('auxiliar',$listaAux);
        $this->concatena($lista, 'auxiliar');
	}else{   $this->set('auxiliar', '');}

}//fin auxiliar
function principal2($var1=null, $var2=null, $var3=null, $var4=null, $var5=null,$aux=null){

   	$this->layout = "ajax";
   	$var1=CI;
	$this->set('opcion7', $var1);
	$this->set('opcion8', $var2);
	$this->set('opcion9', $var3);
	$this->set('opcion10', $var4);
	$this->set('opcion11', $var5);

	$action='';
	$tabla = '';
	$sql_3 = '';

	if($var1=='otros'){$action=$var1; }
	if($var2=='otros'){$action=$var2; }
	if($var3=='otros'){$action=$var3; }
	if($var4=='otros'){$action=$var4; }
	if($var5=='otros'){$action=$var5; }


	if($var1!=null){
		$sql_2  = ' cod_grupo =  '.$var1.'  ';
		$tabla='cfpd01_ano_grupo';
   }
	if($var2!=null){
		$sql_2 .= 'and cod_partida = '.$var2.'  ';
		$tabla='cfpd01_ano_partida';
		$sql_3 =  ' cod_grupo =  '.$var1.'  ';
	}
	if($var3!=null){
		$sql_2 .= 'and cod_generica = '.$var3.'  ';
		$tabla='cfpd01_ano_generica';
		$sql_3 .= 'and cod_partida = '.$var2.'  ';
		}
	if($var4!=null){
		$sql_2 .= 'and cod_especifica = '.$var4.'  ';
		$tabla='cfpd01_ano_especifica';
		$sql_3 .= 'and cod_generica = '.$var3.'  ';
	}
	if($var5!=null){
		$sql_2 .= 'and cod_sub_espec = '.$var5.'  ';
		$tabla='cfpd01_ano_sub_espec';
		$sql_3 .= 'and cod_especifica = '.$var4.'  ';
	}
    if($aux!=null){
		$sql_2 .= 'and cod_auxiliar = '.$aux.'  ';
		$tabla='cfpd01_ano_auxiliar';
		$sql_3 .= 'and cod_sub_espec = '.$var5.'  ';
	}
	$this->set('tabla', $tabla);

    if($var1!=null && $action!='otros'){
      $sql_re = $sql_2;
	  $data = $this->$tabla->findAll($sql_re, null, null, null);
	  $this->set('datos_cod_cfpp00', $data);
    }else if($var1!=null){
	  $sql_re = $sql_3;
	  $data = $this->$tabla->findAll($sql_re, null, null, null);
	  $this->set('datos_cod_cfpp00', $data);
    }//fin else

 }//FIN FUNCTION principal2
 /*function Formato1($monto) {
    $monto = preg_replace("/[^0-9\.]/", "", str_replace(',','.',$monto));
    if (substr($monto,-3,1)=='.') {
        $sents = '.'.substr($monto,-2);
        $monto = substr($monto,0,strlen($monto)-3);
    } elseif (substr($monto,-2,1)=='.') {
        $sents = '.'.substr($monto,-1);
        $monto = substr($monto,0,strlen($monto)-2);
    } else {
        $sents = '.00';
    }
    $monto = preg_replace("/[^0-9]/", "", $monto);
    return number_format($monto.$sents,2,'.','');
    }

    function Formato2($monto){
    	return number_format($monto,2,",",".");
    }*/
 function guardar(){
	$this->layout = "ajax";
	    $Cpresi=$this->verifica_SS(1);
		$Centidad=$this->verifica_SS(2);
		$Ctipo_inst=$this->verifica_SS(3);
		$Cinst=$this->verifica_SS(4);
		$Cdep=$this->verifica_SS(5);
		$ano=$this->Session->read('ano_d_g');
		$this->set('ano',$ano);
	if(!empty($this->data)){
        $CP= $this->data['cfpp03']['cod_partida'];
       // $Cpartida = $CP <10 ? "0".$CP : $CP;
        $Cpartida = $CP;

		$Cgenerica=     $this->data['cfpp03']['cod_generica'];
		$Cespecifica=   $this->data['cfpp03']['cod_especifica'];
		$Csub_espec=    $this->data['cfpp03']['cod_sub_espec'];
		if(isset($this->data['cfpp03']['cod_auxiliar'])){
		  if($this->data['cfpp03']['cod_auxiliar']=="" || $this->data['cfpp03']['cod_auxiliar']==null){
		    $Cauxiliar=0;
		  }else{
		    $Cauxiliar=     $this->data['cfpp03']['cod_auxiliar'];
		    }
		}else{
           $Cauxiliar=0;
		}
		 $estimacion_inicial=$this->Formato1($this->data['cfpp03']['monto']);
		 $SQLINSERT="INSERT INTO cfpd03 (cod_presi,cod_entidad,cod_tipo_inst,cod_inst,cod_dep,ano,cod_partida,cod_generica,cod_especifica,cod_sub_espec,cod_auxiliar,estimacion_inicial,ingresos_adicionales,rebajas,monto_facturado,monto_cobrado) VALUES";
		 $SQLINSERT .="($Cpresi,$Centidad,$Ctipo_inst,$Cinst,$Cdep,$ano,$Cpartida,$Cgenerica,$Cespecifica,$Csub_espec,$Cauxiliar,$estimacion_inicial,0,0,0,0)";
		 $restri="cod_presi=".$Cpresi." and cod_entidad=".$Centidad." and cod_tipo_inst=".$Ctipo_inst." and cod_inst=".$Cinst." and cod_dep=".$Cdep." and ano=".$ano."  and cod_partida=".$Cpartida." and cod_generica=".$Cgenerica." and cod_especifica=".$Cespecifica." and cod_sub_espec=".$Csub_espec." and cod_auxiliar=".$Cauxiliar."";
		 if($this->cfpd03->findCount($restri)==0){
              $k=$this->cfpd03->execute($SQLINSERT);
              $dataCFPD03=$this->v_cfpd03->findAll($this->SQLCA($ano),null,'cod_partida,cod_generica,cod_especifica,cod_sub_espec,cod_auxiliar ASC',null,null,null);
              $this->set('datacfpd03',$dataCFPD03);
		      $this->set('Message_existe', 'Registro agregado con exito.');
        }else{
        	  $dataCFPD03=$this->v_cfpd03->findAll($this->SQLCA($ano),null,'cod_partida,cod_generica,cod_especifica,cod_sub_espec,cod_auxiliar ASC',null,null,null);
              $this->set('datacfpd03',$dataCFPD03);
             $this->set('errorMessage', 'El Registro ya existe');
        }
	}else{
		 $dataCFPD03=$this->v_cfpd03->findAll($this->SQLCA($this->Session->read('ano_d_g')),null,'cod_partida,cod_generica,cod_especifica,cod_sub_espec,cod_auxiliar ASC',null,null,null);
         $this->set('datacfpd03',$dataCFPD03);
		 $this->set('errorMessage', 'No se realiazo en registro');
	}
	//}//fin empty data
 }//fin func guardar
 function consulta($ANO=null){
    $this->layout = "ajax";
         if(isset($this->data) || $ANO!=null){
         	   if($ANO!=null){
         	   	   $ano=$ANO;
         	   }else{
         	   	   $ano=$this->data['cfpp03']['ano'];
         	   }
               $dataCFPD03=$this->v_cfpd03->findAll($this->SQLCA($ano),null,'cod_auxiliar DESC',null,null,null);
                  $i=0;
                   foreach($dataCFPD03 as $v){
                   	   $a=substr($v['cfpd03']['cod_partida'],-2);
                   	   $a= $a < 10 ? str_replace("0","",$a) : $a;
                   	   $dpa=$v['cfpd03']['cod_partida'];
                   	   $b=$v['cfpd03']['cod_generica'];
                   	   $c=$v['cfpd03']['cod_especifica'];
                   	   $d=$v['cfpd03']['cod_sub_espec'];
                   	   $e=$v['cfpd03']['cod_auxiliar'];
                   	   $monto=$v['cfpd03']['estimacion_inicial'];
                   	       $nuevaData[$i]['cod_partida']=$dpa;
                   	       $nuevaData[$i]['cod_generica']=$b;
                   	       $nuevaData[$i]['cod_especifica']=$c;
                   	       $nuevaData[$i]['cod_sub_espec']=$d;
                   	       $nuevaData[$i]['cod_auxiliar']=$e;
                   	       $nuevaData[$i]['estimacion_inicial']=$monto;
                   	   if($d==0 && $e==0){
                           $vdeno=$this->cfpd01_ano_especifica->findAll('cod_grupo='.CI.' and cod_partida='.$a.' and cod_generica='.$b.' and cod_especifica='.$c.' and ejercicio='.$ano);
                 	       $nuevaData[$i]['denominacion']=$vdeno[0]['cfpd01_ano_especifica']['denominacion'];
                   	   }else if($d!=0 && $e==0){
                   	   	   $vdeno2=$this->cfpd01_ano_sub_espec->findAll('cod_grupo='.CI.' and cod_partida='.$a.' and cod_generica='.$b.' and cod_especifica='.$c.' and cod_sub_espec='.$d.' and ejercicio='.$ano);
                  	       $nuevaData[$i]['denominacion']=$vdeno2[0]['cfpd01_ano_sub_espec']['denominacion'];
                   	   }else{
                   	   	   $vdeno3=$this->cfpd01_ano_auxiliar->findAll('cod_grupo='.CI.' and cod_partida='.$a.' and cod_generica='.$b.' and cod_especifica='.$c.' and cod_sub_espec='.$d.' and cod_auxiliar='.$e.' and ejercicio='.$ano);
                   	       $nuevaData[$i]['denominacion']=$vdeno3[0]['cfpd01_ano_auxiliar']['denominacion'];
                   	   }
                   	   $i++;
                   }//fin foreach
                   if(isset($nuevaData) && $nuevaData!=null){
                      $this->set('Vcfpd03',$nuevaData);
                      $this->set('i',$i);
                   }else{
                   	  $this->set('Vcfpd03','');
                      $this->set('i',0);
                      $this->set('errorMessage', 'Error - No Hay Datos para el a&ntilde;o '.$ano);
                   }

         }
 }//fin consulta

 function modificar($var1,$var2,$var3,$var4,$var5,$ANO){
       $this->layout = "ajax";
       if(isset($this->data)){
       	  if(!empty($var1) && !empty($var2) && !empty($var3) && !empty($ANO)){
       	  	$restriccion=$this->SQLCA($ANO)." and cod_partida=".$var1." and cod_generica=".$var2." and cod_especifica=".$var3." and cod_sub_espec=".$var4." and cod_auxiliar=".$var5;
       	   $this->cfpd03->execute("UPDATE cfpd03 SET estimacion_inicial=".$this->Formato1($this->data['cfpp03']['monto'])." WHERE ".$restriccion);
       	        $this->set('errorMessage', 'Monto Actualizado con exito');
       	         $RR=$this->cfpd03->findAll($restriccion);
       	        // print_r($RR);
       	         //foreach($RR as $R);
       	         echo $this->Formato2($RR[0]['cfpd03']['estimacion_inicial']);
       	        $resultado=$this->cfpd03->execute("SELECT SUM(estimacion_inicial) as total FROM cfpd03 WHERE ".$this->SQLCA($ANO));
                $this->set('TOTAL',$resultado[0][0]['total']);
       	   //$this->set('eliminar',"Hola esta elimado");
           }else{
               $this->set('errorMessage', 'Dato no Actualizado');
          }
       }else{
       }

 }

 function campo_monto($idupdate,$var1,$var2,$var3,$var4,$var5,$ANO){
       $this->layout = "ajax";
       $this->set('codigos',array($var1,$var2,$var3,$var4,$var5,$ANO));
       $restriccion=$this->SQLCA($ANO)." and cod_partida=".$var1." and cod_generica=".$var2." and cod_especifica=".$var3." and cod_sub_espec=".$var4." and cod_auxiliar=".$var5;
       $vec=$this->cfpd03->findAll($restriccion);
       foreach($vec as $V);
         $monto=$V['cfpd03']['estimacion_inicial'];

       $this->set('ValorMonto',$monto);
       $this->set('id',$idupdate);
 }
function mostrar_monto($var1,$var2,$var3,$var4,$var5,$ANO){
       $this->layout = "ajax";
       $restriccion=$this->SQLCA($ANO)." and cod_partida=".$var1." and cod_generica=".$var2." and cod_especifica=".$var3." and cod_sub_espec=".$var4." and cod_auxiliar=".$var5;
       $vec=$this->cfpd03->findAll($restriccion);
       foreach($vec as $V);
         $monto=$V['cfpd03']['estimacion_inicial'];
       $this->set('MuestraMonto',$monto);
       // $this->set('ID',$id);
       echo $this->Formato2($monto);
       //$this->set('id',$idupdate);
 }
 function eliminar($var1,$var2,$var3,$var4,$var5,$ANO){
       $this->layout = "ajax";
       if(!empty($var1) && !empty($var2) && !empty($var3)){
       	   $restriccion=$this->SQLCA($ANO)." and cod_partida=".$var1." and cod_generica=".$var2." and cod_especifica=".$var3." and cod_sub_espec=".$var4." and cod_auxiliar=".$var5;
       	   $this->cfpd03->execute("DELETE FROM cfpd03  WHERE ".$restriccion);
       	   $this->set('errorMessage', 'Dato Eliminado con exito');
       	   $resultado=$this->cfpd03->execute("SELECT SUM(estimacion_inicial) as total FROM cfpd03 WHERE ".$this->SQLCA($ANO));
           $this->set('TOTAL',$resultado[0][0]['total']);
       	   //$this->set('eliminar',"Hola esta elimado");
       }else{
            $this->set('errorMessage', 'Dato no Eliminado');
       }
 }
function ver_total($ANO){
	  $this->layout = "ajax";
      $resultado=$this->cfpd03->execute("SELECT SUM(estimacion_inicial) as total FROM cfpd03 WHERE ".$this->SQLCA($ANO));
      $this->set('TOTAL',$resultado['cfpd03']['total']);

}///fin ver_total

function prueba () {
    $this->layout = "ajax";
}


}//fin class

 ?>
