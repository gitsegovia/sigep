<?php
class Casp01AtencionSocialController extends AppController {
   var $name = 'casp01_atencion_social';
   var $uses = array('casd01_datos_personales','casd01_datos_familiares','cnmd06_profesiones','cnmd06_oficio','cnmd06_parentesco',
   					'cugd01_estados','cugd01_municipios','cugd01_parroquias','cugd01_centropoblados','cugd02_institucion','cugd02_dependencia',
   					'casd01_ayudas_cuerpo','casd01_tipo_ayuda','casd01_ayuda_detalles','casd01_evaluacion_ayuda','casd01_solicitud_ayuda','cugd10_imagenes',
   					'v_historia_solicitud_ayudas','v_casd01_datos_personales','v_casd01_solicitudes_ayudas');
   var $helpers = array('Html','Ajax','Javascript', 'Sisap');

function checkSession(){
				if (!$this->Session->check('Usuario')){
						$this->redirect('/salir/');
						exit();
				}else{
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




 function beforeFilter(){
 	$this->checkSession();
 	$paso=explode('/',$this->params['url']['url']);
 	if(!isset($_SESSION["ATS_autorizados"][$paso[0]]) || $_SESSION["ATS_autorizados"][$paso[0]]==2){
 		$this->Session->write('errorMessage', 'usted no esta autorizado para operar este programa');
 		$this->redirect('modulos/vacio');
 	}
 }


 function index(){
 	$this->layout ="ajax";
	$this->limpiar_lista();

 	$nacionalidad= array('1'=>'Venezolana','2'=>'Extranjero');
	$this->concatena($nacionalidad, 'nacionalidad');

	$sexo= array('1'=>'Masculino','2'=>'Femenino');
	$this->concatena($sexo, 'sexo');

	$estado_civil= array('1'=>'Soltero(a)','2'=>'Casado(a)','3'=>'Divorciado(a)','4'=>'Viudo(a)','5'=>'Otro');
	$this->concatena($estado_civil, 'estado_civil');

	$this->concatena($this->cnmd06_profesiones->generateList(null,'cod_profesion ASC', null, '{n}.cnmd06_profesiones.cod_profesion', '{n}.cnmd06_profesiones.denominacion'),'profesion');

	$this->concatena($this->cnmd06_oficio->generateList(null,'cod_oficio ASC', null, '{n}.cnmd06_oficio.cod_oficio', '{n}.cnmd06_oficio.denominacion'),'oficio');

	$ambito= array('1'=>'Urbano','2'=>'Rural');
	$this->concatena($ambito, 'ambito');

	$zonificacion= array('1'=>'Urbanizacion','2'=>'Barrio','3'=>'Caserio','4'=>'Comuna','5'=>'Vialidad');
	$this->concatena($zonificacion, 'zonificacion');

	$vivienda= array('1'=>'Quinta','2'=>'Casa-Quinta','3'=>'Casa popular','4'=>'apartamento','5'=>'Vivienda popular','6'=>'Rancho','7'=>'Otro','8'=>'Ninguno');
	$this->concatena($vivienda, 'vivienda');

	$this->concatena($this->cnmd06_parentesco->generateList(null,'cod_parentesco ASC', null, '{n}.cnmd06_parentesco.cod_parentesco', '{n}.cnmd06_parentesco.denominacion'),'parentesco');

	$cond =" cod_republica=".$this->Session->read('SScodpresi');
	$lista=  $this->cugd01_estados->generateList($cond, 'cod_estado ASC', null, '{n}.cugd01_estados.cod_estado', '{n}.cugd01_estados.denominacion');
	$this->concatena($lista, 'estado');

	$this->data=null;

	echo"<script>
  	    document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal".$_SESSION["rand_atencion"]."_1').style.display='none';
        document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal".$_SESSION["rand_atencion"]."_2').style.display='none';
        document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal".$_SESSION["rand_atencion"]."_3').style.display='none';
        document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal".$_SESSION["rand_atencion"]."_4').style.display='none';
   </script>";

   //////////////////////////////////////////////////////////////////////////////////////////////////////
    $username=$this->Session->read('nom_usuario');
	$campos=$this->casd01_datos_personales->execute("select * from usuarios where username='$username'");
	if($campos[0][0]['cedula_identidad']!=null){
		$cedula_usuario=$campos[0][0]['cedula_identidad'];
	}else{
		$cedula_usuario=0;
	}
	$funcionario=$campos[0][0]['funcionario'];
	$this->set('user',$username);
	$this->set('ced',$cedula_usuario);
	$this->set('fun',$funcionario);
	//////////////////////////////////////////////////////////////////////////////////////////////////////

   if(isset($_SESSION['cedula_pestana_atencion'])){
   		$cedula= $this->Session->read('cedula_pestana_atencion');
		$this->busqueda_cedula($cedula);
		$this->render('busqueda_cedula');
   }

 }// fin index


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


function sumar_ci($vector,$ci_principal){
        if(isset($vector) && is_array($vector)){
	foreach($vector as $vv){
	   $ci=(int)$this->dv_ci2($vv);
	   $ci_principal=(int)$ci_principal;
	   if($ci_principal==$ci){
	      $vv2[]=$vv;
	   }
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

	if(empty($this->data['casp01']['parentesco']) || empty($this->data['casp01']['ape_nom']) || empty($this->data['casp01']['fecha_nacimiento_fami']) || empty($this->data['casp01']['sexo_fami']) || empty($this->data['casp01']['trabaja']) || empty($this->data['casp01']['estudia'])){
		$this->set('errorMessage', 'Debe ingresar los datos requeridos');
		if(!isset($_SESSION["contador"])){
 			$this->set('vacio','');
 		}
		return;
	}else if(empty($this->data['casp01']['cedula_fami']) && $this->edad($this->Cfecha($this->data['casp01']['fecha_nacimiento_fami'],"A-M-D"))>=15){
		$this->set('errorMessage', 'Debe ingresar la cedula del familiar');
		if(!isset($_SESSION["contador"])){
 			$this->set('vacio','');
 		}
		return;
	}else if($this->data['casp01']['cedula_fami']==$this->data['casp01']['cedula']){
		$this->set('errorMessage', 'no se podra agregar este familiar ya que posee la misma cedula que el registro principal');
		if(!isset($_SESSION["contador"])){
 			$this->set('vacio','');
 		}
		return;
	}



	 $ci_principal=$this->data['casp01']['cedula'];
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

	    $parentesco=$this->data['casp01']['parentesco'];
	    $ape_nom=$this->data['casp01']['ape_nom'];
	    $fecha=$this->data['casp01']['fecha_nacimiento_fami'];
	    $sexo_fami=$this->data['casp01']['sexo_fami'];
	    $trabaja=$this->data['casp01']['trabaja'];
	    $estudia=$this->data['casp01']['estudia'];


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

}//fin funcu¡ions



function limpiar_lista () {
	$this->layout = "ajax";
	$this->Session->delete("items1");
	$this->Session->delete("i");
	$this->Session->delete("contador");
}


function eliminar_items ($id) {
	$this->layout = "ajax";
	$paren=$this->cnmd06_parentesco->findAll();
	$this->set('paren',$paren);
	$_SESSION["items1"][$id]=null;
	$NL=0;
	$codigos1=array();
	foreach($_SESSION ["items1"] as $codigos){
       if($codigos[0]!=null){

       		$codigos1[$NL][0]=$codigos[0];
       		$codigos1[$NL][1]=$codigos[1];
       		$codigos1[$NL][2]=$codigos[2];
       		$codigos1[$NL][3]=$codigos[3];
       		$codigos1[$NL][4]=$codigos[4];
       		$codigos1[$NL][5]=$codigos[5];
       		$codigos1[$NL][6]=$codigos[6];
       		$codigos1[$NL]['id']=$NL;
			$NL++;
       }

	}
    $_SESSION["contador"]=$_SESSION["contador"]-1;
    $_SESSION["items1"]=array();
    $_SESSION["items1"]=$codigos1;
}



function busqueda_cedula($var=null){
	$this->layout="ajax";

if($var!=null){
	$sql="SELECT * from casd01_datos_personales where cedula_identidad='$var'";
	$result=$this->casd01_datos_personales->execute($sql);
	if($result!=null){



		$sql_1="SELECT * from casd01_datos_familiares where cedula_identidad='$var' order by cedula asc";
		$result_1=$this->casd01_datos_familiares->execute($sql_1);
		$this->set('perso',$result);
		$this->set('fami',$result_1);
		$this->set('profesion',$profesion = $this->cnmd06_profesiones->field('denominacion', $conditions = 'cod_profesion='.$result[0][0]['cod_profesion'], $order ="cod_profesion ASC"));
		$this->set('oficio',$oficio = $this->cnmd06_oficio->field('denominacion', $conditions = 'cod_oficio='.$result[0][0]['cod_oficio'], $order ="cod_oficio ASC"));
		$this->set('estado',$this->cugd01_estados->field('denominacion', $conditions ="cod_republica=".$this->Session->read('SScodpresi')." and cod_estado=".$result[0][0]['cod_estado'], $order ="cod_estado ASC"));
		$this->set('municipio',$this->cugd01_municipios->field('denominacion', $conditions ="cod_republica=".$this->Session->read('SScodpresi')." and cod_estado=".$result[0][0]['cod_estado']." and cod_municipio=".$result[0][0]['cod_municipio'], $order ="cod_municipio ASC"));
		$this->set('parroquia',$this->cugd01_parroquias->field('denominacion', $conditions ="cod_republica=".$this->Session->read('SScodpresi')." and cod_estado=".$result[0][0]['cod_estado']." and cod_municipio=".$result[0][0]['cod_municipio']." and cod_parroquia=".$result[0][0]['cod_parroquia'], $order ="cod_parroquia ASC"));
		$this->set('centro',$this->cugd01_centropoblados->field('denominacion', $conditions ="cod_republica=".$this->Session->read('SScodpresi')." and cod_estado=".$result[0][0]['cod_estado']." and cod_municipio=".$result[0][0]['cod_municipio']." and cod_parroquia=".$result[0][0]['cod_parroquia']." and cod_centro=".$result[0][0]['cod_centro_poblado'], $order ="cod_centro ASC"));
		$this->set('institucion',$this->cugd02_institucion->field('denominacion', $conditions ="cod_tipo_institucion=".$result[0][0]['cod_tipo_inst']." and cod_institucion=".$result[0][0]['cod_inst'], $order ="cod_institucion ASC"));
		$this->set('dependencia',$this->cugd02_dependencia->field('denominacion', $conditions ="cod_tipo_institucion=".$result[0][0]['cod_tipo_inst']." and cod_institucion=".$result[0][0]['cod_inst']." and cod_dependencia=".$result[0][0]['cod_dep'], $order ="cod_institucion ASC"));
		$this->set('paren',$this->cnmd06_parentesco->findAll());
		$cargar=false;

		$sql2="select * from v_historia_solicitud_ayudas where cedula_identidad='$var' order by cod_presi,cod_entidad,cod_tipo_inst,cod_inst,cod_dep,cedula_identidad,numero_ocacion asc";
		$dato2=$this->v_historia_solicitud_ayudas->execute($sql2);
		if($dato2!=null){
			$this->set('dato2',$dato2);
		}else{
			$this->set('dato2','');
		}

		$this->Session->delete('cedula_pestana_atencion');
	  	$this->Session->write('cedula_pestana_atencion',$var);

			echo"<script>
	      	    document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal".$_SESSION["rand_atencion"]."_1').style.display='block';
	            document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal".$_SESSION["rand_atencion"]."_2').style.display='block';
	            document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal".$_SESSION["rand_atencion"]."_3').style.display='block';
	            document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal".$_SESSION["rand_atencion"]."_4').style.display='block';
	       </script>";
	////////////////////////////////////////////////////////////////////////////////////////////////////

	$this->set('user',$result[0][0]['username']);
	$this->set('cedu',$result[0][0]['cedula_usuario']);
	$this->set('fun',$result[0][0]['nombre_usuario']);
	//////////////////////////////////////////////////////////////////////////////////////////////////////
	}else{
		$cargar=true;
	}
}else{
	$cargar=true;
}

if($cargar==true){
	$this->set('perso',null);
	$this->set('ced',$var);
	$nacionalidad= array('1'=>'Venezolana','2'=>'Extranjero');
	$this->set('nacionalidad',$nacionalidad);

	$sexo= array('1'=>'Masculino','2'=>'Femenino');
	$this->set('sexo',$sexo);

	$estado_civil= array('1'=>'Soltero(a)','2'=>'Casado(a)','3'=>'Divorciado(a)','4'=>'Viudo(a)','5'=>'Otro');
	$this->set('estado_civil',$estado_civil);

	$this->concatena($this->cnmd06_profesiones->generateList(null,'cod_profesion ASC', null, '{n}.cnmd06_profesiones.cod_profesion', '{n}.cnmd06_profesiones.denominacion'),'profesion');

	$this->concatena($this->cnmd06_oficio->generateList(null,'cod_oficio ASC', null, '{n}.cnmd06_oficio.cod_oficio', '{n}.cnmd06_oficio.denominacion'),'oficio');

	$ambito= array('1'=>'Urbano','2'=>'Rural');
	$this->set('ambito',$ambito);

	$zonificacion= array('1'=>'Urbanizacion','2'=>'Barrio','3'=>'Caserio','4'=>'Comuna','5'=>'Vialidad');
	$this->set('zonificacion',$zonificacion);

	$vivienda= array('1'=>'Quinta','2'=>'Casa-Quinta','3'=>'Casa popular','4'=>'apartamento','5'=>'Vivienda popular','6'=>'Rancho','7'=>'Otro','8'=>'Ninguno');
	$this->set('vivienda',$vivienda);

	$tenencia= array('1'=>'Ninguna','2'=>'Propia','3'=>'Alquilada','4'=>'De un familiar','5'=>'Al cuidado','6'=>'Hipotecada','7'=>'Invadida');
	$this->set('tenencia',$tenencia);

	$misiones= array('1'=>'Ninguna','2'=>'Robinsón I','3'=>'Robinsón II','4'=>'Ribas','5'=>'Sucre','6'=>'Negra Hipolita',
					'7'=>'José Gregório Hernández','8'=>'Barrio Adentro','9'=>'Mercal','10'=>'Arbol','11'=>'Ciencia',
					'12'=>'Miranda','13'=>'Guaicaipuro','14'=>'Piar','15'=>'Vuelvan Caras','16'=>'Identidad','17'=>'Che Guevara',
					'18'=>'Cultura','19'=>'Esperanza','20'=>'Habitad','21'=>'Madres del Barrio','22'=>'Milagro','23'=>'Niñas y Niños del Barrio',
					'24'=>'Zamora','25'=>'AMOR MAYOR','26'=>'HIJOS DE VENEZUELA','27'=>'AGROVENEZUELA','28'=>'SABER Y TRABAJO',
					'29'=>'VIVIENDA VENEZUELA','30'=>'BARRIO ADENTRO DEPORTIVO','31'=>'BARRIO ADENTRO SALUD','32'=>'MISIÓN SONRISA',
					'33'=>'BARRIO TRICOLOR','34'=>'MADRES DEL BARRIO');
	$this->set('misiones',$misiones);

	$this->concatena($this->cnmd06_parentesco->generateList(null,'cod_parentesco ASC', null, '{n}.cnmd06_parentesco.cod_parentesco', '{n}.cnmd06_parentesco.denominacion'),'parentesco');

	$cond =" cod_republica=".$this->Session->read('SScodpresi');
	$lista=  $this->cugd01_estados->generateList($cond, 'cod_estado ASC', null, '{n}.cugd01_estados.cod_estado', '{n}.cugd01_estados.denominacion');
	$this->concatena($lista, 'estado');
	echo"<script>
  	    document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal".$_SESSION["rand_atencion"]."_1').style.display='none';
        document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal".$_SESSION["rand_atencion"]."_2').style.display='none';
        document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal".$_SESSION["rand_atencion"]."_3').style.display='none';
        document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal".$_SESSION["rand_atencion"]."_4').style.display='none';
   </script>";

   //////////////////////////////////////////////////////////////////////////////////////////////////////
    $username=$this->Session->read('nom_usuario');
	$campos=$this->casd01_datos_personales->execute("select * from usuarios where username='$username'");
	if($campos[0][0]['cedula_identidad']!=null){
		$cedula_usuario=$campos[0][0]['cedula_identidad'];
	}else{
		$cedula_usuario=0;
	}
	$funcionario=$campos[0][0]['funcionario'];
	$this->set('user',$username);
	$this->set('cedu',$cedula_usuario);
	$this->set('fun',$funcionario);
	//////////////////////////////////////////////////////////////////////////////////////////////////////
}

$vec=$this->cugd10_imagenes->findCount("cod_campo=11 and identificacion='".$var."'");
if($vec!=0){
	$this->set('existe_imagen',true);
}else{
	$this->set('existe_imagen',false);
}

$this->limpiar_lista();


}// fin busqueda_cedula


function consulta_historial_solicitudes($presi=null,$entidad=null,$tipo_inst=null,$inst=null,$dep=null,$cedula=null,$tipo_ayuda=null,$ocacion=null,$evaluacion=null,$ayuda=null){
$this->layout="ajax";

	if($evaluacion==null && $ayuda==null){
		$datos=$this->casd01_solicitud_ayuda->execute("select * from casd01_solicitud_ayuda where cod_presi=".$presi." and cod_entidad=".$entidad." and cod_tipo_inst=".$tipo_inst." and cod_inst=".$inst." and cod_dep=".$dep." and cedula_identidad=".$cedula." and cod_tipo_ayuda=".$tipo_ayuda." and numero_ocacion=".$ocacion);
		$this->set('muestra',1);
	}else if($evaluacion!=null && $ayuda==null){
		$datos1=$this->casd01_solicitud_ayuda->execute("select * from casd01_solicitud_ayuda where cod_presi=".$presi." and cod_entidad=".$entidad." and cod_tipo_inst=".$tipo_inst." and cod_inst=".$inst." and cod_dep=".$dep." and cedula_identidad=".$cedula." and cod_tipo_ayuda=".$tipo_ayuda." and numero_ocacion=".$ocacion);
		$datos=$this->casd01_evaluacion_ayuda->execute("select * from casd01_evaluacion_ayuda where cod_presi=".$presi." and cod_entidad=".$entidad." and cod_tipo_inst=".$tipo_inst." and cod_inst=".$inst." and cod_dep=".$dep." and cedula_identidad=".$cedula." and cod_tipo_ayuda=".$tipo_ayuda." and numero_ocacion=".$ocacion." and numero_documento_evaluacion=".$evaluacion);
		$this->set('muestra',2);
		$this->set('datos1',$datos1);
	}else if($evaluacion!=null && $ayuda!=null){
		$datos1=$this->casd01_solicitud_ayuda->execute("select * from casd01_solicitud_ayuda where cod_presi=".$presi." and cod_entidad=".$entidad." and cod_tipo_inst=".$tipo_inst." and cod_inst=".$inst." and cod_dep=".$dep." and cedula_identidad=".$cedula." and cod_tipo_ayuda=".$tipo_ayuda." and numero_ocacion=".$ocacion);
		$datos2=$this->casd01_ayudas_cuerpo->execute("select * from casd01_ayudas_cuerpo where cod_presi=".$presi." and cod_entidad=".$entidad." and cod_tipo_inst=".$tipo_inst." and cod_inst=".$inst." and cod_dep=".$dep." and cedula_identidad=".$cedula." and cod_tipo_ayuda=".$tipo_ayuda." and numero_ocacion=".$ocacion." and numero_documento_evaluacion=".$evaluacion." and numero_documento_ayuda=".$ayuda);
		$datos=$this->casd01_evaluacion_ayuda->execute("select * from casd01_evaluacion_ayuda where cod_presi=".$presi." and cod_entidad=".$entidad." and cod_tipo_inst=".$tipo_inst." and cod_inst=".$inst." and cod_dep=".$dep." and cedula_identidad=".$cedula." and cod_tipo_ayuda=".$tipo_ayuda." and numero_ocacion=".$ocacion." and numero_documento_evaluacion=".$evaluacion." and numero_documento_ayuda=".$ayuda);
		$datos3=$this->casd01_ayuda_detalles->execute("select * from casd01_ayuda_detalles where cod_presi=".$presi." and cod_entidad=".$entidad." and cod_tipo_inst=".$tipo_inst." and cod_inst=".$inst." and cod_dep=".$dep." and cedula_identidad=".$cedula." and cod_tipo_ayuda=".$tipo_ayuda." and numero_ocacion=".$ocacion." and numero_documento_evaluacion=".$evaluacion." and numero_documento_ayuda=".$ayuda);
		$this->set('muestra',3);
		$this->set('datos1',$datos1);
		$this->set('dato3',$datos3);
	}

	$this->set('datos',$datos);
	$this->set('ayu',$this->casd01_tipo_ayuda->FindAll());

}// fin consulta_historial_solicitudes



function buscar_datos($var1=null){
	$this->layout="ajax";
	$this->set("opcion",$var1);
	$this->Session->delete('pista');
}//fin buscar_ficha



function buscar_por_pista($var1=null, $var2=null, $var3=null){
$this->layout="ajax";
    if($var3==null){
    	$var2 = strtoupper($var2);
					$this->Session->write('pista', $var2);
					$Tfilas=$this->casd01_datos_personales->findCount("cedula_identidad::text LIKE '%$var2%' or upper(apellidos_nombres::text) LIKE upper('%".$var2."%') ");
					        if($Tfilas!=0){
					        	$pagina=1;
					        	$Tfilas=(int)ceil($Tfilas/100);
					        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
								$this->set('total_paginas',$Tfilas);
								$this->set('pagina_actual',$pagina);
								$this->set('ultimo',$Tfilas);
					     	    $datos_filas=$this->casd01_datos_personales->findAll("cedula_identidad::text LIKE '%$var2%' or upper(apellidos_nombres::text) LIKE upper('%".$var2."%')",null,"cedula_identidad ASC",100,1,null);
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
						$Tfilas=$this->casd01_datos_personales->findCount("cedula_identidad::text LIKE '%$var22%' or upper(apellidos_nombres::text) LIKE upper('%".$var22."%')");
						        if($Tfilas!=0){
						        	$pagina=$var3;
						        	$Tfilas=(int)ceil($Tfilas/100);
						        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
									$this->set('total_paginas',$Tfilas);
									$this->set('pagina_actual',$pagina);
									$this->set('ultimo',$Tfilas);
									$datos_filas=$this->casd01_datos_personales->findAll("cedula_identidad::text LIKE '%$var2%' or upper(apellidos_nombres::text) LIKE upper('%".$var22."%')",null,"cedula_identidad ASC",100,$pagina,null);
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


function seleccion_busqueda($opcion=null,$var=null){
	$this->layout="ajax";

	$sql="SELECT * from casd01_datos_personales where cedula_identidad='$var'";
	$result=$this->casd01_datos_personales->execute($sql);
	$sql_1="SELECT * from casd01_datos_familiares where cedula_identidad='$var' order by cedula asc";
	$result_1=$this->casd01_datos_familiares->execute($sql_1);
	$this->set('perso',$result);
	$this->set('fami',$result_1);
	$this->set('profesion',$profesion = $this->cnmd06_profesiones->field('denominacion', $conditions = 'cod_profesion='.$result[0][0]['cod_profesion'], $order ="cod_profesion ASC"));
	$this->set('oficio',$oficio = $this->cnmd06_oficio->field('denominacion', $conditions = 'cod_oficio='.$result[0][0]['cod_oficio'], $order ="cod_oficio ASC"));
	$this->set('estado',$this->cugd01_estados->field('denominacion', $conditions ="cod_republica=".$this->Session->read('SScodpresi')." and cod_estado=".$result[0][0]['cod_estado'], $order ="cod_estado ASC"));
	$this->set('municipio',$this->cugd01_municipios->field('denominacion', $conditions ="cod_republica=".$this->Session->read('SScodpresi')." and cod_estado=".$result[0][0]['cod_estado']." and cod_municipio=".$result[0][0]['cod_municipio'], $order ="cod_municipio ASC"));
	$this->set('parroquia',$this->cugd01_parroquias->field('denominacion', $conditions ="cod_republica=".$this->Session->read('SScodpresi')." and cod_estado=".$result[0][0]['cod_estado']." and cod_municipio=".$result[0][0]['cod_municipio']." and cod_parroquia=".$result[0][0]['cod_parroquia'], $order ="cod_parroquia ASC"));
	$this->set('centro',$this->cugd01_centropoblados->field('denominacion', $conditions ="cod_republica=".$this->Session->read('SScodpresi')." and cod_estado=".$result[0][0]['cod_estado']." and cod_municipio=".$result[0][0]['cod_municipio']." and cod_parroquia=".$result[0][0]['cod_parroquia']." and cod_centro=".$result[0][0]['cod_centro_poblado'], $order ="cod_centro ASC"));
	$this->set('institucion',$this->cugd02_institucion->field('denominacion', $conditions ="cod_tipo_institucion=".$result[0][0]['cod_tipo_inst']." and cod_institucion=".$result[0][0]['cod_inst'], $order ="cod_institucion ASC"));
	$this->set('dependencia',$this->cugd02_dependencia->field('denominacion', $conditions ="cod_tipo_institucion=".$result[0][0]['cod_tipo_inst']." and cod_institucion=".$result[0][0]['cod_inst']." and cod_dependencia=".$result[0][0]['cod_dep'], $order ="cod_institucion ASC"));
	$this->set('paren',$this->cnmd06_parentesco->findAll());

	$sql2="select * from v_historia_solicitud_ayudas where cedula_identidad='$var' order by cod_presi,cod_entidad,cod_tipo_inst,cod_inst,cod_dep,cedula_identidad,numero_ocacion asc";
	$dato2=$this->v_historia_solicitud_ayudas->execute($sql2);
	if($dato2!=null){
		$this->set('dato2',$dato2);
	}else{
		$this->set('dato2','');
	}


	$this->Session->delete('cedula_pestana_atencion');
  	$this->Session->write('cedula_pestana_atencion',$var);

		echo"<script>
      	    document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal".$_SESSION["rand_atencion"]."_1').style.display='block';
            document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal".$_SESSION["rand_atencion"]."_2').style.display='block';
            document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal".$_SESSION["rand_atencion"]."_3').style.display='block';
            document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal".$_SESSION["rand_atencion"]."_4').style.display='block';
       </script>";


	$vec=$this->cugd10_imagenes->findCount("cod_campo=11 and identificacion='".$var."'");
	if($vec!=0){
		$this->set('existe_imagen',true);
	}else{
		$this->set('existe_imagen',false);
	}
$this->limpiar_lista();

 ////////////////////////////////////////////////////////////////////////////////////////////////////

	$this->set('user',$result[0][0]['username']);
	$this->set('cedu',$result[0][0]['cedula_usuario']);
	$this->set('fun',$result[0][0]['nombre_usuario']);
	//////////////////////////////////////////////////////////////////////////////////////////////////////
}// fin seleccion_busqueda




function guardar($nomina=null,$cargo=null,$ficha=null) {
 	$this->layout = "ajax";
	if(!empty($this->data) && isset($_SESSION ["items1"]) && ($_SESSION ["items1"]!=null || $_SESSION ["items1"]!=array())){
		$cod_presi = $this->Session->read('SScodpresi');
	    $cod_entidad = $this->Session->read('SScodentidad');
	    $cod_tipo_inst = $this->Session->read('SScodtipoinst');
	    $cod_inst = $this->Session->read('SScodinst');
	    $cod_dep = $this->Session->read('SScoddep');
	    if(!empty($this->data['casp01']['cedula'])){
		 	$nacionalidad=$this->data['casp01']['nacionalidad'];
		 	$cedula=$this->data['casp01']['cedula'];
		 	$apellido_nombre=$this->data['casp01']['apellido_nombre'];
			$fecha_nacimiento=$this->data['casp01']['fecha_nacimiento'];
		 	$sexo=$this->data['casp01']['sexo'];
		 	$estado_civil=$this->data['casp01']['estado_civil'];
		 	if(empty($this->data['casp01']['peso'])){
		 		$peso=0;
		 	}else{
				$peso=$this->data['casp01']['peso'];
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

		 	if(empty($this->data['casp01']['estatura'])){
		 		$estatura=0;
		 	}else{
		 		$estatura=$this->Formato1($this->data['casp01']['estatura']);
		 	}
		 	$sangre=$this->data['casp01']['sangre'];
		 	$profesion=$this->data['casp01']['profesion'];
		 	$oficio=$this->data['casp01']['oficio'];
		 	$ambito=$this->data['casp01']['ambito'];
		 	$zonificacion=$this->data['casp01']['zonificacion'];
		 	$vivienda=$this->data['casp01']['vivienda'];

		 	if(empty($this->data['casp01']['tenencia'])){
		 		$tenencia=0;
		 	}else{
		 		$tenencia=$this->data['casp01']['tenencia'];
		 	}

		 	if(empty($this->data['casp01']['misiones'])){
		 		$misiones=0;
		 	}else{
		 		$misiones=$this->data['casp01']['misiones'];
		 	}

		 	if(empty($this->data['casp01']['ano'])){
		 		$ano=0;
		 	}else{
		 		$ano=$this->data['casp01']['ano'];
		 	}

		 	if(empty($this->data['casp01']['alquiler'])){
		 		$alquiler=0;
		 	}else{
		 		$alquiler=$this->Formato1($this->data['casp01']['alquiler']);
		 	}

		 	$estado=$this->data['casp01']['estado'];
		 	$cod_municipio=$this->data['casp01']['cod_municipio'];
		 	$cod_parroquia=$this->data['casp01']['cod_parroquia'];
		 	$cod_centro=$this->data['casp01']['cod_centro'];
		 	$direccion=$this->data['casp01']['direccion'];
		 	$fijos=$this->data['casp01']['fijos'];
		 	$celulares=$this->data['casp01']['celulares'];
		 	$fecha_inscripcion=date("d/m/Y");
		 	$guardar[]=0;
			$i=0;
			$username=$this->Session->read('nom_usuario');
			$campos=$this->casd01_datos_personales->execute("select * from usuarios where username='$username'");
			if($campos[0][0]['cedula_identidad']!=null){
				$cedula_usuario=$campos[0][0]['cedula_identidad'];
			}else{
				$cedula_usuario=0;
			}
			$funcionario=$campos[0][0]['funcionario'];
		 	$sql = "BEGIN;INSERT INTO casd01_datos_personales VALUES ('$cedula', '$nacionalidad', '$apellido_nombre', '$fecha_nacimiento', '$sexo', '$estado_civil',$peso,$estatura,'$sangre','$profesion','$oficio','$ambito','$zonificacion','$vivienda','$estado','$cod_municipio','$cod_parroquia','$cod_centro','$direccion','$fijos','$celulares','$fecha_inscripcion','$cod_presi','$cod_entidad','$cod_tipo_inst','$cod_inst','$cod_dep','$tenencia','$ano','$alquiler','$misiones','$username','$cedula_usuario','$funcionario')";
	   		$sw=$this->casd01_datos_personales->execute($sql);

				foreach($_SESSION ["items1"] as $guardar){
					if($guardar!=null){
						$sql_insert = "INSERT INTO casd01_datos_familiares VALUES('$cedula', '$guardar[0]', '$guardar[1]', '$guardar[2]', '$guardar[3]','$guardar[4]','$guardar[5]','$guardar[6]')";
						$sw2 = $this->casd01_datos_familiares->execute($sql_insert);
					}
				   $i++;
			     }
			if($sw>1 && $sw2>1){
				$this->casd01_datos_personales->execute("COMMIT");
				$this->set('Message_existe', 'REGISTRO EXITOSO');
	   		}else{
	   			$this->casd01_datos_personales->execute("ROLLBACK");
	   			$this->set('errorMessage', 'INSERCI&Oacute;N FALLIDA DE LOS DATOS');
	   		}

	    }else{
		$this->set('errorMessage', 'DEBE INGRESAR TODOS LOS DATOS');
		}

	}else{
		$this->set('errorMessage', 'DEBE INGRESAR TODOS LOS DATOS');
	}


	$this->busqueda_cedula($cedula);
	$this->render('busqueda_cedula');


 }// fin guardar




function guarda_familiares($cedula){
	$this->layout = "ajax";
    $paren=$this->cnmd06_parentesco->findAll();
	$this->set('paren',$paren);

	if(empty($this->data['casp01']['parentesco']) || empty($this->data['casp01']['ape_nom']) || empty($this->data['casp01']['fecha_nacimiento_fami']) || empty($this->data['casp01']['sexo_fami']) || empty($this->data['casp01']['trabaja']) || empty($this->data['casp01']['estudia'])){
		$this->set('errorMessage', 'Debe ingresar los datos requeridos');
 		$sql_1="SELECT * from casd01_datos_familiares where cedula_identidad='$cedula'  order by cedula asc";
		$result_1=$this->casd01_datos_familiares->execute($sql_1);
		$this->set('fami',$result_1);
		return;
	}else if(empty($this->data['casp01']['cedula_fami']) && $this->edad($this->Cfecha($this->data['casp01']['fecha_nacimiento_fami'],"A-M-D"))>=15){
		$this->set('errorMessage', 'Debe ingresar la cedula del familiar');
		$sql_1="SELECT * from casd01_datos_familiares where cedula_identidad='$cedula'  order by cedula asc";
		$result_1=$this->casd01_datos_familiares->execute($sql_1);
		$this->set('fami',$result_1);
		return;
	}else if($this->data['casp01']['cedula_fami']==$cedula){
		$this->set('errorMessage', 'no se podra agregar este familiar ya que posee la misma cedula que el registro principal');
		$sql_1="SELECT * from casd01_datos_familiares where cedula_identidad='$cedula'  order by cedula asc";
		$result_1=$this->casd01_datos_familiares->execute($sql_1);
		$this->set('fami',$result_1);
		return;
	}


	if(empty($this->data['casp01']['cedula_fami'])){
		 $s=$this->casd01_datos_familiares->execute("SELECT * from casd01_datos_familiares where cedula_identidad='$cedula' order by cedula asc");
		 $vector=array();
		for($i=0;$i<count($s);$i++){
			$vector[]=$s[$i][0]['cedula'];
		}
		$cedula_ide=$this->sumar_ci($vector,$cedula);
	}else if(!empty($this->data['casp01']['cedula_fami'])){
		$cedula_ide=$this->data['casp01']['cedula_fami'];
	}
	    $parentesco=$this->data['casp01']['parentesco'];
	    $ape_nom=$this->data['casp01']['ape_nom'];
	    $fecha=$this->data['casp01']['fecha_nacimiento_fami'];
	    $sexo_fami=$this->data['casp01']['sexo_fami'];
	    $trabaja=$this->data['casp01']['trabaja'];
	    $estudia=$this->data['casp01']['estudia'];
	    $verifica=$this->casd01_datos_familiares->FindCount("cedula_identidad='$cedula' and cedula='$cedula_ide'");
		if($verifica==0){
			 $sql_insert = "INSERT INTO casd01_datos_familiares VALUES('$cedula', '$parentesco', '$cedula_ide', '$ape_nom', '$fecha','$sexo_fami','$trabaja','$estudia')";
			 $sw2 = $this->casd01_datos_familiares->execute($sql_insert);
			 if($sw2>1){
			 	$this->set('Message_existe', 'FAMILIAR AGREGADO CON EXITO');
			 }else{
			 	$this->set('errorMessage', 'ERROR EN LA INSERCI&Oacute;N DEL DATO');
			 }
		}else{
			$this->set('errorMessage', 'EL FAMILIAR QUE DESEA AGREGAR YA EXISTE EN LA LISTA');
		}


		$sql_1="SELECT * from casd01_datos_familiares where cedula_identidad='$cedula' and 1=1 order by cedula asc";
		$result_1=$this->casd01_datos_familiares->execute($sql_1);
		$this->set('fami',$result_1);


}




function eliminar($cedula=null,$pagina=null){
	  $this->layout = "ajax";
      $cod_presi                =       $this->Session->read('SScodpresi');
	  $cod_entidad              =       $this->Session->read('SScodentidad');
	  $cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
	  $cod_inst                 =       $this->Session->read('SScodinst');
	  $cod_dep                  =       $this->Session->read('SScoddep');
	  $sw=$this->casd01_ayudas_cuerpo->FindCount('cedula_identidad='.$cedula);
	  $sw1=$this->casd01_evaluacion_ayuda->FindCount('cedula_identidad='.$cedula);
	  $sw2=$this->casd01_solicitud_ayuda->FindCount('cedula_identidad='.$cedula);
	  if($sw==0 && $sw1==0 && $sw2==0){
		  $x = $this->casd01_datos_personales->execute("BEGIN;DELETE FROM casd01_datos_personales  WHERE cedula_identidad='$cedula'");
		  $x2 = $this->casd01_datos_familiares->execute("DELETE FROM casd01_datos_familiares  WHERE cedula_identidad='$cedula'");
		  if($x>1 && $x2>1){
		  	$this->casd01_datos_familiares->execute("COMMIT");
		  	$this->set('Message_existe','registro eliminado con exito');
		  	 if($pagina!=null){
			  		$this->consulta($pagina);
			  		$this->render('consulta');
			  }else{
				  	$this->index();
		  			$this->render('index');
			  }
		  }else{
		  	$this->casd01_datos_familiares->execute("ROLLBACK");
		  	$this->set('errorMessage', 'EL DATO NO PUDO SER ELIMINADO');
		  	 if($pagina!=null){
		  		$this->consulta($pagina);
		  		$this->render('consulta');
			  }else{
				  	$this->seleccion_busqueda(2,$cedula);
				    $this->render('seleccion_busqueda');
			  }
		  }
	  }else{
		  $this->set('errorMessage', 'EL DATO NO PODRA SER ELIMINADO YA QUE ESTA SIENDO USADO POR OTRO PROGRAMA');
		  if($pagina!=null){
		  		$this->consulta($pagina);
		  		$this->render('consulta');
		  }else{
			  	$this->seleccion_busqueda(2,$cedula);
			    $this->render('seleccion_busqueda');
		  }
	  }


}//fin function



function eliminar_familiar($cedula_ide=null,$cedula=null){
	  $this->layout = "ajax";
      $cod_presi                =       $this->Session->read('SScodpresi');
	  $cod_entidad              =       $this->Session->read('SScodentidad');
	  $cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
	  $cod_inst                 =       $this->Session->read('SScodinst');
	  $cod_dep                  =       $this->Session->read('SScoddep');

	  $x2 = $this->casd01_datos_familiares->execute("DELETE FROM casd01_datos_familiares  WHERE cedula_identidad='$cedula_ide' and cedula='$cedula'");
}//fin function


function modificar($var=null,$pagina=null){
	  $this->layout = "ajax";
      $cod_presi                =       $this->Session->read('SScodpresi');
	  $cod_entidad              =       $this->Session->read('SScodentidad');
	  $cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
	  $cod_inst                 =       $this->Session->read('SScodinst');
	  $cod_dep                  =       $this->Session->read('SScoddep');

	    $sql="SELECT * from casd01_datos_personales where cedula_identidad='$var'";
		$result=$this->casd01_datos_personales->execute($sql);
		$sql_1="SELECT * from casd01_datos_familiares where cedula_identidad='$var' order by cedula asc";
		$result_1=$this->casd01_datos_familiares->execute($sql_1);
		$this->set('perso',$result);
		$this->set('fami',$result_1);
		$this->set('institucion',$this->cugd02_institucion->field('denominacion', $conditions ="cod_tipo_institucion=".$result[0][0]['cod_tipo_inst']." and cod_institucion=".$result[0][0]['cod_inst'], $order ="cod_institucion ASC"));
		$this->set('dependencia',$this->cugd02_dependencia->field('denominacion', $conditions ="cod_tipo_institucion=".$result[0][0]['cod_tipo_inst']." and cod_institucion=".$result[0][0]['cod_inst']." and cod_dependencia=".$result[0][0]['cod_dep'], $order ="cod_institucion ASC"));
		$this->set('paren',$this->cnmd06_parentesco->findAll());

		$nacionalidad= array('1'=>'Venezolana','2'=>'Extranjero');
		$this->set('nacionalidad',$nacionalidad);

		$sexo= array('1'=>'Masculino','2'=>'Femenino');
		$this->set('sexo',$sexo);

		$estado_civil= array('1'=>'Soltero(a)','2'=>'Casado(a)','3'=>'Divorciado(a)','4'=>'Viudo(a)','5'=>'Otro');
		$this->set('estado_civil',$estado_civil);

		$this->concatena($this->cnmd06_profesiones->generateList(null,'cod_profesion ASC', null, '{n}.cnmd06_profesiones.cod_profesion', '{n}.cnmd06_profesiones.denominacion'),'profesion');

		$this->concatena($this->cnmd06_oficio->generateList(null,'cod_oficio ASC', null, '{n}.cnmd06_oficio.cod_oficio', '{n}.cnmd06_oficio.denominacion'),'oficio');

		$tenencia= array('1'=>'Ninguna','2'=>'Propia','3'=>'Alquilada','4'=>'De un familiar','5'=>'Al cuidado','6'=>'Hipotecada','7'=>'Invadida');
		$this->set('tenencia',$tenencia);

		$misiones= array('1'=>'Ninguna','2'=>'Robinsón I','3'=>'Robinsón II','4'=>'Ribas','5'=>'Sucre','6'=>'Negra Hipolita',
					'7'=>'José Gregório Hernández','8'=>'Barrio Adentro','9'=>'Mercal','10'=>'Arbol','11'=>'Ciencia',
					'12'=>'Miranda','13'=>'Guaicaipuro','14'=>'Piar','15'=>'Vuelvan Caras','16'=>'Identidad','17'=>'Che Guevara',
					'18'=>'Cultura','19'=>'Esperanza','20'=>'Habitad','21'=>'Madres del Barrio','22'=>'Milagro','23'=>'Niñas y Niños del Barrio',
					'24'=>'Zamora','25'=>'AMOR MAYOR','26'=>'HIJOS DE VENEZUELA','27'=>'AGROVENEZUELA','28'=>'SABER Y TRABAJO',
					'29'=>'VIVIENDA VENEZUELA','30'=>'BARRIO ADENTRO DEPORTIVO','31'=>'BARRIO ADENTRO SALUD','32'=>'MISIÓN SONRISA',
					'33'=>'BARRIO TRICOLOR','34'=>'MADRES DEL BARRIO');
		$this->set('misiones',$misiones);

		$ambito= array('1'=>'Urbano','2'=>'Rural');
		$this->set('ambito',$ambito);

		$zonificacion= array('1'=>'Urbanizacion','2'=>'Barrio','3'=>'Caserio','4'=>'Comuna','5'=>'Vialidad');
		$this->set('zonificacion',$zonificacion);

		$vivienda= array('1'=>'Quinta','2'=>'Casa-Quinta','3'=>'Casa popular','4'=>'apartamento','5'=>'Vivienda popular','6'=>'Rancho','7'=>'Otro','8'=>'Ninguno');
		$this->set('vivienda',$vivienda);



		$this->concatena($this->cnmd06_parentesco->generateList(null,'cod_parentesco ASC', null, '{n}.cnmd06_parentesco.cod_parentesco', '{n}.cnmd06_parentesco.denominacion'),'parentesco');

		$cond =" cod_republica=".$this->Session->read('SScodpresi');
		$lista=  $this->cugd01_estados->generateList($cond, 'cod_estado ASC', null, '{n}.cugd01_estados.cod_estado', '{n}.cugd01_estados.denominacion');
		$this->concatena($lista, 'estado');

		$cond=" cod_republica=".$this->Session->read('SScodpresi')." and cod_estado=".$result[0][0]['cod_estado'];
		$lista1=  $this->cugd01_municipios->generateList($cond, 'cod_municipio ASC', null, '{n}.cugd01_municipios.cod_municipio', '{n}.cugd01_municipios.denominacion');
		$this->concatena($lista1, 'municipio');

		$cond =" cod_republica=".$this->Session->read('SScodpresi')." and cod_estado=".$result[0][0]['cod_estado']." and cod_municipio=".$result[0][0]['cod_municipio'];
		$lista2=  $this->cugd01_parroquias->generateList($cond, 'cod_parroquia ASC', null, '{n}.cugd01_parroquias.cod_parroquia', '{n}.cugd01_parroquias.denominacion');
		$this->concatena($lista2, 'parroquia');

		$cond =" cod_republica=".$this->Session->read('SScodpresi')." and cod_estado=".$result[0][0]['cod_estado']." and cod_municipio=".$result[0][0]['cod_municipio']." and cod_parroquia=".$result[0][0]['cod_parroquia'];
		$lista3=  $this->cugd01_centropoblados->generateList($cond, 'cod_centro ASC', null, '{n}.cugd01_centropoblados.cod_centro', '{n}.cugd01_centropoblados.denominacion');
		$this->concatena($lista3, 'centro');

		$this->Session->delete('cod1');
		$this->Session->delete('cod2');
		$this->Session->write('cod1',$result[0][0]['cod_estado']);
		$this->Session->write('cod2',$result[0][0]['cod_municipio']);

		$sql2="select * from v_historia_solicitud_ayudas where cedula_identidad='$var' order by cod_presi,cod_entidad,cod_tipo_inst,cod_inst,cod_dep,cedula_identidad,numero_ocacion asc";
		$dato2=$this->v_historia_solicitud_ayudas->execute($sql2);
		if($dato2!=null){
			$this->set('dato2',$dato2);
		}else{
			$this->set('dato2','');
		}


		if($pagina!=null)$this->set('pagina',$pagina);

		$vec=$this->cugd10_imagenes->findCount("cod_campo=11 and identificacion='".$var."'");
		if($vec!=0){
			$this->set('existe_imagen',true);
		}else{
			$this->set('existe_imagen',false);
		}

 //////////////////////////////////////////////////////////////////////////////////////////////////////

	$this->set('user',$result[0][0]['username']);
	$this->set('ced',$result[0][0]['cedula_usuario']);
	$this->set('fun',$result[0][0]['nombre_usuario']);
	//////////////////////////////////////////////////////////////////////////////////////////////////////

}//fin function




  function guardar_modificar($cedula=null,$pagina=null){
 	$this->layout="ajax";
 	$cod_presi                =       $this->Session->read('SScodpresi');
    $cod_entidad              =       $this->Session->read('SScodentidad');
    $cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
    $cod_inst                 =       $this->Session->read('SScodinst');
    $cod_dep                  =       $this->Session->read('SScoddep');
	if(!empty($this->data)){
	    if(!empty($this->data['casp01']['cedula'])){
		 	$nacionalidad=$this->data['casp01']['nacionalidad'];
		 	$apellido_nombre=$this->data['casp01']['apellido_nombre'];
		 	$fecha_nacimiento=$this->data['casp01']['fecha_nacimiento'];
		 	$sexo=$this->data['casp01']['sexo'];
		 	$estado_civil=$this->data['casp01']['estado_civil'];
			if(empty($this->data['casp01']['peso'])){
		 		$peso=0;
		 	}else{
				$peso=$this->data['casp01']['peso'];
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
		 	$estatura=$this->Formato1($this->data['casp01']['estatura']);
		 	$sangre=$this->data['casp01']['sangre'];
		 	$profesion=$this->data['casp01']['profesion'];
		 	$oficio=$this->data['casp01']['oficio'];
		 	$ambito=$this->data['casp01']['ambito'];
		 	$zonificacion=$this->data['casp01']['zonificacion'];
		 	$vivienda=$this->data['casp01']['vivienda'];

		 	if(empty($this->data['casp01']['tenencia'])){
		 		$tenencia=0;
		 	}else{
		 		$tenencia=$this->data['casp01']['tenencia'];
		 	}

		 	if(empty($this->data['casp01']['misiones'])){
		 		$misiones=0;
		 	}else{
		 		$misiones=$this->data['casp01']['misiones'];
		 	}

		 	if(empty($this->data['casp01']['ano'])){
		 		$ano=0;
		 	}else{
		 		$ano=$this->data['casp01']['ano'];
		 	}

	 		$alquiler=$this->Formato1($this->data['casp01']['alquiler']);

		 	$estado=$this->data['casp01']['estado'];
		 	$cod_municipio=$this->data['casp01']['cod_municipio'];
		 	$cod_parroquia=$this->data['casp01']['cod_parroquia'];
		 	$cod_centro=$this->data['casp01']['cod_centro'];
		 	$direccion=$this->data['casp01']['direccion'];
		 	$fijos=$this->data['casp01']['fijos'];
		 	$celulares=$this->data['casp01']['celulares'];
		 	$fecha_inscripcion=$this->data['casp01']['fecha_sist'];
			$i=0;
		 	$sql = "BEGIN;UPDATE casd01_datos_personales SET nacionalidad='$nacionalidad',apellidos_nombres='$apellido_nombre',fecha_nacimiento='$fecha_nacimiento',sexo='$sexo',estado_civil='$estado_civil',peso='$peso',estatura='$estatura',grupo_sanguineo='$sangre',cod_profesion='$profesion',cod_oficio='$oficio',cod_ambito='$ambito',cod_zona='$zonificacion',cod_vivienda='$vivienda',cod_estado='$estado',cod_municipio='$cod_municipio',cod_parroquia='$cod_parroquia',cod_centro_poblado='$cod_centro',direccion_habitacion='$direccion',telefonos_fijos='$fijos',telefonos_movil='$celulares',fecha_inscripcion='$fecha_inscripcion',cod_tenencia_vivienda='$tenencia',anos_residencia='$ano',monto_alquiler_hipoteca='$alquiler',cod_mision='$misiones' where cedula_identidad='$cedula'";
	   		$sw=$this->casd01_datos_personales->execute($sql);
	   		if($sw>1){
				$this->set('Message_existe','EL REGISTRO SE MODIFICO EXITOSAMENTE');
				$this->casd01_datos_personales->execute("COMMIT");
	   		}else{
				$this->set('errorMessage', 'LOS DATOS NO PUDIERON SER MODIFICADOS');
				$this->casd01_datos_personales->execute("ROLLBACK");
	   		}


	    }else{
			$this->set('errorMessage', 'DEBE INGRESAR TODOS LOS DATOS');
		}
	}else{
		$this->set('errorMessage', 'DEBE INGRESAR TODOS LOS DATOS');
	}

	if($pagina!=null){
		$this->consulta($pagina);
		$this->render('consulta');
	}else{
		$this->seleccion_busqueda(2,$cedula);
		$this->render('seleccion_busqueda');
	}

 }//guardar modificar



 function modificar_items($cedula_ide=null,$cedula=null,$i=null){
 	 $this->layout = "ajax";
 	$sql_1="SELECT * from casd01_datos_familiares where cedula_identidad='$cedula_ide' and cedula='$cedula'";
	$result_1=$this->casd01_datos_familiares->execute($sql_1);
	$this->set('fami',$result_1);

	$sexo= array('1'=>'Masculino','2'=>'Femenino');
	$this->concatena($sexo, 'sexo');

	$this->concatena($this->cnmd06_parentesco->generateList(null,'cod_parentesco ASC', null, '{n}.cnmd06_parentesco.cod_parentesco', '{n}.cnmd06_parentesco.denominacion'),'parentesco');

	$this->set('k',$i);

 }// fin modificar_items


function guardar_modificar_items($cedula_ide=null,$cedula=null,$i=null){
	$this->layout = "ajax";
	$parentesco=$this->data['casp01']['parentesco'.$i];
	$cedula_fami=$this->data['casp01']['cedula_fami'.$i];
	$ape_nom=$this->data['casp01']['ape_nom'.$i];
	$fecha_nacimiento_fami=$this->data['casp01']['fecha_nacimiento_fami'.$i];
	$sexo_fami=$this->data['casp01']['sexo_fami'.$i];
	$trabaja=$this->data['casp01']['trabaja'.$i];
	$estudia=$this->data['casp01']['estudia'.$i];

	$sql = "BEGIN;UPDATE casd01_datos_familiares SET cod_parentesco='$parentesco',cedula='$cedula_fami',apellidos_nombres='$ape_nom',fecha_nacimiento='$fecha_nacimiento_fami',sexo='$sexo_fami',trabaja='$trabaja',estudia='$estudia' where cedula_identidad='$cedula_ide' and cedula='$cedula'";
	$sw=$this->casd01_datos_familiares->execute($sql);
	if($sw>1){
		$this->set('Message_existe','DATOS DEL FAMILIAR MODIFICADOS EXITOSAMENTE');
		$this->casd01_datos_familiares->execute("COMMIT");
	}else{
		$this->set('errorMessage', 'LOS DATOS DEL FAMILIAR NO PUDIERON SER MODIFICADOS');
		$this->casd01_datos_familiares->execute("ROLLBACK");
	}

	$paren=$this->cnmd06_parentesco->findAll();
	$this->set('paren',$paren);

	$sql_1="SELECT * from casd01_datos_familiares where cedula_identidad='$cedula_ide' order by cedula asc";
	$result_1=$this->casd01_datos_familiares->execute($sql_1);
	$this->set('fami',$result_1);

}//fin guardar_items_modificar



function cancelar($cedula_ide=nulll){
    $this->layout = "ajax";
    $paren=$this->cnmd06_parentesco->findAll();
	$this->set('paren',$paren);

	$sql_1="SELECT * from casd01_datos_familiares where cedula_identidad='$cedula_ide' order by cedula asc";
	$result_1=$this->casd01_datos_familiares->execute($sql_1);
	$this->set('fami',$result_1);

}//fin cancelar





function consulta($pagina=null) {
	$this->layout="ajax";

	if(isset($pagina)){
		$Tfilas=$this->casd01_datos_personales->findCount();
        if($Tfilas!=0){
        	$x=$this->casd01_datos_personales->findAll(null,null,"cedula_identidad ASC",1,$pagina,null);

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
		$Tfilas=$this->casd01_datos_personales->findCount();

        if($Tfilas!=0){
        	$x=$this->casd01_datos_personales->findAll(null,null,"cedula_identidad ASC",1,$pagina,null);
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

	$cedula= $x[0]["casd01_datos_personales"]["cedula_identidad"];
	$sql="SELECT * from casd01_datos_personales where cedula_identidad='$cedula'";
	$result=$this->casd01_datos_personales->execute($sql);
	$sql_1="SELECT * from casd01_datos_familiares where cedula_identidad='$cedula' order by cedula asc";
	$result_1=$this->casd01_datos_familiares->execute($sql_1);
	$this->set('perso',$result);
	$this->set('fami',$result_1);
	$this->set('profesion',$profesion = $this->cnmd06_profesiones->field('denominacion', $conditions = 'cod_profesion='.$result[0][0]['cod_profesion'], $order ="cod_profesion ASC"));
	$this->set('oficio',$oficio = $this->cnmd06_oficio->field('denominacion', $conditions = 'cod_oficio='.$result[0][0]['cod_oficio'], $order ="cod_oficio ASC"));
	$this->set('estado',$this->cugd01_estados->field('denominacion', $conditions ="cod_republica=".$this->Session->read('SScodpresi')." and cod_estado=".$result[0][0]['cod_estado'], $order ="cod_estado ASC"));
	$this->set('municipio',$this->cugd01_municipios->field('denominacion', $conditions ="cod_republica=".$this->Session->read('SScodpresi')." and cod_estado=".$result[0][0]['cod_estado']." and cod_municipio=".$result[0][0]['cod_municipio'], $order ="cod_municipio ASC"));
	$this->set('parroquia',$this->cugd01_parroquias->field('denominacion', $conditions ="cod_republica=".$this->Session->read('SScodpresi')." and cod_estado=".$result[0][0]['cod_estado']." and cod_municipio=".$result[0][0]['cod_municipio']." and cod_parroquia=".$result[0][0]['cod_parroquia'], $order ="cod_parroquia ASC"));
	$this->set('centro',$this->cugd01_centropoblados->field('denominacion', $conditions ="cod_republica=".$this->Session->read('SScodpresi')." and cod_estado=".$result[0][0]['cod_estado']." and cod_municipio=".$result[0][0]['cod_municipio']." and cod_parroquia=".$result[0][0]['cod_parroquia']." and cod_centro=".$result[0][0]['cod_centro_poblado'], $order ="cod_centro ASC"));
	$this->set('institucion',$this->cugd02_institucion->field('denominacion', $conditions ="cod_tipo_institucion=".$result[0][0]['cod_tipo_inst']." and cod_institucion=".$result[0][0]['cod_inst'], $order ="cod_institucion ASC"));
	$this->set('dependencia',$this->cugd02_dependencia->field('denominacion', $conditions ="cod_tipo_institucion=".$result[0][0]['cod_tipo_inst']." and cod_institucion=".$result[0][0]['cod_inst']." and cod_dependencia=".$result[0][0]['cod_dep'], $order ="cod_institucion ASC"));
	$this->set('paren',$this->cnmd06_parentesco->findAll());

	$sql2="select * from v_historia_solicitud_ayudas where cedula_identidad='$cedula' order by cod_presi,cod_entidad,cod_tipo_inst,cod_inst,cod_dep,cedula_identidad,numero_ocacion asc";
	$dato2=$this->v_historia_solicitud_ayudas->execute($sql2);
	if($dato2!=null){
		$this->set('dato2',$dato2);
	}else{
		$this->set('dato2','');
	}


	$vec=$this->cugd10_imagenes->findCount("cod_campo=11 and identificacion='".$cedula."'");
		if($vec!=0){
			$this->set('existe_imagen',true);
		}else{
			$this->set('existe_imagen',false);
		}
	 $this->Session->delete('cedula_pestana_atencion');
	  $this->Session->write('cedula_pestana_atencion',$cedula);
	echo"<script>
  	    document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal".$_SESSION["rand_atencion"]."_1').style.display='block';
        document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal".$_SESSION["rand_atencion"]."_2').style.display='block';
        document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal".$_SESSION["rand_atencion"]."_3').style.display='block';
        document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal".$_SESSION["rand_atencion"]."_4').style.display='block';
   </script>";

    //////////////////////////////////////////////////////////////////////////////////////////////////////

	$this->set('user',$x[0]["casd01_datos_personales"]["username"]);
	$this->set('ced',$x[0]["casd01_datos_personales"]["cedula_usuario"]);
	$this->set('fun',$x[0]["casd01_datos_personales"]["nombre_usuario"]);
	//////////////////////////////////////////////////////////////////////////////////////////////////////

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






function ver_estado($vare = null){
	$this->layout = "ajax";
	if($vare!=null){
		$this->set('vare', $vare);
		$estado = $this->v_casd01_datos_personales->findAll($this->SQLCA()." and cod_estado=".$vare,"DISTINCT cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_estado, deno_cod_estado",null, 1);
		$municipios = $this->v_casd01_datos_personales->generateList($this->SQLCA()." and cod_estado=".$vare,'deno_cod_municipio ASC', null, '{n}.v_casd01_datos_personales.cod_municipio', '{n}.v_casd01_datos_personales.deno_cod_municipio');
			if(!empty($municipios))
				$this->concatena($municipios, 'municipios');
			else
				$this->set('municipios', array());

		if(!empty($estado)){
			echo "<script>
				document.getElementById('codt_estado').value='".mascara($estado[0]['v_casd01_datos_personales']['cod_estado'],2)."';
				document.getElementById('denot_estado').value='".$estado[0]['v_casd01_datos_personales']['deno_cod_estado']."';
			</script>";
		}else{
			echo "<script>
				document.getElementById('codt_estado').value='';
				document.getElementById('denot_estado').value='';
			</script>";
		}
	}else{
			echo "<script>
				document.getElementById('codt_estado').value='';
				document.getElementById('denot_estado').value='';
			</script>";
	}
}


function ver_municipio($vare = null, $varm = null){
	$this->layout = "ajax";
	if($varm!=null){
		$municipio = $this->v_casd01_datos_personales->findAll($this->SQLCA()." and cod_estado=".$vare." and cod_municipio=".$varm,"DISTINCT cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_estado, cod_municipio, deno_cod_municipio",null, 1);
		if(!empty($municipio)){
			echo "<script>
				document.getElementById('codt_municipio').value='".mascara($municipio[0]['v_casd01_datos_personales']['cod_municipio'],2)."';
				document.getElementById('denot_municipio').value='".$municipio[0]['v_casd01_datos_personales']['deno_cod_municipio']."';
			</script>";
		}else{
			echo "<script>
				document.getElementById('codt_municipio').value='';
				document.getElementById('denot_municipio').value='';
			</script>";
		}
	}else{
			echo "<script>
				document.getElementById('codt_municipio').value='';
				document.getElementById('denot_municipio').value='';
			</script>";
	}
}


	function relacion_cumpleano($opcion = null){
		if($opcion=='no'){

		    $this->layout = "ajax";
		    $this->set('ir', 'pdf');
		    $this->set('opcion', 1);
		    $this->set('entidad_federal', $this->Session->read('entidad_federal'));

			$cod_presi = $this->Session->read('SScodpresi');
			$cod_entidad = $this->Session->read('SScodentidad');
			$cod_tipo_inst = $this->Session->read('SScodtipoinst');
			$cod_inst = $this->Session->read('SScodinst');
			$cod_dep = $this->Session->read('SScoddep');
			$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep;

			$estados = $this->v_casd01_datos_personales->generateList($condicion,'deno_cod_estado ASC', null, '{n}.v_casd01_datos_personales.cod_estado', '{n}.v_casd01_datos_personales.deno_cod_estado');
			if(!empty($estados))
				$this->concatena($estados, 'estados');
			else
				$this->set('estados', array());

		}else if($opcion=='pdf'){

			$this->layout = "pdf";
			$this->set('opcion', 2);

			$cod_presi = $this->Session->read('SScodpresi');
			$cod_entidad = $this->Session->read('SScodentidad');
			$cod_tipo_inst = $this->Session->read('SScodtipoinst');
			$cod_inst = $this->Session->read('SScodinst');
			$cod_dep = $this->Session->read('SScoddep');
			$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep;

			$cod_estado = $this->data['casp01_atencion_social']['cod_estado'];
			$cod_municipio = $this->data['casp01_atencion_social']['cod_municipio'];
			$mes_cumple = $this->data['casp01_atencion_social']['mes_cumple'];
			$dia_cumple = $this->data['casp01_atencion_social']['dia_cumple'];
			$ano_mes = $mes_cumple."-".$dia_cumple;

			$datos = $this->v_casd01_datos_personales->findAll($condicion." and cod_estado=".$cod_estado." and cod_municipio=".$cod_municipio." and substr(fecha_nacimiento::text, 6, 5)::text = '$ano_mes'", "deno_cod_estado, deno_cod_municipio, direccion_habitacion, cedula_identidad, nacionalidad, apellidos_nombres, fecha_nacimiento, edad, genero, estado_civil, telefonos_fijos, mision",'apellidos_nombres ASC');
			$this->set('modelo', 'v_casd01_datos_personales');
			$this->set('datos', $datos);
		}//fin else
	} // Fin Function relacion_cumpleano






function ver_estado2($vare = null){
	$this->layout = "ajax";
	if($vare!=null){
		$this->set('vare', $vare);
		$estado = $this->v_casd01_solicitudes_ayudas->findAll($this->SQLCA()." and cod_estado=".$vare,"DISTINCT cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_estado, deno_cod_estado",null, 1);
		$municipios = $this->v_casd01_solicitudes_ayudas->generateList($this->SQLCA()." and cod_estado=".$vare,'deno_cod_municipio ASC', null, '{n}.v_casd01_solicitudes_ayudas.cod_municipio', '{n}.v_casd01_solicitudes_ayudas.deno_cod_municipio');
			if(!empty($municipios))
				$this->concatena($municipios, 'municipios');
			else
				$this->set('municipios', array());

		if(!empty($estado)){
			echo "<script>
				document.getElementById('codt_estado').value='".mascara($estado[0]['v_casd01_solicitudes_ayudas']['cod_estado'],2)."';
				document.getElementById('denot_estado').value='".$estado[0]['v_casd01_solicitudes_ayudas']['deno_cod_estado']."';
			</script>";
		}else{
			echo "<script>
				document.getElementById('codt_estado').value='';
				document.getElementById('denot_estado').value='';
			</script>";
		}
	}else{
			echo "<script>
				document.getElementById('codt_estado').value='';
				document.getElementById('denot_estado').value='';
			</script>";
	}
}


function ver_municipio2($vare = null, $varm = null){
	$this->layout = "ajax";
	if($varm!=null){
		$municipio = $this->v_casd01_solicitudes_ayudas->findAll($this->SQLCA()." and cod_estado=".$vare." and cod_municipio=".$varm,"DISTINCT cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_estado, cod_municipio, deno_cod_municipio",null, 1);
		if(!empty($municipio)){
			echo "<script>
				document.getElementById('codt_municipio').value='".mascara($municipio[0]['v_casd01_solicitudes_ayudas']['cod_municipio'],2)."';
				document.getElementById('denot_municipio').value='".$municipio[0]['v_casd01_solicitudes_ayudas']['deno_cod_municipio']."';
			</script>";
		}else{
			echo "<script>
				document.getElementById('codt_municipio').value='';
				document.getElementById('denot_municipio').value='';
			</script>";
		}
	}else{
			echo "<script>
				document.getElementById('codt_municipio').value='';
				document.getElementById('denot_municipio').value='';
			</script>";
	}
}



	function relacion_ayudas($opcion = null){
		if($opcion=='no'){

		    $this->layout = "ajax";
		    $this->set('ir', 'pdf');
		    $this->set('opcion', 1);
		    $this->set('entidad_federal', $this->Session->read('entidad_federal'));

			$cod_presi = $this->Session->read('SScodpresi');
			$cod_entidad = $this->Session->read('SScodentidad');
			$cod_tipo_inst = $this->Session->read('SScodtipoinst');
			$cod_inst = $this->Session->read('SScodinst');
			$cod_dep = $this->Session->read('SScoddep');
			$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep;

			$estados = $this->v_casd01_solicitudes_ayudas->generateList($condicion,'deno_cod_estado ASC', null, '{n}.v_casd01_solicitudes_ayudas.cod_estado', '{n}.v_casd01_solicitudes_ayudas.deno_cod_estado');
			if(!empty($estados))
				$this->concatena($estados, 'estados');
			else
				$this->set('estados', array());

		}else if($opcion=='pdf'){

			$this->layout = "pdf";
			$this->set('opcion', 2);

			$cod_presi = $this->Session->read('SScodpresi');
			$cod_entidad = $this->Session->read('SScodentidad');
			$cod_tipo_inst = $this->Session->read('SScodtipoinst');
			$cod_inst = $this->Session->read('SScodinst');
			$cod_dep = $this->Session->read('SScoddep');
			$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep;

			$cod_estado = $this->data['casp01_atencion_social']['cod_estado'];
			$cod_municipio = $this->data['casp01_atencion_social']['cod_municipio'];
			$fecha_desde = $this->data['casp01_atencion_social']['fecha_desde'];
			$fecha_hasta = $this->data['casp01_atencion_social']['fecha_hasta'];
			$edad_desde = $this->data['casp01_atencion_social']['edad_desde'];
			$edad_hasta = $this->data['casp01_atencion_social']['edad_hasta'];
			$genero = $this->data['casp01_atencion_social']['genero'];

			$datos = $this->v_casd01_solicitudes_ayudas->findAll($condicion." and cod_estado=".$cod_estado." and cod_municipio=".$cod_municipio." and (fecha_solicitud BETWEEN '$fecha_desde' AND '$fecha_hasta') and (edad BETWEEN '$edad_desde' AND '$edad_hasta') and UPPER(genero)='$genero'", "ayuda_solicitada, fecha_solicitud, deno_cod_estado, deno_cod_municipio, cedula_identidad, nacionalidad, apellidos_nombres, fecha_nacimiento, edad, genero, mision",'apellidos_nombres ASC');
			$this->set('modelo', 'v_casd01_solicitudes_ayudas');
			$this->set('datos', $datos);
		}//fin else
	} // Fin Function relacion_ayudas




	function relacion_tipos_ayudas($opcion = null){
		if($opcion=='no'){

		    $this->layout = "ajax";
		    $this->set('ir', 'pdf');
		    $this->set('opcion', 1);
		    $this->set('entidad_federal', $this->Session->read('entidad_federal'));

			$cod_presi = $this->Session->read('SScodpresi');
			$cod_entidad = $this->Session->read('SScodentidad');
			$cod_tipo_inst = $this->Session->read('SScodtipoinst');
			$cod_inst = $this->Session->read('SScodinst');
			$cod_dep = $this->Session->read('SScoddep');
			$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep;

			$estados = $this->v_casd01_solicitudes_ayudas->generateList($condicion,'deno_cod_estado ASC', null, '{n}.v_casd01_solicitudes_ayudas.cod_estado', '{n}.v_casd01_solicitudes_ayudas.deno_cod_estado');
			if(!empty($estados))
				$this->concatena($estados, 'estados');
			else
				$this->set('estados', array());


			$tipos_ayudas = $this->v_casd01_solicitudes_ayudas->generateList($condicion,'denominacion ASC', null, '{n}.v_casd01_solicitudes_ayudas.cod_tipo_ayuda', '{n}.v_casd01_solicitudes_ayudas.denominacion');
			if(!empty($tipos_ayudas))
				$this->concatena_tres_digitos($tipos_ayudas, 'tipos_ayudas');
			else
				$this->set('tipos_ayudas', array());

		}else if($opcion=='pdf'){

			$this->layout = "pdf";
			$this->set('opcion', 2);

			$cod_presi = $this->Session->read('SScodpresi');
			$cod_entidad = $this->Session->read('SScodentidad');
			$cod_tipo_inst = $this->Session->read('SScodtipoinst');
			$cod_inst = $this->Session->read('SScodinst');
			$cod_dep = $this->Session->read('SScoddep');
			$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep;

			$cod_estado = $this->data['casp01_atencion_social']['cod_estado'];
			$cod_municipio = $this->data['casp01_atencion_social']['cod_municipio'];
			$fecha_desde = $this->data['casp01_atencion_social']['fecha_desde'];
			$fecha_hasta = $this->data['casp01_atencion_social']['fecha_hasta'];
			$tipo_ayuda = $this->data['casp01_atencion_social']['tipo_ayuda'];

			$datos = $this->v_casd01_solicitudes_ayudas->findAll($condicion." and cod_tipo_ayuda='$tipo_ayuda' and cod_estado=".$cod_estado." and cod_municipio=".$cod_municipio." and (fecha_solicitud BETWEEN '$fecha_desde' AND '$fecha_hasta')", "denominacion, ayuda_solicitada, fecha_solicitud, deno_cod_estado, deno_cod_municipio, cedula_identidad, nacionalidad, apellidos_nombres, fecha_nacimiento, edad, genero, mision",'apellidos_nombres ASC');
			$this->set('modelo', 'v_casd01_solicitudes_ayudas');
			$this->set('datos', $datos);
		}//fin else
	} // Fin Function relacion_tipos_ayudas

 }//Fin de la clase controller
 ?>