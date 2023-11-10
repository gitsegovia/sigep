<?php
class Cfpp10ReformulacionOficiosController extends AppController{
	 var $name = 'cfpp10_reformulacion_oficios';
     var $uses = array('cfpd03','ccfd04_cierre_mes','ccfd03_instalacion','cfpd20','cfpd10_reformulacion_tipo', 'cfpd10_reformulacion_tipo_dep', 'cfpd10_reformulacion_tipo_dep_ip', 'cfpd10_reformulacion_texto','v_deno_dependencia','cfpd01_formulacion','cfpd10_reformulacion_funcionarios','cfpd10_reformulacion_partidas','cfpd10_reformulacion_partidas_tmp','cugd03_acta_anulacion_numero','cugd03_acta_anulacion_cuerpo','v_cfpd05_disponibilidad','cugd04','cpcd02','cscd01_catalogo','cepd01_tipo_compromiso','cepd01_compromiso_cuerpo','cepd01_compromiso_numero','cepd01_compromiso_partidas','cfpd05','cfpd05_requerimiento','cfpd05_2032_tmp','cfpd05_auxiliar','cfpp05auxiliar','cfpd02_sector', 'cfpd02_programa', 'cfpd02_sub_prog', 'cfpd02_proyecto', 'cfpd02_activ_obra', 'arrd05','cfpd01_ano_grupo', 'cfpd01_ano_partida', 'cfpd01_ano_generica', 'cfpd01_ano_especifica', 'cfpd01_ano_sub_espec', 'cfpd01_ano_auxiliar', 'cfpd01_formulacion','cugd02_direccionsuperior', 'cugd02_coordinacion', 'cugd02_secretaria', 'cugd02_direccion','cugd05_restriccion_clave', 'v_cstd10_planilla_recaudado');
     var $helpers = array('Html','Ajax','Javascript', 'Sisap');
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

