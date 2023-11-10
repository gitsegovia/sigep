<?php
 class Shp002CobradoresReasig5Controller extends AppController{
	var $uses = array('v_shd500_aseo_domiciliario','v_shd400_propiedad','v_shd200_vehiculos','shd600_aprobacion_arrendamiento','shd500_aseo_domiciliario','shd600_solicitud_arrendamiento','shd700_credito_vivienda','shd300_propaganda','shd100_patente','v_shd100_patente','shd001_registro_contribuyentes','shd600_aprobacion_arrendamiento','shd400_propiedad','shd300_propaganda','shd003_codigo_ingresos','shd002_cobradores','shd100_patente','shd200_vehiculos','cnmd06_profesiones','cugd01_vereda','cugd01_vialidad','v_shd001_registro_contribuyentes','shd200_vehiculos_usos', 'ccfd04_cierre_mes','cugd01_republica','cugd01_estados','cugd01_municipios','cugd01_parroquias','cugd01_centropoblados');
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
	$this->concatena($this->shd002_cobradores->generateList($this->SQLCA(), "rif_ci ASC", null, '{n}.shd002_cobradores.rif_ci', '{n}.shd002_cobradores.nombre_razon'), "lista_cobrador");
}//fin index

function codigo_rif($codigo){
	$this->layout = "ajax";
    $this->set("a",$codigo);
}//fin cpcp02_codigo

function denominacion_rif($codigo){
	$this->layout = "ajax";
	$b = $this->shd002_cobradores->findAll($this->SQLCA()." and rif_ci='".$codigo."'",array('rif_ci','nombre_razon'));
	$this->set("b",$b[0]['shd002_cobradores']['nombre_razon']);
}//fin cpcp02_denominacion

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
					$Tfilas=$this->v_shd001_registro_contribuyentes->findCount("((rif_cedula LIKE '%$var2%') or (quitar_acentos(razon_social_nombres) LIKE '%$var2%'))");
					        if($Tfilas!=0){
					        	$pagina=1;
					        	$Tfilas=(int)ceil($Tfilas/50);
					        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
								$this->set('total_paginas',$Tfilas);
								$this->set('pagina_actual',$pagina);
								$this->set('ultimo',$Tfilas);
					     	    $datos_filas=$this->v_shd001_registro_contribuyentes->findAll("((rif_cedula LIKE '%$var2%') or (quitar_acentos(razon_social_nombres) LIKE '%$var2%'))   ",null,"rif_cedula ASC",50,1,null);
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
						$Tfilas=$this->v_shd001_registro_contribuyentes->findCount("((rif_cedula LIKE '%$var22%') or (quitar_acentos(razon_social_nombres) LIKE '%$var22%'))");
						        if($Tfilas!=0){
						        	$pagina=$var3;
						        	$Tfilas=(int)ceil($Tfilas/50);
						        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
									$this->set('total_paginas',$Tfilas);
									$this->set('pagina_actual',$pagina);
									$this->set('ultimo',$Tfilas);
						     	    $datos_filas=$this->v_shd001_registro_contribuyentes->findAll("((rif_cedula LIKE '%$var22%') or (quitar_acentos(razon_social_nombres) LIKE '%$var22%'))  ",null,"rif_cedula ASC",50,$pagina,null);
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
	$sacar=$this->v_shd500_aseo_domiciliario->findAll($this->SQLCA()." and rif_cedula='".$var1."'");
	$this->set('profesion',$profesiones);
	$this->set('pais',$pais);
	$this->set('estados',$estados);
	$this->set('municipios',$municipios);
	$this->set('parroquias',$parroquias);
	$this->set('centros',$centros);
	$this->set('vialidad',$vialidad);
	$this->set('vereda',$vereda);
	$this->set('datos',$datos);



$this->set('datos',$datos);
$resul = javascript_encode($datos[0]['shd001_registro_contribuyentes']['razon_social_nombres'], 1);
echo'<script>';
  echo"document.getElementById('deno_rif').value = \"$resul\"; ";
  echo "document.getElementById('rif_constribuyente').value='".$datos[0]['shd001_registro_contribuyentes']['rif_cedula']."';   ";
 echo "document.getElementById('cobrador_actual').value='".$sacar[0]['v_shd500_aseo_domiciliario']['rif_ci_cobrador']."';   ";
echo "document.getElementById('deno_cobrador_actual').value='".$sacar[0]['v_shd500_aseo_domiciliario']['deno_cobrador']."';	 ";
echo'</script>';
					/*echo "<script>";
					    echo "document.getElementById('deno_rif').value='".$datos[0]['shd001_registro_contribuyentes']['razon_social_nombres']."';   ";
					    echo "document.getElementById('rif_constribuyente').value='".$datos[0]['shd001_registro_contribuyentes']['rif_cedula']."';   ";
						echo "document.getElementById('cobrador_actual').value='".$sacar[0]['v_shd500_aseo_domiciliario']['rif_ci_cobrador']."';   ";
					    echo "document.getElementById('deno_cobrador_actual').value='".$sacar[0]['v_shd500_aseo_domiciliario']['deno_cobrador']."';	 ";
					echo "</script>";*/
}else{
	$vacio='';
					echo "<script>";
						echo "document.getElementById('deno_rif').value='".$vacio."';   ";
						echo "document.getElementById('rif_constribuyente').value='".$vacio."';   ";
						echo "document.getElementById('cobrador_actual').value='".$vacio."';   ";
						echo "document.getElementById('deno_cobrador_actual').value='".$vacio."';   ";
					echo "</script>";
}
}

