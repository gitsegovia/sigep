<?php

class Cspp01EjecutoresController extends AppController {

   var $name = "cspp01_ejecutores";
   var $uses = array('cspd01_ejecutores','v_cspd03_planteamientos','cspd03_planteamientos','cspd01_reconocimiento','cugd01_municipios','cugd90_municipio_defecto');
   var $helpers = array('Html','Ajax','Javascript','Sisap');

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
 }//fin function



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

		function SQLCA_insert($ano=null){//sql para busqueda de codigos de arranque con y sin año
				 $sql_re = $this->verifica_SS(1).",";
				 $sql_re .= $this->verifica_SS(2).",";
				 $sql_re .= $this->verifica_SS(3).",";
				 $sql_re .= $this->verifica_SS(4).",";
				 $sql_re .= $this->verifica_SS(5);

				 return $sql_re;
		}//fin funcion SQLCA

function index(){
	$this->layout  = "ajax";

      $this->set("modelo","cspd01_ejecutores");

    $rs=$this->cspd01_ejecutores->findAll($this->SQLCA(),null,'cedula_identidad ASC');
    $this->set("data",$rs);

}//index


function guardar () {
   $this->layout="ajax";
   $modelo_form="cspp01_ejecutores";
   if(isset($this->data[$modelo_form])){
      if(!empty($this->data[$modelo_form]["cedula"])  && !empty($this->data[$modelo_form]["nombre"])){
            $cod[0]=$this->data[$modelo_form]["cedula"];
			$cod[1]=$this->data[$modelo_form]["nombre"];
			$cod[2]=$this->data[$modelo_form]["cargo"];

	        if($this->cspd01_ejecutores->findCount($this->SQLCA()." and cedula_identidad=".$cod[0])==0){
	            $rs=$this->cspd01_ejecutores->execute("INSERT INTO cspd01_ejecutores VALUES (".$this->SQLCA_insert().",".$cod[0].",'".$cod[1]."','".$cod[2]."');");
                if($rs>1){
           	        $this->set("Message_existe","Los Datos Fuerón Guardados Exitosamente");
                }else{
           	        $this->set("errorMessage","Los Datos No Fuerón Guardados");
                }
	        }else{
	        	 $this->set("errorMessage","La Cédula ya se encuentra registrada");
	        }//coun
      $this->set("modelo","cspd01_ejecutores");

    $rs=$this->cspd01_ejecutores->findAll($this->SQLCA(),null,'cedula_identidad ASC');

    $this->set("data",$rs);
      }//fin if empty
   }//if isset
}//fin guardar



function eliminar_items ($cedula) {
	$this->layout = "ajax";

			if($this->v_cspd03_planteamientos->findCount($this->SQLCA()." and ejecutor_cedula=".$cedula)==0){

	    	$rs=$this->cspd01_ejecutores->execute("DELETE FROM cspd01_ejecutores WHERE ".$this->SQLCA()." and cedula_identidad=".$cedula);
    		if($rs>1){
           	        $this->set("Message_existe","El Dato Fu&eacute; Eliminado Exitosamente");
    		}else{
           	        $this->set("errorMessage","El Dato No Fu&eacute; Eliminado");

			}
		}else $this->set("errorMessage","El Dato No Fu&eacute; Eliminado, Se encuentra registrado en una solicitud");

}//fin eliminar_items

function editar ($cedula,$id_up,$id_fila) {
	$this->layout = "ajax";

    $rs=$this->cspd01_ejecutores->findAll($this->SQLCA()." and cedula_identidad=".$cedula);
    $this->set("cedula",$rs[0]["cspd01_ejecutores"]["cedula_identidad"]);
    $this->set("nombre",$rs[0]["cspd01_ejecutores"]["nombres_apellidos"]);
    $this->set("cargo",$rs[0]["cspd01_ejecutores"]["cargo"]);
    $this->set("i",$id_up);
    $this->set("id_fila",$id_fila);
}


function guardar_editar ($cedula,$id_up,$id_fila) {
   $this->layout="ajax";
   $modelo_form="cspp01_ejecutores";




             $xc=$this->cspd01_ejecutores->findCount($this->SQLCA()." and cedula_identidad=".$cedula);
	        if($xc!=0){
	            $rs=$this->cspd01_ejecutores->execute("UPDATE cspd01_ejecutores SET nombres_apellidos='".$this->data[$modelo_form]["nombre_edi"]."',cargo='".$this->data[$modelo_form]["cargo_edi"]."' WHERE ".$this->SQLCA()." and cedula_identidad=".$cedula);
                if($rs>1){
           	        $this->set("Message_existe","Los Datos Fueron actualizado Exitosamente");
                }else{
           	        $this->set("errorMessage","Los Datos No Fueron actualizado");
                }
	        }
	        $rando = rand();
    $rs=$this->cspd01_ejecutores->findAll($this->SQLCA()." and cedula_identidad=".$cedula);
    $this->set("cedula",$rs[0]["cspd01_ejecutores"]["cedula_identidad"]);
    $this->set("nombre",$rs[0]["cspd01_ejecutores"]["nombres_apellidos"]);
    $this->set("cargo",$rs[0]["cspd01_ejecutores"]["cargo"]);
    $this->set("i",$id_up);
    $this->set("id_fila",$id_fila);




}//fin guardar editar

