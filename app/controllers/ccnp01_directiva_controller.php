<?php
class Ccnp01DirectivaController extends AppController {
   var $name = 'ccnp01_directiva';
   var $uses = array('ccnd01_tipo_directivo','ccnd01_cargos_directivos',"cugd01_republica", "cugd01_estados", "cugd01_municipios",
                     "cugd01_parroquias", "cugd02_direccionsuperior", "ccnd01_concejo_comunal", "cnmd06_parentesco", "cnmd06_profesiones",
                     "cnmd06_oficio", "ccnd01_directiva_familiar", "ccnd01_directiva", "casd01_datos_familiares", "cugd10_imagenes");
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


 function index(){
 	$this->layout ="ajax";
    $zonificacion= array('1'=>'Urbanizacion','2'=>'Barrio','3'=>'Caserio','4'=>'Comuna','5'=>'Vialidad');
	$this->concatena($zonificacion, 'zonificacion');
	$this->set('republica',$this->cugd01_republica->field('denominacion', $conditions ="cod_republica=".$this->Session->read('CC_republica'), $order ="cod_republica ASC"));
	$this->set('estado',$this->cugd01_estados->field('denominacion', $conditions ="cod_republica=".$this->Session->read('CC_republica')." and cod_estado=".$this->Session->read('CC_estado'), $order ="cod_estado ASC"));
	$this->set('municipio',$this->cugd01_municipios->field('denominacion', $conditions ="cod_republica=".$this->Session->read('CC_republica')." and cod_estado=".$this->Session->read('CC_estado')." and cod_municipio=".$this->Session->read('CC_municipio'), $order ="cod_municipio ASC"));
	$this->set('parroquia',$this->cugd01_parroquias->field('denominacion', $conditions ="cod_republica=".$this->Session->read('CC_republica')." and cod_estado=".$this->Session->read('CC_estado')." and cod_municipio=".$this->Session->read('CC_municipio')." and cod_parroquia=".$this->Session->read('CC_parroquia'), $order ="cod_parroquia ASC"));

    $condicion_dir_sup = "";
    $dir_sup=$this->cugd02_direccionsuperior->generateList($condicion_dir_sup,'denominacion ASC', null, '{n}.cugd02_direccionsuperior.cod_dir_superior', '{n}.cugd02_direccionsuperior.denominacion');
	$dir_sup = $dir_sup != null ? $dir_sup : array();
	$this->concatena($dir_sup, 'dir_superior');

    $this->concatena($this->cnmd06_parentesco->generateList(null,'cod_parentesco ASC', null, '{n}.cnmd06_parentesco.cod_parentesco', '{n}.cnmd06_parentesco.denominacion'),'parentesco');



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

	$this->concatenaN($this->cnmd06_profesiones->generateList(null,'denominacion ASC', null, '{n}.cnmd06_profesiones.cod_profesion', '{n}.cnmd06_profesiones.denominacion'),'profesion');
	$this->concatenaN($this->cnmd06_oficio->generateList(null,'denominacion ASC', null, '{n}.cnmd06_oficio.cod_oficio', '{n}.cnmd06_oficio.denominacion'),'oficio');

	$this->Session->delete("items1");
	$this->Session->delete("i");
	$this->Session->delete("contador");

	if(!isset($_SESSION["contar_grilla"])){$_SESSION["contar_grilla"] = 1;}

 }// fin index



