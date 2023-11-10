<?php

 class Cnmp07ConsultaController extends AppController{

	var $name = 'cnmp07_consulta';
	var $uses = array('cnmd07_transacciones_actuales', 'cnmd06_fichas', 'Cnmd01', 'cnmd06_datos_personales', 'cnmd03_transacciones','cnmd09_frecuencia',
                      'v_cnmd05', 'ccfd03_instalacion', 'ccfd04_cierre_mes', "datos_personales_super_busqueda", "cnmd05", "v_cnmd06_fichas_datos_personales",'v_cnmd07_transacciones_actuales_frecuencias2');
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

	$cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$modulo = $this->Session->read('Modulo');

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
		foreach($vector1 as $x => $y){$cod[$x] = $this->zero($x).' - '.$y;}
		$this->set($nomVar, $cod);
	}//fin if
}//fin concatena






function buscar_persona($var1=null){
	$this->layout="ajax";
	$this->set("opcion",$var1);
	$this->Session->delete('pista');
	$this->Session->write('pista_opcion', 2);
}//fin function

function buscar_por_pista2($var1=null, $var2=null, $var3=null){
$this->layout="ajax";
$var_like = "";
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
						$sql_like = $this->sql_like_super_busqueda($var_like, $this->Session->read('pista_opcion'));
				    	$Tfilas=$this->datos_personales_super_busqueda->findCount($sql_like);
						        if($Tfilas!=0){
						        	$pagina=1;
						        	$Tfilas=(int)ceil($Tfilas/100);
						        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
									$this->set('total_paginas',$Tfilas);
									$this->set('pagina_actual',$pagina);
									$this->set('ultimo',$Tfilas);
						     	    $datos_filas=$this->datos_personales_super_busqueda->findAll($sql_like,null,"primer_nombre,primer_apellido ASC",100,1,null);
                                    $sql = "";
                                    foreach($datos_filas as $ve){
                                         if($sql==""){
                                         	$sql .= "    a.cedula_identidad = '".$ve["datos_personales_super_busqueda"]["cedula_identidad"]."' ";
                                         }else{
                                         	$sql .= " or a.cedula_identidad = '".$ve["datos_personales_super_busqueda"]["cedula_identidad"]."'  ";
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



                                    $this->set("datosFILAS",$datos_filas);
							        $this->set('siguiente',$pagina+1);
									$this->set('anterior',$pagina-1);
									$this->bt_nav($Tfilas,$pagina);
						          }else{
						        	$this->set("datosFILAS",'');
						          }
						          $this->set("dato_a",$dato_a);
            }else{
						$var22 = $this->Session->read('pista');
						$var22 = strtoupper($var22);
						$var_like = $var22;
						$sql_like = $this->sql_like_super_busqueda($var_like, $this->Session->read('pista_opcion'));
						$Tfilas=$this->datos_personales_super_busqueda->findCount($sql_like);
						        if($Tfilas!=0){
						        	$pagina=$var3;
						        	$Tfilas=(int)ceil($Tfilas/100);
						        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
									$this->set('total_paginas',$Tfilas);
									$this->set('pagina_actual',$pagina);
									$this->set('ultimo',$Tfilas);
						     	    $datos_filas=$this->datos_personales_super_busqueda->findAll($sql_like,null,"primer_nombre,primer_apellido ASC",100,$pagina,null);
							        $sql = "";
                                    foreach($datos_filas as $ve){
                                         if($sql==""){
                                         	$sql .= "    a.cedula_identidad = '".$ve["datos_personales_super_busqueda"]["cedula_identidad"]."' ";
                                         }else{
                                         	$sql .= " or a.cedula_identidad = '".$ve["datos_personales_super_busqueda"]["cedula_identidad"]."'  ";
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
					                $this->set("datosFILAS",$datos_filas);
							        $this->set('siguiente',$pagina+1);
									$this->set('anterior',$pagina-1);
									$this->bt_nav($Tfilas,$pagina);
									$this->set("dato_a",$dato_a);
						          }else{
						        	$this->set("datosFILAS",'');
						          }
                 }//fin else
$this->set("opcion",$var1);
}//fin function




 function index($var=null){

  $this->layout = "ajax";

  $this->Session->delete('tipo_nomina');


    $cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep;



if($var != null && $var=='1' || $var == '0'){
		$this->set('opc', $var);
		if($var == 0){
			$this->set('enabled','READONLY');
		}else{
			$this->set('enabled','');
		}
		//echo "gloria a Dios ".$var;
	}else{
		$this->set('opc', '1');
		$this->set('enabled','');
	}
 	//$Lista = $this->v_cnmd05->generateList($condicion, 'cod_tipo_nomina ASC', null, '{n}.v_cnmd05.cod_tipo_nomina', '{n}.v_cnmd05.tipo_nomina');
   //	$this->concatena($Lista, 'cod_tipo_nomina');


   	$lista = $this->Cnmd01->generateList($this->SQLCA()." AND status_nomina IN (0,1)", $order = 'cod_tipo_nomina', $limit = null, '{n}.Cnmd01.cod_tipo_nomina', '{n}.Cnmd01.denominacion');
	$this->concatenaN($lista, 'cod_tipo_nomina');



   	$this->set('cod_puesto','');

}///fin function





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

$datos_cnmd01 = $this->Cnmd01->findAll($condicion." and cod_tipo_nomina=".$var);
$this->set('frecuencia_pago',$datos_cnmd01[0]['Cnmd01']['frecuencia_pago']);
$this->set('periodo_desde',$datos_cnmd01[0]['Cnmd01']['periodo_desde']);
$this->set('periodo_hasta',$datos_cnmd01[0]['Cnmd01']['periodo_hasta']);

}else{
   $this->set('mensajeError', 'La cedula de identidad no existe');
   $this->set('var_2', $var);
   $this->set('cedula', '');
   $this->set('pag_num', $pag_num);
   $this->set('aceptacion', $acepta);

    echo'<script>';
            echo'document.getElementById("monto_origina_deuda").readOnly = true;   ';
            echo'document.getElementById("cantidad_original_deuda").readOnly = true;  ';
            echo'document.getElementById("cantidad_de_cuotas_canceladas").readOnly = true;  ';
            echo'document.getElementById("cantidad_de_cuotas_cancelar").readOnly = true;  ';
			echo'document.getElementById("cuotas_cancela").readOnly = true; ';
			echo'document.getElementById("monto_cuotas_a_cancelar").readOnly = true; ';

			echo'document.getElementById("monto_origina_deuda").value = "0,00"; ';
			echo'document.getElementById("saldo").value = "0,00"; ';
			echo'document.getElementById("monto_cuotas_a_cancelar").value = "0,00"; ';

			echo'document.getElementById("cantidad_original_deuda").value = "0";   ';
            echo'document.getElementById("cantidad_de_cuotas_canceladas").value = "0";  ';
            echo'document.getElementById("cantidad_de_cuotas_cancelar").value = "0";   ';
			echo'document.getElementById("cuotas_cancela").value = "1";  ';
			echo'document.getElementById("modificar").disabled = true; ';
            echo'document.getElementById("guardar").disabled = true; ';
            echo'document.getElementById("eliminar").disabled = true; ';


			echo'document.getElementById("saldo").readOnly = true; ';

			 echo'document.getElementById("username").innerHTML = "'.$_SESSION['nom_usuario'].'"; ';
     echo'</script>';


}//fin else




   }else{

        echo'<script>';
			echo'document.getElementById("cod_cargo").value = ""; ';
  			echo'document.getElementById("cod_puesto").value = "";';
  			echo'document.getElementById("deno_puesto").value = "";';

  			echo'document.getElementById("monto_origina_deuda").readOnly = true;   ';
            echo'document.getElementById("cantidad_original_deuda").readOnly = true;  ';
            echo'document.getElementById("cantidad_de_cuotas_canceladas").readOnly = true;  ';
            echo'document.getElementById("cantidad_de_cuotas_cancelar").readOnly = true;  ';
			echo'document.getElementById("cuotas_cancela").readOnly = true; ';
			echo'document.getElementById("monto_cuotas_a_cancelar").readOnly = true; ';
			echo'document.getElementById("monto_origina_deuda").value = "0,00"; ';
			echo'document.getElementById("saldo").value = "0,00"; ';
			echo'document.getElementById("monto_cuotas_a_cancelar").value = "0,00"; ';

			echo'document.getElementById("cantidad_original_deuda").value = "0";   ';
            echo'document.getElementById("cantidad_de_cuotas_canceladas").value = "0";  ';
            echo'document.getElementById("cantidad_de_cuotas_cancelar").value = "0";   ';
			echo'document.getElementById("cuotas_cancela").value = "1";  ';

			echo'document.getElementById("saldo").readOnly = true; ';

			echo'document.getElementById("modificar").disabled = true; ';
            echo'document.getElementById("guardar").disabled = true; ';
            echo'document.getElementById("eliminar").disabled = true; ';

             echo'document.getElementById("username").innerHTML = "'.$_SESSION['nom_usuario'].'"; ';


		echo'</script>';

        $this->set('resultado', '');

	}//fin else



       $Lista = $this->cnmd03_transacciones->generateList('cod_tipo_transaccion=1 ', 'cod_transaccion ASC', null, '{n}.cnmd03_transacciones.cod_transaccion', '{n}.cnmd03_transacciones.denominacion');
    if($Lista == ""){
        $this->set('lista_transacciones', $Lista);
    }else{
        $this->concatena_tres_digitos($Lista, 'lista_transacciones');
    }//fin else




}//fin function









function concatena_tres_digitos($vector1=null, $nomVar=null, $extra=null){
	$cod = array();
	if($vector1 != null){
		foreach($vector1 as $x => $y){
			if($extra!=null){
						      if($x<99 && $x>9){
						$cod[$x] = $extra.'0'.$x.' - '.$y;
				    }else if($x<=9){
						$cod[$x] = $extra.'00'.$x.' - '.$y;
					}else{
						$cod[$x] = $extra.''.$x.' - '.$y;
					}

			}else{
				      if($x<99 && $x>9){
					$cod[$x] = '0'.$x.' - '.$y;
			    }else if($x<=9){
					$cod[$x] = '00'.$x.' - '.$y;
				}else{
					$cod[$x] = ''.$x.' - '.$y;
				}
			}
		}
		//print_r($cod);
	}

	$this->set($nomVar, $cod);
}//fin function



















