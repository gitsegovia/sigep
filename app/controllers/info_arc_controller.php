<?php
 class InfoArcController extends AppController{
	var $name = "info_arc";
    var $uses = array("ccfd04_cierre_mes", "cugd07_firmas_oficio_anulacion", "Cnmd01", "cnmd03_transaccion", "v_cnmd07_transacciones_actuales", "v_cnmd07_transacciones_actuales_con",
        "v_cnmd06_fichas","v_cnmd08_historia_trans_con","v_cnmd08_historia_trabajador", "v_cnmd08_historia_trabajador_vision", "v_cnmd08_historia_transacciones");
    var $helpers = array('Html', 'Javascript', 'Ajax', 'Sisap', 'Fpdf', 'infogob');


 function checkSession(){
	if (!$this->Session->check('infogobierno')){
		$this->redirect('/infogobierno/');
		exit();
	}else{
		$c1=substr_count (strtoupper($_SESSION['infogobierno']['cedula_identidad']), 'J');
					$c2=substr_count (strtoupper($_SESSION['infogobierno']['cedula_identidad']), 'G');
					if($c1!=0 || $c2!=0){
						echo "<script type=\"text/javascript\" language=\"javascript\">error('Por favor registrese cómo persona natural para acceder a la información');</script>";
                        exit();
					}
	}
 }


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
                    	if(isset($codigos[5]))
                    		$cod_tipo_nomina = " and cod_tipo_nomina=". $codigos[5];
                    	else
                    		$cod_tipo_nomina = "";
                    }else{$cod_tipo_nomina = "";}
                }else{
                    $cod_presi = null;
                    $cod_entidad = null;
                    $cod_tipo_inst = null;
                    $cod_inst = null;
                    $cod_dep = null;
                    $cod_tipo_nomina = "";
                }

		$condicions = "cod_presi=$cod_presi and cod_entidad=$cod_entidad and cod_tipo_inst=$cod_tipo_inst and cod_inst=$cod_inst and cod_dep=$cod_dep" . $cod_tipo_nomina;
		return $condicions;
	}


function reporte_arc_info() {
	$this->layout="ajax";

	$cedula = $_SESSION['infogobierno']['cedula_identidad'];
	$nominas = $this->v_cnmd06_fichas->findAll("cedula_identidad=$cedula",'cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, denominacion_dependencia, cod_tipo_nomina, cod_cargo, cod_ficha, denominacion_nomina, primer_apellido, primer_nombre');

	if(count($nominas)!=0){
		foreach($nominas as $n){
			$cod[] = $n['v_cnmd06_fichas']['cod_presi'].'-'.$n['v_cnmd06_fichas']['cod_entidad'].'-'.$n['v_cnmd06_fichas']['cod_tipo_inst'].'-'.$n['v_cnmd06_fichas']['cod_inst'].'-'.$n['v_cnmd06_fichas']['cod_dep'].'-'.$n['v_cnmd06_fichas']['cod_tipo_nomina'];
			$deno[] = mascara_tres($n['v_cnmd06_fichas']['cod_tipo_nomina']).' - '.strtoupper($n['v_cnmd06_fichas']['denominacion_dependencia']).' - '.strtoupper($n['v_cnmd06_fichas']['denominacion_nomina']);
		}
		$lista=array_combine($cod, $deno);
	}else{
		$lista=array('0'=>'No se encuentra presente en ninguna nomina');
	}
	$this->set('lista',$lista);
	$this->set('cedula',$cedula);
	$this->set('nominas_trabajador',$nominas);
}


function mostrar_ano($cedula = null, $cod_tipo_nomina = null){
	$this->layout="ajax";

		$cod_dep_nomina = $cod_tipo_nomina;

        $rsp = $this->Cnmd01->execute("SELECT DISTINCT ano FROM cnmd08_historia_trabajador_fideicomiso WHERE " . $this->codSS($cod_dep_nomina). " and cedula_identidad='$cedula' ORDER BY ano ASC");
        foreach ($rsp as $lp) {
            $vp[] = $lp[0]["ano"];
            $dp[] = $lp[0]["ano"];
        }
        if (isset($vp)) {
            $ano = array_combine($vp, $dp);
        } else {
            $ano = array();
        }
        $this->set('lista', $ano);
        echo "<script>document.getElementById('boton_garc').disabled=true;</script>";
}


