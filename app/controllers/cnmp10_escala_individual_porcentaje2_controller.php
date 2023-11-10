<?php
/*
 * Fecha: 8/01/2010
 *
 * Por Erisk G. Aragol H.
 *
 * Herramienta utilizada: easyEclipse
 * sisap
 */
class Cnmp10EscalaIndividualporcentaje2Controller extends AppController {
   var $name = 'cnmp10_escala_individual_porcentaje2';
   var $uses = array('Cnmd01',  'cnmd03_transacciones', 'cnmd10_control_escenarios','cnmd10_individual_porcentaje','cnmd10_individual_porcentaje_cantidad','v_cnmd06_fichas_datos_personales');
   var $helpers = array('Html','Ajax','Javascript', 'Sisap');


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

 }



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


function condicion(){
	  $cod_presi = $this->Session->read('SScodpresi');
	  $cod_entidad = $this->Session->read('SScodentidad');
	  $cod_tipo_inst = $this->Session->read('SScodtipoinst');
	  $cod_inst = $this->Session->read('SScodinst');
	  $cod_dep = $this->Session->read('SScoddep');
	  $modulo = $this->Session->read('Modulo');
	  $condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep;
  	  return $condicion;
}

function index(){
	$this->layout="ajax";
	$lista = $this->Cnmd01->generateList($conditions = $this->condicion(), $order = 'cod_tipo_nomina', $limit = null, '{n}.Cnmd01.cod_tipo_nomina', '{n}.Cnmd01.denominacion');
	if($lista!=null){
		$this->concatenaN($lista, 'nomina');
	}else{
		$this->set('nomina',array());
	}

}//fin index



function seleccion_nomina($nomina=null){
	$this->layout="ajax";
	if($nomina!=null){
		$this->set('cod_nomina', $nomina);

		$lista = $this->Cnmd01->generateList($conditions = $this->condicion(), $order = 'cod_tipo_nomina', $limit = null, '{n}.Cnmd01.cod_tipo_nomina', '{n}.Cnmd01.denominacion');
		$this->concatenaN($lista, 'nomina');

		$deno_nomina = $this->Cnmd01->field('denominacion', $conditions = $this->condicion()." and Cnmd01.cod_tipo_nomina='$nomina'", $order ="cod_tipo_nomina ASC");
		$this->set('deno_nomina', $deno_nomina);

		//////////////////////AQUI ESCENARIOS SIMILARES REGISTRADOS EN ESTE TIPO DE NÓMINA/////////////
		$this->carga_transaccion($nomina);
		///////////////////////////////////////////////////////////////////////////////////////////////

		$cnmd03 = $this->cnmd03_transacciones->generateList2($conditions ='cod_tipo_transaccion = 1', $order = null, $limit = null, '{n}.cnmd03_transacciones.cod_transaccion', '{n}.cnmd03_transacciones.denominacion');
		if($lista!=null){
			$this->concatenaN($cnmd03, 'transaccion');
		}else{
			$this->set('transaccion',array());
		}

	}else{
		$this->index();
		$this->render('index');
	}

}//fin seleccion_nomina

function carga_transaccion($nomina=null){
	$this->layout="ajax";
	//////////////////////AQUI ESCENARIOS SIMILARES REGISTRADOS EN ESTE TIPO DE NÓMINA/////////////
	$sql="select
		  a.cod_tipo_nomina,
		  a.cod_transaccion,
		  a.porcentaje,
		  (select b.denominacion from cnmd03_transacciones b where b.cod_transaccion=a.cod_transaccion and b.cod_tipo_transaccion=1 ) as deno_transaccion
		  from cnmd10_individual_porcentaje a	where ".$this->SQLCA()." and cod_tipo_nomina='$nomina' order by cod_transaccion";

	$transacciones=$this->cnmd10_individual_porcentaje->execute($sql);
	if($transacciones!=null){
		$this->set('escenarios',$transacciones);
	}else{
		$this->set('escenarios',null);
	}
	///////////////////////////////////////////////////////////////////////////////////////////////
}//fin carga_transaccion



