<?php

class InfoCertificacionController extends AppController
{
	var $name = "info_certificacion";
    var $uses = array('cugd_usuarios','ccfd04_cierre_mes','v_cnmd06_fichas','v_cnmd06_fichas_datos_personales','cnmd06_constancia_certificacion','cnmd06_constancia_firmante','cugd02_institucion');
    var $helpers = array('Html', 'Javascript', 'Ajax', 'Sisap','Infogob');

	function checkSession(){
		if (!$this->Session->check('infogobierno')){
				$this->redirect('/infogobierno/salir_todo');
				exit();
		}
	}//fin checksession


	function beforeFilter(){
		// $this->checkSession();
	}

function certificacion () {
	$this->layout="ajax";
}


function datos_certificacion ($cod_certif = null) {
	$this->layout="ajax";
	if(!empty($cod_certif)){

		$certificacion = $this->cnmd06_constancia_certificacion->findAll("UPPER(codigo_certificacion) = UPPER('$cod_certif')", null, null, 1);

		if(!empty($certificacion)){

			$cod_presi = $certificacion[0]['cnmd06_constancia_certificacion']['cod_presi'];
			$cod_entidad = $certificacion[0]['cnmd06_constancia_certificacion']['cod_entidad'];
			$cod_tipo_inst = $certificacion[0]['cnmd06_constancia_certificacion']['cod_tipo_inst'];
			$cod_inst = $certificacion[0]['cnmd06_constancia_certificacion']['cod_inst'];
			$cod_dep = $certificacion[0]['cnmd06_constancia_certificacion']['cod_dep'];

			$cod_tipo_nomina = $certificacion[0]['cnmd06_constancia_certificacion']['cod_tipo_nomina'];
			$cod_cargo = $certificacion[0]['cnmd06_constancia_certificacion']['cod_cargo'];
			$cod_ficha = $certificacion[0]['cnmd06_constancia_certificacion']['cod_ficha'];
			$cedula_identidad = $certificacion[0]['cnmd06_constancia_certificacion']['cedula_identidad'];
			$codigo_certificacion = $certificacion[0]['cnmd06_constancia_certificacion']['codigo_certificacion'];

/*
$codigo_completo = $certificacion[0]['cnmd06_constancia_certificacion']['cod_presi']."-".$certificacion[0]['cnmd06_constancia_certificacion']['cod_entidad']."-".$certificacion[0]['cnmd06_constancia_certificacion']['cod_tipo_inst']."-".$certificacion[0]['cnmd06_constancia_certificacion']['cod_inst']."-".$certificacion[0]['cnmd06_constancia_certificacion']['cod_dep']."-".$certificacion[0]['cnmd06_constancia_certificacion']['cod_tipo_nomina']."-".$certificacion[0]['cnmd06_constancia_certificacion']['cod_cargo']."-".$certificacion[0]['cnmd06_constancia_certificacion']['cod_ficha'];
echo "
<script type='text/javascript'>
	document.getElementById('codigo_completo').value = '".$codigo_completo."';
	document.getElementById('ced_identidad').value = '".$cedula_identidad."';
</script>";
*/

	$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep;

	$sueldo = $this->v_cnmd06_fichas->findAll($condicion." and cod_tipo_nomina='$cod_tipo_nomina' and cod_cargo='$cod_cargo' and cod_ficha='$cod_ficha' and cedula_identidad='$cedula_identidad'",'sueldo_integral', null, 1);
	$datos_constancia = $this->v_cnmd06_fichas_datos_personales->findAll($condicion." and cod_tipo_nomina='$cod_tipo_nomina' and cod_cargo='$cod_cargo' and cod_ficha='$cod_ficha' and cedula_identidad='$cedula_identidad' and condicion_actividad=1",'tipo_nomina, cedula_identidad, nacionalidad, primer_apellido, segundo_apellido, primer_nombre, segundo_nombre, denominacion_clase, fecha_ingreso', null, 1);
	$datos_firma = $this->cnmd06_constancia_firmante->findAll($condicion,'funcionario_firmante, cargo_firmante, resolucion', null, 1);


	if($cod_dep != '1'){
		$deno_inst = $this->cugd02_institucion->execute("SELECT denominacion FROM cugd02_dependencias WHERE cod_tipo_institucion = $cod_tipo_inst and cod_institucion = $cod_inst and cod_dependencia = $cod_dep LIMIT 1;");
	}else{
		$deno_inst = $this->cugd02_institucion->execute("SELECT denominacion FROM cugd02_institucion WHERE cod_tipo_institucion = $cod_tipo_inst and cod_institucion = $cod_inst LIMIT 1;");
	}

	$this->set("deno_inst", $deno_inst);
	$this->set("sueldo", $sueldo);
	$this->set("datos_constancia", $datos_constancia);
	$this->set("datos_firma", $datos_firma);
	$this->set("certificacion", $certificacion);

	}else{

			$this->set("certificacion", null);
			$this->set("cod_certificacion", $cod_certif);
			$this->set('msj',array('El C&oacute;digo suministrado no es valido!!','error'));
	}

		}else{

			$this->set("certificacion", null);
			$this->set('msj',array('Ingrese C&oacute;digo!!','error'));
		}
}


