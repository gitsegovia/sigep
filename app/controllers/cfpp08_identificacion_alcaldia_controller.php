<?php

class Cfpp08IdentificacionAlcaldiaController extends AppController{

    var $name    = "cfpp08_identificacion_alcaldia";
    var $uses    = array('cfpd08_identificacion_alcaldia','cfpd01_formulacion','cfpd08_identificacion_alcaldia','cfpd08_identificacion_alcaldia_concejales','cfpd08_identificacion_alcaldia_directivos');
	var $helpers = array('Html','Ajax','Javascript', 'Sisap', 'Fpdf');





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
 }
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



 function SQLCA_S($ano=null){//sql para busqueda de codigos de arranque con y sin año
				 $sql_re = "cod_presi=".$this->verifica_SS(1)."  and    ";
				 $sql_re .= "cod_entidad=".$this->verifica_SS(2)."  and  ";
				 $sql_re .= "cod_tipo_inst=".$this->verifica_SS(3)."  and ";
				 $sql_re .= "cod_inst=".$this->verifica_SS(4)."   ";
				 return $sql_re;
}//fin funcion SQLCA



 function index($otro=null){
 	$this->layout ="ajax";
	$this->data=null;
	$a = $this->cfpd01_formulacion->findAll($this->SQLCX());
	if($a != null){
		$ano_formulacion = $a[0]['cfpd01_formulacion']['ano_formular'];
	}else{
		$ano_formulacion='';
	}
	for($minCount = 2007; $minCount < 2030; $minCount++) {
    $anos[sprintf('%02d', $minCount)] = sprintf('%02d', $minCount);
    $this->set('anos',$anos);
    $this->set('ano_formulacion',$ano_formulacion);
	}

 	if($otro=='1'){
 	$this->data=null;
 	$this->Session->delete("DATOS1");
	$this->Session->delete("DATOS2");
//	for($minCount = 2007; $minCount < 2030; $minCount++) {
 //   $anos[sprintf('%02d', $minCount)] = sprintf('%02d', $minCount);
 //   $this->set('anos',$anos);
  //  $this->set('ano_formulacion',$ano_formulacion);
//	}
	}else if($otro==null){
		$a = $this->cfpd01_formulacion->findAll($this->SQLCX());
		if($a != null){
			$ano_formulacion = $a[0]['cfpd01_formulacion']['ano_formular'];
		}else{
			$ano_formulacion='';
		}
	if($ano_formulacion != null){
	$verxx = $this->cfpd08_identificacion_alcaldia->findCount($this->SQLCA().' and ejercicio_fiscal='.$ano_formulacion);
	if($verxx=='1'){
	$this->consulta2($ano_formulacion);
	$this->render("consulta2");
	}
	}
	}



}//fin function

function agregar_grilla1(){

	$this->layout = "ajax";
   	$codigo_directivos    = $this->data['cfpp08_identificacion_alcaldia']['codigo_directivos'];
   	$nombres_directivo    = $this->data['cfpp08_identificacion_alcaldia']['nombres_directivo'];
   	$telefonos_directivos = $this->data['cfpp08_identificacion_alcaldia']['telefonos_directivos'];
   	$direccion_directivos = $this->data['cfpp08_identificacion_alcaldia']['direccion_directivos'];

			if(!isset($_SESSION["DATOS1"])){
	              $_SESSION["CUENTA1"] = 1;
	              $cont = $_SESSION["CUENTA1"];
	              $_SESSION["DATOS1"][$cont]["codigo_directivos"]    = $codigo_directivos;
	              $_SESSION["DATOS1"][$cont]["nombres_directivo"]    = $nombres_directivo;
	              $_SESSION["DATOS1"][$cont]["telefonos_directivos"] = $telefonos_directivos;
	              $_SESSION["DATOS1"][$cont]["direccion_directivos"] = $direccion_directivos;
	              $_SESSION["DATOS1"][$cont]["activa"]           	 = 1;
	              $_SESSION["DATOS1"][$cont]["id"]               	 = $cont;
	              $this->set("Message_existe", "EL REGISTRO FUE AGREGADO");
			}else{
                  $cont  = $_SESSION["CUENTA1"];
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
                  $_SESSION["DATOS1"][$cont]["codigo_directivos"]    	= $codigo_directivos;
				  $_SESSION["DATOS1"][$cont]["nombres_directivo"]       = $nombres_directivo;
				  $_SESSION["DATOS1"][$cont]["telefonos_directivos"]    = $telefonos_directivos;
				  $_SESSION["DATOS1"][$cont]["direccion_directivos"] 	= $direccion_directivos;
				  $_SESSION["DATOS1"][$cont]["activa"]           		= 1;
				  $_SESSION["DATOS1"][$cont]["id"]               		= $cont;
                  $this->set("Message_existe", "EL REGISTRO FUE AGREGADO");
	                 }//fin else
			}//fin else

	$cont  = $_SESSION["CUENTA1"];
	$nu=1;
	for($i=1; $i<=$cont; $i++){
	   if($_SESSION["DATOS1"][$i]["activa"]==1){
	   	$nu++;
	   }
	}
echo'<script>';
       echo" document.getElementById('codigo_directivos').value           = '".mascara($nu,2)."'; ";
       echo" document.getElementById('nombres_directivo').value           = ''; ";
       echo" document.getElementById('telefonos_directivos').value        = ''; ";
       echo" document.getElementById('direccion_directivos').value     	  = ''; ";
echo'</script>';

$this->set("accion1", $_SESSION["DATOS1"]);

}//fin function

