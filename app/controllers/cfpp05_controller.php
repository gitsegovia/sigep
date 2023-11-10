<?php

 class Cfpp05Controller extends AppController{


 	var $uses = array('cugd05_restriccion_clave','ccfd04_cierre_mes','cfpd05','cfpd05_requerimiento','cfpd05_2032_tmp','cfpd05_auxiliar','cfpp05auxiliar','cfpd02_sector', 'cfpd02_programa', 'cfpd02_sub_prog', 'cfpd02_proyecto', 'cfpd02_activ_obra', 'arrd05','cfpd01_ano_grupo', 'cfpd01_ano_partida', 'cfpd01_ano_generica', 'cfpd01_ano_especifica', 'cfpd01_ano_sub_espec', 'cfpd01_ano_auxiliar', 'cfpd01_formulacion', 'cugd05_restriccion_clave', 'v_cfpd05_denominaciones', 'v_cfpd05_disponibilidad', 'cfpd05_congelar_descongelar');
 	var $helpers = array('Html','Ajax','Javascript', 'Sisap','Fpdf');


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
    function SQLCA_admin($ano=null){//sql para busqueda de codigos de arranque con y sin año
         $sql_re = "cod_presi=".$this->verifica_SS(1)."  and    ";
         $sql_re .= "cod_entidad=".$this->verifica_SS(2)."  and  ";
         $sql_re .= "cod_tipo_inst=".$this->verifica_SS(3)."  and ";
         $sql_re .= "cod_inst=".$this->verifica_SS(4)."  and  ";
         if($ano!=null){
	        if($this->verifica_SS(5)!=1){
         	$sql_re .= "cod_dep=".$this->verifica_SS(5)."  and  ";
                }
                $sql_re .= "ano=".$ano."  ";
         }else{
         	if($this->verifica_SS(5)!=1){
         	$sql_re .= "cod_dep=".$this->verifica_SS(5)."  ";
                }
         }
         return $sql_re;
    }//fin funcion SQLCA
    function SQLCA_reque($ano=null){//sql para busqueda de codigos de arranque con y sin año
         $sql_re = "cod_presi=".$this->verifica_SS(1)."  and    ";
         $sql_re .= "cod_entidad=".$this->verifica_SS(2)."  and  ";
         $sql_re .= "cod_tipo_inst=".$this->verifica_SS(3)."  and ";
         $sql_re .= "cod_inst=".$this->verifica_SS(4)."  and  ";
         if($ano!=null){
         	$sql_re .= "ano=".$ano."  ";
         }else{

         }
         return $sql_re;
    }//fin funcion SQLCA

    function SQLCA_report($pre=null){
         $sql_re = "cod_presi=".$this->verifica_SS(1)."  and    ";
         $sql_re .= "cod_entidad=".$this->verifica_SS(2)."  and  ";
         $sql_re .= "cod_tipo_inst=".$this->verifica_SS(3)."  and ";
         if($pre!=null && $pre==1){
         $sql_re .= "cod_inst=".$this->verifica_SS(4)." ";
         //$sql_re .= "cod_dep=0";
         }else{
         	$sql_re .= "cod_inst=".$this->verifica_SS(4)."  and  ";
            $sql_re .= "cod_dep=".$this->verifica_SS(5)." ";
         }

         return $sql_re;
    }//fin funcion SQLCA
    function SQLCA_report_in($pre=null){
         $sql_re = $this->verifica_SS(1).",";
         $sql_re .= $this->verifica_SS(2).",";
         $sql_re .= $this->verifica_SS(3).",";
         if($pre!=null && $pre==1){
         $sql_re .= $this->verifica_SS(4).",";
         $sql_re .= 0;
         }else{
         	$sql_re .= $this->verifica_SS(4).",";
            $sql_re .= $this->verifica_SS(5)." ";
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

function SQLCA_noDEP(){
  $cod_presi = $this->Session->read('SScodpresi');
  $cod_entidad = $this->Session->read('SScodentidad');
  $cod_tipo_inst = $this->Session->read('SScodtipoinst');
  $cod_inst = $this->Session->read('SScodinst');
  $condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst;

  return $condicion;
}//fin funcion SQLCA_noDEP

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
   	return $Var;
   	  }else{
   	  	  //return $Var;
   	  }



   }//fin AddCero


   function concatenaCE_v2($vector1=null, $nomVar=null){
	if($vector1 != null){
		foreach($vector1 as $x => $y){
			$cod[CE.$this->zero($x)] =CE.$this->zero($x).' - '.$y;
		}
		//print_r($cod);
		$this->set($nomVar, $cod);
	}
}
	function index(){

$this->verifica_entrada('22');

	 $this->layout = "ajax";

	  //A partir de aqui esta el codigo para bajar el año presupuestario por defecto
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
         $this->Session->write('ANO_EJECUCION',$this->ano_ejecucion());
         $this->Session->write('ANO_FORMULAR',$dato);
		$this->set('year', $dato);

	}else{
		$this->set('year', '');
	}

// fin del codigo

      $prov=$this->arrd05->findAll($this->SQLCX().'and cod_dep='.$cod_dep,array('formulacion'),null,1,1,null);
	  $formulacion = $prov[0]["arrd05"]["formulacion"];
      if($formulacion == 2){
      	$this->set('mensajeError', 'NO ESTA AUTORIZADO PARA ENTRAR A ESTE PROGRAMA.');
		echo "<script>
				document.getElementById('bt_continuar').disabled = true;
			</script>";
      }

	}

    function distribucion_gasto(){
          $this->layout="ajax";
          $pagina=1;
          if(!empty($this->data['cfpp05']['ano'])){
          	      $ano=$this->data['cfpp05']['ano'];
          	      $this->set('ano',$ano);
          	      $this->Session->delete('ano_d_g');
                  $this->Session->write('ano_d_g', $ano);
                  $listaSector=$this->cfpd02_sector->generateList("where ".$this->SQLCA($ano), 'cod_sector ASC', null, '{n}.cfpd02_sector.cod_sector', '{n}.cfpd02_sector.denominacion');
                  if($listaSector!=null){

                  	  //$this->AddCero('sector',$listaSector);
                  	  $this->concatena($listaSector,'sector');
                  	  //$this->set('Message_existe', 'Datos encontrados con exito.');
                  	  $Tfilas=$this->cfpd05->findCount($this->SQLCA($ano));
          	          $Tpag = (int)ceil($Tfilas/250);
          	          $this->set('ultimo',$Tpag);
          	          $this->set('TotalPaginas',$Tpag);
          	          $this->set('pagina_actual',$pagina);
          	          $this->set('paginacion',$pagina.' / '.$Tpag);
          	          $TT=$this->cfpd05->execute("SELECT SUM(asignacion_anual) as total FROM cfpd05 WHERE ".$this->SQLCA($ano));

          	          $this->set("TOTALDISTRIBUCION",$TT[0][0]["total"]);
                  	  $dataCFPD05=$this->cfpd05->findAll($this->SQLCA($ano),array('cod_presi','cod_entidad','cod_tipo_inst','cod_inst','cod_dep','cod_sector','cod_programa','cod_sub_prog','cod_proyecto','cod_activ_obra','cod_partida','cod_generica','cod_especifica','cod_sub_espec','cod_auxiliar','asignacion_anual','ano'),'cod_presi,cod_entidad,cod_tipo_inst,cod_inst,cod_dep,cod_sector,cod_programa,cod_sub_prog,cod_proyecto,cod_activ_obra,cod_partida,cod_generica,cod_especifica,cod_sub_espec,cod_auxiliar ASC',250,$pagina,null);
                      $this->set('datacfpd05',$dataCFPD05);
                      $this->set('siguiente',$pagina+1);
          	          $this->set('anterior',$pagina-1);
                      $this->bt_nav($Tpag,$pagina);
                       $dataCFPD05requerimiento=$this->cfpd05_requerimiento->findAll($this->SQLCA_reque($this->Session->read('ano_d_g')),array('cod_presi','cod_entidad','cod_tipo_inst','cod_inst','cod_dep','cod_sector','cod_programa','cod_sub_prog','cod_proyecto','cod_activ_obra','cod_partida','cod_generica','cod_especifica','cod_sub_espec','cod_auxiliar'),'cod_presi,cod_entidad,cod_tipo_inst,cod_inst,cod_dep,cod_sector,cod_programa,cod_sub_prog,cod_proyecto,cod_activ_obra,cod_partida,cod_generica,cod_especifica,cod_sub_espec,cod_auxiliar ASC',null,null,null);
                       $this->set('datos_req',$dataCFPD05requerimiento);
                  }else{
                  	   $this->set('errorMessage', 'No se encontraron datos para el año indicado.');
                  	   $this->set('sector','');
                  	    $Tfilas=$this->cfpd05->findCount($this->SQLCA($ano));
                  	    $Tpag = (int)ceil($Tfilas/250);
                  	    $this->set('ultimo',$Tpag);
          	          $this->set('TotalPaginas',$Tpag);
          	          $this->set('pagina_actual',$pagina);
          	          $this->set('paginacion',$pagina.' / '.$Tpag);
          	          $TT=$this->cfpd05->execute("SELECT SUM(asignacion_anual) as total FROM cfpd05 WHERE ".$this->SQLCA($ano));
          	          $this->set("TOTALDISTRIBUCION",$TT[0][0]["total"]);
                  	   $dataCFPD05=$this->cfpd05->findAll($this->SQLCA($ano),array('cod_presi','cod_entidad','cod_tipo_inst','cod_inst','cod_dep','cod_sector','cod_programa','cod_sub_prog','cod_proyecto','cod_activ_obra','cod_partida','cod_generica','cod_especifica','cod_sub_espec','cod_auxiliar','asignacion_anual','ano'),'cod_presi,cod_entidad,cod_tipo_inst,cod_inst,cod_dep,cod_sector,cod_programa,cod_sub_prog,cod_proyecto,cod_activ_obra,cod_partida,cod_generica,cod_especifica,cod_sub_espec,cod_auxiliar ASC',250,$pagina,null);
                       $this->set('datacfpd05',$dataCFPD05);
                       $this->set('siguiente',$pagina+1);
          	           $this->set('anterior',$pagina-1);
                       $this->bt_nav($Tpag,$pagina);
                        $dataCFPD05requerimiento=$this->cfpd05_requerimiento->findAll($this->SQLCA_reque($this->Session->read('ano_d_g')),array('cod_presi','cod_entidad','cod_tipo_inst','cod_inst','cod_dep','cod_sector','cod_programa','cod_sub_prog','cod_proyecto','cod_activ_obra','cod_partida','cod_generica','cod_especifica','cod_sub_espec','cod_auxiliar'),'cod_presi,cod_entidad,cod_tipo_inst,cod_inst,cod_dep,cod_sector,cod_programa,cod_sub_prog,cod_proyecto,cod_activ_obra,cod_partida,cod_generica,cod_especifica,cod_sub_espec,cod_auxiliar ASC',null,null,null);
                        $this->set('datos_req',$dataCFPD05requerimiento);
                  }

                  //$this->set('partida', $this->cfpd01_ano_partida->generateList("where cod_grupo=4 AND ejercicio=".$ano, 'cod_partida ASC', null, '{n}.cfpd01_ano_partida.cod_partida', '{n}.cfpd01_ano_partida.cod_partida'));

          }else{
              //echo "no hay año";
              //$this->set('sector',array(1,2,3,4));
          }
          $this->verifica_SS(5)>1000 ? $this->render('distribucion_gasto') : $this->render('distribucion_gasto');

    }//fin distribucion_gasto
   /****************************************************************************************
    *
    *
    * **/

function selec_sector($var=null){
   $this->layout = "ajax";
   if($this->data['cfpp05']['codigo']){$var = $this->data['cfpp05']['codigo'];   $this->set('opcion', $var);
   }else{ $this->set('opcion', $var);  }
   $ano=$this->Session->read('ano_d_g');
   $lista =  $this->cfpd02_sector->generateList("where ".$this->SQLCA($ano), 'cod_sector ASC', null, '{n}.cfpd02_sector.cod_sector', '{n}.cfpd02_sector.cod_sector');
   $this->AddCero('sector',$lista);
   //$this->set('sector', $lista);
}//fin s sector


function selec_programa($var=null, $aux=null){
	$this->layout = "ajax";
	if($this->data['cfpp05']['codigo'] &&  $var!=null){ $this->set('selecion', $this->data['cfpp05']['codigo']); }
	if($var==null){ $var = $this->data['cfpp05']['codigo']; }
	if($aux!=null){  $this->set('selecion', $aux);}
	$this->set('opcion1', $var);
	if($var!=null && $var!='otros'){
		$ano=$this->Session->read('ano_d_g');
		$lista=$this->cfpd02_programa->generateList('where '.$this->SQLCA($ano).' and cod_sector =  '.$var.' ', ' cod_programa ASC', null, '{n}.cfpd02_programa.cod_programa', '{n}.cfpd02_programa.denominacion');
	//$this->AddCero('programa',$listaProg);
	$this->concatena($lista,'programa');
	}else{   $this->set('programa', '');}
}//fin s programa


function selec_sub_prog($var1=null, $var2=null , $aux=null){
    $this->layout = "ajax";
	if($this->data['cfpp05']['codigo']  &&  $var2!=null){ $this->set('selecion', $this->data['cfpp05']['codigo']); }
	if($var2==null){ $var2 = $this->data['cfpp05']['codigo'];}
	if($aux!=null){  $this->set('selecion', $aux);}
		$this->set('opcion1', $var1);
		$this->set('opcion2', $var2);
		if($var2!=null && $var2!='otros'){
			$ano=$this->Session->read('ano_d_g');
		$lista=$this->cfpd02_sub_prog->generateList('where '.$this->SQLCA($ano).' and cod_sector =  '.$var1.'  and cod_programa = '.$var2.'', ' cod_sub_prog ASC', null, '{n}.cfpd02_sub_prog.cod_sub_prog', '{n}.cfpd02_sub_prog.denominacion');
		//$this->AddCero('sub_prog',$listaSP);
		$this->concatena($lista,'sub_prog');
		}else{   $this->set('sub_prog', ''); }
}//fin s sub prog

function selec_proyecto($var1=null, $var2=null, $var3=null , $aux=null){
	$this->layout = "ajax";
	if($this->data['cfpp05']['codigo']  &&  $var3!=null){ $this->set('selecion', $this->data['cfpp05']['codigo']); }
	if($var3==null){ $var3 = $this->data['cfpp05']['codigo'];}
	if($aux!=null){  $this->set('selecion', $aux);}
		$this->set('opcion1', $var1);
		$this->set('opcion2', $var2);
		$this->set('opcion3', $var3);
		if($var3!=null && $var3!='otros'){
			$ano=$this->Session->read('ano_d_g');
	    $lista=$this->cfpd02_proyecto->generateList('where '.$this->SQLCA($ano).' and cod_sector =  '.$var1.'  and cod_programa = '.$var2.' and cod_sub_prog = '.$var3.'', ' cod_proyecto ASC', null, '{n}.cfpd02_proyecto.cod_proyecto', '{n}.cfpd02_proyecto.denominacion');
		//$this->AddCero('proyecto',$listaProyecto);
		$this->concatena($lista,'proyecto');
		}else{   $this->set('proyecto', ''); }
}//fin s proyecto


function selec_activ_obra($var1=null, $var2=null, $var3=null, $var4=null, $aux=null) {
	$this->layout = "ajax";
	if($this->data['cfpp05']['codigo']  &&  $var4!=null){ $this->set('selecion', $this->data['cfpp05']['codigo']); }
	if($var4==null){ $var4 = $this->data['cfpp05']['codigo'];}
	if($aux!=null){  $this->set('selecion', $aux);}
		$this->set('opcion1', $var1);
		$this->set('opcion2', $var2);
		$this->set('opcion3', $var3);
		$this->set('opcion4', $var4);
		if($var4!=null && $var4!='otros'){
			$ano=$this->Session->read('ano_d_g');
		$lista=$this->cfpd02_activ_obra->generateList('where '.$this->SQLCA($ano).' and cod_sector =  '.$var1.'  and cod_programa = '.$var2.' and cod_sub_prog = '.$var3.' and cod_proyecto = '.$var4.'', ' cod_activ_obra ASC', null, '{n}.cfpd02_activ_obra.cod_activ_obra', '{n}.cfpd02_activ_obra.denominacion');
		//$this->AddCero('activ_obra',$listaActiv);
		$this->concatena($lista,'activ_obra');
		}else{   $this->set('activ_obra', ''); }
}//fin s activi_obra

function CODSPSPA($ano,$c1,$c2,$c3,$c4,$c5){
    $SQL ='where '.$this->SQLCA($ano).' and ';
    $SQL .='cod_sector='.$c1.' and ';
    $SQL .='cod_programa='.$c2.' and ';
    $SQL .='cod_sub_prog='.$c3.' and ';
    $SQL .='cod_proyecto='.$c4.' and ';
    $SQL .='cod_activ_obra='.$c5.' and ';
    return $SQL;
}
/**
 * select del onapre
 * */
function selec_partida($op1=null,$op2=null,$op3=null,$op4=null,$aux=null){
	$this->layout = "ajax";
	//$this->set('codigo',array($op1,$op2,$op3,$op4,$aux));
	$this->Session->delete('c_sector');
	$this->Session->delete('c_programa');
	$this->Session->delete('c_sub_prog');
	$this->Session->delete('c_proyecto');
	$this->Session->delete('c_activ_obra');

	$this->Session->write('c_sector',$op1);
	$this->Session->write('c_programa',$op2);
	$this->Session->write('c_sub_prog',$op3);
	$this->Session->write('c_proyecto',$op4);
	$this->Session->write('c_activ_obra',$aux);
	$var=CE;
	if($this->data['cfpp05']['codigo'] &&  $var!=null){ $this->set('selecion', $this->data['cfpp05']['codigo']); }
	if($var==null){ $var = $this->data['cfpp05']['codigo']; }
	if($aux!=null){  $this->set('selecion', '');}
	$this->set('opcion7', $var);
	if($var!=null && $var!='otros'){
		$ano=$this->Session->read('ano_d_g');
         if($aux==''){
         	$this->set('partida', '');
         }else{
         	$lista=$this->cfpd01_ano_partida->generateList('where ejercicio='.$ano.' and cod_grupo =  '.$var.' ', ' cod_partida ASC', null, '{n}.cfpd01_ano_partida.cod_partida', '{n}.cfpd01_ano_partida.denominacion');
        //$this->AddCero('partida',$parti,CE);
        $this->concatenaCE_v2($lista,'partida');
         }



	}else{   $this->set('partida', '');}

}//fin partida

