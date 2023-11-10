<?php
/*
 * Creado el  30/10/2007 a las 12:03:17 PM
 *
 * Herramienta: EasyEclipse.
 *
 * Proyecto: SIGEP
 *
 */

 class Cimp03InventarioMueblesController extends AppController {
   var $name = 'cimp03_inventario_muebles';
   var $uses = array('cugd90_municipio_defecto','cimd03_inventario_numero','cugd10_imagenes','v_inventario_muebles_todo','v_buscar_muebles','cugd02_division',
                     'cimd03_inventario_muebles','cimd02_tipo_movimiento','cimd01_clasificacion_tipo','cimd01_clasificacion_seccion',
                     'cimd01_clasificacion_subgrupo','cimd01_clasificacion_grupo','cugd01_estados','cugd01_municipios','cugd01_parroquias',
                     'Cugd01_centropoblados','cugd02_dependencia','cugd02_coordinacion','cugd02_departamento','cugd02_direccion',
                     'cugd02_direccionsuperior','cugd02_division','cugd02_oficina','cugd02_institucion','cugd02_secretaria','cugd01_centropoblados',
                     'ccfd04_cierre_mes','cimd06_acta_firmantes','v_cimd01_clasificacion_seccion',

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

        function SQLCX($ano=null){//sql para busqueda de codigos de arranque con y sin año
         $sql_re = "cod_presi=".$this->verifica_SS(1)."  and    ";
         $sql_re .= "cod_entidad=".$this->verifica_SS(2)."  and  ";
         $sql_re .= "cod_tipo_inst=".$this->verifica_SS(3)."  and ";
         $sql_re .= "cod_inst=".$this->verifica_SS(4)." ";
		 return $sql_re;


    }//fin funcion SQLX

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


 function index($numero=null){
 	$this->layout ="ajax";//echo 'es '.$numero;
 	$cod_presi = $this->verifica_SS(1);
 	//echo $cod_presi;
	$cod_entidad = $this->verifica_SS(2);
	$cod_tipo_inst = $this->verifica_SS(3);
	$cod_inst = $this->verifica_SS(4);
	$cod_dep = $this->verifica_SS(5);
 	$this->data=null;

 	$this->set('cod_depe',$cod_dep);
 	$this->set('cod_inst',$cod_inst);
	$this->Session->write('cinst',$cod_inst);
	$this->Session->write('dinst',$cod_inst);
	$this->Session->write('cdepe',$cod_dep);
	$this->Session->write('ddepe',$cod_dep);

    $aa=  $this->cugd02_institucion->findAll('cod_tipo_institucion='.$cod_tipo_inst.' and cod_institucion='.$cod_inst);
    $ee=$aa[0]['cugd02_institucion']['denominacion'];
    $this->set('deno_inst',$ee);

    $aaa=  $this->cugd02_dependencia->findAll('cod_tipo_institucion='.$cod_tipo_inst.' and cod_institucion='.$cod_inst.'and cod_dependencia='.$cod_dep);
    $eee=$aaa[0]['cugd02_dependencia']['denominacion'];
    $this->set('deno_depe',$eee);

 	$this->set('entidad_federal', $this->Session->read('entidad_federal'));
  	$tipo = $this->cimd01_clasificacion_tipo->generateList(null,'cod_tipo ASC', null, '{n}.cimd01_clasificacion_tipo.cod_tipo', '{n}.cimd01_clasificacion_tipo.denominacion');
	$this->concatena($tipo, 'tipo');
	$institucion = $this->cugd02_institucion->generateList('cod_tipo_institucion='.$cod_tipo_inst.' and cod_institucion='.$cod_inst,'cod_institucion ASC', null, '{n}.cugd02_institucion.cod_institucion', '{n}.cugd02_institucion.denominacion');
	$this->concatena($institucion, 'institucion');
	$lista=  $this->cugd02_dependencia->generateList('cod_tipo_institucion='.$cod_tipo_inst.' and cod_institucion='.$cod_inst, 'cod_dependencia ASC', null, '{n}.cugd02_dependencia.cod_dependencia', '{n}.cugd02_dependencia.denominacion');
    $this->concatena($lista,'dependencia');
    $cond2 ="cod_tipo_institucion=".$cod_tipo_inst." and cod_institucion=".$cod_inst." and cod_dependencia=".$cod_dep;
	$lista2 = $this->cugd02_direccionsuperior->generateList($cond2, 'cod_dir_superior ASC', null, '{n}.cugd02_direccionsuperior.cod_dir_superior', '{n}.cugd02_direccionsuperior.denominacion');
    $this->concatena($lista2,'dir_superior');

	$incorporacion = $this->cimd02_tipo_movimiento->generateList('cod_tipo_mov=1','cod_mov ASC', null, '{n}.cimd02_tipo_movimiento.cod_mov', '{n}.cimd02_tipo_movimiento.denominacion');
	$this->concatena($incorporacion, 'incorporacion');

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
	$cod_presi = $this->verifica_SS(1);//echo "si llego";
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
		//echo "si generica";
		echo "<script>";
		  		echo "document.getElementById('cod_seleccion_2').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('cod_seleccion_3').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('cod_seleccion_4').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('de_seleccion_2').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('de_seleccion_3').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('de_seleccion_4').innerHTML='<input type=text  size=10% class=inputtext />';  ";
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
		echo "<script>";
		  		echo "document.getElementById('cod_seleccion_4').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('de_seleccion_4').innerHTML='<input type=text  size=10% class=inputtext />';  ";
        	echo "</script>";
		  $this->set('SELECT','centro');
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
		  $this->Session->write('desta',$var);
		  $cond2 ="cod_republica=".$cod_presi." and cod_estado=".$var;
		  $a=  $this->cugd01_estados->findAll($cond2);
          $e=$a[0]['cugd01_estados']['denominacion'];
         $this->set('var',$e);
		break;
		case 'municipio':
		  $desta=  $this->Session->read('desta');
		  $this->Session->write('dmuni',$var);
		  $cond2 ="cod_republica=".$cod_presi." and cod_estado=".$desta." and cod_municipio=".$var;
		  $a=  $this->cugd01_municipios->findAll($cond2);
          $e=$a[0]['cugd01_municipios']['denominacion'];
          $this->set('var',$e);
		break;
		case 'parroquia':
		  $desta=  $this->Session->read('desta');
		  $dmuni =  $this->Session->read('dmuni');
		  $this->Session->write('dparr',$var);
		  $cond2 ="cod_republica=".$cod_presi." and cod_estado=".$desta." and cod_municipio=".$dmuni." and cod_parroquia=".$var;
		  $a=  $this->cugd01_parroquias->findAll($cond2);
          $e= $a[0]['cugd01_parroquias']['denominacion'];
          $this->set('var',$e);
		break;
		case 'centro':
		  $desta=  $this->Session->read('desta');
		  $dmuni =  $this->Session->read('dmuni');
		  $dparr =  $this->Session->read('dparr');
		  $this->Session->write('dcent',$var);
		  $cond2 ="cod_republica=".$cod_presi." and cod_estado=".$desta." and cod_municipio=".$dmuni." and cod_parroquia=".$dparr." and cod_centro=".$var;
		  $a=  $this->cugd01_centropoblados->findAll($cond2);
          $e= $a[0]['cugd01_centropoblados']['denominacion'];
           $this->set('var',$e);
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
          	echo "<input type='text' name='data[cimp03_inventario_muebles][cod_estado]' value='".$this->AddCero3($var)."' id='editar1'  class='inputtext' readonly=readonly  style='text-align:center'/>";
		break;

		case 'municipio':
			 echo "<input type='text' name='data[cimp03_inventario_muebles][cod_municipio]' value='".$this->AddCero3($var)."' id='editar2'  class='inputtext' readonly=readonly  style='text-align:center'/>";
		break;

		case 'parroquia':
           echo "<input type='text' name='data[cimp03_inventario_muebles][cod_parroquia]' value='".$this->AddCero3($var)."' id='editar3' class='inputtext' readonly=readonly  style='text-align:center'/>";
		break;

		case 'centro':
           echo "<input type='text' name='data[cimp03_inventario_muebles][cod_centro]' value='".$this->AddCero3($var)."' class='inputtext' readonly=readonly  style='text-align:center'/>";
		break;

	}//fin wsitch
	}else{
		echo "";
	}
}//fin mostrar3 codigos presupuestarios