function editar1($id=null){
$this->layout = "ajax";
	$cont  = $_SESSION["CUENTA1"];
	for($i=1; $i<=$cont; $i++){
		if($_SESSION["DATOS1"][$i]["activa"]==1 and $_SESSION["DATOS1"][$i]["id"]==$id){
        	$codigo_directivos     = $_SESSION["DATOS1"][$i]["codigo_directivos"];
        	$nombres_directivo     = $_SESSION["DATOS1"][$i]["nombres_directivo"];
        	$telefonos_directivos  = $_SESSION["DATOS1"][$i]["telefonos_directivos"];
        	$direccion_directivos  = $_SESSION["DATOS1"][$i]["direccion_directivos"];
        	$ids  				   = $_SESSION["DATOS1"][$i]["id"];
		}
	}

$this->set('codigo_directivos',$codigo_directivos);
$this->set('nombres_directivo',$nombres_directivo);
$this->set('telefonos_directivos',$telefonos_directivos);
$this->set('direccion_directivos',$direccion_directivos);
$this->set('i1',$id);
$this->set('id',$ids);
$this->set('Message_existe', 'PUEDE EDITAR LOS DATOS');
}//fin function

function cancelar1($id=null){
$this->layout = "ajax";
	$cont  = $_SESSION["CUENTA1"];
	for($i=1; $i<=$cont; $i++){
		if($_SESSION["DATOS1"][$i]["activa"]==1 and $_SESSION["DATOS1"][$i]["id"]==$id){
        	$codigo_directivos     = $_SESSION["DATOS1"][$i]["codigo_directivos"];
        	$nombres_directivo     = $_SESSION["DATOS1"][$i]["nombres_directivo"];
        	$telefonos_directivos  = $_SESSION["DATOS1"][$i]["telefonos_directivos"];
        	$direccion_directivos  = $_SESSION["DATOS1"][$i]["direccion_directivos"];
        	$ids  				   = $_SESSION["DATOS1"][$i]["id"];
		}
	}

$this->set('codigo_directivos',$codigo_directivos);
$this->set('nombres_directivo',$nombres_directivo);
$this->set('telefonos_directivos',$telefonos_directivos);
$this->set('direccion_directivos',$direccion_directivos);
$this->set('i1',$id);
$this->set('id',$ids);
}//fin function

function guardar_editar1($i=null){
	$this->layout='ajax';
	$codigo_directivos     = $this->data['cfpp08_identificacion_alcaldia']["codigo_directivos_".$i];
    $nombres_directivo     = $this->data['cfpp08_identificacion_alcaldia']["nombres_directivo_".$i];
    $telefonos_directivos  = $this->data['cfpp08_identificacion_alcaldia']["telefonos_directivos_".$i];
	$direccion_directivos  = $this->data['cfpp08_identificacion_alcaldia']["direccion_directivos_".$i];
	$_SESSION["DATOS1"][$i]["codigo_directivos"]    = $codigo_directivos;
	$_SESSION["DATOS1"][$i]["nombres_directivo"]    = $nombres_directivo;
	$_SESSION["DATOS1"][$i]["telefonos_directivos"] = $telefonos_directivos;
	$_SESSION["DATOS1"][$i]["direccion_directivos"] = $direccion_directivos;
	$ids  				   = $_SESSION["DATOS1"][$i]["id"];
	$this->set('codigo_directivos',$codigo_directivos);
	$this->set('nombres_directivo',$nombres_directivo);
	$this->set('telefonos_directivos',$telefonos_directivos);
	$this->set('direccion_directivos',$direccion_directivos);
	$this->set('i1',$i);
	$this->set('id',$ids);

}

function eliminar1($id=null){
$this->layout = "ajax";
$_SESSION["DATOS1"][$id]["activa"] = 0;
$this->set("errorMessage", "EL REGISTRO FUE ELIMINADO");
$cont  = $_SESSION["CUENTA1"];
$marca = 0;

 for($i=1; $i<=$cont; $i++){
    if($_SESSION["DATOS1"][$i]["activa"]==1){
       $marca++;
   }//fin if
 }//fin for

	$cont  = $_SESSION["CUENTA1"];
	$nu=1;
	for($i=1; $i<=$cont; $i++){
	   if($_SESSION["DATOS1"][$i]["activa"]==1){
	   	$nu++;
	   }
	}
echo'<script>';
       echo" document.getElementById('cuenta_grilla1').value = '".$marca."'; ";
       echo" document.getElementById('codigo_directivos').value           = '".mascara($nu,2)."'; ";
echo'</script>';

$this->set("accion1", $_SESSION["DATOS1"]);


}//fin function

function agregar_grilla2(){

	$this->layout = "ajax";
   	$codigo_concejales     = $this->data['cfpp08_identificacion_alcaldia']['codigo_concejales'];
   	$nombres_concejales    = $this->data['cfpp08_identificacion_alcaldia']['nombres_concejales'];

			if(!isset($_SESSION["DATOS2"])){
	              $_SESSION["CUENTA2"] = 1;
	              $cont = $_SESSION["CUENTA2"];
	              $_SESSION["DATOS2"][$cont]["codigo_concejales"]    =  $codigo_concejales;
	              $_SESSION["DATOS2"][$cont]["nombres_concejales"]   =  $nombres_concejales;
	              $_SESSION["DATOS2"][$cont]["activa"]           	 = 1;
	              $_SESSION["DATOS2"][$cont]["id"]               	 = $cont;
	              $this->set("Message_existe", "EL REGISTRO FUE AGREGADO");
			}else{
                  $cont  = $_SESSION["CUENTA2"];
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
                  $_SESSION["DATOS2"][$cont]["codigo_concejales"]    	= $codigo_concejales;
				  $_SESSION["DATOS2"][$cont]["nombres_concejales"]      = $nombres_concejales;
				  $_SESSION["DATOS2"][$cont]["activa"]           		= 1;
				  $_SESSION["DATOS2"][$cont]["id"]               		= $cont;
                  $this->set("Message_existe", "EL REGISTRO FUE AGREGADO");
	                 }//fin else
			}//fin else

	$cont  = $_SESSION["CUENTA2"];
	$nu=1;
	for($i=1; $i<=$cont; $i++){
	   if($_SESSION["DATOS2"][$i]["activa"]==1){
	   	$nu++;
	   }
	}
