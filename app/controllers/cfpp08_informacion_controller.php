<?php

class Cfpp08InformacionController extends AppController{

    var $name    = "cfpp08_informacion";
    var $uses    = array('cfpd01_formulacion','cfpd08_ident_inst','cfpd08_ident_dir_inst','cfpd08_ident_clp','cfpd11_pol_pres_finan','ccfd04_cierre_mes');
	var $helpers = array('Html','Ajax','Javascript', 'Sisap', 'Fpdf');


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

   function SQLCX($ano=null){//sql para busqueda de codigos de arranque con y sin año
         $sql_re = "cod_presi=".$this->verifica_SS(1)."  and    ";
         $sql_re .= "cod_entidad=".$this->verifica_SS(2)."  and  ";
         $sql_re .= "cod_tipo_inst=".$this->verifica_SS(3)."  and ";
         $sql_re .= "cod_inst=".$this->verifica_SS(4)." ";
		 return $sql_re;
    }//fin funcion SQLX



function index($otro=null){
 	$this->layout ="ajax";

	$this->data=null;

	$this->set('institucion', $this->verifica_SS(3));

	$a = $this->cfpd01_formulacion->findAll($this->SQLCX());
	if($a != null){
		$ano_formulacion = $a[0]['cfpd01_formulacion']['ano_formular'];
	}else{
		$ano_formulacion='';
	}

	$ano_actual = date("Y");
	for($minCount = 2007; $minCount < 2030; $minCount++) {
	    $anos[sprintf('%02d', $minCount)] = sprintf('%02d', $minCount);
	}

	if($ano_actual == $anos[sprintf('%02d', $minCount-1)]){
		$ano_actual_fin = $ano_actual+30;
		for($minCount = $ano_actual; $minCount < $ano_actual_fin; $minCount++) {
	    	$anos[sprintf('%02d', $minCount)] = sprintf('%02d', $minCount);
		}
	}

    $this->set('anos',$anos);
    $this->set('ano_formulacion',$ano_formulacion);


	if(isset($_SESSION["DATOS1"])){
 		$this->Session->delete("DATOS1");
	}

	if(isset($_SESSION["DATOS2"])){
		$this->Session->delete("DATOS2");
	}


 	if($otro=='1'){
 		$this->data=null;
	}else if($otro==null){
		$a = $this->cfpd01_formulacion->findAll($this->SQLCX());
		if($a != null){
			$ano_formulacion = $a[0]['cfpd01_formulacion']['ano_formular'];
		}else{
			$ano_formulacion='';
		}
	if($ano_formulacion != null){
	$verxx = $this->cfpd08_ident_inst->findCount($this->SQLCA().' and ejercicio_fiscal='.$ano_formulacion);
	if($verxx=='1'){
		$this->consulta2($ano_formulacion);
		$this->render("consulta2");
	}
	}
	}
}//fin function


function agregar_grilla1(){

	$this->layout = "ajax";
   	$codigo_directivos = $this->data['cfpd08_informacion']['codigo_directivos'];
   	$direccion_administrativa = $this->data['cfpd08_informacion']['direccion_administrativa'];
   	$nombres_directivo = $this->data['cfpd08_informacion']['nombres_directivo'];
   	$correo_directivos = $this->data['cfpd08_informacion']['correo_directivos'];
   	$telefonos_directivos = $this->data['cfpd08_informacion']['telefonos_directivos'];

			if(!isset($_SESSION["DATOS1"])){
	              $_SESSION["CUENTA1"] = 1;
	              $cont = $_SESSION["CUENTA1"];
	              $_SESSION["DATOS1"][$cont]["codigo_directivos"] = $codigo_directivos;
	              $_SESSION["DATOS1"][$cont]["direccion_administrativa"] = $direccion_administrativa;
	              $_SESSION["DATOS1"][$cont]["nombres_directivo"] = $nombres_directivo;
	              $_SESSION["DATOS1"][$cont]["correo_directivos"] = $correo_directivos;
	              $_SESSION["DATOS1"][$cont]["telefonos_directivos"] = $telefonos_directivos;
	              $_SESSION["DATOS1"][$cont]["activa"]           	 = 1;
	              $_SESSION["DATOS1"][$cont]["id"]               	 = $cont;
	              $this->set("Message_existe", "EL REGISTRO FUE AGREGADO");
			}else{
                  $cont = $_SESSION["CUENTA1"];
                  $marca = 0;
		          for($i=1; $i<=$cont; $i++){
		             if($codigo_directivos==$_SESSION["DATOS1"][$i]["codigo_directivos"]  &&  $_SESSION["DATOS1"][$i]["activa"]==1){
                         $marca=1;
		             }//fin if
		          }//fin for
	              if($marca==1){
                    $this->set("errorMessage", "EL REGISTRO YA EXISTE");
	              }else{
                  $cont = $_SESSION["CUENTA1"];  $cont++; $_SESSION["CUENTA1"] = $cont;
	              $_SESSION["DATOS1"][$cont]["codigo_directivos"] = $codigo_directivos;
	              $_SESSION["DATOS1"][$cont]["direccion_administrativa"] = $direccion_administrativa;
	              $_SESSION["DATOS1"][$cont]["nombres_directivo"] = $nombres_directivo;
	              $_SESSION["DATOS1"][$cont]["correo_directivos"] = $correo_directivos;
	              $_SESSION["DATOS1"][$cont]["telefonos_directivos"] = $telefonos_directivos;
				  $_SESSION["DATOS1"][$cont]["activa"]           		= 1;
				  $_SESSION["DATOS1"][$cont]["id"]               		= $cont;
                  $this->set("Message_existe", "EL REGISTRO FUE AGREGADO");
	              }//fin else
			}//fin else

/*
	$cont  = $_SESSION["CUENTA1"];
	$nu=1;
	for($i=1; $i<=$cont; $i++){
	   if($_SESSION["DATOS1"][$i]["activa"]==1){
	   	$nu++;
	   }
	}
*/

echo'<script>';
       // echo" document.getElementById('codigo_directivos').value           = '".mascara($nu,2)."'; ";
       echo" document.getElementById('codigo_directivos').value           = '".mascara(($cont+1),2)."'; ";
       echo" document.getElementById('direccion_administrativa').value           = ''; ";
       echo" document.getElementById('nombres_directivo').value        = ''; ";
       echo" document.getElementById('correo_directivos').value     	  = ''; ";
       echo" document.getElementById('telefonos_directivos').value     	  = ''; ";
echo'</script>';

$this->set("accion1", $_SESSION["DATOS1"]);

}//fin function


function editar1($id=null){
$this->layout = "ajax";
	$cont  = $_SESSION["CUENTA1"];
	for($i=1; $i<=$cont; $i++){
		if($_SESSION["DATOS1"][$i]["activa"]==1 and $_SESSION["DATOS1"][$i]["id"]==$id){
        	$codigo_directivos     = $_SESSION["DATOS1"][$i]["codigo_directivos"];
        	$direccion_administrativa = $_SESSION["DATOS1"][$i]["direccion_administrativa"];
        	$nombres_directivo     = $_SESSION["DATOS1"][$i]["nombres_directivo"];
        	$correo_directivos  = $_SESSION["DATOS1"][$i]["correo_directivos"];
        	$telefonos_directivos  = $_SESSION["DATOS1"][$i]["telefonos_directivos"];
        	$ids  				   = $_SESSION["DATOS1"][$i]["id"];
		}
	}

$this->set('codigo_directivos',$codigo_directivos);
$this->set('direccion_administrativa',$direccion_administrativa);
$this->set('nombres_directivo',$nombres_directivo);
$this->set('correo_directivos',$correo_directivos);
$this->set('telefonos_directivos',$telefonos_directivos);
$this->set('i1',$id);
$this->set('id',$ids);
$this->set('Message_existe', 'PUEDE EDITAR LOS DATOS DE <BR>'.$codigo_directivos.' -- '.$direccion_administrativa);
}//fin function


function guardar_editar1($i=null){
	$this->layout='ajax';
	$codigo_directivos = $this->data['cfpd08_informacion']["codigo_directivos_".$i];
    $direccion_administrativa = $this->data['cfpd08_informacion']["direccion_administrativa_".$i];
    $nombres_directivo = $this->data['cfpd08_informacion']["nombres_directivo_".$i];
	$correo_directivos = $this->data['cfpd08_informacion']["correo_directivos_".$i];
	$telefonos_directivos = $this->data['cfpd08_informacion']["telefonos_directivos_".$i];

	if($codigo_directivos != null && $direccion_administrativa != null && $nombres_directivo != null && $correo_directivos != null && $telefonos_directivos != null){
	    $_SESSION["DATOS1"][$i]["codigo_directivos"] = $codigo_directivos;
		$_SESSION["DATOS1"][$i]["direccion_administrativa"] = $direccion_administrativa;
		$_SESSION["DATOS1"][$i]["nombres_directivo"] = $nombres_directivo;
		$_SESSION["DATOS1"][$i]["correo_directivos"] = $correo_directivos;
		$_SESSION["DATOS1"][$i]["telefonos_directivos"] = $telefonos_directivos;
		$this->set('Message_existe', 'Datos Actualizados...');
	}else{
		$codigo_directivos = $_SESSION["DATOS1"][$i]["codigo_directivos"];
		$direccion_administrativa = $_SESSION["DATOS1"][$i]["direccion_administrativa"];
		$nombres_directivo = $_SESSION["DATOS1"][$i]["nombres_directivo"];
		$correo_directivos = $_SESSION["DATOS1"][$i]["correo_directivos"];
		$telefonos_directivos = $_SESSION["DATOS1"][$i]["telefonos_directivos"];
		$this->set('errorMessage', 'Debe ingresar todos los datos...');
	}

	$ids = $_SESSION["DATOS1"][$i]["id"];

	$this->set('codigo_directivos',$codigo_directivos);
	$this->set('direccion_administrativa',$direccion_administrativa);
	$this->set('nombres_directivo',$nombres_directivo);
	$this->set('correo_directivos',$correo_directivos);
	$this->set('telefonos_directivos',$telefonos_directivos);
	$this->set('i1',$i);
	$this->set('id',$ids);

}


function cancelar1($id=null){
$this->layout = "ajax";
	$cont  = $_SESSION["CUENTA1"];
	for($i=1; $i<=$cont; $i++){
		if($_SESSION["DATOS1"][$i]["activa"]==1 and $_SESSION["DATOS1"][$i]["id"]==$id){
			$codigo_directivos = $_SESSION["DATOS1"][$i]["codigo_directivos"];
			$direccion_administrativa = $_SESSION["DATOS1"][$i]["direccion_administrativa"];
			$nombres_directivo = $_SESSION["DATOS1"][$i]["nombres_directivo"];
			$correo_directivos = $_SESSION["DATOS1"][$i]["correo_directivos"];
			$telefonos_directivos = $_SESSION["DATOS1"][$i]["telefonos_directivos"];
        	$ids = $_SESSION["DATOS1"][$i]["id"];
		}
	}

	$this->set('codigo_directivos',$codigo_directivos);
	$this->set('direccion_administrativa',$direccion_administrativa);
	$this->set('nombres_directivo',$nombres_directivo);
	$this->set('correo_directivos',$correo_directivos);
	$this->set('telefonos_directivos',$telefonos_directivos);
	$this->set('i1',$id);
	$this->set('id',$ids);
}//fin function


function eliminar1($id=null){
$this->layout = "ajax";
$_SESSION["DATOS1"][$id]["activa"] = 0;
$this->set("Message_existe", "EL REGISTRO FUE ELIMINADO");
$cont  = $_SESSION["CUENTA1"];
$marca = 0;

 for($i=1; $i<=$cont; $i++){
    if($_SESSION["DATOS1"][$i]["activa"]==1){
       $marca++;
   }//fin if
 }//fin for

	/* $cont  = $_SESSION["CUENTA1"];
	$nu=1;
	for($i=1; $i<=$cont; $i++){
	   if($_SESSION["DATOS1"][$i]["activa"]==1){
	   	$nu++;
	   }
	} */

echo'<script>';
       echo" document.getElementById('cuenta_grilla1').value = '".$marca."'; ";
       // echo" document.getElementById('codigo_directivos').value           = '".mascara($nu,2)."'; ";
echo'</script>';

$this->set("accion1", $_SESSION["DATOS1"]);
}



function agregar_grilla2(){
	$this->layout = "ajax";
   	$codigo_concejales = $this->data['cfpd08_informacion']['codigo_cpp'];
   	$nombres_concejales = $this->data['cfpd08_informacion']['nombres_cpp'];
   	$correo_concejales = $this->data['cfpd08_informacion']['correo_cpp'];
   	$telf_concejales = $this->data['cfpd08_informacion']['telefonos_cpp'];

			if(!isset($_SESSION["DATOS2"])){
	              $_SESSION["CUENTA2"] = 1;
	              $cont = $_SESSION["CUENTA2"];
	              $_SESSION["DATOS2"][$cont]["codigo_concejales"]    =  $codigo_concejales;
	              $_SESSION["DATOS2"][$cont]["nombres_concejales"]   =  $nombres_concejales;
	              $_SESSION["DATOS2"][$cont]["correo_concejales"]    =  $correo_concejales;
	              $_SESSION["DATOS2"][$cont]["telf_concejales"]      =  $telf_concejales;
	              $_SESSION["DATOS2"][$cont]["activa"]           	 = 1;
	              $_SESSION["DATOS2"][$cont]["id"]               	 = $cont;
	              $this->set("Message_existe", "EL REGISTRO FUE AGREGADO");
			}else{
                  $cont = $_SESSION["CUENTA2"];
                  $marca = 0;
		          for($i=1; $i<=$cont; $i++){
		             if($codigo_concejales==$_SESSION["DATOS2"][$i]["codigo_concejales"]  &&  $_SESSION["DATOS2"][$i]["activa"]==1){
                         $marca=1;
		             }//fin if
		          }//fin for
	              if($marca==1){
                    $this->set("errorMessage", "EL REGISTRO YA EXISTE");
	              }else{
                  $cont = $_SESSION["CUENTA2"];  $cont++; $_SESSION["CUENTA2"] = $cont;
                  $_SESSION["DATOS2"][$cont]["codigo_concejales"]  	= $codigo_concejales;
				  $_SESSION["DATOS2"][$cont]["nombres_concejales"]  = $nombres_concejales;
	              $_SESSION["DATOS2"][$cont]["correo_concejales"]   =  $correo_concejales;
	              $_SESSION["DATOS2"][$cont]["telf_concejales"]     =  $telf_concejales;
				  $_SESSION["DATOS2"][$cont]["activa"]              = 1;
				  $_SESSION["DATOS2"][$cont]["id"]               	= $cont;
                  $this->set("Message_existe", "EL REGISTRO FUE AGREGADO");
	                 }//fin else
			}//fin else

	/* $cont  = $_SESSION["CUENTA2"];
	$nu=1;
	for($i=1; $i<=$cont; $i++){
	   if($_SESSION["DATOS2"][$i]["activa"]==1){
	   	$nu++;
	   }
	} */

echo'<script>';
       echo" document.getElementById('codigo_cpp').value            = '".mascara(($cont+1),2)."'; ";
       echo" document.getElementById('nombres_cpp').value           = ''; ";
       echo" document.getElementById('correo_cpp').value           = ''; ";
       echo" document.getElementById('telefonos_cpp').value           = ''; ";
echo'</script>';

$this->set("accion2", $_SESSION["DATOS2"]);

}//fin function


function editar2($id=null){
$this->layout = "ajax";
	$cont = $_SESSION["CUENTA2"];
	for($i=1; $i<=$cont; $i++){
		if($_SESSION["DATOS2"][$i]["activa"]==1 and $_SESSION["DATOS2"][$i]["id"]==$id){
        	$codigo_concejales  = $_SESSION["DATOS2"][$i]["codigo_concejales"];
        	$nombres_concejales = $_SESSION["DATOS2"][$i]["nombres_concejales"];
			$correo_concejales  = $_SESSION["DATOS2"][$i]["correo_concejales"];
			$telf_concejales    = $_SESSION["DATOS2"][$i]["telf_concejales"];
        	$ids  			    = $_SESSION["DATOS2"][$i]["id"];
		}
	}

$this->set('codigo_concejales',$codigo_concejales);
$this->set('nombres_concejales',$nombres_concejales);
$this->set('correo_concejales',$correo_concejales);
$this->set('telf_concejales',$telf_concejales);
$this->set('i2',$id);
$this->set('id',$ids);
$this->set('Message_existe', 'PUEDE EDITAR LOS DATOS DE <BR>'.$codigo_concejales.' -- '.$nombres_concejales);
}//fin function


function guardar_editar2($i=null){
	$this->layout='ajax';
	$codigo_concejales  = $this->data['cfpd08_informacion']["codigo_cpp_".$i];
    $nombres_concejales = $this->data['cfpd08_informacion']["nombres_cpp_".$i];
    $correo_concejales  = $this->data['cfpd08_informacion']["correo_cpp_".$i];
    $telf_concejales    = $this->data['cfpd08_informacion']["telefonos_cpp_".$i];

	if($codigo_concejales != null && $nombres_concejales != null && $correo_concejales != null && $telf_concejales != null){
		$_SESSION["DATOS2"][$i]["codigo_concejales"]  = $codigo_concejales;
		$_SESSION["DATOS2"][$i]["nombres_concejales"] = $nombres_concejales;
		$_SESSION["DATOS2"][$i]["correo_concejales"]  = $correo_concejales;
		$_SESSION["DATOS2"][$i]["telf_concejales"]    = $telf_concejales;
		$this->set('Message_existe', 'Datos Actualizados...');
	}else{
		$codigo_concejales = $_SESSION["DATOS2"][$i]["codigo_concejales"];
		$nombres_concejales = $_SESSION["DATOS2"][$i]["nombres_concejales"];
		$correo_concejales = $_SESSION["DATOS2"][$i]["correo_concejales"];
		$telf_concejales = $_SESSION["DATOS2"][$i]["telf_concejales"];
		$this->set('errorMessage', 'Debe ingresar todos los datos...');
	}

	$ids = $_SESSION["DATOS2"][$i]["id"];
	$this->set('codigo_concejales',$codigo_concejales);
	$this->set('nombres_concejales',$nombres_concejales);
	$this->set('correo_concejales',$correo_concejales);
	$this->set('telf_concejales',$telf_concejales);
	$this->set('i2',$i);
	$this->set('id',$ids);
}

function cancelar2($id=null){
$this->layout = "ajax";
	$cont  = $_SESSION["CUENTA2"];
	for($i=1; $i<=$cont; $i++){
		if($_SESSION["DATOS2"][$i]["activa"]==1 and $_SESSION["DATOS2"][$i]["id"]==$id){
        	$codigo_concejales  = $_SESSION["DATOS2"][$i]["codigo_concejales"];
        	$nombres_concejales = $_SESSION["DATOS2"][$i]["nombres_concejales"];
			$correo_concejales  = $_SESSION["DATOS2"][$i]["correo_concejales"];
			$telf_concejales    = $_SESSION["DATOS2"][$i]["telf_concejales"];
        	$ids  				= $_SESSION["DATOS2"][$i]["id"];
		}
	}

$this->set('codigo_concejales',$codigo_concejales);
$this->set('nombres_concejales',$nombres_concejales);
$this->set('correo_concejales',$correo_concejales);
$this->set('telf_concejales',$telf_concejales);
$this->set('i2',$id);
$this->set('id',$ids);
}//fin function

function eliminar2($id=null){
$this->layout = "ajax";
$_SESSION["DATOS2"][$id]["activa"] = 0;
$this->set("Message_existe", "EL REGISTRO FUE ELIMINADO");
$cont  = $_SESSION["CUENTA2"];
$marca = 0;

 for($i=1; $i<=$cont; $i++){
    if($_SESSION["DATOS2"][$i]["activa"]==1){
       $marca++;
   }//fin if
 }//fin for

	/* $cont  = $_SESSION["CUENTA2"];
	$nu=1;
	for($i=1; $i<=$cont; $i++){
	   if($_SESSION["DATOS2"][$i]["activa"]==1){
	   	$nu++;
	   }
	} */

echo'<script>';
       echo" document.getElementById('cuenta_grilla2').value = '".$marca."'; ";
       // echo" document.getElementById('codigo_concejales').value            = '".mascara($nu,2)."'; ";
echo'</script>';

$this->set("accion2", $_SESSION["DATOS2"]);

}//fin function





function guardar(){
	$this->layout = "ajax";
	$cod_presi 					= $this->Session->read('SScodpresi');
	$cod_entidad 				= $this->Session->read('SScodentidad');
	$cod_tipo_inst 				= $this->Session->read('SScodtipoinst');
	$cod_inst 					= $this->Session->read('SScodinst');
	$cod_dep 					= $this->Session->read('SScoddep');
	$ano_formulacion = $this->data['cfpd08_informacion']['presupuesto'];

	$verxx = $this->cfpd08_ident_inst->findCount($this->SQLCA().' and ejercicio_fiscal='.$ano_formulacion);
	if($verxx == 0){

  	$base_legal = $this->data['cfpd08_informacion']['base_legal'];
  	$domicilio_legal = $this->data['cfpd08_informacion']['domicilio_legal'];
  	$telefonos_gob = $this->data['cfpd08_informacion']['telefonos'];
  	$pagina_web_gob = $this->data['cfpd08_informacion']['direccion_internet'];
  	$fax_gob = $this->data['cfpd08_informacion']['fax'];
  	$codigo_postal_gob = $this->data['cfpd08_informacion']['codigo_postal'];
  	$nombre_gobernador = $this->data['cfpd08_informacion']['nombre_alc_gob'];

  	$nombre_contralor = $this->data['cfpd08_informacion']['nombres_contralor'];
  	$domicilio_legal_contra = $this->data['cfpd08_informacion']['domicilio_contralor'];
  	$telefonos_contra = $this->data['cfpd08_informacion']['telefonos_contraloria'];
  	$pagina_web_contra = $this->data['cfpd08_informacion']['pagina_web_contraloria'];
  	$fax_contra = $this->data['cfpd08_informacion']['fax_contraloria'];

  	$nombre_presi_conce = $this->data['cfpd08_informacion']['nombres_presidente_consejo'];
  	$nombre_secre_conce = $this->data['cfpd08_informacion']['nombres_secretario_consejo'];
  	$domicilio_legal_conce = $this->data['cfpd08_informacion']['domicilio_consejo'];
  	$telefonos_conce = $this->data['cfpd08_informacion']['telefonos_consejo'];
  	$pagina_web_conce = $this->data['cfpd08_informacion']['pagina_web_consejo'];
  	$fax_conce = $this->data['cfpd08_informacion']['fax_consejo'];

	 	$SQL_INSERT = "BEGIN; INSERT INTO cfpd08_ident_inst (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, ejercicio_fiscal, base_legal_gob, domicilio_legal_gob, telefonos_gob, pagina_web_gob, fax_gob, codigo_postal_gob, nombre_gobernador, nombre_contralor, domicilio_legal_contra, telefonos_contra, pagina_web_contra, fax_contra, nombre_presi_conce, nombre_secre_conce, domicilio_legal_conce, telefonos_conce, pagina_web_conce, fax_conce)";
	 	$SQL_INSERT .=" VALUES ($cod_presi,$cod_entidad,$cod_tipo_inst,$cod_inst,$cod_dep, $ano_formulacion, '".$base_legal."', '".$domicilio_legal."', '".$telefonos_gob."', '".$pagina_web_gob."', '".$fax_gob."', '".$codigo_postal_gob."', '".$nombre_gobernador."', '".$nombre_contralor."', '".$domicilio_legal_contra."', '".$telefonos_contra."', '".$pagina_web_contra."', '".$fax_contra."', '".$nombre_presi_conce."', '".$nombre_secre_conce."', '".$domicilio_legal_conce."', '".$telefonos_conce."', '".$pagina_web_conce."', '".$fax_conce."')";
	 	$sw = $this->cfpd08_ident_inst->execute($SQL_INSERT);
		if($sw>1){

            /**
             * FOR TABLE :: cfpd08_ident_dir_inst
             */

        if(isset($_SESSION["DATOS1"]) && $_SESSION["DATOS1"]!=null){ // ** DATOS DEL PERSONAL DIRECTIVO DE LA ALCALDIA O GOB Y ORGANOS AUXILIARES **
		 	$cont = $_SESSION["CUENTA1"];
			 for($i=1; $i<=$cont; $i++){
			    if($_SESSION["DATOS1"][$i]["activa"]==1){
                    $codigo_directivos = $_SESSION["DATOS1"][$i]["codigo_directivos"];
        			$direccion_administrativa = $_SESSION["DATOS1"][$i]["direccion_administrativa"];
        			$nombres_directivo = $_SESSION["DATOS1"][$i]["nombres_directivo"];
        			$correo_directivos = $_SESSION["DATOS1"][$i]["correo_directivos"];
        			$telefonos_directivos = $_SESSION["DATOS1"][$i]["telefonos_directivos"];
				    $DATOS_A[]="($cod_presi, $cod_entidad, $cod_tipo_inst, $cod_inst, $cod_dep, $ano_formulacion, $codigo_directivos,'".$direccion_administrativa."','".$nombres_directivo."','".$correo_directivos."','".$telefonos_directivos."')";
			    }//fin if
			 }//fin for
             $VALUES_A=implode(',', $DATOS_A);
			 $sw1=$this->cfpd08_ident_dir_inst->execute("INSERT INTO cfpd08_ident_dir_inst (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, ejercicio_fiscal, cod_adm, direccion_adm, nombres_apellidos, correo_electronico, telefonos) VALUES ".$VALUES_A.";");
		} //fin isset session DATOS1


            /**
             * FOR TABLE :: cfpd08_ident_clp
             */

        if(isset($_SESSION["DATOS2"]) && $_SESSION["DATOS2"]!=null){ // ** CONSEJO LOCAL DE PLANIFICACION Y POLITICAS PUBLICA: **
		 	$cont = $_SESSION["CUENTA2"];
			 for($i=1; $i<=$cont; $i++){
			    if($_SESSION["DATOS2"][$i]["activa"]==1){
	              $codigo_concejales = $_SESSION["DATOS2"][$i]["codigo_concejales"];
	              $nombres_concejales = $_SESSION["DATOS2"][$i]["nombres_concejales"];
	              $correo_concejales = $_SESSION["DATOS2"][$i]["correo_concejales"];
	              $telf_concejales = $_SESSION["DATOS2"][$i]["telf_concejales"];
				  $DATOS_B[]="($cod_presi, $cod_entidad, $cod_tipo_inst, $cod_inst, $cod_dep, $ano_formulacion, $codigo_concejales,'".$nombres_concejales."','".$correo_concejales."','".$telf_concejales."')";
			    }//fin if
			 }//fin for
             $VALUES_B=implode(',', $DATOS_B);
			 $sw2=$this->cfpd08_ident_clp->execute("INSERT INTO cfpd08_ident_clp (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, ejercicio_fiscal, cod_miembro, nombres_apellidos, correo_electronico, telefonos) VALUES ".$VALUES_B.";");
		} //fin isset session DATOS2

			if((isset($sw1) && $sw1>1) && (isset($sw2) && $sw2>1)){
				$this->cfpd08_ident_inst->execute("COMMIT;");
				$this->set('Message_existe', 'LOS DATOS FUERON GUARDADOS EXITOSAMENTE.');
				$this->consulta2($ano_formulacion);
				$this->render("consulta2");
			}else{
				$this->cfpd08_ident_inst->execute("ROLLBACK;");
				$this->set('errorMessage', 'LOS DATOS NO FUERON GUARDADOS.');
				$this->index('1');
				$this->render("index");
			}
		}else{
			$this->cfpd08_ident_inst->execute("ROLLBACK;");
    		$this->set('errorMessage', 'LOS DATOS NO FUERON GUARDADOS.');
			$this->index('1');
			$this->render("index");
		}

	}else{
		$this->set('errorMessage', 'ESTE EJERCICIO YA SE ENCUENTRA REGISTRADO...');
		$this->consulta2($ano_formulacion);
		$this->render("consulta2");
	}
}


function consulta($pagina=null){
 		$this->layout = "ajax";
		$this->set('institucion', $this->verifica_SS(3));
         if($pagina!=null){
          	 $pagina=$pagina;
          	 $this->set('pagina',$pagina);
          	 $Tfilas=$this->cfpd08_ident_inst->findCount($this->SQLCA());
          	 if($Tfilas==0){
          	 	$this->set('errorMessage', 'NO SE ENCONTRARON DATOS');
          	 	$this->index();
          		$this->render("index");
          	 }
          	 if($Tfilas!=0){
          	 $this->set('pag_cant',$pagina.'/'.$Tfilas);
          	 $datos  = $this->cfpd08_ident_inst->findAll($this->SQLCA(),null,'ejercicio_fiscal ASC',1,$pagina,null);
          	 $ano    = $datos[0]['cfpd08_ident_inst']['ejercicio_fiscal'];
			 $datos2 = $this->cfpd08_ident_dir_inst->findAll($this->SQLCA().' and ejercicio_fiscal='.$ano,null,'cod_adm ASC',null,null,null);
			 $datos3 = $this->cfpd08_ident_clp->findAll($this->SQLCA().' and ejercicio_fiscal='.$ano,null,'cod_miembro ASC',null,null,null);
          	 $this->set('datos',$datos);
          	 $this->set('datos2',$datos2);
          	 $this->set('datos3',$datos3);
          	 $this->set('siguiente',$pagina+1);
          	 $this->set('anterior',$pagina-1);
             $this->bt_nav($Tfilas,$pagina);
             }
 }else{
 	$pagina=1;$this->set('pagina',$pagina);
          	 $Tfilas=$this->cfpd08_ident_inst->findCount($this->SQLCA());
          	 if($Tfilas==0){
          	 	$this->set('errorMessage', 'NO SE ENCONTRARON DATOS');
          	 	$this->index();
          		$this->render("index");
          	 }
          	 if($Tfilas!=0){
          	 $this->set('pag_cant',$pagina.'/'.$Tfilas);
          	 $datos  = $this->cfpd08_ident_inst->findAll($this->SQLCA(),null,'ejercicio_fiscal ASC',1,$pagina,null);
          	 $ano    = $datos[0]['cfpd08_ident_inst']['ejercicio_fiscal'];
			 $datos2 = $this->cfpd08_ident_dir_inst->findAll($this->SQLCA().' and ejercicio_fiscal='.$ano,null,'cod_adm ASC',null,null,null);
			 $datos3 = $this->cfpd08_ident_clp->findAll($this->SQLCA().' and ejercicio_fiscal='.$ano,null,'cod_miembro ASC',null,null,null);
          	 $this->set('datos',$datos);
          	 $this->set('datos2',$datos2);
          	 $this->set('datos3',$datos3);
          	 $this->set('siguiente',$pagina+1);
          	 $this->set('anterior',$pagina-1);
             $this->bt_nav($Tfilas,$pagina);
			 }
         }
}//fin function consulta


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


function consulta2($ano){
	$this->layout='ajax';
	$this->set('institucion', $this->verifica_SS(3));
	$datos  =$this->cfpd08_ident_inst->findAll($this->SQLCA().' and ejercicio_fiscal='.$ano);
	$datos2 =$this->cfpd08_ident_dir_inst->findAll($this->SQLCA().' and ejercicio_fiscal='.$ano,null,'cod_adm ASC',null,null,null);
	$datos3 =$this->cfpd08_ident_clp->findAll($this->SQLCA().' and ejercicio_fiscal='.$ano,null,'cod_miembro ASC',null,null,null);
    $this->set('datos',$datos);
    $this->set('datos2',$datos2);
    $this->set('datos3',$datos3);
}


function modificar($ano=null,$pagina=null){
	$this->layout='ajax';
	$this->set('institucion', $this->verifica_SS(3));
    $datos  =$this->cfpd08_ident_inst->findAll($this->SQLCA().' and ejercicio_fiscal='.$ano);
	$datos2 =$this->cfpd08_ident_dir_inst->findAll($this->SQLCA().' and ejercicio_fiscal='.$ano,null,'cod_adm ASC',null,null,null);
	$datos3 =$this->cfpd08_ident_clp->findAll($this->SQLCA().' and ejercicio_fiscal='.$ano,null,'cod_miembro ASC',null,null,null);
    $d2=$this->cfpd08_ident_dir_inst->findAll($this->SQLCA().' and ejercicio_fiscal='.$ano,'cod_adm','cod_adm DESC');
    $d3=$this->cfpd08_ident_clp->findAll($this->SQLCA().' and ejercicio_fiscal='.$ano,'cod_miembro','cod_miembro DESC');
	if($d2==null){
     	$n2=1;
    }else{
     	$n2=$d2[0]["cfpd08_ident_dir_inst"]["cod_adm"]+1;
    }
    if($d3==null){
     	$n3=1;
    }else{
     	$n3=$d3[0]["cfpd08_ident_clp"]["cod_miembro"]+1;
    }
	$this->set('n2',$n2);
	$this->set('n3',$n3);
    $this->set('datos',$datos);
    $this->set('datos2',$datos2);
    $this->set('datos3',$datos3);
    $this->set('pagina',$pagina);
}


function agregar_grilla1m($ano=null){
	$this->layout = "ajax";
	$cod_presi 					= $this->Session->read('SScodpresi');
	$cod_entidad 				= $this->Session->read('SScodentidad');
	$cod_tipo_inst 				= $this->Session->read('SScodtipoinst');
	$cod_inst 					= $this->Session->read('SScodinst');
	$cod_dep 					= $this->Session->read('SScoddep');
   	$codigo_directivos = $this->data['cfpd08_informacion']['codigo_directivos'];
   	$direccion_administrativa = $this->data['cfpd08_informacion']['direccion_administrativa'];
   	$nombres_directivo = $this->data['cfpd08_informacion']['nombres_directivo'];
   	$correo_directivos = $this->data['cfpd08_informacion']['correo_directivos'];
   	$telefonos_directivos = $this->data['cfpd08_informacion']['telefonos_directivos'];

    $cont = $this->cfpd08_ident_dir_inst->findCount($this->SQLCA().' and ejercicio_fiscal='.$ano.' and cod_adm='.$codigo_directivos);
	if($cont==0){
		$sql = "INSERT INTO cfpd08_ident_dir_inst (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, ejercicio_fiscal, cod_adm, direccion_adm, nombres_apellidos, correo_electronico, telefonos)";
		$sql .= " VALUES ($cod_presi, $cod_entidad, $cod_tipo_inst, $cod_inst, $cod_dep, $ano, $codigo_directivos,'".$direccion_administrativa."','".$nombres_directivo."','".$correo_directivos."','".$telefonos_directivos."');";
		$sw1m = $this->cfpd08_ident_dir_inst->execute($sql);
		if($sw1m > 1){
			$this->set('Message_existe', 'LOS DATOS FUERON GUARDADOS AL REGISTRO.');
		}else{
			$this->set('errorMessage', 'LOS DATOS NO FUERON GUARDADOS AL REGISTRO.');
		}
	}else{
		$this->set("errorMessage", "EL REGISTRO YA EXISTE");
	}
	$datos2 =$this->cfpd08_ident_dir_inst->findAll($this->SQLCA().' and ejercicio_fiscal='.$ano,null,'cod_adm ASC',null,null,null);
	$d2=$this->cfpd08_ident_dir_inst->findAll($this->SQLCA().' and ejercicio_fiscal='.$ano,'cod_adm','cod_adm DESC');
	if($d2==null){
     	$n2=1;
    }else{
     	$n2=$d2[0]["cfpd08_ident_dir_inst"]["cod_adm"]+1;
    }
	$this->set('accion1',$datos2);

	echo'<script>';
       echo" document.getElementById('codigo_directivos').value         = '".mascara($n2,2)."'; ";
       echo" document.getElementById('direccion_administrativa').value  = ''; ";
       echo" document.getElementById('nombres_directivo').value         = ''; ";
       echo" document.getElementById('correo_directivos').value     	= ''; ";
       echo" document.getElementById('telefonos_directivos').value      = ''; ";
	echo'</script>';
}


function guardar_modificar($ano=null,$pagina=null){
	$this->layout='ajax';
	$ano_formulacion = $this->data['cfpd08_informacion']['presupuesto'];
  	$base_legal = $this->data['cfpd08_informacion']['base_legal'];
  	$domicilio_legal = $this->data['cfpd08_informacion']['domicilio_legal'];
  	$telefonos_gob = $this->data['cfpd08_informacion']['telefonos'];
  	$pagina_web_gob = $this->data['cfpd08_informacion']['direccion_internet'];
  	$fax_gob = $this->data['cfpd08_informacion']['fax'];
  	$codigo_postal_gob = $this->data['cfpd08_informacion']['codigo_postal'];
  	$nombre_gobernador = $this->data['cfpd08_informacion']['nombre_alc_gob'];

  	$nombre_contralor = $this->data['cfpd08_informacion']['nombres_contralor'];
  	$domicilio_legal_contra = $this->data['cfpd08_informacion']['domicilio_contralor'];
  	$telefonos_contra = $this->data['cfpd08_informacion']['telefonos_contraloria'];
  	$pagina_web_contra = $this->data['cfpd08_informacion']['pagina_web_contraloria'];
  	$fax_contra = $this->data['cfpd08_informacion']['fax_contraloria'];

  	$nombre_presi_conce = $this->data['cfpd08_informacion']['nombres_presidente_consejo'];
  	$nombre_secre_conce = $this->data['cfpd08_informacion']['nombres_secretario_consejo'];
  	$domicilio_legal_conce = $this->data['cfpd08_informacion']['domicilio_consejo'];
  	$telefonos_conce = $this->data['cfpd08_informacion']['telefonos_consejo'];
  	$pagina_web_conce = $this->data['cfpd08_informacion']['pagina_web_consejo'];
  	$fax_conce = $this->data['cfpd08_informacion']['fax_consejo'];

	$SQL_UPD = "UPDATE cfpd08_ident_inst SET base_legal_gob='$base_legal', domicilio_legal_gob='$domicilio_legal', telefonos_gob='$telefonos_gob', pagina_web_gob='$pagina_web_gob', fax_gob='$fax_gob', codigo_postal_gob='$codigo_postal_gob', nombre_gobernador='$nombre_gobernador', nombre_contralor='$nombre_contralor', domicilio_legal_contra='$domicilio_legal_contra', telefonos_contra='$telefonos_contra', pagina_web_contra='$pagina_web_contra', fax_contra='$fax_contra', nombre_presi_conce='$nombre_presi_conce', nombre_secre_conce='$nombre_secre_conce', domicilio_legal_conce='$domicilio_legal_conce', telefonos_conce='$telefonos_conce', pagina_web_conce='$pagina_web_conce', fax_conce='$fax_conce' WHERE ".$this->SQLCA()." and ejercicio_fiscal=$ano_formulacion;";
	$sw = $this->cfpd08_ident_inst->execute($SQL_UPD);
	if($sw>1){
		$this->set('Message_existe', 'LOS DATOS FUERON ACTUALIZADOS.');
	}else{
		$this->set('errorMessage', 'LOS DATOS NO FUERON ACTUALIZADOS.');
	}

    $this->consulta($pagina);
    $this->render("consulta");
}


function editar1m($ano=null,$cod=null,$i=null){
	$this->layout = "ajax";
	$d = $this->cfpd08_ident_dir_inst->findAll($this->SQLCA().' and ejercicio_fiscal='.$ano.' and cod_adm='.$cod);
	$codigo_directivos        = $d[0]['cfpd08_ident_dir_inst']['cod_adm'];
	$direccion_administrativa = $d[0]['cfpd08_ident_dir_inst']['direccion_adm'];
	$nombres_directivo    = $d[0]['cfpd08_ident_dir_inst']['nombres_apellidos'];
	$correo_directivos    = $d[0]['cfpd08_ident_dir_inst']['correo_electronico'];
	$telefonos_directivos = $d[0]['cfpd08_ident_dir_inst']['telefonos'];

	$this->set('codigo_directivos',$codigo_directivos);
	$this->set('direccion_administrativa',$direccion_administrativa);
	$this->set('nombres_directivo',$nombres_directivo);
	$this->set('correo_directivos',$correo_directivos);
	$this->set('telefonos_directivos',$telefonos_directivos);
	$this->set('i1',$i);
	$this->set('id',$i);
	$this->set('ano',$ano);
	$this->set('cod',$cod);

	$this->set('Message_existe', 'PUEDE EDITAR LOS DATOS DE <BR>'.mascara($codigo_directivos,2).' -- '.$direccion_administrativa);
}//fin function

function cancelar1m($ano=null,$cod=null,$i=null){
	$this->layout = "ajax";
	$d = $this->cfpd08_ident_dir_inst->findAll($this->SQLCA().' and ejercicio_fiscal='.$ano.' and cod_adm='.$cod);
	$codigo_directivos        = $d[0]['cfpd08_ident_dir_inst']['cod_adm'];
	$direccion_administrativa = $d[0]['cfpd08_ident_dir_inst']['direccion_adm'];
	$nombres_directivo    = $d[0]['cfpd08_ident_dir_inst']['nombres_apellidos'];
	$correo_directivos    = $d[0]['cfpd08_ident_dir_inst']['correo_electronico'];
	$telefonos_directivos = $d[0]['cfpd08_ident_dir_inst']['telefonos'];

	$this->set('codigo_directivos',$codigo_directivos);
	$this->set('direccion_administrativa',$direccion_administrativa);
	$this->set('nombres_directivo',$nombres_directivo);
	$this->set('correo_directivos',$correo_directivos);
	$this->set('telefonos_directivos',$telefonos_directivos);
	$this->set('i1',$i);
	$this->set('id',$i);
	$this->set('ano',$ano);
	$this->set('cod',$cod);
}//fin function


function guardar_editar1m($ano=null,$cod=null,$i=null){
	$this->layout='ajax';
	$codigo_directivos = $this->data['cfpd08_informacion']["codigo_directivos_".$i];
    $direccion_administrativa = $this->data['cfpd08_informacion']["direccion_administrativa_".$i];
    $nombres_directivo = $this->data['cfpd08_informacion']["nombres_directivo_".$i];
	$correo_directivos = $this->data['cfpd08_informacion']["correo_directivos_".$i];
	$telefonos_directivos = $this->data['cfpd08_informacion']["telefonos_directivos_".$i];

	if($codigo_directivos != null && $direccion_administrativa != null && $nombres_directivo != null && $correo_directivos != null && $telefonos_directivos != null){
		$upd="update cfpd08_ident_dir_inst set direccion_adm='".$direccion_administrativa."', nombres_apellidos='".$nombres_directivo."', correo_electronico='".$correo_directivos."', telefonos='".$telefonos_directivos."' WHERE ".$this->SQLCA()." and ejercicio_fiscal=$ano and cod_adm=$cod;";
		$swup = $this->cfpd08_ident_dir_inst->execute($upd);
		if($swup > 1){
			$this->set('Message_existe', 'Los Datos han sido actualizados del registro...');
		}else{
			$this->set('errorMessage', 'No se pudo actualizar los datos del registro...');
		}
	}else{
		$d = $this->cfpd08_ident_dir_inst->findAll($this->SQLCA().' and ejercicio_fiscal='.$ano.' and cod_adm='.$cod);
		$codigo_directivos        = $d[0]['cfpd08_ident_dir_inst']['cod_adm'];
		$direccion_administrativa = $d[0]['cfpd08_ident_dir_inst']['direccion_adm'];
		$nombres_directivo    = $d[0]['cfpd08_ident_dir_inst']['nombres_apellidos'];
		$correo_directivos    = $d[0]['cfpd08_ident_dir_inst']['correo_electronico'];
		$telefonos_directivos = $d[0]['cfpd08_ident_dir_inst']['telefonos'];
		$this->set('errorMessage', 'Debe ingresar todos los datos...');
	}

	$this->set('codigo_directivos',$codigo_directivos);
	$this->set('direccion_administrativa',$direccion_administrativa);
	$this->set('nombres_directivo',$nombres_directivo);
	$this->set('correo_directivos',$correo_directivos);
	$this->set('telefonos_directivos',$telefonos_directivos);
	$this->set('i1',$i);
	$this->set('id',$i);
	$this->set('ano',$ano);
	$this->set('cod',$cod);
}


function eliminar1m($ano=null,$cod=null){
	$this->layout='ajax';
	$upd = "DELETE FROM cfpd08_ident_dir_inst WHERE ".$this->SQLCA()." and ejercicio_fiscal=$ano and cod_adm=$cod;";
	$sw1d = $this->cfpd08_ident_dir_inst->execute($upd);
	$datos2 = $this->cfpd08_ident_dir_inst->findAll($this->SQLCA().' and ejercicio_fiscal='.$ano,null,'cod_adm ASC',null,null,null);
	$this->set('accion1',$datos2);
	if($sw1d > 1){
		$this->set('Message_existe', 'LOS DATOS FUERON ELIMINADOS DEL REGISTRO.');
	}else{
		$this->set('errorMessage', 'LOS DATOS NO FUERON ELIMINADOS DEL REGISTRO.');
	}
}



function agregar_grilla2m($ano=null){
	$this->layout='ajax';
	$cod_presi 					= $this->Session->read('SScodpresi');
	$cod_entidad 				= $this->Session->read('SScodentidad');
	$cod_tipo_inst 				= $this->Session->read('SScodtipoinst');
	$cod_inst 					= $this->Session->read('SScodinst');
	$cod_dep 					= $this->Session->read('SScoddep');
   	$codigo_concejales = $this->data['cfpd08_informacion']['codigo_cpp'];
   	$nombres_concejales = $this->data['cfpd08_informacion']['nombres_cpp'];
   	$correo_concejales = $this->data['cfpd08_informacion']['correo_cpp'];
   	$telf_concejales = $this->data['cfpd08_informacion']['telefonos_cpp'];
    $cont = $this->cfpd08_ident_clp->findCount($this->SQLCA().' and ejercicio_fiscal='.$ano.' and cod_miembro='.$codigo_concejales);
	if($cont==0){
		$sql2 = "INSERT INTO cfpd08_ident_clp (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, ejercicio_fiscal, cod_miembro, nombres_apellidos, correo_electronico, telefonos)";
		$sql2 .= " VALUES ($cod_presi, $cod_entidad, $cod_tipo_inst, $cod_inst, $cod_dep, $ano, $codigo_concejales,'".$nombres_concejales."','".$correo_concejales."','".$telf_concejales."');";
		$sw2m = $this->cfpd08_ident_clp->execute($sql2);
		if($sw2m > 1){
			$this->set('Message_existe', 'LOS DATOS FUERON GUARDADOS AL REGISTRO.');
		}else{
			$this->set('errorMessage', 'LOS DATOS NO FUERON GUARDADOS AL REGISTRO.');
		}
	}else{
		$this->set("errorMessage", "EL REGISTRO YA EXISTE");
	}
	$datos3 =$this->cfpd08_ident_clp->findAll($this->SQLCA().' and ejercicio_fiscal='.$ano,null,'cod_miembro ASC',null,null,null);
	$d2=$this->cfpd08_ident_clp->findAll($this->SQLCA().' and ejercicio_fiscal='.$ano,'cod_miembro','cod_miembro DESC');
	if($d2==null){
     	$n2=1;
    }else{
     	$n2=$d2[0]["cfpd08_ident_clp"]["cod_miembro"]+1;
    }
	$this->set('accion2',$datos3);

	echo'<script>';
       echo" document.getElementById('codigo_cpp').value            = '".mascara($n2,2)."'; ";
       echo" document.getElementById('nombres_cpp').value           = ''; ";
       echo" document.getElementById('correo_cpp').value           = ''; ";
       echo" document.getElementById('telefonos_cpp').value           = ''; ";
	echo'</script>';
}


function editar2m($ano=null,$cod=null,$i=null){
	$this->layout = "ajax";
	$d = $this->cfpd08_ident_clp->findAll($this->SQLCA().' and ejercicio_fiscal='.$ano.' and cod_miembro='.$cod);
   	$codigo_concejales = $d[0]['cfpd08_ident_clp']['cod_miembro'];
   	$nombres_concejales = $d[0]['cfpd08_ident_clp']['nombres_apellidos'];
   	$correo_concejales = $d[0]['cfpd08_ident_clp']['correo_electronico'];
   	$telf_concejales = $d[0]['cfpd08_ident_clp']['telefonos'];

	$this->set('codigo_concejales',$codigo_concejales);
	$this->set('nombres_concejales',$nombres_concejales);
	$this->set('correo_concejales',$correo_concejales);
	$this->set('telf_concejales',$telf_concejales);
	$this->set('i2',$i);
	$this->set('id',$i);
	$this->set('ano',$ano);
	$this->set('cod',$cod);
	$this->set('Message_existe', 'PUEDE EDITAR LOS DATOS DE <BR>'.mascara($codigo_concejales,2).' -- '.$nombres_concejales);
}//fin function

function cancelar2m($ano=null,$cod=null,$i=null){
	$this->layout = "ajax";
	$d = $this->cfpd08_ident_clp->findAll($this->SQLCA().' and ejercicio_fiscal='.$ano.' and cod_miembro='.$cod);
   	$codigo_concejales = $d[0]['cfpd08_ident_clp']['cod_miembro'];
   	$nombres_concejales = $d[0]['cfpd08_ident_clp']['nombres_apellidos'];
   	$correo_concejales = $d[0]['cfpd08_ident_clp']['correo_electronico'];
   	$telf_concejales = $d[0]['cfpd08_ident_clp']['telefonos'];

	$this->set('codigo_concejales',$codigo_concejales);
	$this->set('nombres_concejales',$nombres_concejales);
	$this->set('correo_concejales',$correo_concejales);
	$this->set('telf_concejales',$telf_concejales);
	$this->set('i2',$i);
	$this->set('id',$i);
	$this->set('ano',$ano);
	$this->set('cod',$cod);
}//fin function


function guardar_editar2m($ano=null,$cod=null,$i=null){
	$this->layout='ajax';
	$codigo_concejales  = $this->data['cfpd08_informacion']["codigo_cpp_".$i];
    $nombres_concejales = $this->data['cfpd08_informacion']["nombres_cpp_".$i];
    $correo_concejales  = $this->data['cfpd08_informacion']["correo_cpp_".$i];
    $telf_concejales    = $this->data['cfpd08_informacion']["telefonos_cpp_".$i];

if($codigo_concejales != null && $nombres_concejales != null && $correo_concejales != null && $telf_concejales != null){

	$upd = "update cfpd08_ident_clp set nombres_apellidos='".$nombres_concejales."', correo_electronico='".$correo_concejales."', telefonos='".$telf_concejales."' WHERE ".$this->SQLCA()." and ejercicio_fiscal=$ano and cod_miembro=$cod;";
	$sw2m = $this->cfpd08_ident_clp->execute($upd);
	if($sw2m > 1){
		$this->set('Message_existe', 'Los Datos han sido actualizados del registro...');
	}else{
		$this->set('errorMessage', 'No se pudo actualizar los datos del registro...');
	}

}else{
		$d = $this->cfpd08_ident_clp->findAll($this->SQLCA().' and ejercicio_fiscal='.$ano.' and cod_miembro='.$cod);
		$codigo_concejales        = $d[0]['cfpd08_ident_clp']['cod_miembro'];
		$nombres_concejales = $d[0]['cfpd08_ident_clp']['nombres_apellidos'];
		$correo_concejales    = $d[0]['cfpd08_ident_clp']['correo_electronico'];
		$telf_concejales = $d[0]['cfpd08_ident_clp']['telefonos'];
		$this->set('errorMessage', 'Debe ingresar todos los datos...');
}

	$this->set('codigo_concejales',$codigo_concejales);
	$this->set('nombres_concejales',$nombres_concejales);
	$this->set('correo_concejales',$correo_concejales);
	$this->set('telf_concejales',$telf_concejales);
	$this->set('ano',$ano);
	$this->set('cod',$cod);
	$this->set('i2',$i);
	$this->set('id',$i);
}


function eliminar2m($ano=null,$cod=null){
	$this->layout='ajax';
	$delclp = "DELETE FROM cfpd08_ident_clp WHERE ".$this->SQLCA()." and ejercicio_fiscal=$ano and cod_miembro=$cod;";
	$sw2d = $this->cfpd08_ident_clp->execute($delclp);
	$datos3 =$this->cfpd08_ident_clp->findAll($this->SQLCA().' and ejercicio_fiscal='.$ano,null,'cod_miembro ASC',null,null,null);
	$this->set('accion2',$datos3);
	if($sw2d > 1){
		$this->set('Message_existe', 'LOS DATOS FUERON ELIMINADOS DEL REGISTRO.');
	}else{
		$this->set('errorMessage', 'LOS DATOS NO FUERON ELIMINADOS DEL REGISTRO.');
	}
}



function eliminar($ano=null,$pagina=null){
 	$this->layout = "ajax";
 	$ca=$this->SQLCA();
 	$cond = $this->SQLCA().' and ejercicio_fiscal='.$ano;
 	$this->cfpd08_ident_inst->execute("DELETE FROM cfpd08_ident_inst WHERE ".$cond);
 	$y=$this->cfpd08_ident_inst->findCount($this->SQLCA());
 	if($pagina>$y){
 		$pagina=$pagina-1;
 	}
 	if($y!=0){
	  	$this->set('Message_existe', 'Registro Eliminado con exito.');
      	$this->consulta($pagina);
    	$this->render("consulta");
	}else if($y==0){
		$this->set('Message_existe', 'Registro Eliminado con exito.');
		$this->index();
      	$this->render("index");
	}//fin if
}



function buscar($var1=null){
	$this->layout="ajax";
	$this->set("opcion",$var1);
	$this->Session->delete('pista');
	echo "<script>document.getElementById('select_obra_cod_obra').focus();</script>";
}//fin function

function buscar_por_pista($var1=null, $var2=null, $var3=null){
$this->layout="ajax";

    if($var3==null){$var2 = strtoupper($var2);
					$this->Session->write('pista', $var2);
					$Tfilas=$this->cfpd08_ident_inst->findCount("(ejercicio_fiscal::text LIKE '%$var2%') and ".$this->SQLCA());
					        if($Tfilas!=0){
					        	$pagina=1;
					        	$Tfilas=(int)ceil($Tfilas/50);
					        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
								$this->set('total_paginas',$Tfilas);
								$this->set('pagina_actual',$pagina);
								$this->set('ultimo',$Tfilas);
					     	    $datos_filas=$this->cfpd08_ident_inst->findAll("(ejercicio_fiscal::text LIKE '%$var2%') and ".$this->SQLCA(),null,"ejercicio_fiscal ASC",50,1,null);
						        $this->set("datosFILAS",$datos_filas);
						        $this->set('siguiente',$pagina+1);
								$this->set('anterior',$pagina-1);
								$this->bt_nav($Tfilas,$pagina);
					          }else{
					        	$this->set("datosFILAS",'');
					          }
            }else{
						$var22 = $this->Session->read('pista');
						$var22 = strtoupper($var22);
						$Tfilas=$this->cfpd08_ident_inst->findCount("(ejercicio_fiscal::text LIKE '%$var2%') and ".$this->SQLCA());
						        if($Tfilas!=0){
						        	$pagina=$var3;
						        	$Tfilas=(int)ceil($Tfilas/50);
						        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
									$this->set('total_paginas',$Tfilas);
									$this->set('pagina_actual',$pagina);
									$this->set('ultimo',$Tfilas);
						     	    $datos_filas=$this->cfpd08_ident_inst->findAll("(ejercicio_fiscal::text LIKE '%$var2%') and ".$this->SQLCA(),null,"ejercicio_fiscal ASC",50,$pagina,null);
							        $this->set("datosFILAS",$datos_filas);
							        $this->set('siguiente',$pagina+1);
									$this->set('anterior',$pagina-1);
									$this->bt_nav($Tfilas,$pagina);
						          }else{
						        	$this->set("datosFILAS",'');
						          }
                 }//fin else
$this->set("opcion",$var1);
}//fin function


function salir_index(){
	$this->layout ="ajax";
	if(isset($_SESSION["DATOS1"])){
 		$this->Session->delete("DATOS1");
	}

	if(isset($_SESSION["DATOS2"])){
		$this->Session->delete("DATOS2");
	}

	if(isset($_SESSION["CUENTA1"])){
		$this->Session->delete("CUENTA1");
	}

	if(isset($_SESSION["CUENTA2"])){
		$this->Session->delete("CUENTA2");
	}
}




function index_politica_pf($otro = null){
 	$this->layout ="ajax";

	$this->data=null;

	$this->set('institucion', $this->verifica_SS(3));

 	if($otro=='1'){
	 	$this->data=null;
	}else if($otro==null){
		$a = $this->cfpd01_formulacion->findAll($this->SQLCX());
		if($a != null){
			$ano_formulacion = $a[0]['cfpd01_formulacion']['ano_formular'];
		}else{
			$ano_formulacion='';
		}
	if($ano_formulacion != null){
	$verxx = $this->cfpd11_pol_pres_finan->findCount($this->SQLCA().' and ano='.$ano_formulacion);
	if($verxx==1){
		$this->consulta2_ppf($ano_formulacion);
		$this->render("consulta2_ppf");
	}
	}
	}
}//fin function


function guardar_ppf($ano=null){
	$this->layout='ajax';
	$cod_presi 					= $this->Session->read('SScodpresi');
	$cod_entidad 				= $this->Session->read('SScodentidad');
	$cod_tipo_inst 				= $this->Session->read('SScodtipoinst');
	$cod_inst 					= $this->Session->read('SScodinst');
	$cod_dep 					= $this->Session->read('SScoddep');
	$ano = $this->ano_ejecucion();
   	$financiamiento = $this->data['cfpd11_pol_pres_finan']['financiamiento'];
   	$gastos = $this->data['cfpd11_pol_pres_finan']['gastos'];
   	$servicios = $this->data['cfpd11_pol_pres_finan']['servicios'];
    $cont_ppf = $this->cfpd11_pol_pres_finan->findCount($this->SQLCA().' and ano='.$ano);
	if($cont_ppf==0){
		$sq = "INSERT INTO cfpd11_pol_pres_finan (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, ano, financiamiento, gastos, servicios)";
		$sq .= " VALUES ($cod_presi, $cod_entidad, $cod_tipo_inst, $cod_inst, $cod_dep, $ano, '".$financiamiento."','".$gastos."','".$servicios."');";
		$s = $this->cfpd11_pol_pres_finan->execute($sq);
		if($s > 1){
			$this->set('Message_existe', 'LOS DATOS FUERON GUARDADOS.');
			$this->consulta2_ppf($ano);
			$this->render("consulta2_ppf");
		}else{
			$this->set('errorMessage', 'LOS DATOS NO FUERON GUARDADOS - Intente Nuevamente...');
			$this->index_politica_pf('1');
			$this->render("index_politica_pf");
		}
	}else{
		$this->set("errorMessage", "LA POL&Iacute;TICA PRESUPUESTARIA Y DE FINANCIAMIENTO YA ESTA REGISTRADA PARA EL A&Ntilde;O.");
		$this->consulta2_ppf($ano);
		$this->render("consulta2_ppf");
	}
	echo "<script>document.getElementById('guardar_ppf').disabled = false;</script>";
} // FIN FUNCTION::guardar_ppf


function consulta_ppf($pagina=null){
 		$this->layout = "ajax";
		$this->set('institucion', $this->verifica_SS(3));
         if($pagina!=null){
          	 $pagina=$pagina;
          	 $this->set('pagina',$pagina);
          	 $Tfilas=$this->cfpd11_pol_pres_finan->findCount($this->SQLCA());
          	 if($Tfilas==0){
          	 	$this->set('errorMessage', 'NO SE ENCONTRARON DATOS');
          	 	$this->index_politica_pf();
          		$this->render("index_politica_pf");
          	 }
          	 if($Tfilas!=0){
          	 $this->set('pag_cant',$pagina.'/'.$Tfilas);
          	 $datos  = $this->cfpd11_pol_pres_finan->findAll($this->SQLCA(),null,'ano ASC',1,$pagina,null);
          	 $this->set('datos',$datos);
          	 $this->set('siguiente',$pagina+1);
          	 $this->set('anterior',$pagina-1);
             $this->bt_nav($Tfilas,$pagina);
             }
 }else{
 	$pagina=1;$this->set('pagina',$pagina);
          	 $Tfilas=$this->cfpd11_pol_pres_finan->findCount($this->SQLCA());
          	 if($Tfilas==0){
          	 	$this->set('errorMessage', 'NO SE ENCONTRARON DATOS');
          	 	$this->index_politica_pf();
          		$this->render("index_politica_pf");
          	 }
          	 if($Tfilas!=0){
          	 $this->set('pag_cant',$pagina.'/'.$Tfilas);
          	 $datos  = $this->cfpd11_pol_pres_finan->findAll($this->SQLCA(),null,'ano ASC',1,$pagina,null);
          	 $this->set('datos',$datos);
          	 $this->set('siguiente',$pagina+1);
          	 $this->set('anterior',$pagina-1);
             $this->bt_nav($Tfilas,$pagina);
			 }
         }
}//fin function consulta


function modificar_ppf($ano=null,$pagina=null){
	$this->layout='ajax';
	$this->set('institucion', $this->verifica_SS(3));
	$datos = $this->cfpd11_pol_pres_finan->findAll($this->SQLCA().' and ano='.$ano,null,null,1,null,null);
	$this->set('datos',$datos);
	$this->set('pagina',$pagina);
}

function consulta2_ppf($ano=null){
	$this->layout='ajax';
	$this->set('institucion', $this->verifica_SS(3));
	$datos = $this->cfpd11_pol_pres_finan->findAll($this->SQLCA().' and ano='.$ano,null,null,1,null,null);
	$this->set('datos',$datos);
}

function guardar_modificar_ppf($ano=null,$pagina=null){
	$this->layout='ajax';
	$financiamiento = $this->data['cfpd11_pol_pres_finan']["financiamiento"];
    $gastos = $this->data['cfpd11_pol_pres_finan']["gastos"];
    $servicios = $this->data['cfpd11_pol_pres_finan']["servicios"];

if($ano != null){
	$updppf = "update cfpd11_pol_pres_finan set financiamiento='".$financiamiento."', gastos='".$gastos."', servicios='".$servicios."' WHERE ".$this->SQLCA()." and ano=$ano;";
	$sw2mppf = $this->cfpd11_pol_pres_finan->execute($updppf);
	if($sw2mppf > 1){
		$this->set('Message_existe', 'Los Datos han sido actualizados exitosamente.');
	}else{
		$this->set('errorMessage', 'No se pudo actualizar los datos del registro...');
	}

}else{
	$this->set('errorMessage', 'Debe ingresar todos los datos...');
}

    $this->consulta_ppf($pagina);
    $this->render("consulta_ppf");
}


function eliminar_ppf($ano=null,$pagina=null){
 	$this->layout = "ajax";
 	$ca=$this->SQLCA();
 	$this->cfpd11_pol_pres_finan->execute("DELETE FROM cfpd11_pol_pres_finan WHERE ".$this->SQLCA().' and ano='.$ano);
 	$y=$this->cfpd11_pol_pres_finan->findCount($this->SQLCA());
 	if($pagina>$y){
 		$pagina=$pagina-1;
 	}
 	if($y!=0){
	  	$this->set('Message_existe', 'Registro Eliminado con exito.');
      	$this->consulta_ppf($pagina);
    	$this->render("consulta_ppf");
	}else if($y==0){
		$this->set('Message_existe', 'Registro Eliminado con exito.');
		$this->index_politica_pf();
      	$this->render("index_politica_pf");
	}//fin if
}



function buscar_ppf($var1=null){
	$this->layout="ajax";
	$this->set("opcion",$var1);
	$this->Session->delete('pista');
	echo "<script>document.getElementById('select_obra_cod_obra').focus();</script>";
}//fin function

function buscar_por_pista_ppf($var1=null, $var2=null, $var3=null){
$this->layout="ajax";

    if($var3==null){$var2 = strtoupper($var2);
					$this->Session->write('pista', $var2);
					$Tfilas=$this->cfpd11_pol_pres_finan->findCount("(ano::text LIKE '%$var2%') and ".$this->SQLCA());
					        if($Tfilas!=0){
					        	$pagina=1;
					        	$Tfilas=(int)ceil($Tfilas/50);
					        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
								$this->set('total_paginas',$Tfilas);
								$this->set('pagina_actual',$pagina);
								$this->set('ultimo',$Tfilas);
					     	    $datos_filas=$this->cfpd11_pol_pres_finan->findAll("(ano::text LIKE '%$var2%') and ".$this->SQLCA(),null,"ano ASC",50,1,null);
						        $this->set("datosFILAS",$datos_filas);
						        $this->set('siguiente',$pagina+1);
								$this->set('anterior',$pagina-1);
								$this->bt_nav($Tfilas,$pagina);
					          }else{
					        	$this->set("datosFILAS",'');
					          }
            }else{
						$var22 = $this->Session->read('pista');
						$var22 = strtoupper($var22);
						$Tfilas=$this->cfpd11_pol_pres_finan->findCount("(ano::text LIKE '%$var2%') and ".$this->SQLCA());
						        if($Tfilas!=0){
						        	$pagina=$var3;
						        	$Tfilas=(int)ceil($Tfilas/50);
						        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
									$this->set('total_paginas',$Tfilas);
									$this->set('pagina_actual',$pagina);
									$this->set('ultimo',$Tfilas);
						     	    $datos_filas=$this->cfpd11_pol_pres_finan->findAll("(ano::text LIKE '%$var2%') and ".$this->SQLCA(),null,"ano ASC",50,$pagina,null);
							        $this->set("datosFILAS",$datos_filas);
							        $this->set('siguiente',$pagina+1);
									$this->set('anterior',$pagina-1);
									$this->bt_nav($Tfilas,$pagina);
						          }else{
						        	$this->set("datosFILAS",'');
						          }
                 }//fin else
$this->set("opcion",$var1);
}//fin function


} // FIN CLASS::Cfpp08InformacionController

?>
