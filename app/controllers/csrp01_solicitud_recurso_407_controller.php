<?php
class Csrp01SolicitudRecursoFiController extends AppController{


	//var $uses = array('cnmd03_transacciones','cnmd09_asignacion_calcula_asignacion','Cnmd01','ccfd03_instalacion','v_cnmd09_asignacion_calcula_asignacion_2','cnmd09_asignacion_calcula_asignacion_2');
 	var $uses = array('csrd01_oficios','cstd01_sucursales_bancarias','cstd01_entidades_bancarias','v_cfpd05_denominaciones','arrd05','csrd01_solicitud_recurso_cuerpo','v_csrd01_solicitud_recurso_cuerpo','csrd01_solicitud_recurso_numero','csrd01_solicitud_recurso_partidas','ccfd03_instalacion','cfpd05','v_solicitud_cfpd05_p2','cstd01_entidades_bancarias',
					'cstd01_sucursales_bancarias','cstd02_cuentas_bancarias','cstd03_cheque_cuerpo','v_solicitud_cfpd05_pp2','v_cfpd05_disponibilidad','ccfd04_cierre_mes','csrd01_tipo_solicitud','cugd05_restriccion_clave');
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






function SQLCX($ano=null){//sql para busqueda de codigos de arranque con y sin año
         $sql_re = "cod_presi=".$this->verifica_SS(1)."  and    ";
         $sql_re .= "cod_entidad=".$this->verifica_SS(2)."  and  ";
         $sql_re .= "cod_tipo_inst=".$this->verifica_SS(3)."  and ";
         $sql_re .= "cod_inst=".$this->verifica_SS(4)." ";
		 return $sql_re;


    }//fin funcion SQLX


function buscar_por_pista_year($var1=null){

 $this->layout = "ajax";

 $this->Session->write('year_buscar', $var1);

}//fin function







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

function index(){
	$this->layout = "ajax";
	$ano=$this->ano_ejecucion();
}


function index2($var=null){///////////////<<--INDEX
	  $this->layout = "ajax";
	  $de=$this->Session->read('SScoddep');
	  $ano=$this->ano_ejecucion();
	  $this->set('ano',$ano);
      $prov=$this->arrd05->findAll($this->SQLCX().'and cod_dep='.$de,array('denominacion','betar_ingresos'),null,1,1,null);
      //pr($prov);
      $this->set('deno_dep',$prov[0]["arrd05"]["denominacion"]);

	  $betar_ingreso = $prov[0]["arrd05"]["betar_ingresos"];
      if($betar_ingreso == 2){
      	$this->set('errorMessage', 'FAVOR PASAR POR LA SECRETAR&Iacute;A DE PLANIFICACI&Oacute;N Y PRESUPUESTO.');
		echo "<script>
				document.getElementById('bt_guardar').disabled = true;
			</script>";
      }

      $asignacion=$this->cfpd05->execute('select sum(asignacion_anual) as asignacion_anual from cfpd05 where '.$this->SQLCA($this->ano_ejecucion()));
      $this->set('asignacion_a',$asignacion[0][0]["asignacion_anual"]);
      $ingresos_saldos =$this->csrd01_solicitud_recurso_cuerpo->execute('select sum(monto) as ingresos_saldos  from cfpd10_reformulacion_texto where '.$this->SQLCA().' and cod_tipo=4 and ano_reformulacion='.$this->ano_ejecucion());
      $ingresos_propios=$this->csrd01_solicitud_recurso_cuerpo->execute('select sum(monto) as ingresos_propios from cfpd10_reformulacion_texto where '.$this->SQLCA().' and cod_tipo=5 and ano_reformulacion='.$this->ano_ejecucion());
      $aumentos=$this->cfpd05->execute('select sum(aumento_traslado_anual + credito_adicional_anual + nacionales_anual) as aumentos from cfpd05 where '.$this->SQLCA($this->ano_ejecucion()));
      $this->set('aumentos',$aumentos[0][0]["aumentos"]-($ingresos_saldos[0][0]["ingresos_saldos"] + $ingresos_propios[0][0]["ingresos_propios"]));
      $disminuciones=$this->cfpd05->execute('select sum(disminucion_traslado_anual + rebaja_anual) as disminuciones from cfpd05 where '.$this->SQLCA($this->ano_ejecucion()));
      $this->set('disminuciones',$disminuciones[0][0]["disminuciones"]);
      $precompromiso=$this->cfpd05->execute('select sum(precompromiso_congelado+precompromiso_requisicion+precompromiso_fondo_avance) as precompromiso from cfpd05 where '.$this->SQLCA($this->ano_ejecucion()));
      $this->set('precompromiso',$precompromiso[0][0]["precompromiso"]);
      $solicitado=$this->csrd01_solicitud_recurso_cuerpo->execute('select sum(monto_solicitado) as solicitado from csrd01_solicitud_recurso_cuerpo where '.$this->SQLCA().' and ano_solicitud='.$this->ano_ejecucion());
      $this->set('solicitado',$solicitado[0][0]["solicitado"]);
      $reintegro=$this->csrd01_solicitud_recurso_cuerpo->execute('select sum(monto_reintegro) as reintegro from csrd01_solicitud_recurso_cuerpo where '.$this->SQLCA().' and ano_solicitud='.$this->ano_ejecucion());
      $this->set('reintegro',$reintegro[0][0]["reintegro"]);
      $entregado=$this->csrd01_solicitud_recurso_cuerpo->execute('select sum(monto_entregado) as entregado from csrd01_solicitud_recurso_cuerpo where '.$this->SQLCA().' and ano_solicitud='.$this->ano_ejecucion());
      $this->set('entregado',$entregado[0][0]["entregado"]);
      $ajustada=($asignacion[0][0]["asignacion_anual"] + $aumentos[0][0]["aumentos"]) - ($ingresos_saldos[0][0]["ingresos_saldos"] + $ingresos_propios[0][0]["ingresos_propios"] + $disminuciones[0][0]["disminuciones"] + $precompromiso[0][0]["precompromiso"]);
	  $this->set('ajustada',$ajustada);



	  $su=$ajustada - $solicitado[0][0]["solicitado"];
	  $r= 13 - date('m');
	  $resul=$su / $r;
	  $resul = $resul + $reintegro[0][0]["reintegro"];
	  $this->set('resul',$resul);


      $meses= array('1'=>'Enero','2'=>'Febrero','3'=>'Marzo','4'=>'Abril','5'=>'Mayo','6'=>'Junio','7'=>'Julio','8'=>'Agosto','9'=>'Septiembre','10'=>'Octubre','11'=>'Noviembre','12'=>'Diciembre');
	  $this->concatena($meses, 'mes');
      $dato=$this->ano_ejecucion();
  	  $maxi=$this->csrd01_solicitud_recurso_numero->findCount($this->SQLCA());
      $max=$this->csrd01_solicitud_recurso_numero->execute("SELECT numero_solicitud FROM csrd01_solicitud_recurso_numero WHERE ".$this->SQLCA()." and ano_solicitud=".$dato." and situacion=1 ORDER BY numero_solicitud ASC LIMIT 1");
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

}//fin index

function meses($mes){
	$this->layout = "ajax";
	  $asignacion=$this->cfpd05->execute('select sum(asignacion_anual) as asignacion_anual from cfpd05 where '.$this->SQLCA($this->ano_ejecucion()));
	  $ingresos_saldos =$this->csrd01_solicitud_recurso_cuerpo->execute('select sum(monto) as ingresos_saldos  from cfpd10_reformulacion_texto where '.$this->SQLCA().' and cod_tipo=4 and ano_reformulacion='.$this->ano_ejecucion());
	  $ingresos_propios=$this->csrd01_solicitud_recurso_cuerpo->execute('select sum(monto) as ingresos_propios from cfpd10_reformulacion_texto where '.$this->SQLCA().' and cod_tipo=5 and ano_reformulacion='.$this->ano_ejecucion());
      $aumentos=$this->cfpd05->execute('select sum(aumento_traslado_anual + credito_adicional_anual) as aumentos from cfpd05 where '.$this->SQLCA($this->ano_ejecucion()));
      $disminuciones=$this->cfpd05->execute('select sum(disminucion_traslado_anual + rebaja_anual) as disminuciones from cfpd05 where '.$this->SQLCA($this->ano_ejecucion()));
      $precompromiso=$this->cfpd05->execute('select sum(precompromiso_congelado+precompromiso_requisicion+precompromiso_fondo_avance) as precompromiso from cfpd05 where '.$this->SQLCA($this->ano_ejecucion()));
      $solicitado=$this->csrd01_solicitud_recurso_cuerpo->execute('select sum(monto_solicitado) as solicitado from csrd01_solicitud_recurso_cuerpo where '.$this->SQLCA().' and ano_solicitud='.$this->ano_ejecucion());
      $entregado=$this->csrd01_solicitud_recurso_cuerpo->execute('select sum(monto_entregado) as entregado from csrd01_solicitud_recurso_cuerpo where '.$this->SQLCA().' and ano_solicitud='.$this->ano_ejecucion());
      $ajustada=($asignacion[0][0]["asignacion_anual"] + $aumentos[0][0]["aumentos"]) - ($ingresos_saldos[0][0]["ingresos_saldos"] + $ingresos_propios[0][0]["ingresos_propios"] + $disminuciones[0][0]["disminuciones"] + $precompromiso[0][0]["precompromiso"]);
	  $reintegro=$this->csrd01_solicitud_recurso_cuerpo->execute('select sum(monto_reintegro) as reintegro from csrd01_solicitud_recurso_cuerpo where '.$this->SQLCA().' and ano_solicitud='.$this->ano_ejecucion());
	  $su = $ajustada - $solicitado[0][0]["solicitado"];

	  $mes2 = 13 - $mes;
	  $resul = $su / $mes2;
	  $resul = $resul +  $reintegro[0][0]["reintegro"];
	  $this->set('resul',$resul);

		echo "<script>
				document.getElementById('monto_a_sol').value = '".$this->Formato2($resul)."';
			</script>";
}

function salir_solicitud($num_rc=null){
	$this->layout="ajax";
	$ano=$this->ano_ejecucion();
	$resultado=$this->csrd01_solicitud_recurso_numero->execute("UPDATE  csrd01_solicitud_recurso_numero SET situacion=1 WHERE ".$this->SQLCA()." and numero_solicitud=".$num_rc." and ano_solicitud=".$ano." and situacion=2");
	//$this->('index');
}

function quincena($var=null){
	$this->layout="ajax";

	if($var==1){//echo 'si';
		echo "<script>";
 		 		echo "document.getElementById('quincena_1').disabled='';  ";
 		 		echo "document.getElementById('quincena_2').disabled='';  ";
 		 		echo "document.getElementById('quincena_1').checked=true;  ";
 		 		echo "document.getElementById('quincena_2').checked=false;  ";
	echo "if(document.getElementById('monto_a_sol').value == document.getElementById('dispo_fecha').value){
			var monto_asolicitar=reemplazarPC(document.getElementById('monto_a_sol').value);
	  division = eval(monto_asolicitar)/eval(2);
      division = redondear(division, 2);
      total_solic=reemplazarPC(division);
      document.getElementById('monto_a_sol').value = total_solic;
	  moneda('monto_a_sol');}";
       	 echo "</script>";
	}else if($var==2){//echo 'no';
		echo "<script>";
 		 		echo "document.getElementById('quincena_1').disabled=true;  ";
 		 		echo "document.getElementById('quincena_2').disabled=true;  ";
 		 		echo "document.getElementById('quincena_1').checked=false;  ";
 		 		echo "document.getElementById('quincena_2').checked=false;  ";
       	 echo "</script>";
	}
}