echo'<script>';
       echo" document.getElementById('codigo_concejales').value            = '".mascara($nu,2)."'; ";
       echo" document.getElementById('nombres_concejales').value           = ''; ";
echo'</script>';

$this->set("accion2", $_SESSION["DATOS2"]);

}//fin function

function editar2($id=null){
$this->layout = "ajax";
	$cont  = $_SESSION["CUENTA2"];
	for($i=1; $i<=$cont; $i++){
		if($_SESSION["DATOS2"][$i]["activa"]==1 and $_SESSION["DATOS2"][$i]["id"]==$id){
        	$codigo_concejales      = $_SESSION["DATOS2"][$i]["codigo_concejales"];
        	$nombres_concejales     = $_SESSION["DATOS2"][$i]["nombres_concejales"];
        	$ids  				    = $_SESSION["DATOS2"][$i]["id"];
		}
	}

$this->set('codigo_concejales',$codigo_concejales);
$this->set('nombres_concejales',$nombres_concejales);
$this->set('i2',$id);
$this->set('id',$ids);
$this->set('Message_existe', 'PUEDE EDITAR LOS DATOS');
}//fin function


function cancelar2($id=null){
$this->layout = "ajax";
	$cont  = $_SESSION["CUENTA2"];
	for($i=1; $i<=$cont; $i++){
		if($_SESSION["DATOS2"][$i]["activa"]==1 and $_SESSION["DATOS2"][$i]["id"]==$id){
        	$codigo_concejales      = $_SESSION["DATOS2"][$i]["codigo_concejales"];
        	$nombres_concejales     = $_SESSION["DATOS2"][$i]["nombres_concejales"];
        	$ids  				    = $_SESSION["DATOS2"][$i]["id"];
		}
	}

$this->set('codigo_concejales',$codigo_concejales);
$this->set('nombres_concejales',$nombres_concejales);
$this->set('i2',$id);
$this->set('id',$ids);
}//fin function

function guardar_editar2($i=null){
	$this->layout='ajax';
	$codigo_concejales     = $this->data['cfpp08_identificacion_alcaldia']["codigo_concejales".$i];
    $nombres_concejales     = $this->data['cfpp08_identificacion_alcaldia']["nombres_concejales".$i];
	$_SESSION["DATOS2"][$i]["codigo_concejales"]     = $codigo_concejales;
	$_SESSION["DATOS2"][$i]["nombres_concejales"]    = $nombres_concejales;
	$ids  				   = $_SESSION["DATOS2"][$i]["id"];
	$this->set('codigo_concejales',$codigo_concejales);
	$this->set('nombres_concejales',$nombres_concejales);
	$this->set('i2',$i);
	$this->set('id',$ids);

}

