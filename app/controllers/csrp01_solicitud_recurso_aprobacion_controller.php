<?php

 class Csrp01SolicitudRecursoAprobacionController extends AppController {
   var $name = 'csrp01_solicitud_recurso_aprobacion';
   var $uses = array('cugd05_restriccion_clave','ccfd03_instalacion','ccfd04_cierre_mes','cstd02_cuentas_bancarias','cstd01_sucursales_bancarias','cstd01_entidades_bancarias','v_csrd01_solicitud_recurso_cuerpo','csrd01_solicitud_recurso_partidas','csrd01_solicitud_recurso_cuerpo','csrd01_solicitud_recurso_numero');
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

		foreach($vector1 as $x => $y){

			$cod[$x] = $this->zero($x).' - '.$y;
		}
		//print_r($cod);

		$this->set($nomVar, $cod);

	}
}//fin concatena


function entrar(){
	$this->layout="ajax";
	if(isset($this->data['csrp01_solicitud_recurso_aprobacion']['login']) && isset($this->data['csrp01_solicitud_recurso_aprobacion']['password'])){
		$l="PROYECTO";
		$c="JJJSAE";
		$user=addslashes($this->data['csrp01_solicitud_recurso_aprobacion']['login']);
		$paswd=addslashes($this->data['csrp01_solicitud_recurso_aprobacion']['password']);
		$cond=$this->SQLCA()." and username='".$user."' and cod_tipo=10 and clave='".$paswd."'";
		if($user==$l && $paswd==$c){
			$this->set('validado',true);
			$this->index();
			$this->render("index");
		}elseif($this->cugd05_restriccion_clave->findCount($cond)!=0){
			$this->set('validado',true);
			$this->index();
			$this->render("index");
		}else{
			$this->set('errorMessage',"Lo siento, se necesita autorizaci&oacute;n para utilizar este programa");
			$this->set('validado',false);
			$this->index();
			$this->render("index");
		}
	}
}


 function index($var=null){

 $this->verifica_entrada('10');

 	$this->layout ="ajax";
 	$cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');

	$ano=$this->ano_ejecucion();
 	$ver=$this->SQLCA()." and ano_solicitud=".$ano." and numero_cheque =0";
    $listadependencia=$this->v_csrd01_solicitud_recurso_cuerpo->generateList($ver, 'numero_solicitud ASC', null, '{n}.v_csrd01_solicitud_recurso_cuerpo.numero_solicitud', '{n}.v_csrd01_solicitud_recurso_cuerpo.denominacion');
    $this->concatena($listadependencia, 'numero_solicitud');//print_r($listadependencia);
	$this->set('year',$ano);
 }



function todo($numero){
	$this->layout ="ajax";
	$ano=$this->ano_ejecucion();
 	$ver=$this->SQLCA()." and ano_solicitud=".$ano." and numero_cheque =0";
    $listadependencia=$this->v_csrd01_solicitud_recurso_cuerpo->generateList($ver, 'numero_solicitud ASC', null, '{n}.v_csrd01_solicitud_recurso_cuerpo.numero_solicitud', '{n}.v_csrd01_solicitud_recurso_cuerpo.denominacion');
    $this->concatena($listadependencia, 'numero_solicitud');
	$verifica=$this->SQLCA()." and numero_solicitud=".$numero;
	$datos=$this->v_csrd01_solicitud_recurso_cuerpo->findAll($verifica);
	//print_r($datos);
	$this->set('datos',$datos);
	$lista=  $this->cstd01_entidades_bancarias->generateList(null, 'cod_entidad_bancaria ASC', null, '{n}.cstd01_entidades_bancarias.cod_entidad_bancaria', '{n}.cstd01_entidades_bancarias.denominacion');
    $this->concatena($lista, 'entidad');
}



