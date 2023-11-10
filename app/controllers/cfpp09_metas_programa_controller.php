<?php
 class Cfpp09MetasProgramaController extends AppController{

     var $uses = array('ccfd04_cierre_mes','cfpd09_metas_programa','cfpd09_metas_sector', 'cfpd02_sector', 'cfpd02_programa', 'cfpd05', 'cfpd05_auxiliar', 'cfpd02_sub_prog', 'cfpd02_proyecto', 'cfpd02_activ_obra', 'cfpd01_ano_grupo', 'cfpd01_ano_partida', 'cfpd01_ano_generica', 'cfpd01_ano_especifica', 'cfpd01_ano_sub_espec', 'cfpd01_ano_auxiliar', 'cfpd01_formulacion','v_programas_metas');
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
    $cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst;
	$year = $this->cfpd01_formulacion->findAll($condicion, null, 'ano_formular ASC', null);
    $this->Session->write('ano',$year[0]['cfpd01_formulacion']['ano_formular']);
	$ano = $year[0]['cfpd01_formulacion']['ano_formular'];
	$_SESSION['ano_meta']=$ano;
	$this->set('ano',$ano);//echo $this->SQLCA($ano);
	$listaSector=$this->v_programas_metas->generateList($this->SQLCA($ano)." and identificador=1", 'cod_sector ASC', null, '{n}.v_programas_metas.cod_sector', '{n}.v_programas_metas.denominacion');
	$this->concatena($listaSector,'sector');
//	$this->AddCero('sector',$listaSector);

	$this->Session->write('ano',$ano);
	$dato = null;

// fin del codigo

       $this->set('entidadFederal',$this->verifica_SS(6));

}//fin index



function datos($var=null){
	$this->layout = "ajax";
	$ano =  $this->Session->read('ano');
	$sec =  $this->Session->read('sec');
	if($var!=null){
		$v=$this->cfpd09_metas_programa->execute("select metas,unidad_medida,cantidad from cfpd09_metas_programa where ".$this->SQLCA()." and ano=".$ano." and cod_sector=".$sec." and cod_programa=".$var);
		if($v){
			$meta=$v[0][0]["metas"];
			$unidad=$v[0][0]["unidad_medida"];
			$cantidad=$v[0][0]["cantidad"];
			$this->set('metas',$meta);
			$this->set('unidad',$unidad);
			$this->set('cantidad',$cantidad);
			echo "<script>";
				echo "document.getElementById('modi').disabled=false;";
				echo "document.getElementById('elimi').disabled=false;";
			echo "</script>";
		}else{
			$this->set('nada','');
		}
	}else{
		$this->set('metas','');
		$this->set('unidad','');
	}


}// fin datos