function eliminar2($id=null){
$this->layout = "ajax";
$_SESSION["DATOS2"][$id]["activa"] = 0;
$this->set("errorMessage", "EL REGISTRO FUE ELIMINADO");
$cont  = $_SESSION["CUENTA2"];
$marca = 0;

 for($i=1; $i<=$cont; $i++){
    if($_SESSION["DATOS2"][$i]["activa"]==1){
       $marca++;
   }//fin if
 }//fin for
	$cont  = $_SESSION["CUENTA2"];
	$nu=1;
	for($i=1; $i<=$cont; $i++){
	   if($_SESSION["DATOS2"][$i]["activa"]==1){
	   	$nu++;
	   }
	}

echo'<script>';
       echo" document.getElementById('cuenta_grilla2').value = '".$marca."'; ";
       echo" document.getElementById('codigo_concejales').value            = '".mascara($nu,2)."'; ";
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
	$ano_formulacion = $this->data['cfpp08_identificacion_alcaldia']['presupuesto'];

	$verxx = $this->cfpd08_identificacion_alcaldia->findCount($this->SQLCA().' and ejercicio_fiscal='.$ano_formulacion);
	if($verxx == '0'){

  	$domicilio_legal = $this->data['cfpp08_identificacion_alcaldia']['domicilio_legal'];
  	$base_legal = $this->data['cfpp08_identificacion_alcaldia']['base_legal'];
  	$fecha_creacion = $this->data['cfpp08_identificacion_alcaldia']['fecha_creacion'];
  	$ciudad = $this->data['cfpp08_identificacion_alcaldia']['ciudad'];
  	$estado = $this->data['cfpp08_identificacion_alcaldia']['estado'];
  	$telefonos = $this->data['cfpp08_identificacion_alcaldia']['telefonos'];
  	$direccion_internet = $this->data['cfpp08_identificacion_alcaldia']['direccion_internet'];
  	if($direccion_internet==null){
  		$direccion_internet='0';
  	}
  	$fax = $this->data['cfpp08_identificacion_alcaldia']['fax'];
  	$rif = $this->data['cfpp08_identificacion_alcaldia']['rif'];
  	$codigo_postal = $this->data['cfpp08_identificacion_alcaldia']['codigo_postal'];
  	$alcalde = $this->data['cfpp08_identificacion_alcaldia']['alcalde'];

	 	$SQL_INSERT ="BEGIN; INSERT INTO cfpd08_identificacion_alcaldia (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep,  ejercicio_fiscal,
  		domicilio_legal, base_legal, fecha_creacion, ciudad, estado, telefonos, direccion_internet, fax, rif, codigo_postal, alcalde)";
	 	$SQL_INSERT .=" VALUES ($cod_presi,$cod_entidad,$cod_tipo_inst,$cod_inst,$cod_dep, $ano_formulacion, '".$domicilio_legal."', '".$base_legal."',
  		'".$fecha_creacion."', '".$ciudad."', '".$estado."', '".$telefonos."', '".$direccion_internet."', '".$fax."', '".$rif."', '".$codigo_postal."', '".$alcalde."')";
	 	$sw = $this->cfpd08_identificacion_alcaldia->execute($SQL_INSERT);
		if($sw>1){
	 	$cont  = $_SESSION["CUENTA1"];
			 for($i=1; $i<=$cont; $i++){
			    if($_SESSION["DATOS1"][$i]["activa"]==1){
                    $codigo_directivos     = $_SESSION["DATOS1"][$i]["codigo_directivos"];
        			$nombres_directivo     = $_SESSION["DATOS1"][$i]["nombres_directivo"];
        			$telefonos_directivos  = $_SESSION["DATOS1"][$i]["telefonos_directivos"];
        			$direccion_directivos  = $_SESSION["DATOS1"][$i]["direccion_directivos"];
					   $sql ="INSERT INTO cfpd08_identificacion_alcaldia_directivos (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep,
					   		 ejercicio_fiscal, cod_directivo, nombres_apellidos, telefonos, direccion_electronica)";
					   $sql.="VALUES ($cod_presi, $cod_entidad, $cod_tipo_inst, $cod_inst, $cod_dep, $ano_formulacion,
					   	$codigo_directivos,'".$nombres_directivo."','".$telefonos_directivos."','".$direccion_directivos."');";
					   $sw1 = $this->cfpd08_identificacion_alcaldia_directivos->execute($sql);
					   if($sw1>1){}else{break;}
			    }//fin if
			 }//fin for
		if($sw1>1){
			 $cont  = $_SESSION["CUENTA2"];
			 for($i=1; $i<=$cont; $i++){
			    if($_SESSION["DATOS2"][$i]["activa"]==1){
                    $codigo_concejales     = $_SESSION["DATOS2"][$i]["codigo_concejales"];
        			$nombres_concejales     = $_SESSION["DATOS2"][$i]["nombres_concejales"];
					   $sql2 ="INSERT INTO cfpd08_identificacion_alcaldia_concejales (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep,
					   		 ejercicio_fiscal, cod_concejal, nombres_apellidos)";
					   $sql2.="VALUES ($cod_presi, $cod_entidad, $cod_tipo_inst, $cod_inst, $cod_dep, $ano_formulacion,
					   	$codigo_concejales,'".$nombres_concejales."');";
					   $sw2 = $this->cfpd08_identificacion_alcaldia_concejales->execute($sql2);
					   if($sw2>1){}else{break;}
			    }//fin if
			 }//fin for

		}
		if($sw2>1){
			$this->cfpd08_identificacion_alcaldia_concejales->execute("COMMIT;");
			$this->set('Message_existe', 'LOS DATOS FUERON GUARDADOS');
		}else{
			$this->cfpd08_identificacion_alcaldia_concejales->execute("ROLLBACK;");
			$this->set('errorMessage', 'LOS DATOS NO FUERON GUARDADOS');
		}
		}else{
			$this->cfpd08_identificacion_alcaldia->execute("ROLLBACK;");
    		$this->set('errorMessage', 'LOS DATOS NO FUERON GUARDADOS');
		}
	$this->index();
	$this->render("index");
	}else if($verxx =='1'){
	$this->set('errorMessage', 'EJERCICIO YA SE ENCUENTRA REGISTRADO');
	$this->consulta2($ano_formulacion);
	$this->render("consulta2");
	}

}