 function funcion($var1=null){

 	$this->layout ="ajax";

 }//fin function









function select3($opcion=null,$var=null){
	$this->layout="ajax";
	if($var!=''){
		switch($opcion){
			case 'municipio':
				$this->set('no','');
				$this->set('SELECT','parroquia');
				$this->set('codigo','municipio');
				$this->set('seleccion','');
				$this->set('n',2);
				$this->Session->write('cod1',$var);
				$cond =" cod_republica=".$this->Session->read('SScodpresi')." and cod_estado=".$var;
				$lista=  $this->cugd01_municipios->generateList($cond, 'cod_municipio ASC', null, '{n}.cugd01_municipios.cod_municipio', '{n}.cugd01_municipios.denominacion');
				$this->concatena($lista, 'vector');
				$this->set('anula','otros');
			break;
			case 'parroquia':
				$this->set('no','');
				$this->set('SELECT','centro');
				$this->set('codigo','parroquia');
				$this->set('seleccion','');
				$this->set('n',3);
				$this->Session->write('cod2',$var);
				$cod1=$this->Session->read('cod1');
				$cond =" cod_republica=".$this->Session->read('SScodpresi')." and cod_estado=".$cod1." and cod_municipio=".$var;
				$lista=  $this->cugd01_parroquias->generateList($cond, 'cod_parroquia ASC', null, '{n}.cugd01_parroquias.cod_parroquia', '{n}.cugd01_parroquias.denominacion');
				$this->concatena($lista, 'vector');
				$this->set('anula','otros');
			break;
			case 'centro':
				$this->set('no','no');
				$this->set('SELECT','centro');
				$this->set('codigo','centro');
				$this->set('seleccion','');
				$this->set('n',4);
				$cod1=$this->Session->read('cod1');
				$cod2=$this->Session->read('cod2');
				$cond =" cod_republica=".$this->Session->read('SScodpresi')." and cod_estado=".$cod1." and cod_municipio=".$cod2." and cod_parroquia=".$var;
				$lista=  $this->cugd01_centropoblados->generateList($cond, 'cod_centro ASC', null, '{n}.cugd01_centropoblados.cod_centro', '{n}.cugd01_centropoblados.denominacion');
				$this->concatena($lista, 'vector');
				$this->set('anula','otros');
			break;
		}//fin switch
	}

}















function agregar_grilla($var=null) {
	$this->layout="ajax";

	$paren=$this->cnmd06_parentesco->findAll();
	$this->set('paren',$paren);

	if(empty($this->data['ccnp01_directiva']['parentesco_fami']) || empty($this->data['ccnp01_directiva']['ape_nom']) || empty($this->data['ccnp01_directiva']['fecha_nacimiento_fami']) || empty($this->data['ccnp01_directiva']['sexo_fami']) || empty($this->data['ccnp01_directiva']['trabaja']) || empty($this->data['ccnp01_directiva']['estudia'])){
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
	}else if($this->data['ccnp01_directiva']['cedula_fami']==$this->data['ccnp01_directiva']['cedula_identidad']){
		$this->set('errorMessage', 'no se podra agregar este familiar ya que posee la misma cedula que el registro principal');
		if(!isset($_SESSION["contador"])){
 			$this->set('vacio','');
 		}
		return;
	}



	 $ci_principal=$this->data['ccnp01_directiva']['cedula_identidad'];
	if(empty($this->data['ccnp01_directiva']['cedula_fami']) && isset($_SESSION["items1"])){
		$vector=array();
		foreach($_SESSION ["items1"] as $xx){
			$vector[]=$xx[1];
		}
		$cedula=$this->sumar_ci($vector,$ci_principal);
	}else if(empty($this->data['ccnp01_directiva']['cedula_fami']) && !isset($_SESSION["items1"])){
		 $cedula=$this->dv_ci($ci_principal);

	}else if(!empty($this->data['ccnp01_directiva']['cedula_fami'])){
		$cedula=$this->data['ccnp01_directiva']['cedula_fami'];
	}

	    $parentesco=$this->data['ccnp01_directiva']['parentesco_fami'];
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

			$cod[0]=$parentesco;
			$cod[1]=$cedula;
			$cod[2]=$ape_nom;
			$cod[3]=$fecha;
			$cod[4]=$sexo_fami;
			$cod[5]=$trabaja;
			$cod[6]=$estudia;

		    if(isset($_SESSION["i"])){
				$i=$this->Session->read("i")+1;
				$this->Session->write("i",$i);
	   		 }else{
			   $this->Session->write("i",0);
				$i=0;
			}
        switch($var){
        	case 'normal':
				     $vec[$i][0]=$parentesco;
				     $vec[$i][1]=$cedula;
					 $vec[$i][2]=$ape_nom;
					 $vec[$i][3]=$fecha;
					 $vec[$i][4]=$sexo_fami;
					 $vec[$i][5]=$trabaja;
					 $vec[$i][6]=$estudia;
					 //echo $vec[$i][6];
					 $vec[$i]["id"]=$i;
					if(isset($_SESSION["items1"])){
						foreach($_SESSION["items1"] as $codi){
            	           if($codi[1]==$cod[1]){
                              $est=true;
                              break;
            	          }else{
            	          	 $est=false;
            	          }
                        }//fin foreach
                        if($est==true){
            	          	$i=$this->Session->read("i")-1;
				            $this->Session->write("i",$i);
				            $this->set('errorMessage', 'Esta persona ya existe en la lista');
                        }else{
                        	$_SESSION["items1"]=$_SESSION["items1"]+$vec;
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





function limpiar_lista() {
	$this->layout = "ajax";
	$this->Session->delete("items1");
	$this->Session->delete("i");
	$this->Session->delete("contador");
}


function eliminar_items($id) {
	$this->layout = "ajax";
	$paren=$this->cnmd06_parentesco->findAll();
	$this->set('paren',$paren);


        $cod_republica = $this->Session->read('CC_republica');
 	 	$cod_estado    = $this->Session->read('CC_estado');
 	 	$cod_municipio = $this->Session->read('CC_municipio');
 	 	$cod_parroquia = $this->Session->read('CC_parroquia');
  	    $cod_centro    = $this->Session->read('CC_centro');
 	    $cod_concejo   = $this->Session->read('CC_concejo');


     $cont=0;
	 foreach($_SESSION ["items1"] as $in => $codigos){
	 	 if($codigos!=null){
			       if($id==$codigos["id"]){
			           if(isset($codigos[4])){

			           	           $sql_b  = "cod_republica                = '".$cod_republica."'     and
											  cod_estado                   = '".$cod_estado."'        and
											  cod_municipio                = '".$cod_municipio."'     and
											  cod_centro                   = '".$cod_parroquia."'     and
											  cod_centro                   = '".$cod_centro."'        and
											  cod_concejo                  = '".$cod_concejo."'       and
											  cod_tipo                     = '".$codigos[8]."'        and
											  cod_cargo                    = '".$codigos[9]."'        and
											  cedula_directivo             = '".$codigos[10]."'       and
											  cod_parentesco               = '".$codigos[0]."'        and
											  cedula_pariente              = '".$codigos[1]."' ";

					                $x2 = $this->ccnd01_directiva->execute("DELETE FROM ccnd01_directiva_familiar  WHERE ".$sql_b." ");
			                        $this->set('errorMessage','registro eliminado con exito');

			            }
			       	    $_SESSION["items1"][$in]=null;
			      }//fin if
			      $cont++;
	 	 }//fin if
	}//fin foreach


	$NL=0;
	$codigos1=array();
	foreach($_SESSION ["items1"] as $codigos){
       if($codigos!=null){
       		$codigos1[$NL][0]=$codigos[0];
       		$codigos1[$NL][1]=$codigos[1];
       		$codigos1[$NL][2]=$codigos[2];
       		$codigos1[$NL][3]=$codigos[3];
       		$codigos1[$NL][4]=$codigos[4];
       		$codigos1[$NL][5]=$codigos[5];
       		$codigos1[$NL][6]=$codigos[6];
       		if(isset($codigos[7])){$codigos1[$NL][7]=$codigos[7];}//fin if
       		if(isset($codigos[8])){$codigos1[$NL][8]=$codigos[8];}//fin if
       		if(isset($codigos[9])){$codigos1[$NL][9]=$codigos[9];}//fin if
       		if(isset($codigos[10])){$codigos1[$NL][10]=$codigos[10];}//fin if
       		$codigos1[$NL]['id']=$codigos["id"];
       		$NL++;
       }//fin if
	}//fin foreach

    $_SESSION["contador"]=$_SESSION["contador"]-1;


    if($NL==0){
        $this->Session->delete("items1");
	    $this->Session->delete("i");
	    $this->Session->delete("contador");
    }else{
    	$_SESSION["items1"]=array();
        $_SESSION["items1"]=$codigos1;
    }



}//fin function











 function selecion_cod_tipo($var1=null){

 	$this->layout ="ajax";

 	$condicion_cod_cargo = "cod_tipo='".$var1."'   ";
    $dir_cod_cargo=$this->ccnd01_cargos_directivos->generateList($condicion_cod_cargo,'denominacion ASC', null, '{n}.ccnd01_cargos_directivos.cod_cargo', '{n}.ccnd01_cargos_directivos.denominacion');
	$dir_cod_cargo = $dir_cod_cargo != null ? $dir_cod_cargo : array();
	$this->concatena($dir_cod_cargo, 'cod_cargo');
	$this->set('var1', $var1);

	$denominacion = $this->ccnd01_tipo_directivo->field('denominacion', $condicion_cod_cargo , null);

	             echo "<script>";
					echo "document.getElementById('deno_cod_tipo').value='".$denominacion."';";
					echo "document.getElementById('deno_cod_cargo').value='';";
				 echo "</script>";


 }//fin function





  function selecion_cod_cargo($var1=null, $var2=null){

 	$this->layout ="ajax";

 	$condicion_cod_cargo = "cod_tipo='".$var1."'  and  cod_cargo='".$var2."' ";
 	$denominacion = $this->ccnd01_cargos_directivos->field('denominacion', $condicion_cod_cargo , null);

                  echo "<script>";
					echo "document.getElementById('deno_cod_cargo').value='".$denominacion."';";
				 echo "</script>";

				 $this->funcion();
				 $this->render("funcion");

 }//fin function






   function selecion_concejo($var1=null, $var2=null){

 	$this->layout ="ajax";

 	$conditions  = "     cod_republica=".$this->Session->read('CC_republica')." and cod_estado=".$this->Session->read('CC_estado')." and cod_municipio=".$this->Session->read('CC_municipio')." and cod_parroquia=".$this->Session->read('CC_parroquia')." ";
 	$conditions .= " and cod_centro=".$this->Session->read('CC_centro')." and cod_concejo=".$this->Session->read('CC_concejo')."";
 	$denominacion = $this->ccnd01_concejo_comunal->field('denominacion', $conditions , null);

                  echo "<script>";
					echo "document.getElementById('deno_concejo_comunal').value='".$denominacion."';";
				 echo "</script>";

				 $this->funcion();
				 $this->render("funcion");

 }//fin function








 function guardar($nomina=null,$cargo=null,$ficha=null) {
 	$this->layout = "ajax";

 	 	$cod_republica = $this->Session->read('CC_republica');
 	 	$cod_estado    = $this->Session->read('CC_estado');
 	 	$cod_municipio = $this->Session->read('CC_municipio');
 	 	$cod_parroquia = $this->Session->read('CC_parroquia');
  	    $cod_centro    = $this->Session->read('CC_centro');
 	    $cod_concejo   = $this->Session->read('CC_concejo');

 	    $guardar[]=0;
	    $i=0;

if(!empty($this->data['ccnp01_directiva']['cod_tipo'])){
if(!empty($this->data['ccnp01_directiva']['cod_cargo'])){
if(!empty($this->data['ccnp01_directiva']['cedula_identidad'])){
if(!empty($this->data['ccnp01_directiva']['nacionalidad'])){
if(!empty($this->data['ccnp01_directiva']['nombre_apellidos'])){
if(!empty($this->data['ccnp01_directiva']['fecha_nacimiento'])){
if(!empty($this->data['ccnp01_directiva']['sexo'])){
if(!empty($this->data['ccnp01_directiva']['estado_civil'])){
if(!empty($this->data['ccnp01_directiva']['profesion'])){
if(!empty($this->data['ccnp01_directiva']['oficio'])){
if(!empty($this->data['ccnp01_directiva']['tipo_vivienda'])){
if(!empty($this->data['ccnp01_directiva']['tenencia_vivencia'])){
if(!empty($this->data['ccnp01_directiva']['anos_residencia'])){
if(!empty($this->data['ccnp01_directiva']['cod_mision'])){
if(!empty($this->data['ccnp01_directiva']['direccion'])){





	    if(!empty($this->data) && !empty($this->data['ccnp01_directiva']['cedula_identidad'])){

		 	$direccion_habitacion          =  $this->data['ccnp01_directiva']['direccion'];
		 	$nacionalidad                  =  $this->data['ccnp01_directiva']['nacionalidad'];
		 	$cedula_identidad              =  $this->data['ccnp01_directiva']['cedula_identidad'];
		 	$apellidos_nombres             =  $this->data['ccnp01_directiva']['nombre_apellidos'];
		 	$fecha_nacimiento              =  $this->data['ccnp01_directiva']['fecha_nacimiento'];
		 	$sexo                          =  $this->data['ccnp01_directiva']['sexo'];
		 	$estado_civil                  =  $this->data['ccnp01_directiva']['estado_civil'];
		 	$cod_profesion                 =  $this->data['ccnp01_directiva']['profesion'];
		 	$cod_ocupacion                 =  $this->data['ccnp01_directiva']['oficio'];
		 	$cod_tipo                      =  $this->data['ccnp01_directiva']['cod_tipo'];
		 	$cod_cargo                     =  $this->data['ccnp01_directiva']['cod_cargo'];
		 	$cod_vivienda                  =  $this->data['ccnp01_directiva']['tipo_vivienda'];


		 	if(empty($this->data['ccnp01_directiva']['estado_conservacion'])){
		 		$estado_conservacion_vivienda=0;
		 	}else{
		 		$estado_conservacion_vivienda=$this->data['ccnp01_directiva']['estado_conservacion'];
		 	}

		 	if(empty($this->data['ccnp01_directiva']['fijos'])){
		 		$telefonos_fijos=0;
		 	}else{
		 		$telefonos_fijos=$this->data['ccnp01_directiva']['fijos'];
		 	}

		 	if(empty($this->data['ccnp01_directiva']['celulares'])){
		 		$telefonos_moviles=0;
		 	}else{
		 		$telefonos_moviles=$this->data['ccnp01_directiva']['celulares'];
		 	}


		 	if(empty($this->data['ccnp01_directiva']['peso'])){
		 		$peso=0;
		 	}else{
				$peso=$this->data['ccnp01_directiva']['peso'];
		 		$xp=explode(',',$peso);
		 		$valor1=strlen($xp[0]);
		 		$valor2=strlen(isset($xp[1]));
				if($valor1>3){
					$xp[0]=substr($xp[0],0,3);
				}

				if($valor2>3){
					$xp[1]=substr($xp[1],0,3);
				}
				if(isset($xp[1])){
					$peso=$xp[0].'.'.$xp[1];
				}else{
					$peso=$xp[0];
				}
		 	}

		 	if(empty($this->data['ccnp01_directiva']['estatura'])){
		 		$estatura=0;
		 	}else{
		 		$estatura=$this->Formato1($this->data['ccnp01_directiva']['estatura']);
		 	}


		 	if(empty($this->data['ccnp01_directiva']['grupo_sanguineo'])){
		 		$grupo_sanguineo=0;
		 	}else{
		 		$grupo_sanguineo=$this->data['ccnp01_directiva']['grupo_sanguineo'];
		 	}



		 	if(empty($this->data['ccnp01_directiva']['monto_alquiler'])){
		 		$monto_alquiler_hipoteca=0;
		 	}else{
		 		$monto_alquiler_hipoteca=$this->Formato1($this->data['ccnp01_directiva']['monto_alquiler']);
		 	}

		 	if(empty($this->data['ccnp01_directiva']['anos_residencia'])){
		 		$anos_residencia=0;
		 	}else{
		 		$anos_residencia=$this->data['ccnp01_directiva']['anos_residencia'];
		 	}

		 	if(empty($this->data['ccnp01_directiva']['tenencia_vivencia'])){
		 		$cod_tenencia_vivienda=0;
		 	}else{
		 		$cod_tenencia_vivienda=$this->data['ccnp01_directiva']['tenencia_vivencia'];
		 	}

            if(empty($this->data['ccnp01_directiva']['cod_mision'])){
		 		$cod_mision=0;
		 	}else{
		 		$cod_mision=$this->data['ccnp01_directiva']['cod_mision'];
		 	}






 $campos_a =  "   cod_republica,
				  cod_estado,
				  cod_municipio,
				  cod_parroquia,
				  cod_centro,
				  cod_concejo,
				  cod_tipo,
				  cod_cargo,
				  cedula_identidad,
				  nacionalidad,
				  apellidos_nombres,
				  fecha_nacimiento,
				  sexo,
				  estado_civil,
				  peso,
				  estatura,
				  grupo_sanguineo,
				  cod_profesion,
				  cod_ocupacion,
				  cod_vivienda,
				  cod_tenencia_vivienda,
				  anos_residencia,
				  monto_alquiler_hipoteca,
				  cod_mision,
				  direccion_habitacion,
				  telefonos_fijos,
				  telefonos_moviles,
				  estado_conservacion_vivienda	";


$values_a = "     '".$cod_republica."',
				  '".$cod_estado."',
				  '".$cod_municipio."',
				  '".$cod_parroquia."',
				  '".$cod_centro."',
				  '".$cod_concejo."',
				  '".$cod_tipo."',
				  '".$cod_cargo."',
				  '".$cedula_identidad."',
				  '".$nacionalidad."',
				  '".$apellidos_nombres."',
				  '".$fecha_nacimiento."',
				  '".$sexo."',
				  '".$estado_civil."',
				  '".$peso."',
				  '".$estatura."',
				  '".$grupo_sanguineo."',
				  '".$cod_profesion."',
				  '".$cod_ocupacion."',
				  '".$cod_vivienda."',
				  '".$cod_tenencia_vivienda."',
				  '".$anos_residencia."',
				  '".$monto_alquiler_hipoteca."',
				  '".$cod_mision."',
				  '".$direccion_habitacion."',
				  '".$telefonos_fijos."',
				  '".$telefonos_moviles."',
				  '".$estado_conservacion_vivienda."' ";


		if(!empty($this->data) && isset($_SESSION ["items1"]) && ($_SESSION ["items1"]!=null || $_SESSION ["items1"]!=array())){


 $campos_b =  "   cod_republica,
				  cod_estado,
				  cod_municipio,
				  cod_parroquia,
				  cod_centro,
				  cod_concejo,
				  cod_tipo,
				  cod_cargo,
				  cedula_directivo,
				  cod_parentesco,
				  cedula_pariente,
				  apellidos_nombres,
				  fecha_nacimiento,
				  sexo,
				  trabaja,
				  estudia ";


		 	$sql = "BEGIN; INSERT INTO ccnd01_directiva (".$campos_a.") VALUES (".$values_a."); ";
	   		$sw=$this->ccnd01_directiva_familiar->execute($sql);

				foreach($_SESSION ["items1"] as $guardar){
					if($guardar!=null){

						 $values_b =  "   '".$cod_republica."',
										  '".$cod_estado."',
										  '".$cod_municipio."',
										  '".$cod_parroquia."',
										  '".$cod_centro."',
										  '".$cod_concejo."',
										  '".$cod_tipo."',
										  '".$cod_cargo."',
										  '".$cedula_identidad."',
										  '".$guardar[0]."',
										  '".$guardar[1]."',
										  '".$guardar[2]."',
										  '".$guardar[3]."',
										  '".$guardar[4]."',
										  '".$guardar[5]."',
										  '".$guardar[6]."' ";

						$sql_insert = "INSERT INTO ccnd01_directiva_familiar (".$campos_b.") VALUES(".$values_b."); ";
						$sw2 = $this->ccnd01_directiva_familiar->execute($sql_insert);
					}
				   $i++;
			     }
		}

			if($sw>1){
				$this->ccnd01_directiva_familiar->execute("COMMIT");
				$this->set('Message_existe', 'REGISTRO GUARDADO CON EXITO');
				echo" <script> ver_documento('/ccnp01_directiva/','principal'); </script>";
	   		}else{
	   			$this->ccnd01_directiva_familiar->execute("ROLLBACK");
	   			$this->set('errorMessage', 'LOS DATOS no fueron guardados');
	   		}

	}else{
		$this->set('errorMessage', 'DEBE INGRESAR TODOS LOS DATOS');
	}

}else{ $this->set('errorMessage', 'DEBE INGRESAR la dirección'); }
}else{ $this->set('errorMessage', 'DEBE INGRESAR si es Beneficiario de una misión'); }
}else{ $this->set('errorMessage', 'DEBE INGRESAR los años de residencia'); }
}else{ $this->set('errorMessage', 'DEBE INGRESAR tenecia de la vivienda'); }
}else{ $this->set('errorMessage', 'DEBE INGRESAR el Tipo de vivienda'); }
}else{ $this->set('errorMessage', 'DEBE INGRESAR la ocupación'); }
}else{ $this->set('errorMessage', 'DEBE INGRESAR la profesión'); }
}else{ $this->set('errorMessage', 'DEBE INGRESAR el estado civil'); }
}else{ $this->set('errorMessage', 'DEBE INGRESAR el sexo'); }
}else{ $this->set('errorMessage', 'DEBE INGRESAR la fecha de nacimiento'); }
}else{ $this->set('errorMessage', 'DEBE INGRESAR los nombres y apellidos'); }
}else{ $this->set('errorMessage', 'DEBE INGRESAR la nacionalidad'); }
}else{ $this->set('errorMessage', 'DEBE INGRESAR la cédula de identidad'); }
}else{ $this->set('errorMessage', 'DEBE INGRESAR el código del cargo'); }
}else{ $this->set('errorMessage', 'DEBE INGRESAR el código del directivo'); }


	$this->funcion();
	$this->render("funcion");


 }// fin guardar




















function consulta($pagina=null) {
	$this->layout="ajax";

	    $cod_republica = $this->Session->read('CC_republica');
 	 	$cod_estado    = $this->Session->read('CC_estado');
 	 	$cod_municipio = $this->Session->read('CC_municipio');
 	 	$cod_parroquia = $this->Session->read('CC_parroquia');
  	    $cod_centro    = $this->Session->read('CC_centro');
 	    $cod_concejo   = $this->Session->read('CC_concejo');

 	    $sql_concejo = " cod_republica='".$cod_republica."' and cod_estado='".$cod_estado."' and  cod_municipio='".$cod_municipio."' and  cod_parroquia='".$cod_parroquia."' and cod_centro='".$cod_centro."' and cod_concejo='".$cod_concejo."'  ";

	if(isset($pagina)){
		$Tfilas=$this->ccnd01_directiva->findCount($sql_concejo);
        if($Tfilas!=0){
	        	$x=$this->ccnd01_directiva->findAll($sql_concejo,null,"cod_tipo, cod_cargo, cedula_identidad ASC",1,$pagina,null);
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
		$Tfilas=$this->ccnd01_directiva->findCount($sql_concejo);

        if($Tfilas!=0){
	        	$x=$this->ccnd01_directiva->findAll($sql_concejo, null,"cod_tipo, cod_cargo, cedula_identidad ASC",1,$pagina,null);
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


	$cedula    = $x[0]["ccnd01_directiva"]["cedula_identidad"];
	$cod_tipo  = $x[0]["ccnd01_directiva"]["cod_tipo"];
	$cod_cargo = $x[0]["ccnd01_directiva"]["cod_cargo"];

	$sql="SELECT * from ccnd01_directiva where ".$sql_concejo." and cod_tipo='".$cod_tipo."' and cod_cargo='".$cod_cargo."' and cedula_identidad='".$cedula."' ";
	$result=$this->ccnd01_directiva->execute($sql);
	$this->set('perso',$result);


	$sql_1="SELECT * from ccnd01_directiva_familiar where ".$sql_concejo." and cod_tipo='".$cod_tipo."' and cod_cargo='".$cod_cargo."'  and cedula_directivo='".$cedula."' order by cedula_directivo asc";
	$result_1=$this->ccnd01_directiva_familiar->execute($sql_1);

	$this->set('fami',$result_1);

	$this->set('profesion',$profesion = $this->cnmd06_profesiones->field('denominacion', $conditions = 'cod_profesion='.$result[0][0]['cod_profesion'], $order ="cod_profesion ASC"));
	$this->set('oficio',$oficio = $this->cnmd06_oficio->field('denominacion', $conditions = 'cod_oficio='.$result[0][0]['cod_ocupacion'], $order ="cod_oficio ASC"));


	$this->set('paren',$this->cnmd06_parentesco->findAll());


	$this->set('republica',$this->cugd01_republica->field('denominacion', $conditions ="cod_republica=".$this->Session->read('CC_republica'), $order ="cod_republica ASC"));
	$this->set('estado',$this->cugd01_estados->field('denominacion', $conditions ="cod_republica=".$this->Session->read('CC_republica')." and cod_estado=".$this->Session->read('CC_estado'), $order ="cod_estado ASC"));
	$this->set('municipio',$this->cugd01_municipios->field('denominacion', $conditions ="cod_republica=".$this->Session->read('CC_republica')." and cod_estado=".$this->Session->read('CC_estado')." and cod_municipio=".$this->Session->read('CC_municipio'), $order ="cod_municipio ASC"));
	$this->set('parroquia',$this->cugd01_parroquias->field('denominacion', $conditions ="cod_republica=".$this->Session->read('CC_republica')." and cod_estado=".$this->Session->read('CC_estado')." and cod_municipio=".$this->Session->read('CC_municipio')." and cod_parroquia=".$this->Session->read('CC_parroquia'), $order ="cod_parroquia ASC"));

    $sql="select * from cugd01_centros_poblados where cod_republica=".$this->Session->read('CC_republica')." and cod_estado=".$this->Session->read('CC_estado')." and cod_municipio=".$this->Session->read('CC_municipio')." and cod_parroquia=".$this->Session->read('CC_parroquia')." and cod_centro=".$this->Session->read('CC_centro');
	$datos=$this->cugd01_parroquias->execute($sql);
	$this->set('datos',$datos);

	$denominacion = $this->ccnd01_concejo_comunal->field('denominacion', $conditions , null);
	$this->set('seleccion_concejo', $this->Session->read('CC_concejo'));
	$this->set('denominacion_concejo', $denominacion);


    $condicion_cod_tipo  = "cod_tipo='".$result[0][0]['cod_tipo']."'   ";
	$deno_cod_tipo       = $this->ccnd01_tipo_directivo->field('denominacion', $condicion_cod_tipo , null);

 	$condicion_cod_cargo = "cod_tipo='".$result[0][0]['cod_tipo']."'  and  cod_cargo='".$result[0][0]['cod_cargo']."' ";
 	$deno_cod_cargo      = $this->ccnd01_cargos_directivos->field('denominacion', $condicion_cod_cargo , null);

 	$this->set('cod_tipo',  $result[0][0]['cod_tipo']);
 	$this->set('cod_cargo', $result[0][0]['cod_cargo']);

    $this->set('deno_cod_tipo',  $deno_cod_tipo);
	$this->set('deno_cod_cargo', $deno_cod_cargo);




	$vec=$this->cugd10_imagenes->findCount("cod_campo=11 and identificacion='".$cedula."'");
		if($vec!=0){
			$this->set('existe_imagen',true);
		}else{
			$this->set('existe_imagen',false);
		}



		$this->set('opcion', 2);


 }//consultar









function buscar_vista_1($var1=null){

	$this->layout="ajax";
	$this->Session->delete('pista');

}//fin function





function buscar_vista_2($var1=null, $var2=null, $var3=null){
$this->layout="ajax";
$sql_like = "";

        $cod_republica = $this->Session->read('CC_republica');
 	 	$cod_estado    = $this->Session->read('CC_estado');
 	 	$cod_municipio = $this->Session->read('CC_municipio');
 	 	$cod_parroquia = $this->Session->read('CC_parroquia');
  	    $cod_centro    = $this->Session->read('CC_centro');
 	    $cod_concejo   = $this->Session->read('CC_concejo');

 	    $sql_concejo = " cod_republica='".$cod_republica."' and cod_estado='".$cod_estado."' and  cod_municipio='".$cod_municipio."' and  cod_parroquia='".$cod_parroquia."' and cod_centro='".$cod_centro."' and cod_concejo='".$cod_concejo."'  ";

	if(isset($var2)){

		            $var1 = $this->Session->read('pista');
					$var1 = strtoupper_sisap($var1);
					$pagina = $var2;
					$sql     =" (".$this->busca_separado(array("cedula_identidad", "apellidos_nombres"), $var1).") ";

		$Tfilas=$this->ccnd01_directiva->findCount($sql_concejo." and ".$sql);
        if($Tfilas!=0){

					        	$Tfilas=(int)ceil($Tfilas/100);
					        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
								$this->set('total_paginas',$Tfilas);
								$this->set('pagina_actual',$pagina);
								$this->set('ultimo',$Tfilas);
					     	    $datos_filas=$this->ccnd01_directiva->findAll($sql_concejo." and ".$sql,null,"cod_tipo, cod_cargo, cedula_identidad ASC",100,$pagina,null);
						        $this->set("datosFILAS",$datos_filas);
						        $this->set('siguiente',$pagina+1);
								$this->set('anterior',$pagina-1);
								$this->bt_nav($Tfilas,$pagina);


        }else{
	 	        $this->set('errorMessage', 'No se encontrar&oacute;n datos');
	 	        $this->set("datosFILAS",'');

			return;
        }

	}else{
	              	$pagina=1;
	       	        $this->Session->write('pista', $var1);
					$var1 = strtoupper_sisap($var1);
					$sql     =" (".$this->busca_separado(array("cedula_identidad", "apellidos_nombres"), $var1).") ";

		$Tfilas=$this->ccnd01_directiva->findCount($sql_concejo." and ".$sql);
        if($Tfilas!=0){
	        					$Tfilas=(int)ceil($Tfilas/100);
					        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
								$this->set('total_paginas',$Tfilas);
								$this->set('pagina_actual',$pagina);
								$this->set('ultimo',$Tfilas);
					     	    $datos_filas=$this->ccnd01_directiva->findAll($sql_concejo." and ".$sql,null,"cod_tipo, cod_cargo, cedula_identidad ASC",100,$pagina,null);
						        $this->set("datosFILAS",$datos_filas);
						        $this->set('siguiente',$pagina+1);
								$this->set('anterior',$pagina-1);
								$this->bt_nav($Tfilas,$pagina);

        }else{
	 	        $this->set('errorMessage', 'No se encontrar&oacute;n datos');
	 	         $this->set("datosFILAS",'');

			 return;
        }
	}



}//fin function












function busqueda_cedula($var1=null){

   $this->layout="ajax";


        $cod_republica = $this->Session->read('CC_republica');
 	 	$cod_estado    = $this->Session->read('CC_estado');
 	 	$cod_municipio = $this->Session->read('CC_municipio');
 	 	$cod_parroquia = $this->Session->read('CC_parroquia');
  	    $cod_centro    = $this->Session->read('CC_centro');
 	    $cod_concejo   = $this->Session->read('CC_concejo');

 	    $sql_concejo = " cod_republica='".$cod_republica."' and cod_estado='".$cod_estado."' and  cod_municipio='".$cod_municipio."' and  cod_parroquia='".$cod_parroquia."' and cod_centro='".$cod_centro."' and cod_concejo='".$cod_concejo."'  ";



        $contar_a = $this->ccnd01_directiva->findCount($sql_concejo." and cedula_identidad = '".$var1."'  ");
        $contar_b = $this->ccnd01_directiva->findCount(" cedula_identidad = '".$var1."'  ");


            if($contar_a!=0){

               $this->set("errorMessage", "Ya la cédula ".$var1." esta registrada en este concejo comunal ");

               echo" <script> ver_documento('/ccnp01_directiva/consulta_especifica/".$var1."','principal'); </script>";

      }else if($contar_b!=0){

               $this->set("errorMessage", "Ya la cédula ".$var1." esta registrada en otro concejo comunal ");
               echo" <script> document.getElementById('guardar').disabled=true; </script>";

      }else{
               echo" <script> ver_documento('/ccnp01_directiva/agregar_imagen/".$var1."','aqui_imagen'); </script>";
               echo" <script> document.getElementById('guardar').disabled=false; </script>";

      }//fin else


$this->funcion();
$this->render("funcion");



}//fin function










function agregar_imagen($var1=null){

  $this->layout="ajax";
  $this->set("cedula_identidad", $var1);

  $vec=$this->cugd10_imagenes->findCount("cod_campo=11 and identificacion='".$var1."'");

		if($vec!=0){
			$this->set('existe_imagen',true);
		}else{
			$this->set('existe_imagen',false);
		}

}//fin function








function consulta_especifica($cedula=null) {
	$this->layout="ajax";



	    $cod_republica = $this->Session->read('CC_republica');
 	 	$cod_estado    = $this->Session->read('CC_estado');
 	 	$cod_municipio = $this->Session->read('CC_municipio');
 	 	$cod_parroquia = $this->Session->read('CC_parroquia');
  	    $cod_centro    = $this->Session->read('CC_centro');
 	    $cod_concejo   = $this->Session->read('CC_concejo');

 	    $sql_concejo = " cod_republica='".$cod_republica."' and cod_estado='".$cod_estado."' and  cod_municipio='".$cod_municipio."' and  cod_parroquia='".$cod_parroquia."' and cod_centro='".$cod_centro."' and cod_concejo='".$cod_concejo."'  ";


		$pagina=1;

		$Tfilas=$this->ccnd01_directiva->findCount($sql_concejo." and cedula_identidad = '".$cedula."'  ");

	        	$x=$this->ccnd01_directiva->findAll($sql_concejo." and cedula_identidad = '".$cedula."'  ", null,"cod_tipo, cod_cargo, cedula_identidad ASC",1,$pagina,null);
				$this->set('DATA',$x);
				$this->set('pagina',$pagina);
	          	$this->set('siguiente',$pagina+1);
	          	$this->set('anterior',$pagina-1);
	          	$this->bt_nav($Tfilas,$pagina);
	          	$this->set('numT',$Tfilas);
				$this->set('numP',$pagina);




	$cedula    = $x[0]["ccnd01_directiva"]["cedula_identidad"];
	$cod_tipo  = $x[0]["ccnd01_directiva"]["cod_tipo"];
	$cod_cargo = $x[0]["ccnd01_directiva"]["cod_cargo"];

	$sql="SELECT * from ccnd01_directiva where ".$sql_concejo." and cod_tipo='".$cod_tipo."' and cod_cargo='".$cod_cargo."' and cedula_identidad='".$cedula."' ";
	$result=$this->ccnd01_directiva->execute($sql);
	$this->set('perso',$result);


	$sql_1="SELECT * from ccnd01_directiva_familiar where ".$sql_concejo." and cod_tipo='".$cod_tipo."' and cod_cargo='".$cod_cargo."'  and cedula_directivo='".$cedula."' order by cedula_directivo asc";
	$result_1=$this->ccnd01_directiva_familiar->execute($sql_1);

	$this->set('fami',$result_1);

	$this->set('profesion',$profesion = $this->cnmd06_profesiones->field('denominacion', $conditions = 'cod_profesion='.$result[0][0]['cod_profesion'], $order ="cod_profesion ASC"));
	$this->set('oficio',$oficio = $this->cnmd06_oficio->field('denominacion', $conditions = 'cod_oficio='.$result[0][0]['cod_ocupacion'], $order ="cod_oficio ASC"));


	$this->set('paren',$this->cnmd06_parentesco->findAll());


	$this->set('republica',$this->cugd01_republica->field('denominacion', $conditions ="cod_republica=".$this->Session->read('CC_republica'), $order ="cod_republica ASC"));
	$this->set('estado',$this->cugd01_estados->field('denominacion', $conditions ="cod_republica=".$this->Session->read('CC_republica')." and cod_estado=".$this->Session->read('CC_estado'), $order ="cod_estado ASC"));
	$this->set('municipio',$this->cugd01_municipios->field('denominacion', $conditions ="cod_republica=".$this->Session->read('CC_republica')." and cod_estado=".$this->Session->read('CC_estado')." and cod_municipio=".$this->Session->read('CC_municipio'), $order ="cod_municipio ASC"));
	$this->set('parroquia',$this->cugd01_parroquias->field('denominacion', $conditions ="cod_republica=".$this->Session->read('CC_republica')." and cod_estado=".$this->Session->read('CC_estado')." and cod_municipio=".$this->Session->read('CC_municipio')." and cod_parroquia=".$this->Session->read('CC_parroquia'), $order ="cod_parroquia ASC"));

    $sql="select * from cugd01_centros_poblados where cod_republica=".$this->Session->read('CC_republica')." and cod_estado=".$this->Session->read('CC_estado')." and cod_municipio=".$this->Session->read('CC_municipio')." and cod_parroquia=".$this->Session->read('CC_parroquia')." and cod_centro=".$this->Session->read('CC_centro');
	$datos=$this->cugd01_parroquias->execute($sql);
	$this->set('datos',$datos);

	$denominacion = $this->ccnd01_concejo_comunal->field('denominacion', $conditions , null);
	$this->set('seleccion_concejo', $this->Session->read('CC_concejo'));
	$this->set('denominacion_concejo', $denominacion);


    $condicion_cod_tipo  = "cod_tipo='".$result[0][0]['cod_tipo']."'   ";
	$deno_cod_tipo       = $this->ccnd01_tipo_directivo->field('denominacion', $condicion_cod_tipo , null);

 	$condicion_cod_cargo = "cod_tipo='".$result[0][0]['cod_tipo']."'  and  cod_cargo='".$result[0][0]['cod_cargo']."' ";
 	$deno_cod_cargo      = $this->ccnd01_cargos_directivos->field('denominacion', $condicion_cod_cargo , null);

 	$this->set('cod_tipo',  $result[0][0]['cod_tipo']);
 	$this->set('cod_cargo', $result[0][0]['cod_cargo']);

    $this->set('deno_cod_tipo',  $deno_cod_tipo);
	$this->set('deno_cod_cargo', $deno_cod_cargo);




	$vec=$this->cugd10_imagenes->findCount("cod_campo=11 and identificacion='".$cedula."'");
		if($vec!=0){
			$this->set('existe_imagen',true);
		}else{
			$this->set('existe_imagen',false);
		}

  $this->set('opcion', 1);
  $this->render("consulta");


 }//consultar

























function modificar($var1=null, $var2=null, $var3=null, $var4=null, $var5=null) {
	$this->layout="ajax";

	    $this->Session->delete("i");
	    $this->Session->delete("items1");
	    $this->Session->delete("contador");

	    $cod_republica = $this->Session->read('CC_republica');
 	 	$cod_estado    = $this->Session->read('CC_estado');
 	 	$cod_municipio = $this->Session->read('CC_municipio');
 	 	$cod_parroquia = $this->Session->read('CC_parroquia');
  	    $cod_centro    = $this->Session->read('CC_centro');
 	    $cod_concejo   = $this->Session->read('CC_concejo');

	    $cod_tipo  = $var1;
	    $cod_cargo = $var2;
	    $cedula    = $var3;
	    $pagina    = $var4;
	    $opcion    = $var5;


 	    $sql_concejo = " cod_republica='".$cod_republica."' and cod_estado='".$cod_estado."' and  cod_municipio='".$cod_municipio."' and  cod_parroquia='".$cod_parroquia."' and cod_centro='".$cod_centro."' and cod_concejo='".$cod_concejo."'  ";
		$Tfilas = $this->ccnd01_directiva->findCount($sql_concejo." and cod_tipo='".$cod_tipo."' and cod_cargo='".$cod_cargo."' and cedula_identidad = '".$cedula."'  ");
        $x      = $this->ccnd01_directiva->findAll(  $sql_concejo." and cod_tipo='".$cod_tipo."' and cod_cargo='".$cod_cargo."' and cedula_identidad = '".$cedula."'  ", null,"cod_tipo, cod_cargo, cedula_identidad ASC",1,1,null);
		$this->set('DATA',$x);
		$this->set('pagina',$pagina);

		$this->set('opcion',$opcion);

		$this->concatenaN($this->cnmd06_profesiones->generateList(null,'denominacion ASC', null, '{n}.cnmd06_profesiones.cod_profesion', '{n}.cnmd06_profesiones.denominacion'),'lista_profesion');
	    $this->concatenaN($this->cnmd06_oficio->generateList(null,'denominacion ASC', null, '{n}.cnmd06_oficio.cod_oficio', '{n}.cnmd06_oficio.denominacion'),'lista_oficio');


		 $this->concatena($this->cnmd06_parentesco->generateList(null,'cod_parentesco ASC', null, '{n}.cnmd06_parentesco.cod_parentesco', '{n}.cnmd06_parentesco.denominacion'),'parentesco');


         $condicion_cod_tipo = "";
	     $dir_cod_tipo=$this->ccnd01_tipo_directivo->generateList($condicion_cod_tipo,'denominacion ASC', null, '{n}.ccnd01_tipo_directivo.cod_tipo', '{n}.ccnd01_tipo_directivo.denominacion');
		 $dir_cod_tipo = $dir_cod_tipo != null ? $dir_cod_tipo : array();
		 $this->concatena($dir_cod_tipo, 'lista_cod_tipo');

		    $condicion_cod_cargo = "cod_tipo='".$cod_tipo."'   ";
		    $dir_cod_cargo=$this->ccnd01_cargos_directivos->generateList($condicion_cod_cargo,'denominacion ASC', null, '{n}.ccnd01_cargos_directivos.cod_cargo', '{n}.ccnd01_cargos_directivos.denominacion');
			$dir_cod_cargo = $dir_cod_cargo != null ? $dir_cod_cargo : array();
			$this->concatena($dir_cod_cargo, 'lista_cod_cargo');


	$cedula    = $x[0]["ccnd01_directiva"]["cedula_identidad"];
	$cod_tipo  = $x[0]["ccnd01_directiva"]["cod_tipo"];
	$cod_cargo = $x[0]["ccnd01_directiva"]["cod_cargo"];

	$sql="SELECT * from ccnd01_directiva where ".$sql_concejo." and cod_tipo='".$cod_tipo."' and cod_cargo='".$cod_cargo."' and cedula_identidad='".$cedula."' ";
	$result=$this->ccnd01_directiva->execute($sql);
	$this->set('perso',$result);


	$sql_1="SELECT * from ccnd01_directiva_familiar where ".$sql_concejo." and cod_tipo='".$cod_tipo."' and cod_cargo='".$cod_cargo."'  and cedula_directivo='".$cedula."' order by cedula_directivo asc";
	$result_1=$this->ccnd01_directiva_familiar->execute($sql_1);

	$this->set('fami',$result_1);

	$this->set('profesion',$profesion = $this->cnmd06_profesiones->field('denominacion', $conditions = 'cod_profesion='.$result[0][0]['cod_profesion'], $order ="cod_profesion ASC"));
	$this->set('oficio',   $oficio = $this->cnmd06_oficio->field('denominacion', $conditions = 'cod_oficio='.$result[0][0]['cod_ocupacion'], $order ="cod_oficio ASC"));


	$this->set('paren',$this->cnmd06_parentesco->findAll());


	$this->set('republica',$this->cugd01_republica->field('denominacion', $conditions ="cod_republica=".$this->Session->read('CC_republica'), $order ="cod_republica ASC"));
	$this->set('estado',$this->cugd01_estados->field('denominacion', $conditions ="cod_republica=".$this->Session->read('CC_republica')." and cod_estado=".$this->Session->read('CC_estado'), $order ="cod_estado ASC"));
	$this->set('municipio',$this->cugd01_municipios->field('denominacion', $conditions ="cod_republica=".$this->Session->read('CC_republica')." and cod_estado=".$this->Session->read('CC_estado')." and cod_municipio=".$this->Session->read('CC_municipio'), $order ="cod_municipio ASC"));
	$this->set('parroquia',$this->cugd01_parroquias->field('denominacion', $conditions ="cod_republica=".$this->Session->read('CC_republica')." and cod_estado=".$this->Session->read('CC_estado')." and cod_municipio=".$this->Session->read('CC_municipio')." and cod_parroquia=".$this->Session->read('CC_parroquia'), $order ="cod_parroquia ASC"));

    $sql="select * from cugd01_centros_poblados where cod_republica=".$this->Session->read('CC_republica')." and cod_estado=".$this->Session->read('CC_estado')." and cod_municipio=".$this->Session->read('CC_municipio')." and cod_parroquia=".$this->Session->read('CC_parroquia')." and cod_centro=".$this->Session->read('CC_centro');
	$datos=$this->cugd01_parroquias->execute($sql);
	$this->set('datos',$datos);

	$denominacion = $this->ccnd01_concejo_comunal->field('denominacion', $conditions , null);
	$this->set('seleccion_concejo', $this->Session->read('CC_concejo'));
	$this->set('denominacion_concejo', $denominacion);


    $condicion_cod_tipo  = "cod_tipo='".$result[0][0]['cod_tipo']."'   ";
	$deno_cod_tipo       = $this->ccnd01_tipo_directivo->field('denominacion', $condicion_cod_tipo , null);

 	$condicion_cod_cargo = "cod_tipo='".$result[0][0]['cod_tipo']."'  and  cod_cargo='".$result[0][0]['cod_cargo']."' ";
 	$deno_cod_cargo      = $this->ccnd01_cargos_directivos->field('denominacion', $condicion_cod_cargo , null);

 	$this->set('cod_tipo',  $result[0][0]['cod_tipo']);
 	$this->set('cod_cargo', $result[0][0]['cod_cargo']);

    $this->set('deno_cod_tipo',  $deno_cod_tipo);
	$this->set('deno_cod_cargo', $deno_cod_cargo);




	$vec=$this->cugd10_imagenes->findCount("cod_campo=11 and identificacion='".$cedula."'");
		if($vec!=0){
			$this->set('existe_imagen',true);
		}else{
			$this->set('existe_imagen',false);
		}



	for($i=0; $i<count($result_1); $i++){
             $vec2[$i][0]=$result_1[$i][0]['cod_parentesco'];
		     $vec2[$i][1]=$result_1[$i][0]['cedula_pariente'];
			 $vec2[$i][2]=$result_1[$i][0]['apellidos_nombres'];
			 $vec2[$i][3]=cambia_fecha($result_1[$i][0]['fecha_nacimiento']);
			 $vec2[$i][4]=$result_1[$i][0]['sexo'];
			 $vec2[$i][5]=$result_1[$i][0]['trabaja'];
			 $vec2[$i][6]=$result_1[$i][0]['estudia'];
			 $vec2[$i][7]="si";
			 $vec2[$i][8] =$cod_tipo;
			 $vec2[$i][9] =$cod_cargo;
			 $vec2[$i][10]=$cedula;
			 $vec2[$i]["id"]=$i;
	}//fin for



   $_SESSION["items1"]=$vec2;
   $this->Session->write("i",$i);
   $this->Session->write("contador", ($i+1));


   $estado_civil= array('S'=>'Soltero(a)',
                        'C'=>'Casado(a)',
                        'D'=>'Divorciado(a)',
                        'V'=>'Viudo(a)',
                        'O'=>'Otro'
                       );

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



			$this->set('lista_estado_civil',         $estado_civil);
			$this->set('lista_estado_conservacion',  $estado_conservacion);
			$this->set('lista_vivienda',             $vivienda);
			$this->set('lista_misiones',             $misiones);
			$this->set('lista_tenencia_vivencia',    $tenencia_vivencia);



 }//consultar





function guardar_modificar($var1=null, $var2=null, $var3=null) {
 	$this->layout = "ajax";



 	 	$cod_republica = $this->Session->read('CC_republica');
 	 	$cod_estado    = $this->Session->read('CC_estado');
 	 	$cod_municipio = $this->Session->read('CC_municipio');
 	 	$cod_parroquia = $this->Session->read('CC_parroquia');
  	    $cod_centro    = $this->Session->read('CC_centro');
 	    $cod_concejo   = $this->Session->read('CC_concejo');

 	    $guardar[]=0;
	    $i=0;

if(!empty($this->data['ccnp01_directiva']['cod_tipo'])){
if(!empty($this->data['ccnp01_directiva']['cod_cargo'])){
if(!empty($this->data['ccnp01_directiva']['cedula_identidad'])){
if(!empty($this->data['ccnp01_directiva']['nacionalidad'])){
if(!empty($this->data['ccnp01_directiva']['nombre_apellidos'])){
if(!empty($this->data['ccnp01_directiva']['fecha_nacimiento'])){
if(!empty($this->data['ccnp01_directiva']['sexo'])){
if(!empty($this->data['ccnp01_directiva']['estado_civil'])){
if(!empty($this->data['ccnp01_directiva']['profesion'])){
if(!empty($this->data['ccnp01_directiva']['oficio'])){
if(!empty($this->data['ccnp01_directiva']['tipo_vivienda'])){
if(!empty($this->data['ccnp01_directiva']['tenencia_vivencia'])){
if(!empty($this->data['ccnp01_directiva']['anos_residencia'])){
if(!empty($this->data['ccnp01_directiva']['cod_mision'])){
if(!empty($this->data['ccnp01_directiva']['direccion'])){




	   if(!empty($this->data) && isset($_SESSION ["items1"]) && ($_SESSION ["items1"]!=null || $_SESSION ["items1"]!=array())){


	    if(!empty($this->data['ccnp01_directiva']['cedula_identidad'])){

		 	$direccion_habitacion          =  $this->data['ccnp01_directiva']['direccion'];
		 	$nacionalidad                  =  $this->data['ccnp01_directiva']['nacionalidad'];
		 	$cedula_identidad              =  $this->data['ccnp01_directiva']['cedula_identidad'];
		 	$apellidos_nombres             =  $this->data['ccnp01_directiva']['nombre_apellidos'];
		 	$fecha_nacimiento              =  $this->data['ccnp01_directiva']['fecha_nacimiento'];
		 	$sexo                          =  $this->data['ccnp01_directiva']['sexo'];
		 	$estado_civil                  =  $this->data['ccnp01_directiva']['estado_civil'];
		 	$cod_profesion                 =  $this->data['ccnp01_directiva']['profesion'];
		 	$cod_ocupacion                 =  $this->data['ccnp01_directiva']['oficio'];
		 	$cod_tipo                      =  $this->data['ccnp01_directiva']['cod_tipo'];
		 	$cod_cargo                     =  $this->data['ccnp01_directiva']['cod_cargo'];
		 	$cod_vivienda                  =  $this->data['ccnp01_directiva']['tipo_vivienda'];


		 	if(empty($this->data['ccnp01_directiva']['estado_conservacion'])){
		 		$estado_conservacion_vivienda=0;
		 	}else{
		 		$estado_conservacion_vivienda=$this->data['ccnp01_directiva']['estado_conservacion'];
		 	}

		 	if(empty($this->data['ccnp01_directiva']['fijos'])){
		 		$telefonos_fijos=0;
		 	}else{
		 		$telefonos_fijos=$this->data['ccnp01_directiva']['fijos'];
		 	}

		 	if(empty($this->data['ccnp01_directiva']['celulares'])){
		 		$telefonos_moviles=0;
		 	}else{
		 		$telefonos_moviles=$this->data['ccnp01_directiva']['celulares'];
		 	}


		 	if(empty($this->data['ccnp01_directiva']['peso'])){
		 		$peso=0;
		 	}else{
				$peso=$this->data['ccnp01_directiva']['peso'];
		 		$xp=explode(',',$peso);
		 		$valor1=strlen($xp[0]);
		 		$valor2=strlen(isset($xp[1]));
				if($valor1>3){
					$xp[0]=substr($xp[0],0,3);
				}

				if($valor2>3){
					$xp[1]=substr($xp[1],0,3);
				}
				if(isset($xp[1])){
					$peso=$xp[0].'.'.$xp[1];
				}else{
					$peso=$xp[0];
				}
		 	}

		 	if(empty($this->data['ccnp01_directiva']['estatura'])){
		 		$estatura=0;
		 	}else{
		 		$estatura=$this->Formato1($this->data['ccnp01_directiva']['estatura']);
		 	}


		 	if(empty($this->data['ccnp01_directiva']['grupo_sanguineo'])){
		 		$grupo_sanguineo=0;
		 	}else{
		 		$grupo_sanguineo=$this->data['ccnp01_directiva']['grupo_sanguineo'];
		 	}



		 	if(empty($this->data['ccnp01_directiva']['monto_alquiler'])){
		 		$monto_alquiler_hipoteca=0;
		 	}else{
		 		$monto_alquiler_hipoteca=$this->Formato1($this->data['ccnp01_directiva']['monto_alquiler']);
		 	}

		 	if(empty($this->data['ccnp01_directiva']['anos_residencia'])){
		 		$anos_residencia=0;
		 	}else{
		 		$anos_residencia=$this->data['ccnp01_directiva']['anos_residencia'];
		 	}

		 	if(empty($this->data['ccnp01_directiva']['tenencia_vivencia'])){
		 		$cod_tenencia_vivienda=0;
		 	}else{
		 		$cod_tenencia_vivienda=$this->data['ccnp01_directiva']['tenencia_vivencia'];
		 	}

            if(empty($this->data['ccnp01_directiva']['cod_mision'])){
		 		$cod_mision=0;
		 	}else{
		 		$cod_mision=$this->data['ccnp01_directiva']['cod_mision'];
		 	}



$sql_a = "cod_republica                = '".$cod_republica."'     and
		  cod_estado                   = '".$cod_estado."'        and
		  cod_municipio                = '".$cod_municipio."'     and
		  cod_parroquia                = '".$cod_parroquia."'     and
		  cod_centro                   = '".$cod_centro."'        and
		  cod_concejo                  = '".$cod_concejo."'       and
		  cod_tipo                     = '".$cod_tipo."'          and
		  cod_cargo                    = '".$cod_cargo."'         and
		  cedula_identidad             = '".$cedula_identidad."'  ";


$values_a = "

				  nacionalidad                 = '".$nacionalidad."',
				  apellidos_nombres            = '".$apellidos_nombres."',
				  fecha_nacimiento             = '".$fecha_nacimiento."',
				  sexo                         = '".$sexo."',
				  estado_civil                 = '".$estado_civil."',
				  peso                         = '".$peso."',
				  estatura                     = '".$estatura."',
				  grupo_sanguineo              = '".$grupo_sanguineo."',
				  cod_profesion                = '".$cod_profesion."',
				  cod_ocupacion                = '".$cod_ocupacion."',
				  cod_vivienda                 = '".$cod_vivienda."',
				  cod_tenencia_vivienda        = '".$cod_tenencia_vivienda."',
				  anos_residencia              = '".$anos_residencia."',
				  monto_alquiler_hipoteca      = '".$monto_alquiler_hipoteca."',
				  cod_mision                   = '".$cod_mision."',
				  direccion_habitacion         = '".$direccion_habitacion."',
				  telefonos_fijos              = '".$telefonos_fijos."',
				  telefonos_moviles            = '".$telefonos_moviles."',
				  estado_conservacion_vivienda = '".$estado_conservacion_vivienda."' ";



		 	$sql = " BEGIN; UPDATE ccnd01_directiva SET ".$values_a." where ".$sql_a."; ";
	   		$sw=$this->ccnd01_directiva_familiar->execute($sql);

				foreach($_SESSION ["items1"] as $guardar){
					if($guardar!=null){

					   $sql_b  = "cod_republica                = '".$cod_republica."'     and
								  cod_estado                   = '".$cod_estado."'        and
								  cod_municipio                = '".$cod_municipio."'     and
								  cod_parroquia                = '".$cod_parroquia."'     and
								  cod_centro                   = '".$cod_centro."'        and
								  cod_concejo                  = '".$cod_concejo."'       and
								  cod_tipo                     = '".$cod_tipo."'          and
								  cod_cargo                    = '".$cod_cargo."'         and
								  cedula_directivo             = '".$cedula_identidad."'  and
								  cod_parentesco               = '".$guardar[0]."'        and
								  cedula_pariente              = '".$guardar[1]."' ";



					   if($this->ccnd01_directiva_familiar->findCount($sql_b)==0){


					   	 $campos_b =  "   cod_republica,
										  cod_estado,
										  cod_municipio,
										  cod_parroquia,
										  cod_centro,
										  cod_concejo,
										  cod_tipo,
										  cod_cargo,
										  cedula_directivo,
										  cod_parentesco,
										  cedula_pariente,
										  apellidos_nombres,
										  fecha_nacimiento,
										  sexo,
										  trabaja,
										  estudia ";


                          $values_b =  "   '".$cod_republica."',
										  '".$cod_estado."',
										  '".$cod_municipio."',
										  '".$cod_parroquia."',
										  '".$cod_centro."',
										  '".$cod_concejo."',
										  '".$cod_tipo."',
										  '".$cod_cargo."',
										  '".$cedula_identidad."',
										  '".$guardar[0]."',
										  '".$guardar[1]."',
										  '".$guardar[2]."',
										  '".$guardar[3]."',
										  '".$guardar[4]."',
										  '".$guardar[5]."',
										  '".$guardar[6]."' ";

						   $sql_insert = "INSERT INTO ccnd01_directiva_familiar (".$campos_b.") VALUES(".$values_b."); "  ;

					   }else{

					   	   $values_b =  "
										  apellidos_nombres = '".$guardar[2]."',
										  fecha_nacimiento  = '".$guardar[3]."',
										  sexo              = '".$guardar[4]."',
										  trabaja           = '".$guardar[5]."',
										  estudia           = '".$guardar[6]."' ";

					   	   $sql_insert = "UPDATE ccnd01_directiva_familiar SET ".$values_b." where ".$sql_b."; ";

					   }//fin else

						   $sw2 = $this->ccnd01_directiva_familiar->execute($sql_insert);

					}//fin if guardar

				   $i++;

			     }//fin foreach



			if($sw>1 && $sw2>1){
				$this->ccnd01_directiva_familiar->execute("COMMIT");
				$this->set('Message_existe', 'REGISTRO GUARDADO CON EXITO');

				    if($var1==1){
				        echo" <script> ver_documento('/ccnp01_directiva/consulta_especifica/".$var3."','principal'); </script>";
				 	}else{
				        echo" <script> ver_documento('/ccnp01_directiva/consulta/".$var2."','principal'); </script>";
				 	}//fin else
	   		}else{
	   			$this->ccnd01_directiva_familiar->execute("ROLLBACK");
	   			$this->set('errorMessage', 'LOS DATOS no fueron guardados');
	   		}

	    }else{
		$this->set('errorMessage', 'DEBE INGRESAR TODOS LOS DATOS');
		}

	}else{
		$this->set('errorMessage', 'DEBE INGRESAR TODOS LOS DATOS');
	}

}else{ $this->set('errorMessage', 'DEBE INGRESAR la dirección'); }
}else{ $this->set('errorMessage', 'DEBE INGRESAR si es Beneficiario de una misión'); }
}else{ $this->set('errorMessage', 'DEBE INGRESAR los años de residencia'); }
}else{ $this->set('errorMessage', 'DEBE INGRESAR tenecia de la vivienda'); }
}else{ $this->set('errorMessage', 'DEBE INGRESAR el Tipo de vivienda'); }
}else{ $this->set('errorMessage', 'DEBE INGRESAR la ocupación'); }
}else{ $this->set('errorMessage', 'DEBE INGRESAR la profesión'); }
}else{ $this->set('errorMessage', 'DEBE INGRESAR el estado civil'); }
}else{ $this->set('errorMessage', 'DEBE INGRESAR el sexo'); }
}else{ $this->set('errorMessage', 'DEBE INGRESAR la fecha de nacimiento'); }
}else{ $this->set('errorMessage', 'DEBE INGRESAR los nombres y apellidos'); }
}else{ $this->set('errorMessage', 'DEBE INGRESAR la nacionalidad'); }
}else{ $this->set('errorMessage', 'DEBE INGRESAR la cédula de identidad'); }
}else{ $this->set('errorMessage', 'DEBE INGRESAR el código del cargo'); }
}else{ $this->set('errorMessage', 'DEBE INGRESAR el código del directivo'); }


	$this->funcion();
	$this->render("funcion");


 }// fin guardar







function eliminar($var1=null, $var2=null, $var3=null, $var4=null, $var5=null){
	  $this->layout = "ajax";

	    $cod_republica = $this->Session->read('CC_republica');
 	 	$cod_estado    = $this->Session->read('CC_estado');
 	 	$cod_municipio = $this->Session->read('CC_municipio');
 	 	$cod_parroquia = $this->Session->read('CC_parroquia');
  	    $cod_centro    = $this->Session->read('CC_centro');
 	    $cod_concejo   = $this->Session->read('CC_concejo');
 	    $cedula    = $var3;
	    $cod_tipo  = $var1;
	    $cod_cargo = $var2;

 	    $sql_concejo = " cod_republica='".$cod_republica."' and cod_estado='".$cod_estado."' and  cod_municipio='".$cod_municipio."' and  cod_parroquia='".$cod_parroquia."' and cod_centro='".$cod_centro."' and cod_concejo='".$cod_concejo."'  ";

				$x = $this->ccnd01_directiva->execute(" DELETE FROM ccnd01_directiva           WHERE ".$sql_concejo." and cod_tipo='".$cod_tipo."' and cod_cargo='".$cod_cargo."'  and cedula_identidad='".$cedula."'  ");
				$x2 = $this->ccnd01_directiva->execute("DELETE FROM ccnd01_directiva_familiar  WHERE ".$sql_concejo." and cod_tipo='".$cod_tipo."' and cod_cargo='".$cod_cargo."'  and cedula_directivo='".$cedula."'  ");

		 $this->set('Message_existe','registro eliminado con exito');

		 $Tfilas=$this->ccnd01_directiva->findCount($sql_concejo);

        if($Tfilas!=0){
        	                if($var5==1){
			 	                 $this->consulta();
							 }else{
							 	 $this->consulta($var4);
							 }//fin function

			   $this->render("consulta");
        }else{
                $this->index();
                $this->render("index");
        }//fin else






}//fin function










 }//Fin de la clase controller
 ?>