function guardar(){
	$this->layout="ajax";

	$cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$ano=$this->data['csrp01_solicitud_recurso_fi']['ano'];
	$numero_solicitud  =  $this->data['csrp01_solicitud_recurso_fi']['numero_solicitud'];
	$fecha_solicitud   =  $this->data['csrp01_solicitud_recurso_fi']['fecha_solicitud'];
	$monto_a_sol       =  $this->Formato1($this->data['csrp01_solicitud_recurso_fi']['monto_a_sol']);
	$concepto		   =  $this->data['csrp01_solicitud_recurso_fi']['concepto'];
	$frecuencia        =  $this->data['csrp01_solicitud_recurso_fi']['frecuencia'];
	$mes               =  $this->data['csrp01_solicitud_recurso_fi']['mes'];
	if($frecuencia == 2){
		$quincena=0;
	}else{
		$quincena          =  $this->data['csrp01_solicitud_recurso_fi']['quincena'];
	}
	$asig_inicial      =  $this->Formato1($this->data['csrp01_solicitud_recurso_fi']['asig_inicial']);
	$aumentos          =  $this->Formato1($this->data['csrp01_solicitud_recurso_fi']['aumentos']);
	$disminuciones     =  $this->Formato1($this->data['csrp01_solicitud_recurso_fi']['disminuciones']);
	$asig_ajustada     =  $this->Formato1($this->data['csrp01_solicitud_recurso_fi']['asig_ajustada']);
	$monto_sol         =  $this->Formato1($this->data['csrp01_solicitud_recurso_fi']['monto_sol']);
	$monto_ent         =  $this->Formato1($this->data['csrp01_solicitud_recurso_fi']['monto_ent']);
	$dispo_anual       =  $this->Formato1($this->data['csrp01_solicitud_recurso_fi']['dispo_anual']);
	$dispo_fecha       =  $this->Formato1($this->data['csrp01_solicitud_recurso_fi']['dispo_fecha']);
	$reintegro_a       =  $this->Formato1($this->data['csrp01_solicitud_recurso_fi']['monto_rei']);

	$insert ="INSERT INTO csrd01_solicitud_recurso_cuerpo (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, ano_solicitud,
  			  numero_solicitud, fecha_solicitud, monto_solicitado, monto_entregado, cod_entidad_bancaria, cod_sucursal, cuenta_bancaria,
  			  numero_cheque, fecha_cheque, concepto, frecuencia_solicitud, tipo_solicitud_recurso, forma_solicitud, mes_solicitado,
  			  numero_quincena, asignacion_inicial, aumentos, disminuciones, asignacion_ajustada, monto_solicitado_acumulado,
              monto_entregado_acumulado, disponibilidad_anual, disponibilidad_fecha,monto_reintegro,monto_reintegro_acumulado,aprobacion_presupuesto)";
	$insert .=" VALUES ($cod_presi, $cod_entidad, $cod_tipo_inst,  $cod_inst, $cod_dep, $ano,
			  $numero_solicitud,'".$fecha_solicitud."',$monto_a_sol,0,0,0,0,
			  0,'1900-01-01','".$concepto."',$frecuencia,1,1,$mes,
			  $quincena,$asig_inicial,$aumentos,$disminuciones,$asig_ajustada,$monto_sol,
			  $monto_ent,$dispo_anual,$dispo_fecha,0,$reintegro_a,0)";
	$res=$this->csrd01_solicitud_recurso_cuerpo->execute($insert);
	if($res > 1){
		$this->csrd01_solicitud_recurso_numero->execute("UPDATE  csrd01_solicitud_recurso_numero SET situacion=3 WHERE ".$this->SQLCA()." and numero_solicitud=".$numero_solicitud." and ano_solicitud=".$ano." and situacion=2");
		$this->set('Message_existe', 'Registro Agregado con exito.');
	 	$this->index();
	 	$this->render("index");//echo "si entro";
  	}else if ($res <= 1){
  		$this->set('errorMessage', 'Disculpe, El Registro no fue creado.');
  		$this->index();
  		$this->render("index");//echo "no entro";
     }
}