function envia_form_firmas($num_tipo_doc = null, $cod_dep_nomina = array()) {

        if ($num_tipo_doc != null) {

            $firmantes = $this->cugd07_firmas_oficio_anulacion->findAll($this->codSS($cod_dep_nomina, false) . " and tipo_documento=" . $num_tipo_doc);

            if ($firmantes != null) {
                // $this->set('firma_existe', 'si');
                // $this->set('b_readonly', 'readonly');
                $this->set('tipo_documento', $firmantes[0]['cugd07_firmas_oficio_anulacion']['tipo_documento']);
                $this->set('nombre_primera_firma', $firmantes[0]['cugd07_firmas_oficio_anulacion']['nombre_primera_firma']);
                $this->set('cargo_primera_firma', $firmantes[0]['cugd07_firmas_oficio_anulacion']['cargo_primera_firma']);
                $this->set('nombre_segunda_firma', $firmantes[0]['cugd07_firmas_oficio_anulacion']['nombre_segunda_firma']);
                $this->set('cargo_segunda_firma', $firmantes[0]['cugd07_firmas_oficio_anulacion']['cargo_segunda_firma']);
                $this->set('nombre_tercera_firma', $firmantes[0]['cugd07_firmas_oficio_anulacion']['nombre_tercera_firma']);
                $this->set('cargo_tercera_firma', $firmantes[0]['cugd07_firmas_oficio_anulacion']['cargo_tercera_firma']);
                $this->set('nombre_cuarta_firma', $firmantes[0]['cugd07_firmas_oficio_anulacion']['nombre_cuarta_firma']);
                $this->set('cargo_cuarta_firma', $firmantes[0]['cugd07_firmas_oficio_anulacion']['cargo_cuarta_firma']);
                $this->set('cedula_primera_firma', $firmantes[0]['cugd07_firmas_oficio_anulacion']['primera_copia']);
                $this->set('cedula_segunda_firma', $firmantes[0]['cugd07_firmas_oficio_anulacion']['segunda_copia']);
                $this->set('cedula_tercera_firma', $firmantes[0]['cugd07_firmas_oficio_anulacion']['tercera_copia']);
                $this->set('cedula_cuarta_firma', $firmantes[0]['cugd07_firmas_oficio_anulacion']['cuarta_copia']);

            } else {
                // $this->set('Message_existe', 'POR FAVOR, INGRESE LOS NOMBRES Y CARGO DE LOS FIRMANTES');
                // $this->set('firma_existe', 'no');
                // $this->set('b_readonly', '');
                $this->set('tipo_documento', $num_tipo_doc);
                $this->set('nombre_primera_firma', '');
                $this->set('cargo_primera_firma', '');
                $this->set('nombre_segunda_firma', '');
                $this->set('cargo_segunda_firma', '');
                $this->set('nombre_tercera_firma', '');
                $this->set('cargo_tercera_firma', '');
                $this->set('nombre_cuarta_firma', '');
                $this->set('cargo_cuarta_firma', '');
                $this->set('cedula_primera_firma', '');
                $this->set('cedula_segunda_firma', '');
                $this->set('cedula_tercera_firma', '');
                $this->set('cedula_cuarta_firma', '');

            }
        } else {
            // $this->set('errorMessage', 'Disculpe, No llego el N&uacute;mero del Tipo de Documento para realizar el proceso de firmas');
        } // fin else num_tipo_doc dif. null
    }

// fin funcion envia_form_firmas


function generar_reporte_arc($proviene = null){
	/**
	 * ESTA FUNCION PUEDE IMPRIMIR TANTO PARA EL MODULO DE NOMINA COMO PARA EL MODULO INFOGOBIERNO...
	 * */
	$this->layout = "pdf";
	// Configure::write('debug',1);
	$cedula_identidad=$this->data['reporte_arc']['cedula_identidad2'];
	$ano_nomina=$this->data['reporte_arc']['ano_nomina'];

	if($proviene == 'i'){ // InfoGobierno...
		$cod_dep_nomina=$this->data['reporte_arc']['cod_nomina'];
		$condicion = $this->codSS($cod_dep_nomina)." and ano='$ano_nomina'";
	}else{
		$cod_tipo_nomina=$this->data['reporte_arc']['cod_nomina'];
		$condicion = $this->SQLCA()." and ano='$ano_nomina' and cod_tipo_nomina=".$cod_tipo_nomina;
	}

       if (isset($cedula_identidad) && ($cedula_identidad !='')){$condicion=$condicion." and cedula_identidad=".$cedula_identidad;}
				$sql_vista = "SELECT * FROM v_cnmd08_arc_historia_cuatro WHERE ".$condicion;
				$vista = $this->Cnmd01->execute($sql_vista);
				$this->set('vista',$vista);
                $this->set('titulo_reporte','REPORTE ARC');

				$this->envia_form_firmas(10006, $cod_dep_nomina);

}// fin generar_reporte_arc

 } // FIN CLASS INFO ARC
?>