function selec_generica($var1=null, $var2=null , $aux=null){
    $this->layout = "ajax";
			if($this->data['cfpp05']['codigo']  &&  $var2!=null){ $this->set('selecion', $this->data['cfpp05']['codigo']); }
            if($var2==null){ $var2 = $this->data['cfpp05']['codigo'];}
			if($aux!=null){  $this->set('selecion', $aux);}
    $var2=substr($var2,1);
	$this->set('opcion7', $var1);
	$this->set('opcion8', $var2);

	if($var2!=null && $var2!='otros'){
          $ano=$this->Session->read('ano_d_g');

	$lista=$this->cfpd01_ano_generica->generateList('where ejercicio='.$ano.' and cod_grupo =  '.$var1.'  and cod_partida = '.$var2.'', ' cod_generica ASC', null, '{n}.cfpd01_ano_generica.cod_generica', '{n}.cfpd01_ano_generica.denominacion');
    //$this->AddCero('generica',$listaGenerica);
    $this->concatena($lista,'generica');
	}else{   $this->set('generica', ''); }

}//fin generica

function selec_especifica($var1=null, $var2=null, $var3=null , $aux=null){
	$this->layout = "ajax";

if($this->data['cfpp05']['codigo']  &&  $var3!=null){ $this->set('selecion', $this->data['cfpp05']['codigo']); }
if($var3==null){ $var3 = $this->data['cfpp05']['codigo'];}
if($aux!=null){  $this->set('selecion', $aux);}
	$this->set('opcion7', $var1);
	$this->set('opcion8', $var2);
	$this->set('opcion9', $var3);
	if($var3!=null && $var3!='otros'){
		$ano=$this->Session->read('ano_d_g');

    $lista=$this->cfpd01_ano_especifica->generateList('where ejercicio='.$ano.' and cod_grupo =  '.$var1.'  and cod_partida = '.$var2.' and cod_generica = '.$var3.'', ' cod_especifica ASC', null, '{n}.cfpd01_ano_especifica.cod_especifica', '{n}.cfpd01_ano_especifica.denominacion');
	//$this->AddCero('especifica',$listaEspec);
	$this->concatena($lista,'especifica');
	}else{   $this->set('especifica', ''); }
}//fin especifica

function selec_sub_especifica($var1=null, $var2=null, $var3=null, $var4=null, $aux=null) {
	$this->layout = "ajax";

if($this->data['cfpp05']['codigo']  &&  $var4!=null){ $this->set('selecion', $this->data['cfpp05']['codigo']); }
if($var4==null){ $var4 = $this->data['cfpp05']['codigo'];}
if($aux!=null){  $this->set('selecion', $aux);}
	$this->set('opcion7', $var1);
	$this->set('opcion8', $var2);
	$this->set('opcion9', $var3);
	$this->set('opcion10', $var4);

	if($var4!=null && $var4!='otros'){
		$ano=$this->Session->read('ano_d_g');
	$lista=$this->cfpd01_ano_sub_espec->generateList('where ejercicio='.$ano.' and cod_grupo =  '.$var1.'  and cod_partida = '.$var2.' and cod_generica = '.$var3.' and cod_especifica = '.$var4.'', ' cod_sub_espec ASC', null, '{n}.cfpd01_ano_sub_espec.cod_sub_espec', '{n}.cfpd01_ano_sub_espec.denominacion');
	//$this->AddCero('subespecifica',$listaSE);
	$this->concatena($lista,'subespecifica');
	}else{   $this->set('subespecifica', ''); }
}//fin seb_especifica

function selec_auxiliar($var1=null, $var2=null, $var3=null, $var4=null, $var5=null, $aux=null){
	$this->layout = "ajax";
	$var2 = (int) $var2;
	$var2 = $var2 < 10 ? CE."0".$var2 : CE.$var2;

	if($aux!=null){  $this->set('selecion', $aux);}
    $sql_gpgese =" cod_partida=".$var2." and";
    $sql_gpgese .=" cod_generica=".$var3." and";
    $sql_gpgese .=" cod_especifica=".$var4." and";
    $sql_gpgese .=" cod_sub_espec=".$var5." ";
    $a = $this->Session->read('c_sector');
	$b = $this->Session->read('c_programa');
	$c = $this->Session->read('c_sub_prog');
	$d = $this->Session->read('c_proyecto');
	$e = $this->Session->read('c_activ_obra');
	if($this->data['cfpp05']['codigo']  &&  $var5!=null){ $this->set('selecion', $this->data['cfpp05']['codigo']); }
	if($var5==null){ $var5 = $this->data['cfpp05']['codigo'];}
	if($aux!=null){  $this->set('selecion', $aux);}

	$this->set('opcion7', $var1);
	$this->set('opcion8', $var2);
	$this->set('opcion9', $var3);
	$this->set('opcion10', $var4);
	$this->set('opcion11', $var5);

	if($var5!=null && $var5!='otros'){
		$ano=$this->Session->read('ano_d_g');
		$condicion=$this->CODSPSPA($ano,$a,$b,$c,$d,$e)." ".$sql_gpgese;
	$lista=$this->cfpd05_auxiliar->generateList($condicion, ' cod_auxiliar ASC', null, '{n}.cfpd05_auxiliar.cod_auxiliar', '{n}.cfpd05_auxiliar.denominacion');
	if($lista==null){
		$lista=array("0"=>"N/A");
	}
	    //$this->set('auxiliar',$listaAux);
    //$this->AddCero('auxiliar',$listaAux);
    $this->concatena_auxiliar($lista,'auxiliar');
	}else{

		$this->set('auxiliar', '');

		}

}//fin auxiliar

