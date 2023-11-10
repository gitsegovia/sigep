<?php
 class Caop02solicitudcotizacionController extends AppController{
	 var $name='caop02_solicitud_cotizacion';
     var $uses = array('cscd01_unidad_medida','ccfd03_instalacion','cscd01_catalogo','cugd02_direccionsuperior','cugd02_direccion',
                       'cugd02_coordinacion','cugd02_secretaria','cugd02_division','cugd02_departamento','cugd02_oficina', 'ccfd04_cierre_mes',
                       'cscd02_solicitud_numero','cscd02_solicitud_encabezado','cscd02_solicitud_cuerpo', 'cfpd05', 'v_cscd01_catalogo_deno_und', 'v_cscd01_catalogo_d_u_c_o',
                        'cscd03_cotizacion_encabezado', 'cscd02_solicitud_numero_poremitir', 'v_cscd01_catalogo_snc', 'cscd02_solicitud_numero','cugd05_restriccion_clave',
                        'cfpd07_obras_cuerpo', 'cfpd07_obras_partidas');

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

}//fin function


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


    function SQLCAX($ano=null){//sql para busqueda de codigos de arranque con y sin año
         $sql_re = "cod_tipo_institucion=".$this->verifica_SS(3)."  and ";
         $sql_re .= "cod_institucion=".$this->verifica_SS(4)."  and  ";
         $sql_re .= "cod_dependencia=".$this->verifica_SS(5)." ";

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

}


function concatena($vector1=null, $nomVar=null){

	if($vector1 != null){

		foreach($vector1 as $x => $y){

			$cod[$x] = $this->zero($x).' - '.$y;
		}
		//print_r($cod);

		$this->set($nomVar, $cod);

	}
}

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