function consulta($pagina=null){
 	  $this->layout = "ajax";
 	  $cod_presi = $this->Session->read('SScodpresi');
	  $cod_entidad = $this->Session->read('SScodentidad');
	  $cod_tipo_inst = $this->Session->read('SScodtipoinst');
	  $cod_inst = $this->Session->read('SScodinst');
	  $cod_dep = $this->Session->read('SScoddep');
	  $modulo = $this->Session->read('Modulo');
	  $prov=$this->arrd05->findAll($this->SQLCX().'and cod_dep='.$cod_dep,array('denominacion'),null,1,1,null);
      $this->set('deno_dep',$prov[0]["arrd05"]["denominacion"]);
      $meses= array('1'=>'Enero','2'=>'Febrero','3'=>'Marzo','4'=>'Abril','5'=>'Mayo','6'=>'Junio','7'=>'Julio','8'=>'Agosto','9'=>'Septiembre','10'=>'Octubre','11'=>'Noviembre','12'=>'Diciembre');
	  $this->concatena($meses, 'mes');
          $veri=$this->csrd01_solicitud_recurso_cuerpo->findCount($this->SQLCA().' and ano_solicitud='.$this->ano_ejecucion());
         if($veri!=0)
{

         if($pagina!=null){
          	 $pagina=$pagina;
          	 $this->set('pagina',$pagina);
          	  $Tfilas=$this->csrd01_solicitud_recurso_cuerpo->findCount($this->SQLCA().' and ano_solicitud='.$this->ano_ejecucion());
          	 if($Tfilas==0){
          	 	$this->index();
          		$this->render("index");
          	 }
          	 if($Tfilas!=0){
          	 $this->set('pag_cant',$pagina.'/'.$Tfilas);
          	 $datacpcp01=$this->csrd01_solicitud_recurso_cuerpo->findAll($this->SQLCA().' and ano_solicitud='.$this->ano_ejecucion(),null,'numero_solicitud ASC',1,$pagina,null);
          	 foreach($datacpcp01 as $row){
  				$ce=$row['csrd01_solicitud_recurso_cuerpo']['cod_entidad_bancaria'];
  				$cs=$row['csrd01_solicitud_recurso_cuerpo']['cod_sucursal'];
          	 }
          	 $p1=$this->cstd01_entidades_bancarias->findAll('cod_entidad_bancaria='.$ce,array('denominacion'),null,1,1,null);
      		 if($p1 != null){
      		 	$this->set('deno_entidad',$p1[0]["cstd01_entidades_bancarias"]["denominacion"]);
      		 }else{
      		 	$this->set('deno_entidad','');
      		 }
          	 $p2=$this->cstd01_sucursales_bancarias->findAll('cod_entidad_bancaria='.$ce.' and cod_sucursal='.$cs,array('denominacion'),null,1,1,null);
      		 if($p1 != null){
      		 	$this->set('deno_sucursal',$p2[0]["cstd01_sucursales_bancarias"]["denominacion"]);
      		 }else{
      		 	$this->set('deno_sucursal','');
      		 }
          	 $this->set('datos',$datacpcp01);
          	 $this->set('siguiente',$pagina+1);
          	 $this->set('anterior',$pagina-1);
             $this->bt_nav($Tfilas,$pagina);
             }
 }else{
 	$pagina=1;
 	$this->set('pagina',$pagina);
          	 $Tfilas=$this->csrd01_solicitud_recurso_cuerpo->findCount($this->SQLCA().' and ano_solicitud='.$this->ano_ejecucion());
          	 if($Tfilas==0){
          	 	$this->index();
          		$this->render("index");
          	 }
          	 if($Tfilas!=0){
          	 $this->set('pag_cant',$pagina.'/'.$Tfilas);
          	 $datacpcp01=$this->csrd01_solicitud_recurso_cuerpo->findAll($this->SQLCA().' and ano_solicitud='.$this->ano_ejecucion(),null,'numero_solicitud ASC',1,$pagina,null);
          	 foreach($datacpcp01 as $row){
  				$ce=$row['csrd01_solicitud_recurso_cuerpo']['cod_entidad_bancaria'];
  				$cs=$row['csrd01_solicitud_recurso_cuerpo']['cod_sucursal'];
          	 }
          	 $p1=$this->cstd01_entidades_bancarias->findAll('cod_entidad_bancaria='.$ce,array('denominacion'),null,1,1,null);
      		 if($p1 != null){
      		 	$this->set('deno_entidad',$p1[0]["cstd01_entidades_bancarias"]["denominacion"]);
      		 }else{
      		 	$this->set('deno_entidad','');
      		 }
          	 $p2=$this->cstd01_sucursales_bancarias->findAll('cod_entidad_bancaria='.$ce.' and cod_sucursal='.$cs,array('denominacion'),null,1,1,null);
      		 if($p1 != null){
      		 	$this->set('deno_sucursal',$p2[0]["cstd01_sucursales_bancarias"]["denominacion"]);
      		 }else{
      		 	$this->set('deno_sucursal','');
      		 }
          	 $this->set('datos',$datacpcp01);
          	 $this->set('siguiente',$pagina+1);
          	 $this->set('anterior',$pagina-1);
             $this->bt_nav($Tfilas,$pagina);
			 }
        }
        }else{
	$this->set('errorMessage', 'No se encontrar&oacute;n datos');
	$this->index();
    $this->render("index");
}
}//fin function consultar2


