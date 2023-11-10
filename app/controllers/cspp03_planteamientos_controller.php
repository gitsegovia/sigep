<?php

 class Cspp03PlanteamientosController extends AppController {

   var $uses = array('ccfd04_cierre_mes','cspd02_datos_solicitante','v_cspd02_datos_solicitante','cspd03_planteamientos','v_cspd03_planteamientos','cspd01_area_principal','cspd01_area_derivada');
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



function index($var=null){///////////////<<--INDEX
	 $this->layout = "ajax";
}//fin index

function index2($var=null){///////////////<<--INDEX2
	 $this->layout = "ajax";

	 $this->set("ano",$this->ano_ejecucion());
	 $num= $this->cspd03_planteamientos->findAll($this->SQLCA(),null, 'numero_solicitud DESC',1);
     $this->set('num_sol',$num[0]['cspd03_planteamientos']['numero_solicitud']+1);

	$lista=  $this->cspd01_area_principal->generateList(null, 'cod_principal ASC', null, '{n}.cspd01_area_principal.cod_principal', '{n}.cspd01_area_principal.denominacion');
	$this->concatena($lista, 'principal');

}//fin index

function solicitante($var=null){///////////////<<--solicitante
	 $this->layout = "ajax";

	 if($this->v_cspd02_datos_solicitante->findCount($this->SQLCA()." and rif_cedula='$var'")!=0){

			$rs=$this->v_cspd02_datos_solicitante->findAll($this->SQLCA()." and rif_cedula='$var'");
		 	$this->set("nombre",$rs[0]['v_cspd02_datos_solicitante']['nombre_solicitante']);
			$this->set("error",false);


	 }else{

	 	$this->set("errorMessage","La Cédula del Solicitante No Se Encuentra Registrada");
		$this->set("error",true);
	 }


}//fin solicitante

function cargar_select($procede,$cod_principal,$var=null){///////////////<<--INDEX2
	 $this->layout = "ajax";

if($procede==1){

	$cond ="cod_principal=".$var;
	$this->set('procede',$procede);
	$rs=$this->cspd01_area_principal->findAll($cond);
	$this->set('datos',$rs);



}elseif($procede==2){


	$cond ="cod_principal=".$var;
	$this->set('procede',$procede);
	$rs=$this->cspd01_area_principal->findAll($cond);
	$this->set('datos',$rs);



}elseif($procede==3){
	$cond ="cod_principal=".$var;
	$lista=  $this->cspd01_area_derivada->generateList($cond, 'cod_derivada ASC', null, '{n}.cspd01_area_derivada.cod_derivada', '{n}.cspd01_area_derivada.denominacion');
	$this->concatena($lista, 'derivada');
	$this->set('cod_principal',$var);
	$this->set('procede',$procede);

}elseif($procede==4){
	$cond ="cod_principal=".$cod_principal." and cod_derivada=".$var;
	$rs=$this->cspd01_area_derivada->findAll($cond);
	$this->set('datos',$rs);
	$this->set('procede',$procede);


}elseif($procede==5){
	$cond ="cod_principal=".$cod_principal." and cod_derivada=".$var;
	$rs=$this->cspd01_area_derivada->findAll($cond);
	$this->set('datos',$rs);
	$this->set('procede',$procede);


}


}//fin cargar_select

function guardar(){///////////////<<--GUARDAR
	 $this->layout = "ajax";


	 //$num= $this->cspd03_planteamientos->findAll(null,null, 'numero_solicitud DESC',1);

    if($this->cspd03_planteamientos->findCount($this->SQLCA()." and numero_solicitud=".$this->data['cspp03_planteamientos']['numero'])==0){

	$fecha=$this->data['cspp03_planteamientos']['fecha'];

    $sql="INSERT INTO cspd03_planteamientos";
    $sql.=" VALUES (".$this->verifica_SS(1).",".$this->verifica_SS(2).",".$this->verifica_SS(3).",".$this->verifica_SS(4).",".$this->verifica_SS(5).",".$this->data['cspp03_planteamientos']['ano'].",".$this->data['cspp03_planteamientos']['numero'].",'";
	$sql.= $this->Cfecha($fecha, 'A-M-D')."','".$this->data['cspp03_planteamientos']['cedula']."',".$this->data['cspp03_planteamientos']['cod_principal'].",".$this->data['cspp03_planteamientos']['cod_derivada'].",'".$this->data['cspp03_planteamientos']['solicitud']."')";
	$rs=$this->cspd03_planteamientos->execute($sql);

		if($rs>1){
           	        $this->set("Message_existe","Los Datos Fuerón Guardados Exitosamente");
           	        $this->set("bien",true);
    			}else{
           	        $this->set("errorMessage","Los Datos No Fuerón Guardados");
           	        $this->set("bien",false);
                }
     }
}//FIN GUARDAR



function consultar($numero=null,$pagina=null) {
	$this->layout="ajax";

		$Tfilas=$this->v_cspd03_planteamientos->findCount($this->SQLCA()." and numero_solicitud=$numero");

        if($Tfilas!=0){
        	$datos=$this->v_cspd03_planteamientos->findAll($this->SQLCA()." and numero_solicitud=$numero",null,"numero_solicitud ASC",1,$pagina,null);

            $this->set('datos',$datos);
          	$this->set('siguiente',$pagina+1);
          	$this->set('anterior',$pagina-1);
            $this->bt_nav($Tfilas,$pagina);
            $this->set('numT',$Tfilas);
			$this->set('numP',$pagina);
			$this->set('pagina',$pagina);
			$this->set('Tfilas',$Tfilas);

			$this->set("ano",$datos[0]['v_cspd03_planteamientos']['ano']);
	 		$this->set('num_sol',$datos[0]['v_cspd03_planteamientos']['numero_solicitud']);
	 		$this->set('fecha_doc',$datos[0]['v_cspd03_planteamientos']['fecha_solicitud']);

	 		$this->set('cedula_sol',$datos[0]['v_cspd03_planteamientos']['rif_cedula']);
			$nombre= $this->v_cspd02_datos_solicitante->findAll($this->SQLCA()." and rif_cedula='".$datos[0]['v_cspd03_planteamientos']['rif_cedula']."'",'nombre_solicitante', null,1);
	 		$this->set('nombre',$nombre[0]['v_cspd02_datos_solicitante']['nombre_solicitante']);

			$lista=  $this->cspd01_area_principal->generateList(null, 'cod_principal ASC', null, '{n}.cspd01_area_principal.cod_principal', '{n}.cspd01_area_principal.denominacion');
			$this->concatena($lista, 'principal');


			$lista=  $this->cspd01_area_derivada->generateList('cod_principal='.$datos[0]['v_cspd03_planteamientos']['cod_principal'], 'cod_derivada ASC', null, '{n}.cspd01_area_derivada.cod_derivada', '{n}.cspd01_area_derivada.denominacion');
			$this->concatena($lista, 'derivada');


	        }else{
	 	    $this->set('errorMessage', 'No se encontrar&oacute;n datos');
	 	    $this->set('noExiste',true);
	 	    $this->index();
			$this->render("index");
		    return;
        }

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


function editar($numero_solicitud){
	$this->layout="ajax";




}

function guardar_editar($numero_solicitud){
	$this->layout="ajax";
echo $numero_solicitud;
	if($this->v_cspd03_planteamientos->findCount($this->SQLCA()."and numero_solicitud=$numero_solicitud")!=0){


	$rs=$this->v_cspd03_planteamientos->execute("UPDATE cspd03_planteamientos SET solicitud_planteamiento='".$this->data['cspp03_planteamientos']['solicitud']."',cod_principal=".$this->data['cspp03_planteamientos']['cod_principal'].",cod_derivada=". $this->data['cspp03_planteamientos']['cod_derivada']
														." WHERE ".$this->SQLCA()." and numero_solicitud=$numero_solicitud");

                if($rs>1){
           	        $this->set("Message_existe","Los Datos Fueron actualizado Exitosamente");
                }else{
           	        $this->set("errorMessage","Los Datos No Fueron actualizado");

                }


	}else{
		  $this->set("errorMessage","Los Datos No Fueron actualizado, esta solicitud fue eliminada");
		  $this->set('no_exite',true);

	 	  return;
	}


}//fin guardar editar


function eliminar($numero_solicitud){
	$this->layout="ajax";

if($this->cspd03_planteamientos->findCount($this->SQLCA()."and numero_solicitud=$numero_solicitud and evaluador_aprobacion=0")!=0){

$rs=$this->v_cspd03_planteamientos->execute("DELETE FROM cspd03_planteamientos WHERE ". $this->SQLCA()."and numero_solicitud=$numero_solicitud and evaluador_aprobacion=0");
    		if($rs>1){
           	        $this->set("Message_existe","El Dato Fu&eacute; Eliminado Exitosamente");
           	        $this->set('bien',true);
    		}else{
           	        $this->set("errorMessage","El Dato No Fu&eacute; Eliminado");

			}
		}else $this->set("errorMessage","El Dato No Fu&eacute; Eliminado, esta aprobada su evaluación");


}//eliminar


//BUSCAR DATOS

function buscar_datos(){
	$this->layout="ajax";
	$this->Session->delete('pista');

	echo "<script>$('campo_pista').focus();</script>";
}//fin function


function buscar_datos_porpista($var2=null, $var3=null){
	$this->layout="ajax";
	$modelo='cspd02_datos_solicitante';
    if($var3==null){ $var2 = strtoupper($var2);
					 $this->Session->write('pista', $var2);
					 $Tfilas=$this->$modelo->findCount($this->SQLCA()." and ((rif_cedula LIKE '%$var2%') or (quitar_acentos(nombre_solicitante) LIKE quitar_acentos('%$var2%')) )");
					        if($Tfilas!=0){
					        	$pagina=1;
					        	$Tfilas=(int)ceil($Tfilas/50);
					        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
								$this->set('total_paginas',$Tfilas);
								$this->set('pagina_actual',$pagina);
								$this->set('ultimo',$Tfilas);
								$datos_filas=$this->$modelo->findAll($this->SQLCA()." and ((rif_cedula LIKE '%$var2%') or (quitar_acentos(nombre_solicitante) LIKE quitar_acentos('%$var2%')) )","rif_cedula, nombre_solicitante, telefonos","rif_cedula ASC",50,1,null);
						        $this->set("datosFILAS",$datos_filas);
						        $this->set('siguiente',$pagina+1);
								$this->set('anterior',$pagina-1);
								$this->bt_nav($Tfilas,$pagina);
					          }else{
					        	$this->set("datosFILAS",'');
								$this->set('total_paginas','');
								$this->set('pagina_actual','');
							    $this->set('siguiente','');
								$this->set('anterior','');
								$this->set('ultimo','');
					          }
            }else{
						$var22 = $this->Session->read('pista');
						$var22 = strtoupper($var22);
						$Tfilas=$this->$modelo->findCount($this->SQLCA()." and ((rif_cedula LIKE '%$var2%') or (quitar_acentos(nombre_solicitante) LIKE quitar_acentos('%$var2%')) )");
						        if($Tfilas!=0){
						        	$pagina=$var3;
						        	$Tfilas=(int)ceil($Tfilas/50);
						        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
									$this->set('total_paginas',$Tfilas);
									$this->set('pagina_actual',$pagina);
									$this->set('ultimo',$Tfilas);
									$datos_filas=$this->$modelo->findAll($this->SQLCA()." and ((rif_cedula LIKE '%$var2%') or (quitar_acentos(nombre_solicitante) LIKE quitar_acentos('%$var2%')) )","rif_cedula, nombre_solicitante, telefonos","rif_cedula ASC",50,1,null);
							        $this->set("datosFILAS",$datos_filas);
							        $this->set('siguiente',$pagina+1);
									$this->set('anterior',$pagina-1);
									$this->bt_nav($Tfilas,$pagina);
						          }else{
						        	$this->set("datosFILAS",'');
									$this->set('total_paginas','');
									$this->set('pagina_actual','');
							    	$this->set('siguiente','');
									$this->set('anterior','');
									$this->set('ultimo','');
						          }
   		}//fin else

} //fin funcion

function cargar_solicitante($cedula=null,$nombre=null){
	$this->layout="ajax";
$this->set('cedula',$cedula);
$this->set('nombre',$nombre);
}


//BUSCAR DATOS 1

function buscar_datos1(){
	$this->layout="ajax";
	$this->Session->delete('pista');

	echo "<script>$('campo_pista').focus();</script>";
}//fin function


function buscar_datos_porpista1($var2=null, $var3=null,$pagina=null){
	$this->layout="ajax";
	$modelo='v_cspd03_planteamientos';

    if($var3==null){ $var2 = strtoupper($var2);
					 $this->Session->write('pista', $var2);
					 $Tfilas=$this->$modelo->findCount($this->SQLCA()." and ((ano::text='$var2') or (numero_solicitud::text='$var2') or (rif_cedula LIKE '%$var2%') or (quitar_acentos(solicitante) LIKE quitar_acentos('%$var2%')) )");
					        if($Tfilas!=0){
					        	$pagina=1;
					        	$Tfilas=(int)ceil($Tfilas/50);
					        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
								$this->set('total_paginas',$Tfilas);
								$this->set('pagina_actual',$pagina);
								$this->set('ultimo',$Tfilas);
								$datos_filas=$this->$modelo->findAll($this->SQLCA()." and ((ano::text='$var2') or (numero_solicitud::text='$var2')  or (rif_cedula LIKE '%$var2%') or (quitar_acentos(solicitante) LIKE quitar_acentos('%$var2%')) )","numero_solicitud, rif_cedula, solicitante, fecha_solicitud","numero_solicitud ASC",50,1,null);
						        $this->set("datosFILAS",$datos_filas);
						        $this->set('siguiente',$pagina+1);
								$this->set('anterior',$pagina-1);
								$this->bt_nav($Tfilas,$pagina);
					          }else{
					        	$this->set("datosFILAS",'');
								$this->set('total_paginas','');
								$this->set('pagina_actual','');
							    $this->set('siguiente','');
								$this->set('anterior','');
								$this->set('ultimo','');
					          }
            }else{
						$var22 = $this->Session->read('pista');
						$var22 = strtoupper($var22);
						$Tfilas=$this->$modelo->findCount($this->SQLCA()." and ((ano::text='$var2') or (numero_solicitud::text='$var2') or (rif_cedula LIKE '%$var2%') or (quitar_acentos(solicitante) LIKE quitar_acentos('%$var2%')) )");
						        	if($Tfilas!=0){
						        	$pagina=$var3;
						        	$Tfilas=(int)ceil($Tfilas/50);
						        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
									$this->set('total_paginas',$Tfilas);
									$this->set('pagina_actual',$pagina);
									$this->set('ultimo',$Tfilas);
									$datos_filas=$this->$modelo->findAll($this->SQLCA()." and ((ano::text='$var2') or (numero_solicitud::text='$var2')  or  (rif_cedula LIKE '%$var2%') or (quitar_acentos(solicitante) LIKE quitar_acentos('%$var2%')) )","numero_solicitud, rif_cedula, solicitante, fecha_solicitud","numero_solicitud ASC",50,1,null);
							        $this->set("datosFILAS",$datos_filas);
							        $this->set('siguiente',$pagina+1);
									$this->set('anterior',$pagina-1);
									$this->bt_nav($Tfilas,$pagina);
						          }else{
						        	$this->set("datosFILAS",'');
									$this->set('total_paginas','');
									$this->set('pagina_actual','');
							    	$this->set('siguiente','');
									$this->set('anterior','');
									$this->set('ultimo','');
						          }

}//fin else

} //fin funcion

}//fin clase
?>