function principal($var1=null, $var2=null, $var3=null, $var4=null, $var5=null, $var6=null){

	$year = $this->cfpd01_formulacion->findAll($this->condicionNDEP(), null, 'ano_formular ASC', null);
	$dato = null;
	foreach($year as $year){
		$ano = $year['cfpd01_formulacion']['ano_formular'];
	}
   	$this->layout = "ajax";
	$this->set('opcion1', $var1);
	$this->set('opcion2', $var2);
	$this->set('opcion3', $var3);
	$this->set('opcion4', $var4);
	$this->set('opcion5', $var5);
    $this->set('opcion6', $var6);

	$action='';
	$tabla = '';
	$sql_3 = '';

	if($var1=='otros'){$action=$var1; }
	if($var2=='otros'){$action=$var2; }
	if($var3=='otros'){$action=$var3; }
	if($var4=='otros'){$action=$var4; }
	if($var5=='otros'){$action=$var5; }
	if($var6=='otros'){$action=$var6; }


	if($var1!=null){
		$sql_2 =  ' cod_sector =  '.$var1.'  ';
		$tabla='cfpd02_sector';
	}
	if($var2!=null){
		$sql_2 .= 'and cod_programa = '.$var2.'  ';
		$tabla='cfpd02_programa';
		$sql_3 =  ' cod_sector =  '.$var1.'  ';
	}
	if($var3!=null){
		$sql_2 .= 'and cod_sub_prog = '.$var3.'  ';
		$tabla='cfpd02_sub_prog';
		$sql_3 .= 'and cod_programa = '.$var2.'  ';
	}
	if($var4!=null){ $sql_2 .= 'and cod_proyecto = '.$var4.'  ';
		$tabla='cfpd02_proyecto';
		$sql_3 .= 'and cod_sub_prog = '.$var3.'  ';
	}
	if($var5!=null){ $sql_2.= 'and cod_activ_obra = '.$var5.'  ';
		$tabla='cfpd02_activ_obra';
		$sql_3 .= 'and cod_proyecto = '.$var4.'  ';
	}

	$this->set('tabla', $tabla);


if($var1!=null && $action!='otros'){

       $sql_re = $sql_2;
	  $data = $this->$tabla->findAll($this->condicion().' and '.$sql_re.' and ano='.$ano, null, null, null);

	  $this->set('datos_cod_cfpp02', $data);

}else if($var1!=null){

	  $sql_re = $sql_3;
	  $data = $this->$tabla->findAll($this->condicion().' and '.$sql_re.' and ano='.$ano, null, null, null);

	  $this->set('datos_cod_cfpp02', $data);

}//fin else


 }//FIN function principal

 function principal2($var1=null, $var2=null, $var3=null, $var4=null, $var5=null,$aux=null){

   	$this->layout = "ajax";
   	$var1=CE;
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

 function principal3($var1=null, $var2=null, $var3=null, $var4=null, $var5=null,$aux=null){

   	$this->layout = "ajax";
   	$var1=CE;
   	if($aux!=null){
   		$aux=$aux;
   	}else{
   		$aux=$this->data['cfpp05']['codigo'];
   	}
   	//echo "v1: ".$var1." v2: ".$var2." v3: ".$var3." v4: ".$var4." v5: ".$var5." aux: ".$aux;
   	$sql_gpgese =" cod_partida=".$var2." and";
    $sql_gpgese .=" cod_generica=".$var3." and";
    $sql_gpgese .=" cod_especifica=".$var4." and";
    $sql_gpgese .=" cod_sub_espec=".$var5." and";
    $sql_gpgese .=" cod_auxiliar=".$aux." ";
    $a = $this->Session->read('c_sector');
	$b = $this->Session->read('c_programa');
	$c = $this->Session->read('c_sub_prog');
	$d = $this->Session->read('c_proyecto');
	$e = $this->Session->read('c_activ_obra');
	    $ano=$this->Session->read('ano_d_g');
		$condicion=$this->CODSPSPA($ano,$a,$b,$c,$d,$e)." ".$sql_gpgese;
		if($aux!=null){
			$dataAux=$this->cfpd05_auxiliar->findAll($condicion, null, null, null);
	        $this->set('datosAuxiliar', $dataAux);
		}else{
            $this->set('datosAuxiliar', '');
		}


 }//FIN FUNCTION principal3


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

function guardar($pagina=null){
	$this->layout = "ajax";
	if(!isset($pagina)){
			$pagina=1;
		}

	if(!empty($this->data)){
		   if(isset($this->data['cfpp05']['tipo_presupuesto'])){
		   	  $tipo_presupuesto=$this->data['cfpp05']['tipo_presupuesto'];
		   	  $clasificacion_recurso_extra=$this->data['cfpp05']['clasificacion_recurso_extra'];
              $clasificacion_recurso_extra = $tipo_presupuesto==5 ? $clasificacion_recurso_extra : 0;
		   }else{
		   	  $tipo_presupuesto =1;
		   	  $clasificacion_recurso_extra = 0;
		   }

        $Cpresi=$this->verifica_SS(1);
		$Centidad=$this->verifica_SS(2);
		$Ctipo_inst=$this->verifica_SS(3);
		$Cinst=$this->verifica_SS(4);
		$Cdep=$this->verifica_SS(5);
		$ano=$this->Session->read('ano_d_g');
		$Csector=       $this->data['cfpp05']['cod_sector'];
		$Cprograma=     $this->data['cfpp05']['cod_programa'];
		$Csubprograma=  $this->data['cfpp05']['cod_sub_prog'];
		$Cproyecto=     $this->data['cfpp05']['cod_proyecto'];
		$Cactividad=    $this->data['cfpp05']['cod_activ_obra'];
		$Cpartida=      $this->data['cfpp05']['cod_partida'];
		$Cgenerica=     $this->data['cfpp05']['cod_generica'];
		$Cespecifica=   $this->data['cfpp05']['cod_especifica'];
		$Csub_espec=    $this->data['cfpp05']['cod_sub_espec'];
		if(isset($this->data['cfpp05']['cod_auxiliar']))
		  if($this->data['cfpp05']['cod_auxiliar']=="" || $this->data['cfpp05']['cod_auxiliar']==null)
		    $Cauxiliar=0;
		  else
		    $Cauxiliar=     $this->data['cfpp05']['cod_auxiliar'];
	    else
           $Cauxiliar=0;

		$asignacion_anual=$this->Formato1($this->data['cfpp05']['monto']);
		$Ctipo_gasto=$this->data['cfpp05']['tipo_gasto'];
        $SQLINSERT="INSERT INTO cfpd05 (cod_presi,cod_entidad,cod_tipo_inst,cod_inst,cod_dep,ano,cod_sector,cod_programa,cod_sub_prog,cod_proyecto,cod_activ_obra,cod_partida,cod_generica,cod_especifica,cod_sub_espec,cod_auxiliar,cod_tipo_gasto,tipo_presupuesto,asignacion_anual,aumento_traslado_anual,
		disminucion_traslado_anual,
		credito_adicional_anual,
		rebaja_anual,
		compromiso_anual,
		causado_anual,
		pagado_anual,
		asignacion_ene,
		aumento_traslado_ene,
		disminucion_traslado_ene,
		credito_adicional_ene,
		rebaja_ene,
		compromiso_ene,
		causado_ene,
		pagado_ene,
		asignacion_feb,
		aumento_traslado_feb,
		disminucion_traslado_feb,
		credito_adicional_feb,
		rebaja_feb,
		compromiso_feb,
		causado_feb,
		pagado_feb,
		asignacion_mar,
		aumento_traslado_mar,
		disminucion_traslado_mar,
		credito_adicional_mar,
		rebaja_mar,
		compromiso_mar,
		causado_mar,
		pagado_mar,
		asignacion_abr,
		aumento_traslado_abr,
		disminucion_traslado_abr,
		credito_adicional_abr,
		rebaja_abr,
		compromiso_abr,
		causado_abr,
		pagado_abr,
		asignacion_may,
		aumento_traslado_may,
		disminucion_traslado_may,
		credito_adicional_may,
		rebaja_may,
		compromiso_may,
		causado_may,
		pagado_may,
		asignacion_jun,
		aumento_traslado_jun,
		disminucion_traslado_jun,
		credito_adicional_jun,
		rebaja_jun,
		compromiso_jun,
		causado_jun,
		pagado_jun,
		asignacion_jul,
		disminucion_traslado_jul,
		credito_adicional_jul,
		rebaja_jul,
		compromiso_jul,
		causado_jul,
		pagado_jul,
		asignacion_ago,
		aumento_traslado_ago,
		disminucion_traslado_ago,
		credito_adicional_ago,
		rebaja_ago,
		compromiso_ago,
		causado_ago,
		pagado_ago,
		asignacion_sep,
		aumento_traslado_sep,
		disminucion_traslado_sep,
		credito_adicional_sep,
		rebaja_sep,
		compromiso_sep,
		causado_sep,
		pagado_sep,
		asignacion_oct,
		aumento_traslado_oct,
		disminucion_traslado_oct,
		credito_adicional_oct,
		rebaja_oct,
		compromiso_oct,
		causado_oct,
		pagado_oct,
		asignacion_nov,
		aumento_traslado_nov,
		disminucion_traslado_nov,
		credito_adicional_nov,
		rebaja_nov,
		compromiso_nov,
		causado_nov,
		pagado_nov,
		asignacion_dic,
		aumento_traslado_dic,
		disminucion_traslado_dic,
		credito_adicional_dic,
		rebaja_dic,
		compromiso_dic,
		causado_dic,
		pagado_dic,
		precompromiso_congelado,
		precompromiso_requisicion,
		precompromiso_obras,
		precompromiso_fondo_avance,clasificacion_recurso_extra) VALUES";
        $SQLINSERT .="($Cpresi,$Centidad,$Ctipo_inst,$Cinst,$Cdep,$ano,$Csector,$Cprograma,$Csubprograma,$Cproyecto,$Cactividad,$Cpartida,$Cgenerica,$Cespecifica,$Csub_espec,$Cauxiliar,$Ctipo_gasto,$tipo_presupuesto,$asignacion_anual,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,$clasificacion_recurso_extra)";
        $restri="cod_presi=".$Cpresi." and cod_entidad=".$Centidad." and cod_tipo_inst=".$Ctipo_inst." and cod_inst=".$Cinst." and cod_dep=".$Cdep." and ano=".$ano." and cod_sector=".$Csector." and cod_programa=".$Cprograma." and cod_sub_prog=".$Csubprograma." and cod_proyecto=".$Cproyecto." and cod_activ_obra=".$Cactividad." and cod_partida=".$Cpartida." and cod_generica=".$Cgenerica." and cod_especifica=".$Cespecifica." and cod_sub_espec=".$Csub_espec." and cod_auxiliar=".$Cauxiliar."";
        $restri_aux_cero="cod_presi=".$Cpresi." and cod_entidad=".$Centidad." and cod_tipo_inst=".$Ctipo_inst." and cod_inst=".$Cinst." and cod_dep=".$Cdep." and ano=".$ano." and cod_sector=".$Csector." and cod_programa=".$Cprograma." and cod_sub_prog=".$Csubprograma." and cod_proyecto=".$Cproyecto." and cod_activ_obra=".$Cactividad." and cod_partida=".$Cpartida." and cod_generica=".$Cgenerica." and cod_especifica=".$Cespecifica." and cod_sub_espec=".$Csub_espec." and cod_auxiliar=0";
        $restri_aux_dist_cero="cod_presi=".$Cpresi." and cod_entidad=".$Centidad." and cod_tipo_inst=".$Ctipo_inst." and cod_inst=".$Cinst." and cod_dep=".$Cdep." and ano=".$ano." and cod_sector=".$Csector." and cod_programa=".$Cprograma." and cod_sub_prog=".$Csubprograma." and cod_proyecto=".$Cproyecto." and cod_activ_obra=".$Cactividad." and cod_partida=".$Cpartida." and cod_generica=".$Cgenerica." and cod_especifica=".$Cespecifica." and cod_sub_espec=".$Csub_espec." and cod_auxiliar!=0";
        


        if($Cauxiliar==0){
              if($this->cfpd05->findCount($restri_aux_dist_cero)==0){
              	$puede_crear_presupuesto=true;
              }else{
              	$puede_crear_presupuesto=false;
              }
        }else{
            	if($this->cfpd05->findCount($restri_aux_cero)==0){
              	$puede_crear_presupuesto=true;
              }else{
              	$puede_crear_presupuesto=false;
              }
        }
       //echo $this->cfpd05->findCount($restri);
       if($puede_crear_presupuesto==true){
        if($this->cfpd05->findCount($restri)==0){
        	  $this->cfpd05->execute($SQLINSERT);
              $Tfilas=$this->cfpd05->findCount($this->SQLCA($ano));
              $Tpag = (int)ceil($Tfilas/250);
          	          $this->set('TotalPaginas',$Tpag);
          	          $this->set('pagina_actual',$pagina);
          	          $this->set('paginacion',$pagina.' / '.$Tpag);
          	          $this->set('ultimo',$Tpag);
          	          $TT=$this->cfpd05->execute("SELECT SUM(asignacion_anual) as total FROM cfpd05 WHERE ".$this->SQLCA($ano));
          	          $this->set("TOTALDISTRIBUCION",$TT[0][0]["total"]);
          	          $dataCFPD05=$this->cfpd05->findAll($this->SQLCA($ano),array('cod_presi','cod_entidad','cod_tipo_inst','cod_inst','cod_dep','cod_sector','cod_programa','cod_sub_prog','cod_proyecto','cod_activ_obra','cod_partida','cod_generica','cod_especifica','cod_sub_espec','cod_auxiliar','cod_tipo_gasto','tipo_presupuesto','asignacion_anual','ano'),'cod_presi,cod_entidad,cod_tipo_inst,cod_inst,cod_dep,cod_sector,cod_programa,cod_sub_prog,cod_proyecto,cod_activ_obra,cod_partida,cod_generica,cod_especifica,cod_sub_espec,cod_auxiliar ASC',250,$pagina,null);
              $this->set('datacfpd05',$dataCFPD05);
              $this->set('siguiente',$pagina+1);
          	  $this->set('anterior',$pagina-1);
              $this->bt_nav($Tpag,$pagina);
		      $this->set('Message_existe', 'Registro agregado exitosamente.');
		      $dataCFPD05requerimiento=$this->cfpd05_requerimiento->findAll($this->SQLCA_reque($ano),array('cod_presi','cod_entidad','cod_tipo_inst','cod_inst','cod_dep','cod_sector','cod_programa','cod_sub_prog','cod_proyecto','cod_activ_obra','cod_partida','cod_generica','cod_especifica','cod_sub_espec','cod_auxiliar'),'cod_presi,cod_entidad,cod_tipo_inst,cod_inst,cod_dep,cod_sector,cod_programa,cod_sub_prog,cod_proyecto,cod_activ_obra,cod_partida,cod_generica,cod_especifica,cod_sub_espec,cod_auxiliar ASC',null,null,null);
              $this->set('datos_req',$dataCFPD05requerimiento);
        }else{
        	$Tfilas=$this->cfpd05->findCount($this->SQLCA($ano));
        	$Tpag = (int)ceil($Tfilas/250);
          	          $this->set('TotalPaginas',$Tpag);
          	          $this->set('pagina_actual',$pagina);
          	          $this->set('paginacion',$pagina.' / '.$Tpag);
          	          $this->set('ultimo',$Tpag);
          	          $TT=$this->cfpd05->execute("SELECT SUM(asignacion_anual) as total FROM cfpd05 WHERE ".$this->SQLCA($ano));
          	          $this->set("TOTALDISTRIBUCION",$TT[0][0]["total"]);
        	 $dataCFPD05=$this->cfpd05->findAll($this->SQLCA($ano),array('cod_presi','cod_entidad','cod_tipo_inst','cod_inst','cod_dep','cod_sector','cod_programa','cod_sub_prog','cod_proyecto','cod_activ_obra','cod_partida','cod_generica','cod_especifica','cod_sub_espec','cod_auxiliar','cod_tipo_gasto','tipo_presupuesto','asignacion_anual','ano'),'cod_presi,cod_entidad,cod_tipo_inst,cod_inst,cod_dep,cod_sector,cod_programa,cod_sub_prog,cod_proyecto,cod_activ_obra,cod_partida,cod_generica,cod_especifica,cod_sub_espec,cod_auxiliar ASC',250,$pagina,null);
              $this->set('datacfpd05',$dataCFPD05);
              $this->set('siguiente',$pagina+1);
          	  $this->set('anterior',$pagina-1);
              $this->bt_nav($Tpag,$pagina);
             $this->set('errorMessage', 'Este c&oacute;digo presupuestario ya existe');
              $dataCFPD05requerimiento=$this->cfpd05_requerimiento->findAll($this->SQLCA_reque($ano),array('cod_presi','cod_entidad','cod_tipo_inst','cod_inst','cod_dep','cod_sector','cod_programa','cod_sub_prog','cod_proyecto','cod_activ_obra','cod_partida','cod_generica','cod_especifica','cod_sub_espec','cod_auxiliar'),'cod_presi,cod_entidad,cod_tipo_inst,cod_inst,cod_dep,cod_sector,cod_programa,cod_sub_prog,cod_proyecto,cod_activ_obra,cod_partida,cod_generica,cod_especifica,cod_sub_espec,cod_auxiliar ASC',null,null,null);
              $this->set('datos_req',$dataCFPD05requerimiento);
        }
	}else{
		///si entro en esta condicion es porque existe un auxiliar 0 y estan tratando de registrar un auxiliar distinto de cero o viceversa. no se puede crear registro con auxiliar cero, estando registros existentes con auxiliares distintos de cero
		$Tfilas=$this->cfpd05->findCount($this->SQLCA($ano));
        	$Tpag = (int)ceil($Tfilas/250);
          	          $this->set('TotalPaginas',$Tpag);
          	          $this->set('pagina_actual',$pagina);
          	          $this->set('paginacion',$pagina.' / '.$Tpag);
          	          $this->set('ultimo',$Tpag);
          	          $TT=$this->cfpd05->execute("SELECT SUM(asignacion_anual) as total FROM cfpd05 WHERE ".$this->SQLCA($ano));
          	          $this->set("TOTALDISTRIBUCION",$TT[0][0]["total"]);
        	 $dataCFPD05=$this->cfpd05->findAll($this->SQLCA($ano),array('cod_presi','cod_entidad','cod_tipo_inst','cod_inst','cod_dep','cod_sector','cod_programa','cod_sub_prog','cod_proyecto','cod_activ_obra','cod_partida','cod_generica','cod_especifica','cod_sub_espec','cod_auxiliar','cod_tipo_gasto','tipo_presupuesto','asignacion_anual','ano'),'cod_presi,cod_entidad,cod_tipo_inst,cod_inst,cod_dep,cod_sector,cod_programa,cod_sub_prog,cod_proyecto,cod_activ_obra,cod_partida,cod_generica,cod_especifica,cod_sub_espec,cod_auxiliar ASC',250,$pagina,null);
              $this->set('datacfpd05',$dataCFPD05);
              $this->set('siguiente',$pagina+1);
          	  $this->set('anterior',$pagina-1);
              $this->bt_nav($Tpag,$pagina);
              $this->set('errorMessage', 'No se puede guardar el registro');
              $dataCFPD05requerimiento=$this->cfpd05_requerimiento->findAll($this->SQLCA_reque($ano),array('cod_presi','cod_entidad','cod_tipo_inst','cod_inst','cod_dep','cod_sector','cod_programa','cod_sub_prog','cod_proyecto','cod_activ_obra','cod_partida','cod_generica','cod_especifica','cod_sub_espec','cod_auxiliar'),'cod_presi,cod_entidad,cod_tipo_inst,cod_inst,cod_dep,cod_sector,cod_programa,cod_sub_prog,cod_proyecto,cod_activ_obra,cod_partida,cod_generica,cod_especifica,cod_sub_espec,cod_auxiliar ASC',null,null,null);
              $this->set('datos_req',$dataCFPD05requerimiento);
	}
	}else{
		 $ano=$this->Session->read('ano_d_g');
		 $Tfilas=$this->cfpd05->findCount($this->SQLCA($ano));
		 $Tpag = (int)ceil($Tfilas/250);
          	          $this->set('TotalPaginas',$Tpag);
			          $this->set('pagina_actual',$pagina);
          	          $this->set('paginacion',$pagina.' / '.$Tpag);
          	          $this->set('ultimo',$Tpag);
          	          $TT=$this->cfpd05->execute("SELECT SUM(asignacion_anual) as total FROM cfpd05 WHERE ".$this->SQLCA($ano));
          	          $this->set("TOTALDISTRIBUCION",$TT[0][0]["total"]);
		 $dataCFPD05=$this->cfpd05->findAll($this->SQLCA($this->Session->read('ano_d_g')),array('cod_presi','cod_entidad','cod_tipo_inst','cod_inst','cod_dep','cod_sector','cod_programa','cod_sub_prog','cod_proyecto','cod_activ_obra','cod_partida','cod_generica','cod_especifica','cod_sub_espec','cod_auxiliar','cod_tipo_gasto','tipo_presupuesto','asignacion_anual','ano'),'cod_presi,cod_entidad,cod_tipo_inst,cod_inst,cod_dep,cod_sector,cod_programa,cod_sub_prog,cod_proyecto,cod_activ_obra,cod_partida,cod_generica,cod_especifica,cod_sub_espec,cod_auxiliar ASC',250,$pagina,null);
         $this->set('datacfpd05',$dataCFPD05);
             $this->set('siguiente',$pagina+1);
          	 $this->set('anterior',$pagina-1);
             $this->bt_nav($Tpag,$pagina);
		 if(!isset($pagina)){
		 	$this->set('errorMessage', 'No se realiz&oacute; el registro');
		 }
		  $dataCFPD05requerimiento=$this->cfpd05_requerimiento->findAll($this->SQLCA_reque($this->Session->read('ano_d_g')),array('cod_presi','cod_entidad','cod_tipo_inst','cod_inst','cod_dep','cod_sector','cod_programa','cod_sub_prog','cod_proyecto','cod_activ_obra','cod_partida','cod_generica','cod_especifica','cod_sub_espec','cod_auxiliar'),'cod_presi,cod_entidad,cod_tipo_inst,cod_inst,cod_dep,cod_sector,cod_programa,cod_sub_prog,cod_proyecto,cod_activ_obra,cod_partida,cod_generica,cod_especifica,cod_sub_espec,cod_auxiliar ASC',null,null,null);
          $this->set('datos_req',$dataCFPD05requerimiento);
	}


}



 function modificar($dv1,$dv2,$dv3,$dv4,$dv5,$dv6,$dv7,$dv8,$dv9,$dv10,$dv11,$idupdate,$codepr,$montopr){
       $this->layout = "ajax";
       $codigos=array($dv1,$dv2,$dv3,$dv4,$dv5,$dv6,$dv7,$dv8,$dv9,$dv10,$dv11);
       $this->set('cod',$codigos);
       $this->set("ANO",$dv11);
       $this->set('codepr',$codepr);
       $this->set("montopr",$montopr);
       if(isset($this->data)){
       	  if(isset($dv1)){
		   	 $tipo_recurso=$this->data['cfpp05']['tipo_presupuesto_'];
		   	 $tipo_gasto=$this->data['cfpp05']['tipo_gasto'];
		   	 $clasificacion_recurso_extra=$tipo_recurso==5?$this->data['cfpp05']['clasificacion_recurso_extra']:0;

       	  	$condicion=$this->SQLCA($dv11)." and cod_sector=".$dv1." and cod_programa=".$dv2." and cod_sub_prog=".$dv3." and cod_proyecto=".$dv4." and cod_activ_obra=".$dv5." and cod_partida=".$dv6." and cod_generica=".$dv7." and cod_especifica=".$dv8." and cod_sub_espec=".$dv9." and cod_auxiliar=".$dv10;

       	    $this->cfpd05->execute("UPDATE cfpd05 SET asignacion_anual=".$this->Formato1($this->data['cfpp05']['monto']).", tipo_presupuesto=".$tipo_recurso.", cod_tipo_gasto=".$tipo_gasto.", clasificacion_recurso_extra=$clasificacion_recurso_extra WHERE ".$condicion);
       	        $this->set('errorMessage', 'Monto Actualizado con exito');
       	        $resultado=$this->cfpd05->execute("SELECT SUM(asignacion_anual) as total FROM cfpd05 WHERE ".$this->SQLCA($dv11));
                $this->set('TOTAL',$resultado[0][0]['total']);
		        $vec=$this->cfpd05->findAll($condicion,array('cod_tipo_gasto','tipo_presupuesto','asignacion_anual'),null,null,null,null);
				$this->set('id',$idupdate);
       			$this->set('tipo_gasto',$vec[0]['cfpd05']['cod_tipo_gasto']);
       			$this->set('tipo_recurso',$vec[0]['cfpd05']['tipo_presupuesto']);
       			$this->set('monto_campoact',$vec[0]['cfpd05']['asignacion_anual']);

                // echo $this->data['cfpp05']['monto']. "";
       	   //$this->set('eliminar',"Hola esta elimado");
           }else{
               $this->set('errorMessage', 'Dato no Actualizado');
          }
       }else{
       	   //echo "2Hola ".$id;
       }

 }

 function campo_monto($dv1,$dv2,$dv3,$dv4,$dv5,$dv6,$dv7,$dv8,$dv9,$dv10,$dv11,$idupdate,$codepr,$montopr){
       $this->layout = "ajax";
       $condicion=$this->SQLCA($dv11)." and cod_sector=".$dv1." and cod_programa=".$dv2." and cod_sub_prog=".$dv3." and cod_proyecto=".$dv4." and cod_activ_obra=".$dv5." and cod_partida=".$dv6." and cod_generica=".$dv7." and cod_especifica=".$dv8." and cod_sub_espec=".$dv9." and cod_auxiliar=".$dv10;
       $codigos=array($dv1,$dv2,$dv3,$dv4,$dv5,$dv6,$dv7,$dv8,$dv9,$dv10,$dv11);
       $this->set('cod',$codigos);
       $this->set("ANO",$dv11);
       $this->set('codepr',$codepr);
       $this->set("montopr",$montopr);
       //$condicion="nro=".$nro;
       //echo $condicion;
       $vec=$this->cfpd05->findAll($condicion,array('asignacion_anual','tipo_presupuesto','cod_tipo_gasto','clasificacion_recurso_extra'),null,null,null,null);
       $monto=$vec[0]['cfpd05']['asignacion_anual'];
       $this->set('ValorMonto',$monto);
       $this->set('tipo_gasto',$vec[0]['cfpd05']['cod_tipo_gasto']);
       $this->set('tipo_recurso',$vec[0]['cfpd05']['tipo_presupuesto']);
       $this->set('id',$idupdate);
       $this->set('clasificacion_recurso_extra',$vec[0]['cfpd05']['clasificacion_recurso_extra']);
 }

function mostrar_monto($dv1,$dv2,$dv3,$dv4,$dv5,$dv6,$dv7,$dv8,$dv9,$dv10,$dv11,$idupdate,$codepr,$montopr){
       $this->layout = "ajax";
       $condicion=$this->SQLCA($dv11)." and cod_sector=".$dv1." and cod_programa=".$dv2." and cod_sub_prog=".$dv3." and cod_proyecto=".$dv4." and cod_activ_obra=".$dv5." and cod_partida=".$dv6." and cod_generica=".$dv7." and cod_especifica=".$dv8." and cod_sub_espec=".$dv9." and cod_auxiliar=".$dv10;
       $codigos=array($dv1,$dv2,$dv3,$dv4,$dv5,$dv6,$dv7,$dv8,$dv9,$dv10,$dv11);
       $this->set('cod',$codigos);
       $this->set("ANO",$dv11);
       $this->set('codepr',$codepr);
       $this->set("montopr",$montopr);
       //$condicion="nro=".$nro;
       //echo $condicion;
       $vec=$this->cfpd05->findAll($condicion,array('asignacion_anual','tipo_presupuesto','cod_tipo_gasto','clasificacion_recurso_extra'),null,null,null,null);
       $monto=$vec[0]['cfpd05']['asignacion_anual'];
       $this->set('ValorMonto',$monto);
       $this->set('tipo_gasto',$vec[0]['cfpd05']['cod_tipo_gasto']);
       $this->set('tipo_recurso',$vec[0]['cfpd05']['tipo_presupuesto']);
       $this->set('id',$idupdate);
       $this->set('clasificacion_recurso_extra',$vec[0]['cfpd05']['clasificacion_recurso_extra']);
 }

 function eliminar($dv1,$dv2,$dv3,$dv4,$dv5,$dv6,$dv7,$dv8,$dv9,$dv10,$dv11){
       $this->layout = "ajax";
       if(isset($dv1)){
       	$condicion=$this->SQLCA($dv11)." and cod_sector=".$dv1." and cod_programa=".$dv2." and cod_sub_prog=".$dv3." and cod_proyecto=".$dv4." and cod_activ_obra=".$dv5." and cod_partida=".$dv6." and cod_generica=".$dv7." and cod_especifica=".$dv8." and cod_sub_espec=".$dv9." and cod_auxiliar=".$dv10." and compromiso_anual=0 and aumento_traslado_anual=0 and disminucion_traslado_anual=0 and credito_adicional_anual=0 and rebaja_anual=0 and causado_anual=0 and pagado_anual=0";
       	   $c_m=$this->cfpd05->findCount($condicion);
       	   if($c_m!=0){
       	   	  $this->cfpd05->execute("DELETE FROM cfpd05  WHERE ".$condicion);
       	   	  $this->set('Message_existe', 'Dato Eliminado con exito');
       	      $resultado=$this->cfpd05->execute("SELECT SUM(asignacion_anual) as total FROM cfpd05 WHERE ".$this->SQLCA($dv11));
              $this->set('TOTAL',$resultado[0][0]['total']);
       	   }else{
              $this->set('errorMessage', 'El Registro tiene movimientos, no se puede eliminar');
       	      $resultado=$this->cfpd05->execute("SELECT SUM(asignacion_anual) as total FROM cfpd05 WHERE ".$this->SQLCA($dv11));
              $this->set('TOTAL',$resultado[0][0]['total']);
       	   }


       }else{
            $this->set('errorMessage', 'Dato no Eliminado');
       }
 }

 function dataGrid($vano=null,$pagina=null){
 	$this->layout = "ajax";
 	if($pagina!=null && $vano!=null){
          	 $pagina=$pagina;
          	 $Ano=$vano;
          	 $this->set('ejercicio', $Ano);
          	 $this->set('ano',$Ano);
          	 $Tfilas=$this->cfpd05->findCount($this->SQLCA($Ano));
          	 $Tpag = (int)ceil($Tfilas/10);
          	 $dataCFPD05=$this->cfpd05->findAll($this->SQLCA($Ano),null,'cod_presi,cod_entidad,cod_tipo_inst,cod_inst,cod_dep,cod_sector,cod_programa,cod_sub_prog,cod_proyecto,cod_activ_obra,cod_partida,cod_generica,cod_especifica,cod_sub_espec,cod_auxiliar ASC',10,$pagina,null);
          	 $this->set('datacfpd05',$dataCFPD05);
          	 //print_r($dataCFPD05);
          	 //echo "<br>p: ".$pagina." tp:".$Tpag." tf:".$Tfilas;
          	 //if($Tpag>=3){
                if($Tpag==$pagina){
                  $this->set('mostrarS',false);
                  $this->set('mostrarA',true);
          	    }else if($pagina==1 && $pagina==$Tpag){
                  $this->set('mostrarS',false);
                  $this->set('mostrarA',false);
          	    }else if($pagina==1 && $pagina<$Tpag){
          		  $this->set('mostrarS',true);
                  $this->set('mostrarA',false);
          	    }
          	 $this->set('siguiente',$pagina+1);
          	 $this->set('anterior',$pagina-1);
          }//fin if
 }//fin dataGrid

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