function seleccion_trans($nomina=null,$transaccion=null,$otro=null){
	$this->layout="ajax";
	$this->set('cod_nomina', $nomina);
	if($transaccion!=null){

		$this->set('cod_transaccion', $transaccion);

		$cnmd03 = $this->cnmd03_transacciones->generateList2($conditions ='cod_tipo_transaccion = 1', $order = null, $limit = null, '{n}.cnmd03_transacciones.cod_transaccion', '{n}.cnmd03_transacciones.denominacion');
		$this->concatenaN($cnmd03, 'transaccion');

		$deno_trans = $this->cnmd03_transacciones->field('cnmd03_transacciones.denominacion', $conditions = "cnmd03_transacciones.cod_transaccion='$transaccion' and cod_tipo_transaccion=1", $order ="cod_transaccion ASC");
		$this->set('deno_transaccion', $deno_trans);

		////////////////AQUI VERIFICO LA PARTE DE LOS RADIOS SI EXISTE O NO LA TRANSACCION REGISTRADA///////////////////////////
		$ubicacion = $this->cnmd10_control_escenarios->field('cnmd10_control_escenarios.ubicacion_escenario', $conditions = $this->condicion()." and cod_tipo_nomina='$nomina' and cod_tipo_transaccion=1 and cod_transaccion='$transaccion'", $order =null);
		if($ubicacion!=null){
			///////////////ESTO ES PARA CUANDO RENDERICE CUANDO GUARDE NO ME MUESTRE EL MENSAJE DE LA UBICACIÓN//////////////////
			if(isset($otro)){
				$this->set('ubicacion',true);//////////MENSAJE NO
			}else{
				//////////////////////////
				$this->set('Message_existe','ESTA TRANSACCION YA FUE CREADA EN EL ESCENARIO '.$ubicacion);
				$this->set('ubicacion',$ubicacion);
			}

		}else{
			$this->set('ubicacion',null);
		}


		$sql="select
			  a.cod_tipo_nomina,
			  a.cod_tipo_transaccion,
			  a.cod_transaccion,
			  a.cod_frecuencia,
			  a.cod_condicion,
			  a.codi_tipo_transaccion,
			  a.codi_transaccion,
			  a.activar_frecuencia_eventual,
			  a.porcentaje,
			  (select b.denominacion from cnmd03_transacciones b where b.cod_transaccion=a.codi_transaccion and b.cod_tipo_transaccion=a.codi_tipo_transaccion ) as deno_transaccion
			  from cnmd10_individual_porcentaje a	where ".$this->SQLCA()." and a.cod_tipo_nomina='$nomina' and a.cod_transaccion='$transaccion' order by a.cod_transaccion";

		$verifica=$this->cnmd10_individual_porcentaje_cantidad->execute($sql);
		if($verifica!=null){
			$this->set('verifica',$verifica);
			$cnmd03 = $this->cnmd03_transacciones->generateList3($conditions ='cod_tipo_transaccion ='.$verifica[0][0]['codi_tipo_transaccion'], $order = null, $limit = null, '{n}.cnmd03_transacciones.cod_transaccion', '{n}.cnmd03_transacciones.denominacion');
			$this->concatenaN($cnmd03, 'select_transaccion');


		}else{
			$this->set('verifica',null);
			$this->set('select_transaccion',array());
			$radios=$this->cnmd03_transacciones->execute("select * from cnmd03_transacciones where cod_tipo_transaccion=1 and cod_transaccion='$transaccion'");
			$this->Session->delete('frecuencia');
			if($radios[0][0]['uso_transaccion']==7){
				$this->Session->write('frecuencia',2);
				echo "<script>document.getElementById('frecuencia_2').checked=true;</script>";
				echo "<script>escenario_show();</script>";
			}else{
				$this->Session->write('frecuencia',1);
				echo "<script>document.getElementById('frecuencia_1').checked=true;</script>";
				echo "<script>escenario_show();</script>";
			}
			echo "<script>document.getElementById('frecuencia_1').disabled='disabled';</script>";
			echo "<script>document.getElementById('frecuencia_2').disabled='disabled';</script>";
		}

		////////////////////////////////AQUI LA GRILLA////////////////////////////////////
			$grilla="select
			  a.cod_presi,
			  a.cod_entidad,
			  a.cod_tipo_inst,
			  a.cod_inst,
			  a.cod_dep,
			  a.cod_tipo_nomina,
			  a.cod_cargo,
			  a.cod_ficha,
			  a.cod_transaccion,
			  a.cantidad,
			  (select b.cedula_identidad from v_cnmd06_fichas_datos_personales b where b.cod_presi=a.cod_presi and b.cod_entidad=a.cod_entidad and b.cod_tipo_inst=a.cod_tipo_inst and b.cod_inst=a.cod_inst and b.cod_dep=a.cod_dep and b.cod_tipo_nomina=a.cod_tipo_nomina and b.cod_cargo=a.cod_cargo and b.cod_ficha=a.cod_ficha) as cedula,
			  (select b.primer_apellido from v_cnmd06_fichas_datos_personales b where b.cod_presi=a.cod_presi and b.cod_entidad=a.cod_entidad and b.cod_tipo_inst=a.cod_tipo_inst and b.cod_inst=a.cod_inst and b.cod_dep=a.cod_dep and b.cod_tipo_nomina=a.cod_tipo_nomina and b.cod_cargo=a.cod_cargo and b.cod_ficha=a.cod_ficha) as primer_apellido,
			  (select b.segundo_apellido from v_cnmd06_fichas_datos_personales b where b.cod_presi=a.cod_presi and b.cod_entidad=a.cod_entidad and b.cod_tipo_inst=a.cod_tipo_inst and b.cod_inst=a.cod_inst and b.cod_dep=a.cod_dep and b.cod_tipo_nomina=a.cod_tipo_nomina and b.cod_cargo=a.cod_cargo and b.cod_ficha=a.cod_ficha) as segundo_apellido,
			  (select b.primer_nombre from v_cnmd06_fichas_datos_personales b where b.cod_presi=a.cod_presi and b.cod_entidad=a.cod_entidad and b.cod_tipo_inst=a.cod_tipo_inst and b.cod_inst=a.cod_inst and b.cod_dep=a.cod_dep and b.cod_tipo_nomina=a.cod_tipo_nomina and b.cod_cargo=a.cod_cargo and b.cod_ficha=a.cod_ficha) as primer_nombre,
			  (select b.segundo_nombre from v_cnmd06_fichas_datos_personales b where b.cod_presi=a.cod_presi and b.cod_entidad=a.cod_entidad and b.cod_tipo_inst=a.cod_tipo_inst and b.cod_inst=a.cod_inst and b.cod_dep=a.cod_dep and b.cod_tipo_nomina=a.cod_tipo_nomina and b.cod_cargo=a.cod_cargo and b.cod_ficha=a.cod_ficha) as segundo_nombre
			  from cnmd10_individual_porcentaje_cantidad a where ".$this->SQLCA()." and a.cod_tipo_nomina=".$nomina." and cod_transaccion=".$transaccion." ORDER BY (select b.cedula_identidad from v_cnmd06_fichas_datos_personales b where b.cod_presi=a.cod_presi and b.cod_entidad=a.cod_entidad and b.cod_tipo_inst=a.cod_tipo_inst and b.cod_inst=a.cod_inst and b.cod_dep=a.cod_dep and b.cod_tipo_nomina=a.cod_tipo_nomina and b.cod_cargo=a.cod_cargo and b.cod_ficha=a.cod_ficha) ASC";
			  $datos=$this->cnmd10_individual_porcentaje_cantidad->execute($grilla);
			  $this->set('datos',$datos);

		//////////////////////////////AQUI TIPOS DE NOMINAS DONDE APARECE TAMBIEN ESTE ESCENARIO///////////////////
		$this->otros_escenarios($nomina,$transaccion);
		///////////////////////////////////////////////////////
	}else{
		$this->set('cod_transaccion',null);
		$cnmd03 = $this->cnmd03_transacciones->generateList2($conditions ='cod_tipo_transaccion = 1', $order = null, $limit = null, '{n}.cnmd03_transacciones.cod_transaccion', '{n}.cnmd03_transacciones.denominacion');
		$this->concatenaN($cnmd03, 'transaccion');
		$this->set('readonly','readonly');

	}

}//fin seleccion_trans





function select_trans($cod_trans=null){
	$this->layout="ajax";
	if($cod_trans!=null){
		$cnmd03 = $this->cnmd03_transacciones->generateList3($conditions ='cod_tipo_transaccion ='.$cod_trans, $order = null, $limit = null, '{n}.cnmd03_transacciones.cod_transaccion', '{n}.cnmd03_transacciones.denominacion');
		if($cnmd03){
			$this->concatenaN($cnmd03, 'radio');
		}else{
			$this->set('radio',array());
		}
	}else{
		$this->set('radio',array());
	}
	$this->set('codi_trans',$cod_trans);
}//fin select_trans


function codi_deno_trans($codi_trans=null,$cod_trans=null){
	$this->layout="ajax";
	if($cod_trans!=null){
		$this->set('cod',mascara($cod_trans,3));
		$deno_trans = $this->cnmd03_transacciones->field('cnmd03_transacciones.denominacion', $conditions = "cnmd03_transacciones.cod_transaccion='$cod_trans' and cod_tipo_transaccion=".$codi_trans, $order ="cod_transaccion ASC");
		$this->set('deno_transaccion', $deno_trans);
	}else{
		$this->set('cod','');
		$this->set('deno_transaccion', '');
	}


}//fin codi_deno_trans


function cod_deno_transferir($nomina=null){
	$this->layout="ajax";
	if($nomina!=null){
		$this->set('cod',mascara($nomina,3));
		$deno_nomina = $this->Cnmd01->field('denominacion', $conditions = $this->condicion()." and Cnmd01.cod_tipo_nomina='$nomina'", $order ="cod_tipo_nomina ASC");
		$this->set('deno_nomina', $deno_nomina);
		$this->set('disabled','true');
	}else{
		$this->set('cod','');
		$this->set('deno_nomina', '');
		$this->set('disabled','false');
	}

}//fin cod_deno_transferir



