<?php

 class Cfpp05Controller extends AppController{


 	var $uses = array('cfpd05','cfpd05_auxiliar','cfpp05auxiliar','cfpd02_sector', 'cfpd02_programa', 'cfpd02_sub_prog', 'cfpd02_proyecto', 'cfpd02_activ_obra', 'arrd05','cfpd01_ano_grupo', 'cfpd01_ano_partida', 'cfpd01_ano_generica', 'cfpd01_ano_especifica', 'cfpd01_ano_sub_espec', 'cfpd01_ano_auxiliar', 'cfpd01_formulacion' );
 	var $helpers = array('Html','Ajax','Javascript', 'Sisap','Fpdf');


function checkSession()
    {
        if (!$this->Session->check('Usuario'))
        {
            $this->redirect('/salir/');

            exit();
        }
    }


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
    function SQLCA_report($pre=null){
         $sql_re = "cod_presi=".$this->verifica_SS(1)."  and    ";
         $sql_re .= "cod_entidad=".$this->verifica_SS(2)."  and  ";
         $sql_re .= "cod_tipo_inst=".$this->verifica_SS(3)."  and ";
         if($pre!=null && $pre==1){
         $sql_re .= "cod_inst=".$this->verifica_SS(4)." and ";
         $sql_re .= "cod_dep=0";
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

		$this->set('year', $dato);

	}else{
		$this->set('year', '');
	}

// fin del codigo


	}
    function distribucion_gasto(){
          $this->layout="ajax";

          if(!empty($this->data['cfpp05']['ano'])){
          	      $ano=$this->data['cfpp05']['ano'];
          	      $this->set('ano',$ano);
          	      $this->Session->delete('ano_d_g');
                  $this->Session->write('ano_d_g', $ano);
                  $listaSector=$this->cfpd02_sector->generateList("where ".$this->SQLCA($ano), 'cod_sector ASC', null, '{n}.cfpd02_sector.cod_sector', '{n}.cfpd02_sector.cod_sector');
                  if($listaSector!=null){
                  	  $this->AddCero('sector',$listaSector);
                  	  //$this->set('Message_existe', 'Datos encontrados con exito.');
                  	  $Tfilas=$this->cfpd05->findCount($this->SQLCA($ano));
          	          $Tpag = (int)ceil($Tfilas/10);
          	          $this->set('TotalPaginas',$Tpag);
                  	  $dataCFPD05=$this->cfpd05->findAll($this->SQLCA($ano),null,'nro DESC',10,1,null);
                      $this->set('datacfpd05',$dataCFPD05);
                  }else{
                  	   $this->set('errorMessage', 'No se encontraron datos para el año indicado.');
                  	   $this->set('sector','');
                  	   $dataCFPD05=$this->cfpd05->findAll($this->SQLCA($ano),null,'nro DESC',10,1,null);
                       $this->set('datacfpd05',$dataCFPD05);
                  }

                  //$this->set('partida', $this->cfpd01_ano_partida->generateList("where cod_grupo=4 AND ejercicio=".$ano, 'cod_partida ASC', null, '{n}.cfpd01_ano_partida.cod_partida', '{n}.cfpd01_ano_partida.cod_partida'));

          }else{
              //echo "no hay año";
              //$this->set('sector',array(1,2,3,4));
          }
          $this->verifica_SS(5)>1000 ? $this->render('distribucion_gasto_dep') : $this->render('distribucion_gasto');

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
		$listaProg=$this->cfpd02_programa->generateList('where '.$this->SQLCA($ano).' and cod_sector =  '.$var.' ', ' cod_programa ASC', null, '{n}.cfpd02_programa.cod_programa', '{n}.cfpd02_programa.cod_programa');
	$this->AddCero('programa',$listaProg);
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
		$listaSP=$this->cfpd02_sub_prog->generateList('where '.$this->SQLCA($ano).' and cod_sector =  '.$var1.'  and cod_programa = '.$var2.'', ' cod_sub_prog ASC', null, '{n}.cfpd02_sub_prog.cod_sub_prog', '{n}.cfpd02_sub_prog.cod_sub_prog');
		$this->AddCero('sub_prog',$listaSP);
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
	    $listaProyecto=$this->cfpd02_proyecto->generateList('where '.$this->SQLCA($ano).' and cod_sector =  '.$var1.'  and cod_programa = '.$var2.' and cod_sub_prog = '.$var3.'', ' cod_proyecto ASC', null, '{n}.cfpd02_proyecto.cod_proyecto', '{n}.cfpd02_proyecto.cod_proyecto');
		$this->AddCero('proyecto',$listaProyecto);
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
		$listaActiv=$this->cfpd02_activ_obra->generateList('where '.$this->SQLCA($ano).' and cod_sector =  '.$var1.'  and cod_programa = '.$var2.' and cod_sub_prog = '.$var3.' and cod_proyecto = '.$var4.'', ' cod_activ_obra ASC', null, '{n}.cfpd02_activ_obra.cod_activ_obra', '{n}.cfpd02_activ_obra.cod_activ_obra');
		$this->AddCero('activ_obra',$listaActiv);
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
         	$parti=$this->cfpd01_ano_partida->generateList('where ejercicio='.$ano.' and cod_grupo =  '.$var.' ', ' cod_partida ASC', null, '{n}.cfpd01_ano_partida.cod_partida', '{n}.cfpd01_ano_partida.cod_partida');
        $this->AddCero('partida',$parti,CE);
         }



	}else{   $this->set('partida', '');}

}//fin partida
/**
 function selec_partida($var=null, $aux=null){
	$this->layout = "ajax";
	$this->set('codigo',array($var,$aux));
	$var=4;
	if($this->data['cfpp05']['codigo'] &&  $var!=null){ $this->set('selecion', $this->data['cfpp05']['codigo']); }
	if($var==null){ $var = $this->data['cfpp05']['codigo']; }
	if($aux!=null){  $this->set('selecion', $aux);}
	$this->set('opcion7', $var);
	if($var!=null && $var!='otros'){
		//$this->set('partida', $this->cfpd01_ano_partida->generateList('where cod_grupo =  '.$var.' ', ' cod_partida ASC', null, '{n}.cfpd01_ano_partida.cod_partida', '{n}.cfpd01_ano_partida.cod_partida'));
    $parti=$this->cfpd01_ano_partida->generateList('where cod_grupo =  '.$var.' ', ' cod_partida ASC', null, '{n}.cfpd01_ano_partida.cod_partida', '{n}.cfpd01_ano_partida.cod_partida');
        $this->AddCero('partida',$parti,'4');
	}else{   $this->set('partida', '');}

}//fin partida
 * */

function selec_generica($var1=null, $var2=null , $aux=null){
    $this->layout = "ajax";
			if($this->data['cfpp05']['codigo']  &&  $var2!=null){ $this->set('selecion', $this->data['cfpp05']['codigo']); }
            if($var2==null){ $var2 = $this->data['cfpp05']['codigo'];}
			if($aux!=null){  $this->set('selecion', $aux);}

	$this->set('opcion7', $var1);
	$this->set('opcion8', $var2);

	if($var2!=null && $var2!='otros'){
          $ano=$this->Session->read('ano_d_g');

	$listaGenerica=$this->cfpd01_ano_generica->generateList('where ejercicio='.$ano.' and cod_grupo =  '.$var1.'  and cod_partida = '.$var2.'', ' cod_generica ASC', null, '{n}.cfpd01_ano_generica.cod_generica', '{n}.cfpd01_ano_generica.cod_generica');
    $this->AddCero('generica',$listaGenerica);
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

    $listaEspec=$this->cfpd01_ano_especifica->generateList('where ejercicio='.$ano.' and cod_grupo =  '.$var1.'  and cod_partida = '.$var2.' and cod_generica = '.$var3.'', ' cod_especifica ASC', null, '{n}.cfpd01_ano_especifica.cod_especifica', '{n}.cfpd01_ano_especifica.cod_especifica');
	$this->AddCero('especifica',$listaEspec);
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
	$listaSE=$this->cfpd01_ano_sub_espec->generateList('where ejercicio='.$ano.' and cod_grupo =  '.$var1.'  and cod_partida = '.$var2.' and cod_generica = '.$var3.' and cod_especifica = '.$var4.'', ' cod_sub_espec ASC', null, '{n}.cfpd01_ano_sub_espec.cod_sub_espec', '{n}.cfpd01_ano_sub_espec.cod_sub_espec');
	$this->AddCero('subespecifica',$listaSE);
	}else{   $this->set('subespecifica', ''); }
}//fin seb_especifica

function selec_auxiliar($var1=null, $var2=null, $var3=null, $var4=null, $var5=null, $aux=null){
	$this->layout = "ajax";
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
	$listaAux=$this->cfpd05_auxiliar->generateList($condicion, ' cod_auxiliar ASC', null, '{n}.cfpd05_auxiliar.cod_auxiliar', '{n}.cfpd05_auxiliar.cod_auxiliar');
    //$this->set('auxiliar',$listaAux);
    $this->AddCero('auxiliar',$listaAux);
	}else{   $this->set('auxiliar', '');}

}//fin auxiliar