function pasar_ejercicio_form () {

$this->verifica_entrada('21');

	$this->layout="ajax";
	$cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst;

	$year = $this->cfpd01_formulacion->findAll($condicion, null, 'ano_formular ASC', null);
	$dato = null;
	foreach($year as $ye){
		$dato = $ye['cfpd01_formulacion']['ano_formular'];
	}

	if(!empty($dato)){
		$this->set('ano_formular', $dato);
	}else{
		$this->set('ano_formular', '');
	}
}

function pasar_ejercicio () {
	$this->layout="ajax";
	$ano=$this->data["cfpp05"]["ano"];
	$ano_formular=$this->data["cfpp05"]["ano_pasar_ejercicio"];
	//echo $ano." ".$ano_formular;
	$cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst;
$INSERT="INSERT INTO ";
$CAMPOS=" (cod_presi,cod_entidad,cod_tipo_inst,cod_inst,cod_dep,ano,cod_sector,cod_programa,cod_sub_prog,cod_proyecto,cod_activ_obra,cod_partida,cod_generica,cod_especifica,cod_sub_espec,cod_auxiliar,cod_tipo_gasto,tipo_presupuesto,asignacion_anual,aumento_traslado_anual,
disminucion_traslado_anual,
credito_adicional_anual,
rebaja_anual,
compromiso_anual,
causado_anual,
pagado_anual,
asignacion_ene,
aumento_traslado_ene,
disminucion_traslado_ene,
credito_adicional_ene,
rebaja_ene,
compromiso_ene,
causado_ene,
pagado_ene,
asignacion_feb,
aumento_traslado_feb,
disminucion_traslado_feb,
credito_adicional_feb,
rebaja_feb,
compromiso_feb,
causado_feb,
pagado_feb,
asignacion_mar,
aumento_traslado_mar,
disminucion_traslado_mar,
credito_adicional_mar,
rebaja_mar,
compromiso_mar,
causado_mar,
pagado_mar,
asignacion_abr,
aumento_traslado_abr,
disminucion_traslado_abr,
credito_adicional_abr,
rebaja_abr,
compromiso_abr,
causado_abr,
pagado_abr,
asignacion_may,
aumento_traslado_may,
disminucion_traslado_may,
credito_adicional_may,
rebaja_may,
compromiso_may,
causado_may,
pagado_may,
asignacion_jun,
aumento_traslado_jun,
disminucion_traslado_jun,
credito_adicional_jun,
rebaja_jun,
compromiso_jun,
causado_jun,
pagado_jun,
asignacion_jul,
disminucion_traslado_jul,
credito_adicional_jul,
rebaja_jul,
compromiso_jul,
causado_jul,
pagado_jul,
asignacion_ago,
aumento_traslado_ago,
disminucion_traslado_ago,
credito_adicional_ago,
rebaja_ago,
compromiso_ago,
causado_ago,
pagado_ago,
asignacion_sep,
aumento_traslado_sep,
disminucion_traslado_sep,
credito_adicional_sep,
rebaja_sep,
compromiso_sep,
causado_sep,
pagado_sep,
asignacion_oct,
aumento_traslado_oct,
disminucion_traslado_oct,
credito_adicional_oct,
rebaja_oct,
compromiso_oct,
causado_oct,
pagado_oct,
asignacion_nov,
aumento_traslado_nov,
disminucion_traslado_nov,
credito_adicional_nov,
rebaja_nov,
compromiso_nov,
causado_nov,
pagado_nov,
asignacion_dic,
aumento_traslado_dic,
disminucion_traslado_dic,
credito_adicional_dic,
rebaja_dic,
compromiso_dic,
causado_dic,
pagado_dic,
precompromiso_congelado,
precompromiso_requisicion,
precompromiso_obras,
precompromiso_fondo_avance) VALUES";
        //$SQLINSERT .="($Cpresi,$Centidad,$Ctipo_inst,$Cinst,$Cdep,$ano,$Csector,$Cprograma,$Csubprograma,$Cproyecto,$Cactividad,$Cpartida,$Cgenerica,$Cespecifica,$Csub_espec,$Cauxiliar,$Ctipo_gasto,$tipo_presupuesto,$asignacion_anual,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0)";


	//if($this->cfpd05->findCount($condicion." and ano=".$ano_formular)==0){
         $this->traspaso_a_otros($ano, $ano_formular);
         $this->traspaso_clasificador_partidas($ano,$ano_formular);
         $this->pasar_ejercicio_auxiliares($ano,$ano_formular);
         $resultado_cfpd05=$this->cfpd05->findAll($condicion." and ano=".$ano."");
         foreach($resultado_cfpd05 as $rs){
            $v=$rs["cfpd05"];
            if($this->cfpd05->findCount($condicion." and ano=".$ano_formular." and cod_dep=".$v["cod_dep"]." and cod_sector=".$v["cod_sector"]." and cod_programa=".$v["cod_programa"]." and cod_sub_prog=".$v["cod_sub_prog"]." and cod_proyecto=".$v["cod_proyecto"]." and cod_activ_obra=".$v["cod_activ_obra"]." and cod_partida=".$v["cod_partida"]." and cod_generica=".$v["cod_generica"]." and cod_especifica=".$v["cod_especifica"]." and cod_sub_espec=".$v["cod_sub_espec"]." and cod_auxiliar=".$v["cod_auxiliar"]) == 0){
            	$values_cfpd05[]="(".$v["cod_presi"].",".$v["cod_entidad"].",".$v["cod_tipo_inst"].",".$v["cod_inst"].",".$v["cod_dep"].",$ano_formular,".$v["cod_sector"].",".$v["cod_programa"].",".$v["cod_sub_prog"].",".$v["cod_proyecto"].",".$v["cod_activ_obra"].",".$v["cod_partida"].",".$v["cod_generica"].",".$v["cod_especifica"].",".$v["cod_sub_espec"].",".$v["cod_auxiliar"].",".$v["cod_tipo_gasto"].",".$v["tipo_presupuesto"].",".$v["asignacion_anual"].",0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0)";
            }
         }
         if(isset($values_cfpd05) && is_array($values_cfpd05)){
	         $VALUES_CFPD05=implode(',', $values_cfpd05);
	         $a=$this->cfpd05->execute($INSERT." cfpd05 ".$CAMPOS." ".$VALUES_CFPD05);
	         $this->cfpd05->execute("DELETE FROM cfpd05 where $condicion and ano=".$ano_formular." and cod_partida=404");
	         $this->cfpd05->execute("DELETE FROM cfpd05 where $condicion and ano=".$ano_formular." and cod_partida=403 and cod_generica=18 and cod_especifica=1");
	         $this->cfpd05->execute("DELETE FROM cfpd05 where $condicion and ano=".$ano_formular." and cod_partida=411");
	         $this->cfpd05->execute("DELETE FROM cfpd05 where $condicion and ano=".$ano_formular." and cod_partida=407 and cod_generica!=1");
	         $cantidad_cfpd05_nuevos=$this->cfpd05->findCount($condicion." and ano=".$ano_formular."");
	         echo "<b><u>Distribucion Institucional del gasto</u></b><br>";
	         echo "Cantidad de registros ejercicio (".$ano."): ".count($resultado_cfpd05)." <br />Registros pasados al ejercicio (".$ano_formular."): ".$cantidad_cfpd05_nuevos;

         }else{
         	 echo "<b><u>Distribucion Institucional del gasto</u></b><br>";
	         echo "Cantidad de registros ejercicio (".$ano."): ".count($resultado_cfpd05)." <br />Registros pasados al ejercicio (".$ano_formular."): 0";
         }
         $resultado_cfpd05_requerimiento=$this->cfpd05_requerimiento->findAll($condicion." and ano=".$ano." and cod_partida!=404 and (cod_partida!=403 and cod_generica!=18 and cod_especifica!=1) and cod_partida!=411 or (cod_partida=407 and cod_generica=1) and (cod_partida=407 and cod_generica!=1)");
         foreach($resultado_cfpd05_requerimiento as $rs){
            $v=$rs["cfpd05_requerimiento"];
            if($this->cfpd05_requerimiento->findCount($condicion." and ano=".$ano_formular." and cod_dep=".$v["cod_dep"]." and cod_sector=".$v["cod_sector"]." and cod_programa=".$v["cod_programa"]." and cod_sub_prog=".$v["cod_sub_prog"]." and cod_proyecto=".$v["cod_proyecto"]." and cod_activ_obra=".$v["cod_activ_obra"]." and cod_partida=".$v["cod_partida"]." and cod_generica=".$v["cod_generica"]." and cod_especifica=".$v["cod_especifica"]." and cod_sub_espec=".$v["cod_sub_espec"]." and cod_auxiliar=".$v["cod_auxiliar"]) == 0){
            	$values_cfpd05_req[]="(".$v["cod_presi"].",".$v["cod_entidad"].",".$v["cod_tipo_inst"].",".$v["cod_inst"].",".$v["cod_dep"].",$ano_formular,".$v["cod_sector"].",".$v["cod_programa"].",".$v["cod_sub_prog"].",".$v["cod_proyecto"].",".$v["cod_activ_obra"].",".$v["cod_partida"].",".$v["cod_generica"].",".$v["cod_especifica"].",".$v["cod_sub_espec"].",".$v["cod_auxiliar"].",".$v["cod_tipo_gasto"].",".$v["tipo_presupuesto"].",".$v["asignacion_anual"].",0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0)";
            }
         }
         if(isset($values_cfpd05_req) && is_array($values_cfpd05_req)){
	         $VALUES_CFPD05_REQ=implode(',', $values_cfpd05_req);
	         $b=$this->cfpd05_requerimiento->execute($INSERT." cfpd05_requerimiento ".$CAMPOS." ".$VALUES_CFPD05_REQ);
	         //$this->set('Message_existe', 'El datos han sido pasados existosamente al ejercicio '.$ano_formular);
	         $this->cfpd05_requerimiento->execute("DELETE FROM cfpd05_requerimiento where $condicion and ano=".$ano_formular." and cod_partida=404");
	         $this->cfpd05_requerimiento->execute("DELETE FROM cfpd05_requerimiento where $condicion and ano=".$ano_formular." and cod_partida=403 and cod_generica=18 and cod_especifica=1");
	         $this->cfpd05_requerimiento->execute("DELETE FROM cfpd05_requerimiento where $condicion and ano=".$ano_formular." and cod_partida=411");
	         $this->cfpd05_requerimiento->execute("DELETE FROM cfpd05_requerimiento where $condicion and ano=".$ano_formular." and cod_partida=407 and cod_generica!=1");
	         $cantidad_cfpd05req_nuevos=$this->cfpd05_requerimiento->findCount($condicion." and ano=".$ano_formular."");
             echo "<br><b><u>Distribución Institucional del gasto (Requerimientos especiales)</u></b><br>";
	         echo "Cantidad de registros ejercicio (".$ano."): ".count($resultado_cfpd05_requerimiento)." <br />Registros pasados al ejercicio (".$ano_formular."): ".$cantidad_cfpd05req_nuevos;

         }else{
             echo "<br><b><u>Distribución Institucional del gasto (Requerimientos especiales)</u></b><br>";
	         echo "Cantidad de registros ejercicio (".$ano."): ".count($resultado_cfpd05_requerimiento)." <br />Registros pasados al ejercicio (".$ano_formular."): 0";
         }

	/*}else{
         $this->set('errorMessage', 'Ya existe presupuesto para el ejercicio '.$ano_formular);
	}*/

echo "&nbsp;";

}

function traspaso_a_otros($ejercicio_desde=null, $ejercicio_hasta=null){
    ///funcion que hace el traspaso de los sectores,programas, subprogramas,proyectos, actividad u obra a otro ejercicio presupuestario
    //re-escrita
	$cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');

    if(isset($ejercicio_desde) && $ejercicio_desde!=null && isset($ejercicio_hasta) && $ejercicio_hasta!=null){
        $ejercicio_aux = $ejercicio_hasta;
	    $ejercicio = $ejercicio_desde;
    }else{
    	$ejercicio_aux = $this->data['cfpd02']['al'];
	    $ejercicio = $this->data['cfpd02']['de'];
    }
	$this->set('ejercicio', $ejercicio_aux);
	$condicion1 = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst."  and ano = ".$ejercicio;


	$sector = $this->cfpd02_sector->findAll($condicion1, null, 'cod_dep,cod_sector ASC', null, null, null);
	foreach($sector as $datos){
		$cp= $datos['cfpd02_sector']['cod_presi'];
		$ce= $datos['cfpd02_sector']['cod_entidad'];
		$ct= $datos['cfpd02_sector']['cod_tipo_inst'];
		$ci= $datos['cfpd02_sector']['cod_inst'];
		$cd= $datos['cfpd02_sector']['cod_dep'];
		$var1 = $datos['cfpd02_sector']['cod_sector'];
		$denominacion = $datos['cfpd02_sector']['denominacion'];
		$unidad_ejecutora = $datos['cfpd02_sector']['unidad_ejecutora'];
		$objetivo = $datos['cfpd02_sector']['objetivo'];
		$funcionario_responsable = $datos['cfpd02_sector']['funcionario_responsable'];
		$condicion2 = "cod_presi = ".$cp." and cod_entidad = ".$ce." and cod_tipo_inst = ".$ct." and cod_inst = ".$ci." and cod_dep=".$cp."  and ano = ".$ejercicio_aux;
		$sql_aux = $condicion2." and cod_sector='".$var1."' ";
		if($this->cfpd02_sector->findCount($sql_aux) == 0){
			$values_sector[] = " ( '$cp', '$ce', '$ct', '$ci', '$cd', '$ejercicio_aux', '$var1', '$denominacion', '$unidad_ejecutora', '$objetivo', '$funcionario_responsable')  ";
		}
	}//fin foreach
	if(isset($values_sector) && is_array($values_sector)){
		$this->cfpd02_sector->execute("INSERT INTO  cfpd02_sector VALUES  ".implode(',',$values_sector).";");
	}


	$programa = $this->cfpd02_programa->findAll($condicion1, null, 'cod_dep,cod_sector,cod_programa ASC', null, null, null);
	foreach($programa as $datos){
		$cp= $datos['cfpd02_programa']['cod_presi'];
		$ce= $datos['cfpd02_programa']['cod_entidad'];
		$ct= $datos['cfpd02_programa']['cod_tipo_inst'];
		$ci= $datos['cfpd02_programa']['cod_inst'];
		$cd= $datos['cfpd02_programa']['cod_dep'];
		$var1 = $datos['cfpd02_programa']['cod_sector'];
		$var2 = $datos['cfpd02_programa']['cod_programa'];
		$denominacion = $datos['cfpd02_programa']['denominacion'];
		$unidad_ejecutora = $datos['cfpd02_programa']['unidad_ejecutora'];
		$objetivo = $datos['cfpd02_programa']['objetivo'];
		$funcionario_responsable = $datos['cfpd02_programa']['funcionario_responsable'];
		$condicion2 = "cod_presi = ".$cp." and cod_entidad = ".$ce." and cod_tipo_inst = ".$ct." and cod_inst = ".$ci." and cod_dep=".$cp."  and ano = ".$ejercicio_aux;
		$sql_aux = $condicion2." and cod_sector='".$var1."' and cod_programa = '".$var2."' ";
 		if($this->cfpd02_programa->findCount($sql_aux) == 0){
			$values_prog[]= "( '$cp', '$ce', '$ct', '$ci', '$cd', '$ejercicio_aux', '$var1', '$var2', '$denominacion', '$unidad_ejecutora', '$objetivo', '$funcionario_responsable')  ";
		}
	}//fin foreach
	if(isset($values_prog) && is_array($values_prog)){
    	$this->cfpd02_programa->execute("INSERT INTO  cfpd02_programa VALUES  ".implode(',',$values_prog).";");
	}

	$sub_prog = $this->cfpd02_sub_prog->findAll($condicion1, null, 'cod_dep,cod_sector,cod_programa,cod_sub_prog ASC', null, null, null);
	foreach($sub_prog as $datos){
		$cp= $datos['cfpd02_sub_prog']['cod_presi'];
		$ce= $datos['cfpd02_sub_prog']['cod_entidad'];
		$ct= $datos['cfpd02_sub_prog']['cod_tipo_inst'];
		$ci= $datos['cfpd02_sub_prog']['cod_inst'];
		$cd= $datos['cfpd02_sub_prog']['cod_dep'];
		$var1 = $datos['cfpd02_sub_prog']['cod_sector'];
		$var2 = $datos['cfpd02_sub_prog']['cod_programa'];
		$var3 = $datos['cfpd02_sub_prog']['cod_sub_prog'];
		$denominacion = $datos['cfpd02_sub_prog']['denominacion'];
		$unidad_ejecutora = $datos['cfpd02_sub_prog']['unidad_ejecutora'];
		$objetivo = $datos['cfpd02_sub_prog']['objetivo'];
		$funcionario_responsable = $datos['cfpd02_sub_prog']['funcionario_responsable'];
		$condicion2 = "cod_presi = ".$cp." and cod_entidad = ".$ce." and cod_tipo_inst = ".$ct." and cod_inst = ".$ci." and cod_dep=".$cp."  and ano = ".$ejercicio_aux;
		$sql_aux = $condicion2." and cod_sector='".$var1."' and cod_programa = '".$var2."' and  cod_sub_prog='".$var3."'  ";
		if($this->cfpd02_sub_prog->findCount($sql_aux) == 0){
 			$values_sub_prog[] = " ( '$cp', '$ce', '$ct', '$ci', '$cd', '$ejercicio_aux', '$var1', '$var2', '$var3', '$denominacion', '$unidad_ejecutora', '$objetivo', '$funcionario_responsable')  ";
 		}
	}//fin foreach
	if(isset($values_sub_prog) && is_array($values_sub_prog)){
    	$this->cfpd02_sub_prog->execute("INSERT INTO  cfpd02_sub_prog VALUES  ".implode(',',$values_sub_prog).";");
	}

	$proyecto = $this->cfpd02_proyecto->findAll($condicion1, null, 'cod_dep,cod_sector,cod_programa,cod_sub_prog,cod_proyecto ASC', null, null, null);
	foreach($proyecto as $datos){
		$cp= $datos['cfpd02_proyecto']['cod_presi'];
		$ce= $datos['cfpd02_proyecto']['cod_entidad'];
		$ct= $datos['cfpd02_proyecto']['cod_tipo_inst'];
		$ci= $datos['cfpd02_proyecto']['cod_inst'];
		$cd= $datos['cfpd02_proyecto']['cod_dep'];
		$var1 = $datos['cfpd02_proyecto']['cod_sector'];
		$var2 = $datos['cfpd02_proyecto']['cod_programa'];
		$var3 = $datos['cfpd02_proyecto']['cod_sub_prog'];
		$var4 = $datos['cfpd02_proyecto']['cod_proyecto'];
		$denominacion = $datos['cfpd02_proyecto']['denominacion'];
		$unidad_ejecutora = $datos['cfpd02_proyecto']['unidad_ejecutora'];
		$objetivo = $datos['cfpd02_proyecto']['objetivo'];
		$funcionario_responsable = $datos['cfpd02_proyecto']['funcionario_responsable'];
		$condicion2 = "cod_presi = ".$cp." and cod_entidad = ".$ce." and cod_tipo_inst = ".$ct." and cod_inst = ".$ci." and cod_dep=".$cp."  and ano = ".$ejercicio_aux;
		$sql_aux = $condicion2." and cod_sector='".$var1."' and cod_programa = '".$var2."' and  cod_sub_prog='".$var3."' and  cod_proyecto='".$var4."'   ";
 		if($this->cfpd02_proyecto->findCount($sql_aux) == 0){
 			$values_proyecto[] = " ( '$cp', '$ce', '$ct', '$ci', '$cd', '$ejercicio_aux', '$var1', '$var2', '$var3', '$var4', '$denominacion', '$unidad_ejecutora', '$objetivo', '$funcionario_responsable')  ";
		}
 	}//fin foreach
 	if(isset($values_proyecto) && is_array($values_proyecto)){
    	$this->cfpd02_proyecto->execute("INSERT INTO  cfpd02_proyecto VALUES  ".implode(',',$values_proyecto).";");
 	}

	$activ_obra = $this->cfpd02_activ_obra->findAll($condicion1, null, 'cod_dep,cod_sector,cod_programa,cod_sub_prog,cod_proyecto,cod_activ_obra ASC', null, null, null);
	foreach($activ_obra as $datos){
		$cp= $datos['cfpd02_activ_obra']['cod_presi'];
		$ce= $datos['cfpd02_activ_obra']['cod_entidad'];
		$ct= $datos['cfpd02_activ_obra']['cod_tipo_inst'];
		$ci= $datos['cfpd02_activ_obra']['cod_inst'];
		$cd= $datos['cfpd02_activ_obra']['cod_dep'];
		$var1 = $datos['cfpd02_activ_obra']['cod_sector'];
		$var2 = $datos['cfpd02_activ_obra']['cod_programa'];
		$var3 = $datos['cfpd02_activ_obra']['cod_sub_prog'];
		$var4 = $datos['cfpd02_activ_obra']['cod_proyecto'];
		$var5 = $datos['cfpd02_activ_obra']['cod_activ_obra'];
		$denominacion = $datos['cfpd02_activ_obra']['denominacion'];
		$unidad_ejecutora = $datos['cfpd02_activ_obra']['unidad_ejecutora'];
		$objetivo = $datos['cfpd02_activ_obra']['objetivo'];
		$titulo = $datos['cfpd02_activ_obra']['titulo'];
		$funcionario_responsable = $datos['cfpd02_activ_obra']['funcionario_responsable'];
		$condicion2 = "cod_presi = ".$cp." and cod_entidad = ".$ce." and cod_tipo_inst = ".$ct." and cod_inst = ".$ci." and cod_dep=".$cp."  and ano = ".$ejercicio_aux;
		$sql_aux = $condicion2." and cod_sector='".$var1."' and cod_programa = '".$var2."' and  cod_sub_prog='".$var3."' and  cod_proyecto='".$var4."' and cod_activ_obra='".$var5."'  ";
		if($this->cfpd02_activ_obra->findCount($sql_aux) == 0){
			$values_activ[] = " ( '$cp', '$ce', '$ct', '$ci', '$cd', '$ejercicio_aux', '$var1', '$var2', '$var3', '$var4', '$var5', '$denominacion', '$unidad_ejecutora', '$objetivo', '$titulo', '$funcionario_responsable')  ";
		}
 	}//fin foreach
 	if(isset($values_activ) && is_array($values_activ)){
    	$this->cfpd02_activ_obra->execute("INSERT INTO  cfpd02_activ_obra VALUES  ".implode(',',$values_activ).";");
 	}
    //$this->set('Message_existe', 'Registro categoria programatica pasados exitosamente');
}//fin function