function consulta($pagina=null){
 		$this->layout = "ajax";
 		//$a = $this->cfpd01_formulacion->findAll($this->SQLCX());
		//$ano_formulacion = $a[0]['cfpd01_formulacion']['ano_formular'];
         if($pagina!=null){
          	 $pagina=$pagina;$this->set('pagina',$pagina);
          	  $Tfilas=$this->cfpd08_identificacion_alcaldia->findCount($this->SQLCA());
          	 if($Tfilas==0){
          	 	$this->set('errorMessage', 'NO SE ENCONTRARON DATOS');
          	 	$this->index();
          		$this->render("index");
          	 }
          	 if($Tfilas!=0){
          	 $this->set('pag_cant',$pagina.'/'.$Tfilas);
          	 $datos  =$this->cfpd08_identificacion_alcaldia->findAll($this->SQLCA(),null,null,1,$pagina,null);
          	 $ano    = $datos[0]['cfpd08_identificacion_alcaldia']['ejercicio_fiscal'];
			 $datos2 =$this->cfpd08_identificacion_alcaldia_directivos->findAll($this->SQLCA().' and ejercicio_fiscal='.$ano,null,'cod_directivo ASC',null,null,null);
			 $datos3 =$this->cfpd08_identificacion_alcaldia_concejales->findAll($this->SQLCA().' and ejercicio_fiscal='.$ano,null,'cod_concejal ASC',null,null,null);
          	 $this->set('datos',$datos);
          	 $this->set('datos2',$datos2);
          	 $this->set('datos3',$datos3);
          	 $this->set('siguiente',$pagina+1);
          	 $this->set('anterior',$pagina-1);
             $this->bt_nav($Tfilas,$pagina);
             }
 }else{
 	$pagina=1;$this->set('pagina',$pagina);
          	 $Tfilas=$this->cfpd08_identificacion_alcaldia->findCount($this->SQLCA());
          	 if($Tfilas==0){
          	 	$this->set('errorMessage', 'NO SE ENCONTRARON DATOS');
          	 	$this->index();
          		$this->render("index");
          	 }
          	 if($Tfilas!=0){
          	 $this->set('pag_cant',$pagina.'/'.$Tfilas);
          	 $datos  =$this->cfpd08_identificacion_alcaldia->findAll($this->SQLCA(),null,null,1,$pagina,null);
          	 $ano    = $datos[0]['cfpd08_identificacion_alcaldia']['ejercicio_fiscal'];
			 $datos2 =$this->cfpd08_identificacion_alcaldia_directivos->findAll($this->SQLCA().' and ejercicio_fiscal='.$ano,null,'cod_directivo ASC',null,null,null);
			 $datos3 =$this->cfpd08_identificacion_alcaldia_concejales->findAll($this->SQLCA().' and ejercicio_fiscal='.$ano,null,'cod_concejal ASC',null,null,null);
          	 $this->set('datos',$datos);
          	 $this->set('datos2',$datos2);
          	 $this->set('datos3',$datos3);
          	 $this->set('siguiente',$pagina+1);
          	 $this->set('anterior',$pagina-1);
             $this->bt_nav($Tfilas,$pagina);
			 }
         }
}//fin function consultar2
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

function modificar($ano=null,$pagina=null){
	$this->layout='ajax';
    $datos  =$this->cfpd08_identificacion_alcaldia->findAll($this->SQLCA().' and ejercicio_fiscal='.$ano);
	$datos2 =$this->cfpd08_identificacion_alcaldia_directivos->findAll($this->SQLCA().' and ejercicio_fiscal='.$ano,null,'cod_directivo ASC',null,null,null);
	$datos3 =$this->cfpd08_identificacion_alcaldia_concejales->findAll($this->SQLCA().' and ejercicio_fiscal='.$ano,null,'cod_concejal ASC',null,null,null);
    $d2=$this->cfpd08_identificacion_alcaldia_directivos->findAll($this->SQLCA().' and ejercicio_fiscal='.$ano,null,'cod_directivo DESC');
    $d3=$this->cfpd08_identificacion_alcaldia_concejales->findAll($this->SQLCA().' and ejercicio_fiscal='.$ano,null,'cod_concejal DESC');
	if($d2==null){
     	$n2=1;
    }else{
     	$n2=$d2[0]["cfpd08_identificacion_alcaldia_directivos"]["cod_directivo"]+1;
    }
    if($d3==null){
     	$n3=1;
    }else{
     	$n3=$d3[0]["cfpd08_identificacion_alcaldia_concejales"]["cod_concejal"]+1;
    }
	$this->set('n2',$n2);
	$this->set('n3',$n3);
    $this->set('datos',$datos);
    $this->set('datos2',$datos2);
    $this->set('datos3',$datos3);
    $this->set('pagina',$pagina);

}

function agregar_grilla1m($ano=null){
	$this->layout='ajax';
	$cod_presi 					= $this->Session->read('SScodpresi');
	$cod_entidad 				= $this->Session->read('SScodentidad');
	$cod_tipo_inst 				= $this->Session->read('SScodtipoinst');
	$cod_inst 					= $this->Session->read('SScodinst');
	$cod_dep 					= $this->Session->read('SScoddep');
	$codigo_directivos     		= $this->data['cfpp08_identificacion_alcaldia']['codigo_directivos'];
    $nombres_directivo     		= $this->data['cfpp08_identificacion_alcaldia']['nombres_directivo'];
    $telefonos_directivos  		= $this->data['cfpp08_identificacion_alcaldia']['telefonos_directivos'];
    $direccion_directivos  		= $this->data['cfpp08_identificacion_alcaldia']['direccion_directivos'];
    $cont = $this->cfpd08_identificacion_alcaldia_directivos->findCount($this->SQLCA().' and ejercicio_fiscal='.$ano.' and cod_directivo='.$codigo_directivos);
	if($cont==0){
		$sql ="INSERT INTO cfpd08_identificacion_alcaldia_directivos (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep,
		ejercicio_fiscal, cod_directivo, nombres_apellidos, telefonos, direccion_electronica)";
		$sql.="VALUES ($cod_presi, $cod_entidad, $cod_tipo_inst, $cod_inst, $cod_dep, $ano,
		$codigo_directivos,'".$nombres_directivo."','".$telefonos_directivos."','".$direccion_directivos."');";
		$sw1 = $this->cfpd08_identificacion_alcaldia_directivos->execute($sql);
	}else{
		$this->set("errorMessage", "EL REGISTRO YA EXISTE");
	}
	$datos2 =$this->cfpd08_identificacion_alcaldia_directivos->findAll($this->SQLCA().' and ejercicio_fiscal='.$ano,null,'cod_directivo ASC',null,null,null);
	$d2=$this->cfpd08_identificacion_alcaldia_directivos->findAll($this->SQLCA().' and ejercicio_fiscal='.$ano,null,'cod_directivo DESC');
	if($d2==null){
     	$n2=1;
    }else{
     	$n2=$d2[0]["cfpd08_identificacion_alcaldia_directivos"]["cod_directivo"]+1;
    }
	$datos  =$this->cfpd08_identificacion_alcaldia->findAll($this->SQLCA().' and ejercicio_fiscal='.$ano);
	$this->set('datos2',$datos2);
	$this->set('datos',$datos);
	echo'<script>';
       echo" document.getElementById('codigo_directivos').value           = '".mascara($n2,2)."'; ";
       echo" document.getElementById('nombres_directivo').value           = ''; ";
       echo" document.getElementById('telefonos_directivos').value        = ''; ";
       echo" document.getElementById('direccion_directivos').value     	  = ''; ";
	echo'</script>';
}