function principal($var1=null, $var2=null, $var3=null, $var4=null, $var5=null, $var6=null){

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
	  $data = $this->$tabla->findAll($sql_re, null, null, null);

	  $this->set('datos_cod_cfpp02', $data);

}else if($var1!=null){

	  $sql_re = $sql_3;
	  $data = $this->$tabla->findAll($sql_re, null, null, null);

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

function guardar(){
	$this->layout = "ajax";
	if(!empty($this->data)){

           switch(strtoupper($this->data['cfpp05']['tipo_presupuesto'])){
           	    case 'ORDINARIO': $tipo_presupuesto =1;break;
           	    case 'COORDINADO': $tipo_presupuesto =2;break;
           	    case 'LAEE': $tipo_presupuesto =3;break;
           	    case 'FIDES': $tipo_presupuesto =4;break;
           	    case 'INGRESOS_EXTRAORDINARIOS': $tipo_presupuesto =5;break;
           	    default:$tipo_presupuesto =1;
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
		$Cpartida=      CE.$this->AddCeroR($this->data['cfpp05']['cod_partida']);
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
precompromiso_fondo_avance) VALUES";
        $SQLINSERT .="($Cpresi,$Centidad,$Ctipo_inst,$Cinst,$Cdep,$ano,$Csector,$Cprograma,$Csubprograma,$Cproyecto,$Cactividad,$Cpartida,$Cgenerica,$Cespecifica,$Csub_espec,$Cauxiliar,$Ctipo_gasto,$tipo_presupuesto,$asignacion_anual,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0)";
        $restri="cod_presi=".$Cpresi." and cod_entidad=".$Centidad." and cod_tipo_inst=".$Ctipo_inst." and cod_inst=".$Cinst." and cod_dep=".$Cdep." and ano=".$ano." and cod_sector=".$Csector." and cod_programa=".$Cprograma." and cod_sub_prog=".$Csubprograma." and cod_proyecto=".$Cproyecto." and cod_activ_obra=".$Cactividad." and cod_partida=".$Cpartida." and cod_generica=".$Cgenerica." and cod_especifica=".$Cespecifica." and cod_sub_espec=".$Csub_espec." and cod_auxiliar=".$Cauxiliar."";
       //echo $this->cfpd05->findCount($restri);
        if($this->cfpd05->findCount($restri)==0){
              $this->cfpd05->execute($SQLINSERT);
              $dataCFPD05=$this->cfpd05->findAll($this->SQLCA($ano),null,'nro DESC',10,1,null);
              $this->set('datacfpd05',$dataCFPD05);
		      $this->set('Message_existe', 'agregado con exito.');
        }else{
        	 $dataCFPD05=$this->cfpd05->findAll($this->SQLCA($ano),null,'nro DESC',null,null,null);
              $this->set('datacfpd05',$dataCFPD05);
             $this->set('errorMessage', 'Error - No se realiazo la insercion');
        }

	}else{
		 //$dataCFPD05=$this->cfpd05->findAll($this->SQLCA($ano),null,'nro DESC',10,1,null);
		 $dataCFPD05=$this->cfpd05->findAll($this->SQLCA($this->Session->read('ano_d_g')),null,'nro DESC',null,null,null);
              $this->set('datacfpd05',$dataCFPD05);
		$this->set('errorMessage', 'Error - No se realiazo la insercion');
	}


}
 function modificar($nro){
       $this->layout = "ajax";
       if(isset($this->data)){
       	  if(isset($nro)){
       	    $this->cfpd05->execute("UPDATE cfpd05 SET asignacion_anual=".$this->Formato1($this->data['cfpp05']['monto'])." WHERE nro=".$nro);
       	        $this->set('errorMessage', 'Monto Actualizado con exito');
       	        echo $this->data['cfpp05']['monto'];
       	        $resultado=$this->cfpd05->execute("SELECT SUM(asignacion_anual) as total FROM cfpd05 WHERE ".$this->SQLCA());
                $this->set('TOTAL',$resultado[0][0]['total']);
       	   //$this->set('eliminar',"Hola esta elimado");
           }else{
               $this->set('errorMessage', 'Dato no Actualizado');
          }
       }else{
       	   //echo "2Hola ".$id;
       }

 }
 function modificar2($dv1,$dv2,$dv3,$dv4,$dv5,$dv6,$dv7,$dv8,$dv9,$dv10,$dv11){
       $this->layout = "ajax";
       if(isset($this->data)){
       	  if(isset($dv1)){
       	  	$condicion=$this->SQLCA($dv11)." and cod_sector=".$dv1." and cod_programa=".$dv2." and cod_sub_prog=".$dv3." and cod_proyecto=".$dv4." and cod_activ_obra=".$dv5." and cod_partida=".$dv6." and cod_generica=".$dv7." and cod_especifica=".$dv8." and cod_sub_espec=".$dv9." and cod_auxiliar=".$dv10;
       	    $this->cfpd05->execute("UPDATE cfpd05 SET asignacion_anual=".$this->Formato1($this->data['cfpp05']['monto'])." WHERE ".$condicion);
       	        $this->set('errorMessage', 'Monto Actualizado con exito');
       	        echo $this->data['cfpp05']['monto']. " bs";
       	   //$this->set('eliminar',"Hola esta elimado");
           }else{
               $this->set('errorMessage', 'Dato no Actualizado');
          }
       }else{
       	   //echo "2Hola ".$id;
       }

 }
 function campo_monto($monto,$idupdate,$nro){
       $this->layout = "ajax";
       $this->set('nro',$nro);
       $vec=$this->cfpd05->findByNro($nro);
       $monto=$vec['cfpd05']['asignacion_anual'];
       $this->set('ValorMonto',$monto);
       $this->set('id',$idupdate);
 }
  function campo_monto2($dv1,$dv2,$dv3,$dv4,$dv5,$dv6,$dv7,$dv8,$dv9,$dv10,$dv11,$idupdate=null){//

       $this->layout = "ajax";
       $condicion=$this->SQLCA($dv11)." and cod_sector=".$dv1." and cod_programa=".$dv2." and cod_sub_prog=".$dv3." and cod_proyecto=".$dv4." and cod_activ_obra=".$dv5." and cod_partida=".$dv6." and cod_generica=".$dv7." and cod_especifica=".$dv8." and cod_sub_espec=".$dv9." and cod_auxiliar=".$dv10;
       $codigos=array($dv1,$dv2,$dv3,$dv4,$dv5,$dv6,$dv7,$dv8,$dv9,$dv10,$dv11);
       $this->set('cod',$codigos);
       //$condicion="nro=".$nro;
       //echo $condicion;
       $vec=$this->cfpd05->findAll($condicion);
       $monto=$vec[0]['cfpd05']['asignacion_anual'];
       $this->set('ValorMonto',$monto);
       //$this->set('nro',$nro);
       //$this->set('id',$idupdate);
 }
function mostrar_monto($nro){
       $this->layout = "ajax";
       $vec=$this->cfpd05->findByNro($nro);
       $monto=$vec['cfpd05']['asignacion_anual'];
       $this->set('MuestraMonto',$monto);
       // $this->set('ID',$id);
       echo $this->Formato2($monto);
       //$this->set('id',$idupdate);
 }
function mostrar_monto2($dv1,$dv2,$dv3,$dv4,$dv5,$dv6,$dv7,$dv8,$dv9,$dv10,$dv11){
       $this->layout = "ajax";
       $condicion=$this->SQLCA($dv11)." and cod_sector=".$dv1." and cod_programa=".$dv2." and cod_sub_prog=".$dv3." and cod_proyecto=".$dv4." and cod_activ_obra=".$dv5." and cod_partida=".$dv6." and cod_generica=".$dv7." and cod_especifica=".$dv8." and cod_sub_espec=".$dv9." and cod_auxiliar=".$dv10;
       $vec=$this->cfpd05->findAll($condicion);
       $monto=$vec[0]['cfpd05']['asignacion_anual'];
       $this->set('MuestraMonto',$monto);
       // $this->set('ID',$id);
       echo $this->Formato2($monto). " ".MONEDA1;
       //$this->set('id',$idupdate);
 }
 function eliminar($nro,$ANO){
       $this->layout = "ajax";
       if(isset($nro)){
       	   $this->cfpd05->execute("DELETE FROM cfpd05  WHERE nro=".$nro);
       	   $this->set('errorMessage', 'Dato Eliminado con exito');
       	   //$this->set('eliminar',"Hola esta elimado");
       	   $resultado=$this->cfpd05->execute("SELECT SUM(asignacion_anual) as total FROM cfpd05 WHERE ".$this->SQLCA($ANO));
                $this->set('TOTAL',$resultado[0][0]['total']);
       }else{
            $this->set('errorMessage', 'Dato no Eliminado');
       }
 }
 function eliminar2($dv1,$dv2,$dv3,$dv4,$dv5,$dv6,$dv7,$dv8,$dv9,$dv10,$dv11){
       $this->layout = "ajax";
       if(isset($dv1)){
       	$condicion=$this->SQLCA($dv11)." and cod_sector=".$dv1." and cod_programa=".$dv2." and cod_sub_prog=".$dv3." and cod_proyecto=".$dv4." and cod_activ_obra=".$dv5." and cod_partida=".$dv6." and cod_generica=".$dv7." and cod_especifica=".$dv8." and cod_sub_espec=".$dv9." and cod_auxiliar=".$dv10;
       	   $this->cfpd05->execute("DELETE FROM cfpd05  WHERE ".$condicion);
       	   $this->set('errorMessage', 'Dato Eliminado con exito');

          $this->consultar2($dv11,1);
          $this->render("consultar2");
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
          	 $dataCFPD05=$this->cfpd05->findAll($this->SQLCA($Ano),null,'nro DESC',10,$pagina,null);
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



/**
function consultar($year=null, $nro=null){

$this->layout = "ajax";

  $data = "";
  //$year = "";

   if($this->data['cfpp05']['ano']){$year = $this->data['cfpp05']['ano'];}
   if($year!=""){
		   $sql_re=$this->SQLCA($year);
		   $sql_aux=$this->SQLCA($year);
		   $this->set('ejercicio', $year);
	}else{
        $sql_re=$this->SQLCA();
        $sql_aux=$this->SQLCA();
        $this->set('ejercicio', '');
		 }


 if($this->cfpd05->findCount($sql_re) != 0){
 	 if($nro!=null){
                  $aux = $this->cfpd05->findAll($sql_aux, null, 'nro  ASC', null);
	              $data = $this->cfpd05->findAll($sql_re." and nro=".$nro, null, 'nro  DESC', null);
				  $this->set('data', $data);
 	 }else{
				  $aux = $this->cfpd05->findAll($sql_aux, null, 'nro  ASC', null);
				  $data = $this->cfpd05->findAll($sql_re, null, 'nro  DESC', null);
				  $this->set('data', $data);
 }

				  $siguiente = "";
				  $anterior = "";
				  $i = 0;

			  foreach ($data as $datos){$i++;
                    $aux_ejercicio_fiscal[$i] = $datos['cfpd05']['nro'];
              }//fin for

              for($a=1; $a<=$i; $a++){
    			 if($nro == $aux_ejercicio_fiscal[$a]){
                     if(($a-1)>=1){
                     	 $anterior = $aux_ejercicio_fiscal[$a-1];
                      }
					 if(($a+1)<=$i){
					 	 $siguiente = $aux_ejercicio_fiscal[$a+1];
					 }
                 }else if($nro==null && $siguiente==""){
                       if(isset($aux_ejercicio_fiscal[$a + 1])){
                       	    $siguiente = $aux_ejercicio_fiscal[$a + 1];
                       }
                  }
				}//fin for
			  if($siguiente!=""){
			  	 $this->set('siguiente', $siguiente);
			  }
	    	  if($anterior!=""){
	    	  	$this->set('anterior', $anterior);
	    	  }

	}else{
		$this->set('Message', 'No Existen Datos');
		$this->set('data', '');
	}//fin
}//fin consultar
*/

function consultar2($con_ano=null,$pagina=null){
	 $this->layout = "ajax";
	 if($pagina!=null && $con_ano!=null){
          	 $pagina=$pagina;
          	 $Ano=$con_ano;
          	 $ejercicio=$con_ano;
          	 $this->set('ano',$Ano);
          	 $this->set('ejercicio', $Ano);
          	 $Tfilas=$this->cfpd05->findCount($this->SQLCA($Ano));
          	 //echo $Tfilas;
          	 if($Tfilas!=0){
          	 $this->set('pag_cant',$pagina.'/'.$Tfilas);
          	 $dataCFPD05=$this->cfpd05->findAll($this->SQLCA($Ano),null,'nro DESC',1,$pagina,null);
          	 foreach ($dataCFPD05 as $YYY);
                 //$YYY['cfpd05']['cod_sector'];

          	   $this->set('sector',$this->cfpd02_sector->findAll($this->SQLCA().' and cod_sector='.$YYY['cfpd05']['cod_sector'].' and ano='.$ejercicio,null,null,null,null,null));
			   $this->set('programa',$this->cfpd02_programa->findAll($this->SQLCA().' and cod_programa='.$YYY['cfpd05']['cod_programa'].' and ano='.$ejercicio,null,null,null,null,null));
			   $this->set('subprograma',$this->cfpd02_sub_prog->findAll($this->SQLCA().' and cod_sub_prog='.$YYY['cfpd05']['cod_sub_prog'].' and ano='.$ejercicio,null,null,null,null,null));
			   $this->set('proyecto',$this->cfpd02_proyecto->findAll($this->SQLCA().' and cod_proyecto='.$YYY['cfpd05']['cod_proyecto'].' and ano='.$ejercicio,null,null,null,null,null));
			   $this->set('actividad',$this->cfpd02_activ_obra->findAll($this->SQLCA().' and cod_activ_obra='.$YYY['cfpd05']['cod_activ_obra'].' and ano='.$ejercicio,null,null,null,null,null));
			   //$this->set('grupo',$this->cfpd01_ano_grupo->findAll());
			   $this->set('partida',$this->cfpd01_ano_partida->findAll('cod_partida='.substr($YYY['cfpd05']['cod_partida'],-2).' and ejercicio='.$ejercicio.' and cod_grupo='.CE,null,null,null, null, null));
			   $this->set('generica',$this->cfpd01_ano_generica->findAll('cod_generica='.$YYY['cfpd05']['cod_generica'].' and ejercicio='.$ejercicio.' and cod_grupo='.CE,null,null,null, null, null));
			   $this->set('especifica',$this->cfpd01_ano_especifica->findAll('cod_especifica='.$YYY['cfpd05']['cod_especifica'].' and ejercicio='.$ejercicio.' and cod_grupo='.CE,null,null,null, null, null));
			   $this->set('subespecifica',$this->cfpd01_ano_sub_espec->findAll('cod_sub_espec='.$YYY['cfpd05']['cod_sub_espec'].' and ejercicio='.$ejercicio.' and cod_grupo='.CE,null,null,null, null, null));
               $cp= $YYY['cfpd05']['cod_partida'];
               //$cp = $cp < 10 ? str_replace("0","", $cp) : $cp;

               $this->set('auxiliar',$this->cfpd05_auxiliar->findAll($this->SQLCA($ejercicio).' and cod_sector='.$YYY['cfpd05']['cod_sector'].' and cod_programa='.$YYY['cfpd05']['cod_programa'].' and cod_sub_prog='.$YYY['cfpd05']['cod_sub_prog'].' and cod_proyecto='.$YYY['cfpd05']['cod_proyecto'].' and cod_activ_obra='.$YYY['cfpd05']['cod_activ_obra'].' and cod_partida='.$cp.' and cod_generica='.$YYY['cfpd05']['cod_generica'].' and cod_especifica='.$YYY['cfpd05']['cod_especifica'].' and cod_sub_espec='.$YYY['cfpd05']['cod_sub_espec'].' and cod_auxiliar='.$YYY['cfpd05']['cod_auxiliar'],null,null,null, null, null));

               // print_r($dataCFPD05);
          	 $this->set('DATACFPD05',$dataCFPD05);
          	 $this->set('siguiente',$pagina+1);
          	 $this->set('anterior',$pagina-1);

            $this->bt_nav($Tfilas,$pagina);
          	}else{
	 	       $this->set('DATACFPD05','');
	 	       $this->set('errorMessage', 'No se encontrar&oacute;n datos para el a&ntilde;o '.$Ano);
	 	       //echo "sssss";
	         }///fin else  del if-else que compara las Tfilas
          }else{
          	$pagina=1;
          	$Ano=$this->data['cfpp05']['ano'];
          	$ejercicio=$Ano;
          	$this->set('ejercicio', $Ano);
          	$this->set('ano',$Ano);
          	$Tfilas=$this->cfpd05->findCount($this->SQLCA($Ano));
             if($Tfilas!=0){
          	$this->set('pag_cant',$pagina.'/'.$Tfilas);
            $dataCFPD05=$this->cfpd05->findAll($this->SQLCA($Ano),null,'nro DESC',1,$pagina,null);
              foreach ($dataCFPD05 as $YYY);
                  //$YYY['cfpd05']['cod_sector'];

               $this->set('sector',$this->cfpd02_sector->findAll($this->SQLCA().' and cod_sector='.$YYY['cfpd05']['cod_sector'].' and ano='.$ejercicio,null,null,null,null,null));
			   $this->set('programa',$this->cfpd02_programa->findAll($this->SQLCA().' and cod_programa='.$YYY['cfpd05']['cod_programa'].' and ano='.$ejercicio,null,null,null,null,null));
			   $this->set('subprograma',$this->cfpd02_sub_prog->findAll($this->SQLCA().' and cod_sub_prog='.$YYY['cfpd05']['cod_sub_prog'].' and ano='.$ejercicio,null,null,null,null,null));
			   $this->set('proyecto',$this->cfpd02_proyecto->findAll($this->SQLCA().' and cod_proyecto='.$YYY['cfpd05']['cod_proyecto'].' and ano='.$ejercicio,null,null,null,null,null));
			   $this->set('actividad',$this->cfpd02_activ_obra->findAll($this->SQLCA().' and cod_activ_obra='.$YYY['cfpd05']['cod_activ_obra'].' and ano='.$ejercicio,null,null,null,null,null));
			   //$this->set('grupo',$this->cfpd01_ano_grupo->findAll());
			   $this->set('partida',$this->cfpd01_ano_partida->findAll('cod_partida='.substr($YYY['cfpd05']['cod_partida'],-2).' and ejercicio='.$ejercicio.' and cod_grupo='.CE,null,null,null, null, null));
			   $this->set('generica',$this->cfpd01_ano_generica->findAll('cod_generica='.$YYY['cfpd05']['cod_generica'].' and ejercicio='.$ejercicio.' and cod_grupo='.CE,null,null,null, null, null));
			   $this->set('especifica',$this->cfpd01_ano_especifica->findAll('cod_especifica='.$YYY['cfpd05']['cod_especifica'].' and ejercicio='.$ejercicio.' and cod_grupo='.CE,null,null,null, null, null));
			   $this->set('subespecifica',$this->cfpd01_ano_sub_espec->findAll('cod_sub_espec='.$YYY['cfpd05']['cod_sub_espec'].' and ejercicio='.$ejercicio.' and cod_grupo='.CE,null,null,null, null, null));
               $cp= $YYY['cfpd05']['cod_partida'];
               //$cp = $cp < 10 ? str_replace("0","", $cp) : $cp;
              // echo $this->SQLCA($ejercicio).' and cod_sector='.$YYY['cfpd05']['cod_sector'].' and cod_programa='.$YYY['cfpd05']['cod_programa'].' and cod_sub_prog='.$YYY['cfpd05']['cod_sub_prog'].' and cod_proyecto='.$YYY['cfpd05']['cod_proyecto'].' and cod_activ_obra='.$YYY['cfpd05']['cod_activ_obra'].' and cod_partida='.$cp.' and cod_generica='.$YYY['cfpd05']['cod_generica'].' and cod_especifica='.$YYY['cfpd05']['cod_especifica'].' and cod_sub_espec='.$YYY['cfpd05']['cod_sub_espec'].' and cod_auxiliar='.$YYY['cfpd05']['cod_auxiliar'];
               $this->set('auxiliar',$this->cfpd05_auxiliar->findAll($this->SQLCA($ejercicio).' and cod_sector='.$YYY['cfpd05']['cod_sector'].' and cod_programa='.$YYY['cfpd05']['cod_programa'].' and cod_sub_prog='.$YYY['cfpd05']['cod_sub_prog'].' and cod_proyecto='.$YYY['cfpd05']['cod_proyecto'].' and cod_activ_obra='.$YYY['cfpd05']['cod_activ_obra'].' and cod_partida='.$cp.' and cod_generica='.$YYY['cfpd05']['cod_generica'].' and cod_especifica='.$YYY['cfpd05']['cod_especifica'].' and cod_sub_espec='.$YYY['cfpd05']['cod_sub_espec'].' and cod_auxiliar='.$YYY['cfpd05']['cod_auxiliar'],null,null,null, null, null));

          	$this->set('DATACFPD05',$dataCFPD05);
          	$this->set('siguiente',$pagina+1);
          	$this->set('anterior',$pagina-1);
          	 $this->bt_nav($Tfilas,$pagina);
            }else{
	 	       $this->set('DATACFPD05','');
	 	       $this->set('errorMessage', 'No se encontrar&oacute;n datos para el a&ntilde;o '.$Ano);
	 	       //echo "sssss";
	         }///fin else  del if-else que compara las Tfilas
          }//fin else

}//fin consulta2

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

function reporte_form() {
	$this->layout="ajax";


}
function generar_reporte() {
	$this->layout="pdf";
	if(isset($this->data['cfpp05']) && !empty($this->data['cfpp05']['ano'])){
        $Ano=$this->data['cfpp05']['ano'];
	    $gr=$this->cfpd05->execute("SELECT cod_sector FROM cfpd05  WHERE ".$this->SQLCA($Ano)." GROUP BY cod_sector");
        //echo "<br>Cantidad de Sectores Registrados ".count($gr);
        $gr2=$this->cfpd05->execute("SELECT  cod_sector,cod_partida, SUM(cfpd05.asignacion_anual) as monto FROM cfpd05 WHERE ".$this->SQLCA($Ano)." GROUP BY cod_sector,cod_partida ORDER BY cod_partida");
        /*echo "<hr>";
        foreach($gr2 as $aa){
         	echo $this->AddCeroR($aa[0]['cod_sector'])." ___ ".$this->AddCeroR($aa[0]['cod_partida'],4)." ___ ".$aa[0]['monto']."<br>";
       }*/
       $this->set('datos',$gr2);
	}

}//generar_reporte

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
echo "datos borrados";
    	$this->cfpd05_2032_tmp->execute("DELETE FROM cfpd05_2032_tmp WHERE username='".$this->Session->read('nom_usuario')."'");
    }
    //$var=$this->cfpd05->findAll($this->SQLCA()." and ano=".$Ano,array('cod_presi','cod_entidad','cod_tipo_inst','cod_inst','cod_dep','ano','cod_sector','cod_programa','cod_sub_prog','cod_proyecto','cod_activ_obra','cod_partida','cod_generica','cod_especifica','cod_sub_espec','cod_auxiliar','asignacion_anual'));
    $distinto=$this->cfpd05->execute("SELECT DISTINCT cod_partida,cod_generica,cod_especifica,cod_sub_espec FROM cfpd05 WHERE ".$con." and ano=".$Ano."");

    foreach($distinto as $d){
    	$xc[1]=$d[0]['cod_partida'];
    	$xc[2]=$d[0]['cod_generica'];
    	$xc[3]=$d[0]['cod_especifica'];
    	$xc[4]=$d[0]['cod_sub_espec'];
    	$this->cfpd05_2032_tmp->execute("INSERT INTO cfpd05_2032_tmp (cod_presi,cod_entidad,cod_tipo_inst,cod_inst,cod_dep,username,ano,cod_partida,cod_generica,cod_especifica,cod_sub_espec,monto_estimado_anterior,monto_sector_uno,monto_sector_dos,monto_sector_tres,monto_sector_cuatro,monto_sector_cinco,monto_sector_seis,monto_sector_siete,monto_sector_ocho,monto_sector_nueve,monto_sector_diez,monto_sector_once,monto_sector_doce,monto_sector_trece,monto_sector_catorce,monto_sector_quince,monto_total_estimado) VALUES (".$conin.",'".$this->Session->read('nom_usuario')."',".$Ano.",".$xc[1].",0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0)");
        $this->cfpd05_2032_tmp->execute("INSERT INTO cfpd05_2032_tmp (cod_presi,cod_entidad,cod_tipo_inst,cod_inst,cod_dep,username,ano,cod_partida,cod_generica,cod_especifica,cod_sub_espec,monto_estimado_anterior,monto_sector_uno,monto_sector_dos,monto_sector_tres,monto_sector_cuatro,monto_sector_cinco,monto_sector_seis,monto_sector_siete,monto_sector_ocho,monto_sector_nueve,monto_sector_diez,monto_sector_once,monto_sector_doce,monto_sector_trece,monto_sector_catorce,monto_sector_quince,monto_total_estimado) VALUES (".$conin.",'".$this->Session->read('nom_usuario')."',".$Ano.",".$xc[1].",".$xc[2].",0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0)");
        $this->cfpd05_2032_tmp->execute("INSERT INTO cfpd05_2032_tmp (cod_presi,cod_entidad,cod_tipo_inst,cod_inst,cod_dep,username,ano,cod_partida,cod_generica,cod_especifica,cod_sub_espec,monto_estimado_anterior,monto_sector_uno,monto_sector_dos,monto_sector_tres,monto_sector_cuatro,monto_sector_cinco,monto_sector_seis,monto_sector_siete,monto_sector_ocho,monto_sector_nueve,monto_sector_diez,monto_sector_once,monto_sector_doce,monto_sector_trece,monto_sector_catorce,monto_sector_quince,monto_total_estimado) VALUES (".$conin.",'".$this->Session->read('nom_usuario')."',".$Ano.",".$xc[1].",".$xc[2].",".$xc[3].",0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0)");
        $this->cfpd05_2032_tmp->execute("INSERT INTO cfpd05_2032_tmp (cod_presi,cod_entidad,cod_tipo_inst,cod_inst,cod_dep,username,ano,cod_partida,cod_generica,cod_especifica,cod_sub_espec,monto_estimado_anterior,monto_sector_uno,monto_sector_dos,monto_sector_tres,monto_sector_cuatro,monto_sector_cinco,monto_sector_seis,monto_sector_siete,monto_sector_ocho,monto_sector_nueve,monto_sector_diez,monto_sector_once,monto_sector_doce,monto_sector_trece,monto_sector_catorce,monto_sector_quince,monto_total_estimado) VALUES (".$conin.",'".$this->Session->read('nom_usuario')."',".$Ano.",".$xc[1].",".$xc[2].",".$xc[3].",".$xc[4].",0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0)");
    }
    $distinto=$this->cfpd05->execute("SELECT DISTINCT cod_partida,cod_generica,cod_especifica,cod_sub_espec FROM cfpd05 WHERE ".$con." and ano=".$Ano."");
    foreach($distinto as $d){
    	$xc[1]=$d[0]['cod_partida'];
    	$this->cfpd05_2032_tmp->execute("INSERT INTO cfpd05_2032_tmp (cod_presi,cod_entidad,cod_tipo_inst,cod_inst,cod_dep,username,ano,cod_partida,cod_generica,cod_especifica,cod_sub_espec,monto_estimado_anterior,monto_sector_uno,monto_sector_dos,monto_sector_tres,monto_sector_cuatro,monto_sector_cinco,monto_sector_seis,monto_sector_siete,monto_sector_ocho,monto_sector_nueve,monto_sector_diez,monto_sector_once,monto_sector_doce,monto_sector_trece,monto_sector_catorce,monto_sector_quince,monto_total_estimado) VALUES (".$conin.",'".$this->Session->read('nom_usuario')."',".$Ano.",".$xc[1].",0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0)");
}
    $distinto=$this->cfpd05->execute("SELECT DISTINCT cod_partida,cod_generica,cod_especifica,cod_sub_espec FROM cfpd05 WHERE ".$con." and ano=".$Ano."");
    foreach($distinto as $d){
    	$xc[1]=$d[0]['cod_partida'];
    	$xc[2]=$d[0]['cod_generica'];
        $this->cfpd05_2032_tmp->execute("INSERT INTO cfpd05_2032_tmp (cod_presi,cod_entidad,cod_tipo_inst,cod_inst,cod_dep,username,ano,cod_partida,cod_generica,cod_especifica,cod_sub_espec,monto_estimado_anterior,monto_sector_uno,monto_sector_dos,monto_sector_tres,monto_sector_cuatro,monto_sector_cinco,monto_sector_seis,monto_sector_siete,monto_sector_ocho,monto_sector_nueve,monto_sector_diez,monto_sector_once,monto_sector_doce,monto_sector_trece,monto_sector_catorce,monto_sector_quince,monto_total_estimado) VALUES (".$conin.",'".$this->Session->read('nom_usuario')."',".$Ano.",".$xc[1].",".$xc[2].",0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0)");
        }
    $distinto=$this->cfpd05->execute("SELECT DISTINCT cod_partida,cod_generica,cod_especifica,cod_sub_espec FROM cfpd05 WHERE ".$con." and ano=".$Ano."");
    foreach($distinto as $d){
    	$xc[1]=$d[0]['cod_partida'];
    	$xc[2]=$d[0]['cod_generica'];
    	$xc[3]=$d[0]['cod_especifica'];
    	$xc[4]=$d[0]['cod_sub_espec'];
        $this->cfpd05_2032_tmp->execute("INSERT INTO cfpd05_2032_tmp (cod_presi,cod_entidad,cod_tipo_inst,cod_inst,cod_dep,username,ano,cod_partida,cod_generica,cod_especifica,cod_sub_espec,monto_estimado_anterior,monto_sector_uno,monto_sector_dos,monto_sector_tres,monto_sector_cuatro,monto_sector_cinco,monto_sector_seis,monto_sector_siete,monto_sector_ocho,monto_sector_nueve,monto_sector_diez,monto_sector_once,monto_sector_doce,monto_sector_trece,monto_sector_catorce,monto_sector_quince,monto_total_estimado) VALUES (".$conin.",'".$this->Session->read('nom_usuario')."',".$Ano.",".$xc[1].",".$xc[2].",".$xc[3].",0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0)");

    }
    $distinto=$this->cfpd05->execute("SELECT DISTINCT cod_partida,cod_generica,cod_especifica,cod_sub_espec FROM cfpd05 WHERE ".$con." and ano=".$Ano."");
    foreach($distinto as $d){
    	$xc[1]=$d[0]['cod_partida'];
    	$xc[2]=$d[0]['cod_generica'];
    	$xc[3]=$d[0]['cod_especifica'];
    	$xc[4]=$d[0]['cod_sub_espec'];
        $this->cfpd05_2032_tmp->execute("INSERT INTO cfpd05_2032_tmp (cod_presi,cod_entidad,cod_tipo_inst,cod_inst,cod_dep,username,ano,cod_partida,cod_generica,cod_especifica,cod_sub_espec,monto_estimado_anterior,monto_sector_uno,monto_sector_dos,monto_sector_tres,monto_sector_cuatro,monto_sector_cinco,monto_sector_seis,monto_sector_siete,monto_sector_ocho,monto_sector_nueve,monto_sector_diez,monto_sector_once,monto_sector_doce,monto_sector_trece,monto_sector_catorce,monto_sector_quince,monto_total_estimado) VALUES (".$conin.",'".$this->Session->read('nom_usuario')."',".$Ano.",".$xc[1].",".$xc[2].",".$xc[3].",".$xc[4].",0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0)");
    }
    $var=$this->cfpd05->execute("SELECT cod_sector,cod_partida,SUM(asignacion_anual) as asignacion_anual FROM cfpd05 WHERE ".$con." and ano=".$Ano." GROUP BY cod_sector,cod_partida ORDER BY cod_sector,cod_partida");
   $sector_1=null;
   $sector_2=null;
   $partida=0;
   $aa=0;
   $bb=0;
   //print_r($var);
      foreach($var as  $dato){
      	 $a[1]=$dato[0]['cod_sector'];
      	// $a[2]=$dato['cfpd05']['cod_programa'];
      	 //$a[3]=$dato['cfpd05']['cod_sub_prog'];
      	 //$a[4]=$dato['cfpd05']['cod_proyecto'];
      	 //$a[5]=$dato['cfpd05']['cod_activ_obra'];
      	 $a[6]=$dato[0]['cod_partida'];
      	 //$a[7]=$dato['cfpd05']['cod_generica'];
      	 //$a[8]=$dato['cfpd05']['cod_especifica'];
      	 //$a[9]=$dato['cfpd05']['cod_sub_espec'];
      	 //$a[10]=$dato['cfpd05']['cod_auxiliar'];
      	 $a[11]=$dato[0]['asignacion_anual'];
      	 // echo "<br>".$a[1]." - ".$a[6]." - ".$a[11];
         //$p1=$this->cfpd05_2032_tmp->execute("SELECT * FROM cfpd05_2032_tmp WHERE ".$con." and ano=".$Ano." and username='".$this->Session->read('nom_usuario')."' and ano=".$Ano." and cod_partida=".$a[6]."  and cod_generica=0 and cod_especifica=0 and cod_sub_espec=0  GROUP BY cod_sector,cod_partida");
         //$this->cfpd05_2032_tmp->findAll($con." and ano=".$Ano." and username='".$this->Session->read('nom_usuario')."' and cod_partida=".$a[6]."  and cod_generica=".$a[7]." and cod_especifica=".$a[8]." and cod_sub_espec=".$a[9]);
          $p1=$this->cfpd05_2032_tmp->findAll($con." and ano=".$Ano." and username='".$this->Session->read('nom_usuario')."' and cod_partida=".$a[6]."  and cod_generica=0 and cod_especifica=0 and cod_sub_espec=0");
         //if($p1!=null){
             if($a[1]==1){$this->cfpd05_2032_tmp->execute("UPDATE cfpd05_2032_tmp SET monto_sector_uno=".$a[11]." WHERE ".$con." and ano=".$Ano." and username='".$this->Session->read('nom_usuario')."' and cod_partida=".$a[6]."  and cod_generica=0 and cod_especifica=0 and cod_sub_espec=0");}
             if($a[1]==2){$this->cfpd05_2032_tmp->execute("UPDATE cfpd05_2032_tmp SET monto_sector_dos=".$a[11]." WHERE ".$con." and ano=".$Ano." and username='".$this->Session->read('nom_usuario')."' and cod_partida=".$a[6]."  and cod_generica=0 and cod_especifica=0 and cod_sub_espec=0");}
             if($a[1]==3){$this->cfpd05_2032_tmp->execute("UPDATE cfpd05_2032_tmp SET monto_sector_tres=".$a[11]." WHERE ".$con." and ano=".$Ano." and username='".$this->Session->read('nom_usuario')."' and cod_partida=".$a[6]."  and cod_generica=0 and cod_especifica=0 and cod_sub_espec=0");}
          /*}else{
          	  $p1=$this->cfpd05_2032_tmp->findAll($con." and ano=".$Ano." and username='".$this->Session->read('nom_usuario')."' and cod_partida=".$a[6]."  and cod_generica=0 and cod_especifica=0 and cod_sub_espec=0");
          	  if($p1==null)
          	      echo "nulo";//$this->cfpd05_2032_tmp->execute("INSERT INTO cfpd05_2032_tmp (cod_presi,cod_entidad,cod_tipo_inst,cod_inst,cod_dep,username,ano,cod_partida,cod_generica,cod_especifica,cod_sub_espec,monto_estimado_anterior,monto_sector_uno,monto_sector_dos,monto_sector_tres,monto_sector_cuatro,monto_sector_cinco,monto_sector_seis,monto_sector_siete,monto_sector_ocho,monto_sector_nueve,monto_sector_diez,monto_sector_once,monto_sector_doce,monto_sector_trece,monto_sector_catorce,monto_sector_quince,monto_total_estimado) VALUES (".$conin.",'".$this->Session->read('nom_usuario')."',".$Ano.",".$a[6].",0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0)");

              if($a[1]==1){$this->cfpd05_2032_tmp->execute("UPDATE cfpd05_2032_tmp SET monto_sector_uno=".$a[11]." WHERE ".$con." and ano=".$Ano." and username='".$this->Session->read('nom_usuario')."' and cod_partida=".$a[6]."  and cod_generica=0 and cod_especifica=0 and cod_sub_espec=0");}
              if($a[1]==2){$this->cfpd05_2032_tmp->execute("UPDATE cfpd05_2032_tmp SET monto_sector_dos=".$a[11]." WHERE ".$con." and ano=".$Ano." and username='".$this->Session->read('nom_usuario')."' and cod_partida=".$a[6]."  and cod_generica=0 and cod_especifica=0 and cod_sub_espec=0");}
              if($a[1]==3){$this->cfpd05_2032_tmp->execute("UPDATE cfpd05_2032_tmp SET monto_sector_tres=".$a[11]." WHERE ".$con." and ano=".$Ano." and username='".$this->Session->read('nom_usuario')."' and cod_partida=".$a[6]."  and cod_generica=0 and cod_especifica=0 and cod_sub_espec=0");}

          }*/

      }//fin for
        $this->cfpd05_2032_tmp->execute("DELETE FROM cfpd05_2032_tmp WHERE ".$con." and ano=".$Ano." and username='".$this->Session->read('nom_usuario')."' and monto_sector_uno=0.00,monto_sector_dos=0.00,monto_sector_tres=0.00,monto_sector_cuatro=0.00,monto_sector_cinco=0.00,monto_sector_seis=0.00,monto_sector_siete=0.00,monto_sector_ocho=0.00,monto_sector_nueve=0.00,monto_sector_diez=0.00,monto_sector_once=0.00,monto_sector_doce=0.00,monto_sector_trece=0.00,monto_sector_catorce=0.00,monto_sector_quince=0.00");

/**
 *
 * else{
             	$this->cfpd05_2032_tmp->execute("INSERT INTO cfpd05_2032_tmp (cod_presi,cod_entidad,cod_tipo_inst,cod_inst,cod_dep,username,ano,cod_partida,cod_generica,cod_especifica,cod_sub_espec,monto_estimado_anterior,monto_sector_uno,monto_sector_dos,monto_sector_tres,monto_sector_cuatro,monto_sector_cinco,monto_sector_seis,monto_sector_siete,monto_sector_ocho,monto_sector_nueve,monto_sector_diez,monto_sector_once,monto_sector_doce,monto_sector_trece,monto_sector_catorce,monto_sector_quince,monto_total_estimado) VALUES (".$conin.",".$Ano.",0,".$a[11].",0,0,0,0,0,0,0,0,0,0,0,0,0,0,".$a[11].")");
             }
 */
     $y=array('ano','cod_partida','cod_generica','cod_especifica','cod_sub_espec','monto_estimado_anterior','monto_sector_uno','monto_sector_dos','monto_sector_tres','monto_sector_cuatro','monto_sector_cinco','monto_sector_seis','monto_sector_siete','monto_sector_ocho','monto_sector_nueve','monto_sector_diez','monto_sector_once','monto_sector_doce','monto_sector_trece','monto_sector_catorce','monto_sector_quince','monto_total_estimado');
     $d=$this->cfpd05_2032_tmp->findAll($con." and ano=".$Ano,$y,'cod_partida ASC');
     //$d=$this->cfpd05_2032_tmp->execute($con." and ano=".$Ano);
     $z="cfpd05_2032_tmp";
     $i=0;
     foreach($d as $da){
     	echo "<br>".$da['cfpd05_2032_tmp']['cod_partida']." - - ".$da['cfpd05_2032_tmp']['monto_sector_uno']." - - ".$da['cfpd05_2032_tmp']['monto_sector_dos']." - - ".$da['cfpd05_2032_tmp']['monto_sector_tres'];
     	/*$sss ="";
     	for($i=0;$i<count($y);$i++){
     		$sss .=$da[$z][$y[$i]];
     	}
     	$sss."<br>";*/
     }
     //print_r($d);
    }//fin isset
}//fin function

