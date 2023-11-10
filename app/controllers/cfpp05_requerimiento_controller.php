<?php

 class Cfpp05RequerimientoController extends AppController{


 	var $uses = array('ccfd04_cierre_mes','cfpd05','cfpd05_requerimiento','cfpd05_2032_tmp','cfpd05_auxiliar','cfpp05auxiliar','cfpd02_sector', 'cfpd02_programa', 'cfpd02_sub_prog', 'cfpd02_proyecto', 'cfpd02_activ_obra', 'arrd05','cfpd01_ano_grupo', 'cfpd01_ano_partida', 'cfpd01_ano_generica', 'cfpd01_ano_especifica', 'cfpd01_ano_sub_espec', 'cfpd01_ano_auxiliar', 'cfpd01_formulacion' );
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

     function SQLCA1($ano=null){//sql para busqueda de codigos de arranque con y sin año
         $sql_re = "cod_presi=".$this->verifica_SS(1)."  and    ";
         $sql_re .= "cod_entidad=".$this->verifica_SS(2)."  and  ";
         $sql_re .= "cod_tipo_inst=".$this->verifica_SS(3)."  and ";
         $sql_re .= "cod_inst=".$this->verifica_SS(4)."  and  ";
         if($ano!=null){
         	$sql_re .= "cod_dep=1  and  ";
            $sql_re .= "ano=".$ano."  ";
         }else{
         	$sql_re .= "cod_dep=1";
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
         $this->Session->write('ANO_EJECUCION',$this->ano_ejecucion());
         $this->Session->write('ANO_FORMULAR',$dato);
		$this->set('year', $dato);

	}else{
		$this->set('year', '');
	}

// fin del codigo


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
                  	  $Tfilas=$this->cfpd05_requerimiento->findCount($this->SQLCA($ano));
          	          $Tpag = (int)ceil($Tfilas/250);
          	          $this->set('ultimo',$Tpag);
          	          $this->set('TotalPaginas',$Tpag);
          	          $this->set('pagina_actual',$pagina);
          	          $this->set('paginacion',$pagina.' / '.$Tpag);
          	          $TT=$this->cfpd05_requerimiento->execute("SELECT SUM(asignacion_anual) as total FROM cfpd05_requerimiento WHERE ".$this->SQLCA($ano));

          	          $this->set("TOTALDISTRIBUCION",$TT[0][0]["total"]);
                  	  $dataCFPD05=$this->cfpd05_requerimiento->findAll($this->SQLCA($ano),array('cod_dep','cod_sector','cod_programa','cod_sub_prog','cod_proyecto','cod_activ_obra','cod_partida','cod_generica','cod_especifica','cod_sub_espec','cod_auxiliar','asignacion_anual','ano'),'cod_sector,cod_programa,cod_sub_prog,cod_proyecto,cod_activ_obra,cod_partida,cod_generica,cod_especifica,cod_sub_espec,cod_auxiliar ASC',250,$pagina,null);
                      $this->set('datacfpd05',$dataCFPD05);
                      $this->set('siguiente',$pagina+1);
          	          $this->set('anterior',$pagina-1);
                      $this->bt_nav($Tpag,$pagina);
                       //$dataCFPD05requerimiento=$this->cfpd05_requerimiento->findAll($this->SQLCA_reque($this->Session->read('ano_d_g')),array('cod_dep','cod_sector','cod_programa','cod_sub_prog','cod_proyecto','cod_activ_obra','cod_partida','cod_generica','cod_especifica','cod_sub_espec','cod_auxiliar'),'cod_sector,cod_programa,cod_sub_prog,cod_proyecto,cod_activ_obra,cod_partida,cod_generica,cod_especifica,cod_sub_espec,cod_auxiliar ASC',null,null,null);
                       //$this->set('datos_req',$dataCFPD05requerimiento);
                  }else{
                  	   $this->set('errorMessage', 'No se encontraron datos para el año indicado.');
                  	   $this->set('sector','');
                  	    $Tfilas=$this->cfpd05_requerimiento->findCount($this->SQLCA($ano));
                  	    $Tpag = (int)ceil($Tfilas/250);
                  	    $this->set('ultimo',$Tpag);
          	          $this->set('TotalPaginas',$Tpag);
          	          $this->set('pagina_actual',$pagina);
          	          $this->set('paginacion',$pagina.' / '.$Tpag);
          	          $TT=$this->cfpd05_requerimiento->execute("SELECT SUM(asignacion_anual) as total FROM cfpd05_requerimiento WHERE ".$this->SQLCA($ano));
          	          $this->set("TOTALDISTRIBUCION",$TT[0][0]["total"]);
                  	   $dataCFPD05=$this->cfpd05_requerimiento->findAll($this->SQLCA($ano),array('cod_dep','cod_sector','cod_programa','cod_sub_prog','cod_proyecto','cod_activ_obra','cod_partida','cod_generica','cod_especifica','cod_sub_espec','cod_auxiliar','asignacion_anual','ano'),'cod_sector,cod_programa,cod_sub_prog,cod_proyecto,cod_activ_obra,cod_partida,cod_generica,cod_especifica,cod_sub_espec,cod_auxiliar ASC',250,$pagina,null);
                       $this->set('datacfpd05',$dataCFPD05);
                       $this->set('siguiente',$pagina+1);
          	           $this->set('anterior',$pagina-1);
                       $this->bt_nav($Tpag,$pagina);
                        //$dataCFPD05requerimiento=$this->cfpd05_requerimiento->findAll($this->SQLCA_reque($this->Session->read('ano_d_g')),array('cod_dep','cod_sector','cod_programa','cod_sub_prog','cod_proyecto','cod_activ_obra','cod_partida','cod_generica','cod_especifica','cod_sub_espec','cod_auxiliar'),'cod_sector,cod_programa,cod_sub_prog,cod_proyecto,cod_activ_obra,cod_partida,cod_generica,cod_especifica,cod_sub_espec,cod_auxiliar ASC',null,null,null);
                        //$this->set('datos_req',$dataCFPD05requerimiento);
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
		   }else{
		   	$tipo_presupuesto =1;
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
         $SQLINSERT="INSERT INTO ";
        $SQLINSERT2=" (cod_presi,cod_entidad,cod_tipo_inst,cod_inst,cod_dep,ano,cod_sector,cod_programa,cod_sub_prog,cod_proyecto,cod_activ_obra,cod_partida,cod_generica,cod_especifica,cod_sub_espec,cod_auxiliar,cod_tipo_gasto,tipo_presupuesto,asignacion_anual,aumento_traslado_anual,
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
        $a=$SQLINSERT2 ."($Cpresi,$Centidad,$Ctipo_inst,$Cinst,$Cdep,$ano,$Csector,$Cprograma,$Csubprograma,$Cproyecto,$Cactividad,$Cpartida,$Cgenerica,$Cespecifica,$Csub_espec,$Cauxiliar,$Ctipo_gasto,$tipo_presupuesto,$asignacion_anual,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0)";
        $b=$SQLINSERT2 ."($Cpresi,$Centidad,$Ctipo_inst,$Cinst,1,$ano,$Csector,$Cprograma,$Csubprograma,$Cproyecto,$Cactividad,$Cpartida,$Cgenerica,$Cespecifica,$Csub_espec,$Cauxiliar,$Ctipo_gasto,$tipo_presupuesto,$asignacion_anual,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0)";
        $insertar_cfpd05_requerimientos=$SQLINSERT." cfpd05_requerimiento ".$a;
        $insertar_cfpd05=$SQLINSERT." cfpd05 ".$b;
        $restri="cod_presi=".$Cpresi." and cod_entidad=".$Centidad." and cod_tipo_inst=".$Ctipo_inst." and cod_inst=".$Cinst." and cod_dep=".$Cdep." and ano=".$ano." and cod_sector=".$Csector." and cod_programa=".$Cprograma." and cod_sub_prog=".$Csubprograma." and cod_proyecto=".$Cproyecto." and cod_activ_obra=".$Cactividad." and cod_partida=".$Cpartida." and cod_generica=".$Cgenerica." and cod_especifica=".$Cespecifica." and cod_sub_espec=".$Csub_espec." and cod_auxiliar=".$Cauxiliar."";
        $restri2="cod_presi=".$Cpresi." and cod_entidad=".$Centidad." and cod_tipo_inst=".$Ctipo_inst." and cod_inst=".$Cinst." and cod_dep=1 and ano=".$ano." and cod_sector=".$Csector." and cod_programa=".$Cprograma." and cod_sub_prog=".$Csubprograma." and cod_proyecto=".$Cproyecto." and cod_activ_obra=".$Cactividad." and cod_partida=".$Cpartida." and cod_generica=".$Cgenerica." and cod_especifica=".$Cespecifica." and cod_sub_espec=".$Csub_espec." and cod_auxiliar=".$Cauxiliar."";

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
         $ta1=$this->cfpd05_requerimiento->findCount($restri);
         $ta2=$this->cfpd05->findCount($restri2);
         if($ta1==0 && $ta2==0){
        	  $this->cfpd05_requerimiento->execute($insertar_cfpd05_requerimientos);
              $this->cfpd05->execute($insertar_cfpd05);
              $Tfilas=$this->cfpd05_requerimiento->findCount($this->SQLCA($ano));
              $Tpag = (int)ceil($Tfilas/250);
          	          $this->set('TotalPaginas',$Tpag);
          	          $this->set('pagina_actual',$pagina);
          	          $this->set('paginacion',$pagina.' / '.$Tpag);
          	          $this->set('ultimo',$Tpag);
          	          $TT=$this->cfpd05_requerimiento->execute("SELECT SUM(asignacion_anual) as total FROM cfpd05_requerimiento WHERE ".$this->SQLCA($ano));
          	          $this->set("TOTALDISTRIBUCION",$TT[0][0]["total"]);
          	          $dataCFPD05=$this->cfpd05_requerimiento->findAll($this->SQLCA($ano),array('cod_dep','cod_sector','cod_programa','cod_sub_prog','cod_proyecto','cod_activ_obra','cod_partida','cod_generica','cod_especifica','cod_sub_espec','cod_auxiliar','asignacion_anual','ano'),'cod_sector,cod_programa,cod_sub_prog,cod_proyecto,cod_activ_obra,cod_partida,cod_generica,cod_especifica,cod_sub_espec,cod_auxiliar ASC',250,$pagina,null);
              $this->set('datacfpd05',$dataCFPD05);
              $this->set('siguiente',$pagina+1);
          	  $this->set('anterior',$pagina-1);
              $this->bt_nav($Tpag,$pagina);
		      $this->set('Message_existe', 'Registro agregado exitosamente.');
		      //$dataCFPD05requerimiento=$this->cfpd05_requerimiento->findAll($this->SQLCA_reque($ano),array('cod_dep','cod_sector','cod_programa','cod_sub_prog','cod_proyecto','cod_activ_obra','cod_partida','cod_generica','cod_especifica','cod_sub_espec','cod_auxiliar'),'cod_sector,cod_programa,cod_sub_prog,cod_proyecto,cod_activ_obra,cod_partida,cod_generica,cod_especifica,cod_sub_espec,cod_auxiliar ASC',null,null,null);
              //$this->set('datos_req',$dataCFPD05requerimiento);
        }else{
        	$Tfilas=$this->cfpd05_requerimiento->findCount($this->SQLCA($ano));
        	$Tpag = (int)ceil($Tfilas/250);
          	          $this->set('TotalPaginas',$Tpag);
          	          $this->set('pagina_actual',$pagina);
          	          $this->set('paginacion',$pagina.' / '.$Tpag);
          	          $this->set('ultimo',$Tpag);
          	          $TT=$this->cfpd05_requerimiento->execute("SELECT SUM(asignacion_anual) as total FROM cfpd05_requerimiento WHERE ".$this->SQLCA($ano));
          	          $this->set("TOTALDISTRIBUCION",$TT[0][0]["total"]);
        	 $dataCFPD05=$this->cfpd05_requerimiento->findAll($this->SQLCA($ano),array('cod_dep','cod_sector','cod_programa','cod_sub_prog','cod_proyecto','cod_activ_obra','cod_partida','cod_generica','cod_especifica','cod_sub_espec','cod_auxiliar','asignacion_anual','ano'),'cod_sector,cod_programa,cod_sub_prog,cod_proyecto,cod_activ_obra,cod_partida,cod_generica,cod_especifica,cod_sub_espec,cod_auxiliar ASC',250,$pagina,null);
              $this->set('datacfpd05',$dataCFPD05);
              $this->set('siguiente',$pagina+1);
          	  $this->set('anterior',$pagina-1);
              $this->bt_nav($Tpag,$pagina);
             $this->set('errorMessage', 'Este c&oacute;digo presupuestario ya existe');
              //$dataCFPD05requerimiento=$this->cfpd05_requerimiento->findAll($this->SQLCA_reque($ano),array('cod_dep','cod_sector','cod_programa','cod_sub_prog','cod_proyecto','cod_activ_obra','cod_partida','cod_generica','cod_especifica','cod_sub_espec','cod_auxiliar'),'cod_sector,cod_programa,cod_sub_prog,cod_proyecto,cod_activ_obra,cod_partida,cod_generica,cod_especifica,cod_sub_espec,cod_auxiliar ASC',null,null,null);
              //$this->set('datos_req',$dataCFPD05requerimiento);
        }
	}else{
		///si entro en esta condicion es porque existe un auxiliar 0 y estan tratando de registrar un auxiliar distinto de cero o viceversa. no se puede crear registro con auxiliar cero, estando registros existentes con auxiliares distintos de cero
		$Tfilas=$this->cfpd05_requerimiento->findCount($this->SQLCA($ano));
        	$Tpag = (int)ceil($Tfilas/250);
          	          $this->set('TotalPaginas',$Tpag);
          	          $this->set('pagina_actual',$pagina);
          	          $this->set('paginacion',$pagina.' / '.$Tpag);
          	          $this->set('ultimo',$Tpag);
          	          $TT=$this->cfpd05_requerimiento->execute("SELECT SUM(asignacion_anual) as total FROM cfpd05_requerimiento WHERE ".$this->SQLCA($ano));
          	          $this->set("TOTALDISTRIBUCION",$TT[0][0]["total"]);
        	 $dataCFPD05=$this->cfpd05_requerimiento->findAll($this->SQLCA($ano),array('cod_dep','cod_sector','cod_programa','cod_sub_prog','cod_proyecto','cod_activ_obra','cod_partida','cod_generica','cod_especifica','cod_sub_espec','cod_auxiliar','asignacion_anual','ano'),'cod_sector,cod_programa,cod_sub_prog,cod_proyecto,cod_activ_obra,cod_partida,cod_generica,cod_especifica,cod_sub_espec,cod_auxiliar ASC',250,$pagina,null);
              $this->set('datacfpd05',$dataCFPD05);
              $this->set('siguiente',$pagina+1);
          	  $this->set('anterior',$pagina-1);
              $this->bt_nav($Tpag,$pagina);
              $this->set('errorMessage', 'No se puede guardar el registro');
              //$dataCFPD05requerimiento=$this->cfpd05_requerimiento->findAll($this->SQLCA_reque($ano),array('cod_dep','cod_sector','cod_programa','cod_sub_prog','cod_proyecto','cod_activ_obra','cod_partida','cod_generica','cod_especifica','cod_sub_espec','cod_auxiliar'),'cod_sector,cod_programa,cod_sub_prog,cod_proyecto,cod_activ_obra,cod_partida,cod_generica,cod_especifica,cod_sub_espec,cod_auxiliar ASC',null,null,null);
              //$this->set('datos_req',$dataCFPD05requerimiento);
	}
	}else{
		 $ano=$this->Session->read('ano_d_g');
		 //echo "".$ano;
		 $Tfilas=$this->cfpd05_requerimiento->findCount($this->SQLCA($ano));
		 $Tpag = (int)ceil($Tfilas/250);
          	          $this->set('TotalPaginas',$Tpag);
			          $this->set('pagina_actual',$pagina);
          	          $this->set('paginacion',$pagina.' / '.$Tpag);
          	          $this->set('ultimo',$Tpag);
          	          $TT=$this->cfpd05_requerimiento->execute("SELECT SUM(asignacion_anual) as total FROM cfpd05_requerimiento WHERE ".$this->SQLCA($ano));
          	          $this->set("TOTALDISTRIBUCION",$TT[0][0]["total"]);
		 $dataCFPD05=$this->cfpd05_requerimiento->findAll($this->SQLCA($ano),array('cod_dep','cod_sector','cod_programa','cod_sub_prog','cod_proyecto','cod_activ_obra','cod_partida','cod_generica','cod_especifica','cod_sub_espec','cod_auxiliar','asignacion_anual','ano'),'cod_sector,cod_programa,cod_sub_prog,cod_proyecto,cod_activ_obra,cod_partida,cod_generica,cod_especifica,cod_sub_espec,cod_auxiliar ASC',250,$pagina,null);
         $this->set('datacfpd05',$dataCFPD05);
             $this->set('siguiente',$pagina+1);
          	 $this->set('anterior',$pagina-1);
             $this->bt_nav($Tpag,$pagina);
		 if(!isset($pagina)){
		 	$this->set('errorMessage', 'No se realiz&oacute; el registro');
		 }
		  //$dataCFPD05requerimiento=$this->cfpd05_requerimiento->findAll($this->SQLCA_reque($this->Session->read('ano_d_g')),array('cod_dep','cod_sector','cod_programa','cod_sub_prog','cod_proyecto','cod_activ_obra','cod_partida','cod_generica','cod_especifica','cod_sub_espec','cod_auxiliar'),'cod_sector,cod_programa,cod_sub_prog,cod_proyecto,cod_activ_obra,cod_partida,cod_generica,cod_especifica,cod_sub_espec,cod_auxiliar ASC',null,null,null);
          //$this->set('datos_req',$dataCFPD05requerimiento);
	}


}
 function modificar($dv1,$dv2,$dv3,$dv4,$dv5,$dv6,$dv7,$dv8,$dv9,$dv10,$dv11){
       $this->layout = "ajax";
       if(isset($this->data)){
       	  if(isset($dv1)){
		   	 $tipo_recurso=$this->data['cfpp05']['tipo_presupuesto'];
		   	 $tipo_gasto=$this->data['cfpp05']['tipo_gasto'];

       	  	$condicion=$this->SQLCA($dv11)." and cod_sector=".$dv1." and cod_programa=".$dv2." and cod_sub_prog=".$dv3." and cod_proyecto=".$dv4." and cod_activ_obra=".$dv5." and cod_partida=".$dv6." and cod_generica=".$dv7." and cod_especifica=".$dv8." and cod_sub_espec=".$dv9." and cod_auxiliar=".$dv10;
       	    $this->cfpd05_requerimiento->execute("UPDATE cfpd05_requerimiento SET asignacion_anual=".$this->Formato1($this->data['cfpp05']['monto']).", tipo_presupuesto=".$tipo_recurso.", cod_tipo_gasto=".$tipo_gasto." WHERE ".$condicion);
       	    $condicion_cfpd05=$this->SQLCA1($dv11)." and cod_sector=".$dv1." and cod_programa=".$dv2." and cod_sub_prog=".$dv3." and cod_proyecto=".$dv4." and cod_activ_obra=".$dv5." and cod_partida=".$dv6." and cod_generica=".$dv7." and cod_especifica=".$dv8." and cod_sub_espec=".$dv9." and cod_auxiliar=".$dv10;
       	    $this->cfpd05->execute("UPDATE cfpd05 SET asignacion_anual=".$this->Formato1($this->data['cfpp05']['monto']).", tipo_presupuesto=".$tipo_recurso.", cod_tipo_gasto=".$tipo_gasto." WHERE ".$condicion_cfpd05);

       	        $this->set('errorMessage', 'Monto Actualizado con exito');
       	        $resultado=$this->cfpd05_requerimiento->execute("SELECT SUM(asignacion_anual) as total FROM cfpd05_requerimiento WHERE ".$this->SQLCA($dv11));
                $this->set('TOTAL',$resultado[0][0]['total']);
                echo $this->data['cfpp05']['monto']. "";
       	   //$this->set('eliminar',"Hola esta elimado");
           }else{
               $this->set('errorMessage', 'Dato no Actualizado');
          }
       }else{
       	   //echo "2Hola ".$id;
       }

 }

 function campo_monto($dv1,$dv2,$dv3,$dv4,$dv5,$dv6,$dv7,$dv8,$dv9,$dv10,$dv11,$idupdate){
       $this->layout = "ajax";
       $condicion=$this->SQLCA($dv11)." and cod_sector=".$dv1." and cod_programa=".$dv2." and cod_sub_prog=".$dv3." and cod_proyecto=".$dv4." and cod_activ_obra=".$dv5." and cod_partida=".$dv6." and cod_generica=".$dv7." and cod_especifica=".$dv8." and cod_sub_espec=".$dv9." and cod_auxiliar=".$dv10;
       $codigos=array($dv1,$dv2,$dv3,$dv4,$dv5,$dv6,$dv7,$dv8,$dv9,$dv10,$dv11);
       $this->set('cod',$codigos);
       $this->set("ANO",$dv11);
       //$condicion="nro=".$nro;
       //echo $condicion;
       $vec=$this->cfpd05_requerimiento->findAll($condicion,array('asignacion_anual','tipo_presupuesto','cod_tipo_gasto'),null,null,null,null);
       $monto=$vec[0]['cfpd05_requerimiento']['asignacion_anual'];
       $this->set('ValorMonto',$monto);
       $this->set('tipo_gasto',$vec[0]['cfpd05_requerimiento']['cod_tipo_gasto']);
       $this->set('tipo_recurso',$vec[0]['cfpd05_requerimiento']['tipo_presupuesto']);
       $this->set('id',$idupdate);
 }

