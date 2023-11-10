<?php

class Shp001RegistroContribuyentesController extends AppController{

    var $name    = "shp001_registro_contribuyentes";
    var $uses    = array('shd700_credito_vivienda','shd600_solicitud_arrendamiento','shd500_aseo_domiciliario','shd400_propiedad',
                         'shd300_propaganda','shd200_vehiculos','shd900_cobranza_diaria','v_grilla_constribuyentes','v_shd001_registro_contribuyentes',
                         'cugd01_republica','shd001_registro_contribuyentes', 'shd100_solicitud', 'shd100_solicitud_activ', 'ccfd04_cierre_mes',
                         'cnmd06_profesiones','cugd01_republica','shd001_registro_contribuyentes','cugd01_estados','cugd01_municipios',
                         'cugd01_parroquias','cugd01_centropoblados','cugd01_vialidad','cugd01_vereda', 'cugd90_municipio_defecto', "cugd01_cuadra",
                         "v_shd001_registro_con_consul");
	var $helpers = array('Html','Ajax','Javascript', 'Sisap', 'Fpdf');





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




function valida_rif($var=null){
	$this->layout ="ajax";


$var = str_replace(" ", "", $var);
$var = trim($var);
$var = str_replace(",", "", $var);
$var = str_replace(";", "", $var);
$var = strtoupper($var);


  		   $sw = $this->shd001_registro_contribuyentes->findCount("rif_cedula='".$var."'  ");
  		if($sw!=0){
  			$this->set('msg_error',"El CONTRIBUYENTE ya se encuentra registrado");
  		}else{
  		}

$this->set('var',$var);
$this->set('contar',$sw);
}


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




 function SQLCA_S($ano=null){//sql para busqueda de codigos de arranque con y sin año
				 $sql_re = "cod_presi=".$this->verifica_SS(1)."  and    ";
				 $sql_re .= "cod_entidad=".$this->verifica_SS(2)."  and  ";
				 $sql_re .= "cod_tipo_inst=".$this->verifica_SS(3)."  and ";
				 $sql_re .= "cod_inst=".$this->verifica_SS(4)."   ";
				 return $sql_re;
}//fin funcion SQLCA



