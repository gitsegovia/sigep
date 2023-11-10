<?php
/*
 * Creado el 08/01/2008 a las 03:28:45 PM
 * Herramienta: EasyEclipse.
 * Proyecto: SIGEP
 * Descripcion:
 */

 class Cfpp10RegistroAsientoContableController extends AppController{
 	var $name = 'cfpp10_registro_asiento_contable';
 	var $uses = array ('ccfd01_tipo', 'ccfd01_cuenta', 'ccfd01_subcuenta', 'ccfd01_division', 'ccfd01_subdivision','ccfd10_detalles',
                       'ccfd10_descripcion','ccfd05_numero_asiento','ccfd03_instalacion', 'cugd02_dependencia', 'ccfd04_cierre_mes',
                       'ccfd02', 'cugd05_restriccion_clave');
 	var $helpers = array ('Html','Ajax','Javascript','Sisap');


   function checkSession()
    {
        // If the session info hasn't been set...
        if (!$this->Session->check('Usuario'))
        {
            // Force the user to login
            $this->redirect('/salir');
            exit();
        }
    }

 function beforeFilter(){
 	$this->checkSession();
 	 echo'				<script>
							 document.getElementById("valida_codigo").innerHTML = "";
							 document.getElementById("valida_codigo").style.display = "none";
							 if(document.getElementById("registro")){document.getElementById("registro").style.visibility = "hidden";}
                             if(document.getElementById("nomina")){document.getElementById("nomina").style.visibility = "hidden";}
                             if(document.getElementById("dependencia")){document.getElementById("dependencia").style.visibility = "hidden";}
                             if(document.getElementById("sistema_general")){document.getElementById("sistema_general").style.visibility = "hidden";}
                         </script>';
 }

 function ss($i){
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
         $sql_re = "cod_presi=".$this->ss(1)."  and    ";
         $sql_re .= "cod_entidad=".$this->ss(2)."  and  ";
         $sql_re .= "cod_tipo_inst=".$this->ss(3)."  and ";
         $sql_re .= "cod_inst=".$this->ss(4)."  and  ";
         if($ano!=null){
         	$sql_re .= "cod_dep=".$this->ss(5)."  and  ";
            $sql_re .= "ano=".$ano."  ";
         }else{
         	$sql_re .= "cod_dep=".$this->ss(5)." ";
         }
         return $sql_re;
    }//fin funcion SQLCA



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


    function SQLCA_no_dep(){//sql para busqueda de codigos de arranque con y sin año
         $sql_re = "cod_presi=".$this->verifica_SS(1)."  and    ";
         $sql_re .= "cod_entidad=".$this->verifica_SS(2)."  and  ";
         $sql_re .= "cod_tipo_inst=".$this->verifica_SS(3)."  and ";
         $sql_re .= "cod_inst=".$this->verifica_SS(4)."  ";

         return $sql_re;
    }//fin funcion SQLCA_no_dep


function actualizame_op () {
    $this->layout="ajax";
    $this->Session->write('up_op',"");//date("H:i:s")

}


 function index($actual=null){
 	$this->layout="ajax";
 	$cod1=$this->verifica_SS(1);
	$cod2=$this->verifica_SS(2);
	$cod3=$this->verifica_SS(3);
	$cod4=$this->verifica_SS(4);
	$cod5=$this->verifica_SS(5);
 	$this->Session->delete("consolidar_consulta");
	$this->Session->delete("items1");
	$this->Session->delete("i");
	$this->Session->delete("contador");
	$this->Session->delete("snumero_asiento");
	$this->Session->delete("aux_numero_asiento");
	$this->Session->delete("smes_asiento");
	$this->Session->delete("auxn_mes_asiento");
	$this->Session->delete("auxn_numero_asiento");
	// $this->limpiar_lista();
	/* $ver=$this->ccfd03_instalacion->execute("SELECT ano_arranque,mes_arranque FROM ccfd03_instalacion WHERE ".$this->SQLCA()." ORDER BY mes_arranque DESC");
	$ano=$ver[0][0]["ano_arranque"];
	$mes=$ver[0][0]["mes_arranque"]; */
	$ano=$this->ano_ejecucion();
	$mes=date("m");
	$this->set('ano',$ano);
	$this->set('mes',$mes);

	$meses= array('1'=>'Enero','2'=>'Febrero','3'=>'Marzo','4'=>'Abril','5'=>'Mayo','6'=>'Junio','7'=>'Julio','8'=>'Agosto','9'=>'Septiembre','10'=>'Octubre','11'=>'Noviembre','12'=>'Diciembre');
	$this->concatena($meses, 'meses');

	$sql_desc = "SELECT * FROM ccfd10_descripcion_tmp WHERE ".$this->SQLCA()." and ano_asiento=".$ano." ORDER BY numero_asiento ASC LIMIT 1;";
	$sw_desc = $this->ccfd10_descripcion->execute($sql_desc);

	if(!empty($sw_desc)){
		$act='true';
		$this->Session->write("snumero_asiento",$sw_desc[0][0]["numero_asiento"]);
		$this->Session->write("aux_numero_asiento",$sw_desc[0][0]["numero_asiento"]);
		$this->Session->write("smes_asiento",$mes);
		$sql_det = "SELECT * FROM ccfd10_detalles_tmp WHERE ".$this->SQLCA()." and ano_asiento=".$sw_desc[0][0]["ano_asiento"]." and numero_asiento=".$sw_desc[0][0]["numero_asiento"]." ORDER BY numero_linea ASC;";
		$sw_det = $this->ccfd10_detalles->execute($sql_det);
		if(!empty($sw_det)){
			$act='false';
			$i=0;
			$monto_total=0;
			$_SESSION["items1"] = array();
			foreach($sw_det as $irows){
				$monto_total=$monto_total+$irows[0]['monto'];
				$_SESSION["items1"][$i][0] = $i+1;
			 	$_SESSION["items1"][$i][1] = $irows[0]['debito_credito'];
			 	$_SESSION["items1"][$i][2] = $irows[0]['cod_tipo_cuenta'];
			 	$_SESSION["items1"][$i][3] = $this->m2($irows[0]['cod_cuenta']);
			 	$_SESSION["items1"][$i][4] = $this->m3($irows[0]['cod_subcuenta']);
			 	$_SESSION["items1"][$i][5] = $this->m4($irows[0]['cod_division']);
			 	$_SESSION["items1"][$i][6] = $this->m3($irows[0]['cod_subdivision']);
			 	$_SESSION["items1"][$i][7] = $irows[0]['monto'];
			 	$_SESSION["items1"][$i][8] = $irows[0]['numero_linea'];
			 	$_SESSION["items1"][$i]["id"] = $i;
			 	$i++;
			}
			$this->Session->write("i",$i);
			$_SESSION["contador"] = $i+1;
			$this->set('num_prox_line',$irows[0]['numero_linea']+1);
			$this->set('cont_linea',$i+1);
			$this->set('total_partidas_rc',$monto_total);
		}

		$this->set('datos_desc',$sw_desc);
		$this->set('existe_desc',"si");
		$this->mostrar($sw_desc[0][0]['tipo_documento']);

		echo "<script> document.getElementById('concepto').value='".$sw_desc[0][0]["concepto"]."'; document.getElementById('save').disabled=$act; </script>";

	}else{
		if($actual!="no"){
			$v=$this->ccfd05_numero_asiento->execute("SELECT numero_asiento FROM ccfd05_numero_asiento WHERE ".$this->SQLCA()." and ano_asiento=".$ano." and mes_asiento=".$mes." ORDER BY numero_asiento DESC");
			if($v!=null){
				$numero=$v[0][0]["numero_asiento"];
				//$numero = $numero =="" ? 1 : $numero+1;
		if($numero==""){
			$numero = 1;
		}else{
			if($mes==1 && $numero<3){
				$numero = 2;
			}else{
				$numero = $numero+1;
			}
		}
				$sql="UPDATE ccfd05_numero_asiento SET numero_asiento=".$numero." WHERE ".$this->SQLCA()." AND ano_asiento=".$ano." AND mes_asiento=".$mes.";";
			}else{
				if($mes==1){
					$numero=2;
				}else{
					$numero=1;
				}
				$sql = "INSERT INTO ccfd05_numero_asiento VALUES('$cod1', '$cod2', '$cod3', '$cod4', '$cod5', '$ano', '$mes', '$numero');";
			}

			$sw1 = $this->ccfd05_numero_asiento->execute($sql);
			if($sw1>1){
				$this->set("numero",$numero);
			}else{
				$this->set("numero",'');
			}

			$this->Session->write("snumero_asiento",$numero);
			$this->Session->write("aux_numero_asiento",$numero);
			$this->Session->write("smes_asiento",$mes);
		}

		$this->set('existe_desc',"no");
		$this->data['cnmp09']['concepto']=null;
	}

 }// fin del index


 function verifica_asiento($var=null){
 	$this->layout="ajax";
	$ano=$this->ano_ejecucion();
	$this->set('ano',$ano);
 	if($var==1){
		$this->set('ver','otro');
		//$this->index("no");
 	$cod1=$this->verifica_SS(1);
	$cod2=$this->verifica_SS(2);
	$cod3=$this->verifica_SS(3);
	$cod4=$this->verifica_SS(4);
	$cod5=$this->verifica_SS(5);
 	$this->Session->delete("consolidar_consulta");
	$this->Session->delete("items1");
	$this->Session->delete("i");
	$this->Session->delete("contador");
	// $this->limpiar_lista();
	/* $ver=$this->ccfd03_instalacion->execute("SELECT ano_arranque,mes_arranque FROM ccfd03_instalacion WHERE ".$this->SQLCA()." ORDER BY mes_arranque DESC");
	$ano=$ver[0][0]["ano_arranque"];
	$mes=$ver[0][0]["mes_arranque"]; */
	$ano=$this->ano_ejecucion();

	if(isset($_SESSION["auxn_mes_asiento"]) && isset($_SESSION["auxn_numero_asiento"])){
		$mes = $this->Session->read("auxn_mes_asiento");
		$numero_asi = $this->Session->read("auxn_numero_asiento");
	}else{
		$mes = $this->Session->read("smes_asiento");
		$numero_asi = $this->Session->read("snumero_asiento");
	}

	$this->set('ano',$ano);
	$this->set('mes',$mes);
	//$actual="no";

	$veri = $this->Session->read("si_verifica");
	if($veri==true){
		$actual="si";
		$this->Session->write("si_verifica",false);
	}else if($veri==false){
		$actual="no";
	}else{
		$actual="no";
	}

	$meses= array('1'=>'Enero','2'=>'Febrero','3'=>'Marzo','4'=>'Abril','5'=>'Mayo','6'=>'Junio','7'=>'Julio','8'=>'Agosto','9'=>'Septiembre','10'=>'Octubre','11'=>'Noviembre','12'=>'Diciembre');
	$this->concatena($meses, 'meses');

	$sql_desc = "SELECT * FROM ccfd10_descripcion_tmp WHERE ".$this->SQLCA()." and ano_asiento=".$ano." ORDER BY numero_asiento ASC LIMIT 1;";
	$sw_desc = $this->ccfd10_descripcion->execute($sql_desc);

	if(!empty($sw_desc)){
		$act='true';
		$this->Session->write("snumero_asiento",$sw_desc[0][0]["numero_asiento"]);
		$this->Session->write("smes_asiento",$mes);
		$sql_det = "SELECT * FROM ccfd10_detalles_tmp WHERE ".$this->SQLCA()." and ano_asiento=".$sw_desc[0][0]["ano_asiento"]." and numero_asiento=".$sw_desc[0][0]["numero_asiento"]." ORDER BY numero_linea ASC;";
		$sw_det = $this->ccfd10_detalles->execute($sql_det);
		if(!empty($sw_det)){
			$act='false';
			$i=0;
			$monto_total=0;
			$_SESSION["items1"] = array();
			foreach($sw_det as $irows){
				$monto_total=$monto_total+$irows[0]['monto'];
				$_SESSION["items1"][$i][0] = $i+1;
			 	$_SESSION["items1"][$i][1] = $irows[0]['debito_credito'];
			 	$_SESSION["items1"][$i][2] = $irows[0]['cod_tipo_cuenta'];
			 	$_SESSION["items1"][$i][3] = $this->m2($irows[0]['cod_cuenta']);
			 	$_SESSION["items1"][$i][4] = $this->m3($irows[0]['cod_subcuenta']);
			 	$_SESSION["items1"][$i][5] = $this->m4($irows[0]['cod_division']);
			 	$_SESSION["items1"][$i][6] = $this->m3($irows[0]['cod_subdivision']);
			 	$_SESSION["items1"][$i][7] = $irows[0]['monto'];
			 	$_SESSION["items1"][$i][8] = $irows[0]['numero_linea'];
			 	$_SESSION["items1"][$i]["id"] = $i;
			 	$i++;
			}
			$this->Session->write("i",$i);
			$_SESSION["contador"] = $i+1;
			$this->set('num_prox_line',$irows[0]['numero_linea']+1);
			$this->set('cont_linea',$i+1);
			$this->set('total_partidas_rc',$monto_total);
		}

		$this->set('datos_desc',$sw_desc);
		$this->set('existe_desc',"si");
		$this->mostrar($sw_desc[0][0]['tipo_documento']);

		echo "<script> document.getElementById('concepto').value='".$sw_desc[0][0]["concepto"]."'; document.getElementById('concepto').readOnly=true; document.getElementById('save').disabled=$act; </script>";

	}else{
		if($actual!="no"){
			$v=$this->ccfd05_numero_asiento->execute("SELECT numero_asiento FROM ccfd05_numero_asiento WHERE ".$this->SQLCA()." and ano_asiento=".$ano." and mes_asiento=".$mes." ORDER BY numero_asiento DESC");
			if($v!=null){
				$numero=$v[0][0]["numero_asiento"];
				//$numero = $numero =="" ? 1 : $numero+1;
		if($numero==""){
			$numero = 1;
		}else{
			if($mes==1 && $numero<3){
				$numero = 2;
			}else{
				$numero = $numero+1;
			}
		}
				$sql="UPDATE ccfd05_numero_asiento SET numero_asiento=".$numero." WHERE ".$this->SQLCA()." AND ano_asiento=".$ano." AND mes_asiento=".$mes.";";
			}else{
				if($mes==1){
					$numero=2;
				}else{
					$numero=1;
				}
				$sql = "INSERT INTO ccfd05_numero_asiento VALUES('$cod1', '$cod2', '$cod3', '$cod4', '$cod5', '$ano', '$mes', '$numero');";
			}

			$sw1 = $this->ccfd05_numero_asiento->execute($sql);
			if($sw1>1){
				$this->set("numero",$numero);
			}else{
				$this->set("numero",'');
			}

			$this->Session->write("snumero_asiento",$numero);
			$this->Session->write("smes_asiento",$mes);
		}else{
			$this->Session->write("snumero_asiento",$numero_asi);
			$this->Session->write("smes_asiento",$mes);
			$this->set("numero",$numero_asi);
		}

		$this->set('existe_desc',"no");
		$this->data['cnmp09']['concepto']=null;
	}






 	}else{

		$v=$this->ccfd05_numero_asiento->execute("SELECT * FROM ccfd10_descripcion WHERE ".$this->SQLCA()." and ano_asiento=".$ano." and mes_asiento=1  and dia_asiento=1 and numero_asiento=1");
		if($v!=null){
			$this->Session->write("si_verifica",false);
			$this->set('errorMessage', 'EL ASIENTO DE APERTURA PARA ESTE A&Ntilde;O YA SE ENCUENTRA REGISTRADO');
			$this->set('ver','no');
		}else{
			$this->set('ver','si');

		$this->Session->write("si_verifica",true);
		$mes_aux = $this->Session->read("smes_asiento");
		$num_aux = $this->Session->read("snumero_asiento");
		$this->salir_asiento($ano,$mes_aux,$num_aux);

	//$this->index("no");
 	$cod1=$this->verifica_SS(1);
	$cod2=$this->verifica_SS(2);
	$cod3=$this->verifica_SS(3);
	$cod4=$this->verifica_SS(4);
	$cod5=$this->verifica_SS(5);
 	$this->Session->delete("consolidar_consulta");
	$this->Session->delete("items1");
	$this->Session->delete("i");
	$this->Session->delete("contador");
	// $this->limpiar_lista();
	/* $ver=$this->ccfd03_instalacion->execute("SELECT ano_arranque,mes_arranque FROM ccfd03_instalacion WHERE ".$this->SQLCA()." ORDER BY mes_arranque DESC");
	$ano=$ver[0][0]["ano_arranque"];
	$mes=$ver[0][0]["mes_arranque"]; */
	$ano=$this->ano_ejecucion();
	$mes=1;
	$numero=1;
	$this->set('ano',$ano);
	$this->set('mes',$mes);
	$actual="no";

	$meses= array('1'=>'Enero','2'=>'Febrero','3'=>'Marzo','4'=>'Abril','5'=>'Mayo','6'=>'Junio','7'=>'Julio','8'=>'Agosto','9'=>'Septiembre','10'=>'Octubre','11'=>'Noviembre','12'=>'Diciembre');
	$this->concatena($meses, 'meses');

	$sql_desc = "SELECT * FROM ccfd10_descripcion_tmp WHERE ".$this->SQLCA()." and ano_asiento=".$ano." ORDER BY numero_asiento ASC LIMIT 1;";
	$sw_desc = $this->ccfd10_descripcion->execute($sql_desc);

	if(!empty($sw_desc)){
		$act='true';
		$this->Session->write("snumero_asiento",$sw_desc[0][0]["numero_asiento"]);
		$this->Session->write("smes_asiento",$mes);
		$sql_det = "SELECT * FROM ccfd10_detalles_tmp WHERE ".$this->SQLCA()." and ano_asiento=".$sw_desc[0][0]["ano_asiento"]." and numero_asiento=".$sw_desc[0][0]["numero_asiento"]." ORDER BY numero_linea ASC;";
		$sw_det = $this->ccfd10_detalles->execute($sql_det);
		if(!empty($sw_det)){
			$act='false';
			$i=0;
			$monto_total=0;
			$_SESSION["items1"] = array();
			foreach($sw_det as $irows){
				$monto_total=$monto_total+$irows[0]['monto'];
				$_SESSION["items1"][$i][0] = $i+1;
			 	$_SESSION["items1"][$i][1] = $irows[0]['debito_credito'];
			 	$_SESSION["items1"][$i][2] = $irows[0]['cod_tipo_cuenta'];
			 	$_SESSION["items1"][$i][3] = $this->m2($irows[0]['cod_cuenta']);
			 	$_SESSION["items1"][$i][4] = $this->m3($irows[0]['cod_subcuenta']);
			 	$_SESSION["items1"][$i][5] = $this->m4($irows[0]['cod_division']);
			 	$_SESSION["items1"][$i][6] = $this->m3($irows[0]['cod_subdivision']);
			 	$_SESSION["items1"][$i][7] = $irows[0]['monto'];
			 	$_SESSION["items1"][$i][8] = $irows[0]['numero_linea'];
			 	$_SESSION["items1"][$i]["id"] = $i;
			 	$i++;
			}
			$this->Session->write("i",$i);
			$_SESSION["contador"] = $i+1;
			$this->set('num_prox_line',$irows[0]['numero_linea']+1);
			$this->set('cont_linea',$i+1);
			$this->set('total_partidas_rc',$monto_total);
		}

		$this->set('datos_desc',$sw_desc);
		$this->set('existe_desc',"si");
		$this->mostrar($sw_desc[0][0]['tipo_documento']);

		echo "<script> document.getElementById('concepto').value='".$sw_desc[0][0]["concepto"]."'; document.getElementById('save').disabled=$act; </script>";

	}else{

	/*
		if($actual!="no"){
			$v=$this->ccfd05_numero_asiento->execute("SELECT numero_asiento FROM ccfd05_numero_asiento WHERE ".$this->SQLCA()." and ano_asiento=".$ano." and mes_asiento=".$mes." ORDER BY numero_asiento DESC");
			if($v!=null){
				$numero=$v[0][0]["numero_asiento"];
				$numero = $numero =="" ? 1 : 1;
				$sql="UPDATE ccfd05_numero_asiento SET numero_asiento=".$numero." WHERE ".$this->SQLCA()." AND ano_asiento=".$ano." AND mes_asiento=".$mes.";";
			}else{
				if($mes==1){
					$numero=1;
				}else{
					$numero=1;
				}
				$sql = "INSERT INTO ccfd05_numero_asiento VALUES('$cod1', '$cod2', '$cod3', '$cod4', '$cod5', '$ano', '$mes', '$numero');";
			}

			$sw1 = $this->ccfd05_numero_asiento->execute($sql);
			if($sw1>1){
				$this->set("numero",$numero);
			}else{
				$this->set("numero",'');
			}

			$this->Session->write("snumero_asiento",$numero);
			$this->Session->write("smes_asiento",$mes);
		}

		else{
			$v=$this->ccfd05_numero_asiento->execute("SELECT numero_asiento FROM ccfd05_numero_asiento WHERE ".$this->SQLCA()." and ano_asiento=".$ano." and mes_asiento=".$mes." ORDER BY numero_asiento DESC");
			if($v!=null){
				$numero=$v[0][0]["numero_asiento"];
				$numero = $numero =="" ? 1 : 1;
				$sql="UPDATE ccfd05_numero_asiento SET numero_asiento=".$numero." WHERE ".$this->SQLCA()." AND ano_asiento=".$ano." AND mes_asiento=".$mes.";";
			}else{
				if($mes==1){
					$numero=1;
				}else{
					$numero=1;
				}
				$sql = "INSERT INTO ccfd05_numero_asiento VALUES('$cod1', '$cod2', '$cod3', '$cod4', '$cod5', '$ano', '$mes', '$numero');";
			}

			$sw1 = $this->ccfd05_numero_asiento->execute($sql);
			if($sw1>1){
				$this->set("numero",$numero);
			}else{
				$this->set("numero",'');
			}

			$this->Session->write("snumero_asiento",$numero);
			$this->Session->write("smes_asiento",$mes);
		}
*/

		$this->Session->write("snumero_asiento",$numero);
		$this->Session->write("smes_asiento",$mes);

		$this->set('existe_desc',"no");
		$this->data['cnmp09']['concepto']=null;
	}


		}

 	}

 }


function numero_asiento($mes=null){
	$this->layout="ajax";
 	$cod1=$this->verifica_SS(1);
	$cod2=$this->verifica_SS(2);
	$cod3=$this->verifica_SS(3);
	$cod4=$this->verifica_SS(4);
	$cod5=$this->verifica_SS(5);
	$this->Session->delete("snumero_asiento");

	if(isset($_SESSION["auxn_mes_asiento"])){
		$mes_aux = $this->Session->read("auxn_mes_asiento");
	}else{
		$mes_aux = $this->Session->read("smes_asiento");
	}

	if(isset($_SESSION["auxn_numero_asiento"])){
		$num_aux = $this->Session->read("auxn_numero_asiento");
	}else{
		$num_aux = $this->Session->read("aux_numero_asiento");
	}

if($mes!=''){
	$ano=$this->ano_ejecucion();
	$v=$this->ccfd05_numero_asiento->execute("SELECT numero_asiento FROM ccfd05_numero_asiento WHERE ".$this->SQLCA()." and ano_asiento=".$ano." and mes_asiento=".$mes." ORDER BY numero_asiento DESC");
	if($v!=null){
		//$numero = $numero =="" ? 1 : $numero+1;
		$numero=$v[0][0]["numero_asiento"];
		if($numero==""){
			$numero = 1;
		}else{
			if($mes==1 && $numero<3){
				$numero = 2;
			}else{
				$numero = $numero+1;
			}
		}
		$sql="UPDATE ccfd05_numero_asiento SET numero_asiento=".$numero." WHERE ".$this->SQLCA()." AND ano_asiento=".$ano." AND mes_asiento=".$mes.";";
	}else{
		if($mes==1){
			$numero=2;
		}else{
			$numero=1;
		}
		$sql = "INSERT INTO ccfd05_numero_asiento VALUES('$cod1', '$cod2', '$cod3', '$cod4', '$cod5', '$ano', '$mes', '$numero');";
	}

	$sw1 = $this->ccfd05_numero_asiento->execute($sql);
	if($sw1>1){
		$this->Session->write("snumero_asiento",$numero);
		$this->Session->write("auxn_numero_asiento",$numero);
		$this->Session->write("smes_asiento",$mes);
		$this->Session->write("auxn_mes_asiento",$mes);
	}

	$this->set("numero",$numero);
	$this->salir_asiento($ano,$mes_aux,$num_aux);

}else{
	$this->set("numero",'vacio');
}

}//fin numero_asiento


function mostrar($var=null){
	$this->layout="ajax";
	switch ($var){
		case '1':
			$this->set('name','CHEQUE');
		break;
		case '2':
			$this->set('name','DEPOSITO');
		break;
		case '3':
			$this->set('name','NOTA DE CREDITO');
		break;
		case '4':
			$this->set('name','NOTA DE DEBITO');
		break;
		case '5':
			$this->set('name','ORDEN DE COMPRA');
		break;
		case '6':
			$this->set('name','OTROS COMPROMISOS');
		break;
		case '7':
			$this->set('name','CONTRATO DE OBRAS');
		break;
		case '8':
			$this->set('name','CONTRATO DE SERVICIOS');
		break;
		case '9':
			$this->set('name','ORDEN DE PAGO');
		break;
		case '10':
		    $this->set('name','Retención de i.v.a');
		break;
		case '11':
			$this->set('name','Retención de i.s.l.r');
		break;
		case '12':
			$this->set('name','Retención de timbre');
		break;
		case '13':
			$this->set('name','Retención de impuesto municipal');
		break;
		case '14':
			$this->set('name','Retención de multa');
		break;
		case '15':
			$this->set('name','Retención de responsabilidad');
		break;
		case '16':
			$this->set('name','Bienes muebles');
		break;
		case '17':
			$this->set('name','Bienes inmueble');
		break;
		case '18':
			$this->set('name','Reintegros');
		break;
		case '19':
			$this->set('name','Rendiciones');
		break;
		case '99':
			$this->set('name','Asiento de Apertura');
		break;

	}
}// fin mostrar



  function select3($select=null,$var=null) {
 	$this->layout = "ajax";
 	if($var!=null){
 	$cond= $this->condicionNDEP()." and cod_dep=1";
 	switch($select){
 		case 'tipo':
					 $this->set('SELECT','contable');//El parametro que se le pasa para que busque el proximo select (cuando entre en select3)
					 $this->set('codigo','tipo_cuenta');
					 $this->set('seleccion','');
					 $this->set('n',1);
					 $this->Session->write('radio',$var);
					 $lista = $this->ccfd01_tipo->generateList($cond, 'cod_tipo_cuenta ASC', null, '{n}.ccfd01_tipo.cod_tipo_cuenta', '{n}.ccfd01_tipo.denominacion');
			   		 $this->concatena_sin_cero($lista, 'vector');
			   		 $this->set('selected',null);
			   		 echo "<script>";
						 echo "document.getElementById('st_select_2').innerHTML='<select></select>';";
						 echo "document.getElementById('st_select_3').innerHTML='<select></select>';";
						 echo "document.getElementById('st_select_4').innerHTML='<select></select>';";
						 echo "document.getElementById('st_select_5').innerHTML='<select></select>';";
					 echo "</script>";
			  break;
		case 'contable':
					 $this->set('SELECT','subcuenta');//El parametro que se le pasa para que busque el proximo select (cuando entre en select3)
					 $this->set('codigo','contable');
					 $this->set('seleccion','');
					 $this->set('n',2);
					 $this->Session->write('tipo',$var);
					 $cond.=" and cod_tipo_cuenta=".$var;
					 $lista = $this->ccfd01_cuenta->generateList($cond, 'cod_cuenta ASC', null, '{n}.ccfd01_cuenta.cod_cuenta', '{n}.ccfd01_cuenta.denominacion');
			   		 $this->concatena($lista, 'vector');
			   		 $tipo1=$this->Session->read('radio');
			   		 if($lista){
			   		 		$this->set('selected',null);
			          	 	$this->concatena($lista, 'vector');
							echo "<script>";
								echo "document.getElementById('agregar').disabled='disabled';";
							echo "</script>";
			   		 }else{
			   		 	$this->set('selected',1);
			   		 	$lista=array('1'=>'000');
			   		 	echo "<script>";
								echo "document.getElementById('agregar').disabled=false;";
						echo "</script>";
			   		 	if($tipo1==1){
								echo "<script>";
									echo "document.getElementById('debe').readOnly=false;";
									echo "document.getElementById('haber').value='';";
									echo "document.getElementById('haber').readOnly=true;";
								echo "</script>";
						}else{
								echo "<script>";
									echo "document.getElementById('haber').readOnly=false;";
									echo "document.getElementById('debe').value='';";
									echo "document.getElementById('debe').readOnly=true;";
								echo "</script>";
						}
				   	}
			  break;
		case 'subcuenta':
					 $this->set('SELECT','div_contable');
					 $this->set('codigo','subcuenta');
					 $this->set('seleccion','');
					 $this->set('n',3);
					 $this->Session->write('cuenta',$var);
					 $tipo=$this->Session->read('tipo');
					 $cond.=" and cod_tipo_cuenta=".$tipo." and cod_cuenta=".$var;
					 $lista=  $this->ccfd01_subcuenta->generateList($cond, 'cod_subcuenta ASC', null, '{n}.ccfd01_subcuenta.cod_subcuenta', '{n}.ccfd01_subcuenta.denominacion');

			          $tipo1=$this->Session->read('radio');
			          if($lista){
			          	 	$this->concatena_tres_digitos($lista, 'vector');
							echo "<script>";
								echo "document.getElementById('st_select_4').innerHTML='<select></select>';";
			   		 			echo "document.getElementById('st_select_5').innerHTML='<select></select>';";
								echo "document.getElementById('agregar').disabled='disabled';";
							echo "</script>";
			   		 }else{
			   		 	echo "<script>";
			   		 			echo "document.getElementById('st_select_3').innerHTML='<select><option value=000>000</option></select>';";
			   		 			echo "document.getElementById('st_select_4').innerHTML='<select><option value=0000>0000</option></select>';";
			   		 			echo "document.getElementById('st_select_5').innerHTML='<select><option value=000>000</option></select>';";
								echo "document.getElementById('agregar').disabled=false;";
						echo "</script>";
			   		 	if($tipo1==1){
								echo "<script>";
									echo "document.getElementById('debe').readOnly=false;";
									echo "document.getElementById('haber').value='';";
									echo "document.getElementById('haber').readOnly=true;";
								echo "</script>";
						}else{
								echo "<script>";
									echo "document.getElementById('haber').readOnly=false;";
									echo "document.getElementById('debe').value='';";
									echo "document.getElementById('debe').readOnly=true;";
								echo "</script>";
						}
				   	}
			    break;
	     case 'div_contable':
				  		$this->set('SELECT','subdiv_estadistica_contable');
				  		$this->set('codigo','div_estadistica_contable');
				  		$this->set('seleccion','');
				  		$this->set('n',4);
				  		$this->Session->write('subcuenta',$var);
				  		$tipo=$this->Session->read('tipo');
				  		$cuenta=$this->Session->read('cuenta');
				  		$cond.=" and cod_tipo_cuenta=".$tipo." and cod_cuenta=".$cuenta." and cod_subcuenta=".$var;
				  		$lista = $this->ccfd01_division->generateList($cond, 'cod_division ASC', null, '{n}.ccfd01_division.cod_division', '{n}.ccfd01_division.denominacion');
		   		  		 $tipo1=$this->Session->read('radio');
		   		  		 if($lista){
			          	 	$this->concatena_cuatro_digitos($lista, 'vector');
							echo "<script>";
							echo "document.getElementById('st_select_5').innerHTML='<select></select>';";
								echo "document.getElementById('agregar').disabled='disabled';";
							echo "</script>";
			   		 }else{
			   		 	echo "<script>";
			   		 			echo "document.getElementById('st_select_4').innerHTML='<select><option value=0000>0000</option></select>';";
			   		 			echo "document.getElementById('st_select_5').innerHTML='<select><option value=000>000</option></select>';";
								echo "document.getElementById('agregar').disabled=false;";
						echo "</script>";
			   		 	if($tipo1==1){
								echo "<script>";
									echo "document.getElementById('debe').readOnly=false;";
									echo "document.getElementById('haber').value='';";
									echo "document.getElementById('haber').readOnly=true;";
								echo "</script>";
						}else{
								echo "<script>";
									echo "document.getElementById('haber').readOnly=false;";
									echo "document.getElementById('debe').value='';";
									echo "document.getElementById('debe').readOnly=true;";
								echo "</script>";
						}
				   	}
			  		break;
		 case 'subdiv_estadistica_contable':
				  		$this->set('SELECT','direccion');
				  		$this->set('codigo','subdiv_estadistica_contable');
				  		$this->set('seleccion','');
				  		$this->set('no','no');
				  		$this->set('n',5);
				  		$tipo=$this->Session->read('tipo');
				  		$cuenta=$this->Session->read('cuenta');
				  		$subcuenta=$this->Session->read('subcuenta');
				  		$cond.=" and cod_tipo_cuenta=".$tipo." and cod_cuenta=".$cuenta." and cod_subcuenta=".$subcuenta." and cod_division=".$var;
				  		$lista = $this->ccfd01_subdivision->generateList($cond, 'cod_division ASC', null, '{n}.ccfd01_subdivision.cod_subdivision', '{n}.ccfd01_subdivision.denominacion');
		   		  		 $tipo1=$this->Session->read('radio');
		   		  		 if($lista){
		   		  		 	$this->set('selected',null);
			          	 	$this->concatena_tres_digitos($lista, 'vector');
							echo "<script>";
								echo "document.getElementById('agregar').disabled='disabled';";
							echo "</script>";
			   		 }else{
			   		 	$this->set('selected',1);
			   		 	 $lista=array('1'=>'000');
			   		 	  $this->set('vector',$lista);
			   		 	echo "<script>";
			   		 			echo "document.getElementById('st_select_5').innerHTML='<select><option value=000>000</option></select>';";
								echo "document.getElementById('agregar').disabled=false;";
						echo "</script>";
			   		 	if($tipo1==1){
								echo "<script>";
									echo "document.getElementById('debe').readOnly=false;";
									echo "document.getElementById('haber').value='';";
									echo "document.getElementById('haber').readOnly=true;";
								echo "</script>";
						}else{
								echo "<script>";
									echo "document.getElementById('haber').readOnly=false;";
									echo "document.getElementById('debe').value='';";
									echo "document.getElementById('debe').readOnly=true;";
								echo "</script>";
						}
				   	}
			  		break;
	}//fin switch
	}else{
		  $this->set('SELECT','');
		  $this->set('codigo','');
		  $this->set('seleccion','');
		  $this->set('n',10);
		  $this->set('no','no');
		  $this->set('vector','');
	}
 }//fin function select3



function valida($var=null){
	$this->layout = "ajax";
	$tipo=$this->Session->read('radio');
	echo "<script>";
			echo "document.getElementById('agregar').disabled=false;";
		echo "</script>";
	if($tipo==1){
		echo "<script>";
			echo "document.getElementById('debe').readOnly=false;";
			echo "document.getElementById('haber').value='';";
			echo "document.getElementById('haber').readOnly=true;";
		echo "</script>";
	}else{
		echo "<script>";
			echo "document.getElementById('haber').readOnly=false;";
			echo "document.getElementById('debe').value='';";
			echo "document.getElementById('debe').readOnly=true;";
		echo "</script>";
	}
}// fin valida



function m2($var=null){
	switch (strlen($var)){
		case 1:
			return '0'.$var;
		break;
		case 2:
			return $var;
		break;
		case 3:
			return $var;
		break;
		case 4:
			return $var;
		break;
	}
}// fin m4


function m3($var=null){
	switch (strlen($var)){
		case 1:
			return '00'.$var;
		break;
		case 2:
			return '0'.$var;
		break;
		case 3:
			return $var;
		break;
	}
}// fin m4


function m4($var=null){
	switch (strlen($var)){
		case 1:
			return '000'.$var;
		break;
		case 2:
			return '00'.$var;
		break;
		case 3:
			return '0'.$var;
		break;
		case 4:
			return $var;
		break;
	}
}// fin m4



function agregar_grilla($var=null,$existe=null) {
	$this->layout="ajax";

 	$num_asiento=$this->data['cnmp09']['num_asiento'];
 	$dia=$this->data['cnmp09']['dia_day'];
 	$mes=$this->data['cnmp09']['mes'];
 	$ano=$this->data['cnmp09']['ano'];
 	$tipo_documento=$this->data['cnmp09']['tipo_documento'];
 	$numero=$this->data['cnmp09']['numero'];
 	$fecha1=$this->data['cnmp09']['fecha'];
 	$fecha=$this->Cfecha($fecha1,'A-M-D');
 	$concepto=$this->data['cnmp09']['concepto'];

	if(isset($this->data['cnmp09']['asientos'])){
 		if($this->data['cnmp09']['asientos']==2){
 			$instancia=1;
 		}else{
 			$instancia=4;
 		}
	}else{$instancia='';}

	$cod1=$this->verifica_SS(1);
	$cod2=$this->verifica_SS(2);
	$cod3=$this->verifica_SS(3);
	$cod4=$this->verifica_SS(4);
	$cod5=$this->verifica_SS(5);
	$linea=$this->data['cnmp09']['linea'];
	$radio=$this->data['cnmp09']['radio_tipo'];
	$tipo=$this->data['cnmp09']['cod_tipo_cuenta'];


	if(isset($this->data['cnmp09']['cod_contable']) && !empty($this->data['cnmp09']['cod_contable'])){
		$cuenta=$this->data['cnmp09']['cod_contable'];
	}else{
		$cuenta=0;
	}
//////////////////////////////////////////////////////////////////////////////////////////////////
	if(isset($this->data['cnmp09']['cod_subcuenta']) && !empty($this->data['cnmp09']['cod_subcuenta'])){
		$subcuenta=$this->data['cnmp09']['cod_subcuenta'];
	}else{
		$subcuenta=0;
	}
/////////////////////////////////////////////////////////////////////////////////////////////////
	if(isset($this->data['cnmp09']['cod_div_estadistica_contable']) && !empty($this->data['cnmp09']['cod_div_estadistica_contable'])){
		$division=$this->data['cnmp09']['cod_div_estadistica_contable'];
	}else{
		$division=0;
	}
////////////////////////////////////////////////////////////////////////////////////////////////
	if(isset($this->data['cnmp09']['cod_subdiv_estadistica_contable']) && !empty($this->data['cnmp09']['cod_subdiv_estadistica_contable'])){
		$subdivision=$this->data['cnmp09']['cod_subdiv_estadistica_contable'];
	}else{
		$subdivision=0;
	}
///////////////////////////////////////////////////////////////////////////////////////////////

	if(isset($this->data['cnmp09']['num_prox_line']) && !empty($this->data['cnmp09']['num_prox_line'])){
		$num_prox_line = $this->data['cnmp09']['num_prox_line'];
	}else{
		$num_prox_line=1;
	}


	if($radio==1){
		$monto=$this->Formato1($this->data['cnmp09']['debe']);
	}else{
		$monto=$this->Formato1($this->data['cnmp09']['haber']);
	}
	if($monto==0){
		$this->set('num_prox_line',$num_prox_line);
		$this->set('errorMessage', 'ingrese un monto valido');
		if(!isset($_SESSION["contador"])){
 			$this->set('vacio','');
 		}
		return;
	}

	/* if(isset($_SESSION["contador"])){
        $_SESSION["contador"]=$_SESSION["contador"]+1;
	}else{
		$_SESSION["contador"]=1;
	} */

	if(isset($var) && !empty($var)){

			$cod[0]=$linea;
			$cod[1]=$radio;
			$cod[2]=$tipo;
			$cod[3]=$cuenta;
			$cod[4]=$subcuenta;
			$cod[5]=$division;
			$cod[6]=$subdivision;
			$cod[7]=$monto;

		    /* if(isset($_SESSION["i"])){
				$i=$this->Session->read("i")+1;
				$this->Session->write("i",$i);
	   		}else{
			   $this->Session->write("i",0);
				$i=0;
			} */

		$i=0;
        switch($var){
        	case 'normal':
					 $vec[$i][0]=$linea;
					 $vec[$i][1]=$radio;
					 $vec[$i][2]=$tipo;
					 $vec[$i][3]=$this->m2($cuenta);
					 $vec[$i][4]=$this->m3($subcuenta);
					 $vec[$i][5]=$this->m4($division);
					 $vec[$i][6]=$this->m3($subdivision);
					 $vec[$i][7]=$monto;
					 $vec[$i][8]=$num_prox_line;
					 $vec[$i]["id"]=$i;
					 if(isset($_SESSION["items1"])){
						foreach($_SESSION["items1"] as $codi){
            	           if($codi[1]==$cod[1] && $codi[2]==$cod[2] && $codi[3]==$cod[3] && $codi[4]==$cod[4] && $codi[5]==$cod[5] && $codi[6]==$cod[6]){
                              $est=true;
                              break;
            	          }else{
            	          	 $est=false;
            	          }
                        }//fin foreach
                        if($est==true){
            	          	//$i=$this->Session->read("i")-1;
				            //$this->Session->write("i",$i);
				            $this->set('num_prox_line',$num_prox_line);
				            $this->set('errorMessage', 'Los codigos seleccionados ya existen en la lista');
                        }else{

				    	if($vec!=null){
							$sql_insert = "INSERT INTO ccfd10_detalles_tmp VALUES('$cod1', '$cod2', '$cod3', '$cod4', '$cod5', '$ano', '$mes','$dia','$num_asiento', '".$vec[$i][8]."', '".$vec[$i][1]."','".$vec[$i][2]."', '".$vec[$i][3]."', '".$vec[$i][4]."', '".$vec[$i][5]."', '".$vec[$i][6]."', '".$vec[$i][7]."'); ";
							$sw = $this->ccfd10_detalles->execute($sql_insert);
							if($sw>1){


		$sql_det = "SELECT * FROM ccfd10_detalles_tmp WHERE ".$this->SQLCA()." and ano_asiento=".$ano." and mes_asiento=".$mes." and dia_asiento=".$dia." and numero_asiento=".$num_asiento." ORDER BY numero_linea ASC;";
		$sw_det = $this->ccfd10_detalles->execute($sql_det);
		if(!empty($sw_det)){
			$i=0;
			$monto_total=0;
			$_SESSION["items1"] = array();
			foreach($sw_det as $irows){
				$monto_total=$monto_total+$irows[0]['monto'];
				$_SESSION["items1"][$i][0] = $i+1;
			 	$_SESSION["items1"][$i][1] = $irows[0]['debito_credito'];
			 	$_SESSION["items1"][$i][2] = $irows[0]['cod_tipo_cuenta'];
			 	$_SESSION["items1"][$i][3] = $this->m2($irows[0]['cod_cuenta']);
			 	$_SESSION["items1"][$i][4] = $this->m3($irows[0]['cod_subcuenta']);
			 	$_SESSION["items1"][$i][5] = $this->m4($irows[0]['cod_division']);
			 	$_SESSION["items1"][$i][6] = $this->m3($irows[0]['cod_subdivision']);
			 	$_SESSION["items1"][$i][7] = $irows[0]['monto'];
			 	$_SESSION["items1"][$i][8] = $irows[0]['numero_linea'];
			 	$_SESSION["items1"][$i]["id"] = $i;
			 	$i++;
			}
			$this->Session->write("i",$i);
			$_SESSION["contador"] = $i+1;
			$this->set('num_prox_line',$irows[0]['numero_linea']+1);
			$this->set('cont_linea',$i+1);
			$this->set('total_partidas_rc',$monto_total);

		}else{
			$this->limpiar_lista(2);
			$this->set('num_prox_line','');
			$this->set('monto1',0);
			$this->set('monto2',0);
			$this->set('total_partidas_rc',0);
		}


								//$_SESSION["items1"]=$_SESSION["items1"]+$vec;
								//$num_prox_line += 1;
							}else{
								echo "<script> fun_msj('No se logro agregar intente nuevamente'); </script>";
							}
				    	}
                        }
					 }else{

				    	if($vec!=null){
				    		if($existe=="no"){
								$sql2 = " INSERT INTO ccfd10_descripcion_tmp VALUES('$cod1', '$cod2', '$cod3', '$cod4', '$cod5', '$ano', '$mes', '$dia','$num_asiento','$instancia','".$concepto."','$tipo_documento','$numero','$fecha');";
								$sw2 = $this->ccfd10_descripcion->execute($sql2);
				    		}else{$sw2=2;}
							if($sw2>1){
								$sql_insert = "INSERT INTO ccfd10_detalles_tmp VALUES('$cod1', '$cod2', '$cod3', '$cod4', '$cod5', '$ano', '$mes','$dia','$num_asiento', '".$vec[$i][8]."', '".$vec[$i][1]."','".$vec[$i][2]."', '".$vec[$i][3]."', '".$vec[$i][4]."', '".$vec[$i][5]."', '".$vec[$i][6]."', '".$vec[$i][7]."'); ";
								$sw = $this->ccfd10_detalles->execute($sql_insert);

 								echo "<script>
 										document.getElementById('dia').setAttribute('onFocus', 'this.blur()');
										document.getElementById('mes').setAttribute('onFocus', 'this.blur()');
										document.getElementById('select').setAttribute('onFocus', 'this.blur()');
										document.getElementById('asientos_1').disabled=true;
										document.getElementById('asientos_2').disabled=true;
 									</script>";

								if($sw>1){


		$sql_det = "SELECT * FROM ccfd10_detalles_tmp WHERE ".$this->SQLCA()." and ano_asiento=".$ano." and mes_asiento=".$mes." and dia_asiento=".$dia." and numero_asiento=".$num_asiento." ORDER BY numero_linea ASC;";
		$sw_det = $this->ccfd10_detalles->execute($sql_det);
		if(!empty($sw_det)){
			$i=0;
			$monto_total=0;
			$_SESSION["items1"] = array();
			foreach($sw_det as $irows){
				$monto_total=$monto_total+$irows[0]['monto'];
				$_SESSION["items1"][$i][0] = $i+1;
			 	$_SESSION["items1"][$i][1] = $irows[0]['debito_credito'];
			 	$_SESSION["items1"][$i][2] = $irows[0]['cod_tipo_cuenta'];
			 	$_SESSION["items1"][$i][3] = $this->m2($irows[0]['cod_cuenta']);
			 	$_SESSION["items1"][$i][4] = $this->m3($irows[0]['cod_subcuenta']);
			 	$_SESSION["items1"][$i][5] = $this->m4($irows[0]['cod_division']);
			 	$_SESSION["items1"][$i][6] = $this->m3($irows[0]['cod_subdivision']);
			 	$_SESSION["items1"][$i][7] = $irows[0]['monto'];
			 	$_SESSION["items1"][$i][8] = $irows[0]['numero_linea'];
			 	$_SESSION["items1"][$i]["id"] = $i;
			 	$i++;
			}
			$this->Session->write("i",$i);
			$_SESSION["contador"] = $i+1;
			$this->set('num_prox_line',$irows[0]['numero_linea']+1);
			$this->set('cont_linea',$i+1);
			$this->set('total_partidas_rc',$monto_total);

		}else{
			$this->limpiar_lista(2);
			$this->set('num_prox_line','');
			$this->set('monto1',0);
			$this->set('monto2',0);
			$this->set('total_partidas_rc',0);
		}


									//$_SESSION["items1"]=$vec;
									//$num_prox_line += 1;
									echo "<script> document.getElementById('num_asiento').readOnly=true; </script>";
								}else{
									echo "<script> fun_msj('No se logro agregar intente nuevamente'); </script>";
								}
							}else{
								echo "<script> fun_msj('No se logro registrar la descripci&oacute;n intente nuevamente'); </script>";
							}
				    	}
					 }

        	break;

        }//fin switch
		}

	echo'<script>';
		echo "document.getElementById('agregar').disabled='disabled';";
		echo "document.getElementById('save').disabled=false;";
		echo "document.getElementById('debe').readOnly=true;";
		echo "document.getElementById('haber').readOnly=true;";
	  	/* echo "if(document.getElementById('select_2'))document.getElementById('select_2').options[1].selected = true; ";
        echo "if(document.getElementById('st_select_3')) document.getElementById('st_select_3').innerHTML='<select></select>';  ";
        echo "if(document.getElementById('st_select_4')) document.getElementById('st_select_4').innerHTML='<select></select>';  ";
		echo "if(document.getElementById('st_select_5')) document.getElementById('st_select_5').innerHTML='<select></select>';  "; */
 	echo'</script>';

}//fin funcion



function limpiar_lista ($pa=null) {
	$this->layout = "ajax";

	$ano=$this->ano_ejecucion();
	$numero_asi = $this->Session->read("snumero_asiento");
	if($numero_asi!=''){
		$comp_num_asi = " and numero_asiento=".$numero_asi;
	}else{$comp_num_asi="";}
	$sql_desc = "SELECT ano_asiento, mes_asiento, dia_asiento, numero_asiento FROM ccfd10_descripcion_tmp WHERE ".$this->SQLCA()." and ano_asiento=".$ano.$comp_num_asi." ORDER BY numero_asiento ASC LIMIT 1;";
	$sw_desc = $this->ccfd10_descripcion->execute($sql_desc);
	if(!empty($sw_desc)){
		$sql_del1 = "BEGIN; DELETE FROM ccfd10_detalles_tmp WHERE ".$this->SQLCA()." and ano_asiento=".$sw_desc[0][0]["ano_asiento"]." and mes_asiento=".$sw_desc[0][0]["mes_asiento"]." and dia_asiento=".$sw_desc[0][0]["dia_asiento"]." and numero_asiento=".$sw_desc[0][0]["numero_asiento"].";";
		$wd1 = $this->ccfd10_detalles->execute($sql_del1);
	}

	if($wd1 >= 1){

		$sql_del1 = "DELETE FROM ccfd10_descripcion_tmp WHERE ".$this->SQLCA()." and ano_asiento=".$ano.$comp_num_asi.";";
		$wd2 = $this->ccfd10_descripcion->execute($sql_del1);
		if($wd2>1){
			$this->ccfd10_descripcion->execute("COMMIT;");
			if($pa==2){
				$this->continuar();
				$this->render('continuar');
			}
		}else{
			$this->ccfd10_descripcion->execute("ROLLBACK;");
		}
		$this->Session->delete("items1");
		$this->Session->delete("i");
		$this->Session->delete("contador");
		echo "<script>";
   			//echo "document.getElementById('save').disabled='disabled';";
   			echo "document.getElementById('linea').value='1';";
   			echo "document.getElementById('num_prox_line').value='1';";
   		echo "</script>";
	}
}




function eliminar_items ($id,$lin) {
	$this->layout = "ajax";

	$sw_deli=0;
	$ano=$this->ano_ejecucion();
	$numero_asi = $this->Session->read("snumero_asiento");
	if($numero_asi!=''){
		$comp_num_asi = " and numero_asiento=".$numero_asi;
	}else{$comp_num_asi="";}
	$sql_desc = "SELECT ano_asiento, mes_asiento, dia_asiento, numero_asiento FROM ccfd10_descripcion_tmp WHERE ".$this->SQLCA()." and ano_asiento=".$ano.$comp_num_asi." ORDER BY numero_asiento ASC LIMIT 1;";
	$sw_desc = $this->ccfd10_descripcion->execute($sql_desc);
	if(!empty($sw_desc)){
		$sql_deli = "DELETE FROM ccfd10_detalles_tmp WHERE ".$this->SQLCA()." and ano_asiento=".$sw_desc[0][0]["ano_asiento"]." and mes_asiento=".$sw_desc[0][0]["mes_asiento"]." and dia_asiento=".$sw_desc[0][0]["dia_asiento"]." and numero_asiento=".$sw_desc[0][0]["numero_asiento"]." and numero_linea=".$lin;
		$sw_deli = $this->ccfd10_detalles->execute($sql_deli);
	}

	if($sw_deli>=1){

		$sql_det = "SELECT * FROM ccfd10_detalles_tmp WHERE ".$this->SQLCA()." and ano_asiento=".$sw_desc[0][0]["ano_asiento"]." and mes_asiento=".$sw_desc[0][0]["mes_asiento"]." and dia_asiento=".$sw_desc[0][0]["dia_asiento"]." and numero_asiento=".$sw_desc[0][0]["numero_asiento"]." ORDER BY numero_linea ASC;";
		$sw_det = $this->ccfd10_detalles->execute($sql_det);
		if(!empty($sw_det)){
			$i=0;
			$monto_total=0;
			$_SESSION["items1"] = array();
			foreach($sw_det as $irows){
				$monto_total=$monto_total+$irows[0]['monto'];
				$_SESSION["items1"][$i][0] = $i+1;
			 	$_SESSION["items1"][$i][1] = $irows[0]['debito_credito'];
			 	$_SESSION["items1"][$i][2] = $irows[0]['cod_tipo_cuenta'];
			 	$_SESSION["items1"][$i][3] = $this->m2($irows[0]['cod_cuenta']);
			 	$_SESSION["items1"][$i][4] = $this->m3($irows[0]['cod_subcuenta']);
			 	$_SESSION["items1"][$i][5] = $this->m4($irows[0]['cod_division']);
			 	$_SESSION["items1"][$i][6] = $this->m3($irows[0]['cod_subdivision']);
			 	$_SESSION["items1"][$i][7] = $irows[0]['monto'];
			 	$_SESSION["items1"][$i][8] = $irows[0]['numero_linea'];
			 	$_SESSION["items1"][$i]["id"] = $i;
			 	$i++;
			}
			$this->Session->write("i",$i);
			$_SESSION["contador"] = $i+1;
			$this->set('num_prox_line',$irows[0]['numero_linea']+1);
			$this->set('cont_linea',$i+1);
			$this->set('total_partidas_rc',$monto_total);

		}else{
			$this->limpiar_lista(2);
			$this->set('monto1',0);
			$this->set('monto2',0);
			$this->set('total_partidas_rc',0);
		}




	}else{

		$sql_det = "SELECT * FROM ccfd10_detalles_tmp WHERE ".$this->SQLCA()." and ano_asiento=".$ano.$comp_num_asi." ORDER BY numero_linea ASC;";
		$sw_det = $this->ccfd10_detalles->execute($sql_det);
		if(!empty($sw_det)){
			$i=0;
			$monto_total=0;
			$_SESSION["items1"] = array();
			foreach($sw_det as $irows){
				$monto_total=$monto_total+$irows[0]['monto'];
				$_SESSION["items1"][$i][0] = $i+1;
			 	$_SESSION["items1"][$i][1] = $irows[0]['debito_credito'];
			 	$_SESSION["items1"][$i][2] = $irows[0]['cod_tipo_cuenta'];
			 	$_SESSION["items1"][$i][3] = $this->m2($irows[0]['cod_cuenta']);
			 	$_SESSION["items1"][$i][4] = $this->m3($irows[0]['cod_subcuenta']);
			 	$_SESSION["items1"][$i][5] = $this->m4($irows[0]['cod_division']);
			 	$_SESSION["items1"][$i][6] = $this->m3($irows[0]['cod_subdivision']);
			 	$_SESSION["items1"][$i][7] = $irows[0]['monto'];
			 	$_SESSION["items1"][$i][8] = $irows[0]['numero_linea'];
			 	$_SESSION["items1"][$i]["id"] = $i;
			 	$i++;
			}
			$this->Session->write("i",$i);
			$_SESSION["contador"] = $i+1;
			$this->set('num_prox_line',$irows[0]['numero_linea']+1);
			$this->set('cont_linea',$i+1);
			$this->set('total_partidas_rc',$monto_total);

		}else{
			$this->limpiar_lista(2);
			$this->set('monto1',0);
			$this->set('monto2',0);
			$this->set('total_partidas_rc',0);
		}

		echo "<script> fun_msj('No se logro eliminar intente nuevamente'); </script>";

	}
}


function editar_monto ($id,$lin,$tipo_m) {
	$this->layout = "ajax";
	$ano=$this->ano_ejecucion();
	$numero_asi = $this->Session->read("snumero_asiento");
	if($numero_asi!=''){
		$comp_num_asi = " and numero_asiento=".$numero_asi;
	}else{$comp_num_asi="";}

	$sql_desc = "SELECT ano_asiento, mes_asiento, dia_asiento, numero_asiento FROM ccfd10_descripcion_tmp WHERE ".$this->SQLCA()." and ano_asiento=".$ano.$comp_num_asi." ORDER BY numero_asiento ASC LIMIT 1;";
	$sw_desc = $this->ccfd10_descripcion->execute($sql_desc);

	if(!empty($sw_desc)){
		$sql_monto = "SELECT monto FROM ccfd10_detalles_tmp WHERE ".$this->SQLCA()." and ano_asiento=".$sw_desc[0][0]["ano_asiento"]." and mes_asiento=".$sw_desc[0][0]["mes_asiento"]." and dia_asiento=".$sw_desc[0][0]["dia_asiento"]." and numero_asiento=".$sw_desc[0][0]["numero_asiento"]." and numero_linea=".$lin;
		$sw_sel = $this->ccfd10_detalles->execute($sql_monto);
		$sw_sel!=null ? $this->set('monto',$sw_sel[0][0]['monto']) : $this->set('monto',0);
		$this->set('id',$id);
		$this->set('lin',$lin);
		$this->set('tipo_m',$tipo_m);
	}else{
		$this->set('monto',0);
		$this->set('id',$id);
		$this->set('lin',$lin);
		$this->set('tipo_m',$tipo_m);
	}
}

function guardar_monto ($id,$lin,$monto_ac,$tipo_m) {
	$this->layout = "ajax";
	$this->set('tipo_m',$tipo_m);
	$this->set('id',$id);
	$ano=$this->ano_ejecucion();
	$numero_asi = $this->Session->read("snumero_asiento");
	$monto = $this->Formato1($this->data["cnmp09"]["monto_dc_$id"]);
	if($numero_asi!=''){
		$comp_num_asi = " and numero_asiento=".$numero_asi;
	}else{$comp_num_asi="";}
	$sql_desc = "SELECT ano_asiento, mes_asiento, dia_asiento, numero_asiento FROM ccfd10_descripcion_tmp WHERE ".$this->SQLCA()." and ano_asiento=".$ano.$comp_num_asi." ORDER BY numero_asiento ASC LIMIT 1;";
	$sw_desc = $this->ccfd10_descripcion->execute($sql_desc);
	if(!empty($sw_desc)){
		$sql_up_monto = "UPDATE ccfd10_detalles_tmp SET monto=$monto WHERE ".$this->SQLCA()." and ano_asiento=".$sw_desc[0][0]["ano_asiento"]." and mes_asiento=".$sw_desc[0][0]["mes_asiento"]." and dia_asiento=".$sw_desc[0][0]["dia_asiento"]." and numero_asiento=".$sw_desc[0][0]["numero_asiento"]." and numero_linea=".$lin;
		$sw_upd = $this->ccfd10_detalles->execute($sql_up_monto);
	}

	if($sw_upd>1){
		$s_da = $this->ccfd10_detalles->execute("SELECT numero_linea, debito_credito, monto FROM ccfd10_detalles_tmp WHERE ".$this->SQLCA()." and ano_asiento=".$sw_desc[0][0]["ano_asiento"]." and mes_asiento=".$sw_desc[0][0]["mes_asiento"]." and dia_asiento=".$sw_desc[0][0]["dia_asiento"]." and numero_asiento=".$sw_desc[0][0]["numero_asiento"].";");
		$monto_total=0;
	foreach($s_da as $codigos){
       if($codigos[0]['debito_credito']==$tipo_m){
     		$monto_total += $this->Formato1($codigos[0]['monto']);
       }
	}

		$this->set('monto',$monto);
		$this->set('monto_total',$monto_total);

	$j=0;
	foreach($_SESSION ["items1"] as $codigos){
       if($codigos[$j]!=null && $codigos[1]==$tipo_m && $codigos[8]==$lin && $codigos['id']==$id){
			$_SESSION ["items1"][$j][7] = $monto;
			break;
       }
       $j++;
	}

		$this->set('Message_existe', 'El monto fue actualizado correctamente');
	}else{
		$this->set('tipo_m',$tipo_m);
		$this->set('id',$id);
		$this->set('monto',$monto_ac);
		$this->set('errorMessage', 'No se pudo actualizar el monto, intente nuevamente');
	}
}



function cancelar ($id,$lin,$monto_ac) {
	$this->layout = "ajax";
	$this->set('id',$id);
	$this->set('monto',$monto_ac);
}


 function guardar(){

  $this->layout="ajax";

  $cod_presi      = $this->Session->read('SScodpresi');
  $cod_entidad    = $this->Session->read('SScodentidad');
  $cod_tipo_inst  = $this->Session->read('SScodtipoinst');
  $cod_inst       = $this->Session->read('SScodinst');
  $cod_dep        = $this->Session->read('SScoddep');

 	$num_asiento=$this->data['cnmp09']['num_asiento'];
 	$dia=$this->data['cnmp09']['dia_day'];
 	$mes=$this->data['cnmp09']['mes'];
 	$ano=$this->data['cnmp09']['ano'];
 	$tipo_documento=$this->data['cnmp09']['tipo_documento'];
 	$numero=$this->data['cnmp09']['numero'];
 	$fecha1=$this->data['cnmp09']['fecha'];
 	$fecha=$this->Cfecha($fecha1,'A-M-D');
 	$concepto=$this->data['cnmp09']['concepto'];

	if(isset($this->data['cnmp09']['asientos'])){
	 	if($this->data['cnmp09']['asientos']==2){
 			$instancia_asi=1;//Asiento de Apertura
 		}else{
 			$instancia_asi=4;//Otros Asientos
 		}
 	}else if(isset($this->data['cnmp09']['instanc_asiento'])){
	 	if($this->data['cnmp09']['instanc_asiento']==2){
 			$instancia_asi=1;//Asiento de Apertura
 		}else{
 			$instancia_asi=4;//Otros Asientos
 		}
 	}

 	 $cod1=$this->verifica_SS(1);
	 $cod2=$this->verifica_SS(2);
	 $cod3=$this->verifica_SS(3);
	 $cod4=$this->verifica_SS(4);
	 $cod5=$this->verifica_SS(5);
	 $sum_monto1=0;
	 $sum_monto2=0;
	 $sum_monto1_t=0;
	 $sum_monto2_t=0;
	 $envi=0;


  if(isset($_SESSION ["items1"]) && $_SESSION ["items1"]!=null){

 	 	//////////////////////////Para verificar que el monto del debe y el haber son iguales o no/////////////////////////
			// CON EL PROGRAMA:

				foreach($_SESSION ["items1"] as $codigos){
					if($codigos[1]==1){$sum_monto1+=$codigos[7];}
					if($codigos[1]==2){$sum_monto2+=$codigos[7];}

					$sum_monto1 = $this->Formato2($sum_monto1);
					$sum_monto2 = $this->Formato2($sum_monto2);

					$sum_monto1 = $this->Formato1($sum_monto1);
					$sum_monto2 = $this->Formato1($sum_monto2);
				}

			// CON LA TABLA TMP BD:

		$sql_det = "SELECT * FROM ccfd10_detalles_tmp WHERE ".$this->SQLCA()." and ano_asiento=".$ano." and mes_asiento=".$mes." and dia_asiento=".$dia." and numero_asiento=".$num_asiento." ORDER BY numero_linea ASC;";
		$sw_det = $this->ccfd10_detalles->execute($sql_det);
		if(!empty($sw_det)){
			foreach($sw_det as $irows){
				if($irows[0]['debito_credito']==1){$sum_monto1_t+=$irows[0]['monto'];}
				else if($irows[0]['debito_credito']==2){$sum_monto2_t+=$irows[0]['monto'];}
			}

					$sum_monto1_t = $this->Formato2($sum_monto1_t);
					$sum_monto2_t = $this->Formato2($sum_monto2_t);

					$sum_monto1_t = $this->Formato1($sum_monto1_t);
					$sum_monto2_t = $this->Formato1($sum_monto2_t);

		}else{
	 		$sum_monto1_t=1;
	 		$sum_monto2_t=2;
		}

		if($sum_monto1==$sum_monto2 && $sum_monto1_t==$sum_monto2_t){

			$sql_desc = "SELECT * FROM ccfd10_descripcion_tmp WHERE ".$this->SQLCA()." and ano_asiento=".$ano." and mes_asiento=".$mes." and dia_asiento=".$dia." and numero_asiento=".$num_asiento." ORDER BY numero_asiento ASC LIMIT 1;";
			$sw_desc = $this->ccfd10_descripcion->execute($sql_desc);
			if(!empty($sw_desc)){
				$cod1_tmp=$sw_desc[0][0]["cod_presi"];
				$cod2_tmp=$sw_desc[0][0]["cod_entidad"];
				$cod3_tmp=$sw_desc[0][0]["cod_tipo_inst"];
				$cod4_tmp=$sw_desc[0][0]["cod_inst"];
				$cod5_tmp=$sw_desc[0][0]["cod_dep"];
				$ano_asiento_tmp=$sw_desc[0][0]["ano_asiento"];
				$mes_asiento_tmp=$sw_desc[0][0]["mes_asiento"];
				$dia_asiento_tmp=$sw_desc[0][0]["dia_asiento"];
				$numero_asiento_tmp=$sw_desc[0][0]["numero_asiento"];
				$instancia_asiento_tmp=$sw_desc[0][0]["instancia_asiento"];
				$concepto_tmp=$sw_desc[0][0]["concepto"];
				$tipo_documento_tmp=$sw_desc[0][0]["tipo_documento"];
				$numero_documento_tmp=$sw_desc[0][0]["numero_documento"];
				$fecha_documento_tmp=$sw_desc[0][0]["fecha_documento"];
				$instancia=$instancia_asiento_tmp;
			}else{
				$cod1_tmp=$cod1;
				$cod2_tmp=$cod2;
				$cod3_tmp=$cod3;
				$cod4_tmp=$cod4;
				$cod5_tmp=$cod5;
				$ano_asiento_tmp=$ano;
				$mes_asiento_tmp=$mes;
				$dia_asiento_tmp=$dia;
				$numero_asiento_tmp=$num_asiento;
				$instancia_asiento_tmp=$instancia_asi;
				$concepto_tmp='';
				$tipo_documento_tmp='';
				$numero_documento_tmp='';
				$fecha_documento_tmp='';
				$instancia=$instancia_asiento_tmp;
			}

						$sql2 = "BEGIN; INSERT INTO ccfd10_descripcion VALUES('$cod1_tmp', '$cod2_tmp', '$cod3_tmp', '$cod4_tmp', '$cod5_tmp', '$ano_asiento_tmp', '$mes_asiento_tmp', '$dia_asiento_tmp','$numero_asiento_tmp','$instancia_asiento_tmp','".$concepto_tmp."','$tipo_documento_tmp','$numero_documento_tmp','$fecha_documento_tmp');";
						$sw2 = $this->ccfd10_descripcion->execute($sql2);
						if($sw2>1){

							$sql_insert = "INSERT INTO ccfd10_detalles (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, ano_asiento, mes_asiento, dia_asiento, numero_asiento, numero_linea, debito_credito, cod_tipo_cuenta, cod_cuenta, cod_subcuenta, cod_division, cod_subdivision, monto) VALUES ";
							$ij = 1;
							foreach($sw_det as $irows){
								$mes_str = $this->fecha_str($irows[0]['mes_asiento']);
								$sql_ccfd02_campos = "";
								$RANDOM = rand();
								$cuenta_existe = $this->ccfd02->findCount($this->condicion()." and ano_fiscal='".$irows[0]['ano_asiento']."' and cod_tipo_cuenta='".$irows[0]['cod_tipo_cuenta']."' and cod_cuenta='".$irows[0]['cod_cuenta']."' and cod_subcuenta='".$irows[0]['cod_subcuenta']."' and cod_division='".$irows[0]['cod_division']."'  and cod_subdivision='".$irows[0]['cod_subdivision']."'  and ".$RANDOM." = ".$RANDOM."  ");

								if($irows[0]['debito_credito']==1){
									$campo_a = "debito_ene";
									$campo_b = "debito_".$mes_str;
								}else{
									$campo_a = "credito_ene";
									$campo_b = "credito_".$mes_str;
								}//fin else

								if($cuenta_existe==0){
									$sql_ccfd02_campos .= "  INSERT INTO ccfd02 (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, ano_fiscal, cod_tipo_cuenta, cod_cuenta, cod_subcuenta, cod_division, cod_subdivision, debito_acumulado, credito_acumulado, debito_ene, credito_ene, debito_feb, credito_feb, debito_mar, credito_mar, debito_abr, credito_abr, debito_may, credito_may, debito_jun, credito_jun, debito_jul, credito_jul, debito_ago, credito_ago, debito_sep, credito_sep, debito_oct, credito_oct, debito_nov, credito_nov, debito_dic, credito_dic) VALUES ('".$cod_presi."', '".$cod_entidad."', '".$cod_tipo_inst."', '".$cod_inst."', '".$cod_dep."', '".$irows[0]['ano_asiento']."', '".$irows[0]['cod_tipo_cuenta']."', '".$irows[0]['cod_cuenta']."', '".$irows[0]['cod_subcuenta']."', '".$irows[0]['cod_division']."', '".$irows[0]['cod_subdivision']."', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0'); ";
								}//fin if

								if($instancia==1){ // ASIENTO DE APERTURA::1
									$sql_ccfd02_campos .= "  UPDATE ccfd02 SET ".$campo_a." = ".$campo_a." + ".$irows[0]['monto']."   WHERE ".$this->condicion()." and ano_fiscal='".$irows[0]['ano_asiento']."' and cod_tipo_cuenta='".$irows[0]['cod_tipo_cuenta']."' and cod_cuenta='".$irows[0]['cod_cuenta']."' and cod_subcuenta='".$irows[0]['cod_subcuenta']."' and cod_division='".$irows[0]['cod_division']."'  and cod_subdivision='".$irows[0]['cod_subdivision']."' and ".$RANDOM." = ".$RANDOM.";    ";
								}else{ // OTROS ASIENTOS::4
									$sql_ccfd02_campos .= "  UPDATE ccfd02 SET ".$campo_b." = ".$campo_b." + ".$irows[0]['monto']."   WHERE ".$this->condicion()." and ano_fiscal='".$irows[0]['ano_asiento']."' and cod_tipo_cuenta='".$irows[0]['cod_tipo_cuenta']."' and cod_cuenta='".$irows[0]['cod_cuenta']."' and cod_subcuenta='".$irows[0]['cod_subcuenta']."' and cod_division='".$irows[0]['cod_division']."'  and cod_subdivision='".$irows[0]['cod_subdivision']."' and ".$RANDOM." = ".$RANDOM.";    ";
								}

								$sw = $this->ccfd10_detalles->execute($sql_ccfd02_campos);

								$valores_tmp[] = " ('".$irows[0]['cod_presi']."', '".$irows[0]['cod_entidad']."', '".$irows[0]['cod_tipo_inst']."', '".$irows[0]['cod_inst']."', '".$irows[0]['cod_dep']."', '".$irows[0]['ano_asiento']."','".$irows[0]['mes_asiento']."','".$irows[0]['dia_asiento']."','".$irows[0]['numero_asiento']."','".$ij."', '".$irows[0]['debito_credito']."', '".$irows[0]['cod_tipo_cuenta']."', '".$irows[0]['cod_cuenta']."','".$irows[0]['cod_subcuenta']."','".$irows[0]['cod_division']."','".$irows[0]['cod_subdivision']."','".$irows[0]['monto']."')";
								$ij++;
							}

							$sql_insert .= " ".implode(',', $valores_tmp).";";
							$sw = $this->ccfd10_detalles->execute($sql_insert);

					   		 	if($sw > 1){
					   		 		$envi=3;
									$sql_del1 = "DELETE FROM ccfd10_detalles_tmp WHERE ".$this->SQLCA()." and ano_asiento=".$ano." and mes_asiento=".$mes." and dia_asiento=".$dia." and numero_asiento=".$num_asiento.";";
									$sql_del2 = " DELETE FROM ccfd10_descripcion_tmp WHERE ".$this->SQLCA()." and ano_asiento=".$ano." and mes_asiento=".$mes." and dia_asiento=".$dia." and numero_asiento=".$num_asiento.";";
									$this->ccfd10_detalles->execute($sql_del1.$sql_del2);
					   		 		$sw1 = $this->ccfd05_numero_asiento->execute("COMMIT");
									$this->set('Message_existe', 'LOS DATOS FUERON GUARDADOS');

 									/* echo "<script>
        									if(document.getElementById('st_select_1')) document.getElementById('st_select_1').innerHTML='<select></select>';
        									if(document.getElementById('st_select_2')) document.getElementById('st_select_2').innerHTML='<select></select>';
        									if(document.getElementById('st_select_3')) document.getElementById('st_select_3').innerHTML='<select></select>';
        									if(document.getElementById('st_select_4')) document.getElementById('st_select_4').innerHTML='<select></select>';
        									if(document.getElementById('st_select_5')) document.getElementById('st_select_5').innerHTML='<select></select>';
											document.getElementById('radio_tipo_1').disabled=true;
											document.getElementById('radio_tipo_2').disabled=true;
											document.getElementById('save').disabled=true;
 										</script>"; */

								}else{
									$this->ccfd10_detalles->execute("ROLLBACK");
									$this->set('errorMessage', 'POR FAVOR INTENTE REGISTRAR NUEVAMENTE');
								}
						}else{
							$sw1 = $this->ccfd10_descripcion->execute("ROLLBACK");
							$this->set('errorMessage', 'POR FAVOR INTENTE REGISTRAR NUEVAMENTE');
						}

 	 }else{
 	 	$this->set('errorMessage', 'No podra ser registrado ya que el monto del debe y el haber no coinciden');
 	 }//fin diferencia de montos
}else{
	$this->set('errorMessage', 'debe agregar datos que ingresar');
}

	if($envi==3){
		$this->continuar();
		$this->render('continuar');
	}else{
		$this->index("no");
		$this->render('index');
	}
}// fin guardar



function continuar(){

	$this->layout="ajax";

}//fin function


function tipo_busqueda($var=null){


$this->layout="ajax";

$this->set('opcion', $var);

}//fin function





function consultar_form(){

$this->layout="ajax";

 $this->set('ano_ejecucion',   $this->ano_ejecucion());
$this->set('consolidado', 1);
$this->render("consultar");

}//fin function




function consultar($pagina=null) {
	$this->layout="ajax";
	$this->set('entidad_federal', $this->Session->read('entidad_federal'));
	//$this->set('enable2', 'enabled');
 	//$this->set('enable', 'disabled');
 	//$this->set('read', 'readonly');

  $cod_presi                =       $this->Session->read('SScodpresi');
  $cod_entidad              =       $this->Session->read('SScodentidad');
  $cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
  $cod_inst                 =       $this->Session->read('SScodinst');
  $cod_dep                  =       $this->Session->read('SScoddep');
  $Modulo                   =       $this->Session->read('Modulo');
  $ano                      =       $this->ano_ejecucion();

  $this->set('cod_dep_session', $cod_dep);



  if(isset($this->data["cfpp10_registro_asiento_contable"]["opcion"])){
  	     $_SESSION["consolidar_consulta"] = $this->data["cfpp10_registro_asiento_contable"]["opcion"];
  }else{
  	     if(!isset($_SESSION["consolidar_consulta"])){$_SESSION["consolidar_consulta"] = 2;}
  	   }

$consulta1 = $this->SQLCA_consolidado($_SESSION["consolidar_consulta"]);



if(isset($this->data["cfpp10_registro_asiento_contable"]["tipo_busqueda"])){

	               $_SESSION["sql_contena"] = "";

	            if($this->data["cfpp10_registro_asiento_contable"]["ano_asiento"]!=""){
		          $_SESSION["sql_contena"] = " and ano_asiento='".$this->data["cfpp10_registro_asiento_contable"]["ano_asiento"]."' ";
		        }else{
		          $_SESSION["sql_contena"] = " and ano_asiento='".$ano."' ";
		        }

		      if($this->data["cfpp10_registro_asiento_contable"]["tipo_busqueda"]==1){
		        if($this->data["cfpp10_registro_asiento_contable"]["tipo_documento"]!=""){
		      	   $_SESSION["sql_contena"] .= " and tipo_documento='".$this->data["cfpp10_registro_asiento_contable"]["tipo_documento"]."' ";
		      	 }
		}else if($this->data["cfpp10_registro_asiento_contable"]["tipo_busqueda"]==2){
			    if($this->data["cfpp10_registro_asiento_contable"]["mes_asiento"]!=""){
		      	   $_SESSION["sql_contena"] .= " and mes_asiento = '".$this->data["cfpp10_registro_asiento_contable"]["mes_asiento"]."' ";
		      	 }
		      	 if($this->data["cfpp10_registro_asiento_contable"]["numero_asiento"]!=""){
		      	   $_SESSION["sql_contena"] .= " and numero_asiento = '".$this->data["cfpp10_registro_asiento_contable"]["numero_asiento"]."' ";
		      	 }
		}else if($this->data["cfpp10_registro_asiento_contable"]["tipo_busqueda"]==3){
			    if($this->data["cfpp10_registro_asiento_contable"]["pista"]!=""){
		      	   $_SESSION["sql_contena"] .= " and concepto LIKE '%".$this->data["cfpp10_registro_asiento_contable"]["pista"]."%' ";
		      	 }
		}
}//fin if



$consulta1 .= $_SESSION["sql_contena"];


  $this->set('dep_sele', $cod_dep);

	if(isset($pagina)){
		$Tfilas=$this->ccfd10_descripcion->findCount($consulta1);
        if($Tfilas!=0){
        	$data=$this->ccfd10_descripcion->findAll($consulta1,null,"cod_dep, ano_asiento, mes_asiento, dia_asiento, numero_asiento ASC",1,$pagina,null);

            $this->set('DATA',$data);
          	$this->set('siguiente',$pagina+1);
          	$this->set('anterior',$pagina-1);
            $this->bt_nav($Tfilas,$pagina);
            $this->set('numT',$Tfilas);
			$this->set('numP',$pagina);
        }else{
	 	       $this->set('errorMessage', 'No se encontrar&oacute;n datos');
	 	       $this->set('noExiste',true);
	 	       $this->consultar_form();
			   return;
        }

	}else{
		$pagina=1;
		$Tfilas=$this->ccfd10_descripcion->findCount($consulta1);

        if($Tfilas!=0){
        	$data=$this->ccfd10_descripcion->findAll($consulta1,null,"cod_dep, ano_asiento, mes_asiento, dia_asiento, numero_asiento ASC",1,$pagina,null);
			$this->set('DATA',$data);
          	$this->set('siguiente',$pagina+1);
          	$this->set('anterior',$pagina-1);
          	$this->bt_nav($Tfilas,$pagina);
          	$this->set('numT',$Tfilas);
			$this->set('numP',$pagina);

        }else{
	 	       $this->set('errorMessage', 'No se encontrar&oacute;n datos');
	 	       $this->set('noExiste',true);
	 	       $this->consultar_form();
			   return;
        }
	}





	$this->set('ano', $data[0]['ccfd10_descripcion']['ano_asiento']);
	$this->set('dia', $data[0]['ccfd10_descripcion']['dia_asiento']);
	$this->set('mes', $data[0]['ccfd10_descripcion']['mes_asiento']);
    $this->set('numero_asiento', $data[0]['ccfd10_descripcion']['numero_asiento']);
    $this->set('concepto', $data[0]['ccfd10_descripcion']['concepto']);
	$this->set('tipo_documento', $data[0]['ccfd10_descripcion']['tipo_documento']);
    $this->set('numero_documento', $data[0]['ccfd10_descripcion']['numero_documento']);
    $this->set('fecha', $data[0]['ccfd10_descripcion']['fecha_documento']);


if($this->Session->read('SScoddep')==1 && $this->Session->read('Modulo')==0){

	if(!isset($data[0]['ccfd10_descripcion']['cod_dep'])){
		 $data[0]['ccfd10_descripcion']['cod_dep']      = $this->Session->read('SScoddep');
    }

  $this->set('consolidar_consulta', $_SESSION["consolidar_consulta"]);
  $depen=$this->cugd02_dependencia->findAll("cod_tipo_institucion='".$cod_tipo_inst."' and cod_institucion ='".$cod_inst."'  and cod_dependencia='".$data[0]['ccfd10_descripcion']['cod_dep']."'  " );
  $this->set('deno_dependecia', $depen[0]['cugd02_dependencia']['denominacion']);

}


	$datos=$this->ccfd10_detalles->findAll($this->condicionNDEP()."  and cod_dep='".$data[0]['ccfd10_descripcion']['cod_dep']."' and ano_asiento=".$data[0]['ccfd10_descripcion']['ano_asiento']." and dia_asiento=".$data[0]['ccfd10_descripcion']['dia_asiento']." and mes_asiento=".$data[0]['ccfd10_descripcion']['mes_asiento']." and numero_asiento=".$data[0]['ccfd10_descripcion']['numero_asiento']." ",null,"cod_dep, ano_asiento, mes_asiento, dia_asiento, numero_asiento ASC",null,null,null);
   // print_r($datos);


    $this->set('datos', $datos);
    $this->set('pagina', $pagina);
	$i=0;
	$vector=array();
    foreach($datos as $row){
		//print_r($row);
		$cod_dep=$row['ccfd10_detalles']['cod_dep'];
		$linea=$row['ccfd10_detalles']['numero_linea'];
		$tipo=$row['ccfd10_detalles']['debito_credito'];
		$tipo_cuenta     = $row['ccfd10_detalles']['cod_tipo_cuenta'];
		$cod_cuenta      = $row['ccfd10_detalles']['cod_cuenta'];
		$cod_subcuenta   = $row['ccfd10_detalles']['cod_subcuenta'];
		$cod_division    = $row['ccfd10_detalles']['cod_division'];
		$cod_subdivision = $row['ccfd10_detalles']['cod_subdivision'];
		$monto=$row['ccfd10_detalles']['monto'];

		if($cod_subdivision==0){
			if($cod_division==0){
				if($cod_subcuenta==0){
					$d=$this->ccfd01_cuenta->execute("select * from ccfd01_cuenta where ".$this->condicionNDEP()."  and cod_dep=1 and cod_tipo_cuenta=".$row['ccfd10_detalles']['cod_tipo_cuenta']." and cod_cuenta=".$row['ccfd10_detalles']['cod_cuenta']);
				}else{
					$d=$this->ccfd01_subcuenta->execute("select * from ccfd01_subcuenta where ".$this->condicionNDEP()."  and cod_dep=1 and cod_tipo_cuenta=".$row['ccfd10_detalles']['cod_tipo_cuenta']." and cod_cuenta=".$row['ccfd10_detalles']['cod_cuenta']." and cod_subcuenta=".$row['ccfd10_detalles']['cod_subcuenta']);
				}

			}else{
				$d=$this->ccfd01_division->execute("select * from ccfd01_division where ".$this->condicionNDEP()."  and cod_dep=1 and cod_tipo_cuenta=".$row['ccfd10_detalles']['cod_tipo_cuenta']." and cod_cuenta=".$row['ccfd10_detalles']['cod_cuenta']." and cod_subcuenta=".$row['ccfd10_detalles']['cod_subcuenta']." and cod_division=".$row['ccfd10_detalles']['cod_division']);
			}

		}else{
			$d=$this->ccfd01_subdivision->execute("select * from ccfd01_subdivision where ".$this->condicionNDEP()."  and cod_dep=1 and cod_tipo_cuenta=".$row['ccfd10_detalles']['cod_tipo_cuenta']." and cod_cuenta=".$row['ccfd10_detalles']['cod_cuenta']." and cod_subcuenta=".$row['ccfd10_detalles']['cod_subcuenta']." and cod_division=".$row['ccfd10_detalles']['cod_division']." and cod_subdivision=".$row['ccfd10_detalles']['cod_subdivision']);
		}
		$vector[$linea]['linea']=$linea;
		$vector[$linea]['denominacion']=$d[0][0]['denominacion'];
		$i++;
    }

    $this->set('vector',$vector);




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



  function modificar($ano=null,$mes=null,$num_asiento=null,$pagina=null){
 	$this->layout="ajax";

 	$cond1 = $this->SQLCA();
 	$sql2 = $cond1." and ano_asiento=".$ano." and mes_asiento=".$mes." and numero_asiento=".$num_asiento;
 	//$data = $this->ccfd01_division->execute($sql);
 	$data = $this->ccfd10_descripcion->findAll($sql2);
 	if($data){
 		$this->set('mensaje','Puede proceder a Modificar los datos');

		$this->set('ano', $data[0]['ccfd10_descripcion']['ano_asiento']);
		$this->set('dia', $data[0]['ccfd10_descripcion']['dia_asiento']);
		$this->set('mes', $data[0]['ccfd10_descripcion']['mes_asiento']);
	    $this->set('numero_asiento', $data[0]['ccfd10_descripcion']['numero_asiento']);
	    $this->set('concepto', $data[0]['ccfd10_descripcion']['concepto']);
		$this->set('tipo_documento', $data[0]['ccfd10_descripcion']['tipo_documento']);
	    $this->set('numero_documento', $data[0]['ccfd10_descripcion']['numero_documento']);
	    $this->set('fecha', $data[0]['ccfd10_descripcion']['fecha_documento']);

		$datos=$this->ccfd10_detalles->findAll($this->SQLCA()." and ano_asiento=".$data[0]['ccfd10_descripcion']['ano_asiento']." and mes_asiento=".$data[0]['ccfd10_descripcion']['mes_asiento']." and numero_asiento=".$data[0]['ccfd10_descripcion']['numero_asiento'],null,"cod_dep, ano_asiento, mes_asiento, dia_asiento, numero_asiento ASC",null,null,null);
	   // print_r($datos);

	    $this->set('pagina',$pagina);

	    $this->set('datos', $datos);
 	}else{
 		$this->set('mensajeError','Error no se encontraron los datos a Modificar');
 	}
 }//fin modificar





  function guardar_modificar($ano=null,$mes=null,$num_asiento=null,$pagina=null){
 	$this->layout="ajax";


		$concepto=$this->data['cnmp09']['concepto'];
		$numero=$this->data['cnmp09']['numero'];
		$fecha=$this->data['cnmp09']['fecha'];

  	    $sql="update ccfd10_descripcion set numero_documento='".$numero."', fecha_documento='".$fecha."',  concepto='".$concepto."' where ".$this->SQLCA()." and ano_asiento='$ano' and mes_asiento='$mes' and numero_asiento='$num_asiento'";

		if($this->ccfd10_descripcion->execute($sql)>1){
		$this->set('Message_existe','Los datos fuer&oacute;n modificados exitosamente');
		$this->consultar($pagina);
		$this->render("consultar");
		}else{
		$this->set('errorMessage','Los datos no fuer&oacute;n modificados');
		$this->consultar($pagina);
		$this->render("consultar");
		}//fin else actualizacion
 }//guardar modificar



  function eliminar($ano=null, $mes=null,$num_asiento=null ,$pagina_regreso=null){
 		$this->layout="ajax";

			$sql = "DELETE FROM ccfd10_descripcion WHERE ".$this->SQLCA()." and ano_asiento=".$ano." and numero_asiento=".$num_asiento;
				if($this->ccfd10_descripcion->execute($sql)>1){
					$this->set('Message_existe', 'EL REGISTRO FUE ELIMINADO EXITOSAMENTE');
					/////DE AQUI AGREGO ERICK
				$Tfilas=$this->ccfd10_descripcion->findCount();
					if($Tfilas!=0){
					$this->consultar($pagina_regreso);
					$this->render("consultar");
					}else{
						$this->index();
						$this->render("index");
					}/////HASTA AQUI AGREGO ERICK
					//$this->consultar($pagina_regreso);
					//$this->render("consultar");
				}else{
					$this->set('errorMessage', 'EL REGISTRO NO PUDO SER ELIMINADO');
					$this->consultar($pagina_regreso);
					$this->render("consultar");
				}
 }//eliminar



function salir_asiento($ano=null,$mes=null,$num_asiento=null) {
    $this->layout = "ajax";
    if($ano!=null && $mes!=null && $num_asiento!=null){

    }else{
		$ano = $this->ano_ejecucion();
		$mes = $this->Session->read("smes_asiento");
		$num_asiento = $this->Session->read("snumero_asiento");
    }
	$sw_con = $this->ccfd10_descripcion->execute("SELECT COUNT(*) AS cont_registro FROM ccfd10_descripcion_tmp WHERE ".$this->SQLCA()." and ano_asiento=".$ano." and mes_asiento=".$mes." and numero_asiento=".$num_asiento.";");
	if($sw_con!=null){
		if($sw_con[0][0]["cont_registro"]==0){
			$v=$this->ccfd05_numero_asiento->execute("SELECT numero_asiento FROM ccfd05_numero_asiento WHERE ".$this->SQLCA()." and ano_asiento=".$ano." and mes_asiento=".$mes." ORDER BY numero_asiento DESC;");
			if($v!=null){
				$numero=$v[0][0]["numero_asiento"];
				if($numero>0 && $numero==$num_asiento){

					if($mes==1 && $numero<3){
						$numero = 0;
					}else{
						$numero = $numero-1;
					}

					if($numero==0){
						$sq = "DELETE FROM ccfd05_numero_asiento WHERE ".$this->SQLCA()." AND ano_asiento=".$ano." AND mes_asiento=".$mes.";";
					}else{
						$sq = "UPDATE ccfd05_numero_asiento SET numero_asiento=".$numero." WHERE ".$this->SQLCA()." AND ano_asiento=".$ano." AND mes_asiento=".$mes.";";
					}

					$swc = $this->ccfd05_numero_asiento->execute("BEGIN; ".$sq);
					if($swc>1){
						$this->ccfd05_numero_asiento->execute("COMMIT;");
					}else{
						$this->ccfd05_numero_asiento->execute("ROLLBACK;");
					}
				}
			}
		}
	}
}



						/** TRANSFERIR ACUMULADO AÑO ANTERIOR */


function salir_cstatus () {
       $this->layout="ajax";
       $this->Session->delete("autor_valido");
}//fin salir_cstatus

function entrar_cstatus(){
	$this->layout="ajax";
	if(isset($this->data['cfpp10_registro_acumulado']['login']) && isset($this->data['cfpp10_registro_acumulado']['password'])){
		$l="PROYECTO";
		// $c="JJJSAE";
		$c2="CAMBIAR";
		$user=addslashes($this->data['cfpp10_registro_acumulado']['login']);
		$paswd=addslashes($this->data['cfpp10_registro_acumulado']['password']);
		$cond=$this->SQLCA()." and username='".$user."' and cod_tipo=94 and clave='".$paswd."'";
		// if($user==$l && ($paswd==$c || $paswd==$c2)){
		if($user==$l && $paswd==$c2){
			$this->Session->write('autor_valido',true);
			$this->transferir_acumulado();
			$this->render("transferir_acumulado");
		}elseif($this->cugd05_restriccion_clave->findCount($cond)!=0 && $paswd==$c2){
			$this->Session->write('autor_valido',true);
			$this->transferir_acumulado();
			$this->render("transferir_acumulado");
		}else{
			$this->set('errorMessage',"Lo siento, se necesita autorizaci&oacute;n para utilizar este programa");
			$this->transferir_acumulado();
			$this->render("transferir_acumulado");
		}
	}
}


	function transferir_acumulado() {
    	$this->layout = "ajax";
	}


	function anio_actualizar($anio_ant=null) {
    	$this->layout = "ajax";
    	$ano_actual = '';
    	if($anio_ant!=null){
    		if($anio_ant < 1000){
    			$ano_fiscal=null;
    			echo "<script>document.getElementById('procesar').disabled=true;</script>";
    			$this->set('errorMessage', 'Debe ingresar un A&ntilde;o anterior v&aacute;lido.');
    		}else{
    			$ano_actual = $anio_ant + 1;
				$ano_fiscal=$this->ccfd02->execute("SELECT ano_fiscal FROM ccfd02 WHERE ".$this->SQLCA_no_dep()." and ano_fiscal=".$ano_actual." and (debito_acumulado!=0 or credito_acumulado!=0) LIMIT 1;");
    		}
    	}else{
    		$ano_actual = '';
    		$ano_fiscal=null;
    		echo "<script>document.getElementById('procesar').disabled=true;</script>";
    		$this->set('errorMessage', 'Debe ingresar el A&ntilde;o anterior.');
    	}

		echo "<script>document.getElementById('ano_actual').value='$ano_actual';</script>";

		if(!empty($ano_fiscal)){
			$this->set('errorMessage', 'Lo siento no se puede transferir saldo, debido a que ya hay registros en el A&ntilde;o actual.');
			echo "<script>setTimeout(\"fondoCampo('ano_actual',2);\", 3000);</script>";
			echo "<script>document.getElementById('procesar').disabled=true;</script>";
		}else if($ano_actual!=''){
			echo "<script>document.getElementById('procesar').disabled=false;</script>";
		}
	}


	function procesar(){
		$this->layout="ajax";
		$ano_anterior = $this->data["cfpp10_registro_acumulado"]["ano_anterior"];
		$ano_actual = $this->data["cfpp10_registro_acumulado"]["ano_actual"];

		if($ano_anterior!=null && $ano_actual!=null){

			$ano_fiscal=$this->ccfd02->execute("SELECT ano_fiscal FROM ccfd02 WHERE ".$this->SQLCA_no_dep()." and ano_fiscal=".$ano_actual." and (debito_acumulado!=0 or credito_acumulado!=0) LIMIT 1;");
			if(!empty($ano_fiscal)){
				$this->set('errorMessage', 'Lo siento no se puede transferir saldo, debido a que ya hay registros en el A&ntilde;o actual.');
				echo "<script>setTimeout(\"fondoCampo('ano_actual',2);\", 3000);</script>";
			}else{

			$sql_insert = "BEGIN; INSERT INTO ccfd02 (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, ano_fiscal, cod_tipo_cuenta, cod_cuenta, cod_subcuenta, cod_division, cod_subdivision, debito_acumulado, credito_acumulado, debito_ene, credito_ene, debito_feb, credito_feb, debito_mar, credito_mar, debito_abr, credito_abr, debito_may, credito_may, debito_jun, credito_jun, debito_jul, credito_jul, debito_ago, credito_ago, debito_sep, credito_sep, debito_oct, credito_oct, debito_nov, credito_nov, debito_dic, credito_dic) VALUES ";
			$datos_anterior = $this->ccfd02->execute("SELECT cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, ano_fiscal, cod_tipo_cuenta, cod_cuenta, cod_subcuenta, cod_division, cod_subdivision, sum(debito_acumulado + debito_ene + debito_feb + debito_mar + debito_abr + debito_may + debito_jun + debito_jul + debito_ago + debito_sep + debito_oct + debito_nov + debito_dic)::numeric(22,2) AS total_debito, sum(credito_acumulado + credito_ene + credito_feb + credito_mar + credito_abr + credito_may + credito_jun + credito_jul + credito_ago + credito_sep + credito_oct + credito_nov + credito_dic)::numeric(22,2) AS total_credito FROM ccfd02 WHERE ".$this->SQLCA_no_dep()." and ano_fiscal='$ano_anterior' GROUP BY cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, ano_fiscal, cod_tipo_cuenta, cod_cuenta, cod_subcuenta, cod_division, cod_subdivision;");

			if(!empty($datos_anterior)){

				echo '<script>venta_procesos_informacion();</script>';

			foreach($datos_anterior as $datos_conta){
				$cod_presi         = $datos_conta[0]['cod_presi'];
				$cod_entidad       = $datos_conta[0]['cod_entidad'];
				$cod_tipo_inst     = $datos_conta[0]['cod_tipo_inst'];
				$cod_inst          = $datos_conta[0]['cod_inst'];
				$cod_dep           = $datos_conta[0]['cod_dep'];
				$cod_tipo_cuenta   = $datos_conta[0]['cod_tipo_cuenta'];
				$cod_cuenta        = $datos_conta[0]['cod_cuenta'];
				$cod_subcuenta     = $datos_conta[0]['cod_subcuenta'];
				$cod_division      = $datos_conta[0]['cod_division'];
				$cod_subdivision   = $datos_conta[0]['cod_subdivision'];
				$debito_acumulado  = $datos_conta[0]['total_debito'];
				$credito_acumulado = $datos_conta[0]['total_credito'];
				$valores[] = " ('".$cod_presi."', '".$cod_entidad."', '".$cod_tipo_inst."', '".$cod_inst."', '".$cod_dep."', '".$ano_actual."', '".$cod_tipo_cuenta."', '".$cod_cuenta."', '".$cod_subcuenta."', '".$cod_division."', '".$cod_subdivision."', '$debito_acumulado', '$credito_acumulado', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0')";
			}

			$sql_insert .= " ".implode(',', $valores).";";
			$swi = $this->ccfd02->execute($sql_insert);
			if($swi>1){
				$this->ccfd02->execute("COMMIT;");
				echo '<script>Control.Modal.close(true);</script>';
				$this->set('Message_existe','El Proceso fue Ejecutado Exitosamente.');
			}else{
				$this->ccfd02->execute("ROLLBACK;");
				echo '<script>Control.Modal.close(true);</script>';
				$this->set('errorMessage', 'No se pudo almacenar los datos - Intente Nuevamente.');
			}

			}else{
				$this->set('errorMessage', 'Lo siento no hay datos que almacenar.');
			}

			}
		}else{
			$this->set('errorMessage', 'El campo A&ntilde;o anterior no puede estar vacio.');
		}
	}


 }//Fin de la clase
?>