function modificar($numero_solicitud=null,$pagina=null){
 	  $this->layout = "ajax";//echo $numero_solicitud;
 	  $cod_presi = $this->Session->read('SScodpresi');
	  $cod_entidad = $this->Session->read('SScodentidad');
	  $cod_tipo_inst = $this->Session->read('SScodtipoinst');
	  $cod_inst = $this->Session->read('SScodinst');
	  $cod_dep = $this->Session->read('SScoddep');
	  $modulo = $this->Session->read('Modulo');
	  $prov=$this->arrd05->findAll($this->SQLCX().'and cod_dep='.$cod_dep,array('denominacion'),null,1,1,null);
      $this->set('deno_dep',$prov[0]["arrd05"]["denominacion"]);
      $meses= array('1'=>'Enero','2'=>'Febrero','3'=>'Marzo','4'=>'Abril','5'=>'Mayo','6'=>'Junio','7'=>'Julio','8'=>'Agosto','9'=>'Septiembre','10'=>'Octubre','11'=>'Noviembre','12'=>'Diciembre');
	  $this->concatena($meses, 'mes');
          	 $this->set('pagina',$pagina);
          	 $datacpcp01=$this->csrd01_solicitud_recurso_cuerpo->findAll($this->SQLCA().' and ano_solicitud='.$this->ano_ejecucion().' and numero_solicitud='.$numero_solicitud);
          	 foreach($datacpcp01 as $row){
  				$ce=$row['csrd01_solicitud_recurso_cuerpo']['cod_entidad_bancaria'];
  				$cs=$row['csrd01_solicitud_recurso_cuerpo']['cod_sucursal'];
          	 }
          	 $p1=$this->cstd01_entidades_bancarias->findAll('cod_entidad_bancaria='.$ce,array('denominacion'));
      		 if($p1 != null){
      		 	$this->set('deno_entidad',$p1[0]["cstd01_entidades_bancarias"]["denominacion"]);
      		 }else{
      		 	$this->set('deno_entidad','');
      		 }
          	 $p2=$this->cstd01_sucursales_bancarias->findAll('cod_entidad_bancaria='.$ce.' and cod_sucursal='.$cs,array('denominacion'));
      		 if($p1 != null){
      		 	$this->set('deno_sucursal',$p2[0]["cstd01_sucursales_bancarias"]["denominacion"]);
      		 }else{
      		 	$this->set('deno_sucursal','');
      		 }
          	 $this->set('datos',$datacpcp01);
}//fin function consultar2


