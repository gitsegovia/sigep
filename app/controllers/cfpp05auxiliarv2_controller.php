<?php
/*
 * Created on 19/07/2007
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */

 class Cfpp05auxiliarv2Controller extends AppController{

     var $uses = array('cugd05_restriccion_clave','cfpd05_requerimiento','v_busqueda_auxiliares','cfpd05','cfpd05_auxiliar','cfpp05auxiliar','cfpd02_sector', 'cfpd02_programa', 'cfpd02_sub_prog', 'cfpd02_proyecto', 'cfpd02_activ_obra', 'arrd05','cfpd01_ano_grupo', 'cfpd01_ano_partida', 'cfpd01_ano_generica', 'cfpd01_ano_especifica', 'cfpd01_ano_sub_espec', 'cfpd01_ano_auxiliar', 'cfpd01_formulacion','cfpd07', 'cugd05_restriccion_clave','cfpd10_reformulacion_partidas_tmp','cfpd10_reformulacion_partidas');
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

    function SQLCA_sin_dep(){//sql para busqueda de codigos de arranque con y sin año
         $sql_re = "cod_presi=".$this->verifica_SS(1)."  and    ";
         $sql_re .= "cod_entidad=".$this->verifica_SS(2)."  and  ";
         $sql_re .= "cod_tipo_inst=".$this->verifica_SS(3)."  and ";
         $sql_re .= "cod_inst=".$this->verifica_SS(4)." ";
         return $sql_re;
    }//fin funcion SQLCA

    function SQLCA_dep_uno($ano=null){//sql para busqueda de codigos de arranque con y sin año
         $sql_re = "cod_presi=".$this->verifica_SS(1)."  and    ";
         $sql_re .= "cod_entidad=".$this->verifica_SS(2)."  and  ";
         $sql_re .= "cod_tipo_inst=".$this->verifica_SS(3)."  and ";
         $sql_re .= "cod_inst=".$this->verifica_SS(4)."  and  ";
         if($ano!=null){
         	$sql_re .= "cod_dep=1  and  ";
            $sql_re .= "ano=".$ano."  ";
         }else{
         	$sql_re .= "cod_dep=1 ";
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

    function index(){

$this->verifica_entrada('20');

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
		$this->Session->write('year', $dato);
	}else{
		$this->set('year', '');
	}

// fin del codigo
       $this->set('entidadFederal',$this->verifica_SS(6));

	 }//fin index


function registro_auxiliares(){
    	$this->layout="ajax";
    	$cod_presi = $this->Session->read('SScodpresi');
	    $cod_entidad = $this->Session->read('SScodentidad');
	    $cod_tipo_inst = $this->Session->read('SScodtipoinst');
	    $cod_inst = $this->Session->read('SScodinst');
	    $cod_dep = $this->Session->read('SScoddep');

         if($this->data['cfpp05auxiliar']['ano']){
			$ano = $this->data['cfpp05auxiliar']['ano'];
         	$this->set('year', $ano);
			$this->set('depend',$cod_dep);
			$this->Session->write('ano',$ano);
			$this->Session->write('cdepend',$cod_dep);

         	if($cod_dep==1){
         		$this->set('pdep',true);

   				$arr05 = $this->arrd05->generateList($this->condicionNDEP(), 'cod_dep ASC', null, '{n}.arrd05.cod_dep', '{n}.arrd05.denominacion');
				if(!empty($arr05)){
   					$this->concatena($arr05, 'arr05');
				}else{
					$this->set('arr05', array());
				}

         		$condicion_nd = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep." and ano=".$ano;




         	}else{
         		$this->set('pdep',false);

   				$arr05 = $this->arrd05->generateList($this->condicion(), 'cod_dep ASC', null, '{n}.arrd05.cod_dep', '{n}.arrd05.denominacion');
				if(!empty($arr05)){
   					$this->concatena($arr05, 'arr05');
				}else{
					$this->set('arr05', array());
				}

         		$condicion_nd = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep=".$cod_dep."  and ano=".$ano;
         	}
         	$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep=".$cod_dep." and ano=".$ano;


         	$listaSector=$this->cfpd02_sector->generateList($condicion, 'cod_sector ASC', null, '{n}.cfpd02_sector.cod_sector', '{n}.cfpd02_sector.denominacion');
	        $this->concatena($listaSector, 'sector');
            $Tfilas=$this->cfpp05auxiliar->findCount($condicion_nd);
	        if($Tfilas!=0){
	        	$pagina=1;
	        	$Tfilas=(int)ceil($Tfilas/300);
				$this->set('pag_cant',$pagina.'/'.$Tfilas);
				$this->set('total_paginas',$Tfilas);
				$this->set('pagina_actual',$pagina);
				$this->set('ultimo',$Tfilas);
                $todas_auxiliares=$this->cfpp05auxiliar->findAll($condicion_nd,null,'cod_tipo_inst,cod_dep,cod_sector,cod_programa,cod_sub_prog,cod_proyecto,cod_activ_obra,cod_partida,cod_generica,cod_especifica,cod_sub_espec,cod_auxiliar ASC',300,1,null);
	            $this->set("DATA_AUXILIARES",$todas_auxiliares);
		        $this->set('siguiente',$pagina+1);
				$this->set('anterior',$pagina-1);
				$this->bt_nav($Tfilas,$pagina);
	        }else{
	        	$this->set("datosFILAS",'');
	        }
         }//fin

}//fin regsitro_auxiliares











function registro_auxiliares2($year=null){
    	$this->layout="ajax";
    	$cod_presi = $this->Session->read('SScodpresi');
	    $cod_entidad = $this->Session->read('SScodentidad');
	    $cod_tipo_inst = $this->Session->read('SScodtipoinst');
	    $cod_inst = $this->Session->read('SScodinst');
	    $cod_dep = $this->Session->read('SScoddep');

         if($year!=null){
			$ano = $year;
         	$this->set('year', $ano);
			$this->set('depend',$cod_dep);
         	$this->Session->write('ano',$ano);
         	$this->Session->write('cdepend',$cod_dep);

         	if($cod_dep==1){
         		$this->set('pdep',true);

   				$arr05 = $this->arrd05->generateList($this->condicionNDEP(), 'cod_dep ASC', null, '{n}.arrd05.cod_dep', '{n}.arrd05.denominacion');
				if(!empty($arr05)){
   					$this->concatena($arr05, 'arr05');
				}else{
					$this->set('arr05', array());
				}

         		$condicion_nd = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and ano=".$ano;



         	}else{
         		$this->set('pdep',false);

   				$arr05 = $this->arrd05->generateList($this->condicion(), 'cod_dep ASC', null, '{n}.arrd05.cod_dep', '{n}.arrd05.denominacion');
				if(!empty($arr05)){
   					$this->concatena($arr05, 'arr05');
				}else{
					$this->set('arr05', array());
				}

         		$condicion_nd = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep=".$cod_dep."  and ano=".$ano;
         	}
         	$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep=".$cod_dep." and ano=".$ano;


         	$listaSector=$this->cfpd02_sector->generateList($condicion, 'cod_sector ASC', null, '{n}.cfpd02_sector.cod_sector', '{n}.cfpd02_sector.denominacion');
	        $this->concatena($listaSector, 'sector');
            $Tfilas=$this->cfpp05auxiliar->findCount($condicion_nd);
	        if($Tfilas!=0){
	        	$pagina=1;
	        	$Tfilas=(int)ceil($Tfilas/300);
				$this->set('pag_cant',$pagina.'/'.$Tfilas);
				$this->set('total_paginas',$Tfilas);
				$this->set('pagina_actual',$pagina);
				$this->set('ultimo',$Tfilas);
                $todas_auxiliares=$this->cfpp05auxiliar->findAll($condicion_nd,null,'cod_tipo_inst,cod_dep,cod_sector,cod_programa,cod_sub_prog,cod_proyecto,cod_activ_obra,cod_partida,cod_generica,cod_especifica,cod_sub_espec,cod_auxiliar ASC',300,1,null);
	            $this->set("DATA_AUXILIARES",$todas_auxiliares);
		        $this->set('siguiente',$pagina+1);
				$this->set('anterior',$pagina-1);
				$this->bt_nav($Tfilas,$pagina);
	        }else{
	        	$this->set("datosFILAS",'');
	        }
         }//fin

}//fin regsitro_auxiliares





function sectores_dep($year=null, $cdep=null){
    	$this->layout="ajax";
    	$cod_presi = $this->Session->read('SScodpresi');
	    $cod_entidad = $this->Session->read('SScodentidad');
	    $cod_tipo_inst = $this->Session->read('SScodtipoinst');
	    $cod_inst = $this->Session->read('SScodinst');
	    // $cod_dep = $this->Session->read('SScoddep');
	    // $ano=$this->Session->read('ano');

		if($year!=null && $cdep!=null){
         	// $this->set('year', $ano);
         	// $this->Session->write('ano',$ano);
			$this->Session->write('cdepend',$cdep);
         	$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep=".$cdep." and ano=".$year;

         	$listaSector=$this->cfpd02_sector->generateList($condicion, 'cod_sector ASC', null, '{n}.cfpd02_sector.cod_sector', '{n}.cfpd02_sector.denominacion');
	        $this->concatena($listaSector, 'sector');
         }//fin

}//fin sectores_dep


function listar_partidas($year=null, $cdep=null){
	$this->layout="ajax";
    	$cod_presi = $this->Session->read('SScodpresi');
	    $cod_entidad = $this->Session->read('SScodentidad');
	    $cod_tipo_inst = $this->Session->read('SScodtipoinst');
	    $cod_inst = $this->Session->read('SScodinst');
	    // $cod_dep = $this->Session->read('SScoddep');
	    // $ano=$this->Session->read('ano');
	    $this->set('year', $year);
	    $this->set('cdep', $cdep);
		$condicion_nd = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep=".$cdep."  and ano=".$year;
            $Tfilas=$this->cfpp05auxiliar->findCount($condicion_nd);
	        if($Tfilas!=0){
	        	$pagina=1;
	        	$Tfilas=(int)ceil($Tfilas/300);
				$this->set('pag_cant',$pagina.'/'.$Tfilas);
				$this->set('total_paginas',$Tfilas);
				$this->set('pagina_actual',$pagina);
				$this->set('ultimo',$Tfilas);
                $todas_auxiliares=$this->cfpp05auxiliar->findAll($condicion_nd,null,'cod_tipo_inst,cod_dep,cod_sector,cod_programa,cod_sub_prog,cod_proyecto,cod_activ_obra,cod_partida,cod_generica,cod_especifica,cod_sub_espec,cod_auxiliar ASC',300,1,null);
	            $this->set("DATA_AUXILIARES",$todas_auxiliares);
		        $this->set('siguiente',$pagina+1);
				$this->set('anterior',$pagina-1);
				$this->bt_nav($Tfilas,$pagina);
	        }else{
	        	$this->set("datosFILAS",'');
	        }
}




function consulta_paginacion_grid ($ano=null,$pagina=null) {
	$this->layout="ajax";
	    if (isset($pagina) && $pagina!=null) {
			$pagina=$pagina;
		}else {
			$pagina=1;
		}
	    $cod_presi = $this->Session->read('SScodpresi');
	    $cod_entidad = $this->Session->read('SScodentidad');
	    $cod_tipo_inst = $this->Session->read('SScodtipoinst');
	    $cod_inst = $this->Session->read('SScodinst');
	    $cod_dep = $this->Session->read('SScoddep');

	    //$ano=$this->Session->read('ano');

    	 //$this->data['cfpp05auxiliar']['ano']= $codigo != null ? $codigo : $this->data['cfpp05auxiliar']['ano'];
         if(isset($ano)){
         	         	$ano = $ano;
         	$this->set('year', $ano);
         	// $this->Session->write('ano',$ano);
         	$condicion_nd = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep=".$cod_dep." and ano=".$ano;
         	// $condicion_nd = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and ano=".$ano;
            $Tfilas=$this->cfpp05auxiliar->findCount($condicion_nd);
	        if($Tfilas!=0){
	        	$Tfilas=(int)ceil($Tfilas/300);
	        	//$Tfilas=$Tfilas/300;
				$this->set('pag_cant',$pagina.'/'.$Tfilas);
				$this->set('total_paginas',$Tfilas);
				$this->set('pagina_actual',$pagina);
				$this->set('ultimo',$Tfilas);
	     	    //$datos_filas=$this->cepd03_ordenpago_numero->findAll($this->SQLCA()." and ano_orden_pago=".$dato,null,"numero_orden_pago ASC",300,1,null);
		        //$this->set("datosFILAS",$datos_filas);
                $todas_auxiliares=$this->cfpp05auxiliar->findAll($condicion_nd,null,'cod_tipo_inst,cod_dep,cod_sector,cod_programa,cod_sub_prog,cod_proyecto,cod_activ_obra,cod_partida,cod_generica,cod_especifica,cod_sub_espec,cod_auxiliar ASC',300,$pagina,null);
	            $this->set("DATA_AUXILIARES",$todas_auxiliares);
		        $this->set('siguiente',$pagina+1);
				$this->set('anterior',$pagina-1);
				$this->bt_nav($Tfilas,$pagina);
	        }else{
	        	$this->set("DATA_AUXILIARES",'');
	        }
         }//fin

}


function consulta_paginacion_grid2 ($ano=null,$cdep=null,$pagina=null) {
	$this->layout="ajax";
	    if (isset($pagina) && $pagina!=null) {
			$pagina=$pagina;
		}else {
			$pagina=1;
		}
	    $cod_presi = $this->Session->read('SScodpresi');
	    $cod_entidad = $this->Session->read('SScodentidad');
	    $cod_tipo_inst = $this->Session->read('SScodtipoinst');
	    $cod_inst = $this->Session->read('SScodinst');

	    if(isset($cdep) && !empty($cdep)){
	    	$cod_dep = $cdep;
	    }else{
	    	$cod_dep = $this->Session->read('SScoddep');
	    }

	    //$ano=$this->Session->read('ano');

    	 //$this->data['cfpp05auxiliar']['ano']= $codigo != null ? $codigo : $this->data['cfpp05auxiliar']['ano'];
         if(isset($ano)){
         	         	$ano = $ano;
         	$this->set('year', $ano);
         	$this->set('cdep', $cod_dep);
         	// $this->Session->write('ano',$ano);
         	$condicion_nd = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep=".$cod_dep." and ano=".$ano;
         	// $condicion_nd = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and ano=".$ano;
            $Tfilas=$this->cfpp05auxiliar->findCount($condicion_nd);
	        if($Tfilas!=0){
	        	$Tfilas=(int)ceil($Tfilas/300);
	        	//$Tfilas=$Tfilas/300;
				$this->set('pag_cant',$pagina.'/'.$Tfilas);
				$this->set('total_paginas',$Tfilas);
				$this->set('pagina_actual',$pagina);
				$this->set('ultimo',$Tfilas);
	     	    //$datos_filas=$this->cepd03_ordenpago_numero->findAll($this->SQLCA()." and ano_orden_pago=".$dato,null,"numero_orden_pago ASC",300,1,null);
		        //$this->set("datosFILAS",$datos_filas);
                $todas_auxiliares=$this->cfpp05auxiliar->findAll($condicion_nd,null,'cod_tipo_inst,cod_dep,cod_sector,cod_programa,cod_sub_prog,cod_proyecto,cod_activ_obra,cod_partida,cod_generica,cod_especifica,cod_sub_espec,cod_auxiliar ASC',300,$pagina,null);
	            $this->set("DATA_AUXILIARES",$todas_auxiliares);
		        $this->set('siguiente',$pagina+1);
				$this->set('anterior',$pagina-1);
				$this->bt_nav($Tfilas,$pagina);
	        }else{
	        	$this->set("DATA_AUXILIARES",'');
	        }
         }//fin

}


function guardar_auxiliar_grid ($ano,$cod_dep,$cod_sector,$cod_programa,$cod_sub_prog,$cod_proyecto,$cod_activ_obra,$cod_partida,$cod_generica,$cod_especifica,$cod_sub_espec,$id,$id_actualizar) {
        $this->layout="ajax";
        $this->set('ano',$ano);
        $this->set("i",$id);
        $this->set("id_actualizar",$id_actualizar);
        $d1 = $this->verifica_SS(1);
		$d2 = $this->verifica_SS(2);
	 	$d3 = $this->verifica_SS(3);
	 	$d4 = $this->verifica_SS(4);
	 	$d5 = $cod_dep;
	 	// $d5 = $this->verifica_SS(5);

		$d17 = $this->data['cfpp05auxiliarv2']['auxiliar_'.$id];
		$cod_auxiliar=$this->data['cfpp05auxiliarv2']['cod_auxiliar_'.$id];
		$cod_auxiliar=(int) $cod_auxiliar;
        $sql_verificar_f  =" cod_presi=".$d1." and cod_entidad=".$d2." and cod_tipo_inst=".$d3." and cod_inst=".$d4." and cod_dep=".$d5." and ano=".$ano;
		$sql_verificar_f .=" and cod_sector=".$cod_sector." and cod_programa=".$cod_programa." and cod_sub_prog=".$cod_sub_prog." and cod_proyecto=".$cod_proyecto." and cod_activ_obra=".$cod_activ_obra;
		$sql_verificar_f .=" and cod_partida=".$cod_partida." and cod_generica=".$cod_generica." and cod_especifica=".$cod_especifica." and cod_sub_espec=".$cod_sub_espec;


		$sql_verificar  =" cod_presi=".$d1." and cod_entidad=".$d2." and cod_tipo_inst=".$d3." and cod_inst=".$d4." and cod_dep=".$d5." and ano=".$ano;
		$sql_verificar .=" and cod_sector=".$cod_sector." and cod_programa=".$cod_programa." and cod_sub_prog=".$cod_sub_prog." and cod_proyecto=".$cod_proyecto." and cod_activ_obra=".$cod_activ_obra;
		$sql_verificar .=" and cod_partida=".$cod_partida." and cod_generica=".$cod_generica." and cod_especifica=".$cod_especifica." and cod_sub_espec=".$cod_sub_espec." and cod_auxiliar=".$cod_auxiliar;
      //  echo $sql_verificar;
        $sql_verificar_dpuno="cod_presi=".$d1." and cod_entidad=".$d2." and cod_tipo_inst=".$d3." and cod_inst=".$d4." and cod_dep=1 and ano=".$ano;
		$sql_verificar_dpuno .=" and cod_sector=".$cod_sector." and cod_programa=".$cod_programa." and cod_sub_prog=".$cod_sub_prog." and cod_proyecto=".$cod_proyecto." and cod_activ_obra=".$cod_activ_obra;
		$sql_verificar_dpuno .=" and cod_partida=".$cod_partida." and cod_generica=".$cod_generica." and cod_especifica=".$cod_especifica." and cod_sub_espec=".$cod_sub_espec." and cod_auxiliar=".$cod_auxiliar;
      //  echo $sql_verificar;
        $campos="cod_presi,cod_entidad,cod_tipo_inst,cod_inst,cod_dep,ano,cod_sector,cod_programa,cod_sub_prog,cod_proyecto,cod_activ_obra,cod_partida,cod_generica,cod_especifica,cod_sub_espec,cod_auxiliar,denominacion";
		$sql = "INSERT INTO  cfpd05_auxiliar ($campos)VALUES ($d1,$d2,$d3,$d4,$d5,$ano,$cod_sector,$cod_programa,$cod_sub_prog,$cod_proyecto,$cod_activ_obra,$cod_partida,$cod_generica,$cod_especifica,$cod_sub_espec,$cod_auxiliar,'$d17')";
		$sql_dep_uno = "INSERT INTO  cfpd05_auxiliar ($campos)VALUES ($d1,$d2,$d3,$d4,1,$ano,$cod_sector,$cod_programa,$cod_sub_prog,$cod_proyecto,$cod_activ_obra,$cod_partida,$cod_generica,$cod_especifica,$cod_sub_espec,$cod_auxiliar,'$d17')";

// CONTROLA QUE NO PUEDA CREAR AUXILIAR SI LA PARTIDA FUE CREADA SIN LA MISMA
/*
        if($cod_auxiliar!=0){
	        if($this->cfpd05->findCount($sql_verificar_f." and cod_auxiliar=0")==0){
	        if($this->cfpp05auxiliar->findCount($sql_verificar)==0){
	            $this->cfpp05auxiliar->execute($sql);
	            if($d5!=1 && $this->cfpp05auxiliar->findCount($sql_verificar_dpuno)==0){
	            	$this->cfpp05auxiliar->execute($sql_dep_uno);
	            }
	            $this->set('Message_existe', 'Registro guardado exitosamente  ');
	            $this->data['cfpp05auxiliarv2']=null;
	            $cod_auxiliar+=1;
	            $this->set('cod_aux', $this->zero($cod_auxiliar));
	            $DATA_NUEVA_FILA=$this->cfpp05auxiliar->findAll($sql_verificar,null,'cod_auxiliar DESC',1);
	            $this->set('DATA_NUEVA_FILA',$DATA_NUEVA_FILA);
	        }else{
	            $this->set('errorMessage', 'El codigo de la auxiliar ya existe');
	            $this->set('cod_aux', '');
	        }
	        }else {
	            $this->set('errorMessage', 'A ESTA PARTIDA NO PUEDE CREARLE AUXILIARES, YA QUE LA SUBESPECIFICA TIENE MONTO');

	        }
        }else{
            $this->set('errorMessage', 'El codigo auxiliar no puede ser cero');
        }
*/

// PERMITE CREAR AUXILIAR AUNQUE LA PARTIDA NO FUE CREADA CON LA MISMA

	        if($this->cfpp05auxiliar->findCount($sql_verificar)==0){
	            $this->cfpp05auxiliar->execute($sql);
	            if($d5!=1 && $this->cfpp05auxiliar->findCount($sql_verificar_dpuno)==0){
	            	$this->cfpp05auxiliar->execute($sql_dep_uno);
	            }
	            $this->set('Message_existe', 'Registro guardado exitosamente  ');
	            $this->data['cfpp05auxiliarv2']=null;
	            $cod_auxiliar+=1;
	            $this->set('cod_aux', $this->zero($cod_auxiliar));
	            $DATA_NUEVA_FILA=$this->cfpp05auxiliar->findAll($sql_verificar,null,'cod_auxiliar DESC',1);
	            $this->set('DATA_NUEVA_FILA',$DATA_NUEVA_FILA);
	        }else{
	            $this->set('errorMessage', 'El codigo de la auxiliar ya existe');
	            $this->set('cod_aux', '');
	        }

}//fin registro


 function guardar(){
 	$this->layout = "ajax";

    if(!empty($this->data['cfpp05auxiliar'])){

    	$d1 = $this->verifica_SS(1);
		$d2 = $this->verifica_SS(2);
	 	$d3 = $this->verifica_SS(3);
	 	$d4 = $this->verifica_SS(4);
	 	$d5 = $this->data['cfpp05auxiliar']['cod_depend'];
	 	// $d5 = $this->verifica_SS(5);
    	$d6 = $this->data['cfpp05auxiliar']['ano'];
    	$d7 = $this->data['cfpp05auxiliar']['cod_sector'];
		$d8 = $this->data['cfpp05auxiliar']['cod_programa'];
		$d9 = $this->data['cfpp05auxiliar']['cod_subprograma'];
		$d10 = $this->data['cfpp05auxiliar']['cod_proyecto'];
		$d11 = $this->data['cfpp05auxiliar']['cod_actividad'];
		$d12 = $this->data['cfpp05auxiliar']['cod_partida'];
		//$d12 = $d12 >10 ? CE.$d12 : CE."0".$d12 ;
		$d13 = $this->data['cfpp05auxiliar']['cod_generica'];
		$d14 = $this->data['cfpp05auxiliar']['cod_especifica'];
		$d15 = $this->data['cfpp05auxiliar']['cod_subespecifica'];
		$d17 = $this->data['cfpp05auxiliar']['auxiliar'];
		$cod_aux=$this->data['cfpp05auxiliar']['cod_auxiliar'];

		$this->set('year', $d6);

		$sql_verificar_especifica ="cod_presi=".$d1." and cod_entidad=".$d2." and cod_tipo_inst=".$d3." and cod_inst=".$d4." and ano=".$d6;
		$sql_verificar_especifica .=" and cod_sector=".$d7." and cod_programa=".$d8." and cod_sub_prog=".$d9." and cod_proyecto=".$d10." and cod_activ_obra=".$d11;
		$sql_verificar_especifica .=" and cod_partida=".$d12." and cod_generica=".$d13." and cod_especifica=".$d14." and cod_sub_espec=".$d15." and cod_auxiliar=0";

		$sql_verificar="cod_presi=".$d1." and cod_entidad=".$d2." and cod_tipo_inst=".$d3." and cod_inst=".$d4." and cod_dep=".$d5." and ano=".$d6;
		$sql_verificar .=" and cod_sector=".$d7." and cod_programa=".$d8." and cod_sub_prog=".$d9." and cod_proyecto=".$d10." and cod_activ_obra=".$d11;
		$sql_verificar .=" and cod_partida=".$d12." and cod_generica=".$d13." and cod_especifica=".$d14." and cod_sub_espec=".$d15." and cod_auxiliar=".$cod_aux;
      //  echo $sql_verificar;
        $sql_verificar_dpuno="cod_presi=".$d1." and cod_entidad=".$d2." and cod_tipo_inst=".$d3." and cod_inst=".$d4." and cod_dep=1 and ano=".$d6;
		$sql_verificar_dpuno .=" and cod_sector=".$d7." and cod_programa=".$d8." and cod_sub_prog=".$d9." and cod_proyecto=".$d10." and cod_activ_obra=".$d11;
		$sql_verificar_dpuno .=" and cod_partida=".$d12." and cod_generica=".$d13." and cod_especifica=".$d14." and cod_sub_espec=".$d15." and cod_auxiliar=".$cod_aux;
      //  echo $sql_verificar;
        $campos="cod_presi,cod_entidad,cod_tipo_inst,cod_inst,cod_dep,ano,cod_sector,cod_programa,cod_sub_prog,cod_proyecto,cod_activ_obra,cod_partida,cod_generica,cod_especifica,cod_sub_espec,cod_auxiliar,denominacion";
		$sql = "INSERT INTO  cfpd05_auxiliar ($campos) VALUES ($d1,$d2,$d3,$d4,$d5,$d6,$d7,$d8,$d9,$d10,$d11,$d12,$d13,$d14,$d15,$cod_aux,'$d17')";
		$sql_dep_uno = "INSERT INTO  cfpd05_auxiliar ($campos) VALUES ($d1,$d2,$d3,$d4,1,$d6,$d7,$d8,$d9,$d10,$d11,$d12,$d13,$d14,$d15,$cod_aux,'$d17')";



// CONTROLA QUE NO PUEDA CREAR AUXILIAR SI LA PARTIDA FUE CREADA SIN LA MISMA
/*
        if(($this->cfpd05->findCount($sql_verificar_especifica." and cod_dep=1")==0) || ($this->cfpd05->findCount($sql_verificar_especifica." and cod_dep=".$d5)==0)){
		        if($cod_aux!=0){
			        if($this->cfpp05auxiliar->findCount($sql_verificar)==0){
			            $this->cfpp05auxiliar->execute($sql);
			            if($d5!=1 && $this->cfpp05auxiliar->findCount($sql_verificar_dpuno)==0){
			            	$this->cfpp05auxiliar->execute($sql_dep_uno);
			            }
			            $this->set('Message_existe', 'Los Datos Fueron Guardados Exitosamente');
			            $this->data['cfpp05auxiliar']=null;
			            $cod_aux+=1;
			            $this->set('cod_aux', $this->zero($cod_aux));
			            //$this->registro_auxiliares($d6);
			            //$this->render("registro_auxiliares");
			        //    echo "error 1";
			        }else{
			            $this->set('errorMessage', 'El código de la auxiliar ya existe');
			            $this->set('cod_aux', '');
			            // echo "error 2";
			              $this->data['cfpp05auxiliar']=null;
			        }
		    }else{
		          $this->set('errorMessage', 'El c&oacute;digo de la auxiliar no puede ser cero');
		    }
        }else{
           $this->set('errorMessage', 'A ESTA PARTIDA NO PUEDE CREARLE AUXILIARES, YA QUE LA SUBESPECIFICA TIENE MONTO');
        }

*/

// PERMITE CREAR AUXILIAR AUNQUE LA PARTIDA NO FUE CREADA CON LA MISMA

			        if($this->cfpp05auxiliar->findCount($sql_verificar)==0){
			            $this->cfpp05auxiliar->execute($sql);
			            if($d5!=1 && $this->cfpp05auxiliar->findCount($sql_verificar_dpuno)==0){
			            	$this->cfpp05auxiliar->execute($sql_dep_uno);
			            }
			            $this->set('Message_existe', 'Los Datos Fueron Guardados Exitosamente');
			            $this->data['cfpp05auxiliar']=null;
			            $cod_aux+=1;
			            $this->set('cod_aux', $this->zero($cod_aux));
			            //$this->registro_auxiliares($d6);
			            //$this->render("registro_auxiliares");
			        }else{
			            $this->set('errorMessage', 'El código de la auxiliar ya existe');
			            $this->set('cod_aux', '');
		              	$this->data['cfpp05auxiliar']=null;
			        }
    }else{
    	$this->set('errorMessage', 'los datos no fueron guardados');
    	$this->data['cfpp05auxiliar']=null;
    }
 }//FIN FUNCTION

function zero($x=null){
	if($x != null){
		if($x<10){
			$x="0".$x;
		}else if($x>=10 && $x<=99){
			$x=$x;
		}
	}else{
		$x="00";
	}
	return $x;

}
function concatena($vector1=null, $nomVar=null){
	if($vector1 != null){
		foreach($vector1 as $x => $y){
			$z = $x==0 ? '' : ' - '.$y;
			$cod[$x] = $this->zero($x).$z;
		}
		//print_r($cod);
		$this->set($nomVar, $cod);
	}
}
function concatenaCE_v2($vector1=null, $nomVar=null){
	if($vector1 != null){
		foreach($vector1 as $x => $y){
			$cod[CE.$this->zero($x)] =CE.$this->zero($x).' - '.$y;
		}
		//print_r($cod);
		$this->set($nomVar, $cod);
	}
}
function select3($select=null,$var=null) { //select codigos presupuestarios
	$this->layout = "ajax";
if($select!=null && $var!=null){

	// $cond = $this->SQLCA();
	$cdep = $this->Session->read('cdepend');
	$cond = $this->condicionNDEP(). " and cod_dep = ".$cdep;

	switch($select){
		case 'sector':
		  $this->set('SELECT','programa');
		  $this->set('codigo','sector');
		  $this->set('seleccion','');
		  $this->set('n',1);
		  $ano =  $this->Session->read('ano');
		  // $this->Session->write('selsector',$var);
		  $cond .=" and ano=".$ano;
		  $lista = $this->cfpd02_sector->generateList($cond, 'cod_sector ASC', null, '{n}.cfpd02_sector.cod_sector', '{n}.cfpd02_sector.denominacion');
          $this->concatena($lista, 'vector');
		break;
		case 'programa':
		  $this->set('SELECT','subprograma');
		  $this->set('codigo','programa');
		  $this->set('seleccion','');
		  $this->set('n',2);
		  $ano =  $this->Session->read('ano');
		  $this->Session->write('sec',$var);
		  $cond .=" and ano=".$ano." and cod_sector=".$var;
		  $lista=  $this->cfpd02_programa->generateList($cond, 'cod_programa ASC', null, '{n}.cfpd02_programa.cod_programa', '{n}.cfpd02_programa.denominacion');
          $this->concatena($lista, 'vector');
       //   echo $this->Session->read('sec');
		break;
		case 'subprograma':
		  $this->set('SELECT','proyecto');
		  $this->set('codigo','subprograma');
		  $this->set('seleccion','');
		  $this->set('n',3);
		  $ano =  $this->Session->read('ano');
		  $sec =  $this->Session->read('sec');
		  $this->Session->write('prog',$var);
		  $cond .=" and ano=".$ano." and cod_sector=".$sec." and cod_programa=".$var;
		  $lista=  $this->cfpd02_sub_prog->generateList($cond, 'cod_sub_prog ASC', null, '{n}.cfpd02_sub_prog.cod_sub_prog', '{n}.cfpd02_sub_prog.denominacion');
          $this->concatena($lista, 'vector');
       //   echo $this->Session->read('sec').".".$this->Session->read('prog');
		break;
		case 'proyecto':
		  $this->set('SELECT','actividad');
		  $this->set('codigo','proyecto');
		  $this->set('seleccion','');
		   $this->set('n',4);
		  $ano =  $this->Session->read('ano');
		  $sec =  $this->Session->read('sec');
		  $prog =  $this->Session->read('prog');
		  $this->Session->write('subp',$var);
		  $cond .=" and ano=".$ano." and cod_sector=".$sec." and cod_programa=".$prog." and cod_sub_prog=".$var;
		  $lista=  $this->cfpd02_proyecto->generateList($cond, 'cod_proyecto ASC', null, '{n}.cfpd02_proyecto.cod_proyecto', '{n}.cfpd02_proyecto.denominacion');
          $this->concatena($lista, 'vector');
        //  echo $this->Session->read('sec').".".$this->Session->read('prog').".".$this->Session->read('subp');
		break;
		case 'actividad':
		  $this->set('SELECT','partida');
		  $this->set('codigo','actividad');
		  $this->set('seleccion','');
		  $this->set('n',5);
		  $ano =  $this->Session->read('ano');
		  $sec =  $this->Session->read('sec');
		  $prog =  $this->Session->read('prog');
		  $subp =  $this->Session->read('subp');
		  $this->Session->write('proy',$var);
		  $cond .=" and ano=".$ano." and cod_sector=".$sec." and cod_programa=".$prog." and cod_sub_prog=".$subp." and cod_proyecto=".$var;
		  $lista=  $this->cfpd02_activ_obra->generateList($cond, 'cod_activ_obra ASC', null, '{n}.cfpd02_activ_obra.cod_activ_obra', '{n}.cfpd02_activ_obra.denominacion');
          $this->concatena($lista, 'vector');
         //  echo $this->Session->read('sec').".".$this->Session->read('prog').".".$this->Session->read('subp').".".$this->Session->read('proy');
		break;
		case 'partida':
		  $this->set('SELECT','generica');
		  $this->set('codigo','partida');
		  $this->set('seleccion','');
		  $this->set('n',6);
		  $this->Session->write('activ',$var);
		  $ano =  $this->Session->read('ano');
		  $cond2 ="ejercicio=".$ano." and cod_grupo=".CE."";
		  $lista=  $this->cfpd01_ano_partida->generateList($cond2, 'cod_partida ASC', null, '{n}.cfpd01_ano_partida.cod_partida', '{n}.cfpd01_ano_partida.denominacion');
          $this->concatenaCE_v2($lista, 'vector');
        //  echo $this->Session->read('sec').".".$this->Session->read('prog').".".$this->Session->read('subp').".".$this->Session->read('proy').".".$this->Session->read('activ');
		break;
		case 'generica':
		  $this->set('SELECT','especifica');
		  $this->set('codigo','generica');
		  $this->set('seleccion','');
		  $this->set('n',7);
		  $ano =  $this->Session->read('ano');
		  $var = substr($var,1);
		  $this->Session->write('cpar',$var);
		  $cond2 ="ejercicio=".$ano." and cod_grupo=".CE." and cod_partida=".$var;
		  $lista=  $this->cfpd01_ano_generica->generateList($cond2, 'cod_generica ASC', null, '{n}.cfpd01_ano_generica.cod_generica', '{n}.cfpd01_ano_generica.denominacion');
          $this->concatena($lista, 'vector');
         // echo $this->Session->read('sec').".".$this->Session->read('prog').".".$this->Session->read('subp').".".$this->Session->read('proy').".".$this->Session->read('activ').".".$this->Session->read('cpar');
 		break;
		case 'especifica':
		  $this->set('SELECT','subespecifica');
		  $this->set('codigo','especifica');
		  $this->set('seleccion','');
		  $this->set('n',8);
		  $ano =  $this->Session->read('ano');
		  $cpar =  $this->Session->read('cpar');
		  $this->Session->write('cgen',$var);
		  $cond2 ="ejercicio=".$ano." and cod_grupo=".CE." and cod_partida=".$cpar." and cod_generica=".$var;
		  $lista = $this->cfpd01_ano_especifica->generateList($cond2, 'cod_especifica ASC', null, '{n}.cfpd01_ano_especifica.cod_especifica', '{n}.cfpd01_ano_especifica.denominacion');
          $this->concatena($lista, 'vector');
          //echo $this->Session->read('sec').".".$this->Session->read('prog').".".$this->Session->read('subp').".".$this->Session->read('proy').".".$this->Session->read('activ').".".$this->Session->read('cpar').".".$this->Session->read('cgen');
		break;
		case 'subespecifica':
		  $this->set('SELECT','subespecificaf');
		  $this->set('codigo','subespecifica');
		  $this->set('seleccion','');
		  $this->set('n',9);
          $this->set('no','no');
          $this->set('mostrarINPUT',true);
		  $ano =  $this->Session->read('ano');
		  $cpar =  $this->Session->read('cpar');
		  $cgen =  $this->Session->read('cgen');
		  $var= $var=="" ? 0 : $var;
		  $this->Session->write('cesp',$var);

		  $cond2 ="ejercicio=".$ano." and cod_grupo=".CE." and cod_partida=".$cpar." and cod_generica=".$cgen." and cod_especifica=".$var;
		  $lista=  $this->cfpd01_ano_sub_espec->generateList($cond2, 'cod_sub_espec ASC', null, '{n}.cfpd01_ano_sub_espec.cod_sub_espec', '{n}.cfpd01_ano_sub_espec.denominacion');
          $this->concatena($lista, 'vector');
         // echo $this->Session->read('sec').".".$this->Session->read('prog').".".$this->Session->read('subp').".".$this->Session->read('proy').".".$this->Session->read('activ').".".$this->Session->read('cpar').".".$this->Session->read('cgen').".".$this->Session->read('cesp');
		break;
		case 'auxiliar':
		  $this->set('SELECT','auxiliar');
		  $this->set('codigo','auxiliar');
		  $this->set('seleccion','');
		  $this->set('n',10);
		  $this->set('no','no');
		  $ano =  $this->Session->read('ano');
		  $cpar =  $this->Session->read('cpar');
		  $cgen =  $this->Session->read('cgen');
		  $cesp =  $this->Session->read('cesp');
		  $cond2 ="ejercicio=".$ano." and cod_grupo=".CE." and cod_partida=".$cpar." and cod_generica=".$cgen." and cod_especifica=".$cesp." and cod_sub_espec=".$var;
		  $lista=  $this->cfpd01_ano_auxiliar->generateList($cond2, 'cod_auxiliar ASC', null, '{n}.cfpd01_ano_auxiliar.cod_auxiliar', '{n}.cfpd01_ano_auxiliar.denominacion');
            if($lista!=null){
            	$this->concatena($lista, 'vector');
            }else{
            	$this->set('vector',array('0'=>'00'));
            }
		break;
	}//fin wsitch
	}else{

		  $this->set('SELECT','');
		  $this->set('codigo','');
		  $this->set('seleccion','');
		  $this->set('n',11);
		  $this->set('no','no');
		 $this->set('vector',array('0'=>'00'));
	}
}//fin select codigos presupuestarios
function mostrar_icodigo($var=null) {
    $this->layout = "ajax";
        $d1 = $this->verifica_SS(1);
		$d2 = $this->verifica_SS(2);
	 	$d3 = $this->verifica_SS(3);
	 	$d4 = $this->verifica_SS(4);
	 	$d5 = $this->verifica_SS(5);
     $a[1]=$this->Session->read('sec');
     $a[2]=$this->Session->read('prog');
     $a[3]=$this->Session->read('subp');
     $a[4]=$this->Session->read('proy');
     $a[5]=$this->Session->read('activ');
     $a[6]=$this->Session->read('cpar');
     $a[6]=(int) $a[6];
     $a[6]=$a[6]>9 ? CE.$a[6] : CE."0".$a[6];
     $a[7]=$this->Session->read('cgen');
     $a[8]=$this->Session->read('cesp');
     $a[10]=$this->Session->read('ano');
     $a[9]=$var=="" ? 0 : $var;
     $cond_aux=$this->SQLCA($a[10])." and cod_sector=".$a[1]." and ";
     $cond_aux .="cod_programa=".$a[2]." and ";
     $cond_aux .="cod_sub_prog=".$a[3]." and ";
     $cond_aux .="cod_proyecto=".$a[4]." and ";
     $cond_aux .="cod_activ_obra=".$a[5]." and ";
     $cond_aux .="cod_partida=".$a[6]." and ";
     $cond_aux .="cod_generica=".$a[7]." and ";
     $cond_aux .="cod_especifica=".$a[8]." and ";
     $cond_aux .="cod_sub_espec=".$a[9]."";
        $sql_verificar_especifica ="cod_presi=".$d1." and cod_entidad=".$d2." and cod_tipo_inst=".$d3." and cod_inst=".$d4." and ano=".$a[10];
		$sql_verificar_especifica .=" and cod_sector=".$a[1]." and cod_programa=".$a[2]." and cod_sub_prog=".$a[3]." and cod_proyecto=".$a[4]." and cod_activ_obra=".$a[5];
		$sql_verificar_especifica .=" and cod_partida=".$a[6]." and cod_generica=".$a[7]." and cod_especifica=".$a[8]." and cod_sub_espec=".$a[9]." and cod_auxiliar=0";


// CONTROLA QUE NO PUEDA CREAR AUXILIAR SI LA PARTIDA FUE CREADA SIN LA MISMA
/*
     if(($this->cfpd05->findCount($sql_verificar_especifica." and cod_dep=1")==0) && ($this->cfpd05->findCount($sql_verificar_especifica." and cod_dep=".$d5)==0)){
	     $ss=$this->cfpp05auxiliar->findAll($cond_aux,array('cod_auxiliar'),'cod_auxiliar DESC',1,1,null);
	     if($ss==null){
	     	$new_codigo=1;
	     }else{
	     	$new_codigo=$ss[0]["cfpp05auxiliar"]["cod_auxiliar"]+1;
	     }
	     $this->set('new_codigo',$this->zero($new_codigo));
	     $this->set('ocultar_bt_guardar',false);
     }else{
         $this->set('new_codigo',"");
         $this->set('errorMessage', 'A ESTA PARTIDA NO PUEDE CREARLE AUXILIARES, YA QUE LA SUBESPECIFICA TIENE MONTO');
         $this->set('ocultar_bt_guardar',true);
     }
*/


// PERMITE CREAR AUXILIAR AUNQUE LA PARTIDA NO FUE CREADA CON LA MISMA
	     $ss=$this->cfpp05auxiliar->findAll($cond_aux,array('cod_auxiliar'),'cod_auxiliar DESC',1,1,null);
	     if($ss==null){
	     	$new_codigo=1;
	     }else{
	     	$new_codigo=$ss[0]["cfpp05auxiliar"]["cod_auxiliar"]+1;
	     }
	     $this->set('new_codigo',$this->zero($new_codigo));
	     $this->set('ocultar_bt_guardar',false);
}


/**
function mostrar3($select=null,$var=null) { //mostrar3 codigos presupuestarios
	$this->layout = "ajax";
if(isset($var) && !empty($var)){
    $cond =$this->SQLCA();
	switch($select){
		case 'sector':
		  $ano =  $this->Session->read('ano');
		  $this->Session->write('dsec',$var);
		  $cond .=" and ano=".$ano." and cod_sector=".$var;
		  $a=  $this->cfpd02_sector->findAll($cond);
          echo $a[0]['cfpd02_sector']['denominacion'];
		break;
		case 'programa':
		  $ano =  $this->Session->read('ano');
		  $sec =  $this->Session->read('dsec');
		  $this->Session->write('dprog',$var);
		  $cond .=" and ano=".$ano." and cod_sector=".$sec." and cod_programa=".$var;
		  $a=  $this->cfpd02_programa->findAll($cond);
          echo $a[0]['cfpd02_programa']['denominacion'];
		break;
		case 'subprograma':
		  $ano =  $this->Session->read('ano');
		  $sec =  $this->Session->read('dsec');
		  $prog =  $this->Session->read('dprog');
		  $this->Session->write('dsubprog',$var);
		  $cond .=" and ano=".$ano." and cod_sector=".$sec." and cod_programa=".$prog." and cod_sub_prog=".$var;
		  $a=  $this->cfpd02_sub_prog->findAll($cond);
          echo $a[0]['cfpd02_sub_prog']['denominacion'];
		break;
		case 'proyecto':
		  $var= empty($var) ? 0 :  $var;
		  $ano =  $this->Session->read('ano');
		  $sec =  $this->Session->read('dsec');
		  $prog =  $this->Session->read('dprog');
		  $subprog =  $this->Session->read('dsubprog');
		  $this->Session->write('dproy',$var);
		  if(empty($var)){
		  	echo "N/A";
		  }else{
		  $cond .=" and ano=".$ano." and cod_sector=".$sec." and cod_programa=".$prog." and cod_sub_prog=".$subprog." and cod_proyecto=".$var;
		  $a=  $this->cfpd02_proyecto->findAll($cond);
          echo $a[0]['cfpd02_proyecto']['denominacion'];}
		break;
		case 'actividad':
		  $ano =  $this->Session->read('ano');
		  $sec =  $this->Session->read('dsec');
		  $prog =  $this->Session->read('dprog');
		  $subprog =  $this->Session->read('dsubprog');
		  $proy =  $this->Session->read('dproy');
		  $proy= empty($proy) ? 0 :  $proy;
		  $cond .=" and ano=".$ano." and cod_sector=".$sec." and cod_programa=".$prog." and cod_sub_prog=".$subprog." and cod_proyecto=".$proy." and cod_activ_obra=".$var;
		  $a=  $this->cfpd02_activ_obra->findAll($cond);
          echo $a[0]['cfpd02_activ_obra']['denominacion'];
		break;
		case 'partida':
		  $ano =  $this->Session->read('ano');
		  $this->Session->write('dpar',$var);
		  $cond2 ="ejercicio=".$ano." and cod_grupo=".CE." and cod_partida=".$var;
		  $a=  $this->cfpd01_ano_partida->findAll($cond2);
          echo $a[0]['cfpd01_ano_partida']['denominacion'];
		break;
		case 'generica':
		  $ano =  $this->Session->read('ano');
		  $dpar=  $this->Session->read('dpar');
		  $this->Session->write('dgen',$var);
		  $cond2 ="ejercicio=".$ano." and cod_grupo=".CE." and cod_partida=".$dpar." and cod_generica=".$var;
		  $a=  $this->cfpd01_ano_generica->findAll($cond2);
          echo $a[0]['cfpd01_ano_generica']['denominacion'];
		break;
		case 'especifica':
		   $ano =  $this->Session->read('ano');
		  $dpar=  $this->Session->read('dpar');
		  $dgen =  $this->Session->read('dgen');
		  $this->Session->write('desp',$var);
		  $cond2 ="ejercicio=".$ano." and cod_grupo=".CE." and cod_partida=".$dpar." and cod_generica=".$dgen." and cod_especifica=".$var;
		  $a=  $this->cfpd01_ano_especifica->findAll($cond2);
          echo $a[0]['cfpd01_ano_especifica']['denominacion'];
		break;
		case 'subespecifica':
		  $ano =  $this->Session->read('ano');
		  $dpar=  $this->Session->read('dpar');
		  $dgen =  $this->Session->read('dgen');
		  $desp =  $this->Session->read('desp');
		  $this->Session->write('dsubesp',$var);
		  $cond2 ="ejercicio=".$ano." and cod_grupo=".CE." and cod_partida=".$dpar." and cod_generica=".$dgen." and cod_especifica=".$desp." and cod_sub_espec=".$var;
		  $a=  $this->cfpd01_ano_sub_espec->findAll($cond2);
          echo $a[0]['cfpd01_ano_sub_espec']['denominacion'];
		break;
		case 'auxiliar':
		  $ano =  $this->Session->read('ano');
		  $dpar=  $this->Session->read('dpar');
		  $dgen =  $this->Session->read('dgen');
		  $desp =  $this->Session->read('desp');
		  $dsubesp =  $this->Session->read('dsubesp');
		  $cond2 ="ejercicio=".$ano." and cod_grupo=".CE." and cod_partida=".$dpar." and cod_generica=".$dgen." and cod_especifica=".$desp." and cod_sub_espec=".$dsubesp." and cod_auxiliar=".$var;
		  $a=  $this->cfpd01_ano_auxiliar->findAll($cond2);
          echo $a[0]['cfpd01_ano_auxiliar']['denominacion'];
		break;
	}//fin wsitch
	}else{
		echo "";
	}
}//fin mostrar3 codigos presupuestarios
*/

/**
 function editar($var1=null, $var2=null, $var3=null, $var4=null, $var5=null, $var6=null){

 	$this->layout = "ajax";
	$this->set('opcion1', $var1);
	$this->set('opcion2', $var2);
	$this->set('opcion3', $var3);
	$this->set('opcion4', $var4);
	$this->set('opcion5', $var5);
    $this->set('opcion6', $var6);

	$action='';
	$tabla = '';
	$sql_2 = '';


	if($var1!=null){
		$sql_2 =  ' cod_sector =  '.$var1.'  ';
		$tabla='cfpd02_sector';
	}
	if($var2!=null){
		$sql_2 .= 'and cod_programa = '.$var2.'  ';
		$tabla='cfpd02_programa';
	}
	if($var3!=null){
		$sql_2 .= 'and cod_sub_prog = '.$var3.'  ';
		$tabla='cfpd02_sub_prog';
	}
	if($var4!=null){
		$sql_2 .= 'and cod_proyecto = '.$var4.'  ';
		$tabla='cfpd02_proyecto';
	}
	if($var5!=null){
		$sql_2.= 'and cod_activ_obra = '.$var5.'  ';
		$tabla='cfpd02_activ_obra';
	}
	if($var6!=null){
		$sql_2 .='and cod_auxiliar = '.$var6.'  ';
		$tabla='cfpd02_auxiliar';
	}

	$this->set('tabla', $tabla);

	  $data = $this->$tabla->findAll($sql_2, null, null, null);

	  $this->set('datos_cod_cfpp02', $data);



 }
*/
/**
 function  guardar_editar($tabla=null, $var1=null, $var2=null, $var3=null, $var4=null, $var5=null, $var6=null, $aux=null){

 	$this->layout = "ajax";

	 $denominacion = $this->data['cfpp05auxiliar']['denominacion'];
	 $unidad_ejecutora = $this->data['cfpp05auxiliar']['unidad_ejecutora'];
	 $objetivo = $this->data['cfpp05auxiliar']['objetivo'];
	 $funcionario_responsable = $this->data['cfpp05auxiliar']['funcionario_responsable'];
	 $cod_presi = $this->Session->read('SScodpresi');
	 $cod_entidad = $this->Session->read('SScodentidad');
	 $cod_tipo_inst = $this->Session->read('SScodtipoinst');
	 $cod_inst = $this->Session->read('SScodinst');
	 $cod_dep = $this->Session->read('SScoddep');
	 $ano = date('Y');


	$sql_1 = 'UPDATE '.$tabla.'   SET  denominacion = \''.$denominacion.'\', unidad_ejecutora = \''.$unidad_ejecutora.'\', objetivo = \''.$objetivo.'\', funcionario_responsable = \''.$funcionario_responsable.'\' WHERE ';

	if($var1!=null){
		$sql_2 =  ' cod_presi = '.$cod_presi.' and cod_entidad = '.$cod_entidad.' and cod_tipo_inst = '.$cod_tipo_inst.' and cod_inst = '.$cod_inst.' and cod_dep = '.$cod_dep.' and cod_sector =  '.$var1.'  ';
                $tabla='cfpd02_sector';
	}
	if($var2!=null){
		$sql_2 .= 'and cod_programa = '.$var2.'  ';
		$tabla='cfpd02_programa';
	}
	if($var3!=null){
		$sql_2 .= 'and cod_sub_prog = '.$var3.'  ';
		$tabla='cfpd02_sub_prog';
	}
	if($var4!=null){
		$sql_2 .= 'and cod_proyecto = '.$var4.'  ';
		$tabla='cfpd02_proyecto';
	}
	if($var5!=null){
		$sql_2.= 'and cod_activ_obra = '.$var5.'  ';
		$tabla='cfpd02_activ_obra';
	}
	if($var6!=null){
		$sql_2 .='and cod_auxiliar = '.$var6.'  ';
		$tabla='cfpd02_auxiliar';
	}


	$sql = $sql_1.$sql_2;

    $this->$tabla->execute($sql);

	$this->set('errorMessage', 'Los Datos Fueron Modificados');


	$this->set('opcion1', $var1);
	$this->set('opcion2', $var2);
	$this->set('opcion3', $var3);
	$this->set('opcion4', $var4);
	$this->set('opcion5', $var5);
    $this->set('opcion6', $var6);


	$tabla = '';
	$sql_2 = '';



	if($var1!=null){ $sql_2 =  ' cod_sector =  '.$var1.'  ';                       $tabla='cfpd02_sector';   }
	if($var2!=null){ $sql_2 .= 'and cod_programa = '.$var2.'  ';              $tabla='cfpd02_programa';                            }
	if($var3!=null){ $sql_2 .= 'and cod_sub_prog = '.$var3.'  ';           $tabla='cfpd02_sub_prog';                        }
	if($var4!=null){ $sql_2 .= 'and cod_proyecto = '.$var4.'  ';        $tabla='cfpd02_proyecto';                      }
	if($var5!=null){ $sql_2.= 'and cod_activ_obra = '.$var5.'  ';  $tabla='cfpd02_activ_obra';              }
	if($var6!=null){ $sql_2 .='and cod_auxiliar = '.$var6.'  ';                $tabla='cfpd02_auxiliar';                            }


	  $data = $this->$tabla->findAll($sql_2, null, null, null);

	  $this->set('datos_cod_cfpp02', $data);

	  $this->set('tabla', $tabla);





 }//FIN FUNCTION
*/

/**
 function eliminar($v1,$v2,$v3,$v4,$v5,$v6,$v7,$v8,$v9,$v10,$ano){
 	$this->layout = "ajax";
 	echo "<script>alert('".$v1.$v2.$v3.$v4.$v5.$v6.$v7.$v8.$v9.$v10.$ano."');</script>";
 	$condicion=$this->SQLCA($ano)." and cod_sector=".$v1." and ";
    $condicion .="cod_programa=".$v2." and ";
    $condicion .="cod_sub_prog=".$v3." and ";
    $condicion .="cod_proyecto=".$v4." and ";
    $condicion .="cod_activ_obra=".$v5." and ";
    $condicion .="cod_partida=".$v6." and ";
    $condicion .="cod_generica=".$v7." and ";
    $condicion .="cod_especifica=".$v8." and ";
    $condicion .="cod_sub_espec=".$v9." and ";
    $condicion .="cod_auxiliar=".$v10." ";
    if($this->cfpd05->findCount($condicion) == 0){
    	$this->cfpp05auxiliar->execute("DELETE FROM cfpd05_auxiliar WHERE ".$condicion);
    	$this->set('errorMessage', 'Registro Auxiliar Eliminado con exito');
	}else{
    	$this->set('errorMessage', 'LO SIENTO NO SE PUDO ELIMINAR EL AUXILIAR PORQUE EXISTE UN REGISTRO EN LA DISTRIBUCION DE GASTO');
    }

     $this->set('entidadFederal',$this->verifica_SS(6));
      $ejercicio=$ano;
	  if($ejercicio!=null){
	  	    $this->set('ejercicio', $ejercicio);
		}else if($this->data['cfpp05auxiliar']['ano']){
			$this->set('ejercicio', $this->data['cfpp05auxiliar']['ano']);
			$ejercicio = $this->data['cfpp05auxiliar']['ano'];
		}
		   /*$DATOS_res = $this->cfpp05auxiliar->findAll('ano='.$ejercicio.'', null, 'cod_auxiliar ASC', null, null, null);
		   $this->set('sector',$this->cfpd02_sector->findAll('ano='.$ejercicio,null,null,null,null,null));
		   $this->set('programa',$this->cfpd02_programa->findAll('ano='.$ejercicio,null,null,null,null,null));
		   $this->set('subprograma',$this->cfpd02_sub_prog->findAll('ano='.$ejercicio,null,null,null,null,null));
		   $this->set('proyecto',$this->cfpd02_proyecto->findAll('ano='.$ejercicio,null,null,null,null,null));
		   $this->set('actividad',$this->cfpd02_activ_obra->findAll('ano='.$ejercicio,null,null,null,null,null));
		   $this->set('partida',$this->cfpd01_ano_partida->findAll('ejercicio='.$ejercicio.' and cod_grupo='.CE,null,null,null, null, null));
		   $this->set('generica',$this->cfpd01_ano_generica->findAll('ejercicio='.$ejercicio.' and cod_grupo='.CE,null,null,null, null, null));
		   $this->set('especifica',$this->cfpd01_ano_especifica->findAll('ejercicio='.$ejercicio.' and cod_grupo='.CE,null,null,null, null, null));
		   $this->set('subespecifica',$this->cfpd01_ano_sub_espec->findAll('ejercicio='.$ejercicio.' and cod_grupo='.CE,null,null,null, null, null));
		   $this->set('DATOS',$DATOS_res);
           $this->set('ejercicio', $ejercicio);

           $this->consulta($ejercicio);
           $this->render('consulta');

 }//FIN FUNCTION
*/
 /**
 * ********************CONSULTAS***********************
 */


function consulta($ejercicio=null, $pag_num=null) {
 	$this->layout = "ajax";
 	$cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep=".$cod_dep;
 		$this->set('entidadFederal',$this->verifica_SS(6));

	  if($ejercicio!=null){
	  	    $this->set('ejercicio', $ejercicio);
		}else if(
		$this->data['cfpp05auxiliar']['ano']){
		$this->set('ejercicio', $this->data['cfpp05auxiliar']['ano']);
		$ejercicio = $this->data['cfpp05auxiliar']['ano'];

		}
		if($ejercicio==""){
			//$this->index();
			//$this->render("index");
		}else{
		$DATOS_res = $this->cfpp05auxiliar->findAll($condicion.' and ano='.$ejercicio.'', null, 'cod_sector, cod_programa, cod_sub_prog, cod_proyecto, cod_activ_obra, cod_partida, cod_generica, cod_especifica, cod_sub_espec, cod_auxiliar ASC', null, null, null);

        if($DATOS_res!=null){
        	        //$sector= $this->
   //echo $ejercicio;
   $this->set('sector',$this->cfpd02_sector->findAll($this->SQLCA($ejercicio),null,null,null,null,null));
   $this->set('programa',$this->cfpd02_programa->findAll($this->SQLCA($ejercicio),null,null,null,null,null));
   $this->set('subprograma',$this->cfpd02_sub_prog->findAll($this->SQLCA($ejercicio),null,null,null,null,null));
   $this->set('proyecto',$this->cfpd02_proyecto->findAll($this->SQLCA($ejercicio),null,null,null,null,null));
   $this->set('actividad',$this->cfpd02_activ_obra->findAll($this->SQLCA($ejercicio),null,null,null,null,null));
   //$this->set('grupo',$this->cfpd01_ano_grupo->findAll());
   $this->set('partida',$this->cfpd01_ano_partida->findAll('ejercicio='.$ejercicio.' and cod_grupo='.CE,null,null,null, null, null));
   $this->set('generica',$this->cfpd01_ano_generica->findAll('ejercicio='.$ejercicio.' and cod_grupo='.CE,null,null,null, null, null));
   $this->set('especifica',$this->cfpd01_ano_especifica->findAll('ejercicio='.$ejercicio.' and cod_grupo='.CE,null,null,null, null, null));
   $this->set('subespecifica',$this->cfpd01_ano_sub_espec->findAll('ejercicio='.$ejercicio.' and cod_grupo='.CE,null,null,null, null, null));
         $this->set('DATOS',$DATOS_res);
        //print_r($DATOS_res);

$this->set('ejercicio', $ejercicio);
if($pag_num!=null){$this->set('pagina_actual', $pag_num);  }

        }else{
        $this->set('errorMessage', 'Error: No hay Datos');
 	    //$this->index();
      	//$this->render("index");
      	//print_r($DATOS_res);

        }//fin else datosres_null
		}//fin ejercicio vacio
 }//fin function consultar


















/**
function consulta2($ejercicio=null, $pag_num=null) {
 		$this->layout = "ajax";
 	$cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep=".$cod_dep;
    $this->set('entidadFederal',$this->verifica_SS(6));
    if(isset($pag_num)){

    	if($ejercicio!=null){
	  	    $this->set('ejercicio', $ejercicio);
		}else if($this->data['cfpp05auxiliar']['ano']){
		$this->set('ejercicio', $this->data['cfpp05auxiliar']['ano']);
		$ejercicio = $this->data['cfpp05auxiliar']['ano'];
		}


        if($ejercicio!=null){
	  	    $this->set('ejercicio', $ejercicio);
		}
		$Tfilas=$this->cfpp05auxiliar->findCount($condicion.' and ano='.$ejercicio.'');
		if($Tfilas!=0){
           $DATOS_res = $this->cfpp05auxiliar->findAll($condicion.' and ano='.$ejercicio.'', null, 'cod_sector, cod_programa, cod_sub_prog, cod_proyecto, cod_activ_obra, cod_partida, cod_generica, cod_especifica, cod_sub_espec, cod_auxiliar ASC',1,$pag_num, null);
		    $ba[1]=$DATOS_res[0]["cfpp05auxiliar"]["cod_sector"];
		    $ba[2]=$DATOS_res[0]["cfpp05auxiliar"]["cod_programa"];
		    $ba[3]=$DATOS_res[0]["cfpp05auxiliar"]["cod_sub_prog"];
		    $ba[4]=$DATOS_res[0]["cfpp05auxiliar"]["cod_proyecto"];
		    $ba[5]=$DATOS_res[0]["cfpp05auxiliar"]["cod_activ_obra"];
		    $ba[6]=substr($DATOS_res[0]["cfpp05auxiliar"]["cod_partida"],-2);
		    $ba[7]=$DATOS_res[0]["cfpp05auxiliar"]["cod_generica"];
		    $ba[8]=$DATOS_res[0]["cfpp05auxiliar"]["cod_especifica"];
		    $ba[9]=$DATOS_res[0]["cfpp05auxiliar"]["cod_sub_espec"];
		    $this->set('sector',$this->cfpd02_sector->findAll($this->SQLCA($ejercicio)." and cod_sector=".$ba[1],array('denominacion'),null,1,1,null));
	        $this->set('programa',$this->cfpd02_programa->findAll($this->SQLCA($ejercicio)." and cod_sector=".$ba[1]." and cod_programa=".$ba[2],array('denominacion'),null,1,1,null));
	        $this->set('subprograma',$this->cfpd02_sub_prog->findAll($this->SQLCA($ejercicio)." and cod_sector=".$ba[1]." and cod_programa=".$ba[2]." and cod_sub_prog=".$ba[3],array('denominacion'),null,1,1,null));
	        $this->set('proyecto',$this->cfpd02_proyecto->findAll($this->SQLCA($ejercicio)." and cod_sector=".$ba[1]." and cod_programa=".$ba[2]." and cod_sub_prog=".$ba[3]." and cod_proyecto=".$ba[4],array('denominacion'),null,1,1,null));
	        $this->set('actividad',$this->cfpd02_activ_obra->findAll($this->SQLCA($ejercicio)." and cod_sector=".$ba[1]." and cod_programa=".$ba[2]." and cod_sub_prog=".$ba[3]." and cod_proyecto=".$ba[4]." and cod_activ_obra=".$ba[5],array('denominacion'),null,1,1,null));
	        $this->set('partida',$this->cfpd01_ano_partida->findAll("ejercicio=".$ejercicio." and cod_grupo=".CE." and cod_partida=".$ba[6],array('denominacion'),null,1, 1, null));
	        $this->set('generica',$this->cfpd01_ano_generica->findAll("ejercicio=".$ejercicio." and cod_grupo=".CE." and cod_partida=".$ba[6]." and cod_generica=".$ba[7],array('denominacion'),null,1, 1, null));
	        $this->set('especifica',$this->cfpd01_ano_especifica->findAll("ejercicio=".$ejercicio." and cod_grupo=".CE." and cod_partida=".$ba[6]." and cod_generica=".$ba[7]." and cod_especifica=".$ba[8],array('denominacion'),null,1, 1, null));
	        $this->set('subespecifica',$this->cfpd01_ano_sub_espec->findAll("ejercicio=".$ejercicio." and cod_grupo=".CE." and cod_partida=".$ba[6]." and cod_generica=".$ba[7]." and cod_especifica=".$ba[8]." and cod_sub_espec=".$ba[9],array('denominacion'),null,1, 1, null));
	        $this->set('DATA',$DATOS_res);
	        $this->set('siguiente',$pag_num+1);
	        $this->set('anterior',$pag_num-1);
	        $this->bt_nav($Tfilas,$pag_num);
	        $this->set('ultimo',$Tfilas);
		}else{
			$this->set('errorMessage', 'No se encontrar&oacute;n datos');
	 	       $this->index();
	 	       $this->render("index");
		}
    }else{
    	$pag_num=1;
    	if($ejercicio!=null){
	  	    $this->set('ejercicio', $ejercicio);
		}else if($this->data['cfpp05auxiliar']['ano']){
		$this->set('ejercicio', $this->data['cfpp05auxiliar']['ano']);
		$ejercicio = $this->data['cfpp05auxiliar']['ano'];
		}
		$Tfilas=$this->cfpp05auxiliar->findCount($condicion.' and ano='.$ejercicio.'');
		if($Tfilas!=0){
           $DATOS_res = $this->cfpp05auxiliar->findAll($condicion.' and ano='.$ejercicio.'', null, 'cod_sector, cod_programa, cod_sub_prog, cod_proyecto, cod_activ_obra, cod_partida, cod_generica, cod_especifica, cod_sub_espec, cod_auxiliar ASC',1,$pag_num, null);

		    $ba[1]=$DATOS_res[0]["cfpp05auxiliar"]["cod_sector"];
		    $ba[2]=$DATOS_res[0]["cfpp05auxiliar"]["cod_programa"];
		    $ba[3]=$DATOS_res[0]["cfpp05auxiliar"]["cod_sub_prog"];
		    $ba[4]=$DATOS_res[0]["cfpp05auxiliar"]["cod_proyecto"];
		    $ba[5]=$DATOS_res[0]["cfpp05auxiliar"]["cod_activ_obra"];
		    $ba[6]=substr($DATOS_res[0]["cfpp05auxiliar"]["cod_partida"],-2);
		    $ba[7]=$DATOS_res[0]["cfpp05auxiliar"]["cod_generica"];
		    $ba[8]=$DATOS_res[0]["cfpp05auxiliar"]["cod_especifica"];
		    $ba[9]=$DATOS_res[0]["cfpp05auxiliar"]["cod_sub_espec"];
		    $this->set('sector',$this->cfpd02_sector->findAll($this->SQLCA($ejercicio)." and cod_sector=".$ba[1],array('denominacion'),null,1,1,null));
	        $this->set('programa',$this->cfpd02_programa->findAll($this->SQLCA($ejercicio)." and cod_sector=".$ba[1]." and cod_programa=".$ba[2],array('denominacion'),null,1,1,null));
	        $this->set('subprograma',$this->cfpd02_sub_prog->findAll($this->SQLCA($ejercicio)." and cod_sector=".$ba[1]." and cod_programa=".$ba[2]." and cod_sub_prog=".$ba[3],array('denominacion'),null,1,1,null));
	        $this->set('proyecto',$this->cfpd02_proyecto->findAll($this->SQLCA($ejercicio)." and cod_sector=".$ba[1]." and cod_programa=".$ba[2]." and cod_sub_prog=".$ba[3]." and cod_proyecto=".$ba[4],array('denominacion'),null,1,1,null));
	        $this->set('actividad',$this->cfpd02_activ_obra->findAll($this->SQLCA($ejercicio)." and cod_sector=".$ba[1]." and cod_programa=".$ba[2]." and cod_sub_prog=".$ba[3]." and cod_proyecto=".$ba[4]." and cod_activ_obra=".$ba[5],array('denominacion'),null,1,1,null));
	        $this->set('partida',$this->cfpd01_ano_partida->findAll("ejercicio=".$ejercicio." and cod_grupo=".CE." and cod_partida=".$ba[6],array('denominacion'),null,1, 1, null));
	        $this->set('generica',$this->cfpd01_ano_generica->findAll("ejercicio=".$ejercicio." and cod_grupo=".CE." and cod_partida=".$ba[6]." and cod_generica=".$ba[7],array('denominacion'),null,1, 1, null));
	        $this->set('especifica',$this->cfpd01_ano_especifica->findAll("ejercicio=".$ejercicio." and cod_grupo=".CE." and cod_partida=".$ba[6]." and cod_generica=".$ba[7]." and cod_especifica=".$ba[8],array('denominacion'),null,1, 1, null));
	        $this->set('subespecifica',$this->cfpd01_ano_sub_espec->findAll("ejercicio=".$ejercicio." and cod_grupo=".CE." and cod_partida=".$ba[6]." and cod_generica=".$ba[7]." and cod_especifica=".$ba[8]." and cod_sub_espec=".$ba[9],array('denominacion'),null,1, 1, null));
	        $this->set('DATA',$DATOS_res);
	        $this->set('siguiente',$pag_num+1);
	        $this->set('anterior',$pag_num-1);
	        $this->bt_nav($Tfilas,$pag_num);
	        $this->set('ultimo',$Tfilas);
		}else{
			$this->set('errorMessage', 'No se encontrar&oacute;n datos');
	 	       $this->index();
	 	       $this->render("index");
		}

    }
	  		if($ejercicio==""){
			//$this->index();
			//$this->render("index");
		}else{
		$DATOS_res = $this->cfpp05auxiliar->findAll($condicion.' and ano='.$ejercicio.'', null, 'cod_sector, cod_programa, cod_sub_prog, cod_proyecto, cod_activ_obra, cod_partida, cod_generica, cod_especifica, cod_sub_espec, cod_auxiliar ASC', null, null, null);
       // print_r($DATOS_res);
        if($DATOS_res!=null){
        	        //$sector= $this->
   //echo $ejercicio;

         $this->set('DATOS',$DATOS_res);
        //print_r($DATOS_res);

$this->set('ejercicio', $ejercicio);
if($pag_num!=null){$this->set('pagina_actual', $pag_num);  }

        }else{
        $this->set('errorMessage', 'No hay Datos');
 	    //$this->index();
      	//$this->render("index");
      	//print_r($DATOS_res);

        }//fin else datosres_null
		}//fin ejercicio vacio
 }//fin function consultar2
*/

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
/**
function campo_denominacion($v1,$v2,$v3,$v4,$v5,$v6,$v7,$v8,$v9,$v10,$ano){
 	$this->layout = "ajax";
 	$condicion=$this->SQLCA($ano)." and cod_sector=".$v1." and ";
    $condicion .="cod_programa=".$v2." and ";
    $condicion .="cod_sub_prog=".$v3." and ";
    $condicion .="cod_proyecto=".$v4." and ";
    $condicion .="cod_activ_obra=".$v5." and ";
    $condicion .="cod_partida=".$v6." and ";
    $condicion .="cod_generica=".$v7." and ";
    $condicion .="cod_especifica=".$v8." and ";
    $condicion .="cod_sub_espec=".$v9." and ";
    $condicion .="cod_auxiliar=".$v10." ";
    $res=$this->cfpp05auxiliar->findAll($condicion,array('denominacion'));
    $Vcodigos = array($v1,$v2,$v3,$v4,$v5,$v6,$v7,$v8,$v9,$v10,$ano);
    $this->set('codigos',$Vcodigos);
    $this->set('denominacion',$res[0]['cfpp05auxiliar']['denominacion']);
}
*/
/**
 function modificar($v1,$v2,$v3,$v4,$v5,$v6,$v7,$v8,$v9,$v10,$ano){
 	$this->layout = "ajax";
 	$cond1=$this->SQLCA($ano)." ";
 	$cond2=$this->SQLCA_dep_uno($ano)." ";
 	$condicion=" and cod_sector=".$v1." and ";
    $condicion .="cod_programa=".$v2." and ";
    $condicion .="cod_sub_prog=".$v3." and ";
    $condicion .="cod_proyecto=".$v4." and ";
    $condicion .="cod_activ_obra=".$v5." and ";
    $condicion .="cod_partida=".$v6." and ";
    $condicion .="cod_generica=".$v7." and ";
    $condicion .="cod_especifica=".$v8." and ";
    $condicion .="cod_sub_espec=".$v9." and ";
    $condicion .="cod_auxiliar=".$v10."";
    if(!empty($this->data['cfpp05auxiliar']['denominacion'])){
         $this->cfpd05_auxiliar->execute("UPDATE cfpd05_auxiliar SET denominacion='".$this->data['cfpp05auxiliar']['denominacion']."' WHERE ".$cond1.$condicion);
         if($this->verifica_SS(5)!=1){
         $this->cfpd05_auxiliar->execute("UPDATE cfpd05_auxiliar SET denominacion='".$this->data['cfpp05auxiliar']['denominacion']."' WHERE ".$cond2.$condicion);
         }
         $res=$this->cfpd05_auxiliar->findAll($cond1.$condicion,array('denominacion'));
         echo '<div id="ds">'.$res[0]['cfpd05_auxiliar']['denominacion'].'</div>';
    }else{
         $res=$this->cfpd05_auxiliar->findAll($condicion,'denominacion');
         echo '<div id="ds">'.$res[0]['cfpp05auxiliar']['denominacion'].'</div>';
    }
}//fin modificar
*/
/**
function mostrar_deno($v1,$v2,$v3,$v4,$v5,$v6,$v7,$v8,$v9,$v10,$ano) {
     $this->layout = "ajax";
 	$condicion=$this->SQLCA($ano)." and cod_sector=".$v1." and ";
    $condicion .="cod_programa=".$v2." and ";
    $condicion .="cod_sub_prog=".$v3." and ";
    $condicion .="cod_proyecto=".$v4." and ";
    $condicion .="cod_activ_obra=".$v5." and ";
    $condicion .="cod_partida=".$v6." and ";
    $condicion .="cod_generica=".$v7." and ";
    $condicion .="cod_especifica=".$v8." and ";
    $condicion .="cod_sub_espec=".$v9." and ";
    $condicion .="cod_auxiliar=".$v10." ";
    //echo $condicion;
    $res=$this->cfpp05auxiliar->findAll($condicion,array('denominacion'));
    echo    '<div id="ds">'.$res[0]['cfpp05auxiliar']['denominacion'].'</div>';
}
*/

/**
function buscar ($ejercicio=null) {
	$this->layout="ajax";

	if($ejercicio!=null){

$this->set('ano',$ejercicio);

	}else{

	echo $this->data["cfpp05auxiliar"]["ano"];
	$this->set('ano',$this->data["cfpp05auxiliar"]["ano"]);

	}//fin else
}
*/


/**
function lista_encontrados ($ejercicio) {
	$this->layout="ajax";

	if(!empty($this->data["cfpp05auxiliar"])){
		$cod =" and cod_sector=".$this->data["cfpp05auxiliar"]["cod_sector"];
		$cod .=" and cod_programa=".$this->data["cfpp05auxiliar"]["cod_programa"];
		$cod .=" and cod_sub_prog=".$this->data["cfpp05auxiliar"]["cod_sub_prog"];
		$cod .=" and cod_proyecto=".$this->data["cfpp05auxiliar"]["cod_proyecto"];
		$cod .=" and cod_activ_obra=".$this->data["cfpp05auxiliar"]["cod_activ_obra"];
		$cod .=" and cod_partida=".$this->data["cfpp05auxiliar"]["cod_partida"];
		$cod .=" and cod_generica=".$this->data["cfpp05auxiliar"]["cod_generica"];
		$cod .=" and cod_especifica=".$this->data["cfpp05auxiliar"]["cod_especifica"];
		$cod .=" and cod_sub_espec=".$this->data["cfpp05auxiliar"]["cod_sub_espec"];
		$cod .=" and cod_auxiliar=".$this->data["cfpp05auxiliar"]["cod_auxiliar"];

		$cod_presi = $this->Session->read('SScodpresi');
		$cod_entidad = $this->Session->read('SScodentidad');
		$cod_tipo_inst = $this->Session->read('SScodtipoinst');
		$cod_inst = $this->Session->read('SScodinst');
		$cod_dep = $this->Session->read('SScoddep');
		$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep=".$cod_dep;
	    $this->set('entidadFederal',$this->verifica_SS(6));
	        if($ejercicio!=null){
		  	    $this->set('ejercicio', $ejercicio);
			}



if($this->data["cfpp05auxiliar"]["cod_auxiliar"]!=""){

			$Tfilas=$this->cfpp05auxiliar->findCount($condicion." and ano=".$ejercicio.$cod);
			if($Tfilas!=0){
	           $DATOS_res = $this->cfpp05auxiliar->findAll($condicion." and ano=".$ejercicio.$cod, null, 'cod_sector, cod_programa, cod_sub_prog, cod_proyecto, cod_activ_obra, cod_partida, cod_generica, cod_especifica, cod_sub_espec, cod_auxiliar ASC',1,1, null);
			    $ba[1]=$DATOS_res[0]["cfpp05auxiliar"]["cod_sector"];
			    $ba[2]=$DATOS_res[0]["cfpp05auxiliar"]["cod_programa"];
			    $ba[3]=$DATOS_res[0]["cfpp05auxiliar"]["cod_sub_prog"];
			    $ba[4]=$DATOS_res[0]["cfpp05auxiliar"]["cod_proyecto"];
			    $ba[5]=$DATOS_res[0]["cfpp05auxiliar"]["cod_activ_obra"];
			    $ba[6]=substr($DATOS_res[0]["cfpp05auxiliar"]["cod_partida"],-2);
			    $ba[7]=$DATOS_res[0]["cfpp05auxiliar"]["cod_generica"];
			    $ba[8]=$DATOS_res[0]["cfpp05auxiliar"]["cod_especifica"];
			    $ba[9]=$DATOS_res[0]["cfpp05auxiliar"]["cod_sub_espec"];
			    $this->set('sector',$this->cfpd02_sector->findAll($this->SQLCA($ejercicio)." and cod_sector=".$ba[1],array('denominacion'),null,1,1,null));
		        $this->set('programa',$this->cfpd02_programa->findAll($this->SQLCA($ejercicio)." and cod_sector=".$ba[1]." and cod_programa=".$ba[2],array('denominacion'),null,1,1,null));
		        $this->set('subprograma',$this->cfpd02_sub_prog->findAll($this->SQLCA($ejercicio)." and cod_sector=".$ba[1]." and cod_programa=".$ba[2]." and cod_sub_prog=".$ba[3],array('denominacion'),null,1,1,null));
		        $this->set('proyecto',$this->cfpd02_proyecto->findAll($this->SQLCA($ejercicio)." and cod_sector=".$ba[1]." and cod_programa=".$ba[2]." and cod_sub_prog=".$ba[3]." and cod_proyecto=".$ba[4],array('denominacion'),null,1,1,null));
		        $this->set('actividad',$this->cfpd02_activ_obra->findAll($this->SQLCA($ejercicio)." and cod_sector=".$ba[1]." and cod_programa=".$ba[2]." and cod_sub_prog=".$ba[3]." and cod_proyecto=".$ba[4]." and cod_activ_obra=".$ba[5],array('denominacion'),null,1,1,null));
		        $this->set('partida',$this->cfpd01_ano_partida->findAll("ejercicio=".$ejercicio." and cod_grupo=".CE." and cod_partida=".$ba[6],array('denominacion'),null,1, 1, null));
		        $this->set('generica',$this->cfpd01_ano_generica->findAll("ejercicio=".$ejercicio." and cod_grupo=".CE." and cod_partida=".$ba[6]." and cod_generica=".$ba[7],array('denominacion'),null,1, 1, null));
		        $this->set('especifica',$this->cfpd01_ano_especifica->findAll("ejercicio=".$ejercicio." and cod_grupo=".CE." and cod_partida=".$ba[6]." and cod_generica=".$ba[7]." and cod_especifica=".$ba[8],array('denominacion'),null,1, 1, null));
		        $this->set('subespecifica',$this->cfpd01_ano_sub_espec->findAll("ejercicio=".$ejercicio." and cod_grupo=".CE." and cod_partida=".$ba[6]." and cod_generica=".$ba[7]." and cod_especifica=".$ba[8]." and cod_sub_espec=".$ba[9],array('denominacion'),null,1, 1, null));
		        $this->set('DATA',$DATOS_res);
		        $this->set('mostrarA',false);
		        $this->set('mostrarS',false);
		        $this->set('ultimo',1);
	    }else{
	    	$this->set('errorMessage', 'No se encontrar&oacute;n datos');
	    }
	}else{$this->set('errorMessage', 'Faltan datos'); $this->vacio(); $this->render('vacio'); }

	}

}//fin lista_encontrados
*/




function modificar_auxiliar_grid ($ano,$cod_dep,$cod_sector,$cod_programa,$cod_sub_prog,$cod_proyecto,$cod_activ_obra,$cod_partida,$cod_generica,$cod_especifica,$cod_sub_espec,$cod_auxiliar,$id,$id_actualizar=null) {
         $this->layout="ajax";
        $this->set('ano',$ano);
        $this->set("i",$id);

        $this->set("id_actualizar",$id_actualizar);

        $d1 = $this->verifica_SS(1);
		$d2 = $this->verifica_SS(2);
	 	$d3 = $this->verifica_SS(3);
	 	$d4 = $this->verifica_SS(4);
	 	$d5 = $this->verifica_SS(5);

		$d17 = $this->data['cfpp05auxiliarv2']['auxiliar_'.$id];
	//	$cod_auxiliar=$this->data['cfpp05auxiliarv2']['cod_auxiliar_'.$id];
		$sql_verificar  =" cod_presi=".$d1." and cod_entidad=".$d2." and cod_tipo_inst=".$d3." and cod_inst=".$d4." and cod_dep=".$d5." and ano=".$ano;
		$sql_verificar .=" and cod_sector=".$cod_sector." and cod_programa=".$cod_programa." and cod_sub_prog=".$cod_sub_prog." and cod_proyecto=".$cod_proyecto." and cod_activ_obra=".$cod_activ_obra;
		$sql_verificar .=" and cod_partida=".$cod_partida." and cod_generica=".$cod_generica." and cod_especifica=".$cod_especifica." and cod_sub_espec=".$cod_sub_espec." and cod_auxiliar=".$cod_auxiliar;
        if($this->cfpp05auxiliar->findCount($sql_verificar)==0){
            $DATA_NUEVA_FILA=$this->cfpp05auxiliar->findAll($sql_verificar,null,'cod_auxiliar DESC',1);
            $this->set('DATA_NUEVA_FILA',$DATA_NUEVA_FILA);
        }else{
            $this->set('errorMessage', 'El codigo de la auxiliar ya existe');
            $this->set('cod_aux', '');
            $DATA_NUEVA_FILA=$this->cfpp05auxiliar->findAll($sql_verificar,null,'cod_auxiliar DESC',1);
            $this->set('DATA_NUEVA_FILA',$DATA_NUEVA_FILA);
              $this->data['cfpp05auxiliarv2']=null;
        }
}//fin modificar_auxiliar_grid

function cancelar_modificar_auxiliar_grid ($ano,$cod_dep,$cod_sector,$cod_programa,$cod_sub_prog,$cod_proyecto,$cod_activ_obra,$cod_partida,$cod_generica,$cod_especifica,$cod_sub_espec,$cod_auxiliar,$id,$id_actualizar=null) {
         $this->layout="ajax";
        $this->set('ano',$ano);
        $this->set("i",$id);
        $this->set("id_actualizar",$id_actualizar);

        $d1 = $this->verifica_SS(1);
		$d2 = $this->verifica_SS(2);
	 	$d3 = $this->verifica_SS(3);
	 	$d4 = $this->verifica_SS(4);
	 	$d5 = $this->verifica_SS(5);

		$d17 = $this->data['cfpp05auxiliarv2']['auxiliar_'.$id];
	//	$cod_auxiliar=$this->data['cfpp05auxiliarv2']['cod_auxiliar_'.$id];
		$sql_verificar  =" cod_presi=".$d1." and cod_entidad=".$d2." and cod_tipo_inst=".$d3." and cod_inst=".$d4." and cod_dep=".$d5." and ano=".$ano;
		$sql_verificar .=" and cod_sector=".$cod_sector." and cod_programa=".$cod_programa." and cod_sub_prog=".$cod_sub_prog." and cod_proyecto=".$cod_proyecto." and cod_activ_obra=".$cod_activ_obra;
		$sql_verificar .=" and cod_partida=".$cod_partida." and cod_generica=".$cod_generica." and cod_especifica=".$cod_especifica." and cod_sub_espec=".$cod_sub_espec." and cod_auxiliar=".$cod_auxiliar;
        if($this->cfpp05auxiliar->findCount($sql_verificar)==0){
            $DATA_NUEVA_FILA=$this->cfpp05auxiliar->findAll($sql_verificar,null,'cod_auxiliar DESC',1);
            $this->set('DATA_NUEVA_FILA',$DATA_NUEVA_FILA);
        }else{
            $this->set('errorMessage', 'El codigo de la auxiliar ya existe');
            $this->set('cod_aux', '');
            $DATA_NUEVA_FILA=$this->cfpp05auxiliar->findAll($sql_verificar,null,'cod_auxiliar DESC',1);
            $this->set('DATA_NUEVA_FILA',$DATA_NUEVA_FILA);
              $this->data['cfpp05auxiliarv2']=null;
        }
}//fin cancelar_modificar_auxiliar_grid


function eliminar_auxiliar_grid ($ano,$cod_dep,$cod_sector,$cod_programa,$cod_sub_prog,$cod_proyecto,$cod_activ_obra,$cod_partida,$cod_generica,$cod_especifica,$cod_sub_espec,$cod_auxiliar,$id,$id_actualizar=null) {
         $this->layout="ajax";
        $this->set('ano',$ano);
        $this->set("i",$id);
        $this->set("id_actualizar",$id_actualizar);

        $d1 = $this->verifica_SS(1);
		$d2 = $this->verifica_SS(2);
	 	$d3 = $this->verifica_SS(3);
	 	$d4 = $this->verifica_SS(4);
	 	$d5 = $this->verifica_SS(5);

	//	$cod_auxiliar=$this->data['cfpp05auxiliarv2']['cod_auxiliar_'.$id];
	    $sql_verificard  =" cod_presi=".$d1." and cod_entidad=".$d2." and cod_tipo_inst=".$d3." and cod_inst=".$d4." and cod_dep=".$d5." and ano=".$ano;
		$sql_verificard .=" and cod_sector=".$cod_sector." and cod_programa=".$cod_programa." and cod_sub_prog=".$cod_sub_prog." and cod_proyecto=".$cod_proyecto." and cod_activ_obra=".$cod_activ_obra;
		$sql_verificard .=" and cod_partida=".$cod_partida." and cod_generica=".$cod_generica." and cod_especifica=".$cod_especifica." and cod_sub_espec=".$cod_sub_espec." and cod_auxiliar=".$cod_auxiliar;

		$sql_verificar  =" cod_presi=".$d1." and cod_entidad=".$d2." and cod_tipo_inst=".$d3." and cod_inst=".$d4." and ano=".$ano;
		$sql_verificar .=" and cod_sector=".$cod_sector." and cod_programa=".$cod_programa." and cod_sub_prog=".$cod_sub_prog." and cod_proyecto=".$cod_proyecto." and cod_activ_obra=".$cod_activ_obra;
		$sql_verificar .=" and cod_partida=".$cod_partida." and cod_generica=".$cod_generica." and cod_especifica=".$cod_especifica." and cod_sub_espec=".$cod_sub_espec." and cod_auxiliar=".$cod_auxiliar;
        $movimientos= " aumento_traslado_anual=0 and disminucion_traslado_anual=0 and credito_adicional_anual=0 and rebaja_anual=0 and compromiso_anual=0 and causado_anual=0 and pagado_anual=0 ";
        $cantidad_auxiliar=$this->cfpd05->findCount($sql_verificar);
        $cantidad_auxiliar_movimiento=$this->cfpd05->findCount($sql_verificar." and ".$movimientos);
        $cantidad_auxiliarcd=$this->cfpd05->findCount($sql_verificar." and cod_dep=".$d5);
        $cantidad_auxiliar_movimientocd=$this->cfpd05->findCount($sql_verificar." and ".$movimientos." and cod_dep=".$d5);

        $cantidad_auxiliar_reformulacion=$this->cfpd10_reformulacion_partidas->findCount($sql_verificar);
        $cantidad_auxiliar_reformulacion_tmp=$this->cfpd10_reformulacion_partidas_tmp->findCount($sql_verificar);

        $cantidad_auxiliar_reformulacion_cd=$this->cfpd10_reformulacion_partidas->findCount($sql_verificar." and codi_dep=".$d5);
        $cantidad_auxiliar_reformulacion_tmp_cd=$this->cfpd10_reformulacion_partidas_tmp->findCount($sql_verificar." and codi_dep=".$d5);
        if($cantidad_auxiliar_reformulacion!=0 || $cantidad_auxiliar_reformulacion_tmp!=0 || $cantidad_auxiliar_reformulacion_cd!=0 || $cantidad_auxiliar_reformulacion_tmp_cd!=0){
	                $this->set('errorMessage', 'El Registro que desea eliminar no se puede proceder, esta utilizado en reformulaci&oacute;n');
		            $this->set('cod_aux', '');
		            $DATA_NUEVA_FILA=$this->cfpp05auxiliar->findAll($sql_verificard,null,'cod_auxiliar DESC',1);
		            $this->set('DATA_NUEVA_FILA',$DATA_NUEVA_FILA);
		            $this->data['cfpp05auxiliarv2']=null;
	    }else{
	        if($cantidad_auxiliar!=0 || $cantidad_auxiliarcd!=0){
	            if($cantidad_auxiliar_movimiento!=0 || $cantidad_auxiliar_movimientocd!=0){
	            	//cfpd05_requerimiento
	            	$contar_requerimientos_esp=$this->cfpd05_requerimiento->findCount($sql_verificar);
	                $rs_del2=$this->cfpd05->execute("DELETE FROM cfpd05 WHERE ".$sql_verificar." and  ".$movimientos);
		          //  echo "DELETE FROM cfpd05 WHERE ".$sql_verificar." and ".$movimientos;
		          //pr($rs_del2);
		            if($rs_del2>1){
		            	$rs_del1=$this->cfpp05auxiliar->execute("DELETE FROM cfpd05_auxiliar WHERE ".$sql_verificar."");
//		            	$rs_del1_d=$this->cfpp05auxiliar->execute("DELETE FROM cfpd05_auxiliar WHERE ".$sql_verificar."");
		            	if($rs_del1>1){
		            		$this->set('Message_existe', 'El Registro ha sido eliminado exitosamente');
		            	}else{
		            		$DATA_NUEVA_FILA=$this->cfpp05auxiliar->findAll($sql_verificar,null,'cod_auxiliar DESC',1);
		                    $this->set('DATA_NUEVA_FILA',$DATA_NUEVA_FILA);
		            		$this->set('errorMessage', 'El Registro no fue eliminado');
		            	}
		            }
	            }else{
	            	$this->set('errorMessage', 'El Registro que desea eliminar tiene movimientos presupuestarios, por favor verifique');
		            $this->set('cod_aux', '');
		            $DATA_NUEVA_FILA=$this->cfpp05auxiliar->findAll($sql_verificard,null,'cod_auxiliar DESC',1);
		            $this->set('DATA_NUEVA_FILA',$DATA_NUEVA_FILA);
		            $this->data['cfpp05auxiliarv2']=null;
	            }

	        }else{
	        	    $rs_del1=$this->cfpp05auxiliar->execute("DELETE FROM cfpd05_auxiliar WHERE ".$sql_verificar);
	            	if($rs_del1>1){
	            		$this->set('Message_existe', 'El Registro ha sido eliminado exitosamente');
	            	}else{
	            		$DATA_NUEVA_FILA=$this->cfpp05auxiliar->findAll($sql_verificar,null,'cod_auxiliar DESC',1);
	                    $this->set('DATA_NUEVA_FILA',$DATA_NUEVA_FILA);
	            		$this->set('errorMessage', 'El Registro no fue eliminado');
	            	}
	        }

	//----------------
}



}//fin eliminar_auxiliar_grid


function guardar_modificar_auxiliar_grid ($ano,$cod_dep,$cod_sector,$cod_programa,$cod_sub_prog,$cod_proyecto,$cod_activ_obra,$cod_partida,$cod_generica,$cod_especifica,$cod_sub_espec,$cod_auxiliar,$id,$id_actualizar=null) {
         $this->layout="ajax";
        $this->set('ano',$ano);
        $this->set("i",$id);
        $id_actualizar=strtolower($this->data['cfpp05auxiliarv2']['id_actualizar']);
        $this->set("id_actualizar",$id_actualizar);
        $d1 = $this->verifica_SS(1);
		$d2 = $this->verifica_SS(2);
	 	$d3 = $this->verifica_SS(3);
	 	$d4 = $this->verifica_SS(4);
	 	$d5 = $this->verifica_SS(5);

		$d17 = $this->data['cfpp05auxiliarv2']['auxiliar_'.$id];
		//$cod_auxiliar=$this->data['cfpp05auxiliarv2']['cod_auxiliar_'.$id];
        $sql_verificar_f  =" cod_presi=".$d1." and cod_entidad=".$d2." and cod_tipo_inst=".$d3." and cod_inst=".$d4." and cod_dep=".$d5." and ano=".$ano;
		$sql_verificar_f .=" and cod_sector=".$cod_sector." and cod_programa=".$cod_programa." and cod_sub_prog=".$cod_sub_prog." and cod_proyecto=".$cod_proyecto." and cod_activ_obra=".$cod_activ_obra;
		$sql_verificar_f .=" and cod_partida=".$cod_partida." and cod_generica=".$cod_generica." and cod_especifica=".$cod_especifica." and cod_sub_espec=".$cod_sub_espec;


        //$sql_verificar  =" cod_presi=".$d1." and cod_entidad=".$d2." and cod_tipo_inst=".$d3." and cod_inst=".$d4." and cod_dep=".$d5." and ano=".$ano;
		$sql_verificar  =" cod_presi=".$d1." and cod_entidad=".$d2." and cod_tipo_inst=".$d3." and cod_inst=".$d4." and ano=".$ano;
		$sql_verificar .=" and cod_sector=".$cod_sector." and cod_programa=".$cod_programa." and cod_sub_prog=".$cod_sub_prog." and cod_proyecto=".$cod_proyecto." and cod_activ_obra=".$cod_activ_obra;
		$sql_verificar .=" and cod_partida=".$cod_partida." and cod_generica=".$cod_generica." and cod_especifica=".$cod_especifica." and cod_sub_espec=".$cod_sub_espec." and cod_auxiliar=".$cod_auxiliar;
      //  echo $sql_verificar;
        $sql_verificar_dpuno="cod_presi=".$d1." and cod_entidad=".$d2." and cod_tipo_inst=".$d3." and cod_inst=".$d4." and cod_dep=1 and ano=".$ano;
		$sql_verificar_dpuno .=" and cod_sector=".$cod_sector." and cod_programa=".$cod_programa." and cod_sub_prog=".$cod_sub_prog." and cod_proyecto=".$cod_proyecto." and cod_activ_obra=".$cod_activ_obra;
		$sql_verificar_dpuno .=" and cod_partida=".$cod_partida." and cod_generica=".$cod_generica." and cod_especifica=".$cod_especifica." and cod_sub_espec=".$cod_sub_espec." and cod_auxiliar=".$cod_auxiliar;
      //  echo $sql_verificar;
        $campos="cod_presi,cod_entidad,cod_tipo_inst,cod_inst,cod_dep,ano,cod_sector,cod_programa,cod_sub_prog,cod_proyecto,cod_activ_obra,cod_partida,cod_generica,cod_especifica,cod_sub_espec,cod_auxiliar,denominacion";
		$sql = "UPDATE cfpd05_auxiliar SET denominacion='$d17' WHERE ";

		//$sql_dep_uno = "INSERT INTO  cfpd05_auxiliar ($campos)VALUES ($d1,$d2,$d3,$d4,1,$ano,$cod_sector,$cod_programa,$cod_sub_prog,$cod_proyecto,$cod_activ_obra,$cod_partida,$cod_generica,$cod_especifica,$cod_sub_espec,$cod_auxiliar,'$d17')";
        if($this->cfpp05auxiliar->findCount($sql_verificar)!=0){
            $this->cfpp05auxiliar->execute($sql.$sql_verificar);
            //echo $sql.$sql_verificar;
            /*if($d5!=1 && $this->cfpp05auxiliar->findCount($sql_verificar_dpuno)!=0){
            	$this->cfpp05auxiliar->execute($sql.$sql_verificar_dpuno);
            }*/
            $this->set('Message_existe', 'Registro Actualizado exitosamente  ');
            $this->data['cfpp05auxiliarv2']=null;
            $DATA_NUEVA_FILA=$this->cfpp05auxiliar->findAll($sql_verificar." and cod_dep=".$d5,null,'cod_auxiliar DESC',1);
            $this->set('DATA_NUEVA_FILA',$DATA_NUEVA_FILA);
        }else{
            $this->set('errorMessage', 'El codigo de la auxiliar no existe');
            $this->set('cod_aux', '');
            // echo "error 2";
            $DATA_NUEVA_FILA=$this->cfpp05auxiliar->findAll($sql_verificar_f,null,'cod_auxiliar DESC',1);
            $this->set('DATA_NUEVA_FILA',$DATA_NUEVA_FILA);
              $this->data['cfpp05auxiliarv2']=null;
        }
}//fin guardar_modificar_auxiliar_grid


function pasar_ejercicio () {
    $this->layout="ajax";
		    $cod_presi = $this->Session->read('SScodpresi');
			$cod_entidad = $this->Session->read('SScodentidad');
			$cod_tipo_inst = $this->Session->read('SScodtipoinst');
			$cod_inst = $this->Session->read('SScodinst');
			$cod_dep = $this->Session->read('SScoddep');
			$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst;

		    $CAMPOS_AUXILIARES="cod_presi,cod_entidad,cod_tipo_inst,cod_inst,cod_dep,ano,cod_sector,cod_programa,cod_sub_prog,cod_proyecto,cod_activ_obra,cod_partida,cod_generica,cod_especifica,cod_sub_espec,cod_auxiliar,denominacion";
		    if(isset($this->data["cfpp05auxiliar"]["ano"]) && isset($this->data["cfpp05auxiliar"]["ano_pasar_ejercicio"])){
	        $ano_ejercicio=$this->data["cfpp05auxiliar"]["ano"];
	        $ano_ejercicio_pasar=$this->data["cfpp05auxiliar"]["ano_pasar_ejercicio"];
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
	                $this->cfpp05auxiliar->execute("DELETE FROM cfpd05_auxiliar where ano=".$ano_ejercicio_pasar." and cod_partida=403 and cod_generica=18 and cod_especifica=1");
	                $this->cfpp05auxiliar->execute("DELETE FROM cfpd05_auxiliar where ano=".$ano_ejercicio_pasar." and cod_partida=407 and cod_generica!=1");
	         	}else{
	         		$resultado_insert=0;
	         	}


	         	if($resultado_insert>1){
	         		 $this->set('Message_existe', 'Registros pasados exitosamente  ');
	         		 $this->index("");
	         		 $this->render("index");
	         	}else{
	         		 $this->set('errorMessage', 'Fall&oacute; el traspaso de los registros auxiliares');
	         		 $this->index("");
	         		 $this->render("index");
	         	}

	        }else{
	       	  		 $this->set('errorMessage', 'No hay auxiliares para transferir');
	         		 $this->index("");
	         		 $this->render("index");
	        	}

	        }else{
	    	  $this->set('errorMessage', 'Debe ingresar los años correctamente');
	    	  echo $this->data["cfpp05auxiliar"]["ano_ejercicio"];
	    	  echo $this->data["cfpp05auxiliar"]["ano_pasar_ejercicio"];
	        }

}//fin pasar ejercicio