function puesto_busqueda($var=null){
	$this->layout = "ajax";
	$cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep;
	//echo $var;
	if($var!=null){
		//$var2 = $this->v_cnmd05->generateList($this->SQLCA(), null, null, '{n}.v_cnmd05.cod_cargo', '{n}.v_cnmd05.denominacion_clase');
		//$var2 = $this->v_cnmd05->generateList($condicion.' and cod_tipo_nomina='.$var, null);
		$this->set('var', $var);
		//$this->concatena($var2, 'cod_puesto');
		//$this->set('cod_puesto', $var2);

		echo'<script>';
			echo'document.getElementById("cod_cargo").value = ""; ';
  			echo'document.getElementById("cod_puesto").value = "";';
  			echo'document.getElementById("deno_puesto").value = "";';

  			echo'document.getElementById("codigo_ficha2").value = ""; ';
  			echo'document.getElementById("cedula_identidad2").value = "";';
  			echo'document.getElementById("primer_apellido").innerHTML = "<br>";';
  			echo'document.getElementById("segundo_apellido").innerHTML = "<br>";';
  			echo'document.getElementById("primer_nombre").innerHTML = "<br>";';
  			echo'document.getElementById("segundo_nombre").innerHTML = "<br>";';

  			echo'document.getElementById("tipo_transaccion_1").disabled = true;';
  			echo'document.getElementById("tipo_transaccion_2").disabled = true;';

  			echo'document.getElementById("tipo_transaccion_1").checked = false;';
  			echo'document.getElementById("tipo_transaccion_2").checked = false;';

  			echo'document.getElementById("denominacion_transaccion").value = ""; ';
  			echo'document.getElementById("td_cod_transaccion").innerHTML = "<select style=width:50%><option></option></select> ";';

  			echo'document.getElementById("monto_origina_deuda").disabled = true;   ';
            echo'document.getElementById("cantidad_original_deuda").disabled = true;  ';
            echo'document.getElementById("cantidad_de_cuotas_canceladas").disabled = true;  ';
            echo'document.getElementById("cantidad_de_cuotas_cancelar").disabled = true;  ';
			echo'document.getElementById("cuotas_cancela").disabled = true; ';
			echo'document.getElementById("monto_cuotas_a_cancelar").disabled = true; ';
			echo'document.getElementById("monto_origina_deuda").value = "0,00"; ';
			echo'document.getElementById("saldo").value = "0,00"; ';
			echo'document.getElementById("monto_cuotas_a_cancelar").value = "0,00"; ';

			echo'document.getElementById("cantidad_original_deuda").value = "0";   ';
            echo'document.getElementById("cantidad_de_cuotas_canceladas").value = "0";  ';
            echo'document.getElementById("cantidad_de_cuotas_cancelar").value = "0";   ';
			echo'document.getElementById("cuotas_cancela").value = "1";  ';
			echo'document.getElementById("saldo").readOnly = true; ';

			echo'document.getElementById("modificar").disabled = true; ';
            echo'document.getElementById("guardar").disabled = true; ';
            echo'document.getElementById("eliminar").disabled = true; ';

            echo'document.getElementById("continuar_buscar").innerHTML = "<br>"; ';

             echo'document.getElementById("username").innerHTML = "'.$_SESSION['nom_usuario'].'"; ';

		echo'</script>';

	}else{
		$this->set('var2', null);
		$this->set('var', null);
		$this->set('cod_puesto', array());


		echo'<script>';
			echo'document.getElementById("cod_cargo").value = ""; ';
  			echo'document.getElementById("cod_puesto").value = "";';
  			echo'document.getElementById("deno_puesto").value = "";';

  			echo'document.getElementById("buscar_cedula_input").disabled = true;';

  			echo'document.getElementById("codigo_ficha2").value = ""; ';
  			echo'document.getElementById("cedula_identidad2").value = "";';
  			echo'document.getElementById("primer_apellido").innerHTML = "<br>";';
  			echo'document.getElementById("segundo_apellido").innerHTML = "<br>";';
  			echo'document.getElementById("primer_nombre").innerHTML = "<br>";';
  			echo'document.getElementById("segundo_nombre").innerHTML = "<br>";';


  			echo'document.getElementById("tipo_transaccion_1").disabled = true;';
  			echo'document.getElementById("tipo_transaccion_2").disabled = true;';

  			echo'document.getElementById("tipo_transaccion_1").checked = false;';
  			echo'document.getElementById("tipo_transaccion_2").checked = false;';

  			echo'document.getElementById("denominacion_transaccion").value = ""; ';
  			echo'document.getElementById("td_cod_transaccion").innerHTML = "<select style=width:50%><option></option></select> ";';


            echo'document.getElementById("monto_origina_deuda").readOnly = true;   ';
            echo'document.getElementById("cantidad_original_deuda").readOnly = true;  ';
            echo'document.getElementById("cantidad_de_cuotas_canceladas").readOnly = true;  ';
            echo'document.getElementById("cantidad_de_cuotas_cancelar").readOnly = true;  ';
			echo'document.getElementById("cuotas_cancela").readOnly = true; ';
			echo'document.getElementById("monto_cuotas_a_cancelar").readOnly = true; ';
			echo'document.getElementById("monto_origina_deuda").value = "0,00"; ';
			echo'document.getElementById("saldo").value = "0,00"; ';
			echo'document.getElementById("monto_cuotas_a_cancelar").value = "0,00"; ';

			echo'document.getElementById("cantidad_original_deuda").value = "0";   ';
            echo'document.getElementById("cantidad_de_cuotas_canceladas").value = "0";  ';
            echo'document.getElementById("cantidad_de_cuotas_cancelar").value = "0";   ';
			echo'document.getElementById("cuotas_cancela").value = "1";  ';
			echo'document.getElementById("saldo").readOnly = true; ';

			echo'document.getElementById("modificar").disabled = true; ';
            echo'document.getElementById("guardar").disabled = true; ';
            echo'document.getElementById("eliminar").disabled = true; ';

            echo'document.getElementById("continuar_buscar").innerHTML = "<br>"; ';

             echo'document.getElementById("username").innerHTML = "'.$_SESSION['nom_usuario'].'"; ';


		echo'</script>';


	}

}//fin function



function todo_busqueda($cod_cargo=null){
	//echo $p;
	$this->layout="ajax";//echo "hola1";
	/*$cond="cod_puesto=".$cod_puesto;
	$todo1=$this->v_cnmd05->findAll($cond);
	print_r($todo1);
	echo "hola";*/
//echo $cod_puesto;
//$condi=$this->SQLCA()." and cod_puesto=".$cod_puesto;



if($cod_cargo!=null){

$cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$condi = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep." and cod_cargo=".$cod_cargo;
$todov=$this->v_cnmd05->findAll($condi);
//print_r($todov);
$this->set('todov',$todov);


  }//fin if

}//fin function








function cod_ficha($var2=null, $var3=null, $var4=null,  $var1=null){

    $this->layout="ajax";
	$cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep;
    $Lista = '';


    $Lista = $this->cnmd03_transacciones->generateList(' cod_tipo_transaccion='.$var1.' ', 'cod_transaccion ASC', null, '{n}.cnmd03_transacciones.cod_transaccion', '{n}.cnmd03_transacciones.denominacion');

    if($Lista == ""){
        $this->set('lista_transacciones', $Lista);
    }else{
        $this->concatena_tres_digitos($Lista, 'lista_transacciones');
    }//fin else



     $this->set('var', $var2);
     $this->set('var2', $var3);
     $this->set('var3', $var4);
     $this->set('var4', $var1);



     echo'<script>';
			echo'document.getElementById("denominacion_transaccion").value = ""; ';

            echo'document.getElementById("monto_origina_deuda").readOnly = true;   ';
            echo'document.getElementById("cantidad_original_deuda").readOnly = true;  ';
            echo'document.getElementById("cantidad_de_cuotas_canceladas").readOnly = true;  ';
            echo'document.getElementById("cantidad_de_cuotas_cancelar").readOnly = true;  ';
			echo'document.getElementById("cuotas_cancela").readOnly = true; ';
			echo'document.getElementById("monto_cuotas_a_cancelar").readOnly = true; ';
			echo'document.getElementById("monto_origina_deuda").value = "0,00"; ';
			echo'document.getElementById("saldo").value = "0,00"; ';
			echo'document.getElementById("monto_cuotas_a_cancelar").value = "0,00"; ';

			echo'document.getElementById("cantidad_original_deuda").value = "0";   ';
            echo'document.getElementById("cantidad_de_cuotas_canceladas").value = "0";  ';
            echo'document.getElementById("cantidad_de_cuotas_cancelar").value = "0";   ';
			echo'document.getElementById("cuotas_cancela").value = "1";  ';
			echo'document.getElementById("saldo").readOnly = true; ';

			echo'document.getElementById("modificar").disabled = true; ';
            echo'document.getElementById("guardar").disabled = true; ';
            echo'document.getElementById("eliminar").disabled = true; ';

            echo'document.getElementById("username").innerHTML = "'.$_SESSION['nom_usuario'].'"; ';

     echo'</script>';


}//fin function









function select_despues($var2=null, $var3=null, $var4=null,  $var1=null){

    $this->layout="ajax";


                                  $var2                     =    $this->data['cnmp07']['cod_tipo_nomina'];
								  $var3                     =    $this->data['cnmp07']['cod_cargo'];
								  $var4                     =    $this->data['cnmp07']['codigo_ficha2'];
								  $var1                     =    1;


	$cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep;
    $Lista = '';


    $Lista = $this->cnmd03_transacciones->generateList(' cod_tipo_transaccion='.$var1.' ', 'cod_tipo_transaccion, cod_transaccion ASC', null, '{n}.cnmd03_transacciones.cod_transaccion', '{n}.cnmd03_transacciones.denominacion');

    if($Lista == ""){
        $this->set('lista_transacciones', $Lista);
    }else{
        $this->concatena_tres_digitos($Lista, 'lista_transacciones');
    }//fin else



     $this->set('var', $var2);
     $this->set('var2', $var3);
     $this->set('var3', $var4);
     $this->set('var4', $var1);



     echo'<script>';
			echo'document.getElementById("denominacion_transaccion").value = ""; ';

            echo'document.getElementById("monto_origina_deuda").readOnly = true;   ';
            echo'document.getElementById("cantidad_original_deuda").readOnly = true;  ';
            echo'document.getElementById("cantidad_de_cuotas_canceladas").readOnly = true;  ';
            echo'document.getElementById("cantidad_de_cuotas_cancelar").readOnly = true;  ';
			echo'document.getElementById("cuotas_cancela").readOnly = true; ';
			echo'document.getElementById("monto_cuotas_a_cancelar").readOnly = true; ';
			echo'document.getElementById("monto_origina_deuda").value = "0,00"; ';
			echo'document.getElementById("saldo").value = "0,00"; ';
			echo'document.getElementById("monto_cuotas_a_cancelar").value = "0,00"; ';

			echo'document.getElementById("cantidad_original_deuda").value = "0";   ';
            echo'document.getElementById("cantidad_de_cuotas_canceladas").value = "0";  ';
            echo'document.getElementById("cantidad_de_cuotas_cancelar").value = "0";   ';
			echo'document.getElementById("cuotas_cancela").value = "1";  ';
			echo'document.getElementById("saldo").readOnly = true; ';

			echo'document.getElementById("modificar").disabled = true; ';
            echo'document.getElementById("guardar").disabled = true; ';
            echo'document.getElementById("eliminar").disabled = true; ';

            echo'document.getElementById("username").innerHTML = "'.$_SESSION['nom_usuario'].'"; ';

     echo'</script>';


}//fin function


