 function index(){
 	$this->layout ="ajax";
 	$this->set('entidad_federal', $this->Session->read('entidad_federal'));
	$listaprofesion=$this->cnmd06_profesiones->generateList(null, 'denominacion ASC', null, '{n}.cnmd06_profesiones.cod_profesion', '{n}.cnmd06_profesiones.denominacion');
    $this->concatenaN($listaprofesion, 'cod_profesion');

    $this->data = null;
    $can_mun_def=$this->cugd90_municipio_defecto->findCount($this->SQLCA_S());

	if($can_mun_def!=0){

				$mun_defecto=$this->cugd90_municipio_defecto->findAll($this->SQLCA_S());

			    $this->set("mun_defecto",$mun_defecto);
			    $this->set("can_mun_def",$can_mun_def);

			    $cod_republica=$mun_defecto[0]["cugd90_municipio_defecto"]["cod_republica"];
			    $cod_estado=$mun_defecto[0]["cugd90_municipio_defecto"]["cod_estado"];
			    $cod_municipio=$mun_defecto[0]["cugd90_municipio_defecto"]["cod_municipio"];

			    $this->Session->write('pais',$cod_republica);
			    $this->Session->write('esta',$cod_estado);
			    $this->Session->write('muni',$cod_municipio);

				$lista_r=  $this->cugd01_republica->generateList(null,                                                                                                 'denominacion ASC', null, '{n}.cugd01_republica.cod_republica',  '{n}.cugd01_republica.denominacion');
				$lista_e=  $this->cugd01_estados->generateList("cod_republica=".$cod_republica,                                                                        'denominacion ASC', null, '{n}.cugd01_estados.cod_estado',       '{n}.cugd01_estados.denominacion');
				$lista_m=  $this->cugd01_municipios->generateList("cod_republica=".$cod_republica." and cod_estado=".$cod_estado,                                      'denominacion ASC', null, '{n}.cugd01_municipios.cod_municipio', '{n}.cugd01_municipios.denominacion');
                $lista_p=  $this->cugd01_parroquias->generateList("cod_republica=".$cod_republica." and cod_estado=".$cod_estado." and cod_municipio=".$cod_municipio, 'denominacion ASC', null, '{n}.cugd01_parroquias.cod_parroquia', '{n}.cugd01_parroquias.denominacion');


				$this->concatena($lista_r, 'vector_r');
				$this->concatena($lista_e, 'vector_e');
				$this->concatena($lista_m, 'vector_m');
				$this->concatena($lista_p, 'vector_p');

				$deno_r=  $this->cugd01_republica->findAll("cod_republica=".$cod_republica);
				$deno_e=  $this->cugd01_estados->findAll("cod_republica=".$cod_republica." and cod_estado=".$cod_estado);
				$deno_m=  $this->cugd01_municipios->findAll("cod_republica=".$cod_republica." and cod_estado=".$cod_estado." and cod_municipio=".$cod_municipio);

				$this->set('deno_r',$deno_r[0]["cugd01_republica"]["denominacion"]);
				$this->set('deno_e',$deno_e[0]["cugd01_estados"]["denominacion"]);
				$this->set('deno_m',$deno_m[0]["cugd01_municipios"]["denominacion"]);

				$this->set('cod_r',$cod_republica);
				$this->set('cod_e',$cod_estado);
				$this->set('cod_m',$cod_municipio);


				$this->set('seleccion_pais', $cod_republica);
			    $this->set('seleccion_esta', $cod_estado);
			    $this->set('seleccion_muni', $cod_municipio);

	}else{
		        $this->set('deno_r',"");
				$this->set('deno_e',"");
				$this->set('deno_m',"");

				$this->set('cod_r',"");
				$this->set('cod_e',"");
				$this->set('cod_m',"");

				$this->set('seleccion_pais', "");
			    $this->set('seleccion_esta', "");
			    $this->set('seleccion_muni', "");

			    $listarepublica=$this->cugd01_republica->generateList(null, 'denominacion ASC', null, '{n}.cugd01_republica.cod_republica', '{n}.cugd01_republica.denominacion');
                $this->concatena($listarepublica, 'vector_r');

                $this->set('vector_e',"");
				$this->set('vector_m',"");
				$this->set('vector_p',"");
	}//fin else


}//fin function

function personalidad($personalidad=null){
	$this->layout='ajax';
	if($personalidad==1){
		$listaprofesion=$this->cnmd06_profesiones->generateList(null, 'denominacion ASC', null, '{n}.cnmd06_profesiones.cod_profesion', '{n}.cnmd06_profesiones.denominacion');
    	$this->concatenaN($listaprofesion, 'cod_profesion');
    	echo "<script>";
			echo "document.getElementById('nacionalidad_1').checked='true';  ";
			echo "document.getElementById('estado_civil_1').checked='true';  ";
			echo "document.getElementById('nacionalidad_1').disabled='';  ";
			echo "document.getElementById('nacionalidad_2').disabled='';  ";
			echo "document.getElementById('estado_civil_1').disabled='';  ";
			echo "document.getElementById('estado_civil_2').disabled='';  ";
			echo "document.getElementById('estado_civil_3').disabled='';  ";
			echo "document.getElementById('estado_civil_4').disabled='';  ";
			echo "document.getElementById('estado_civil_5').disabled='';  ";
        echo "</script>";
	}else if($personalidad==2){
		echo "<script>";
			echo "document.getElementById('nacionalidad_1').checked='';  ";
			echo "document.getElementById('nacionalidad_2').checked='';  ";
			echo "document.getElementById('nacionalidad_1').disabled='true';  ";
			echo "document.getElementById('nacionalidad_2').disabled='true';  ";
			echo "document.getElementById('estado_civil_1').checked='';  ";
			echo "document.getElementById('estado_civil_2').checked='';  ";
			echo "document.getElementById('estado_civil_3').checked='';  ";
			echo "document.getElementById('estado_civil_4').checked='';  ";
			echo "document.getElementById('estado_civil_5').checked='';  ";
			echo "document.getElementById('estado_civil_1').disabled='true';  ";
			echo "document.getElementById('estado_civil_2').disabled='true';  ";
			echo "document.getElementById('estado_civil_3').disabled='true';  ";
			echo "document.getElementById('estado_civil_4').disabled='true';  ";
			echo "document.getElementById('estado_civil_5').disabled='true';  ";
        echo "</script>";
	}
	$this->set('personalidad',$personalidad);
}

function personalidad2($rif_cedula=null,$personalidad=null){
	$this->layout='ajax';

	$mo=$this->v_shd001_registro_contribuyentes->findAll("rif_cedula='".$rif_cedula."'");
	$profesion=$mo[0]['v_shd001_registro_contribuyentes']['profesion'];
	if($profesion==9999){
		$profesion='';
	}else{
		$profesion=$this->AddCeroR($profesion);
	}
	$this->set('profesion',$profesion);
	$this->set('deno_profesion',$mo[0]['v_shd001_registro_contribuyentes']['deno_profesion']);
	$naci=$mo[0]['v_shd001_registro_contribuyentes']['nacionalidad'];
	$esta=$mo[0]['v_shd001_registro_contribuyentes']['estado_civil'];
	if($naci==1){
		$an=true;
		$bn='';
	}else if($naci==2){
		$bn=true;
		$an='';
	}else if($naci==3){
		$bn='';
		$an=true;
	}

	if($esta==1){
		$ae=true;
		$be='';
		$ce='';
		$de='';
		$ee='';
	}else if($esta==2){
		$ae='';
		$be=true;
		$ce='';
		$de='';
		$ee='';
	}else if($esta==3){
		$ae='';
		$be='';
		$ce=true;
		$de='';
		$ee='';
	}else if($esta==4){
		$ae='';
		$be='';
		$ce='';
		$de=true;
		$ee='';
	}else if($esta==5){
		$ae='';
		$be='';
		$ce='';
		$de='';
		$ee=true;
	}else if($esta==6){
		$ae=true;
		$be='';
		$ce='';
		$de='';
		$ee='';
	}

	if($personalidad==1){
		$listaprofesion=$this->cnmd06_profesiones->generateList(null, 'denominacion ASC', null, '{n}.cnmd06_profesiones.cod_profesion', '{n}.cnmd06_profesiones.denominacion');
    	$this->concatenaN($listaprofesion, 'cod_profesion');
    	echo "<script>";
			echo "document.getElementById('nacionalidad_1').checked='".$an."';  ";
			echo "document.getElementById('nacionalidad_2').checked='".$bn."';  ";
			echo "document.getElementById('estado_civil_1').checked='".$ae."';  ";
			echo "document.getElementById('estado_civil_2').checked='".$be."';  ";
			echo "document.getElementById('estado_civil_3').checked='".$ce."';  ";
			echo "document.getElementById('estado_civil_4').checked='".$de."';  ";
			echo "document.getElementById('estado_civil_5').checked='".$ee."';  ";
			echo "document.getElementById('nacionalidad_1').disabled='';  ";
			echo "document.getElementById('nacionalidad_2').disabled='';  ";
			echo "document.getElementById('estado_civil_1').disabled='';  ";
			echo "document.getElementById('estado_civil_2').disabled='';  ";
			echo "document.getElementById('estado_civil_3').disabled='';  ";
			echo "document.getElementById('estado_civil_4').disabled='';  ";
			echo "document.getElementById('estado_civil_5').disabled='';  ";
        echo "</script>";
	}else if($personalidad==2){
		echo "<script>";
			echo "document.getElementById('nacionalidad_1').checked='';  ";
			echo "document.getElementById('nacionalidad_2').checked='';  ";
			echo "document.getElementById('nacionalidad_1').disabled='true';  ";
			echo "document.getElementById('nacionalidad_2').disabled='true';  ";
			echo "document.getElementById('estado_civil_1').checked='';  ";
			echo "document.getElementById('estado_civil_2').checked='';  ";
			echo "document.getElementById('estado_civil_3').checked='';  ";
			echo "document.getElementById('estado_civil_4').checked='';  ";
			echo "document.getElementById('estado_civil_5').checked='';  ";
			echo "document.getElementById('estado_civil_1').disabled='true';  ";
			echo "document.getElementById('estado_civil_2').disabled='true';  ";
			echo "document.getElementById('estado_civil_3').disabled='true';  ";
			echo "document.getElementById('estado_civil_4').disabled='true';  ";
			echo "document.getElementById('estado_civil_5').disabled='true';  ";
        echo "</script>";
	}

	$this->set('personalidad',$personalidad);
}

function codi_profesion($codigo){
	$this->layout = "ajax";
	$a = $this->cnmd06_profesiones->findAll("cod_profesion=".$codigo,array('cod_profesion','denominacion'));
    $this->set("a",$a[0]['cnmd06_profesiones']['cod_profesion']);
}

function deno_profesion($codigo){
	$this->layout = "ajax";
	$b = $this->cnmd06_profesiones->findAll("cod_profesion=".$codigo,array('cod_profesion','denominacion'));
	$this->set("b",$b[0]['cnmd06_profesiones']['denominacion']);
}

function select3($select=null,$var=null) { //select codigos presupuestarios
	$this->layout = "ajax";//echo $var;
	//echo 'si';
	if($var!=null){
	switch($select){
		case 'republica':
		  $this->set('SELECT','estados');
		  $this->set('codigo','pais');
		  $this->set('seleccion','');
		  $this->set('n',1);
		  $lista=  $this->cugd01_republica->generateList(null,'denominacion ASC', null, '{n}.cfpd02_sector.cod_republica', '{n}.cfpd02_sector.denominacion');
          $this->concatena($lista, 'vector');
		break;
		case 'estados':
		//echo $select;
		  $this->set('SELECT','municipios');
		  $this->set('codigo','estados');
		  $this->set('seleccion','');
		  $this->set('n',2);
		   $this->Session->write('pais',$var);
		  $cond=" cod_republica=".$var;
		  $lista=  $this->cugd01_estados->generateList($cond, 'denominacion ASC', null, '{n}.cugd01_estados.cod_estado', '{n}.cugd01_estados.denominacion');
          $this->concatena($lista, 'vector');
		break;
		case 'municipios':
		  $this->set('SELECT','parroquias');
		  $this->set('codigo','municipios');
		  $this->set('seleccion','');
		  $this->set('n',3);
		  $this->set('buitre',true);
		  $rep =  $this->Session->read('pais');
		  $this->Session->write('esta',$var);
		  $cond =" cod_republica=".$rep." and cod_estado=".$var;
		  $lista=  $this->cugd01_municipios->generateList($cond, 'denominacion ASC', null, '{n}.cugd01_municipios.cod_municipio', '{n}.cugd01_municipios.denominacion');
          $this->concatena($lista, 'vector');
		break;
		case 'parroquias':
		  $this->set('SELECT','centros');
		  $this->set('codigo','parroquias');
		  $this->set('seleccion','');
		  $this->set('n',4);
		  $rep =  $this->Session->read('pais');
		  $est =  $this->Session->read('esta');
		  $this->Session->write('muni',$var);
		  $cond =" cod_republica=".$rep." and cod_estado=".$est." and cod_municipio=".$var;
		  //echo $cond;
		  $lista=  $this->cugd01_parroquias->generateList($cond, 'denominacion ASC', null, '{n}.cugd01_parroquias.cod_parroquia', '{n}.cugd01_parroquias.denominacion');
          $this->concatena($lista, 'vector');
		break;
		case 'centros':
		  $this->set('SELECT','calles');
		  $this->set('codigo','centros');
		  $this->set('seleccion','');
		  $this->set('n',5);
		  $rep =  $this->Session->read('pais');
		  $est =  $this->Session->read('esta');
		  $mun =  $this->Session->read('muni');
		  $this->Session->write('parr',$var);
		  $cond =" cod_republica=".$rep." and cod_estado=".$est." and cod_municipio=".$mun." and cod_parroquia=".$var;
		  $lista=  $this->cugd01_centropoblados->generateList($cond, 'denominacion ASC', null, '{n}.cugd01_centropoblados.cod_centro', '{n}.cugd01_centropoblados.denominacion');
          $this->concatena_tres_digitos($lista, 'vector');
		break;
		case 'calles':
		  $this->set('SELECT','veredas');
		  $this->set('codigo','calles');
		  $this->set('seleccion','');
		  $this->set('n',6);
		  $rep =  $this->Session->read('pais');
		  $est =  $this->Session->read('esta');
		  $mun =  $this->Session->read('muni');
		  $parr =  $this->Session->read('parr');
		  $this->Session->write('cent',$var);
		  $cond =" cod_republica=".$rep." and cod_estado=".$est." and cod_municipio=".$mun." and cod_parroquia=".$parr." and cod_centro=".$var;
		  $lista=  $this->cugd01_vialidad->generateList($cond, 'denominacion ASC', null, '{n}.cugd01_vialidad.cod_vialidad', '{n}.cugd01_vialidad.denominacion');
         	if($lista!=null){
							$this->concatena($lista,'vector');
						}else{
							$this->set('vector',array());
						}
		break;
		case 'veredas':
		  $this->set('SELECT','cuadras');
		  $this->set('codigo','veredas');

		  $this->set('n',7);
//		  $this->set('no','no');
		  $rep =  $this->Session->read('pais');
		  $est =  $this->Session->read('esta');
		  $mun =  $this->Session->read('muni');
		  $parr =  $this->Session->read('parr');
		  $cent =  $this->Session->read('cent');
		  $this->Session->write('vial',$var);
		  $cond =" cod_republica=".$rep." and cod_estado=".$est." and cod_municipio=".$mun." and cod_parroquia=".$parr." and cod_centro=".$cent." and cod_vialidad=".$var;
		  $lista=  $this->cugd01_vereda->generateList($cond, 'denominacion ASC', null, '{n}.cugd01_vereda.cod_vereda', '{n}.cugd01_vereda.denominacion');
          if($lista!=null){
							$this->concatena($lista,'vector');
							$this->set('seleccion','');
						}else{
							$this->set('vector',array('0'=>'00 - N/A'));
							$this->set('seleccion','0');
						}
		break;
		case 'cuadras':
		  $this->set('SELECT','cuadras');
		  $this->set('codigo','cuadras');
		  $this->set('n',8);
		  $this->set('no','no');
		  $rep  =  $this->Session->read('pais');
		  $est  =  $this->Session->read('esta');
		  $mun  =  $this->Session->read('muni');
		  $parr =  $this->Session->read('parr');
		  $cent =  $this->Session->read('cent');
		  $vial =  $this->Session->read('vial');
		  $this->Session->write('vere', $var);
		  $cond  =" cod_republica=".$rep." and cod_estado=".$est." and cod_municipio=".$mun." and cod_parroquia=".$parr." and cod_centro=".$cent." and cod_vialidad=".$vial;
		  $cond .=" and cod_vereda=".$var;
		  $lista=  $this->cugd01_cuadra->generateList($cond, 'cod_cuadra ASC', null, '{n}.cugd01_cuadra.cod_cuadra', '{n}.cugd01_cuadra.denominacion');
          if($lista!=null){
							$this->concatena($lista,'vector');
							$this->set('seleccion','');
						}else{
							$this->set('vector',array('0'=>'00 - N/A'));
							$this->set('seleccion','0');
						}
		break;
		}//fin wsitch
	}else{
		  $this->set('SELECT','');
		  $this->set('codigo','');
		  $this->set('seleccion','');
		  $this->set('n',15);
		  $this->set('no','no');
		 $this->set('vector','');
	}
}//fin select codigos presupuestarios

function mostrar3($select=null,$var=null) { //mostrar3 codigos presupuestarios
	$this->layout = "ajax";//echo $var;
if( $var!=null){
    $cond = "";
	switch($select){
		case 'pais':
		  $this->Session->write('pais',$var);
		  $cond .="  cod_republica=".$var;
		  $a=  $this->cugd01_republica->findAll($cond);
          $den=$a[0]['cugd01_republica']['denominacion'];
          $this->set('deno',$den);
          echo "<script>";
				echo "document.getElementById('s_municipios').innerHTML='<select id=municipios />';  ";
				echo "document.getElementById('s_parroquias').innerHTML='<select id=parroquias />';  ";
				echo "document.getElementById('s_centros').innerHTML='<select id=centros />';  ";
				echo "document.getElementById('s_calles').innerHTML='<select id=calles />';  ";
				echo "document.getElementById('s_veredas').innerHTML='<select id=veredas />';  ";
				echo "document.getElementById('s_cuadras').innerHTML='<select id=cuadras />';  ";
				echo "document.getElementById('c_2').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('c_3').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('c_4').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('c_5').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('c_6').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('c_7').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('c_8').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('d_2').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('d_3').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('d_4').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('d_5').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('d_6').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('d_7').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('d_8').innerHTML='<input type=text  size=10% class=inputtext />';  ";
        	echo "</script>";
		break;
		case 'estados':
		  $pais =  $this->Session->read('pais');
		  $this->Session->write('esta',$var);
		  $cond .="  cod_republica=".$pais." and cod_estado=".$var;
		  $a=  $this->cugd01_estados->findAll($cond);
          $den=$a[0]['cugd01_estados']['denominacion'];
          $this->set('deno',$den);
          echo "<script>";
          		echo "document.getElementById('s_parroquias').innerHTML='<select id=parroquias />';  ";
				echo "document.getElementById('s_centros').innerHTML='<select id=centros />';  ";
				echo "document.getElementById('s_calles').innerHTML='<select id=calles />';  ";
				echo "document.getElementById('s_veredas').innerHTML='<select id=veredas />';  ";
				echo "document.getElementById('s_cuadras').innerHTML='<select id=cuadras />';  ";
		  		echo "document.getElementById('c_3').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('c_4').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('c_5').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('c_6').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('c_7').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('c_8').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('d_3').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('d_4').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('d_5').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('d_6').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('d_7').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('d_8').innerHTML='<input type=text  size=10% class=inputtext />';  ";
        	echo "</script>";
		break;
		case 'municipios':
		  $pais =  $this->Session->read('pais');
		  $esta =  $this->Session->read('esta');
		  $this->Session->write('muni',$var);
		  $cond .=" cod_republica=".$pais." and cod_estado=".$esta." and cod_municipio=".$var;
		  $a=  $this->cugd01_municipios->findAll($cond);
          $den=$a[0]['cugd01_municipios']['denominacion'];
          $this->set('deno',$den);
          echo "<script>";
				echo "document.getElementById('s_centros').innerHTML='<select id=centros />';  ";
				echo "document.getElementById('s_calles').innerHTML='<select id=calles />';  ";
				echo "document.getElementById('s_veredas').innerHTML='<select id=veredas />';  ";
				echo "document.getElementById('s_cuadras').innerHTML='<select id=cuadras />';  ";
		  		echo "document.getElementById('c_4').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('c_5').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('c_6').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('c_7').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('c_8').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('d_4').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('d_5').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('d_6').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('d_7').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('d_8').innerHTML='<input type=text  size=10% class=inputtext />';  ";
        	echo "</script>";
		break;
		case 'parroquias':
		  $pais =  $this->Session->read('pais');
		  $esta =  $this->Session->read('esta');
		  $muni =  $this->Session->read('muni');
		  $this->Session->write('parr',$var);
		  $cond .=" cod_republica=".$pais." and cod_estado=".$esta." and cod_municipio=".$muni." and cod_parroquia=".$var;
		  $a=  $this->cugd01_parroquias->findAll($cond);
          $den=$a[0]['cugd01_parroquias']['denominacion'];
          $this->set('deno',$den);
          echo "<script>";
				echo "document.getElementById('s_calles').innerHTML='<select id=calles />';  ";
				echo "document.getElementById('s_veredas').innerHTML='<select id=veredas />';  ";
				echo "document.getElementById('s_cuadras').innerHTML='<select id=cuadras />';  ";
		  		echo "document.getElementById('c_5').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('c_6').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('c_7').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('c_8').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('d_5').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('d_6').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('d_7').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('d_8').innerHTML='<input type=text  size=10% class=inputtext />';  ";
        	echo "</script>";
		break;
		case 'centros':
		  //echo $select;
		  $pais =  $this->Session->read('pais');
		  $esta =  $this->Session->read('esta');
		  $muni =  $this->Session->read('muni');
		  $parr =  $this->Session->read('parr');
		  $this->Session->write('cent',$var);
		  $cond .=" cod_republica=".$pais." and cod_estado=".$esta." and cod_municipio=".$muni." and cod_parroquia=".$parr." and cod_centro=".$var;
		  $a=  $this->cugd01_centropoblados->findAll($cond);
          if(count($a)==0){
          	$cod='N/A';
          }else{
          $den=$a[0]['cugd01_centropoblados']['denominacion'];
          }
          $this->set('deno',$den);
          echo "<script>";
				echo "document.getElementById('s_veredas').innerHTML='<select id=veredas />';  ";
				echo "document.getElementById('s_cuadras').innerHTML='<select id=cuadras />';  ";
		  		echo "document.getElementById('c_6').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('c_7').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('c_8').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('d_6').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('d_7').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('d_8').innerHTML='<input type=text  size=10% class=inputtext />';  ";
        	echo "</script>";
		break;
		case 'calles':
		  $pais =  $this->Session->read('pais');
		  $esta =  $this->Session->read('esta');
		  $muni =  $this->Session->read('muni');
		  $parr =  $this->Session->read('parr');
		  $cent =  $this->Session->read('cent');
		  $this->Session->write('call',$var);
		  $cond .=" cod_republica=".$pais." and cod_estado=".$esta." and cod_municipio=".$muni." and cod_parroquia=".$parr." and cod_centro=".$cent." and cod_vialidad=".$var;
		  $a=  $this->cugd01_vialidad->findAll($cond);
          if(count($a)==0){
          	$den='N/A';
          }else{
          $den=$a[0]['cugd01_vialidad']['denominacion'];
          }
          $this->set('deno',$den);
            echo "<script>";
                echo "document.getElementById('s_cuadras').innerHTML='<select id=cuadras />';  ";
		  		echo "document.getElementById('c_7').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('d_7').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('c_8').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('d_8').innerHTML='<input type=text  size=10% class=inputtext />';  ";
        	echo "</script>";


		  $aa=  $this->cugd01_vereda->findAll($cond);
          if(count($aa)==0){
            echo "<script>";
//                echo "document.getElementById('s_veredas').innerHTML='<select id=veredas /></select>';  ";
		  		echo "document.getElementById('c_7').innerHTML='<input type=text style=\"text-align:center\" value=\"00\" size=10% class=inputtext />';  ";
		  		echo "document.getElementById('d_7').innerHTML='<input type=text value=\"N/A\" size=10% class=inputtext />';  ";
        	echo "</script>";
          }

          $cond .=" and cod_vereda=0";
		  $bb=  $this->cugd01_cuadra->findAll($cond);
          if(count($bb)==0 && count($aa)==0){
          	 echo "<script>";
                echo "document.getElementById('s_cuadras').innerHTML='<select id=cuadras ><option value=\"0\">00 - N/A</option></select>';  ";
		  		echo "document.getElementById('c_8').innerHTML='<input type=text style=\"text-align:center\" value=\"00\" size=10% class=inputtext />';  ";
		  		echo "document.getElementById('d_8').innerHTML='<input type=text value=\"N/A\" size=10% class=inputtext />';  ";
        	echo "</script>";
          }else if(count($aa)==0 && count($bb)!=0){
             echo "<script>";
                echo"ver_documento('/shp001_registro_contribuyentes/select3/cuadras/0','s_cuadras');";
		  		echo "document.getElementById('c_8').innerHTML='<input type=text size=10% class=inputtext />';  ";
		  		echo "document.getElementById('d_8').innerHTML='<input type=text size=10% class=inputtext />';  ";
        	echo "</script>";
          }



		break;
		case 'veredas':
		  $pais =  $this->Session->read('pais');
		  $esta =  $this->Session->read('esta');
		  $muni =  $this->Session->read('muni');
		  $parr =  $this->Session->read('parr');
		  $cent =  $this->Session->read('cent');
		  $call =  $this->Session->read('call');
		  $this->Session->write('vere',$var);
		  $cond .="cod_republica=".$pais." and cod_estado=".$esta." and cod_municipio=".$muni." and cod_parroquia=".$parr." and cod_centro=".$cent." and cod_vialidad=".$call." and cod_vereda=".$var;
		  $a=  $this->cugd01_vereda->findAll($cond);
          if(count($a)==0){
          	$den='N/A';
          }else{
          $den=$a[0]['cugd01_vereda']['denominacion'];
          }
          $this->set('deno',$den);
            echo "<script>";
                echo "document.getElementById('c_8').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('d_8').innerHTML='<input type=text  size=10% class=inputtext />';  ";
        	echo "</script>";

		  $bb=  $this->cugd01_cuadra->findAll($cond);
          if(count($bb)==0){

          	 echo "<script>";
		  		echo "document.getElementById('c_8').innerHTML='<input type=text style=\"text-align:center\" value=\"00\" size=10% class=inputtext />';  ";
		  		echo "document.getElementById('d_8').innerHTML='<input type=text value=\"N/A\" size=10% class=inputtext />';  ";
        	echo "</script>";

          }


		break;
		case 'cuadras':
		  $pais =  $this->Session->read('pais');
		  $esta =  $this->Session->read('esta');
		  $muni =  $this->Session->read('muni');
		  $parr =  $this->Session->read('parr');
		  $cent =  $this->Session->read('cent');
		  $call =  $this->Session->read('call');
		  $vere =  $this->Session->read('vere');
		  $this->Session->write('cuad', $var);
		  $cond .="cod_republica=".$pais." and cod_estado=".$esta." and cod_municipio=".$muni." and cod_parroquia=".$parr." and cod_centro=".$cent." and cod_vialidad=".$call." and cod_vereda=".$vere;
		  $cond .=" and cod_cuadra=".$var;
		  $a=  $this->cugd01_cuadra->findAll($cond);
          if(count($a)==0){
          	$den='N/A';
          }else{
          $den=$a[0]['cugd01_cuadra']['denominacion'];
          }
          $this->set('deno',$den);
		break;
		}//fin wsitch
	}else{
		echo "";
	}
}//fin mostrar3 co
   function SQLCAX($ano=null){//sql para busqueda de codigos de arranque con y sin año
         $sql_re = "cod_republica=".$this->verifica_SS(1)."  and    ";
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


function mostrar4($select=null,$var=null) { //mostrar3 codigos presupuestarios
	$this->layout = "ajax";
if( $var!=null){
  //  $cond = "";
   // $cond2 = $this->SQLCA();
	switch($select){
		case 'pais':
		  $this->Session->write('pais',$var);
		  $cond="cod_republica=".$var;//echo $cond;
		  $a=  $this->cugd01_republica->findAll($cond);
          $cod= $a[0]['cugd01_republica']['cod_republica'] >9 ?$a[0]['cugd01_republica']['cod_republica'] : "0".$a[0]['cugd01_republica']['cod_republica'] ;
          $this->set('codi',$cod);
		break;
		case 'estados':
		  $this->Session->write('esta',$var);
		  $pais =  $this->Session->read('pais');
		  $cond="cod_republica=".$pais." and cod_estado=".$var;//echo $cond;
		  $a=  $this->cugd01_estados->findAll($cond);
          $cod= $a[0]['cugd01_estados']['cod_estado'] >9 ?$a[0]['cugd01_estados']['cod_estado'] : "0".$a[0]['cugd01_estados']['cod_estado'] ;
          $this->set('codi',$cod);
		break;
		case 'municipios':
		  $pais =  $this->Session->read('pais');
		  $esta =  $this->Session->read('esta');
		  $this->Session->write('muni',$var);
		  $cond="cod_republica=".$pais." and cod_estado=".$esta." and cod_municipio=".$var;
		  $a=  $this->cugd01_municipios->findAll($cond);
		  //echo $cond;
          $cod=$a[0]['cugd01_municipios']['cod_municipio'] >9 ?$a[0]['cugd01_municipios']['cod_municipio'] : "0".$a[0]['cugd01_municipios']['cod_municipio'] ;
		$this->set('codi',$cod);
		break;
		case 'parroquias':
		  $pais =  $this->Session->read('pais');
		  $esta =  $this->Session->read('esta');
		  $muni =  $this->Session->read('muni');
		  $this->Session->write('parr',$var);
		  $cond="cod_republica=".$pais." and cod_estado=".$esta." and cod_municipio=".$muni." and cod_parroquia=".$var;//echo $cond;
		  $a=  $this->cugd01_parroquias->findAll($cond);
		  //echo $cond;
		  //print_r($a);
          $cod= $a[0]['cugd01_parroquias']['cod_parroquia'] >9 ?$a[0]['cugd01_parroquias']['cod_parroquia'] : "0".$a[0]['cugd01_parroquias']['cod_parroquia'] ;
          $this->set('codi',$cod);
		break;
		case 'centros':
		  $pais =  $this->Session->read('pais');
		  $esta =  $this->Session->read('esta');
		  $muni =  $this->Session->read('muni');
		  $parr =  $this->Session->read('parr');
		  $this->Session->write('cent',$var);
		  $cond="cod_republica=".$pais." and cod_estado=".$esta." and cod_municipio=".$muni." and cod_parroquia=".$parr." and cod_centro=".$var;
		  $a=  $this->cugd01_centropoblados->findAll($cond);
          if(count($a)==0){
          	$cod='00';
          }else{
          $cod= $a[0]['cugd01_centropoblados']['cod_centro'] >9 ?$a[0]['cugd01_centropoblados']['cod_centro'] : "0".$a[0]['cugd01_centropoblados']['cod_centro'] ;
          }
          $this->set('codi',$cod);
		break;
		case 'calles':
		  $pais =  $this->Session->read('pais');
		  $esta =  $this->Session->read('esta');
		  $muni =  $this->Session->read('muni');
		  $parr =  $this->Session->read('parr');
		  $cent =  $this->Session->read('cent');
		  $this->Session->write('call',$var);
		  $cond="cod_republica=".$pais." and cod_estado=".$esta." and cod_municipio=".$muni." and cod_parroquia=".$parr." and cod_centro=".$cent." and cod_vialidad=".$var;
		  $a=  $this->cugd01_vialidad->findAll($cond);
		  //print_r($a);
          if(count($a)==0){
          	$cod='00';
          }else{
          $cod=$a[0]['cugd01_vialidad']['cod_vialidad'] >9 ?$a[0]['cugd01_vialidad']['cod_vialidad'] : "0".$a[0]['cugd01_vialidad']['cod_vialidad'] ;
          }
		$this->set('codi',$cod);
		break;
		case 'veredas':
		  $pais =  $this->Session->read('pais');
		  $esta =  $this->Session->read('esta');
		  $muni =  $this->Session->read('muni');
		  $parr =  $this->Session->read('parr');
		  $cent =  $this->Session->read('cent');
		  $call =  $this->Session->read('call');
		  $this->Session->write('vere',$var);
		  $cond="cod_republica=".$pais." and cod_estado=".$esta." and cod_municipio=".$muni." and cod_parroquia=".$parr." and cod_centro=".$cent." and cod_vialidad=".$call." and cod_vereda=".$var;
		  $a=  $this->cugd01_vereda->findAll($cond);
          if(count($a)==0){
          	$cod='00';
          }else{
          $cod=$a[0]['cugd01_vereda']['cod_vereda'] >9 ?$a[0]['cugd01_vereda']['cod_vereda'] : "0".$a[0]['cugd01_vereda']['cod_vereda'] ;
          }
		$this->set('codi',$cod);
		break;
		case 'cuadras':
		  $pais =  $this->Session->read('pais');
		  $esta =  $this->Session->read('esta');
		  $muni =  $this->Session->read('muni');
		  $parr =  $this->Session->read('parr');
		  $cent =  $this->Session->read('cent');
		  $call =  $this->Session->read('call');
		  $vere =  $this->Session->read('vere');
		  $this->Session->write('cuad', $var);
		  $cond  ="cod_republica=".$pais." and cod_estado=".$esta." and cod_municipio=".$muni." and cod_parroquia=".$parr." and cod_centro=".$cent." and cod_vialidad=".$call." and cod_vereda=".$vere;
		  $cond .=" and cod_cuadra=".$var;
		  $a=  $this->cugd01_cuadra->findAll($cond);
          if(count($a)==0){
          	$cod='00';
          }else{
          $cod=$a[0]['cugd01_cuadra']['cod_cuadra'] >9 ?$a[0]['cugd01_cuadra']['cod_cuadra'] : "0".$a[0]['cugd01_cuadra']['cod_cuadra'] ;
          }
		$this->set('codi',$cod);
		break;
		}//fin wsitch
	}else{
		echo "";
	}
}//fin mostrar3 codigos presupuestarios

function guardar(){
	$this->layout = "ajax";
	//pr($this->data);
	$personalidad			    = $this->data['shp001_registro_contribuyentes']['personalidad'];
	$nacionalidad			    = $this->data['shp001_registro_contribuyentes']['nacionalidad'];
	if($nacionalidad==null){
		$nacionalidad=3;
	}
	$estado_civil			    = $this->data['shp001_registro_contribuyentes']['estado_civil'];
	if($estado_civil==null){
		$estado_civil=6;
	}
	$razon_social			    = $this->data['shp001_registro_contribuyentes']['razon_social'];
	$fecha_inscripcion		    = $this->data['shp001_registro_contribuyentes']['fecha_inscripcion'];
	$cod_profesion			    = $this->data['shp001_registro_contribuyentes']['cod_profesion'];
	if($cod_profesion==null){
		$cod_profesion=9999;
	}
	$cod_pais				    = $this->data['shp001_registro_contribuyentes']['cod_pais'];
	$cod_estados			    = $this->data['shp001_registro_contribuyentes']['cod_estados'];
	$cod_municipios			    = $this->data['shp001_registro_contribuyentes']['cod_municipios'];
	$cod_parroquias			    = $this->data['shp001_registro_contribuyentes']['cod_parroquias'];
	$cod_centros    			= $this->data['shp001_registro_contribuyentes']['cod_centros'];
	$cod_calles    				= $this->data['shp001_registro_contribuyentes']['cod_calles'];
	$cod_veredas   				= $this->data['shp001_registro_contribuyentes']['cod_veredas'];
	$cod_cuadras   				= !empty($this->data['shp001_registro_contribuyentes']['cod_cuadras'])?$this->data['shp001_registro_contribuyentes']['cod_cuadras']:0;
	$rif_cedula				    = $this->data['shp001_registro_contribuyentes']['rif_cedula'];
	$razon_social			    = $this->data['shp001_registro_contribuyentes']['razon_social'];
	$fecha_inscripcion		    = $this->data['shp001_registro_contribuyentes']['fecha_inscripcion'];
	$telefonos_fijos   			= $this->data['shp001_registro_contribuyentes']['telefonos_fijos'];
	if($telefonos_fijos==null){
		$telefonos_fijos=0;
	}
	$telefonos_celulares	    = $this->data['shp001_registro_contribuyentes']['telefonos_celulares'];
	if($telefonos_celulares==null){
		$telefonos_celulares=0;
	}
	$correo_electronico		    = $this->data['shp001_registro_contribuyentes']['correo_electronico'];
	if($correo_electronico==null){
		$correo_electronico=0;
	}
	$numero_casa_local		    = $this->data['shp001_registro_contribuyentes']['numero_local'];

		$SQL_INSERT ="INSERT INTO shd001_registro_contribuyentes (rif_cedula, personalidad_juridica, razon_social_nombres, fecha_inscripcion, nacionalidad, estado_civil, profesion,
  		cod_pais, cod_estado, cod_municipio,cod_parroquia, cod_centro_poblado, cod_calle_avenida, cod_vereda_edificio, numero_vivienda_local, telefonos_fijos, telefonos_celulares, correo_electronico, cod_cuadra)";
	 	$SQL_INSERT .=" VALUES ('".$rif_cedula."',$personalidad,'".$razon_social."','".$fecha_inscripcion."',$nacionalidad,$estado_civil,$cod_profesion,
	 	$cod_pais,$cod_estados,$cod_municipios,$cod_parroquias,$cod_centros,$cod_calles,$cod_veredas,'".$numero_casa_local."','".$telefonos_fijos."','".$telefonos_celulares."','".$correo_electronico."', '".$cod_cuadras."')";
		$sw = $this->shd001_registro_contribuyentes->execute($SQL_INSERT);
		if($sw>1){
			$this->set('Message_existe', 'LOS DATOS FUERON GUARDADOS');
		}else{
			$this->set('errorMessage', 'LOS DATOS NO FUERON GUARDADOS - POR FAVOR INTENTE DE NUEVO');
		}
		$this->index();
		$this->render("index");
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

function consultar($pagina=null){//echo 'si llego';
 		$this->layout = "ajax";
         if($pagina!=null){
          	 $pagina=$pagina;
          	 $this->set('pagina',$pagina);
          	  $Tfilas=$this->v_shd001_registro_con_consul->findCount();
          	 if($Tfilas==0){
          	 	$this->set('errorMessage', 'NO SE ENCONTRARON DATOS');
          	 	$this->index();
          		$this->render("index");
          	 }
          	 if($Tfilas!=0){
          	 $this->set('pag_cant',$pagina.'/'.$Tfilas);
          	 $datos=$this->v_shd001_registro_con_consul->findAll(null,null,'rif_cedula ASC',1,$pagina,null);
          	 $this->set('datos',$datos);
          	 foreach($datos as $row){
          	 	$rif_cedula  = $row['v_shd001_registro_con_consul']['rif_cedula'];
          	 }
          	 $datos2=$this->v_grilla_constribuyentes->findAll($this->SQLCA()." and rif_cedula='".$rif_cedula."'",null,'rif_cedula,tipo ASC',null,$pagina,null);
          	 $this->set('datos2',$datos2);
          	 $this->set('siguiente',$pagina+1);
          	 $this->set('anterior',$pagina-1);
             $this->bt_nav($Tfilas,$pagina);
             }
 }else{
 	$pagina=1;$this->set('pagina',$pagina);
          	 $Tfilas=$this->v_shd001_registro_con_consul->findCount();
          	 if($Tfilas==0){
          	 	$this->set('errorMessage', 'NO SE ENCONTRARON DATOS');
          	 	$this->index();
          		$this->render("index");
          	 }
          	 if($Tfilas!=0){
          	 $this->set('pag_cant',$pagina.'/'.$Tfilas);
          	 $datos=$this->v_shd001_registro_con_consul->findAll(null,null,'rif_cedula ASC',1,$pagina,null);
          	 $this->set('datos',$datos);
          	 foreach($datos as $row){
          	 	$rif_cedula  = $row['v_shd001_registro_con_consul']['rif_cedula'];
          	 }
          	 $datos2=$this->v_grilla_constribuyentes->findAll($this->SQLCA()." and rif_cedula='".$rif_cedula."'",null,'rif_cedula,tipo ASC',null,$pagina,null);
          	 $this->set('datos2',$datos2);
          	 $this->set('siguiente',$pagina+1);
          	 $this->set('anterior',$pagina-1);
             $this->bt_nav($Tfilas,$pagina);
			 }
}//fin function consultar2

}
function modificar($rif_cedula=null,$pagina=null){
	$this->layout = "ajax";
	$datos=$this->v_shd001_registro_con_consul->findAll("rif_cedula='".$rif_cedula."'",null,'rif_cedula ASC',1,null,null);
	$this->set('datos',$datos);
	$datos2=$this->v_grilla_constribuyentes->findAll($this->SQLCA()." and rif_cedula='".$rif_cedula."'",null,'rif_cedula,tipo ASC',null,null,null);
    $this->set('datos2',$datos2);
	$this->set('pagina',$pagina);
	$this->set('datos',$datos);
	foreach($datos as $row){
		$pais = $row['v_shd001_registro_con_consul']['cod_pais'];
		$esta= $row['v_shd001_registro_con_consul']['cod_estado'];
		$muni = $row['v_shd001_registro_con_consul']['cod_municipio'];
		$parr = $row['v_shd001_registro_con_consul']['cod_parroquia'];
		$cent = $row['v_shd001_registro_con_consul']['cod_centro_poblado'];
		$vial = $row['v_shd001_registro_con_consul']['cod_calle_avenida'];
		$vere = $row['v_shd001_registro_con_consul']['cod_vereda_edificio'];
		$cod_cuadra = $row['v_shd001_registro_con_consul']['cod_cuadra'];
	}
	$this->Session->write('pais',$pais);
	$this->Session->write('esta',$esta);
	$this->Session->write('muni',$muni);
	$this->Session->write('parr',$parr);
	$this->Session->write('cent',$cent);
	$this->Session->write('call',$vial);
	$this->Session->write('vial',$vial);
	$this->Session->write('vere',$vere);
	$this->Session->write('cuad',$cod_cuadra);

	$listaprofesion=$this->cnmd06_profesiones->generateList(null, 'denominacion ASC', null, '{n}.cnmd06_profesiones.cod_profesion', '{n}.cnmd06_profesiones.denominacion');
    $this->concatena($listaprofesion, 'cod_profesion');
    $listarepublica=$this->cugd01_republica->generateList(null, 'denominacion ASC', null, '{n}.cugd01_republica.cod_republica', '{n}.cugd01_republica.denominacion');
    $this->concatena($listarepublica, 'cod_pais');
    $listaestado=  $this->cugd01_estados->generateList('cod_republica='.$pais, 'denominacion ASC', null, '{n}.cugd01_estados.cod_estado', '{n}.cugd01_estados.denominacion');
    $this->concatena($listaestado, 'cod_estado');
    $listamunicipio=  $this->cugd01_municipios->generateList('cod_republica='.$pais.' and cod_estado='.$esta, 'denominacion ASC', null, '{n}.cugd01_municipios.cod_municipio', '{n}.cugd01_municipios.denominacion');
    $this->concatena($listamunicipio, 'cod_municipio');
    $listaparroquia=  $this->cugd01_parroquias->generateList('cod_republica='.$pais.' and cod_estado='.$esta.' and cod_municipio='.$muni, 'denominacion ASC', null, '{n}.cugd01_parroquias.cod_parroquia', '{n}.cugd01_parroquias.denominacion');
    $this->concatena($listaparroquia, 'cod_parroquia');
    $listacentro=  $this->cugd01_centropoblados->generateList('cod_republica='.$pais.' and cod_estado='.$esta.' and cod_municipio='.$muni.' and cod_parroquia='.$parr, 'denominacion ASC', null, '{n}.cugd01_centropoblados.cod_centro', '{n}.cugd01_centropoblados.denominacion');
    $this->concatena($listacentro, 'cod_centro');
    $listacalle=  $this->cugd01_vialidad->generateList('cod_republica='.$pais.' and cod_estado='.$esta.' and cod_municipio='.$muni.' and cod_parroquia='.$parr.' and cod_centro='.$cent, 'denominacion ASC', null, '{n}.cugd01_vialidad.cod_vialidad', '{n}.cugd01_vialidad.denominacion');
    	if($listacalle!=null){
			$this->concatena($listacalle,'cod_calle');
		}else{
			$this->set('cod_calle',array());
		}
	$listavereda=  $this->cugd01_vereda->generateList('cod_republica='.$pais.' and cod_estado='.$esta.' and cod_municipio='.$muni.' and cod_parroquia='.$parr.' and cod_centro='.$cent.' and cod_vialidad='.$vial, 'denominacion ASC', null, '{n}.cugd01_vereda.cod_vereda', '{n}.cugd01_vereda.denominacion');
    	if($listavereda!=null){
			$this->concatena($listavereda,'cod_vereda');
		}else{
			$this->set('cod_vereda',array('0'=>'00'));
		}

	$listacuadra=  $this->cugd01_cuadra->generateList('cod_republica='.$pais.' and cod_estado='.$esta.' and cod_municipio='.$muni.' and cod_parroquia='.$parr.' and cod_centro='.$cent.' and cod_vialidad='.$vial.' and cod_vereda='.$vere, 'denominacion ASC', null, '{n}.cugd01_cuadra.cod_cuadra', '{n}.cugd01_cuadra.denominacion');
    	if($listavereda!=null){
			$this->concatena($listacuadra,'cod_cuadra');
		}else{
			$this->set('cod_cuadra',array('0'=>'00'));
		}

}

function guardar_modificar($rif_cedula=null,$pagina=null){
	$this->layout = "ajax";
	$personalidad			    = $this->data['shp001_registro_contribuyentes']['personalidad'];

	$nacionalidad			    = $this->data['shp001_registro_contribuyentes']['nacionalidad'];
	if($nacionalidad==null){
		$nacionalidad=3;
	}
	$estado_civil			    = $this->data['shp001_registro_contribuyentes']['estado_civil'];
	if($estado_civil==null){
		$estado_civil=6;
	}
	$razon_social			    = $this->data['shp001_registro_contribuyentes']['razon_social'];
	$fecha_inscripcion		    = $this->data['shp001_registro_contribuyentes']['fecha_inscripcion'];
	$cod_profesion			    = $this->data['shp001_registro_contribuyentes']['cod_profesion'];
	if($cod_profesion==null){
		$cod_profesion=9999;
	}
	$telefonos_fijos   			= $this->data['shp001_registro_contribuyentes']['telefonos_fijos'];
	if($telefonos_fijos==null){
		$telefonos_fijos=0;
	}
	$telefonos_celulares	    = $this->data['shp001_registro_contribuyentes']['telefonos_celulares'];
	if($telefonos_celulares==null){
		$telefonos_celulares=0;
	}
	$correo_electronico		    = $this->data['shp001_registro_contribuyentes']['correo_electronico'];
	if($correo_electronico==null){
		$correo_electronico=0;
	}
	$cod_pais				    = $this->data['shp001_registro_contribuyentes']['cod_pais'];
	$cod_estados			    = $this->data['shp001_registro_contribuyentes']['cod_estados'];
	$cod_municipios			    = $this->data['shp001_registro_contribuyentes']['cod_municipios'];
	$cod_parroquias			    = $this->data['shp001_registro_contribuyentes']['cod_parroquias'];
	$cod_centros    			= $this->data['shp001_registro_contribuyentes']['cod_centros'];
	$cod_calles    				= $this->data['shp001_registro_contribuyentes']['cod_calles'];
	$cod_veredas   				= $this->data['shp001_registro_contribuyentes']['cod_veredas'];
	$cod_cuadras   				= !empty($this->data['shp001_registro_contribuyentes']['cod_cuadras'])?$this->data['shp001_registro_contribuyentes']['cod_cuadras']:0;
	$numero_casa_local		    = $this->data['shp001_registro_contribuyentes']['numero_local'];
	$guardar="update shd001_registro_contribuyentes set numero_vivienda_local='".$numero_casa_local."' ,personalidad_juridica=".$personalidad.", razon_social_nombres='".$razon_social."', fecha_inscripcion='".$fecha_inscripcion."', nacionalidad=".$nacionalidad.", estado_civil=".$estado_civil.", profesion=".$cod_profesion.",cod_pais=".$cod_pais.", cod_estado=".$cod_estados.", cod_municipio=".$cod_municipios.",cod_parroquia=".$cod_parroquias.", cod_centro_poblado=".$cod_centros.", cod_calle_avenida=".$cod_calles.", cod_vereda_edificio=".$cod_veredas.",telefonos_fijos='".$telefonos_fijos."', telefonos_celulares='".$telefonos_celulares."',correo_electronico='".$correo_electronico."', cod_cuadra='".$cod_cuadras."' where rif_cedula='".$rif_cedula."'";
	$sw = $this->shd001_registro_contribuyentes->execute($guardar);
	$this->set('Message_existe', 'LOS DATOS FUERON GUARDADOS');
	$this->consultar($pagina);
	$this->render("consultar");

}
function eliminar($rif=null,$pagina=null){
	$this->layout = "ajax";
	$sql ="rif_cedula='".$rif."'";
	$v1=$this->shd900_cobranza_diaria->findCount($sql);
	$v2=$this->shd100_solicitud->findCount($sql);
	$v3=$this->shd200_vehiculos->findCount($sql);
	$v4=$this->shd300_propaganda->findCount($sql);
	$v5=$this->shd400_propiedad->findCount($sql);
	$v6=$this->shd500_aseo_domiciliario->findCount($sql);
	$v7=$this->shd600_solicitud_arrendamiento->findCount($sql);
	$v8=$this->shd700_credito_vivienda->findCount($sql);

	if($v1==0 and $v2==0 and $v3==0 and $v4==0 and $v5==0 and $v6==0 and $v7==0 and $v8==0){
		$sql1 ="DELETE  FROM  shd001_registro_contribuyentes where ".$sql;
		$this->shd001_registro_contribuyentes->execute($sql1);
		$y=$this->v_shd001_registro_contribuyentes->findCount();
		if($pagina>$y){
 			$pagina=$pagina-1;
 		}
 		if($y!=0){
	  		$this->set('Message_existe', 'Registro Eliminado con exito.');
      	 	$this->consultar($pagina);//si es el primero solamente
      		$this->render("consultar");
		}else if($y==0){
			$this->set('Message_existe', 'Registro Eliminado con exito.');
			$this->index();
      		$this->render("index");
		}//fin if
	}else{
		$this->set('errorMessage', 'Este contribuyente se encuentra en uso actualmente');
      	$this->consultar($pagina);//si es el primero solamente
      	$this->render("consultar");
	}
}
//fin eliminar

function buscar($var1=null){
	$this->layout="ajax";
	$this->set("opcion",$var1);
	$this->Session->delete('pista');
}//fin function

function buscar_por_pista2($var1=null, $var2=null, $var3=null){
$this->layout="ajax";
    if($var3==null){$var2 = strtoupper($var2);
					$this->Session->write('pista', $var2);//echo "(rif_cedula LIKE '%$var2%' or razon_social_nombres LIKE '%$var2%')";
					$Tfilas=$this->v_shd001_registro_contribuyentes->findCount("(rif_cedula LIKE '%$var2%' or quitar_acentos(razon_social_nombres) LIKE quitar_acentos('%$var2%'))");
					        if($Tfilas!=0){
					        	$pagina=1;
					        	$Tfilas=(int)ceil($Tfilas/50);
					        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
								$this->set('total_paginas',$Tfilas);
								$this->set('pagina_actual',$pagina);
								$this->set('ultimo',$Tfilas);
					     	    $datos_filas=$this->v_shd001_registro_contribuyentes->findAll("(rif_cedula LIKE '%$var2%' or quitar_acentos(razon_social_nombres) LIKE quitar_acentos('%$var2%'))",null,"rif_cedula ASC",50,1,null);
						        $this->set("datosFILAS",$datos_filas);
						        $this->set('siguiente',$pagina+1);
								$this->set('anterior',$pagina-1);
								$this->bt_nav($Tfilas,$pagina);
					          }else{
					        	$this->set("datosFILAS",'');
					          }
            }else{
						$var22 = $this->Session->read('pista');
						$var22 = strtoupper($var22);//echo "(rif_cedula LIKE '%$var22%' or razon_social_nombres LIKE '%$var22%')";
						$Tfilas=$this->v_shd001_registro_contribuyentes->findCount("(rif_cedula LIKE '%$var22%' or quitar_acentos(razon_social_nombres) LIKE quitar_acentos('%$var2%'))");
						        if($Tfilas!=0){
						        	$pagina=$var3;
						        	$Tfilas=(int)ceil($Tfilas/50);
						        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
									$this->set('total_paginas',$Tfilas);
									$this->set('pagina_actual',$pagina);
									$this->set('ultimo',$Tfilas);
						     	    $datos_filas=$this->v_shd001_registro_contribuyentes->findAll("(rif_cedula LIKE '%$var22%' or quitar_acentos(razon_social_nombres) LIKE quitar_acentos('%$var2%'))",null,"rif_cedula ASC",50,$pagina,null);
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

function consulta2($numero=null){
	$this->layout = "ajax";
 	$cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$modulo = $this->Session->read('Modulo');
	$c = "rif_cedula='".$numero."'";
    $veri=$this->v_shd001_registro_con_consul->findCount($c);
      if($veri > 0){
      	$datacpcp01=$this->v_shd001_registro_con_consul->findAll($c);
      	$this->set('datos',$datacpcp01);
      	$datos2=$this->v_grilla_constribuyentes->findAll($this->SQLCA()." and rif_cedula='".$numero."'",null,'rif_cedula,tipo ASC',null,1,null);
    	$this->set('datos2',$datos2);
      }else{
	  			$this->index();
				$this->render("index");
          	 }
}//fin function consultar2

}//fin class
?>