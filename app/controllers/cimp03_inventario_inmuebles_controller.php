<?php
/*
 * Creado el  30/10/2007 a las 12:03:17 PM
 *
 * Herramienta: EasyEclipse.
 *
 * Proyecto: SIGEP
 *
 */
 class Cimp03InventarioInmueblesController extends AppController {
   var $name = 'cimp03_inventario_inmuebles';
   var $uses = array('cugd90_municipio_defecto','cugd10_imagenes','cugd01_vialidad','v_inventario_inmuebles_todo','v_buscar_inmuebles','cugd02_division',
                     'cimd03_inventario_inmuebles','cimd02_tipo_movimiento','cimd01_clasificacion_tipo','cimd01_clasificacion_seccion',
                     'cimd01_clasificacion_subgrupo','cimd01_clasificacion_grupo','cugd01_estados','cugd01_municipios','cugd01_parroquias',
                     'Cugd01_centropoblados','cugd02_dependencia','cugd02_coordinacion','cugd02_departamento','cugd02_direccion',
                     'cugd02_direccionsuperior','cugd02_division','cugd02_oficina','cugd02_institucion','cugd02_secretaria','cugd01_centropoblados',
                     'ccfd04_cierre_mes',

                    'cstd01_entidades_bancarias', 'ccfd10_descripcion', 'ccfd10_detalles', 'ccfd02', 'ccfd05_numero_asiento',
                    'ccfd04_cuentas_enlace', 'cpcd02', 'cepd01_compromiso_cuerpo', 'cscd04_ordencompra_anticipo_cuerpo',
                    'cscd04_ordencompra_encabezado', 'cscd04_ordencompra_autorizacion_cuerpo',
				    'cscd04_ordencompra_encabezado', 'cobd01_contratoobras_anticipo_cuerpo', 'cobd01_contratoobras_valuacion_cuerpo',
				    'cobd01_contratoobras_retencion_cuerpo', 'cepd02_contratoservicio_anticipo_cuerpo', 'cepd02_contratoservicio_valuacion_cuerpo',
				    'cepd02_contratoservicio_retencion_cuerpo','cobd01_contratoobras_cuerpo', 'cepd02_contratoservicio_cuerpo');

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



function concatena($vector1=null, $nomVar=null){

	if($vector1 != null){

		foreach($vector1 as $x => $y){

			$cod[$x] = $this->zero($x).' - '.$y;
		}
		//print_r($cod);

		$this->set($nomVar, $cod);

	}
}//fin concatena


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
     }