function guardar_modificar($numero_solicitud=null,$pagina=null){
	$this->layout = "ajax";
	$cond='numero_solicitud='.$numero_solicitud.' and '.$this->SQLCA().' and ano_solicitud='.$this->ano_ejecucion();
	$monto_a_sol       =  $this->Formato1($this->data['csrp01_solicitud_recurso_fi']['monto_a_sol']);
	$concepto		   =  $this->data['csrp01_solicitud_recurso_fi']['concepto'];
 	$sql="update csrd01_solicitud_recurso_cuerpo set monto_solicitado=".$monto_a_sol.", concepto='".$concepto."' where ".$cond;
    $verificar=$this->csrd01_solicitud_recurso_cuerpo->execute($sql);
	$this->set('Message_existe', 'Registro Modificado con exito.');
	$this->consulta($pagina);
    $this->render("consulta");
}


 function eliminar($numero_solicitud=null,$pagina=null){
 	$this->layout = "ajax";
 	$cond=$this->SQLCA().' and numero_solicitud='.$numero_solicitud.' and ano_solicitud='.$this->ano_ejecucion();
 	$this->csrd01_solicitud_recurso_cuerpo->execute("DELETE FROM csrd01_solicitud_recurso_cuerpo  WHERE ".$cond);
 	$this->csrd01_solicitud_recurso_numero->execute("UPDATE  csrd01_solicitud_recurso_numero SET situacion=4 WHERE ".$this->SQLCA()." and numero_solicitud=".$numero_solicitud." and ano_solicitud=".$this->ano_ejecucion()." and situacion=3");//pendiente en la condicion el año "mosca"
 	$y=$this->csrd01_solicitud_recurso_cuerpo->findCount($this->SQLCA().' and ano_solicitud='.$this->ano_ejecucion());
 	if($pagina>$y){
 		$pagina=$pagina-1;
 	}
	if($y!=0){
		$this->set('Message_existe', 'Registro Eliminado con exito.');
      	$this->consulta($pagina);//si es el primero solamente
      	$this->render("consulta");
	}else if($y==0){
		$this->set('Message_existe', 'Registro Eliminado con exito.');
		$this->index();
    	$this->render("index");
		}//fin if
 }

function buscar($var1=null){
	$this->layout="ajax";
	$this->set("opcion",$var1);
	$this->set("year",$this->ano_ejecucion());
	$this->Session->write('year_buscar', $this->ano_ejecucion());
	$this->Session->delete('pista');
}//fin function

function buscar_por_pista($var1=null, $var2=null, $var3=null){
$this->layout="ajax";

 $year = $this->Session->read('year_buscar');

    if($var3==null){//$var2 = strtoupper($var2);echo 'hola3';
					$this->Session->write('pista', $var2);

					$Tfilas=$this->csrd01_solicitud_recurso_cuerpo->findCount($this->SQLCA()." and ano_solicitud=".$year."  and ( (numero_solicitud::text LIKE '%$var2%')  or  (upper(concepto::text) LIKE upper('%$var2%')) ) ");
					        if($Tfilas!=0){
					        	$pagina=1;
					        	$Tfilas=(int)ceil($Tfilas/100);
					        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
								$this->set('total_paginas',$Tfilas);
								$this->set('pagina_actual',$pagina);
								$this->set('ultimo',$Tfilas);
					     	    $datos_filas=$this->csrd01_solicitud_recurso_cuerpo->findAll($this->SQLCA()." and ano_solicitud=".$year." and ((numero_solicitud::text LIKE '%$var2%')  or  (upper(concepto::text) LIKE upper('%$var2%')))",null,"numero_solicitud ASC",100,1,null);
						        $this->set("datosFILAS",$datos_filas);
						        $this->set('siguiente',$pagina+1);
								$this->set('anterior',$pagina-1);
								$this->bt_nav($Tfilas,$pagina);
					          }else{
					        	$this->set("datosFILAS",'');
					          }
            }else{
						$var22 = $this->Session->read('pista');
						$Tfilas=$this->csrd01_solicitud_recurso_cuerpo->findCount($this->SQLCA()." and ano_solicitud=".$year." and  ((numero_solicitud::text LIKE '%$var22%')  or  (upper(concepto) LIKE upper('%$var22%')))");
						        if($Tfilas!=0){echo 'hola';
						        	$pagina=$var3;
						        	$Tfilas=(int)ceil($Tfilas/100);
						        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
									$this->set('total_paginas',$Tfilas);
									$this->set('pagina_actual',$pagina);
									$this->set('ultimo',$Tfilas);
						     	    $datos_filas=$this->csrd01_solicitud_recurso_cuerpo->findAll($this->SQLCA()." and ano_solicitud=".$year." and  ((numero_solicitud::text LIKE '%$var22%')  or  (upper(concepto) LIKE upper('%$var22%'))",null,"numero_solicitud ASC",100,$pagina,null);
							        $this->set("datosFILAS",$datos_filas);
							        $this->set('siguiente',$pagina+1);
									$this->set('anterior',$pagina-1);
									$this->bt_nav($Tfilas,$pagina);
						          }else{
						        	$this->set("datosFILAS",'');
						          }
                 }//fin else
$this->set("opcion",$var1);
}//fin function