function guardar(){
	$this->layout="ajax";
	$rif_contribuyente	 = $this->data['shp002_cobradores_reasig5']['rif_constribuyente'];
	$cobrador_actual	 = $this->data['shp002_cobradores_reasig5']['cobrador_actual'];
	$cobrador_cambio	 = $this->data['shp002_cobradores_reasig5']['cobrador_cambio'];

	//$update1 = 	"update shd200_vehiculos set rif_ci_cobrador='".$cobrador_cambio."' where rif_cedula='".$rif_contribuyente."' and rif_ci_cobrador='".$cobrador_actual."' and ".$this->SQLCA();
	//$update2 = 	"update shd200_vehiculos set rif_ci_cobrador='".$cobrador_cambio."' where rif_cedula='".$rif_contribuyente."' and rif_ci_cobrador='".$cobrador_actual."' and ".$this->SQLCA();
	//$update3 =	"update shd300_propaganda set rif_ci_cobrador='".$cobrador_cambio."' where rif_cedula='".$rif_contribuyente."' and rif_ci_cobrador='".$cobrador_actual."' and ".$this->SQLCA();
	//$update4 =	"update shd400_propiedad set rif_ci_cobrador='".$cobrador_cambio."' where rif_cedula='".$rif_contribuyente."' and rif_ci_cobrador='".$cobrador_actual."' and ".$this->SQLCA();
	$update5 =	"update shd500_aseo_domiciliario set rif_ci_cobrador='".$cobrador_cambio."' where rif_cedula='".$rif_contribuyente."' and rif_ci_cobrador='".$cobrador_actual."' and ".$this->SQLCA();
	//$update6 =	"update shd600_aprobacion_arrendamiento set rif_ci_cobrador='".$cobrador_cambio."' where numero_solicitud='".$numero_solicitud2."' and rif_ci_cobrador='".$cobrador_actual."' and ".$this->SQLCA();
	//$update7 =	"update shd700_credito_vivienda set rif_ci_cobrador='".$cobrador_cambio."' where rif_cedula='".$rif_contribuyente."' and rif_ci_cobrador='".$cobrador_actual."' and ".$this->SQLCA();

	//$this->shd100_patente->execute($update1);
	//$this->shd200_vehiculos->execute($update2);
	//$this->shd300_propaganda->execute($update3);
	//$this->shd400_propiedad->execute($update4);
	$this->shd500_aseo_domiciliario->execute($update5);
	//$this->shd600_aprobacion_arrendamiento->execute($update6);
	//$this->shd700_credito_vivienda->execute($update7);
	$this->set('Message_existe', 'Cambio realizado con exito.');
$this->index();
$this->render('index');
}


}
//fin class
?>