function AddCero3($numero,$extra=null){
   	  if($extra==null){
   	  	$numero = ($numero < 10 ? "0".$numero : $numero);
   	  }else{
   	  	$numero = ($numero < 10 ? $extra."0".$numero : $extra.".".$numero);
   	  }
	    return $numero;
   }//fin AddCero

        function SQLCX($ano=null){//sql para busqueda de codigos de arranque con y sin año
         $sql_re = "cod_presi=".$this->verifica_SS(1)."  and    ";
         $sql_re .= "cod_entidad=".$this->verifica_SS(2)."  and  ";
         $sql_re .= "cod_tipo_inst=".$this->verifica_SS(3)."  and ";
         $sql_re .= "cod_inst=".$this->verifica_SS(4)." ";
		 return $sql_re;


    }//fin funcion SQLX


 function index($numero=null){
 	$this->layout ="ajax";
 	  $this->data=null;
 	   	$cod_presi = $this->verifica_SS(1);
	$cod_entidad = $this->verifica_SS(2);
	$cod_tipo_inst = $this->verifica_SS(3);
	$cod_inst = $this->verifica_SS(4);
	$cod_dep = $this->verifica_SS(5);
 	$this->data=null;

 	//$this->set('cod_esta',$cod_entidad);
 	$this->set('cod_depe',$cod_dep);
 	$this->set('cod_inst',$cod_inst);
 	$this->Session->write('cesta',$cod_entidad);
	$this->Session->write('desta',$cod_entidad);
	$this->Session->write('cinst',$cod_inst);
	$this->Session->write('dinst',$cod_inst);
	$this->Session->write('cdepe',$cod_dep);
	$this->Session->write('ddepe',$cod_dep);
	//$a=  $this->cugd01_estados->findAll('cod_estado='.$cod_entidad);
    //$e=$a[0]['cugd01_estados']['denominacion'];
    //$this->set('deno_esta',$e);
 	$this->set('entidad_federal', $this->Session->read('entidad_federal'));
  	$tipo = $this->cimd01_clasificacion_tipo->generateList(null,'cod_tipo ASC', null, '{n}.cimd01_clasificacion_tipo.cod_tipo', '{n}.cimd01_clasificacion_tipo.denominacion');
	$this->concatena($tipo, 'tipo');
	//$estado = $this->cugd01_estados->generateList('cod_republica='.$cod_presi,'cod_estado ASC', null, '{n}.cugd01_estados.cod_estado', '{n}.cugd01_estados.denominacion');
	//$this->concatena($estado, 'estado');
	//$lista1=  $this->cugd01_municipios->generateList('cod_republica='.$cod_presi.' and cod_estado='.$cod_entidad, 'cod_municipio ASC', null, '{n}.cugd01_municipios.cod_municipio', '{n}.cugd01_municipios.denominacion');
    //$this->concatena($lista1,'municipio');
    $aa=  $this->cugd02_institucion->findAll('cod_tipo_institucion='.$cod_tipo_inst.' and cod_institucion='.$cod_inst);
    $ee=$aa[0]['cugd02_institucion']['denominacion'];
    $this->set('deno_inst',$ee);

    $aaa=  $this->cugd02_dependencia->findAll('cod_tipo_institucion='.$cod_tipo_inst.' and cod_institucion='.$cod_inst.'and cod_dependencia='.$cod_dep);
    $eee=$aaa[0]['cugd02_dependencia']['denominacion'];
    $this->set('deno_depe',$eee);

	$institucion = $this->cugd02_institucion->generateList('cod_tipo_institucion='.$cod_tipo_inst.' and cod_institucion='.$cod_inst,'cod_institucion ASC', null, '{n}.cugd02_institucion.cod_institucion', '{n}.cugd02_institucion.denominacion');
	$this->concatena($institucion, 'institucion');

	$incorporacion = $this->cimd02_tipo_movimiento->generateList('cod_tipo_mov= 1','cod_mov ASC', null, '{n}.cimd02_tipo_movimiento.cod_mov', '{n}.cimd02_tipo_movimiento.denominacion');
	$this->concatena($incorporacion, 'incorporacion');

	$cond2 ="cod_tipo_institucion=".$cod_tipo_inst." and cod_institucion=".$cod_inst." and cod_dependencia=".$cod_dep;
	$lista2 = $this->cugd02_direccionsuperior->generateList($cond2, 'cod_dir_superior ASC', null, '{n}.cugd02_direccionsuperior.cod_dir_superior', '{n}.cugd02_direccionsuperior.denominacion');
    $this->concatena($lista2,'dir_superior');

	if($numero==null){
		$ss=$this->cimd03_inventario_inmuebles->findAll($this->SQLCA(),array('numero_identificacion'),'numero_identificacion DESC',1,1,null);
 		 if($ss==null){
     	$new_numero=1;
     }else{
     	$new_numero=$ss[0]["cimd03_inventario_inmuebles"]["numero_identificacion"]+1;
     }
      $this->set('numero',$new_numero);//$numero
	}else{
		$this->set('numero',$numero);
	}


	$lista=  $this->cugd02_dependencia->generateList('cod_tipo_institucion='.$cod_tipo_inst.' and cod_institucion='.$cod_inst, 'cod_dependencia ASC', null, '{n}.cugd02_dependencia.cod_dependencia', '{n}.cugd02_dependencia.denominacion');
    $this->concatena($lista,'dependencia');
    $cond2 ="cod_tipo_institucion=".$cod_tipo_inst." and cod_institucion=".$cod_inst." and cod_dependencia=".$cod_dep;
	$lista2 = $this->cugd02_direccionsuperior->generateList($cond2, 'cod_dir_superior ASC', null, '{n}.cugd02_direccionsuperior.cod_dir_superior', '{n}.cugd02_direccionsuperior.denominacion');
    $this->concatena($lista2,'dir_superior');

	$mpd = $this->cugd90_municipio_defecto->findAll($this->SQLCX().' and cod_dep=1');
	$this->set('mpd',$mpd);
	if($mpd != null){
		$cod_rep_pd = $mpd[0]['cugd90_municipio_defecto']['cod_republica'];
		$cod_est_pd = $mpd[0]['cugd90_municipio_defecto']['cod_estado'];
		$cod_mun_pd = $mpd[0]['cugd90_municipio_defecto']['cod_municipio'];
		$this->set('cod_est_pd',$cod_est_pd);
		$this->set('cod_mun_pd',$cod_mun_pd);
 		$this->Session->write('cesta',$cod_est_pd);
		$this->Session->write('desta',$cod_est_pd);
		$this->Session->write('cmuni',$cod_mun_pd);
		$this->Session->write('dmuni',$cod_mun_pd);

		$estadosl = $this->cugd01_estados->generateList('cod_republica='.$cod_rep_pd,'cod_estado ASC', null, '{n}.cugd01_estados.cod_estado', '{n}.cugd01_estados.denominacion');
		$this->concatena($estadosl, 'estadosl');
		$e=  $this->cugd01_estados->findAll('cod_estado='.$cod_est_pd);
		$esl=$e[0]['cugd01_estados']['denominacion'];
    	$this->set('deno_esta',$esl);

    	$municipiosl = $this->cugd01_municipios->generateList('cod_republica='.$cod_rep_pd.' and cod_estado='.$cod_est_pd,'cod_municipio ASC', null, '{n}.cugd01_municipios.cod_municipio', '{n}.cugd01_municipios.denominacion');
		$this->concatena($municipiosl, 'municipiosl');
		$m=  $this->cugd01_municipios->findAll('cod_republica='.$cod_rep_pd.' and cod_estado='.$cod_est_pd.' and cod_municipio='.$cod_mun_pd);
		$msl=$m[0]['cugd01_municipios']['denominacion'];
    	$this->set('deno_muni',$msl);

    	$parroquiasl = $this->cugd01_parroquias->generateList('cod_republica='.$cod_rep_pd.' and cod_estado='.$cod_est_pd.' and cod_municipio='.$cod_mun_pd,'cod_parroquia ASC', null, '{n}.cugd01_parroquias.cod_parroquia', '{n}.cugd01_parroquias.denominacion');
		$this->concatena($parroquiasl, 'parroquiasl');
		$this->set('parro',true);

	}else if($mpd == null){
		$this->set('cod_est_pd',$cod_entidad);
		$this->set('cod_mun_pd',null);
		$this->set('deno_muni',null);
		$this->set('parro',false);
 		$this->Session->write('cesta',$cod_entidad);
		$this->Session->write('desta',$cod_entidad);
		$estadosl = $this->cugd01_estados->generateList('cod_republica='.$cod_presi,'cod_estado ASC', null, '{n}.cugd01_estados.cod_estado', '{n}.cugd01_estados.denominacion');
		$this->concatena($estadosl, 'estadosl');
		$e=  $this->cugd01_estados->findAll('cod_republica='.$cod_presi.' and cod_estado='.$cod_entidad);
		$esl=$e[0]['cugd01_estados']['denominacion'];
    	$this->set('deno_esta',$esl);
    	$municipiosl = $this->cugd01_municipios->generateList('cod_republica='.$cod_presi.' and cod_estado='.$cod_entidad,'cod_municipio ASC', null, '{n}.cugd01_municipios.cod_municipio', '{n}.cugd01_municipios.denominacion');
		$this->concatena($municipiosl, 'municipiosl');

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


function select4($select=null,$var=null) { //select codigos presupuestarios
	$this->layout = "ajax";
	$cod_presi = $this->verifica_SS(1);
	if($var!=null){
	switch($select){
		case 'estado':
		  $this->set('SELECT','municipio');
		  $this->set('codigo','estado');
		  $this->set('seleccion','');
		  $this->set('n',1);
		  $ano =  $this->Session->read('ano');
		  $cond2 ="ejercicio=".$ano." and cod_grupo=".CE."";
		  $lista=  $this->cugd01_estados->generateList($cond2, 'cod_estado ASC', null, '{n}.cugd01_estados.cod_estado', '{n}.cugd01_estados.denominacion');
			$this->concatena($lista,'vector');
		break;
		case 'municipio':
			echo "<script>";
		  		echo "document.getElementById('cod_seleccion_2').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('cod_seleccion_3').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('cod_seleccion_4').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('de_seleccion_2').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('de_seleccion_3').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('de_seleccion_4').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('cod_seleccion_5').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('de_seleccion_5').innerHTML='<input type=text  size=10% class=inputtext />';  ";
        	echo "</script>";
		  $this->set('SELECT','parroquia');
		  $this->set('codigo','municipio');
		  $this->set('seleccion','');
		  $this->set('n',2);
		  $this->Session->write('cesta',$var);
		  $cond2 ="cod_republica=".$cod_presi." and cod_estado=".$var;
		  $lista=  $this->cugd01_municipios->generateList($cond2, 'cod_municipio ASC', null, '{n}.cugd01_municipios.cod_municipio', '{n}.cugd01_municipios.denominacion');
          $this->concatena($lista,'vector');
 		break;
		case 'parroquia':
			echo "<script>";
		  		echo "document.getElementById('cod_seleccion_3').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('cod_seleccion_4').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('de_seleccion_3').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('de_seleccion_4').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('cod_seleccion_5').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('de_seleccion_5').innerHTML='<input type=text  size=10% class=inputtext />';  ";
        	echo "</script>";
		  $this->set('SELECT','centro');
		  $this->set('codigo','parroquia');
		  $this->set('seleccion','');
		  $this->set('n',3);
		  $cesta =  $this->Session->read('cesta');
		  $this->Session->write('cmuni',$var);
		  $cond2 ="cod_republica=".$cod_presi." and cod_estado=".$cesta." and cod_municipio=".$var;
		  $lista = $this->cugd01_parroquias->generateList($cond2, 'cod_parroquia ASC', null, '{n}.cugd01_parroquias.cod_parroquia', '{n}.cugd01_parroquias.denominacion');
          $this->concatena($lista,'vector');
		break;
		case 'centro':
		//echo 'hola';
			echo "<script>";
		  		echo "document.getElementById('cod_seleccion_4').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('de_seleccion_4').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('cod_seleccion_5').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('de_seleccion_5').innerHTML='<input type=text  size=10% class=inputtext />';  ";
        	echo "</script>";
		  $this->set('SELECT','calle');
		  $this->set('codigo','centro');
		  $this->set('seleccion','');
		  $this->set('n',4);
		  $cesta =  $this->Session->read('cesta');
		  $cmuni =  $this->Session->read('cmuni');
		  $this->Session->write('cparr',$var);
		  $cond2 ="cod_republica=".$cod_presi." and cod_estado=".$cesta." and cod_municipio=".$cmuni." and cod_parroquia=".$var;
		  $lista=  $this->cugd01_centropoblados->generateList($cond2, 'cod_centro ASC', null, '{n}.cugd01_centropoblados.cod_centro', '{n}.cugd01_centropoblados.denominacion');
          $this->concatena($lista,'vector');
		break;
		case 'calle':
			echo "<script>";
		  		echo "document.getElementById('cod_seleccion_5').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('de_seleccion_5').innerHTML='<input type=text  size=10% class=inputtext />';  ";
        	echo "</script>";
		  $this->set('SELECT','calle');
		  $this->set('codigo','calle');
		  $this->set('seleccion','');
		  $this->set('n',5);
		  $cesta =  $this->Session->read('cesta');
		  $cmuni =  $this->Session->read('cmuni');
		  $cparro =  $this->Session->read('cparr');
		  $this->Session->write('ccent',$var);
		  $cond2 ="cod_republica=".$cod_presi." and cod_estado=".$cesta." and cod_municipio=".$cmuni." and cod_parroquia=".$cparro." and cod_centro=".$var;
		  $lista=  $this->cugd01_vialidad->generateList($cond2, 'cod_vialidad ASC', null, '{n}.cugd01_vialidad.cod_vialidad', '{n}.cugd01_vialidad.denominacion');
          if($lista!=null){
          $this->concatena($lista,'vector');
          }else{
          	$this->set('vector',array('0'=>'00'));
          };
		break;
	}//fin wsitch
	}else{
		  $this->set('SELECT','');
		  $this->set('codigo','');
		  $this->set('seleccion','');
		  $this->set('n',20);
		  $this->set('no','no');
		 $this->set('vector','');
	}
}//fin select codigos presupuestarios



function mostrar5($select=null,$var=null) { //mostrar3 codigos presupuestarios
	$this->layout = "ajax";
	$cod_presi = $this->verifica_SS(1);
		if( $var!=null){
	switch($select){
		case 'estado':
		  //$ano =  $this->Session->read('ano');
		  $this->Session->write('desta',$var);
		  $cond2 ="cod_republica=".$cod_presi." and cod_estado=".$var;
		  $a=  $this->cugd01_estados->findAll($cond2);
          $e=$a[0]['cugd01_estados']['denominacion'];
         $this->set('var',$e);
		break;
		case 'municipio':
		  //$ano =  $this->Session->read('ano');
		  $desta=  $this->Session->read('desta');
		  $this->Session->write('dmuni',$var);
		  $cond2 ="cod_republica=".$cod_presi." and cod_estado=".$desta." and cod_municipio=".$var;
		  $a=  $this->cugd01_municipios->findAll($cond2);
          $e=$a[0]['cugd01_municipios']['denominacion'];
          $this->set('var',$e);
		break;
		case 'parroquia':
		  // $ano =  $this->Session->read('ano');
		  $desta=  $this->Session->read('desta');
		  $dmuni =  $this->Session->read('dmuni');
		  $this->Session->write('dparr',$var);
		  $cond2 ="cod_republica=".$cod_presi." and cod_estado=".$desta." and cod_municipio=".$dmuni." and cod_parroquia=".$var;
		  //echo $cond2;
		  $a=  $this->cugd01_parroquias->findAll($cond2);
		  //print_r($a);
          $e= $a[0]['cugd01_parroquias']['denominacion'];
          $this->set('var',$e);
		break;
		case 'centro':
		  //$ano =  $this->Session->read('ano');
		  $desta=  $this->Session->read('desta');
		  $dmuni =  $this->Session->read('dmuni');
		  $dparr =  $this->Session->read('dparr');
		  $this->Session->write('dcent',$var);
		  $cond2 ="cod_republica=".$cod_presi." and cod_estado=".$desta." and cod_municipio=".$dmuni." and cod_parroquia=".$dparr." and cod_centro=".$var;
		  $a=  $this->cugd01_centropoblados->findAll($cond2);
		  if($a!=null){
          $e=$a[0]['cugd01_centropoblados']['denominacion'];
          $this->set('var',$e);
          }else{
          	$this->set('var','N/A');
          }
		break;
		case 'calle':
		  //$ano =  $this->Session->read('ano');
		  $desta=  $this->Session->read('desta');
		  $dmuni =  $this->Session->read('dmuni');
		  $dparr =  $this->Session->read('dparr');
		  $dcent =  $this->Session->read('dcent');
		  $cond2 ="cod_republica=".$cod_presi." and cod_estado=".$desta." and cod_municipio=".$dmuni." and cod_parroquia=".$dparr." and cod_centro=".$dcent." and cod_vialidad=".$var;
		  $a=  $this->cugd01_vialidad->findAll($cond2);
		  if($a!=null){
          $e=$a[0]['cugd01_vialidad']['denominacion'];
          $this->set('var',$e);
          }else{
          	$this->set('var','N/A');
          }
		break;
	}//fin wsitch
	}else{
		echo "";
	}
}//fin mostrar3 codigos presupuestarios

function mostrar6($select=null,$var=null) { //mostrar3 codigos presupuestarios
	$this->layout = "ajax";
		if( $var!=null){
	switch($select){
		case 'estado':
          	echo "<input type='text' name='data[cimp03_inventario_inmuebles][cod_estado]' value='".$this->AddCero3($var)."' id='editar1'  class='inputtext' readonly=readonly style='text-align:center'/>";
		break;
		case 'municipio':
			 echo "<input type='text' name='data[cimp03_inventario_inmuebles][cod_municipio]' value='".$this->AddCero3($var)."' id='editar2'  class='inputtext' readonly=readonly style='text-align:center'/>";
		break;

		case 'parroquia':
           echo "<input type='text' name='data[cimp03_inventario_inmuebles][cod_parroquia]' value='".$this->AddCero3($var)."' id='editar3' class='inputtext' readonly=readonly style='text-align:center'/>";
		break;

		case 'centro':
           echo "<input type='text' name='data[cimp03_inventario_inmuebles][cod_centro]' value='".$this->AddCero3($var)."' class='inputtext' readonly=readonly  style='text-align:center'/>";
		break;
		case 'calle':
           echo "<input type='text' name='data[cimp03_inventario_inmuebles][cod_calle]' value='".$this->AddCero3($var)."' class='inputtext' readonly=readonly style='text-align:center'/>";
		break;

	}//fin wsitch
	}else{
		echo "";
	}
}//fin mostrar3 codigos presupuestarios

function select5($select=null,$var=null) { //select codigos presupuestarios
	$this->layout = "ajax";
	//echo "si llego";
	if($var!=null){
	switch($select){
		case 'institucion':
		  $this->set('SELECT','dependencia');
		  $this->set('codigo','institucion');
		  $this->set('seleccion','');
		  $this->set('n',5);
		  $ano =  $this->Session->read('ano');
		  $cond2 ="ejercicio=".$ano." and cod_grupo=".CE."";
		  $lista=  $this->Cugd02_dependencia->generateList($cond2, 'cod_institucion ASC', null, '{n}.Cugd02_dependencia.cod_institucion', '{n}.Cugd02_dependencia.denominacion');
			$this->concatena($lista,'vector');
		break;
		case 'dependencia':
		//echo "si generica";
		  $this->set('SELECT','direccions');
		  $this->set('codigo','dependencia');
		  $this->set('seleccion','');
		  $this->set('n',6);
		  $this->Session->write('cinst',$var);
		  $cond2 ="cod_institucion=".$var;
		  $lista=  $this->cugd02_dependencia->generateList($cond2, 'cod_dependencia ASC', null, '{n}.cugd02_dependencia.cod_dependencia', '{n}.cugd02_dependencia.denominacion');
          $this->concatena($lista,'vector');
 		break;
		case 'direccions':
		//echo"si especifica";
		  $this->set('SELECT','coordinacion');
		  $this->set('codigo','direccions');
		  $this->set('seleccion','');
		  $this->set('n',7);
		  $cinst =  $this->Session->read('cinst');
		  $this->Session->write('cdepe',$var);
		  $cond2 ="cod_institucion=".$cinst." and cod_dependencia=".$var;
		 //echo $cond2;
		  $lista = $this->cugd02_direccionsuperior->generateList($cond2, 'cod_dir_superior ASC', null, '{n}.cugd02_direccionsuperior.cod_dir_superior', '{n}.cugd02_direccionsuperior.denominacion');
          $this->concatena($lista,'vector');
		break;
		case 'coordinacion':
		  $this->set('SELECT','secretaria');
		  $this->set('codigo','coordinacion');
		  $this->set('seleccion','');
		  $this->set('n',8);
		  $cinst =  $this->Session->read('cinst');
		  $cdepe =  $this->Session->read('cdepe');
		  $this->Session->write('cdirs',$var);
		  $cond2 ="cod_institucion=".$cinst." and cod_dependencia=".$cdepe." and cod_dir_superior=".$var;
		  $lista=  $this->cugd02_coordinacion->generateList($cond2, 'cod_coordinacion ASC', null, '{n}.cugd02_coordinacion.cod_coordinacion', '{n}.cugd02_coordinacion.denominacion');
          $this->concatena($lista,'vector');
		break;
		case 'secretaria':
		  $this->set('SELECT','direccion');
		  $this->set('codigo','secretaria');
		  $this->set('seleccion','');
		  $this->set('n',9);
		  $cinst =  $this->Session->read('cinst');
		  $cdepe =  $this->Session->read('cdepe');
		  $cdirs =  $this->Session->read('cdirs');
		  $this->Session->write('ccoor',$var);
		  $cond2 ="cod_institucion=".$cinst." and cod_dependencia=".$cdepe." and cod_dir_superior=".$cdirs." and cod_coordinacion=".$var;
		  $lista=  $this->cugd02_secretaria->generateList($cond2, 'cod_secretaria ASC', null, '{n}.cugd02_secretaria.cod_secretaria', '{n}.cugd02_secretaria.denominacion');
			$this->concatena($lista,'vector');
		break;
		case 'direccion':
		  $this->set('SELECT','division');
		  $this->set('codigo','direccion');
		  $this->set('seleccion','');
		  $this->set('n',10);
		  $cinst =  $this->Session->read('cinst');
		  $cdepe =  $this->Session->read('cdepe');
		  $cdirs =  $this->Session->read('cdirs');
		  $ccoor =  $this->Session->read('ccoor');
		  $this->Session->write('csecr',$var);
		  $cond2 ="cod_institucion=".$cinst." and cod_dependencia=".$cdepe." and cod_dir_superior=".$cdirs." and cod_coordinacion=".$ccoor." and cod_secretaria=".$var;
		  $lista=  $this->cugd02_direccion->generateList($cond2, 'cod_direccion ASC', null, '{n}.cugd02_direccion.cod_direccion', '{n}.cugd02_direccion.denominacion');
          $this->concatena($lista,'vector');
 		break;
 		case 'division':
		  $this->set('SELECT','departamento');
		  $this->set('codigo','division');
		  $this->set('seleccion','');
		  $this->set('n',11);
		 // $ano =  $this->Session->read('ano');
		  $cinst =  $this->Session->read('cinst');
		  $cdepe =  $this->Session->read('cdepe');
		  $cdirs =  $this->Session->read('cdirs');
		  $ccoor =  $this->Session->read('ccoor');
		  $csecr =  $this->Session->read('csecr');
		  $this->Session->write('cdire',$var);
		  $cond2 ="cod_institucion=".$cinst." and cod_dependencia=".$cdepe." and cod_dir_superior=".$cdirs." and cod_coordinacion=".$ccoor." and cod_secretaria=".$csecr." and cod_direccion=".$var;
		  $lista=  $this->cugd02_division->generateList($cond2, 'cod_division ASC', null, '{n}.cugd02_division.cod_division', '{n}.cugd02_division.denominacion');
          $this->concatena($lista,'vector');
 		break;
		case 'departamento':
		//echo"si especifica";
		  $this->set('SELECT','oficina');
		  $this->set('codigo','departamento');
		  $this->set('seleccion','');
		  $this->set('n',12);
		  $cinst =  $this->Session->read('cinst');
		  $cdepe =  $this->Session->read('cdepe');
		  $cdirs =  $this->Session->read('cdirs');
		  $ccoor =  $this->Session->read('ccoor');
		  $csecr =  $this->Session->read('csecr');
		  $cdire =  $this->Session->read('cdire');
		  $this->Session->write('cdivi',$var);
		  $cond2 ="cod_institucion=".$cinst." and cod_dependencia=".$cdepe." and cod_dir_superior=".$cdirs." and cod_coordinacion=".$ccoor." and cod_secretaria=".$csecr." and cod_direccion=".$cdire." and cod_division=".$var;
		  $lista = $this->cugd02_departamento->generateList($cond2, 'cod_departamento ASC', null, '{n}.cugd02_departamento.cod_departamento', '{n}.cugd02_departamento.denominacion');
          $this->concatena($lista,'vector');
		break;
		case 'oficina':
		  $this->set('SELECT','oficina');
		  $this->set('codigo','oficina');
		  $this->set('seleccion','');
		  $this->set('n',13);
		  $cinst =  $this->Session->read('cinst');
		  $cdepe =  $this->Session->read('cdepe');
		  $cdirs =  $this->Session->read('cdirs');
		  $ccoor =  $this->Session->read('ccoor');
		  $csecr =  $this->Session->read('csecr');
		  $cdire =  $this->Session->read('cdire');
		  $cdivi =  $this->Session->read('cdivi');
		  $this->Session->write('cdepa',$var);
		  $cond2 ="cod_institucion=".$cinst." and cod_dependencia=".$cdepe." and cod_dir_superior=".$cdirs." and cod_coordinacion=".$ccoor." and cod_secretaria=".$csecr." and cod_direccion=".$cdire." and cod_division=".$cdivi." and cod_departamento=".$var;
		  $lista=  $this->cugd02_oficina->generateList($cond2, 'cod_oficina ASC', null, '{n}.cugd02_oficina.cod_oficina', '{n}.cugd02_oficina.denominacion');
          $this->concatena($lista,'vector');
		break;
	}//fin wsitch
	}else{
		  $this->set('SELECT','');
		  $this->set('codigo','');
		  $this->set('seleccion','');
		  $this->set('n',21);
		  $this->set('no','no');
		 $this->set('vector','');
	}
}//fin select codigos presupuestarios


function mostrar7($select=null,$var=null) { //mostrar3 codigos presupuestarios
	$this->layout = "ajax";
		if( $var!=null){
	switch($select){
		case 'institucion':
		  //$ano =  $this->Session->read('ano');
		  $this->Session->write('dinst',$var);
		   $cond2 ="cod_institucion=".$var;
		  $a=  $this->cugd02_dependencia->findAll($cond2);
          $e=$a[0]['cugd02_dependencia']['denominacion'];
         $this->set('var',$e);
		break;
		case 'dependencia':
		  //$ano =  $this->Session->read('ano');
		  $dinst=  $this->Session->read('dinst');
		  $this->Session->write('ddepe',$var);
		  $cond2 ="cod_institucion=".$dinst." and cod_dependencia=".$var;
		  $a=  $this->cugd02_dependencia->findAll($cond2);
          $e=$a[0]['cugd02_dependencia']['denominacion'];
          $this->set('var',$e);
		break;
		case 'direccions':
		  // $ano =  $this->Session->read('ano');
		  $dinst=  $this->Session->read('dinst');
		  $ddepe =  $this->Session->read('ddepe');
		  $this->Session->write('ddirs',$var);
		  $cond2 ="cod_institucion=".$dinst." and cod_dependencia=".$ddepe." and cod_dir_superior=".$var;
		  //echo $cond2;
		  $a=  $this->cugd02_direccionsuperior->findAll($cond2);
		  //print_r($a);
          $e= $a[0]['cugd02_direccionsuperior']['denominacion'];
          $this->set('var',$e);
		break;
		case 'coordinacion':
		  //$ano =  $this->Session->read('ano');
		  $dinst=  $this->Session->read('dinst');
		  $ddepe =  $this->Session->read('ddepe');
		  $ddirs =  $this->Session->read('ddirs');
		  $this->Session->write('dcoor',$var);
		   $cond2 ="cod_institucion=".$dinst." and cod_dependencia=".$ddepe." and cod_dir_superior=".$ddirs." and cod_coordinacion=".$var;
		  $a=  $this->cugd02_coordinacion->findAll($cond2);
          $e= $a[0]['cugd02_coordinacion']['denominacion'];
           $this->set('var',$e);
		break;
		case 'secretaria':
		  //$ano =  $this->Session->read('ano');
		  $dinst=  $this->Session->read('dinst');
		  $ddepe =  $this->Session->read('ddepe');
		  $ddirs =  $this->Session->read('ddirs');
		  $dcoor =  $this->Session->read('dcoor');
		  $this->Session->write('dsecr',$var);
		   $cond2 ="cod_institucion=".$dinst." and cod_dependencia=".$ddepe." and cod_dir_superior=".$ddirs." and cod_coordinacion=".$dcoor." and cod_secretaria=".$var;
		  $a=  $this->cugd02_secretaria->findAll($cond2);
          $e=$a[0]['cugd02_secretaria']['denominacion'];
         $this->set('var',$e);
		break;
		case 'direccion':
		  //$ano =  $this->Session->read('ano');
		  $dinst=  $this->Session->read('dinst');
		  $ddepe =  $this->Session->read('ddepe');
		  $ddirs =  $this->Session->read('ddirs');
		  $dcoor =  $this->Session->read('dcoor');
		  $dsecr =  $this->Session->read('dsecr');
		  $this->Session->write('ddire',$var);
		  $cond2 ="cod_institucion=".$dinst." and cod_dependencia=".$ddepe." and cod_dir_superior=".$ddirs." and cod_coordinacion=".$dcoor." and cod_secretaria=".$dsecr." and cod_direccion=".$var;
		  $a=  $this->cugd02_direccion->findAll($cond2);
          $e=$a[0]['cugd02_direccion']['denominacion'];
          $this->set('var',$e);
		break;
		case 'division':
		  //$ano =  $this->Session->read('ano');
		  $dinst=  $this->Session->read('dinst');
		  $ddepe =  $this->Session->read('ddepe');
		  $ddirs =  $this->Session->read('ddirs');
		  $dcoor =  $this->Session->read('dcoor');
		  $dsecr =  $this->Session->read('dsecr');
		  $ddire =  $this->Session->read('ddire');
		  $this->Session->write('ddivi',$var);
		  $cond2 ="cod_institucion=".$dinst." and cod_dependencia=".$ddepe." and cod_dir_superior=".$ddirs." and cod_coordinacion=".$dcoor." and cod_secretaria=".$dsecr." and cod_direccion=".$ddire." and cod_division=".$var;
		  $a=  $this->cugd02_division->findAll($cond2);
          $e=$a[0]['cugd02_division']['denominacion'];
          $this->set('var',$e);
		break;
		case 'departamento':
		  // $ano =  $this->Session->read('ano');
		  $dinst=   $this->Session->read('dinst');
		  $ddepe =  $this->Session->read('ddepe');
		  $ddirs =  $this->Session->read('ddirs');
		  $dcoor =  $this->Session->read('dcoor');
		  $dsecr =  $this->Session->read('dsecr');
		  $ddire =  $this->Session->read('ddire');
		  $this->Session->write('ddepa',$var);
		  $cond2 ="cod_institucion=".$dinst." and cod_dependencia=".$ddepe." and cod_dir_superior=".$ddirs." and cod_coordinacion=".$dcoor." and cod_secretaria=".$dsecr." and cod_direccion=".$ddire." and cod_departamento=".$var;
		  //echo $cond2;
		  $a=  $this->cugd02_departamento->findAll($cond2);
		  //print_r($a);
          $e= $a[0]['cugd02_departamento']['denominacion'];
          $this->set('var',$e);
		break;
		case 'oficina':
		  //$ano =  $this->Session->read('ano');
		  $dinst=   $this->Session->read('dinst');
		  $ddepe =  $this->Session->read('ddepe');
		  $ddirs =  $this->Session->read('ddirs');
		  $dcoor =  $this->Session->read('dcoor');
		  $dsecr =  $this->Session->read('dsecr');
		  $ddire =  $this->Session->read('ddire');
		  $ddepa =  $this->Session->read('ddepa');
		  $this->Session->write('dofic',$var);
		  $cond2 ="cod_institucion=".$dinst." and cod_dependencia=".$ddepe." and cod_dir_superior=".$ddirs." and cod_coordinacion=".$dcoor." and cod_secretaria=".$dsecr." and cod_direccion=".$ddire." and cod_departamento=".$ddepa." and cod_oficina=".$var;
		  $a=  $this->cugd02_oficina->findAll($cond2);
          $e= $a[0]['cugd02_oficina']['denominacion'];
           $this->set('var',$e);
		break;
	}//fin wsitch
	}else{
		echo "";
	}
}//fin mostrar3 codigos presupuestarios

function mostrar8($select=null,$var=null) { //mostrar3 codigos presupuestarios
	$this->layout = "ajax";
	//echo "mostrar";
		if( $var!=null){
	switch($select){
		case 'institucion':
          	echo "<input type='text' name='data[cimp03_inventario_inmuebles][cod_institucion]' value='".$this->AddCero3($var)."' id='editar1'  class='inputtext' readonly=readonly/>";
		break;

		case 'dependencia':
			 echo "<input type='text' name='data[cimp03_inventario_inmuebles][cod_dependencia]' value='".$this->AddCero3($var)."' id='editar2'  class='inputtext' readonly=readonly/>";
		break;

		case 'direccions':
           echo "<input type='text' name='data[cimp03_inventario_inmuebles][cod_direccions]' value='".$this->AddCero3($var)."' id='editar3' class='inputtext' readonly=readonly/>";
		break;

		case 'coordinacion':
           echo "<input type='text' name='data[cimp03_inventario_inmuebles][cod_coordinacion]' value='".$this->AddCero3($var)."' class='inputtext' readonly=readonly/>";
		break;
		case 'secretaria':
          	echo "<input type='text' name='data[cimp03_inventario_inmuebles][cod_secretaria]' value='".$this->AddCero3($var)."' id='editar1'  class='inputtext' readonly=readonly/>";
		break;

		case 'direccion':
			 echo "<input type='text' name='data[cimp03_inventario_inmuebles][cod_direccion]' value='".$this->AddCero3($var)."' id='editar2'  class='inputtext' readonly=readonly/>";
		break;

		case 'division':
			 echo "<input type='text' name='data[cimp03_inventario_inmuebles][cod_division]' value='".$this->AddCero3($var)."' id='editar2'  class='inputtext' readonly=readonly/>";
		break;

		case 'departamento':
           echo "<input type='text' name='data[cimp03_inventario_inmuebles][cod_departamento]' value='".$this->AddCero3($var)."' id='editar3' class='inputtext' readonly=readonly/>";
		break;

		case 'oficina':
           echo "<input type='text' name='data[cimp03_inventario_inmuebles][cod_oficina]' value='".$this->AddCero3($var)."' class='inputtext' readonly=readonly/>";
		break;

	}//fin wsitch
	}else{
		echo "";
	}
}//fin mostrar3 codigos presupuestarios




function codigo_incorporacion($codigo){
	$this->layout = "ajax";
	$a = $this->cimd02_tipo_movimiento->findAll("cod_mov=".$codigo,array('cod_mov','denominacion'));
    $this->set("a",$a[0]['cimd02_tipo_movimiento']['cod_mov']);
}//fin cpcp02_codigo

function denominacion_incorporacion($codigo){
	$this->layout = "ajax";
	if(in_array($codigo, array('2','7','11'))){
		echo "<script>";
			echo "document.getElementById('numero_doc').disabled='';   ";
		echo "</script>";
	}else{
		echo "<script>";
			echo "document.getElementById('numero_doc').disabled='true';   ";
		echo "</script>";
	}
	$b = $this->cimd02_tipo_movimiento->findAll("cod_mov=".$codigo,array('cod_mov','denominacion'));
	$this->set("b",$b[0]['cimd02_tipo_movimiento']['denominacion']);


}//fin cpcp02_denominacion


function buscar_pista ($pagina=null)
{
	$this->layout="ajax";
	if($pagina!=null){
		$pagina=$pagina;
	}else{
		$pagina=1;
	}
	if(isset($this->data['cimp03_inventario_inmuebles']['pista']) && !empty($this->data["cimp03_inventario_inmuebles2"]['pista'])){
         $this->data['cimp03_inventario_inmuebles']['pista']=$this->data['cimp03_inventario_inmuebles2']['pista'];
         $otra="si";
	}else{
	  $otra="no";
	}
	if((isset($this->data["cimp03_inventario_inmuebles"]['pista']) && !empty($this->data['cimp03_inventario_inmuebles']['pista'])) || $otra=="si"){
         $pista=strtoupper($this->data['cimp03_inventario_inmuebles']['pista']);
         $cantidad_resultado=$this->v_buscar_muebles->findCount("deno_seccion like '%".$pista."%'");
         $resultado=$this->v_buscar_muebles->findAll("deno_seccion like '%".$pista."%'",null,null,1,$pagina);
         if($cantidad_resultado!=0){
           $this->set("cantidad_resultado",$cantidad_resultado);
           $this->set("resultado",$resultado);
           $this->set("pista",$pista);

           $this->set('siguiente',$pagina+1);
		   $this->set('actual',$pagina);
		   $this->bt_nav($cantidad_resultado,$pagina);
         }else{
           $this->set("cantidad_resultado",$cantidad_resultado);
           $this->set("resultado",array(0=>array("v_buscar_muebles"=>array("cod_grupo"=>0,"cod_partida"=>0,"cod_generica"=>0,"cod_especifica"=>0,"cod_sub_espec"=>0,"cod_auxiliar"=>0,"concepto"=>"","denominacion"=>"No se encontraron datos para la pista indicada, ".$pista))));
           //$this->set("ano",$ano);
           $this->set("pista",$pista);
           $this->set('siguiente',$pagina+1);
		   $this->set('actual',$pagina);
           $this->bt_nav(1,1);
         }
         $this->set("MUESTRAME","");


	}else{
		if(!isset($this->data["cimp03_inventario_inmuebles"]["pista"])){
			echo "<h4>Faltan Datos para las busqueda, por favor indique pista.</h4>";
		}
	}

}//fin buscar_pista

 function radio($var2){
 	$this->layout ="ajax";
 	$this->set('mensaje', 'POR FAVOR INGRESE EL CODIGO');
	$this->set('datos', array());
	$this->set('tipo', array());

 	if($var2==1){
 		 $ss=$this->cimd03_inventario_inmuebles->findAll($this->SQLCA(),array('numero_identificacion'),'numero_identificacion DESC',1,1,null);
 		 if($ss==null){
     	$new_numero=1;
     }else{
     	$new_numero=$ss[0]["cimd03_inventario_inmuebles"]["numero_identificacion"]+1;
     }
     $this->set('numero',$new_numero);//$numero
 	}
 	if($var2==2){
 	  $this->set('numero',"");
 	}
 }










function consulta($pagina=null){
 		$this->layout = "ajax";
         $veri=$this->v_inventario_inmuebles_todo->findCount($this->SQLCA());
         if($veri!=0)
{
         if($pagina!=null){
          	 $pagina=$pagina;
          	 $this->set('pagina',$pagina);
          	  $Tfilas=$this->v_inventario_inmuebles_todo->findCount($this->SQLCA());
          	 if($Tfilas==0){
          	 	$this->index();
          		$this->render("index");
          	 }
          	 if($Tfilas!=0){
          	 $this->set('pag_cant',$pagina.'/'.$Tfilas);
          	 $datacpcp01=$this->v_inventario_inmuebles_todo->findAll($this->SQLCA(),null,'numero_identificacion ASC',1,$pagina,null);
          	// $cd01=$this->cpcd01->findAll();
          	 $this->set('datos',$datacpcp01);
          	 //print_r($datacpcp01);
          	 $this->set('siguiente',$pagina+1);
          	 $this->set('anterior',$pagina-1);
             $this->bt_nav($Tfilas,$pagina);
             }
 }else{
 	$pagina=1;
 	$this->set('pagina',$pagina);
          	 $Tfilas=$this->v_inventario_inmuebles_todo->findCount($this->SQLCA());
          	 if($Tfilas==0){
          	 	$this->index();
          		$this->render("index");
          	 }
          	 if($Tfilas!=0){
          	 $this->set('pag_cant',$pagina.'/'.$Tfilas);
          	 $datacpcp01=$this->v_inventario_inmuebles_todo->findAll($this->SQLCA(),null,'numero_identificacion ASC',1,$pagina,null);
          	 $this->set('datos',$datacpcp01);
         // 	 print_r($datacpcp01);
          	 $this->set('siguiente',$pagina+1);
          	 $this->set('anterior',$pagina-1);
             $this->bt_nav($Tfilas,$pagina);
			 }
        }

}else{
	$this->set('errorMessage', 'No se encontrar&oacute;n datos');
	$this->index();
    $this->render("index");
}
}//fin function consultar2

function modificar($numero_identificacion,$pagina=null){
	$this->layout = "ajax";
	$cod_presi = $this->verifica_SS(1);
$cod_entidad = $this->verifica_SS(2);
$cod_tipo_inst = $this->verifica_SS(3);
$cod_inst = $this->verifica_SS(4);
$cod_dep = $this->verifica_SS(5);
$this->set('verif',$cod_dep);
	$datos=$this->v_inventario_inmuebles_todo->findAll($this->SQLCA().' and numero_identificacion='.$numero_identificacion);
    $this->set('datos',$datos);
    $this->set('pagina',$pagina);
	$vec=$this->cugd10_imagenes->findCount($this->SQLCA()." and cod_campo=18 and identificacion='".$numero_identificacion."'");
		    if($vec!=0){
		    	$this->set('existe_imagen',true);
		    }else{
		    	$this->set('existe_imagen',false);
		    }
    foreach($datos as $row){


  $cod_estado=$row['v_inventario_inmuebles_todo']['cod_estado'];
  $cod_municipio=$row['v_inventario_inmuebles_todo']['cod_municipio'];
  $cod_parroquia=$row['v_inventario_inmuebles_todo']['cod_parroquia'];
  $cod_centro=$row['v_inventario_inmuebles_todo']['cod_centro'];
  $cod_calle=$row['v_inventario_inmuebles_todo']['cod_vialidad'];

  $cod_institucion=$row['v_inventario_inmuebles_todo']['cod_institucion'];
  $cod_dependencia=$row['v_inventario_inmuebles_todo']['cod_dependencia'];
  $cod_dir_superior=$row['v_inventario_inmuebles_todo']['cod_dir_superior'];
  $cod_coordinacion=$row['v_inventario_inmuebles_todo']['cod_coordinacion'];
  $cod_secretaria=$row['v_inventario_inmuebles_todo']['cod_secretaria'];
  $cod_direccion=$row['v_inventario_inmuebles_todo']['cod_direccion'];
  $cod_division=$row['v_inventario_inmuebles_todo']['cod_division'];
  $cod_departamento=$row['v_inventario_inmuebles_todo']['cod_departamento'];
  $cod_oficina=$row['v_inventario_inmuebles_todo']['cod_oficina'];
    }

    $estado = $this->cugd01_estados->generateList(null,'cod_estado ASC', null, '{n}.cugd01_estados.cod_estado', '{n}.cugd01_estados.denominacion');
	$this->concatena($estado, 'estado');
	$municipio = $this->cugd01_municipios->generateList('cod_estado='.$cod_estado,'cod_municipio ASC', null, '{n}.cugd01_municipios.cod_municipio', '{n}.cugd01_municipios.denominacion');
	$this->concatena($municipio, 'municipio');
	$parroquia = $this->cugd01_parroquias->generateList('cod_estado='.$cod_estado.' and cod_municipio='.$cod_municipio,'cod_parroquia ASC', null, '{n}.cugd01_parroquias.cod_parroquia', '{n}.cugd01_parroquias.denominacion');
	$this->concatena($parroquia, 'parroquia');
	$centro = $this->cugd01_centropoblados->generateList('cod_estado='.$cod_estado.' and cod_municipio='.$cod_municipio.' and cod_parroquia='.$cod_parroquia,'cod_centro ASC', null, '{n}.cugd01_centropoblados.cod_centro', '{n}.cugd01_centropoblados.denominacion');
	$this->concatena($centro, 'centro');
	$calle = $this->cugd01_vialidad->generateList('cod_estado='.$cod_estado.' and cod_municipio='.$cod_municipio.' and cod_parroquia='.$cod_parroquia.' and cod_centro='.$cod_centro,'cod_vialidad ASC', null, '{n}.cugd01_vialidad.cod_vialidad', '{n}.cugd01_vialidad.denominacion');
	//$this->concatena($calle, 'calle');
	if($calle!=null){
          $this->concatena($calle,'calle');
          }else{
          	$this->set('calle',array('0'=>'00'));
          };

    $institucion = $this->cugd02_institucion->generateList('cod_tipo_institucion='.$cod_tipo_inst.' and cod_institucion='.$cod_institucion,'cod_institucion ASC', null, '{n}.cugd02_institucion.cod_institucion', '{n}.cugd02_institucion.denominacion');
	$this->concatena($institucion, 'institucion');
	$dependencia = $this->cugd02_dependencia->generateList('cod_tipo_institucion='.$cod_tipo_inst.' and cod_institucion='.$cod_institucion,'cod_dependencia ASC', null, '{n}.cugd02_dependencia.cod_dependencia', '{n}.cugd02_dependencia.denominacion');
	$this->concatena($dependencia, 'dependencia');
	$dir_superior = $this->cugd02_direccionsuperior->generateList('cod_tipo_institucion='.$cod_tipo_inst.' and cod_institucion='.$cod_institucion.' and cod_dependencia='.$cod_dependencia,'cod_dir_superior ASC', null, '{n}.cugd02_direccionsuperior.cod_dir_superior', '{n}.cugd02_direccionsuperior.denominacion');
	$this->concatena($dir_superior, 'dir_superior');
	$coordinacion = $this->cugd02_coordinacion->generateList('cod_tipo_institucion='.$cod_tipo_inst.' and cod_institucion='.$cod_institucion.' and cod_dependencia='.$cod_dependencia.' and cod_dir_superior='.$cod_dir_superior,'cod_coordinacion ASC', null, '{n}.cugd02_coordinacion.cod_coordinacion', '{n}.cugd02_coordinacion.denominacion');
	$this->concatena($coordinacion, 'coordinacion');
	$secretaria = $this->cugd02_secretaria->generateList('cod_tipo_institucion='.$cod_tipo_inst.' and cod_institucion='.$cod_institucion.' and cod_dependencia='.$cod_dependencia.' and cod_dir_superior='.$cod_dir_superior.' and cod_coordinacion='.$cod_coordinacion,'cod_secretaria ASC', null, '{n}.cugd02_secretaria.cod_secretaria', '{n}.cugd02_secretaria.denominacion');
	$this->concatena($secretaria, 'secretaria');
	$direccion = $this->cugd02_direccion->generateList('cod_tipo_institucion='.$cod_tipo_inst.' and cod_institucion='.$cod_institucion.' and cod_dependencia='.$cod_dependencia.' and cod_dir_superior='.$cod_dir_superior.' and cod_coordinacion='.$cod_coordinacion.' and cod_secretaria='.$cod_secretaria,'cod_direccion ASC', null, '{n}.cugd02_direccion.cod_direccion', '{n}.cugd02_direccion.denominacion');
	$this->concatena($direccion, 'direccion');
	$division = $this->cugd02_division->generateList('cod_tipo_institucion='.$cod_tipo_inst.' and cod_institucion='.$cod_institucion.' and cod_dependencia='.$cod_dependencia.' and cod_dir_superior='.$cod_dir_superior.' and cod_coordinacion='.$cod_coordinacion.' and cod_secretaria='.$cod_secretaria.' and cod_direccion='.$cod_direccion,'cod_division ASC', null, '{n}.cugd02_division.cod_division', '{n}.cugd02_division.denominacion');
	if($division!=null){
          $this->concatena($division,'division');
          }else{
          	$this->set('division',array('0'=>'00'));
          };
	$departamento = $this->cugd02_departamento->generateList('cod_tipo_institucion='.$cod_tipo_inst.' and cod_institucion='.$cod_institucion.' and cod_dependencia='.$cod_dependencia.' and cod_dir_superior='.$cod_dir_superior.' and cod_coordinacion='.$cod_coordinacion.' and cod_secretaria='.$cod_secretaria.' and cod_direccion='.$cod_direccion.' and cod_division='.$cod_division,'cod_departamento ASC', null, '{n}.cugd02_departamento.cod_departamento', '{n}.cugd02_departamento.denominacion');
	if($departamento!=null){
          $this->concatena($departamento,'departamento');
          }else{
          	$this->set('departamento',array('0'=>'00'));
          };
	$oficina = $this->cugd02_oficina->generateList('cod_tipo_institucion='.$cod_tipo_inst.' and cod_institucion='.$cod_institucion.' and cod_dependencia='.$cod_dependencia.' and cod_dir_superior='.$cod_dir_superior.' and cod_coordinacion='.$cod_coordinacion.' and cod_secretaria='.$cod_secretaria.' and cod_direccion='.$cod_direccion.' and cod_division='.$cod_division.' and cod_departamento='.$cod_departamento,'cod_oficina ASC', null, '{n}.cugd02_oficina.cod_oficina', '{n}.cugd02_oficina.denominacion');
	if($oficina!=null){
          $this->concatena($oficina,'oficina');
          }else{
          	$this->set('oficina',array('0'=>'00'));
          };



	$incorporacion = $this->cimd02_tipo_movimiento->generateList('cod_tipo_mov= 1','cod_mov ASC', null, '{n}.cimd02_tipo_movimiento.cod_mov', '{n}.cimd02_tipo_movimiento.denominacion');
	$this->concatena($incorporacion, 'incorporacion');
	$desincorporacion = $this->cimd02_tipo_movimiento->generateList('cod_tipo_mov= 2','cod_tipo_mov ASC', null, '{n}.cimd02_tipo_movimiento.cod_mov', '{n}.cimd02_tipo_movimiento.denominacion');
	$this->concatena($desincorporacion, 'desincorporacion');

}

 function eliminar($identificacion=null,$pagina=null){
 	$this->layout = "ajax";
       if(isset($identificacion)){
 	$cond=" numero_identificacion =".$identificacion." and ".$this->SQLCA();
 	//echo $cond;
 	$this->cimd03_inventario_inmuebles->execute("DELETE FROM cimd03_inventario_inmuebles  WHERE ".$cond);
 	$y=$this->v_inventario_inmuebles_todo->findCount($this->SQLCA());
 	 	if($pagina>$y){
 		$pagina=$pagina-1;
 	}

	  if($y!=0){
	  	 $this->set('Message_existe', 'Registro Eliminado con exito.');
      	 $this->consulta($pagina);//si es el primero solamente
      $this->render("consulta");

		}else if($y==0){
			$this->set('Message_existe', 'Registro Eliminado con exito.');
			$this->index();
      		$this->render("index");
		}//fin if
 }

 }







function guardar(){
$this->layout ="ajax";
$cod_presi = $this->verifica_SS(1);
$cod_entidad = $this->verifica_SS(2);
$cod_tipo_inst = $this->verifica_SS(3);
$cod_inst = $this->verifica_SS(4);
$cod_dep = $this->verifica_SS(5);


$cod_tipo = $this->Session->read('cod_tipo1');
$cod_grupo = $this->Session->read('cod_grupo1');
$cod_subgrupo = $this->Session->read('cod_subgrupo1');
$cod_seccion = $this->Session->read('cod_seccion1');


$numero_identificacion = $this->data['cimp03_inventario_inmuebles']['numero_identificacion'];
$denominacion_inmueble = $this->data['cimp03_inventario_inmuebles']['denominacion'];
$cod_tipo_incorporacion = $this->data['cimp03_inventario_inmuebles']['cod_tipo_incorporacion'];
$fecha_incorporacion = $this->Cfecha($this->data['cimp03_inventario_inmuebles']['fecha_incorporacion'],'A-M-D');
$cod_estado = $this->data['cimp03_inventario_inmuebles']['cod_estado'];
$cod_municipio = $this->data['cimp03_inventario_inmuebles']['cod_municipio'];
$cod_parroquia = $this->data['cimp03_inventario_inmuebles']['cod_parroquia'];
$cod_centro = $this->data['cimp03_inventario_inmuebles']['cod_centro'];
$cod_calle = $this->data['cimp03_inventario_inmuebles']['cod_calle'];


$cod_institucion = $this->data['cimp03_inventario_inmuebles']['cod_institucion'];
$cod_dependencia = $this->data['cimp03_inventario_inmuebles']['cod_dependencia'];

$cod_dir_superior = $this->data['cimp03_inventario_muebles']['cod_direccions'];
$cod_coordinacion = $this->data['cimp03_inventario_muebles']['cod_coordinacion'];
$cod_secretaria = $this->data['cimp03_inventario_muebles']['cod_secretaria'];
$cod_direccion = $this->data['cimp03_inventario_muebles']['cod_direccion'];
$cod_division = $this->data['cimp03_inventario_muebles']['cod_division'];
$cod_departamento = $this->data['cimp03_inventario_muebles']['cod_departamento'];
$cod_oficina = $this->data['cimp03_inventario_muebles']['cod_oficina'];

$estado_actual = $this->data['cimp03_inventario_inmuebles']['estado_actual'];
$valor_actual = $this->Formato1($this->data['cimp03_inventario_inmuebles']['valor_actual']);
$observacion = $this->data['cimp03_inventario_inmuebles']['observacion_inmueble'];

$numero_doc =  isset($this->data['cimp03_inventario_inmuebles']['numero_doc']) && !empty($this->data['cimp03_inventario_inmuebles']['numero_doc']) ?
						$this->data['cimp03_inventario_inmuebles']['numero_doc'] : '';

//$cod_vialidad ='0';
$area_total_terreno = $this->Formato1($this->data['cimp03_inventario_inmuebles']['area_total_terreno']);
$area_cubierta = $this->Formato1($this->data['cimp03_inventario_inmuebles']['area_cubierta']);
$area_construccion = $this->Formato1($this->data['cimp03_inventario_inmuebles']['area_construccion']);
$area_otras_instalaciones = $this->Formato1($this->data['cimp03_inventario_inmuebles']['area_otras_instalaciones']);
$area_total_construida = $this->Formato1($this->data['cimp03_inventario_inmuebles']['area_total_construida']);
$avaluo_actual = $this->Formato1($this->data['cimp03_inventario_inmuebles']['avaluo_actual']);
$descripcion_inmueble = $this->data['cimp03_inventario_inmuebles']['descripcion_inmueble'];
$linderos = $this->data['cimp03_inventario_inmuebles']['linderos'];
$estudio_legal_propiedad = $this->data['cimp03_inventario_inmuebles']['estudio_legal_propiedad'];
$avaluo_comision = $this->data['cimp03_inventario_inmuebles']['avaluo_comision'];
$cod_tipo_desincorporacion='0';
$fecha_desincorporacion='1900-01-01';
$cod_republica=$cod_presi;


$this->cimd01_clasificacion_seccion->execute("BEGIN; ");

$aux_datos = $this->cimd01_clasificacion_seccion->findAll(" cod_tipo='".$cod_tipo."' and cod_grupo='".$cod_grupo."' and cod_subgrupo='".$cod_subgrupo."' and cod_seccion='".$cod_seccion."'");


$denominacion_incorporacion = $this->data['cimp03_inventario_inmuebles']['denominacion_incorporacion'];


$campos="cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo, cod_grupo, cod_subgrupo, cod_seccion,
  		 numero_identificacion, denominacion_inmueble, cod_republica, cod_estado, cod_municipio, cod_parroquia,
  		 cod_centro,cod_vialidad, area_total_terreno, area_cubierta, area_construccion, area_otras_instalaciones,
  		 area_total_construida, avaluo_actual, descripcion_inmueble, linderos, estudio_legal_propiedad, avaluo_comision,
  		 cod_tipo_incorporacion, fecha_incorporacion, cod_tipo_desincorporacion, fecha_desincorporacion, fecha_proceso_registro, username_registro, condicion_actividad,
  		 cod_institucion,
  		 cod_dependencia,
  		 cod_dir_superior,
  		 cod_coordinacion,
  		 cod_secretaria,
  		 cod_direccion,
  		 cod_division,
  		 cod_departamento,
  		 cod_oficina,
  		 estado_actual,
  		 valor_actual,
  		 observacion_inmueble,
  		 numero_doc";


		$sql    ="INSERT INTO  cimd03_inventario_inmuebles ($campos)VALUES (
		 $cod_presi, $cod_entidad, $cod_tipo_inst, $cod_inst, $cod_dep, $cod_tipo, $cod_grupo, $cod_subgrupo, $cod_seccion,
  		 $numero_identificacion, '$denominacion_inmueble', $cod_republica, $cod_estado, $cod_municipio, $cod_parroquia,
  		 $cod_centro, $cod_calle, $area_total_terreno, $area_cubierta, $area_construccion, $area_otras_instalaciones,
  		 $area_total_construida, $avaluo_actual, '$descripcion_inmueble', '$linderos', '$estudio_legal_propiedad', '$avaluo_comision',
  		 $cod_tipo_incorporacion, '$fecha_incorporacion', $cod_tipo_desincorporacion, '$fecha_desincorporacion', '".date("d/m/Y")."', '".$_SESSION['nom_usuario']."', '1',
  		 $cod_institucion,
  		 $cod_dependencia,
  		 $cod_dir_superior,
  		 $cod_coordinacion,
  		 $cod_secretaria,
  		 $cod_direccion,
  		 $cod_division,
  		 $cod_departamento,
  		 $cod_oficina,
  		 $estado_actual,
  		 $valor_actual,
  		 '$observacion',
  		 '$numero_doc')";

		$respuesta=$this->cimd03_inventario_inmuebles->execute($sql);


if($respuesta>1){


	    $parametro_bienes_aux["denominacion"]            = $denominacion_inmueble;
        $parametro_bienes_aux["numero_identificacion"]   = $numero_identificacion;
        $parametro_bienes_aux["fecha_identificacion"]    = $this->data['cimp03_inventario_inmuebles']['fecha_incorporacion'];
        $parametro_bienes_aux["concepto"]                = $denominacion_incorporacion;
		$parametro_bienes_aux["monto"]                   = $avaluo_actual;
        //$parametro_bienes_aux["cod_tipo_cuenta"]         = $aux_datos[0]["cimd01_clasificacion_seccion"]["bienes_tipo"];
        //$parametro_bienes_aux["cod_cuenta"]              = $aux_datos[0]["cimd01_clasificacion_seccion"]["bienes_cuenta"];
        //$parametro_bienes_aux["cod_subcuenta"]           = $aux_datos[0]["cimd01_clasificacion_seccion"]["bienes_grupo_subcuenta"];
        //$parametro_bienes_aux["cod_division"]            = $aux_datos[0]["cimd01_clasificacion_seccion"]["bienes_subgrupo_division"];
        //$parametro_bienes_aux["cod_subdivision"]         = $aux_datos[0]["cimd01_clasificacion_seccion"]["bienes_seccion_subdivision"];

		$parametro_bienes_aux["cod_tipo_cuenta"]         = 1;
        $parametro_bienes_aux["cod_cuenta"]              = 212;
        $parametro_bienes_aux["cod_subcuenta"]           = $cod_grupo;
        $parametro_bienes_aux["cod_division"]            = 0;
        $parametro_bienes_aux["cod_subdivision"]         = 0;


        $parametro_bienes_aux["cod_tipo"]              = $cod_tipo;
        $parametro_bienes_aux["cod_grupo"]             = $cod_grupo;
        $parametro_bienes_aux["cod_subgrupo"]          = $cod_subgrupo;
        $parametro_bienes_aux["cod_seccion"]           = $cod_seccion;

					$valor_motor_contabilidad = $this->motor_contabilidad_fiscal(
																			      $to      = 1,
																			      $td      = 17,
																			      $rif_doc = null,
																			      $ano_dc  = $this->ano_ejecucion(),
																			      $n_dc    = $numero_identificacion,
																			      $f_dc    = $this->data['cimp03_inventario_inmuebles']['fecha_incorporacion'],
																			      $cpt_dc  = null,
																			      $ben_dc  = null,
																			      $mon_dc  = array(),

																			      $ano_op   = null,
																			      $n_op     = null,
																			      $f_op     = null,

																			      $a_adj_op = null,
																			      $n_adj_op = null,
																			      $f_adj_op = null,
																			      $tp_op    = null,

																			      $deno_ban_pago  = null,
																			      $ano_movimiento = null,
																			      $cod_ent_pago   = null,
																			      $cod_suc_pago   = null,
																			      $cod_cta_pago   = null,

																			      $num_che_o_debi  = null,
																			      $fec_che_o_debi  = null,
																			      $clas_che_o_debi = null,
																			      $tipo_che_o_debi = null,

																			      $ano_dc_array_pago    = array(),
																			      $n_dc_array_pago      = array(),
																			      $n_dc_adj_array_pago  = array(),
																			      $f_dc_array_pago      = array(),

																			      $ano_op_array_pago  = array(),
																			      $n_op_array_pago    = array(),
																			      $f_op_array_pago    = array(),
																			      $tipo_op_array_pago = array(),
																			      $tipo_modificacion  = null,
																			      $f_dc_adj_array_pago= array(),
																			      $parametro_bienes   = $parametro_bienes_aux
																			  );

 }else{

   $valor_motor_contabilidad = false;

 }//fin esle


$valor_motor_contabilidad=true;
   if($valor_motor_contabilidad==true){
	  $this->set('Message_existe', 'Registro Agregado con exito.');
	  $this->cimd03_inventario_inmuebles->execute("COMMIT;");
	  $this->index();
	  $this->render("index");//echo "si entro";
	  $this->data=null;
  }else{
	  $this->set('errorMessage', 'Disculpe, El Registro no fue creado.');
	  $this->cimd03_inventario_inmuebles->execute("ROLLBACK;");
	  $this->index();
	  $this->render("index");//echo "no entro";
	  $this->data=null;
  }//fin else




}//fin function










function guardar_modificar($numero_identificacion=null,$cod_tipo=null,$cod_grupo=null,$cod_subgrupo=null,$cod_seccion=null,$pagina=null){//echo 'llego aqui';
$this->layout = "ajax";
//pr($this->data);
$cod_tipo_incorporacion = $this->data['cimp03_inventario_inmuebles']['cod_tipo_incorporacion'];
$fecha_incorporacion = $this->Cfecha($this->data['cimp03_inventario_inmuebles']['fecha_incorporacion'],'A-M-D');
$cod_estado = $this->data['cimp03_inventario_inmuebles']['cod_estado'];
$cod_municipio = $this->data['cimp03_inventario_inmuebles']['cod_municipio'];
$cod_parroquia = $this->data['cimp03_inventario_inmuebles']['cod_parroquia'];
$cod_centro = $this->data['cimp03_inventario_inmuebles']['cod_centro'];
$cod_vialidad =$this->data['cimp03_inventario_inmuebles']['cod_calle'];
$area_total_terreno = $this->Formato1($this->data['cimp03_inventario_inmuebles']['area_total_terreno']);
$area_cubierta = $this->Formato1($this->data['cimp03_inventario_inmuebles']['area_cubierta']);
$area_construccion = $this->Formato1($this->data['cimp03_inventario_inmuebles']['area_construccion']);
$area_otras_instalaciones = $this->Formato1($this->data['cimp03_inventario_inmuebles']['area_otras_instalaciones']);
$area_total_construida = $this->Formato1($this->data['cimp03_inventario_inmuebles']['area_total_construida']);
$avaluo_actual = $this->Formato1($this->data['cimp03_inventario_inmuebles']['avaluo_actual']);
$descripcion_inmueble = $this->data['cimp03_inventario_inmuebles']['descripcion_inmueble'];
$linderos = $this->data['cimp03_inventario_inmuebles']['linderos'];
$estudio_legal_propiedad = $this->data['cimp03_inventario_inmuebles']['estudio_legal_propiedad'];
$avaluo_comision = $this->data['cimp03_inventario_inmuebles']['avaluo_comision'];
$denominacion = $this->data['cimp03_inventario_inmuebles']['denominacion'];
if($this->data['cimp03_inventario_inmuebles']['cod_tipo_desincorporacion']!=''){
	$cod_tipo_desincorporacion = $this->data['cimp03_inventario_inmuebles']['cod_tipo_desincorporacion'];
}else{
$cod_tipo_desincorporacion = 0;
}
if($this->data['cimp03_inventario_inmuebles']['fecha_desincorporacion']!=''){
$fecha_desincorporacion = $this->Cfecha($this->data['cimp03_inventario_inmuebles']['fecha_desincorporacion'],'A-M-D');
$fpd= date("d/m/Y");
$upd= $_SESSION['nom_usuario'];
$condicion = 2;
}else{
	$fecha_desincorporacion='1900-01-01';
	$fpd='1900-01-01';
	$upd='';
	$condicion = 1;
}


$cod_institucion = $this->data['cimp03_inventario_inmuebles']['cod_institucion'];
$cod_dependencia = $this->data['cimp03_inventario_inmuebles']['cod_dependencia'];



//if(isset($this->data['cimp03_inventario_muebles'])){
	$cod_dir_superior = $this->data['cimp03_inventario_muebles']['cod_direccions'];
	$cod_coordinacion = $this->data['cimp03_inventario_muebles']['cod_coordinacion'];
	$cod_secretaria = $this->data['cimp03_inventario_muebles']['cod_secretaria'];
	$cod_direccion = $this->data['cimp03_inventario_muebles']['cod_direccion'];
	$cod_division = $this->data['cimp03_inventario_muebles']['cod_division'];
	$cod_departamento = $this->data['cimp03_inventario_muebles']['cod_departamento'];
	$cod_oficina = $this->data['cimp03_inventario_muebles']['cod_oficina'];
/*}else{
	$cod_dir_superior = $this->data['cimp03_inventario_inmuebles']['cod_direccions'];
	$cod_coordinacion = $this->data['cimp03_inventario_inmuebles']['cod_coordinacion'];
	$cod_secretaria = $this->data['cimp03_inventario_inmuebles']['cod_secretaria'];
	$cod_direccion = $this->data['cimp03_inventario_inmuebles']['cod_direccion'];
	$cod_division = $this->data['cimp03_inventario_inmuebles']['cod_division'];
	$cod_departamento = $this->data['cimp03_inventario_inmuebles']['cod_departamento'];
	$cod_oficina = $this->data['cimp03_inventario_inmuebles']['cod_oficina'];
}*/


$estado_actual = $this->data['cimp03_inventario_inmuebles']['estado_actual'];
$valor_actual = $this->Formato1($this->data['cimp03_inventario_inmuebles']['valor_actual']);
$observacion = $this->data['cimp03_inventario_inmuebles']['observacion_inmueble'];

$numero_doc =  isset($this->data['cimp03_inventario_inmuebles']['numero_doc']) && !empty($this->data['cimp03_inventario_inmuebles']['numero_doc']) ?
						$this->data['cimp03_inventario_inmuebles']['numero_doc'] : '';



$this->cimd03_inventario_inmuebles->execute("BEGIN; ");



 $sql="update cimd03_inventario_inmuebles set
  		cod_estado=".$cod_estado.",
  		cod_municipio=".$cod_municipio.",
  		cod_parroquia=".$cod_parroquia.",
  		cod_centro=".$cod_centro.",
  		cod_vialidad=".$cod_vialidad.",
  		area_total_terreno=".$area_total_terreno.",
  		area_cubierta=".$area_cubierta.",
  		area_construccion=".$area_construccion.",
  		area_otras_instalaciones=".$area_otras_instalaciones.",
  		area_total_construida=".$area_total_construida.",
  		avaluo_actual=".$avaluo_actual.",
  		descripcion_inmueble='".$descripcion_inmueble."',
  		linderos='".$linderos."',
  		estudio_legal_propiedad='".$estudio_legal_propiedad."',
  		avaluo_comision='".$avaluo_comision."',
  		cod_tipo_incorporacion=".$cod_tipo_incorporacion.",
  		fecha_incorporacion='".$fecha_incorporacion."',
  		cod_tipo_desincorporacion=".$cod_tipo_desincorporacion.",
  		fecha_desincorporacion='".$fecha_desincorporacion."',
  		denominacion_inmueble='".$denominacion."',
  		condicion_actividad=".$condicion.",
  		 fecha_proceso_desincorporacion='".$fpd."',
  		 username_desincorporacion='".$upd."',
  		 cod_institucion = ".$cod_institucion.",
  		 cod_dependencia = ".$cod_dependencia.",
  		 cod_dir_superior = ".$cod_dir_superior.",
  		 cod_coordinacion = ".$cod_coordinacion.",
  		 cod_secretaria = ".$cod_secretaria.",
  		 cod_direccion = ".$cod_direccion.",
  		 cod_division = ".$cod_division.",
  		 cod_departamento = ".$cod_departamento.",
  		 cod_oficina = ".$cod_oficina.",
  		 estado_actual = ".$estado_actual.",
  		 valor_actual = ".$valor_actual.",
  		 observacion_inmueble = '".$observacion."',
  		 numero_doc = '".$numero_doc."'

  	where numero_identificacion=".$numero_identificacion." and  cod_tipo=".$cod_tipo." and cod_grupo=".$cod_grupo." and cod_subgrupo=".$cod_subgrupo." and cod_seccion=".$cod_seccion." and ".$this->SQLCA();


     $verificar=$this->cimd03_inventario_inmuebles->execute($sql);


if($cod_tipo_desincorporacion!=0){

$aux_datos = $this->cimd01_clasificacion_seccion->findAll(" cod_tipo='".$cod_tipo."' and cod_grupo='".$cod_grupo."' and cod_subgrupo='".$cod_subgrupo."' and cod_seccion='".$cod_seccion."'");


        $parametro_bienes_aux["denominacion"]            = $denominacion;
        $parametro_bienes_aux["numero_identificacion"]   = $numero_identificacion;
        $parametro_bienes_aux["fecha_identificacion"]    = $this->data['cimp03_inventario_inmuebles']['fecha_incorporacion'];
        $parametro_bienes_aux["concepto"]                = $this->data['cimp03_inventario_inmuebles']['denominacion_desincorporacion'];
		$parametro_bienes_aux["monto"]                   = $avaluo_actual;
        $parametro_bienes_aux["cod_tipo_cuenta"]         = 1;
        $parametro_bienes_aux["cod_cuenta"]              = 212;
        $parametro_bienes_aux["cod_subcuenta"]           = $cod_grupo;
        $parametro_bienes_aux["cod_division"]            = 0;
        $parametro_bienes_aux["cod_subdivision"]         = 0;

        $parametro_bienes_aux["cod_tipo"]              = $cod_tipo;
        $parametro_bienes_aux["cod_grupo"]             = $cod_grupo;
        $parametro_bienes_aux["cod_subgrupo"]          = $cod_subgrupo;
        $parametro_bienes_aux["cod_seccion"]           = $cod_seccion;


					  if($verificar>1){

							             $valor_motor_contabilidad = $this->motor_contabilidad_fiscal(
																								      $to      = 2,
																								      $td      = 17,
																								      $rif_doc = null,
																								      $ano_dc  = $this->ano_ejecucion(),
																								      $n_dc    = $numero_identificacion,
																								      $f_dc    = $this->data['cimp03_inventario_inmuebles']['fecha_desincorporacion'],
																								      $cpt_dc  = null,
																								      $ben_dc  = null,
																								      $mon_dc  = array(),

																								      $ano_op   = null,
																								      $n_op     = null,
																								      $f_op     = null,

																								      $a_adj_op = null,
																								      $n_adj_op = null,
																								      $f_adj_op = null,
																								      $tp_op    = null,

																								      $deno_ban_pago  = null,
																								      $ano_movimiento = null,
																								      $cod_ent_pago   = null,
																								      $cod_suc_pago   = null,
																								      $cod_cta_pago   = null,

																								      $num_che_o_debi  = null,
																								      $fec_che_o_debi  = null,
																								      $clas_che_o_debi = null,
																								      $tipo_che_o_debi = null,

																								      $ano_dc_array_pago    = array(),
																								      $n_dc_array_pago      = array(),
																								      $n_dc_adj_array_pago  = array(),
																								      $f_dc_array_pago      = array(),

																								      $ano_op_array_pago  = array(),
																								      $n_op_array_pago    = array(),
																								      $f_op_array_pago    = array(),
																								      $tipo_op_array_pago = array(),
																								      $tipo_modificacion  = null,
																								      $f_dc_adj_array_pago= array(),
																								      $parametro_bienes   = $parametro_bienes_aux
																								  );

										    if($valor_motor_contabilidad==true){
												    $this->set('Message_existe', 'Registro Modificado con exito.');
												    $this->cimd03_inventario_inmuebles->execute("COMMIT;");
													$this->consulta($pagina);
										      		$this->render("consulta");
											}else{
												    $this->set('errorMessage', 'El registro no pudo ser modificado');
												    $this->cimd03_inventario_inmuebles->execute("ROLLBACK;");
										 		 	$this->consulta($pagina);
										      		$this->render("consulta");
											}//fin else

					  }else{

					  	                            $this->set('errorMessage', 'El registro no pudo ser modificado');
												    $this->cimd03_inventario_inmuebles->execute("ROLLBACK;");
										 		 	$this->consulta($pagina);
										      		$this->render("consulta");

					  }//fin else


}else{



					if($verificar>1){
						$this->set('Message_existe', 'Registro Modificado con exito.');
						$this->cimd03_inventario_inmuebles->execute("COMMIT;");
							$this->consulta($pagina);
				      		$this->render("consulta");
					}else{
						$this->set('errorMessage', 'El registro no pudo ser modificado');
						    $this->cimd03_inventario_inmuebles->execute("ROLLBACK;");
				 		 	$this->consulta($pagina);
				      		$this->render("consulta");
					}

}//fin else


		//$this->set('Message_existe', 'Registro Modificado con exito.');
		//$this->consulta($pagina);
  		//$this->render("consulta");//echo "no entro";
  		//$this->data=null;

}//fin function






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
	if($tipo=='numero_identificacion'){
		$busq="numero_identificacion=".$pista2;
	}else if($tipo=='denominacion'){
		$busq="upper($tipo) LIKE '%$pista2%'";
	}


	if($tipo!=null && $pista!=null){
				$exe="select * from v_inventario_inmuebles_todo where ".$busq;
				$datos=$this->v_inventario_inmuebles_todo->execute($exe);
				$this->set('datos', $datos);

	}

}

