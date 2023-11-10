<?php
class Canp00DatosPersonalesAyudasController extends AppController {
   var $name = 'canp00_datos_personales_ayudas';
   var $uses = array('casd01_datos_personales','casd01_datos_familiares','cnmd06_profesiones','cnmd06_oficio','cnmd06_parentesco',
   					'cugd01_estados','cugd01_municipios','cugd01_parroquias','cugd01_centropoblados','cugd02_institucion','cugd02_dependencia',
   					'casd01_ayudas_cuerpo','casd01_tipo_ayuda','casd01_ayuda_detalles','casd01_evaluacion_ayuda','casd01_solicitud_ayuda','cugd10_imagenes',
   					'v_historia_solicitud_ayudas');
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


 function index(){
 	$this->layout ="ajax";

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


 }// fin index


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


	/////////////////////////////////////////////////////////////////////////////////

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
//	$this->concatena($nacionalidad, 'nacionalidad');
	$this->set('nacionalidad',$nacionalidad);

	$sexo= array('1'=>'Masculino','2'=>'Femenino');
//	$this->concatena($sexo, 'sexo');
	$this->set('sexo',$sexo);

	$estado_civil= array('1'=>'Soltero(a)','2'=>'Casado(a)','3'=>'Divorciado(a)','4'=>'Viudo(a)','5'=>'Otro');
//	$this->concatena($estado_civil, 'estado_civil');
	$this->set('estado_civil',$estado_civil);

	$this->concatena($this->cnmd06_profesiones->generateList(null,'cod_profesion ASC', null, '{n}.cnmd06_profesiones.cod_profesion', '{n}.cnmd06_profesiones.denominacion'),'profesion');

	$this->concatena($this->cnmd06_oficio->generateList(null,'cod_oficio ASC', null, '{n}.cnmd06_oficio.cod_oficio', '{n}.cnmd06_oficio.denominacion'),'oficio');

	$ambito= array('1'=>'Urbano','2'=>'Rural');
//	$this->concatena($ambito, 'ambito');
	$this->set('ambito',$ambito);

	$zonificacion= array('1'=>'Urbanizacion','2'=>'Barrio','3'=>'Caserio','4'=>'Comuna','5'=>'Vialidad');
//	$this->concatena($zonificacion, 'zonificacion');
	$this->set('zonificacion',$zonificacion);

	$vivienda= array('1'=>'Quinta','2'=>'Casa-Quinta','3'=>'Casa popular','4'=>'apartamento','5'=>'Vivienda popular','6'=>'Rancho','7'=>'Otro','8'=>'Ninguno');
//	$this->concatena($vivienda, 'vivienda');
	$this->set('vivienda',$vivienda);

	$tenencia= array('1'=>'Ninguna','2'=>'Propia','3'=>'Alquilada','4'=>'De un familiar','5'=>'Al cuidado','6'=>'Hipotecada','7'=>'Invadida');
	$this->set('tenencia',$tenencia);

	$misiones= array('1'=>'Ninguna','2'=>'Robinsón I','3'=>'Robinsón II','4'=>'Ribas','5'=>'Sucre','6'=>'Negra Hipolita',
					'7'=>'José Gregório Hernández','8'=>'Barrio Adentro','9'=>'Mercal','10'=>'Arbol','11'=>'Ciencia',
					'12'=>'Miranda','13'=>'Guaicaipuro','14'=>'Piar','15'=>'Vuelvan Caras','16'=>'Identidad','17'=>'Che Guevara',
					'18'=>'Cultura','19'=>'Esperanza','20'=>'Habitad','21'=>'Madres del Barrio','22'=>'Milagro','23'=>'Niñas y Niños del Barrio',
					'24'=>'Zamora');
	$this->set('misiones',$misiones);

	$this->concatena($this->cnmd06_parentesco->generateList(null,'cod_parentesco ASC', null, '{n}.cnmd06_parentesco.cod_parentesco', '{n}.cnmd06_parentesco.denominacion'),'parentesco');

	$cond =" cod_republica=".$this->Session->read('SScodpresi');
	$lista=  $this->cugd01_estados->generateList($cond, 'cod_estado ASC', null, '{n}.cugd01_estados.cod_estado', '{n}.cugd01_estados.denominacion');
	$this->concatena($lista, 'estado');


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



}// fin busqueda_cedula



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
//					if(is_int($var2)){$sql   = " (cod_tipo_nomina='$nomina' and denominacion_busqueda LIKE '%$var2%')  or   ";}else{ $sql = "";}
					$Tfilas=$this->casd01_datos_personales->findCount("cedula_identidad::text LIKE '%$var2%' or upper(apellidos_nombres::text) LIKE upper('%".$var2."%') ");
//					        echo "cod_tipo_nomina='$nomina' and denominacion_busqueda LIKE '%$var2%'";
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
//						     	    $datos_filas=$this->cscd01_catalogo->findAll($sql." (denominacion LIKE '%$var22%')  OR  (cod_snc LIKE '%$var22%')   ",null,"codigo_prod_serv ASC",100,$pagina,null);
									$datos_filas=$this->casd01_datos_personales->findAll("cedula_identidad::text LIKE '%$var2%' or upper(apellidos_nombres::text) LIKE upper('%".$var22."%')",null,"cedula_identidad ASC",100,$pagina,null);
							        $this->set("datosFILAS",$datos_filas);
							        $this->set('siguiente',$pagina+1);
									$this->set('anterior',$pagina-1);
									$this->bt_nav($Tfilas,$pagina);
						          }else{
						        	$this->set("datosFILAS",'');
						          }
                 }//fin else


//$this->set("cscd01_unidad_medida",$this->cscd01_unidad_medida->findAll());
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



	$vec=$this->cugd10_imagenes->findCount("cod_campo=11 and identificacion='".$var."'");
	if($vec!=0){
		$this->set('existe_imagen',true);
	}else{
		$this->set('existe_imagen',false);
	}


 ////////////////////////////////////////////////////////////////////////////////////////////////////

	$this->set('user',$result[0][0]['username']);
	$this->set('cedu',$result[0][0]['cedula_usuario']);
	$this->set('fun',$result[0][0]['nombre_usuario']);
	//////////////////////////////////////////////////////////////////////////////////////////////////////
}// fin seleccion_busqueda


function consulta_historial_solicitudes($presi=null,$entidad=null,$tipo_inst=null,$inst=null,$dep=null,$cedula=null,$tipo_ayuda=null,$ocacion=null,$evaluacion=null,$ayuda=null){
$this->layout="ajax";
//echo "holaaaaaaaaaaaa    ".$presi. "  ".$cedula."  ".$ocacion;

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


 }//Fin de la clase controller
 ?>