function select_cod_ficha($var1=null, $var3=null, $var4=null, $var5=null, $var2=null){

    $this->layout="ajax";
	$cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep;
    $denominacion = "";
    $tipo_actualizacion = "";

	$fecha_transaccion        =   "";
	$monto_original           =   "0,00";
	$numero_original_cuotas   =   "0";
	$numero_cuotas_cancelar   =   "1";
	$numero_cuotas_canceladas =   "0";
	$monto_cuota              =   "0,00";
	$saldo                    =   "0,00";
	$marca_fin_descuento      =   "0";
	$username                 =   "";
	$existe                   =   "no";

    if($var2!=null){
               $resul =   $this->cnmd03_transacciones->findAll(' cod_tipo_transaccion='.$var5.' and cod_transaccion='.$var2.'  ');
               $denominacion =  $resul[0]['cnmd03_transacciones']['denominacion'];
               $tipo_actualizacion =  $resul[0]['cnmd03_transacciones']['tipo_actualizacion'];
               $uso_transaccion    =  $resul[0]['cnmd03_transacciones']['uso_transaccion'];
    }else{//else#1
//           echo "CERO";
    	    echo'<script>';
			echo'	document.getElementById("denominacion_transaccion").value = ""; ';
            echo'	document.getElementById("monto_origina_deuda").readOnly = true;   ';
            echo'	document.getElementById("cantidad_original_deuda").readOnly = true;  ';
            echo'	document.getElementById("cantidad_de_cuotas_canceladas").readOnly = true;  ';
            echo'	document.getElementById("cantidad_de_cuotas_cancelar").readOnly = true;  ';
			echo'	document.getElementById("cuotas_cancela").readOnly = true; ';
			echo'	document.getElementById("monto_cuotas_a_cancelar").readOnly = true; ';
			echo'	document.getElementById("monto_origina_deuda").value = "0,00"; ';
			echo'	document.getElementById("saldo").value = "0,00"; ';
			echo'	document.getElementById("monto_cuotas_a_cancelar").value = "0,00"; ';
			echo'	document.getElementById("cantidad_original_deuda").value = "0";   ';
            echo'	document.getElementById("cantidad_de_cuotas_canceladas").value = "0";  ';
            echo'	document.getElementById("cantidad_de_cuotas_cancelar").value = "0";   ';
			echo'	document.getElementById("cuotas_cancela").value = "1";  ';
			echo'	document.getElementById("saldo").readOnly = true; ';
			echo'	document.getElementById("modificar").disabled = true; ';
            echo'	document.getElementById("guardar").disabled = true; ';
            echo'	document.getElementById("eliminar").disabled = true; ';
            echo'	document.getElementById("username").innerHTML = "'.$_SESSION['nom_usuario'].'"; ';
            echo'</script>';
    }//else#1

	if($var3!=null && $var4!=null && $var5!=null && $var2!=null){
		$datos_cnmd07_transacciones_actuales  =  $this->cnmd07_transacciones_actuales->findAll($this->SQLCA().' and cod_tipo_nomina='.$var1.' and cod_cargo='.$var3.' and cod_ficha='.$var4.' and cod_tipo_transaccion='.$var5.' and cod_transaccion='.$var2.' ');
		$cuenta_transacciones  =  $this->cnmd07_transacciones_actuales->findCount($this->SQLCA().' and cod_tipo_nomina='.$var1.' and cod_cargo='.$var3.' and cod_ficha='.$var4.' and cod_tipo_transaccion='.$var5.' and cod_transaccion='.$var2.' ');
	    if($cuenta_transacciones!=0){
	        $cod_tipo_nomina          =   $datos_cnmd07_transacciones_actuales[0]['cnmd07_transacciones_actuales']['cod_tipo_nomina'];
		    $cod_cargo                =   $datos_cnmd07_transacciones_actuales[0]['cnmd07_transacciones_actuales']['cod_cargo'];
			$cod_ficha                =   $datos_cnmd07_transacciones_actuales[0]['cnmd07_transacciones_actuales']['cod_ficha'];
			$cod_tipo_transaccion     =   $datos_cnmd07_transacciones_actuales[0]['cnmd07_transacciones_actuales']['cod_tipo_transaccion'];
		    $cod_transaccion          =   $datos_cnmd07_transacciones_actuales[0]['cnmd07_transacciones_actuales']['cod_transaccion'];
		    $fecha_transaccion        =   $datos_cnmd07_transacciones_actuales[0]['cnmd07_transacciones_actuales']['fecha_transaccion'];
		    $monto_original           =   $datos_cnmd07_transacciones_actuales[0]['cnmd07_transacciones_actuales']['monto_original'];
		    $numero_original_cuotas   =   $datos_cnmd07_transacciones_actuales[0]['cnmd07_transacciones_actuales']['numero_cuotas_descontar'];
		    $numero_cuotas_cancelar   =   $datos_cnmd07_transacciones_actuales[0]['cnmd07_transacciones_actuales']['numero_cuotas_cancelar'];
		    $numero_cuotas_canceladas =   $datos_cnmd07_transacciones_actuales[0]['cnmd07_transacciones_actuales']['numero_cuotas_canceladas'];
		    $monto_cuota              =   $datos_cnmd07_transacciones_actuales[0]['cnmd07_transacciones_actuales']['monto_cuota'];
		    $saldo                    =   $datos_cnmd07_transacciones_actuales[0]['cnmd07_transacciones_actuales']['saldo'];
		    $marca_fin_descuento      =   $datos_cnmd07_transacciones_actuales[0]['cnmd07_transacciones_actuales']['marca_fin_descuento'];
		    $fecha_proceso            =   $datos_cnmd07_transacciones_actuales[0]['cnmd07_transacciones_actuales']['fecha_proceso'];
		    $username                 =   $datos_cnmd07_transacciones_actuales[0]['cnmd07_transacciones_actuales']['username'];
		    $existe = "si";
	    }
	}//fin fin
	if($existe!="si"){
	    echo'<script>';
	    echo'	document.getElementById("guardar").disabled = false; ';
	    echo'	document.getElementById("modificar").disabled = true; ';
	    echo'	document.getElementById("eliminar").disabled = true; ';
	    echo'	document.getElementById("username").innerHTML = "'.$_SESSION['nom_usuario'].'"; ';
	    echo'</script>';
	}else{
	    echo'<script>';
	    echo'	document.getElementById("guardar").disabled = true; ';
	    echo'	document.getElementById("modificar").disabled = false; ';
	    echo'	document.getElementById("eliminar").disabled = false; ';
	    echo'	document.getElementById("username").innerHTML = "'.$username.'"; ';
	    echo'</script>';
	}//fin else

    $cantidad_de_cuotas_cancelar = $numero_original_cuotas - $numero_cuotas_canceladas;

	if($tipo_actualizacion=="1" || $var5=="1" && $var2!=null){
//		echo "UNO";
		echo'<script>';
		echo'document.getElementById("monto_origina_deuda").readOnly = true;   ';
		echo'document.getElementById("cantidad_original_deuda").readOnly = true;  ';
		echo'document.getElementById("cantidad_de_cuotas_canceladas").readOnly = true;  ';
		echo'document.getElementById("cantidad_de_cuotas_cancelar").readOnly = true;  ';
		echo'document.getElementById("cuotas_cancela").readOnly = true; ';
		echo'document.getElementById("monto_cuotas_a_cancelar").readOnly = true; ';
		echo'document.getElementById("saldo").readOnly = true; ';
		echo'document.getElementById("monto_origina_deuda").value = "'.$this->Formato2($monto_original).'"; ';
		echo'document.getElementById("saldo").value = "'.$this->Formato2($saldo).'"; ';
		echo'document.getElementById("monto_cuotas_a_cancelar").value = "'.$this->Formato2($monto_cuota).'"; ';
		echo'document.getElementById("cantidad_original_deuda").value = "'.$numero_original_cuotas.'";   ';
		echo'document.getElementById("cantidad_de_cuotas_canceladas").value = "'.$numero_cuotas_canceladas.'";  ';
		echo'document.getElementById("cantidad_de_cuotas_cancelar").value = "'.$cantidad_de_cuotas_cancelar.'";   ';
		echo'document.getElementById("cuotas_cancela").value = "'.$numero_cuotas_cancelar.'";  ';
		echo'</script>';

		if($existe!="si"){
//			echo "DOS";
			echo'<script>';
			echo'document.getElementById("monto_origina_deuda").readOnly = true;   ';
			echo'document.getElementById("cantidad_original_deuda").readOnly = true;  ';
			echo'document.getElementById("cantidad_de_cuotas_canceladas").readOnly = true;  ';
			echo'document.getElementById("cantidad_de_cuotas_cancelar").readOnly = true;  ';
			echo'document.getElementById("cuotas_cancela").readOnly = false; ';
			echo'document.getElementById("monto_cuotas_a_cancelar").readOnly = false; ';
			echo'document.getElementById("saldo").readOnly = true; ';
			echo'</script>';
		}

    }else if($tipo_actualizacion=="2"){
//    	echo "TRES";
		echo'<script>';
		echo'document.getElementById("monto_origina_deuda").readOnly = true;   ';
		echo'document.getElementById("cantidad_original_deuda").readOnly = true;  ';
		echo'document.getElementById("cantidad_de_cuotas_canceladas").readOnly = true;  ';
		echo'document.getElementById("cantidad_de_cuotas_cancelar").readOnly = true;  ';
		echo'document.getElementById("cuotas_cancela").readOnly = true; ';
		echo'document.getElementById("monto_cuotas_a_cancelar").readOnly = true; ';
		echo'document.getElementById("saldo").readOnly = false; ';
		echo'document.getElementById("monto_origina_deuda").value = "'.$this->Formato2($monto_original).'"; ';
		echo'document.getElementById("saldo").value = "'.$this->Formato2($saldo).'"; ';
		echo'document.getElementById("monto_cuotas_a_cancelar").value = "'.$this->Formato2($monto_cuota).'"; ';
		echo'document.getElementById("cantidad_original_deuda").value = "'.$numero_original_cuotas.'";   ';
		echo'document.getElementById("cantidad_de_cuotas_canceladas").value = "'.$numero_cuotas_canceladas.'";  ';
		echo'document.getElementById("cantidad_de_cuotas_cancelar").value = "'.$cantidad_de_cuotas_cancelar.'";   ';
		echo'document.getElementById("cuotas_cancela").value = "'.$numero_cuotas_cancelar.'";  ';
		echo'</script>';

		if($existe!="si"){
//			echo "CUATRO";
			echo'<script>';
			echo'document.getElementById("monto_origina_deuda").readOnly = false;   ';
			echo'document.getElementById("cantidad_original_deuda").readOnly = false;  ';
			echo'document.getElementById("cantidad_de_cuotas_canceladas").readOnly = true;  ';
			echo'document.getElementById("cantidad_de_cuotas_cancelar").readOnly = true;  ';
			echo'document.getElementById("cuotas_cancela").readOnly = false; ';
			echo'document.getElementById("monto_cuotas_a_cancelar").readOnly = false; ';
			//echo'document.getElementById("saldo").readOnly = true; ';
			echo'</script>';
		 }//
    }else if($var5==null){
//    	echo "CINCO";
		echo'<script>';
		echo'document.getElementById("monto_origina_deuda").readOnly = true;   ';
		echo'document.getElementById("cantidad_original_deuda").readOnly = true;  ';
		echo'document.getElementById("cantidad_de_cuotas_canceladas").readOnly = true;  ';
		echo'document.getElementById("cantidad_de_cuotas_cancelar").readOnly = true;  ';
		echo'document.getElementById("cuotas_cancela").readOnly = true; ';
		echo'document.getElementById("monto_cuotas_a_cancelar").readOnly = true; ';
		echo'document.getElementById("saldo").readOnly = true; ';
		echo'document.getElementById("monto_origina_deuda").value = "'.$this->Formato2($monto_original).'"; ';
		echo'document.getElementById("saldo").value = "'.$this->Formato2($saldo).'"; ';
		echo'document.getElementById("monto_cuotas_a_cancelar").value = "'.$this->Formato2($monto_cuota).'"; ';
		echo'document.getElementById("cantidad_original_deuda").value = "'.$numero_original_cuotas.'";   ';
		echo'document.getElementById("cantidad_de_cuotas_canceladas").value = "'.$numero_cuotas_canceladas.'";  ';
		echo'document.getElementById("cantidad_de_cuotas_cancelar").value = "'.$cantidad_de_cuotas_cancelar.'";   ';
		echo'document.getElementById("cuotas_cancela").value = "'.$numero_cuotas_cancelar.'";  ';
		echo'</script>';

		if($existe!="si"){
//			echo "SEIS";
			echo'<script>';
			echo'document.getElementById("monto_origina_deuda").readOnly = true;   ';
			echo'document.getElementById("cantidad_original_deuda").readOnly = true;  ';
			echo'document.getElementById("cantidad_de_cuotas_canceladas").readOnly = true;  ';
			echo'document.getElementById("cantidad_de_cuotas_cancelar").readOnly = true;  ';
			echo'document.getElementById("cuotas_cancela").readOnly = false; ';
			echo'document.getElementById("monto_cuotas_a_cancelar").readOnly = false; ';
			echo'document.getElementById("saldo").readOnly = false; ';
			echo'</script>';
		 }//
    }//
	echo'<script>';
	echo"document.getElementById('monto_origina_deuda').style.backgroundColor = '#ffffff';   ";
	echo"document.getElementById('cantidad_original_deuda').style.backgroundColor = '#ffffff';   ";
	echo"document.getElementById('cantidad_de_cuotas_canceladas').style.backgroundColor = '#ffffff';   ";
	echo"document.getElementById('cantidad_de_cuotas_cancelar').style.backgroundColor = '#ffffff';   ";
	echo"document.getElementById('cuotas_cancela').style.backgroundColor = '#ffffff';   ";
	echo"document.getElementById('monto_cuotas_a_cancelar').style.backgroundColor = '#ffffff';   ";
	echo"document.getElementById('saldo').style.backgroundColor = '#ffffff';   ";
	echo"document.getElementById('campo_modificar').value='no';   ";
	echo'</script>';
    $this->set('denominacion', $denominacion);

    $c_frecuencia = $this->cnmd09_frecuencia->findCount($this->SQLCA().' and cod_tipo_nomina='.$var1.' and cod_tipo_transaccion='.$var5.' and cod_transaccion='.$var2.' ');
    if($c_frecuencia==0){
        $this->set('errorMessage','Por favor registre la frecuencia de pago para transacci&oacute;n seleccionada');
        echo'<script>';
	    echo'	document.getElementById("guardar").disabled = true; ';
	    echo'	document.getElementById("modificar").disabled = true; ';
	    echo'	document.getElementById("eliminar").disabled = true; ';
	    echo'</script>';
    }

   $this->set('tipo_actualizacion',$tipo_actualizacion);

}//fin function select_cod_ficha






