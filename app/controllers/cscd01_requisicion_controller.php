<?php

class Cscd01RequisicionController extends AppController {
 	var $name = 'cscd01_requisicion';
 	var $uses = array ('ccfd04_cierre_mes', 'v_cfpd05_denominaciones', 'v_cfpd05_disponibilidad', 'cscd01_requisicion_cuerpo', 'cscd01_requisicion_partidas', 'cugd07_firmas_oficio_anulacion');
 	var $helpers = array ('Html','Ajax','Javascript','Sisap');


function beforeFilter(){
	$this->checkSession();
}


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



function index(){
	$this->layout ="ajax";
	$ano = $this->ano_ejecucion();
	if($ano != ""){
		$this->set("ano",$ano);
		$this->Session->write('ano_reporte',$ano);
	}else{
		$this->set("ano", date("Y"));
		$this->Session->write('ano_reporte', date("Y"));
	}

		$des=$this->Session->read('SScoddep');
		if($des==1){
			$conds=$this->SQLCX();
		}else{
			$conds=$this->SQLCA();
		}

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
}







function select3($select=null,$var=null) { //select codigos presupuestarios
	$this->layout = "ajax";

if($select!=null && $var!=null){
		if($this->verifica_SS(5)==1){
			$cond=$this->SQLCX();
    	    // $cond=$this->SQLCA_report(1); // Para Reporte x Rene x Inst-dep=01(A.C.)...
    	}else{
    		$cond=$this->SQLCA();
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
							$this->set('disponibilidad',$disponibilidad);

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
				$this->set('disponibilidad',$disponibilidad);
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






function add_pp(){
	$this->layout="ajax";

	//if(isset($this->data["cscd01_requisicion"])){
		if(isset($_SESSION["i"])){
			$i=$this->Session->read("i")+1;
			$this->Session->write("i",$i);
		}else{
			$this->Session->write("i",0);
			$i=0;
		}

		 $vec[$i][0]=$this->data["cscd01_requisicion"]["ano_partidas"];
		 $vec[$i][1]=$this->data["cscd01_requisicion"]["cod_sector"];
		 $vec[$i][2]=$this->data["cscd01_requisicion"]["cod_programa"];
		 $vec[$i][3]=$this->data["cscd01_requisicion"]["cod_subprograma"];
		 $vec[$i][4]=$this->data["cscd01_requisicion"]["cod_proyecto"];
		 $vec[$i][5]=$this->data["cscd01_requisicion"]["cod_actividad"];
		 $vec[$i][6]=$this->data["cscd01_requisicion"]["cod_partida"];
		 $vec[$i][7]=$this->data["cscd01_requisicion"]["cod_generica"];
		 $vec[$i][8]=$this->data["cscd01_requisicion"]["cod_especifica"];
		 $vec[$i][9]=$this->data["cscd01_requisicion"]["cod_subespecifica"];
		 $vec[$i][10]=$this->data["cscd01_requisicion"]["cod_auxiliar"];
		 $vec[$i][11]=$this->Formato1($this->data["cscd01_requisicion"]["monto_disponibilidad"]);
		 $vec[$i]["id"]=$i;

		 if(isset($_SESSION["items"]) && $_SESSION["items"] != null){
	    	foreach($_SESSION["items"] as $codpp){
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
			}
		 }else{
			$_SESSION["items"]=$vec;
		 }
	// }
}// fin function add_pp: Agrega Partida Presupuestaria


function del_pp ($id=null) {
	$this->layout="ajax";
	if($id!=null){
		$_SESSION["items"][$id]=null;
	}
}// fin function del_pp: Elimina Partida Presupuestaria de la list


function clean_list_pp () {
	$this->layout = "ajax";
	$this->Session->delete("items");
	$this->Session->delete("i");
}// fin function clean_list_pp





function guardar_datos(){
	$this->layout="ajax";
	$cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$ano_requisicion = $this->data['cscd01_requisicion']['ano_requisicion'];
	$numero_requisicion = $this->data['cscd01_requisicion']['numero_requisicion'];
	$unidad_solicitante = $this->data['cscd01_requisicion']['unidad_solicitante'];
	$fecha_requisicion = $this->Cfecha($this->data['cscd01_requisicion']['fecha_requisicion'], 'A-M-D');
	$descripcion = $this->data['cscd01_requisicion']['descripcion'];
	$codigo_cprecio = $this->data['cscd01_requisicion']['codigo_cprecio'];

	if($this->data['cscd01_requisicion'] != null){

		if(isset($_SESSION["items"]) && $_SESSION["items"]!=null){

		$sql = "BEGIN; INSERT INTO cscd01_requisicion_cuerpo (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, ano_requisicion, numero_requisicion, unidad_solicitante, fecha_requisicion, descripcion_adquisicion, cod_consulta_precio) VALUES($cod_presi, $cod_entidad, $cod_tipo_inst, $cod_inst, $cod_dep, $ano_requisicion, '$numero_requisicion', '$unidad_solicitante', '$fecha_requisicion', '$descripcion', '$codigo_cprecio');";
    	$sws = $this->cscd01_requisicion_cuerpo->execute($sql);
    	if($sws > 1){
		      foreach($_SESSION["items"] as $pp){
			     if($pp!=null){
  			         $datos_pp[]="($cod_presi, $cod_entidad, $cod_tipo_inst, $cod_inst, $cod_dep, $ano_requisicion, '$numero_requisicion', '$unidad_solicitante', ".$pp[1].",".$pp[2].",".$pp[3].",".$pp[4].",".$pp[5].",".$pp[6].",".$pp[7].",".$pp[8].",".$pp[9].",".$pp[10].",".$pp[11].")";
			    }
		     } // fin foreach
			if(!empty($datos_pp)){
            	$VALUES_PP=implode(',', $datos_pp);
			 	$rws=$this->cscd01_requisicion_partidas->execute("INSERT INTO cscd01_requisicion_partidas VALUES ".$VALUES_PP.";");
            }else{$rws = 1;}

			if($rws > 1){
				$this->cscd01_requisicion_partidas->execute("COMMIT;");
				$this->set('Message_existe', "Los datos fueron guardados correctamente. - Puede generar la certificaci&oacute;n de disponibilidad presupuestaria.");
			}else{
				$this->cscd01_requisicion_partidas->execute("ROLLBACK;");
				$this->set('errorMessage', "No se pudo guardar los datos - Intente nuevamente...");
			}

		}else{
			$this->cscd01_requisicion_partidas->execute("ROLLBACK;");
    		$this->set('errorMessage', "No se pudo guardar los datos - Intente nuevamente...");
    	}
	}else{
		$this->set('errorMessage', "Debe ingresar todos los datos (Partida Presupuestaria)...");
    }

	}else{
    	$this->set('errorMessage', "No se puede guardar los datos - faltan datos - Intente nuevamente...");
    }





	echo "<script>
			document.getElementById('descripcion').value='';
			document.getElementById('b_generar').disabled='';
		</script>";

	$this->set("reporte", true);
	$this->set("ano_requisicion", $ano_requisicion);
	$this->set("numero_requisicion", $numero_requisicion);
	$this->set("unidad_solicitante", $unidad_solicitante);

	$ano = $this->ano_ejecucion();
	if($ano != ""){
		$this->set("ano",$ano);
		$this->Session->write('ano_reporte',$ano);
	}else{
		$this->set("ano", date("Y"));
		$this->Session->write('ano_reporte', date("Y"));
	}

		$des=$this->Session->read('SScoddep');
		if($des==1){
			$conds=$this->SQLCX();
		}else{
			$conds=$this->SQLCA();
		}

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

		if(isset($_SESSION["items"])){
			$this->Session->delete("i");
			$this->Session->delete("items");
		}

	$this->render('index');
} // fin function guardar_datos


function nrequisicion($ano = null){
	$this->layout="ajax";
	if($ano != null){
	$lista = $this->cscd01_requisicion_cuerpo->generateList($this->SQLCA()." and ano_requisicion=$ano", 'numero_requisicion ASC', null, '{n}.cscd01_requisicion_cuerpo.numero_requisicion', '{n}.cscd01_requisicion_cuerpo.numero_requisicion');
	if(!empty($lista)){
		$this->set('requisicion', $lista);
		$this->set('ano', $ano);
	}else{
		$this->set('requisicion', array());
		$this->set('ano', null);
	}
		echo '<script>
			document.getElementById("unidad_solic").innerHTML=\'<select style="font-weight:bold;background-color:#e0ffff;color:#840000;font-size:12pt;text-align:left;" class="inputtext input_datopk"></select>\';
		</script>';
		echo "<script>
			document.getElementById('b_generar').disabled=true;
			if(document.getElementById('bt_eliminar')){document.getElementById('bt_eliminar').disabled=false;}
		</script>";
	}else{
		$this->set('requisicion', array());
		$this->set('ano', null);
		echo '<script>
			document.getElementById("unidad_solic").innerHTML=\'<select style="font-weight:bold;background-color:#e0ffff;color:#840000;font-size:12pt;text-align:left;" class="inputtext input_datopk"></select>\';
		</script>';
		echo "<script>
			document.getElementById('b_generar').disabled=true;
			if(document.getElementById('bt_eliminar')){document.getElementById('bt_eliminar').disabled=false;}
		</script>";
	}
}

function nusolic($ano = null, $num_req = null){
	$this->layout="ajax";
	if($ano != null && $num_req != null){
	$lista = $this->cscd01_requisicion_cuerpo->generateList($this->SQLCA()." and ano_requisicion=$ano and numero_requisicion='$num_req'", 'unidad_solicitante ASC', null, '{n}.cscd01_requisicion_cuerpo.unidad_solicitante', '{n}.cscd01_requisicion_cuerpo.unidad_solicitante');
	if(!empty($lista)){
		$this->set('unidads', $lista);
		echo "<script>
			document.getElementById('b_generar').disabled=false;
			if(document.getElementById('bt_eliminar')){document.getElementById('bt_eliminar').disabled=false;}
		</script>";
	}else{
		$this->set('unidads', array());
		echo "<script>
			document.getElementById('b_generar').disabled=true;
			if(document.getElementById('bt_eliminar')){document.getElementById('bt_eliminar').disabled=false;}
		</script>";
	}
	}else{
		$this->set('unidads', array());
		echo "<script>
			document.getElementById('b_generar').disabled=true;
			if(document.getElementById('bt_eliminar')){document.getElementById('bt_eliminar').disabled=false;}
		</script>";
	}
}



function nrequisicion2($ano = null){
	$this->layout="ajax";
		echo "<script>if(document.getElementById('fecha_requisicion').value!=''){
			document.getElementById('cargar_consulta').innerHTML='';}
		</script>";
	if($ano != null){
	$lista = $this->cscd01_requisicion_cuerpo->generateList($this->SQLCA()." and ano_requisicion=$ano", 'numero_requisicion ASC', null, '{n}.cscd01_requisicion_cuerpo.numero_requisicion', '{n}.cscd01_requisicion_cuerpo.numero_requisicion');
	if(!empty($lista)){
		$this->set('requisicion', $lista);
		$this->set('ano', $ano);
	}else{
		$this->set('requisicion', array());
		$this->set('ano', null);
	}
		echo '<script>
			document.getElementById("unidad_solic").innerHTML=\'<select style="font-weight:bold;background-color:#e0ffff;color:#840000;font-size:12pt;text-align:left;" class="inputtext input_datopk"></select>\';
		</script>';
		echo "<script>
			document.getElementById('b_generar').disabled=true;
			document.getElementById('bt_eliminar').disabled=true;
		</script>";
	}else{
		$this->set('requisicion', array());
		$this->set('ano', null);
		echo '<script>
			document.getElementById("unidad_solic").innerHTML=\'<select style="font-weight:bold;background-color:#e0ffff;color:#840000;font-size:12pt;text-align:left;" class="inputtext input_datopk"></select>\';
		</script>';
		echo "<script>
			document.getElementById('b_generar').disabled=true;
			document.getElementById('bt_eliminar').disabled=true;
		</script>";
	}
}

function nusolic2($ano = null, $num_req = null){
	$this->layout="ajax";
		echo "<script>if(document.getElementById('fecha_requisicion').value!=''){
			document.getElementById('cargar_consulta').innerHTML='';}
		</script>";
	if($ano != null && $num_req != null){
	$lista = $this->cscd01_requisicion_cuerpo->generateList($this->SQLCA()." and ano_requisicion=$ano and numero_requisicion='$num_req'", 'unidad_solicitante ASC', null, '{n}.cscd01_requisicion_cuerpo.unidad_solicitante', '{n}.cscd01_requisicion_cuerpo.unidad_solicitante');
	if(!empty($lista)){
		$this->set('unidads', $lista);
		$this->set('ano', $ano);
		$this->set('num_req', $num_req);
		echo "<script>
			document.getElementById('b_generar').disabled=true;
			document.getElementById('bt_eliminar').disabled=true;
		</script>";
	}else{
		$this->set('unidads', array());
		$this->set('ano', null);
		$this->set('num_req', null);
		echo "<script>
			document.getElementById('b_generar').disabled=true;
			document.getElementById('bt_eliminar').disabled=true;
		</script>";
	}
	}else{
		$this->set('unidads', array());
		$this->set('ano', null);
		$this->set('num_req', null);
		echo "<script>
			document.getElementById('b_generar').disabled=true;
			document.getElementById('bt_eliminar').disabled=true;
		</script>";
	}
}

function consulta($var = null, $ano = null, $numero = null, $unidad = null){
  if($var == 'no'){
	$this->layout="ajax";
	$this->set('var',$var);
	$ano_ejec = $this->ano_ejecucion();
	$rs=$this->cscd01_requisicion_cuerpo->execute("SELECT DISTINCT ano_requisicion FROM cscd01_requisicion_cuerpo WHERE ".$this->SQLCA()." ORDER BY ano_requisicion ASC");
		foreach($rs as $l){
			$v[]=$l[0]["ano_requisicion"];
			$d[]=$l[0]["ano_requisicion"];
		}
		if(isset($v) && is_array($v)){
			$anos = array_combine($v, $d);
		}else{
			$anos = array("$ano_ejec"=>"".$ano_ejec);
		}

		$anos = $anos != null ? $anos : array();
		$this->set('anos', $anos);
		$this->set('ano_ejec', $ano_ejec);

    $lista = $this->cscd01_requisicion_cuerpo->generateList($this->SQLCA()." and ano_requisicion=$ano_ejec", 'numero_requisicion ASC', null, '{n}.cscd01_requisicion_cuerpo.numero_requisicion', '{n}.cscd01_requisicion_cuerpo.numero_requisicion');
	if(!empty($lista)){
		$this->set('requisicion', $lista);
	}else{
		$this->set('requisicion', array());
	}
  }
}

function consulta2($var = null, $ano = null, $numero = null, $unidad = null){
  if($var == 'no'){
	$this->layout="ajax";
	$this->set('var',$var);
	if($ano != null){
		$ano_ejec = $ano;
	}else{
		$ano_ejec = $this->ano_ejecucion();
		if($ano_ejec != null){}{$ano_ejec = date("Y");}
	}

	$dato_cuerpo = $this->cscd01_requisicion_cuerpo->findAll($this->SQLCA()." and ano_requisicion=$ano_ejec and numero_requisicion='$numero' and unidad_solicitante='$unidad'", array('fecha_requisicion','descripcion_adquisicion','cod_consulta_precio'),'numero_requisicion, unidad_solicitante ASC',1,1,null);
	$dato_partida = $this->cscd01_requisicion_partidas->findAll($this->SQLCA()." and ano_requisicion=$ano_ejec and numero_requisicion='$numero' and unidad_solicitante='$unidad'", array('ano_requisicion','cod_sector','cod_programa','cod_sub_prog','cod_proyecto','cod_activ_obra','cod_partida','cod_generica','cod_especifica','cod_sub_espec','cod_auxiliar','disponibilidad'),'cod_presi,cod_entidad,cod_tipo_inst,cod_inst,cod_dep,cod_sector,cod_programa,cod_sub_prog,cod_proyecto,cod_activ_obra,cod_partida,cod_generica,cod_especifica,cod_sub_espec,cod_auxiliar ASC',null,null,null);
	$this->set('dato_cuerpo', $dato_cuerpo);
	$this->set('dato_partida', $dato_partida);

  }
}


function certificacion_dispo($var = null, $ano = null, $numero = null, $unidad = null, $tipo_doc_anul = 12111){
  if($var == 'no'){
	$this->layout="ajax";
	$this->set('var',$var);

	if($ano == 'a'){
		$this->set('vara',$ano);
	}

	$ano_ejec = $this->ano_ejecucion();
	$rs=$this->cscd01_requisicion_cuerpo->execute("SELECT DISTINCT ano_requisicion FROM cscd01_requisicion_cuerpo WHERE ".$this->SQLCA()." ORDER BY ano_requisicion ASC");
		foreach($rs as $l){
			$v[]=$l[0]["ano_requisicion"];
			$d[]=$l[0]["ano_requisicion"];
		}
		if(isset($v) && is_array($v)){
			$anos = array_combine($v, $d);
		}else{
			$anos = array("$ano_ejec"=>"".$ano_ejec);
		}

		$anos = $anos != null ? $anos : array();
		$this->set('anos', $anos);
		$this->set('ano_ejec', $ano_ejec);

    $lista = $this->cscd01_requisicion_cuerpo->generateList($this->SQLCA()." and ano_requisicion=$ano_ejec", 'numero_requisicion ASC', null, '{n}.cscd01_requisicion_cuerpo.numero_requisicion', '{n}.cscd01_requisicion_cuerpo.numero_requisicion');
	if(!empty($lista)){
		$this->set('requisicion', $lista);
	}else{
		$this->set('requisicion', array());
	}

	$firmantes = $this->cugd07_firmas_oficio_anulacion->findAll($this->SQLCA()." and tipo_documento=12111", "nombre_primera_firma, cargo_primera_firma");
	if(empty($firmantes)){
		$this->set('errorMessage','Por favor, ingrese el nombre y cargo del firmante...');
		$this->set('nombre_primera_firma','');
		$this->set('cargo_primera_firma','');
		$this->set('tipo_doc_anul', 12111);
		$this->set('b_readonly','');
		$firma_existe = 'no';
	}else{
		$this->set('nombre_primera_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['nombre_primera_firma']);
		$this->set('cargo_primera_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['cargo_primera_firma']);
		$this->set('tipo_doc_anul', 12111);
		$this->set('b_readonly','readonly');
		$firma_existe = 'si';
	}

	$this->set('firma_existe',$firma_existe);

  }else{
		$this->layout = "pdf";
		if($ano != null && $numero != null && $unidad != null){
		}else{
			$ano = $this->data['cnmp99_prenomina']['ano_requisicion'];
			$numero = $this->data['cnmp99_prenomina']['numero_requisicion'];
			$unidad = $this->data['cnmp99_prenomina']['unidad_solicitante'];
			$tipo_doc_anul = isset($this->data['cnmp99_prenomina']['tipo_doc_anul']) ? $this->data['cnmp99_prenomina']['tipo_doc_anul'] : '12111';
		}
		$req_cuerpo = $this->cscd01_requisicion_cuerpo->execute("SELECT ano_requisicion, numero_requisicion, unidad_solicitante, fecha_requisicion, descripcion_adquisicion, cod_consulta_precio FROM cscd01_requisicion_cuerpo WHERE ".$this->SQLCA()." and ano_requisicion=$ano and numero_requisicion='$numero' and unidad_solicitante='$unidad' LIMIT 1;");
		$req_partida = $this->cscd01_requisicion_partidas->execute("SELECT * FROM cscd01_requisicion_partidas WHERE ".$this->SQLCA()." and ano_requisicion=$ano and numero_requisicion='$numero' and unidad_solicitante='$unidad' ORDER BY cod_sector, cod_programa, cod_sub_prog, cod_proyecto, cod_activ_obra, cod_partida, cod_generica, cod_especifica, cod_sub_espec, cod_auxiliar ASC;");
		$firmantes = $this->cugd07_firmas_oficio_anulacion->findAll($this->SQLCA()." and tipo_documento=$tipo_doc_anul", "nombre_primera_firma, cargo_primera_firma");
		$this->set('req_cuerpo', $req_cuerpo);
		$this->set('req_partida', $req_partida);
		$this->set('firmantes', $firmantes);
  }
        $mcpio_defecto=$this->cscd01_requisicion_cuerpo->execute("SELECT cod_republica, cod_estado, cod_municipio FROM cugd90_municipio_defecto WHERE ".$this->SQLCA()."");
	 if($mcpio_defecto!=null){
		$cod_republica=$mcpio_defecto[0][0]["cod_republica"];
		$cod_estado=$mcpio_defecto[0][0]["cod_estado"];
		$cod_municipio=$mcpio_defecto[0][0]["cod_municipio"];

			$conocido_como=$this->cscd01_requisicion_cuerpo->execute("SELECT conocido FROM cugd01_municipios WHERE cod_republica = ".$cod_republica." and cod_estado = ".$cod_estado." and cod_municipio = ".$cod_municipio."");
			if($conocido_como!=null){
			$ciudad=$conocido_como[0][0]["conocido"];
			}
	 }
        $this->set('ciudad' ,$ciudad);
}



function guardar_editar_firmas($varf=null){
	$this->layout="ajax";

	if($varf=='no'){
		$this->set('varf',''.$varf);
		$this->set('Message_existe','Puede modificar los datos de los firmantes...');
	}else if($varf=='si'){

		$tipo_doc_anul = $this->data['cnmp99_prenomina']['tipo_doc_anul'];
		$nombre_primera_firma = $this->data['cnmp99_prenomina']['nombre_primera_firma'];
		$cargo_primera_firma  = $this->data['cnmp99_prenomina']['cargo_primera_firma'];
		$nombre_segunda_firma = 'N/A';
		$cargo_segunda_firma = 'N/A';
		$nombre_tercera_firma = 'N/A';
		$cargo_tercera_firma = 'N/A';

     	$firmas = $this->cugd07_firmas_oficio_anulacion->findCount($this->condicion()." and tipo_documento=$tipo_doc_anul");

     	$cp  = $this->Session->read('SScodpresi');
		$ce  = $this->Session->read('SScodentidad');
		$cti = $this->Session->read('SScodtipoinst');
		$ci  = $this->Session->read('SScodinst');
		$cd  = $this->Session->read('SScoddep');

		if($firmas==0){
			$muestr_accion = 'Registradas';
			$sql_ejecutar = "INSERT INTO cugd07_firmas_oficio_anulacion (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, tipo_documento, nombre_primera_firma, cargo_primera_firma, nombre_segunda_firma, cargo_segunda_firma, nombre_tercera_firma, cargo_tercera_firma) VALUES ($cp, $ce, $cti, $ci, $cd, $tipo_doc_anul,'$nombre_primera_firma', '$cargo_primera_firma', '$nombre_segunda_firma', '$cargo_segunda_firma', '$nombre_tercera_firma', '$cargo_tercera_firma')";
     	}else{
     		$muestr_accion = 'Modificadas';
     		$sql_ejecutar = "UPDATE cugd07_firmas_oficio_anulacion SET nombre_primera_firma='$nombre_primera_firma', cargo_primera_firma='$cargo_primera_firma', nombre_segunda_firma='$nombre_segunda_firma', cargo_segunda_firma='$cargo_segunda_firma', nombre_tercera_firma='$nombre_tercera_firma', cargo_tercera_firma='$cargo_tercera_firma' WHERE ".$this->SQLCA()." and tipo_documento=".$tipo_doc_anul;
     	}

	$swi = $this->cugd07_firmas_oficio_anulacion->execute($sql_ejecutar);

	$this->set('varf',''.$varf);

	if($swi>1){
		$this->set('Message_existe','Las firmas fuer&oacute;n '.$muestr_accion.' correctamente');
	}else{
		$this->set('errorMessage','Las firmas no fuer&oacute;n '.$muestr_accion.'');
	}
}else{
	$this->set('errorMessage','Lo siento la informaci&oacute;n no puede ser procesada...');
}
} // fin funcion guardar_editar_firma



function delete_requisicion(){
	$this->layout="ajax";
	$ano = $this->data['cnmp99_prenomina']['ano_requisicion'];
	$numero = $this->data['cnmp99_prenomina']['numero_requisicion'];
	$unidad = $this->data['cnmp99_prenomina']['unidad_solicitante'];

	if($ano != null && $numero != null && $unidad != null){
	$sw_partida = $this->cscd01_requisicion_partidas->execute("BEGIN; DELETE FROM cscd01_requisicion_partidas WHERE ".$this->SQLCA()." and ano_requisicion=$ano and numero_requisicion='$numero' and unidad_solicitante='$unidad';");
	if($sw_partida > 1){
		$sw_cuerpo = $this->cscd01_requisicion_cuerpo->execute("DELETE FROM cscd01_requisicion_cuerpo WHERE ".$this->SQLCA()." and ano_requisicion=$ano and numero_requisicion='$numero' and unidad_solicitante='$unidad';");
		if($sw_cuerpo > 1){
			$this->cscd01_requisicion_partidas->execute("COMMIT;");
			$this->set('Message_existe','La requisici&oacute;n fue eliminada exitosamente.');
			echo "<script>
				document.getElementById('descripcion').value='';
			</script>";
		}else{
			$this->cscd01_requisicion_partidas->execute("ROLLBACK;");
			$this->set('errorMessage','Lo siento no se pudo eliminar la requisici&oacute;n . . . Intente otra vez.');
		}
	}else{
		$this->cscd01_requisicion_partidas->execute("ROLLBACK;");
		$this->set('errorMessage','Lo siento no se pudo eliminar la requisici&oacute;n . . . Intente otra vez.');
	}
	}else{
		$this->set('errorMessage','Lo siento no se puede eliminar la requisici&oacute;n . . . Faltan datos.');
	}

	$this->consulta('no');
	$this->render('consulta','ajax');
}

} // FIN CLASS
?>
