<?php
class Ccnp03CensoPoblacionalController extends AppController {
   var $name = 'ccnp03_censo_poblacional';
   var $uses = array('ccnd01_tipo_directivo','ccnd01_cargos_directivos',"cugd01_republica", "cugd01_estados", "cugd01_municipios",
                     "cugd01_parroquias", "cugd02_direccionsuperior", "ccnd01_concejo_comunal", "cnmd06_parentesco", "cnmd06_profesiones",
                     "cnmd06_oficio", "ccnd01_directiva_familiar", "ccnd01_directiva", "casd01_datos_familiares", "cugd10_imagenes",
					 "ccnd03_censo_familias","ccnd03_censo_miembros",'cugd01_vialidad');
   var $helpers = array('Html','Ajax','Javascript', 'Sisap');

function checkSession(){
				if (!$this->Session->check('concejo_comunal')){
						$this->redirect('/salir');
						exit();
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




 function beforeFilter(){
 	$this->checkSession();
 }



function concatena_familia($vector1=null, $nomVar=null, $extra=null){
	$cod = array();
	if($vector1 != null){
		foreach($vector1 as $x => $y){
			if($x<10){
				$cod[$x] = '00'.$x;
			}else if($x>=10 && $x<=99){
				$cod[$x] = '0'.$x;
			}else if($x>=100 && $x<=999){
				$cod[$x] = $x;
			}
		}
		//print_r($cod);
	}

	$this->set($nomVar, $cod);
}//fin function



 function index(){
 	$this->layout ="ajax";

	$this->set('republica',$this->cugd01_republica->field('denominacion', $conditions ="cod_republica=".$this->Session->read('CC_republica'), $order ="cod_republica ASC"));
	$this->set('estado',$this->cugd01_estados->field('denominacion', $conditions ="cod_republica=".$this->Session->read('CC_republica')." and cod_estado=".$this->Session->read('CC_estado'), $order ="cod_estado ASC"));
	$this->set('municipio',$this->cugd01_municipios->field('denominacion', $conditions ="cod_republica=".$this->Session->read('CC_republica')." and cod_estado=".$this->Session->read('CC_estado')." and cod_municipio=".$this->Session->read('CC_municipio'), $order ="cod_municipio ASC"));
	$this->set('parroquia',$this->cugd01_parroquias->field('denominacion', $conditions ="cod_republica=".$this->Session->read('CC_republica')." and cod_estado=".$this->Session->read('CC_estado')." and cod_municipio=".$this->Session->read('CC_municipio')." and cod_parroquia=".$this->Session->read('CC_parroquia'), $order ="cod_parroquia ASC"));

    $condicion_dir_sup = "";
    $dir_sup=$this->cugd02_direccionsuperior->generateList($condicion_dir_sup,'denominacion ASC', null, '{n}.cugd02_direccionsuperior.cod_dir_superior', '{n}.cugd02_direccionsuperior.denominacion');
	$dir_sup = $dir_sup != null ? $dir_sup : array();
	$this->concatena($dir_sup, 'dir_superior');

	$conditions  = "     cod_republica=".$this->Session->read('CC_republica')." and cod_estado=".$this->Session->read('CC_estado')." and cod_municipio=".$this->Session->read('CC_municipio')." and cod_parroquia=".$this->Session->read('CC_parroquia')." ";
 	$conditions .= " and cod_centro=".$this->Session->read('CC_centro')." and cod_concejo=".$this->Session->read('CC_concejo')."";
    $dir_concejo=$this->ccnd01_concejo_comunal->generateList($conditions,'denominacion ASC', null, '{n}.ccnd01_concejo_comunal.cod_concejo', '{n}.ccnd01_concejo_comunal.denominacion');
	$dir_concejo = $dir_concejo != null ? $dir_concejo : array();
	$this->concatena($dir_concejo, 'concejo');

	$denominacion = $this->ccnd01_concejo_comunal->field('denominacion', $conditions , null);
	$this->set('seleccion_concejo', $this->Session->read('CC_concejo'));
	$this->set('denominacion_concejo', $denominacion);



	$condicion_cod_tipo = "";
    $dir_cod_tipo=$this->ccnd01_tipo_directivo->generateList($condicion_cod_tipo,'denominacion ASC', null, '{n}.ccnd01_tipo_directivo.cod_tipo', '{n}.ccnd01_tipo_directivo.denominacion');
	$dir_cod_tipo = $dir_cod_tipo != null ? $dir_cod_tipo : array();
	$this->concatena($dir_cod_tipo, 'cod_tipo');


	$sql="select * from cugd01_centros_poblados where cod_republica=".$this->Session->read('CC_republica')." and cod_estado=".$this->Session->read('CC_estado')." and cod_municipio=".$this->Session->read('CC_municipio')." and cod_parroquia=".$this->Session->read('CC_parroquia')." and cod_centro=".$this->Session->read('CC_centro');
	$datos=$this->cugd01_parroquias->execute($sql);
	$this->set('datos',$datos);

	$familia=$this->ccnd03_censo_familias->generateList($conditions,'numero_familia ASC', null, '{n}.ccnd03_censo_familias.numero_familia','{n}.ccnd03_censo_familias.numero_familia');
	if($familia!=null){
//		$this->concatenaN($familia,'familia');
		$this->concatena_familia($familia,'familia');
//		$this->set('familia',mascara_tres($familia));
	}else{
		$this->set('familia',array());
	}


	$nacionalidad= array('V'=>'Venezolana','E'=>'Extranjero');
	$this->set('nacionalidad',$nacionalidad);

	$sexo= array('M'=>'Masculino','F'=>'Femenino');
	$this->set('sexo',$sexo);

	$this->concatena($this->cnmd06_parentesco->generateList(null,'cod_parentesco ASC', null, '{n}.cnmd06_parentesco.cod_parentesco', '{n}.cnmd06_parentesco.denominacion'),'parentesco');

	$this->limpiar_lista();
	$this->Session->delete('promotor_ced');
	$this->Session->delete('promotor_deno');


	$cod_republica = $this->Session->read('CC_republica');
 	$cod_estado    = $this->Session->read('CC_estado');
 	$cod_municipio = $this->Session->read('CC_municipio');
 	$cod_parroquia = $this->Session->read('CC_parroquia');
    $cod_centro    = $this->Session->read('CC_centro');
    $cod_concejo   = $this->Session->read('CC_concejo');

	$sql="cod_republica='$cod_republica' and cod_estado='$cod_estado' and cod_municipio='$cod_municipio' and cod_parroquia='$cod_parroquia' and cod_centro='$cod_centro'";
    $vialidad=$this->cugd01_vialidad->generateList($sql,'cod_vialidad ASC', null, '{n}.cugd01_vialidad.cod_vialidad', '{n}.cugd01_vialidad.denominacion');
	$vialidad = $vialidad != null ? $vialidad : array();
	$this->concatena($vialidad, 'vialidad');

 }// fin index

function sesiones($var1=null,$var2=null){
	$this->layout="ajax";
	if($var1==1){
		$this->Session->write('promotor_ced',$var2);
	}else{
		$this->Session->write('promotor_deno',$var2);
	}

}






function seleccion_familia($var=null){
	$this->layout="ajax";
	$this->set('republica',$this->cugd01_republica->field('denominacion', $conditions ="cod_republica=".$this->Session->read('CC_republica'), $order ="cod_republica ASC"));
	$this->set('estado',$this->cugd01_estados->field('denominacion', $conditions ="cod_republica=".$this->Session->read('CC_republica')." and cod_estado=".$this->Session->read('CC_estado'), $order ="cod_estado ASC"));
	$this->set('municipio',$this->cugd01_municipios->field('denominacion', $conditions ="cod_republica=".$this->Session->read('CC_republica')." and cod_estado=".$this->Session->read('CC_estado')." and cod_municipio=".$this->Session->read('CC_municipio'), $order ="cod_municipio ASC"));
	$this->set('parroquia',$this->cugd01_parroquias->field('denominacion', $conditions ="cod_republica=".$this->Session->read('CC_republica')." and cod_estado=".$this->Session->read('CC_estado')." and cod_municipio=".$this->Session->read('CC_municipio')." and cod_parroquia=".$this->Session->read('CC_parroquia'), $order ="cod_parroquia ASC"));

    $condicion_dir_sup = "";
    $dir_sup=$this->cugd02_direccionsuperior->generateList($condicion_dir_sup,'denominacion ASC', null, '{n}.cugd02_direccionsuperior.cod_dir_superior', '{n}.cugd02_direccionsuperior.denominacion');
	$dir_sup = $dir_sup != null ? $dir_sup : array();
	$this->concatena($dir_sup, 'dir_superior');

	$conditions  = "     cod_republica=".$this->Session->read('CC_republica')." and cod_estado=".$this->Session->read('CC_estado')." and cod_municipio=".$this->Session->read('CC_municipio')." and cod_parroquia=".$this->Session->read('CC_parroquia')." ";
 	$conditions .= " and cod_centro=".$this->Session->read('CC_centro')." and cod_concejo=".$this->Session->read('CC_concejo')."";
    $dir_concejo=$this->ccnd01_concejo_comunal->generateList($conditions,'denominacion ASC', null, '{n}.ccnd01_concejo_comunal.cod_concejo', '{n}.ccnd01_concejo_comunal.denominacion');
	$dir_concejo = $dir_concejo != null ? $dir_concejo : array();
	$this->concatena($dir_concejo, 'concejo');

	$denominacion = $this->ccnd01_concejo_comunal->field('denominacion', $conditions , null);
	$this->set('seleccion_concejo', $this->Session->read('CC_concejo'));
	$this->set('denominacion_concejo', $denominacion);



	$condicion_cod_tipo = "";
    $dir_cod_tipo=$this->ccnd01_tipo_directivo->generateList($condicion_cod_tipo,'denominacion ASC', null, '{n}.ccnd01_tipo_directivo.cod_tipo', '{n}.ccnd01_tipo_directivo.denominacion');
	$dir_cod_tipo = $dir_cod_tipo != null ? $dir_cod_tipo : array();
	$this->concatena($dir_cod_tipo, 'cod_tipo');

	$sql="select * from cugd01_centros_poblados where cod_republica=".$this->Session->read('CC_republica')." and cod_estado=".$this->Session->read('CC_estado')." and cod_municipio=".$this->Session->read('CC_municipio')." and cod_parroquia=".$this->Session->read('CC_parroquia')." and cod_centro=".$this->Session->read('CC_centro');
	$datos=$this->cugd01_parroquias->execute($sql);
	$this->set('datos',$datos);

	$familia=$this->ccnd03_censo_familias->generateList($conditions,'numero_familia ASC', null, '{n}.ccnd03_censo_familias.numero_familia','{n}.ccnd03_censo_familias.numero_familia');
	if($familia!=null){
//		$this->concatenaN($familia,'familia');
		$this->concatena_familia($familia,'familia');
//		$this->set('familia',mascara_tres($familia));
	}else{
		$this->set('familia',array());
	}

	$nacionalidad= array('V'=>'Venezolana','E'=>'Extranjero');
	$this->set('nacionalidad',$nacionalidad);

	$sexo= array('M'=>'Masculino','F'=>'Femenino');
	$this->set('sexo',$sexo);

	$this->concatena($this->cnmd06_parentesco->generateList(null,'cod_parentesco ASC', null, '{n}.cnmd06_parentesco.cod_parentesco', '{n}.cnmd06_parentesco.denominacion'),'parentesco');


	////////////////////aqui es donde verifico////////////////////
	if($var!=''){
		if($var!='agregar'){
			$this->set('no_agregar','no_agregar');
			$this->Session->delete('promotor_ced');
			$this->Session->delete('promotor_deno');


			$paren=$this->cnmd06_parentesco->findAll();
			$this->set('paren',$paren);

			$conditions.=" and numero_familia=".$var;
			$datos1=$this->ccnd03_censo_familias->execute("select * from ccnd03_censo_familias where ".$conditions." order by numero_familia desc limit 1");
			$datos2=$this->ccnd03_censo_miembros->execute("select * from ccnd03_censo_miembros where ".$conditions." order by miembro_numero asc ");


			$ver=$this->ccnd03_censo_familias->execute("select * from ccnd03_censo_miembros where ".$conditions." order by miembro_numero desc limit 1");
			if($ver!=null)
				$miembro_numero=$ver[0][0]['miembro_numero']+1;
			else
				$miembro_numero=1;

			$this->set('miembro_numero',$miembro_numero);
			$this->set('perso',$datos1);
			$this->set('fami',$datos2);

			$this->set('deno_vialidad',$this->cugd01_vialidad->field('denominacion', $conditions ="cod_republica=".$this->Session->read('CC_republica')." and cod_estado=".$this->Session->read('CC_estado')." and cod_municipio=".$this->Session->read('CC_municipio')." and cod_parroquia=".$this->Session->read('CC_parroquia')." and cod_centro=".$this->Session->read('CC_centro')." and cod_vialidad=".$datos1[0][0]['cod_calle'], $order ="cod_vialidad ASC"));

		}else{
			$this->set('agregar','agregar');
			/////////////////////////////////////////////////

			$ver=$this->ccnd03_censo_familias->execute("select * from ccnd03_censo_familias where ".$conditions." order by numero_familia desc limit 1");
			if($ver!=null)
				$num_familia=$ver[0][0]['numero_familia']+1;
			else
				$num_familia=1;
			$this->set('numero_familia',$num_familia);

			////////////////////////////////////////////////

			$this->set('linea',1);

		}
	}else{
		$this->set('agregar','agregar');
		/////////////////////////////////////////////////

		$ver=$this->ccnd03_censo_familias->execute("select * from ccnd03_censo_familias where ".$conditions." order by numero_familia desc limit 1");
		if($ver!=null)
			$num_familia=$ver[0][0]['numero_familia']+1;
		else
			$num_familia=1;
		$this->set('numero_familia',$num_familia);

		////////////////////////////////////////////////

		$this->set('linea',1);

	}
	$this->limpiar_lista();


	$cod_republica = $this->Session->read('CC_republica');
 	$cod_estado    = $this->Session->read('CC_estado');
 	$cod_municipio = $this->Session->read('CC_municipio');
 	$cod_parroquia = $this->Session->read('CC_parroquia');
    $cod_centro    = $this->Session->read('CC_centro');
    $cod_concejo   = $this->Session->read('CC_concejo');

	$sql="cod_republica='$cod_republica' and cod_estado='$cod_estado' and cod_municipio='$cod_municipio' and cod_parroquia='$cod_parroquia' and cod_centro='$cod_centro'";
    $vialidad=$this->cugd01_vialidad->generateList($sql,'cod_vialidad ASC', null, '{n}.cugd01_vialidad.cod_vialidad', '{n}.cugd01_vialidad.denominacion');
	$vialidad = $vialidad != null ? $vialidad : array();
	$this->concatena($vialidad, 'vialidad');
}//fin seleccion_familia



function select(){
	$this->layout="ajax";

	$cod_republica = $this->Session->read('CC_republica');
 	$cod_estado    = $this->Session->read('CC_estado');
 	$cod_municipio = $this->Session->read('CC_municipio');
 	$cod_parroquia = $this->Session->read('CC_parroquia');
    $cod_centro    = $this->Session->read('CC_centro');
    $cod_concejo   = $this->Session->read('CC_concejo');

	$sql="cod_republica='$cod_republica' and cod_estado='$cod_estado' and cod_municipio='$cod_municipio' and cod_parroquia='$cod_parroquia' and cod_centro='$cod_centro'";
    $vialidad=$this->cugd01_vialidad->generateList($sql,'cod_vialidad ASC', null, '{n}.cugd01_vialidad.cod_vialidad', '{n}.cugd01_vialidad.denominacion');
	$vialidad = $vialidad != null ? $vialidad : array();
	$this->concatena($vialidad, 'vialidad');
}



function sumar_ci($vector,$ci_principal){
        if(isset($vector) && is_array($vector)){
	foreach($vector as $vv){
	   //echo $vv."<br>";
	   //print_r($vv);
	   $ci=(int)$this->dv_ci2($vv);
	   $ci_principal=(int)$ci_principal;
	   if($ci_principal==$ci){
	      $vv2[]=$vv;
	   }/*else{
	   	  $vv2[]=$ci_principal;
	   }*/
	}
	if(isset($vv2)){
		rsort($vv2);
	}else{
		$vv2[]=$ci_principal;
		rsort($vv2);
	}

	return $this->dv_ci($vv2[0]);
}else{
   return 0;
}
}

function dv_ci($cedula){
	$c=stripos  ( $cedula  , '-' );
	if($c!=null){
	   $v=explode('-',$cedula);
	   $nueva_cedula=$v[0]."-".($v[1]+1);
	   return $nueva_cedula;
	}else{
	    return $cedula."-1";
	}
}

function dv_ci2($cedula){
	$c=stripos  ( $cedula  , '-' );
	if($c!=null){
	   $v=explode('-',$cedula);
	   $nueva_cedula=$v[0];
	   return $nueva_cedula;
	}else{
	    return $cedula;
	}
}


function edad($nacimiento){
//restamos los años (año actual - año cumpleaños)
$edad = date("Y") - ereg_replace("^(.{4}).*","\\1",$nacimiento);

//si pasamos de año, pero aún no cumplimos años, resta 1
if( date("m-d") < ereg_replace(".*(.{5})$","\\1",$nacimiento) )
 $edad--;

return $edad;
}


function agregar_grilla($var=null) {
	$this->layout="ajax";

	$paren=$this->cnmd06_parentesco->findAll();
	$this->set('paren',$paren);

	$linea=$this->data['ccnp01_directiva']['num_miembro'];

	if(empty($this->data['ccnp01_directiva']['parentesco_fami']) || empty($this->data['ccnp01_directiva']['ape_nom']) || empty($this->data['ccnp01_directiva']['fecha_nacimiento_fami']) || empty($this->data['ccnp01_directiva']['sexo_fami']) || empty($this->data['ccnp01_directiva']['trabaja']) || empty($this->data['ccnp01_directiva']['estudia']) || empty($this->data['ccnp01_directiva']['nacionalidad'])){
		$this->set('errorMessage', 'Debe ingresar los datos requeridos');
		if(!isset($_SESSION["contador"])){
 			$this->set('vacio','');
 		}
		return;
	}else if(empty($this->data['ccnp01_directiva']['cedula_fami']) && $this->edad($this->Cfecha($this->data['ccnp01_directiva']['fecha_nacimiento_fami'],"A-M-D"))>=15){
		$this->set('errorMessage', 'Debe ingresar la cedula del familiar');
		if(!isset($_SESSION["contador"])){
 			$this->set('vacio','');
 		}
		return;
	}

/*//aqui se hace lo de la cedula del familiar cuando es menor pero el problema es que aqui no hay una cedula principal
 *
	else if(empty($this->data['ccnp01_directiva']['cedula_fami']) && $this->edad($this->Cfecha($this->data['ccnp01_directiva']['fecha_nacimiento_fami'],"A-M-D"))>=15){
		$this->set('errorMessage', 'Debe ingresar la cedula del familiar');
		if(!isset($_SESSION["contador"])){
 			$this->set('vacio','');
 		}
		return;
	}

	 $ci_principal=$this->data['ccnp01_directiva']['cedula'];
	if(empty($this->data['casp01']['cedula_fami']) && isset($_SESSION["items1"])){
		$vector=array();
		foreach($_SESSION ["items1"] as $xx){
			$vector[]=$xx[1];
		}
		$cedula=$this->sumar_ci($vector,$ci_principal);
	}else if(empty($this->data['casp01']['cedula_fami']) && !isset($_SESSION["items1"])){
		 $cedula=$this->dv_ci($ci_principal);

	}else if(!empty($this->data['casp01']['cedula_fami'])){
		$cedula=$this->data['casp01']['cedula_fami'];
	}

*/

	    $parentesco=$this->data['ccnp01_directiva']['parentesco_fami'];
	    $nacionalidad=$this->data['ccnp01_directiva']['nacionalidad'];
	    $cedula=$this->data['ccnp01_directiva']['cedula_fami'];
	    $ape_nom=$this->data['ccnp01_directiva']['ape_nom'];
	    $fecha=$this->data['ccnp01_directiva']['fecha_nacimiento_fami'];
	    $sexo_fami=$this->data['ccnp01_directiva']['sexo_fami'];
	    $trabaja=$this->data['ccnp01_directiva']['trabaja'];
	    $estudia=$this->data['ccnp01_directiva']['estudia'];


	if(isset($_SESSION["contador"])){
        $_SESSION["contador"]=$_SESSION["contador"]+1;
	}else{
		$_SESSION["contador"]=1;
	}

	if(isset($var) && !empty($var)){

			$cod[0]=$linea;
			$cod[1]=$parentesco;
			$cod[2]=$nacionalidad;
			$cod[3]=$cedula;
			$cod[4]=$ape_nom;
			$cod[5]=$fecha;
			$cod[6]=$sexo_fami;
			$cod[7]=$trabaja;
			$cod[8]=$estudia;

		    if(isset($_SESSION["i"])){
				$i=$this->Session->read("i")+1;
				$this->Session->write("i",$i);
	   		 }else{
			   $this->Session->write("i",0);
				$i=0;
			}
        switch($var){
        	case 'normal':
				     $vec[$i][0]=$linea;
				     $vec[$i][1]=$parentesco;
				     $vec[$i][2]=$nacionalidad;
				     $vec[$i][3]=$cedula;
					 $vec[$i][4]=$ape_nom;
					 $vec[$i][5]=$fecha;
					 $vec[$i][6]=$sexo_fami;
					 $vec[$i][7]=$trabaja;
					 $vec[$i][8]=$estudia;
					 //echo $vec[$i][6];
					 $vec[$i]["id"]=$i;
					if(isset($_SESSION["items1"])){
						foreach($_SESSION["items1"] as $codi){
//							echo $codi[0].$cod[0];
            	           if($codi[3]==$cod[3]){
                              $est=true;
                              break;
            	          }else{
            	          	 $est=false;
            	          }
                        }//fin foreach
                        if($est==true){
                           //	echo "no";
            	          	$i=$this->Session->read("i")-1;
				            $this->Session->write("i",$i);
				            $this->set('errorMessage', 'Esta persona ya existe en la lista');
                        }else{
                        	$_SESSION["items1"]=$_SESSION["items1"]+$vec;
                          //  echo "si";
                        }
					 }else{
						$_SESSION["items1"]=$vec;
					 }

        	break;

        }//fin switch
		}//

		echo "<script>";
			echo "document.getElementById('agregar').disabled=false;";
		echo "</script>";

//pr($_SESSION["items1"]);
}//fin funcu¡ions



function limpiar_lista () {
	$this->layout = "ajax";
	echo "<script>document.getElementById('num_miembro').value=1;</script>";

	$this->Session->delete("items1");
	$this->Session->delete("i");
	$this->Session->delete("contador");
}



function eliminar_items ($id) {
	$this->layout = "ajax";
	$paren=$this->cnmd06_parentesco->findAll();
	$this->set('paren',$paren);
	$_SESSION["items1"][$id]=null;
	$NL=1;
	$codigos1=array();
	foreach($_SESSION ["items1"] as $codigos){
       if($codigos[$NL]!=null){

       		$codigos1[$NL][0]=$NL;
       		$codigos1[$NL][1]=$codigos[1];
       		$codigos1[$NL][2]=$codigos[2];
       		$codigos1[$NL][3]=$codigos[3];
       		$codigos1[$NL][4]=$codigos[4];
       		$codigos1[$NL][5]=$codigos[5];
       		$codigos1[$NL][6]=$codigos[6];
       		$codigos1[$NL][7]=$codigos[7];
       		$codigos1[$NL][8]=$codigos[8];
       		$codigos1[$NL]['id']=$NL;
			$NL++;
       }

	}
	//print_r($codigos1);
    $_SESSION["contador"]=$_SESSION["contador"]-1;
    $_SESSION["items1"]=array();
    $_SESSION["items1"]=$codigos1;
    //print_r($_SESSION["items1"]);
}



function guardar($nomina=null,$cargo=null,$ficha=null) {
 	$this->layout = "ajax";
 	$cod_republica = $this->Session->read('CC_republica');
 	$cod_estado    = $this->Session->read('CC_estado');
 	$cod_municipio = $this->Session->read('CC_municipio');
 	$cod_parroquia = $this->Session->read('CC_parroquia');
    $cod_centro    = $this->Session->read('CC_centro');
    $cod_concejo   = $this->Session->read('CC_concejo');

//	pr($this->data);

	if(empty($this->data['ccnp01_directiva']['cedula_promotor'])){
		$this->set('errorMessage', 'Debe ingresar la cédula del promotor');

	}else if(empty($this->data['ccnp01_directiva']['nombre_promotor'])){
		$this->set('errorMessage', 'Debe ingresar el nombre y apellido del promotor');

	}else if(empty($this->data['ccnp01_directiva']['av_calle'])){
		$this->set('errorMessage', 'ingrese la avenida, calle, callejon o carretera donde reside la familia');

	}else if(empty($this->data['ccnp01_directiva']['num_casa'])){
		$this->set('errorMessage', 'ingrese el número de la casa');

	}else if(empty($this->data['ccnp01_directiva']['telefono'])){
		$this->set('errorMessage', 'ingrese el telefono');

	}else if(empty($this->data['ccnp01_directiva']['tipo_vivienda'])){
		$this->set('errorMessage', 'seleccione el tipo de vivienda');

	}else if(empty($this->data['ccnp01_directiva']['tenencia_vivienda'])){
		$this->set('errorMessage', 'seleccione la tenencia de la vivienda');

	}else if(empty($this->data['ccnp01_directiva']['condicion_vivienda'])){
		$this->set('errorMessage', 'seleccione la condición de la vivienda');

	}else if(empty($this->data['ccnp01_directiva']['numero_ambientes'])){
		$this->set('errorMessage', 'ingrese el número de ambientes');

	}else if(empty($this->data['ccnp01_directiva']['anos_residencia'])){
		$this->set('errorMessage', 'ingrese los años de residencia');

	}else if(empty($this->data['ccnp01_directiva']['cod_mision'])){
		$this->set('errorMessage', 'seleccione una misión');

	}else if(empty($this->data['ccnp01_directiva']['ingreso_familiar'])){
		$this->set('errorMessage', 'especifique el ingreso familiar');

	}else if(empty($this->data['ccnp01_directiva']['num_habitantes'])){
		$this->set('errorMessage', 'ingrese el número de habitantes');

	}else if(!isset($_SESSION ["items1"]) || $_SESSION ["items1"]==null || $_SESSION ["items1"]==array()){
		$this->set('errorMessage', 'Debe ingresar el grupo familiar');
	}else{

		$cod_familia=$this->data['ccnp01_directiva']['cod_familia'];
		$cedula_promotor=$this->data['ccnp01_directiva']['cedula_promotor'];
		$nombre_promotor=$this->data['ccnp01_directiva']['nombre_promotor'];
		$av_calle=$this->data['ccnp01_directiva']['av_calle'];
		$num_casa=$this->data['ccnp01_directiva']['num_casa'];
		$telefono=$this->data['ccnp01_directiva']['telefono'];
		$tipo_vivienda=$this->data['ccnp01_directiva']['tipo_vivienda'];
		$tenencia_vivienda=$this->data['ccnp01_directiva']['tenencia_vivienda'];
		$condicion_vivienda=$this->data['ccnp01_directiva']['condicion_vivienda'];
		$numero_ambientes=$this->data['ccnp01_directiva']['numero_ambientes'];
		$anos_residencia=$this->data['ccnp01_directiva']['anos_residencia'];
		$cod_mision=$this->data['ccnp01_directiva']['cod_mision'];
		$ingreso_familiar=$this->Formato1($this->data['ccnp01_directiva']['ingreso_familiar']);
		$num_habitantes=$this->data['ccnp01_directiva']['num_habitantes'];

		 if(empty($this->data['ccnp01_directiva']['numero_familias'])){
			$numero_familias=0;
		 }else{
		 	$numero_familias=$this->data['ccnp01_directiva']['numero_familias'];
		 }

		 if(empty($this->data['ccnp01_directiva']['monto_alquiler'])){
			$monto_alquiler=0;
		 }else{
		 	$monto_alquiler=$this->Formato1($this->data['ccnp01_directiva']['monto_alquiler']);
		 }

		 if(empty($this->data['ccnp01_directiva']['adultos'])){
			$adultos=0;
		 }else{
		 	$adultos=$this->data['ccnp01_directiva']['adultos'];
		 }

		 if(empty($this->data['ccnp01_directiva']['discapacitados'])){
			$discapacitados=0;
		 }else{
		 	$discapacitados=$this->data['ccnp01_directiva']['discapacitados'];
		 }

		 if(empty($this->data['ccnp01_directiva']['enfermos_controlados'])){
			$enfermos_controlados=0;
		 }else{
		 	$enfermos_controlados=$this->data['ccnp01_directiva']['enfermos_controlados'];
		 }

		 if(empty($this->data['ccnp01_directiva']['enfermos_terminales'])){
			$enfermos_terminales=0;
		 }else{
		 	$enfermos_terminales=$this->data['ccnp01_directiva']['enfermos_terminales'];
		 }


		 $sql = "BEGIN;INSERT INTO ccnd03_censo_familias VALUES ('$cod_republica', '$cod_estado', '$cod_municipio', '$cod_parroquia', '$cod_centro', '$cod_concejo','$cod_familia','$av_calle','$num_casa','$telefono','$tipo_vivienda','$tenencia_vivienda','$condicion_vivienda','$numero_ambientes','$anos_residencia','$cod_mision','$ingreso_familiar','$numero_familias','$monto_alquiler','$num_habitantes','$adultos','$discapacitados','$enfermos_controlados','$enfermos_terminales','$cedula_promotor','$nombre_promotor')";
	   	 $sw=$this->ccnd03_censo_familias->execute($sql);
				foreach($_SESSION ["items1"] as $guardar){
					if($guardar!=null){
						if($guardar[3]!=''){
							$campos="(cod_republica,cod_estado,cod_municipio,cod_parroquia,cod_centro,cod_concejo,numero_familia,miembro_numero,cod_miembro,nacionalidad,cedula_identidad,apellidos_nombres,fecha_nacimiento,sexo,trabaja,estudia)";
							$sql_insert = "INSERT INTO ccnd03_censo_miembros ".$campos." VALUES('$cod_republica', '$cod_estado', '$cod_municipio', '$cod_parroquia', '$cod_centro', '$cod_concejo','$cod_familia','$guardar[0]', '$guardar[1]', '$guardar[2]', '$guardar[3]','$guardar[4]','$guardar[5]','$guardar[6]','$guardar[7]','$guardar[8]')";
						}else{
							$campos="(cod_republica,cod_estado,cod_municipio,cod_parroquia,cod_centro,cod_concejo,numero_familia,miembro_numero,cod_miembro,nacionalidad,apellidos_nombres,fecha_nacimiento,sexo,trabaja,estudia)";
							$sql_insert = "INSERT INTO ccnd03_censo_miembros ".$campos." VALUES('$cod_republica', '$cod_estado', '$cod_municipio', '$cod_parroquia', '$cod_centro', '$cod_concejo','$cod_familia','$guardar[0]', '$guardar[1]', '$guardar[2]','$guardar[4]','$guardar[5]','$guardar[6]','$guardar[7]','$guardar[8]')";
						}
							$sw2 = $this->ccnd03_censo_miembros->execute($sql_insert);
					}
			     }
			if($sw>1 && $sw2>1){
				$this->ccnd03_censo_familias->execute("COMMIT");
				$this->set('Message_existe', 'REGISTRO EXITOSO');
				echo" <script> ver_documento('/ccnp03_censo_poblacional/seleccion_familia/".$cod_familia."','principal'); </script>";
	   		}else{
	   			$this->ccnd03_censo_familias->execute("ROLLBACK");
	   			$this->set('errorMessage', 'INSERCI&Oacute;N FALLIDA DE LOS DATOS');
	   		}

	}

 }// fin guardar


function modificar($var=null,$pagina=null){
	$this->layout = "ajax";
	if(isset($pagina)){
		$this->set('pagina',$pagina);
	}

	$this->set('republica',$this->cugd01_republica->field('denominacion', $conditions ="cod_republica=".$this->Session->read('CC_republica'), $order ="cod_republica ASC"));
	$this->set('estado',$this->cugd01_estados->field('denominacion', $conditions ="cod_republica=".$this->Session->read('CC_republica')." and cod_estado=".$this->Session->read('CC_estado'), $order ="cod_estado ASC"));
	$this->set('municipio',$this->cugd01_municipios->field('denominacion', $conditions ="cod_republica=".$this->Session->read('CC_republica')." and cod_estado=".$this->Session->read('CC_estado')." and cod_municipio=".$this->Session->read('CC_municipio'), $order ="cod_municipio ASC"));
	$this->set('parroquia',$this->cugd01_parroquias->field('denominacion', $conditions ="cod_republica=".$this->Session->read('CC_republica')." and cod_estado=".$this->Session->read('CC_estado')." and cod_municipio=".$this->Session->read('CC_municipio')." and cod_parroquia=".$this->Session->read('CC_parroquia'), $order ="cod_parroquia ASC"));

    $condicion_dir_sup = "";
    $dir_sup=$this->cugd02_direccionsuperior->generateList($condicion_dir_sup,'denominacion ASC', null, '{n}.cugd02_direccionsuperior.cod_dir_superior', '{n}.cugd02_direccionsuperior.denominacion');
	$dir_sup = $dir_sup != null ? $dir_sup : array();
	$this->concatena($dir_sup, 'dir_superior');

	$conditions  = "     cod_republica=".$this->Session->read('CC_republica')." and cod_estado=".$this->Session->read('CC_estado')." and cod_municipio=".$this->Session->read('CC_municipio')." and cod_parroquia=".$this->Session->read('CC_parroquia')." ";
 	$conditions .= " and cod_centro=".$this->Session->read('CC_centro')." and cod_concejo=".$this->Session->read('CC_concejo')."";
    $dir_concejo=$this->ccnd01_concejo_comunal->generateList($conditions,'denominacion ASC', null, '{n}.ccnd01_concejo_comunal.cod_concejo', '{n}.ccnd01_concejo_comunal.denominacion');
	$dir_concejo = $dir_concejo != null ? $dir_concejo : array();
	$this->concatena($dir_concejo, 'concejo');

	$denominacion = $this->ccnd01_concejo_comunal->field('denominacion', $conditions , null);
	$this->set('seleccion_concejo', $this->Session->read('CC_concejo'));
	$this->set('denominacion_concejo', $denominacion);



	$condicion_cod_tipo = "";
    $dir_cod_tipo=$this->ccnd01_tipo_directivo->generateList($condicion_cod_tipo,'denominacion ASC', null, '{n}.ccnd01_tipo_directivo.cod_tipo', '{n}.ccnd01_tipo_directivo.denominacion');
	$dir_cod_tipo = $dir_cod_tipo != null ? $dir_cod_tipo : array();
	$this->concatena($dir_cod_tipo, 'cod_tipo');

	$sql="select * from cugd01_centros_poblados where cod_republica=".$this->Session->read('CC_republica')." and cod_estado=".$this->Session->read('CC_estado')." and cod_municipio=".$this->Session->read('CC_municipio')." and cod_parroquia=".$this->Session->read('CC_parroquia')." and cod_centro=".$this->Session->read('CC_centro');
	$datos=$this->cugd01_parroquias->execute($sql);
	$this->set('datos',$datos);

	$familia=$this->ccnd03_censo_familias->generateList($conditions,'numero_familia ASC', null, '{n}.ccnd03_censo_familias.numero_familia');
	if($familia!=null){
		$this->concatenaN($familia,'familia');
	}else{
		$this->set('familia',array());
	}

	$nacionalidad= array('V'=>'Venezolana','E'=>'Extranjero');
	$this->set('nacionalidad',$nacionalidad);

	$sexo= array('M'=>'Masculino','F'=>'Femenino');
	$this->set('sexo',$sexo);

	$this->concatena($this->cnmd06_parentesco->generateList(null,'cod_parentesco ASC', null, '{n}.cnmd06_parentesco.cod_parentesco', '{n}.cnmd06_parentesco.denominacion'),'parentesco');

    $paren=$this->cnmd06_parentesco->findAll();
	$this->set('paren',$paren);


  $estado_conservacion= array('E'=>'Excelente',
		                      'B'=>'Buena',
		                      'R'=>'Regular',
		                      'M'=>'Mala'
		                       );


  $tenencia_vivencia= array('1'=>'Propia',
		                    '2'=>'Alquilada',
		                    '3'=>'De un familiar',
		                    '4'=>'Al cuidado',
		                    '5'=>'Hipotecada',
		                    '6'=>'Invadida'
		                  );

  $vivienda= array('1'=>'Quinta',
                   '2'=>'Casa/Quinta',
                   '3'=>'Casa manposteria',
                   '4'=>'apartamento',
                   '5'=>'Vivienda popular',
                   '6'=>'Rancho'
                  );

  $misiones = array("1"=>'Ninguna',
				    "2"=>'Robinsón I',
				    "3"=>'Robinsón II',
				    "4"=>'Ribas',
				    "5"=>'Sucre',
				    "6"=>'Negra hipolita',
				    "7"=>'José Gregorio Hernandez',
				    "8"=>'Barrio adentro',
				    "9"=>'Mercal',
				    "10"=>'Arbol',
				    "11"=>'Ciencia',
				    "12"=>'Miranda',
				    "13"=>'Guacaipuro',
				    "14"=>'Piar',
				    "15"=>'Vuelvan caras',
				    "16"=>'Identidad',
				    "17"=>'Che Guevara',
				    "18"=>'Cultura',
				    "19"=>'Esperanza',
				    "20"=>'Habitat',
				    "21"=>'Madre del barrio',
				    "22"=>'Milagro',
				    "23"=>'Niños y niñas del barrio',
				    "24"=>'Zamora'
                    );



			$this->set('lista_estado_conservacion',  $estado_conservacion);
			$this->set('lista_vivienda',             $vivienda);
			$this->set('lista_misiones',             $misiones);
			$this->set('lista_tenencia_vivencia',    $tenencia_vivencia);

			$conditions.=" and numero_familia=".$var;
			$datos1=$this->ccnd03_censo_familias->execute("select * from ccnd03_censo_familias where ".$conditions." order by numero_familia desc limit 1");
			$datos2=$this->ccnd03_censo_miembros->execute("select * from ccnd03_censo_miembros where ".$conditions." order by miembro_numero asc ");


			$ver=$this->ccnd03_censo_familias->execute("select * from ccnd03_censo_miembros where ".$conditions." order by miembro_numero desc limit 1");
			if($ver!=null)
				$miembro_numero=$ver[0][0]['miembro_numero']+1;
			else
				$miembro_numero=1;

			$this->set('miembro_numero',$miembro_numero);
			$this->set('perso',$datos1);
			$this->set('fami',$datos2);
			$this->set('Message_existe', 'proceda a modificar los datos');

			$cod_republica = $this->Session->read('CC_republica');
		 	$cod_estado    = $this->Session->read('CC_estado');
		 	$cod_municipio = $this->Session->read('CC_municipio');
		 	$cod_parroquia = $this->Session->read('CC_parroquia');
		    $cod_centro    = $this->Session->read('CC_centro');
		    $cod_concejo   = $this->Session->read('CC_concejo');

			$sql="cod_republica='$cod_republica' and cod_estado='$cod_estado' and cod_municipio='$cod_municipio' and cod_parroquia='$cod_parroquia' and cod_centro='$cod_centro'";
		    $vialidad=$this->cugd01_vialidad->generateList($sql,'cod_vialidad ASC', null, '{n}.cugd01_vialidad.cod_vialidad', '{n}.cugd01_vialidad.denominacion');
			$vialidad = $vialidad != null ? $vialidad : array();
			$this->concatena($vialidad, 'vialidad');

}



  function guardar_modificar($cod_familia=null,$pagina=null){
 		$this->layout = "ajax";
	 	$cod_republica = $this->Session->read('CC_republica');
	 	$cod_estado    = $this->Session->read('CC_estado');
	 	$cod_municipio = $this->Session->read('CC_municipio');
	 	$cod_parroquia = $this->Session->read('CC_parroquia');
	    $cod_centro    = $this->Session->read('CC_centro');
	    $cod_concejo   = $this->Session->read('CC_concejo');

//		pr($this->data);

		if(empty($this->data['ccnp01_directiva']['cedula_promotor'])){
			$this->set('errorMessage', 'Debe ingresar la cédula del promotor');

		}else if(empty($this->data['ccnp01_directiva']['nombre_promotor'])){
			$this->set('errorMessage', 'Debe ingresar el nombre y apellido del promotor');

		}else if(empty($this->data['ccnp01_directiva']['av_calle'])){
			$this->set('errorMessage', 'ingrese la avenida, calle, callejon o carretera donde reside la familia');

		}else if(empty($this->data['ccnp01_directiva']['num_casa'])){
			$this->set('errorMessage', 'ingrese el número de la casa');

		}else if(empty($this->data['ccnp01_directiva']['telefono'])){
			$this->set('errorMessage', 'ingrese el telefono');

		}else if(empty($this->data['ccnp01_directiva']['tipo_vivienda'])){
			$this->set('errorMessage', 'seleccione el tipo de vivienda');

		}else if(empty($this->data['ccnp01_directiva']['tenencia_vivienda'])){
			$this->set('errorMessage', 'seleccione la tenencia de la vivienda');

		}else if(empty($this->data['ccnp01_directiva']['condicion_vivienda'])){
			$this->set('errorMessage', 'seleccione la condición de la vivienda');

		}else if(empty($this->data['ccnp01_directiva']['numero_ambientes'])){
			$this->set('errorMessage', 'ingrese el número de ambientes');

		}else if(empty($this->data['ccnp01_directiva']['anos_residencia'])){
			$this->set('errorMessage', 'ingrese los años de residencia');

		}else if(empty($this->data['ccnp01_directiva']['cod_mision'])){
			$this->set('errorMessage', 'seleccione una misión');

		}else if(empty($this->data['ccnp01_directiva']['ingreso_familiar'])){
			$this->set('errorMessage', 'especifique el ingreso familiar');

		}else if(empty($this->data['ccnp01_directiva']['num_habitantes'])){
			$this->set('errorMessage', 'ingrese el número de habitantes');

		}else{

//			$cod_familia=$this->data['ccnp01_directiva']['cod_familia'];
			$cedula_promotor=$this->data['ccnp01_directiva']['cedula_promotor'];
			$nombre_promotor=$this->data['ccnp01_directiva']['nombre_promotor'];
			$av_calle=$this->data['ccnp01_directiva']['av_calle'];
			$num_casa=$this->data['ccnp01_directiva']['num_casa'];
			$telefono=$this->data['ccnp01_directiva']['telefono'];
			$tipo_vivienda=$this->data['ccnp01_directiva']['tipo_vivienda'];
			$tenencia_vivienda=$this->data['ccnp01_directiva']['tenencia_vivienda'];
			$condicion_vivienda=$this->data['ccnp01_directiva']['condicion_vivienda'];
			$numero_ambientes=$this->data['ccnp01_directiva']['numero_ambientes'];
			$anos_residencia=$this->data['ccnp01_directiva']['anos_residencia'];
			$cod_mision=$this->data['ccnp01_directiva']['cod_mision'];
			$ingreso_familiar=$this->Formato1($this->data['ccnp01_directiva']['ingreso_familiar']);
			$num_habitantes=$this->data['ccnp01_directiva']['num_habitantes'];

			 if(empty($this->data['ccnp01_directiva']['numero_familias'])){
				$numero_familias=0;
			 }else{
			 	$numero_familias=$this->data['ccnp01_directiva']['numero_familias'];
			 }

			 if(empty($this->data['ccnp01_directiva']['monto_alquiler'])){
				$monto_alquiler=0;
			 }else{
			 	$monto_alquiler=$this->Formato1($this->data['ccnp01_directiva']['monto_alquiler']);
			 }

			 if(empty($this->data['ccnp01_directiva']['adultos'])){
				$adultos=0;
			 }else{
			 	$adultos=$this->data['ccnp01_directiva']['adultos'];
			 }

			 if(empty($this->data['ccnp01_directiva']['discapacitados'])){
				$discapacitados=0;
			 }else{
			 	$discapacitados=$this->data['ccnp01_directiva']['discapacitados'];
			 }

			 if(empty($this->data['ccnp01_directiva']['enfermos_controlados'])){
				$enfermos_controlados=0;
			 }else{
			 	$enfermos_controlados=$this->data['ccnp01_directiva']['enfermos_controlados'];
			 }

			 if(empty($this->data['ccnp01_directiva']['enfermos_terminales'])){
				$enfermos_terminales=0;
			 }else{
			 	$enfermos_terminales=$this->data['ccnp01_directiva']['enfermos_terminales'];
			 }

			 $conditions  = "cod_republica=".$this->Session->read('CC_republica')." and cod_estado=".$this->Session->read('CC_estado')." and cod_municipio=".$this->Session->read('CC_municipio')." and cod_parroquia=".$this->Session->read('CC_parroquia')." and cod_centro=".$this->Session->read('CC_centro')." and cod_concejo=".$this->Session->read('CC_concejo');
   			 $conditions.=" and numero_familia=".$cod_familia;
			 $update="update ccnd03_censo_familias set cod_calle='$av_calle',numero_casa='$num_casa',telefonos='$telefono',cod_vivienda='$tipo_vivienda',cod_tenencia='$tenencia_vivienda',condicion_vivienda='$condicion_vivienda',numero_ambientes='$numero_ambientes',tiempo_residencia='$anos_residencia',cod_mision='$cod_mision',ingresos_familiar='$ingreso_familiar',numero_familias='$numero_familias',monto_alquiler_hipoteca='$monto_alquiler',numero_habitantes='$num_habitantes',adultos_mayores='$adultos',discapacitados='$discapacitados',enfermos_controlados='$enfermos_controlados',enfermos_terminales='$enfermos_terminales',cedula_promotor='$cedula_promotor',nombres_apellidos='$nombre_promotor' where ".$conditions;
		   	 $sw=$this->ccnd03_censo_familias->execute($update);

				if($sw>1){
					$this->set('Message_existe', 'los datos fueron modificados con exito');
					if(isset($pagina)){
						echo" <script> ver_documento('/ccnp03_censo_poblacional/consulta/".$pagina."','principal'); </script>";
					}else{
						echo" <script> ver_documento('/ccnp03_censo_poblacional/seleccion_familia/".$cod_familia."','principal'); </script>";
					}

		   		}else{
		   			$this->ccnd03_censo_familias->execute("ROLLBACK");
		   			$this->set('errorMessage', 'MODIFICACIÓN FALLIDA DE LOS DATOS');
		   		}

		}

 }//guardar modificar


 function guardar_familiar($cod_familia=null){
	$this->layout = "ajax";
	$cod_republica = $this->Session->read('CC_republica');
 	$cod_estado    = $this->Session->read('CC_estado');
 	$cod_municipio = $this->Session->read('CC_municipio');
 	$cod_parroquia = $this->Session->read('CC_parroquia');
    $cod_centro    = $this->Session->read('CC_centro');
    $cod_concejo   = $this->Session->read('CC_concejo');


	$conditions  = "     cod_republica=".$this->Session->read('CC_republica')." and cod_estado=".$this->Session->read('CC_estado')." and cod_municipio=".$this->Session->read('CC_municipio')." and cod_parroquia=".$this->Session->read('CC_parroquia')." and cod_centro=".$this->Session->read('CC_centro')." and cod_concejo=".$this->Session->read('CC_concejo');
    $conditions.=" and numero_familia=".$cod_familia;

    $paren=$this->cnmd06_parentesco->findAll();
	$this->set('paren',$paren);

	$linea=$this->data['ccnp01_directiva']['num_miembro'];

	if(empty($this->data['ccnp01_directiva']['parentesco_fami']) || empty($this->data['ccnp01_directiva']['ape_nom']) || empty($this->data['ccnp01_directiva']['fecha_nacimiento_fami']) || empty($this->data['ccnp01_directiva']['sexo_fami']) || empty($this->data['ccnp01_directiva']['trabaja']) || empty($this->data['ccnp01_directiva']['estudia']) || empty($this->data['ccnp01_directiva']['nacionalidad'])){
		$this->set('errorMessage', 'Debe ingresar los datos requeridos');
		$sql_1="SELECT * from ccnd03_censo_miembros where ".$conditions." and 1=1 order by miembro_numero asc";
		$result_1=$this->ccnd03_censo_miembros->execute($sql_1);
		$this->set('fami',$result_1);
		return;
	}else if(empty($this->data['ccnp01_directiva']['cedula_fami']) && $this->edad($this->Cfecha($this->data['ccnp01_directiva']['fecha_nacimiento_fami'],"A-M-D"))>=15){
		$this->set('errorMessage', 'Debe ingresar la cedula del familiar');
		if(!isset($_SESSION["contador"])){
 			$this->set('vacio','');
 		}
 		$sql_1="SELECT * from ccnd03_censo_miembros where ".$conditions." and 1=1 order by miembro_numero asc";
		$result_1=$this->ccnd03_censo_miembros->execute($sql_1);
		$this->set('fami',$result_1);
		return;
	}

	    $parentesco=$this->data['ccnp01_directiva']['parentesco_fami'];
	    $nacionalidad=$this->data['ccnp01_directiva']['nacionalidad'];
	    $cedula=$this->data['ccnp01_directiva']['cedula_fami'];
	    $ape_nom=$this->data['ccnp01_directiva']['ape_nom'];
	    $fecha=$this->data['ccnp01_directiva']['fecha_nacimiento_fami'];
	    $sexo_fami=$this->data['ccnp01_directiva']['sexo_fami'];
	    $trabaja=$this->data['ccnp01_directiva']['trabaja'];
	    $estudia=$this->data['ccnp01_directiva']['estudia'];

		if(!empty($this->data['ccnp01_directiva']['cedula_fami'])){
	    	$verifica=$this->ccnd03_censo_miembros->FindCount($conditions." and cedula_identidad='$cedula'");
		}else{
			$verifica=0;
		}
		if($verifica==0){
			 if(empty($this->data['ccnp01_directiva']['cedula_fami'])){
				$campos="(cod_republica,cod_estado,cod_municipio,cod_parroquia,cod_centro,cod_concejo,numero_familia,miembro_numero,cod_miembro,nacionalidad,apellidos_nombres,fecha_nacimiento,sexo,trabaja,estudia)";
				$sql_insert = "INSERT INTO ccnd03_censo_miembros ".$campos." VALUES('$cod_republica', '$cod_estado', '$cod_municipio', '$cod_parroquia', '$cod_centro', '$cod_concejo','$cod_familia','$linea', '$parentesco', '$nacionalidad','$ape_nom','$fecha','$sexo_fami','$trabaja','$estudia')";
			 }else{
			 	$campos="(cod_republica,cod_estado,cod_municipio,cod_parroquia,cod_centro,cod_concejo,numero_familia,miembro_numero,cod_miembro,nacionalidad,cedula_identidad,apellidos_nombres,fecha_nacimiento,sexo,trabaja,estudia)";
				$sql_insert = "INSERT INTO ccnd03_censo_miembros ".$campos." VALUES('$cod_republica', '$cod_estado', '$cod_municipio', '$cod_parroquia', '$cod_centro', '$cod_concejo','$cod_familia','$linea', '$parentesco', '$nacionalidad', '$cedula','$ape_nom','$fecha','$sexo_fami','$trabaja','$estudia')";
			 }
			 $sw2 = $this->casd01_datos_familiares->execute($sql_insert);
			 if($sw2>1){
			 	$this->set('Message_existe', 'FAMILIAR AGREGADO CON EXITO');
			 }else{
			 	$this->set('errorMessage', 'ERROR EN LA INSERCI&Oacute;N DEL DATO');
			 }
		}else{
			$this->set('errorMessage', 'EL FAMILIAR QUE DESEA AGREGAR YA EXISTE EN LA LISTA');
		}


		$sql_1="SELECT * from ccnd03_censo_miembros where ".$conditions." and 1=1 order by miembro_numero asc";
		$result_1=$this->ccnd03_censo_miembros->execute($sql_1);
		$this->set('fami',$result_1);
//		pr($result_1);


}




 function modificar_familiar($cod_familia=null,$numero_miembro=null,$i=null){
 	 $this->layout = "ajax";
 	 $conditions  = "cod_republica=".$this->Session->read('CC_republica')." and cod_estado=".$this->Session->read('CC_estado')." and cod_municipio=".$this->Session->read('CC_municipio')." and cod_parroquia=".$this->Session->read('CC_parroquia')." and cod_centro=".$this->Session->read('CC_centro')." and cod_concejo=".$this->Session->read('CC_concejo');
     $conditions.=" and numero_familia=".$cod_familia." and miembro_numero=".$numero_miembro;

    $paren=$this->cnmd06_parentesco->findAll();
	$this->set('paren',$paren);
 	$sql_1="SELECT * from ccnd03_censo_miembros where ".$conditions;
	$result_1=$this->ccnd03_censo_miembros->execute($sql_1);
	$this->set('fami',$result_1);

	$nacionalidad= array('V'=>'Venezolana','E'=>'Extranjero');
	$this->set('nacionalidad',$nacionalidad);

	$sexo= array('M'=>'Masculino','F'=>'Femenino');
	$this->set('sexo',$sexo);

	$this->concatena($this->cnmd06_parentesco->generateList(null,'cod_parentesco ASC', null, '{n}.cnmd06_parentesco.cod_parentesco', '{n}.cnmd06_parentesco.denominacion'),'parentesco');

	$this->set('k',$i);


 }// fin modificar_items


function guardar_modificar_familiar($cod_familia=null,$numero_miembro=null,$i=null){
	$this->layout = "ajax";
//	pr($this->data);
	$paren=$this->cnmd06_parentesco->findAll();
	$this->set('paren',$paren);

	$conditions  = "cod_republica=".$this->Session->read('CC_republica')." and cod_estado=".$this->Session->read('CC_estado')." and cod_municipio=".$this->Session->read('CC_municipio')." and cod_parroquia=".$this->Session->read('CC_parroquia')." and cod_centro=".$this->Session->read('CC_centro')." and cod_concejo=".$this->Session->read('CC_concejo');
    $conditions.=" and numero_familia=".$cod_familia;

	if(empty($this->data['ccnp01_directiva']['parentesco_fami'.$i]) || empty($this->data['ccnp01_directiva']['ape_nom'.$i]) || empty($this->data['ccnp01_directiva']['fecha_nacimiento_fami'.$i]) || empty($this->data['ccnp01_directiva']['sexo_fami'.$i]) || empty($this->data['ccnp01_directiva']['trabaja'.$i]) || empty($this->data['ccnp01_directiva']['estudia'.$i]) || empty($this->data['ccnp01_directiva']['nacionalidad'.$i])){
		$this->set('errorMessage', 'Debe ingresar los datos requeridos');
		$sql_1="SELECT * from ccnd03_censo_miembros where ".$conditions." and 1=1 order by miembro_numero asc";
		$result_1=$this->ccnd03_censo_miembros->execute($sql_1);
		$this->set('fami',$result_1);
		return;
	}else if(empty($this->data['ccnp01_directiva']['cedula_fami'.$i]) && $this->edad($this->Cfecha($this->data['ccnp01_directiva']['fecha_nacimiento_fami'.$i],"A-M-D"))>=15){
		$this->set('errorMessage', 'Debe ingresar la cedula del familiar');
		if(!isset($_SESSION["contador"])){
 			$this->set('vacio','');
 		}
 		$sql_1="SELECT * from ccnd03_censo_miembros where ".$conditions." and 1=1 order by miembro_numero asc";
		$result_1=$this->ccnd03_censo_miembros->execute($sql_1);
		$this->set('fami',$result_1);
		return;
	}

	$linea=$this->data['ccnp01_directiva']['num_miembro'.$i];
	$parentesco=$this->data['ccnp01_directiva']['parentesco_fami'.$i];
    $nacionalidad=$this->data['ccnp01_directiva']['nacionalidad'.$i];
    $cedula=$this->data['ccnp01_directiva']['cedula_fami'.$i];
    $ape_nom=$this->data['ccnp01_directiva']['ape_nom'.$i];
    $fecha=$this->data['ccnp01_directiva']['fecha_nacimiento_fami'.$i];
    $sexo_fami=$this->data['ccnp01_directiva']['sexo_fami'.$i];
    $trabaja=$this->data['ccnp01_directiva']['trabaja'.$i];
    $estudia=$this->data['ccnp01_directiva']['estudia'.$i];

    $conditions  = "cod_republica=".$this->Session->read('CC_republica')." and cod_estado=".$this->Session->read('CC_estado')." and cod_municipio=".$this->Session->read('CC_municipio')." and cod_parroquia=".$this->Session->read('CC_parroquia')." and cod_centro=".$this->Session->read('CC_centro')." and cod_concejo=".$this->Session->read('CC_concejo');
    $conditions.=" and numero_familia=".$cod_familia;

		$conditions1=$conditions." and miembro_numero=".$numero_miembro;
		if(empty($this->data['ccnp01_directiva']['cedula_fami'.$i])){
			$sql = "BEGIN;UPDATE ccnd03_censo_miembros SET cod_miembro='$parentesco',nacionalidad='$nacionalidad',cedula_identidad=null,apellidos_nombres='$ape_nom',fecha_nacimiento='$fecha',sexo='$sexo_fami',trabaja='$trabaja',estudia='$estudia' where ".$conditions1;
		}else{
		 	$sql = "BEGIN;UPDATE ccnd03_censo_miembros SET cod_miembro='$parentesco',nacionalidad='$nacionalidad',cedula_identidad='$cedula',apellidos_nombres='$ape_nom',fecha_nacimiento='$fecha',sexo='$sexo_fami',trabaja='$trabaja',estudia='$estudia' where ".$conditions1;
		}
		$sw=$this->ccnd03_censo_miembros->execute($sql);
		if($sw>1){
			$this->set('Message_existe','DATOS DEL FAMILIAR MODIFICADOS EXITOSAMENTE');
			$this->ccnd03_censo_miembros->execute("COMMIT");
		}else{
			$this->set('errorMessage', 'LOS DATOS DEL FAMILIAR NO PUDIERON SER MODIFICADOS');
			$this->ccnd03_censo_miembros->execute("ROLLBACK");
		}

	/*$verifica=$this->ccnd03_censo_miembros->FindCount($conditions." and cedula_identidad='$cedula'");
	if($verifica==0){
		$conditions.=" and miembro_numero=".$numero_miembro;
		$sql = "BEGIN;UPDATE ccnd03_censo_miembros SET cod_miembro='$parentesco',nacionalidad='$nacionalidad',cedula_identidad='$cedula',apellidos_nombres='$ape_nom',fecha_nacimiento='$fecha_nacimiento_fami',sexo='$sexo_fami',trabaja='$trabaja',estudia='$estudia' where ".$conditions;
		$sw=$this->ccnd03_censo_miembros->execute($sql);
		if($sw>1){
			$this->set('Message_existe','DATOS DEL FAMILIAR MODIFICADOS EXITOSAMENTE');
			$this->ccnd03_censo_miembros->execute("COMMIT");
		}else{
			$this->set('errorMessage', 'LOS DATOS DEL FAMILIAR NO PUDIERON SER MODIFICADOS');
			$this->ccnd03_censo_miembros->execute("ROLLBACK");
		}
	}else{
		$this->set('errorMessage', 'la cedula de identidad ya existe registrada entre sus familiares');
	}*/

	$sql_1="SELECT * from ccnd03_censo_miembros where ".$conditions." and 1=1 order by miembro_numero asc";
	$result_1=$this->ccnd03_censo_miembros->execute($sql_1);
	$this->set('fami',$result_1);


}//fin guardar_items_modificar



function cancelar($cod_familia=nulll){
    $this->layout = "ajax";
    $paren=$this->cnmd06_parentesco->findAll();
	$this->set('paren',$paren);

	$conditions  = "     cod_republica=".$this->Session->read('CC_republica')." and cod_estado=".$this->Session->read('CC_estado')." and cod_municipio=".$this->Session->read('CC_municipio')." and cod_parroquia=".$this->Session->read('CC_parroquia')." and cod_centro=".$this->Session->read('CC_centro')." and cod_concejo=".$this->Session->read('CC_concejo');
    $conditions.=" and numero_familia=".$cod_familia;

	$sql_1="SELECT * from ccnd03_censo_miembros where ".$conditions." and 1=1 order by miembro_numero asc";
	$result_1=$this->ccnd03_censo_miembros->execute($sql_1);
	$this->set('fami',$result_1);

}//fin cancelar





function eliminar($cod_familia=null,$pagina=null){
	$this->layout = "ajax";

    $conditions  = "cod_republica=".$this->Session->read('CC_republica')." and cod_estado=".$this->Session->read('CC_estado')." and cod_municipio=".$this->Session->read('CC_municipio')." and cod_parroquia=".$this->Session->read('CC_parroquia')." and cod_centro=".$this->Session->read('CC_centro')." and cod_concejo=".$this->Session->read('CC_concejo');
    $cond=$conditions;
    $conditions.=" and numero_familia=".$cod_familia;

    $x2 = $this->ccnd03_censo_familias->execute("DELETE FROM ccnd03_censo_familias  WHERE ".$conditions);
    $x2 = $this->ccnd03_censo_miembros->execute("DELETE FROM ccnd03_censo_miembros  WHERE ".$conditions);
    $this->set('Message_existe','registro eliminado con exito');

	 if(isset($pagina)){
		$num=$this->ccnd03_censo_familias->FindCount($cond);
		if($num==0){
			$this->index();
			$this->render('index');
		}else{
			$this->consulta($pagina);
	  		$this->render('consulta');
		}
	  }else{
		  	$this->index();
			$this->render('index');
	  }
}//fin function



function eliminar_familiar($cod_familia=null,$numero_miembro=null){
	$this->layout = "ajax";
	$paren=$this->cnmd06_parentesco->findAll();
	$this->set('paren',$paren);
    $conditions  = "cod_republica=".$this->Session->read('CC_republica')." and cod_estado=".$this->Session->read('CC_estado')." and cod_municipio=".$this->Session->read('CC_municipio')." and cod_parroquia=".$this->Session->read('CC_parroquia')." and cod_centro=".$this->Session->read('CC_centro')." and cod_concejo=".$this->Session->read('CC_concejo')." and numero_familia=".$cod_familia;
    $conditions1=$conditions." and miembro_numero=".$numero_miembro;

    $x2 = $this->ccnd03_censo_miembros->execute("DELETE FROM ccnd03_censo_miembros  WHERE ".$conditions1);
    $this->set('Message_existe','familiar eliminado con exito');

	$sql_1="SELECT * from ccnd03_censo_miembros where ".$conditions." and 1=1 order by miembro_numero asc";
	$result_1=$this->ccnd03_censo_miembros->execute($sql_1);
	$k=1;
	for($i=0;$i<count($result_1);$i++){
		$conditions2=$conditions." and miembro_numero=".$result_1[$i][0]['miembro_numero'];
		$sql = "BEGIN;UPDATE ccnd03_censo_miembros SET miembro_numero='$k' where ".$conditions2;
		$sw=$this->ccnd03_censo_miembros->execute($sql);
		$k++;
	}
	if($sw>1){
		$sw=$this->ccnd03_censo_miembros->execute('COMMIT');
	}else{
		$sw=$this->ccnd03_censo_miembros->execute('ROLLBACK');
	}

	$this->cancelar($cod_familia);
	$this->render('cancelar');
	/*
	$sql_2="SELECT * from ccnd03_censo_miembros where ".$conditions." and 1=1 order by miembro_numero asc";
	$result_2=$this->ccnd03_censo_miembros->execute($sql_2);
	$this->set('fami',$result_2);
	*/

}//fin function




function consulta($pagina=null) {
	$this->layout="ajax";

	$paren=$this->cnmd06_parentesco->findAll();
	$this->set('paren',$paren);
	$this->set('republica',$this->cugd01_republica->field('denominacion', $conditions ="cod_republica=".$this->Session->read('CC_republica'), $order ="cod_republica ASC"));
	$this->set('estado',$this->cugd01_estados->field('denominacion', $conditions ="cod_republica=".$this->Session->read('CC_republica')." and cod_estado=".$this->Session->read('CC_estado'), $order ="cod_estado ASC"));
	$this->set('municipio',$this->cugd01_municipios->field('denominacion', $conditions ="cod_republica=".$this->Session->read('CC_republica')." and cod_estado=".$this->Session->read('CC_estado')." and cod_municipio=".$this->Session->read('CC_municipio'), $order ="cod_municipio ASC"));
	$this->set('parroquia',$this->cugd01_parroquias->field('denominacion', $conditions ="cod_republica=".$this->Session->read('CC_republica')." and cod_estado=".$this->Session->read('CC_estado')." and cod_municipio=".$this->Session->read('CC_municipio')." and cod_parroquia=".$this->Session->read('CC_parroquia'), $order ="cod_parroquia ASC"));


    $condicion_dir_sup = "";
    $dir_sup=$this->cugd02_direccionsuperior->generateList($condicion_dir_sup,'denominacion ASC', null, '{n}.cugd02_direccionsuperior.cod_dir_superior', '{n}.cugd02_direccionsuperior.denominacion');
	$dir_sup = $dir_sup != null ? $dir_sup : array();
	$this->concatena($dir_sup, 'dir_superior');

	$conditions  = "     cod_republica=".$this->Session->read('CC_republica')." and cod_estado=".$this->Session->read('CC_estado')." and cod_municipio=".$this->Session->read('CC_municipio')." and cod_parroquia=".$this->Session->read('CC_parroquia')." ";
 	$conditions .= " and cod_centro=".$this->Session->read('CC_centro')." and cod_concejo=".$this->Session->read('CC_concejo')."";
    $dir_concejo=$this->ccnd01_concejo_comunal->generateList($conditions,'denominacion ASC', null, '{n}.ccnd01_concejo_comunal.cod_concejo', '{n}.ccnd01_concejo_comunal.denominacion');
	$dir_concejo = $dir_concejo != null ? $dir_concejo : array();
	$this->concatena($dir_concejo, 'concejo');

	$denominacion = $this->ccnd01_concejo_comunal->field('denominacion', $conditions , null);
	$this->set('seleccion_concejo', $this->Session->read('CC_concejo'));
	$this->set('denominacion_concejo', $denominacion);



	$condicion_cod_tipo = "";
    $dir_cod_tipo=$this->ccnd01_tipo_directivo->generateList($condicion_cod_tipo,'denominacion ASC', null, '{n}.ccnd01_tipo_directivo.cod_tipo', '{n}.ccnd01_tipo_directivo.denominacion');
	$dir_cod_tipo = $dir_cod_tipo != null ? $dir_cod_tipo : array();
	$this->concatena($dir_cod_tipo, 'cod_tipo');

	$sql="select * from cugd01_centros_poblados where cod_republica=".$this->Session->read('CC_republica')." and cod_estado=".$this->Session->read('CC_estado')." and cod_municipio=".$this->Session->read('CC_municipio')." and cod_parroquia=".$this->Session->read('CC_parroquia')." and cod_centro=".$this->Session->read('CC_centro');
	$datos=$this->cugd01_parroquias->execute($sql);
	$this->set('datos',$datos);


	$conditions1  = "cod_republica=".$this->Session->read('CC_republica')." and cod_estado=".$this->Session->read('CC_estado')." and cod_municipio=".$this->Session->read('CC_municipio')." and cod_parroquia=".$this->Session->read('CC_parroquia')." and cod_centro=".$this->Session->read('CC_centro')." and cod_concejo=".$this->Session->read('CC_concejo');

	if(isset($pagina)){
		$Tfilas=$this->ccnd03_censo_familias->findCount($conditions1);
        if($Tfilas!=0){
        	$x=$this->ccnd03_censo_familias->findAll($conditions1,null,"numero_familia ASC",1,$pagina,null);

            $this->set('DATA',$x);
            $this->set('pagina',$pagina);
          	$this->set('siguiente',$pagina+1);
          	$this->set('anterior',$pagina-1);
            $this->bt_nav($Tfilas,$pagina);
            $this->set('numT',$Tfilas);
			$this->set('numP',$pagina);
        }else{
	 	       $this->set('errorMessage', 'No se encontrar&oacute;n datos');
	 	       $this->set('noExiste',true);
	 	       $this->index();
			   $this->render("index");
			   return;
        }

	}else{
		$pagina=1;
		$Tfilas=$this->ccnd03_censo_familias->findCount($conditions1);

        if($Tfilas!=0){
        	$x=$this->ccnd03_censo_familias->findAll($conditions1,null,"numero_familia ASC",1,$pagina,null);
			$this->set('DATA',$x);
			$this->set('pagina',$pagina);
          	$this->set('siguiente',$pagina+1);
          	$this->set('anterior',$pagina-1);
          	$this->bt_nav($Tfilas,$pagina);
          	$this->set('numT',$Tfilas);
			$this->set('numP',$pagina);

        }else{
	 	       $this->set('errorMessage', 'No se encontrar&oacute;n datos');
	 	       $this->set('noExiste',true);
	 	       $this->index();
			   $this->render("index");
			   return;
        }
	}

	$this->set('perso',$x);

	$cod_familia= $x[0]["ccnd03_censo_familias"]["numero_familia"];
	$conditions.=" and numero_familia=".$cod_familia;
	$datos2=$this->ccnd03_censo_miembros->execute("select * from ccnd03_censo_miembros where ".$conditions." order by miembro_numero asc ");

	$this->set('deno_vialidad',$this->cugd01_vialidad->field('denominacion', $conditions ="cod_republica=".$this->Session->read('CC_republica')." and cod_estado=".$this->Session->read('CC_estado')." and cod_municipio=".$this->Session->read('CC_municipio')." and cod_parroquia=".$this->Session->read('CC_parroquia')." and cod_centro=".$this->Session->read('CC_centro')." and cod_vialidad=".$x[0]['ccnd03_censo_familias']['cod_calle'], $order ="cod_vialidad ASC"));

	$this->set('fami',$datos2);


 }//consultar




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







 }//Fin de la clase controller
 ?>