function preconsulta(){
	$this->layout="ajax";
	$opciones = array('numero_identificacion'=>'Numero de Identificacion');
	$this->set('opcion', $opciones);
}
function buscar_rif() {
	$this->layout="ajax";
}
function lista_encontrados($rif){
 		$this->layout = "ajax";
 		  $cond =$this->SQLCA()." and numero_identificacion='".$rif."'";//echo $cond;
 		 $num=$this->v_inventario_inmuebles_todo->findCount($cond);
 		 if($num==1){
          	 $datacpcp01=$this->v_inventario_inmuebles_todo->findAll($cond,null,'numero_identificacion ASC');
          	 $this->set('datos',$datacpcp01);
 		 }else{//echo "no hay dato";
 		 	$this->set('errorMessage', 'No se encontrar&oacute;n datos');
 		 	$this->preconsulta();
 		 	$this->render("preconsulta");
      }//fin function consultar2
}
function buscar_mueble($var1=null){
	$this->layout="ajax";
	$this->set("opcion",$var1);
	$this->Session->delete('pista');
}//fin function

function buscar_por_pista($var1=null, $var2=null, $var3=null){
$this->layout="ajax";


    if($var3==null){$var2 = strtoupper($var2);
					$this->Session->write('pista', $var2);
					if(is_int($var2)){$sql   = " (codigo_prod_serv LIKE '%$var2%')  or   ";}else{ $sql = "";}
					$Tfilas=$this->v_buscar_inmuebles->findCount($sql." (deno_grupo LIKE '%$var2%')");
					        if($Tfilas!=0){
					        	$pagina=1;
					        	$Tfilas=(int)ceil($Tfilas/100);
					        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
								$this->set('total_paginas',$Tfilas);
								$this->set('pagina_actual',$pagina);
								$this->set('ultimo',$Tfilas);
					     	    $datos_filas=$this->v_buscar_inmuebles->findAll($sql." (deno_grupo LIKE '%$var2%')  ",null,"cod_tipo,cod_grupo ASC",100,1,null);
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
						if(is_int($var22)){$sql   = " (codigo_prod_serv LIKE '%$var22%')  or   ";}else{ $sql = "";}
						$Tfilas=$this->v_buscar_inmuebles->findCount($sql." (deno_grupo LIKE '%$var22%')");
						        if($Tfilas!=0){
						        	$pagina=$var3;
						        	$Tfilas=(int)ceil($Tfilas/100);
						        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
									$this->set('total_paginas',$Tfilas);
									$this->set('pagina_actual',$pagina);
									$this->set('ultimo',$Tfilas);
						     	    $datos_filas=$this->v_buscar_inmuebles->findAll($sql." (deno_grupo LIKE '%$var22%')  ",null,"cod_tipo,cod_grupo ASC",100,$pagina,null);
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

function seleccion_busqueda_venta($var1=null, $var2=null, $var3=null,$var4=null){//echo 'holaaaaa';
$this->layout="ajax";
$this->Session->write('cod_tipo1',$var1);
$this->Session->write('cod_grupo1',$var2);
$this->Session->write('cod_subgrupo1',$var3);
$this->Session->write('cod_seccion1',$var4);
//$cod_seccion = $this->Session->read('cod_seccion1');
$resultado=$this->v_buscar_inmuebles->findAll('cod_tipo='.$var1.' and cod_grupo='.$var2.' and cod_subgrupo='.$var3.' and cod_seccion='.$var4);
$a=$resultado[0]['v_buscar_inmuebles']['cod_tipo'];
$b=$this->AddCeroR($resultado[0]['v_buscar_inmuebles']['cod_grupo']);
$c=$this->AddCeroR($resultado[0]['v_buscar_inmuebles']['cod_subgrupo']);
$d=$this->AddCeroR($resultado[0]['v_buscar_inmuebles']['cod_seccion']);
//$espe='N/A';
					echo "<script>";
					    echo "document.getElementById('cod_tipo').value='".$a."';   ";
					    echo "document.getElementById('deno_tipo').value='".$resultado[0]['v_buscar_inmuebles']['deno_tipo']."';   ";
					    echo "document.getElementById('cod_grupo').value='".$b."';   ";
					    echo "document.getElementById('deno_grupo').value='".$resultado[0]['v_buscar_inmuebles']['deno_grupo']."';   ";
					    echo "document.getElementById('cod_subgrupo').value='".$c."';   ";
					    echo "document.getElementById('deno_subgrupo').value='N/A';   ";
					    echo "document.getElementById('cod_seccion').value='".$d."';   ";
					    echo "document.getElementById('deno_seccion').value='N/A';   ";
					    echo "document.getElementById('especificaciones').value='N/A';   ";
					    echo "document.getElementById('segunda_ventana').disabled=false";
					echo "</script>";
$this->funcion();
$this->render("funcion");



}//fin function


function funcion($var1=null, $var2=null, $var3=null){

$this->layout="ajax";


}//fin function
function codigo_desincorporacion($codigo){
	$this->layout = "ajax";
	$a = $this->cimd02_tipo_movimiento->findAll("cod_mov=".$codigo." and cod_tipo_mov=2",array('cod_mov','denominacion'));
    $this->set("a",$a[0]['cimd02_tipo_movimiento']['cod_mov']);
}//fin cpcp02_codigo

function denominacion_desincorporacion($codigo){
	$this->layout = "ajax";
	$b = $this->cimd02_tipo_movimiento->findAll("cod_mov=".$codigo." and cod_tipo_mov=2",array('cod_mov','denominacion'));
	$this->set("b",$b[0]['cimd02_tipo_movimiento']['denominacion']);


}//fin cpcp02_denominacion
/*
function lista_registrada($pag=null){
$this->layout="ajax";
$var1=   $this->Session->read('cod_tipo1');
$var2 =  $this->Session->read('cod_grupo1');
$var3 =  $this->Session->read('cod_subgrupo1');
$var4 =  $this->Session->read('cod_seccion1');
    if($pag==null){
					$Tfilas=$this->v_inventario_inmuebles_todo->findCount($this->SQLCA().' and cod_tipo='.$var1.' and cod_grupo='.$var2.' and cod_subgrupo='.$var3.' and cod_seccion='.$var4);
					        if($Tfilas!=0){
					        	$pagina=1;
					        	$Tfilas=(int)ceil($Tfilas/100);
					        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
								$this->set('total_paginas',$Tfilas);
								$this->set('pagina_actual',$pagina);
								$this->set('ultimo',$Tfilas);
					     	    $datos_filas=$this->v_inventario_inmuebles_todo->findAll($this->SQLCA().' and cod_tipo='.$var1.' and cod_grupo='.$var2.' and cod_subgrupo='.$var3.' and cod_seccion='.$var4,null,"cod_tipo,cod_grupo,cod_subgrupo,cod_seccion ASC",100,1,null);
						        $this->set("datosFILAS",$datos_filas);
						        $this->set('siguiente',$pagina+1);
								$this->set('anterior',$pagina-1);
								$this->bt_nav($Tfilas,$pagina);
								$this->set('pagina',$pagina);
					          }else{
					        	$this->set("datosFILAS",'');
					          }
            }else{
						$Tfilas=$this->v_inventario_inmuebles_todo->findCount($this->SQLCA().' and cod_tipo='.$var1.' and cod_grupo='.$var2.' and cod_subgrupo='.$var3.' and cod_seccion='.$var4);
						        if($Tfilas!=0){
						        	$pagina=$pag;
						        	$Tfilas=(int)ceil($Tfilas/100);
						        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
									$this->set('total_paginas',$Tfilas);
									$this->set('pagina_actual',$pagina);
									$this->set('ultimo',$Tfilas);
						     	    $datos_filas=$this->v_inventario_inmuebles_todo->findAll($this->SQLCA().' and cod_tipo='.$var1.' and cod_grupo='.$var2.' and cod_subgrupo='.$var3.' and cod_seccion='.$var4,null,"cod_tipo,cod_grupo,cod_subgrupo,cod_seccion ASC",100,$pagina,null);
							        $this->set("datosFILAS",$datos_filas);
							        $this->set('siguiente',$pagina+1);
									$this->set('anterior',$pagina-1);
									$this->bt_nav($Tfilas,$pagina);
						         	$this->set('pagina',$pagina);
						          }else{
						        	$this->set("datosFILAS",'');
						          }
                 }//fin else
$this->set("opcion",$var1);
}//fin function

*/

function lista_registrada($pagina=null){
$this->layout="ajax";
$var1=   $this->Session->read('cod_tipo1');
$var2 =  $this->Session->read('cod_grupo1');
$var3 =  $this->Session->read('cod_subgrupo1');
$var4 =  $this->Session->read('cod_seccion1');
			if($pagina==null){
					$Tfilas=$this->v_inventario_inmuebles_todo->findCount($this->SQLCA().' and cod_tipo='.$var1.' and cod_grupo='.$var2.' and cod_subgrupo='.$var3.' and cod_seccion='.$var4);
					        if($Tfilas!=0){
					        	$pagina=1;
					        	$Tfilas=(int)ceil($Tfilas/100);
					        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
								$this->set('total_paginas',$Tfilas);
								$this->set('pagina_actual',$pagina);
								$this->set('ultimo',$Tfilas);
						        $datos_filas=$this->v_inventario_inmuebles_todo->findAll($this->SQLCA().' and cod_tipo='.$var1.' and cod_grupo='.$var2.' and cod_subgrupo='.$var3.' and cod_seccion='.$var4,null,"cod_tipo,cod_grupo,cod_subgrupo,cod_seccion ASC",100,$pagina,null);
						        $this->set("datosFILAS",$datos_filas);
						        $this->set('siguiente',$pagina+1);
								$this->set('anterior',$pagina-1);
								$this->bt_nav($Tfilas,$pagina);
					          }else{
					        	$this->set("datosFILAS",'');
					          }
            }else{
						$Tfilas=$this->v_inventario_inmuebles_todo->findCount($this->SQLCA().' and cod_tipo='.$var1.' and cod_grupo='.$var2.' and cod_subgrupo='.$var3.' and cod_seccion='.$var4);
						        if($Tfilas!=0){
						        	$pagina=$pagina;
						        	$Tfilas=(int)ceil($Tfilas/100);
						        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
									$this->set('total_paginas',$Tfilas);
									$this->set('pagina_actual',$pagina);
									$this->set('ultimo',$Tfilas);
							        $datos_filas=$this->v_inventario_inmuebles_todo->findAll($this->SQLCA().' and cod_tipo='.$var1.' and cod_grupo='.$var2.' and cod_subgrupo='.$var3.' and cod_seccion='.$var4,null,"cod_tipo,cod_grupo,cod_subgrupo,cod_seccion ASC",100,$pagina,null);
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


function consulta2($numero=null, $ficha = null){
 		$this->layout = "ajax";
 	  $cod_presi = $this->Session->read('SScodpresi');
	  $cod_entidad = $this->Session->read('SScodentidad');
	  $cod_tipo_inst = $this->Session->read('SScodtipoinst');
	  $cod_inst = $this->Session->read('SScodinst');
	  $cod_dep = $this->Session->read('SScoddep');
	  $modulo = $this->Session->read('Modulo');

	  
	

	  $c = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep." and numero_identificacion=".$numero;
          	 $veri=$this->v_inventario_inmuebles_todo->findCount($c);
          	 if($veri > 0){
          	 $datacpcp01=$this->v_inventario_inmuebles_todo->findAll($c);
          	 $this->set('datos',$datacpcp01);

          	$incorporacion = $this->cimd02_tipo_movimiento->generateList('cod_tipo_mov= 1','cod_mov ASC', null, '{n}.cimd02_tipo_movimiento.cod_mov', '{n}.cimd02_tipo_movimiento.denominacion');
          	$this->concatena($incorporacion, 'incorporacion');

          	 	if($ficha != null){
                if($datacpcp01[0]['v_inventario_inmuebles_todo']['cod_tipo_incorporacion'] == 3){

                  $sql = "SELECT b.denominacion 
                      FROM 
                        cscd04_ordencompra_encabezado AS a 
                      INNER join
                        cpcd02 AS b ON
                        a.rif = b.rif 
                      WHERE 
                        a.cod_dep = $cod_dep AND
                        a.numero_orden_compra = ".$datacpcp01[0]['v_inventario_inmuebles_todo']['numero_orden_compra']." AND
                        a.ano_orden_compra = ".$datacpcp01[0]['v_inventario_inmuebles_todo']['ano_orden_compra'];

                  $proveedor = $this->v_inventario_muebles_todo->execute($sql);
                  
                  $this->set('proveedor', $proveedor[0][0]['denominacion']);
                }
	          	 	$this->render('consulta_ficha_inmueble');
	          	 }
          	 }else{
				$this->index($numero);
				$this->render("index");
          	 }
}//fin function consultar2

function buscar($var1=null){
	$this->layout="ajax";
	$this->set("opcion",$var1);
	$this->Session->delete('pista');
}//fin function

function buscar_por_pista2($var1=null, $var2=null, $var3=null){
$this->layout="ajax";

    if($var3==null){$var2 = strtoupper($var2);
					$this->Session->write('pista', $var2);
					//$sql   = " (numero_identificacion::text LIKE '%$var2%')  or   ";
					$Tfilas=$this->v_inventario_inmuebles_todo->findCount(" (buscar LIKE '%$var2%') and ".$this->SQLCA());
					        if($Tfilas!=0){
					        	$pagina=1;
					        	$Tfilas=(int)ceil($Tfilas/100);
					        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
								$this->set('total_paginas',$Tfilas);
								$this->set('pagina_actual',$pagina);
								$this->set('ultimo',$Tfilas);
					     	    $datos_filas=$this->v_inventario_inmuebles_todo->findAll(" (buscar LIKE '%$var2%') and ".$this->SQLCA(),null,"numero_identificacion ASC",100,1,null);
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
						//$sql   = " (numero_identificacion::text LIKE '%$var2%')  or   ";
						$Tfilas=$this->v_inventario_inmuebles_todo->findCount(" (buscar LIKE '%$var22%') and ".$this->SQLCA());
						        if($Tfilas!=0){
						        	$pagina=$var3;
						        	$Tfilas=(int)ceil($Tfilas/100);
						        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
									$this->set('total_paginas',$Tfilas);
									$this->set('pagina_actual',$pagina);
									$this->set('ultimo',$Tfilas);
						     	    $datos_filas=$this->v_inventario_inmuebles_todo->findAll(" (buscar LIKE '%$var22%') and ".$this->SQLCA(),null,"numero_identificacion ASC",100,$pagina,null);
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

function verificar($numero){
	$this->layout="ajax";//echo 'si llego';
	$si=$this->v_inventario_inmuebles_todo->findCount($this->SQLCA().' and numero_identificacion='.$numero);

	if($si > 0){
		 	$exi='si';
		 	$this->set('exi',$exi);
		 	$this->set('numero',$numero);
		 //$datos_filas=$this->v_inventario_muebles_todo->findAll($this->SQLCA().' and numero_identificacion='.$numero);
	}else{
			$exi='no';
			$this->set('exi',$exi);
			$this->set('numero',$numero);
	}
}

function imagen($numero=null){
	$this->layout="ajax";
	$this->set('numero',$numero);
	//echo $numero;
}
function esquema($numero=null){
	$this->layout="ajax";
	$this->set('numero',$numero);
	//echo $numero;
}
function plano($numero=null){
	$this->layout="ajax";
	$this->set('numero',$numero);
	//echo $numero;
}

function ficha_bienes_inmuebles(){
	$this->layout="ajax";
}

}//fin de la clase controller
?>