function tabla_temporal2 () {
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
    }
    $var=$this->cfpd05->findAll($this->SQLCA()." and ano=".$Ano,array('cod_presi','cod_entidad','cod_tipo_inst','cod_inst','cod_dep','ano','cod_sector','cod_programa','cod_sub_prog','cod_proyecto','cod_activ_obra','cod_partida','cod_generica','cod_especifica','cod_sub_espec','cod_auxiliar','asignacion_anual'));
    //$var=$this->cfpd05->execute("SELECT ano,cod_sector,cod_programa,cod_sub_prog,cod_proyecto,cod_activ_obra,cod_partida,cod_generica,cod_especifica,cod_sub_espec,cod_auxiliar,SUM(asignacion_anual) as asignacion_anual FROM cfpd05 WHERE ".$this->SQLCA()." and ano=".$Ano." GROUP BY cod_sector,cod_partida");

      foreach($var as  $dato){
      	 $a[1]=$dato['cfpd05']['cod_sector'];
      	 $a[2]=$dato['cfpd05']['cod_programa'];
      	 $a[3]=$dato['cfpd05']['cod_sub_prog'];
      	 $a[4]=$dato['cfpd05']['cod_proyecto'];
      	 $a[5]=$dato['cfpd05']['cod_activ_obra'];
      	 $a[6]=$dato['cfpd05']['cod_partida'];
      	 $a[7]=$dato['cfpd05']['cod_generica'];
      	 $a[8]=$dato['cfpd05']['cod_especifica'];
      	 $a[9]=$dato['cfpd05']['cod_sub_espec'];
      	 $a[10]=$dato['cfpd05']['cod_auxiliar'];
      	 $a[11]=$dato['cfpd05']['asignacion_anual'];
         //$p1=$this->cfpd05_2032_tmp->execute("SELECT * FROM cfpd05_2032_tmp WHERE ".$con." and ano=".$Ano." and username='".$this->Session->read('nom_usuario')."' and ano=".$Ano." and cod_partida=".$a[6]."  and cod_generica=0 and cod_especifica=0 and cod_sub_espec=0  GROUP BY cod_sector,cod_partida");
         //$this->cfpd05_2032_tmp->findAll($con." and ano=".$Ano." and username='".$this->Session->read('nom_usuario')."' and cod_partida=".$a[6]."  and cod_generica=".$a[7]." and cod_especifica=".$a[8]." and cod_sub_espec=".$a[9]);
         $p1=$this->cfpd05_2032_tmp->findAll($con." and ano=".$Ano." and username='".$this->Session->read('nom_usuario')."' and cod_partida=".$a[6]."  and cod_generica=0 and cod_especifica=0 and cod_sub_espec=0");
         // $p2=$this->cfpd05_2032_tmp->findAll($con." and ano=".$Ano." and username='".$this->Session->read('nom_usuario')."' and cod_partida=".$a[6]."  and cod_generica=".$a[7]." and cod_especifica=0 and cod_sub_espec=0");
         // $p3=$this->cfpd05_2032_tmp->findAll($con." and ano=".$Ano." and username='".$this->Session->read('nom_usuario')."' and cod_partida=".$a[6]."  and cod_generica=".$a[7]." and cod_especifica=".$a[8]." and cod_sub_espec=0");
          //$p4=$this->cfpd05_2032_tmp->findAll($con." and ano=".$Ano." and username='".$this->Session->read('nom_usuario')."' and cod_partida=".$a[6]."  and cod_generica=".$a[7]." and cod_especifica=".$a[8]." and cod_sub_espec=".$a[9]);
          //print_r($p1);
          if($p1!=null){
             if($a[1]==1){
             	$new_monto=$p1[0]['cfpd05_2032_tmp']['monto_sector_uno']+$a[11];
          	    $this->cfpd05_2032_tmp->execute("UPDATE cfpd05_2032_tmp SET monto_sector_uno=".$new_monto." WHERE ".$con." and ano=".$Ano." and username='".$this->Session->read('nom_usuario')."' and cod_partida=".$a[6]."  and cod_generica=0 and cod_especifica=0 and cod_sub_espec=0");
             }else{
             	 $this->cfpd05_2032_tmp->execute("INSERT INTO cfpd05_2032_tmp (cod_presi,cod_entidad,cod_tipo_inst,cod_inst,cod_dep,username,ano,cod_partida,cod_generica,cod_especifica,cod_sub_espec,monto_estimado_anterior,monto_sector_uno,monto_sector_dos,monto_sector_tres,monto_sector_cuatro,monto_sector_cinco,monto_sector_seis,monto_sector_siete,monto_sector_ocho,monto_sector_nueve,monto_sector_diez,monto_sector_once,monto_sector_doce,monto_sector_trece,monto_sector_catorce,monto_sector_quince,monto_total_estimado) VALUES (".$conin.",'".$this->Session->read('nom_usuario')."',".$Ano.",".$a[6].",0,0,0,0,".$a[11].",0,0,0,0,0,0,0,0,0,0,0,0,0,0,".$a[11].")");

             }

              if($a[1]==2){
             	$new_monto=$p1[0]['cfpd05_2032_tmp']['monto_sector_dos']+$a[11];
          	    $this->cfpd05_2032_tmp->execute("UPDATE cfpd05_2032_tmp SET monto_sector_dos=".$new_monto." WHERE ".$con." and ano=".$Ano." and username='".$this->Session->read('nom_usuario')."' and cod_partida=".$a[6]."  and cod_generica=0 and cod_especifica=0 and cod_sub_espec=0");
             }else{
             	 $this->cfpd05_2032_tmp->execute("INSERT INTO cfpd05_2032_tmp (cod_presi,cod_entidad,cod_tipo_inst,cod_inst,cod_dep,username,ano,cod_partida,cod_generica,cod_especifica,cod_sub_espec,monto_estimado_anterior,monto_sector_uno,monto_sector_dos,monto_sector_tres,monto_sector_cuatro,monto_sector_cinco,monto_sector_seis,monto_sector_siete,monto_sector_ocho,monto_sector_nueve,monto_sector_diez,monto_sector_once,monto_sector_doce,monto_sector_trece,monto_sector_catorce,monto_sector_quince,monto_total_estimado) VALUES (".$conin.",'".$this->Session->read('nom_usuario')."',".$Ano.",".$a[6].",0,0,0,0,0,".$a[11].",0,0,0,0,0,0,0,0,0,0,0,0,0,".$a[11].")");
             }

             if($a[1]==3){
             	$new_monto=$p1[0]['cfpd05_2032_tmp']['monto_sector_tres']+$a[11];
          	    $this->cfpd05_2032_tmp->execute("UPDATE cfpd05_2032_tmp SET monto_sector_tres=".$new_monto." WHERE ".$con." and ano=".$Ano." and username='".$this->Session->read('nom_usuario')."' and cod_partida=".$a[6]."  and cod_generica=0 and cod_especifica=0 and cod_sub_espec=0");
             }else{}

              if($a[1]==4){
             	$new_monto=$p1[0]['cfpd05_2032_tmp']['monto_sector_uno']+$a[11];
          	    $this->cfpd05_2032_tmp->execute("UPDATE cfpd05_2032_tmp SET monto_sector_cuatro=".$new_monto." WHERE ".$con." and ano=".$Ano." and username='".$this->Session->read('nom_usuario')."' and cod_partida=".$a[6]."  and cod_generica=0 and cod_especifica=0 and cod_sub_espec=0");
             }else{}

              if($a[1]==5){
             	$new_monto=$p1[0]['cfpd05_2032_tmp']['monto_sector_cuatro']+$a[11];
          	    $this->cfpd05_2032_tmp->execute("UPDATE cfpd05_2032_tmp SET monto_sector_cinco=".$new_monto." WHERE ".$con." and ano=".$Ano." and username='".$this->Session->read('nom_usuario')."' and cod_partida=".$a[6]."  and cod_generica=0 and cod_especifica=0 and cod_sub_espec=0");
             }else{}

              if($a[1]==6){
             	$new_monto=$p1[0]['cfpd05_2032_tmp']['monto_sector_seis']+$a[11];
          	    $this->cfpd05_2032_tmp->execute("UPDATE cfpd05_2032_tmp SET monto_sector_seis=".$new_monto." WHERE ".$con." and ano=".$Ano." and username='".$this->Session->read('nom_usuario')."' and cod_partida=".$a[6]."  and cod_generica=0 and cod_especifica=0 and cod_sub_espec=0");
             }else{}

              if($a[1]==7){
             	$new_monto=$p1[0]['cfpd05_2032_tmp']['monto_sector_siete']+$a[11];
          	    $this->cfpd05_2032_tmp->execute("UPDATE cfpd05_2032_tmp SET monto_sector_siete=".$new_monto." WHERE ".$con." and ano=".$Ano." and username='".$this->Session->read('nom_usuario')."' and cod_partida=".$a[6]."  and cod_generica=0 and cod_especifica=0 and cod_sub_espec=0");
             }else{}

              if($a[1]==8){
             	$new_monto=$p1[0]['cfpd05_2032_tmp']['monto_sector_ocho']+$a[11];
          	    $this->cfpd05_2032_tmp->execute("UPDATE cfpd05_2032_tmp SET monto_sector_ocho=".$new_monto." WHERE ".$con." and ano=".$Ano." and username='".$this->Session->read('nom_usuario')."' and cod_partida=".$a[6]."  and cod_generica=0 and cod_especifica=0 and cod_sub_espec=0");
             }else{}
              if($a[1]==9){
             	$new_monto=$p1[0]['cfpd05_2032_tmp']['monto_sector_nueve']+$a[11];
          	    $this->cfpd05_2032_tmp->execute("UPDATE cfpd05_2032_tmp SET monto_sector_nueve=".$new_monto." WHERE ".$con." and ano=".$Ano." and username='".$this->Session->read('nom_usuario')."' and cod_partida=".$a[6]."  and cod_generica=0 and cod_especifica=0 and cod_sub_espec=0");
             }else{}

             if($a[1]==10){
             	$new_monto=$p1[0]['cfpd05_2032_tmp']['monto_sector_diez']+$a[11];
          	    $this->cfpd05_2032_tmp->execute("UPDATE cfpd05_2032_tmp SET monto_sector_diez=".$new_monto." WHERE ".$con." and ano=".$Ano." and username='".$this->Session->read('nom_usuario')."' and cod_partida=".$a[6]."  and cod_generica=0 and cod_especifica=0 and cod_sub_espec=0");
             }else{}

             if($a[1]==11){
             	$new_monto=$p1[0]['cfpd05_2032_tmp']['monto_sector_once']+$a[11];
          	    $this->cfpd05_2032_tmp->execute("UPDATE cfpd05_2032_tmp SET monto_sector_once=".$new_monto." WHERE ".$con." and ano=".$Ano." and username='".$this->Session->read('nom_usuario')."' and cod_partida=".$a[6]."  and cod_generica=0 and cod_especifica=0 and cod_sub_espec=0");
             }else{}

              if($a[1]==12){
             	$new_monto=$p1[0]['cfpd05_2032_tmp']['monto_sector_doce']+$a[11];
          	    $this->cfpd05_2032_tmp->execute("UPDATE cfpd05_2032_tmp SET monto_sector_doce=".$new_monto." WHERE ".$con." and ano=".$Ano." and username='".$this->Session->read('nom_usuario')."' and cod_partida=".$a[6]."  and cod_generica=0 and cod_especifica=0 and cod_sub_espec=0");
             }else{}

              if($a[1]==13){
             	$new_monto=$p1[0]['cfpd05_2032_tmp']['monto_sector_trece']+$a[11];
          	    $this->cfpd05_2032_tmp->execute("UPDATE cfpd05_2032_tmp SET monto_sector_trece=".$new_monto." WHERE ".$con." and ano=".$Ano." and username='".$this->Session->read('nom_usuario')."' and cod_partida=".$a[6]."  and cod_generica=0 and cod_especifica=0 and cod_sub_espec=0");
             }else{}

              if($a[1]==14){
             	$new_monto=$p1[0]['cfpd05_2032_tmp']['monto_sector_catorce']+$a[11];
          	    $this->cfpd05_2032_tmp->execute("UPDATE cfpd05_2032_tmp SET monto_sector_catorce=".$new_monto." WHERE ".$con." and ano=".$Ano." and username='".$this->Session->read('nom_usuario')."' and cod_partida=".$a[6]."  and cod_generica=0 and cod_especifica=0 and cod_sub_espec=0");
             }else{}

              if($a[1]==15){
             	$new_monto=$p1[0]['cfpd05_2032_tmp']['monto_sector_quince']+$a[11];
          	    $this->cfpd05_2032_tmp->execute("UPDATE cfpd05_2032_tmp SET monto_sector_quince=".$new_monto." WHERE ".$con." and ano=".$Ano." and username='".$this->Session->read('nom_usuario')."' and cod_partida=".$a[6]."  and cod_generica=0 and cod_especifica=0 and cod_sub_espec=0");
             }else{}

          }else{////////////////////////////////
          	if($a[1]==1){
          		$this->cfpd05_2032_tmp->execute("INSERT INTO cfpd05_2032_tmp (cod_presi,cod_entidad,cod_tipo_inst,cod_inst,cod_dep,username,ano,cod_partida,cod_generica,cod_especifica,cod_sub_espec,monto_estimado_anterior,monto_sector_uno,monto_sector_dos,monto_sector_tres,monto_sector_cuatro,monto_sector_cinco,monto_sector_seis,monto_sector_siete,monto_sector_ocho,monto_sector_nueve,monto_sector_diez,monto_sector_once,monto_sector_doce,monto_sector_trece,monto_sector_catorce,monto_sector_quince,monto_total_estimado) VALUES (".$conin.",'".$this->Session->read('nom_usuario')."',".$Ano.",".$a[6].",0,0,0,0,".$a[11].",0,0,0,0,0,0,0,0,0,0,0,0,0,0,".$a[11].")");
             }

              if($a[1]==2){
              	$this->cfpd05_2032_tmp->execute("INSERT INTO cfpd05_2032_tmp (cod_presi,cod_entidad,cod_tipo_inst,cod_inst,cod_dep,username,ano,cod_partida,cod_generica,cod_especifica,cod_sub_espec,monto_estimado_anterior,monto_sector_uno,monto_sector_dos,monto_sector_tres,monto_sector_cuatro,monto_sector_cinco,monto_sector_seis,monto_sector_siete,monto_sector_ocho,monto_sector_nueve,monto_sector_diez,monto_sector_once,monto_sector_doce,monto_sector_trece,monto_sector_catorce,monto_sector_quince,monto_total_estimado) VALUES (".$conin.",'".$this->Session->read('nom_usuario')."',".$Ano.",".$a[6].",0,0,0,0,0,".$a[11].",0,0,0,0,0,0,0,0,0,0,0,0,0,".$a[11].")");
             }

             if($a[1]==3){
             	$new_monto=$p1[0]['cfpd05_2032_tmp']['monto_sector_tres']+$a[11];
          	    $this->cfpd05_2032_tmp->execute("UPDATE cfpd05_2032_tmp SET monto_sector_tres=".$new_monto." WHERE ".$con." and ano=".$Ano." and username='".$this->Session->read('nom_usuario')."' and cod_partida=".$a[6]."  and cod_generica=0 and cod_especifica=0 and cod_sub_espec=0");
             }else{}

              if($a[1]==4){
             	$new_monto=$p1[0]['cfpd05_2032_tmp']['monto_sector_uno']+$a[11];
          	    $this->cfpd05_2032_tmp->execute("UPDATE cfpd05_2032_tmp SET monto_sector_cuatro=".$new_monto." WHERE ".$con." and ano=".$Ano." and username='".$this->Session->read('nom_usuario')."' and cod_partida=".$a[6]."  and cod_generica=0 and cod_especifica=0 and cod_sub_espec=0");
             }else{}

              if($a[1]==5){
             	$new_monto=$p1[0]['cfpd05_2032_tmp']['monto_sector_cuatro']+$a[11];
          	    $this->cfpd05_2032_tmp->execute("UPDATE cfpd05_2032_tmp SET monto_sector_cinco=".$new_monto." WHERE ".$con." and ano=".$Ano." and username='".$this->Session->read('nom_usuario')."' and cod_partida=".$a[6]."  and cod_generica=0 and cod_especifica=0 and cod_sub_espec=0");
             }else{}

              if($a[1]==6){
             	$new_monto=$p1[0]['cfpd05_2032_tmp']['monto_sector_seis']+$a[11];
          	    $this->cfpd05_2032_tmp->execute("UPDATE cfpd05_2032_tmp SET monto_sector_seis=".$new_monto." WHERE ".$con." and ano=".$Ano." and username='".$this->Session->read('nom_usuario')."' and cod_partida=".$a[6]."  and cod_generica=0 and cod_especifica=0 and cod_sub_espec=0");
             }else{}

              if($a[1]==7){
             	$new_monto=$p1[0]['cfpd05_2032_tmp']['monto_sector_siete']+$a[11];
          	    $this->cfpd05_2032_tmp->execute("UPDATE cfpd05_2032_tmp SET monto_sector_siete=".$new_monto." WHERE ".$con." and ano=".$Ano." and username='".$this->Session->read('nom_usuario')."' and cod_partida=".$a[6]."  and cod_generica=0 and cod_especifica=0 and cod_sub_espec=0");
             }else{}

              if($a[1]==8){
             	$new_monto=$p1[0]['cfpd05_2032_tmp']['monto_sector_ocho']+$a[11];
          	    $this->cfpd05_2032_tmp->execute("UPDATE cfpd05_2032_tmp SET monto_sector_ocho=".$new_monto." WHERE ".$con." and ano=".$Ano." and username='".$this->Session->read('nom_usuario')."' and cod_partida=".$a[6]."  and cod_generica=0 and cod_especifica=0 and cod_sub_espec=0");
             }else{}
              if($a[1]==9){
             	$new_monto=$p1[0]['cfpd05_2032_tmp']['monto_sector_nueve']+$a[11];
          	    $this->cfpd05_2032_tmp->execute("UPDATE cfpd05_2032_tmp SET monto_sector_nueve=".$new_monto." WHERE ".$con." and ano=".$Ano." and username='".$this->Session->read('nom_usuario')."' and cod_partida=".$a[6]."  and cod_generica=0 and cod_especifica=0 and cod_sub_espec=0");
             }else{}

             if($a[1]==10){
             	$new_monto=$p1[0]['cfpd05_2032_tmp']['monto_sector_diez']+$a[11];
          	    $this->cfpd05_2032_tmp->execute("UPDATE cfpd05_2032_tmp SET monto_sector_diez=".$new_monto." WHERE ".$con." and ano=".$Ano." and username='".$this->Session->read('nom_usuario')."' and cod_partida=".$a[6]."  and cod_generica=0 and cod_especifica=0 and cod_sub_espec=0");
             }else{}

             if($a[1]==11){
             	$new_monto=$p1[0]['cfpd05_2032_tmp']['monto_sector_once']+$a[11];
          	    $this->cfpd05_2032_tmp->execute("UPDATE cfpd05_2032_tmp SET monto_sector_once=".$new_monto." WHERE ".$con." and ano=".$Ano." and username='".$this->Session->read('nom_usuario')."' and cod_partida=".$a[6]."  and cod_generica=0 and cod_especifica=0 and cod_sub_espec=0");
             }else{}

              if($a[1]==12){
             	$new_monto=$p1[0]['cfpd05_2032_tmp']['monto_sector_doce']+$a[11];
          	    $this->cfpd05_2032_tmp->execute("UPDATE cfpd05_2032_tmp SET monto_sector_doce=".$new_monto." WHERE ".$con." and ano=".$Ano." and username='".$this->Session->read('nom_usuario')."' and cod_partida=".$a[6]."  and cod_generica=0 and cod_especifica=0 and cod_sub_espec=0");
             }else{}

              if($a[1]==13){
             	$new_monto=$p1[0]['cfpd05_2032_tmp']['monto_sector_trece']+$a[11];
          	    $this->cfpd05_2032_tmp->execute("UPDATE cfpd05_2032_tmp SET monto_sector_trece=".$new_monto." WHERE ".$con." and ano=".$Ano." and username='".$this->Session->read('nom_usuario')."' and cod_partida=".$a[6]."  and cod_generica=0 and cod_especifica=0 and cod_sub_espec=0");
             }else{}

              if($a[1]==14){
             	$new_monto=$p1[0]['cfpd05_2032_tmp']['monto_sector_catorce']+$a[11];
          	    $this->cfpd05_2032_tmp->execute("UPDATE cfpd05_2032_tmp SET monto_sector_catorce=".$new_monto." WHERE ".$con." and ano=".$Ano." and username='".$this->Session->read('nom_usuario')."' and cod_partida=".$a[6]."  and cod_generica=0 and cod_especifica=0 and cod_sub_espec=0");
             }else{}

              if($a[1]==15){
             	$new_monto=$p1[0]['cfpd05_2032_tmp']['monto_sector_quince']+$a[11];
          	    $this->cfpd05_2032_tmp->execute("UPDATE cfpd05_2032_tmp SET monto_sector_quince=".$new_monto." WHERE ".$con." and ano=".$Ano." and username='".$this->Session->read('nom_usuario')."' and cod_partida=".$a[6]."  and cod_generica=0 and cod_especifica=0 and cod_sub_espec=0");
             }else{}

          	 //$this->cfpd05_2032_tmp->execute("INSERT INTO cfpd05_2032_tmp (cod_presi,cod_entidad,cod_tipo_inst,cod_inst,cod_dep,username,ano,cod_partida,cod_generica,cod_especifica,cod_sub_espec,monto_estimado_anterior,monto_sector_uno,monto_sector_dos,monto_sector_tres,monto_sector_cuatro,monto_sector_cinco,monto_sector_seis,monto_sector_siete,monto_sector_ocho,monto_sector_nueve,monto_sector_diez,monto_sector_once,monto_sector_doce,monto_sector_trece,monto_sector_catorce,monto_sector_quince,monto_total_estimado) VALUES (".$conin.",'".$this->Session->read('nom_usuario')."',".$Ano.",".$a[6].",0,0,0,0,".$a[11].",0,0,0,0,0,0,0,0,0,0,0,0,0,0,".$a[11].")");

          }//fin null p1



      }//fin for