function pasar_ejercicio_auxiliares ($ano=null,$ano_traspaso=null) {
	$cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst;
    $CAMPOS_AUXILIARES="cod_presi,cod_entidad,cod_tipo_inst,cod_inst,cod_dep,ano,cod_sector,cod_programa,cod_sub_prog,cod_proyecto,cod_activ_obra,cod_partida,cod_generica,cod_especifica,cod_sub_espec,cod_auxiliar,denominacion";
    if(isset($ano_traspaso) && isset($ano)){
           $ano_ejercicio=$ano;
           $ano_ejercicio_pasar=$ano_traspaso;
           //if($this->cfpp05auxiliar->findCount("ano=".$ano_ejercicio_pasar)==0){
           	if($this->cfpp05auxiliar->findCount($condicion." and ano=".$ano_ejercicio)!=0){
           		$resultados=$this->cfpp05auxiliar->findAll($condicion." and ano=".$ano_ejercicio." and cod_partida not in (404,411)");
				foreach($resultados as $rs){
					$v=$rs["cfpp05auxiliar"];
					if($this->cfpp05auxiliar->findCount($condicion." and ano=".$ano_ejercicio_pasar." and cod_dep=".$v["cod_dep"]." and cod_sector=".$v["cod_sector"]." and cod_programa=".$v["cod_programa"]." and cod_sub_prog=".$v["cod_sub_prog"]." and cod_proyecto=".$v["cod_proyecto"]." and cod_activ_obra=".$v["cod_activ_obra"]." and cod_partida=".$v["cod_partida"]." and cod_generica=".$v["cod_generica"]." and cod_especifica=".$v["cod_especifica"]." and cod_sub_espec=".$v["cod_sub_espec"]." and cod_auxiliar=".$v["cod_auxiliar"]) == 0){
                 		$values[]="(".$v["cod_presi"].",".$v["cod_entidad"].",".$v["cod_tipo_inst"].",".$v["cod_inst"].",".$v["cod_dep"].",".$ano_ejercicio_pasar.",".$v["cod_sector"].",".$v["cod_programa"].",".$v["cod_sub_prog"].",".$v["cod_proyecto"].",".$v["cod_activ_obra"].",".$v["cod_partida"].",".$v["cod_generica"].",".$v["cod_especifica"].",".$v["cod_sub_espec"].",".$v["cod_auxiliar"].",'".$v["denominacion"]."')";
					}
             	}//fin foreach new array
 	            if(isset($values) && is_array($values)){
    	           $VALUES_AUXILIARES=implode(',', $values);
             	   $SQL="INSERT INTO cfpd05_auxiliar (".$CAMPOS_AUXILIARES.") VALUES ".$VALUES_AUXILIARES."; ";
             	   $resultado_insert=$this->cfpp05auxiliar->execute($SQL);
             	   $this->cfpp05auxiliar->execute("DELETE FROM cfpd05_auxiliar where $condicion and ano=".$ano_ejercicio_pasar." and cod_partida=403 and cod_generica=18 and cod_especifica=1");
	                $this->cfpp05auxiliar->execute("DELETE FROM cfpd05_auxiliar where $condicion and ano=".$ano_ejercicio_pasar." and cod_partida=407 and cod_generica!=1");
 	            }else{
                    $resultado_insert="";
 	            }
             	if($resultado_insert>1){
             		 $this->set('Message_existe', 'Registro pasados exitosamente  ');
             	}else{
             		 $this->set('errorMessage', 'Fall&oacute; el traspaso de los registros auxiliares');
             	}
           	}
           /*}else{
           	  $this->set('errorMessage', 'Fall&oacute; el traspaso, ya existe registro para el año indicado');
           }*/
    }else{
    	//$this->set('errorMessage', 'Debe ingresar los años correctamente');
    }
}//fin funcion pasar_ejercicio_auxiliares

function traspaso_clasificador_partidas($ejercicio_desde=null, $ejercicio_hasta=null){
         $ejercicio_aux = $ejercicio_hasta;
	     $ejercicio = $ejercicio_desde;

	     $grupo = $this->cfpd01_ano_grupo->findAll('ejercicio='.$ejercicio.'', null, 'cod_grupo ASC', null, null, null);
		 foreach($grupo as $datos){
		     $var1 = $datos['cfpd01_ano_grupo']['cod_grupo'];
			 $concepto = $datos['cfpd01_ano_grupo']['concepto'];
			 $descripcion = $datos['cfpd01_ano_grupo']['denominacion'];
			 $sql_aux = "ejercicio = ".$ejercicio_aux." and cod_grupo='".$var1."'  ";
			 if($this->cfpd01_ano_grupo->findCount($sql_aux) == 0){
				$sql_1 = "INSERT INTO  cfpd01_ano_1_grupo  (ejercicio,cod_grupo,concepto, denominacion)   VALUES  ( '".$ejercicio_aux."',".$var1.",  '$concepto', '$descripcion' )  ";
			    $this->cfpd01_ano_grupo->execute($sql_1);
			}
		 }//fin foreach

		  $partida = $this->cfpd01_ano_partida->findAll('ejercicio='.$ejercicio.'', null, 'cod_partida ASC', null, null, null);
		  foreach($partida as $datos){
		     $var1 = $datos['cfpd01_ano_partida']['cod_grupo'];
			 $var2 = $datos['cfpd01_ano_partida']['cod_partida'];
			 $concepto = $datos['cfpd01_ano_partida']['concepto'];
			 $descripcion = $datos['cfpd01_ano_partida']['denominacion'];
		     $values =  " '".$var1."', '".$var2."',  ";
             $sql_aux = "ejercicio = ".$ejercicio_aux." and cod_grupo='".$var1."' and  cod_partida='".$var2."'  ";
			 if($this->cfpd01_ano_partida->findCount($sql_aux) == 0){
			    $sql_1 = "INSERT INTO  cfpd01_ano_2_partida   (ejercicio,cod_grupo, cod_partida,concepto, denominacion)   VALUES  ( '".$ejercicio_aux."',  ".$values."   '$concepto', '$descripcion' )  ";
			    $this->cfpd01_ano_partida->execute($sql_1);
			 }
		 }//fin foreach

		 $generica = $this->cfpd01_ano_generica->findAll('ejercicio='.$ejercicio.'', null, 'cod_generica ASC', null, null, null);
		 foreach($generica as $datos){
		     $var1 = $datos['cfpd01_ano_generica']['cod_grupo'];
			 $var2 = $datos['cfpd01_ano_generica']['cod_partida'];
			 $var3 = $datos['cfpd01_ano_generica']['cod_generica'];
			 $concepto = $datos['cfpd01_ano_generica']['concepto'];
			 $descripcion = $datos['cfpd01_ano_generica']['denominacion'];
			 $values =  " '".$var1."', '".$var2."', '".$var3."', ";
			 $sql_aux = "ejercicio = ".$ejercicio_aux." and cod_grupo='".$var1."' and  cod_partida='".$var2."' and  cod_generica='".$var3."'  ";
			 if($this->cfpd01_ano_generica->findCount($sql_aux) == 0){
			   $values_generica[] = "( '".$ejercicio_aux."',  ".$values."   '$concepto', '$descripcion' )  ";
			 }
		 }//fin foreach
		 if(isset($values_generica) && is_array($values_generica)){
		     $this->cfpd01_ano_generica->execute("INSERT INTO  cfpd01_ano_3_generica (ejercicio,cod_grupo,  cod_partida,  cod_generica, concepto, denominacion)   VALUES  ".implode(',',$values_generica).";");
		 }

		 $especifica = $this->cfpd01_ano_especifica->findAll('ejercicio='.$ejercicio.'', null, 'cod_especifica ASC', null, null, null);
		 foreach($especifica as $datos){
		     $var1 = $datos['cfpd01_ano_especifica']['cod_grupo'];
			 $var2 = $datos['cfpd01_ano_especifica']['cod_partida'];
			 $var3 = $datos['cfpd01_ano_especifica']['cod_generica'];
			 $var4 = $datos['cfpd01_ano_especifica']['cod_especifica'];
			 $concepto = $datos['cfpd01_ano_especifica']['concepto'];
			 $descripcion = $datos['cfpd01_ano_especifica']['denominacion'];
			 $values =  " '".$var1."',  '".$var2."',    '".$var3."', '".$var4."', ";
			 $sql_aux = "ejercicio = ".$ejercicio_aux." and cod_grupo='".$var1."' and  cod_partida='".$var2."' and  cod_generica='".$var3."' and  cod_especifica='".$var4."'   ";
			 if($this->cfpd01_ano_especifica->findCount($sql_aux) == 0){
			    $values_especifica[] = "( '".$ejercicio_aux."',  ".$values."   '$concepto', '$descripcion' )  ";
			 }

             $sql_aux = "ejercicio = ".$ejercicio_aux." and cod_grupo='".$var1."' and  cod_partida='".$var2."' and  cod_generica='".$var3."' and  cod_especifica='".$var4."' and cod_sub_espec='0'  ";
             if($this->cfpd01_ano_sub_espec->findCount($sql_aux) == 0){
                $values_aux =  " '".$var1."',  '".$var2."',   '".$var3."', '".$var4."', '0', ";
                $values_sub_espec[] = "( '".$ejercicio_aux."',  ".$values_aux."   '$concepto', '$descripcion' )  ";
             }
        }//fin foreach
        if(isset($values_especifica) && is_array($values_especifica)){
            $this->cfpd01_ano_especifica->execute("INSERT INTO  cfpd01_ano_4_especifica   (ejercicio,cod_grupo, cod_partida, cod_generica, cod_especifica,concepto, denominacion)   VALUES  ".implode(',',$values_especifica).";");
        }
        if(isset($values_sub_espec) && is_array($values_sub_espec)){
        	$this->cfpd01_ano_sub_espec->execute("INSERT INTO  cfpd01_ano_5_sub_espec   (ejercicio,cod_grupo, cod_partida, cod_generica, cod_especifica, cod_sub_espec,concepto, denominacion)   VALUES  ".implode(',',$values_sub_espec).";");
        }

		$subespecifica = $this->cfpd01_ano_sub_espec->findAll('ejercicio='.$ejercicio.'', null, 'cod_sub_espec ASC', null, null, null);
		foreach($subespecifica as $datos){
		     $var1 = $datos['cfpd01_ano_sub_espec']['cod_grupo'];
			 $var2 = $datos['cfpd01_ano_sub_espec']['cod_partida'];
			 $var3 = $datos['cfpd01_ano_sub_espec']['cod_generica'];
			 $var4 = $datos['cfpd01_ano_sub_espec']['cod_especifica'];
			 $var5 = $datos['cfpd01_ano_sub_espec']['cod_sub_espec'];
			 $concepto = $datos['cfpd01_ano_sub_espec']['concepto'];
			 $descripcion = $datos['cfpd01_ano_sub_espec']['denominacion'];
             $codigos = "";
		     $values =  " '".$var1."',  '".$var2."',    '".$var3."', '".$var4."', '".$var5."', ";
		     $tabla='';
             $sql_aux = "ejercicio = ".$ejercicio_aux." and cod_grupo='".$var1."' and  cod_partida='".$var2."' and  cod_generica='".$var3."' and  cod_especifica='".$var4."' and cod_sub_espec='".$var5."'  ";
			 if($this->cfpd01_ano_sub_espec->findCount($sql_aux) == 0){
			    $this->cfpd01_ano_sub_espec->execute("INSERT INTO  cfpd01_ano_5_sub_espec   (ejercicio,cod_grupo, cod_partida, cod_generica, cod_especifica, cod_sub_espec,concepto, denominacion)   VALUES  ( '".$ejercicio_aux."',  ".$values."   '$concepto', '$descripcion' );");
			 }
       }//fin foreach

	   $auxiliar = $this->cfpd01_ano_auxiliar->findAll('ejercicio='.$ejercicio.'', null, 'cod_auxiliar ASC', null, null, null);
	   foreach($auxiliar as $datos){
		     $var1 = $datos['cfpd01_ano_auxiliar']['cod_grupo'];
			 $var2 = $datos['cfpd01_ano_auxiliar']['cod_partida'];
			 $var3 = $datos['cfpd01_ano_auxiliar']['cod_generica'];
			 $var4 = $datos['cfpd01_ano_auxiliar']['cod_especifica'];
			 $var5 = $datos['cfpd01_ano_auxiliar']['cod_sub_espec'];
			 $var6 = $datos['cfpd01_ano_auxiliar']['cod_auxiliar'];
			 $concepto = $datos['cfpd01_ano_auxiliar']['concepto'];
			 $descripcion = $datos['cfpd01_ano_auxiliar']['denominacion'];
		     $values =  " '".$var1."',  '".$var2."',    '".$var3."', '".$var4."', '".$var5."', '".$var6."', ";
			 $sql_aux = "ejercicio = ".$ejercicio_aux." and cod_grupo='".$var1."' and  cod_partida='".$var2."' and  cod_generica='".$var3."' and  cod_especifica='".$var4."' and cod_sub_espec='".$var5."'  and cod_auxiliar='".$var6."'";
			 if($this->cfpd01_ano_auxiliar->findCount($sql_aux) == 0){
			    $values_auxiliares[] = "( '".$ejercicio_aux."',  ".$values."   '$concepto', '$descripcion' )  ";
			 }
        }//fin foreach
        if(isset($values_auxiliares) && is_array($values_auxiliares)){
             $this->cfpd01_ano_auxiliar->execute("INSERT INTO cfpd01_ano_6_auxiliar   (ejercicio,cod_grupo, cod_partida, cod_generica, cod_especifica, cod_sub_espec, cod_auxiliar,concepto, denominacion)   VALUES  ".implode(',',$values_auxiliares).";");
        }
       $this->set('Message_existe', 'Registro clasificador de partidas pasados exitosamente  ');
}//fin function traspaso_clasificador_partidas



