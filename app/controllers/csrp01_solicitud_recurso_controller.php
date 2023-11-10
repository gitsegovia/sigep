<?php
class Csrp01SolicitudRecursoController extends AppController{


	//var $uses = array('cnmd03_transacciones','cnmd09_asignacion_calcula_asignacion','Cnmd01','ccfd03_instalacion','v_cnmd09_asignacion_calcula_asignacion_2','cnmd09_asignacion_calcula_asignacion_2');
 	var $uses = array('v_cfpd05_denominaciones','csrd01_solicitud_recurso_cuerpo','v_csrd01_solicitud_recurso_cuerpo','csrd01_solicitud_recurso_numero','csrd01_solicitud_recurso_partidas','ccfd03_instalacion','cfpd05','v_solicitud_cfpd05_p2','cstd01_entidades_bancarias',
					'cstd01_sucursales_bancarias','cstd02_cuentas_bancarias','cstd03_cheque_cuerpo','v_solicitud_cfpd05_pp2','v_cfpd05_disponibilidad','ccfd04_cierre_mes','csrd01_tipo_solicitud');
 	var $helpers = array('Html','Ajax','Javascript', 'Sisap');
	//var $layout =  "administradors";
function checkSession()
    {
        // If the session info hasn't been set...
        if (!$this->Session->check('Usuario'))
        {
            // Force the user to login
            $this->redirect('/salir/');
						exit();
        }
    }//checkSession