function select_busqueda($var=null){

$this->layout = "ajax";

    $cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep;
	//echo $var;
	if($var!=null){
		$var2 = $this->v_cnmd05->generateList($this->SQLCA(), null, null, '{n}.v_cnmd05.cod_cargo', '{n}.v_cnmd05.denominacion_clase');
		//$var2 = $this->v_cnmd05->generateList($condicion.' and cod_tipo_nomina='.$var, null);
		$this->set('var', $var);
		$this->concatena($var2, 'cod_puesto');
		//$this->set('cod_puesto', $var2);

	}else{
		$this->set('var', null);
		$this->set('var2', null);
		$this->set('cod_puesto', array());

	}


 }//fin function






function mostrar_datos_griya($var1=null, $var2=null, $var3=null, $var4=null, $var5=null){

	$this->layout = "ajax";
    $cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep;



$cod_cargo[1]="";
$cod_ficha[1]="";
$acepta = "no";
$datos_cnmd07_transacciones_actuales = "";
$datos_cnmd03_transacciones = "";
$opcion = "1";

echo'<script>';
    echo"document.getElementById('monto_origina_deuda').style.backgroundColor = '#ffffff';   ";
    echo"document.getElementById('cantidad_original_deuda').style.backgroundColor = '#ffffff';   ";
    echo"document.getElementById('cantidad_de_cuotas_canceladas').style.backgroundColor = '#ffffff';   ";
    echo"document.getElementById('cantidad_de_cuotas_cancelar').style.backgroundColor = '#ffffff';   ";
	echo"document.getElementById('cuotas_cancela').style.backgroundColor = '#ffffff';   ";
	echo"document.getElementById('monto_cuotas_a_cancelar').style.backgroundColor = '#ffffff';   ";
	echo"document.getElementById('saldo').style.backgroundColor = '#ffffff';   ";
    echo"document.getElementById('campo_modificar').value='no';   ";
echo'</script>';



      if(!isset($_SESSION['opcion_griya'])){$_SESSION['opcion_griya'] = "1";
}else if(isset($_SESSION['opcion_griya'])){if($_SESSION['opcion_griya']=="1"){$_SESSION['opcion_griya']="2";}else{$_SESSION['opcion_griya']="1";}}//fin else

//echo $_SESSION['opcion_griya'];

if($var3==null){ $var3 = 1;}


if($var2!=null && $var1!=null){

	$var =   $this->Session->read('tipo_nomina');

							                    if($this->cnmd06_fichas->findCount($condicion.' and cod_tipo_nomina='.$var.' and cedula_identidad='.$var1.'  '.' and cod_cargo='.$var2)!=0){
								  $fichas        =   $this->cnmd06_fichas->findAll($condicion.' and cod_tipo_nomina='.$var.' and cedula_identidad='.$var1.'  '.' and cod_cargo='.$var2);
							      $fichas_aux    =   $fichas;
							      $i = 0;
							      foreach($fichas_aux as $aux){ $i++;
							             $cod_cargo[$i] =  $aux['cnmd06_fichas']['cod_cargo'];
							             $cod_ficha[$i] =  $aux['cnmd06_fichas']['cod_ficha'];
							      }//fin foreach

							$var4  =   $cod_cargo[$var3];
							$var5  =   $cod_ficha[$var3];

							//echo $var5;

							  }//fin if

							$acepta = "si";


							      if($_SESSION['opcion_griya']=="1"){$sql = ' cod_tipo_nomina='.$var.' and cod_ficha='.$var5.' and cod_cargo='.$var4.'  ';
							}else if($_SESSION['opcion_griya']=="2"){$sql = ' cod_tipo_nomina='.$var.' and cod_cargo='.$var4.' and cod_ficha='.$var5.' ';}//fin else

                            $cod_tipo_nomina = $var;
                            $cod_cargo       = $var4;


			$datos_sueldo =   $this->cnmd05->findAll($condicion.' and cod_tipo_nomina='.$cod_tipo_nomina.' and cod_cargo='.$cod_cargo, null, null);
			$this->set('dato_sueldo', $datos_sueldo);


}else if($var1==null){


								if(isset($this->data['cnmp07']['cod_transaccion'])){



								  $cod_tipo_nomina               =    $this->data['cnmp07']['cod_tipo_nomina'];
								  $cod_cargo                     =    $this->data['cnmp07']['cod_cargo'];
								  $cod_ficha                     =    $this->data['cnmp07']['codigo_ficha2'];
								  $cod_tipo_transaccion          =    $this->data['cnmp07']['tipo_transaccion'];
								  $cod_transaccion               =    $this->data['cnmp07']['cod_transaccion'];


								$acepta = "si";

								$var1 = $cod_tipo_nomina;
								$var4 = $cod_cargo;
								$var5 = $cod_ficha;

								      if($_SESSION['opcion_griya']=="1"){$sql = ' cod_tipo_nomina='.$var1.' and cod_ficha='.$var5.' and cod_cargo='.$var4.'  ';
								}else if($_SESSION['opcion_griya']=="2"){$sql = ' cod_tipo_nomina='.$var1.' and cod_cargo='.$var4.' and cod_ficha='.$var5.' ';}//fin else


								  }//fin if
								}//fin if


								   if($acepta=='si' && $var5!=null){


									//if(isset($this->data['cnmp07']['saldo'])){ echo $this->data['cnmp07']['saldo'].'--'.rand();}

								     $datos_cnmd07_transacciones_actuales_aux           =   $this->v_cnmd07_transacciones_actuales_frecuencias2->findAll($condicion." and  ".$sql, null, ' cod_tipo_transaccion,  cod_transaccion  ASC');
								     //$datos_cnmd03_transacciones =   $this->cnmd03_transacciones->findAll(null, null, ' cod_tipo_transaccion,  cod_transaccion  ASC');

								     // echo rand();

								     $this->set('datos_cnmd07_transacciones_actuales', $datos_cnmd07_transacciones_actuales_aux);
//								     $this->set('datos_cnmd03_transacciones', $datos_cnmd03_transacciones);

				$datos_sueldo =   $this->cnmd05->findAll($condicion.' and cod_tipo_nomina='.$cod_tipo_nomina.' and cod_cargo='.$cod_cargo, null, null);
				$this->set('dato_sueldo', $datos_sueldo);


   }//fin fin








}//fin function






 function guardar(){

	$this->layout = "ajax";
    $cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep;



if(!isset($this->data['cnmp07']['monto_origina_deuda'])){           $this->data['cnmp07']['monto_origina_deuda']="0"; }
if(!isset($this->data['cnmp07']['cantidad_original_deuda'])){       $this->data['cnmp07']['cantidad_original_deuda']="0"; }
if(!isset($this->data['cnmp07']['cantidad_de_cuotas_cancelar'])){   $this->data['cnmp07']['cantidad_de_cuotas_cancelar']="0";  }
if(!isset($this->data['cnmp07']['cantidad_de_cuotas_canceladas'])){ $this->data['cnmp07']['cantidad_de_cuotas_canceladas']="0"; }
if(!isset($this->data['cnmp07']['monto_cuotas_a_cancelar'])){ $this->data['cnmp07']['monto_cuotas_a_cancelar']="0,00"; }
if(!isset($this->data['cnmp07']['saldo'])){ $this->data['cnmp07']['saldo'] = "0,00"; }

  $cod_presi                     =    $this->Session->read('SScodpresi');
  $cod_entidad                   =    $this->Session->read('SScodentidad');
  $cod_tipo_inst                 =    $this->Session->read('SScodtipoinst');
  $cod_inst                      =    $this->Session->read('SScodinst');
  $cod_dep                       =    $this->Session->read('SScoddep');
  $cod_tipo_nomina               =    $this->data['cnmp07']['cod_tipo_nomina'];
  $cod_cargo                     =    $this->data['cnmp07']['cod_cargo'];
  $cod_ficha                     =    $this->data['cnmp07']['codigo_ficha2'];
  $cod_tipo_transaccion          =    $this->data['cnmp07']['tipo_transaccion'];
  $cod_transaccion               =    $this->data['cnmp07']['cod_transaccion'];
  $fecha_transaccion             =    $this->data['cnmp07']['fecha_transaccion'];
  $monto_original                =    $this->Formato1($this->data['cnmp07']['monto_origina_deuda']);
  $numero_original_cuotas        =    $this->data['cnmp07']['cantidad_original_deuda'];
  $numero_cuotas_cancelar        =    $this->data['cnmp07']['cuotas_cancela'];
  $numero_cuotas_canceladas      =    $this->data['cnmp07']['cantidad_de_cuotas_canceladas'];
  $monto_cuota                   =    $this->Formato1($this->data['cnmp07']['monto_cuotas_a_cancelar']);
  $saldo                         =    $this->Formato1($this->data['cnmp07']['saldo']);
  $marca_fin_descuento           =    "0";
  $fecha_proceso                 =    date('d/m/Y');
  $username                      =    $_SESSION['nom_usuario'];


 if($this->cnmd07_transacciones_actuales->findCount($condicion.' and cod_tipo_nomina='.$cod_tipo_nomina.' and cod_cargo = '.$cod_cargo.' and cod_ficha = '.$cod_ficha.' and  cod_tipo_transaccion = '.$cod_tipo_transaccion.' and  cod_transaccion = '.$cod_transaccion.'')==0){


$sql = "INSERT INTO cnmd07_transacciones_actuales (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, cod_cargo, cod_ficha, cod_tipo_transaccion, cod_transaccion, fecha_transaccion, monto_original, numero_cuotas_descontar, numero_cuotas_cancelar, numero_cuotas_canceladas, monto_cuota, saldo, marca_fin_descuento, fecha_proceso, username )";
$sql.="VALUES ('".$cod_presi."', '".$cod_entidad."', '".$cod_tipo_inst."', '".$cod_inst."', '".$cod_dep."', '".$cod_tipo_nomina."', '".$cod_cargo."', '".$cod_ficha."', '".$cod_tipo_transaccion."', '".$cod_transaccion."', '".$this->Cfecha($fecha_transaccion, 'A-M-D')."', '".$monto_original."', '".$numero_original_cuotas."', '".$numero_cuotas_cancelar."', '".$numero_cuotas_canceladas."', '".$monto_cuota."', '".$saldo."', '".$marca_fin_descuento."', '".$this->Cfecha($fecha_proceso, 'A-M-D')."', '".$username."' )";
$this->cnmd07_transacciones_actuales->execute($sql);

             $status_actual = $this->Cnmd01->field("status_nomina", $this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina);
             if ($status_actual==1){
             $this->cnmd07_transacciones_actuales->execute("UPDATE cnmd01 SET status_nomina=0 where ".$this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina);
             }


$this->set('Message_existe', 'Los datos fuerón guardados correctamente');

echo'<script>';
    echo'document.getElementById("tipo_transaccion_1").checked = true;';
  	echo'document.getElementById("tipo_transaccion_2").checked = false;';
    echo'document.getElementById("guardar").disabled = true; ';
    echo'document.getElementById("modificar").disabled = false; ';
     echo'document.getElementById("eliminar").disabled = false; ';
echo'</script>';



$datos_sueldo =   $this->cnmd05->findAll($condicion.' and  cod_tipo_nomina='.$cod_tipo_nomina.' and cod_cargo='.$cod_cargo, null, null);
$this->set('dato_sueldo', $datos_sueldo);


 }else{



$sql  ="UPDATE cnmd07_transacciones_actuales SET fecha_transaccion='".$this->Cfecha($fecha_transaccion, 'A-M-D')."',  monto_original='".$monto_original."', numero_cuotas_descontar='".$numero_original_cuotas."',  numero_cuotas_cancelar='".$numero_cuotas_cancelar."',  numero_cuotas_canceladas='".$numero_cuotas_canceladas."',   monto_cuota='".$monto_cuota."',   saldo='".$saldo."', marca_fin_descuento='".$marca_fin_descuento."', fecha_proceso='".$this->Cfecha($fecha_proceso, 'A-M-D')."', username ='".$username."'  where ".$condicion.' and cod_tipo_nomina='.$cod_tipo_nomina.' and cod_cargo = '.$cod_cargo.' and cod_ficha = '.$cod_ficha.' and  cod_tipo_transaccion = '.$cod_tipo_transaccion.' and  cod_transaccion = '.$cod_transaccion.';';
$sql .="UPDATE cnmd07_transacciones_prenomina SET fecha_transaccion='".$this->Cfecha($fecha_transaccion, 'A-M-D')."',  monto_original='".$monto_original."', numero_cuotas_descontar='".$numero_original_cuotas."',  numero_cuotas_cancelar='".$numero_cuotas_cancelar."',  numero_cuotas_canceladas='".$numero_cuotas_canceladas."',   monto_cuota='".$monto_cuota."',   saldo='".$saldo."', marca_fin_descuento='".$marca_fin_descuento."', fecha_proceso='".$this->Cfecha($fecha_proceso, 'A-M-D')."', username ='".$username."'  where ".$condicion.' and cod_tipo_nomina='.$cod_tipo_nomina.' and cod_cargo = '.$cod_cargo.' and cod_ficha = '.$cod_ficha.' and  cod_tipo_transaccion = '.$cod_tipo_transaccion.' and  cod_transaccion = '.$cod_transaccion.';';
$this->cnmd07_transacciones_actuales->execute($sql);

             $status_actual = $this->Cnmd01->field("status_nomina", $this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina);
             if ($status_actual==1){
             $this->cnmd07_transacciones_actuales->execute("UPDATE cnmd01 SET status_nomina=0 where ".$this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina);
             }

$datos_sueldo =   $this->cnmd05->findAll($condicion.' and cod_tipo_nomina='.$cod_tipo_nomina.' and cod_cargo='.$cod_cargo, null, null);
$this->set('dato_sueldo', $datos_sueldo);


 $this->set('Message_existe', 'Los datos fuerón modificados');


echo'<script>';
            echo'document.getElementById("monto_origina_deuda").readOnly = true;   ';
            echo'document.getElementById("cantidad_original_deuda").readOnly = true;  ';
            echo'document.getElementById("cantidad_de_cuotas_canceladas").readOnly = true;  ';
            echo'document.getElementById("cantidad_de_cuotas_cancelar").readOnly = true;  ';
			echo'document.getElementById("cuotas_cancela").readOnly = true; ';
			echo'document.getElementById("monto_cuotas_a_cancelar").readOnly = true; ';

			echo'document.getElementById("monto_origina_deuda").value = "0,00"; ';
			echo'document.getElementById("saldo").value = "0,00"; ';
			echo'document.getElementById("monto_cuotas_a_cancelar").value = "0,00"; ';

			echo'document.getElementById("cantidad_original_deuda").value = "0";   ';
            echo'document.getElementById("cantidad_de_cuotas_canceladas").value = "0";  ';
            echo'document.getElementById("cantidad_de_cuotas_cancelar").value = "0";   ';
			echo'document.getElementById("cuotas_cancela").value = "1";  ';
			echo'document.getElementById("modificar").disabled = true; ';
            echo'document.getElementById("guardar").disabled = true; ';

            echo'document.getElementById("tipo_transaccion_1").checked = true;';
  			echo'document.getElementById("tipo_transaccion_2").checked = false;';

  			echo'document.getElementById("denominacion_transaccion").value = ""; ';
  			//echo'document.getElementById("td_cod_transaccion").innerHTML = "<select style=width:50%><option></option></select> ";';

			echo'document.getElementById("saldo").readOnly = true; ';


			 echo'document.getElementById("username").innerHTML = "'.$_SESSION['nom_usuario'].'"; ';
 echo'</script>';


echo'<script>';
    echo'document.getElementById("guardar").disabled = true; ';
    echo'document.getElementById("modificar").disabled = true; ';
     echo'document.getElementById("eliminar").disabled = true; ';
echo'</script>';


}//fin else




//$this->mostrar_datos_griya();


echo'<script>';
    echo"document.getElementById('monto_origina_deuda').style.backgroundColor = '#ffffff';   ";
    echo"document.getElementById('cantidad_original_deuda').style.backgroundColor = '#ffffff';   ";
    echo"document.getElementById('cantidad_de_cuotas_canceladas').style.backgroundColor = '#ffffff';   ";
    echo"document.getElementById('cantidad_de_cuotas_cancelar').style.backgroundColor = '#ffffff';   ";
	echo"document.getElementById('cuotas_cancela').style.backgroundColor = '#ffffff';   ";
	echo"document.getElementById('monto_cuotas_a_cancelar').style.backgroundColor = '#ffffff';   ";
	echo"document.getElementById('saldo').style.backgroundColor = '#ffffff';   ";
    echo"document.getElementById('campo_modificar').value='no';   ";
echo'</script>';


 }//fin function