function busqueda_auxiliares ($ano=null,$pista=null,$pagina=null) {
      $this->layout="ajax";

      if($this->verifica_SS(5)==1){
             $cond=$this->SQLCA_sin_dep();
         }else{
         	$cond=$this->SQLCA();
         }

      if(isset($this->data["cfpp05auxiliar"]["pista_busqueda"]) && !empty($this->data["cfpp05auxiliar"]["pista_busqueda"])  && isset($this->data["cfpp05auxiliar"]["ano"]) && !empty($this->data["cfpp05auxiliar"]["ano"])){
      	 $pista=$this->data["cfpp05auxiliar"]["pista_busqueda"];
      	 $ano=$this->data["cfpp05auxiliar"]["ano"];
         $cantidad_resultado=$this->v_busqueda_auxiliares->findCount($cond." and ano=".$ano." and quitar_acentos(upper(denominacion_busqueda)) like quitar_acentos('%".$pista."%')");
         $resultado=$this->v_busqueda_auxiliares->findAll($cond." and ano=".$ano." and quitar_acentos(upper(denominacion_busqueda)) like quitar_acentos('%".$pista."%')",null,'cod_tipo_inst,cod_dep,cod_sector,cod_programa,cod_sub_prog,cod_proyecto,cod_activ_obra,cod_partida,cod_generica,cod_especifica,cod_sub_espec,cod_auxiliar ASC',300,1,null);
         $this->set('pista',$pista);
         $this->set('ano',$ano);
         if($cantidad_resultado!=0){
         	    $Tfilas=(int)ceil($cantidad_resultado/300);
				$this->set('pag_cant',$pagina.'/'.$Tfilas);
				$this->set('total_paginas',$Tfilas);
				$this->set('pagina_actual',$pagina);
				$this->set('ultimo',$Tfilas);
                $this->set("resultado",$resultado);
		        $this->set('siguiente',$pagina+1);
				$this->set('anterior',$pagina-1);
				$this->bt_nav($Tfilas,$pagina);
         }else{
              $this->set('errorMessage', 'No se encontrarón registros con la pista indicada');
         }
      }else{
          if(isset($ano) && isset($pista) && $ano!=null && $pista!=null){
          	$pagina=isset($pagina) && $pagina!=null ? $pagina : 1 ;
          	$Tfilas=$this->v_busqueda_auxiliares->findCount($cond." and ano=".$ano." and quitar_acentos(upper(denominacion_busqueda)) like quitar_acentos('%".$pista."%')");
	        $this->set('pista',$pista);
            $this->set('ano',$ano);
	        if($Tfilas!=0){
	        	$Tfilas=(int)ceil($Tfilas/300);
				$this->set('pag_cant',$pagina.'/'.$Tfilas);
				$this->set('total_paginas',$Tfilas);
				$this->set('pagina_actual',$pagina);
				$this->set('ultimo',$Tfilas);
                $todas_auxiliares=$this->v_busqueda_auxiliares->findAll($cond." and ano=".$ano." and quitar_acentos(upper(denominacion_busqueda)) like quitar_acentos('%".$pista."%')",null,'cod_tipo_inst,cod_dep,cod_sector,cod_programa,cod_sub_prog,cod_proyecto,cod_activ_obra,cod_partida,cod_generica,cod_especifica,cod_sub_espec,cod_auxiliar ASC',300,$pagina,null);
	            $this->set("resultado",$todas_auxiliares);
		        $this->set('siguiente',$pagina+1);
				$this->set('anterior',$pagina-1);
				$this->bt_nav($Tfilas,$pagina);
	        }else{
	        	//$this->set("datosFILAS",'');
	        	$this->set('errorMessage', 'No se encontrarón registros con la pista indicada');
	        }

          }else{
          	$this->set('errorMessage', 'Indique la pista a buscar');
          }
      }
}