	function beforeFilter(){

		$this->checkSession();

}//beforeFilter






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


function Cfecha($fecha,$tipo_return){
      if($tipo_return=="A-M-D"){
           $paso = explode('/', $fecha);
           $fecha_aux[] = $paso[2];
           $fecha_aux[] = $paso[1];
           $fecha_aux[] = $paso[0];
           $fecha_return=implode('-', $fecha_aux);
      }else if($tipo_return=="D/M/A"){
           $paso = explode('-', $fecha);
           $fecha_aux[] = $paso[2];
           $fecha_aux[] = $paso[1];
           $fecha_aux[] = $paso[0];
           $fecha_return=implode('/', $fecha_aux);
     }
     return $fecha_return;
}
/**
 * #########################################################
 *
 */



function concatena_prueba($vector1=null, $nomVar=null){

	if($vector1 != null){

		foreach($vector1 as $x => $y){
			$cod[$x] = $this->zero($x).' - '.$this->Cfecha($y,'D/M/A');
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




function index($var=null){///////////////<<--INDEX
	 $this->layout = "ajax";

	 $ano=$this->ano_ejecucion();

      $maxi=$this->csrd01_solicitud_recurso_numero->findCount($this->SQLCA()." and ano_solicitud=".$ano." and situacion=1");
      //$max=$this->cepd01_compromiso_numero->execute("SELECT numero_compromiso FROM cepd01_compromiso_numero WHERE ".$this->SQLCA()."  ORDER BY numero_compromiso ASC LIMIT 1");
      if($maxi==0){
         $this->set("errorMessage","Verifique el n&uacute;mero de control de solicitudes");
      	 $this->set("numero_solicitud","");
      	 $this->redirect("/csrp01_solicitud_recurso_numero/index/numero");
      }
      if(isset($_SESSION["MSJ"])){
					$a=$_SESSION["MSJ"];
					if($a["tipo_msj"]=="exito")$this->set('Message_existe', $a["msj"]);
					else if($a["tipo_msj"]=="error")$this->set('errorMessage', $a["msj"]);
					$this->Session->delete("MSJ");
	  }

}//fin index



function index2(){
	$this->layout = "ajax";
//echo "da error cuando el monto es 0.00";
/*	$ano='';
 $condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').'';
 $year = $this->ccfd03_instalacion->findAll($condicion, null, 'ano_arranque ASC', null);
//
 $ano = null;
 foreach($year as $year){$ano = $year['ccfd03_instalacion']['ano_arranque'];}
 */
 $ano=$this->ano_ejecucion();
 $this->set('ano',$ano);//el año

$this->set('dependencia',$_SESSION["dependencia"]);////La dependencia

$lista = $this->csrd01_tipo_solicitud->generateList(null, $order = 'cod_tipo_solicitud', $limit = null, '{n}.csrd01_tipo_solicitud.cod_tipo_solicitud', '{n}.csrd01_tipo_solicitud.denominacion');
$this->concatena($lista, 'tipo');

$cond= $this->SQLCA();

$this->limpiar_lista();
			//$cond.=" and cod_entidad_bancaria=".$cod_1." and cod_sucursal=".$cod_2." and cuenta_bancaria=".$var." and ano_movimiento=".$ano;
			//buscar para que el codigo sea automatico
			/*$v=$this->csrd01_solicitud_recurso_numero->execute("SELECT numero_solicitud FROM csrd01_solicitud_recurso_numero WHERE ".$cond." ORDER BY numero_solicitud ");
			//print_r($v);
			if($v!=null){
				$numero=$v[0][0]["numero_solicitud"];
				$numero = $numero =="" ? 1 : $numero+1;
			}else{
				$numero=1;
			}
			//echo $numero;
		$this->set("numero",$numero);*/

/////////////////////////////////////AQUI LA PRUEBA DEL NUMERO////////////////////////////
 $dato=$this->ano_ejecucion();
  $maxi=$this->csrd01_solicitud_recurso_numero->findCount($this->SQLCA());
      $max=$this->csrd01_solicitud_recurso_numero->execute("SELECT numero_solicitud FROM csrd01_solicitud_recurso_numero WHERE ".$this->SQLCA()." and ano_solicitud=".$dato." and situacion=1 ORDER BY numero_solicitud ASC LIMIT 1");
      //echo "numero".$maxi;
      //print_r($max);
      if($max!=null){
      	    $codigo=$max[0][0]["numero_solicitud"];
            $resultado=$this->csrd01_solicitud_recurso_numero->execute("UPDATE  csrd01_solicitud_recurso_numero SET situacion=2 WHERE ".$this->SQLCA()." and numero_solicitud=".$codigo." and ano_solicitud=".$dato);
	         if($resultado>1){
                //$this->set("Message_existe","Situacion de solicitud actualizada con exito");
               $this->set("numero_solicitud",$codigo);
	         }else{
		        $this->set("errorMessage","Por favor Verifique el n&uacute;mero de control de solicitudes");
		        $this->set("numero_solicitud","");
		        $MSJ1=array("msj"=>"debe registrar nuevos numeros para la solicitud de recursos","tipo_msj"=>"exito");
				$this->Session->write("MSJ1",$MSJ1);
		        $this->redirect("/csrp01_solicitud_recurso_numero/index/numero/otro");
	      }
      }else{
      	 $this->set("errorMessage","Verifique el n&uacute;mero de control de solicitudes");
      	 $this->set("numero_solicitud","");
      	 $MSJ1=array("msj"=>"debe registrar nuevos numeros para la solicitud de recursos","tipo_msj"=>"exito");
		 $this->Session->write("MSJ1",$MSJ1);
      	 $this->redirect("/csrp01_solicitud_recurso_numero/index/numero/otro");
      }

 ///////////////////////////////////FIN PRUEBA NUMERO//////////////////////////////////

$meses= array('1'=>'Enero','2'=>'Febrero','3'=>'Marzo','4'=>'Abril','5'=>'Mayo','6'=>'Junio','7'=>'Julio','8'=>'Agosto','9'=>'Septiembre','10'=>'Octubre','11'=>'Noviembre','12'=>'Diciembre');
$this->concatena($meses, 'mes');

/*
   $x=$this->csrd01_solicitud_recurso_cuerpo->execute("SELECT cod_entidad_bancaria, cod_sucursal, numero_cheque FROM csrd01_solicitud_recurso_cuerpo WHERE ".$cond);
	if($x!=null){
		$cheque=$x[0][0]["numero_cheque"];
		if($cheque!=0){
			//echo "hoooola";
			 $entidad=$x[0][0]["cod_entidad_bancaria"];
			 $sucursal=$x[0][0]["cod_sucursal"];
			$e=$this->cstd01_entidades_bancarias->execute("SELECT * FROM cstd01_entidades_bancarias WHERE cod_entidad_bancaria=".$entidad);
			if($e!=null){
				$deno_entidad=$e[0][0]["denominacion"];
			}else{echo "aqui";
				$deno_entidad="";
			}
			$d=$this->cstd01_sucursales_bancarias->execute("SELECT * FROM cstd01_sucursales_bancarias WHERE cod_entidad_bancaria=".$entidad." and cod_sucursal=".$sucursal);
			if($d!=null){
				$deno_sucursal=$d[0][0]["denominacion"];
			}else{
				$deno_sucursal="";
			}
			$f=$this->cstd02_cuentas_bancarias->execute("SELECT * FROM cstd02_cuentas_bancarias WHERE ".$cond." and cod_entidad_bancaria=".$entidad." and cod_sucursal=".$sucursal);
			//print_r($f);
			if($f!=null){
				$cuenta=$f[0][0]["cuenta_bancaria"];
			}else{
				$cuenta="";
			}

			if($cuenta!=""){
					$g=$this->cstd03_cheque_cuerpo->execute("SELECT * FROM cstd03_cheque_cuerpo WHERE ".$cond." and cod_entidad_bancaria=".$entidad." and cod_sucursal=".$sucursal." and cuenta_bancaria='".$cuenta."'");
					//print_r($g);
				if($g!=null){
					$fecha_cheque=$g[0][0]["fecha_cheque"];
					$monto=$g[0][0]["monto"];
				}else{
					//$cuenta="";
					$fecha_cheque="";
					$monto="";
				}
			}else{
					$fecha_cheque="";
					$monto="";
			}


		//	$fecha_cheque=$g[0][0]["fecha_cheque"];
			//$monto=$g[0][0]["monto"];
			$this->set("cheque",$cheque);
			$this->set("deno_entidad",$deno_entidad);
			$this->set("deno_sucursal",$deno_sucursal);
			$this->set("cuenta",$cuenta);
			$this->set("fecha_cheque",$fecha_cheque);
			$this->set("monto",$monto);
		}else{
			//echo "cheque vacio";
			$this->set("cheque","");
			$this->set("deno_entidad","");
			$this->set("deno_sucursal","");
			$this->set("cuenta","");
			$this->set("fecha_cheque","");
			$this->set("monto","");
		}
	}else{
			$this->set("cheque","");
			$this->set("deno_entidad","");
			$this->set("deno_sucursal","");
			$this->set("cuenta","");
			$this->set("fecha_cheque","");
			$this->set("monto","");
		//echo "hooooooola2222";
		$cheque="";
	}
*/

   /*
$data=$this->csrd01_solicitud_recurso_partidas->findAll($this->SQLCA(),null,null);
   	if(!$data){
   		echo "<script>";
   			echo "document.getElementById('consultar_index').disabled='disabled';";
   		echo "</script>";
   		//echo "no consulto";
   	}else{
   		echo "<script>";
   			echo "document.getElementById('consultar_index').disabled=false;";
   		echo "</script>";
   		*/



}//FIN INDEX



function mostrar($opcion=null,$cod=null){
	$this->layout="ajax";
if($cod!=null){
	switch ($opcion){
		case'tipo':
			$deno_nomina = $this->csrd01_tipo_solicitud->field('denominacion'," cod_tipo_solicitud=".$cod, $order ="cod_tipo_solicitud ASC");
			$this->set('deno_tipo', $deno_nomina);
			$this->set('codigo', 'tipo_recurso');
		break;
		case'radio':
			$this->set('radio',$cod);
		break;
		case'select':
			$meses= array('1'=>'Enero','2'=>'Febrero','3'=>'Marzo','4'=>'Abril','5'=>'Mayo','6'=>'Junio','7'=>'Julio','8'=>'Agosto','9'=>'Septiembre','10'=>'Octubre','11'=>'Noviembre','12'=>'Diciembre');
			$this->concatena($meses, 'mes');
			$this->set('select',$cod);
		break;
	}
}
}// fin mostrar


function activ_obra(){
	$this->layout="ajax";
	$ano =$this->ano_ejecucion();
	 $clasificada =$this->data["csrp01_solicitud_recurso2"]["clasificadas"];
	$this->set('ver',$clasificada);
	$lista=$this->v_solicitud_cfpd05_p2->execute("select distinct cod_activ_obra from v_solicitud_cfpd05_p2 where ".$this->SQLCA($ano));
	foreach($lista as $l){
		$v[]=$l[0]["cod_activ_obra"];
	}
	$lista = array_combine($v, $v);
	$this->set('ano',$lista);
	if($clasificada==3){
		$this->set('selects','');
		$ano =$this->ano_ejecucion();
		$lista=  $this->v_cfpd05_denominaciones->generateList($this->SQLCA()." and ano=".$ano, 'ano ASC', null, '{n}.v_cfpd05_denominaciones.ano','{n}.v_cfpd05_denominaciones.ano');
		$this->set('ano',$lista);
		$this->limpiar_lista();
		/*echo "<script>";
   			echo "document.getElementById('save').disabled='disabled';";
   		echo "</script>";*/
	}


}//activ_obra




function grilla2($var=null){
	$this->layout="ajax";
	//echo "llega";
//print_r($_SESSION["recurso"]);
		/*echo "<script>";
   			echo "document.getElementById('save').disabled=false;";
   		echo "</script>";*/
$ano1=$this->ano_ejecucion();
	$cond= $this->SQLCA();
	  $frecuencia=$this->Session->read('frecuen');
	  $mes=$this->Session->read('mes');

	 $clasificada=$this->Session->read('clasificada');
	 $this->set('frecuencia',$frecuencia);
	 $this->set('mes',$mes);
//print_r($monto_use);
	if($clasificada==1){
		$datos = $this->v_solicitud_cfpd05_pp2->findAll($cond." and ano=".$ano1." and cod_activ_obra=".$var);
		$this->set('partidas','');
		$monto_use=$this->csrd01_solicitud_recurso_partidas->execute("select cod_sector,cod_programa,cod_sub_prog,cod_proyecto,cod_activ_obra,cod_partida,sum(monto) as monto,sum(monto_entregado) as monto_entregado from csrd01_solicitud_recurso_partidas where ".$this->SQLCA()." and ano_solicitud=".$ano1." group by cod_sector,cod_programa,cod_sub_prog,cod_proyecto,cod_activ_obra,cod_partida order by cod_sector,cod_programa,cod_sub_prog,cod_proyecto,cod_activ_obra,cod_partida asc");
		$this->set('usados',$monto_use);
		/*echo "<script>";
   			echo "document.getElementById('save').disabled=false;";
   		echo "</script>";*/
	}else if($clasificada==2){
		$datos = $this->v_solicitud_cfpd05_p2->findAll($cond." and ano=".$ano1."  and cod_activ_obra=".$var);
		$this->set('subpartidas','');
		$monto_use=$this->csrd01_solicitud_recurso_partidas->execute("select cod_sector,cod_programa,cod_sub_prog,cod_proyecto,cod_activ_obra,cod_partida,cod_generica,cod_especifica,cod_sub_espec,cod_auxiliar,sum(monto) as monto,sum(monto_entregado) as monto_entregado from csrd01_solicitud_recurso_partidas where ".$this->SQLCA()." and ano_solicitud=".$ano1." group by cod_sector,cod_programa,cod_sub_prog,cod_proyecto,cod_activ_obra,cod_partida,cod_generica,cod_especifica,cod_sub_espec,cod_auxiliar order by cod_sector,cod_programa,cod_sub_prog,cod_proyecto,cod_activ_obra,cod_partida,cod_generica,cod_especifica,cod_sub_espec,cod_auxiliar asc");
		$this->set('usados',$monto_use);
		/*echo "<script>";
   			echo "document.getElementById('save').disabled=false;";
   		echo "</script>";*/
	}
		$this->set('datos',$datos);
}//FIN GRILLA2


function monto_solicitar($i=null,$monto_ver=null,$var=null){
	$this->layout="ajax";
//echo "si".$var;
//print_r($_SESSION["recursos"]);
//echo "aqui";
 $this->set('i',$i);
 $clasificada=$this->Session->read('clasificada');
 $sum_monto=0;
 if($clasificada==1){
	$vec_recurso=$_SESSION["recursos"];
	$_SESSION["recursos"]=array();
	//print_r($_SESSION["recursos"]);echo "<hr>";
	for($j=0;$j<count($vec_recurso);$j++){
		if($j==$i){
			$var=$this->Formato1($var);
			$var=sprintf("%01.2f",$var);
			$vec[$i]['ano']=$vec_recurso[$i]['ano'];
			$vec[$i]['cod_sector']=$vec_recurso[$i]['cod_sector'];
			$vec[$i]['cod_prog']=$vec_recurso[$i]['cod_prog'];
			$vec[$i]['cod_sub_prog']=$vec_recurso[$i]['cod_sub_prog'];
			$vec[$i]['cod_proy']=$vec_recurso[$i]['cod_proy'];
			$vec[$i]['cod_activ_obra']=$vec_recurso[$i]['cod_activ_obra'];
			$vec[$i]['cod_partida']=$vec_recurso[$i]['cod_partida'];
			$vec[$i]['disponibilidad']=$vec_recurso[$i]['disponibilidad'];
			$vec[$i]['asig_anual']=$vec_recurso[$i]['asig_anual'];//agregue esto
			$dispo=sprintf("%01.2f",$vec[$i]['disponibilidad']);
			if($dispo<=0 || $vec_recurso[$i]['monto']<=0){
				$vec[$i]['monto']=$vec_recurso[$i]['monto'];
				$variable=$vec_recurso[$i]['monto'];
				$bueno="nada";
				$this->set('no','');
			}else if($var<=$dispo){
				$vec[$i]['monto']=$var;
				$bueno="si";
				$this->set('si','');
				echo "<script>";
   					echo "document.getElementById('input_tag".$i."').readOnly=true;";
   				echo "</script>";
			}else{
				//$vec[$i]['monto']=0;
				$vec[$i]['monto']=$vec_recurso[$i]['monto'];
				$variable=$vec_recurso[$i]['monto'];
				$bueno="no";
				$this->set('no','');
			}
			$vec[$i]['id']=$i;
		}else{
			$vec[$j]=$vec_recurso[$j];
		}
	}//fin for

	$_SESSION["recursos"]=$_SESSION["recursos"]+$vec;
	if($bueno=="no"){
		$this->set('errorMessage', 'El monto que ingreso es mayor a la disponibilidad');
			echo "<script>";
	   			echo "document.getElementById('input_tag".$i."').value='".$this->Formato2($variable)."';";
	   		echo "</script>";
	}else if($bueno=="nada"){
		$this->set('errorMessage', 'esta partida posee saldos negativos');
			echo "<script>";
   			echo "document.getElementById('input_tag".$i."').value='".$this->Formato2($variable)."';";
   		echo "</script>";
	}


 }else if($clasificada==2){
		$vec_recurso=$_SESSION["recursos"];
	   $_SESSION["recursos"]=array();
	for($j=0;$j<count($vec_recurso);$j++){
		if($j==$i){
			$var=$this->Formato1($var);
			$var=sprintf("%01.2f",$var);
			$vec[$i]['ano']=$vec_recurso[$i]['ano'];
			$vec[$i]['cod_sector']=$vec_recurso[$i]['cod_sector'];
			$vec[$i]['cod_prog']=$vec_recurso[$i]['cod_prog'];
			$vec[$i]['cod_sub_prog']=$vec_recurso[$i]['cod_sub_prog'];
			$vec[$i]['cod_proy']=$vec_recurso[$i]['cod_proy'];
			$vec[$i]['cod_activ_obra']=$vec_recurso[$i]['cod_activ_obra'];
			$vec[$i]['cod_partida']=$vec_recurso[$i]['cod_partida'];
			$vec[$i]['disponibilidad']=$vec_recurso[$i]['disponibilidad'];
			$vec[$i]['cod_generica']=$vec_recurso[$i]['cod_generica'];
			$vec[$i]['cod_especifica']=$vec_recurso[$i]['cod_especifica'];
			$vec[$i]['cod_sub_espec']=$vec_recurso[$i]['cod_sub_espec'];
			$vec[$i]['cod_auxiliar']=$vec_recurso[$i]['cod_auxiliar'];
			$vec[$i]['asig_anual']=$vec_recurso[$i]['asig_anual'];//agregue esto
			$dispo=sprintf("%01.2f",$vec[$i]['disponibilidad']);
			if($dispo<=0 || $vec_recurso[$i]['monto']<=0){
				$vec[$i]['monto']=$vec_recurso[$i]['monto'];
				$variable=$vec_recurso[$i]['monto'];
				$bueno="nada";
				$this->set('no','');
			}else if($var<=$dispo){
				$vec[$i]['monto']=$var;
				$bueno="si";
				$this->set('si','');
				echo "<script>";
   					echo "document.getElementById('input_tag".$i."').readOnly=true;";
   				echo "</script>";
			}else{
				//$vec[$i]['monto']=0;
				//$bueno="no";
				$vec[$i]['monto']=$vec_recurso[$i]['monto'];
				$variable=$vec_recurso[$i]['monto'];
				$bueno="no";
				$this->set('no','');
			}
		    $vec[$i]['id']=$i;
		}else{
			$vec[$j]=$vec_recurso[$j];
		}
	}//fin for

	//print_r($vec);
	$_SESSION["recursos"]=$_SESSION["recursos"]+$vec;

	if($bueno=="no"){
		$this->set('errorMessage', 'El monto que ingreso es mayor a la disponibilidad');
			echo "<script>";
   			echo "document.getElementById('input_tag".$i."').value='".$this->Formato2($variable)."';";
   		echo "</script>";
	}else if($bueno=="nada"){
		$this->set('errorMessage', 'esta partida posee saldos negativos');
			echo "<script>";
   			echo "document.getElementById('input_tag".$i."').value='".$this->Formato2($variable)."';";
   		echo "</script>";
	}

}//fin else if clasificada 2
			$vec_recurso=$_SESSION["recursos"];
			for($j=0;$j<count($vec_recurso);$j++){
				$sum_monto+=$vec_recurso[$j]['monto'];
				/*if($vec_recurso[$j]['monto']>0){
					$sum_monto+=$vec_recurso[$j]['monto'];
				}*/
			}
		echo "<script>";
			echo "document.getElementById('cambio_monto').innerHTML='".$this->Formato2($sum_monto)."';";
   		echo "</script>";
}//fin clasificada 1
// monto_solicitar



function modificar_monto($tipo=null,$i=null,$monto=null){
	$this->layout="ajax";//echo $id;
	//echo $monto;
	$this->set('monto',$monto);
	$this->set('i',$i);
	if($tipo=="cancelar"){
		$this->set('cancelar','');
		echo "<script>";
	   			echo "document.getElementById('input_tag".$i."').readOnly=false;";
	   			/*echo "n=eval(document.getElementById('input_tag".$i."').value);";
	   			echo "m=eval(document.getElementById('oculto').value);";
	   			echo "total=m-n;";*/
	   		echo "</script>";
	}else{
		$this->set('modificar','');
		echo "<script>";
				//echo "document.getElementById('input_tag".$i."').value='';";*/
				echo "document.getElementById('input_tag".$i."').value=".$monto.";";
	   			echo "document.getElementById('input_tag".$i."').readOnly=true;";

	   	echo "</script>";
	}//fin cancelar y modificar
	$this->set('tipo',$tipo);



}//fin modificar_monto


function grilla($var=null){
	$this->layout="ajax";
//print_r($_SESSION["recursos"]);
//count($_SESSION ["recursos"]);
	echo "<script>";
   			echo "document.getElementById('grilla').innerHTML='';";
   		echo "</script>";

	$cond= $this->SQLCA();
	 $fecha=$this->data["csrp01_solicitud_recurso2"]["fecha_1"];
     $frecuencia=$this->data["csrp01_solicitud_recurso2"]["frecuencia"];
	 $clasificada=$this->data["csrp01_solicitud_recurso2"]["clasificadas"];
	 $this->Session->delete('frecuen');
	$this->Session->write('frecuen',$frecuencia);
	 $this->set('frecuen',$frecuencia);

	$paso= explode('/',$fecha);
	//print_r($paso);
	 $mes=$paso[1];
	 $ano=$paso[2];
	 $this->Session->delete('mes');
	$this->Session->write('mes',$mes);
	 $this->Session->delete('clasificada');
	$this->Session->write('clasificada',$clasificada);

}// grilla


function guardar(){
	$this->layout="ajax";
//	pr($this->data);

//	  $ano_1=$this->data["csrp01_solicitud_recurso2"]["ano"];
$ano_1=$this->ano_ejecucion();
	  $numero=$this->data["csrp01_solicitud_recurso2"]["numero"];
	  $frecuencia=$this->data["csrp01_solicitud_recurso2"]["frecuencia"];
	  $fecha=$this->data["csrp01_solicitud_recurso2"]["fecha_1"];
	 $concepto =$this->data["csrp01_solicitud_recurso2"]["concepto"];
	 $clasificada =$this->data["csrp01_solicitud_recurso2"]["clasificadas"];
	 $recurso =$this->data["csrp01_solicitud_recurso2"]["tipo_recurso1"];
	 $mes_solicitud =$this->data["csrp01_solicitud_recurso2"]["mes_solicitud"];

	 if($frecuencia==1){
	 	$quincena =$this->data["csrp01_solicitud_recurso2"]["quincena"];
	 }else{
	 	$quincena=0;
	 }

	 $cod1=$this->verifica_SS(1);
	 $cod2=$this->verifica_SS(2);
	 $cod3=$this->verifica_SS(3);
	 $cod4=$this->verifica_SS(4);
	 $cod5=$this->verifica_SS(5);
	 $cond= $this->SQLCA();
	 $paso= explode('/',$fecha);
	 $mes=$paso[1];
	 $ano=$paso[2];
	 $sw=0;

$montoTOTAL=0;




if((isset($_SESSION ["recursos"]) && $_SESSION ["recursos"]!=null) && ($clasificada==1 || $clasificada==2)){
$TOTAL1=0;
$TOTAL2=0;

foreach($_SESSION ["recursos"] as $codigos){
	if($codigos!=null){
		if($codigos['monto']>0){
			$TOTAL1+=$codigos['monto'];
		}
		$TOTAL2+=$codigos['disponibilidad'];
	}
}
//echo"solicitado= ".$TOTAL1." disponible= ".$TOTAL2;
if(sprintf("%01.2f",$TOTAL1)<=sprintf("%01.2f",$TOTAL2)&& sprintf("%01.2f",$TOTAL1)>0){

	$sql = "BEGIN; INSERT INTO csrd01_solicitud_recurso_cuerpo VALUES('$cod1', '$cod2', '$cod3', '$cod4', '$cod5', '$ano_1', '$numero', '$fecha', '$montoTOTAL', '0', '0', '0','', '0', '1900-01-01','".$concepto."','$frecuencia','$recurso','$clasificada','$mes_solicitud','$quincena')";
	$sw1 = $this->csrd01_solicitud_recurso_cuerpo->execute($sql);
	if($sw1 > 1){
	$this->csrd01_solicitud_recurso_numero->execute("UPDATE  csrd01_solicitud_recurso_numero SET situacion=3 WHERE ".$this->SQLCA()." and numero_solicitud=".$numero." and ano_solicitud=".$ano_1." and situacion=2");
		if ($clasificada==1){
			 if($_SESSION ["recursos"]!=null){
			 //print_r($_SESSION ["recursos"]);
			 	count($_SESSION ["recursos"]);
			 	$guardar[]=0;
			 	$codigos[]=0;
			 	$i=0;
			 	$total=0;
			 	$montoTOTAL=0;
			    foreach($_SESSION ["recursos"] as $codigos){
			    	//echo $i."<br>";
			    	//print_r($codigos);
			    	if($codigos!=null){
			    		$guardar[0]=$codigos['ano'];
			    		$guardar[1]=$codigos['cod_sector'];
			    		$guardar[2]=$codigos['cod_prog'];
			    		$guardar[3]=$codigos['cod_sub_prog'];
			    		$guardar[4]=$codigos['cod_proy'];
			    		$guardar[5]=$codigos['cod_activ_obra'];
			    		$guardar[6]=$codigos['cod_partida'];
			    		$guardar[7]=$codigos['monto'];
						if($guardar[7]>0){
							$montoTOTAL+=$codigos['monto'];
							$sql_insert = "INSERT INTO csrd01_solicitud_recurso_partidas VALUES('$cod1', '$cod2', '$cod3', '$cod4', '$cod5', '$ano_1', '$numero', '$guardar[0]', '$guardar[1]', '$guardar[2]', '$guardar[3]', '$guardar[4]', '$guardar[5]', '$guardar[6]','0','0','0','0', '$guardar[7]','$guardar[7]')";
							$sw = $this->csrd01_solicitud_recurso_partidas->execute($sql_insert);
						}
					}else{
					}
	   			$i++;

	   		 	}
	   		 	if($sw > 1){
					$this->csrd01_solicitud_recurso_cuerpo->execute("update csrd01_solicitud_recurso_cuerpo set monto_solicitado=".$montoTOTAL." where ".$cond." and numero_solicitud=".$numero." and ano_solicitud=".$ano_1);///modifica el sueldo
					//$this->set('Message_existe', 'Registro exitoso');
					$MSJ=array("msj"=>"Registro de Solicitud de recursos Guardada con exito","tipo_msj"=>"exito");
					$this->Session->write("MSJ",$MSJ);
					$sw1 = $this->csrd01_solicitud_recurso_cuerpo->execute("COMMIT");
				}else{
					$this->csrd01_solicitud_recurso_partidas->execute("ROLLBACK");
					//$this->set('errorMessage', 'POR FAVOR INTENTE REGISTRAR NUEVAMENTE');
					$MSJ=array("msj"=>"POR FAVOR INTENTE REGISTRAR NUEVAMENTE","tipo_msj"=>"error");
					$this->Session->write("MSJ",$MSJ);
				}
	   		 		//$montoTOTAL=$montoTOTAL;//fin foreach
	   		 	//aqui el update
	  		}
		}else if($clasificada==2){
			if($_SESSION ["recursos"]!=null){
			 	//print_r($_SESSION ["recursos"]);
			 	count($_SESSION ["recursos"]);
			 	$guardar[]=0;
			 	$codigos[]=0;
			 	$i=0;
			 	$total=0;
			 	$montoTOTAL=0;
			    foreach($_SESSION ["recursos"] as $codigos){
			    	//print_r($codigos);
			    	if($codigos!=null){
			    		$guardar[0]=$codigos['ano'];
			    		$guardar[1]=$codigos['cod_sector'];
			    		$guardar[2]=$codigos['cod_prog'];
			    		$guardar[3]=$codigos['cod_sub_prog'];
			    		$guardar[4]=$codigos['cod_proy'];
			    		$guardar[5]=$codigos['cod_activ_obra'];
			    		$guardar[6]=$codigos['cod_partida'];
			    		$guardar[7]=$codigos['cod_generica'];
			    		$guardar[8]=$codigos['cod_especifica'];
			    		$guardar[9]=$codigos['cod_sub_espec'];
			    		$guardar[10]=$codigos['cod_auxiliar'];
			    		$guardar[11]=$codigos['monto'];
						//echo $guardar[11];
						if($guardar[11]>0){
							$montoTOTAL+=$codigos['monto'];
							$sql_insert = "INSERT INTO csrd01_solicitud_recurso_partidas VALUES('$cod1', '$cod2', '$cod3', '$cod4', '$cod5', '$ano_1', '$numero', '$guardar[0]', '$guardar[1]', '$guardar[2]', '$guardar[3]', '$guardar[4]', '$guardar[5]', '$guardar[6]','$guardar[7]','$guardar[8]','$guardar[9]','$guardar[10]', '$guardar[11]','$guardar[11]')";
							$sw = $this->csrd01_solicitud_recurso_partidas->execute($sql_insert);
						}
					}else{
					}
	   			$i++;

	   		 	}
	   		 	if($sw > 1){
					$this->csrd01_solicitud_recurso_cuerpo->execute("update csrd01_solicitud_recurso_cuerpo set monto_solicitado=".$montoTOTAL." where ".$cond." and numero_solicitud=".$numero." and ano_solicitud=".$ano_1);///modifica el sueldo
					//$this->set('Message_existe', 'Registro exitoso');
					$MSJ=array("msj"=>"Registro de Solicitud de recursos Guardada con exito","tipo_msj"=>"exito");
					$this->Session->write("MSJ",$MSJ);
					$sw1 = $this->csrd01_solicitud_recurso_cuerpo->execute("COMMIT");
				}else{
					$this->csrd01_solicitud_recurso_partidas->execute("ROLLBACK");
					//$this->set('errorMessage', 'POR FAVOR INTENTE REGISTRAR NUEVAMENTE');
					$MSJ=array("msj"=>"POR FAVOR INTENTE REGISTRAR NUEVAMENTE","tipo_msj"=>"error");
					$this->Session->write("MSJ",$MSJ);
				}
	   		 		//$montoTOTAL=$montoTOTAL;//fin foreach
	   		 	//aqui el update
	  		}

		}//FIN CLASIFICADA 2

	  }//FIN SW1 DEL CUERPO
}else{
	$MSJ=array("msj"=>" MONTO SOLICITADO MAYOR A SU DISPONIBILIDAD A LA FECHA","tipo_msj"=>"error");
	$this->Session->write("MSJ",$MSJ);
}
}else if($clasificada==3 && (isset($_SESSION ["items"]) && $_SESSION ["items"]!=null)){
	$sql = "BEGIN; INSERT INTO csrd01_solicitud_recurso_cuerpo VALUES('$cod1', '$cod2', '$cod3', '$cod4', '$cod5', '$ano_1', '$numero', '$fecha', '$montoTOTAL', '0', '0', '0','', '0', '1900-01-01','".$concepto."','$frecuencia','$recurso','$clasificada','$mes_solicitud','$quincena')";
	$sw1 = $this->csrd01_solicitud_recurso_cuerpo->execute($sql);
	if($sw1 > 1){
		$this->csrd01_solicitud_recurso_numero->execute("UPDATE  csrd01_solicitud_recurso_numero SET situacion=3 WHERE ".$this->SQLCA()." and numero_solicitud=".$numero." and ano_solicitud=".$ano_1." and situacion=2");
		 	//print_r($_SESSION ["items"]);
		 	$guardar[]=0;
		 	$i=0;
		 	$total=0;
		 	$monto_total=0;
		 	//print_r($_SESSION["items"]);
		    foreach($_SESSION ["items"] as $codigos){
		    	//print_r($codigos);
		    	if($codigos[0]!=null){
				   for($x=0;$x<=11;$x++){
				   	if($x==11){
				   		$guardar[$x]=$this->Formato1($codigos[$x]);
				   		$montoTOTAL=$montoTOTAL+$guardar[$x];
				   		 //$guardar[$x]=$codigos[$x];
					}else{
						 $guardar[$x]=$codigos[$x];
					}

					}
					//aqui el insert
					if($guardar[11]>0){

					$sql_insert = "INSERT INTO csrd01_solicitud_recurso_partidas VALUES('$cod1', '$cod2', '$cod3', '$cod4', '$cod5', '$ano_1', '$numero', '$guardar[0]', '$guardar[1]', '$guardar[2]', '$guardar[3]', '$guardar[4]', '$guardar[5]', '$guardar[6]','$guardar[7]','$guardar[8]','$guardar[9]','$guardar[10]', '$guardar[11]','$guardar[11]')";
					$sw = $this->csrd01_solicitud_recurso_partidas->execute($sql_insert);
					}
				}
   			$i++;

   		 	}
   		 	if($sw > 1){
				$this->csrd01_solicitud_recurso_cuerpo->execute("update csrd01_solicitud_recurso_cuerpo set monto_solicitado=".$montoTOTAL." where ".$cond." and numero_solicitud=".$numero." and ano_solicitud=".$ano_1);///modifica el sueldo
				//$this->set('Message_existe', 'Registro exitoso');
				$MSJ=array("msj"=>"Registro de Solicitud de recursos Guardada con exito","tipo_msj"=>"exito");
				$this->Session->write("MSJ",$MSJ);
				$sw1 = $this->csrd01_solicitud_recurso_cuerpo->execute("COMMIT");
			}else{
				$this->csrd01_solicitud_recurso_partidas->execute("ROLLBACK");
				//$this->set('errorMessage', 'POR FAVOR INTENTE REGISTRAR NUEVAMENTE');
				$MSJ=array("msj"=>"POR FAVOR INTENTE REGISTRAR NUEVAMENTE","tipo_msj"=>"error");
				$this->Session->write("MSJ",$MSJ);
			}

		 }//fin if sw1 del cuerpo
 }else{
	$sw1 = $this->csrd01_solicitud_recurso_cuerpo->execute("ROLLBACK");
	$MSJ=array("msj"=>"POR FAVOR INTENTE REGISTRAR NUEVAMENTE, VERIFIQUE LOS DATOS INGRESADOS","tipo_msj"=>"error");
	$this->Session->write("MSJ",$MSJ);
}

$this->index();
$this->render('index');


}//fin guardar

/*

function consulta($var=null){
	 $this->layout="ajax";
	 $cond= $this->SQLCA();
	// echo $var;
	 $aux=$var;
	 $ver = $this->csrd01_solicitud_recurso_cuerpo->findAll($cond);
	 if($ver){
		 $condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').'';
		 $year = $this->ccfd03_instalacion->findAll($condicion, null, 'ano_arranque ASC', null);
		 $ano = null;

		 foreach($year as $year){$ano = $year['ccfd03_instalacion']['ano_arranque'];}
		 $this->set('ano',$ano);//el año

		 $v=$this->csrd01_solicitud_recurso_partidas->execute("SELECT cod_presi,cod_entidad,cod_tipo_inst,cod_inst,cod_dep,numero_solicitud FROM csrd01_solicitud_recurso_partidas WHERE ".$cond." group by cod_presi,cod_entidad,cod_tipo_inst,cod_inst,cod_dep,numero_solicitud ORDER BY numero_solicitud ASC");
		//print_r($v);
		// count($v);
		 if($var<count($v)){
		 		$n=count($v)-1;
		 		if($var==$n){
		 			//echo "aqui";
		 			echo "<script>";
		 				echo "document.getElementById('consulta_ide').disabled='disabled';";
		 			echo "</script>";
		 		}
		 		if($var==0){
		 			echo "<script>";
		 				echo "document.getElementById('consulta2_ide').disabled='disabled';";
		 			echo "</script>";
		 		}else{
		 			echo "<script>";
		 				echo "document.getElementById('consulta2_ide').disabled=false;";
		 			echo "</script>";
		 		}
				if($v!=null){
					  $numero=$v[$var][0]["numero_solicitud"];
				}
				$this->set('numero',$numero);
				$datos = $this->csrd01_solicitud_recurso_partidas->findAll($cond."and numero_solicitud=".$numero);
				$x = $this->csrd01_solicitud_recurso_cuerpo->findAll($cond."and numero_solicitud=".$numero);

				//pr($x);
				$this->set('datos',$datos);
				$frecuen= $x[0]["csrd01_solicitud_recurso_cuerpo"]["frecuencia_solicitud"];
				$concep= $x[0]["csrd01_solicitud_recurso_cuerpo"]["concepto"];
				$fecha= $x[0]["csrd01_solicitud_recurso_cuerpo"]["fecha_solicitud"];
				$forma_solicitud= $x[0]["csrd01_solicitud_recurso_cuerpo"]["forma_solicitud"];
				$mes_solicitud= $x[0]["csrd01_solicitud_recurso_cuerpo"]["mes_solicitado"];
				$numero_quincena= $x[0]["csrd01_solicitud_recurso_cuerpo"]["numero_quincena"];
				$this->set('tipo_recurso',$x[0]["csrd01_solicitud_recurso_cuerpo"]["tipo_solicitud_recurso"]);//el concepto
				$deno_tipo = $this->csrd01_tipo_solicitud->field('denominacion'," cod_tipo_solicitud=".$x[0]["csrd01_solicitud_recurso_cuerpo"]["tipo_solicitud_recurso"], $order ="cod_tipo_solicitud ASC");
				$this->set('clasificada', $forma_solicitud);
				$this->set('mes_solicitud', $mes_solicitud);
				$this->set('numero_quincena', $numero_quincena);
				$this->set('deno_tipo', $deno_tipo);
				$this->set('concepto',$concep);//el concepto
				$this->set('fre',$frecuen);//la frecuencia mensual o quincenal
				$this->set('fecha',$fecha);//la fecha para la cual se registro
				$this->set('dependencia',$_SESSION["dependencia"]);////La dependencia
				$var++;
				--$aux;
				$this->set('i',$var);
				$this->set('aux',$aux);

		 }else{
		 	echo "<script>";
		 		echo "document.getElementById('consulta_ide').disabled='disabled';";
		 	echo "</script>";
		 }

		 /////////////////////////////PARA MONTAR LOS DATOS DE LA PARTE DE ABAJO//////////////////////////////////


		 $x=$this->csrd01_solicitud_recurso_cuerpo->execute("SELECT cod_entidad_bancaria, cod_sucursal, numero_cheque,cuenta_bancaria,fecha_cheque,monto_entregado FROM csrd01_solicitud_recurso_cuerpo WHERE ".$cond." and numero_solicitud=".$numero);
		if($x!=null){
			$cheque=$x[0][0]["numero_cheque"];
			if($cheque!=0){
				//echo "hoooola";
				 $entidad=$x[0][0]["cod_entidad_bancaria"];
				 $sucursal=$x[0][0]["cod_sucursal"];
				 $fecha_cheque=$x[0][0]["fecha_cheque"];
				 $cuenta=$x[0][0]["cuenta_bancaria"];
				  $monto2=$x[0][0]["monto_entregado"];
				$e=$this->cstd01_entidades_bancarias->execute("SELECT * FROM cstd01_entidades_bancarias WHERE cod_entidad_bancaria=".$entidad);
				if($e!=null){
					$deno_entidad=$e[0][0]["denominacion"];
				}else{//echo "aqui";
					$deno_entidad="";
				}
				$d=$this->cstd01_sucursales_bancarias->execute("SELECT * FROM cstd01_sucursales_bancarias WHERE cod_entidad_bancaria=".$entidad." and cod_sucursal=".$sucursal);
				if($d!=null){
					$deno_sucursal=$d[0][0]["denominacion"];
				}else{
					$deno_sucursal="";
				}


				$this->set("cheque",$cheque);
				$this->set("deno_entidad",$deno_entidad);
				$this->set("deno_sucursal",$deno_sucursal);
				$this->set("cuenta",$cuenta);
				$this->set("fecha_cheque",$fecha_cheque);
				$this->set("monto2",$monto2);
			}else{
				//echo "cheque vacio";
				$this->set("cheque","");
				$this->set("deno_entidad","");
				$this->set("deno_sucursal","");
				$this->set("cuenta","");
				$this->set("fecha_cheque","");
				$this->set("monto2","");
			}
		}else{
				$this->set("cheque","");
				$this->set("deno_entidad","");
				$this->set("deno_sucursal","");
				$this->set("cuenta","");
				$this->set("fecha_cheque","");
				$this->set("monto2","");
			//echo "hooooooola2222";
			$cheque="";
		}
	}else{
		$this->index();
		$this->render('index');
	}

}//fin consultar

*/


 function consulta($pagina=null) {
	$this->layout="ajax";
	$this->set('entidad_federal', $this->Session->read('entidad_federal'));
	//$this->set('enable2', 'enabled');
 	//$this->set('enable', 'disabled');
 	//$this->set('read', 'readonly');

	$consulta1=$this->SQLCA();
	/* $condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').'';
		 $year = $this->ccfd03_instalacion->findAll($condicion, null, 'ano_arranque ASC', null);
		 $ano = null;

		 foreach($year as $year){$ano = $year['ccfd03_instalacion']['ano_arranque'];}*/
		 $ano=$this->ano_ejecucion();
		 $this->set('ano',$ano);//el año

	if(isset($pagina)){
		$Tfilas=$this->csrd01_solicitud_recurso_cuerpo->findCount($this->SQLCA()." and ano_solicitud=".$ano);
        if($Tfilas!=0){
        	$x=$this->csrd01_solicitud_recurso_cuerpo->findAll($this->SQLCA()." and ano_solicitud=".$ano,null,"numero_solicitud ASC",1,$pagina,null);

            $this->set('DATA',$x);
          	$this->set('siguiente',$pagina+1);
          	$this->set('anterior',$pagina-1);
            $this->bt_nav($Tfilas,$pagina);
            $this->set('numT',$Tfilas);
			$this->set('numP',$pagina);
        }else{
	 	       $this->set('errorMessage', 'No se encontrar&oacute;n datos');
	 	       $this->set('noExiste',true);
	 	       $this->index();
			   $this->render("index");
			   return;
        }

	}else{
		$pagina=1;
		$Tfilas=$this->csrd01_solicitud_recurso_cuerpo->findCount($this->SQLCA()." and ano_solicitud=".$ano);

        if($Tfilas!=0){
        	$x=$this->csrd01_solicitud_recurso_cuerpo->findAll($this->SQLCA()." and ano_solicitud=".$ano,null,"numero_solicitud ASC",1,$pagina,null);
			$this->set('DATA',$x);
          	$this->set('siguiente',$pagina+1);
          	$this->set('anterior',$pagina-1);
          	$this->bt_nav($Tfilas,$pagina);
          	$this->set('numT',$Tfilas);
			$this->set('numP',$pagina);

        }else{
	 	       $this->set('errorMessage', 'No se encontrar&oacute;n datos');
	 	       $this->set('noExiste',true);
	 	       $this->index();
			   $this->render("index");
			   return;
        }
	}

				$numero= $x[0]["csrd01_solicitud_recurso_cuerpo"]["numero_solicitud"];
				$frecuen= $x[0]["csrd01_solicitud_recurso_cuerpo"]["frecuencia_solicitud"];
				$concep= $x[0]["csrd01_solicitud_recurso_cuerpo"]["concepto"];
				$fecha= $x[0]["csrd01_solicitud_recurso_cuerpo"]["fecha_solicitud"];
				$forma_solicitud= $x[0]["csrd01_solicitud_recurso_cuerpo"]["forma_solicitud"];
				$mes_solicitud= $x[0]["csrd01_solicitud_recurso_cuerpo"]["mes_solicitado"];
				$numero_quincena= $x[0]["csrd01_solicitud_recurso_cuerpo"]["numero_quincena"];
				$this->set('tipo_recurso',$x[0]["csrd01_solicitud_recurso_cuerpo"]["tipo_solicitud_recurso"]);//el concepto
				$deno_tipo = $this->csrd01_tipo_solicitud->field('denominacion'," cod_tipo_solicitud=".$x[0]["csrd01_solicitud_recurso_cuerpo"]["tipo_solicitud_recurso"], $order ="cod_tipo_solicitud ASC");
				$this->set('clasificada', $forma_solicitud);
				$this->set('numero', $numero);
				$this->set('mes_solicitud', $mes_solicitud);
				$this->set('numero_quincena', $numero_quincena);
				$this->set('deno_tipo', $deno_tipo);
				$this->set('concepto',$concep);//el concepto
				$this->set('fre',$frecuen);//la frecuencia mensual o quincenal
				$this->set('fecha',$fecha);//la fecha para la cual se registro
				$this->set('dependencia',$_SESSION["dependencia"]);////La dependencia
				$datos = $this->csrd01_solicitud_recurso_partidas->findAll($this->SQLCA()." and ano_solicitud=".$ano."and numero_solicitud=".$numero);
				$this->set('datos',$datos);


				 /////////////////////////////PARA MONTAR LOS DATOS DE LA PARTE DE ABAJO//////////////////////////////////


		 $x=$this->csrd01_solicitud_recurso_cuerpo->execute("SELECT cod_entidad_bancaria, cod_sucursal, numero_cheque,cuenta_bancaria,fecha_cheque,monto_entregado FROM csrd01_solicitud_recurso_cuerpo WHERE ".$this->SQLCA()." and ano_solicitud=".$ano." and numero_solicitud=".$numero);
		if($x!=null){
			$cheque=$x[0][0]["numero_cheque"];
			if($cheque!=0){
				//echo "hoooola";
				 $entidad=$x[0][0]["cod_entidad_bancaria"];
				 $sucursal=$x[0][0]["cod_sucursal"];
				 $fecha_cheque=$x[0][0]["fecha_cheque"];
				 $cuenta=$x[0][0]["cuenta_bancaria"];
				  $monto2=$x[0][0]["monto_entregado"];
				$e=$this->cstd01_entidades_bancarias->execute("SELECT * FROM cstd01_entidades_bancarias WHERE cod_entidad_bancaria=".$entidad);
				if($e!=null){
					$deno_entidad=$e[0][0]["denominacion"];
				}else{//echo "aqui";
					$deno_entidad="";
				}
				$d=$this->cstd01_sucursales_bancarias->execute("SELECT * FROM cstd01_sucursales_bancarias WHERE cod_entidad_bancaria=".$entidad." and cod_sucursal=".$sucursal);
				if($d!=null){
					$deno_sucursal=$d[0][0]["denominacion"];
				}else{
					$deno_sucursal="";
				}


				$this->set("cheque",$cheque);
				$this->set("deno_entidad",$deno_entidad);
				$this->set("deno_sucursal",$deno_sucursal);
				$this->set("cuenta",$cuenta);
				$this->set("fecha_cheque",$fecha_cheque);
				$this->set("monto2",$monto2);
			}else{
				//echo "cheque vacio";
				$this->set("cheque","");
				$this->set("deno_entidad","");
				$this->set("deno_sucursal","");
				$this->set("cuenta","");
				$this->set("fecha_cheque","");
				$this->set("monto2","");
			}
		}else{
				$this->set("cheque","");
				$this->set("deno_entidad","");
				$this->set("deno_sucursal","");
				$this->set("cuenta","");
				$this->set("fecha_cheque","");
				$this->set("monto2","");
			//echo "hooooooola2222";
			$cheque="";
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





function m2($var=null){
	switch (strlen($var)){
		case 1:
			return '0'.$var;
		break;
		/*case 2:
			return $var;
		break;
		case 3:
			return $var;
		break;
		case 4:
			return $var;
		break;*/
		default:
		return $var;
	}
}// fin m4



function eliminar_busqueda($var=null){
	$this->layout="ajax";
	//echo "eliminar";
	//echo $var;
	$ano=$this->ano_ejecucion();
	 $cond= $this->SQLCA()." and ano_solicitud=".$ano;
	 //$cond.=" and numero_solicitud=".$var;

	 $datos=$this->csrd01_solicitud_recurso_cuerpo->findAll($cond,null,null);
		if(!$datos){
			//echo "no consulto";
			$this->set('errorMessage', 'Registro no existen datos que eliminar');

		}else{
			$verifica=$this->csrd01_solicitud_recurso_cuerpo->findAll($cond." and numero_solicitud=".$var." and monto_entregado=0",null,null);
				if($verifica){
						$a=$this->csrd01_solicitud_recurso_cuerpo->execute("delete from csrd01_solicitud_recurso_cuerpo where ".$cond." and numero_solicitud=".$var);
					if($a>1){
						$this->csrd01_solicitud_recurso_cuerpo->execute("delete from csrd01_solicitud_recurso_partidas where ".$cond." and numero_solicitud=".$var);
						$this->csrd01_solicitud_recurso_numero->execute("UPDATE  csrd01_solicitud_recurso_numero SET situacion=4 WHERE ".$this->SQLCA()." and numero_solicitud=".$var." and ano_solicitud=".$ano." and situacion=3");//pendiente en la condicion el año "mosca"
						$this->set('Message_existe', 'solicitud numero '.$this->m2($var).' eliminada con exito');
					}
				}else{
					$this->set('errorMessage', 'la solicitud numero '.$this->m2($var).' ya fue entregada, no podra ser eliminada');
				}


		}

				$this->buscar();
				$this->render("buscar");



}//fin eliminar



function eliminar($var=null,$anterior=null){
	$this->layout="ajax";
	//echo "eliminar";
	//echo $var;
	$ano=$this->ano_ejecucion();
	 $cond= $this->SQLCA()." and ano_solicitud=".$ano;
	 //$cond.=" and numero_solicitud=".$var;

	 $datos=$this->csrd01_solicitud_recurso_cuerpo->findAll($cond,null,null);
		if(!$datos){
			//echo "no consulto";
			$this->set('errorMessage', 'no existen datos que eliminar');

		}else{
			$a=$this->csrd01_solicitud_recurso_cuerpo->execute("delete from csrd01_solicitud_recurso_cuerpo where ".$cond." and numero_solicitud=".$var);
			if($a>1){
				$this->csrd01_solicitud_recurso_cuerpo->execute("delete from csrd01_solicitud_recurso_partidas where ".$cond." and numero_solicitud=".$var);
				$this->csrd01_solicitud_recurso_numero->execute("UPDATE  csrd01_solicitud_recurso_numero SET situacion=4 WHERE ".$this->SQLCA()." and numero_solicitud=".$var." and ano_solicitud=".$ano." and situacion=3");//pendiente en la condicion el año "mosca"
				$this->set('Message_existe', 'solicitud numero '.$this->m2($var).' eliminada con exito');
			}
		}
		 $Tfilas=$this->csrd01_solicitud_recurso_cuerpo->findCount($cond);
				if($Tfilas!=0){
				$this->consulta($anterior);
				$this->render("consulta");
				}else{
					$this->index();
					$this->render("index");
				}/////HASTA AQUI AGREGO ERICK
		//$this->consulta(0);
		//$this->render('consulta');


}//fin eliminar




function modificar($var=null,$anterior=null){
	$this->layout="ajax";
$ano=$this->ano_ejecucion();
	$cond= $this->SQLCA()." and ano_solicitud=".$ano;
	 $aux=$var;
	$this->set('Message_existe', 'Proceda a Modificar');
	 $condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').'';
	/* $year = $this->ccfd03_instalacion->findAll($condicion, null, 'ano_arranque ASC', null);
	 $ano = null;

	 foreach($year as $year){$ano = $year['ccfd03_instalacion']['ano_arranque'];}
	 //el año*/

$this->set('ano',$ano);

$lista = $this->csrd01_tipo_solicitud->generateList(null, $order = 'cod_tipo_solicitud', $limit = null, '{n}.csrd01_tipo_solicitud.cod_tipo_solicitud', '{n}.csrd01_tipo_solicitud.denominacion');
$this->concatena($lista, 'tipo');


	 $datos = $this->csrd01_solicitud_recurso_partidas->findAll($cond." and numero_solicitud=".$var);

$this->set('anterior',$anterior+1);
			if($datos!=null){
				$this->set('numero',$var);
				$this->set('datos',$datos);
				$x = $this->csrd01_solicitud_recurso_cuerpo->findAll($cond." and numero_solicitud=".$var);
				$this->set('concepto',$x[0]["csrd01_solicitud_recurso_cuerpo"]["concepto"]);//el concepto
				$this->set('fre',$x[0]["csrd01_solicitud_recurso_cuerpo"]["frecuencia_solicitud"]);//la frecuencia mensual o quincenal
				$this->set('fecha',$x[0]["csrd01_solicitud_recurso_cuerpo"]["fecha_solicitud"]);//la fecha para la cual se registro
				$this->set('dependencia',$_SESSION["dependencia"]);////La dependencia
				$this->set('tipo_recurso',$x[0]["csrd01_solicitud_recurso_cuerpo"]["tipo_solicitud_recurso"]);//el concepto
				$deno_tipo = $this->csrd01_tipo_solicitud->field('denominacion'," cod_tipo_solicitud=".$x[0]["csrd01_solicitud_recurso_cuerpo"]["tipo_solicitud_recurso"], $order ="cod_tipo_solicitud ASC");
				$this->set('deno_tipo', $deno_tipo);

				$forma_solicitud= $x[0]["csrd01_solicitud_recurso_cuerpo"]["forma_solicitud"];
				$mes_solicitud= $x[0]["csrd01_solicitud_recurso_cuerpo"]["mes_solicitado"];
				$numero_quincena= $x[0]["csrd01_solicitud_recurso_cuerpo"]["numero_quincena"];
				$this->set('clasificada', $forma_solicitud);
				$this->set('mes_solicitud', $mes_solicitud);
				$this->set('numero_quincena', $numero_quincena);
			}else{
				//$this->set('datos',$datos);redireccionar
				$this->consulta($anterior);
				$this->render('consulta');
			}
				/*$frecuen= $x[0]["csrd01_solicitud_recurso_cuerpo"]["frecuencia_solicitud"];
				$concep= $x[0]["csrd01_solicitud_recurso_cuerpo"]["concepto"];
				$fecha= $x[0]["csrd01_solicitud_recurso_cuerpo"]["fecha_solicitud"];*/


	 /////////////////////////////PARA MONTAR LOS DATOS DE LA PARTE DE ABAJO//////////////////////////////////


	 $x=$this->csrd01_solicitud_recurso_cuerpo->execute("SELECT cod_entidad_bancaria, cod_sucursal, numero_cheque,cuenta_bancaria,fecha_cheque,monto_entregado FROM csrd01_solicitud_recurso_cuerpo WHERE ".$this->SQLCA()." and ano_solicitud=".$ano." and numero_solicitud=".$var);
		if($x!=null){
			$cheque=$x[0][0]["numero_cheque"];
			if($cheque!=0){
				//echo "hoooola";
				 $entidad=$x[0][0]["cod_entidad_bancaria"];
				 $sucursal=$x[0][0]["cod_sucursal"];
				 $fecha_cheque=$x[0][0]["fecha_cheque"];
				 $cuenta=$x[0][0]["cuenta_bancaria"];
				  $monto2=$x[0][0]["monto_entregado"];
				$e=$this->cstd01_entidades_bancarias->execute("SELECT * FROM cstd01_entidades_bancarias WHERE cod_entidad_bancaria=".$entidad);
				if($e!=null){
					$deno_entidad=$e[0][0]["denominacion"];
				}else{//echo "aqui";
					$deno_entidad="";
				}
				$d=$this->cstd01_sucursales_bancarias->execute("SELECT * FROM cstd01_sucursales_bancarias WHERE cod_entidad_bancaria=".$entidad." and cod_sucursal=".$sucursal);
				if($d!=null){
					$deno_sucursal=$d[0][0]["denominacion"];
				}else{
					$deno_sucursal="";
				}


				$this->set("cheque",$cheque);
				$this->set("deno_entidad",$deno_entidad);
				$this->set("deno_sucursal",$deno_sucursal);
				$this->set("cuenta",$cuenta);
				$this->set("fecha_cheque",$fecha_cheque);
				$this->set("monto2",$monto2);
			}else{
				//echo "cheque vacio";
				$this->set("cheque","");
				$this->set("deno_entidad","");
				$this->set("deno_sucursal","");
				$this->set("cuenta","");
				$this->set("fecha_cheque","");
				$this->set("monto2","");
			}
		}else{
				$this->set("cheque","");
				$this->set("deno_entidad","");
				$this->set("deno_sucursal","");
				$this->set("cuenta","");
				$this->set("fecha_cheque","");
				$this->set("monto2","");
			//echo "hooooooola2222";
			$cheque="";
		}

}//FIN MODIFICAR






function guardar_modificar($anterior=null){
	$this->layout="ajax";
//	echo "guarda modifica";
	$ano=$this->ano_ejecucion();
	$cond= $this->SQLCA();
	  $numero=$this->data["csrp01_solicitud_recurso2"]["numero"];
	  $concepto =$this->data["csrp01_solicitud_recurso2"]["concepto"];
	  $recurso =$this->data["csrp01_solicitud_recurso2"]["tipo_recurso1"];
	 //$f=$this->csrd01_solicitud_recurso_cuerpo->execute("SELECT * FROM cstd02_cuentas_bancarias WHERE ".$cond." and cod_entidad_bancaria=".$entidad." and cod_sucursal=".$sucursal);
	 $ver=$this->csrd01_solicitud_recurso_cuerpo->execute("select numero_cheque from csrd01_solicitud_recurso_cuerpo where ".$this->SQLCA()." and ano_solicitud=".$ano." and numero_solicitud=".$numero);
	$cheque=$ver[0][0]["numero_cheque"];
	if($cheque==0){
		 $this->csrd01_solicitud_recurso_cuerpo->execute("update csrd01_solicitud_recurso_cuerpo set concepto='".$concepto."',tipo_solicitud_recurso=".$recurso." where ".$cond." and ano_solicitud=".$ano." and numero_solicitud=".$numero);
	}else{
		  $this->csrd01_solicitud_recurso_cuerpo->execute("update csrd01_solicitud_recurso_cuerpo set tipo_solicitud_recurso=".$recurso." where ".$cond." and ano_solicitud=".$ano." and numero_solicitud=".$numero);
	}

	$this->set('Message_existe', 'El Registro se Modifico con Exito');
	$this->consulta($anterior);
	$this->render('consulta');



}//FIN GUARDAR_MODIFICAR




function modificar_busqueda($var=null){
	$this->layout="ajax";
 $ano=$this->ano_ejecucion();
	$cond= $this->SQLCA()." and ano_solicitud=".$ano;
	 $aux=$var;
	$this->set('Message_existe', 'Proceda a Modificar');
	 /*$condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').'';
	 $year = $this->ccfd03_instalacion->findAll($condicion, null, 'ano_arranque ASC', null);
	 $ano = null;

	 foreach($year as $year){$ano = $year['ccfd03_instalacion']['ano_arranque'];}
	 ;//el año*/

	$this->set('ano',$ano);
	 $lista = $this->csrd01_tipo_solicitud->generateList(null, $order = 'cod_tipo_solicitud', $limit = null, '{n}.csrd01_tipo_solicitud.cod_tipo_solicitud', '{n}.csrd01_tipo_solicitud.denominacion');
	$this->concatena($lista, 'tipo');

	 $datos = $this->csrd01_solicitud_recurso_partidas->findAll($cond." and numero_solicitud=".$var);

				$this->set('numero',$var);
				$this->set('datos',$datos);
				$x = $this->csrd01_solicitud_recurso_cuerpo->findAll($cond." and numero_solicitud=".$var);
				$this->set('concepto',$x[0]["csrd01_solicitud_recurso_cuerpo"]["concepto"]);//el concepto
				$this->set('fre',$x[0]["csrd01_solicitud_recurso_cuerpo"]["frecuencia_solicitud"]);//la frecuencia mensual o quincenal
				$this->set('fecha',$x[0]["csrd01_solicitud_recurso_cuerpo"]["fecha_solicitud"]);//la fecha para la cual se registro
				$this->set('dependencia',$_SESSION["dependencia"]);////La dependencia
				$this->set('tipo_recurso',$x[0]["csrd01_solicitud_recurso_cuerpo"]["tipo_solicitud_recurso"]);//el concepto
				$deno_tipo = $this->csrd01_tipo_solicitud->field('denominacion'," cod_tipo_solicitud=".$x[0]["csrd01_solicitud_recurso_cuerpo"]["tipo_solicitud_recurso"], $order ="cod_tipo_solicitud ASC");
				$this->set('deno_tipo', $deno_tipo);

				$forma_solicitud= $x[0]["csrd01_solicitud_recurso_cuerpo"]["forma_solicitud"];
				$mes_solicitud= $x[0]["csrd01_solicitud_recurso_cuerpo"]["mes_solicitado"];
				$numero_quincena= $x[0]["csrd01_solicitud_recurso_cuerpo"]["numero_quincena"];
				$this->set('clasificada', $forma_solicitud);
				$this->set('mes_solicitud', $mes_solicitud);
				$this->set('numero_quincena', $numero_quincena);



	 /////////////////////////////PARA MONTAR LOS DATOS DE LA PARTE DE ABAJO//////////////////////////////////


	 $x=$this->csrd01_solicitud_recurso_cuerpo->execute("SELECT cod_entidad_bancaria, cod_sucursal, numero_cheque,cuenta_bancaria,fecha_cheque,monto_entregado FROM csrd01_solicitud_recurso_cuerpo WHERE ".$this->SQLCA()." and numero_solicitud=".$var);
		if($x!=null){
			$cheque=$x[0][0]["numero_cheque"];
			if($cheque!=0){
				//echo "hoooola";
				 $entidad=$x[0][0]["cod_entidad_bancaria"];
				 $sucursal=$x[0][0]["cod_sucursal"];
				 $fecha_cheque=$x[0][0]["fecha_cheque"];
				 $cuenta=$x[0][0]["cuenta_bancaria"];
				  $monto2=$x[0][0]["monto_entregado"];
				$e=$this->cstd01_entidades_bancarias->execute("SELECT * FROM cstd01_entidades_bancarias WHERE cod_entidad_bancaria=".$entidad);
				if($e!=null){
					$deno_entidad=$e[0][0]["denominacion"];
				}else{//echo "aqui";
					$deno_entidad="";
				}
				$d=$this->cstd01_sucursales_bancarias->execute("SELECT * FROM cstd01_sucursales_bancarias WHERE cod_entidad_bancaria=".$entidad." and cod_sucursal=".$sucursal);
				if($d!=null){
					$deno_sucursal=$d[0][0]["denominacion"];
				}else{
					$deno_sucursal="";
				}


				$this->set("cheque",$cheque);
				$this->set("deno_entidad",$deno_entidad);
				$this->set("deno_sucursal",$deno_sucursal);
				$this->set("cuenta",$cuenta);
				$this->set("fecha_cheque",$fecha_cheque);
				$this->set("monto2",$monto2);
			}else{
				//echo "cheque vacio";
				$this->set("cheque","");
				$this->set("deno_entidad","");
				$this->set("deno_sucursal","");
				$this->set("cuenta","");
				$this->set("fecha_cheque","");
				$this->set("monto2","");
			}
		}else{
				$this->set("cheque","");
				$this->set("deno_entidad","");
				$this->set("deno_sucursal","");
				$this->set("cuenta","");
				$this->set("fecha_cheque","");
				$this->set("monto2","");
			//echo "hooooooola2222";
			$cheque="";
		}

}//FIN MODIFICAR




function guardar_modificar_busqueda($numero=null){
	$this->layout="ajax";
	//echo "guarda modifica";
	$ano=$this->ano_ejecucion();
	$cond= $this->SQLCA();
	$concepto =$this->data["csrp01_solicitud_recurso2"]["concepto"];
	$recurso =$this->data["csrp01_solicitud_recurso2"]["tipo_recurso1"];
	$ver=$this->csrd01_solicitud_recurso_cuerpo->execute("select numero_cheque from csrd01_solicitud_recurso_cuerpo where ".$this->SQLCA()." and ano_solicitud=".$ano." and numero_solicitud=".$numero);
	$cheque=$ver[0][0]["numero_cheque"];
	if($cheque==0){
		 $this->csrd01_solicitud_recurso_cuerpo->execute("update csrd01_solicitud_recurso_cuerpo set concepto='".$concepto."',tipo_solicitud_recurso=".$recurso." where ".$cond." and ano_solicitud=".$ano." and numero_solicitud=".$numero);
	}else{
		  $this->csrd01_solicitud_recurso_cuerpo->execute("update csrd01_solicitud_recurso_cuerpo set tipo_solicitud_recurso=".$recurso." where ".$cond." and ano_solicitud=".$ano." and numero_solicitud=".$numero);
	}
	$this->set('Message_existe', 'El Registro se Modifico con Exito');
	$this->buscar();
	$this->render('buscar');



}//FIN GUARDAR_MODIFICAR

function select3($select=null,$var=null) { //select codigos presupuestarios
	$this->layout = "ajax";
	$de=$this->Session->read('SScoddep');
	$cond=$this->SQLCA();
	//echo $var;

if($var!=""){
	switch($select){
		case 'sector'://echo "1";
			$this->set('no','');
			$this->set('SELECT','programa');
			$this->set('codigo','sector');
			$this->set('seleccion','');
			$this->set('n',2);
			$this->Session->write('ano',$var);
			$cond .=" and ano=".$var;
			$lista=  $this->v_cfpd05_denominaciones->generateList($cond, 'cod_sector ASC', null, '{n}.v_cfpd05_denominaciones.cod_sector', '{n}.v_cfpd05_denominaciones.deno_sector');
			$this->concatena($lista, 'vector');
		break;
		case 'programa'://echo "1";
 			$this->set('no','');
			$this->set('SELECT','subprograma');
			$this->set('codigo','programa');
			$this->set('seleccion','');
			$this->set('n',3);
			$cod=$this->Session->read('ano');
			$this->Session->write('sec',$var);
			$cond .=" and ano=".$cod." and cod_sector=".$var;
			$lista=  $this->v_cfpd05_denominaciones->generateList($cond, 'cod_programa ASC', null, '{n}.v_cfpd05_denominaciones.cod_programa', '{n}.v_cfpd05_denominaciones.deno_programa');
			$this->concatena($lista, 'vector');
		break;
		case 'subprograma'://echo "1";
		    $this->set('no','');
			$this->set('SELECT','proyecto');
			$this->set('codigo','subprograma');
			$this->set('seleccion','');
			$this->set('n',4);
			$ano =  $this->Session->read('ano');
			$sec =  $this->Session->read('sec');
			$this->Session->write('prog',$var);
			$cond .=" and ano=".$ano." and cod_sector=".$sec." and cod_programa=".$var;
			$lista=  $this->v_cfpd05_denominaciones->generateList($cond, 'cod_sub_prog ASC', null, '{n}.v_cfpd05_denominaciones.cod_sub_prog', '{n}.v_cfpd05_denominaciones.deno_sub_prog');
			$this->concatena($lista, 'vector');
		break;
		case 'proyecto'://echo "1";
		    $this->set('no','');
			$this->set('SELECT','actividad');
			$this->set('codigo','proyecto');
			$this->set('seleccion','');
			$this->set('n',5);
			$ano =  $this->Session->read('ano');
			$sec =  $this->Session->read('sec');
			$prog =  $this->Session->read('prog');
			$this->Session->write('subp',$var);
			$cond .=" and ano=".$ano." and cod_sector=".$sec." and cod_programa=".$prog." and cod_sub_prog=".$var;
			$lista=  $this->v_cfpd05_denominaciones->generateList($cond, 'cod_proyecto ASC', null, '{n}.v_cfpd05_denominaciones.cod_proyecto', '{n}.v_cfpd05_denominaciones.deno_proyecto');
			$this->concatena($lista, 'vector');
		break;
		case 'actividad'://echo "1";
		    $this->set('no','');
			$this->set('SELECT','partida');
			$this->set('codigo','actividad');
			$this->set('seleccion','');
			$this->set('n',6);
			$ano =  $this->Session->read('ano');
			$sec =  $this->Session->read('sec');
			$prog =  $this->Session->read('prog');
			$subp =  $this->Session->read('subp');
			$this->Session->write('proy',$var);
			$cond .=" and ano=".$ano." and cod_sector=".$sec." and cod_programa=".$prog." and cod_sub_prog=".$subp." and cod_proyecto=".$var;
			$lista=  $this->v_cfpd05_denominaciones->generateList($cond, 'cod_activ_obra ASC', null, '{n}.v_cfpd05_denominaciones.cod_activ_obra', '{n}.v_cfpd05_denominaciones.deno_activ_obra');
			$this->concatena($lista, 'vector');
		break;
		case 'partida'://echo "1";
		    $this->set('no','');
			$this->set('SELECT','generica');
			$this->set('codigo','partida');
			$this->set('seleccion','');
			$this->set('n',7);
			$ano =  $this->Session->read('ano');
			$sec =  $this->Session->read('sec');
			$prog =  $this->Session->read('prog');
			$subp =  $this->Session->read('subp');
			$proy=$this->Session->read('proy');
			$this->Session->write('activ',$var);
		    $cond .=" and ano=".$ano." and cod_sector=".$sec." and cod_programa=".$prog." and cod_sub_prog=".$subp." and cod_proyecto=".$proy." and cod_activ_obra=".$var;
			$lista=  $this->v_cfpd05_denominaciones->generateList($cond, 'cod_partida ASC', null, '{n}.v_cfpd05_denominaciones.cod_partida', '{n}.v_cfpd05_denominaciones.deno_partida');
			$this->concatena($lista, 'vector');
		break;
		case 'generica'://echo "1";
		    $this->set('no','');
			$this->set('SELECT','especifica');
			$this->set('codigo','generica');
			$this->set('seleccion','');
			$this->set('n',8);
			$ano =  $this->Session->read('ano');
			$sec =  $this->Session->read('sec');
			$prog =  $this->Session->read('prog');
			$subp =  $this->Session->read('subp');
			$proy=$this->Session->read('proy');
			$activ=$this->Session->read('activ');
			$this->Session->write('part',$var);
		    $cond .=" and ano=".$ano." and cod_sector=".$sec." and cod_programa=".$prog." and cod_sub_prog=".$subp." and cod_proyecto=".$proy." and cod_activ_obra=".$activ." and cod_partida=".$var;
			$lista=  $this->v_cfpd05_denominaciones->generateList($cond, 'cod_generica ASC', null, '{n}.v_cfpd05_denominaciones.cod_generica', '{n}.v_cfpd05_denominaciones.deno_generica');
			$this->concatena($lista, 'vector');
 		break;
		case 'especifica'://echo "1";
		    $this->set('no','');
			$this->set('SELECT','subespecifica');
			$this->set('codigo','especifica');
			$this->set('seleccion','');
			$this->set('n',9);
			$ano =  $this->Session->read('ano');
			$sec =  $this->Session->read('sec');
			$prog =  $this->Session->read('prog');
			$subp =  $this->Session->read('subp');
			$proy=$this->Session->read('proy');
			$activ=$this->Session->read('activ');
			$part=$this->Session->read('part');
			$this->Session->write('gene',$var);
		    $cond .=" and ano=".$ano." and cod_sector=".$sec." and cod_programa=".$prog." and cod_sub_prog=".$subp." and cod_proyecto=".$proy." and cod_activ_obra=".$activ." and cod_partida=".$part." and cod_generica=".$var;
			$lista=  $this->v_cfpd05_denominaciones->generateList($cond, 'cod_especifica ASC', null, '{n}.v_cfpd05_denominaciones.cod_especifica', '{n}.v_cfpd05_denominaciones.deno_especifica');
			$this->concatena($lista, 'vector');
		break;
		case 'subespecifica'://echo "1";
		 	$this->set('no','');
			$this->set('SELECT','auxiliar');
			$this->set('codigo','subespecifica');
			$this->set('seleccion','');
			$this->set('n',10);
			$ano =  $this->Session->read('ano');
			$sec =  $this->Session->read('sec');
			$prog =  $this->Session->read('prog');
			$subp =  $this->Session->read('subp');
			$proy=$this->Session->read('proy');
			$activ=$this->Session->read('activ');
			$part=$this->Session->read('part');
			$gene=$this->Session->read('gene');
			$this->Session->write('espec',$var);
		    $cond .=" and ano=".$ano." and cod_sector=".$sec." and cod_programa=".$prog." and cod_sub_prog=".$subp." and cod_proyecto=".$proy." and cod_activ_obra=".$activ." and cod_partida=".$part." and cod_generica=".$gene." and cod_especifica=".$var;
			$lista=  $this->v_cfpd05_denominaciones->generateList($cond, 'cod_sub_espec ASC', null, '{n}.v_cfpd05_denominaciones.cod_sub_espec', '{n}.v_cfpd05_denominaciones.deno_sub_espec');
			$this->concatena($lista, 'vector');
		break;
		case 'auxiliar'://echo "1";
	    	$this->set('no','');
			$this->set('SELECT','monto');
			$this->set('codigo','auxiliar');
			$this->set('seleccion',null);
			$this->set('n',11);
			$ano =  $this->Session->read('ano');
			$sec =  $this->Session->read('sec');
			$prog =  $this->Session->read('prog');
			$subp =  $this->Session->read('subp');
			$proy=$this->Session->read('proy');
			$activ=$this->Session->read('activ');
			$part=$this->Session->read('part');
			$gene=$this->Session->read('gene');
			$espec=$this->Session->read('espec');
			$this->Session->write('sub_espec',$var);
		    $cond .=" and ano=".$ano." and cod_sector=".$sec." and cod_programa=".$prog." and cod_sub_prog=".$subp." and cod_proyecto=".$proy." and cod_activ_obra=".$activ." and cod_partida=".$part." and cod_generica=".$gene." and cod_especifica=".$espec." and cod_sub_espec=".$var;
			$lista=  $this->v_cfpd05_denominaciones->generateList($cond, 'cod_auxiliar ASC', null, '{n}.v_cfpd05_denominaciones.cod_auxiliar', '{n}.v_cfpd05_denominaciones.deno_auxiliar');
			//$this->concatena($lista, 'vector');
			//print_r($lista);
			if($lista!=null){
							$this->concatena($lista, 'vector');
						}else{
							$this->set('vector',array('0'=>'00'));

						}
		break;
		case 'monto':
		// echo "2";
		    $this->set('no','');
		    	$this->set('n',11);

			$ano =  $this->Session->read('ano');
			$sec =  $this->Session->read('sec');
			$prog =  $this->Session->read('prog');
			$subp =  $this->Session->read('subp');
			$proy=$this->Session->read('proy');
			$activ=$this->Session->read('activ');
			$part=$this->Session->read('part');
			$gene=$this->Session->read('gene');
			$espec=$this->Session->read('espec');
			$sub_espec=$this->Session->read('sub_espec');
		    $cond .=" and ano=".$ano." and cod_sector=".$sec." and cod_programa=".$prog." and cod_sub_prog=".$subp." and cod_proyecto=".$proy." and cod_activ_obra=".$activ." and cod_partida=".$part." and cod_generica=".$gene." and cod_especifica=".$espec." and cod_sub_espec=".$sub_espec." and cod_auxiliar=".$var;
			$frecuencia=$this->Session->read('frecuen');
			$mes=$this->Session->read('mes');
			if($mes==1){
				$campo1='ene_';
			}else if($mes==2){
				$campo1='feb_';
			}else if($mes==3){
				$campo1='mar_';
			}else if($mes==4){
				$campo1='abr_';
			}else if($mes==5){
				$campo1='may_';
		    }else if($mes==6){
				$campo1='jun_';
		    }else if($mes==7){
				$campo1='jul_';
			}else if($mes==8){
				$campo1='ago_';
			}else if($mes==9){
				$campo1='sep_';
			}else if($mes==10){
				$campo1='oct_';
			}else if($mes==11){
				$campo1='nov_';
			}else if($mes==12){
				$campo1='dic_';
			}
		//	echo $frecuencia."  ".$campo1;

			/*if($frecuencia==1){
				$mon = $this->v_solicitud_cfpd05_p2->field($campo1.'montoq', $cond,null );
			}else{
				$mon = $this->v_solicitud_cfpd05_p2->field($campo1.'montom',$cond, null);
			}
			if($mon){
				$this->set('monto',$mon);
			}else{
				$this->set('monto','');
			}*/
			//$disponibilidad1=$this->disponibilidad($ano,$sec,$prog,$subp,$proy,$activ,$part,$gene,$espec,$sub_espec,$var);
			$consulta = $this->v_solicitud_cfpd05_p2->findAll($cond);
			//print_r($consulta);
			$disponibilidad1=0;
			foreach($consulta as $row){
				$disponibilidad1 = $row['v_solicitud_cfpd05_p2']['asignacion_anual_actualizada'];
				//$disponibilidad1= $row['v_solicitud_cfpd05_p2']['ene_montom']+$row['v_solicitud_cfpd05_p2']['feb_montom']+$row['v_solicitud_cfpd05_p2']['mar_montom']+$row['v_solicitud_cfpd05_p2']['abr_montom']+$row['v_solicitud_cfpd05_p2']['may_montom']+$row['v_solicitud_cfpd05_p2']['jun_montom']+$row['v_solicitud_cfpd05_p2']['jul_montom']+$row['v_solicitud_cfpd05_p2']['ago_montom']+$row['v_solicitud_cfpd05_p2']['sep_montom']+$row['v_solicitud_cfpd05_p2']['oct_montom']+$row['v_solicitud_cfpd05_p2']['nov_montom']+$row['v_solicitud_cfpd05_p2']['dic_montom'];
			}
			$monto_uses=$this->csrd01_solicitud_recurso_partidas->execute("select cod_sector,cod_programa,cod_sub_prog,cod_proyecto,cod_activ_obra,cod_partida,cod_generica,cod_especifica,cod_sub_espec,cod_auxiliar,sum(monto) as monto from csrd01_solicitud_recurso_partidas where ".$this->SQLCA()." group by cod_sector,cod_programa,cod_sub_prog,cod_proyecto,cod_activ_obra,cod_partida,cod_generica,cod_especifica,cod_sub_espec,cod_auxiliar order by cod_sector,cod_programa,cod_sub_prog,cod_proyecto,cod_activ_obra,cod_partida,cod_generica,cod_especifica,cod_sub_espec,cod_auxiliar asc");
			foreach($monto_uses as $x){
			 $sector=$x[0]['cod_sector'];
			 $programa=$x[0]['cod_programa'];
			 $sub_prog=$x[0]['cod_sub_prog'];
			 $proyecto=$x[0]['cod_proyecto'];
			 $activ_obra=$x[0]['cod_activ_obra'];
			 $par=$x[0]['cod_partida'];
			 $generica = $x[0]['cod_generica'];
			 $especifica= $x[0]['cod_especifica'];
			 $sub_espec = $x[0]['cod_sub_espec'];
			 $auxiliar = $x[0]['cod_auxiliar'];
			if ($sec==$sector && $prog==$programa && $subp==$sub_prog && $proy==$proyecto && $activ==$activ_obra && $part==$par && $gene==$generica && $espec==$especifica && $sub_espec==$sub_espec && $var==$auxiliar){
			    $disponibilidad1=$this->Formato2($disponibilidad1);
				$disponibilidad1=$this->Formato1($disponibilidad1);
				$x[0]['monto']=$this->Formato2($x[0]['monto']);
				$x[0]['monto']=$this->Formato1($x[0]['monto']);
				$disponibilidad1=$disponibilidad1-$x[0]['monto'];
				break;
			}
		}
		/*if($disponibilidad1<=0){
			$disponibilidad1=0;
		}*///importante esta parte!!
			$this->set('monto',$disponibilidad1);
			$this->set('monto_ver','');
			$this->set('ocultar','');
			echo "<script>";
				echo "document.getElementById('agregar').disabled=false;";
			echo "</script>";
		break;
	}

}else{
	$this->set('ocultar','');
	//echo $select;
	if($select!='monto'){
		$this->set('vacio','');
	}else{
		$this->set('vacio','monto');
	}
}

}//fin select codigos presupuestarios




function agregar_partidas($var=null) {
	$this->layout="ajax";
	    $ano=$this->data["csrp01_solicitud_recurso2"]["ano_partidas"];
	    $sector=$this->data["csrp01_solicitud_recurso2"]["cod_sector"];
	    $prog=$this->data["csrp01_solicitud_recurso2"]["cod_programa"];
	    $subprog=$this->data["csrp01_solicitud_recurso2"]["cod_subprograma"];
	    $proy=$this->data["csrp01_solicitud_recurso2"]["cod_proyecto"];
	    $activ=$this->data["csrp01_solicitud_recurso2"]["cod_actividad"];
	    $part=$this->data["csrp01_solicitud_recurso2"]["cod_partida"];
	    $gene=$this->data["csrp01_solicitud_recurso2"]["cod_generica"];
	    $espec=$this->data["csrp01_solicitud_recurso2"]["cod_especifica"];
	    $subespec=$this->data["csrp01_solicitud_recurso2"]["cod_subespecifica"];
	    $aux=$this->data["csrp01_solicitud_recurso2"]["cod_auxiliar"];
	    $monto=$this->Formato1($this->data["csrp01_solicitud_recurso2"]["disponibilidad1"]);
	    $monto2=$this->Formato1($this->data["csrp01_solicitud_recurso2"]["monto_partidas"]);
	    //echo $monto=$this->data["csrp01_solicitud_recurso2"]["disponibilidad1"];
	    //echo "<br>".$monto2=$this->data["csrp01_solicitud_recurso2"]["monto_partidas"];
		$dispo=$this->disponibilidad($ano,$sector,$prog,$subprog,$proy,$activ,$part,$gene,$espec,$subespec,$aux);

if($monto<=0){
	  $this->set('errorMessage', 'No tiene disponibilidad,no se podra agregar a la lista');
 	if(!isset($_SESSION["contador"])){
 	$this->set('vacio','');
 	}
	return;
}//fin monto

if($monto2<=0){
	  $this->set('errorMessage', 'El monto a solicitar debe ser mayor al ingresado');
 	if(!isset($_SESSION["contador"])){
 	$this->set('vacio','');
 	}
	return;
}//fin monto

if($monto2 > $monto){
	  $this->set('errorMessage', 'El monto ingresado es mayor a la disponibilidad');
	  echo "<script>";
	  	echo "document.getElementById('monto2').value='';";
	  echo "</script>";
 	if(!isset($_SESSION["contador"])){
 	$this->set('vacio','');
 	}
	return;
}//fin monto




	if(isset($_SESSION["contador"])){
        $_SESSION["contador"]=$_SESSION["contador"]+1;
	}else{
		$_SESSION["contador"]=1;
	}
	if(isset($var) && !empty($var)){
            $cod[0]=$this->data["csrp01_solicitud_recurso2"]["ano_partidas"];
			$cod[1]=$this->data["csrp01_solicitud_recurso2"]["cod_sector"];
			$cod[2]=$this->data["csrp01_solicitud_recurso2"]["cod_programa"];
			$cod[3]=$this->data["csrp01_solicitud_recurso2"]["cod_subprograma"];
			$cod[4]=$this->data["csrp01_solicitud_recurso2"]["cod_proyecto"];
			$cod[5]=$this->data["csrp01_solicitud_recurso2"]["cod_actividad"];
			$cod[6]=$this->data["csrp01_solicitud_recurso2"]["cod_partida"];
			if($cod[6]<9){
				$cod[6]="40".$cod[6];
			}else if($cod[6]<100){
				$cod[6]="4".$cod[6];
			}else{
				$cod[6]=$cod[6];
			}

			$cod[7]=$this->data["csrp01_solicitud_recurso2"]["cod_generica"];
			$cod[8]=$this->data["csrp01_solicitud_recurso2"]["cod_especifica"];
			$cod[9]=$this->data["csrp01_solicitud_recurso2"]["cod_subespecifica"];
			$cod[10]=$this->data["csrp01_solicitud_recurso2"]["cod_auxiliar"];//
			$cod[10]=$cod[10]<9?str_replace("0","",$cod[10]):$cod[10];
			$cod[10]=$cod[10]<9?"0".$cod[10]:$cod[10];
			$cod[11]=$this->data["csrp01_solicitud_recurso2"]["monto_partidas"];

		    if(isset($_SESSION["i"])){
			$i=$this->Session->read("i")+1;
			$this->Session->write("i",$i);
	    }else{
		   $this->Session->write("i",0);
			$i=0;
		}
        switch($var){
        	case 'normal':
					 $vec[$i][0]=$this->data["csrp01_solicitud_recurso2"]["ano_partidas"];
					 $vec[$i][1]=$this->AddCeroR($this->data["csrp01_solicitud_recurso2"]["cod_sector"]);
					 $vec[$i][2]=$this->AddCeroR($this->data["csrp01_solicitud_recurso2"]["cod_programa"]);
					 $vec[$i][3]=$this->AddCeroR($this->data["csrp01_solicitud_recurso2"]["cod_subprograma"]);
					 $vec[$i][4]=$this->AddCeroR($this->data["csrp01_solicitud_recurso2"]["cod_proyecto"]);
					 $vec[$i][5]=$this->AddCeroR($this->data["csrp01_solicitud_recurso2"]["cod_actividad"]);
					 $vec[$i][6]=$this->data["csrp01_solicitud_recurso2"]["cod_partida"];//<9 ? "4.0".$this->data["cepp01_compromiso_partidas"]["cod_partida"] : "4.".$this->data["cepp01_compromiso_partidas"]["cod_partida"];
					 $vec[$i][7]=$this->AddCeroR($this->data["csrp01_solicitud_recurso2"]["cod_generica"]);
					 $vec[$i][8]=$this->AddCeroR($this->data["csrp01_solicitud_recurso2"]["cod_especifica"]);
					 $vec[$i][9]=$this->AddCeroR($this->data["csrp01_solicitud_recurso2"]["cod_subespecifica"]);
					 //$this->data["csrp01_solicitud_recurso2"]["cod_auxiliar"];
					 $vec[$i][10]=$this->AddCeroR($this->data["csrp01_solicitud_recurso2"]["cod_auxiliar"]);
					//$vec[$i][11]=$this->data["csrp01_solicitud_recurso2"]["disponibilidad1"];
					$vec[$i][11]=$this->data["csrp01_solicitud_recurso2"]["monto_partidas"];
					 //$vec[$i][11]=$this->data["csrp01_solicitud_recurso2"]["monto_partidas"];
					 //$cod[$i][11]=$this->data["csrp01_solicitud_recurso2"]["disponibilidad1"];
					 $vec[$i]["id"]=$i;
					 if(isset($_SESSION["items"])){
						foreach($_SESSION["items"] as $codi){
							//echo $codi[0].$cod[0].$codi[1].$cod[1].$codi[2].$cod[2].$codi[3].$cod[3].$codi[4].$cod[4].$codi[5].$cod[5].$codi[6].$cod[6].$codi[7].$cod[7]. $codi[8].$cod[8].$codi[9].$cod[9].$codi[10].$cod[10];
            	           if($codi[0]==$cod[0] && $codi[1]==$cod[1] && $codi[2]==$cod[2] && $codi[3]==$cod[3] && $codi[4]==$cod[4] && $codi[5]==$cod[5] && $codi[6]==$cod[6] && $codi[7]==$cod[7] && $codi[8]==$cod[8] && $codi[9]==$cod[9] && $codi[10]==$cod[10]){
                              $est=true;
                              break;
            	          }else{
            	          	 $est=false;
            	          }
                        }//fin foreach
                        if($est==true){
                           //	echo "no";
            	          	$i=$this->Session->read("i")-1;
				            $this->Session->write("i",$i);
				            $this->set('errorMessage', 'Los codigos seleccionados ya existen en la lista');
                        }else{
                        	$_SESSION["items"]=$_SESSION["items"]+$vec;
                          //  echo "si";
                        }
					 }else{
						$_SESSION["items"]=$vec;
					 }
				    /*echo "<script>";
   						echo "document.getElementById('save').disabled=false;";desbloquear cualquier cosa
   					echo "</script>";*/
        	break;
        	case 'nuevos':
                     $vec[$i][0]=$cod[0];
					 $vec[$i][1]=$this->AddCeroR($cod[1]);
					 $vec[$i][2]=$this->AddCeroR($cod[2]);
					 $vec[$i][3]=$this->AddCeroR($cod[3]);
					 $vec[$i][4]=$this->AddCeroR($cod[4]);
					 $vec[$i][5]=$this->AddCeroR($cod[5]);
					 $vec[$i][6]=$cod[6];
					 $vec[$i][7]=$this->AddCeroR($cod[7]);
					 $vec[$i][8]=$this->AddCeroR($cod[8]);
					 $vec[$i][9]=$this->AddCeroR($cod[9]);
					 $vec[$i][10]=$this->AddCeroR($cod[10]);
					 $vec[$i][11]=$cod[11];
					 $vec[$i]["id"]=$i;
					 if(isset($_SESSION["items"])){
						foreach($_SESSION["items"] as $codi){
							//echo $codi[0].$cod[0].$codi[1].$cod[1].$codi[2].$cod[2].$codi[3].$cod[3].$codi[4].$cod[4].$codi[5].$cod[5].$codi[6].$cod[6].$codi[7].$cod[7]. $codi[8].$cod[8].$codi[9].$cod[9].$codi[10].$cod[10];
            	           if($codi[0]==$cod[0] && $codi[1]==$cod[1] && $codi[2]==$cod[2] && $codi[3]==$cod[3] && $codi[4]==$cod[4] && $codi[5]==$cod[5] && $codi[6]==$cod[6] && $codi[7]==$cod[7] && $codi[8]==$cod[8] && $codi[9]==$cod[9] && $codi[10]==$cod[10]){
                              $est=true;
                              break;
            	          }else{
            	          	 $est=false;
            	          }
                        }//fin foreach
                        if($est==true){
                           //	echo "no";
            	          	$i=$this->Session->read("i")-1;
				            $this->Session->write("i",$i);
				            $this->set('errorMessage', 'Los codigos seleccionados ya existen en la lista');
                        }else{
                        	$_SESSION["items"]=$_SESSION["items"]+$vec;
                          //  echo "si";
                        }
					 }else{
						$_SESSION["items"]=$vec;
					 }
        	break;

        }//fin switch
		}//

	echo'<script>';
		echo "document.getElementById('agregar').disabled='disabled';";
        echo "document.getElementById('monto1').value=''   ;";
        echo"   document.getElementById('select_7').options[1].selected = true; ";
        echo"   document.getElementById('select_8').innerHTML='<select></select>';  ";
        echo"   document.getElementById('select_9').innerHTML='<select></select>';  ";
        echo"   document.getElementById('select_10').innerHTML='<select></select>';  ";
        echo"   document.getElementById('select_11').innerHTML='<select id=seleccion_11 class=select100>';  ";
        echo "document.getElementById('monto2').value='';";

 	echo'</script>';



}//fin funcu¡ions



function limpiar_lista () {
	$this->layout = "ajax";
	$this->Session->delete("items");
	$this->Session->delete("i");
	$this->Session->delete("contador");
	$this->Session->delete("recursos");
	/*echo "<script>";
   		echo "document.getElementById('save').disabled='disabled';";desbloquear cualquier cosa
   	echo "</script>";*/
}




function eliminar_items ($id) {
	$this->layout = "ajax";
	$_SESSION["items"][$id]=null;
	$monto_total=0;
	foreach($_SESSION ["items"] as $codigos){
       $monto_total=$monto_total+$this->Formato1($codigos[11]);
	}
	$this->set('total_partidas_rc',$monto_total);
    $_SESSION["contador"]=$_SESSION["contador"]-1;

   		////////////////actualiza el monto en la grilla de agrega_partidas///////////////////////
   		echo "<script>";
			echo "document.getElementById('total_partidas_rc').innerHTML='".$this->Formato2($monto_total)."';";
   			//echo "document.getElementById('input_tag".$i."').value='".$this->Formato2($variable)."';";
   		echo "</script>";
}




function eliminar_items_recurso ($id=null,$tipo=null) {
	$this->layout = "ajax";
	//echo $tipo;
	//print_r($_SESSION["recurso"]);
	$_SESSION["recursos"][$id]=null;
	$monto_total=0;
	if($tipo=="partidas"){
		$this->set('partidas','');
	}else if($tipo=="subpartidas"){
		$this->set('subpartidas','');
	}
	//print_r($_SESSION["recursos"]);
	/*foreach($_SESSION ["recursos"] as $codigos){
		//print_r($codigos);
		if($tipo="partida"){
       		$monto_total=$monto_total+$this->Formato1($codigos["monto"]);
       		$this->set('partida','');
		}else if($tipo="subpartidas"){
			$monto_total=$monto_total+$this->Formato1($codigos["monto"]);
			$this->set('subpartidas','');
		}
	}*/
	foreach($_SESSION ["recursos"] as $codigos){
		//print_r($_SESSION ["recursos");
			$monto_total=$monto_total+$this->Formato1($codigos["monto"]);
	}
	$this->set('total_partidas_rc',$monto_total);
    $_SESSION["contador"]=$_SESSION["contador"]-1;
}



function buscar(){
	$this->layout="ajax";
	$ano=$this->ano_ejecucion();
	//$lista = $this->v_csrd01_solicitud_recurso_cuerpo->generateList($this->SQLCA()." and ano_solicitud=".$ano, $order = 'numero_solicitud', $limit = null, '{n}.csrd01_solicitud_recurso_cuerpo.numero_solicitud', '{n}.csrd01_solicitud_recurso_cuerpo.denominacion');
	$listadependencia=$this->v_csrd01_solicitud_recurso_cuerpo->generateList($this->SQLCA()." and ano_solicitud=".$ano, 'numero_solicitud ASC', null, '{n}.v_csrd01_solicitud_recurso_cuerpo.numero_solicitud', '{n}.v_csrd01_solicitud_recurso_cuerpo.fecha_solicitud');
    $this->concatena_prueba($listadependencia, 'numero_solicitud');//print_r($listadependencia);
}// fin buscar




function result_busqueda($var=null){
	$this->layout="ajax";

	 $ano=$this->ano_ejecucion();
	 $datos = $this->csrd01_solicitud_recurso_partidas->findAll($this->SQLCA()." and ano_solicitud=".$ano." and numero_solicitud=".$var);
	 $x = $this->csrd01_solicitud_recurso_cuerpo->findAll($this->SQLCA()." and ano_solicitud=".$ano." and numero_solicitud=".$var);
				$this->set('numero',$var);
				$this->set('datos',$datos);
				$this->set('ano',$x[0]["csrd01_solicitud_recurso_cuerpo"]["ano_solicitud"]);//el concepto
				$this->set('concepto',$x[0]["csrd01_solicitud_recurso_cuerpo"]["concepto"]);//el concepto
				$this->set('fre',$x[0]["csrd01_solicitud_recurso_cuerpo"]["frecuencia_solicitud"]);//la frecuencia mensual o quincenal
				$this->set('fecha',$x[0]["csrd01_solicitud_recurso_cuerpo"]["fecha_solicitud"]);//la fecha para la cual se registro
				$this->set('dependencia',$_SESSION["dependencia"]);////La dependencia
				$this->set('tipo_recurso',$x[0]["csrd01_solicitud_recurso_cuerpo"]["tipo_solicitud_recurso"]);//el concepto
				$deno_tipo = $this->csrd01_tipo_solicitud->field('denominacion'," cod_tipo_solicitud=".$x[0]["csrd01_solicitud_recurso_cuerpo"]["tipo_solicitud_recurso"], $order ="cod_tipo_solicitud ASC");
				$this->set('deno_tipo', $deno_tipo);

				$forma_solicitud= $x[0]["csrd01_solicitud_recurso_cuerpo"]["forma_solicitud"];
				$mes_solicitud= $x[0]["csrd01_solicitud_recurso_cuerpo"]["mes_solicitado"];
				$numero_quincena= $x[0]["csrd01_solicitud_recurso_cuerpo"]["numero_quincena"];
				$this->set('clasificada', $forma_solicitud);
				$this->set('mes_solicitud', $mes_solicitud);
				$this->set('numero_quincena', $numero_quincena);


				/////////////////////////////PARA MONTAR LOS DATOS DE LA PARTE DE ABAJO//////////////////////////////////


	 $x=$this->csrd01_solicitud_recurso_cuerpo->execute("SELECT cod_entidad_bancaria, cod_sucursal, numero_cheque,cuenta_bancaria,fecha_cheque,monto_entregado FROM csrd01_solicitud_recurso_cuerpo WHERE ".$this->SQLCA()." and numero_solicitud=".$var);
		if($x!=null){
			$cheque=$x[0][0]["numero_cheque"];
			if($cheque!=0){
				//echo "hoooola";
				 $entidad=$x[0][0]["cod_entidad_bancaria"];
				 $sucursal=$x[0][0]["cod_sucursal"];
				 $fecha_cheque=$x[0][0]["fecha_cheque"];
				 $cuenta=$x[0][0]["cuenta_bancaria"];
				  $monto2=$x[0][0]["monto_entregado"];
				$e=$this->cstd01_entidades_bancarias->execute("SELECT * FROM cstd01_entidades_bancarias WHERE cod_entidad_bancaria=".$entidad);
				if($e!=null){
					$deno_entidad=$e[0][0]["denominacion"];
				}else{//echo "aqui";
					$deno_entidad="";
				}
				$d=$this->cstd01_sucursales_bancarias->execute("SELECT * FROM cstd01_sucursales_bancarias WHERE cod_entidad_bancaria=".$entidad." and cod_sucursal=".$sucursal);
				if($d!=null){
					$deno_sucursal=$d[0][0]["denominacion"];
				}else{
					$deno_sucursal="";
				}


				$this->set("cheque",$cheque);
				$this->set("deno_entidad",$deno_entidad);
				$this->set("deno_sucursal",$deno_sucursal);
				$this->set("cuenta",$cuenta);
				$this->set("fecha_cheque",$fecha_cheque);
				$this->set("monto2",$monto2);
			}else{
				//echo "cheque vacio";
				$this->set("cheque","");
				$this->set("deno_entidad","");
				$this->set("deno_sucursal","");
				$this->set("cuenta","");
				$this->set("fecha_cheque","");
				$this->set("monto2","");
			}
		}else{
				$this->set("cheque","");
				$this->set("deno_entidad","");
				$this->set("deno_sucursal","");
				$this->set("cuenta","");
				$this->set("fecha_cheque","");
				$this->set("monto2","");
			//echo "hooooooola2222";
			$cheque="";
		}


}// result_busqueda





function salir_solicitud($num_rc=null){
	$this->layout="ajax";
	$ano=$this->ano_ejecucion();
	$resultado=$this->csrd01_solicitud_recurso_numero->execute("UPDATE  csrd01_solicitud_recurso_numero SET situacion=1 WHERE ".$this->SQLCA()." and numero_solicitud=".$num_rc." and ano_solicitud=".$ano." and situacion=2");
	//$this->('index');
}


}//fin controller
?>