function cancelar_editar ($cedula,$id_up,$id_fila) {
   $this->layout="ajax";

    $rs=$this->cspd01_ejecutores->findAll($this->SQLCA()." and cedula_identidad=".$cedula);
    $this->set("cedula",$rs[0]["cspd01_ejecutores"]["cedula_identidad"]);
    $this->set("nombre",$rs[0]["cspd01_ejecutores"]["nombres_apellidos"]);
    $this->set("cargo",$rs[0]["cspd01_ejecutores"]["cargo"]);
    $this->set("i",$id_up);
    $this->set("id_fila",$id_fila);

}//fin cancelar


function ejecucion ($var) {
   $this->layout="ajax";

   if($var!=0){
   		$datos=$this->v_cspd03_planteamientos->findAll($this->SQLCA().' and numero_solicitud='.$var);
		$this->set('datos',$datos);
		$lista=  $this->cspd01_ejecutores->generateList($this->SQLCA(), 'cedula_identidad ASC', null, '{n}.cspd01_ejecutores.cedula_identidad', '{n}.cspd01_ejecutores.nombres_apellidos');
		$this->concatena_sin_cero($lista,'ejecutores');


   }else {
   		$datos=$this->v_cspd03_planteamientos->findAll($this->SQLCA().' and numero_solicitud=0');
		$this->set('datos',$datos);
   }
   		$lista=$this->v_cspd03_planteamientos->findAll($this->SQLCA().' and reconocimiento_aprobacion=1 and evaluador_aprobacion=1 and ejecutor_aprobacion=0','numero_solicitud','numero_solicitud ASC');


  		 foreach($lista as $x){

  		 	$arreglo[]=mascara($x['v_cspd03_planteamientos']['numero_solicitud'],6);
   			$arreglo_id[]=mascara($x['v_cspd03_planteamientos']['numero_solicitud'],6);

 		 }

   		$arreglo1=array_combine($arreglo_id,$arreglo);

		$this->set('arreglo',$arreglo1);
		$this->set('var',$var);


}//fin evaluacion

function seleccion ($var) {
   $this->layout="ajax";

	$this->ejecucion($var);
	$this->render('ejecucion');


}//fin evaluacion

function nom_carg ($procede,$var) {
   $this->layout="ajax";

	if($procede==1){

		$this->set('nom_evaluador',$this->cspd01_ejecutores->field('nombres_apellidos', $this->SQLCA().' and cedula_identidad='.$var));

	}else{

	$this->set('cargo_evaluador',$this->cspd01_ejecutores->field('cargo', $this->SQLCA().' and cedula_identidad='.$var));

	}
$this->set('procede',$procede);
}//fin nom_carg

function guardar_ejecucion () {
   $this->layout="ajax";

 		$rs=$this->cspd03_planteamientos->execute("UPDATE cspd03_planteamientos SET ejecutor_cedula=".$this->data['cspp01_ejecutores']['cedula_ejecutor'].",ejecutor_aprobacion=".$this->data['cspp01_ejecutores']['radio2'].",ejecutor_observaciones='".$this->data['cspp03_planteamientos']['observ_ejecutor']."', ejecutor_costo=".$this->Formato1($this->data['cspp03_planteamientos']['monto'])." WHERE ".$this->SQLCA()." and numero_solicitud=".$this->data['cspp03_planteamientos']['numero']);
		if($rs>1){
           	        $this->set("Message_existe","Los Datos Fueron actualizado Exitosamente");
        }else{
           	        $this->set("errorMessage","Los Datos No Fueron actualizado");
        }

    $this->ejecucion(0);
	$this->render('ejecucion');

}//fin nom_carg

//BUSCAR DATOS

function buscar_datos(){
	$this->layout="ajax";
	$this->Session->delete('pista');

	echo "<script>$('campo_pista').focus();</script>";
}//fin function


