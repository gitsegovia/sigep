<?php
/*
 * Created on 19/07/2007
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */

 class Cfpp10ReformulacionFuncionariosController extends AppController{
	  var $name = 'cfpp10_reformulacion_funcionarios';
     var $uses = array('cnmd06_fichas','casd01_datos_personales','ccnd01_directiva','cnmd06_especialidades','cnmd06_profesiones','cnmd06_oficio','cnmd06_datos_personales','cfpd10_reformulacion_partidas_tmp','cscd02_solicitud_cuerpo','cscd03_cotizacion_encabezado','cscd04_ordencompra_encabezado','cepd03_ordenpago_partidas','cfpd10_reformulacion_funcionarios','cfpd10_reformulacion_partidas','cfpd05','cfpd05_requerimiento','cfpd10_reformulacion_texto');
     var $helpers = array('Html','Ajax','Javascript', 'Sisap');
    // var $paginate = array('limit' => 3, 'page' => 1);
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


    function AddCeroR($n,$extra=null){
   	  if($n!=null){
   	  	  if($extra==null){
        	if($n<10){
        	   $Var="0".$n;
        	}else{
	           $Var=$n;
        	}
   	  }else{
        	if($n<10){
        	   $Var=$extra.".0".$n;
        	}else{
	           $Var=$extra.".".$n;
        	}
   	  }

   	  $Var = substr($Var, - 2);

   	return $Var;
   	  }else{
   	  	  //return $Var;
   	  }



   }//fin AddCero


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


   function Formato1($monto) {
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
    }



    function index(){
       $this->layout = "ajax";
	         $resp=$this->cfpd10_reformulacion_funcionarios->findCount($this->SQLCA());
          	 if($resp==1){
          	 	$datos=$this->cfpd10_reformulacion_funcionarios->findAll($this->SQLCA());
          	 	$this->set('datos',$datos);
          	 	$this->indexc();
          		$this->render("indexc");
          	 }

/*
	$cont=0;
    $especialidades=$this->cnmd06_datos_personales->findAll();
	echo '<table border=1><tr><td>CODIGO PROFESION</td></tr>';
	foreach($especialidades as $es){
		$cod_pro=$es['cnmd06_datos_personales']['cod_oficio'];
		//$cod_esp=$es['casd01_datos_personales']['cod_especialidad'];
		$veri=$this->cnmd06_oficio->findCount('cod_oficio='.$cod_pro);
			if($veri==0){
				$cont ++;
				echo '<tr><td>'.$cod_pro.'</td></tr>';
	//			$sql='delete from cnmd06_especialidades where cod_profesion='.$cod_pro.' and cod_especialidad='.$cod_esp;
	//			$this->cnmd06_especialidades->execute($sql);
			}

	}
	echo '</table>';
	echo $cont;
*/

/*
	$cont=0;
    $especialidades=$this->cnmd06_profesiones->findAll();
	echo '<table border=1><tr><td>CODIGO PROFESION</td></tr>';
	foreach($especialidades as $es){
		$cod_pro=$es['cnmd06_profesiones']['cod_profesion'];
		//$cod_esp=$es['cnmd06_especialidades']['cod_especialidad'];
		$veri=$this->casd01_datos_personales->findCount('cod_profesion='.$cod_pro);
			if($veri==0){
				$cont ++;
				echo '<tr><td>'.$cod_pro.'</td></tr>';
	//			$sql='delete from cnmd06_especialidades where cod_profesion='.$cod_pro.' and cod_especialidad='.$cod_esp;
	//			$this->cnmd06_especialidades->execute($sql);
			}

	}
	echo '</table>';
	echo $cont;

	*/

/*
	$cont=0;
    $especialidades=$this->cnmd06_profesiones->findAll();
	echo '<table border=1><tr><td>CODIGO PROFESION</td></tr>';
	foreach($especialidades as $es){
		$cod_pro=$es['cnmd06_profesiones']['cod_profesion'];
		//$cod_esp=$es['cnmd06_especialidades']['cod_especialidad'];
		$veri=$this->ccnd01_directiva->findCount('cod_profesion='.$cod_pro);
			if($veri==0){
				$cont ++;
				echo '<tr><td>'.$cod_pro.'</td></tr>';
	//			$sql='delete from cnmd06_especialidades where cod_profesion='.$cod_pro.' and cod_especialidad='.$cod_esp;
	//			$this->cnmd06_especialidades->execute($sql);
			}

	}
	echo '</table>';
	echo $cont;
*/


/*
          	 $refor_par=$this->cfpd10_reformulacion_partidas->findAll();
$i=1;
$j=1;
$k=1;
$ma=0;
$md=0;

$mar=0;
$mdr=0;
echo '<table border=1>
		<tr><td>cod_dep</td>
			<td>numero_oficio</td>
			<td>numero_decreto</td>
			<td>cod_sector</td>
			<td>cod_programa</td>
			<td>cod_sub_programa</td>
			<td>cod_proyecto</td>
			<td>cod_activ_obra</td>
			<td>cod_partida</td>
			<td>cod_generica</td>
			<td>cod_especifica</td>
			<td>cod_sub_espec</td>
			<td>cod_auxiliar</td>
			<td>aumento</td>
			<td>disminucion</td>
		</tr>';


    foreach($refor_par as $row){
    $nof = $row['cfpd10_reformulacion_partidas']['numero_oficio'];
    $cde = $row['cfpd10_reformulacion_partidas']['codi_dep'];
	$cst = $row['cfpd10_reformulacion_partidas']['cod_sector'];
	$cpg = $row['cfpd10_reformulacion_partidas']['cod_programa'];
	$csp = $row['cfpd10_reformulacion_partidas']['cod_sub_prog'];
	$cpy = $row['cfpd10_reformulacion_partidas']['cod_proyecto'];
	$cac = $row['cfpd10_reformulacion_partidas']['cod_activ_obra'];
	$cpa = $row['cfpd10_reformulacion_partidas']['cod_partida'];
	$cge = $row['cfpd10_reformulacion_partidas']['cod_generica'];
	$ces = $row['cfpd10_reformulacion_partidas']['cod_especifica'];
	$cse = $row['cfpd10_reformulacion_partidas']['cod_sub_espec'];
	$cau = $row['cfpd10_reformulacion_partidas']['cod_auxiliar'];
	$mau = $row['cfpd10_reformulacion_partidas']['monto_aumento'];
	$mdi = $row['cfpd10_reformulacion_partidas']['monto_disminucion'];
	$mar=$mar + $mau;
	$mdr=$mdr + $mdi;


	$condicion='cod_dep='.$cde.' and cod_sector='.$cst.' and cod_programa='.$cpg.' and cod_sub_prog='.$csp.' and cod_proyecto='.$cpy.' and cod_activ_obra='.$cac.' and cod_partida='.$cpa.' and cod_generica='.$cge.' and cod_especifica='.$ces.' and cod_sub_espec='.$cse.' and cod_auxiliar='.$cau;
	$condicion2='cod_dep=1 and cod_sector='.$cst.' and cod_programa='.$cpg.' and cod_sub_prog='.$csp.' and cod_proyecto='.$cpy.' and cod_activ_obra='.$cac.' and cod_partida='.$cpa.' and cod_generica='.$cge.' and cod_especifica='.$ces.' and cod_sub_espec='.$cse.' and cod_auxiliar='.$cau;
	$cfpd05=$this->cfpd05->findCount($condicion);
	//$cfpd06=$this->cfpd05->findCount($condicion2);
	//echo $cfpd05.'-';
	if($cfpd05<1){
		$oficio=$this->cfpd10_reformulacion_texto->findAll("numero_oficio='".$nof."'");
		foreach($oficio as $row2){
			$fecha = $row2['cfpd10_reformulacion_texto']['fecha_oficio'];
			$decre = $row2['cfpd10_reformulacion_texto']['numero_decreto'];
			echo '<tr><td>'.$cde.'</td><td>'.$nof.'</td><td>'.$decre.'</td><td>'.$this->AddCeroR($cst).'</td><td>'.$this->AddCeroR($cpg).'</td><td>'.$this->AddCeroR($csp).'</td><td>'.$this->AddCeroR($cpy).'</td><td>'.$cac.'</td><td>'.$cpa.'</td><td>'.$this->AddCeroR($cge).'</td><td>'.$this->AddCeroR($ces).'</td><td>'.$this->AddCeroR($cse).'</td><td>'.$this->mascara_cuatro($cau).'</td><td>'.$mau.'</td><td>'.$mdi.'</td></tr>';
			$ma=$ma + $mau;
			$md=$md + $mdi;
		}

	}
    }

echo '</table>';
/*
$maf=$mar-$ma;
$mdf=$mdr-$md;

$ea05='select sum(credito_adicional_anual + aumento_traslado_anual) as sumaa from cfpd05';
$ed05='select sum(rebaja_anual + disminucion_traslado_anual) as sumad from cfpd05';
$a052=$this->cfpd05->execute($ea05);
$d052=$this->cfpd05->execute($ed05);
$a05=$a052[0][0]['sumaa'];
$d05=$d052[0][0]['sumad'];

$ta=$maf-$a05;
$td=$mdf-$d05;
echo '<table border=1>
		<tr><td>aumentos reformulacion t</td>
			<td>aumentos reformulacion f</td>
			<td>aumentos reformulacion</td>
			<td>aumentos 05</td>
			<td>resta aumentos</td>
			<td>rebajas reformulacion t</td>
			<td>rebajas reformulacion f</td>
			<td>rebajas reformulacion</td>
			<td>rebajas 05</td>
			<td>resta rebajas</td>
		</tr>
		<tr><td>'.$mar.'</td>
			<td>'.$ma.'</td>
			<td>'.$maf.'</td>
			<td>'.$a05.'</td>
			<td>'.$ta.'</td>
			<td>'.$mdr.'</td>
			<td>'.$md.'</td>
			<td>'.$mdf.'</td>
			<td>'.$d05.'</td>
			<td>'.$td.'</td>
		</tr>
	 </table>';


$xx=0;
$nn=1;
$refor_text=$this->cfpd10_reformulacion_texto->findAll('cod_tipo=2');
foreach($refor_text as $row2){
	$numero = $row2['cfpd10_reformulacion_texto']['numero_oficio'];
	$refor_part=$this->cfpd10_reformulacion_partidas->findAll("numero_oficio='".$numero."'");
foreach($refor_part as $row3){
	//echo $nn;
	$monto = $row3['cfpd10_reformulacion_partidas']['monto_aumento'];
	$xx=$xx+$monto;
	$nn++;
}
}

*/



/*          	 $orden_comp=$this->cscd04_ordencompra_encabezado->findAll();
$i=1;
$j=1;
$k=1;
$ma=0;
$md=0;

$mar=0;
$mdr=0;*/
/*
 $orden_comp=$this->cscd03_cotizacion_encabezado->findAll();
echo '<table border=1>
		<tr><td>cod_dep</td>
			<td>año</td>
			<td>numero_cotizacion</td>
			<td>numero solicitud</td>
			<td>rif</td>
			<td>fecha proceso registro</td>
		</tr>';

set_time_limit(0);
    foreach($orden_comp as $row){
    $cod_dep = $row['cscd03_cotizacion_encabezado']['cod_dep'];
    $ano = $row['cscd03_cotizacion_encabezado']['ano_cotizacion'];
	$nocompra = $row['cscd03_cotizacion_encabezado']['numero_cotizacion'];
	$ncotizacion = $row['cscd03_cotizacion_encabezado']['numero_solicitud'];
	$rif = $row['cscd03_cotizacion_encabezado']['rif'];
	$fecha = $row['cscd03_cotizacion_encabezado']['fecha_proceso'];

	$condicion="cod_dep=".$cod_dep." and ano_solicitud=".$ano." and numero_solicitud=".$ncotizacion;
	$cfpd05=$this->cscd02_solicitud_cuerpo->findCount($condicion);
	if($cfpd05<1){
			echo '<tr><td>'.$this->mascara_cuatro($cod_dep).'</td><td>'.$ano.'</td><td>'.$nocompra.'</td><td>'.$ncotizacion.'</td><td>'.$rif.'</td><td>'.$this->Cfecha($fecha,'D/M/A').'</td></tr>';
		}

    }

echo '</table>';


*/



//echo 'monto='.$xx.'num'.$nn;



/*
          	 $refor_text=$this->cfpd10_reformulacion_texto->findAll('cod_dep=1020');
$i=1;
$j=1;
$k=1;
echo '<table border=1>
		<tr>
			<td>Numero oficio</td>
			<td>Numero decreto</td>
		</tr>';


    foreach($refor_text as $row){
    $nof = $row['cfpd10_reformulacion_texto']['numero_oficio'];
    $nde = $row['cfpd10_reformulacion_texto']['numero_decreto'];
			echo '<tr><td>'.$nof.'</td><td>'.$nde.'</td></tr>';

	}

echo '</table>';

*/



/*
	 $borrar="update cfpd05 set precompromiso_congelado=0";
    $respx1=$this->cfpd05->execute($borrar);

	$total_dis=0;
	 $ref_temp=$this->cfpd10_reformulacion_partidas_tmp->findAll('monto_disminucion !=0',null,'ano_reformulacion,cod_dep,codi_dep,numero_oficio,cod_sector,cod_programa,cod_programa,cod_sub_prog,cod_proyecto,cod_activ_obra,cod_partida,cod_generica,cod_especifica,cod_sub_espec,cod_auxiliar ASC');
	 echo '<table border=1>
		<tr>
			<td>año</td>
			<td>cod dep</td>
			<td>codi dep</td>
			<td>numero oficio</td>
			<td>sector</td>
			<td>programa</td>
			<td>sub programa</td>
			<td>proyecto</td>
			<td>actividad</td>
			<td>partida</td>
			<td>generica</td>
			<td>especifica</td>
			<td>sub especifica</td>
			<td>auxiliar</td>
			<td>disminucion</td>
			<td>aumento</td>
		</tr>';
		set_time_limit(0);
    foreach($ref_temp as $row){
    $ano				 = $row['cfpd10_reformulacion_partidas_tmp']['ano_reformulacion'];
    $cod_dep			 = $row['cfpd10_reformulacion_partidas_tmp']['cod_dep'];
    $codi_dep			 = $row['cfpd10_reformulacion_partidas_tmp']['codi_dep'];
	$numero_oficio		 = $row['cfpd10_reformulacion_partidas_tmp']['numero_oficio'];
	$sector				 = $row['cfpd10_reformulacion_partidas_tmp']['cod_sector'];
	$programa			 = $row['cfpd10_reformulacion_partidas_tmp']['cod_programa'];
	$sub_prog			 = $row['cfpd10_reformulacion_partidas_tmp']['cod_sub_prog'];
	$proyecto			 = $row['cfpd10_reformulacion_partidas_tmp']['cod_proyecto'];
    $actividad			 = $row['cfpd10_reformulacion_partidas_tmp']['cod_activ_obra'];
	$partida			 = $row['cfpd10_reformulacion_partidas_tmp']['cod_partida'];
	$generica			 = $row['cfpd10_reformulacion_partidas_tmp']['cod_generica'];
	$especifica			 = $row['cfpd10_reformulacion_partidas_tmp']['cod_especifica'];
	$sub_especifica		 = $row['cfpd10_reformulacion_partidas_tmp']['cod_sub_espec'];
	$auxiliar			 = $row['cfpd10_reformulacion_partidas_tmp']['cod_auxiliar'];
	$monto_disminucion	 = $row['cfpd10_reformulacion_partidas_tmp']['monto_disminucion'];
	$monto_aumento		 = $row['cfpd10_reformulacion_partidas_tmp']['monto_aumento'];

	$total_dis=$total_dis + $monto_disminucion;
	//echo ' el monto es '.$total_dis;

	$validaxx="ano =".$ano." and cod_sector=".$sector." and cod_programa=".$programa." and cod_sub_prog=".$sub_prog." and cod_proyecto=".$proyecto." and cod_activ_obra=".$actividad." and  cod_partida=".$partida." and cod_generica=".$generica." and cod_especifica=".$especifica." and cod_sub_espec=".$sub_especifica." and cod_auxiliar=".$auxiliar." and cod_dep=".$codi_dep;
    $ud05="update cfpd05 set precompromiso_congelado=precompromiso_congelado + $monto_disminucion where ".$validaxx;
    $respxx=$this->cfpd05->execute($ud05);




	echo 	'<tr>
				<td>'.$ano.'</td>
				<td>'.$this->mascara_cuatro($cod_dep).'</td>
				<td>'.$this->mascara_cuatro($codi_dep).'</td>
				<td>'.$numero_oficio.'</td>
				<td>'.$this->AddCeroR($sector).'</td>
				<td>'.$this->AddCeroR($programa).'</td>
				<td>'.$this->AddCeroR($sub_prog).'</td>
				<td>'.$this->AddCeroR($proyecto).'</td>
				<td>'.$this->AddCeroR($actividad).'</td>
				<td>'.$this->AddCeroR($partida).'</td>
				<td>'.$this->AddCeroR($generica).'</td>
				<td>'.$this->AddCeroR($especifica).'</td>
				<td>'.$this->AddCeroR($sub_especifica).'</td>
				<td>'.$this->mascara_cuatro($auxiliar).'</td>
				<td>'.$this->Formato2($monto_disminucion).'</td>
				<td>'.$this->Formato2($monto_aumento).'</td>
			 </tr>';
    }

    $pre='select sum(precompromiso_congelado) as congelado from cfpd05';
    $prec=$this->cfpd05->execute($pre);
    echo $total_dis .' = '. $prec[0][0]['congelado'];
    //pr($prec);
*/
/*
	 $cuentas = $this->cnmd06_fichas->findAll("forma_pago=3 and cuenta_bancaria != '0'");
	 //pr($cuentas);
	 //$cu = 0;
	 $re = 0;
	 //echo '<table border=1 width=100%><tr><td width=20% align=center>ENTIDAD</td><td width=20% align=center>SUCURSAL</td><td width=20% align=center>CUENTA</td><td width=20% align=center>NC</td><td width=20% align=center>E-S</td></tr>';
	foreach($cuentas as $ct){
		$cod_entidad_bancaria 	= $ct['cnmd06_fichas']['cod_entidad_bancaria'];
		$cod_sucursal 			= $ct['cnmd06_fichas']['cod_sucursal'];
		$cuenta_bancaria 		= $ct['cnmd06_fichas']['cuenta_bancaria'];
		$cedula_identidad 		= $ct['cnmd06_fichas']['cedula_identidad'];
		$cod_dep				= $ct['cnmd06_fichas']['cod_dep'];
		$nc						= strlen($cuenta_bancaria);
		//$e 						= $cuenta_bancaria [0].$cuenta_bancaria [1].$cuenta_bancaria [2].$cuenta_bancaria [3];
		//$s 						= $cuenta_bancaria [4].$cuenta_bancaria [5].$cuenta_bancaria [6].$cuenta_bancaria [7];

		if($nc < 20){
		//if($cod_entidad_bancaria!=$e || $cod_sucursal!= $s){
		//	echo '<tr><td width=20% align=center>'.$cod_entidad_bancaria.'</td><td width=20% align=center>'.$cod_sucursal.'</td><td width=20% align=center>'.$cuenta_bancaria.'</td>'.'</td><td width=20% align=center>'.$nc.'</td><td width=20% align=center>'.$e.'-'.$s.'</td></tr>';
		//$cu ++;
			$nuew_cuenta = $this->arreglar_cuenta_bancaria_conformato($cuenta_bancaria);
			if($nuew_cuenta != 0){
				$act = "update cnmd06_fichas set cuenta_bancaria='".$nuew_cuenta."' where cod_entidad_bancaria=$cod_entidad_bancaria and cod_sucursal=$cod_sucursal and cedula_identidad=$cedula_identidad and cuenta_bancaria='".$cuenta_bancaria."' and cod_dep=$cod_dep";
				$this->cnmd06_fichas->execute($act);
				$re ++;
			}
		//	$nc2 				= strlen($nuew_cuenta);
		//	if($nc != $nc2){
		//		$di = $nc2 - $nc;
		//	}else{
		//		$di=0;
		//	}
		//	echo $cedula_identidad.'---'.$cuenta_bancaria.'---'.$nuew_cuenta.'---'.$di.'<br>';
		}
	}
	//echo '</table>';
	//echo $cu.'-'.$re.'-'.($re - $cu);
	echo $re;


*/

	 }//fin index

function arreglar_cuenta_bancaria_conformato($cuenta = null){
        if(strlen($cuenta)>15){
		$banco_sucursal = substr($cuenta,0,8);
		$paso1 = substr($cuenta,8);
		$paso2 = substr($paso1,0,1);

                $x1 = substr($cuenta,8);
		$x1 = substr($x1,0,2);
		$x1 = substr($x1,1);

                if($x1=='-'){
                   $paso2='0'.$paso2;
                }else{
                   $paso2 = ''.$paso2;
                }
		$paso5 = substr($paso1,1);
	        $nueva_cuenta_bancaria = $banco_sucursal.$paso2.$paso5;
                //$nueva_cuenta_bancaria = $paso2;
        }else{
		$nueva_cuenta_bancaria = 0;
        }
	return $nueva_cuenta_bancaria;
}



function indexc(){
	$this->layout = "ajax";
}


function guardar(){
	$this->layout = "ajax";//print_r($this->data['cfpp10_reformulacion_funcionarios']);
if(!empty($this->data)){
  $aa[1]=$this->verifica_SS(1);
  $aa[2]=$this->verifica_SS(2);
  $aa[3]=$this->verifica_SS(3);
  $aa[4]=$this->verifica_SS(4);
  $aa[5]=$this->verifica_SS(5);
  $funcionario_presupuesto=$this->data['cfpp10_reformulacion_funcionarios']['titulo_nombres_originar'];
  $cargo_presupuesto=$this->data['cfpp10_reformulacion_funcionarios']['cargo_originar'];
  $funcionario_envia_oficio=$this->data['cfpp10_reformulacion_funcionarios']['titulo_nombres_enviar'];
  $cargo_envia_oficio=$this->data['cfpp10_reformulacion_funcionarios']['cargo_enviar'];
  $funcionario_remite_oficio=$this->data['cfpp10_reformulacion_funcionarios']['titulo_nombres_remitir'];
  $cargo_remite_oficio=$this->data['cfpp10_reformulacion_funcionarios']['cargo_remitir'];
  $funcionario_aprueba=$this->data['cfpp10_reformulacion_funcionarios']['titulo_nombres_aprobar'];
  $cargo_aprueba=$this->data['cfpp10_reformulacion_funcionarios']['cargo_aprobar'];
  $funcionario_decreto=$this->data['cfpp10_reformulacion_funcionarios']['titulo_nombres_firmar'];
  $cargo_decreto=$this->data['cfpp10_reformulacion_funcionarios']['cargo_firmar'];
  $funcionario_refrenda=$this->data['cfpp10_reformulacion_funcionarios']['titulo_nombres_refrendar'];
  $cargo_refrenda=$this->data['cfpp10_reformulacion_funcionarios']['cargo_refrendar'];



  $SQL_INSERT ="INSERT INTO cfpd10_reformulacion_funcionarios (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, funcionario_presupuesto, cargo_presupuesto, funcionario_envia_oficio, cargo_envia_oficio,
  funcionario_remite_oficio, cargo_remite_oficio, funcionario_aprueba, cargo_aprueba, funcionario_decreto, cargo_decreto, funcionario_refrenda, cargo_refrenda)";
  $SQL_INSERT .=" VALUES (".$aa[1].",".$aa[2].",".$aa[3].",".$aa[4].",".$aa[5].", '".$funcionario_presupuesto."', '".$cargo_presupuesto."', '".$funcionario_envia_oficio."', '".$cargo_envia_oficio."',
  '".$funcionario_remite_oficio."', '".$cargo_remite_oficio."', '".$funcionario_aprueba."', '".$cargo_aprueba."', '".$funcionario_decreto."', '".$cargo_decreto."', '".$funcionario_refrenda."', '".$cargo_refrenda."')";
  $resp=$this->cfpd10_reformulacion_funcionarios->execute($SQL_INSERT);
  if($resp>1)
  {
    $this->data=null;
	 $this->set('Message_existe', 'Registro Agregado con exito.');
	 $datos=$this->cfpd10_reformulacion_funcionarios->findAll($this->SQLCA());
  $this->set('datos',$datos);
	 $this->indexc();
	 $this->render("indexc");//echo "si entro";
  }else if ($resp <= 1){
  $this->set('Message_existe', 'Disculpe, El Registro no fue creado.');
  $this->index();
  $this->render("index");//echo "no entro";
}// fin else
}//fin existe
}//fin guardar