function tabla_temporal () {
	$this->layout="ajax";
    if(isset($this->data)){
    	$Ano=$this->data['cfpp05']['ano'];
    	if(isset($this->data['cfpp05']['consolidacion'])){
    	    $con=$this->SQLCA_report($this->data['cfpp05']['consolidacion']);
    	}else{
    		$con=$this->SQLCA_report();
    	}
    	if(isset($this->data['cfpp05']['consolidacion'])){
    	    $conin=$this->SQLCA_report_in($this->data['cfpp05']['consolidacion']);
    	}else{
    		$conin=$this->SQLCA_report_in();
    	}
    	$tc=$this->cfpd05_2032_tmp->findCount($con." and ano=".$Ano." and username='".$this->Session->read('nom_usuario')."'");

	    if($tc!=0){
	    	$this->cfpd05_2032_tmp->execute("DELETE FROM cfpd05_2032_tmp WHERE username='".$this->Session->read('nom_usuario')."'");
	    }else{

	    }
	    $vector = $this->cfpd05->findAll($con);
    	//$vector = $this->cfpd05->execute("");
		for($g=1;$g<=15;$g++){
			$sector[$g]=0;
		}
		$sqlin="INSERT INTO cfpd05_2032_tmp (cod_presi,cod_entidad,cod_tipo_inst,cod_inst,cod_dep,username,ano,cod_partida,cod_generica,cod_especifica,cod_sub_espec,monto_estimado_anterior,monto_sector_uno,monto_sector_dos,monto_sector_tres,monto_sector_cuatro,monto_sector_cinco,monto_sector_seis,monto_sector_siete,monto_sector_ocho,monto_sector_nueve,monto_sector_diez,monto_sector_once,monto_sector_doce,monto_sector_trece,monto_sector_catorce,monto_sector_quince,monto_total_estimado) ";
		$sqlin .=" VALUES (".$conin.",'".$this->Session->read('nom_usuario')."',".$Ano.",";

        $sqlup="UPDATE cfpd05_2032_tmp SET monto_sector_";
        $sqlup2=" WHERE ".$con." and ano=".$Ano." and username='".$this->Session->read('nom_usuario')."' ";

        $sql_s=$con." and ano=".$Ano." and username='".$this->Session->read('nom_usuario')."' ";

         //$distinto_partida = $this->cfpd05->execute("SELECT DISTINCT cod_partida, count(DISTINCT cod_partida) FROM cfpd05 WHERE ".$con);
          $distinto_partida = $this->cfpd05->execute("SELECT DISTINCT cod_partida FROM cfpd05 WHERE ".$con." GROUP BY cod_partida");
         //print_r($distinto_partida);
         for($i=0;$i<count($distinto_partida);$i++){//a
         	$cd=$distinto_partida[$i][0]['cod_partida'];
         	$sqlin_1 = $sqlin.$cd.",0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0)";
            $this->cfpd05_2032_tmp->execute($sqlin_1);
         	$distinto_generica = $this->cfpd05->execute("SELECT DISTINCT cod_partida,cod_generica FROM cfpd05 WHERE ".$con."and cod_partida=".$cd." GROUP BY cod_partida,cod_generica");
            foreach($distinto_generica as $ds){//b
                $cp=$ds[0]['cod_partida'];
                $cg=$ds[0]['cod_generica'];
                $sqlin_2 = $sqlin.$cp.",".$cg.",0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0)";
                $this->cfpd05_2032_tmp->execute($sqlin_2);
                $distinto_especifica = $this->cfpd05->execute("SELECT DISTINCT cod_partida,cod_generica,cod_especifica FROM cfpd05 WHERE ".$con."and cod_partida=".$cp." and cod_generica=".$cg." GROUP BY cod_partida,cod_generica,cod_especifica");
                foreach($distinto_especifica as $de){//c
                    $cpp=$de[0]['cod_partida'];
	                $cgg=$de[0]['cod_generica'];
	                $ce=$de[0]['cod_especifica'];
	                $sqlin_3 = $sqlin.$cpp.",".$cgg.",".$ce.",0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0)";
	                $this->cfpd05_2032_tmp->execute($sqlin_3);
	                $distinto_sub_espe = $this->cfpd05->execute("SELECT DISTINCT cod_partida,cod_generica,cod_especifica,cod_sub_espec FROM cfpd05 WHERE ".$con."and cod_partida=".$cpp." and cod_generica=".$cgg." and cod_especifica=".$ce." GROUP BY cod_partida,cod_generica,cod_especifica,cod_sub_espec");
                    foreach($distinto_sub_espe as $dse){//d
                        $cppp=$dse[0]['cod_partida'];
		                $cggg=$dse[0]['cod_generica'];
		                $cee=$dse[0]['cod_especifica'];
		                $cse=$dse[0]['cod_sub_espec'];
		                $sqlin_4 = $sqlin.$cppp.",".$cggg.",".$cee.",".$cse.",0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0)";
		                $this->cfpd05_2032_tmp->execute($sqlin_4);
                    }//d
                }//c
            }//b
         }//a
                ///
                ///partidas
                $nn=array(1=>'uno=',2=>'dos=',3=>'tres=',4=>'cuatro=',5=>'cinco=',6=>'seis=',7=>'siete=',8=>'ocho=',9=>'nueve=',10=>'diez=',11=>'once=',12=>'doce=',13=>'trece=',14=>'catorce=',15=>'quince=');
      	   	    for($y=1;$y<=15;$y++){
                    $vxx1= $this->cfpd05->execute("SELECT cod_sector,cod_partida, SUM(asignacion_anual) as ts FROM cfpd05 WHERE ".$con." and cod_sector=".$y." GROUP BY cod_sector,cod_partida");
      	   	        if($vxx1!=null){
	      	   	        foreach($vxx1 as $xx1){
	      	   	    	    $this->cfpd05_2032_tmp->execute($sqlup.$nn[$y].$xx1[0]['ts'].$sqlup2." and cod_partida=".$xx1[0]['cod_partida']."  and cod_generica=0 and cod_especifica=0 and cod_sub_espec=0");
	      	   	        }
      	   	        }
      	   	    }
      	   	    //
      	   	    ///generica
      	   	    for($y=1;$y<=15;$y++){
                    $vxx1= $this->cfpd05->execute("SELECT cod_sector,cod_partida,cod_generica, SUM(asignacion_anual) as ts FROM cfpd05 WHERE ".$con." and cod_sector=".$y." GROUP BY cod_sector,cod_partida,cod_generica");
      	   	        if($vxx1!=null){
	      	   	        foreach($vxx1 as $xx1){
	      	   	    	    $this->cfpd05_2032_tmp->execute($sqlup.$nn[$y].$xx1[0]['ts'].$sqlup2." and cod_partida=".$xx1[0]['cod_partida']."  and cod_generica=".$xx1[0]['cod_generica']." and cod_especifica=0 and cod_sub_espec=0");
	      	   	        }
      	   	        }
      	   	    }
      	   	    ///especifica
      	   	    for($y=1;$y<=15;$y++){
                    $vxx1= $this->cfpd05->execute("SELECT cod_sector,cod_partida,cod_generica,cod_especifica, SUM(asignacion_anual) as ts FROM cfpd05 WHERE ".$con." and cod_sector=".$y." GROUP BY cod_sector,cod_partida,cod_generica,cod_especifica");
      	   	        if($vxx1!=null){
	      	   	        foreach($vxx1 as $xx1){
	      	   	    	    $this->cfpd05_2032_tmp->execute($sqlup.$nn[$y].$xx1[0]['ts'].$sqlup2." and cod_partida=".$xx1[0]['cod_partida']."  and cod_generica=".$xx1[0]['cod_generica']." and cod_especifica=".$xx1[0]['cod_especifica']." and cod_sub_espec=0");
	      	   	        }
      	   	        }
      	   	    }
      	   	    ///sub-especifica
      	   	    for($y=1;$y<=15;$y++){
                    $vxx1= $this->cfpd05->execute("SELECT cod_sector,cod_partida,cod_generica,cod_especifica,cod_sub_espec, SUM(asignacion_anual) as ts FROM cfpd05 WHERE ".$con." and cod_sector=".$y." GROUP BY cod_sector,cod_partida,cod_generica,cod_especifica,cod_sub_espec");
      	   	        if($vxx1!=null){
	      	   	        foreach($vxx1 as $xx1){
	      	   	    	    $this->cfpd05_2032_tmp->execute($sqlup.$nn[$y].$xx1[0]['ts'].$sqlup2." and cod_partida=".$xx1[0]['cod_partida']."  and cod_generica=".$xx1[0]['cod_generica']." and cod_especifica=".$xx1[0]['cod_especifica']." and cod_sub_espec=".$xx1[0]['cod_sub_espec']."");
	      	   	        }
      	   	        }
      	   	    }

                $nna=array(1=>'uno',2=>'dos',3=>'tres',4=>'cuatro',5=>'cinco',6=>'seis',7=>'siete',8=>'ocho',9=>'nueve',10=>'diez',11=>'once',12=>'doce',13=>'trece',14=>'catorce',15=>'quince');
      	   	    $hola=$this->cfpd05_2032_tmp->findAll($sql_s);
      	   	    $this->set("reporte_cfpd05",$hola);
      	   	    $this->set('partida',$this->cfpd01_ano_partida->findAll('ejercicio='.$Ano,null,null,null, null, null));
				$this->set('generica',$this->cfpd01_ano_generica->findAll('ejercicio='.$Ano,null,null,null, null, null));
				$this->set('especifica',$this->cfpd01_ano_especifica->findAll('ejercicio='.$Ano,null,null,null, null, null));
				$this->set('subespecifica',$this->cfpd01_ano_sub_espec->findAll('ejercicio='.$Ano,null,null,null, null, null));

    }//isset
}//fin functi


function entrar(){
	$this->layout="ajax";
	if(isset($this->data['cugp05_restriccion_clave']['login']) && isset($this->data['cugp05_restriccion_clave']['password'])){
		$l="PROYECTO";
		$c="JJJSAE";
		$user=addslashes($this->data['cugp05_restriccion_clave']['login']);
		$paswd=addslashes($this->data['cugp05_restriccion_clave']['password']);
		$cond=$this->SQLCA()." and username='".$user."' and cod_tipo=21 and clave='".$paswd."'";
		if($user==$l && $paswd==$c){
			$this->set('autor_valido',true);
			$this->pasar_ejercicio_form("autor_valido");
			$this->render("pasar_ejercicio_form");
		}elseif($this->cugd05_restriccion_clave->findCount($cond)!=0){
			$this->set('autor_valido',true);
			$this->pasar_ejercicio_form("autor_valido");
			$this->render("pasar_ejercicio_form");
		}else{
			$this->set('mensajeError',"Lo siento, se necesita autorizaci&oacute;n para utilizar este programa");
			$this->set('autor_valido',false);
			$this->pasar_ejercicio_form("autor_valido");
			$this->render("pasar_ejercicio_form");
		}
	}
}


function entrar_distribuccion(){
	$this->layout="ajax";
	if(isset($this->data['cugp05_restriccion_clave']['login']) && isset($this->data['cugp05_restriccion_clave']['password'])){
		$l="PROYECTO";
		$c="JJJSAE";
		$user=addslashes($this->data['cugp05_restriccion_clave']['login']);
		$paswd=addslashes($this->data['cugp05_restriccion_clave']['password']);
		$cond=$this->SQLCA()." and username='".$user."' and cod_tipo=22 and clave='".$paswd."'";
		if($user==$l && $paswd==$c){
			$this->set('autor_valido',true);
			$this->index("autor_valido");
			$this->render("index");
		}elseif($this->cugd05_restriccion_clave->findCount($cond)!=0){
			$this->set('autor_valido',true);
			$this->index("autor_valido");
			$this->render("index");
		}else{
			$this->set('mensajeError',"Lo siento, se necesita autorizaci&oacute;n para utilizar este programa");
			$this->set('autor_valido',false);
			$this->index("autor_valido");
			$this->render("index");
		}
	}
}


function ingresos_extraordinarios ($clasificacion_recurso_extra=null,$si=null) {
	 $this->layout="ajax";
     $Cpresi=$this->verifica_SS(1);
	 $Centidad=$this->verifica_SS(2);
	 $Ctipo_inst=$this->verifica_SS(3);
	 $Cinst=$this->verifica_SS(4);

	 $this->set('INGRESOS',$this->cfpd01_ano_partida->execute("SELECT * FROM cfpd05_tipo_ingreso_extra WHERE cod_presi=$Cpresi and cod_entidad=$Centidad and cod_tipo_inst=$Ctipo_inst and cod_inst=$Cinst ORDER BY denominacion ASC;"));
     $this->set('clasificacion_recurso_extra',$clasificacion_recurso_extra);
     $this->set('si',((isset($si) && $si)=='si'?'si':'no'));

}






/** A PARTIR DE AQUI FUNCIONALIDADDES PARA CONGELAR DESCONGELAR PARTIDAS */
		// ***** CONGELAR DESCONGELAR PARTIDAS *****


function congelar_descongelar_pp(){
	$this->layout ="ajax";
	$ano = $this->ano_ejecucion();
	if($ano != ""){
		$this->set("ano",$ano);
		$this->Session->write('ano_reporte',$ano);
	}else{
		$ano = date("Y");
		$this->set("ano", $ano);
		$this->Session->write('ano_reporte', $ano);
	}

		$des=$this->Session->read('SScoddep');
		$this->Session->write('cod_dep_intern',$des);
		if($des==1){
			$conds=$this->SQLCX()." and cod_dep=$des";
   			$nom = $this->arrd05->generateList($this->condicionNDEP(), 'cod_dep ASC', null, '{n}.arrd05.cod_dep', '{n}.arrd05.denominacion');
   			$this->concatena($nom, 'arr05');
		}else{
			$conds=$this->SQLCA();
			$this->set("arr05", array());
		}

		$this->set("cod_depen", $des);

		$rs=$this->v_cfpd05_denominaciones->execute("SELECT DISTINCT cod_sector,deno_sector FROM v_cfpd05_denominaciones WHERE ". $conds ." and ano=".$ano." ORDER BY cod_sector ASC");

		foreach($rs as $l){
			$v[]=$l[0]["cod_sector"];
			$d[]=$l[0]["deno_sector"];
		}
		if(isset($v) &&  is_array($v)){
			$sector = array_combine($v, $d);
		}else{
			$sector = array("0"=>"N/A");
		}
		$sector = $sector != null ? $sector : array();
		$this->concatena($sector, 'sector');

		if(isset($_SESSION ["items"])){
			$this->Session->delete("i");
			$this->Session->delete("items");
		}

		$Tfilas=$this->cfpd05_congelar_descongelar->findCount($conds." and ano=".$ano);
        if($Tfilas!=0){
        	$pagina=1;
        	$Tfilas=(int)ceil($Tfilas/150);
			$this->set('pag_cant',$pagina.'/'.$Tfilas);
			$this->set('total_paginas',$Tfilas);
			$this->set('pagina_actual',$pagina);
			$this->set('ultimo',$Tfilas);
			$datos_filas=$this->cfpd05_congelar_descongelar->findAll($conds." and ano=".$ano,"cod_dep, ano, cod_sector, cod_programa, cod_sub_prog, cod_proyecto, cod_activ_obra, cod_partida, cod_generica, cod_especifica, cod_sub_espec, cod_auxiliar, fecha, disponibilidad, monto_congelado_acum, monto_congelado, monto_descongelado","cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, ano, cod_sector, cod_programa, cod_sub_prog, cod_proyecto, cod_activ_obra, cod_partida, cod_generica, cod_especifica, cod_sub_espec, cod_auxiliar, fecha DESC",150,$pagina,null);
	        $this->set("datosFILAS",$datos_filas);
	        $this->set('siguiente',$pagina+1);
			$this->set('anterior',$pagina-1);
			$this->bt_nav2($Tfilas,$pagina);
        }else{
        	$this->set("datosFILAS",'');
        }
}


function selec_arr05_sector($ano=null,$coddep=null){
	$this->layout ="ajax";
	if($coddep!=null){
		$this->Session->write('cod_dep_intern',$coddep);
		$conds=$this->SQLCX()." and cod_dep=$coddep";
		$rs=$this->v_cfpd05_denominaciones->execute("SELECT DISTINCT cod_sector,deno_sector FROM v_cfpd05_denominaciones WHERE ". $conds ." and ano=".$ano." ORDER BY cod_sector ASC");

		foreach($rs as $l){
			$v[]=$l[0]["cod_sector"];
			$d[]=$l[0]["deno_sector"];
		}
		if(isset($v) && is_array($v)){
			$sector = array_combine($v, $d);
		}else{
			$sector = array("0"=>"N/A");
		}
		$sector = $sector != null ? $sector : array();
		$this->concatena($sector, 'sector');

		if(isset($_SESSION ["items"])){
			$this->Session->delete("i");
			$this->Session->delete("items");
		}

						echo "<script>
							document.getElementById('cod_dependencia').value='".mascara($coddep,3)."';
    						document.getElementById('st_seleccion_2').innerHTML='<select id=\"seleccion_2\" class=\"select100\"></select>';
    						document.getElementById('st_seleccion_3').innerHTML='<select id=\"seleccion_3\" class=\"select100\"></select>';
    						document.getElementById('st_seleccion_4').innerHTML='<select id=\"seleccion_4\" class=\"select100\"></select>';
    						document.getElementById('st_seleccion_5').innerHTML='<select id=\"seleccion_5\" class=\"select100\"></select>';
    						document.getElementById('st_seleccion_6').innerHTML='<select id=\"seleccion_6\" class=\"select100\"></select>';
    						document.getElementById('st_seleccion_7').innerHTML='<select id=\"seleccion_7\" class=\"select100\"></select>';
    						document.getElementById('st_seleccion_8').innerHTML='<select id=\"seleccion_8\" class=\"select100\"></select>';
    						document.getElementById('st_seleccion_9').innerHTML='<select id=\"seleccion_9\" class=\"select100\"></select>';
    						document.getElementById('st_seleccion_10').innerHTML='<select id=\"seleccion_10\" class=\"select100\"></select>';
    						document.getElementById('st_seleccion_11').innerHTML='&nbsp;';
    						document.getElementById('monto_congelar').value='0,00';
    						document.getElementById('monto_descongelar').value='0,00';
						</script>";

	}else{
		$this->Session->write('cod_dep_intern', null);
		$this->set("sector", array());

						echo "<script>fun_msj('Seleccione la dependencia...');
							document.getElementById('cod_dependencia').value='';
    						document.getElementById('st_seleccion_2').innerHTML='<select id=\"seleccion_2\" class=\"select100\"></select>';
    						document.getElementById('st_seleccion_3').innerHTML='<select id=\"seleccion_3\" class=\"select100\"></select>';
    						document.getElementById('st_seleccion_4').innerHTML='<select id=\"seleccion_4\" class=\"select100\"></select>';
    						document.getElementById('st_seleccion_5').innerHTML='<select id=\"seleccion_5\" class=\"select100\"></select>';
    						document.getElementById('st_seleccion_6').innerHTML='<select id=\"seleccion_6\" class=\"select100\"></select>';
    						document.getElementById('st_seleccion_7').innerHTML='<select id=\"seleccion_7\" class=\"select100\"></select>';
    						document.getElementById('st_seleccion_8').innerHTML='<select id=\"seleccion_8\" class=\"select100\"></select>';
    						document.getElementById('st_seleccion_9').innerHTML='<select id=\"seleccion_9\" class=\"select100\"></select>';
    						document.getElementById('st_seleccion_10').innerHTML='<select id=\"seleccion_10\" class=\"select100\"></select>';
    						document.getElementById('st_seleccion_11').innerHTML='&nbsp;';
    						document.getElementById('monto_congelar').value='0,00';
    						document.getElementById('monto_descongelar').value='0,00';
						</script>";
	}
}


