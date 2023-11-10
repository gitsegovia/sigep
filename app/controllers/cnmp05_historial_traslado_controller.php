<?php


 class Cnmp05HistorialTrasladoController extends AppController{
	var $uses = array('cnmd05_historial_traslado', 'Cnmd01', 'v_cnmd06_fichas_datos_personales', 'cnmd06_fichas',
                      'cnmd06_datos_personales', 'v_cnmd05', 'cnmd03_transacciones', 'v_cnmd05_cargos', 'v_cnmd05_historial_traslado',
                      'arrd05','Cnmd02_empleados_puestos','Cnmd02_obreros_puestos','cnmd02_varios_puestos', 'v_cnmd05_historial_traslado_g',
                      'cnmd05','cugd02_direccionsuperior', 'cugd02_coordinacion', 'cugd02_secretaria', 'cugd02_direccion', 'cugd02_division',
                      'cugd02_departamento', 'cugd02_oficina','cugd01_estados', 'cugd01_municipios', 'cugd01_parroquias', 'cugd01_centropoblados',
                      'cfpd02_sector', 'cfpd02_programa', 'cfpd02_sub_prog', 'cfpd02_proyecto', 'cfpd02_activ_obra', 'cfpd01_ano_partida',
                      'cfpd01_ano_generica', 'cfpd01_ano_especifica', 'cfpd01_ano_sub_espec', 'cfpd01_ano_auxiliar','cnmd04_ocupacion', 'ccfd04_cierre_mes',
                      'cnmd04_tipo', 'v_cnmd05','cfpd05_auxiliar','cnmd06_fichas','cnmd06_datos_personales','cfpd01_formulacion', 'v_cfpd05_denominaciones',
                      'v_cnmd05_cargos','cnmd06_fichas','cnmd01_autorizados');
 	var $helpers = array('Html','Ajax','Javascript', 'Sisap');
 	var $name = "cnmp05_historial_traslado";

//cnmp06_amonestaciones

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




function codigo_nomina($codigo=null){
	$this->layout = "ajax";
	$a = $this->Cnmd01->findAll($this->condicion()." and cod_tipo_nomina=".$codigo,array('cod_tipo_nomina','denominacion', 'horas_laborables'));
    $this->set("a",$a[0]['Cnmd01']['cod_tipo_nomina']);
    $this->Session->write('tipo_nomina',$codigo);
    echo'<script>';
  			echo'if(document.getElementById("segunda_ventana")){document.getElementById("segunda_ventana").disabled = false; }  ';
    echo'</script>';
}//fin cpcp02_codigo

function denominacion_nomina($codigo){
	$this->layout = "ajax";
	$b = $this->Cnmd01->findAll($this->condicion()." and cod_tipo_nomina=".$codigo,array('cod_tipo_nomina','denominacion'));
	$this->set("b",$b[0]['Cnmd01']['denominacion']);
}//fin cpcp02_denominacion





function buscar_persona($var1=null){
	$this->layout="ajax";
	$this->set("opcion",$var1);
	$this->Session->delete('pista');
	$this->Session->write('pista_opcion', 2);
}//fin function



function buscar_por_pista2($var1=null, $var2=null, $var3=null){
$this->layout="ajax";
$sql_like = "";
$tipo_nomina=   $this->Session->read('tipo_nomina');
                    $cod_presi      =  $this->Session->read('SScodpresi');
					$cod_entidad    =  $this->Session->read('SScodentidad');
					$cod_tipo_inst  =  $this->Session->read('SScodtipoinst');
					$cod_inst       =  $this->Session->read('SScodinst');
					$cod_dep        =  $this->Session->read('SScoddep');
	    if($var3==null){
	    	            $var2 = strtoupper($var2);
						$this->Session->write('pista', $var2);
						$var_like = $var2;
						$sql_like = $this->condicion(). " and (".$this->sql_like_super_busqueda($var_like, $this->Session->read('pista_opcion')).") and cod_tipo_nomina   =  '".$tipo_nomina."'  and (condicion_actividad_ficha!=6 and condicion_actividad_ficha!=7)  ";
						$Tfilas=$this->v_cnmd06_fichas_datos_personales->findCount($sql_like);
						        if($Tfilas!=0){
						        	$pagina=1;
						        	$Tfilas=(int)ceil($Tfilas/100);
						        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
									$this->set('total_paginas',$Tfilas);
									$this->set('pagina_actual',$pagina);
									$this->set('ultimo',$Tfilas);
						     	    $datos_filas=$this->v_cnmd06_fichas_datos_personales->findAll($sql_like,null,"primer_nombre,primer_apellido ASC",100,1,null);
                                    $sql = "";

                                    /*pr($datos_filas);


						                                    foreach($datos_filas as $ve){
						                                         if($sql==""){
						                                         	$sql .= "    a.cedula_identidad = '".$ve["v_cnmd06_fichas_datos_personales"]["cedula_identidad"]."' ";
						                                         }else{
						                                         	$sql .= " or a.cedula_identidad = '".$ve["v_cnmd06_fichas_datos_personales"]["cedula_identidad"]."'  ";
						                                         }
						                                    }//fin foreach
						                                    $dato_a =   $this->datos_personales_super_busqueda->execute("
						                                    SELECT
						                                          a.cod_presi,
																  a.cod_entidad,
																  a.cod_tipo_inst,
																  a.cod_inst,
																  a.cod_dep,
																  a.cod_tipo_nomina,
																  a.cod_cargo,
																  a.cod_ficha,
																  a.cedula_identidad,
																  a.condicion_actividad
														     FROM
														           cnmd06_fichas a,
														           cnmd05        b
														     WHERE
														     	  a.cod_presi         =  '".$cod_presi."'      and
																  a.cod_entidad       =  '".$cod_entidad."'    and
																  a.cod_tipo_inst     =  '".$cod_tipo_inst."'  and
																  a.cod_inst          =  '".$cod_inst."'       and
																  a.cod_dep           =  '".$cod_dep."'        and
																  a.cod_tipo_nomina   =  '".$tipo_nomina."'    and
														          b.cod_presi         =  a.cod_presi           and
																  b.cod_entidad       =  a.cod_entidad         and
																  b.cod_tipo_inst     =  a.cod_tipo_inst       and
																  b.cod_inst          =  a.cod_inst            and
																  b.cod_dep           =  a.cod_dep             and
																  b.cod_tipo_nomina   =  a.cod_tipo_nomina     and
																  b.cod_cargo         =  a.cod_cargo           and ( ".$sql." ) ");

                                                    */

                                    $this->set("datosFILAS",$datos_filas);
							        $this->set('siguiente',$pagina+1);
									$this->set('anterior',$pagina-1);
									$this->bt_nav($Tfilas,$pagina);
						          }else{
						        	$this->set("datosFILAS",'');
						          }
						          //$this->set("dato_a",$dato_a);
            }else{
						$var22 = $this->Session->read('pista');
						$var22 = strtoupper($var22);
						$var_like = $var22;
						$sql_like = $this->condicion(). " and (".$this->sql_like_super_busqueda($var_like, $this->Session->read('pista_opcion')).") and cod_tipo_nomina   =  '".$tipo_nomina."'  and (condicion_actividad_ficha!=6 and condicion_actividad_ficha!=7)   ";
						$Tfilas=$this->v_cnmd06_fichas_datos_personales->findCount($sql_like);
						        if($Tfilas!=0){
						        	$pagina=$var3;
						        	$Tfilas=(int)ceil($Tfilas/100);
						        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
									$this->set('total_paginas',$Tfilas);
									$this->set('pagina_actual',$pagina);
									$this->set('ultimo',$Tfilas);
						     	    $datos_filas=$this->v_cnmd06_fichas_datos_personales->findAll($sql_like,null,"primer_nombre,primer_apellido ASC",100,$pagina,null);
							        $sql = "";
                                               /*


						                                   foreach($datos_filas as $ve){
						                                         if($sql==""){
						                                         	$sql .= "    a.cedula_identidad = '".$ve["v_cnmd06_fichas_datos_personales"]["cedula_identidad"]."' ";
						                                         }else{
						                                         	$sql .= " or a.cedula_identidad = '".$ve["v_cnmd06_fichas_datos_personales"]["cedula_identidad"]."'  ";
						                                         }
						                                    }//fin foreach
											                $dato_a =   $this->datos_personales_super_busqueda->execute("
											                                    SELECT
											                                          a.cod_presi,
																					  a.cod_entidad,
																					  a.cod_tipo_inst,
																					  a.cod_inst,
																					  a.cod_dep,
																					  a.cod_tipo_nomina,
																					  a.cod_cargo,
																					  a.cod_ficha,
																					  a.cedula_identidad,
																					  a.condicion_actividad
																			     FROM
																			           cnmd06_fichas a,
																			           cnmd05        b
																			     WHERE
																			     	  a.cod_presi         =  '".$cod_presi."'      and
																					  a.cod_entidad       =  '".$cod_entidad."'    and
																					  a.cod_tipo_inst     =  '".$cod_tipo_inst."'  and
																					  a.cod_inst          =  '".$cod_inst."'       and
																					  a.cod_dep           =  '".$cod_dep."'        and
																					  a.cod_tipo_nomina   =  '".$tipo_nomina."'    and
																			          b.cod_presi         =  a.cod_presi           and
																					  b.cod_entidad       =  a.cod_entidad         and
																					  b.cod_tipo_inst     =  a.cod_tipo_inst       and
																					  b.cod_inst          =  a.cod_inst            and
																					  b.cod_dep           =  a.cod_dep             and
																					  b.cod_tipo_nomina   =  a.cod_tipo_nomina     and
																					  b.cod_cargo         =  a.cod_cargo           and ( ".$sql." ) ");

                                                   */



					                $this->set("datosFILAS",$datos_filas);
							        $this->set('siguiente',$pagina+1);
									$this->set('anterior',$pagina-1);
									$this->bt_nav($Tfilas,$pagina);
									//$this->set("dato_a",$dato_a);
						          }else{
						        	$this->set("datosFILAS",'');
						          }
                 }//fin else
$this->set("opcion",$var1);
}//fin function













function mostrar_busqueda($var1=null, $var2=null, $pag_num=null){
	$this->layout="ajax";
	$var2 = strtoupper($var2);
	$var_min = strtolower($var2);
	$var_wrap = ucfirst($var_min);
	$this->set('var2', $var2);
	$var =   $this->Session->read('tipo_nomina');
	$cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep;


    $cod_cargo[1]     = "";
    $cod_ficha[1]     = "";
    $fichas        = "";
    $datos_cnmd05  = "";
    $datos_peronal = "";
    $acepta        = "no";
    $cedu          = "";

if($pag_num==null){$pag_num = 1;}



	if($var != null){

      $cont_cargo    =   $this->cnmd06_fichas->findCount($condicion.' and cod_tipo_nomina='.$var.' and cedula_identidad='.$var1.'  '.' and cod_cargo='.$var2);
      $fichas        =   $this->cnmd06_fichas->findAll($condicion.' and cod_tipo_nomina='.$var.' and cedula_identidad='.$var1.'  '.' and cod_cargo='.$var2);
      $fichas_aux    =   $fichas;

$i = 0;

      foreach($fichas_aux as $aux){ $i++;
             $cod_cargo[$i] =  $aux['cnmd06_fichas']['cod_cargo'];
             $cod_ficha[$i] =  $aux['cnmd06_fichas']['cod_ficha'];
      }//fin foreach




if($cod_cargo[$pag_num]!=""){

	 $fichas        =   $this->cnmd06_fichas->findAll($condicion.' and cod_tipo_nomina='.$var.' and cod_cargo='.$cod_cargo[$pag_num].' and cod_ficha='.$cod_ficha[$pag_num].' and cedula_identidad='.$var1.'  ');
	 $datos_peronal =   $this->cnmd06_datos_personales->findAll(' cedula_identidad='.$var1.'  ');
	 $datos_cnmd05  =   $this->v_cnmd05->findAll($condicion.' and cod_tipo_nomina='.$var.' and cod_cargo='.$cod_cargo[$pag_num].' ');
   if($i>=2 && $pag_num!=$i){$acepta="si"; $cedu = $var2; $this->set('pag_num', $pag_num); }
   $this->set('ficha', $fichas);
   $this->set('datos_cnmd05', $datos_cnmd05);
   $this->set('datos_personales', $datos_peronal);
   $this->set('aceptacion', $acepta);
   $this->set('var_2', $var);
   $this->set('cedula', $cedu);

    $sql="select * from v_cnmd05_cargos where ".$condicion.' and cod_tipo_nomina='.$var.' and cod_cargo='.$cod_cargo[$pag_num];
	$datos=$this->v_cnmd05_cargos->execute($sql);
	$this->set('datos',$datos);

$datos_cnmd01 = $this->Cnmd01->findAll($condicion." and cod_tipo_nomina=".$var);
$this->set('frecuencia_pago',$datos_cnmd01[0]['Cnmd01']['frecuencia_pago']);
$this->set('periodo_desde',$datos_cnmd01[0]['Cnmd01']['periodo_desde']);
$this->set('periodo_hasta',$datos_cnmd01[0]['Cnmd01']['periodo_hasta']);




//////////////////////////////////////////////////UBICACIÓN ADMINISTRATIVA///////////////////////////////////////////////
	$cond = "cod_tipo_institucion = ".$cod_tipo_inst." and cod_institucion = ".$cod_inst." and cod_dependencia = ".$cod_dep;

	$cod_dirsup =  $this->cugd02_direccionsuperior->generateList($cond, 'cod_dir_superior ASC', null, '{n}.cugd02_direccionsuperior.cod_dir_superior', '{n}.cugd02_direccionsuperior.denominacion');
	$this->concatena($cod_dirsup, 'cod_dir_superior');

	$cond.=" and cod_dir_superior=".$datos[0][0]['cod_dir_superior'];
	$lista=  $this->cugd02_coordinacion->generateList($cond, 'cod_coordinacion ASC', null, '{n}.cugd02_coordinacion.cod_coordinacion', '{n}.cugd02_coordinacion.denominacion');
    $this->concatena($lista, 'coordinacion');


    $cond.=" and cod_coordinacion=".$datos[0][0]['cod_coordinacion'];
    $lista=  $this->cugd02_secretaria->generateList($cond, 'cod_secretaria ASC', null, '{n}.cugd02_secretaria.cod_secretaria', '{n}.cugd02_secretaria.denominacion');
    $this->concatena($lista, 'secretaria');

    $cond.=" and cod_secretaria=".$datos[0][0]['cod_secretaria'];
    $lista=  $this->cugd02_direccion->generateList($cond, 'cod_direccion ASC', null, '{n}.cugd02_direccion.cod_direccion', '{n}.cugd02_direccion.denominacion');
    $this->concatena($lista, 'direccion');

    $cond.=" and cod_direccion=".$datos[0][0]['cod_direccion'];
    $lista=  $this->cugd02_division->generateList($cond, 'cod_division ASC', null, '{n}.cugd02_division.cod_division', '{n}.cugd02_division.denominacion');
    $this->concatena($lista, 'division');

    $cond.=" and cod_division=".$datos[0][0]['cod_division'];
    $lista=  $this->cugd02_departamento->generateList($cond, 'cod_departamento ASC', null, '{n}.cugd02_departamento.cod_departamento', '{n}.cugd02_departamento.denominacion');
    $this->concatena($lista, 'departamento');

    $cond.=" and cod_departamento=".$datos[0][0]['cod_departamento'];
    $lista=  $this->cugd02_oficina->generateList($cond, 'cod_oficina ASC', null, '{n}.cugd02_oficina.cod_oficina', '{n}.cugd02_oficina.denominacion');
    $this->concatena($lista, 'oficina');

    $this->Session->write('cod_1',$datos[0][0]['cod_dir_superior']);
	$this->Session->write('cod_2',$datos[0][0]['cod_coordinacion']);
	$this->Session->write('cod_3',$datos[0][0]['cod_secretaria']);
	$this->Session->write('cod_4',$datos[0][0]['cod_direccion']);
	$this->Session->write('cod_5',$datos[0][0]['cod_division']);
	$this->Session->write('cod_6',$datos[0][0]['cod_departamento']);
	$this->Session->write('cod_7',$datos[0][0]['cod_oficina']);











}else{
   $this->set('mensajeError', 'La cedula de identidad no existe');
   $this->set('var_2', $var);
   $this->set('cedula', '');
   $this->set('pag_num', $pag_num);
   $this->set('aceptacion', $acepta);


}//fin else




   }else{


        $this->set('resultado', '');

	}//fin else



       $Lista = $this->cnmd03_transacciones->generateList('cod_tipo_transaccion=1 ', 'cod_transaccion ASC', null, '{n}.cnmd03_transacciones.cod_transaccion', '{n}.cnmd03_transacciones.denominacion');
    if($Lista == ""){
        $this->set('lista_transacciones', $Lista);
    }else{
        $this->concatena_tres_digitos($Lista, 'lista_transacciones');
    }//fin else



	$cond_cds ="cod_tipo_institucion=".$this->verifica_SS(3)." and cod_institucion=".$this->verifica_SS(4)." and cod_dependencia=".$this->verifica_SS(5);
   	$cod_dirsup =  $this->cugd02_direccionsuperior->generateList($cond_cds, 'cod_dir_superior ASC', null, '{n}.cugd02_direccionsuperior.cod_dir_superior', '{n}.cugd02_direccionsuperior.denominacion');
    $this->concatena($cod_dirsup, 'cod_dir_superior');





    $cod_estado =  $this->cugd01_estados->generateList("cod_republica=".$cod_presi, 'cod_estado ASC', null, '{n}.cugd01_estados.cod_estado', '{n}.cugd01_estados.denominacion');
    $this->concatena( $cod_estado, 'cod_estado');

	$a=$this->cugd01_estados->execute("select * from cugd90_municipio_defecto where ".$this->condicion());

	if($a!=null){
		$estado=$a[0][0]['cod_estado'];

		$cod_municipio =  $this->cugd01_municipios->generateList("cod_republica=".$cod_presi." and cod_estado=".$estado, 'cod_estado, cod_municipio ASC', null, '{n}.cugd01_municipios.cod_municipio', '{n}.cugd01_municipios.denominacion');
	    $this->concatena( $cod_municipio, 'cod_municipio');

	    $dEstado = $this->cugd01_estados->field('denominacion', 'cod_republica='.$cod_presi.' and cod_estado='.$estado, null);
		$this->set('dEstado', $dEstado);


	    $this->set('estado',$a[0][0]['cod_estado'] );
	    $this->Session->write('codg_1',$a[0][0]['cod_estado']);

		$this->Session->write('codg_2',$a[0][0]['cod_municipio']);
		$this->set('cod_muni',$a[0][0]['cod_municipio']);

		$dmunicipio = $this->cugd01_municipios->field('denominacion', 'cod_republica='.$cod_presi.' and cod_estado='.$estado.' and cod_municipio='.$a[0][0]['cod_municipio'], null);
		$this->set('dmunicipio', $dmunicipio);

		$cod_parroquia =  $this->cugd01_parroquias->generateList("cod_republica=".$cod_presi." and cod_estado=".$estado." and cod_municipio=".$a[0][0]['cod_municipio'], 'cod_parroquia ASC', null, '{n}.cugd01_parroquias.cod_parroquia', '{n}.cugd01_parroquias.denominacion');
	    $this->concatena( $cod_parroquia, 'cod_parroquia');


	}else{
		$estado=$this->Session->read('SScodentidad');

		$cod_municipio =  $this->cugd01_municipios->generateList("cod_republica=".$cod_presi." and cod_estado=".$estado, 'cod_estado, cod_municipio ASC', null, '{n}.cugd01_municipios.cod_municipio', '{n}.cugd01_municipios.denominacion');
	    $this->concatena( $cod_municipio, 'cod_municipio');

	    $dEstado = $this->cugd01_estados->field('denominacion', 'cod_republica='.$cod_presi.' and cod_estado='.$estado, null);
		$this->set('dEstado', $dEstado);


	    $this->set('estado',$this->Session->read('SScodentidad') );
	    $this->Session->write('codg_1',$this->Session->read('SScodentidad'));

		$this->set('cod_muni','');
		$this->set('dmunicipio', '');

		$this->set('cod_parroquia',array());
	}


}//fin function









function cuerpo($var=null){
	$this->layout = "ajax";
}//fin cpcp02_denominacion



function index($var=null, $var_cont=null){
	$this->layout = "ajax";
	$this->data =null;
   	$lista = $this->Cnmd01->generateList($conditions = $this->condicion(), $order = 'cod_tipo_nomina', $limit = null, '{n}.Cnmd01.cod_tipo_nomina', '{n}.Cnmd01.denominacion');
	if($lista !=null){
		$this->concatena($lista, 'cod_tipo_nomina');
	}else{
		$this->set('cod_tipo_nomina','');
	}
}//fin index











function mostrar($select=null,$var=null) {
    $this->layout = "ajax";
if(isset($var) && !empty($var) && $var!=''){
	$cond = "cod_tipo_institucion=".$this->verifica_SS(3)." and cod_institucion=".$this->verifica_SS(4)." and cod_dependencia=".$this->verifica_SS(5);
    switch($select){
		case 'coordinacion':
		   $cod_1 =  $this->Session->read('cod_1');
		   $this->Session->write('cod_2',$var);
		   $cond .=" and cod_dir_superior=".$cod_1." and cod_coordinacion=".$var;
		   $a=  $this->cugd02_coordinacion->findAll($cond);
		   if($a!=null){
		  	  echo "<input type='text' name='data[cnmp05_historial_traslado][cod_div_contable1]' value='".$a[0]['cugd02_coordinacion']['denominacion']."'  id='codigos2' readonly='readonly' class='inputtext' />";
		   }else{
		  	  echo "<input type='text' name='data[cnmp05_historial_traslado][cod_div_contable1]' value=''   readonly='readonly' class='inputtext' />";
		   }


		    echo "<script>";
			    echo "document.getElementById('cod_estado').value='';";
				echo "document.getElementById('deno_estado').value='';";
				echo "document.getElementById('cod_municipio').value='';";
				echo "document.getElementById('deno_municipio').value='';";
				echo "document.getElementById('cod_parroquia').value='';";
				echo "document.getElementById('deno_parroquia').value='';";
				echo "document.getElementById('cod_centropoblado').value='';";
				echo "document.getElementById('deno_centro').value='';";
			echo "</script>";

			echo "<script>";
				echo "document.getElementById('codigos3').value='';";
				echo "document.getElementById('codigos4').value='';";
				echo "document.getElementById('codigos5').value='';";
				echo "document.getElementById('codigos6').value='';";
				echo "document.getElementById('codigos7').value='';";
			echo "</script>";
 		break;
		case 'secretaria':
		  $cod_1 =  $this->Session->read('cod_1');
		  $cod_2 =  $this->Session->read('cod_2');
		  $this->Session->write('cod_3',$var);

		  $cond .=" and cod_dir_superior=".$cod_1." and cod_coordinacion=".$cod_2." and cod_secretaria=".$var;
		  $cond2 =" and cod_dir_superior=".$cod_1." and cod_coordinacion=".$cod_2." and cod_secretaria=".$var;
		  $a=  $this->cugd02_secretaria->findAll($cond);

		    $tipo_nomina   = $this->Session->read('tipo_nomina');
			$cod_presi     = $this->Session->read('SScodpresi');
			$cod_entidad   = $this->Session->read('SScodentidad');
			$cod_tipo_inst = $this->Session->read('SScodtipoinst');
			$cod_inst      = $this->Session->read('SScodinst');
			$cod_dep       = $this->Session->read('SScoddep');
			$condicion     = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep;
		    $sql="select * from v_cnmd05_cargos where ".$condicion.' and cod_tipo_nomina='.$tipo_nomina.'  '.$cond2;
			$datos=$this->v_cnmd05_cargos->execute($sql);

			if(empty($datos[0][0]['cod_estado'])){
				$sql="select * from v_cnmd05_cargos where ".$condicion.' and cod_tipo_nomina='.$tipo_nomina.'  '." and cod_dir_superior=".$cod_1." and cod_coordinacion=".$cod_2." and cod_secretaria=".$var;
			    $datos=$this->v_cnmd05_cargos->execute($sql);
			}

            $datos[0][0]['cod_estado']    = !empty($datos[0][0]['cod_estado'])    ? $datos[0][0]['cod_estado'] : "" ;
            $datos[0][0]['deno_estado']   = !empty($datos[0][0]['deno_estado'])    ? $datos[0][0]['deno_estado'] : "" ;

            $datos[0][0]['cod_municipio']   = !empty($datos[0][0]['cod_municipio'])    ? $datos[0][0]['cod_municipio'] : "" ;
            $datos[0][0]['deno_municipio']  = !empty($datos[0][0]['deno_municipio'])    ? $datos[0][0]['deno_municipio'] : "" ;

            $datos[0][0]['cod_parroquia']   = !empty($datos[0][0]['cod_parroquia'])    ? $datos[0][0]['cod_parroquia'] : "" ;
            $datos[0][0]['deno_parroquia']  = !empty($datos[0][0]['deno_parroquia'])    ? $datos[0][0]['deno_parroquia'] : "" ;

            $datos[0][0]['cod_centro']   = !empty($datos[0][0]['cod_centro'])    ? $datos[0][0]['cod_centro'] : "" ;
            $datos[0][0]['deno_centro']  = !empty($datos[0][0]['deno_centro'])    ? $datos[0][0]['deno_centro'] : "" ;


			$cod_estado     = mascara($datos[0][0]['cod_estado'],2);
			$deno_estado    = $datos[0][0]['deno_estado'];
			$cod_municipio  = mascara($datos[0][0]['cod_municipio'],2);
			$deno_municipio = $datos[0][0]['deno_municipio'];
			$cod_parroquia  = mascara($datos[0][0]['cod_parroquia'],2);
			$deno_parroquia = $datos[0][0]['deno_parroquia'];
			$cod_centro     = mascara($datos[0][0]['cod_centro'],2);
			$deno_centro    = $datos[0][0]['deno_centro'];
			echo "<script>";
			    echo "document.getElementById('cod_estado').value='".$cod_estado."';";
				echo "document.getElementById('deno_estado').value='".$deno_estado."';";
				echo "document.getElementById('cod_municipio').value='".$cod_municipio."';";
				echo "document.getElementById('deno_municipio').value='".$deno_municipio."';";
				echo "document.getElementById('cod_parroquia').value='".$cod_parroquia."';";
				echo "document.getElementById('deno_parroquia').value='".$deno_parroquia."';";
				echo "document.getElementById('cod_centropoblado').value='".$cod_centro."';";
				echo "document.getElementById('deno_centro').value='".$deno_centro."';";
			echo "</script>";

		  if($a!=null){
		  	 echo "<input type='text' name='data[cnmp05_historial_traslado][cod_div_contable2]' value='".$a[0]['cugd02_secretaria']['denominacion']."'  id='codigos3' readonly='readonly'  class='inputtext' />";
		  }else{
		  	 echo "<input type='text' name='data[cnmp05_historial_traslado][cod_div_contable2]' value=''   readonly='readonly' class='inputtext' />";
		  }
			echo "<script>";
				echo "document.getElementById('codigos4').value='';";
				echo "document.getElementById('codigos5').value='';";
				echo "document.getElementById('codigos6').value='';";
				echo "document.getElementById('codigos7').value='';";
			echo "</script>";
 		break;
		case 'direccion':
		  $cod_1 =  $this->Session->read('cod_1');
		  $cod_2 =  $this->Session->read('cod_2');
		  $cod_3 =  $this->Session->read('cod_3');
		  $this->Session->write('cod_4',$var);

		  $cond .=" and cod_dir_superior=".$cod_1." and cod_coordinacion=".$cod_2." and  cod_secretaria=".$cod_3." and cod_direccion=".$var;
		  $cond2 =" and cod_dir_superior=".$cod_1." and cod_coordinacion=".$cod_2." and  cod_secretaria=".$cod_3." and cod_direccion=".$var;
		  $a=  $this->cugd02_direccion->findAll($cond);

		    $tipo_nomina   = $this->Session->read('tipo_nomina');
			$cod_presi     = $this->Session->read('SScodpresi');
			$cod_entidad   = $this->Session->read('SScodentidad');
			$cod_tipo_inst = $this->Session->read('SScodtipoinst');
			$cod_inst      = $this->Session->read('SScodinst');
			$cod_dep       = $this->Session->read('SScoddep');
			$condicion     = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep;
		    $sql="select * from v_cnmd05_cargos where ".$condicion.' and cod_tipo_nomina='.$tipo_nomina.'  '.$cond2;
			$datos=$this->v_cnmd05_cargos->execute($sql);

			if(empty($datos[0][0]['cod_estado'])){
				$sql="select * from v_cnmd05_cargos where ".$condicion.' and cod_tipo_nomina='.$tipo_nomina.'  '." and cod_dir_superior=".$cod_1." and cod_coordinacion=".$cod_2." and cod_secretaria=".$cod_3;
			    $datos=$this->v_cnmd05_cargos->execute($sql);
			}

			$datos[0][0]['cod_estado']    = !empty($datos[0][0]['cod_estado'])    ? $datos[0][0]['cod_estado'] : "" ;
            $datos[0][0]['deno_estado']   = !empty($datos[0][0]['deno_estado'])    ? $datos[0][0]['deno_estado'] : "" ;

            $datos[0][0]['cod_municipio']   = !empty($datos[0][0]['cod_municipio'])    ? $datos[0][0]['cod_municipio'] : "" ;
            $datos[0][0]['deno_municipio']  = !empty($datos[0][0]['deno_municipio'])    ? $datos[0][0]['deno_municipio'] : "" ;

            $datos[0][0]['cod_parroquia']   = !empty($datos[0][0]['cod_parroquia'])    ? $datos[0][0]['cod_parroquia'] : "" ;
            $datos[0][0]['deno_parroquia']  = !empty($datos[0][0]['deno_parroquia'])    ? $datos[0][0]['deno_parroquia'] : "" ;

            $datos[0][0]['cod_centro']   = !empty($datos[0][0]['cod_centro'])    ? $datos[0][0]['cod_centro'] : "" ;
            $datos[0][0]['deno_centro']  = !empty($datos[0][0]['deno_centro'])    ? $datos[0][0]['deno_centro'] : "" ;


			$cod_estado     = mascara($datos[0][0]['cod_estado'],2);
			$deno_estado    = $datos[0][0]['deno_estado'];
			$cod_municipio  = mascara($datos[0][0]['cod_municipio'],2);
			$deno_municipio = $datos[0][0]['deno_municipio'];
			$cod_parroquia  = mascara($datos[0][0]['cod_parroquia'],2);
			$deno_parroquia = $datos[0][0]['deno_parroquia'];
			$cod_centro     = mascara($datos[0][0]['cod_centro'],2);
			$deno_centro    = $datos[0][0]['deno_centro'];
			echo "<script>";
			    echo "document.getElementById('cod_estado').value='".$cod_estado."';";
				echo "document.getElementById('deno_estado').value='".$deno_estado."';";
				echo "document.getElementById('cod_municipio').value='".$cod_municipio."';";
				echo "document.getElementById('deno_municipio').value='".$deno_municipio."';";
				echo "document.getElementById('cod_parroquia').value='".$cod_parroquia."';";
				echo "document.getElementById('deno_parroquia').value='".$deno_parroquia."';";
				echo "document.getElementById('cod_centropoblado').value='".$cod_centro."';";
				echo "document.getElementById('deno_centro').value='".$deno_centro."';";
			echo "</script>";



		   if($a!=null){
		  	 echo "<input type='text' name='data[cnmp05_historial_traslado][cod_div_contable3]' value='".$a[0]['cugd02_direccion']['denominacion']."'  id='codigos4'   readonly='readonly'  class='inputtext' />";
		  }else{
		  	 echo "<input type='text' name='data[cnmp05_historial_traslado][cod_div_contable3]' value=''   readonly='readonly' class='inputtext' />";
		  }
			echo "<script>";
				echo "document.getElementById('codigos5').value='';";
				echo "document.getElementById('codigos6').value='';";
				echo "document.getElementById('codigos7').value='';";
			echo "</script>";
  		break;
		case 'division':
		  $cod_1 =  $this->Session->read('cod_1');
		  $cod_2 =  $this->Session->read('cod_2');
		  $cod_3 =  $this->Session->read('cod_3');
		  $cod_4 =  $this->Session->read('cod_4');
		  $this->Session->write('cod_5',$var);

		  $cond  .=" and cod_dir_superior=".$cod_1." and cod_coordinacion=".$cod_2." and  cod_secretaria=".$cod_3." and cod_direccion=".$cod_4." and cod_division=".$var;
		  $cond2  =" and cod_dir_superior=".$cod_1." and cod_coordinacion=".$cod_2." and  cod_secretaria=".$cod_3." and cod_direccion=".$cod_4." and cod_division=".$var;
		  $a=  $this->cugd02_division->findAll($cond);
		   if($a!=null){
		  	 echo "<input type='text' name='data[cnmp05_historial_traslado][cod_div_contable4]' value='".$a[0]['cugd02_division']['denominacion']."'  id='codigos5'  readonly='readonly'  class='inputtext' />";
		  }else{
		  	 echo "<input type='text' name='data[cnmp05_historial_traslado][cod_div_contable4]' value=''   readonly='readonly' class='inputtext' />";
		  }

		    $tipo_nomina   = $this->Session->read('tipo_nomina');
			$cod_presi     = $this->Session->read('SScodpresi');
			$cod_entidad   = $this->Session->read('SScodentidad');
			$cod_tipo_inst = $this->Session->read('SScodtipoinst');
			$cod_inst      = $this->Session->read('SScodinst');
			$cod_dep       = $this->Session->read('SScoddep');
			$condicion     = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep;
		    $sql="select * from v_cnmd05_cargos where ".$condicion.' and cod_tipo_nomina='.$tipo_nomina.'  '.$cond2;
			$datos=$this->v_cnmd05_cargos->execute($sql);

			if(empty($datos[0][0]['cod_estado'])){
				$sql="select * from v_cnmd05_cargos where ".$condicion.' and cod_tipo_nomina='.$tipo_nomina.'  '." and cod_dir_superior=".$cod_1." and cod_coordinacion=".$cod_2." and cod_secretaria=".$cod_3;
			    $datos=$this->v_cnmd05_cargos->execute($sql);
			}

			$datos[0][0]['cod_estado']    = !empty($datos[0][0]['cod_estado'])    ? $datos[0][0]['cod_estado'] : "" ;
            $datos[0][0]['deno_estado']   = !empty($datos[0][0]['deno_estado'])    ? $datos[0][0]['deno_estado'] : "" ;

            $datos[0][0]['cod_municipio']   = !empty($datos[0][0]['cod_municipio'])    ? $datos[0][0]['cod_municipio'] : "" ;
            $datos[0][0]['deno_municipio']  = !empty($datos[0][0]['deno_municipio'])    ? $datos[0][0]['deno_municipio'] : "" ;

            $datos[0][0]['cod_parroquia']   = !empty($datos[0][0]['cod_parroquia'])    ? $datos[0][0]['cod_parroquia'] : "" ;
            $datos[0][0]['deno_parroquia']  = !empty($datos[0][0]['deno_parroquia'])    ? $datos[0][0]['deno_parroquia'] : "" ;

            $datos[0][0]['cod_centro']   = !empty($datos[0][0]['cod_centro'])    ? $datos[0][0]['cod_centro'] : "" ;
            $datos[0][0]['deno_centro']  = !empty($datos[0][0]['deno_centro'])    ? $datos[0][0]['deno_centro'] : "" ;

			$cod_estado     = mascara($datos[0][0]['cod_estado'],2);
			$deno_estado    = $datos[0][0]['deno_estado'];
			$cod_municipio  = mascara($datos[0][0]['cod_municipio'],2);
			$deno_municipio = $datos[0][0]['deno_municipio'];
			$cod_parroquia  = mascara($datos[0][0]['cod_parroquia'],2);
			$deno_parroquia = $datos[0][0]['deno_parroquia'];
			$cod_centro     = mascara($datos[0][0]['cod_centro'],2);
			$deno_centro    = $datos[0][0]['deno_centro'];
			echo "<script>";
			    echo "document.getElementById('cod_estado').value='".$cod_estado."';";
				echo "document.getElementById('deno_estado').value='".$deno_estado."';";
				echo "document.getElementById('cod_municipio').value='".$cod_municipio."';";
				echo "document.getElementById('deno_municipio').value='".$deno_municipio."';";
				echo "document.getElementById('cod_parroquia').value='".$cod_parroquia."';";
				echo "document.getElementById('deno_parroquia').value='".$deno_parroquia."';";
				echo "document.getElementById('cod_centropoblado').value='".$cod_centro."';";
				echo "document.getElementById('deno_centro').value='".$deno_centro."';";
			echo "</script>";



			echo "<script>";
				echo "document.getElementById('codigos6').value='';";
				echo "document.getElementById('codigos7').value='';";
			echo "</script>";
		break;
		case 'departamento':
		  $cod_1 =  $this->Session->read('cod_1');
		  $cod_2 =  $this->Session->read('cod_2');
		  $cod_3 =  $this->Session->read('cod_3');
		  $cod_4 =  $this->Session->read('cod_4');
		  $cod_5 =  $this->Session->read('cod_5');
		  $this->Session->write('cod_6',$var);

		  $cond .=" and cod_dir_superior=".$cod_1." and cod_coordinacion=".$cod_2." and  cod_secretaria=".$cod_3." and cod_direccion=".$cod_4." and cod_division=".$cod_5." and cod_departamento=".$var;
		  $cond2 =" and cod_dir_superior=".$cod_1." and cod_coordinacion=".$cod_2." and  cod_secretaria=".$cod_3." and cod_direccion=".$cod_4." and cod_division=".$cod_5." and cod_departamento=".$var;
		  $a =  $this->cugd02_departamento->findAll($cond);


		    $tipo_nomina   = $this->Session->read('tipo_nomina');
			$cod_presi     = $this->Session->read('SScodpresi');
			$cod_entidad   = $this->Session->read('SScodentidad');
			$cod_tipo_inst = $this->Session->read('SScodtipoinst');
			$cod_inst      = $this->Session->read('SScodinst');
			$cod_dep       = $this->Session->read('SScoddep');
			$condicion     = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep;
		    $sql="select * from v_cnmd05_cargos where ".$condicion.' and cod_tipo_nomina='.$tipo_nomina.'  '.$cond2;
			$datos=$this->v_cnmd05_cargos->execute($sql);

			if(empty($datos[0][0]['cod_estado'])){
				$sql="select * from v_cnmd05_cargos where ".$condicion.' and cod_tipo_nomina='.$tipo_nomina.'  '." and cod_dir_superior=".$cod_1." and cod_coordinacion=".$cod_2." and cod_secretaria=".$cod_3;
			    $datos=$this->v_cnmd05_cargos->execute($sql);
			}

			$datos[0][0]['cod_estado']    = !empty($datos[0][0]['cod_estado'])    ? $datos[0][0]['cod_estado'] : "" ;
            $datos[0][0]['deno_estado']   = !empty($datos[0][0]['deno_estado'])    ? $datos[0][0]['deno_estado'] : "" ;

            $datos[0][0]['cod_municipio']   = !empty($datos[0][0]['cod_municipio'])    ? $datos[0][0]['cod_municipio'] : "" ;
            $datos[0][0]['deno_municipio']  = !empty($datos[0][0]['deno_municipio'])    ? $datos[0][0]['deno_municipio'] : "" ;

            $datos[0][0]['cod_parroquia']   = !empty($datos[0][0]['cod_parroquia'])    ? $datos[0][0]['cod_parroquia'] : "" ;
            $datos[0][0]['deno_parroquia']  = !empty($datos[0][0]['deno_parroquia'])    ? $datos[0][0]['deno_parroquia'] : "" ;

            $datos[0][0]['cod_centro']   = !empty($datos[0][0]['cod_centro'])    ? $datos[0][0]['cod_centro'] : "" ;
            $datos[0][0]['deno_centro']  = !empty($datos[0][0]['deno_centro'])    ? $datos[0][0]['deno_centro'] : "" ;

			$cod_estado     = mascara($datos[0][0]['cod_estado'],2);
			$deno_estado    = $datos[0][0]['deno_estado'];
			$cod_municipio  = mascara($datos[0][0]['cod_municipio'],2);
			$deno_municipio = $datos[0][0]['deno_municipio'];
			$cod_parroquia  = mascara($datos[0][0]['cod_parroquia'],2);
			$deno_parroquia = $datos[0][0]['deno_parroquia'];
			$cod_centro     = mascara($datos[0][0]['cod_centro'],2);
			$deno_centro    = $datos[0][0]['deno_centro'];
			echo "<script>";
			    echo "document.getElementById('cod_estado').value='".$cod_estado."';";
				echo "document.getElementById('deno_estado').value='".$deno_estado."';";
				echo "document.getElementById('cod_municipio').value='".$cod_municipio."';";
				echo "document.getElementById('deno_municipio').value='".$deno_municipio."';";
				echo "document.getElementById('cod_parroquia').value='".$cod_parroquia."';";
				echo "document.getElementById('deno_parroquia').value='".$deno_parroquia."';";
				echo "document.getElementById('cod_centropoblado').value='".$cod_centro."';";
				echo "document.getElementById('deno_centro').value='".$deno_centro."';";
			echo "</script>";




		   if($a!=null){
		  	 echo "<input type='text' name='data[cnmp05_historial_traslado][cod_div_contable4]' value='".$a[0]['cugd02_departamento']['denominacion']."'  id='codigos6' readonly='readonly'  class='inputtext' />";
		  }else{
		  	 echo "<input type='text' name='data[cnmp05_historial_traslado][cod_div_contable4]' value=''   readonly='readonly' class='inputtext' />";
		  }
			echo "<script>";
				echo "document.getElementById('codigos7').value='';";
			echo "</script>";
		break;
		case 'oficina':
		  $cod_1 =  $this->Session->read('cod_1');
		  $cod_2 =  $this->Session->read('cod_2');
		  $cod_3 =  $this->Session->read('cod_3');
		  $cod_4 =  $this->Session->read('cod_4');
		  $cod_5 =  $this->Session->read('cod_5');
		  $cod_6 =  $this->Session->read('cod_6');
		  $this->Session->write('cod_7',$var);

		  $cond .=" and cod_dir_superior=".$cod_1." and cod_coordinacion=".$cod_2." and  cod_secretaria=".$cod_3." and cod_direccion=".$cod_4." and cod_division=".$cod_5." and cod_departamento=".$cod_6." and cod_oficina=".$var;
		  $cond2 =" and cod_dir_superior=".$cod_1." and cod_coordinacion=".$cod_2." and  cod_secretaria=".$cod_3." and cod_direccion=".$cod_4." and cod_division=".$cod_5." and cod_departamento=".$cod_6." and cod_oficina=".$var;
		  $a =  $this->cugd02_oficina->findAll($cond);


		    $tipo_nomina   = $this->Session->read('tipo_nomina');
			$cod_presi     = $this->Session->read('SScodpresi');
			$cod_entidad   = $this->Session->read('SScodentidad');
			$cod_tipo_inst = $this->Session->read('SScodtipoinst');
			$cod_inst      = $this->Session->read('SScodinst');
			$cod_dep       = $this->Session->read('SScoddep');
			$condicion     = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep;
		    $sql="select * from v_cnmd05_cargos where ".$condicion.' and cod_tipo_nomina='.$tipo_nomina.'  '.$cond2;
			$datos=$this->v_cnmd05_cargos->execute($sql);

			if(empty($datos[0][0]['cod_estado'])){
				$sql="select * from v_cnmd05_cargos where ".$condicion.' and cod_tipo_nomina='.$tipo_nomina.'  '." and cod_dir_superior=".$cod_1." and cod_coordinacion=".$cod_2." and cod_secretaria=".$cod_3;
			    $datos=$this->v_cnmd05_cargos->execute($sql);
			}

			$datos[0][0]['cod_estado']    = !empty($datos[0][0]['cod_estado'])    ? $datos[0][0]['cod_estado'] : "" ;
            $datos[0][0]['deno_estado']   = !empty($datos[0][0]['deno_estado'])    ? $datos[0][0]['deno_estado'] : "" ;

            $datos[0][0]['cod_municipio']   = !empty($datos[0][0]['cod_municipio'])    ? $datos[0][0]['cod_municipio'] : "" ;
            $datos[0][0]['deno_municipio']  = !empty($datos[0][0]['deno_municipio'])    ? $datos[0][0]['deno_municipio'] : "" ;

            $datos[0][0]['cod_parroquia']   = !empty($datos[0][0]['cod_parroquia'])    ? $datos[0][0]['cod_parroquia'] : "" ;
            $datos[0][0]['deno_parroquia']  = !empty($datos[0][0]['deno_parroquia'])    ? $datos[0][0]['deno_parroquia'] : "" ;

            $datos[0][0]['cod_centro']   = !empty($datos[0][0]['cod_centro'])    ? $datos[0][0]['cod_centro'] : "" ;
            $datos[0][0]['deno_centro']  = !empty($datos[0][0]['deno_centro'])    ? $datos[0][0]['deno_centro'] : "" ;

			$cod_estado     = mascara($datos[0][0]['cod_estado'],2);
			$deno_estado    = $datos[0][0]['deno_estado'];
			$cod_municipio  = mascara($datos[0][0]['cod_municipio'],2);
			$deno_municipio = $datos[0][0]['deno_municipio'];
			$cod_parroquia  = mascara($datos[0][0]['cod_parroquia'],2);
			$deno_parroquia = $datos[0][0]['deno_parroquia'];
			$cod_centro     = mascara($datos[0][0]['cod_centro'],2);
			$deno_centro    = $datos[0][0]['deno_centro'];
			echo "<script>";
			    echo "document.getElementById('cod_estado').value='".$cod_estado."';";
				echo "document.getElementById('deno_estado').value='".$deno_estado."';";
				echo "document.getElementById('cod_municipio').value='".$cod_municipio."';";
				echo "document.getElementById('deno_municipio').value='".$deno_municipio."';";
				echo "document.getElementById('cod_parroquia').value='".$cod_parroquia."';";
				echo "document.getElementById('deno_parroquia').value='".$deno_parroquia."';";
				echo "document.getElementById('cod_centropoblado').value='".$cod_centro."';";
				echo "document.getElementById('deno_centro').value='".$deno_centro."';";
			echo "</script>";




		   if($a!=null){
		  	 echo "<input type='text' name='data[cnmp05_historial_traslado][cod_div_contable4]' value='".$a[0]['cugd02_oficina']['denominacion']."'  id='codigos7' readonly='readonly'  class='inputtext' />";
		  }else{
		  	 echo "<input type='text' name='data[cnmp05_historial_traslado][cod_div_contable4]' value=''   readonly='readonly' class='inputtext' />";
		  }
		break;
	}//fin switch
    }else{
      echo "<input type='text' name='data[cnmp05_historial_traslado][cod_div_contable4]' value=''   readonly='readonly' class='inputtext' />";
    }

}//fin mostrar ubicacion administrativa


function select($select=null,$var=null) { //select de ubicacion administrativa
	$this->layout = "ajax";
if(isset($var) && !empty($var) && $var!=''){
    $cond ="cod_tipo_institucion=".$this->verifica_SS(3)." and cod_institucion=".$this->verifica_SS(4)." and cod_dependencia=".$this->verifica_SS(5);
	switch($select){
		case 'coordinacion':
		  $this->set('SELECT','secretaria');
		  $this->set('codigo','coordinacion');
		  $this->set('seleccion','');
		  $this->set('n',2);
		  $this->Session->write('cod_1',$var);
		  	$cond .=" and cod_dir_superior=".$var;
		  $lista=  $this->cugd02_coordinacion->generateList($cond, 'cod_coordinacion ASC', null, '{n}.cugd02_coordinacion.cod_coordinacion', '{n}.cugd02_coordinacion.denominacion');
          if($lista!=null){
          	$this->concatena($lista, 'vector');
          }else{
          	$this->set('vector',array());
          }
		break;
		case 'secretaria':
		  $this->set('SELECT','direccion');
		  $this->set('codigo','secretaria');
		  $this->set('seleccion','');
		  $this->set('n',3);
		  $cod_1 =  $this->Session->read('cod_1');

		  $this->Session->write('cod_2',$var);
		  $cond .=" and cod_dir_superior=".$cod_1." and cod_coordinacion=".$var;
		  $lista=  $this->cugd02_secretaria->generateList($cond, 'cod_secretaria ASC', null, '{n}.cugd02_secretaria.cod_secretaria', '{n}.cugd02_secretaria.denominacion');
          if($lista!=null){
          	$this->concatena($lista, 'vector');
          }else{
          	$this->set('vector',array());
          }
		break;
		case 'direccion':
		  $this->set('SELECT','division');
		  $this->set('codigo','direccion');
		  $this->set('seleccion','');
		  $this->set('n',4);
		  $cod_1 =  $this->Session->read('cod_1');
		  $cod_2 =  $this->Session->read('cod_2');
		  $this->Session->write('cod_3',$var);
		  $cond .=" and cod_dir_superior=".$cod_1." and cod_coordinacion=".$cod_2." and  cod_secretaria=".$var;
		  $lista=  $this->cugd02_direccion->generateList($cond, 'cod_direccion ASC', null, '{n}.cugd02_direccion.cod_direccion', '{n}.cugd02_direccion.denominacion');
          if($lista!=null){
          	$this->concatena($lista, 'vector');
          }else{
          	$this->set('vector',array());
          }
		break;
		case 'division':
		  $this->set('SELECT','departamento');
		  $this->set('codigo','division');
		  $this->set('seleccion','');
		  $this->set('n',5);
		  $cod_1 =  $this->Session->read('cod_1');
		  $cod_2 =  $this->Session->read('cod_2');
		  $cod_3 =  $this->Session->read('cod_3');
		  $this->Session->write('cod_4',$var);

		  $cond .=" and cod_dir_superior=".$cod_1." and cod_coordinacion=".$cod_2." and  cod_secretaria=".$cod_3." and cod_direccion=".$var;
		  $lista=  $this->cugd02_division->generateList($cond, 'cod_division ASC', null, '{n}.cugd02_division.cod_division', '{n}.cugd02_division.denominacion');
          if($lista!=null){
          	$this->concatena($lista, 'vector');
          }else{
          	$this->set('vector',array());
          }
		break;
		case 'departamento':
		  $this->set('SELECT','oficina');
		  $this->set('codigo','departamento');
		  $this->set('seleccion','');
		  $this->set('n',6);
		  $cod_1 =  $this->Session->read('cod_1');
		  $cod_2 =  $this->Session->read('cod_2');
		  $cod_3 =  $this->Session->read('cod_3');
		  $cod_4 =  $this->Session->read('cod_4');
		  $this->Session->write('cod_5',$var);
		  $cond .=" and cod_dir_superior=".$cod_1." and cod_coordinacion=".$cod_2." and  cod_secretaria=".$cod_3." and cod_direccion=".$cod_4." and cod_division=".$var;
		  $lista=  $this->cugd02_departamento->generateList($cond, 'cod_departamento ASC', null, '{n}.cugd02_departamento.cod_departamento', '{n}.cugd02_departamento.denominacion');
          if($lista!=null){
          	$this->concatena($lista, 'vector');
          }else{
          	$this->set('vector',array());
          }
		break;
		case 'oficina':
		  $this->set('SELECT','oficina');
		  $this->set('codigo','oficina');
		  $this->set('seleccion','');
		  $this->set('n',7);
		  $this->set('no','no');
		  $cod_1 =  $this->Session->read('cod_1');
		  $cod_2 =  $this->Session->read('cod_2');
		  $cod_3 =  $this->Session->read('cod_3');
		  $cod_4 =  $this->Session->read('cod_4');
		  $cod_5 =  $this->Session->read('cod_5');
		  $this->Session->write('cod_6',$var);
		  $cond .=" and cod_dir_superior=".$cod_1." and cod_coordinacion=".$cod_2." and  cod_secretaria=".$cod_3." and cod_direccion=".$cod_4." and cod_division=".$cod_5." and cod_departamento=".$var;
		  $lista=  $this->cugd02_oficina->generateList($cond, 'cod_oficina ASC', null, '{n}.cugd02_oficina.cod_oficina', '{n}.cugd02_oficina.denominacion');
          if($lista!=null){
          	$this->concatena($lista, 'vector');
          }else{
          	$this->set('vector',array());
          }
		break;
	}
	}else{
		echo "";
	}
}//fin select ubicacion administrativa

function select2($select=null,$var=null) {//ubicacion geografica
	$this->layout = "ajax";
	$cod_presi = $this->Session->read('SScodpresi');
if(isset($var) && !empty($var) && $var!=''){
    $cond ="cod_republica=".$cod_presi;
	switch($select){
		case 'municipio':
		  $this->set('SELECT','parroquia');
		  $this->set('codigo','municipio');
		  $this->set('seleccion','');
		  $this->set('n',2);
		  $this->Session->write('codg_1',$var);
		  $cond .=" and cod_estado=".$var;
		  $lista=  $this->cugd01_municipios->generateList($cond, 'cod_municipio ASC', null, '{n}.cugd01_municipios.cod_municipio', '{n}.cugd01_municipios.denominacion');
          if($lista!=null){
          	$this->concatena($lista, 'vector');
          }else{
          	$this->set('vector',array());
          }
		break;
		case 'parroquia':
		  $this->set('SELECT','centropoblado');
		  $this->set('codigo','parroquia');
		  $this->set('seleccion','');
		  $this->set('n',3);
		  $cod_1 =  $this->Session->read('codg_1');
		  $this->Session->write('codg_2',$var);
		  $cond .=" and cod_estado=".$cod_1." and cod_municipio=".$var;
		  $lista=  $this->cugd01_parroquias->generateList($cond, 'cod_parroquia ASC', null, '{n}.cugd01_parroquias.cod_parroquia', '{n}.cugd01_parroquias.denominacion');
         if($lista!=null){
          	$this->concatena($lista, 'vector');
          }else{
          	$this->set('vector',array());
          }
		break;
		case 'centropoblado':
		  $this->set('SELECT','centropoblado');
		  $this->set('codigo','centropoblado');
		  $this->set('seleccion','');
		  $this->set('n',4);
		  $this->set('no','no');
		  $cod_1 =  $this->Session->read('codg_1');
		  $cod_2 =  $this->Session->read('codg_2');
		  $this->Session->write('codg_3',$var);
		  $cond .=" and cod_estado=".$cod_1." and cod_municipio=".$cod_2." and  cod_parroquia=".$var;
		  $lista=  $this->cugd01_centropoblados->generateList($cond, 'cod_centro ASC', null, '{n}.cugd01_centropoblados.cod_centro', '{n}.cugd01_centropoblados.denominacion');
          if($lista!=null){
          	$this->concatena($lista, 'vector');
          }else{
          	$this->set('vector',array());
          }
		break;
	}//fin switch
	}else{
		echo "";
	}
}//fin select ubicacion geografica






function mostrar2($select=null,$var=null) {//ubicacion geografica
	$this->layout = "ajax";
	$cod_presi = $this->Session->read('SScodpresi');
if(isset($var) && !empty($var) && $var!=''){
    $cond ="cod_republica=".$cod_presi;
	switch($select){
		case 'estado':
		$this->Session->delete('esta');
		  $this->Session->write('esta',$var);
		  $cond .=" and cod_estado=".$var;
		  $a=  $this->cugd01_estados->findAll($cond);
		   if($a!=null){
		  	 echo "<input type='text' name='data[cnmp05_historial_traslado][cod_div_contable1]' value='".$a[0]['cugd01_estados']['denominacion']."'  id='zona1' readonly='readonly' class='inputtext' />";
		  }else{
		  	 echo "<input type='text' name='data[cnmp05_historial_traslado][cod_div_contable1]' value=''   readonly='readonly' class='inputtext' />";
		  }
		  echo "<script>";
				echo "document.getElementById('zona2').value='';";
				echo "document.getElementById('zona3').value='';";
				echo "document.getElementById('zona4').value='';";
			echo "</script>";
		break;
		case 'municipio':
			$cod_1=$this->Session->read('codg_1');

		    $cond .=" and cod_estado=".$cod_1." and cod_municipio=".$var;
		    $a=  $this->cugd01_municipios->findAll($cond);
		    if($a!=null){
		  	  echo "<input type='text' name='data[cnmp05_historial_traslado][cod_div_contable2]' value='".$a[0]['cugd01_municipios']['denominacion']."'  id='zona2' readonly='readonly' class='inputtext' />";
		    }else{
		  	  echo "<input type='text' name='data[cnmp05_historial_traslado][cod_div_contable2]' value=''   readonly='readonly' class='inputtext' />";
		    }
		    echo "<script>";
				echo "document.getElementById('zona3').value='';";
				echo "document.getElementById('zona4').value='';";
			echo "</script>";
		break;
		case 'parroquia':
			$cod_1=$this->Session->read('codg_1');
		  	$cod_2 =  $this->Session->read('codg_2');

   		    $cond .=" and cod_estado=".$cod_1." and cod_municipio=".$cod_2." and cod_parroquia=".$var;
		    $a=  $this->cugd01_parroquias->findAll($cond);
		    if($a!=null){
		  	   echo "<input type='text' name='data[cnmp05_historial_traslado][cod_div_contable3]' value='".$a[0]['cugd01_parroquias']['denominacion']."'  id='zona3' readonly='readonly' class='inputtext' />";
		    }else{
		  	   echo "<input type='text' name='data[cnmp05_historial_traslado][cod_div_contable3]' value=''   readonly='readonly' class='inputtext' />";
		    }
		    echo "<script>";
				echo "document.getElementById('zona4').value='';";
			echo "</script>";
		break;
		case 'centropoblado':
			$cod_1=$this->Session->read('codg_1');
		  	$cod_2 =  $this->Session->read('codg_2');
		  	$cod_3 =  $this->Session->read('codg_3');

		    $cond .=" and cod_estado=".$cod_1." and cod_municipio=".$cod_2." and  cod_parroquia=".$cod_3." and cod_centro=".$var;
		    $a=  $this->cugd01_centropoblados->findAll($cond);
		    if($a!=null){
		  	  echo "<input type='text' name='data[cnmp05_historial_traslado][cod_div_contable4]' value='".$a[0]['cugd01_centropoblados']['denominacion']."'  id='zona4' readonly='readonly' class='inputtext' />";
		    }else{
		  	  echo "<input type='text' name='data[cnmp05_historial_traslado][cod_div_contable4]' value=''   readonly='readonly' class='inputtext' />";
		    }
		break;
	}//fin switch
	}else{
		echo "<input type='text' name='data[cnmp05_historial_traslado][cod_div_contable5]' value=''   readonly='readonly' class='inputtext' />";
	}
}//fin select ubicacion geografica




function select3($select=null,$var=null) { //select codigos presupuestarios
	$this->layout = "ajax";
if($select!=null && $var!=null){
    $cond =$this->SQLCA();
	switch($select){
		case 'sector':
		  $this->set('SELECT','programa');
		  $this->set('codigo','sector');
		  $this->set('seleccion','');
		  $this->set('n',2);
		  $this->Session->write('ano',$var);
		  $cond .=" and ano=".$var;
		  $lista=  $this->cfpd02_sector->generateList($cond, 'cod_sector ASC', null, '{n}.cfpd02_sector.cod_sector', '{n}.cfpd02_sector.denominacion');
          if($lista!=null){
          	$this->concatena($lista, 'vector');
          }else{
          	$this->set('vector',array());
          }
           echo "<script>";
           		echo "document.getElementById('presupuesto2').value='';";
				echo "document.getElementById('presupuesto3').value='';";
				echo "document.getElementById('presupuesto4').value='';";
				echo "document.getElementById('presupuesto5').value='';";
				echo "document.getElementById('presupuesto6').value='';";
				echo "document.getElementById('presupuesto7').value='';";
				echo "document.getElementById('presupuesto8').value='';";
				echo "document.getElementById('presupuesto9').value='';";
				echo "document.getElementById('presupuesto10').value='';";
				echo "document.getElementById('presupuesto11').value='';";
			echo "</script>";
		break;
		case 'programa':
		  $this->set('SELECT','subprograma');
		  $this->set('codigo','programa');
		  $this->set('seleccion','');
		  $this->set('n',3);
		  $ano =  $this->Session->read('ano');
		  $this->Session->write('dsec',$var);
		  $cond .=" and ano=".$ano." and cod_sector=".$var;
		  $lista=  $this->cfpd02_programa->generateList($cond, 'cod_programa ASC', null, '{n}.cfpd02_programa.cod_programa', '{n}.cfpd02_programa.denominacion');
          if($lista!=null){
          	$this->concatena($lista, 'vector');
          }else{
          	$this->set('vector',array());
          }
		break;
		case 'subprograma':
		  $this->set('SELECT','proyecto');
		  $this->set('codigo','subprograma');
		  $this->set('seleccion','');
		  $this->set('n',4);
		  $ano =  $this->Session->read('ano');
		  $sec =  $this->Session->read('dsec');
		  $this->Session->write('dprog',$var);
		  $cond .=" and ano=".$ano." and cod_sector=".$sec." and cod_programa=".$var;
		  $lista=  $this->cfpd02_sub_prog->generateList($cond, 'cod_sub_prog ASC', null, '{n}.cfpd02_sub_prog.cod_sub_prog', '{n}.cfpd02_sub_prog.denominacion');
          if($lista!=null){
          	$this->concatena($lista, 'vector');
          }else{
          	$this->set('vector',array());
          }
		break;
		case 'proyecto':
		  $this->set('SELECT','actividad');
		  $this->set('codigo','proyecto');
		  $this->set('seleccion','');
		  $this->set('n',5);
		  $ano =  $this->Session->read('ano');
		  $sec =  $this->Session->read('dsec');
		  $prog =  $this->Session->read('dprog');
		  $this->Session->write('dsubp',$var);
		  $cond .=" and ano=".$ano." and cod_sector=".$sec." and cod_programa=".$prog." and cod_sub_prog=".$var;
		  $lista=  $this->cfpd02_proyecto->generateList($cond, 'cod_proyecto ASC', null, '{n}.cfpd02_proyecto.cod_proyecto', '{n}.cfpd02_proyecto.denominacion');
          if($lista!=null){
          	$this->concatena($lista, 'vector');
          }else{
          	$this->set('vector',array());
          }
		break;
		case 'actividad':
		  $this->set('SELECT','partida');
		  $this->set('codigo','actividad');
		  $this->set('seleccion','');
		  $this->set('n',6);
		  $ano =  $this->Session->read('ano');
		  $sec =  $this->Session->read('dsec');
		  $prog =  $this->Session->read('dprog');
		  $subp =  $this->Session->read('dsubp');
		  $this->Session->write('proy',$var);
		  $cond .=" and ano=".$ano." and cod_sector=".$sec." and cod_programa=".$prog." and cod_sub_prog=".$subp." and cod_proyecto=".$var;
		  $lista=  $this->cfpd02_activ_obra->generateList($cond, 'cod_activ_obra ASC', null, '{n}.cfpd02_activ_obra.cod_activ_obra', '{n}.cfpd02_activ_obra.denominacion');
          if($lista!=null){
          	$this->concatena($lista, 'vector');
          }else{
          	$this->set('vector',array());
          }
		break;
		case 'partida':
		  $this->set('SELECT','generica');
		  $this->set('codigo','partida');
		  $this->set('seleccion','');
		  $this->set('n',7);
		  $ano =  $this->Session->read('ano');
		  $sec =  $this->Session->read('dsec');
		  $prog =  $this->Session->read('dprog');
		  $subp =  $this->Session->read('dsubp');
		  $proy =  $this->Session->read('proy');
		  $this->Session->write('actividad',$var);
		  $condicion = $this->condicionNDEP();
		  $cond2 ="ejercicio=".$ano." and cod_grupo=".CE." and cod_partida in(1,3,7)";
		  $lista=  $this->cfpd01_ano_partida->generateList($cond2, 'cod_partida ASC', null, '{n}.cfpd01_ano_partida.cod_partida', '{n}.cfpd01_ano_partida.denominacion');
          if($lista!=null){
          	$this->concatena($lista, 'vector',CE);
          }else{
          	$this->set('vector',array());
          }
		break;
		case 'generica':
		  $this->set('SELECT','especifica');
		  $this->set('codigo','generica');
		  $this->set('seleccion','');
		  $this->set('n',8);
		  $ano =  $this->Session->read('ano');
		  $sec =  $this->Session->read('dsec');
		  $prog =  $this->Session->read('dprog');
		  $subp =  $this->Session->read('dsubp');
		  $proy =  $this->Session->read('proy');
		  $activ = $this->Session->read('actividad');
		  $this->Session->write('cpar',$var);
		  if($var<10){$var = '0'.$var;}
		  $cond2 ="ejercicio=".$ano." and cod_grupo=".CE." and cod_partida=".$var;
		  $lista=  $this->cfpd01_ano_generica->generateList($cond2, 'cod_generica ASC', null, '{n}.cfpd01_ano_generica.cod_generica', '{n}.cfpd01_ano_generica.denominacion');
          if($lista!=null){
          	$this->concatena($lista, 'vector');
          }else{
          	$this->set('vector',array());
          }
 		break;
		case 'especifica':
		  $this->set('SELECT','subespecifica');
		  $this->set('codigo','especifica');
		  $this->set('seleccion','');
		  $this->set('n',9);
		  $ano =  $this->Session->read('ano');
		  $sec =  $this->Session->read('dsec');
		  $prog =  $this->Session->read('dprog');
		  $subp =  $this->Session->read('dsubp');
		  $proy =  $this->Session->read('proy');
		  $activ = $this->Session->read('actividad');
		  $cpar =  $this->Session->read('cpar');
		  $this->Session->write('cgen',$var);
		  $cond2 ="ejercicio=".$ano." and cod_grupo=".CE." and cod_partida=".$cpar." and cod_generica=".$var;
		  $lista = $this->cfpd01_ano_especifica->generateList($cond2, 'cod_especifica ASC', null, '{n}.cfpd01_ano_especifica.cod_especifica', '{n}.cfpd01_ano_especifica.denominacion');
          if($lista!=null){
          	$this->concatena($lista, 'vector');
          }else{
          	$this->set('vector',array());
          }
		break;
		case 'subespecifica':
		  $this->set('SELECT','auxiliar');
		  $this->set('codigo','subespecifica');
		  $this->set('seleccion','');
		  $this->set('n',10);
		  $ano =  $this->Session->read('ano');
		  $sec =  $this->Session->read('dsec');
		  $prog =  $this->Session->read('dprog');
		  $subp =  $this->Session->read('dsubp');
		  $proy =  $this->Session->read('proy');
		  $activ = $this->Session->read('actividad');
		  $cpar =  $this->Session->read('cpar');
		  $cgen =  $this->Session->read('cgen');
		  $this->Session->write('cesp',$var);
		  $cond2 ="ejercicio=".$ano." and cod_grupo=".CE." and cod_partida=".$cpar." and cod_generica=".$cgen." and cod_especifica=".$var;
		  $lista=  $this->cfpd01_ano_sub_espec->generateList($cond2, 'cod_sub_espec ASC', null, '{n}.cfpd01_ano_sub_espec.cod_sub_espec', '{n}.cfpd01_ano_sub_espec.denominacion');

          if($lista!=null){
          	$this->concatena($lista, 'vector');
          }else{
          	$this->set('vector',array());
          }
		break;
		case 'auxiliar':
		  $this->set('SELECT','auxiliar');
		  $this->set('codigo','auxiliar');
		  $this->set('seleccion','');
		  $this->set('n',11);
		  $this->set('no','no');
		  $ano =  $this->Session->read('ano');
		  $sec =  $this->Session->read('dsec');
		  $prog =  $this->Session->read('dprog');
		  $subp =  $this->Session->read('dsubp');
		  $proy =  $this->Session->read('proy');
		  $activ = $this->Session->read('actividad');
		  $cpar =  $this->Session->read('cpar');
		  $cgen =  $this->Session->read('cgen');
		  $cesp =  $this->Session->read('cesp');
		  $this->Session->write('csesp',$var);
		  $cod_partida=CE.mascara($cpar,2);
		  $cond3 =" and cod_partida=".$cod_partida." and cod_generica=".$cgen." and cod_especifica=".$cesp." and cod_sub_espec=".$var;
		  $cond2 =" and ano=".$ano." and cod_sector=".$sec." and cod_programa=".$prog." and cod_sub_prog=".$subp." and cod_proyecto=".$proy." and cod_activ_obra=".$activ;
		  $lista=  $this->cfpd05_auxiliar->generateList($cond." ".$cond2.$cond3, 'cod_auxiliar ASC', null, '{n}.cfpd05_auxiliar.cod_auxiliar', '{n}.cfpd05_auxiliar.denominacion');
            if($lista!=null){
            	$this->concatena($lista, 'vector');
            }else{
            	$this->set('vector',array('0'=>'00'));
            }

		break;
	}//fin wsitch
	}else{
		  $this->set('SELECT','');
		  $this->set('codigo','');
		  $this->set('seleccion','');
		  $this->set('n',11);
		  $this->set('no','no');
		 $this->set('vector',array('0'=>'00'));
	}
}//fin select codigos presupuestarios



function mostrar3($select=null,$var=null) { //mostrar3 codigos presupuestarios
	$this->layout = "ajax";
if(isset($var) && $var!=""){
	if(isset($_SESSION['ano'])){
		$ano =  $this->Session->read('ano');
	}else{
		$ano =$this->ano_ejecucion();
	}

    $cond =$this->SQLCA()." and ano=".$ano;
	switch($select){
		case 'sector':
			  $cond .=" and ano=".$ano." and cod_sector=".$var;
			  $a=  $this->cfpd02_sector->findAll($cond);
			  if($a!=null){
			  	 echo "<input type='text' name='data[cnmp05_historial_traslado][cod_div_contable1]' value='".$a[0]['cfpd02_sector']['denominacion']."'  id='presupuesto2' readonly='readonly' class='inputtext' />";
			  }else{
			  	 echo "<input type='text' name='data[cnmp05_historial_traslado][cod_div_contable1]' value=''   readonly='readonly' class='inputtext' />";
			  }
			  echo "<script>";
					echo "document.getElementById('presupuesto3').value='';";
					echo "document.getElementById('presupuesto4').value='';";
					echo "document.getElementById('presupuesto5').value='';";
					echo "document.getElementById('presupuesto6').value='';";
					echo "document.getElementById('presupuesto7').value='';";
					echo "document.getElementById('presupuesto8').value='';";
					echo "document.getElementById('presupuesto9').value='';";
					echo "document.getElementById('presupuesto10').value='';";
					echo "document.getElementById('presupuesto11').value='';";
			  echo "</script>";
		break;
		case 'programa':
			  $sec =  $this->Session->read('dsec');

			  $cond .=" and cod_sector=".$sec." and cod_programa=".$var;
			  $a=  $this->cfpd02_programa->findAll($cond);
			  if($a!=null){
			  	 echo "<input type='text' name='data[cnmp05_historial_traslado][cod_div_contable1]' value='".$a[0]['cfpd02_programa']['denominacion']."'  id='presupuesto3' readonly='readonly' class='inputtext' />";
			  }else{
			  	 echo "<input type='text' name='data[cnmp05_historial_traslado][cod_div_contable1]' value=''   readonly='readonly' class='inputtext' />";
			  }
			   echo "<script>";
					echo "document.getElementById('presupuesto4').value='';";
					echo "document.getElementById('presupuesto5').value='';";
					echo "document.getElementById('presupuesto6').value='';";
					echo "document.getElementById('presupuesto7').value='';";
					echo "document.getElementById('presupuesto8').value='';";
					echo "document.getElementById('presupuesto9').value='';";
					echo "document.getElementById('presupuesto10').value='';";
					echo "document.getElementById('presupuesto11').value='';";
			   echo "</script>";
		break;
		case 'subprograma':
			  $sec =  $this->Session->read('dsec');
			  $prog =  $this->Session->read('dprog');

			  $cond .=" and cod_sector=".$sec." and cod_programa=".$prog." and cod_sub_prog=".$var;
			  $a=  $this->cfpd02_sub_prog->findAll($cond);
			   if($a!=null){
			  	 echo "<input type='text' name='data[cnmp05_historial_traslado][cod_div_contable1]' value='".$a[0]['cfpd02_sub_prog']['denominacion']."'  id='presupuesto4' readonly='readonly' class='inputtext' />";
			  }else{
			  	 echo "<input type='text' name='data[cnmp05_historial_traslado][cod_div_contable1]' value=''   readonly='readonly' class='inputtext' />";
			  }
			   echo "<script>";
					echo "document.getElementById('presupuesto5').value='';";
					echo "document.getElementById('presupuesto6').value='';";
					echo "document.getElementById('presupuesto7').value='';";
					echo "document.getElementById('presupuesto8').value='';";
					echo "document.getElementById('presupuesto9').value='';";
					echo "document.getElementById('presupuesto10').value='';";
					echo "document.getElementById('presupuesto11').value='';";
			   echo "</script>";
		break;
		case 'proyecto':
			  $var= empty($var) ? 0 :  $var;
			  $sec =  $this->Session->read('dsec');
			  $prog =  $this->Session->read('dprog');
			  $subprog =  $this->Session->read('dsubp');

			  $cond .=" and cod_sector=".$sec." and cod_programa=".$prog." and cod_sub_prog=".$subprog." and cod_proyecto=".$var;
			  if(empty($var)){
			  	      echo "<input type='text' name='data[cnmp05_historial_traslado][cod_div_contable1]' value='N/A'  id='presupuesto5' readonly='readonly' class='inputtext' />";
			  }else{
			  		  $a=  $this->cfpd02_proyecto->findAll($cond);
					  if($a!=null){
					  	    echo "<input type='text' name='data[cnmp05_historial_traslado][cod_div_contable1]' value='".$a[0]['cfpd02_proyecto']['denominacion']."'  id='presupuesto5' readonly='readonly' class='inputtext' />";
					  }else{
					  	    echo "<input type='text' name='data[cnmp05_historial_traslado][cod_div_contable1]' value=''   readonly='readonly' class='inputtext' />";
					  }
			  }
			 echo "<script>";
					echo "document.getElementById('presupuesto6').value='';";
					echo "document.getElementById('presupuesto7').value='';";
					echo "document.getElementById('presupuesto8').value='';";
					echo "document.getElementById('presupuesto9').value='';";
					echo "document.getElementById('presupuesto10').value='';";
					echo "document.getElementById('presupuesto11').value='';";
			 echo "</script>";
		break;
		case 'actividad':
			  $sec =  $this->Session->read('dsec');
			  $prog =  $this->Session->read('dprog');
			  $subprog =  $this->Session->read('dsubp');
			  $proy =  $this->Session->read('proy');

			  $proy= empty($proy) ? 0 :  $proy;
			  $cond .=" and cod_sector=".$sec." and cod_programa=".$prog." and cod_sub_prog=".$subprog." and cod_proyecto=".$proy." and cod_activ_obra=".$var;
			  $a=  $this->cfpd02_activ_obra->findAll($cond);
			  if($a!=null){
			  	 echo "<input type='text' name='data[cnmp05_historial_traslado][cod_div_contable1]' value='".$a[0]['cfpd02_activ_obra']['denominacion']."'  id='presupuesto6' readonly='readonly' class='inputtext' />";
			  }else{
			  	 echo "<input type='text' name='data[cnmp05_historial_traslado][cod_div_contable1]' value=''   readonly='readonly' class='inputtext' />";
			  }
			  echo "<script>";
					echo "document.getElementById('presupuesto7').value='';";
					echo "document.getElementById('presupuesto8').value='';";
					echo "document.getElementById('presupuesto9').value='';";
					echo "document.getElementById('presupuesto10').value='';";
					echo "document.getElementById('presupuesto11').value='';";
			  echo "</script>";
		break;
		case 'partida':
			  $cond2 ="ejercicio=".$ano." and cod_grupo=".CE." and cod_partida=".$var;
			  $a=  $this->cfpd01_ano_partida->findAll($cond2);
			  if($a!=null){
			  	 echo "<input type='text' name='data[cnmp05_historial_traslado][cod_div_contable1]' value='".$a[0]['cfpd01_ano_partida']['denominacion']."'  id='presupuesto7' readonly='readonly' class='inputtext' />";
			  }else{
			  	 echo "<input type='text' name='data[cnmp05_historial_traslado][cod_div_contable1]' value=''   readonly='readonly' class='inputtext' />";
			  }
			  echo "<script>";
					echo "document.getElementById('presupuesto8').value='';";
					echo "document.getElementById('presupuesto9').value='';";
					echo "document.getElementById('presupuesto10').value='';";
					echo "document.getElementById('presupuesto11').value='';";
			  echo "</script>";
		break;
		case 'generica':
			  $dpar=  $this->Session->read('cpar');

			  $cond2 ="ejercicio=".$ano." and cod_grupo=".CE." and cod_partida=".$dpar." and cod_generica=".$var;
			  $a=  $this->cfpd01_ano_generica->findAll($cond2);
			  if($a!=null){
			  	 echo "<input type='text' name='data[cnmp05_historial_traslado][cod_div_contable1]' value='".$a[0]['cfpd01_ano_generica']['denominacion']."'  id='presupuesto8' readonly='readonly' class='inputtext' />";
			  }else{
			  	 echo "<input type='text' name='data[cnmp05_historial_traslado][cod_div_contable1]' value=''   readonly='readonly' class='inputtext' />";
			  }
			  echo "<script>";
				echo "document.getElementById('presupuesto9').value='';";
				echo "document.getElementById('presupuesto10').value='';";
				echo "document.getElementById('presupuesto11').value='';";
			  echo "</script>";
		break;
		case 'especifica':
			   $dpar=  $this->Session->read('cpar');
			   $dgen =  $this->Session->read('cgen');

			   $cond2 ="ejercicio=".$ano." and cod_grupo=".CE." and cod_partida=".$dpar." and cod_generica=".$dgen." and cod_especifica=".$var;
			   $a=  $this->cfpd01_ano_especifica->findAll($cond2);
			   if($a!=null){
			  	 echo "<input type='text' name='data[cnmp05_historial_traslado][cod_div_contable1]' value='".$a[0]['cfpd01_ano_especifica']['denominacion']."'  id='presupuesto9' readonly='readonly' class='inputtext' />";
			   }else{
			  	 echo "<input type='text' name='data[cnmp05_historial_traslado][cod_div_contable1]' value=''   readonly='readonly' class='inputtext' />";
			   }
			   echo "<script>";
					echo "document.getElementById('presupuesto10').value='';";
					echo "document.getElementById('presupuesto11').value='';";
				echo "</script>";
		break;
		case 'subespecifica':
			  $dpar=  $this->Session->read('cpar');
			  $dgen =  $this->Session->read('cgen');
			  $desp =  $this->Session->read('cesp');

			  $cond2 ="ejercicio=".$ano." and cod_grupo=".CE." and cod_partida=".$dpar." and cod_generica=".$dgen." and cod_especifica=".$desp." and cod_sub_espec=".$var;
			  $a=  $this->cfpd01_ano_sub_espec->findAll($cond2);
			  if($a!=null){
			  	 echo "<input type='text' name='data[cnmp05_historial_traslado][cod_div_contable1]' value='".$a[0]['cfpd01_ano_sub_espec']['denominacion']."'  id='presupuesto10' readonly='readonly' class='inputtext' />";
			  }else{
			  	 echo "<input type='text' name='data[cnmp05_historial_traslado][cod_div_contable1]' value=''   readonly='readonly' class='inputtext' />";
			  }
			  echo "<script>";
					echo "document.getElementById('presupuesto11').value='';";
			  echo "</script>";
		break;
		case 'auxiliar':
			  $ano =  $this->Session->read('ano');
			  $sec =  $this->Session->read('dsec');
			  $prog =  $this->Session->read('dprog');
			  $subp =  $this->Session->read('dsubp');
			  $proy =  $this->Session->read('proy');
			  $activ = $this->Session->read('actividad');
			  $cpar =  $this->Session->read('cpar');
			  $cgen =  $this->Session->read('cgen');
			  $cesp =  $this->Session->read('cesp');
			  $dsubesp =  $this->Session->read('csesp');
			  $cod_partida=CE.mascara($cpar,2);
			  $cond3 =" and cod_partida=".$cod_partida." and cod_generica=".$cgen." and cod_especifica=".$cesp." and cod_sub_espec=".$dsubesp." and cod_auxiliar=".$var;
			  $cond2 =" and ano=".$ano." and cod_sector=".$sec." and cod_programa=".$prog." and cod_sub_prog=".$subp." and cod_proyecto=".$proy." and cod_activ_obra=".$activ;

			  $a=  $this->cfpd05_auxiliar->findAll($this->SQLCA()." ".$cond2.$cond3);
			  if($a!=null){
			  	 echo "<input type='text' name='data[cnmp05_historial_traslado][cod_div_contable1]' value='".$a[0]['cfpd05_auxiliar']['denominacion']."'  id='presupuesto11' readonly='readonly' class='inputtext' />";
			  }else{
			  	 echo "<input type='text' name='data[cnmp05_historial_traslado][cod_div_contable1]' value=''   readonly='readonly' class='inputtext' />";
			  }
		break;
	}//fin switch
	}else{
		if($var==0){
			echo "<input type='text' name='data[cnmp05_historial_traslado][cod_div_contable1]' value='N/A'  id='presupuesto5' readonly='readonly' class='inputtext' />";
		}else{
			echo "<input type='text' name='data[cnmp05_historial_traslado][cod_div_contable1]' value=''  id='presupuesto5' readonly='readonly' class='inputtext' />";
		}
	}
}//fin mostrar3 codigos presupuestarios



function mostrar_cod_dir_superior($var=null) {
    $this->layout = "ajax";
    if(isset($var) && !empty($var)){
    	$cond_cds ="cod_tipo_institucion=".$this->verifica_SS(3)." and cod_institucion=".$this->verifica_SS(4)." and cod_dependencia=".$this->verifica_SS(5);
    	$condicion=$cond_cds." and cod_dir_superior=".$var;
    	$this->Session->write('dirsup',$var);
        $a=$this->cugd02_direccionsuperior->findAll($condicion);
         if($a!=null){
		  	 echo "<input type='text' name='data[cnmp05_historial_traslado][cod_div_contable1]' value='".$a[0]['cugd02_direccionsuperior']['denominacion']."' id='codigos1'   readonly='readonly' class='inputtext' />";
		  }else{
		  	 echo "<input type='text' name='data[cnmp05_historial_traslado][cod_div_contable1]' value=''   readonly='readonly' class='inputtext' />";
		  }
			echo "<script>";
				echo "document.getElementById('codigos2').value='';";
				echo "document.getElementById('codigos3').value='';";
				echo "document.getElementById('codigos4').value='';";
				echo "document.getElementById('codigos5').value='';";
				echo "document.getElementById('codigos6').value='';";
				echo "document.getElementById('codigos7').value='';";
			echo "</script>";
    }else{
     echo "<input type='text' name='data[cnmp05_historial_traslado][cod_div_contable1]' value=''   readonly='readonly' class='inputtext' />";
    }

}//fin mostrar cod dir superior



















function guardar(){

$this->layout = "ajax";


            $cod_presi      =  $this->Session->read('SScodpresi');
			$cod_entidad    =  $this->Session->read('SScodentidad');
			$cod_tipo_inst  =  $this->Session->read('SScodtipoinst');
			$cod_inst       =  $this->Session->read('SScodinst');
			$cod_dep        =  $this->Session->read('SScoddep');


            $cod_tipo_nomina    = !empty($this->data['cnmp05_historial_traslado']['cod_tipo_nomina'])    ? $this->data['cnmp05_historial_traslado']['cod_tipo_nomina'] : 0 ;
            $cod_nomina         = !empty($this->data['cnmp05_historial_traslado']['cod_nomina'])         ? $this->data['cnmp05_historial_traslado']['cod_nomina'] : 0 ;
            $cod_cargo          = !empty($this->data['cnmp05_historial_traslado']['cod_cargo'])          ? $this->data['cnmp05_historial_traslado']['cod_cargo'] : 0 ;
            $cod_puesto         = !empty($this->data['cnmp05_historial_traslado']['cod_puesto'])         ? $this->data['cnmp05_historial_traslado']['cod_puesto'] : 0 ;
            $denominacion_clase = !empty($this->data['cnmp05_historial_traslado']['denominacion_clase']) ? $this->data['cnmp05_historial_traslado']['denominacion_clase'] : 0 ;
            $codigo_ficha       = !empty($this->data['cnmp05_historial_traslado']['codigo_ficha'])       ? $this->data['cnmp05_historial_traslado']['codigo_ficha'] : 0 ;
            $cedula_identidad   = !empty($this->data['cnmp05_historial_traslado']['cedula_identidad'])   ? $this->data['cnmp05_historial_traslado']['cedula_identidad'] : 0 ;

            $cod_dir_superior_anterior = !empty($this->data['cnmp05_historial_traslado']['cod_dir_superior_actual']) ? $this->data['cnmp05_historial_traslado']['cod_dir_superior_actual'] : 0 ;
            $cod_coordinacion_anterior = !empty($this->data['cnmp05_historial_traslado']['cod_coordinacion_actual']) ? $this->data['cnmp05_historial_traslado']['cod_coordinacion_actual'] : 0 ;
            $cod_secretaria_anterior   = !empty($this->data['cnmp05_historial_traslado']['cod_secretaria_actual'])   ? $this->data['cnmp05_historial_traslado']['cod_secretaria_actual'] : 0 ;
            $cod_direccion_anterior    = !empty($this->data['cnmp05_historial_traslado']['cod_direccion_actual'])    ? $this->data['cnmp05_historial_traslado']['cod_direccion_actual'] : 0 ;
            $cod_division_anterior     = !empty($this->data['cnmp05_historial_traslado']['cod_division_actual'])     ? $this->data['cnmp05_historial_traslado']['cod_division_actual'] : 0 ;
            $cod_departamento_anterior = !empty($this->data['cnmp05_historial_traslado']['cod_departamento_actual']) ? $this->data['cnmp05_historial_traslado']['cod_departamento_actual'] : 0 ;
            $cod_oficina_anterior      = !empty($this->data['cnmp05_historial_traslado']['cod_oficina_actual'])      ? $this->data['cnmp05_historial_traslado']['cod_oficina_actual'] : 0 ;

            $cod_estado_anterior        = !empty($this->data['cnmp05_historial_traslado']['cod_estado_actual'])        ? $this->data['cnmp05_historial_traslado']['cod_estado_actual'] : 0 ;
            $cod_municipio_anterior     = !empty($this->data['cnmp05_historial_traslado']['cod_municipio_actual'])     ? $this->data['cnmp05_historial_traslado']['cod_municipio_actual'] : 0 ;
            $cod_parroquia_anterior     = !empty($this->data['cnmp05_historial_traslado']['cod_parroquia_actual'])     ? $this->data['cnmp05_historial_traslado']['cod_parroquia_actual'] : 0 ;
            $cod_centropoblado_anterior = !empty($this->data['cnmp05_historial_traslado']['cod_centropoblado_actual']) ? $this->data['cnmp05_historial_traslado']['cod_centropoblado_actual'] : 0 ;


            $cod_dir_superior_actual = !empty($this->data['cnmp05_historial_traslado']['cod_dir_superior']) ? $this->data['cnmp05_historial_traslado']['cod_dir_superior'] : 0 ;
            $cod_coordinacion_actual = !empty($this->data['cnmp05_historial_traslado']['cod_coordinacion']) ? $this->data['cnmp05_historial_traslado']['cod_coordinacion'] : 0 ;
            $cod_secretaria_actual   = !empty($this->data['cnmp05_historial_traslado']['cod_secretaria'])   ? $this->data['cnmp05_historial_traslado']['cod_secretaria'] : 0 ;
            $cod_direccion_actual    = !empty($this->data['cnmp05_historial_traslado']['cod_direccion'])    ? $this->data['cnmp05_historial_traslado']['cod_direccion'] : 0 ;
            $cod_division_actual     = !empty($this->data['cnmp05_historial_traslado']['cod_division'])     ? $this->data['cnmp05_historial_traslado']['cod_division'] : 0 ;
            $cod_departamento_actual = !empty($this->data['cnmp05_historial_traslado']['cod_departamento']) ? $this->data['cnmp05_historial_traslado']['cod_departamento'] : 0 ;
            $cod_oficina_actual      = !empty($this->data['cnmp05_historial_traslado']['cod_oficina'])      ? $this->data['cnmp05_historial_traslado']['cod_oficina'] : 0 ;

            $cod_estado_actual        = !empty($this->data['cnmp05_historial_traslado']['cod_estado'])        ? $this->data['cnmp05_historial_traslado']['cod_estado'] : 0 ;
            $cod_municipio_actual     = !empty($this->data['cnmp05_historial_traslado']['cod_municipio'])     ? $this->data['cnmp05_historial_traslado']['cod_municipio'] : 0 ;
            $cod_parroquia_actual     = !empty($this->data['cnmp05_historial_traslado']['cod_parroquia'])     ? $this->data['cnmp05_historial_traslado']['cod_parroquia'] : 0 ;
            $cod_centropoblado_actual = !empty($this->data['cnmp05_historial_traslado']['cod_centropoblado']) ? $this->data['cnmp05_historial_traslado']['cod_centropoblado'] : 0 ;


            $fecha_traslado = !empty($this->data['cnmp05_historial_traslado']['fecha_traslado']) ? cambiar_formato_fecha($this->data['cnmp05_historial_traslado']['fecha_traslado']) : cambiar_formato_fecha(date('d/m/Y')) ;

    $sql  = "UPDATE cnmd05 SET  cod_dir_superior='".$cod_dir_superior_actual."', cod_coordinacion='".$cod_coordinacion_actual."',  cod_secretaria='".$cod_secretaria_actual."', cod_direccion='".$cod_direccion_actual."', cod_division='".$cod_division_actual."', cod_departamento='".$cod_departamento_actual."', cod_oficina='".$cod_oficina_actual."', cod_estado='".$cod_estado_actual."',  cod_municipio='".$cod_municipio_actual."', cod_parroquia='".$cod_parroquia_actual."', cod_centro='".$cod_centropoblado_actual."'   WHERE ".$this->condicion()." and cod_tipo_nomina='".$cod_tipo_nomina."' and cod_cargo='".$cod_cargo."'";
	$sw = $this->cnmd05->execute($sql);

	   if($sw > 1){

	   	        $campos  = "cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, cod_cargo, cod_ficha, cod_dir_superior_anterior, cod_coordinacion_anterior, cod_secretaria_anterior, cod_direccion_anterior, cod_division_anterior, cod_departamento_anterior, cod_oficina_anterior, cod_dir_superior_actual, cod_coordinacion_actual, cod_secretaria_actual, cod_direccion_actual, cod_division_actual, cod_departamento_actual, cod_oficina_actual, fecha_traslado ";
				$values  = " '".$cod_presi."', '".$cod_entidad."', '".$cod_tipo_inst."', '".$cod_inst."', '".$cod_dep."', '".$cod_tipo_nomina."', '".$cod_cargo."', '".$codigo_ficha."', '".$cod_dir_superior_anterior."', '".$cod_coordinacion_anterior."', '".$cod_secretaria_anterior."', '".$cod_direccion_anterior."', '".$cod_division_anterior."', '".$cod_departamento_anterior."', '".$cod_oficina_anterior."', '".$cod_dir_superior_actual."', '".$cod_coordinacion_actual."', '".$cod_secretaria_actual."', '".$cod_direccion_actual."', '".$cod_division_actual."', '".$cod_departamento_actual."', '".$cod_oficina_actual."', '".$fecha_traslado."'   ";
				$sql_1 = "INSERT INTO  cnmd05_historial_traslado (".$campos.")  VALUES  ( ".$values.")  ";

			    $sw1                     = $this->cnmd05_historial_traslado->execute($sql_1);
			    if($sw1 > 1){
			           $this->cnmd05_historial_traslado->execute("COMMIT;");
				       $this->set('Message_existe', 'LOS DATOS FUERON GUARDADOS');
				}else{
					   $this->cnmd05_historial_traslado->execute("ROLLBACK;");
				       $this->set('errorMessage', 'LOS DATOS NO FUERON GUARDADOS');
				}//fin else
		}else{
			   $this->cnmd05_historial_traslado->execute("ROLLBACK;");
		       $this->set('errorMessage', 'LOS DATOS NO FUERON GUARDADOS');
		}//fin else



$this->index();
$this->render("index");


}//fin function






function consulta_1($pagina=null){

$this->layout = "ajax";

	$this->data =null;
   	$lista = $this->Cnmd01->generateList($conditions = $this->condicion(), $order = 'cod_tipo_nomina', $limit = null, '{n}.Cnmd01.cod_tipo_nomina', '{n}.Cnmd01.denominacion');
	if($lista !=null){
		$this->concatena($lista, 'cod_tipo_nomina');
	}else{
		$this->set('cod_tipo_nomina','');
	}

}//fin function



function consulta_2($pagina=null){

$this->layout = "ajax";

}//fin function




function consulta_3($pagina=null){
$this->layout = "ajax";
	$this->set("opcion",$pagina);
	$this->Session->delete('pista');
	$this->Session->write('pista_opcion', 2);
}//fin function



function consulta_4($var1=null, $var2=null, $var3=null){
$this->layout="ajax";
$sql_like = "";
$tipo_nomina=   $this->Session->read('tipo_nomina');
                    $cod_presi      =  $this->Session->read('SScodpresi');
					$cod_entidad    =  $this->Session->read('SScodentidad');
					$cod_tipo_inst  =  $this->Session->read('SScodtipoinst');
					$cod_inst       =  $this->Session->read('SScodinst');
					$cod_dep        =  $this->Session->read('SScoddep');
	    if($var3==null){
	    	            $var2 = strtoupper($var2);
						$this->Session->write('pista', $var2);
						$var_like = $var2;
						$sql_like = $this->condicion(). " and (".$this->sql_like_super_busqueda($var_like, $this->Session->read('pista_opcion')).") and cod_tipo_nomina   =  '".$tipo_nomina."'    ";
						$Tfilas=$this->v_cnmd05_historial_traslado->findCount($sql_like);
						        if($Tfilas!=0){
						        	$pagina=1;
						        	$Tfilas=(int)ceil($Tfilas/100);
						        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
									$this->set('total_paginas',$Tfilas);
									$this->set('pagina_actual',$pagina);
									$this->set('ultimo',$Tfilas);
						     	    $datos_filas=$this->v_cnmd05_historial_traslado->findAll($sql_like,null,"primer_nombre,primer_apellido, secuencia ASC",100,1,null);
                                    $sql = "";

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
						$var_like = $var22;
						$sql_like = $this->condicion(). " and (".$this->sql_like_super_busqueda($var_like, $this->Session->read('pista_opcion')).") and cod_tipo_nomina   =  '".$tipo_nomina."'    ";
						$Tfilas=$this->v_cnmd05_historial_traslado->findCount($sql_like);
						        if($Tfilas!=0){
						        	$pagina=$var3;
						        	$Tfilas=(int)ceil($Tfilas/100);
						        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
									$this->set('total_paginas',$Tfilas);
									$this->set('pagina_actual',$pagina);
									$this->set('ultimo',$Tfilas);
						     	    $datos_filas=$this->v_cnmd05_historial_traslado->findAll($sql_like,null,"primer_nombre,primer_apellido, secuencia ASC",100,$pagina,null);
							        $sql = "";

					                $this->set("datosFILAS",$datos_filas);
							        $this->set('siguiente',$pagina+1);
									$this->set('anterior',$pagina-1);
									$this->bt_nav($Tfilas,$pagina);
									//$this->set("dato_a",$dato_a);
						          }else{
						        	$this->set("datosFILAS",'');
						          }
                 }//fin else
$this->set("opcion",$var1);
}//fin function







function consulta_5($var1=null, $var2=null, $var3=null, $var4=null, $var5=null, $pag_num=null){
	$this->layout="ajax";
	$var2 = strtoupper($var2);
	$var_min = strtolower($var2);
	$var_wrap = ucfirst($var_min);
	$this->set('var2', $var2);
	$var =   $this->Session->read('tipo_nomina');
	$cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep;



	 $fichas        =   $this->cnmd06_fichas->findAll($condicion.' and cod_tipo_nomina='.$var1.' and cod_cargo='.$var2.' and cod_ficha='.$var3.' and cedula_identidad='.$var4.'  ');
	 $datos_peronal =   $this->cnmd06_datos_personales->findAll(' cedula_identidad='.$var4.'  ');
	 $datos_cnmd05  =   $this->v_cnmd05->findAll($condicion.' and cod_tipo_nomina='.$var1.' and cod_cargo='.$var2.' ');

   $this->set('ficha', $fichas);
   $this->set('datos_cnmd05', $datos_cnmd05);
   $this->set('datos_personales', $datos_peronal);
   $this->set('cedula', $var4);


    $sql   = "select * from v_cnmd05_historial_traslado where ".$condicion." and cod_tipo_nomina='".$var1."' and cod_cargo='".$var2."' and cod_ficha='".$var3."' and cedula_identidad='".$var4."' and secuencia='".$var5."' ";
	$datos = $this->v_cnmd05_cargos->execute($sql);
	$this->set('datos',$datos);



}//fin function





















function reporte_1($var=null){
	$cod_presi     = $this->Session->read('SScodpresi');
	$cod_entidad   = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst      = $this->Session->read('SScodinst');
	$cod_dep       = $this->Session->read('SScoddep');
		      if($var==1){ $this->layout="ajax";
		      	$lista = $this->Cnmd01->generateList($conditions = $this->condicion(), $order = 'cod_tipo_nomina', $limit = null, '{n}.Cnmd01.cod_tipo_nomina', '{n}.Cnmd01.denominacion');
				if($lista !=null){
					$this->concatena($lista, 'cod_tipo_nomina');
				}else{
					$this->set('cod_tipo_nomina','');
				}
		}else if($var==2){
                $this->layout="pdf";
	            $cod_tipo_nomina    = !empty($this->data['cnmp05_historial_traslado']['cod_tipo_nomina'])   ? $this->data['cnmp05_historial_traslado']['cod_tipo_nomina'] : 0 ;
	            $opcion_busqueda1   = !empty($this->data['cnmp05_historial_traslado']['opcion_busqueda1'])  ? $this->data['cnmp05_historial_traslado']['opcion_busqueda1'] : 0 ;
	            $opcion_busqueda2   = !empty($this->data['cnmp05_historial_traslado']['opcion_busqueda2'])  ? $this->data['cnmp05_historial_traslado']['opcion_busqueda2'] : 0 ;
	            $cedula_identidad   = !empty($this->data['cnmp05_historial_traslado']['cedula_identidad'])  ? $this->data['cnmp05_historial_traslado']['cedula_identidad'] : 0 ;
	            $fecha_desde        = !empty($this->data['cnmp05_historial_traslado']['fecha_desde'])       ? $this->data['cnmp05_historial_traslado']['fecha_desde'] : 0 ;
	            $fecha_hasta        = !empty($this->data['cnmp05_historial_traslado']['fecha_hasta'])       ? $this->data['cnmp05_historial_traslado']['fecha_hasta'] : 0 ;
                $sql_1 = "";
                $sql_2 = "";
                if($opcion_busqueda1==2){
                                          $sql_1 = " and cedula_identidad='".$cedula_identidad."'  ";
                }
                if($opcion_busqueda2==2 && $fecha_desde!=0 && $fecha_hasta!=0){
                                          $sql_2 = " and fecha_traslado between '$fecha_desde' and '$fecha_hasta' ";
                }
                $sql    = $this->condicion()." and cod_tipo_nomina='".$cod_tipo_nomina."' ".$sql_1." ".$sql_2;
                $datos  = $this->v_cnmd05_historial_traslado->findAll($sql, null," cod_tipo_nomina, cod_cargo, cod_ficha, cedula_identidad, fecha_traslado ASC");
                $this->set('datos',$datos);

                $this->set('opcion_busqueda2',$opcion_busqueda2);
                $this->set('fecha_desde',$fecha_desde);
                $this->set('fecha_hasta',$fecha_hasta);

		}//fin if
$this->set('opcion',$var);
}//fin function












function funcion_1($var=null){

    $cod_presi     = $this->Session->read('SScodpresi');
	$cod_entidad   = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst      = $this->Session->read('SScodinst');
	$cod_dep       = $this->Session->read('SScoddep');

    $this->layout="ajax";

$url                  =  "/cnmp05_historial_traslado/funcion_3/1";
$width_aux            =  "750px";
$height_aux           =  "400px";
$title_aux            =  "Buscar";
$resizable_aux        =  false;
$maximizable_aux      =  false;
$minimizable_aux      =  false;
$closable_aux         =  false;


	if($var==2){
	         echo"<script>";
	           echo  "codigo_ventana('".$url."', '".$width_aux."', '".$height_aux."', '".$title_aux."', '".$resizable_aux."', '".$maximizable_aux."', '".$minimizable_aux."', '".$closable_aux."')";
	         echo"</script>";
    	}else{
              echo"<script>";
               echo  " Windows.close(document.getElementById('capa_ventana').value)";
              echo"</script>";
	}//fin else


	          echo"<script>";
               echo  " document.getElementById('cedula_id').value='';  ";
              echo"</script>";

$this->set('opcion',$var);

}//fin function













function funcion_2($var=null){

    $cod_presi     = $this->Session->read('SScodpresi');
	$cod_entidad   = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst      = $this->Session->read('SScodinst');
	$cod_dep       = $this->Session->read('SScoddep');

    $this->layout="ajax";

          if($var==1){

    }else if($var==2){

    }//fin if

$this->set('opcion',$var);

}//fin function







function funcion_3($pagina=null){
$this->layout = "ajax";
	$this->set("opcion",$pagina);
	$this->Session->delete('pista');
	$this->Session->write('pista_opcion', 2);
}//fin function



function funcion_4($var1=null, $var2=null, $var3=null){
$this->layout="ajax";
$sql_like = "";
$tipo_nomina=   $this->Session->read('tipo_nomina');

if($tipo_nomina==""){$tipo_nomina=0;}

                    $cod_presi      =  $this->Session->read('SScodpresi');
					$cod_entidad    =  $this->Session->read('SScodentidad');
					$cod_tipo_inst  =  $this->Session->read('SScodtipoinst');
					$cod_inst       =  $this->Session->read('SScodinst');
					$cod_dep        =  $this->Session->read('SScoddep');
	    if($var3==null){
	    	            $var2 = strtoupper($var2);
						$this->Session->write('pista', $var2);
						$var_like = $var2;
						$sql_like = $this->condicion(). " and (".$this->sql_like_super_busqueda($var_like, $this->Session->read('pista_opcion')).") and cod_tipo_nomina   =  '".$tipo_nomina."'    ";
						$Tfilas=$this->v_cnmd05_historial_traslado_g->findCount($sql_like);
						        if($Tfilas!=0){
						        	$pagina=1;
						        	$Tfilas=(int)ceil($Tfilas/100);
						        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
									$this->set('total_paginas',$Tfilas);
									$this->set('pagina_actual',$pagina);
									$this->set('ultimo',$Tfilas);
						     	    $datos_filas=$this->v_cnmd05_historial_traslado_g->findAll($sql_like,null,"primer_nombre,primer_apellido ASC",100,1,null);
                                    $sql = "";

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
						$var_like = $var22;
						$sql_like = $this->condicion(). " and (".$this->sql_like_super_busqueda($var_like, $this->Session->read('pista_opcion')).") and cod_tipo_nomina   =  '".$tipo_nomina."'    ";
						$Tfilas=$this->v_cnmd05_historial_traslado_g->findCount($sql_like);
						        if($Tfilas!=0){
						        	$pagina=$var3;
						        	$Tfilas=(int)ceil($Tfilas/100);
						        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
									$this->set('total_paginas',$Tfilas);
									$this->set('pagina_actual',$pagina);
									$this->set('ultimo',$Tfilas);
						     	    $datos_filas=$this->v_cnmd05_historial_traslado_g->findAll($sql_like,null,"primer_nombre,primer_apellido ASC",100,$pagina,null);
							        $sql = "";

					                $this->set("datosFILAS",$datos_filas);
							        $this->set('siguiente',$pagina+1);
									$this->set('anterior',$pagina-1);
									$this->bt_nav($Tfilas,$pagina);
									//$this->set("dato_a",$dato_a);
						          }else{
						        	$this->set("datosFILAS",'');
						          }
                 }//fin else
$this->set("opcion",$var1);
}//fin function





function funcion_5($cedula=null){
$this->layout = "ajax";

              echo"<script>";
               echo  " document.getElementById('cedula_id').value='".$cedula."';  ";
              echo"</script>";


}//fin function










}//fin class
?>
