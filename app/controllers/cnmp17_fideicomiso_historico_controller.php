<?php
 class Cnmp17FideicomisoHistoricoController extends AppController{
	var $uses = array('v_cnmd08_historia_trans_con','v_cnmd08_historia_trabajador_vision','cnmd17_fideicomiso_cuentas_bancarias','v_cnmp17_fideicomiso_tipo_nomina','Cnmd01','v_cnmd08_historia_trabajador_vision');
 	var $helpers = array('Html','Ajax','Javascript', 'Sisap');
 	var $name = "cnmp17_fideicomiso_historico";


 	function checkSession(){
 	if (!$this->Session->check('Usuario')){
 		$this->redirect('/salir/');
		exit();
	}else{
		$this->requestAction('/usuarios/actualizar_user');
	}

 }//fin checkSession



 function beforeFilter(){
 	$this->checkSession();
 }//fin before filter

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



function codigo_nomina2($codigo=null){
	$this->layout = "ajax";

if($codigo!=null){
	$a = $this->v_cnmp17_fideicomiso_tipo_nomina->findAll($this->SQLCA()." and cod_tipo_nomina=".$codigo,array('cod_tipo_nomina','denominacion'));
    $this->Session->write('cod_tipo_nomina',$codigo);

	if($a!=null){
		echo "<script>
				document.getElementById('codigo_tipo_nom').value='".mascara($a[0]['v_cnmp17_fideicomiso_tipo_nomina']['cod_tipo_nomina'], 3)."';
				document.getElementById('denominacion_deno_nom').value='".$a[0]['v_cnmp17_fideicomiso_tipo_nomina']['denominacion']."';
                                $('opciones').style.visibility='visible';
                                $('submit_pdf').disabled=false;
                            </script>";
	}else{
		echo "<script>
				document.getElementById('codigo_tipo_nom').value='';
				document.getElementById('denominacion_deno_nom').value='';
                               $('opciones').style.visibility='hidden';
                               $('submit_pdf').disabled=true;
</script>";
	}
}else{
	$this->set('mensajeError','No llego el c&oacute;digo del tipo de n&oacute;mina para procesar - Seleccione N&oacute;mina');
	echo "<script>
			document.getElementById('codigo_tipo_nom').value='';
			document.getElementById('denominacion_deno_nom').value='';
                         $('opciones').style.visibility='hidden';
                         $('submit_pdf').disabled=true;

		</script>";
}
}//fin codigo_nomina2


function index_detallado(){
$this->layout ="ajax";
$cod_presi = $this->Session->read('SScodpresi');
$cod_entidad = $this->Session->read('SScodentidad');
$cod_tipo_inst = $this->Session->read('SScodtipoinst');
$cod_inst = $this->Session->read('SScodinst');
$cod_dep = $this->Session->read('SScoddep');
$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep;

   	$lista = $this->v_cnmp17_fideicomiso_tipo_nomina->generateList($this->SQLCA(), $order = 'cod_tipo_nomina', $limit = null, '{n}.v_cnmp17_fideicomiso_tipo_nomina.cod_tipo_nomina', '{n}.v_cnmp17_fideicomiso_tipo_nomina.denominacion');
	if(!empty($lista)){
		$this->concatenaN($lista, 'cod_tipo_nomina');
	}else{
		$this->set('cod_tipo_nomina', $lista);
	}

    echo"   <script>document.getElementById('submit_pdf').disabled=true;</script>";
}//fin index detallado



function historico_detallado(){
	$this->layout ="pdf";
	set_time_limit(0);
	ini_set("memory_limit","2048M");
$cod_presi = $this->Session->read('SScodpresi');
$cod_entidad = $this->Session->read('SScodentidad');
$cod_tipo_inst = $this->Session->read('SScodtipoinst');
$cod_inst = $this->Session->read('SScodinst');
$cod_dep = $this->Session->read('SScoddep');
$cod_tipo_nomina = $this->Session->read('cod_tipo_nomina');
$ano = $this->Session->read('ano_nomina');
$cedula_identidad2=$this->data['reporte_arc']['cedula_identidad2'];

$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep;

if ($ano != null) {$condi=$condi."and a.ano=".$ano." "; }
if ($cedula_identidad2 != null) {$condi=$condi."and ficha.cedula_identidad=".$cedula_identidad2." "; }


//AGREGAR LAS DEMÁS CONDICIONES: AÑO Y/O UN TRABAJADOR EN PARTICULAR

    $sql_busca_perma = "SELECT
  a.*,cnmd01.denominacion,
  (ficha.primer_nombre || ' '  ||ficha.primer_apellido) as nombre_completo,
  to_char(fecha_ingreso, 'dd/mm/yyyy') as fecha_ingreso
FROM
  public.cnmd17_fideicomiso_trimestral_perma as a,
  public.cnmd01 ,
  public.cnmd06_datos_personales as ficha
WHERE
cnmd01.cod_presi = a.cod_presi
and cnmd01.cod_entidad = a.cod_entidad
and cnmd01.cod_tipo_inst = a.cod_tipo_inst
and cnmd01.cod_dep = a.cod_dep
and cnmd01.cod_tipo_nomina = a.cod_tipo_nomina
and a.cedula_identidad = ficha.cedula_identidad and
a.cod_tipo_nomina = cnmd01.cod_tipo_nomina $condi and a.cod_presi=$cod_presi and a.cod_entidad=$cod_entidad and a.cod_tipo_inst=$cod_tipo_inst and a.cod_inst=$cod_inst and a.cod_dep=$cod_dep and a.cod_tipo_nomina=$cod_tipo_nomina";
    $sql_perma = $this->Cnmd01->execute($sql_busca_perma);
    if(!empty($sql_perma)){
        $this->set('historico_detallado', $sql_perma);
        $this->set('titulo_reporte', 'FIDEICOMISO HISTORICO-DETALLADO');

        echo "<script>
         $('submit_pdf').disabled = false;
          </script>";

    }else{
    	$this->set('historico_detallado', array());
		echo "<script> fun_msj('NO SE ENCONTRAR&Oacute;N DATOS PARA PROCESAR');</script>";

		echo "<script>
         $('Generar').disabled = true;
          </script>";
    }


}//fin historico_detallado



function index_resumido(){
$this->layout ="ajax";
$cod_presi = $this->Session->read('SScodpresi');
$cod_entidad = $this->Session->read('SScodentidad');
$cod_tipo_inst = $this->Session->read('SScodtipoinst');
$cod_inst = $this->Session->read('SScodinst');
$cod_dep = $this->Session->read('SScoddep');
$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep;

   	$lista = $this->v_cnmp17_fideicomiso_tipo_nomina->generateList($this->SQLCA(), $order = 'cod_tipo_nomina', $limit = null, '{n}.v_cnmp17_fideicomiso_tipo_nomina.cod_tipo_nomina', '{n}.v_cnmp17_fideicomiso_tipo_nomina.denominacion');
	if(!empty($lista)){
		$this->concatenaN($lista, 'cod_tipo_nomina');
	}else{
		$this->set('cod_tipo_nomina', array());
	}
}//fin index resumido



function historico_resumido(){
	$this->layout ="pdf";
	set_time_limit(0);
	ini_set("memory_limit","2048M");
$cod_presi = $this->Session->read('SScodpresi');
$cod_entidad = $this->Session->read('SScodentidad');
$cod_tipo_inst = $this->Session->read('SScodtipoinst');
$cod_inst = $this->Session->read('SScodinst');
$cod_dep = $this->Session->read('SScoddep');
$cod_tipo_nomina = $this->Session->read('cod_tipo_nomina');
$ano = $this->Session->read('ano_nomina');
$cedula_identidad2=$this->data['reporte_arc']['cedula_identidad2'];

$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep;

if ($ano != null) {$condi=$condi."and v.ano=".$ano." "; }
if ($cedula_identidad2 != null) {$condi=$condi."and f.cedula_identidad=".$cedula_identidad2." "; }


//AGREGAR LAS DEMÁS CONDICIONES: AÑO Y/O UN TRABAJADOR EN PARTICULAR

    $sql_busca_perma = "SELECT * from cnmd17_fideicomiso_trimestral_perma as v, cnmd06_datos_personales f , cnmd01 as n
WHERE
v.cedula_identidad = f.cedula_identidad
and v.cod_presi = n.cod_presi
and v.cod_entidad = n.cod_entidad
and v.cod_tipo_inst = n.cod_tipo_inst
and v.cod_inst = n.cod_inst
and v.cod_dep = n.cod_dep
and v.cod_tipo_nomina = n.cod_tipo_nomina
and v.cod_presi=$cod_presi
and v.cod_entidad=$cod_entidad
and v.cod_tipo_inst=$cod_tipo_inst
and v.cod_inst=$cod_inst
and v.cod_dep=$cod_dep
and v.cod_tipo_nomina=$cod_tipo_nomina"." ".$condi;

    $sql_perma = $this->cnmd17_fideicomiso_cuentas_bancarias->execute($sql_busca_perma);
    if(!empty($sql_perma)){
        $this->set('historico_resumido', $sql_perma);
        $this->set('titulo_reporte', 'FIDEICOMISO HISTORICO-RESUMIDO');
    }else{
        $this->set('historico_resumido', array());
        $this->set('titulo_reporte', 'FIDEICOMISO HISTORICO-RESUMIDO');
    }
}//fin historico_resumido


 function seleccion($var=null){
       $this->layout = "ajax";
       if ($var==1){
           echo "<script>document.getElementById('datos_personales').style.visibility='hidden';
                       document.getElementById('submit_pdf').disabled=false;
               </script>";
       }else {
           echo "<script>document.getElementById('datos_personales').style.visibility='visible';
                         document.getElementById('submit_pdf').disabled=true;
                </script>";
       }

    }



    function buscar_persona_historico_recibos($var1 = null) {
        $this->layout = "ajax";
        $this->set("opcion", $var1);
        $this->Session->delete('pista');
        $this->Session->write('pista_opcion', 2);
    }

//fin function

    function buscar_persona_historico_2_recibos($var1 = null, $var2 = null, $var3 = null) {
        $this->layout = "ajax";
        $sql_like = "";
        $tipo_nomina = $this->Session->read('tipo_nomina');
        $ano_nomina = $this->Session->read('ano_nomina');
        $opcion_buscar_historico = $this->Session->read('opcion_buscar_historico');

       if ( $ano_nomina != null){ $condi="'  and ano='" . $ano_nomina . "'";}


        $cod_presi = $this->Session->read('SScodpresi');
        $cod_entidad = $this->Session->read('SScodentidad');
        $cod_tipo_inst = $this->Session->read('SScodtipoinst');
        $cod_inst = $this->Session->read('SScodinst');
        $cod_dep = $this->Session->read('SScoddep');

        if ($var3 == null) {
            $var2 = strtoupper($var2);
            $this->Session->write('pista', $var2);
            $var_like = $var2;
            if ($opcion_buscar_historico == 1) {
                $sql_like = $this->SQLCA() . " and (" . $this->sql_like_super_busqueda($var_like, $this->Session->read('pista_opcion')) . ") and cod_tipo_nomina   =  '" . $tipo_nomina .$condi;
            } else {
                $sql_like = $this->SQLCA() . " and (" . $this->sql_like_super_busqueda($var_like, $this->Session->read('pista_opcion')) . ") and cod_tipo_nomina   =  '" . $tipo_nomina . $condi;
            }
            $Tfilas = $this->v_cnmd08_historia_trabajador_vision->findCount($sql_like); // v_cnmd08_historia_trabajador
            if ($Tfilas != 0) {
                $pagina = 1;
                $Tfilas = (int) ceil($Tfilas / 100);
                $this->set('pag_cant', $pagina . '/' . $Tfilas);
                $this->set('total_paginas', $Tfilas);
                $this->set('pagina_actual', $pagina);
                $this->set('ultimo', $Tfilas);
                $datos_filas = $this->v_cnmd08_historia_trabajador_vision->findAll($sql_like, "  DISTINCT   cedula_identidad, cod_cargo, cod_ficha, primer_nombre, segundo_nombre, primer_apellido, segundo_apellido ", "primer_nombre,primer_apellido ASC", 100, 1, null);
                $sql = "";

                $this->set("datosFILAS", $datos_filas);
                $this->set('siguiente', $pagina + 1);
                $this->set('anterior', $pagina - 1);
                $this->bt_nav($Tfilas, $pagina);
            } else {
                $this->set("datosFILAS", '');
            }
        } else {
            $var22 = $this->Session->read('pista');
            $var22 = strtoupper($var22);
            $var_like = $var22;
            if ($opcion_buscar_historico == 1) {
                $sql_like = $this->SQLCA() . " and (" . $this->sql_like_super_busqueda($var_like, $this->Session->read('pista_opcion')) . ") and cod_tipo_nomina   =  '" . $tipo_nomina . "'".$condi;
            } else {
                $sql_like = $this->SQLCA() . " and (" . $this->sql_like_super_busqueda($var_like, $this->Session->read('pista_opcion')) . ") and cod_tipo_nomina   =  '" . $tipo_nomina . "'".$condi;
            }
            $Tfilas = $this->v_cnmd08_historia_trabajador_vision->findCount($sql_like);
            if ($Tfilas != 0) {
                $pagina = $var3;
                $Tfilas = (int) ceil($Tfilas / 100);
                $this->set('pag_cant', $pagina . '/' . $Tfilas);
                $this->set('total_paginas', $Tfilas);
                $this->set('pagina_actual', $pagina);
                $this->set('ultimo', $Tfilas);
                $datos_filas = $this->v_cnmd08_historia_trabajador_vision->findAll($sql_like, "  DISTINCT   cedula_identidad, cod_cargo, cod_ficha, primer_nombre, segundo_nombre, primer_apellido, segundo_apellido ", "primer_nombre,primer_apellido ASC", 100, $pagina, null);
                $sql = "";
                $this->set("datosFILAS", $datos_filas);
                $this->set('siguiente', $pagina + 1);
                $this->set('anterior', $pagina - 1);
                $this->bt_nav($Tfilas, $pagina);
                //$this->set("dato_a",$dato_a);
            } else {
                $this->set("datosFILAS", '');
            }
        }//fin else
        $this->set("opcion", $var1);
    }

//fin buscar_persona_historico_2_recibos

    function buscar_persona_historico_3_recibos($var1 = null, $var2 = null, $pag_num = null) {
        $this->layout = "ajax";
        $tipo_nomina = $this->Session->read('tipo_nomina');
        $ano_nomina = $this->Session->read('ano_nomina');

        $cedula_identidad = $var1;
        $cod_cargo = $var2;

        $opcion_buscar_historico = $this->Session->read('opcion_buscar_historico');

        if ($opcion_buscar_historico == 1) {
            $sql_like = $this->SQLCA() . " and cod_tipo_nomina   =  '" . $tipo_nomina . "'  and ano='" . $ano_nomina . "' and cedula_identidad='" . $cedula_identidad . "' and cod_cargo='" . $cod_cargo . "'  ";
        } else {
            $sql_like = $this->SQLCA() . " and cod_tipo_nomina   =  '" . $tipo_nomina . "'  and ano='" . $ano_nomina . "' and cedula_identidad='" . $cedula_identidad . "' and cod_cargo='" . $cod_cargo . "'  ";
        }

        $datos_cnmd07_transacciones_actuales = $this->v_cnmd08_historia_trans_con->findAll($sql_like, null, ' cod_tipo_transaccion,  cod_transaccion  ASC');
        $this->set("datos_cnmd07_transacciones_actuales", $datos_cnmd07_transacciones_actuales);

        echo"<script>";
            echo"document.getElementById('submit_pdf').disabled=false;";
        echo"</script>";
    }

//fin buscar_persona_historico_3_recibos

    function show_numero_nomina_2_recibos($tipo_nomina = null, $ano = null) {
        $this->layout = "ajax";
       $this->Session->write('ano_nomina', $ano);
       $this->Session->write('tipo_nomina', $tipo_nomina);

        if ($ano == '') {
         $script="
            $('submit_pdf').disabled=true;
            </script>";

        }else {
        $script=" <script>
            $('submit_pdf').disabled=false;
            </script>";
        }
     $this->set("script",$script);
    }



    function show_ano_nomina_2_recibos($opcion = null) {
        $this->layout = "ajax";
        $this->Session->delete('codig_tipo_nomina');
        $this->Session->write('codig_tipo_nomina', $cod_tipo_nomina);
        $cod_tipo_nomina=$this->Session->read('cod_tipo_nomina');

        if ($opcion == 2){

        $rsp = $this->Cnmd01->execute("SELECT DISTINCT ano  FROM cnmd08_historia_trabajador_fideicomiso  WHERE " . $this->condicion() . " and cod_tipo_nomina='" . $cod_tipo_nomina . "'  ORDER BY ano ASC");
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
        $this->set('opcion1', $cod_tipo_nomina);
        echo"<script>";
        echo"$('submit_pdf').disabled=true;
            $('select_ano').style.visibility='visible'";

        echo"</script>";

        }else if ($opcion == 1){
        echo"<script>";
        echo"$('submit_pdf').disabled=false;;
             $('select_ano').style.visibility='hidden'";
        echo"</script>";
        }
    }


}// FIN DE LA CLASE
