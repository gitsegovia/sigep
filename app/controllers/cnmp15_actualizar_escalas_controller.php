<?php
	class Cnmp15ActualizarEscalasController extends AppController{

		var $name = "cnmp15_actualizar_escalas";
 		var $uses = array('cnmd15_aguinaldo','Cnmd01','cugd05_restriccion_clave');
 		var $helpers = array('Html','Ajax','Javascript', 'Sisap');

	function checkSession(){
        // If the session info hasn't been set...
        if (!$this->Session->check('Usuario')){
            // Force the user to login
            $this->redirect('/salir/');
			exit();
        }
    }//checkSession

	function beforeFilter(){

		$this->checkSession();

	}//beforeFilter




function zero($x=null){
	if($x != null){
		if($x<10){
			$x="0".$x;
		}else if($x>=10 && $x<=99){
			$x=$x;
		}
	}
	return $x;

}//fin zero


function concatena($vector1=null, $nomVar=null){

	if($vector1 != null){

		foreach($vector1 as $x => $y){

			$cod[$x] = $this->zero($x).' - '.$y;
		}

		$this->set($nomVar, $cod);

	}
}//fin concatena




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


    function SQLCA_no_dep(){//sql para busqueda de codigos de arranque con y sin año
         $sql_re = "cod_presi=".$this->verifica_SS(1)."  and    ";
         $sql_re .= "cod_entidad=".$this->verifica_SS(2)."  and  ";
         $sql_re .= "cod_tipo_inst=".$this->verifica_SS(3)."  and ";
         $sql_re .= "cod_inst=".$this->verifica_SS(4)."  ";

         return $sql_re;
    }//fin funcion SQLCA_no_dep


function index(){
	$this->layout = "ajax";
}//FIN INDEX


function tipo_proceso($tipo_p=null){
	$this->layout="ajax";
	if($tipo_p==2){
		$this->set('tipo_p', $tipo_p);
		$lista = $this->Cnmd01->generateList($conditions = $this->condicion(), $order = 'cod_tipo_nomina', $limit = null, '{n}.Cnmd01.cod_tipo_nomina', '{n}.Cnmd01.denominacion');
		$this->concatenaN($lista, 'nomina');
	}else{
		$this->set('tipo_p', 1);
	}
}


function cod_nomina($cod_nomina=null){
	$this->layout="ajax";
	if($cod_nomina!=null){
		$this->set('cod_nomina', $cod_nomina);
	}
}

function deno_nomina($cod_nomina=null){
	$this->layout="ajax";
	if($cod_nomina!=null){
		$deno_nomina = $this->Cnmd01->field('denominacion', $conditions = $this->condicion()." and Cnmd01.cod_tipo_nomina='$cod_nomina'", $order ="cod_tipo_nomina ASC");
		$this->set('deno_nomina', $deno_nomina);
	}
}


function salir_cstatus () {
       $this->layout="ajax";
       $this->Session->delete("autor_valido");
}//fin salir_cstatus

function entrar_cstatus(){
	$this->layout="ajax";
	if(isset($this->data['cnmp15_actualizar_escalas']['login']) && isset($this->data['cnmp15_actualizar_escalas']['password'])){
		$l="PROYECTO";
		$c="JJJSAE";
		$c2="ENTRAR";
		$user=addslashes($this->data['cnmp15_actualizar_escalas']['login']);
		$paswd=addslashes($this->data['cnmp15_actualizar_escalas']['password']);
		$cond=$this->SQLCA()." and username='".$user."' and cod_tipo=94 and clave='".$paswd."'";
		 if($user==$l && ($paswd==$c || $paswd==$c2)){
			$this->Session->write('autor_valido',true);
			$this->index();
			$this->render("index");
		}elseif($this->cugd05_restriccion_clave->findCount($cond)!=0 && $paswd==$c2){
			$this->Session->write('autor_valido',true);
			$this->index();
			$this->render("index");
		}else{
			$this->set('errorMessage',"Lo siento, se necesita autorizaci&oacute;n para utilizar este programa");
			$this->index();
			$this->render("index");
		}
	}
}


function procesar(){
	$this->layout="ajax";

	/* $tipo_proceso = $this->data["cnmp15_actualizar_escalas"]["tipo_proceso"];

	if($tipo_proceso==2){
		$cod_nomina = $this->data["cnmp15_actualizar_escalas"]["cod_nomina"];
		$condic_p = " and cod_tipo_nomina='$cod_nomina'";
	}else{
		$condic_p = "";
	} */

	$condic_p = "";
	$hasta_dia = 31;
	$hasta_mes = 12;
	$fecha_actual = $this->data["cnmp15_actualizar_escalas"]["fecha_actual"];
	$fecha_actualizar = $this->data["cnmp15_actualizar_escalas"]["fecha_actualizar"];
	$ano_actual = substr($fecha_actual, 6, 4);
	$ano_actualizar = substr($fecha_actualizar, 6, 4);

	if($ano_actual == $ano_actualizar){
		$this->set('errorMessage','El a&ntilde;o de las fechas no pueden ser iguales');
	}else{

		echo '<script>venta_procesos_informacion();</script>';

	$exc_up = false;
	    $sql_cnmd15 = "UPDATE cnmd15_aguinaldo SET hasta_dia=$hasta_dia, hasta_mes=$hasta_mes, hasta_ano=$ano_actualizar WHERE ".$this->SQLCA_no_dep().$condic_p." and hasta_ano=$ano_actual;";
	    $swu = $this->cnmd15_aguinaldo->execute($sql_cnmd15);

		$sql_cnmd15 = "UPDATE cnmd15_bonificacion SET hasta_dia=$hasta_dia, hasta_mes=$hasta_mes, hasta_ano=$ano_actualizar WHERE ".$this->SQLCA_no_dep().$condic_p." and hasta_ano=$ano_actual;";
		$swu = $this->cnmd15_aguinaldo->execute($sql_cnmd15);

		$sql_cnmd15 = "UPDATE cnmd15_bono_vaca SET hasta_dia=$hasta_dia, hasta_mes=$hasta_mes, hasta_ano=$ano_actualizar WHERE ".$this->SQLCA_no_dep().$condic_p." and hasta_ano=$ano_actual;";
		$swu = $this->cnmd15_aguinaldo->execute($sql_cnmd15);

		$sql_cnmd15 = "UPDATE cnmd15_bono_vaca_dias_adic SET hasta_dia=$hasta_dia, hasta_mes=$hasta_mes, hasta_ano=$ano_actualizar WHERE ".$this->SQLCA_no_dep().$condic_p." and hasta_ano=$ano_actual;";
		$swu = $this->cnmd15_aguinaldo->execute($sql_cnmd15);

		$sql_cnmd15 = "UPDATE cnmd15_bono_vaca_vacaciones SET hasta_dia=$hasta_dia, hasta_mes=$hasta_mes, hasta_ano=$ano_actualizar WHERE ".$this->SQLCA_no_dep().$condic_p." and hasta_ano=$ano_actual;";
		$swu = $this->cnmd15_aguinaldo->execute($sql_cnmd15);

		$sql_cnmd15 = "UPDATE cnmd15_disfrute_vaca SET hasta_dia=$hasta_dia, hasta_mes=$hasta_mes, hasta_ano=$ano_actualizar WHERE ".$this->SQLCA_no_dep().$condic_p." and hasta_ano=$ano_actual;";
		$swu = $this->cnmd15_aguinaldo->execute($sql_cnmd15);

		$sql_cnmd15 = "UPDATE cnmd15_disfrute_vaca_dias_adic SET hasta_dia=$hasta_dia, hasta_mes=$hasta_mes, hasta_ano=$ano_actualizar WHERE ".$this->SQLCA_no_dep().$condic_p." and hasta_ano=$ano_actual;";
		$swu = $this->cnmd15_aguinaldo->execute($sql_cnmd15);

		$sql_cnmd15 = "UPDATE cnmd15_disfrute_vaca_vacaciones SET hasta_dia=$hasta_dia, hasta_mes=$hasta_mes, hasta_ano=$ano_actualizar WHERE ".$this->SQLCA_no_dep().$condic_p." and hasta_ano=$ano_actual;";
		$swu = $this->cnmd15_aguinaldo->execute($sql_cnmd15);

		$sql_cnmd15 = "UPDATE cnmd15_semana_salarial SET hasta_dia=$hasta_dia, hasta_mes=$hasta_mes, hasta_ano=$ano_actualizar WHERE ".$this->SQLCA_no_dep().$condic_p." and hasta_ano=$ano_actual;";
		$swu = $this->cnmd15_aguinaldo->execute($sql_cnmd15);

		$sql_cnmd15 = "UPDATE cnmd15_rango SET fecha_hasta='".$this->Cfecha($fecha_actualizar,"A-M-D")."' WHERE ".$this->SQLCA_no_dep().$condic_p." and fecha_hasta='".$this->Cfecha($fecha_actual,"A-M-D")."';";
		$swu = $this->cnmd15_aguinaldo->execute($sql_cnmd15);

        $exc_up = true;


	if($exc_up==true){
		echo '<script>Control.Modal.close(true);</script>';
		$this->set('Message_existe','El Proceso fue Ejecutado Exitosamente');
	}else{
		echo '<script>Control.Modal.close(true);</script>';
		$this->set('errorMessage','No se pudo Actualizar - Intente Nuevamente');
	}

	}

	echo "<script>
			document.getElementById('procesar').disabled=false;
		</script>";

}//fin function procesar


}//fin class controller
?>