/**
 *
 * else{
             	$this->cfpd05_2032_tmp->execute("INSERT INTO cfpd05_2032_tmp (cod_presi,cod_entidad,cod_tipo_inst,cod_inst,cod_dep,username,ano,cod_partida,cod_generica,cod_especifica,cod_sub_espec,monto_estimado_anterior,monto_sector_uno,monto_sector_dos,monto_sector_tres,monto_sector_cuatro,monto_sector_cinco,monto_sector_seis,monto_sector_siete,monto_sector_ocho,monto_sector_nueve,monto_sector_diez,monto_sector_once,monto_sector_doce,monto_sector_trece,monto_sector_catorce,monto_sector_quince,monto_total_estimado) VALUES (".$conin.",".$Ano.",0,".$a[11].",0,0,0,0,0,0,0,0,0,0,0,0,0,0,".$a[11].")");
             }
 */
     $y=array('ano','cod_partida','cod_generica','cod_especifica','cod_sub_espec','monto_estimado_anterior','monto_sector_uno','monto_sector_dos','monto_sector_tres','monto_sector_cuatro','monto_sector_cinco','monto_sector_seis','monto_sector_siete','monto_sector_ocho','monto_sector_nueve','monto_sector_diez','monto_sector_once','monto_sector_doce','monto_sector_trece','monto_sector_catorce','monto_sector_quince','monto_total_estimado');
     $d=$this->cfpd05_2032_tmp->findAll($con." and ano=".$Ano,$y,'cod_partida ASC');
     //$d=$this->cfpd05_2032_tmp->execute($con." and ano=".$Ano);
     $z="cfpd05_2032_tmp";
     $i=0;
     foreach($d as $da){
     	echo "<br>".$da['cfpd05_2032_tmp']['cod_partida']." - - ".$da['cfpd05_2032_tmp']['monto_sector_uno']." - - ".$da['cfpd05_2032_tmp']['monto_sector_dos'];
     	/*$sss ="";
     	for($i=0;$i<count($y);$i++){
     		$sss .=$da[$z][$y[$i]];
     	}
     	$sss."<br>";*/
     }
     //print_r($d);
    }//fin isset
}//fin function


