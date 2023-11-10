<?php
/*
 * Creado el  08/12/2007 a las 09:14:46 AM
 * Herramienta: EasyEclipse.
 * Proyecto: SIGEP
 * Descripcion:
 */
class Cstp10PlanillaRecaudacionController extends AppController {
 	var $name = 'cstp10_planilla_recaudacion';
 	var $uses = array ('ccfd04_cierre_mes',
        'cugd02_dependencia',
        'ccfd03_instalacion',
        'cstd01_entidades_bancarias',
        'cstd01_sucursales_bancarias',
        'arrd05',
        'cstd10_tipo_ramo',
        'cstd10_firmantes',
        'cstd10_planilla_liquidacion',
        'cstd10_planilla_recaudacion',
        'v_cstd10_planilla_recaudacion',
 		'cstd01_sucursales_bancarias',
 		'v_cstd01_bancos',
 		'v_cstd01_sucursales',
        'v_cuentas_bancarias',
        'cstd10_cuentas_bancarias');
 	var $helpers = array ('Html','Ajax','Javascript','Sisap');

    function beforeFilter(){
    	$this->checkSession();
    }

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

    function mascara3($cod){
    	$opc = strlen($cod);
    	switch ($opc) {
    		case 1:
    			$cod = '000'.$cod;
    			break;
    		case 2:
    			$cod = '00'.$cod;
    			break;
    		case 3:
    			$cod = '0'.$cod;
    			break;

    		default:
    			break;
    	}

    	return $cod;
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



    function index () {

     	$this->layout ="ajax";
     	$this->set('entidad_federal', $this->Session->read('entidad_federal'));
     	$this->set('vector_cuenta','');
     	$this->concatena_cuatro_digitos($this->v_cstd01_bancos->generateList($this->SQLCA(), 'cod_entidad_bancaria ASC', null, '{n}.v_cstd01_bancos.cod_entidad_bancaria', '{n}.v_cstd01_bancos.denominacion'),'direccion_superior');

    	$tipo_ramo=  $this->cstd10_tipo_ramo->generateList(null, 'denominacion ASC', null, '{n}.cstd10_tipo_ramo.cod_tipo_ramo', '{n}.cstd10_tipo_ramo.denominacion');
        $this->concatena($tipo_ramo, 'tipo_ramo');
    	$ano = $this->ano_ejecucion();
        $this->set('operador_1', $this->Session->read('nom_usuario'));
    	$this->set('ano_movimiento', $ano);
    }

    function liquidacion(){

        $this->layout ="ajax";
        $cod_presi = $this->Session->read('SScodpresi');
        $cod_entidad = $this->Session->read('SScodentidad');
        $cod_tipo_inst = $this->Session->read('SScodtipoinst');
        $cod_inst = $this->Session->read('SScodinst');
        $cod_dep = $this->Session->read('SScoddep');
        $this->set('entidad_federal', $this->Session->read('entidad_federal'));

        $ano_planilla = $this->ano_ejecucion();
        
        $lista = $this->comboBox("cstd10_planilla_liquidacion", "dependencia_ciudadano", "dependencia_ciudadano", $this->SQLCA());

        $otros = array("agregar" => "AGREGAR");
        if($lista!=null)
        {
            $lista = $lista + $otros;
        }
        else
        {
            $lista = $otros;
        }

        $this->concatena($lista, 'entidad');

        $tipo_ramo=  $this->cstd10_tipo_ramo->generateList(null, 'cod_tipo_ramo ASC', null, '{n}.cstd10_tipo_ramo.cod_tipo_ramo', '{n}.cstd10_tipo_ramo.denominacion');
        $this->concatena($tipo_ramo, 'tipo_ramo');

        $max = $this->cstd10_planilla_liquidacion->execute("SELECT MAX(numero_planilla) as numero_planilla FROM cstd10_planilla_liquidacion WHERE ".$this->SQLCA().' and ano_planilla='.$ano_planilla);
       if($max[0][0]["numero_planilla"]==""){
          $numero_planilla=1;
       }else{
          $numero_planilla=$max[0][0]["numero_planilla"]+1;
       }
        

        $tipo_credito = array("Gastos de Personal" => "Gastos de Personal",
                              "Gastos de Funcionamiento" => "Gastos de Funcionamiento",
                              "Convenios Especiales" => "Convenios Especiales");

        $this->set('operador_1', $this->Session->read('nom_usuario'));
        $this->set('ano_planilla', $ano_planilla);
        $this->set('numero_planilla', $numero_planilla);
        $this->set('tipo_credito', $tipo_credito);
    }

    function consultar_liquidacion (){

        $this->layout ="ajax";
        $ano_planilla = $this->ano_ejecucion();
        
        $planilla = $this->comboBox("cstd10_planilla_liquidacion", "numero_planilla", "dependencia_ciudadano", $this->SQLCA()." and ano_planilla=".$ano_planilla);

        $planilla = $planilla != null ? $planilla : array();
        
        $this->set('ano_planilla', $ano_planilla);
        $this->concatena($planilla, 'numero_planilla');
    }

    function buscar_ano_liquidacion ($year){
        $this->layout ="ajax";
        
        $planilla = $this->comboBox("cstd10_planilla_liquidacion", "numero_planilla", "dependencia_ciudadano", $this->SQLCA()." and ano_planilla=".$year);

        $planilla = $planilla != null ? $planilla : array();
        
        $this->concatena($planilla, 'numero_planilla');
    }

    function ver_liquidacion(){

        $this->layout ="ajax";
        $cod_presi = $this->Session->read('SScodpresi');
        $cod_entidad = $this->Session->read('SScodentidad');
        $cod_tipo_inst = $this->Session->read('SScodtipoinst');
        $cod_inst = $this->Session->read('SScodinst');
        $cod_dep = $this->Session->read('SScoddep');
        $this->set('entidad_federal', $this->Session->read('entidad_federal'));

        $ano_planilla = $this->data['cstp10_planilla_recaudacion']["ano_planilla"];
        $numero_planilla = $this->data['cstp10_planilla_recaudacion']["numero_planilla"];
        
        
        // $data_planilla = $this->cstd10_planilla_liquidacion->findAll();
        
        $data_planilla = $this->cstd10_planilla_liquidacion->findAll($this->SQLCA()." and ano_planilla=".$ano_planilla." and numero_planilla='".$numero_planilla."'");
        
        $tipo_ramo=  $this->cstd10_tipo_ramo->generateList(null, 'cod_tipo_ramo ASC', null, '{n}.cstd10_tipo_ramo.cod_tipo_ramo', '{n}.cstd10_tipo_ramo.denominacion');
        $this->concatena($tipo_ramo, 'tipo_ramo');

        $tipo_credito = array("Gastos de Personal" => "Gastos de Personal",
                              "Gastos de Funcionamiento" => "Gastos de Funcionamiento",
                              "Convenios Especiales" => "Convenios Especiales");

        $this->set('operador_1', $this->Session->read('nom_usuario'));
        $this->set('ano_planilla', $ano_planilla);
        $this->set('numero_planilla', $numero_planilla);
        $this->set('tipo_credito', $tipo_credito);
        $this->set('data_planilla', $data_planilla);
        $this->set('enable_delet', false);
    }

    function editar_liquidacion($ano, $n_planilla){

        $this->layout ="ajax";
        $cod_presi = $this->Session->read('SScodpresi');
        $cod_entidad = $this->Session->read('SScodentidad');
        $cod_tipo_inst = $this->Session->read('SScodtipoinst');
        $cod_inst = $this->Session->read('SScodinst');
        $cod_dep = $this->Session->read('SScoddep');
        $this->set('entidad_federal', $this->Session->read('entidad_federal'));

        $ano_planilla = $ano;
        $numero_planilla = $n_planilla;
                
        // $data_planilla = $this->cstd10_planilla_liquidacion->findAll();
        
        $data_planilla = $this->cstd10_planilla_liquidacion->findAll($this->SQLCA()." and ano_planilla=".$ano_planilla." and numero_planilla='".$numero_planilla."'");
        
        $tipo_ramo=  $this->cstd10_tipo_ramo->generateList(null, 'cod_tipo_ramo ASC', null, '{n}.cstd10_tipo_ramo.cod_tipo_ramo', '{n}.cstd10_tipo_ramo.denominacion');
        $this->concatena($tipo_ramo, 'tipo_ramo');

        $tipo_credito = array("Gastos de Personal" => "Gastos de Personal",
                              "Gastos de Funcionamiento" => "Gastos de Funcionamiento",
                              "Convenios Especiales" => "Convenios Especiales");

        $this->set('operador_1', $this->Session->read('nom_usuario'));
        $this->set('ano_planilla', $ano_planilla);
        $this->set('numero_planilla', $numero_planilla);
        $this->set('tipo_credito', $tipo_credito);
        $this->set('data_planilla', $data_planilla);
        $this->set('enable_delet', false);
    }

    function editar_recaudacion($ano, $n_planilla){

        $this->layout ="ajax";
            $cod_presi = $this->Session->read('SScodpresi');
        $cod_entidad = $this->Session->read('SScodentidad');
        $cod_tipo_inst = $this->Session->read('SScodtipoinst');
        $cod_inst = $this->Session->read('SScodinst');
        $cod_dep = $this->Session->read('SScoddep');
        $this->set('entidad_federal', $this->Session->read('entidad_federal'));

        $ano_planilla = $ano;
        $numero_planilla = $n_planilla;
        $this->set('entidad_federal', $this->Session->read('entidad_federal'));

        $sql = "SELECT * FROM cstd10_planilla_recaudacion WHERE ".$this->SQLCA()." and ano_planilla=".$ano_planilla." and numero_planilla=".$numero_planilla;
            $data = $this->cstd10_planilla_recaudacion->execute($sql);

            $sql_banco = "SELECT * FROM cstd10_planilla_recaudacion_banco WHERE ".$this->SQLCA()." and ano_planilla=".$ano_planilla." and numero_planilla=".$numero_planilla;
            $data_banco = $this->cstd10_planilla_recaudacion->execute($sql_banco);

            $sql_liq = "SELECT * FROM cstd10_planilla_liquidacion WHERE ".$this->SQLCA()." and ano_planilla=".$data[0][0]["ano_planilla_liquidacion"]." and numero_planilla=".$data[0][0]["numero_planilla_liquidacion"];
            $data_liq = $this->cstd10_planilla_liquidacion->execute($sql_liq);

            $sql_banco = "SELECT deno_entidad FROM v_cuentas_bancarias WHERE cuenta_bancaria='".$data[0][0]["cuenta_bancaria"]."'";
            $deno_banco = $this->v_cuentas_bancarias->execute($sql_banco);

            $sql_ramo = "SELECT denominacion FROM cstd10_tipo_ramo WHERE cod_tipo_ramo='".$data_liq[0][0]["cod_tipo_ramo"]."'";
            $deno_ramo = $this->cstd10_tipo_ramo->execute($sql_ramo);

            $idplanilla = $this->mascara_cuatro($data[0][0]["numero_planilla"]);
            $idplanilla_liq = $this->mascara_cuatro($data[0][0]["numero_planilla_liquidacion"]);

            $this->set('idplanilla', $idplanilla);
            $this->set('ano_planilla', $ano_planilla);
            $this->set('numero_planilla', $numero_planilla);
            $this->set('idplanilla_liq', $idplanilla_liq);
            $this->set('tipo_planilla', 2);
            $this->set('data', $data[0][0]);
            $this->set('data_banco', $data_banco);
            $this->set('data_liq', $data_liq[0][0]);
            $this->set('banco', $deno_banco[0][0]["deno_entidad"]);
            $this->set('tipo_ramo', $deno_ramo[0][0]["denominacion"]);
    }

    function ver_recaudacion(){

        $this->layout ="ajax";

            $cod_presi = $this->Session->read('SScodpresi');
            $cod_entidad = $this->Session->read('SScodentidad');
            $cod_tipo_inst = $this->Session->read('SScodtipoinst');
            $cod_inst = $this->Session->read('SScodinst');
            $cod_dep = $this->Session->read('SScoddep');
            $this->set('entidad_federal', $this->Session->read('entidad_federal'));

            $ano_planilla = $this->data['cstp10_planilla_recaudacion']["ano_planilla"];
            $numero_planilla = $this->data['cstp10_planilla_recaudacion']["numero_planilla"];
            
            $sql = "SELECT * FROM cstd10_planilla_recaudacion WHERE ".$this->SQLCA()." and ano_planilla=".$ano_planilla." and numero_planilla=".$numero_planilla;
            $data = $this->cstd10_planilla_recaudacion->execute($sql);

            $sql_banco = "SELECT * FROM cstd10_planilla_recaudacion_banco WHERE ".$this->SQLCA()." and ano_planilla=".$ano_planilla." and numero_planilla=".$numero_planilla;
            $data_banco = $this->cstd10_planilla_recaudacion->execute($sql_banco);

            $sql_liq = "SELECT * FROM cstd10_planilla_liquidacion WHERE ".$this->SQLCA()." and ano_planilla=".$data[0][0]["ano_planilla_liquidacion"]." and numero_planilla=".$data[0][0]["numero_planilla_liquidacion"];
            $data_liq = $this->cstd10_planilla_liquidacion->execute($sql_liq);

            $sql_banco = "SELECT deno_entidad FROM v_cuentas_bancarias WHERE cuenta_bancaria='".$data[0][0]["cuenta_bancaria"]."'";
            $deno_banco = $this->v_cuentas_bancarias->execute($sql_banco);

            $sql_ramo = "SELECT denominacion FROM cstd10_tipo_ramo WHERE cod_tipo_ramo='".$data_liq[0][0]["cod_tipo_ramo"]."'";
            $deno_ramo = $this->cstd10_tipo_ramo->execute($sql_ramo);

            $idplanilla = $this->mascara_cuatro($data[0][0]["numero_planilla"]);
            $idplanilla_liq = $this->mascara_cuatro($data[0][0]["numero_planilla_liquidacion"]);

            $this->set('idplanilla', $idplanilla);
            $this->set('ano_planilla', $ano_planilla);
            $this->set('numero_planilla', $numero_planilla);
            $this->set('idplanilla_liq', $idplanilla_liq);
            $this->set('tipo_planilla', 2);

            $this->set('data', $data[0][0]);
            $this->set('data_banco', $data_banco);
            $this->set('data_liq', $data_liq[0][0]);
            $this->set('banco', $deno_banco[0][0]["deno_entidad"]);
            $this->set('tipo_ramo', $deno_ramo[0][0]["denominacion"]);
       
    }

    function tipo_credito($tipo_credito){

        if($tipo_credito=="Gastos de Personal"){
            $tipo_sub_credito = array("1" => "Servicio de Seguridad y Protección Ciudadana",
                                  "2" => "Gastos de Personal");
        
            $this->set('tipo_sub_credito', $tipo_sub_credito);
        }

        $this->set('tipo_credito', $tipo_credito);
    }

    function recaudacion(){

        $this->layout ="ajax";
        $this->set('entidad_federal', $this->Session->read('entidad_federal'));

        $ano_planilla = $this->ano_ejecucion();
        $bancos = $this->v_cstd01_bancos->execute("SELECT a.cod_entidad_bancaria, a.denominacion
         FROM v_cstd01_bancos as a 
         INNER JOIN cstd10_cuentas_bancarias as b ON a.cod_dep=b.cod_dep and a.cod_entidad_bancaria=b.cod_entidad_bancaria
         GROUP BY a.cod_entidad_bancaria, a.denominacion
         ORDER BY a.cod_entidad_bancaria ASC");
        
        $lista_banco = array();
        foreach ($bancos as $key) {
            $lista_banco[$key[0]["cod_entidad_bancaria"]] = $key[0]["denominacion"];
        }

        $this->concatena_cuatro_digitos($lista_banco,'direccion_superior');
      
        $planilla = $this->comboBox("v_cstd10_planilla_por_recaudar", "numero_planilla", "dependencia_ciudadano", $this->SQLCA()." and ano_planilla=".$ano_planilla);

        $this->concatena($planilla, 'planilla_liquidacion');
        
        $max = $this->cstd10_planilla_recaudacion->execute("SELECT MAX(numero_planilla) as numero_planilla FROM cstd10_planilla_recaudacion WHERE ".$this->SQLCA().' and ano_planilla='.$ano_planilla);
       if($max[0][0]["numero_planilla"]==""){
          $numero_planilla=1;
       }else{
          $numero_planilla=$max[0][0]["numero_planilla"]+1;
       }

        $this->set('operador_1', $this->Session->read('nom_usuario'));
        $this->set('ano_planilla', $ano_planilla);
        $this->set('numero_planilla', $numero_planilla);
    }

    function guardar_sesion_cuentas()
    {
        $this->layout = "ajax";
        $cod_presi=$this->verifica_SS(1);
        $cod_entidad=$this->verifica_SS(2);
        $cod_tipo_inst=$this->verifica_SS(3);
        $cod_inst=$this->verifica_SS(4);
        $cod_dep=$this->verifica_SS(5);

        $numero_planilla             = $this->data['cstp10_planilla_recaudacion']['numero_planilla'];
        $cod_entidad_bancaria        = $this->data['cstp10_planilla_recaudacion']['cod_entidad_bancaria'];
        $cod_sucursal                = $this->data['cstp10_planilla_recaudacion']['cod_sucursal'];
        $cuenta_bancaria             = $this->data['cstp10_planilla_recaudacion']['cuenta_bancaria'];
        $numero_transaccion          = $this->data['cstp10_planilla_recaudacion']['numero_deposito'];
        $fecha_transaccion           = $this->data['cstp10_planilla_recaudacion']['fecha_deposito'];
        
        
        if(isset($_SESSION["planilla_recaudacion"]["numero"]) && $_SESSION["planilla_recaudacion"]["numero"]==$numero_planilla)
        {
          $_SESSION["planilla_recaudacion"]["count"]++;
          $count = $_SESSION["planilla_recaudacion"]["count"];
          $_SESSION["planilla_recaudacion"]["cod_bancaria_".$count]=$cod_entidad_bancaria;
          $_SESSION["planilla_recaudacion"]["cod_sucursal_".$count]=$cod_sucursal;
          $_SESSION["planilla_recaudacion"]["cuenta_bancaria_".$count]=$cuenta_bancaria;
          $_SESSION["planilla_recaudacion"]["numero_transaccion_".$count]=$numero_transaccion;
          $_SESSION["planilla_recaudacion"]["fecha_transaccion_".$count]=$fecha_transaccion;
        }
        else
        {
          $_SESSION["planilla_recaudacion"]["numero"]=$numero_planilla;
          $_SESSION["planilla_recaudacion"]["cod_bancaria_1"]=$cod_entidad_bancaria;
          $_SESSION["planilla_recaudacion"]["cod_sucursal_1"]=$cod_sucursal;
          $_SESSION["planilla_recaudacion"]["cuenta_bancaria_1"]=$cuenta_bancaria;
          $_SESSION["planilla_recaudacion"]["numero_transaccion_1"]=$numero_transaccion;
          $_SESSION["planilla_recaudacion"]["fecha_transaccion_1"]=$fecha_transaccion;
          $_SESSION["planilla_recaudacion"]["count"]=1;
        }
        $this->set('numero_planilla', $numero_planilla);

    }

    function limpiar_sesion_cuentas(){
      $this->layout = "ajax";
      $_SESSION["planilla_recaudacion"]["count"]=0;
      $this->render("guardar_sesion_cuentas");
    }

    function consultar_planilla_liquidacion ($var = null){

        $this->layout ="ajax";
        $ano_planilla = $this->ano_ejecucion();
        
        $planilla = $this->comboBox("cstd10_planilla_liquidacion", "numero_planilla", "dependencia_ciudadano", $this->SQLCA()." and ano_planilla=".$ano_planilla);

        $planilla = $planilla != null ? $planilla : array();
        
        $this->set('ano_planilla', $ano_planilla);
        $this->concatena($planilla, 'numero_planilla');
    }

    function consultar_planilla_recaudacion ($var = null){

        $this->layout ="ajax";
        $ano_planilla = $this->ano_ejecucion();
        
        $planilla = $this->comboBox("v_cstd10_planilla_recaudacion", "numero_planilla", "dependencia_ciudadano", $this->SQLCA()." and ano_planilla=".$ano_planilla);

        $planilla = $planilla != null ? $planilla : array();

        $this->set('ano_planilla', $ano_planilla);
        $this->concatena($planilla, 'numero_planilla');
    }

    function consultar_recaudacion (){

        $this->layout ="ajax";
        $ano_planilla = $this->ano_ejecucion();
        
        $planilla = $this->comboBox("v_cstd10_planilla_recaudacion", "numero_planilla", "dependencia_ciudadano", $this->SQLCA()." and ano_planilla=".$ano_planilla);

        $planilla = $planilla != null ? $planilla : array();

        $this->set('ano_planilla', $ano_planilla);
        $this->concatena($planilla, 'numero_planilla');
    }

    function buscar_ano_recaudacion ($year){

        $this->layout ="ajax";
       
        $planilla = $this->comboBox("v_cstd10_planilla_recaudacion", "numero_planilla", "dependencia_ciudadano", $this->SQLCA()." and ano_planilla=".$year);

        $planilla = $planilla != null ? $planilla : array();
        $this->concatena($planilla, 'numero_planilla');
    }

    function mostrar_planilla($liquidacion=null, $campo=null, $numero_planilla=null){
        if($liquidacion==null || $campo==null || $numero_planilla==null)
        {
            echo "<input type='text' name='data[cstp10_planilla_recaudacion][".$campo."]' maxlength='150' id='".$campo."' readonly='readonly' class='inputtext' value='' />";
        }
        else
        {
            $cond = $this->SQLCA()." and ano_planilla=".$this->ano_ejecucion()." and numero_planilla=".$numero_planilla;
            switch ($liquidacion) {
                case 'liquidacion':
                    $vector=  $this->cstd10_planilla_liquidacion->execute("SELECT ".$campo." FROM cstd10_planilla_liquidacion WHERE ".$cond);
                    break;
                
                case 'recaudacion':
                    $vector=  $this->cstd10_planilla_recaudacion->execute("SELECT ".$campo." FROM v_cstd10_planilla_recaudacion WHERE ".$cond);
                    break;
            }

            if($campo=='monto')
            {
                echo "<input type='text' name='data[cstp10_planilla_recaudacion][".$campo."]' maxlength='150' id='".$campo."' readonly='readonly' class='inputtext' value='".$this->formato2($vector[0][0][$campo])."' />";
            }
            else
            {
                echo "<input type='text' name='data[cstp10_planilla_recaudacion][".$campo."]' maxlength='150' id='".$campo."' readonly='readonly' class='inputtext' value='".$vector[0][0][$campo]."' />";   
            }
        }

        $this->render("mostrar");
    }

    function select($select=null,$var=null,$var2=null) { //select codigos
        $this->layout = "ajax";

        if($var!=null){
        $cond =$this->SQLCA();//vario
        switch($select){
            case 'sucursal':
              $this->set('codigo','sucursal');
              $this->set('codigo_entidad',$var);
              $this->set('codigo_sucursal',$var2);
              $this->set('select','cuenta_bancaria');
              $this->set('mostrar','sucursal');
              $this->set('update1','st_cuenta_bancaria');
              $this->set('update2','div_deno_sucursal');
              $busca="a.cod_dep=".$this->verifica_SS(5)." and a.cod_entidad_bancaria=".$var;
              $bancos = $this->v_cstd01_bancos->execute("SELECT a.cod_sucursal, a.denominacion
                 FROM v_cstd01_sucursales as a 
                 INNER JOIN cstd10_cuentas_bancarias as b ON a.cod_dep=b.cod_dep and a.cod_entidad_bancaria=b.cod_entidad_bancaria and a.cod_sucursal=b.cod_sucursal
                 WHERE ".$busca."
                 GROUP BY a.cod_sucursal, a.denominacion
                 ORDER BY a.cod_sucursal ASC");
        
                $lista_banco = array();
                foreach ($bancos as $key) {
                    $lista_banco[$key[0]["cod_sucursal"]] = $key[0]["denominacion"];
                }

                $this->concatena_cuatro_digitos($lista_banco,'vector');
            break;
            case 'cuenta_bancaria':
              $this->set('codigo','cuenta_bancaria');
              $this->set('codigo_entidad',$var);
              $this->set('codigo_sucursal',$var2);
              $this->set('select','');
              $this->set('mostrar','');
              $this->set('update1','');
              $this->set('update2','');
              $busca="a.cod_dep=".$this->verifica_SS(5)." and a.cod_entidad_bancaria=".$var." and a.cod_sucursal=".$var2;
              $bancos = $this->v_cstd01_bancos->execute("SELECT a.cuenta_bancaria, a.concepto_manejo
                 FROM v_cuentas_bancarias as a 
                 INNER JOIN cstd10_cuentas_bancarias as b ON a.cod_dep=b.cod_dep and a.cod_entidad_bancaria=b.cod_entidad_bancaria and a.cod_sucursal=b.cod_sucursal and a.cuenta_bancaria=b.cuenta_bancaria
                 WHERE ".$busca."
                 GROUP BY a.cuenta_bancaria, a.concepto_manejo
                 ORDER BY a.cuenta_bancaria ASC");
        
                $lista_banco = array();
                foreach ($bancos as $key) {
                    $lista_banco[$key[0]["cuenta_bancaria"]] = $key[0]["concepto_manejo"];
                }

                $this->concatena_cuatro_digitos($lista_banco,'vector');
            break;
            case 'otros':
                $this->set('codigo','otros');
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
    }//fin select codigos

    function otros($otros=null) { //select codigos
        $this->layout = "ajax";

        $cod_tipo_inst = $this->Session->read('SScodtipoinst');
        $cod_inst = $this->Session->read('SScodinst');
        switch($otros){
            case 'agregar':
                $this->set('codigo',$otros);
                break;
            default:
                $this->set('codigo',$otros);
                $lista = $this->comboBox("cstd10_planilla_liquidacion", "dependencia_ciudadano", "dependencia_ciudadano", $this->SQLCA());
                $lista = $lista != null ? $lista : array();
                $this->concatena($lista, 'entidad');
                break;
        }//fin switch
        
    }//fin select codigos

    function mostrar($select=null,$var=null,$var2=null) {
        $this->layout = "ajax";

        if( $var!=null){
        switch($select){
            case 'entidad_bancaria':
              $cond ="cod_entidad_bancaria=".$var;
              $a=  $this->cstd01_entidades_bancarias->findAll($cond);
              echo "<input type='text' name='data[cstp10_planilla_recaudacion][deno_entidad_bancaria]' maxlength='150' id='deno_entidad_bancaria' value='".$this->mascara3($a[0]['cstd01_entidades_bancarias']['denominacion'])."' size='5'  maxlength='4' readonly='readonly' class='inputtext' style='text-align:center' />";
            break;
            case 'sucursal':
                if(!isset($var) || !isset($var2)){
                    echo "<input type='text' name='data[cstp10_planilla_recaudacion][deno_sucursal_bancaria]' value='' size='5' maxlength='4' id='deno_sucursal_bancaria' readonly='readonly' class='inputtext' style='text-align:center' />";
                }else{
                    $cond ="cod_entidad_bancaria=".$var." and cod_sucursal=".$var2;
                    $a=  $this->cstd01_sucursales_bancarias->findAll($cond);
                    echo "<input type='text' name='data[cstp10_planilla_recaudacion][deno_sucursal_bancaria]' value='".$this->mascara3($a[0]['cstd01_sucursales_bancarias']['denominacion'])."' id='deno_sucursal_bancaria' size='5' maxlength='4'  readonly='readonly' class='inputtext' style='text-align:center' />";
                }
            break;
          }//fin switch
        }else{
            echo "<input type='text' name='data[cstp10_planilla_recaudacion]' size='5' maxlength='4' id='empty_entidad_bancaria' class='inputtext' style='text-align:center' />";
        }
    }//fin mostrar codigos

    function codigo_planilla_liquidacion($numero_planilla){

        $this->layout = "ajax";
        $cond = $this->SQLCA()." and ano_planilla=".$this->ano_ejecucion()." and numero_planilla=".$numero_planilla;
       
        $liquidacion=  $this->cstd10_planilla_liquidacion->execute("SELECT monto FROM cstd10_planilla_liquidacion WHERE ".$cond);
        
        echo "<input type='text' name='data[cstp10_planilla_recaudacion][monto_liquidacion]' maxlength='150' id='monto_liquidacion' readonly='readonly' class='inputtext' style='text-align:center' value='".$this->Formato2($liquidacion[0][0]["monto"])."' />";
        $this->render("mostrar");
    }

    function nombre_planilla_liquidacion($numero_planilla){

        $this->layout = "ajax";
        $cond = $this->SQLCA()." and ano_planilla=".$this->ano_ejecucion()." and numero_planilla=".$numero_planilla;

        $a=  $this->cstd10_planilla_liquidacion->execute("SELECT dependencia_ciudadano FROM cstd10_planilla_liquidacion WHERE ".$cond);

        echo "<input type='text' name='data[cstp10_planilla_recaudacion][ciudadano]' id='ciudadano' readonly='readonly' class='inputtext' style='text-align:center' value='".$a[0][0]["dependencia_ciudadano"]."' readonly='readonly' />";

        $this->render("mostrar");
    }

    function monto_por_recaudar($numero_planilla){

        $this->layout = "ajax";
        $cond = $this->SQLCA()." and ano_planilla=".$this->ano_ejecucion()." and numero_planilla=".$numero_planilla;
        $cond2 = $this->SQLCA()." and ano_planilla_liquidacion=".$this->ano_ejecucion()." and numero_planilla_liquidacion=".$numero_planilla;

        $recaudacion=  $this->cstd10_planilla_liquidacion->execute("SELECT SUM(monto) as monto FROM cstd10_planilla_recaudacion WHERE ".$cond2);

        if($recaudacion != null)
        {
            $monto_recaudacion =  $recaudacion[0][0]["monto"];
        }
        else
        {
            $monto_recaudacion = "0.00";
        }
        
        $liquidacion=  $this->cstd10_planilla_liquidacion->execute("SELECT monto FROM cstd10_planilla_liquidacion WHERE ".$cond);

        $monto_liquidacion = $liquidacion[0][0]["monto"];

        $monto_por_recaudar = $monto_liquidacion - $monto_recaudacion;

        echo "<input type='text' name='data[cstp10_planilla_recaudacion][monto_por_recaudar]' maxlength='150' id='monto_por_recaudar' readonly='readonly' class='inputtext' style='text-align:center' value='".$this->Formato2($monto_por_recaudar)."' />";
        $this->render("mostrar");
    }

    function guardar_planilla_liquidacion(){
        $this->layout = "ajax";
        $cod_presi=$this->verifica_SS(1);
        $cod_entidad=$this->verifica_SS(2);
        $cod_tipo_inst=$this->verifica_SS(3);
        $cod_inst=$this->verifica_SS(4);
        $cod_dep=$this->verifica_SS(5);

        $entidad = '';
        $ciudadano = '';
        $cedula_identidad = 'NULL';

        $ano_planilla              = $this->data['cstp10_planilla_recaudacion']['ano_planilla'];
        $numero_planilla           = $this->data['cstp10_planilla_recaudacion']['numero_planilla'];
        $fecha_planilla            = cambiar_formato_fecha($this->data['cstp10_planilla_recaudacion']['fecha_planilla']);
        $tipo_acto                 = $this->data['cstp10_planilla_recaudacion']['tipo_acto_administrativo'];

        if($tipo_acto!=1)
        {
            $entidad               = $this->data['cstp10_planilla_recaudacion']['entidad'];
            $numero_acto           = $this->data['cstp10_planilla_recaudacion']['numero_decreto_acto'];
            $fecha_acto            = cambiar_formato_fecha($this->data['cstp10_planilla_recaudacion']['fecha_acto']);
            $monto                 = $this->Formato1($this->data['cstp10_planilla_recaudacion']['monto_acto']);
        }
        else
        {
            $ciudadano             = $this->data['cstp10_planilla_recaudacion']['ciudadano'];
            $cedula_identidad      = $this->data['cstp10_planilla_recaudacion']['cedula_identidad'];
            $numero_acto           = $this->data['cstp10_planilla_recaudacion']['numero_decreto_multa'];
            $fecha_acto            = cambiar_formato_fecha($this->data['cstp10_planilla_recaudacion']['fecha_acto_multa']);
            $monto                 = $this->Formato1($this->data['cstp10_planilla_recaudacion']['monto_acto_multa']);
        }

        $concepto                  = $this->data['cstp10_planilla_recaudacion']['concepto'];
        $cod_tipo_ramo             = $this->data['cstp10_planilla_recaudacion']['tipo_ramo'];
        $partida                   = $this->data['cstp10_planilla_recaudacion']['partida'];

        if($this->data['cstp10_planilla_recaudacion']['tipo_ramo']=='7' && isset($this->data['cstp10_planilla_recaudacion']['credito_adicional']))
        {
            if($this->data['cstp10_planilla_recaudacion']['tipo_credito']=="GASTOS DE PERSONAL")
            {
                $tipo_credito      = $this->data['cstp10_planilla_recaudacion']['tipo_sub_credito'];
            }
            else if($this->data['cstp10_planilla_recaudacion']['tipo_credito']=="GASTOS DE FUNCIONAMIENTO")
            {
                $tipo_credito      = 3;
            }
            else if($this->data['cstp10_planilla_recaudacion']['tipo_credito']=="CONVENIOS ESPECIALES")
            {
                $tipo_credito      = 4;
            }
            else
            {
                $tipo_credito = -1;
            }
        }
        else
        {
            $tipo_credito = -1;
        }

        if(isset($this->data['cstp10_planilla_recaudacion']['excedente']))
        {
            $excedente = 'TRUE';
        }
        else
        {
            $excedente = 'FALSE';
        }
        
        $motivado                  = $this->data['cstp10_planilla_recaudacion']['motivado'];
        if($motivado=='')
        {
            $motivado = "NULL";
        }else
        {
          $motivado= "'".$motivado."'";
        }
        
        if($entidad!='')
        {
            $dependencia_ciudadano = $entidad;
        }
        else
        {
            $dependencia_ciudadano = $ciudadano;
        }

        //Buscar Firmantes y guardar firmantes
        $firmantes = $this->cstd10_firmantes->execute("SELECT * FROM cstd10_firmantes WHERE ".$this->SQLCA());
        
        if(count($firmantes)>0)
        {
            
            $CAMPOS_CUERPO = "cod_presi,cod_entidad,cod_tipo_inst,cod_inst,cod_dep,ano_planilla,numero_planilla,fecha_planilla,tipo_acto,
            numero_acto,fecha_acto,dependencia_ciudadano,cedula_identidad,monto,concepto,cod_tipo_ramo,partida,tipo_credito,motivado,excedentes";

            $VALUES_CUERPO=$cod_presi.",".$cod_entidad.",".$cod_tipo_inst.",".$cod_inst.",".$cod_dep.",".$ano_planilla.",".$numero_planilla.",'".$fecha_planilla."',".$tipo_acto.",
            '".$numero_acto."','".$fecha_acto."','".$dependencia_ciudadano."',".$cedula_identidad.",".$monto.",'".$concepto."',".$cod_tipo_ramo.",'".$partida."',".$tipo_credito.",".$motivado.",".$excedente;
            
            $this->cstd10_planilla_liquidacion->execute("BEGIN;");

              $resultado=$this->cstd10_planilla_liquidacion->execute("INSERT INTO cstd10_planilla_liquidacion ($CAMPOS_CUERPO) VALUES($VALUES_CUERPO)");


            if($resultado>1){
                $this->cstd10_planilla_liquidacion->execute("COMMIT;");
                $this->set('Message_existe', 'LOS DATOS FUERON GUARDADOS EXITOSAMENTE.');
                echo"<script>menu_activo();</script>";
                $this->render("mostrar");
            }else{
                $this->cstd10_planilla_liquidacion->execute("ROLLBACK;");
                $this->set('errorMessage', 'LOS DATOS NO FUERON GUARDADOS.');
               // $this->liquidacion();
                echo"<script>menu_activo();</script>";
                $this->render("mostrar");
            }
        }
        else
        {
            $this->set('errorMessage', 'DEBE REGISTRAR LOS FIRMANTES.');
           // $this->liquidacion();
            echo"<script>menu_activo();</script>";
            $this->render("mostrar");
        }
    }

     function actualizar_planilla_liquidacion($ano_planilla, $numero_planilla){

        $this->layout = "ajax";

        $concepto                  = $this->data['cstp10_planilla_recaudacion']['concepto'];
        $motivado                  = $this->data['cstp10_planilla_recaudacion']['motivado'];

        $sql="UPDATE cstd10_planilla_liquidacion SET concepto='$concepto', motivado='$motivado' WHERE ".$this->SQLCA()." and ano_planilla=$ano_planilla and numero_planilla=$numero_planilla";
        
        $this->cstd10_planilla_liquidacion->execute("BEGIN;");

        $resultado=$this->cstd10_planilla_liquidacion->execute($sql);

        if($resultado>1){
            $this->cstd10_planilla_liquidacion->execute("COMMIT;");
            $this->set('Message_existe', 'LOS DATOS FUERON GUARDADOS EXITOSAMENTE.');
            echo"<script>menu_activo();</script>";
            $this->render("mostrar");
        }else{
            $this->cstd10_planilla_liquidacion->execute("ROLLBACK;");
            $this->set('errorMessage', 'LOS DATOS NO FUERON GUARDADOS.');
            // $this->liquidacion();
            echo"<script>menu_activo();</script>";
            $this->render("mostrar");
        }
    }

    function actualizar_planilla_recaudacion($ano_planilla, $numero_planilla){

        $this->layout = "ajax";

        $concepto                  = $this->data['cstp10_planilla_recaudacion']['concepto'];
        $motivado                  = $this->data['cstp10_planilla_recaudacion']['motivado'];

        $sql="UPDATE cstd10_planilla_recaudacion SET concepto='$concepto', motivado='$motivado' WHERE ".$this->SQLCA()." and ano_planilla=$ano_planilla and numero_planilla=$numero_planilla";
        
        $this->cstd10_planilla_liquidacion->execute("BEGIN;");

        $resultado=$this->cstd10_planilla_liquidacion->execute($sql);

        if($resultado>1){
            $this->cstd10_planilla_liquidacion->execute("COMMIT;");
            $this->set('Message_existe', 'LOS DATOS FUERON GUARDADOS EXITOSAMENTE.');
            echo"<script>menu_activo();</script>";
            $this->render("mostrar");
        }else{
            $this->cstd10_planilla_liquidacion->execute("ROLLBACK;");
            $this->set('errorMessage', 'LOS DATOS NO FUERON GUARDADOS.');
            // $this->liquidacion();
            echo"<script>menu_activo();</script>";
            $this->render("mostrar");
        }
    }

    function guardar_planilla_recaudacion(){
        $this->layout = "ajax";
        $cod_presi=$this->verifica_SS(1);
        $cod_entidad=$this->verifica_SS(2);
        $cod_tipo_inst=$this->verifica_SS(3);
        $cod_inst=$this->verifica_SS(4);
        $cod_dep=$this->verifica_SS(5);

        $ano_planilla                = $this->data['cstp10_planilla_recaudacion']['ano_planilla'];
        $numero_planilla             = $this->data['cstp10_planilla_recaudacion']['numero_planilla'];
        $fecha_planilla              = cambiar_formato_fecha($this->data['cstp10_planilla_recaudacion']['fecha_planilla']);
        $numero_planilla_liquidacion = $this->data['cstp10_planilla_recaudacion']['numero_planilla_liquidacion'];
        $monto                       = $this->Formato1($this->data['cstp10_planilla_recaudacion']['monto']);
        $concepto                    = $this->data['cstp10_planilla_recaudacion']['concepto'];
        $cod_entidad_bancaria        = $this->data['cstp10_planilla_recaudacion']['cod_entidad_bancaria'];
        $deno_entidad_bancaria       = $this->data['cstp10_planilla_recaudacion']['deno_entidad_bancaria'];
        $cod_sucursal                = $this->data['cstp10_planilla_recaudacion']['cod_sucursal'];
        $deno_sucursal_bancaria      = $this->data['cstp10_planilla_recaudacion']['deno_sucursal_bancaria'];
        $cuenta_bancaria             = $this->data['cstp10_planilla_recaudacion']['cuenta_bancaria'];
        $numeros_transacciones       = $this->data['cstp10_planilla_recaudacion']['numero_deposito'];
        $fechas_transacciones        = $this->data['cstp10_planilla_recaudacion']['fecha_deposito'];
        $motivado                    = $this->data['cstp10_planilla_recaudacion']['motivado'];

        if($motivado=='')
        {
            $motivado = "NULL";
        }else
        {
          $motivado= "'".$motivado."'";
        }

        //cambiar_formato_fecha($fecha)
        $firmantes = $this->cstd10_firmantes->execute("SELECT * FROM cstd10_firmantes WHERE ".$this->SQLCA());

        if(count($firmantes)>0)
        {

            $CAMPOS_CUERPO = "cod_presi,cod_entidad,cod_tipo_inst,cod_inst,cod_dep,ano_planilla,numero_planilla,fecha_planilla,ano_planilla_liquidacion,numero_planilla_liquidacion,monto,concepto,cod_entidad_bancaria,cod_sucursal,cuenta_bancaria,numeros_transacciones,fechas_transacciones, motivado";

            $VALUES_CUERPO=$cod_presi.",".$cod_entidad.",".$cod_tipo_inst.",".$cod_inst.",".$cod_dep.",".$ano_planilla.",".$numero_planilla.",'".$fecha_planilla."',".$ano_planilla.",".$numero_planilla_liquidacion.",".$monto.",'".$concepto."',".$cod_entidad_bancaria.",".$cod_sucursal.",'".$cuenta_bancaria."','".$numeros_transacciones."','".$fechas_transacciones."',".$motivado;

            $this->cstd10_planilla_recaudacion->execute("BEGIN;");

              $resultado=$this->cstd10_planilla_recaudacion->execute("INSERT INTO cstd10_planilla_recaudacion ($CAMPOS_CUERPO) VALUES($VALUES_CUERPO)");

            if($_SESSION["planilla_recaudacion"]["numero"]==$numero_planilla){
              for ($i=1; $i <= $_SESSION["planilla_recaudacion"]["count"]; $i++) { 
          
                $VALUES_CUERPO_BANCO=$cod_presi.",".$cod_entidad.",".$cod_tipo_inst.",".$cod_inst.",".$cod_dep.",".$ano_planilla.",".$numero_planilla.",".$_SESSION["planilla_recaudacion"]["cod_bancaria_".$i].",".$_SESSION["planilla_recaudacion"]["cod_sucursal_".$i].",'".$_SESSION["planilla_recaudacion"]["cuenta_bancaria_".$i]."','".$_SESSION["planilla_recaudacion"]["numero_transaccion_".$i]."','".$_SESSION["planilla_recaudacion"]["fecha_transaccion_".$i]."'";
                
                $resultado2=$this->cstd10_planilla_recaudacion->execute("INSERT INTO cstd10_planilla_recaudacion_banco VALUES($VALUES_CUERPO_BANCO)");
              }
            }

            if($resultado>1){
                $this->cstd10_planilla_recaudacion->execute("COMMIT;");
                $this->set('Message_existe', 'LOS DATOS FUERON GUARDADOS EXITOSAMENTE.');
                echo"<script>menu_activo();</script>";
                $this->render("mostrar");
            }else{
                $this->cstd10_planilla_recaudacion->execute("ROLLBACK;");
                $this->set('errorMessage', 'LOS DATOS NO FUERON GUARDADOS.');
                echo"<script>menu_activo();</script>";
                $this->render("mostrar");
            }
        }
        else
        {
            $this->set('errorMessage', 'DEBE REGISTRAR LOS FIRMANTES.');
                echo"<script>menu_activo();</script>";
                $this->render("mostrar");
        }

    }

    function generar_planilla_pdf(){
        $this->layout ="pdf";

        $tipo            = $this->data['cstp10_planilla_recaudacion']['planilla'];
        $ano_planilla    = $this->data['cstp10_planilla_recaudacion']['ano_planilla'];
        $numero_planilla = $this->data['cstp10_planilla_recaudacion']['numero_planilla'];

        if($tipo==1)
        {

            $sql = "SELECT * FROM cstd10_planilla_liquidacion WHERE ".$this->SQLCA()." and ano_planilla=".$ano_planilla." and numero_planilla=".$numero_planilla;
            $data = $this->cstd10_planilla_liquidacion->execute($sql);

            $sql_banco = "SELECT deno_entidad FROM v_cuentas_bancarias WHERE cuenta_bancaria='".$data[0][0]["cuenta_bancaria"]."'";
            $deno_banco = $this->v_cuentas_bancarias->execute($sql_banco);

            $sql_ramo = "SELECT denominacion FROM cstd10_tipo_ramo WHERE cod_tipo_ramo='".$data[0][0]["cod_tipo_ramo"]."'";
            $deno_ramo = $this->cstd10_tipo_ramo->execute($sql_ramo);

            $idplanilla = $this->mascara_cuatro($data[0][0]["numero_planilla"]);

            $this->set('idplanilla', $idplanilla);
            //esto cambia si se genero mediante una liquidacion, una recaudacion
            $this->set('tipo_planilla', 1);

            $this->set('data', $data[0][0]);
            $this->set('tipo_ramo', $deno_ramo[0][0]["denominacion"]);
        }
        else if($tipo==2)
        {

            $sql = "SELECT * FROM cstd10_planilla_recaudacion WHERE ".$this->SQLCA()." and ano_planilla=".$ano_planilla." and numero_planilla=".$numero_planilla;
            $data = $this->cstd10_planilla_recaudacion->execute($sql);

            $sql_banco = "SELECT * FROM cstd10_planilla_recaudacion_banco WHERE ".$this->SQLCA()." and ano_planilla=".$ano_planilla." and numero_planilla=".$numero_planilla;
            $data_banco = $this->cstd10_planilla_recaudacion->execute($sql_banco);

            $sql_liq = "SELECT * FROM cstd10_planilla_liquidacion WHERE ".$this->SQLCA()." and ano_planilla=".$data[0][0]["ano_planilla_liquidacion"]." and numero_planilla=".$data[0][0]["numero_planilla_liquidacion"];
            $data_liq = $this->cstd10_planilla_liquidacion->execute($sql_liq);

            $sql_banco = "SELECT deno_entidad FROM v_cuentas_bancarias WHERE cuenta_bancaria='".$data[0][0]["cuenta_bancaria"]."'";
            $deno_banco = $this->v_cuentas_bancarias->execute($sql_banco);

            $sql_ramo = "SELECT denominacion FROM cstd10_tipo_ramo WHERE cod_tipo_ramo='".$data_liq[0][0]["cod_tipo_ramo"]."'";
            $deno_ramo = $this->cstd10_tipo_ramo->execute($sql_ramo);

            $idplanilla = $this->mascara_cuatro($data[0][0]["numero_planilla"]);
            $idplanilla_liq = $this->mascara_cuatro($data[0][0]["numero_planilla_liquidacion"]);

            $this->set('idplanilla', $idplanilla);
            $this->set('idplanilla_liq', $idplanilla_liq);
            $this->set('tipo_planilla', 2);

            $this->set('data', $data[0][0]);
            $this->set('data_banco', $data_banco);
            $this->set('data_liq', $data_liq[0][0]);
            $this->set('banco', $deno_banco[0][0]["deno_entidad"]);
            $this->set('tipo_ramo', $deno_ramo[0][0]["denominacion"]);

        }
        else
        {
            $this->set('tipo_planilla', 0);
        }
        
    }

    function firmantes_planilla(){
        $this->layout ="ajax";

        $firmantes = $this->cstd10_firmantes->execute("SELECT * FROM cstd10_firmantes WHERE ".$this->SQLCA());
        $firmantes = $firmantes[0][0];

        $this->set('firmantes', $firmantes);
    }

    function guardar_firmas(){
        $this->layout ="ajax";
        $cod_presi=$this->verifica_SS(1);
        $cod_entidad=$this->verifica_SS(2);
        $cod_tipo_inst=$this->verifica_SS(3);
        $cod_inst=$this->verifica_SS(4);
        $cod_dep=$this->verifica_SS(5);

        $nombre_primera_firma    = $this->data['cstp10_planilla_recaudacion']['nombre_primera_firma'];
        $cedula_primera_firma    = $this->data['cstp10_planilla_recaudacion']['cedula_primera_firma'];
        $cargo_primera_firma     = $this->data['cstp10_planilla_recaudacion']['cargo_primera_firma'];
        $decreto_primera_firma   = $this->data['cstp10_planilla_recaudacion']['decreto_primera_firma'];
        $nombre_segunda_firma    = $this->data['cstp10_planilla_recaudacion']['nombre_segunda_firma'];
        $cedula_segunda_firma    = $this->data['cstp10_planilla_recaudacion']['cedula_segunda_firma'];
        $cargo_segunda_firma     = $this->data['cstp10_planilla_recaudacion']['cargo_segunda_firma'];
        $decreto_segunda_firma   = $this->data['cstp10_planilla_recaudacion']['decreto_segunda_firma'];

        
        $firmantes = $this->cstd10_firmantes->execute("SELECT * FROM cstd10_firmantes WHERE ".$this->SQLCA());

        $this->cstd10_firmantes->execute("BEGIN;");
        if($firmantes == null)
        {
            $CAMPOS_CUERPO = "cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, primer_funcionario, 
            primer_cedula, primer_cargo, primer_decreto, segundo_funcionario, 
            segundo_cedula, segundo_cargo, segundo_decreto";

            $VALUES_CUERPO=$cod_presi.",".$cod_entidad.",".$cod_tipo_inst.",".$cod_inst.",".$cod_dep.",'".$nombre_primera_firma."','".$cedula_primera_firma."','".$cargo_primera_firma."','".$decreto_primera_firma."','".$nombre_segunda_firma."','".$cedula_segunda_firma."','".$cargo_segunda_firma."','".$decreto_segunda_firma."'";
            $resultado=$this->cstd10_firmantes->execute("INSERT INTO cstd10_firmantes ($CAMPOS_CUERPO) VALUES($VALUES_CUERPO)");
        }
        else
        {
            $CAMPOS_CUERPO = "primer_funcionario='$nombre_primera_firma', primer_cedula='$cedula_primera_firma', primer_cargo='$cargo_primera_firma', primer_decreto='$decreto_primera_firma', segundo_funcionario='$nombre_segunda_firma', segundo_cedula='$cedula_segunda_firma', segundo_cargo='$cargo_segunda_firma', segundo_decreto='$decreto_segunda_firma'";
            $resultado=$this->cstd10_firmantes->execute("UPDATE cstd10_firmantes SET $CAMPOS_CUERPO WHERE ".$this->SQLCA());
        }
   


        if($resultado>1){
            $this->cstd10_firmantes->execute("COMMIT;");
            $this->set('Message_existe', 'LOS DATOS FUERON GUARDADOS EXITOSAMENTE.');
            echo"<script>menu_activo();</script>";
            $this->render("mostrar");
        }else{
            $this->cstd10_firmantes->execute("ROLLBACK;");
            $this->set('errorMessage', 'LOS DATOS NO FUERON GUARDADOS.');
            $this->firmantes_planilla();
            $this->render("firmantes_planilla");
        }

    }

}//fin clase
?>
