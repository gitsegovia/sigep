<?php

class InfoConstanciaController extends AppController
{
	var $name = "info_constancia";
    var $uses = array('cugd_usuarios','ccfd04_cierre_mes','v_cnmd06_fichas','v_cnmd06_fichas_datos_personales','cnmd06_constancia_certificacion','cnmd06_constancia_firmante','cugd02_institucion');
    var $helpers = array('Html', 'Javascript', 'Ajax', 'Sisap','Infogob');

	function checkSession(){
		if (!$this->Session->check('infogobierno')){
				$this->redirect('/infogobierno/salir_todo');
				exit();
		}
	}//fin checksession


	function beforeFilter(){
		$this->checkSession();
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


function index(){
	$this->layout="ajax";

	$cedula = $_SESSION['infogobierno']['cedula_identidad'];
	$condicion = $this->v_cnmd06_fichas_datos_personales->findAll("cedula_identidad=$cedula and (condicion_actividad=1 OR condicion_actividad=2 OR condicion_actividad=3 OR condicion_actividad=4 OR condicion_actividad=8)",'condicion_actividad', null, 1);
	$nominas = $this->v_cnmd06_fichas->findAll("cedula_identidad=$cedula and (condicion_actividad_ficha=1 OR condicion_actividad_ficha=2 OR condicion_actividad_ficha=3 OR condicion_actividad_ficha=4 OR condicion_actividad_ficha=8)",'cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, denominacion_dependencia, cod_tipo_nomina, cod_cargo, cod_ficha, denominacion_nomina, primer_apellido, primer_nombre');

	if(count($nominas)!=0){
		foreach($nominas as $n){
			$cod[] = $n['v_cnmd06_fichas']['cod_presi'].'-'.$n['v_cnmd06_fichas']['cod_entidad'].'-'.$n['v_cnmd06_fichas']['cod_tipo_inst'].'-'.$n['v_cnmd06_fichas']['cod_inst'].'-'.$n['v_cnmd06_fichas']['cod_dep'].'-'.$n['v_cnmd06_fichas']['cod_tipo_nomina'].'-'.$n['v_cnmd06_fichas']['cod_cargo'].'-'.$n['v_cnmd06_fichas']['cod_ficha'];
			$deno[] = mascara_tres($n['v_cnmd06_fichas']['cod_tipo_nomina']).' - '.strtoupper($n['v_cnmd06_fichas']['denominacion_dependencia']).' - '.strtoupper($n['v_cnmd06_fichas']['denominacion_nomina']);
		}
		$lista=array_combine($cod, $deno);
		$this->set('permite','si');
	}else{
		$lista=array('0'=>'No se encuentra presente en ninguna nomina');
		$this->set('permite','no');
	}
	$this->set('condicion',$condicion);
	$this->set('lista',$lista);
	$this->set('cedula',$cedula);
	$this->set('nominas_trabajador',$nominas);
}//fin



function procesar(){
	$this->layout="ajax";
	$cod_dep_nomina = $this->data['cnmd06_constancia']['cod_nomina'];
	$cedula_identidad = $this->data['cnmd06_constancia']['cedula_identidad2'];
	$aleatorio=intval(rand());

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

	$datos_perso = $this->v_cnmd06_fichas->findAll($this->codSS($cod_dep_nomina)." and cedula_identidad='$cedula_identidad'",'cod_tipo_nomina, cod_ficha, cedula_identidad, nacionalidad, sexo, estado_civil', null, 1);

// Codigos para el cod certificacion:
	$cedula_s1 = substr($datos_perso[0]['v_cnmd06_fichas']['cedula_identidad'], -4, 4);
	$cedula_s2 = substr($datos_perso[0]['v_cnmd06_fichas']['cedula_identidad'], 0, 2);
	$aleatorio = substr($aleatorio, 0, 2);
	$segun = date("s");
	$mes_act = $this->mes_cod(date("m"));
	$ano_act = substr(date("Y"), -2, 2);
	$num_a1 = $this->num_aleatorio($datos_perso[0]['v_cnmd06_fichas']['cedula_identidad']);
	$num_a2 = $this->num_aleatorio($datos_perso[0]['v_cnmd06_fichas']['cedula_identidad']);
// Fin codigos

	$codigo_certificacion = $num_a1.$datos_perso[0]['v_cnmd06_fichas']['sexo'].$cedula_s1.$datos_perso[0]['v_cnmd06_fichas']['estado_civil'].mascara($datos_perso[0]['v_cnmd06_fichas']['cod_ficha'], 2).$datos_perso[0]['v_cnmd06_fichas']['nacionalidad'].$cedula_s2.$datos_perso[0]['v_cnmd06_fichas']['cod_tipo_nomina'].$aleatorio.$num_a2.$mes_act.$ano_act.$segun;
	$fecha_emision = date("Y-m-d");
	$mes = date("m");

	if($mes!='12'){
		$mes = $mes + 1;
		$mes = mascara($mes, 2);
		if($mes==2 AND date("d")>=28){
			$fecha_expiracion = date("Y")."-".$mes."-"."28";
		}elseif ($mes!=2 AND date("d")==31) {
			$fecha_expiracion = date("Y")."-".$mes."-"."30";
		}else{
			$fecha_expiracion = date("Y")."-".$mes."-".date("d");
		}
		
	}else{
		$fecha_expiracion = date("Y")."-".$mes."-31";
	}


	/*
	$certificacion = $this->cnmd06_constancia_certificacion->findAll($this->codSS($cod_dep_nomina)." and cedula_identidad='$cedula_identidad'", 'codigo_certificacion', null, 1);
	if(!empty($certificacion)){
	}
	*/

	$fecha_exp = $this->cnmd06_constancia_certificacion->findAll($this->codSS($cod_dep_nomina)." and cedula_identidad='$cedula_identidad'",'fecha_expiracion', null, 1);

	if(!empty($fecha_exp)){

		$fecha = $fecha_exp[0]['cnmd06_constancia_certificacion']['fecha_expiracion'];
            $yearf = $fecha[0] . $fecha[1] . $fecha[2] . $fecha[3];
            $mesf = $fecha[5] . $fecha[6];
            $diaf = $fecha[8] . $fecha[9];

	 $fecha_expira = $diaf."-".$mesf."-".$yearf;
	 $fecha_dia = date("d-m-Y");

		if ($this->compara_fechas($fecha_dia,$fecha_expira) >0){ // Si Expira o caduca el codigo de certificacion

				$this->cnmd06_constancia_certificacion->execute("DELETE FROM cnmd06_constancia_certificacion WHERE ".$this->codSS($cod_dep_nomina)." and cedula_identidad='$cedula_identidad';");
				$sw_c = $this->cnmd06_constancia_certificacion->execute("INSERT INTO cnmd06_constancia_certificacion VALUES ($cod_presi, $cod_entidad, $cod_tipo_inst, $cod_inst, $cod_dep, $cod_tipo_nomina, $cod_cargo, $cod_ficha, '$cedula_identidad', '".$codigo_certificacion."', '$fecha_emision', '$fecha_expiracion');");
		}else{
      $sw_c = $this->cnmd06_constancia_certificacion->execute("UPDATE cnmd06_constancia_certificacion SET fecha_emision='$fecha_emision', fecha_expiracion='$fecha_expiracion' WHERE cod_presi=$cod_presi and cod_entidad=$cod_entidad and cod_tipo_inst=$cod_tipo_inst and cod_inst=$cod_inst and cod_dep=$cod_dep and cod_tipo_nomina=$cod_tipo_nomina and cod_cargo=$cod_cargo and cod_ficha=$cod_ficha and cedula_identidad=$cedula_identidad;");
		}
	}else{

		$this->cnmd06_constancia_certificacion->execute("DELETE FROM cnmd06_constancia_certificacion WHERE ".$this->codSS($cod_dep_nomina)." and cedula_identidad='$cedula_identidad';");
		$sw_c = $this->cnmd06_constancia_certificacion->execute("INSERT INTO cnmd06_constancia_certificacion VALUES ($cod_presi, $cod_entidad, $cod_tipo_inst, $cod_inst, $cod_dep, $cod_tipo_nomina, $cod_cargo, $cod_ficha, '$cedula_identidad', '".$codigo_certificacion."', '$fecha_emision', '$fecha_expiracion');");
	}

	if($sw_c>1){
		$this->set("procesado", true);
	}else{
		$this->set("procesado", false);
	}

	$this->set("cod_dep_nomina", $cod_dep_nomina);
	$this->set("cedula_identidad", $cedula_identidad);
}




function prueba(){
	$this->layout = "pdf";
}

function constancia_trabajo(){
	$this->layout = "pdf";
	$cod_dep_nomina = $this->data['cnmd06_constancia']['codigo_completo'];
	$cedula_identidad = $this->data['cnmd06_constancia']['ced_identidad'];
	$aleatorio=intval(rand());

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

	if($cod_tipo_inst != '50'){
		$campo_deno = 'deno_cod_secretaria';
	}else{
		$campo_deno = 'deno_cod_direccion';
	}


	$sueldo = $this->v_cnmd06_fichas->findAll($this->codSS($cod_dep_nomina)." and cedula_identidad='$cedula_identidad'",'sueldo_integral,'.$campo_deno, null, 1);
	$datos_constancia = $this->v_cnmd06_fichas_datos_personales->findAll($this->codSS($cod_dep_nomina)." and cedula_identidad='$cedula_identidad' and (condicion_actividad=1 OR condicion_actividad=2 OR condicion_actividad=3 OR condicion_actividad=4 OR condicion_actividad=8)",'cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, tipo_nomina, cedula_identidad, nacionalidad, primer_apellido, segundo_apellido, primer_nombre, segundo_nombre, denominacion_clase, fecha_ingreso, sexo', null, 1);
	$datos_firma = $this->cnmd06_constancia_firmante->findAll($this->codSS($cod_dep_nomina, false),'funcionario_firmante, cargo_firmante, resolucion', null, 1);
	$certificacion = $this->cnmd06_constancia_certificacion->findAll($this->codSS($cod_dep_nomina)." and cedula_identidad='$cedula_identidad'",'cedula_identidad, codigo_certificacion, fecha_emision, fecha_expiracion', null, 1);

	if($cod_dep != '1'){
		$deno_inst = $this->cugd02_institucion->execute("SELECT denominacion, direccion, cod_area, telefonos FROM cugd02_dependencias WHERE cod_tipo_institucion = $cod_tipo_inst and cod_institucion = $cod_inst and cod_dependencia = $cod_dep LIMIT 1;");
	}else{
		$deno_inst = $this->cugd02_institucion->execute("SELECT denominacion, direccion, cod_area, telefonos FROM cugd02_institucion WHERE cod_tipo_institucion = $cod_tipo_inst and cod_institucion = $cod_inst LIMIT 1;");
	}


	$cod_defecto = $this->cugd02_institucion->execute("SELECT cod_republica, cod_estado, cod_municipio FROM cugd90_municipio_defecto WHERE ".$this->codSS($cod_dep_nomina, false)." LIMIT 1;");

	$republica = $this->cugd02_institucion->execute("SELECT denominacion FROM cugd01_republica WHERE cod_republica=".$cod_defecto[0][0]['cod_republica']." LIMIT 1;");
	$estado = $this->cugd02_institucion->execute("SELECT denominacion FROM cugd01_estados WHERE cod_republica=".$cod_defecto[0][0]['cod_republica']." and cod_estado=".$cod_defecto[0][0]['cod_estado']." LIMIT 1;");
	$cod_zona = $this->cugd02_institucion->execute("SELECT conocido, zona_postal FROM cugd01_municipios WHERE cod_republica=".$cod_defecto[0][0]['cod_republica']." and cod_estado=".$cod_defecto[0][0]['cod_estado']." and cod_municipio=".$cod_defecto[0][0]['cod_municipio']." LIMIT 1;");



	  	// $rs_img=$this->cnmd06_constancia_firmante->execute("SELECT coalesce(logo_derecho,'-1') as imagen, tipo_logo_derecho as tipo FROM cnmd06_constancia_firmante WHERE ".$this->codSS($cod_dep_nomina, false)." and ".$aleatorio."=".$aleatorio);


  	$datos_imgs=$this->cnmd06_constancia_firmante->execute("SELECT tipo_logo_derecho, tipo_logo_izquierdo, tipo_imagen_sello, tipo_imagen_sello_firma, tipo_imagen_firma FROM cnmd06_constancia_firmante WHERE ".$this->codSS($cod_dep_nomina, false)." and ".$aleatorio."=".$aleatorio);

	$cod_imagen = $datos_constancia[0]['v_cnmd06_fichas_datos_personales']['cod_presi']."_".$datos_constancia[0]['v_cnmd06_fichas_datos_personales']['cod_entidad']."_".$datos_constancia[0]['v_cnmd06_fichas_datos_personales']['cod_tipo_inst']."_".$datos_constancia[0]['v_cnmd06_fichas_datos_personales']['cod_inst']."_".$datos_constancia[0]['v_cnmd06_fichas_datos_personales']['cod_dep'];

	$this->set("cod_imagen", $cod_imagen);
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
	$this->set("cod_dep", $datos_constancia[0]['v_cnmd06_fichas_datos_personales']['cod_dep']);
}



// Devuelve los dias exactos entre dos fechas:

function calcula_dias_fecha($fecha_row, $fecha_actual){
	$segundos=strtotime($fecha_row) - strtotime($fecha_actual);
	$diferencia_dias=intval($segundos/60/60/24);
	return $diferencia_dias;
}



function compara_fechas($fecha1,$fecha2){
      if (preg_match("/[0-9]{1,2}\/[0-9]{1,2}\/([0-9][0-9]){1,2}/",$fecha1))
              list($dia1,$mes1,$año1)=split("/",$fecha1);
      if (preg_match("/[0-9]{1,2}-[0-9]{1,2}-([0-9][0-9]){1,2}/",$fecha1))
              list($dia1,$mes1,$año1)=split("-",$fecha1);
      if (preg_match("/[0-9]{1,2}\/[0-9]{1,2}\/([0-9][0-9]){1,2}/",$fecha2))
              list($dia2,$mes2,$año2)=split("/",$fecha2);
      if (preg_match("/[0-9]{1,2}-[0-9]{1,2}-([0-9][0-9]){1,2}/",$fecha2))
              list($dia2,$mes2,$año2)=split("-",$fecha2);
        $dif = mktime(0,0,0,$mes1,$dia1,$año1) - mktime(0,0,0, $mes2,$dia2,$año2);
        return ($dif);
}//fin function


// Funcion para calcular numero entero aleatorio de una cadena, donde n: es el rango max
function num_aleatorio($cadena=null){
	if($cadena!=null){
		$n = strlen($cadena);
	}else{
		$n=8;
	}

    for($i=0;$i<$n;$i++){
        $numero[$i] = ($i+1);
    }
    $get = count($numero)-1;
    $aleatoreo = rand(0,$get);
    return $aleatoreo;
}



// Funcion para calcular numero entero aleatorio directo, donde n: es el rango max
function num_aleatorio2($n=8){
    for($i=0;$i<$n;$i++){
        $numero[$i] = ($i+1);
    }
    $get = count($numero)-1;
    $aleatoreo = rand(0,$get);
    return $aleatoreo;
}


    function mes_cod($value_mes = null) {
        if (empty($value_mes)) {
            $value_mes = date("m");
        }

        $meses_cod = array('01' => 'E1', '02' => 'F2', '03' => 'M3', '04' => 'A4', '05' => 'M5', '06' => 'J6', '07' => 'J7', '08' => 'A8', '09' => 'S9', '10' => 'O0', '11' => 'N1', '12' => 'D2');
		$valor_cod = $meses_cod[$value_mes];
		if(empty($valor_cod)){
			$valor_cod = "Z0";
		}

        return $valor_cod;
    }

} // Fin class

?>
