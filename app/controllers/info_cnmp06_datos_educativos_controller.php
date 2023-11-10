<?php
/*
 * Creado el  30/10/2007 a las 12:03:17 PM
 *
 * Herramienta: EasyEclipse.
 *
 * Proyecto: SIGEP
 *
 */
 class InfoCnmp06DatosEducativosController extends AppController {
   var $name = 'info_cnmp06_datos_educativos';
   var $uses = array('v_cnmd06_datos_educativos','cnmd06_datos_educativos','cnmd06_nivel_educacion','cnmd06_instituto_educativo','cnmd06_datos_personales','cugd01_republica','cugd01_estados','cugd01_municipios','cugd01_parroquias','cugd01_centropoblados');
   var $helpers = array('Html','Ajax','Javascript', 'Sisap','Infogob');

function checkSession(){
				if (!$this->Session->check('infogobierno')){
						$this->redirect('/infogobierno/salir_todo');
						exit();
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

function SQLCAX($ano=null){//sql para busqueda de codigos de arranque con y sin año
         $sql_re = "cod_republica=".$this->verifica_SS(1)."  and    ";
         return $sql_re;
    }//fin funcion SQLCA

function concatena($vector1=null, $nomVar=null){

	if($vector1 != null){

		foreach($vector1 as $x => $y){

			$cod[$x] = $this->zero($x).' - '.$y;
		}
		//print_r($cod);

		$this->set($nomVar, $cod);

	}
}//fin concatena

 function index(){
 	$this->layout ="ajax";
 	$this->data = null;
    $listarepublica=$this->cugd01_republica->generateList(null, 'cod_republica ASC', null, '{n}.cugd01_republica.cod_republica', '{n}.cugd01_republica.denominacion');
    $this->concatena($listarepublica, 'cod_republica');
    $listanivel=$this->cnmd06_nivel_educacion->generateList(null, 'denominacion ASC', null, '{n}.cnmd06_nivel_educacion.cod_nivel_educativo', '{n}.cnmd06_nivel_educacion.denominacion');
    $this->concatena($listanivel, 'cod_nivel_educativo');


    $listainstitucion=$this->cnmd06_instituto_educativo->generateList(null, 'denominacion ASC', null, '{n}.cnmd06_instituto_educativo.cod_institucion', '{n}.cnmd06_instituto_educativo.denominacion');
    $this->concatena_cuatro_digitos("", 'cod_institucion');
	$id = $_SESSION['infogobierno']['cedula_identidad'];
    	$Tfilas=$this->cnmd06_datos_personales->findCount("cedula_identidad=".$id);
       if($Tfilas!=0){
          $this->cedula($id);
          $this->render("cedula");
       }else{
       	    $this->set('ci',"");
		    $this->set('pa',"");
		    $this->set('sa',"");
		    $this->set('pn',"");
		    $this->set('sn',"");
       }//fin else

}//fin function


function cedula($ci=null){
	$this->layout = "ajax";
	//$ci = $_SESSION['infogobierno']['cedula_identidad'];
	$cond ="cedula_identidad=".$ci;
	$cond2="cedula=".$ci;
	$resul=$this->cnmd06_datos_educativos->findCount($cond2);
	$resul = 0;
	if($resul==0){
			$a = $this->cnmd06_datos_personales->findAll($cond);
		    $pa=$a[0]['cnmd06_datos_personales']['primer_apellido'];
		    $sa=$a[0]['cnmd06_datos_personales']['segundo_apellido'];
		    $pn=$a[0]['cnmd06_datos_personales']['primer_nombre'];
		    $sn=$a[0]['cnmd06_datos_personales']['segundo_nombre'];
		    $this->set('ci',$ci);
		    $this->set('pa',$pa);
		    $this->set('sa',$sa);
		    $this->set('pn',$pn);
		    $this->set('sn',$sn);
		    //$this->set('errorMessage', 'No se encontro informacion para esa cedula');
	}else{
          	 $datos=$this->v_cnmd06_datos_educativos->findAll($cond2,null,'cedula ASC',null,null,null);
          	 $this->set('datos',$datos);
	}//fin else
}//fin function



/*
function consultar($id=null){
	$this->layout = "ajax";
	if($id==null){
 		$id = $_SESSION['infogobierno']['cedula_identidad'];
	}
    $cond2="cedula=".$id;
    $datos=$this->v_cnmd06_datos_educativos->findAll($cond2,null,'cedula ASC',1,null,null);
    $this->set('datos',$datos);
}//fin function

*/

function consultar($pagina=null){
 		$this->layout = "ajax";
	$id = $_SESSION['infogobierno']['cedula_identidad'];
	$Tfilas=$this->cnmd06_datos_personales->findCount("cedula_identidad=".$id);
   	if($Tfilas!=0){
       $cond2="cedula=".$id;
   	}else{
   	    $cond2="";
   	}//fin else

if($pagina!=null){
          	 $pagina=$pagina;
          	  $Tfilas=$this->v_cnmd06_datos_educativos->findCount($cond2);
          	 if($Tfilas==0){
          	 	$this->index();
          		//$this->render("index");
          	 }
          	 if($Tfilas!=0){
          	 $this->set('pag_cant',$pagina.'/'.$Tfilas);
          	 $datos=$this->v_cnmd06_datos_educativos->findAll($cond2,null,'cedula ASC',1,$pagina,null);
          	 $this->set('datos',$datos);
          	 $this->set('siguiente',$pagina+1);
          	 $this->set('anterior',$pagina-1);
             $this->bt_nav($Tfilas,$pagina);
             $this->set('pagina',$pagina);
             }
 }else{
 	         $pagina=1;
          	 $Tfilas=$this->v_cnmd06_datos_educativos->findCount($cond2);
          	 if($Tfilas==0){
          	 	$this->index();
          		//$this->render("index");
          	 }
          	 if($Tfilas!=0){
          	 $this->set('pag_cant',$pagina.'/'.$Tfilas);
          	 $datos=$this->v_cnmd06_datos_educativos->findAll($cond2,null,'cedula ASC',1,$pagina,null);
          	 }
          	 $this->set('datos',$datos);
          	 $this->set('siguiente',$pagina+1);
          	 $this->set('anterior',$pagina-1);
             $this->bt_nav($Tfilas,$pagina);
             $this->set('pagina',$pagina);
	  }//fin else
}//fin function








function codi_nivel($codigo){
	$this->layout = "ajax";
	$a = $this->cnmd06_nivel_educacion->findAll("cod_nivel_educativo=".$codigo,array('cod_nivel_educativo','denominacion'));
    $this->set("a",$a[0]['cnmd06_nivel_educacion']['cod_nivel_educativo']);
}

function deno_nivel($codigo){
	$this->layout = "ajax";
	$b = $this->cnmd06_nivel_educacion->findAll("cod_nivel_educativo=".$codigo,array('cod_nivel_educativo','denominacion'));
	$this->set("b",$b[0]['cnmd06_nivel_educacion']['denominacion']);
}

function codi_institucion($codigo){
	$this->layout = "ajax";
	$a = $this->cnmd06_instituto_educativo->findAll("cod_institucion=".$codigo,array('cod_institucion','denominacion'));
    $this->set("a",$a[0]['cnmd06_instituto_educativo']['cod_institucion']);
}

function deno_institucion($codigo){
	$this->layout = "ajax";
	$b = $this->cnmd06_instituto_educativo->findAll("cod_institucion=".$codigo,array('cod_institucion','denominacion'));
	$this->set("b",$b[0]['cnmd06_instituto_educativo']['denominacion']);
}

function select3($select=null,$var=null) { //select codigos presupuestarios
	$this->layout = "ajax";
	if($var!=null){
    $cond ="";
	switch($select){
		case 'pais':
		  $this->set('SELECT','estados');
		  $this->set('codigo','pais');
		  $this->set('seleccion','');
		  $this->set('n',1);
		  $lista=  $this->cugd01_republica->generateList($cond, 'cod_republica ASC', null, '{n}.cugd01_republica.cod_republica', '{n}.cugd01_republica.denominacion]');
          $this->concatena($lista, 'vector');
		break;
		case 'estados':
		  $this->set('SELECT','municipios');
		  $this->set('codigo','estados');
		  $this->set('seleccion','');
		  $this->set('n',2);
		  $cond .=" cod_republica=".$var;
		  $this->Session->write('republica', $var);
		  $lista=  $this->cugd01_estados->generateList($cond, 'cod_estado ASC', null, '{n}.cugd01_estados.cod_estado', '{n}.cugd01_estados.denominacion');
          $this->concatena($lista, 'vector');
		break;
			case 'municipios':
		  $this->set('SELECT','parroquias');
		  $this->set('codigo','municipios');
		  $this->set('seleccion','');
		  $this->set('n',3);
		  //$esta =  $this->Session->read('esta');
		  $this->Session->write('esta',$var);
		  $republica = $this->Session->read('republica');
		  $cond .=" cod_republica='".$republica."' and ";
		  $cond .=" cod_estado=".$var;
		  $lista=  $this->cugd01_municipios->generateList($cond, 'cod_municipio ASC', null, '{n}.cugd01_municipios.cod_municipio', '{n}.cugd01_municipios.denominacion');
          $this->concatena($lista, 'vector');
		break;
			case 'parroquias':
		  $this->set('SELECT','centros');
		  $this->set('codigo','parroquias');
		  $this->set('seleccion','');
		  $this->set('n',4);
		  $esta =  $this->Session->read('esta');
		  $this->Session->write('muni',$var);
		  $republica = $this->Session->read('republica');
		  $cond .=" cod_republica='".$republica."' and ";
		  $cond .=" cod_estado=".$esta." and cod_municipio=".$var;
		  $lista=  $this->cugd01_parroquias->generateList($cond, 'cod_parroquia ASC', null, '{n}.cugd01_parroquias.cod_parroquia', '{n}.cugd01_parroquias.denominacion');
          $this->concatena($lista, 'vector');
		break;
			case 'centros':
		  $this->set('SELECT','centros2');
		  $this->set('codigo','centros');
		  $this->set('seleccion','');
		  $this->set('n',5);
		  $this->set('no','no');
		 // $ano =  $this->Session->read('ano');
		  $republica = $this->Session->read('republica');
		  $cond .=" cod_republica='".$republica."' and ";
		  $esta =  $this->Session->read('esta');
		  $muni =  $this->Session->read('muni');
		  $this->Session->write('parro',$var);
		  $cond .=" cod_estado=".$esta." and cod_municipio=".$muni." and cod_parroquia=".$var;
		  $lista=  $this->cugd01_centropoblados->generateList($cond, 'cod_centro ASC', null, '{n}.cugd01_centropoblados.cod_centro', '{n}.cugd01_centropoblados.denominacion');
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
    $cond = "";
   // $cond2 = $this->SQLCA();
	switch($select){
		case 'pais':
		 // $ano =  $this->Session->read('ano');
		  $this->Session->write('pais',$var);
		  $cond .="  cod_republica=".$var;
		  $a=  $this->cugd01_republica->findAll($cond);
          $cod= $a[0]['cugd01_republica']['cod_republica'] >9 ?$a[0]['cugd01_republica']['cod_republica'] : "0".$a[0]['cugd01_republica']['cod_republica'] ;
          $this->set('codi',$cod);
		break;
		case 'estados':
		 // $ano =  $this->Session->read('ano');

		  $re = $this->Session->read('pais');
		  $this->Session->write('esta',$var);
		  $cond .="  cod_republica='".$re."'  and cod_estado=".$var;
		  $a=  $this->cugd01_estados->findAll($cond);
          $cod= $a[0]['cugd01_estados']['cod_estado'] >9 ?$a[0]['cugd01_estados']['cod_estado'] : "0".$a[0]['cugd01_estados']['cod_estado'] ;
          $this->set('codi',$cod);
		break;
		case 'municipios':
		  //$ano =  $this->Session->read('ano');
		  $esta =  $this->Session->read('esta');
		  $this->Session->write('muni',$var);
		  $cond .="  cod_estado=".$esta." and cod_municipio=".$var;
		  $a=  $this->cugd01_municipios->findAll($cond);
          $cod=$a[0]['cugd01_municipios']['cod_municipio'] >9 ?$a[0]['cugd01_municipios']['cod_municipio'] : "0".$a[0]['cugd01_municipios']['cod_municipio'] ;
		$this->set('codi',$cod);
		break;
		case 'parroquias':
		  //$ano =  $this->Session->read('ano');
		  $esta =  $this->Session->read('esta');
		  $muni =  $this->Session->read('muni');
		  $this->Session->write('parr',$var);
		  $cond .="  cod_estado=".$esta." and cod_municipio=".$muni." and cod_parroquia=".$var;
		  $a=  $this->cugd01_parroquias->findAll($cond);
          $cod=$a[0]['cugd01_parroquias']['cod_parroquia'] >9 ?$a[0]['cugd01_parroquias']['cod_parroquia'] : "0".$a[0]['cugd01_parroquias']['cod_parroquia'] ;
		$this->set('codi',$cod);
		break;
		case 'centros':
		  //$ano =  $this->Session->read('ano');
		  $esta =  $this->Session->read('esta');
		  $muni =  $this->Session->read('muni');
		  $parr =  $this->Session->read('parr');
		  $this->Session->write('cent',$var);
		  $cond .="  cod_estado=".$esta." and cod_municipio=".$muni." and cod_parroquia=".$parr." and cod_centro=".$var;
		  $a=  $this->cugd01_centropoblados->findAll($cond);
          $cod=$a[0]['cugd01_centropoblados']['cod_centro'] >9 ?$a[0]['cugd01_centropoblados']['cod_centro'] : "0".$a[0]['cugd01_centropoblados']['cod_centro'] ;
		$this->set('codi',$cod);
		break;
		}//fin wsitch
	}else{
		echo "";
	}
}//fin mostrar3 codigos presupuestarios

function mostrar3($select=null,$var=null) { //mostrar3 codigos presupuestarios
	$this->layout = "ajax";
if( $var!=null && !empty($var)){
    $cond = "";
   // $cond2 = $this->SQLCA();
	switch($select){
		case 'pais':
		 // $ano =  $this->Session->read('ano');
		  $this->Session->write('pais',$var);
		  $cond .="  cod_republica=".$var;
		  $a=  $this->cugd01_republica->findAll($cond);
          $den=$a[0]['cugd01_republica']['denominacion'];
          $this->set('deno',$den);
		break;
		case 'estados':
		 // $ano =  $this->Session->read('ano');
		  $re = $this->Session->read('pais');
		  $this->Session->write('esta',$var);
		  $cond .="  cod_republica='".$re."'  and cod_estado=".$var;
		  $a=  $this->cugd01_estados->findAll($cond);
          $den=$a[0]['cugd01_estados']['denominacion'];
          $this->set('deno',$den);
		break;
		case 'municipios':
		  //$ano =  $this->Session->read('ano');
		  $esta =  $this->Session->read('esta');
		  $this->Session->write('muni',$var);
		  $republica = $this->Session->read('pais');
		  $cond .=" cod_republica='".$republica."' and ";
		  $cond .=" cod_estado=".$esta." and cod_municipio=".$var;
		  $a=  $this->cugd01_municipios->findAll($cond);
          $den=$a[0]['cugd01_municipios']['denominacion'];
          $this->set('deno',$den);
		break;
		case 'parroquias':
		  //$ano =  $this->Session->read('ano');
		  $esta =  $this->Session->read('esta');
		  $muni =  $this->Session->read('muni');
		  $this->Session->write('parr',$var);
		  $republica = $this->Session->read('pais');
		  $cond .=" cod_republica='".$republica."' and ";
		  $cond .="  cod_estado=".$esta." and cod_municipio=".$muni." and cod_parroquia=".$var;
		  $a=  $this->cugd01_parroquias->findAll($cond);
          $den=$a[0]['cugd01_parroquias']['denominacion'];
          $this->set('deno',$den);
		break;
		case 'centros':
		  //$ano =  $this->Session->read('ano');
		  $esta =  $this->Session->read('esta');
		  $muni =  $this->Session->read('muni');
		  $parr =  $this->Session->read('parr');
		  $this->Session->write('cent',$var);
		  $republica = $this->Session->read('pais');
		  $cond .=" cod_republica='".$republica."' and ";
		  $cond .="  cod_estado=".$esta." and cod_municipio=".$muni." and cod_parroquia=".$parr." and cod_centro=".$var;
		  $a=  $this->cugd01_centropoblados->findAll($cond);
          $den=$a[0]['cugd01_centropoblados']['denominacion'];
          $this->set('deno',$den);
		break;
		}//fin wsitch
	}else{
		echo "";
	}
}//fin mostrar3 co











function guardar(){
	$this->layout = "ajax";
	//pr($this->data);
	if(!empty($this->data)){
	 $cedula=$this->data['cnmp06_datos_educativos']['cedula'];
	 $cod_nivel=$this->data['cnmp06_datos_educativos']['cod_nivel'];
	 $cod_institucion=$this->data['cnmp06_datos_educativos']['cod_institucion'];
	 $cod_pais=$this->data['cnmp06_datos_educativos']['cod_pais'];
	 $cod_estado=$this->data['cnmp06_datos_educativos']['cod_estados'];
	 $cod_municipio=$this->data['cnmp06_datos_educativos']['cod_municipios'];
	 $cod_parroquia=$this->data['cnmp06_datos_educativos']['cod_parroquias'];
	 $cod_centro=$this->data['cnmp06_datos_educativos']['cod_centros'];
	 $fecha_inicio=$this->data['cnmp06_datos_educativos']['fecha_inicio'];
	 $fecha_fin=$this->data['cnmp06_datos_educativos']['fecha_fin'];
	 $observaciones=$this->data['cnmp06_datos_educativos']['observaciones'];
	 $ss=$this->cnmd06_datos_educativos->findAll(null,array('consecutivo'),'consecutivo DESC',1,1,null);
 		 if($ss==null){
     	$consecutivo=1;
     }else{
     	$consecutivo=$ss[0]["cnmd06_datos_educativos"]["consecutivo"]+1;
     }
	}

	$SQL_INSERT ="INSERT INTO cnmd06_datos_educativos (cedula,cod_nivel_educacion,consecutivo,cod_institucion,cod_republica,cod_estado,cod_municipio,cod_parroquia,cod_centro,fecha_inicio,fecha_culminacion,observaciones)";
	$SQL_INSERT .=" VALUES ($cedula, $cod_nivel,$consecutivo,$cod_institucion,$cod_pais,$cod_estado,$cod_municipio,$cod_parroquia,$cod_centro,'".$fecha_inicio."','".$fecha_fin."', '".$observaciones."')";
	$resp=$this->cnmd06_datos_educativos->execute($SQL_INSERT);
	if($resp>1){
		$this->set('msj', array('Registro Agregado con exito.','exito'));
	  	$this->consultar();
	  	$this->render('consultar');
  	}else if ($resp <= 1){
  		$this->set('msj', array('Disculpe, El Registro no fue creado.','error'));
	  	$this->cedula();
     }//fin else
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







function concatena_cuatro_digitos($vector1=null, $nomVar=null, $extra=null){
	$cod = array();
	if($vector1 != null){
		foreach($vector1 as $x => $y){
			if($extra!=null){
						  if($x<=999 && $x>99){
					    $cod[$x] = $extra.'0'.$x.' - '.$y;
			     	}else if($x<=99 && $x>9){
						$cod[$x] = $extra.'00'.$x.' - '.$y;
				    }else if($x<=9){
						$cod[$x] = $extra.'000'.$x.' - '.$y;
					}else{
						$cod[$x] = $extra.''.$x.' - '.$y;
					}

			}else{
				      if($x<=999 && $x>99){
					$cod[$x] = '0'.$x.' - '.$y;
				}else if($x<=99 && $x>9){
					$cod[$x] = '00'.$x.' - '.$y;
			    }else if($x<=9){
					$cod[$x] = '000'.$x.' - '.$y;
				}else{
					$cod[$x] = ''.$x.' - '.$y;
				}
			}
		}

	}

	$this->set($nomVar, $cod);
}//fin function











function modificar($cedula=null,$consecutivo=null,$cod_nivel_educacion=null,$pagina=null){
	  $this->layout = "ajax";
	  		$this->set('pagina',$pagina);
          	$cond ="cedula=".$cedula." and consecutivo=".$consecutivo." and cod_nivel_educacion=".$cod_nivel_educacion;
          	$datos=$this->v_cnmd06_datos_educativos->findAll($cond);
	       	$listarepublica=$this->cugd01_republica->generateList(null, 'cod_republica ASC', null, '{n}.cugd01_republica.cod_republica', '{n}.cugd01_republica.denominacion');
    		$this->concatena($listarepublica, 'cod_republica');
    		$listanivel=$this->cnmd06_nivel_educacion->generateList(null, 'denominacion ASC', null, '{n}.cnmd06_nivel_educacion.cod_nivel_educativo', '{n}.cnmd06_nivel_educacion.denominacion');
    		$this->concatena($listanivel, 'cod_nivel_educativo');
    		$listainstitucion=$this->cnmd06_instituto_educativo->generateList("cod_institucion=".$datos[0]["v_cnmd06_datos_educativos"]["cod_institucion"], 'denominacion ASC', null, '{n}.cnmd06_instituto_educativo.cod_institucion', '{n}.cnmd06_instituto_educativo.denominacion');
    		$this->concatena_cuatro_digitos($listainstitucion, 'cod_institucion');
          	 $this->set('datos',$datos);


		  $cond =" ";
		  $listarepublica=$this->cugd01_republica->generateList($cond, 'cod_republica ASC', null, '{n}.cugd01_republica.cod_republica', '{n}.cugd01_republica.denominacion');
          $this->concatena($listarepublica, 'lista_cod_republica');

		  $cond .=" cod_republica=".$datos[0]["v_cnmd06_datos_educativos"]["cod_republica"];
		  $listacugd01_estados=$this->cugd01_estados->generateList($cond, 'cod_estado ASC', null, '{n}.cugd01_estados.cod_estado', '{n}.cugd01_estados.denominacion');
          $this->concatena($listacugd01_estados, 'lista_cod_estado');


		  $cond .=" and cod_estado=".$datos[0]["v_cnmd06_datos_educativos"]["cod_estado"];
		  $listacugd01_municipios=$this->cugd01_municipios->generateList($cond, 'cod_municipio ASC', null, '{n}.cugd01_municipios.cod_municipio', '{n}.cugd01_municipios.denominacion');
          $this->concatena($listacugd01_municipios, 'lista_cod_municipio');


		  $cond .=" and cod_municipio=".$datos[0]["v_cnmd06_datos_educativos"]["cod_municipio"];
		  $listacugd01_parroquias=$this->cugd01_parroquias->generateList($cond, 'cod_parroquia ASC', null, '{n}.cugd01_parroquias.cod_parroquia', '{n}.cugd01_parroquias.denominacion');
          $this->concatena($listacugd01_parroquias, 'lista_cod_parroquia');


		  $cond .="  and cod_parroquia=".$datos[0]["v_cnmd06_datos_educativos"]["cod_parroquia"];
		  $listacugd01_centropoblados=$this->cugd01_centropoblados->generateList($cond, 'cod_centro ASC', null, '{n}.cugd01_centropoblados.cod_centro', '{n}.cugd01_centropoblados.denominacion');
          $this->concatena($listacugd01_centropoblados, 'lista_cod_centro');



		  $this->Session->write('pais',$datos[0]["v_cnmd06_datos_educativos"]["cod_republica"]);
		  $this->Session->write('esta',$datos[0]["v_cnmd06_datos_educativos"]["cod_estado"]);
		  $this->Session->write('muni',$datos[0]["v_cnmd06_datos_educativos"]["cod_municipio"]);
		  $this->Session->write('parr',$datos[0]["v_cnmd06_datos_educativos"]["cod_parroquia"]);
		  $this->Session->write('cent',$datos[0]["v_cnmd06_datos_educativos"]["cod_centro"]);






 }//fin function





function guardar_modificar($cedula=null,$consecutivo=null,$cod_nivel_educacion=null,$pagina=null){
	$this->layout = "ajax";
	if(!empty($this->data)){


	 $cod_nivel=$this->data['cnmp06_datos_educativos']['cod_nivel'];
	 $cod_institucion=$this->data['cnmp06_datos_educativos']['cod_institucion'];
	 $fecha_inicio=$this->data['cnmp06_datos_educativos']['fecha_inicio'];
	 $fecha_fin=$this->data['cnmp06_datos_educativos']['fecha_fin'];
	 $observaciones=$this->data['cnmp06_datos_educativos']['observaciones'];


	 $cod_pais           =   $this->data['cnmp06_datos_educativos']['cod_pais'];
	 $cod_estados         =   $this->data['cnmp06_datos_educativos']['cod_estados'];
	 $cod_municipios      =   $this->data['cnmp06_datos_educativos']['cod_municipios'];
	 $cod_parroquias      =   $this->data['cnmp06_datos_educativos']['cod_parroquias'];
	 $cod_centros         =   $this->data['cnmp06_datos_educativos']['cod_centros'];






	 $cond ="cedula=".$cedula." and consecutivo=".$consecutivo." and cod_nivel_educacion=".$cod_nivel_educacion;
	 $sql="update cnmd06_datos_educativos set cod_republica = '".$cod_pais."', cod_estado = '".$cod_estados."', cod_municipio = '".$cod_municipios."', cod_parroquia = '".$cod_parroquias."', cod_centro = '".$cod_centros."', cod_nivel_educacion=$cod_nivel, cod_institucion=$cod_institucion, fecha_inicio='".$fecha_inicio."', fecha_culminacion='".$fecha_fin."', observaciones='".$observaciones."' where ". $cond;
     $vvv=$this->cnmd06_datos_educativos->execute($sql);
     $this->data=null;
     $this->set('msj', array('Registro Modificado con exito.','exito'));

	 $this->consultar($pagina);
     $this->render("consultar");


	}
}//fin guardar_modificar






function expediente(){

		if($this->Session->read('cedula_pestana_expediente')==""){
		         	$id=0;
		}else{
		    	    $id=$this->Session->read('cedula_pestana_expediente');
		}//fin else

		    $y=$this->v_cnmd06_datos_educativos->findCount("cedula=".$id);

				    if($y==0){
				            $this->index();
				    }else{
				            $this->consultar();
				            $this->render("consultar");
				    }//fin else
}//fin function






function infomacion_faltante($var1=null, $var2=null){

$this->layout = "ajax";

$var3 = "";

		switch($var1){
                case "instituto_educativo":{  $this->set('userTable', $this->requestAction('/cnmp06_instituto_educativo/', array('return')));  }break;
		 }//fin

$this->set('opcion',     $var1);
$this->set('capa',       $var2);
$this->set('controlador',$var3);

}//fin function



function select_cambio($var1=null, $var2=null, $var3=null){

$this->layout = "ajax";

	switch($var1){
                case "instituto_educativo":{
                	$listainstitucion=$this->cnmd06_instituto_educativo->generateList(null, 'denominacion ASC', null, '{n}.cnmd06_instituto_educativo.cod_institucion', '{n}.cnmd06_instituto_educativo.denominacion');
	                $this->concatena_cuatro_digitos("", 'lista');
	                $this->set("name", "cod_institucion");
                }break;

		 }//fin

}//fin function





function buscar_pista($var1=null, $var2=null){
$this->layout = "ajax";
        switch($var1){
                 case "instituto_educativo":{
                	$listainstitucion=$this->cnmd06_instituto_educativo->generateList("upper(denominacion) LIKE upper('%$var2%')", 'denominacion ASC', null, '{n}.cnmd06_instituto_educativo.cod_institucion', '{n}.cnmd06_instituto_educativo.denominacion');
	                $this->concatena_cuatro_digitos($listainstitucion, 'lista');
	                $this->set("name", "cod_institucion");
                }break;
		 }//fin
 $this->render("select_cambio");
}//fin function











function eliminar($cedula=null,$consecutivo=null,$cod_nivel_educacion=null){
	$this->layout = "ajax";
	$cond ="cedula=".$cedula." and consecutivo=".$consecutivo." and cod_nivel_educacion=".$cod_nivel_educacion;
		$sql1 ="DELETE  FROM  cnmd06_datos_educativos where ".$cond;
		$this->cnmd06_datos_educativos->execute($sql1);
		$this->set('msj', array('Registro Eliminado con exito.','exito'));
	  	$this->index();
	  //	$this->render('index');
}
//fin eliminar
}