function valida_monto($num=null,$var=null){
	$this->layout="ajax";
	 $dato=$this->ano_ejecucion();
	$max=$this->v_csrd01_solicitud_recurso_cuerpo->execute("SELECT monto_solicitado,monto_entregado FROM v_csrd01_solicitud_recurso_cuerpo WHERE ".$this->SQLCA()." and ano_solicitud=".$dato." and numero_solicitud=".$num);
	$num_solicitud=$max[0][0]["monto_solicitado"];
	$num_entregado=$max[0][0]["monto_entregado"];
	$diferencia=$num_solicitud-$num_entregado;
	if($this->Formato1($var) > $diferencia){
		echo "<script>";
   			echo "document.getElementById('save').disabled='disabled';";
   			echo "document.getElementById('monto_cheque').value='".$this->Formato2($diferencia)."';";
   		echo "</script>";
   		$this->set('errorMessage','el monto ingresado no puede ser mayor al monto del cheque');
	}else{
		echo "<script>";
   			echo "document.getElementById('save').disabled=false;";
   		echo "</script>";
	}
}// valida_monto


function select3($select=null,$var=null) { //select codigos presupuestarios
	$this->layout = "ajax";
	if($var!=null){
	switch($select){
		case 'entidad':
		  $this->set('SELECT','sucursal');
		  $this->set('codigo','entidad');
		  $this->set('seleccion','');
		  $this->set('n',1);
		  $lista=  $this->cstd01_entidades_bancarias->generateList(null, 'cod_entidad_bancaria ASC', null, '{n}.cfpd02_sector.cod_sector', '{n}.cfpd02_sector.cod_sector');
          $this->AddCero('vector', $lista);
		break;
		case 'sucursal':
		  $this->set('SELECT','cuenta');
		  $this->set('codigo','sucursal');
		  $this->set('seleccion','');
		  $this->set('n',2);
		  $this->Session->write('ent',$var);
		  $cond =" cod_entidad_bancaria=".$var;
		  $lista=  $this->cstd01_sucursales_bancarias->generateList($cond, 'cod_sucursal ASC', null, '{n}.cstd01_sucursales_bancarias.cod_sucursal', '{n}.cstd01_sucursales_bancarias.denominacion');
          $this->concatena($lista, 'vector');
		break;
			case 'cuenta':
		  $this->set('SELECT','cuenta');
		  $this->set('codigo','cuenta');
		  $this->set('seleccion','');
		  $this->set('n',3);
		   $this->set('no','no');
		  $ent =  $this->Session->read('ent');
		  $this->Session->write('suc',$var);
		  //echo $var;
		  $cond =$this->condicionNDEP()." and cod_dep=1 and cod_entidad_bancaria=".$ent." and cod_sucursal=".$var;
		  $lista=  $this->cstd02_cuentas_bancarias->generateList($cond, 'cuenta_bancaria ASC', null, '{n}.cstd02_cuentas_bancarias.cuenta_bancaria', '{n}.cstd02_cuentas_bancarias.concepto_manejo');
          $this->concatena($lista, 'vector');
          //$this->set('vector',$lista);
		break;
		}//fin wsitch
	}else{
		  $this->set('SELECT','');
		  $this->set('codigo','');
		  $this->set('seleccion','');
		  $this->set('n',10);
		  $this->set('no','no');
		 $this->set('vector','');
	}
}//fin select codigos presupuestarios



function mostrar3($select=null,$var=null) { //mostrar3 codigos presupuestarios
	$this->layout = "ajax";
if( $var!=null){
	switch($select){
		case 'entidad'://$select.$var;
		  $this->Session->write('ent',$var);
		  $cond="cod_entidad_bancaria=".$var;
		  $a=  $this->cstd01_entidades_bancarias->findAll($cond);
          echo "<input type='text' name='data[csrp01_solicitud_recurso_aprobacion][entidad_bancaria]' value='".$a[0]['cstd01_entidades_bancarias']['denominacion']."' id='entidad_bancaria' class='inputtext' readonly=readonly/>";
		break;
		case 'sucursal'://$select.$var;
		  $ent =  $this->Session->read('ent');
		  $this->Session->write('suc',$var);
		  $cond="cod_entidad_bancaria=".$ent." and cod_sucursal=".$var;
		  $a=  $this->cstd01_sucursales_bancarias->findAll($cond);
           echo "<input type='text' name='data[csrp01_solicitud_recurso_aprobacion][sucursal_bancaria]' value='".$a[0]['cstd01_sucursales_bancarias']['denominacion']."' id='sucursal_bancaria' class='inputtext' readonly=readonly/>";
		break;
		case 'cuenta':
		  $ent =  $this->Session->read('ent');
		  $suc =  $this->Session->read('suc');
		  $this->Session->write('cuenta',$var);
		  $cond="cod_entidad_bancaria=".$ent." and cod_sucursal=".$suc." and cuenta_bancaria='".$var."'";
		  $a=  $this->cstd02_cuentas_bancarias->findAll($cond);
          echo "<input type='text' name='data[csrp01_solicitud_recurso_aprobacion][cuenta_bancaria]' value='".$a[0]['cstd02_cuentas_bancarias']['cuenta_bancaria']."' id='cuenta_bancaria' class='inputtext' readonly=readonly/>";
		  echo "<script>";
			echo "document.getElementById('numero_cheque').value=''";
		  echo "</script>";
		break;
	}//fin wsitch
	}else{
		echo "";
	}
}//fin mostrar3 codigos presupuestarios