function agregar_grilla2m($ano=null){
	$this->layout='ajax';
	$cod_presi 					= $this->Session->read('SScodpresi');
	$cod_entidad 				= $this->Session->read('SScodentidad');
	$cod_tipo_inst 				= $this->Session->read('SScodtipoinst');
	$cod_inst 					= $this->Session->read('SScodinst');
	$cod_dep 					= $this->Session->read('SScoddep');
	$codigo_concejales     		= $this->data['cfpp08_identificacion_alcaldia']['codigo_concejales'];
    $nombres_concejales    		= $this->data['cfpp08_identificacion_alcaldia']['nombres_concejales'];
    $cont = $this->cfpd08_identificacion_alcaldia_concejales->findCount($this->SQLCA().' and ejercicio_fiscal='.$ano.' and cod_concejal='.$codigo_concejales);
	if($cont==0){
		$sql ="INSERT INTO cfpd08_identificacion_alcaldia_concejales (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep,
		ejercicio_fiscal, cod_concejal, nombres_apellidos)";
		$sql.="VALUES ($cod_presi, $cod_entidad, $cod_tipo_inst, $cod_inst, $cod_dep, $ano,
		$codigo_concejales,'".$nombres_concejales."');";
		$sw1 = $this->cfpd08_identificacion_alcaldia_concejales->execute($sql);
	}else{
		$this->set("errorMessage", "EL REGISTRO YA EXISTE");
	}
	$datos3 =$this->cfpd08_identificacion_alcaldia_concejales->findAll($this->SQLCA().' and ejercicio_fiscal='.$ano,null,'cod_concejal ASC',null,null,null);
	$d2=$this->cfpd08_identificacion_alcaldia_concejales->findAll($this->SQLCA().' and ejercicio_fiscal='.$ano,null,'cod_concejal DESC');
	if($d2==null){
     	$n2=1;
    }else{
     	$n2=$d2[0]["cfpd08_identificacion_alcaldia_concejales"]["cod_concejal"]+1;
    }
    $datos  =$this->cfpd08_identificacion_alcaldia->findAll($this->SQLCA().' and ejercicio_fiscal='.$ano);
	$this->set('datos',$datos);
	$this->set('datos3',$datos3);
	echo'<script>';
       echo" document.getElementById('codigo_concejales').value           = '".mascara($n2,2)."'; ";
       echo" document.getElementById('nombres_concejales').value           = ''; ";
	echo'</script>';
}

function editar1m($ano=null,$cod=null,$i=null){
$this->layout = "ajax";
        	$d = $this->cfpd08_identificacion_alcaldia_directivos->findAll($this->SQLCA().' and ejercicio_fiscal='.$ano.' and cod_directivo='.$cod);
        	$codigo_directivos     = $d[0]['cfpd08_identificacion_alcaldia_directivos']['cod_directivo'];
        	$nombres_directivo     = $d[0]['cfpd08_identificacion_alcaldia_directivos']['nombres_apellidos'];
        	$telefonos_directivos  = $d[0]['cfpd08_identificacion_alcaldia_directivos']['telefonos'];
        	$direccion_directivos  = $d[0]['cfpd08_identificacion_alcaldia_directivos']['direccion_electronic'];

$this->set('codigo_directivos',$codigo_directivos);
$this->set('nombres_directivo',$nombres_directivo);
$this->set('telefonos_directivos',$telefonos_directivos);
$this->set('direccion_directivos',$direccion_directivos);
$this->set('i1',$i);
$this->set('ano',$ano);
$this->set('cod',$cod);
}//fin function

function cancelar1m($ano=null,$cod=null,$i=null){
$this->layout = "ajax";
        	$d = $this->cfpd08_identificacion_alcaldia_directivos->findAll($this->SQLCA().' and ejercicio_fiscal='.$ano.' and cod_directivo='.$cod);
        	$codigo_directivos     = $d[0]['cfpd08_identificacion_alcaldia_directivos']['cod_directivo'];
        	$nombres_directivo     = $d[0]['cfpd08_identificacion_alcaldia_directivos']['nombres_apellidos'];
        	$telefonos_directivos  = $d[0]['cfpd08_identificacion_alcaldia_directivos']['telefonos'];
        	$direccion_directivos  = $d[0]['cfpd08_identificacion_alcaldia_directivos']['direccion_electronic'];

$this->set('codigo_directivos',$codigo_directivos);
$this->set('nombres_directivo',$nombres_directivo);
$this->set('telefonos_directivos',$telefonos_directivos);
$this->set('direccion_directivos',$direccion_directivos);
$this->set('ano',$ano);
$this->set('cod',$cod);
$this->set('i1',$i);
}//fin function