function consulta2($numero_solicitud=null){
	$this->layout = "ajax";
	$cod_dep = $this->Session->read('SScoddep');
    $datacpcp01=$this->csrd01_solicitud_recurso_cuerpo->findAll($this->SQLCA().' and numero_solicitud='.$numero_solicitud.' and ano_solicitud='.$this->ano_ejecucion());
    $prov=$this->arrd05->findAll($this->SQLCX().'and cod_dep='.$cod_dep,array('denominacion'),null,1,1,null);
    $this->set('deno_dep',$prov[0]["arrd05"]["denominacion"]);
    $meses= array('1'=>'Enero','2'=>'Febrero','3'=>'Marzo','4'=>'Abril','5'=>'Mayo','6'=>'Junio','7'=>'Julio','8'=>'Agosto','9'=>'Septiembre','10'=>'Octubre','11'=>'Noviembre','12'=>'Diciembre');
	$this->concatena($meses, 'mes');
          	 foreach($datacpcp01 as $row){
  				$ce=$row['csrd01_solicitud_recurso_cuerpo']['cod_entidad_bancaria'];
  				$cs=$row['csrd01_solicitud_recurso_cuerpo']['cod_sucursal'];
          	 }
          	 $p1=$this->cstd01_entidades_bancarias->findAll('cod_entidad_bancaria='.$ce,array('denominacion'),null,1,1,null);
      		 if($p1 != null){
      		 	$this->set('deno_entidad',$p1[0]["cstd01_entidades_bancarias"]["denominacion"]);
      		 }else{
      		 	$this->set('deno_entidad','');
      		 }
          	 $p2=$this->cstd01_sucursales_bancarias->findAll('cod_entidad_bancaria='.$ce.' and cod_sucursal='.$cs,array('denominacion'),null,1,1,null);
      		 if($p1 != null){
      		 	$this->set('deno_sucursal',$p2[0]["cstd01_sucursales_bancarias"]["denominacion"]);
      		 }else{
      		 	$this->set('deno_sucursal','');
      		 }
    $this->set('datos',$datacpcp01);
}//fin function consultar2


function solicitud($var=null){

$this->verifica_entrada('37');

	$this->layout="ajax";
	$cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep=".$cod_dep;

	if($var=='no'){
		$this->layout="ajax";
		$this->set('var', 'no');
	    $ano2 = $this->ano_ejecucion();

	    if(!empty($ano2)){
		$this->set('year',$ano2);
	    }else{
		$this->set('year','');
	    }
	    $select_solicitud=$this->csrd01_solicitud_recurso_cuerpo->generateList($this->SQLCA().' and ano_solicitud='.$this->ano_ejecucion(), 'numero_solicitud ASC', null, '{n}.csrd01_solicitud_recurso_cuerpo.numero_solicitud', '{n}.csrd01_solicitud_recurso_cuerpo.numero_solicitud');
	  //$select_solicitud=$this->csrd01_solicitud_recurso_cuerpo->generateList($this->SQLCA().' and aprobacion_presupuesto=1 and ano_solicitud='.$this->ano_ejecucion(), 'numero_solicitud ASC', null, '{n}.csrd01_solicitud_recurso_cuerpo.numero_solicitud', '{n}.csrd01_solicitud_recurso_cuerpo.numero_solicitud');
    	$this->set('select_solicitud', $select_solicitud);
    	$cont = $this->csrd01_oficios->findCount($this->SQLCA());
		if($cont == 0){
			$this->set('mensaje','Por favor, ingrese los nombres y cargos de los firmantes');
			$this->set('enviado_a','');
			$this->set('cargo_a','');
			$this->set('enviado_por','');
			$this->set('cargo_por','');
			$this->set('enviado_por2','');
			$this->set('cargo_por2','');
			$firma_existe = 'no';
		}else{
			$firmantes= $this->csrd01_oficios->findAll($this->SQLCA());
			$this->set('enviado_a',$firmantes[0]['csrd01_oficios']['enviado_a']);
			$this->set('cargo_a',$firmantes[0]['csrd01_oficios']['cargo_a']);
			$this->set('enviado_por',$firmantes[0]['csrd01_oficios']['enviado_por']);
			$this->set('cargo_por',$firmantes[0]['csrd01_oficios']['cargo_por']);
			$this->set('enviado_por2',$firmantes[0]['csrd01_oficios']['enviado_por_admin']);
			$this->set('cargo_por2',$firmantes[0]['csrd01_oficios']['cargo_por_admin']);
			$firma_existe = 'si';
		}
		$this->set('firma_existe',$firma_existe);



	}elseif($var=='si'){
		$this->layout = "pdf";
		if($this->data['csrp01_solicitud_recurso_fi']['ano']!=null && $this->data['csrp01_solicitud_recurso_fi']['cod_solicitud']!=null){
		$ano_solicitud=$this->data['csrp01_solicitud_recurso_fi']['ano'];
		$num_solicitud=$this->data['csrp01_solicitud_recurso_fi']['cod_solicitud'];
			////////////////////////////////////////////////////////////////////
				$a=$this->csrd01_solicitud_recurso_cuerpo->execute("select * from cugd90_municipio_defecto where ".$this->SQLCA());
				if($a!=null){
					$aa=$this->csrd01_solicitud_recurso_cuerpo->execute("select * from cugd01_municipios where cod_republica=".$a[0][0]['cod_republica']." and cod_estado=".$a[0][0]['cod_estado']." and cod_municipio=".$a[0][0]['cod_municipio']);
					$_SESSION['ciudad_d']=$aa[0][0]['conocido'].",";
				}else{
					$_SESSION['ciudad_d']='';
				}
		$cond=$this->SQLCA()." and ano_solicitud='$ano_solicitud' and numero_solicitud='$num_solicitud'";
		$this->set('numero_sol',$num_solicitud);
		$solicitud_cuerpo=$this->csrd01_solicitud_recurso_cuerpo->findAll($cond);
		$this->set('datos',$solicitud_cuerpo);
		$this->set('var', 'si');
		$prov=$this->arrd05->findAll($this->SQLCA_report($this->verifica_SS(5)),array('denominacion'),null,1,1,null);
    	$this->set('deno_dep',$prov[0]["arrd05"]["denominacion"]);
    	$firmantes= $this->csrd01_oficios->findAll($this->SQLCA());
			$this->set('enviado_a',$firmantes[0]['csrd01_oficios']['enviado_a']);
			$this->set('cargo_a',$firmantes[0]['csrd01_oficios']['cargo_a']);
			$this->set('enviado_por',$firmantes[0]['csrd01_oficios']['enviado_por']);
			$this->set('cargo_por',$firmantes[0]['csrd01_oficios']['cargo_por']);
			$this->set('enviado_por2',$firmantes[0]['csrd01_oficios']['enviado_por_admin']);
			$this->set('cargo_por2',$firmantes[0]['csrd01_oficios']['cargo_por_admin']);
			$m=$solicitud_cuerpo[0]['csrd01_solicitud_recurso_cuerpo']['mes_solicitado'];
			if($m==1){
				$me='ENERO';
			}
			if($m==2){
				$me='FEBRERO';
			}
			if($m==3){
				$me='MARZO';
			}
			if($m==4){
				$me='ABRIL';
			}
			if($m==5){
				$me='MAYO';
			}
			if($m==6){
				$me='JUNIO';
			}
			if($m==7){
				$me='JULIO';
			}
			if($m==8){
				$me='AGOSTO';
			}
			if($m==9){
				$me='SEPTIEMBRE';
			}
			if($m==10){
				$me='OCTUBRE';
			}
			if($m==11){
				$me='NOVIEMBRE';
			}
			if($m==12){
				$me='DICIEMBRE';
			}
			$this->set('me',$me);
	}
}//fin reporte_pago_transferencia
}