function modificar(){


$this->layout = "ajax";


  $cod_presi                     =    $this->Session->read('SScodpresi');
  $cod_entidad                   =    $this->Session->read('SScodentidad');
  $cod_tipo_inst                 =    $this->Session->read('SScodtipoinst');
  $cod_inst                      =    $this->Session->read('SScodinst');
  $cod_dep                       =    $this->Session->read('SScoddep');
  $cod_tipo_nomina               =    $this->data['cnmp07']['cod_tipo_nomina'];
  $cod_cargo                     =    $this->data['cnmp07']['cod_cargo'];
  $cod_ficha                     =    $this->data['cnmp07']['codigo_ficha2'];
  $cod_tipo_transaccion          =    $this->data['cnmp07']['tipo_transaccion'];
  $cod_transaccion               =    $this->data['cnmp07']['cod_transaccion'];
  $fecha_transaccion             =    $this->data['cnmp07']['fecha_transaccion'];

  $monto_original                =    $this->data['cnmp07']['monto_origina_deuda'];
  $numero_original_cuotas        =    $this->data['cnmp07']['cantidad_original_deuda'];
  $numero_cuotas_cancelar        =    $this->data['cnmp07']['cuotas_cancela'];
  $numero_cuotas_canceladas      =    $this->data['cnmp07']['cantidad_de_cuotas_canceladas'];
  $monto_cuota                   =    $this->data['cnmp07']['monto_cuotas_a_cancelar'];
  $saldo                         =    $this->data['cnmp07']['saldo'];
  $cantidad_de_cuotas_cancelar   =    $this->data['cnmp07']['cantidad_de_cuotas_cancelar'];


          $resul =   $this->cnmd03_transacciones->findAll(' cod_tipo_transaccion='.$cod_tipo_transaccion.' and cod_transaccion='.$cod_transaccion.'  ');
          foreach($resul as $aux){
               $denominacion =  $aux['cnmd03_transacciones']['denominacion'];
               $tipo_actualizacion =  $aux['cnmd03_transacciones']['tipo_actualizacion'];
               $uso_transaccion    =  $aux['cnmd03_transacciones']['uso_transaccion'];
          	}//fin foreach



          if($tipo_actualizacion=="1" || $cod_tipo_transaccion=="1" && $cod_transaccion!=null){

          	       echo'<script>';
			            echo'document.getElementById("monto_origina_deuda").readOnly = true;   ';
			            echo'document.getElementById("cantidad_original_deuda").readOnly = true;  ';
			            echo'document.getElementById("cantidad_de_cuotas_canceladas").readOnly = true;  ';
			            echo'document.getElementById("cantidad_de_cuotas_cancelar").readOnly = true;  ';
						echo'document.getElementById("cuotas_cancela").readOnly = false; ';
						echo'document.getElementById("monto_cuotas_a_cancelar").readOnly = false; ';
						echo'document.getElementById("saldo").readOnly = false; ';


						echo"document.getElementById('monto_origina_deuda').style.backgroundColor = '#ffffff';   ";
					    echo"document.getElementById('cantidad_original_deuda').style.backgroundColor = '#ffffff';   ";
					    echo"document.getElementById('cantidad_de_cuotas_canceladas').style.backgroundColor = '#ffffff';   ";
					    echo"document.getElementById('cantidad_de_cuotas_cancelar').style.backgroundColor = '#ffffff';   ";
						echo"document.getElementById('cuotas_cancela').style.backgroundColor = '#ffffca';   ";
						echo"document.getElementById('monto_cuotas_a_cancelar').style.backgroundColor = '#ffffca';   ";
						echo"document.getElementById('saldo').style.backgroundColor = '#ffffca';   ";
			        echo'</script>';



    }else if($tipo_actualizacion=="2"){


    	              echo'<script>';
			            echo'document.getElementById("monto_origina_deuda").readOnly = false;   ';
			            echo'document.getElementById("cantidad_original_deuda").readOnly = false;  ';
			            echo'document.getElementById("cantidad_de_cuotas_canceladas").readOnly = true;  ';
			            echo'document.getElementById("cantidad_de_cuotas_cancelar").readOnly = false;  ';
						echo'document.getElementById("cuotas_cancela").readOnly = false; ';
						echo'document.getElementById("monto_cuotas_a_cancelar").readOnly = false; ';
						echo'document.getElementById("saldo").readOnly = false; ';

						echo"document.getElementById('monto_origina_deuda').style.backgroundColor = '#ffffca';   ";
					    echo"document.getElementById('cantidad_original_deuda').style.backgroundColor = '#ffffca';   ";
					    echo"document.getElementById('cantidad_de_cuotas_canceladas').style.backgroundColor = '#ffffca';   ";
					    echo"document.getElementById('cantidad_de_cuotas_cancelar').style.backgroundColor = '#ffffca';   ";
						echo"document.getElementById('cuotas_cancela').style.backgroundColor = '#ffffca';   ";
						echo"document.getElementById('monto_cuotas_a_cancelar').style.backgroundColor = '#ffffca';   ";
						echo"document.getElementById('saldo').style.backgroundColor = '#ffffca';   ";
			        echo'</script>';



    }else if($cod_tipo_transaccion==null){



                    echo'<script>';
			            echo'document.getElementById("monto_origina_deuda").readOnly = true;   ';
			            echo'document.getElementById("cantidad_original_deuda").readOnly = true;  ';
			            echo'document.getElementById("cantidad_de_cuotas_canceladas").readOnly = true;  ';
			            echo'document.getElementById("cantidad_de_cuotas_cancelar").readOnly = true;  ';
						echo'document.getElementById("cuotas_cancela").readOnly = false; ';
						echo'document.getElementById("monto_cuotas_a_cancelar").readOnly = false; ';
						echo'document.getElementById("saldo").readOnly = false; ';

						echo"document.getElementById('monto_origina_deuda').style.backgroundColor = '#ffffff';   ";
					    echo"document.getElementById('cantidad_original_deuda').style.backgroundColor = '#ffffff';   ";
					    echo"document.getElementById('cantidad_de_cuotas_canceladas').style.backgroundColor = '#ffffff';   ";
					    echo"document.getElementById('cantidad_de_cuotas_cancelar').style.backgroundColor = '#ffffff';   ";
						echo"document.getElementById('cuotas_cancela').style.backgroundColor = '#ffffca';   ";
						echo"document.getElementById('monto_cuotas_a_cancelar').style.backgroundColor = '#ffffca';   ";
						echo"document.getElementById('saldo').style.backgroundColor = '#ffffca';   ";
			        echo'</script>';




    }//fin else


$this->set('monto_origina_deuda',           $monto_original);
$this->set('cantidad_original_deuda',       $numero_original_cuotas);
$this->set('cuotas_cancela',                $numero_cuotas_cancelar);
$this->set('cantidad_de_cuotas_canceladas', $numero_cuotas_canceladas);
$this->set('monto_cuotas_a_cancelar',              $monto_cuota);
$this->set('saldo',                    $saldo);
$this->set('cantidad_de_cuotas_cancelar',           $cantidad_de_cuotas_cancelar);


echo'<script>';
    echo'document.getElementById("guardar").disabled = false; ';
    echo'document.getElementById("modificar").disabled = true; ';
    echo'document.getElementById("eliminar").disabled = true; ';
 echo'</script>';



}//fin function