function reporte_prueba(){
	 $this->layout = "ajax";
	 $Ano=$this->data['cfpp05']['ano'];
	 $gr=$this->cfpd05->execute("SELECT cod_sector FROM cfpd05  WHERE ".$this->SQLCA($Ano)." GROUP BY cod_sector");
     //print_r($gr);
     echo "<br>Cantidad de Sectores Registrados ".count($gr);


     $gr2=$this->cfpd05->execute("SELECT  cod_sector,cod_partida, SUM(cfpd05.asignacion_anual) as monto FROM cfpd05 WHERE ".$this->SQLCA($Ano)." GROUP BY cod_sector,cod_partida ORDER BY cod_partida");
     echo "<hr>";
     $i=0;
     foreach($gr2 as $aa){
     	echo $this->AddCeroR($aa[0]['cod_sector'])." ___ ".$this->AddCeroR($aa[0]['cod_partida'],4)." ___ ".$aa[0]['monto']."<br>";
        $tt=$aa[0]['cod_sector'];
        $mm=$aa[0]['monto'];
        $pp=$aa[0]['cod_partida'];
     	$sectores[$i]["cod_partida"]=$pp;
     	$sectores[$i]["denominacion"]="";

     	$sectores[$i]["s1"]=$tt==1 ? $mm : 0;
        $sectores[$i]["s2"]=$tt==2 ? $mm : 0;
        $sectores[$i]["s3"]=$tt==3 ? $mm : 0;
        $sectores[$i]["s4"]=$tt==4 ? $mm : 0;
        $sectores[$i]["s5"]=$tt==5 ? $mm : 0;
        $sectores[$i]["s6"]=$tt==6 ? $mm : 0;
        $sectores[$i]["s7"]=$tt==7 ? $mm : 0;
        $sectores[$i]["s8"]=$tt==8 ? $mm : 0;
        $sectores[$i]["s9"]=$tt==9 ? $mm : 0;
        $sectores[$i]["s10"]=$tt==10 ? $mm : 0;
        $sectores[$i]["s11"]=$tt==11 ? $mm : 0;
        $sectores[$i]["s12"]=$tt==12 ? $mm : 0;
        $sectores[$i]["s13"]=$tt==13 ? $mm : 0;
        $sectores[$i]["s14"]=$tt==14 ? $mm : 0;
        $sectores[$i]["s15"]=$tt==15 ? $mm : 0;



     	$i++;
     }
     //---
     $gr2=$this->cfpd05->execute("SELECT  cod_sector,cod_partida,cod_generica, SUM(cfpd05.asignacion_anual) as monto FROM cfpd05 WHERE ".$this->SQLCA($Ano)." GROUP BY cod_sector,cod_partida,cod_generica ORDER BY cod_sector,cod_partida,cod_generica ASC");
     echo "<hr>";
     foreach($gr2 as $aa){
     	echo $this->AddCeroR($aa[0]['cod_sector'])." ___ ".$this->AddCeroR($aa[0]['cod_partida'],4).".".$this->AddCeroR($aa[0]['cod_generica'])." ___ ".$aa[0]['monto']."<br>";
     }
      //---
     $gr2=$this->cfpd05->execute("SELECT  cod_sector,cod_partida,cod_generica,cod_especifica, SUM(cfpd05.asignacion_anual) as monto FROM cfpd05 WHERE ".$this->SQLCA($Ano)." GROUP BY cod_sector,cod_partida,cod_generica,cod_especifica ORDER BY cod_sector,cod_partida,cod_generica,cod_especifica ASC");
     echo "<hr>";
     foreach($gr2 as $aa){
     	echo $this->AddCeroR($aa[0]['cod_sector'])." ___ ".$this->AddCeroR($aa[0]['cod_partida'],4).".".$this->AddCeroR($aa[0]['cod_generica']).".".$this->AddCeroR($aa[0]['cod_especifica'])." ___ ".$aa[0]['monto']."<br>";
     }

       //---
     $gr2=$this->cfpd05->execute("SELECT  cod_sector,cod_partida,cod_generica,cod_especifica,cod_sub_espec, SUM(cfpd05.asignacion_anual) as monto FROM cfpd05 WHERE ".$this->SQLCA($Ano)." GROUP BY cod_sector,cod_partida,cod_generica,cod_especifica,cod_sub_espec ORDER BY cod_sector,cod_partida,cod_generica,cod_especifica,cod_sub_espec ASC");
     echo "<hr>";
     foreach($gr2 as $aa){
     	echo $this->AddCeroR($aa[0]['cod_sector'])." ___ ".$this->AddCeroR($aa[0]['cod_partida'],4).".".$this->AddCeroR($aa[0]['cod_generica']).".".$this->AddCeroR($aa[0]['cod_especifica']).".".$this->AddCeroR($aa[0]['cod_sub_espec'])." ___ ".$aa[0]['monto']."<br>";
     }
	 $con_ano=null;
	 $pagina=null;
	 if($pagina!=null && $con_ano!=null){
          	 $pagina=$pagina;
          	 $Ano=$con_ano;
          	 $ejercicio=$con_ano;
          	 $this->set('ano',$Ano);
          	 $this->set('ejercicio', $Ano);
          	 $Tfilas=$this->cfpd05->findCount($this->SQLCA($Ano));
          	 //echo $Tfilas;
          	 if($Tfilas!=0){
          	 $this->set('pag_cant',$pagina.'/'.$Tfilas);
          	 $dataCFPD05=$this->cfpd05->findAll($this->SQLCA($Ano),null,'nro DESC',1,$pagina,null);
          	 foreach ($dataCFPD05 as $YYY);


          	   $this->set('sector',$this->cfpd02_sector->findAll($this->SQLCA().' and cod_sector='.$YYY['cfpd05']['cod_sector'].' and ano='.$ejercicio,null,null,null,null,null));
			   $this->set('programa',$this->cfpd02_programa->findAll($this->SQLCA().' and cod_programa='.$YYY['cfpd05']['cod_programa'].' and ano='.$ejercicio,null,null,null,null,null));
			   $this->set('subprograma',$this->cfpd02_sub_prog->findAll($this->SQLCA().' and cod_sub_prog='.$YYY['cfpd05']['cod_sub_prog'].' and ano='.$ejercicio,null,null,null,null,null));
			   $this->set('proyecto',$this->cfpd02_proyecto->findAll($this->SQLCA().' and cod_proyecto='.$YYY['cfpd05']['cod_proyecto'].' and ano='.$ejercicio,null,null,null,null,null));
			   $this->set('actividad',$this->cfpd02_activ_obra->findAll($this->SQLCA().' and cod_activ_obra='.$YYY['cfpd05']['cod_activ_obra'].' and ano='.$ejercicio,null,null,null,null,null));
			   $this->set('partida',$this->cfpd01_ano_partida->findAll('cod_partida='.$YYY['cfpd05']['cod_partida'].' and ejercicio='.$ejercicio.' and cod_grupo=4',null,null,null, null, null));
			   $this->set('generica',$this->cfpd01_ano_generica->findAll('cod_generica='.$YYY['cfpd05']['cod_generica'].' and ejercicio='.$ejercicio.' and cod_grupo=4',null,null,null, null, null));
			   $this->set('especifica',$this->cfpd01_ano_especifica->findAll('cod_especifica='.$YYY['cfpd05']['cod_especifica'].' and ejercicio='.$ejercicio.' and cod_grupo=4',null,null,null, null, null));
			   $this->set('subespecifica',$this->cfpd01_ano_sub_espec->findAll('cod_sub_espec='.$YYY['cfpd05']['cod_sub_espec'].' and ejercicio='.$ejercicio.' and cod_grupo=4',null,null,null, null, null));
               $this->set('auxiliar',$this->cfpd05_auxiliar->findAll($this->SQLCA($ejercicio).' and cod_sector='.$YYY['cfpd05']['cod_sector'].' and cod_programa='.$YYY['cfpd05']['cod_programa'].' and cod_sub_prog='.$YYY['cfpd05']['cod_sub_prog'].' and cod_proyecto='.$YYY['cfpd05']['cod_proyecto'].' and cod_activ_obra='.$YYY['cfpd05']['cod_activ_obra'].' and cod_partida='.$YYY['cfpd05']['cod_partida'].' and cod_generica='.$YYY['cfpd05']['cod_generica'].' and cod_especifica='.$YYY['cfpd05']['cod_especifica'].' and cod_sub_espec='.$YYY['cfpd05']['cod_sub_espec'].' and cod_auxiliar='.$YYY['cfpd05']['cod_auxiliar'],null,null,null, null, null));

               // print_r($dataCFPD05);
          	 $this->set('DATACFPD05',$dataCFPD05);
          	 $this->set('siguiente',$pagina+1);
          	 $this->set('anterior',$pagina-1);

            $this->bt_nav($Tfilas,$pagina);
          	}else{
	 	       $this->set('DATACFPD05','');
	 	       $this->set('errorMessage', 'No se encontrar&oacute;n datos para el a&ntilde;o '.$Ano);
	         }///fin else  del if-else que compara las Tfilas
          }else{
          	$pagina=1;
          	$Ano=$this->data['cfpp05']['ano'];
          	$ejercicio=$Ano;
          	$this->set('ejercicio', $Ano);
          	$this->set('ano',$Ano);
          	$Tfilas=$this->cfpd05->findCount($this->SQLCA($Ano));
             if($Tfilas!=0){
          	$this->set('pag_cant',$pagina.'/'.$Tfilas);
            $dataCFPD05=$this->cfpd05->findAll($this->SQLCA($Ano),null,'nro DESC',1,$pagina,null);
              foreach ($dataCFPD05 as $YYY);
                  //$YYY['cfpd05']['cod_sector'];

               $this->set('sector',$this->cfpd02_sector->findAll($this->SQLCA().' and cod_sector='.$YYY['cfpd05']['cod_sector'].' and ano='.$ejercicio,null,null,null,null,null));
			   $this->set('programa',$this->cfpd02_programa->findAll($this->SQLCA().' and cod_programa='.$YYY['cfpd05']['cod_programa'].' and ano='.$ejercicio,null,null,null,null,null));
			   $this->set('subprograma',$this->cfpd02_sub_prog->findAll($this->SQLCA().' and cod_sub_prog='.$YYY['cfpd05']['cod_sub_prog'].' and ano='.$ejercicio,null,null,null,null,null));
			   $this->set('proyecto',$this->cfpd02_proyecto->findAll($this->SQLCA().' and cod_proyecto='.$YYY['cfpd05']['cod_proyecto'].' and ano='.$ejercicio,null,null,null,null,null));
			   $this->set('actividad',$this->cfpd02_activ_obra->findAll($this->SQLCA().' and cod_activ_obra='.$YYY['cfpd05']['cod_activ_obra'].' and ano='.$ejercicio,null,null,null,null,null));
			   //$this->set('grupo',$this->cfpd01_ano_grupo->findAll());
			   $this->set('partida',$this->cfpd01_ano_partida->findAll('cod_partida='.$YYY['cfpd05']['cod_partida'].' and ejercicio='.$ejercicio.' and cod_grupo=4',null,null,null, null, null));
			   $this->set('generica',$this->cfpd01_ano_generica->findAll('cod_generica='.$YYY['cfpd05']['cod_generica'].' and ejercicio='.$ejercicio.' and cod_grupo=4',null,null,null, null, null));
			   $this->set('especifica',$this->cfpd01_ano_especifica->findAll('cod_especifica='.$YYY['cfpd05']['cod_especifica'].' and ejercicio='.$ejercicio.' and cod_grupo=4',null,null,null, null, null));
			   $this->set('subespecifica',$this->cfpd01_ano_sub_espec->findAll('cod_sub_espec='.$YYY['cfpd05']['cod_sub_espec'].' and ejercicio='.$ejercicio.' and cod_grupo=4',null,null,null, null, null));
               $this->set('auxiliar',$this->cfpd05_auxiliar->findAll($this->SQLCA($ejercicio).' and cod_sector='.$YYY['cfpd05']['cod_sector'].' and cod_programa='.$YYY['cfpd05']['cod_programa'].' and cod_sub_prog='.$YYY['cfpd05']['cod_sub_prog'].' and cod_proyecto='.$YYY['cfpd05']['cod_proyecto'].' and cod_activ_obra='.$YYY['cfpd05']['cod_activ_obra'].' and cod_partida='.$YYY['cfpd05']['cod_partida'].' and cod_generica='.$YYY['cfpd05']['cod_generica'].' and cod_especifica='.$YYY['cfpd05']['cod_especifica'].' and cod_sub_espec='.$YYY['cfpd05']['cod_sub_espec'].' and cod_auxiliar='.$YYY['cfpd05']['cod_auxiliar'],null,null,null, null, null));

          	$this->set('DATACFPD05',$dataCFPD05);
          	$this->set('siguiente',$pagina+1);
          	$this->set('anterior',$pagina-1);
          	 $this->bt_nav($Tfilas,$pagina);
            }else{
	 	       $this->set('DATACFPD05','');
	 	       $this->set('errorMessage', 'No se encontrar&oacute;n datos para el a&ntilde;o '.$Ano);
	         }///fin else  del if-else que compara las Tfilas
          }//fin else

}//fin generar_d

}//fin class


?>