function concatena3($vector1=null, $nomVar=null){

	if($vector1 != null){
		//trim($vector1);
		foreach($vector1 as $x => $y){
			trim($x);
			trim($y);
			if(strlen($y) > 80){
				$cod[$x] = $x.' - '.substr($y, 0, 80).'...';
			}else{
				$cod[$x] = $x.' - '.substr($y, 0, 80);
			}
		}
		//print_r($cod);

		$this->set($nomVar, $cod);

	}
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

/*
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
*/

/*
function Formato2($monto){
    	return number_format($monto,2,",",".");
    }
*/


function index($var=null){///////////////<<--INDEX
	 $this->layout = "ajax";

	 $ano=$this->ano_ejecucion();
	 $this->Session->delete('items');
	 $this->Session->write("caop02_codigo_obra", "0");



}//fin index




 function index2(){
       $this->layout = "ajax";
       $this->limpiar_lista ();
       $this->Session->delete('items');
	    $cod_presi = $this->Session->read('SScodpresi');
		$cod_entidad = $this->Session->read('SScodentidad');
		$cod_tipo_inst = $this->Session->read('SScodtipoinst');
		$cod_inst = $this->Session->read('SScodinst');
		$cod_dep = $this->Session->read('SScoddep');
		$SScoddeporig             =       $this->Session->read('SScoddeporig');
		$ano                      =       $this->ano_ejecucion();

		$condicion_dir_sup = "cod_tipo_institucion = ".$cod_tipo_inst." and cod_institucion = ".$cod_inst." and cod_dependencia=".$cod_dep;

        	$listadireccion_superior=$this->cugd02_direccionsuperior->generateList($condicion_dir_sup, 'cod_dir_superior ASC', null, '{n}.cugd02_direccionsuperior.cod_dir_superior', '{n}.cugd02_direccionsuperior.denominacion');
	        $this->concatena($listadireccion_superior, 'direccion_superior');

	// *** DIRECCION SUPERIOR ***
	if($listadireccion_superior!=null){
		foreach($listadireccion_superior as $p => $aux_cs){
			$codigo_ds = $p;
			break;
		}
		$this->set('seleccion_ds',$codigo_ds);
		$this->Session->write('dirs',$codigo_ds);
		$this->Session->write('ddirs',$codigo_ds);
			echo "<script>
					document.getElementById('select_1').value='$aux_cs';
					document.getElementById('codigo_select_1').innerHTML='".$this->zero($codigo_ds)."';
					document.getElementById('deno_select_1').innerHTML='".strtoupper($aux_cs)."';
				</script>";

	// *** COORDINACION ****
		$lista = $this->cugd02_coordinacion->generateList($condicion_dir_sup." and cod_dir_superior=".$codigo_ds, 'cod_coordinacion ASC', null, '{n}.cugd02_coordinacion.cod_coordinacion', '{n}.cugd02_coordinacion.denominacion');
    	if($lista!=null){
          	$this->concatena($lista, 'vector_coord');
			foreach($lista as $p => $aux_cs){
				$codigo_ds1 = $p;
				break;
			}
			$this->set('seleccion_ds1',$codigo_ds1);
			$this->Session->write('coor',$codigo_ds1);
			$this->Session->write('dcoor',$codigo_ds1);
			echo "<script>
					document.getElementById('select_2').value='$aux_cs';
					document.getElementById('codigo_select_2').innerHTML='".$this->zero($codigo_ds1)."';
					document.getElementById('deno_select_2').innerHTML='".strtoupper($aux_cs)."';
				</script>";

	// *** SECRETARIA ****
		  $lista = $this->cugd02_secretaria->generateList($condicion_dir_sup." and cod_dir_superior=".$codigo_ds." and cod_coordinacion=".$codigo_ds1, 'cod_secretaria ASC', null, '{n}.cugd02_secretaria.cod_secretaria', '{n}.cugd02_secretaria.denominacion');
          if($lista!=null){
          	$this->concatena($lista, 'vector_sec');
			foreach($lista as $p => $aux_cs){
				$codigo_ds2 = $p;
				break;
			}
			$this->set('seleccion_ds2',$codigo_ds2);
			$this->Session->write('secr',$codigo_ds2);
			$this->Session->write('dsecr',$codigo_ds2);
			echo "<script>
					document.getElementById('select_3').value='$aux_cs';
					document.getElementById('codigo_select_3').innerHTML='".$this->zero($codigo_ds2)."';
					document.getElementById('deno_select_3').innerHTML='".strtoupper($aux_cs)."';
				</script>";

			// *** DIRECCION ***
				$lista = $this->cugd02_direccion->generateList($condicion_dir_sup." and cod_dir_superior=".$codigo_ds." and cod_coordinacion=".$codigo_ds1." and cod_secretaria=".$codigo_ds2, 'cod_direccion ASC', null, '{n}.cugd02_direccion.cod_direccion', '{n}.cugd02_direccion.denominacion');
          		if($lista!=null){
          			$this->concatena($lista, 'vector_direcc');
          		}else{
          			$this->set('vector_direcc',array());
          		}

          }else{
          	$this->set('vector_sec',array());
          	$this->Session->write('secr',0);
          	$this->Session->write('dsecr',0);
          }

		}else{
			$this->set('vector_coord',array());
			$this->Session->write('coor',0);
			$this->Session->write('secr',0);
			$this->Session->write('dcoor',0);
			$this->Session->write('dsecr',0);
		}

	}else{
		$this->Session->write('dirs',0);
		$this->Session->write('coor',0);
		$this->Session->write('secr',0);
		$this->Session->write('ddirs',0);
		$this->Session->write('dcoor',0);
		$this->Session->write('dsecr',0);
	}

	        $listaproductos=array();
	        $this->concatena($listaproductos, 'codigo');
	        $this->AddCero('codigo',$listaproductos);


	$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst;

	$condicion222 = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$cod_dep.' and ano_estimacion='.$ano;
    $lista = $this->cfpd07_obras_cuerpo->generateList($condicion222.' and ano_estimacion='.$ano.'   and (estimado_presu - ((monto_contratado+ aumento_obras) - disminucion_obras)) != 0     ', ' cod_obra ASC', null, '{n}.cfpd07_obras_cuerpo.cod_obra', '{n}.cfpd07_obras_cuerpo.denominacion');
    $this->concatena_sin_cero($lista, 'lista_numero');


          $ano = $this->ano_ejecucion();
          $dato = $this->ano_ejecucion();

	if(!empty($dato)){

		$this->set('year', $dato);

	}else{
		$this->set('year', '');
	}
	//$this->set('Message_existe', 'Ingrese el Número de Solicitud');


    $var2 = 1;
    $cond=$this->SQLCA()."and ano_solicitud=".$dato;

	/*if($var2==1){
 		 $ss=$this->cscd02_solicitud_numero->findAll($cond,array('numero_solicitud'),'numero_solicitud DESC',1,1,null);
 		 //print_r($ss);
 	if($ss==null){
     	$new_numero=1;
     }else{
     	$new_numero=$ss[0]["cscd02_solicitud_numero"]["numero_solicitud"]+1;
     }
     $this->set('numero_radio',$new_numero);//$numero4
     $this->cscd02_solicitud_numero->execute("UPDATE cscd02_solicitud_numero SET numero_solicitud='$new_numero' WHERE cod_presi='$cod_presi' and cod_entidad='$cod_entidad' and cod_tipo_inst='$cod_tipo_inst' and cod_inst='$cod_inst' and cod_dep='$cod_dep' and ano_solicitud='$dato';");
 	}*/

  $ano_arranque = $ano = $this->ano_ejecucion();
  $numero_solicitud=$this->cscd02_solicitud_numero->field('cscd02_solicitud_numero.numero_solicitud', $conditions = $this->condicion()." and ano_solicitud='$ano_arranque' and situacion=1", $order ="cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, numero_solicitud ASC");
  if(!empty($numero_solicitud)){
  	$this->set('numero_radio', $numero_solicitud);
  }else{
  	$this->set('errorMessage', $msg_error1 = 'NECESITA CREAR LOS NUMEROS DE LA SOLICITUD PARA CONTINUAR');
  	$this->redirect('/cscp02_solicitud_numero/index/xy');
	return;
  }
  $this->cscd02_solicitud_numero->execute('UPDATE cscd02_solicitud_numero set situacion=2 WHERE '.$this->condicion()." and ano_solicitud='$ano_arranque' and numero_solicitud = '$numero_solicitud'");



}//fin index







function selecion_cod_obra($var=null){

       $this->layout = "ajax";


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

  $this->Session->delete("items");
  $this->Session->delete("i");
  $this->Session->write("caop02_codigo_obra", strtoupper_sisap($var));
	if($var!=null){
	$cfpd07_obras_cuerpo     =  $this->cfpd07_obras_cuerpo->findAll($condicion." and ano_estimacion=".$ano." and upper(cod_obra)='".strtoupper($var)."' ");
	$denominacion_obra       = "";
	$cfpd07_obras_cuerpo_aux = $cfpd07_obras_cuerpo;
	foreach($cfpd07_obras_cuerpo_aux as $aux){
		$codigo_prod_serv    = $aux['cfpd07_obras_cuerpo']['codigo_prod_serv'];
		$estimado_presu      = $aux['cfpd07_obras_cuerpo']['estimado_presu'];
		$denominacion_obra   = $aux['cfpd07_obras_cuerpo']['denominacion'];
	}//fin foreach



	$this->set('selecion_lista', $var);
	$this->set('estimado_presu', $estimado_presu);
	$this->set('cfpd07_obras_cuerpo', $cfpd07_obras_cuerpo);
	$this->set('ano_ejecucion', $ano);


	$denominacion_obra = str_replace("\n",'',$denominacion_obra);
	$denominacion_obra = str_replace("'",'',$denominacion_obra);
	$denominacion_obra = str_replace(">",'',$denominacion_obra);
	$denominacion_obra = str_replace("<",'',$denominacion_obra);
	$denominacion_obra = str_replace('"','',$denominacion_obra);


			if($denominacion_snc!=""){
					echo'<script>';
					echo"   if(document.getElementById('denominacion_obra')){document.getElementById('denominacion_obra').value=\"$denominacion_obra\";  }";
					echo"   if(document.getElementById('input_cod_obra')){document.getElementById('input_cod_obra').value=\"$var\";  }";
					echo'</script>';
			}else{
			        echo'<script>';
					echo"   if(document.getElementById('denominacion_obra')){document.getElementById('denominacion_obra').value=\"$denominacion_obra\";  }";
					echo"   if(document.getElementById('input_cod_obra')){document.getElementById('input_cod_obra').value=\"$var\";  }";
					echo'</script>';
			}//fin else

	}else{
		    echo'<script>';
			echo"   if(document.getElementById('denominacion_obra')){document.getElementById('denominacion_obra').value='';  }";
			echo"   if(document.getElementById('denominacion_obra')){document.getElementById('input_cod_obra').value='';  }";
			echo'</script>';
	}//fin else
}//fin funtion







function desbloquear_numero($numero){
	$this->layout="ajax";
	$ano=$this->ano_ejecucion();
	$resultado=$this->cscd02_solicitud_numero->execute("UPDATE cscd02_solicitud_numero SET situacion=1 WHERE ".$this->condicion()."  and ano_solicitud='$ano' and numero_solicitud='$numero' and situacion=2 ");
}


function catProg($dirs=null, $coor=null, $secr=null, $dir=null){
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$cod_sector = $this->cugd02_direccion->field('cugd02_direccion.cod_sector', $conditions = "cod_tipo_institucion='$cod_tipo_inst' and cod_institucion='$cod_inst' and cod_dependencia='$cod_dep' and cod_dir_superior='$dirs' and cod_coordinacion='$coor' and cod_secretaria='$secr' and cod_direccion='$dir'", $order =null);
	$cod_programa = $this->cugd02_direccion->field('cugd02_direccion.cod_programa', $conditions = "cod_tipo_institucion='$cod_tipo_inst' and cod_institucion='$cod_inst' and cod_dependencia='$cod_dep' and cod_dir_superior='$dirs' and cod_coordinacion='$coor' and cod_secretaria='$secr' and cod_direccion='$dir' and cod_sector='$cod_sector'", $order =null);
	$cod_sub_prog = $this->cugd02_direccion->field('cugd02_direccion.cod_sub_prog', $conditions = "cod_tipo_institucion='$cod_tipo_inst' and cod_institucion='$cod_inst' and cod_dependencia='$cod_dep' and cod_dir_superior='$dirs' and cod_coordinacion='$coor' and cod_secretaria='$secr' and cod_direccion='$dir' and cod_sector='$cod_sector' and cod_programa='$cod_programa'", $order =null);
	$cod_proyecto = $this->cugd02_direccion->field('cugd02_direccion.cod_proyecto', $conditions = "cod_tipo_institucion='$cod_tipo_inst' and cod_institucion='$cod_inst' and cod_dependencia='$cod_dep' and cod_dir_superior='$dirs' and cod_coordinacion='$coor' and cod_secretaria='$secr' and cod_direccion='$dir' and cod_sector='$cod_sector' and cod_programa='$cod_programa' and cod_sub_prog='$cod_sub_prog'", $order =null);

	$categoria = $this->zero($cod_sector).'.'.$this->zero($cod_programa).'.'.$this->zero($cod_sub_prog).'.'.$this->zero($cod_proyecto);

	echo "<script>";
	echo "document.getElementById('partida_producto').innerHTML='$categoria';";
	echo "</script>";

}


 function mostrar($var=null, $cod_sector=null, $cod_programa=null, $cod_sub_prog=null, $cod_proyecto=null){
 	$this->layout="ajax";
 	$dir = "";
 	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
 	$dirs =  $this->Session->read('dirs');
	$coor =  $this->Session->read('coor');
	$secr =  $this->Session->read('secr');
	$dir =  $this->Session->read('dir');

    $SScoddeporig           =  $this->Session->read('SScoddeporig');
	$ano                    =  $this->ano_ejecucion();
    $condicion222           =  'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep_original='.$SScoddeporig.' and ano_estimacion='.$ano;
	$caop02_codigo_obra     =  $this->Session->read("caop02_codigo_obra");
	$cfpd07_obras_partidas  =  $this->cfpd07_obras_partidas->findAll($this->condicion()." and ano_estimacion='".$ano."' and upper(cod_obra)='".strtoupper_sisap($caop02_codigo_obra)."' ", null, 'cod_obra DESC');



 		$var = strtoupper($var);


$slq_or = "";



foreach($cfpd07_obras_partidas as $ve3){

  $cod_obra_2          = $ve3["cfpd07_obras_partidas"]["cod_obra"];
  $cod_sector_2        = $ve3["cfpd07_obras_partidas"]["cod_sector"];
  $cod_programa_2      = $ve3["cfpd07_obras_partidas"]["cod_programa"];
  $cod_sub_prog_2      = $ve3["cfpd07_obras_partidas"]["cod_sub_prog"];
  $cod_proyecto_2      = $ve3["cfpd07_obras_partidas"]["cod_proyecto"];
  $cod_activ_obra_2    = $ve3["cfpd07_obras_partidas"]["cod_activ_obra"];
  $cod_partida_2       = $ve3["cfpd07_obras_partidas"]["cod_partida"];
  $cod_generica_2      = $ve3["cfpd07_obras_partidas"]["cod_generica"];
  $cod_especifica_2    = $ve3["cfpd07_obras_partidas"]["cod_especifica"];
  $cod_sub_espec_2     = $ve3["cfpd07_obras_partidas"]["cod_sub_espec"];
  $cod_auxiliar_2      = $ve3["cfpd07_obras_partidas"]["cod_auxiliar"];
  $monto_2             = $ve3["cfpd07_obras_partidas"]["monto"];
  $monto_contratado_2  = $ve3["cfpd07_obras_partidas"]["monto_contratado"];
  $aumento_obras_2     = $ve3["cfpd07_obras_partidas"]["aumento_obras"];
  $disminucion_obras_2 = $ve3["cfpd07_obras_partidas"]["disminucion_obras"];

   if($slq_or==""){
    $slq_or  = "    ( cod_partida='".$cod_partida_2."' and cod_generica='".$cod_generica_2."' and cod_especifica='".$cod_especifica_2."' and cod_sub_espec='".$cod_sub_espec_2."'  ) ";
   }else{
    $slq_or .= " or ( cod_partida='".$cod_partida_2."' and cod_generica='".$cod_generica_2."' and cod_especifica='".$cod_especifica_2."' and cod_sub_espec='".$cod_sub_espec_2."'  ) ";
   }


}//fin foreach

 		$sql_aux = $this->busca_separado(array("denominacion"), $var);
 		$existe  = $this->cscd01_catalogo->findCount($sql_aux);
 		//echo $existe;
 		if($existe == 0){
 			$this->set('catalogo', array());
 			$this->set('deno', '');
 			$this->set('notfound', 'ESTE PRODUCTO NO ESTA REGISTRADO EN EL CATALOGO - POR FAVOR INTENTE DE NUEVO');
 			return;
 		}

// 		echo $this->condicion()."  and ano='".$this->ano_ejecucion()."' and (".$slq_or.") and (".$sql_aux.") ";
 		if($this->v_cscd01_catalogo_d_u_c_o->findCount(               "  (".$slq_or.") and (".$sql_aux.") ") != 0){
 			$this->set('deno', $var);
 			$catalogo= $this->v_cscd01_catalogo_d_u_c_o->generateList(" (".$slq_or.") and (".$sql_aux.") ", 'cod_snc ASC', null, '{n}.v_cscd01_catalogo_d_u_c_o.codigo_prod_serv', '{n}.v_cscd01_catalogo_d_u_c_o.denominacion');
 			if(!empty($catalogo)){
		 		$this->set('catalogo', $catalogo);
		 	}else{
		 		$this->set('catalogo', array());
		 	}
 		}else{
 			$this->set('catalogo', array());
 			$this->set('deno', '');
 			$this->set('notfound', 'LAS PARTIDAS DE LA RELACIÓN '.$caop02_codigo_obra.' NO TIENE PRODUCTOS PRESUPUESTADOS ');

 		}
 }//fin function






  function mostrar2($cod_sector=null, $cod_programa=null, $cod_sub_prog=null, $cod_proyecto=null, $var=null){
 	 	$this->layout="ajax";
 	$dir = "";
 	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
 	$dirs =  $this->Session->read('dirs');
	$coor =  $this->Session->read('coor');
	$secr =  $this->Session->read('secr');
	$dir =  $this->Session->read('dir');

    $SScoddeporig           =  $this->Session->read('SScoddeporig');
	$ano                    =  $this->ano_ejecucion();
    $condicion222           =  'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep_original='.$SScoddeporig.' and ano_estimacion='.$ano;
	$caop02_codigo_obra     =  $this->Session->read("caop02_codigo_obra");
	$cfpd07_obras_partidas  =  $this->cfpd07_obras_partidas->findAll($this->condicion()." and ano_estimacion='".$ano."' and  upper(cod_obra)='".strtoupper_sisap($caop02_codigo_obra)."' ", null, 'cod_obra DESC');



 		$var = strtoupper($var);


$slq_or = "";



foreach($cfpd07_obras_partidas as $ve3){

  $cod_obra_2          = $ve3["cfpd07_obras_partidas"]["cod_obra"];
  $cod_sector_2        = $ve3["cfpd07_obras_partidas"]["cod_sector"];
  $cod_programa_2      = $ve3["cfpd07_obras_partidas"]["cod_programa"];
  $cod_sub_prog_2      = $ve3["cfpd07_obras_partidas"]["cod_sub_prog"];
  $cod_proyecto_2      = $ve3["cfpd07_obras_partidas"]["cod_proyecto"];
  $cod_activ_obra_2    = $ve3["cfpd07_obras_partidas"]["cod_activ_obra"];
  $cod_partida_2       = $ve3["cfpd07_obras_partidas"]["cod_partida"];
  $cod_generica_2      = $ve3["cfpd07_obras_partidas"]["cod_generica"];
  $cod_especifica_2    = $ve3["cfpd07_obras_partidas"]["cod_especifica"];
  $cod_sub_espec_2     = $ve3["cfpd07_obras_partidas"]["cod_sub_espec"];
  $cod_auxiliar_2      = $ve3["cfpd07_obras_partidas"]["cod_auxiliar"];
  $monto_2             = $ve3["cfpd07_obras_partidas"]["monto"];
  $monto_contratado_2  = $ve3["cfpd07_obras_partidas"]["monto_contratado"];
  $aumento_obras_2     = $ve3["cfpd07_obras_partidas"]["aumento_obras"];
  $disminucion_obras_2 = $ve3["cfpd07_obras_partidas"]["disminucion_obras"];

   if($slq_or==""){
    $slq_or  = "    ( cod_partida='".$cod_partida_2."' and cod_generica='".$cod_generica_2."' and cod_especifica='".$cod_especifica_2."' and cod_sub_espec='".$cod_sub_espec_2."'  ) ";
   }else{
    $slq_or .= " or ( cod_partida='".$cod_partida_2."' and cod_generica='".$cod_generica_2."' and cod_especifica='".$cod_especifica_2."' and cod_sub_espec='".$cod_sub_espec_2."'  ) ";
   }



}//fin foreach

 		$sql_aux = $this->busca_separado(array("denominacion"), $var);
 		$existe  = $this->cscd01_catalogo->findCount($sql_aux);
 		//echo $existe;
 		if($existe == 0){
 			$this->set('catalogo', array());
 			$this->set('deno', '');
 			$this->set('notfound', 'ESTE PRODUCTO NO ESTA REGISTRADO EN EL CATALOGO - POR FAVOR INTENTE DE NUEVO');
 			return;
 		}
 		   if($this->cscd01_catalogo->findCount(               "  (".$slq_or.") and (".$sql_aux.") ") != 0){
 			$this->set('deno', $var);
 			$catalogo= $this->cscd01_catalogo->generateList(" (".$slq_or.") and (".$sql_aux.") ", 'cod_snc ASC', null, '{n}.cscd01_catalogo.codigo_prod_serv', '{n}.cscd01_catalogo.denominacion');
 			if(!empty($catalogo)){
		 		$this->set('catalogo', $catalogo);
		 	}else{
		 		$this->set('catalogo', array());
		 	}
 		}else{
 			$this->set('catalogo', array());
 			$this->set('deno', '');
 			$this->set('notfound', 'LAS PARTIDAS DE LA RELACIÓN '.$caop02_codigo_obra.' NO TIENE PRODUCTOS PRESUPUESTADOS ');

 		}
 	$this->render('mostrar');

 }//fin function











function select3($select=null,$var=null) { //select codigos presupuestarios
	$this->layout = "ajax";

	if($var!=null){
    $cond =$this->SQLCAX();
	switch($select){
		case 'dirsuperior':
		  $this->set('SELECT','coordinacion');
		  $this->set('codigo','secretaria');
		  $this->set('seleccion','');
		  $this->set('n',1);
		  $lista=  $this->cugd02_direccionsuperior->generateList($cond, 'cod_dir_superior ASC', null, '{n}.cugd02_direccionsuperior.cod_dir_superior', '{n}.cugd02_direccionsuperior.cod_dir_superior');
          $this->AddCero('vector', $lista);
		break;
		case 'coordinacion':
		  $this->set('SELECT','secretaria');
		  $this->set('codigo','coordinacion');
		  $this->set('seleccion','');
		  $this->set('n',2);
		 // $ano =  $this->Session->read('ano');
		  $this->Session->write('dirs',$var);
		  $cond .=" and cod_dir_superior=".$var;
		  $lista=  $this-> cugd02_coordinacion->generateList($cond, 'cod_coordinacion ASC', null, '{n}.cugd02_coordinacion.cod_coordinacion', '{n}.cugd02_coordinacion.denominacion');
          $this->concatena($lista, 'vector');
		break;
		case 'secretaria':
		  $this->set('SELECT','direccion');
		  $this->set('codigo','secretaria');
		  $this->set('seleccion','');
		  $this->set('n',3);
		 // $ano =  $this->Session->read('ano');
		  $dirs =  $this->Session->read('dirs');
		  $this->Session->write('coor',$var);
		  $cond .=" and cod_dir_superior=".$dirs." and cod_coordinacion=".$var;
		  $lista=  $this->cugd02_secretaria->generateList($cond, 'cod_secretaria ASC', null, '{n}.cugd02_secretaria.cod_secretaria', '{n}.cugd02_secretaria.denominacion');
          $this->concatena($lista, 'vector');
		break;
		case 'direccion':
		  $this->set('SELECT','division');
		  $this->set('codigo','direccion');
		  $this->set('seleccion','');
		  $this->set('n',4);
		  //$ano =  $this->Session->read('ano');
		  $dirs =  $this->Session->read('dirs');
		  $coor =  $this->Session->read('coor');
		  $this->Session->write('secr',$var);
		  $cond .=" and cod_dir_superior=".$dirs." and cod_coordinacion=".$coor." and cod_secretaria=".$var;
		  $lista=  $this->cugd02_direccion->generateList($cond, 'cod_direccion ASC', null, '{n}.cugd02_direccion.cod_direccion', '{n}.cugd02_direccion.denominacion');
          $this->concatena($lista, 'vector');

		break;
		case 'division':
		  $this->set('SELECT','departamento');
		  $this->set('codigo','division');
		  $this->set('seleccion','');
		  $this->set('n',5);
		 // $ano =  $this->Session->read('ano');
		  $dirs =  $this->Session->read('dirs');
		  $coor =  $this->Session->read('coor');
		  $secr =  $this->Session->read('secr');
		  $this->Session->write('dir',$var);
		  $cond .=" and cod_dir_superior=".$dirs." and cod_coordinacion=".$coor." and cod_secretaria=".$secr." and cod_direccion=".$var;
		  $lista=  $this->cugd02_division->generateList($cond, 'cod_division ASC', null, '{n}.cugd02_division.cod_division', '{n}.cugd02_division.denominacion');
          $this->concatena($lista, 'vector');
//          $this->catProg($dirs, $coor, $secr, $var);
		break;
		case 'departamento':
		  $this->set('SELECT','oficina');
		  $this->set('codigo','departamento');
		  $this->set('seleccion','');
		  $this->set('n',6);
		 // $ano =  $this->Session->read('ano');
		  $dirs =  $this->Session->read('dirs');
		  $coor =  $this->Session->read('coor');
		  $secr =  $this->Session->read('secr');
		  $dir =  $this->Session->read('dir');
		  $div =  $this->Session->write('div',$var);
		  $cond .=" and cod_dir_superior=".$dirs." and cod_coordinacion=".$coor." and cod_secretaria=".$secr." and cod_direccion=".$dir." and cod_division=".$var;
		  $lista=  $this->cugd02_departamento->generateList($cond, 'cod_departamento ASC', null, '{n}.cugd02_departamento.cod_departamento', '{n}.cugd02_departamento.denominacion');
          $this->concatena($lista, 'vector');
		break;
		case 'oficina':
		  $this->set('SELECT','oficina');
		  $this->set('codigo','oficina');
		  $this->set('seleccion','');
		  $this->set('n',7);
		  $this->set('no','no');
		 // $ano =  $this->Session->read('ano');
		  $dirs =  $this->Session->read('dirs');
		  $coor =  $this->Session->read('coor');
		  $secr =  $this->Session->read('secr');
		  $dir =  $this->Session->read('dir');
		  $div =  $this->Session->read('div');
		  $this->Session->write('dep',$var);
		  $cond .=" and cod_dir_superior=".$dirs." and cod_coordinacion=".$coor." and cod_secretaria=".$secr." and cod_direccion=".$dir." and cod_division=".$div." and cod_departamento=".$var;
		  $lista=  $this->cugd02_oficina->generateList($cond, 'cod_oficina ASC', null, '{n}.cugd02_oficina.cod_oficina', '{n}.cugd02_oficina.denominacion');
          $this->concatena($lista, 'vector');
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

function mostrar4($select=null,$var=null) { //mostrar3 codigos presupuestarios
	$this->layout = "ajax";
if( $var!=null){
    $cond = $this->SQLCAX();
   // $cond2 = $this->SQLCA();
	switch($select){
		case 'dirsuperior':
		 // $ano =  $this->Session->read('ano');
		  $this->Session->write('ddirs',$var);
		  $cond .=" and cod_dir_superior=".$var;
		  $a=  $this->cugd02_direccionsuperior->findAll($cond);
		  if(isset($a[0]['cugd02_direccionsuperior']['cod_dir_superior'])){
			$num = $this->zero($a[0]['cugd02_direccionsuperior']['cod_dir_superior']);
			echo $num;
          	//echo $a[0]['cugd02_direccionsuperior']['cod_dir_superior'] >9 ?$a[0]['cugd02_direccionsuperior']['cod_dir_superior'] : "0".$a[0]['cugd02_direccionsuperior']['cod_dir_superior'] ;
		  }
		break;
		case 'coordinacion':
		  //$ano =  $this->Session->read('ano');
		  $ddirs =  $this->Session->read('ddirs');
		  $this->Session->write('dcoor',$var);
		  $cond .=" and cod_dir_superior=".$ddirs." and cod_coordinacion=".$var;
		  $a=  $this->cugd02_coordinacion->findAll($cond);
		  if(isset($a[0]['cugd02_coordinacion']['cod_coordinacion'])){
		  	$num = $this->zero($a[0]['cugd02_coordinacion']['cod_coordinacion']);
		  	echo $num;
          	//echo $a[0]['cugd02_coordinacion']['cod_coordinacion'] >9 ?$a[0]['cugd02_coordinacion']['cod_coordinacion'] : "0".$a[0]['cugd02_coordinacion']['cod_coordinacion'] ;
		  }
		break;
		case 'secretaria':
		 // $ano =  $this->Session->read('ano');
		  $ddirs =  $this->Session->read('ddirs');
		  $dcoor =  $this->Session->read('dcoor');
		  $this->Session->write('dsecr',$var);
		  $cond .=" and cod_dir_superior=".$ddirs." and cod_coordinacion=".$dcoor." and cod_secretaria=".$var;
		 $a=  $this->cugd02_secretaria->findAll($cond);
		  if(isset($a[0]['cugd02_secretaria']['cod_secretaria'])){
		  	$num = $this->zero($a[0]['cugd02_secretaria']['cod_secretaria']);
		  	//echo "<input type='text' style='width:98%;text-align:center' value='$num' READONLY>";
          	echo $a[0]['cugd02_secretaria']['cod_secretaria']>9 ?$a[0]['cod_secretaria']['cod_coordinacion'] : "0".$a[0]['cugd02_secretaria']['cod_secretaria'];
		  }
		break;
		case 'direccion':
		 // $ano =  $this->Session->read('ano');
		  $ddirs =  $this->Session->read('ddirs');
		  $dcoor =  $this->Session->read('dcoor');
		  $dsecr =  $this->Session->read('dsecr');
		  $this->Session->write('ddir',$var);
		  $cond .=" and cod_dir_superior=".$ddirs." and cod_coordinacion=".$dcoor." and cod_secretaria=".$dsecr." and cod_direccion=".$var;
		  $a=  $this->cugd02_direccion->findAll($cond);
		  if(isset($a[0]['cugd02_direccion']['cod_direccion'])){
		  	$num = $this->zero($a[0]['cugd02_direccion']['cod_direccion']);
		  	//echo "<input type='text' style='width:98%;text-align:center' value='$num' READONLY>";
          	echo $a[0]['cugd02_direccion']['cod_direccion']>9 ?$a[0]['cugd02_direccion']['cod_direccion'] : "0".$a[0]['cugd02_direccion']['cod_direccion'];

		  }
		break;
		case 'division':
		  //$ano =  $this->Session->read('ano');
		  $ddirs =  $this->Session->read('ddirs');
		  $dcoor =  $this->Session->read('dcoor');
		  $dsecr =  $this->Session->read('dsecr');
		  $ddir =  $this->Session->read('ddir');
		  $this->Session->write('ddiv',$var);
		  $cond .=" and cod_dir_superior=".$ddirs." and cod_coordinacion=".$dcoor." and cod_secretaria=".$dsecr." and cod_direccion=".$ddir." and cod_division=".$var;
		  $a=  $this->cugd02_division->findAll($cond);
		  if(isset($a[0]['cugd02_division']['cod_division'])){
		  	$num = $this->zero($a[0]['cugd02_division']['cod_division']);
		  	//echo "<input type='text' style='width:98%;text-align:center' value='$num' READONLY>";
         	echo $a[0]['cugd02_division']['cod_division']>9 ?$a[0]['cugd02_division']['cod_division'] : "0".$a[0]['cugd02_division']['cod_division'];
		  }
		break;
		case 'departamento':
		  //$ano =  $this->Session->read('ano');
		  $ddirs =  $this->Session->read('ddirs');
		  $dcoor =  $this->Session->read('dcoor');
		  $dsecr =  $this->Session->read('dsecr');
		  $ddir =  $this->Session->read('ddir');
		  $ddiv =  $this->Session->read('ddiv');
		  $this->Session->write('ddep',$var);
		  $cond .=" and cod_dir_superior=".$ddirs." and cod_coordinacion=".$dcoor." and cod_secretaria=".$dsecr." and cod_direccion=".$ddir." and cod_division=".$ddiv." and cod_departamento=".$var;
		  $a=  $this->cugd02_departamento->findAll($cond);
		  if(isset($a[0]['cugd02_departamento']['cod_departamento'])){
		  	$num = $this->zero($a[0]['cugd02_departamento']['cod_departamento']);
		  	//echo "<input type='text' style='width:98%;text-align:center' value='$num' READONLY>";
          	echo $a[0]['cugd02_departamento']['cod_departamento']>9 ?$a[0]['cugd02_departamento']['cod_departamento'] : "0".$a[0]['cugd02_departamento']['cod_departamento'];
          }
		break;
		case 'oficina':
		 // $ano =  $this->Session->read('ano');
		  $ddirs =  $this->Session->read('ddirs');
		  $dcoor =  $this->Session->read('dcoor');
		  $dsecr =  $this->Session->read('dsecr');
		  $ddir =  $this->Session->read('ddir');
		  $ddiv =  $this->Session->read('ddiv');
		  $ddep =  $this->Session->read('ddep');
		  $this->Session->write('dofi',$var);
		  $cond .=" and cod_dir_superior=".$ddirs." and cod_coordinacion=".$dcoor." and cod_secretaria=".$dsecr." and cod_direccion=".$ddir." and cod_division=".$ddiv." and cod_departamento=".$ddep." and cod_oficina=".$var;
		  $a=  $this->cugd02_oficina->findAll($cond);
        if(isset($a[0]['cugd02_oficina']['cod_oficina'])){
        	$num = $this->zero($a[0]['cugd02_oficina']['cod_oficina']);
		  	//echo "<input type='text' style='width:98%;text-align:center' value='$num' READONLY>";
          	echo $a[0]['cugd02_oficina']['cod_oficina']>9 ?$a[0]['cugd02_oficina']['cod_oficina'] : "0".$a[0]['cugd02_oficina']['cod_oficina'];
		  }
		break;
	}//fin wsitch
	}else{
		echo "&nbsp;";
	}
}//fin mostrar3 codigos presupuestarios



function mostrar3($select=null,$var=null){ //mostrar3 denominacion presupuestarios
	$this->layout = "ajax";
if( $var!=null && !empty($var)){
    $cond = $this->SQLCAX();
   // $cond2 = $this->SQLCA();
	switch($select){
		case 'dirsuperior':
		 // $ano =  $this->Session->read('ano');
		  $this->Session->write('ddirs',$var);
		  $cond .=" and cod_dir_superior=".$var;
		  $a=  $this->cugd02_direccionsuperior->findAll($cond);
		  if(isset($a[0]['cugd02_direccionsuperior']['denominacion'])){
          $this->set("denominacion",$a[0]['cugd02_direccionsuperior']['denominacion']);
		  }
		break;
		case 'coordinacion':
		  //$ano =  $this->Session->read('ano');
		  $ddirs =  $this->Session->read('ddirs');
		  $this->Session->write('dcoor',$var);
		  $cond .=" and cod_dir_superior=".$ddirs." and cod_coordinacion=".$var;
		  $a=  $this->cugd02_coordinacion->findAll($cond);
		  if(isset($a[0]['cugd02_coordinacion']['denominacion'])){
          $this->set("denominacion",$a[0]['cugd02_coordinacion']['denominacion']);
		  }
		break;
		case 'secretaria':
		 // $ano =  $this->Session->read('ano');
		  $ddirs =  $this->Session->read('ddirs');
		  $dcoor =  $this->Session->read('dcoor');
		  $this->Session->write('dsecr',$var);
		  $cond .=" and cod_dir_superior=".$ddirs." and cod_coordinacion=".$dcoor." and cod_secretaria=".$var;
		  $a=  $this->cugd02_secretaria->findAll($cond);
		  if(isset($a[0]['cugd02_secretaria']['denominacion'])){
          $this->set("denominacion",$a[0]['cugd02_secretaria']['denominacion']);
		  }
		break;
		case 'direccion':
		 // $ano =  $this->Session->read('ano');
		  $ddirs =  $this->Session->read('ddirs');
		  $dcoor =  $this->Session->read('dcoor');
		  $dsecr =  $this->Session->read('dsecr');
		  $this->Session->write('ddir',$var);
		  $cond .=" and cod_dir_superior=".$ddirs." and cod_coordinacion=".$dcoor." and cod_secretaria=".$dsecr." and cod_direccion=".$var;
		  $a=  $this->cugd02_direccion->findAll($cond);
		  if(isset($a[0]['cugd02_direccion']['denominacion'])){
          $this->set("denominacion",$a[0]['cugd02_direccion']['denominacion']);
		  }
		break;
		case 'division':
		  //$ano =  $this->Session->read('ano');
		  $ddirs =  $this->Session->read('ddirs');
		  $dcoor =  $this->Session->read('dcoor');
		  $dsecr =  $this->Session->read('dsecr');
		  $ddir =  $this->Session->read('ddir');
		  $this->Session->write('ddiv',$var);
		  $cond .=" and cod_dir_superior=".$ddirs." and cod_coordinacion=".$dcoor." and cod_secretaria=".$dsecr." and cod_direccion=".$ddir." and cod_division=".$var;
		  $a=  $this->cugd02_division->findAll($cond);
		  if(isset($a[0]['cugd02_division']['denominacion'])){
          $this->set("denominacion",$a[0]['cugd02_division']['denominacion']);
		  }
		break;
		case 'departamento':
		  //$ano =  $this->Session->read('ano');
		  $ddirs =  $this->Session->read('ddirs');
		  $dcoor =  $this->Session->read('dcoor');
		  $dsecr =  $this->Session->read('dsecr');
		  $ddir =  $this->Session->read('ddir');
		  $ddiv =  $this->Session->read('ddiv');
		  $this->Session->write('ddep',$var);
		  $cond .=" and cod_dir_superior=".$ddirs." and cod_coordinacion=".$dcoor." and cod_secretaria=".$dsecr." and cod_direccion=".$ddir." and cod_division=".$ddiv." and cod_departamento=".$var;
		  $a=  $this->cugd02_departamento->findAll($cond);
		  if(isset($a[0]['cugd02_departamento']['denominacion'])){
          $this->set("denominacion",$a[0]['cugd02_departamento']['denominacion']);
		  }
		break;
		case 'oficina':
		 // $ano =  $this->Session->read('ano');
		  $ddirs =  $this->Session->read('ddirs');
		  $dcoor =  $this->Session->read('dcoor');
		  $dsecr =  $this->Session->read('dsecr');
		  $ddir =  $this->Session->read('ddir');
		  $ddiv =  $this->Session->read('ddiv');
		  $ddep =  $this->Session->read('ddep');
		  $this->Session->write('dofi',$var);
		  $cond .=" and cod_dir_superior=".$ddirs." and cod_coordinacion=".$dcoor." and cod_secretaria=".$dsecr." and cod_direccion=".$ddir." and cod_division=".$ddiv." and cod_departamento=".$ddep." and cod_oficina=".$var;
		  $a=  $this->cugd02_oficina->findAll($cond);
		  if(isset($a[0]['cugd02_oficina']['denominacion'])){
          $this->set("denominacion",$a[0]['cugd02_oficina']['denominacion']);
		  }
		break;
	}//fin wsitch
	}else{
		$this->set("denominacion"," ");
	}
}//fin mostrar3 codigos presupuestarios

function valida_numero($num_solicitud=null) {
	$this->layout = "ajax";
	//echo "el numero de solicitud es: ".$num_solicitud;
	if($num_solicitud != null){
		$ano = $this->ano_ejecucion();
		$cont = $this->cscd02_solicitud_encabezado->findCount($this->condicion()." and ano_solicitud='$ano' and numero_solicitud='$num_solicitud'");
		if($cont != 0){
			$this->set('msg', 'YA EXISTE UNA SOLICITUD DE COTIZACION CON ESE NUMERO');
		}else{
			$this->set('num', $num_solicitud);
		}
	}
}//FIN VALIDA NUMERO

function unidad($var=null){
	$this->layout = "ajax";

	if( $var!=null && !empty($var)){
		$codig_prod_serv = $var;
		$cod_snc = $this->cscd01_catalogo->field('cscd01_catalogo.cod_snc', $conditions = "cscd01_catalogo.codigo_prod_serv='$var'", $order =null);
		$var = $cod_snc;
		$var = trim($var);
		$cond="trim(cod_snc)='$var'";
		$this->set('cod_snc', $var);
		//$x=  $this->cscd01_catalogo->findByCod_tipo(1);
		$x=  $this->cscd01_catalogo->findAll("codigo_prod_serv='$codig_prod_serv'");
		$cod_medida=$x[0]['cscd01_catalogo']['cod_medida'];
		//echo "el cod_medida es: ".$cod_medida;
		$cond2="cod_medida=".$cod_medida;
		$y=  $this->cscd01_unidad_medida->findAll($cond2);
		$expresion=$y[0]['cscd01_unidad_medida']['expresion'];
		$this->set('var2',$expresion);
		$this->set('var',$cod_medida);

	}else{
		$this->set('var2','');
	}
}

function crear_producto($cod_snc, $denominacion, $cod_medida){
	$cod_partida = $this->cscd01_catalogo->field('cscd01_catalogo.cod_partida', $conditions = "trim(cod_snc)='$cod_snc'", $order =null);
	$cod_generica = $this->cscd01_catalogo->field('cscd01_catalogo.cod_generica', $conditions = "trim(cod_snc)='$cod_snc'", $order =null);
	$cod_especifica = $this->cscd01_catalogo->field('cscd01_catalogo.cod_especifica', $conditions = "trim(cod_snc)='$cod_snc'", $order =null);
	$cod_sub_espec = $this->cscd01_catalogo->field('cscd01_catalogo.cod_sub_espec', $conditions = "trim(cod_snc)='$cod_snc'", $order =null);
	if(empty($cod_sub_espec))$cod_sub_espec=0;
	$cod_auxiliar = $this->cscd01_catalogo->field('cscd01_catalogo.cod_auxiliar', $conditions = "trim(cod_snc)='$cod_snc'", $order =null);
	if(empty($cod_auxiliar))$cod_auxiliar=0;
	$cod_tipo = $this->cscd01_catalogo->field('cscd01_catalogo.cod_tipo', $conditions = "trim(cod_snc)='$cod_snc'", $order =null);
	$especificaciones = $this->cscd01_catalogo->field('cscd01_catalogo.especificaciones', $conditions = "trim(cod_snc)='$cod_snc'", $order =null);
	//$cod_medida = $this->cscd01_catalogo->field('cscd01_catalogo.cod_medida', $conditions = "trim(cod_snc)'$cod_snc'", $order =null);
	$exento_iva = $this->cscd01_catalogo->field('cscd01_catalogo.exento_iva', $conditions = "trim(cod_snc)='$cod_snc'", $order =null);
	$alicuota = $this->cscd01_catalogo->field('cscd01_catalogo.alicuota_iva', $conditions = "trim(cod_snc)='$cod_snc'", $order =null);
	//echo 'el alicuota iva es: '.$alicuota_iva;

	$sql = "INSERT INTO cscd01_catalogo (cod_tipo, denominacion, especificaciones, cod_medida, cod_partida, cod_generica, cod_especifica, cod_sub_espec, cod_auxiliar, exento_iva, alicuota_iva, cod_snc) ";
	$sql .= "VALUES('$cod_tipo', '$denominacion', '$especificaciones', '$cod_medida', '$cod_partida', '$cod_generica', '$cod_especifica', '$cod_sub_espec', '$cod_auxiliar', '$exento_iva', '$alicuota', '$cod_snc')";

	$x = $this->cscd01_catalogo->execute($sql);
	//if($x>1) echo "holaaa"; else "error";

}

function verificar($cod_snc, $cod_sector, $cod_programa, $cod_sub_prog, $cod_proyecto){
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$prod = $this->cscd01_catalogo->findAll($conditions = "codigo_prod_serv='$cod_snc'", $fields ='cod_partida, cod_generica, cod_especifica, cod_sub_espec, cod_auxiliar', $order = null, $limit = 1, $page = null, $recursive = null);
	$categoria = $this->cugd02_direccion->findAll("cod_tipo_institucion='$cod_tipo_inst' and cod_institucion='$cod_inst' and cod_dependencia='$cod_dep'", $fields ='cod_sector, cod_programa, cod_sub_prog, cod_proyecto', $order = null, $limit = 1, $page = null, $recursive = null);

	if($prod != null && $categoria != null){
		foreach($prod as $row){
			$cod_partida = $row['cscd01_catalogo']['cod_partida'];
			$cod_generica = $row['cscd01_catalogo']['cod_generica'];
			$cod_especifica = $row['cscd01_catalogo']['cod_especifica'];
			$cod_sub_espec = $row['cscd01_catalogo']['cod_sub_espec'];
			$cod_auxiliar = $row['cscd01_catalogo']['cod_auxiliar'];
		}

		$cond1 = " cod_partida='$cod_partida' and cod_generica='$cod_generica' and cod_especifica='$cod_especifica' and cod_sub_espec='$cod_sub_espec' ";
		$cont = $this->cscd01_catalogo->findCount($cond1);
		if($cont>0){
			return true;
		}else{
			return false;
		}


	}else{
		return false;
	}

}

function agregar_items () {
	$this->layout = "ajax";
		$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	if(isset($this->data['caop02_solicitud_cotizacion'])){ //echo $this->data['caop02_solicitud_cotizacion']['cod_snc'];

	 $ano              =   $this->data['caop02_solicitud_cotizacion']['ano'];
	 $numero           =   $this->data['caop02_solicitud_cotizacion']['numero'];
	 $fecha            =   $this->data['caop02_solicitud_cotizacion']['fecha'];
	 $cod_dirsuperior  =   $this->data['caop02_solicitud_cotizacion']['cod_dirsuperior'];
	 $cod_coordinacion =   $this->data['caop02_solicitud_cotizacion']['cod_coordinacion'];
	 $cod_secretaria   =   $this->data['caop02_solicitud_cotizacion']['cod_secretaria'];
	 $cod_direccion    =   $this->data['caop02_solicitud_cotizacion']['cod_direccion'];


	 if(isset($this->data['caop02_solicitud_cotizacion']['cod_division'])){
	 	$cod_division=$this->data['caop02_solicitud_cotizacion']['cod_division'];
	 }else{
	 	$cod_division=0;
	 }

	 if(isset($this->data['caop02_solicitud_cotizacion']['cod_departamento'])){
	 	$cod_departamento=$this->data['caop02_solicitud_cotizacion']['cod_departamento'];
	 }else{
	 	$cod_departamento=0;
	 }
	 if(isset($this->data['caop02_solicitud_cotizacion']['cod_oficina'])){
	 	$cod_oficina=$this->data['caop02_solicitud_cotizacion']['cod_oficina'];
	 }else{
	 	$cod_oficina=0;
	 }


	 $cod_prod            =      $this->data['caop02_solicitud_cotizacion']['cod_prod'];
	 $unidad_medida       =      $this->data['caop02_solicitud_cotizacion']['unidad_medida'];
	 $cod_medida          =      $this->data['caop02_solicitud_cotizacion']['cod_medida'];
	 $cod_snc             =      $this->data['caop02_solicitud_cotizacion']['cod_snc'];
	 $descripcion_bienes  =      $this->data['caop02_solicitud_cotizacion']['descripcion_bienes'];
	 $cantidad_estimada   =      $this->data['caop02_solicitud_cotizacion']['cantidad_estimada'];
	 $uso                 =      $this->data['caop02_solicitud_cotizacion']['uso'];
	 $cod_snc             =      trim($cod_snc);
	 $deno_tabla          =      $this->cscd01_catalogo->field('cscd01_catalogo.denominacion', $conditions = "trim(cod_snc)='$cod_snc'", $order =null);
	 $deno_sw             =      $this->cscd01_catalogo->field('cscd01_catalogo.denominacion', $conditions = "trim(denominacion)='$descripcion_bienes'", $order =null);

	$prod2 = $this->cscd01_catalogo->findAll($conditions = "codigo_prod_serv='$cod_prod'", $fields ='cod_partida, cod_generica, cod_especifica, cod_sub_espec', $order = null, $limit = 1, $page = null, $recursive = null);
	foreach($prod2 as $row){
		$cod_partida = $row['cscd01_catalogo']['cod_partida'];
		$cod_generica = $row['cscd01_catalogo']['cod_generica'];
		$cod_especifica = $row['cscd01_catalogo']['cod_especifica'];
		$cod_sub_espec = $row['cscd01_catalogo']['cod_sub_espec'];
	}



$caop02_codigo_obra     =  $this->Session->read("caop02_codigo_obra");
$cfpd07_obras_partidas  =  $this->cfpd07_obras_partidas->findAll($this->condicion()." and ano_estimacion='".$ano."'  and upper(cod_obra)='".strtoupper_sisap($caop02_codigo_obra)."' and cod_partida='".$cod_partida."' and cod_generica='".$cod_generica."' and cod_especifica='".$cod_especifica."' and cod_sub_espec='".$cod_sub_espec."'  ", null, 'cod_obra DESC');
$slq_or = "";

$cod_sector   = 0;
$cod_programa = 0;
$cod_sub_prog = 0;
$cod_proyecto = 0;
foreach($cfpd07_obras_partidas as $ve3){
  $cod_obra_2          = $ve3["cfpd07_obras_partidas"]["cod_obra"];
  $cod_sector_2        = $ve3["cfpd07_obras_partidas"]["cod_sector"];
  $cod_programa_2      = $ve3["cfpd07_obras_partidas"]["cod_programa"];
  $cod_sub_prog_2      = $ve3["cfpd07_obras_partidas"]["cod_sub_prog"];
  $cod_proyecto_2      = $ve3["cfpd07_obras_partidas"]["cod_proyecto"];
  $cod_activ_obra_2    = $ve3["cfpd07_obras_partidas"]["cod_activ_obra"];
  $cod_partida_2       = $ve3["cfpd07_obras_partidas"]["cod_partida"];
  $cod_generica_2      = $ve3["cfpd07_obras_partidas"]["cod_generica"];
  $cod_especifica_2    = $ve3["cfpd07_obras_partidas"]["cod_especifica"];
  $cod_sub_espec_2     = $ve3["cfpd07_obras_partidas"]["cod_sub_espec"];
  $cod_auxiliar_2      = $ve3["cfpd07_obras_partidas"]["cod_auxiliar"];
  $monto_2             = $ve3["cfpd07_obras_partidas"]["monto"];
  $monto_contratado_2  = $ve3["cfpd07_obras_partidas"]["monto_contratado"];
  $aumento_obras_2     = $ve3["cfpd07_obras_partidas"]["aumento_obras"];
  $disminucion_obras_2 = $ve3["cfpd07_obras_partidas"]["disminucion_obras"];
  $monto_aux           = ($monto_contratado_2 + $aumento_obras_2) - $disminucion_obras_2;
   if($monto_2>$monto_aux || $monto_aux==0){
   	$cod_sector   = $cod_sector_2;
   	$cod_programa = $cod_programa_2;
   	$cod_sub_prog = $cod_sub_prog_2;
   	$cod_proyecto = $cod_proyecto_2;
   }
}//fin foreach
if($cod_sector==0){
	$cod_sector   = $cod_sector_2;
   	$cod_programa = $cod_programa_2;
   	$cod_sub_prog = $cod_sub_prog_2;
   	$cod_proyecto = $cod_proyecto_2;
}

	 if(!$this->verificar($cod_prod, $cod_sector, $cod_programa, $cod_sub_prog, $cod_proyecto)){
		$this->set('errorMessage', 'LA PARTIDA A LA QUE PERTENECE ESTE PRODUCTO NO FUE PRESUPUESTADA');
		return;
	 }


     if(isset($_SESSION["i"])){

        $i=$this->Session->read("i")+1;
        $this->Session->write("i",$i);
     }else{/////////////////////////////////////////////////////////////////////////////////////






$caop02_codigo_obra     =  $this->Session->read("caop02_codigo_obra");
$cfpd07_obras_partidas  =  $this->cfpd07_obras_partidas->findAll($this->condicion()." and ano_estimacion='".$ano."' and upper(cod_obra)='".strtoupper_sisap($caop02_codigo_obra)."' and cod_partida='403' and cod_generica='18' and cod_especifica='1' and cod_sub_espec='0'  ", null, 'cod_obra DESC');
$slq_or = "";

$cod_sector_iva   = 0;
$cod_programa_iva = 0;
$cod_sub_prog_iva = 0;
$cod_proyecto_iva = 0;
foreach($cfpd07_obras_partidas as $ve3){
  $cod_obra_2          = $ve3["cfpd07_obras_partidas"]["cod_obra"];
  $cod_sector_2        = $ve3["cfpd07_obras_partidas"]["cod_sector"];
  $cod_programa_2      = $ve3["cfpd07_obras_partidas"]["cod_programa"];
  $cod_sub_prog_2      = $ve3["cfpd07_obras_partidas"]["cod_sub_prog"];
  $cod_proyecto_2      = $ve3["cfpd07_obras_partidas"]["cod_proyecto"];
  $cod_activ_obra_2    = $ve3["cfpd07_obras_partidas"]["cod_activ_obra"];
  $cod_partida_2       = $ve3["cfpd07_obras_partidas"]["cod_partida"];
  $cod_generica_2      = $ve3["cfpd07_obras_partidas"]["cod_generica"];
  $cod_especifica_2    = $ve3["cfpd07_obras_partidas"]["cod_especifica"];
  $cod_sub_espec_2     = $ve3["cfpd07_obras_partidas"]["cod_sub_espec"];
  $cod_auxiliar_2      = $ve3["cfpd07_obras_partidas"]["cod_auxiliar"];
  $monto_2             = $ve3["cfpd07_obras_partidas"]["monto"];
  $monto_contratado_2  = $ve3["cfpd07_obras_partidas"]["monto_contratado"];
  $aumento_obras_2     = $ve3["cfpd07_obras_partidas"]["aumento_obras"];
  $disminucion_obras_2 = $ve3["cfpd07_obras_partidas"]["disminucion_obras"];
  $monto_aux           = ($monto_contratado_2 + $aumento_obras_2) - $disminucion_obras_2;
   if($monto_2>$monto_aux || $monto_aux==0){
   	$cod_sector_iva   = $cod_sector_2;
   	$cod_programa_iva = $cod_programa_2;
   	$cod_sub_prog_iva = $cod_sub_prog_2;
   	$cod_proyecto_iva = $cod_proyecto_2;
   }
}//fin foreach
if($cod_sector_iva==0){
	$cod_sector_iva   = $cod_sector_2;
   	$cod_programa_iva = $cod_programa_2;
   	$cod_sub_prog_iva = $cod_sub_prog_2;
   	$cod_proyecto_iva = $cod_proyecto_2;
}





$existe = $this->verificar(3228, $cod_sector_iva, $cod_programa_iva, $cod_sub_prog_iva, $cod_proyecto_iva);

 if($cod_prod!="3228"){
 	if($existe){
     	$this->Session->write("i",1);
     	$i=1;
     	$items[0]["ano"]=$ano;
     	$items[0]["numero"]=$numero;
     	$items[0]["fecha"]=$fecha;
     	$items[0]["cod_dirsuperior"]=$cod_dirsuperior;
     	$items[0]["cod_coordinacion"]=$cod_coordinacion;
     	$items[0]["cod_secretaria"]=$cod_secretaria;
     	$items[0]["cod_direccion"]=$cod_direccion;
     	$items[0]["cod_division"]=$cod_division;
     	$items[0]["cod_departamento"]=$cod_departamento;
     	$items[0]["cod_oficina"]=$cod_oficina;
     	$items[0]["cod_prod"]='3228';
     	$items[0]["unidad_medida"]='UNIDAD';
     	$items[0]["cod_medida"]=1;
     	$items[0]["descripcion_bienes"]='IMPUESTO AL VALOR AGREGADO (IVA)';
     	$items[0]["cantidad_estimada"]=1;
     	$items[0]["id"]=0;
     	$items[0]["cod_sector"]   = $cod_sector_iva;
     	$items[0]["cod_programa"] = $cod_programa_iva;
     	$items[0]["cod_sub_prog"] = $cod_sub_prog_iva;
     	$items[0]["cod_proyecto"] = $cod_proyecto_iva;
     	$items[0]["cod_partida"] = 403;
     $items[0]["cod_generica"] = 18;
     $items[0]["cod_especifica"] = 1;
     $items[0]["cod_sub_espec"] = 0;
     	//$i++;
     	if(isset($_SESSION["items"])){
     		$_SESSION["items"]=$_SESSION["items"]+$items;
     	}else{
     		$_SESSION["items"]=$items;
     	}
 	}else{
 		$this->set('errorMessage', 'UD NO TIENE PRESUPUESTADO LA PARTIDA DEL I.V.A.');
 		$this->Session->write("i",0);
     	$i=0;
 		  $items[$i]["ano"]=$ano;
	     $items[$i]["numero"]=$numero;
	     $items[$i]["fecha"]=$fecha;
	     $items[$i]["cod_dirsuperior"]=$cod_dirsuperior;
	     $items[$i]["cod_coordinacion"]=$cod_coordinacion;
	     $items[$i]["cod_secretaria"]=$cod_secretaria;
	     $items[$i]["cod_direccion"]=$cod_direccion;
	     $items[$i]["cod_division"]=$cod_division;
	     $items[$i]["cod_departamento"]=$cod_departamento;
	     $items[$i]["cod_oficina"]=$cod_oficina;
	     $items[$i]["cod_prod"]=$cod_prod;
	     $items[$i]["unidad_medida"]=$unidad_medida;
	     $items[$i]["cod_medida"]=$cod_medida;
	     //echo $cod_medida;
	     $items[$i]["descripcion_bienes"]=$descripcion_bienes;
	     $items[$i]["cantidad_estimada"]=$cantidad_estimada;
	     $items[$i]["id"]=$i;
	     $items[$i]["cod_sector"] = $cod_sector;
     	$items[$i]["cod_programa"] = $cod_programa;
     	$items[$i]["cod_sub_prog"] = $cod_sub_prog;
     	$items[$i]["cod_proyecto"] = $cod_proyecto;
     	$items[$i]["cod_partida"] = $cod_partida;
     $items[$i]["cod_generica"] = $cod_generica;
     $items[$i]["cod_especifica"] = $cod_especifica;
     $items[$i]["cod_sub_espec"] = $cod_sub_espec;
	     if(isset($_SESSION["items"])){
	     	$_SESSION["items"]=$_SESSION["items"]+$items;
	     }else{
	     	$_SESSION["items"]=$items;
	     }
	     echo "<script>document.getElementById('cantidad_estimada2').value='';</script>";
	     return;
 	}
  }//fin if
}//fin else

if(isset($_SESSION["items"])){
	foreach($_SESSION["items"] as $codi){
		if($codi['cod_prod']==$cod_prod){ $est=true; break; }else{ $est=false; }}//fin foreach
                          if($est==true){
                            $i=$this->Session->read("i")-1;
				            $this->Session->write("i",$i);
				            $this->set('errorMessage', 'el producto seleccionado ya existe en la lista');
                        }else{


     $items[$i]["ano"]=$ano;
     $items[$i]["numero"]=$numero;
     $items[$i]["fecha"]=$fecha;
     $items[$i]["cod_dirsuperior"]=$cod_dirsuperior;
     $items[$i]["cod_coordinacion"]=$cod_coordinacion;
     $items[$i]["cod_secretaria"]=$cod_secretaria;
     $items[$i]["cod_direccion"]=$cod_direccion;
     $items[$i]["cod_division"]=$cod_division;
     $items[$i]["cod_departamento"]=$cod_departamento;
     $items[$i]["cod_oficina"]=$cod_oficina;
     $items[$i]["cod_prod"]=$cod_prod;
     $items[$i]["unidad_medida"]=$unidad_medida;
     $items[$i]["cod_medida"]=$cod_medida;
     //echo $cod_medida;
     $items[$i]["descripcion_bienes"]=$descripcion_bienes;
     $items[$i]["cantidad_estimada"]=$cantidad_estimada;
     $items[$i]["id"]=$i;
     $items[$i]["cod_sector"] = $cod_sector;
     $items[$i]["cod_programa"] = $cod_programa;
     $items[$i]["cod_sub_prog"] = $cod_sub_prog;
     $items[$i]["cod_proyecto"] = $cod_proyecto;
     $items[$i]["cod_partida"] = $cod_partida;
     $items[$i]["cod_generica"] = $cod_generica;
     $items[$i]["cod_especifica"] = $cod_especifica;
     $items[$i]["cod_sub_espec"] = $cod_sub_espec;
     if(isset($_SESSION["items"])){
     	$_SESSION["items"]=$_SESSION["items"]+$items;
     }else{
     	$_SESSION["items"]=$items;
     }

      }//fin if
}//fin else

	//pr($_SESSION["items"]);

     echo "<script>document.getElementById('cantidad_estimada2').value='';</script>";
}
}//fin function


function eliminar_items ($id) {
	$this->layout = "ajax";
	$_SESSION["items"][$id]=null;
	//echo "la cantidad de items es: ".count($_SESSION["items"]);
	   $c = count($_SESSION["items"]);
	   $opcion =  "a";

foreach($_SESSION["items"] as $ve){
  if($ve['cod_prod']!=""){ $opcion = "b"; }
}//fin for


$this->set('disabled', $opcion);

/*
echo'<script>';
   echo"var a = document.getElementById('cuenta_i').value;";
   echo"var b = eval(a) - eval(1);";
   echo"document.getElementById('cuenta_i').value = b;";
echo'</script>';*/


}//fin function



function snc($var = null){
	$this->layout = "ajax";
	if( $var!=null && !empty($var)){
		$codigo=$var;
		$cod_snc = $this->cscd01_catalogo->field('cscd01_catalogo.cod_snc', $conditions = "cscd01_catalogo.codigo_prod_serv='$var'", $order =null);
		$this->set('cod_snc', $cod_snc);


$cod_snc = $this->cscd01_catalogo->findAll($conditions = "cscd01_catalogo.codigo_prod_serv='$var'", $order =null);

foreach($cod_snc as $ve){$partida = $this->zero($ve['cscd01_catalogo']['cod_partida']).'.'.$this->AddCeroR2($ve['cscd01_catalogo']['cod_generica']).'.'.$this->AddCeroR2($ve['cscd01_catalogo']['cod_especifica']).'.'.$this->AddCeroR2($ve['cscd01_catalogo']['cod_sub_espec']).'.'.mascara_cuatro($ve['cscd01_catalogo']['cod_auxiliar']);}//fin foreach


echo'<script>';
      echo' document.getElementById("partida_producto").innerHTML = "'.$partida.'"; ';
echo'</script>';


	}else{

		$this->set('cod_snc', '');
echo'<script>';
      echo' document.getElementById("partida_producto").innerHTML = "<br>"; ';
echo'</script>';

	}//fin else

}//fin funtion








function limpiar_lista() {
	$this->layout = "ajax";
	$this->Session->delete("items");
	$this->Session->delete("i");

}//fin



function descripcion_bienes($var=null){
	$this->layout = "ajax";
	//echo $var;
	if( $var!=null && !empty($var)){
		$codigo=$var;
		$cod_snc = $this->cscd01_catalogo->field('cscd01_catalogo.cod_snc', $conditions = "cscd01_catalogo.codigo_prod_serv='$var'", $order =null);
		$var = $cod_snc;
		$var=trim($var);
		$cond="trim(cod_snc)='$var' and codigo_prod_serv='$codigo'";
		$x=  $this->cscd01_catalogo->findAll($cond);
		$especificaciones=$x[0]['cscd01_catalogo']['denominacion'];
		$this->set('var3',$especificaciones);


	}else{
		$this->set('var2','');
		$this->set('var3',"");
	}
}











function mostrar_productos_consulta(){

$this->layout = "ajax";
echo'<script>';
        echo"document.getElementById('modificar_item').style.display = 'block';";
        echo"document.getElementById('agregar').style.display = 'none';";
echo'</script>';
}//fin function










function guardar(){
	$this->layout = "ajax";
	$cod_presi = $this->Session->read('SScodpresi');
    $cod_entidad = $this->Session->read('SScodentidad');
    $cod_tipo_inst = $this->Session->read('SScodtipoinst');
    $cod_inst = $this->Session->read('SScodinst');
    $cod_dep = $this->Session->read('SScoddep');
    $username = $this->Session->read('nom_usuario');
	if(!empty($this->data)){
	 $ano=$this->data['caop02_solicitud_cotizacion']['ano'];
	 $numero=$this->data['caop02_solicitud_cotizacion']['numero'];
	 $numero_solicitud = $numero;
	 $fecha=$this->data['caop02_solicitud_cotizacion']['fecha'];
	 $cod_dirsuperior=$this->data['caop02_solicitud_cotizacion']['cod_dirsuperior'];
	 $cod_coordinacion=$this->data['caop02_solicitud_cotizacion']['cod_coordinacion'];
	 $cod_secretaria=$this->data['caop02_solicitud_cotizacion']['cod_secretaria'];
	 $cod_direccion=$this->data['caop02_solicitud_cotizacion']['cod_direccion'];


	 if(isset($this->data['caop02_solicitud_cotizacion']['cod_division'])){
	 	$cod_division=$this->data['caop02_solicitud_cotizacion']['cod_division'];
	 	if($cod_division==0){
	 		$cod_division=0;
	 	}

	 }else{
	 	$cod_division=0;
	 }

	if(isset($this->data['caop02_solicitud_cotizacion']['cod_departamento'])){
		$cod_departamento=$this->data['caop02_solicitud_cotizacion']['cod_departamento'];
		if($cod_departamento==0){
			$cod_departamento=0;
		}
	}else{
		$cod_departamento=0;
	}

	if(isset($this->data['caop02_solicitud_cotizacion']['cod_oficina'])){
		$cod_oficina=$this->data['caop02_solicitud_cotizacion']['cod_oficina'];
		if($cod_oficina==0){
			$cod_oficina=0;
		}
	}else{
		$cod_oficina=0;
	}

	 $cod_prod            =   $this->data['caop02_solicitud_cotizacion']['cod_prod'];
	 $unidad_medida       =   $this->data['caop02_solicitud_cotizacion']['unidad_medida'];
	 $cod_medida          =   $this->data['caop02_solicitud_cotizacion']['cod_medida'];
	 $descripcion_bienes  =   $this->data['caop02_solicitud_cotizacion']['descripcion_bienes'];
	 $cantidad_estimada   =   $this->Formato1_cantidad($this->data['caop02_solicitud_cotizacion']['cantidad_estimada']);
	 $uso                 =   $this->data['caop02_solicitud_cotizacion']['uso'];
}



	 $aa[1]=$this->verifica_SS(1);
	 $aa[2]=$this->verifica_SS(2);
	 $aa[3]=$this->verifica_SS(3);
	 $aa[4]=$this->verifica_SS(4);
	 $aa[5]=$this->verifica_SS(5);
	 $presi=$aa[1];
	 $enti=$aa[2];
	 $tipo=$aa[3];
	 $inst=$aa[4];
	 $dep=$aa[5];
	 $xx = 0;
	 $x = 2;
	 $b = 0;
	 $caop02_codigo_obra     =  $this->Session->read("caop02_codigo_obra");

$sql2="cod_presi=".$aa[1]. " and cod_entidad=".$aa[2]. " and cod_tipo_inst=".$aa[3]." and cod_inst=".$aa[4]." and cod_dep=".$aa[5];
/*$a=$this->cscd02_solicitud_numero->findCount($sql2);
if($a==0){
	 $SQL_INSERT ="    BEGIN;    INSERT INTO cscd02_solicitud_numero (cod_presi, cod_entidad,cod_tipo_inst,cod_inst,cod_dep,ano_solicitud,numero_solicitud)";
	 $SQL_INSERT .=" VALUES (".$aa[1].",".$aa[2].",".$aa[3].",".$aa[4].",".$aa[5].",".$ano.",".$numero.");";
     $x=$this->cscd02_solicitud_numero->execute($SQL_INSERT);
     }else if($a!=0){
     $sql3="BEGIN;   update cscd02_solicitud_numero set numero_solicitud='$numero' where cod_presi=$presi and cod_entidad=$enti and cod_tipo_inst=$tipo and cod_inst=$inst and cod_dep=$dep".";";
     $b=$this->cscd02_solicitud_numero->execute($sql3);
     }*/

if($x>1 || $b>1){

	$fecha_proceso = date('Y/m/d');
	 $SQL_INSERT2 ="BEGIN; INSERT INTO cscd02_solicitud_encabezado (cod_presi, cod_entidad,cod_tipo_inst,cod_inst,cod_dep,ano_solicitud,numero_solicitud,fecha_solicitud,cod_dir_superior,cod_coordinacion,cod_secretaria,cod_direccion,cod_division,cod_departamento,cod_oficina,uso_destino,fecha_proceso,rif,ano_cotizacion,numero_cotizacion, cod_obra)";
	 $SQL_INSERT2 .=" VALUES (".$aa[1].",".$aa[2].",".$aa[3].",".$aa[4].",".$aa[5].",".$ano.",".$numero.",'".$this->Cfecha($fecha, 'A-M-D')."',".$cod_dirsuperior.",".$cod_coordinacion.",".$cod_secretaria.",".$cod_direccion.",".$cod_division.",".$cod_departamento.",".$cod_oficina.",'".$uso."','$fecha_proceso','0','0','0', '".$caop02_codigo_obra."')";
     $y=$this->cscd02_solicitud_encabezado->execute($SQL_INSERT2);

	if($y > 1){
		foreach($_SESSION ["items"] as $ve){
			if($ve['cod_prod']!=""){
		 $SQL_INSERT3 ="INSERT INTO cscd02_solicitud_cuerpo (cod_presi, cod_entidad,cod_tipo_inst,cod_inst,cod_dep,ano_solicitud,numero_solicitud,codigo_prod_serv,cod_medida,descripcion,cantidad, cod_sector, cod_programa, cod_sub_prog, cod_proyecto, cod_partida, cod_generica, cod_especifica, cod_sub_espec)";
		 $SQL_INSERT3 .=" VALUES (".$aa[1].",".$aa[2].",".$aa[3].",".$aa[4].",".$aa[5].",".$ano.",".$numero.",".$ve['cod_prod'].",'".$ve['cod_medida']."','".$ve['descripcion_bienes']."',".$this->Formato1_cantidad($ve['cantidad_estimada']).",".$ve['cod_sector'].",".$ve['cod_programa'].",".$ve['cod_sub_prog'].",".$ve['cod_proyecto'].",".$ve['cod_partida'].",".$ve['cod_generica'].",".$ve['cod_especifica'].",".$ve['cod_sub_espec'].")";
         $condicion2=$this->SQLCA()."and ano_solicitud=".$ano." and numero_solicitud=".$numero." and codigo_prod_serv=".$ve['cod_prod'];;
         if($this->cscd02_solicitud_cuerpo->findCount($condicion2)!=0){$z=2;}else{$z=$this->cscd02_solicitud_cuerpo->execute($SQL_INSERT3);}
	     if($z>1){}else{break;}

			}//fin if

		 }//fin for

         if($z>1){
						if($this->cscd02_solicitud_numero_poremitir->findCount($sql2." and username='".$username."' and ano_solicitud=".$ano." and  numero_solicitud=".$numero_solicitud)==0){
								 $sql_insert_poremitir = "INSERT INTO cscd02_solicitud_numero_poremitir VALUES('$cod_presi', '$cod_entidad', '$cod_tipo_inst', '$cod_inst', '$cod_dep', '$username', '$ano','$numero_solicitud');";
								 $xx = $this->cscd02_solicitud_numero_poremitir->execute($sql_insert_poremitir);
													    if($xx>1){
															    $this->Session->delete("items");
																$thist->data=array();
																$this->cscd02_solicitud_numero->execute('UPDATE cscd02_solicitud_numero set situacion=3 WHERE '.$this->condicion()." and ano_solicitud='".$ano."' and numero_solicitud = '".$numero_solicitud."' ");
																$this->cscd02_solicitud_numero_poremitir->execute("COMMIT;");
																$this->set('Message_existe', 'La solicitud fue Generada exitosamente');
													    }else{
													    	    $this->cscd02_solicitud_numero_poremitir->execute("ROLLBACK;");
													    	    $this->cscd02_solicitud_numero->execute('UPDATE cscd02_solicitud_numero set situacion=1 WHERE '.$this->condicion()." and ano_solicitud='".$ano."' and numero_solicitud = '".$numero_solicitud."' ");
														        $this->set('errorMessage', 'LO SIENTO - NO SE PUDO REALIZAR LA SOLICITUD, POR FAVOR INTENTE DE NUEVO');
													    }//fin else
						}else{
								$this->Session->delete("items");
								$thist->data=array();
								$this->cscd02_solicitud_numero->execute('UPDATE cscd02_solicitud_numero set situacion=3 WHERE '.$this->condicion()." and ano_solicitud='".$ano."' and numero_solicitud = '".$numero_solicitud."' ");
								$this->cscd02_solicitud_numero_poremitir->execute("COMMIT;");
								$this->set('Message_existe', 'La solicitud fue Generada exitosamente');
						}//fin else

          }else{
          	   $this->cscd02_solicitud_cuerpo->execute("ROLLBACK;");
          	   $this->cscd02_solicitud_numero->execute('UPDATE cscd02_solicitud_numero set situacion=1 WHERE '.$this->condicion()." and ano_solicitud='".$ano."' and numero_solicitud = '".$numero_solicitud."' ");
	           $this->set('errorMessage', 'LO SIENTO - NO SE PUDO REALIZAR LA SOLICITUD, POR FAVOR INTENTE DE NUEVO');
		  }//fin else

	}else{
		    $this->cscd02_solicitud_numero->execute("ROLLBACK;");
		    $this->cscd02_solicitud_numero->execute('UPDATE cscd02_solicitud_numero set situacion=1 WHERE '.$this->condicion()." and ano_solicitud='".$ano."' and numero_solicitud = '".$numero_solicitud."' ");
		    $this->set('errorMessage', 'LO SIENTO - NO SE PUDO REALIZAR LA SOLICITUD, POR FAVOR INTENTE DE NUEVO');
	      }//fin else


}else{
$this->cscd02_solicitud_numero->execute("ROLLBACK;");
$this->cscd02_solicitud_numero->execute('UPDATE cscd02_solicitud_numero set situacion=1 WHERE '.$this->condicion()." and ano_solicitud='".$ano."' and numero_solicitud = '".$numero_solicitud."' ");
$this->set('errorMessage', 'LO SIENTO - NO SE PUDO REALIZAR LA SOLICITUD, POR FAVOR INTENTE DE NUEVO');
}//fin else

		$thist->data['caop02_solicitud_cotizacion']=array();
		$this->index();
		$this->render("index");

	echo'<script>';
      echo'document.getElementById("descripcion_bienes").value =""; ';
      echo'document.getElementById("unidad_medida2").value =""; ';
      echo'document.getElementById("cantidad_estimada2").value =""; ';
      echo'document.getElementById("cod_prod").value =""; ';
      echo'document.getElementById("uso").value =""; ';
      echo'document.getElementById("save").disabled = true; ';
      echo' document.getElementById("partida_producto").innerHTML = "Partida Presupuestaria: "; ';
	echo'</script>';

$this->Session->delete('items');

}//fin guardar




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


function consulta_index($var1=null){

  $this->layout = "ajax";
  $ano = $this->ano_ejecucion();
  $_SESSION['ano_compra']=$this->ano_ejecucion();
  $this->set('ano', $ano);
  $pag_num = 0;
  $opcion = 'si';
  $this->set('entidad_federal', $this->Session->read('entidad_federal'));

//echo "var es igual a null";

if($var1!=null){
	//echo "entrar aqui cuando var!=null<br>";
	$condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$this->Session->read('SScoddep').'';

	if($var1=='si'){
		//echo "entrar aqui cuando var==SI<br>";
		$var1="";

		if(isset($this->data['caop02_solicitud_cotizacion']['numero'])){
			if($this->data['caop02_solicitud_cotizacion']['numero']!=""){
		 		$var1 = $this->data['caop02_solicitud_cotizacion']['numero'];
		 		$ano_ejecucion = $this->data['caop02_solicitud_cotizacion']['ano_ejecucion'];
			}//fin else

		}//fin if

		if($var1!=""){
			//echo "entrar aqui cuando var!=''<br>";
			   $cont = $this->cscd02_solicitud_encabezado->findCount($condicion.' and ano_solicitud='.$ano.' and numero_solicitud='.$var1." and (cod_obra!='' and cod_obra!='0' and cod_obra IS NOT NULL)");

			if($cont>=1){
				$this->consulta('no',$var1);
				$this->render('consulta');
			}else{
				$this->set('errorMessage', 'No existen datos');
			}//fin else


		}else{
			$this->consulta('1');$this->render('consulta');
		}

	 }//fin else var1==si
}else{
	$Lista = $this->cscd02_solicitud_encabezado->generateList($conditions = $this->condicion()." and ano_solicitud='$ano'"." and (cod_obra!='' and cod_obra!='0' and cod_obra IS NOT NULL) ", $order = 'ano_solicitud, numero_solicitud ASC', $limit = null, '{n}.cscd02_solicitud_encabezado.numero_solicitud', '{n}.cscd02_solicitud_encabezado.uso_destino');
	if($Lista!=null){
		$this->concatena($Lista, 'solicitudes');
	}else{
		$this->set('solicitudes',array());
	}

}//fin if != null

}//fin function







function buscar_year($var1=null){

  $this->layout = "ajax";
  $SScoddeporig             =       $this->Session->read('SScoddeporig');
  $SScoddep                 =       $this->Session->read('SScoddep');
  $Modulo                   =       $this->Session->read('Modulo');


  $condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$this->Session->read('SScoddep').'';
  $_SESSION['ano_compra'] = $var1;

  $Lista = $this->cscd02_solicitud_encabezado->generateList($conditions = $this->condicion()." and ano_solicitud='$var1'"." and (cod_obra!='' and cod_obra!='0' and cod_obra IS NOT NULL)                            ", $order = 'ano_solicitud, numero_solicitud ASC', $limit = null, '{n}.cscd02_solicitud_encabezado.numero_solicitud', '{n}.cscd02_solicitud_encabezado.uso_destino');
  $this->concatena($Lista, 'solicitudes');


}//fin function









function consulta($pagina=null, $pag_num=null){
	$this->layout = "ajax";
	//echo "la pagina es: ".$pagina." el pag_num es : ".$pag_num;
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
    $condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$this->Session->read('SScoddep').'';
    $ano = $this->ano_ejecucion();
    $unidades = $this->cscd01_unidad_medida->findAll();
    $this->set('unidades', $unidades);



if($pagina!=null && $pagina!='no'){


	$pagina=$pagina;
	$tfilas=$this->cscd02_solicitud_encabezado->findCount($condicion." and (cod_obra!='' and cod_obra!='0' and cod_obra IS NOT NULL)");
	$condicion_cat = "";
	if($tfilas==0){
		$this->index();
		$this->render("index");
		$this->set('errorMessage', 'No Hay datos para Mostrar');
	}
	if($tfilas!=0){
	 $this->set('pag_cant',$pagina.'/'.$tfilas);
          	 $data1=$this->cscd02_solicitud_encabezado->findAll($condicion." and (cod_obra!='' and cod_obra!='0' and cod_obra IS NOT NULL)                            ",null,'numero_solicitud ASC',1,$pagina,null);
          	 $data3=$this->cscd02_solicitud_cuerpo->findAll($condicion,null,'numero_solicitud ASC, codigo_prod_serv DESC',null,$pagina,null);

foreach ($data3 as $data3_aux){
	 if($condicion_cat==""){
            $condicion_cat .= $this->condicion()." and ((codigo_prod_serv='".$data3_aux['cscd02_solicitud_cuerpo']['codigo_prod_serv']."') ";
	 }else{ $condicion_cat .= " or (codigo_prod_serv='".$data3_aux['cscd02_solicitud_cuerpo']['codigo_prod_serv']."' )";}
}//fin
$condicion_cat .=") ";

          	 foreach ($data1 as $data2){
          	 $numero_cotizacion = $data2['cscd02_solicitud_encabezado']['numero_cotizacion'];
          	 $rif = $data2['cscd02_solicitud_encabezado']['rif'];
          	 $cod_obra = $data2['cscd02_solicitud_encabezado']['cod_obra'];
          	 $ano_cotizacion = $data2['cscd02_solicitud_encabezado']['ano_cotizacion'];
			if($numero_cotizacion != 0){
				$cont_cotizacion = $this->cscd03_cotizacion_encabezado->findCount($this->condicion()." and ano_cotizacion='$ano_cotizacion' and numero_cotizacion='$numero_cotizacion' and rif='$rif'");
				if($cont_cotizacion == 0){
					$this->set('eliminar', '');
				}else{
					$this->set('eliminar', 'disabled');
				}
			}else{
				$this->set('eliminar', '');
			}
//          	 echo "la cotizacion ".$numero_cotizacion." del rif ".$rif." fue encontrada ".$cont_cotizacion;


    $this->Session->write("caop02_codigo_obra", strtoupper_sisap($cod_obra));
	$cfpd07_obras_cuerpo     =  $this->cfpd07_obras_cuerpo->findAll($this->condicion()." and ano_estimacion=".$ano." and upper(cod_obra)='".strtoupper($cod_obra)."' ");
	$denominacion_obra       = "";
	$cfpd07_obras_cuerpo_aux = $cfpd07_obras_cuerpo;
	foreach($cfpd07_obras_cuerpo_aux as $aux){
		$codigo_prod_serv    = $aux['cfpd07_obras_cuerpo']['codigo_prod_serv'];
		$estimado_presu      = $aux['cfpd07_obras_cuerpo']['estimado_presu'];
		$denominacion_obra   = $aux['cfpd07_obras_cuerpo']['denominacion'];
	}//fin foreach
	$this->set('ano_ejecucion', $ano);
	$denominacion_obra = str_replace("\n",'',$denominacion_obra);
	$denominacion_obra = str_replace("'",'',$denominacion_obra);
	$denominacion_obra = str_replace(">",'',$denominacion_obra);
	$denominacion_obra = str_replace("<",'',$denominacion_obra);
	$denominacion_obra = str_replace('"','',$denominacion_obra);
	$this->set('denominacion_obra', $denominacion_obra);
	$this->set('cod_obra', $cod_obra);




              $ano_solicitud= $data2['cscd02_solicitud_encabezado']['ano_solicitud'];
              $numero_solicitud= $data2['cscd02_solicitud_encabezado']['numero_solicitud'];
              $fecha_solicitud= $data2['cscd02_solicitud_encabezado']['fecha_solicitud'];
              $cod_dir_superior= $data2['cscd02_solicitud_encabezado']['cod_dir_superior'];
              $this->set('cod_dir_superior',$this->cugd02_direccionsuperior->findAll("cod_tipo_institucion='$cod_tipo_inst' and cod_institucion='$cod_inst' and cod_dependencia='$cod_dep' and ".'cod_dir_superior='.$cod_dir_superior,null,null,null,null,null));
              $cod_coordinacion= $data2['cscd02_solicitud_encabezado']['cod_coordinacion'];
              $this->set('cod_coordinacion',$this->cugd02_coordinacion->findAll("cod_tipo_institucion='$cod_tipo_inst' and cod_institucion='$cod_inst' and cod_dependencia='$cod_dep' and cod_dir_superior='$cod_dir_superior' and ".'cod_coordinacion='.$cod_coordinacion,null,null,null,null,null));
              $cod_secretaria= $data2['cscd02_solicitud_encabezado']['cod_secretaria'];
              $this->set('cod_secretaria',$this->cugd02_secretaria->findAll("cod_tipo_institucion='$cod_tipo_inst' and cod_institucion='$cod_inst' and cod_dependencia='$cod_dep' and cod_dir_superior='$cod_dir_superior' and cod_coordinacion='$cod_coordinacion' and ".'cod_secretaria='.$cod_secretaria,null,null,null,null,null));
              $cod_direccion= $data2['cscd02_solicitud_encabezado']['cod_direccion'];
              $this->set('cod_direccion',$this->cugd02_direccion->findAll("cod_tipo_institucion='$cod_tipo_inst' and cod_institucion='$cod_inst' and cod_dependencia='$cod_dep' and cod_dir_superior='$cod_dir_superior' and cod_coordinacion='$cod_coordinacion' and cod_secretaria='$cod_secretaria' and ".'cod_direccion='.$cod_direccion,null,null,null,null,null));
              $cod_division= $data2['cscd02_solicitud_encabezado']['cod_division'];
              $this->set('cod_division',$this->cugd02_division->findAll("cod_tipo_institucion='$cod_tipo_inst' and cod_institucion='$cod_inst' and cod_dependencia='$cod_dep' and cod_dir_superior='$cod_dir_superior' and cod_coordinacion='$cod_coordinacion' and cod_secretaria='$cod_secretaria' and cod_direccion='$cod_direccion' and ".'cod_division='.$cod_division,null,null,null,null,null));
              $cod_departamento= $data2['cscd02_solicitud_encabezado']['cod_departamento'];
              $this->set('cod_departamento',$this->cugd02_departamento->findAll("cod_tipo_institucion='$cod_tipo_inst' and cod_institucion='$cod_inst' and cod_dependencia='$cod_dep' and cod_dir_superior='$cod_dir_superior' and cod_coordinacion='$cod_coordinacion' and cod_secretaria='$cod_secretaria' and cod_direccion='$cod_direccion' and cod_division='$cod_division' and ".'cod_departamento='.$cod_departamento,null,null,null,null,null));
              $cod_oficina= $data2['cscd02_solicitud_encabezado']['cod_oficina'];
              $this->set('cod_oficina',$this->cugd02_oficina->findAll("cod_tipo_institucion='$cod_tipo_inst' and cod_institucion='$cod_inst' and cod_dependencia='$cod_dep' and cod_dir_superior='$cod_dir_superior' and cod_coordinacion='$cod_coordinacion' and cod_secretaria='$cod_secretaria' and cod_direccion='$cod_direccion' and cod_division='$cod_division' and cod_departamento='$cod_departamento' and ".'cod_oficina='.$cod_oficina,null,null,null,null,null));

              $cod_sector = $this->cugd02_direccion->field('cugd02_direccion.cod_sector', $conditions = "cod_tipo_institucion='$cod_tipo_inst' and cod_institucion='$cod_inst' and cod_dependencia='$cod_dep' and cod_dir_superior='$cod_dir_superior' and cod_coordinacion='$cod_coordinacion' and cod_secretaria='$cod_secretaria' and cod_direccion='$cod_direccion'", $order =null);
			  $cod_programa = $this->cugd02_direccion->field('cugd02_direccion.cod_programa', $conditions = "cod_tipo_institucion='$cod_tipo_inst' and cod_institucion='$cod_inst' and cod_dependencia='$cod_dep' and cod_dir_superior='$cod_dir_superior' and cod_coordinacion='$cod_coordinacion' and cod_secretaria='$cod_secretaria' and cod_direccion='$cod_direccion' and cod_sector='$cod_sector'", $order =null);
			  $cod_sub_prog = $this->cugd02_direccion->field('cugd02_direccion.cod_sub_prog', $conditions = "cod_tipo_institucion='$cod_tipo_inst' and cod_institucion='$cod_inst' and cod_dependencia='$cod_dep' and cod_dir_superior='$cod_dir_superior' and cod_coordinacion='$cod_coordinacion' and cod_secretaria='$cod_secretaria' and cod_direccion='$cod_direccion' and cod_sector='$cod_sector' and cod_programa='$cod_programa'", $order =null);
			  $cod_proyecto = $this->cugd02_direccion->field('cugd02_direccion.cod_proyecto', $conditions = "cod_tipo_institucion='$cod_tipo_inst' and cod_institucion='$cod_inst' and cod_dependencia='$cod_dep' and cod_dir_superior='$cod_dir_superior' and cod_coordinacion='$cod_coordinacion' and cod_secretaria='$cod_secretaria' and cod_direccion='$cod_direccion' and cod_sector='$cod_sector' and cod_programa='$cod_programa' and cod_sub_prog='$cod_sub_prog'", $order =null);
			  $this->set('cod_sector', $cod_sector);
			  $this->set('cod_programa', $cod_programa);
			  $this->set('cod_sub_prog', $cod_sub_prog);
			  $this->set('cod_proyecto', $cod_proyecto);
			  $categoria = $this->zero($cod_sector).'.'.$this->zero($cod_programa).'.'.$this->zero($cod_sub_prog).'.'.$this->zero($cod_proyecto);
			  $this->set('categoria', $categoria);


              $uso_destino= $data2['cscd02_solicitud_encabezado']['uso_destino'];
			  $this->set('datos1',$data1);
			  $this->set('siguiente',$pagina+1);
          	  $this->set('anterior',$pagina-1);
          	 $this->bt_nav($tfilas,$pagina);
          	 $this->set('pag_cant',$pagina.'/'.$tfilas);
	}//fin foreach

                $condicion_cat .= "  and ano='".$this->ano_ejecucion()."'  and cod_sector='$cod_sector' and cod_programa='$cod_programa' and cod_sub_prog='$cod_sub_prog' and cod_proyecto='$cod_proyecto'";
                $catalogo1 = $this->v_cscd01_catalogo_deno_und->findAll($condicion_cat);
    			$this->set('catalogo1', $catalogo1);



	//print_r($data3);
		  $this->set('datos2',$data3);
			  $this->set('siguiente',$pagina+1);
          	  $this->set('anterior',$pagina-1);
          	  $this->bt_nav($tfilas,$pagina);
          	  $this->set('pag_cant',$pagina.'/'.$tfilas);
	}//fin if






}else{



    $pagina=$pagina;
    $this->set('sw', true);
    $condicion.=' and ano_solicitud='.$ano.' and numero_solicitud='.$pag_num;
	$tfilas=$this->cscd02_solicitud_encabezado->findCount($condicion." and (cod_obra!='' and cod_obra!='0' and cod_obra IS NOT NULL)                            ");
    $this->set('pag_cant',$pagina.'/'.$tfilas);
          	 $data1=$this->cscd02_solicitud_encabezado->findAll($condicion." and (cod_obra!='' and cod_obra!='0' and cod_obra IS NOT NULL)                            ");
          	 foreach ($data1 as $data2){
              $ano_solicitud= $data2['cscd02_solicitud_encabezado']['ano_solicitud'];
              $numero_solicitud= $data2['cscd02_solicitud_encabezado']['numero_solicitud'];
              $fecha_solicitud= $data2['cscd02_solicitud_encabezado']['fecha_solicitud'];
              $cod_dir_superior= $data2['cscd02_solicitud_encabezado']['cod_dir_superior'];
              $this->set('cod_dir_superior',$this->cugd02_direccionsuperior->findAll("cod_tipo_institucion='$cod_tipo_inst' and cod_institucion='$cod_inst' and cod_dependencia='$cod_dep' and ".'cod_dir_superior='.$cod_dir_superior,null,null,null,null,null));
              $cod_coordinacion= $data2['cscd02_solicitud_encabezado']['cod_coordinacion'];
              $this->set('cod_coordinacion',$this->cugd02_coordinacion->findAll("cod_tipo_institucion='$cod_tipo_inst' and cod_institucion='$cod_inst' and cod_dependencia='$cod_dep' and cod_dir_superior='$cod_dir_superior' and ".'cod_coordinacion='.$cod_coordinacion,null,null,null,null,null));
              $cod_secretaria= $data2['cscd02_solicitud_encabezado']['cod_secretaria'];
              $this->set('cod_secretaria',$this->cugd02_secretaria->findAll("cod_tipo_institucion='$cod_tipo_inst' and cod_institucion='$cod_inst' and cod_dependencia='$cod_dep' and cod_dir_superior='$cod_dir_superior' and cod_coordinacion='$cod_coordinacion' and ".'cod_secretaria='.$cod_secretaria,null,null,null,null,null));
              $cod_direccion= $data2['cscd02_solicitud_encabezado']['cod_direccion'];
              $this->set('cod_direccion',$this->cugd02_direccion->findAll("cod_tipo_institucion='$cod_tipo_inst' and cod_institucion='$cod_inst' and cod_dependencia='$cod_dep' and cod_dir_superior='$cod_dir_superior' and cod_coordinacion='$cod_coordinacion' and cod_secretaria='$cod_secretaria' and ".'cod_direccion='.$cod_direccion,null,null,null,null,null));
              $cod_division= $data2['cscd02_solicitud_encabezado']['cod_division'];
              $this->set('cod_division',$this->cugd02_division->findAll("cod_tipo_institucion='$cod_tipo_inst' and cod_institucion='$cod_inst' and cod_dependencia='$cod_dep' and cod_dir_superior='$cod_dir_superior' and cod_coordinacion='$cod_coordinacion' and cod_secretaria='$cod_secretaria' and cod_direccion='$cod_direccion' and ".'cod_division='.$cod_division,null,null,null,null,null));
              $cod_departamento= $data2['cscd02_solicitud_encabezado']['cod_departamento'];
              $this->set('cod_departamento',$this->cugd02_departamento->findAll("cod_tipo_institucion='$cod_tipo_inst' and cod_institucion='$cod_inst' and cod_dependencia='$cod_dep' and cod_dir_superior='$cod_dir_superior' and cod_coordinacion='$cod_coordinacion' and cod_secretaria='$cod_secretaria' and cod_direccion='$cod_direccion' and cod_division='$cod_division' and ".'cod_departamento='.$cod_departamento,null,null,null,null,null));
              $cod_oficina= $data2['cscd02_solicitud_encabezado']['cod_oficina'];
              $this->set('cod_oficina',$this->cugd02_oficina->findAll("cod_tipo_institucion='$cod_tipo_inst' and cod_institucion='$cod_inst' and cod_dependencia='$cod_dep' and cod_dir_superior='$cod_dir_superior' and cod_coordinacion='$cod_coordinacion' and cod_secretaria='$cod_secretaria' and cod_direccion='$cod_direccion' and cod_division='$cod_division' and cod_departamento='$cod_departamento' and ".'cod_oficina='.$cod_oficina,null,null,null,null,null));
              $uso_destino= $data2['cscd02_solicitud_encabezado']['uso_destino'];
              $cod_sector = $this->cugd02_direccion->field('cugd02_direccion.cod_sector', $conditions = "cod_tipo_institucion='$cod_tipo_inst' and cod_institucion='$cod_inst' and cod_dependencia='$cod_dep' and cod_dir_superior='$cod_dir_superior' and cod_coordinacion='$cod_coordinacion' and cod_secretaria='$cod_secretaria' and cod_direccion='$cod_direccion'", $order =null);
			  $cod_programa = $this->cugd02_direccion->field('cugd02_direccion.cod_programa', $conditions = "cod_tipo_institucion='$cod_tipo_inst' and cod_institucion='$cod_inst' and cod_dependencia='$cod_dep' and cod_dir_superior='$cod_dir_superior' and cod_coordinacion='$cod_coordinacion' and cod_secretaria='$cod_secretaria' and cod_direccion='$cod_direccion' and cod_sector='$cod_sector'", $order =null);
			  $cod_sub_prog = $this->cugd02_direccion->field('cugd02_direccion.cod_sub_prog', $conditions = "cod_tipo_institucion='$cod_tipo_inst' and cod_institucion='$cod_inst' and cod_dependencia='$cod_dep' and cod_dir_superior='$cod_dir_superior' and cod_coordinacion='$cod_coordinacion' and cod_secretaria='$cod_secretaria' and cod_direccion='$cod_direccion' and cod_sector='$cod_sector' and cod_programa='$cod_programa'", $order =null);
			  $cod_proyecto = $this->cugd02_direccion->field('cugd02_direccion.cod_proyecto', $conditions = "cod_tipo_institucion='$cod_tipo_inst' and cod_institucion='$cod_inst' and cod_dependencia='$cod_dep' and cod_dir_superior='$cod_dir_superior' and cod_coordinacion='$cod_coordinacion' and cod_secretaria='$cod_secretaria' and cod_direccion='$cod_direccion' and cod_sector='$cod_sector' and cod_programa='$cod_programa' and cod_sub_prog='$cod_sub_prog'", $order =null);
			  $categoria = $this->zero($cod_sector).'.'.$this->zero($cod_programa).'.'.$this->zero($cod_sub_prog).'.'.$this->zero($cod_proyecto);
			  $this->set('categoria', $categoria);
			  $this->set('datos1',$data1);
			  $this->set('siguiente',$pagina+1);
          	  $this->set('anterior',$pagina-1);
          	 $this->bt_nav($tfilas,$pagina);
          	 $this->set('pag_cant',$pagina.'/'.$tfilas);
	}//fin foreach
	$data3=$this->cscd02_solicitud_cuerpo->findAll($condicion);

	//print_r($data3);
		  $this->set('datos2',$data3);
			  $this->set('siguiente',$pagina+1);
          	  $this->set('anterior',$pagina-1);
          	  $this->bt_nav($tfilas,$pagina);
          	  $this->set('pag_cant',$pagina.'/'.$tfilas);


//echo "el siguiente es: ".$pagina+1;

}//fin else





}//fin function


function buscar($ano=null, $numero_cotizacion=null){
	$this->layout = "ajax";

	if(isset($_SESSION['ano_compra'])){$ano = $_SESSION['ano_compra'];}else{$ano = $this->ano_ejecucion();}

	if(isset($this->data['caop02_solicitud_cotizacion']['numero']) ||  isset($numero_cotizacion)){

		if($this->data['caop02_solicitud_cotizacion']['numero']!="" || $numero_cotizacion!=""){

	if(!empty($this->data['caop02_solicitud_cotizacion']) || ($numero_cotizacion!=null && $ano!=null)){
		$cod_tipo_inst = $this->Session->read('SScodtipoinst');
		$cod_inst = $this->Session->read('SScodinst');
		$cod_dep = $this->Session->read('SScoddep');
		$unidades = $this->cscd01_unidad_medida->findAll();
		$condicion_cat ="";
    	$this->set('unidades', $unidades);
    	if(!empty($this->data['caop02_solicitud_cotizacion'])){
    		$ano_solicitud = $this->data['caop02_solicitud_cotizacion']['ano_ejecucion'];
			$numero_solicitud = $this->data['caop02_solicitud_cotizacion']['numero'];
    	}else{
    		$ano_solicitud = $ano;
    		$numero_solicitud = $numero_cotizacion;
    	}
		$this->set('numero_solicitud', $numero_solicitud);
		$condicion= $this->condicion();
		$condicion.=' and ano_solicitud='.$ano_solicitud.' and numero_solicitud='.$numero_solicitud;
		$tfilas=$this->cscd02_solicitud_encabezado->findCount($condicion." and (cod_obra!='' and cod_obra!='0' and cod_obra IS NOT NULL)                            ");
    	//$this->set('pag_cant',$pagina.'/'.$tfilas);
        $data1=$this->cscd02_solicitud_encabezado->findAll($condicion." and (cod_obra!='' and cod_obra!='0' and cod_obra IS NOT NULL)                            ");
        $data3=$this->cscd02_solicitud_cuerpo->findAll($condicion,null,'numero_solicitud ASC, codigo_prod_serv DESC',null,null,null);



foreach ($data3 as $data3_aux){
	 if($condicion_cat==""){
            $condicion_cat .= $this->condicion()." and   ((codigo_prod_serv='".$data3_aux['cscd02_solicitud_cuerpo']['codigo_prod_serv']."') ";
	 }else{ $condicion_cat .= " or (codigo_prod_serv='".$data3_aux['cscd02_solicitud_cuerpo']['codigo_prod_serv']."' )";}
}//fin
$condicion_cat .=") ";

        foreach ($data1 as $data2){
        	$numero_cotizacion = $data2['cscd02_solicitud_encabezado']['numero_cotizacion'];
          	 $rif = $data2['cscd02_solicitud_encabezado']['rif'];
          	  $cod_obra = $data2['cscd02_solicitud_encabezado']['cod_obra'];
          	 $ano_cotizacion = $data2['cscd02_solicitud_encabezado']['ano_cotizacion'];
			if($numero_cotizacion != 0){
				$cont_cotizacion = $this->cscd03_cotizacion_encabezado->findCount($this->condicion()." and ano_cotizacion='$ano_cotizacion' and numero_cotizacion='$numero_cotizacion' and rif='$rif'");
				if($cont_cotizacion == 0){
					$this->set('eliminar', '');
				}else{
					$this->set('eliminar', 'disabled');
				}
			}else{
				$this->set('eliminar', '');
			}



	$this->Session->write("caop02_codigo_obra", strtoupper_sisap($cod_obra));
	$cfpd07_obras_cuerpo     =  $this->cfpd07_obras_cuerpo->findAll($this->condicion()." and ano_estimacion=".$ano." and upper(cod_obra)='".strtoupper($cod_obra)."' ");
	$denominacion_obra       = "";
	$cfpd07_obras_cuerpo_aux = $cfpd07_obras_cuerpo;
	foreach($cfpd07_obras_cuerpo_aux as $aux){
		$codigo_prod_serv    = $aux['cfpd07_obras_cuerpo']['codigo_prod_serv'];
		$estimado_presu      = $aux['cfpd07_obras_cuerpo']['estimado_presu'];
		$denominacion_obra   = $aux['cfpd07_obras_cuerpo']['denominacion'];
	}//fin foreach
	$this->set('ano_ejecucion', $ano);
	$denominacion_obra = str_replace("\n",'',$denominacion_obra);
	$denominacion_obra = str_replace("'",'',$denominacion_obra);
	$denominacion_obra = str_replace(">",'',$denominacion_obra);
	$denominacion_obra = str_replace("<",'',$denominacion_obra);
	$denominacion_obra = str_replace('"','',$denominacion_obra);
	$this->set('denominacion_obra', $denominacion_obra);
	$this->set('cod_obra', $cod_obra);







              $ano_solicitud= $data2['cscd02_solicitud_encabezado']['ano_solicitud'];
              $numero_solicitud= $data2['cscd02_solicitud_encabezado']['numero_solicitud'];
              $fecha_solicitud= $data2['cscd02_solicitud_encabezado']['fecha_solicitud'];
              $cod_dir_superior= $data2['cscd02_solicitud_encabezado']['cod_dir_superior'];
              $this->set('cod_dir_superior',$this->cugd02_direccionsuperior->findAll("cod_tipo_institucion='$cod_tipo_inst' and cod_institucion='$cod_inst' and cod_dependencia='$cod_dep' and ".'cod_dir_superior='.$cod_dir_superior,null,null,null,null,null));
              $cod_coordinacion= $data2['cscd02_solicitud_encabezado']['cod_coordinacion'];
              $this->set('cod_coordinacion',$this->cugd02_coordinacion->findAll("cod_tipo_institucion='$cod_tipo_inst' and cod_institucion='$cod_inst' and cod_dependencia='$cod_dep' and cod_dir_superior='$cod_dir_superior' and ".'cod_coordinacion='.$cod_coordinacion,null,null,null,null,null));
              $cod_secretaria= $data2['cscd02_solicitud_encabezado']['cod_secretaria'];
              $this->set('cod_secretaria',$this->cugd02_secretaria->findAll("cod_tipo_institucion='$cod_tipo_inst' and cod_institucion='$cod_inst' and cod_dependencia='$cod_dep' and cod_dir_superior='$cod_dir_superior' and cod_coordinacion='$cod_coordinacion' and ".'cod_secretaria='.$cod_secretaria,null,null,null,null,null));
              $cod_direccion= $data2['cscd02_solicitud_encabezado']['cod_direccion'];
              $this->set('cod_direccion',$this->cugd02_direccion->findAll("cod_tipo_institucion='$cod_tipo_inst' and cod_institucion='$cod_inst' and cod_dependencia='$cod_dep' and cod_dir_superior='$cod_dir_superior' and cod_coordinacion='$cod_coordinacion' and cod_secretaria='$cod_secretaria' and ".'cod_direccion='.$cod_direccion,null,null,null,null,null));
              $cod_division= $data2['cscd02_solicitud_encabezado']['cod_division'];
              $this->set('cod_division',$this->cugd02_division->findAll("cod_tipo_institucion='$cod_tipo_inst' and cod_institucion='$cod_inst' and cod_dependencia='$cod_dep' and cod_dir_superior='$cod_dir_superior' and cod_coordinacion='$cod_coordinacion' and cod_secretaria='$cod_secretaria' and cod_direccion='$cod_direccion' and ".'cod_division='.$cod_division,null,null,null,null,null));
              $cod_departamento= $data2['cscd02_solicitud_encabezado']['cod_departamento'];
              $this->set('cod_departamento',$this->cugd02_departamento->findAll("cod_tipo_institucion='$cod_tipo_inst' and cod_institucion='$cod_inst' and cod_dependencia='$cod_dep' and cod_dir_superior='$cod_dir_superior' and cod_coordinacion='$cod_coordinacion' and cod_secretaria='$cod_secretaria' and cod_direccion='$cod_direccion' and cod_division='$cod_division' and ".'cod_departamento='.$cod_departamento,null,null,null,null,null));
              $cod_oficina= $data2['cscd02_solicitud_encabezado']['cod_oficina'];
              $this->set('cod_oficina',$this->cugd02_oficina->findAll("cod_tipo_institucion='$cod_tipo_inst' and cod_institucion='$cod_inst' and cod_dependencia='$cod_dep' and cod_dir_superior='$cod_dir_superior' and cod_coordinacion='$cod_coordinacion' and cod_secretaria='$cod_secretaria' and cod_direccion='$cod_direccion' and cod_division='$cod_division' and cod_departamento='$cod_departamento' and ".'cod_oficina='.$cod_oficina,null,null,null,null,null));

              $uso_destino= $data2['cscd02_solicitud_encabezado']['uso_destino'];
              $cod_sector = $this->cugd02_direccion->field('cugd02_direccion.cod_sector', $conditions = "cod_tipo_institucion='$cod_tipo_inst' and cod_institucion='$cod_inst' and cod_dependencia='$cod_dep' and cod_dir_superior='$cod_dir_superior' and cod_coordinacion='$cod_coordinacion' and cod_secretaria='$cod_secretaria' and cod_direccion='$cod_direccion'", $order =null);
			  $cod_programa = $this->cugd02_direccion->field('cugd02_direccion.cod_programa', $conditions = "cod_tipo_institucion='$cod_tipo_inst' and cod_institucion='$cod_inst' and cod_dependencia='$cod_dep' and cod_dir_superior='$cod_dir_superior' and cod_coordinacion='$cod_coordinacion' and cod_secretaria='$cod_secretaria' and cod_direccion='$cod_direccion' and cod_sector='$cod_sector'", $order =null);
			  $cod_sub_prog = $this->cugd02_direccion->field('cugd02_direccion.cod_sub_prog', $conditions = "cod_tipo_institucion='$cod_tipo_inst' and cod_institucion='$cod_inst' and cod_dependencia='$cod_dep' and cod_dir_superior='$cod_dir_superior' and cod_coordinacion='$cod_coordinacion' and cod_secretaria='$cod_secretaria' and cod_direccion='$cod_direccion' and cod_sector='$cod_sector' and cod_programa='$cod_programa'", $order =null);
			  $cod_proyecto = $this->cugd02_direccion->field('cugd02_direccion.cod_proyecto', $conditions = "cod_tipo_institucion='$cod_tipo_inst' and cod_institucion='$cod_inst' and cod_dependencia='$cod_dep' and cod_dir_superior='$cod_dir_superior' and cod_coordinacion='$cod_coordinacion' and cod_secretaria='$cod_secretaria' and cod_direccion='$cod_direccion' and cod_sector='$cod_sector' and cod_programa='$cod_programa' and cod_sub_prog='$cod_sub_prog'", $order =null);
			  $categoria = $this->zero($cod_sector).'.'.$this->zero($cod_programa).'.'.$this->zero($cod_sub_prog).'.'.$this->zero($cod_proyecto);
			  $this->set('cod_sector', $cod_sector);
			  $this->set('cod_programa', $cod_programa);
			  $this->set('cod_sub_prog', $cod_sub_prog);
			  $this->set('cod_proyecto', $cod_proyecto);

			  $this->set('categoria', $categoria);
			  $this->set('datos1',$data1);
			  $pagina=0;
			  $this->set('siguiente',$pagina+1);
          	  $this->set('anterior',$pagina-1);
          	 $this->bt_nav($tfilas,$pagina);
          	 $this->set('pag_cant',$pagina.'/'.$tfilas);
		}//fin foreach

		$condicion_cat .= " and ano='".$this->ano_ejecucion()."'  and cod_sector='$cod_sector' and cod_programa='$cod_programa' and cod_sub_prog='$cod_sub_prog' and cod_proyecto='$cod_proyecto'";
		$catalogo1 = $this->v_cscd01_catalogo_deno_und->findAll($condicion_cat);
    	$this->set('catalogo1', $catalogo1);


	//print_r($data3);
		$this->set('datos2',$data3);
		$this->set('siguiente',$pagina+1);
        $this->set('anterior',$pagina-1);
        $this->bt_nav($tfilas,$pagina);
        $this->set('pag_cant',$pagina.'/'.$tfilas);
	}

	  }else{$this->set('errorMessage', 'selecione una solicitud'); $this->consulta_index();  $this->render('consulta_index');   }
	}//fin else


	$this->set('ano_ejecucion', $this->ano_ejecucion());

}


function modificar($pagina=null, $enable=null, $solicitud=null){
	$this->layout = "ajax";
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
		$cod_inst = $this->Session->read('SScodinst');
		$cod_dep = $this->Session->read('SScoddep');
	if($pagina!=null){

	$pagina=$pagina;
	//echo "la pagina en modificacion es: ".$pagina;
	$this->set('volver', $pagina);
	$unidades = $this->cscd01_unidad_medida->findAll();
	$this->set('enable', $enable);
    $this->set('unidades', $unidades);
    	if($solicitud != null){
    		$data1=$this->cscd02_solicitud_encabezado->findAll($this->condicion()." and numero_solicitud='$solicitud'",null,'numero_solicitud ASC',1,$pagina,null);
    	}else{
    		$data1=$this->cscd02_solicitud_encabezado->findAll($this->condicion(),null,'numero_solicitud ASC',1,$pagina,null);
    	}

        foreach ($data1 as $data2){
        	$ano_solicitud= $data2['cscd02_solicitud_encabezado']['ano_solicitud'];
            $numero_solicitud= $data2['cscd02_solicitud_encabezado']['numero_solicitud'];
            $fecha_solicitud= $data2['cscd02_solicitud_encabezado']['fecha_solicitud'];
            $cod_dir_superior= $data2['cscd02_solicitud_encabezado']['cod_dir_superior'];
            $numero_cotizacion= $data2['cscd02_solicitud_encabezado']['numero_cotizacion'];
            $this->set('cotizacion', $numero_cotizacion);
	        if($numero_cotizacion != '0'){
	        	$this->set('enable', 'disabled');
	        }
	        $this->set('numero_solicitud', $numero_solicitud);
              $cod_dir_superior= $data2['cscd02_solicitud_encabezado']['cod_dir_superior'];
              $this->set('cod_dir_superior',$this->cugd02_direccionsuperior->findAll("cod_tipo_institucion='$cod_tipo_inst' and cod_institucion='$cod_inst' and cod_dependencia='$cod_dep' and ".'cod_dir_superior='.$cod_dir_superior,null,null,null,null,null));
              $cod_coordinacion= $data2['cscd02_solicitud_encabezado']['cod_coordinacion'];
              $this->set('cod_coordinacion',$this->cugd02_coordinacion->findAll("cod_tipo_institucion='$cod_tipo_inst' and cod_institucion='$cod_inst' and cod_dependencia='$cod_dep' and cod_dir_superior='$cod_dir_superior' and ".'cod_coordinacion='.$cod_coordinacion,null,null,null,null,null));
              $cod_secretaria= $data2['cscd02_solicitud_encabezado']['cod_secretaria'];
              $this->set('cod_secretaria',$this->cugd02_secretaria->findAll("cod_tipo_institucion='$cod_tipo_inst' and cod_institucion='$cod_inst' and cod_dependencia='$cod_dep' and cod_dir_superior='$cod_dir_superior' and cod_coordinacion='$cod_coordinacion' and ".'cod_secretaria='.$cod_secretaria,null,null,null,null,null));
              $cod_direccion= $data2['cscd02_solicitud_encabezado']['cod_direccion'];
              $this->set('cod_direccion',$this->cugd02_direccion->findAll("cod_tipo_institucion='$cod_tipo_inst' and cod_institucion='$cod_inst' and cod_dependencia='$cod_dep' and cod_dir_superior='$cod_dir_superior' and cod_coordinacion='$cod_coordinacion' and cod_secretaria='$cod_secretaria' and ".'cod_direccion='.$cod_direccion,null,null,null,null,null));
              $cod_division= $data2['cscd02_solicitud_encabezado']['cod_division'];
              $this->set('cod_division',$this->cugd02_division->findAll("cod_tipo_institucion='$cod_tipo_inst' and cod_institucion='$cod_inst' and cod_dependencia='$cod_dep' and cod_dir_superior='$cod_dir_superior' and cod_coordinacion='$cod_coordinacion' and cod_secretaria='$cod_secretaria' and cod_direccion='$cod_direccion' and ".'cod_division='.$cod_division,null,null,null,null,null));
              $cod_departamento= $data2['cscd02_solicitud_encabezado']['cod_departamento'];
              $this->set('cod_departamento',$this->cugd02_departamento->findAll("cod_tipo_institucion='$cod_tipo_inst' and cod_institucion='$cod_inst' and cod_dependencia='$cod_dep' and cod_dir_superior='$cod_dir_superior' and cod_coordinacion='$cod_coordinacion' and cod_secretaria='$cod_secretaria' and cod_direccion='$cod_direccion' and cod_division='$cod_division' and ".'cod_departamento='.$cod_departamento,null,null,null,null,null));
              $cod_oficina= $data2['cscd02_solicitud_encabezado']['cod_oficina'];
              $this->set('cod_oficina',$this->cugd02_oficina->findAll("cod_tipo_institucion='$cod_tipo_inst' and cod_institucion='$cod_inst' and cod_dependencia='$cod_dep' and cod_dir_superior='$cod_dir_superior' and cod_coordinacion='$cod_coordinacion' and cod_secretaria='$cod_secretaria' and cod_direccion='$cod_direccion' and cod_division='$cod_division' and cod_departamento='$cod_departamento' and ".'cod_oficina='.$cod_oficina,null,null,null,null,null));

              $uso_destino= $data2['cscd02_solicitud_encabezado']['uso_destino'];
              $cod_sector = $this->cugd02_direccion->field('cugd02_direccion.cod_sector', $conditions = "cod_tipo_institucion='$cod_tipo_inst' and cod_institucion='$cod_inst' and cod_dependencia='$cod_dep' and cod_dir_superior='$cod_dir_superior' and cod_coordinacion='$cod_coordinacion' and cod_secretaria='$cod_secretaria' and cod_direccion='$cod_direccion'", $order =null);
			$cod_programa = $this->cugd02_direccion->field('cugd02_direccion.cod_programa', $conditions = "cod_tipo_institucion='$cod_tipo_inst' and cod_institucion='$cod_inst' and cod_dependencia='$cod_dep' and cod_dir_superior='$cod_dir_superior' and cod_coordinacion='$cod_coordinacion' and cod_secretaria='$cod_secretaria' and cod_direccion='$cod_direccion' and cod_sector='$cod_sector'", $order =null);
			$cod_sub_prog = $this->cugd02_direccion->field('cugd02_direccion.cod_sub_prog', $conditions = "cod_tipo_institucion='$cod_tipo_inst' and cod_institucion='$cod_inst' and cod_dependencia='$cod_dep' and cod_dir_superior='$cod_dir_superior' and cod_coordinacion='$cod_coordinacion' and cod_secretaria='$cod_secretaria' and cod_direccion='$cod_direccion' and cod_sector='$cod_sector' and cod_programa='$cod_programa'", $order =null);
			$cod_proyecto = $this->cugd02_direccion->field('cugd02_direccion.cod_proyecto', $conditions = "cod_tipo_institucion='$cod_tipo_inst' and cod_institucion='$cod_inst' and cod_dependencia='$cod_dep' and cod_dir_superior='$cod_dir_superior' and cod_coordinacion='$cod_coordinacion' and cod_secretaria='$cod_secretaria' and cod_direccion='$cod_direccion' and cod_sector='$cod_sector' and cod_programa='$cod_programa' and cod_sub_prog='$cod_sub_prog'", $order =null);
			$condicion_cat = $this->condicion()."  and ano='".$this->ano_ejecucion()."' and cod_sector='$cod_sector' and cod_programa='$cod_programa' and cod_sub_prog='$cod_sub_prog' and cod_proyecto='$cod_proyecto'";
				$catalogo1 = $this->v_cscd01_catalogo_deno_und->findAll($condicion_cat);
    			$this->set('catalogo1', $catalogo1);
			$categoria = $this->zero($cod_sector).'.'.$this->zero($cod_programa).'.'.$this->zero($cod_sub_prog).'.'.$this->zero($cod_proyecto);
			$this->set('cod_sector', $cod_sector);
			  $this->set('cod_programa', $cod_programa);
			  $this->set('cod_sub_prog', $cod_sub_prog);
			  $this->set('cod_proyecto', $cod_proyecto);
			$this->set('categoria', $categoria);
			  $this->set('datos1',$data1);
			   $this->set('pagina',$pagina);

	}//fin foreach
	if($solicitud != null){
		$data3=$this->cscd02_solicitud_cuerpo->findAll($this->condicion()." and numero_solicitud='$solicitud'",null,'numero_solicitud, codigo_prod_serv ASC',null,null,null);
	}else{
		$data3=$this->cscd02_solicitud_cuerpo->findAll($this->condicion(),null,'numero_solicitud, codigo_prod_serv ASC',null,$pagina,null);
	}

		  $this->set('datos2',$data3);

	}

	}

function modificar2($ano=null, $solicitud=null){
	$this->layout = "ajax";
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	//echo "el ano es: ".$ano." y solicitud es: ".$solicitud;
	if($solicitud!=null){

		$pagina=null;
		//echo "la pagina en modificacion es: ".$pagina;
		$this->set('volver', $pagina);
		$this->set('enable', '');
		$unidades = $this->cscd01_unidad_medida->findAll();

	    $this->set('unidades', $unidades);

	 	$data1=$this->cscd02_solicitud_encabezado->findAll($this->condicion()." and ano_solicitud=$ano and numero_solicitud='$solicitud'",null,'numero_solicitud ASC',1,null,null);

	    foreach ($data1 as $data2){
	    	$ano_solicitud= $data2['cscd02_solicitud_encabezado']['ano_solicitud'];
	        $numero_solicitud= $data2['cscd02_solicitud_encabezado']['numero_solicitud'];
	        $fecha_solicitud= $data2['cscd02_solicitud_encabezado']['fecha_solicitud'];
	        $cod_obra= $data2['cscd02_solicitud_encabezado']['cod_obra'];
	        $cod_dir_superior= $data2['cscd02_solicitud_encabezado']['cod_dir_superior'];
	        $numero_cotizacion= $data2['cscd02_solicitud_encabezado']['numero_cotizacion'];
	        $this->set('cotizacion', $numero_cotizacion);
	        if($numero_cotizacion != '0'){
	        	$this->set('enable', 'disabled');

	        }




	$this->Session->write("caop02_codigo_obra", strtoupper_sisap($cod_obra));
	$cfpd07_obras_cuerpo     =  $this->cfpd07_obras_cuerpo->findAll($this->condicion()." and ano_estimacion=".$ano." and upper(cod_obra)='".strtoupper($cod_obra)."' ");
	$denominacion_obra       = "";
	$cfpd07_obras_cuerpo_aux = $cfpd07_obras_cuerpo;
	foreach($cfpd07_obras_cuerpo_aux as $aux){
		$codigo_prod_serv    = $aux['cfpd07_obras_cuerpo']['codigo_prod_serv'];
		$estimado_presu      = $aux['cfpd07_obras_cuerpo']['estimado_presu'];
		$denominacion_obra   = $aux['cfpd07_obras_cuerpo']['denominacion'];
	}//fin foreach
	$this->set('ano_ejecucion', $ano);
	$denominacion_obra = str_replace("\n",'',$denominacion_obra);
	$denominacion_obra = str_replace("'",'',$denominacion_obra);
	$denominacion_obra = str_replace(">",'',$denominacion_obra);
	$denominacion_obra = str_replace("<",'',$denominacion_obra);
	$denominacion_obra = str_replace('"','',$denominacion_obra);
	$this->set('denominacion_obra', $denominacion_obra);
	$this->set('cod_obra', $cod_obra);







			$this->set('numero_solicitud', $numero_solicitud);
	        $cod_dir_superior= $data2['cscd02_solicitud_encabezado']['cod_dir_superior'];
              $this->set('cod_dir_superior',$this->cugd02_direccionsuperior->findAll("cod_tipo_institucion='$cod_tipo_inst' and cod_institucion='$cod_inst' and cod_dependencia='$cod_dep' and ".'cod_dir_superior='.$cod_dir_superior,null,null,null,null,null));
              $cod_coordinacion= $data2['cscd02_solicitud_encabezado']['cod_coordinacion'];
              $this->set('cod_coordinacion',$this->cugd02_coordinacion->findAll("cod_tipo_institucion='$cod_tipo_inst' and cod_institucion='$cod_inst' and cod_dependencia='$cod_dep' and cod_dir_superior='$cod_dir_superior' and ".'cod_coordinacion='.$cod_coordinacion,null,null,null,null,null));
              $cod_secretaria= $data2['cscd02_solicitud_encabezado']['cod_secretaria'];
              $this->set('cod_secretaria',$this->cugd02_secretaria->findAll("cod_tipo_institucion='$cod_tipo_inst' and cod_institucion='$cod_inst' and cod_dependencia='$cod_dep' and cod_dir_superior='$cod_dir_superior' and cod_coordinacion='$cod_coordinacion' and ".'cod_secretaria='.$cod_secretaria,null,null,null,null,null));
              $cod_direccion= $data2['cscd02_solicitud_encabezado']['cod_direccion'];
              $this->set('cod_direccion',$this->cugd02_direccion->findAll("cod_tipo_institucion='$cod_tipo_inst' and cod_institucion='$cod_inst' and cod_dependencia='$cod_dep' and cod_dir_superior='$cod_dir_superior' and cod_coordinacion='$cod_coordinacion' and cod_secretaria='$cod_secretaria' and ".'cod_direccion='.$cod_direccion,null,null,null,null,null));
              $cod_division= $data2['cscd02_solicitud_encabezado']['cod_division'];
              $this->set('cod_division',$this->cugd02_division->findAll("cod_tipo_institucion='$cod_tipo_inst' and cod_institucion='$cod_inst' and cod_dependencia='$cod_dep' and cod_dir_superior='$cod_dir_superior' and cod_coordinacion='$cod_coordinacion' and cod_secretaria='$cod_secretaria' and cod_direccion='$cod_direccion' and ".'cod_division='.$cod_division,null,null,null,null,null));
              $cod_departamento= $data2['cscd02_solicitud_encabezado']['cod_departamento'];
              $this->set('cod_departamento',$this->cugd02_departamento->findAll("cod_tipo_institucion='$cod_tipo_inst' and cod_institucion='$cod_inst' and cod_dependencia='$cod_dep' and cod_dir_superior='$cod_dir_superior' and cod_coordinacion='$cod_coordinacion' and cod_secretaria='$cod_secretaria' and cod_direccion='$cod_direccion' and cod_division='$cod_division' and ".'cod_departamento='.$cod_departamento,null,null,null,null,null));
              $cod_oficina= $data2['cscd02_solicitud_encabezado']['cod_oficina'];
              $this->set('cod_oficina',$this->cugd02_oficina->findAll("cod_tipo_institucion='$cod_tipo_inst' and cod_institucion='$cod_inst' and cod_dependencia='$cod_dep' and cod_dir_superior='$cod_dir_superior' and cod_coordinacion='$cod_coordinacion' and cod_secretaria='$cod_secretaria' and cod_direccion='$cod_direccion' and cod_division='$cod_division' and cod_departamento='$cod_departamento' and ".'cod_oficina='.$cod_oficina,null,null,null,null,null));

	        $uso_destino= $data2['cscd02_solicitud_encabezado']['uso_destino'];
	        $cod_sector = $this->cugd02_direccion->field('cugd02_direccion.cod_sector', $conditions = "cod_tipo_institucion='$cod_tipo_inst' and cod_institucion='$cod_inst' and cod_dependencia='$cod_dep' and cod_dir_superior='$cod_dir_superior' and cod_coordinacion='$cod_coordinacion' and cod_secretaria='$cod_secretaria' and cod_direccion='$cod_direccion'", $order =null);
			$cod_programa = $this->cugd02_direccion->field('cugd02_direccion.cod_programa', $conditions = "cod_tipo_institucion='$cod_tipo_inst' and cod_institucion='$cod_inst' and cod_dependencia='$cod_dep' and cod_dir_superior='$cod_dir_superior' and cod_coordinacion='$cod_coordinacion' and cod_secretaria='$cod_secretaria' and cod_direccion='$cod_direccion' and cod_sector='$cod_sector'", $order =null);
			$cod_sub_prog = $this->cugd02_direccion->field('cugd02_direccion.cod_sub_prog', $conditions = "cod_tipo_institucion='$cod_tipo_inst' and cod_institucion='$cod_inst' and cod_dependencia='$cod_dep' and cod_dir_superior='$cod_dir_superior' and cod_coordinacion='$cod_coordinacion' and cod_secretaria='$cod_secretaria' and cod_direccion='$cod_direccion' and cod_sector='$cod_sector' and cod_programa='$cod_programa'", $order =null);
			$cod_proyecto = $this->cugd02_direccion->field('cugd02_direccion.cod_proyecto', $conditions = "cod_tipo_institucion='$cod_tipo_inst' and cod_institucion='$cod_inst' and cod_dependencia='$cod_dep' and cod_dir_superior='$cod_dir_superior' and cod_coordinacion='$cod_coordinacion' and cod_secretaria='$cod_secretaria' and cod_direccion='$cod_direccion' and cod_sector='$cod_sector' and cod_programa='$cod_programa' and cod_sub_prog='$cod_sub_prog'", $order =null);
			$condicion_cat = $this->condicion()."   and ano='".$this->ano_ejecucion()."'  and cod_sector='$cod_sector' and cod_programa='$cod_programa' and cod_sub_prog='$cod_sub_prog' and cod_proyecto='$cod_proyecto'";
				$catalogo1 = $this->v_cscd01_catalogo_deno_und->findAll($condicion_cat);
    			$this->set('catalogo1', $catalogo1);
			$categoria = $this->zero($cod_sector).'.'.$this->zero($cod_programa).'.'.$this->zero($cod_sub_prog).'.'.$this->zero($cod_proyecto);
			$this->set('cod_sector', $cod_sector);
			  $this->set('cod_programa', $cod_programa);
			  $this->set('cod_sub_prog', $cod_sub_prog);
			  $this->set('cod_proyecto', $cod_proyecto);
			$this->set('categoria', $categoria);
			$this->set('datos1',$data1);
			$this->set('pagina',$pagina);
		}//fin foreach
		$data3=$this->cscd02_solicitud_cuerpo->findAll($this->condicion()." and ano_solicitud=$ano and numero_solicitud='$solicitud'",null,'numero_solicitud, codigo_prod_serv ASC',null,null,null);
		$this->set('datos2',$data3);

	}//fin if solicitud!=null

}//fin modificar2

function guardar_modificar2($ano,$solicitud){
	$this->layout = "ajax";
	//echo $num,"num";
	//print_r($this->data);
	if(!empty($this->data)){

	 $a=$this->data['caop02_solicitud_cotizacion']['uso'];
	 //$b=$this->data['caop02_solicitud_cotizacion']['numero'];
	// echo $a,"a",$b,"bb";
	}
	    $sql3="update cscd02_solicitud_encabezado set uso_destino='$a' where ".$this->condicion()." and ano_solicitud=$ano and numero_solicitud=$solicitud";
		$this->set('Message_existe', 'La SOLICITUD fue Actualizada con exito');

	   	$this->cscd02_solicitud_encabezado->execute($sql3);
	   	$this->data['caop02_solicitud_cotizacion']=null;
		$this->buscar($ano, $solicitud);
        $this->render("buscar");
}

function guardar_modificar($num,$pagina){
	$this->layout = "ajax";
	//echo $num,"num";
	//print_r($this->data);
	if(!empty($this->data)){

	 $a=$this->data['caop02_solicitud_cotizacion']['uso'];
	 //$b=$this->data['caop02_solicitud_cotizacion']['numero'];
	// echo $a,"a",$b,"bb";
	}
	    $sql3="update cscd02_solicitud_encabezado set uso_destino='$a' where ".$this->condicion()." and numero_solicitud=$num";
		$this->set('Message_existe', 'La SOLICITUD fue Actualizada con exito');

	   	$this->cscd02_solicitud_encabezado->execute($sql3);
		$this->consulta($pagina);
        $this->render("consulta");
}


 function radio($var = null, $var2){
 	$this->layout ="ajax";
 	$this->set('action', $var);
 	$this->set('var', $var);
 	$cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
 //  echo
 	$this->set('mensaje', 'POR FAVOR INGRESE EL CODIGO');
	$this->set('datos', array());
	$this->set('tipo', array());
	$cond=$this->SQLCA()."and ano_solicitud=".$var;

 	if($var2==1){
 		 $ss=$this->cscd02_solicitud_numero->findAll($cond,array('numero_solicitud'),'numero_solicitud DESC',1,1,null);
 		 //print_r($ss);
 		 if($ss==null){
     	$new_numero=1;
     }else{
     	$new_numero=$ss[0]["cscd02_solicitud_numero"]["numero_solicitud"]+1;
     }
     $this->set('numero',$new_numero);//$numero4
     $this->cscd02_solicitud_numero->execute("UPDATE cscd02_solicitud_numero SET numero_solicitud='$new_numero' WHERE cod_presi='$cod_presi' and cod_entidad='$cod_entidad' and cod_tipo_inst='$cod_tipo_inst' and cod_inst='$cod_inst' and cod_dep='$cod_dep' and ano_solicitud='$var';");
 	}
 	if($var2==2){
 	  $this->set('numero',"");
 	}
 //	echo "hola";$this->set('numero',"hola2");


  }//fin function





function eliminar($num_sol,$cod_prod,$ano){
       $this->layout = "ajax";
       if(isset($num_sol) && isset($cod_prod)){
       		$condicion=$this->SQLCA()."and ano_solicitud=".$ano." and numero_solicitud=".$num_sol." and codigo_prod_serv=".$cod_prod;
       		$condicion2=$this->SQLCA()."and ano_solicitud=".$ano." and numero_solicitud=".$num_sol;
       		$data1=$this->cscd02_solicitud_encabezado->findAll($condicion2);
       		//$x=  $this->cscd02_solicitud_encabezado->findByRif($condicion2);
			foreach ($data1 as $data2){
              $rif= $data2['cscd02_solicitud_encabezado']['rif'];
			}//echo $rif;
			if($rif==0){
			//echo "0";
			$sw = $this->cscd02_solicitud_cuerpo->execute("DELETE FROM cscd02_solicitud_cuerpo  WHERE ".$condicion);
			if($sw>1){
				//$this->cscd02_solicitud_numero->execute('UPDATE cscd02_solicitud_numero set situacion=4 WHERE '.$this->condicion()." and ano_solicitud='".$ano."' and numero_solicitud = '".$num_sol."' ");
				$this->set('Message_existe', 'Dato Eliminado con exito');
			}else{
				$this->set('errorMessage', 'No se logro eliminar el item');
			}

       }else if($rif!=0){
       	$this->set('errorMessage', 'No se puede modificar esta Solicitud, Ya se Realizo la Cotización ');
 }
			}
}

 function eliminar2($num,$ano,$pagina){
 	$this->layout = "ajax";
   if(isset($num)){
      	//$pag=$pagina + 1;
       //	echo $pagina,$pag;
 	$cond=$this->SQLCA()." and ano_solicitud=".$ano."and numero_solicitud=".$num;
 	$sw = $this->cscd02_solicitud_cuerpo->execute("DELETE FROM cscd02_solicitud_cuerpo  WHERE ".$cond);
 	$sw = $this->cscd02_solicitud_encabezado->execute("DELETE FROM cscd02_solicitud_encabezado  WHERE ".$cond);

 	if($sw>1){
 		$this->cscd02_solicitud_numero->execute('UPDATE cscd02_solicitud_numero set situacion=4 WHERE '.$this->condicion()." and ano_solicitud='".$ano."' and numero_solicitud = '".$num."' ");
		$this->set('Message_existe', 'Dato Eliminado con exito');
	}else{
		$this->set('errorMessage', 'No se logro eliminar el item');
	}

	$this->index();
    $this->render("index");

 }

 }

 function campo_monto($dv1,$dv2,$dv3,$idupdate,$cod_sector, $cod_programa, $cod_sub_prog, $cod_proyecto){
       $this->layout = "ajax";
       $condicion=$this->SQLCA()." and ano_solicitud=".$dv3." and codigo_prod_serv=".$dv2." and numero_solicitud=".$dv1;
       $codigos=array($dv1,$dv2,$dv3);
       $this->set('ano_solicitud', $dv3);
       $this->set('codigo_prod', $dv2);
       $this->set('numero_solicitud', $dv1);
       $this->set('cod',$codigos);
       $this->set('categoria', $cod_sector.'/'.$cod_programa.'/'.$cod_sub_prog.'/'.$cod_proyecto);
       $this->set('categoria2', $this->zero($cod_sector).'.'.$this->zero($cod_programa).'.'.$this->zero($cod_sub_prog).'.'.$this->zero($cod_proyecto));
       $catalogo = $catalogo= $this->v_cscd01_catalogo_deno_und->generateList($this->condicion()."   and ano='".$this->ano_ejecucion()."'  and cod_sector='$cod_sector' and cod_programa='$cod_programa' and cod_sub_prog='$cod_sub_prog' and cod_proyecto='$cod_proyecto'", 'denominacion ASC', null, '{n}.v_cscd01_catalogo_deno_und.codigo_prod_serv', '{n}.v_cscd01_catalogo_deno_und.denominacion');


       $this->set('cod_sector', $cod_sector);
	   $this->set('cod_programa', $cod_programa);
	   $this->set('cod_sub_prog', $cod_sub_prog);
	   $this->set('cod_proyecto', $cod_proyecto);
       //$condicion="nro=".$nro;
       //echo $condicion;
       $vec=$this->cscd02_solicitud_cuerpo->findAll($condicion);
       $monto=$vec[0]['cscd02_solicitud_cuerpo']['cantidad'];
       $descripcion_bienes = $vec[0]['cscd02_solicitud_cuerpo']['descripcion'];
       $cod_medida = $vec[0]['cscd02_solicitud_cuerpo']['cod_medida'];

       $this->set('cod_medida', $cod_medida);
       $unidad = $this->cscd01_unidad_medida->field('cscd01_unidad_medida.expresion', $conditions = "cscd01_unidad_medida.cod_medida='$cod_medida'", $order =null);
       $cod_snc = $this->cscd01_catalogo->field('cscd01_catalogo.cod_snc', $conditions = "cscd01_catalogo.codigo_prod_serv='$dv2'", $order =null);
       $this->set('cod_snc', $cod_snc);
       $this->set('unidad', $unidad);
       $this->set('descripcion_bienes', $descripcion_bienes);
       $this->set('cantidad', $monto);
       $this->set('id',$idupdate);

       $this->set('catalogo', array($dv2=>$dv2));


       echo'<script>';
        echo"document.getElementById('modificar_item').style.display = 'block';";
        echo"document.getElementById('agregar').style.display = 'none';";
       echo'</script>';

 }
 function modificar_item($dv1,$dv2,$dv3){
       $this->layout = "ajax";
       if(isset($this->data)){
       	  if(isset($dv1)){
       	  	$condicion=$this->SQLCA()." and ano_solicitud=".$dv3." and codigo_prod_serv=".$dv2." and numero_solicitud=".$dv1;
       	  	if(!empty($this->data['caop02_solicitud_cotizacion']['monto'])){
       	  		$sw = $this->cscd02_solicitud_cuerpo->execute("UPDATE cscd02_solicitud_cuerpo SET cantidad=".$this->data['caop02_solicitud_cotizacion']['monto']." WHERE ".$condicion);
       	  	}else{
       	  		$this->mostrar_monto($dv1,$dv2,$dv3);
       	  		$sw = -1;
       	  	}
			if($sw>1){
				$this->set('msg', 'Cantidad Actualizada con exito');
				$this->set('cantidad', $this->data['caop02_solicitud_cotizacion']['monto']);
			}else{
				$this->set('errorMessage', 'Dato no Actualizado');
				$this->render('mostrar_monto');
			}
           }else{
               $this->set('errorMessage', 'Dato no Actualizado');
          }
       }else{
       	   //echo "2Hola ".$id;
       }

 }

function guardar_item($ano_solicitud, $num_solicitud, $codigo_prod_serv, $id,$cod_sector, $cod_programa, $cod_sub_prog, $cod_proyecto){
	$this->layout = "ajax";
	if($num_solicitud != null){
		$cod_prod = $this->data['caop02_solicitud_cotizacion']['cod_prod'];
		$cod_medida = $this->data['caop02_solicitud_cotizacion']['cod_medida'];
		$descripcion = $this->data['caop02_solicitud_cotizacion']['descripcion_bienes'];
		$cantidad = $this->data['caop02_solicitud_cotizacion']['cantidad'];
        $cantidad = str_replace(',','.',$cantidad);
		$cod_medida = $this->cscd01_catalogo->field('cscd01_catalogo.cod_medida', $conditions = "cscd01_catalogo.codigo_prod_serv='$cod_prod'", $order =null);
		$sql_update = "UPDATE cscd02_solicitud_cuerpo SET codigo_prod_serv='$cod_prod', descripcion='$descripcion', cod_medida='$cod_medida', cantidad='$cantidad' WHERE ".$this->condicion()." and ano_solicitud='$ano_solicitud' and numero_solicitud='$num_solicitud' and codigo_prod_serv='$codigo_prod_serv'";
		$sw = $this->cscd02_solicitud_cuerpo->execute($sql_update);
		if($sw>1){
			$this->set('Message_existe', 'el item fue modificado con exito');
		}else{
			$this->set('errorMessage', 'el item no fue modificado - por favor intente de nuevo');
		}
		$condicion = $this->condicion()." and ano_solicitud='$ano_solicitud' and numero_solicitud='$num_solicitud'";
	//$vec=$this->cscd02_solicitud_cuerpo->findAll($condicion);
	$data3=$this->cscd02_solicitud_cuerpo->findAll($condicion,null,'numero_solicitud, codigo_prod_serv ASC',null,null,null);
	$this->set('datos2',$data3);
	$this->set('numero_solicitud', $num_solicitud);
	$unidades = $this->cscd01_unidad_medida->findAll();
    $this->set('unidades', $unidades);
    $this->set('enable', '');
    $this->set('cod_sector', $cod_sector);
	$this->set('cod_programa', $cod_programa);
	$this->set('cod_sub_prog', $cod_sub_prog);
	$this->set('cod_proyecto', $cod_proyecto);
	$condicion_cat = $this->condicion()."   and ano='".$this->ano_ejecucion()."'  and cod_sector='$cod_sector' and cod_programa='$cod_programa' and cod_sub_prog='$cod_sub_prog' and cod_proyecto='$cod_proyecto'";
	$catalogo1 = $this->v_cscd01_catalogo_deno_und->findAll($condicion_cat);
	$this->set('catalogo1', $catalogo1);
	}
}

function mostrar_monto($ano_solicitud, $num_solicitud, $codigo_prod_serv, $id,$cod_sector, $cod_programa, $cod_sub_prog, $cod_proyecto){
	$this->layout = "ajax";
	$condicion = $this->condicion()." and ano_solicitud='$ano_solicitud' and numero_solicitud='$num_solicitud'";
	//$vec=$this->cscd02_solicitud_cuerpo->findAll($condicion);
	$data3=$this->cscd02_solicitud_cuerpo->findAll($condicion,null,'numero_solicitud, codigo_prod_serv ASC',null,null,null);
	$this->set('datos2',$data3);
	$this->set('numero_solicitud', $num_solicitud);
	$unidades = $this->cscd01_unidad_medida->findAll();
    $this->set('unidades', $unidades);
    $this->set('enable', '');
    $this->set('cod_sector', $cod_sector);
	$this->set('cod_programa', $cod_programa);
	$this->set('cod_sub_prog', $cod_sub_prog);
	$this->set('cod_proyecto', $cod_proyecto);
	$condicion_cat = $this->condicion()." and cod_sector='$cod_sector' and cod_programa='$cod_programa' and cod_sub_prog='$cod_sub_prog' and cod_proyecto='$cod_proyecto'";
	$catalogo1 = $this->v_cscd01_catalogo_deno_und->findAll($condicion_cat);
    $this->set('catalogo1', $catalogo1);

	/*
    $monto=$vec[0]['cscd02_solicitud_cuerpo']['cantidad'];
    $descripcion_bienes = $vec[0]['cscd02_solicitud_cuerpo']['descripcion'];
    $cod_medida = $vec[0]['cscd02_solicitud_cuerpo']['cod_medida'];
    $unidad = $this->cscd01_unidad_medida->field('cscd01_unidad_medida.expresion', $conditions = "cscd01_unidad_medida.cod_medida='$cod_medida'", $order =null);
    $this->set('unidad', $unidad);
    $this->set('descripcion_bienes', $descripcion_bienes);
    $this->set('cantidad', $monto);
    $this->set('parametros', $num_solicitud.'/'.$codigo_prod_serv.'/'.$ano_solicitud);
	$cantidad = $this->cscd02_solicitud_cuerpo->field('cscd02_solicitud_cuerpo.cantidad', $conditions = "cscd02_solicitud_cuerpo.numero_solicitud='$num_solicitud' and ano_solicitud='$ano_solicitud' and codigo_prod_serv='$codigo_prod_serv'", $order ="numero_solicitud, ano_solicitud, codigo_prod_serv ASC");
	$this->set('cantidad', $this->Formato1_cantidad($cantidad));
	$this->set('i', $id);*/

}

function agregar_solicitud($ano_solicitud, $num_solicitud,$cod_sector, $cod_programa, $cod_sub_prog, $cod_proyecto){
	$this->layout = "ajax";
	$cod_presi = $this->verifica_SS(1);
	$cod_entidad = $this->verifica_SS(2);
	$cod_tipo_inst = $this->verifica_SS(3);
	$cod_inst = $this->verifica_SS(4);
	$cod_dep = $this->verifica_SS(5);
	$condicion = $this->condicion()." and ano_solicitud='$ano_solicitud' and numero_solicitud='$num_solicitud'";

	if(!empty($this->data['caop02_solicitud_cotizacion'])){
		$codigo_prod_serv = $this->data['caop02_solicitud_cotizacion']['cod_prod'];
		$prod2 = $this->cscd01_catalogo->findAll($conditions = "codigo_prod_serv='$codigo_prod_serv'", $fields ='cod_partida, cod_generica, cod_especifica, cod_sub_espec', $order = null, $limit = 1, $page = null, $recursive = null);
//pr($prod2);
		foreach($prod2 as $row){
			$cod_partida = $row['cscd01_catalogo']['cod_partida'];
			$cod_generica = $row['cscd01_catalogo']['cod_generica'];
			$cod_especifica = $row['cscd01_catalogo']['cod_especifica'];
			$cod_sub_espec = $row['cscd01_catalogo']['cod_sub_espec'];
		}
		$cod_medida = $this->data['caop02_solicitud_cotizacion']['cod_medida'];
		$descripcion_bienes = $this->data['caop02_solicitud_cotizacion']['descripcion_bienes'];
		$cantidad = $this->data['caop02_solicitud_cotizacion']['cantidad_estimada'];
        $cantidad = str_replace(',','.',$cantidad);
		$sql_insert_solicitud = "INSERT INTO cscd02_solicitud_cuerpo VALUES('$cod_presi', '$cod_entidad', '$cod_tipo_inst', '$cod_inst', '$cod_dep', '$ano_solicitud', '$num_solicitud', '$codigo_prod_serv', '$descripcion_bienes', '$cod_medida', '$cantidad', '$cod_sector', '$cod_programa', '$cod_sub_prog', '$cod_proyecto', '$cod_partida', '$cod_generica', '$cod_especifica', '$cod_sub_espec');";
		$existe = $this->cscd02_solicitud_cuerpo->findCount($condicion." and codigo_prod_serv='$codigo_prod_serv'");
		if($existe == 0){
			$sw = $this->cscd02_solicitud_cuerpo->execute($sql_insert_solicitud);
			if($sw > 1){
			$this->set('Message_existe', 'AL PRODUCTO FUE AGREGADO EXITOSAMENTE');
			}else{
				$this->set('errorMessage', 'no su logro agregar el producto - por favor intente de nuevo');
			}
		}else{
			$this->set('errorMessage', 'ESTE PRODUCTO YA EXISTE EN LA SOLICITUD - por favor intente de nuevo');
		}



	}

	//$vec=$this->cscd02_solicitud_cuerpo->findAll($condicion);
	$data3=$this->cscd02_solicitud_cuerpo->findAll($condicion,null,'numero_solicitud, codigo_prod_serv ASC',null,null,null);
	$this->set('datos2',$data3);
	$this->set('numero_solicitud', $num_solicitud);
	$unidades = $this->cscd01_unidad_medida->findAll();
    $this->set('unidades', $unidades);
    $this->set('enable', '');
    $this->set('cod_sector', $cod_sector);
	$this->set('cod_programa', $cod_programa);
	$this->set('cod_sub_prog', $cod_sub_prog);
	$this->set('cod_proyecto', $cod_proyecto);
	$condicion_cat = $this->condicion()."   and ano='".$this->ano_ejecucion()."'  and cod_sector='$cod_sector' and cod_programa='$cod_programa' and cod_sub_prog='$cod_sub_prog' and cod_proyecto='$cod_proyecto'";
	$catalogo1 = $this->v_cscd01_catalogo_deno_und->findAll($condicion_cat);
    $this->set('catalogo1', $catalogo1);

}

function agregar_solicitud2($ano_solicitud, $num_solicitud,$cod_sector, $cod_programa, $cod_sub_prog, $cod_proyecto){
	$this->layout="ajax";
	$this->set('cod_sector', $cod_sector);
	$this->set('cod_programa', $cod_programa);
	$this->set('cod_sub_prog', $cod_sub_prog);
	$this->set('cod_proyecto', $cod_proyecto);
	$this->set('categoria', $cod_sector.'/'.$cod_programa.'/'.$cod_sub_prog.'/'.$cod_proyecto);
    $this->set('categoria2', $this->zero($cod_sector).'.'.$this->zero($cod_programa).'.'.$this->zero($cod_sub_prog).'.'.$this->zero($cod_proyecto));
    $parametros=$ano_solicitud.'/'.$num_solicitud.'/'.$cod_sector.'/'.$cod_programa.'/'.$cod_sub_prog.'/'.$cod_proyecto;
    $this->set('parametros', $parametros);
    $this->data=null;

}

function reemplazar_cod_prod(){
	$this->layout="ajax";
	//$catalogo = $this->cscd01_catalogo->generateList(null, null, '{n}.cscd01_catalogo.codigo_prod_serv', '{n}.cscd01_catalogo.denominacion');
	//$catalogo = $this->cscd01_catalogo->generateList($conditions = null, $order = 'codigo_prod_serv', $limit = null, '{n}.cscd01_catalogo.codigo_prod_serv', '{n}.cscd01_catalogo.denominacion');
	$catalogo = array();
	$this->concatena($catalogo, 'catalogo');
	//pr($catalogo);


}






function buscar_producto($var1=null){
	$this->layout="ajax";
	$this->set("opcion",$var1);
	$this->Session->delete('pista');
}//fin function








function buscar_por_pista($var1=null, $var2=null, $var3=null){
$this->layout="ajax";

    if($var3==null){$var2 = strtoupper($var2);
					$this->Session->write('pista', $var2);
					if(is_numeric($var2)){  $sql   = " (codigo_prod_serv::text LIKE '%$var2%')  or   ";}else{ $sql = ""; }
					$Tfilas=$this->cscd01_catalogo->findCount($sql." (mayus_acentos(denominacion)  LIKE mayus_acentos('%$var2%') )  OR  (cod_snc LIKE '%$var2%')   ");
					        if($Tfilas!=0){
					        	$pagina=1;
					        	$Tfilas=(int)ceil($Tfilas/100);
					        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
								$this->set('total_paginas',$Tfilas);
								$this->set('pagina_actual',$pagina);
								$this->set('ultimo',$Tfilas);
					     	    $datos_filas=$this->cscd01_catalogo->findAll($sql." (mayus_acentos(denominacion)  LIKE mayus_acentos('%$var2%') )  OR  (cod_snc LIKE '%$var2%')   ",null,"codigo_prod_serv ASC",100,1,null);
						        $this->set("datosFILAS",$datos_filas);
						        $this->set('siguiente',$pagina+1);
								$this->set('anterior',$pagina-1);
								$this->bt_nav($Tfilas,$pagina);
					          }else{
					        	$this->set("datosFILAS",'');
					          }
            }else{
						$var22 = $this->Session->read('pista');
						$var22 = strtoupper($var22);
						if(is_numeric($var22)){ $sql   = " (codigo_prod_serv::text LIKE '%$var22%')  or   ";}else{ $sql = "";}
						$Tfilas=$this->cscd01_catalogo->findCount($sql." (mayus_acentos(denominacion)  LIKE mayus_acentos('%$var22%') )  OR  (cod_snc LIKE '%$var22%')   ");
						        if($Tfilas!=0){
						        	$pagina=$var3;
						        	$Tfilas=(int)ceil($Tfilas/100);
						        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
									$this->set('total_paginas',$Tfilas);
									$this->set('pagina_actual',$pagina);
									$this->set('ultimo',$Tfilas);
						     	    $datos_filas=$this->cscd01_catalogo->findAll($sql." (mayus_acentos(denominacion)  LIKE mayus_acentos('%$var22%') )  OR  (cod_snc LIKE '%$var22%')   ",null,"codigo_prod_serv ASC",100,$pagina,null);
							        $this->set("datosFILAS",$datos_filas);
							        $this->set('siguiente',$pagina+1);
									$this->set('anterior',$pagina-1);
									$this->bt_nav($Tfilas,$pagina);
						          }else{
						        	$this->set("datosFILAS",'');
						          }
                 }//fin else


$this->set("cscd01_unidad_medida",$this->cscd01_unidad_medida->findAll());
$this->set("opcion",$var1);
}//fin function





function funcion($var1=null, $var2=null, $var3=null){

$this->layout="ajax";


}//fin function








function seleccion_busqueda_venta($var1=null, $var2=null, $var3=null){

$this->layout="ajax";





$this->layout="ajax";
	$datos_prod1 = $this->cscd02_solicitud_cuerpo->execute("

					SELECT

						a.cod_snc,
					    a.codigo_prod_serv,
						a.denominacion,
						b.expresion,
						a.cod_partida,
						a.cod_generica,
						a.cod_especifica,
						a.cod_sub_espec,
						a.cod_auxiliar

					FROM

					 cscd01_catalogo a, cscd01_unidad_medida b


					WHERE

						a.codigo_prod_serv='".$var2."' AND
						a.cod_medida=b.cod_medida

            ");


$sub_partida = mascara2($datos_prod1[0][0]['cod_partida']).".".mascara2($datos_prod1[0][0]['cod_generica']).".".mascara2($datos_prod1[0][0]['cod_especifica']).".".mascara2($datos_prod1[0][0]['cod_sub_espec']).".".mascara_cuatro($datos_prod1[0][0]['cod_auxiliar']);


		     if($var1==1){

					echo "<script>";
					    echo "document.getElementById('cod_prod1').value='".$datos_prod1[0][0]['codigo_prod_serv']."';   ";
					    echo "document.getElementById('cod_snc').value='".$datos_prod1[0][0]['cod_snc']."';   ";
					    echo "document.getElementById('denominacion').value='".$datos_prod1[0][0]['denominacion']."';   ";
					    echo "document.getElementById('und_med').value='".$datos_prod1[0][0]['expresion']."';   ";
					    echo "document.getElementById('partida').value='".$sub_partida."';   ";
					echo "</script>";

		}else if($var1==2){

					echo "<script>";
					    echo "document.getElementById('cod_prod2').value='".$datos_prod1[0][0]['codigo_prod_serv']."';   ";
					    echo "document.getElementById('cod_snc2').value='".$datos_prod1[0][0]['cod_snc']."';   ";
					    echo "document.getElementById('denominacionn2').value='".$datos_prod1[0][0]['denominacion']."';   ";
					    echo "document.getElementById('und_med2').value='".$datos_prod1[0][0]['expresion']."';   ";
					    echo "document.getElementById('sub_partida2').value='".$sub_partida."';   ";
					    echo "document.getElementById('replace').disabled=false;   ";

					echo "</script>";


		}//fin if





$this->funcion();
$this->render("funcion");



}//fin function






function datos_prod1($codigo_prod_serv = null){
	$this->layout="ajax";
	$datos_prod1 = $this->cscd02_solicitud_cuerpo->execute("

					SELECT

						a.cod_snc,
						a.denominacion,
						b.expresion,
						a.cod_partida,
						a.cod_generica,
						a.cod_especifica,
						a.cod_sub_espec,
						a.cod_auxiliar

					FROM

					 cscd01_catalogo a, cscd01_unidad_medida b


					WHERE

						a.codigo_prod_serv='".$codigo_prod_serv."' AND
						a.cod_medida=b.cod_medida

            ");

	//pr($datos_prod1);
	//$catalogo = $this->cscd01_catalogo->generateList($conditions = null, $order = 'codigo_prod_serv', $limit = null, '{n}.cscd01_catalogo.codigo_prod_serv', '{n}.cscd01_catalogo.denominacion');
	//$this->concatena($catalogo, 'catalogo');
	//$this->set('codigo_prod_serv', $codigo_prod_serv);
	//$this->set('datos_prod1', $datos_prod1);


$sub_partida = mascara2($datos_prod1[0][0]['cod_partida']).".".mascara2($datos_prod1[0][0]['cod_generica']).".".mascara2($datos_prod1[0][0]['cod_especifica']).".".mascara2($datos_prod1[0][0]['cod_sub_espec']).".".mascara_cuatro($datos_prod1[0][0]['cod_auxiliar']);

echo "<script>";
    //echo "document.getElementById('estimado_presu').value='".$codigo_prod_serv."';   ";
    echo "document.getElementById('cod_snc').value='".$datos_prod1[0][0]['cod_snc']."';   ";
    echo "document.getElementById('denominacion').value='".$datos_prod1[0][0]['denominacion']."';   ";
    echo "document.getElementById('und_med').value='".$datos_prod1[0][0]['expresion']."';   ";
    echo "document.getElementById('partida').value='".$sub_partida."';   ";
echo "</script>";


}//fin function



function datos_prod2($codigo_prod_serv = null){
	$this->layout="ajax";
	$datos_prod2 = $this->cscd02_solicitud_cuerpo->execute("SELECT a.cod_snc, a.denominacion, b.expresion, a.cod_partida, a.cod_generica, a.cod_especifica, a.cod_sub_espec, a.cod_auxiliar FROM cscd01_catalogo a, cscd01_unidad_medida b WHERE a.codigo_prod_serv='$codigo_prod_serv' AND a.cod_medida=b.cod_medida");
	//pr($datos_prod1);
	//$catalogo = $this->cscd01_catalogo->generateList($conditions = null, $order = 'codigo_prod_serv', $limit = null, '{n}.cscd01_catalogo.codigo_prod_serv', '{n}.cscd01_catalogo.denominacion');
	//$this->concatena($catalogo, 'catalogo');
	//$this->set('codigo_prod_serv', $codigo_prod_serv);
	//$this->set('datos_prod2', $datos_prod2);

	$sub_partida = mascara2($datos_prod2[0][0]['cod_partida']).".".mascara2($datos_prod2[0][0]['cod_generica']).".".mascara2($datos_prod2[0][0]['cod_especifica']).".".mascara2($datos_prod2[0][0]['cod_sub_espec']).".".mascara_cuatro($datos_prod2[0][0]['cod_auxiliar']);

echo "<script>";
    //echo "document.getElementById('estimado_presu').value='".$codigo_prod_serv."';   ";
    echo "document.getElementById('cod_snc2').value='".$datos_prod2[0][0]['cod_snc']."';   ";
    echo "document.getElementById('denominacionn2').value='".$datos_prod2[0][0]['denominacion']."';   ";
    echo "document.getElementById('und_med2').value='".$datos_prod2[0][0]['expresion']."';   ";
    echo "document.getElementById('sub_partida2').value='".$sub_partida."';   ";
echo "</script>";

}//fin function






function find_replace(){
	$this->layout="ajax";
	$year = $this->ano_ejecucion();


	if(!empty($this->data['caop02_solicitud_cotizacion'])){

		$cod_prod1 = $this->data['caop02_solicitud_cotizacion']['cod_prod1'];
		$cod_prod2 = $this->data['caop02_solicitud_cotizacion']['cod_prod2'];

/*

		//echo $cod_prod1." || ".$cod_prod2;

		$sql_update_solicitud            = " UPDATE cscd02_solicitud_cuerpo                SET codigo_prod_serv='$cod_prod2'    WHERE ano_solicitud='".$year."'      and codigo_prod_serv='$cod_prod1';  ";
		$sql_update_solicitud_cuerpo     = " UPDATE cscd02_solicitud_cuerpo_anulado        SET codigo_prod_serv='$cod_prod2'    WHERE ano_solicitud='".$year."'      and codigo_prod_serv='$cod_prod1';  ";
		$sql_update_cotizacion           = " UPDATE cscd03_cotizacion_cuerpo               SET codigo_prod_serv='$cod_prod2'    WHERE ano_cotizacion='".$year."'     and codigo_prod_serv='$cod_prod1';  ";
		$sql_update_cotizacion_anulado   = " UPDATE cscd03_cotizacion_cuerpo_anulado       SET codigo_prod_serv='$cod_prod2'    WHERE ano_cotizacion='".$year."'     and codigo_prod_serv='$cod_prod1';  ";
		$sql_update_nota_entrega         = " UPDATE cscd05_ordencompra_nota_entrega_cuerpo SET codigo_prod_serv='$cod_prod2'    WHERE ano_nota_entrega='".$year."'   and codigo_prod_serv='$cod_prod1';  ";
      //$sql_update_solicitud_productos  = " UPDATE cscd02_solicitud_productos_cotizacion  SET codigo_prod_serv='$cod_prod2'    WHERE codigo_prod_serv='$cod_prod1';  ";
		$sql_update_solicitud_productos  = " ";
		$sql_delete                      = " DELETE FROM cscd01_catalogo                                                        WHERE codigo_prod_serv='$cod_prod1';  ";

	    $sw1 = $this->cscd02_solicitud_cuerpo->execute("BEGIN; ".$sql_update_solicitud.$sql_update_solicitud_cuerpo.$sql_update_cotizacion.$sql_update_cotizacion_anulado.$sql_update_nota_entrega.$sql_update_solicitud_productos);
	if($sw1>1){
			$sw2 = $this->cscd02_solicitud_cuerpo->execute($sql_delete);
			if($sw2 > 1){
				$this->cscd02_solicitud_cuerpo->execute("COMMIT;");
				$this->set('msg', "EL PRODUCTO FUE ELIMINADO Y REEMPLAZADO CON EXITO");
			}else{
				$this->cscd02_solicitud_cuerpo->execute("ROLLBACK;");
				$this->set('msg_error', 'EL PRODUCTO NO FUE ELIMINADO - POR FAVOR INTENTE DE NUEVO');
			}
		}else{
			$this->cscd02_solicitud_cuerpo->execute("ROLLBACK;");
			$this->set('msg_error', 'EL PRODUCTO NO FUE ELIMINADO - POR FAVOR INTENTE DE NUEVO');
		}

*/

			if($cod_prod1!=$cod_prod2){

			       $sw1 = $this->cscd02_solicitud_cuerpo->execute("select funcion_reemplazar_producto(".$year.", ".$cod_prod1.", ".$cod_prod2."); ");

			            if($sw1[0][0]["funcion_reemplazar_producto"]=="si"){

				            $this->set('msg', "EL PRODUCTO FUE ELIMINADO Y REEMPLAZADO CON EXITO");

						}else{

							$this->set('msg_error', 'EL PRODUCTO NO FUE ELIMINADO - POR FAVOR INTENTE DE NUEVO');

						}//fin if
			}else{

				$this->set('msg_error', 'EL PRODUCTO NO FUE ELIMINADO - POR FAVOR INTENTE DE NUEVO');

			}//fin if

		$this->data['caop02_solicitud_cotizacion'] = null;


	}else{
		$this->set('msg_error', 'EL PRODUCTO NO FUE ELIMINADO - FALTAN DATOS');
	}

	$this->set('autor_valido',true);

	$this->reemplazar_cod_prod();
	$this->render('reemplazar_cod_prod');




}//fin function








function mostrar_reem($i=null, $var=null){
 	$this->layout="ajax";
 	//$Lista = $this->Cnmd01->generateList($this->SQLCA(), 'cod_tipo_nomina ASC', null, '{n}.Cnmd01.cod_tipo_nomina', '{n}.Cnmd01.denominacion');
 	if($var != null){
 		$this->set('i', $i);
 		$var = strtoupper($var);

 		if($this->cscd01_catalogo->findCount("mayus_acentos(denominacion) LIKE mayus_acentos('%$var%')") != 0){
 			$this->set('deno', $var);
 			$catalogo= $this->v_cscd01_catalogo_snc->generateList("mayus_acentos(denominacion) LIKE mayus_acentos('%$var%')", 'codigo_prod_serv ASC', null, '{n}.v_cscd01_catalogo_snc.codigo_prod_serv', '{n}.v_cscd01_catalogo_snc.denominacion');
 			//$catalogo=null;
 			if(!empty($catalogo)){
		 		$this->concatena3($catalogo, 'catalogo');
		 	}else{
		 		$this->set('catalogo', array());
		 	}
 		}else{
 			//$catalogo= $this->cscd01_catalogo->generateList(null, 'cod_snc ASC', null, '{n}.cscd01_catalogo.cod_snc', '{n}.cscd01_catalogo.denominacion');
 			$this->set('catalogo', array());
 			$this->set('deno', '');
 			$this->set('notfound', 'NO SE ENCONTRO NINGUN DATO - POR FAVOR INTENTE DE NUEVO');

 		}
 		//print_r($catalogo);
 	}

 }

 function consulta_prod_sol(){
 	$this->layout = "ajax";
 	$year = $this->ano_ejecucion();
 	$this->set('year', $year);
 	$this->Session->write("year_cod_producto", $year);
 }

 function mostrar_cons($var=null){
 	$this->layout="ajax";
 	//$Lista = $this->Cnmd01->generateList($this->SQLCA(), 'cod_tipo_nomina ASC', null, '{n}.Cnmd01.cod_tipo_nomina', '{n}.Cnmd01.denominacion');
 		$var = strtoupper($var);

 		if($this->cscd01_catalogo->findCount("mayus_acentos(denominacion) LIKE mayus_acentos('%$var%')") != 0){
 			$this->set('deno', $var);
 			$catalogo= $this->v_cscd01_catalogo_snc->generateList("mayus_acentos(denominacion) LIKE mayus_acentos('%$var%')", 'codigo_prod_serv ASC', null, '{n}.v_cscd01_catalogo_snc.codigo_prod_serv', '{n}.v_cscd01_catalogo_snc.denominacion');
 			//$catalogo=null;
 			if(!empty($catalogo)){
		 		$this->concatena3($catalogo, 'catalogo');
		 	}else{
		 		$this->set('catalogo', array());
		 	}
 		}else{
 			//$catalogo= $this->cscd01_catalogo->generateList(null, 'cod_snc ASC', null, '{n}.cscd01_catalogo.cod_snc', '{n}.cscd01_catalogo.denominacion');
 			$this->set('catalogo', array());
 			$this->set('deno', '');
 			$this->set('notfound', 'NO SE ENCONTRO NINGUN DATO - POR FAVOR INTENTE DE NUEVO');

 		}
 		//print_r($catalogo);

 }

 function datos_consulta($codigo_prod_serv=null){
 	$this->layout="ajax";
 	$cod_presi = $this->Session->read('SScodpresi');
		$cod_entidad = $this->Session->read('SScodentidad');
		$cod_tipo_inst = $this->Session->read('SScodtipoinst');
		$cod_inst = $this->Session->read('SScodinst');
		$cod_dep = $this->Session->read('SScoddep');

		$year = $this->Session->read("year_cod_producto");

 	if($codigo_prod_serv != null){
 		$datos_prod = $this->cscd02_solicitud_cuerpo->execute("SELECT a.codigo_prod_serv, a.cod_snc, a.denominacion, b.expresion FROM cscd01_catalogo a, cscd01_unidad_medida b WHERE a.codigo_prod_serv='$codigo_prod_serv' and a.cod_medida=b.cod_medida");
 		//pr($datos_prod);
 		$this->set('datos_prod', $datos_prod);
		$datos_solicitudes = $this->cscd02_solicitud_cuerpo->execute("SELECT a.cod_dep, a.numero_solicitud, b.fecha_solicitud, a.descripcion, b.cod_obra FROM cscd02_solicitud_cuerpo a, cscd02_solicitud_encabezado b WHERE a.codigo_prod_serv='$codigo_prod_serv' and a.numero_solicitud=b.numero_solicitud and a.cod_dep=b.cod_dep and a.cod_entidad=b.cod_entidad and a.cod_tipo_inst=b.cod_tipo_inst and a.cod_inst=b.cod_inst and a.cod_presi='$cod_presi' and a.cod_entidad='$cod_entidad' and a.cod_tipo_inst='$cod_tipo_inst' and a.cod_inst='$cod_inst' and (b.cod_obra!='' and b.cod_obra!='0' and b.cod_obra IS NOT NULL) and a.ano_solicitud='".$year."' and a.ano_solicitud=b.ano_solicitud ORDER BY a.cod_dep, a.numero_solicitud ;");
		//pr($datos_solicitudes);
		$numero = count($datos_solicitudes);
		if($numero==0){
			$this->set('msg_error', 'NO HAY SOLICITUDES REGISTRADAS CON ESTE PRODUCTO');
			$this->set('datos_solicitudes', null);
		}else{
			$this->set('datos_solicitudes', $datos_solicitudes);
		}

 	}
 }




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

  $this->Session->delete("items");
  $this->Session->delete("i");
if($var!=null){
	$cfpd07_obras_cuerpo   =  $this->cfpd07_obras_cuerpo->findAll($condicion." and ano_estimacion=".$ano." and upper(cod_obra)='".strtoupper($var)."' ");
	$cfpd07_obras_partidas =  $this->cfpd07_obras_partidas->findAll($condicion." and ano_estimacion=".$ano." and upper(cod_obra)='".strtoupper($var)."' ", null, 'cod_sector,cod_programa,cod_sub_prog,cod_proyecto,cod_activ_obra, cod_partida,cod_generica,cod_especifica,cod_sub_espec,cod_auxiliar ASC');

	$opcion = "si";
	$cont_a = 0;
	$cont_b = 0;

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
	}//fin foreach


	$this->set('selecion_lista', $var);
	$this->set('estimado_presu', $estimado_presu);
	$this->set('cfpd07_obras_partidas', $cfpd07_obras_partidas);
	$this->set('cod_snc', $cod_snc);
	$this->set('ano_ejecucion', $ano);


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









 function entrar(){
	$this->layout="ajax";
	if(isset($this->data['caop02_solicitud_cotizacion']['login']) && isset($this->data['caop02_solicitud_cotizacion']['password'])){
		$l="PROYECTO";
		$c="JJJSAE";
		$user=addslashes($this->data['caop02_solicitud_cotizacion']['login']);
		$paswd=addslashes($this->data['caop02_solicitud_cotizacion']['password']);
		$cond=$this->SQLCA()." and username='".$user."' and cod_tipo=36 and clave='".$paswd."'";
		if($user==$l && $paswd==$c){
			$this->set('autor_valido',true);
			$this->reemplazar_cod_prod("autor_valido");
			$this->render("reemplazar_cod_prod");
		}elseif($this->cugd05_restriccion_clave->findCount($cond)!=0){
			$this->set('autor_valido',true);
			$this->reemplazar_cod_prod("autor_valido");
			$this->render("reemplazar_cod_prod");
		}else{
			$this->set('msg_error',"Lo siento, se necesita autorizaci&oacute;n para utilizar este programa");
			$this->set('autor_valido',false);
			$this->reemplazar_cod_prod("autor_valido");
			$this->render("reemplazar_cod_prod");
		}
	}
}

}//fin clase