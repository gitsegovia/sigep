<?php
class Cnmp09TrabajadoresCobranCancelanTransaccionController extends AppController {
   var $name = 'cnmp09_trabajadores_cobran_cancelan_transaccion';
   var $uses = array('Cnmd01', 'cnmd06_fichas','cnmd06_datos_personales','cnmd09_traba_queno_cobran_cancela_transa','v_cnmd06_fichas_datos_personales','cnmd03_transacciones');
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




 function beforeFilter(){
 	$this->checkSession();
 	 echo'<script>
							 document.getElementById("valida_codigo").innerHTML = "";
							 document.getElementById("valida_codigo").style.display = "none";
							 if(document.getElementById("registro")){document.getElementById("registro").style.visibility = "hidden";}
                             if(document.getElementById("nomina")){document.getElementById("nomina").style.visibility = "hidden";}
                             if(document.getElementById("dependencia")){document.getElementById("dependencia").style.visibility = "hidden";}
                             if(document.getElementById("sistema_general")){document.getElementById("sistema_general").style.visibility = "hidden";}
                          </script>';
 }


 function index(){
 	$this->layout ="ajax";
 	$this->set('entidad_federal', $this->Session->read('entidad_federal'));
	$this->concatenaN($this->Cnmd01->generateList($this->SQLCA(),'cod_tipo_nomina ASC', null, '{n}.Cnmd01.cod_tipo_nomina', '{n}.Cnmd01.denominacion'),'nomina');

   $cnmd03_transacciones = $this->cnmd03_transacciones->generateList("cod_tipo_transaccion='1'  ", 'cod_transaccion ASC', null, '{n}.cnmd03_transacciones.cod_transaccion', '{n}.cnmd03_transacciones.denominacion');
   $this->concatenaN($cnmd03_transacciones, 'nomina2');

 }// fin index

function cod_nomina($cod_nomina=null){
	$this->layout="ajax";
	if($cod_nomina!=null){
		$this->set('cod_nomina', $cod_nomina);
	}
	$this->Session->delete('cod_nomina_1');
	$this->Session->write('cod_nomina_1',$cod_nomina);
	echo "<script>";
		echo "document.getElementById('buscar').disabled=false;";
		echo "if(cod_ficha)document.getElementById('cod_ficha').value='';";
		echo "if(deno_ficha)document.getElementById('deno_ficha').value='';";
		echo "if(cod_cargo)document.getElementById('cod_cargo').value='';";
		echo "if(cod_ficha_1)document.getElementById('cod_ficha_1').value='';";
		echo "if(cedula)document.getElementById('cedula').value='';";
		echo "if(apellido_1)document.getElementById('apellido_1').value='';";
		echo "if(apellido_2)document.getElementById('apellido_2').value='';";
		echo "if(nombre_1)document.getElementById('nombre_1').value='';";
		echo "if(nombre_2)document.getElementById('nombre_2').value='';";
//		echo "document.getElementById('dias').value='';";
//		echo "document.getElementById('transaccion').value='';";
//		echo "document.getElementById('denominacion').value='';";
//		echo "document.getElementById('porcentaje').value='';";
//		echo"document.getElementById('agregar').disabled ='disabled'; ";
	echo "</script>";
}//fin cod_nomina

function deno_nomina($cod_nomina=null){
	$this->layout="ajax";
	if($cod_nomina!=null){
		$deno_nomina = $this->Cnmd01->field('denominacion', $conditions = $this->SQLCA()." and Cnmd01.cod_tipo_nomina='$cod_nomina'", $order ="cod_tipo_nomina ASC");
		//echo "el tipo de nomina es: ".$deno_nomina;
		$this->set('deno_nomina', $deno_nomina);
	}
}// fin deno_nomina


function cod_ficha($cod_ficha=null){
	$this->layout="ajax";
	if($cod_ficha!=null){
		$this->set('cod_ficha', $cod_ficha);
	}
}//fin cod_nomina

function deno_ficha($cod_nomina=null,$cod_ficha=null){
	$this->layout="ajax";
	if($cod_nomina!=null){
		$deno_ficha = $this->v_cnmd06_fichas_datos_personales->field('denominacion_clase', $conditions = $this->SQLCA()." and cod_tipo_nomina='$cod_nomina' and cod_ficha='$cod_ficha'", $order ="cod_tipo_nomina ASC");
		//echo "el tipo de nomina es: ".$deno_nomina;
		$this->set('deno_ficha', $deno_ficha);
	}
}// fin deno_nomina

function select_ficha($cod_nomina=null){
	$this->layout="ajax";
//	echo $opc." y  ".
	if($cod_nomina!=null){
		$cnmd03 = $this->v_cnmd06_fichas_datos_personales->generateList($this->SQLCA()." and cod_tipo_nomina='$cod_nomina' and condicion_actividad=1", $order = 'cod_ficha', $limit = null, '{n}.v_cnmd06_fichas_datos_personales.cod_ficha', '{n}.v_cnmd06_fichas_datos_personales.denominacion_busqueda');
		if($cnmd03){
		$this->concatena($cnmd03, 'ficha');
		$this->set('cod_nomina', $cod_nomina);
		}else{
			$this->set('vacio', "");
		}
	}

}//fin select_trans

function cod_nomina2($var=null , $var2=null){
    $this->layout = "ajax";
    $cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep;



   $resultado= $this->Cnmd01->execute("SELECT
			  a.cod_tipo_transaccion,
              a.cod_transaccion,
              a.denominacion

			  from  cnmd03_transacciones a where

			    a.cod_tipo_transaccion     =    ".$var." and
			    a.cod_transaccion          =    ".$var2.";
			    ");


echo "<script>";
        echo "document.getElementById('cod_transaccion').value='".$this->AddCeroR2($resultado[0][0]['cod_transaccion'])."';";
		echo "document.getElementById('deno_transaccion').value='".$resultado[0][0]['denominacion']."';";
echo "</script>";


}//fin function


function cod_nomina3($var=null){
    $this->layout = "ajax";
    $cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep;


$cnmd03_transacciones = $this->cnmd03_transacciones->generateList("cod_tipo_transaccion='".$var."'  ", 'cod_transaccion ASC', null, '{n}.cnmd03_transacciones.cod_transaccion', '{n}.cnmd03_transacciones.denominacion');
$this->concatenaN($cnmd03_transacciones, 'nomina2');
$this->set('tipo_nomina', $var);

echo "<script>";
        echo "document.getElementById('cod_transaccion').value='';";
		echo "document.getElementById('deno_transaccion').value='';";
echo "</script>";

}//fin function



function carga_datos($nomina=null,$ficha=null){
	 $this->layout = "ajax";
	 $cod_presi = $this->Session->read('SScodpresi');
     $cod_entidad = $this->Session->read('SScodentidad');
     $cod_tipo_inst = $this->Session->read('SScodtipoinst');
     $cod_inst = $this->Session->read('SScodinst');
     $cod_dep = $this->Session->read('SScoddep');

	 $datos=$this->v_cnmd06_fichas_datos_personales->execute("SELECT a.cod_tipo_nomina,a.cod_cargo,a.cod_ficha,a.cedula_identidad,a.primer_apellido,a.segundo_apellido,a.primer_nombre,a.segundo_nombre FROM v_cnmd06_fichas_datos_personales a where ".$this->SQLCA()." and cod_tipo_nomina=".$nomina." and cod_ficha=".$ficha);
	 $this->set('datos',$datos);

	 $cnmd03_transacciones = $this->cnmd03_transacciones->generateList("cod_tipo_transaccion='1'  ", 'cod_transaccion ASC', null, '{n}.cnmd03_transacciones.cod_transaccion', '{n}.cnmd03_transacciones.denominacion');
     $this->concatenaN($cnmd03_transacciones, 'nomina2');

	$sql="  select a.cod_presi,
			a.cod_entidad,
			a.cod_tipo_inst,
			a.cod_inst,
			a.cod_dep,
			a.cod_tipo_nomina,
			a.cod_cargo,
			a.cod_ficha,
			a.cod_tipo_transaccion,
			a.cod_transaccion,
			(select b.denominacion from cnmd03_transacciones b where b.cod_tipo_transaccion=a.cod_tipo_transaccion and b.cod_transaccion=a.cod_transaccion) as denominacion
			from cnmd09_traba_queno_cobran_cancela_transa a where
			a.cod_presi='$cod_presi' and
			a.cod_entidad='$cod_entidad' and
			a.cod_tipo_inst='$cod_tipo_inst' and
			a.cod_inst='$cod_inst' and
			a.cod_dep='$cod_dep' and a.cod_tipo_nomina='$nomina' and cod_ficha='$ficha'";
		$result=$this->cnmd09_traba_queno_cobran_cancela_transa->execute($sql);
		$this->set('datos_1',$result);
}//fin carga_datos


function buscar_ficha($var1=null){
	$this->layout="ajax";
	$this->set("opcion",$var1);
	$this->Session->delete('pista');
}//fin buscar_ficha



function buscar_por_pista($var1=null, $var2=null, $var3=null){
$this->layout="ajax";
$nomina=$this->Session->read('cod_nomina_1');
    if($var3==null){
    	$var2 = strtoupper($var2);
					$this->Session->write('pista', $var2);
//					if(is_int($var2)){$sql   = " (cod_tipo_nomina='$nomina' and denominacion_busqueda LIKE '%$var2%')  or   ";}else{ $sql = "";}
					$Tfilas=$this->v_cnmd06_fichas_datos_personales->findCount($this->SQLCA()." and cod_tipo_nomina='$nomina' and condicion_actividad=1 and upper(super_busqueda) LIKE upper('%".$var2."%')");
//					        echo "cod_tipo_nomina='$nomina' and denominacion_busqueda LIKE '%$var2%'";
					        if($Tfilas!=0){
					        	$pagina=1;
					        	$Tfilas=(int)ceil($Tfilas/100);
					        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
								$this->set('total_paginas',$Tfilas);
								$this->set('pagina_actual',$pagina);
								$this->set('ultimo',$Tfilas);
					     	    $datos_filas=$this->v_cnmd06_fichas_datos_personales->findAll($this->SQLCA()." and cod_tipo_nomina='$nomina' and condicion_actividad=1 and upper(super_busqueda) LIKE upper('%".$var2."%')",null,"cod_tipo_nomina,cod_cargo,cod_ficha ASC",100,1,null);
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
						$Tfilas=$this->v_cnmd06_fichas_datos_personales->findCount($this->SQLCA()." and cod_tipo_nomina='$nomina' and condicion_actividad=1 and upper(super_busqueda) LIKE upper('%".$var22."%')");
						        if($Tfilas!=0){
						        	$pagina=$var3;
						        	$Tfilas=(int)ceil($Tfilas/100);
						        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
									$this->set('total_paginas',$Tfilas);
									$this->set('pagina_actual',$pagina);
									$this->set('ultimo',$Tfilas);
//						     	    $datos_filas=$this->cscd01_catalogo->findAll($sql." (denominacion LIKE '%$var22%')  OR  (cod_snc LIKE '%$var22%')   ",null,"codigo_prod_serv ASC",100,$pagina,null);
									$datos_filas=$this->v_cnmd06_fichas_datos_personales->findAll($this->SQLCA()." and cod_tipo_nomina='$nomina' and condicion_actividad=1 and upper(super_busqueda) LIKE upper('%".$var22."%')'",null,"cod_tipo_nomina,cod_cargo,cod_ficha ASC",100,$pagina,null);
							        $this->set("datosFILAS",$datos_filas);
							        $this->set('siguiente',$pagina+1);
									$this->set('anterior',$pagina-1);
									$this->bt_nav($Tfilas,$pagina);
						          }else{
						        	$this->set("datosFILAS",'');
						          }
                 }//fin else


//$this->set("cscd01_unidad_medida",$this->cscd01_unidad_medida->findAll());
$this->set("opcion",$var1);
}//fin function


function seleccion_busqueda($opcion=null,$nomina=null,$cargo=null,$ficha=null){
	$this->layout="ajax";
	$cod_presi = $this->Session->read('SScodpresi');
    $cod_entidad = $this->Session->read('SScodentidad');
    $cod_tipo_inst = $this->Session->read('SScodtipoinst');
    $cod_inst = $this->Session->read('SScodinst');
    $cod_dep = $this->Session->read('SScoddep');

	$this->concatenaN($this->Cnmd01->generateList($this->SQLCA(),'cod_tipo_nomina ASC', null, '{n}.Cnmd01.cod_tipo_nomina', '{n}.Cnmd01.denominacion'),'nomina');

	$datos=$this->v_cnmd06_fichas_datos_personales->execute("SELECT a.cod_tipo_nomina,a.tipo_nomina,a.cod_cargo,a.denominacion_clase,a.cod_ficha,a.cedula_identidad,a.primer_apellido,a.segundo_apellido,a.primer_nombre,a.segundo_nombre FROM v_cnmd06_fichas_datos_personales a where ".$this->SQLCA()." and cod_tipo_nomina=".$nomina." and cod_cargo=".$cargo." and cod_ficha=".$ficha);
	$this->set('datos',$datos);

	$cnmd03 = $this->v_cnmd06_fichas_datos_personales->generateList($this->SQLCA()." and cod_tipo_nomina='$nomina'", $order ='cod_ficha', $limit = null, '{n}.v_cnmd06_fichas_datos_personales.cod_ficha', '{n}.v_cnmd06_fichas_datos_personales.denominacion_busqueda');
		if($cnmd03){
		$this->concatena($cnmd03, 'ficha');
		$this->set('cod_nomina', $nomina);
		}else{
			$this->set('vacio', "");
		}

	$cnmd03_transacciones = $this->cnmd03_transacciones->generateList("cod_tipo_transaccion='1'  ", 'cod_transaccion ASC', null, '{n}.cnmd03_transacciones.cod_transaccion', '{n}.cnmd03_transacciones.denominacion');
    $this->concatenaN($cnmd03_transacciones, 'nomina2');

    $sql="  select a.cod_presi,
			a.cod_entidad,
			a.cod_tipo_inst,
			a.cod_inst,
			a.cod_dep,
			a.cod_tipo_nomina,
			a.cod_cargo,
			a.cod_ficha,
			a.cod_tipo_transaccion,
			a.cod_transaccion,
			(select b.denominacion from cnmd03_transacciones b where b.cod_tipo_transaccion=a.cod_tipo_transaccion and b.cod_transaccion=a.cod_transaccion) as denominacion
			from cnmd09_traba_queno_cobran_cancela_transa a where
			a.cod_presi='$cod_presi' and
			a.cod_entidad='$cod_entidad' and
			a.cod_tipo_inst='$cod_tipo_inst' and
			a.cod_inst='$cod_inst' and
			a.cod_dep='$cod_dep' and a.cod_tipo_nomina='$nomina' and cod_ficha='$ficha'";
		$result=$this->cnmd09_traba_queno_cobran_cancela_transa->execute($sql);
		if($result!=null){
			$this->set('datos_1',$result);
		}else{
			$this->set('datos_1',null);
		}


}// fin seleccion_busqueda

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



 function guardar($nomina=null,$cargo=null,$ficha=null) {
 	$this->layout = "ajax";
//	pr($this->data);
 	$cod_presi = $this->Session->read('SScodpresi');
    $cod_entidad = $this->Session->read('SScodentidad');
    $cod_tipo_inst = $this->Session->read('SScodtipoinst');
    $cod_inst = $this->Session->read('SScodinst');
    $cod_dep = $this->Session->read('SScoddep');
	if(!empty($this->data['cnmp09_deduccion']['cod_transaccion2'])){
		$tipo_transaccion=$this->data['cnmp09_deduccion']['tipo_transaccion'];
		$transaccion=$this->data['cnmp09_deduccion']['cod_transaccion2'];
		if($this->cnmd09_traba_queno_cobran_cancela_transa->FindCount($this->SQLCA()." and cod_tipo_nomina='$nomina' and cod_cargo='$cargo' and cod_ficha='$ficha' and cod_tipo_transaccion='$tipo_transaccion' and cod_transaccion='$transaccion'")==0){
			$sql = "INSERT INTO cnmd09_traba_queno_cobran_cancela_transa VALUES ('$cod_presi', '$cod_entidad', '$cod_tipo_inst', '$cod_inst', '$cod_dep', '$nomina','$cargo','$ficha','$tipo_transaccion','$transaccion')";
			if($this->cnmd09_traba_queno_cobran_cancela_transa->execute($sql)>1){
				$this->set('Message_existe', 'REGISTRO EXITOSO');
				echo "<script>";
	//				echo "document.getElementById('agregar').disabled='disabled';";
				echo "</script>";
			}else{
				$this->set('errorMessage', 'NO PUDIERON SER INSERTADOS LOS DATOS');
			}
		}else{
			$this->set('errorMessage', 'ESTOS DATOS YA EXISTEN REGISTRADOS');
		}
	}else{
		$this->set('errorMessage', 'DEBE SELECCIONAR EL CÓDIGO DE TRANSACCIÓN ');
		echo "<script>";
			echo "document.getElementById('agregar').disabled=false;";
		echo "</script>";
	}
/*
	$data=$this->cnmd09_dias_trabajados_ingreso_egreso->findAll($this->SQLCA()." and cod_tipo_nomina=".$nomina,null,"cod_profesion, cod_especialidad ASC",null,null,null);
	if($data!=null){
		$this->set('datos',$data);
	}else{
		$this->set('datos','');
	}*/

	$sql="  select a.cod_presi,
			a.cod_entidad,
			a.cod_tipo_inst,
			a.cod_inst,
			a.cod_dep,
			a.cod_tipo_nomina,
			a.cod_cargo,
			a.cod_ficha,
			a.cod_tipo_transaccion,
			a.cod_transaccion,
			(select b.denominacion from cnmd03_transacciones b where b.cod_tipo_transaccion=a.cod_tipo_transaccion and b.cod_transaccion=a.cod_transaccion) as denominacion
			from cnmd09_traba_queno_cobran_cancela_transa a where
			a.cod_presi='$cod_presi' and
			a.cod_entidad='$cod_entidad' and
			a.cod_tipo_inst='$cod_tipo_inst' and
			a.cod_inst='$cod_inst' and
			a.cod_dep='$cod_dep' and a.cod_tipo_nomina='$nomina' and cod_cargo='$cargo' and cod_ficha='$ficha'";
		$result=$this->cnmd09_traba_queno_cobran_cancela_transa->execute($sql);
		$this->set('datos',$result);


 }// fin guardar





function eliminar($nomina=null,$cargo=null,$ficha=null,$tipo_transaccion=null,$transaccion=null){
	  $this->layout = "ajax";
      $cod_presi                =       $this->Session->read('SScodpresi');
	  $cod_entidad              =       $this->Session->read('SScodentidad');
	  $cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
	  $cod_inst                 =       $this->Session->read('SScodinst');
	  $cod_dep                  =       $this->Session->read('SScoddep');

	  $sw = $this->cnmd09_traba_queno_cobran_cancela_transa->execute("DELETE FROM cnmd09_traba_queno_cobran_cancela_transa  WHERE ".$this->SQLCA()." and cod_tipo_nomina='$nomina' and cod_cargo='$cargo' and cod_ficha='$ficha' and cod_tipo_transaccion='$tipo_transaccion' and cod_transaccion='$transaccion'");
	  $this->set('Message_existe','registro eliminado con exito');

}//fin function



function cancelar($nomina=nulll){
	  $this->layout = "ajax";
	  $cod_presi                =       $this->Session->read('SScodpresi');
	  $cod_entidad              =       $this->Session->read('SScodentidad');
	  $cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
	  $cod_inst                 =       $this->Session->read('SScodinst');
	  $cod_dep                  =       $this->Session->read('SScoddep');

	  $sql="  select a.cod_presi,
			a.cod_entidad,
			a.cod_tipo_inst,
			a.cod_inst,
			a.cod_dep,
			a.cod_tipo_nomina,
			a.cod_cargo,
			a.cod_ficha,
			(select b.cedula_identidad from v_cnmd06_fichas_datos_personales b where a.cod_presi=b.cod_presi and a.cod_entidad=b.cod_entidad and a.cod_tipo_inst=b.cod_tipo_inst and a.cod_inst=b.cod_inst and a.cod_dep=b.cod_dep and a.cod_tipo_nomina=b.cod_tipo_nomina and a.cod_cargo=b.cod_cargo and a.cod_ficha=b.cod_ficha ) as cedula_identidad,
			(select c.primer_apellido from v_cnmd06_fichas_datos_personales c where a.cod_presi=c.cod_presi and a.cod_entidad=c.cod_entidad and a.cod_tipo_inst=c.cod_tipo_inst and a.cod_inst=c.cod_inst and a.cod_dep=c.cod_dep and a.cod_tipo_nomina=c.cod_tipo_nomina and a.cod_cargo=c.cod_cargo and a.cod_ficha=c.cod_ficha ) as primer_apellido,
			(select d.segundo_apellido from v_cnmd06_fichas_datos_personales d where a.cod_presi=d.cod_presi and a.cod_entidad=d.cod_entidad and a.cod_tipo_inst=d.cod_tipo_inst and a.cod_inst=d.cod_inst and a.cod_dep=d.cod_dep and a.cod_tipo_nomina=d.cod_tipo_nomina and a.cod_cargo=d.cod_cargo and a.cod_ficha=d.cod_ficha ) as segundo_apellido,
			(select e.primer_nombre from v_cnmd06_fichas_datos_personales e where a.cod_presi=e.cod_presi and a.cod_entidad=e.cod_entidad and a.cod_tipo_inst=e.cod_tipo_inst and a.cod_inst=e.cod_inst and a.cod_dep=e.cod_dep and a.cod_tipo_nomina=e.cod_tipo_nomina and a.cod_cargo=e.cod_cargo and a.cod_ficha=e.cod_ficha ) as primer_nombre,
			(select f.segundo_nombre from v_cnmd06_fichas_datos_personales f where a.cod_presi=f.cod_presi and a.cod_entidad=f.cod_entidad and a.cod_tipo_inst=f.cod_tipo_inst and a.cod_inst=f.cod_inst and a.cod_dep=f.cod_dep and a.cod_tipo_nomina=f.cod_tipo_nomina and a.cod_cargo=f.cod_cargo and a.cod_ficha=f.cod_ficha ) as segundo_nombre,
			a.dias
			from cnmd09_dias_trabajados_ingreso_egreso a where
			a.cod_presi='$cod_presi' and
			a.cod_entidad='$cod_entidad' and
			a.cod_tipo_inst='$cod_tipo_inst' and
			a.cod_inst='$cod_inst' and
			a.cod_dep='$cod_dep' and a.cod_tipo_nomina='$nomina'";

			$result=$this->v_cnmd06_fichas_datos_personales->execute($sql);
			$this->set('datos',$result);



}//fin cancelar



function modificar($nomina=null, $cargo=null,$ficha=null,$i=null){
	  $this->layout = "ajax";
      $cod_presi                =       $this->Session->read('SScodpresi');
	  $cod_entidad              =       $this->Session->read('SScodentidad');
	  $cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
	  $cod_inst                 =       $this->Session->read('SScodinst');
	  $cod_dep                  =       $this->Session->read('SScoddep');

	  $sql="  select a.cod_presi,
			a.cod_entidad,
			a.cod_tipo_inst,
			a.cod_inst,
			a.cod_dep,
			a.cod_tipo_nomina,
			a.cod_cargo,
			a.cod_ficha,
			(select b.cedula_identidad from v_cnmd06_fichas_datos_personales b where a.cod_presi=b.cod_presi and a.cod_entidad=b.cod_entidad and a.cod_tipo_inst=b.cod_tipo_inst and a.cod_inst=b.cod_inst and a.cod_dep=b.cod_dep and a.cod_tipo_nomina=b.cod_tipo_nomina and a.cod_cargo=b.cod_cargo and a.cod_ficha=b.cod_ficha ) as cedula_identidad,
			(select c.primer_apellido from v_cnmd06_fichas_datos_personales c where a.cod_presi=c.cod_presi and a.cod_entidad=c.cod_entidad and a.cod_tipo_inst=c.cod_tipo_inst and a.cod_inst=c.cod_inst and a.cod_dep=c.cod_dep and a.cod_tipo_nomina=c.cod_tipo_nomina and a.cod_cargo=c.cod_cargo and a.cod_ficha=c.cod_ficha ) as primer_apellido,
			(select d.segundo_apellido from v_cnmd06_fichas_datos_personales d where a.cod_presi=d.cod_presi and a.cod_entidad=d.cod_entidad and a.cod_tipo_inst=d.cod_tipo_inst and a.cod_inst=d.cod_inst and a.cod_dep=d.cod_dep and a.cod_tipo_nomina=d.cod_tipo_nomina and a.cod_cargo=d.cod_cargo and a.cod_ficha=d.cod_ficha ) as segundo_apellido,
			(select e.primer_nombre from v_cnmd06_fichas_datos_personales e where a.cod_presi=e.cod_presi and a.cod_entidad=e.cod_entidad and a.cod_tipo_inst=e.cod_tipo_inst and a.cod_inst=e.cod_inst and a.cod_dep=e.cod_dep and a.cod_tipo_nomina=e.cod_tipo_nomina and a.cod_cargo=e.cod_cargo and a.cod_ficha=e.cod_ficha ) as primer_nombre,
			(select f.segundo_nombre from v_cnmd06_fichas_datos_personales f where a.cod_presi=f.cod_presi and a.cod_entidad=f.cod_entidad and a.cod_tipo_inst=f.cod_tipo_inst and a.cod_inst=f.cod_inst and a.cod_dep=f.cod_dep and a.cod_tipo_nomina=f.cod_tipo_nomina and a.cod_cargo=f.cod_cargo and a.cod_ficha=f.cod_ficha ) as segundo_nombre,
			a.dias
			from cnmd09_dias_trabajados_ingreso_egreso a where
			a.cod_presi='$cod_presi' and
			a.cod_entidad='$cod_entidad' and
			a.cod_tipo_inst='$cod_tipo_inst' and
			a.cod_inst='$cod_inst' and
			a.cod_dep='$cod_dep' and a.cod_tipo_nomina='$nomina' and cod_cargo='$cargo' and cod_ficha='$ficha'";

			$result=$this->v_cnmd06_fichas_datos_personales->execute($sql);
			$this->set('datos',$result);
			$this->set('k',$i);
       	    $this->set('Message_existe','proceda a modificar los datos');

}//fin function




  function guardar_modificar($nomina=null, $cargo=null,$ficha=null,$i=null){
 	$this->layout="ajax";
 	$cod_presi                =       $this->Session->read('SScodpresi');
    $cod_entidad              =       $this->Session->read('SScodentidad');
    $cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
    $cod_inst                 =       $this->Session->read('SScodinst');
    $cod_dep                  =       $this->Session->read('SScoddep');
 	if(!empty($this->data['cnmp09_deduccion']['dias'.$i])){
		$dias=$this->data['cnmp09_deduccion']['dias'.$i];
  	    $sql="update cnmd09_dias_trabajados_ingreso_egreso set dias='$dias' where ".$this->SQLCA()." and cod_tipo_nomina='$nomina' and cod_cargo='$cargo' and cod_ficha='$ficha'";

		if($this->cnmd09_dias_trabajados_ingreso_egreso->execute($sql)>1){
		$this->set('Message_existe','Los datos fuer&oacute;n modificados');
 	}
 	}
 	 $sql="  select a.cod_presi,
			a.cod_entidad,
			a.cod_tipo_inst,
			a.cod_inst,
			a.cod_dep,
			a.cod_tipo_nomina,
			a.cod_cargo,
			a.cod_ficha,
			(select b.cedula_identidad from v_cnmd06_fichas_datos_personales b where a.cod_presi=b.cod_presi and a.cod_entidad=b.cod_entidad and a.cod_tipo_inst=b.cod_tipo_inst and a.cod_inst=b.cod_inst and a.cod_dep=b.cod_dep and a.cod_tipo_nomina=b.cod_tipo_nomina and a.cod_cargo=b.cod_cargo and a.cod_ficha=b.cod_ficha ) as cedula_identidad,
			(select c.primer_apellido from v_cnmd06_fichas_datos_personales c where a.cod_presi=c.cod_presi and a.cod_entidad=c.cod_entidad and a.cod_tipo_inst=c.cod_tipo_inst and a.cod_inst=c.cod_inst and a.cod_dep=c.cod_dep and a.cod_tipo_nomina=c.cod_tipo_nomina and a.cod_cargo=c.cod_cargo and a.cod_ficha=c.cod_ficha ) as primer_apellido,
			(select d.segundo_apellido from v_cnmd06_fichas_datos_personales d where a.cod_presi=d.cod_presi and a.cod_entidad=d.cod_entidad and a.cod_tipo_inst=d.cod_tipo_inst and a.cod_inst=d.cod_inst and a.cod_dep=d.cod_dep and a.cod_tipo_nomina=d.cod_tipo_nomina and a.cod_cargo=d.cod_cargo and a.cod_ficha=d.cod_ficha ) as segundo_apellido,
			(select e.primer_nombre from v_cnmd06_fichas_datos_personales e where a.cod_presi=e.cod_presi and a.cod_entidad=e.cod_entidad and a.cod_tipo_inst=e.cod_tipo_inst and a.cod_inst=e.cod_inst and a.cod_dep=e.cod_dep and a.cod_tipo_nomina=e.cod_tipo_nomina and a.cod_cargo=e.cod_cargo and a.cod_ficha=e.cod_ficha ) as primer_nombre,
			(select f.segundo_nombre from v_cnmd06_fichas_datos_personales f where a.cod_presi=f.cod_presi and a.cod_entidad=f.cod_entidad and a.cod_tipo_inst=f.cod_tipo_inst and a.cod_inst=f.cod_inst and a.cod_dep=f.cod_dep and a.cod_tipo_nomina=f.cod_tipo_nomina and a.cod_cargo=f.cod_cargo and a.cod_ficha=f.cod_ficha ) as segundo_nombre,
			a.dias
			from cnmd09_dias_trabajados_ingreso_egreso a where
			a.cod_presi='$cod_presi' and
			a.cod_entidad='$cod_entidad' and
			a.cod_tipo_inst='$cod_tipo_inst' and
			a.cod_inst='$cod_inst' and
			a.cod_dep='$cod_dep' and a.cod_tipo_nomina='$nomina'";

			$result=$this->v_cnmd06_fichas_datos_personales->execute($sql);
			$this->set('datos',$result);
 }//guardar modificar


 }//Fin de la clase controller
 ?>