function mostrar_monto($dv1,$dv2,$dv3,$dv4,$dv5,$dv6,$dv7,$dv8,$dv9,$dv10,$dv11){
       $this->layout = "ajax";
       $condicion=$this->SQLCA($dv11)." and cod_sector=".$dv1." and cod_programa=".$dv2." and cod_sub_prog=".$dv3." and cod_proyecto=".$dv4." and cod_activ_obra=".$dv5." and cod_partida=".$dv6." and cod_generica=".$dv7." and cod_especifica=".$dv8." and cod_sub_espec=".$dv9." and cod_auxiliar=".$dv10;
       $vec=$this->cfpd05_requerimiento->findAll($condicion,array('asignacion_anual'),null,null,null,null);
       $monto=$vec[0]['cfpd05_requerimiento']['asignacion_anual'];
       $this->set('MuestraMonto',$monto);
       // $this->set('ID',$id);
       echo $this->Formato2($monto);
       //$this->set('id',$idupdate);
 }

 function eliminar($dv1,$dv2,$dv3,$dv4,$dv5,$dv6,$dv7,$dv8,$dv9,$dv10,$dv11){
       $this->layout = "ajax";
       if(isset($dv1)){
       	   $condicion=$this->SQLCA1($dv11)." and cod_sector=".$dv1." and cod_programa=".$dv2." and cod_sub_prog=".$dv3." and cod_proyecto=".$dv4." and cod_activ_obra=".$dv5." and cod_partida=".$dv6." and cod_generica=".$dv7." and cod_especifica=".$dv8." and cod_sub_espec=".$dv9." and cod_auxiliar=".$dv10." and compromiso_anual=0 and aumento_traslado_anual=0 and disminucion_traslado_anual=0 and credito_adicional_anual=0 and rebaja_anual=0 and causado_anual=0 and pagado_anual=0";
       	   $c_m=$this->cfpd05->findCount($condicion);
       	   if($c_m!=0){
       	   	  $condicion_r=$this->SQLCA($dv11)." and cod_sector=".$dv1." and cod_programa=".$dv2." and cod_sub_prog=".$dv3." and cod_proyecto=".$dv4." and cod_activ_obra=".$dv5." and cod_partida=".$dv6." and cod_generica=".$dv7." and cod_especifica=".$dv8." and cod_sub_espec=".$dv9." and cod_auxiliar=".$dv10." and compromiso_anual=0 and aumento_traslado_anual=0 and disminucion_traslado_anual=0 and credito_adicional_anual=0 and rebaja_anual=0 and causado_anual=0 and pagado_anual=0";
       	   	  $this->cfpd05_requerimiento->execute("DELETE FROM cfpd05_requerimiento  WHERE ".$condicion_r);
       	   	  $condicion2=$this->SQLCA1($dv11)." and cod_sector=".$dv1." and cod_programa=".$dv2." and cod_sub_prog=".$dv3." and cod_proyecto=".$dv4." and cod_activ_obra=".$dv5." and cod_partida=".$dv6." and cod_generica=".$dv7." and cod_especifica=".$dv8." and cod_sub_espec=".$dv9." and cod_auxiliar=".$dv10;
       	      $this->cfpd05->execute("DELETE FROM cfpd05  WHERE ".$condicion2);
       	   	  $this->set('errorMessage', 'El Registro eliminado con exito');
       	      $resultado=$this->cfpd05_requerimiento->execute("SELECT SUM(asignacion_anual) as total FROM cfpd05_requerimiento WHERE ".$this->SQLCA($dv11));
              $this->set('TOTAL',$resultado[0][0]['total']);
       	   }else{
       	   	  $condicion=$this->SQLCA1($dv11)." and cod_sector=".$dv1." and cod_programa=".$dv2." and cod_sub_prog=".$dv3." and cod_proyecto=".$dv4." and cod_activ_obra=".$dv5." and cod_partida=".$dv6." and cod_generica=".$dv7." and cod_especifica=".$dv8." and cod_sub_espec=".$dv9." and cod_auxiliar=".$dv10."";
       	   	  $condicion2=$this->SQLCA1($dv11)." and cod_sector=".$dv1." and cod_programa=".$dv2." and cod_sub_prog=".$dv3." and cod_proyecto=".$dv4." and cod_activ_obra=".$dv5." and cod_partida=".$dv6." and cod_generica=".$dv7." and cod_especifica=".$dv8." and cod_sub_espec=".$dv9." and cod_auxiliar=".$dv10." and compromiso_anual=0 and aumento_traslado_anual=0 and disminucion_traslado_anual=0 and credito_adicional_anual=0 and rebaja_anual=0 and causado_anual=0 and pagado_anual=0";
       	      $c_m=$this->cfpd05->findCount($condicion);
       	      $c_m2=$this->cfpd05->findCount($condicion2);
       	      if($c_m!=0){
                 if($c_m2!=0){
                 	//no tiene  moviemiento
                 	  $condicion_r=$this->SQLCA($dv11)." and cod_sector=".$dv1." and cod_programa=".$dv2." and cod_sub_prog=".$dv3." and cod_proyecto=".$dv4." and cod_activ_obra=".$dv5." and cod_partida=".$dv6." and cod_generica=".$dv7." and cod_especifica=".$dv8." and cod_sub_espec=".$dv9." and cod_auxiliar=".$dv10." and compromiso_anual=0 and aumento_traslado_anual=0 and disminucion_traslado_anual=0 and credito_adicional_anual=0 and rebaja_anual=0 and causado_anual=0 and pagado_anual=0";
		       	   	  $this->cfpd05_requerimiento->execute("DELETE FROM cfpd05_requerimiento  WHERE ".$condicion_r);
		       	   	  $condicion2=$this->SQLCA1($dv11)." and cod_sector=".$dv1." and cod_programa=".$dv2." and cod_sub_prog=".$dv3." and cod_proyecto=".$dv4." and cod_activ_obra=".$dv5." and cod_partida=".$dv6." and cod_generica=".$dv7." and cod_especifica=".$dv8." and cod_sub_espec=".$dv9." and cod_auxiliar=".$dv10;
		       	      $this->cfpd05->execute("DELETE FROM cfpd05  WHERE ".$condicion2);
		       	   	  $this->set('Message_existe', 'El Registro eliminado con exito');
		       	      $resultado=$this->cfpd05_requerimiento->execute("SELECT SUM(asignacion_anual) as total FROM cfpd05_requerimiento WHERE ".$this->SQLCA($dv11));
		              $this->set('TOTAL',$resultado[0][0]['total']);
                 }else{
                 	$this->set('errorMessage', 'El Registro tiene movimientos, no se puede eliminar');
                 }
       	      }else{
       	      	 //eliminar de requerimiento
       	      	  $condicion_r=$this->SQLCA($dv11)." and cod_sector=".$dv1." and cod_programa=".$dv2." and cod_sub_prog=".$dv3." and cod_proyecto=".$dv4." and cod_activ_obra=".$dv5." and cod_partida=".$dv6." and cod_generica=".$dv7." and cod_especifica=".$dv8." and cod_sub_espec=".$dv9." and cod_auxiliar=".$dv10." and compromiso_anual=0 and aumento_traslado_anual=0 and disminucion_traslado_anual=0 and credito_adicional_anual=0 and rebaja_anual=0 and causado_anual=0 and pagado_anual=0";
	       	   	  $r=$this->cfpd05_requerimiento->execute("DELETE FROM cfpd05_requerimiento  WHERE ".$condicion_r);
	       	   	  if($r>0){
	       	   	  	 $this->set('Message_existe', 'El Registro eliminado con exito');
	       	   	  }else{
					$this->set('errorMessage', 'El Registro tiene movimientos, no se puede eliminar');
	       	   	  }
       	      }

              //$this->set('errorMessage', 'El Registro tiene movimientos, no se puede eliminar');
       	      $resultado=$this->cfpd05_requerimiento->execute("SELECT SUM(asignacion_anual) as total FROM cfpd05_requerimiento WHERE ".$this->SQLCA($dv11));
              $this->set('TOTAL',$resultado[0][0]['total']);
       	   }


       }else{
            $this->set('errorMessage', 'Registro no Eliminado');
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
          	 $dataCFPD05=$this->cfpd05->findAll($this->SQLCA($Ano),null,'cod_sector,cod_programa,cod_sub_prog,cod_proyecto,cod_activ_obra,cod_partida,cod_generica,cod_especifica,cod_sub_espec,cod_auxiliar ASC',10,$pagina,null);
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





}//fin class


?>