function modificar(){
	$this->layout = "ajax";
	$datos=$this->cfpd10_reformulacion_funcionarios->findAll($this->SQLCA());
    $this->set('datos',$datos);
}

function guardar_modificar(){
	$this->layout = "ajax";
	//$cond=$this->SQLCA();
  $funcionario_presupuesto=$this->data['cfpp10_reformulacion_funcionarios']['titulo_nombres_originar'];
  $cargo_presupuesto=$this->data['cfpp10_reformulacion_funcionarios']['cargo_originar'];
  $funcionario_envia_oficio=$this->data['cfpp10_reformulacion_funcionarios']['titulo_nombres_enviar'];
  $cargo_envia_oficio=$this->data['cfpp10_reformulacion_funcionarios']['cargo_enviar'];
  $funcionario_remite_oficio=$this->data['cfpp10_reformulacion_funcionarios']['titulo_nombres_remitir'];
  $cargo_remite_oficio=$this->data['cfpp10_reformulacion_funcionarios']['cargo_remitir'];
  $funcionario_aprueba=$this->data['cfpp10_reformulacion_funcionarios']['titulo_nombres_aprobar'];
  $cargo_aprueba=$this->data['cfpp10_reformulacion_funcionarios']['cargo_aprobar'];
  $funcionario_decreto=$this->data['cfpp10_reformulacion_funcionarios']['titulo_nombres_firmar'];
  $cargo_decreto=$this->data['cfpp10_reformulacion_funcionarios']['cargo_firmar'];
  $funcionario_refrenda=$this->data['cfpp10_reformulacion_funcionarios']['titulo_nombres_refrendar'];
  $cargo_refrenda=$this->data['cfpp10_reformulacion_funcionarios']['cargo_refrendar'];

    $cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$cond = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep;



  $sql="update cfpd10_reformulacion_funcionarios set funcionario_presupuesto='".$funcionario_presupuesto."', cargo_presupuesto='".$cargo_presupuesto."', funcionario_envia_oficio='".$funcionario_envia_oficio."', cargo_envia_oficio='".$cargo_envia_oficio."',
  funcionario_remite_oficio='".$funcionario_remite_oficio."', cargo_remite_oficio='".$cargo_remite_oficio."', funcionario_aprueba='".$funcionario_aprueba."', cargo_aprueba='".$cargo_aprueba."', funcionario_decreto='".$funcionario_decreto."', cargo_decreto='".$cargo_decreto."', funcionario_refrenda='".$funcionario_refrenda."', cargo_refrenda='".$cargo_refrenda."' where ".$cond;
  $this->cfpd10_reformulacion_funcionarios->execute($sql);
  $datos=$this->cfpd10_reformulacion_funcionarios->findAll($this->SQLCA());
  $this->set('datos',$datos);
  $this->set('Message_existe', 'Registro actualizado con exito.');
  $this->indexc();
  $this->render("indexc");
}

function actualizar_datos_p(){
	$this->layout='ajax';
	$especialidades=$this->cnmd06_especialidades->findAll();
	echo '<table><tr><td>CODIGO PROFESION</td><td>CODIGO ESPECIALIDAD</td></tr>';
	foreach($especialidades as $es){
		$cod_pro=$es['cnmd06_especialidades']['cod_profesion'];
		$cod_esp=$es['cnmd06_especialidades']['cod_especialidad'];
		$veri=$this->cnmd06_datos_personales->findCount('cod_profesion='.$cod_pro.' and cod_especialidad='.$cod_esp);
			if($veri==0){
				echo '<tr><td>'.$cod_pro.'</td><td>'.$cod_esp.'</td></tr>';
			}

	}
	echo '</table>';
}


}//fin clase cfpp09Controller

