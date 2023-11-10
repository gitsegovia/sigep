<?php
class Caop00VincularController extends AppController {

   var $name = "caop00_vincular";
   var $uses = array('ccfd03_instalacion', 'cscd04_ordencompra_encabezado', 'ccfd04_cierre_mes', 'cugd03_acta_anulacion_numero', 'cugd03_acta_anulacion_cuerpo', 'cscd04_ordcom_modificacion_cuerpo', 'cscd04_ordencompra_modificacion_partidas', 'cscd04_ordencompra_partidas', 'cpcd02', 'v_cfpd05_disponibilidad', 'cfpd21_numero_asiento_compromiso', 'cfpd21', 'cfpd05', 'cugd04'
                     ,'select_orden_compra','select_modificacion_compra', 'cfpd07_obras_partidas', 'cfpd07_obras_cuerpo', 'cfpd07_obras_modificacion_cuerpo', 'cfpd07_obras_modificacion_partidas',

                            'cstd01_entidades_bancarias', 'ccfd10_descripcion', 'ccfd10_detalles', 'ccfd02', 'ccfd05_numero_asiento', 'cscd02_solicitud_encabezado',
                            'ccfd04_cuentas_enlace', 'cpcd02', 'cepd01_compromiso_cuerpo', 'cscd04_ordencompra_anticipo_cuerpo', 'cepd01_compromiso_partidas',
                            'cscd04_ordencompra_encabezado', 'cscd04_ordencompra_autorizacion_cuerpo', 'cscd03_cotizacion_encabezado',
						    'cscd04_ordencompra_encabezado', 'cobd01_contratoobras_anticipo_cuerpo', 'cobd01_contratoobras_valuacion_cuerpo',
						    'cobd01_contratoobras_retencion_cuerpo', 'cepd02_contratoservicio_anticipo_cuerpo', 'cepd02_contratoservicio_valuacion_cuerpo',
						    'cepd02_contratoservicio_retencion_cuerpo','cobd01_contratoobras_cuerpo', 'cepd02_contratoservicio_cuerpo','cugd05_restriccion_clave'


                     );
   var $helpers = array('Html','Ajax','Javascript','Sisap');


function checkSession(){
				if (!$this->Session->check('Usuario')){
						$this->redirect('/salir/');
						exit();
				}else{
					$this->requestAction('/usuarios/actualizar_user');
				}
}//fin checksession


 function beforeFilter(){
    $this->checkSession();
 }//fin function



 function index(){
		 $this->layout = "ajax";
		 $ano = $this->ano_ejecucion();
		 $this->set('year', $ano);
		 $this->data = null;
 }//fin function
















function buscar_codigos_obras($var1=null, $var2=null, $var3=null){
$this->layout="ajax";
     //$this->Session->write('beneficiario_buscar',1);

}//fin function


function buscar_pista_obras($var1=null, $var2=null, $var3=null){
	$this->layout="ajax";

	$year_buscar         = $this->ano_ejecucion();
	$tabla = "cfpd07_obras_cuerpo";
	$campo = "cod_obra";

        $SScoddeporig             =       $this->Session->read('SScoddeporig');
	    $SScoddep                 =       $this->Session->read('SScoddep');
	    $Modulo                   =       $this->Session->read('Modulo');
		$condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$SScoddep.' and ano_estimacion='.$year_buscar;
	    //$lista_ob = $this->cfpd07_obras_cuerpo->generateList($condicion.' and ano_estimacion='.$year_buscar.'   and (estimado_presu - ((monto_contratado+ aumento_obras) - disminucion_obras)) != 0     ', ' cod_obra ASC', null, '{n}.cfpd07_obras_cuerpo.cod_obra', '{n}.cfpd07_obras_cuerpo.denominacion');
		//$this->set('lista_codigos_obras', $lista_ob);
		//$this->concatena_sin_cero($lista_ob, 'lista_codigos_obras');

            if($var1!=null && $var1!='_'){
            	$var1 = strtoupper_sisap($var1);
				$this->Session->write('pista', $var1);
            }else{
				$var1  = $this->Session->read('pista');
            }//fin else

            if($var2!=null){
               $pagina = $var2;
            }else{
            	$pagina = 1;
            }
            $this->set('pista', $var1);


                            $condicion = $condicion.' and ano_estimacion='.$year_buscar.'   and (estimado_presu - ((monto_contratado+ aumento_obras) - disminucion_obras)) != 0     ';
                            $ordena = "cod_obra";
                            $Tfilas=$this->$tabla->findCount($condicion." and ".$this->busca_separado(array("cod_obra","denominacion"), $var1));
					        if($Tfilas!=0){

					        	$Tfilas=(int)ceil($Tfilas/100);
					        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
								$this->set('total_paginas',$Tfilas);
								$this->set('pagina_actual',$pagina);
								$this->set('ultimo',$Tfilas);
					     	    $datos_filas=$this->$tabla->findAll($condicion." and ".$this->busca_separado(array("cod_obra","denominacion"), $var1),null,$ordena." ASC",100,$pagina,null);
						        $this->set("datosFILAS",$datos_filas);
						        $this->set('siguiente',$pagina+1);
								$this->set('anterior',$pagina-1);
								$this->bt_nav($Tfilas,$pagina);
					          }else{
					        	$this->set("datosFILAS",'');
					          }


$this->set("tabla", $tabla);
$this->set("campo", $campo);


}//fin function








function tipo_documento($var1=null){
   $this->layout="ajax";

  $cod_presi                =       $this->Session->read('SScodpresi');
  $cod_entidad              =       $this->Session->read('SScodentidad');
  $cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
  $cod_inst                 =       $this->Session->read('SScodinst');
  $cod_dep                  =       $this->Session->read('SScoddep');
  $condicion                =       'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$this->Session->read('SScoddep').'  ';
  $ano                      =       $this->ano_ejecucion();


  if($var1==1){
  	  $lista = $this->select_orden_compra->generateList($condicion." and ano_orden_compra = ".$ano." and condicion_actividad=1"." and (cod_obra='' or cod_obra='0' or cod_obra IS NULL)", ' numero_orden_compra ASC', null, '{n}.select_orden_compra.numero_orden_compra', '{n}.select_orden_compra.beneficiario');
      $this->set('lista', $lista);
  }else{
  	  $lista = $this->cepd01_compromiso_cuerpo->generateList($condicion." and ano_documento = ".$ano." and condicion_actividad=1 "." and (cod_obra='' or cod_obra='0' or cod_obra IS NULL)", ' numero_documento ASC', null, '{n}.cepd01_compromiso_cuerpo.numero_documento', '{n}.cepd01_compromiso_cuerpo.beneficiario');
      $this->concatena($lista, 'lista');
  }//fin function

  echo  "<script>$('deno_documento').value='';</script>";


  $this->set("opcion", $var1);


}//fin function



















function actualizar_vinculacion($var1=null){
  $this->layout="ajax";
  $cod_presi                =       $this->Session->read('SScodpresi');
  $cod_entidad              =       $this->Session->read('SScodentidad');
  $cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
  $cod_inst                 =       $this->Session->read('SScodinst');
  $cod_dep                  =       $this->Session->read('SScoddep');
  $ano                      =       $this->ano_ejecucion();


$lista_aux_2                 =  $this->cscd04_ordencompra_encabezado->findAll($this->condicion()." and ano_orden_compra = ".$ano." "." and (cod_obra!='' and cod_obra!='0' and cod_obra IS NOT NULL) ");
foreach($lista_aux_2 as $aux_2){
	  $this->Session->delete('codigos');

  $ano_obra                 =       $aux_2['cscd04_ordencompra_encabezado']['ano_orden_compra'];
  $numero_documento         =       $aux_2['cscd04_ordencompra_encabezado']['numero_orden_compra'];
  $codigo_obra              =       $aux_2['cscd04_ordencompra_encabezado']['cod_obra'];

           $lista                 =  $this->cscd04_ordencompra_encabezado->findAll($this->condicion()." and ano_orden_compra = ".$ano_obra." and numero_orden_compra='".$numero_documento."' "." ");
	  	   $cfpd07_obras_partidas =  $this->cfpd07_obras_partidas->findAll($this->condicion()."        and ano_estimacion   = ".$ano."      and upper(cod_obra)='".strtoupper($codigo_obra)."' ", null, 'cod_sector,cod_programa,cod_sub_prog,cod_proyecto,cod_activ_obra, cod_partida,cod_generica,cod_especifica,cod_sub_espec,cod_auxiliar ASC');
	  	   foreach($lista as $aux){
	  	   	    $contador_exitentes  = 0;
				$rif                 = $aux['cscd04_ordencompra_encabezado']['rif'];
				$ano_orden_compra    = $aux['cscd04_ordencompra_encabezado']['ano_orden_compra'];
				$numero_orden_compra = $aux['cscd04_ordencompra_encabezado']['numero_orden_compra'];
				$ano_cotizacion      = $aux['cscd04_ordencompra_encabezado']['ano_cotizacion'];
				$numero_cotizacion   = $aux['cscd04_ordencompra_encabezado']['numero_cotizacion'];
				$condicion_actividad   = $aux['cscd04_ordencompra_encabezado']['condicion_actividad'];
				$lista3              = $this->cscd03_cotizacion_encabezado->findAll($this->condicion()." and ano_cotizacion = ".$ano_cotizacion." and numero_cotizacion='".$numero_cotizacion."' and rif='".$rif."' ");
                $ano_solicitud       = $lista3[0]["cscd03_cotizacion_encabezado"]["ano_solicitud"];
                $numero_solicitud    = $lista3[0]["cscd03_cotizacion_encabezado"]["numero_solicitud"];
                $lista2              = $this->cscd04_ordencompra_partidas->findAll($this->condicion()." and ano_orden_compra = ".$ano_obra." and numero_orden_compra='".$numero_documento."' ");
				  	    foreach($lista2 as $aux2){
				  	    	          $cod_sector2          =   $aux2['cscd04_ordencompra_partidas']['cod_sector'];
									  $cod_programa2        =   $aux2['cscd04_ordencompra_partidas']['cod_programa'];
									  $cod_sub_prog2        =   $aux2['cscd04_ordencompra_partidas']['cod_sub_prog'];
									  $cod_proyecto2        =   $aux2['cscd04_ordencompra_partidas']['cod_proyecto'];
									  $cod_activ_obra2      =   $aux2['cscd04_ordencompra_partidas']['cod_activ_obra'];
									  $cod_partida2         =   $aux2['cscd04_ordencompra_partidas']['cod_partida'];
									  $cod_generica2        =   $aux2['cscd04_ordencompra_partidas']['cod_generica'];
									  $cod_especifica2      =   $aux2['cscd04_ordencompra_partidas']['cod_especifica'];
									  $cod_sub_espec2       =   $aux2['cscd04_ordencompra_partidas']['cod_sub_espec'];
									  $cod_auxiliar2        =   $aux2['cscd04_ordencompra_partidas']['cod_auxiliar'];
									  $marca = 0;
				  	    		foreach($cfpd07_obras_partidas  as  $aux_cfpd07_obras_partidas){
									  $cod_sector          =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_sector'];
									  $cod_programa        =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_programa'];
									  $cod_sub_prog        =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_sub_prog'];
									  $cod_proyecto        =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_proyecto'];
									  $cod_activ_obra      =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_activ_obra'];
									  $cod_partida         =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_partida'];
									  $cod_generica        =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_generica'];
									  $cod_especifica      =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_especifica'];
									  $cod_sub_espec       =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_sub_espec'];
									  $cod_auxiliar        =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_auxiliar'];
										  if($cod_sector     == $cod_sector2     &&
										     $cod_programa   == $cod_programa2   &&
										     $cod_sub_prog   == $cod_sub_prog2   &&
										     $cod_proyecto   == $cod_proyecto2   &&
										     $cod_activ_obra == $cod_activ_obra2 &&
										     $cod_partida    == $cod_partida2    &&
										     $cod_generica   == $cod_generica2   &&
										     $cod_especifica == $cod_especifica2 &&
										     $cod_sub_espec  == $cod_sub_espec2  &&
										     $cod_auxiliar   == $cod_auxiliar2){
										     	$marca = 1;
										  }else{

										  }
				  	    		 }//fin foreach
				  	    		 if($marca==0){$contador_exitentes++;
									$_SESSION["codigos"][$contador_exitentes][0]=$ano_obra;
									$_SESSION["codigos"][$contador_exitentes][1]=$cod_sector2;
									$_SESSION["codigos"][$contador_exitentes][2]=$cod_programa2;
									$_SESSION["codigos"][$contador_exitentes][3]=$cod_sub_prog2;
									$_SESSION["codigos"][$contador_exitentes][4]=$cod_proyecto2;
									$_SESSION["codigos"][$contador_exitentes][5]=$cod_activ_obra2;
									$_SESSION["codigos"][$contador_exitentes][6]=$cod_partida2;
									$_SESSION["codigos"][$contador_exitentes][7]=$cod_generica2;
									$_SESSION["codigos"][$contador_exitentes][8]=$cod_especifica2;
									$_SESSION["codigos"][$contador_exitentes][9]=$cod_sub_espec2;
									$_SESSION["codigos"][$contador_exitentes][10]=$cod_auxiliar2;
				  	    		 }
						}//fin foreach
						if($contador_exitentes==0){
                           $sw = $this->cscd02_solicitud_encabezado->execute("UPDATE cscd02_solicitud_encabezado   set cod_obra='".$codigo_obra."' where ".$this->condicion()." and ano_solicitud='".$ano_solicitud."'         and  numero_solicitud='".$numero_solicitud."'    ");
                           $sw = $this->cscd02_solicitud_encabezado->execute("UPDATE cscd03_cotizacion_encabezado  set cod_obra='".$codigo_obra."' where ".$this->condicion()." and ano_cotizacion='".$ano_cotizacion."'       and  numero_cotizacion='".$numero_cotizacion."' and rif='".$rif."'   ");
                           $sw = $this->cscd02_solicitud_encabezado->execute("UPDATE cscd04_ordencompra_encabezado set cod_obra='".$codigo_obra."' where ".$this->condicion()." and ano_orden_compra='".$ano_orden_compra."'   and  numero_orden_compra='".$numero_orden_compra."'    ");
						   foreach($lista2 as $aux2){
				  	    	          $cod_sector          =   $aux2['cscd04_ordencompra_partidas']['cod_sector'];
									  $cod_programa        =   $aux2['cscd04_ordencompra_partidas']['cod_programa'];
									  $cod_sub_prog        =   $aux2['cscd04_ordencompra_partidas']['cod_sub_prog'];
									  $cod_proyecto        =   $aux2['cscd04_ordencompra_partidas']['cod_proyecto'];
									  $cod_activ_obra      =   $aux2['cscd04_ordencompra_partidas']['cod_activ_obra'];
									  $cod_partida         =   $aux2['cscd04_ordencompra_partidas']['cod_partida'];
									  $cod_generica        =   $aux2['cscd04_ordencompra_partidas']['cod_generica'];
									  $cod_especifica      =   $aux2['cscd04_ordencompra_partidas']['cod_especifica'];
									  $cod_sub_espec       =   $aux2['cscd04_ordencompra_partidas']['cod_sub_espec'];
									  $cod_auxiliar        =   $aux2['cscd04_ordencompra_partidas']['cod_auxiliar'];
									  $monto               =   $aux2['cscd04_ordencompra_partidas']['monto'];
									  $cond2               =   $this->condicion()." and ano='".$ano_obra."' and cod_sector='$cod_sector' and cod_programa='$cod_programa' and cod_sub_prog='$cod_sub_prog' and cod_proyecto='$cod_proyecto' and cod_activ_obra='$cod_activ_obra' and cod_partida='$cod_partida' and cod_generica='$cod_generica' and cod_especifica='$cod_especifica' and cod_sub_espec='$cod_sub_espec' and cod_auxiliar='$cod_auxiliar'";
	                                  $sql55               =   "UPDATE cfpd05 SET precompromiso_obras = precompromiso_obras - ".$monto." WHERE ".$cond2." and precompromiso_obras!=0";
//		                              $sw155               =   $this->cscd04_ordencompra_partidas->execute($sql55);


		                              $sql_aux = "cod_presi=".$cod_presi." and cod_entidad=".$cod_entidad." and cod_tipo_inst=".$cod_tipo_inst." and  cod_inst=".$cod_inst." and cod_dep=".$cod_dep." and ano_estimacion=".$ano." and upper(cod_obra)='".strtoupper($codigo_obra)."' and cod_sector=".$cod_sector."   and   cod_programa=".$cod_programa."   and   cod_sub_prog=".$cod_sub_prog."   and   cod_proyecto=".$cod_proyecto."   and   cod_activ_obra=".$cod_activ_obra."   and   cod_partida=".$cod_partida."   and   cod_generica=".$cod_generica."   and   cod_especifica=".$cod_especifica."   and   cod_sub_espec=".$cod_sub_espec."   and   cod_auxiliar=".$cod_auxiliar.";";
						              $sw455 = $this->cscd04_ordencompra_partidas->execute("UPDATE cfpd07_obras_partidas SET monto_contratado=monto_contratado+".$monto." where ".$sql_aux);

							          $sql_aux = "cod_presi=".$cod_presi." and cod_entidad=".$cod_entidad." and cod_tipo_inst=".$cod_tipo_inst." and  cod_inst=".$cod_inst." and cod_dep=".$cod_dep." and ano_estimacion=".$ano." and upper(cod_obra)='".strtoupper($codigo_obra)."';";
						              $sw455   = $this->cscd04_ordencompra_partidas->execute("UPDATE cfpd07_obras_cuerpo SET monto_contratado=monto_contratado+".$monto." where ".$sql_aux);


						  }//fin foreach
						   $this->set('Message_existe', 'Los datos fueron  guardados');
						}else{
								$this->set('msg_error', 'los siguientes códigos presupuestarios no estan registrados en la relación de obras');
								$this->set('codigos', $_SESSION["codigos"]);
						}//fin if
			}//fin foreach
  }//fin foreach







$lista_aux_2                 =  $this->cepd01_compromiso_cuerpo->findAll($this->condicion()." and ano_documento = ".$ano." "." and (cod_obra!='' and cod_obra!='0' and cod_obra IS NOT NULL) ");
foreach($lista_aux_2 as $aux_2){
  $this->Session->delete('codigos');
  $ano_obra                 =       $aux_2['cepd01_compromiso_cuerpo']['ano_documento'];
  $numero_documento         =       $aux_2['cepd01_compromiso_cuerpo']['numero_documento'];
  $codigo_obra              =       $aux_2['cepd01_compromiso_cuerpo']['cod_obra'];

  	       $lista                 = $this->cepd01_compromiso_cuerpo->findAll($this->condicion()." and ano_documento = ".$ano_obra." and numero_documento='".$numero_documento."' "." ");
	  	   $cfpd07_obras_partidas =  $this->cfpd07_obras_partidas->findAll($this->condicion()."   and ano_estimacion   = ".$ano."      and upper(cod_obra)='".strtoupper($codigo_obra)."' ", null, 'cod_sector,cod_programa,cod_sub_prog,cod_proyecto,cod_activ_obra, cod_partida,cod_generica,cod_especifica,cod_sub_espec,cod_auxiliar ASC');
	  	   foreach($lista as $aux){
	  	   	    $contador_exitentes  =  0;
				$ano_documento       =  $aux['cepd01_compromiso_cuerpo']['ano_documento'];
				$numero_documento    =  $aux['cepd01_compromiso_cuerpo']['numero_documento'];
                $lista2              =  $this->cepd01_compromiso_partidas->findAll($this->condicion()." and ano_documento = ".$ano_obra." and numero_documento='".$numero_documento."' ");
				  	    foreach($lista2 as $aux2){
				  	    	          $cod_sector2          =   $aux2['cepd01_compromiso_partidas']['cod_sector'];
									  $cod_programa2        =   $aux2['cepd01_compromiso_partidas']['cod_programa'];
									  $cod_sub_prog2        =   $aux2['cepd01_compromiso_partidas']['cod_sub_prog'];
									  $cod_proyecto2        =   $aux2['cepd01_compromiso_partidas']['cod_proyecto'];
									  $cod_activ_obra2      =   $aux2['cepd01_compromiso_partidas']['cod_activ_obra'];
									  $cod_partida2         =   $aux2['cepd01_compromiso_partidas']['cod_partida'];
									  $cod_generica2        =   $aux2['cepd01_compromiso_partidas']['cod_generica'];
									  $cod_especifica2      =   $aux2['cepd01_compromiso_partidas']['cod_especifica'];
									  $cod_sub_espec2       =   $aux2['cepd01_compromiso_partidas']['cod_sub_espec'];
									  $cod_auxiliar2        =   $aux2['cepd01_compromiso_partidas']['cod_auxiliar'];
									  $marca = 0;
				  	    		foreach($cfpd07_obras_partidas  as  $aux_cfpd07_obras_partidas){
									  $cod_sector          =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_sector'];
									  $cod_programa        =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_programa'];
									  $cod_sub_prog        =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_sub_prog'];
									  $cod_proyecto        =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_proyecto'];
									  $cod_activ_obra      =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_activ_obra'];
									  $cod_partida         =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_partida'];
									  $cod_generica        =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_generica'];
									  $cod_especifica      =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_especifica'];
									  $cod_sub_espec       =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_sub_espec'];
									  $cod_auxiliar        =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_auxiliar'];
										  if($cod_sector     == $cod_sector2     &&
										     $cod_programa   == $cod_programa2   &&
										     $cod_sub_prog   == $cod_sub_prog2   &&
										     $cod_proyecto   == $cod_proyecto2   &&
										     $cod_activ_obra == $cod_activ_obra2 &&
										     $cod_partida    == $cod_partida2    &&
										     $cod_generica   == $cod_generica2   &&
										     $cod_especifica == $cod_especifica2 &&
										     $cod_sub_espec  == $cod_sub_espec2  &&
										     $cod_auxiliar   == $cod_auxiliar2){
										     	$marca = 1;
										  }//fin if
				  	    		 }//fin foreach
				  	    		 if($marca==0){$contador_exitentes++;
									$_SESSION["codigos"][$contador_exitentes][0]=$ano_obra;
									$_SESSION["codigos"][$contador_exitentes][1]=$cod_sector2;
									$_SESSION["codigos"][$contador_exitentes][2]=$cod_programa2;
									$_SESSION["codigos"][$contador_exitentes][3]=$cod_sub_prog2;
									$_SESSION["codigos"][$contador_exitentes][4]=$cod_proyecto2;
									$_SESSION["codigos"][$contador_exitentes][5]=$cod_activ_obra2;
									$_SESSION["codigos"][$contador_exitentes][6]=$cod_partida2;
									$_SESSION["codigos"][$contador_exitentes][7]=$cod_generica2;
									$_SESSION["codigos"][$contador_exitentes][8]=$cod_especifica2;
									$_SESSION["codigos"][$contador_exitentes][9]=$cod_sub_espec2;
									$_SESSION["codigos"][$contador_exitentes][10]=$cod_auxiliar2;
				  	    		 }
						}//fin foreach
						if($contador_exitentes==0){
                           $sw = $this->cepd01_compromiso_cuerpo->execute("UPDATE cepd01_compromiso_cuerpo set cod_obra='".$codigo_obra."' where ".$this->condicion()." and ano_documento='".$ano_documento."'   and  numero_documento='".$numero_documento."'    ");
						   foreach($lista2 as $aux2){
				  	    	          $cod_sector          =   $aux2['cepd01_compromiso_partidas']['cod_sector'];
									  $cod_programa        =   $aux2['cepd01_compromiso_partidas']['cod_programa'];
									  $cod_sub_prog        =   $aux2['cepd01_compromiso_partidas']['cod_sub_prog'];
									  $cod_proyecto        =   $aux2['cepd01_compromiso_partidas']['cod_proyecto'];
									  $cod_activ_obra      =   $aux2['cepd01_compromiso_partidas']['cod_activ_obra'];
									  $cod_partida         =   $aux2['cepd01_compromiso_partidas']['cod_partida'];
									  $cod_generica        =   $aux2['cepd01_compromiso_partidas']['cod_generica'];
									  $cod_especifica      =   $aux2['cepd01_compromiso_partidas']['cod_especifica'];
									  $cod_sub_espec       =   $aux2['cepd01_compromiso_partidas']['cod_sub_espec'];
									  $cod_auxiliar        =   $aux2['cepd01_compromiso_partidas']['cod_auxiliar'];
									  $monto               =   $aux2['cepd01_compromiso_partidas']['monto'];
									  $cond2               =   $this->condicion()." and ano='".$ano_obra."' and cod_sector='$cod_sector' and cod_programa='$cod_programa' and cod_sub_prog='$cod_sub_prog' and cod_proyecto='$cod_proyecto' and cod_activ_obra='$cod_activ_obra' and cod_partida='$cod_partida' and cod_generica='$cod_generica' and cod_especifica='$cod_especifica' and cod_sub_espec='$cod_sub_espec' and cod_auxiliar='$cod_auxiliar'";
	                                  $sql55               =   "UPDATE cfpd05 SET precompromiso_obras = precompromiso_obras - ".$monto." WHERE ".$cond2." and precompromiso_obras!=0";
//		                              $sw155               =   $this->cepd01_compromiso_partidas->execute($sql55);

		                              $sql_aux = "cod_presi=".$cod_presi." and cod_entidad=".$cod_entidad." and cod_tipo_inst=".$cod_tipo_inst." and  cod_inst=".$cod_inst." and cod_dep=".$cod_dep." and ano_estimacion=".$ano." and upper(cod_obra)='".strtoupper($codigo_obra)."' and cod_sector=".$cod_sector."   and   cod_programa=".$cod_programa."   and   cod_sub_prog=".$cod_sub_prog."   and   cod_proyecto=".$cod_proyecto."   and   cod_activ_obra=".$cod_activ_obra."   and   cod_partida=".$cod_partida."   and   cod_generica=".$cod_generica."   and   cod_especifica=".$cod_especifica."   and   cod_sub_espec=".$cod_sub_espec."   and   cod_auxiliar=".$cod_auxiliar.";";
						              $sw455 = $this->cscd04_ordencompra_partidas->execute("UPDATE cfpd07_obras_partidas SET monto_contratado=monto_contratado+".$monto." where ".$sql_aux);

							          $sql_aux = "cod_presi=".$cod_presi." and cod_entidad=".$cod_entidad." and cod_tipo_inst=".$cod_tipo_inst." and  cod_inst=".$cod_inst." and cod_dep=".$cod_dep." and ano_estimacion=".$ano." and upper(cod_obra)='".strtoupper($codigo_obra)."';";
						              $sw455   = $this->cscd04_ordencompra_partidas->execute("UPDATE cfpd07_obras_cuerpo SET monto_contratado=monto_contratado+".$monto." where ".$sql_aux);



						  }//fin foreach
						  $this->set('Message_existe', 'Los datos fueron  guardados');
						}else{
								$this->set('msg_error', 'los siguientes códigos presupuestarios no estan registrados en la relación de obras');
								$this->set('codigos', $_SESSION["codigos"]);
						}//fin if
			}//fin foreach
  }//fin function
}//fin function













function guardar($var1=null){
  $this->layout="ajax";
  $cod_presi                =       $this->Session->read('SScodpresi');
  $cod_entidad              =       $this->Session->read('SScodentidad');
  $cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
  $cod_inst                 =       $this->Session->read('SScodinst');
  $cod_dep                  =       $this->Session->read('SScoddep');
  $ano                      =       $this->ano_ejecucion();
  $ano_obra                 =       $this->data['caop00_vincular']['ano_obra'];
  $codigo_obra              =       $this->data['caop00_vincular']['codigo_obra'];
  $opcion_radio             =       $this->data['caop00_vincular']['opcion_radio'];
  $numero_documento         =       $this->data['caop00_vincular']['numero_documento'];
  $this->Session->delete('codigos');

        if($opcion_radio==1){
           $lista                 = $this->cscd04_ordencompra_encabezado->findAll($this->condicion()." and ano_orden_compra = ".$ano_obra." and numero_orden_compra='".$numero_documento."' "." and (cod_obra='' or cod_obra='0' or cod_obra IS NULL)");
	  	   $cfpd07_obras_partidas =  $this->cfpd07_obras_partidas->findAll($this->condicion()."        and ano_estimacion   = ".$ano."      and upper(cod_obra)='".strtoupper($codigo_obra)."' ", null, 'cod_sector,cod_programa,cod_sub_prog,cod_proyecto,cod_activ_obra, cod_partida,cod_generica,cod_especifica,cod_sub_espec,cod_auxiliar ASC');
	  	   foreach($lista as $aux){
	  	   	    $contador_exitentes  = 0;
				$rif                 = $aux['cscd04_ordencompra_encabezado']['rif'];
				$ano_orden_compra    = $aux['cscd04_ordencompra_encabezado']['ano_orden_compra'];
				$numero_orden_compra = $aux['cscd04_ordencompra_encabezado']['numero_orden_compra'];
				$ano_cotizacion      = $aux['cscd04_ordencompra_encabezado']['ano_cotizacion'];
				$numero_cotizacion   = $aux['cscd04_ordencompra_encabezado']['numero_cotizacion'];
				$condicion_actividad = $aux['cscd04_ordencompra_encabezado']['condicion_actividad'];
				$lista3              = $this->cscd03_cotizacion_encabezado->findAll($this->condicion()." and ano_cotizacion = ".$ano_cotizacion." and numero_cotizacion='".$numero_cotizacion."' and rif='".$rif."' ");
                $ano_solicitud       = $lista3[0]["cscd03_cotizacion_encabezado"]["ano_solicitud"];
                $numero_solicitud    = $lista3[0]["cscd03_cotizacion_encabezado"]["numero_solicitud"];
                $lista2              = $this->cscd04_ordencompra_partidas->findAll($this->condicion()." and ano_orden_compra = ".$ano_obra." and numero_orden_compra='".$numero_documento."' ");
				  	    foreach($lista2 as $aux2){
				  	    	          $cod_sector2          =   $aux2['cscd04_ordencompra_partidas']['cod_sector'];
									  $cod_programa2        =   $aux2['cscd04_ordencompra_partidas']['cod_programa'];
									  $cod_sub_prog2        =   $aux2['cscd04_ordencompra_partidas']['cod_sub_prog'];
									  $cod_proyecto2        =   $aux2['cscd04_ordencompra_partidas']['cod_proyecto'];
									  $cod_activ_obra2      =   $aux2['cscd04_ordencompra_partidas']['cod_activ_obra'];
									  $cod_partida2         =   $aux2['cscd04_ordencompra_partidas']['cod_partida'];
									  $cod_generica2        =   $aux2['cscd04_ordencompra_partidas']['cod_generica'];
									  $cod_especifica2      =   $aux2['cscd04_ordencompra_partidas']['cod_especifica'];
									  $cod_sub_espec2       =   $aux2['cscd04_ordencompra_partidas']['cod_sub_espec'];
									  $cod_auxiliar2        =   $aux2['cscd04_ordencompra_partidas']['cod_auxiliar'];
									  $marca = 0;
				  	    		foreach($cfpd07_obras_partidas  as  $aux_cfpd07_obras_partidas){
									  $cod_sector          =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_sector'];
									  $cod_programa        =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_programa'];
									  $cod_sub_prog        =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_sub_prog'];
									  $cod_proyecto        =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_proyecto'];
									  $cod_activ_obra      =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_activ_obra'];
									  $cod_partida         =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_partida'];
									  $cod_generica        =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_generica'];
									  $cod_especifica      =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_especifica'];
									  $cod_sub_espec       =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_sub_espec'];
									  $cod_auxiliar        =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_auxiliar'];
										  if($cod_sector     == $cod_sector2     &&
										     $cod_programa   == $cod_programa2   &&
										     $cod_sub_prog   == $cod_sub_prog2   &&
										     $cod_proyecto   == $cod_proyecto2   &&
										     $cod_activ_obra == $cod_activ_obra2 &&
										     $cod_partida    == $cod_partida2    &&
										     $cod_generica   == $cod_generica2   &&
										     $cod_especifica == $cod_especifica2 &&
										     $cod_sub_espec  == $cod_sub_espec2  &&
										     $cod_auxiliar   == $cod_auxiliar2){
										     	$marca = 1;
										  }else{

										  }
				  	    		 }//fin foreach
				  	    		 if($marca==0){$contador_exitentes++;
									$_SESSION["codigos"][$contador_exitentes][0]=$ano_obra;
									$_SESSION["codigos"][$contador_exitentes][1]=$cod_sector2;
									$_SESSION["codigos"][$contador_exitentes][2]=$cod_programa2;
									$_SESSION["codigos"][$contador_exitentes][3]=$cod_sub_prog2;
									$_SESSION["codigos"][$contador_exitentes][4]=$cod_proyecto2;
									$_SESSION["codigos"][$contador_exitentes][5]=$cod_activ_obra2;
									$_SESSION["codigos"][$contador_exitentes][6]=$cod_partida2;
									$_SESSION["codigos"][$contador_exitentes][7]=$cod_generica2;
									$_SESSION["codigos"][$contador_exitentes][8]=$cod_especifica2;
									$_SESSION["codigos"][$contador_exitentes][9]=$cod_sub_espec2;
									$_SESSION["codigos"][$contador_exitentes][10]=$cod_auxiliar2;
				  	    		 }
						}//fin foreach
						if($contador_exitentes==0){
                           $sw = $this->cscd02_solicitud_encabezado->execute("UPDATE cscd02_solicitud_encabezado   set cod_obra='".$codigo_obra."' where ".$this->condicion()." and ano_solicitud='".$ano_solicitud."'         and  numero_solicitud='".$numero_solicitud."'    ");
                           $sw = $this->cscd02_solicitud_encabezado->execute("UPDATE cscd03_cotizacion_encabezado  set cod_obra='".$codigo_obra."' where ".$this->condicion()." and ano_cotizacion='".$ano_cotizacion."'       and  numero_cotizacion='".$numero_cotizacion."' and rif='".$rif."'   ");
                           $sw = $this->cscd02_solicitud_encabezado->execute("UPDATE cscd04_ordencompra_encabezado set cod_obra='".$codigo_obra."' where ".$this->condicion()." and ano_orden_compra='".$ano_orden_compra."'   and  numero_orden_compra='".$numero_orden_compra."'    ");
						   foreach($lista2 as $aux2){
				  	    	          $cod_sector          =   $aux2['cscd04_ordencompra_partidas']['cod_sector'];
									  $cod_programa        =   $aux2['cscd04_ordencompra_partidas']['cod_programa'];
									  $cod_sub_prog        =   $aux2['cscd04_ordencompra_partidas']['cod_sub_prog'];
									  $cod_proyecto        =   $aux2['cscd04_ordencompra_partidas']['cod_proyecto'];
									  $cod_activ_obra      =   $aux2['cscd04_ordencompra_partidas']['cod_activ_obra'];
									  $cod_partida         =   $aux2['cscd04_ordencompra_partidas']['cod_partida'];
									  $cod_generica        =   $aux2['cscd04_ordencompra_partidas']['cod_generica'];
									  $cod_especifica      =   $aux2['cscd04_ordencompra_partidas']['cod_especifica'];
									  $cod_sub_espec       =   $aux2['cscd04_ordencompra_partidas']['cod_sub_espec'];
									  $cod_auxiliar        =   $aux2['cscd04_ordencompra_partidas']['cod_auxiliar'];
									  $monto               =   $aux2['cscd04_ordencompra_partidas']['monto'];
									  $cond2               =   $this->condicion()." and ano='".$ano_obra."' and cod_sector='$cod_sector' and cod_programa='$cod_programa' and cod_sub_prog='$cod_sub_prog' and cod_proyecto='$cod_proyecto' and cod_activ_obra='$cod_activ_obra' and cod_partida='$cod_partida' and cod_generica='$cod_generica' and cod_especifica='$cod_especifica' and cod_sub_espec='$cod_sub_espec' and cod_auxiliar='$cod_auxiliar'";
	                                  $sql55               =   "UPDATE cfpd05 SET precompromiso_obras = precompromiso_obras - ".$monto." WHERE ".$cond2." and precompromiso_obras!=0";
		                              if($condicion_actividad==1){
		                                $sw155 = $this->cscd04_ordencompra_partidas->execute($sql55);
		                              }
		                              $sql_aux = "cod_presi=".$cod_presi." and cod_entidad=".$cod_entidad." and cod_tipo_inst=".$cod_tipo_inst." and  cod_inst=".$cod_inst." and cod_dep=".$cod_dep." and ano_estimacion=".$ano." and upper(cod_obra)='".strtoupper($codigo_obra)."' and cod_sector=".$cod_sector."   and   cod_programa=".$cod_programa."   and   cod_sub_prog=".$cod_sub_prog."   and   cod_proyecto=".$cod_proyecto."   and   cod_activ_obra=".$cod_activ_obra."   and   cod_partida=".$cod_partida."   and   cod_generica=".$cod_generica."   and   cod_especifica=".$cod_especifica."   and   cod_sub_espec=".$cod_sub_espec."   and   cod_auxiliar=".$cod_auxiliar.";";
						              $sw455 = $this->cscd04_ordencompra_partidas->execute("UPDATE cfpd07_obras_partidas SET monto_contratado=monto_contratado+".$monto." where ".$sql_aux);

							          $sql_aux = "cod_presi=".$cod_presi." and cod_entidad=".$cod_entidad." and cod_tipo_inst=".$cod_tipo_inst." and  cod_inst=".$cod_inst." and cod_dep=".$cod_dep." and ano_estimacion=".$ano." and upper(cod_obra)='".strtoupper($codigo_obra)."';";
						              $sw455   = $this->cscd04_ordencompra_partidas->execute("UPDATE cfpd07_obras_cuerpo SET monto_contratado=monto_contratado+".$monto." where ".$sql_aux);


						  }//fin foreach
						   $this->set('Message_existe', 'Los datos fueron  guardados');
						}else{
								$this->set('msg_error', 'los siguientes códigos presupuestarios no estan registrados en la relación de obras');
								$this->set('codigos', $_SESSION["codigos"]);
						}//fin if
			}//fin foreach
  }else if($opcion_radio==2){
  	       $lista                 = $this->cepd01_compromiso_cuerpo->findAll($this->condicion()." and ano_documento = ".$ano_obra." and numero_documento='".$numero_documento."' "." and (cod_obra='' or cod_obra='0' or cod_obra IS NULL)");
	  	   $cfpd07_obras_partidas =  $this->cfpd07_obras_partidas->findAll($this->condicion()."   and ano_estimacion   = ".$ano."      and upper(cod_obra)='".strtoupper($codigo_obra)."' ", null, 'cod_sector,cod_programa,cod_sub_prog,cod_proyecto,cod_activ_obra, cod_partida,cod_generica,cod_especifica,cod_sub_espec,cod_auxiliar ASC');
	  	   foreach($lista as $aux){
	  	   	    $contador_exitentes  =  0;
				$ano_documento       =  $aux['cepd01_compromiso_cuerpo']['ano_documento'];
				$numero_documento    =  $aux['cepd01_compromiso_cuerpo']['numero_documento'];
				$condicion_actividad =  $aux['cepd01_compromiso_cuerpo']['condicion_actividad'];
                $lista2              =  $this->cepd01_compromiso_partidas->findAll($this->condicion()." and ano_documento = ".$ano_obra." and numero_documento='".$numero_documento."' ");
				  	    foreach($lista2 as $aux2){
				  	    	          $cod_sector2          =   $aux2['cepd01_compromiso_partidas']['cod_sector'];
									  $cod_programa2        =   $aux2['cepd01_compromiso_partidas']['cod_programa'];
									  $cod_sub_prog2        =   $aux2['cepd01_compromiso_partidas']['cod_sub_prog'];
									  $cod_proyecto2        =   $aux2['cepd01_compromiso_partidas']['cod_proyecto'];
									  $cod_activ_obra2      =   $aux2['cepd01_compromiso_partidas']['cod_activ_obra'];
									  $cod_partida2         =   $aux2['cepd01_compromiso_partidas']['cod_partida'];
									  $cod_generica2        =   $aux2['cepd01_compromiso_partidas']['cod_generica'];
									  $cod_especifica2      =   $aux2['cepd01_compromiso_partidas']['cod_especifica'];
									  $cod_sub_espec2       =   $aux2['cepd01_compromiso_partidas']['cod_sub_espec'];
									  $cod_auxiliar2        =   $aux2['cepd01_compromiso_partidas']['cod_auxiliar'];
									  $marca = 0;
				  	    		foreach($cfpd07_obras_partidas  as  $aux_cfpd07_obras_partidas){
									  $cod_sector          =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_sector'];
									  $cod_programa        =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_programa'];
									  $cod_sub_prog        =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_sub_prog'];
									  $cod_proyecto        =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_proyecto'];
									  $cod_activ_obra      =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_activ_obra'];
									  $cod_partida         =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_partida'];
									  $cod_generica        =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_generica'];
									  $cod_especifica      =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_especifica'];
									  $cod_sub_espec       =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_sub_espec'];
									  $cod_auxiliar        =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_auxiliar'];
										  if($cod_sector     == $cod_sector2     &&
										     $cod_programa   == $cod_programa2   &&
										     $cod_sub_prog   == $cod_sub_prog2   &&
										     $cod_proyecto   == $cod_proyecto2   &&
										     $cod_activ_obra == $cod_activ_obra2 &&
										     $cod_partida    == $cod_partida2    &&
										     $cod_generica   == $cod_generica2   &&
										     $cod_especifica == $cod_especifica2 &&
										     $cod_sub_espec  == $cod_sub_espec2  &&
										     $cod_auxiliar   == $cod_auxiliar2){
										     	$marca = 1;
										  }//fin if
				  	    		 }//fin foreach
				  	    		 if($marca==0){$contador_exitentes++;
									$_SESSION["codigos"][$contador_exitentes][0]=$ano_obra;
									$_SESSION["codigos"][$contador_exitentes][1]=$cod_sector2;
									$_SESSION["codigos"][$contador_exitentes][2]=$cod_programa2;
									$_SESSION["codigos"][$contador_exitentes][3]=$cod_sub_prog2;
									$_SESSION["codigos"][$contador_exitentes][4]=$cod_proyecto2;
									$_SESSION["codigos"][$contador_exitentes][5]=$cod_activ_obra2;
									$_SESSION["codigos"][$contador_exitentes][6]=$cod_partida2;
									$_SESSION["codigos"][$contador_exitentes][7]=$cod_generica2;
									$_SESSION["codigos"][$contador_exitentes][8]=$cod_especifica2;
									$_SESSION["codigos"][$contador_exitentes][9]=$cod_sub_espec2;
									$_SESSION["codigos"][$contador_exitentes][10]=$cod_auxiliar2;
				  	    		 }
						}//fin foreach
						if($contador_exitentes==0){
                           $sw = $this->cepd01_compromiso_cuerpo->execute("UPDATE cepd01_compromiso_cuerpo set cod_obra='".$codigo_obra."' where ".$this->condicion()." and ano_documento='".$ano_documento."'   and  numero_documento='".$numero_documento."'    ");
						   foreach($lista2 as $aux2){
				  	    	          $cod_sector          =   $aux2['cepd01_compromiso_partidas']['cod_sector'];
									  $cod_programa        =   $aux2['cepd01_compromiso_partidas']['cod_programa'];
									  $cod_sub_prog        =   $aux2['cepd01_compromiso_partidas']['cod_sub_prog'];
									  $cod_proyecto        =   $aux2['cepd01_compromiso_partidas']['cod_proyecto'];
									  $cod_activ_obra      =   $aux2['cepd01_compromiso_partidas']['cod_activ_obra'];
									  $cod_partida         =   $aux2['cepd01_compromiso_partidas']['cod_partida'];
									  $cod_generica        =   $aux2['cepd01_compromiso_partidas']['cod_generica'];
									  $cod_especifica      =   $aux2['cepd01_compromiso_partidas']['cod_especifica'];
									  $cod_sub_espec       =   $aux2['cepd01_compromiso_partidas']['cod_sub_espec'];
									  $cod_auxiliar        =   $aux2['cepd01_compromiso_partidas']['cod_auxiliar'];
									  $monto               =   $aux2['cepd01_compromiso_partidas']['monto'];
									  $cond2               =   $this->condicion()." and ano='".$ano_obra."' and cod_sector='$cod_sector' and cod_programa='$cod_programa' and cod_sub_prog='$cod_sub_prog' and cod_proyecto='$cod_proyecto' and cod_activ_obra='$cod_activ_obra' and cod_partida='$cod_partida' and cod_generica='$cod_generica' and cod_especifica='$cod_especifica' and cod_sub_espec='$cod_sub_espec' and cod_auxiliar='$cod_auxiliar'";
	                                  $sql55               =   "UPDATE cfpd05 SET precompromiso_obras = precompromiso_obras - ".$monto." WHERE ".$cond2." and precompromiso_obras!=0";

		                              if($condicion_actividad==1){
		                               $sw155               =   $this->cepd01_compromiso_partidas->execute($sql55);
		                              }

		                              $sql_aux = "cod_presi=".$cod_presi." and cod_entidad=".$cod_entidad." and cod_tipo_inst=".$cod_tipo_inst." and  cod_inst=".$cod_inst." and cod_dep=".$cod_dep." and ano_estimacion=".$ano." and upper(cod_obra)='".strtoupper($codigo_obra)."' and cod_sector=".$cod_sector."   and   cod_programa=".$cod_programa."   and   cod_sub_prog=".$cod_sub_prog."   and   cod_proyecto=".$cod_proyecto."   and   cod_activ_obra=".$cod_activ_obra."   and   cod_partida=".$cod_partida."   and   cod_generica=".$cod_generica."   and   cod_especifica=".$cod_especifica."   and   cod_sub_espec=".$cod_sub_espec."   and   cod_auxiliar=".$cod_auxiliar.";";
						              $sw455 = $this->cscd04_ordencompra_partidas->execute("UPDATE cfpd07_obras_partidas SET monto_contratado=monto_contratado+".$monto." where ".$sql_aux);

							          $sql_aux = "cod_presi=".$cod_presi." and cod_entidad=".$cod_entidad." and cod_tipo_inst=".$cod_tipo_inst." and  cod_inst=".$cod_inst." and cod_dep=".$cod_dep." and ano_estimacion=".$ano." and upper(cod_obra)='".strtoupper($codigo_obra)."';";
						              $sw455   = $this->cscd04_ordencompra_partidas->execute("UPDATE cfpd07_obras_cuerpo SET monto_contratado=monto_contratado+".$monto." where ".$sql_aux);



						  }//fin foreach
						  $this->set('Message_existe', 'Los datos fueron  guardados');
						}else{
								$this->set('msg_error', 'los siguientes códigos presupuestarios no estan registrados en la relación de obras');
								$this->set('codigos', $_SESSION["codigos"]);
						}//fin if
			}//fin foreach
  }//fin function
}//fin function
















function seleccion($var1=null, $var2=null){
   $this->layout="ajax";

  $cod_presi                =       $this->Session->read('SScodpresi');
  $cod_entidad              =       $this->Session->read('SScodentidad');
  $cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
  $cod_inst                 =       $this->Session->read('SScodinst');
  $cod_dep                  =       $this->Session->read('SScoddep');
  $condicion                =       'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$this->Session->read('SScoddep').'  ';
  $ano                      =       $this->ano_ejecucion();

  if($var1==1){
  	  $lista = $this->cscd04_ordencompra_encabezado->findAll($condicion." and ano_orden_compra = ".$ano." and numero_orden_compra='".$var2."' ");
  	    foreach($lista as $aux){
			$rif                 = $aux['cscd04_ordencompra_encabezado']['rif'];
			$ano_orden_compra    = $aux['cscd04_ordencompra_encabezado']['ano_orden_compra'];
			$numero_orden_compra = $aux['cscd04_ordencompra_encabezado']['numero_orden_compra'];
		}//fin foreach
		$rif_datos = $this->cpcd02->findAll("rif='".$rif."'");
		foreach($rif_datos as $aux_2){
			$deno        = $aux_2['cpcd02']['denominacion'];
		}//fin foreach
  }else{
  	  $lista = $this->cepd01_compromiso_cuerpo->findAll($condicion." and ano_documento = ".$ano." and numero_documento='".$var2."' ");
  	  $deno  = $lista[0]["cepd01_compromiso_cuerpo"]["beneficiario"];
  }//fin function

  $this->set("deno", $deno);

}//fin function










