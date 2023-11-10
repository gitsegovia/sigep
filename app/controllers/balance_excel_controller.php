<?php

 class BalanceExcelController extends AppController{


 	var $uses = array('ccfd04_cierre_mes','v_balance_ejecucion_inst','v_balance_ejecucion','balance_mes22_inst','balance_mes2');
 	var $helpers = array('Html','Ajax','Javascript', 'Sisap','Fpdf','Form');


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


function beforeFilter(){
	$this->checkSession();
	if($this->ano_ejecucion()!=""){
		return;
	}else{
		echo "<h3>Por Favor, Registre el Año de Ejecuci&oacute;n de Presupuesto<br>Ingrese al M&oacute;dulo de Uso General</h3>";
		exit();
	}
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

    function SQLCA_no_dep($ano=null){//sql para busqueda de codigos de arranque con y sin año
         $sql_re = "cod_presi=".$this->verifica_SS(1)."  and    ";
         $sql_re .= "cod_entidad=".$this->verifica_SS(2)."  and  ";
         $sql_re .= "cod_tipo_inst=".$this->verifica_SS(3)."  and ";
         $sql_re .= "cod_inst=".$this->verifica_SS(4)."  ";

         return $sql_re;
    }//fin funcion SQLCA


function index () {
    $this->layout="ajax";
    $this->set('ano',$this->ano_ejecucion());

}//fin index

function balance () {
	$this->layout="ajax";
    //pr($this->data);
    $this->set('entidad_federal', $this->Session->read('entidad_federal'));
	     if(isset($this->data["reporte"]["ano"]) && !empty($this->data["reporte"]["ano"])){
              $Ano=$this->data["reporte"]["ano"];

	     }else{
	     	$Ano=$this->ano_ejecucion();

	     }

	    $this->set('ANO',$Ano);
    	if(isset($this->data['cfpp05']['consolidacion'])){
    	     $con=$this->SQLCA_consolidado($this->data['cfpp05']['consolidacion']);
    	     $modelo=$this->data['cfpp05']['consolidacion']==1?"v_balance_ejecucion_inst":"v_balance_ejecucion";
              $this->set("modelo",$modelo);
    	}else{
    		$con=$this->SQLCA_consolidado();
    		$modelo="v_balance_ejecucion";
	     	$this->set("modelo",$modelo);
    	}

        $titulo_a = $this->Session->read('dependencia');
  	    $this->set('titulo_a',$titulo_a);

        if(isset($this->data["reporte"]["cod_sector"]) && $this->data["reporte"]["cod_sector"]!="")
        	$cod_sector=" cod_sector=".$this->data["reporte"]["cod_sector"]." and ";
        else
        	$cod_sector=" 1=1 and ";
        if(isset($this->data["reporte"]["cod_programa"]) && $this->data["reporte"]["cod_programa"]!="")
        	$cod_programa=" cod_programa=".$this->data["reporte"]["cod_programa"]." and ";
        else
        	$cod_programa=" 1=1 and ";
        if(isset($this->data["reporte"]["cod_subprograma"]) && $this->data["reporte"]["cod_subprograma"]!="")
        	$cod_sub_prog=" cod_sub_prog=".$this->data["reporte"]["cod_subprograma"]." and ";
        else
        	$cod_sub_prog=" 1=1 and ";
        if(isset($this->data["reporte"]["cod_proyecto"]) && $this->data["reporte"]["cod_proyecto"]!="")
        	$cod_proyecto=" cod_proyecto=".$this->data["reporte"]["cod_proyecto"]." and ";
        else
        	$cod_proyecto=" 1=1 and ";
        if(isset($this->data["reporte"]["cod_actividad"]) && $this->data["reporte"]["cod_actividad"]!="")
        	$cod_activ_obra=" cod_activ_obra=".$this->data["reporte"]["cod_actividad"]." ";
        else
        	$cod_activ_obra=" 1=1 ";
        if(isset($this->data["reporte"]["cod_partida"]) && $this->data["reporte"]["cod_partida"]!="")
        	$cod_partida=" cod_partida=".$this->data["reporte"]["cod_partida"]." ";
        else
        	$cod_partida=" 1=1 ";
        if(isset($this->data["reporte"]["cod_generica"]) && $this->data["reporte"]["cod_generica"]!="")
        	$cod_generica=" cod_generica=".$this->data["reporte"]["cod_generica"]." and ";
        else
        	$cod_generica=" 1=1 and ";
        if(isset($this->data["reporte"]["cod_especifica"]) && $this->data["reporte"]["cod_especifica"]!="")
        	$cod_especifica=" cod_especifica=".$this->data["reporte"]["cod_especifica"]." and ";
        else
        	$cod_especifica=" 1=1 and ";
        if(isset($this->data["reporte"]["cod_subespecifica"]) && $this->data["reporte"]["cod_subespecifica"]!="")
        	$cod_sub_espec=" cod_sub_espec=".$this->data["reporte"]["cod_subespecifica"]." and ";
        else
        	$cod_sub_espec=" 1=1 and ";
        if(isset($this->data["reporte"]["cod_auxiliar"]) && $this->data["reporte"]["cod_auxiliar"]!="")
        	$cod_auxiliar=" cod_auxiliar=".$this->data["reporte"]["cod_auxiliar"]." ";
        else
        	$cod_auxiliar=" 1=1 ";

  	    $modo= (int) $this->data["reporte"]["modo"];
  	   // echo "MODO: ".$modo;
  	    switch($modo){
  	    	case 1:
                 //completo todo
                 $condicion=" 1=1";
  	    	break;
  	    	case 2:
  	    	      //por categoria
  	    	      $condicion=" ".$cod_sector.$cod_programa.$cod_sub_prog.$cod_proyecto.$cod_activ_obra;
  	    	break;
  	    	case 3:
  	    	    //por categoria y partida
  	    	    $condicion=" ".$cod_sector.$cod_programa.$cod_sub_prog.$cod_proyecto.$cod_activ_obra." and ".$cod_partida." and ".$cod_generica.$cod_especifica.$cod_sub_espec.$cod_auxiliar;
            break;
            case 4:
                 $condicion=" ".$cod_partida;
            break;
            case 5:
                 $condicion=" ".$cod_partida." and ".$cod_generica.$cod_especifica.$cod_sub_espec.$cod_auxiliar;
            break;
            default: $condicion=" 1=1";
  	    }//fin switch
        //echo $condicion;

	    $vector = $this->$modelo->findAll($con." and ano=".$Ano." and ".$condicion,null,'cod_partida,cod_generica,cod_especifica,cod_sub_espec,cod_auxiliar ASC');
        $total_spsppa = $this->$modelo->execute("SELECT cod_sector,cod_programa,cod_sub_prog,cod_proyecto,cod_activ_obra FROM ".$modelo." WHERE ".$con." and ano=".$Ano." and ".$condicion."  GROUP BY cod_sector,cod_programa,cod_sub_prog,cod_proyecto,cod_activ_obra  ORDER BY cod_sector,cod_programa,cod_sub_prog,cod_proyecto,cod_activ_obra ASC");

        $distinto_sector = $this->$modelo->execute("SELECT cod_sector,cod_programa,cod_sub_prog,cod_proyecto,cod_activ_obra FROM ".$modelo." WHERE ".$con." and ano=".$Ano." and ".$condicion." GROUP BY cod_sector,cod_programa,cod_sub_prog,cod_proyecto,cod_activ_obra");
        $this->set('distintos_sectores',$total_spsppa);
        $this->set('cfpd05',$vector);
        //$this->render('balance2');
}

function balance_mes () {
	$this->layout="ajax";
	$this->set('entidad_federal', $this->Session->read('entidad_federal'));
	     if(isset($this->data["reporte"]["ano"]) && !empty($this->data["reporte"]["ano"])){
              $Ano=$this->data["reporte"]["ano"];

	     }else{
	     	$Ano=$this->ano_ejecucion();

	     }
    	$this->set('ANO',$Ano);
    	if(isset($this->data['cfpp05']['consolidacion'])){
    	    $con=$this->SQLCA_consolidado($this->data['cfpp05']['consolidacion']);
    	    $modelo=$this->data['cfpp05']['consolidacion']==1?"balance_mes22_inst":"balance_mes2";
    	    $tabla=$this->data['cfpp05']['consolidacion']==1?"v_balance_ejecucion22_inst":"v_balance_ejecucion2_2";
            $this->set("modelo",$modelo);
    	}else{
    		$con=$this->SQLCA_consolidado();
    		$modelo="balance_mes2";
    		$tabla="v_balance_ejecucion2_2";
            $this->set("modelo",$modelo);
    	}
        $titulo_a = $this->Session->read('dependencia');
  	    $this->set('titulo_a',$titulo_a);

        if(isset($this->data["reporte"]["cod_sector"]) && $this->data["reporte"]["cod_sector"]!="")
        	$cod_sector=" cod_sector=".$this->data["reporte"]["cod_sector"]." and ";
        else
        	$cod_sector=" 1=1 and ";
        if(isset($this->data["reporte"]["cod_programa"]) && $this->data["reporte"]["cod_programa"]!="")
        	$cod_programa=" cod_programa=".$this->data["reporte"]["cod_programa"]." and ";
        else
        	$cod_programa=" 1=1 and ";
        if(isset($this->data["reporte"]["cod_subprograma"]) && $this->data["reporte"]["cod_subprograma"]!="")
        	$cod_sub_prog=" cod_sub_prog=".$this->data["reporte"]["cod_subprograma"]." and ";
        else
        	$cod_sub_prog=" 1=1 and ";
        if(isset($this->data["reporte"]["cod_proyecto"]) && $this->data["reporte"]["cod_proyecto"]!="")
        	$cod_proyecto=" cod_proyecto=".$this->data["reporte"]["cod_proyecto"]." and ";
        else
        	$cod_proyecto=" 1=1 and ";
        if(isset($this->data["reporte"]["cod_actividad"]) && $this->data["reporte"]["cod_actividad"]!="")
        	$cod_activ_obra=" cod_activ_obra=".$this->data["reporte"]["cod_actividad"]." ";
        else
        	$cod_activ_obra=" 1=1 ";
        if(isset($this->data["reporte"]["cod_partida"]) && $this->data["reporte"]["cod_partida"]!="")
        	$cod_partida=" cod_partida=".$this->data["reporte"]["cod_partida"]." ";
        else
        	$cod_partida=" 1=1 ";
        if(isset($this->data["reporte"]["cod_generica"]) && $this->data["reporte"]["cod_generica"]!="")
        	$cod_generica=" cod_generica=".$this->data["reporte"]["cod_generica"]." and ";
        else
        	$cod_generica=" 1=1 and ";
        if(isset($this->data["reporte"]["cod_especifica"]) && $this->data["reporte"]["cod_especifica"]!="")
        	$cod_especifica=" cod_especifica=".$this->data["reporte"]["cod_especifica"]." and ";
        else
        	$cod_especifica=" 1=1 and ";
        if(isset($this->data["reporte"]["cod_subespecifica"]) && $this->data["reporte"]["cod_subespecifica"]!="")
        	$cod_sub_espec=" cod_sub_espec=".$this->data["reporte"]["cod_subespecifica"]." and ";
        else
        	$cod_sub_espec=" 1=1 and ";
        if(isset($this->data["reporte"]["cod_auxiliar"]) && $this->data["reporte"]["cod_auxiliar"]!="")
        	$cod_auxiliar=" cod_auxiliar=".$this->data["reporte"]["cod_auxiliar"]." ";
        else
        	$cod_auxiliar=" 1=1 ";

  	    $modo= (int) $this->data["reporte"]["modo"];
  	    //echo "MODO: ".$modo;
  	    switch($modo){
  	    	case 1:
                 //completo todo
                 $condicion=" 1=1";
  	    	break;
  	    	case 2:
  	    	      //por categoria
  	    	      $condicion=" ".$cod_sector.$cod_programa.$cod_sub_prog.$cod_proyecto.$cod_activ_obra;
  	    	break;
  	    	case 3:
  	    	    //por categoria y partida
  	    	    $condicion=" ".$cod_sector.$cod_programa.$cod_sub_prog.$cod_proyecto.$cod_activ_obra." and ".$cod_partida." and ".$cod_generica.$cod_especifica.$cod_sub_espec.$cod_auxiliar;
            break;
            case 4:
                 $condicion=" ".$cod_partida;
            break;
            case 5:
                 $condicion=" ".$cod_partida." and ".$cod_generica.$cod_especifica.$cod_sub_espec.$cod_auxiliar;
            break;
            default: $condicion=" 1=1";
  	    }//fin switch
         if(isset($this->data["reporte"]["mes"]) && !empty($this->data["reporte"]["mes"])){
         	$this->set("mes",$this->data["reporte"]["mes"]);
         	$this->Session->write("mes_solicitado",$this->data["reporte"]["mes"]);
         }else{
         	$this->set("mes",date("m"));
         	$this->Session->write("mes_solicitado",date("m"));
         }
	    $vector = $this->$modelo->findAll($con." and ano=".$Ano." and ".$condicion,null,'cod_partida,cod_generica,cod_especifica,cod_sub_espec,cod_auxiliar ASC');
        $total_spsppa = $this->$modelo->execute("SELECT cod_sector,cod_programa,cod_sub_prog,cod_proyecto,cod_activ_obra FROM ".$tabla." WHERE ".$con." and ano=".$Ano." and ".$condicion."  GROUP BY cod_sector,cod_programa,cod_sub_prog,cod_proyecto,cod_activ_obra  ORDER BY cod_sector,cod_programa,cod_sub_prog,cod_proyecto,cod_activ_obra ASC");
        $distinto_sector = $this->$modelo->execute("SELECT cod_sector,cod_programa,cod_sub_prog,cod_proyecto,cod_activ_obra FROM ".$tabla." WHERE ".$con." and ano=".$Ano." and ".$condicion." GROUP BY cod_sector,cod_programa,cod_sub_prog,cod_proyecto,cod_activ_obra");
        $this->set('distintos_sectores',$total_spsppa);
        $this->set('cfpd05',$vector);

         //$this->render('balance_mes2');

}//fin balance_mes

}//fin class
?>