function select3($select=null,$var=null) { //select codigos presupuestarios
	$this->layout = "ajax";
	//echo $select. " ".$var;
	if($var!=null){
    $cond =$this->SQLCA();
	switch($select){
		case 'sector':
		  $this->set('SELECT','programa');
		  $this->set('codigo','sector');
		  $this->set('seleccion','');
		  $this->set('n',1);
		  $ano =  $this->Session->read('ano');
		  $cond .=" and ano=".$var;
		  $lista=  $this->cfpd02_sector->generateList($cond, 'cod_sector ASC', null, '{n}.cfpd02_sector.cod_sector', '{n}.cfpd02_sector.cod_sector');
          $this->AddCero('vector', $lista);
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
          $this->concatena($lista,'vector');
//          $this->AddCero('vector', $lista);
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
		  $lista=  $this->cfpd02_sub_prog->generateList($cond, 'cod_sub_prog ASC', null, '{n}.cfpd02_sub_prog.cod_sub_prog', '{n}.cfpd02_sub_prog.cod_sub_prog');
          $this->AddCero('vector', $lista);
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
		  $lista=  $this->cfpd02_proyecto->generateList($cond, 'cod_proyecto ASC', null, '{n}.cfpd02_proyecto.cod_proyecto', '{n}.cfpd02_proyecto.cod_proyecto');
          $this->AddCero('vector', $lista);
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
		  $cond .=" and ano=".$ano." and cod_sector=".$sec." and cod_programa=".$prog." and cod_sub_prog=".$subp." and cod_proyecto=".$var;
		  $lista=  $this->cfpd02_activ_obra->generateList($cond, 'cod_activ_obra ASC', null, '{n}.cfpd02_activ_obra.cod_activ_obra', '{n}.cfpd02_activ_obra.cod_activ_obra');
          $this->AddCero('vector', $lista);
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
    $ano  =  $this->Session->read('ano');
    $cond = $this->SQLCA()." and ano=".$ano;
    $cond2 = $this->SQLCA();
	switch($select){
		case 'sector':
		  $ano =  $this->Session->read('ano');
		  $this->Session->write('dsec',$var);
		  $cond .=" and cod_sector=".$var;
		  $a=  $this->cfpd02_sector->findAll($cond);
		  //echo $cond;
		  //print_r($a);
		  if($a!=null){
		  	 echo "<input type='text' name='data[ccfp01_division][cod_div_contable1]' value='".$a[0]['cfpd02_sector']['denominacion']."'  id='presupuesto2' readonly='readonly' class='inputtext' />";
		  }else{
		  	 echo "<input type='text' name='data[ccfp01_division][cod_div_contable1]' value=''   readonly='readonly' class='inputtext' />";
		  }
//          echo $a[0]['cfpd02_sector']['denominacion'];
		break;
		case 'programa':
		  $ano =  $this->Session->read('ano');
		  $sec =  $this->Session->read('dsec');
		  $this->Session->write('dprog',$var);
		  $cond .=" and cod_sector=".$sec." and cod_programa=".$var;
		  $a=  $this->cfpd02_programa->findAll($cond);
		  if($a!=null){
		  	 echo "<input type='text' name='data[ccfp01_division][cod_div_contable1]' value='".$a[0]['cfpd02_programa']['denominacion']."'  id='presupuesto2' readonly='readonly' class='inputtext' />";
		  }else{
		  	 echo "<input type='text' name='data[ccfp01_division][cod_div_contable1]' value=''   readonly='readonly' class='inputtext' />";
		  }
//          echo $a[0]['cfpd02_programa']['denominacion'];
		break;
		case 'subprograma':
		  $ano =  $this->Session->read('ano');
		  $sec =  $this->Session->read('dsec');
		  $prog =  $this->Session->read('dprog');
		  $this->Session->write('dsubprog',$var);
		  $cond .=" and cod_sector=".$sec." and cod_programa=".$prog." and cod_sub_prog=".$var;
		  $a=  $this->cfpd02_sub_prog->findAll($cond);
          echo $a[0]['cfpd02_sub_prog']['denominacion'];
		break;
		case 'proyecto':
		  $ano =  $this->Session->read('ano');
		  $sec =  $this->Session->read('dsec');
		  $prog =  $this->Session->read('dprog');
		  $subprog =  $this->Session->read('dsubprog');
		  $this->Session->write('dproy',$var);
		  $cond .=" and cod_sector=".$sec." and cod_programa=".$prog." and cod_sub_prog=".$subprog." and cod_proyecto=".$var;
		  $a=  $this->cfpd02_proyecto->findAll($cond);
          echo $a[0]['cfpd02_proyecto']['denominacion'];
		break;
		case 'actividad':
		  $ano =  $this->Session->read('ano');
		  $sec =  $this->Session->read('dsec');
		  $prog =  $this->Session->read('dprog');
		  $subprog =  $this->Session->read('dsubprog');
		  $proy =  $this->Session->read('dproy');
		  $this->Session->write('activ',$var);
		  $cond .=" and cod_sector=".$sec." and cod_programa=".$prog." and cod_sub_prog=".$subprog." and cod_proyecto=".$proy." and cod_activ_obra=".$var;
		  $a=  $this->cfpd02_activ_obra->findAll($cond);
         echo $a[0]['cfpd02_activ_obra']['denominacion'];
		break;
	}//fin wsitch
	}else{
		echo "<input type='text' name='data[ccfp01_division][cod_div_contable1]' value=''  style='text-align:center' readonly='readonly' class='inputtext' />";
	}
}//fin mostrar3 codigos presupuestarios
function mostrarcodigo($select=null,$var=null) { //mostrar3 codigos presupuestarios
	$this->layout = "ajax";
if( $var!=null){
    $cond = $this->SQLCA();
    $cond2 = $this->SQLCA();
    $var= $var > 10 ? $var : "0".$var;
	switch($select){
		case 'sector':
		 echo "<input type='text' name='data[ccfp01_division][cod_div_contable1]' value='".$var."'  style='text-align:center' readonly='readonly' class='inputtext' />";
//          echo '<center>'.$var.'</center>';
		break;
		case 'programa':
		 echo "<input type='text' name='data[ccfp01_division][cod_div_contable1]' value='".$var."'  style='text-align:center' readonly='readonly' class='inputtext' />";
//		   echo '<center>'.$var.'</center>';
		break;
		case 'subprograma':
		   echo '<center>'.$var.'</center>';
		break;
		case 'proyecto':
		   echo '<center>'.$var.'</center>';
		break;
		case 'actividad':
		   echo '<center>'.$var.'</center>';
		break;

	}//fin wsitch
	}else{
		echo "<input type='text' name='data[ccfp01_division][cod_div_contable1]' value=''  style='text-align:center' readonly='readonly' class='inputtext' />";
	}
}//fin mostrar3 codigos presupuestarios

function guardar(){
	$this->layout = "ajax";
	$cod_presi = $this->Session->read('SScodpresi');
		$cod_entidad = $this->Session->read('SScodentidad');
		$cod_tipo_inst = $this->Session->read('SScodtipoinst');
		$cod_inst = $this->Session->read('SScodinst');
		$cod_dep = $this->Session->read('SScoddep');
		$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst;
		$year = $this->cfpd01_formulacion->findAll($condicion, null, 'ano_formular ASC', null);
		$ano=$year[0]['cfpd01_formulacion']['ano_formular'];
	if(!empty($this->data)){
	 $a=$this->data['cfpp09_metas_programa']['cod_sector'];
	 $b=$this->data['cfpp09_metas_programa']['cod_programa'];
	 $f=$this->data['cfpp09_metas_programa']['metas'];
	 $g=$this->data['cfpp09_metas_programa']['unidad_medida'];
	 $ca=$this->data['cfpp09_metas_programa']['cantidad'];
	 $ano=$this->data['cfpp09_metas_programa']['ano_formulacion'];
	}

	 $aa[1]=$this->verifica_SS(1);
	 $aa[2]=$this->verifica_SS(2);
	 $aa[3]=$this->verifica_SS(3);
	 $aa[4]=$this->verifica_SS(4);
	 $aa[5]=$this->verifica_SS(5);
	 $SQL_INSERT ="INSERT INTO cfpd09_metas_programa (cod_presi, cod_entidad,cod_tipo_inst,cod_inst,cod_dep,ano,cod_sector,cod_programa,metas,unidad_medida,cantidad)";
	 $SQL_INSERT .=" VALUES (".$aa[1].",".$aa[2].",".$aa[3].",".$aa[4].",".$aa[5].",".$ano.",".$a.",".$b.",'".$f."','".$g."','".$ca."')";
    // echo $SQL_INSERT;
     $condicion="cod_sector=".$a." and cod_programa=".$b." and ano=".$ano." and ".$this->SQLCA();
     $tc=$this->cfpd09_metas_programa->findCount($condicion);
	 if($tc==0){
     $x=$this->cfpd09_metas_programa->execute($SQL_INSERT);
     $this->data=null;
     $this->set('mensaje', 'Meta Agregada con exito.');
     $this->index();
	 $this->render("index");
	 }else if($tc!=0){
	  $this->set('mensajeError', 'Meta ya Existe');
	  $this->index();
	 $this->render("index");
	 }
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

function escribe_ano_meta ($var) {
	$this->layout = "ajax";
	if($var!='')
        $_SESSION['ano_meta']=$var;
        else
        $_SESSION['ano_meta']=date('Y');
}
function consultar($pagina=null){//echo 'pagina es '.$pagina.' y ano2 es '.$ano2;
 		$this->layout = "ajax";
 		$ano=$_SESSION['ano_meta'];
 		$this->set('ano',$ano);
 		$cod_presi = $this->Session->read('SScodpresi');
		$cod_entidad = $this->Session->read('SScodentidad');
		$cod_tipo_inst = $this->Session->read('SScodtipoinst');
		$cod_inst = $this->Session->read('SScodinst');
		$cod_dep = $this->Session->read('SScoddep');
		$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst;
		$year = $this->cfpd01_formulacion->findAll($condicion, null, 'ano_formular ASC', null);
		$anof=$year[0]['cfpd01_formulacion']['ano_formular'];
		$this->set('anof',$anof);
         if($pagina!=null){
          	 $pagina=$pagina;
          	  $Tfilas=$this->cfpd09_metas_programa->findCount($this->SQLCA().' and ano='.$ano);
          	 // echo $Tfilas;
          	 if($Tfilas==0){
          	 	$this->set('mensajeError', 'No existen datos para este año');
          	 	$this->index();
          		$this->render("index");
          	 }
          	 if($Tfilas!=0){
          	 $this->set('pag_cant',$pagina.'/'.$Tfilas);
          	 $datacpcp01=$this->cfpd09_metas_programa->findAll($this->SQLCA().' and ano='.$ano,null,'cod_sector,cod_programa ASC',1,$pagina,null);
          	 $sec=$this->cfpd02_sector->findAll($this->SQLCA().' and ano='.$ano.' and cod_sector='.$datacpcp01[0]['cfpd09_metas_programa']['cod_sector']);
          	 //$this->set('sec',$sec);
          	 $prog=$this->cfpd02_programa->findAll($this->SQLCA().' and ano='.$ano.' and cod_sector='.$datacpcp01[0]['cfpd09_metas_programa']['cod_sector'].' and cod_programa='.$datacpcp01[0]['cfpd09_metas_programa']['cod_programa']);

          	 $this->set('cod_sector',$sec[0]['cfpd02_sector']['cod_sector']);
          	 $this->set('deno_sector',$sec[0]['cfpd02_sector']['denominacion']);
          	 $this->set('cod_programa',$prog[0]['cfpd02_programa']['cod_programa']);
          	 $this->set('deno_programa',$prog[0]['cfpd02_programa']['denominacion']);

          	 //$this->set('prog',$prog);
          	 $this->set('DATOS',$datacpcp01);
          	 $this->set('siguiente',$pagina+1);
          	 $this->set('anterior',$pagina-1);
             $this->bt_nav($Tfilas,$pagina);
             }
 }else{
 	$pagina=1;
          	 $Tfilas=$this->cfpd09_metas_programa->findCount($this->SQLCA().' and ano='.$ano);
          	 // echo $Tfilas;
          	 if($Tfilas==0){
          	 	$this->set('mensajeError', 'No existen datos para este año');
          	 	$this->index();
          		$this->render("index");
          	 }
          	 if($Tfilas!=0){
          	 $this->set('pag_cant',$pagina.'/'.$Tfilas);
          	 $datacpcp01=$this->cfpd09_metas_programa->findAll($this->SQLCA().' and ano='.$ano,null,'cod_sector,cod_programa ASC',1,$pagina,null);
          	 $sec=$this->cfpd02_sector->findAll($this->SQLCA().' and ano='.$ano.' and cod_sector='.$datacpcp01[0]['cfpd09_metas_programa']['cod_sector']);
          	 //$this->set('sec',$sec);
          	 $prog=$this->cfpd02_programa->findAll($this->SQLCA().' and ano='.$ano.' and cod_sector='.$datacpcp01[0]['cfpd09_metas_programa']['cod_sector'].' and cod_programa='.$datacpcp01[0]['cfpd09_metas_programa']['cod_programa']);

          	 $this->set('cod_sector',$sec[0]['cfpd02_sector']['cod_sector']);
          	 $this->set('deno_sector',$sec[0]['cfpd02_sector']['denominacion']);
          	 $this->set('cod_programa',$prog[0]['cfpd02_programa']['cod_programa']);
          	 $this->set('deno_programa',$prog[0]['cfpd02_programa']['denominacion']);

          	 //$this->set('prog',$prog);
          	 $this->set('DATOS',$datacpcp01);
          	 $this->set('siguiente',$pagina+1);
          	 $this->set('anterior',$pagina-1);
             $this->bt_nav($Tfilas,$pagina);


 }
         }
}//fin function consultar2

 function modificar($a=null,$b=null,$siguiente=null){
		$this->layout = "ajax";
		$cod_presi = $this->Session->read('SScodpresi');
		$cod_entidad = $this->Session->read('SScodentidad');
		$cod_tipo_inst = $this->Session->read('SScodtipoinst');
		$cod_inst = $this->Session->read('SScodinst');
		$cod_dep = $this->Session->read('SScoddep');
		$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst;
		$year = $this->cfpd01_formulacion->findAll($condicion, null, 'ano_formular ASC', null);
		$ano=$year[0]['cfpd01_formulacion']['ano_formular'];
		$this->set('ano',$ano);
		$pagina=$siguiente-1;
		if(!isset($a)){
			$ano=$this->data['cfpp09_metas_programa']['ano_formulacion'];
			$a=$this->data['cfpp09_metas_programa']['cod_sector'];
			$b=$this->data['cfpp09_metas_programa']['cod_programa'];
			$this->set('pagina','index');
			$this->set('regreso','index');
		}else{
			$this->set('pagina',$pagina);
			$this->set('regreso','consultar/'.$pagina);
		}
		$sql=" cod_sector=".$a." and cod_programa=".$b." and ano=".$ano." and ".$this->SQLCA();
		$a=  $this->cfpd09_metas_programa->findAll($sql);
		$sec=$this->cfpd02_sector->findAll($this->SQLCA().' and ano='.$ano.' and cod_sector='.$a[0]['cfpd09_metas_programa']['cod_sector']);
		$prog=$this->cfpd02_programa->findAll($this->SQLCA().' and ano='.$ano.' and cod_sector='.$a[0]['cfpd09_metas_programa']['cod_sector'].' and cod_programa='.$a[0]['cfpd09_metas_programa']['cod_programa']);

		$this->set('DATOS',$a);
		$this->set('cod_sector',$sec[0]['cfpd02_sector']['cod_sector']);
		$this->set('deno_sector',$sec[0]['cfpd02_sector']['denominacion']);
		$this->set('cod_programa',$prog[0]['cfpd02_programa']['cod_programa']);
		$this->set('deno_programa',$prog[0]['cfpd02_programa']['denominacion']);

		//$sec=$this->cfpd02_sector->findAll($this->SQLCA().' and ano='.$ano);
		//$this->set('sec',$sec);
		//$prog=$this->cfpd02_programa->findAll($this->SQLCA().' and ano='.$ano);
		//$this->set('prog',$prog);
 }
 function guardar_modificar($a=null,$b=null,$pagina=null){
	$this->layout = "ajax";
		$cod_presi = $this->Session->read('SScodpresi');
		$cod_entidad = $this->Session->read('SScodentidad');
		$cod_tipo_inst = $this->Session->read('SScodtipoinst');
		$cod_inst = $this->Session->read('SScodinst');
		$cod_dep = $this->Session->read('SScoddep');
		$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst;
		$year = $this->cfpd01_formulacion->findAll($condicion, null, 'ano_formular ASC', null);
		$ano=$year[0]['cfpd01_formulacion']['ano_formular'];
	if(!empty($this->data)){
	 $metas=$this->data['cfpp09_metas_programa']['metas'];
	 $unidad_medida=$this->data['cfpp09_metas_programa']['unidad_medida'];
	 $cantidad=$this->data['cfpp09_metas_programa']['cantidad'];
	$sql=" cod_sector=".$a." and cod_programa=".$b." and ano=".$ano." and ".$this->SQLCA();
	 $sql2 ="update cfpd09_metas_programa set metas ='$metas',unidad_medida='$unidad_medida',cantidad='$cantidad' where ".$sql;
     $vvv=$this->cfpd09_metas_programa->execute($sql2);
     $this->data=null;
     $this->set('mensaje', 'Registro Modificado con exito.');
      if($pagina!='index'){
     	 $this->consultar($pagina);
	 	 $this->render("consultar");
     }else{
     	 $this->index();
		 $this->render("index");
     }


	}
}//fin guardar_modificar

function eliminar($a=null,$b=null,$hola=null){
	$this->layout = "ajax";
	$p=1;
		$cod_presi = $this->Session->read('SScodpresi');
		$cod_entidad = $this->Session->read('SScodentidad');
		$cod_tipo_inst = $this->Session->read('SScodtipoinst');
		$cod_inst = $this->Session->read('SScodinst');
		$cod_dep = $this->Session->read('SScoddep');
		$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst;
		$year = $this->cfpd01_formulacion->findAll($condicion, null, 'ano_formular ASC', null);
		$ano=$year[0]['cfpd01_formulacion']['ano_formular'];
		if(!isset($a)){
			$ano=$this->data['cfpp09_metas_programa']['ano_formulacion'];
			$a=$this->data['cfpp09_metas_programa']['cod_sector'];
			$b=$this->data['cfpp09_metas_programa']['cod_programa'];
			$p=0;
			$this->data['cfpp09_metas_programa']=null;
		}
	$sql=" cod_sector=".$a." and cod_programa=".$b." and ano=".$ano." and ".$this->SQLCA();
    $x=$this->cfpd09_metas_programa->findCount($sql);
	if($x!=0){
		$sql1 ="DELETE  FROM  cfpd09_metas_programa where ".$sql;
		$this->cfpd09_metas_programa->execute($sql1);
		$this->set('mensaje', 'Meta Eliminada con exito.');
		//echo $x;
	  $y=$this->cfpd09_metas_programa->findCount($this->SQLCA().' and ano='.$ano);
	//  if($p==0){
	//	$this->index();
     // 	$this->render("index");
	 // }else{
	  if($y!='0'){
	  	if($hola!=null){
	  		$this->buscar();
      $this->render("buscar");
	  	}else{
	  $this->consultar();
      $this->render("consultar");}
		}else if($y=='0'){
			$this->index();
      		$this->render("index");
		}//fin if
	  }
//}
	}

	function buscar () {
	$this->layout="ajax";
	$cod_presi = $this->Session->read('SScodpresi');
		$cod_entidad = $this->Session->read('SScodentidad');
		$cod_tipo_inst = $this->Session->read('SScodtipoinst');
		$cod_inst = $this->Session->read('SScodinst');
		$cod_dep = $this->Session->read('SScoddep');
		$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst;
		$year = $this->cfpd01_formulacion->findAll($condicion, null, 'ano_formular ASC', null);
		$ano=$year[0]['cfpd01_formulacion']['ano_formular'];
		$this->set('ano',$ano);
}
function lista_encontrados () {
	$this->layout="ajax";
	//	pr($this->data);
		$cod_presi = $this->Session->read('SScodpresi');
		$cod_entidad = $this->Session->read('SScodentidad');
		$cod_tipo_inst = $this->Session->read('SScodtipoinst');
		$cod_inst = $this->Session->read('SScodinst');
		$cod_dep = $this->Session->read('SScoddep');
		$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst;
		$year = $this->cfpd01_formulacion->findAll($condicion, null, 'ano_formular ASC', null);
		$anof=$year[0]['cfpd01_formulacion']['ano_formular'];
		$ano=$this->data['cfpp09_metas_programa']['ano_formulacion'];
		$this->set('anof',$anof);
	    $this->set('ano',$ano);
	if(!empty($this->data["cfpp09_metas_programa"])){
		$sector=$this->data["cfpp09_metas_programa"]["cod_sector"];
		$programa=$this->data["cfpp09_metas_programa"]["cod_programa"];
		$cod =" and cod_sector=".$this->data["cfpp09_metas_programa"]["cod_sector"];
		$cod .=" and cod_programa=".$this->data["cfpp09_metas_programa"]["cod_programa"];
		$cod_presi = $this->Session->read('SScodpresi');
		$cod_entidad = $this->Session->read('SScodentidad');
		$cod_tipo_inst = $this->Session->read('SScodtipoinst');
		$cod_inst = $this->Session->read('SScodinst');
		$cod_dep = $this->Session->read('SScoddep');
		$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep=".$cod_dep;
	        $Tfilas=$this->cfpd09_metas_programa->findCount($this->SQLCA().' and ano='.$ano.' and cod_sector='.$sector.' and cod_programa='.$programa);
			if($Tfilas!=0){
	           $DATOS_res = $this->cfpd09_metas_programa->findAll($this->SQLCA().$cod.' and ano='.$ano, null, 'cod_sector ASC',1,1, null);
			    $this->set('sec',$this->cfpd02_sector->findAll($this->SQLCA($ano)." and cod_sector=".$sector,null,'cod_sector ASC',1,1,null));
		        $this->set('pgr',$this->cfpd02_programa->findAll($this->SQLCA($ano)." and cod_sector=".$sector." and cod_programa=".$programa,null,'cod_sector ASC',1,1,null));
		        $this->set('DATA',$DATOS_res);
	    }else{
	    	$this->set('mensajeError', 'No se encontrar&oacute;n datos');
	    	//echo "no hayyyyyyyyyyyyyyyy";
	    }
	}//if empty
	else{
		echo "vacio";
	}
}//fin lista_encontrados



function modificar_buscar($a,$b){
 	$this->layout = "ajax";
		$cod_presi = $this->Session->read('SScodpresi');
		$cod_entidad = $this->Session->read('SScodentidad');
		$cod_tipo_inst = $this->Session->read('SScodtipoinst');
		$cod_inst = $this->Session->read('SScodinst');
		$cod_dep = $this->Session->read('SScoddep');
		$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst;
		$year = $this->cfpd01_formulacion->findAll($condicion, null, 'ano_formular ASC', null);
		$ano=$year[0]['cfpd01_formulacion']['ano_formular'];
		$this->set('ano',$ano);
       $sql=" cod_sector=".$a." and cod_programa=".$b." and ano=".$ano." and ".$this->SQLCA();
       		 $sec=$this->cfpd02_sector->findAll($this->SQLCA().' and ano='.$ano);
       		 $this->set('sec',$sec);
          	 $prog=$this->cfpd02_programa->findAll($this->SQLCA().' and ano='.$ano);
          	 $this->set('prog',$prog);
	   $a=  $this->cfpd09_metas_programa->findAll($sql);
	   $this->set('DATOS',$a);
}

 function guardar_buscar($a,$b){
	$this->layout = "ajax";
		$cod_presi = $this->Session->read('SScodpresi');
		$cod_entidad = $this->Session->read('SScodentidad');
		$cod_tipo_inst = $this->Session->read('SScodtipoinst');
		$cod_inst = $this->Session->read('SScodinst');
		$cod_dep = $this->Session->read('SScoddep');
		$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst;
		$year = $this->cfpd01_formulacion->findAll($condicion, null, 'ano_formular ASC', null);
		$ano=$year[0]['cfpd01_formulacion']['ano_formular'];
	if(!empty($this->data)){
	 $metas=$this->data['cfpp09_metas_programa']['metas'];
	 $unidad_medida=$this->data['cfpp09_metas_programa']['unidad_medida'];
	 $cantidad=$this->data['cfpp09_metas_programa']['cantidad'];
	 $sql=" cod_sector=".$a." and cod_programa=".$b." and ano=".$ano." and ".$this->SQLCA();
	 $sql2 ="update cfpd09_metas_programa set metas ='$metas',unidad_medida='$unidad_medida',cantidad='$cantidad' where ".$sql;
     $vvv=$this->cfpd09_metas_programa->execute($sql2);
     $this->data=null;
     $this->set('mensaje', 'Registro Modificado con exito.');
	 $this->buscar();
	 $this->render("buscar");


	}
}







}//fin clase cfpp09_metas_programa_metas_sector_Controller