function guardar_firmas_solicitud(){
	$this->layout="ajax";
	$cp  = $this->Session->read('SScodpresi');
	$ce  = $this->Session->read('SScodentidad');
	$cti = $this->Session->read('SScodtipoinst');
	$ci  = $this->Session->read('SScodinst');
	$cd  = $this->Session->read('SScoddep');

	$enviado_a = $this->data['solicitud']['enviado_a'];
	$cargo_a  = $this->data['solicitud']['cargo_a'];
	$enviado_por = $this->data['solicitud']['enviado_por'];
	$cargo_por  = $this->data['solicitud']['cargo_por'];
	$enviado_por2 = $this->data['solicitud']['enviado_por2'];
	$cargo_por2  = $this->data['solicitud']['cargo_por2'];

	$insert = "INSERT INTO csrd01_oficios VALUES ($cp, $ce, $cti, $ci, $cd,'$enviado_a', '$cargo_a', '$enviado_por', '$cargo_por', '$enviado_por2', '$cargo_por2')";
	$this->csrd01_oficios->execute($insert);

	$this->set('mensaje','Datos registrados correctamente');
	$this->set('firma_existe','si');
	$firmantes= $this->csrd01_oficios->findAll($this->SQLCA());
			$this->set('enviado_a',$firmantes[0]['csrd01_oficios']['enviado_a']);
			$this->set('cargo_a',$firmantes[0]['csrd01_oficios']['cargo_a']);
			$this->set('enviado_por',$firmantes[0]['csrd01_oficios']['enviado_por']);
			$this->set('cargo_por',$firmantes[0]['csrd01_oficios']['cargo_por']);
			$this->set('enviado_por2',$firmantes[0]['csrd01_oficios']['enviado_por_admin']);
			$this->set('cargo_por2',$firmantes[0]['csrd01_oficios']['cargo_por_admin']);
}

function guardar_firmas_solicitud_m(){
	$this->layout="ajax";
	$cp  = $this->Session->read('SScodpresi');
	$ce  = $this->Session->read('SScodentidad');
	$cti = $this->Session->read('SScodtipoinst');
	$ci  = $this->Session->read('SScodinst');
	$cd  = $this->Session->read('SScoddep');

	$enviado_a = $this->data['solicitud']['enviado_a'];
	$cargo_a  = $this->data['solicitud']['cargo_a'];
	$enviado_por = $this->data['solicitud']['enviado_por'];
	$cargo_por  = $this->data['solicitud']['cargo_por'];
	$enviado_por2 = $this->data['solicitud']['enviado_por2'];
	$cargo_por2  = $this->data['solicitud']['cargo_por2'];

	$insert = "UPDATE csrd01_oficios SET enviado_a='".$enviado_a."', cargo_a='".$cargo_a."', enviado_por='".$enviado_por."', cargo_por='".$cargo_por."', enviado_por_admin='".$enviado_por2."', cargo_por_admin='".$cargo_por2."' where ".$this->SQLCA();
	$this->csrd01_oficios->execute($insert);


	$this->set('mensaje','Datos registrados correctamente');
	$this->set('firma_existe','si');
	$firmantes= $this->csrd01_oficios->findAll($this->SQLCA());
			$this->set('enviado_a',$firmantes[0]['csrd01_oficios']['enviado_a']);
			$this->set('cargo_a',$firmantes[0]['csrd01_oficios']['cargo_a']);
			$this->set('enviado_por',$firmantes[0]['csrd01_oficios']['enviado_por']);
			$this->set('cargo_por',$firmantes[0]['csrd01_oficios']['cargo_por']);
			$this->set('enviado_por2',$firmantes[0]['csrd01_oficios']['enviado_por_admin']);
			$this->set('cargo_por2',$firmantes[0]['csrd01_oficios']['cargo_por_admin']);
}


