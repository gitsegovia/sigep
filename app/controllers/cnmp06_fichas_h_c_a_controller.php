<?php


class Cnmp06FichasHCAController extends AppController{
	var $uses = array('cnmd06_fichas_h_c_a', 'v_cnmd06_fichas_h_c_a', 'v_cnmd06_fichas_h_c_a2', 'ccfd04_cierre_mes','cnmd06_datos_personales','cugd05_restriccion_clave', 'Cnmd01', 'cstd01_entidades_bancarias',
	                  'v_cnmd06_nombres', 'v_cnmd06', 'v_cnmd06_fichas', 'cnmd06_fichas', 'cnmd06_fichas', 'cnmd07_transacciones_actuales','cnmd08_historia_nomina', 'cnmd08_historia_trabajador', 'cnmd08_historia_transacciones','cnmd08_historia_nomina_fideicomiso', 'cnmd08_historia_trabajador_fideicomiso', 'cnmd08_historia_transacciones_fideicomiso');
 	var $helpers = array('Html','Ajax','Javascript', 'Sisap');
 	var $name = "cnmp06_fichas_h_c_a";

//cnmp06_religiones2

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





 function index($var=null){
 	$this->layout ="ajax";
	$this->data=null;
 	//$Lista = $this->v_cnmd06->generateList($this->SQLCA(), 'cod_tipo_nomina ASC', null, '{n}.v_cnmd06.cod_tipo_nomina', '{n}.v_cnmd06.tipo_nomina');
   	//$this->concatena($Lista, 'cod_tipo_nomina');



   	$lista = $this->Cnmd01->generateList($conditions = $this->condicion(), $order = 'cod_tipo_nomina', $limit = null, '{n}.Cnmd01.cod_tipo_nomina', '{n}.Cnmd01.denominacion');
	if($lista !=null){
		$this->concatena($lista, 'cod_tipo_nomina');
	}else{
		$this->set('cod_tipo_nomina','');
	}
   	$Listaentidad = $this->cstd01_entidades_bancarias->generateList(null, 'cod_entidad_bancaria ASC', null, '{n}.cstd01_entidades_bancarias.cod_entidad_bancaria', '{n}.cstd01_entidades_bancarias.denominacion');
   	$this->set('cod_entidad_bancaria', $Listaentidad);
	$persona = $this->v_cnmd06_nombres->generateList(null, null, null, '{n}.v_cnmd06_nombres.cedula_identidad', '{n}.v_cnmd06_nombres.nombre_completo');
	$this->concatena($persona, 'persona');
	$this->set("forma",array(1=>"Efectivo",2=>"Recibo",3=>"Deposito Bancario",4=>"Cheque"));
	$condicion = array(1=>"Activo",2=>"Permiso no Remunerado",3=>"Comisión de Servicio",4=>"Vacaciones",5=>"Suspendido",6=>"Retirado",7=>"Ascenso", 8=>"Reposo");
	$this->concatena($condicion, "condicion");
	$this->set("motivo",array(1=>"Despido Justificado",2=>"Despido Injustificado",3=>"Retiro Justificado",4=>"Renuncia",5=>"Culminacion de Contrato",6=>"Remoción del Cargo",7=>"Baja por Propia Solicitud",8=>"Baja por Expulsión",9=>"Reducción de Personal ",10=>"Jubilado",11=>"Pensionado",12=>"Fallecimiento"));


    $this->Session->delete('tipo_nomina_cod_cargo');

    if($var!=null){
    	            $ss=$this->cnmd06_fichas->findAll($this->SQLCA()." and cod_tipo_nomina=".$var,'cod_ficha','cod_ficha DESC',1);
			 	 if($ss==null){
			     	$new_numero=1;
			     }else{
			     	$new_numero=$ss[0]["cnmd06_fichas"]["cod_ficha"]+1;
			     }//fin else

                 $b = $this->Cnmd01->findAll($this->SQLCA()." and cod_tipo_nomina=".$var,array('cod_tipo_nomina','denominacion'));

                 $this->set("disabled_radio","");
			     $this->set("cod_ficha","");
			     $this->set("radio", 1);
			     $this->set("codigo_tipo_nomina", mascara2($var));
	             $this->set("denominacion_nomina",$b[0]['Cnmd01']['denominacion']);

    }else{

    	          $this->Session->delete('tipo_nomina');

    	          $this->set("disabled_radio","disabled");
    	          $this->set("cod_ficha","");
			      $this->set("radio", "");
                  $this->set("codigo_tipo_nomina", "");
                  $this->set("denominacion_nomina","");

    }//fin else


 }//fin if





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







function buscar_cargo_input($var1=null){



$this->layout="ajax";
$tipo_nomina=   $this->Session->read('tipo_nomina');
                    $cod_presi      =  $this->Session->read('SScodpresi');
					$cod_entidad    =  $this->Session->read('SScodentidad');
					$cod_tipo_inst  =  $this->Session->read('SScodtipoinst');
					$cod_inst       =  $this->Session->read('SScodinst');
					$cod_dep        =  $this->Session->read('SScoddep');


$tipo_nomina=   $this->Session->read('tipo_nomina');
$resultado=$this->v_cnmd06->findAll($this->SQLCA().' and cod_tipo_nomina='.$tipo_nomina.' and cod_cargo='.$var1);

if($resultado[0]['v_cnmd06']["cod_ficha"]=="0"){ $resultado[0]['v_cnmd06']["cod_ficha"] = "";}

			if($resultado[0]['v_cnmd06']['condicion_actividad']==2 && $resultado[0]['v_cnmd06']['cod_ficha']!=""){



					    $lista = $this->Cnmd01->generateList($conditions = $this->condicion(), $order = 'cod_tipo_nomina', $limit = null, '{n}.Cnmd01.cod_tipo_nomina', '{n}.Cnmd01.denominacion');
						$this->concatena($lista, 'cod_tipo_nomina');

					   	$Listaentidad = $this->cstd01_entidades_bancarias->generateList(null, 'cod_entidad_bancaria ASC', null, '{n}.cstd01_entidades_bancarias.cod_entidad_bancaria', '{n}.cstd01_entidades_bancarias.denominacion');
					   	$this->concatena($Listaentidad, 'cod_entidad_bancaria');

						$persona = $this->v_cnmd06_nombres->generateList(null, null, null, '{n}.v_cnmd06_nombres.cedula_identidad', '{n}.v_cnmd06_nombres.nombre_completo');
						$this->concatena($persona, 'persona');

						$this->set("forma",array(1=>"Efectivo",2=>"Recibo",3=>"Deposito Bancario",4=>"Cheque"));
						$condicion = array(1=>"Activo",2=>"Permiso no Remunerado",3=>"Comisión de Servicio",4=>"Vacaciones",5=>"Suspendido",6=>"Retirado",7=>"Ascenso", 8=>"Reposo");
						$this->concatena($condicion, "condicion");
						$this->set("motivo",array(1=>"Despido Justificado",2=>"Despido Injustificado",3=>"Retiro Justificado",4=>"Renuncia",5=>"Culminacion de Contrato",6=>"Remoción del Cargo",7=>"Baja por Propia Solicitud",8=>"Baja por Expulsión",9=>"Reducción de Personal ",10=>"Jubilado",11=>"Pensionado",12=>"Fallecimiento"));

						$condi = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep;
					    $sql2  =  $condi." and cod_tipo_nomina='".$tipo_nomina."' and  cod_cargo='".$var1."'  and cod_ficha='".$resultado[0]['v_cnmd06']['cod_ficha']."'  ";
                        $datos=$this->v_cnmd06_fichas->findAll($sql2,null,'cod_ficha ASC',null);

                         $pagina=1;
			          	  $Tfilas=$this->v_cnmd06_fichas->findCount($sql2);
			          	 if($Tfilas!=0){
				          	 $this->set('pag_cant',$pagina.'/'.$Tfilas);
				          	 $this->set('siguiente',$pagina+1);
				          	 $this->set('anterior',$pagina-1);
				             $this->bt_nav($Tfilas,$pagina);
			             }//fin if

                        $sql   =  $condi." and cod_tipo_nomina='".$tipo_nomina."' and  cod_cargo='".$var1."'  and cod_ficha='".$resultado[0]['v_cnmd06']['cod_ficha']."' and cedula_identidad='".$datos[0]['v_cnmd06_fichas']['cedula_identidad']."'   ";
				        $datos=$this->v_cnmd06_fichas->findAll($sql);
				        $this->set('datos',$datos);

				        echo "<script>";
								    echo "document.getElementById('capa_aux_principal').innerHTML='';   ";
						echo "</script>";

						       $puesto = $resultado[0]['v_cnmd06']['cod_puesto'];
								$datos_air = $this->v_cnmd06_fichas->execute("
										select devolver_grado_puesto(
										               (select xy.clasificacion_personal from cnmd01 xy where
										                  xy.cod_presi           =     '".$cod_presi."'         and
														  xy.cod_entidad         =     '".$cod_entidad."'       and
														  xy.cod_tipo_inst       =     '".$cod_tipo_inst."'         and
														  xy.cod_inst            =     '".$cod_inst."'         and
														  xy.cod_dep             =     '".$cod_dep."'         and
														  xy.cod_tipo_nomina     =     '".$tipo_nomina."'
										               ), '".$puesto."' ); ");

										           $this->set('grado_input',$datos_air[0][0]["devolver_grado_puesto"]);




               $this->render("consulta");


			}else{


				                $puesto = $resultado[0]['v_cnmd06']['cod_puesto'];
								$datos_air = $this->v_cnmd06_fichas->execute("
										select devolver_grado_puesto(
										               (select xy.clasificacion_personal from cnmd01 xy where
										                  xy.cod_presi           =     '".$cod_presi."'         and
														  xy.cod_entidad         =     '".$cod_entidad."'       and
														  xy.cod_tipo_inst       =     '".$cod_tipo_inst."'         and
														  xy.cod_inst            =     '".$cod_inst."'         and
														  xy.cod_dep             =     '".$cod_dep."'         and
														  xy.cod_tipo_nomina     =     '".$tipo_nomina."'
										               ), '".$puesto."' ); ");

$A1=$resultado[0]['v_cnmd06']["sueldo_basico"];
$A2=$resultado[0]['v_cnmd06']["compensaciones"];
$A3=$resultado[0]['v_cnmd06']["primas"];
$A4=$resultado[0]['v_cnmd06']["bonos"];
$A5=($A1+$A2+$A3+$A4);





								echo "<script>";
								    echo "document.getElementById('grado_input').value='".mascara2($datos_air[0][0]["devolver_grado_puesto"])."';   ";
								    //echo "document.getElementById('i_cod_cargo').value='".$this->AddCeroR($resultado[0]['v_cnmd06']['cod_cargo'])."';   ";
								    echo "document.getElementById('i_cod_puesto').value='".$resultado[0]['v_cnmd06']['cod_puesto']."';   ";
								    echo "document.getElementById('i_deno_puesto').value='".$resultado[0]['v_cnmd06']['demonimacion_puesto']."';   ";
								    echo "document.getElementById('ubicacion_geografica').value='".$resultado[0]['v_cnmd06']['deno_cod_dir_superior']."\\n".$resultado[0]['v_cnmd06']['deno_cod_coordinacion']."\\n".$resultado[0]['v_cnmd06']['deno_cod_secretaria']."\\n".$resultado[0]['v_cnmd06']['deno_cod_direccion']."\\n".$resultado[0]['v_cnmd06']['deno_cod_division']."\\n".$resultado[0]['v_cnmd06']['deno_cod_departamento']."\\n".$resultado[0]['v_cnmd06']['deno_cod_oficina']."';   ";
								    echo "document.getElementById('ubicacion_administrativa').value='".$resultado[0]['v_cnmd06']['deno_cod_estado']."\\n".$resultado[0]['v_cnmd06']['deno_cod_municipio']."\\n".$resultado[0]['v_cnmd06']['deno_cod_parroquia']."\\n".$resultado[0]['v_cnmd06']['deno_cod_centro']."';   ";
								    echo "document.getElementById('recursos_tipo').value='".$resultado[0]['v_cnmd06']['denominacion_cod_nivel_i']."\\n".$resultado[0]['v_cnmd06']['denominacion_cod_nivel_ii']."';   ";
								    echo "document.getElementById('sueldo').value='".$this->Formato2($A1)."';   ";
								    echo "document.getElementById('compensaciones').value='".$this->Formato2($A2)."';   ";
								    echo "document.getElementById('primas').value='".$this->Formato2($A3)."';   ";
								    echo "document.getElementById('bonos').value='".$this->Formato2($A4)."';   ";
								    echo "document.getElementById('total').value='".$this->Formato2($A5)."';   ";
								  //echo "document.getElementById('total').value='".$this->Formato2($resultado[0]['v_cnmd06']['asignaciones_sueldo_integral'] + $A1)."';   ";
								echo "</script>";
			  $this->funcion();
			  $this->render("funcion");


			}//fin else



}//fin function












function funcion($var1=null, $var2=null, $var3=null){

$this->layout="ajax";


}//fin function








function buscar_cargo($var1=null){
	$this->layout="ajax";
	$this->set("opcion",$var1);
	$this->Session->delete('pista');
	$this->Session->write('pista_opcion_cargo', 1);
}//fin function

function buscar_por_pista($var1=null, $var2=null, $var3=null){
$this->layout="ajax";
$tipo_nomina=   $this->Session->read('tipo_nomina');
$pista_opcion_cargo = $this->Session->read('pista_opcion_cargo');

 $sql_cargo = " and condicion_actividad='2' ";



    if($var3==null){$var2 = strtoupper($var2);
					$this->Session->write('pista', $var2);
					if(is_int($var2)){$sql   = " (demonimacion_puesto LIKE '%$var2%')  or   ";}else{ $sql = "";}
					$Tfilas=$this->v_cnmd06->findCount($this->SQLCA().' and cod_tipo_nomina='.$tipo_nomina.' and '.$sql." (upper(demonimacion_puesto) LIKE upper('%$var2%')  or cod_cargo::text  LIKE '%$var2%' )  ".$sql_cargo);
					        if($Tfilas!=0){
					        	$pagina=1;
					        	$Tfilas=(int)ceil($Tfilas/100);
					        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
								$this->set('total_paginas',$Tfilas);
								$this->set('pagina_actual',$pagina);
								$this->set('ultimo',$Tfilas);
					     	    $datos_filas=$this->v_cnmd06->findAll($this->SQLCA().' and cod_tipo_nomina='.$tipo_nomina.' and '.$sql." (upper(demonimacion_puesto) LIKE upper('%$var2%') or cod_cargo::text  LIKE '%$var2%'  )   ".$sql_cargo,null,"cod_cargo,cod_puesto ASC",100,1,null);
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
						if(is_int($var22)){$sql   = " (demonimacion_puesto  LIKE '%$var22%')  or   ";}else{ $sql = "";}
						$Tfilas=$this->v_cnmd06->findCount($this->SQLCA().' and cod_tipo_nomina='.$tipo_nomina.' and '.$sql." (upper(demonimacion_puesto) LIKE upper('%$var22%')  or cod_cargo::text  LIKE '%$var22%' ) ".$sql_cargo);
						        if($Tfilas!=0){
						        	$pagina=$var3;
						        	$Tfilas=(int)ceil($Tfilas/100);
						        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
									$this->set('total_paginas',$Tfilas);
									$this->set('pagina_actual',$pagina);
									$this->set('ultimo',$Tfilas);
						     	    $datos_filas=$this->v_cnmd06->findAll($this->SQLCA().' and cod_tipo_nomina='.$tipo_nomina.' and '.$sql." (upper(demonimacion_puesto) LIKE upper('%$var22%') or cod_cargo::text  LIKE '%$var22%' )  ".$sql_cargo,null,"cod_cargo,cod_puesto ASC",100,$pagina,null);
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







function seleccion_busqueda_venta($var1=null, $var2=null, $var3=null){
$this->layout="ajax";


                    $cod_presi      =  $this->Session->read('SScodpresi');
					$cod_entidad    =  $this->Session->read('SScodentidad');
					$cod_tipo_inst  =  $this->Session->read('SScodtipoinst');
					$cod_inst       =  $this->Session->read('SScodinst');
					$cod_dep        =  $this->Session->read('SScoddep');


$tipo_nomina=   $this->Session->read('tipo_nomina');
$resultado=$this->v_cnmd06->findAll($this->SQLCA().' and cod_tipo_nomina='.$tipo_nomina.' and cod_cargo='.$var1.' and cod_puesto='.$var2);

$this->Session->write('tipo_nomina_cod_cargo', $var1);


$resultado2=$this->cnmd06_fichas->findAll($this->SQLCA().' and cod_tipo_nomina='.$tipo_nomina.' and cod_cargo='.$var1.' and cod_ficha='.$var3);
$resultado3=$this->cnmd06_datos_personales->findAll('cedula_identidad='.$resultado2[0]["cnmd06_fichas"]["cedula_identidad"]);

//DIARIO

$A1=$resultado[0]['v_cnmd06']["sueldo_basico"];
$A2=$resultado[0]['v_cnmd06']["compensaciones"];
$A3=$resultado[0]['v_cnmd06']["primas"];
$A4=$resultado[0]['v_cnmd06']["bonos"];
$A5=($A1+$A2+$A3+$A4);

				                $puesto = $resultado[0]['v_cnmd06']['cod_puesto'];
								$datos_air = $this->v_cnmd06_fichas->execute("
										select devolver_grado_puesto(
										               (select xy.clasificacion_personal from cnmd01 xy where
										                  xy.cod_presi           =     '".$cod_presi."'         and
														  xy.cod_entidad         =     '".$cod_entidad."'       and
														  xy.cod_tipo_inst       =     '".$cod_tipo_inst."'         and
														  xy.cod_inst            =     '".$cod_inst."'         and
														  xy.cod_dep             =     '".$cod_dep."'         and
														  xy.cod_tipo_nomina     =     '".$tipo_nomina."'
										               ), '".$puesto."' ); ");


								echo "<script>";
								    echo "document.getElementById('i_cod_cargo').value='".$this->AddCeroR($resultado[0]['v_cnmd06']['cod_cargo'])."';   ";
								    echo "document.getElementById('i_cod_puesto').value='".$resultado[0]['v_cnmd06']['cod_puesto']."';   ";
								    echo "document.getElementById('i_deno_puesto').value='".$resultado[0]['v_cnmd06']['demonimacion_puesto']."';   ";
								    echo "document.getElementById('ubicacion_geografica').value='".$resultado[0]['v_cnmd06']['deno_cod_dir_superior']."\\n".$resultado[0]['v_cnmd06']['deno_cod_coordinacion']."\\n".$resultado[0]['v_cnmd06']['deno_cod_secretaria']."\\n".$resultado[0]['v_cnmd06']['deno_cod_direccion']."\\n".$resultado[0]['v_cnmd06']['deno_cod_division']."\\n".$resultado[0]['v_cnmd06']['deno_cod_departamento']."\\n".$resultado[0]['v_cnmd06']['deno_cod_oficina']."';   ";
								    echo "document.getElementById('ubicacion_administrativa').value='".$resultado[0]['v_cnmd06']['deno_cod_estado']."\\n".$resultado[0]['v_cnmd06']['deno_cod_municipio']."\\n".$resultado[0]['v_cnmd06']['deno_cod_parroquia']."\\n".$resultado[0]['v_cnmd06']['deno_cod_centro']."';   ";
								    echo "document.getElementById('sueldo').value='".$this->Formato2($A1)."';   ";
								    echo "document.getElementById('compensaciones').value='".$this->Formato2($A2)."';   ";
								    echo "document.getElementById('primas').value='".$this->Formato2($A3)."';   ";
								    echo "document.getElementById('bonos').value='".$this->Formato2($A4)."';   ";
								    echo "document.getElementById('total').value='".$this->Formato2($A5)."';   ";
							   	 // echo "document.getElementById('compensaciones').value='".$this->Formato2($resultado[0]['v_cnmd06']['compensaciones'])."';   ";
								 // echo "document.getElementById('primas').value='".$this->Formato2($resultado[0]['v_cnmd06']['primas'])."';   ";
								 // echo "document.getElementById('bonos').value='".$this->Formato2($resultado[0]['v_cnmd06']['bonos'])."';   ";
								 // echo "document.getElementById('total').value='".$this->Formato2($resultado[0]['v_cnmd06']['asignaciones_sueldo_integral'] + $A1)."';   ";
								    echo "document.getElementById('numero_input').value='".mascara_seis($var3)."';   ";

                                    echo "document.getElementById('cedula').value='".$this->AddCeroR($resultado3[0]['cnmd06_datos_personales']['cedula_identidad'])."';   ";
								    echo "document.getElementById('primer_n').value='".$resultado3[0]['cnmd06_datos_personales']['primer_nombre']."';   ";
								    echo "document.getElementById('segundo_n').value='".$resultado3[0]['cnmd06_datos_personales']['segundo_nombre']."';   ";
								    echo "document.getElementById('primer_a').value='".$resultado3[0]['cnmd06_datos_personales']['primer_apellido']."';   ";
								    echo "document.getElementById('segundo_a').value='".$resultado3[0]['cnmd06_datos_personales']['segundo_apellido']."';   ";
								echo "</script>";



$lista = $this->Cnmd01->generateList($conditions = $this->condicion(), $order = 'cod_tipo_nomina', $limit = null, '{n}.Cnmd01.cod_tipo_nomina', '{n}.Cnmd01.denominacion');
	if($lista !=null){
		$this->concatena($lista, 'cod_tipo_nomina2');
	}else{
		$this->set('cod_tipo_nomina2','');
	}


      $this->set("disabled_radio","disabled");
      $this->set("codigo_tipo_nomina2", "");
      $this->set("denominacion_nomina2","");






}//fin function








function llenar_pista_opcion_cargo($var1=null){
    $this->layout="ajax";
	$this->Session->write('pista_opcion_cargo', $var1);
	 echo "<script>$('select_obra_cod_obra').value='';</script>";
}//fin fucntion









function codigo_nomina($codigo=null){
	$this->layout = "ajax";
	$a = $this->Cnmd01->findAll($this->SQLCA()." and cod_tipo_nomina=".$codigo,array('cod_tipo_nomina','denominacion', 'horas_laborables'));
    $this->set("a",$a[0]['Cnmd01']['cod_tipo_nomina']);
    $this->Session->write('tipo_nomina',$codigo);

    				echo "<script>";
					    echo "document.getElementById('segunda_ventana').disabled=false;";
					    echo "document.getElementById('i_cod_cargo').readOnly=false;";
					    echo "document.getElementById('numero_input').value='';";
					echo "</script>";
}//fin cpcp02_codigo

function denominacion_nomina($codigo){
	$this->layout = "ajax";
	$b = $this->Cnmd01->findAll($this->SQLCA()." and cod_tipo_nomina=".$codigo,array('cod_tipo_nomina','denominacion'));
	$this->set("b",$b[0]['Cnmd01']['denominacion']);


}//fin cpcp02_denominacion







function codigo_nomina2($codigo=null){
	$this->layout = "ajax";
	$a = $this->Cnmd01->findAll($this->SQLCA()." and cod_tipo_nomina=".$codigo,array('cod_tipo_nomina','denominacion', 'horas_laborables'));
    $this->set("a",$a[0]['Cnmd01']['cod_tipo_nomina']);
    $this->Session->write('tipo_nomina2',$codigo);

    				echo "<script>";
					    echo "document.getElementById('segunda_ventana2').disabled=false;";
					    echo "document.getElementById('i_cod_cargo2').readOnly=false;";
					echo "</script>";

					$this->render("codigo_nomina");
}//fin cpcp02_codigo

function denominacion_nomina2($codigo){
	$this->layout = "ajax";
	$b = $this->Cnmd01->findAll($this->SQLCA()." and cod_tipo_nomina=".$codigo,array('cod_tipo_nomina','denominacion'));
	$this->set("b",$b[0]['Cnmd01']['denominacion']);

$this->render("denominacion_nomina");

}//fin cpcp02_denominacion



function buscar_cargo2($var1=null){
	$this->layout="ajax";
	$this->set("opcion",$var1);
	$this->Session->delete('pista2');
	$this->Session->write('pista_opcion_cargo2', 1);
}//fin function

function buscar_por_pista2($var1=null, $var2=null, $var3=null){
$this->layout="ajax";
$tipo_nomina=   $this->Session->read('tipo_nomina2');
$pista_opcion_cargo = $this->Session->read('pista_opcion_cargo2');

 $sql_cargo = " and condicion_actividad!='2' ";



    if($var3==null){$var2 = strtoupper($var2);
					$this->Session->write('pista2', $var2);
					if(is_int($var2)){$sql   = " (demonimacion_puesto LIKE '%$var2%')  or   ";}else{ $sql = "";}
					$Tfilas=$this->v_cnmd06->findCount($this->SQLCA().' and cod_tipo_nomina='.$tipo_nomina.' and '.$sql." (upper(demonimacion_puesto) LIKE upper('%$var2%')  or cod_cargo::text  LIKE '%$var2%' )  ".$sql_cargo);
					        if($Tfilas!=0){
					        	$pagina=1;
					        	$Tfilas=(int)ceil($Tfilas/100);
					        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
								$this->set('total_paginas',$Tfilas);
								$this->set('pagina_actual',$pagina);
								$this->set('ultimo',$Tfilas);
					     	    $datos_filas=$this->v_cnmd06->findAll($this->SQLCA().' and cod_tipo_nomina='.$tipo_nomina.' and '.$sql." (upper(demonimacion_puesto) LIKE upper('%$var2%') or cod_cargo::text  LIKE '%$var2%'  )   ".$sql_cargo,null,"cod_cargo,cod_puesto ASC",100,1,null);
						        $this->set("datosFILAS",$datos_filas);
						        $this->set('siguiente',$pagina+1);
								$this->set('anterior',$pagina-1);
								$this->bt_nav($Tfilas,$pagina);
					          }else{
					        	$this->set("datosFILAS",'');
					          }
            }else{
						$var22 = $this->Session->read('pista2');
						$var22 = strtoupper($var22);
						if(is_int($var22)){$sql   = " (demonimacion_puesto  LIKE '%$var22%')  or   ";}else{ $sql = "";}
						$Tfilas=$this->v_cnmd06->findCount($this->SQLCA().' and cod_tipo_nomina='.$tipo_nomina.' and '.$sql." (upper(demonimacion_puesto) LIKE upper('%$var22%')  or cod_cargo::text  LIKE '%$var22%' ) ".$sql_cargo);
						        if($Tfilas!=0){
						        	$pagina=$var3;
						        	$Tfilas=(int)ceil($Tfilas/100);
						        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
									$this->set('total_paginas',$Tfilas);
									$this->set('pagina_actual',$pagina);
									$this->set('ultimo',$Tfilas);
						     	    $datos_filas=$this->v_cnmd06->findAll($this->SQLCA().' and cod_tipo_nomina='.$tipo_nomina.' and '.$sql." (upper(demonimacion_puesto) LIKE upper('%$var22%') or cod_cargo::text  LIKE '%$var22%' )  ".$sql_cargo,null,"cod_cargo,cod_puesto ASC",100,$pagina,null);
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







function seleccion_busqueda_venta2($var1=null, $var2=null, $var3=null){
$this->layout="ajax";


                    $cod_presi      =  $this->Session->read('SScodpresi');
					$cod_entidad    =  $this->Session->read('SScodentidad');
					$cod_tipo_inst  =  $this->Session->read('SScodtipoinst');
					$cod_inst       =  $this->Session->read('SScodinst');
					$cod_dep        =  $this->Session->read('SScoddep');


$tipo_nomina=   $this->Session->read('tipo_nomina2');
$resultado=$this->v_cnmd06->findAll($this->SQLCA().' and cod_tipo_nomina='.$tipo_nomina.' and cod_cargo='.$var1.' and cod_puesto='.$var2);

$this->Session->write('tipo_nomina_cod_cargo2', $var1);




				                $puesto = $resultado[0]['v_cnmd06']['cod_puesto'];
								$datos_air = $this->v_cnmd06_fichas->execute("
										select devolver_grado_puesto(
										               (select xy.clasificacion_personal from cnmd01 xy where
										                  xy.cod_presi           =     '".$cod_presi."'         and
														  xy.cod_entidad         =     '".$cod_entidad."'       and
														  xy.cod_tipo_inst       =     '".$cod_tipo_inst."'         and
														  xy.cod_inst            =     '".$cod_inst."'         and
														  xy.cod_dep             =     '".$cod_dep."'         and
														  xy.cod_tipo_nomina     =     '".$tipo_nomina."'
										               ), '".$puesto."' ); ");

$A1=$resultado[0]['v_cnmd06']["sueldo_basico"];
$A2=$resultado[0]['v_cnmd06']["compensaciones"];
$A3=$resultado[0]['v_cnmd06']["primas"];
$A4=$resultado[0]['v_cnmd06']["bonos"];
$A5=($A1+$A2+$A3+$A4);


								echo "<script>";
								    echo "document.getElementById('i_cod_cargo2').value='".$this->AddCeroR($resultado[0]['v_cnmd06']['cod_cargo'])."';   ";
								    echo "document.getElementById('i_cod_puesto2').value='".$resultado[0]['v_cnmd06']['cod_puesto']."';   ";
								    echo "document.getElementById('i_deno_puesto2').value='".$resultado[0]['v_cnmd06']['demonimacion_puesto']."';   ";
								    echo "document.getElementById('ubicacion_geografica2').value='".$resultado[0]['v_cnmd06']['deno_cod_dir_superior']."\\n".$resultado[0]['v_cnmd06']['deno_cod_coordinacion']."\\n".$resultado[0]['v_cnmd06']['deno_cod_secretaria']."\\n".$resultado[0]['v_cnmd06']['deno_cod_direccion']."\\n".$resultado[0]['v_cnmd06']['deno_cod_division']."\\n".$resultado[0]['v_cnmd06']['deno_cod_departamento']."\\n".$resultado[0]['v_cnmd06']['deno_cod_oficina']."';   ";
								    echo "document.getElementById('ubicacion_administrativa2').value='".$resultado[0]['v_cnmd06']['deno_cod_estado']."\\n".$resultado[0]['v_cnmd06']['deno_cod_municipio']."\\n".$resultado[0]['v_cnmd06']['deno_cod_parroquia']."\\n".$resultado[0]['v_cnmd06']['deno_cod_centro']."';   ";
								    echo "document.getElementById('sueldo2').value='".$this->Formato2($A1)."';   ";
								    echo "document.getElementById('compensaciones2').value='".$this->Formato2($A2)."';   ";
								    echo "document.getElementById('primas2').value='".$this->Formato2($A3)."';   ";
								    echo "document.getElementById('bonos2').value='".$this->Formato2($A4)."';   ";
								    echo "document.getElementById('total2').value='".$this->Formato2($A5)."';   ";
								 // echo "document.getElementById('total2').value='".$this->Formato2($resultado[0]['v_cnmd06']['asignaciones_sueldo_integral'] + $A1)."';   ";
								echo "</script>";





}//fin function






function guardar(){

  $cod_presi      =  $this->Session->read('SScodpresi');
  $cod_entidad    =  $this->Session->read('SScodentidad');
  $cod_tipo_inst  =  $this->Session->read('SScodtipoinst');
  $cod_inst       =  $this->Session->read('SScodinst');
  $cod_dep        =  $this->Session->read('SScoddep');

  $cedula_identidad          =  $this->data['cnmp06_fichas_h_c_a']['cedula_identidad'];
  $cod_tipo_nomina_anterior  =  $this->data['cnmp06_fichas_h_c_a']['cod_tipo_nomina'];
  $cod_cargo_anterior        =  $this->data['cnmp06_fichas_h_c_a']['cod_cargo'];
  $cod_ficha_anterior        =  $this->data['cnmp06_fichas_h_c_a']['cod_ficha'];

  $resultado                 =  $this->cnmd06_fichas->findAll($this->SQLCA().' and cod_tipo_nomina='.$this->data['cnmp06_fichas_h_c_a']['cod_tipo_nomina'].' and cod_cargo='.$this->data['cnmp06_fichas_h_c_a']['cod_cargo'].' and cod_ficha='.$this->data['cnmp06_fichas_h_c_a']['cod_ficha']);

  $desde_fecha_anterior      =  $resultado[0]["cnmd06_fichas"]["fecha_ingreso"];
  $hasta_fecha_anterior      =  date("Y-m-d");
  $sueldo_basico_anterior    =  $this->Formato1($this->data['cnmp06_fichas_h_c_a']['sueldo']);
  $sueldo_integral_anterior  =  $this->Formato1($this->data['cnmp06_fichas_h_c_a']['total']);

  $cod_tipo_nomina_actual    =  $this->data['cnmp06_fichas_h_c_a']['cod_tipo_nomina2'];
  $cod_cargo_actual          =  $this->data['cnmp06_fichas_h_c_a']['cod_cargo2'];
  $ss=$this->cnmd06_fichas->findAll($this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina_actual,'cod_ficha','cod_ficha DESC',1);
  if($ss==null){
 	$cod_ficha=1;
  }else{
 	$cod_ficha=$ss[0]["cnmd06_fichas"]["cod_ficha"]+1;
  }
  $cod_ficha_actual          =  $cod_ficha;
  $fecha_desde_actual        =  date("Y-m-d");
  $sueldo_basico_actual      =  $this->Formato1($this->data['cnmp06_fichas_h_c_a']['sueldo2']);
  $sueldo_integral_actual    =  $this->Formato1($this->data['cnmp06_fichas_h_c_a']['total2']);
  $cambio_ascenso            =  $this->data['cnmp06_fichas_h_c_a']['radio'];

  $SQL_INSERT  =" BEGIN; INSERT INTO cnmd06_fichas_historial_cambios_ascensos (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cedula_identidad, cod_tipo_nomina_anterior, cod_cargo_anterior, cod_ficha_anterior, desde_fecha_anterior, hasta_fecha_anterior, sueldo_basico_anterior, sueldo_integral_anterior, cod_tipo_nomina_actual, cod_cargo_actual, cod_ficha_actual, fecha_desde_actual, sueldo_basico_actual, sueldo_integral_actual, cambio_ascenso)";
  $SQL_INSERT .=" VALUES ('".$cod_presi."','".$cod_entidad."','".$cod_tipo_inst."','".$cod_inst."','".$cod_dep."','".$cedula_identidad."','".$cod_tipo_nomina_anterior."','".$cod_cargo_anterior."','".$cod_ficha_anterior."','".$desde_fecha_anterior."','".$hasta_fecha_anterior."','".$sueldo_basico_anterior."','".$sueldo_integral_anterior."','".$cod_tipo_nomina_actual."','".$cod_cargo_actual."','".$cod_ficha_actual."','".$fecha_desde_actual."','".$sueldo_basico_actual."','".$sueldo_integral_actual."','".$cambio_ascenso."')";

  if($cambio_ascenso==1){
  	$condicion_actividad_anterior = "6";
  }else {
  	$condicion_actividad_anterior = "7";
  }
  $condicion_actividad="1";

  $sw2        = $this->cnmd06_datos_personales->execute($SQL_INSERT);
  $resultado2 = $this->cnmd06_fichas->findAll($this->SQLCA().' and cod_tipo_nomina='.$cod_tipo_nomina_anterior.' and cod_cargo='.$cod_cargo_anterior.' and cod_ficha='.$cod_ficha_anterior);

  extract($resultado2[0]["cnmd06_fichas"]);
  $SQL_INSERT2 =" BEGIN; INSERT INTO cnmd06_fichas (cod_presi,cod_entidad,cod_tipo_inst,cod_inst,cod_dep,cod_tipo_nomina,cod_cargo,cod_ficha,cedula_identidad,
												   fecha_ingreso,forma_pago,cod_entidad_bancaria,cod_sucursal,cuenta_bancaria,condicion_actividad,funciones_realizar,
												   responsabilidad_administrativa,horas_laborar,porcentaje_jub_pension,fecha_terminacion_contrato,fecha_retiro,motivo_retiro, paso, tipo_contrato, situacion, nivel, categoria,
												   username_registro, fecha_registro, username_movimiento, fecha_movimiento, ultimo_recibo,fecha_condicion)";
  if(isset($fecha_movimiento) && $fecha_movimiento==''){
  	$fecha_movimiento = "1900-01-01";
  }else if(!isset($fecha_movimiento)){
	$fecha_movimiento = "1900-01-01";
  }

  if(isset($fecha_condicion) && $fecha_condicion==''){
  	$fecha_condicion =  "1900-01-01";
  }else if(!isset($fecha_condicion)){
  	$fecha_condicion =  "1900-01-01";
  }

  $SQL_INSERT2 .=" VALUES  ('".$cod_presi."',
			               '".$cod_entidad."',
			               '".$cod_tipo_inst."',
			               '".$cod_inst."',
			               '".$cod_dep."',
			               '".$cod_tipo_nomina_actual."',
			               '".$cod_cargo_actual."',
			               '".$cod_ficha_actual."',
			               '".$cedula_identidad."',
						   '".$fecha_ingreso."',
						   '".$forma_pago."',
						   '".$cod_entidad_bancaria."',
						   '".$cod_sucursal."',
						   '".$cuenta_bancaria."',
						   '".$condicion_actividad."',
						   '".$funciones_realizar."',
						   '".$responsabilidad_administrativa."',
						   '".$horas_laborar."',
						   '".$porcentaje_jub_pension."',
						   '".$fecha_terminacion_contrato."',
						   '".$fecha_retiro."',
						   '".$motivo_retiro."',
						   '".$paso."',
						   '".$tipo_contrato."',
						   '".$situacion."',
						   '".$nivel."',
						   '".$categoria."',
						   '".$username_registro."',
						   '".$fecha_registro."',
						   '".$username_movimiento."',
						   '".$fecha_movimiento."',
						   '".$ultimo_recibo."',
						   '".$fecha_condicion."');

						   UPDATE  cnmd06_fichas set condicion_actividad='".$condicion_actividad_anterior."'         where ".$this->SQLCA()." and cod_tipo_nomina='".$cod_tipo_nomina_anterior."'  and cod_cargo='".$cod_cargo_anterior."' and cod_ficha='".$cod_ficha_anterior."';
                           UPDATE  cnmd05        set condicion_actividad='2',  cod_ficha='".$cod_ficha_actual."'     where ".$this->SQLCA()." and cod_tipo_nomina='".$cod_tipo_nomina_actual."'    and cod_cargo='".$cod_cargo_actual."';
                           UPDATE  cnmd05        set condicion_actividad='1',  cod_ficha='0'                         where ".$this->SQLCA()." and cod_tipo_nomina='".$cod_tipo_nomina_anterior."'  and cod_cargo='".$cod_cargo_anterior."'; ";

      $resp = $this->cnmd06_fichas->execute($SQL_INSERT2);
     if($resp>1){
      	  if($sw2>1){


// HISTORIA NOMINA
$historia_nomina_anterior = $this->cnmd08_historia_nomina_fideicomiso->findAll($this->SQLCA().' and cod_tipo_nomina='.$cod_tipo_nomina_anterior);
if(!empty($historia_nomina_anterior) || $historia_nomina_anterior==null){

	$sql_ihn = "INSERT INTO cnmd08_historia_nomina_fideicomiso (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, ano, numero_nomina, periodo_desde, periodo_hasta, concepto, numero_recibo, frecuencia_pago, mensaje_colectivo, cantidad_pagos) VALUES ";

	foreach($historia_nomina_anterior as $historia_nomina_ante){
		$codi_presi = $historia_nomina_ante['cnmd08_historia_nomina_fideicomiso']['cod_presi'];
		$codi_entidad = $historia_nomina_ante['cnmd08_historia_nomina_fideicomiso']['cod_entidad'];
		$codi_tipo_inst = $historia_nomina_ante['cnmd08_historia_nomina_fideicomiso']['cod_tipo_inst'];
		$codi_inst = $historia_nomina_ante['cnmd08_historia_nomina_fideicomiso']['cod_inst'];
		$codi_dep = $historia_nomina_ante['cnmd08_historia_nomina_fideicomiso']['cod_dep'];
		$codi_tipo_nomina = $historia_nomina_ante['cnmd08_historia_nomina_fideicomiso']['cod_tipo_nomina'];
		$anoi = $historia_nomina_ante['cnmd08_historia_nomina_fideicomiso']['ano'];
		$numeroi_nomina = $historia_nomina_ante['cnmd08_historia_nomina_fideicomiso']['numero_nomina'];
		$periodoi_desde = $historia_nomina_ante['cnmd08_historia_nomina_fideicomiso']['periodo_desde'];
		$periodoi_hasta = $historia_nomina_ante['cnmd08_historia_nomina_fideicomiso']['periodo_hasta'];
		$conceptoi = $historia_nomina_ante['cnmd08_historia_nomina_fideicomiso']['concepto']." (CAMBIO DE NOMINA)";
		$numeroi_recibo = $historia_nomina_ante['cnmd08_historia_nomina_fideicomiso']['numero_recibo'];
		$frecuenciai_pago = $historia_nomina_ante['cnmd08_historia_nomina_fideicomiso']['frecuencia_pago'];
		$mensajei_colectivo = $historia_nomina_ante['cnmd08_historia_nomina_fideicomiso']['mensaje_colectivo'];
		$cantidadi_pagos = $historia_nomina_ante['cnmd08_historia_nomina_fideicomiso']['cantidad_pagos'];

				$busca_historia_actual = $this->cnmd08_historia_nomina_fideicomiso->findAll($this->SQLCA().' and cod_tipo_nomina='.$cod_tipo_nomina_actual.' and ano='.$anoi.' and numero_nomina='.$numeroi_nomina);
				if(!empty($busca_historia_actual)){
				}else{
					$dhn[] = " ('".$codi_presi."', '".$codi_entidad."', '".$codi_tipo_inst."', '".$codi_inst."', '".$codi_dep."', '".$cod_tipo_nomina_actual."','".$anoi."','".$numeroi_nomina."','".$periodoi_desde."','".$periodoi_hasta."', '".$conceptoi."', '".$numeroi_recibo."', '".$frecuenciai_pago."','".$mensajei_colectivo."','".$cantidadi_pagos."')";
				}
		}

	if(isset($dhn) && !empty($dhn)){
		$sql_ihn .= " ".implode(',', $dhn).";";
		$sws = $this->Cnmd01->execute($sql_ihn);

		if($sws > 1){
			$swi = 2;
		}else{
			$swi = 1;
		}

		unset($dhn);
	}else{$swi = 2;}
}

// HISTORIA TRABAJADOR
$historia_trabajador_anterior = $this->cnmd08_historia_trabajador_fideicomiso->findAll($this->SQLCA().' and cod_tipo_nomina='.$cod_tipo_nomina_anterior.' and cod_cargo='.$cod_cargo_anterior.' and cod_ficha='.$cod_ficha_anterior);
if(!empty($historia_trabajador_anterior) || $historia_trabajador_anterior==null){

	$sql_ihn = "INSERT INTO cnmd08_historia_trabajador_fideicomiso (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, ano, numero_nomina, cod_cargo, cod_ficha, cedula_identidad, dias_cobro, acumulado_prestaciones, mensaje_personal, numero_recibo) VALUES ";

	foreach($historia_trabajador_anterior as $historia_trabajador_ante){
		$codi_presi = $historia_trabajador_ante['cnmd08_historia_trabajador_fideicomiso']['cod_presi'];
		$codi_entidad = $historia_trabajador_ante['cnmd08_historia_trabajador_fideicomiso']['cod_entidad'];
		$codi_tipo_inst = $historia_trabajador_ante['cnmd08_historia_trabajador_fideicomiso']['cod_tipo_inst'];
		$codi_inst = $historia_trabajador_ante['cnmd08_historia_trabajador_fideicomiso']['cod_inst'];
		$codi_dep = $historia_trabajador_ante['cnmd08_historia_trabajador_fideicomiso']['cod_dep'];
		$anoi = $historia_trabajador_ante['cnmd08_historia_trabajador_fideicomiso']['ano'];
		$numeroi_nomina = $historia_trabajador_ante['cnmd08_historia_trabajador_fideicomiso']['numero_nomina'];
		$cedulai = $historia_trabajador_ante['cnmd08_historia_trabajador_fideicomiso']['cedula_identidad'];
		$diasi_cobro = $historia_trabajador_ante['cnmd08_historia_trabajador_fideicomiso']['dias_cobro'];
		$acumuladoi = $historia_trabajador_ante['cnmd08_historia_trabajador_fideicomiso']['acumulado_prestaciones'];
		$mensajei_personal = $historia_trabajador_ante['cnmd08_historia_trabajador_fideicomiso']['mensaje_personal'];
		$numeroi_recibo = $historia_trabajador_ante['cnmd08_historia_trabajador_fideicomiso']['numero_recibo'];

			$dht[] = " ('".$codi_presi."', '".$codi_entidad."', '".$codi_tipo_inst."', '".$codi_inst."', '".$codi_dep."', '".$cod_tipo_nomina_actual."','".$anoi."','".$numeroi_nomina."','".$cod_cargo_actual."','".$cod_ficha_actual."', '".$cedulai."', '".$diasi_cobro."', '".$acumuladoi."','".$mensajei_personal."','".$numeroi_recibo."')";
		 // $inserta_historia_trabajador=$this->cnmd08_historia_trabajador_fideicomiso->execute("INSERT INTO cnmd08_historia_trabajador (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, ano, numero_nomina, cod_cargo, cod_ficha, cedula_identidad, dias_cobro, acumulado_prestaciones, mensaje_personal, numero_recibo) VALUES (".$codi_presi.",".$codi_entidad.",".$codi_tipo_inst.",".$codi_inst.",".$codi_dep.",".$cod_tipo_nomina_actual.",".$anoi.",".$numeroi_nomina.",".$cod_cargo_actual.",".$cod_ficha_actual.",".$cedulai.",".$diasi_cobro.",".$acumuladoi.",'".$mensajei_personal."',".$numeroi_recibo.")");
		}

	if(isset($dht) && !empty($dht)){
		$sql_ihn .= " ".implode(',', $dht).";";
		$sws = $this->Cnmd01->execute($sql_ihn);
		if($sws > 1){
			$swi = 2;
		}else{
			$swi = 1;
		}

		unset($dht);
	}else{$swi = 2;}
}

// HISTORIA DE TRANSACCIONES
$historia_transacciones_anterior = $this->cnmd08_historia_transacciones_fideicomiso->findAll($this->SQLCA().' and cod_tipo_nomina='.$cod_tipo_nomina_anterior.' and cod_cargo='.$cod_cargo_anterior.' and cod_ficha='.$cod_ficha_anterior);
if(!empty($historia_transacciones_anterior) || $historia_transacciones_anterior==null){

	$sql_ihn = "INSERT INTO cnmd08_historia_transacciones_fideicomiso (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, ano, numero_nomina, cod_cargo, cod_ficha, cod_tipo_transaccion, cod_transaccion, fecha_transaccion, monto_original, numero_cuotas_descontar, numero_cuotas_canceladas, monto_cuota, saldo, dias_horas) VALUES ";

	foreach($historia_transacciones_anterior as $historia_transacciones_ante){
		$codi_presi = $historia_transacciones_ante['cnmd08_historia_transacciones_fideicomiso']['cod_presi'];
		$codi_entidad = $historia_transacciones_ante['cnmd08_historia_transacciones_fideicomiso']['cod_entidad'];
		$codi_tipo_inst = $historia_transacciones_ante['cnmd08_historia_transacciones_fideicomiso']['cod_tipo_inst'];
		$codi_inst = $historia_transacciones_ante['cnmd08_historia_transacciones_fideicomiso']['cod_inst'];
		$codi_dep = $historia_transacciones_ante['cnmd08_historia_transacciones_fideicomiso']['cod_dep'];
		$anoi = $historia_transacciones_ante['cnmd08_historia_transacciones_fideicomiso']['ano'];
		$numeroi_nomina = $historia_transacciones_ante['cnmd08_historia_transacciones_fideicomiso']['numero_nomina'];
		$tipoi_transaccion = $historia_transacciones_ante['cnmd08_historia_transacciones_fideicomiso']['cod_tipo_transaccion'];
		$codi_transaccion = $historia_transacciones_ante['cnmd08_historia_transacciones_fideicomiso']['cod_transaccion'];
		$fechai_transaccion = $historia_transacciones_ante['cnmd08_historia_transacciones_fideicomiso']['fecha_transaccion'];
		$montoi_original = $historia_transacciones_ante['cnmd08_historia_transacciones_fideicomiso']['monto_original'];
		$cuotas_descontar = $historia_transacciones_ante['cnmd08_historia_transacciones_fideicomiso']['numero_cuotas_descontar'];
		$cuotas_canceladas = $historia_transacciones_ante['cnmd08_historia_transacciones_fideicomiso']['numero_cuotas_canceladas'];
		$monto_cuota = $historia_transacciones_ante['cnmd08_historia_transacciones_fideicomiso']['monto_cuota'];
		$saldo = $historia_transacciones_ante['cnmd08_historia_transacciones_fideicomiso']['saldo'];
		$dias_hora = $historia_transacciones_ante['cnmd08_historia_transacciones_fideicomiso']['dias_horas'];
		if ($cuotas_descontar==null || $cuotas_descontar==0){$cuotas_descontar=1;}
		if ($cuotas_canceladas==null || $cuotas_canceladas==0){$cuotas_canceladas=1;}

			$dhtt[] = " ('$codi_presi', '$codi_entidad', '$codi_tipo_inst', '$codi_inst', '$codi_dep', '$cod_tipo_nomina_actual','$anoi','$numeroi_nomina','$cod_cargo_actual','$cod_ficha_actual', '$tipoi_transaccion', '$codi_transaccion', '$fechai_transaccion','$montoi_original','$cuotas_descontar','$cuotas_canceladas','$monto_cuota','$saldo','$dias_hora')";
			// $inserta_historia_transacciones=$this->cnmd08_historia_transacciones_fideicomiso->execute("INSERT INTO cnmd08_historia_transacciones (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, ano, numero_nomina, cod_cargo, cod_ficha, cod_tipo_transaccion, cod_transaccion, fecha_transaccion, monto_original, numero_cuotas_descontar, numero_cuotas_canceladas, monto_cuota, saldo, dias_horas) VALUES (".$codi_presi.",".$codi_entidad.",".$codi_tipo_inst.",".$codi_inst.",".$codi_dep.",".$cod_tipo_nomina_actual.",".$anoi.",".$numeroi_nomina.",".$cod_cargo_actual.",".$cod_ficha_actual.",".$tipoi_transaccion.",".$codi_transaccion.",'".$fechai_transaccion."',".$montoi_original.",".$cuotas_descontar.",".$cuotas_canceladas.",".$monto_cuota.",".$saldo.",".$dias_hora.")");
		}

	if(isset($dhtt) && !empty($dhtt)){
		$sql_ihn .= " ".implode(',', $dhtt).";";
		$sws = $this->Cnmd01->execute($sql_ihn);
		if($sws > 1){
			$swi = 2;
		}else{
			$swi = 1;
		}

		unset($dhtt);
	}else{$swi = 2;}
}


// TRANSACCIONES ACTUALES
$transacciones_actuales_anterior = $this->cnmd07_transacciones_actuales->findAll($this->SQLCA().' and cod_tipo_nomina='.$cod_tipo_nomina_anterior.' and cod_cargo='.$cod_cargo_anterior.' and cod_ficha='.$cod_ficha_anterior);
if(!empty($transacciones_actuales_anterior) || $transacciones_actuales_anterior==null){

	foreach($transacciones_actuales_anterior as $transacciones_actuales_ante){
		$codi_presi = $transacciones_actuales_ante['cnmd07_transacciones_actuales']['cod_presi'];
		$codi_entidad = $transacciones_actuales_ante['cnmd07_transacciones_actuales']['cod_entidad'];
		$codi_tipo_inst = $transacciones_actuales_ante['cnmd07_transacciones_actuales']['cod_tipo_inst'];
		$codi_inst = $transacciones_actuales_ante['cnmd07_transacciones_actuales']['cod_inst'];
		$codi_dep = $transacciones_actuales_ante['cnmd07_transacciones_actuales']['cod_dep'];
		$tipoi_transaccion = $transacciones_actuales_ante['cnmd07_transacciones_actuales']['cod_tipo_transaccion'];
		$codi_transaccion = $transacciones_actuales_ante['cnmd07_transacciones_actuales']['cod_transaccion'];
		$fechai_transaccion = $transacciones_actuales_ante['cnmd07_transacciones_actuales']['fecha_transaccion'];
		$montoi_original = $transacciones_actuales_ante['cnmd07_transacciones_actuales']['monto_original'];
		$cuotas_descontar = $transacciones_actuales_ante['cnmd07_transacciones_actuales']['numero_cuotas_descontar'];
		$cuotas_cancelar = $transacciones_actuales_ante['cnmd07_transacciones_actuales']['numero_cuotas_cancelar'];
		$cuotas_canceladas = $transacciones_actuales_ante['cnmd07_transacciones_actuales']['numero_cuotas_canceladas'];
		$monto_cuota = $transacciones_actuales_ante['cnmd07_transacciones_actuales']['monto_cuota'];
		$saldo = $transacciones_actuales_ante['cnmd07_transacciones_actuales']['saldo'];
		$marcai = $transacciones_actuales_ante['cnmd07_transacciones_actuales']['marca_fin_descuento'];
		$fechai_proceso = $transacciones_actuales_ante['cnmd07_transacciones_actuales']['fecha_proceso'];
		$usuarioi = $transacciones_actuales_ante['cnmd07_transacciones_actuales']['username'];
		$dias_hora = $transacciones_actuales_ante['cnmd07_transacciones_actuales']['dias_horas'];

      	$sql_transacciones = "INSERT INTO cnmd07_transacciones_actuales (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina,
      			cod_cargo, cod_ficha, cod_tipo_transaccion, cod_transaccion, fecha_transaccion, monto_original, numero_cuotas_descontar,
      					numero_cuotas_cancelar, numero_cuotas_canceladas, monto_cuota, saldo, marca_fin_descuento, fecha_proceso, username, dias_horas)
      			VALUES ('$codi_presi', '$codi_entidad', '$codi_tipo_inst', '$codi_inst', '$codi_dep', '$cod_tipo_nomina_actual',
      					'$cod_cargo_actual','$cod_ficha_actual', '$tipoi_transaccion', '$codi_transaccion', '$fechai_transaccion',
      					'$montoi_original','$cuotas_descontar','$cuotas_cancelar','$cuotas_canceladas','$monto_cuota','$saldo',
      					'$marcai','$fechai_proceso','$usuarioi','$dias_hora');";
      			$insertar_transacciones  = $this->Cnmd01->execute($sql_transacciones);

		}
	}



//ELIMINA TRANSACCIONES ACTUALES ANTERIOR
    $sws1 = $this->Cnmd01->execute("DELETE FROM cnmd07_transacciones_suspendidas WHERE ".$this->SQLCA().' and cod_tipo_nomina='.$cod_tipo_nomina_anterior.' and cod_cargo='.$cod_cargo_anterior.' and cod_ficha='.$cod_ficha_anterior);
    $sws2 = $this->Cnmd01->execute("DELETE FROM cnmd07_transacciones_prenomina WHERE ".$this->SQLCA().' and cod_tipo_nomina='.$cod_tipo_nomina_anterior.' and cod_cargo='.$cod_cargo_anterior.' and cod_ficha='.$cod_ficha_anterior);
	$sws = $this->Cnmd01->execute("DELETE FROM cnmd07_transacciones_actuales WHERE ".$this->SQLCA().' and cod_tipo_nomina='.$cod_tipo_nomina_anterior.' and cod_cargo='.$cod_cargo_anterior.' and cod_ficha='.$cod_ficha_anterior);
		if($sws >= 1){
			$swi = 2;
		}else{
			$swi = 1;
		}


//ELIMINA HISTORIA DE TRANSACCIONES ANTERIOR
	$sws = $this->Cnmd01->execute("DELETE FROM cnmd08_historia_transacciones_fideicomiso WHERE ".$this->SQLCA().' and cod_tipo_nomina='.$cod_tipo_nomina_anterior.' and cod_cargo='.$cod_cargo_anterior.' and cod_ficha='.$cod_ficha_anterior);
		if($sws >= 1){
			$swi = 2;
		}else{
			$swi = 1;
		}

//ELIMINA HISTORIA DE TRABAJADOR ANTERIOR
	$sws = $this->Cnmd01->execute("DELETE FROM cnmd08_historia_trabajador_fideicomiso WHERE ".$this->SQLCA().' and cod_tipo_nomina='.$cod_tipo_nomina_anterior.' and cod_cargo='.$cod_cargo_anterior.' and cod_ficha='.$cod_ficha_anterior);
		if($sws >= 1){
			$swi = 2;
		}else{
			$swi = 1;
		}

				if($swi > 1){
		      	  $this->cnmd06_datos_personales->execute("COMMIT;");
		     	  $this->data=null;
				  $this->set('Message_existe', 'Registro Agregado con exito');



// ACTUALIZA TABLAS DE FIDEICOMISO
$sql_ejecutar_1 = "UPDATE cnmd17_fideicomiso_cuentas_bancarias SET cod_tipo_nomina='$cod_tipo_nomina_actual', cod_cargo='$cod_cargo_actual', cod_ficha='$cod_ficha_actual' WHERE ".$this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina_anterior." and cod_cargo=".$cod_cargo_anterior." and cod_ficha=".$cod_ficha_anterior;
$ejecutar_1 = $this->cnmd06_datos_personales->execute($sql_ejecutar_1);

$sql_ejecutar_2 = "UPDATE cnmd17_fideicomiso_trimestral_perma SET cod_tipo_nomina='$cod_tipo_nomina_actual', cod_cargo='$cod_cargo_actual', cod_ficha='$cod_ficha_actual' WHERE ".$this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina_anterior." and cod_cargo=".$cod_cargo_anterior." and cod_ficha=".$cod_ficha_anterior;
$ejecutar_2 = $this->cnmd06_datos_personales->execute($sql_ejecutar_2);

$sql_ejecutar_3 = "UPDATE cnmd17_fideicomiso_trimestral_temporal SET cod_tipo_nomina='$cod_tipo_nomina_actual', cod_cargo='$cod_cargo_actual', cod_ficha='$cod_ficha_actual' WHERE ".$this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina_anterior." and cod_cargo=".$cod_cargo_anterior." and cod_ficha=".$cod_ficha_anterior;
$ejecutar_3 = $this->cnmd06_datos_personales->execute($sql_ejecutar_3);

$ejecutar_elim_1 = $this->cnmd06_datos_personales->execute("DELETE FROM cnmd17_fideicomiso_cuentas_bancarias WHERE ".$this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina_anterior." and cod_cargo=".$cod_cargo_anterior." and cod_ficha=".$cod_ficha_anterior);
$ejecutar_elim_2 = $this->cnmd06_datos_personales->execute("DELETE FROM cnmd17_fideicomiso_trimestral_perma WHERE ".$this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina_anterior." and cod_cargo=".$cod_cargo_anterior." and cod_ficha=".$cod_ficha_anterior);
$ejecutar_elim_3 = $this->cnmd06_datos_personales->execute("DELETE FROM cnmd17_fideicomiso_trimestral_temporal WHERE ".$this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina_anterior." and cod_cargo=".$cod_cargo_anterior." and cod_ficha=".$cod_ficha_anterior);



				}else{
		     	  $this->cnmd06_datos_personales->execute("ROLLBACK;");
		     	  $this->set('errorMessage', 'Disculpe, El Registro no fue creado');
		     	}
		     }else{
		     	  $this->cnmd06_datos_personales->execute("ROLLBACK;");
		     	  $this->set('errorMessage', 'Disculpe, El Registro no fue creado');
		     }//fin else
     }else{
          $this->cnmd06_datos_personales->execute("ROLLBACK;");
     	  $this->set('errorMessage', 'Disculpe, El Registro no fue creado');
     }


$this->index();
$this->render("index");

}










function buscar_consulta($var1=null){
	$this->layout="ajax";
	$this->set("opcion",$var1);
	$this->Session->delete('pista3');
	$this->Session->write('pista_opcion_cargo3', 1);
}//fin function



function buscar_consulta2($var1=null, $var2=null, $var3=null){
$this->layout="ajax";


    if($var3==null){$var2 = strtoupper($var2);
					$this->Session->write('pista3', $var2);
					$sql   = " (cedula_identidad::text LIKE '%$var2%')     ";
					$Tfilas=$this->cnmd06_fichas_h_c_a->findCount($this->SQLCA().' and '.$sql);
					        if($Tfilas!=0){
					        	$pagina=1;
					        	$Tfilas=(int)ceil($Tfilas/100);
					        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
								$this->set('total_paginas',$Tfilas);
								$this->set('pagina_actual',$pagina);
								$this->set('ultimo',$Tfilas);
					     	    $datos_filas=$this->cnmd06_fichas_h_c_a->findAll($this->SQLCA().' and '.$sql,null,"cedula_identidad,secuencia ASC",100,1,null);
						        $this->set("datosFILAS",$datos_filas);
						        $this->set('siguiente',$pagina+1);
								$this->set('anterior',$pagina-1);
								$this->bt_nav($Tfilas,$pagina);
					          }else{
					        	$this->set("datosFILAS",'');
					          }
            }else{
						$var22 = $this->Session->read('pista3');
						$var22 = strtoupper($var22);
						$sql   = " (cedula_identidad::text  LIKE '%$var22%')  ";
						$Tfilas=$this->cnmd06_fichas_h_c_a->findCount($this->SQLCA().'  and '.$sql);
						        if($Tfilas!=0){
						        	$pagina=$var3;
						        	$Tfilas=(int)ceil($Tfilas/100);
						        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
									$this->set('total_paginas',$Tfilas);
									$this->set('pagina_actual',$pagina);
									$this->set('ultimo',$Tfilas);
						     	    $datos_filas=$this->cnmd06_fichas_h_c_a->findAll($this->SQLCA().' and '.$sql,null,"cedula_identidad,secuencia ASC",100,$pagina,null);
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








function buscar_consulta3($var1=null, $var2=null){
	$this->layout="ajax";


$cedula_identidad  = $var1;
$secuencia         = $var2;


$datos_filas=$this->cnmd06_fichas_h_c_a->findAll($this->SQLCA()." and cedula_identidad='".$cedula_identidad."' and secuencia='".$secuencia."' ",null,"cedula_identidad,secuencia ASC");


foreach($datos_filas as $aux){


			  $cod_tipo_nomina_anterior = $aux['cnmd06_fichas_h_c_a']['cod_tipo_nomina_anterior'];
			  $cod_cargo_anterior       = $aux['cnmd06_fichas_h_c_a']['cod_cargo_anterior'];
			  $cod_ficha_anterior       = $aux['cnmd06_fichas_h_c_a']['cod_ficha_anterior'];
			  $desde_fecha_anterior     = $aux['cnmd06_fichas_h_c_a']['desde_fecha_anterior'];
			  $hasta_fecha_anterior     = $aux['cnmd06_fichas_h_c_a']['hasta_fecha_anterior'];
			  $sueldo_basico_anterior   = $aux['cnmd06_fichas_h_c_a']['sueldo_basico_anterior'];
			  $sueldo_integral_anterior = $aux['cnmd06_fichas_h_c_a']['sueldo_integral_anterior'];

			  $cod_tipo_nomina_actual   = $aux['cnmd06_fichas_h_c_a']['cod_tipo_nomina_actual'];
			  $cod_cargo_actual         = $aux['cnmd06_fichas_h_c_a']['cod_cargo_actual'];
			  $cod_ficha_actual         = $aux['cnmd06_fichas_h_c_a']['cod_ficha_actual'];
			  $fecha_desde_actual       = $aux['cnmd06_fichas_h_c_a']['fecha_desde_actual'];
			  $sueldo_basico_actual     = $aux['cnmd06_fichas_h_c_a']['sueldo_basico_actual'];
			  $sueldo_integral_actual   = $aux['cnmd06_fichas_h_c_a']['sueldo_integral_actual'];
			  $cambio_ascenso           = $aux['cnmd06_fichas_h_c_a']['cambio_ascenso'];





			  ///ANTERIOR//

$resultado =$this->v_cnmd06->findAll($this->SQLCA().' and cod_tipo_nomina='.$cod_tipo_nomina_anterior.' and cod_cargo='.$cod_cargo_anterior.' ');
$resultado2=$this->cnmd06_fichas->findAll($this->SQLCA().' and cod_tipo_nomina='.$cod_tipo_nomina_anterior.' and cod_cargo='.$cod_cargo_anterior.' and cod_ficha='.$cod_ficha_anterior);
$resultado3=$this->cnmd06_datos_personales->findAll('cedula_identidad='.$resultado2[0]["cnmd06_fichas"]["cedula_identidad"]);


$this->set("resultado",$resultado);
$this->set("resultado2",$resultado2);
$this->set("resultado3",$resultado3);

$a = $this->Cnmd01->findAll($this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina_anterior,array('cod_tipo_nomina','denominacion', 'horas_laborables'));
$this->set("a",mascara_tres($a[0]['Cnmd01']['cod_tipo_nomina']));
$this->set("b",$a[0]['Cnmd01']['denominacion']);



//$resultado4=$this->v_cnmd06->findAll($this->SQLCA().' and cod_tipo_nomina='.$cod_tipo_nomina_actual.' and cod_cargo='.$cod_cargo_actual.' and cod_ficha='.$cod_ficha_actual);
  $resultado4=$this->v_cnmd06->findAll($this->SQLCA().' and cod_tipo_nomina='.$cod_tipo_nomina_actual.' and cod_cargo='.$cod_cargo_actual);

$this->set("resultado4",$resultado4);

$b = $this->Cnmd01->findAll($this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina_actual,array('cod_tipo_nomina','denominacion', 'horas_laborables'));
$this->set("c",mascara_tres($b[0]['Cnmd01']['cod_tipo_nomina']));
$this->set("d",$b[0]['Cnmd01']['denominacion']);


$this->set("d",$b[0]['Cnmd01']['denominacion']);

}//fin foreach


$this->set("secuencia",$secuencia);
$this->set("cambio_ascenso",$cambio_ascenso);
$this->set("datos_filas",$datos_filas);

}//fin function









function reporte_1($var1=null){


	      if($var1==1){ $this->layout = "ajax";

	      	    $lista = $this->Cnmd01->generateList($conditions = $this->condicion(), $order = 'cod_tipo_nomina', $limit = null, '{n}.Cnmd01.cod_tipo_nomina', '{n}.Cnmd01.denominacion');
				if($lista !=null){
					$this->concatena($lista, 'cod_tipo_nomina');
				}else{
					$this->set('cod_tipo_nomina','');
				}


	      }else{  $this->layout = "pdf";

	      	    $cod_tipo_nomina    = !empty($this->data['cnmp06_fichas_h_c_a']['cod_tipo_nomina'])   ? $this->data['cnmp06_fichas_h_c_a']['cod_tipo_nomina'] : 0 ;
	            $opcion_busqueda1   = !empty($this->data['cnmp06_fichas_h_c_a']['opcion_busqueda1'])  ? $this->data['cnmp06_fichas_h_c_a']['opcion_busqueda1'] : 0 ;
	            $cedula_identidad   = !empty($this->data['cnmp06_fichas_h_c_a']['cedula_identidad'])  ? $this->data['cnmp06_fichas_h_c_a']['cedula_identidad'] : 0 ;
                $sql_1 = "";
                if($opcion_busqueda1==2){
                 $sql_1 = " and cedula_identidad='".$cedula_identidad."'  ";
                }
                $sql        = $this->condicion()." and cod_tipo_nomina='".$cod_tipo_nomina."' ".$sql_1." ";
                $resultado4 = $this->v_cnmd06_fichas_h_c_a2->findAll($sql,null, "cod_tipo_nomina, cedula_identidad, secuencia ASC");
                $this->set("resultado", $resultado4);

	      }//fin else

$this->set("opcion",$var1);


}










}//fin class
?>