<?php
class Catp02FichaDatosController extends AppController{
	var $name = 'catp02_ficha_datos';
    var $uses = array('catd01_ano_ordenanza','catd02_ficha_datos','v_catd02_ficha_datos','catd02_ficha_tipologia','catd02_ficha_variables','catd02_ficha_tipologia','cugd90_municipio_defecto','cugd01_republica', 'cugd01_estados','cugd01_municipios','cugd01_parroquias','catd02_numero_archivo','catd02_numero_inscripcion','catd02_numero_ficha','cugd01_centropoblados','catd01_planta_valores_tierra','catd01_complemento_variable_principal','catd01_complemento_variable_primaria','catd01_valor_construccion','catd01_depreciacion_edificaciones','cugd10_imagenes');
    var $helpers = array('Html', 'Javascript', 'Ajax','Sisap');


function checkSession(){
				if (!$this->Session->check('Usuario')){
						$this->redirect('/salir/');
						exit();
				}else{
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

function SQLCA_S($ano=null){//sql para busqueda de codigos de arranque con y sin año
				 $sql_re = "cod_presi=".$this->verifica_SS(1)."  and    ";
				 $sql_re .= "cod_entidad=".$this->verifica_SS(2)."  and  ";
				 $sql_re .= "cod_tipo_inst=".$this->verifica_SS(3)."  and ";
				 $sql_re .= "cod_inst=".$this->verifica_SS(4)."   ";
				 return $sql_re;
}//fin funcion SQLCA

function SQLCAIN($ano=null){//sql para busqueda de codigos de arranque con y sin año
				 $sql_re = $this->verifica_SS(1).",";
				 $sql_re .= $this->verifica_SS(2).",";
				 $sql_re .=  $this->verifica_SS(3).",";
				 $sql_re .= $this->verifica_SS(4).",";
				 if($ano!=null){
					 $sql_re .= $this->verifica_SS(5).",";
						$sql_re .= $ano."";
				 }else{
					 $sql_re .=  $this->verifica_SS(5)."";
				 }
				 return $sql_re;
		}//fin funcion SQLCAIN


function index(){
	$this->layout  = "ajax";
	$ano_actual = $this->catd01_ano_ordenanza->ano_actual($this->SQLCA());
	$this->set('ano_actual',$ano_actual);
	$this->Session->delete('rand_expediente');
	$this->Session->delete('cod_republica');
	$this->Session->delete('cod_estado');
	$this->Session->delete('cod_municipio');
	$this->Session->delete('cod_estado2');
	$this->Session->delete('cod_municipio2');
}


function index2(){

	    $this->layout  = "ajax";
        $ano_actual = $this->catd01_ano_ordenanza->ano_actual($this->SQLCA());
	    $this->set('ano_actual',$ano_actual);
	    $can_mun_def=$this->cugd90_municipio_defecto->findCount($this->SQLCA_S());
	    if($can_mun_def!=0){
	        $mun_defecto=$this->cugd90_municipio_defecto->findAll($this->SQLCA_S());
	        $this->set("mun_defecto",$mun_defecto);
	        $cod_republica=$mun_defecto[0]["cugd90_municipio_defecto"]["cod_republica"];
	        $cod_estado=$mun_defecto[0]["cugd90_municipio_defecto"]["cod_estado"];
	        $cod_municipio=$mun_defecto[0]["cugd90_municipio_defecto"]["cod_municipio"];
	        $this->Session->write('cod_republica',$cod_republica);
	        $this->Session->write('cod_estado',$cod_estado);
	        $this->Session->write('cod_municipio',$cod_municipio);
	        $this->Session->write('cod_estado2',$cod_estado);
	        $this->Session->write('cod_municipio2',$cod_municipio);
	        $this->Session->delete("cod_parroquia2");
		    $sql_re = "cod_republica=".$this->Session->read('SScodpresi')."";
	 	    $denominacion =  $this->cugd01_estados->generateList($sql_re, 'denominacion ASC', null, '{n}.cugd01_estados.cod_estado', '{n}.cugd01_estados.denominacion');
	        $denominacion = $denominacion != null ? $denominacion : array();
			$this->concatena($denominacion, 'estado');
			$denominacion =  $this->cugd01_municipios->generateList($sql_re." and cod_estado=".$cod_estado, 'denominacion ASC', null, '{n}.cugd01_municipios.cod_municipio', '{n}.cugd01_municipios.denominacion');
			$denominacion = $denominacion != null ? $denominacion : array();
			$this->concatena($denominacion, 'municipio');
			$denominacion =  $this->cugd01_parroquias->generateList($sql_re." and cod_estado=".$cod_estado." and cod_municipio=".$cod_municipio, 'denominacion ASC', null, '{n}.cugd01_parroquias.cod_parroquia', '{n}.cugd01_parroquias.denominacion');
			$denominacion = $denominacion != null ? $denominacion : array();
			$this->concatena($denominacion, 'parroquia');
			$max_ficha=$this->catd02_numero_ficha->findCount($this->SQLCA());
	        $max_fichad=$this->catd02_numero_ficha->execute("SELECT numero FROM catd02_numero_ficha WHERE ".$this->SQLCA()." and situacion=1 ORDER BY numero ASC LIMIT 1");
	        if($max_ficha!=0){
	      	    $codigo=$max_fichad[0][0]["numero"];
	      	    $verifica_img = $this->cugd10_imagenes->findCount($this->SQLCA()." and cod_campo=21 and identificacion='$codigo'");
	      	    $this->set("verifica_img",$verifica_img);
	            $resultado=$this->catd02_numero_ficha->execute("UPDATE  catd02_numero_ficha SET situacion=2 WHERE ".$this->SQLCA()." and numero=".$codigo." and situacion<2");
		         if($resultado>1){
	               $this->set("numero_ficha",$codigo);
		         }else{
			        $this->set("errorMessage","Por favor Verifique el n&uacute;mero de control de ficha catastral");
			        $this->set("numero_ficha","");
			        $this->redirect("/catp02_numero_ficha/");
		      }
	       }else{
	      	 $this->set("errorMessage","Por favor Verifique el n&uacute;mero de control de ficha catastral");
			 $this->set("numero_ficha","");
			 $this->redirect("/catp02_numero_ficha/");
	       }

	        $max_ficha=$this->catd02_numero_inscripcion->findCount($this->SQLCA());
	        $max_fichad=$this->catd02_numero_inscripcion->execute("SELECT numero FROM catd02_numero_inscripcion WHERE ".$this->SQLCA()." and situacion=1 ORDER BY numero ASC LIMIT 1");
	        if($max_ficha!=0){
	      	    $codigo=$max_fichad[0][0]["numero"];
	            $resultado=$this->catd02_numero_inscripcion->execute("UPDATE  catd02_numero_inscripcion SET situacion=2 WHERE ".$this->SQLCA()." and numero=".$codigo." and situacion<2");
		         if($resultado>1){
	               $this->set("numero_inscripcion",$codigo);
		         }else{
			        $this->set("errorMessage","Por favor Verifique el n&uacute;mero de control de inscripcion catastral");
			        $this->set("numero_inscripcion","");
			        $this->redirect("/catp02_numero_inscripcion/");
		      }
	       }else{
	      	 $this->set("errorMessage","Por favor Verifique el n&uacute;mero de control de inscripcion catastral");
			 $this->set("numero_inscripcion","");
			 $this->redirect("/catp02_numero_inscripcion/");
	       }

	       $max_ficha=$this->catd02_numero_archivo->findCount($this->SQLCA());
	        $max_fichad=$this->catd02_numero_archivo->execute("SELECT numero FROM catd02_numero_archivo WHERE ".$this->SQLCA()." and situacion=1 ORDER BY numero ASC LIMIT 1");
	        if($max_ficha!=0){
	      	    $codigo=$max_fichad[0][0]["numero"];
	            $resultado=$this->catd02_numero_inscripcion->execute("UPDATE  catd02_numero_archivo SET situacion=2 WHERE ".$this->SQLCA()." and numero=".$codigo." and situacion<2");
		         if($resultado>1){
	               $this->set("numero_archivo",$codigo);
		         }else{
			        $this->set("errorMessage","Por favor Verifique el n&uacute;mero de control de archivo");
			        $this->set("numero_archivo","");
			        $this->redirect("/catp02_numero_archivo/");
		      }
	       }else{
	      	 $this->set("errorMessage","Por favor Verifique el n&uacute;mero de control de archivo");
			 $this->set("numero_archivo","");
			 $this->redirect("/catp02_numero_archivo/");
	       }
	        $_SESSION["ano_ordenanza"]=$ano_actual;
	        $cond =$this->SQLCA()." and ano_ordenanza=".$ano_actual;
			$lista=  $this->catd01_valor_construccion->generateList($cond, 'cod_tipo_construccion ASC', null, '{n}.catd01_valor_construccion.cod_tipo_construccion', '{n}.catd01_valor_construccion.denominacion_tipo');
			$this->concatena_sin_cero($lista, 'tipologia');
			if(isset($_SESSION ["items"])){
	            $this->Session->delete("i");
	            $this->Session->delete("items");
			}
			if(isset($_SESSION ["items_2"])){
	            $this->Session->delete("i2");
	            $this->Session->delete("items_2");
			}
			if(isset($_SESSION ["denom_catpd02_tpg"])){
				$this->Session->delete("denom_catpd02_tpg");
			}
			if(isset($_SESSION ["itemsn"])){
	            $this->Session->delete("n");
	            $this->Session->delete("itemsn");
			}
			if(isset($_SESSION ["itemsn2"])){
	            $this->Session->delete("n2");
	            $this->Session->delete("itemsn2");
			}
	    }else{
             $this->set('errorMessage','Registre el municipio por defecto por favor');
             $this->index();
             $this->render('index');
	    }

}//index2



function consulta($pagina=null, $codigo_ficha=null, $ced_rif_repre=null){

	$this->layout  = "ajax";
	$pagina=isset($pagina)?$pagina:1;
	$Tfilas = $this->v_catd02_ficha_datos->findCount($this->SQLCA());

if($Tfilas!=0){

	$this->seleccion('1', $codigo_ficha, $ced_rif_repre, $pagina, $Tfilas);
	$this->render("seleccion");

}else{
	$this->set('errorMessage','No se encontraron datos');
	$this->index();
	$this->render('index');
}
}


function seleccion($npag=null, $codigo_ficha=null, $ced_rif_repre=null, $pagina=null, $Tfilas=null){

	$this->layout  = "ajax";

	if($pagina!=null){
	    $datos_ficha = $this->v_catd02_ficha_datos->findAll($this->SQLCA(),null,"cod_ficha, cod_inscripcion, cod_control_archivo, ano_ordenanza ASC",1,$pagina,null);

if($Tfilas!=0){

	$this->set('pag_cant',$pagina.'/'.$Tfilas);
 	$this->set('ultimo',$Tfilas);
	$this->set('pag_num', $pagina);
	$this->set('pagina', $pagina);
	$this->set('siguiente',$pagina+1);
	$this->set('anterior',$pagina-1);
	$this->set('total_paginas',$Tfilas);

	$this->bt_nav($Tfilas,$pagina);
}

	}

	else
		$datos_ficha = $this->v_catd02_ficha_datos->findAll($this->SQLCA()." and cod_ficha=$codigo_ficha",null,null,1,1,null);


	if(!empty($datos_ficha)){

		$this->set("datos_ficha",$datos_ficha);
        $ano_actual = $this->catd01_ano_ordenanza->ano_actual($this->SQLCA());
	    $this->set('ano_actual',$ano_actual);

			if(isset($_SESSION ["items"])){
	            $this->Session->delete("i");
	            $this->Session->delete("items");
			}
			if(isset($_SESSION ["items_2"])){
	            $this->Session->delete("i2");
	            $this->Session->delete("items_2");
			}
			if(isset($_SESSION ["denom_catpd02_tpg"])){
				$this->Session->delete("denom_catpd02_tpg");
			}

		$codigo_ficha = $datos_ficha[0]['v_catd02_ficha_datos']['cod_ficha'];
		$ced_rif_repre = $datos_ficha[0]['v_catd02_ficha_datos']['cedula_rif_repre'];

	    /* $can_mun_def=$this->cugd90_municipio_defecto->findCount($this->SQLCA_S());
	    if($can_mun_def!=0) */

	        $mun_defecto=$this->cugd90_municipio_defecto->findAll($this->SQLCA_S());
	        /* $this->set("mun_defecto",$mun_defecto);
	        $cod_republica=$mun_defecto[0]["cugd90_municipio_defecto"]["cod_republica"];
	        $cod_estado=$mun_defecto[0]["cugd90_municipio_defecto"]["cod_estado"];
	        $cod_municipio=$mun_defecto[0]["cugd90_municipio_defecto"]["cod_municipio"]; */


	        $cod_republica = $mun_defecto[0]["cugd90_municipio_defecto"]["cod_republica"];
		// ** PARA selects Codigo Anterior:
	        $cod_estado = $datos_ficha[0]['v_catd02_ficha_datos']['cod_ant_edo'];
	        $cod_municipio = $datos_ficha[0]['v_catd02_ficha_datos']['cod_ant_mun'];
	        $cod_parroquia = $datos_ficha[0]['v_catd02_ficha_datos']['cod_ant_prr'];
	        // $cod_sector=$datos_ficha[0]['v_catd02_ficha_datos']['cod_ant_sec'];

		// *** PARA selects Codigo Catastral:
	        $cod_estado_c = $datos_ficha[0]['v_catd02_ficha_datos']['cod_act_edo'];
	        $cod_municipio_c = $datos_ficha[0]['v_catd02_ficha_datos']['cod_act_mun'];
	        $cod_parroquia_c = $datos_ficha[0]['v_catd02_ficha_datos']['cod_act_prr'];
	        // $cod_sector_c = $datos_ficha[0]['v_catd02_ficha_datos']['cod_act_sec'];

	        $this->Session->write('cod_republica',$cod_republica);
	        $this->Session->write('cod_estado',$cod_estado);
	        $this->Session->write('cod_municipio',$cod_municipio);
	        $this->Session->write('cod_estado2',$cod_estado_c);
	        $this->Session->write('cod_municipio2',$cod_municipio_c);
	        $this->Session->write('cod_parroquia2',$cod_parroquia_c);
	        // $this->Session->delete("cod_parroquia2");
		    $sql_re = "cod_republica=".$this->Session->read('SScodpresi')."";

	// PARA CODIGO ANTERIOR:

	 	    $denominacion =  $this->cugd01_estados->generateList($sql_re, 'denominacion ASC', null, '{n}.cugd01_estados.cod_estado', '{n}.cugd01_estados.denominacion');
	        $denominacion = $denominacion != null ? $denominacion : array();
			$this->concatena($denominacion, 'estado');
			$denominacion =  $this->cugd01_municipios->generateList($sql_re." and cod_estado=".$cod_estado, 'denominacion ASC', null, '{n}.cugd01_municipios.cod_municipio', '{n}.cugd01_municipios.denominacion');
			$denominacion = $denominacion != null ? $denominacion : array();
			$this->concatena($denominacion, 'municipio');
			$denominacion =  $this->cugd01_parroquias->generateList($sql_re." and cod_estado=".$cod_estado." and cod_municipio=".$cod_municipio, 'denominacion ASC', null, '{n}.cugd01_parroquias.cod_parroquia', '{n}.cugd01_parroquias.denominacion');
			$denominacion = $denominacion != null ? $denominacion : array();
			$this->concatena($denominacion, 'parroquia');
			$denominacion =  $this->cugd01_centropoblados->generateList($sql_re." and cod_estado=".$cod_estado." and cod_municipio=".$cod_municipio." and cod_parroquia=".$cod_parroquia, 'denominacion ASC', null, '{n}.cugd01_centropoblados.cod_centro', '{n}.cugd01_centropoblados.denominacion');
		    $denominacion = $denominacion != null ? $denominacion : array();
		    $this->concatena_tres_digitos($denominacion, 'sector');

	// PARA CODIGO CATASTRAL:

	 	    $denominacion =  $this->cugd01_estados->generateList($sql_re, 'denominacion ASC', null, '{n}.cugd01_estados.cod_estado', '{n}.cugd01_estados.denominacion');
	        $denominacion = $denominacion != null ? $denominacion : array();
			$this->concatena($denominacion, 'estado_c');
			$denominacion =  $this->cugd01_municipios->generateList($sql_re." and cod_estado=".$cod_estado_c, 'denominacion ASC', null, '{n}.cugd01_municipios.cod_municipio', '{n}.cugd01_municipios.denominacion');
			$denominacion = $denominacion != null ? $denominacion : array();
			$this->concatena($denominacion, 'municipio_c');
			$denominacion =  $this->cugd01_parroquias->generateList($sql_re." and cod_estado=".$cod_estado_c." and cod_municipio=".$cod_municipio_c, 'denominacion ASC', null, '{n}.cugd01_parroquias.cod_parroquia', '{n}.cugd01_parroquias.denominacion');
			$denominacion = $denominacion != null ? $denominacion : array();
			$this->concatena($denominacion, 'parroquia_c');
			$denominacion =  $this->cugd01_centropoblados->generateList($sql_re." and cod_estado=".$cod_estado_c." and cod_municipio=".$cod_municipio_c." and cod_parroquia=".$cod_parroquia_c, 'denominacion ASC', null, '{n}.cugd01_centropoblados.cod_centro', '{n}.cugd01_centropoblados.denominacion');
		    $denominacion = $denominacion != null ? $denominacion : array();
		    $this->concatena_tres_digitos($denominacion, 'sector_c');

	// VALOR PLANTA TIERRA:

			$denominacion =  $this->catd01_planta_valores_tierra->generateList($this->SQLCA()." and ".$sql_re." and cod_estado=".$cod_estado_c." and cod_municipio=".$cod_municipio_c." and cod_parroquia=".$cod_parroquia_c, 'denominacion_zona ASC', null, '{n}.catd01_planta_valores_tierra.cod_zona', '{n}.catd01_planta_valores_tierra.denominacion_zona');
		    $denominacion = $denominacion != null ? $denominacion : array();
		    $this->concatena($denominacion, 'valores_pt');

		$_SESSION["ano_ordenanza"]=$ano_actual;
		$_SESSION["radio_descripcionuso"]=$datos_ficha[0]['v_catd02_ficha_datos']['radio_descripcionuso'];

			$lista = $this->catd01_valor_construccion->generateList($this->SQLCA()." and ano_ordenanza=".$ano_actual, 'cod_tipo_construccion ASC', null, '{n}.catd01_valor_construccion.cod_tipo_construccion', '{n}.catd01_valor_construccion.denominacion_tipo');
			$this->concatena_sin_cero($lista, 'tipologia');

		if($datos_ficha[0]['v_catd02_ficha_datos']['registro_area_construccion']==0){
			/* echo "<script>
					document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal".$_SESSION["rand_expediente"]."_3').style.display='none';
					document.getElementById('capa_ei_divcatp').className='capa_inactiva_divcatp';
				</script>"; */

		}else{


	// ***+ CARGANDO DATOS POBLACION ADULTA +***

	$i=0;
	$datos_itemsn = $this->catd02_ficha_tipologia->execute("SELECT edad, sexo, nivel_educativo, profesion_oficio, lugar_trabajo, medio_transporte FROM catd02_poblacion_adulta WHERE ".$this->SQLCA()." and cod_ficha=$codigo_ficha ORDER BY edad ASC;");
	if(!empty($datos_itemsn)){
	foreach($datos_itemsn as $d_itemsn){
		 $vec_pa[$i][0]=$d_itemsn[0]["edad"];
		 $vec_pa[$i][1]=$d_itemsn[0]["sexo"];
		 $vec_pa[$i][2]=$d_itemsn[0]["nivel_educativo"];
		 $vec_pa[$i][3]=$d_itemsn[0]["profesion_oficio"];
		 $vec_pa[$i][4]=$d_itemsn[0]["lugar_trabajo"];
		 $vec_pa[$i][5]=$d_itemsn[0]["medio_transporte"];
		 $vec_pa[$i]["id"]=$i;
		 $i++;
	}

	$_SESSION["itemsn"]=$vec_pa;
	$this->Session->write("n",$i);
	}


	// ***+ CARGANDO DATOS POBLACION INFANTIL +***

	$i=0;
	$datos_itemsn = $this->catd02_ficha_tipologia->execute("SELECT edad, sexo, nivel_educativo, lugar_nombre_institucion, medio_transporte FROM catd02_poblacion_infantil WHERE ".$this->SQLCA()." and cod_ficha=$codigo_ficha ORDER BY edad ASC;");
	if(!empty($datos_itemsn)){
	foreach($datos_itemsn as $d_itemsn){
		 $vec_pa[$i][0]=$d_itemsn[0]["edad"];
		 $vec_pa[$i][1]=$d_itemsn[0]["sexo"];
		 $vec_pa[$i][2]=$d_itemsn[0]["nivel_educativo"];
		 $vec_pa[$i][3]=$d_itemsn[0]["lugar_nombre_institucion"];
		 $vec_pa[$i][4]=$d_itemsn[0]["medio_transporte"];
		 $vec_pa[$i]["id"]=$i;
		 $i++;
	}

	$_SESSION["itemsn2"]=$vec_pa;
	$this->Session->write("n2",$i);
	}


	// ***+ CARGANDO DATOS CONSTRUCCION +***

	$cod_tpg_aux='';
	$i=0;
	$datos_items2 = $this->catd02_ficha_tipologia->findAll($this->SQLCA()." and cod_ficha=$codigo_ficha", "cod_tipo, area_m2, valor_m2, valor_construccion, porcentaje_depre, valor_actual", "cod_tipo ASC");
	if(!empty($datos_items2)){
	foreach($datos_items2 as $d_items2){
		if($cod_tpg_aux!=$d_items2["catd02_ficha_tipologia"]["cod_tipo"]){
			$deno=$this->catd01_valor_construccion->findAll($this->SQLCA()." and ano_ordenanza=".$ano_actual." and cod_tipo_construccion='".$d_items2["catd02_ficha_tipologia"]["cod_tipo"]."'", "denominacion_tipo", null, 1, 1, null);
		}
		 $vec[$i][0]=$d_items2["catd02_ficha_tipologia"]["cod_tipo"];
		 $vec[$i][1]=$d_items2["catd02_ficha_tipologia"]["area_m2"];
		 $vec[$i][2]=$d_items2["catd02_ficha_tipologia"]["valor_m2"];
		 $vec[$i][3]=$d_items2["catd02_ficha_tipologia"]["porcentaje_depre"];
		 $vec[$i][4]=$d_items2["catd02_ficha_tipologia"]["valor_actual"];
		 $vec[$i][5]=$d_items2["catd02_ficha_tipologia"]["valor_construccion"];
		 $vec[$i]["id"]=$i;
		 $vec[$i]["deno"]=$deno[0]["catd01_valor_construccion"]["denominacion_tipo"];
		 $cod_tpg_aux=$d_items2["catd02_ficha_tipologia"]["cod_tipo"];
		 $i++;
	}

	$_SESSION["items_2"]=$vec;
	$this->Session->write("i2",$i);
	}

	// ****+ CARGANDO VARIABLES DE CONSTRUCCION +****

	$cod_tpg_aux='';
	$i=0;
	$datos_items2 = $this->catd02_ficha_variables->findAll($this->SQLCA()." and cod_ficha=$codigo_ficha", "cod_tipo, cod_variable_principal, cod_variable_primaria, monto_variable", "cod_tipo, cod_variable_principal, cod_variable_primaria ASC");
	if(!empty($datos_items2)){
	foreach($datos_items2 as $d_items2){
		if($cod_tpg_aux!=$d_items2["catd02_ficha_variables"]["cod_tipo"]){
			$deno=$this->catd01_valor_construccion->findAll($this->SQLCA()." and ano_ordenanza=".$ano_actual." and cod_tipo_construccion='".$d_items2["catd02_ficha_variables"]["cod_tipo"]."'", "denominacion_tipo", null, 1, 1, null);
		}
		$deno_princ=$this->catd01_complemento_variable_principal->findAll($this->SQLCA()." and ano_ordenanza=".$ano_actual." and cod_tipo='".$d_items2["catd02_ficha_variables"]["cod_tipo"]."' and cod_variable_principal='".$d_items2["catd02_ficha_variables"]["cod_variable_principal"]."'", "denominacion_principal", null, 1, 1, null);
		$deno_prima=$this->catd01_complemento_variable_primaria->findAll($this->SQLCA()." and ano_ordenanza=".$ano_actual." and cod_tipo='".$d_items2["catd02_ficha_variables"]["cod_tipo"]."' and cod_variable_principal='".$d_items2["catd02_ficha_variables"]["cod_variable_principal"]."' and cod_variable_primaria='".$d_items2["catd02_ficha_variables"]["cod_variable_primaria"]."'", "denominacion_primaria", null, 1, 1, null);
		 $vec[$i][0]=$d_items2["catd02_ficha_variables"]["cod_variable_principal"];
		 $vec[$i][1]=$deno_princ[0]["catd01_complemento_variable_principal"]["denominacion_principal"];
		 $vec[$i][2]=$d_items2["catd02_ficha_variables"]["cod_variable_primaria"];
		 $vec[$i][3]=$deno_prima[0]["catd01_complemento_variable_primaria"]["denominacion_primaria"];
		 $vec[$i][4]=$d_items2["catd02_ficha_variables"]["monto_variable"];
		 $vec[$i][5]=$deno[0]["catd01_valor_construccion"]["denominacion_tipo"];
		 $vec[$i][6]=$d_items2["catd02_ficha_variables"]["cod_tipo"];
		 $vec[$i]["id"]=$i;
		 $vec[$i]["cod_tipo"]=$d_items2["catd02_ficha_variables"]["cod_tipo"];
		 $cod_tpg_aux=$d_items2["catd02_ficha_variables"]["cod_tipo"];
		 $cod_v[] = $vec[$i][6];
		 $den_v[] = $vec[$i][5];
		 $i++;
	}

	$_SESSION["items"]=$vec;
	$this->Session->write("i",$i);
		$lista = array_combine($cod_v,$den_v);
		$this->concatena_sin_cero($lista, 'vars_const');
	}
	}

	$verifica_img = $this->cugd10_imagenes->findCount($this->SQLCA()." and cod_campo=21 and identificacion='$codigo_ficha'");
	$this->set("verifica_img",$verifica_img);
	$var_con_total=$datos_ficha[0]['v_catd02_ficha_datos']['construccion_valor_total'];
	$var_ter_area=$datos_ficha[0]['v_catd02_ficha_datos']['terreno_area'];
	$var_ter_area_ajust=$datos_ficha[0]['v_catd02_ficha_datos']['terreno_ajuste_area'];
	$var_ter_unit=$datos_ficha[0]['v_catd02_ficha_datos']['terreno_valor_unitario'];
	$var_total_ajust=$datos_ficha[0]['v_catd02_ficha_datos']['terreno_valor_ajustado'];
	$var_regimen=$datos_ficha[0]['v_catd02_ficha_datos']['radio_regimen'];
	$var_ter_total=$datos_ficha[0]['v_catd02_ficha_datos']['terreno_valor_total'];
	$var_total_inm=$datos_ficha[0]['v_catd02_ficha_datos']['valor_total_inmueble'];
	echo "<script language='JavaScript' type='text/javascript'>".
					"ver_documento('/catp02_ficha_datos/calculo_impuesto_anual_trim/$var_total_ajust/$var_con_total/0/$var_total_ajust/$var_ter_area_ajust/$var_regimen/$var_ter_total', 'carga_dimp_anutrim');
		</script>";
	}else{
             $this->set('errorMessage','Disculpe, los datos no fueron encontrados');
             $this->index();
             $this->render('index');
	}
}//fin function




function modificar_ficha($reedi=null, $codigo_ficha=null, $ced_rif_repre=null, $pagm=null){

	$this->layout="ajax";
	if($reedi!=null)
		$this->set("reedi",$reedi);
	$this->set("codigo_ficha",$codigo_ficha);
	$this->set("ced_rif_repre",$ced_rif_repre);
	$this->set("pagm",$pagm);
}


function buscar_datos_ficha($var1=null, $cod=null){

	$this->layout="ajax";
	$this->set("opcion",$var1);
	$this->Session->delete('pista');
	echo "<script>$('campo_pista_ficha').focus();</script>";
}//fin function


function buscar_datos_porpista($var1=null, $var2=null, $var3=null){

	$this->layout="ajax";
	$modelo='v_catd02_ficha_datos';
    if($var3==null){ $var2 = strtoupper($var2);
					 $this->Session->write('pista', $var2);
					 $Tfilas=$this->$modelo->findCount($this->SQLCA()." and ((cod_ficha::text LIKE '%$var2%') or (cedula_rif_repre::text LIKE '%$var2%') or (quitar_acentos(nombre_repre) LIKE quitar_acentos('%$var2%')) or (quitar_acentos(terreno_sector) LIKE quitar_acentos('%$var2%')))");
					        if($Tfilas!=0){
					        	$pagina=1;
					        	$Tfilas=(int)ceil($Tfilas/50);
					        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
								$this->set('total_paginas',$Tfilas);
								$this->set('pagina_actual',$pagina);
								$this->set('ultimo',$Tfilas);
								$datos_filas=$this->$modelo->findAll($this->SQLCA()." and ((cod_ficha::text LIKE '%$var2%') or (cedula_rif_repre::text LIKE '%$var2%') or (quitar_acentos(nombre_repre) LIKE quitar_acentos('%$var2%')) or (quitar_acentos(terreno_sector) LIKE quitar_acentos('%$var2%')))","cod_ficha, cedula_rif_repre, nombre_repre, terreno_sector","cod_ficha, cedula_rif_repre, nombre_repre ASC",50,1,null);
						        $this->set("datosFILAS",$datos_filas);
						        $this->set('siguiente',$pagina+1);
								$this->set('anterior',$pagina-1);
								$this->bt_nav($Tfilas,$pagina);
					          }else{
					        	$this->set("datosFILAS",'');
								$this->set('total_paginas','');
								$this->set('pagina_actual','');
							    $this->set('siguiente','');
								$this->set('anterior','');
								$this->set('ultimo','');
					          }
            }else{
						$var22 = $this->Session->read('pista');
						$var22 = strtoupper($var22);
						$Tfilas=$this->$modelo->findCount($this->SQLCA()." and ((cod_ficha::text LIKE '%$var2%') or (cedula_rif_repre::text LIKE '%$var2%') or (quitar_acentos(nombre_repre) LIKE quitar_acentos('%$var2%')) or (quitar_acentos(terreno_sector) LIKE quitar_acentos('%$var2%')))");
						        if($Tfilas!=0){
						        	$pagina=$var3;
						        	$Tfilas=(int)ceil($Tfilas/50);
						        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
									$this->set('total_paginas',$Tfilas);
									$this->set('pagina_actual',$pagina);
									$this->set('ultimo',$Tfilas);
									$datos_filas=$this->$modelo->findAll($this->SQLCA()." and ((cod_ficha::text LIKE '%$var2%') or (cedula_rif_repre::text LIKE '%$var2%') or (quitar_acentos(nombre_repre) LIKE quitar_acentos('%$var2%')) or (quitar_acentos(terreno_sector) LIKE quitar_acentos('%$var2%')))","cod_ficha, cedula_rif_repre, nombre_repre, terreno_sector","cod_ficha, cedula_rif_repre, nombre_repre ASC",50,$pagina,null);
							        $this->set("datosFILAS",$datos_filas);
							        $this->set('siguiente',$pagina+1);
									$this->set('anterior',$pagina-1);
									$this->bt_nav($Tfilas,$pagina);
						          }else{
						        	$this->set("datosFILAS",'');
									$this->set('total_paginas','');
									$this->set('pagina_actual','');
							    	$this->set('siguiente','');
									$this->set('anterior','');
									$this->set('ultimo','');
						          }
   		}//fin else
$this->set("opcion",$var1);
} //fin funcion


function mostrar_amb ($var_ambi=null) {
	$this->layout="ajax";
	if($var_ambi==1){
		$var_ambi2 = "U01";
		$descms = "Man<br>(Manzana)";
		$descur = "Urbano";
		$dcolor = "#940000";  // Rojo Oscuro
	}else{
		$var_ambi2 = "R02";
		$descms = "Ssec<br>(SubSector)";
		$descur = "Rural";
		$dcolor = "#006400";  // Verde Oscuro
	}
	echo $descms;
	echo "<script> document.getElementById('id_descrip_ur').innerHTML = '<font color=$dcolor><b>$descur</b></font>';
				   document.getElementById('ambito_actual_c1').style.fontSize='13pt';
				   document.getElementById('ambito_actual_c1').style.fontWeight='bold';
				   document.getElementById('ambito_actual_c1').style.color='$dcolor';
				   document.getElementById('ambito_actual_c1').value = '".$var_ambi2[0]."';
				   document.getElementById('ambito_actual_c2').value = '".$var_ambi2[1]."';
				   document.getElementById('ambito_actual_c3').value = '".$var_ambi2[2]."';

			// **** ACTUALIZANDO +AMBITO+ EN LINDEROS Y COORDENADAS: ****

				   document.getElementById('id_descrip_ur_m').innerHTML = '<font color=$dcolor><b>$descur</b></font>';
				   document.getElementById('id_man_ssect_m').innerHTML = '$descms';
				   document.getElementById('ambito_actual_c1_m').style.fontSize='13pt';
				   document.getElementById('ambito_actual_c1_m').style.fontWeight='bold';
				   document.getElementById('ambito_actual_c1_m').style.color='$dcolor';
				   document.getElementById('ambito_actual_c1_m').value = '".$var_ambi2[0]."';
				   document.getElementById('ambito_actual_c2_m').value = '".$var_ambi2[1]."';
				   document.getElementById('ambito_actual_c3_m').value = '".$var_ambi2[2]."';
			</script>";
}

	function establece_construccion($establece=null){

		$this->layout="ajax";
		if($establece=='1'){
			echo "<script>
				document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal".$_SESSION["rand_expediente"]."_3').style.display='block';
				document.getElementById('ide_aconst').readOnly=false;
				document.getElementById('plus2').disabled=false;
				document.getElementById('capa_ei_divcatp').className='capa_activa_divcatp';
			</script>";
		}else if($establece=='2'){
			echo "<script>
				document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal".$_SESSION["rand_expediente"]."_3').style.display='none';
				document.getElementById('ide_aconst').value='0,00';
				document.getElementById('area_m2').value='0,00';
				document.getElementById('ide_aconst').readOnly=true;
				document.getElementById('plus2').disabled=true;
				document.getElementById('capa_ei_divcatp').className='capa_inactiva_divcatp';
				if(document.getElementById('monto_total_de_construccion').value!='0,00'){
					ver_documento('/catp02_ficha_datos/limpiar_lista_tipo_construccion/', 'cargar_filas_construccion');
					document.getElementById('select_tipologia').options[0].selected = true;
					document.getElementById('select_tipologia').disabled=true;
					document.getElementById('area_m2').value='0,00';
					document.getElementById('valor_m2').value='0,0000';
					document.getElementById('valor_construcci').value='0,00';
					document.getElementById('valor_actual').value='0,00';
				}
			</script>";
		}
	}

	function colocar_fecha($var1=null,$var2=null,$var3=null){
		$this->layout="ajax";
		if($var1!=null && $var2!=null && $var3!=null){
			echo "<script>
				document.getElementById('fecha_inscripcion_m').value='$var1/$var2/$var3';
			</script>";
		}else{
			echo "<script>
				document.getElementById('fecha_inscripcion_m').value='".date("d/m/Y")."';
			</script>";
		}
	}


function denominacione_sector ($var_ds=null) {
	$this->layout="ajax";
	if($var_ds!=null){
		$cond ="cod_republica=".$this->Session->read('SScodpresi')."";
			$cod_estado =  $this->Session->read('cod_estado');
			$cod_municipio =  $this->Session->read('cod_municipio');
			$cond .=" and cod_estado=".$cod_estado." and cod_municipio=".$cod_municipio." and cod_parroquia=".$this->Session->read('cod_parroquia2')." and cod_centro=".$var_ds;
			$denominacione = $this->cugd01_centropoblados->findAll($cond, 'denominacion', 'denominacion ASC', 1);
		    $denominacione = $denominacione != null ? $denominacione[0]['cugd01_centropoblados']['denominacion'] : '';

		echo "<script>
				document.getElementById('id_nombren').value = '$denominacione';
				document.getElementById('id_nombrenocp').value = '$denominacione';
				document.getElementById('id_nombrenocp2').value = '$denominacione';
			</script>";
	}
}

function mostrar_valor_tipogdos ($var=null) {

	$this->layout="ajax";
	    $condtg = $this->SQLCA()." and ano_ordenanza=".$_SESSION["ano_ordenanza"]." and cod_tipo_construccion='".$var."'";
		$datostg = $this->catd01_valor_construccion->findAll($condtg, 'denominacion_tipo', 'cod_tipo_caracteristica ASC', 1);
		$_SESSION["denom_catpd02_tpg"] = $datostg[0]["catd01_valor_construccion"]["denominacion_tipo"];

	echo "<script>
 			document.getElementById('idcam_vprincipal').value = '';
 			document.getElementById('st_select3_2').innerHTML = '<select></select>';
 			document.getElementById('idcam_vprimaria').value = '';
 			document.getElementById('monto_vs').value = '';
	 	</script>";

		/* echo "<script>
				document.getElementById('campo_tipologia_dos').value = '".$datostg[0]["catd01_valor_construccion"]["denominacion_tipo"]."';
			</script>"; */
}

function mostrar_valorm2 ($var=null) {

	$this->layout="ajax";
	    $cond =$this->SQLCA()." and ano_ordenanza=".$_SESSION["ano_ordenanza"]." and cod_tipo_caracteristica=0 and cod_tipo_construccion='".$var."'";
		$datos =  $this->catd01_valor_construccion->findAll($cond);
		$this->set('valor_m2',$datos[0]["catd01_valor_construccion"]["valor_m2"]);
		$_SESSION["denom_catpd02_tpg"] = $datos[0]["catd01_valor_construccion"]["denominacion_tipo"];
		echo "<script>calcular_fila_valor_actual(); // document.getElementById('plus2').disabled = false;
			</script>";
}




function escribir_ano_ordenanza ($ano=null) {
	$this->layout="ajax";
	if(isset($ano) && $ano!=null){
		$_SESSION["ano_ordenanza"]=$ano;
		echo "<script>document.getElementById('ano_ordenanza_m').value = '$ano';</script>";
	}else{
		$ano_actual = $this->catd01_ano_ordenanza->ano_actual($this->SQLCA());
	    $this->set('ano_actual',$ano_actual);
		$_SESSION["ano_ordenanza"]=$ano_actual;
		echo "<script>document.getElementById('ano_ordenanza_m').value = '$ano_actual';</script>";
	}
}


function select($select=null,$var=null) {

	$this->layout = "ajax";
	$this->set("modelo_form","catp02_ficha_datos");
	$this->set("ruta","catp02_ficha_datos");
	//echo $select." ".$var;
if(isset($var) && !empty($var) && $var!=''){
		$cond ="cod_republica=".$this->Session->read('SScodpresi')."";
	switch($select){
		/*case 'estado':
			$this->set('SELECT','municipio');
			$this->set('codigo','estado');
			$this->set('seleccion','');
			$this->set('n',2);
			$this->Session->write('cod_1',$var);
 	        $denominacion =  $this->cugd01_estados->generateList($cond, 'denominacion ASC', null, '{n}.cugd01_estados.cod_estado', '{n}.cugd01_estados.denominacion');
            $denominacion = $denominacion != null ? $denominacion : array();
		    $this->concatena($denominacion, 'vector');
		break;*/
		case 'municipio':
			$this->set('SELECT','parroquia');
			$this->set('codigo','municipio');
			$this->set('seleccion','');
			$this->set('n',2);
			$this->Session->write('cod_estado',$var);
			$cond .=" and cod_estado=".$var;
			$denominacion =  $this->cugd01_municipios->generateList($cond, 'denominacion ASC', null, '{n}.cugd01_municipios.cod_municipio', '{n}.cugd01_municipios.denominacion');
		    $denominacion = $denominacion != null ? $denominacion : array();
		    $this->concatena($denominacion, 'vector');
		break;
		case 'parroquia':
			$this->set('SELECT','sector');
			$this->set('codigo','parroquia');
			$this->set('seleccion','');
			$this->set('n',3);
			$cod_estado =  $this->Session->read('cod_estado');
			$this->Session->write('cod_municipio',$var);
			$cond .=" and cod_estado=".$cod_estado." and cod_municipio=".$var;
			$denominacion =  $this->cugd01_parroquias->generateList($cond, 'denominacion ASC', null, '{n}.cugd01_parroquias.cod_parroquia', '{n}.cugd01_parroquias.denominacion');
		    $denominacion = $denominacion != null ? $denominacion : array();
		    $this->concatena($denominacion, 'vector');
		break;
		case 'sector':
		    $this->set('SELECT','sector');
			$this->set('codigo','sector');
			$this->set('seleccion','');
			$this->set('n',4);
			//$this->set('no','no');
			$this->set('SELECT22','sector22');
			$cod_estado =  $this->Session->read('cod_estado');
			$cod_municipio =  $this->Session->read('cod_municipio');
			$this->Session->write('cod_parroquia',$var);
			$this->Session->write('cod_parroquia2',$var);
			$cond .=" and cod_estado=".$cod_estado." and cod_municipio=".$cod_municipio." and cod_parroquia=".$var;
			$denominacion =  $this->cugd01_centropoblados->generateList($cond, 'denominacion ASC', null, '{n}.cugd01_centropoblados.cod_centro', '{n}.cugd01_centropoblados.denominacion');
		    $denominacion = $denominacion != null ? $denominacion : array();
		    $this->concatena_tres_digitos($denominacion, 'vector');
		    $parro =  $this->cugd01_parroquias->findAll($cond);
		    $parroquia=$parro[0]["cugd01_parroquias"]["denominacion"];

		    echo "<script>document.getElementById('select2_4').value = '';";
		    echo "document.getElementById('select2_4_c1').value = '';";
		    echo "document.getElementById('select2_4_c2').value = '';";
		    echo "document.getElementById('select2_4_c3').value = '';";
		    echo "document.getElementById('cparroquia').value='".$parroquia."';";
		    // echo "document.getElementById('datos_ocupante_localidad').value='".$parroquia."';";
		    echo "document.getElementById('cparroquiaren').value='".$parroquia."';";
		    echo "document.getElementById('cparroquiaren2').value='".$parroquia."';";
		    echo "document.getElementById('id_nombren').value = '';";
		    // echo "document.getElementById('datos_ocupante_urb_barrio_sector').value = '';";
		    echo "document.getElementById('id_nombrenocp').value = '';";
		    echo "</script>";

		    if($var!=null)
           		echo "<script>document.getElementById('select2_3').value = '$var'; picar_catp01('select2_3',2);</script>";
		break;
		case 'sector22':
		    if($var!=null){
           		// echo "<script>document.getElementById('select2_4').value = '$var'; picar_catp01('select2_4',3);</script>";
		    }
		break;
	}
	}else{
		echo "";
	}
}//fin select

function select2($select=null,$var=null) {

	$this->layout = "ajax";
	$this->set("modelo_form","catp02_ficha_datos");
	$this->set("ruta","catp02_ficha_datos");
if(isset($var) && !empty($var) && $var!=''){
		$cond ="cod_republica=".$this->Session->read('SScodpresi')."";
	switch($select){
		/*case 'estado':
			$this->set('SELECT','municipio');
			$this->set('codigo','estado');
			$this->set('seleccion','');
			$this->set('n',2);
			$this->Session->write('cod_1',$var);
 	        $denominacion =  $this->cugd01_estados->generateList($cond, 'denominacion ASC', null, '{n}.cugd01_estados.cod_estado', '{n}.cugd01_estados.denominacion');
            $denominacion = $denominacion != null ? $denominacion : array();
		    $this->concatena($denominacion, 'vector');
		break;*/
		case 'municipio':
			$this->set('SELECT','parroquia');
			$this->set('codigo','municipio');
			$this->set('seleccion','');
			$this->set('n',2);
			$this->set('len','2');
			$this->Session->write('cod_estado',$var);
			$this->Session->write('cod_estado2',$var);
			$cond .=" and cod_estado=".$var;
			$denominacion =  $this->cugd01_municipios->generateList($cond, 'denominacion ASC', null, '{n}.cugd01_municipios.cod_municipio', '{n}.cugd01_municipios.denominacion');
		    $denominacion = $denominacion != null ? $denominacion : array();
		    $this->concatena($denominacion, 'vector');
		break;
		case 'parroquia':
			$this->set('SELECT','sector');
			$this->set('codigo','parroquia');
			$this->set('seleccion','');
			$this->set('n',3);
			$this->set('len','2');
			$cod_estado =  $this->Session->read('cod_estado2');
			$this->Session->write('cod_municipio',$var);
			$this->Session->write('cod_municipio2',$var);
			$cond .=" and cod_estado=".$cod_estado." and cod_municipio=".$var;
			$denominacion =  $this->cugd01_parroquias->generateList($cond, 'denominacion ASC', null, '{n}.cugd01_parroquias.cod_parroquia', '{n}.cugd01_parroquias.denominacion');
		    $denominacion = $denominacion != null ? $denominacion : array();
		    $this->concatena($denominacion, 'vector');
		break;
		case 'sector':
			$this->set('nomarca','');
		    $this->set('SELECT','valor_planta_tierra');
			$this->set('codigo','sector');
			$this->set('seleccion','');
			$this->set('n',4);
			$this->set('len','3');
			$cod_estado =  $this->Session->read('cod_estado2');
			$cod_municipio =  $this->Session->read('cod_municipio2');
			$cond .=" and cod_estado=".$cod_estado." and cod_municipio=".$cod_municipio." and cod_parroquia=".$var;
			$denominacion =  $this->cugd01_centropoblados->generateList($cond, 'denominacion ASC', null, '{n}.cugd01_centropoblados.cod_centro', '{n}.cugd01_centropoblados.denominacion');
		    $denominacion = $denominacion != null ? $denominacion : array();
		    $this->concatena_tres_digitos($denominacion, 'vector');
		    $parro =  $this->cugd01_parroquias->findAll($cond);
		    $parroquia=$parro[0]["cugd01_parroquias"]["denominacion"];
		    echo "<script>";
		    /* echo "document.getElementById('select2_4_c1').value = '';";
		    echo "document.getElementById('select2_4_c2').value = '';";
		    echo "document.getElementById('select2_4_c3').value = '';"; */
		    echo "document.getElementById('cparroquia').value='".$parroquia."';";
		    // echo "document.getElementById('datos_ocupante_localidad').value='".$parroquia."';";
		    echo "document.getElementById('cparroquiaren').value='".$parroquia."';";
		    echo "document.getElementById('cparroquiaren2').value='".$parroquia."';";
		    /* echo "document.getElementById('id_nombren').value = '';";
		    echo "document.getElementById('datos_ocupante_urb_barrio_sector').value = '';";
		    echo "document.getElementById('id_nombrenocp').value = '';"; */
		    echo "</script>";
		break;
		case 'valor_planta_tierra':
		    $this->set('SELECT','valor_planta_tierra');
			$this->set('codigo','valor_planta_tierra');
			$this->set('seleccion','');
			$this->set('n',4);
			//$this->set('no','no');
			$cod_estado =  $this->Session->read('cod_estado2');
			$cod_municipio =  $this->Session->read('cod_municipio2');
			if($this->Session->check('cod_parroquia2') && $this->Session->read('cod_parroquia2')!=null)
				$cod_parro = $this->Session->read('cod_parroquia2');
			else
				$cod_parro = $var;
			$cond .=" and cod_estado=".$cod_estado." and cod_municipio=".$cod_municipio." and cod_parroquia=".$cod_parro;
			$denominacion =  $this->catd01_planta_valores_tierra->generateList($this->SQLCA()." and ".$cond, 'denominacion_zona ASC', null, '{n}.catd01_planta_valores_tierra.cod_zona', '{n}.catd01_planta_valores_tierra.denominacion_zona');
		    $denominacion = $denominacion != null ? $denominacion : array();
		    $this->concatena($denominacion, 'vector');
		break;
	}
	}else{
		echo "";
	}
}//fin select2


function select3($select=null,$var=null) {

	$this->layout = "ajax";
	$this->set("modelo_form","catp02_ficha_datos");
	$this->set("ruta","catp02_ficha_datos");
if(isset($var) && !empty($var) && $var!=''){
		$cond ="".$this->SQLCA()." and ano_ordenanza=".$_SESSION["ano_ordenanza"];
	switch($select){
		case 'vprincipal':
			$this->set('SELECT','vprimaria');
			$this->set('codigo','vprincipal');
			$this->set('seleccion','');
			$this->set('n',1);
			$this->Session->write('cod_tipo',$var);
			$cond .=" and cod_tipo='".$var."'";
			$denominacion =  $this->catd01_complemento_variable_principal->generateList($cond, 'cod_variable_principal ASC', null, '{n}.catd01_complemento_variable_principal.cod_variable_principal', '{n}.catd01_complemento_variable_principal.denominacion_principal');
		    $denominacion = $denominacion != null ? $denominacion : array();
		    $this->concatena($denominacion, 'vector');
		break;
		case 'vprimaria':
			$this->set('SELECT','vsecundaria');
			$this->set('codigo','vprimaria');
			$this->set('seleccion','');
			$this->set('n',2);
			$this->Session->write('cod_variable_principal',$var);
			$cond .=" and cod_tipo='".$this->Session->read('cod_tipo')."' and cod_variable_principal=".$var;
			//echo $cond;
			$denominacion =  $this->catd01_complemento_variable_primaria->generateList($cond, 'cod_variable_primaria ASC', null, '{n}.catd01_complemento_variable_primaria.cod_variable_primaria', '{n}.catd01_complemento_variable_primaria.denominacion_primaria');
		    $denominacion = $denominacion != null ? $denominacion : array();
		    $this->concatena($denominacion, 'vector');
			echo "<script>document.getElementById('idcam_vprimaria').value = '';</script>";
		    echo "<script>document.getElementById('plus').disabled = true;</script>";
		break;
		case 'vsecundaria':
			$this->set('SELECT','vsecundaria');
			$this->set('codigo','vsecundaria');
			$this->set('seleccion','');
			$this->set('n',3);
			$this->Session->write('cod_variable_primaria',$var);
			$cond .=" and cod_tipo='".$this->Session->read('cod_tipo')."' and cod_variable_principal=".$this->Session->read('cod_variable_principal')." and cod_variable_primaria=".$var;
			$denominacion =  $this->catd01_complemento_variable_secundaria->generateList($cond, 'cod_variable_secundaria ASC', null, '{n}.catd01_complemento_variable_secundaria.cod_variable_secundaria', '{n}.catd01_complemento_variable_secundaria.denominacion_secundaria');
		    $denominacion = $denominacion != null ? $denominacion : array();
		    $this->concatena($denominacion, 'vector');
		break;

	}
	}else{
		echo "";
	}
}//fin select3


function deno_select3($select=null,$var=null) {

	$this->layout = "ajax";
	$this->set("modelo_form","catp02_ficha_datos");
	$this->set("ruta","catp02_ficha_datos");
if(isset($var) && !empty($var) && $var!=''){
		$cond ="".$this->SQLCA()." and ano_ordenanza=".$_SESSION["ano_ordenanza"];
	switch($select){
		case 'vprincipal':
			$this->set('SELECT','vprimaria');
			$this->set('codigo','vprincipal');
			$this->set('seleccion','');
			$this->set('n',1);
			$ct=$this->Session->read('cod_tipo');
			$this->Session->write('cod_variable_principal',$var);
			$cond .=" and cod_tipo='".$ct."' and cod_variable_principal=".$var;
			$denominacion =  $this->catd01_complemento_variable_principal->findAll($cond);
		    $denominacion = $denominacion[0]["catd01_complemento_variable_principal"]["denominacion_principal"];
		    $this->set('deno',$denominacion);
		    $this->set('valor','');
		break;
		case 'vprimaria':
			$this->set('SELECT','vprimaria');
			$this->set('codigo','vprimaria');
			$this->set('seleccion','');
			$this->set('n',2);
			//$this->Session->write('cod_variable_principal',$this->Session->read('cod_variable_principal'));
			$this->Session->write('cod_variable_primaria',$var);
			$cond .=" and cod_tipo='".$this->Session->read('cod_tipo')."' and cod_variable_principal=".$this->Session->read('cod_variable_principal')." and cod_variable_primaria=".$var;
			$denominacion2 =  $this->catd01_complemento_variable_primaria->findAll($cond);
		    $denominacion = $denominacion2[0]["catd01_complemento_variable_primaria"]["denominacion_primaria"];
		    $valor = $denominacion2[0]["catd01_complemento_variable_primaria"]["monto"];
		    $this->set('valor',$valor);
		    $this->set('deno',$denominacion);
		    echo "<script>document.getElementById('plus').disabled = false;</script>";
		break;
		case 'vsecundaria':
			$this->set('SELECT','vsecundaria');
			$this->set('codigo','vsecundaria');
			$this->set('seleccion','');
			$this->set('n',4);
			//$this->Session->write('cod_variable_primaria',$var);
			$cond .=" and cod_tipo='".$this->Session->read('cod_tipo')."' and cod_variable_principal=".$this->Session->read('cod_variable_principal')." and cod_variable_primaria=".$this->Session->read('cod_variable_primaria')." and cod_variable_secundaria=".$var;
			$denominacionv =  $this->catd01_complemento_variable_secundaria->findAll($cond);
		    $denominacion = $denominacionv[0]["catd01_complemento_variable_secundaria"]["denominacion_secundaria"];
		    $this->set('deno',$denominacion);
		    $valor = $denominacionv[0]["catd01_complemento_variable_secundaria"]["monto"];
		    $this->set('valor',$valor);
		break;

	}
	}else{
		echo "";
	}
}//fin select3

function datos_planta_valor ($var=null) {

    $this->layout="ajax";
            $cod_republica =  $this->Session->read('cod_republica');
            $cod_estado =  $this->Session->read('cod_estado2');
			$cod_municipio =  $this->Session->read('cod_municipio2');
			$cod_parroquia =  $this->Session->read('cod_parroquia2');

			$cond ="cod_republica=".$cod_republica." and cod_estado=".$cod_estado." and cod_municipio=".$cod_municipio." and cod_parroquia=".$cod_parroquia." and cod_zona=".$var;
			$datos =  $this->catd01_planta_valores_tierra->findAll($this->SQLCA()." and ".$cond);
            $this->set("datos_planta",$datos);
}


function guardar_ficha ($vmmod=null, $codigo_ficha=null, $codigo_inscripcion=null, $codigo_archivo=null, $pagina=null) {
	$this->layout="ajax";

						/* ----- BLOQUE UNO::1 ----- */
					/**  **** PESTAÑA::DATOS GENERALES **** */

	if(isset($this->data["catp02_ficha_datos"]["cod_estado"]) && $this->data["catp02_ficha_datos"]["cod_estado"]!=null)
		$codi_edo = $this->data["catp02_ficha_datos"]["cod_estado"];
	else $codi_edo = $this->Session->read('cod_estado');

	if(isset($this->data["catp02_ficha_datos"]["cod_estado"]) && $this->data["catp02_ficha_datos"]["cod_estado"]!=null)
		$codi_mun = $this->data["catp02_ficha_datos"]["cod_municipio"];
	else $codi_mun = $this->Session->read('cod_municipio');


	if(isset($this->data["catp02_ficha_datos"]["cod_estado_c"]) && $this->data["catp02_ficha_datos"]["cod_estado_c"]!=null)
		$codi_edo_c = $this->data["catp02_ficha_datos"]["cod_estado_c"];
	else if(isset($this->data["catp02_ficha_datos"]["cod_estado_c1"]) && $this->data["catp02_ficha_datos"]["cod_estado_c1"]!=null && isset($this->data["catp02_ficha_datos"]["cod_estado_c2"]) && $this->data["catp02_ficha_datos"]["cod_estado_c2"]!=null)
		$codi_edo_c = $this->data["catp02_ficha_datos"]["cod_estado_c1"].$this->data["catp02_ficha_datos"]["cod_estado_c2"];
	else{
		if(isset($_SESSION["cod_estado2"]) && $_SESSION["cod_estado2"]!=null)
			$codi_edo_c = $this->Session->read('cod_estado2');
		else $codi_edo_c = $this->Session->read('cod_estado');
	}

	if(isset($this->data["catp02_ficha_datos"]["cod_municipio_c"]) && $this->data["catp02_ficha_datos"]["cod_municipio_c"]!=null)
		$codi_mun_c = $this->data["catp02_ficha_datos"]["cod_municipio_c"];
	else if(isset($this->data["catp02_ficha_datos"]["cod_municipio_c1"]) && $this->data["catp02_ficha_datos"]["cod_municipio_c1"]!=null && isset($this->data["catp02_ficha_datos"]["cod_municipio_c2"]) && $this->data["catp02_ficha_datos"]["cod_municipio_c2"]!=null)
		$codi_mun_c = $this->data["catp02_ficha_datos"]["cod_municipio_c1"].$this->data["catp02_ficha_datos"]["cod_municipio_c2"];
	else{
		if(isset($_SESSION["cod_municipio2"]) && $_SESSION["cod_municipio2"]!=null)
			$codi_mun_c = $this->Session->read('cod_municipio2');
		else $codi_mun_c = $this->Session->read('cod_municipio');
	}


	        $var["numero_ficha"] = $this->data["catp02_ficha_datos"]["numero_ficha"];
            $var["numero_inscripcion"] = $this->data["catp02_ficha_datos"]["numero_inscripcion"];
            $var["fecha_inscripcion"] = $this->Cfecha($this->data["catp02_ficha_datos"]["fecha_inscripcion"],"A-M-D");
            $var["numero_archivo"] = $this->data["catp02_ficha_datos"]["numero_archivo"];
            $var["ano_ordenanza"] = $this->data["catp02_ficha_datos"]["ano_ordenanza"];
            $var[1] = $codi_edo;
            $var[2] = $codi_mun;
            $var[3] = $this->data["catp02_ficha_datos"]["cod_parroquia"];
            $var[4] = $this->data["catp02_ficha_datos"]["cod_sector"];
            $var[5] = $this->data["catp02_ficha_datos"]["cod_ant_manzana"]!=null ? $this->data["catp02_ficha_datos"]["cod_ant_manzana"] : 0;
            $var[6] = $this->data["catp02_ficha_datos"]["cod_ant_parcela"]!=null ? $this->data["catp02_ficha_datos"]["cod_ant_parcela"] : 0;
            $var[7] = $this->data["catp02_ficha_datos"]["cod_bloque"]!=null ? $this->data["catp02_ficha_datos"]["cod_bloque"] : 0;
            $var[8] = $this->data["catp02_ficha_datos"]["cod_piso"]!=null ? $this->data["catp02_ficha_datos"]["cod_piso"] : 0;
            $var[9] = $this->data["catp02_ficha_datos"]["cod_apto"]!=null ? $this->data["catp02_ficha_datos"]["cod_apto"] : 0;
            $var[10] = $codi_edo_c;
            $var[11] = $codi_mun_c;
            $var[12] = $this->data["catp02_ficha_datos"]["cod_parroquia_c"];
            if($this->data["catp02_ficha_datos"]["ambito_actual_c1"]!=null)
            	$var[13] = $this->data["catp02_ficha_datos"]["ambito_actual_c1"];
            else
            	$var[13] = $this->data["catp02_ficha_datos"]["radio_ambito"]==1 ? 'U' : 'R';

            $var[14] = $this->data["catp02_ficha_datos"]["ambito_actual_c2"].$this->data["catp02_ficha_datos"]["ambito_actual_c3"];
            $var[15] = $this->data["catp02_ficha_datos"]["cod_sector_c"];
            $var[16] = $this->data["catp02_ficha_datos"]["cod_manzana_c1"].$this->data["catp02_ficha_datos"]["cod_manzana_c2"].$this->data["catp02_ficha_datos"]["cod_manzana_c3"];
            $var[17] = $this->data["catp02_ficha_datos"]["cod_parcela_c1"].$this->data["catp02_ficha_datos"]["cod_parcela_c2"].$this->data["catp02_ficha_datos"]["cod_parcela_c3"];
            $var[18] = $this->data["catp02_ficha_datos"]["cod_sub_parcela_c1"].$this->data["catp02_ficha_datos"]["cod_sub_parcela_c2"].$this->data["catp02_ficha_datos"]["cod_sub_parcela_c3"];
            $var[19] = $this->data["catp02_ficha_datos"]["cod_nivel_c1"].$this->data["catp02_ficha_datos"]["cod_nivel_c2"].$this->data["catp02_ficha_datos"]["cod_nivel_c3"];
            $var[20] = $this->data["catp02_ficha_datos"]["cod_unidad_c1"].$this->data["catp02_ficha_datos"]["cod_unidad_c2"].$this->data["catp02_ficha_datos"]["cod_unidad_c3"];

			$var[21] = $this->data["catp02_ficha_datos"]["cparroquia"];
			$var[22] = $this->data["catp02_ficha_datos"]["dir_inmueble"];
			$var[23] = $this->data["catp02_ficha_datos"]["nombre"];

			$var[24] = $this->data["catp02_ficha_datos"]["dir_inmuebleB"];
			$var[25] = $this->data["catp02_ficha_datos"]["dir_inm_descripcion"];

			$var[26] = $this->data["catp02_ficha_datos"]["dir_inmuebleEntre"];
			$var[27] = $this->data["catp02_ficha_datos"]["dir_inm_entre_descripcion"];

			$var[28] = $this->data["catp02_ficha_datos"]["dir_inmuebleY"];
			$var[29] = $this->data["catp02_ficha_datos"]["dir_inm_y_descripcion"];

			$var[30] = $this->data["catp02_ficha_datos"]["dir_inmuebleX"];
			$var[31] = isset($this->data["catp02_ficha_datos"]["tipo_vivienda"]) ? $this->data["catp02_ficha_datos"]["tipo_vivienda"] : null;

			$var[32] = $this->data["catp02_ficha_datos"]["dir_inm_nombre_inmueble"];
			$var[33] = $this->data["catp02_ficha_datos"]["dir_inm_nro_civico"];
			$var[34] = $this->data["catp02_ficha_datos"]["dir_inm_telefonos"];
			$var[35] = $this->data["catp02_ficha_datos"]["dir_inm_pto_ref"];
			$var[36] = $this->data["catp02_ficha_datos"]["condicion_ocup"];


						/* ----- BLOQUE DOS::2 ----- */
					/**  **** PESTAÑA::DATOS DEL OCUPANTE **** */

			// 2.1. Datos del Ocupante:

			$var[37] = $this->data["catp02_ficha_datos"]["datos_ocupante_personalidad"];        // personalidad_juridica
			$var[38] = $this->data["catp02_ficha_datos"]["datos_ocupante_ci_rif"];              // cedula_rif
			$var[39] = $this->data["catp02_ficha_datos"]["datos_ocupante_nombre"];				// nombre_ocupante
			$var[40] = $this->data["catp02_ficha_datos"]["cparroquia_ocp2"];			// localidad_ocupante  (ciudad)
			$var[41] = $this->data["catp02_ficha_datos"]["dir_inmueble_ocp2"];					// radio_localidad_ocupante
			$var[42] = $this->data["catp02_ficha_datos"]["nombre_ocp2"];						// urb_barrio_sector_ocupante

			$var[43] = $this->data["catp02_ficha_datos"]["dir_propB2"];					// radio_cuatro_ocupante
			$var[44] = $this->data["catp02_ficha_datos"]["dir_prop_descripcion2"];	// direccion_cuatro_ocupante
			$var[45] = $this->data["catp02_ficha_datos"]["dir_propEntre2"];				// radio_cinco_ocupante
			$var[46] = $this->data["catp02_ficha_datos"]["dir_prop_entre_descripcion2"];		// direccion_cinco_ocupante

			$var[47] = $this->data["catp02_ficha_datos"]["dir_propY2"];						// radio_seis_ocupante
			$var[48] = $this->data["catp02_ficha_datos"]["dir_prop_y_descripcion2"];			// direccion_seis_ocupante
			$var[49] = $this->data["catp02_ficha_datos"]["dir_propX2"];							// radio_vivienda_dos_ocupante
			$var[50] = isset($this->data["catp02_ficha_datos"]["prop_tipo_vivienda2"]) ? $this->data["catp02_ficha_datos"]["prop_tipo_vivienda2"] : ''; // tipo_vivienda_otro_dos_ocupante
			$var[51] = $this->data["catp02_ficha_datos"]["dir_prop_nombre_inmueble2"];		// nombre_inmueble_ocupante
			$var[52] = $this->data["catp02_ficha_datos"]["dir_prop_nro_civico2"];			// numero_civico_ocupante
			$var[53] = $this->data["catp02_ficha_datos"]["dir_prop_telefonos2"];			// telefono_ocupante
			$var[54] = $this->data["catp02_ficha_datos"]["dir_prop_pto_ref2"];				// email_ocupante

			// 2.2. Datos del Propietario:

			$var[55] = $this->data["catp02_ficha_datos"]["datos_ocupante_personalidad_p"];	// personalidad_juridica_repre
			$var[56] = $this->data["catp02_ficha_datos"]["datos_ocupante_ci_rif_p"];		// cedula_rif_repre
			$var[57] = $this->data["catp02_ficha_datos"]["datos_ocupante_nombre_p"];		// nombre_repre
			$var[58] = $this->data["catp02_ficha_datos"]["cparroquia_ocp"];					// parroquia_repre
			$var[59] = $this->data["catp02_ficha_datos"]["dir_inmueble_ocp"];				// radio_localidad_repre
			$var[60] = $this->data["catp02_ficha_datos"]["nombre_ocp"];				// nombre_localidad_repre
			$var[61] = $this->data["catp02_ficha_datos"]["dir_propB"];				// radio_cuatro
			$var[62] = $this->data["catp02_ficha_datos"]["dir_prop_descripcion"];		// direccion_cuatro
			$var[63] = $this->data["catp02_ficha_datos"]["dir_propEntre"];					// radio_cinco
			$var[64] = $this->data["catp02_ficha_datos"]["dir_prop_entre_descripcion"];		// direccion_cinco
			$var[65] = $this->data["catp02_ficha_datos"]["dir_propY"];				// radio_seis
			$var[66] = $this->data["catp02_ficha_datos"]["dir_prop_y_descripcion"];		// direccion_seis
			$var[67] = $this->data["catp02_ficha_datos"]["dir_propX"];		// radio_vivienda_dos
			$var[68] = isset($this->data["catp02_ficha_datos"]["prop_tipo_vivienda"]) ? $this->data["catp02_ficha_datos"]["prop_tipo_vivienda"] : ''; // tipo_vivienda_otro_dos
			$var[69] = $this->data["catp02_ficha_datos"]["dir_prop_nombre_inmueble"];		// nombre_inmueble_repre
			$var[70] = $this->data["catp02_ficha_datos"]["dir_prop_nro_civico"];		// numero_civico_repre
			$var[71] = $this->data["catp02_ficha_datos"]["dir_prop_telefonos"];		// telefono_repre
			$var[72] = $this->data["catp02_ficha_datos"]["dir_prop_pto_ref"];		// email_repre


						/* ----- BLOQUE TRES::3 ----- */
					/**  **** PESTAÑA::DATOS DEL TERRENO **** */

			$var[73] = $this->data["catp02_ficha_datos"]["tilde_topo"];			// radio_topo
			$var[74] = $this->data["catp02_ficha_datos"]["tilde_acceso"];		// radio_acceso
			$var[75] = $this->data["catp02_ficha_datos"]["tilde_forma_regular"];	// radio_forma
			$var[76] = $this->data["catp02_ficha_datos"]["tilde_ubica"];			// radio_ubica
			$var[77] = $this->data["catp02_ficha_datos"]["tilde_entorno_zona"];		// radio_entorno... la de abajo: tilde_uso
			$var[78] = $this->data["catp02_ficha_datos"]["tilde_uso_residencial"].$this->data["catp02_ficha_datos"]["tilde_uso_comercial"].$this->data["catp02_ficha_datos"]["tilde_uso_industrial"].$this->data["catp02_ficha_datos"]["tilde_uso_recreativo"].$this->data["catp02_ficha_datos"]["tilde_uso_asistencia"].$this->data["catp02_ficha_datos"]["tilde_uso_educacional"].$this->data["catp02_ficha_datos"]["tilde_uso_turistico"].$this->data["catp02_ficha_datos"]["tilde_uso_social_cultural"].$this->data["catp02_ficha_datos"]["tilde_uso_gubernamental"].$this->data["catp02_ficha_datos"]["tilde_uso_religioso"].$this->data["catp02_ficha_datos"]["tilde_uso_sin_uso"].$this->data["catp02_ficha_datos"]["tilde_uso_otro"];
			$var[79] = $this->data["catp02_ficha_datos"]["tilde_mejoras_muro_contencion"].$this->data["catp02_ficha_datos"]["tilde_mejoras_nivelacion"].$this->data["catp02_ficha_datos"]["tilde_mejoras_cercado"].$this->data["catp02_ficha_datos"]["tilde_mejoras_pozo_septico"].$this->data["catp02_ficha_datos"]["tilde_mejoras_lagunas_art"].$this->data["catp02_ficha_datos"]["tilde_mejoras_otro"];
			$var[80] = $this->data["catp02_ficha_datos"]["tilde_tenencia"];		// radio_tenencia
			$var[81] = $this->data["catp02_ficha_datos"]["tilde_reg_prop"];		// radio_regimen... el de abajo: tilde_servicio... mas abajo: tilde_servicio
			$var[82] = $this->data["catp02_ficha_datos"]["tilde_serv_acueducto"].$this->data["catp02_ficha_datos"]["tilde_serv_cloacas"].$this->data["catp02_ficha_datos"]["tilde_serv_drenaje_art"].$this->data["catp02_ficha_datos"]["tilde_serv_elect_residencial"].$this->data["catp02_ficha_datos"]["tilde_serv_elect_indus"].$this->data["catp02_ficha_datos"]["tilde_serv_alumbrado"].$this->data["catp02_ficha_datos"]["tilde_serv_vialidad"].$this->data["catp02_ficha_datos"]["tilde_serv_pavimento"].$this->data["catp02_ficha_datos"]["tilde_serv_acera"].$this->data["catp02_ficha_datos"]["tilde_serv_transporte"].$this->data["catp02_ficha_datos"]["tilde_serv_telefono"].$this->data["catp02_ficha_datos"]["tilde_serv_cable_tv"].$this->data["catp02_ficha_datos"]["tilde_serv_tvsate"].$this->data["catp02_ficha_datos"]["tilde_serv_correo_telec"].$this->data["catp02_ficha_datos"]["tilde_serv_gas"].$this->data["catp02_ficha_datos"]["tilde_serv_aseo"].$this->data["catp02_ficha_datos"]["tilde_serv_otro"];
			$var[83] = $this->data["catp02_ficha_datos"]["radio_afect_leg"];		// radio_afectacion
			$var[84] = $this->data["catp02_ficha_datos"]["normativa_gaceta"];		// normativa_gaceta
			$var[85] = $this->data["catp02_ficha_datos"]["normativa_resolucion"];	// normativa_resolucion
			$var[86] = $this->Cfecha($this->data["catp02_ficha_datos"]["normativa_fecha"],"A-M-D"); // normativa_fecha
			$var[87] = $this->data["catp02_ficha_datos"]["ubic_zonificacion"];		// ubica_zonificacion


						/* ----- BLOQUE CUARTO::4 ----- */
			/**  **** PESTAÑA::DATOS GENERALES DE LA CONSTRUCCION **** */

			// radio_tipo... radio_descripcionuso... radio_tenencia_const... radio_regi_prop

$_SESSION["radio_descripcionuso"]=$this->data["catp02_ficha_datos"]["tilde_desc_uso"];

			$var[88] = isset($this->data["catp02_ficha_datos"]["tilde_tipo"]) ? $this->data["catp02_ficha_datos"]["tilde_tipo"] : 0;
			$var[89] = isset($this->data["catp02_ficha_datos"]["tilde_desc_uso"]) ? $this->data["catp02_ficha_datos"]["tilde_desc_uso"] : 0;
			$var[90] = isset($this->data["catp02_ficha_datos"]["tilde_tenencia_cons"]) ? $this->data["catp02_ficha_datos"]["tilde_tenencia_cons"] : 0;
			$var[91] = isset($this->data["catp02_ficha_datos"]["tilde_regimen_prop"]) ? $this->data["catp02_ficha_datos"]["tilde_regimen_prop"] : 0;

		if((int) $this->data["catp02_ficha_datos"]["tilde_tenencia_cons"]==2){
			$var[92] = $this->Formato1($this->data["catp02_ficha_datos"]["canon_mensual"]);			// declara_canon
			$var[93] = $this->Cfecha($this->data["catp02_ficha_datos"]["fecha_contrato"],"A-M-D");	// declara_fecha
			$var[94] = $this->Formato1($this->data["catp02_ficha_datos"]["ingreso_familiar"]);		// declara_ingreso
		}else{
			$var[92] = 0;				// declara_canon
			$var[93] = "1900-01-01";	// declara_fecha
			$var[94] = 0;				// declara_ingreso
		}

			// tilde_soporte...
			// tilde_techo...
			// tilde_cubierta_externa...
			// tilde_cubierta_interna...
			// tilde_pared_tipo...
			// tilde_pared_acaba...
			// tilde_pared_pintura...
			// tilde_insta_electricas...
			// tilde_piso...
			$var[95] = $this->data["catp02_ficha_datos"]["tilde_soporte_concreto_armado"].$this->data["catp02_ficha_datos"]["tilde_soporte_metalica"].$this->data["catp02_ficha_datos"]["tilde_soporte_madera"].$this->data["catp02_ficha_datos"]["tilde_soporte_paredes_carga"].$this->data["catp02_ficha_datos"]["tilde_soporte_prefrabicado"].$this->data["catp02_ficha_datos"]["tilde_soporte_machones"].$this->data["catp02_ficha_datos"]["tilde_soporte_otro"];
			$var[96] = $this->data["catp02_ficha_datos"]["tilde_techo_concreto_armado"].$this->data["catp02_ficha_datos"]["tilde_techo_metalica"].$this->data["catp02_ficha_datos"]["tilde_techo_madera"].$this->data["catp02_ficha_datos"]["tilde_techo_varas"].$this->data["catp02_ficha_datos"]["tilde_techo_cerchas"].$this->data["catp02_ficha_datos"]["tilde_techo_al"].$this->data["catp02_ficha_datos"]["tilde_techo_otro"];
			$var[97] = $this->data["catp02_ficha_datos"]["tilde_cubierta_madera_teja"].$this->data["catp02_ficha_datos"]["tilde_cubierta_placa_teja"].$this->data["catp02_ficha_datos"]["tilde_cubierta_tejas_canabrava"].$this->data["catp02_ficha_datos"]["tilde_cubierta_platabanda"].$this->data["catp02_ficha_datos"]["tilde_cubierta_asbesto"].$this->data["catp02_ficha_datos"]["tilde_cubierta_aluminio"].$this->data["catp02_ficha_datos"]["tilde_cubierta_zinc"].$this->data["catp02_ficha_datos"]["tilde_cubierta_acerolit"].$this->data["catp02_ficha_datos"]["tilde_cubierta_palma"].$this->data["catp02_ficha_datos"]["tilde_cubierta_tabelon"].$this->data["catp02_ficha_datos"]["tilde_cubierta_barro"].$this->data["catp02_ficha_datos"]["tilde_cubierta_riple"].$this->data["catp02_ficha_datos"]["tilde_cubierta_cinduteja"].$this->data["catp02_ficha_datos"]["tilde_cubierta_otro"];
			$var[98] = $this->data["catp02_ficha_datos"]["tilde_cubiertai_machi"].$this->data["catp02_ficha_datos"]["tilde_cubiertai_plafon"].$this->data["catp02_ficha_datos"]["tilde_cubiertai_crl"].$this->data["catp02_ficha_datos"]["tilde_cubiertai_cre"].$this->data["catp02_ficha_datos"]["tilde_cubiertai_sinc"].$this->data["catp02_ficha_datos"]["tilde_cubiertai_otro"];
			$var[99] = $this->data["catp02_ficha_datos"]["tilde_pared_tipo_bloque_cemento"].$this->data["catp02_ficha_datos"]["tilde_pared_tipo_bloque_arcilla"].$this->data["catp02_ficha_datos"]["tilde_pared_tipo_ladrillo"].$this->data["catp02_ficha_datos"]["tilde_pared_tipo_adobe"].$this->data["catp02_ficha_datos"]["tilde_pared_tipo_tapia"].$this->data["catp02_ficha_datos"]["tilde_pared_tipo_bahareque"].$this->data["catp02_ficha_datos"]["tilde_pared_tipo_prefabricada"].$this->data["catp02_ficha_datos"]["tilde_pared_tipo_vidrio"].$this->data["catp02_ficha_datos"]["tilde_pared_tipo_madera_aserrada"].$this->data["catp02_ficha_datos"]["tilde_pared_tipo_zinc_laton"].$this->data["catp02_ficha_datos"]["tilde_pared_tipo_carton"].$this->data["catp02_ficha_datos"]["tilde_pared_tipo_sin_paredes"].$this->data["catp02_ficha_datos"]["tilde_pared_tipo_otro"];
			$var[100] = $this->data["catp02_ficha_datos"]["tilde_pared_acabado_friso_liso"].$this->data["catp02_ficha_datos"]["tilde_pared_acabado_friso_rustico"].$this->data["catp02_ficha_datos"]["tilde_pared_acabado_ol"].$this->data["catp02_ficha_datos"]["tilde_pared_acabado_c"].$this->data["catp02_ficha_datos"]["tilde_pared_acabado_porc"].$this->data["catp02_ficha_datos"]["tilde_pared_acabado_cemenb"].$this->data["catp02_ficha_datos"]["tilde_pared_acabado_papelt"].$this->data["catp02_ficha_datos"]["tilde_pared_acabado_vidrio"].$this->data["catp02_ficha_datos"]["tilde_pared_acabado_yeso_cal"].$this->data["catp02_ficha_datos"]["tilde_pared_acabado_baldosa"].$this->data["catp02_ficha_datos"]["tilde_pared_acabado_sin_friso"].$this->data["catp02_ficha_datos"]["tilde_pared_acabado_otro"];
			$var[101] = $this->data["catp02_ficha_datos"]["tilde_pintura_caucho"].$this->data["catp02_ficha_datos"]["tilde_pintura_oleo"].$this->data["catp02_ficha_datos"]["tilde_pintura_pasta"].$this->data["catp02_ficha_datos"]["tilde_pintura_asbestina"].$this->data["catp02_ficha_datos"]["tilde_pintura_lechada"].$this->data["catp02_ficha_datos"]["tilde_pintura_sinpintura"].$this->data["catp02_ficha_datos"]["tilde_pintura_otro"];
			$var[102] = $this->data["catp02_ficha_datos"]["tilde_instalac_embutida"].$this->data["catp02_ficha_datos"]["tilde_instalac_externa"].$this->data["catp02_ficha_datos"]["tilde_instalac_industrial"];
			$var[103] = $this->data["catp02_ficha_datos"]["tilde_piso_ceramica"].$this->data["catp02_ficha_datos"]["tilde_piso_porcelanato"].$this->data["catp02_ficha_datos"]["tilde_piso_granito"].$this->data["catp02_ficha_datos"]["tilde_piso_parquet"].$this->data["catp02_ficha_datos"]["tilde_piso_marmol"].$this->data["catp02_ficha_datos"]["tilde_piso_vinil"].$this->data["catp02_ficha_datos"]["tilde_piso_mosaico"].$this->data["catp02_ficha_datos"]["tilde_piso_madera"].$this->data["catp02_ficha_datos"]["tilde_piso_cemento_pulido"].$this->data["catp02_ficha_datos"]["tilde_piso_cemento_rustico"].$this->data["catp02_ficha_datos"]["tilde_piso_ladrillo"].$this->data["catp02_ficha_datos"]["tilde_piso_terracota"].$this->data["catp02_ficha_datos"]["tilde_piso_sinpiso"].$this->data["catp02_ficha_datos"]["tilde_piso_otro"];

			$var[104] = (int) $this->data["catp02_ficha_datos"]["numero_banera"];		// bano_nro_banera...
			$var[105] = (int) $this->data["catp02_ficha_datos"]["numero_wc"];			// bano_nro_wc...
			$var[106] = (int) $this->data["catp02_ficha_datos"]["numero_bidet"];		// bano_nro_bidet...
			$var[107] = (int) $this->data["catp02_ficha_datos"]["numero_lavamanos"];	// bano_nro_lavamanos...
			$var[108] = (int) $this->data["catp02_ficha_datos"]["numero_duchas"];		// bano_nro_ducha...
			$var[109] = $this->Formato1($this->data["catp02_ficha_datos"]["numero_ceramica_uno"]);	// bano_nro_ceramica_pri...
			$var[110] = $this->Formato1($this->data["catp02_ficha_datos"]["numero_ceramica_dos"]);	// bano_nro_ceramica_seg...
			$var[111] = (int) $this->data["catp02_ficha_datos"]["numero_bano_otro"];	// bano_nro_otro...
			$var[112] = (int) $this->data["catp02_ficha_datos"]["numero_ventanal"];	// ventana_nro_ventanal...
			$var[113] = $this->data["catp02_ficha_datos"]["m_a1"].$this->data["catp02_ficha_datos"]["v_a1"].$this->data["catp02_ficha_datos"]["a_a1"].$this->data["catp02_ficha_datos"]["h_a1"].$this->data["catp02_ficha_datos"]["o_a1"];	// ventana_tilde_ventanal...
			$var[114] = (int) $this->data["catp02_ficha_datos"]["numero_celosia"];	// ventana_nro_celosia...
			$var[115] = $this->data["catp02_ficha_datos"]["m_a2"].$this->data["catp02_ficha_datos"]["v_a2"].$this->data["catp02_ficha_datos"]["a_a2"].$this->data["catp02_ficha_datos"]["h_a2"].$this->data["catp02_ficha_datos"]["o_a2"];	// ventana_tilde_celosia...
			$var[116] = (int) $this->data["catp02_ficha_datos"]["numero_corredera"];	// ventana_nro_corredera...
			$var[117] = $this->data["catp02_ficha_datos"]["m_a3"].$this->data["catp02_ficha_datos"]["v_a3"].$this->data["catp02_ficha_datos"]["a_a3"].$this->data["catp02_ficha_datos"]["h_a3"].$this->data["catp02_ficha_datos"]["o_a3"];	// ventana_tilde_corredera...
			$var[118] = (int) $this->data["catp02_ficha_datos"]["numero_basculante"];	// ventana_nro_basculante...
			$var[119] = $this->data["catp02_ficha_datos"]["m_a4"].$this->data["catp02_ficha_datos"]["v_a4"].$this->data["catp02_ficha_datos"]["a_a4"].$this->data["catp02_ficha_datos"]["h_a4"].$this->data["catp02_ficha_datos"]["o_a4"];	// ventana_tilde_basculante...
			$var[120] = (int) $this->data["catp02_ficha_datos"]["numero_batiente"];	// ventana_nro_batiente...
			$var[121] = $this->data["catp02_ficha_datos"]["m_a5"].$this->data["catp02_ficha_datos"]["v_a5"].$this->data["catp02_ficha_datos"]["a_a5"].$this->data["catp02_ficha_datos"]["h_a5"].$this->data["catp02_ficha_datos"]["o_a5"];	// ventana_tilde_batiente...
			$var[122] = (int) $this->data["catp02_ficha_datos"]["numero_panoramica"];	// ventana_nro_panoramica...
			$var[123] = $this->data["catp02_ficha_datos"]["m_a6"].$this->data["catp02_ficha_datos"]["v_a6"].$this->data["catp02_ficha_datos"]["a_a6"].$this->data["catp02_ficha_datos"]["h_a6"].$this->data["catp02_ficha_datos"]["o_a6"];	// ventana_tilde_panoramica...
			$var[124] = (int) $this->data["catp02_ficha_datos"]["numero_entamborad"];	// puerta_nro_entamb_fina...
			$var[125] = $this->data["catp02_ficha_datos"]["m_a7"].$this->data["catp02_ficha_datos"]["v_a7"].$this->data["catp02_ficha_datos"]["a_a7"].$this->data["catp02_ficha_datos"]["h_a7"].$this->data["catp02_ficha_datos"]["o_a7"];	// puerta_tilde_entamb_fina...
			$var[126] = (int) $this->data["catp02_ficha_datos"]["numero_entamborad_econ"];	// puerta_nro_entamb_econo...
			$var[127] = $this->data["catp02_ficha_datos"]["m_a8"].$this->data["catp02_ficha_datos"]["v_a8"].$this->data["catp02_ficha_datos"]["a_a8"].$this->data["catp02_ficha_datos"]["h_a8"].$this->data["catp02_ficha_datos"]["o_a8"];	// puerta_tilde_entamb_econo...
			$var[128] = (int) $this->data["catp02_ficha_datos"]["numero_madera_cepillada"];	// puerta_nro_madera_cepi...
			$var[129] = $this->data["catp02_ficha_datos"]["m_a9"].$this->data["catp02_ficha_datos"]["v_a9"].$this->data["catp02_ficha_datos"]["a_a9"].$this->data["catp02_ficha_datos"]["h_a9"].$this->data["catp02_ficha_datos"]["o_a9"];	// puerta_tilde_madera_cepi...
			$var[130] = (int) $this->data["catp02_ficha_datos"]["numero_metalicas"];	// puerta_nro_metalica...
			$var[131] = $this->data["catp02_ficha_datos"]["m_a10"].$this->data["catp02_ficha_datos"]["v_a10"].$this->data["catp02_ficha_datos"]["a_a10"].$this->data["catp02_ficha_datos"]["h_a10"].$this->data["catp02_ficha_datos"]["o_a10"];	// puerta_tilde_metalica...
			$var[132] = (int) $this->data["catp02_ficha_datos"]["numero_seguridad"];	// puerta_nro_seguridad...
			$var[133] = $this->data["catp02_ficha_datos"]["m_a11"].$this->data["catp02_ficha_datos"]["v_a11"].$this->data["catp02_ficha_datos"]["a_a11"].$this->data["catp02_ficha_datos"]["h_a11"].$this->data["catp02_ficha_datos"]["o_a11"];	// puerta_tilde_seguridad...
			$var[134] = (int) $this->data["catp02_ficha_datos"]["numero_vidrio"];	// puerta_nro_vidrio...
			$var[135] = $this->data["catp02_ficha_datos"]["m_a12"].$this->data["catp02_ficha_datos"]["v_a12"].$this->data["catp02_ficha_datos"]["a_a12"].$this->data["catp02_ficha_datos"]["h_a12"].$this->data["catp02_ficha_datos"]["o_a12"];	// puerta_tilde_vidrio...
			$var[136] = (int) $this->data["catp02_ficha_datos"]["numero_puertas_otro"];	// puerta_nro_otro...
			$var[137] = $this->data["catp02_ficha_datos"]["m_a13"].$this->data["catp02_ficha_datos"]["v_a13"].$this->data["catp02_ficha_datos"]["a_a13"].$this->data["catp02_ficha_datos"]["h_a13"].$this->data["catp02_ficha_datos"]["o_a13"];	// puerta_tilde_otro...

			$var[138] = (int) $this->data["catp02_ficha_datos"]["numero_dorm_hab"];		// ambiente_nro_dormitorio...
			$var[139] = (int) $this->data["catp02_ficha_datos"]["numero_comedor"];		// ambiente_nro_comedor...
			$var[140] = (int) $this->data["catp02_ficha_datos"]["numero_sala"];			// ambiente_nro_sala...
			$var[141] = (int) $this->data["catp02_ficha_datos"]["numero_bano_econ"];	// ambiente_nro_bano_econo...
			$var[142] = (int) $this->data["catp02_ficha_datos"]["numero_bano_lujoso"];	// ambiente_nro_bano_lujoso...
			$var[143] = (int) $this->data["catp02_ficha_datos"]["numero_cocina"];		// ambiente_nro_cocina...
			$var[144] = (int) $this->data["catp02_ficha_datos"]["numero_area_serv"];	// ambiente_nro_area_serv...
			$var[145] = (int) $this->data["catp02_ficha_datos"]["numero_estudio"];		// ambiente_nro_estudio...
			$var[146] = (int) $this->data["catp02_ficha_datos"]["numero_garaje"];		// ambiente_nro_garaje...
			$var[147] = (int) $this->data["catp02_ficha_datos"]["numero_estacionamiento"];	// ambiente_nro_estaciona...
			$var[148] = (int) $this->data["catp02_ficha_datos"]["numero_maletero"];		// ambiente_nro_maletero...
			$var[149] = (int) $this->data["catp02_ficha_datos"]["numero_sotano"];		// ambiente_nro_sotano...
			$var[150] = (int) $this->data["catp02_ficha_datos"]["numero_letrinap"];		// ambiente_nro_letrina...
			$var[151] = (int) $this->data["catp02_ficha_datos"]["numero_otro_ambiente"];	// ambiente_nro_otro...

			$var[152] = (int) $this->data["catp02_ficha_datos"]["numero_ascensor"];		// compleme_nro_ascensor...
			$var[153] = (int) $this->data["catp02_ficha_datos"]["numero_aire_acond"];	// compleme_nro_aire_acondi...
			$var[154] = (int) $this->data["catp02_ficha_datos"]["numero_rejas_puertas"];	// compleme_nro_reja_puerta...
			$var[155] = (int) $this->data["catp02_ficha_datos"]["numero_rejas_vent"];	// compleme_nro_reja_ventana...
			$var[156] = (int) $this->data["catp02_ficha_datos"]["numero_closet"];	// compleme_nro_closet...
			$var[157] = (int) $this->data["catp02_ficha_datos"]["numero_ceramicas"];		// compleme_nro_ceramica...
			$var[158] = (int) $this->data["catp02_ficha_datos"]["numero_puerta_stam"];	// compleme_nro_puerta_santa...
			$var[159] = (int) $this->data["catp02_ficha_datos"]["numero_neumatico"];		// compleme_nro_hidro...
			$var[160] = (int) $this->data["catp02_ficha_datos"]["numero_jacuzzi"];		// compleme_nro_jacuzzi...
			$var[161] = (int) $this->data["catp02_ficha_datos"]["numero_calentador"];	// compleme_nro_calentador...
			$var[162] = (int) $this->data["catp02_ficha_datos"]["numero_piscina"];		// compleme_nro_piscina...
			$var[163] = $this->Formato_3_in($this->data["catp02_ficha_datos"]["cant_ag_piscina"]);	// compleme_cap_piscina...
			$var[164] = (int) $this->data["catp02_ficha_datos"]["numero_tanque_tc"];		// compleme_nro_tanque...
			$var[165] = $this->data["catp02_ficha_datos"]["espec_capac_tanque_tc"];	// compleme_tipo_tanque...
			$var[166] = $this->Formato_3_in($this->data["catp02_ficha_datos"]["capacidad_tanque_tc"]);	// compleme_cap_tanque...
			$var[167] = (int) $this->data["catp02_ficha_datos"]["numero_otro_complemento"];	// compleme_nro_otro...

			$var[168] = isset($this->data["catp02_ficha_datos"]["tilde_conserva"]) ? $this->data["catp02_ficha_datos"]["tilde_conserva"] : 0; // radio_conserva
			$var[169] = $this->data["catp02_ficha_datos"]["ano_construccion"]!=null ? $this->data["catp02_ficha_datos"]["ano_construccion"] : 0; // ano_construccion
			$var[170] = $this->data["catp02_ficha_datos"]["ano_refraccion"]; // ano_refaccion
			$var[171] = $this->Formato1($this->data["catp02_ficha_datos"]["porce_refraccion"]); // porce_refaccion
			$var[172] = $this->data["catp02_ficha_datos"]["edad_efectiva"]!=null ? $this->data["catp02_ficha_datos"]["edad_efectiva"] : 0; // edad_efectiva
			$var[173] = $this->data["catp02_ficha_datos"]["nro_niveles"]; // numero_niveles
			$var[174] = $this->data["catp02_ficha_datos"]["nro_edificaciones"]; // numero_edificaciones
			$var[175] = $this->data["catp02_ficha_datos"]["nro_familias"]; // numero_familias
			$var[176] = $this->Formato1($this->data["catp02_ficha_datos"]["registro_area_terreno"]); // registro_area_terreno

			$var[177] = (int) $this->data["catp02_ficha_datos"]["numero_construccion"]; // ingenieria_nro_constru
			 // ingenieria_fecha_constru:
			$this->data["catp02_ficha_datos"]["fecha_ing_munc"]!="" ? $var[178] = $this->Cfecha($this->data["catp02_ficha_datos"]["fecha_ing_munc"],"A-M-D")
			: $var[178] = "1900-01-01"; // ingenieria_fecha_constru
			$var[179] = (int) $this->data["catp02_ficha_datos"]["numero_habitabilidad"]; // ingenieria_nro_habita
			// ingenieria_fecha_habita:
			$this->data["catp02_ficha_datos"]["fecha_ing_munh"]!="" ? $var[180] = $this->Cfecha($this->data["catp02_ficha_datos"]["fecha_ing_munh"],"A-M-D")
			: $var[180] = "1900-01-01"; // ingenieria_fecha_habita
			$var[181] = (int) $this->data["catp02_ficha_datos"]["numero_demolicion"]; // ingenieria_nro_demolicion
			// ingenieria_fecha_demolicion:
			$this->data["catp02_ficha_datos"]["fecha_ing_mund"]!="" ? $var[182] = $this->Cfecha($this->data["catp02_ficha_datos"]["fecha_ing_mund"],"A-M-D")
			: $var[182] = "1900-01-01"; // ingenieria_fecha_demolicion

			$var[183] = $this->Formato1($this->data["catp02_ficha_datos"]["monto_hipoteca"]); // afectacion_hipoteca
			$var[184] = $this->Formato1($this->data["catp02_ficha_datos"]["monto_expropiacion"]); // afectacion_expropiacion
			$var[185] = $this->Formato1($this->data["catp02_ficha_datos"]["area_cc_a"]); // categoria_a_area
			$var[186] = $this->Formato1($this->data["catp02_ficha_datos"]["porc_cc_a"]); // categoria_a_porc
			$var[187] = $this->Formato1($this->data["catp02_ficha_datos"]["area_cc_b"]); // categoria_b_area
			$var[188] = $this->Formato1($this->data["catp02_ficha_datos"]["porc_cc_b"]); // categoria_b_porc
			$var[189] = $this->Formato1($this->data["catp02_ficha_datos"]["area_cc_c"]); // categoria_c_area
			$var[190] = $this->Formato1($this->data["catp02_ficha_datos"]["porc_cc_c"]); // categoria_c_porc
			$var[191] = $this->Formato1($this->data["catp02_ficha_datos"]["area_cc_d"]); // categoria_d_area
			$var[192] = $this->Formato1($this->data["catp02_ficha_datos"]["porc_cc_d"]); // categoria_d_porc
			$var[193] = $this->Formato1($this->data["catp02_ficha_datos"]["area_cc_e"]); // categoria_e_area
			$var[194] = $this->Formato1($this->data["catp02_ficha_datos"]["porc_cc_e"]); // categoria_e_porc

			$var[195] = $this->Formato1($this->data["catp02_ficha_datos"]["area_cc_f"]); // categoria_f_area
			$var[196] = $this->Formato1($this->data["catp02_ficha_datos"]["porc_cc_f"]); // categoria_f_porc
			$var[197] = $this->Formato1($this->data["catp02_ficha_datos"]["total_porc_const"]); // categoria_total_porc
			$var[198] = $this->Formato1($this->data["catp02_ficha_datos"]["registro_area_construccion"]); // registro_area_construccion
			$var[199] = $this->data["catp02_ficha_datos"]["serv_inm_abast_agua"]; // radio_abaste_agua
			$var[200] = $this->data["catp02_ficha_datos"]["serv_inm_abastag_dist"]; // radio_abaste_agua_distrib
			$var[201] = $this->data["catp02_ficha_datos"]["serv_inm_electrico"]; // radio_electricidad
			$var[202] = $this->data["catp02_ficha_datos"]["espec_electric"]; // medidor_electricidad
			$var[203] = $this->data["catp02_ficha_datos"]["serv_inm_energia"]; // radio_energia_cocinar
			$var[204] = (int) $this->data["catp02_ficha_datos"]["numero_serv_inm_telef"]; // telefono_nro_lineas
			$var[205] = (int) $this->data["catp02_ficha_datos"]["numero_serv_inm_resid"]; // telefono_nro_resid
			$var[206] = (int) $this->data["catp02_ficha_datos"]["numero_serv_inm_com"]; // telefono_nro_comer
			$var[207] = (int) $this->data["catp02_ficha_datos"]["numero_serv_inm_otro"]; // telefono_nro_otro
			$var[208] = (int) $this->data["catp02_ficha_datos"]["numero_serv_inm_sinserv"]; // telefono_nro_sin_servicio
			$var[209] = $this->data["catp02_ficha_datos"]["serv_inm_internet"]; // radio_internet
			$var[210] = $this->data["catp02_ficha_datos"]["serv_inm_agua_servida"]; // radio_aguas_servidas
			$var[211] = $this->data["catp02_ficha_datos"]["serv_inm_aseo_urbano"]; // radio_aseo_urbano
			$var[212] = $this->data["catp02_ficha_datos"]["serv_inm_aseou_dist"]; // radio_aseo_urbano_distrib
			$var[213] = $this->data["catp02_ficha_datos"]["observaciones_poblac"]; // observaciones_construccion


					/* ----- BLOQUE QUINTO::5 ----- */
				/**  **** VALORACION ECONOMICA **** */

            $var[214] = $this->Formato1($this->data["catp02_ficha_datos"]["valoracion_area"]); // terreno_area
            $var[215] = $this->Formato1_cantidad($this->data["catp02_ficha_datos"]["valoracion_valor_unitario"]); // terreno_valor_unitario
            $var[216] = $this->data["catp02_ficha_datos"]["valoracion_sector"]; // terreno_sector
            $var[217] = $this->Formato1($this->data["catp02_ficha_datos"]["valoracion_ajuste_area"]); // terreno_ajuste_area
            $var[218] = $this->data["catp02_ficha_datos"]["valoracion_ajuste_forma"]; // terreno_ajuste_forma
            $var[219] = $this->Formato1_cantidad($this->data["catp02_ficha_datos"]["valoracion_valor_ajustado"]); // terreno_valor_ajustado
            $var[220] = $this->Formato1($this->data["catp02_ficha_datos"]["valoracion_valor_total"]); // terreno_valor_total

            $var[221] = $this->Formato1($this->data["catp02_ficha_datos"]["area_total"]); // construccion_area_total
            $var[222] = $this->Formato1_cantidad($this->data["catp02_ficha_datos"]["c_valor_construccion"]); // construccion_valor_promedio_m2
			$var[223] = $this->Formato1($this->data["catp02_ficha_datos"]["d_valor_construccion"]); // construccion_total_variacion_m2
			$var[224] = $this->Formato1_cantidad($this->data["catp02_ficha_datos"]["monto_tota_variables_c"]); // construccion_total_valor_ajustado_m2

			$var[225] = $this->Formato1($this->data["catp02_ficha_datos"]["d_valor_montoajust"]); // construccion_monto_ajustado
            $var[226] = $this->Formato1($this->data["catp02_ficha_datos"]["d_valor_depreciac"]); // construccion_porc_depre
            $var[227] = $this->Formato1($this->data["catp02_ficha_datos"]["monto_total_de_construccion"]); // construccion_valor_total
            $var[228] = $this->Formato1($this->data["catp02_ficha_datos"]["terreno_construccion"]); // valor_total_inmueble
            $var[229] = $this->Formato1($this->data["catp02_ficha_datos"]["impuesto_anual"]); // impuesto_anual
            $var[230] = $this->data["catp02_ficha_datos"]["observaciones_ficha"]; // observaciones_calculos
			$var[255] = (int) $this->data["catp02_ficha_datos"]["codigo_planta"]; // cod_zona

						/* ----- BLOQUE SEXTO::6 ----- */
			/**  **** PESTAÑA::LINDEROS Y COORDENADAS **** */

            $var[231] = $this->data["catp02_ficha_datos"]["lindero_norte"]; // lindero_norte
            $var[232] = $this->data["catp02_ficha_datos"]["lindero_sur"]; // lindero_sur
            $var[233] = $this->data["catp02_ficha_datos"]["lindero_este"]; // lindero_este
            $var[234] = $this->data["catp02_ficha_datos"]["lindero_oeste"]; // lindero_oeste
            $var[235] = $this->data["catp02_ficha_datos"]["situacion_relativa"]; // situacion_relativa

            $var[236] = $this->data["catp02_ficha_datos"]["coordenada_norte"]; // coordenada_norte
            $var[237] = $this->data["catp02_ficha_datos"]["coordenada_este"]; // coordenada_este
            $var[238] = $this->data["catp02_ficha_datos"]["huso"]; // huso

            $var[239] = $this->data["catp02_ficha_datos"]["observaciones_lind_coord"]; // observaciones_levantamiento
            $var[240] = $this->Cfecha($this->data["catp02_ficha_datos"]["fecha_primera_visita"],"A-M-D"); // fecha_primera_visita
            $var[241] = $this->Cfecha($this->data["catp02_ficha_datos"]["fecha_levantamiento"],"A-M-D"); // fecha_levantamiento

            $var[242] = $this->data["catp02_ficha_datos"]["elaborado_nombre"]; // elaborado_nombre
            $var[243] = $this->data["catp02_ficha_datos"]["elaborado_ci"]; // elaborado_ci
            $var[244] = $this->data["catp02_ficha_datos"]["revisado_nombre"]; // revisado_nombre
            $var[245] = $this->data["catp02_ficha_datos"]["revisado_ci"]; // revisado_ci


						/* ----- BLOQUE SEPTIMO::7 ----- */
						/**  **** PESTAÑA::CROQUIS **** */

            $var[246] = $this->Cfecha($this->data["catp02_ficha_datos"]["croquis_fecha"],"A-M-D"); // croquis_fecha
            $var[247] = $this->data["catp02_ficha_datos"]["croquis_escala"]; // croquis_escala
            $var[248] = $this->data["catp02_ficha_datos"]["croquis_observaciones"]; // croquis_observaciones
            $var[249] = null; // $this->Formato1($this->data["catp02_ficha_datos"]["calculo_terreno"]); croquis_calculo_terreno:: quitar character varying(50) a numeric(26,2)
            $var[250] = null; // $this->Formato1($this->data["catp02_ficha_datos"]["calculo_construccion"]); croquis_calculo_construccion:: quitar character varying(50) a numeric(26,2)

            $var[251] = $this->data["catp02_ficha_datos"]["elaborado_nombre_c"]; // croquis_elaborado_nombre
            $var[252] = $this->data["catp02_ficha_datos"]["elaborado_ci_c"]; // croquis_elaborado_ci
            $var[253] = $this->data["catp02_ficha_datos"]["revisado_nombre_c"]; // croquis_revisado_nombre
            $var[254] = $this->data["catp02_ficha_datos"]["revisado_ci_c"]; // croquis_revisado_ci


	$SQ_INSERT = "BEGIN; INSERT INTO catd02_ficha_datos(cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep,
			cod_ficha, cod_inscripcion, fecha_inscripcion, cod_control_archivo, ano_ordenanza,
			cod_ant_edo, cod_ant_mun, cod_ant_prr, cod_ant_sec, cod_ant_man, cod_ant_par, cod_ant_blo, cod_ant_piso, cod_ant_apto,
			cod_act_edo, cod_act_mun, cod_act_prr, cod_act_amb_t, cod_act_amb, cod_act_sec, cod_act_man, cod_act_par, cod_act_sbp, cod_act_niv, cod_act_und,
			parroquia, radio_localidad_inmueble, nombre_localidad, radio_uno, direccion_uno, radio_dos, direccion_dos, radio_tres, direccion_tres, radio_vivienda_uno, tipo_vivienda_otro_uno, nombre_inmueble, numero_civico, telefono_inmueble, punto_referencia_inmueble, radio_condicion_ocupacion,

			personalidad_juridica, cedula_rif, nombre_ocupante, localidad_ocupante, radio_localidad_ocupante, urb_barrio_sector_ocupante, radio_cuatro_ocupante, direccion_cuatro_ocupante, radio_cinco_ocupante, direccion_cinco_ocupante, radio_seis_ocupante, direccion_seis_ocupante, radio_vivienda_dos_ocupante, tipo_vivienda_otro_dos_ocupante, nombre_inmueble_ocupante, numero_civico_ocupante, telefono_ocupante, email_ocupante,
			personalidad_juridica_repre, cedula_rif_repre, nombre_repre, parroquia_repre, radio_localidad_repre, nombre_localidad_repre, radio_cuatro, direccion_cuatro, radio_cinco, direccion_cinco, radio_seis, direccion_seis, radio_vivienda_dos, tipo_vivienda_otro_dos, nombre_inmueble_repre, numero_civico_repre, telefono_repre, email_repre,

			radio_topo, radio_acceso, radio_forma, radio_ubica, radio_entorno, tilde_uso, tilde_mejora, radio_tenencia, radio_regimen, tilde_servicio, radio_afectacion, normativa_gaceta, normativa_resolucion, normativa_fecha, ubica_zonificacion,

			radio_tipo, radio_descripcionuso, radio_tenencia_const, radio_regi_prop, declara_canon, declara_fecha, declara_ingreso, tilde_soporte, tilde_techo, tilde_cubierta_externa, tilde_cubierta_interna, tilde_pared_tipo, tilde_pared_acaba, tilde_pared_pintura, tilde_insta_electricas, tilde_piso, bano_nro_banera, bano_nro_wc, bano_nro_bidet, bano_nro_lavamanos, bano_nro_ducha, bano_nro_ceramica_pri, bano_nro_ceramica_seg, bano_nro_otro,
			ventana_nro_ventanal, ventana_tilde_ventanal, ventana_nro_celosia, ventana_tilde_celosia, ventana_nro_corredera, ventana_tilde_corredera, ventana_nro_basculante, ventana_tilde_basculante, ventana_nro_batiente, ventana_tilde_batiente, ventana_nro_panoramica, ventana_tilde_panoramica, puerta_nro_entamb_fina, puerta_tilde_entamb_fina, puerta_nro_entamb_econo, puerta_tilde_entamb_econo, puerta_nro_madera_cepi, puerta_tilde_madera_cepi,
			puerta_nro_metalica, puerta_tilde_metalica, puerta_nro_seguridad, puerta_tilde_seguridad, puerta_nro_vidrio, puerta_tilde_vidrio, puerta_nro_otro, puerta_tilde_otro, ambiente_nro_dormitorio, ambiente_nro_comedor, ambiente_nro_sala, ambiente_nro_bano_econo, ambiente_nro_bano_lujoso, ambiente_nro_cocina, ambiente_nro_area_serv, ambiente_nro_estudio, ambiente_nro_garaje, ambiente_nro_estaciona, ambiente_nro_maletero, ambiente_nro_sotano,
			ambiente_nro_letrina, ambiente_nro_otro, compleme_nro_ascensor, compleme_nro_aire_acondi, compleme_nro_reja_puerta, compleme_nro_reja_ventana, compleme_nro_closet, compleme_nro_ceramica, compleme_nro_puerta_santa, compleme_nro_hidro, compleme_nro_jacuzzi, compleme_nro_calentador, compleme_nro_piscina, compleme_cap_piscina, compleme_nro_tanque, compleme_tipo_tanque, compleme_cap_tanque, compleme_nro_otro, radio_conserva, ano_construccion,
			ano_refaccion, porce_refaccion, edad_efectiva, numero_niveles, numero_edificaciones, numero_familias, registro_area_terreno, ingenieria_nro_constru, ingenieria_fecha_constru, ingenieria_nro_habita, ingenieria_fecha_habita, ingenieria_nro_demolicion, ingenieria_fecha_demolicion, afectacion_hipoteca, afectacion_expropiacion, categoria_a_area, categoria_a_porc, categoria_b_area, categoria_b_porc, categoria_c_area, categoria_c_porc,
			categoria_d_area, categoria_d_porc, categoria_e_area, categoria_e_porc, categoria_f_area, categoria_f_porc, categoria_total_porc, registro_area_construccion, radio_abaste_agua, radio_abaste_agua_distrib, radio_electricidad, medidor_electricidad, radio_energia_cocinar, telefono_nro_lineas, telefono_nro_resid, telefono_nro_comer, telefono_nro_otro, telefono_nro_sin_servicio, radio_internet, radio_aguas_servidas, radio_aseo_urbano, radio_aseo_urbano_distrib, observaciones_construccion,

			terreno_area, terreno_valor_unitario, terreno_sector, terreno_ajuste_area, terreno_ajuste_forma, terreno_valor_ajustado, terreno_valor_total, construccion_area_total, construccion_valor_promedio_m2, construccion_total_variacion_m2, construccion_total_valor_ajustado_m2, construccion_monto_ajustado, construccion_porc_depre, construccion_valor_total, valor_total_inmueble, impuesto_anual, observaciones_calculos,

			lindero_norte, lindero_sur, lindero_este, lindero_oeste, situacion_relativa, coordenada_norte, coordenada_este, huso, observaciones_levantamiento, fecha_primera_visita, fecha_levantamiento, elaborado_nombre, elaborado_ci, revisado_nombre, revisado_ci,

			croquis_fecha, croquis_escala, croquis_observaciones, croquis_calculo_terreno, croquis_calculo_construccion, croquis_elaborado_nombre, croquis_elaborado_ci, croquis_revisado_nombre, croquis_revisado_ci, cod_zona) VALUES (".$this->SQLCAIN().",
			".$var["numero_ficha"].", ".$var["numero_inscripcion"].", '".$var["fecha_inscripcion"]."', ".$var["numero_archivo"].", ".$var["ano_ordenanza"].",
            '".$var[1]."', '".$var[2]."', '".$var[3]."', '".$var[4]."', '".$var[5]."', '".$var[6]."', '".$var[7]."', '".$var[8]."', '".$var[9]."',
            '".$var[10]."', '".$var[11]."', '".$var[12]."', '".$var[13]."', '".$var[14]."', '".$var[15]."', '".$var[16]."', '".$var[17]."', '".$var[18]."', '".$var[19]."', '".$var[20]."',
            '".$var[21]."', '".$var[22]."', '".$var[23]."', '".$var[24]."', '".$var[25]."', '".$var[26]."', '".$var[27]."', '".$var[28]."', '".$var[29]."', '".$var[30]."', '".$var[31]."', '".$var[32]."', '".$var[33]."', '".$var[34]."', '".$var[35]."', '".$var[36]."',

			'".$var[37]."', '".$var[38]."', '".$var[39]."', '".$var[40]."', '".$var[41]."', '".$var[42]."', '".$var[43]."', '".$var[44]."', '".$var[45]."', '".$var[46]."', '".$var[47]."', '".$var[48]."', '".$var[49]."', '".$var[50]."', '".$var[51]."', '".$var[52]."', '".$var[53]."', '".$var[54]."', '".$var[55]."', '".$var[56]."', '".$var[57]."', '".$var[58]."',
			'".$var[59]."', '".$var[60]."', '".$var[61]."', '".$var[62]."', '".$var[63]."', '".$var[64]."', '".$var[65]."', '".$var[66]."', '".$var[67]."', '".$var[68]."', '".$var[69]."', '".$var[70]."', '".$var[71]."', '".$var[72]."',

			'".$var[73]."', '".$var[74]."', '".$var[75]."', '".$var[76]."', '".$var[77]."', '".$var[78]."', '".$var[79]."', '".$var[80]."', '".$var[81]."', '".$var[82]."', '".$var[83]."', '".$var[84]."', '".$var[85]."', '".$var[86]."', '".$var[87]."',

			'".$var[88]."', '".$var[89]."', '".$var[90]."', '".$var[91]."', '".$var[92]."', '".$var[93]."', '".$var[94]."', '".$var[95]."', '".$var[96]."', '".$var[97]."', '".$var[98]."', '".$var[99]."', '".$var[100]."', '".$var[101]."', '".$var[102]."', '".$var[103]."', '".$var[104]."', '".$var[105]."', '".$var[106]."', '".$var[107]."', '".$var[108]."', '".$var[109]."', '".$var[110]."',
			'".$var[111]."', '".$var[112]."', '".$var[113]."', '".$var[114]."', '".$var[115]."', '".$var[116]."', '".$var[117]."', '".$var[118]."', '".$var[119]."', '".$var[120]."', '".$var[121]."', '".$var[122]."', '".$var[123]."', '".$var[124]."', '".$var[125]."',

			'".$var[126]."', '".$var[127]."', '".$var[128]."', '".$var[129]."', '".$var[130]."', '".$var[131]."', '".$var[132]."', '".$var[133]."', '".$var[134]."', '".$var[135]."', '".$var[136]."', '".$var[137]."', '".$var[138]."', '".$var[139]."', '".$var[140]."',
			'".$var[141]."', '".$var[142]."', '".$var[143]."', '".$var[144]."', '".$var[145]."', '".$var[146]."', '".$var[147]."', '".$var[148]."', '".$var[149]."', '".$var[150]."', '".$var[151]."', '".$var[152]."', '".$var[153]."', '".$var[154]."', '".$var[155]."',
			'".$var[156]."', '".$var[157]."', '".$var[158]."', '".$var[159]."', '".$var[160]."', '".$var[161]."', '".$var[162]."', '".$var[163]."', '".$var[164]."', '".$var[165]."', '".$var[166]."', '".$var[167]."', '".$var[168]."', '".$var[169]."', '".$var[170]."',
			'".$var[171]."', '".$var[172]."', '".$var[173]."', '".$var[174]."', '".$var[175]."', '".$var[176]."', '".$var[177]."', '".$var[178]."', '".$var[179]."', '".$var[180]."', '".$var[181]."', '".$var[182]."', '".$var[183]."', '".$var[184]."', '".$var[185]."',
			'".$var[186]."', '".$var[187]."', '".$var[188]."', '".$var[189]."', '".$var[190]."', '".$var[191]."', '".$var[192]."', '".$var[193]."', '".$var[194]."', '".$var[195]."', '".$var[196]."', '".$var[197]."', '".$var[198]."', '".$var[199]."', '".$var[200]."',
			'".$var[201]."', '".$var[202]."', '".$var[203]."', '".$var[204]."', '".$var[205]."', '".$var[206]."', '".$var[207]."', '".$var[208]."', '".$var[209]."', '".$var[210]."', '".$var[211]."', '".$var[212]."', '".$var[213]."',

			'".$var[214]."', '".$var[215]."', '".$var[216]."', '".$var[217]."', '".$var[218]."', '".$var[219]."', '".$var[220]."', '".$var[221]."', '".$var[222]."', '".$var[223]."', '".$var[224]."', '".$var[225]."', '".$var[226]."', '".$var[227]."', '".$var[228]."', '".$var[229]."', '".$var[230]."',

			'".$var[231]."', '".$var[232]."', '".$var[233]."', '".$var[234]."', '".$var[235]."', '".$var[236]."', '".$var[237]."', '".$var[238]."', '".$var[239]."', '".$var[240]."', '".$var[241]."', '".$var[242]."', '".$var[243]."', '".$var[244]."', '".$var[245]."',

			'".$var[246]."', '".$var[247]."', '".$var[248]."', '".$var[249]."', '".$var[250]."', '".$var[251]."', '".$var[252]."', '".$var[253]."', '".$var[254]."', '".$var[255]."');";


	$codig_ficha = isset($this->data["catp02_ficha_datos"]["numero_ficha"]) ? $this->data["catp02_ficha_datos"]["numero_ficha"] : -1;
	$ficha_enc=$this->catd02_ficha_datos->findCount($this->SQLCA()." and cod_ficha=".$codig_ficha);
  if($ficha_enc==0){

	$rs_insert=$this->catd02_ficha_datos->execute($SQ_INSERT);

		if($rs_insert>1){

            /**
             * ESTO VA PARA LA TABLA catd02_poblacion_adulta
             */

          if(isset($_SESSION["itemsn"]) && $_SESSION["itemsn"]!=null){ // ** POBLACION ADULTA **
		      $it=1;
		      foreach($_SESSION["itemsn"] as $nss){
			     if($nss!=null){
  			         $POBLACION_A[]="(".$this->SQLCAIN().",".$var["numero_ficha"].",$it,".$nss[0].",".$nss[1].",'".$nss[2]."','".$nss[3]."','".$nss[4]."','".$nss[5]."')";
			    	 $it++;
			    }
		     } // fin foreach
             $VALUES_POBA=implode(',', $POBLACION_A);
			 $rsvcp=$this->catd02_ficha_tipologia->execute("INSERT INTO catd02_poblacion_adulta VALUES ".$VALUES_POBA.";");
	      } //fin isset session itemsn



            /**
             * ESTO VA PARA LA TABLA catd02_poblacion_infantil
             */

          if(isset($_SESSION["itemsn2"]) && $_SESSION["itemsn2"]!=null){ // ** POBLACION INFANTIL **
		      $it=1;
		      foreach($_SESSION["itemsn2"] as $nss){
			     if($nss!=null){
  			         $POBLACION_I[]="(".$this->SQLCAIN().",".$var["numero_ficha"].",$it,".$nss[0].",".$nss[1].",'".$nss[2]."','".$nss[3]."','".$nss[4]."')";
			    	 $it++;
			    }
		     } // fin foreach
             $VALUES_POBI=implode(',', $POBLACION_I);
			 $rsvcp=$this->catd02_ficha_tipologia->execute("INSERT INTO catd02_poblacion_infantil VALUES ".$VALUES_POBI.";");
	      } //fin isset session itemsn2



	if($var[198]>0){

            /**
             * ESTO VA PARA LA TABLA catd02_ficha_tipologia
             */

          if(isset($_SESSION["items_2"]) && $_SESSION["items_2"]!=null){ // ** VARIABLES_TIPOLOGIA **
		      $it=1;
		      foreach($_SESSION["items_2"] as $nss){
			     if($nss!=null){
  			         $VARIABLES_TIPOLOGIA[]="(".$this->SQLCAIN().",".$var["numero_ficha"].",$it,'".$nss[0]."',".$this->Formato1($nss[1]).",".$this->Formato1_cantidad($nss[2]).",".$this->Formato1($nss[5]).",".$this->Formato1($nss[3]).",".$this->Formato1($nss[4]).")";
			    	 $it++;
			    }
		     } // fin foreach
             $VALUES_TIPOLOGIA=implode(',', $VARIABLES_TIPOLOGIA);
			 $rsvcp=$this->catd02_ficha_tipologia->execute("INSERT INTO catd02_ficha_tipologia VALUES ".$VALUES_TIPOLOGIA.";");
	      } //fin isset session items_2



         /**
          * ESTO VA PARA LA TABLA catd02_ficha_variables
          */

	if(isset($rsvcp) && $rsvcp>1){
         if(isset($_SESSION["items"]) && $_SESSION["items"]!=null){ // *** VARIABLES DE LA CONSTRUCCION ***
		      $it=1;
		      foreach($_SESSION["items"] as $nss){
			     if($nss!=null){
			     	$VARIABLES_CONSTRUCCION[]="(".$this->SQLCAIN().",".$var["numero_ficha"].",$it,'".$nss[6]."',".$nss[0].",".$nss[2].",".$this->Formato1($nss[4]).")";
			    	$it++;
			    }
		     }
             $VALUES_VARIABLES_CONSTRUCCION=implode(',', $VARIABLES_CONSTRUCCION);
             $rsvcp=$this->catd02_ficha_variables->execute("INSERT INTO catd02_ficha_variables VALUES ".$VALUES_VARIABLES_CONSTRUCCION.";");
	      } //fin isset session items
	}
	} // fin if Posee Construccion +

            	$this->set("FICHA_GUARDADA",false);
            	echo "<script>document.getElementById('guardar').disabled=false;</script>";
				$this->set("Message_existe","La Ficha Fue Registrada Exitosamente.");
				$this->catd02_numero_ficha->execute("UPDATE catd02_numero_ficha SET situacion=3 WHERE ".$this->SQLCA()." and numero=".$codigo_ficha." and situacion<3;");
	            $this->catd02_numero_inscripcion->execute("UPDATE catd02_numero_inscripcion SET situacion=3 WHERE ".$this->SQLCA()." and numero=".$codigo_inscripcion." and situacion<3;");
	            $this->catd02_numero_inscripcion->execute("UPDATE catd02_numero_archivo SET situacion=3 WHERE ".$this->SQLCA()." and numero=".$codigo_archivo." and situacion<3;");
				$this->catd02_ficha_datos->execute("COMMIT;");
				$this->index2();
				$this->render("index2");

		}else{
				$this->set("FICHA_GUARDADA",false);
				echo "<script>document.getElementById('guardar').disabled=false;</script>";
				$this->set("errorMessage","Disculpe, los datos no fueron Guardados - intente nuevamente");
				$this->catd02_ficha_datos->execute("ROLLBACK;");
				$this->catd02_numero_ficha->execute("UPDATE catd02_numero_ficha SET situacion=1 WHERE ".$this->SQLCA()." and numero=".$codigo_ficha." and situacion<3;");
	            $this->catd02_numero_inscripcion->execute("UPDATE catd02_numero_inscripcion SET situacion=1 WHERE ".$this->SQLCA()." and numero=".$codigo_inscripcion." and situacion<3;");
	            $this->catd02_numero_inscripcion->execute("UPDATE catd02_numero_archivo SET situacion=1 WHERE ".$this->SQLCA()." and numero=".$codigo_archivo." and situacion<3;");
				$this->index2();
				$this->render("index2");
		}



  }else if($vmmod!=null && $vmmod==1){


	$SQ_UPDATE = "UPDATE catd02_ficha_datos SET cod_presi='".$this->verifica_SS(1)."', cod_entidad='".$this->verifica_SS(2)."', cod_tipo_inst='".$this->verifica_SS(3)."', cod_inst='".$this->verifica_SS(4)."', cod_dep='".$this->verifica_SS(5)."',
			cod_ficha=".$var["numero_ficha"].", cod_inscripcion=".$var["numero_inscripcion"].", fecha_inscripcion='".$var["fecha_inscripcion"]."', cod_control_archivo=".$var["numero_archivo"].", ano_ordenanza=".$var["ano_ordenanza"].",
			cod_ant_edo='".$var[1]."', cod_ant_mun='".$var[2]."', cod_ant_prr='".$var[3]."', cod_ant_sec='".$var[4]."', cod_ant_man='".$var[5]."', cod_ant_par='".$var[6]."', cod_ant_blo='".$var[7]."', cod_ant_piso='".$var[8]."', cod_ant_apto='".$var[9]."',
			cod_act_edo='".$var[10]."', cod_act_mun='".$var[11]."', cod_act_prr='".$var[12]."', cod_act_amb_t='".$var[13]."', cod_act_amb='".$var[14]."', cod_act_sec='".$var[15]."', cod_act_man='".$var[16]."', cod_act_par='".$var[17]."', cod_act_sbp='".$var[18]."', cod_act_niv='".$var[19]."', cod_act_und='".$var[20]."',
			parroquia='".$var[21]."', radio_localidad_inmueble='".$var[22]."', nombre_localidad='".$var[23]."', radio_uno='".$var[24]."', direccion_uno='".$var[25]."', radio_dos='".$var[26]."', direccion_dos='".$var[27]."', radio_tres='".$var[28]."', direccion_tres='".$var[29]."', radio_vivienda_uno='".$var[30]."', tipo_vivienda_otro_uno='".$var[31]."', nombre_inmueble='".$var[32]."', numero_civico='".$var[33]."', telefono_inmueble='".$var[34]."', punto_referencia_inmueble='".$var[35]."', radio_condicion_ocupacion='".$var[36]."',

			personalidad_juridica='".$var[37]."', cedula_rif='".$var[38]."', nombre_ocupante='".$var[39]."', localidad_ocupante='".$var[40]."', radio_localidad_ocupante='".$var[41]."', urb_barrio_sector_ocupante='".$var[42]."', radio_cuatro_ocupante='".$var[43]."', direccion_cuatro_ocupante='".$var[44]."', radio_cinco_ocupante='".$var[45]."', direccion_cinco_ocupante='".$var[46]."', radio_seis_ocupante='".$var[47]."', direccion_seis_ocupante='".$var[48]."', radio_vivienda_dos_ocupante='".$var[49]."', tipo_vivienda_otro_dos_ocupante='".$var[50]."', nombre_inmueble_ocupante='".$var[51]."', numero_civico_ocupante='".$var[52]."', telefono_ocupante='".$var[53]."', email_ocupante='".$var[54]."',
			personalidad_juridica_repre='".$var[55]."', cedula_rif_repre='".$var[56]."', nombre_repre='".$var[57]."', parroquia_repre='".$var[58]."', radio_localidad_repre='".$var[59]."', nombre_localidad_repre='".$var[60]."', radio_cuatro='".$var[61]."', direccion_cuatro='".$var[62]."', radio_cinco='".$var[63]."', direccion_cinco='".$var[64]."', radio_seis='".$var[65]."', direccion_seis='".$var[66]."', radio_vivienda_dos='".$var[67]."', tipo_vivienda_otro_dos='".$var[68]."', nombre_inmueble_repre='".$var[69]."', numero_civico_repre='".$var[70]."', telefono_repre='".$var[71]."', email_repre='".$var[72]."',

			radio_topo='".$var[73]."', radio_acceso='".$var[74]."', radio_forma='".$var[75]."', radio_ubica='".$var[76]."', radio_entorno='".$var[77]."', tilde_uso='".$var[78]."', tilde_mejora='".$var[79]."', radio_tenencia='".$var[80]."', radio_regimen='".$var[81]."', tilde_servicio='".$var[82]."', radio_afectacion='".$var[83]."', normativa_gaceta='".$var[84]."', normativa_resolucion='".$var[85]."', normativa_fecha='".$var[86]."', ubica_zonificacion='".$var[87]."',

			radio_tipo='".$var[88]."', radio_descripcionuso='".$var[89]."', radio_tenencia_const='".$var[90]."', radio_regi_prop='".$var[91]."', declara_canon='".$var[92]."', declara_fecha='".$var[93]."', declara_ingreso='".$var[94]."', tilde_soporte='".$var[95]."', tilde_techo='".$var[96]."', tilde_cubierta_externa='".$var[97]."', tilde_cubierta_interna='".$var[98]."', tilde_pared_tipo='".$var[99]."', tilde_pared_acaba='".$var[100]."', tilde_pared_pintura='".$var[101]."', tilde_insta_electricas='".$var[102]."', tilde_piso='".$var[103]."', bano_nro_banera='".$var[104]."', bano_nro_wc='".$var[105]."', bano_nro_bidet='".$var[106]."', bano_nro_lavamanos='".$var[107]."', bano_nro_ducha='".$var[108]."', bano_nro_ceramica_pri='".$var[109]."', bano_nro_ceramica_seg='".$var[110]."',
			bano_nro_otro='".$var[111]."', ventana_nro_ventanal='".$var[112]."', ventana_tilde_ventanal='".$var[113]."', ventana_nro_celosia='".$var[114]."', ventana_tilde_celosia='".$var[115]."', ventana_nro_corredera='".$var[116]."', ventana_tilde_corredera='".$var[117]."', ventana_nro_basculante='".$var[118]."', ventana_tilde_basculante='".$var[119]."', ventana_nro_batiente='".$var[120]."', ventana_tilde_batiente='".$var[121]."', ventana_nro_panoramica='".$var[122]."', ventana_tilde_panoramica='".$var[123]."', puerta_nro_entamb_fina='".$var[124]."', puerta_tilde_entamb_fina='".$var[125]."',

			puerta_nro_entamb_econo='".$var[126]."', puerta_tilde_entamb_econo='".$var[127]."', puerta_nro_madera_cepi='".$var[128]."', puerta_tilde_madera_cepi='".$var[129]."', puerta_nro_metalica='".$var[130]."', puerta_tilde_metalica='".$var[131]."', puerta_nro_seguridad='".$var[132]."', puerta_tilde_seguridad='".$var[133]."', puerta_nro_vidrio='".$var[134]."', puerta_tilde_vidrio='".$var[135]."', puerta_nro_otro='".$var[136]."', puerta_tilde_otro='".$var[137]."', ambiente_nro_dormitorio='".$var[138]."', ambiente_nro_comedor='".$var[139]."', ambiente_nro_sala='".$var[140]."',
			ambiente_nro_bano_econo='".$var[141]."', ambiente_nro_bano_lujoso='".$var[142]."', ambiente_nro_cocina='".$var[143]."', ambiente_nro_area_serv='".$var[144]."', ambiente_nro_estudio='".$var[145]."', ambiente_nro_garaje='".$var[146]."', ambiente_nro_estaciona='".$var[147]."', ambiente_nro_maletero='".$var[148]."', ambiente_nro_sotano='".$var[149]."', ambiente_nro_letrina='".$var[150]."', ambiente_nro_otro='".$var[151]."', compleme_nro_ascensor='".$var[152]."', compleme_nro_aire_acondi='".$var[153]."', compleme_nro_reja_puerta='".$var[154]."', compleme_nro_reja_ventana='".$var[155]."',
			compleme_nro_closet='".$var[156]."', compleme_nro_ceramica='".$var[157]."', compleme_nro_puerta_santa='".$var[158]."', compleme_nro_hidro='".$var[159]."', compleme_nro_jacuzzi='".$var[160]."', compleme_nro_calentador='".$var[161]."', compleme_nro_piscina='".$var[162]."', compleme_cap_piscina='".$var[163]."', compleme_nro_tanque='".$var[164]."', compleme_tipo_tanque='".$var[165]."', compleme_cap_tanque='".$var[166]."', compleme_nro_otro='".$var[167]."', radio_conserva='".$var[168]."', ano_construccion='".$var[169]."', ano_refaccion='".$var[170]."',
			porce_refaccion='".$var[171]."', edad_efectiva='".$var[172]."', numero_niveles='".$var[173]."', numero_edificaciones='".$var[174]."', numero_familias='".$var[175]."', registro_area_terreno='".$var[176]."', ingenieria_nro_constru='".$var[177]."', ingenieria_fecha_constru='".$var[178]."', ingenieria_nro_habita='".$var[179]."', ingenieria_fecha_habita='".$var[180]."', ingenieria_nro_demolicion='".$var[181]."', ingenieria_fecha_demolicion='".$var[182]."', afectacion_hipoteca='".$var[183]."', afectacion_expropiacion='".$var[184]."', categoria_a_area='".$var[185]."',
			categoria_a_porc='".$var[186]."', categoria_b_area='".$var[187]."', categoria_b_porc='".$var[188]."', categoria_c_area='".$var[189]."', categoria_c_porc='".$var[190]."', categoria_d_area='".$var[191]."', categoria_d_porc='".$var[192]."', categoria_e_area='".$var[193]."', categoria_e_porc='".$var[194]."', categoria_f_area='".$var[195]."', categoria_f_porc='".$var[196]."', categoria_total_porc='".$var[197]."', registro_area_construccion='".$var[198]."', radio_abaste_agua='".$var[199]."', radio_abaste_agua_distrib='".$var[200]."',
			radio_electricidad='".$var[201]."', medidor_electricidad='".$var[202]."', radio_energia_cocinar='".$var[203]."', telefono_nro_lineas='".$var[204]."', telefono_nro_resid='".$var[205]."', telefono_nro_comer='".$var[206]."', telefono_nro_otro='".$var[207]."', telefono_nro_sin_servicio='".$var[208]."', radio_internet='".$var[209]."', radio_aguas_servidas='".$var[210]."', radio_aseo_urbano='".$var[211]."', radio_aseo_urbano_distrib='".$var[212]."', observaciones_construccion='".$var[213]."',

			terreno_area='".$var[214]."', terreno_valor_unitario='".$var[215]."', terreno_sector='".$var[216]."', terreno_ajuste_area='".$var[217]."', terreno_ajuste_forma='".$var[218]."', terreno_valor_ajustado='".$var[219]."', terreno_valor_total='".$var[220]."', construccion_area_total='".$var[221]."', construccion_valor_promedio_m2='".$var[222]."', construccion_total_variacion_m2='".$var[223]."', construccion_total_valor_ajustado_m2='".$var[224]."', construccion_monto_ajustado='".$var[225]."', construccion_porc_depre='".$var[226]."', construccion_valor_total='".$var[227]."', valor_total_inmueble='".$var[228]."', impuesto_anual='".$var[229]."', observaciones_calculos='".$var[230]."',

			lindero_norte='".$var[231]."', lindero_sur='".$var[232]."', lindero_este='".$var[233]."', lindero_oeste='".$var[234]."', situacion_relativa='".$var[235]."', coordenada_norte='".$var[236]."', coordenada_este='".$var[237]."', huso='".$var[238]."', observaciones_levantamiento='".$var[239]."', fecha_primera_visita='".$var[240]."', fecha_levantamiento='".$var[241]."', elaborado_nombre='".$var[242]."', elaborado_ci='".$var[243]."', revisado_nombre='".$var[244]."', revisado_ci='".$var[245]."',

			croquis_fecha='".$var[246]."', croquis_escala='".$var[247]."', croquis_observaciones='".$var[248]."', croquis_calculo_terreno='".$var[249]."', croquis_calculo_construccion='".$var[250]."', croquis_elaborado_nombre='".$var[251]."', croquis_elaborado_ci='".$var[252]."', croquis_revisado_nombre='".$var[253]."', croquis_revisado_ci='".$var[254]."', cod_zona='".$var[255]."' WHERE ".$this->SQLCA()." and cod_ficha=".$var["numero_ficha"].";";


	$rs_update=$this->catd02_ficha_datos->execute($SQ_UPDATE);
	if($rs_update>1){

		$rws_delfv = $this->catd02_ficha_datos->execute("BEGIN; DELETE FROM catd02_ficha_variables WHERE ".$this->SQLCA()." and cod_ficha=".$var["numero_ficha"].";");
		$rws_delft = $this->catd02_ficha_datos->execute("DELETE FROM catd02_ficha_tipologia WHERE ".$this->SQLCA()." and cod_ficha=".$var["numero_ficha"].";");

		if($rws_delfv > 1 && $rws_delft > 1){

            /**
             * ESTO VA PARA LA TABLA catd02_ficha_tipologia
             */

          if(isset($_SESSION["items_2"]) && $_SESSION["items_2"]!=null){ // ** VARIABLES_TIPOLOGIA **
		      $it=1;
		      foreach($_SESSION["items_2"] as $nss){
			     if($nss!=null){
  			         $VARIABLES_TIPOLOGIA[]="(".$this->SQLCAIN().",".$var["numero_ficha"].",$it,'".$nss[0]."',".$this->Formato1($nss[1]).",".$this->Formato1_cantidad($nss[2]).",".$this->Formato1($nss[5]).",".$this->Formato1($nss[3]).",".$this->Formato1($nss[4]).")";
			    	 $it++;
			    }
		     } // fin foreach
             $VALUES_TIPOLOGIA=implode(',', $VARIABLES_TIPOLOGIA);
//print_r($VALUES_TIPOLOGIA);
			 $rsvcp=$this->catd02_ficha_tipologia->execute("INSERT INTO catd02_ficha_tipologia VALUES ".$VALUES_TIPOLOGIA.";");
	      } //fin isset session items_2



         /**
          * ESTO VA PARA LA TABLA catd02_ficha_variables
          */

	if(isset($rsvcp) && $rsvcp>1){
         if(isset($_SESSION["items"]) && $_SESSION["items"]!=null){ // *** VARIABLES DE LA CONSTRUCCION ***
		      $it=1;
		      foreach($_SESSION["items"] as $nss){
			     if($nss!=null){
			     	$VARIABLES_CONSTRUCCION[]="(".$this->SQLCAIN().",".$var["numero_ficha"].",$it,'".$nss[6]."',".$nss[0].",".$nss[2].",".$this->Formato1($nss[4]).")";
			    	$it++;
			    }
		     }
             $VALUES_VARIABLES_CONSTRUCCION=implode(',', $VARIABLES_CONSTRUCCION);
	      	 $rsvcp=$this->catd02_ficha_variables->execute("INSERT INTO catd02_ficha_variables VALUES ".$VALUES_VARIABLES_CONSTRUCCION.";");
	      } //fin isset session items
	}
		$this->catd02_ficha_datos->execute("COMMIT;");
		}else{ $this->catd02_ficha_datos->execute("ROLLBACK;"); }



		$rws_delfpa = $this->catd02_ficha_datos->execute("BEGIN; DELETE FROM catd02_poblacion_adulta WHERE ".$this->SQLCA()." and cod_ficha=".$var["numero_ficha"].";");
		$rws_delfpi = $this->catd02_ficha_datos->execute("DELETE FROM catd02_poblacion_infantil WHERE ".$this->SQLCA()." and cod_ficha=".$var["numero_ficha"].";");

		if($rws_delfpa > 1 && $rws_delfpi > 1){

            /**
             * ESTO VA PARA LA TABLA catd02_poblacion_adulta
             */

          if(isset($_SESSION["itemsn"]) && $_SESSION["itemsn"]!=null){ // ** POBLACION ADULTA **
		      $it=1;
		      foreach($_SESSION["itemsn"] as $nss){
			     if($nss!=null){
  			         $POBLACION_A[]="(".$this->SQLCAIN().",".$var["numero_ficha"].",$it,".$nss[0].",".$nss[1].",'".$nss[2]."','".$nss[3]."','".$nss[4]."','".$nss[5]."')";
			    	 $it++;
			    }
		     } // fin foreach
             $VALUES_POBA=implode(',', $POBLACION_A);
			 $rsvcp=$this->catd02_ficha_tipologia->execute("INSERT INTO catd02_poblacion_adulta VALUES ".$VALUES_POBA.";");
	      } //fin isset session itemsn



            /**
             * ESTO VA PARA LA TABLA catd02_poblacion_infantil
             */

          if(isset($_SESSION["itemsn2"]) && $_SESSION["itemsn2"]!=null){ // ** POBLACION INFANTIL **
		      $it=1;
		      foreach($_SESSION["itemsn2"] as $nss){
			     if($nss!=null){
  			         $POBLACION_I[]="(".$this->SQLCAIN().",".$var["numero_ficha"].",$it,".$nss[0].",".$nss[1].",'".$nss[2]."','".$nss[3]."','".$nss[4]."')";
			    	 $it++;
			    }
		     } // fin foreach
             $VALUES_POBI=implode(',', $POBLACION_I);
			 $rsvcp=$this->catd02_ficha_tipologia->execute("INSERT INTO catd02_poblacion_infantil VALUES ".$VALUES_POBI.";");
	      } //fin isset session itemsn2

		$this->catd02_ficha_datos->execute("COMMIT;");
		}else{ $this->catd02_ficha_datos->execute("ROLLBACK;"); }


		$this->set("FICHA_GUARDADA",false);
		// echo "<script>ver_documento('/catp02_ficha_datos/modificar_ficha/2','div_modifica')</script>";
		$this->set("Message_existe","Los Datos Fueron Modificados Exitosamente");

		if($pagina!=null){
			$this->consulta($pagina,$var["numero_ficha"],$var[56]);
			$this->render("consulta");
		}else{
			$this->seleccion('1',$var["numero_ficha"],$var[56]);
			$this->render("seleccion");
		}
	}else{
		$this->set("FICHA_GUARDADA",false);
		echo "<script>document.getElementById('guardar').disabled=false;</script>";
		$this->set("errorMessage","Los Datos No Fueron Modificados - intente nuevamente");
		if($pagina!=null){
			$this->consulta($pagina,$var["numero_ficha"],$var[56]);
			$this->render("consulta");
		}else{
			$this->seleccion('1',$var["numero_ficha"],$var[56]);
			$this->render("seleccion");
		}
	}



  }else if($ficha_enc > 0){
	$this->set("FICHA_GUARDADA",false);
	echo "<script>document.getElementById('guardar').disabled=false;</script>";
	$this->set("errorMessage","Disculpe, esta ficha ya se encuentra registrada");
  }else{
	$this->set("FICHA_GUARDADA",false);
	echo "<script>document.getElementById('guardar').disabled=false;</script>";
	$this->set("errorMessage","Disculpe, no se pudo procesar - intente nuevamente. verifique el n&uacute;mero de la ficha");
  }

	unset($var);
	if(isset($SQ_INSERT))
		unset($SQ_INSERT);
	elseif(isset($SQ_UPDATE))
		unset($SQ_UPDATE);
	unset($POBLACION_A);
	unset($VALUES_POBA);
	unset($POBLACION_I);
	unset($VALUES_POBI);
	unset($VARIABLES_TIPOLOGIA);
	unset($VALUES_TIPOLOGIA);
	unset($VARIABLES_CONSTRUCCION);
	unset($VALUES_VARIABLES_CONSTRUCCION);

}//fin function guardar_ficha


function eliminar_ficha ($numero_ficha=null, $cedula_rif_repre=null, $codigo_ficha=null, $codigo_inscripcion=null, $codigo_archivo=null, $pagina=null) {
	$this->layout="ajax";
	if($numero_ficha!=null){
		$eliminado=false;
		$rws_delfv = $this->catd02_ficha_datos->execute("BEGIN; DELETE FROM catd02_ficha_variables WHERE ".$this->SQLCA()." and cod_ficha=".$numero_ficha.";");
		$rws_delft = $this->catd02_ficha_datos->execute("DELETE FROM catd02_ficha_tipologia WHERE ".$this->SQLCA()." and cod_ficha=".$numero_ficha.";");
		$rws_delfi = $this->catd02_ficha_datos->execute("DELETE FROM catd02_ficha_datos WHERE ".$this->SQLCA()." and cod_ficha=".$numero_ficha.";");
		if($rws_delfv > 1 && $rws_delft > 1 && $rws_delfi > 1){
			$eliminado=true;
			$this->catd02_ficha_datos->execute("COMMIT;");
		}else{ $this->catd02_ficha_datos->execute("ROLLBACK;"); }

		if($eliminado===false){
			$rws_delfi = $this->catd02_ficha_datos->execute("DELETE FROM catd02_ficha_datos WHERE ".$this->SQLCA()." and cod_ficha=".$numero_ficha.";");
		}

		if($rws_delfi>1){
				$this->catd02_numero_ficha->execute("UPDATE catd02_numero_ficha SET situacion=4 WHERE ".$this->SQLCA()." and numero=".$codigo_ficha." and situacion<4;");
	            $this->catd02_numero_inscripcion->execute("UPDATE catd02_numero_inscripcion SET situacion=4 WHERE ".$this->SQLCA()." and numero=".$codigo_inscripcion." and situacion<4;");
	            $this->catd02_numero_inscripcion->execute("UPDATE catd02_numero_archivo SET situacion=4 WHERE ".$this->SQLCA()." and numero=".$codigo_archivo." and situacion<4;");
	      	    $verifica_img = $this->cugd10_imagenes->findCount($this->SQLCA()." and cod_campo=21 and identificacion='$numero_ficha'");
	      	    if($verifica_img > 0){
	      	    	$this->cugd10_imagenes->execute("DELETE FROM cugd10_imagenes WHERE ".$this->SQLCA()." and cod_campo=21 and identificacion='$numero_ficha'");
	      	    }
			$this->set("Message_existe","La Ficha fue Eliminada Exitosamente");
			if($pagina!=null){
				$this->consulta($pagina,$numero_ficha,$cedula_rif_repre);
				$this->render("consulta");
			}else{
				$this->index();
				$this->render("index");
			}

		}else{
			$this->set("errorMessage","No se pudo eliminar la ficha - intente nuevamente");
			if($pagina!=null){
				$this->consulta($pagina,$numero_ficha,$cedula_rif_repre);
				$this->render("consulta");
			}else{
				$this->seleccion('1',$numero_ficha,$cedula_rif_repre);
				$this->render("seleccion");
			}
		}
	}
} // fin function eliminar_ficha


function actualiza_session_catp01 () {
	$this->layout="ajax";
	$_SESSION["actualiza_catp01"]="".date("H:i");
    //echo "<br>".$_SESSION["actualiza_catp01"];
}



function filas_variable_construccion($varedi=null){

	$this->layout="ajax";
	if($varedi==2){

	}else{

	if(isset($this->data["catp02_ficha_datos"])){
		if(isset($_SESSION["i"])){
			$i=$this->Session->read("i")+1;
			$this->Session->write("i",$i);
		}else{
			$this->Session->write("i",0);
			$i=0;
		}

		 $vec[$i][0]=$this->data["catp02_ficha_datos"]["cod_vprincipal"];
		 $vec[$i][1]=$this->data["catp02_ficha_datos"]["deno_vprincipal"];
		 $vec[$i][2]=$this->data["catp02_ficha_datos"]["cod_vprimaria"];
		 $vec[$i][3]=$this->data["catp02_ficha_datos"]["deno_vprimaria"];
		 $vec[$i][4]=$this->data["catp02_ficha_datos"]["monto_vs"];
		 $vec[$i][5]= isset($_SESSION["denom_catpd02_tpg"]) ? $_SESSION["denom_catpd02_tpg"] : '';
		 $vec[$i][6]=$this->data["catp02_ficha_datos"]["cod_tipologia_dos"];
		 $vec[$i]["id"]=$i;
		 $vec[$i]["cod_tipo"]=$this->Session->read('cod_tipo');

		 if(isset($_SESSION["items"]) && $_SESSION["items"]!=null){

	    	foreach($_SESSION["items"] as $codis_2){
    			if($codis_2[0]!=null){
    				if((int)$vec[$i][0]==(int)$codis_2[0] && (int)$vec[$i][2]==(int)$codis_2[2] && (string)$vec[$i][6]==(string)$codis_2[6]){
    					$pase = false;
    					$this->set('errorExcede',true);
    					break;
    				}else{
    					$pase = true;
    					$this->set('errorExcede',false);
    				}
    			}
	    	}

			if(isset($pase) && $pase == true){
				$_SESSION["items"]=$_SESSION["items"]+$vec;
			}

		 }else{
			$_SESSION["items"]=$vec;
		 }

		echo "<script>document.getElementById('plus').disabled=true;</script>";
	}
	}
}//fin filas_variables_construccion



function filas_tipo_construccion($varedi2=null){

	$this->layout="ajax";

	if($varedi2==2){

	}else{

	if(isset($this->data["catp02_ficha_datos"])){
		if(isset($_SESSION["i2"])){
			$i=$this->Session->read("i2")+1;
			$this->Session->write("i2",$i);
		}else{
			$this->Session->write("i2",0);
			$i=0;
		}

         $deno=$this->catd01_valor_construccion->findAll($this->SQLCA()." and ano_ordenanza=".$_SESSION["ano_ordenanza"]." and cod_tipo_construccion='".$this->data["catp02_ficha_datos"]["cod_tipologia"]."'");
		 $vec[$i][0]=$this->data["catp02_ficha_datos"]["cod_tipologia"];
		 $vec[$i][1]=$this->Formato1($this->data["catp02_ficha_datos"]["area_m2"]);
         $vec[$i][2]=$this->Formato1_cantidad($this->data["catp02_ficha_datos"]["valor_m2"]);
		 $vec[$i][3]=$this->Formato1($this->data["catp02_ficha_datos"]["porc_depreciacion"]);
		 $vec[$i][4]=$this->Formato1($this->data["catp02_ficha_datos"]["valor_actual"]);
		 $vec[$i][5]=round($vec[$i][1] *  $vec[$i][2],2);
		 $vec[$i]["id"]=$i;
		 $vec[$i]["deno"]=$deno[0]["catd01_valor_construccion"]["denominacion_tipo"];


		 if(isset($_SESSION["items_2"]) && $_SESSION["items_2"]!=null){

	    	foreach($_SESSION["items_2"] as $codis_2){
    			if($codis_2[0]!=null){
    				if($vec[$i][0]!=$codis_2[0]){
    					$pase = true;
    					$this->set('errorExcede',false);
    				}else{
    					$pase = false;
    					$this->set('errorExcede',true);
    					break;
    				}
    			}
	    	}

			if(isset($pase) && $pase == true){
				$_SESSION["items_2"]=$_SESSION["items_2"]+$vec;
			}

		 }else{
			$_SESSION["items_2"]=$vec;
		 }

		 echo "<script> // document.getElementById('plus2').disabled=true;
		 		ver_documento('/catp02_ficha_datos/cargar_select_tipg/', 'carga_select_tpg');
		 		ver_documento('/catp02_ficha_datos/select3/vprincipal/".$this->data["catp02_ficha_datos"]["cod_tipologia"]."', 'st_select3_1');
		 		</script>";

		}
	}
}//fin filas_tipo_construccion


function up_fill_tipe_construction($porcentajed=0){

	$this->layout="ajax";
	if(isset($_SESSION["items_2"]) && $_SESSION["items_2"]!=null){
	$i=0;
		foreach($_SESSION["items_2"] as $codis_2){
    		if($codis_2[0]!=null){
    			$m_valor_total = ($this->Formato1($codis_2[1])*($this->Formato1($codis_2[2])*($porcentajed)));
				$_SESSION["items_2"][0][3]=$porcentajed;
				$_SESSION["items_2"][0][4]=$this->Formato1($m_valor_total);
    			$i++;
    		}
	    }
		$this->Session->write("i2",$i);
	}
}//fin up_fill_tipe_construction


function cargar_select_tipg($vreci=null){

	$this->layout="ajax";
	if(isset($_SESSION["items_2"]) && $_SESSION["items_2"]!=null){
		foreach($_SESSION["items_2"] as $ditems){
			$c_tipologia[] = $ditems[0];
			$d_tipologia[] = $ditems["deno"];
		}
		$tipologia = array_combine($c_tipologia, $d_tipologia);
		$this->set('tipologia', $tipologia);

		if($vreci==2){

			echo "<script>
					if(document.getElementById('select_tipologia_dos')){document.getElementById('select_tipologia_dos').options[0].selected = true;}
		 			document.getElementById('st_select3_1').innerHTML = '<select></select>';
		 			document.getElementById('idcam_vprincipal').value = '';
		 			document.getElementById('st_select3_2').innerHTML = '<select></select>';
		 			document.getElementById('idcam_vprimaria').value = '';
		 			document.getElementById('monto_vs').value = '';
		 	</script>";

		}else{

			echo "<script>
					if(document.getElementById('select_tipologia_dos')){document.getElementById('select_tipologia_dos').value = '".$ditems[0]."';}
		 			document.getElementById('wselect3_1').options[0].selected = true;
		 			document.getElementById('idcam_vprincipal').value = '';
		 			document.getElementById('wselect3_2').options[0].selected = true;
		 			document.getElementById('idcam_vprimaria').value = '';
		 			document.getElementById('monto_vs').value = '';
		 	</script>";

		}

	}else{

		$this->set('tipologia', array());

		echo "<script>

					if(document.getElementById('campo_tipologia_dos')){document.getElementById('campo_tipologia_dos').value = '';}
					else if(document.getElementById('select_tipologia_dos')){document.getElementById('select_tipologia_dos').value = '';}
		 			document.getElementById('wselect3_1').options[0].selected = true;
		 			document.getElementById('idcam_vprincipal').value = '';
		 			document.getElementById('wselect3_2').options[0].selected = true;
		 			document.getElementById('idcam_vprimaria').value = '';
		 			document.getElementById('monto_vs').value = '';

		 			</script>";

	}
}


function elimina_fila_tipo_construccion ($id,$cotp=null) {

     $this->layout="ajax";

	if(isset($_SESSION["items"]) && $_SESSION["items"]!=null){
		$pasere = true;
     foreach($_SESSION["items"] as $rs){
	     if($rs[6]==$cotp){
			$_SESSION["items"][$rs["id"]]=null;
		}
     }
	}else{$pasere = false;}
     $_SESSION["items_2"][$id]=null;

	if(isset($pasere) && $pasere==true)
	echo "<script>ver_documento('/catp02_ficha_datos/filas_variable_construccion/2', 'cargar_variables_construccion');</script>";
	echo "<script>ver_documento('/catp02_ficha_datos/filas_tipo_construccion/2', 'cargar_filas_construccion');</script>";
	echo "<script>ver_documento('/catp02_ficha_datos/cargar_select_tipg/2', 'carga_select_tpg');</script>";

}//elimina_fila_tipo_construccion


function eliminar_fila_variable_construccion ($id) {

     $this->layout="ajax";
     $_SESSION["items"][$id]=null;
	echo "<script>ver_documento('/catp02_ficha_datos/filas_variable_construccion/2', 'cargar_variables_construccion');</script>";
}//eliminar_fila_variable_construccion

function limpiar_lista_tipo_construccion () {

	$this->layout = "ajax";
	$this->Session->delete("items_2");
	$this->Session->delete("i2");
	$this->Session->delete("items");
	$this->Session->delete("i");
	$this->Session->delete("denom_catpd02_tpg");

	echo "<script>
		    document.getElementById('carga_select_tpg').innerHTML = '<input name=\'data[catp02_ficha_datos][campo_tipologia_dos]\' type=\'text\' value=\'\' id=\'campo_tipologia_dos\' class=\'input_catp\' onfocus=\'this.blur();\'>';
 			document.getElementById('st_select3_1').innerHTML = '<select></select>';
 			document.getElementById('idcam_vprincipal').value = '';
 			document.getElementById('st_select3_2').innerHTML = '<select></select>';
 			document.getElementById('idcam_vprimaria').value = '';
 			document.getElementById('monto_vs').value = '';
	 	</script>";
	echo "<script> // document.getElementById('plus2').disabled = false;
			document.getElementById('plus').disabled = true; document.getElementById('cargar_variables_construccion').innerHTML = '';</script>";
	echo "<script>
			document.getElementById('area_total').value='0,00';
			document.getElementById('c_valor_construccion').value='0,0000';
			document.getElementById('d_valor_construccion').value='0,00';
			document.getElementById('monto_tota_variables_c').value='0,0000';
			document.getElementById('d_valor_montoajust').value='0,00';
			document.getElementById('d_valor_depreciac').value='0,00';
		    document.getElementById('monto_total_de_construccion').value='0,00';
		    calcular_distribucion_imp();
		</script>";

		echo "<script> if(eval(document.getElementById('valoracion_valor_total').value) != eval(0)){
					document.getElementById('ctrl_num_ne').value='0,00';
				    document.getElementById('calculo_terreno').value = document.getElementById('valoracion_valor_total').value;
			        document.getElementById('calculo_construccion').value = '0,00';
			        calcular_distribucion_imp();
			}else{
				document.getElementById('impe_anual').value='0,00';
				document.getElementById('impe_trime').value='0,00';
				document.getElementById('ctrl_num_ne').value='0,00';
			    document.getElementById('calculo_terreno').value = '0,00';
			    document.getElementById('calculo_construccion').value = '0,00';
			  	ver_documento('/catp02_ficha_datos/calculo_impuesto_anual_trim/0/2', 'carga_dimp_anutrim');
			}

		</script>";


}//limpiar_lista_tipo_construccion



function limpiar_lista_variable_construccion () {

	$this->layout = "ajax";
	$this->Session->delete("items");
	$this->Session->delete("i");
	// $this->Session->delete("denom_catpd02_tpg");

	echo "<script>document.getElementById('plus').disabled = false;</script>";
	echo "<script>
		    //document.getElementById('d_valor_construccion').value='0,0000';
		    //document.getElementById('monto_tota_variables_c').value = '0,0000';
  		    //document.getElementById('d_valor_montoajust').value = redondear(eval(document.getElementById('area_total').value) * eval(document.getElementById('monto_tota_variables_c').value),2);
  			//moneda('d_valor_montoajust');
  			//calcular_distribucion_imp();
		</script>";
}//limpiar_lista_variable_construccion


function filas_pobc_adulta($varedi=null){

	$this->layout="ajax";
	if($varedi==2){

	}else{

	if(isset($this->data["catp02_ficha_datos"])){
		if(isset($_SESSION["n"])){
			$i=$this->Session->read("n")+1;
			$this->Session->write("n",$i);
		}else{
			$this->Session->write("n",0);
			$i=0;
		}

		 $vec[$i][0]=$this->data["catp02_ficha_datos"]["pob_adulta_edad"];
		 $vec[$i][1]=$this->data["catp02_ficha_datos"]["pob_adulta_sexo"];
		 $vec[$i][2]=$this->data["catp02_ficha_datos"]["pob_adulta_neduc"];
		 $vec[$i][3]=$this->data["catp02_ficha_datos"]["pob_adulta_oprof"];
		 $vec[$i][4]=$this->data["catp02_ficha_datos"]["pob_adulta_ltrab"];
		 $vec[$i][5]=$this->data["catp02_ficha_datos"]["pob_adulta_trans"];
		 $vec[$i]["id"]=$i;

		 if(isset($_SESSION["itemsn"]) && $_SESSION["itemsn"]!=null){
			$_SESSION["itemsn"]=$_SESSION["itemsn"]+$vec;
		 }else{
			$_SESSION["itemsn"]=$vec;
		 }
	}
		echo "<script>
			document.getElementById('pob_adulta_edad').value='';
			document.getElementById('pob_adulta_sexo_1').checked=false;
			document.getElementById('pob_adulta_sexo_2').checked=false;
			document.getElementById('pob_adulta_neduc').value='';
			document.getElementById('pob_adulta_oprof').value='';
			document.getElementById('pob_adulta_ltrab').value='';
			document.getElementById('pob_adulta_trans').value='';
			document.getElementById('boton_add_pa').disabled = true;
		</script>";
	}
}//fin filas_pobc_adulta


function elim_fpa ($id=null) {

	$this->layout="ajax";
	if(isset($_SESSION["itemsn"]) && $_SESSION["itemsn"]!=null){
		foreach($_SESSION["itemsn"] as $rs){
			if($rs["id"]==$id){
				$_SESSION["itemsn"][$rs["id"]]=null;
			}
     	}
	}

	echo "<script>ver_documento('/catp02_ficha_datos/filas_pobc_adulta/2', 'cargar_poblacion_adulta');</script>";

}// fin function elim_fpa (elimina fila poblacion adulta)



function editar_fpa($id=null,$if=null){

	$this->layout="ajax";
	if(isset($_SESSION["itemsn"]) && $_SESSION["itemsn"]!=null){
		if($_SESSION["itemsn"][$if]!=null)
			$this->set('datos_fpa', $_SESSION["itemsn"][$if]);
		else
			$this->set('datos_fpa', null);
	}
	else{
		$this->set('datos_fpa', array());
	}
	$this->set('if', $if);
}



function up_fpa($id=null,$if=null){

	$this->layout="ajax";

if($this->data["catp02_ficha_datos"]["eedad_$if"]!=null && $this->data["catp02_ficha_datos"]["ssexo_$if"]!=null && $this->data["catp02_ficha_datos"]["neduc_$if"]!=null && $this->data["catp02_ficha_datos"]["oprof_$if"]!=null && $this->data["catp02_ficha_datos"]["ltrab_$if"]!=null && $this->data["catp02_ficha_datos"]["trans_$if"]!=null){

	if(isset($_SESSION["itemsn"])){
		if($_SESSION["itemsn"][$if]!=null){
		$vec[0]= (int) $this->data["catp02_ficha_datos"]["eedad_$if"];
	 	$vec[1]=$this->data["catp02_ficha_datos"]["ssexo_$if"];
	 	$vec[2]=$this->data["catp02_ficha_datos"]["neduc_$if"];
	 	$vec[3]=$this->data["catp02_ficha_datos"]["oprof_$if"];
	 	$vec[4]=$this->data["catp02_ficha_datos"]["ltrab_$if"];
	 	$vec[5]=$this->data["catp02_ficha_datos"]["trans_$if"];
		$_SESSION["itemsn"][$if][0]=mascara($vec[0],2);
		$_SESSION["itemsn"][$if][1]=$vec[1];
		$_SESSION["itemsn"][$if][2]=$vec[2];
		$_SESSION["itemsn"][$if][3]=$vec[3];
		$_SESSION["itemsn"][$if][4]=$vec[4];
		$_SESSION["itemsn"][$if][5]=$vec[5];
			$this->set('codigos', $_SESSION["itemsn"][$if]);
		}else{
			$this->set('codigos', null);
		}
	}else{
		$this->set('codigos', array());
	}
}else{
	$this->set('codigos', $_SESSION["itemsn"][$if]);
	$this->set('errorMessage', "Debe ingresar todos los datos requeridos");
}
	$this->set('if', $if);
}//fin function up_fpa (Actualiza Fila Poblacion Adulta)



function cancel_fpa($id=null,$if=null){

	$this->layout="ajax";
	if(isset($_SESSION["itemsn"])){
		if($_SESSION["itemsn"][$if]!=null){
			$this->set('codigos', $_SESSION["itemsn"][$if]);
		}else{
			$this->set('codigos', null);
		}
	}else{
		$this->set('codigos', array());
	}
	$this->set('if', $if);
}


function limpiar_lista_fpa () {

	$this->layout = "ajax";
	$this->Session->delete("itemsn");
	$this->Session->delete("n");
	echo "<script>
			document.getElementById('pob_adulta_edad').value='';
			document.getElementById('pob_adulta_sexo_1').checked=false;
			document.getElementById('pob_adulta_sexo_2').checked=false;
			document.getElementById('pob_adulta_neduc').value='';
			document.getElementById('pob_adulta_oprof').value='';
			document.getElementById('pob_adulta_ltrab').value='';
			document.getElementById('pob_adulta_trans').value='';
			document.getElementById('boton_add_pa').disabled = true;
			document.getElementById('inum_pa').value='0';
		</script>";
}// fin function limpiar_lista_fpa






function filas_pobc_infantil($varedi=null){
	$this->layout="ajax";
	if($varedi==2){

	}else{

	if(isset($this->data["catp02_ficha_datos"])){
		if(isset($_SESSION["n2"])){
			$i=$this->Session->read("n2")+1;
			$this->Session->write("n2",$i);
		}else{
			$this->Session->write("n2",0);
			$i=0;
		}

		 $vec[$i][0]=$this->data["catp02_ficha_datos"]["pob_infantil_edad"];
		 $vec[$i][1]=$this->data["catp02_ficha_datos"]["pob_infantil_sexo"];
		 $vec[$i][2]=$this->data["catp02_ficha_datos"]["pob_infantil_neduc"];
		 $vec[$i][3]=$this->data["catp02_ficha_datos"]["pob_infantil_nomb_inst"];
		 $vec[$i][4]=$this->data["catp02_ficha_datos"]["pob_infantil_trans"];
		 $vec[$i]["id"]=$i;

		 if(isset($_SESSION["itemsn2"]) && $_SESSION["itemsn2"]!=null){
			$_SESSION["itemsn2"]=$_SESSION["itemsn2"]+$vec;
		 }else{
			$_SESSION["itemsn2"]=$vec;
		 }
	}
		echo "<script>
			document.getElementById('pob_infantil_edad').value='';
			document.getElementById('pob_infantil_sexo_1').checked=false;
			document.getElementById('pob_infantil_sexo_2').checked=false;
			document.getElementById('pob_infantil_neduc').value='';
			document.getElementById('pob_infantil_nomb_inst').value='';
			document.getElementById('pob_infantil_trans').value='';
			document.getElementById('boton_add_pi').disabled = true;
		</script>";
	}
}//fin filas_pobc_adulta


function elim_fpi ($id=null) {

	$this->layout="ajax";
	if(isset($_SESSION["itemsn2"]) && $_SESSION["itemsn2"]!=null){
		foreach($_SESSION["itemsn2"] as $rs){
			if($rs["id"]==$id){
				$_SESSION["itemsn2"][$rs["id"]]=null;
			}
     	}
	}

	echo "<script>ver_documento('/catp02_ficha_datos/filas_pobc_infantil/2', 'cargar_poblacion_infantil');</script>";

}// fin function elim_fpa (elimina fila poblacion infantil)



function editar_fpi($id=null,$if=null){

	$this->layout="ajax";
	if(isset($_SESSION["itemsn2"]) && $_SESSION["itemsn2"]!=null){
		if($_SESSION["itemsn2"][$if]!=null)
			$this->set('datos_fpa', $_SESSION["itemsn2"][$if]);
		else
			$this->set('datos_fpa', null);
	}
	else{
		$this->set('datos_fpa', array());
	}
	$this->set('if', $if);
}



function up_fpi($id=null,$if=null){

	$this->layout="ajax";

if($this->data["catp02_ficha_datos"]["eedad2_$if"]!=null && $this->data["catp02_ficha_datos"]["ssexo2_$if"]!=null && $this->data["catp02_ficha_datos"]["neduc2_$if"]!=null && $this->data["catp02_ficha_datos"]["lnomb_inst2_$if"]!=null && $this->data["catp02_ficha_datos"]["trans2_$if"]!=null){

	if(isset($_SESSION["itemsn2"])){
		if($_SESSION["itemsn2"][$if]!=null){
		$vec[0]= (int) $this->data["catp02_ficha_datos"]["eedad2_$if"];
	 	$vec[1]=$this->data["catp02_ficha_datos"]["ssexo2_$if"];
	 	$vec[2]=$this->data["catp02_ficha_datos"]["neduc2_$if"];
	 	$vec[3]=$this->data["catp02_ficha_datos"]["lnomb_inst2_$if"];
	 	$vec[4]=$this->data["catp02_ficha_datos"]["trans2_$if"];
		$_SESSION["itemsn2"][$if][0]=mascara($vec[0],2);
		$_SESSION["itemsn2"][$if][1]=$vec[1];
		$_SESSION["itemsn2"][$if][2]=$vec[2];
		$_SESSION["itemsn2"][$if][3]=$vec[3];
		$_SESSION["itemsn2"][$if][4]=$vec[4];
			$this->set('codigos', $_SESSION["itemsn2"][$if]);
		}else{
			$this->set('codigos', null);
		}
	}else{
		$this->set('codigos', array());
	}
}else{
	$this->set('codigos', $_SESSION["itemsn2"][$if]);
	$this->set('errorMessage', "Debe ingresar todos los datos requeridos");
}
	$this->set('if', $if);
}//fin function up_fpa (Actualiza Fila Poblacion Infantil)



function cancel_fpi($id=null,$if=null){

	$this->layout="ajax";
	if(isset($_SESSION["itemsn2"])){
		if($_SESSION["itemsn2"][$if]!=null){
			$this->set('codigos', $_SESSION["itemsn2"][$if]);
		}else{
			$this->set('codigos', null);
		}
	}else{
		$this->set('codigos', array());
	}
	$this->set('if', $if);
}


function limpiar_lista_fpi () {

	$this->layout = "ajax";
	$this->Session->delete("itemsn2");
	$this->Session->delete("n2");
	echo "<script>
			document.getElementById('pob_infantil_edad').value='';
			document.getElementById('pob_infantil_sexo_1').checked=false;
			document.getElementById('pob_infantil_sexo_2').checked=false;
			document.getElementById('pob_infantil_neduc').value='';
			document.getElementById('pob_infantil_nomb_inst').value='';
			document.getElementById('pob_infantil_trans').value='';
			document.getElementById('boton_add_pi').disabled = true;
		</script>";
}// fin function limpiar_lista_fpi




function calculo_impuesto_anual_trim($var_mtc=null,$valor_total_con=null,$ddivredi=null,$var_t1=null,$var_t2=null,$var_t3=null,$var_t4=null) {

$ano_act_ord = $_SESSION["ano_ordenanza"];
$tipo_inm=$_SESSION["radio_descripcionuso"];

	$escalas_cat = $this->catd02_ficha_datos->execute("SELECT escala, monto_desde, monto_hasta, porcentaje, sustraendo FROM catd01_escala_cobro WHERE ".$this->SQLCA()." AND ano_ordenanza='$ano_act_ord' AND cod_tipo_inmueble=99");

	if(!empty($escalas_cat)){
		$tipo_inmueble=99;
	}else{
		$tipo_inmueble=3;
		if($tipo_inm==1){$tipo_inmueble=1;}else
		if($tipo_inm==2 || $tipo_inm==3){$tipo_inmueble=2;}else
		if($tipo_inm==4 || $tipo_inm==5 || $tipo_inm==6 || $tipo_inm==7 || $tipo_inm==9){$tipo_inmueble=3;}else
		if($tipo_inm==8){$tipo_inmueble=4;}
		}

	$this->layout="ajax";
	if($ddivredi==2){
		$this->set("ddivredi",$ddivredi);
	}else{

	if($var_mtc!=null){

	if(isset($_SESSION["ano_ordenanza"]) && $_SESSION["ano_ordenanza"]!=null){
		$ano_act_ord = $_SESSION["ano_ordenanza"];
	}else{
		$ano_actuale = $this->catd01_ano_ordenanza->ano_actual($this->SQLCA());
	    $ano_act_ord = $ano_actuale;
	}

	$escalas_catastrales = $this->catd02_ficha_datos->execute("SELECT escala, monto_desde, monto_hasta, porcentaje, sustraendo FROM catd01_escala_cobro WHERE ".$this->SQLCA()." AND ano_ordenanza='$ano_act_ord' AND cod_tipo_inmueble='$tipo_inmueble'");
	$this->set("escalas_catastrales",$escalas_catastrales);
	$this->set("var_mtc",$var_mtc);
		$this->set("var_t1",$var_t1);
		$this->set("var_t2",$var_t2);
		$this->set("var_t3",$var_t3);
		$this->set("var_t4",$var_t4);
		$this->set("valor_total_con",$valor_total_con);


	$escalas_terrenos_ejidos = $this->catd02_ficha_datos->execute("SELECT escala, metros_desde, metros_hasta, porcentaje, sustraendo FROM catd01_escala_terrenos_ejidos WHERE ".$this->SQLCA()." AND ano_ordenanza='$ano_act_ord'");
	$this->set("escalas_terrenos_ejidos",$escalas_terrenos_ejidos);
		}
	}

} // fin function calculo_impuesto_anual_trimestral


function consultar ($var=null) {
   $this->layout="ajax";
}

function lista_busqueda ($pista_buscar=null) {

	$this->layout="ajax";
	//echo $pista_buscar;
	$pista_buscar=(string) $pista_buscar;
	$c=$this->v_catd02_ficha_datos->findCount($this->SQLCA()." and upper(denominacion_busqueda)::text LIKE '%".strtoupper($pista_buscar)."%'");
    //echo "select * from v_catd02_ficha_datos WHERE  ".$this->SQLCA()." and upper(denominacion_busqueda)::text LIKE '%".strtoupper($pista_buscar)."%'";
	if($c!=0){
		$rs=$this->v_catd02_ficha_datos->execute("select * from v_catd02_ficha_datos WHERE  ".$this->SQLCA()." and upper(denominacion_busqueda)::text LIKE '%".$pista_buscar."%'");
	    $this->set("DATA",$rs);
	}else{
        $this->set("errorMessage","Disculpe, datos no encontrados con la pista ".$pista_buscar);
	}


}

function generar_ficha_pdf ($i) {

  $this->layout="ajax";
       if(isset($this->data)){
            $cod_ficha=$this->data["v_catd02_ficha_datos".$i]["cod_ficha"];
            $ano_ordenanza=$this->data["v_catd02_ficha_datos".$i]["ano_ordenanza"];
            $rs=$this->v_catd02_ficha_datos->execute("select * from v_catd02_ficha_datos WHERE  ".$this->SQLCA()." and ano_ordenanza=".$ano_ordenanza." and cod_ficha=".$cod_ficha);
	        $this->set("DATOS",$rs);
       }
}

function traer_factor_depreciacion ($edad_efectiva,$factor,$ano_ordenanza,$con_cporc=null) {

    $this->layout="ajax";
    $rs=$this->catd01_depreciacion_edificaciones->findAll($this->SQLCA()." and ano_ordenanza=".$ano_ordenanza." and edad=".$edad_efectiva);

     switch ((int) $factor) {
		case 1:
			$porcetaje=$rs[0]["catd01_depreciacion_edificaciones"]["factor_excelente"];
			$this->set("FACTOR_A",$porcetaje);
			break;
	    case 2:
			$porcetaje=$rs[0]["catd01_depreciacion_edificaciones"]["factor_bueno"];
			$this->set("FACTOR_A",$porcetaje);
			break;
		case 3:
			$porcetaje=$rs[0]["catd01_depreciacion_edificaciones"]["factor_regular"];
			$this->set("FACTOR_A",$porcetaje);
			break;
		case 4:
			$porcetaje=$rs[0]["catd01_depreciacion_edificaciones"]["factor_malo"];
			$this->set("FACTOR_A",$porcetaje);
			break;
	}

	if((int) $con_cporc==2){
		$porcetajed = $porcetaje/100;
		echo "<script>ver_documento('/catp02_ficha_datos/up_fill_tipe_construction/'+$porcetajed,'cargar_filas_construccion');</script>";
	}
}

function salir_catp02_ficha_datos ($codigo_ficha,$codigo_inscripcion,$codigo_archivo) {

    $this->layout = "ajax";
    $resultado=$this->catd02_numero_ficha->execute("UPDATE catd02_numero_ficha SET situacion=1 WHERE ".$this->SQLCA()." and numero=".$codigo_ficha." and situacion<3");
    $resultado=$this->catd02_numero_inscripcion->execute("UPDATE catd02_numero_inscripcion SET situacion=1 WHERE ".$this->SQLCA()." and numero=".$codigo_inscripcion." and situacion<3");
    $resultado=$this->catd02_numero_inscripcion->execute("UPDATE catd02_numero_archivo SET situacion=1 WHERE ".$this->SQLCA()." and numero=".$codigo_archivo." and situacion<3");

			if(isset($_SESSION ["items"])){
	            $this->Session->delete("i");
	            $this->Session->delete("items");
			}
			if(isset($_SESSION ["items_2"])){
	            $this->Session->delete("i2");
	            $this->Session->delete("items_2");
			}
			if(isset($_SESSION ["denom_catpd02_tpg"])){
				$this->Session->delete("denom_catpd02_tpg");
			}
			if(isset($_SESSION ["rand_expediente"])){
				$this->Session->delete("rand_expediente");
			}
			if(isset($_SESSION ["itemsn"])){
	            $this->Session->delete("n");
	            $this->Session->delete("itemsn");
			}
			if(isset($_SESSION ["itemsn2"])){
	            $this->Session->delete("n2");
	            $this->Session->delete("itemsn2");
			}
}//fin salir


}//fin class
?>
