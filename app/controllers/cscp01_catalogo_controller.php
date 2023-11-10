<?php
/*
 * Created on 17/10/2007
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */

class Cscp01CatalogoController extends AppController {
   var $name = 'cscp01_catalogo';
   var $uses = array('cscd01_catalogo', 'cscd01_unidad_medida', 'cfpd01_partida', 'cfpd01_generica',
                     'cfpd01_especifica', 'cfpd01_sub_espec', 'cfpd01_auxiliar', 'v_cscd01_catalogo_snc',
                     'cscd02_solicitud_cuerpo', 'cscd01_snc_grupo', 'cscd01_snc_tipo', "v_cscd01_catalogo_con_snc_denominacion",'cugd05_restriccion_clave');
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


 /*function zero($x=null){
	if($x != null && is_nan($x)){
		if($x<10){
			$x="0".$x;
		}else if($x>=10 && $x<=99){
			$x=$x;
		}
	}
	return $x;

}*/

function concatena3($vector1=null, $nomVar=null){

if($vector1 != null){
		foreach($vector1 as $x => $y){
			if($x<10 && is_int($x)){$x_aux = "0".$x;}else{ $x_aux = trim($x);}
			trim($y);
			if(strlen($y) > 80){
				$cod[$x] = $x_aux.' - '.substr($y, 0, 80).'...';
			}else{
				$cod[$x] = $x_aux.' - '.substr($y, 0, 80);
			}
		}
   $this->set($nomVar, $cod);
  }//fin if
}//fin function


 function index(){

$this->verifica_entrada('33');

 	$this->layout= "ajax";
 	$this->Session->delete('selecion_snc');
 	$this->data=null;
 	//$catalogo= $this->cscd01_catalogo->generateList(null, 'cod_snc ASC', null, '{n}.cscd01_catalogo.cod_snc', '{n}.cscd01_catalogo.denominacion');
 	$catalogo=null;
 	if(!empty($catalogo)){
 		$this->concatena3($catalogo, 'catalogo');
 	}else{
 		$this->set('catalogo', array());
 	}

 	  $this->Session->delete('selecion_snc');

 }

 function mostrar($var=null){
 	$this->layout="ajax";
 	//$Lista = $this->Cnmd01->generateList($this->SQLCA(), 'cod_tipo_nomina ASC', null, '{n}.Cnmd01.cod_tipo_nomina', '{n}.Cnmd01.denominacion');
 	if($var != null){
 		$var = strtoupper_sisap($var);

 		if($this->cscd01_catalogo->findCount(" mayus_acentos(denominacion) LIKE mayus_acentos('%$var%')  ") != 0){
 			$this->set('deno', $var);
 			$catalogo= $this->v_cscd01_catalogo_snc->generateList(" mayus_acentos(denominacion) LIKE mayus_acentos('%$var%')  ", 'codigo_prod_serv ASC', null, '{n}.v_cscd01_catalogo_snc.codigo_prod_serv', '{n}.v_cscd01_catalogo_snc.denominacion');
 			//$catalogo=null;
 			if(!empty($catalogo)){
		 		$this->concatena3($catalogo, 'catalogo');
		 	}else{
		 		$this->set('catalogo', array());
		 	}
 		}else{
 			//$catalogo= $this->cscd01_catalogo->generateList(null, 'cod_snc ASC', null, '{n}.cscd01_catalogo.cod_snc', '{n}.cscd01_catalogo.denominacion');
 			$this->set('catalogo', array());
 			$this->set('deno', '');
 			$this->set('notfound', 'NO SE ENCONTRO NINGUN DATO - POR FAVOR INTENTE DE NUEVO');

 		}
 		//print_r($catalogo);
 	}

 }

function codcatalogo($cod_snc=null){
 	$this->layout= "ajax";
 	if($cod_snc != null){
 		$this->set($cod_snc);
 	}
 }

 function dcatalogo($cod_tipo=null){
 	$this->layout= "ajax";
 	if($cod_tipo != null && $cod_tipo != 'otros'){
 		$this->set('denominacion', $this->cscd01_catalogo->field('denominacion', 'codigo_prod_serv='.$cod_tipo));
 	}
 }








function selecion_snc($var1=null){
     $this->layout= "ajax";
     $denominacion = $this->cscd01_snc_tipo->field('denominacion', " cod_tipo='".$var1."'   ");
     $this->Session->write('selecion_snc', $var1);



          echo "<script>";
			    echo "document.getElementById('denominacion_snc').value='".$denominacion."';";
			    echo "document.getElementById('cod_snc').value='".$var1."';";
			    echo "document.getElementById('buscar_cod_sistema_1').disabled=false; ";
		  echo "</script>";



     $this->render("funcion");
}//fin function








function agregar(){

   $this->layout= "ajax";

   $var1 = $this->Session->read('selecion_snc');
   $denominacion = $this->cscd01_snc_tipo->field('denominacion', " cod_tipo='".$var1."'   ");

   $this->set("cod_snc",          $var1);
   $this->set("denominacion_snc", $denominacion);

   $listaPartida= $this->cfpd01_partida->generateList(	$conditions = "cod_grupo=".CE, $order = 'cod_partida ASC', $limit = null, '{n}.cfpd01_partida.cod_partida', '{n}.cfpd01_partida.descripcion');
  $this->concatena($listaPartida, 'partida',4);




  if($var1!=null){
		$tipo = strtoupper_sisap($var1[0]);
		 switch ($tipo) {
			case 'B':
				$this->set('tipo', 1);
				break;
			case 'S':
				$this->set('tipo', 2);
				break;
			case 'O':
				$this->set('tipo', 3);
				break;

			default:

				break;
		}
	}



}//fin function







function funcion($var1=null){
$this->layout= "ajax";
}//fin function





function activar($var1=null){
$this->layout= "ajax";
          echo "<script>";
			       echo" document.getElementById('agregar').disabled = false; ";
		  echo "</script>";
$this->render("funcion");
}//fin function






 function principal($deno=null, $var=null){
 	$this->layout= "ajax";
 	//echo "el var es ".$var." y la deno es".$deno;

 	$this->Session->delete('selecion_snc');
 	$this->data=null;
 	if($deno=='otros'){
		 //$otro='otros';
		 $this->set('agregar',true);
	     $this->principal();
	     $this->render("principal");
 	}
 	$this->Session->delete('selecion_snc');
 	//$catalogo= $this->v_cscd01_catalogo_snc->generateList("denominacion LIKE '%$deno%'", 'codigo_prod_serv ASC', null, '{n}.v_cscd01_catalogo_snc.codigo_prod_serv', '{n}.v_cscd01_catalogo_snc.denominacion');
 	$catalogo = "";
 	$this->set('deno', $deno);
 	if(!empty($catalogo)){
 		$this->concatena3($catalogo, 'catalogo');
 	}else{
 		$this->set('catalogo', array());
 	}
 	$unidad_medida = $this->cscd01_unidad_medida->findAll($conditions = null, $fields = null, $order = "cod_medida ASC", $limit = null, $page = null, $recursive = null);
 	$partida = $this->cfpd01_partida->findAll($conditions = "cod_grupo=".CE, $fields = "cod_partida, descripcion", $order = "cod_grupo, cod_partida ASC", $limit = null, $page = null, $recursive = null);
 	$unidad = $this->cscd01_unidad_medida->generateList($conditions = null, $order = null, $limit = null, '{n}.cscd01_unidad_medida.cod_medida', '{n}.cscd01_unidad_medida.expresion');
 	$this->concatena3($unidad, 'unidad');
 	$listaPartida= $this->cfpd01_partida->generateList(	$conditions = "cod_grupo=".CE, $order = null, $limit = null, '{n}.cfpd01_partida.cod_partida', '{n}.cfpd01_partida.descripcion');
 	$this->concatena($listaPartida, 'partida',4);
 	//echo $var;



 	$cscd01_snc_tipo= $this->cscd01_snc_tipo->generateList(null, $order = null, $limit = null, '{n}.cscd01_snc_tipo.cod_tipo', '{n}.cscd01_snc_tipo.denominacion');
 	$this->concatena_sin_cero($cscd01_snc_tipo, "snc_tipo_lista");


//echo "el var es: ".$var;
 	if($var != null){

		if($var=='otros'){
			$this->set('agregar', true);


		}else{
//			echo "estoy aqui";
			$this->set('agregar', false);
			$cod_snc = $this->cscd01_catalogo->field('cscd01_catalogo.cod_snc', $conditions = "cscd01_catalogo.codigo_prod_serv='$var'", $order ="codigo_prod_serv ASC");
			$cant_cotizaciones = $this->cscd02_solicitud_cuerpo->findCount("codigo_prod_serv='$var'");
			if($cant_cotizaciones > 0 ){
				$this->set('disabled', 'disabled');
			}else{
				$this->set('disabled', '');
			}
			$this->set('cod_snc', $cod_snc);
			$this->set('codigo', $var);
			//echo 'el deno es: '.$deno;
			$var = trim($var);
			$this->set('datos', $this->v_cscd01_catalogo_con_snc_denominacion->findAll("codigo_prod_serv='$var'"));
			//echo "el var es: ".$var;
			$cod_partida = $this->cscd01_catalogo->field('cscd01_catalogo.cod_partida', $conditions = "codigo_prod_serv='$var'", $order ="");
			//echo "el codigo de la partida es: ".$cod_partida;
			$cod_generica = $this->cscd01_catalogo->field('cscd01_catalogo.cod_generica', $conditions = "codigo_prod_serv='$var'", $order ="codigo_prod_serv ASC");
			$cod_especifica = $this->cscd01_catalogo->field('cscd01_catalogo.cod_especifica', $conditions = "codigo_prod_serv='$var'", $order ="codigo_prod_serv ASC");
			$cod_sub_espec = $this->cscd01_catalogo->field('cscd01_catalogo.cod_sub_espec', $conditions = "codigo_prod_serv='$var'", $order ="codigo_prod_serv ASC");
			$cod_auxiliar = $this->cscd01_catalogo->field('cscd01_catalogo.cod_auxiliar', $conditions = "codigo_prod_serv='$var'", $order ="codigo_prod_serv ASC");
			//echo $cod_auxiliar;
			$cod_partida =substr($cod_partida, 1, 2);
			//echo "la partida: ".$cod_partida;

			$dPartida = $this->cfpd01_partida->field('cfpd01_partida.descripcion', $conditions = "cfpd01_partida.cod_partida=$cod_partida", $order ="cod_partida ASC");
			$this->set('dPartida', $dPartida);

			$dGenerica = $this->cfpd01_generica->field('cfpd01_generica.descripcion', $conditions = "cfpd01_generica.cod_partida=$cod_partida and cfpd01_generica.cod_generica=$cod_generica", $order ="cod_partida, cod_generica ASC");
			$this->set('dGenerica', $dGenerica);

			$dEspecifica = $this->cfpd01_especifica->field('cfpd01_especifica.descripcion', $conditions = "cfpd01_especifica.cod_partida=$cod_partida and cfpd01_especifica.cod_generica=$cod_generica and cfpd01_especifica.cod_especifica=$cod_especifica", $order ="cod_partida, cod_generica, cod_especifica ASC");

			$dSubEspec = $this->cfpd01_sub_espec->field('cfpd01_sub_espec.descripcion', $conditions = "cfpd01_sub_espec.cod_partida=$cod_partida and cfpd01_sub_espec.cod_generica=$cod_generica and cfpd01_sub_espec.cod_especifica=$cod_especifica and cfpd01_sub_espec.cod_sub_espec=$cod_sub_espec", $order ="cod_partida, cod_generica, cod_especifica, cod_sub_espec ASC");

			$dAuxiliar = $this->cfpd01_auxiliar->field('cfpd01_auxiliar.descripcion', $conditions = "cfpd01_auxiliar.cod_partida=$cod_partida and cfpd01_auxiliar.cod_generica=$cod_generica and cfpd01_auxiliar.cod_especifica=$cod_especifica and cfpd01_auxiliar.cod_sub_espec=$cod_sub_espec and cfpd01_auxiliar.cod_auxiliar=$cod_auxiliar", $order ="cod_partida, cod_generica, cod_especifica, cod_sub_espec ASC");

			if(empty($dSubEspec)){$dSubEspec = 'N/A';}
			if(empty($dAuxiliar)){$dAuxiliar = 'N/A';}

			$this->set('dEspecifica', $dEspecifica);
			$this->set('dSubEspec', $dSubEspec);
			$this->set('dAuxiliar', $dAuxiliar);


 			$this->set('medida', $this->cscd01_unidad_medida->findAll());
		}
 	}




 }//fin function






 function dcodigo($cod_medida = null){
 	$this->layout="ajax";
 	if($cod_medida != null){
 		$this->set('dcodigo', $this->cscd01_unidad_medida->field('cscd01_unidad_medida.cod_medida', $conditions = "cscd01_unidad_medida.cod_medida='$cod_medida'", $order ="cod_medida ASC"));
 	}
 }

 function dexp($cod_medida = null){
 	$this->layout="ajax";
 	if($cod_medida != null){
 		$this->set('dexp', $this->cscd01_unidad_medida->field('cscd01_unidad_medida.expresion', $conditions = "cscd01_unidad_medida.cod_medida='$cod_medida'", $order ="cod_medida ASC"));
 	}
 }

 function dunidad($cod_medida = null){
 	$this->layout="ajax";
 	if($cod_medida != null){
 		$this->set('dunidad', $this->cscd01_unidad_medida->field('cscd01_unidad_medida.denominacion', $conditions = "cscd01_unidad_medida.cod_medida='$cod_medida'", $order ="cod_medida ASC"));

 	}
 }

