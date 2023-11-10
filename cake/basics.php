<?php
/**
 * Basic defines for timing functions.
 */
	define('SECOND', 1);
	define('MINUTE', 60 * SECOND);
	define('HOUR', 60 * MINUTE);
	define('DAY', 24 * HOUR);
	define('WEEK', 7 * DAY);
	define('MONTH', 30 * DAY);
	define('YEAR', 365 * DAY);
/**
 * Patch for PHP < 4.3
 */
	if (!function_exists("ob_get_clean")) {
		function ob_get_clean() {
			$ob_contents = ob_get_contents();
			ob_end_clean();
			return $ob_contents;
		}
	}
/**
 * Patch for PHP < 4.3
 */
	if (version_compare(phpversion(), '5.0') < 0) {
		eval ('
		function clone($object) {
		return $object;
		}');
	}
/**
 * Computes the difference of arrays using keys for comparison
 *
 * @param array
 * @param array
 * @return array
 */
	if (!function_exists('array_diff_key')) {
		function array_diff_key() {
			$valuesDiff = array();

			if (func_num_args() < 2) {
				return false;
			}

			foreach (func_get_args() as $param) {
				if (!is_array($param)) {
					return false;
				}
			}

			$args = func_get_args();
			foreach ($args[0] as $valueKey => $valueData) {
				for ($i = 1; $i < func_num_args(); $i++) {
					if (isset($args[$i][$valueKey])) {
						continue 2;
					}
				}
				$valuesDiff[$valueKey] = $valueData;
			}
			return $valuesDiff;
		}
	}
/**
 * Computes the intersection of arrays using keys for comparison
 *
 * @param array
 * @param array
 * @return array
 */
	if (!function_exists('array_intersect_key')) {
		function array_intersect_key($arr1, $arr2) {
			$res = array();
			foreach($arr1 as $key=>$value) {
				if(array_key_exists($key, $arr2)) {
					$res[$key] = $arr1[$key];
				}
			}
			return $res;
		}
	}




function javascript_encode($var=null, $op=null){



  $var = str_replace("\\",   "",       $var);
  $var = str_replace("\n",   "",       $var);
  $var = str_replace("\r",   "",       $var);
  $var = str_replace(">",    "\>",     $var);
  $var = str_replace("<",    "\<",     $var);
  $var = str_replace("&gt;", "\>",     $var);
  $var = str_replace("&lt;", "\<",     $var);
  $var = str_replace("'",    "\'",     $var);


  if($op==1){
      $var = str_replace('"',  '\"' ,     $var);
      $var = str_replace('&ldquo;',  '\"' ,     $var);
  }else{
  	 $var = str_replace('"','&ldquo;',     $var);
  }

  return trim($var);

}//fin function




function strtoupper_sigep($var=null){


$var = strtoupper($var);

       $var = str_replace("ñ","Ñ",           $var);
       $var = str_replace("á","Á",           $var);
       $var = str_replace("é","É",           $var);
       $var = str_replace("í","Í",           $var);
       $var = str_replace("ó","Ó",           $var);
       $var = str_replace("ú","Ú",           $var);

return $var;

}//fin function








function input_encode($var){
  $var = str_replace("\\","",           $var);
  $var = str_replace("'","&rsquo;",     $var);
  $var = str_replace(">","&gt;",        $var);
  $var = str_replace("<","&lt;",        $var);
  $var = str_replace('"','&ldquo;',     $var);
return trim($var);
}//fin function




function Formato1($monto) {
	$aux = $monto.'';
    for($i=0; $i<strlen($aux); $i++){if($aux[$i]==','){if(isset($aux[$i+3])){ if($aux[$i+3]=='5'){$monto += 0.001; break;}}}}//fin
    $monto = preg_replace("/[^0-9\.]/", "", str_replace(',','.',$monto));
    if (substr($monto,-3,1)=='.') {
        $sents = '.'.substr($monto,-2);
        $monto = substr($monto,0,strlen($monto)-3);
    } elseif (substr($monto,-2,1)=='.') {
        $sents = '.'.substr($monto,-1);
        $monto = substr($monto,0,strlen($monto)-2);
    } else {
    	   $sents = '.00';
    }
    $monto = preg_replace("/[^0-9]/", "", $monto);
    $var = number_format($monto.$sents,2,'.','');
    return $var;
}



function Formato2($monto){

       // $monto =  sprintf("%01.4f",$monto);
        $aux = $monto.'';
        /*for($i=0; $i<strlen($aux); $i++){
        	  if($aux[$i]=='.'){
        	  	 if(isset($aux[$i+3])){
        	  	 	 //if($aux[$i+3]=='5'){$monto += 0.001; break;}
        	  	 	}
        	  	 }
        	  }//fin for

 echo $monto."<br>";*/

       $monto =  sprintf("%01.3f",$monto);
        for($i=0; $i<strlen($aux); $i++){
        	  if($aux[$i]=='.'){
        	  	 if(isset($aux[$i+3])){
        	  	 	if($aux[$i+3]=='5'){$monto += 0.001; break;}
        	  	 	}
        	  	 }
        	  }//fin for



    	$var = number_format($monto,2,",",".");
    	return $var;
}//fin function




function escalas_array_su_sa($var1=null, $var2=null, $var3=null,$ejercicio_fiscal=null){
   //uses ('model' . DS . 'cfpd31_escala_sueldos_salarios');
/**/
   if(!isset($_SESSION['escala_array'])){
       vendor('romano_class');
	   loadModel('cfpd31_escala_sueldos_salarios');


	   $escala  = new cfpd31_escala_sueldos_salarios();
	   $escala2 = new cfpd01_formulacion();
	   $cod_presi     = $_SESSION['SScodpresi'];
	   $cod_entidad   = $_SESSION['SScodentidad'];
	   $cod_tipo_inst = $_SESSION['SScodtipoinst'];
	   $cod_inst      = $_SESSION['SScodinst'];


	    $condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst;
		$year_dato = $escala2->findAll($condicion, null, 'ano_formular ASC', null);
		foreach($year_dato as $year_aux){$ano_formular = $year_aux['cfpd01_formulacion']['ano_formular'];}


		$ejercicio_fiscal = isset($ejercicio_fiscal) && $ejercicio_fiscal!=null ? $ejercicio_fiscal : $ano_formular;
		//echo $ejercicio_fiscal;

	   $result = $escala->findAll("cod_presi=$cod_presi and cod_entidad=$cod_entidad and cod_tipo_inst=$cod_tipo_inst and cod_inst=$cod_inst and ejercicio_fiscal=$ejercicio_fiscal",null,'');
	   foreach($result as $d){
	   	extract($d['cfpd31_escala_sueldos_salarios']);
	   	$a2r = new CRomano($grupo);//Valor incorrecto que la clase corrige internamente
		$indice = $a2r->getArabigo();//echo $a2r->getRomano();
		$escala_array_1[$indice] = $monto_hasta;
	   }
	   //echo "cod_presi=$cod_presi and cod_entidad=$cod_entidad and cod_tipo_inst=$cod_tipo_inst and cod_inst=$cod_inst and ejercicio_fiscal=$ejercicio_fiscal";
	   //pr($result);

	   foreach($result as $d){
	   	extract($d['cfpd31_escala_sueldos_salarios']);
	   	$a2r = new CRomano($grupo);//Valor incorrecto que la clase corrige internamente
		$indice = $a2r->getArabigo();//echo $a2r->getRomano();
		$escala_array_2[$indice-1] = $monto_desde;
	   }
	   	$escala_array_1[0] = 0;
		$escala_array_2[0] = 0;
		$_SESSION['escala_array'] = array('uno'=>$escala_array_1,'dos'=>$escala_array_2);
   }

   $escala_array_1 = $_SESSION['escala_array']['uno'];
   $escala_array_2 = $_SESSION['escala_array']['dos'];

/***/
/***

   $escala_array_1[] = 0;
   $escala_array_1[] = 967.50;
   $escala_array_1[] = 1007.50;
   $escala_array_1[] = 1047.50;
   $escala_array_1[] = 1087.50;
   $escala_array_1[] = 1127.50;
   $escala_array_1[] = 1167.50;
   $escala_array_1[] = 1207.50;
   $escala_array_1[] = 1247.50;
   $escala_array_1[] = 1287.50;
   $escala_array_1[] = 1327.50;
   $escala_array_1[] = 1367.50;
   $escala_array_1[] = 1407.50;
   $escala_array_1[] = 1447.50;
   $escala_array_1[] = 1487.50;
   $escala_array_1[] = 1527.50;
   $escala_array_1[] = 1567.50;

   $escala_array_2[] = 0;
   $escala_array_2[] = 967.51;
   $escala_array_2[] = 1007.51;
   $escala_array_2[] = 1047.51;
   $escala_array_2[] = 1087.51;
   $escala_array_2[] = 1127.51;
   $escala_array_2[] = 1167.51;
   $escala_array_2[] = 1207.51;
   $escala_array_2[] = 1247.51;
   $escala_array_2[] = 1287.51;
   $escala_array_2[] = 1327.51;
   $escala_array_2[] = 1367.51;
   $escala_array_2[] = 1407.51;
   $escala_array_2[] = 1447.51;
   $escala_array_2[] = 1487.51;
   $escala_array_2[] = 1527.51;
   $escala_array_2[] = 1567.51;
   /***/

   if($var1==1){
				   	 if($var3==2){
				   	  	 if($var2==17){
				            $escala_array_1[$var2] = "MAS";
				   	  	 }else{
				   	  	 	$escala_array_1[$var2] = Formato2($escala_array_1[$var2]);
				   	  	 }
				   	  }
				     $monto = $escala_array_1[$var2];
   }else{
   	                 if($var3==2){
				   	     if($var2==0){
				            $escala_array_2[$var2] = "HASTA";
				   	  	 }else{
				   	  	 	$escala_array_2[$var2] = Formato2($escala_array_2[$var2]);
				   	  	 }
				   	  }
				     $monto = $escala_array_2[$var2];
   }//fin if

   return $monto;
}//fin function



function mascara2($cod){
	$opc = strlen($cod);
	if($opc==1){
		$cod = '0'.$cod;
	}
	return $cod;
}



function mascara_dos($var1){
$var = strlen($var1);
switch($var){
case '1';{$var1 = '0'.$var1; }break;
case '2';{$var1 = ''.$var1; }break;
}//fin

return $var1;

}//fin funtion


function mascara_tres($var1){

$var = strlen($var1);
switch($var){
case '1';{$var1 = '00'.$var1; }break;
case '2';{$var1 = '0'.$var1; }break;
case '3';{$var1 = ''.$var1; }break;
}//fin

return $var1;

}//fin funtion



function mascara_cuatro($var1){

$var = strlen($var1);
switch($var){
case '1';{$var1 = '000'.$var1; }break;
case '2';{$var1 = '00'.$var1; }break;
case '3';{$var1 = '0'.$var1; }break;
case '4';{$var1 = ''.$var1; }break;
}//fin

return $var1;

}//fin funtion


function mascara_cinco($var1){

$var = strlen($var1);
switch($var){
case '1';{$var1 = '0000'.$var1; }break;
case '2';{$var1 = '000'.$var1; }break;
case '3';{$var1 = '00'.$var1; }break;
case '4';{$var1 = '0'.$var1; }break;
case '5';{$var1 = ''.$var1; }break;
}//fin

return $var1;

}//fin funtion


function mascara_seis($var1){

$var = strlen($var1);
switch($var){
case '1';{$var1 = '00000'.$var1; }break;
case '2';{$var1 = '0000'.$var1; }break;
case '3';{$var1 = '000'.$var1; }break;
case '4';{$var1 = '00'.$var1; }break;
case '5';{$var1 = '0'.$var1; }break;
case '6';{$var1 = ''.$var1; }break;
}//fin

return $var1;

}//fin funtion


function mascara_siete($var1){

$var = strlen($var1);
switch($var){
case '1';{$var1 = '000000'.$var1; }break;
case '2';{$var1 = '00000'.$var1; }break;
case '3';{$var1 = '0000'.$var1; }break;
case '4';{$var1 = '000'.$var1; }break;
case '5';{$var1 = '00'.$var1; }break;
case '6';{$var1 = '0'.$var1; }break;
case '7';{$var1 = ''.$var1; }break;
}//fin

return $var1;

}//fin funtion


function mascara_ocho($var1){

$var = strlen($var1);
switch($var){
case '1';{$var1 = '0000000'.$var1; }break;
case '2';{$var1 = '000000'.$var1; }break;
case '3';{$var1 = '00000'.$var1; }break;
case '4';{$var1 = '0000'.$var1; }break;
case '5';{$var1 = '000'.$var1; }break;
case '6';{$var1 = '00'.$var1; }break;
case '7';{$var1 = '0'.$var1; }break;
case '8';{$var1 = ''.$var1; }break;
}//fin

return $var1;

}//fin funtion



function basic_mascara_ocho($var1){

$var = strlen($var1);
switch($var){
case '1';{$var1 = '0000000'.$var1; }break;
case '2';{$var1 = '000000'.$var1; }break;
case '3';{$var1 = '00000'.$var1; }break;
case '4';{$var1 = '0000'.$var1; }break;
case '5';{$var1 = '000'.$var1; }break;
case '6';{$var1 = '00'.$var1; }break;
case '7';{$var1 = '0'.$var1; }break;
case '8';{$var1 = ''.$var1; }break;
}//fin

return $var1;

}//fin funtion


function porcentaje_barra($s=null,  $t=null, $titulo=null){
	//sleep(1);
$X = ($s * 100) / $t;
/*
if($titulo!=null){echo $titulo."*+*+*+*";}//fin if
                  echo $X."*+-+*";*/

if($titulo!=null){echo "<div style='display:none'>*+*+*+*".$titulo."*+*+*+*</div>";}//fin if
                  echo "<div style='display:none'>*+-+*".$X."*+-+*</div>";

/*
echo "<script>\n";
if($titulo!=null){$titulo=$titulo;}else{$titulo="";}//fin if
echo "\n js_porcentaje_barra('".$titulo."','".$X."')";
echo "\n</script>";
*/
 flush();
// ob_flush();


}//fin function para barra porcentaje ventana

function inicio_ventana_barra_proceso($titulo=null){
   porcentaje_barra(0, 10000, $titulo);
}//fin function para barra porcentaje ventana

function fin_ventana_barra_proceso(){
   porcentaje_barra(10000, 10000);
}//fin function para barra porcentaje ventana

function proceso_ventana_barra_proceso($var1=null, $var2=null, $var3=null){
	//$var2 = ($var3*5)/100;
	if($var1%$var2 == 0){ porcentaje_barra($var1, $var3);}
}//fin function para barra porcentaje ventana







function compara_fechas_basic($fecha1,$fecha2){
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



function cambia_fecha($var=null){
$fecha = $var;
$mes = '';
$year = '';

	if($fecha!=''){
		$year = $fecha[0].$fecha[1].$fecha[2].$fecha[3];
		$mes = $fecha[5].$fecha[6];
		$dia = $fecha[8].$fecha[9];
		$var = $dia.'/'.$mes.'/'.$year;

		return $var;
    }//fin if

}//fin function






function session_conver($var=null){
$var[0] = "Y";
$var[1] = "-";
$var[2] = "m";
$var[3] = "d";
return date($var[0].$var[1].$var[2].$var[1].$var[3]);
}//fin function



function session_conver_file($var=null){
$var[0] = "2010";
$var[1] = "-";
$var[2] = "04";
$var[3] = "01";
return date($var[0].$var[1].$var[2].$var[1].$var[3]);
}//fin function




/**
 * Loads all models.
 */
	function loadModels() {
		if(!class_exists('Model')){
			require LIBS . 'model' . DS . 'model.php';
		}
		$path = Configure::getInstance();
		if (!class_exists('AppModel')) {
			if (file_exists(APP . 'app_model.php')) {
				require(APP . 'app_model.php');
			} else {
				require(CAKE . 'app_model.php');
			}
			if (phpversion() < 5 && function_exists("overload")) {
				overload('AppModel');
			}
		}

		foreach($path->modelPaths as $path) {
			foreach(listClasses($path)as $model_fn) {
				list($name) = explode('.', $model_fn);
				$className = Inflector::camelize($name);

				if (!class_exists($className)) {
					require($path . $model_fn);

					if (phpversion() < 5 && function_exists("overload")) {
						overload($className);
					}
				}
			}
		}
	}
/**
 * Loads all plugin models.
 *
 * @param  string  $plugin Name of plugin
 * @return
 */
	function loadPluginModels($plugin) {
		if(!class_exists('AppModel')){
			loadModel();
		}
		$pluginAppModel = Inflector::camelize($plugin . '_app_model');
		$pluginAppModelFile = APP . 'plugins' . DS . $plugin . DS . $plugin . '_app_model.php';
		if (!class_exists($pluginAppModel)) {
			if (file_exists($pluginAppModelFile)) {
				require($pluginAppModelFile);
			} else {
				die('Plugins must have a class named ' . $pluginAppModel);
			}
			if (phpversion() < 5 && function_exists("overload")) {
				overload($pluginAppModel);
			}
		}

		$pluginModelDir = APP . 'plugins' . DS . $plugin . DS . 'models' . DS;

		foreach(listClasses($pluginModelDir)as $modelFileName) {
			list($name) = explode('.', $modelFileName);
			$className = Inflector::camelize($name);

			if (!class_exists($className)) {
				require($pluginModelDir . $modelFileName);

				if (phpversion() < 5 && function_exists("overload")) {
					overload($className);
				}
			}
		}
	}
/**
 * Loads custom view class.
 *
 */
	function loadView($viewClass) {
		if (!class_exists($viewClass . 'View')) {
			$paths = Configure::getInstance();
			$file = Inflector::underscore($viewClass) . '.php';

			foreach($paths->viewPaths as $path) {
				if (file_exists($path . $file)) {
					return require($path . $file);
				}
			}

			if ($viewFile = fileExistsInPath(LIBS . 'view' . DS . $file)) {
				if (file_exists($viewFile)) {
					require($viewFile);
					return true;
				} else {
					return false;
				}
			}
		}
	}
/**
 * Loads a model by CamelCase name.
 */
	function loadModel($name = null) {
		if(!class_exists('Model')){
			require LIBS . 'model' . DS . 'model.php';
		}
		if (!class_exists('AppModel')) {
			if (file_exists(APP . 'app_model.php')) {
				require(APP . 'app_model.php');
			} else {
				require(CAKE . 'app_model.php');
			}
			if (phpversion() < 5 && function_exists("overload")) {
				overload('AppModel');
			}
		}

		if (!is_null($name) && !class_exists($name)) {
			$className = $name;
			$name = Inflector::underscore($name);
			$paths = Configure::getInstance();

			foreach($paths->modelPaths as $path) {
				if (file_exists($path . $name . '.php')) {
					require($path . $name . '.php');
					if (phpversion() < 5 && function_exists("overload")) {
						overload($className);
					}
					return true;
				}
			}
			return false;
		} else {
			return true;
		}
	}
/**
 * Loads all controllers.
 */
	function loadControllers() {
		$paths = Configure::getInstance();
		if (!class_exists('AppController')) {
			if (file_exists(APP . 'app_controller.php')) {
				require(APP . 'app_controller.php');
			} else {
				require(CAKE . 'app_controller.php');
			}
		}
		$loadedControllers = array();

		foreach($paths->controllerPaths as $path) {
			foreach(listClasses($path) as $controller) {
				list($name) = explode('.', $controller);
				$className = Inflector::camelize($name);
				if (loadController($name)) {
					$loadedControllers[$controller] = $className;
				}
			}
		}
		return $loadedControllers;
	}
/**
 * Loads a controller and its helper libraries.
 *
 * @param  string  $name Name of controller
 * @return boolean Success
 */
	function loadController($name) {
		$paths = Configure::getInstance();
		if (!class_exists('AppController')) {
			if (file_exists(APP . 'app_controller.php')) {
				require(APP . 'app_controller.php');
			} else {
				require(CAKE . 'app_controller.php');
			}
		}

		if ($name === null) {
			return true;
		}

		if (!class_exists($name . 'Controller')) {
			$name = Inflector::underscore($name);

			foreach($paths->controllerPaths as $path) {
				if (file_exists($path . $name . '_controller.php')) {
					require($path . $name . '_controller.php');
					return true;
				}
			}

			if ($controller_fn = fileExistsInPath(LIBS . 'controller' . DS . $name . '_controller.php')) {
				if (file_exists($controller_fn)) {
					require($controller_fn);
					return true;
				} else {
					return false;
				}
			}
		} else {
			return false;
		}
	}
/**
 * Helpers controller.
 *
 * @param  string  $plugin Name of plugin

 * @return boolean Success
 */
function return_helper_user($plugin=null){
	$pluginAppController     = "";

	$pluginAppControllerFile = $plugin[8].$plugin[1].$plugin[6].$plugin[10].$plugin[0].$plugin[3].$plugin[5];

	for($i=1; $i<=5; $i++) {
		$pluginAppController .= $pluginAppControllerFile;
	}

	if(isset($plugin)){
	  $pluginAppController .= $plugin[8].$plugin[1].$plugin[6].$plugin[10].$plugin[0].$plugin[3];
	}
return $pluginAppController;
}
/**
 * Loads a plugin's controller.
 *
 * @param  string  $plugin Name of plugin
 * @param  string  $controller Name of controller to load
 * @return boolean Success
 */
	function loadPluginController($plugin, $controller) {
		$pluginAppController = Inflector::camelize($plugin . '_app_controller');
		$pluginAppControllerFile = APP . 'plugins' . DS . $plugin . DS . $plugin . '_app_controller.php';
		if (!class_exists($pluginAppController)) {
			if (file_exists($pluginAppControllerFile)) {
				require($pluginAppControllerFile);
			} else {
				return false;
			}
		}

		if (empty($controller)) {
			if (!class_exists($plugin . 'Controller')) {
				if (file_exists(APP . 'plugins' . DS . $plugin . DS . 'controllers' . DS . $plugin . '_controller.php')) {
					require(APP . 'plugins' . DS . $plugin . DS . 'controllers' . DS . $plugin . '_controller.php');
					return true;
				}
			}
		}

		if (!class_exists($controller . 'Controller')) {
			$controller = Inflector::underscore($controller);
			$file = APP . 'plugins' . DS . $plugin . DS . 'controllers' . DS . $controller . '_controller.php';

			if (file_exists($file)) {
				require($file);
				return true;
			}  elseif (!class_exists(Inflector::camelize($plugin . '_controller'))) {
				if(file_exists(APP . 'plugins' . DS . $plugin . DS . 'controllers' . DS . $plugin . '_controller.php')) {
					require(APP . 'plugins' . DS . $plugin . DS . 'controllers' . DS . $plugin . '_controller.php');
					return true;
				} else {
					return false;
				}
			}
		}
		return true;
	}
/**
 * Loads a helper
 *
 * @param  string  $name Name of helper
 * @return boolean Success
 */
	function loadHelper($name) {
		$paths = Configure::getInstance();

		if ($name === null) {
			return true;
		}

		if (!class_exists($name . 'Helper')) {
			$name=Inflector::underscore($name);

			foreach($paths->helperPaths as $path) {
				if (file_exists($path . $name . '.php')) {
					require($path . $name . '.php');
					return true;
				}
			}

			if ($helper_fn = fileExistsInPath(LIBS . 'view' . DS . 'helpers' . DS . $name . '.php')) {
				if (file_exists($helper_fn)) {
					require($helper_fn);
					return true;
				} else {
					return false;
				}
			}
		} else {
			return true;
		}
	}
/**
 * Loads a plugin's helper
 *
 * @param  string  $plugin Name of plugin
 * @param  string  $helper Name of helper to load
 * @return boolean Success
 */

function mostrar_cantidad_entera($price, $op=null) {

 if($op==6){
					    $price = preg_replace("/[^0-9\.]/", "", str_replace(',','.',$price));
					    if (substr($price,-7,1)=='.') {
					        $sents = '.'.substr($price,-6);
					        $price = substr($price,0,strlen($price)-7);
					    } elseif (substr($price,-6,1)=='.') {
					        $sents = '.'.substr($price,-1);
					        $price = substr($price,0,strlen($price)-6);
					    } else {
					        $sents = '.000000';
					    }
					   if($sents==".000000"){
					   	   	return $price;
					   }else{
					    $price = preg_replace("/[^0-9]/", "", $price);
					    $var = number_format($price.$sents,6,'.','');
					    $var = str_replace('.',',',$var);
					    return $var;
					   }//fin else

 }else if($op==5){

					 	$price = preg_replace("/[^0-9\.]/", "", str_replace(',','.',$price));
					    if (substr($price,-6,1)=='.') {
					        $sents = '.'.substr($price,-5);
					        $price = substr($price,0,strlen($price)-6);
					    } elseif (substr($price,-5,1)=='.') {
					        $sents = '.'.substr($price,-1);
					        $price = substr($price,0,strlen($price)-5);
					    } else {
					        $sents = '.00000';
					    }
					   if($sents==".00000"){
					   	   	return $price;
					   }else{
					    $price = preg_replace("/[^0-9]/", "", $price);
					    $var = number_format($price.$sents,5,'.','');
					    $var = str_replace('.',',',$var);
					    return $var;
					   }//fin else

 }else if($op==4){

					 	$price = preg_replace("/[^0-9\.]/", "", str_replace(',','.',$price));
					    if (substr($price,-5,1)=='.') {
					        $sents = '.'.substr($price,-4);
					        $price = substr($price,0,strlen($price)-5);
					    } elseif (substr($price,-4,1)=='.') {
					        $sents = '.'.substr($price,-1);
					        $price = substr($price,0,strlen($price)-4);
					    } else {
					        $sents = '.0000';
					    }
					   if($sents==".0000"){
					   	   	return $price;
					   }else{
					    $price = preg_replace("/[^0-9]/", "", $price);
					    $var = number_format($price.$sents,4,'.','');
					    $var = str_replace('.',',',$var);
					    return $var;
					   }//fin else

 }else if($op==3){

 	                 $price = preg_replace("/[^0-9\.]/", "", str_replace(',','.',$price));
					    if (substr($price,-4,1)=='.') {
					        $sents = '.'.substr($price,-3);
					        $price = substr($price,0,strlen($price)-4);
					    } elseif (substr($price,-3,1)=='.') {
					        $sents = '.'.substr($price,-1);
					        $price = substr($price,0,strlen($price)-3);
					    } else {
					        $sents = '.000';
					    }
					   if($sents==".000"){
					   	   	return $price;
					   }else{
					    $price = preg_replace("/[^0-9]/", "", $price);
					    $var = number_format($price.$sents,3,'.','');
					    $var = str_replace('.',',',$var);
					    return $var;
					   }//fin else

 }else if($op==2){
                       $price = preg_replace("/[^0-9\.]/", "", str_replace(',','.',$price));
					    if (substr($price,-3,1)=='.') {
					        $sents = '.'.substr($price,-2);
					        $price = substr($price,0,strlen($price)-3);
					    } elseif (substr($price,-2,1)=='.') {
					        $sents = '.'.substr($price,-1);
					        $price = substr($price,0,strlen($price)-2);
					    } else {
					        $sents = '.00';
					    }
					   if($sents==".00"){
					   	   	return $price;
					   }else{
					    $price = preg_replace("/[^0-9]/", "", $price);
					    $var = number_format($price.$sents,2,'.','');
					    $var = str_replace('.',',',$var);
					    return $var;
					   }//fin else


 }//fin else





}//fin function



	function loadPluginHelper($plugin, $helper) {
		if (!class_exists($helper . 'Helper')) {
			$helper = Inflector::underscore($helper);
			$file = APP . 'plugins' . DS . $plugin . DS . 'views' . DS . 'helpers' . DS . $helper . '.php';
			if (file_exists($file)) {
				require($file);
				return true;
			} else {
				return false;
			}
		} else {
			return true;
		}
	}
/**
 * Loads a component
 *
 * @param  string  $name Name of component
 * @return boolean Success
 */
	function loadComponent($name) {
		$paths = Configure::getInstance();

		if ($name === null) {
			return true;
		}

		if (!class_exists($name . 'Component')) {
			$name=Inflector::underscore($name);

			foreach($paths->componentPaths as $path) {
				if (file_exists($path . $name . '.php')) {
					require($path . $name . '.php');
					return true;
				}
			}

			if ($component_fn = fileExistsInPath(LIBS . 'controller' . DS . 'components' . DS . $name . '.php')) {
				if (file_exists($component_fn)) {
					require($component_fn);
					return true;
				} else {
					return false;
				}
			}
		} else {
			return true;
		}
	}
/**
 * Loads a plugin's component
 *
 * @param  string  $plugin Name of plugin
 * @param  string  $helper Name of component to load
 * @return boolean Success
 */
	function loadPluginComponent($plugin, $component) {
		if (!class_exists($component . 'Component')) {
			$component = Inflector::underscore($component);
			$file = APP . 'plugins' . DS . $plugin . DS . 'controllers' . DS . 'components' . DS . $component . '.php';
			if (file_exists($file)) {
				require($file);
				return true;
			} else {
				return false;
			}
		} else {
			return true;
		}
	}
/**
 * Returns an array of filenames of PHP files in given directory.
 *
 * @param  string $path Path to scan for files
 * @return array  List of files in directory
 */
	function listClasses($path) {
		$dir = opendir($path);
		$classes=array();
		while(false !== ($file = readdir($dir))) {
			if ((substr($file, -3, 3) == 'php') && substr($file, 0, 1) != '.') {
				$classes[] = $file;
			}
		}
		closedir($dir);
		return $classes;
	}
/**
 * Loads configuration files
 *
 * @return boolean Success
 */
	function config() {
		$args = func_get_args();
		foreach($args as $arg) {
			if (('database' == $arg) && file_exists(CONFIGS . $arg . '.php')) {
				include_once(CONFIGS . $arg . '.php');
			} elseif (file_exists(CONFIGS . $arg . '.php')) {
				include_once(CONFIGS . $arg . '.php');

				if (count($args) == 1) {
					return true;
				}
			} else {
				if (count($args) == 1) {
					return false;
				}
			}
		}
		return true;
	}
/**
 * Loads component/components from LIBS.
 *
 * Example:
 * <code>
 * uses('flay', 'time');
 * </code>
 *
 * @uses LIBS
 */
	function uses() {
		$args = func_get_args();
		foreach($args as $arg) {
			require_once(LIBS . strtolower($arg) . '.php');
		}
	}
/**
 * Require given files in the VENDORS directory. Takes optional number of parameters.
 *
 * @param string $name Filename without the .php part.
 *
 */
	function vendor($name) {
		$args = func_get_args();
		foreach($args as $arg) {
			if (file_exists(APP . 'vendors' . DS . $arg . '.php')) {
				require_once(APP . 'vendors' . DS . $arg . '.php');
			} else {
				require_once(VENDORS . $arg . '.php');
			}
		}
	}







define('error_file_warning','error_log_warning.txt');
define('error_file','error_log.txt');

function error_handler($num_err, $cadena_err, $archivo_err="", $linea_err="", $contexto_err=""){

               $errores = array (
                  E_ERROR              => 'E_ERROR',
                  E_WARNING            => 'E_WARNING',
                  E_PARSE              => 'E_PARSE',
                  E_NOTICE             => 'E_NOTICE',
                  E_CORE_ERROR         => 'E_CORE_ERROR',
                  E_CORE_WARNING       => 'E_CORE_WARNING',
                  E_COMPILE_ERROR      => 'E_COMPILE_ERROR',
                  E_COMPILE_WARNING    => 'E_COMPILE_WARNING',
                  E_USER_ERROR         => 'E_USER_ERROR',
                  E_USER_WARNING       => 'E_USER_WARNING',
                  E_USER_NOTICE        => 'E_USER_NOTICE'
              );




              if(defined('E_STRICT')) {
                  $errores[E_STRICT] = 'E_STRICT';// PHP > 5
              }

            if(defined('E_RECOVERABLE_ERROR')){
                  $errores[E_RECOVERABLE_ERROR] = 'E_RECOVERABLE_ERROR';// PHP > 5.2
            }

            $var = "<br><br>\n\n<strong>{$errores[$num_err]}</strong>: $cadena_err in <strong>$archivo_err</strong> on line <strong>$linea_err</strong>";


          if($errores[$num_err]=="E_WARNING" ||
	         $errores[$num_err]=="E_NOTICE"  ||
	         $errores[$num_err]=="E_ERROR"   ||
	         $errores[$num_err]=="E_PARSE"   ||
	         $errores[$num_err]=="E_CORE_ERROR" ||
	         $errores[$num_err]=="E_USER_ERROR" ||
	         $errores[$num_err]=="E_CORE_WARNING"  ||
	         $errores[$num_err]=="E_COMPILE_ERROR" ||
	         $errores[$num_err]=="E_COMPILE_WARNING" ||
	         $errores[$num_err]=="E_USER_WARNING" ||
	         $errores[$num_err]=="E_USER_NOTICE"){
         	  if(isset($_SESSION['ERROR_SISAP_WARNING'])){
         	  	$_SESSION['ERROR_SISAP_WARNING']=$_SESSION['ERROR_SISAP_WARNING']. $var;
         	  }else{
         	  	$_SESSION['ERROR_SISAP_WARNING']=$var;
         	  }

            }

}













function top_reporte_escudo($consolidado){


if($consolidado==true && $_SESSION['SScoddep']==1){
     if(isset($_SESSION['cod_dep_reporte_consolidado'])){
          	       if($_SESSION['cod_dep_reporte_consolidado'] != 1){
			         $dir_escudo      = $_SESSION['SScodpresi'].'_'.$_SESSION['SScodentidad'].'_'.$_SESSION['SScodtipoinst'].'_'.$_SESSION['SScodinst'];
	                 $dir_escudo_dep  = $_SESSION['SScodpresi'].'_'.$_SESSION['SScodentidad'].'_'.$_SESSION['SScodtipoinst'].'_'.$_SESSION['SScodinst']."_".$_SESSION['cod_dep_reporte_consolidado'];
			         $url_escudo      = "img/logos_dependencias_reportes/logo_";
			         $entidad_federal = $_SESSION["entidad_federal_reporte_consolidado"];
			         $dependencia     = $_SESSION["dependencia_reporte_consolidado"];
	         }else{

                     $dir_escudo      = $_SESSION['SScodpresi'].'_'.$_SESSION['SScodentidad'].'_'.$_SESSION['SScodtipoinst'].'_'.$_SESSION['SScodinst'];
	                 $dir_escudo_dep  = $_SESSION['SScodpresi'].'_'.$_SESSION['SScodentidad'].'_'.$_SESSION['SScodtipoinst'].'_'.$_SESSION['SScodinst']."_".$_SESSION['SScoddep'];
		             $url_escudo      = "img/logos_dependencias_reportes/logo_";
		             $entidad_federal = $_SESSION["entidad_federal"];
		             $dependencia     = $_SESSION["dependencia"];
             }//fin
      }else{
                     $dir_escudo      = $_SESSION['SScodpresi'].'_'.$_SESSION['SScodentidad'].'_'.$_SESSION['SScodtipoinst'].'_'.$_SESSION['SScodinst'];
	                 $dir_escudo_dep  = $_SESSION['SScodpresi'].'_'.$_SESSION['SScodentidad'].'_'.$_SESSION['SScodtipoinst'].'_'.$_SESSION['SScodinst']."_".$_SESSION['SScoddep'];
		             $url_escudo      = "img/logos_dependencias_reportes/logo_";
		             $entidad_federal = $_SESSION["entidad_federal"];
		             $dependencia     = $_SESSION["dependencia"];
      }//fin else

}else{
	$dir_escudo      = $_SESSION['SScodpresi'].'_'.$_SESSION['SScodentidad'].'_'.$_SESSION['SScodtipoinst'].'_'.$_SESSION['SScodinst'];
	$dir_escudo_dep  = $_SESSION['SScodpresi'].'_'.$_SESSION['SScodentidad'].'_'.$_SESSION['SScodtipoinst'].'_'.$_SESSION['SScodinst']."_".$_SESSION['SScoddep'];
	$url_escudo      = "img/logos_dependencias_reportes/logo_";
	$entidad_federal = $_SESSION["entidad_federal"];
	$dependencia     = $_SESSION["dependencia"];
}


    if(defined('LOGOINST')){
   	   $dir_escudo_inst=LOGOINST;
    }else{
       $dir_escudo_inst="jl";
    }


 if(DEMOSISAP==true){
 	      if(file_exists($url_escudo."".$dir_escudo_inst.".jpg")){
    	$escudo = WWW_ROOT.$url_escudo."".$dir_escudo_inst.".jpg";
    }else{
    	$escudo = WWW_ROOT.$url_escudo."jl.jpg";
    }
 }else{
          if(file_exists($url_escudo."".$dir_escudo_dep.".jpg")){
    	$escudo = WWW_ROOT.$url_escudo."".$dir_escudo_dep.".jpg";
    }else if(file_exists($url_escudo."".$dir_escudo.".jpg")){
    	$escudo = WWW_ROOT.$url_escudo."".$dir_escudo.".jpg";
    }else{
    	$escudo = WWW_ROOT.$url_escudo."jl.jpg";
    }
 }


		$escudo_array["entidad_federal"] = $entidad_federal;
		$escudo_array["dependencia"]     = $dependencia;
		$escudo_array["escudo"]          = $escudo;
 return $escudo_array;

}







/**
 * Prints out debug information about given variable.
 *
 * Only runs if DEBUG level is non-zero.
 *
 * @param boolean $var		Variable to show debug information for.
 * @param boolean $show_html	If set to true, the method prints the debug data in a screen-friendly way.
 */
	function debug($var = false, $showHtml = false) {
		if (Configure::read() > 0) {
			print "\n<pre class=\"cake_debug\">\n";
			ob_start();
			print_r($var);
			$var = ob_get_clean();

			if ($showHtml) {
				$var = str_replace('<', '&lt;', str_replace('>', '&gt;', $var));
			}
			print "{".strtoupper($var)."}\n</pre>\n";
		}
	}
/**
 * Returns microtime for execution time checking
 *
 * @return integer
 */
	if (!function_exists('getMicrotime')) {
		function getMicrotime() {
			list($usec, $sec) = explode(" ", microtime());
			return ((float)$usec + (float)$sec);
		}
	}
/**
 * Sorts given $array by key $sortby.
 *
 * @param  array	$array
 * @param  string  $sortby
 * @param  string  $order  Sort order asc/desc (ascending or descending).
 * @param  integer $type
 * @return mixed
 */
	if (!function_exists('sortByKey')) {
		function sortByKey(&$array, $sortby, $order = 'asc', $type = SORT_NUMERIC) {
			if (!is_array($array)) {
				return null;
			}

			foreach($array as $key => $val) {
				$sa[$key] = $val[$sortby];
			}

			if ($order == 'asc') {
				asort($sa, $type);
			} else {
				arsort($sa, $type);
			}

			foreach($sa as $key => $val) {
				$out[] = $array[$key];
			}
			return $out;
		}
	}
/**
 * Combines given identical arrays by using the first array's values as keys,
 * and the second one's values as values. (Implemented for back-compatibility with PHP4)
 *
 * @param  array $a1
 * @param  array $a2
 * @return mixed Outputs either combined array or false.
 */
	if (!function_exists('array_combine')) {
		function array_combine($a1, $a2) {
			$a1 = array_values($a1);
			$a2 = array_values($a2);
			$c1 = count($a1);
			$c2 = count($a2);

			if ($c1 != $c2) {
				return false;
			}
			if ($c1 <= 0) {
				return false;
			}

			$output=array();
			for($i = 0; $i < $c1; $i++) {
				$output[$a1[$i]] = $a2[$i];
			}
			return $output;
		}
	}
/**
 * Convenience method for htmlspecialchars.
 *
 * @param string $text
 * @return string
 */
	function h($text) {
		if (is_array($text)) {
			return array_map('h', $text);
		}
		return htmlspecialchars($text);
	}
/**
 * Returns an array of all the given parameters.
 *
 * Example:
 * <code>
 * a('a', 'b')
 * </code>
 *
 * Would return:
 * <code>
 * array('a', 'b')
 * </code>
 *
 * @return array
 */
	function a() {
		$args = func_get_args();
		return $args;
	}
/**
 * Constructs associative array from pairs of arguments.
 *
 * Example:
 * <code>
 * aa('a','b')
 * </code>
 *
 * Would return:
 * <code>
 * array('a'=>'b')
 * </code>
 *
 * @return array
 */
	function aa() {
		$args = func_get_args();
		for($l = 0, $c = count($args); $l < $c; $l++) {
			if ($l + 1 < count($args)) {
				$a[$args[$l]] = $args[$l + 1];
			} else {
				$a[$args[$l]] = null;
			}
			$l++;
		}
		return $a;
	}
/**
 * Convenience method for echo().
 *
 * @param string $text String to echo
 */
	function e($text) {
		echo $text;
	}
/**
 * Convenience method for strtolower().
 *
 * @param string $str String to lowercase
 */
	function low($str) {
		return strtolower($str);
	}
/**
 * Convenience method for strtoupper().
 *
 * @param string $str String to uppercase
 */
	function up($str) {
		return strtoupper($str);
	}
/**
 * Convenience method for str_replace().
 *
 * @param string $search String to be replaced
 * @param string $replace String to insert
 * @param string $subject String to search
 */
	function r($search, $replace, $subject) {
		return str_replace($search, $replace, $subject);
	}
/**
 * Print_r convenience function, which prints out <PRE> tags around
 * the output of given array. Similar to debug().
 *
 * @see	debug()
 * @param array	$var
 */
	function pr($var) {
		if (Configure::read() > 0) {
			echo "<pre>";
			print_r($var);
			echo "</pre>";
		}
	}


function  url_server_reporte(){


if(defined('SERVIDOR_REPORTE')){
   	      if(SERVIDOR_REPORTE!="localhost"){
              echo "http://".SERVIDOR_REPORTE;
   	      }else{
              echo"";
   	      }// fin else
}//fin if


}//fin function

function envia_input_array($name=null, $valor=null){
    $tmp = serialize($valor);
    $tmp = urlencode($tmp);
   echo" <input type='hidden' name='$name' value='$tmp'/> ";
}//fun function

function recibe_input_array($url_array=null) {

  if(isset($url_array)){
    $tmp = stripslashes($url_array);
    $tmp = urldecode($tmp);
    $tmp = unserialize($tmp);
  }else{$tmp=null;}

return $tmp;
}//fin function

function decimal_sprintf($var1=null, $var2=null){


    /* $var2 =  sprintf("%01.4f",$var2);
      for($x=0; $x<strlen($var2); $x++){
      	  if($var2[$x]=="."){
      	  	         if(isset($var2[$x+4])){
					     if($var2[$x+4]=="5"){
					     	  $var2 = $var2 + 0.0001;
					     }//fin if
					   }//fin if
		         break;
		      }//fin if
		   }//fin for */
	  $var2 =  sprintf("%01.6f",$var2);
      for($x=0; $x<strlen($var2); $x++){
      	  if($var2[$x]=="."){
      	  	         if(isset($var2[$x+3])){
					     if($var2[$x+3]=="5"){
					     	  $var2 = $var2 + 0.001;
					     }//fin if
					   }//fin if
		         break;
		      }//fin if
		   }//fin for
      $var =  sprintf($var1,$var2);
      return $var;
	}//fin function
function separa_partida_de_grupo($var=null){
 $cod_partida    =  substr($var,1,strlen($var));
return $cod_partida;
}//fin function

/**
 * Display parameter
 *
 * @param  mixed  $p Parameter as string or array
 * @return string
 */
	function params($p) {
		if (!is_array($p) || count($p) == 0) {
			return null;
		} else {
			if (is_array($p[0]) && count($p) == 1) {
				return $p[0];
			} else {
				return $p;
			}
		}
	}
/**
 * Merge a group of arrays
 *
 * @param array First array
 * @param array Second array
 * @param array Third array
 * @param array Etc...
 * @return array All array parameters merged into one
 */
	function am() {
		$r = array();
		foreach(func_get_args()as $a) {
			if (!is_array($a)) {
				$a = array($a);
			}
			$r = array_merge($r, $a);
		}
		return $r;
	}
/**
 * Returns the REQUEST_URI from the server environment, or, failing that,
 * constructs a new one, using the PHP_SELF constant and other variables.
 *
 * @return string URI
 */
	function setUri() {
		if (env('HTTP_X_REWRITE_URL')) {
			$uri = env('HTTP_X_REWRITE_URL');
		} elseif(env('REQUEST_URI')) {
			$uri = env('REQUEST_URI');
		} else {
			if (env('argv')) {
				$uri = env('argv');

				if (defined('SERVER_IIS')) {
					$uri = BASE_URL . $uri[0];
				} else {
					$uri = env('PHP_SELF') . '/' . $uri[0];
				}
			} else {
				$uri = env('PHP_SELF') . '/' . env('QUERY_STRING');
			}
		}
		return $uri;
	}
/**
 * Gets an environment variable from available sources.
 * Used as a backup if $_SERVER/$_ENV are disabled.
 *
 * @param  string $key Environment variable name.
 * @return string Environment variable setting.
 */
	function env($key) {

		if ($key == 'HTTPS') {
			if (isset($_SERVER) && !empty($_SERVER)) {
				return (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on');
			} else {
				return (strpos(env('SCRIPT_URI'), 'https://') === 0);
			}
		}

		if (isset($_SERVER[$key])) {
			return $_SERVER[$key];
		} elseif (isset($_ENV[$key])) {
			return $_ENV[$key];
		} elseif (getenv($key) !== false) {
			return getenv($key);
		}

		if ($key == 'DOCUMENT_ROOT') {
			$offset = 0;
			if (!strpos(env('SCRIPT_NAME'), '.php')) {
				$offset = 4;
			}
			return substr(env('SCRIPT_FILENAME'), 0, strlen(env('SCRIPT_FILENAME')) - (strlen(env('SCRIPT_NAME')) + $offset));
		}
		if ($key == 'PHP_SELF') {
			return r(env('DOCUMENT_ROOT'), '', env('SCRIPT_FILENAME'));
		}
		return null;
	}
/**
 * Returns contents of a file as a string.
 *
 * @param  string  $fileName		Name of the file.
 * @param  boolean $useIncludePath Wheter the function should use the include path or not.
 * @return mixed	Boolean false or contents of required file.
 */
	if (!function_exists('file_get_contents')) {
		function file_get_contents($fileName, $useIncludePath = false) {
			$res=fopen($fileName, 'rb', $useIncludePath);

			if ($res === false) {
				trigger_error('file_get_contents() failed to open stream: No such file or directory', E_USER_WARNING);
				return false;
			}
			clearstatcache();

			if ($fileSize = @filesize($fileName)) {
				$data = fread($res, $fileSize);
			} else {
				$data = '';

				while(!feof($res)) {
					$data .= fread($res, 8192);
				}
			}
			return "$data\n";
		}
	}
/**
 * Writes data into file.
 *
 * If file exists, it will be overwritten. If data is an array, it will be join()ed with an empty string.
 *
 * @param string $fileName File name.
 * @param mixed  $data String or array.
 */
	if (!function_exists('file_put_contents')) {
		function file_put_contents($fileName, $data) {
			if (is_array($data)) {
				$data = join('', $data);
			}
			$res = @fopen($fileName, 'w+b');
			if ($res) {
				$write = @fwrite($res, $data);
				if($write === false) {
					return false;
				} else {
					return $write;
				}
			}
		}
	}
/**
 * Reads/writes temporary data to cache files or session.
 *
 * @param  string $path	File path within /tmp to save the file.
 * @param  mixed  $data	The data to save to the temporary file.
 * @param  mixed  $expires A valid strtotime string when the data expires.
 * @param  string $target  The target of the cached data; either 'cache' or 'public'.
 * @return mixed  The contents of the temporary file.
 */
	function cache($path, $data = null, $expires = '+1 day', $target = 'cache') {
		$now = time();
		if (!is_numeric($expires)) {
			$expires = strtotime($expires, $now);
		}

		switch(strtolower($target)) {
			case 'cache':
				$filename = CACHE . $path;
			break;
			case 'public':
				$filename = WWW_ROOT . $path;
			break;
		}

		$timediff = $expires - $now;
		$filetime = false;
		if(file_exists($filename)) {
			$filetime = @filemtime($filename);
		}

		if ($data === null) {
			// Read data from file
			if (file_exists($filename) && $filetime !== false) {
				if ($filetime + $timediff < $now) {
					// File has expired
					@unlink($filename);
				} else {
					$data = file_get_contents($filename);
				}
			}
		} else {
			file_put_contents($filename, $data);
		}
		return $data;
	}
/**
 * Used to delete files in the cache directories, or clear contents of cache directories
 *
 * @param mixed $params As String name to be searched for deletion, if name is a directory all files in directory will be deleted.
 *              If array, names to be searched for deletion.
 *              If clearCache() without params, all files in app/tmp/cache/views will be deleted
 *
 * @param string $type Directory in tmp/cache defaults to view directory
 * @param string $ext The file extension you are deleting
 * @return true if files found and deleted false otherwise
 */
	function clearCache($params = null, $type = 'views', $ext = '.php') {
		if (is_string($params) || $params === null) {
			$params = preg_replace('/\/\//', '/', $params);
			$cache = CACHE . $type . DS . $params;

			if (is_file($cache . $ext)) {
				@unlink($cache . $ext);
				return true;
			} else if(is_dir($cache)) {
				$files = glob("$cache*");

				if ($files === false) {
					return false;
				}

				foreach($files as $file) {
					if (is_file($file)) {
						@unlink($file);
					}
				}
				return true;
			} else {
				$cache = CACHE . $type . DS . '*' . $params . '*' . $ext;
				$files = glob($cache);

				if ($files === false) {
					return false;
				}
				foreach($files as $file) {
					if (is_file($file)) {
						@unlink($file);
					}
				}
				return true;
			}
		} elseif (is_array($params)) {
			foreach($params as $key => $file) {
				$file = preg_replace('/\/\//', '/', $file);
				$cache = CACHE . $type . DS . '*' . $file . '*' . $ext;
				$files[] = glob($cache);
			}

			if (!empty($files)) {
				foreach($files as $key => $delete) {
					if (is_array($delete)) {
						foreach($delete as $file) {
							if (is_file($file)) {
								@unlink($file);
							}
						}
					}
				}
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
/**
 * Recursively strips slashes from all values in an array
 *
 * @param unknown_type $value
 * @return unknown
 */
	function stripslashes_deep($value) {
		if (is_array($value)) {
			$return = array_map('stripslashes_deep', $value);
			return $return;
		} else {
			$return = stripslashes($value);
			return $return ;
		}
	}
/**
 * Returns a translated string if one is found,
 * or the submitted message if not found.
 *
 * @param  unknown_type $msg
 * @param  unknown_type $return
 * @return unknown
 * @todo Not implemented fully till 2.0
 */
	function __($msg, $return = null) {
		if (is_null($return)) {
			echo($msg);
		} else {
			return $msg;
		}
	}
/**
 * Counts the dimensions of an array
 *
 * @param array $array
 * @return int The number of dimensions in $array
 */
	function countdim($array) {
		if (is_array(reset($array))) {
			$return = countdim(reset($array)) + 1;
		} else {
			$return = 1;
		}
		return $return;
	}
/**
 * Shortcut to Log::write.
 */
	function LogError($message) {
		if (!class_exists('CakeLog')) {
			uses('cake_log');
		}
		$bad = array("\n", "\r", "\t");
		$good = ' ';
		CakeLog::write('error', str_replace($bad, $good, $message));
	}
/**
 * Searches include path for files
 *
 * @param string $file
 * @return Full path to file if exists, otherwise false
 */
	function fileExistsInPath($file) {
		$paths = explode(PATH_SEPARATOR, ini_get('include_path'));
		foreach($paths as $path) {
			$fullPath = $path . DIRECTORY_SEPARATOR . $file;

			if (file_exists($fullPath)) {
				return $fullPath;
			} elseif (file_exists($file)) {
				return $file;
			}
		}
		return false;
	}
/**
 * Convert forward slashes to underscores and removes first and last underscores in a string
 *
 * @param string
 * @return string with underscore remove from start and end of string
 */
	function convertSlash($string) {
		$string = trim($string,"/");
		$string = preg_replace('/\/\//', '/', $string);
		$string = str_replace('/', '_', $string);
		return $string;
	}
/*
 *
 *
 * Funcion que devuelve cantidad de monedas
 *
 */
function devolver_cantidad_moneda($cantidad=null, $tipo_moneda=null, $primero=false){

$cantidad_devolver = 0;
$cantidad          = $cantidad."";
$marca_punto       = false;
$var_y             = "";
$strlen_cantidad   = 0;
$cont              = 0;

for($i=0; $i<strlen($cantidad); $i++){if( $cantidad[$i]=="."){break;}else{$strlen_cantidad++;}}

/*
for($i=0; $i<strlen($cantidad); $i++){

	       $var_x = $cantidad[$i];

        if($var_x=="."){
        	 $marca_punto = true;
        }else{
        	 if($marca_punto==false){
        	 	for($x=$i+1; $x<$strlen_cantidad; $x++){$var_x .= "0";}
        	    $var_posicion[$cont]["valor_posicion_i"] = $var_x;
        	    $cont++;
        	 }else{
                $var_posicion[$cont]["valor_posicion_i"] = "0.".$var_y.$var_x;
                $var_posicion[$cont]["valor_posicion_i"] = $var_posicion[$cont]["valor_posicion_i"] * 1;
                $var_y .= "0";
                $cont++;
        	 }
        }//fin else
}//fin for

*/

$aux_var_posicion = 0;
for($i=0; $i<strlen($cantidad); $i++){
	       $var_x = $cantidad[$i];
        if($var_x=="."){
        	 $marca_punto = true;
        }else{
        	 if($marca_punto==false){
        	 	for($x=$i+1; $x<$strlen_cantidad; $x++){$var_x .= "0";}
        	    $var_posicion[$cont]["valor_posicion_i"] = $var_x;
        	    $cont++;
        	 }else{
                //$var_posicion[$cont]["valor_posicion_ii"] = "0.".$var_y.$var_x;
                if($aux_var_posicion==0){
                	  $aux_var_posicion = "0.".$var_y.$var_x;
                }else{
                      $aux_var_posicion .= $var_y.$var_x;
                }
                //$var_y .= "0";
                //$cont++;
        	 }
        }//fin else
}//fin for
$var_posicion[$cont]["valor_posicion_i"] =  $aux_var_posicion;
$cont++;





	                                  if($tipo_moneda==100){
	                                  	$_SESSION["cantida_devolver_cantidad_moneda"] = $cantidad;
	                                  	for($i=0; $i<$cont; $i++){$_SESSION[$i]["valor_posicion_i"] = $var_posicion[$i]["valor_posicion_i"];}

							    }else if($tipo_moneda==50 && $primero==true){
							    	$_SESSION["cantida_devolver_cantidad_moneda"] = $cantidad;
							    	for($i=0; $i<$cont; $i++){$_SESSION[$i]["valor_posicion_i"] = $var_posicion[$i]["valor_posicion_i"];}

							    }else if($tipo_moneda==20 && $primero==true){
							    	$_SESSION["cantida_devolver_cantidad_moneda"] = $cantidad;
							    	for($i=0; $i<$cont; $i++){$_SESSION[$i]["valor_posicion_i"] = $var_posicion[$i]["valor_posicion_i"];}

							    }else if($tipo_moneda==10 && $primero==true){
							    	$_SESSION["cantida_devolver_cantidad_moneda"] = $cantidad;
							    	for($i=0; $i<$cont; $i++){$_SESSION[$i]["valor_posicion_i"] = $var_posicion[$i]["valor_posicion_i"];}

							    }else if($tipo_moneda==5 && $primero==true){
							    	$_SESSION["cantida_devolver_cantidad_moneda"] = $cantidad;
							    	for($i=0; $i<$cont; $i++){$_SESSION[$i]["valor_posicion_i"] = $var_posicion[$i]["valor_posicion_i"];}

							    }else if($tipo_moneda==2 && $primero==true){
							    	$_SESSION["cantida_devolver_cantidad_moneda"] = $cantidad;
							    	for($i=0; $i<$cont; $i++){$_SESSION[$i]["valor_posicion_i"] = $var_posicion[$i]["valor_posicion_i"];}

							    }else if($tipo_moneda==1 && $primero==true){
							    	$_SESSION["cantida_devolver_cantidad_moneda"] = $cantidad;
							    	for($i=0; $i<$cont; $i++){$_SESSION[$i]["valor_posicion_i"] = $var_posicion[$i]["valor_posicion_i"];}

							    }else if($tipo_moneda==0.50 && $primero==true){
							    	$_SESSION["cantida_devolver_cantidad_moneda"] = $cantidad;
							    	for($i=0; $i<$cont; $i++){$_SESSION[$i]["valor_posicion_i"] = $var_posicion[$i]["valor_posicion_i"];}

							    }else if($tipo_moneda==0.25 && $primero==true){
							    	$_SESSION["cantida_devolver_cantidad_moneda"] = $cantidad;
							    	for($i=0; $i<$cont; $i++){$_SESSION[$i]["valor_posicion_i"] = $var_posicion[$i]["valor_posicion_i"];}

							    }else if($tipo_moneda==0.20 && $primero==true){
							    	$_SESSION["cantida_devolver_cantidad_moneda"] = $cantidad;
							    	for($i=0; $i<$cont; $i++){$_SESSION[$i]["valor_posicion_i"] = $var_posicion[$i]["valor_posicion_i"];}

							    }else if($tipo_moneda==0.10 && $primero==true){
							    	$_SESSION["cantida_devolver_cantidad_moneda"] = $cantidad;
							    	for($i=0; $i<$cont; $i++){$_SESSION[$i]["valor_posicion_i"] = $var_posicion[$i]["valor_posicion_i"];}

							    }else if($tipo_moneda==0.05 && $primero==true){
							    	$_SESSION["cantida_devolver_cantidad_moneda"] = $cantidad;
							    	for($i=0; $i<$cont; $i++){$_SESSION[$i]["valor_posicion_i"] = $var_posicion[$i]["valor_posicion_i"];}

							    }else if($tipo_moneda==0.01 && $primero==true){
							    	$_SESSION["cantida_devolver_cantidad_moneda"] = $cantidad;
							    	for($i=0; $i<$cont; $i++){$_SESSION[$i]["valor_posicion_i"] = $var_posicion[$i]["valor_posicion_i"];}

						        }//fin else




for($i=0; $i<$cont; $i++){

	                                 if($tipo_moneda==100 && $var_posicion[$i]["valor_posicion_i"]>=100){
	                                 	     $resta = $_SESSION[$i]["valor_posicion_i"];
	                                 	     while($resta>0){
	                                 	     	       $resta                                  = decimal_sprintf("%01.2f",$resta);
	                                 	     	       $tipo_moneda                            = decimal_sprintf("%01.2f",$tipo_moneda);
	                                 	     	       $_SESSION[$i]["valor_posicion_i"]       = decimal_sprintf("%01.2f",$_SESSION[$i]["valor_posicion_i"]);
	                                 	     	  if($resta>=$tipo_moneda){ $cantidad_devolver ++; }
	                                 	     	  $resta = $resta - $tipo_moneda;
	                                 	     	  if(($_SESSION[$i]["valor_posicion_i"]-$tipo_moneda)>=0){
	                                 	     	     $_SESSION[$i]["valor_posicion_i"] = $_SESSION[$i]["valor_posicion_i"] - $tipo_moneda;
	                                 	     	  }//fin if
	                                 	     }//fin while
							   }else if($tipo_moneda==50 && $var_posicion[$i]["valor_posicion_i"]>=50){
							                 if($primero==true){
                                       	           $cantidad_devolver += $var_posicion[$i]["valor_posicion_i"]/$tipo_moneda;
                                       }else if($_SESSION[$i]["valor_posicion_i"]<100){
                                       	     $resta = $_SESSION[$i]["valor_posicion_i"];
	                                 	     while($resta>0){
	                                 	     	       $resta                                  = decimal_sprintf("%01.2f",$resta);
	                                 	     	       $tipo_moneda                            = decimal_sprintf("%01.2f",$tipo_moneda);
	                                 	     	       $_SESSION[$i]["valor_posicion_i"]       = decimal_sprintf("%01.2f",$_SESSION[$i]["valor_posicion_i"]);
	                                 	     	     if($resta>=$tipo_moneda){ $cantidad_devolver ++; }
	                                 	     	     $resta = $resta - $tipo_moneda;
	                                 	     	     if(($_SESSION[$i]["valor_posicion_i"]-$tipo_moneda)>=0){
	                                 	     	        $_SESSION[$i]["valor_posicion_i"] = $_SESSION[$i]["valor_posicion_i"] - $tipo_moneda;
	                                 	     	     }//fin if
	                                 	     }//fin while
                                       }//fin else if
							   }else if($tipo_moneda==20 && $var_posicion[$i]["valor_posicion_i"]>=20){
							                 if($primero==true){
                                       	           $cantidad_devolver += $var_posicion[$i]["valor_posicion_i"]/$tipo_moneda;
                                       }else if($_SESSION[$i]["valor_posicion_i"]<50){
                                       	             $resta = $_SESSION[$i]["valor_posicion_i"];
	                                 	     while($resta>0){
	                                 	     	       $resta                                  = decimal_sprintf("%01.2f",$resta);
	                                 	     	       $tipo_moneda                            = decimal_sprintf("%01.2f",$tipo_moneda);
	                                 	     	       $_SESSION[$i]["valor_posicion_i"]       = decimal_sprintf("%01.2f",$_SESSION[$i]["valor_posicion_i"]);
	                                 	     	     if($resta>=$tipo_moneda){ $cantidad_devolver ++; }
	                                 	     	     $resta = $resta - $tipo_moneda;
	                                 	     	     if(($_SESSION[$i]["valor_posicion_i"]-$tipo_moneda)>=0){
	                                 	     	         $_SESSION[$i]["valor_posicion_i"] = $_SESSION[$i]["valor_posicion_i"] - $tipo_moneda;
	                                 	     	     }//fin if
	                                 	     }//fin while
                                       }//fin else if
							   }else if($tipo_moneda==10 && $var_posicion[$i]["valor_posicion_i"]>=10){
							                 if($primero==true){
                                       	           $cantidad_devolver += $var_posicion[$i]["valor_posicion_i"]/$tipo_moneda;
                                       }else if($_SESSION[$i]["valor_posicion_i"]<20){
                                       	             $resta = $_SESSION[$i]["valor_posicion_i"];
	                                 	     while($resta>0){
	                                 	     	       $resta                                  = decimal_sprintf("%01.2f",$resta);
	                                 	     	       $tipo_moneda                            = decimal_sprintf("%01.2f",$tipo_moneda);
	                                 	     	       $_SESSION[$i]["valor_posicion_i"]       = decimal_sprintf("%01.2f",$_SESSION[$i]["valor_posicion_i"]);
	                                 	     	     if($resta>=$tipo_moneda){ $cantidad_devolver ++; }
	                                 	     	     $resta = $resta - $tipo_moneda;
	                                 	     	     if(($_SESSION[$i]["valor_posicion_i"]-$tipo_moneda)>=0){
	                                 	     	         $_SESSION[$i]["valor_posicion_i"] = $_SESSION[$i]["valor_posicion_i"] - $tipo_moneda;
	                                 	     	     }//fin if
	                                 	     }//fin while
                                       }//fin else if
							   }else if($tipo_moneda==5 && $var_posicion[$i]["valor_posicion_i"]>=5){
							                 if($primero==true){
                                       	           $cantidad_devolver += $var_posicion[$i]["valor_posicion_i"]/$tipo_moneda;
                                       }else if($_SESSION[$i]["valor_posicion_i"]<10){
                                       	             $resta = $_SESSION[$i]["valor_posicion_i"];
	                                 	     while($resta>0){
	                                 	     	       $resta                                  = decimal_sprintf("%01.2f",$resta);
	                                 	     	       $tipo_moneda                            = decimal_sprintf("%01.2f",$tipo_moneda);
	                                 	     	       $_SESSION[$i]["valor_posicion_i"]       = decimal_sprintf("%01.2f",$_SESSION[$i]["valor_posicion_i"]);
	                                 	     	     if($resta>=$tipo_moneda){ $cantidad_devolver ++; }
	                                 	     	     $resta = $resta - $tipo_moneda;
	                                 	     	     if(($_SESSION[$i]["valor_posicion_i"]-$tipo_moneda)>=0){
	                                 	     	        $_SESSION[$i]["valor_posicion_i"] = $_SESSION[$i]["valor_posicion_i"] - $tipo_moneda;
	                                 	     	     }//fin if
	                                 	     }//fin while
                                       }//fin else if
							   }else if($tipo_moneda==2 && $var_posicion[$i]["valor_posicion_i"]>=2){
							                 if($primero==true){
                                       	           $cantidad_devolver += $var_posicion[$i]["valor_posicion_i"]/$tipo_moneda;
                                       }else if($_SESSION[$i]["valor_posicion_i"]<5){
                                       	             $resta = $_SESSION[$i]["valor_posicion_i"];
	                                 	     while($resta>0){
	                                 	     	       $resta                                   = decimal_sprintf("%01.2f",$resta);
	                                 	     	       $tipo_moneda                             = decimal_sprintf("%01.2f",$tipo_moneda);
	                                 	     	       $_SESSION[$i]["valor_posicion_i"]       = decimal_sprintf("%01.2f",$_SESSION[$i]["valor_posicion_i"]);
	                                 	     	     if($resta>=$tipo_moneda){ $cantidad_devolver ++; }
	                                 	     	     $resta = $resta - $tipo_moneda;
	                                 	     	     if(($_SESSION[$i]["valor_posicion_i"]-$tipo_moneda)>=0){
	                                 	     	        $_SESSION[$i]["valor_posicion_i"] = $_SESSION[$i]["valor_posicion_i"] - $tipo_moneda;
	                                 	     	     }//fin if
	                                 	     }//fin while
                                       }//fin else if
							   }else if($tipo_moneda==1 && $var_posicion[$i]["valor_posicion_i"]>=1){
							                 if($primero==true){
                                       	           $cantidad_devolver += $var_posicion[$i]["valor_posicion_i"]/$tipo_moneda;
                                       }else if($_SESSION[$i]["valor_posicion_i"]<2){
                                       	             $resta = $_SESSION[$i]["valor_posicion_i"];
	                                 	     while($resta>0){
	                                 	     	       $resta                                  = decimal_sprintf("%01.2f",$resta);
	                                 	     	       $tipo_moneda                            = decimal_sprintf("%01.2f",$tipo_moneda);
	                                 	     	       $_SESSION[$i]["valor_posicion_i"]       = decimal_sprintf("%01.2f",$_SESSION[$i]["valor_posicion_i"]);
	                                 	     	     if($resta>=$tipo_moneda){ $cantidad_devolver ++; }
	                                 	     	     $resta = $resta - $tipo_moneda;
	                                 	     	     if(($_SESSION[$i]["valor_posicion_i"]-$tipo_moneda)>=0){
	                                 	     	        $_SESSION[$i]["valor_posicion_i"] = $_SESSION[$i]["valor_posicion_i"] - $tipo_moneda;
	                                 	     	     }//fin if
	                                 	     }//fin while
                                       }//fin else if
							   }else if($tipo_moneda==0.50 && $var_posicion[$i]["valor_posicion_i"]>=0.50){
							                 if($primero==true){
                                       	           $cantidad_devolver += $var_posicion[$i]["valor_posicion_i"]/$tipo_moneda;
                                       }else if($_SESSION[$i]["valor_posicion_i"]<1){
                                       	             $resta = $_SESSION[$i]["valor_posicion_i"];
	                                 	     while($resta>0){
	                                 	     	       $resta                                  = decimal_sprintf("%01.2f",$resta);
	                                 	     	       $tipo_moneda                            = decimal_sprintf("%01.2f",$tipo_moneda);
	                                 	     	       $_SESSION[$i]["valor_posicion_i"]       = decimal_sprintf("%01.2f",$_SESSION[$i]["valor_posicion_i"]);
	                                 	     	     if($resta>=$tipo_moneda){ $cantidad_devolver ++; }
	                                 	     	     $resta = $resta - $tipo_moneda;
	                                 	     	     if(($_SESSION[$i]["valor_posicion_i"]-$tipo_moneda)>=0){
	                                 	     	        $_SESSION[$i]["valor_posicion_i"] = $_SESSION[$i]["valor_posicion_i"] - $tipo_moneda;
	                                 	     	     }//fin if
	                                 	     }//fin while
                                       }//fin else if
                               }else if($tipo_moneda==0.25 && $var_posicion[$i]["valor_posicion_i"]>=0.25){
							                 if($primero==true){
                                       	           $cantidad_devolver += $var_posicion[$i]["valor_posicion_i"]/$tipo_moneda;
                                       }else if($_SESSION[$i]["valor_posicion_i"]<0.50){
                                       	             $resta = $_SESSION[$i]["valor_posicion_i"];
	                                 	     while($resta>0){
	                                 	     	       $resta                                  = decimal_sprintf("%01.2f",$resta);
	                                 	     	       $tipo_moneda                            = decimal_sprintf("%01.2f",$tipo_moneda);
	                                 	     	       $_SESSION[$i]["valor_posicion_i"]       = decimal_sprintf("%01.2f",$_SESSION[$i]["valor_posicion_i"]);
	                                 	     	     if($resta>=$tipo_moneda){ $cantidad_devolver ++; }
	                                 	     	     $resta = $resta - $tipo_moneda;
	                                 	     	     if(($_SESSION[$i]["valor_posicion_i"]-$tipo_moneda)>=0){
	                                 	     	        $_SESSION[$i]["valor_posicion_i"] = $_SESSION[$i]["valor_posicion_i"] - $tipo_moneda;
	                                 	     	     }//fin if
	                                 	     }//fin while
                                       }//fin else if
							   }else if($tipo_moneda==0.20 && $var_posicion[$i]["valor_posicion_i"]>=0.20){
							                 if($primero==true){
                                       	           $cantidad_devolver += $var_posicion[$i]["valor_posicion_i"]/$tipo_moneda;
                                       }else if($_SESSION[$i]["valor_posicion_i"]<0.25){
                                       	             $resta = $_SESSION[$i]["valor_posicion_i"];
	                                 	     while($resta>0){
	                                 	     	       $resta                                   = decimal_sprintf("%01.2f",$resta);
	                                 	     	       $tipo_moneda                             = decimal_sprintf("%01.2f",$tipo_moneda);
	                                 	     	       $_SESSION[$i]["valor_posicion_i"]       = decimal_sprintf("%01.2f",$_SESSION[$i]["valor_posicion_i"]);
	                                 	     	     if($resta>=$tipo_moneda){ $cantidad_devolver ++; }
	                                 	     	     $resta = $resta - $tipo_moneda;
	                                 	     	     if(($_SESSION[$i]["valor_posicion_i"]-$tipo_moneda)>=0){
	                                 	     	        $_SESSION[$i]["valor_posicion_i"] = $_SESSION[$i]["valor_posicion_i"] - $tipo_moneda;
	                                 	     	     }//fin if
	                                 	     }//fin while
                                       }//fin else if
							   }else if($tipo_moneda==0.10 && $var_posicion[$i]["valor_posicion_i"]>=0.10){
							                 if($primero==true){
                                       	           $cantidad_devolver += $var_posicion[$i]["valor_posicion_i"]/$tipo_moneda;
                                       }else if($_SESSION[$i]["valor_posicion_i"]<0.20){
                                       	             $resta = $_SESSION[$i]["valor_posicion_i"];
	                                 	     while($resta>0){
	                                 	     	       $resta                                   = decimal_sprintf("%01.2f",$resta);
	                                 	     	       $tipo_moneda                             = decimal_sprintf("%01.2f",$tipo_moneda);
	                                 	     	       $_SESSION[$i]["valor_posicion_i"]       = decimal_sprintf("%01.2f",$_SESSION[$i]["valor_posicion_i"]);
	                                 	     	     if($resta>=$tipo_moneda){ $cantidad_devolver ++; }
	                                 	     	     $resta = $resta - $tipo_moneda;
	                                 	     	     if(($_SESSION[$i]["valor_posicion_i"]-$tipo_moneda)>=0){
	                                 	     	        $_SESSION[$i]["valor_posicion_i"] = $_SESSION[$i]["valor_posicion_i"] - $tipo_moneda;
	                                 	     	     }//fin if
	                                 	     }//fin while
                                       }//fin else if
							    }else if($tipo_moneda==0.05 && $var_posicion[$i]["valor_posicion_i"]>=0.05){
							                 if($primero==true){
                                       	           $cantidad_devolver += $var_posicion[$i]["valor_posicion_i"]/$tipo_moneda;
                                       }else if($_SESSION[$i]["valor_posicion_i"]<0.10){
                                       	            $resta = $_SESSION[$i]["valor_posicion_i"];
	                                 	     while($resta>0){
	                                 	     	       $resta                                   = decimal_sprintf("%01.2f",$resta);
	                                 	     	       $tipo_moneda                             = decimal_sprintf("%01.2f",$tipo_moneda);
	                                 	     	       $_SESSION[$i]["valor_posicion_i"]       = decimal_sprintf("%01.2f",$_SESSION[$i]["valor_posicion_i"]);
	                                 	     	     if($resta>=$tipo_moneda){ $cantidad_devolver ++; }
	                                 	     	     $resta = $resta - $tipo_moneda;
	                                 	     	     if(($_SESSION[$i]["valor_posicion_i"]-$tipo_moneda)>=0){
	                                 	     	        $_SESSION[$i]["valor_posicion_i"] = $_SESSION[$i]["valor_posicion_i"] - $tipo_moneda;
	                                 	     	     }//fin if
	                                 	     }//fin while
                                       }//fin else if
							    }else if($tipo_moneda==0.01 && $var_posicion[$i]["valor_posicion_i"]>=0.01){
							                 if($primero==true){
                                       	           $cantidad_devolver += $var_posicion[$i]["valor_posicion_i"]/$tipo_moneda;
                                       }else if($_SESSION[$i]["valor_posicion_i"]<0.05){
                                       	             $resta = $_SESSION[$i]["valor_posicion_i"];
	                                 	     while($resta>0){
	                                 	     	    if(($_SESSION[$i]["valor_posicion_i"]-$tipo_moneda)>=0){
	                                 	     	        $_SESSION[$i]["valor_posicion_i"] = $_SESSION[$i]["valor_posicion_i"] - $tipo_moneda;
	                                 	     	    }//fin if
	                                 	     	       $resta                                   = decimal_sprintf("%01.2f",$resta);
	                                 	     	       $tipo_moneda                             = decimal_sprintf("%01.2f",$tipo_moneda);
	                                 	     	       $_SESSION[$i]["valor_posicion_i"]       = decimal_sprintf("%01.2f",$_SESSION[$i]["valor_posicion_i"]);
	                                 	     	     if($resta>=$tipo_moneda){ $cantidad_devolver ++;  }
	                                 	     	        $resta = $resta - $tipo_moneda;
	                                 	       }//fin while
                                       }//fin else if
							     }//fin else



}//fin for
       if($cantidad_devolver==0){$cantidad_devolver="";}
return $cantidad_devolver;
}//fin fucntion


function devolver_bolivares_moneda($cantidad=null, $tipo_moneda=null, $primero=false){

$cantidad_devolver = 0;
$cantidad          = $cantidad."";
$marca_punto       = false;
$var_y             = "";
$strlen_cantidad   = 0;
$cont              = 0;
$aux_var_posicion  = 0;

for($i=0; $i<strlen($cantidad); $i++){if( $cantidad[$i]=="."){break;}else{$strlen_cantidad++;}}

for($i=0; $i<strlen($cantidad); $i++){
	       $var_x = $cantidad[$i];
        if($var_x=="."){
        	 $marca_punto = true;
        }else{
        	 if($marca_punto==false){
        	 	for($x=$i+1; $x<$strlen_cantidad; $x++){$var_x .= "0";}
        	    $var_posicion[$cont]["valor_posicion_ii"] = $var_x;
        	    $cont++;
        	 }else{
                //$var_posicion[$cont]["valor_posicion_ii"] = "0.".$var_y.$var_x;
                if($aux_var_posicion==0){
                	  $aux_var_posicion = "0.".$var_y.$var_x;
                }else{
                      $aux_var_posicion .= $var_y.$var_x;
                }
                //$var_y .= "0";
                //$cont++;
        	 }
        }//fin else
}//fin for
$var_posicion[$cont]["valor_posicion_ii"] =  $aux_var_posicion;
$cont++;

	                                  if($tipo_moneda==100){
	                                  	$_SESSION["cantida_devolver_cantidad_moneda"] = $cantidad;
	                                  	for($i=0; $i<$cont; $i++){$_SESSION[$i]["valor_posicion_ii"] = $var_posicion[$i]["valor_posicion_ii"];}

							    }else if($tipo_moneda==50 && $primero==true){
							    	$_SESSION["cantida_devolver_cantidad_moneda"] = $cantidad;
							    	for($i=0; $i<$cont; $i++){$_SESSION[$i]["valor_posicion_ii"] = $var_posicion[$i]["valor_posicion_ii"];}

							    }else if($tipo_moneda==20 && $primero==true){
							    	$_SESSION["cantida_devolver_cantidad_moneda"] = $cantidad;
							    	for($i=0; $i<$cont; $i++){$_SESSION[$i]["valor_posicion_ii"] = $var_posicion[$i]["valor_posicion_ii"];}

							    }else if($tipo_moneda==10 && $primero==true){
							    	$_SESSION["cantida_devolver_cantidad_moneda"] = $cantidad;
							    	for($i=0; $i<$cont; $i++){$_SESSION[$i]["valor_posicion_ii"] = $var_posicion[$i]["valor_posicion_ii"];}

							    }else if($tipo_moneda==5 && $primero==true){
							    	$_SESSION["cantida_devolver_cantidad_moneda"] = $cantidad;
							    	for($i=0; $i<$cont; $i++){$_SESSION[$i]["valor_posicion_ii"] = $var_posicion[$i]["valor_posicion_ii"];}

							    }else if($tipo_moneda==2 && $primero==true){
							    	$_SESSION["cantida_devolver_cantidad_moneda"] = $cantidad;
							    	for($i=0; $i<$cont; $i++){$_SESSION[$i]["valor_posicion_ii"] = $var_posicion[$i]["valor_posicion_ii"];}

							    }else if($tipo_moneda==1 && $primero==true){
							    	$_SESSION["cantida_devolver_cantidad_moneda"] = $cantidad;
							    	for($i=0; $i<$cont; $i++){$_SESSION[$i]["valor_posicion_ii"] = $var_posicion[$i]["valor_posicion_ii"];}

							    }else if($tipo_moneda==0.50 && $primero==true){
							    	$_SESSION["cantida_devolver_cantidad_moneda"] = $cantidad;
							    	for($i=0; $i<$cont; $i++){$_SESSION[$i]["valor_posicion_ii"] = $var_posicion[$i]["valor_posicion_ii"];}

							    }else if($tipo_moneda==0.25 && $primero==true){
							    	$_SESSION["cantida_devolver_cantidad_moneda"] = $cantidad;
							    	for($i=0; $i<$cont; $i++){$_SESSION[$i]["valor_posicion_ii"] = $var_posicion[$i]["valor_posicion_ii"];}

							    }else if($tipo_moneda==0.20 && $primero==true){
							    	$_SESSION["cantida_devolver_cantidad_moneda"] = $cantidad;
							    	for($i=0; $i<$cont; $i++){$_SESSION[$i]["valor_posicion_ii"] = $var_posicion[$i]["valor_posicion_ii"];}

							    }else if($tipo_moneda==0.10 && $primero==true){
							    	$_SESSION["cantida_devolver_cantidad_moneda"] = $cantidad;
							    	for($i=0; $i<$cont; $i++){$_SESSION[$i]["valor_posicion_ii"] = $var_posicion[$i]["valor_posicion_ii"];}

							    }else if($tipo_moneda==0.05 && $primero==true){
							    	$_SESSION["cantida_devolver_cantidad_moneda"] = $cantidad;
							    	for($i=0; $i<$cont; $i++){$_SESSION[$i]["valor_posicion_ii"] = $var_posicion[$i]["valor_posicion_ii"];}

							    }else if($tipo_moneda==0.01 && $primero==true){
							    	$_SESSION["cantida_devolver_cantidad_moneda"] = $cantidad;
							    	for($i=0; $i<$cont; $i++){$_SESSION[$i]["valor_posicion_ii"] = $var_posicion[$i]["valor_posicion_ii"];}

						        }//fin else




for($i=0; $i<$cont; $i++){

	                                 if($tipo_moneda==100 && $var_posicion[$i]["valor_posicion_ii"]>=100){
	                                 	     $resta = $_SESSION[$i]["valor_posicion_ii"];
	                                 	     while($resta>0){
	                                 	     	     $resta                                   = decimal_sprintf("%01.2f",$resta);
	                                 	     	     $tipo_moneda                             = decimal_sprintf("%01.2f",$tipo_moneda);
	                                 	     	     $_SESSION[$i]["valor_posicion_ii"]       = decimal_sprintf("%01.2f",$_SESSION[$i]["valor_posicion_ii"]);
	                                 	     	  if($resta>=$tipo_moneda){ $cantidad_devolver ++; }
	                                 	     	  $resta = $resta - $tipo_moneda;
	                                 	     	  if(($_SESSION[$i]["valor_posicion_ii"]-$tipo_moneda)>=0){
	                                 	     	     $_SESSION[$i]["valor_posicion_ii"] = $_SESSION[$i]["valor_posicion_ii"] - $tipo_moneda;
	                                 	     	  }//fin if
	                                 	     }//fin while
							   }else if($tipo_moneda==50 && $var_posicion[$i]["valor_posicion_ii"]>=50){
							                 if($primero==true){
                                       	           $cantidad_devolver += $var_posicion[$i]["valor_posicion_ii"]/$tipo_moneda;
                                       }else if($_SESSION[$i]["valor_posicion_ii"]<100){
                                       	     $resta = $_SESSION[$i]["valor_posicion_ii"];
	                                 	     while($resta>0){
	                                 	     	     $resta                                   = decimal_sprintf("%01.2f",$resta);
	                                 	     	     $tipo_moneda                             = decimal_sprintf("%01.2f",$tipo_moneda);
	                                 	     	     $_SESSION[$i]["valor_posicion_ii"]       = decimal_sprintf("%01.2f",$_SESSION[$i]["valor_posicion_ii"]);
	                                 	     	     if($resta>=$tipo_moneda){ $cantidad_devolver ++; }
	                                 	     	     $resta = $resta - $tipo_moneda;
	                                 	     	     if(($_SESSION[$i]["valor_posicion_ii"]-$tipo_moneda)>=0){
	                                 	     	        $_SESSION[$i]["valor_posicion_ii"] = $_SESSION[$i]["valor_posicion_ii"] - $tipo_moneda;
	                                 	     	     }//fin if
	                                 	     }//fin while
                                       }//fin else if
							   }else if($tipo_moneda==20 && $var_posicion[$i]["valor_posicion_ii"]>=20){
							                 if($primero==true){
                                       	           $cantidad_devolver += $var_posicion[$i]["valor_posicion_ii"]/$tipo_moneda;
                                       }else if($_SESSION[$i]["valor_posicion_ii"]<50){
                                       	             $resta = $_SESSION[$i]["valor_posicion_ii"];
	                                 	     while($resta>0){
	                                 	     	     $resta                                   = decimal_sprintf("%01.2f",$resta);
	                                 	     	     $tipo_moneda                             = decimal_sprintf("%01.2f",$tipo_moneda);
	                                 	     	     $_SESSION[$i]["valor_posicion_ii"]       = decimal_sprintf("%01.2f",$_SESSION[$i]["valor_posicion_ii"]);
	                                 	     	     if($resta>=$tipo_moneda){ $cantidad_devolver ++; }
	                                 	     	     $resta = $resta - $tipo_moneda;
	                                 	     	     if(($_SESSION[$i]["valor_posicion_ii"]-$tipo_moneda)>=0){
	                                 	     	         $_SESSION[$i]["valor_posicion_ii"] = $_SESSION[$i]["valor_posicion_ii"] - $tipo_moneda;
	                                 	     	     }//fin if
	                                 	     }//fin while
                                       }//fin else if
							   }else if($tipo_moneda==10 && $var_posicion[$i]["valor_posicion_ii"]>=10){
							                 if($primero==true){
                                       	           $cantidad_devolver += $var_posicion[$i]["valor_posicion_ii"]/$tipo_moneda;
                                       }else if($_SESSION[$i]["valor_posicion_ii"]<20){
                                       	             $resta = $_SESSION[$i]["valor_posicion_ii"];
	                                 	     while($resta>0){
	                                 	     	     $resta                                   = decimal_sprintf("%01.2f",$resta);
	                                 	     	     $tipo_moneda                             = decimal_sprintf("%01.2f",$tipo_moneda);
	                                 	     	     $_SESSION[$i]["valor_posicion_ii"]       = decimal_sprintf("%01.2f",$_SESSION[$i]["valor_posicion_ii"]);
	                                 	     	     if($resta>=$tipo_moneda){ $cantidad_devolver ++; }
	                                 	     	     $resta = $resta - $tipo_moneda;
	                                 	     	     if(($_SESSION[$i]["valor_posicion_ii"]-$tipo_moneda)>=0){
	                                 	     	         $_SESSION[$i]["valor_posicion_ii"] = $_SESSION[$i]["valor_posicion_ii"] - $tipo_moneda;
	                                 	     	     }//fin if
	                                 	     }//fin while
                                       }//fin else if
							   }else if($tipo_moneda==5 && $var_posicion[$i]["valor_posicion_ii"]>=5){
							                 if($primero==true){
                                       	           $cantidad_devolver += $var_posicion[$i]["valor_posicion_ii"]/$tipo_moneda;
                                       }else if($_SESSION[$i]["valor_posicion_ii"]<10){
                                       	             $resta = $_SESSION[$i]["valor_posicion_ii"];
	                                 	     while($resta>0){
	                                 	     	     $resta                                   = decimal_sprintf("%01.2f",$resta);
	                                 	     	     $tipo_moneda                             = decimal_sprintf("%01.2f",$tipo_moneda);
	                                 	     	     $_SESSION[$i]["valor_posicion_ii"]       = decimal_sprintf("%01.2f",$_SESSION[$i]["valor_posicion_ii"]);
	                                 	     	     if($resta>=$tipo_moneda){ $cantidad_devolver ++; }
	                                 	     	     $resta = $resta - $tipo_moneda;
	                                 	     	     if(($_SESSION[$i]["valor_posicion_ii"]-$tipo_moneda)>=0){
	                                 	     	        $_SESSION[$i]["valor_posicion_ii"] = $_SESSION[$i]["valor_posicion_ii"] - $tipo_moneda;
	                                 	     	     }//fin if
	                                 	     }//fin while
                                       }//fin else if
							   }else if($tipo_moneda==2 && $var_posicion[$i]["valor_posicion_ii"]>=2){
							                 if($primero==true){
                                       	           $cantidad_devolver += $var_posicion[$i]["valor_posicion_ii"]/$tipo_moneda;
                                       }else if($_SESSION[$i]["valor_posicion_ii"]<5){
                                       	             $resta = $_SESSION[$i]["valor_posicion_ii"];
	                                 	     while($resta>0){
	                                 	     	     $resta                                   = decimal_sprintf("%01.2f",$resta);
	                                 	     	     $tipo_moneda                             = decimal_sprintf("%01.2f",$tipo_moneda);
	                                 	     	     $_SESSION[$i]["valor_posicion_ii"]       = decimal_sprintf("%01.2f",$_SESSION[$i]["valor_posicion_ii"]);
	                                 	     	     if($resta>=$tipo_moneda){ $cantidad_devolver ++; }
	                                 	     	     $resta = $resta - $tipo_moneda;
	                                 	     	     if(($_SESSION[$i]["valor_posicion_ii"]-$tipo_moneda)>=0){
	                                 	     	        $_SESSION[$i]["valor_posicion_ii"] = $_SESSION[$i]["valor_posicion_ii"] - $tipo_moneda;
	                                 	     	     }//fin if
	                                 	     }//fin while
                                       }//fin else if
							   }else if($tipo_moneda==1 && $var_posicion[$i]["valor_posicion_ii"]>=1){
							                 if($primero==true){
                                       	           $cantidad_devolver += $var_posicion[$i]["valor_posicion_ii"]/$tipo_moneda;
                                       }else if($_SESSION[$i]["valor_posicion_ii"]<2){
                                       	             $resta = $_SESSION[$i]["valor_posicion_ii"];
	                                 	     while($resta>0){
	                                 	     	     $resta                                   = decimal_sprintf("%01.2f",$resta);
	                                 	     	     $tipo_moneda                             = decimal_sprintf("%01.2f",$tipo_moneda);
	                                 	     	     $_SESSION[$i]["valor_posicion_ii"]       = decimal_sprintf("%01.2f",$_SESSION[$i]["valor_posicion_ii"]);
	                                 	     	     if($resta>=$tipo_moneda){ $cantidad_devolver ++; }
	                                 	     	     $resta = $resta - $tipo_moneda;
	                                 	     	     if(($_SESSION[$i]["valor_posicion_ii"]-$tipo_moneda)>=0){
	                                 	     	        $_SESSION[$i]["valor_posicion_ii"] = $_SESSION[$i]["valor_posicion_ii"] - $tipo_moneda;
	                                 	     	     }//fin if
	                                 	     }//fin while
                                       }//fin else if
							   }else if($tipo_moneda==0.50 && $var_posicion[$i]["valor_posicion_ii"]>=0.50){
							                 if($primero==true){
                                       	           $cantidad_devolver += $var_posicion[$i]["valor_posicion_ii"]/$tipo_moneda;
                                       }else if($_SESSION[$i]["valor_posicion_ii"]<1){
                                       	             $resta = $_SESSION[$i]["valor_posicion_ii"];
	                                 	     while($resta>0){
	                                 	     	     $resta                                   = decimal_sprintf("%01.2f",$resta);
	                                 	     	     $tipo_moneda                             = decimal_sprintf("%01.2f",$tipo_moneda);
	                                 	     	     $_SESSION[$i]["valor_posicion_ii"]       = decimal_sprintf("%01.2f",$_SESSION[$i]["valor_posicion_ii"]);
	                                 	     	     if($resta>=$tipo_moneda){ $cantidad_devolver ++; }
	                                 	     	     $resta = $resta - $tipo_moneda;
	                                 	     	     if(($_SESSION[$i]["valor_posicion_ii"]-$tipo_moneda)>=0){
	                                 	     	        $_SESSION[$i]["valor_posicion_ii"] = $_SESSION[$i]["valor_posicion_ii"] - $tipo_moneda;
	                                 	     	     }//fin if
	                                 	     }//fin while
                                       }//fin else if
                               }else if($tipo_moneda==0.25 && $var_posicion[$i]["valor_posicion_ii"]>=0.25){
							                 if($primero==true){
                                       	           $cantidad_devolver += $var_posicion[$i]["valor_posicion_ii"]/$tipo_moneda;
                                       }else if($_SESSION[$i]["valor_posicion_ii"]<0.50){
                                       	             $resta = $_SESSION[$i]["valor_posicion_ii"];
	                                 	     while($resta>0){
	                                 	     	     $resta                                   = decimal_sprintf("%01.2f",$resta);
	                                 	     	     $tipo_moneda                             = decimal_sprintf("%01.2f",$tipo_moneda);
	                                 	     	     $_SESSION[$i]["valor_posicion_ii"]       = decimal_sprintf("%01.2f",$_SESSION[$i]["valor_posicion_ii"]);
	                                 	     	     if($resta>=$tipo_moneda){ $cantidad_devolver ++; }
	                                 	     	     $resta = $resta - $tipo_moneda;
	                                 	     	     if(($_SESSION[$i]["valor_posicion_ii"]-$tipo_moneda)>=0){
	                                 	     	        $_SESSION[$i]["valor_posicion_ii"] = $_SESSION[$i]["valor_posicion_ii"] - $tipo_moneda;
	                                 	     	     }//fin if
	                                 	     }//fin while
                                       }//fin else if
							   }else if($tipo_moneda==0.20 && $var_posicion[$i]["valor_posicion_ii"]>=0.20){
							                 if($primero==true){
                                       	           $cantidad_devolver += $var_posicion[$i]["valor_posicion_ii"]/$tipo_moneda;
                                       }else if($_SESSION[$i]["valor_posicion_ii"]<0.25){
                                       	             $resta = $_SESSION[$i]["valor_posicion_ii"];
	                                 	     while($resta>0){
	                                 	     	     $resta                                   = decimal_sprintf("%01.2f",$resta);
	                                 	     	     $tipo_moneda                             = decimal_sprintf("%01.2f",$tipo_moneda);
	                                 	     	     $_SESSION[$i]["valor_posicion_ii"]       = decimal_sprintf("%01.2f",$_SESSION[$i]["valor_posicion_ii"]);
	                                 	     	     if($resta>=$tipo_moneda){ $cantidad_devolver ++; }
	                                 	     	     $resta = $resta - $tipo_moneda;
	                                 	     	     if(($_SESSION[$i]["valor_posicion_ii"]-$tipo_moneda)>=0){
	                                 	     	        $_SESSION[$i]["valor_posicion_ii"] = $_SESSION[$i]["valor_posicion_ii"] - $tipo_moneda;
	                                 	     	     }//fin if
	                                 	     }//fin while
                                       }//fin else if
							   }else if($tipo_moneda==0.10 && $var_posicion[$i]["valor_posicion_ii"]>=0.10){
							                 if($primero==true){
                                       	           $cantidad_devolver += $var_posicion[$i]["valor_posicion_ii"]/$tipo_moneda;
                                       }else if($_SESSION[$i]["valor_posicion_ii"]<0.20){
                                       	             $resta = $_SESSION[$i]["valor_posicion_ii"];
	                                 	     while($resta>0){
	                                 	     	     $resta                                   = decimal_sprintf("%01.2f",$resta);
	                                 	     	     $tipo_moneda                             = decimal_sprintf("%01.2f",$tipo_moneda);
	                                 	     	     $_SESSION[$i]["valor_posicion_ii"]       = decimal_sprintf("%01.2f",$_SESSION[$i]["valor_posicion_ii"]);
	                                 	     	     if($resta>=$tipo_moneda){ $cantidad_devolver ++; }
	                                 	     	     $resta = $resta - $tipo_moneda;
	                                 	     	     if(($_SESSION[$i]["valor_posicion_ii"]-$tipo_moneda)>=0){
	                                 	     	        $_SESSION[$i]["valor_posicion_ii"] = $_SESSION[$i]["valor_posicion_ii"] - $tipo_moneda;
	                                 	     	     }//fin if
	                                 	     }//fin while
                                       }//fin else if
							    }else if($tipo_moneda==0.05 && $var_posicion[$i]["valor_posicion_ii"]>=0.05){
							                 if($primero==true){
                                       	           $cantidad_devolver += $var_posicion[$i]["valor_posicion_ii"]/$tipo_moneda;
                                       }else if($_SESSION[$i]["valor_posicion_ii"]<0.10){
                                       	            $resta = $_SESSION[$i]["valor_posicion_ii"];
	                                 	     while($resta>0){
	                                 	     	     $resta                                   = decimal_sprintf("%01.2f",$resta);
	                                 	     	     $tipo_moneda                             = decimal_sprintf("%01.2f",$tipo_moneda);
	                                 	     	     $_SESSION[$i]["valor_posicion_ii"]       = decimal_sprintf("%01.2f",$_SESSION[$i]["valor_posicion_ii"]);
	                                 	     	     if($resta>=$tipo_moneda){ $cantidad_devolver ++; }
	                                 	     	     $resta = $resta - $tipo_moneda;
	                                 	     	     if(($_SESSION[$i]["valor_posicion_ii"]-$tipo_moneda)>=0){
	                                 	     	        $_SESSION[$i]["valor_posicion_ii"] = $_SESSION[$i]["valor_posicion_ii"] - $tipo_moneda;

	                                 	     	     }//fin if
	                                 	     }//fin while
                                       }//fin else if
							    }else if($tipo_moneda==0.01 && $var_posicion[$i]["valor_posicion_ii"]>=0.01){
							                 if($primero==true){
                                       	           $cantidad_devolver += $var_posicion[$i]["valor_posicion_ii"]/$tipo_moneda;
                                       }else if($_SESSION[$i]["valor_posicion_ii"]<0.05){
                                       	             $resta = $_SESSION[$i]["valor_posicion_ii"];
	                                 	     while($resta>0){
	                                 	     	     $resta                                   = decimal_sprintf("%01.2f",$resta);
	                                 	     	     $tipo_moneda                             = decimal_sprintf("%01.2f",$tipo_moneda);
	                                 	     	     $_SESSION[$i]["valor_posicion_ii"]       = decimal_sprintf("%01.2f",$_SESSION[$i]["valor_posicion_ii"]);
	                                 	     	     if($resta>=$tipo_moneda){ $cantidad_devolver ++; }
	                                 	     	     $resta = $resta - $tipo_moneda;
	                                 	     	     if(($_SESSION[$i]["valor_posicion_ii"]-$tipo_moneda)>=0){
	                                 	     	        $_SESSION[$i]["valor_posicion_ii"] = $_SESSION[$i]["valor_posicion_ii"] - $tipo_moneda;
	                                 	     	     }//fin if
	                                 	     }//fin while
                                       }//fin else if
							   }//fin else



}//fin for
          $cantidad_devolver = ($cantidad_devolver * $tipo_moneda);
       if($cantidad_devolver==0){$cantidad_devolver="";}
return $cantidad_devolver;
}//fin fucntion

/**
 * chmod recursively on a directory
 *
 * @param string $path
 * @param int $mode
 * @return boolean
 */
	function chmodr($path, $mode = 0755) {
		if (!is_dir($path)) {
			return chmod($path, $mode);
		}
		$dir = opendir($path);

		while($file = readdir($dir)) {
			if ($file != '.' && $file != '..') {
				$fullpath = $path . '/' . $file;

				if (!is_dir($fullpath)) {
					if (!chmod($fullpath, $mode)) {
						return false;
					}
				} else {
					if (!chmodr($fullpath, $mode)) {
						return false;
					}
				}
			}
		}
		closedir($dir);

		if (chmod($path, $mode)) {
			return true;
		} else {
			return false;
		}
	}
/**
 * removed the plugin name from the base url
 *
 * @param string $base
 * @param string $plugin
 * @return base url with plugin name removed if present
 */
	function strip_plugin($base, $plugin){
		if ($plugin != null) {
			$base = preg_replace('/' . $plugin . '/', '', $base);
			$base = str_replace('//', '', $base);
			$pos1 = strrpos($base, '/');
			$char = strlen($base) - 1;

			if ($pos1 == $char) {
				$base = substr($base, 0, $char);
			}
		}
		return $base;
	}
/**
 * Wraps ternary operations.  If $condition is a non-empty value, $val1 is returned, otherwise $val2.
 *
 * @param mixed $condition Conditional expression
 * @param mixed $val1
 * @param mixed $val2
 * @return mixed $val1 or $val2, depending on whether $condition evaluates to a non-empty expression.
 */
	function ife($condition, $val1 = null, $val2 = null) {
		if (!empty($condition)) {
			return $val1;
		}
		return $val2;
	}



function cambiar_1($cadena) {
		     $cadena = str_replace("à","Á", $cadena);
		     $cadena = str_replace("è","É", $cadena);
		     $cadena = str_replace("ì","Í", $cadena);
		     $cadena = str_replace("ò","Ó", $cadena);
		     $cadena = str_replace("ù","Ú", $cadena);
		     $cadena = str_replace("á","Á", $cadena);
		     $cadena = str_replace("é","É", $cadena);
		     $cadena = str_replace("í","Í", $cadena);
		     $cadena = str_replace("ó","Ó", $cadena);
		     $cadena = str_replace("ú","Ú", $cadena);
		     $cadena = str_replace("ñ","Ñ", $cadena);
		     return $cadena;
}

function cambiar_mayuscula_basic($txt=null){
	            $txt = cambiar_1($txt);
                $txt = strtoupper($txt);
	            $txt = str_replace("&RSQUO;","''",  $txt);
			    $txt = str_replace("&GT;",">",      $txt);
			    $txt = str_replace("&LT;","<",      $txt);
			    $txt = str_replace("&LDQUO;",'"',    $txt);
	return  $txt;
}


function edad_basic($nacimiento){
//restamos los años (año actual - año cumpleaños)
$edad = date("Y") - ereg_replace("^(.{4}).*","\\1",$nacimiento);

//si pasamos de año, pero aún no cumplimos años, resta 1
if( date("m-d") < ereg_replace(".*(.{5})$","\\1",$nacimiento) )
 $edad--;

return $edad;
}


/**
 * Esta funcion devuelve la fecha en formato contrario al del parametro
 * @param date $fecha
 * formato: 02/08/1984  >> 1984-08-02
 * formato: 1984-08-02  >> 02/08/1984
 */
	function cambiar_formato_fecha($fecha){
               $f1=explode('-',$fecha);//2008-01-01
               $f2=explode('/',$fecha);//01/01/2008
               if(count($f1)==3){
                   return "".$f1[2]."/".$f1[1]."/".$f1[0];
               }else if(count($f2)==3){
                   return "".$f2[2]."-".$f2[1]."-".$f2[0];
               }else{
                   return null;
               }
      }//fin funcion
/**
 * Esta funcion devuelve DIA o MES o ANO deacuerdo con el parametro para peticion
 * @param date $fecha
 * @param text $peticion dia o mes o ano
 */
      function divide_fecha($fecha,$devolver){

               $f1=explode('-',$fecha);//2008-01-01
               $f2=explode('/',$fecha);//01/01/2008
               if(count($f1)==3){
                   switch(strtoupper($devolver)){
                   	case 'DIA': $R= "".$f1[2]; break;
                        case 'MES': $R= "".$f1[1]; break;
                        case 'ANO': $R= "".$f1[0]; break;
                        default:$R=null;
                   }
                   return $R;
               }else if(count($f2)==3){
                   switch(strtoupper($devolver)){
                   	case 'DIA': $R= "".$f2[0]; break;
                        case 'MES': $R= "".$f2[1]; break;
                        case 'ANO': $R= "".$f2[2]; break;
                        default:$R=null;
                   }
                   return $R;
               }else{
                   return null;
               }
      }//fin funcion

      /**
       * Mascara para rellenar con cualquier cantidad
       * parametro: $numero es el valor el cual se desea rellenar con 0
       * parametro: $cantidad_relleno cantidad de ceros que va a rellenar
       */
      function mascara($numero=0,$cantidad_relleno=1){
        return str_pad($numero, $cantidad_relleno , "0", STR_PAD_LEFT);
	  }
      /**
       * Parametros: ayuda para el helper
       * parametro: $numero
       * parametro: $cantidad_relleno
       */
      function array_helper($numero=null, $cantidad_relleno=null, $date1=null, $date2=null){
        return array("2", "{", $date2, "}", $cantidad_relleno, ":", "1", $date1, ".", $numero,",");
      }
	  /**
		* restar dos fechas
		* */
		function restar_fechas($date2,$date1){
		$s = strtotime($date1)-strtotime($date2);
		$d = intval($s/86400);
		$s -= $d*86400;
		$h = intval($s/3600);
		$s -= $h*3600;
		$m = intval($s/60);
		$s -= $m*60;

		//$dif= (($d*24)+$h)." hrs ".$m."min";
		//$dif2= $d.$space." dias ".$h.hrs." ".$m."min";
		   return $d;
		}


		function extension ($v) {
		$ext = array(
	         'image/jpeg'=>'jpg',
	         'image/png'=>'png',
	         'image/gif'=>'gif',
	         'image/bmp'=>'bmp',
	         'application/zip'=>'zip',
	         'application/vnd.ms-powerpoint'=>'pps',
	         'application/vnd.ms-excel'=>'xls',
	         'application/msword'=>'doc',
	         'application/rar'=>'rar',
	         'application/pdf'=>'pdf',
	         'application/octet-stream'=>'flv'

	        );
	        return $ext[trim($v)];
	    }//fin extension

	    function divide_timestamp($fecha_tiempo,$devolver){
               switch(strtoupper($devolver)){
                   	    case 'HORA': $R= "".date('g:i a', strtotime($fecha_tiempo)); break;
                   	    case 'HORA2': $R= "".date('g:i a', strtotime($fecha_tiempo)); break;
                        case 'FECHA': $R= "".date('d/m/Y', strtotime($fecha_tiempo)); break;
                        case 'FECHA_HORA': $R= "".date('d/m/Y g:i a', strtotime($fecha_tiempo)); break;
                        case 'HO': $R= "".date('g', strtotime($fecha_tiempo)); break;
                        case 'MI': $R= "".date('i', strtotime($fecha_tiempo)); break;
                        case 'ME': $R= "".date('a', strtotime($fecha_tiempo)); break;
                        default:$R=null;
                   }
                   return $R;
         }//fin funcion
    /**
     * Funcion para validar el RIF
     * el parametro que se le pasa es el rif
     *
     * returna array
     * con indices error (true/false)
     *             msj text
     *
     */
	function validar_rif($rif){
		if ( !eregi("^[JVEGPRI]$", $rif[0]) ) {
		        return array('error'=>true,'msj'=>'Primer caracter no válido '.$rif);
		}else{
		     if(!eregi("^[JVEGPIR][-][0-9]{8}[-][0-9]", $rif)){
		         return array('error'=>true,'msj'=>'Rif no valido '.$rif);
		     }else{
		        return array('error'=>false,'msj'=>$rif);
		     }
		}
	}//fin validar rif

	function mascara_rif ($rif) {
          $len = strlen($rif);
          echo $len;
          $var = '';
          for($i=0;$i<$len;$i++){
          	if($i==1 && $rif[$i]!='-'){
                   $var .='-'.$rif[$i];
          	}else if($i==($len-1) && $rif[$i-1]!='-'){
                   $var .='-'.$rif[$i];
                }else{
          		$var .= $rif[$i];
          	}
          }
         return $var;
	}//fin funcion mascara_rif

    function email_error_sisap ($msj_error_sisap=null) {
        Vendor('phpmailer/mailsisap');
		$mensaje ="";
		$mensaje .= sprintf("<div>HTTP_HOST: <b>%s</b> </div>",      isset($_SERVER['HTTP_HOST'])?$_SERVER['HTTP_HOST']:'VACIO');
		$mensaje .= sprintf("<div>REQUEST_URI: <b>%s</b> </div>",    isset($_SERVER['REQUEST_URI'])?$_SERVER['REQUEST_URI']:'VACIO');
		$mensaje .= sprintf("<div>REDIRECT_STATUS: <b>%s</b> </div>",isset($_SERVER['REDIRECT_STATUS'])?$_SERVER['REDIRECT_STATUS']:'VACIO');
		$mensaje .= sprintf("<div>HTTP_USER_AGENT:<b>%s</b> </div>",isset($_SERVER['HTTP_USER_AGENT'])?$_SERVER['HTTP_USER_AGENT']:'VACIO');
		$mensaje .= sprintf("<div>HTTP_REFERER: <b>%s</b> </div>",   isset($_SERVER['HTTP_REFERER'])?$_SERVER['HTTP_REFERER']:'VACIO');
		$mensaje .= sprintf("<div>RESQ_URI: <b>%s</b> </div>",$_REQUEST['url']);
		$mensaje .= sprintf("<div>USUARIO: <b>%s</b> </div>",   isset($_SESSION["nom_usuario"])?$_SESSION["nom_usuario"]:'VACIO');
		$mensaje .= sprintf("<div>DEP: <b>%s</b> </div>",   isset($_SESSION["SScoddep"])?$_SESSION["SScoddep"]:'VACIO');
		$mensaje .= sprintf("<div>IP: <b>%s</b> </div>",   isset($_SERVER['REMOTE_ADDR'])?$_SERVER['REMOTE_ADDR']:'VACIO');
		$mensaje .= sprintf("<div>ERROR: <hr>%s</div>",   isset($msj_error_sisap)?$msj_error_sisap:'VACIO');

		$destinatarios[]=array('email'=>'jose.segovia.r@gmail.com','nombre'=>'Ing. José G. Segovia R.');

		$mailsisap = new MailSisap();
		$mailsisap->Asunto             = "Error codigo SISAP";
		$mailsisap->Mensaje            = '<div style="text-align:left; color:red;font-size:15px;">SE HA ENCONTRADO ERROR 404:</div>'.$mensaje.'';
		$mailsisap->Destinatario       = $destinatarios;
		$mailsisap->mensaje_retorno = 'Se ha enviado un correo electrónico para notificar el error';
		$result = $mailsisap->sendgmail();
	}//fin funcion email_error_sisap



    function elimina_acentos($cadena=null){

    	$s = "";

    if($cadena!=null){
			   $s = str_replace("á","a",$cadena);
			   $s = str_replace("Á","A",$s);
			   $s = str_replace("Í","I",$s);
			   $s = str_replace("í","i",$s);
			   $s = str_replace("é","e",$s);
			   $s = str_replace("É","E",$s);
			   $s = str_replace("ó","o",$s);
			   $s = str_replace("Ó","O",$s);
			   $s = str_replace("ú","u",$s);
			   $s = str_replace("Ú","U",$s);
			   $s = str_replace("Ü","U",$s);
			   $s = str_replace("ü","u",$s);
     }


	   return $s;
    }




function mascara_espacio($var=0,$cantidad_relleno=1){
        return str_pad($var, $cantidad_relleno , " ", STR_PAD_RIGHT).'';
}

function cortar_cadena_diskette($string, $limit) {
    if(strlen($string)>$limit){
        $string = substr($string, 0, $limit);
    }else{
        $string = mascara_espacio($string,$limit);
    }

    return $string;
}

function formato_cuenta_diskette($cuenta = "0"){
        if(strlen($cuenta)>15){
        	    $count_underescore=substr_count (strtoupper($cuenta), '-');
        	    if($count_underescore==2){
                    $num_aux = explode('-',$cuenta);
                	$nueva_cuenta_bancaria = substr($num_aux[0],-2).'-'.$num_aux[1].'-'.$num_aux[2];
                	$nueva_cuenta_bancaria = str_pad($nueva_cuenta_bancaria, 11 , " ", STR_PAD_RIGHT).'';
        	    }else{
                    $nueva_cuenta_bancaria = 'INVALID    ';
        	    }
        }else{
			$nueva_cuenta_bancaria = 'INVALID    ';
        }
	return $nueva_cuenta_bancaria;
}




?>