function buscar_datos_porpista($var2=null, $var3=null){
	$this->layout="ajax";
	$modelo='v_cspd03_planteamientos';

    if($var3==null){ $var2 = strtoupper($var2);
					 $this->Session->write('pista', $var2);
					 $Tfilas=$this->$modelo->findCount($this->SQLCA()." and reconocimiento_aprobacion=1 and ((ejecutor_aprobacion=1) or (ejecutor_aprobacion=2)) and ((ano::text='$var2') or (numero_solicitud::text='$var2') or (rif_cedula LIKE '%$var2%') or (quitar_acentos(solicitante)  LIKE quitar_acentos('%$var2%')))");
					        if($Tfilas!=0){
					        	$pagina=1;
					        	$Tfilas=(int)ceil($Tfilas/50);
					        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
								$this->set('total_paginas',$Tfilas);
								$this->set('pagina_actual',$pagina);
								$this->set('ultimo',$Tfilas);
								$datos_filas=$this->$modelo->findAll($this->SQLCA()." and reconocimiento_aprobacion=1  and ((ejecutor_aprobacion=1) or (ejecutor_aprobacion=2)) and ((ano::text='$var2') or (numero_solicitud::text='$var2')  or (rif_cedula LIKE '%$var2%') or (quitar_acentos(solicitante) LIKE quitar_acentos('%$var2%')))","numero_solicitud, rif_cedula, solicitante, fecha_solicitud","numero_solicitud ASC",50,1,null);
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
						$Tfilas=$this->$modelo->findCount($this->SQLCA()." and reconocimiento_aprobacion=1 and ((ejecutor_aprobacion=1) or (ejecutor_aprobacion=2)) and ((ano::text='$var2') or (numero_solicitud::text='$var2') or (rif_cedula LIKE '%$var2%') or (quitar_acentos(solicitante) LIKE quitar_acentos('%$var2%')))");
						        	if($Tfilas!=0){
						        	$pagina=$var3;
						        	$Tfilas=(int)ceil($Tfilas/50);
						        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
									$this->set('total_paginas',$Tfilas);
									$this->set('pagina_actual',$pagina);
									$this->set('ultimo',$Tfilas);
									$datos_filas=$this->$modelo->findAll($this->SQLCA()." and reconocimiento_aprobacion=1 and ((ejecutor_aprobacion=1) or (ejecutor_aprobacion=2)) and ((ano::text='$var2') or (numero_solicitud::text='$var2')  or  (rif_cedula LIKE '%$var2%') or (quitar_acentos(solicitante) LIKE quitar_acentos('%$var2%')))","numero_solicitud, rif_cedula, solicitante, fecha_solicitud","numero_solicitud ASC",50,1,null);
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


function consultar ($var) {
	if($var!=0){
   $this->layout="ajax";

  		$datos=$this->v_cspd03_planteamientos->findAll($this->SQLCA().' and numero_solicitud='.$var);
		$this->set('datos',$datos);
	}elseif($var==0){
 		$this->layout="pdf";
 		$datos=$this->v_cspd03_planteamientos->findAll($this->SQLCA().' and numero_solicitud='.$this->data['cspp03_planteamientos']['numero']);
 		$this->set('datos',$datos);
 		$_SESSION['nombre_reconocimiento']=isset($datos[0]['v_cspd03_planteamientos']['nombres_apellidos_ejecutor']) ? $datos[0]['v_cspd03_planteamientos']['nombres_apellidos_ejecutor'] : '';
 		$_SESSION['cargo_reconocimiento']=isset($datos[0]['v_cspd03_planteamientos']['cargo_ejecutor']) ? $datos[0]['v_cspd03_planteamientos']['cargo_ejecutor'] : '';
		$rs=$this->cugd90_municipio_defecto->findAll($this->SQLCA());
		$this->set('conocido',$this->cugd01_municipios->field("conocido","cod_republica='".$rs[0]['cugd90_municipio_defecto']['cod_republica']."' and cod_estado='".$rs[0]['cugd90_municipio_defecto']['cod_estado']."' and cod_municipio='".$rs[0]['cugd90_municipio_defecto']['cod_municipio']."'"));
		if($datos[0]['v_cspd03_planteamientos']['ejecutor_aprobacion']==2){
			$this->set('ciudadano',$datos[0]['v_cspd03_planteamientos']['nombres_apellidos_reconocimiento']);
			$this->set('cargo',$datos[0]['v_cspd03_planteamientos']['cargo_reconocimiento']);
			$this->set('status','REPROBADO');
			$this->set('concatenacion','.');
		}elseif($datos[0]['v_cspd03_planteamientos']['ejecutor_aprobacion']==1){
			$x=$this->cspd01_reconocimiento->findAll(null,null,null,1);
			$this->set('ciudadano',$x[0]['cspd01_reconocimiento']['nombres_apellidos']);
			$this->set('cargo',$x[0]['cspd01_reconocimiento']['cargo']);
			$this->set('status','APROBADO');
			$this->set('concatenacion',', SIRVA LA PRESENTE PARA SU CONOCIMIENTO Y DEMAS FINES.');

		}
	}
		$this->set('var',$var);

}//fin consultar



}//fin clasee

?>