function eliminar(){

	$this->layout = "ajax";

    $cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep;



if(!isset($this->data['cnmp07']['monto_origina_deuda'])){           $this->data['cnmp07']['monto_origina_deuda']="0"; }
if(!isset($this->data['cnmp07']['cantidad_original_deuda'])){       $this->data['cnmp07']['cantidad_original_deuda']="0"; }
if(!isset($this->data['cnmp07']['cantidad_de_cuotas_cancelar'])){   $this->data['cnmp07']['cantidad_de_cuotas_cancelar']="0";  }
if(!isset($this->data['cnmp07']['cantidad_de_cuotas_canceladas'])){ $this->data['cnmp07']['cantidad_de_cuotas_canceladas']="0"; }
if(!isset($this->data['cnmp07']['monto_cuotas_a_cancelar'])){ $this->data['cnmp07']['monto_cuotas_a_cancelar']="0,00"; }
if(!isset($this->data['cnmp07']['saldo'])){ $this->data['cnmp07']['saldo'] = "0,00"; }

  $cod_presi                     =    $this->Session->read('SScodpresi');
  $cod_entidad                   =    $this->Session->read('SScodentidad');
  $cod_tipo_inst                 =    $this->Session->read('SScodtipoinst');
  $cod_inst                      =    $this->Session->read('SScodinst');
  $cod_dep                       =    $this->Session->read('SScoddep');
  $cod_tipo_nomina               =    $this->data['cnmp07']['cod_tipo_nomina'];
  $cod_cargo                     =    $this->data['cnmp07']['cod_cargo'];
  $cod_ficha                     =    $this->data['cnmp07']['codigo_ficha2'];
  $cod_tipo_transaccion          =    $this->data['cnmp07']['tipo_transaccion'];
  $cod_transaccion               =    $this->data['cnmp07']['cod_transaccion'];
  $fecha_transaccion             =    $this->data['cnmp07']['fecha_transaccion'];
  $monto_original                =    $this->Formato1($this->data['cnmp07']['monto_origina_deuda']);
  $numero_original_cuotas        =    $this->data['cnmp07']['cantidad_original_deuda'];
  $numero_cuotas_cancelar        =    $this->data['cnmp07']['cuotas_cancela'];
  $numero_cuotas_canceladas      =    $this->data['cnmp07']['cantidad_de_cuotas_canceladas'];
  $monto_cuota                   =    $this->Formato1($this->data['cnmp07']['monto_cuotas_a_cancelar']);
  $saldo                         =    $this->Formato1($this->data['cnmp07']['saldo']);
  $marca_fin_descuento           =    "0";
  $fecha_proceso                 =    date('d/m/Y');
  $username                      =    $_SESSION['nom_usuario'];


 if($this->cnmd07_transacciones_actuales->findCount($condicion.' and cod_tipo_nomina='.$cod_tipo_nomina.' and cod_cargo = '.$cod_cargo.' and cod_ficha = '.$cod_ficha.' and  cod_tipo_transaccion = '.$cod_tipo_transaccion.' and  cod_transaccion = '.$cod_transaccion.'')!=0){

$sql = "DELETE FROM cnmd07_transacciones_actuales WHERE ".$condicion.' and cod_tipo_nomina='.$cod_tipo_nomina.' and cod_cargo = '.$cod_cargo.' and cod_ficha = '.$cod_ficha.' and  cod_tipo_transaccion = '.$cod_tipo_transaccion.' and  cod_transaccion = '.$cod_transaccion.';';
$sql .= " DELETE FROM cnmd07_transacciones_prenomina WHERE ".$condicion.' and cod_tipo_nomina='.$cod_tipo_nomina.' and cod_cargo = '.$cod_cargo.' and cod_ficha = '.$cod_ficha.' and  cod_tipo_transaccion = '.$cod_tipo_transaccion.' and  cod_transaccion = '.$cod_transaccion.';';
$sql .= " DELETE FROM cnmd07_transacciones_suspendidas WHERE ".$condicion.' and cod_tipo_nomina='.$cod_tipo_nomina.' and cod_cargo = '.$cod_cargo.' and cod_ficha = '.$cod_ficha.' and  cod_tipo_transaccion = '.$cod_tipo_transaccion.' and  cod_transaccion = '.$cod_transaccion.';';
$this->cnmd07_transacciones_actuales->execute($sql);


echo'<script>';
            echo'document.getElementById("monto_origina_deuda").readOnly = true;   ';
            echo'document.getElementById("cantidad_original_deuda").readOnly = true;  ';
            echo'document.getElementById("cantidad_de_cuotas_canceladas").readOnly = true;  ';
            echo'document.getElementById("cantidad_de_cuotas_cancelar").readOnly = true;  ';
			echo'document.getElementById("cuotas_cancela").readOnly = true; ';
			echo'document.getElementById("monto_cuotas_a_cancelar").readOnly = true; ';

			echo'document.getElementById("monto_origina_deuda").value = "0,00"; ';
			echo'document.getElementById("saldo").value = "0,00"; ';
			echo'document.getElementById("monto_cuotas_a_cancelar").value = "0,00"; ';

			echo'document.getElementById("cantidad_original_deuda").value = "0";   ';
            echo'document.getElementById("cantidad_de_cuotas_canceladas").value = "0";  ';
            echo'document.getElementById("cantidad_de_cuotas_cancelar").value = "0";   ';
			echo'document.getElementById("cuotas_cancela").value = "1";  ';
			echo'document.getElementById("modificar").disabled = true; ';
            echo'document.getElementById("guardar").disabled = true; ';

            echo'document.getElementById("tipo_transaccion_1").checked = true;';
  			echo'document.getElementById("tipo_transaccion_2").checked = false;';

  			echo'document.getElementById("denominacion_transaccion").value = ""; ';
  			//echo'document.getElementById("td_cod_transaccion").innerHTML = "<select style=width:50%><option></option></select> ";';

			echo'document.getElementById("saldo").readOnly = true; ';

			 echo'document.getElementById("username").innerHTML = "'.$_SESSION['nom_usuario'].'"; ';
 echo'</script>';


echo'<script>';
    echo'document.getElementById("guardar").disabled = true; ';
    echo'document.getElementById("modificar").disabled = true; ';
     echo'document.getElementById("eliminar").disabled = true; ';
echo'</script>';

$this->set('Message_existe', 'Los datos fueron eliminados correctamente');

 }//







echo'<script>';
    echo"document.getElementById('monto_origina_deuda').style.backgroundColor = '#ffffff';   ";
    echo"document.getElementById('cantidad_original_deuda').style.backgroundColor = '#ffffff';   ";
    echo"document.getElementById('cantidad_de_cuotas_canceladas').style.backgroundColor = '#ffffff';   ";
    echo"document.getElementById('cantidad_de_cuotas_cancelar').style.backgroundColor = '#ffffff';   ";
	echo"document.getElementById('cuotas_cancela').style.backgroundColor = '#ffffff';   ";
	echo"document.getElementById('monto_cuotas_a_cancelar').style.backgroundColor = '#ffffff';   ";
	echo"document.getElementById('saldo').style.backgroundColor = '#ffffff';   ";
    echo"document.getElementById('campo_modificar').value='no';   ";
echo'</script>';





}//fin function