 function mostrar_partidas_obra ($var = null) {
   $this->layout="ajax";

  $cod_presi                =       $this->Session->read('SScodpresi');
  $cod_entidad              =       $this->Session->read('SScodentidad');
  $cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
  $cod_inst                 =       $this->Session->read('SScodinst');
  $cod_dep                  =       $this->Session->read('SScoddep');
  $condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$this->Session->read('SScoddep').'  ';
  $ano='';
  $monto_original_contrato_aux = 0;
  $aux_guardar_2 = 0;
  $aux_guardar   = 0;
  $ano = $this->ano_ejecucion();
  $denominacion_snc = "";
  $cod_snc          = "";

   $this->Session->write("codigo_cod_obra", $var);

  $this->Session->delete("items");
  $this->Session->delete("i");
if($var!=null){
	$cfpd07_obras_cuerpo   =  $this->cfpd07_obras_cuerpo->findAll($condicion." and ano_estimacion=".$ano." and upper(cod_obra)='".strtoupper($var)."' ");
	$cfpd07_obras_partidas =  $this->cfpd07_obras_partidas->findAll($condicion." and ano_estimacion=".$ano." and upper(cod_obra)='".strtoupper($var)."' ", null, 'cod_sector,cod_programa,cod_sub_prog,cod_proyecto,cod_activ_obra, cod_partida,cod_generica,cod_especifica,cod_sub_espec,cod_auxiliar ASC');

	$opcion = "si";
	$cont_a = 0;
	$cont_b = 0;
	$opc    = 0;

	$result   = $this->cfpd07_obras_modificacion_cuerpo->findAll($condicion."   and ano_estimacion=".$ano."  and  upper(cod_obra)='".strtoupper($var)."' ", null, "numero_modificacion ASC", null, null);
    foreach($result as $ves){$opc = $ves['cfpd07_obras_modificacion_cuerpo']['numero_modificacion'];}//fin foreach
    $opc++;
    $this->set('numero_modificacion', $opc);



	foreach($cfpd07_obras_partidas  as  $aux_cfpd07_obras_partidas){ $cont_a++;

	  $cod_presi           =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_presi'];
	  $cod_entidad         =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_entidad'];
	  $cod_tipo_inst       =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_tipo_inst'];
	  $cod_inst            =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_inst'];
	  $cod_dep             =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_dep'];
	  $ano_estimacion      =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['ano_estimacion'];
	  $cod_obra            =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_obra'];
	  $cod_sector          =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_sector'];
	  $cod_programa        =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_programa'];
	  $cod_sub_prog        =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_sub_prog'];
	  $cod_proyecto        =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_proyecto'];
	  $cod_activ_obra      =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_activ_obra'];
	  $cod_partida         =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_partida'];
	  $cod_generica        =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_generica'];
	  $cod_especifica      =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_especifica'];
	  $cod_sub_espec       =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_sub_espec'];
	  $cod_auxiliar        =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_auxiliar'];
	  $monto               =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['monto'];
	  $monto_contratado    =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['monto_contratado'];


	 $sql_verificar ="cod_presi=".$cod_presi." and cod_entidad=".$cod_entidad." and cod_tipo_inst=".$cod_tipo_inst." and cod_inst=".$cod_inst." and cod_dep=1 and ano=".$ano_estimacion;
	 $sql_verificar .=" and cod_sector=".$cod_sector." and cod_programa=".$cod_programa." and cod_sub_prog=".$cod_sub_prog." and cod_proyecto=".$cod_proyecto." and cod_activ_obra=".$cod_activ_obra;
	 $sql_verificar .=" and cod_partida=".$this->AddCeroR($cod_partida)." and cod_generica=".$cod_generica." and cod_especifica=".$cod_especifica." and cod_sub_espec=".$cod_sub_espec." and cod_auxiliar=".$cod_auxiliar."";

	if($this->cfpd05->findCount($sql_verificar)!=0){
	    $disponibilidad = $this->disponibilidad_con_dep('1', $ano, $cod_sector, $cod_programa, $cod_sub_prog, $cod_proyecto, $cod_activ_obra, $cod_partida, $cod_generica, $cod_especifica, $cod_sub_espec, $cod_auxiliar);
	    $monto2 = $monto - $monto_contratado;
		          if($disponibilidad=="0.00" || $disponibilidad<$monto2){$opcion="no"; $cont_b++;

		          }else{
		               //$this->trafico($ano_estimacion, $cod_sector, $cod_programa, $cod_sub_prog, $cod_proyecto, $cod_activ_obra, $cod_partida, $cod_generica, $cod_especifica, $cod_sub_espec, $monto2, $cod_auxiliar);
		          }//fin else
		}//fin if contador
	}//fin foreach

	if($opcion=="no" && ($cont_a==$cont_b)){
		      $this->set('msg_error', 'Lo siento una de las partidas no tiene disponibilidad');
		      echo'<script>';
		    	echo"document.getElementById('guardar').disabled=true;";
			  echo'</script>';
	}//fin if


	$denominacion_obra = "";
	$cfpd07_obras_cuerpo_aux = $cfpd07_obras_cuerpo;
	foreach($cfpd07_obras_cuerpo_aux as $aux){
		$codigo_prod_serv    = $aux['cfpd07_obras_cuerpo']['codigo_prod_serv'];
		$estimado_presu      = $aux['cfpd07_obras_cuerpo']['estimado_presu'];
		$denominacion_obra   = $aux['cfpd07_obras_cuerpo']['denominacion'];

		$a   = $aux['cfpd07_obras_cuerpo']['estimado_presu'];
		$b   = $aux['cfpd07_obras_cuerpo']['monto_contratado'];
		$c   = $aux['cfpd07_obras_cuerpo']['aumento_obras'];
		$d   = $aux['cfpd07_obras_cuerpo']['disminucion_obras'];
	}//fin foreach


	$this->set('selecion_lista', $var);
	$this->set('estimado_presu', $estimado_presu);
	$this->set('cfpd07_obras_partidas', $cfpd07_obras_partidas);
	$this->set('cod_snc', $cod_snc);
	$this->set('ano_ejecucion', $ano);

	$this->set('a', $a);
	$this->set('b', $b);
	$this->set('c', $c);
	$this->set('d', $d);


$denominacion_obra = str_replace("\n",'',$denominacion_obra);
$denominacion_obra = str_replace("'",'',$denominacion_obra);
$denominacion_obra = str_replace(">",'',$denominacion_obra);
$denominacion_obra = str_replace("<",'',$denominacion_obra);
$denominacion_obra = str_replace('"','',$denominacion_obra);
$this->set('denominacion_obra',$denominacion_obra);

}else{




}//fin else

$this->set('var',$var);

}//fin funcion mostrar_partidas_obra













////////////////////////////////////AQUI REACTUALIZA//////////////////////////////////////////////////////////



function actualizar_precompromiso_relacion_obras_en_cero($var1=null){

	set_time_limit(0);
  $this->layout="ajax";
  $cod_presi                =       $this->Session->read('SScodpresi');
  $cod_entidad              =       $this->Session->read('SScodentidad');
  $cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
  $cod_inst                 =       $this->Session->read('SScodinst');
  $cod_dep                  =       $this->Session->read('SScoddep');
  $ano                      =       $this->ano_ejecucion();


$cfpd07_obras_partidas =  $this->cfpd07_obras_partidas->findAll($this->condicion()."        and ano_estimacion   = ".$ano." ", null, 'cod_sector,cod_programa,cod_sub_prog,cod_proyecto,cod_activ_obra, cod_partida,cod_generica,cod_especifica,cod_sub_espec,cod_auxiliar ASC');

	  	  foreach($cfpd07_obras_partidas  as  $aux_cfpd07_obras_partidas){
									  $cod_sector          =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_sector'];
									  $cod_programa        =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_programa'];
									  $cod_sub_prog        =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_sub_prog'];
									  $cod_proyecto        =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_proyecto'];
									  $cod_activ_obra      =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_activ_obra'];
									  $cod_partida         =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_partida'];
									  $cod_generica        =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_generica'];
									  $cod_especifica      =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_especifica'];
									  $cod_sub_espec       =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_sub_espec'];
									  $cod_auxiliar        =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_auxiliar'];
									  $monto               =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['monto'];

								      $cond2               =   $this->condicion()." and ano='".$ano."' and cod_sector='$cod_sector' and cod_programa='$cod_programa' and cod_sub_prog='$cod_sub_prog' and cod_proyecto='$cod_proyecto' and cod_activ_obra='$cod_activ_obra' and cod_partida='$cod_partida' and cod_generica='$cod_generica' and cod_especifica='$cod_especifica' and cod_sub_espec='$cod_sub_espec' and cod_auxiliar='$cod_auxiliar'";
	                                  $sql55               =   "UPDATE cfpd05 SET precompromiso_obras = 0  WHERE ".$cond2." ";
		                              $sw155               =   $this->cscd04_ordencompra_partidas->execute($sql55);

		  }//fin foreach







}//fin function
























function actualizar_precompromiso_relacion_obras($var1=null){

	set_time_limit(0);
  $this->layout="ajax";
  $cod_presi                =       $this->Session->read('SScodpresi');
  $cod_entidad              =       $this->Session->read('SScodentidad');
  $cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
  $cod_inst                 =       $this->Session->read('SScodinst');
  $cod_dep                  =       $this->Session->read('SScoddep');
  $ano                      =       $this->ano_ejecucion();


$cfpd07_obras_partidas =  $this->cfpd07_obras_partidas->findAll($this->condicion()."        and ano_estimacion   = ".$ano." ", null, 'cod_sector,cod_programa,cod_sub_prog,cod_proyecto,cod_activ_obra, cod_partida,cod_generica,cod_especifica,cod_sub_espec,cod_auxiliar ASC');

	  	  foreach($cfpd07_obras_partidas  as  $aux_cfpd07_obras_partidas){
									  $cod_sector          =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_sector'];
									  $cod_programa        =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_programa'];
									  $cod_sub_prog        =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_sub_prog'];
									  $cod_proyecto        =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_proyecto'];
									  $cod_activ_obra      =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_activ_obra'];
									  $cod_partida         =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_partida'];
									  $cod_generica        =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_generica'];
									  $cod_especifica      =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_especifica'];
									  $cod_sub_espec       =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_sub_espec'];
									  $cod_auxiliar        =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_auxiliar'];
									  $monto               =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['monto'];

								      $cond2               =   $this->condicion()." and ano='".$ano."' and cod_sector='$cod_sector' and cod_programa='$cod_programa' and cod_sub_prog='$cod_sub_prog' and cod_proyecto='$cod_proyecto' and cod_activ_obra='$cod_activ_obra' and cod_partida='$cod_partida' and cod_generica='$cod_generica' and cod_especifica='$cod_especifica' and cod_sub_espec='$cod_sub_espec' and cod_auxiliar='$cod_auxiliar'";
	                                  $sql55               =   "UPDATE cfpd05 SET precompromiso_obras = precompromiso_obras + ".$monto." WHERE ".$cond2." ";
		                              $sw155               =   $this->cscd04_ordencompra_partidas->execute($sql55);

		  }//fin foreach







}//fin function



















function actualizar_precompromiso_activo($var1=null){

	set_time_limit(0);
  $this->layout="ajax";
  $cod_presi                =       $this->Session->read('SScodpresi');
  $cod_entidad              =       $this->Session->read('SScodentidad');
  $cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
  $cod_inst                 =       $this->Session->read('SScodinst');
  $cod_dep                  =       $this->Session->read('SScoddep');
  $ano                      =       $this->ano_ejecucion();


$lista_aux_2                 =  $this->cscd04_ordencompra_encabezado->findAll($this->condicion()." and ano_orden_compra = ".$ano." "." and (cod_obra!='' and cod_obra!='0' and cod_obra IS NOT NULL) ");
foreach($lista_aux_2 as $aux_2){
	  $this->Session->delete('codigos');

  $ano_obra                 =       $aux_2['cscd04_ordencompra_encabezado']['ano_orden_compra'];
  $numero_documento         =       $aux_2['cscd04_ordencompra_encabezado']['numero_orden_compra'];
  $codigo_obra              =       $aux_2['cscd04_ordencompra_encabezado']['cod_obra'];

           $lista                 =  $this->cscd04_ordencompra_encabezado->findAll($this->condicion()." and ano_orden_compra = ".$ano_obra." and numero_orden_compra='".$numero_documento."' "." ");
	  	   $cfpd07_obras_partidas =  $this->cfpd07_obras_partidas->findAll($this->condicion()."        and ano_estimacion   = ".$ano."      and upper(cod_obra)='".strtoupper($codigo_obra)."' ", null, 'cod_sector,cod_programa,cod_sub_prog,cod_proyecto,cod_activ_obra, cod_partida,cod_generica,cod_especifica,cod_sub_espec,cod_auxiliar ASC');
	  	   foreach($lista as $aux){
	  	   	    $contador_exitentes  = 0;
				$rif                 = $aux['cscd04_ordencompra_encabezado']['rif'];
				$ano_orden_compra    = $aux['cscd04_ordencompra_encabezado']['ano_orden_compra'];
				$numero_orden_compra = $aux['cscd04_ordencompra_encabezado']['numero_orden_compra'];
				$ano_cotizacion      = $aux['cscd04_ordencompra_encabezado']['ano_cotizacion'];
				$numero_cotizacion   = $aux['cscd04_ordencompra_encabezado']['numero_cotizacion'];
				$lista3              = $this->cscd03_cotizacion_encabezado->findAll($this->condicion()." and ano_cotizacion = ".$ano_cotizacion." and numero_cotizacion='".$numero_cotizacion."' and rif='".$rif."' ");
                $ano_solicitud       = $lista3[0]["cscd03_cotizacion_encabezado"]["ano_solicitud"];
                $numero_solicitud    = $lista3[0]["cscd03_cotizacion_encabezado"]["numero_solicitud"];
                $lista2              = $this->cscd04_ordencompra_partidas->findAll($this->condicion()." and ano_orden_compra = ".$ano_obra." and numero_orden_compra='".$numero_documento."' ");
				  	    foreach($lista2 as $aux2){
				  	    	          $cod_sector2          =   $aux2['cscd04_ordencompra_partidas']['cod_sector'];
									  $cod_programa2        =   $aux2['cscd04_ordencompra_partidas']['cod_programa'];
									  $cod_sub_prog2        =   $aux2['cscd04_ordencompra_partidas']['cod_sub_prog'];
									  $cod_proyecto2        =   $aux2['cscd04_ordencompra_partidas']['cod_proyecto'];
									  $cod_activ_obra2      =   $aux2['cscd04_ordencompra_partidas']['cod_activ_obra'];
									  $cod_partida2         =   $aux2['cscd04_ordencompra_partidas']['cod_partida'];
									  $cod_generica2        =   $aux2['cscd04_ordencompra_partidas']['cod_generica'];
									  $cod_especifica2      =   $aux2['cscd04_ordencompra_partidas']['cod_especifica'];
									  $cod_sub_espec2       =   $aux2['cscd04_ordencompra_partidas']['cod_sub_espec'];
									  $cod_auxiliar2        =   $aux2['cscd04_ordencompra_partidas']['cod_auxiliar'];
									  $marca = 0;
				  	    		foreach($cfpd07_obras_partidas  as  $aux_cfpd07_obras_partidas){
									  $cod_sector          =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_sector'];
									  $cod_programa        =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_programa'];
									  $cod_sub_prog        =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_sub_prog'];
									  $cod_proyecto        =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_proyecto'];
									  $cod_activ_obra      =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_activ_obra'];
									  $cod_partida         =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_partida'];
									  $cod_generica        =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_generica'];
									  $cod_especifica      =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_especifica'];
									  $cod_sub_espec       =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_sub_espec'];
									  $cod_auxiliar        =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_auxiliar'];
										  if($cod_sector     == $cod_sector2     &&
										     $cod_programa   == $cod_programa2   &&
										     $cod_sub_prog   == $cod_sub_prog2   &&
										     $cod_proyecto   == $cod_proyecto2   &&
										     $cod_activ_obra == $cod_activ_obra2 &&
										     $cod_partida    == $cod_partida2    &&
										     $cod_generica   == $cod_generica2   &&
										     $cod_especifica == $cod_especifica2 &&
										     $cod_sub_espec  == $cod_sub_espec2  &&
										     $cod_auxiliar   == $cod_auxiliar2){
										     	$marca = 1;
										  }else{

										  }
				  	    		 }//fin foreach
				  	    		 if($marca==0){$contador_exitentes++;
									$_SESSION["codigos"][$contador_exitentes][0]=$ano_obra;
									$_SESSION["codigos"][$contador_exitentes][1]=$cod_sector2;
									$_SESSION["codigos"][$contador_exitentes][2]=$cod_programa2;
									$_SESSION["codigos"][$contador_exitentes][3]=$cod_sub_prog2;
									$_SESSION["codigos"][$contador_exitentes][4]=$cod_proyecto2;
									$_SESSION["codigos"][$contador_exitentes][5]=$cod_activ_obra2;
									$_SESSION["codigos"][$contador_exitentes][6]=$cod_partida2;
									$_SESSION["codigos"][$contador_exitentes][7]=$cod_generica2;
									$_SESSION["codigos"][$contador_exitentes][8]=$cod_especifica2;
									$_SESSION["codigos"][$contador_exitentes][9]=$cod_sub_espec2;
									$_SESSION["codigos"][$contador_exitentes][10]=$cod_auxiliar2;
				  	    		 }
						}//fin foreach
						if($contador_exitentes==0){
                           $sw = $this->cscd02_solicitud_encabezado->execute("UPDATE cscd02_solicitud_encabezado   set cod_obra='".$codigo_obra."' where ".$this->condicion()." and ano_solicitud='".$ano_solicitud."'         and  numero_solicitud='".$numero_solicitud."'    ");
                           $sw = $this->cscd02_solicitud_encabezado->execute("UPDATE cscd03_cotizacion_encabezado  set cod_obra='".$codigo_obra."' where ".$this->condicion()." and ano_cotizacion='".$ano_cotizacion."'       and  numero_cotizacion='".$numero_cotizacion."' and rif='".$rif."'   ");
                           $sw = $this->cscd02_solicitud_encabezado->execute("UPDATE cscd04_ordencompra_encabezado set cod_obra='".$codigo_obra."' where ".$this->condicion()." and ano_orden_compra='".$ano_orden_compra."'   and  numero_orden_compra='".$numero_orden_compra."'    ");
						   foreach($lista2 as $aux2){
				  	    	          $cod_sector          =   $aux2['cscd04_ordencompra_partidas']['cod_sector'];
									  $cod_programa        =   $aux2['cscd04_ordencompra_partidas']['cod_programa'];
									  $cod_sub_prog        =   $aux2['cscd04_ordencompra_partidas']['cod_sub_prog'];
									  $cod_proyecto        =   $aux2['cscd04_ordencompra_partidas']['cod_proyecto'];
									  $cod_activ_obra      =   $aux2['cscd04_ordencompra_partidas']['cod_activ_obra'];
									  $cod_partida         =   $aux2['cscd04_ordencompra_partidas']['cod_partida'];
									  $cod_generica        =   $aux2['cscd04_ordencompra_partidas']['cod_generica'];
									  $cod_especifica      =   $aux2['cscd04_ordencompra_partidas']['cod_especifica'];
									  $cod_sub_espec       =   $aux2['cscd04_ordencompra_partidas']['cod_sub_espec'];
									  $cod_auxiliar        =   $aux2['cscd04_ordencompra_partidas']['cod_auxiliar'];
									  $monto               =   $aux2['cscd04_ordencompra_partidas']['monto'];
									  $cond2               =   $this->condicion()." and ano='".$ano_obra."' and cod_sector='$cod_sector' and cod_programa='$cod_programa' and cod_sub_prog='$cod_sub_prog' and cod_proyecto='$cod_proyecto' and cod_activ_obra='$cod_activ_obra' and cod_partida='$cod_partida' and cod_generica='$cod_generica' and cod_especifica='$cod_especifica' and cod_sub_espec='$cod_sub_espec' and cod_auxiliar='$cod_auxiliar'";
	                                  $sql55               =   "UPDATE cfpd05 SET precompromiso_obras = precompromiso_obras - ".$monto." WHERE ".$cond2." ";
		                              $sw155               =   $this->cscd04_ordencompra_partidas->execute($sql55);


		                              $sql_aux = "cod_presi=".$cod_presi." and cod_entidad=".$cod_entidad." and cod_tipo_inst=".$cod_tipo_inst." and  cod_inst=".$cod_inst." and cod_dep=".$cod_dep." and ano_estimacion=".$ano." and upper(cod_obra)='".strtoupper($codigo_obra)."' and cod_sector=".$cod_sector."   and   cod_programa=".$cod_programa."   and   cod_sub_prog=".$cod_sub_prog."   and   cod_proyecto=".$cod_proyecto."   and   cod_activ_obra=".$cod_activ_obra."   and   cod_partida=".$cod_partida."   and   cod_generica=".$cod_generica."   and   cod_especifica=".$cod_especifica."   and   cod_sub_espec=".$cod_sub_espec."   and   cod_auxiliar=".$cod_auxiliar.";";
//						              $sw455 = $this->cscd04_ordencompra_partidas->execute("UPDATE cfpd07_obras_partidas SET monto_contratado=monto_contratado+".$monto." where ".$sql_aux);

							          $sql_aux = "cod_presi=".$cod_presi." and cod_entidad=".$cod_entidad." and cod_tipo_inst=".$cod_tipo_inst." and  cod_inst=".$cod_inst." and cod_dep=".$cod_dep." and ano_estimacion=".$ano." and upper(cod_obra)='".strtoupper($codigo_obra)."';";
//						              $sw455   = $this->cscd04_ordencompra_partidas->execute("UPDATE cfpd07_obras_cuerpo SET monto_contratado=monto_contratado+".$monto." where ".$sql_aux);


						  }//fin foreach
						   $this->set('Message_existe', 'Los datos fueron  guardados');
						}else{
								$this->set('msg_error', 'los siguientes códigos presupuestarios no estan registrados en la relación de obras');
								$this->set('codigos', $_SESSION["codigos"]);
						}//fin if
			}//fin foreach
  }//fin foreach







$lista_aux_2                 =  $this->cepd01_compromiso_cuerpo->findAll($this->condicion()." and ano_documento = ".$ano." "." and (cod_obra!='' and cod_obra!='0' and cod_obra IS NOT NULL) ");
foreach($lista_aux_2 as $aux_2){
  $this->Session->delete('codigos');
  $ano_obra                 =       $aux_2['cepd01_compromiso_cuerpo']['ano_documento'];
  $numero_documento         =       $aux_2['cepd01_compromiso_cuerpo']['numero_documento'];
  $codigo_obra              =       $aux_2['cepd01_compromiso_cuerpo']['cod_obra'];

  	       $lista                 = $this->cepd01_compromiso_cuerpo->findAll($this->condicion()." and ano_documento = ".$ano_obra." and numero_documento='".$numero_documento."' "." ");
	  	   $cfpd07_obras_partidas =  $this->cfpd07_obras_partidas->findAll($this->condicion()."   and ano_estimacion   = ".$ano."      and upper(cod_obra)='".strtoupper($codigo_obra)."' ", null, 'cod_sector,cod_programa,cod_sub_prog,cod_proyecto,cod_activ_obra, cod_partida,cod_generica,cod_especifica,cod_sub_espec,cod_auxiliar ASC');
	  	   foreach($lista as $aux){
	  	   	    $contador_exitentes  =  0;
				$ano_documento       =  $aux['cepd01_compromiso_cuerpo']['ano_documento'];
				$numero_documento    =  $aux['cepd01_compromiso_cuerpo']['numero_documento'];
                $lista2              =  $this->cepd01_compromiso_partidas->findAll($this->condicion()." and ano_documento = ".$ano_obra." and numero_documento='".$numero_documento."' ");
				  	    foreach($lista2 as $aux2){
				  	    	          $cod_sector2          =   $aux2['cepd01_compromiso_partidas']['cod_sector'];
									  $cod_programa2        =   $aux2['cepd01_compromiso_partidas']['cod_programa'];
									  $cod_sub_prog2        =   $aux2['cepd01_compromiso_partidas']['cod_sub_prog'];
									  $cod_proyecto2        =   $aux2['cepd01_compromiso_partidas']['cod_proyecto'];
									  $cod_activ_obra2      =   $aux2['cepd01_compromiso_partidas']['cod_activ_obra'];
									  $cod_partida2         =   $aux2['cepd01_compromiso_partidas']['cod_partida'];
									  $cod_generica2        =   $aux2['cepd01_compromiso_partidas']['cod_generica'];
									  $cod_especifica2      =   $aux2['cepd01_compromiso_partidas']['cod_especifica'];
									  $cod_sub_espec2       =   $aux2['cepd01_compromiso_partidas']['cod_sub_espec'];
									  $cod_auxiliar2        =   $aux2['cepd01_compromiso_partidas']['cod_auxiliar'];
									  $marca = 0;
				  	    		foreach($cfpd07_obras_partidas  as  $aux_cfpd07_obras_partidas){
									  $cod_sector          =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_sector'];
									  $cod_programa        =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_programa'];
									  $cod_sub_prog        =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_sub_prog'];
									  $cod_proyecto        =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_proyecto'];
									  $cod_activ_obra      =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_activ_obra'];
									  $cod_partida         =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_partida'];
									  $cod_generica        =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_generica'];
									  $cod_especifica      =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_especifica'];
									  $cod_sub_espec       =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_sub_espec'];
									  $cod_auxiliar        =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_auxiliar'];
										  if($cod_sector     == $cod_sector2     &&
										     $cod_programa   == $cod_programa2   &&
										     $cod_sub_prog   == $cod_sub_prog2   &&
										     $cod_proyecto   == $cod_proyecto2   &&
										     $cod_activ_obra == $cod_activ_obra2 &&
										     $cod_partida    == $cod_partida2    &&
										     $cod_generica   == $cod_generica2   &&
										     $cod_especifica == $cod_especifica2 &&
										     $cod_sub_espec  == $cod_sub_espec2  &&
										     $cod_auxiliar   == $cod_auxiliar2){
										     	$marca = 1;
										  }//fin if
				  	    		 }//fin foreach
				  	    		 if($marca==0){$contador_exitentes++;
									$_SESSION["codigos"][$contador_exitentes][0]=$ano_obra;
									$_SESSION["codigos"][$contador_exitentes][1]=$cod_sector2;
									$_SESSION["codigos"][$contador_exitentes][2]=$cod_programa2;
									$_SESSION["codigos"][$contador_exitentes][3]=$cod_sub_prog2;
									$_SESSION["codigos"][$contador_exitentes][4]=$cod_proyecto2;
									$_SESSION["codigos"][$contador_exitentes][5]=$cod_activ_obra2;
									$_SESSION["codigos"][$contador_exitentes][6]=$cod_partida2;
									$_SESSION["codigos"][$contador_exitentes][7]=$cod_generica2;
									$_SESSION["codigos"][$contador_exitentes][8]=$cod_especifica2;
									$_SESSION["codigos"][$contador_exitentes][9]=$cod_sub_espec2;
									$_SESSION["codigos"][$contador_exitentes][10]=$cod_auxiliar2;
				  	    		 }
						}//fin foreach
						if($contador_exitentes==0){
                           $sw = $this->cepd01_compromiso_cuerpo->execute("UPDATE cepd01_compromiso_cuerpo set cod_obra='".$codigo_obra."' where ".$this->condicion()." and ano_documento='".$ano_documento."'   and  numero_documento='".$numero_documento."'    ");
						   foreach($lista2 as $aux2){
				  	    	          $cod_sector          =   $aux2['cepd01_compromiso_partidas']['cod_sector'];
									  $cod_programa        =   $aux2['cepd01_compromiso_partidas']['cod_programa'];
									  $cod_sub_prog        =   $aux2['cepd01_compromiso_partidas']['cod_sub_prog'];
									  $cod_proyecto        =   $aux2['cepd01_compromiso_partidas']['cod_proyecto'];
									  $cod_activ_obra      =   $aux2['cepd01_compromiso_partidas']['cod_activ_obra'];
									  $cod_partida         =   $aux2['cepd01_compromiso_partidas']['cod_partida'];
									  $cod_generica        =   $aux2['cepd01_compromiso_partidas']['cod_generica'];
									  $cod_especifica      =   $aux2['cepd01_compromiso_partidas']['cod_especifica'];
									  $cod_sub_espec       =   $aux2['cepd01_compromiso_partidas']['cod_sub_espec'];
									  $cod_auxiliar        =   $aux2['cepd01_compromiso_partidas']['cod_auxiliar'];
									  $monto               =   $aux2['cepd01_compromiso_partidas']['monto'];
									  $cond2               =   $this->condicion()." and ano='".$ano_obra."' and cod_sector='$cod_sector' and cod_programa='$cod_programa' and cod_sub_prog='$cod_sub_prog' and cod_proyecto='$cod_proyecto' and cod_activ_obra='$cod_activ_obra' and cod_partida='$cod_partida' and cod_generica='$cod_generica' and cod_especifica='$cod_especifica' and cod_sub_espec='$cod_sub_espec' and cod_auxiliar='$cod_auxiliar'";
	                                  $sql55               =   "UPDATE cfpd05 SET precompromiso_obras = precompromiso_obras - ".$monto." WHERE ".$cond2." ";
		                              $sw155               =   $this->cepd01_compromiso_partidas->execute($sql55);

		                              $sql_aux = "cod_presi=".$cod_presi." and cod_entidad=".$cod_entidad." and cod_tipo_inst=".$cod_tipo_inst." and  cod_inst=".$cod_inst." and cod_dep=".$cod_dep." and ano_estimacion=".$ano." and upper(cod_obra)='".strtoupper($codigo_obra)."' and cod_sector=".$cod_sector."   and   cod_programa=".$cod_programa."   and   cod_sub_prog=".$cod_sub_prog."   and   cod_proyecto=".$cod_proyecto."   and   cod_activ_obra=".$cod_activ_obra."   and   cod_partida=".$cod_partida."   and   cod_generica=".$cod_generica."   and   cod_especifica=".$cod_especifica."   and   cod_sub_espec=".$cod_sub_espec."   and   cod_auxiliar=".$cod_auxiliar.";";
//						              $sw455 = $this->cscd04_ordencompra_partidas->execute("UPDATE cfpd07_obras_partidas SET monto_contratado=monto_contratado+".$monto." where ".$sql_aux);

							          $sql_aux = "cod_presi=".$cod_presi." and cod_entidad=".$cod_entidad." and cod_tipo_inst=".$cod_tipo_inst." and  cod_inst=".$cod_inst." and cod_dep=".$cod_dep." and ano_estimacion=".$ano." and upper(cod_obra)='".strtoupper($codigo_obra)."';";
//						              $sw455   = $this->cscd04_ordencompra_partidas->execute("UPDATE cfpd07_obras_cuerpo SET monto_contratado=monto_contratado+".$monto." where ".$sql_aux);



						  }//fin foreach
						  $this->set('Message_existe', 'Los datos fueron  guardados');
						}else{
								$this->set('msg_error', 'los siguientes códigos presupuestarios no estan registrados en la relación de obras');
								$this->set('codigos', $_SESSION["codigos"]);
						}//fin if
			}//fin foreach
  }//fin function
}//fin function











function actualizar_precompromiso_anulado($var1=null){

	set_time_limit(0);
  $this->layout="ajax";
  $cod_presi                =       $this->Session->read('SScodpresi');
  $cod_entidad              =       $this->Session->read('SScodentidad');
  $cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
  $cod_inst                 =       $this->Session->read('SScodinst');
  $cod_dep                  =       $this->Session->read('SScoddep');
  $ano                      =       $this->ano_ejecucion();


$lista_aux_2                 =  $this->cscd04_ordencompra_encabezado->findAll($this->condicion()." and ano_orden_compra = ".$ano." "." and (cod_obra!='' and cod_obra!='0' and cod_obra IS NOT NULL) and condicion_actividad=2");
foreach($lista_aux_2 as $aux_2){
	  $this->Session->delete('codigos');

  $ano_obra                 =       $aux_2['cscd04_ordencompra_encabezado']['ano_orden_compra'];
  $numero_documento         =       $aux_2['cscd04_ordencompra_encabezado']['numero_orden_compra'];
  $codigo_obra              =       $aux_2['cscd04_ordencompra_encabezado']['cod_obra'];

           $lista                 =  $this->cscd04_ordencompra_encabezado->findAll($this->condicion()." and ano_orden_compra = ".$ano_obra." and numero_orden_compra='".$numero_documento."' "." ");
	  	   $cfpd07_obras_partidas =  $this->cfpd07_obras_partidas->findAll($this->condicion()."        and ano_estimacion   = ".$ano."      and upper(cod_obra)='".strtoupper($codigo_obra)."' ", null, 'cod_sector,cod_programa,cod_sub_prog,cod_proyecto,cod_activ_obra, cod_partida,cod_generica,cod_especifica,cod_sub_espec,cod_auxiliar ASC');
	  	   foreach($lista as $aux){
	  	   	    $contador_exitentes  = 0;
				$rif                 = $aux['cscd04_ordencompra_encabezado']['rif'];
				$ano_orden_compra    = $aux['cscd04_ordencompra_encabezado']['ano_orden_compra'];
				$numero_orden_compra = $aux['cscd04_ordencompra_encabezado']['numero_orden_compra'];
				$ano_cotizacion      = $aux['cscd04_ordencompra_encabezado']['ano_cotizacion'];
				$numero_cotizacion   = $aux['cscd04_ordencompra_encabezado']['numero_cotizacion'];
				$lista3              = $this->cscd03_cotizacion_encabezado->findAll($this->condicion()." and ano_cotizacion = ".$ano_cotizacion." and numero_cotizacion='".$numero_cotizacion."' and rif='".$rif."' ");
                $ano_solicitud       = $lista3[0]["cscd03_cotizacion_encabezado"]["ano_solicitud"];
                $numero_solicitud    = $lista3[0]["cscd03_cotizacion_encabezado"]["numero_solicitud"];
                $lista2              = $this->cscd04_ordencompra_partidas->findAll($this->condicion()." and ano_orden_compra = ".$ano_obra." and numero_orden_compra='".$numero_documento."' ");
				  	    foreach($lista2 as $aux2){
				  	    	          $cod_sector2          =   $aux2['cscd04_ordencompra_partidas']['cod_sector'];
									  $cod_programa2        =   $aux2['cscd04_ordencompra_partidas']['cod_programa'];
									  $cod_sub_prog2        =   $aux2['cscd04_ordencompra_partidas']['cod_sub_prog'];
									  $cod_proyecto2        =   $aux2['cscd04_ordencompra_partidas']['cod_proyecto'];
									  $cod_activ_obra2      =   $aux2['cscd04_ordencompra_partidas']['cod_activ_obra'];
									  $cod_partida2         =   $aux2['cscd04_ordencompra_partidas']['cod_partida'];
									  $cod_generica2        =   $aux2['cscd04_ordencompra_partidas']['cod_generica'];
									  $cod_especifica2      =   $aux2['cscd04_ordencompra_partidas']['cod_especifica'];
									  $cod_sub_espec2       =   $aux2['cscd04_ordencompra_partidas']['cod_sub_espec'];
									  $cod_auxiliar2        =   $aux2['cscd04_ordencompra_partidas']['cod_auxiliar'];
									  $marca = 0;
				  	    		foreach($cfpd07_obras_partidas  as  $aux_cfpd07_obras_partidas){
									  $cod_sector          =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_sector'];
									  $cod_programa        =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_programa'];
									  $cod_sub_prog        =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_sub_prog'];
									  $cod_proyecto        =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_proyecto'];
									  $cod_activ_obra      =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_activ_obra'];
									  $cod_partida         =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_partida'];
									  $cod_generica        =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_generica'];
									  $cod_especifica      =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_especifica'];
									  $cod_sub_espec       =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_sub_espec'];
									  $cod_auxiliar        =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_auxiliar'];
										  if($cod_sector     == $cod_sector2     &&
										     $cod_programa   == $cod_programa2   &&
										     $cod_sub_prog   == $cod_sub_prog2   &&
										     $cod_proyecto   == $cod_proyecto2   &&
										     $cod_activ_obra == $cod_activ_obra2 &&
										     $cod_partida    == $cod_partida2    &&
										     $cod_generica   == $cod_generica2   &&
										     $cod_especifica == $cod_especifica2 &&
										     $cod_sub_espec  == $cod_sub_espec2  &&
										     $cod_auxiliar   == $cod_auxiliar2){
										     	$marca = 1;
										  }else{

										  }
				  	    		 }//fin foreach
				  	    		 if($marca==0){$contador_exitentes++;
									$_SESSION["codigos"][$contador_exitentes][0]=$ano_obra;
									$_SESSION["codigos"][$contador_exitentes][1]=$cod_sector2;
									$_SESSION["codigos"][$contador_exitentes][2]=$cod_programa2;
									$_SESSION["codigos"][$contador_exitentes][3]=$cod_sub_prog2;
									$_SESSION["codigos"][$contador_exitentes][4]=$cod_proyecto2;
									$_SESSION["codigos"][$contador_exitentes][5]=$cod_activ_obra2;
									$_SESSION["codigos"][$contador_exitentes][6]=$cod_partida2;
									$_SESSION["codigos"][$contador_exitentes][7]=$cod_generica2;
									$_SESSION["codigos"][$contador_exitentes][8]=$cod_especifica2;
									$_SESSION["codigos"][$contador_exitentes][9]=$cod_sub_espec2;
									$_SESSION["codigos"][$contador_exitentes][10]=$cod_auxiliar2;
				  	    		 }
						}//fin foreach
						if($contador_exitentes==0){
                           $sw = $this->cscd02_solicitud_encabezado->execute("UPDATE cscd02_solicitud_encabezado   set cod_obra='".$codigo_obra."' where ".$this->condicion()." and ano_solicitud='".$ano_solicitud."'         and  numero_solicitud='".$numero_solicitud."'    ");
                           $sw = $this->cscd02_solicitud_encabezado->execute("UPDATE cscd03_cotizacion_encabezado  set cod_obra='".$codigo_obra."' where ".$this->condicion()." and ano_cotizacion='".$ano_cotizacion."'       and  numero_cotizacion='".$numero_cotizacion."' and rif='".$rif."'   ");
                           $sw = $this->cscd02_solicitud_encabezado->execute("UPDATE cscd04_ordencompra_encabezado set cod_obra='".$codigo_obra."' where ".$this->condicion()." and ano_orden_compra='".$ano_orden_compra."'   and  numero_orden_compra='".$numero_orden_compra."'    ");
						   foreach($lista2 as $aux2){
				  	    	          $cod_sector          =   $aux2['cscd04_ordencompra_partidas']['cod_sector'];
									  $cod_programa        =   $aux2['cscd04_ordencompra_partidas']['cod_programa'];
									  $cod_sub_prog        =   $aux2['cscd04_ordencompra_partidas']['cod_sub_prog'];
									  $cod_proyecto        =   $aux2['cscd04_ordencompra_partidas']['cod_proyecto'];
									  $cod_activ_obra      =   $aux2['cscd04_ordencompra_partidas']['cod_activ_obra'];
									  $cod_partida         =   $aux2['cscd04_ordencompra_partidas']['cod_partida'];
									  $cod_generica        =   $aux2['cscd04_ordencompra_partidas']['cod_generica'];
									  $cod_especifica      =   $aux2['cscd04_ordencompra_partidas']['cod_especifica'];
									  $cod_sub_espec       =   $aux2['cscd04_ordencompra_partidas']['cod_sub_espec'];
									  $cod_auxiliar        =   $aux2['cscd04_ordencompra_partidas']['cod_auxiliar'];
									  $monto               =   $aux2['cscd04_ordencompra_partidas']['monto'];
									  $cond2               =   $this->condicion()." and ano='".$ano_obra."' and cod_sector='$cod_sector' and cod_programa='$cod_programa' and cod_sub_prog='$cod_sub_prog' and cod_proyecto='$cod_proyecto' and cod_activ_obra='$cod_activ_obra' and cod_partida='$cod_partida' and cod_generica='$cod_generica' and cod_especifica='$cod_especifica' and cod_sub_espec='$cod_sub_espec' and cod_auxiliar='$cod_auxiliar'";
	                                  $sql55               =   "UPDATE cfpd05 SET precompromiso_obras = precompromiso_obras + ".$monto." WHERE ".$cond2." ";
		                              $sw155               =   $this->cscd04_ordencompra_partidas->execute($sql55);


		                              $sql_aux = "cod_presi=".$cod_presi." and cod_entidad=".$cod_entidad." and cod_tipo_inst=".$cod_tipo_inst." and  cod_inst=".$cod_inst." and cod_dep=".$cod_dep." and ano_estimacion=".$ano." and upper(cod_obra)='".strtoupper($codigo_obra)."' and cod_sector=".$cod_sector."   and   cod_programa=".$cod_programa."   and   cod_sub_prog=".$cod_sub_prog."   and   cod_proyecto=".$cod_proyecto."   and   cod_activ_obra=".$cod_activ_obra."   and   cod_partida=".$cod_partida."   and   cod_generica=".$cod_generica."   and   cod_especifica=".$cod_especifica."   and   cod_sub_espec=".$cod_sub_espec."   and   cod_auxiliar=".$cod_auxiliar.";";
//						              $sw455 = $this->cscd04_ordencompra_partidas->execute("UPDATE cfpd07_obras_partidas SET monto_contratado=monto_contratado+".$monto." where ".$sql_aux);

							          $sql_aux = "cod_presi=".$cod_presi." and cod_entidad=".$cod_entidad." and cod_tipo_inst=".$cod_tipo_inst." and  cod_inst=".$cod_inst." and cod_dep=".$cod_dep." and ano_estimacion=".$ano." and upper(cod_obra)='".strtoupper($codigo_obra)."';";
//						              $sw455   = $this->cscd04_ordencompra_partidas->execute("UPDATE cfpd07_obras_cuerpo SET monto_contratado=monto_contratado+".$monto." where ".$sql_aux);


						  }//fin foreach
						   $this->set('Message_existe', 'Los datos fueron  guardados');
						}else{
								$this->set('msg_error', 'los siguientes códigos presupuestarios no estan registrados en la relación de obras');
								$this->set('codigos', $_SESSION["codigos"]);
						}//fin if
			}//fin foreach
  }//fin foreach







$lista_aux_2                 =  $this->cepd01_compromiso_cuerpo->findAll($this->condicion()." and ano_documento = ".$ano." "." and (cod_obra!='' and cod_obra!='0' and cod_obra IS NOT NULL) and condicion_actividad=2");
foreach($lista_aux_2 as $aux_2){
  $this->Session->delete('codigos');
  $ano_obra                 =       $aux_2['cepd01_compromiso_cuerpo']['ano_documento'];
  $numero_documento         =       $aux_2['cepd01_compromiso_cuerpo']['numero_documento'];
  $codigo_obra              =       $aux_2['cepd01_compromiso_cuerpo']['cod_obra'];

  	       $lista                 = $this->cepd01_compromiso_cuerpo->findAll($this->condicion()." and ano_documento = ".$ano_obra." and numero_documento='".$numero_documento."' "." ");
	  	   $cfpd07_obras_partidas =  $this->cfpd07_obras_partidas->findAll($this->condicion()."   and ano_estimacion   = ".$ano."      and upper(cod_obra)='".strtoupper($codigo_obra)."' ", null, 'cod_sector,cod_programa,cod_sub_prog,cod_proyecto,cod_activ_obra, cod_partida,cod_generica,cod_especifica,cod_sub_espec,cod_auxiliar ASC');
	  	   foreach($lista as $aux){
	  	   	    $contador_exitentes  =  0;
				$ano_documento       =  $aux['cepd01_compromiso_cuerpo']['ano_documento'];
				$numero_documento    =  $aux['cepd01_compromiso_cuerpo']['numero_documento'];
                $lista2              =  $this->cepd01_compromiso_partidas->findAll($this->condicion()." and ano_documento = ".$ano_obra." and numero_documento='".$numero_documento."' ");
				  	    foreach($lista2 as $aux2){
				  	    	          $cod_sector2          =   $aux2['cepd01_compromiso_partidas']['cod_sector'];
									  $cod_programa2        =   $aux2['cepd01_compromiso_partidas']['cod_programa'];
									  $cod_sub_prog2        =   $aux2['cepd01_compromiso_partidas']['cod_sub_prog'];
									  $cod_proyecto2        =   $aux2['cepd01_compromiso_partidas']['cod_proyecto'];
									  $cod_activ_obra2      =   $aux2['cepd01_compromiso_partidas']['cod_activ_obra'];
									  $cod_partida2         =   $aux2['cepd01_compromiso_partidas']['cod_partida'];
									  $cod_generica2        =   $aux2['cepd01_compromiso_partidas']['cod_generica'];
									  $cod_especifica2      =   $aux2['cepd01_compromiso_partidas']['cod_especifica'];
									  $cod_sub_espec2       =   $aux2['cepd01_compromiso_partidas']['cod_sub_espec'];
									  $cod_auxiliar2        =   $aux2['cepd01_compromiso_partidas']['cod_auxiliar'];
									  $marca = 0;
				  	    		foreach($cfpd07_obras_partidas  as  $aux_cfpd07_obras_partidas){
									  $cod_sector          =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_sector'];
									  $cod_programa        =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_programa'];
									  $cod_sub_prog        =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_sub_prog'];
									  $cod_proyecto        =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_proyecto'];
									  $cod_activ_obra      =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_activ_obra'];
									  $cod_partida         =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_partida'];
									  $cod_generica        =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_generica'];
									  $cod_especifica      =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_especifica'];
									  $cod_sub_espec       =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_sub_espec'];
									  $cod_auxiliar        =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_auxiliar'];
										  if($cod_sector     == $cod_sector2     &&
										     $cod_programa   == $cod_programa2   &&
										     $cod_sub_prog   == $cod_sub_prog2   &&
										     $cod_proyecto   == $cod_proyecto2   &&
										     $cod_activ_obra == $cod_activ_obra2 &&
										     $cod_partida    == $cod_partida2    &&
										     $cod_generica   == $cod_generica2   &&
										     $cod_especifica == $cod_especifica2 &&
										     $cod_sub_espec  == $cod_sub_espec2  &&
										     $cod_auxiliar   == $cod_auxiliar2){
										     	$marca = 1;
										  }//fin if
				  	    		 }//fin foreach
				  	    		 if($marca==0){$contador_exitentes++;
									$_SESSION["codigos"][$contador_exitentes][0]=$ano_obra;
									$_SESSION["codigos"][$contador_exitentes][1]=$cod_sector2;
									$_SESSION["codigos"][$contador_exitentes][2]=$cod_programa2;
									$_SESSION["codigos"][$contador_exitentes][3]=$cod_sub_prog2;
									$_SESSION["codigos"][$contador_exitentes][4]=$cod_proyecto2;
									$_SESSION["codigos"][$contador_exitentes][5]=$cod_activ_obra2;
									$_SESSION["codigos"][$contador_exitentes][6]=$cod_partida2;
									$_SESSION["codigos"][$contador_exitentes][7]=$cod_generica2;
									$_SESSION["codigos"][$contador_exitentes][8]=$cod_especifica2;
									$_SESSION["codigos"][$contador_exitentes][9]=$cod_sub_espec2;
									$_SESSION["codigos"][$contador_exitentes][10]=$cod_auxiliar2;
				  	    		 }
						}//fin foreach
						if($contador_exitentes==0){
                           $sw = $this->cepd01_compromiso_cuerpo->execute("UPDATE cepd01_compromiso_cuerpo set cod_obra='".$codigo_obra."' where ".$this->condicion()." and ano_documento='".$ano_documento."'   and  numero_documento='".$numero_documento."'    ");
						   foreach($lista2 as $aux2){
				  	    	          $cod_sector          =   $aux2['cepd01_compromiso_partidas']['cod_sector'];
									  $cod_programa        =   $aux2['cepd01_compromiso_partidas']['cod_programa'];
									  $cod_sub_prog        =   $aux2['cepd01_compromiso_partidas']['cod_sub_prog'];
									  $cod_proyecto        =   $aux2['cepd01_compromiso_partidas']['cod_proyecto'];
									  $cod_activ_obra      =   $aux2['cepd01_compromiso_partidas']['cod_activ_obra'];
									  $cod_partida         =   $aux2['cepd01_compromiso_partidas']['cod_partida'];
									  $cod_generica        =   $aux2['cepd01_compromiso_partidas']['cod_generica'];
									  $cod_especifica      =   $aux2['cepd01_compromiso_partidas']['cod_especifica'];
									  $cod_sub_espec       =   $aux2['cepd01_compromiso_partidas']['cod_sub_espec'];
									  $cod_auxiliar        =   $aux2['cepd01_compromiso_partidas']['cod_auxiliar'];
									  $monto               =   $aux2['cepd01_compromiso_partidas']['monto'];
									  $cond2               =   $this->condicion()." and ano='".$ano_obra."' and cod_sector='$cod_sector' and cod_programa='$cod_programa' and cod_sub_prog='$cod_sub_prog' and cod_proyecto='$cod_proyecto' and cod_activ_obra='$cod_activ_obra' and cod_partida='$cod_partida' and cod_generica='$cod_generica' and cod_especifica='$cod_especifica' and cod_sub_espec='$cod_sub_espec' and cod_auxiliar='$cod_auxiliar'";
	                                  $sql55               =   "UPDATE cfpd05 SET precompromiso_obras = precompromiso_obras + ".$monto." WHERE ".$cond2."";
		                              $sw155               =   $this->cepd01_compromiso_partidas->execute($sql55);

		                              $sql_aux = "cod_presi=".$cod_presi." and cod_entidad=".$cod_entidad." and cod_tipo_inst=".$cod_tipo_inst." and  cod_inst=".$cod_inst." and cod_dep=".$cod_dep." and ano_estimacion=".$ano." and upper(cod_obra)='".strtoupper($codigo_obra)."' and cod_sector=".$cod_sector."   and   cod_programa=".$cod_programa."   and   cod_sub_prog=".$cod_sub_prog."   and   cod_proyecto=".$cod_proyecto."   and   cod_activ_obra=".$cod_activ_obra."   and   cod_partida=".$cod_partida."   and   cod_generica=".$cod_generica."   and   cod_especifica=".$cod_especifica."   and   cod_sub_espec=".$cod_sub_espec."   and   cod_auxiliar=".$cod_auxiliar.";";
//						              $sw455 = $this->cscd04_ordencompra_partidas->execute("UPDATE cfpd07_obras_partidas SET monto_contratado=monto_contratado+".$monto." where ".$sql_aux);

							          $sql_aux = "cod_presi=".$cod_presi." and cod_entidad=".$cod_entidad." and cod_tipo_inst=".$cod_tipo_inst." and  cod_inst=".$cod_inst." and cod_dep=".$cod_dep." and ano_estimacion=".$ano." and upper(cod_obra)='".strtoupper($codigo_obra)."';";
//						              $sw455   = $this->cscd04_ordencompra_partidas->execute("UPDATE cfpd07_obras_cuerpo SET monto_contratado=monto_contratado+".$monto." where ".$sql_aux);



						  }//fin foreach
						  $this->set('Message_existe', 'Los datos fueron  guardados');
						}else{
								$this->set('msg_error', 'los siguientes códigos presupuestarios no estan registrados en la relación de obras');
								$this->set('codigos', $_SESSION["codigos"]);
						}//fin if
			}//fin foreach
  }//fin function
}//fin function












function actualizar_monto_contratado(){

	set_time_limit(0);

	$this->layout="ajax";

	$sql55               =   "UPDATE cfpd07_obras_partidas SET monto_contratado = 0  WHERE cod_dep='".$cod_dep."' and ano_estimacion=2010; ";
	$sw155               =   $this->cepd01_compromiso_partidas->execute($sql55);

	$sql55               =   "UPDATE cfpd07_obras_cuerpo SET monto_contratado = 0  WHERE cod_dep='".$cod_dep."' and ano_estimacion=2010; ";
	$sw155               =   $this->cepd01_compromiso_partidas->execute($sql55);


  $cod_presi                =       $this->Session->read('SScodpresi');
  $cod_entidad              =       $this->Session->read('SScodentidad');
  $cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
  $cod_inst                 =       $this->Session->read('SScodinst');
  $cod_dep                  =       '".$cod_dep."';
  $ano                      =       $this->ano_ejecucion();


$lista_aux_2                 =  $this->cscd04_ordencompra_encabezado->findAll($this->condicionNDEP()." and cod_dep='".$cod_dep."' and ano_orden_compra = ".$ano." "." and (cod_obra!='' and cod_obra!='0' and cod_obra IS NOT NULL) and condicion_actividad=1");
foreach($lista_aux_2 as $aux_2){
	  $this->Session->delete('codigos');

  $ano_obra                 =       $aux_2['cscd04_ordencompra_encabezado']['ano_orden_compra'];
  $numero_documento         =       $aux_2['cscd04_ordencompra_encabezado']['numero_orden_compra'];
  $codigo_obra              =       $aux_2['cscd04_ordencompra_encabezado']['cod_obra'];

           $lista                 =  $this->cscd04_ordencompra_encabezado->findAll($this->condicionNDEP()." and cod_dep='".$cod_dep."' and ano_orden_compra = ".$ano_obra." and numero_orden_compra='".$numero_documento."' "." ");
	  	   $cfpd07_obras_partidas =  $this->cfpd07_obras_partidas->findAll($this->condicionNDEP()."         and cod_dep='".$cod_dep."' and ano_estimacion   = ".$ano."      and upper(cod_obra)='".strtoupper($codigo_obra)."' ", null, 'cod_sector,cod_programa,cod_sub_prog,cod_proyecto,cod_activ_obra, cod_partida,cod_generica,cod_especifica,cod_sub_espec,cod_auxiliar ASC');
	  	   foreach($lista as $aux){
	  	   	    $contador_exitentes  = 0;
				$rif                 = $aux['cscd04_ordencompra_encabezado']['rif'];
				$ano_orden_compra    = $aux['cscd04_ordencompra_encabezado']['ano_orden_compra'];
				$numero_orden_compra = $aux['cscd04_ordencompra_encabezado']['numero_orden_compra'];
				$ano_cotizacion      = $aux['cscd04_ordencompra_encabezado']['ano_cotizacion'];
				$numero_cotizacion   = $aux['cscd04_ordencompra_encabezado']['numero_cotizacion'];
				$lista3              = $this->cscd03_cotizacion_encabezado->findAll($this->condicionNDEP()." and cod_dep='".$cod_dep."' and ano_cotizacion = ".$ano_cotizacion." and numero_cotizacion='".$numero_cotizacion."' and rif='".$rif."' ");
                $ano_solicitud       = $lista3[0]["cscd03_cotizacion_encabezado"]["ano_solicitud"];
                $numero_solicitud    = $lista3[0]["cscd03_cotizacion_encabezado"]["numero_solicitud"];
                $lista2              = $this->cscd04_ordencompra_partidas->findAll($this->condicionNDEP()." and cod_dep='".$cod_dep."' and ano_orden_compra = ".$ano_obra." and numero_orden_compra='".$numero_documento."' ");
				  	    foreach($lista2 as $aux2){
				  	    	          $cod_sector2          =   $aux2['cscd04_ordencompra_partidas']['cod_sector'];
									  $cod_programa2        =   $aux2['cscd04_ordencompra_partidas']['cod_programa'];
									  $cod_sub_prog2        =   $aux2['cscd04_ordencompra_partidas']['cod_sub_prog'];
									  $cod_proyecto2        =   $aux2['cscd04_ordencompra_partidas']['cod_proyecto'];
									  $cod_activ_obra2      =   $aux2['cscd04_ordencompra_partidas']['cod_activ_obra'];
									  $cod_partida2         =   $aux2['cscd04_ordencompra_partidas']['cod_partida'];
									  $cod_generica2        =   $aux2['cscd04_ordencompra_partidas']['cod_generica'];
									  $cod_especifica2      =   $aux2['cscd04_ordencompra_partidas']['cod_especifica'];
									  $cod_sub_espec2       =   $aux2['cscd04_ordencompra_partidas']['cod_sub_espec'];
									  $cod_auxiliar2        =   $aux2['cscd04_ordencompra_partidas']['cod_auxiliar'];
									  $marca = 0;
				  	    		foreach($cfpd07_obras_partidas  as  $aux_cfpd07_obras_partidas){
									  $cod_sector          =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_sector'];
									  $cod_programa        =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_programa'];
									  $cod_sub_prog        =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_sub_prog'];
									  $cod_proyecto        =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_proyecto'];
									  $cod_activ_obra      =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_activ_obra'];
									  $cod_partida         =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_partida'];
									  $cod_generica        =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_generica'];
									  $cod_especifica      =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_especifica'];
									  $cod_sub_espec       =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_sub_espec'];
									  $cod_auxiliar        =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_auxiliar'];
										  if($cod_sector     == $cod_sector2     &&
										     $cod_programa   == $cod_programa2   &&
										     $cod_sub_prog   == $cod_sub_prog2   &&
										     $cod_proyecto   == $cod_proyecto2   &&
										     $cod_activ_obra == $cod_activ_obra2 &&
										     $cod_partida    == $cod_partida2    &&
										     $cod_generica   == $cod_generica2   &&
										     $cod_especifica == $cod_especifica2 &&
										     $cod_sub_espec  == $cod_sub_espec2  &&
										     $cod_auxiliar   == $cod_auxiliar2){
										     	$marca = 1;
										  }else{

										  }
				  	    		 }//fin foreach
				  	    		 if($marca==0){$contador_exitentes++;
									$_SESSION["codigos"][$contador_exitentes][0]=$ano_obra;
									$_SESSION["codigos"][$contador_exitentes][1]=$cod_sector2;
									$_SESSION["codigos"][$contador_exitentes][2]=$cod_programa2;
									$_SESSION["codigos"][$contador_exitentes][3]=$cod_sub_prog2;
									$_SESSION["codigos"][$contador_exitentes][4]=$cod_proyecto2;
									$_SESSION["codigos"][$contador_exitentes][5]=$cod_activ_obra2;
									$_SESSION["codigos"][$contador_exitentes][6]=$cod_partida2;
									$_SESSION["codigos"][$contador_exitentes][7]=$cod_generica2;
									$_SESSION["codigos"][$contador_exitentes][8]=$cod_especifica2;
									$_SESSION["codigos"][$contador_exitentes][9]=$cod_sub_espec2;
									$_SESSION["codigos"][$contador_exitentes][10]=$cod_auxiliar2;
				  	    		 }
						}//fin foreach
						if($contador_exitentes==0){
                           $sw = $this->cscd02_solicitud_encabezado->execute("UPDATE cscd02_solicitud_encabezado   set cod_obra='".$codigo_obra."' where ".$this->condicionNDEP()." and cod_dep='".$cod_dep."' and ano_solicitud='".$ano_solicitud."'         and  numero_solicitud='".$numero_solicitud."'    ");
                           $sw = $this->cscd02_solicitud_encabezado->execute("UPDATE cscd03_cotizacion_encabezado  set cod_obra='".$codigo_obra."' where ".$this->condicionNDEP()." and cod_dep='".$cod_dep."' and ano_cotizacion='".$ano_cotizacion."'       and  numero_cotizacion='".$numero_cotizacion."' and rif='".$rif."'   ");
                           $sw = $this->cscd02_solicitud_encabezado->execute("UPDATE cscd04_ordencompra_encabezado set cod_obra='".$codigo_obra."' where ".$this->condicionNDEP()." and cod_dep='".$cod_dep."' and ano_orden_compra='".$ano_orden_compra."'   and  numero_orden_compra='".$numero_orden_compra."'    ");
						   foreach($lista2 as $aux2){
				  	    	          $cod_sector          =   $aux2['cscd04_ordencompra_partidas']['cod_sector'];
									  $cod_programa        =   $aux2['cscd04_ordencompra_partidas']['cod_programa'];
									  $cod_sub_prog        =   $aux2['cscd04_ordencompra_partidas']['cod_sub_prog'];
									  $cod_proyecto        =   $aux2['cscd04_ordencompra_partidas']['cod_proyecto'];
									  $cod_activ_obra      =   $aux2['cscd04_ordencompra_partidas']['cod_activ_obra'];
									  $cod_partida         =   $aux2['cscd04_ordencompra_partidas']['cod_partida'];
									  $cod_generica        =   $aux2['cscd04_ordencompra_partidas']['cod_generica'];
									  $cod_especifica      =   $aux2['cscd04_ordencompra_partidas']['cod_especifica'];
									  $cod_sub_espec       =   $aux2['cscd04_ordencompra_partidas']['cod_sub_espec'];
									  $cod_auxiliar        =   $aux2['cscd04_ordencompra_partidas']['cod_auxiliar'];
									  $monto               =   $aux2['cscd04_ordencompra_partidas']['monto'];
									  $cond2               =   $this->condicionNDEP()." and cod_dep='".$cod_dep."' and ano='".$ano_obra."' and cod_sector='$cod_sector' and cod_programa='$cod_programa' and cod_sub_prog='$cod_sub_prog' and cod_proyecto='$cod_proyecto' and cod_activ_obra='$cod_activ_obra' and cod_partida='$cod_partida' and cod_generica='$cod_generica' and cod_especifica='$cod_especifica' and cod_sub_espec='$cod_sub_espec' and cod_auxiliar='$cod_auxiliar'";
	                                  $sql55               =   "UPDATE cfpd05 SET precompromiso_obras = precompromiso_obras + ".$monto." WHERE ".$cond2." ";
//		                              $sw155               =   $this->cscd04_ordencompra_partidas->execute($sql55);

		                              $sql_aux = "cod_presi=".$cod_presi." and cod_entidad=".$cod_entidad." and cod_tipo_inst=".$cod_tipo_inst." and  cod_inst=".$cod_inst." and cod_dep=".$cod_dep." and ano_estimacion=".$ano." and upper(cod_obra)='".strtoupper($codigo_obra)."' and cod_sector=".$cod_sector."   and   cod_programa=".$cod_programa."   and   cod_sub_prog=".$cod_sub_prog."   and   cod_proyecto=".$cod_proyecto."   and   cod_activ_obra=".$cod_activ_obra."   and   cod_partida=".$cod_partida."   and   cod_generica=".$cod_generica."   and   cod_especifica=".$cod_especifica."   and   cod_sub_espec=".$cod_sub_espec."   and   cod_auxiliar=".$cod_auxiliar.";";
						              $sw455 = $this->cscd04_ordencompra_partidas->execute("UPDATE cfpd07_obras_partidas SET monto_contratado=monto_contratado+".$monto." where ".$sql_aux);

							          $sql_aux = "cod_presi=".$cod_presi." and cod_entidad=".$cod_entidad." and cod_tipo_inst=".$cod_tipo_inst." and  cod_inst=".$cod_inst." and cod_dep=".$cod_dep." and ano_estimacion=".$ano." and upper(cod_obra)='".strtoupper($codigo_obra)."';";
						              $sw455   = $this->cscd04_ordencompra_partidas->execute("UPDATE cfpd07_obras_cuerpo SET monto_contratado=monto_contratado+".$monto." where ".$sql_aux);


						  }//fin foreach
						   $this->set('Message_existe', 'Los datos fueron  guardados');
						}else{
								$this->set('msg_error', 'los siguientes códigos presupuestarios no estan registrados en la relación de obras');
								$this->set('codigos', $_SESSION["codigos"]);
						}//fin if
			}//fin foreach
  }//fin foreach







$lista_aux_2                 =  $this->cepd01_compromiso_cuerpo->findAll($this->condicionNDEP()." and cod_dep='".$cod_dep."' and ano_documento = ".$ano." "." and (cod_obra!='' and cod_obra!='0' and cod_obra IS NOT NULL) and condicion_actividad=1");
foreach($lista_aux_2 as $aux_2){
  $this->Session->delete('codigos');
  $ano_obra                 =       $aux_2['cepd01_compromiso_cuerpo']['ano_documento'];
  $numero_documento         =       $aux_2['cepd01_compromiso_cuerpo']['numero_documento'];
  $codigo_obra              =       $aux_2['cepd01_compromiso_cuerpo']['cod_obra'];

  	       $lista                 = $this->cepd01_compromiso_cuerpo->findAll($this->condicionNDEP()." and cod_dep='".$cod_dep."' and ano_documento = ".$ano_obra." and numero_documento='".$numero_documento."' "." ");
	  	   $cfpd07_obras_partidas =  $this->cfpd07_obras_partidas->findAll($this->condicionNDEP()." and cod_dep='".$cod_dep."'   and ano_estimacion   = ".$ano."      and upper(cod_obra)='".strtoupper($codigo_obra)."' ", null, 'cod_sector,cod_programa,cod_sub_prog,cod_proyecto,cod_activ_obra, cod_partida,cod_generica,cod_especifica,cod_sub_espec,cod_auxiliar ASC');
	  	   foreach($lista as $aux){
	  	   	    $contador_exitentes  =  0;
				$ano_documento       =  $aux['cepd01_compromiso_cuerpo']['ano_documento'];
				$numero_documento    =  $aux['cepd01_compromiso_cuerpo']['numero_documento'];
                $lista2              =  $this->cepd01_compromiso_partidas->findAll($this->condicionNDEP()." and cod_dep='".$cod_dep."' and ano_documento = ".$ano_obra." and numero_documento='".$numero_documento."' ");
				  	    foreach($lista2 as $aux2){
				  	    	          $cod_sector2          =   $aux2['cepd01_compromiso_partidas']['cod_sector'];
									  $cod_programa2        =   $aux2['cepd01_compromiso_partidas']['cod_programa'];
									  $cod_sub_prog2        =   $aux2['cepd01_compromiso_partidas']['cod_sub_prog'];
									  $cod_proyecto2        =   $aux2['cepd01_compromiso_partidas']['cod_proyecto'];
									  $cod_activ_obra2      =   $aux2['cepd01_compromiso_partidas']['cod_activ_obra'];
									  $cod_partida2         =   $aux2['cepd01_compromiso_partidas']['cod_partida'];
									  $cod_generica2        =   $aux2['cepd01_compromiso_partidas']['cod_generica'];
									  $cod_especifica2      =   $aux2['cepd01_compromiso_partidas']['cod_especifica'];
									  $cod_sub_espec2       =   $aux2['cepd01_compromiso_partidas']['cod_sub_espec'];
									  $cod_auxiliar2        =   $aux2['cepd01_compromiso_partidas']['cod_auxiliar'];
									  $marca = 0;
				  	    		foreach($cfpd07_obras_partidas  as  $aux_cfpd07_obras_partidas){
									  $cod_sector          =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_sector'];
									  $cod_programa        =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_programa'];
									  $cod_sub_prog        =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_sub_prog'];
									  $cod_proyecto        =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_proyecto'];
									  $cod_activ_obra      =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_activ_obra'];
									  $cod_partida         =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_partida'];
									  $cod_generica        =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_generica'];
									  $cod_especifica      =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_especifica'];
									  $cod_sub_espec       =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_sub_espec'];
									  $cod_auxiliar        =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_auxiliar'];
										  if($cod_sector     == $cod_sector2     &&
										     $cod_programa   == $cod_programa2   &&
										     $cod_sub_prog   == $cod_sub_prog2   &&
										     $cod_proyecto   == $cod_proyecto2   &&
										     $cod_activ_obra == $cod_activ_obra2 &&
										     $cod_partida    == $cod_partida2    &&
										     $cod_generica   == $cod_generica2   &&
										     $cod_especifica == $cod_especifica2 &&
										     $cod_sub_espec  == $cod_sub_espec2  &&
										     $cod_auxiliar   == $cod_auxiliar2){
										     	$marca = 1;
										  }//fin if
				  	    		 }//fin foreach
				  	    		 if($marca==0){$contador_exitentes++;
									$_SESSION["codigos"][$contador_exitentes][0]=$ano_obra;
									$_SESSION["codigos"][$contador_exitentes][1]=$cod_sector2;
									$_SESSION["codigos"][$contador_exitentes][2]=$cod_programa2;
									$_SESSION["codigos"][$contador_exitentes][3]=$cod_sub_prog2;
									$_SESSION["codigos"][$contador_exitentes][4]=$cod_proyecto2;
									$_SESSION["codigos"][$contador_exitentes][5]=$cod_activ_obra2;
									$_SESSION["codigos"][$contador_exitentes][6]=$cod_partida2;
									$_SESSION["codigos"][$contador_exitentes][7]=$cod_generica2;
									$_SESSION["codigos"][$contador_exitentes][8]=$cod_especifica2;
									$_SESSION["codigos"][$contador_exitentes][9]=$cod_sub_espec2;
									$_SESSION["codigos"][$contador_exitentes][10]=$cod_auxiliar2;
				  	    		 }
						}//fin foreach
						if($contador_exitentes==0){
                           $sw = $this->cepd01_compromiso_cuerpo->execute("UPDATE cepd01_compromiso_cuerpo set cod_obra='".$codigo_obra."' where ".$this->condicionNDEP()." and cod_dep='".$cod_dep."' and ano_documento='".$ano_documento."'   and  numero_documento='".$numero_documento."'    ");
						   foreach($lista2 as $aux2){
				  	    	          $cod_sector          =   $aux2['cepd01_compromiso_partidas']['cod_sector'];
									  $cod_programa        =   $aux2['cepd01_compromiso_partidas']['cod_programa'];
									  $cod_sub_prog        =   $aux2['cepd01_compromiso_partidas']['cod_sub_prog'];
									  $cod_proyecto        =   $aux2['cepd01_compromiso_partidas']['cod_proyecto'];
									  $cod_activ_obra      =   $aux2['cepd01_compromiso_partidas']['cod_activ_obra'];
									  $cod_partida         =   $aux2['cepd01_compromiso_partidas']['cod_partida'];
									  $cod_generica        =   $aux2['cepd01_compromiso_partidas']['cod_generica'];
									  $cod_especifica      =   $aux2['cepd01_compromiso_partidas']['cod_especifica'];
									  $cod_sub_espec       =   $aux2['cepd01_compromiso_partidas']['cod_sub_espec'];
									  $cod_auxiliar        =   $aux2['cepd01_compromiso_partidas']['cod_auxiliar'];
									  $monto               =   $aux2['cepd01_compromiso_partidas']['monto'];
									  $cond2               =   $this->condicionNDEP()." and cod_dep='".$cod_dep."' and ano='".$ano_obra."' and cod_sector='$cod_sector' and cod_programa='$cod_programa' and cod_sub_prog='$cod_sub_prog' and cod_proyecto='$cod_proyecto' and cod_activ_obra='$cod_activ_obra' and cod_partida='$cod_partida' and cod_generica='$cod_generica' and cod_especifica='$cod_especifica' and cod_sub_espec='$cod_sub_espec' and cod_auxiliar='$cod_auxiliar'";
	                                  $sql55               =   "UPDATE cfpd05 SET precompromiso_obras = precompromiso_obras + ".$monto." WHERE ".$cond2."";
//		                              $sw155               =   $this->cepd01_compromiso_partidas->execute($sql55);

		                              $sql_aux = "cod_presi=".$cod_presi." and cod_entidad=".$cod_entidad." and cod_tipo_inst=".$cod_tipo_inst." and  cod_inst=".$cod_inst." and cod_dep=".$cod_dep." and ano_estimacion=".$ano." and upper(cod_obra)='".strtoupper($codigo_obra)."' and cod_sector=".$cod_sector."   and   cod_programa=".$cod_programa."   and   cod_sub_prog=".$cod_sub_prog."   and   cod_proyecto=".$cod_proyecto."   and   cod_activ_obra=".$cod_activ_obra."   and   cod_partida=".$cod_partida."   and   cod_generica=".$cod_generica."   and   cod_especifica=".$cod_especifica."   and   cod_sub_espec=".$cod_sub_espec."   and   cod_auxiliar=".$cod_auxiliar.";";
						              $sw455 = $this->cscd04_ordencompra_partidas->execute("UPDATE cfpd07_obras_partidas SET monto_contratado=monto_contratado+".$monto." where ".$sql_aux);

							          $sql_aux = "cod_presi=".$cod_presi." and cod_entidad=".$cod_entidad." and cod_tipo_inst=".$cod_tipo_inst." and  cod_inst=".$cod_inst." and cod_dep=".$cod_dep." and ano_estimacion=".$ano." and upper(cod_obra)='".strtoupper($codigo_obra)."';";
						              $sw455   = $this->cscd04_ordencompra_partidas->execute("UPDATE cfpd07_obras_cuerpo SET monto_contratado=monto_contratado+".$monto." where ".$sql_aux);



						  }//fin foreach
						  $this->set('Message_existe', 'Los datos fueron  guardados');
						}else{
								$this->set('msg_error', 'los siguientes códigos presupuestarios no estan registrados en la relación de obras');
								$this->set('codigos', $_SESSION["codigos"]);
						}//fin if
			}//fin foreach
  }//fin function











}

























function actualizar_precompromiso_resta_1($var1=null){
  $this->layout="ajax";
  $cod_presi                =       $this->Session->read('SScodpresi');
  $cod_entidad              =       $this->Session->read('SScodentidad');
  $cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
  $cod_inst                 =       $this->Session->read('SScodinst');
  $cod_dep                  =       $this->Session->read('SScoddep');
  $ano                      =       $this->ano_ejecucion();

$lista_aux_2                 =  $this->cscd04_ordencompra_encabezado->findAll($this->condicion()." and ano_orden_compra = ".$ano." "." and (cod_obra!='' and cod_obra!='0' and cod_obra IS NOT NULL)  and condicion_actividad=1");
foreach($lista_aux_2 as $aux_2){
	  $this->Session->delete('codigos');

  $ano_obra                 =       $aux_2['cscd04_ordencompra_encabezado']['ano_orden_compra'];
  $numero_documento         =       $aux_2['cscd04_ordencompra_encabezado']['numero_orden_compra'];
  $codigo_obra              =       $aux_2['cscd04_ordencompra_encabezado']['cod_obra'];

           $lista                 =  $this->cscd04_ordencompra_encabezado->findAll($this->condicion()." and ano_orden_compra = ".$ano_obra." and numero_orden_compra='".$numero_documento."' "." ");
	  	   $cfpd07_obras_partidas =  $this->cfpd07_obras_partidas->findAll($this->condicion()."        and ano_estimacion   = ".$ano."      and upper(cod_obra)='".strtoupper($codigo_obra)."' ", null, 'cod_sector,cod_programa,cod_sub_prog,cod_proyecto,cod_activ_obra, cod_partida,cod_generica,cod_especifica,cod_sub_espec,cod_auxiliar ASC');
	  	   foreach($lista as $aux){
	  	   	    $contador_exitentes  = 0;
				$rif                 = $aux['cscd04_ordencompra_encabezado']['rif'];
				$ano_orden_compra    = $aux['cscd04_ordencompra_encabezado']['ano_orden_compra'];
				$numero_orden_compra = $aux['cscd04_ordencompra_encabezado']['numero_orden_compra'];
				$ano_cotizacion      = $aux['cscd04_ordencompra_encabezado']['ano_cotizacion'];
				$numero_cotizacion   = $aux['cscd04_ordencompra_encabezado']['numero_cotizacion'];
				$lista3              = $this->cscd03_cotizacion_encabezado->findAll($this->condicion()." and ano_cotizacion = ".$ano_cotizacion." and numero_cotizacion='".$numero_cotizacion."' and rif='".$rif."' ");
                $ano_solicitud       = $lista3[0]["cscd03_cotizacion_encabezado"]["ano_solicitud"];
                $numero_solicitud    = $lista3[0]["cscd03_cotizacion_encabezado"]["numero_solicitud"];
                $lista2              = $this->cscd04_ordencompra_partidas->findAll($this->condicion()." and ano_orden_compra = ".$ano_obra." and numero_orden_compra='".$numero_documento."' ");
				  	    foreach($lista2 as $aux2){
				  	    	          $cod_sector2          =   $aux2['cscd04_ordencompra_partidas']['cod_sector'];
									  $cod_programa2        =   $aux2['cscd04_ordencompra_partidas']['cod_programa'];
									  $cod_sub_prog2        =   $aux2['cscd04_ordencompra_partidas']['cod_sub_prog'];
									  $cod_proyecto2        =   $aux2['cscd04_ordencompra_partidas']['cod_proyecto'];
									  $cod_activ_obra2      =   $aux2['cscd04_ordencompra_partidas']['cod_activ_obra'];
									  $cod_partida2         =   $aux2['cscd04_ordencompra_partidas']['cod_partida'];
									  $cod_generica2        =   $aux2['cscd04_ordencompra_partidas']['cod_generica'];
									  $cod_especifica2      =   $aux2['cscd04_ordencompra_partidas']['cod_especifica'];
									  $cod_sub_espec2       =   $aux2['cscd04_ordencompra_partidas']['cod_sub_espec'];
									  $cod_auxiliar2        =   $aux2['cscd04_ordencompra_partidas']['cod_auxiliar'];
									  $marca = 0;
				  	    		foreach($cfpd07_obras_partidas  as  $aux_cfpd07_obras_partidas){
									  $cod_sector          =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_sector'];
									  $cod_programa        =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_programa'];
									  $cod_sub_prog        =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_sub_prog'];
									  $cod_proyecto        =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_proyecto'];
									  $cod_activ_obra      =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_activ_obra'];
									  $cod_partida         =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_partida'];
									  $cod_generica        =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_generica'];
									  $cod_especifica      =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_especifica'];
									  $cod_sub_espec       =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_sub_espec'];
									  $cod_auxiliar        =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_auxiliar'];
										  if($cod_sector     == $cod_sector2     &&
										     $cod_programa   == $cod_programa2   &&
										     $cod_sub_prog   == $cod_sub_prog2   &&
										     $cod_proyecto   == $cod_proyecto2   &&
										     $cod_activ_obra == $cod_activ_obra2 &&
										     $cod_partida    == $cod_partida2    &&
										     $cod_generica   == $cod_generica2   &&
										     $cod_especifica == $cod_especifica2 &&
										     $cod_sub_espec  == $cod_sub_espec2  &&
										     $cod_auxiliar   == $cod_auxiliar2){
										     	$marca = 1;
										  }else{

										  }
				  	    		 }//fin foreach
				  	    		 if($marca==0){$contador_exitentes++;
									$_SESSION["codigos"][$contador_exitentes][0]=$ano_obra;
									$_SESSION["codigos"][$contador_exitentes][1]=$cod_sector2;
									$_SESSION["codigos"][$contador_exitentes][2]=$cod_programa2;
									$_SESSION["codigos"][$contador_exitentes][3]=$cod_sub_prog2;
									$_SESSION["codigos"][$contador_exitentes][4]=$cod_proyecto2;
									$_SESSION["codigos"][$contador_exitentes][5]=$cod_activ_obra2;
									$_SESSION["codigos"][$contador_exitentes][6]=$cod_partida2;
									$_SESSION["codigos"][$contador_exitentes][7]=$cod_generica2;
									$_SESSION["codigos"][$contador_exitentes][8]=$cod_especifica2;
									$_SESSION["codigos"][$contador_exitentes][9]=$cod_sub_espec2;
									$_SESSION["codigos"][$contador_exitentes][10]=$cod_auxiliar2;
				  	    		 }
						}//fin foreach
						if($contador_exitentes==0){
                           $sw = $this->cscd02_solicitud_encabezado->execute("UPDATE cscd02_solicitud_encabezado   set cod_obra='".$codigo_obra."' where ".$this->condicion()." and ano_solicitud='".$ano_solicitud."'         and  numero_solicitud='".$numero_solicitud."'    ");
                           $sw = $this->cscd02_solicitud_encabezado->execute("UPDATE cscd03_cotizacion_encabezado  set cod_obra='".$codigo_obra."' where ".$this->condicion()." and ano_cotizacion='".$ano_cotizacion."'       and  numero_cotizacion='".$numero_cotizacion."' and rif='".$rif."'   ");
                           $sw = $this->cscd02_solicitud_encabezado->execute("UPDATE cscd04_ordencompra_encabezado set cod_obra='".$codigo_obra."' where ".$this->condicion()." and ano_orden_compra='".$ano_orden_compra."'   and  numero_orden_compra='".$numero_orden_compra."'    ");
						   foreach($lista2 as $aux2){
				  	    	          $cod_sector          =   $aux2['cscd04_ordencompra_partidas']['cod_sector'];
									  $cod_programa        =   $aux2['cscd04_ordencompra_partidas']['cod_programa'];
									  $cod_sub_prog        =   $aux2['cscd04_ordencompra_partidas']['cod_sub_prog'];
									  $cod_proyecto        =   $aux2['cscd04_ordencompra_partidas']['cod_proyecto'];
									  $cod_activ_obra      =   $aux2['cscd04_ordencompra_partidas']['cod_activ_obra'];
									  $cod_partida         =   $aux2['cscd04_ordencompra_partidas']['cod_partida'];
									  $cod_generica        =   $aux2['cscd04_ordencompra_partidas']['cod_generica'];
									  $cod_especifica      =   $aux2['cscd04_ordencompra_partidas']['cod_especifica'];
									  $cod_sub_espec       =   $aux2['cscd04_ordencompra_partidas']['cod_sub_espec'];
									  $cod_auxiliar        =   $aux2['cscd04_ordencompra_partidas']['cod_auxiliar'];
									  $monto               =   $aux2['cscd04_ordencompra_partidas']['monto'];
									  $cond2               =   $this->condicion()." and ano='".$ano_obra."' and cod_sector='$cod_sector' and cod_programa='$cod_programa' and cod_sub_prog='$cod_sub_prog' and cod_proyecto='$cod_proyecto' and cod_activ_obra='$cod_activ_obra' and cod_partida='$cod_partida' and cod_generica='$cod_generica' and cod_especifica='$cod_especifica' and cod_sub_espec='$cod_sub_espec' and cod_auxiliar='$cod_auxiliar'";
	                                  $sql55               =   "UPDATE cfpd05 SET precompromiso_congelado = precompromiso_congelado + ".$monto." WHERE ".$cond2." ";
		                              $sw155               =   $this->cscd04_ordencompra_partidas->execute($sql55);


		                              $sql_aux = "cod_presi=".$cod_presi." and cod_entidad=".$cod_entidad." and cod_tipo_inst=".$cod_tipo_inst." and  cod_inst=".$cod_inst." and cod_dep=".$cod_dep." and ano_estimacion=".$ano." and upper(cod_obra)='".strtoupper($codigo_obra)."' and cod_sector=".$cod_sector."   and   cod_programa=".$cod_programa."   and   cod_sub_prog=".$cod_sub_prog."   and   cod_proyecto=".$cod_proyecto."   and   cod_activ_obra=".$cod_activ_obra."   and   cod_partida=".$cod_partida."   and   cod_generica=".$cod_generica."   and   cod_especifica=".$cod_especifica."   and   cod_sub_espec=".$cod_sub_espec."   and   cod_auxiliar=".$cod_auxiliar.";";
//						              $sw455 = $this->cscd04_ordencompra_partidas->execute("UPDATE cfpd07_obras_partidas SET monto_contratado=monto_contratado+".$monto." where ".$sql_aux);

							          $sql_aux = "cod_presi=".$cod_presi." and cod_entidad=".$cod_entidad." and cod_tipo_inst=".$cod_tipo_inst." and  cod_inst=".$cod_inst." and cod_dep=".$cod_dep." and ano_estimacion=".$ano." and upper(cod_obra)='".strtoupper($codigo_obra)."';";
//						              $sw455   = $this->cscd04_ordencompra_partidas->execute("UPDATE cfpd07_obras_cuerpo SET monto_contratado=monto_contratado+".$monto." where ".$sql_aux);


						  }//fin foreach
						   $this->set('Message_existe', 'Los datos fueron  guardados');
						}else{
								$this->set('msg_error', 'los siguientes códigos presupuestarios no estan registrados en la relación de obras');
								$this->set('codigos', $_SESSION["codigos"]);
						}//fin if
			}//fin foreach
  }//fin foreach







$lista_aux_2                 =  $this->cepd01_compromiso_cuerpo->findAll($this->condicion()." and ano_documento = ".$ano." "." and (cod_obra!='' and cod_obra!='0' and cod_obra IS NOT NULL) and condicion_actividad=1");
foreach($lista_aux_2 as $aux_2){
  $this->Session->delete('codigos');
  $ano_obra                 =       $aux_2['cepd01_compromiso_cuerpo']['ano_documento'];
  $numero_documento         =       $aux_2['cepd01_compromiso_cuerpo']['numero_documento'];
  $codigo_obra              =       $aux_2['cepd01_compromiso_cuerpo']['cod_obra'];

  	       $lista                 = $this->cepd01_compromiso_cuerpo->findAll($this->condicion()." and ano_documento = ".$ano_obra." and numero_documento='".$numero_documento."' "." ");
	  	   $cfpd07_obras_partidas =  $this->cfpd07_obras_partidas->findAll($this->condicion()."   and ano_estimacion   = ".$ano."      and upper(cod_obra)='".strtoupper($codigo_obra)."' ", null, 'cod_sector,cod_programa,cod_sub_prog,cod_proyecto,cod_activ_obra, cod_partida,cod_generica,cod_especifica,cod_sub_espec,cod_auxiliar ASC');
	  	   foreach($lista as $aux){
	  	   	    $contador_exitentes  =  0;
				$ano_documento       =  $aux['cepd01_compromiso_cuerpo']['ano_documento'];
				$numero_documento    =  $aux['cepd01_compromiso_cuerpo']['numero_documento'];
                $lista2              =  $this->cepd01_compromiso_partidas->findAll($this->condicion()." and ano_documento = ".$ano_obra." and numero_documento='".$numero_documento."' ");
				  	    foreach($lista2 as $aux2){
				  	    	          $cod_sector2          =   $aux2['cepd01_compromiso_partidas']['cod_sector'];
									  $cod_programa2        =   $aux2['cepd01_compromiso_partidas']['cod_programa'];
									  $cod_sub_prog2        =   $aux2['cepd01_compromiso_partidas']['cod_sub_prog'];
									  $cod_proyecto2        =   $aux2['cepd01_compromiso_partidas']['cod_proyecto'];
									  $cod_activ_obra2      =   $aux2['cepd01_compromiso_partidas']['cod_activ_obra'];
									  $cod_partida2         =   $aux2['cepd01_compromiso_partidas']['cod_partida'];
									  $cod_generica2        =   $aux2['cepd01_compromiso_partidas']['cod_generica'];
									  $cod_especifica2      =   $aux2['cepd01_compromiso_partidas']['cod_especifica'];
									  $cod_sub_espec2       =   $aux2['cepd01_compromiso_partidas']['cod_sub_espec'];
									  $cod_auxiliar2        =   $aux2['cepd01_compromiso_partidas']['cod_auxiliar'];
									  $marca = 0;
				  	    		foreach($cfpd07_obras_partidas  as  $aux_cfpd07_obras_partidas){
									  $cod_sector          =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_sector'];
									  $cod_programa        =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_programa'];
									  $cod_sub_prog        =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_sub_prog'];
									  $cod_proyecto        =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_proyecto'];
									  $cod_activ_obra      =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_activ_obra'];
									  $cod_partida         =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_partida'];
									  $cod_generica        =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_generica'];
									  $cod_especifica      =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_especifica'];
									  $cod_sub_espec       =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_sub_espec'];
									  $cod_auxiliar        =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_auxiliar'];
										  if($cod_sector     == $cod_sector2     &&
										     $cod_programa   == $cod_programa2   &&
										     $cod_sub_prog   == $cod_sub_prog2   &&
										     $cod_proyecto   == $cod_proyecto2   &&
										     $cod_activ_obra == $cod_activ_obra2 &&
										     $cod_partida    == $cod_partida2    &&
										     $cod_generica   == $cod_generica2   &&
										     $cod_especifica == $cod_especifica2 &&
										     $cod_sub_espec  == $cod_sub_espec2  &&
										     $cod_auxiliar   == $cod_auxiliar2){
										     	$marca = 1;
										  }//fin if
				  	    		 }//fin foreach
				  	    		 if($marca==0){$contador_exitentes++;
									$_SESSION["codigos"][$contador_exitentes][0]=$ano_obra;
									$_SESSION["codigos"][$contador_exitentes][1]=$cod_sector2;
									$_SESSION["codigos"][$contador_exitentes][2]=$cod_programa2;
									$_SESSION["codigos"][$contador_exitentes][3]=$cod_sub_prog2;
									$_SESSION["codigos"][$contador_exitentes][4]=$cod_proyecto2;
									$_SESSION["codigos"][$contador_exitentes][5]=$cod_activ_obra2;
									$_SESSION["codigos"][$contador_exitentes][6]=$cod_partida2;
									$_SESSION["codigos"][$contador_exitentes][7]=$cod_generica2;
									$_SESSION["codigos"][$contador_exitentes][8]=$cod_especifica2;
									$_SESSION["codigos"][$contador_exitentes][9]=$cod_sub_espec2;
									$_SESSION["codigos"][$contador_exitentes][10]=$cod_auxiliar2;
				  	    		 }
						}//fin foreach
						if($contador_exitentes==0){
                           $sw = $this->cepd01_compromiso_cuerpo->execute("UPDATE cepd01_compromiso_cuerpo set cod_obra='".$codigo_obra."' where ".$this->condicion()." and ano_documento='".$ano_documento."'   and  numero_documento='".$numero_documento."'    ");
						   foreach($lista2 as $aux2){
				  	    	          $cod_sector          =   $aux2['cepd01_compromiso_partidas']['cod_sector'];
									  $cod_programa        =   $aux2['cepd01_compromiso_partidas']['cod_programa'];
									  $cod_sub_prog        =   $aux2['cepd01_compromiso_partidas']['cod_sub_prog'];
									  $cod_proyecto        =   $aux2['cepd01_compromiso_partidas']['cod_proyecto'];
									  $cod_activ_obra      =   $aux2['cepd01_compromiso_partidas']['cod_activ_obra'];
									  $cod_partida         =   $aux2['cepd01_compromiso_partidas']['cod_partida'];
									  $cod_generica        =   $aux2['cepd01_compromiso_partidas']['cod_generica'];
									  $cod_especifica      =   $aux2['cepd01_compromiso_partidas']['cod_especifica'];
									  $cod_sub_espec       =   $aux2['cepd01_compromiso_partidas']['cod_sub_espec'];
									  $cod_auxiliar        =   $aux2['cepd01_compromiso_partidas']['cod_auxiliar'];
									  $monto               =   $aux2['cepd01_compromiso_partidas']['monto'];
									  $cond2               =   $this->condicion()." and ano='".$ano_obra."' and cod_sector='$cod_sector' and cod_programa='$cod_programa' and cod_sub_prog='$cod_sub_prog' and cod_proyecto='$cod_proyecto' and cod_activ_obra='$cod_activ_obra' and cod_partida='$cod_partida' and cod_generica='$cod_generica' and cod_especifica='$cod_especifica' and cod_sub_espec='$cod_sub_espec' and cod_auxiliar='$cod_auxiliar'";
	                                  $sql55               =   "UPDATE cfpd05 SET precompromiso_congelado = precompromiso_congelado + ".$monto." WHERE ".$cond2."";
		                              $sw155               =   $this->cepd01_compromiso_partidas->execute($sql55);

		                              $sql_aux = "cod_presi=".$cod_presi." and cod_entidad=".$cod_entidad." and cod_tipo_inst=".$cod_tipo_inst." and  cod_inst=".$cod_inst." and cod_dep=".$cod_dep." and ano_estimacion=".$ano." and upper(cod_obra)='".strtoupper($codigo_obra)."' and cod_sector=".$cod_sector."   and   cod_programa=".$cod_programa."   and   cod_sub_prog=".$cod_sub_prog."   and   cod_proyecto=".$cod_proyecto."   and   cod_activ_obra=".$cod_activ_obra."   and   cod_partida=".$cod_partida."   and   cod_generica=".$cod_generica."   and   cod_especifica=".$cod_especifica."   and   cod_sub_espec=".$cod_sub_espec."   and   cod_auxiliar=".$cod_auxiliar.";";
//						              $sw455 = $this->cscd04_ordencompra_partidas->execute("UPDATE cfpd07_obras_partidas SET monto_contratado=monto_contratado+".$monto." where ".$sql_aux);

							          $sql_aux = "cod_presi=".$cod_presi." and cod_entidad=".$cod_entidad." and cod_tipo_inst=".$cod_tipo_inst." and  cod_inst=".$cod_inst." and cod_dep=".$cod_dep." and ano_estimacion=".$ano." and upper(cod_obra)='".strtoupper($codigo_obra)."';";
//						              $sw455   = $this->cscd04_ordencompra_partidas->execute("UPDATE cfpd07_obras_cuerpo SET monto_contratado=monto_contratado+".$monto." where ".$sql_aux);



						  }//fin foreach
						  $this->set('Message_existe', 'Los datos fueron  guardados');
						}else{
								$this->set('msg_error', 'los siguientes códigos presupuestarios no estan registrados en la relación de obras');
								$this->set('codigos', $_SESSION["codigos"]);
						}//fin if
			}//fin foreach
  }//fin function
}//fin function







function actualizar_precompromiso_resta_2($var1=null){
  $this->layout="ajax";
  $cod_presi                =       $this->Session->read('SScodpresi');
  $cod_entidad              =       $this->Session->read('SScodentidad');
  $cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
  $cod_inst                 =       $this->Session->read('SScodinst');
  $cod_dep                  =       $this->Session->read('SScoddep');
  $ano                      =       $this->ano_ejecucion();


$cfpd07_obras_partidas =  $this->cfpd07_obras_partidas->findAll($this->condicion()."        and ano_estimacion   = ".$ano." ", null, 'cod_sector,cod_programa,cod_sub_prog,cod_proyecto,cod_activ_obra, cod_partida,cod_generica,cod_especifica,cod_sub_espec,cod_auxiliar ASC');

	  	  foreach($cfpd07_obras_partidas  as  $aux_cfpd07_obras_partidas){
									  $cod_sector          =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_sector'];
									  $cod_programa        =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_programa'];
									  $cod_sub_prog        =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_sub_prog'];
									  $cod_proyecto        =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_proyecto'];
									  $cod_activ_obra      =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_activ_obra'];
									  $cod_partida         =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_partida'];
									  $cod_generica        =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_generica'];
									  $cod_especifica      =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_especifica'];
									  $cod_sub_espec       =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_sub_espec'];
									  $cod_auxiliar        =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_auxiliar'];
									  $monto               =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['monto'];

								      $cond2               =   $this->condicion()." and ano='".$ano."' and cod_sector='$cod_sector' and cod_programa='$cod_programa' and cod_sub_prog='$cod_sub_prog' and cod_proyecto='$cod_proyecto' and cod_activ_obra='$cod_activ_obra' and cod_partida='$cod_partida' and cod_generica='$cod_generica' and cod_especifica='$cod_especifica' and cod_sub_espec='$cod_sub_espec' and cod_auxiliar='$cod_auxiliar'";
	                                  $sql55               =   "UPDATE cfpd05 SET precompromiso_congelado = precompromiso_congelado - ".$monto." WHERE ".$cond2." ";
		                              $sw155               =   $this->cscd04_ordencompra_partidas->execute($sql55);

		  }//fin foreach







}//fin function


















function actualizar_cod_obra1($cod_dep=null){
	$this->layout="ajax";
	set_time_limit(0);
	ini_set("memory_limit","2000M");

	if($cod_dep!=null){

		$cod_presi      =  $this->Session->read('SScodpresi');
		$cod_entidad    =  $this->Session->read('SScodentidad');
		$cod_tipo_inst  =  $this->Session->read('SScodtipoinst');
		$cod_inst       =  $this->Session->read('SScodinst');
		$cod_dep_aux    =  $this->Session->read('SScoddep');
		$ano            =  $this->ano_ejecucion();
		$lista_aux_2    =  $this->cscd04_ordencompra_encabezado->findAll($this->condicionNDEP()." and cod_dep='".$cod_dep."' and ano_orden_compra = ".$ano." "." and (cod_obra!='' and cod_obra!='0' and cod_obra IS NOT NULL) and condicion_actividad=1");
		foreach($lista_aux_2 as $aux_2){
			$rif                 = $aux_2['cscd04_ordencompra_encabezado']['rif'];
			$ano_orden_compra    = $aux_2['cscd04_ordencompra_encabezado']['ano_orden_compra'];
			$numero_orden_compra = $aux_2['cscd04_ordencompra_encabezado']['numero_orden_compra'];
			$ano_cotizacion      = $aux_2['cscd04_ordencompra_encabezado']['ano_cotizacion'];
			$numero_cotizacion   = $aux_2['cscd04_ordencompra_encabezado']['numero_cotizacion'];
			$codigo_obra         = $aux_2['cscd04_ordencompra_encabezado']['cod_obra'];
			$sw = $this->cscd02_solicitud_encabezado->execute("UPDATE cscd03_cotizacion_encabezado  set cod_obra='".$codigo_obra."' where ".$this->condicionNDEP()." and cod_dep='".$cod_dep."' and ano_cotizacion='".$ano_cotizacion."'       and  numero_cotizacion='".$numero_cotizacion."' and rif='".$rif."'   ");
		}
	}

$this->render("actualizar_precompromiso");

}





function actualizar_cod_obra2($cod_dep=null){
	$this->layout="ajax";
	set_time_limit(0);
	ini_set("memory_limit","2000M");

	if($cod_dep!=null){

		$cod_presi      =  $this->Session->read('SScodpresi');
		$cod_entidad    =  $this->Session->read('SScodentidad');
		$cod_tipo_inst  =  $this->Session->read('SScodtipoinst');
		$cod_inst       =  $this->Session->read('SScodinst');
		$cod_dep_aux    =  $this->Session->read('SScoddep');
		$ano            =  $this->ano_ejecucion();
		$lista_aux_2    =  $this->cscd04_ordencompra_encabezado->findAll($this->condicionNDEP()." and cod_dep='".$cod_dep."' and ano_orden_compra = ".$ano." "." and (cod_obra!='' and cod_obra!='0' and cod_obra IS NOT NULL) and condicion_actividad=1");
		foreach($lista_aux_2 as $aux_2){

			$lista3              = $this->cscd03_cotizacion_encabezado->findAll($this->condicionNDEP()." and cod_dep='".$cod_dep."' and ano_cotizacion = ".$ano_cotizacion." and numero_cotizacion='".$numero_cotizacion."' and rif='".$rif."' ");
            $ano_solicitud       = $lista3[0]["cscd03_cotizacion_encabezado"]["ano_solicitud"];
            $numero_solicitud    = $lista3[0]["cscd03_cotizacion_encabezado"]["numero_solicitud"];

			$sw = $this->cscd02_solicitud_encabezado->execute("UPDATE cscd02_solicitud_encabezado   set cod_obra='".$codigo_obra."' where ".$this->condicionNDEP()." and cod_dep='".$cod_dep."' and ano_solicitud='".$ano_solicitud."'         and  numero_solicitud='".$numero_solicitud."'    ");
			$sw = $this->cscd02_solicitud_encabezado->execute("UPDATE cscd03_cotizacion_encabezado  set cod_obra='".$codigo_obra."' where ".$this->condicionNDEP()." and cod_dep='".$cod_dep."' and ano_cotizacion='".$ano_cotizacion."'       and  numero_cotizacion='".$numero_cotizacion."' and rif='".$rif."'   ");
		}
	}

$this->render("actualizar_precompromiso");

}




function actualizar_cod_obra3($cod_dep=null){
	$this->layout="ajax";
	set_time_limit(0);
	ini_set("memory_limit","2000M");

	if($cod_dep!=null){

		$cod_presi      =  $this->Session->read('SScodpresi');
		$cod_entidad    =  $this->Session->read('SScodentidad');
		$cod_tipo_inst  =  $this->Session->read('SScodtipoinst');
		$cod_inst       =  $this->Session->read('SScodinst');
		$cod_dep_aux    =  $this->Session->read('SScoddep');
		$ano            =  $this->ano_ejecucion();
		$lista_aux_2    =  $this->cscd02_solicitud_encabezado->findAll($this->condicionNDEP()." and cod_dep='".$cod_dep."' and ano_solicitud = ".$ano." "." and (cod_obra!='' and cod_obra!='0' and cod_obra IS NOT NULL)");
	    foreach($lista_aux_2 as $aux_2){

            $rif                = $aux_2['cscd02_solicitud_encabezado']['rif'];
	    	$ano_cotizacion     = $aux_2['cscd02_solicitud_encabezado']['ano_cotizacion'];
			$numero_cotizacion  = $aux_2['cscd02_solicitud_encabezado']['numero_cotizacion'];
			$cod_obra           = $aux_2['cscd02_solicitud_encabezado']['cod_obra'];

			$sw = $this->cscd02_solicitud_encabezado->execute("UPDATE cscd03_cotizacion_encabezado  set cod_obra='".$cod_obra."' where ".$this->condicionNDEP()." and cod_dep='".$cod_dep."' and ano_cotizacion='".$ano_cotizacion."'       and  numero_cotizacion='".$numero_cotizacion."' and rif='".$rif."'   ");


	    }
	}

$this->render("actualizar_precompromiso");

}


function actualizar_precompromiso($cod_dep=null){

	$this->layout="ajax";
	set_time_limit(0);
	ini_set("memory_limit","2000M");

	if($cod_dep!=null){

		  $cod_presi                =       $this->Session->read('SScodpresi');
		  $cod_entidad              =       $this->Session->read('SScodentidad');
		  $cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
		  $cod_inst                 =       $this->Session->read('SScodinst');
		  $cod_dep_aux              =       $this->Session->read('SScoddep');
		  $ano                      =       $this->ano_ejecucion();


		  ////////////////////////PARTE I///////////////////////////////////////
											$sql55               =   "UPDATE cfpd07_obras_partidas SET monto_contratado = 0, aumento_obras = 0, disminucion_obras = 0  WHERE ".$this->condicionNDEP()." and cod_dep='".$cod_dep."' and ano_estimacion='".$ano."'; ";
											$sw155               =   $this->cepd01_compromiso_partidas->execute($sql55);

											$sql55               =   "UPDATE cfpd07_obras_cuerpo SET monto_contratado = 0, aumento_obras = 0, disminucion_obras = 0  WHERE ".$this->condicionNDEP()." and cod_dep='".$cod_dep."' and ano_estimacion='".$ano."'; ";
											$sw155               =   $this->cepd01_compromiso_partidas->execute($sql55);


										$lista_aux_2                 =  $this->cscd04_ordencompra_encabezado->findAll($this->condicionNDEP()." and cod_dep='".$cod_dep."' and ano_orden_compra = ".$ano." "." and (cod_obra!='' and cod_obra!='0' and cod_obra IS NOT NULL) and condicion_actividad=1");
										foreach($lista_aux_2 as $aux_2){
											  $this->Session->delete('codigos');

										  $ano_obra                 =       $aux_2['cscd04_ordencompra_encabezado']['ano_orden_compra'];
										  $numero_documento         =       $aux_2['cscd04_ordencompra_encabezado']['numero_orden_compra'];
										  $codigo_obra              =       $aux_2['cscd04_ordencompra_encabezado']['cod_obra'];

										           $lista                 =  $this->cscd04_ordencompra_encabezado->findAll($this->condicionNDEP()." and cod_dep='".$cod_dep."' and ano_orden_compra = ".$ano_obra." and numero_orden_compra='".$numero_documento."' "." ");
											  	   $cfpd07_obras_partidas =  $this->cfpd07_obras_partidas->findAll($this->condicionNDEP()."         and cod_dep='".$cod_dep."' and ano_estimacion   = ".$ano."      and upper(cod_obra)='".strtoupper($codigo_obra)."' ", null, 'cod_sector,cod_programa,cod_sub_prog,cod_proyecto,cod_activ_obra, cod_partida,cod_generica,cod_especifica,cod_sub_espec,cod_auxiliar ASC');
											  	   foreach($lista as $aux){
											  	   	    $contador_exitentes  = 0;
														$rif                 = $aux['cscd04_ordencompra_encabezado']['rif'];
														$ano_orden_compra    = $aux['cscd04_ordencompra_encabezado']['ano_orden_compra'];
														$numero_orden_compra = $aux['cscd04_ordencompra_encabezado']['numero_orden_compra'];
														$ano_cotizacion      = $aux['cscd04_ordencompra_encabezado']['ano_cotizacion'];
														$numero_cotizacion   = $aux['cscd04_ordencompra_encabezado']['numero_cotizacion'];
														$lista3              = $this->cscd03_cotizacion_encabezado->findAll($this->condicionNDEP()." and cod_dep='".$cod_dep."' and ano_cotizacion = ".$ano_cotizacion." and numero_cotizacion='".$numero_cotizacion."' and rif='".$rif."' ");
										                $ano_solicitud       = $lista3[0]["cscd03_cotizacion_encabezado"]["ano_solicitud"];
										                $numero_solicitud    = $lista3[0]["cscd03_cotizacion_encabezado"]["numero_solicitud"];


										                if(!isset($lista3[0]["cscd03_cotizacion_encabezado"]["numero_solicitud"])){
										                	echo $this->condicionNDEP()." and cod_dep='".$cod_dep."' and ano_cotizacion = ".$ano_cotizacion." and numero_cotizacion='".$numero_cotizacion."' and rif='".$rif."' <br>";
										                }

										                $lista2              = $this->cscd04_ordencompra_partidas->findAll($this->condicionNDEP()." and cod_dep='".$cod_dep."' and ano_orden_compra = ".$ano_obra." and numero_orden_compra='".$numero_documento."' ");
														  	    foreach($lista2 as $aux2){
														  	    	          $cod_sector2          =   $aux2['cscd04_ordencompra_partidas']['cod_sector'];
																			  $cod_programa2        =   $aux2['cscd04_ordencompra_partidas']['cod_programa'];
																			  $cod_sub_prog2        =   $aux2['cscd04_ordencompra_partidas']['cod_sub_prog'];
																			  $cod_proyecto2        =   $aux2['cscd04_ordencompra_partidas']['cod_proyecto'];
																			  $cod_activ_obra2      =   $aux2['cscd04_ordencompra_partidas']['cod_activ_obra'];
																			  $cod_partida2         =   $aux2['cscd04_ordencompra_partidas']['cod_partida'];
																			  $cod_generica2        =   $aux2['cscd04_ordencompra_partidas']['cod_generica'];
																			  $cod_especifica2      =   $aux2['cscd04_ordencompra_partidas']['cod_especifica'];
																			  $cod_sub_espec2       =   $aux2['cscd04_ordencompra_partidas']['cod_sub_espec'];
																			  $cod_auxiliar2        =   $aux2['cscd04_ordencompra_partidas']['cod_auxiliar'];
																			  $marca = 0;
														  	    		foreach($cfpd07_obras_partidas  as  $aux_cfpd07_obras_partidas){
																			  $cod_sector          =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_sector'];
																			  $cod_programa        =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_programa'];
																			  $cod_sub_prog        =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_sub_prog'];
																			  $cod_proyecto        =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_proyecto'];
																			  $cod_activ_obra      =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_activ_obra'];
																			  $cod_partida         =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_partida'];
																			  $cod_generica        =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_generica'];
																			  $cod_especifica      =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_especifica'];
																			  $cod_sub_espec       =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_sub_espec'];
																			  $cod_auxiliar        =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_auxiliar'];
																				  if($cod_sector     == $cod_sector2     &&
																				     $cod_programa   == $cod_programa2   &&
																				     $cod_sub_prog   == $cod_sub_prog2   &&
																				     $cod_proyecto   == $cod_proyecto2   &&
																				     $cod_activ_obra == $cod_activ_obra2 &&
																				     $cod_partida    == $cod_partida2    &&
																				     $cod_generica   == $cod_generica2   &&
																				     $cod_especifica == $cod_especifica2 &&
																				     $cod_sub_espec  == $cod_sub_espec2  &&
																				     $cod_auxiliar   == $cod_auxiliar2){
																				     	$marca = 1;
																				  }else{

																				  }
														  	    		 }//fin foreach
														  	    		 if($marca==0){$contador_exitentes++;
																			$_SESSION["codigos"][$contador_exitentes][0]=$ano_obra;
																			$_SESSION["codigos"][$contador_exitentes][1]=$cod_sector2;
																			$_SESSION["codigos"][$contador_exitentes][2]=$cod_programa2;
																			$_SESSION["codigos"][$contador_exitentes][3]=$cod_sub_prog2;
																			$_SESSION["codigos"][$contador_exitentes][4]=$cod_proyecto2;
																			$_SESSION["codigos"][$contador_exitentes][5]=$cod_activ_obra2;
																			$_SESSION["codigos"][$contador_exitentes][6]=$cod_partida2;
																			$_SESSION["codigos"][$contador_exitentes][7]=$cod_generica2;
																			$_SESSION["codigos"][$contador_exitentes][8]=$cod_especifica2;
																			$_SESSION["codigos"][$contador_exitentes][9]=$cod_sub_espec2;
																			$_SESSION["codigos"][$contador_exitentes][10]=$cod_auxiliar2;
														  	    		 }
																}//fin foreach
																if($contador_exitentes==0){
										                           $sw = $this->cscd02_solicitud_encabezado->execute("UPDATE cscd02_solicitud_encabezado   set cod_obra='".$codigo_obra."' where ".$this->condicionNDEP()." and cod_dep='".$cod_dep."' and ano_solicitud='".$ano_solicitud."'         and  numero_solicitud='".$numero_solicitud."'    ");
										                           $sw = $this->cscd02_solicitud_encabezado->execute("UPDATE cscd03_cotizacion_encabezado  set cod_obra='".$codigo_obra."' where ".$this->condicionNDEP()." and cod_dep='".$cod_dep."' and ano_cotizacion='".$ano_cotizacion."'       and  numero_cotizacion='".$numero_cotizacion."' and rif='".$rif."'   ");
										                           $sw = $this->cscd02_solicitud_encabezado->execute("UPDATE cscd04_ordencompra_encabezado set cod_obra='".$codigo_obra."' where ".$this->condicionNDEP()." and cod_dep='".$cod_dep."' and ano_orden_compra='".$ano_orden_compra."'   and  numero_orden_compra='".$numero_orden_compra."'    ");
																   foreach($lista2 as $aux2){
														  	    	          $cod_sector          =   $aux2['cscd04_ordencompra_partidas']['cod_sector'];
																			  $cod_programa        =   $aux2['cscd04_ordencompra_partidas']['cod_programa'];
																			  $cod_sub_prog        =   $aux2['cscd04_ordencompra_partidas']['cod_sub_prog'];
																			  $cod_proyecto        =   $aux2['cscd04_ordencompra_partidas']['cod_proyecto'];
																			  $cod_activ_obra      =   $aux2['cscd04_ordencompra_partidas']['cod_activ_obra'];
																			  $cod_partida         =   $aux2['cscd04_ordencompra_partidas']['cod_partida'];
																			  $cod_generica        =   $aux2['cscd04_ordencompra_partidas']['cod_generica'];
																			  $cod_especifica      =   $aux2['cscd04_ordencompra_partidas']['cod_especifica'];
																			  $cod_sub_espec       =   $aux2['cscd04_ordencompra_partidas']['cod_sub_espec'];
																			  $cod_auxiliar        =   $aux2['cscd04_ordencompra_partidas']['cod_auxiliar'];
																			  $monto               =   $aux2['cscd04_ordencompra_partidas']['monto'];
																			  $cond2               =   $this->condicionNDEP()." and cod_dep='".$cod_dep."' and ano='".$ano_obra."' and cod_sector='$cod_sector' and cod_programa='$cod_programa' and cod_sub_prog='$cod_sub_prog' and cod_proyecto='$cod_proyecto' and cod_activ_obra='$cod_activ_obra' and cod_partida='$cod_partida' and cod_generica='$cod_generica' and cod_especifica='$cod_especifica' and cod_sub_espec='$cod_sub_espec' and cod_auxiliar='$cod_auxiliar'";
											                                  $sql55               =   "UPDATE cfpd05 SET precompromiso_obras = precompromiso_obras + ".$monto." WHERE ".$cond2." ";
										//		                              $sw155               =   $this->cscd04_ordencompra_partidas->execute($sql55);

												                              $sql_aux = "cod_presi=".$cod_presi." and cod_entidad=".$cod_entidad." and cod_tipo_inst=".$cod_tipo_inst." and  cod_inst=".$cod_inst." and cod_dep=".$cod_dep." and ano_estimacion=".$ano." and upper(cod_obra)='".strtoupper($codigo_obra)."' and cod_sector=".$cod_sector."   and   cod_programa=".$cod_programa."   and   cod_sub_prog=".$cod_sub_prog."   and   cod_proyecto=".$cod_proyecto."   and   cod_activ_obra=".$cod_activ_obra."   and   cod_partida=".$cod_partida."   and   cod_generica=".$cod_generica."   and   cod_especifica=".$cod_especifica."   and   cod_sub_espec=".$cod_sub_espec."   and   cod_auxiliar=".$cod_auxiliar.";";
																              $sw455 = $this->cscd04_ordencompra_partidas->execute("UPDATE cfpd07_obras_partidas SET monto_contratado=monto_contratado+".$monto." where ".$sql_aux);

																	          $sql_aux = "cod_presi=".$cod_presi." and cod_entidad=".$cod_entidad." and cod_tipo_inst=".$cod_tipo_inst." and  cod_inst=".$cod_inst." and cod_dep=".$cod_dep." and ano_estimacion=".$ano." and upper(cod_obra)='".strtoupper($codigo_obra)."';";
																              $sw455   = $this->cscd04_ordencompra_partidas->execute("UPDATE cfpd07_obras_cuerpo SET monto_contratado=monto_contratado+".$monto." where ".$sql_aux);


																  }//fin foreach
																   $this->set('Message_existe', 'Los datos fueron  guardados');
																}else{
																		$this->set('msg_error', 'los siguientes códigos presupuestarios no estan registrados en la relación de obras');
																		$this->set('codigos', $_SESSION["codigos"]);
																}//fin if
													}//fin foreach
										  }//fin foreach




										$lista_aux_2                 =  $this->cepd01_compromiso_cuerpo->findAll($this->condicionNDEP()." and cod_dep='".$cod_dep."' and ano_documento = ".$ano." "." and (cod_obra!='' and cod_obra!='0' and cod_obra IS NOT NULL) and condicion_actividad=1");
										foreach($lista_aux_2 as $aux_2){
										  $this->Session->delete('codigos');
										  $ano_obra                 =       $aux_2['cepd01_compromiso_cuerpo']['ano_documento'];
										  $numero_documento         =       $aux_2['cepd01_compromiso_cuerpo']['numero_documento'];
										  $codigo_obra              =       $aux_2['cepd01_compromiso_cuerpo']['cod_obra'];

										  	       $lista                 = $this->cepd01_compromiso_cuerpo->findAll($this->condicionNDEP()." and cod_dep='".$cod_dep."' and ano_documento = ".$ano_obra." and numero_documento='".$numero_documento."' "." ");
											  	   $cfpd07_obras_partidas =  $this->cfpd07_obras_partidas->findAll($this->condicionNDEP()." and cod_dep='".$cod_dep."'   and ano_estimacion   = ".$ano."      and upper(cod_obra)='".strtoupper($codigo_obra)."' ", null, 'cod_sector,cod_programa,cod_sub_prog,cod_proyecto,cod_activ_obra, cod_partida,cod_generica,cod_especifica,cod_sub_espec,cod_auxiliar ASC');
											  	   foreach($lista as $aux){
											  	   	    $contador_exitentes  =  0;
														$ano_documento       =  $aux['cepd01_compromiso_cuerpo']['ano_documento'];
														$numero_documento    =  $aux['cepd01_compromiso_cuerpo']['numero_documento'];
										                $lista2              =  $this->cepd01_compromiso_partidas->findAll($this->condicionNDEP()." and cod_dep='".$cod_dep."' and ano_documento = ".$ano_obra." and numero_documento='".$numero_documento."' ");
														  	    foreach($lista2 as $aux2){
														  	    	          $cod_sector2          =   $aux2['cepd01_compromiso_partidas']['cod_sector'];
																			  $cod_programa2        =   $aux2['cepd01_compromiso_partidas']['cod_programa'];
																			  $cod_sub_prog2        =   $aux2['cepd01_compromiso_partidas']['cod_sub_prog'];
																			  $cod_proyecto2        =   $aux2['cepd01_compromiso_partidas']['cod_proyecto'];
																			  $cod_activ_obra2      =   $aux2['cepd01_compromiso_partidas']['cod_activ_obra'];
																			  $cod_partida2         =   $aux2['cepd01_compromiso_partidas']['cod_partida'];
																			  $cod_generica2        =   $aux2['cepd01_compromiso_partidas']['cod_generica'];
																			  $cod_especifica2      =   $aux2['cepd01_compromiso_partidas']['cod_especifica'];
																			  $cod_sub_espec2       =   $aux2['cepd01_compromiso_partidas']['cod_sub_espec'];
																			  $cod_auxiliar2        =   $aux2['cepd01_compromiso_partidas']['cod_auxiliar'];
																			  $marca = 0;
														  	    		foreach($cfpd07_obras_partidas  as  $aux_cfpd07_obras_partidas){
																			  $cod_sector          =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_sector'];
																			  $cod_programa        =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_programa'];
																			  $cod_sub_prog        =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_sub_prog'];
																			  $cod_proyecto        =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_proyecto'];
																			  $cod_activ_obra      =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_activ_obra'];
																			  $cod_partida         =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_partida'];
																			  $cod_generica        =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_generica'];
																			  $cod_especifica      =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_especifica'];
																			  $cod_sub_espec       =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_sub_espec'];
																			  $cod_auxiliar        =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_auxiliar'];
																				  if($cod_sector     == $cod_sector2     &&
																				     $cod_programa   == $cod_programa2   &&
																				     $cod_sub_prog   == $cod_sub_prog2   &&
																				     $cod_proyecto   == $cod_proyecto2   &&
																				     $cod_activ_obra == $cod_activ_obra2 &&
																				     $cod_partida    == $cod_partida2    &&
																				     $cod_generica   == $cod_generica2   &&
																				     $cod_especifica == $cod_especifica2 &&
																				     $cod_sub_espec  == $cod_sub_espec2  &&
																				     $cod_auxiliar   == $cod_auxiliar2){
																				     	$marca = 1;
																				  }//fin if
														  	    		 }//fin foreach
														  	    		 if($marca==0){$contador_exitentes++;
																			$_SESSION["codigos"][$contador_exitentes][0]=$ano_obra;
																			$_SESSION["codigos"][$contador_exitentes][1]=$cod_sector2;
																			$_SESSION["codigos"][$contador_exitentes][2]=$cod_programa2;
																			$_SESSION["codigos"][$contador_exitentes][3]=$cod_sub_prog2;
																			$_SESSION["codigos"][$contador_exitentes][4]=$cod_proyecto2;
																			$_SESSION["codigos"][$contador_exitentes][5]=$cod_activ_obra2;
																			$_SESSION["codigos"][$contador_exitentes][6]=$cod_partida2;
																			$_SESSION["codigos"][$contador_exitentes][7]=$cod_generica2;
																			$_SESSION["codigos"][$contador_exitentes][8]=$cod_especifica2;
																			$_SESSION["codigos"][$contador_exitentes][9]=$cod_sub_espec2;
																			$_SESSION["codigos"][$contador_exitentes][10]=$cod_auxiliar2;
														  	    		 }
																}//fin foreach
																if($contador_exitentes==0){
										                           $sw = $this->cepd01_compromiso_cuerpo->execute("UPDATE cepd01_compromiso_cuerpo set cod_obra='".$codigo_obra."' where ".$this->condicionNDEP()." and cod_dep='".$cod_dep."' and ano_documento='".$ano_documento."'   and  numero_documento='".$numero_documento."'    ");
																   foreach($lista2 as $aux2){
														  	    	          $cod_sector          =   $aux2['cepd01_compromiso_partidas']['cod_sector'];
																			  $cod_programa        =   $aux2['cepd01_compromiso_partidas']['cod_programa'];
																			  $cod_sub_prog        =   $aux2['cepd01_compromiso_partidas']['cod_sub_prog'];
																			  $cod_proyecto        =   $aux2['cepd01_compromiso_partidas']['cod_proyecto'];
																			  $cod_activ_obra      =   $aux2['cepd01_compromiso_partidas']['cod_activ_obra'];
																			  $cod_partida         =   $aux2['cepd01_compromiso_partidas']['cod_partida'];
																			  $cod_generica        =   $aux2['cepd01_compromiso_partidas']['cod_generica'];
																			  $cod_especifica      =   $aux2['cepd01_compromiso_partidas']['cod_especifica'];
																			  $cod_sub_espec       =   $aux2['cepd01_compromiso_partidas']['cod_sub_espec'];
																			  $cod_auxiliar        =   $aux2['cepd01_compromiso_partidas']['cod_auxiliar'];
																			  $monto               =   $aux2['cepd01_compromiso_partidas']['monto'];
																			  $cond2               =   $this->condicionNDEP()." and cod_dep='".$cod_dep."' and ano='".$ano_obra."' and cod_sector='$cod_sector' and cod_programa='$cod_programa' and cod_sub_prog='$cod_sub_prog' and cod_proyecto='$cod_proyecto' and cod_activ_obra='$cod_activ_obra' and cod_partida='$cod_partida' and cod_generica='$cod_generica' and cod_especifica='$cod_especifica' and cod_sub_espec='$cod_sub_espec' and cod_auxiliar='$cod_auxiliar'";
											                                  $sql55               =   "UPDATE cfpd05 SET precompromiso_obras = precompromiso_obras + ".$monto." WHERE ".$cond2."";
										//		                              $sw155               =   $this->cepd01_compromiso_partidas->execute($sql55);

												                              $sql_aux = "cod_presi=".$cod_presi." and cod_entidad=".$cod_entidad." and cod_tipo_inst=".$cod_tipo_inst." and  cod_inst=".$cod_inst." and cod_dep=".$cod_dep." and ano_estimacion=".$ano." and upper(cod_obra)='".strtoupper($codigo_obra)."' and cod_sector=".$cod_sector."   and   cod_programa=".$cod_programa."   and   cod_sub_prog=".$cod_sub_prog."   and   cod_proyecto=".$cod_proyecto."   and   cod_activ_obra=".$cod_activ_obra."   and   cod_partida=".$cod_partida."   and   cod_generica=".$cod_generica."   and   cod_especifica=".$cod_especifica."   and   cod_sub_espec=".$cod_sub_espec."   and   cod_auxiliar=".$cod_auxiliar.";";
																              $sw455 = $this->cscd04_ordencompra_partidas->execute("UPDATE cfpd07_obras_partidas SET monto_contratado=monto_contratado+".$monto." where ".$sql_aux);

																	          $sql_aux = "cod_presi=".$cod_presi." and cod_entidad=".$cod_entidad." and cod_tipo_inst=".$cod_tipo_inst." and  cod_inst=".$cod_inst." and cod_dep=".$cod_dep." and ano_estimacion=".$ano." and upper(cod_obra)='".strtoupper($codigo_obra)."';";
																              $sw455   = $this->cscd04_ordencompra_partidas->execute("UPDATE cfpd07_obras_cuerpo SET monto_contratado=monto_contratado+".$monto." where ".$sql_aux);



																  }//fin foreach
																  $this->set('Message_existe', 'Los datos fueron  guardados');
																}else{
																		$this->set('msg_error', 'los siguientes códigos presupuestarios no estan registrados en la relación de obras');
																		$this->set('codigos', $_SESSION["codigos"]);
																}//fin if
													}//fin foreach
										  }//fin
////////////////////////FIN PARTE I///////////////////////////////////////


//////////////////////// PARTE II///////////////////////////////////////
											  $cfpd07_obras_modificacion_cuerpo =  $this->cfpd07_obras_modificacion_cuerpo->findAll($this->condicionNDEP()." and cod_dep='".$cod_dep."'  and ano_estimacion   = ".$ano."  and condicion_actividad=1  ", null, null);
										  	  foreach($cfpd07_obras_modificacion_cuerpo  as  $aux_cfpd07_obras_modificacion_cuerpo){

										  	  	      $ano_estimacion      =   $aux_cfpd07_obras_modificacion_cuerpo['cfpd07_obras_modificacion_cuerpo']['ano_estimacion'];
													  $cod_obra            =   $aux_cfpd07_obras_modificacion_cuerpo['cfpd07_obras_modificacion_cuerpo']['cod_obra'];
													  $numero_modificacion =   $aux_cfpd07_obras_modificacion_cuerpo['cfpd07_obras_modificacion_cuerpo']['numero_modificacion'];
													  $tipo_modificacion   =   $aux_cfpd07_obras_modificacion_cuerpo['cfpd07_obras_modificacion_cuerpo']['tipo_modificacion'];

										  	  	      $lista2              =  $this->cfpd07_obras_modificacion_partidas->findAll($this->condicionNDEP()." and cod_dep='".$cod_dep."' and ano_estimacion = ".$ano_estimacion." and cod_obra='".$cod_obra."' and numero_modificacion='".$numero_modificacion."' ");
													  foreach($lista2 as $aux2){
															  $cod_sector          =   $aux2['cfpd07_obras_modificacion_partidas']['cod_sector'];
															  $cod_programa        =   $aux2['cfpd07_obras_modificacion_partidas']['cod_programa'];
															  $cod_sub_prog        =   $aux2['cfpd07_obras_modificacion_partidas']['cod_sub_prog'];
															  $cod_proyecto        =   $aux2['cfpd07_obras_modificacion_partidas']['cod_proyecto'];
															  $cod_activ_obra      =   $aux2['cfpd07_obras_modificacion_partidas']['cod_activ_obra'];
															  $cod_partida         =   $aux2['cfpd07_obras_modificacion_partidas']['cod_partida'];
															  $cod_generica        =   $aux2['cfpd07_obras_modificacion_partidas']['cod_generica'];
															  $cod_especifica      =   $aux2['cfpd07_obras_modificacion_partidas']['cod_especifica'];
															  $cod_sub_espec       =   $aux2['cfpd07_obras_modificacion_partidas']['cod_sub_espec'];
															  $cod_auxiliar        =   $aux2['cfpd07_obras_modificacion_partidas']['cod_auxiliar'];
															  $monto               =   $aux2['cfpd07_obras_modificacion_partidas']['monto'];
										                    if($tipo_modificacion=='1'){
																						$aa = $monto;
																						$bb = 0;
													  }else if($tipo_modificacion=='2'){
																						$aa = 0;
																						$bb = $monto;
													  }//fin else

												      $sql_aux = "cod_presi=".$cod_presi." and cod_entidad=".$cod_entidad." and cod_tipo_inst=".$cod_tipo_inst." and  cod_inst=".$cod_inst." and cod_dep=".$cod_dep." and ano_estimacion=".$ano." and upper(cod_obra)='".strtoupper($cod_obra)."' and cod_sector=".$cod_sector."   and   cod_programa=".$cod_programa."   and   cod_sub_prog=".$cod_sub_prog."   and   cod_proyecto=".$cod_proyecto."   and   cod_activ_obra=".$cod_activ_obra."   and   cod_partida=".$cod_partida."   and   cod_generica=".$cod_generica."   and   cod_especifica=".$cod_especifica."   and   cod_sub_espec=".$cod_sub_espec."   and   cod_auxiliar=".$cod_auxiliar.";";
										              $sw455 = $this->cscd04_ordencompra_partidas->execute("UPDATE cfpd07_obras_partidas SET aumento_obras = aumento_obras + ".$aa.",  disminucion_obras = disminucion_obras + ".$bb." where ".$sql_aux);

											          $sql_aux = "cod_presi=".$cod_presi." and cod_entidad=".$cod_entidad." and cod_tipo_inst=".$cod_tipo_inst." and  cod_inst=".$cod_inst." and cod_dep=".$cod_dep." and ano_estimacion=".$ano." and upper(cod_obra)='".strtoupper($cod_obra)."';";
										              $sw455   = $this->cscd04_ordencompra_partidas->execute("UPDATE cfpd07_obras_cuerpo SET aumento_obras = aumento_obras + ".$aa.",  disminucion_obras = disminucion_obras + ".$bb." where ".$sql_aux);

                                                      echo "OBRA ".$sql_aux." <br>";
                                                     }//fin foreach
											  }//fin foreach

											  $cfpd07_obras_partidas =  $this->cfpd07_obras_partidas->findAll($this->condicionNDEP()." and cod_dep='".$cod_dep."'         and ano_estimacion   = ".$ano." ", null, 'cod_sector,cod_programa,cod_sub_prog,cod_proyecto,cod_activ_obra, cod_partida,cod_generica,cod_especifica,cod_sub_espec,cod_auxiliar ASC');
														  	  foreach($cfpd07_obras_partidas  as  $aux_cfpd07_obras_partidas){
																						  $cod_sector          =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_sector'];
																						  $cod_programa        =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_programa'];
																						  $cod_sub_prog        =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_sub_prog'];
																						  $cod_proyecto        =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_proyecto'];
																						  $cod_activ_obra      =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_activ_obra'];
																						  $cod_partida         =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_partida'];
																						  $cod_generica        =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_generica'];
																						  $cod_especifica      =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_especifica'];
																						  $cod_sub_espec       =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_sub_espec'];
																						  $cod_auxiliar        =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_auxiliar'];
																						  $monto               =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['monto'];

																					      $cond2               =   $this->condicionNDEP()." and cod_dep='".$cod_dep."'  and ano='".$ano."' and cod_sector='$cod_sector' and cod_programa='$cod_programa' and cod_sub_prog='$cod_sub_prog' and cod_proyecto='$cod_proyecto' and cod_activ_obra='$cod_activ_obra' and cod_partida='$cod_partida' and cod_generica='$cod_generica' and cod_especifica='$cod_especifica' and cod_sub_espec='$cod_sub_espec' and cod_auxiliar='$cod_auxiliar'";
														                                  $sql55               =   "UPDATE cfpd05 SET precompromiso_obras = 0  WHERE ".$cond2." ";
															                              $sw155               =   $this->cscd04_ordencompra_partidas->execute($sql55);
															  }//fin foreach
														  	  foreach($cfpd07_obras_partidas  as  $aux_cfpd07_obras_partidas){
																						  $cod_sector          =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_sector'];
																						  $cod_programa        =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_programa'];
																						  $cod_sub_prog        =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_sub_prog'];
																						  $cod_proyecto        =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_proyecto'];
																						  $cod_activ_obra      =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_activ_obra'];
																						  $cod_partida         =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_partida'];
																						  $cod_generica        =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_generica'];
																						  $cod_especifica      =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_especifica'];
																						  $cod_sub_espec       =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_sub_espec'];
																						  $cod_auxiliar        =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_auxiliar'];
																						  $monto               =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['monto'];

																					      $cond2               =   $this->condicionNDEP()." and cod_dep='".$cod_dep."' and ano='".$ano."' and cod_sector='$cod_sector' and cod_programa='$cod_programa' and cod_sub_prog='$cod_sub_prog' and cod_proyecto='$cod_proyecto' and cod_activ_obra='$cod_activ_obra' and cod_partida='$cod_partida' and cod_generica='$cod_generica' and cod_especifica='$cod_especifica' and cod_sub_espec='$cod_sub_espec' and cod_auxiliar='$cod_auxiliar'";
														                                  $sql55               =   "UPDATE cfpd05 SET precompromiso_obras = precompromiso_obras + ".$monto." WHERE ".$cond2." ";
															                              $sw155               =   $this->cscd04_ordencompra_partidas->execute($sql55);

															  }//fin foreach
														  	  foreach($cfpd07_obras_partidas  as  $aux_cfpd07_obras_partidas){
																						  $cod_sector          =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_sector'];
																						  $cod_programa        =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_programa'];
																						  $cod_sub_prog        =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_sub_prog'];
																						  $cod_proyecto        =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_proyecto'];
																						  $cod_activ_obra      =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_activ_obra'];
																						  $cod_partida         =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_partida'];
																						  $cod_generica        =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_generica'];
																						  $cod_especifica      =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_especifica'];
																						  $cod_sub_espec       =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_sub_espec'];
																						  $cod_auxiliar        =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_auxiliar'];
																						  $aumento_obras       =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['aumento_obras'];

																					      $cond2               =   $this->condicionNDEP()." and cod_dep='".$cod_dep."' and ano='".$ano."' and cod_sector='$cod_sector' and cod_programa='$cod_programa' and cod_sub_prog='$cod_sub_prog' and cod_proyecto='$cod_proyecto' and cod_activ_obra='$cod_activ_obra' and cod_partida='$cod_partida' and cod_generica='$cod_generica' and cod_especifica='$cod_especifica' and cod_sub_espec='$cod_sub_espec' and cod_auxiliar='$cod_auxiliar'";
														                                  $sql55               =   "UPDATE cfpd05 SET precompromiso_obras = precompromiso_obras + ".$aumento_obras." WHERE ".$cond2." ";
															                              $sw155               =   $this->cscd04_ordencompra_partidas->execute($sql55);

															  }//fin foreach
														  	  foreach($cfpd07_obras_partidas  as  $aux_cfpd07_obras_partidas){
																						  $cod_sector          =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_sector'];
																						  $cod_programa        =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_programa'];
																						  $cod_sub_prog        =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_sub_prog'];
																						  $cod_proyecto        =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_proyecto'];
																						  $cod_activ_obra      =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_activ_obra'];
																						  $cod_partida         =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_partida'];
																						  $cod_generica        =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_generica'];
																						  $cod_especifica      =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_especifica'];
																						  $cod_sub_espec       =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_sub_espec'];
																						  $cod_auxiliar        =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_auxiliar'];
																						  $disminucion_obras   =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['disminucion_obras'];

																					      $cond2               =   $this->condicionNDEP()." and cod_dep='".$cod_dep."' and ano='".$ano."' and cod_sector='$cod_sector' and cod_programa='$cod_programa' and cod_sub_prog='$cod_sub_prog' and cod_proyecto='$cod_proyecto' and cod_activ_obra='$cod_activ_obra' and cod_partida='$cod_partida' and cod_generica='$cod_generica' and cod_especifica='$cod_especifica' and cod_sub_espec='$cod_sub_espec' and cod_auxiliar='$cod_auxiliar'";
														                                  $sql55               =   "UPDATE cfpd05 SET precompromiso_obras = precompromiso_obras - ".$disminucion_obras." WHERE ".$cond2." ";
															                              $sw155               =   $this->cscd04_ordencompra_partidas->execute($sql55);

															  }//fin foreach
//////////////////////// FIN PARTE II///////////////////////////////////////



//////////////////////// PARTE IV///////////////////////////////////////
														$lista_aux_2                 =  $this->cscd04_ordencompra_encabezado->findAll($this->condicionNDEP()." and cod_dep='".$cod_dep."' and ano_orden_compra = ".$ano." "." and (cod_obra!='' and cod_obra!='0' and cod_obra IS NOT NULL) ");
														foreach($lista_aux_2 as $aux_2){
															  $this->Session->delete('codigos');

														  $ano_obra                 =       $aux_2['cscd04_ordencompra_encabezado']['ano_orden_compra'];
														  $numero_documento         =       $aux_2['cscd04_ordencompra_encabezado']['numero_orden_compra'];
														  $codigo_obra              =       $aux_2['cscd04_ordencompra_encabezado']['cod_obra'];

														           $lista                 =  $this->cscd04_ordencompra_encabezado->findAll($this->condicionNDEP()." and cod_dep='".$cod_dep."' and ano_orden_compra = ".$ano_obra." and numero_orden_compra='".$numero_documento."' "." ");
															  	   $cfpd07_obras_partidas =  $this->cfpd07_obras_partidas->findAll($this->condicionNDEP()." and cod_dep='".$cod_dep."'        and ano_estimacion   = ".$ano."      and upper(cod_obra)='".strtoupper($codigo_obra)."' ", null, 'cod_sector,cod_programa,cod_sub_prog,cod_proyecto,cod_activ_obra, cod_partida,cod_generica,cod_especifica,cod_sub_espec,cod_auxiliar ASC');
															  	   foreach($lista as $aux){
															  	   	    $contador_exitentes  = 0;
																		$rif                 = $aux['cscd04_ordencompra_encabezado']['rif'];
																		$ano_orden_compra    = $aux['cscd04_ordencompra_encabezado']['ano_orden_compra'];
																		$numero_orden_compra = $aux['cscd04_ordencompra_encabezado']['numero_orden_compra'];
																		$ano_cotizacion      = $aux['cscd04_ordencompra_encabezado']['ano_cotizacion'];
																		$numero_cotizacion   = $aux['cscd04_ordencompra_encabezado']['numero_cotizacion'];
																		$lista3              = $this->cscd03_cotizacion_encabezado->findAll($this->condicionNDEP()." and cod_dep='".$cod_dep."' and ano_cotizacion = ".$ano_cotizacion." and numero_cotizacion='".$numero_cotizacion."' and rif='".$rif."' ");
														                $ano_solicitud       = $lista3[0]["cscd03_cotizacion_encabezado"]["ano_solicitud"];
														                $numero_solicitud    = $lista3[0]["cscd03_cotizacion_encabezado"]["numero_solicitud"];
														                $lista2              = $this->cscd04_ordencompra_partidas->findAll($this->condicionNDEP()." and cod_dep='".$cod_dep."' and ano_orden_compra = ".$ano_obra." and numero_orden_compra='".$numero_documento."' ");

														                if(!isset($lista3[0]["cscd03_cotizacion_encabezado"]["numero_solicitud"])){
														                	echo $this->condicionNDEP()." and cod_dep='".$cod_dep."' and ano_cotizacion = ".$ano_cotizacion." and numero_cotizacion='".$numero_cotizacion."' and rif='".$rif."' <br>";
														                }


																		  	    foreach($lista2 as $aux2){
																		  	    	          $cod_sector2          =   $aux2['cscd04_ordencompra_partidas']['cod_sector'];
																							  $cod_programa2        =   $aux2['cscd04_ordencompra_partidas']['cod_programa'];
																							  $cod_sub_prog2        =   $aux2['cscd04_ordencompra_partidas']['cod_sub_prog'];
																							  $cod_proyecto2        =   $aux2['cscd04_ordencompra_partidas']['cod_proyecto'];
																							  $cod_activ_obra2      =   $aux2['cscd04_ordencompra_partidas']['cod_activ_obra'];
																							  $cod_partida2         =   $aux2['cscd04_ordencompra_partidas']['cod_partida'];
																							  $cod_generica2        =   $aux2['cscd04_ordencompra_partidas']['cod_generica'];
																							  $cod_especifica2      =   $aux2['cscd04_ordencompra_partidas']['cod_especifica'];
																							  $cod_sub_espec2       =   $aux2['cscd04_ordencompra_partidas']['cod_sub_espec'];
																							  $cod_auxiliar2        =   $aux2['cscd04_ordencompra_partidas']['cod_auxiliar'];
																							  $marca = 0;
																		  	    		foreach($cfpd07_obras_partidas  as  $aux_cfpd07_obras_partidas){
																							  $cod_sector          =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_sector'];
																							  $cod_programa        =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_programa'];
																							  $cod_sub_prog        =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_sub_prog'];
																							  $cod_proyecto        =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_proyecto'];
																							  $cod_activ_obra      =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_activ_obra'];
																							  $cod_partida         =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_partida'];
																							  $cod_generica        =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_generica'];
																							  $cod_especifica      =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_especifica'];
																							  $cod_sub_espec       =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_sub_espec'];
																							  $cod_auxiliar        =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_auxiliar'];
																								  if($cod_sector     == $cod_sector2     &&
																								     $cod_programa   == $cod_programa2   &&
																								     $cod_sub_prog   == $cod_sub_prog2   &&
																								     $cod_proyecto   == $cod_proyecto2   &&
																								     $cod_activ_obra == $cod_activ_obra2 &&
																								     $cod_partida    == $cod_partida2    &&
																								     $cod_generica   == $cod_generica2   &&
																								     $cod_especifica == $cod_especifica2 &&
																								     $cod_sub_espec  == $cod_sub_espec2  &&
																								     $cod_auxiliar   == $cod_auxiliar2){
																								     	$marca = 1;
																								  }else{

																								  }
																		  	    		 }//fin foreach
																		  	    		 if($marca==0){$contador_exitentes++;
																							$_SESSION["codigos"][$contador_exitentes][0]=$ano_obra;
																							$_SESSION["codigos"][$contador_exitentes][1]=$cod_sector2;
																							$_SESSION["codigos"][$contador_exitentes][2]=$cod_programa2;
																							$_SESSION["codigos"][$contador_exitentes][3]=$cod_sub_prog2;
																							$_SESSION["codigos"][$contador_exitentes][4]=$cod_proyecto2;
																							$_SESSION["codigos"][$contador_exitentes][5]=$cod_activ_obra2;
																							$_SESSION["codigos"][$contador_exitentes][6]=$cod_partida2;
																							$_SESSION["codigos"][$contador_exitentes][7]=$cod_generica2;
																							$_SESSION["codigos"][$contador_exitentes][8]=$cod_especifica2;
																							$_SESSION["codigos"][$contador_exitentes][9]=$cod_sub_espec2;
																							$_SESSION["codigos"][$contador_exitentes][10]=$cod_auxiliar2;
																		  	    		 }
																				}//fin foreach
																				if($contador_exitentes==0){
														                           $sw = $this->cscd02_solicitud_encabezado->execute("UPDATE cscd02_solicitud_encabezado   set cod_obra='".$codigo_obra."' where ".$this->condicionNDEP()." and cod_dep='".$cod_dep."' and ano_solicitud='".$ano_solicitud."'         and  numero_solicitud='".$numero_solicitud."'    ");
														                           $sw = $this->cscd02_solicitud_encabezado->execute("UPDATE cscd03_cotizacion_encabezado  set cod_obra='".$codigo_obra."' where ".$this->condicionNDEP()." and cod_dep='".$cod_dep."' and ano_cotizacion='".$ano_cotizacion."'       and  numero_cotizacion='".$numero_cotizacion."' and rif='".$rif."'   ");
														                           $sw = $this->cscd02_solicitud_encabezado->execute("UPDATE cscd04_ordencompra_encabezado set cod_obra='".$codigo_obra."' where ".$this->condicionNDEP()." and cod_dep='".$cod_dep."' and ano_orden_compra='".$ano_orden_compra."'   and  numero_orden_compra='".$numero_orden_compra."'    ");
																				   foreach($lista2 as $aux2){
																		  	    	          $cod_sector          =   $aux2['cscd04_ordencompra_partidas']['cod_sector'];
																							  $cod_programa        =   $aux2['cscd04_ordencompra_partidas']['cod_programa'];
																							  $cod_sub_prog        =   $aux2['cscd04_ordencompra_partidas']['cod_sub_prog'];
																							  $cod_proyecto        =   $aux2['cscd04_ordencompra_partidas']['cod_proyecto'];
																							  $cod_activ_obra      =   $aux2['cscd04_ordencompra_partidas']['cod_activ_obra'];
																							  $cod_partida         =   $aux2['cscd04_ordencompra_partidas']['cod_partida'];
																							  $cod_generica        =   $aux2['cscd04_ordencompra_partidas']['cod_generica'];
																							  $cod_especifica      =   $aux2['cscd04_ordencompra_partidas']['cod_especifica'];
																							  $cod_sub_espec       =   $aux2['cscd04_ordencompra_partidas']['cod_sub_espec'];
																							  $cod_auxiliar        =   $aux2['cscd04_ordencompra_partidas']['cod_auxiliar'];
																							  $monto               =   $aux2['cscd04_ordencompra_partidas']['monto'];
																							  $cond2               =   $this->condicionNDEP()." and cod_dep='".$cod_dep."' and ano='".$ano_obra."' and cod_sector='$cod_sector' and cod_programa='$cod_programa' and cod_sub_prog='$cod_sub_prog' and cod_proyecto='$cod_proyecto' and cod_activ_obra='$cod_activ_obra' and cod_partida='$cod_partida' and cod_generica='$cod_generica' and cod_especifica='$cod_especifica' and cod_sub_espec='$cod_sub_espec' and cod_auxiliar='$cod_auxiliar'";
															                                  $sql55               =   "UPDATE cfpd05 SET precompromiso_obras = precompromiso_obras - ".$monto." WHERE ".$cond2." ";
																                              $sw155               =   $this->cscd04_ordencompra_partidas->execute($sql55);


																                              $sql_aux = "cod_presi=".$cod_presi." and cod_entidad=".$cod_entidad." and cod_tipo_inst=".$cod_tipo_inst." and  cod_inst=".$cod_inst." and cod_dep=".$cod_dep." and ano_estimacion=".$ano." and upper(cod_obra)='".strtoupper($codigo_obra)."' and cod_sector=".$cod_sector."   and   cod_programa=".$cod_programa."   and   cod_sub_prog=".$cod_sub_prog."   and   cod_proyecto=".$cod_proyecto."   and   cod_activ_obra=".$cod_activ_obra."   and   cod_partida=".$cod_partida."   and   cod_generica=".$cod_generica."   and   cod_especifica=".$cod_especifica."   and   cod_sub_espec=".$cod_sub_espec."   and   cod_auxiliar=".$cod_auxiliar.";";
														//						              $sw455 = $this->cscd04_ordencompra_partidas->execute("UPDATE cfpd07_obras_partidas SET monto_contratado=monto_contratado+".$monto." where ".$sql_aux);

																					          $sql_aux = "cod_presi=".$cod_presi." and cod_entidad=".$cod_entidad." and cod_tipo_inst=".$cod_tipo_inst." and  cod_inst=".$cod_inst." and cod_dep=".$cod_dep." and ano_estimacion=".$ano." and upper(cod_obra)='".strtoupper($codigo_obra)."';";
														//						              $sw455   = $this->cscd04_ordencompra_partidas->execute("UPDATE cfpd07_obras_cuerpo SET monto_contratado=monto_contratado+".$monto." where ".$sql_aux);


																				  }//fin foreach
																				   $this->set('Message_existe', 'Los datos fueron  guardados');
																				}else{
																						$this->set('msg_error', 'los siguientes códigos presupuestarios no estan registrados en la relación de obras');
																						$this->set('codigos', $_SESSION["codigos"]);
																				}//fin if
																	}//fin foreach
														  }//fin foreach







														$lista_aux_2                 =  $this->cepd01_compromiso_cuerpo->findAll($this->condicionNDEP()." and cod_dep='".$cod_dep."' and ano_documento = ".$ano." "." and (cod_obra!='' and cod_obra!='0' and cod_obra IS NOT NULL) ");
														foreach($lista_aux_2 as $aux_2){
														  $this->Session->delete('codigos');
														  $ano_obra                 =       $aux_2['cepd01_compromiso_cuerpo']['ano_documento'];
														  $numero_documento         =       $aux_2['cepd01_compromiso_cuerpo']['numero_documento'];
														  $codigo_obra              =       $aux_2['cepd01_compromiso_cuerpo']['cod_obra'];

														  	       $lista                 = $this->cepd01_compromiso_cuerpo->findAll($this->condicionNDEP()." and cod_dep='".$cod_dep."' and ano_documento = ".$ano_obra." and numero_documento='".$numero_documento."' "." ");
															  	   $cfpd07_obras_partidas =  $this->cfpd07_obras_partidas->findAll($this->condicionNDEP()." and cod_dep='".$cod_dep."'   and ano_estimacion   = ".$ano."      and upper(cod_obra)='".strtoupper($codigo_obra)."' ", null, 'cod_sector,cod_programa,cod_sub_prog,cod_proyecto,cod_activ_obra, cod_partida,cod_generica,cod_especifica,cod_sub_espec,cod_auxiliar ASC');
															  	   foreach($lista as $aux){
															  	   	    $contador_exitentes  =  0;
																		$ano_documento       =  $aux['cepd01_compromiso_cuerpo']['ano_documento'];
																		$numero_documento    =  $aux['cepd01_compromiso_cuerpo']['numero_documento'];
														                $lista2              =  $this->cepd01_compromiso_partidas->findAll($this->condicionNDEP()." and cod_dep='".$cod_dep."' and ano_documento = ".$ano_obra." and numero_documento='".$numero_documento."' ");
																		  	    foreach($lista2 as $aux2){
																		  	    	          $cod_sector2          =   $aux2['cepd01_compromiso_partidas']['cod_sector'];
																							  $cod_programa2        =   $aux2['cepd01_compromiso_partidas']['cod_programa'];
																							  $cod_sub_prog2        =   $aux2['cepd01_compromiso_partidas']['cod_sub_prog'];
																							  $cod_proyecto2        =   $aux2['cepd01_compromiso_partidas']['cod_proyecto'];
																							  $cod_activ_obra2      =   $aux2['cepd01_compromiso_partidas']['cod_activ_obra'];
																							  $cod_partida2         =   $aux2['cepd01_compromiso_partidas']['cod_partida'];
																							  $cod_generica2        =   $aux2['cepd01_compromiso_partidas']['cod_generica'];
																							  $cod_especifica2      =   $aux2['cepd01_compromiso_partidas']['cod_especifica'];
																							  $cod_sub_espec2       =   $aux2['cepd01_compromiso_partidas']['cod_sub_espec'];
																							  $cod_auxiliar2        =   $aux2['cepd01_compromiso_partidas']['cod_auxiliar'];
																							  $marca = 0;
																		  	    		foreach($cfpd07_obras_partidas  as  $aux_cfpd07_obras_partidas){
																							  $cod_sector          =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_sector'];
																							  $cod_programa        =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_programa'];
																							  $cod_sub_prog        =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_sub_prog'];
																							  $cod_proyecto        =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_proyecto'];
																							  $cod_activ_obra      =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_activ_obra'];
																							  $cod_partida         =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_partida'];
																							  $cod_generica        =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_generica'];
																							  $cod_especifica      =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_especifica'];
																							  $cod_sub_espec       =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_sub_espec'];
																							  $cod_auxiliar        =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_auxiliar'];
																								  if($cod_sector     == $cod_sector2     &&
																								     $cod_programa   == $cod_programa2   &&
																								     $cod_sub_prog   == $cod_sub_prog2   &&
																								     $cod_proyecto   == $cod_proyecto2   &&
																								     $cod_activ_obra == $cod_activ_obra2 &&
																								     $cod_partida    == $cod_partida2    &&
																								     $cod_generica   == $cod_generica2   &&
																								     $cod_especifica == $cod_especifica2 &&
																								     $cod_sub_espec  == $cod_sub_espec2  &&
																								     $cod_auxiliar   == $cod_auxiliar2){
																								     	$marca = 1;
																								  }//fin if
																		  	    		 }//fin foreach
																		  	    		 if($marca==0){$contador_exitentes++;
																							$_SESSION["codigos"][$contador_exitentes][0]=$ano_obra;
																							$_SESSION["codigos"][$contador_exitentes][1]=$cod_sector2;
																							$_SESSION["codigos"][$contador_exitentes][2]=$cod_programa2;
																							$_SESSION["codigos"][$contador_exitentes][3]=$cod_sub_prog2;
																							$_SESSION["codigos"][$contador_exitentes][4]=$cod_proyecto2;
																							$_SESSION["codigos"][$contador_exitentes][5]=$cod_activ_obra2;
																							$_SESSION["codigos"][$contador_exitentes][6]=$cod_partida2;
																							$_SESSION["codigos"][$contador_exitentes][7]=$cod_generica2;
																							$_SESSION["codigos"][$contador_exitentes][8]=$cod_especifica2;
																							$_SESSION["codigos"][$contador_exitentes][9]=$cod_sub_espec2;
																							$_SESSION["codigos"][$contador_exitentes][10]=$cod_auxiliar2;
																		  	    		 }
																				}//fin foreach
																				if($contador_exitentes==0){
														                           $sw = $this->cepd01_compromiso_cuerpo->execute("UPDATE cepd01_compromiso_cuerpo set cod_obra='".$codigo_obra."' where ".$this->condicionNDEP()." and cod_dep='".$cod_dep."' and ano_documento='".$ano_documento."'   and  numero_documento='".$numero_documento."'    ");
																				   foreach($lista2 as $aux2){
																		  	    	          $cod_sector          =   $aux2['cepd01_compromiso_partidas']['cod_sector'];
																							  $cod_programa        =   $aux2['cepd01_compromiso_partidas']['cod_programa'];
																							  $cod_sub_prog        =   $aux2['cepd01_compromiso_partidas']['cod_sub_prog'];
																							  $cod_proyecto        =   $aux2['cepd01_compromiso_partidas']['cod_proyecto'];
																							  $cod_activ_obra      =   $aux2['cepd01_compromiso_partidas']['cod_activ_obra'];
																							  $cod_partida         =   $aux2['cepd01_compromiso_partidas']['cod_partida'];
																							  $cod_generica        =   $aux2['cepd01_compromiso_partidas']['cod_generica'];
																							  $cod_especifica      =   $aux2['cepd01_compromiso_partidas']['cod_especifica'];
																							  $cod_sub_espec       =   $aux2['cepd01_compromiso_partidas']['cod_sub_espec'];
																							  $cod_auxiliar        =   $aux2['cepd01_compromiso_partidas']['cod_auxiliar'];
																							  $monto               =   $aux2['cepd01_compromiso_partidas']['monto'];
																							  $cond2               =   $this->condicionNDEP()." and cod_dep='".$cod_dep."' and ano='".$ano_obra."' and cod_sector='$cod_sector' and cod_programa='$cod_programa' and cod_sub_prog='$cod_sub_prog' and cod_proyecto='$cod_proyecto' and cod_activ_obra='$cod_activ_obra' and cod_partida='$cod_partida' and cod_generica='$cod_generica' and cod_especifica='$cod_especifica' and cod_sub_espec='$cod_sub_espec' and cod_auxiliar='$cod_auxiliar'";
															                                  $sql55               =   "UPDATE cfpd05 SET precompromiso_obras = precompromiso_obras - ".$monto." WHERE ".$cond2." ";
																                              $sw155               =   $this->cepd01_compromiso_partidas->execute($sql55);

																                              $sql_aux = "cod_presi=".$cod_presi." and cod_entidad=".$cod_entidad." and cod_tipo_inst=".$cod_tipo_inst." and  cod_inst=".$cod_inst." and cod_dep=".$cod_dep." and ano_estimacion=".$ano." and upper(cod_obra)='".strtoupper($codigo_obra)."' and cod_sector=".$cod_sector."   and   cod_programa=".$cod_programa."   and   cod_sub_prog=".$cod_sub_prog."   and   cod_proyecto=".$cod_proyecto."   and   cod_activ_obra=".$cod_activ_obra."   and   cod_partida=".$cod_partida."   and   cod_generica=".$cod_generica."   and   cod_especifica=".$cod_especifica."   and   cod_sub_espec=".$cod_sub_espec."   and   cod_auxiliar=".$cod_auxiliar.";";
														//						              $sw455 = $this->cscd04_ordencompra_partidas->execute("UPDATE cfpd07_obras_partidas SET monto_contratado=monto_contratado+".$monto." where ".$sql_aux);

																					          $sql_aux = "cod_presi=".$cod_presi." and cod_entidad=".$cod_entidad." and cod_tipo_inst=".$cod_tipo_inst." and  cod_inst=".$cod_inst." and cod_dep=".$cod_dep." and ano_estimacion=".$ano." and upper(cod_obra)='".strtoupper($codigo_obra)."';";
														//						              $sw455   = $this->cscd04_ordencompra_partidas->execute("UPDATE cfpd07_obras_cuerpo SET monto_contratado=monto_contratado+".$monto." where ".$sql_aux);



																				  }//fin foreach
																				  $this->set('Message_existe', 'Los datos fueron  guardados');
																				}else{
																						$this->set('msg_error', 'los siguientes códigos presupuestarios no estan registrados en la relación de obras');
																						$this->set('codigos', $_SESSION["codigos"]);
																				}//fin if
																	}//fin foreach
														  }//fin function
//////////////////////// FIN PARTE IV///////////////////////////////////////



//////////////////////// PARTE V///////////////////////////////////////
														$lista_aux_2                 =  $this->cscd04_ordencompra_encabezado->findAll($this->condicionNDEP()." and cod_dep='".$cod_dep."' and ano_orden_compra = ".$ano." "." and (cod_obra!='' and cod_obra!='0' and cod_obra IS NOT NULL) and condicion_actividad=2");
														foreach($lista_aux_2 as $aux_2){
															  $this->Session->delete('codigos');

														  $ano_obra                 =       $aux_2['cscd04_ordencompra_encabezado']['ano_orden_compra'];
														  $numero_documento         =       $aux_2['cscd04_ordencompra_encabezado']['numero_orden_compra'];
														  $codigo_obra              =       $aux_2['cscd04_ordencompra_encabezado']['cod_obra'];

														           $lista                 =  $this->cscd04_ordencompra_encabezado->findAll($this->condicionNDEP()." and cod_dep='".$cod_dep."' and ano_orden_compra = ".$ano_obra." and numero_orden_compra='".$numero_documento."' "." ");
															  	   $cfpd07_obras_partidas =  $this->cfpd07_obras_partidas->findAll($this->condicionNDEP()." and cod_dep='".$cod_dep."'        and ano_estimacion   = ".$ano."      and upper(cod_obra)='".strtoupper($codigo_obra)."' ", null, 'cod_sector,cod_programa,cod_sub_prog,cod_proyecto,cod_activ_obra, cod_partida,cod_generica,cod_especifica,cod_sub_espec,cod_auxiliar ASC');
															  	   foreach($lista as $aux){
															  	   	    $contador_exitentes  = 0;
																		$rif                 = $aux['cscd04_ordencompra_encabezado']['rif'];
																		$ano_orden_compra    = $aux['cscd04_ordencompra_encabezado']['ano_orden_compra'];
																		$numero_orden_compra = $aux['cscd04_ordencompra_encabezado']['numero_orden_compra'];
																		$ano_cotizacion      = $aux['cscd04_ordencompra_encabezado']['ano_cotizacion'];
																		$numero_cotizacion   = $aux['cscd04_ordencompra_encabezado']['numero_cotizacion'];
																		$lista3              = $this->cscd03_cotizacion_encabezado->findAll($this->condicionNDEP()." and cod_dep='".$cod_dep."' and ano_cotizacion = ".$ano_cotizacion." and numero_cotizacion='".$numero_cotizacion."' and rif='".$rif."' ");
														                $ano_solicitud       = $lista3[0]["cscd03_cotizacion_encabezado"]["ano_solicitud"];
														                $numero_solicitud    = $lista3[0]["cscd03_cotizacion_encabezado"]["numero_solicitud"];
														                $lista2              = $this->cscd04_ordencompra_partidas->findAll($this->condicionNDEP()." and cod_dep='".$cod_dep."' and ano_orden_compra = ".$ano_obra." and numero_orden_compra='".$numero_documento."' ");
																		  	    foreach($lista2 as $aux2){
																		  	    	          $cod_sector2          =   $aux2['cscd04_ordencompra_partidas']['cod_sector'];
																							  $cod_programa2        =   $aux2['cscd04_ordencompra_partidas']['cod_programa'];
																							  $cod_sub_prog2        =   $aux2['cscd04_ordencompra_partidas']['cod_sub_prog'];
																							  $cod_proyecto2        =   $aux2['cscd04_ordencompra_partidas']['cod_proyecto'];
																							  $cod_activ_obra2      =   $aux2['cscd04_ordencompra_partidas']['cod_activ_obra'];
																							  $cod_partida2         =   $aux2['cscd04_ordencompra_partidas']['cod_partida'];
																							  $cod_generica2        =   $aux2['cscd04_ordencompra_partidas']['cod_generica'];
																							  $cod_especifica2      =   $aux2['cscd04_ordencompra_partidas']['cod_especifica'];
																							  $cod_sub_espec2       =   $aux2['cscd04_ordencompra_partidas']['cod_sub_espec'];
																							  $cod_auxiliar2        =   $aux2['cscd04_ordencompra_partidas']['cod_auxiliar'];
																							  $marca = 0;
																		  	    		foreach($cfpd07_obras_partidas  as  $aux_cfpd07_obras_partidas){
																							  $cod_sector          =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_sector'];
																							  $cod_programa        =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_programa'];
																							  $cod_sub_prog        =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_sub_prog'];
																							  $cod_proyecto        =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_proyecto'];
																							  $cod_activ_obra      =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_activ_obra'];
																							  $cod_partida         =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_partida'];
																							  $cod_generica        =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_generica'];
																							  $cod_especifica      =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_especifica'];
																							  $cod_sub_espec       =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_sub_espec'];
																							  $cod_auxiliar        =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_auxiliar'];
																								  if($cod_sector     == $cod_sector2     &&
																								     $cod_programa   == $cod_programa2   &&
																								     $cod_sub_prog   == $cod_sub_prog2   &&
																								     $cod_proyecto   == $cod_proyecto2   &&
																								     $cod_activ_obra == $cod_activ_obra2 &&
																								     $cod_partida    == $cod_partida2    &&
																								     $cod_generica   == $cod_generica2   &&
																								     $cod_especifica == $cod_especifica2 &&
																								     $cod_sub_espec  == $cod_sub_espec2  &&
																								     $cod_auxiliar   == $cod_auxiliar2){
																								     	$marca = 1;
																								  }else{

																								  }
																		  	    		 }//fin foreach
																		  	    		 if($marca==0){$contador_exitentes++;
																							$_SESSION["codigos"][$contador_exitentes][0]=$ano_obra;
																							$_SESSION["codigos"][$contador_exitentes][1]=$cod_sector2;
																							$_SESSION["codigos"][$contador_exitentes][2]=$cod_programa2;
																							$_SESSION["codigos"][$contador_exitentes][3]=$cod_sub_prog2;
																							$_SESSION["codigos"][$contador_exitentes][4]=$cod_proyecto2;
																							$_SESSION["codigos"][$contador_exitentes][5]=$cod_activ_obra2;
																							$_SESSION["codigos"][$contador_exitentes][6]=$cod_partida2;
																							$_SESSION["codigos"][$contador_exitentes][7]=$cod_generica2;
																							$_SESSION["codigos"][$contador_exitentes][8]=$cod_especifica2;
																							$_SESSION["codigos"][$contador_exitentes][9]=$cod_sub_espec2;
																							$_SESSION["codigos"][$contador_exitentes][10]=$cod_auxiliar2;
																		  	    		 }
																				}//fin foreach
																				if($contador_exitentes==0){
														                           $sw = $this->cscd02_solicitud_encabezado->execute("UPDATE cscd02_solicitud_encabezado   set cod_obra='".$codigo_obra."' where ".$this->condicionNDEP()." and cod_dep='".$cod_dep."' and ano_solicitud='".$ano_solicitud."'         and  numero_solicitud='".$numero_solicitud."'    ");
														                           $sw = $this->cscd02_solicitud_encabezado->execute("UPDATE cscd03_cotizacion_encabezado  set cod_obra='".$codigo_obra."' where ".$this->condicionNDEP()." and cod_dep='".$cod_dep."' and ano_cotizacion='".$ano_cotizacion."'       and  numero_cotizacion='".$numero_cotizacion."' and rif='".$rif."'   ");
														                           $sw = $this->cscd02_solicitud_encabezado->execute("UPDATE cscd04_ordencompra_encabezado set cod_obra='".$codigo_obra."' where ".$this->condicionNDEP()." and cod_dep='".$cod_dep."' and ano_orden_compra='".$ano_orden_compra."'   and  numero_orden_compra='".$numero_orden_compra."'    ");
																				   foreach($lista2 as $aux2){
																		  	    	          $cod_sector          =   $aux2['cscd04_ordencompra_partidas']['cod_sector'];
																							  $cod_programa        =   $aux2['cscd04_ordencompra_partidas']['cod_programa'];
																							  $cod_sub_prog        =   $aux2['cscd04_ordencompra_partidas']['cod_sub_prog'];
																							  $cod_proyecto        =   $aux2['cscd04_ordencompra_partidas']['cod_proyecto'];
																							  $cod_activ_obra      =   $aux2['cscd04_ordencompra_partidas']['cod_activ_obra'];
																							  $cod_partida         =   $aux2['cscd04_ordencompra_partidas']['cod_partida'];
																							  $cod_generica        =   $aux2['cscd04_ordencompra_partidas']['cod_generica'];
																							  $cod_especifica      =   $aux2['cscd04_ordencompra_partidas']['cod_especifica'];
																							  $cod_sub_espec       =   $aux2['cscd04_ordencompra_partidas']['cod_sub_espec'];
																							  $cod_auxiliar        =   $aux2['cscd04_ordencompra_partidas']['cod_auxiliar'];
																							  $monto               =   $aux2['cscd04_ordencompra_partidas']['monto'];
																							  $cond2               =   $this->condicionNDEP()." and cod_dep='".$cod_dep."' and ano='".$ano_obra."' and cod_sector='$cod_sector' and cod_programa='$cod_programa' and cod_sub_prog='$cod_sub_prog' and cod_proyecto='$cod_proyecto' and cod_activ_obra='$cod_activ_obra' and cod_partida='$cod_partida' and cod_generica='$cod_generica' and cod_especifica='$cod_especifica' and cod_sub_espec='$cod_sub_espec' and cod_auxiliar='$cod_auxiliar'";
															                                  $sql55               =   "UPDATE cfpd05 SET precompromiso_obras = precompromiso_obras + ".$monto." WHERE ".$cond2." ";
																                              $sw155               =   $this->cscd04_ordencompra_partidas->execute($sql55);


																                              $sql_aux = "cod_presi=".$cod_presi." and cod_entidad=".$cod_entidad." and cod_tipo_inst=".$cod_tipo_inst." and  cod_inst=".$cod_inst." and cod_dep=".$cod_dep." and ano_estimacion=".$ano." and upper(cod_obra)='".strtoupper($codigo_obra)."' and cod_sector=".$cod_sector."   and   cod_programa=".$cod_programa."   and   cod_sub_prog=".$cod_sub_prog."   and   cod_proyecto=".$cod_proyecto."   and   cod_activ_obra=".$cod_activ_obra."   and   cod_partida=".$cod_partida."   and   cod_generica=".$cod_generica."   and   cod_especifica=".$cod_especifica."   and   cod_sub_espec=".$cod_sub_espec."   and   cod_auxiliar=".$cod_auxiliar.";";
														//						              $sw455 = $this->cscd04_ordencompra_partidas->execute("UPDATE cfpd07_obras_partidas SET monto_contratado=monto_contratado+".$monto." where ".$sql_aux);

																					          $sql_aux = "cod_presi=".$cod_presi." and cod_entidad=".$cod_entidad." and cod_tipo_inst=".$cod_tipo_inst." and  cod_inst=".$cod_inst." and cod_dep=".$cod_dep." and ano_estimacion=".$ano." and upper(cod_obra)='".strtoupper($codigo_obra)."';";
														//						              $sw455   = $this->cscd04_ordencompra_partidas->execute("UPDATE cfpd07_obras_cuerpo SET monto_contratado=monto_contratado+".$monto." where ".$sql_aux);


																				  }//fin foreach
																				   $this->set('Message_existe', 'Los datos fueron  guardados');
																				}else{
																						$this->set('msg_error', 'los siguientes códigos presupuestarios no estan registrados en la relación de obras');
																						$this->set('codigos', $_SESSION["codigos"]);
																				}//fin if
																	}//fin foreach
														  }//fin foreach







														$lista_aux_2                 =  $this->cepd01_compromiso_cuerpo->findAll($this->condicionNDEP()." and cod_dep='".$cod_dep."' and ano_documento = ".$ano." "." and (cod_obra!='' and cod_obra!='0' and cod_obra IS NOT NULL) and condicion_actividad=2");
														foreach($lista_aux_2 as $aux_2){
														  $this->Session->delete('codigos');
														  $ano_obra                 =       $aux_2['cepd01_compromiso_cuerpo']['ano_documento'];
														  $numero_documento         =       $aux_2['cepd01_compromiso_cuerpo']['numero_documento'];
														  $codigo_obra              =       $aux_2['cepd01_compromiso_cuerpo']['cod_obra'];

														  	       $lista                 = $this->cepd01_compromiso_cuerpo->findAll($this->condicionNDEP()." and cod_dep='".$cod_dep."' and ano_documento = ".$ano_obra." and numero_documento='".$numero_documento."' "." ");
															  	   $cfpd07_obras_partidas =  $this->cfpd07_obras_partidas->findAll($this->condicionNDEP()." and cod_dep='".$cod_dep."'   and ano_estimacion   = ".$ano."      and upper(cod_obra)='".strtoupper($codigo_obra)."' ", null, 'cod_sector,cod_programa,cod_sub_prog,cod_proyecto,cod_activ_obra, cod_partida,cod_generica,cod_especifica,cod_sub_espec,cod_auxiliar ASC');
															  	   foreach($lista as $aux){
															  	   	    $contador_exitentes  =  0;
																		$ano_documento       =  $aux['cepd01_compromiso_cuerpo']['ano_documento'];
																		$numero_documento    =  $aux['cepd01_compromiso_cuerpo']['numero_documento'];
														                $lista2              =  $this->cepd01_compromiso_partidas->findAll($this->condicionNDEP()." and cod_dep='".$cod_dep."' and ano_documento = ".$ano_obra." and numero_documento='".$numero_documento."' ");
																		  	    foreach($lista2 as $aux2){
																		  	    	          $cod_sector2          =   $aux2['cepd01_compromiso_partidas']['cod_sector'];
																							  $cod_programa2        =   $aux2['cepd01_compromiso_partidas']['cod_programa'];
																							  $cod_sub_prog2        =   $aux2['cepd01_compromiso_partidas']['cod_sub_prog'];
																							  $cod_proyecto2        =   $aux2['cepd01_compromiso_partidas']['cod_proyecto'];
																							  $cod_activ_obra2      =   $aux2['cepd01_compromiso_partidas']['cod_activ_obra'];
																							  $cod_partida2         =   $aux2['cepd01_compromiso_partidas']['cod_partida'];
																							  $cod_generica2        =   $aux2['cepd01_compromiso_partidas']['cod_generica'];
																							  $cod_especifica2      =   $aux2['cepd01_compromiso_partidas']['cod_especifica'];
																							  $cod_sub_espec2       =   $aux2['cepd01_compromiso_partidas']['cod_sub_espec'];
																							  $cod_auxiliar2        =   $aux2['cepd01_compromiso_partidas']['cod_auxiliar'];
																							  $marca = 0;
																		  	    		foreach($cfpd07_obras_partidas  as  $aux_cfpd07_obras_partidas){
																							  $cod_sector          =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_sector'];
																							  $cod_programa        =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_programa'];
																							  $cod_sub_prog        =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_sub_prog'];
																							  $cod_proyecto        =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_proyecto'];
																							  $cod_activ_obra      =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_activ_obra'];
																							  $cod_partida         =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_partida'];
																							  $cod_generica        =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_generica'];
																							  $cod_especifica      =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_especifica'];
																							  $cod_sub_espec       =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_sub_espec'];
																							  $cod_auxiliar        =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_auxiliar'];
																								  if($cod_sector     == $cod_sector2     &&
																								     $cod_programa   == $cod_programa2   &&
																								     $cod_sub_prog   == $cod_sub_prog2   &&
																								     $cod_proyecto   == $cod_proyecto2   &&
																								     $cod_activ_obra == $cod_activ_obra2 &&
																								     $cod_partida    == $cod_partida2    &&
																								     $cod_generica   == $cod_generica2   &&
																								     $cod_especifica == $cod_especifica2 &&
																								     $cod_sub_espec  == $cod_sub_espec2  &&
																								     $cod_auxiliar   == $cod_auxiliar2){
																								     	$marca = 1;
																								  }//fin if
																		  	    		 }//fin foreach
																		  	    		 if($marca==0){$contador_exitentes++;
																							$_SESSION["codigos"][$contador_exitentes][0]=$ano_obra;
																							$_SESSION["codigos"][$contador_exitentes][1]=$cod_sector2;
																							$_SESSION["codigos"][$contador_exitentes][2]=$cod_programa2;
																							$_SESSION["codigos"][$contador_exitentes][3]=$cod_sub_prog2;
																							$_SESSION["codigos"][$contador_exitentes][4]=$cod_proyecto2;
																							$_SESSION["codigos"][$contador_exitentes][5]=$cod_activ_obra2;
																							$_SESSION["codigos"][$contador_exitentes][6]=$cod_partida2;
																							$_SESSION["codigos"][$contador_exitentes][7]=$cod_generica2;
																							$_SESSION["codigos"][$contador_exitentes][8]=$cod_especifica2;
																							$_SESSION["codigos"][$contador_exitentes][9]=$cod_sub_espec2;
																							$_SESSION["codigos"][$contador_exitentes][10]=$cod_auxiliar2;
																		  	    		 }
																				}//fin foreach
																				if($contador_exitentes==0){
														                           $sw = $this->cepd01_compromiso_cuerpo->execute("UPDATE cepd01_compromiso_cuerpo set cod_obra='".$codigo_obra."' where ".$this->condicionNDEP()." and cod_dep='".$cod_dep."' and ano_documento='".$ano_documento."'   and  numero_documento='".$numero_documento."'    ");
																				   foreach($lista2 as $aux2){
																		  	    	          $cod_sector          =   $aux2['cepd01_compromiso_partidas']['cod_sector'];
																							  $cod_programa        =   $aux2['cepd01_compromiso_partidas']['cod_programa'];
																							  $cod_sub_prog        =   $aux2['cepd01_compromiso_partidas']['cod_sub_prog'];
																							  $cod_proyecto        =   $aux2['cepd01_compromiso_partidas']['cod_proyecto'];
																							  $cod_activ_obra      =   $aux2['cepd01_compromiso_partidas']['cod_activ_obra'];
																							  $cod_partida         =   $aux2['cepd01_compromiso_partidas']['cod_partida'];
																							  $cod_generica        =   $aux2['cepd01_compromiso_partidas']['cod_generica'];
																							  $cod_especifica      =   $aux2['cepd01_compromiso_partidas']['cod_especifica'];
																							  $cod_sub_espec       =   $aux2['cepd01_compromiso_partidas']['cod_sub_espec'];
																							  $cod_auxiliar        =   $aux2['cepd01_compromiso_partidas']['cod_auxiliar'];
																							  $monto               =   $aux2['cepd01_compromiso_partidas']['monto'];
																							  $cond2               =   $this->condicionNDEP()." and cod_dep='".$cod_dep."' and ano='".$ano_obra."' and cod_sector='$cod_sector' and cod_programa='$cod_programa' and cod_sub_prog='$cod_sub_prog' and cod_proyecto='$cod_proyecto' and cod_activ_obra='$cod_activ_obra' and cod_partida='$cod_partida' and cod_generica='$cod_generica' and cod_especifica='$cod_especifica' and cod_sub_espec='$cod_sub_espec' and cod_auxiliar='$cod_auxiliar'";
															                                  $sql55               =   "UPDATE cfpd05 SET precompromiso_obras = precompromiso_obras + ".$monto." WHERE ".$cond2."";
																                              $sw155               =   $this->cepd01_compromiso_partidas->execute($sql55);

																                              $sql_aux = "cod_presi=".$cod_presi." and cod_entidad=".$cod_entidad." and cod_tipo_inst=".$cod_tipo_inst." and  cod_inst=".$cod_inst." and cod_dep=".$cod_dep." and ano_estimacion=".$ano." and upper(cod_obra)='".strtoupper($codigo_obra)."' and cod_sector=".$cod_sector."   and   cod_programa=".$cod_programa."   and   cod_sub_prog=".$cod_sub_prog."   and   cod_proyecto=".$cod_proyecto."   and   cod_activ_obra=".$cod_activ_obra."   and   cod_partida=".$cod_partida."   and   cod_generica=".$cod_generica."   and   cod_especifica=".$cod_especifica."   and   cod_sub_espec=".$cod_sub_espec."   and   cod_auxiliar=".$cod_auxiliar.";";
														//						              $sw455 = $this->cscd04_ordencompra_partidas->execute("UPDATE cfpd07_obras_partidas SET monto_contratado=monto_contratado+".$monto." where ".$sql_aux);

																					          $sql_aux = "cod_presi=".$cod_presi." and cod_entidad=".$cod_entidad." and cod_tipo_inst=".$cod_tipo_inst." and  cod_inst=".$cod_inst." and cod_dep=".$cod_dep." and ano_estimacion=".$ano." and upper(cod_obra)='".strtoupper($codigo_obra)."';";
														//						              $sw455   = $this->cscd04_ordencompra_partidas->execute("UPDATE cfpd07_obras_cuerpo SET monto_contratado=monto_contratado+".$monto." where ".$sql_aux);



																				  }//fin foreach
																				  $this->set('Message_existe', 'Los datos fueron  guardados');
																				}else{
																						$this->set('msg_error', 'los siguientes códigos presupuestarios no estan registrados en la relación de obras');
																						$this->set('codigos', $_SESSION["codigos"]);
																				}//fin if
																	}//fin foreach
														  }//fin function
//////////////////////// FIN PARTE V///////////////////////////////////////



//////////////////////// PARTE VI///////////////////////////////////////
$lista_aux_2                 =  $this->cscd04_ordencompra_encabezado->findAll($this->condicionNDEP()." and cod_dep='".$cod_dep."' and ano_orden_compra = ".$ano." "." and (cod_obra!='' and cod_obra!='0' and cod_obra IS NOT NULL)  and condicion_actividad=1 ");
foreach($lista_aux_2 as $aux_2){

  $ano_obra                 =       $aux_2['cscd04_ordencompra_encabezado']['ano_orden_compra'];
  $numero_documento         =       $aux_2['cscd04_ordencompra_encabezado']['numero_orden_compra'];
  $codigo_obra              =       $aux_2['cscd04_ordencompra_encabezado']['cod_obra'];

       $lista                 =  $this->cscd04_ordcom_modificacion_cuerpo->findAll($this->condicionNDEP()." and cod_dep='".$cod_dep."' and ano_orden_compra = ".$ano_obra." and numero_orden_compra='".$numero_documento."' "."  and condicion_actividad=1 ");
  	   foreach($lista as $aux){
          $numero_modificacion =   $aux['cscd04_ordcom_modificacion_cuerpo']['numero_modificacion'];
		  $tipo_modificacion   =   $aux['cscd04_ordcom_modificacion_cuerpo']['tipo_modificacion'];

					  $lista2              =  $this->cscd04_ordencompra_modificacion_partidas->findAll($this->condicionNDEP()." and cod_dep='".$cod_dep."' and ano_orden_compra = ".$ano_estimacion." and numero_modificacion='".$numero_modificacion."' ");
					  foreach($lista2 as $aux2){
							  $cod_sector          =   $aux2['cscd04_ordencompra_modificacion_partidas']['cod_sector'];
							  $cod_programa        =   $aux2['cscd04_ordencompra_modificacion_partidas']['cod_programa'];
							  $cod_sub_prog        =   $aux2['cscd04_ordencompra_modificacion_partidas']['cod_sub_prog'];
							  $cod_proyecto        =   $aux2['cscd04_ordencompra_modificacion_partidas']['cod_proyecto'];
							  $cod_activ_obra      =   $aux2['cscd04_ordencompra_modificacion_partidas']['cod_activ_obra'];
							  $cod_partida         =   $aux2['cscd04_ordencompra_modificacion_partidas']['cod_partida'];
							  $cod_generica        =   $aux2['cscd04_ordencompra_modificacion_partidas']['cod_generica'];
							  $cod_especifica      =   $aux2['cscd04_ordencompra_modificacion_partidas']['cod_especifica'];
							  $cod_sub_espec       =   $aux2['cscd04_ordencompra_modificacion_partidas']['cod_sub_espec'];
							  $cod_auxiliar        =   $aux2['cscd04_ordencompra_modificacion_partidas']['cod_auxiliar'];
							  $monto               =   $aux2['cscd04_ordencompra_modificacion_partidas']['monto'];



                      if($tipo_modificacion=='1'){
                      	        $sql_aux = "cod_presi=".$cod_presi." and cod_entidad=".$cod_entidad." and cod_tipo_inst=".$cod_tipo_inst." and  cod_inst=".$cod_inst." and cod_dep=".$cod_dep." and ano=".$ano." and cod_sector=".$cod_sector."   and   cod_programa=".$cod_programa."   and   cod_sub_prog=".$cod_sub_prog."   and   cod_proyecto=".$cod_proyecto."   and   cod_activ_obra=".$cod_activ_obra."   and   cod_partida=".$cod_partida."   and   cod_generica=".$cod_generica."   and   cod_especifica=".$cod_especifica."   and   cod_sub_espec=".$cod_sub_espec."   and   cod_auxiliar=".$cod_auxiliar.";";
                                $sql55  = "UPDATE cfpd05 SET precompromiso_obras = precompromiso_obras - ".$monto." WHERE ".$sql_aux;
			                    $sw155 = $this->cscd04_ordencompra_partidas->execute($sql55);

			                    $sql_aux = "cod_presi=".$cod_presi." and cod_entidad=".$cod_entidad." and cod_tipo_inst=".$cod_tipo_inst." and  cod_inst=".$cod_inst." and cod_dep=".$cod_dep." and ano_estimacion=".$ano." and upper(cod_obra)='".strtoupper($codigo_obra)."' and cod_sector=".$cod_sector."   and   cod_programa=".$cod_programa."   and   cod_sub_prog=".$cod_sub_prog."   and   cod_proyecto=".$cod_proyecto."   and   cod_activ_obra=".$cod_activ_obra."   and   cod_partida=".$cod_partida."   and   cod_generica=".$cod_generica."   and   cod_especifica=".$cod_especifica."   and   cod_sub_espec=".$cod_sub_espec."   and   cod_auxiliar=".$cod_auxiliar.";";
				                $sw455 = $this->cscd04_ordencompra_partidas->execute("UPDATE cfpd07_obras_partidas SET monto_contratado=monto_contratado+".$monto." where ".$sql_aux);

					            $sql_aux = "cod_presi=".$cod_presi." and cod_entidad=".$cod_entidad." and cod_tipo_inst=".$cod_tipo_inst." and  cod_inst=".$cod_inst." and cod_dep=".$cod_dep." and ano_estimacion=".$ano." and upper(cod_obra)='".strtoupper($codigo_obra)."';";
				                $sw455   = $this->cscd04_ordencompra_partidas->execute("UPDATE cfpd07_obras_cuerpo SET monto_contratado=monto_contratado+".$monto." where ".$sql_aux);
						}else{
							    $sql_aux = "cod_presi=".$cod_presi." and cod_entidad=".$cod_entidad." and cod_tipo_inst=".$cod_tipo_inst." and  cod_inst=".$cod_inst." and cod_dep=".$cod_dep." and ano=".$ano." and cod_sector=".$cod_sector."   and   cod_programa=".$cod_programa."   and   cod_sub_prog=".$cod_sub_prog."   and   cod_proyecto=".$cod_proyecto."   and   cod_activ_obra=".$cod_activ_obra."   and   cod_partida=".$cod_partida."   and   cod_generica=".$cod_generica."   and   cod_especifica=".$cod_especifica."   and   cod_sub_espec=".$cod_sub_espec."   and   cod_auxiliar=".$cod_auxiliar.";";
                                $sql55  = "UPDATE cfpd05 SET precompromiso_obras = precompromiso_obras + ".$monto." WHERE ".$sql_aux;
			                    $sw155 = $this->cscd04_ordencompra_partidas->execute($sql55);

			                    $sql_aux = "cod_presi=".$cod_presi." and cod_entidad=".$cod_entidad." and cod_tipo_inst=".$cod_tipo_inst." and  cod_inst=".$cod_inst." and cod_dep=".$cod_dep." and ano_estimacion=".$ano." and upper(cod_obra)='".strtoupper($codigo_obra)."' and cod_sector=".$cod_sector."   and   cod_programa=".$cod_programa."   and   cod_sub_prog=".$cod_sub_prog."   and   cod_proyecto=".$cod_proyecto."   and   cod_activ_obra=".$cod_activ_obra."   and   cod_partida=".$cod_partida."   and   cod_generica=".$cod_generica."   and   cod_especifica=".$cod_especifica."   and   cod_sub_espec=".$cod_sub_espec."   and   cod_auxiliar=".$cod_auxiliar.";";
					            $sw455 = $this->cscd04_ordencompra_partidas->execute("UPDATE cfpd07_obras_partidas SET monto_contratado=monto_contratado-".$monto." where ".$sql_aux);

						        $sql_aux = "cod_presi=".$cod_presi." and cod_entidad=".$cod_entidad." and cod_tipo_inst=".$cod_tipo_inst." and  cod_inst=".$cod_inst." and cod_dep=".$cod_dep." and ano_estimacion=".$ano." and upper(cod_obra)='".strtoupper($codigo_obra)."';";
					            $sw455   = $this->cscd04_ordencompra_partidas->execute("UPDATE cfpd07_obras_cuerpo SET monto_contratado=monto_contratado-".$monto." where ".$sql_aux);
						}//fin else
			         }//fin foreach
  	   }//fin foreach
}//fin foreachecho

////////////////////////FIN PARTE VI///////////////////////////////////////


	}//fin if

}//fin function















function actualizar_precompromiso_en_cero($var1=null){

  set_time_limit(0);
  $this->layout="ajax";
  $cod_presi                =       $this->Session->read('SScodpresi');
  $cod_entidad              =       $this->Session->read('SScodentidad');
  $cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
  $cod_inst                 =       $this->Session->read('SScodinst');
  $cod_dep                  =       $this->Session->read('SScoddep');
  $ano                      =       $this->ano_ejecucion();

$cfpd07_obras_partidas =  $this->cfpd07_obras_partidas->findAll($this->condicionNDEP()." and cod_dep='".$var1."'        and ano_estimacion   = ".$ano." ", null, 'cod_sector,cod_programa,cod_sub_prog,cod_proyecto,cod_activ_obra, cod_partida,cod_generica,cod_especifica,cod_sub_espec,cod_auxiliar ASC');
	  	  foreach($cfpd07_obras_partidas  as  $aux_cfpd07_obras_partidas){
									  $cod_sector          =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_sector'];
									  $cod_programa        =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_programa'];
									  $cod_sub_prog        =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_sub_prog'];
									  $cod_proyecto        =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_proyecto'];
									  $cod_activ_obra      =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_activ_obra'];
									  $cod_partida         =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_partida'];
									  $cod_generica        =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_generica'];
									  $cod_especifica      =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_especifica'];
									  $cod_sub_espec       =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_sub_espec'];
									  $cod_auxiliar        =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_auxiliar'];
									  $monto               =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['monto'];

								      $cond2               =   $this->condicionNDEP()." and cod_dep='".$var1."' and ano='".$ano."' and cod_sector='$cod_sector' and cod_programa='$cod_programa' and cod_sub_prog='$cod_sub_prog' and cod_proyecto='$cod_proyecto' and cod_activ_obra='$cod_activ_obra' and cod_partida='$cod_partida' and cod_generica='$cod_generica' and cod_especifica='$cod_especifica' and cod_sub_espec='$cod_sub_espec' and cod_auxiliar='$cod_auxiliar'";
	                                  $sql55               =   "UPDATE cfpd05 SET precompromiso_congelado 	 = 0  WHERE ".$cond2." ";
		                              $sw155               =   $this->cscd04_ordencompra_partidas->execute($sql55);

		  }//fin foreach
}//fin function







}//fin class

?>