function buscar_ficha($var1=null,$nomina=null,$transaccion=null){
	$this->layout="ajax";
	$this->set("opcion",$var1);
	$this->set('nomina',$nomina);
	$this->set('transaccion',$transaccion);
	$this->Session->delete('pista');
}//fin buscar_ficha



function buscar_por_pista($nomina=null,$transaccion=null,$var1=null, $var2=null, $var3=null){
$this->layout="ajax";
 $nomina=$nomina;
 $this->set('nomina',$nomina);
 $this->set('transaccion',$transaccion);
    if($var3==null){
    	$var2 = strtoupper($var2);
					$this->Session->write('pista', $var2);
					$sql     =" (".$this->busca_separado(array("super_busqueda", "denominacion_clase","cod_cargo","cod_ficha"), $var2).") ";
					$condicion=$this->SQLCA()." and cod_tipo_nomina=".$nomina." and condicion_actividad=1  and ".$sql;
					$Tfilas=$this->v_cnmd06_fichas_datos_personales->findCount($condicion);//
					        if($Tfilas!=0){
					        	$pagina=1;
					        	$Tfilas=(int)ceil($Tfilas/100);
					        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
								$this->set('total_paginas',$Tfilas);
								$this->set('pagina_actual',$pagina);
								$this->set('ultimo',$Tfilas);
					     	    $datos_filas=$this->v_cnmd06_fichas_datos_personales->findAll($condicion,null,"cod_ficha ASC",100,1,null);
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
						$sql     =" (".$this->busca_separado(array("super_busqueda", "denominacion_clase","cod_cargo","cod_ficha"), $var22).") ";
						$condicion=$this->SQLCA()." and cod_tipo_nomina=".$nomina." and condicion_actividad=1 and ".$sql;
						$Tfilas=$this->v_cnmd06_fichas_datos_personales->findCount($condicion);
						        if($Tfilas!=0){
						        	$pagina=$var3;
						        	$Tfilas=(int)ceil($Tfilas/100);
						        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
									$this->set('total_paginas',$Tfilas);
									$this->set('pagina_actual',$pagina);
									$this->set('ultimo',$Tfilas);
									$datos_filas=$this->v_cnmd06_fichas_datos_personales->findAll($condicion,null,"cod_ficha ASC",100,$pagina,null);
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

function seleccion_busqueda($opcion=null,$nomina=null,$transaccion=null,$cargo=null,$ficha=null){
	$this->layout="ajax";



	$dato=$this->v_cnmd06_fichas_datos_personales->execute("select * from v_cnmd06_fichas_datos_personales where ".$this->SQLCA()." and cod_tipo_nomina=".$nomina." and cod_cargo=".$cargo." and cod_ficha=".$ficha);
	$this->set('dato',$dato);


	$datos=$this->cnmd10_individual_porcentaje_cantidad->FindCount($this->SQLCA()." and cod_tipo_nomina=".$nomina." and cod_transaccion=".$transaccion." and cod_cargo=".$cargo." and cod_ficha=".$ficha,null,null);
	if($datos!=0){
		$this->set('errorMessage','este trabajador ya existe registrado en la transacción');
	}
	$this->set('datos',$datos);
}// fin seleccion_busqueda


function guardar(){
	$this->layout="ajax";
	$cod_presi = $this->Session->read('SScodpresi');
    $cod_entidad = $this->Session->read('SScodentidad');
    $cod_tipo_inst = $this->Session->read('SScodtipoinst');
    $cod_inst = $this->Session->read('SScodinst');
    $cod_dep = $this->Session->read('SScoddep');

    if(empty($this->data['cnmp10']['cod_cargo'])){
    	$this->set('errorMessage','debe buscar y seleccionar un trabajador');
    }else if(empty($this->data['cnmp10']['cantidad']) || $this->Formato1($this->data['cnmp10']['cantidad'])==0){
    	$this->set('errorMessage','Ingrese una cantidad');
    }else{
		$cod_tipo_nomina      = $this->data['cnmp10']['cod_nomina'];
	    $cod_tipo_transaccion = 1;
	    $cod_transaccion      = $this->data['cnmp10']['cod_transaccion'];
		$cod_cargo            = $this->data['cnmp10']['cod_cargo'];
		$cod_ficha            = $this->data['cnmp10']['cod_ficha'];
		$cantidad             = $this->Formato1($this->data['cnmp10']['cantidad']);
	    $ubicacion_escenario = strtoupper('ASIGNACIÓN INDIVIDUAL EN PORCENTAJE SEGÚN CANTIDAD QUE LE CORRESPONDE');
	    $this->set('nomina',$cod_tipo_nomina);
	    $this->set('transaccion',$cod_transaccion);

		$verifica=$this->cnmd10_individual_porcentaje->FindCount($this->SQLCA()." and cod_tipo_nomina='$cod_tipo_nomina' and cod_transaccion='$cod_transaccion'");
		if($verifica==0){/////////// AQUI COMPRUEBO SI YA EXISTE EL ESCENARIO,SI NO EXISTE REGISTRO TODOS LOS DATOS INCLUYENDO LOS RADIOS SINO REGISTRO SOLO LA ESCALA
				if(empty($this->data['cnmp10']['porcentaje']) || $this->Formato1($this->data['cnmp10']['porcentaje'])==0){
			    	$this->set('errorMessage','Debe ingresar el porcentaje');
			    }else if($this->data['cnmp10']['condicion']==2 && empty($this->data['cnmp10']['tipo_trans'])){
			    	$this->set('errorMessage','Debe seleccionar el tipo de transacción');
			    }else if(!empty($this->data['cnmp10']['tipo_trans']) && empty($this->data['cnmp10']['select4'])){
			    	$this->set('errorMessage','Debe seleccionar el código de transacción');
			    }else {
			    	$porcentaje=$this->Formato1($this->data['cnmp10']['porcentaje']);
			    	$cod_frecuencia       = $this->Session->read('frecuencia');
					$cod_condicion        = $this->data['cnmp10']['condicion'];
			    	if(!isset($this->data['cnmp10']['escenario'])){
						$activar_frecuencia_eventual = 2;
					}else{
						$activar_frecuencia_eventual = $this->data['cnmp10']['escenario'];
					}

					if(!isset($this->data['cnmp10']['tipo_trans'])){
						$codi_tipo_transaccion = 0;
						$codi_transaccion      = 0;
					}else{
						$codi_tipo_transaccion = $this->data['cnmp10']['tipo_trans'];
						$codi_transaccion      = $this->data['cnmp10']['select4'];
					}

					$insert1 = "Begin;INSERT INTO cnmd10_individual_porcentaje VALUES('$cod_presi', '$cod_entidad', '$cod_tipo_inst', '$cod_inst', '$cod_dep', '$cod_tipo_nomina', '$cod_tipo_transaccion', '$cod_transaccion','$porcentaje','$cod_frecuencia', '$cod_condicion', '$codi_tipo_transaccion', '$codi_transaccion', '$activar_frecuencia_eventual')";
					$sw1 = $this->cnmd10_individual_porcentaje->execute($insert1);
					if($sw1>1){
						$insert2 = "INSERT INTO cnmd10_individual_porcentaje_cantidad VALUES('$cod_presi', '$cod_entidad', '$cod_tipo_inst', '$cod_inst', '$cod_dep', '$cod_tipo_nomina','$cod_cargo','$cod_ficha', '$cod_tipo_transaccion', '$cod_transaccion','$cantidad')";
						$sw2 = $this->cnmd10_individual_porcentaje_cantidad->execute($insert2);
						if($sw2>1){
							$insert3 = "INSERT INTO cnmd10_control_de_escenarios VALUES('$cod_presi', '$cod_entidad', '$cod_tipo_inst', '$cod_inst', '$cod_dep', '$cod_tipo_nomina', '$cod_tipo_transaccion', '$cod_transaccion', '$ubicacion_escenario')";
							$sw3 = $this->cnmd10_individual_porcentaje->execute($insert3);
							if($sw3>1){
								$this->cnmd10_individual_porcentaje->execute('COMMIT');
								$this->set('Message_existe','Registro exitoso');
								$this->set('guardado','si');
							}else{
								$this->cnmd10_individual_porcentaje->execute('ROLLBACK');
								$this->set('errorMessage','INSERCIÓN DE DATOS FALLIDAS');
							}
						}else{
							$this->cnmd10_individual_porcentaje->execute('ROLLBACK');
							$this->set('errorMessage','INSERCIÓN DE DATOS FALLIDAS');
						}
					}else{
						$this->cnmd10_individual_porcentaje->execute('ROLLBACK');
						$this->set('errorMessage','INSERCIÓN DE DATOS FALLIDAS');
					}

			 }

		}else{
				$insert2 = "INSERT INTO cnmd10_individual_porcentaje_cantidad VALUES('$cod_presi', '$cod_entidad', '$cod_tipo_inst', '$cod_inst', '$cod_dep', '$cod_tipo_nomina','$cod_cargo','$cod_ficha', '$cod_tipo_transaccion', '$cod_transaccion','$cantidad')";
				$sw2 = $this->cnmd10_individual_porcentaje_cantidad->execute($insert2);
				if($sw2>1){
					$this->set('Message_existe','Registro exitoso');
					$this->set('guardado','si');
				}else{
					$this->set('errorMessage','INSERCIÓN DE DATOS FALLIDAS');
				}

		}

	}

}//FIN GUARDAR



function guardar_transferir($nomina=null,$transaccion=null){
	$this->layout="ajax";
	$cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');

	  $cod_tipo_nomina     =$nomina;
	  $cod_transaccion     =$transaccion;
	  $cod_transferir      =$this->data['cnmp10']['cod_transferir'];
	  $cod_tipo_transaccion=1;
	  $this->set('nomina',$cod_tipo_nomina);
	  $this->set('transaccion',$cod_transaccion);

	  $ubicacion = $this->cnmd10_control_escenarios->execute("select * from cnmd10_control_de_escenarios where ".$this->SQLCA()." and cod_tipo_nomina='$cod_transferir' and cod_tipo_transaccion=1 and cod_transaccion='$cod_transaccion'");
	  if($ubicacion==null){
		  $data=$this->cnmd10_individual_porcentaje->execute("select * from cnmd10_individual_porcentaje where ".$this->condicion()." and cod_tipo_nomina=".$cod_tipo_nomina." and cod_tipo_transaccion=1 and cod_transaccion=".$cod_transaccion);
		  $data2=$this->cnmd10_individual_porcentaje_cantidad->execute("select * from cnmd10_individual_porcentaje_cantidad where ".$this->condicion()." and cod_tipo_nomina=".$cod_tipo_nomina." and cod_tipo_transaccion=1 and cod_transaccion=".$cod_transaccion);
		  $ubicacion_escenario = strtoupper('ASIGNACIÓN INDIVIDUAL EN PORCENTAJE SEGÚN CANTIDAD QUE LE CORRESPONDE');
		  $cod_frecuencia       		 = $data[0][0]['cod_frecuencia'];
		  $cod_condicion         		 = $data[0][0]['cod_condicion'];
		  $codi_tipo_transaccion 		 = $data[0][0]['codi_tipo_transaccion'];
		  $codi_transaccion              = $data[0][0]['codi_transaccion'];
		  $activar_frecuencia_eventual   = $data[0][0]['activar_frecuencia_eventual'];
		  $insert1 = "Begin;INSERT INTO cnmd10_individual_porcentaje VALUES('$cod_presi', '$cod_entidad', '$cod_tipo_inst', '$cod_inst', '$cod_dep', '$cod_transferir', '$cod_tipo_transaccion', '$cod_transaccion','$cod_frecuencia', '$cod_condicion', '$codi_tipo_transaccion', '$codi_transaccion', '$activar_frecuencia_eventual')";
		  $sw      = $this->cnmd10_individual_porcentaje->execute($insert1);
		  if($sw>1){
			  for($j=0;$j<count($data2);$j++){
				  $escala  = $data2[$j][0]['escala'];
				  $desde   = $data2[$j][0]['desde_ano'];
				  $hasta   = $data2[$j][0]['hasta_ano'];
				  $monto   = $data2[$j][0]['dias'];
				  $insert2 = "INSERT INTO cnmd10_individual_porcentaje_cantidad VALUES('$cod_presi', '$cod_entidad', '$cod_tipo_inst', '$cod_inst', '$cod_dep', '$cod_transferir', '$cod_tipo_transaccion', '$cod_transaccion','$escala','$desde','$hasta', '$monto')";
				  $sw2     = $this->cnmd10_individual_porcentaje_cantidad->execute($insert2);
			  }
			  if($sw2>1){
			  		$insert3 = "INSERT INTO cnmd10_control_de_escenarios VALUES('$cod_presi', '$cod_entidad', '$cod_tipo_inst', '$cod_inst', '$cod_dep', '$cod_transferir', '$cod_tipo_transaccion', '$cod_transaccion', '$ubicacion_escenario')";
					$sw3 = $this->cnmd10_control_escenarios->execute($insert3);
					if($sw3>1){
						$this->cnmd10_individual_porcentaje->execute('COMMIT');
						$this->set('Message_existe', 'Transferencia realizada con exito');
						$this->set('guardado','si');
					}else{
						$this->cnmd10_individual_porcentaje->execute('ROLLBACK');
						$this->set('errorMessage', 'Transferencia sin exito');
					}
			  }else{
			  		$this->cnmd10_individual_porcentaje->execute('ROLLBACK');
			  		$this->set('errorMessage', 'Transferencia sin exito');
			  }
		  }else{
				$this->cnmd10_individual_porcentaje->execute('ROLLBACK');
				$this->set('errorMessage', 'Transferencia sin exito');
		  }
	  }else{
	  		$this->set('errorMessage','ESTA TRANSACCION YA FUE CREADA EN EL ESCENARIO '.$ubicacion[0][0]['ubicacion_escenario']);
	  }

}// fin guardar_transferir



function otros_escenarios($nomina=null,$transaccion=null){
	$this->layout="ajax";
	$sql="select
			 a.cod_presi,a.cod_entidad,a.cod_tipo_inst,a.cod_inst,a.cod_dep,
			 a.cod_tipo_nomina,
			 a.cod_tipo_transaccion,
			 a.cod_transaccion,
			 (select b.denominacion from cnmd01 b where b.cod_presi=a.cod_presi and b.cod_entidad=a.cod_entidad and b.cod_tipo_inst=a.cod_tipo_inst and b.cod_inst=a.cod_inst and b.cod_dep=a.cod_dep and b.cod_tipo_nomina=a.cod_tipo_nomina) as deno_nomina
			 from cnmd10_individual_porcentaje a where ".$this->SQLCA()." and a.cod_transaccion=".$transaccion." and a.cod_tipo_nomina!=".$nomina." order by a.cod_tipo_nomina asc";
	$otras_nominas=$this->cnmd10_individual_porcentaje_cantidad->execute($sql);
	 if($otras_nominas!=null){
	 	 $this->set('opciones',$otras_nominas);
	 }else{
	 	 $this->set('opciones',null);
	 }
}//fin otros_escenarios



function eliminar($nomina=null,$transaccion=null){
	$this->layout="ajax";
	if(isset($nomina) && isset($transaccion)){
		$this->set('nomina',$nomina);
		$delete1="Begin;DELETE FROM cnmd10_individual_porcentaje WHERE ".$this->SQLCA()." and cod_tipo_nomina='$nomina' and cod_transaccion='$transaccion'";
		$sw=$this->cnmd10_individual_porcentaje->execute($delete1);
		if($sw>1){
			$delete2="DELETE FROM cnmd10_individual_porcentaje_cantidad WHERE ".$this->SQLCA()." and cod_tipo_nomina='$nomina' and cod_transaccion='$transaccion'";
			$sw2=$this->cnmd10_individual_porcentaje_cantidad->execute($delete2);
			if($sw2>1){
				$delete3 = "DELETE FROM cnmd10_control_de_escenarios WHERE ".$this->SQLCA()." and cod_tipo_nomina='$nomina' and cod_tipo_transaccion=1 and cod_transaccion='$transaccion'";
				$sw3=$this->cnmd10_individual_porcentaje_cantidad->execute($delete3);
				if($sw3>1){
					$this->cnmd10_individual_porcentaje->execute('COMMIT');
					$this->set('Message_existe', 'EL ESCENARIO FUE ELIMINADO CON EXITO');
					$this->set('eliminado', 'si');
				}else{
					$this->cnmd10_individual_porcentaje->execute('ROLLBACK');
					$this->set('errorMessage','El registro no pudo ser eliminado');
				}
			}else{
				$this->cnmd10_individual_porcentaje->execute('ROLLBACK');
				$this->set('errorMessage','El registro no pudo ser eliminado');
			}
		}else{
			$this->cnmd10_individual_porcentaje->execute('ROLLBACK');
			$this->set('errorMessage','El registro no pudo ser eliminado');
		}
	}else{
		$this->set('errorMessage','El registro no pudo ser eliminado');
	}
}//FIN ELIMINAR




function eliminar_items($cod_nomina=null, $cod_trans=null,$cargo=null,$ficha=null){
	$this->layout="ajax";

	if($this->cnmd10_individual_porcentaje_cantidad->FindCount($this->SQLCA()." and cod_tipo_nomina='$cod_nomina' and cod_tipo_transaccion=1 and cod_transaccion='$cod_trans'")==1){
		$this->set('errorMessage', 'NO PODRA ELIMINAR TODAS LAS FICHAS');
	}else{
		$sql_eliminar2="DELETE FROM cnmd10_individual_porcentaje_cantidad WHERE ".$this->SQLCA()." and cod_tipo_nomina='$cod_nomina' and cod_tipo_transaccion=1 and cod_transaccion='$cod_trans' and cod_cargo='$cargo' and cod_ficha='$ficha'";
		$sw2 = $this->cnmd10_individual_porcentaje_cantidad->execute($sql_eliminar2);
		if($sw2 > 1){
//			$this->set('Message_existe', 'LA TRANSACCION SE ELIMINO CON EXITO');
		}else{
			$this->set('errorMessage', 'NO SE PUDO ELIMINAR');
		}
	}

	////////////////////////////////AQUI LA GRILLA////////////////////////////////////
			$grilla="select
			  a.cod_presi,
			  a.cod_entidad,
			  a.cod_tipo_inst,
			  a.cod_inst,
			  a.cod_dep,
			  a.cod_tipo_nomina,
			  a.cod_cargo,
			  a.cod_ficha,
			  a.cod_transaccion,
			  a.cantidad,
			  (select b.cedula_identidad from v_cnmd06_fichas_datos_personales b where b.cod_presi=a.cod_presi and b.cod_entidad=a.cod_entidad and b.cod_tipo_inst=a.cod_tipo_inst and b.cod_inst=a.cod_inst and b.cod_dep=a.cod_dep and b.cod_tipo_nomina=a.cod_tipo_nomina and b.cod_cargo=a.cod_cargo and b.cod_ficha=a.cod_ficha) as cedula,
			  (select b.primer_apellido from v_cnmd06_fichas_datos_personales b where b.cod_presi=a.cod_presi and b.cod_entidad=a.cod_entidad and b.cod_tipo_inst=a.cod_tipo_inst and b.cod_inst=a.cod_inst and b.cod_dep=a.cod_dep and b.cod_tipo_nomina=a.cod_tipo_nomina and b.cod_cargo=a.cod_cargo and b.cod_ficha=a.cod_ficha) as primer_apellido,
			  (select b.segundo_apellido from v_cnmd06_fichas_datos_personales b where b.cod_presi=a.cod_presi and b.cod_entidad=a.cod_entidad and b.cod_tipo_inst=a.cod_tipo_inst and b.cod_inst=a.cod_inst and b.cod_dep=a.cod_dep and b.cod_tipo_nomina=a.cod_tipo_nomina and b.cod_cargo=a.cod_cargo and b.cod_ficha=a.cod_ficha) as segundo_apellido,
			  (select b.primer_nombre from v_cnmd06_fichas_datos_personales b where b.cod_presi=a.cod_presi and b.cod_entidad=a.cod_entidad and b.cod_tipo_inst=a.cod_tipo_inst and b.cod_inst=a.cod_inst and b.cod_dep=a.cod_dep and b.cod_tipo_nomina=a.cod_tipo_nomina and b.cod_cargo=a.cod_cargo and b.cod_ficha=a.cod_ficha) as primer_nombre,
			  (select b.segundo_nombre from v_cnmd06_fichas_datos_personales b where b.cod_presi=a.cod_presi and b.cod_entidad=a.cod_entidad and b.cod_tipo_inst=a.cod_tipo_inst and b.cod_inst=a.cod_inst and b.cod_dep=a.cod_dep and b.cod_tipo_nomina=a.cod_tipo_nomina and b.cod_cargo=a.cod_cargo and b.cod_ficha=a.cod_ficha) as segundo_nombre
			  from cnmd10_individual_porcentaje_cantidad a where ".$this->SQLCA()." and a.cod_tipo_nomina=".$cod_nomina." and cod_transaccion=".$cod_trans." order by cod_cargo,cod_ficha";
			  $datos=$this->cnmd10_individual_porcentaje_cantidad->execute($grilla);
			  $this->set('datos',$datos);

}


function modificar_items($cod_nomina=null, $cod_trans=null,$cargo=null,$ficha=null,$i=null){
	$this->layout="ajax";

	$grilla="select
			  a.cod_presi,
			  a.cod_entidad,
			  a.cod_tipo_inst,
			  a.cod_inst,
			  a.cod_dep,
			  a.cod_tipo_nomina,
			  a.cod_cargo,
			  a.cod_ficha,
			  a.cod_transaccion,
			  a.cantidad,
			  (select b.cedula_identidad from v_cnmd06_fichas_datos_personales b where b.cod_presi=a.cod_presi and b.cod_entidad=a.cod_entidad and b.cod_tipo_inst=a.cod_tipo_inst and b.cod_inst=a.cod_inst and b.cod_dep=a.cod_dep and b.cod_tipo_nomina=a.cod_tipo_nomina and b.cod_cargo=a.cod_cargo and b.cod_ficha=a.cod_ficha) as cedula,
			  (select b.primer_apellido from v_cnmd06_fichas_datos_personales b where b.cod_presi=a.cod_presi and b.cod_entidad=a.cod_entidad and b.cod_tipo_inst=a.cod_tipo_inst and b.cod_inst=a.cod_inst and b.cod_dep=a.cod_dep and b.cod_tipo_nomina=a.cod_tipo_nomina and b.cod_cargo=a.cod_cargo and b.cod_ficha=a.cod_ficha) as primer_apellido,
			  (select b.segundo_apellido from v_cnmd06_fichas_datos_personales b where b.cod_presi=a.cod_presi and b.cod_entidad=a.cod_entidad and b.cod_tipo_inst=a.cod_tipo_inst and b.cod_inst=a.cod_inst and b.cod_dep=a.cod_dep and b.cod_tipo_nomina=a.cod_tipo_nomina and b.cod_cargo=a.cod_cargo and b.cod_ficha=a.cod_ficha) as segundo_apellido,
			  (select b.primer_nombre from v_cnmd06_fichas_datos_personales b where b.cod_presi=a.cod_presi and b.cod_entidad=a.cod_entidad and b.cod_tipo_inst=a.cod_tipo_inst and b.cod_inst=a.cod_inst and b.cod_dep=a.cod_dep and b.cod_tipo_nomina=a.cod_tipo_nomina and b.cod_cargo=a.cod_cargo and b.cod_ficha=a.cod_ficha) as primer_nombre,
			  (select b.segundo_nombre from v_cnmd06_fichas_datos_personales b where b.cod_presi=a.cod_presi and b.cod_entidad=a.cod_entidad and b.cod_tipo_inst=a.cod_tipo_inst and b.cod_inst=a.cod_inst and b.cod_dep=a.cod_dep and b.cod_tipo_nomina=a.cod_tipo_nomina and b.cod_cargo=a.cod_cargo and b.cod_ficha=a.cod_ficha) as segundo_nombre
			  from cnmd10_individual_porcentaje_cantidad a where ".$this->SQLCA()." and a.cod_tipo_nomina=".$cod_nomina." and cod_transaccion=".$cod_trans." and cod_cargo=".$cargo." and cod_ficha=".$ficha." order by cod_cargo,cod_ficha";
    $datos=$this->cnmd10_individual_porcentaje_cantidad->execute($grilla);
    $this->set('datos',$datos);
	$this->set('k',$i);
}



function cancelar($cod_nomina=null, $cod_trans=null){
	$this->layout="ajax";

	$grilla="select
			  a.cod_presi,
			  a.cod_entidad,
			  a.cod_tipo_inst,
			  a.cod_inst,
			  a.cod_dep,
			  a.cod_tipo_nomina,
			  a.cod_cargo,
			  a.cod_ficha,
			  a.cod_transaccion,
			  a.cantidad,
			  (select b.cedula_identidad from v_cnmd06_fichas_datos_personales b where b.cod_presi=a.cod_presi and b.cod_entidad=a.cod_entidad and b.cod_tipo_inst=a.cod_tipo_inst and b.cod_inst=a.cod_inst and b.cod_dep=a.cod_dep and b.cod_tipo_nomina=a.cod_tipo_nomina and b.cod_cargo=a.cod_cargo and b.cod_ficha=a.cod_ficha) as cedula,
			  (select b.primer_apellido from v_cnmd06_fichas_datos_personales b where b.cod_presi=a.cod_presi and b.cod_entidad=a.cod_entidad and b.cod_tipo_inst=a.cod_tipo_inst and b.cod_inst=a.cod_inst and b.cod_dep=a.cod_dep and b.cod_tipo_nomina=a.cod_tipo_nomina and b.cod_cargo=a.cod_cargo and b.cod_ficha=a.cod_ficha) as primer_apellido,
			  (select b.segundo_apellido from v_cnmd06_fichas_datos_personales b where b.cod_presi=a.cod_presi and b.cod_entidad=a.cod_entidad and b.cod_tipo_inst=a.cod_tipo_inst and b.cod_inst=a.cod_inst and b.cod_dep=a.cod_dep and b.cod_tipo_nomina=a.cod_tipo_nomina and b.cod_cargo=a.cod_cargo and b.cod_ficha=a.cod_ficha) as segundo_apellido,
			  (select b.primer_nombre from v_cnmd06_fichas_datos_personales b where b.cod_presi=a.cod_presi and b.cod_entidad=a.cod_entidad and b.cod_tipo_inst=a.cod_tipo_inst and b.cod_inst=a.cod_inst and b.cod_dep=a.cod_dep and b.cod_tipo_nomina=a.cod_tipo_nomina and b.cod_cargo=a.cod_cargo and b.cod_ficha=a.cod_ficha) as primer_nombre,
			  (select b.segundo_nombre from v_cnmd06_fichas_datos_personales b where b.cod_presi=a.cod_presi and b.cod_entidad=a.cod_entidad and b.cod_tipo_inst=a.cod_tipo_inst and b.cod_inst=a.cod_inst and b.cod_dep=a.cod_dep and b.cod_tipo_nomina=a.cod_tipo_nomina and b.cod_cargo=a.cod_cargo and b.cod_ficha=a.cod_ficha) as segundo_nombre
			  from cnmd10_individual_porcentaje_cantidad a where ".$this->SQLCA()." and a.cod_tipo_nomina=".$cod_nomina." and cod_transaccion=".$cod_trans." order by cod_cargo,cod_ficha";
    $datos=$this->cnmd10_individual_porcentaje_cantidad->execute($grilla);
    $this->set('datos',$datos);
}


function guardar_editar($cod_nomina=null, $cod_trans=null,$cargo=null,$ficha=null,$i=null){
	$this->layout="ajax";
	if(!empty($this->data['cnmp10']['cantidad'.$i])){
		$cantidad = $this->Formato1($this->data['cnmp10']['cantidad'.$i]);
		$ver=$this->cnmd10_individual_porcentaje_cantidad->execute("update cnmd10_individual_porcentaje_cantidad set cantidad=".$cantidad." where ".$this->SQLCA()." and cod_tipo_nomina=".$cod_nomina." and cod_transaccion=".$cod_trans." and cod_cargo=".$cargo." and cod_ficha=".$ficha);
	}else{
		$this->set('errorMessage','Debe ingresar la cantidad');
	}

	$grilla="select
			  a.cod_presi,
			  a.cod_entidad,
			  a.cod_tipo_inst,
			  a.cod_inst,
			  a.cod_dep,
			  a.cod_tipo_nomina,
			  a.cod_cargo,
			  a.cod_ficha,
			  a.cod_transaccion,
			  a.cantidad,
			  (select b.cedula_identidad from v_cnmd06_fichas_datos_personales b where b.cod_presi=a.cod_presi and b.cod_entidad=a.cod_entidad and b.cod_tipo_inst=a.cod_tipo_inst and b.cod_inst=a.cod_inst and b.cod_dep=a.cod_dep and b.cod_tipo_nomina=a.cod_tipo_nomina and b.cod_cargo=a.cod_cargo and b.cod_ficha=a.cod_ficha) as cedula,
			  (select b.primer_apellido from v_cnmd06_fichas_datos_personales b where b.cod_presi=a.cod_presi and b.cod_entidad=a.cod_entidad and b.cod_tipo_inst=a.cod_tipo_inst and b.cod_inst=a.cod_inst and b.cod_dep=a.cod_dep and b.cod_tipo_nomina=a.cod_tipo_nomina and b.cod_cargo=a.cod_cargo and b.cod_ficha=a.cod_ficha) as primer_apellido,
			  (select b.segundo_apellido from v_cnmd06_fichas_datos_personales b where b.cod_presi=a.cod_presi and b.cod_entidad=a.cod_entidad and b.cod_tipo_inst=a.cod_tipo_inst and b.cod_inst=a.cod_inst and b.cod_dep=a.cod_dep and b.cod_tipo_nomina=a.cod_tipo_nomina and b.cod_cargo=a.cod_cargo and b.cod_ficha=a.cod_ficha) as segundo_apellido,
			  (select b.primer_nombre from v_cnmd06_fichas_datos_personales b where b.cod_presi=a.cod_presi and b.cod_entidad=a.cod_entidad and b.cod_tipo_inst=a.cod_tipo_inst and b.cod_inst=a.cod_inst and b.cod_dep=a.cod_dep and b.cod_tipo_nomina=a.cod_tipo_nomina and b.cod_cargo=a.cod_cargo and b.cod_ficha=a.cod_ficha) as primer_nombre,
			  (select b.segundo_nombre from v_cnmd06_fichas_datos_personales b where b.cod_presi=a.cod_presi and b.cod_entidad=a.cod_entidad and b.cod_tipo_inst=a.cod_tipo_inst and b.cod_inst=a.cod_inst and b.cod_dep=a.cod_dep and b.cod_tipo_nomina=a.cod_tipo_nomina and b.cod_cargo=a.cod_cargo and b.cod_ficha=a.cod_ficha) as segundo_nombre
			  from cnmd10_individual_porcentaje_cantidad a where ".$this->SQLCA()." and a.cod_tipo_nomina=".$cod_nomina." and cod_transaccion=".$cod_trans." ORDER BY (select b.cedula_identidad from v_cnmd06_fichas_datos_personales b where b.cod_presi=a.cod_presi and b.cod_entidad=a.cod_entidad and b.cod_tipo_inst=a.cod_tipo_inst and b.cod_inst=a.cod_inst and b.cod_dep=a.cod_dep and b.cod_tipo_nomina=a.cod_tipo_nomina and b.cod_cargo=a.cod_cargo and b.cod_ficha=a.cod_ficha) ASC";
    $datos=$this->cnmd10_individual_porcentaje_cantidad->execute($grilla);
    $this->set('datos',$datos);
}


function modificar($var=null,$nomina=null,$transaccion=null){//////AQUI SI VAR ES 1 SOLO ENVIO UN MENSAJE PARA QUE MODIFIQUE Y HABILITE EL BOTON GUARDAR SINO VAR ES 2 Y PROCEDE A MODIFICAR(SOLO MODIFICA LOS RADIOS)
	$this->layout="ajax";
	if($var==2 && (isset($nomina) && isset($transaccion))){
		if(empty($this->data['cnmp10']['porcentaje']) && $this->data['cnmp10']['porcentaje']==0){
	    	$this->set('errorMessage','Debe ingresar el porcentaje');
	    }else if($this->data['cnmp10']['condicion']==2 && empty($this->data['cnmp10']['tipo_trans'])){
	    	$this->set('errorMessage','Debe seleccionar el tipo de transacción');
	    }else if(!empty($this->data['cnmp10']['tipo_trans']) && empty($this->data['cnmp10']['select4'])){
	    	$this->set('errorMessage','Debe seleccionar el código de transacción');
	    }else {
	    	$porcentaje           = $this->Formato1($this->data['cnmp10']['porcentaje']);
	    	$cod_frecuencia       = $this->Session->read('frecuencia');
			$cod_condicion        = $this->data['cnmp10']['condicion'];
	    	if(!isset($this->data['cnmp10']['escenario'])){
				$activar_frecuencia_eventual = 2;
			}else{
				$activar_frecuencia_eventual = $this->data['cnmp10']['escenario'];
			}

			if(!isset($this->data['cnmp10']['tipo_trans'])){
				$codi_tipo_transaccion = 0;
				$codi_transaccion      = 0;
			}else{
				$codi_tipo_transaccion = $this->data['cnmp10']['tipo_trans'];
				$codi_transaccion      = $this->data['cnmp10']['select4'];
			}

			$ver=$this->cnmd10_individual_porcentaje->execute("update cnmd10_individual_porcentaje set cod_condicion=".$cod_condicion.",codi_tipo_transaccion=".$codi_tipo_transaccion.",codi_transaccion=".$codi_transaccion.",activar_frecuencia_eventual=".$activar_frecuencia_eventual.",porcentaje=".$porcentaje." where ".$this->SQLCA()." and cod_tipo_nomina=".$nomina." and cod_transaccion=".$transaccion);
		   	if($ver>1){
		   		$this->set('Message_existe', 'El registro se ha modificado');
		   		$this->set('modificado','si');
		   	}else{
		   		$this->set('errorMessage','El registro no pudo ser modificado');
		   	}




		}

	}else if($var==1){
		$this->set('Message_existe', 'Proceda a modificar los datos');
	}
	$this->set('opcion',$var);

}//fin modificar



/*Esta funcion es un script especial que se hizo para copiar una informacion de una transaccion a otra
la forma de usar es haciendo un link a este controlador que vaya a la funcion proceso!!Hay que cmbiar los valores
dentro de la funcion dependiendo de la nomina y la transaccion que se quiera copiar!

*/
function proceso(){

	$sql=$this->cnmd10_individual_porcentaje_cantidad->execute("select * from cnmd10_individual_porcentaje_cantidad a where ".$this->SQLCA()." and cod_tipo_nomina=301 and cod_transaccion=100");
	for($i=0;$i<count($sql);$i++){
		$cod_presi=$sql[$i][0]['cod_presi'];
		$cod_entidad=$sql[$i][0]['cod_entidad'];
		$cod_tipo_inst=$sql[$i][0]['cod_tipo_inst'];
		$cod_inst=$sql[$i][0]['cod_inst'];
		$cod_dep=$sql[$i][0]['cod_dep'];
		$nomina=$sql[$i][0]['cod_tipo_nomina'];
		$cod_cargo=$sql[$i][0]['cod_cargo'];
		$cod_ficha=$sql[$i][0]['cod_ficha'];
		$tipo_transaccion=1;
		$transaccion=601;//transaccion en la que se pega la informacion
		$cantidad=$sql[$i][0]['cantidad'];


		$a=$this->cnmd10_individual_porcentaje_cantidad->execute("select * from cnmd10_individual_porcentaje_cantidad a where ".$this->SQLCA()." and cod_tipo_nomina='$nomina' and cod_transaccion='$transaccion' and cod_cargo='$cod_cargo' and cod_ficha='$cod_ficha' and cod_tipo_transaccion='$tipo_transaccion' and cod_transaccion='$transaccion'");
		if($a==null){
			$insert2 = "INSERT INTO cnmd10_individual_porcentaje_cantidad VALUES('$cod_presi', '$cod_entidad', '$cod_tipo_inst', '$cod_inst', '$cod_dep', '$nomina','$cod_cargo','$cod_ficha', '$tipo_transaccion', '$transaccion','$cantidad')";
			$sw2 = $this->cnmd10_individual_porcentaje_cantidad->execute($insert2);
		}

	}

	////////////////////////////////////////////////////////////////////////////


	$sql=$this->cnmd10_individual_porcentaje_cantidad->execute("select * from cnmd10_individual_porcentaje_cantidad a where ".$this->SQLCA()." and cod_tipo_nomina=301 and cod_transaccion=107");
	for($i=0;$i<count($sql);$i++){
		$cod_presi=$sql[$i][0]['cod_presi'];
		$cod_entidad=$sql[$i][0]['cod_entidad'];
		$cod_tipo_inst=$sql[$i][0]['cod_tipo_inst'];
		$cod_inst=$sql[$i][0]['cod_inst'];
		$cod_dep=$sql[$i][0]['cod_dep'];
		$nomina=$sql[$i][0]['cod_tipo_nomina'];
		$cod_cargo=$sql[$i][0]['cod_cargo'];
		$cod_ficha=$sql[$i][0]['cod_ficha'];
		$tipo_transaccion=1;
		$transaccion=610;
		$cantidad=$sql[$i][0]['cantidad'];


		$a=$this->cnmd10_individual_porcentaje_cantidad->execute("select * from cnmd10_individual_porcentaje_cantidad a where ".$this->SQLCA()." and cod_tipo_nomina='$nomina' and cod_transaccion='$transaccion' and cod_cargo='$cod_cargo' and cod_ficha='$cod_ficha' and cod_tipo_transaccion='$tipo_transaccion' and cod_transaccion='$transaccion'");
		if($a==null){
			$insert2 = "INSERT INTO cnmd10_individual_porcentaje_cantidad VALUES('$cod_presi', '$cod_entidad', '$cod_tipo_inst', '$cod_inst', '$cod_dep', '$nomina','$cod_cargo','$cod_ficha', '$tipo_transaccion', '$transaccion','$cantidad')";
			$sw2 = $this->cnmd10_individual_porcentaje_cantidad->execute($insert2);
		}

	}




	////////////////////////////////////////////////////////////////////////////


	$sql=$this->cnmd10_individual_porcentaje_cantidad->execute("select * from cnmd10_individual_porcentaje_cantidad a where ".$this->SQLCA()." and cod_tipo_nomina=302 and cod_transaccion=100");
	for($i=0;$i<count($sql);$i++){
		$cod_presi=$sql[$i][0]['cod_presi'];
		$cod_entidad=$sql[$i][0]['cod_entidad'];
		$cod_tipo_inst=$sql[$i][0]['cod_tipo_inst'];
		$cod_inst=$sql[$i][0]['cod_inst'];
		$cod_dep=$sql[$i][0]['cod_dep'];
		$nomina=$sql[$i][0]['cod_tipo_nomina'];
		$cod_cargo=$sql[$i][0]['cod_cargo'];
		$cod_ficha=$sql[$i][0]['cod_ficha'];
		$tipo_transaccion=1;
		$transaccion=601;
		$cantidad=$sql[$i][0]['cantidad'];


		$a=$this->cnmd10_individual_porcentaje_cantidad->execute("select * from cnmd10_individual_porcentaje_cantidad a where ".$this->SQLCA()." and cod_tipo_nomina='$nomina' and cod_transaccion='$transaccion' and cod_cargo='$cod_cargo' and cod_ficha='$cod_ficha' and cod_tipo_transaccion='$tipo_transaccion' and cod_transaccion='$transaccion'");
		if($a==null){
			$insert2 = "INSERT INTO cnmd10_individual_porcentaje_cantidad VALUES('$cod_presi', '$cod_entidad', '$cod_tipo_inst', '$cod_inst', '$cod_dep', '$nomina','$cod_cargo','$cod_ficha', '$tipo_transaccion', '$transaccion','$cantidad')";
			$sw2 = $this->cnmd10_individual_porcentaje_cantidad->execute($insert2);
		}

	}



	////////////////////////////////////////////////////////////////////////////


	$sql=$this->cnmd10_individual_porcentaje_cantidad->execute("select * from cnmd10_individual_porcentaje_cantidad a where ".$this->SQLCA()." and cod_tipo_nomina=302 and cod_transaccion=107");
	for($i=0;$i<count($sql);$i++){
		$cod_presi=$sql[$i][0]['cod_presi'];
		$cod_entidad=$sql[$i][0]['cod_entidad'];
		$cod_tipo_inst=$sql[$i][0]['cod_tipo_inst'];
		$cod_inst=$sql[$i][0]['cod_inst'];
		$cod_dep=$sql[$i][0]['cod_dep'];
		$nomina=$sql[$i][0]['cod_tipo_nomina'];
		$cod_cargo=$sql[$i][0]['cod_cargo'];
		$cod_ficha=$sql[$i][0]['cod_ficha'];
		$tipo_transaccion=1;
		$transaccion=610;
		$cantidad=$sql[$i][0]['cantidad'];


		$a=$this->cnmd10_individual_porcentaje_cantidad->execute("select * from cnmd10_individual_porcentaje_cantidad a where ".$this->SQLCA()." and cod_tipo_nomina='$nomina' and cod_transaccion='$transaccion' and cod_cargo='$cod_cargo' and cod_ficha='$cod_ficha' and cod_tipo_transaccion='$tipo_transaccion' and cod_transaccion='$transaccion'");
		if($a==null){
			$insert2 = "INSERT INTO cnmd10_individual_porcentaje_cantidad VALUES('$cod_presi', '$cod_entidad', '$cod_tipo_inst', '$cod_inst', '$cod_dep', '$nomina','$cod_cargo','$cod_ficha', '$tipo_transaccion', '$transaccion','$cantidad')";
			$sw2 = $this->cnmd10_individual_porcentaje_cantidad->execute($insert2);
		}

	}



}

}//FIN CONTROLADOR
?>