function eliminar_transaccion($cod_tipo_nomina,$cod_cargo,$cod_ficha,$cod_tipo_transaccion,$cod_transaccion){
	$this->layout = "ajax";

    $cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep;


    if($this->cnmd07_transacciones_actuales->findCount($condicion.' and cod_tipo_nomina='.$cod_tipo_nomina.' and cod_cargo = '.$cod_cargo.' and cod_ficha = '.$cod_ficha.' and  cod_tipo_transaccion = '.$cod_tipo_transaccion.' and  cod_transaccion = '.$cod_transaccion.'')!=0){
	$sql = "DELETE FROM cnmd07_transacciones_actuales WHERE ".$condicion.' and cod_tipo_nomina='.$cod_tipo_nomina.' and cod_cargo = '.$cod_cargo.' and cod_ficha = '.$cod_ficha.' and  cod_tipo_transaccion = '.$cod_tipo_transaccion.' and  cod_transaccion = '.$cod_transaccion.';';
	$sql .= " DELETE FROM cnmd07_transacciones_prenomina WHERE ".$condicion.' and cod_tipo_nomina='.$cod_tipo_nomina.' and cod_cargo = '.$cod_cargo.' and cod_ficha = '.$cod_ficha.' and  cod_tipo_transaccion = '.$cod_tipo_transaccion.' and  cod_transaccion = '.$cod_transaccion.';';
	$sql .= " DELETE FROM cnmd07_transacciones_suspendidas WHERE ".$condicion.' and cod_tipo_nomina='.$cod_tipo_nomina.' and cod_cargo = '.$cod_cargo.' and cod_ficha = '.$cod_ficha.' and  cod_tipo_transaccion = '.$cod_tipo_transaccion.' and  cod_transaccion = '.$cod_transaccion.';';
	//echo $sql;
	$this->cnmd07_transacciones_actuales->execute($sql);
	$this->set('Message_existe', 'Los datos fueron eliminados correctamente');
 }//
    $this->render('eliminar');
}//fin eliminar transaccion



function consulta_index($pagina=null,$tipo=1,$ccod_tipo_nomina=null, $ccod_cargo=null, $cod_ficha=null){

	$this->layout = "ajax";
    $cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep;
    $Ano=$this->ano_ejecucion();

     $lista = $this->Cnmd01->generateList($conditions = $this->condicion(), $order = 'cod_tipo_nomina', $limit = null, '{n}.Cnmd01.cod_tipo_nomina', '{n}.Cnmd01.denominacion');
	if($lista !=null){
		$this->concatenaN($lista, 'cod_tipo_nomina');
	}else{
		$this->set('cod_tipo_nomina','');
	}

}



function denominacion_nomina($codigo){
	$this->layout = "ajax";
	$b = $this->Cnmd01->findAll($this->SQLCA()." and cod_tipo_nomina=".$codigo,array('cod_tipo_nomina','denominacion'));
	$this->set("b",$b[0]['Cnmd01']['denominacion']);
}//fin cpcp02_denominacion


function codigo_nomina($codigo=null){
	$this->layout = "ajax";
	$a = $this->Cnmd01->findAll($this->SQLCA()." and cod_tipo_nomina=".$codigo,array('cod_tipo_nomina','denominacion', 'horas_laborables'));
    $this->set("a",$a[0]['Cnmd01']['cod_tipo_nomina']);
    $this->Session->write('tipo_nomina',$codigo);
    				echo "<script>";
					    echo "document.getElementById('datos_ventana').disabled=false;";
					echo "</script>";
}//fin cpcp02_codigo