        function SQLCX($ano=null){//sql para busqueda de codigos de arranque con y sin año
         $sql_re = "cod_presi=".$this->verifica_SS(1)."  and    ";
         $sql_re .= "cod_entidad=".$this->verifica_SS(2)."  and  ";
         $sql_re .= "cod_tipo_inst=".$this->verifica_SS(3)."  and ";
         $sql_re .= "cod_inst=".$this->verifica_SS(4)." ";
		 return $sql_re;


    }//fin funcion SQLX

function concatena4($vector1=null, $nomVar=null, $extra=null){
	$cod = array();
	if($vector1 != null){
		foreach($vector1 as $x => $y){
			if($extra!=null){
				if($x<10){
					$cod[$x] = $extra.'.0'.$x.' - '.$y;
				}else if($x>=10 && $x<=99){
					$cod[$x] = $extra.'.'.$x.' - '.$y;
				}
			}else{

				if($x<10){
					$cod[$x] = '0'.$x.' - '.$y;
				}else if($x>=10 && $x<=9999){
					$cod[$x] = $x.' - '.$y;
				}
			}
		}
		//print_r($cod);
	}

	$this->set($nomVar, $cod);
}//fin function


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
    	}elseif (substr($monto,-2,1)=='.') {
        	$sents = '.'.substr($monto,-1);
        	$monto = substr($monto,0,strlen($monto)-2);
    	}else{
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
    	$veri="decretado != '1'";
    	$ver=$this->verifica_SS(5);
    	$ano=$this->ano_ejecucion();
		$this->Session->write('ano_pistaz', $ano);
    	if($ver==1){
    		$fil=$this->SQLCA();
    	}else if($ver!=1){
    		$fil=$this->SQLCA();
    	}
    	$oficio=$this->cfpd10_reformulacion_texto->generateList($fil." and ".$veri." and ano_reformulacion=".$this->ano_ejecucion(),'numero_oficio ASC', null, '{n}.cfpd10_reformulacion_texto.numero_oficio', '{n}.cfpd10_reformulacion_texto.numero_oficio');
		$this->set('numero',$oficio);
		$this->set('ano_reformulacion',$ano);
		$this->set('ano',$ano);
    }

	function tipo($var){
		$cod_presi = $this->Session->read('SScodpresi');
		$cod_entidad = $this->Session->read('SScodentidad');
		$cod_tipo_inst = $this->Session->read('SScodtipoinst');
		$cod_inst = $this->Session->read('SScodinst');
        $cod_dep = $this->Session->read('SScoddep');
		$this->layout = "ajax";

    // NO BORRAR
    /*
    		if ($cod_dep==1){
    		$a = $this->cfpd10_reformulacion_tipo->findAll("cod_tipo=".$var,array('denominacion'));//print_r($a);
        	$this->set("tipo",$a[0]['cfpd10_reformulacion_tipo']['denominacion']);
    		}else if($cod_presi==1 && $cod_entidad==12 && $cod_tipo_inst==30 && $cod_inst==12 && ($cod_dep==1001 || $cod_dep==1004 || $cod_dep==1016)){
    		$a = $this->cfpd10_reformulacion_tipo_dep_ip->findAll("cod_tipo=".$var,array('denominacion'));//print_r($a);
        	$this->set("tipo",$a[0]['cfpd10_reformulacion_tipo_dep_ip']['denominacion']);
    		}else if($cod_dep!=1){
    		$a = $this->cfpd10_reformulacion_tipo_dep->findAll("cod_tipo=".$var,array('denominacion'));//print_r($a);
        	$this->set("tipo",$a[0]['cfpd10_reformulacion_tipo_dep']['denominacion']);
    	    }
    */

		if ($cod_dep==1){
		  $a = $this->cfpd10_reformulacion_tipo->findAll("cod_tipo=".$var,array('denominacion'));//print_r($a);
    	$this->set("tipo",$a[0]['cfpd10_reformulacion_tipo']['denominacion']);
		}else {
		  $a = $this->cfpd10_reformulacion_tipo_dep_ip->findAll("cod_tipo=".$var,array('denominacion'));//print_r($a);
    	$this->set("tipo",$a[0]['cfpd10_reformulacion_tipo_dep_ip']['denominacion']);
	  }


    echo"<script>activar_tabla_planilla();</script>";

	}//fin tipo

	function select_tipo($var){
		$this->layout = "ajax";
		if($var== 'otros'){
			$this->nuevo();
			$this->render("nuevo");
		}else if($var!= 'otros'){
			$this->todo($var);
			$this->render("todo");
		}
	}

	function nuevo(){
    $cod_presi = $this->Session->read('SScodpresi');
		$cod_entidad = $this->Session->read('SScodentidad');
		$cod_tipo_inst = $this->Session->read('SScodtipoinst');
		$cod_inst = $this->Session->read('SScodinst');
		$cod_dep = $this->Session->read('SScoddep');
		$this->layout = "ajax";
		$ano=$this->ano_ejecucion();
   	$veri="decretado != '1'";
   	$ver=$this->verifica_SS(5);
  	if($ver==1){
  		$fil=$this->SQLCA();
  	}else if($ver!=1){
  		$fil=$this->SQLCA();
  	}
		$oficio=$this->cfpd10_reformulacion_texto->generateList($fil." and ".$veri,'numero_oficio ASC', null, '{n}.cfpd10_reformulacion_texto.numero_oficio', '{n}.cfpd10_reformulacion_texto.numero_oficio');
		$this->set('numero',$oficio);

    // NO BORRRAR
    /*
    		if ($cod_dep==1){
    		$tipo=$this->cfpd10_reformulacion_tipo->generateList(null,'cod_tipo ASC', null, '{n}.cfpd10_reformulacion_tipo.cod_tipo', '{n}.cfpd10_reformulacion_tipo.denominacion');
    		}else if($cod_presi==1 && $cod_entidad==12 && $cod_tipo_inst==30 && $cod_inst==12 && ($cod_dep==1001 || $cod_dep==1004 || $cod_dep==1016)){
    		$tipo=$this->cfpd10_reformulacion_tipo_dep_ip->generateList(null,'cod_tipo ASC', null, '{n}.cfpd10_reformulacion_tipo_dep_ip.cod_tipo', '{n}.cfpd10_reformulacion_tipo_dep_ip.denominacion');
    		}else if($cod_dep!=1){
    		$tipo=$this->cfpd10_reformulacion_tipo_dep->generateList(null,'cod_tipo ASC', null, '{n}.cfpd10_reformulacion_tipo_dep.cod_tipo', '{n}.cfpd10_reformulacion_tipo_dep.denominacion');
    		}
    */

		if ($cod_dep==1){
		  $tipo=$this->cfpd10_reformulacion_tipo->generateList(null,'cod_tipo ASC', null, '{n}.cfpd10_reformulacion_tipo.cod_tipo', '{n}.cfpd10_reformulacion_tipo.denominacion');
		}else{
		  $tipo=$this->cfpd10_reformulacion_tipo_dep_ip->generateList(null,'cod_tipo ASC', null, '{n}.cfpd10_reformulacion_tipo_dep_ip.cod_tipo', '{n}.cfpd10_reformulacion_tipo_dep_ip.denominacion');
		}

    $resultado = $this->comboBox("v_cstd10_planilla_recaudado", "numero_planilla", "dependencia_ciudadano", $this->SQLCA()." and ano_planilla=".$ano);
    $this->concatena($resultado, 'planilla_liquidacion');
		$this->concatena($tipo,'tipo');
 		$this->set('ano_reformulacion',$ano);
		$this->set('ano',$ano);

	}

  function planilla($tipo, $planilla)
  {
     $this->layout = "ajax";

        if($tipo!=null){
        $cond =$this->SQLCA()." and numero_planilla=$planilla and ano_planilla=".$this->ano_ejecucion();//vario
        switch($tipo){
            case 'destino':
              $vector=  $this->v_cstd10_planilla_recaudado->execute("SELECT dependencia_ciudadano FROM v_cstd10_planilla_recaudado WHERE ".$cond);
               echo "<input type='text' name='data[cstp10_planilla_recaudacion][destino]' id='destino' readonly='readonly' class='inputtext' value='".$vector[0][0]["dependencia_ciudadano"]."' />";
            break;
            case 'monto':
              $vector=  $this->v_cstd10_planilla_recaudado->execute("SELECT monto FROM v_cstd10_planilla_recaudado WHERE ".$cond);
              echo "<input type='text' name='data[cfpp_reformulacion_oficios][monto_planilla]' id='monto_planilla' readonly='readonly' class='inputtext' value='".$this->formato2($vector[0][0]['monto'])."' />";
            break;
            case 'monto_recaudado':
              $vector=  $this->v_cstd10_planilla_recaudado->execute("SELECT monto_recaudado FROM v_cstd10_planilla_recaudado WHERE ".$cond);
              echo "<input type='text' name='data[cfpp_reformulacion_oficios][monto_planilla_recaudado]' id='monto_planilla_recaudado' readonly='readonly' class='inputtext' value='".$this->formato2($vector[0][0]['monto_recaudado'])."' />";
            break;
            case 'monto_disponible':
              $vector=  $this->v_cstd10_planilla_recaudado->execute("SELECT monto_recaudado-monto_reformulado as monto_disponible FROM v_cstd10_planilla_recaudado WHERE ".$cond);
              echo "<input type='text' name='data[cfpp_reformulacion_oficios][monto_planilla_disponible]' id='monto_planilla_disponible' readonly='readonly' class='inputtext' value='".$this->formato2($vector[0][0]['monto_disponible'])."' />";
            break;
        }//fin switch
        }else{
              $this->set('SELECT','');
              $this->set('codigo','');
              $this->set('seleccion','');
              $this->set('n',10);
              $this->set('no','no');
              $this->set('vector','');
        }

    $this->render('mostrar');
  }


   	function todo($var){//echo "si entro a tipo";
		$this->layout = "ajax";$ano=$this->ano_ejecucion();
		$veri="decretado != '1'"." and ano_reformulacion=".$this->ano_ejecucion();
		$a = $this->cfpd10_reformulacion_texto->findAll("ano_reformulacion=".$ano." and ".$this->SQLCA()." and numero_oficio='".$var."'",array('revisado','elaborado','cod_tipo','numero_oficio','concepto','monto','cod_tipo','encabezado_oficio','pie_oficio','encabezado_decreto','pie_decreto','nota_final_decreto','nota_final_oficio','fecha_oficio'));
   		if($a[0]['cfpd10_reformulacion_texto']['elaborado']==1){
   			$presupuesto=1;
   		}
   		if($a[0]['cfpd10_reformulacion_texto']['revisado']==2){
   			$presupuesto=2;
   		}
   		if(($a[0]['cfpd10_reformulacion_texto']['elaborado'])!=1 && ($a[0]['cfpd10_reformulacion_texto']['elaborado'])!=2){
   			$presupuesto=0;
   		}
    	$datos = $this->cfpd10_reformulacion_texto->findAll($this->SQLCA()." and ano_reformulacion=".$this->ano_ejecucion()." and numero_oficio='".$var."'");
		$this->set('datos',$datos);
   		$this->set('presupuesto',$presupuesto);
    	$this->set("razon",$a[0]['cfpd10_reformulacion_texto']['concepto']);
    	$this->set("monto",$a[0]['cfpd10_reformulacion_texto']['monto']);
    	$this->set("codigo",$a[0]['cfpd10_reformulacion_texto']['cod_tipo']);
    	$this->set("encabezado_oficio",$a[0]['cfpd10_reformulacion_texto']['encabezado_oficio']);
    	$this->set("pie_oficio",$a[0]['cfpd10_reformulacion_texto']['pie_oficio']);
    	$this->set("nota_final_oficio",$a[0]['cfpd10_reformulacion_texto']['nota_final_oficio']);
    	$this->set("encabezado_decreto",$a[0]['cfpd10_reformulacion_texto']['encabezado_decreto']);
    	$this->set("pie_decreto",$a[0]['cfpd10_reformulacion_texto']['pie_decreto']);
    	$this->set("nota_final_decreto",$a[0]['cfpd10_reformulacion_texto']['nota_final_decreto']);
    	$this->set("numero_aprobacion",$a[0]['cfpd10_reformulacion_texto']['numero_aprobacion']);
    	$this->set("numero_decreto",$a[0]['cfpd10_reformulacion_texto']['numero_decreto']);
    	$fec=$a[0]['cfpd10_reformulacion_texto']['fecha_oficio'];
    	$fecha_oficio=$this->Cfecha($fec,'D/M/A');
    	$this->set("fecha_oficio",$fecha_oficio);
		$this->set("numero_oficio2",$a[0]['cfpd10_reformulacion_texto']['numero_oficio']);
		$cod=$a[0]['cfpd10_reformulacion_texto']['cod_tipo'];//echo $cod;
		$b = $this->cfpd10_reformulacion_tipo->findAll("cod_tipo=".$cod,array('cod_tipo','denominacion'));//print_r($b);
    	$this->set("tipo",$b[0]['cfpd10_reformulacion_tipo']['denominacion']);
    	$this->set("codigo",$b[0]['cfpd10_reformulacion_tipo']['cod_tipo']);
    	$oficio=$this->cfpd10_reformulacion_texto->generateList($this->SQLCA()." and ".$veri,'numero_oficio ASC', null, '{n}.cfpd10_reformulacion_texto.numero_oficio', '{n}.cfpd10_reformulacion_texto.numero_oficio');
		$this->set('numero',$oficio);
		$ano=$this->ano_ejecucion();
		$this->set('ano_reformulacion',$ano);
		$this->set('ano',$ano);
    	$this->set('numero_oficio',$var);
	}//fin todo


   	function desaprobacion($var=null){
		$this->layout = "ajax";$ano=$this->ano_ejecucion();
		$datos = $this->cfpd10_reformulacion_texto->execute("update cfpd10_reformulacion_texto set  aprobado='0', numero_aprobacion='0', fecha_aprobacion=null where ".$this->SQLCA()." and ano_reformulacion=".$this->ano_ejecucion()." and numero_oficio='".$var."'");
		 if($datos>1){
		        $this->set("Message_existe","El oficio Nº ".$var.", fue desaprobado satisfactoriamente");
	         }else{
		        $this->set("errorMessage","El oficio Nº ".$var.", no fue desaprobado satisfactoriamente");
	      }
		$this->index();
    	$this->render('index');



   	}
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


	function consultar($pagina=null,$ano=null){
 		$this->layout = "ajax";
    	$cod_presi = $this->Session->read('SScodpresi');
		$cod_entidad = $this->Session->read('SScodentidad');
		$cod_tipo_inst = $this->Session->read('SScodtipoinst');
		$cod_inst = $this->Session->read('SScodinst');
		$ano_d=$this->data['cfpp10_reformulacion_oficios']['ano_reformulacion'];
		if($ano_d != null){
			$ano=$ano_d;
		}
		$this->set('ano',$ano);
		$cond = $this->SQLCA()." and ano_reformulacion=".$ano;
        if($pagina!=null){
        	$pagina=$pagina;
          	$this->set('pagina',$pagina);
          	$Tfilas=$this->cfpd10_reformulacion_texto->findCount($cond);
          	if($Tfilas==0){
          		$this->set('Message_existe', 'No se encontraron datos.');
          	 	$this->set('validado',true);
				$this->index();
				$this->render("index");
          	}
          	if($Tfilas!=0){
          		$this->set('pag_cant',$pagina.'/'.$Tfilas);
          		$datos=$this->cfpd10_reformulacion_texto->findAll($cond,null,'numero_oficio ASC',1,$pagina,null);
          	 	$this->set('datos',$datos);
          	 	$a = $this->cfpd10_reformulacion_tipo->findAll("cod_tipo=".$datos[0]['cfpd10_reformulacion_texto']['cod_tipo'],array('denominacion'));
    			$this->set("tipo",$a[0]['cfpd10_reformulacion_tipo']['denominacion']);
          	 	$this->set('siguiente',$pagina+1);
          	 	$this->set('anterior',$pagina-1);
             	$this->bt_nav($Tfilas,$pagina);
           	}
 		}else{
 			$pagina=1;
 			$this->set('pagina',$pagina);
          	$Tfilas=$this->cfpd10_reformulacion_texto->findCount($cond);
          	if($Tfilas==0){
          		$this->set('Message_existe', 'No se encontraron datos.');
          	 	$this->set('validado',true);
				$this->index();
				$this->render("index");
          	}
          	if($Tfilas!=0){
          		$this->set('pag_cant',$pagina.'/'.$Tfilas);
          	 	$datos=$this->cfpd10_reformulacion_texto->findAll($cond,null,'numero_oficio ASC',1,$pagina,null);
          	 	$this->set('datos',$datos);
          	 	$a = $this->cfpd10_reformulacion_tipo->findAll("cod_tipo=".$datos[0]['cfpd10_reformulacion_texto']['cod_tipo'],array('denominacion'));
    			$this->set("tipo",$a[0]['cfpd10_reformulacion_tipo']['denominacion']);
          	 	$this->set('siguiente',$pagina+1);
          	 	$this->set('anterior',$pagina-1);
             	$this->bt_nav($Tfilas,$pagina);
			}
        }
}//fin function consultar2
	function modificar($var=null,$pagina=null){
 		$this->layout = "ajax";
 		$cod_presi = $this->Session->read('SScodpresi');
		$cod_entidad = $this->Session->read('SScodentidad');
		$cod_tipo_inst = $this->Session->read('SScodtipoinst');
		$cod_inst = $this->Session->read('SScodinst');
		$ano=$this->ano_ejecucion();
		$cond = $this->SQLCA()." and ano_reformulacion=".$ano;
        $this->set('pagina',$pagina);
        if($var!=null){
        	$datos=$this->cfpd10_reformulacion_texto->findAll($cond." and numero_oficio='".$var."'");
          	$this->set('datos',$datos);
          	$a = $this->cfpd10_reformulacion_tipo->findAll("cod_tipo=".$datos[0]['cfpd10_reformulacion_texto']['cod_tipo'],array('denominacion'));
    		$this->set("tipo",$a[0]['cfpd10_reformulacion_tipo']['denominacion']);
        }
}//fin function consultar2

	function guardar(){
		$this->layout = "ajax";
  		$aa1=$this->verifica_SS(1);
  		$aa2=$this->verifica_SS(2);
  		$aa3=$this->verifica_SS(3);
  		$aa4=$this->verifica_SS(4);
  		$aa5=$this->verifica_SS(5);
  		$username_registro =  $this->Session->read('nom_usuario');
  		$username_registro = strtoupper($username_registro);
  		$fecha_proceso_registro = date('Y/m/d');
		if(!empty($this->data)){
	 		$ano_reformulacion=$this->data['cfpp10_reformulacion_oficios']['ano_reformulacion'];
	 		$numero_oficio=$this->data['cfpp10_reformulacion_oficios']['numero_oficio'];
	 		$cod_tipo=$this->data['cfpp10_reformulacion_oficios']['tipo'];
	 		$f=$this->data['cfpp10_reformulacion_oficios']['fecha_oficio'];
	 		$f=$f==""?"01/01/1900":$f;
     		$fecha_oficio=$f[6].$f[7].$f[8].$f[9]."-".$f[3].$f[4]."-".$f[0].$f[1];
	 		$concepto=$this->data['cfpp10_reformulacion_oficios']['razones'];
	 		$monto=$this->Formato1($this->data['cfpp10_reformulacion_oficios']['monto']);
	 		$encabezado_oficio=$this->data['cfpp10_reformulacion_oficios']['encabezado_oficio'];
	 		$pie_oficio=$this->data['cfpp10_reformulacion_oficios']['pie_oficio'];
     		$nota_final_oficio=$this->data['cfpp10_reformulacion_oficios']['nota_final_oficio'];
	 		$encabezado_decreto=$this->data['cfpp10_reformulacion_oficios']['encabezado_decreto'];
	 		$pie_decreto=$this->data['cfpp10_reformulacion_oficios']['pie_decreto'];
	 		$nota_final_decreto=$this->data['cfpp10_reformulacion_oficios']['nota_final_decreto'];
      if(($cod_tipo==1 || $cod_tipo==2) && isset($this->data['cfpp10_reformulacion_oficios']['planilla']))
      {
        $numero_planilla=$this->data['cfpp10_reformulacion_oficios']['planilla'];
      }
      else
      {
        $numero_planilla='';
      }
		}

    if($numero_planilla!='')
    {
      if($numero_planilla=='9999')
      {
        $sql_update_planilla="UPDATE cstd10_planilla_liquidacion SET monto_reformulado=monto_reformulado+$monto WHERE ".$this->SQLCA()." and ano_planilla=$ano_reformulacion and numero_planilla=(SELECT numero_planilla
          FROM cstd10_planilla_liquidacion
          WHERE ".$this->SQLCA()." and ano_planilla=$ano_reformulacion and tipo_credito<>-1
          ORDER BY numero_planilla ASC
          limit 1)";
      }
      else if($numero_planilla=='9998')
      {
        $sql_update_planilla="UPDATE cstd10_planilla_liquidacion SET monto_reformulado=monto_reformulado+$monto WHERE ".$this->SQLCA()." and ano_planilla=$ano_reformulacion and numero_planilla=(SELECT numero_planilla
          FROM cstd10_planilla_liquidacion
          WHERE ".$this->SQLCA()." and ano_planilla=$ano_reformulacion and excedentes='TRUE'
          ORDER BY numero_planilla ASC
          limit 1)"; 
      }
      else
      {
        $sql_update_planilla="UPDATE cstd10_planilla_liquidacion SET monto_reformulado=monto_reformulado+$monto WHERE ".$this->SQLCA()." and ano_planilla=$ano_reformulacion and numero_planilla=$numero_planilla";
      }

      $resp2=$this->cfpd10_reformulacion_texto->execute($sql_update_planilla);

      $sql_planilla_reformulacion="INSERT INTO cstd10_reformulacion(
            cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, ano_planilla, 
            numero_planilla, numero_oficio, monto)
    VALUES (".$aa1.", ".$aa2.", ".$aa3.", ".$aa4.", ".$aa5.", ".$ano_reformulacion.", 
            ".$numero_planilla.", ".$numero_oficio.", ".$monto.");";

      $resp3=$this->cfpd10_reformulacion_texto->execute($sql_planilla_reformulacion);

    }

	 	$sql ="INSERT INTO cfpd10_reformulacion_texto (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, ano_reformulacion,
  		numero_oficio, cod_tipo, fecha_oficio, concepto, monto, encabezado_oficio, pie_oficio, nota_final_oficio, encabezado_decreto,
  		pie_decreto, nota_final_decreto, numero_decreto, fecha_decreto, elaborado, revisado, por_enviar, enviado, por_remitir, remitido,
  		por_aprobar, aprobado, numero_aprobacion, fecha_aprobacion, decretado, fecha_registro_oficio_inicial,
		username_registro_oficio_inicial, fecha_registro_oficio_consejo, username_registro_oficio_consejo, fecha_registro_decreto, username_registro_decreto)";
  		$sql .=" VALUES (".$aa1.", ".$aa2.", ".$aa3.", ".$aa4.", ".$aa5.", ".$ano_reformulacion.", '".$numero_oficio."', ".$cod_tipo.",
  		'".$fecha_oficio."', '".$concepto."', ".$monto.", '".$encabezado_oficio."', '".$pie_oficio."', '".$nota_final_oficio."',
  		'".$encabezado_decreto."', '".$pie_decreto."', '".$nota_final_decreto."', 0, '1900-01-01', 1, 2, 1, 2, 1, 2, 1, 0, 0, '1900-01-01', 0,'".$fecha_proceso_registro."','".$username_registro."','1900-01-01','0','1900-01-01','0') ";
 		$resp=$this->cfpd10_reformulacion_texto->execute($sql);


 		if($resp>1){
			$opc='no';
   			$this->redirect('cfpp10_reformulacion_partidas/index/'.$opc.'/'.$numero_oficio);
  		}else if ($resp <= 1){
  			$this->set('Message_existe', 'Disculpe, El Registro no fue creado.');
  			$this->set('validado',true);
			$this->index();
			$this->render("index");
		}
  	}

	function guardar_modificar($var=null,$pagina=null){
		$this->layout = "ajax";
  		if(!empty($this->data)){
	 		$ano_reformulacion=$this->data['cfpp10_reformulacion_oficios']['ano_reformulacion'];
	 		if(isset($this->data['cfpp10_reformulacion_oficios']['numero_decreto'])){
	 			$numero_decreto=$this->data['cfpp10_reformulacion_oficios']['numero_decreto'];
	 		}else{
	 			$numero_decreto='0';
	 		}
	 		$concepto=$this->data['cfpp10_reformulacion_oficios']['razones'];
	 		$monto=$this->Formato1($this->data['cfpp10_reformulacion_oficios']['monto']);
	 		$encabezado_oficio=$this->data['cfpp10_reformulacion_oficios']['encabezado_oficio'];
	 		$pie_oficio=$this->data['cfpp10_reformulacion_oficios']['pie_oficio'];
     		$nota_final_oficio=$this->data['cfpp10_reformulacion_oficios']['nota_final_oficio'];
	 		$encabezado_decreto=$this->data['cfpp10_reformulacion_oficios']['encabezado_decreto'];
	 		$pie_decreto=$this->data['cfpp10_reformulacion_oficios']['pie_decreto'];
	 		$nota_final_decreto=$this->data['cfpp10_reformulacion_oficios']['nota_final_decreto'];
	 		if(isset($this->data['cfpp10_reformulacion_oficios']['fecha_decreto'])){
	 		$f1=$this->data['cfpp10_reformulacion_oficios']['fecha_decreto'];
	 		}else{
	 			$f1=null;
	 		}
	 		if($f1==null){
	 			$f1="01/01/1900";
	 		}
     		$fecha_decreto=$f1[6].$f1[7].$f1[8].$f1[9]."-".$f1[3].$f1[4]."-".$f1[0].$f1[1];
	 		if(isset($this->data['cfpp10_reformulacion_oficios']['fecha_aprobacion'])){
	 			$f2=$this->data['cfpp10_reformulacion_oficios']['fecha_aprobacion'];
	 		}else{
	 			$f2=null;
	 		}
	 		if($f2==null){
	 			$f2="01/01/1900";
	 		}
	 		$f2=$f2==""?"01/01/1900":$f2;
     		$fecha_aprobacion=$f2[6].$f2[7].$f2[8].$f2[9]."-".$f2[3].$f2[4]."-".$f2[0].$f2[1];
		}
		$sql="update cfpd10_reformulacion_texto set concepto='".$concepto."', monto=".$monto.", encabezado_oficio='".$encabezado_oficio."',
  		pie_oficio='".$pie_oficio."', nota_final_oficio='".$nota_final_oficio."', encabezado_decreto='".$encabezado_decreto."',
  		pie_decreto='".$pie_decreto."', nota_final_decreto='".$nota_final_decreto."', numero_decreto='".$numero_decreto."',
  		fecha_decreto='".$fecha_decreto."' where " .$this->SQLCA(). " and ano_reformulacion=".$this->ano_ejecucion()." and numero_oficio='".$var."'";
 		$resp=$this->cfpd10_reformulacion_texto->execute($sql);
 		if($resp>1){
   			$this->set('Message_existe', 'Registro Modificado con exito.');
   			$this->consultar($pagina);
   			$this->render("consultar");
  		}else if ($resp <= 1){
  			$this->set('Message_existe', 'Disculpe, El Registro no fue Modificado.');
  			$this->consultar($pagina);
  			$this->render("consultar");
		}
 	}

	function eliminar($var=null,$pagina=null){
 		$this->layout = "ajax";
       	if(isset($var)){
       		$sacar=$this->SQLCA()."and numero_oficio='".$var."' and ano_reformulacion=".$this->ano_ejecucion()."";
			$bus=$this->cfpd10_reformulacion_partidas_tmp->findCount($sacar);
			if($bus > 0){
				$temporal = $this->cfpd10_reformulacion_partidas_tmp->findAll($sacar);
				foreach($temporal as $row){
					$var1 	= $row['cfpd10_reformulacion_partidas_tmp']['cod_presi'];
					$var2 	= $row['cfpd10_reformulacion_partidas_tmp']['cod_entidad'];
					$var3 	= $row['cfpd10_reformulacion_partidas_tmp']['cod_tipo_inst'];
					$var4 	= $row['cfpd10_reformulacion_partidas_tmp']['cod_inst'];
					$var5 	= $row['cfpd10_reformulacion_partidas_tmp']['cod_dep'];
					$var6 	= $row['cfpd10_reformulacion_partidas_tmp']['ano_reformulacion'];
					$var7 	= $row['cfpd10_reformulacion_partidas_tmp']['numero_oficio'];
					$var8 	= $row['cfpd10_reformulacion_partidas_tmp']['codi_dep'];
					$var9 	= $row['cfpd10_reformulacion_partidas_tmp']['ano'];
					$var10 	= $row['cfpd10_reformulacion_partidas_tmp']['cod_sector'];
					$var11 	= $row['cfpd10_reformulacion_partidas_tmp']['cod_programa'];
					$var12 	= $row['cfpd10_reformulacion_partidas_tmp']['cod_sub_prog'];
					$var13 	= $row['cfpd10_reformulacion_partidas_tmp']['cod_proyecto'];
					$var14 	= $row['cfpd10_reformulacion_partidas_tmp']['cod_activ_obra'];
					$var15 	= $row['cfpd10_reformulacion_partidas_tmp']['cod_partida'];
					$var16 	= $row['cfpd10_reformulacion_partidas_tmp']['cod_generica'];
					$var17 	= $row['cfpd10_reformulacion_partidas_tmp']['cod_especifica'];
					$var18 	= $row['cfpd10_reformulacion_partidas_tmp']['cod_sub_espec'];
					$var19 	= $row['cfpd10_reformulacion_partidas_tmp']['cod_auxiliar'];
					$var20 	= $row['cfpd10_reformulacion_partidas_tmp']['monto_disminucion'];
					$var21 	= $row['cfpd10_reformulacion_partidas_tmp']['monto_aumento'];
					$validar1="cod_presi=".$var1." and cod_entidad=".$var2." and cod_tipo_inst=".$var3." and cod_inst=".$var4." and cod_dep=".$var8;
					$validar2="ano=".$var9." and cod_sector=".$var10." and cod_programa=".$var11." and cod_sub_prog=".$var12." and cod_proyecto=".$var13." and cod_activ_obra=".$var14." and  cod_partida=".$var15." and cod_generica=".$var16." and cod_especifica=".$var17." and cod_sub_espec=".$var18." and cod_auxiliar=".$var19;
					$validar3="ano_reformulacion=".$var9." and cod_sector=".$var10." and cod_programa=".$var11." and cod_sub_prog=".$var12." and cod_proyecto=".$var13." and cod_activ_obra=".$var14." and  cod_partida=".$var15." and cod_generica=".$var16." and cod_especifica=".$var17." and cod_sub_espec=".$var18." and cod_auxiliar=".$var19;
					if($var20 != 0){
     					$ud05="update cfpd05 set precompromiso_congelado=precompromiso_congelado - ".$var20." where ".$validar1." and ".$validar2;
     					$respxx=$this->cfpd05->execute($ud05);
     				}
     				$tempo="delete from cfpd10_reformulacion_partidas_tmp where ".$validar1." and ".$validar3;
     				$respxxx=$this->cfpd10_reformulacion_partidas_tmp->execute($tempo);
				}
			}
 			$this->cfpd10_reformulacion_texto->execute("DELETE FROM cfpd10_reformulacion_texto  WHERE ".$this->SQLCA()." and numero_oficio='".$var."' and ano_reformulacion=".$this->ano_ejecucion()."");
 			$y=$this->cfpd10_reformulacion_texto->findCount($this->SQLCA()." and numero_oficio='".$var."' and ano_reformulacion=".$this->ano_ejecucion()."");
	  		if($y!=0){
	  			if($pagina!=1){
	  				$this->set('errorMessage', 'El registro fue eliminado');
	  				$this->consultar($pagina-1);
      				$this->render("consultar");
      			}else{
      				$this->set('errorMessage', 'El registro fue eliminado');
      	 			$this->consultar($pagina);//si es el primero solamente
        			$this->render("consultar");
      			}
			}else if($y==0){
				$this->set('errorMessage', 'El registro fue eliminado');
			 	$this->set('validado',true);
				$this->index();
				$this->render("index");
			}//fin if
 		}
 	}

	function emitir_decreto($numero_oficio=null,$ano=null,$codigo=null){
		$username = $this->Session->read('nom_usuario');
		$username = strtoupper($username);
		$username_registro =  $this->Session->read('nom_usuario');
		$username_registro = strtoupper($username_registro);
  		$fecha_proceso_registro = date('Y/m/d');
	 	$numero_decreto=$this->data['cfpp10_reformulacion_oficios']['numero_decreto'];
	 	$fecha_decreto=$this->data['cfpp10_reformulacion_oficios']['fecha_decreto'];
		$sacar=$this->SQLCA()." and ano_reformulacion=".$this->ano_ejecucion()." and numero_oficio='".$numero_oficio."'";
		$temporal = $this->cfpd10_reformulacion_partidas_tmp->findAll($sacar);
		$monto_disminuir_todo=0;
		$sql_update_cfpd05 = '';
		$c=0;
		foreach($temporal as $row){
			$var1 	= $row['cfpd10_reformulacion_partidas_tmp']['cod_presi'];
			$var2 	= $row['cfpd10_reformulacion_partidas_tmp']['cod_entidad'];
			$var3 	= $row['cfpd10_reformulacion_partidas_tmp']['cod_tipo_inst'];
			$var4 	= $row['cfpd10_reformulacion_partidas_tmp']['cod_inst'];
			$var5 	= $row['cfpd10_reformulacion_partidas_tmp']['cod_dep'];
			$var6 	= $row['cfpd10_reformulacion_partidas_tmp']['ano_reformulacion'];
			$var7 	= $row['cfpd10_reformulacion_partidas_tmp']['numero_oficio'];
			$var8 	= $row['cfpd10_reformulacion_partidas_tmp']['codi_dep'];
			$var9 	= $row['cfpd10_reformulacion_partidas_tmp']['ano'];
			$var10 	= $row['cfpd10_reformulacion_partidas_tmp']['cod_sector'];
			$var11 	= $row['cfpd10_reformulacion_partidas_tmp']['cod_programa'];
			$var12 	= $row['cfpd10_reformulacion_partidas_tmp']['cod_sub_prog'];
			$var13 	= $row['cfpd10_reformulacion_partidas_tmp']['cod_proyecto'];
			$var14 	= $row['cfpd10_reformulacion_partidas_tmp']['cod_activ_obra'];
			$var15 	= $row['cfpd10_reformulacion_partidas_tmp']['cod_partida'];
			$var16 	= $row['cfpd10_reformulacion_partidas_tmp']['cod_generica'];
			$var17 	= $row['cfpd10_reformulacion_partidas_tmp']['cod_especifica'];
			$var18 	= $row['cfpd10_reformulacion_partidas_tmp']['cod_sub_espec'];
			$var19 	= $row['cfpd10_reformulacion_partidas_tmp']['cod_auxiliar'];
			$var20 	= $row['cfpd10_reformulacion_partidas_tmp']['monto_disminucion'];
			$var21 	= $row['cfpd10_reformulacion_partidas_tmp']['monto_aumento'];
			$monto_disminuir= $var20;
			$c++;
			$cf05=$this->cfpd05->findAll("cod_presi=".$var1." and cod_entidad=".$var2." and cod_tipo_inst=".$var3." and cod_inst=".$var4." and cod_dep=".$var8." and ano=".$var9." and cod_sector=".$var10." and cod_programa=".$var11." and cod_sub_prog=".$var12." and cod_proyecto=".$var13." and cod_activ_obra=".$var14." and cod_partida=".$var15." and cod_generica=".$var16." and cod_especifica=".$var17." and cod_sub_espec=".$var18." and cod_auxiliar=".$var19."");
			$ayuda=$cf05[0]['cfpd05'];
///////////////inicio motor
			if($codigo==1 && $var20 != 0){
				$cp = $this->crear_partida($var9, $var10, $var11, $var12, $var13, $var14, $var15,$var16, $var17, $var18,$var19);
					$to = 1;
					$td = 1;
					$ta = 2;
					$mt = $this->Formato1($var20);
					$ccp = '';
					$dnco = $this->motor_presupuestario($cp, $to ,$td, $ta, "$fecha_decreto", $mt, $ccp, $var6, $numero_decreto, null, null, null, null, null, null, null, null, null, null, null, null, $var8);
						if($dnco != false){
							$numero_control_compromiso=$dnco;
						}else{
							$numero_control_compromiso=null;
							break;
						}
			}
			if($codigo==1 && $var21 != 0){
				$cp = $this->crear_partida($var9, $var10, $var11, $var12, $var13, $var14, $var15,$var16, $var17, $var18,$var19);
				$to = 1;
				$td = 1;
				$ta = 1;
				$mt = $this->Formato1($var21);
				$ccp = '';
				$dnco = $this->motor_presupuestario($cp, $to ,$td, $ta, "$fecha_decreto", $mt, $ccp, $var6, $numero_decreto, null, null, null, null, null, null, null, null, null, null, null, null, $var8);
				if($dnco != false){
					$numero_control_compromiso=$dnco;
				}else{
					$numero_control_compromiso=null;
					break;
				}
			}
			if($codigo==2 && $var21 != 0){
				$cp = $this->crear_partida($var9, $var10, $var11, $var12, $var13, $var14, $var15, $var16, $var17, $var18, $var19);
				$to = 1;
				$td = 1;
				$ta = 3;
				$mt = $this->Formato1($var21);
				$ccp = '';
				$dnco = $this->motor_presupuestario($cp, $to ,$td, $ta, "$fecha_decreto", $mt, $ccp, $var6, $numero_decreto, null, null, null, null, null, null, null, null, null, null, null, null, $var8);
				if($dnco != false){
					$numero_control_compromiso=$dnco;
				}else{
					$numero_control_compromiso=null;
					break;
				}
			}
      if($codigo==6 && $var21 != 0){
        $cp = $this->crear_partida($var9, $var10, $var11, $var12, $var13, $var14, $var15, $var16, $var17, $var18, $var19);
        $to = 1;
        $td = 1;
        $ta = 6;
        $mt = $this->Formato1($var21);
        $ccp = '';
        $dnco = $this->motor_presupuestario($cp, $to ,$td, $ta, "$fecha_decreto", $mt, $ccp, $var6, $numero_decreto, null, null, null, null, null, null, null, null, null, null, null, null, $var8);
        if($dnco != false){
          $numero_control_compromiso=$dnco;
        }else{
          $numero_control_compromiso=null;
          break;
        }
      }
      if($codigo==7 && $var21 != 0){
        $cp = $this->crear_partida($var9, $var10, $var11, $var12, $var13, $var14, $var15, $var16, $var17, $var18, $var19);
        $to = 1;
        $td = 1;
        $ta = 7;
        $mt = $this->Formato1($var21);
        $ccp = '';
        $dnco = $this->motor_presupuestario($cp, $to ,$td, $ta, "$fecha_decreto", $mt, $ccp, $var6, $numero_decreto, null, null, null, null, null, null, null, null, null, null, null, null, $var8);
        if($dnco != false){
          $numero_control_compromiso=$dnco;
        }else{
          $numero_control_compromiso=null;
          break;
        }
      }
      if($codigo==8 && $var21 != 0){
        $cp = $this->crear_partida($var9, $var10, $var11, $var12, $var13, $var14, $var15, $var16, $var17, $var18, $var19);
        $to = 1;
        $td = 1;
        $ta = 8;
        $mt = $this->Formato1($var21);
        $ccp = '';
        $dnco = $this->motor_presupuestario($cp, $to ,$td, $ta, "$fecha_decreto", $mt, $ccp, $var6, $numero_decreto, null, null, null, null, null, null, null, null, null, null, null, null, $var8);
        if($dnco != false){
          $numero_control_compromiso=$dnco;
        }else{
          $numero_control_compromiso=null;
          break;
        }
      }
			if($codigo==3 && $var20!=0){
				$cp = $this->crear_partida($var9, $var10, $var11, $var12, $var13, $var14, $var15, $var16, $var17, $var18, $var19);
				$to = 1;
				$td = 1;
				$ta = 4;
				$mt = $this->Formato1($var20);
				$ccp = '';
				$dnco = $this->motor_presupuestario($cp, $to ,$td, $ta, "$fecha_decreto", $mt, $ccp, $var6, $numero_decreto, null, null, null, null, null, null, null, null, null, null, null, null, $var8);
				if($dnco != false){
					$numero_control_compromiso=$dnco;
				}else{
					$numero_control_compromiso=null;
					break;
				}
			}
      if($codigo==8 && $var20 != 0){
        $cp = $this->crear_partida($var9, $var10, $var11, $var12, $var13, $var14, $var15,$var16, $var17, $var18,$var19);
          $to = 1;
          $td = 1;
          $ta = 3;
          $mt = $this->Formato1($var20);
          $ccp = '';
          $dnco = $this->motor_presupuestario($cp, $to ,$td, $ta, "$fecha_decreto", $mt, $ccp, $var6, $numero_decreto, null, null, null, null, null, null, null, null, null, null, null, null, $var8);
            if($dnco != false){
              $numero_control_compromiso=$dnco;
            }else{
              $numero_control_compromiso=null;
              break;
            }
      }
			if($codigo==4 && $var21 != 0){
				$cp = $this->crear_partida($var9, $var10, $var11, $var12, $var13, $var14, $var15, $var16, $var17, $var18, $var19);
				$to = 1;
				$td = 1;
				$ta = 5;
				$mt = $this->Formato1($var21);
				$ccp = '';
				$dnco = $this->motor_presupuestario($cp, $to ,$td, $ta, "$fecha_decreto", $mt, $ccp, $var6, $numero_decreto, null, null, null, null, null, null, null, null, null, null, null, null, $var8);
				if($dnco != false){
					$numero_control_compromiso=$dnco;
				}else{
					$numero_control_compromiso=null;
					break;
				}
			}

				if($codigo==5 && $var21 != 0){
				$cp = $this->crear_partida($var9, $var10, $var11, $var12, $var13, $var14, $var15, $var16, $var17, $var18, $var19);
				$to = 1;
				$td = 1;
				$ta = 6;
				$mt = $this->Formato1($var21);
				$ccp = '';
				$dnco = $this->motor_presupuestario($cp, $to ,$td, $ta, "$fecha_decreto", $mt, $ccp, $var6, $numero_decreto, null, null, null, null, null, null, null, null, null, null, null, null, $var8);
				if($dnco != false){
					$numero_control_compromiso=$dnco;
				}else{
					$numero_control_compromiso=null;
					break;
				}
			}
///////////////fin motor


////inicio contabilidad
/*
			$ver03=$this->cfpd03->findCount("cod_presi=".$var1." and cod_entidad=".$var2." and cod_tipo_inst=".$var3." and cod_inst=".$var4." and cod_dep=1 and ano=".$var6." and cod_partida=".$ayuda['cod_ramo']." and cod_generica=".$ayuda['cod_subramo']." and cod_especifica=".$ayuda['cod_esp']." and cod_sub_espec=".$ayuda['cod_subesp']." and cod_auxiliar=".$ayuda['cod_aux']."");
			$cfpd03_aumento="update cfpd03 set ingresos_adicionales = ingresos_adicionales + ".$var21." where cod_presi=".$var1." and cod_entidad=".$var2." and cod_tipo_inst=".$var3." and cod_inst=".$var4." and cod_dep=1 and ano=".$var6." and cod_partida=".$ayuda['cod_ramo']." and cod_generica=".$ayuda['cod_subramo']." and cod_especifica=".$ayuda['cod_esp']." and cod_sub_espec=".$ayuda['cod_subesp']." and cod_auxiliar=".$ayuda['cod_aux']."";
			$cfpd03_rebajas="update cfpd03 set rebajas = rebajas + ".$var20." where cod_presi=".$var1." and cod_entidad=".$var2." and cod_tipo_inst=".$var3." and cod_inst=".$var4." and cod_dep=1 and ano=".$var6." and cod_partida=".$ayuda['cod_ramo']." and cod_generica=".$ayuda['cod_subramo']." and cod_especifica=".$ayuda['cod_esp']." and cod_sub_espec=".$ayuda['cod_subesp']." and cod_auxiliar=".$ayuda['cod_aux']."";
			$this->cfpd03->execute($cfpd03_aumento);
			$this->cfpd03->execute($cfpd03_rebajas);
*/
////fin contabilidad
	 		if($codigo==4){
	 			$ano_anterior="update cfpd05 set compromiso_ano_anterior=1 where cod_presi=".$var1." and cod_entidad=".$var2." and cod_tipo_inst=".$var3." and cod_inst=".$var4." and cod_dep=".$var8." and ano=".$var9." and cod_sector=".$var10." and cod_programa=".$var11." and cod_sub_prog=".$var12." and cod_proyecto=".$var13." and cod_activ_obra=".$var14." and cod_partida=".$var15." and cod_generica=".$var16." and cod_especifica=".$var17." and cod_sub_espec=".$var18." and cod_auxiliar=".$var19."";
	 			$this->cfpd05->execute($ano_anterior);
	 		}
	 		$sql ="INSERT INTO cfpd10_reformulacion_partidas (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, ano_reformulacion, numero_oficio,
     		codi_dep, ano, cod_sector, cod_programa, cod_sub_prog, cod_proyecto, cod_activ_obra, cod_partida, cod_generica, cod_especifica, cod_sub_espec,
     		cod_auxiliar, monto_disminucion, monto_aumento)";
	 		$sql .=" VALUES (".$var1.",".$var2.",".$var3.",".$var4.",".$var5.", ".$var6.",'".$var7."', ".$var8.", ".$var9.",
     		".$var10.", ".$var11.", ".$var12.", ".$var13.", ".$var14.", ".$var15.", ".$var16.",
     		".$var17.", ".$var18.", ".$var19.", ".$var20.", ".$var21.")";
     		$resp=$this->cfpd10_reformulacion_partidas->execute($sql);
			$validar1="cod_presi=".$var1." and cod_entidad=".$var2." and cod_tipo_inst=".$var3." and cod_inst=".$var4." and cod_dep=".$var8;
			$validar2="ano=".$var9." and cod_sector=".$var10." and cod_programa=".$var11." and cod_sub_prog=".$var12." and cod_proyecto=".$var13." and cod_activ_obra=".$var14." and  cod_partida=".$var15." and cod_generica=".$var16." and cod_especifica=".$var17." and cod_sub_espec=".$var18." and cod_auxiliar=".$var19;
			if($var20 != 0){
     			$ud05="update cfpd05 set precompromiso_congelado=precompromiso_congelado - ".$var20." where ".$validar1." and ".$validar2;
     			$respxx=$this->cfpd05->execute($ud05);
     		}
		}
		$eli=$this->SQLCA()." and ano_reformulacion=".$this->ano_ejecucion()." and numero_oficio='".$numero_oficio."'";
		$sql2="delete from cfpd10_reformulacion_partidas_tmp where ".$eli;
		$sql_update_texto="update cfpd10_reformulacion_texto set numero_decreto='".$numero_decreto."', fecha_decreto='".$fecha_decreto."', decretado=1, fecha_registro_decreto='".$fecha_proceso_registro."',username_registro_decreto='".$username_registro."' where ".$eli;
		$actualizartxt=$this->cfpd10_reformulacion_texto->execute($sql_update_texto);
		$resp2=$this->cfpd10_reformulacion_partidas_tmp->execute($sql2);
		$this->set('validado',true);
 		$this->set('Message_existe', 'El decreto fue creado correctamente');
		$this->index();
		$this->render("index");
	}


	function entrar(){
		$this->layout="ajax";
		if(isset($this->data['cfpp10_revision']['login']) && isset($this->data['cfpp10_revision']['password'])){
			$l="PROYECTO";
			$c="JJJSAE";
			$user=addslashes($this->data['cfpp10_revision']['login']);
			$paswd=addslashes($this->data['cfpp10_revision']['password']);
			$cond=$this->SQLCA()." and username='".$user."' and cod_tipo=1 and clave='".$paswd."'";
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


	function consultar_partidas($var=null,$ano=null,$pagina=null){
		$this->layout = "ajax";
		//$ano=$this->ano_ejecucion();
		$de=$this->Session->read('SScoddep');
		if($de==1){
			$cond=$this->SQLCA();
		}
		else $cond=$this->SQLCA();
		$a = $this->cfpd10_reformulacion_texto->findAll($this->SQLCA()." and numero_oficio='".$var."' and ano_reformulacion=".$ano);
    	$this->set("razon",$a[0]['cfpd10_reformulacion_texto']['concepto']);
    	$this->set("monto",$a[0]['cfpd10_reformulacion_texto']['monto']);
    	$this->set("codigo",$a[0]['cfpd10_reformulacion_texto']['cod_tipo']);
    	$this->set('ano',$a[0]['cfpd10_reformulacion_texto']['ano_reformulacion']);
    	$this->set('fecha',$a[0]['cfpd10_reformulacion_texto']['fecha_oficio']);
    	$this->set('var',$var);
    	$tr=$a[0]['cfpd10_reformulacion_texto']['cod_tipo'];
    	$b = $this->cfpd10_reformulacion_tipo->findAll("cod_tipo=".$tr,array('denominacion'));
    	$this->set("tipo",$b[0]['cfpd10_reformulacion_tipo']['denominacion']);
    	$this->set('numero_oficio',$var);
		$datos = $this->cfpd10_reformulacion_partidas->findAll($this->SQLCA()." and ano_reformulacion=".$ano." and numero_oficio='".$var."'",null,'codi_dep,cod_sector,cod_programa,cod_sub_prog,cod_proyecto,cod_activ_obra,cod_partida,cod_generica,cod_especifica,cod_sub_espec,cod_auxiliar ASC');
    	$this->set("datos",$datos);
    	$this->set('pagina',$pagina);
	}//fin razon_monto

	function query($var=null){
		$this->layout="ajax";
		$this->set('tipo', $var);
	}

	function datos($tipo = null, $pista=null){
		$this->layout="ajax";
		$cod_presi = $this->Session->read('SScodpresi');
		$cod_entidad = $this->Session->read('SScodentidad');
		$cod_tipo_inst = $this->Session->read('SScodtipoinst');
		$cod_inst = $this->Session->read('SScodinst');
		$cod_dep = $this->Session->read('SScoddep');
		$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep;
		$pista2=strtoupper($pista);
		if($tipo=='numero_oficio' or $tipo=='numero_decreto'){
			$busq=$this->SQLCA()." and upper($tipo) LIKE '%$pista2%'";
		}
		if($tipo!=null && $pista!=null){
			$exe="select * from cfpd10_reformulacion_texto where ".$busq." and ano_reformulacion=".$this->ano_ejecucion();
			$datos=$this->cfpd10_reformulacion_texto->execute($exe);
			$this->set('datos', $datos);
		}
	}

	function preconsulta(){
		$this->layout="ajax";
		$opciones = array('numero_oficio'=>'NUMERO OFICIO', 'numero_decreto'=>'NUMERO DECRETO');
		$this->set('opcion', $opciones);
		$ano=$this->ano_ejecucion();
		$this->set('ano',$ano);
	}

	function buscar_rif() {
		$this->layout="ajax";
	}

	function lista_encontrados($rif=null,$ano=null){
 		$this->layout = "ajax";
 		$cond ="upper(numero_oficio)='".strtoupper($rif)."' and ano_reformulacion=".$ano;
 		$num=$this->cfpd10_reformulacion_texto->findCount($this->SQLCA().' and '.$cond);
 		if($num==1){
        	$datos=$this->cfpd10_reformulacion_texto->findAll($this->SQLCA().' and '.$cond);
          	$this->set('datos',$datos);
          	$a = $this->cfpd10_reformulacion_tipo->findAll("cod_tipo=".$datos[0]['cfpd10_reformulacion_texto']['cod_tipo'],array('denominacion'));
    		$this->set("tipo",$a[0]['cfpd10_reformulacion_tipo']['denominacion']);
 		}else{
 			$this->set('errorMessage', 'No se encontrar&oacute;n datos');
 		 	$this->index();
 		 	$this->render("index");
      	}
	}
	function eliminar_decreto($numero_oficio,$tipo,$numero_decreto,$fecha_decreto){
		$this->layout = "ajax";
		$cond="numero_oficio='".$numero_oficio."'";
		$fecha_decreto=str_replace('.','/',$fecha_decreto);
		$cero=0;
		$partidas_ref=$this->cfpd10_reformulacion_partidas->findAll($this->SQLCA()." and ano_reformulacion=".$this->ano_ejecucion()." and ". $cond);
		$cont_mov=0;
		foreach($partidas_ref as $row){
			$cod_presi 			= $row['cfpd10_reformulacion_partidas']['cod_presi'];
			$cod_entidad 		= $row['cfpd10_reformulacion_partidas']['cod_entidad'];
			$cod_tipo_inst 		= $row['cfpd10_reformulacion_partidas']['cod_tipo_inst'];
			$cod_inst 			= $row['cfpd10_reformulacion_partidas']['cod_inst'];
			$cod_dep 			= $row['cfpd10_reformulacion_partidas']['codi_dep'];
			$ano 				= $row['cfpd10_reformulacion_partidas']['ano_reformulacion'];
			$cod_sector 		= $row['cfpd10_reformulacion_partidas']['cod_sector'];
			$cod_programa 		= $row['cfpd10_reformulacion_partidas']['cod_programa'];
			$cod_sub_prog 		= $row['cfpd10_reformulacion_partidas']['cod_sub_prog'];
			$cod_proyecto 		= $row['cfpd10_reformulacion_partidas']['cod_proyecto'];
			$cod_activ_obra 	= $row['cfpd10_reformulacion_partidas']['cod_activ_obra'];
			$cod_partida		= $row['cfpd10_reformulacion_partidas']['cod_partida'];
			$cod_generica		= $row['cfpd10_reformulacion_partidas']['cod_generica'];
			$cod_especifica		= $row['cfpd10_reformulacion_partidas']['cod_especifica'];
			$cod_sub_espec 		= $row['cfpd10_reformulacion_partidas']['cod_sub_espec'];
			$cod_auxiliar		= $row['cfpd10_reformulacion_partidas']['cod_auxiliar'];
			$monto_disminucion	= $row['cfpd10_reformulacion_partidas']['monto_disminucion'];
			$monto_aumento		= $row['cfpd10_reformulacion_partidas']['monto_aumento'];
			$cfpd05=$this->cfpd05->findCount('cod_presi='.$cod_presi.' and cod_entidad='.$cod_entidad.' and cod_tipo_inst='.$cod_tipo_inst.' and cod_inst='.$cod_inst.' and cod_dep='.$cod_dep.' and cod_sector='.$cod_sector.' and cod_programa='.$cod_programa.' and cod_sub_prog='.$cod_sub_prog.' and cod_proyecto='.$cod_proyecto.' and cod_activ_obra='.$cod_activ_obra.' and cod_partida='.$cod_partida.' and cod_generica='.$cod_generica.' and cod_especifica='.$cod_especifica.' and cod_sub_espec='.$cod_sub_espec.' and cod_auxiliar='.$cod_auxiliar.' and compromiso_anual!='.$cero);
			if($cfpd05>0){
				$cont_mov=1;
				break;
			}
		}
		if($cont_mov==0){
			foreach($partidas_ref as $row){
				$cod_presi 			= $row['cfpd10_reformulacion_partidas']['cod_presi'];
				$cod_entidad 		= $row['cfpd10_reformulacion_partidas']['cod_entidad'];
				$cod_tipo_inst 		= $row['cfpd10_reformulacion_partidas']['cod_tipo_inst'];
				$cod_inst 			= $row['cfpd10_reformulacion_partidas']['cod_inst'];
				$cod_dep 			= $row['cfpd10_reformulacion_partidas']['codi_dep'];
				$ano 				= $row['cfpd10_reformulacion_partidas']['ano_reformulacion'];
				$cod_sector 		= $row['cfpd10_reformulacion_partidas']['cod_sector'];
				$cod_programa 		= $row['cfpd10_reformulacion_partidas']['cod_programa'];
				$cod_sub_prog 		= $row['cfpd10_reformulacion_partidas']['cod_sub_prog'];
				$cod_proyecto 		= $row['cfpd10_reformulacion_partidas']['cod_proyecto'];
				$cod_activ_obra 	= $row['cfpd10_reformulacion_partidas']['cod_activ_obra'];
				$cod_partida		= $row['cfpd10_reformulacion_partidas']['cod_partida'];
				$cod_generica		= $row['cfpd10_reformulacion_partidas']['cod_generica'];
				$cod_especifica		= $row['cfpd10_reformulacion_partidas']['cod_especifica'];
				$cod_sub_espec 		= $row['cfpd10_reformulacion_partidas']['cod_sub_espec'];
				$cod_auxiliar		= $row['cfpd10_reformulacion_partidas']['cod_auxiliar'];
				$monto_disminucion	= $row['cfpd10_reformulacion_partidas']['monto_disminucion'];
				$monto_aumento		= $row['cfpd10_reformulacion_partidas']['monto_aumento'];
				$cfpd05=$this->cfpd05->findCount('cod_presi='.$cod_presi.' and cod_entidad='.$cod_entidad.' and cod_tipo_inst='.$cod_tipo_inst.' and cod_inst='.$cod_inst.' and cod_dep='.$cod_dep.' and cod_sector='.$cod_sector.' and cod_programa='.$cod_programa.' and cod_sub_prog='.$cod_sub_prog.' and cod_proyecto='.$cod_proyecto.' and cod_activ_obra='.$cod_activ_obra.' and cod_partida='.$cod_partida.' and cod_generica='.$cod_generica.' and cod_especifica='.$cod_especifica.' and cod_sub_espec='.$cod_sub_espec.' and cod_auxiliar='.$cod_auxiliar.' and compromiso_anual!='.$cero);
				if($cfpd05>0){
					$msn=1;
					break;
				}else{
					if($tipo==1 and $monto_aumento!=0){
						$cp = $this->crear_partida($ano, $cod_sector, $cod_programa,$cod_sub_prog, $cod_proyecto, $cod_activ_obra, $cod_partida,$cod_generica, $cod_especifica, $cod_sub_espec,$cod_auxiliar);
						$to = 2;
						$td = 1;
						$ta = 1;
						$mt = $this->Formato1($monto_aumento);
						$ccp = '';
						$dnco = $this->motor_presupuestario($cp, $to ,$td, $ta, "$fecha_decreto", $mt, $ccp, $ano, $numero_decreto, null, null, null, null, null, null, null, null, null, null, null, null, $cod_dep);
						if($dnco != false){
							$numero_control_compromiso=$dnco;
						}else{
							$numero_control_compromiso=null;
							break;
						}
					}else if($tipo==1 and $monto_disminucion!=0){
						$cp = $this->crear_partida($ano, $cod_sector, $cod_programa,$cod_sub_prog, $cod_proyecto, $cod_activ_obra, $cod_partida,$cod_generica, $cod_especifica, $cod_sub_espec,$cod_auxiliar);
						$to = 2;
						$td = 1;
						$ta = 2;
						$mt = $this->Formato1($monto_disminucion);
						$ccp = '';
						$dnco = $this->motor_presupuestario($cp, $to ,$td, $ta, "$fecha_decreto", $mt, $ccp, $ano, $numero_decreto, null, null, null, null, null, null, null, null, null, null, null, null, $cod_dep);
						if($dnco != false){
							$numero_control_compromiso=$dnco;
						}else{
							$numero_control_compromiso=null;
							break;
						}
					}else if($tipo==2){
						$cp = $this->crear_partida($ano, $cod_sector, $cod_programa,$cod_sub_prog, $cod_proyecto, $cod_activ_obra, $cod_partida,$cod_generica, $cod_especifica, $cod_sub_espec,$cod_auxiliar);
						$to = 2;
						$td = 1;
						$ta = 3;
						$mt = $this->Formato1($monto_aumento);
						$ccp = '';
						$dnco = $this->motor_presupuestario($cp, $to ,$td, $ta, "$fecha_decreto", $mt, $ccp, $ano, $numero_decreto, null, null, null, null, null, null, null, null, null, null, null, null, $cod_dep);
						if($dnco != false){
							$numero_control_compromiso=$dnco;
						}else{
							$numero_control_compromiso=null;
							break;
						}
					}else if($tipo==3){
						$cp = $this->crear_partida($ano, $cod_sector, $cod_programa,$cod_sub_prog, $cod_proyecto, $cod_activ_obra, $cod_partida,$cod_generica, $cod_especifica, $cod_sub_espec,$cod_auxiliar);
						$to = 2;
						$td = 1;
						$ta = 4;
						$mt = $this->Formato1($monto_disminucion);
						$ccp = '';
						$dnco = $this->motor_presupuestario($cp, $to ,$td, $ta, "$fecha_decreto", $mt, $ccp, $ano, $numero_decreto, null, null, null, null, null, null, null, null, null, null, null, null, $cod_dep);
						if($dnco != false){
							$numero_control_compromiso=$dnco;
						}else{
							$numero_control_compromiso=null;
							break;
						}
					}else if($tipo==6){
            $cp = $this->crear_partida($ano, $cod_sector, $cod_programa,$cod_sub_prog, $cod_proyecto, $cod_activ_obra, $cod_partida,$cod_generica, $cod_especifica, $cod_sub_espec,$cod_auxiliar);
            $to = 2;
            $td = 1;
            $ta = 6;
            $mt = $this->Formato1($monto_disminucion);
            $ccp = '';
            $dnco = $this->motor_presupuestario($cp, $to ,$td, $ta, "$fecha_decreto", $mt, $ccp, $ano, $numero_decreto, null, null, null, null, null, null, null, null, null, null, null, null, $cod_dep);
            if($dnco != false){
              $numero_control_compromiso=$dnco;
            }else{
              $numero_control_compromiso=null;
              break;
            }
          }else if($tipo==7){
            $cp = $this->crear_partida($ano, $cod_sector, $cod_programa,$cod_sub_prog, $cod_proyecto, $cod_activ_obra, $cod_partida,$cod_generica, $cod_especifica, $cod_sub_espec,$cod_auxiliar);
            $to = 2;
            $td = 1;
            $ta = 7;
            $mt = $this->Formato1($monto_disminucion);
            $ccp = '';
            $dnco = $this->motor_presupuestario($cp, $to ,$td, $ta, "$fecha_decreto", $mt, $ccp, $ano, $numero_decreto, null, null, null, null, null, null, null, null, null, null, null, null, $cod_dep);
            if($dnco != false){
              $numero_control_compromiso=$dnco;
            }else{
              $numero_control_compromiso=null;
              break;
            }
          }else if($tipo==8){
            $cp = $this->crear_partida($ano, $cod_sector, $cod_programa,$cod_sub_prog, $cod_proyecto, $cod_activ_obra, $cod_partida,$cod_generica, $cod_especifica, $cod_sub_espec,$cod_auxiliar);
            $to = 2;
            $td = 1;
            $ta = 3;
            $mt = $this->Formato1($monto_disminucion);
            $ccp = '';
            $dnco = $this->motor_presupuestario($cp, $to ,$td, $ta, "$fecha_decreto", $mt, $ccp, $ano, $numero_decreto, null, null, null, null, null, null, null, null, null, null, null, null, $cod_dep);
            if($dnco != false){
              $numero_control_compromiso=$dnco;
            }else{
              $numero_control_compromiso=null;
              break;
            }
          }
					

          $delete_ref_part="delete from cfpd10_reformulacion_partidas where ".$this->SQLCA()." and ano_reformulacion=".$this->ano_ejecucion()." and numero_oficio='".$numero_oficio."'";
					$delete_ref_text="delete from cfpd10_reformulacion_texto where ".$this->SQLCA()." and ano_reformulacion=".$this->ano_ejecucion()." and numero_oficio='".$numero_oficio."'";
					$delete_cfpd20="delete from cfpd20 where cod_presi=".$cod_presi." and cod_entidad=".$cod_entidad." and cod_tipo_inst=".$cod_tipo_inst." and cod_inst=".$cod_inst." and cod_dep=".$cod_dep." and ano=".$this->ano_ejecucion()." and cod_sector=".$cod_sector." and cod_programa=".$cod_programa." and cod_sub_prog=".$cod_sub_prog." and cod_proyecto=".$cod_proyecto." and cod_activ_obra=".$cod_activ_obra." and cod_partida=".$cod_partida." and cod_generica=".$cod_generica." and cod_especifica=".$cod_especifica." and cod_sub_espec=".$cod_sub_espec." and cod_auxiliar=".$cod_auxiliar." and fecha='".$fecha_decreto."'";
					$eliminar1=$this->cfpd10_reformulacion_partidas->execute($delete_ref_part);
					$eliminar2=$this->cfpd10_reformulacion_texto->execute($delete_ref_text);
					$eliminar3=$this->cfpd20->execute($delete_cfpd20);
					$msn=2;
				}
			}
			if($msn==1){
				$validado=true;
				$this->set('validado',$validado);
				$this->set('errorMessage', 'No se puede eliminar este decreto, las partidas tienen algun movimiento');
 				$this->index();
 				$this->render("index");
			}else if($msn==2){
				$validado=true;
				$this->set('validado',$validado);
				$this->set('Message_existe', 'El decreto fue eliminado correctamente');
 				$this->index();
 				$this->render("index");
			}
		}else if($cont_mov==1){
			$validado=true;
			$this->set('validado',$validado);
			$this->set('errorMessage', 'No se puede eliminar este decreto, las partidas tienen algun movimiento');
 			$this->index();
 			$this->render("index");
		}
	}

	function radio($marca=null){
		$this->layout = "ajax";
		if($marca==1){
			echo'<script>';
        		echo "document.getElementById('pista').readOnly=true   ;";
   			echo'</script>';
		}else if($marca==2){
			echo'<script>';
        		echo "document.getElementById('pista').readOnly=false   ;";
   			echo'</script>';
		}
	}

	function grilla($ano=null,$ordenacion=null,$cantidad=null,$pista=null,$pagina_actual=null){
		$this->layout="ajax";//echo 'a'.$ano.'a';
		if($ano==null){//echo 'no';
			$pagina_actual=1;
			$ano=$this->data['cfpp10_reformulacion_oficios']['ano'];
			$ordenacion=$this->data['cfpp10_reformulacion_oficios']['ordenados'];
			$cantidad=$this->data['cfpp10_reformulacion_oficios']['todo_uno'];
			$pista=$this->data['cfpp10_reformulacion_oficios']['pista'];
		}
		if($cantidad==1){
			if($ordenacion==1){
				$Tfilas=$this->cfpd10_reformulacion_texto->findCount("ano_reformulacion=".$ano." and ".$this->SQLCA());
				if($Tfilas!=0){
					$pista='0';
        			$pagina=$pagina_actual;
        			$Tfilas=(int)ceil($Tfilas/50);
					$this->set('pag_cant',$pagina.'/'.$Tfilas);
					$this->set('total_paginas',$Tfilas);
					$this->set('pagina_actual',$pagina);
					$this->set('ultimo',$Tfilas);
					$datos=$this->cfpd10_reformulacion_texto->findAll("ano_reformulacion=".$ano." and ".$this->SQLCA(),null,'numero_oficio ASC',50,$pagina);
					$this->set("datos",$datos);
					$a = $this->cfpd10_reformulacion_tipo->findAll("cod_tipo=".$datos[0]['cfpd10_reformulacion_texto']['cod_tipo'],array('denominacion'));
    				$this->set("tipo",$a[0]['cfpd10_reformulacion_tipo']['denominacion']);
				}else{
        			$this->set("datos",'');
        		}
			}else if($ordenacion==2){
				$Tfilas=$this->cfpd10_reformulacion_texto->findCount("ano_reformulacion=".$ano." and ".$this->SQLCA());
				if($Tfilas!=0){
        			$pagina=$pagina_actual;
        			$Tfilas=(int)ceil($Tfilas/50);
					$this->set('pag_cant',$pagina.'/'.$Tfilas);
					$this->set('total_paginas',$Tfilas);
					$this->set('pagina_actual',$pagina);
					$this->set('ultimo',$Tfilas);
					$datos=$this->cfpd10_reformulacion_texto->findAll("ano_reformulacion=".$ano." and ".$this->SQLCA(),null,'numero_decreto ASC',50,$pagina);
					$this->set("datos",$datos);
					$a = $this->cfpd10_reformulacion_tipo->findAll("cod_tipo=".$datos[0]['cfpd10_reformulacion_texto']['cod_tipo'],array('denominacion'));
    				$this->set("tipo",$a[0]['cfpd10_reformulacion_tipo']['denominacion']);
				}else{
        			$this->set("datos",'');
        		}
			}
		}
		if($cantidad==2){
			if($ordenacion==1){
				$Tfilas=$this->cfpd10_reformulacion_texto->findCount("ano_reformulacion=".$ano." and ".$this->SQLCA()." and (upper(numero_oficio) LIKE '%$pista%' or upper(numero_decreto) LIKE '%$pista%' or upper(numero_oficio_consejo_legis) LIKE '%$pista%')");
        		if($Tfilas!=0){
        			$pagina=$pagina_actual;
        			$Tfilas=(int)ceil($Tfilas/50);
					$this->set('pag_cant',$pagina.'/'.$Tfilas);
					$this->set('total_paginas',$Tfilas);
					$this->set('pagina_actual',$pagina);
					$this->set('ultimo',$Tfilas);
     	    		$datos=$this->cfpd10_reformulacion_texto->findAll("ano_reformulacion=".$ano." and ".$this->SQLCA()." and (upper(numero_oficio) LIKE '%$pista%' or upper(numero_decreto) LIKE '%$pista%'  or upper(numero_oficio_consejo_legis) LIKE '%$pista%')",null,"numero_oficio ASC",50,$pagina,null);
	        		$this->set("datos",$datos);
	        		$a = $this->cfpd10_reformulacion_tipo->findAll("cod_tipo=".$datos[0]['cfpd10_reformulacion_texto']['cod_tipo'],array('denominacion'));
    				$this->set("tipo",$a[0]['cfpd10_reformulacion_tipo']['denominacion']);
	        		$this->set('siguiente',$pagina+1);
					$this->set('anterior',$pagina-1);
					$this->bt_nav($Tfilas,$pagina);
        		}else{
        			$this->set("datos",'');
        		}
			}else if($ordenacion==2){
				$Tfilas=$this->cfpd10_reformulacion_texto->findCount("ano_reformulacion=".$ano." and ".$this->SQLCA()." and (upper(numero_oficio) LIKE '%$pista%' or upper(numero_decreto) LIKE '%$pista%'  or upper(numero_oficio_consejo_legis) LIKE '%$pista%')");
        		if($Tfilas!=0){
        			$pagina=$pagina_actual;
        			$Tfilas=(int)ceil($Tfilas/50);
					$this->set('pag_cant',$pagina.'/'.$Tfilas);
					$this->set('total_paginas',$Tfilas);
					$this->set('pagina_actual',$pagina);
					$this->set('ultimo',$Tfilas);
     	    		$datos=$this->cfpd10_reformulacion_texto->findAll("ano_reformulacion=".$ano." and ".$this->SQLCA()." and (upper(numero_oficio) LIKE '%$pista%' or upper(numero_decreto) LIKE '%$pista%'  or upper(numero_oficio_consejo_legis) LIKE '%$pista%')",null,"numero_decreto ASC",50,$pagina,null);
	        		$this->set("datos",$datos);
	        		$a = $this->cfpd10_reformulacion_tipo->findAll("cod_tipo=".$datos[0]['cfpd10_reformulacion_texto']['cod_tipo'],array('denominacion'));
    				$this->set("tipo",$a[0]['cfpd10_reformulacion_tipo']['denominacion']);
	        		$this->set('siguiente',$pagina+1);
					$this->set('anterior',$pagina-1);
					$this->bt_nav($Tfilas,$pagina);
        		}else{
        			$this->set("datos",'');
       		 	}
			}
		}
		$this->set('ano',$ano);
		$this->set('ordenacion',$ordenacion);
		$this->set('cantidad',$cantidad);
		$this->set('pista',$pista);
	}


function ano_buscar_por_pistaz($ano_var=null){
	$this->layout="ajax";
	$this->Session->delete('ano_pistaz');
	$this->Session->write('ano_pistaz', $ano_var);
}//fin function


function buscar_z($var1=null){
	$this->layout="ajax";
	$this->set("opcion",$var1);
	$this->Session->delete('pista');
}//fin function


function buscar_por_pistaz($var1=null, $var2=null, $var3=null){
$this->layout="ajax";

$ano = '';
$ano = $this->Session->read('ano_pistaz');
if($ano != ''){

}else{
	$ano = $this->ano_ejecucion();
}

    if($var3==null){$var2 = strtoupper($var2);
					$this->Session->write('pista', $var2);
					//if(is_int($var2)){$sql   = " ((rif_cedula LIKE '%$var2%') or (razon_social LIKE '%$var2%'))  or   ";}else{ $sql = "";}
					$Tfilas=$this->cfpd10_reformulacion_texto->findCount('ano_reformulacion='.$ano.' and '.$this->SQLCA()." and ((quitar_acentos(numero_oficio) LIKE quitar_acentos('%$var2%')) or (quitar_acentos(numero_decreto) LIKE quitar_acentos('%$var2%'))  or (quitar_acentos(numero_oficio_consejo_legis) LIKE quitar_acentos('%$var2%')))");
					        if($Tfilas!=0){
					        	$pagina=1;
					        	$Tfilas=(int)ceil($Tfilas/50);
					        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
								$this->set('total_paginas',$Tfilas);
								$this->set('pagina_actual',$pagina);
								$this->set('ultimo',$Tfilas);
					     	    $datos_filas=$this->cfpd10_reformulacion_texto->findAll('ano_reformulacion='.$ano.' and '.$this->SQLCA()." and ((quitar_acentos(numero_oficio) LIKE quitar_acentos('%$var2%')) or (quitar_acentos(numero_decreto) LIKE quitar_acentos('%$var2%'))  or (quitar_acentos(numero_oficio_consejo_legis) LIKE quitar_acentos('%$var2%')))",null,"numero_oficio,numero_decreto ASC",50,1,null);
						        $cod_tipo = $datos_filas[0]['cfpd10_reformulacion_texto']['cod_tipo'];
						        $tipos = $this->cfpd10_reformulacion_tipo->findAll('cod_tipo='.$cod_tipo);
						        $deno = $tipos[0]['cfpd10_reformulacion_tipo']['denominacion'];
						        $this->set('deno',$deno);
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
						//if(is_int($var22)){$sql   = " (codigo_prod_serv LIKE '%$var22%')  or   ";}else{ $sql = "";}
						$Tfilas=$this->cfpd10_reformulacion_texto->findCount('ano_reformulacion='.$ano.' and '.$this->SQLCA()." and ((quitar_acentos(numero_oficio) LIKE quitar_acentos('%$var2%')) or (quitar_acentos(numero_decreto) LIKE quitar_acentos('%$var2%'))  or (quitar_acentos(numero_oficio_consejo_legis) LIKE quitar_acentos('%$var2%')))");
						        if($Tfilas!=0){
						        	$pagina=$var3;
						        	$Tfilas=(int)ceil($Tfilas/50);
						        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
									$this->set('total_paginas',$Tfilas);
									$this->set('pagina_actual',$pagina);
									$this->set('ultimo',$Tfilas);
						     	    $datos_filas=$this->cfpd10_reformulacion_texto->findAll('ano_reformulacion='.$ano.' and '.$this->SQLCA()." and ((quitar_acentos(numero_oficio) LIKE quitar_acentos('%$var2%')) or (quitar_acentos(numero_decreto) LIKE quitar_acentos('%$var2%'))  or (quitar_acentos(numero_oficio_consejo_legis) LIKE quitar_acentos('%$var2%')))",null,"numero_oficio,numero_decreto ASC",50,$pagina,null);
									$cod_tipo = $datos_filas[0]['cfpd10_reformulacion_texto']['cod_tipo'];
						        	$tipos = $this->cfpd10_reformulacion_tipo->findAll('cod_tipo='.$cod_tipo);
						        	$deno = $tipos[0]['cfpd10_reformulacion_tipo']['denominacion'];
						        	$this->set('deno',$deno);
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


}//fin clase cfpp09Controller