	function codSS ($codigoSS = array(), $ver = true){
                $codigos = explode('-',$codigoSS);
                if(!empty($codigos)){
                    $cod_presi = $codigos[0];
                    $cod_entidad = $codigos[1];
                    $cod_tipo_inst = $codigos[2];
                    $cod_inst = $codigos[3];
                    $cod_dep = $codigos[4];
                    if($ver == true){
                    	if(isset($codigos[5])){
                    		$cod_tipo_nomina = " and cod_tipo_nomina=". $codigos[5];
                    	}else{
                    		$cod_tipo_nomina = "";
                    	}


                    	if(isset($codigos[6])){
                    		$cod_cargo = " and cod_cargo=". $codigos[6];
                    	}else{
                    		$cod_cargo = "";
                    	}


                    	if(isset($codigos[7])){
                    		$cod_ficha = " and cod_ficha=". $codigos[7];
                    	}else{
                    		$cod_ficha = "";
                    	}

                    }else{
                    	$cod_tipo_nomina = "";
                    	$cod_cargo = "";
                    	$cod_ficha = "";
                    }
                }else{
                    $cod_presi = null;
                    $cod_entidad = null;
                    $cod_tipo_inst = null;
                    $cod_inst = null;
                    $cod_dep = null;
                    $cod_tipo_nomina = "";
                    $cod_cargo = "";
                    $cod_ficha = "";
                }

		$condicions = "cod_presi=$cod_presi and cod_entidad=$cod_entidad and cod_tipo_inst=$cod_tipo_inst and cod_inst=$cod_inst and cod_dep=$cod_dep" . $cod_tipo_nomina . $cod_cargo . $cod_ficha;
		return $condicions;
	}



function const_certificacion(){
	$this->layout = "pdf";
	$cod_certificacion = $this->data['cnmd06_constancia']['cod_certificacion'];
	$aleatorio=intval(rand());

		$certificacion = $this->cnmd06_constancia_certificacion->findAll("UPPER(codigo_certificacion) = UPPER('$cod_certificacion')", null, null, 1);

		if(!empty($certificacion)){

			$cod_presi = $certificacion[0]['cnmd06_constancia_certificacion']['cod_presi'];
			$cod_entidad = $certificacion[0]['cnmd06_constancia_certificacion']['cod_entidad'];
			$cod_tipo_inst = $certificacion[0]['cnmd06_constancia_certificacion']['cod_tipo_inst'];
			$cod_inst = $certificacion[0]['cnmd06_constancia_certificacion']['cod_inst'];
			$cod_dep = $certificacion[0]['cnmd06_constancia_certificacion']['cod_dep'];

			$cod_tipo_nomina = $certificacion[0]['cnmd06_constancia_certificacion']['cod_tipo_nomina'];
			$cod_cargo = $certificacion[0]['cnmd06_constancia_certificacion']['cod_cargo'];
			$cod_ficha = $certificacion[0]['cnmd06_constancia_certificacion']['cod_ficha'];
			$cedula_identidad = $certificacion[0]['cnmd06_constancia_certificacion']['cedula_identidad'];
			$codigo_certificacion = $certificacion[0]['cnmd06_constancia_certificacion']['codigo_certificacion'];

			$cod_dep_nomina = $certificacion[0]['cnmd06_constancia_certificacion']['cod_presi']."-".$certificacion[0]['cnmd06_constancia_certificacion']['cod_entidad']."-".$certificacion[0]['cnmd06_constancia_certificacion']['cod_tipo_inst']."-".$certificacion[0]['cnmd06_constancia_certificacion']['cod_inst']."-".$certificacion[0]['cnmd06_constancia_certificacion']['cod_dep']."-".$certificacion[0]['cnmd06_constancia_certificacion']['cod_tipo_nomina']."-".$certificacion[0]['cnmd06_constancia_certificacion']['cod_cargo']."-".$certificacion[0]['cnmd06_constancia_certificacion']['cod_ficha'];

	$codigos = explode('-',$cod_dep_nomina);
	if(!empty($codigos)){
		$cod_presi = $codigos[0];
		$cod_entidad = $codigos[1];
		$cod_tipo_inst = $codigos[2];
		$cod_inst = $codigos[3];
		$cod_dep = $codigos[4];
		$cod_tipo_nomina = $codigos[5];
		$cod_cargo = $codigos[6];
		$cod_ficha = $codigos[7];
	}else{
		$cod_presi = null;
		$cod_entidad = null;
		$cod_tipo_inst = null;
		$cod_inst = null;
		$cod_dep = null;
		$cod_tipo_nomina = "";
		$cod_cargo = "";
		$cod_ficha = "";
	}


	$cod_imagen = $cod_presi."_".$cod_entidad."_".$cod_tipo_inst."_".$cod_inst."_".$cod_dep;
	$condicion = "cod_presi=$cod_presi and cod_entidad=$cod_entidad and cod_tipo_inst=$cod_tipo_inst and cod_inst=$cod_inst and cod_dep=$cod_dep";

	$this->set("cod_imagen", $cod_imagen);


	if($cod_tipo_inst != '50'){
		$campo_deno = 'deno_cod_secretaria';
	}else{
		$campo_deno = 'deno_cod_direccion';
	}

	$sueldo = $this->v_cnmd06_fichas->findAll($this->codSS($cod_dep_nomina)." and cedula_identidad='$cedula_identidad'",'sueldo_integral,'.$campo_deno, null, 1);
	$datos_constancia = $this->v_cnmd06_fichas_datos_personales->findAll($this->codSS($cod_dep_nomina)." and cedula_identidad='$cedula_identidad' and condicion_actividad=1",'tipo_nomina, cedula_identidad, nacionalidad, primer_apellido, segundo_apellido, primer_nombre, segundo_nombre, denominacion_clase, fecha_ingreso', null, 1);
	$datos_firma = $this->cnmd06_constancia_firmante->findAll($this->codSS($cod_dep_nomina, false),'funcionario_firmante, cargo_firmante, resolucion', null, 1);
	$certificacion = $this->cnmd06_constancia_certificacion->findAll($this->codSS($cod_dep_nomina)." and cedula_identidad='$cedula_identidad' and codigo_certificacion='$cod_certificacion'",'cedula_identidad, codigo_certificacion, fecha_emision, fecha_expiracion', null, 1);


	$cod_defecto = $this->cugd02_institucion->execute("SELECT cod_republica, cod_estado, cod_municipio FROM cugd90_municipio_defecto WHERE ".$this->codSS($cod_dep_nomina, false)." LIMIT 1;");

	$republica = $this->cugd02_institucion->execute("SELECT denominacion FROM cugd01_republica WHERE cod_republica=".$cod_defecto[0][0]['cod_republica']." LIMIT 1;");
	$estado = $this->cugd02_institucion->execute("SELECT denominacion FROM cugd01_estados WHERE cod_republica=".$cod_defecto[0][0]['cod_republica']." and cod_estado=".$cod_defecto[0][0]['cod_estado']." LIMIT 1;");
	$cod_zona = $this->cugd02_institucion->execute("SELECT zona_postal FROM cugd01_municipios WHERE cod_republica=".$cod_defecto[0][0]['cod_republica']." and cod_estado=".$cod_defecto[0][0]['cod_estado']." and cod_municipio=".$cod_defecto[0][0]['cod_municipio']." LIMIT 1;");


	  	// $rs_img=$this->cnmd06_constancia_firmante->execute("SELECT coalesce(logo_derecho,'-1') as imagen, tipo_logo_derecho as tipo FROM cnmd06_constancia_firmante WHERE ".$this->codSS($cod_dep_nomina, false)." and ".$aleatorio."=".$aleatorio);

  	$datos_imgs=$this->cnmd06_constancia_firmante->execute("SELECT tipo_logo_derecho, tipo_logo_izquierdo, tipo_imagen_sello, tipo_imagen_sello_firma, tipo_imagen_firma FROM cnmd06_constancia_firmante WHERE ".$this->codSS($cod_dep_nomina, false)." and ".$aleatorio."=".$aleatorio);


	if(!empty($certificacion)){

	if($cod_dep != '1'){
		$deno_inst = $this->cugd02_institucion->execute("SELECT denominacion, direccion, cod_area, telefonos FROM cugd02_dependencias WHERE cod_tipo_institucion = $cod_tipo_inst and cod_institucion = $cod_inst and cod_dependencia = $cod_dep LIMIT 1;");
	}else{
		$deno_inst = $this->cugd02_institucion->execute("SELECT denominacion, direccion, cod_area, telefonos FROM cugd02_institucion WHERE cod_tipo_institucion = $cod_tipo_inst and cod_institucion = $cod_inst LIMIT 1;");
	}


	$this->set("datos_imgs", $datos_imgs);
	$this->set("cod_tipo_inst", $cod_tipo_inst);
	$this->set("deno_inst", $deno_inst);
	$this->set("cod_zona", $cod_zona);
	$this->set("republica", $republica);
	$this->set("estado", $estado);
	$this->set("sueldo", $sueldo);
	$this->set("datos_constancia", $datos_constancia);
	$this->set("datos_firma", $datos_firma);
	$this->set("certificacion", $certificacion);


	}else{

			$this->set("certificacion", null);
			$this->set("cod_certificacion", $cod_certificacion);
			$this->set('msj',array('El C&oacute;digo suministrado no es valido!!','error'));
	}




	}else{ // NO EXISTE EL CODIGO

		$this->set("cod_certificacion", $cod_certificacion);

		// $datos_firma = $this->cnmd06_constancia_firmante->findAll($condicion,'funcionario_firmante, cargo_firmante, resolucion', null, 1);

	  	// $rs_img=$this->cnmd06_constancia_firmante->execute("SELECT coalesce(logo_derecho,'-1') as imagen, tipo_logo_derecho as tipo FROM cnmd06_constancia_firmante WHERE ".$this->SQLCA()." and ".$aleatorio."=".$aleatorio);

  	// $datos_imgs=$this->cnmd06_constancia_firmante->execute("SELECT tipo_logo_derecho, tipo_logo_izquierdo, tipo_imagen_sello, tipo_imagen_sello_firma, tipo_imagen_firma FROM cnmd06_constancia_firmante WHERE ".$this->SQLCA()." and ".$aleatorio."=".$aleatorio);

/*
	if($cod_dep != '1'){
		$deno_inst = $this->cugd02_institucion->execute("SELECT denominacion, direccion, cod_area, telefonos FROM cugd02_dependencias WHERE cod_tipo_institucion = $cod_tipo_inst and cod_institucion = $cod_inst and cod_dependencia = $cod_dep LIMIT 1;");
	}else{
		$deno_inst = $this->cugd02_institucion->execute("SELECT denominacion, direccion, cod_area, telefonos FROM cugd02_institucion WHERE cod_tipo_institucion = $cod_tipo_inst and cod_institucion = $cod_inst LIMIT 1;");
	}
*/

	// $this->set("datos_imgs", $datos_imgs);
	// $this->set("deno_inst", $deno_inst);
	// $this->set("datos_firma", $datos_firma);

	}
}


} // Fin Class

?>