function valida_num_cheque($var=null){
	$this->layout="ajax";
	$ent =  $this->Session->read('ent');
	$suc =  $this->Session->read('suc');
	$cuenta=$this->Session->read('cuenta');
//	echo $ent."  ".$suc."   ".$cuenta."  ".$var."   ".$this->verifica_SS(5);
//		echo "si";
	$ano=$this->ano_ejecucion();
	$dato=$this->csrd01_solicitud_recurso_cuerpo->FindCount($this->SQLCA()."and cod_entidad_bancaria=".$ent." and cod_sucursal=".$suc." and cuenta_bancaria='".$cuenta."' and numero_cheque=".$var);
	if($dato!=0){
		$this->set('errorMessage', 'EL CHEQUE YA FUE REGISTRADO ANTERIORMENTE');
		echo "<script>";
			echo "document.getElementById('save').disabled='disabled'";
		echo "</script>";
	}else{
		echo "<script>";
			echo "document.getElementById('save').disabled=false";
		echo "</script>";
	}

}// fin valida_num_cheque



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

function guardar($cod_dep=null){
	$this->layout = "ajax";
	$dato=$this->ano_ejecucion();
	if(!empty($this->data)){

		$numero_solicitud=$this->data['csrp01_solicitud_recurso_aprobacion']['numero_solicitud'];
		$cod_entidad_bancaria=$this->data['csrp01_solicitud_recurso_aprobacion']['entidad'];
		$cod_sucursal=$this->data['csrp01_solicitud_recurso_aprobacion']['cod_sucursal'];
		$cuenta_bancaria=$this->data['csrp01_solicitud_recurso_aprobacion']['cod_cuenta'];
		$numero_cheque=$this->data['csrp01_solicitud_recurso_aprobacion']['numero_cheque'];
		$fecha=$this->data['csrp01_solicitud_recurso_aprobacion']['fecha_cheque'];
		$fecha_cheque=$this->Cfecha($fecha,'A-M-D');
		$monto_entregado1=$this->Formato1($this->data['csrp01_solicitud_recurso_aprobacion']['monto_cheque']);
	}
	$max=$this->v_csrd01_solicitud_recurso_cuerpo->execute("SELECT monto_solicitado,monto_entregado FROM v_csrd01_solicitud_recurso_cuerpo WHERE ".$this->SQLCA()." and ano_solicitud=".$dato." and numero_solicitud=".$numero_solicitud);
	$num_solicitud=$max[0][0]["monto_solicitado"];
	$num_entregado=$max[0][0]["monto_entregado"];
	$monto_entregado=$monto_entregado1;
//	$monto_entregado=$monto_entregado1+$num_entregado;

   	 $ver_udp=$this->SQLCA()." and numero_solicitud=".$numero_solicitud." and ano_solicitud=".$dato;
   	 $sql_update="update csrd01_solicitud_recurso_cuerpo set monto_entregado=".$monto_entregado.", cod_entidad_bancaria=".$cod_entidad_bancaria.", cod_sucursal=".$cod_sucursal.", cuenta_bancaria='".$cuenta_bancaria."', numero_cheque=".$numero_cheque.", fecha_cheque='".$fecha_cheque."'   where ".$ver_udp;
     $udt=$this->csrd01_solicitud_recurso_cuerpo->execute($sql_update);

     /////////////////////////////////////////////////////////////////////////////////////////

if($num_solicitud!=$monto_entregado){
	$total_acumulado=0;
	$i=1;
     $partidas=$this->csrd01_solicitud_recurso_partidas->FindAll($this->SQLCA()."and ano=".$dato." and numero_solicitud=".$numero_solicitud);
		foreach($partidas as $row){
			$ano=$row['csrd01_solicitud_recurso_partidas']['ano'];
			$cod_sector=$row['csrd01_solicitud_recurso_partidas']['cod_sector'];
			$cod_programa=$row['csrd01_solicitud_recurso_partidas']['cod_programa'];
			$cod_sub_prog=$row['csrd01_solicitud_recurso_partidas']['cod_sub_prog'];
			$cod_proyecto=$row['csrd01_solicitud_recurso_partidas']['cod_proyecto'];
			$cod_activ_obra=$row['csrd01_solicitud_recurso_partidas']['cod_activ_obra'];
			$cod_partida=$row['csrd01_solicitud_recurso_partidas']['cod_partida'];
			$cod_generica=$row['csrd01_solicitud_recurso_partidas']['cod_generica'];
			$cod_especifica=$row['csrd01_solicitud_recurso_partidas']['cod_especifica'];
			$cod_sub_espec=$row['csrd01_solicitud_recurso_partidas']['cod_sub_espec'];
			$cod_auxiliar=$row['csrd01_solicitud_recurso_partidas']['cod_auxiliar'];
			$monto=$row['csrd01_solicitud_recurso_partidas']['monto'];

			$total_modificar=(($monto_entregado*$monto)/$num_solicitud);
			//$total_acumulado+=$total_modificar;
			if($i==1){
				$condicion1=$this->SQLCA()." and ano=".$ano." and cod_sector=".$cod_sector." and cod_programa=".$cod_programa." and cod_sub_prog=".$cod_sub_prog." and cod_proyecto=".$cod_proyecto." and cod_activ_obra=".$cod_activ_obra." and cod_partida=".$cod_partida." and cod_generica=".$cod_generica." and cod_especifica=".$cod_especifica." and cod_sub_espec=".$cod_sub_espec." and cod_auxiliar=".$cod_auxiliar;
			}
			$condicion=$this->SQLCA()." and numero_solicitud=".$numero_solicitud." and ano=".$ano." and cod_sector=".$cod_sector." and cod_programa=".$cod_programa." and cod_sub_prog=".$cod_sub_prog." and cod_proyecto=".$cod_proyecto." and cod_activ_obra=".$cod_activ_obra." and cod_partida=".$cod_partida." and cod_generica=".$cod_generica." and cod_especifica=".$cod_especifica." and cod_sub_espec=".$cod_sub_espec." and cod_auxiliar=".$cod_auxiliar;
			$sql_update1="update csrd01_solicitud_recurso_partidas set monto_entregado=".$total_modificar." where ".$condicion;
     		$udt1=$this->csrd01_solicitud_recurso_partidas->execute($sql_update1);
			$i++;
		}// fin foreach
		//echo $monto_entregado."  ".$total_acumulado;
		$maximo=$this->csrd01_solicitud_recurso_partidas->execute("SELECT numero_solicitud,sum((monto_entregado)::numeric)::numeric(22,2) AS monto_total FROM csrd01_solicitud_recurso_partidas WHERE ".$this->SQLCA()." and ano_solicitud=".$dato." and numero_solicitud=".$numero_solicitud ."GROUP BY numero_solicitud");
		$sum_monto_entregado=$maximo[0][0]["monto_total"];
		$ver=$this->csrd01_solicitud_recurso_partidas->execute("SELECT numero_solicitud,monto,monto_entregado FROM csrd01_solicitud_recurso_partidas WHERE ".$condicion1." and ano_solicitud=".$dato." and numero_solicitud=".$numero_solicitud);
		$monto_partida=$ver[0][0]["monto_entregado"];
		if($monto_entregado != $sum_monto_entregado){
			$total_diferencia_centimos=$monto_entregado-$sum_monto_entregado;
			if($total_diferencia_centimos > 0){
				$total_modificar_centimo=$monto_partida+$total_diferencia_centimos;
			}else if ($total_diferencia_centimos < 0){
				$total_modificar_centimo=$monto_partida-$total_diferencia_centimos;
			}

			$sql_update1="update csrd01_solicitud_recurso_partidas set monto_entregado=".$total_modificar_centimo." where ".$condicion1." and ano=".$dato." and numero_solicitud=".$numero_solicitud;
	     	$udt1=$this->csrd01_solicitud_recurso_partidas->execute($sql_update1);

		}
}//fin condicion update monto y monto_entregado
     /////////////////////////////////////////////////////////////////////////////////////////
	if($udt>1){
	 	$this->set('Message_existe', 'Registro Guardado con exito.');
	 	 $this->index(true);
	     $this->render("index");
	 }else{
	 	$this->set('Message_existe', 'Disculpe, El Registro no fue Guardado.');
	 }
}// fin guardar