function guardar_editar1m($ano=null,$cod=null,$i=null){
	$this->layout='ajax';
	$codigo_directivos     = $this->data['cfpp08_identificacion_alcaldia']["codigo_directivos_".$i];
    $nombres_directivo     = $this->data['cfpp08_identificacion_alcaldia']["nombres_directivo_".$i];
    $telefonos_directivos  = $this->data['cfpp08_identificacion_alcaldia']["telefonos_directivos_".$i];
	$direccion_directivos  = $this->data['cfpp08_identificacion_alcaldia']["direccion_directivos_".$i];
	$upd="update cfpd08_identificacion_alcaldia_directivos set nombres_apellidos='".$nombres_directivo."', telefonos='".$telefonos_directivos."',direccion_electronica='".$direccion_directivos."' WHERE ejercicio_fiscal=$ano and cod_directivo=$cod and ".$this->SQLCA();
	$sw1 = $this->cfpd08_identificacion_alcaldia_concejales->execute($upd);
	$this->set('Message_existe', 'LOS DATOS FUERON GUARDADOS');
	$this->set('codigo_directivos',$codigo_directivos);
	$this->set('nombres_directivo',$nombres_directivo);
	$this->set('telefonos_directivos',$telefonos_directivos);
	$this->set('direccion_directivos',$direccion_directivos);
	$this->set('ano',$ano);
	$this->set('cod',$cod);
	$this->set('i1',$i);

}

function eliminar1m($ano=null,$cod=null){
	$this->layout='ajax';
	$upd="DELETE FROM cfpd08_identificacion_alcaldia_directivos WHERE ejercicio_fiscal=$ano and cod_directivo=$cod and ".$this->SQLCA();
	$sw1 = $this->cfpd08_identificacion_alcaldia_concejales->execute($upd);
	$datos2 =$this->cfpd08_identificacion_alcaldia_directivos->findAll($this->SQLCA().' and ejercicio_fiscal='.$ano,null,'cod_directivo ASC',null,null,null);
	$datos  =$this->cfpd08_identificacion_alcaldia->findAll($this->SQLCA().' and ejercicio_fiscal='.$ano);
	$this->set('datos2',$datos2);
	$this->set('datos',$datos);
	$d2=$this->cfpd08_identificacion_alcaldia_directivos->findAll($this->SQLCA().' and ejercicio_fiscal='.$ano,null,'cod_directivo DESC');
	if($d2==null){
     	$n2=1;
    }else{
     	$n2=$d2[0]["cfpd08_identificacion_alcaldia_directivos"]["cod_directivo"]+1;
    }
   	echo'<script>';
       echo" document.getElementById('codigo_directivos').value           = '".mascara($n2,2)."'; ";
	echo'</script>';
	$this->set('Message_existe', 'LOS DATOS FUERON ELIMINADOS');
}




function editar2m($ano=null,$cod=null,$i=null){
$this->layout = "ajax";
        	$d = $this->cfpd08_identificacion_alcaldia_concejales->findAll($this->SQLCA().' and ejercicio_fiscal='.$ano.' and cod_concejal='.$cod);
        	$codigo_concejales     = $d[0]['cfpd08_identificacion_alcaldia_concejales']['cod_concejal'];
        	$nombres_concejales     = $d[0]['cfpd08_identificacion_alcaldia_concejales']['nombres_apellidos'];

$this->set('codigo_concejales',$codigo_concejales);
$this->set('nombres_concejales',$nombres_concejales);
$this->set('i2',$i);
$this->set('ano',$ano);
$this->set('cod',$cod);
}//fin function

function cancelar2m($ano=null,$cod=null,$i=null){
$this->layout = "ajax";
        	$d = $this->cfpd08_identificacion_alcaldia_concejales->findAll($this->SQLCA().' and ejercicio_fiscal='.$ano.' and cod_concejal='.$cod);
        	$codigo_concejales     = $d[0]['cfpd08_identificacion_alcaldia_concejales']['cod_concejal'];
        	$nombres_concejales     = $d[0]['cfpd08_identificacion_alcaldia_concejales']['nombres_apellidos'];

$this->set('codigo_concejales',$codigo_concejales);
$this->set('nombres_concejales',$nombres_concejales);
$this->set('ano',$ano);
$this->set('cod',$cod);
$this->set('i2',$i);
}//fin function

function guardar_editar2m($ano=null,$cod=null,$i=null){
	$this->layout='ajax';
	$codigo_concejales     = $this->data['cfpp08_identificacion_alcaldia']["codigo_concejales_".$i];
    $nombres_concejales     = $this->data['cfpp08_identificacion_alcaldia']["nombres_concejales_".$i];
	$upd="update cfpd08_identificacion_alcaldia_concejales set nombres_apellidos='".$nombres_concejales."' WHERE ejercicio_fiscal=$ano and cod_concejal=$cod and ".$this->SQLCA();
	$sw1 = $this->cfpd08_identificacion_alcaldia_concejales->execute($upd);
	$this->set('Message_existe', 'LOS DATOS FUERON GUARDADOS');
	$this->set('codigo_concejales',$codigo_concejales);
	$this->set('nombres_concejales',$nombres_concejales);
	$this->set('ano',$ano);
	$this->set('cod',$cod);
	$this->set('i2',$i);

}

function eliminar2m($ano=null,$cod=null){
	$this->layout='ajax';
	$upd="DELETE FROM cfpd08_identificacion_alcaldia_concejales WHERE ejercicio_fiscal=$ano and cod_concejal=$cod and ".$this->SQLCA();
	$sw1 = $this->cfpd08_identificacion_alcaldia_concejales->execute($upd);
	$datos3 =$this->cfpd08_identificacion_alcaldia_concejales->findAll($this->SQLCA().' and ejercicio_fiscal='.$ano,null,'cod_concejal ASC',null,null,null);
	$datos  =$this->cfpd08_identificacion_alcaldia->findAll($this->SQLCA().' and ejercicio_fiscal='.$ano);
	$this->set('datos3',$datos3);
	$this->set('datos',$datos);
		$d2=$this->cfpd08_identificacion_alcaldia_concejales->findAll($this->SQLCA().' and ejercicio_fiscal='.$ano,null,'cod_concejal DESC');
	if($d2==null){
     	$n2=1;
    }else{
     	$n2=$d2[0]["cfpd08_identificacion_alcaldia_concejales"]["cod_concejal"]+1;
    }
	echo'<script>';
       echo" document.getElementById('codigo_concejales').value           = '".mascara($n2,2)."'; ";
	echo'</script>';
	$this->set('Message_existe', 'LOS DATOS FUERON ELIMINADOS');
}