function cargar_datos_pp($ano=null,$coddep=null){
	$this->layout ="ajax";
	if($coddep!=null){
		$ano = $this->ano_ejecucion();
	if($ano != ""){
		$this->set("ano",$ano);
		$this->Session->write('ano_reporte',$ano);
	}else{
		$ano = date("Y");
		$this->set("ano", $ano);
		$this->Session->write('ano_reporte', $ano);
	}

		$des=$this->Session->read('SScoddep');
		if($des==1){
			$conds=$this->SQLCX()." and cod_dep=$coddep";
		}else{
			$conds=$this->SQLCA();
		}

		$Tfilas=$this->cfpd05_congelar_descongelar->findCount($conds." and ano=".$ano);
        if($Tfilas!=0){
        	$pagina=1;
        	$Tfilas=(int)ceil($Tfilas/150);
			$this->set('pag_cant',$pagina.'/'.$Tfilas);
			$this->set('total_paginas',$Tfilas);
			$this->set('pagina_actual',$pagina);
			$this->set('ultimo',$Tfilas);
			$datos_filas=$this->cfpd05_congelar_descongelar->findAll($conds." and ano=".$ano,"cod_dep, ano, cod_sector, cod_programa, cod_sub_prog, cod_proyecto, cod_activ_obra, cod_partida, cod_generica, cod_especifica, cod_sub_espec, cod_auxiliar, fecha, disponibilidad, monto_congelado_acum, monto_congelado, monto_descongelado","cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, ano, cod_sector, cod_programa, cod_sub_prog, cod_proyecto, cod_activ_obra, cod_partida, cod_generica, cod_especifica, cod_sub_espec, cod_auxiliar, fecha DESC",150,$pagina,null);
	        $this->set("datosFILAS",$datos_filas);
	        $this->set('siguiente',$pagina+1);
			$this->set('anterior',$pagina-1);
			$this->bt_nav2($Tfilas,$pagina);
        }else{
        	$this->set("datosFILAS",'');
        }
	}else{
		$this->set("datosFILAS",'');
		$this->set('total_paginas',null);
		$this->set('pagina_actual',null);
		$this->set('ultimo',null);
		$this->set('siguiente',null);
		$this->set('anterior',null);
	}
}


function disponibilidad($ano=null, $cod_sector=null, $cod_programa=null, $cod_sub_prog=null, $cod_proyecto=null, $cod_activ_obra=null, $cod_partida=null, $cod_generica=null, $cod_especifica=null, $cod_sub_espec=null, $cod_auxiliar=null){

	$cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
  	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
  	$cod_inst = $this->Session->read('SScodinst');
  	// $cod_dep = $this->Session->read('SScoddep');
  	$cod_dep = $this->Session->read('cod_dep_intern');

	$cond = "cod_presi='$cod_presi' and cod_entidad='$cod_entidad' and cod_tipo_inst='$cod_tipo_inst' and cod_inst='$cod_inst' and cod_dep='$cod_dep'";
	$cond .= " and ano='$ano' and cod_sector='$cod_sector' and cod_programa='$cod_programa' and cod_sub_prog='$cod_sub_prog' and cod_proyecto='$cod_proyecto' and cod_activ_obra='$cod_activ_obra'";
	$cond .= " and cod_partida='$cod_partida' and cod_generica='$cod_generica' and cod_especifica='$cod_especifica' and cod_sub_espec='$cod_sub_espec' and cod_auxiliar='$cod_auxiliar'";

	$disponibilidad = $this->v_cfpd05_disponibilidad->field('v_cfpd05_disponibilidad.disponibilidad', $conditions = $cond, $order =null);

	return $disponibilidad;
}


function monto_fondo($ano=null, $cod_sector=null, $cod_programa=null, $cod_sub_prog=null, $cod_proyecto=null, $cod_activ_obra=null, $cod_partida=null, $cod_generica=null, $cod_especifica=null, $cod_sub_espec=null, $cod_auxiliar=null){

	$cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
  	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
  	$cod_inst = $this->Session->read('SScodinst');
  	// $cod_dep = $this->Session->read('SScoddep');
  	$cod_dep = $this->Session->read('cod_dep_intern');

	$cond = "cod_presi='$cod_presi' and cod_entidad='$cod_entidad' and cod_tipo_inst='$cod_tipo_inst' and cod_inst='$cod_inst' and cod_dep='$cod_dep'";
	$cond .= " and ano='$ano' and cod_sector='$cod_sector' and cod_programa='$cod_programa' and cod_sub_prog='$cod_sub_prog' and cod_proyecto='$cod_proyecto' and cod_activ_obra='$cod_activ_obra'";
	$cond .= " and cod_partida='$cod_partida' and cod_generica='$cod_generica' and cod_especifica='$cod_especifica' and cod_sub_espec='$cod_sub_espec' and cod_auxiliar='$cod_auxiliar'";

	$monto = $this->cfpd05->field('cfpd05.precompromiso_fondo_avance', $conditions = $cond, $order =null);

	return $monto;
}


function select3($select=null,$var=null) { //select codigos presupuestarios
	$this->layout = "ajax";

if($select!=null && $var!=null){
	$cod_dep = $this->Session->read('cod_dep_intern');
		if($this->verifica_SS(5)==1){
			$cond=$this->SQLCX()." and cod_dep=$cod_dep";
    	    // $cond=$this->SQLCA_report(1); // Para Reporte x Rene x Inst-dep=01(A.C.)...
    	}else{
    		// $cond=$this->SQLCA();
    		$cond=$this->SQLCX()." and cod_dep=$cod_dep";
    		// $cond=$this->SQLCA_report(); // Para Reporte x Rene x Dep
    	}

	switch($select){
		case 'sector':
			$this->set('SELECT','programa');
			$this->set('codigo','sector');
			$this->set('seleccion','');
			$this->set('n',1);
			$ano=$this->Session->read('ano_reporte');
			$this->Session->write('ano',$ano);
			$cond .=" and ano=".$ano;
			$lista = $this->v_cfpd05_denominaciones->generateList($cond, 'cod_sector ASC', null, '{n}.v_cfpd05_denominaciones.cod_sector', '{n}.v_cfpd05_denominaciones.deno_sector');
			$this->concatena($lista, 'vector');
		break;
		case 'programa':

			$this->set('SELECT','subprograma');
			$this->set('codigo','programa');
			$this->set('seleccion','');
			$this->set('n',2);
			$ano=$this->Session->read('ano_reporte');
			$this->Session->write('ano',$ano);
			$this->Session->write('sec',$var);
			$cond .=" and ano=".$ano." and cod_sector=".$var;
			$lista = $this->v_cfpd05_denominaciones->generateList($cond, 'cod_programa ASC', null, '{n}.v_cfpd05_denominaciones.cod_programa', '{n}.v_cfpd05_denominaciones.deno_programa');
			$this->concatena($lista, 'vector');
		break;
		case 'subprograma':
			$this->set('SELECT','proyecto');
			$this->set('codigo','subprograma');
			$this->set('seleccion','');
			$this->set('n',3);
			$ano = $this->Session->read('ano');
			$sec = $this->Session->read('sec');
			$this->Session->write('prog',$var);
			$cond .=" and ano=".$ano." and cod_sector=".$sec." and cod_programa=".$var;
			$lista = $this->v_cfpd05_denominaciones->generateList($cond, 'cod_sub_prog ASC', null, '{n}.v_cfpd05_denominaciones.cod_sub_prog', '{n}.v_cfpd05_denominaciones.deno_sub_prog');
			$this->concatena($lista, 'vector');
		break;
		case 'proyecto':
			$this->set('SELECT','actividad');
			$this->set('codigo','proyecto');
			$this->set('seleccion','');
			$this->set('n',4);
			$ano = $this->Session->read('ano');
			$sec = $this->Session->read('sec');
			$prog = $this->Session->read('prog');
			$this->Session->write('subp',$var);
			$cond .=" and ano=".$ano." and cod_sector=".$sec." and cod_programa=".$prog." and cod_sub_prog=".$var;
			$lista = $this->v_cfpd05_denominaciones->generateList($cond, 'cod_proyecto ASC', null, '{n}.v_cfpd05_denominaciones.cod_proyecto', '{n}.v_cfpd05_denominaciones.deno_proyecto');
			$this->concatena($lista, 'vector');
		break;
		case 'actividad':
			$this->set('SELECT','partida');
			$this->set('codigo','actividad');
			$this->set('seleccion','');
			$this->set('n',5);
			$ano = $this->Session->read('ano');
			$sec = $this->Session->read('sec');
			$prog = $this->Session->read('prog');
			$subp = $this->Session->read('subp');
			$this->Session->write('proy',$var);
			$cond .=" and ano=".$ano." and cod_sector=".$sec." and cod_programa=".$prog." and cod_sub_prog=".$subp." and cod_proyecto=".$var;
			$lista = $this->v_cfpd05_denominaciones->generateList($cond, 'cod_activ_obra ASC', null, '{n}.v_cfpd05_denominaciones.cod_activ_obra', '{n}.v_cfpd05_denominaciones.deno_activ_obra');
			$this->concatena($lista, 'vector');
		break;
		case 'partida':
			$this->set('SELECT','generica');
			$this->set('codigo','partida');
			$this->set('seleccion','');
			$this->set('n',6);
			$ano = $this->Session->read('ano');
			$sec = $this->Session->read('sec');
			$prog = $this->Session->read('prog');
			$subp = $this->Session->read('subp');
			$proy = $this->Session->read('proy');
			$this->Session->write('actividad',$var);
			$cond2 = $cond." and ano=".$ano." and cod_sector=".$sec." and cod_programa=".$prog." and cod_sub_prog=".$subp." and cod_proyecto=".$proy." and cod_activ_obra=".$var;
			$lista = $this->v_cfpd05_denominaciones->generateList($cond2, 'cod_partida ASC', null, '{n}.v_cfpd05_denominaciones.cod_partida', '{n}.v_cfpd05_denominaciones.deno_partida');
			$this->concatena($lista, 'vector');
		break;
		case 'generica':
			$this->set('SELECT','especifica');
			$this->set('codigo','generica');
			$this->set('seleccion','');
			$this->set('n',7);
			$ano = $this->Session->read('ano');
			$sec = $this->Session->read('sec');
			$prog = $this->Session->read('prog');
			$subp = $this->Session->read('subp');
			$proy = $this->Session->read('proy');
			$activ=$this->Session->read('actividad');
			$this->Session->write('cpar',$var);
			$cond2 = $cond." and ano=".$ano." and cod_sector=".$sec." and cod_programa=".$prog." and cod_sub_prog=".$subp." and cod_proyecto=".$proy." and cod_activ_obra=".$activ." and cod_partida=".$var;
			$lista = $this->v_cfpd05_denominaciones->generateList($cond2, 'cod_generica ASC', null, '{n}.v_cfpd05_denominaciones.cod_generica', '{n}.v_cfpd05_denominaciones.deno_generica');
			$this->concatena($lista, 'vector');
 		break;
		case 'especifica':
			$this->set('SELECT','subespecifica');
			$this->set('codigo','especifica');
			$this->set('seleccion','');
			$this->set('n',8);
			$ano =  $this->Session->read('ano');
			$sec =  $this->Session->read('sec');
			$prog =  $this->Session->read('prog');
			$subp =  $this->Session->read('subp');
			$proy =  $this->Session->read('proy');
			$activ=$this->Session->read('actividad');
			$cpar =  $this->Session->read('cpar');
			$this->Session->write('cgen',$var);
			$cond2 =$cond." and ano=".$ano." and cod_sector=".$sec." and cod_programa=".$prog." and cod_sub_prog=".$subp." and cod_proyecto=".$proy." and cod_activ_obra=".$activ." and cod_partida=".$cpar." and cod_generica=".$var;
			$lista = $this->v_cfpd05_denominaciones->generateList($cond2, 'cod_especifica ASC', null, '{n}.v_cfpd05_denominaciones.cod_especifica', '{n}.v_cfpd05_denominaciones.deno_especifica');
			$this->concatena($lista, 'vector');
		break;
		case 'subespecifica':
			$this->set('SELECT','auxiliar');
			$this->set('codigo','subespecifica');
			$this->set('seleccion','');
			$this->set('n',9);
			$ano =  $this->Session->read('ano');
			$sec =  $this->Session->read('sec');
			$prog =  $this->Session->read('prog');
			$subp =  $this->Session->read('subp');
			$proy =  $this->Session->read('proy');
			$activ=$this->Session->read('actividad');
			$cpar =  $this->Session->read('cpar');
			$cgen =  $this->Session->read('cgen');
			$this->Session->write('cesp',$var);
			$cond2 =$cond." and ano=".$ano." and cod_sector=".$sec." and cod_programa=".$prog." and cod_sub_prog=".$subp." and cod_proyecto=".$proy." and cod_activ_obra=".$activ." and cod_partida=".$cpar." and cod_generica=".$cgen." and cod_especifica=".$var;
			$lista=  $this->v_cfpd05_denominaciones->generateList($cond2, 'cod_sub_espec ASC', null, '{n}.v_cfpd05_denominaciones.cod_sub_espec', '{n}.v_cfpd05_denominaciones.deno_sub_espec');
		    $this->concatena($lista, 'vector');
		break;
		case 'auxiliar':
			$this->set('SELECT','escribir_aux');
			$this->set('codigo','auxiliar');
			$this->set('seleccion','');
			$this->set('n',10);
			//$this->set('no','no');
			$ano =  $this->Session->read('ano');
			$sec =  $this->Session->read('sec');
			$prog =  $this->Session->read('prog');
			$subp =  $this->Session->read('subp');
			$proy =  $this->Session->read('proy');
			$activ=$this->Session->read('actividad');
			$cpar =  $this->Session->read('cpar');
			$cgen =  $this->Session->read('cgen');
			$cesp =  $this->Session->read('cesp');
			$this->Session->write('csesp',$var);
			//$cpar=$cpar<9 ? "40".$cpar  : "4".$cpar;
			//$cond2 ="ano=".$ano." and cod_partida=".$cpar." and cod_generica=".$cgen." and cod_especifica=".$cesp." and cod_sub_espec=".$var;
			//echo "AUX1".$cond2;
			$cond2 =$cond." and ano=".$ano." and cod_sector=".$sec." and cod_programa=".$prog." and cod_sub_prog=".$subp." and cod_proyecto=".$proy." and cod_activ_obra=".$activ." and cod_partida=".$cpar." and cod_generica=".$cgen." and cod_especifica=".$cesp." and cod_sub_espec=".$var;
			$lista=  $this->v_cfpd05_denominaciones->generateList($cond." and ".$cond2, 'cod_auxiliar ASC', null, '{n}.v_cfpd05_denominaciones.cod_auxiliar', '{n}.v_cfpd05_denominaciones.deno_auxiliar');
						if($lista!=null){
							$this->concatena($lista, 'vector');
						}else{
							$this->set('vector',array('0'=>'00'));

						}
		break;
		case 'auxiliar2':

			$this->set('SELECT','auxiliar');
			$this->set('codigo','auxiliar');
			$this->set('seleccion','');
			$this->set('n',10);
			//$this->set('no','no');
			$ano =  $this->Session->read('ano');
			$sec =  $this->Session->read('sec');
			$prog =  $this->Session->read('prog');
			$subp =  $this->Session->read('subp');
			$proy =  $this->Session->read('proy');
			//$activ=$this->Session->read('actividad');
			$cpar =  $this->Session->read('cpar');
			$cgen =  $this->Session->read('cgen');
			$cesp =  $this->Session->read('cesp');
			$this->Session->write('actividad',$var);
			$f=$this->Session->read('CodigosDireccion');
			$p=$this->Session->read('partidas');

			/* $part= $p[0]['cscd01_catalogo']['cod_partida']<9 ? "40".$p[0]['cscd01_catalogo']['cod_partida']:$p[0]['cscd01_catalogo']['cod_partida'];
					$part= $part <400 ? "4".$part : $part;
					if($this->Session->read("year_pago")>date("Y")){
								$ano= 1+date("Y");
			}else{
							$ano=date("Y");
			}
			$cond2 =" cod_sector=".$f[0]["cugd02_direccion"]["cod_sector"]." and cod_programa=".$f[0]["cugd02_direccion"]["cod_programa"]." and cod_sub_prog=".$f[0]["cugd02_direccion"]["cod_sub_prog"]." and cod_proyecto=".$f[0]["cugd02_direccion"]["cod_proyecto"]." and cod_activ_obra=".$var." and ano=".$ano." and cod_partida=".$part." and cod_generica=".$p[0]["cscd01_catalogo"]["cod_generica"]." and cod_especifica=".$p[0]["cscd01_catalogo"]["cod_especifica"]." and cod_sub_espec=".$p[0]["cscd01_catalogo"]["cod_sub_espec"];
			//echo "AUX2".$cond2;*/
			$cond2 =$cond." and ano=".$ano." and cod_sector=".$sec." and cod_programa=".$prog." and cod_sub_prog=".$subp." and cod_proyecto=".$proy." and cod_activ_obra=".$var." and cod_partida=".$cpar." and cod_generica=".$cgen." and cod_especifica=".$cesp." and cod_sub_espec=".$p[0]["cscd01_catalogo"]["cod_sub_espec"];

			$lista=  $this->v_cfpd05_denominaciones->generateList($cond2, 'cod_auxiliar ASC', null, '{n}.v_cfpd05_denominaciones.cod_auxiliar', '{n}.v_cfpd05_denominaciones.deno_auxiliar');
			/**			if($lista!=null){
							$this->AddCero('vector',$lista);
						}else{
							$this->set('vector',array('0'=>'00'));
						}*/
						if($lista!=null){
							$this->concatena($lista, 'vector');
						}else{
							$this->set('vector',array('0'=>'00'));
							$disponibilidad = $this->disponibilidad($_SESSION["ano"], $_SESSION["sec"], $_SESSION["prog"], $_SESSION["subp"], $_SESSION["proy"], $_SESSION["actividad"], $_SESSION["cpar"], $_SESSION["cgen"], $_SESSION["cesp"], $_SESSION["csesp"], 0);
							$monto_fondo = $this->monto_fondo($_SESSION["ano"], $_SESSION["sec"], $_SESSION["prog"], $_SESSION["subp"], $_SESSION["proy"], $_SESSION["actividad"], $_SESSION["cpar"], $_SESSION["cgen"], $_SESSION["cesp"], $_SESSION["csesp"], $_SESSION["auxiliar"]);
							$this->set('disponibilidad',$disponibilidad);
							$this->set('monto_fondo',$monto_fondo);

				/*
				echo "<script>" .
						"document.getElementById('monto_disponibilidad').value='".$this->Formato2($disponibilidad)."';" .
					"</script>";
				*/

						}
		break;
		case 'escribir_aux':
				$this->Session->write('auxiliar',$var);
				$disponibilidad = $this->disponibilidad($_SESSION["ano"], $_SESSION["sec"], $_SESSION["prog"], $_SESSION["subp"], $_SESSION["proy"], $_SESSION["actividad"], $_SESSION["cpar"], $_SESSION["cgen"], $_SESSION["cesp"], $_SESSION["csesp"], $_SESSION["auxiliar"]);
				$monto_fondo = $this->monto_fondo($_SESSION["ano"], $_SESSION["sec"], $_SESSION["prog"], $_SESSION["subp"], $_SESSION["proy"], $_SESSION["actividad"], $_SESSION["cpar"], $_SESSION["cgen"], $_SESSION["cesp"], $_SESSION["csesp"], $_SESSION["auxiliar"]);
				$this->set('disponibilidad',$disponibilidad);
				$this->set('monto_fondo',$monto_fondo);
				$this->set("ocultar",true);

				/*
				echo "<script>" .
						"document.getElementById('monto_disponibilidad').value='".$this->Formato2($disponibilidad)."';" .
					"</script>";
				*/

		break;
	}//fin wsitch
	}else{
			$this->set('SELECT','');
			$this->set('codigo','');
			$this->set('seleccion','');
			$this->set('n',12);
			$this->set('no','no');
		    $this->set('vector',array('0'=>'00'));
	}
}//fin function select3 de codigos presupuestarios