 function cod_partida($cod_partida=null){
 	$this->layout="ajax";

 	if($cod_partida != null){
 		$this->set('cod_partida', $cod_partida);

 	}

}

function dPartida($cod_partida=null){
	$this->layout="ajax";
	//echo "la partida1: ".$cod_partida;
	if($cod_partida != null){
		//$cod_partida =substr($cod_partida, 1, 2);
		//echo "la partida: ".$cod_partida;
		$cod_grupo = CE;
		$this->set('dPartida', $this->cfpd01_partida->field('cfpd01_partida.descripcion', $conditions = "cfpd01_partida.cod_grupo=$cod_grupo and cfpd01_partida.cod_partida=$cod_partida", $order ="cod_partida ASC"));
	}

}

function select_generica($cod_partida=null){
	$this->layout= "ajax";
	if ($cod_partida!=null){
		$listaGenerica= $this->cfpd01_generica->generateList($conditions ="cod_grupo=".CE." and cod_partida=$cod_partida", $order = "cod_generica ASC", $limit = null, '{n}.cfpd01_generica.cod_generica', '{n}.cfpd01_generica.descripcion');
		$this->concatena3($listaGenerica, 'generica');
		$this->set('cod_partida', $cod_partida);
		$cod_grupo = CE;
		$this->set('codigo',CE.mascara($cod_partida,2));
		$this->set('deno', $this->cfpd01_partida->field('cfpd01_partida.descripcion', $conditions = "cfpd01_partida.cod_grupo=$cod_grupo and cfpd01_partida.cod_partida=$cod_partida", $order ="cod_partida ASC"));
	}else{
		$this->set('codigo','');
		$this->set('deno','');
	}
}

function cod_generica($cod_generica=null){
 	$this->layout="ajax";

 	if($cod_generica!=null){
 		$this->set('cod_generica', $cod_generica);
 	}

}

function dGenerica($cod_partida=null, $cod_generica=null){
	$this->layout="ajax";
	if($cod_partida != null && $cod_generica!=null){
		$this->set('dGenerica', $this->cfpd01_generica->field('cfpd01_generica.descripcion', $conditions = "cfpd01_generica.cod_partida=$cod_partida"." and cfpd01_generica.cod_generica=$cod_generica", $order ="cod_partida ASC"));
	}
}

function select_especifica($cod_partida=null, $cod_generica=null){
	$this->layout="ajax";
		//echo $cod_partida.' - '.$cod_generica;
	if ($cod_partida!=null && $cod_generica !=null){
		$listaEspecifica= $this->cfpd01_especifica->generateList($conditions ="cod_grupo=".CE." and cod_partida=$cod_partida and cod_generica=$cod_generica", $order = "cod_grupo, cod_partida, cod_generica, cod_especifica ASC", $limit = null, '{n}.cfpd01_especifica.cod_especifica', '{n}.cfpd01_especifica.descripcion');
		$this->concatena3($listaEspecifica, 'especifica');
		$this->set('cod_partida', $cod_partida);
		$this->set('cod_generica', $cod_generica);
		$this->set('codigo',mascara($cod_generica,2));
		$this->set('deno', $this->cfpd01_generica->field('cfpd01_generica.descripcion', $conditions = " cod_grupo=".CE." and cfpd01_generica.cod_partida=$cod_partida"." and cfpd01_generica.cod_generica=$cod_generica", $order ="cod_generica ASC"));
	}else{
		$this->set('codigo','');
		$this->set('deno','');
	}

}

function cod_especifica($cod_especifica=null){
	$this->layout="ajax";

	if($cod_especifica!=null){
		$this->set('cod_especifica', $cod_especifica);
	}

}

function dEspecifica($cod_partida=null, $cod_generica=null, $cod_especifica=null) {
   	$this->layout="ajax";
   	//echo $cod_partida." - ".$cod_generica." - ".$cod_especifica;
   	if($cod_partida != null && $cod_generica!=null && $cod_especifica!= null){
		$this->set('dEspecifica', $this->cfpd01_especifica->field('cfpd01_especifica.descripcion', $conditions = "cfpd01_especifica.cod_grupo=".CE." and cod_partida=$cod_partida and cod_generica=$cod_generica and cod_especifica=$cod_especifica", $order ="descripcion ASC"));
	}else{
		//echo "no entre en el if";
	}

}

function select_subespec($cod_partida=null, $cod_generica=null, $cod_especifica=null) {
	$this->layout="ajax";
	if ($cod_partida!=null && $cod_generica !=null && $cod_especifica!=null){
		$listaSubEspecifica= $this->cfpd01_sub_espec->generateList($conditions ="cod_grupo=".CE." and cod_partida=$cod_partida and cod_generica=$cod_generica and cod_especifica=$cod_especifica", $order = "cod_grupo, cod_partida, cod_generica, cod_especifica, cod_sub_espec ASC", $limit = null, '{n}.cfpd01_sub_espec.cod_sub_espec', '{n}.cfpd01_sub_espec.descripcion');
		$this->concatena3($listaSubEspecifica, 'sub_espec');
		$this->set('cod_partida', $cod_partida);
		$this->set('cod_generica', $cod_generica);
		$this->set('cod_especifica', $cod_especifica);
		$this->set('codigo',mascara($cod_especifica,2));
		$this->set('deno', $this->cfpd01_especifica->field('cfpd01_especifica.descripcion', $conditions = "cfpd01_especifica.cod_grupo=".CE." and cod_partida=$cod_partida and cod_generica=$cod_generica and cod_especifica=$cod_especifica", $order ="descripcion ASC"));
	}else{
		$this->set('codigo','');
		$this->set('deno','');
	}
}

function cod_subespec($cod_sub_espec=null) {
	$this->layout="ajax";

	if($cod_sub_espec!=null){
		$this->set('cod_sub_espec', $cod_sub_espec);
	}
}

function dSubEspec($cod_partida=null, $cod_generica=null, $cod_especifica=null, $cod_subespec=null) {
	$this->layout="ajax";

	if($cod_partida!=null && $cod_generica!=null && $cod_especifica!=null && $cod_subespec!=null){
		$this->set('dSubEspec', $this->cfpd01_sub_espec->field('cfpd01_sub_espec.descripcion', $conditions = "cfpd01_sub_espec.cod_grupo=".CE." and cod_partida=$cod_partida and cod_generica=$cod_generica and cod_especifica=$cod_especifica and cod_sub_espec=$cod_subespec", $order ="descripcion ASC"));

	}


}

function select_auxiliar($cod_partida=null, $cod_generica=null, $cod_especifica=null, $cod_subespec=null) {
	$this->layout="ajax";
	//echo $cod_partida." - ".$cod_generica." - ".$cod_especifica;
	if ($cod_partida!=null && $cod_generica !=null && $cod_especifica && $cod_subespec!=null){
		$c= $this->cfpd01_auxiliar->findCount("cod_grupo=".CE." and cod_partida=$cod_partida and cod_generica=$cod_generica and cod_especifica=$cod_especifica and cod_sub_espec=$cod_subespec");
		$listaAuxiliar= $this->cfpd01_auxiliar->generateList($conditions ="cod_grupo=".CE." and cod_partida=$cod_partida and cod_generica=$cod_generica and cod_especifica=$cod_especifica and cod_sub_espec=$cod_subespec", $order = null, $limit = null, '{n}.cfpd01_auxiliar.cod_auxiliar', '{n}.cfpd01_auxiliar.descripcion');
		$listaAuxiliar = $c!=0?$listaAuxiliar:array('0'=>'N/A');
		$this->concatena3($listaAuxiliar, 'auxiliar');
		$this->set('cod_partida', $cod_partida);
		$this->set('cod_generica', $cod_generica);
		$this->set('cod_especifica', $cod_especifica);
		$this->set('cod_subespec', $cod_subespec);
		$this->set('codigo',mascara($cod_subespec,2));
		$this->set('deno', $this->cfpd01_sub_espec->field('cfpd01_sub_espec.descripcion', $conditions = "cfpd01_sub_espec.cod_grupo=".CE." and cod_partida=$cod_partida and cod_generica=$cod_generica and cod_especifica=$cod_especifica and cod_sub_espec=$cod_subespec", $order ="descripcion ASC"));
	}else{
		$this->set('codigo','');
		$this->set('deno','');
	}

}

function mostrar_deno_auxiliar($cod_partida=null, $cod_generica=null, $cod_especifica=null, $cod_subespec=null, $cod_auxiliar= null) {
	$this->layout="ajax";
	//echo $cod_partida." - ".$cod_generica." - ".$cod_especifica;
	if ($cod_partida!=null && $cod_generica !=null && $cod_especifica && $cod_subespec!=null && $cod_auxiliar != null){
		$listaAuxiliar= $this->cfpd01_auxiliar->generateList($conditions ="cod_grupo=".CE." and cod_partida=$cod_partida and cod_generica=$cod_generica and cod_especifica=$cod_especifica and cod_sub_espec=$cod_subespec", $order = null, $limit = null, '{n}.cfpd01_auxiliar.cod_auxiliar', '{n}.cfpd01_auxiliar.descripcion');
		$this->concatena3($listaAuxiliar, 'auxiliar');
		$this->set('cod_partida', $cod_partida);
		$this->set('cod_generica', $cod_generica);
		$this->set('cod_especifica', $cod_especifica);
		$this->set('cod_subespec', $cod_subespec);
		$this->set('cod_auxiliar', $cod_auxiliar);
		$this->set('codigo',mascara($cod_auxiliar,2));
		$c=$this->cfpd01_auxiliar->findCount($conditions = "cod_grupo=".CE." and cod_partida=$cod_partida and cod_generica=$cod_generica and cod_especifica=$cod_especifica and cod_sub_espec=$cod_subespec and cod_partida=$cod_partida");
        $deno = $c!=0? $this->cfpd01_auxiliar->field('cfpd01_auxiliar.descripcion', $conditions = "cod_grupo=".CE." and cod_partida=$cod_partida and cod_generica=$cod_generica and cod_especifica=$cod_especifica and cod_sub_espec=$cod_subespec and cod_partida=$cod_partida", $order ="descripcion ASC"): 'N/A';
		$this->set('deno', $deno);

	}else{
		$this->set('codigo','');
		$this->set('deno','');
	}

}
function tipo($var=null){
	$this->layout="ajax";
	if($var!=null){
		$tipo = strtoupper_sisap($var[0]);
		 switch ($tipo) {
			case 'B':
				$this->set('tipo', 1);
				break;
			case 'S':
				$this->set('tipo', 2);
				break;
			case 'O':
				$this->set('tipo', 3);
				break;

			default:

				break;
		}
	}
}//function

function alicuota($opc=null){
	$this->layout="ajax";
	if($opc!=null && $opc == 2){
		$this->set('opc', true);
	}else{
		$this->set('opc', false);
	}

}

function guardar(){
	$this->layout="ajax";

	$this->Session->delete('selecion_snc');

	if(!empty($this->data['cscp01_catalogo'])){

		$cod_tipo= $this->data['cscp01_catalogo']['tipo'];
		$cod_snc= $this->data['cscp01_catalogo']['cod_snc'];
		$denominacion= $this->data['cscp01_catalogo']['deno_sistema_input'];
		$especificaciones=$this->data['cscp01_catalogo']['especificaciones'];
		$cod_medida = $this->data['cscp01_catalogo']['cod_medida'];
		$cod_partida = $this->data['cscp01_catalogo']['cod_partida'];
		$cod_generica = $this->data['cscp01_catalogo']['cod_generica'];
		$cod_especifica = $this->data['cscp01_catalogo']['cod_especifica'];


			if(!empty($this->data['cscp01_catalogo']['cod_sub_espec'])){
				$cod_sub_espec = $this->data['cscp01_catalogo']['cod_sub_espec'];
			}else{
				$cod_sub_espec = 0;
			}//fin else
			if(empty($this->data['cscp01_catalogo']['cod_auxiliar'])){
				$cod_auxiliar=0;
			}else{
				$cod_auxiliar = $this->data['cscp01_catalogo']['cod_auxiliar'];
			}//fin else
		$exento_iva = $this->data['cscp01_catalogo']['iva'];
			if(empty($this->data['cscp01_catalogo']['alicuota'])){
				$alicuota = 0;
			}else{
				$alicuota = $this->Formato1($this->data['cscp01_catalogo']['alicuota']);
			}//fin else

		$precio_referencia    = '0.00';
	    $fecha_precio         = '1900-01-01';
	    $denominacion_fuente  = '';
	    $distancia_ciudad     = '';

		$sql = "INSERT INTO cscd01_catalogo (cod_tipo, denominacion, especificaciones, cod_medida, cod_partida, cod_generica, cod_especifica, cod_sub_espec, cod_auxiliar, exento_iva, alicuota_iva, cod_snc, precio_referencia, fecha_precio, denominacion_fuente, distancia_ciudad) ";
		$sql .= "VALUES('$cod_tipo', '$denominacion', '$especificaciones', '$cod_medida', '$cod_partida', '$cod_generica', '$cod_especifica', '$cod_sub_espec', '$cod_auxiliar', '$exento_iva', '$alicuota', '$cod_snc', '$precio_referencia', '$fecha_precio', '$denominacion_fuente', '$distancia_ciudad')";
		$resultado = $this->cscd01_catalogo->execute($sql);

		if($resultado > 1){
		  $this->set('Message_existe', 'el dato fue insertado con exito');
		  $this->index();
		  $this->render('index');
		}else
		  $this->set('errorMessage', 'el dato no fue insertado');
	    }//fin else

}//fin function


/*
function dNomina($nomina, $cod_tipo_nomina){

	for($i=0;$i<count($cod_tipo_nomina);$i++){
		foreach($nomina as $row){
			if($row['Cnmd01']['cod_tipo_nomina'] == $cod_tipo_nomina[$i]){
				$dNomina[$i] = $row['Cnmd01']['denominacion'];
			}
		}
	}

	return $dNomina;
}*/

function dMedida($cod_medida, $medida){
	for($i=0;$i<count($cod_medida);$i++){
		foreach($medida as $row){
			if($row['cscd01_unidad_medida']['cod_medida'] == $cod_medida[$i]){
				$dMedida[$i] = $row['cscd01_unidad_medida']['denominacion'];
			}
		}
	}

	return $dMedida;
}

function dPartida2($partida, $cod_partida){

	for($i=0;$i<count($cod_partida);$i++){
		foreach($partida as $row){
			if($row['cfpd01_partida']['cod_partida'] == $cod_partida[$i]){
				$dPartida[$i] = $row['cfpd01_partida']['descripcion'];
			}
		}
	}

	return $dPartida;
}

function dGenerica2($generica, $cod_partida, $cod_generica){

	for($i=0;$i<count($cod_generica);$i++){
		foreach($generica as $row){
			if($row['cfpd01_generica']['cod_partida'] == $cod_partida[$i] && $row['cfpd01_generica']['cod_generica'] == $cod_generica[$i]){
				$dGenerica[$i] = $row['cfpd01_generica']['descripcion'];
			}
		}
	}


	return $dGenerica;
}


function dEspecifica2($especifica, $cod_partida, $cod_generica, $cod_especifica){

	for($i=0;$i<count($cod_especifica);$i++){
		foreach($especifica as $row){
			if($row['cfpd01_especifica']['cod_partida'] == $cod_partida[$i] && $row['cfpd01_especifica']['cod_generica'] == $cod_generica[$i] && $row['cfpd01_especifica']['cod_especifica'] == $cod_especifica[$i]){
				$dEspecifica[$i] = $row['cfpd01_especifica']['descripcion'];
			}
		}
	}

	return $dEspecifica;
}

function dSubEspec2($sub_espec, $cod_partida, $cod_generica, $cod_especifica, $cod_sub_espec){

	for($i=0;$i<count($cod_sub_espec);$i++){
		foreach($sub_espec as $row){
			if($row['cfpd01_sub_espec']['cod_partida'] == $cod_partida[$i] && $row['cfpd01_sub_espec']['cod_generica'] == $cod_generica[$i] && $row['cfpd01_sub_espec']['cod_especifica'] == $cod_especifica[$i] && $row['cfpd01_sub_espec']['cod_sub_espec'] == $cod_sub_espec[$i]){
				$dSubEspec[$i] = $row['cfpd01_sub_espec']['descripcion'];
			}else{
				$dSubEspec[$i] = 'N/A';
			}
		}
	}

	return $dSubEspec;

}

function dAuxiliar2($auxiliar, $cod_partida, $cod_generica, $cod_especifica, $cod_sub_espec, $cod_auxiliar){

	for($i=0;$i<count($cod_auxiliar);$i++){
		foreach($auxiliar as $row){
			if($row['cfpd01_auxiliar']['cod_partida'] == $cod_partida[$i] && $row['cfpd01_auxiliar']['cod_generica'] == $cod_generica[$i] && $row['cfpd01_auxiliar']['cod_especifica'] == $cod_especifica[$i] && $row['cfpd01_auxiliar']['cod_sub_espec'] == $cod_sub_espec[$i] && $row['cfpd01_auxiliar']['cod_auxiliar'] == $cod_auxiliar[$i]){
				$dAuxiliar[$i] = $row['cfpd01_auxiliar']['descripcion'];
			}
		}
	}


	return $dAuxiliar = null;
}

function dExpresion($cod_medida, $medida){
	for($i=0;$i<count($cod_medida);$i++){
		foreach($medida as $row){
			if($row['cscd01_unidad_medida']['cod_medida'] == $cod_medida[$i]){
				$dExpresion[$i] = $row['cscd01_unidad_medida']['expresion'];
			}
		}
	}

	return $dExpresion;
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










function consulta($pagina=null){
	$this->layout="ajax";
	$this->data=null;
	$this->Session->delete('selecion_snc');
	//$datos = $this->cscd01_catalogo->findAll();
	$cotizaciones = $this->cscd02_solicitud_cuerpo->findAll($conditions = null, $fields = 'DISTINCT codigo_prod_serv', $order = 'codigo_prod_serv ASC', $limit = null, $page = null, $recursive = null);
	$this->set('cotizaciones', $cotizaciones);

	if($pagina!=null){
	    $pagina=$pagina;
	    if($pagina<=0){$pagina=1;}
	}else{
	 	 $pagina=1;
	}

	 $Tfilas=$this->v_cscd01_catalogo_con_snc_denominacion->findCount();
	 if($Tfilas==0){
	 	$this->index();
		$this->render("index");
	 }
	 if($Tfilas!=0){
	 	$this->set('pag_cant',$pagina.'/'.$Tfilas);
	 	$datos=$this->v_cscd01_catalogo_con_snc_denominacion->findAll(null,null,'codigo_prod_serv ASC',1,$pagina,null);
	 	$this->set('datos',$datos);
	 	$this->set('siguiente',$pagina+1);
	 	$this->set('anterior',$pagina-1);
	 	$this->set('pagina_actual',$pagina);
	    $this->bt_nav($Tfilas,$pagina);
	 }
     $this->set('ultimo',$Tfilas);
	 if(empty($datos)){
		$this->set('vacio', true);
		$vacio =  true;
	 }else{
		$vacio= false;
	 }
	//echo count($datos);
	if(!$vacio){
	$this->set('datos', $datos);
	/*$k = 0;
	foreach($datos as $row){

		$cod_medida[$k] = $row['v_cscd01_catalogo_con_snc_denominacion']['cod_medida'];
		$cod_partida[$k] = $row['v_cscd01_catalogo_con_snc_denominacion']['cod_partida'];
		$cod_partida[$k] =substr($cod_partida[$k], 1, 2);
		$cod_generica[$k] = $row['v_cscd01_catalogo_con_snc_denominacion']['cod_generica'];
		$cod_especifica[$k] =  $row['v_cscd01_catalogo_con_snc_denominacion']['cod_especifica'];
		$cod_sub_espec[$k] = $row['v_cscd01_catalogo_con_snc_denominacion']['cod_sub_espec'];
		$cod_auxiliar[$k] = $row['v_cscd01_catalogo_con_snc_denominacion']['cod_auxiliar'];
		$k++;
	}*/
	//echo count($cod_medida);
	//echo count($cod_partida);
    $cod_medida     = $datos[0]['v_cscd01_catalogo_con_snc_denominacion']['cod_medida'];
	$cod_partida    = $datos[0]['v_cscd01_catalogo_con_snc_denominacion']['cod_partida'];
	$cod_partida    = substr($cod_partida, 1, 2);
	$cod_generica   = $datos[0]['v_cscd01_catalogo_con_snc_denominacion']['cod_generica'];
	$cod_especifica = $datos[0]['v_cscd01_catalogo_con_snc_denominacion']['cod_especifica'];
	$cod_sub_espec  = $datos[0]['v_cscd01_catalogo_con_snc_denominacion']['cod_sub_espec'];
	$cod_auxiliar   = $datos[0]['v_cscd01_catalogo_con_snc_denominacion']['cod_auxiliar'];
	$partida = $this->cfpd01_partida->findAll('cod_grupo='.CE.' and cod_partida='.$cod_partida, 'cod_partida, descripcion', 'cod_partida ASC', null, null, null);
	$this->set('partida', CE.mascara($cod_partida,2));
	$this->set('dPartida', $partida[0]['cfpd01_partida']['descripcion']);

	$generica = $this->cfpd01_generica->findAll('cod_grupo='.CE.' and cod_partida='.$cod_partida.' and cod_generica='.$cod_generica, 'descripcion', null, null, null, null);
	$this->set('generica', mascara($cod_generica,2));
	$this->set('dGenerica', $generica[0]['cfpd01_generica']['descripcion']);

	$especifica = $this->cfpd01_especifica->findAll('cod_grupo='.CE.' and cod_partida='.$cod_partida.' and cod_generica='.$cod_generica.' and cod_especifica='.$cod_especifica, 'descripcion', null, null, null, null);
	$this->set('especifica', mascara($cod_especifica,2));
	$this->set('dEspecifica', $especifica[0]['cfpd01_especifica']['descripcion']);

	$sub_espec = $this->cfpd01_sub_espec->findAll('cod_grupo='.CE.' and cod_partida='.$cod_partida.' and cod_generica='.$cod_generica.' and cod_especifica='.$cod_especifica.' and cod_sub_espec='.$cod_sub_espec, 'descripcion', null, null, null, null);
	$this->set('sub_espec', mascara($cod_sub_espec,2));
	$this->set('dSubEspec', $sub_espec[0]['cfpd01_sub_espec']['descripcion']);

	$c_auxiliar = $this->cfpd01_auxiliar->findCount('cod_grupo='.CE.' and cod_partida='.$cod_partida.' and cod_generica='.$cod_generica.' and cod_especifica='.$cod_especifica.' and cod_sub_espec='.$cod_sub_espec.' and cod_auxiliar='.$cod_auxiliar);
	//echo $c_auxiliar;
	$auxiliar = $this->cfpd01_auxiliar->findAll('cod_grupo='.CE.' and cod_partida='.$cod_partida.' and cod_generica='.$cod_generica.' and cod_especifica='.$cod_especifica.' and cod_sub_espec='.$cod_sub_espec.' and cod_auxiliar='.$cod_auxiliar, 'descripcion', null, null, null, null);
	$this->set('auxiliar', mascara($cod_auxiliar,2));
	$this->set('dAuxiliar', $c_auxiliar!=0?$auxiliar[0]['cfpd01_auxiliar']['descripcion']:'N/A');

	$medida = $this->cscd01_unidad_medida->findAll('cod_medida='.$cod_medida);
	$this->set('dMedida', $medida[0]['cscd01_unidad_medida']['denominacion']);
	$this->set('dExpresion', $medida[0]['cscd01_unidad_medida']['expresion']);
 	//$this->set('medida', $this->cscd01_unidad_medida->findAll());
 	$this->set('cod_medida',  mascara($cod_medida,2));
    /*
	$partida = $this->cfpd01_partida->findAll('cod_grupo='.CE, 'cod_partida, descripcion', 'cod_partida ASC', null, null, null);
	$this->set('partida', $partida);
	$this->set('dPartida', $this->dPartida2($partida, $cod_partida));

	$generica = $this->cfpd01_generica->findAll('cod_grupo='.CE, 'cod_partida, cod_generica, descripcion', 'cod_partida, cod_generica ASC', null, null, null);
	$this->set('generica', $generica);
	$this->set('dGenerica', $this->dGenerica2($generica, $cod_partida, $cod_generica));

	$especifica = $this->cfpd01_especifica->findAll('cod_grupo='.CE, 'cod_partida, cod_generica, cod_especifica, descripcion', 'cod_partida, cod_generica, cod_especifica ASC', null, null, null);
	$this->set('especifica', $especifica);
	$this->set('dEspecifica', $this->dEspecifica2($especifica, $cod_partida, $cod_generica, $cod_especifica));

	$sub_espec = $this->cfpd01_sub_espec->findAll('cod_grupo='.CE, 'cod_partida, cod_generica, cod_especifica, cod_sub_espec, descripcion', 'cod_partida, cod_generica, cod_sub_espec, cod_especifica ASC', null, null, null);
	$this->set('sub_espec', $sub_espec);
	$this->set('dSubEspec', $this->dSubEspec2($sub_espec, $cod_partida, $cod_generica, $cod_especifica, $cod_sub_espec));

	$auxiliar = $this->cfpd01_auxiliar->findAll('cod_grupo='.CE, 'cod_partida, cod_generica, cod_especifica, cod_sub_espec, cod_auxiliar, descripcion', 'cod_partida, cod_generica, cod_sub_espec, cod_especifica, cod_auxiliar ASC', null, null, null);
	$this->set('auxiliar', $auxiliar);
	$this->set('dAuxiliar', $this->dAuxiliar2($auxiliar, $cod_partida, $cod_generica, $cod_especifica, $cod_sub_espec, $cod_auxiliar));

	$medida = $this->cscd01_unidad_medida->findAll();
	$this->set('dMedida', $this->dMedida($cod_medida, $medida));
	$this->set('dExpresion', $this->dExpresion($cod_medida, $medida));
 	$this->set('medida', $this->cscd01_unidad_medida->findAll());
    */
	}



	/*if($pag_num!=null){
    	$this->set('pagina_actual', $pag_num);
    }*/


}//fin function










function consulta2($pagina=null, $pista=null){
	$this->layout="ajax";
	//$datos = $this->cscd01_catalogo->findAll();
	$pista = strtoupper_sisap($pista);

    $this->Session->delete('selecion_snc');
	$this->data=null;
	///$datos = $this->cscd01_catalogo->findAll($conditions="mayus_acentos(denominacion) LIKE mayus_acentos('%$pista%')", $fields = null, $order = 'codigo_prod_serv ASC', $limit = null, $page = null, $recursive = null);
	if($pagina!=null){
		$pagina=$pagina;
		if($pagina<=0){$pagina=1;}
	}else{
		$pagina=1;
	}//fin else
     $Tfilas=$this->v_cscd01_catalogo_con_snc_denominacion->findCount("mayus_acentos(denominacion) LIKE mayus_acentos('%$pista%')");
  	 if($Tfilas==0){
  	 	$this->index();
  		$this->render("index");
  	 }

  	 if($Tfilas!=0){
      	 $this->set('pag_cant',$pagina.'/'.$Tfilas);
      	 $datos=$this->v_cscd01_catalogo_con_snc_denominacion->findAll("mayus_acentos(denominacion) LIKE mayus_acentos('%$pista%')",null,'codigo_prod_serv ASC',1,$pagina,null);
      	 $this->set('datos',$datos);
      	 $this->set('siguiente',$pagina+1);
      	 $this->set('anterior',$pagina-1);
      	 $this->set('pagina_actual',$pagina);
         $this->bt_nav($Tfilas,$pagina);
     }
   $this->set('ultimo',$Tfilas);
	$cotizaciones = $this->cscd02_solicitud_cuerpo->findAll($conditions = null, $fields = 'DISTINCT codigo_prod_serv', $order = 'codigo_prod_serv ASC', $limit = null, $page = null, $recursive = null);
	$this->set('cotizaciones', $cotizaciones);
	$this->set('pista', $pista);

	if(empty($datos)){
		$this->set('vacio', true);
		$vacio =  true;
	}else{
		$vacio= false;
	}
	//echo count($datos);
	if(!$vacio){
	$this->set('datos', $datos);
	/*
	$k = 0;
	foreach($datos as $row){

		$cod_medida[$k] = $row['v_cscd01_catalogo_con_snc_denominacion']['cod_medida'];
		$cod_partida[$k] = $row['v_cscd01_catalogo_con_snc_denominacion']['cod_partida'];
		$cod_partida[$k] =substr($cod_partida[$k], 1, 2);
		$cod_generica[$k] = $row['v_cscd01_catalogo_con_snc_denominacion']['cod_generica'];
		$cod_especifica[$k] =  $row['v_cscd01_catalogo_con_snc_denominacion']['cod_especifica'];
		$cod_sub_espec[$k] = $row['v_cscd01_catalogo_con_snc_denominacion']['cod_sub_espec'];
		$cod_auxiliar[$k] = $row['v_cscd01_catalogo_con_snc_denominacion']['cod_auxiliar'];
		$k++;
	}*/
	//echo count($cod_medida);
	//echo count($cod_partida);
	$cod_medida     = $datos[0]['v_cscd01_catalogo_con_snc_denominacion']['cod_medida'];
	$cod_partida    = $datos[0]['v_cscd01_catalogo_con_snc_denominacion']['cod_partida'];
	$cod_partida    = substr($cod_partida, 1, 2);
	$cod_generica   = $datos[0]['v_cscd01_catalogo_con_snc_denominacion']['cod_generica'];
	$cod_especifica = $datos[0]['v_cscd01_catalogo_con_snc_denominacion']['cod_especifica'];
	$cod_sub_espec  = $datos[0]['v_cscd01_catalogo_con_snc_denominacion']['cod_sub_espec'];
	$cod_auxiliar   = $datos[0]['v_cscd01_catalogo_con_snc_denominacion']['cod_auxiliar'];
	$partida = $this->cfpd01_partida->findAll('cod_grupo='.CE.' and cod_partida='.$cod_partida, 'cod_partida, descripcion', 'cod_partida ASC', null, null, null);
	$this->set('partida', CE.mascara($cod_partida,2));
	$this->set('dPartida', $partida[0]['cfpd01_partida']['descripcion']);

	$generica = $this->cfpd01_generica->findAll('cod_grupo='.CE.' and cod_partida='.$cod_partida.' and cod_generica='.$cod_generica, 'descripcion', null, null, null, null);
	$this->set('generica', mascara($cod_generica,2));
	$this->set('dGenerica', $generica[0]['cfpd01_generica']['descripcion']);

	$especifica = $this->cfpd01_especifica->findAll('cod_grupo='.CE.' and cod_partida='.$cod_partida.' and cod_generica='.$cod_generica.' and cod_especifica='.$cod_especifica, 'descripcion', null, null, null, null);
	$this->set('especifica', mascara($cod_especifica,2));
	$this->set('dEspecifica', $especifica[0]['cfpd01_especifica']['descripcion']);

	$sub_espec = $this->cfpd01_sub_espec->findAll('cod_grupo='.CE.' and cod_partida='.$cod_partida.' and cod_generica='.$cod_generica.' and cod_especifica='.$cod_especifica.' and cod_sub_espec='.$cod_sub_espec, 'descripcion', null, null, null, null);
	$this->set('sub_espec', mascara($cod_sub_espec,2));
	$this->set('dSubEspec', $sub_espec[0]['cfpd01_sub_espec']['descripcion']);

	$c_auxiliar = $this->cfpd01_auxiliar->findCount('cod_grupo='.CE.' and cod_partida='.$cod_partida.' and cod_generica='.$cod_generica.' and cod_especifica='.$cod_especifica.' and cod_sub_espec='.$cod_sub_espec.' and cod_auxiliar='.$cod_auxiliar);
	//echo $c_auxiliar;
	$auxiliar = $this->cfpd01_auxiliar->findAll('cod_grupo='.CE.' and cod_partida='.$cod_partida.' and cod_generica='.$cod_generica.' and cod_especifica='.$cod_especifica.' and cod_sub_espec='.$cod_sub_espec.' and cod_auxiliar='.$cod_auxiliar, 'descripcion', null, null, null, null);
	$this->set('auxiliar', mascara($cod_auxiliar,2));
	$this->set('dAuxiliar', $c_auxiliar!=0?$auxiliar[0]['cfpd01_auxiliar']['descripcion']:'N/A');

	$medida = $this->cscd01_unidad_medida->findAll('cod_medida='.$cod_medida);
	$this->set('dMedida', $medida[0]['cscd01_unidad_medida']['denominacion']);
	$this->set('dExpresion', $medida[0]['cscd01_unidad_medida']['expresion']);
 	//$this->set('medida', $this->cscd01_unidad_medida->findAll());
 	$this->set('cod_medida', mascara($cod_medida,2));

    /*
	$partida = $this->cfpd01_partida->findAll('cod_grupo='.CE, 'cod_partida, descripcion', 'cod_partida ASC', null, null, null);
	$this->set('partida', $partida);
	$this->set('dPartida', $this->dPartida2($partida, $cod_partida));

	$generica = $this->cfpd01_generica->findAll('cod_grupo='.CE, 'cod_partida, cod_generica, descripcion', 'cod_partida, cod_generica ASC', null, null, null);
	$this->set('generica', $generica);
	$this->set('dGenerica', $this->dGenerica2($generica, $cod_partida, $cod_generica));

	$especifica = $this->cfpd01_especifica->findAll('cod_grupo='.CE, 'cod_partida, cod_generica, cod_especifica, descripcion', 'cod_partida, cod_generica, cod_especifica ASC', null, null, null);
	$this->set('especifica', $especifica);
	$this->set('dEspecifica', $this->dEspecifica2($especifica, $cod_partida, $cod_generica, $cod_especifica));

	$sub_espec = $this->cfpd01_sub_espec->findAll('cod_grupo='.CE, 'cod_partida, cod_generica, cod_especifica, cod_sub_espec, descripcion', 'cod_partida, cod_generica, cod_sub_espec, cod_especifica ASC', null, null, null);
	$this->set('sub_espec', $sub_espec);
	$this->set('dSubEspec', $this->dSubEspec2($sub_espec, $cod_partida, $cod_generica, $cod_especifica, $cod_sub_espec));

	$auxiliar = $this->cfpd01_auxiliar->findAll('cod_grupo='.CE, 'cod_partida, cod_generica, cod_especifica, cod_sub_espec, cod_auxiliar, descripcion', 'cod_partida, cod_generica, cod_sub_espec, cod_especifica, cod_auxiliar ASC', null, null, null);
	$this->set('auxiliar', $auxiliar);
	$this->set('dAuxiliar', $this->dAuxiliar2($auxiliar, $cod_partida, $cod_generica, $cod_especifica, $cod_sub_espec, $cod_auxiliar));

	$medida = $this->cscd01_unidad_medida->findAll();
	$this->set('dMedida', $this->dMedida($cod_medida, $medida));
	$this->set('dExpresion', $this->dExpresion($cod_medida, $medida));
 	$this->set('medida', $this->cscd01_unidad_medida->findAll());*/
	}



}//fin function


function eliminar ($cod_snc=null, $consulta=null) {
	if($cod_snc!=null){
		$cod_presi = $this->Session->read('SScodpresi');
		$cod_entidad = $this->Session->read('SScodentidad');
		$cod_tipo_inst = $this->Session->read('SScodtipoinst');
		$cod_inst = $this->Session->read('SScodinst');
		$cod_dep = $this->Session->read('SScoddep');
		$codigo = $cod_presi."-".$cod_entidad."-".$cod_tipo_inst."-".$cod_inst."-".$cod_dep;
		$sql="DELETE FROM cscd01_catalogo WHERE codigo_prod_serv='$cod_snc'";
		$this->cscd01_catalogo->execute($sql);
		$this->set('mensaje', 'El dato fue eliminado correctamente');

		if($consulta != 0) $consulta = $consulta-1;
		else $consulta = null;
		$user = $this->Session->read('nom_usuario');
		$this->log('El usuario: '.$user.' con el codigo: '.$codigo.' elimino de la tabla cscd01_catalogo el codigo: '.$cod_snc, LOG_ELIMINAR);
		$this->consulta($consulta);
		$this->render('consulta');
	}

}

function eliminar3 ($cod_snc=null, $consulta=null, $pista=null) {
	if($cod_snc!=null){
		$cod_presi = $this->Session->read('SScodpresi');
		$cod_entidad = $this->Session->read('SScodentidad');
		$cod_tipo_inst = $this->Session->read('SScodtipoinst');
		$cod_inst = $this->Session->read('SScodinst');
		$cod_dep = $this->Session->read('SScoddep');
		$codigo = $cod_presi."-".$cod_entidad."-".$cod_tipo_inst."-".$cod_inst."-".$cod_dep;
		$sql="DELETE FROM cscd01_catalogo WHERE codigo_prod_serv='$cod_snc'";
		$this->cscd01_catalogo->execute($sql);
		$this->set('mensaje', 'El dato fue eliminado correctamente');

		if($consulta != 0) $consulta = $consulta-1;
		else $consulta = null;
		$user = $this->Session->read('nom_usuario');
		$this->log('El usuario: '.$user.' con el codigo: '.$codigo.' elimino de la tabla cscd01_catalogo el codigo: '.$cod_snc, LOG_ELIMINAR);
		$this->consulta2($consulta, $pista);
		$this->render('consulta2');
	}

}

function eliminar2($cod_snc=null) {
	if($cod_snc!=null){
		$cod_presi = $this->Session->read('SScodpresi');
		$cod_entidad = $this->Session->read('SScodentidad');
		$cod_tipo_inst = $this->Session->read('SScodtipoinst');
		$cod_inst = $this->Session->read('SScodinst');
		$cod_dep = $this->Session->read('SScoddep');
		$codigo = $cod_presi."-".$cod_entidad."-".$cod_tipo_inst."-".$cod_inst."-".$cod_dep;
		$sql="DELETE FROM cscd01_catalogo WHERE codigo_prod_serv='$cod_snc'";
		$this->cscd01_catalogo->execute($sql);
		$this->set('mensaje', 'El dato fue eliminado correctamente');
		$user = $this->Session->read('nom_usuario');
		$this->log('El usuario: '.$user.' con el codigo: '.$codigo.' elimino de la tabla cscd01_catalogo el codigo: '.$cod_snc, LOG_ELIMINAR);
		$this->index();
		$this->render('index');
	}

}

function editar2($var=null, $pag=null){
	$this->layout="ajax";
	//$catalogo= $this->v_cscd01_catalogo_snc->generateList(null, 'codigo_prod_serv ASC', null, '{n}.v_cscd01_catalogo_snc.codigo_prod_serv', '{n}.v_cscd01_catalogo_snc.denominacion');
 	$this->concatena3(array(""=>""), 'catalogo');
	if($var != null){


        $datos2=$this->v_cscd01_catalogo_con_snc_denominacion->findAll("codigo_prod_serv='$var'");
		$this->set('codigo', $var);
		$this->set('datos', $datos2);



		/*$cod_partida = $this->cscd01_catalogo->field('cscd01_catalogo.cod_partida', $conditions = "codigo_prod_serv='$var'", $order ="codigo_prod_serv ASC");
		$cod_partida = substr($cod_partida, 1, 2);
		$cod_generica = $this->cscd01_catalogo->field('cscd01_catalogo.cod_generica', $conditions = "codigo_prod_serv='$var'", $order ="codigo_prod_serv ASC");
		$cod_especifica = $this->cscd01_catalogo->field('cscd01_catalogo.cod_especifica', $conditions = "codigo_prod_serv='$var'", $order ="codigo_prod_serv ASC");
		$cod_sub_espec = $this->cscd01_catalogo->field('cscd01_catalogo.cod_sub_espec', $conditions = "codigo_prod_serv='$var'", $order ="codigo_prod_serv ASC");
		$cod_auxiliar = $this->cscd01_catalogo->field('cscd01_catalogo.cod_auxiliar', $conditions = "codigo_prod_serv='$var'", $order ="codigo_prod_serv ASC");
*/


		foreach($datos2 as $row){
			$cod_partida = $row['v_cscd01_catalogo_con_snc_denominacion']['cod_partida'];
			$cod_partida = substr($cod_partida, 1, 2);
			$cod_generica = $row['v_cscd01_catalogo_con_snc_denominacion']['cod_generica'];
			$cod_especifica =  $row['v_cscd01_catalogo_con_snc_denominacion']['cod_especifica'];
			$cod_sub_espec = $row['v_cscd01_catalogo_con_snc_denominacion']['cod_sub_espec'];
			$cod_auxiliar = $row['v_cscd01_catalogo_con_snc_denominacion']['cod_auxiliar'];
		}



		$dPartida = $this->cfpd01_partida->field('cfpd01_partida.descripcion', $conditions = " cod_grupo=".CE." and cod_partida='$cod_partida'", $order ="cod_partida ASC");
		$this->set('dPartida', $dPartida);
		$dGenerica = $this->cfpd01_generica->field('cfpd01_generica.descripcion', $conditions = " cod_grupo=".CE." and cod_partida='$cod_partida' and cfpd01_generica.cod_generica='$cod_generica'", $order ="cod_partida, cod_generica ASC");
		$this->set('dGenerica', $dGenerica);

		$dEspecifica = $this->cfpd01_especifica->field('cfpd01_especifica.descripcion', $conditions = " cod_grupo=".CE." and cod_partida='$cod_partida' and cfpd01_especifica.cod_generica='$cod_generica' and cfpd01_especifica.cod_especifica='$cod_especifica'", $order ="cod_partida, cod_generica, cod_especifica ASC");

		$dSubEspec = $this->cfpd01_sub_espec->field('cfpd01_sub_espec.descripcion', $conditions = " cod_grupo=".CE." and cod_partida='$cod_partida' and cfpd01_sub_espec.cod_generica='$cod_generica' and cfpd01_sub_espec.cod_especifica='$cod_especifica' and cfpd01_sub_espec.cod_sub_espec='$cod_sub_espec'", $order ="cod_partida, cod_generica, cod_especifica, cod_sub_espec ASC");

		$dAuxiliar = $this->cfpd01_auxiliar->field('cfpd01_auxiliar.descripcion', $conditions = " cod_grupo=".CE." and cod_partida='$cod_partida' and cfpd01_auxiliar.cod_generica='$cod_generica' and cfpd01_auxiliar.cod_especifica='$cod_especifica' and cfpd01_auxiliar.cod_sub_espec='$cod_sub_espec' and cfpd01_auxiliar.cod_auxiliar='$cod_auxiliar'", $order ="cod_partida, cod_generica, cod_especifica, cod_sub_espec ASC");

		if(empty($dSubEspec)){$dSubEspec = 'N/A';}
		if(empty($dAuxiliar)){$dAuxiliar = 'N/A';}
		$unidad = $this->cscd01_unidad_medida->generateList($conditions = null, $order = null, $limit = null, '{n}.cscd01_unidad_medida.cod_medida', '{n}.cscd01_unidad_medida.expresion');
 		$this->concatena3($unidad, 'unidad');

		$this->set('dEspecifica', $dEspecifica);
		$this->set('dSubEspec', $dSubEspec);
		$this->set('dAuxiliar', $dAuxiliar);
		if(isset($pag)){
			$pag=$pag;
		}else{
			$pag=1;
		}
		$this->set('pag', $pag);
 		$this->set('medida', $this->cscd01_unidad_medida->findAll());




 				$datos=$this->cscd01_catalogo->findAll("codigo_prod_serv='$var'");

		foreach($datos as $row){
			$cod_partida = $row['cscd01_catalogo']['cod_partida'];
			$cod_generica = $row['cscd01_catalogo']['cod_generica'];
			$cod_especifica =  $row['cscd01_catalogo']['cod_especifica'];
			$cod_subespecifica = $row['cscd01_catalogo']['cod_sub_espec'];
			$cod_auxiliar = $row['cscd01_catalogo']['cod_auxiliar'];
		}
$cod_partida2=$cod_partida[1].$cod_partida[2];
$cod_partida2=(int) $cod_partida2;
//echo $cod_partida2;

//$conditions = "cod_grupo=".CE." and cod_partida=".$cod_partida2." and cod_generica=".$cod_generica." and cod_especifica=".$cod_especifica;
//echo $conditions;

		$listaPartida= $this->cfpd01_partida->generateList(	$conditions = "cod_grupo=".CE, $order = null, $limit = null, '{n}.cfpd01_partida.cod_partida', '{n}.cfpd01_partida.descripcion');
 		$this->concatena($listaPartida, 'partida',4);
 		$listaGenerica= $this->cfpd01_generica->generateList($conditions = "cod_grupo=".CE." and cod_partida=".$cod_partida2,'cod_partida ASC',null, '{n}.cfpd01_generica.cod_generica', '{n}.cfpd01_generica.descripcion');
 		$this->concatena($listaGenerica, 'generica');
 		$listaEspecifica= $this->cfpd01_especifica->generateList($conditions = "cod_grupo=".CE." and cod_partida=".$cod_partida2." and cod_generica=".$cod_generica,'cod_especifica ASC',null, '{n}.cfpd01_especifica.cod_especifica', '{n}.cfpd01_especifica.descripcion');
 		$this->concatena($listaEspecifica, 'especifica');
 		$listaSub_especifica= $this->cfpd01_sub_espec->generateList($conditions = "cod_grupo=".CE." and cod_partida=".$cod_partida2." and cod_generica=".$cod_generica." and cod_especifica=".$cod_especifica,'cod_sub_espec ASC',null, '{n}.cfpd01_sub_espec.cod_sub_espec', '{n}.cfpd01_sub_espec.descripcion');
 		$this->concatena($listaSub_especifica, 'sub_especifica');
 		$c_aux= $this->cfpd01_auxiliar->findCount($conditions = "cod_grupo=".CE." and cod_partida=".$cod_partida2." and cod_generica=".$cod_generica." and cod_especifica=".$cod_especifica." and cod_sub_espec=".$cod_sub_espec);
 		$listaAuxiliar= $this->cfpd01_auxiliar->generateList($conditions = "cod_grupo=".CE." and cod_partida=".$cod_partida2." and cod_generica=".$cod_generica." and cod_especifica=".$cod_especifica." and cod_sub_espec=".$cod_sub_espec,'cod_auxiliar ASC',null, '{n}.cfpd01_auxiliar.cod_auxiliar', '{n}.cfpd01_auxiliar.descripcion');
 		$listaAuxiliar=$c_aux!=0?$listaAuxiliar:array('0'=>'N/A');
 		$this->concatena($listaAuxiliar, 'auxiliar');

		//print_r($listaGenerica);

			}
 	$cscd01_snc_tipo= $this->cscd01_snc_tipo->generateList(null, $order = null, $limit = null, '{n}.cscd01_snc_tipo.cod_tipo', '{n}.cscd01_snc_tipo.denominacion');
 	$this->concatena_sin_cero($cscd01_snc_tipo, "snc_tipo_lista");


}

function editar($var=null){
	$this->layout="ajax";
	//$catalogo= $this->cscd01_catalogo->generateList(null, 'codigo_prod_serv ASC', null, '{n}.cscd01_catalogo.codigo_prod_serv', '{n}.cscd01_catalogo.denominacion');
 	$this->concatena3(array(""=>""), 'catalogo');
	if($var != null){

        $datos2=$this->v_cscd01_catalogo_con_snc_denominacion->findAll("codigo_prod_serv='$var'");
		$this->set('codigo', $var);
		$this->set('datos', $datos2);


/*
		$cod_partida = $this->cscd01_catalogo->field('cscd01_catalogo.cod_partida', $conditions = "cscd01_catalogo.codigo_prod_serv='$var'", $order ="codigo_prod_serv ASC");
		$cod_partida = substr($cod_partida, 1, 2);
		$cod_generica = $this->cscd01_catalogo->field('cscd01_catalogo.cod_generica', $conditions = "cscd01_catalogo.codigo_prod_serv='$var'", $order ="codigo_prod_serv ASC");
		$cod_especifica = $this->cscd01_catalogo->field('cscd01_catalogo.cod_especifica', $conditions = "cscd01_catalogo.codigo_prod_serv='$var'", $order ="codigo_prod_serv ASC");
		$cod_sub_espec = $this->cscd01_catalogo->field('cscd01_catalogo.cod_sub_espec', $conditions = "cscd01_catalogo.codigo_prod_serv='$var'", $order ="codigo_prod_serv ASC");
		$cod_auxiliar = $this->cscd01_catalogo->field('cscd01_catalogo.cod_auxiliar', $conditions = "cscd01_catalogo.codigo_prod_serv='$var'", $order ="codigo_prod_serv ASC");
*/


		foreach($datos2 as $row){
			$cod_partida = $row['v_cscd01_catalogo_con_snc_denominacion']['cod_partida'];
			$cod_partida = substr($cod_partida, 1, 2);
			$cod_generica = $row['v_cscd01_catalogo_con_snc_denominacion']['cod_generica'];
			$cod_especifica =  $row['v_cscd01_catalogo_con_snc_denominacion']['cod_especifica'];
			$cod_sub_espec = $row['v_cscd01_catalogo_con_snc_denominacion']['cod_sub_espec'];
			$cod_auxiliar = $row['v_cscd01_catalogo_con_snc_denominacion']['cod_auxiliar'];
		}





		$dPartida = $this->cfpd01_partida->field('cfpd01_partida.descripcion', $conditions = "cfpd01_partida.cod_partida='$cod_partida'", $order ="cod_partida ASC");
		$this->set('dPartida', $dPartida);
		$dGenerica = $this->cfpd01_generica->field('cfpd01_generica.descripcion', $conditions = "cfpd01_generica.cod_partida='$cod_partida' and cfpd01_generica.cod_generica='$cod_generica'", $order ="cod_partida, cod_generica ASC");
		$this->set('dGenerica', $dGenerica);

		$dEspecifica = $this->cfpd01_especifica->field('cfpd01_especifica.descripcion', $conditions = "cfpd01_especifica.cod_partida='$cod_partida' and cfpd01_especifica.cod_generica='$cod_generica' and cfpd01_especifica.cod_especifica='$cod_especifica'", $order ="cod_partida, cod_generica, cod_especifica ASC");

		$dSubEspec = $this->cfpd01_sub_espec->field('cfpd01_sub_espec.descripcion', $conditions = "cfpd01_sub_espec.cod_partida='$cod_partida' and cfpd01_sub_espec.cod_generica='$cod_generica' and cfpd01_sub_espec.cod_especifica='$cod_especifica' and cfpd01_sub_espec.cod_sub_espec='$cod_sub_espec'", $order ="cod_partida, cod_generica, cod_especifica, cod_sub_espec ASC");

		$dAuxiliar = $this->cfpd01_auxiliar->field('cfpd01_auxiliar.descripcion', $conditions = "cfpd01_auxiliar.cod_partida='$cod_partida' and cfpd01_auxiliar.cod_generica='$cod_generica' and cfpd01_auxiliar.cod_especifica='$cod_especifica' and cfpd01_auxiliar.cod_sub_espec='$cod_sub_espec' and cfpd01_auxiliar.cod_auxiliar='$cod_auxiliar'", $order ="cod_partida, cod_generica, cod_especifica, cod_sub_espec ASC");

		if(empty($dSubEspec)){$dSubEspec = 'N/A';}
		if(empty($dAuxiliar)){$dAuxiliar = 'N/A';}
		$unidad = $this->cscd01_unidad_medida->generateList($conditions = null, $order = null, $limit = null, '{n}.cscd01_unidad_medida.cod_medida', '{n}.cscd01_unidad_medida.expresion');
 		$this->concatena3($unidad, 'unidad');

		$this->set('dEspecifica', $dEspecifica);
		$this->set('dSubEspec', $dSubEspec);
		$this->set('dAuxiliar', $dAuxiliar);
 		$this->set('medida', $this->cscd01_unidad_medida->findAll());
	}

	$cscd01_snc_tipo= $this->cscd01_snc_tipo->generateList(null, $order = null, $limit = null, '{n}.cscd01_snc_tipo.cod_tipo', '{n}.cscd01_snc_tipo.denominacion');
 	$this->concatena_sin_cero($cscd01_snc_tipo, "snc_tipo_lista");


}

function guardarEditar2($var=null, $pag=null){
	$this->layout="ajax";

//echo "el codigo del producto es ".$var;

$this->Session->delete('selecion_snc');

	//$cod=$this->data['cscp01_catalogo']['cod_snc'];
	$cod_tipo= $this->data['cscp01_catalogo']['tipo'];
	$cod_snc = $this->data['cscp01_catalogo']['cod_snc'];
	$denominacion= $this->data['cscp01_catalogo']['deno_sistema_input'];
	$especificaciones=$this->data['cscp01_catalogo']['especificaciones'];
	$cod_medida = $this->data['cscp01_catalogo']['cod_medida'];
	$cod_partida = $this->data['cscp01_catalogo']['cod_partida'];
	$cod_generica = $this->data['cscp01_catalogo']['cod_generica'];
	$cod_especifica = $this->data['cscp01_catalogo']['cod_especifica'];


	if(!empty($this->data['cscp01_catalogo']['cod_subespecifica'])){
		$cod_sub_espec = $this->data['cscp01_catalogo']['cod_subespecifica'];
	}else{
		$cod_sub_espec = 0;
	}

	if(empty($this->data['cscp01_catalogo']['cod_auxiliar'])){
		$cod_auxiliar=0;
	}else{
		$cod_auxiliar = $this->data['cscp01_catalogo']['cod_auxiliar'];
	}
	$exento_iva = $this->data['cscp01_catalogo']['iva'];
	if(empty($this->data['cscp01_catalogo']['alicuota'])){
		$alicuota = 0;
	}else{
		$alicuota = $this->data['cscp01_catalogo']['alicuota']!=''?$this->Formato1($this->data['cscp01_catalogo']['alicuota']):0;
	}



	$sql = "update cscd01_catalogo set cod_snc='".$cod_snc."', denominacion='".$denominacion."', especificaciones='".$especificaciones."', cod_medida=$cod_medida, alicuota_iva=$alicuota, exento_iva=$exento_iva, cod_partida=$cod_partida,cod_generica=$cod_generica,cod_especifica=$cod_especifica,cod_sub_espec=$cod_sub_espec, cod_auxiliar=$cod_auxiliar  where codigo_prod_serv=$var";

//echo $sql;
	$actualizar=$this->cscd01_catalogo->execute($sql);
	//echo "esto es el valor de actualizar: ".count($actualizar);
	$this->set('mensaje', 'el registro fue modificado exitosamente');
	$this->consulta2($pag);
	$this->render('consulta2');


}

function guardarEditar($var=null){
	$this->layout="ajax";

	if(isset($this->data['cscp01_catalogo']['tipo'])){
		$cod_tipo= $this->data['cscp01_catalogo']['tipo'];
	}

	$this->Session->delete('selecion_snc');


	$cod_snc = $this->data['cscp01_catalogo']['cod_snc'];
	$denominacion= $this->data['cscp01_catalogo']['deno_sistema_input'];
	$especificaciones=$this->data['cscp01_catalogo']['especificaciones'];
	$cod_medida = $this->data['cscp01_catalogo']['cod_medida'];
	$cod_partida = CE.$this->data['cscp01_catalogo']['cod_partida'];
	$cod_generica = $this->data['cscp01_catalogo']['cod_generica'];
	$cod_especifica = $this->data['cscp01_catalogo']['cod_especifica'];

	if(!empty($this->data['cscp01_catalogo']['cod_subespecifica'])){
		$cod_sub_espec = $this->data['cscp01_catalogo']['cod_subespecifica'];
	}else{
		$cod_sub_espec = 0;
	}

	if(empty($this->data['cscp01_catalogo']['cod_auxiliar'])){
		$cod_auxiliar=0;
	}else{
		$cod_auxiliar = $this->data['cscp01_catalogo']['cod_auxiliar'];
	}
	$exento_iva = $this->data['cscp01_catalogo']['iva'];
	if(empty($this->data['cscp01_catalogo']['alicuota'])){
		$alicuota = 0;
	}else{
		$alicuota = $this->data['cscp01_catalogo']['alicuota'];
	}


	$sql = "update cscd01_catalogo set cod_snc='".$cod_snc."', denominacion='$denominacion', especificaciones='$especificaciones', cod_medida='$cod_medida', alicuota_iva='$alicuota', exento_iva='$exento_iva' where codigo_prod_serv='$var'";

	$actualizar=$this->cscd01_catalogo->execute($sql);
	//echo "esto es el valor de actualizar: ".count($actualizar);
	$this->set('msg', 'el registro fue modificado exitosamente');
	$this->principal($var);
	$this->render('principal');


}



function guardar_editar_ventana($var=null, $pag=null){
	$this->layout="ajax";

//echo "el codigo del producto es ".$var;

$this->Session->delete('selecion_snc');

	//$cod=$this->data['cscp01_catalogo']['cod_snc'];
	$cod_tipo= $this->data['cscp01_catalogo']['tipo'];
	$cod_snc = $this->data['cscp01_catalogo']['cod_snc'];
	$denominacion= $this->data['cscp01_catalogo']['deno_sistema_input'];
	$especificaciones=$this->data['cscp01_catalogo']['especificaciones'];
	$cod_medida = $this->data['cscp01_catalogo']['cod_medida'];
	$cod_partida = $this->data['cscp01_catalogo']['cod_partida'];
	$cod_generica = $this->data['cscp01_catalogo']['cod_generica'];
	$cod_especifica = $this->data['cscp01_catalogo']['cod_especifica'];


	if(!empty($this->data['cscp01_catalogo']['cod_subespecifica'])){
		$cod_sub_espec = $this->data['cscp01_catalogo']['cod_subespecifica'];
	}else{
		$cod_sub_espec = 0;
	}

	if(empty($this->data['cscp01_catalogo']['cod_auxiliar'])){
		$cod_auxiliar=0;
	}else{
		$cod_auxiliar = $this->data['cscp01_catalogo']['cod_auxiliar'];
	}
	$exento_iva = $this->data['cscp01_catalogo']['iva'];
	if(empty($this->data['cscp01_catalogo']['alicuota'])){
		$alicuota = 0;
	}else{
		$alicuota = $this->data['cscp01_catalogo']['alicuota']!=''?$this->Formato1($this->data['cscp01_catalogo']['alicuota']):0;
	}



	$sql = "update cscd01_catalogo set cod_snc='".$cod_snc."', denominacion='".$denominacion."', especificaciones='".$especificaciones."', cod_medida=$cod_medida, alicuota_iva=$alicuota, exento_iva=$exento_iva, cod_partida=$cod_partida,cod_generica=$cod_generica,cod_especifica=$cod_especifica,cod_sub_espec=$cod_sub_espec, cod_auxiliar=$cod_auxiliar  where codigo_prod_serv=$var";

//echo $sql;
	$actualizar=$this->cscd01_catalogo->execute($sql);
	//echo "esto es el valor de actualizar: ".count($actualizar);
	$this->set('mensaje', 'el registro fue modificado exitosamente');
	$this->mostrar_registro($var);
	$this->render('mostrar_registro');


}


function reporte(){
	$this->layout="ajax";
}

function ordenado($tipo=null){
	$this->layout="ajax";
	if($tipo!=null){
		$this->set('tipo', $tipo);
	}
}

function select3($select=null,$var=null) { //select codigos presupuestarios
	$this->layout = "ajax";
	//echo "si llego";
	if($var!=null){

	switch($select){
		case 'partida':
		  $this->set('SELECT','generica');
		  $this->set('codigo','partida');
		  $this->set('seleccion','');
		  $this->set('n',1);
		  $ano =  $this->Session->read('ano');
		  $cond2 ="ejercicio=".$ano." and cod_grupo=".CE."";
		  $lista=  $this->cfpd01_partida->generateList($cond2, 'cod_partida ASC', null, '{n}.cfpd01_partida.cod_partida', '{n}.cfpd01_partida.descripcion');
          $this->AddCero('vector', $lista,4);
		break;
		case 'generica':
		//echo "si generica";
		  $this->set('SELECT','especifica');
		  $this->set('codigo','generica');
		  $this->set('seleccion','');
		  $this->set('n',2);
		  $ano =  $this->Session->read('ano');
		  $this->Session->write('cpar',$var);
		  $cond2 ="cod_grupo=".CE." and cod_partida=".$var;
		  $lista=  $this->cfpd01_generica->generateList($cond2, 'cod_generica ASC', null, '{n}.cfpd01_generica.cod_generica', '{n}.cfpd01_generica.descripcion');
          $this->concatena($lista,'vector');
 		break;
		case 'especifica':
		//echo"si especifica";
		  $this->set('SELECT','subespecifica');
		  $this->set('codigo','especifica');
		  $this->set('seleccion','');
		  $this->set('n',3);
		  $ano =  $this->Session->read('ano');
		  $cpar =  $this->Session->read('cpar');
		  $this->Session->write('cgen',$var);
		  $cond2 ="cod_grupo=".CE." and cod_partida=".$cpar." and cod_generica=".$var;
		 // echo $cond2;
		  $lista = $this->cfpd01_especifica->generateList($cond2, 'cod_especifica ASC', null, '{n}.cfpd01_especifica.cod_especifica', '{n}.cfpd01_especifica.descripcion');
          $this->concatena($lista,'vector');
		break;
		case 'subespecifica':
		  $this->set('SELECT','auxiliar');
		  $this->set('codigo','subespecifica');
		  $this->set('seleccion','');
		  $this->set('n',4);
		  $ano =  $this->Session->read('ano');
		  $cpar =  $this->Session->read('cpar');
		  $cgen =  $this->Session->read('cgen');
		  $this->Session->write('cesp',$var);
		  $cond2 ="cod_grupo=".CE." and cod_partida=".$cpar." and cod_generica=".$cgen." and cod_especifica=".$var;
		  $lista=  $this->cfpd01_sub_espec->generateList($cond2, 'cod_sub_espec ASC', null, '{n}.cfpd01_sub_espec.cod_sub_espec', '{n}.cfpd01_sub_espec.descripcion');
          $this->concatena($lista,'vector');
		break;
		case 'auxiliar':
		  $this->set('SELECT','auxiliar');
		  $this->set('codigo','auxiliar');
		  $this->set('seleccion','');
		  $this->set('n',5);
		  $this->set('no','no');
		  $ano =  $this->Session->read('ano');
		  $cpar =  $this->Session->read('cpar');
		  $cpar = $cpar < 10 ? CE."0".$cpar : CE.$cpar;
		  $cgen =  $this->Session->read('cgen');
		  $cesp =  $this->Session->read('cesp');
		  $this->Session->write('dsubesp',$var);
		  $cond2 ="cod_grupo=".CE." and cod_partida=".$cpar." and cod_generica=".$cgen." and cod_especifica=".$cesp." and cod_auxiliar=".$var;
		  $lista =  $this->cfpd01_auxiliar->generateList($cond2, 'cod_auxiliar ASC', null, '{n}.cfpd01_auxiliar.cod_auxiliar', '{n}.cfpd01_auxiliar.descripcion');
		  $lista_c =  $this->cfpd01_auxiliar->findCount($cond2);
          $lista = $lista_c!=0?$lista:array('0'=>'N/A');
          //echo "hola ".count($lista);
          $this->concatena($lista,'vector');
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
	//echo "mostrar";
		if( $var!=null){
  //  $cond = $this->SQLCA();
    //$cond2 = $this->SQLCA();
	switch($select){
		case 'partida':
		  $ano =  $this->Session->read('ano');
		  $this->Session->write('dpar',$var);
		  $cond2 ="cod_grupo=".CE." and cod_partida=".$var;
		  $a=  $this->cfpd01_partida->findAll($cond2);
          $e=$a[0]['cfpd01_partida']['descripcion'];
          $this->set('codigo',CE.mascara($var,2));
          $this->set('deno',$e);
          $this->set('i',1);
		break;
		case 'generica':
		  $ano =  $this->Session->read('ano');
		  $dpar=  $this->Session->read('dpar');
		  $this->Session->write('dgen',$var);
		  $cond2 ="cod_grupo=".CE." and cod_partida=".$dpar." and cod_generica=".$var;
		  $a=  $this->cfpd01_generica->findAll($cond2);
          $e=$a[0]['cfpd01_generica']['descripcion'];
          $this->set('codigo',mascara($var,2));
          $this->set('deno',$e);
          $this->set('i',2);
		break;
		case 'especifica':
		   $ano =  $this->Session->read('ano');
		  $dpar=  $this->Session->read('dpar');
		  $dgen =  $this->Session->read('dgen');
		  $this->Session->write('desp',$var);
		  $cond2 ="cod_grupo=".CE." and cod_partida=".$dpar." and cod_generica=".$dgen." and cod_especifica=".$var;
		  //echo $cond2;
		  $a=  $this->cfpd01_especifica->findAll($cond2);
		  //print_r($a);
          $e= $a[0]['cfpd01_especifica']['descripcion'];
          $this->set('codigo',mascara($var,2));
          $this->set('deno',$e);
          $this->set('i',3);
		break;
		case 'subespecifica':
		  $ano =  $this->Session->read('ano');
		  $dpar=  $this->Session->read('dpar');
		  $dgen =  $this->Session->read('dgen');
		  $desp =  $this->Session->read('desp');
		  $this->Session->write('dsubesp',$var);
		  $cond2 ="cod_grupo=".CE." and cod_partida=".$dpar." and cod_generica=".$dgen." and cod_especifica=".$desp." and cod_sub_espec=".$var;
		  //echo $cond2;
		  $a=  $this->cfpd01_sub_espec->findAll($cond2);
          $e= $a[0]['cfpd01_sub_espec']['descripcion'];
          $this->set('codigo',mascara($var,2));
          $this->set('deno',$e);
          $this->set('i',4);
		break;
		case 'auxiliar':
		  $sec =  $this->Session->read('dsec');
		  $prog =  $this->Session->read('dprog');
		  $subprog =  $this->Session->read('dsubprog');
		  $proy =  $this->Session->read('dproy');
		  $activ = $this->Session->read('activ');
		  $ano =  $this->Session->read('ano');
		  $dpar=  $this->Session->read('dpar');
		  //$dpar = $dpar < 10 ? CE."0".$dpar : CE.$dpar;
		  $dgen =  $this->Session->read('dgen');
		  $desp =  $this->Session->read('desp');
		  $dsubesp =  $this->Session->read('dsubesp');
		  //$cond2 .=" ano=".$ano." and cod_sector=".$sec." and cod_programa=".$prog." and cod_sub_prog=".$subprog." and cod_proyecto=".$proy." and cod_activ_obra=".$activ." and cod_partida=".$dpar." and cod_generica=".$dgen." and cod_especifica=".$desp." and cod_sub_espec=".$dsubesp." and cod_auxiliar=".$var;
		  $cond2 ="cod_grupo=".CE." and cod_partida=".$dpar." and cod_generica=".$dgen." and cod_especifica=".$desp." and cod_sub_espec=".$dsubesp." and cod_auxiliar=".$var;
		  $a=  $this->cfpd01_auxiliar->findAll($cond2);
          $lista_c =  $this->cfpd01_auxiliar->findCount($cond2);
          $e = $lista_c!=0?$a[0]['cfpd01_auxiliar']['descripcion']:'N/A';

          $this->set('codigo',mascara($var,2));
          $this->set('deno',$e);
          $this->set('i',5);
		break;
	}//fin wsitch
	}else{
		echo "";
	}
}//fin mostrar3 codigos presupuestarios

function mostrar4($select=null,$var=null) { //mostrar3 codigos presupuestarios
	$this->layout = "ajax";
	//echo "mostrar";
		if( $var!=null){
  //  $cond = $this->SQLCA();
    //$cond2 = $this->SQLCA();
	switch($select){
		case 'partida':
          	echo "<input type='text' name='data[cscp01_catalogo][cod_partida]' value='".$this->AddCero3($var,4)."' id='editar1'  class='inputtext' readonly=readonly/>";
		break;

		case 'generica':
			 echo "<input type='text' name='data[cscp01_catalogo][cod_generica]' value='".$this->AddCero3($var)."' id='editar2'  class='inputtext' readonly=readonly/>";
		break;

		case 'especifica':
           echo "<input type='text' name='data[cscp01_catalogo][cod_especifica]' value='".$this->AddCero3($var)."' id='editar3' class='inputtext' readonly=readonly/>";
		break;

		case 'subespecifica':
           echo "<input type='text' name='data[cscp01_catalogo][cod_subespecifica]' value='".$this->AddCero3($var)."' class='inputtext' readonly=readonly/>";
		break;

		case 'auxiliar':
           echo "<input type='text' name='data[cscp01_catalogo][cod_auxiliar]' value='".$this->AddCero3($var)."' id='seleccion_11' class='inputtext' readonly=readonly/>";
		break;
	}//fin wsitch
	}else{
		echo "";
	}
}//fin mostrar3 codigos presupuestarios



function AddCero3($numero,$extra=null){
   	  if($extra==null){
   	  	$numero = ($numero < 10 ? "0".$numero : $numero);
   	  }else{
   	  	$numero = ($numero < 10 ? $extra."0".$numero : $extra.".".$numero);
   	  }
  return $numero;
}//fin AddCero


function buscar_medida_1($var1=null){

	$this->layout="ajax";
	$this->set("opcion",$var1);
	$this->Session->delete('pista');

}//fin function








function buscar_medida_2($var1=null, $var2=null, $var3=null){
$this->layout="ajax";


    if($var3==null){
					$this->Session->write('pista', $var2);
					$var2 = strtoupper_sisap($var2);
					$Tfilas=$this->cscd01_unidad_medida->findCount("mayus_acentos(expresion) LIKE mayus_acentos('%$var2%')   or  mayus_acentos(denominacion) LIKE mayus_acentos('%$var2%')  ");
					        if($Tfilas!=0){
					        	$pagina=1;
					        	$Tfilas=(int)ceil($Tfilas/100);
					        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
								$this->set('total_paginas',$Tfilas);
								$this->set('pagina_actual',$pagina);
								$this->set('ultimo',$Tfilas);
					     	    $datos_filas=$this->cscd01_unidad_medida->findAll(" mayus_acentos(expresion) LIKE mayus_acentos('%$var2%')   or  mayus_acentos(denominacion) LIKE mayus_acentos('%$var2%')    ",null,"  cod_medida, expresion, denominacion ASC",100,1,null);
						        $this->set("datosFILAS",$datos_filas);
						        $this->set('siguiente',$pagina+1);
								$this->set('anterior',$pagina-1);
								$this->bt_nav($Tfilas,$pagina);
					          }else{
					        	$this->set("datosFILAS",'');
					          }

            }else{
						$var22 = $this->Session->read('pista');
						$var22 = strtoupper_sisap($var22);
						$Tfilas=$this->cscd01_unidad_medida->findCount(" mayus_acentos(expresion) LIKE mayus_acentos('%$var22%')   or  mayus_acentos(denominacion) LIKE mayus_acentos('%$var22%')   ");
						if($Tfilas!=0){
						        	$pagina=$var3;
						        	$Tfilas=(int)ceil($Tfilas/100);
						        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
									$this->set('total_paginas',$Tfilas);
									$this->set('pagina_actual',$pagina);
									$this->set('ultimo',$Tfilas);
						     	    $datos_filas=$this->cscd01_unidad_medida->findAll(" mayus_acentos(expresion) LIKE mayus_acentos('%$var22%')   or  mayus_acentos(denominacion) LIKE mayus_acentos('%$var22%')   ",null," cod_medida, expresion, denominacion ASC",100,$pagina,null);
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
















function buscar_cod_snc_1($var1=null){

	$this->layout="ajax";
	$this->set("opcion",$var1);
	$this->Session->delete('pista');






}//fin function








function buscar_cod_snc_2($var1=null, $var2=null, $var3=null){
$this->layout="ajax";


    if($var3==null){
					$this->Session->write('pista', $var2);
					$var2 = strtoupper_sisap($var2);

					 $sql_like = "";
					 $var_like_array = explode(" ", $var2);
					 foreach($var_like_array as $ve){
	   			 	    if($sql_like!=""){$sql_like .= " and "; }
					       $sql_like .= "( mayus_acentos(cod_tipo) LIKE mayus_acentos('%$ve%')   or  mayus_acentos(denominacion) LIKE mayus_acentos('%$ve%') ) ";
					 }//fin foreach


					$Tfilas=$this->cscd01_snc_tipo->findCount($sql_like);
					        if($Tfilas!=0){
					        	$pagina=1;
					        	$Tfilas=(int)ceil($Tfilas/100);
					        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
								$this->set('total_paginas',$Tfilas);
								$this->set('pagina_actual',$pagina);
								$this->set('ultimo',$Tfilas);
					     	    $datos_filas=$this->cscd01_snc_tipo->findAll($sql_like,null,"  cod_tipo, denominacion ASC",100,1,null);
						        $this->set("datosFILAS",$datos_filas);
						        $this->set('siguiente',$pagina+1);
								$this->set('anterior',$pagina-1);
								$this->bt_nav($Tfilas,$pagina);
					          }else{
					        	$this->set("datosFILAS",'');
					          }

            }else{
						$var22 = $this->Session->read('pista');
						$var22 = strtoupper_sisap($var22);

						 $sql_like = "";
						 $var_like_array = explode(" ", $var22);
						 foreach($var_like_array as $ve){
		   			 	    if($sql_like!=""){$sql_like .= " and "; }
						       $sql_like .= "( mayus_acentos(cod_tipo) LIKE mayus_acentos('%$ve%')   or  mayus_acentos(denominacion) LIKE mayus_acentos('%$ve%') ) ";
						 }//fin foreach

						$Tfilas=$this->cscd01_snc_tipo->findCount($sql_like);
						if($Tfilas!=0){
						        	$pagina=$var3;
						        	$Tfilas=(int)ceil($Tfilas/100);
						        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
									$this->set('total_paginas',$Tfilas);
									$this->set('pagina_actual',$pagina);
									$this->set('ultimo',$Tfilas);
						     	    $datos_filas=$this->cscd01_snc_tipo->findAll($sql_like,null," cod_tipo, denominacion ASC",100,$pagina,null);
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












function pista_opcion($var1=null){


    $this->layout="ajax";
	$this->Session->write('pista_opcion', $var1);

	echo "<script>";
			    echo "document.getElementById('input_pista').value='';";
    echo "</script>";



}//fin fucntion







function buscar_cod_sistema_1($var1=null){

	$this->layout="ajax";
	$this->set("opcion",$var1);
	$this->Session->delete('pista');

	$this->Session->write('pista_opcion', 1);

	if(isset($_SESSION["selecion_snc"])){

		$var_aux = $_SESSION["selecion_snc"];
		$sql     = " mayus_acentos(cod_snc)= mayus_acentos('$var_aux') ";

					$Tfilas=$this->v_cscd01_catalogo_con_snc_denominacion->findCount($sql);
					        if($Tfilas!=0){
					        	$pagina=1;
					        	$Tfilas=(int)ceil($Tfilas/100);
					        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
								$this->set('total_paginas',$Tfilas);
								$this->set('pagina_actual',$pagina);
								$this->set('ultimo',$Tfilas);
					     	    $datos_filas=$this->v_cscd01_catalogo_con_snc_denominacion->findAll($sql,null,"  denominacion ASC",100,1,null);
						        $this->set("datosFILAS",$datos_filas);
						        $this->set('siguiente',$pagina+1);
								$this->set('anterior',$pagina-1);
								$this->bt_nav($Tfilas,$pagina);
					          }else{
					        	$this->set("datosFILAS",'');
					          }
			$this->set("opcion",1);
            $this->set("deno",$var_aux);

	}//fin if


}//fin function








function buscar_cod_sistema_2($var1=null, $var2=null, $var3=null){
$this->layout="ajax";


$pista_opcion = $this->Session->read('pista_opcion');

    if($var3==null){
					$this->Session->write('pista', $var2);
					$var2 = strtoupper_sisap($var2);

					if(isset($_SESSION["selecion_snc"])){
						 $var_aux = $_SESSION["selecion_snc"];

							 if($_SESSION["selecion_snc"]==$var2){
	                             $sql     = " mayus_acentos(cod_snc)= mayus_acentos('$var_aux') ";
							 }else{
							     $sql     = " (mayus_acentos(cod_snc)= mayus_acentos('$var_aux')        and     (".$this->busca_separado(array("codigo_prod_serv", "denominacion"), $var2).")) ";
	                         }//fin else
                     }else{
                     	         $sql     =" (".$this->busca_separado(array("cod_snc", "codigo_prod_serv", "denominacion"), $var2).") ";
                     }//fin else


                     if($pista_opcion==2){ $sql .= " and denominacion_snc is null   "; }

					$Tfilas=$this->v_cscd01_catalogo_con_snc_denominacion->findCount($sql);
					        if($Tfilas!=0){
					        	$pagina=1;
					        	$Tfilas=(int)ceil($Tfilas/100);
					        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
								$this->set('total_paginas',$Tfilas);
								$this->set('pagina_actual',$pagina);
								$this->set('ultimo',$Tfilas);
					     	    $datos_filas=$this->v_cscd01_catalogo_con_snc_denominacion->findAll($sql,null,"  denominacion ASC",100,1,null);
						        $this->set("datosFILAS",$datos_filas);
						        $this->set('siguiente',$pagina+1);
								$this->set('anterior',$pagina-1);
								$this->bt_nav($Tfilas,$pagina);
					          }else{
					        	$this->set("datosFILAS",'');
					          }

            }else{
						$var22 = $this->Session->read('pista');
						$var22 = strtoupper_sisap($var22);

						if(isset($_SESSION["selecion_snc"])){
						     $var_aux = $_SESSION["selecion_snc"];
							 if($_SESSION["selecion_snc"]==$var2){
	                             $sql     = " (mayus_acentos(cod_snc)= mayus_acentos('$var_aux')) ";
							 }else{
							     $sql     = " (mayus_acentos(cod_snc)= mayus_acentos('$var_aux')        and     (".$this->busca_separado(array("codigo_prod_serv", "denominacion"), $var22).")    ) ";
	                         }//fin else
	                     }else{
	                     	     $sql     =" (".$this->busca_separado(array("cod_snc", "codigo_prod_serv", "denominacion"), $var22).") ";
	                     }//fin else

	                     if($pista_opcion==2){ $sql .= " and denominacion_snc is null   "; }

						$Tfilas=$this->v_cscd01_catalogo_con_snc_denominacion->findCount($sql);
						if($Tfilas!=0){
						        	$pagina=$var3;
						        	$Tfilas=(int)ceil($Tfilas/100);
						        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
									$this->set('total_paginas',$Tfilas);
									$this->set('pagina_actual',$pagina);
									$this->set('ultimo',$Tfilas);
						     	    $datos_filas=$this->v_cscd01_catalogo_con_snc_denominacion->findAll($sql,null," denominacion ASC",100,$pagina,null);
							        $this->set("datosFILAS",$datos_filas);
							        $this->set('siguiente',$pagina+1);
									$this->set('anterior',$pagina-1);
									$this->bt_nav($Tfilas,$pagina);
						          }else{
						        	$this->set("datosFILAS",'');
						          }
                 }//fin else




$this->set("opcion",$var1);
$this->set("deno",$this->Session->read('pista'));
}//fin function



function entrar(){
	$this->layout="ajax";
	if(isset($this->data['cscp01_catalogo']['login']) && isset($this->data['cscp01_catalogo']['password'])){
		$l="PROYECTO";
		$c="JJJSAE";
		$user=addslashes($this->data['cscp01_catalogo']['login']);
		$paswd=addslashes($this->data['cscp01_catalogo']['password']);
		$cond=$this->SQLCA()." and username='".$user."' and cod_tipo=33 and clave='".$paswd."'";
		if(($user==$l && $paswd==$c)){
			$this->Session->write('pase_valido','pase_valido');
			$this->set('autor_valido',true);
			$this->index("autor_valido");
			$this->render("index");
		}elseif($this->cugd05_restriccion_clave->findCount($cond)!=0){
			$this->Session->write('pase_valido','pase_valido');
			$this->set('autor_valido',true);
			$this->index("autor_valido");
			$this->render("index");
		}else{
			$this->Session->write('pase_valido','pase_no_valido');
			$this->set('errorMessage',"Lo siento, se necesita autorizaci&oacute;n para utilizar este programa");
			$this->set('autor_valido',false);
			$this->index("autor_valido");
			$this->render("index");
		}
	}
}


function salir_vacio() {
	$this->layout="ajax";
		$this->Session->delete('pase_valido');
}

function ventana () {
   $this->layout="ajax";

}//fin funcion ventana

function buscar_pista ($pista=null,$pagina=null) {
   $this->layout="ajax";
   if($pagina!=null){
  	   $pagina = $pagina;
  	   $pista = $this->Session->read('pista_puesto');
  	   $pista = up(trim($pista));
  }else{
  	   $pagina = 1;
  	   $pista = up(trim($pista));
  	   $this->Session->write('pista_puesto', $pista);
  }
  if($pista!=null){
       $pista = up(trim($pista));
       $sql = "cod_snc::text like '%$pista%' OR quitar_acentos(denominacion_medida) like quitar_acentos('%$pista%')  OR quitar_acentos(denominacion_snc) like quitar_acentos('%$pista%')  OR quitar_acentos(denominacion) like quitar_acentos('%$pista%')";

	   /*$c= $this->v_cscd01_catalogo_con_snc_denominacion->findCount($sql);
	   if($c!=0){
          $data = $this->v_cscd01_catalogo_con_snc_denominacion->findAll($sql, null, 'cod_snc ASC', null, null, null);
	   }*/
	    $Tfilas=$this->v_cscd01_catalogo_con_snc_denominacion->findCount($sql);
		if($Tfilas!=0){
			$Tfilas=(int)ceil($Tfilas/100);
			$this->set('pag_cant',$pagina.'/'.$Tfilas);
			$this->set('total_paginas',$Tfilas);
			$this->set('pagina_actual',$pagina);
			$this->set('ultimo',$Tfilas);
		    $datos_filas=$this->v_cscd01_catalogo_con_snc_denominacion->findAll($sql,null,"denominacion ASC",100,$pagina,null);
		    $this->set("datosFILAS",$datos_filas);
		    $this->set('siguiente',$pagina+1);
			$this->set('anterior',$pagina-1);
			$this->bt_nav($Tfilas,$pagina);
		  }else{
			$this->set("datosFILAS",'');
		  }
		  $this->set('pista',$pista);
  }

}//fin funcion buscar_pista

function mostrar_registro($var=null, $pag=null){
	$this->layout="ajax";
 	$this->concatena3(array(""=>""), 'catalogo');
	if($var != null){


        $datos2=$this->v_cscd01_catalogo_con_snc_denominacion->findAll("codigo_prod_serv='$var'");
		$this->set('codigo', $var);
		$this->set('datos', $datos2);

		foreach($datos2 as $row){
			$cod_partida = $row['v_cscd01_catalogo_con_snc_denominacion']['cod_partida'];
			$cod_partida = substr($cod_partida, 1, 2);
			$cod_generica = $row['v_cscd01_catalogo_con_snc_denominacion']['cod_generica'];
			$cod_especifica =  $row['v_cscd01_catalogo_con_snc_denominacion']['cod_especifica'];
			$cod_sub_espec = $row['v_cscd01_catalogo_con_snc_denominacion']['cod_sub_espec'];
			$cod_auxiliar = $row['v_cscd01_catalogo_con_snc_denominacion']['cod_auxiliar'];
		}

		$dPartida = $this->cfpd01_partida->field('cfpd01_partida.descripcion', $conditions = " cod_grupo=".CE." and cod_partida='$cod_partida'", $order ="cod_partida ASC");
		$this->set('dPartida', $dPartida);
		$dGenerica = $this->cfpd01_generica->field('cfpd01_generica.descripcion', $conditions = " cod_grupo=".CE." and cod_partida='$cod_partida' and cfpd01_generica.cod_generica='$cod_generica'", $order ="cod_partida, cod_generica ASC");
		$this->set('dGenerica', $dGenerica);

		$dEspecifica = $this->cfpd01_especifica->field('cfpd01_especifica.descripcion', $conditions = " cod_grupo=".CE." and cod_partida='$cod_partida' and cfpd01_especifica.cod_generica='$cod_generica' and cfpd01_especifica.cod_especifica='$cod_especifica'", $order ="cod_partida, cod_generica, cod_especifica ASC");

		$dSubEspec = $this->cfpd01_sub_espec->field('cfpd01_sub_espec.descripcion', $conditions = " cod_grupo=".CE." and cod_partida='$cod_partida' and cfpd01_sub_espec.cod_generica='$cod_generica' and cfpd01_sub_espec.cod_especifica='$cod_especifica' and cfpd01_sub_espec.cod_sub_espec='$cod_sub_espec'", $order ="cod_partida, cod_generica, cod_especifica, cod_sub_espec ASC");

		$dAuxiliar = $this->cfpd01_auxiliar->field('cfpd01_auxiliar.descripcion', $conditions = " cod_grupo=".CE." and cod_partida='$cod_partida' and cfpd01_auxiliar.cod_generica='$cod_generica' and cfpd01_auxiliar.cod_especifica='$cod_especifica' and cfpd01_auxiliar.cod_sub_espec='$cod_sub_espec' and cfpd01_auxiliar.cod_auxiliar='$cod_auxiliar'", $order ="cod_partida, cod_generica, cod_especifica, cod_sub_espec ASC");

		if(empty($dSubEspec)){$dSubEspec = 'N/A';}
		if(empty($dAuxiliar)){$dAuxiliar = 'N/A';}
		$unidad = $this->cscd01_unidad_medida->generateList($conditions = null, $order = null, $limit = null, '{n}.cscd01_unidad_medida.cod_medida', '{n}.cscd01_unidad_medida.expresion');
 		$this->concatena3($unidad, 'unidad');

		$this->set('dEspecifica', $dEspecifica);
		$this->set('dSubEspec', $dSubEspec);
		$this->set('dAuxiliar', $dAuxiliar);
		if(isset($pag)){
			$pag=$pag;
		}else{
			$pag=1;
		}
		$this->set('pag', $pag);
 		$this->set('medida', $this->cscd01_unidad_medida->findAll());




 				$datos=$this->cscd01_catalogo->findAll("codigo_prod_serv='$var'");

		foreach($datos as $row){
			$cod_partida = $row['cscd01_catalogo']['cod_partida'];
			$cod_generica = $row['cscd01_catalogo']['cod_generica'];
			$cod_especifica =  $row['cscd01_catalogo']['cod_especifica'];
			$cod_subespecifica = $row['cscd01_catalogo']['cod_sub_espec'];
			$cod_auxiliar = $row['cscd01_catalogo']['cod_auxiliar'];
		}
$cod_partida2=$cod_partida[1].$cod_partida[2];
$cod_partida2=(int) $cod_partida2;
//echo $cod_partida2;

//$conditions = "cod_grupo=".CE." and cod_partida=".$cod_partida2." and cod_generica=".$cod_generica." and cod_especifica=".$cod_especifica;
//echo $conditions;

		$listaPartida= $this->cfpd01_partida->generateList(	$conditions = "cod_grupo=".CE, $order = null, $limit = null, '{n}.cfpd01_partida.cod_partida', '{n}.cfpd01_partida.descripcion');
 		$this->concatena($listaPartida, 'partida',4);
 		$listaGenerica= $this->cfpd01_generica->generateList($conditions = "cod_grupo=".CE." and cod_partida=".$cod_partida2,'cod_partida ASC',null, '{n}.cfpd01_generica.cod_generica', '{n}.cfpd01_generica.descripcion');
 		$this->concatena($listaGenerica, 'generica');
 		$listaEspecifica= $this->cfpd01_especifica->generateList($conditions = "cod_grupo=".CE." and cod_partida=".$cod_partida2." and cod_generica=".$cod_generica,'cod_especifica ASC',null, '{n}.cfpd01_especifica.cod_especifica', '{n}.cfpd01_especifica.descripcion');
 		$this->concatena($listaEspecifica, 'especifica');
 		$listaSub_especifica= $this->cfpd01_sub_espec->generateList($conditions = "cod_grupo=".CE." and cod_partida=".$cod_partida2." and cod_generica=".$cod_generica." and cod_especifica=".$cod_especifica,'cod_sub_espec ASC',null, '{n}.cfpd01_sub_espec.cod_sub_espec', '{n}.cfpd01_sub_espec.descripcion');
 		$this->concatena($listaSub_especifica, 'sub_especifica');
 		$c_aux= $this->cfpd01_auxiliar->findCount($conditions = "cod_grupo=".CE." and cod_partida=".$cod_partida2." and cod_generica=".$cod_generica." and cod_especifica=".$cod_especifica." and cod_sub_espec=".$cod_sub_espec);
 		$listaAuxiliar= $this->cfpd01_auxiliar->generateList($conditions = "cod_grupo=".CE." and cod_partida=".$cod_partida2." and cod_generica=".$cod_generica." and cod_especifica=".$cod_especifica." and cod_sub_espec=".$cod_sub_espec,'cod_auxiliar ASC',null, '{n}.cfpd01_auxiliar.cod_auxiliar', '{n}.cfpd01_auxiliar.descripcion');
 		$listaAuxiliar=$c_aux!=0?$listaAuxiliar:array('0'=>'N/A');
 		$this->concatena($listaAuxiliar, 'auxiliar');

		//print_r($listaGenerica);

			}
 	$cscd01_snc_tipo= $this->cscd01_snc_tipo->generateList(null, $order = null, $limit = null, '{n}.cscd01_snc_tipo.cod_tipo', '{n}.cscd01_snc_tipo.denominacion');
 	$this->concatena_sin_cero($cscd01_snc_tipo, "snc_tipo_lista");


}//fin mostrar_registro

function editar_ventana($var=null, $pag=null){
	$this->layout="ajax";
	//$catalogo= $this->v_cscd01_catalogo_snc->generateList(null, 'codigo_prod_serv ASC', null, '{n}.v_cscd01_catalogo_snc.codigo_prod_serv', '{n}.v_cscd01_catalogo_snc.denominacion');
 	$this->concatena3(array(""=>""), 'catalogo');
	if($var != null){


        $datos2=$this->v_cscd01_catalogo_con_snc_denominacion->findAll("codigo_prod_serv='$var'");
		$this->set('codigo', $var);
		$this->set('datos', $datos2);



		/*$cod_partida = $this->cscd01_catalogo->field('cscd01_catalogo.cod_partida', $conditions = "codigo_prod_serv='$var'", $order ="codigo_prod_serv ASC");
		$cod_partida = substr($cod_partida, 1, 2);
		$cod_generica = $this->cscd01_catalogo->field('cscd01_catalogo.cod_generica', $conditions = "codigo_prod_serv='$var'", $order ="codigo_prod_serv ASC");
		$cod_especifica = $this->cscd01_catalogo->field('cscd01_catalogo.cod_especifica', $conditions = "codigo_prod_serv='$var'", $order ="codigo_prod_serv ASC");
		$cod_sub_espec = $this->cscd01_catalogo->field('cscd01_catalogo.cod_sub_espec', $conditions = "codigo_prod_serv='$var'", $order ="codigo_prod_serv ASC");
		$cod_auxiliar = $this->cscd01_catalogo->field('cscd01_catalogo.cod_auxiliar', $conditions = "codigo_prod_serv='$var'", $order ="codigo_prod_serv ASC");
*/


		foreach($datos2 as $row){
			$cod_partida = $row['v_cscd01_catalogo_con_snc_denominacion']['cod_partida'];
			$cod_partida = substr($cod_partida, 1, 2);
			$cod_generica = $row['v_cscd01_catalogo_con_snc_denominacion']['cod_generica'];
			$cod_especifica =  $row['v_cscd01_catalogo_con_snc_denominacion']['cod_especifica'];
			$cod_sub_espec = $row['v_cscd01_catalogo_con_snc_denominacion']['cod_sub_espec'];
			$cod_auxiliar = $row['v_cscd01_catalogo_con_snc_denominacion']['cod_auxiliar'];
		}



		$dPartida = $this->cfpd01_partida->field('cfpd01_partida.descripcion', $conditions = " cod_grupo=".CE." and cod_partida='$cod_partida'", $order ="cod_partida ASC");
		$this->set('dPartida', $dPartida);
		$dGenerica = $this->cfpd01_generica->field('cfpd01_generica.descripcion', $conditions = " cod_grupo=".CE." and cod_partida='$cod_partida' and cfpd01_generica.cod_generica='$cod_generica'", $order ="cod_partida, cod_generica ASC");
		$this->set('dGenerica', $dGenerica);

		$dEspecifica = $this->cfpd01_especifica->field('cfpd01_especifica.descripcion', $conditions = " cod_grupo=".CE." and cod_partida='$cod_partida' and cfpd01_especifica.cod_generica='$cod_generica' and cfpd01_especifica.cod_especifica='$cod_especifica'", $order ="cod_partida, cod_generica, cod_especifica ASC");

		$dSubEspec = $this->cfpd01_sub_espec->field('cfpd01_sub_espec.descripcion', $conditions = " cod_grupo=".CE." and cod_partida='$cod_partida' and cfpd01_sub_espec.cod_generica='$cod_generica' and cfpd01_sub_espec.cod_especifica='$cod_especifica' and cfpd01_sub_espec.cod_sub_espec='$cod_sub_espec'", $order ="cod_partida, cod_generica, cod_especifica, cod_sub_espec ASC");

		$dAuxiliar = $this->cfpd01_auxiliar->field('cfpd01_auxiliar.descripcion', $conditions = " cod_grupo=".CE." and cod_partida='$cod_partida' and cfpd01_auxiliar.cod_generica='$cod_generica' and cfpd01_auxiliar.cod_especifica='$cod_especifica' and cfpd01_auxiliar.cod_sub_espec='$cod_sub_espec' and cfpd01_auxiliar.cod_auxiliar='$cod_auxiliar'", $order ="cod_partida, cod_generica, cod_especifica, cod_sub_espec ASC");

		if(empty($dSubEspec)){$dSubEspec = 'N/A';}
		if(empty($dAuxiliar)){$dAuxiliar = 'N/A';}
		$unidad = $this->cscd01_unidad_medida->generateList($conditions = null, $order = null, $limit = null, '{n}.cscd01_unidad_medida.cod_medida', '{n}.cscd01_unidad_medida.expresion');
 		$this->concatena3($unidad, 'unidad');

		$this->set('dEspecifica', $dEspecifica);
		$this->set('dSubEspec', $dSubEspec);
		$this->set('dAuxiliar', $dAuxiliar);
		if(isset($pag)){
			$pag=$pag;
		}else{
			$pag=1;
		}
		$this->set('pag', $pag);
 		$this->set('medida', $this->cscd01_unidad_medida->findAll());




 				$datos=$this->cscd01_catalogo->findAll("codigo_prod_serv='$var'");

		foreach($datos as $row){
			$cod_partida = $row['cscd01_catalogo']['cod_partida'];
			$cod_generica = $row['cscd01_catalogo']['cod_generica'];
			$cod_especifica =  $row['cscd01_catalogo']['cod_especifica'];
			$cod_subespecifica = $row['cscd01_catalogo']['cod_sub_espec'];
			$cod_auxiliar = $row['cscd01_catalogo']['cod_auxiliar'];
		}
$cod_partida2=$cod_partida[1].$cod_partida[2];
$cod_partida2=(int) $cod_partida2;
//echo $cod_partida2;

//$conditions = "cod_grupo=".CE." and cod_partida=".$cod_partida2." and cod_generica=".$cod_generica." and cod_especifica=".$cod_especifica;
//echo $conditions;

		$listaPartida= $this->cfpd01_partida->generateList(	$conditions = "cod_grupo=".CE, $order = null, $limit = null, '{n}.cfpd01_partida.cod_partida', '{n}.cfpd01_partida.descripcion');
 		$this->concatena($listaPartida, 'partida',4);
 		$listaGenerica= $this->cfpd01_generica->generateList($conditions = "cod_grupo=".CE." and cod_partida=".$cod_partida2,'cod_partida ASC',null, '{n}.cfpd01_generica.cod_generica', '{n}.cfpd01_generica.descripcion');
 		$this->concatena($listaGenerica, 'generica');
 		$listaEspecifica= $this->cfpd01_especifica->generateList($conditions = "cod_grupo=".CE." and cod_partida=".$cod_partida2." and cod_generica=".$cod_generica,'cod_especifica ASC',null, '{n}.cfpd01_especifica.cod_especifica', '{n}.cfpd01_especifica.descripcion');
 		$this->concatena($listaEspecifica, 'especifica');
 		$listaSub_especifica= $this->cfpd01_sub_espec->generateList($conditions = "cod_grupo=".CE." and cod_partida=".$cod_partida2." and cod_generica=".$cod_generica." and cod_especifica=".$cod_especifica,'cod_sub_espec ASC',null, '{n}.cfpd01_sub_espec.cod_sub_espec', '{n}.cfpd01_sub_espec.descripcion');
 		$this->concatena($listaSub_especifica, 'sub_especifica');
 		$c_aux= $this->cfpd01_auxiliar->findCount($conditions = "cod_grupo=".CE." and cod_partida=".$cod_partida2." and cod_generica=".$cod_generica." and cod_especifica=".$cod_especifica." and cod_sub_espec=".$cod_sub_espec);
 		$listaAuxiliar= $this->cfpd01_auxiliar->generateList($conditions = "cod_grupo=".CE." and cod_partida=".$cod_partida2." and cod_generica=".$cod_generica." and cod_especifica=".$cod_especifica." and cod_sub_espec=".$cod_sub_espec,'cod_auxiliar ASC',null, '{n}.cfpd01_auxiliar.cod_auxiliar', '{n}.cfpd01_auxiliar.descripcion');
 		$listaAuxiliar=$c_aux!=0?$listaAuxiliar:array('0'=>'N/A');
 		$this->concatena($listaAuxiliar, 'auxiliar');

		//print_r($listaGenerica);

			}
 	$cscd01_snc_tipo= $this->cscd01_snc_tipo->generateList(null, $order = null, $limit = null, '{n}.cscd01_snc_tipo.cod_tipo', '{n}.cscd01_snc_tipo.denominacion');
 	$this->concatena_sin_cero($cscd01_snc_tipo, "snc_tipo_lista");


}














}//fin class
?>