function guardar_modificar($ano=null,$pagina=null){
	$this->layout='ajax';
	$domicilio_legal = $this->data['cfpp08_identificacion_alcaldia']['domicilio_legal'];
  	$base_legal = $this->data['cfpp08_identificacion_alcaldia']['base_legal'];
  	$fecha_creacion = $this->data['cfpp08_identificacion_alcaldia']['fecha_creacion'];
  	$ciudad = $this->data['cfpp08_identificacion_alcaldia']['ciudad'];
  	$estado = $this->data['cfpp08_identificacion_alcaldia']['estado'];
  	$telefonos = $this->data['cfpp08_identificacion_alcaldia']['telefonos'];
  	$direccion_internet = $this->data['cfpp08_identificacion_alcaldia']['direccion_internet'];
  	if($direccion_internet==null){
  		$direccion_internet='0';
  	}
  	$fax = $this->data['cfpp08_identificacion_alcaldia']['fax'];
  	$rif = $this->data['cfpp08_identificacion_alcaldia']['rif'];
  	$codigo_postal = $this->data['cfpp08_identificacion_alcaldia']['codigo_postal'];
  	$alcalde = $this->data['cfpp08_identificacion_alcaldia']['alcalde'];
  	$update  = "update cfpd08_identificacion_alcaldia set domicilio_legal='".$domicilio_legal."', base_legal='".$base_legal."', fecha_creacion='".$fecha_creacion."', ciudad='".$ciudad."', estado='".$estado."',
  	telefonos='".$telefonos."', direccion_internet='".$direccion_internet."', fax='".$fax."', rif='".$rif."', codigo_postal='".$codigo_postal."', alcalde='".$alcalde."' where ejercicio_fiscal=$ano and ".$this->SQLCA();
	$this->cfpd08_identificacion_alcaldia->execute($update);
	$this->set('Message_existe', 'LOS DATOS FUERON GUARDADOS');
    $this->consulta($pagina);
    $this->render("consulta");
}

function eliminar($ano=null,$pagina=null){
 	$this->layout = "ajax";
 	$ca=$this->SQLCA();
 	$cond='ejercicio_fiscal='.$ano.' and '.$this->SQLCA();
 	$this->cfpd08_identificacion_alcaldia->execute("DELETE FROM cfpd08_identificacion_alcaldia  WHERE ".$cond);
 	$y=$this->cfpd08_identificacion_alcaldia->findCount($this->SQLCA());
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
}//fin function

function buscar_por_pista($var1=null, $var2=null, $var3=null){
$this->layout="ajax";

    if($var3==null){$var2 = strtoupper($var2);
					$this->Session->write('pista', $var2);
					//if(is_int($var2)){$sql   = " ((rif_cedula LIKE '%$var2%') or (razon_social LIKE '%$var2%'))  or   ";}else{ $sql = "";}
					$Tfilas=$this->cfpd08_identificacion_alcaldia->findCount("(ejercicio_fiscal::text LIKE '%$var2%') and ".$this->SQLCA());
					        if($Tfilas!=0){
					        	$pagina=1;
					        	$Tfilas=(int)ceil($Tfilas/50);
					        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
								$this->set('total_paginas',$Tfilas);
								$this->set('pagina_actual',$pagina);
								$this->set('ultimo',$Tfilas);
					     	    $datos_filas=$this->cfpd08_identificacion_alcaldia->findAll("(ejercicio_fiscal::text LIKE '%$var2%') and ".$this->SQLCA(),null,"ejercicio_fiscal ASC",50,1,null);
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
						//if(is_int($var22)){$sql   = " (codigo_prod_serv LIKE '%$var22%')  or   ";}else{ $sql = "";}
						$Tfilas=$this->cfpd08_identificacion_alcaldia->findCount("(ejercicio_fiscal::text LIKE '%$var2%') and ".$this->SQLCA());
						        if($Tfilas!=0){
						        	$pagina=$var3;
						        	$Tfilas=(int)ceil($Tfilas/50);
						        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
									$this->set('total_paginas',$Tfilas);
									$this->set('pagina_actual',$pagina);
									$this->set('ultimo',$Tfilas);
						     	    $datos_filas=$this->cfpd08_identificacion_alcaldia->findAll("(ejercicio_fiscal::text LIKE '%$var2%') and ".$this->SQLCA(),null,"ejercicio_fiscal ASC",50,$pagina,null);
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

function consulta2($ano){
	$this->layout='ajax';
	$datos  =$this->cfpd08_identificacion_alcaldia->findAll($this->SQLCA().' and ejercicio_fiscal='.$ano);
	$datos2 =$this->cfpd08_identificacion_alcaldia_directivos->findAll($this->SQLCA().' and ejercicio_fiscal='.$ano,null,'cod_directivo ASC',null,null,null);
	$datos3 =$this->cfpd08_identificacion_alcaldia_concejales->findAll($this->SQLCA().' and ejercicio_fiscal='.$ano,null,'cod_concejal ASC',null,null,null);
    $this->set('datos',$datos);
    $this->set('datos2',$datos2);
    $this->set('datos3',$datos3);
}


}//fin class
?>