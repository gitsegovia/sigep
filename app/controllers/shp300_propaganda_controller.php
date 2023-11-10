<?php

 class shp300PropagandaController extends AppController{
 	var $name = "shp300_propaganda";
	var $uses = array('v_shd300_propaganda','shd300_tipo_propaganda','shd300_recargos','shd300_detalles_propaganda',
	'shd300_propaganda','v_shd100_patente_actividades','v_shd100_patente','v_shd100_solicitud','shd100_patente','shd001_registro_contribuyentes','shd100_patente_actividades','shd100_solicitud',
                      'shd100_solicitud_actividades', 'shd002_cobradores', 'shd100_actividades', 'cnmd06_profesiones','cugd01_republica', 'cugd01_estados',
                      'cugd01_municipios', 'cugd01_parroquias', 'cugd01_centropoblados', 'cugd01_vialidad', 'cugd01_vereda', 'v_shd001_registro_contribuyentes', 'shd300_detalles_adicional');
 	var $helpers = array('Html','Ajax','Javascript', 'Sisap');

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



function SQLCX($ano=null){//sql para busqueda de codigos de arranque con y sin año
         $sql_re = "cod_presi=".$this->verifica_SS(1)."  and    ";
         $sql_re .= "cod_entidad=".$this->verifica_SS(2)."  and  ";
         $sql_re .= "cod_tipo_inst=".$this->verifica_SS(3)."  and ";
         $sql_re .= "cod_inst=".$this->verifica_SS(4)." ";
		 return $sql_re;
}//fin funcion SQLX


function SQLCAX($ano=null){//sql para busqueda de codigos de arranque con y sin año
         $sql_re = "cod_republica=".$this->verifica_SS(1)."  and    ";
         return $sql_re;
}//fin funcion SQLCA


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

function index($var=null){
  	$this->layout = "ajax";
 	$this->data = null;
 	//$_SESSION = array();
 	//unset($_SESSION);
 	$this->Session->delete('pro');
 	$this->Session->delete('recargo');
 	$this->Session->delete('rif_cedula');

 	$rif_cedula 	= $this->shd002_cobradores->generateList($this->condicion(), 'rif_ci ASC', null, '{n}.shd002_cobradores.rif_ci', '{n}.shd002_cobradores.nombre_razon');
	if($rif_cedula != null){
	$this->concatena($rif_cedula, 'rif_cedula');
	}else if($rif_cedula ==null){
		$this->set('rif_cedula',array());
	}
	$cod_tipo = $this->shd300_tipo_propaganda->findAll($this->condicion(), null, 'cod_tipo ASC');
	if($cod_tipo!= null){
	$this->set("cod_tipo", $cod_tipo);
	}

	$cod_recargo 	= $this->shd300_recargos->generateList($this->SQLCA(), 'cod_recargo ASC', null, '{n}.shd300_recargos.cod_recargo', '{n}.shd300_recargos.denominacion');
	if($cod_recargo!= null){
	$this->concatena($cod_recargo, 'cod_recargo');
	}else if($cod_recargo ==null){
		$this->set('cod_recargo',array());
	}
}//fin index







function agregar_tipo_propaganda_consulta($var1=null, $var2=null){
   $this->layout = "ajax";


$cont = 0;
$this->set('cod_recargo',$this->shd300_recargos->findAll($this->SQLCA()));


if($var1!=null){
    $b = $this->shd300_tipo_propaganda->findAll($this->condicion().' and cod_tipo='.$var1);
	$this->set("denominacion",$b[0]['shd300_tipo_propaganda']['denominacion']);
	$this->set("codigo",      $b[0]['shd300_tipo_propaganda']['cod_tipo']);
    if(isset($_SESSION["pro"])){ $this->set("accion", $_SESSION["pro"]);}
}else{

}//fin else


}//fin function




function agregar_tipo_propaganda($var1=null, $var2=null){
   $this->layout = "ajax";


$cont = 0;
$this->set('cod_recargo',$this->shd300_recargos->findAll($this->SQLCA()));



if($var1!=null){

	$rif_cedula = $_SESSION["rif_cedula"];

    $shd300_detalles_adicional  = $this->shd300_detalles_adicional->findAll($this->condicion()."  and rif_cedula='".$rif_cedula."' and cod_tipo='".$var1."'   ", null, "numero, cod_tipo, cod_recargo ASC");
    $shd300_detalles_propaganda = $this->shd300_detalles_propaganda->findAll($this->condicion()." and rif_cedula='".$rif_cedula."'  ", null, "numero, cod_tipo ASC");


    if(!isset($_SESSION["recargo"][$var1])){
       foreach($shd300_detalles_adicional as $ve1){
       	   $cod_tipo     = $ve1["shd300_detalles_adicional"]["cod_tipo"];
       	   $numero       = $ve1["shd300_detalles_adicional"]["numero"];
       	   $cod_recargo  = $ve1["shd300_detalles_adicional"]["cod_recargo"];
       	   $monto_re     = $ve1["shd300_detalles_adicional"]["monto"];
       	   $porcentaje   = $ve1["shd300_detalles_adicional"]["porcentaje_recargo"];

           if(!isset($_SESSION["recargo"][$var1][$numero])){
                  $_SESSION["recargo"][$var1][$numero] = $cod_recargo;
                  $_SESSION["recargo"][$cod_tipo][$numero."_".$cod_recargo]["monto_re"] = $monto_re;
                  $_SESSION["recargo"][$cod_tipo][$numero."_".$cod_recargo]["porcentaje"] = $porcentaje * 100;
           }else{
                  $_SESSION["recargo"][$var1][$numero] .= ",".$cod_recargo;
                  $_SESSION["recargo"][$cod_tipo][$numero."_".$cod_recargo]["monto_re"]   = $monto_re;
                  $_SESSION["recargo"][$cod_tipo][$numero."_".$cod_recargo]["porcentaje"] = $porcentaje * 100;
           }
       }
    }

    if(!isset($_SESSION["pro"])){
        foreach($shd300_detalles_propaganda as $ve2){
        	   $b = $this->shd300_tipo_propaganda->findAll($this->condicion().' and cod_tipo='.$ve2["shd300_detalles_propaganda"]['cod_tipo']);

               $cod_tipo               =  $ve2["shd300_detalles_propaganda"]['cod_tipo'];
			   $monto_articulo         =  $b[0]['shd300_tipo_propaganda']['monto'];
			   $deno_tipo              =  $b[0]['shd300_tipo_propaganda']['denominacion'];
			   $num_tipo               =  $ve2["shd300_detalles_propaganda"]['numero'];
			   $espesor                =  $ve2["shd300_detalles_propaganda"]['espesor'];
			   $largo                  =  $ve2["shd300_detalles_propaganda"]['largo'];
			   $alto                   =  $ve2["shd300_detalles_propaganda"]['alto'];
			   $aream2                 =  $ve2["shd300_detalles_propaganda"]['area'];
			   $cnt_am2                =  $ve2["shd300_detalles_propaganda"]['cantidad'];
			   $monto_mensual          =  $ve2["shd300_detalles_propaganda"]['monto'];
			   $monto_adicional        =  $ve2["shd300_detalles_propaganda"]['monto_adicional'];
			   $total_mensual          =  $ve2["shd300_detalles_propaganda"]['monto_mensual'];
			   $fecha_registro         =  $ve2["shd300_detalles_propaganda"]['fecha_registro'];
			   $ubicacion              =  $ve2["shd300_detalles_propaganda"]['ubicacion'];

			             if(!isset($_SESSION["pro"])){
							  $_SESSION["CUENTA"] = 1;
				              $cont               = 1;
				              $_SESSION["pro"]["contador_tipo_publicidad"][$cod_tipo] = 0;
						}else{
                                 $_SESSION["CUENTA"]++;
                              	 $cont = $_SESSION["CUENTA"];
						}//fin else

                           	  $_SESSION["pro"]["contador_tipo_publicidad"][$cod_tipo]=$num_tipo;
				              $_SESSION["pro"][$cont]["id"]                    = $cont;
				              $_SESSION["pro"][$cont]["condicion_actividad"]   = 1;
				              $_SESSION["pro"][$cont]["cod_tipo"]              = $cod_tipo;
				              $_SESSION["pro"][$cont]["num_tipo"]              = $num_tipo;
				              $_SESSION["pro"][$cont]["deno_tipo"]             = $deno_tipo;
				              $_SESSION["pro"][$cont]["monto_articulo"]        = $this->Formato2($monto_articulo);
				              $_SESSION["pro"][$cont]["espesor"]               = $this->Formato_3_out($espesor);
				              $_SESSION["pro"][$cont]["largo"]                 = $this->Formato_3_out($largo);
				              $_SESSION["pro"][$cont]["alto"]                  = $this->Formato_3_out($alto);
				              $_SESSION["pro"][$cont]["area"]                  = $this->Formato_3_out($aream2);
				              $_SESSION["pro"][$cont]["cantidad_area2"]        = $this->Formato_3_out($cnt_am2);
				              $_SESSION["pro"][$cont]["monto_mensual"]         = $this->Formato2($monto_mensual);
				              $_SESSION["pro"][$cont]["monto_adicional"]       = $this->Formato2($monto_adicional);
				              $_SESSION["pro"][$cont]["total_mensual"]         = $this->Formato2($total_mensual);
				              $_SESSION["pro"][$cont]["fecha_registro"]        = cambiar_formato_fecha($fecha_registro);
				              $_SESSION["pro"][$cont]["ubicacion"]             = $ubicacion;
       }
    }

	$b = $this->shd300_tipo_propaganda->findAll($this->condicion().' and cod_tipo='.$var1);
	$this->set("denominacion",$b[0]['shd300_tipo_propaganda']['denominacion']);
	$this->set("codigo",      $b[0]['shd300_tipo_propaganda']['cod_tipo']);
    $cont = 0;
    if(!isset($_SESSION["pro"])){
		  $cont               = 1;
	}else{
          for($i=1; $i<=$_SESSION["CUENTA"]; $i++){
             if($_SESSION["pro"][$i]["cod_tipo"]==$var1 && $_SESSION["pro"][$i]["condicion_actividad"]==2){
                   if($_SESSION["pro"][$i]["num_tipo"]<=$_SESSION["pro"]["contador_tipo_publicidad"][$var1]){
                   	 $cont = $_SESSION["pro"][$i]["num_tipo"];
                   	 break;
                   }
             }//fin if
          }//fin for
	}//fin else

	if($cont==0){
		if(isset($_SESSION["pro"]["contador_tipo_publicidad"][$var1])){
			$cont = $_SESSION["pro"]["contador_tipo_publicidad"][$var1] + 1;
		}else{
			$cont = 1;
		}
	}
    if(isset($_SESSION["pro"])){ $this->set("accion", $_SESSION["pro"]);}
	$this->set("numero",  $cont);

}else{


}//fin else


}//fin funtion






function recargo_propaganda($var1=null, $var2=null, $var3=null, $var4=null){

	$this->layout = "ajax";
	$this->set('cod_recargo',$this->shd300_recargos->findAll($this->SQLCA()));
	$recargo     = $_SESSION["recargo"][$var2][$var4];
	$recargo_aux = split(",", $recargo);
	$nuevo       = "";

          if($var1==1){

               for($a_recargo=0; $a_recargo<count($recargo_aux); $a_recargo++){
	                 if($nuevo==""){
	                     $nuevo  = $recargo_aux[$a_recargo];
	                 }else{
	                     $nuevo .= ",".$recargo_aux[$a_recargo];
	                 }
		         }
                 if($nuevo==""){
                 	   $nuevo  = $var3;
                 	   $datos  = $this->shd300_recargos->findAll($this->SQLCA()." and cod_recargo='".$var3."' ");
	                   $_SESSION["recargo"][$var2][$var4."_".$var3]["porcentaje"] = $datos[0]["shd300_recargos"]["porcentaje"];
                 }else{
                       $nuevo .= ",".$var3;
                       $datos  = $this->shd300_recargos->findAll($this->SQLCA()." and cod_recargo='".$var3."' ");
	                   $_SESSION["recargo"][$var2][$var4."_".$var3]["porcentaje"] = $datos[0]["shd300_recargos"]["porcentaje"];
                 }
    }else if($var1==2){
       for($a_recargo=0; $a_recargo<count($recargo_aux); $a_recargo++){
             if($var3!=$recargo_aux[$a_recargo]){
                 if($nuevo==""){
                     $nuevo  = $recargo_aux[$a_recargo];
                 }else{
                     $nuevo .= ",".$recargo_aux[$a_recargo];
                 }
             }
         }
     }//fin else

$_SESSION["recargo"][$var2][$var4] = $nuevo;
$this->set("codigo",  $var2);
$this->set("numero",  $var4);
$this->set("recargo", $_SESSION["recargo"][$var2][$var4]);
}



function agregar_publicidad($codigo=null, $numero=null){

	$this->layout = "ajax";

	$b = $this->shd300_tipo_propaganda->findAll($this->condicion().' and cod_tipo='.$codigo);
	$this->set('cod_recargo',$this->shd300_recargos->findAll($this->SQLCA()));
	$this->set("denominacion",$b[0]['shd300_tipo_propaganda']['denominacion']);
	$this->set("codigo",      $b[0]['shd300_tipo_propaganda']['cod_tipo']);
	$this->set("monto",       $b[0]['shd300_tipo_propaganda']['monto']);
	$this->set("tipo_unidad", $b[0]['shd300_tipo_propaganda']['tipo_unidad']);
	$this->set("numero",      $numero);
	if(!isset($_SESSION["recargo"][$b[0]['shd300_tipo_propaganda']['cod_tipo']][$numero])){$_SESSION["recargo"][$b[0]['shd300_tipo_propaganda']['cod_tipo']][$numero]="";}
	$this->set("recargo", $_SESSION["recargo"][$b[0]['shd300_tipo_propaganda']['cod_tipo']][$numero]);

}



function modificar_publicidad_consulta($id=null, $numero=null, $codigo=null){
	$this->layout = "ajax";
    $b = $this->shd300_tipo_propaganda->findAll($this->condicion().' and cod_tipo='.$codigo);
	$this->set('cod_recargo',$this->shd300_recargos->findAll($this->SQLCA()));
	$this->set("denominacion",$b[0]['shd300_tipo_propaganda']['denominacion']);
	$this->set("codigo",      $b[0]['shd300_tipo_propaganda']['cod_tipo']);
	$this->set("monto",       $b[0]['shd300_tipo_propaganda']['monto']);
	$this->set("tipo_unidad", $b[0]['shd300_tipo_propaganda']['tipo_unidad']);
	$this->set("numero",      $numero);
	if(!isset($_SESSION["recargo"][$b[0]['shd300_tipo_propaganda']['cod_tipo']][$numero])){$_SESSION["recargo"][$b[0]['shd300_tipo_propaganda']['cod_tipo']][$numero]="";}
	$this->set("recargo", $_SESSION["recargo"][$b[0]['shd300_tipo_propaganda']['cod_tipo']][$numero]);
	$this->set("accion",  $_SESSION["pro"][$id]);
}//fin function


function modificar_publicidad($id=null, $numero=null, $codigo=null){

	$this->layout = "ajax";
    $b = $this->shd300_tipo_propaganda->findAll($this->condicion().' and cod_tipo='.$codigo);
	$this->set('cod_recargo',$this->shd300_recargos->findAll($this->SQLCA()));
	$this->set("denominacion",$b[0]['shd300_tipo_propaganda']['denominacion']);
	$this->set("codigo",      $b[0]['shd300_tipo_propaganda']['cod_tipo']);
	$this->set("monto",       $b[0]['shd300_tipo_propaganda']['monto']);
	$this->set("tipo_unidad", $b[0]['shd300_tipo_propaganda']['tipo_unidad']);
	$this->set("numero",      $numero);
	if(!isset($_SESSION["recargo"][$b[0]['shd300_tipo_propaganda']['cod_tipo']][$numero])){$_SESSION["recargo"][$b[0]['shd300_tipo_propaganda']['cod_tipo']][$numero]="";}
	$this->set("recargo", $_SESSION["recargo"][$b[0]['shd300_tipo_propaganda']['cod_tipo']][$numero]);
	$this->set("accion",  $_SESSION["pro"][$id]);


}


function eliminar_publicidad($count=null, $numero=null, $codigo=null){

	$this->layout = "ajax";
    $_SESSION["pro"][$count]["condicion_actividad"] = 2;

    $cod_tipo = $_SESSION["pro"][$count]["cod_tipo"];
	$num_tipo = $_SESSION["pro"][$count]["num_tipo"];
    if(isset($_SESSION["recargo"][$cod_tipo][$num_tipo])){
       $_SESSION["recargo"][$cod_tipo][$num_tipo] = "";
    }

    $this->set('cod_recargo',$this->shd300_recargos->findAll($this->SQLCA()));
    $var1=$codigo;

				                $b = $this->shd300_tipo_propaganda->findAll($this->condicion().' and cod_tipo='.$var1);
								$this->set("denominacion",$b[0]['shd300_tipo_propaganda']['denominacion']);
								$this->set("codigo",      $b[0]['shd300_tipo_propaganda']['cod_tipo']);

							    $cont = 0;

							    if(!isset($_SESSION["pro"])){
									  $cont               = 1;
								}else{
							          for($i=1; $i<=$_SESSION["CUENTA"]; $i++){
							             if($_SESSION["pro"][$i]["cod_tipo"]==$var1 && $_SESSION["pro"][$i]["condicion_actividad"]==2){
							                   if($_SESSION["pro"][$i]["num_tipo"]<=$_SESSION["pro"]["contador_tipo_publicidad"][$var1]){
							                   	 $cont = $_SESSION["pro"][$i]["num_tipo"];
							                   	 break;
							                   }
							             }//fin if
							          }//fin for
								}//fin else

								if($cont==0){
									if(isset($_SESSION["pro"]["contador_tipo_publicidad"][$var1])){
										$cont = $_SESSION["pro"]["contador_tipo_publicidad"][$var1] + 1;
									}else{
										$cont = 1;
									}

								}
							    if(isset($_SESSION["pro"])){ $this->set("accion", $_SESSION["pro"]);}

								$this->set("numero",  $cont);
}





function agregar_grilla(){

$this->layout = "ajax";

$this->set('cod_recargo',$this->shd300_recargos->findAll($this->SQLCA()));
$cont = 0;



if(!empty($this->data['shp300_propaganda']['cod_tipo'])             &&
   !empty($this->data['shp300_propaganda']['monto_articulo'])       &&
   !empty($this->data['shp300_propaganda']['num_tipo'])             &&
   !empty($this->data['shp300_propaganda']['deno_tipo'])            &&
   !empty($this->data['shp300_propaganda']['monto_mensual'])        &&
   !empty($this->data['shp300_propaganda']['monto_adicional'])      &&
   !empty($this->data['shp300_propaganda']['total_mensual'])        &&
   !empty($this->data['shp300_propaganda']['fecha_registro'])       &&
   !empty($this->data['shp300_propaganda']['ubicacion'])){


   $cod_tipo               =  $this->data['shp300_propaganda']['cod_tipo'];
   $monto_articulo         =  $this->data['shp300_propaganda']['monto_articulo'];
   $num_tipo               =  $this->data['shp300_propaganda']['num_tipo'];
   $deno_tipo              =  $this->data['shp300_propaganda']['deno_tipo'];
   $espesor                =  empty($this->data['shp300_propaganda']['espesor'])?0:$this->data['shp300_propaganda']['espesor'];
   $largo                  =  empty($this->data['shp300_propaganda']['largo'])?0:$this->data['shp300_propaganda']['largo'];
   $alto                   =  empty($this->data['shp300_propaganda']['alto'])?0:$this->data['shp300_propaganda']['alto'];
   $aream2                 =  empty($this->data['shp300_propaganda']['area'])?0:$this->data['shp300_propaganda']['area'];
   $cnt_am2                =  empty($this->data['shp300_propaganda']['cantidad_area2'])?0:$this->data['shp300_propaganda']['cantidad_area2'];
   $monto_mensual          =  $this->data['shp300_propaganda']['monto_mensual'];
   $monto_adicional        =  $this->data['shp300_propaganda']['monto_adicional'];
   $total_mensual          =  $this->data['shp300_propaganda']['total_mensual'];
   $fecha_registro         =  $this->data['shp300_propaganda']['fecha_registro'];
   $ubicacion              =  $this->data['shp300_propaganda']['ubicacion'];




						if(!isset($_SESSION["pro"])){
							  $_SESSION["CUENTA"] = 1;
				              $cont               = 1;
				              $_SESSION["pro"]["contador_tipo_publicidad"][$cod_tipo] = 0;
//				              $this->set("Message_existe","EL REGISTRO FUE AGREGADO");
						}else{
                              for($i=1; $i<=$_SESSION["CUENTA"]; $i++){
                                 if($_SESSION["pro"][$i]["cod_tipo"]==$cod_tipo && $_SESSION["pro"][$i]["num_tipo"]==$num_tipo){
                                         $cont = $i;
                                         break;
                                 }//fin if
                              }//fin for
                              if($cont==0){
                              	 $_SESSION["CUENTA"]++;
                              	 $cont = $_SESSION["CUENTA"];
//                              	 $this->set("Message_existe","LA PUBLICIDAD FUE AGREGADO");
                              }else{
//                              	 $this->set("Message_existe","LA PUBLICIDAD FUE ACTUALIZADA");
                              }
						}//fin else

                           if(isset($_SESSION["pro"]["contador_tipo_publicidad"][$cod_tipo])){
                              if($num_tipo>$_SESSION["pro"]["contador_tipo_publicidad"][$cod_tipo]){
                              	$_SESSION["pro"]["contador_tipo_publicidad"][$cod_tipo]++;
                              }
                           }else{
                           	     $_SESSION["pro"]["contador_tipo_publicidad"][$cod_tipo]=1;
                           }

				              $_SESSION["pro"][$cont]["id"]                    = $cont;
				              $_SESSION["pro"][$cont]["condicion_actividad"]   = 1;
				              $_SESSION["pro"][$cont]["cod_tipo"]              = $cod_tipo;
				              $_SESSION["pro"][$cont]["num_tipo"]              = $num_tipo;
				              $_SESSION["pro"][$cont]["deno_tipo"]             = $deno_tipo;
				              $_SESSION["pro"][$cont]["monto_articulo"]        = $monto_articulo;
				              $_SESSION["pro"][$cont]["espesor"]               = $espesor;
				              $_SESSION["pro"][$cont]["largo"]                 = $largo;
				              $_SESSION["pro"][$cont]["alto"]                  = $alto;
				              $_SESSION["pro"][$cont]["area"]                  = $aream2;
				              $_SESSION["pro"][$cont]["cantidad_area2"]        = $cnt_am2;
				              $_SESSION["pro"][$cont]["monto_mensual"]         = $monto_mensual;
				              $_SESSION["pro"][$cont]["monto_adicional"]       = $monto_adicional;
				              $_SESSION["pro"][$cont]["total_mensual"]         = $total_mensual;
				              $_SESSION["pro"][$cont]["fecha_registro"]        = $fecha_registro;
				              $_SESSION["pro"][$cont]["ubicacion"]             = $ubicacion;



                                $var1=$cod_tipo;

				                $b = $this->shd300_tipo_propaganda->findAll($this->condicion().' and cod_tipo='.$var1);
								$this->set("denominacion",$b[0]['shd300_tipo_propaganda']['denominacion']);
								$this->set("codigo",      $b[0]['shd300_tipo_propaganda']['cod_tipo']);

							    $cont = 0;

							    if(!isset($_SESSION["pro"])){
									  $cont               = 1;
								}else{
							          for($i=1; $i<=$_SESSION["CUENTA"]; $i++){
							             if($_SESSION["pro"][$i]["cod_tipo"]==$var1 && $_SESSION["pro"][$i]["condicion_actividad"]==2){
							                   if($_SESSION["pro"][$i]["num_tipo"]<=$_SESSION["pro"]["contador_tipo_publicidad"][$var1]){
							                   	 $cont = $_SESSION["pro"][$i]["num_tipo"];
							                   	 break;
							                   }
							             }//fin if
							          }//fin for
								}//fin else

								if($cont==0){
									if(isset($_SESSION["pro"]["contador_tipo_publicidad"][$var1])){
										$cont = $_SESSION["pro"]["contador_tipo_publicidad"][$var1] + 1;
									}else{
										$cont = 1;
									}

								}
							    if(isset($_SESSION["pro"])){ $this->set("accion", $_SESSION["pro"]);}
								$this->set("numero",  $cont);



			}else{

 			 $this->set("errorMessage","POR FAVOR INSERTE LOS DATOS DE LA PUBLICIDAD");

			}

if(isset($_SESSION["pro"])){
	$this->set("accion", $_SESSION["pro"]);
}


}//fin function





function codigo_rif($codigo){
	$this->layout = "ajax";
    $this->set("a",$codigo);
}//fin cpcp02_codigo

function denominacion_rif($codigo){
	$this->layout = "ajax";
	$b = $this->shd002_cobradores->findAll("rif_ci='".$codigo."'",array('rif_ci','nombre_razon'));
	$this->set("b",$b[0]['shd002_cobradores']['nombre_razon']);


}//fin cpcp02_denominacion





function buscar_propaganda($var1=null){
	$this->layout="ajax";
	$this->set("opcion",$var1);
	$this->Session->delete('pista');
}//fin function

function buscar_propaganda_2($var1=null, $var2=null, $var3=null){
$this->layout="ajax";

    if($var3==null){$var2 = strtoupper($var2);
					$this->Session->write('pista', $var2);
					$sql = $this->condicion()." and ".$this->busca_separado(array("rif_cedula","nombre_razon"), $var2);
					$Tfilas=$this->v_shd300_propaganda->findCount($sql);
					        if($Tfilas!=0){
					        	$pagina=1;
					        	$Tfilas=(int)ceil($Tfilas/100);
					        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
								$this->set('total_paginas',$Tfilas);
								$this->set('pagina_actual',$pagina);
								$this->set('ultimo',$Tfilas);
					     	    $datos_filas=$this->v_shd300_propaganda->findAll($sql,null,"rif_cedula ASC",100,1,null);
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
						$sql = $this->condicion()." and ".$this->busca_separado(array("rif_cedula","nombre_razon"), $var22);
						$Tfilas=$this->v_shd300_propaganda->findCount($sql);
						        if($Tfilas!=0){
						        	$pagina=$var3;
						        	$Tfilas=(int)ceil($Tfilas/100);
						        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
									$this->set('total_paginas',$Tfilas);
									$this->set('pagina_actual',$pagina);
									$this->set('ultimo',$Tfilas);
						     	    $datos_filas=$this->v_shd300_propaganda->findAll($sql,null,"rif_cedula ASC",100,$pagina,null);
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



















function buscar_constribuyente($var1=null){
	$this->layout="ajax";
	$this->set("opcion",$var1);
	$this->Session->delete('pista');
}//fin function

function buscar_por_pista($var1=null, $var2=null, $var3=null){
$this->layout="ajax";

    if($var3==null){$var2 = strtoupper($var2);
					$this->Session->write('pista', $var2);
					//if(is_int($var2)){$sql   = " ((rif_cedula LIKE '%$var2%') or (razon_social LIKE '%$var2%'))  or   ";}else{ $sql = "";}
					$Tfilas=$this->v_shd001_registro_contribuyentes->findCount("((rif_cedula LIKE '%$var2%') or (razon_social_nombres LIKE '%$var2%'))");
					        if($Tfilas!=0){
					        	$pagina=1;
					        	$Tfilas=(int)ceil($Tfilas/100);
					        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
								$this->set('total_paginas',$Tfilas);
								$this->set('pagina_actual',$pagina);
								$this->set('ultimo',$Tfilas);
					     	    $datos_filas=$this->v_shd001_registro_contribuyentes->findAll("((rif_cedula LIKE '%$var2%') or (razon_social_nombres LIKE '%$var2%'))   ",null,"rif_cedula ASC",100,1,null);
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
						$Tfilas=$this->v_shd001_registro_contribuyentes->findCount("((rif_cedula LIKE '%$var22%') or (razon_social_nombres LIKE '%$var22%'))");
						        if($Tfilas!=0){
						        	$pagina=$var3;
						        	$Tfilas=(int)ceil($Tfilas/100);
						        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
									$this->set('total_paginas',$Tfilas);
									$this->set('pagina_actual',$pagina);
									$this->set('ultimo',$Tfilas);
						     	    $datos_filas=$this->v_shd001_registro_contribuyentes->findAll("((rif_cedula LIKE '%$var22%') or (razon_social_nombres LIKE '%$var22%'))  ",null,"rif_cedula ASC",100,$pagina,null);
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


function seleccion_busqueda_venta($var1=null){
$this->layout="ajax";

$contar=$this->v_shd300_propaganda->findCount($this->condicion()." and rif_cedula='".$var1."'",null,'rif_cedula ASC',null,null,null);

if($contar!=0){

echo "<script type='text/javascript'>ver_documento('/shp300_propaganda/modificar/".$var1."','principal');</script>";
$this->set('opcion',1);
}else{
$this->set('opcion',2);
				       $datos=$this->shd001_registro_contribuyentes->findAll("rif_cedula='".$var1."'");
					if($datos != null){
					$cod_profesion=$datos[0]["shd001_registro_contribuyentes"]["profesion"];
					$cod_pais=$datos[0]["shd001_registro_contribuyentes"]["cod_pais"];
					$cod_estado=$datos[0]["shd001_registro_contribuyentes"]["cod_estado"];
					$cod_municipio=$datos[0]["shd001_registro_contribuyentes"]["cod_municipio"];
					$cod_parroquia=$datos[0]["shd001_registro_contribuyentes"]["cod_parroquia"];
					$cod_centro_poblado=$datos[0]["shd001_registro_contribuyentes"]["cod_centro_poblado"];
					$cod_calle_avenida=$datos[0]["shd001_registro_contribuyentes"]["cod_calle_avenida"];
					$cod_vereda_edificio=$datos[0]["shd001_registro_contribuyentes"]["cod_vereda_edificio"];
					$pais=$this->cugd01_republica->findAll('cod_republica='.$cod_pais);
					$estados=$this->cugd01_estados->findAll('cod_republica='.$cod_pais.' and cod_estado='.$cod_estado);
					$municipios=$this->cugd01_municipios->findAll('cod_republica='.$cod_pais.' and cod_estado='.$cod_estado.' and cod_municipio='.$cod_municipio);
					$parroquias=$this->cugd01_parroquias->findAll('cod_republica='.$cod_pais.' and cod_estado='.$cod_estado.' and cod_municipio='.$cod_municipio.' and cod_parroquia='.$cod_parroquia);
					$centros=$this->cugd01_centropoblados->findAll('cod_republica='.$cod_pais.' and cod_estado='.$cod_estado.' and cod_municipio='.$cod_municipio.' and cod_parroquia='.$cod_parroquia.' and cod_centro='.$cod_centro_poblado);
					$vialidad=$this->cugd01_vialidad->findAll('cod_republica='.$cod_pais.' and cod_estado='.$cod_estado.' and cod_municipio='.$cod_municipio.' and cod_parroquia='.$cod_parroquia.' and cod_centro='.$cod_centro_poblado.' and cod_vialidad='.$cod_calle_avenida);
					$vereda=$this->cugd01_vereda->findAll('cod_republica='.$cod_pais.' and cod_estado='.$cod_estado.' and cod_municipio='.$cod_municipio.' and cod_parroquia='.$cod_parroquia.' and cod_centro='.$cod_centro_poblado.' and cod_vialidad='.$cod_calle_avenida.' and cod_vereda='.$cod_vereda_edificio);
					$profesiones=$this->cnmd06_profesiones->findAll('cod_profesion='.$cod_profesion);
					$this->set('profesion',$profesiones);
					$this->set('pais',$pais);
					$this->set('estados',$estados);
					$this->set('municipios',$municipios);
					$this->set('parroquias',$parroquias);
					$this->set('centros',$centros);
					$this->set('vialidad',$vialidad);
					$this->set('vereda',$vereda);
					$this->set('datos',$datos);
					$this->Session->write('rif_tipo',   $var1);
					$this->Session->write('rif_cedula', $var1);



				$this->set('datos',$datos);
				$resul = javascript_encode($datos[0]['shd001_registro_contribuyentes']['razon_social_nombres'], 1);
				   echo'<script>';
						  echo"document.getElementById('deno_rif').value = \"$resul\"; ";
						  echo "document.getElementById('rif_constribuyente').value='".$datos[0]['shd001_registro_contribuyentes']['rif_cedula']."';   ";
						  echo "document.getElementById('cod_tipo_pro').disabled='';   ";
						  echo "document.getElementById('recargo').disabled='';   ";
				   echo'</script>';
									/*echo "<script>";
									    echo "document.getElementById('deno_rif').value='".$datos[0]['shd001_registro_contribuyentes']['razon_social_nombres']."';   ";
									    echo "document.getElementById('rif_constribuyente').value='".$datos[0]['shd001_registro_contribuyentes']['rif_cedula']."';   ";
										echo "document.getElementById('cod_tipo_pro').disabled='';   ";
										echo "document.getElementById('recargo').disabled='';   ";
									echo "</script>";*/
				}else{
					$vacio='';
									echo "<script>";
										echo "document.getElementById('deno_rif').value='".$vacio."';   ";
										echo "document.getElementById('rif_constribuyente').value='".$vacio."';   ";
									echo "</script>";
				}

}

}//fin function






function guardar(){
	$this->layout = "ajax";//pr($this->data);

	$cod_presi 					= $this->Session->read('SScodpresi');
	$cod_entidad 				= $this->Session->read('SScodentidad');
	$cod_tipo_inst 				= $this->Session->read('SScodtipoinst');
	$cod_inst 					= $this->Session->read('SScodinst');
	$cod_dep 					= $this->Session->read('SScoddep');
  	$rif_cedula 				= $this->data['shp300_propaganda']['rif_constribuyente'];
  	$frecuencia_pago			= $this->data['shp300_propaganda']['frecuencia_pago'];
  	$pago_todo					= $this->data['shp300_propaganda']['pago_todo'];
  	$suspendido					= $this->data['shp300_propaganda']['suspendido'];
  	$rif_ci_cobrador			= $this->data['shp300_propaganda']['rif_ci_cobrador'];
  	$ultimo_ano_facturado		= '0';
  	$ultimo_mes_facturado		= '0';
	$monto_mensual_general		= $this->Formato1($this->data['shp300_propaganda']['monto_mensual_general']);


$this->shd300_detalles_propaganda->execute("BEGIN;");


$sw1 = $this->shd300_detalles_propaganda->execute("DELETE FROM shd300_detalles_adicional   WHERE ".$this->condicion()." and rif_cedula='".$rif_cedula."' ");
$sw1 = $this->shd300_detalles_propaganda->execute("DELETE FROM shd300_detalles_propaganda  WHERE ".$this->condicion()." and rif_cedula='".$rif_cedula."' ");
$sw1 = $this->shd300_detalles_propaganda->execute("DELETE FROM shd300_propaganda           WHERE ".$this->condicion()." and rif_cedula='".$rif_cedula."' ");

$SQL_INSERT ="INSERT INTO shd300_propaganda (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, rif_cedula, frecuencia_pago,
  monto_mensual_general, pago_todo, suspendido, rif_ci_cobrador, ultimo_ano_facturado, ultimo_mes_facturado)";
$SQL_INSERT .=" VALUES ($cod_presi, $cod_entidad, $cod_tipo_inst, $cod_inst, $cod_dep, '".$rif_cedula."', $frecuencia_pago,
  			$monto_mensual_general, $pago_todo, $suspendido, '".$rif_ci_cobrador."', $ultimo_ano_facturado, $ultimo_mes_facturado)";

$sw = $this->shd300_propaganda->execute($SQL_INSERT);

  if($sw>1){

           $cont  = $_SESSION["CUENTA"];

			 for($i=1; $i<=$cont; $i++){
			    if($_SESSION["pro"][$i]["condicion_actividad"]==1){
  					   	$cod_tipo        = $_SESSION["pro"][$i]["cod_tipo"];
  						$monto_articulo  = $this->Formato1($_SESSION["pro"][$i]["monto_articulo"]);
  						$numero          = $_SESSION["pro"][$i]["num_tipo"];
  						$monto_articulo                = $this->Formato1($_SESSION["pro"][$i]["monto_articulo"]);
  						$largo           = $this->Formato_3_in($_SESSION["pro"][$i]["largo"]);
						$alto            = $this->Formato_3_in($_SESSION["pro"][$i]["alto"]);
  						$area            = $this->Formato_3_in($_SESSION["pro"][$i]["area"]);
  						$espesor         = $this->Formato_3_in($_SESSION["pro"][$i]["espesor"]);
  						$cantidad        = $this->Formato_3_in($_SESSION["pro"][$i]["cantidad_area2"]);
  						$monto           = $this->Formato1($_SESSION["pro"][$i]["monto_mensual"]);
  						$monto_adicional = $this->Formato1($_SESSION["pro"][$i]["monto_adicional"]);
  						$total_mensual   = $this->Formato1($_SESSION["pro"][$i]["total_mensual"]);
  						$ubicacion       = $_SESSION["pro"][$i]["ubicacion"];
  						$fecha_registro  = $_SESSION["pro"][$i]["fecha_registro"];



					   $sql ="INSERT INTO shd300_detalles_propaganda ( cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, rif_cedula, cod_tipo,
  							numero, largo, alto, area, espesor, cantidad, monto, monto_adicional, monto_mensual, ubicacion, fecha_registro)";
					   $sql.="VALUES ( $cod_presi, $cod_entidad, $cod_tipo_inst, $cod_inst, $cod_dep, '".$rif_cedula."', $cod_tipo,
  							$numero, $largo, $alto, $area, $espesor, $cantidad, $monto, $monto_adicional, $total_mensual, '".$ubicacion."', '".$fecha_registro."');";
					   $sw1 = $this->shd300_detalles_propaganda->execute($sql);
					   if($sw1>1){
					   	     if(isset($_SESSION["recargo"][$cod_tipo][$numero])){
                                $recargo_aux = $_SESSION["recargo"][$cod_tipo][$numero];
                                $recargo_aux = split(",", $recargo_aux);
                                 for($a_recargo=0; $a_recargo<count($recargo_aux); $a_recargo++){
                                 	  if($recargo_aux[$a_recargo]!=""){
                                        $cod_recargo = $recargo_aux[$a_recargo];
                                        $datos = $this->shd300_recargos->findAll($this->SQLCA()." and cod_recargo='".$cod_recargo."' ");
//                                        $porcentaje    = $datos[0]["shd300_recargos"]["porcentaje"]/100;
                                        $porcentaje    = $_SESSION["recargo"][$cod_tipo][$numero."_".$cod_recargo]["porcentaje"]/100;
                                        $monto_recargo = $monto * $porcentaje;
		 								$sql  = " INSERT INTO shd300_detalles_adicional (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, rif_cedula, cod_tipo, numero, cod_recargo, monto, porcentaje_recargo) ";
		                                $sql .= " VALUES ('".$cod_presi."', '".$cod_entidad."', '".$cod_tipo_inst."', '".$cod_inst."', '".$cod_dep."', '".$rif_cedula."', '".$cod_tipo."', '".$numero."', '".$cod_recargo."', '".$monto_recargo."', '".$porcentaje."'); ";
							   	        $sw1  = $this->shd300_detalles_propaganda->execute($sql);
							   	        if($sw1>1){}else{break;}
                                 	  }
                                 }
					   	      }
					   }else{break;}
			    }//fin if
			 }//fin for


			                 if($sw1>1){
			                 	  $this->set('Message_existe', 'LOS DATOS FUERON GUARDADOS');
			                 	  $this->shd300_detalles_propaganda->execute("COMMIT;");
			                 }else{
			                 	  $this->set('errorMessage', 'LOS DATOS NO FUERON GUARDADOS');
			                 	  $this->shd300_detalles_propaganda->execute("ROLLBACK;");
			                 }

  }else{
    $this->set('errorMessage', 'LOS DATOS NO FUERON GUARDADOS - POR FAVOR INTENTE DE NUEVO');
  }//fin else
		$this->index();
		$this->render("index");
}



function consultar($rif_cedula=null){
$this->layout = "ajax";
	if($rif_cedula==null){
        $sql= $this->condicion();
	}else{
        $sql = $this->SQLCA()." and rif_cedula='".$rif_cedula."' ";
	}
    $pagina=1;
	$this->set('pagina',$pagina);
  	 $Tfilas=$this->v_shd300_propaganda->findCount($sql);
  	 if($Tfilas==0){
  	 	$this->set('errorMessage', 'NO SE ENCONTRARON DATOS');
  	 	$this->index();
  		$this->render("index");
  	 }
  	 $this->set('pag_cant',$pagina.'/'.$Tfilas);
  	 $datos=$this->v_shd300_propaganda->findAll($sql, null, 'rif_cedula ASC',1,$pagina,null);//pr($datos);
  	 $this->set('datos', $datos);
  	 $this->set('siguiente',$pagina+1);
  	 $this->set('anterior', $pagina-1);
     $this->bt_nav($Tfilas, $pagina);

     $cod_tipo = $this->shd300_tipo_propaganda->findAll($this->condicion(), null, 'cod_tipo ASC');
	 if($cod_tipo!= null){
	  $this->set("cod_tipo", $cod_tipo);
	 }


	$this->Session->delete('pro');
 	$this->Session->delete('recargo');
 	$this->Session->delete('rif_cedula');

	$this->Session->write('rif_tipo',   $rif_cedula);
	$this->Session->write('rif_cedula', $rif_cedula);

    $shd300_detalles_adicional  = $this->shd300_detalles_adicional->findAll($this->condicion()."  and rif_cedula='".$rif_cedula."'  ", null, "numero, cod_tipo, cod_recargo ASC");
    $shd300_detalles_propaganda = $this->shd300_detalles_propaganda->findAll($this->condicion()." and rif_cedula='".$rif_cedula."'  ", null, "numero, cod_tipo ASC");


    if(!isset($_SESSION["recargo"])){
       foreach($shd300_detalles_adicional as $ve1){
       	   $cod_tipo     = $ve1["shd300_detalles_adicional"]["cod_tipo"];
       	   $numero       = $ve1["shd300_detalles_adicional"]["numero"];
       	   $cod_recargo  = $ve1["shd300_detalles_adicional"]["cod_recargo"];
       	   $monto_re     = $ve1["shd300_detalles_adicional"]["monto"];
       	   $porcentaje   = $ve1["shd300_detalles_adicional"]["porcentaje_recargo"];
           if(!isset($_SESSION["recargo"][$cod_tipo][$numero])){
                  $_SESSION["recargo"][$cod_tipo][$numero] = $cod_recargo;
                  $_SESSION["recargo"][$cod_tipo][$numero."_".$cod_recargo]["monto_re"] = $monto_re;
                  $_SESSION["recargo"][$cod_tipo][$numero."_".$cod_recargo]["porcentaje"] = $porcentaje * 100;
           }else{
                  $_SESSION["recargo"][$cod_tipo][$numero] .= ",".$cod_recargo;
                  $_SESSION["recargo"][$cod_tipo][$numero."_".$cod_recargo]["monto_re"] = $monto_re;
                  $_SESSION["recargo"][$cod_tipo][$numero."_".$cod_recargo]["porcentaje"] = $porcentaje * 100;
           }
       }
    }

    if(!isset($_SESSION["pro"])){
        foreach($shd300_detalles_propaganda as $ve2){
        	   $b = $this->shd300_tipo_propaganda->findAll($this->condicion().' and cod_tipo='.$ve2["shd300_detalles_propaganda"]['cod_tipo']);

               $cod_tipo               =  $ve2["shd300_detalles_propaganda"]['cod_tipo'];
			   $monto_articulo         =  $b[0]['shd300_tipo_propaganda']['monto'];
			   $deno_tipo              =  $b[0]['shd300_tipo_propaganda']['denominacion'];
			   $num_tipo               =  $ve2["shd300_detalles_propaganda"]['numero'];
			   $espesor                =  $ve2["shd300_detalles_propaganda"]['espesor'];
			   $largo                  =  $ve2["shd300_detalles_propaganda"]['largo'];
			   $alto                   =  $ve2["shd300_detalles_propaganda"]['alto'];
			   $aream2                 =  $ve2["shd300_detalles_propaganda"]['area'];
			   $cnt_am2                =  $ve2["shd300_detalles_propaganda"]['cantidad'];
			   $monto_mensual          =  $ve2["shd300_detalles_propaganda"]['monto'];
			   $monto_adicional        =  $ve2["shd300_detalles_propaganda"]['monto_adicional'];
			   $total_mensual          =  $ve2["shd300_detalles_propaganda"]['monto_mensual'];
			   $fecha_registro         =  $ve2["shd300_detalles_propaganda"]['fecha_registro'];
			   $ubicacion              =  $ve2["shd300_detalles_propaganda"]['ubicacion'];

			             if(!isset($_SESSION["pro"])){
							  $_SESSION["CUENTA"] = 1;
				              $cont               = 1;
				              $_SESSION["pro"]["contador_tipo_publicidad"][$cod_tipo] = 0;
						}else{
                                 $_SESSION["CUENTA"]++;
                              	 $cont = $_SESSION["CUENTA"];
						}//fin else

                           	  $_SESSION["pro"]["contador_tipo_publicidad"][$cod_tipo]=$num_tipo;
				              $_SESSION["pro"][$cont]["id"]                    = $cont;
				              $_SESSION["pro"][$cont]["condicion_actividad"]   = 1;
				              $_SESSION["pro"][$cont]["cod_tipo"]              = $cod_tipo;
				              $_SESSION["pro"][$cont]["num_tipo"]              = $num_tipo;
				              $_SESSION["pro"][$cont]["deno_tipo"]             = $deno_tipo;
				              $_SESSION["pro"][$cont]["monto_articulo"]        = $this->Formato2($monto_articulo);
				              $_SESSION["pro"][$cont]["espesor"]               = $this->Formato_3_out($espesor);
				              $_SESSION["pro"][$cont]["largo"]                 = $this->Formato_3_out($largo);
				              $_SESSION["pro"][$cont]["alto"]                  = $this->Formato_3_out($alto);
				              $_SESSION["pro"][$cont]["area"]                  = $this->Formato_3_out($aream2);
				              $_SESSION["pro"][$cont]["cantidad_area2"]        = $this->Formato_3_out($cnt_am2);
				              $_SESSION["pro"][$cont]["monto_mensual"]         = $this->Formato2($monto_mensual);
				              $_SESSION["pro"][$cont]["monto_adicional"]       = $this->Formato2($monto_adicional);
				              $_SESSION["pro"][$cont]["total_mensual"]         = $this->Formato2($total_mensual);
				              $_SESSION["pro"][$cont]["fecha_registro"]        = cambiar_formato_fecha($fecha_registro);
				              $_SESSION["pro"][$cont]["ubicacion"]             = $ubicacion;
       }
    }


    $this->set("accion", $_SESSION["pro"]);



}//fin class





function modificar($rif_cedula=null,$pagina=null){
	$this->layout = "ajax";
	$datos2=$this->v_shd300_propaganda->findAll($this->condicion()." and rif_cedula='".$rif_cedula."'",null,'rif_cedula ASC',null,null,null);
	$this->set('datos',$datos2);
    $this->set('pagina',$pagina);
    $rif_cedula2 	= $this->shd002_cobradores->generateList($this->condicion(), 'rif_ci ASC', null, '{n}.shd002_cobradores.rif_ci', '{n}.shd002_cobradores.nombre_razon');
	$this->concatena($rif_cedula2, 'rif_cedula');

	$this->Session->delete('pro');
 	$this->Session->delete('recargo');
 	$this->Session->delete('rif_cedula');

	$datos=$this->shd001_registro_contribuyentes->findAll("rif_cedula='".$rif_cedula."'");

	$cod_tipo = $this->shd300_tipo_propaganda->findAll($this->condicion(), null, 'cod_tipo ASC');
	if($cod_tipo!= null){
	$this->set("cod_tipo", $cod_tipo);
	}

	$cod_profesion=$datos[0]["shd001_registro_contribuyentes"]["profesion"];
	$cod_pais=$datos[0]["shd001_registro_contribuyentes"]["cod_pais"];
	$cod_estado=$datos[0]["shd001_registro_contribuyentes"]["cod_estado"];
	$cod_municipio=$datos[0]["shd001_registro_contribuyentes"]["cod_municipio"];
	$cod_parroquia=$datos[0]["shd001_registro_contribuyentes"]["cod_parroquia"];
	$cod_centro_poblado=$datos[0]["shd001_registro_contribuyentes"]["cod_centro_poblado"];
	$cod_calle_avenida=$datos[0]["shd001_registro_contribuyentes"]["cod_calle_avenida"];
	$cod_vereda_edificio=$datos[0]["shd001_registro_contribuyentes"]["cod_vereda_edificio"];
	$pais=$this->cugd01_republica->findAll('cod_republica='.$cod_pais);
	$estados=$this->cugd01_estados->findAll('cod_republica='.$cod_pais.' and cod_estado='.$cod_estado);
	$municipios=$this->cugd01_municipios->findAll('cod_republica='.$cod_pais.' and cod_estado='.$cod_estado.' and cod_municipio='.$cod_municipio);
	$parroquias=$this->cugd01_parroquias->findAll('cod_republica='.$cod_pais.' and cod_estado='.$cod_estado.' and cod_municipio='.$cod_municipio.' and cod_parroquia='.$cod_parroquia);
	$centros=$this->cugd01_centropoblados->findAll('cod_republica='.$cod_pais.' and cod_estado='.$cod_estado.' and cod_municipio='.$cod_municipio.' and cod_parroquia='.$cod_parroquia.' and cod_centro='.$cod_centro_poblado);
	$vialidad=$this->cugd01_vialidad->findAll('cod_republica='.$cod_pais.' and cod_estado='.$cod_estado.' and cod_municipio='.$cod_municipio.' and cod_parroquia='.$cod_parroquia.' and cod_centro='.$cod_centro_poblado.' and cod_vialidad='.$cod_calle_avenida);
	$vereda=$this->cugd01_vereda->findAll('cod_republica='.$cod_pais.' and cod_estado='.$cod_estado.' and cod_municipio='.$cod_municipio.' and cod_parroquia='.$cod_parroquia.' and cod_centro='.$cod_centro_poblado.' and cod_vialidad='.$cod_calle_avenida.' and cod_vereda='.$cod_vereda_edificio);
	$profesiones=$this->cnmd06_profesiones->findAll('cod_profesion='.$cod_profesion);
	$this->set('profesion',$profesiones);
	$this->set('pais',$pais);
	$this->set('estados',$estados);
	$this->set('municipios',$municipios);
	$this->set('parroquias',$parroquias);
	$this->set('centros',$centros);
	$this->set('vialidad',$vialidad);
	$this->set('vereda',$vereda);
	$this->Session->write('rif_tipo',   $rif_cedula);
	$this->Session->write('rif_cedula', $rif_cedula);


    $shd300_detalles_adicional  = $this->shd300_detalles_adicional->findAll($this->condicion()."  and rif_cedula='".$rif_cedula."'  ", null, "numero, cod_tipo, cod_recargo ASC");
    $shd300_detalles_propaganda = $this->shd300_detalles_propaganda->findAll($this->condicion()." and rif_cedula='".$rif_cedula."'  ", null, "numero, cod_tipo ASC");


    if(!isset($_SESSION["recargo"])){
       foreach($shd300_detalles_adicional as $ve1){
       	   $cod_tipo     = $ve1["shd300_detalles_adicional"]["cod_tipo"];
       	   $numero       = $ve1["shd300_detalles_adicional"]["numero"];
       	   $cod_recargo  = $ve1["shd300_detalles_adicional"]["cod_recargo"];
       	   $monto_re     = $ve1["shd300_detalles_adicional"]["monto"];
       	   $porcentaje   = $ve1["shd300_detalles_adicional"]["porcentaje_recargo"];
           if(!isset($_SESSION["recargo"][$cod_tipo][$numero])){
                  $_SESSION["recargo"][$cod_tipo][$numero] = $cod_recargo;
                  $_SESSION["recargo"][$cod_tipo][$numero."_".$cod_recargo]["monto_re"] = $monto_re;
                  $_SESSION["recargo"][$cod_tipo][$numero."_".$cod_recargo]["porcentaje"] = $porcentaje * 100;
           }else{
                  $_SESSION["recargo"][$cod_tipo][$numero] .= ",".$cod_recargo;
                  $_SESSION["recargo"][$cod_tipo][$numero."_".$cod_recargo]["monto_re"] = $monto_re;
                  $_SESSION["recargo"][$cod_tipo][$numero."_".$cod_recargo]["porcentaje"] = $porcentaje * 100;
           }
       }
    }

    if(!isset($_SESSION["pro"])){
        foreach($shd300_detalles_propaganda as $ve2){
        	   $b = $this->shd300_tipo_propaganda->findAll($this->condicion().' and cod_tipo='.$ve2["shd300_detalles_propaganda"]['cod_tipo']);

               $cod_tipo               =  $ve2["shd300_detalles_propaganda"]['cod_tipo'];
			   $monto_articulo         =  $b[0]['shd300_tipo_propaganda']['monto'];
			   $deno_tipo              =  $b[0]['shd300_tipo_propaganda']['denominacion'];
			   $num_tipo               =  $ve2["shd300_detalles_propaganda"]['numero'];
			   $espesor                =  $ve2["shd300_detalles_propaganda"]['espesor'];
			   $largo                  =  $ve2["shd300_detalles_propaganda"]['largo'];
			   $alto                   =  $ve2["shd300_detalles_propaganda"]['alto'];
			   $aream2                 =  $ve2["shd300_detalles_propaganda"]['area'];
			   $cnt_am2                =  $ve2["shd300_detalles_propaganda"]['cantidad'];
			   $monto_mensual          =  $ve2["shd300_detalles_propaganda"]['monto'];
			   $monto_adicional        =  $ve2["shd300_detalles_propaganda"]['monto_adicional'];
			   $total_mensual          =  $ve2["shd300_detalles_propaganda"]['monto_mensual'];
			   $fecha_registro         =  $ve2["shd300_detalles_propaganda"]['fecha_registro'];
			   $ubicacion              =  $ve2["shd300_detalles_propaganda"]['ubicacion'];

			             if(!isset($_SESSION["pro"])){
							  $_SESSION["CUENTA"] = 1;
				              $cont               = 1;
				              $_SESSION["pro"]["contador_tipo_publicidad"][$cod_tipo] = 0;
						}else{
                                 $_SESSION["CUENTA"]++;
                              	 $cont = $_SESSION["CUENTA"];
						}//fin else

                           	  $_SESSION["pro"]["contador_tipo_publicidad"][$cod_tipo]=$num_tipo;
				              $_SESSION["pro"][$cont]["id"]                    = $cont;
				              $_SESSION["pro"][$cont]["condicion_actividad"]   = 1;
				              $_SESSION["pro"][$cont]["cod_tipo"]              = $cod_tipo;
				              $_SESSION["pro"][$cont]["num_tipo"]              = $num_tipo;
				              $_SESSION["pro"][$cont]["deno_tipo"]             = $deno_tipo;
				              $_SESSION["pro"][$cont]["monto_articulo"]        = $this->Formato2($monto_articulo);
				              $_SESSION["pro"][$cont]["espesor"]               = $this->Formato_3_out($espesor);
				              $_SESSION["pro"][$cont]["largo"]                 = $this->Formato_3_out($largo);
				              $_SESSION["pro"][$cont]["alto"]                  = $this->Formato_3_out($alto);
				              $_SESSION["pro"][$cont]["area"]                  = $this->Formato_3_out($aream2);
				              $_SESSION["pro"][$cont]["cantidad_area2"]        = $this->Formato_3_out($cnt_am2);
				              $_SESSION["pro"][$cont]["monto_mensual"]         = $this->Formato2($monto_mensual);
				              $_SESSION["pro"][$cont]["monto_adicional"]       = $this->Formato2($monto_adicional);
				              $_SESSION["pro"][$cont]["total_mensual"]         = $this->Formato2($total_mensual);
				              $_SESSION["pro"][$cont]["fecha_registro"]        = cambiar_formato_fecha($fecha_registro);
				              $_SESSION["pro"][$cont]["ubicacion"]             = $ubicacion;
       }
    }


    $this->set("accion", $_SESSION["pro"]);

}//fin function





function guardar_modificar($rif_cedula=null){
	$this->layout = "ajax";

	$cod_presi 					= $this->Session->read('SScodpresi');
	$cod_entidad 				= $this->Session->read('SScodentidad');
	$cod_tipo_inst 				= $this->Session->read('SScodtipoinst');
	$cod_inst 					= $this->Session->read('SScodinst');
	$cod_dep 					= $this->Session->read('SScoddep');
  	$rif_cedula 				= $this->data['shp300_propaganda']['rif_constribuyente'];
  	$frecuencia_pago			= $this->data['shp300_propaganda']['frecuencia_pago'];
  	$pago_todo					= $this->data['shp300_propaganda']['pago_todo'];
  	$suspendido					= $this->data['shp300_propaganda']['suspendido'];
  	$rif_ci_cobrador			= $this->data['shp300_propaganda']['rif_ci_cobrador'];
  	$ultimo_ano_facturado		= '0';
  	$ultimo_mes_facturado		= '0';
	$monto_mensual_general		= $this->Formato1($this->data['shp300_propaganda']['monto_mensual_general']);


$this->shd300_detalles_propaganda->execute("BEGIN;");


$sw1 = $this->shd300_detalles_propaganda->execute("DELETE FROM shd300_detalles_adicional   WHERE ".$this->condicion()." and rif_cedula='".$rif_cedula."' ");
$sw1 = $this->shd300_detalles_propaganda->execute("DELETE FROM shd300_detalles_propaganda  WHERE ".$this->condicion()." and rif_cedula='".$rif_cedula."' ");
$sw1 = $this->shd300_detalles_propaganda->execute("DELETE FROM shd300_propaganda           WHERE ".$this->condicion()." and rif_cedula='".$rif_cedula."' ");

$SQL_INSERT ="INSERT INTO shd300_propaganda (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, rif_cedula, frecuencia_pago,
  monto_mensual_general, pago_todo, suspendido, rif_ci_cobrador, ultimo_ano_facturado, ultimo_mes_facturado)";
$SQL_INSERT .=" VALUES ($cod_presi, $cod_entidad, $cod_tipo_inst, $cod_inst, $cod_dep, '".$rif_cedula."', $frecuencia_pago,
  			$monto_mensual_general, $pago_todo, $suspendido, '".$rif_ci_cobrador."', $ultimo_ano_facturado, $ultimo_mes_facturado)";

$sw = $this->shd300_propaganda->execute($SQL_INSERT);

  if($sw>1){

           $cont  = $_SESSION["CUENTA"];

			 for($i=1; $i<=$cont; $i++){
			    if($_SESSION["pro"][$i]["condicion_actividad"]==1){
  					   	$cod_tipo        = $_SESSION["pro"][$i]["cod_tipo"];
  						$monto_articulo  = $this->Formato1($_SESSION["pro"][$i]["monto_articulo"]);
  						$numero          = $_SESSION["pro"][$i]["num_tipo"];
  						$monto_articulo                = $this->Formato1($_SESSION["pro"][$i]["monto_articulo"]);
  						$largo           = $this->Formato_3_in($_SESSION["pro"][$i]["largo"]);
						$alto            = $this->Formato_3_in($_SESSION["pro"][$i]["alto"]);
  						$area            = $this->Formato_3_in($_SESSION["pro"][$i]["area"]);
  						$espesor         = $this->Formato_3_in($_SESSION["pro"][$i]["espesor"]);
  						$cantidad        = $this->Formato_3_in($_SESSION["pro"][$i]["cantidad_area2"]);
  						$monto           = $this->Formato1($_SESSION["pro"][$i]["monto_mensual"]);
  						$monto_adicional = $this->Formato1($_SESSION["pro"][$i]["monto_adicional"]);
  						$total_mensual   = $this->Formato1($_SESSION["pro"][$i]["total_mensual"]);
  						$ubicacion       = $_SESSION["pro"][$i]["ubicacion"];
  						$fecha_registro  = $_SESSION["pro"][$i]["fecha_registro"];



					   $sql ="INSERT INTO shd300_detalles_propaganda ( cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, rif_cedula, cod_tipo,
  							numero, largo, alto, area, espesor, cantidad, monto, monto_adicional, monto_mensual, ubicacion, fecha_registro)";
					   $sql.="VALUES ( $cod_presi, $cod_entidad, $cod_tipo_inst, $cod_inst, $cod_dep, '".$rif_cedula."', $cod_tipo,
  							$numero, $largo, $alto, $area, $espesor, $cantidad, $monto, $monto_adicional, $total_mensual, '".$ubicacion."', '".$fecha_registro."');";
					   $sw1 = $this->shd300_detalles_propaganda->execute($sql);
					   if($sw1>1){
					   	     if(isset($_SESSION["recargo"][$cod_tipo][$numero])){
                                $recargo_aux = $_SESSION["recargo"][$cod_tipo][$numero];
                                $recargo_aux = split(",", $recargo_aux);
                                 for($a_recargo=0; $a_recargo<count($recargo_aux); $a_recargo++){
                                 	  if($recargo_aux[$a_recargo]!=""){
                                        $cod_recargo = $recargo_aux[$a_recargo];
//                                        $datos = $this->shd300_recargos->findAll($this->SQLCA()." and cod_recargo='".$cod_recargo."' ");
//                                        $porcentaje    = $datos[0]["shd300_recargos"]["porcentaje"]/100;
                                          $porcentaje    = $_SESSION["recargo"][$cod_tipo][$numero."_".$cod_recargo]["porcentaje"]/100;
                                        $monto_recargo = $monto * $porcentaje;
		 								$sql  = " INSERT INTO shd300_detalles_adicional (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, rif_cedula, cod_tipo, numero, cod_recargo, monto, porcentaje_recargo) ";
		                                $sql .= " VALUES ('".$cod_presi."', '".$cod_entidad."', '".$cod_tipo_inst."', '".$cod_inst."', '".$cod_dep."', '".$rif_cedula."', '".$cod_tipo."', '".$numero."', '".$cod_recargo."', '".$monto_recargo."', '".$porcentaje."'); ";
							   	        $sw1  = $this->shd300_detalles_propaganda->execute($sql);
							   	        if($sw1>1){}else{break;}
                                 	  }
                                 }
					   	      }
					   }else{break;}
			    }//fin if
			 }//fin for


			                 if($sw1>1){
			                 	  $this->set('Message_existe', 'LOS DATOS FUERON GUARDADOS');
			                 	  $this->shd300_detalles_propaganda->execute("COMMIT;");
			                 }else{
			                 	  $this->set('errorMessage', 'LOS DATOS NO FUERON GUARDADOS');
			                 	  $this->shd300_detalles_propaganda->execute("ROLLBACK;");
			                 }

  }else{
    $this->set('errorMessage', 'LOS DATOS NO FUERON GUARDADOS - POR FAVOR INTENTE DE NUEVO');
  }//fin else

    $this->consultar($rif_cedula);
    $this->render("consultar");
}//fin function




function eliminar($rif_cedula=null,$pagina=null){
 	$this->layout = "ajax";
    $sw1 = $this->shd300_detalles_propaganda->execute("DELETE FROM shd300_detalles_adicional   WHERE ".$this->condicion()." and rif_cedula='".$rif_cedula."' ");
    $sw1 = $this->shd300_detalles_propaganda->execute("DELETE FROM shd300_detalles_propaganda  WHERE ".$this->condicion()." and rif_cedula='".$rif_cedula."' ");
    $sw1 = $this->shd300_detalles_propaganda->execute("DELETE FROM shd300_propaganda           WHERE ".$this->condicion()." and rif_cedula='".$rif_cedula."' ");
  	$this->set('Message_existe', 'Registro Eliminado');
  	$this->index();
    $this->render("index");
}//fin function




function deno_tipo($codigo){
	$this->layout = "ajax";
	$b = $this->shd300_tipo_propaganda->findAll($this->condicion().' and cod_tipo='.$codigo);
	$this->set("b",$b[0]['shd300_tipo_propaganda']['denominacion']);

$cont = 0;

//pr($_SESSION["pro"]);
    if(!isset($_SESSION["pro"])){
		  $cont               = 1;
	}else{
          for($i=1; $i<=$_SESSION["CUENTA"]; $i++){
             if($_SESSION["pro"][$i]["cod_tipo"]==$codigo && $_SESSION["pro"][$i]["condicion_actividad"]==2){
                   if($_SESSION["pro"][$i]["num_tipo"]<=$_SESSION["pro"]["contador_tipo_publicidad"][$codigo]){
                   	 $cont = $_SESSION["pro"][$i]["num_tipo"];
                   	 break;
                   }
             }//fin if
          }//fin for
	}//fin else

	if($cont==0){
		if(isset($_SESSION["pro"]["contador_tipo_publicidad"][$codigo])){
			$cont = $_SESSION["pro"]["contador_tipo_publicidad"][$codigo] + 1;
		}else{
			$cont = 1;
		}

	}

	echo "<script>";
	    echo "document.getElementById('numero').value='".$this->AddCeroR($cont)."';   ";
	echo "</script>";
}//fin cpcp02_denominacion

function articulo($codigo){
	$this->layout = "ajax";
	$b = $this->shd300_tipo_propaganda->findAll($this->condicion().' and cod_tipo='.$codigo);
	$this->set("b",$b[0]['shd300_tipo_propaganda']['articulo']);
}//fin cpcp02_denominacion

function tipo_unidad($codigo){
	$this->layout = "ajax";
	$b = $this->shd300_tipo_propaganda->findAll($this->condicion().' and cod_tipo='.$codigo);
	$a=$b[0]['shd300_tipo_propaganda']['tipo_unidad'];
	if($a==1){
		$tipo='UNIDAD';
	}else if($a==2){
		$tipo='METROS';
	}
	$this->set("b",$tipo);
}//fin cpcp02_denominacion

function monto_articulo($codigo){
	$this->layout = "ajax";
	$b = $this->shd300_tipo_propaganda->findAll($this->condicion().' and cod_tipo='.$codigo);
	$this->set("b",$b[0]['shd300_tipo_propaganda']['monto']);
}//fin cpcp02_denominacion

function monto_cancelar($codigo){
	$this->layout = "ajax";
	$b = $this->shd300_tipo_propaganda->findAll($this->condicion().' and cod_tipo='.$codigo);
	$this->set("b",$b[0]['shd300_tipo_propaganda']['denominacion']);
}//fin cpcp02_denominacion

function deno_recargo($codigo){
	$this->layout = "ajax";
	$b = $this->shd300_recargos->findAll($this->condicion().' and cod_recargo='.$codigo);
	$this->set("b",$b[0]['shd300_recargos']['denominacion']);


}//fin cpcp02_denominacion

function porcentaje_recargo($codigo){
	$this->layout = "ajax";
	$b = $this->shd300_recargos->findAll($this->condicion().' and cod_recargo='.$codigo);
	$this->set("b",$b[0]['shd300_recargos']['porcentaje']);


}//fin cpcp02_denominacion

















function eliminar_grilla($var1=null){

$this->layout = "ajax";
$_SESSION["pro"][$var1]["condicion_actividad"] = 2;

$this->render("funcion");

}//fin function










function actualizar_grilla($cod_tipo=null){
	$this->layout = "ajax";
	if(isset($_SESSION["pro"])){
	$xx=$_SESSION["pro"];
	$this->set("xx",$_SESSION["pro"]);
	$this->set('cod',$cod_tipo);
	}else{
		$this->set("xx",null);
	}

}
}//fin class ?>