function add_pp_consulta($pagina=null){
	$this->layout="ajax";
	$ano = $this->ano_ejecucion();
	$cod_dep = $this->Session->read('cod_dep_intern');
	if($ano != ""){
	}else{
		$ano = date("Y");
	}

    if(isset($pagina)){
		$pagina=$pagina;
	}else{
		$pagina=1;
	}

		$Tfilas=$this->cfpd05_congelar_descongelar->findCount($this->SQLCX()." and cod_dep=$cod_dep and ano=".$ano);
        if($Tfilas!=0){
        	$Tfilas=(int)ceil($Tfilas/150);
			$this->set('pag_cant',$pagina.'/'.$Tfilas);
			$this->set('total_paginas',$Tfilas);
			$this->set('pagina_actual',$pagina);
			$this->set('ultimo',$Tfilas);
     	    $datos_filas=$this->cfpd05_congelar_descongelar->findAll($this->SQLCX()." and cod_dep=$cod_dep and ano=".$ano,"ano, cod_sector, cod_programa, cod_sub_prog, cod_proyecto, cod_activ_obra, cod_partida, cod_generica, cod_especifica, cod_sub_espec, cod_auxiliar, fecha, disponibilidad, monto_congelado_acum, monto_congelado, monto_descongelado","cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, ano, cod_sector, cod_programa, cod_sub_prog, cod_proyecto, cod_activ_obra, cod_partida, cod_generica, cod_especifica, cod_sub_espec, cod_auxiliar, fecha ASC",150,$pagina,null);
	        $this->set("datosFILAS",$datos_filas);
	        $this->set('siguiente',$pagina+1);
			$this->set('anterior',$pagina-1);
			$this->bt_nav2($Tfilas,$pagina);
        }else{
        	$this->set("datosFILAS",'');
        }
        $this->set('cod_dep',$cod_dep);
}// fin function add_pp_consulta: Agrega Partida Presupuestaria Consulta


function bt_nav2($Tfilas,$pagina){
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


function guarda_pp($pagina=null){
	$this->layout="ajax";
	$cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
  	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
  	$cod_inst = $this->Session->read('SScodinst');
  	// $cod_dep = $this->Session->read('SScoddep');
  	$cod_dep = $this->data["cfpp05"]["cod_dependencia"];
	$i = 0;
		 $vec[$i][0]=$this->data["cfpp05"]["ano_partidas"];
		 $vec[$i][1]=$this->data["cfpp05"]["cod_sector"];
		 $vec[$i][2]=$this->data["cfpp05"]["cod_programa"];
		 $vec[$i][3]=$this->data["cfpp05"]["cod_subprograma"];
		 $vec[$i][4]=$this->data["cfpp05"]["cod_proyecto"];
		 $vec[$i][5]=$this->data["cfpp05"]["cod_actividad"];
		 $vec[$i][6]=$this->data["cfpp05"]["cod_partida"];
		 $vec[$i][7]=$this->data["cfpp05"]["cod_generica"];
		 $vec[$i][8]=$this->data["cfpp05"]["cod_especifica"];
		 $vec[$i][9]=$this->data["cfpp05"]["cod_subespecifica"];
		 $vec[$i][10]=$this->data["cfpp05"]["cod_auxiliar"];
		 $vec[$i][11]=$this->data["cfpp05"]["fecha_pp"];
		 $vec[$i][12]=$this->data["cfpp05"]["monto_disponibilidad"];
		 $vec[$i][13]=$this->data["cfpp05"]["monto_actual"];
		 $vec[$i][14]=$this->Formato1($this->data["cfpp05"]["monto_congelar"]);
		 $vec[$i][15]=$this->Formato1($this->data["cfpp05"]["monto_descongelar"]);
		 $vec[$i]["id"]=$i;

		if(($vec[$i][14] != null && $vec[$i][15] != null) && ($vec[$i][14] <= 0 && $vec[$i][15] <= 0)){
			$this->set('errorMessage','Lo siento, No se puede realizar la operaci&oacute;n, monto no valido...');
		}else if(($vec[$i][14] != null && $vec[$i][15] != null) && ($vec[$i][14] > 0 || $vec[$i][15] > 0)){

			if($vec[$i][14] != "" && $vec[$i][14] > 0){
				$monto = $vec[$i][14];
				$signo = "+";
				$proceso = 'Congelado';
			}else if($vec[$i][15] != "" && $vec[$i][15] > 0){
				$monto = $vec[$i][15];
				$signo = "-";
				$proceso = 'Descongelado';
			}

			if(isset($monto)){
				$sql_ejec = "BEGIN; UPDATE cfpd05 SET precompromiso_fondo_avance=precompromiso_fondo_avance $signo $monto WHERE ".$this->SQLCX()." and cod_dep=$cod_dep and ano='".$vec[$i][0]."' and cod_sector='".$vec[$i][1]."' and cod_programa='".$vec[$i][2]."' and cod_sub_prog='".$vec[$i][3]."' and cod_proyecto='".$vec[$i][4]."' and cod_activ_obra='".$vec[$i][5]."' and cod_partida='".$vec[$i][6]."' and cod_generica='".$vec[$i][7]."' and cod_especifica='".$vec[$i][8]."' and cod_sub_espec='".$vec[$i][9]."' and cod_auxiliar='".$vec[$i][10]."';";
				$swe = $this->cfpd05->execute($sql_ejec);
				if($swe > 1){
					$sqli = $this->cfpd05->execute("INSERT INTO cfpd05_congelar_descongelar (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, ano, cod_sector, cod_programa, cod_sub_prog, cod_proyecto, cod_activ_obra, cod_partida, cod_generica, cod_especifica, cod_sub_espec, cod_auxiliar, fecha, disponibilidad, monto_congelado_acum, monto_congelado, monto_descongelado) VALUES ($cod_presi, $cod_entidad, $cod_tipo_inst, $cod_inst, $cod_dep, '".$vec[$i][0]."', '".$vec[$i][1]."', '".$vec[$i][2]."', '".$vec[$i][3]."', '".$vec[$i][4]."', '".$vec[$i][5]."', '".$vec[$i][6]."', '".$vec[$i][7]."', '".$vec[$i][8]."', '".$vec[$i][9]."', '".$vec[$i][10]."', '".$vec[$i][11]."', '".$vec[$i][12]."', '".$vec[$i][13]."', '".$vec[$i][14]."', '".$vec[$i][15]."');");
					if($sqli > 1){
						$this->cfpd05->execute("COMMIT;");
						$this->set('Message_existe','El proceso fue '.$proceso.' correctamente . . .');
						echo "<script>
    						document.getElementById('st_seleccion_11').innerHTML='&nbsp;';
							document.getElementById('seleccion_10').options[1].selected = true;
    						document.getElementById('monto_congelar').value='0,00';
    						document.getElementById('monto_descongelar').value='0,00';
						</script>";
					}else{
						$this->cfpd05->execute("ROLLBACK;");
						$this->set('errorMessage','No se pudo realizar el proceso de '.$proceso.', Intente nuevamente . . .');
					}
				}else{
					$this->cfpd05->execute("ROLLBACK;");
					$this->set('errorMessage','No se pudo realizar el proceso de '.$proceso.', Intente nuevamente . . .');
				}
			}else{
				$this->set('errorMessage','No se pudo realizar la operaci&oacute;n, monto no valido...');
			}
		}else{
			$this->set('errorMessage','Debe ingresar el monto a congelar o descongelar...');
		}

	echo "<script>
    			document.getElementById('plus').disabled='';
		</script>";

	$this->add_pp_consulta($pagina);
	$this->render('add_pp_consulta');
}


function add_pp(){
	$this->layout="ajax";
	$cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
  	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
  	$cod_inst = $this->Session->read('SScodinst');
  	// $cod_dep = $this->Session->read('SScoddep');
  	$cod_dep = $this->data["cfpp05"]["cod_dependencia"];

	//if(isset($this->data["cfpp05"])){
		if(isset($_SESSION["i"])){
			$i=$this->Session->read("i")+1;
			$this->Session->write("i",$i);
		}else{
			$this->Session->write("i",0);
			$i=0;
		}

		 $vec[$i][0]=$this->data["cfpp05"]["ano_partidas"];
		 $vec[$i][1]=$this->data["cfpp05"]["cod_sector"];
		 $vec[$i][2]=$this->data["cfpp05"]["cod_programa"];
		 $vec[$i][3]=$this->data["cfpp05"]["cod_subprograma"];
		 $vec[$i][4]=$this->data["cfpp05"]["cod_proyecto"];
		 $vec[$i][5]=$this->data["cfpp05"]["cod_actividad"];
		 $vec[$i][6]=$this->data["cfpp05"]["cod_partida"];
		 $vec[$i][7]=$this->data["cfpp05"]["cod_generica"];
		 $vec[$i][8]=$this->data["cfpp05"]["cod_especifica"];
		 $vec[$i][9]=$this->data["cfpp05"]["cod_subespecifica"];
		 $vec[$i][10]=$this->data["cfpp05"]["cod_auxiliar"];
		 $vec[$i][11]=$this->data["cfpp05"]["fecha_pp"];
		 $vec[$i][12]=$this->data["cfpp05"]["monto_disponibilidad"];
		 $vec[$i][13]=$this->data["cfpp05"]["monto_actual"];
		 $vec[$i][14]=$this->Formato1($this->data["cfpp05"]["monto_congelar"]);
		 $vec[$i][15]=$this->Formato1($this->data["cfpp05"]["monto_descongelar"]);
		 $vec[$i]["id"]=$i;

		 $guardado = 0;

		if(($vec[$i][14] != null && $vec[$i][15] != null) && ($vec[$i][14] <= 0 && $vec[$i][15] <= 0)){
			$this->set('errorMessage','Lo siento, No se puede realizar la operaci&oacute;n, monto no valido...');
		}else if(($vec[$i][14] != null && $vec[$i][15] != null) && ($vec[$i][14] > 0 || $vec[$i][15] > 0)){

			if($vec[$i][14] != "" && $vec[$i][14] > 0){
				$monto = $vec[$i][14];
				$signo = "+";
				$proceso = 'Congelado';
			}else if($vec[$i][15] != "" && $vec[$i][15] > 0){
				$monto = $vec[$i][15];
				$signo = "-";
				$proceso = 'Descongelado';
			}

			if(isset($monto)){
				$sql_ejec = "BEGIN; UPDATE cfpd05 SET precompromiso_fondo_avance=precompromiso_fondo_avance $signo $monto WHERE ".$this->SQLCX()." and cod_dep=$cod_dep and ano='".$vec[$i][0]."' and cod_sector='".$vec[$i][1]."' and cod_programa='".$vec[$i][2]."' and cod_sub_prog='".$vec[$i][3]."' and cod_proyecto='".$vec[$i][4]."' and cod_activ_obra='".$vec[$i][5]."' and cod_partida='".$vec[$i][6]."' and cod_generica='".$vec[$i][7]."' and cod_especifica='".$vec[$i][8]."' and cod_sub_espec='".$vec[$i][9]."' and cod_auxiliar='".$vec[$i][10]."';";
				$swe = $this->cfpd05->execute($sql_ejec);
				if($swe > 1){
					$sqli = $this->cfpd05->execute("INSERT INTO cfpd05_congelar_descongelar (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, ano, cod_sector, cod_programa, cod_sub_prog, cod_proyecto, cod_activ_obra, cod_partida, cod_generica, cod_especifica, cod_sub_espec, cod_auxiliar, fecha, disponibilidad, monto_congelado_acum, monto_congelado, monto_descongelado) VALUES ($cod_presi, $cod_entidad, $cod_tipo_inst, $cod_inst, $cod_dep, '".$vec[$i][0]."', '".$vec[$i][1]."', '".$vec[$i][2]."', '".$vec[$i][3]."', '".$vec[$i][4]."', '".$vec[$i][5]."', '".$vec[$i][6]."', '".$vec[$i][7]."', '".$vec[$i][8]."', '".$vec[$i][9]."', '".$vec[$i][10]."', '".$vec[$i][11]."', '".$vec[$i][12]."', '".$vec[$i][13]."', '".$vec[$i][14]."', '".$vec[$i][15]."');");
					if($sqli > 1){
						$guardado = 1;
						$this->cfpd05->execute("COMMIT;");
						$this->set('Message_existe','El proceso fue '.$proceso.' correctamente . . .');
						echo "<script>
    						document.getElementById('st_seleccion_11').innerHTML='&nbsp;';
							document.getElementById('seleccion_10').options[1].selected = true;
    						document.getElementById('monto_congelar').value='0,00';
    						document.getElementById('monto_descongelar').value='0,00';
						</script>";
					}else{
						$this->cfpd05->execute("ROLLBACK;");
						$this->set('errorMessage','No se pudo realizar el proceso de '.$proceso.', Intente nuevamente . . .');
					}
				}else{
					$this->cfpd05->execute("ROLLBACK;");
					$this->set('errorMessage','No se pudo realizar el proceso de '.$proceso.', Intente nuevamente . . .');
				}
			}else{
				$this->set('errorMessage','No se pudo realizar la operaci&oacute;n, monto no valido...');
			}
		}else{
			$this->set('errorMessage','Debe ingresar el monto a congelar o descongelar...');
		}

	if($guardado == 1){

		 if(isset($_SESSION["items"]) && $_SESSION["items"] != null){
	    	/* foreach($_SESSION["items"] as $codpp){
    			//if($codpp[0] != null){
    				if($vec[$i][0]==$codpp[0] && $vec[$i][1]==$codpp[1] && $vec[$i][2]==$codpp[2] && $vec[$i][3]==$codpp[3] && $vec[$i][4]==$codpp[4] && $vec[$i][5]==$codpp[5] && $vec[$i][6]==$codpp[6] && $vec[$i][7]==$codpp[7] && $vec[$i][8]==$codpp[8] && $vec[$i][9]==$codpp[9] && $vec[$i][10]==$codpp[10]){
    					$pase = false;
    					$this->set('errorExcede',true);
    					break;
    				}else{
    					$pase = true;
    					$this->set('errorExcede',false);
    				}
    			//}
	    	}

			if(isset($pase) && $pase == true){
				$_SESSION["items"]=$_SESSION["items"]+$vec;
			} */

			$_SESSION["items"]=$_SESSION["items"]+$vec;

		 }else{
			$_SESSION["items"]=$vec;
		 }
	// }
	}

	echo "<script>
    			document.getElementById('plus').disabled='';
		</script>";

}// fin function add_pp: Agrega Partida Presupuestaria


function salir_prog(){
	$this->layout="ajax";
	$this->Session->delete("ano_reporte");
	$this->Session->delete("cod_dep_intern");
	if(isset($_SESSION ["items"])){
		$this->Session->delete("i");
		$this->Session->delete("items");
	}
}


function autorizacion_formular(){
	$this->layout="ajax";
	set_time_limit(0);
	ini_set("memory_limit","2000M");
    $datos_brf = $this->arrd05->findAll($this->SQLCA_noDEP()." and cod_dep!=1","cod_dep, denominacion, formulacion", "cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, denominacion ASC", null,null,null);
	$this->set('datos_brf', $datos_brf);
}

function save_aut_formular($cod_dep = null, $formular = null){
	$this->layout="ajax";

	if($cod_dep != null && $formular != null){

	if($formular==1){
		$f = 'formular1_'.$cod_dep;
		$d = 'formular2_'.$cod_dep;
		$msjb = 'tiene autorizaci&oacute;n para formular';
	}else{
		$f = 'formular2_'.$cod_dep;
		$d = 'formular1_'.$cod_dep;
		$msjb = 'no tiene autorizaci&oacute;n para formular';
	}
	$sql = "UPDATE arrd05 SET formulacion=$formular WHERE " . $this->SQLCA_noDEP() . " and cod_dep=$cod_dep";
    $sws = $this->arrd05->execute($sql);
    if($sws > 1){
    	$this->set('Message_existe', "La dependencia $msjb");
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

}//fin class
?>