function select5($select=null,$var=null) { //select codigos presupuestarios
	$this->layout = "ajax";
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
			echo "<script>";
				echo "document.getElementById('c_seleccion_6').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('c_seleccion_7').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('c_seleccion_8').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('c_seleccion_9').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('c_seleccion_10').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('c_seleccion_11').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('c_seleccion_12').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('c_seleccion_13').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('d_seleccion_6').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('d_seleccion_7').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('d_seleccion_8').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('d_seleccion_9').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('d_seleccion_10').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('d_seleccion_11').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('d_seleccion_12').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('d_seleccion_13').innerHTML='<input type=text  size=10% class=inputtext />';  ";
        	echo "</script>";
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
		echo "<script>";
		  		echo "document.getElementById('c_seleccion_7').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('c_seleccion_8').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('c_seleccion_9').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('c_seleccion_10').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('c_seleccion_11').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('c_seleccion_12').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('c_seleccion_13').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('d_seleccion_7').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('d_seleccion_8').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('d_seleccion_9').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('d_seleccion_10').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('d_seleccion_11').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('d_seleccion_12').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('d_seleccion_13').innerHTML='<input type=text  size=10% class=inputtext />';  ";
        	echo "</script>";
		  $this->set('SELECT','coordinacion');
		  $this->set('codigo','direccions');
		  $this->set('seleccion','');
		  $this->set('n',7);
		  $cinst =  $this->Session->read('cinst');
		  $this->Session->write('cdepe',$var);
		  $cond2 ="cod_institucion=".$cinst." and cod_dependencia=".$var;
		  $lista = $this->cugd02_direccionsuperior->generateList($cond2, 'cod_dir_superior ASC', null, '{n}.cugd02_direccionsuperior.cod_dir_superior', '{n}.cugd02_direccionsuperior.denominacion');
          $this->concatena($lista,'vector');
		break;
		case 'coordinacion':
		echo "<script>";
		  		echo "document.getElementById('c_seleccion_8').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('c_seleccion_9').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('c_seleccion_10').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('c_seleccion_11').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('c_seleccion_12').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('c_seleccion_13').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('d_seleccion_8').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('d_seleccion_9').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('d_seleccion_10').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('d_seleccion_11').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('d_seleccion_12').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('d_seleccion_13').innerHTML='<input type=text  size=10% class=inputtext />';  ";
        	echo "</script>";
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
		echo "<script>";
		  		echo "document.getElementById('c_seleccion_9').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('c_seleccion_10').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('c_seleccion_11').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('c_seleccion_12').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('c_seleccion_13').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('d_seleccion_9').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('d_seleccion_10').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('d_seleccion_11').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('d_seleccion_12').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('d_seleccion_13').innerHTML='<input type=text  size=10% class=inputtext />';  ";
        	echo "</script>";
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
		echo "<script>";
		  		echo "document.getElementById('c_seleccion_10').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('c_seleccion_11').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('c_seleccion_12').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('c_seleccion_13').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('d_seleccion_10').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('d_seleccion_11').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('d_seleccion_12').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('d_seleccion_13').innerHTML='<input type=text  size=10% class=inputtext />';  ";
        	echo "</script>";
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
		echo "<script>";
		  		echo "document.getElementById('c_seleccion_11').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('c_seleccion_12').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('c_seleccion_13').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('d_seleccion_11').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('d_seleccion_12').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('d_seleccion_13').innerHTML='<input type=text  size=10% class=inputtext />';  ";
        	echo "</script>";
		  $this->set('SELECT','departamento');
		  $this->set('codigo','division');
		  $this->set('seleccion','');
		  $this->set('n',11);
		  $cinst =  $this->Session->read('cinst');
		  $cdepe =  $this->Session->read('cdepe');
		  $cdirs =  $this->Session->read('cdirs');
		  $ccoor =  $this->Session->read('ccoor');
		  $csecr =  $this->Session->read('csecr');
		  $this->Session->write('cdire',$var);
		  $cond2 ="cod_institucion=".$cinst." and cod_dependencia=".$cdepe." and cod_dir_superior=".$cdirs." and cod_coordinacion=".$ccoor." and cod_secretaria=".$csecr." and cod_direccion=".$var;
		  $lista=  $this->cugd02_division->generateList($cond2, 'cod_division ASC', null, '{n}.cugd02_division.cod_division', '{n}.cugd02_division.denominacion');
          if($lista!=null){
          $this->concatena($lista,'vector');
          }else{
          	echo "<script>";
		  		echo "document.getElementById('c_seleccion_11').innerHTML='<input type=text value=00 name=data[cimp03_inventario_muebles][cod_division] size=10% class=inputtext readonly style=text-align:center />';  ";
		  		echo "document.getElementById('c_seleccion_12').innerHTML='<input type=text value=00 name=data[cimp03_inventario_muebles][cod_departamento] size=10% class=inputtext readonly style=text-align:center />';  ";
		  		echo "document.getElementById('c_seleccion_13').innerHTML='<input type=text value=00 name=data[cimp03_inventario_muebles][cod_oficina] size=10% class=inputtext readonly style=text-align:center />';  ";
		  		echo "document.getElementById('d_seleccion_11').innerHTML='<input type=text value=N/A size=10% class=inputtext readonly />';  ";
		  		echo "document.getElementById('d_seleccion_12').innerHTML='<input type=text value=N/A size=10% class=inputtext readonly />';  ";
		  		echo "document.getElementById('d_seleccion_13').innerHTML='<input type=text value=N/A size=10% class=inputtext readonly />';  ";
		  		echo "document.getElementById('sel_11').innerHTML='<select  class=select100 id=x_11><option value=00 selected>00</select>';  ";
		  		echo "document.getElementById('sel_12').innerHTML='<select  class=select100 id=x_12><option value=00 selected>00</select>';  ";
		  		echo "document.getElementById('sel_13').innerHTML='<select  class=select100 id=x_13><option value=00 selected>00</select>';  ";
        	echo "</script>";
          }


 		break;
		case 'departamento':
		echo "<script>";
		  		echo "document.getElementById('c_seleccion_12').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('c_seleccion_13').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('d_seleccion_12').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('d_seleccion_13').innerHTML='<input type=text  size=10% class=inputtext />';  ";
        	echo "</script>";
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
          if($lista!=null){
          $this->concatena($lista,'vector');
          }else{
          	echo "<script>";
		  		echo "document.getElementById('c_seleccion_12').innerHTML='<input type=text name=data[cimp03_inventario_muebles][cod_departamento] value=00  size=10% class=inputtext readonly style=text-align:center />';  ";
		  		echo "document.getElementById('c_seleccion_13').innerHTML='<input type=text name=data[cimp03_inventario_muebles][cod_oficina] value=00  size=10% class=inputtext readonly style=text-align:center />';  ";
		  		echo "document.getElementById('d_seleccion_12').innerHTML='<input type=text value=N/A size=10% class=inputtext readonly />';  ";
		  		echo "document.getElementById('d_seleccion_13').innerHTML='<input type=text value=N/A size=10% class=inputtext readonly />';  ";
		  		echo "document.getElementById('sel_12').innerHTML='<select  class=select100 id=x_12><option value=00 selected>00</select>';  ";
		  		echo "document.getElementById('sel_13').innerHTML='<select  class=select100 id=x_13><option value=00 selected>00</select>';  ";
        	echo "</script>";
          }
		break;
		case 'oficina':
		echo "<script>";
		  		echo "document.getElementById('c_seleccion_13').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('d_seleccion_13').innerHTML='<input type=text  size=10% class=inputtext />';  ";
        	echo "</script>";
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
          if($lista!=null){
          $this->concatena($lista,'vector');
          }else{
          	echo "<script>";
		  		echo "document.getElementById('c_seleccion_13').innerHTML='<input type=text name=data[cimp03_inventario_muebles][cod_oficina] value=00  size=10% class=inputtext readonly style=text-align:center />';  ";
		  		echo "document.getElementById('d_seleccion_13').innerHTML='<input type=text value=N/A size=10% class=inputtext readonly />';  ";
		  		echo "document.getElementById('sel_13').innerHTML='<select  class=select100 id=x_13>gdfgdgfdg<option value=00 selected>00</select>';  ";
		  		echo "document.getElementById('sel_13').innerHTML='<select  class=select100 id=x_13>gdfgdgfdg<option value=00 selected>00</select>';  ";
        	echo "</script>";
          };
		break;
		case 'bien':
		echo "<script>";
		  		echo "document.getElementById('c_seleccion_14').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('d_seleccion_14').innerHTML='<input type=text  size=10% class=inputtext />';  ";
        	echo "</script>";
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
          if($lista!=null){
          $this->concatena($lista,'vector');
          }else{
          	echo "<script>";
		  		echo "document.getElementById('c_seleccion_13').innerHTML='<input type=text name=data[cimp03_inventario_muebles][cod_oficina] value=00  size=10% class=inputtext readonly style=text-align:center />';  ";
		  		echo "document.getElementById('d_seleccion_13').innerHTML='<input type=text value=N/A size=10% class=inputtext readonly />';  ";
		  		echo "document.getElementById('sel_13').innerHTML='<select  class=select100 id=x_13>gdfgdgfdg<option value=00 selected>00</select>';  ";
		  		echo "document.getElementById('sel_13').innerHTML='<select  class=select100 id=x_13>gdfgdgfdg<option value=00 selected>00</select>';  ";
        	echo "</script>";
          };
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
		  $this->Session->write('dinst',$var);
		   $cond2 ="cod_institucion=".$var;
		  $a=  $this->cugd02_dependencia->findAll($cond2);
          $e=$a[0]['cugd02_dependencia']['denominacion'];
         $this->set('var',$e);
		break;
		case 'dependencia':
		  $dinst=  $this->Session->read('dinst');
		  $this->Session->write('ddepe',$var);
		  $cond2 ="cod_institucion=".$dinst." and cod_dependencia=".$var;
		  $a=  $this->cugd02_dependencia->findAll($cond2);
          $e=$a[0]['cugd02_dependencia']['denominacion'];
          $this->set('var',$e);
		break;
		case 'direccions':
		  $dinst=  $this->Session->read('dinst');
		  $ddepe =  $this->Session->read('ddepe');
		  $this->Session->write('ddirs',$var);
		  $cond2 ="cod_institucion=".$dinst." and cod_dependencia=".$ddepe." and cod_dir_superior=".$var;
		  $a=  $this->cugd02_direccionsuperior->findAll($cond2);
          $e= $a[0]['cugd02_direccionsuperior']['denominacion'];
          $this->set('var',$e);
		break;
		case 'coordinacion':
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
		  $dinst=  $this->Session->read('dinst');
		  $ddepe =  $this->Session->read('ddepe');
		  $ddirs =  $this->Session->read('ddirs');
		  $dcoor =  $this->Session->read('dcoor');
		  $dsecr =  $this->Session->read('dsecr');
		  $ddire =  $this->Session->read('ddire');
		  $this->Session->write('ddivi',$var);

		  $cond2 ="cod_institucion=".$dinst." and cod_dependencia=".$ddepe." and cod_dir_superior=".$ddirs." and cod_coordinacion=".$dcoor." and cod_secretaria=".$dsecr." and cod_direccion=".$ddire." and cod_division=".$var;
		  $a=  $this->cugd02_division->findAll($cond2);
          if($a!=null){
          $e=$a[0]['cugd02_division']['denominacion'];
          $this->set('var',$e);
          }else{
          	$this->set('var','N/A');
          }
		break;
		case 'departamento':
		  $dinst=   $this->Session->read('dinst');
		  $ddepe =  $this->Session->read('ddepe');
		  $ddirs =  $this->Session->read('ddirs');
		  $dcoor =  $this->Session->read('dcoor');
		  $dsecr =  $this->Session->read('dsecr');
		  $ddire =  $this->Session->read('ddire');
		  $ddivi =  $this->Session->read('ddivi');
		  $this->Session->write('ddepa',$var);
		  $cond2 ="cod_institucion=".$dinst." and cod_dependencia=".$ddepe." and cod_dir_superior=".$ddirs." and cod_coordinacion=".$dcoor." and cod_secretaria=".$dsecr." and cod_direccion=".$ddire." and cod_division=".$ddivi." and cod_departamento=".$var;
		  $a=  $this->cugd02_departamento->findAll($cond2);
          if($a!=null){
          $e=$a[0]['cugd02_departamento']['denominacion'];
          $this->set('var',$e);
          }else{
          	$this->set('var','N/A');
          }
		break;
		case 'oficina':
		  $dinst=   $this->Session->read('dinst');
		  $ddepe =  $this->Session->read('ddepe');
		  $ddirs =  $this->Session->read('ddirs');
		  $dcoor =  $this->Session->read('dcoor');
		  $dsecr =  $this->Session->read('dsecr');
		  $ddire =  $this->Session->read('ddire');
		  $ddivi =  $this->Session->read('ddivi');
		  $ddepa =  $this->Session->read('ddepa');
		  $this->Session->write('dofic',$var);
		  $cond2 ="cod_institucion=".$dinst." and cod_dependencia=".$ddepe." and cod_dir_superior=".$ddirs." and cod_coordinacion=".$dcoor." and cod_secretaria=".$dsecr." and cod_direccion=".$ddire." and cod_division=".$ddivi." and cod_departamento=".$ddepa." and cod_oficina=".$var;
		  $a=  $this->cugd02_oficina->findAll($cond2);
          if($a!=null){
          $e=$a[0]['cugd02_oficina']['denominacion'];
          $this->set('var',$e);
          }else{
          	$this->set('var','N/A');
          }
			break;
		case 'bien':
		  $dinst=   $this->Session->read('dinst');
		  $ddepe =  $this->Session->read('ddepe');
		  $ddirs =  $this->Session->read('ddirs');
		  $dcoor =  $this->Session->read('dcoor');
		  $dsecr =  $this->Session->read('dsecr');
		  $ddire =  $this->Session->read('ddire');
		  $ddivi =  $this->Session->read('ddivi');
		  $ddepa =  $this->Session->read('ddepa');
		  $dofic =  $this->Session->read('dofic');
		  $this->Session->write('bien',$var);
		  $cond2 ="cod_institucion=".$dinst." and cod_dependencia=".$ddepe." and cod_dir_superior=".$ddirs." and cod_coordinacion=".$dcoor." and cod_secretaria=".$dsecr." and cod_direccion=".$ddire." and cod_division=".$ddivi." and cod_departamento=".$ddepa." and cod_oficina=". $dofic ." and numero_identificacion=".$var;
		  $a=  $this->cimd03_inventario_muebles->findAll($cond2);
          if($a!=null){
          $e=$a[0]['cimd03_inventario_muebles']['numero_identificacion'];
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

function mostrar8($select=null,$var=null) { //mostrar3 codigos presupuestarios
	$this->layout = "ajax";
		if( $var!=null){
	switch($select){
		case 'institucion':
          	echo "<input type='text' name='data[cimp03_inventario_muebles][cod_institucion]' value='".$this->AddCero3($var)."' id='editar1'  class='inputtext' readonly=readonly  style='text-align:center'/> ";
		break;

		case 'dependencia':
			 echo "<input type='text' name='data[cimp03_inventario_muebles][cod_dependencia]' value='".$this->AddCero3($var)."' id='editar2'  class='inputtext' readonly=readonly  style='text-align:center'/>";
		break;

		case 'direccions':
           echo "<input type='text' name='data[cimp03_inventario_muebles][cod_direccions]' value='".$this->AddCero3($var)."' id='editar3' class='inputtext' readonly=readonly  style='text-align:center'/>";
		break;

		case 'coordinacion':
           echo "<input type='text' name='data[cimp03_inventario_muebles][cod_coordinacion]' value='".$this->AddCero3($var)."' class='inputtext' readonly=readonly  style='text-align:center'/>";
		break;
		case 'secretaria':
          	echo "<input type='text' name='data[cimp03_inventario_muebles][cod_secretaria]' value='".$this->AddCero3($var)."' id='editar1'  class='inputtext' readonly=readonly  style='text-align:center'/>";
		break;

		case 'direccion':
			 echo "<input type='text' name='data[cimp03_inventario_muebles][cod_direccion]' value='".$this->AddCero3($var)."' id='editar2'  class='inputtext' readonly=readonly  style='text-align:center'/>";
		break;

		case 'division':
			 echo "<input type='text' name='data[cimp03_inventario_muebles][cod_division]' value='".$this->AddCero3($var)."' id='editar2'  class='inputtext' readonly=readonly  style='text-align:center'/>";
		break;

		case 'departamento':
           echo "<input type='text' name='data[cimp03_inventario_muebles][cod_departamento]' value='".$this->AddCero3($var)."' id='editar3' class='inputtext' readonly=readonly  style='text-align:center'/>";
		break;

		case 'oficina':
           echo "<input type='text' name='data[cimp03_inventario_muebles][cod_oficina]' value='".$this->AddCero3($var)."' class='inputtext' readonly=readonly  style='text-align:center'/>";
		break;
		case 'bien':
           echo "<input type='text' name='data[cimp03_inventario_muebles][numero_identificacion]' value='".$this->AddCero3($var)."' class='inputtext' readonly=readonly  style='text-align:center'/>";
		break;

	}//fin wsitch
	}else{
		echo "";
	}
}//fin mostrar3 codigos presupuestarios




function codigo_incorporacion($codigo){
	$this->layout = "ajax";
	$a = $this->cimd02_tipo_movimiento->findAll("cod_mov=".$codigo." and cod_tipo_mov=1",array('cod_mov','denominacion'));
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
	$b = $this->cimd02_tipo_movimiento->findAll("cod_mov=".$codigo." and cod_tipo_mov=1",array('cod_mov','denominacion'));
	$this->set("b",$b[0]['cimd02_tipo_movimiento']['denominacion']);


}//fin cpcp02_denominacion

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


function verifica_firmantes($codigo){
	$this->layout = "ajax";
$ano_acteje = $this->ano_ejecucion();
$dactafi = $this->cimd06_acta_firmantes->findAll($this->SQLCA()." and ano_acta='".$ano_acteje."'");

if(empty($dactafi)){
	$this->set('errorMessage', 'Disculpe, Debe registrar las firmas para el acta de desincorporaci&oacute;n');
}else{
     	echo "<script> var confirma; var confirma_edos;";
 			echo "confirma = confirm('POR FAVOR VERIFIQUE LOS FIRMANTES PARA EL ACTA DE DESINCORPORACION:\\n\\n\\tFirma 1: " .$dactafi[0]['cimd06_acta_firmantes']['funcionario_primero'].", ".$dactafi[0]['cimd06_acta_firmantes']['cargo_primero'] .
					"\\n\\n\\tFirma 2: " .$dactafi[0]['cimd06_acta_firmantes']['funcionario_segundo'].", ".$dactafi[0]['cimd06_acta_firmantes']['cargo_segundo'].
					"\\n\\n\\tFirma 3: " .$dactafi[0]['cimd06_acta_firmantes']['funcionario_tercer'].", ".$dactafi[0]['cimd06_acta_firmantes']['cargo_tercer'].
					"\\n\\n\\tFirma 4: " .$dactafi[0]['cimd06_acta_firmantes']['funcionario_cuarto'].", ".$dactafi[0]['cimd06_acta_firmantes']['cargo_cuarto']."');";
 	  	echo "if(confirma==true){document.getElementById('save').disabled = false;}else{document.getElementById('save').disabled = true;" .
 	  			"confirma_edos = confirm('¿Desea Modificar las Firmas?');".
 	  			"if(confirma_edos==true){ver_documento('/cimp06_acta_firmantes/','principal');}else{}}</script>";
}
}//fin verifica_firmantes


function buscar_pista ($pagina=null)
{
	$this->layout="ajax";
	if($pagina!=null){
		$pagina=$pagina;
	}else{
		$pagina=1;
	}
	if(isset($this->data['cimp03_inventario_muebles']['pista']) && !empty($this->data["cimp03_inventario_muebles2"]['pista'])){
         $this->data['cimp03_inventario_muebles']['pista']=$this->data['cimp03_inventario_muebles2']['pista'];
         $otra="si";
	}else{
	  $otra="no";
	}
	if((isset($this->data["cimp03_inventario_muebles"]['pista']) && !empty($this->data['cimp03_inventario_muebles']['pista'])) || $otra=="si"){
         $pista=strtoupper($this->data['cimp03_inventario_muebles']['pista']);
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
           $this->set("pista",$pista);
           $this->set('siguiente',$pagina+1);
		   $this->set('actual',$pagina);
           $this->bt_nav(1,1);
         }
         $this->set("MUESTRAME","");


	}else{
		if(!isset($this->data["cimp03_inventario_muebles"]["pista"])){
			//echo 'entro en el segundo';
			echo "<h4>Faltan Datos para las busqueda, por favor indique pista.</h4>";
		}
	}

}//fin buscar_pista

 function radio($var){
 	$this->layout ="ajax";
 	$this->set('mensaje', 'POR FAVOR INGRESE EL CODIGO');
	$this->set('datos', array());
	$this->set('tipo', array());
	$var1	=   $this->Session->read('cod_tipo1');
	$var2 	=  	$this->Session->read('cod_grupo1');
	$var3 	=  	$this->Session->read('cod_subgrupo1');
	$var4 	=  	$this->Session->read('cod_seccion1');

 	if($var==1){
 		$ss=$this->cimd03_inventario_numero->findAll($this->SQLCA().' and cod_tipo='.$var1.' and cod_grupo='.$var2.' and cod_subgrupo='.$var3.' and cod_seccion='.$var4,array('numero_identificacion'));
 	 	if($ss==null){
     		$new_numero=1;
     	}else{
     		$new_numero=$ss[0]["cimd03_inventario_numero"]["numero_identificacion"]+1;
     	}
		$numero=$this->mascara_ocho($new_numero);
     	echo "<script>";
 			echo "document.getElementById('cantidad2').innerHTML='<input type=text  size=10% class=inputtext id=cantidad           value=1 name=data[cimp03_inventario_muebles][cantidad]           onKeyPress=return solonumeros(event); style=text-align:center  />';  ";
 			echo "document.getElementById('cantidad3').innerHTML='<input type=text  size=10% class=inputtext id=numero_a_registrar value=1 name=data[cimp03_inventario_muebles][numero_a_registrar] onKeyPress=return solonumeros(event); style=text-align:center  />';  ";
 	  	echo "</script>";
     $this->set('numero',$numero);
     $this->set('readonly','readonly');
 	}
 	if($var==2){
 		echo "<script>";
 			echo "document.getElementById('cantidad3').innerHTML='<input name=data[cimp03_inventario_muebles][numero_a_registrar] type=text size=10% class=inputtext value=1 readonly id=numero_a_registrar style=text-align:center readonly=readonly/>';  ";
 			echo "document.getElementById('cantidad2').innerHTML='<input name=data[cimp03_inventario_muebles][cantidad]           type=text size=10% class=inputtext value=1 readonly id=cantidad           style=text-align:center readonly=readonly/>';  ";
 	  	echo "</script>";
 	  	echo "<script>";
 	  		echo "ver_documento('/cimp03_inventario_muebles/funcion','aqui_imagen_mueble');";
 	  	echo "</script>";
 	  $this->set('numero',"");
 	  $this->set('readonly','');
 	}
 }




function consulta($pagina=null){
 		$this->layout = "ajax";
 	  $cod_presi = $this->Session->read('SScodpresi');
	  $cod_entidad = $this->Session->read('SScodentidad');
	  $cod_tipo_inst = $this->Session->read('SScodtipoinst');
	  $cod_inst = $this->Session->read('SScodinst');
	  $cod_dep = $this->Session->read('SScoddep');
	  $modulo = $this->Session->read('Modulo');
	  $c = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep;
          $veri=$this->v_inventario_muebles_todo->findCount($c);
         if($veri!=0){
         if($pagina!=null){
          	 $pagina=$pagina;
          	 $this->set('pagina',$pagina);
          	  $Tfilas=$this->v_inventario_muebles_todo->findCount($c);
          	 if($Tfilas==0){
          	 	$this->index();
          		$this->render("index");
          	 }
          	 if($Tfilas!=0){
          	 $this->set('pag_cant',$pagina.'/'.$Tfilas);
          	 $datacpcp01=$this->v_inventario_muebles_todo->findAll($c,null,'cod_tipo,cod_grupo,cod_subgrupo,cod_seccion,numero_identificacion ASC',1,$pagina,null);
          	 $this->set('datos',$datacpcp01);
          	 $this->set('siguiente',$pagina+1);
          	 $this->set('anterior',$pagina-1);
             $this->bt_nav($Tfilas,$pagina);
             }
 }else{
 	$pagina=1;
 	$this->set('pagina',$pagina);
          	 $Tfilas=$this->v_inventario_muebles_todo->findCount($c);
          	 if($Tfilas==0){
          	 	$this->index();
          		$this->render("index");
          	 }
          	 if($Tfilas!=0){
          	 $this->set('pag_cant',$pagina.'/'.$Tfilas);
          	 $datacpcp01=$this->v_inventario_muebles_todo->findAll($c,null,'cod_tipo,cod_grupo,cod_subgrupo,cod_seccion,numero_identificacion ASC',1,$pagina,null);
          	 $this->set('datos',$datacpcp01);
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


 function eliminar($identificacion=null,$cod_tipo=null,$cod_grupo=null,$cod_subgrupo=null,$cod_seccion=null,$pagina=null){
 	$this->layout = "ajax";
        $cod_presi = $this->Session->read('SScodpresi');
	  $cod_entidad = $this->Session->read('SScodentidad');
	  $cod_tipo_inst = $this->Session->read('SScodtipoinst');
	  $cod_inst = $this->Session->read('SScodinst');
	  $cod_dep = $this->Session->read('SScoddep');
	  $modulo = $this->Session->read('Modulo');
	  $c = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep;
       if(isset($identificacion)){
 	$cond=" numero_identificacion =".$identificacion." and ".$c;
 	//echo $cond;
 	$this->cimd03_inventario_muebles->execute("DELETE FROM cimd03_inventario_muebles  WHERE ".$cond." and cod_tipo=".$cod_tipo." and cod_grupo=".$cod_grupo." and cod_subgrupo=".$cod_subgrupo." and cod_seccion=".$cod_seccion);
 	$y=$this->v_inventario_muebles_todo->findCount($this->SQLCA());
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


function modificar($numero_identificacion=null,$cod_tipo=null,$cod_grupo=null,$cod_subgrupo=null,$cod_seccion=null,$pagina=null){

$cod_presi = $this->verifica_SS(1);
$cod_entidad = $this->verifica_SS(2);
$cod_tipo_inst = $this->verifica_SS(3);
$cod_inst = $this->verifica_SS(4);
$cod_dep = $this->verifica_SS(5);
$this->set('verif',$cod_dep);

	$this->layout = "ajax";
	$datos=$this->v_inventario_muebles_todo->findAll($this->SQLCA().' and numero_identificacion='.$numero_identificacion.' and cod_tipo='.$cod_tipo.' and cod_grupo='.$cod_grupo.' and cod_subgrupo='.$cod_subgrupo.' and cod_seccion='.$cod_seccion);
    $this->set('datos',$datos);
    $this->set('pagina',$pagina);
	$vec=$this->cugd10_imagenes->findCount($this->SQLCA()." and cod_campo=17 and identificacion='".$cod_tipo.$cod_grupo.$cod_subgrupo.$cod_seccion.$numero_identificacion."'");
		    if($vec!=0){
		    	$this->set('existe_imagen',true);
		    }else{
		    	$this->set('existe_imagen',false);
		    }
    foreach($datos as $row){
  $cod_estado=$row['v_inventario_muebles_todo']['cod_estado'];
  $cod_municipio=$row['v_inventario_muebles_todo']['cod_municipio'];
  $cod_parroquia=$row['v_inventario_muebles_todo']['cod_parroquia'];
  $cod_centro=$row['v_inventario_muebles_todo']['cod_centro'];
  $cod_institucion=$row['v_inventario_muebles_todo']['cod_institucion'];
  $cod_dependencia=$row['v_inventario_muebles_todo']['cod_dependencia'];
  $cod_dir_superior=$row['v_inventario_muebles_todo']['cod_dir_superior'];
  $cod_coordinacion=$row['v_inventario_muebles_todo']['cod_coordinacion'];
  $cod_secretaria=$row['v_inventario_muebles_todo']['cod_secretaria'];
  $cod_direccion=$row['v_inventario_muebles_todo']['cod_direccion'];
  $cod_division=$row['v_inventario_muebles_todo']['cod_division'];
  $cod_departamento=$row['v_inventario_muebles_todo']['cod_departamento'];
  $cod_oficina=$row['v_inventario_muebles_todo']['cod_oficina'];
    }
    $estado = $this->cugd01_estados->generateList(null,'cod_estado ASC', null, '{n}.cugd01_estados.cod_estado', '{n}.cugd01_estados.denominacion');
	$this->concatena($estado, 'estado');
	$municipio = $this->cugd01_municipios->generateList('cod_estado='.$cod_estado,'cod_municipio ASC', null, '{n}.cugd01_municipios.cod_municipio', '{n}.cugd01_municipios.denominacion');
	$this->concatena($municipio, 'municipio');
	$parroquia = $this->cugd01_parroquias->generateList('cod_estado='.$cod_estado.' and cod_municipio='.$cod_municipio,'cod_parroquia ASC', null, '{n}.cugd01_parroquias.cod_parroquia', '{n}.cugd01_parroquias.denominacion');
	$this->concatena($parroquia, 'parroquia');
	$centro = $this->cugd01_centropoblados->generateList('cod_estado='.$cod_estado.' and cod_municipio='.$cod_municipio.' and cod_parroquia='.$cod_parroquia,'cod_centro ASC', null, '{n}.cugd01_centropoblados.cod_centro', '{n}.cugd01_centropoblados.denominacion');
	$this->concatena($centro, 'centro');
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
	$incorporacion = $this->cimd02_tipo_movimiento->generateList('cod_tipo_mov=1','cod_mov ASC', null, '{n}.cimd02_tipo_movimiento.cod_mov', '{n}.cimd02_tipo_movimiento.denominacion');
	$this->concatena($incorporacion, 'incorporacion');
	$desincorporacion = $this->cimd02_tipo_movimiento->generateList('cod_tipo_mov=2','cod_mov ASC', null, '{n}.cimd02_tipo_movimiento.cod_mov', '{n}.cimd02_tipo_movimiento.denominacion');
	$this->concatena($desincorporacion, 'desincorporacion');
}









function guardar(){
$this->layout ="ajax";
$cod_presi = $this->verifica_SS(1);
$cod_entidad = $this->verifica_SS(2);
$cod_tipo_inst = $this->verifica_SS(3);
$cod_inst = $this->verifica_SS(4);
$cod_dep = $this->verifica_SS(5);
$automatico = $this->data['cimp03_inventario_muebles']['automatico'];

$cod_tipo = $this->Session->read('cod_tipo1');
$cod_grupo = $this->Session->read('cod_grupo1');
$cod_subgrupo = $this->Session->read('cod_subgrupo1');
$cod_seccion = $this->Session->read('cod_seccion1');


$numero_identificacion = $this->data['cimp03_inventario_muebles']['numero_identificacion'];
$numero_a_registrar = $this->data['cimp03_inventario_muebles']['numero_a_registrar'];
$cantidad = $this->data['cimp03_inventario_muebles']['cantidad'];
if($this->data['cimp03_inventario_muebles']['cantidad']==null){
	$cantidad=1;
}else{
	$cantidad = $this->Formato1($this->data['cimp03_inventario_muebles']['cantidad']);
}

$valor_unitario = $this->Formato1($this->data['cimp03_inventario_muebles']['valor_unitario']);
$cod_tipo_incorporacion = $this->data['cimp03_inventario_muebles']['codigo_incorporacion'];
$fecha_incorporacion = $this->Cfecha($this->data['cimp03_inventario_muebles']['fecha_incorporacion'],'A-M-D');
$cod_estado = $this->data['cimp03_inventario_muebles']['cod_estado'];
$cod_municipio = $this->data['cimp03_inventario_muebles']['cod_municipio'];
$cod_parroquia = $this->data['cimp03_inventario_muebles']['cod_parroquia'];
$cod_centro = $this->data['cimp03_inventario_muebles']['cod_centro'];
$cod_institucion = $this->data['cimp03_inventario_muebles']['cod_institucion'];
$cod_dependencia = $this->data['cimp03_inventario_muebles']['cod_dependencia'];
$cod_dir_superior = $this->data['cimp03_inventario_muebles']['cod_direccions'];
$cod_coordinacion = $this->data['cimp03_inventario_muebles']['cod_coordinacion'];
$cod_secretaria = $this->data['cimp03_inventario_muebles']['cod_secretaria'];
$cod_direccion = $this->data['cimp03_inventario_muebles']['cod_direccion'];
$cod_division = $this->data['cimp03_inventario_muebles']['cod_division'];
$cod_departamento = $this->data['cimp03_inventario_muebles']['cod_departamento'];
$cod_oficina = $this->data['cimp03_inventario_muebles']['cod_oficina'];
$denominacion = $this->data['cimp03_inventario_muebles']['denominacion'];
$estado_actual = $this->data['cimp03_inventario_muebles']['estado_actual'];
$valor_actual = $this->Formato1($this->data['cimp03_inventario_muebles']['valor_actual']);
$observacion = $this->data['cimp03_inventario_muebles']['observacion_mueble'];
$numero_doc = $this->data['cimp03_inventario_muebles']['numero_doc'];

if(isset($this->data['cimp03_inventario_muebles']['ano_compra']) && $this->data['cimp03_inventario_muebles']['ano_compra'] != null){
	$ano_orden_compra = $this->data['cimp03_inventario_muebles']['ano_compra'];
}else{
	$ano_orden_compra = 0;
}

if(isset($this->data['cimp03_inventario_muebles']['numero_compra']) && $this->data['cimp03_inventario_muebles']['numero_compra'] != null){
	$numero_orden_compra = $this->data['cimp03_inventario_muebles']['numero_compra'];
}else{
	$numero_orden_compra = 0;
}

if(isset($this->data['cimp03_inventario_muebles']['fecha_compra']) && $this->data['cimp03_inventario_muebles']['fecha_compra'] != null){
	$fecha_orden_compra = $this->data['cimp03_inventario_muebles']['fecha_compra'];
}else{
	$fecha_orden_compra = '1900-01-01';
}


$denominacion_incorporacion = $this->data['cimp03_inventario_muebles']['denominacion_incorporacion'];
$cod_tipo_desincorporacion='0';
$fecha_desincorporacion='1900-01-01';
$cod_republica=$cod_presi;

$this->cimd03_inventario_numero->execute("BEGIN; ");

$aux_datos = $this->cimd01_clasificacion_seccion->findAll(" cod_tipo='".$cod_tipo."' and cod_grupo='".$cod_grupo."' and cod_subgrupo='".$cod_subgrupo."' and cod_seccion='".$cod_seccion."'");

$campos="cod_presi,
		 cod_entidad,
		 cod_tipo_inst,
		 cod_inst,
		 cod_dep,
		 cod_tipo,
		 cod_grupo,
		 cod_subgrupo,
		 cod_seccion,
  		 numero_identificacion,
  		 denominacion,
  		 cantidad,
  		 valor_unitario,
  		 cod_tipo_incorporacion,
  		 fecha_incorporacion,
  		 cod_tipo_desincorporacion,
  		 fecha_desincorporacion,
  		 cod_republica,
  		 cod_estado,
  		 cod_municipio,
  		 cod_centro,
  		 cod_institucion,
  		 cod_dependencia,
  		 cod_dir_superior,
  		 cod_coordinacion,
  		 cod_secretaria,
  		 cod_direccion,
  		 cod_division,
  		 cod_departamento,
  		 cod_oficina,
  		 cod_parroquia,
  		 fecha_proceso_registro,
  		 username_registro,
  		 condicion_actividad,
  		 ano_orden_compra,
  		 numero_orden_compra,
  		 fecha_orden_compra,
  		 estado_actual,
  		 valor_actual,
  		 observacion_mueble,
  		 numero_doc";
	$num=$numero_identificacion;
$i=0;


for($i=0;$i<$numero_a_registrar;$i++){
		//echo "entro al".$i;
		$sql    ="INSERT INTO  cimd03_inventario_muebles ($campos)VALUES (
																			$cod_presi,
																			$cod_entidad,
																			$cod_tipo_inst,
																			$cod_inst,
																			$cod_dep,
																			$cod_tipo,
																			$cod_grupo,
																			$cod_subgrupo,
																			$cod_seccion,
																			$num,'$denominacion',
																			$cantidad,
																			$valor_unitario,
																			$cod_tipo_incorporacion,
																	        '$fecha_incorporacion',
																			$cod_tipo_desincorporacion,
																	        '$fecha_desincorporacion',
																	        $cod_republica,
																	        $cod_estado,
																	        $cod_municipio,
																	        $cod_centro,
																			$cod_institucion,
																			$cod_dependencia,
																			$cod_dir_superior,
																			$cod_coordinacion,
																			$cod_secretaria,
																			$cod_direccion,
																			$cod_division,
																			$cod_departamento,
																			$cod_oficina,
																			$cod_parroquia,
																	        '".date("d/m/Y")."',
																	        '".$_SESSION['nom_usuario']."',
																	        '1',
																	        $ano_orden_compra,
																	        $numero_orden_compra,
																	        '$fecha_orden_compra',
																	        $estado_actual,
																	        $valor_actual,
																	        '$observacion',
																	        '$numero_doc')";

	    $respuesta = $this->cimd03_inventario_muebles->execute($sql);


		$parametro_bienes_aux["denominacion"]            = $denominacion;
        $parametro_bienes_aux["numero_identificacion"]   = $num;
        $parametro_bienes_aux["fecha_identificacion"]    = $this->data['cimp03_inventario_muebles']['fecha_incorporacion'];
        $parametro_bienes_aux["concepto"]                = $denominacion_incorporacion;
		$parametro_bienes_aux["monto"]                   = $valor_unitario;
        $parametro_bienes_aux["cod_tipo_cuenta"]         = $aux_datos[0]["cimd01_clasificacion_seccion"]["bienes_tipo"];
        $parametro_bienes_aux["cod_cuenta"]              = $aux_datos[0]["cimd01_clasificacion_seccion"]["bienes_cuenta"];
        $parametro_bienes_aux["cod_subcuenta"]           = $aux_datos[0]["cimd01_clasificacion_seccion"]["bienes_grupo_subcuenta"];
        $parametro_bienes_aux["cod_division"]            = $aux_datos[0]["cimd01_clasificacion_seccion"]["bienes_subgrupo_division"];
        $parametro_bienes_aux["cod_subdivision"]         = $aux_datos[0]["cimd01_clasificacion_seccion"]["bienes_seccion_subdivision"];

        $parametro_bienes_aux["cod_tipo"]              = $cod_tipo;
        $parametro_bienes_aux["cod_grupo"]             = $cod_grupo;
        $parametro_bienes_aux["cod_subgrupo"]          = $cod_subgrupo;
        $parametro_bienes_aux["cod_seccion"]           = $cod_seccion;

        if($respuesta>1){

		             $valor_motor_contabilidad = $this->motor_contabilidad_fiscal(
																			      $to      = 1,
																			      $td      = 16,
																			      $rif_doc = null,
																			      $ano_dc  = $this->ano_ejecucion(),
																			      $n_dc    = $num,
																			      $f_dc    = $this->data['cimp03_inventario_muebles']['fecha_incorporacion'],
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
									$num++;
								}else{
									break;
								}
        }else{
        	$valor_motor_contabilidad = false;
			break;
        }











}//fin for










 if($valor_motor_contabilidad==true){


    $numero_id = $numero_identificacion + ($numero_a_registrar - 1);
	$veri_num  = $this->cimd03_inventario_numero->findCount($this->SQLCA().' and cod_tipo='.$cod_tipo.' and cod_grupo='.$cod_grupo.' and cod_subgrupo='.$cod_subgrupo.' and cod_seccion='.$cod_seccion);
	if($veri_num ==0){
		$campos2="cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep,
  				   cod_tipo, cod_grupo, cod_subgrupo, cod_seccion, numero_identificacion";
		$sql_num=" INSERT INTO cimd03_inventario_numero ($campos2) VALUES ($cod_presi, $cod_entidad, $cod_tipo_inst, $cod_inst, $cod_dep,
  				   $cod_tipo, $cod_grupo, $cod_subgrupo, $cod_seccion, $numero_id)";
	}else{
		$val=$this->SQLCA()." and cod_tipo=$cod_tipo and cod_grupo=$cod_grupo and cod_subgrupo=$cod_subgrupo and cod_seccion=$cod_seccion";
		$sql_num="UPDATE cimd03_inventario_numero set numero_identificacion=$numero_id where ".$val;
	}

 	 $var = $this->cimd03_inventario_numero->execute($sql_num);

		 	  if($var>1){

		 	  	     $this->set('Message_existe', 'Registro Agregado con exito.');
					 $this->cimd03_inventario_numero->execute("COMMIT;");

		 	  }else{

		 	  	     $this->cimd03_inventario_numero->execute("ROLLBACK;");
		             $this->set('errorMessage', 'Disculpe, El Registro no fue creado.');

		 	  }

	 $this->index();
	 $this->render("index");
	 $this->data=null;


  }else{

  $this->cimd03_inventario_numero->execute("ROLLBACK;");
  $this->set('errorMessage', 'Disculpe, El Registro no fue creado.');

  $this->index();
  $this->render("index");
  $this->data=null;


  }//fin else




}//fin function











function guardar_modificar($numero_identificacion=null,$cod_tipo=null,$cod_grupo=null,$cod_subgrupo=null,$cod_seccion=null,$pagina=null){
	$this->layout = "ajax";
$numero_a_registrar = $this->data['cimp03_inventario_muebles']['numero_a_registrar'];
$cantidad = $this->Formato1($this->data['cimp03_inventario_muebles']['cantidad']);
$valor_unitario = $this->Formato1($this->data['cimp03_inventario_muebles']['valor_unitario']);
$cod_tipo_incorporacion = $this->data['cimp03_inventario_muebles']['codigo_incorporacion'];
$fecha_incorporacion = $this->Cfecha($this->data['cimp03_inventario_muebles']['fecha_incorporacion'],'A-M-D');
$cod_estado = $this->data['cimp03_inventario_muebles']['cod_estado'];
$cod_municipio = $this->data['cimp03_inventario_muebles']['cod_municipio'];
$cod_parroquia = $this->data['cimp03_inventario_muebles']['cod_parroquia'];
$cod_centro = $this->data['cimp03_inventario_muebles']['cod_centro'];
$cod_institucion = $this->data['cimp03_inventario_muebles']['cod_institucion'];
$cod_dependencia = $this->data['cimp03_inventario_muebles']['cod_dependencia'];
$cod_dir_superior = $this->data['cimp03_inventario_muebles']['cod_direccions'];
$cod_coordinacion = $this->data['cimp03_inventario_muebles']['cod_coordinacion'];
$cod_secretaria = $this->data['cimp03_inventario_muebles']['cod_secretaria'];
$cod_direccion = $this->data['cimp03_inventario_muebles']['cod_direccion'];
$cod_division = $this->data['cimp03_inventario_muebles']['cod_division'];
$cod_departamento = $this->data['cimp03_inventario_muebles']['cod_departamento'];
$cod_oficina = $this->data['cimp03_inventario_muebles']['cod_oficina'];
$denominacion = $this->data['cimp03_inventario_muebles']['denominacion'];
$observaciones = $this->data['cimp03_inventario_muebles']['observaciones'];

$observacion_mueble = $this->data['cimp03_inventario_muebles']['observacion_mueble'];
$estado_actual = $this->data['cimp03_inventario_muebles']['estado_actual'];
$valor_actual = $this->Formato1($this->data['cimp03_inventario_muebles']['valor_actual']);
$numero_doc = $this->data['cimp03_inventario_muebles']['numero_doc'];

$cod_tipo_desincorporacion = $this->data['cimp03_inventario_muebles']['codigo_desincorporacion'];
if($cod_tipo_desincorporacion==null){
	$cod_tipo_desincorporacion='0';
}

if(isset($this->data['cimp03_inventario_muebles']['ano_compra']) && $this->data['cimp03_inventario_muebles']['ano_compra'] != null){
	$ano_orden_compra = $this->data['cimp03_inventario_muebles']['ano_compra'];
}else{
	$ano_orden_compra = 0;
}

if(isset($this->data['cimp03_inventario_muebles']['numero_compra']) && $this->data['cimp03_inventario_muebles']['numero_compra'] != null){
	$numero_orden_compra = $this->data['cimp03_inventario_muebles']['numero_compra'];
}else{
	$numero_orden_compra = 0;
}

if(isset($this->data['cimp03_inventario_muebles']['fecha_compra']) && $this->data['cimp03_inventario_muebles']['fecha_compra'] != null){
	$fecha_orden_compra = $this->data['cimp03_inventario_muebles']['fecha_compra'];
}else{
	$fecha_orden_compra = '1900-01-01';
}


$denominacion = $this->data['cimp03_inventario_muebles']['denominacion'];
//echo $cod_tipo_desincorporacion;
if($cod_tipo_desincorporacion != 0){
	//$xxxx=1;
$fpd= date("d/m/Y");
$upd= $_SESSION['nom_usuario'];
$fecha_desincorporacion = $this->Cfecha($this->data['cimp03_inventario_muebles']['fecha_desincorporacion'],'A-M-D');
$condicion = 2;
}else{
	$fecha_desincorporacion='1900-01-01';
	$fpd='1900-01-01';
	$upd='';
	$condicion = 1;
	//$xxxx=0;
}
if($fecha_desincorporacion=='--'){
	$fecha_desincorporacion='1900-01-01';
}
$cod_republica='1';
$val=$this->SQLCA();


$ano_act_eje = $this->ano_ejecucion();
$dactaf = $this->cimd06_acta_firmantes->findAll($val." and ano_acta='".$ano_act_eje."'");

if(empty($dactaf)){
	$this->set('errorMessage', 'Disculpe, Debe registrar las firmas para el acta de desincorporaci&oacute;n');
	$this->consulta($pagina);
	$this->render("consulta");
}else{

	if ($cod_tipo_desincorporacion!=0){
$this->cimd03_inventario_numero->execute("BEGIN; ");
$sql = " update cimd03_inventario_muebles set
  		 denominacion='".$denominacion."',
  		 valor_unitario=".$valor_unitario.",
  		 cod_tipo_incorporacion=".$cod_tipo_incorporacion.",
  		 fecha_incorporacion='".$fecha_incorporacion."',
  		 cod_tipo_desincorporacion=".$cod_tipo_desincorporacion.",
  		 fecha_desincorporacion='".$fecha_desincorporacion."',
  		 cod_estado=".$cod_estado.",
  		 cod_municipio=".$cod_municipio.",
  		 cod_centro=".$cod_centro.",
  		 cod_institucion=".$cod_institucion.",
  		 cod_dependencia=".$cod_dependencia.",
  		 cod_dir_superior=".$cod_dir_superior.",
  		 cod_coordinacion=".$cod_coordinacion.",
  		 cod_secretaria=".$cod_secretaria.",
  		 cod_direccion=".$cod_direccion.",
  		 cod_division=".$cod_division.",
  		 cod_departamento=".$cod_departamento.",
  		 cod_oficina=".$cod_oficina.",
  		 cod_parroquia=".$cod_parroquia.",
  		 cantidad=".$cantidad.",
  		 condicion_actividad=".$condicion.",
  		 fecha_proceso_desincorporacion='".$fpd."',
  		 username_desincorporacion='".$upd."',
		 ano_orden_compra=".$ano_orden_compra.",
  		 numero_orden_compra=".$numero_orden_compra.",
  		 fecha_orden_compra='".$fecha_orden_compra."',
  		 estado_actual=".$estado_actual.",
  		 valor_actual=".$valor_actual.",
  		 observacion_mueble='".$observacion_mueble."',
  		 numero_doc='".$numero_doc."',
  		 ano_acta='".$dactaf[0]['cimd06_acta_firmantes']['ano_acta']."',
  		 numero_acta='".($dactaf[0]['cimd06_acta_firmantes']['numero_acta']+1)."',
  		 funcionario_primero='".$dactaf[0]['cimd06_acta_firmantes']['funcionario_primero']."',
  		 cedula_primero='".$dactaf[0]['cimd06_acta_firmantes']['cedula_primero']."',
  		 cargo_primero='".$dactaf[0]['cimd06_acta_firmantes']['cargo_primero']."',
  		 funcionario_segundo='".$dactaf[0]['cimd06_acta_firmantes']['funcionario_segundo']."',
  		 cedula_segundo='".$dactaf[0]['cimd06_acta_firmantes']['cedula_segundo']."',
  		 cargo_segundo='".$dactaf[0]['cimd06_acta_firmantes']['cargo_segundo']."',
  		 funcionario_tercero='".$dactaf[0]['cimd06_acta_firmantes']['funcionario_tercer']."',
  		 cedula_tercero='".$dactaf[0]['cimd06_acta_firmantes']['cedula_tercer']."',
  		 cargo_tercero='".$dactaf[0]['cimd06_acta_firmantes']['cargo_tercer']."',
  		 funcionario_cuarto='".$dactaf[0]['cimd06_acta_firmantes']['funcionario_cuarto']."',
  		 cedula_cuarto='".$dactaf[0]['cimd06_acta_firmantes']['cedula_cuarto']."',
  		 cargo_cuarto='".$dactaf[0]['cimd06_acta_firmantes']['cargo_cuarto']."',
  		 observaciones_desincorporacion='".$observaciones."'
  		where numero_identificacion=".$numero_identificacion." and cod_tipo=".$cod_tipo." and cod_grupo=".$cod_grupo." and cod_subgrupo=".$cod_subgrupo." and cod_seccion=".$cod_seccion." and ".$val;

			$verificar=$this->cimd03_inventario_muebles->execute($sql);
			if($verificar>1){
			$this->cimd06_acta_firmantes->execute("update cimd06_acta_firmantes set numero_acta='".($dactaf[0]['cimd06_acta_firmantes']['numero_acta']+1)."' where ". $val." and ano_acta='".$ano_act_eje."'");
				}
		}else{
		$this->cimd03_inventario_numero->execute("BEGIN; ");
$sql = " update cimd03_inventario_muebles set
  		 denominacion='".$denominacion."',
  		 valor_unitario=".$valor_unitario.",
  		 cod_tipo_incorporacion=".$cod_tipo_incorporacion.",
  		 fecha_incorporacion='".$fecha_incorporacion."',
  		 cod_tipo_desincorporacion=".$cod_tipo_desincorporacion.",
  		 fecha_desincorporacion='".$fecha_desincorporacion."',
  		 cod_estado=".$cod_estado.",
  		 cod_municipio=".$cod_municipio.",
  		 cod_centro=".$cod_centro.",
  		 cod_institucion=".$cod_institucion.",
  		 cod_dependencia=".$cod_dependencia.",
  		 cod_dir_superior=".$cod_dir_superior.",
  		 cod_coordinacion=".$cod_coordinacion.",
  		 cod_secretaria=".$cod_secretaria.",
  		 cod_direccion=".$cod_direccion.",
  		 cod_division=".$cod_division.",
  		 cod_departamento=".$cod_departamento.",
  		 cod_oficina=".$cod_oficina.",
  		 cod_parroquia=".$cod_parroquia.",
  		 cantidad=".$cantidad.",
  		 condicion_actividad=".$condicion.",
  		 fecha_proceso_desincorporacion='".$fpd."',
  		 username_desincorporacion='".$upd."',
		 ano_orden_compra=".$ano_orden_compra.",
  		 numero_orden_compra=".$numero_orden_compra.",
  		 fecha_orden_compra='".$fecha_orden_compra."',
  		 estado_actual=".$estado_actual.",
  		 valor_actual=".$valor_actual.",
  		 observacion_mueble='".$observacion_mueble."',
  		 numero_doc='".$numero_doc."',
  		 observaciones_desincorporacion='".$observaciones."'
  		where numero_identificacion=".$numero_identificacion." and cod_tipo=".$cod_tipo." and cod_grupo=".$cod_grupo." and cod_subgrupo=".$cod_subgrupo." and cod_seccion=".$cod_seccion." and ".$val;
  		$verificar=$this->cimd03_inventario_muebles->execute($sql);
		}

//echo $cod_tipo_desincorporacion;
if($cod_tipo_desincorporacion!=0){

$aux_datos = $this->cimd01_clasificacion_seccion->findAll(" cod_tipo='".$cod_tipo."' and cod_grupo='".$cod_grupo."' and cod_subgrupo='".$cod_subgrupo."' and cod_seccion='".$cod_seccion."'");


        $parametro_bienes_aux["denominacion"]            = $denominacion;
        $parametro_bienes_aux["numero_identificacion"]   = $numero_identificacion;
        $parametro_bienes_aux["fecha_identificacion"]    = $this->data['cimp03_inventario_muebles']['fecha_incorporacion'];
        $parametro_bienes_aux["concepto"]                = $this->data['cimp03_inventario_muebles']['denominacion_desincorporacion'];
		$parametro_bienes_aux["monto"]                   = $valor_unitario;
        $parametro_bienes_aux["cod_tipo_cuenta"]         = $aux_datos[0]["cimd01_clasificacion_seccion"]["bienes_tipo"];
        $parametro_bienes_aux["cod_cuenta"]              = $aux_datos[0]["cimd01_clasificacion_seccion"]["bienes_cuenta"];
        $parametro_bienes_aux["cod_subcuenta"]           = $aux_datos[0]["cimd01_clasificacion_seccion"]["bienes_grupo_subcuenta"];
        $parametro_bienes_aux["cod_division"]            = $aux_datos[0]["cimd01_clasificacion_seccion"]["bienes_subgrupo_division"];
        $parametro_bienes_aux["cod_subdivision"]         = $aux_datos[0]["cimd01_clasificacion_seccion"]["bienes_seccion_subdivision"];

        $parametro_bienes_aux["cod_tipo"]              = $cod_tipo;
        $parametro_bienes_aux["cod_grupo"]             = $cod_grupo;
        $parametro_bienes_aux["cod_subgrupo"]          = $cod_subgrupo;
        $parametro_bienes_aux["cod_seccion"]           = $cod_seccion;


					  if($verificar>1){

							             $valor_motor_contabilidad = $this->motor_contabilidad_fiscal(
																								      $to      = 2,
																								      $td      = 16,
																								      $rif_doc = null,
																								      $ano_dc  = $this->ano_ejecucion(),
																								      $n_dc    = $numero_identificacion,
																								      $f_dc    = $this->data['cimp03_inventario_muebles']['fecha_desincorporacion'],
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
												    $this->cimd03_inventario_numero->execute("COMMIT;");
													$this->consulta($pagina);
										      		$this->render("consulta");
											}else{
												    $this->set('errorMessage', 'El registro no pudo ser modificado');
												    $this->cimd03_inventario_numero->execute("ROLLBACK;");
										 		 	$this->consulta($pagina);
										      		$this->render("consulta");
											}//fin else

					  }else{

					  	                            $this->set('errorMessage', 'El registro no pudo ser modificado');
												    $this->cimd03_inventario_numero->execute("ROLLBACK;");
										 		 	$this->consulta($pagina);
										      		$this->render("consulta");

					  }//fin else


}else{



					if($verificar>1){
						$this->set('Message_existe', 'Registro Modificado con exito.');
						$this->cimd03_inventario_numero->execute("COMMIT;");
							$this->consulta($pagina);
				      		$this->render("consulta");
					}else{
						$this->set('errorMessage', 'El registro no pudo ser modificado');
						    $this->cimd03_inventario_numero->execute("ROLLBACK;");
				 		 	$this->consulta($pagina);
				      		$this->render("consulta");
					}

}//fin else

} // fin else acta firmante

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
				$exe="select * from v_inventario_muebles_todo where ".$busq." and ".$this->SQLCA();
				$datos=$this->v_inventario_muebles_todo->execute($exe);
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
 		 $num=$this->v_inventario_muebles_todo->findCount($cond);
 		 if($num==1){

          	 $datacpcp01=$this->v_inventario_muebles_todo->findAll($cond,null,'numero_identificacion ASC');
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
//echo $var1.$var1.$var3.$var4;
    if($var3==null){$var2 = strtoupper($var2);
					$this->Session->write('pista', $var2);
					$Tfilas=$this->v_buscar_muebles->findCount(" cod_seccion!=0 and (deno_seccion LIKE '%$var2%')");
					        if($Tfilas!=0){
					        	$pagina=1;
					        	$Tfilas=(int)ceil($Tfilas/100);
					        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
								$this->set('total_paginas',$Tfilas);
								$this->set('pagina_actual',$pagina);
								$this->set('ultimo',$Tfilas);
					     	    $datos_filas=$this->v_buscar_muebles->findAll(" cod_seccion!=0 and (deno_seccion LIKE '%$var2%')   ",null,"cod_tipo,cod_grupo,cod_subgrupo,cod_seccion ASC",100,1,null);
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
						$Tfilas=$this->v_buscar_muebles->findCount(" cod_seccion!=0 and (deno_seccion LIKE '%$var22%')");
						        if($Tfilas!=0){
						        	$pagina=$var3;
						        	$Tfilas=(int)ceil($Tfilas/100);
						        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
									$this->set('total_paginas',$Tfilas);
									$this->set('pagina_actual',$pagina);
									$this->set('ultimo',$Tfilas);
						     	    $datos_filas=$this->v_buscar_muebles->findAll(" cod_seccion!=0 and (deno_seccion LIKE '%$var22%')  ",null,"cod_tipo,cod_grupo,cod_subgrupo,cod_seccion ASC",100,$pagina,null);
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

function seleccion_busqueda_venta($var1=null, $var2=null, $var3=null,$var4=null){
	//echo $var1.$var2.$var3.$var4;
$this->layout="ajax";
$this->Session->write('cod_tipo1',$var1);
$this->Session->write('cod_grupo1',$var2);
$this->Session->write('cod_subgrupo1',$var3);
$this->Session->write('cod_seccion1',$var4);


	//		$cod_tipo 		=  $this->Session->read('cod_tipo1');
	//		$cod_grupo 		=  $this->Session->read('cod_grupo1');
	//		$cod_subgrupo 	=  $this->Session->read('cod_subgrupo1');
	//		$cod_seccion 	=  $this->Session->read('cod_seccion1');
	//		echo 'tipo='.$cod_tipo.'grupo='.$cod_grupo.'subgrupo='.$cod_subgrupo.'seccion='.$cod_seccion.'numero=';



$cod_seccion = $this->Session->read('cod_seccion1');
$resultado=$this->v_buscar_muebles->findAll('cod_tipo='.$var1.' and cod_grupo='.$var2.' and cod_subgrupo='.$var3.' and cod_seccion='.$var4);
$a=$resultado[0]['v_buscar_muebles']['cod_tipo'];
$b=$this->AddCeroR($resultado[0]['v_buscar_muebles']['cod_grupo']);
$c=$this->AddCeroR($resultado[0]['v_buscar_muebles']['cod_subgrupo']);
$d=mascara_tres($resultado[0]['v_buscar_muebles']['cod_seccion']);
	$ss=$this->cimd03_inventario_numero->findAll($this->SQLCA().' and cod_tipo='.$var1.' and cod_grupo='.$var2.' and cod_subgrupo='.$var3.' and cod_seccion='.$var4,array('numero_identificacion'));
 	 if($ss==null){
     	$new_numero=1;
     }else{
     	$new_numero=$ss[0]["cimd03_inventario_numero"]["numero_identificacion"]+1;
     }
	$numero=$this->mascara_ocho($new_numero);
	$num_ima=$var1.$var2.$var3.$var4.$new_numero;

					echo "<script>";
					    echo "document.getElementById('cod_tipo').value='".$a."';   ";
					    echo "document.getElementById('deno_tipo').value='".$resultado[0]['v_buscar_muebles']['deno_tipo']."';   ";
					    echo "document.getElementById('cod_grupo').value='".$b."';   ";
					    echo "document.getElementById('deno_grupo').value='".$resultado[0]['v_buscar_muebles']['deno_grupo']."';   ";
					    echo "document.getElementById('cod_subgrupo').value='".$c."';   ";
					    echo "document.getElementById('deno_subgrupo').value='".$resultado[0]['v_buscar_muebles']['deno_subgrupo']."';   ";
					    echo "document.getElementById('cod_seccion').value='".$d."';   ";
					    echo "document.getElementById('deno_seccion').value='".$resultado[0]['v_buscar_muebles']['deno_seccion']."';   ";
					    echo "document.getElementById('especificaciones').value='".$resultado[0]['v_buscar_muebles']['especificaciones']."';   ";
					    echo "document.getElementById('segunda_ventana').disabled=false;";
					    echo "document.getElementById('automatico_1').disabled=false;";
					    echo "document.getElementById('automatico_2').disabled=false;";
					    echo "document.getElementById('automatico_1').checked=true;   ";
					    echo "document.getElementById('numero_identificacion').value='".$numero."';   ";
					    echo "ver_documento('/cimp03_inventario_muebles/imagen/$num_ima','aqui_imagen_mueble');";
					echo "</script>";
$this->funcion();
$this->render("funcion");



}//fin function


function funcion($var1=null, $var2=null, $var3=null){

$this->layout="ajax";


}//fin function
/*
function lista_registrada($pag=null){
$this->layout="ajax";
$var1=   $this->Session->read('cod_tipo1');
$var2 =  $this->Session->read('cod_grupo1');
$var3 =  $this->Session->read('cod_subgrupo1');
$var4 =  $this->Session->read('cod_seccion1');
    if($pag==null){
					$Tfilas=$this->v_inventario_muebles_todo->findCount($this->SQLCA().' and cod_tipo='.$var1.' and cod_grupo='.$var2.' and cod_subgrupo='.$var3.' and cod_seccion='.$var4);
					        if($Tfilas!=0){
					        	$pagina=1;
					        	$Tfilas=(int)ceil($Tfilas/100);
					        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
								$this->set('total_paginas',$Tfilas);
								$this->set('pagina_actual',$pagina);
								$this->set('ultimo',$Tfilas);
					     	    $datos_filas=$this->v_inventario_muebles_todo->findAll($this->SQLCA().' and cod_tipo='.$var1.' and cod_grupo='.$var2.' and cod_subgrupo='.$var3.' and cod_seccion='.$var4,null,"cod_tipo,cod_grupo,cod_subgrupo,cod_seccion ASC",100,1,null);
						        $this->set("datosFILAS",$datos_filas);
						        $this->set('siguiente',$pagina+1);
								$this->set('anterior',$pagina-1);
								$this->bt_nav($Tfilas,$pagina);
								$this->set('pagina',$pagina);
					          }else{
					        	$this->set("datosFILAS",'');
					          }
            }else{
						$Tfilas=$this->v_inventario_muebles_todo->findCount($this->SQLCA().' and cod_tipo='.$var1.' and cod_grupo='.$var2.' and cod_subgrupo='.$var3.' and cod_seccion='.$var4);
						        if($Tfilas!=0){
						        	$pagina=$pag;
						        	$Tfilas=(int)ceil($Tfilas/100);
						        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
									$this->set('total_paginas',$Tfilas);
									$this->set('pagina_actual',$pagina);
									$this->set('ultimo',$Tfilas);
						     	    $datos_filas=$this->v_inventario_muebles_todo->findAll($this->SQLCA().' and cod_tipo='.$var1.' and cod_grupo='.$var2.' and cod_subgrupo='.$var3.' and cod_seccion='.$var4,null,"cod_tipo,cod_grupo,cod_subgrupo,cod_seccion ASC",100,$pagina,null);
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
$var4 =  $this->Session->read('cod_seccion1');//echo 'a';
			if($pagina==null){
					$Tfilas=$this->v_inventario_muebles_todo->findCount($this->SQLCA().' and cod_tipo='.$var1.' and cod_grupo='.$var2.' and cod_subgrupo='.$var3.' and cod_seccion='.$var4);
					        if($Tfilas!=0){
					        	$pagina=1;
					        	$Tfilas=(int)ceil($Tfilas/100);
					        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
								$this->set('total_paginas',$Tfilas);
								$this->set('pagina_actual',$pagina);
								$this->set('ultimo',$Tfilas);
						        $datos_filas=$this->v_inventario_muebles_todo->findAll($this->SQLCA().' and cod_tipo='.$var1.' and cod_grupo='.$var2.' and cod_subgrupo='.$var3.' and cod_seccion='.$var4,null,"cod_tipo,cod_grupo,cod_subgrupo,cod_seccion ASC",100,$pagina,null);
						        $this->set("datosFILAS",$datos_filas);
						        $this->set('siguiente',$pagina+1);
								$this->set('anterior',$pagina-1);
								$this->bt_nav($Tfilas,$pagina);
					          }else{
					        	$this->set("datosFILAS",'');
					          }
            }else{
						$Tfilas=$this->v_inventario_muebles_todo->findCount($this->SQLCA().' and cod_tipo='.$var1.' and cod_grupo='.$var2.' and cod_subgrupo='.$var3.' and cod_seccion='.$var4);
						        if($Tfilas!=0){
						        	$pagina=$pagina;
						        	$Tfilas=(int)ceil($Tfilas/100);
						        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
									$this->set('total_paginas',$Tfilas);
									$this->set('pagina_actual',$pagina);
									$this->set('ultimo',$Tfilas);
							        $datos_filas=$this->v_inventario_muebles_todo->findAll($this->SQLCA().' and cod_tipo='.$var1.' and cod_grupo='.$var2.' and cod_subgrupo='.$var3.' and cod_seccion='.$var4,null,"cod_tipo,cod_grupo,cod_subgrupo,cod_seccion ASC",100,$pagina,null);
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




function consulta2($numero=null,$cod_tipo=null,$cod_grupo=null,$cod_subgrupo=null,$cod_seccion=null,$ficha=null){
 	  $this->layout = "ajax";
 	  $cod_presi = $this->Session->read('SScodpresi');
	  $cod_entidad = $this->Session->read('SScodentidad');
	  $cod_tipo_inst = $this->Session->read('SScodtipoinst');
	  $cod_inst = $this->Session->read('SScodinst');
	  $cod_dep = $this->Session->read('SScoddep');
	  $modulo = $this->Session->read('Modulo');

	  if($cod_tipo == null && $cod_grupo == null && $cod_subgrupo == null && $cod_seccion == null){
	  		$cod_tipo 		=  $this->Session->read('cod_tipo1');
			$cod_grupo 		=  $this->Session->read('cod_grupo1');
			$cod_subgrupo 	=  $this->Session->read('cod_subgrupo1');
			$cod_seccion 	=  $this->Session->read('cod_seccion1');
	  }

	  $c = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep." and numero_identificacion=".$numero;
          	 $veri=$this->v_inventario_muebles_todo->findCount($c.' and cod_tipo='.$cod_tipo.' and cod_grupo='.$cod_grupo.' and cod_subgrupo='.$cod_subgrupo.' and cod_seccion='.$cod_seccion);
          	 //echo $veri;
          	 if($veri > 0){
          	 $datacpcp01=$this->v_inventario_muebles_todo->findAll($c.' and cod_tipo='.$cod_tipo.' and cod_grupo='.$cod_grupo.' and cod_subgrupo='.$cod_subgrupo.' and cod_seccion='.$cod_seccion);
          	 
          	 $this->set('datos',$datacpcp01);
	          	 if($ficha != null){
	          	 	if($datacpcp01[0]['v_inventario_muebles_todo']['cod_tipo_incorporacion'] == 3){

	          	 		$sql = "SELECT b.denominacion 
	          	 				FROM 
	          	 					cscd04_ordencompra_encabezado AS a 
	          	 				INNER join
	          	 					cpcd02 AS b ON
	          	 					a.rif = b.rif 
	          	 				WHERE 
		          	 				a.cod_dep = $cod_dep AND
		          	 				a.numero_orden_compra = ".$datacpcp01[0]['v_inventario_muebles_todo']['numero_orden_compra']." AND
		          	 				a.ano_orden_compra = ".$datacpcp01[0]['v_inventario_muebles_todo']['ano_orden_compra'];

		          	 	$proveedor = $this->v_inventario_muebles_todo->execute($sql);
		          	 	
		          	 	$this->set('proveedor', $proveedor[0][0]['denominacion']);
	          	 	}
	          	 	$this->render('consulta_ficha_mueble');
	          	 }
          	 }else{//echo 'menor';
				echo "<script>";
					echo "document.getElementById('numero_identificacion').value='".$numero."';   ";
				echo "</script>";
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
					$Tfilas=$this->v_inventario_muebles_todo->findCount(" (buscar LIKE '%$var2%') and ".$this->SQLCA());
					        if($Tfilas!=0){
					        	$pagina=1;
					        	$Tfilas=(int)ceil($Tfilas/100);
					        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
								$this->set('total_paginas',$Tfilas);
								$this->set('pagina_actual',$pagina);
								$this->set('ultimo',$Tfilas);
					     	    $datos_filas=$this->v_inventario_muebles_todo->findAll(" (buscar LIKE '%$var2%') and ".$this->SQLCA(),null,"numero_identificacion ASC",100,1,null);
						        $this->set("datosFILAS",$datos_filas);
						        $this->set('siguiente',$pagina+1);
								$this->set('anterior',$pagina-1);
								$this->bt_nav($Tfilas,$pagina);
					          }else{
					        	$this->set("datosFILAS",'');
					          }
    }else{
		$var2 = $this->Session->read('pista');
		$var2 = strtoupper($var2);
		//$sql   = " (numero_identificacion::text LIKE '%$var22%')  or   ";
		$Tfilas=$this->v_inventario_muebles_todo->findCount(" (buscar LIKE '%$var2%') and ".$this->SQLCA());
		        if($Tfilas!=0){
		        	$pagina=$var3;
		        	$Tfilas=(int)ceil($Tfilas/100);
		        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
					$this->set('total_paginas',$Tfilas);
					$this->set('pagina_actual',$pagina);
					$this->set('ultimo',$Tfilas);
		     	    $datos_filas=$this->v_inventario_muebles_todo->findAll(" (buscar LIKE '%$var2%') and ".$this->SQLCA(),null,"numero_identificacion ASC",100,$pagina,null);
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
	$cod_tipo 		=  $this->Session->read('cod_tipo1');
	$cod_grupo 		=  $this->Session->read('cod_grupo1');
	$cod_subgrupo 	=  $this->Session->read('cod_subgrupo1');
	$cod_seccion 	=  $this->Session->read('cod_seccion1');
	$si=$this->v_inventario_muebles_todo->findCount($this->SQLCA().' and numero_identificacion='.$numero.' and cod_tipo='.$cod_tipo.' and cod_grupo='.$cod_grupo.' and cod_subgrupo='.$cod_subgrupo.' and cod_seccion='.$cod_seccion);

	if($si > 0){
		 	$exi='si';
		 	$this->set('exi',$exi);
		 	$this->set('numero',$numero);
	}else{
			$var1=   $this->Session->read('cod_tipo1');
			$var2 =  $this->Session->read('cod_grupo1');
			$var3 =  $this->Session->read('cod_subgrupo1');
			$var4 =  $this->Session->read('cod_seccion1');
			$exi='no';
			$this->set('exi',$exi);//echo $numero;
			$this->set('numero',$numero);
			//$this->set('var1',$var1);
			//$this->set('var2',$var2);
			//$this->set('var3',$var3);
			//$this->set('var4',$var4);
	}
}

function imagen($numero=null){
	$this->layout="ajax";
	$this->set('numero',$numero);
}

function a_c($var = null){
	$this->layout="ajax";

	$longitud = strlen($var);
	echo $longitud;

	if($var != null && $var != '' && $longitud == 4){
		$this->Session->write('a_compra', $var);
		echo "<script>";
			echo "document.getElementById('numero_compra').value='';   ";
			echo "document.getElementById('fecha_compra').value='';   ";
			echo "document.getElementById('proveedor').value='';   ";
			echo "document.getElementById('numero_compra').disabled='';   ";
		echo "</script>";
	}else{
		$this->Session->write('a_compra', 'no');
		echo "<script>";
			echo "document.getElementById('ano_compra').value='';   ";
			echo "document.getElementById('numero_compra').value='';   ";
			echo "document.getElementById('fecha_compra').value='';   ";
			echo "document.getElementById('proveedor').value='';   ";
			echo "document.getElementById('numero_compra').disabled='true';   ";
		echo "</script>";
	}
	if($longitud != 4){
		$this->set('errorMessage', 'Ingrese un a&ntilde;o valido que tenga m&iacute;nimo 4 d&iacute;gitos');
	}
}

function n_c($var = null){
	$this->layout="ajax";
	$ano 			=   $this->Session->read('a_compra');
	$compras 		= $this->cscd04_ordencompra_encabezado->findAll($this->condicion().' and ano_orden_compra ='.$ano.' and numero_orden_compra ='.$var);
	//pr($compras);
	$fecha 			= $compras[0]['cscd04_ordencompra_encabezado']['fecha_orden_compra'];
	$fecha 			= cambia_fecha($fecha);
	$rif 			= $compras[0]['cscd04_ordencompra_encabezado']['rif'];
	$proveedores	= $this->cpcd02->findAll("rif = '$rif'");
	$deno 			= $proveedores[0]['cpcd02']['denominacion'];
	//echo $deno;
	echo "<script>";
			echo "document.getElementById('fecha_compra').value='$fecha';   ";
			echo "document.getElementById('proveedor').value='$deno';   ";
	echo "</script>";




}



function select6($select=null,$var=null) {
	$this->layout = "ajax";
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
			echo "<script>";
				echo "document.getElementById('c_seleccion2_6').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('c_seleccion2_7').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('c_seleccion2_8').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('c_seleccion2_9').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('c_seleccion2_10').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('c_seleccion2_11').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('c_seleccion2_12').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('c_seleccion2_13').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('d_seleccion2_6').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('d_seleccion2_7').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('d_seleccion2_8').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('d_seleccion2_9').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('d_seleccion2_10').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('d_seleccion2_11').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('d_seleccion2_12').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('d_seleccion2_13').innerHTML='<input type=text  size=10% class=inputtext />';  ";
        	echo "</script>";
		  $this->set('SELECT','direccions');
		  $this->set('codigo','dependencia');
		  $this->set('seleccion','');
		  $this->set('n',6);
		  $this->Session->write('cinst2',$var);
		  $cond2 ="cod_institucion=".$var;
		  $lista=  $this->cugd02_dependencia->generateList($cond2, 'cod_dependencia ASC', null, '{n}.cugd02_dependencia.cod_dependencia', '{n}.cugd02_dependencia.denominacion');
          $this->concatena($lista,'vector');
 		break;
		case 'direccions':
		echo "<script>";
		  		echo "document.getElementById('c_seleccion2_7').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('c_seleccion2_8').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('c_seleccion2_9').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('c_seleccion2_10').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('c_seleccion2_11').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('c_seleccion2_12').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('c_seleccion2_13').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('d_seleccion2_7').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('d_seleccion2_8').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('d_seleccion2_9').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('d_seleccion2_10').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('d_seleccion2_11').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('d_seleccion2_12').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('d_seleccion2_13').innerHTML='<input type=text  size=10% class=inputtext />';  ";
        	echo "</script>";
		  $this->set('SELECT','coordinacion');
		  $this->set('codigo','direccions');
		  $this->set('seleccion','');
		  $this->set('n',7);
		  $cinst =  $this->Session->read('cinst2');
		  $this->Session->write('cdepe2',$var);
		  $cond2 ="cod_institucion=".$cinst." and cod_dependencia=".$var;
		  $lista = $this->cugd02_direccionsuperior->generateList($cond2, 'cod_dir_superior ASC', null, '{n}.cugd02_direccionsuperior.cod_dir_superior', '{n}.cugd02_direccionsuperior.denominacion');
          $this->concatena($lista,'vector');
		break;
		case 'coordinacion':
		echo "<script>";
		  		echo "document.getElementById('c_seleccion2_8').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('c_seleccion2_9').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('c_seleccion2_10').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('c_seleccion2_11').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('c_seleccion2_12').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('c_seleccion2_13').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('d_seleccion2_8').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('d_seleccion2_9').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('d_seleccion2_10').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('d_seleccion2_11').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('d_seleccion2_12').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('d_seleccion2_13').innerHTML='<input type=text  size=10% class=inputtext />';  ";
        	echo "</script>";
		  $this->set('SELECT','secretaria');
		  $this->set('codigo','coordinacion');
		  $this->set('seleccion','');
		  $this->set('n',8);
		  $cinst =  $this->Session->read('cinst2');
		  $cdepe =  $this->Session->read('cdepe2');
		  $this->Session->write('cdirs2',$var);
		  $cond2 ="cod_institucion=".$cinst." and cod_dependencia=".$cdepe." and cod_dir_superior=".$var;
		  $lista=  $this->cugd02_coordinacion->generateList($cond2, 'cod_coordinacion ASC', null, '{n}.cugd02_coordinacion.cod_coordinacion', '{n}.cugd02_coordinacion.denominacion');
          $this->concatena($lista,'vector');
		break;
		case 'secretaria':
		echo "<script>";
		  		echo "document.getElementById('c_seleccion2_9').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('c_seleccion2_10').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('c_seleccion2_11').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('c_seleccion2_12').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('c_seleccion2_13').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('d_seleccion2_9').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('d_seleccion2_10').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('d_seleccion2_11').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('d_seleccion2_12').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('d_seleccion2_13').innerHTML='<input type=text  size=10% class=inputtext />';  ";
        	echo "</script>";
		  $this->set('SELECT','direccion');
		  $this->set('codigo','secretaria');
		  $this->set('seleccion','');
		  $this->set('n',9);
		  $cinst =  $this->Session->read('cinst2');
		  $cdepe =  $this->Session->read('cdepe2');
		  $cdirs =  $this->Session->read('cdirs2');
		  $this->Session->write('ccoor2',$var);
		  $cond2 ="cod_institucion=".$cinst." and cod_dependencia=".$cdepe." and cod_dir_superior=".$cdirs." and cod_coordinacion=".$var;
		  $lista=  $this->cugd02_secretaria->generateList($cond2, 'cod_secretaria ASC', null, '{n}.cugd02_secretaria.cod_secretaria', '{n}.cugd02_secretaria.denominacion');
			$this->concatena($lista,'vector');
		break;
		case 'direccion':
		echo "<script>";
		  		echo "document.getElementById('c_seleccion2_10').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('c_seleccion2_11').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('c_seleccion2_12').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('c_seleccion2_13').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('d_seleccion2_10').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('d_seleccion2_11').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('d_seleccion2_12').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('d_seleccion2_13').innerHTML='<input type=text  size=10% class=inputtext />';  ";
        	echo "</script>";
		  $this->set('SELECT','division');
		  $this->set('codigo','direccion');
		  $this->set('seleccion','');
		  $this->set('n',10);
		  $cinst =  $this->Session->read('cinst2');
		  $cdepe =  $this->Session->read('cdepe2');
		  $cdirs =  $this->Session->read('cdirs2');
		  $ccoor =  $this->Session->read('ccoor2');
		  $this->Session->write('csecr2',$var);
		  $cond2 ="cod_institucion=".$cinst." and cod_dependencia=".$cdepe." and cod_dir_superior=".$cdirs." and cod_coordinacion=".$ccoor." and cod_secretaria=".$var;
		  $lista=  $this->cugd02_direccion->generateList($cond2, 'cod_direccion ASC', null, '{n}.cugd02_direccion.cod_direccion', '{n}.cugd02_direccion.denominacion');
          $this->concatena($lista,'vector');
 		break;
 		case 'division':
		echo "<script>";
		  		echo "document.getElementById('c_seleccion2_11').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('c_seleccion2_12').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('c_seleccion2_13').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('d_seleccion2_11').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('d_seleccion2_12').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('d_seleccion2_13').innerHTML='<input type=text  size=10% class=inputtext />';  ";
        	echo "</script>";
		  $this->set('SELECT','departamento');
		  $this->set('codigo','division');
		  $this->set('seleccion','');
		  $this->set('n',11);
		  $cinst =  $this->Session->read('cinst2');
		  $cdepe =  $this->Session->read('cdepe2');
		  $cdirs =  $this->Session->read('cdirs2');
		  $ccoor =  $this->Session->read('ccoor2');
		  $csecr =  $this->Session->read('csecr2');
		  $this->Session->write('cdire2',$var);
		  $cond2 ="cod_institucion=".$cinst." and cod_dependencia=".$cdepe." and cod_dir_superior=".$cdirs." and cod_coordinacion=".$ccoor." and cod_secretaria=".$csecr." and cod_direccion=".$var;
		  $lista=  $this->cugd02_division->generateList($cond2, 'cod_division ASC', null, '{n}.cugd02_division.cod_division', '{n}.cugd02_division.denominacion');
          if($lista!=null){
          $this->concatena($lista,'vector');
          }else{
          	echo "<script>";
		  		echo "document.getElementById('c_seleccion2_11').innerHTML='<input type=text value=00 name=data[cimp03_inventario_muebles][cod_division] size=10% class=inputtext readonly style=text-align:center />';  ";
		  		echo "document.getElementById('c_seleccion2_12').innerHTML='<input type=text value=00 name=data[cimp03_inventario_muebles][cod_departamento] size=10% class=inputtext readonly style=text-align:center />';  ";
		  		echo "document.getElementById('c_seleccion2_13').innerHTML='<input type=text value=00 name=data[cimp03_inventario_muebles][cod_oficina] size=10% class=inputtext readonly style=text-align:center />';  ";
		  		echo "document.getElementById('d_seleccion2_11').innerHTML='<input type=text value=N/A size=10% class=inputtext readonly />';  ";
		  		echo "document.getElementById('d_seleccion2_12').innerHTML='<input type=text value=N/A size=10% class=inputtext readonly />';  ";
		  		echo "document.getElementById('d_seleccion2_13').innerHTML='<input type=text value=N/A size=10% class=inputtext readonly />';  ";
		  		echo "document.getElementById('sel2_11').innerHTML='<select  class=select100 id=x2_11><option value=00 selected>00</select>';  ";
		  		echo "document.getElementById('sel2_12').innerHTML='<select  class=select100 id=x2_12><option value=00 selected>00</select>';  ";
		  		echo "document.getElementById('sel2_13').innerHTML='<select  class=select100 id=x2_13><option value=00 selected>00</select>';  ";
        	echo "</script>";
          }


 		break;
		case 'departamento':
		echo "<script>";
		  		echo "document.getElementById('c_seleccion2_12').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('c_seleccion2_13').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('d_seleccion2_12').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('d_seleccion2_13').innerHTML='<input type=text  size=10% class=inputtext />';  ";
        	echo "</script>";
		  $this->set('SELECT','oficina');
		  $this->set('codigo','departamento');
		  $this->set('seleccion','');
		  $this->set('n',12);
		  $cinst =  $this->Session->read('cinst2');
		  $cdepe =  $this->Session->read('cdepe2');
		  $cdirs =  $this->Session->read('cdirs2');
		  $ccoor =  $this->Session->read('ccoor2');
		  $csecr =  $this->Session->read('csecr2');
		  $cdire =  $this->Session->read('cdire2');
		  $this->Session->write('cdivi2',$var);
		  $cond2 ="cod_institucion=".$cinst." and cod_dependencia=".$cdepe." and cod_dir_superior=".$cdirs." and cod_coordinacion=".$ccoor." and cod_secretaria=".$csecr." and cod_direccion=".$cdire." and cod_division=".$var;
		  $lista = $this->cugd02_departamento->generateList($cond2, 'cod_departamento ASC', null, '{n}.cugd02_departamento.cod_departamento', '{n}.cugd02_departamento.denominacion');
          if($lista!=null){
          $this->concatena($lista,'vector');
          }else{
          	echo "<script>";
		  		echo "document.getElementById('c_seleccion2_12').innerHTML='<input type=text name=data[cimp03_inventario_muebles][cod_departamento] value=00  size=10% class=inputtext readonly style=text-align:center />';  ";
		  		echo "document.getElementById('c_seleccion2_13').innerHTML='<input type=text name=data[cimp03_inventario_muebles][cod_oficina] value=00  size=10% class=inputtext readonly style=text-align:center />';  ";
		  		echo "document.getElementById('d_seleccion2_12').innerHTML='<input type=text value=N/A size=10% class=inputtext readonly />';  ";
		  		echo "document.getElementById('d_seleccion2_13').innerHTML='<input type=text value=N/A size=10% class=inputtext readonly />';  ";
		  		echo "document.getElementById('sel2_12').innerHTML='<select  class=select100 id=x2_12><option value=00 selected>00</select>';  ";
		  		echo "document.getElementById('sel2_13').innerHTML='<select  class=select100 id=x2_13><option value=00 selected>00</select>';  ";
        	echo "</script>";
          }
		break;
		case 'oficina':
		echo "<script>";
		  		echo "document.getElementById('c_seleccion2_13').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('d_seleccion2_13').innerHTML='<input type=text  size=10% class=inputtext />';  ";
        	echo "</script>";
		  $this->set('SELECT','oficina');
		  $this->set('codigo','oficina');
		  $this->set('seleccion','');
		  $this->set('n',13);
		  $cinst =  $this->Session->read('cinst2');
		  $cdepe =  $this->Session->read('cdepe2');
		  $cdirs =  $this->Session->read('cdirs2');
		  $ccoor =  $this->Session->read('ccoor2');
		  $csecr =  $this->Session->read('csecr2');
		  $cdire =  $this->Session->read('cdire2');
		  $cdivi =  $this->Session->read('cdivi2');
		  $this->Session->write('cdepa2',$var);
		  $cond2 ="cod_institucion=".$cinst." and cod_dependencia=".$cdepe." and cod_dir_superior=".$cdirs." and cod_coordinacion=".$ccoor." and cod_secretaria=".$csecr." and cod_direccion=".$cdire." and cod_division=".$cdivi." and cod_departamento=".$var;
		  $lista=  $this->cugd02_oficina->generateList($cond2, 'cod_oficina ASC', null, '{n}.cugd02_oficina.cod_oficina', '{n}.cugd02_oficina.denominacion');
          if($lista!=null){
          $this->concatena($lista,'vector');
          }else{
          	echo "<script>";
		  		echo "document.getElementById('c_seleccion2_13').innerHTML='<input type=text name=data[cimp03_inventario_muebles][cod_oficina] value=00  size=10% class=inputtext readonly style=text-align:center />';  ";
		  		echo "document.getElementById('d_seleccion2_13').innerHTML='<input type=text value=N/A size=10% class=inputtext readonly />';  ";
		  		echo "document.getElementById('sel2_13').innerHTML='<select  class=select100 id=x2_13><option value=00 selected>00</select>';  ";
        	echo "</script>";
          };
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
}//fin select6 codigos


function mostrar77($select=null,$var=null) {
	$this->layout = "ajax";
		if( $var!=null){
	switch($select){
		case 'institucion':
		  $this->Session->write('dinst2',$var);
		   $cond2 ="cod_institucion=".$var;
		  $a=  $this->cugd02_dependencia->findAll($cond2);
          $e=$a[0]['cugd02_dependencia']['denominacion'];
         $this->set('var',$e);
		break;
		case 'dependencia':
		  $dinst=  $this->Session->read('dinst2');
		  $this->Session->write('ddepe2',$var);
		  $cond2 ="cod_institucion=".$dinst." and cod_dependencia=".$var;
		  $a=  $this->cugd02_dependencia->findAll($cond2);
          $e=$a[0]['cugd02_dependencia']['denominacion'];
          $this->set('var',$e);
		break;
		case 'direccions':
		  $dinst=  $this->Session->read('dinst2');
		  $ddepe =  $this->Session->read('ddepe2');
		  $this->Session->write('ddirs2',$var);
		  $cond2 ="cod_institucion=".$dinst." and cod_dependencia=".$ddepe." and cod_dir_superior=".$var;
		  $a=  $this->cugd02_direccionsuperior->findAll($cond2);
          $e= $a[0]['cugd02_direccionsuperior']['denominacion'];
          $this->set('var',$e);
		break;
		case 'coordinacion':
		  $dinst=  $this->Session->read('dinst2');
		  $ddepe =  $this->Session->read('ddepe2');
		  $ddirs =  $this->Session->read('ddirs2');
		  $this->Session->write('dcoor2',$var);
		   $cond2 ="cod_institucion=".$dinst." and cod_dependencia=".$ddepe." and cod_dir_superior=".$ddirs." and cod_coordinacion=".$var;
		  $a=  $this->cugd02_coordinacion->findAll($cond2);
          $e= $a[0]['cugd02_coordinacion']['denominacion'];
           $this->set('var',$e);
		break;
		case 'secretaria':
		  $dinst=  $this->Session->read('dinst2');
		  $ddepe =  $this->Session->read('ddepe2');
		  $ddirs =  $this->Session->read('ddirs2');
		  $dcoor =  $this->Session->read('dcoor2');
		  $this->Session->write('dsecr2',$var);
		   $cond2 ="cod_institucion=".$dinst." and cod_dependencia=".$ddepe." and cod_dir_superior=".$ddirs." and cod_coordinacion=".$dcoor." and cod_secretaria=".$var;
		  $a=  $this->cugd02_secretaria->findAll($cond2);
          $e=$a[0]['cugd02_secretaria']['denominacion'];
         $this->set('var',$e);
		break;
		case 'direccion':
		  //$ano =  $this->Session->read('ano');
		  $dinst=  $this->Session->read('dinst2');
		  $ddepe =  $this->Session->read('ddepe2');
		  $ddirs =  $this->Session->read('ddirs2');
		  $dcoor =  $this->Session->read('dcoor2');
		  $dsecr =  $this->Session->read('dsecr2');
		  $this->Session->write('ddire2',$var);
		  $cond2 ="cod_institucion=".$dinst." and cod_dependencia=".$ddepe." and cod_dir_superior=".$ddirs." and cod_coordinacion=".$dcoor." and cod_secretaria=".$dsecr." and cod_direccion=".$var;
		  $a=  $this->cugd02_direccion->findAll($cond2);
          $e=$a[0]['cugd02_direccion']['denominacion'];
          $this->set('var',$e);
		break;
		case 'division':
		  $dinst=  $this->Session->read('dinst2');
		  $ddepe =  $this->Session->read('ddepe2');
		  $ddirs =  $this->Session->read('ddirs2');
		  $dcoor =  $this->Session->read('dcoor2');
		  $dsecr =  $this->Session->read('dsecr2');
		  $ddire =  $this->Session->read('ddire2');
		  $this->Session->write('ddivi2',$var);

		  $cond2 ="cod_institucion=".$dinst." and cod_dependencia=".$ddepe." and cod_dir_superior=".$ddirs." and cod_coordinacion=".$dcoor." and cod_secretaria=".$dsecr." and cod_direccion=".$ddire." and cod_division=".$var;
		  $a=  $this->cugd02_division->findAll($cond2);
          if($a!=null){
          $e=$a[0]['cugd02_division']['denominacion'];
          $this->set('var',$e);
          }else{
          	$this->set('var','N/A');
          }
		break;
		case 'departamento':
		  $dinst=   $this->Session->read('dinst2');
		  $ddepe =  $this->Session->read('ddepe2');
		  $ddirs =  $this->Session->read('ddirs2');
		  $dcoor =  $this->Session->read('dcoor2');
		  $dsecr =  $this->Session->read('dsecr2');
		  $ddire =  $this->Session->read('ddire2');
		  $ddivi =  $this->Session->read('ddivi2');
		  $this->Session->write('ddepa2',$var);
		  $cond2 ="cod_institucion=".$dinst." and cod_dependencia=".$ddepe." and cod_dir_superior=".$ddirs." and cod_coordinacion=".$dcoor." and cod_secretaria=".$dsecr." and cod_direccion=".$ddire." and cod_division=".$ddivi." and cod_departamento=".$var;
		  $a=  $this->cugd02_departamento->findAll($cond2);
          if($a!=null){
          $e=$a[0]['cugd02_departamento']['denominacion'];
          $this->set('var',$e);
          }else{
          	$this->set('var','N/A');
          }
		break;
		case 'oficina':
		  $dinst=   $this->Session->read('dinst2');
		  $ddepe =  $this->Session->read('ddepe2');
		  $ddirs =  $this->Session->read('ddirs2');
		  $dcoor =  $this->Session->read('dcoor2');
		  $dsecr =  $this->Session->read('dsecr2');
		  $ddire =  $this->Session->read('ddire2');
		  $ddivi =  $this->Session->read('ddivi2');
		  $ddepa =  $this->Session->read('ddepa2');
		  $this->Session->write('dofic2',$var);
		  $cond2 ="cod_institucion=".$dinst." and cod_dependencia=".$ddepe." and cod_dir_superior=".$ddirs." and cod_coordinacion=".$dcoor." and cod_secretaria=".$dsecr." and cod_direccion=".$ddire." and cod_division=".$ddivi." and cod_departamento=".$ddepa." and cod_oficina=".$var;
		  $a=  $this->cugd02_oficina->findAll($cond2);
          if($a!=null){
          $e=$a[0]['cugd02_oficina']['denominacion'];
          $this->set('var',$e);
          }else{
          	$this->set('var','N/A');
          }
		break;
	}//fin wsitch
	}else{
		echo "";
	}
}//fin mostrar77

function mostrar88($select=null,$var=null) {
	$this->layout = "ajax";
		if( $var!=null){
	switch($select){
		case 'institucion':
          	echo "<input type='text' name='data[cimp03_inventario_muebles][cod_institucion2]' value='".$this->AddCero3($var)."' id='cod_institucion2'  class='inputtext' readonly=readonly  style='text-align:center'/> ";
		break;

		case 'dependencia':
			 echo "<input type='text' name='data[cimp03_inventario_muebles][cod_dependencia2]' value='".$this->AddCero3($var)."' id='cod_dependencia2'  class='inputtext' readonly=readonly  style='text-align:center'/>";
		break;

		case 'direccions':
           echo "<input type='text' name='data[cimp03_inventario_muebles][cod_direccions2]' value='".$this->AddCero3($var)."' id='cod_direccions2' class='inputtext' readonly=readonly  style='text-align:center'/>";
		break;

		case 'coordinacion':
           echo "<input type='text' name='data[cimp03_inventario_muebles][cod_coordinacion2]' value='".$this->AddCero3($var)."' id='cod_coordinacion2' class='inputtext' readonly=readonly  style='text-align:center'/>";
		break;
		case 'secretaria':
          	echo "<input type='text' name='data[cimp03_inventario_muebles][cod_secretaria2]' value='".$this->AddCero3($var)."' id='cod_secretaria2'  class='inputtext' readonly=readonly  style='text-align:center'/>";
		break;

		case 'direccion':
			 echo "<input type='text' name='data[cimp03_inventario_muebles][cod_direccion2]' value='".$this->AddCero3($var)."' id='cod_direccion2'  class='inputtext' readonly=readonly  style='text-align:center'/>";
		break;

		case 'division':
			 echo "<input type='text' name='data[cimp03_inventario_muebles][cod_division2]' value='".$this->AddCero3($var)."' id='cod_division2'  class='inputtext' readonly=readonly  style='text-align:center'/>";
		break;

		case 'departamento':
           echo "<input type='text' name='data[cimp03_inventario_muebles][cod_departamento2]' value='".$this->AddCero3($var)."' id='cod_departamento2' class='inputtext' readonly=readonly  style='text-align:center'/>";
		break;

		case 'oficina':
           echo "<input type='text' name='data[cimp03_inventario_muebles][cod_oficina2]' value='".$this->AddCero3($var)."' id='cod_oficina2' class='inputtext' readonly=readonly  style='text-align:center'/>";
		break;

	}//fin wsitch
	}else{
		echo "";
	}
}//fin mostrar88



function cambio_ubicacion_bienes_muebles($var = null){
	$this->layout="ajax";
 	$cod_presi = $this->verifica_SS(1);
	$cod_entidad = $this->verifica_SS(2);
	$cod_tipo_inst = $this->verifica_SS(3);
	$cod_inst = $this->verifica_SS(4);
	$cod_dep = $this->verifica_SS(5);
 	$this->data=null;

 	$this->set('cod_depe',$cod_dep);
 	$this->set('cod_inst',$cod_inst);
	$this->Session->write('cinst',$cod_inst);
	$this->Session->write('dinst',$cod_inst);
	$this->Session->write('cdepe',$cod_dep);
	$this->Session->write('ddepe',$cod_dep);

	$this->Session->write('cinst2',$cod_inst);
	$this->Session->write('dinst2',$cod_inst);
	$this->Session->write('cdepe2',$cod_dep);
	$this->Session->write('ddepe2',$cod_dep);

    $aa = $this->cugd02_institucion->findAll('cod_tipo_institucion='.$cod_tipo_inst.' and cod_institucion='.$cod_inst);
    $ee = $aa[0]['cugd02_institucion']['denominacion'];
    $this->set('deno_inst',$ee);

    $aaa = $this->cugd02_dependencia->findAll('cod_tipo_institucion='.$cod_tipo_inst.' and cod_institucion='.$cod_inst.'and cod_dependencia='.$cod_dep);
    $eee = $aaa[0]['cugd02_dependencia']['denominacion'];
    $this->set('deno_depe',$eee);


  	$tipo = $this->cimd01_clasificacion_tipo->generateList(null,'cod_tipo ASC', null, '{n}.cimd01_clasificacion_tipo.cod_tipo', '{n}.cimd01_clasificacion_tipo.denominacion');
	$this->concatena($tipo, 'tipo');
	$institucion = $this->cugd02_institucion->generateList('cod_tipo_institucion='.$cod_tipo_inst.' and cod_institucion='.$cod_inst,'cod_institucion ASC', null, '{n}.cugd02_institucion.cod_institucion', '{n}.cugd02_institucion.denominacion');
	$this->concatena($institucion, 'institucion');
	$lista=  $this->cugd02_dependencia->generateList('cod_tipo_institucion='.$cod_tipo_inst.' and cod_institucion='.$cod_inst, 'cod_dependencia ASC', null, '{n}.cugd02_dependencia.cod_dependencia', '{n}.cugd02_dependencia.denominacion');
    $this->concatena($lista,'dependencia');
    $cond2 ="cod_tipo_institucion=".$cod_tipo_inst." and cod_institucion=".$cod_inst." and cod_dependencia=".$cod_dep;
	$lista2 = $this->cugd02_direccionsuperior->generateList($cond2, 'cod_dir_superior ASC', null, '{n}.cugd02_direccionsuperior.cod_dir_superior', '{n}.cugd02_direccionsuperior.denominacion');
    $this->concatena($lista2,'dir_superior');
}
function cambio_ubicacion_bienes_muebles_especifico($var = null){
	$this->layout="ajax";
 	$cod_presi = $this->verifica_SS(1);
	$cod_entidad = $this->verifica_SS(2);
	$cod_tipo_inst = $this->verifica_SS(3);
	$cod_inst = $this->verifica_SS(4);
	$cod_dep = $this->verifica_SS(5);
 	$this->data=null;

 	$this->set('cod_depe',$cod_dep);
 	$this->set('cod_inst',$cod_inst);
	$this->Session->write('cinst',$cod_inst);
	$this->Session->write('dinst',$cod_inst);
	$this->Session->write('cdepe',$cod_dep);
	$this->Session->write('ddepe',$cod_dep);

	$this->Session->write('cinst2',$cod_inst);
	$this->Session->write('dinst2',$cod_inst);
	$this->Session->write('cdepe2',$cod_dep);
	$this->Session->write('ddepe2',$cod_dep);

    $aa = $this->cugd02_institucion->findAll('cod_tipo_institucion='.$cod_tipo_inst.' and cod_institucion='.$cod_inst);
    $ee = $aa[0]['cugd02_institucion']['denominacion'];
    $this->set('deno_inst',$ee);

    $aaa = $this->cugd02_dependencia->findAll('cod_tipo_institucion='.$cod_tipo_inst.' and cod_institucion='.$cod_inst.'and cod_dependencia='.$cod_dep);
    $eee = $aaa[0]['cugd02_dependencia']['denominacion'];
    $this->set('deno_depe',$eee);


  	$tipo = $this->cimd01_clasificacion_tipo->generateList(null,'cod_tipo ASC', null, '{n}.cimd01_clasificacion_tipo.cod_tipo', '{n}.cimd01_clasificacion_tipo.denominacion');
	$this->concatena($tipo, 'tipo');
	$institucion = $this->cugd02_institucion->generateList('cod_tipo_institucion='.$cod_tipo_inst.' and cod_institucion='.$cod_inst,'cod_institucion ASC', null, '{n}.cugd02_institucion.cod_institucion', '{n}.cugd02_institucion.denominacion');
	$this->concatena($institucion, 'institucion');
	$lista=  $this->cugd02_dependencia->generateList('cod_tipo_institucion='.$cod_tipo_inst.' and cod_institucion='.$cod_inst, 'cod_dependencia ASC', null, '{n}.cugd02_dependencia.cod_dependencia', '{n}.cugd02_dependencia.denominacion');
    $this->concatena($lista,'dependencia');
    $cond2 ="cod_tipo_institucion=".$cod_tipo_inst." and cod_institucion=".$cod_inst." and cod_dependencia=".$cod_dep;
	$lista2 = $this->cugd02_direccionsuperior->generateList($cond2, 'cod_dir_superior ASC', null, '{n}.cugd02_direccionsuperior.cod_dir_superior', '{n}.cugd02_direccionsuperior.denominacion');
    $this->concatena($lista2,'dir_superior');
}


function guardar_cambio_ubic_bm($var = null){
	$this->layout="ajax";

 	$cod_presi = $this->verifica_SS(1);
	$cod_entidad = $this->verifica_SS(2);
	$cod_tipo_inst = $this->verifica_SS(3);
	$cod_inst = $this->verifica_SS(4);
	$cod_dep = $this->verifica_SS(5);

$cod_institucion = $this->data['cimp03_inventario_muebles']['cod_institucion'];
$cod_dependencia = $this->data['cimp03_inventario_muebles']['cod_dependencia'];
$cod_dir_superior = $this->data['cimp03_inventario_muebles']['cod_direccions'];
$cod_coordinacion = $this->data['cimp03_inventario_muebles']['cod_coordinacion'];
$cod_secretaria = $this->data['cimp03_inventario_muebles']['cod_secretaria'];
$cod_direccion = $this->data['cimp03_inventario_muebles']['cod_direccion'];
$cod_division = $this->data['cimp03_inventario_muebles']['cod_division'];
$cod_departamento = $this->data['cimp03_inventario_muebles']['cod_departamento'];
$cod_oficina = $this->data['cimp03_inventario_muebles']['cod_oficina'];

$cod_institucion2 = $this->data['cimp03_inventario_muebles']['cod_institucion2'];
$cod_dependencia2 = $this->data['cimp03_inventario_muebles']['cod_dependencia2'];
$cod_dir_superior2 = $this->data['cimp03_inventario_muebles']['cod_direccions2'];
$cod_coordinacion2 = $this->data['cimp03_inventario_muebles']['cod_coordinacion2'];
$cod_secretaria2 = $this->data['cimp03_inventario_muebles']['cod_secretaria2'];
$cod_direccion2 = $this->data['cimp03_inventario_muebles']['cod_direccion2'];
$cod_division2 = $this->data['cimp03_inventario_muebles']['cod_division2'];
$cod_departamento2 = $this->data['cimp03_inventario_muebles']['cod_departamento2'];
$cod_oficina2 = $this->data['cimp03_inventario_muebles']['cod_oficina2'];

	if($cod_institucion == $cod_institucion2 &&
		$cod_dependencia == $cod_dependencia2 &&
		$cod_dir_superior == $cod_dir_superior2 &&
		$cod_coordinacion == $cod_coordinacion2 &&
		$cod_secretaria == $cod_secretaria2 &&
		$cod_direccion == $cod_direccion2 &&
		$cod_division == $cod_division2 &&
		$cod_departamento == $cod_departamento2 &&
		$cod_oficina == $cod_oficina2
	){
		$this->set('errorMessage', 'No se puede efectuar cambios. La ubicaci&oacute;n actual es igual a la de traslado...');
	}else{

		$fc = $this->cimd03_inventario_muebles->findCount("cod_presi = $cod_presi AND
cod_entidad   = $cod_entidad AND
cod_tipo_inst = $cod_tipo_inst AND
cod_inst      = $cod_inst AND
cod_dep       = $cod_dep AND
cod_institucion  = $cod_institucion AND
cod_dependencia  = $cod_dependencia AND
cod_dir_superior = $cod_dir_superior AND
cod_coordinacion = $cod_coordinacion AND
cod_secretaria   = $cod_secretaria AND
cod_direccion    = $cod_direccion AND
cod_division     = $cod_division AND
cod_departamento = $cod_departamento AND
cod_oficina      = $cod_oficina");

	if($fc > 0){

		$cambio = $this->cimd03_inventario_muebles->execute("UPDATE cimd03_inventario_muebles SET

cod_institucion  = $cod_institucion2,
cod_dependencia  = $cod_dependencia2,
cod_dir_superior = $cod_dir_superior2,
cod_coordinacion = $cod_coordinacion2,
cod_secretaria   = $cod_secretaria2,
cod_direccion    = $cod_direccion2,
cod_division     = $cod_division2,
cod_departamento = $cod_departamento2,
cod_oficina      = $cod_oficina2 WHERE

cod_presi     = $cod_presi AND
cod_entidad   = $cod_entidad AND
cod_tipo_inst = $cod_tipo_inst AND
cod_inst      = $cod_inst AND
cod_dep       = $cod_dep AND
cod_institucion  = $cod_institucion AND
cod_dependencia  = $cod_dependencia AND
cod_dir_superior = $cod_dir_superior AND
cod_coordinacion = $cod_coordinacion AND
cod_secretaria   = $cod_secretaria AND
cod_direccion    = $cod_direccion AND
cod_division     = $cod_division AND
cod_departamento = $cod_departamento AND
cod_oficina      = $cod_oficina".";");

		if($cambio > 1){
			$this->set('Message_existe', 'Traslado de bienes realizado exitosamente.!!');
		}else{
			$this->set('errorMessage', 'No se pudo efectuar los cambios. Intente Nuevamente...');
		}
	}else{
		$this->set('errorMessage', 'No hay bienes en esta ubicaci&oacute;n para trasladar...');
	}
	}
	echo "<script>document.getElementById('bt_cambiar').disabled=false;</script>";
}


function salir_ubic_bm($var = null){
	$this->layout="ajax";

	$this->Session->delete('cinst');
	$this->Session->delete('dinst');
	$this->Session->delete('cdepe');
	$this->Session->delete('ddepe');

	$this->Session->delete('cinst2');
	$this->Session->delete('dinst2');
	$this->Session->delete('cdepe2');
	$this->Session->delete('ddepe2');
}





function cambio_clasificador_funcional($var = null){
	$this->layout="ajax";
 	$cod_presi = $this->verifica_SS(1);
	$cod_entidad = $this->verifica_SS(2);
	$cod_tipo_inst = $this->verifica_SS(3);
	$cod_inst = $this->verifica_SS(4);
	$cod_dep = $this->verifica_SS(5);
 	$this->data=null;

    $codg = $this->v_cimd01_clasificacion_seccion->execute("SELECT DISTINCT cod_tipo, deno_tipo FROM v_cimd01_clasificacion_seccion;");

	if(!empty($codg)){
		$this->Session->write('cxgrupo',$codg[0][0]['cod_tipo']);
		$this->Session->write('cxgrupo2',$codg[0][0]['cod_tipo']);
		foreach ($codg as $lg) {
			$vg[] = $lg[0]["cod_tipo"];
			$dg[] = $lg[0]["deno_tipo"];
		}
		$grupo = array_combine($vg, $dg);
		$this->concatena($grupo, 'grupo');
    	$this->set('cod_g', $codg[0][0]['cod_tipo']);
    	$this->set('deno_g', $codg[0][0]['deno_tipo']);
	}else{
		$this->Session->write('cxgrupo',0);
		$this->Session->write('cxgrupo2',0);
    	$this->set('grupo', array());
    	$this->set('deno_g', '');
	}

  	$subgrupo = $this->v_cimd01_clasificacion_seccion->generateList("cod_tipo=".$codg[0][0]['cod_tipo'], 'cod_grupo ASC', null, '{n}.v_cimd01_clasificacion_seccion.cod_grupo', '{n}.v_cimd01_clasificacion_seccion.deno_grupo');
	if(!empty($subgrupo))
		$this->concatena($subgrupo, 'subgrupo');
	else
		$this->set('subgrupo', array());
}





function select5_a($select=null,$var=null) {
	$this->layout = "ajax";
	if($var!=null){
	switch($select){
		case 'grupo':
			$this->Session->write('cxgrupo',$var);
			$cod_grupo = $this->Session->read('cxgrupo');
			$codg = $this->v_cimd01_clasificacion_seccion->execute("SELECT DISTINCT cod_tipo, deno_tipo FROM v_cimd01_clasificacion_seccion WHERE cod_tipo=$var;");

			echo "<script>
				document.getElementById('cod_grupo').value='".mascara($codg[0][0]['cod_tipo'], 2)."';
		  		document.getElementById('deno_grupo').value='".$codg[0][0]['deno_tipo']."';
				document.getElementById('cod_subgrupo').value='';
		  		document.getElementById('deno_subgrupo').value='';
				document.getElementById('cod_seccion').value='';
		  		document.getElementById('deno_seccion').value='';
				document.getElementById('cod_subseccion').value='';
		  		document.getElementById('deno_subseccion').value='';
        	</script>";

		  $this->set('SELECT','subgrupo');
		  $this->set('codigo','grupo');
		  $this->set('seleccion','');
		  $this->set('n',6);

  	$lista = $this->v_cimd01_clasificacion_seccion->generateList("cod_tipo=$var", 'cod_grupo ASC', null, '{n}.v_cimd01_clasificacion_seccion.cod_grupo', '{n}.v_cimd01_clasificacion_seccion.deno_grupo');
	if(!empty($lista))
		$this->concatena($lista, 'vector');
	else
		$this->set('vector', array());

		break;




		case 'subgrupo':

			$this->Session->write('cxsubgrupo',$var);
			$cod_grupo = $this->Session->read('cxgrupo');
			$codg = $this->v_cimd01_clasificacion_seccion->execute("SELECT DISTINCT cod_tipo, deno_tipo, cod_grupo, deno_grupo FROM v_cimd01_clasificacion_seccion WHERE cod_tipo=$cod_grupo and cod_grupo=$var;");

			echo "<script>
				document.getElementById('cod_subgrupo').value='".mascara($codg[0][0]['cod_grupo'], 2)."';
		  		document.getElementById('deno_subgrupo').value='".$codg[0][0]['deno_grupo']."';
				document.getElementById('cod_seccion').value='';
		  		document.getElementById('deno_seccion').value='';
				document.getElementById('cod_subseccion').value='';
		  		document.getElementById('deno_subseccion').value='';
        	</script>";

		  $this->set('SELECT','seccion');
		  $this->set('codigo','subgrupo');
		  $this->set('seleccion','');
		  $this->set('n',7);

  	$lista = $this->v_cimd01_clasificacion_seccion->generateList("cod_tipo=$cod_grupo and cod_grupo=$var", 'cod_subgrupo ASC', null, '{n}.v_cimd01_clasificacion_seccion.cod_subgrupo', '{n}.v_cimd01_clasificacion_seccion.deno_subgrupo');
	if(!empty($lista))
		$this->concatena($lista, 'vector');
	else
		$this->set('vector', array());
 		break;


		case 'seccion':

			$this->Session->write('cxseccion',$var);
		  	$cod_grupo = $this->Session->read('cxgrupo');
		  	$cod_subgrupo = $this->Session->read('cxsubgrupo');
			$codg = $this->v_cimd01_clasificacion_seccion->execute("SELECT DISTINCT cod_tipo, deno_tipo, cod_grupo, deno_grupo, cod_subgrupo, deno_subgrupo FROM v_cimd01_clasificacion_seccion WHERE cod_tipo=$cod_grupo and cod_grupo=$cod_subgrupo and cod_subgrupo=$var;");

			echo "<script>
				document.getElementById('cod_seccion').value='".mascara($codg[0][0]['cod_subgrupo'], 2)."';
		  		document.getElementById('deno_seccion').value='".$codg[0][0]['deno_subgrupo']."';
				document.getElementById('cod_subseccion').value='';
		  		document.getElementById('deno_subseccion').value='';
        	</script>";

		  $this->set('SELECT','subseccion');
		  $this->set('codigo','seccion');
		  $this->set('seleccion','');
		  $this->set('n',8);

  	$lista = $this->v_cimd01_clasificacion_seccion->generateList("cod_tipo=$cod_grupo and cod_grupo=$cod_subgrupo and cod_subgrupo=$var", 'cod_seccion ASC', null, '{n}.v_cimd01_clasificacion_seccion.cod_seccion', '{n}.v_cimd01_clasificacion_seccion.deno_seccion');
	if(!empty($lista))
		$this->concatena($lista, 'vector');
	else
		$this->set('vector', array());

		break;



		case 'subseccion':

			$this->Session->write('cxsubseccion',$var);
		  	$cod_grupo = $this->Session->read('cxgrupo');
		  	$cod_subgrupo = $this->Session->read('cxsubgrupo');
		  	$cod_seccion = $this->Session->read('cxseccion');

			$codg = $this->v_cimd01_clasificacion_seccion->execute("SELECT DISTINCT cod_tipo, deno_tipo, cod_grupo, deno_grupo, cod_subgrupo, deno_subgrupo, cod_seccion, deno_seccion FROM v_cimd01_clasificacion_seccion WHERE cod_tipo=$cod_grupo and cod_grupo=$cod_subgrupo and cod_subgrupo=$cod_seccion and cod_seccion=$var;");
			$this->set('codg',$codg);

		  $this->set('vector','1');
		  $this->set('SELECT','');
		  $this->set('codigo','');
		  $this->set('seleccion','');
		  $this->set('no','no');
		  $this->set('n',9);

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
}//fin select codigos



function select5_b($select=null,$var=null) {
	$this->layout = "ajax";
	if($var!=null){
	switch($select){
		case 'grupo':
			$this->Session->write('cxgrupo2',$var);
			$cod_grupo = $this->Session->read('cxgrupo2');
			$codg = $this->v_cimd01_clasificacion_seccion->execute("SELECT DISTINCT cod_tipo, deno_tipo FROM v_cimd01_clasificacion_seccion WHERE cod_tipo=$var;");

			echo "<script>
				document.getElementById('cod_grupo2').value='".mascara($codg[0][0]['cod_tipo'], 2)."';
		  		document.getElementById('deno_grupo2').value='".$codg[0][0]['deno_tipo']."';
				document.getElementById('cod_subgrupo2').value='';
		  		document.getElementById('deno_subgrupo2').value='';
				document.getElementById('cod_seccion2').value='';
		  		document.getElementById('deno_seccion2').value='';
				document.getElementById('cod_subseccion2').value='';
		  		document.getElementById('deno_subseccion2').value='';
        	</script>";

		  $this->set('SELECT','subgrupo');
		  $this->set('codigo','grupo');
		  $this->set('seleccion','');
		  $this->set('n',6);

  	$lista = $this->v_cimd01_clasificacion_seccion->generateList("cod_tipo=$var", 'cod_grupo ASC', null, '{n}.v_cimd01_clasificacion_seccion.cod_grupo', '{n}.v_cimd01_clasificacion_seccion.deno_grupo');
	if(!empty($lista))
		$this->concatena($lista, 'vector');
	else
		$this->set('vector', array());

		break;




		case 'subgrupo':

			$this->Session->write('cxsubgrupo2',$var);
			$cod_grupo = $this->Session->read('cxgrupo2');
			$codg = $this->v_cimd01_clasificacion_seccion->execute("SELECT DISTINCT cod_tipo, deno_tipo, cod_grupo, deno_grupo FROM v_cimd01_clasificacion_seccion WHERE cod_tipo=$cod_grupo and cod_grupo=$var;");

			echo "<script>
				document.getElementById('cod_subgrupo2').value='".mascara($codg[0][0]['cod_grupo'], 2)."';
		  		document.getElementById('deno_subgrupo2').value='".$codg[0][0]['deno_grupo']."';
				document.getElementById('cod_seccion2').value='';
		  		document.getElementById('deno_seccion2').value='';
				document.getElementById('cod_subseccion2').value='';
		  		document.getElementById('deno_subseccion2').value='';
        	</script>";

		  $this->set('SELECT','seccion');
		  $this->set('codigo','subgrupo');
		  $this->set('seleccion','');
		  $this->set('n',7);

  	$lista = $this->v_cimd01_clasificacion_seccion->generateList("cod_tipo=$cod_grupo and cod_grupo=$var", 'cod_subgrupo ASC', null, '{n}.v_cimd01_clasificacion_seccion.cod_subgrupo', '{n}.v_cimd01_clasificacion_seccion.deno_subgrupo');
	if(!empty($lista))
		$this->concatena($lista, 'vector');
	else
		$this->set('vector', array());
 		break;


		case 'seccion':

			$this->Session->write('cxseccion2',$var);
		  	$cod_grupo = $this->Session->read('cxgrupo2');
		  	$cod_subgrupo = $this->Session->read('cxsubgrupo2');
			$codg = $this->v_cimd01_clasificacion_seccion->execute("SELECT DISTINCT cod_tipo, deno_tipo, cod_grupo, deno_grupo, cod_subgrupo, deno_subgrupo FROM v_cimd01_clasificacion_seccion WHERE cod_tipo=$cod_grupo and cod_grupo=$cod_subgrupo and cod_subgrupo=$var;");

			echo "<script>
				document.getElementById('cod_seccion2').value='".mascara($codg[0][0]['cod_subgrupo'], 2)."';
		  		document.getElementById('deno_seccion2').value='".$codg[0][0]['deno_subgrupo']."';
				document.getElementById('cod_subseccion2').value='';
		  		document.getElementById('deno_subseccion2').value='';
        	</script>";

		  $this->set('SELECT','subseccion');
		  $this->set('codigo','seccion');
		  $this->set('seleccion','');
		  $this->set('n',8);

  	$lista = $this->v_cimd01_clasificacion_seccion->generateList("cod_tipo=$cod_grupo and cod_grupo=$cod_subgrupo and cod_subgrupo=$var", 'cod_seccion ASC', null, '{n}.v_cimd01_clasificacion_seccion.cod_seccion', '{n}.v_cimd01_clasificacion_seccion.deno_seccion');
	if(!empty($lista))
		$this->concatena($lista, 'vector');
	else
		$this->set('vector', array());

		break;



		case 'subseccion':

			$this->Session->write('cxsubseccion2',$var);
		  	$cod_grupo = $this->Session->read('cxgrupo2');
		  	$cod_subgrupo = $this->Session->read('cxsubgrupo2');
		  	$cod_seccion = $this->Session->read('cxseccion2');

			$codg = $this->v_cimd01_clasificacion_seccion->execute("SELECT DISTINCT cod_tipo, deno_tipo, cod_grupo, deno_grupo, cod_subgrupo, deno_subgrupo, cod_seccion, deno_seccion FROM v_cimd01_clasificacion_seccion WHERE cod_tipo=$cod_grupo and cod_grupo=$cod_subgrupo and cod_subgrupo=$cod_seccion and cod_seccion=$var;");
			$this->set('codg',$codg);

		  $this->set('vector','1');
		  $this->set('SELECT','');
		  $this->set('codigo','');
		  $this->set('seleccion','');
		  $this->set('no','no');
		  $this->set('n',9);

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
}//fin select codigos


function guardar_cambio_clasif(){
	$this->layout="ajax";

 	$cod_presi = $this->verifica_SS(1);
	$cod_entidad = $this->verifica_SS(2);
	$cod_tipo_inst = $this->verifica_SS(3);
	$cod_inst = $this->verifica_SS(4);
	$cod_dep = $this->verifica_SS(5);

$cod_grupo = $this->data['cimp03_inventario_muebles']['cod_grupo'];
$cod_subgrupo = $this->data['cimp03_inventario_muebles']['cod_subgrupo'];
$cod_seccion = $this->data['cimp03_inventario_muebles']['cod_seccion'];
$cod_subseccion = $this->data['cimp03_inventario_muebles']['cod_subseccion'];

$cod_grupo2 = $this->data['cimp03_inventario_muebles']['cod_grupo2'];
$cod_subgrupo2 = $this->data['cimp03_inventario_muebles']['cod_subgrupo2'];
$cod_seccion2 = $this->data['cimp03_inventario_muebles']['cod_seccion2'];
$cod_subseccion2 = $this->data['cimp03_inventario_muebles']['cod_subseccion2'];

	if($cod_grupo == $cod_grupo2 && $cod_subgrupo == $cod_subgrupo2 && $cod_seccion == $cod_seccion2 && $cod_subseccion == $cod_subseccion2){
		$this->set('errorMessage', 'No se puede efectuar cambios. El clasificador actual es igual al de cambiar...<br><br>Seleccione clasificadores distintos!!');
	}else{

	$condicion  = "cod_presi = $cod_presi AND cod_entidad = $cod_entidad AND cod_tipo_inst = $cod_tipo_inst AND cod_inst = $cod_inst AND cod_dep = $cod_dep AND cod_tipo = $cod_grupo AND cod_grupo = $cod_subgrupo AND cod_subgrupo = $cod_seccion AND cod_seccion = $cod_subseccion";

	$condicion2 = "cod_presi = $cod_presi AND cod_entidad = $cod_entidad AND cod_tipo_inst = $cod_tipo_inst AND cod_inst = $cod_inst AND cod_dep = $cod_dep AND cod_tipo = $cod_grupo2 AND cod_grupo = $cod_subgrupo2 AND cod_subgrupo = $cod_seccion2 AND cod_seccion = $cod_subseccion2";
    $control_numero = $this->cimd03_inventario_numero->findCount($condicion2);
    if($control_numero <= 0){
    $numero_identificacion=0;
    $control_num = "INSERT INTO cimd03_inventario_numero (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo, cod_grupo, cod_subgrupo, cod_seccion, numero_identificacion) ";
	$control_num .= "VALUES('$cod_presi', '$cod_entidad', '$cod_tipo_inst', '$cod_inst', '$cod_dep', '$cod_grupo2', '$cod_subgrupo2', '$cod_seccion2', '$cod_subseccion2', '$numero_identificacion')";
	$control_n = $this->cimd03_inventario_numero->execute($control_num);
    }

    $control_nm = $this->cimd03_inventario_numero->execute("SELECT numero_identificacion FROM cimd03_inventario_numero WHERE $condicion2;");
    $numero = $control_nm[0][0]["numero_identificacion"];

    $fcon = $this->v_cimd01_clasificacion_seccion->execute("SELECT numero_identificacion FROM cimd03_inventario_muebles WHERE $condicion;");

		if(!empty($fcon)){
		foreach ($fcon as $fc) {
			 $nume_ident = $fc[0]["numero_identificacion"];
            $numero=$numero+1;
            $cambio = $this->cimd03_inventario_muebles->execute("UPDATE cimd03_inventario_muebles SET cod_tipo = $cod_grupo2, cod_grupo = $cod_subgrupo2, cod_subgrupo = $cod_seccion2, cod_seccion = $cod_subseccion2, numero_identificacion = $numero WHERE ".$condicion." and numero_identificacion=".$nume_ident);
            $numero_control = $this->cimd03_inventario_numero->execute("UPDATE cimd03_inventario_numero SET numero_identificacion = $numero WHERE ".$condicion2);
            }
            $numero_control = $this->cimd03_inventario_numero->execute("UPDATE cimd03_inventario_numero SET numero_identificacion = 0 WHERE ".$condicion);
	}else{
		$this->set('errorMessage', 'No hay bienes en esta ubicaci&oacute;n para cambiar...');
	}
	}
	echo "<script>document.getElementById('bt_cambiar').disabled=false;</script>";
}


function salir_clas_bm($var = null){
	$this->layout="ajax";
	$this->Session->delete('cxgrupo');
	$this->Session->delete('cxsubgrupo');
	$this->Session->delete('cxseccion');
	$this->Session->delete('cxsubseccion');

	$this->Session->delete('cxgrupo2');
	$this->Session->delete('cxsubgrupo2');
	$this->Session->delete('cxseccion2');
	$this->Session->delete('cxsubseccion2');
}

function ficha_bienes_muebles(){
	$this->layout="ajax";
}

}//fin de la clase controller
?>