function consulta($ccod_tipo_nomina=null, $ccod_cargo=null, $cod_ficha=null){

	$this->layout = "ajax";
    $cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep;
    $Ano=$this->ano_ejecucion();
    $lista = $this->Cnmd01->generateList($conditions = $this->condicion(), $order = 'cod_tipo_nomina', $limit = null, '{n}.Cnmd01.cod_tipo_nomina', '{n}.Cnmd01.denominacion');
	$this->concatenaN($lista, 'lista_cod_tipo_nomina');

	$ccod_tipo_nomina = " and cod_tipo_nomina=".$ccod_tipo_nomina." and cod_cargo='".$ccod_cargo."' and cod_ficha='".$cod_ficha."' ";
	$this->set('tipo',1);


    $result_cnmd07 = $this->v_cnmd07_transacciones_actuales_frecuencias2->findAll($condicion.$ccod_tipo_nomina, 'DISTINCT cod_tipo_nomina, cod_cargo, cod_ficha ',  'cod_tipo_nomina, cod_cargo, cod_ficha ASC',1,1, null);
    $contar        = $this->v_cnmd07_transacciones_actuales_frecuencias2->findAll($condicion.$ccod_tipo_nomina, 'DISTINCT cod_tipo_nomina, cod_cargo, cod_ficha ');
    $Tfilas=count($contar);
    $pagina = 1;

if($Tfilas!=0){
 $this->set('pag_cant',$pagina.'/'.$Tfilas);
 $this->set('ultimo',$Tfilas);

      $cod_tipo_nomina=$result_cnmd07[0]['v_cnmd07_transacciones_actuales_frecuencias2']['cod_tipo_nomina'];
      $cod_cargo=$result_cnmd07[0]['v_cnmd07_transacciones_actuales_frecuencias2']['cod_cargo'];
      $cod_ficha=$result_cnmd07[0]['v_cnmd07_transacciones_actuales_frecuencias2']['cod_ficha'];
      $datos_v_cnmd05 = $this->v_cnmd05->findAll($condicion.' and cod_tipo_nomina='.$cod_tipo_nomina.' and cod_cargo = '.$cod_cargo.' ');


	$fichas        =   $this->cnmd06_fichas->findAll($condicion.' and cod_tipo_nomina='.$cod_tipo_nomina.' and cod_cargo = '.$cod_cargo.' and cod_ficha = '.$cod_ficha.'   ');
	$cedula=  $fichas[0]['cnmd06_fichas']['cedula_identidad'];

 	$datos_cnmd07_transacciones_actuales = $this->v_cnmd07_transacciones_actuales_frecuencias2->findAll($condicion.' and cod_tipo_nomina='.$cod_tipo_nomina.' and cod_cargo = '.$cod_cargo.' and cod_ficha = '.$cod_ficha.' ', null, ' cod_tipo_transaccion,  cod_transaccion  ASC');
 	$datos_cnmd03_transacciones =   $this->cnmd03_transacciones->findAll(null, null, ' cod_tipo_transaccion,  cod_transaccion  ASC');
 	$datos_peronal =   $this->cnmd06_datos_personales->findAll(' cedula_identidad='.$cedula.'  ');
        $cedula              =  $datos_peronal[0]['cnmd06_datos_personales']['cedula_identidad'];
        $primer_apellido     =  $datos_peronal[0]['cnmd06_datos_personales']['primer_apellido'];
        $segundo_apellido    =  $datos_peronal[0]['cnmd06_datos_personales']['segundo_apellido'];
        $primer_nombre       =  $datos_peronal[0]['cnmd06_datos_personales']['primer_nombre'];
        $segundo_nombre      =  $datos_peronal[0]['cnmd06_datos_personales']['segundo_nombre'];


         $this->set('cedula', $cedula);
         $this->set('primer_apellido', $primer_apellido);
         $this->set('segundo_apellido', $segundo_apellido);
         $this->set('primer_nombre', $primer_nombre);
         $this->set('segundo_nombre', $segundo_nombre);


 $this->set('cod_tipo_nomina', $datos_v_cnmd05[0]['v_cnmd05']['cod_tipo_nomina']);
 $this->set('deno_tipo_nomina', $datos_v_cnmd05[0]['v_cnmd05']['tipo_nomina']);
 $this->set('cod_cargo', $datos_v_cnmd05[0]['v_cnmd05']['cod_cargo']);
 $this->set('cod_puesto', $datos_v_cnmd05[0]['v_cnmd05']['cod_puesto']);
 $this->set('cod_ficha', $cod_ficha);
 $this->set('denominacion', $datos_v_cnmd05[0]['v_cnmd05']['denominacion_clase']);
 $this->set('datos_cnmd07_transacciones_actuales', $datos_cnmd07_transacciones_actuales);
 $this->set('pag_num', $pagina);
 $this->set('pagina', $pagina);

$datos_sueldo =  $this->cnmd05->findAll($condicion.' and cod_tipo_nomina='.$cod_tipo_nomina.' and cod_cargo='.$cod_cargo, null, null);
$this->set('dato_sueldo', $datos_sueldo);

$this->set('siguiente',$pagina+1);
$this->set('anterior',$pagina-1);
$this->set('total_paginas',$Tfilas);

$this->bt_nav($Tfilas,$pagina);


$datos_cnmd01 = $this->Cnmd01->findAll($condicion." and cod_tipo_nomina=".$cod_tipo_nomina);
$this->set('frecuencia_pago',$datos_cnmd01[0]['Cnmd01']['frecuencia_pago']);
$this->set('periodo_desde',$datos_cnmd01[0]['Cnmd01']['periodo_desde']);
$this->set('periodo_hasta',$datos_cnmd01[0]['Cnmd01']['periodo_hasta']);
}else{

 $this->set('pag_num', 0);
 $this->set('totalPages_Recordset1', '');
 $this->set('errorMessage', 'No existen datos');

}//fin else



//$this->mostrar_datos_griya();


}//fin consulta

function consulta2($tipo=1,$ccod_tipo_nomina=null,$pagina=null){

	$this->layout = "ajax";


	$pagina=isset($pagina)?$pagina:1;
	if(isset($ccod_tipo_nomina) && $tipo==1){
		$ccod_tipo_nomina=" and 1=1";
	}else if(isset($ccod_tipo_nomina) && $tipo==2){
		$ccod_tipo_nomina=" and cod_tipo_nomina=".$ccod_tipo_nomina;
	}else{
         $ccod_tipo_nomina=" and 1=1";
	}



$this->consulta($pagina,$tipo,$ccod_tipo_nomina);
$this->render('consulta');

//$this->mostrar_datos_griya();


}//fin consulta

function consulta_original_juan($pag_num=null){

	$this->layout = "ajax";
    $cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep;


  $this->ano_ejecucion();


   $array = $this->cnmd07_transacciones_actuales->findAll($condicion, 'DISTINCT cod_tipo_nomina, cod_cargo, cod_ficha ',  'cod_tipo_nomina, cod_cargo, cod_ficha ASC', null);

    $i = 0;
 if($pag_num==null){$pag_num=0;}

 foreach($array as $aux){

 	$numero[$i]['cod_tipo_nomina'] =   $aux['cnmd07_transacciones_actuales']['cod_tipo_nomina'];
 	$numero[$i]['cod_cargo']       =   $aux['cnmd07_transacciones_actuales']['cod_cargo'];
 	$numero[$i]['cod_ficha']       =   $aux['cnmd07_transacciones_actuales']['cod_ficha'];

 	$i++;

} $i--;


if(isset($numero[$pag_num]['cod_tipo_nomina'])){


$datos_v_cnmd05 = $this->v_cnmd05->findAll($condicion.' and cod_tipo_nomina='.$numero[$pag_num]['cod_tipo_nomina'].' and cod_cargo = '.$numero[$pag_num]['cod_cargo'].' ');
$ii = 0;
 foreach($datos_v_cnmd05 as $datos_v_cnmd05_aux){
 	$v_cnmd05[$ii]['cod_tipo_nomina']          =         $datos_v_cnmd05_aux['v_cnmd05']['cod_tipo_nomina'];
 	$v_cnmd05[$ii]['tipo_nomina']              =         $datos_v_cnmd05_aux['v_cnmd05']['tipo_nomina'];
 	$v_cnmd05[$ii]['cod_cargo']                =         $datos_v_cnmd05_aux['v_cnmd05']['cod_cargo'];
 	$v_cnmd05[$ii]['cod_puesto']               =         $datos_v_cnmd05_aux['v_cnmd05']['cod_puesto'];
 	$v_cnmd05[$ii]['denominacion_clase']       =         $datos_v_cnmd05_aux['v_cnmd05']['denominacion_clase'];
 	$ii++;
} $ii--;



$fichas        =   $this->cnmd06_fichas->findAll($condicion.' and cod_tipo_nomina='.$numero[$pag_num]['cod_tipo_nomina'].' and cod_cargo = '.$numero[$pag_num]['cod_cargo'].' and cod_ficha = '.$numero[$pag_num]['cod_ficha'].'   ');
$fichas_aux    =   $fichas;
$iiii = 0;

 foreach($fichas_aux as $aux){
        $cedula=  $aux['cnmd06_fichas']['cedula_identidad'];
  }//fin foreach




 $datos_cnmd07_transacciones_actuales = $this->cnmd07_transacciones_actuales->findAll($condicion.' and cod_tipo_nomina='.$numero[$pag_num]['cod_tipo_nomina'].' and cod_cargo = '.$numero[$pag_num]['cod_cargo'].' and cod_ficha = '.$numero[$pag_num]['cod_ficha'].' ', null, ' cod_tipo_transaccion,  cod_transaccion  ASC');
 $datos_cnmd03_transacciones =   $this->cnmd03_transacciones->findAll(null, null, ' cod_tipo_transaccion,  cod_transaccion  ASC');
 $datos_peronal =   $this->cnmd06_datos_personales->findAll(' cedula_identidad='.$cedula.'  ');

 foreach($datos_peronal as $datos_peronal_aux){
        $cedula              =  $datos_peronal_aux['cnmd06_datos_personales']['cedula_identidad'];
        $primer_apellido     =  $datos_peronal_aux['cnmd06_datos_personales']['primer_apellido'];
        $segundo_apellido    =  $datos_peronal_aux['cnmd06_datos_personales']['segundo_apellido'];
        $primer_nombre       =  $datos_peronal_aux['cnmd06_datos_personales']['primer_nombre'];
        $segundo_nombre      =  $datos_peronal_aux['cnmd06_datos_personales']['segundo_nombre'];
  }//fin foreach


         $this->set('cedula', $cedula);
         $this->set('primer_apellido', $primer_apellido);
         $this->set('segundo_apellido', $segundo_apellido);
         $this->set('primer_nombre', $primer_nombre);
         $this->set('segundo_nombre', $segundo_nombre);


 $this->set('cod_tipo_nomina', $v_cnmd05[$ii]['cod_tipo_nomina']);
 $this->set('deno_tipo_nomina', $v_cnmd05[$ii]['tipo_nomina']);
 $this->set('cod_cargo', $v_cnmd05[$ii]['cod_cargo']);
 $this->set('cod_puesto', $v_cnmd05[$ii]['cod_puesto']);
 $this->set('cod_ficha', $numero[$pag_num]['cod_ficha']);
 $this->set('denominacion', $v_cnmd05[$ii]['denominacion_clase']);


 $this->set('datos_cnmd07_transacciones_actuales', $datos_cnmd07_transacciones_actuales);
 $this->set('datos_cnmd03_transacciones', $datos_cnmd03_transacciones);
 $this->set('pag_num', $pag_num);
 $this->set('totalPages_Recordset1', $i);




$datos_sueldo =  $this->cnmd05->findAll($condicion.' and cod_tipo_nomina='.$numero[$pag_num]['cod_tipo_nomina'].' and cod_cargo='.$numero[$pag_num]['cod_cargo'], null, null);
$this->set('dato_sueldo', $datos_sueldo);



}else{

 $this->set('pag_num', 0);
 $this->set('totalPages_Recordset1', '');
 $this->set('errorMessage', 'No existen datos');

}//fin else



//$this->mostrar_datos_griya();


}//fin consulta2





}//fin class



?>