function vacio(){
	$this->layout="ajax";
}


function entrar(){
	$this->layout="ajax";
	$this->set('mensaje',"Por favor ingrese su login y clave para continuar con la operacion");
	$this->set('autor_valido',false);
}


/**
function cambio_sectores () {
	$this->layout="ajax";
	$a[0]=$this->cfpp05auxiliar->execute("select * from cfpd05_auxiliar where cod_dep=1 and  cod_sector=2 and cod_programa=4 and cod_sub_prog=1 and cod_proyecto=0 and cod_activ_obra=51");
	$a[1]=$this->cfpp05auxiliar->execute("select * from cfpd05_auxiliar where cod_dep=1 and  cod_sector=4 and cod_programa=1 and cod_sub_prog=1 and cod_proyecto=0 and cod_activ_obra=51");
	$a[2]=$this->cfpp05auxiliar->execute("select * from cfpd05_auxiliar where cod_dep=1 and  cod_sector=7 and cod_programa=2 and cod_sub_prog=1 and cod_proyecto=0 and cod_activ_obra=51");
	$a[3]=$this->cfpp05auxiliar->execute("select * from cfpd05_auxiliar where cod_dep=1 and  cod_sector=8 and cod_programa=3 and cod_sub_prog=1 and cod_proyecto=0 and cod_activ_obra=51");
	$a[4]=$this->cfpp05auxiliar->execute("select * from cfpd05_auxiliar where cod_dep=1 and  cod_sector=8 and cod_programa=3 and cod_sub_prog=1 and cod_proyecto=0 and cod_activ_obra=52");
	$a[5]=$this->cfpp05auxiliar->execute("select * from cfpd05_auxiliar where cod_dep=1 and  cod_sector=11 and cod_programa=1 and cod_sub_prog=1 and cod_proyecto=0 and cod_activ_obra=51");
	$a[6]=$this->cfpp05auxiliar->execute("select * from cfpd05_auxiliar where cod_dep=1 and  cod_sector=11 and cod_programa=1 and cod_sub_prog=1 and cod_proyecto=0 and cod_activ_obra=52");
	$a[7]=$this->cfpp05auxiliar->execute("select * from cfpd05_auxiliar where cod_dep=1 and  cod_sector=11 and cod_programa=1 and cod_sub_prog=1 and cod_proyecto=0 and cod_activ_obra=53");
	$a[8]=$this->cfpp05auxiliar->execute("select * from cfpd05_auxiliar where cod_dep=1 and  cod_sector=12 and cod_programa=5 and cod_sub_prog=1 and cod_proyecto=0 and cod_activ_obra=52");
	$a[9]=$this->cfpp05auxiliar->execute("select * from cfpd05_auxiliar where cod_dep=1 and  cod_sector=13 and cod_programa=5 and cod_sub_prog=1 and cod_proyecto=0 and cod_activ_obra=51");
	$a[10]=$this->cfpp05auxiliar->execute("select * from cfpd05_auxiliar where cod_dep=1 and  cod_sector=13 and cod_programa=5 and cod_sub_prog=1 and cod_proyecto=0 and cod_activ_obra=52");

	$i=0;
	foreach($a[0] as $au){
		$sql=$au[0]["cod_presi"].",";
		$sql .=$au[0]["cod_entidad"].",";
		$sql .=$au[0]["cod_tipo_inst"].",";
		$sql .=$au[0]["cod_inst"].",";
		$sql .="18,".$au[0]["ano"].",";
		$sql .=$au[0]["cod_sector"].",";
		$sql .=$au[0]["cod_programa"].",";
		$sql .=$au[0]["cod_sub_prog"].",";
		$sql .=$au[0]["cod_proyecto"].",";
		$sql .=$au[0]["cod_activ_obra"].",";
		$sql .=$au[0]["cod_partida"].",";
		$sql .=$au[0]["cod_generica"].",";
		$sql .=$au[0]["cod_especifica"].",";
		$sql .=$au[0]["cod_sub_espec"].",";
		$sql .=$au[0]["cod_auxiliar"].",";
		$sql .="'".$au[0]["denominacion"]."'";
		$sql2="INSERT INTO cfpd05_auxiliar VALUES (".$sql.")";
		$z=$this->cfpp05auxiliar->execute($sql2);
		print_r($z);
		echo $i."<br>";
		$i++;
	}
	$i=0;
	foreach($a[1] as $au){
		$sql=$au[0]["cod_presi"].",";
		$sql .=$au[0]["cod_entidad"].",";
		$sql .=$au[0]["cod_tipo_inst"].",";
		$sql .=$au[0]["cod_inst"].",";
		$sql .="18,".$au[0]["ano"].",";
		$sql .=$au[0]["cod_sector"].",";
		$sql .=$au[0]["cod_programa"].",";
		$sql .=$au[0]["cod_sub_prog"].",";
		$sql .=$au[0]["cod_proyecto"].",";
		$sql .=$au[0]["cod_activ_obra"].",";
		$sql .=$au[0]["cod_partida"].",";
		$sql .=$au[0]["cod_generica"].",";
		$sql .=$au[0]["cod_especifica"].",";
		$sql .=$au[0]["cod_sub_espec"].",";
		$sql .=$au[0]["cod_auxiliar"].",";
		$sql .="'".$au[0]["denominacion"]."'";
		$sql2="INSERT INTO cfpd05_auxiliar VALUES (".$sql.")";
		$z=$this->cfpp05auxiliar->execute($sql2);
		print_r($z);
		echo $i."<br>";
		$i++;
	}
	$i=0;
	foreach($a[2] as $au){
		$sql=$au[0]["cod_presi"].",";
		$sql .=$au[0]["cod_entidad"].",";
		$sql .=$au[0]["cod_tipo_inst"].",";
		$sql .=$au[0]["cod_inst"].",";
		$sql .="18,".$au[0]["ano"].",";
		$sql .=$au[0]["cod_sector"].",";
		$sql .=$au[0]["cod_programa"].",";
		$sql .=$au[0]["cod_sub_prog"].",";
		$sql .=$au[0]["cod_proyecto"].",";
		$sql .=$au[0]["cod_activ_obra"].",";
		$sql .=$au[0]["cod_partida"].",";
		$sql .=$au[0]["cod_generica"].",";
		$sql .=$au[0]["cod_especifica"].",";
		$sql .=$au[0]["cod_sub_espec"].",";
		$sql .=$au[0]["cod_auxiliar"].",";
		$sql .="'".$au[0]["denominacion"]."'";
		$sql2="INSERT INTO cfpd05_auxiliar VALUES (".$sql.")";
		$z=$this->cfpp05auxiliar->execute($sql2);
		print_r($z);
		echo $i."<br>";
		$i++;
	}
	$i=0;
	foreach($a[3] as $au){
		$sql=$au[0]["cod_presi"].",";
		$sql .=$au[0]["cod_entidad"].",";
		$sql .=$au[0]["cod_tipo_inst"].",";
		$sql .=$au[0]["cod_inst"].",";
		$sql .="18,".$au[0]["ano"].",";
		$sql .=$au[0]["cod_sector"].",";
		$sql .=$au[0]["cod_programa"].",";
		$sql .=$au[0]["cod_sub_prog"].",";
		$sql .=$au[0]["cod_proyecto"].",";
		$sql .=$au[0]["cod_activ_obra"].",";
		$sql .=$au[0]["cod_partida"].",";
		$sql .=$au[0]["cod_generica"].",";
		$sql .=$au[0]["cod_especifica"].",";
		$sql .=$au[0]["cod_sub_espec"].",";
		$sql .=$au[0]["cod_auxiliar"].",";
		$sql .="'".$au[0]["denominacion"]."'";
		$sql2="INSERT INTO cfpd05_auxiliar VALUES (".$sql.")";
		$z=$this->cfpp05auxiliar->execute($sql2);
		print_r($z);
		echo $i."<br>";
		$i++;
	}
	$i=0;
	foreach($a[4] as $au){
		$sql=$au[0]["cod_presi"].",";
		$sql .=$au[0]["cod_entidad"].",";
		$sql .=$au[0]["cod_tipo_inst"].",";
		$sql .=$au[0]["cod_inst"].",";
		$sql .="18,".$au[0]["ano"].",";
		$sql .=$au[0]["cod_sector"].",";
		$sql .=$au[0]["cod_programa"].",";
		$sql .=$au[0]["cod_sub_prog"].",";
		$sql .=$au[0]["cod_proyecto"].",";
		$sql .=$au[0]["cod_activ_obra"].",";
		$sql .=$au[0]["cod_partida"].",";
		$sql .=$au[0]["cod_generica"].",";
		$sql .=$au[0]["cod_especifica"].",";
		$sql .=$au[0]["cod_sub_espec"].",";
		$sql .=$au[0]["cod_auxiliar"].",";
		$sql .="'".$au[0]["denominacion"]."'";
		$sql2="INSERT INTO cfpd05_auxiliar VALUES (".$sql.")";
		$z=$this->cfpp05auxiliar->execute($sql2);
		print_r($z);
		echo $i."<br>";
		$i++;
	}
	$i=0;
	foreach($a[5] as $au){
		$sql=$au[0]["cod_presi"].",";
		$sql .=$au[0]["cod_entidad"].",";
		$sql .=$au[0]["cod_tipo_inst"].",";
		$sql .=$au[0]["cod_inst"].",";
		$sql .="18,".$au[0]["ano"].",";
		$sql .=$au[0]["cod_sector"].",";
		$sql .=$au[0]["cod_programa"].",";
		$sql .=$au[0]["cod_sub_prog"].",";
		$sql .=$au[0]["cod_proyecto"].",";
		$sql .=$au[0]["cod_activ_obra"].",";
		$sql .=$au[0]["cod_partida"].",";
		$sql .=$au[0]["cod_generica"].",";
		$sql .=$au[0]["cod_especifica"].",";
		$sql .=$au[0]["cod_sub_espec"].",";
		$sql .=$au[0]["cod_auxiliar"].",";
		$sql .="'".$au[0]["denominacion"]."'";
		$sql2="INSERT INTO cfpd05_auxiliar VALUES (".$sql.")";
		$z=$this->cfpp05auxiliar->execute($sql2);
		print_r($z);
		echo $i."<br>";
		$i++;
	}
	$i=0;
	foreach($a[6] as $au){
		$sql=$au[0]["cod_presi"].",";
		$sql .=$au[0]["cod_entidad"].",";
		$sql .=$au[0]["cod_tipo_inst"].",";
		$sql .=$au[0]["cod_inst"].",";
		$sql .="18,".$au[0]["ano"].",";
		$sql .=$au[0]["cod_sector"].",";
		$sql .=$au[0]["cod_programa"].",";
		$sql .=$au[0]["cod_sub_prog"].",";
		$sql .=$au[0]["cod_proyecto"].",";
		$sql .=$au[0]["cod_activ_obra"].",";
		$sql .=$au[0]["cod_partida"].",";
		$sql .=$au[0]["cod_generica"].",";
		$sql .=$au[0]["cod_especifica"].",";
		$sql .=$au[0]["cod_sub_espec"].",";
		$sql .=$au[0]["cod_auxiliar"].",";
		$sql .="'".$au[0]["denominacion"]."'";
		$sql2="INSERT INTO cfpd05_auxiliar VALUES (".$sql.")";
		$z=$this->cfpp05auxiliar->execute($sql2);
		print_r($z);
		echo $i."<br>";
		$i++;
	}
	$i=0;
	foreach($a[7] as $au){
		$sql=$au[0]["cod_presi"].",";
		$sql .=$au[0]["cod_entidad"].",";
		$sql .=$au[0]["cod_tipo_inst"].",";
		$sql .=$au[0]["cod_inst"].",";
		$sql .="18,".$au[0]["ano"].",";
		$sql .=$au[0]["cod_sector"].",";
		$sql .=$au[0]["cod_programa"].",";
		$sql .=$au[0]["cod_sub_prog"].",";
		$sql .=$au[0]["cod_proyecto"].",";
		$sql .=$au[0]["cod_activ_obra"].",";
		$sql .=$au[0]["cod_partida"].",";
		$sql .=$au[0]["cod_generica"].",";
		$sql .=$au[0]["cod_especifica"].",";
		$sql .=$au[0]["cod_sub_espec"].",";
		$sql .=$au[0]["cod_auxiliar"].",";
		$sql .="'".$au[0]["denominacion"]."'";
		$sql2="INSERT INTO cfpd05_auxiliar VALUES (".$sql.")";
		$z=$this->cfpp05auxiliar->execute($sql2);
		print_r($z);
		echo $i."<br>";
		$i++;
	}
	$i=0;
	foreach($a[8] as $au){
		$sql=$au[0]["cod_presi"].",";
		$sql .=$au[0]["cod_entidad"].",";
		$sql .=$au[0]["cod_tipo_inst"].",";
		$sql .=$au[0]["cod_inst"].",";
		$sql .="18,".$au[0]["ano"].",";
		$sql .=$au[0]["cod_sector"].",";
		$sql .=$au[0]["cod_programa"].",";
		$sql .=$au[0]["cod_sub_prog"].",";
		$sql .=$au[0]["cod_proyecto"].",";
		$sql .=$au[0]["cod_activ_obra"].",";
		$sql .=$au[0]["cod_partida"].",";
		$sql .=$au[0]["cod_generica"].",";
		$sql .=$au[0]["cod_especifica"].",";
		$sql .=$au[0]["cod_sub_espec"].",";
		$sql .=$au[0]["cod_auxiliar"].",";
		$sql .="'".$au[0]["denominacion"]."'";
		$sql2="INSERT INTO cfpd05_auxiliar VALUES (".$sql.")";
		$z=$this->cfpp05auxiliar->execute($sql2);
		print_r($z);
		echo $i."<br>";
		$i++;
	}
	$i=0;
	foreach($a[9] as $au){
		$sql=$au[0]["cod_presi"].",";
		$sql .=$au[0]["cod_entidad"].",";
		$sql .=$au[0]["cod_tipo_inst"].",";
		$sql .=$au[0]["cod_inst"].",";
		$sql .="18,".$au[0]["ano"].",";
		$sql .=$au[0]["cod_sector"].",";
		$sql .=$au[0]["cod_programa"].",";
		$sql .=$au[0]["cod_sub_prog"].",";
		$sql .=$au[0]["cod_proyecto"].",";
		$sql .=$au[0]["cod_activ_obra"].",";
		$sql .=$au[0]["cod_partida"].",";
		$sql .=$au[0]["cod_generica"].",";
		$sql .=$au[0]["cod_especifica"].",";
		$sql .=$au[0]["cod_sub_espec"].",";
		$sql .=$au[0]["cod_auxiliar"].",";
		$sql .="'".$au[0]["denominacion"]."'";
		$sql2="INSERT INTO cfpd05_auxiliar VALUES (".$sql.")";
		$z=$this->cfpp05auxiliar->execute($sql2);
		print_r($z);
		echo $i."<br>";
		$i++;
	}
	$i=0;
	foreach($a[10] as $au){
		$sql=$au[0]["cod_presi"].",";
		$sql .=$au[0]["cod_entidad"].",";
		$sql .=$au[0]["cod_tipo_inst"].",";
		$sql .=$au[0]["cod_inst"].",";
		$sql .="18,".$au[0]["ano"].",";
		$sql .=$au[0]["cod_sector"].",";
		$sql .=$au[0]["cod_programa"].",";
		$sql .=$au[0]["cod_sub_prog"].",";
		$sql .=$au[0]["cod_proyecto"].",";
		$sql .=$au[0]["cod_activ_obra"].",";
		$sql .=$au[0]["cod_partida"].",";
		$sql .=$au[0]["cod_generica"].",";
		$sql .=$au[0]["cod_especifica"].",";
		$sql .=$au[0]["cod_sub_espec"].",";
		$sql .=$au[0]["cod_auxiliar"].",";
		$sql .="'".$au[0]["denominacion"]."'";
		$sql2="INSERT INTO cfpd05_auxiliar VALUES (".$sql.")";
		$z=$this->cfpp05auxiliar->execute($sql2);
		print_r($z);
		echo $i."<br>";
		$i++;
	}


}*/

 }//fin clase Cfpp05auxiliarController

?>