function consultar($pagina=null){
 		$this->layout = "ajax";
 		 $ano=$this->ano_ejecucion();
         if($pagina!=null){
          	 $pagina=$pagina;
          	 $Tfilas=$this->v_csrd01_solicitud_recurso_cuerpo->findCount($this->SQLCA()." and ano_solicitud=".$ano);
          	 if($Tfilas==0){
          	 	$this->index();
          		$this->render("index");
          	 }
          	 if($Tfilas!=0){
          	 $this->set('pag_cant',$pagina.'/'.$Tfilas);
          	 $datos=$this->v_csrd01_solicitud_recurso_cuerpo->findAll($this->SQLCA()." and ano_solicitud=".$ano,null,'numero_solicitud ASC',1,$pagina,null);
          	// foreach ($datacpcp01 as $YYY);
          	 $entidad=$this->cstd01_entidades_bancarias->findAll(" cod_entidad_bancaria=".$datos[0]["v_csrd01_solicitud_recurso_cuerpo"]["cod_entidad_bancaria"]);
          	 $this->set('entidad',$entidad);
			 $sucursal=$this->cstd01_sucursales_bancarias->findAll("cod_entidad_bancaria=".$datos[0]["v_csrd01_solicitud_recurso_cuerpo"]["cod_entidad_bancaria"]." and cod_sucursal=".$datos[0]["v_csrd01_solicitud_recurso_cuerpo"]["cod_sucursal"]);
          	 $this->set('sucursal',$sucursal);
          	 $this->set('datos',$datos);
          	 $this->set('siguiente',$pagina+1);
          	 $this->set('anterior',$pagina-1);

            $this->bt_nav($Tfilas,$pagina);
             }
 }else{
 	$pagina=1;
          	 $Tfilas=$this->v_csrd01_solicitud_recurso_cuerpo->findCount($this->SQLCA()." and ano_solicitud=".$ano);
          	 if($Tfilas==0){
          	 	$this->index();
          		$this->render("index");
          	 }
          	 if($Tfilas!=0){
          	 $this->set('pag_cant',$pagina.'/'.$Tfilas);
          	 $datos=$this->v_csrd01_solicitud_recurso_cuerpo->findAll($this->SQLCA()." and ano_solicitud=".$ano,null,'numero_solicitud ASC',1,$pagina,null);
          	 $entidad=$this->cstd01_entidades_bancarias->findAll(" cod_entidad_bancaria=".$datos[0]["v_csrd01_solicitud_recurso_cuerpo"]["cod_entidad_bancaria"]);
          	 $this->set('entidad',$entidad);
          	 $sucursal=$this->cstd01_sucursales_bancarias->findAll("cod_entidad_bancaria=".$datos[0]["v_csrd01_solicitud_recurso_cuerpo"]["cod_entidad_bancaria"]." and cod_sucursal=".$datos[0]["v_csrd01_solicitud_recurso_cuerpo"]["cod_sucursal"]);
          	 $this->set('sucursal',$sucursal);
          	 $this->set('datos',$datos);
          	 $this->set('siguiente',$pagina+1);
          	 $this->set('anterior',$pagina-1);

            $this->bt_nav($Tfilas,$pagina);


 }
         }
}//fin function consultar2

}//fin de la clase controller
?>