function modificar_firmas(){
	$this->layout="ajax";
	$firmantes= $this->csrd01_oficios->findAll($this->SQLCA());
			$this->set('enviado_a',$firmantes[0]['csrd01_oficios']['enviado_a']);
			$this->set('cargo_a',$firmantes[0]['csrd01_oficios']['cargo_a']);
			$this->set('enviado_por',$firmantes[0]['csrd01_oficios']['enviado_por']);
			$this->set('cargo_por',$firmantes[0]['csrd01_oficios']['cargo_por']);
			$this->set('enviado_por2',$firmantes[0]['csrd01_oficios']['enviado_por_admin']);
			$this->set('cargo_por2',$firmantes[0]['csrd01_oficios']['cargo_por_admin']);
}


function entrar(){
	$this->layout="ajax";
	if(isset($this->data['csrp01_solicitud_recurso_fi']['login']) && isset($this->data['csrp01_solicitud_recurso_fi']['password'])){
		$l="PROYECTO";
		$c="JJJSAE";
		$user=addslashes($this->data['csrp01_solicitud_recurso_fi']['login']);
		$paswd=addslashes($this->data['csrp01_solicitud_recurso_fi']['password']);
		$cond=$this->SQLCA()." and username='".$user."' and cod_tipo=37 and clave='".$paswd."'";
		if($user==$l && $paswd==$c){
			$this->set('autor_valido',true);
			//$this->index("autor_valido");
			//$this->render("index");
		}elseif($this->cugd05_restriccion_clave->findCount($cond)!=0){
			$this->set('autor_valido',true);
			//$this->index("autor_valido");
			//$this->render("index");
		}else{
			$this->set('mensajeError',"Lo siento, se necesita autorizaci&oacute;n para modificar estas firmas");
			$this->set('autor_valido',false);
			//$this->index("autor_valido");
			//$this->render("index");
		}
	}
}


function SQLCA_noDEP(){
  $cod_presi = $this->Session->read('SScodpresi');
  $cod_entidad = $this->Session->read('SScodentidad');
  $cod_tipo_inst = $this->Session->read('SScodtipoinst');
  $cod_inst = $this->Session->read('SScodinst');
  $condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst;

  return $condicion;
}//fin funcion SQLCA_noDEP

function solicitud_rfinanciero(){
	$this->layout="ajax";
	set_time_limit(0);
	ini_set("memory_limit","2000M");
  //$datos_srf = $this->v_csrd01_solicitud_recurso_cuerpo->findAll($this->SQLCA_noDEP().' and aprobacion_presupuesto=0', "cod_dep, denominacion, ano_solicitud, numero_solicitud, fecha_solicitud, concepto, mes, disponibilidad_anual, disponibilidad_fecha, monto_solicitado", "cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, ano_solicitud, numero_solicitud, fecha_solicitud ASC", null,null,null);
    $datos_srf = $this->v_csrd01_solicitud_recurso_cuerpo->findAll($this->SQLCA_noDEP(), "cod_dep, denominacion, ano_solicitud, numero_solicitud, fecha_solicitud, concepto, mes, disponibilidad_anual, disponibilidad_fecha, monto_solicitado", "cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, ano_solicitud, numero_solicitud, fecha_solicitud ASC", null,null,null);
	$this->set('datos_srf', $datos_srf);
}

function aprobar_recurso($id = null, $cod_dep = null, $ano = null, $numero = null){
	$this->layout="ajax";
	$sql = "UPDATE csrd01_solicitud_recurso_cuerpo SET aprobacion_presupuesto=1 WHERE " . $this->SQLCA_noDEP() . " and cod_dep=$cod_dep and ano_solicitud=$ano and numero_solicitud=$numero";
    $sws = $this->csrd01_solicitud_recurso_cuerpo->execute($sql);
    if($sws > 1){
    	$this->set('Message_existe', "El recurso financiero fue aprobado exitosamente.");
	echo "<script>
			document.getElementById('fila1_$id').innerHTML = '';
			document.getElementById('fila2_$id').innerHTML = '';
			document.getElementById('fila3_$id').innerHTML = '';
			document.getElementById('fila4_$id').innerHTML = '';
		</script>";
    }else{
    	$this->set('errorMessage', "No se pudo aprobar el recurso financiero...");
    }
}



function betar_rfinanciero(){
	$this->layout="ajax";
	set_time_limit(0);
	ini_set("memory_limit","2000M");
  //$datos_brf = $this->arrd05->findAll($this->SQLCA_noDEP()." and tipo_dependencia=2", "cod_dep, denominacion, betar_ingresos", "cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, denominacion ASC", null,null,null);
    $datos_brf = $this->arrd05->findAll($this->SQLCA_noDEP(),"cod_dep, denominacion, betar_ingresos", "cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, denominacion ASC", null,null,null);
	$this->set('datos_brf', $datos_brf);
}

function guardar_betar_rfi($cod_dep = null, $betar = null){
	$this->layout="ajax";

	if($cod_dep != null && $betar != null){

	if($betar==1){
		$f = 'betar1_'.$cod_dep;
		$d = 'betar2_'.$cod_dep;
		$msjb = 'han pasado a no betados';
	}else{
		$f = 'betar2_'.$cod_dep;
		$d = 'betar1_'.$cod_dep;
		$msjb = 'han sido betados';
	}
	$sql = "UPDATE arrd05 SET betar_ingresos=$betar WHERE " . $this->SQLCA_noDEP() . " and cod_dep=$cod_dep";
    $sws = $this->arrd05->execute($sql);
    if($sws > 1){
    	$this->set('Message_existe', "Los recursos de la dependencia $msjb exitosamente.");
	echo "<script>
			document.getElementById('$d').disabled=true;
			document.getElementById('$f').disabled=false;
			document.getElementById('$f').checked=true;
		</script>";

    }else{
    	$this->set('errorMessage', "No se pudo procesar - Intente nuevamente...");
    }
	}else{
    	$this->set('errorMessage', "No se puede procesar... error de parametros...");
    }
}

}
?>