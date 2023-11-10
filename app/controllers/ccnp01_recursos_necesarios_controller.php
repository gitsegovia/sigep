<?php

 class ccnp01RecursosNecesariosController extends AppController {
    var $name    = 'ccnp01_recursos_necesarios';
	var $uses    = array('ccnd01_tipo_directivo','ccnd02_proyectos','ccnd02_proyectos_actividades','ccnd02_proyectos_actividad_equipos','ccnd02_proyectos_actividad_manoobra','ccnd02_proyectos_actividad_materiales');
	var $helpers = array('Html','Ajax','Javascript', 'Sisap');



function checkSession(){
				if (!$this->Session->check('concejo_comunal')){
						$this->redirect('/salir');
						exit();
				}
}//fin checksession



 function beforeFilter(){
 	$this->checkSession();

 }

function concatena_actividad($vector1=null, $nomVar=null, $extra=null){
	$cod = array();
	if($vector1 != null){
		foreach($vector1 as $x => $y){
			if($x<10){
				$cod[$x] = '0'.$x;
			}else{
				$cod[$x] = $x;
			}
		}
		//print_r($cod);
	}

	$this->set($nomVar, $cod);
}//fin function


function index($id1=null, $id2=null){
	$this->layout="ajax";
    $cod_republica     = $this->Session->read('CC_republica');
	$cod_estado        = $this->Session->read('CC_estado');
	$cod_municipio     = $this->Session->read('CC_municipio');
	$cod_parroquia     = $this->Session->read('CC_parroquia');
	$cod_centro        = $this->Session->read('CC_centro');
	$cod_concejo       = $this->Session->read('CC_concejo');
	$ano               = $this->Session->read('concejos_comunal_id1');
	$cod_proyecto      = $this->Session->read('concejos_comunal_id2');

	$conditions  = "cod_republica=".$cod_republica." and cod_estado=".$cod_estado." and cod_municipio=".$cod_municipio." and cod_parroquia=".$cod_parroquia." and cod_centro=".$cod_centro." and cod_concejo=".$cod_concejo." and ano=".$ano." and cod_proyecto='".$cod_proyecto."'";

	$ver=$this->ccnd02_proyectos->execute("select * from ccnd02_proyectos where ".$conditions);
	if($ver[0][0]['nombre_proyecto']=='0'){
		$this->set('obra','');
		$this->set('responsable','');
	}else{
		$this->set('obra',$ver[0][0]['nombre_proyecto']);
		$this->set('responsable',$ver[0][0]['responsable_proyecto']);
	}


	$actividad=$this->ccnd02_proyectos_actividades->generateList($conditions,'cod_actividad ASC', null, '{n}.ccnd02_proyectos_actividades.cod_actividad','{n}.ccnd02_proyectos_actividades.denominacion');
	if($actividad!=null){
		$this->concatena_actividad($actividad,'actividad');
	}else{
		$this->set('actividad',array());
	}

	if($this->ccnd02_proyectos_actividades->FindCount($conditions)!=0){
		$this->consulta();
		$this->render('consulta');
	}

		///////I.V.A//////////
			$iva=$this->ccnd02_proyectos_actividades->execute("select porcentaje_iva from cscd04_ordencompra_parametros where cod_dep=1 limit 1");
			$this->set('iva',$iva[0][0]['porcentaje_iva']);
		//////////////////////


	$this->limpiar_lista_equipo();
	$this->limpiar_lista_material();
	$this->limpiar_lista_obra();

	$this->data=null;
}//index



function seleccion_actividad($var=null){
	$this->layout="ajax";
	$cod_republica     = $this->Session->read('CC_republica');
	$cod_estado        = $this->Session->read('CC_estado');
	$cod_municipio     = $this->Session->read('CC_municipio');
	$cod_parroquia     = $this->Session->read('CC_parroquia');
	$cod_centro        = $this->Session->read('CC_centro');
	$cod_concejo       = $this->Session->read('CC_concejo');
	$ano               = $this->Session->read('concejos_comunal_id1');
	$cod_proyecto      = $this->Session->read('concejos_comunal_id2');

	if($var!=''){
		if($var!='agregar'){
			$this->set('agregar','no_agregar');

			$conditions  = "cod_republica=".$cod_republica." and cod_estado=".$cod_estado." and cod_municipio=".$cod_municipio." and cod_parroquia=".$cod_parroquia." and cod_centro=".$cod_centro." and cod_concejo=".$cod_concejo." and ano=".$ano." and cod_proyecto='".$cod_proyecto."'";
			$actividad=$this->ccnd02_proyectos_actividades->generateList($conditions,'cod_actividad ASC', null, '{n}.ccnd02_proyectos_actividades.cod_actividad','{n}.ccnd02_proyectos_actividades.denominacion');
			if($actividad!=null){
				$this->concatena_actividad($actividad,'actividad');
			}else{
				$this->set('actividad',array());
			}

			$datos=$this->ccnd02_proyectos_actividades->execute("select * from ccnd02_proyectos_actividades where ".$conditions. " and cod_actividad=".$var);
			$this->set('datos',$datos);

			$filtro  = "cod_republica=".$cod_republica." and cod_estado=".$cod_estado." and cod_municipio=".$cod_municipio." and cod_parroquia=".$cod_parroquia." and cod_centro=".$cod_centro." and cod_concejo=".$cod_concejo." and ano=".$ano." and cod_proyecto='".$cod_proyecto."' and cod_actividad=".$datos[0][0]['cod_actividad'];

			$equipo=$this->ccnd02_proyectos_actividad_equipos->execute("select * from ccnd02_proyectos_actividad_equipos where ".$filtro." order by numero_renglon asc");
			$this->set('equipo',$equipo);

			$materiales=$this->ccnd02_proyectos_actividad_materiales->execute("select * from ccnd02_proyectos_actividad_materiales where ".$filtro." order by numero_renglon asc");
			$this->set('materiales',$materiales);

			$obra=$this->ccnd02_proyectos_actividad_manoobra->execute("select * from ccnd02_proyectos_actividad_manoobra where ".$filtro." order by numero_renglon asc");
			$this->set('mano_obra',$obra);

			///////I.V.A//////////
				$iva=$this->ccnd02_proyectos_actividades->execute("select porcentaje_iva from cscd04_ordencompra_parametros where cod_dep=1 limit 1");
				$this->set('iva',$iva[0][0]['porcentaje_iva']);
			//////////////////////

		}else{
			$this->set('agregar','agregar');

			$conditions  = "cod_republica=".$cod_republica." and cod_estado=".$cod_estado." and cod_municipio=".$cod_municipio." and cod_parroquia=".$cod_parroquia." and cod_centro=".$cod_centro." and cod_concejo=".$cod_concejo." and ano=".$ano." and cod_proyecto='".$cod_proyecto."'";
			$actividad=$this->ccnd02_proyectos_actividades->generateList($conditions,'cod_actividad ASC', null, '{n}.ccnd02_proyectos_actividades.cod_actividad','{n}.ccnd02_proyectos_actividades.denominacion');
			if($actividad!=null){
				$this->concatena_actividad($actividad,'actividad');
			}else{
				$this->set('actividad',array());
			}

				///////////////////numero de actividad///////////////////////////
			$ver=$this->ccnd02_proyectos_actividades->execute("select * from ccnd02_proyectos_actividades where ".$conditions." order by cod_actividad desc limit 1");
				if($ver!=null)
					$cod_actividad=$ver[0][0]['cod_actividad']+1;
				else
					$cod_actividad=1;

			$this->set('cod_actividad',$cod_actividad);
			///////////////////////////////////////////////////////////////////

			///////I.V.A//////////
				$iva=$this->ccnd02_proyectos_actividades->execute("select porcentaje_iva from cscd04_ordencompra_parametros where cod_dep=1 limit 1");
				$this->set('iva',$iva[0][0]['porcentaje_iva']);
				$this->Session->write('porcentaje_iva',$iva[0][0]['porcentaje_iva']);
			//////////////////////

		}
	}else{
		$this->index();
		$this->render('index');
		return;
	}

	$ver=$this->ccnd02_proyectos->execute("select * from ccnd02_proyectos where ".$conditions);
	if($ver[0][0]['nombre_proyecto']=='0'){
		$this->set('obra','');
		$this->set('responsable','');
	}else{
		$this->set('obra',$ver[0][0]['nombre_proyecto']);
		$this->set('responsable',$ver[0][0]['responsable_proyecto']);
	}



}//fin seleccion_actividad


function calcula_totales($var=null){
	$this->layout="ajax";

	if(!isset($_SESSION ["items1"]) && !isset($_SESSION ["items2"]) && !isset($_SESSION ["items3"])){
		$this->set('errorMessage', 'Debe ingresar los equipos, materiales o mano de obra');

		echo "<script>document.getElementById('cantidad').value='';</script>";


	}else{

			///////I.V.A//////////
				$iva=$this->Session->read('porcentaje_iva');
			//////////////////////

			///////////////coso actividad///////////////////
					echo "<script>";
						echo "var porcentaje_iva=".$iva.";";
						echo "var n=0;
							 if(document.getElementById('total_obra')){
								n+=eval(document.getElementById('total_obra').value);
							  }
							  if(document.getElementById('total_material')){
								n+=eval(document.getElementById('total_material').value);
							  }
							  if(document.getElementById('total_equipos')){
								n+=eval(document.getElementById('total_equipos').value);
							  }
							  ";
//						echo "var n=eval(document.getElementById('total_obra').value)+eval(document.getElementById('total_material').value)+eval(document.getElementById('total_equipos').value);";
						echo "var p=eval(n)*".$var.";";
						echo "var iva=((eval(n)*".$var.")*porcentaje_iva)/100;";
						echo "var total=eval(p)+eval(iva);";

						echo "var p=redondear(p, 2);";
						echo "var iva=redondear(iva, 2);";
						echo "var total=redondear(total, 2);";

						echo "document.getElementById('costo_actividad').value=p;";
						echo "moneda('costo_actividad');";

						echo "document.getElementById('iva').value=iva;";
						echo "moneda('iva');";

						echo "document.getElementById('total_costo_actividad').value=total;";
						echo "moneda('total_costo_actividad');";
					echo "</script>";
			////////////////////////////////////////////////




	}

}

function calcula_totales2($var=null){
	$this->layout="ajax";

			///////I.V.A//////////
				$iva=$this->Session->read('porcentaje_iva');
			//////////////////////

			///////////////coso actividad///////////////////
					echo "<script>";
						echo "var n=eval(document.getElementById('monto_unitario2').value);";
						echo "var p=eval(n)*".$var.";";
						echo "var iva=((eval(n)*".$var.")*".$iva.")/100;";
						echo "var total=eval(p)+eval(iva);";

						echo "var p=redondear(p, 2);";
						echo "var iva=redondear(iva, 2);";
						echo "var total=redondear(total, 2);";

						echo "document.getElementById('costo_actividad').value=p;";
						echo "moneda('costo_actividad');";

						echo "document.getElementById('iva').value=iva;";
						echo "moneda('iva');";

						echo "document.getElementById('total_costo_actividad').value=total;";
						echo "moneda('total_costo_actividad');";
					echo "</script>";
			////////////////////////////////////////////////


}


function calcula_iva($p=null,$var=null){
	$this->layout="ajax";
	$this->Session->write('porcentaje_iva',$this->Formato1($var));
	$iva=$this->Session->read('porcentaje_iva');
if($p==1){
	echo "<script>";
		echo "if(document.getElementById('monto_unitario').value!='' && document.getElementById('cantidad').value!=''){
				var n=0;
				 if(document.getElementById('total_obra')){
					n+=eval(document.getElementById('total_obra').value);
				  }
				  if(document.getElementById('total_material')){
					n+=eval(document.getElementById('total_material').value);
				  }
				  if(document.getElementById('total_equipos')){
					n+=eval(document.getElementById('total_equipos').value);
				  }
				var p=eval(n)*eval(document.getElementById('cantidad').value);
				var iva=(eval(p)*".$iva.")/100;
				var total=eval(p)+eval(iva);

				var p=redondear(p, 2);
				var iva=redondear(iva, 2);
				var total=redondear(total, 2);

				document.getElementById('costo_actividad').value=p;
				moneda('costo_actividad');

				document.getElementById('iva').value=iva;
				moneda('iva');

				document.getElementById('total_costo_actividad').value=total;
				moneda('total_costo_actividad');
			  }";
	echo "</script>";
//	var n=eval(document.getElementById('total_obra').value)+eval(document.getElementById('total_material').value)+eval(document.getElementById('total_equipos').value);
}else{

	echo "<script>";
		echo "if(document.getElementById('monto_unitario').value!='' && document.getElementById('cantidad').value!=''){
				var n=eval(document.getElementById('monto_unitario2').value);
				var p=eval(n)*eval(document.getElementById('cantidad').value);
				var iva=(eval(p)*".$iva.")/100;
				var total=eval(p)+eval(iva);

				var p=redondear(p, 2);
				var iva=redondear(iva, 2);
				var total=redondear(total, 2);

				document.getElementById('costo_actividad').value=p;
				moneda('costo_actividad');

				document.getElementById('iva').value=iva;
				moneda('iva');

				document.getElementById('total_costo_actividad').value=total;
				moneda('total_costo_actividad');
			  }";
	echo "</script>";

}



}





function guardar(){
	$this->layout="ajax";
//	pr($this->data);
	$cod_republica     = $this->Session->read('CC_republica');
	$cod_estado        = $this->Session->read('CC_estado');
	$cod_municipio     = $this->Session->read('CC_municipio');
	$cod_parroquia     = $this->Session->read('CC_parroquia');
	$cod_centro        = $this->Session->read('CC_centro');
	$cod_concejo       = $this->Session->read('CC_concejo');
	$ano               = $this->Session->read('concejos_comunal_id1');
	$cod_proyecto      = $this->Session->read('concejos_comunal_id2');

	if(empty($this->data['ccnp01']['obra'])){
		$this->set('errorMessage', 'ingrese la obra');

	}else if(empty($this->data['ccnp01']['responsable'])){
		$this->set('errorMessage', 'ingrese el responsable de la obra');

	}else if(empty($this->data['ccnp01']['deno_actividad'])){
		$this->set('errorMessage', 'ingrese la denominación de la actividad');

	}else if(!isset($_SESSION ["items1"]) && !isset($_SESSION ["items2"]) && !isset($_SESSION ["items3"])){
		$this->set('errorMessage', 'Debe ingresar los equipos, materiales o mano de obra');

	}else if(empty($this->data['ccnp01']['cantidad'])){
		$this->set('errorMessage', 'ingrese la cantidad');

	}else if(empty($this->data['ccnp01']['iva1'])){
		$this->set('errorMessage', 'ingrese el i.v.a');

	}else{

		$obra=$this->data['ccnp01']['obra'];
		$responsable=$this->data['ccnp01']['responsable'];
		$cod_actividad=$this->data['ccnp01']['cod_actividad'];
		$deno_actividad=$this->data['ccnp01']['deno_actividad'];
		$cantidad=$this->data['ccnp01']['cantidad'];
		$iva=$this->Formato1($this->data['ccnp01']['iva1']);


//		$update=$this->ccnd02_proyectos->execute("BEGIN;update ccnd02_proyectos set obra='$obra',responsable='$responsable' where cod_republica='$cod_republica' and cod_estado='$cod_estado' and cod_municipio='$cod_municipio' and cod_parroquia='$cod_parroquia' and cod_centro='$cod_centro' and cod_concejo='$cod_concejo' and ano='$ano' and cod_proyecto='$cod_proyecto'");
		$sql_insert = "INSERT INTO ccnd02_proyectos_actividades VALUES('$cod_republica','$cod_estado','$cod_municipio','$cod_parroquia','$cod_centro','$cod_concejo','$ano','$cod_proyecto','$cod_actividad','$deno_actividad','$cantidad','$iva')";
		$sw = $this->ccnd02_proyectos->execute($sql_insert);


		if(isset($_SESSION ["items1"])){
			$i=1;
			$monto1=0;
			$monto2=0;
			$monto3=0;
			$guardar=array();
			foreach($_SESSION ["items1"] as $guardar){
				if($guardar!=null){
					$costo=$this->Formato1($guardar[2]);
					$sql_insert1 = "INSERT INTO ccnd02_proyectos_actividad_equipos VALUES('$cod_republica','$cod_estado','$cod_municipio','$cod_parroquia','$cod_centro','$cod_concejo','$ano','$cod_proyecto','$cod_actividad','$i','$guardar[1]', '$costo')";
					$sw1 = $this->ccnd02_proyectos->execute($sql_insert1);
					$i++;
					$monto1+=$guardar[2];
				}
		     }

		}


		if(isset($_SESSION ["items2"])){
			$i=1;
	     	$guardar=array();
	     	foreach($_SESSION ["items2"] as $guardar){
				if($guardar!=null){
					$costo2=$this->Formato1($guardar[2]);
					$sql_insert2 = "INSERT INTO ccnd02_proyectos_actividad_materiales VALUES('$cod_republica','$cod_estado','$cod_municipio','$cod_parroquia','$cod_centro','$cod_concejo','$ano','$cod_proyecto','$cod_actividad','$i','$guardar[1]', '$costo2')";
					$sw2 = $this->ccnd02_proyectos->execute($sql_insert2);
					$i++;
					$monto2+=$guardar[2];
				}
		     }

		}


		if(isset($_SESSION ["items3"])){
			$i=1;
	     	$guardar=array();
	     	foreach($_SESSION ["items3"] as $guardar){
				if($guardar!=null){
					$costo3=$this->Formato1($guardar[2]);
					$sql_insert3 = "INSERT INTO ccnd02_proyectos_actividad_manoobra VALUES('$cod_republica','$cod_estado','$cod_municipio','$cod_parroquia','$cod_centro','$cod_concejo','$ano','$cod_proyecto','$cod_actividad','$i','$guardar[1]', '$costo3')";
					$sw3 = $this->ccnd02_proyectos->execute($sql_insert3);
					$i++;
					$monto3+=$guardar[2];
				}
		     }
		}


		if($sw>1){
//			$this->ccnd02_proyectos->execute('COMMIT');
			$this->set('Message_existe', 'registro exitoso');
				$this->limpiar_lista_equipo();
				$this->limpiar_lista_material();
				$this->limpiar_lista_obra();
			echo" <script> ver_documento('/ccnp01_recursos_necesarios/seleccion_actividad/$cod_actividad','tab_pestana_descripcion_proyecto_2'); </script>";
	     }else{
//	     	$this->ccnd02_proyectos->execute('ROLLBACK');
			$this->set('errorMessage', 'LOS DATOS NO PUDIERON SER REGISTRADOS');
	     }

	}


}//fin guardar




function consulta($pagina=null) {
	$this->layout="ajax";

	$cod_republica     = $this->Session->read('CC_republica');
	$cod_estado        = $this->Session->read('CC_estado');
	$cod_municipio     = $this->Session->read('CC_municipio');
	$cod_parroquia     = $this->Session->read('CC_parroquia');
	$cod_centro        = $this->Session->read('CC_centro');
	$cod_concejo       = $this->Session->read('CC_concejo');
	$ano               = $this->Session->read('concejos_comunal_id1');
	$cod_proyecto      = $this->Session->read('concejos_comunal_id2');

	$conditions  = "cod_republica=".$cod_republica." and cod_estado=".$cod_estado." and cod_municipio=".$cod_municipio." and cod_parroquia=".$cod_parroquia." and cod_centro=".$cod_centro." and cod_concejo=".$cod_concejo." and ano=".$ano." and cod_proyecto='".$cod_proyecto."'";
	$actividad=$this->ccnd02_proyectos_actividades->generateList($conditions,'cod_actividad ASC', null, '{n}.ccnd02_proyectos_actividades.cod_actividad','{n}.ccnd02_proyectos_actividades.denominacion');
	if($actividad!=null){
		$this->concatena_actividad($actividad,'actividad');
	}else{
		$this->set('actividad',array());
	}

	if(isset($pagina)){
		$Tfilas=$this->ccnd02_proyectos_actividades->findCount($conditions);
        if($Tfilas!=0){
        	$x=$this->ccnd02_proyectos_actividades->findAll($conditions,null,"cod_actividad ASC",1,$pagina,null);

            $this->set('DATA',$x);
            $this->set('pagina',$pagina);
          	$this->set('siguiente',$pagina+1);
          	$this->set('anterior',$pagina-1);
            $this->bt_nav($Tfilas,$pagina);
            $this->set('numT',$Tfilas);
			$this->set('numP',$pagina);
        }else{
	 	       $this->set('errorMessage', 'No se encontrar&oacute;n datos');
	 	       $this->set('noExiste',true);
	 	       $this->index();
			   $this->render("index");
			   return;
        }

	}else{
		$pagina=1;
		$Tfilas=$this->ccnd02_proyectos_actividades->findCount($conditions);

        if($Tfilas!=0){
        	$x=$this->ccnd02_proyectos_actividades->findAll($conditions,null,"cod_actividad ASC",1,$pagina,null);
			$this->set('DATA',$x);
			$this->set('pagina',$pagina);
          	$this->set('siguiente',$pagina+1);
          	$this->set('anterior',$pagina-1);
          	$this->bt_nav($Tfilas,$pagina);
          	$this->set('numT',$Tfilas);
			$this->set('numP',$pagina);

        }else{
	 	       $this->set('errorMessage', 'No se encontrar&oacute;n datos');
	 	       $this->set('noExiste',true);
	 	       $this->index();
			   $this->render("index");
			   return;
        }
	}

	$ver=$this->ccnd02_proyectos->execute("select * from ccnd02_proyectos where ".$conditions);
	if($ver[0][0]['nombre_proyecto']=='0'){
		$this->set('obra','');
		$this->set('responsable','');
	}else{
		$this->set('obra',$ver[0][0]['nombre_proyecto']);
		$this->set('responsable',$ver[0][0]['responsable_proyecto']);
	}


	$datos=$this->ccnd02_proyectos_actividades->execute("select * from ccnd02_proyectos_actividades where ".$conditions. " and cod_actividad=".$x[0]["ccnd02_proyectos_actividades"]["cod_actividad"]);
	$this->set('datos',$datos);

	$filtro  = "cod_republica=".$cod_republica." and cod_estado=".$cod_estado." and cod_municipio=".$cod_municipio." and cod_parroquia=".$cod_parroquia." and cod_centro=".$cod_centro." and cod_concejo=".$cod_concejo." and ano=".$ano." and cod_proyecto='".$cod_proyecto."' and cod_actividad=".$datos[0][0]['cod_actividad'];

	$equipo=$this->ccnd02_proyectos_actividad_equipos->execute("select * from ccnd02_proyectos_actividad_equipos where ".$filtro." order by numero_renglon asc");
	$this->set('equipo',$equipo);

	$materiales=$this->ccnd02_proyectos_actividad_materiales->execute("select * from ccnd02_proyectos_actividad_materiales where ".$filtro." order by numero_renglon asc");
	$this->set('materiales',$materiales);

	$obra=$this->ccnd02_proyectos_actividad_manoobra->execute("select * from ccnd02_proyectos_actividad_manoobra where ".$filtro." order by numero_renglon asc");
	$this->set('mano_obra',$obra);



	$this->Session->write('porcentaje_iva',$this->Formato1($x[0]['ccnd02_proyectos_actividades']['porcentaje_iva']));
	$iva=$this->Session->read('porcentaje_iva');


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



 function modificar($cod_actividad=null,$pagina=null){
	$this->layout="ajax";

	$cod_republica     = $this->Session->read('CC_republica');
	$cod_estado        = $this->Session->read('CC_estado');
	$cod_municipio     = $this->Session->read('CC_municipio');
	$cod_parroquia     = $this->Session->read('CC_parroquia');
	$cod_centro        = $this->Session->read('CC_centro');
	$cod_concejo       = $this->Session->read('CC_concejo');
	$ano               = $this->Session->read('concejos_comunal_id1');
	$cod_proyecto      = $this->Session->read('concejos_comunal_id2');

	$conditions  = "cod_republica=".$cod_republica." and cod_estado=".$cod_estado." and cod_municipio=".$cod_municipio." and cod_parroquia=".$cod_parroquia." and cod_centro=".$cod_centro." and cod_concejo=".$cod_concejo." and ano=".$ano." and cod_proyecto='".$cod_proyecto."'";

	$ver=$this->ccnd02_proyectos->execute("select * from ccnd02_proyectos where ".$conditions);
	if($ver[0][0]['nombre_proyecto']=='0'){
		$this->set('obra','');
		$this->set('responsable','');
	}else{
		$this->set('obra',$ver[0][0]['nombre_proyecto']);
		$this->set('responsable',$ver[0][0]['responsable_proyecto']);
	}


	$datos=$this->ccnd02_proyectos_actividades->execute("select * from ccnd02_proyectos_actividades where ".$conditions. " and cod_actividad=".$cod_actividad);
	$this->set('datos',$datos);

	$filtro  = "cod_republica=".$cod_republica." and cod_estado=".$cod_estado." and cod_municipio=".$cod_municipio." and cod_parroquia=".$cod_parroquia." and cod_centro=".$cod_centro." and cod_concejo=".$cod_concejo." and ano=".$ano." and cod_proyecto='".$cod_proyecto."' and cod_actividad=".$datos[0][0]['cod_actividad'];

	$equipo=$this->ccnd02_proyectos_actividad_equipos->execute("select * from ccnd02_proyectos_actividad_equipos where ".$filtro." order by numero_renglon asc");
	$this->set('equipo',$equipo);

	$materiales=$this->ccnd02_proyectos_actividad_materiales->execute("select * from ccnd02_proyectos_actividad_materiales where ".$filtro." order by numero_renglon asc");
	$this->set('materiales',$materiales);

	$obra=$this->ccnd02_proyectos_actividad_manoobra->execute("select * from ccnd02_proyectos_actividad_manoobra where ".$filtro." order by numero_renglon asc");
	$this->set('mano_obra',$obra);


	$this->Session->write('porcentaje_iva',$this->Formato1($datos[0][0]['porcentaje_iva']));
	$iva=$this->Session->read('porcentaje_iva');


	$v1=$this->ccnd02_proyectos_actividad_equipos->execute("select * from ccnd02_proyectos_actividad_equipos where ".$filtro." order by numero_renglon desc limit 1");
	if($v1!=null)
		$renglon1=$v1[0][0]['numero_renglon']+1;
	else
		$renglon1=1;

	$v2=$this->ccnd02_proyectos_actividad_materiales->execute("select * from ccnd02_proyectos_actividad_materiales where ".$filtro." order by numero_renglon desc limit 1");
	if($v2!=null)
		$renglon2=$v2[0][0]['numero_renglon']+1;
	else
		$renglon2=1;

	$v3=$this->ccnd02_proyectos_actividad_manoobra->execute("select * from ccnd02_proyectos_actividad_manoobra where ".$filtro." order by numero_renglon desc limit 1");
	if($v3!=null)
		$renglon3=$v3[0][0]['numero_renglon']+1;
	else
		$renglon3=1;

	$this->set('renglon1',$renglon1);
	$this->set('renglon2',$renglon2);
	$this->set('renglon3',$renglon3);



	$this->set('pagina',$pagina);
$this->data=null;
//	$this->data['ccnp01']['descripcion_equipo']=null;
//echo "<script>document.getElementById('descripcion_equipo').value='';</script>";


 }

 function modificar_items($tipo=null,$cod_actividad=null,$numero_renglon=null,$i=null,$pagina=null){
 	$this->layout="ajax";

	$cod_republica     = $this->Session->read('CC_republica');
	$cod_estado        = $this->Session->read('CC_estado');
	$cod_municipio     = $this->Session->read('CC_municipio');
	$cod_parroquia     = $this->Session->read('CC_parroquia');
	$cod_centro        = $this->Session->read('CC_centro');
	$cod_concejo       = $this->Session->read('CC_concejo');
	$ano               = $this->Session->read('concejos_comunal_id1');
	$cod_proyecto      = $this->Session->read('concejos_comunal_id2');

	$conditions  = "cod_republica=".$cod_republica." and cod_estado=".$cod_estado." and cod_municipio=".$cod_municipio." and cod_parroquia=".$cod_parroquia." and cod_centro=".$cod_centro." and cod_concejo=".$cod_concejo." and ano=".$ano." and cod_proyecto='".$cod_proyecto."' and cod_actividad=".$cod_actividad." and numero_renglon=".$numero_renglon;

	if($tipo=='obra'){
		$ver=$this->ccnd02_proyectos_actividad_manoobra->execute("select * from ccnd02_proyectos_actividad_manoobra where ".$conditions);
	}else if($tipo=='material'){
		$ver=$this->ccnd02_proyectos_actividad_materiales->execute("select * from ccnd02_proyectos_actividad_materiales where ".$conditions);
	}else{
		$ver=$this->ccnd02_proyectos_actividad_equipos->execute("select * from ccnd02_proyectos_actividad_equipos where ".$conditions);
	}

	$this->set('k',$i);
	$this->set('dato',$ver);
	$this->set('tipo',$tipo);
	$this->set('pagina',$pagina);


 }


function guardar_modificar_items($tipo=null,$cod_actividad=null,$numero_renglon=null,$i=null,$pagina=null){
 	$this->layout="ajax";

	$cod_republica     = $this->Session->read('CC_republica');
	$cod_estado        = $this->Session->read('CC_estado');
	$cod_municipio     = $this->Session->read('CC_municipio');
	$cod_parroquia     = $this->Session->read('CC_parroquia');
	$cod_centro        = $this->Session->read('CC_centro');
	$cod_concejo       = $this->Session->read('CC_concejo');
	$ano               = $this->Session->read('concejos_comunal_id1');
	$cod_proyecto      = $this->Session->read('concejos_comunal_id2');

	$conditions  = "cod_republica=".$cod_republica." and cod_estado=".$cod_estado." and cod_municipio=".$cod_municipio." and cod_parroquia=".$cod_parroquia." and cod_centro=".$cod_centro." and cod_concejo=".$cod_concejo." and ano=".$ano." and cod_proyecto='".$cod_proyecto."' and cod_actividad=".$cod_actividad." and numero_renglon=".$numero_renglon;
//	pr($this->data);
	if($tipo=='obra'){
		if(!empty($this->data['ccnp01']['descripcion_obra'.$i]) && !empty($this->data['ccnp01']['costo_obra'.$i])){
			$descripcion_obra=$this->data['ccnp01']['descripcion_obra'.$i];
			$costo_obra=$this->Formato1($this->data['ccnp01']['costo_obra'.$i]);
			$ver=$this->ccnd02_proyectos_actividad_manoobra->execute("update ccnd02_proyectos_actividad_manoobra set denominacion_manoobra='$descripcion_obra',costo_unitario='$costo_obra' where ".$conditions);
//			$this->set('Message_existe', 'los datos fueron modificados');
		}else{
			$this->set('errorMessage', 'debe completar los datos');
		}
	}else if($tipo=='material'){
		if(!empty($this->data['ccnp01']['descripcion_material'.$i]) && !empty($this->data['ccnp01']['costo_material'.$i])){
			$descripcion_obra=$this->data['ccnp01']['descripcion_material'.$i];
			$costo_obra=$this->Formato1($this->data['ccnp01']['costo_material'.$i]);
			$ver=$this->ccnd02_proyectos_actividad_materiales->execute("update ccnd02_proyectos_actividad_materiales set denominacion_materiales='$descripcion_obra',costo_unitario='$costo_obra' where ".$conditions);
//			$this->set('Message_existe', 'los datos fueron modificados');
		}else{
			$this->set('errorMessage', 'debe completar los datos');
		}
	}else{
		if(!empty($this->data['ccnp01']['descripcion_equipo'.$i]) && !empty($this->data['ccnp01']['costo_equipo'.$i])){
			$descripcion_obra=$this->data['ccnp01']['descripcion_equipo'.$i];
			$costo_obra=$this->Formato1($this->data['ccnp01']['costo_equipo'.$i]);
			$ver=$this->ccnd02_proyectos_actividad_equipos->execute("update ccnd02_proyectos_actividad_equipos set denominacion_equipo='$descripcion_obra',costo_unitario='$costo_obra' where ".$conditions);
//			$this->set('Message_existe', 'los datos fueron modificados');
		}else{
			$this->set('errorMessage', 'debe completar los datos');
		}
	}

	if(isset($pagina)){
		$this->consulta($pagina);
		$this->render('consulta');
	}else{
		$this->seleccion_actividad($cod_actividad);
		$this->render('seleccion_actividad');
	}

 }


 function guardar_modificar($cod_actividad=null,$pagina=null){
	$this->layout="ajax";
	$cod_republica     = $this->Session->read('CC_republica');
	$cod_estado        = $this->Session->read('CC_estado');
	$cod_municipio     = $this->Session->read('CC_municipio');
	$cod_parroquia     = $this->Session->read('CC_parroquia');
	$cod_centro        = $this->Session->read('CC_centro');
	$cod_concejo       = $this->Session->read('CC_concejo');
	$ano               = $this->Session->read('concejos_comunal_id1');
	$cod_proyecto      = $this->Session->read('concejos_comunal_id2');

	if(empty($this->data['ccnp01']['obra'])){
		$this->set('errorMessage', 'ingrese la obra');

	}else if(empty($this->data['ccnp01']['responsable'])){
		$this->set('errorMessage', 'ingrese el responsable de la obra');

	}else if(empty($this->data['ccnp01']['deno_actividad'])){
		$this->set('errorMessage', 'ingrese la denominación de la actividad');

	}else if(empty($this->data['ccnp01']['cantidad'])){
		$this->set('errorMessage', 'ingrese la cantidad');

	}else if(empty($this->data['ccnp01']['iva1'])){
		$this->set('errorMessage', 'ingrese el i.v.a');

	}else{

		$obra=$this->data['ccnp01']['obra'];
		$responsable=$this->data['ccnp01']['responsable'];
		$deno_actividad=$this->data['ccnp01']['deno_actividad'];
		$cantidad=$this->data['ccnp01']['cantidad'];
		$iva=$this->Formato1($this->data['ccnp01']['iva1']);

//		$update=$this->ccnd02_proyectos->execute("BEGIN;update ccnd02_proyectos set obra='$obra',responsable='$responsable' where cod_republica='$cod_republica' and cod_estado='$cod_estado' and cod_municipio='$cod_municipio' and cod_parroquia='$cod_parroquia' and cod_centro='$cod_centro' and cod_concejo='$cod_concejo' and ano='$ano' and cod_proyecto='$cod_proyecto'");
		$update1=$this->ccnd02_proyectos_actividades->execute("update ccnd02_proyectos_actividades set denominacion='$deno_actividad',cantidad='$cantidad',porcentaje_iva='$iva' where cod_republica='$cod_republica' and cod_estado='$cod_estado' and cod_municipio='$cod_municipio' and cod_parroquia='$cod_parroquia' and cod_centro='$cod_centro' and cod_concejo='$cod_concejo' and ano='$ano' and cod_proyecto='$cod_proyecto' and cod_actividad='$cod_actividad'");


		if($update1>1){
			$this->ccnd02_proyectos_actividades->execute('COMMIT');
			$this->set('Message_existe', 'los datos fueron modificados');
		}else{
			$this->ccnd02_proyectos_actividades->execute('ROLLBACK');
			$this->set('errorMessage', 'LOS DATOS NO PUDIERON SER MODIFICADOS');
		}

		if(isset($pagina)){
			echo" <script> ver_documento('/ccnp01_recursos_necesarios/consulta/$pagina','tab_pestana_descripcion_proyecto_2'); </script>";
		}else{
			echo" <script> ver_documento('/ccnp01_recursos_necesarios/seleccion_actividad/$cod_actividad','tab_pestana_descripcion_proyecto_2'); </script>";
		}
	}

 }


 function agregar_items($tipo=null,$cod_actividad=null,$pagina=null){
	$this->layout="ajax";
//	pr($this->data);
	$cod_republica     = $this->Session->read('CC_republica');
	$cod_estado        = $this->Session->read('CC_estado');
	$cod_municipio     = $this->Session->read('CC_municipio');
	$cod_parroquia     = $this->Session->read('CC_parroquia');
	$cod_centro        = $this->Session->read('CC_centro');
	$cod_concejo       = $this->Session->read('CC_concejo');
	$ano               = $this->Session->read('concejos_comunal_id1');
	$cod_proyecto      = $this->Session->read('concejos_comunal_id2');

if($tipo=='equipo'){
	if(empty($this->data['ccnp01']['descripcion_equipo'])){
		$this->set('errorMessage', 'ingrese la descripción del equipo');

	}else if(empty($this->data['ccnp01']['costo_equipo'])){
		$this->set('errorMessage', 'ingrese el costo del equipo');

	}else{
		$renglon_equipo=$this->data['ccnp01']['renglon_equipo'];
		$descripcion_equipo=$this->data['ccnp01']['descripcion_equipo'];
		$costo_equipo=$this->Formato1($this->data['ccnp01']['costo_equipo']);

		$sql_insert = "INSERT INTO ccnd02_proyectos_actividad_equipos VALUES('$cod_republica','$cod_estado','$cod_municipio','$cod_parroquia','$cod_centro','$cod_concejo','$ano','$cod_proyecto','$cod_actividad','$renglon_equipo','$descripcion_equipo', '$costo_equipo')";
		$sw1 = $this->ccnd02_proyectos_actividad_equipos->execute($sql_insert);
		if($sw1>1){
			$this->set('Message_existe', 'el equipo fue registrado');

		}else{
			$this->set('errorMessage', 'no se pudo registrar el equipo');
		}
	}
}else if($tipo=='material'){

		if(empty($this->data['ccnp01']['descripcion_material'])){
		$this->set('errorMessage', 'ingrese la descripción del material');

		}else if(empty($this->data['ccnp01']['costo_material'])){
			$this->set('errorMessage', 'ingrese el costo del material');

		}else{
			$renglon_equipo=$this->data['ccnp01']['renglon_material'];
			$descripcion_equipo=$this->data['ccnp01']['descripcion_material'];
			$costo_equipo=$this->Formato1($this->data['ccnp01']['costo_material']);

			$sql_insert = "INSERT INTO ccnd02_proyectos_actividad_materiales VALUES('$cod_republica','$cod_estado','$cod_municipio','$cod_parroquia','$cod_centro','$cod_concejo','$ano','$cod_proyecto','$cod_actividad','$renglon_equipo','$descripcion_equipo', '$costo_equipo')";
			$sw1 = $this->ccnd02_proyectos_actividad_materiales->execute($sql_insert);
			if($sw1>1){
				$this->set('Message_existe', 'el material fue registrado');

			}else{
				$this->set('errorMessage', 'no se pudo registrar el material');
			}
		}



}else if($tipo=='obra'){

	if(empty($this->data['ccnp01']['descripcion_obra'])){
		$this->set('errorMessage', 'ingrese la descripción de la mano de obra');

		}else if(empty($this->data['ccnp01']['costo_obra'])){
			$this->set('errorMessage', 'ingrese el costo de la mano de obra');

		}else{
			$renglon_equipo=$this->data['ccnp01']['renglon_obra'];
			$descripcion_equipo=$this->data['ccnp01']['descripcion_obra'];
			$costo_equipo=$this->Formato1($this->data['ccnp01']['costo_obra']);

			$sql_insert = "INSERT INTO ccnd02_proyectos_actividad_manoobra VALUES('$cod_republica','$cod_estado','$cod_municipio','$cod_parroquia','$cod_centro','$cod_concejo','$ano','$cod_proyecto','$cod_actividad','$renglon_equipo','$descripcion_equipo', '$costo_equipo')";
			$sw1 = $this->ccnd02_proyectos_actividad_manoobra->execute($sql_insert);
			if($sw1>1){
				$this->set('Message_existe', 'la mano de obra fue registrada');

			}else{
				$this->set('errorMessage', 'no se pudo registrar la mano de obra');
			}
		}


}

if(isset($pagina)){
//	echo" <script> ver_documento('/ccnp01_recursos_necesarios/consulta/$pagina','tab_pestana_descripcion_proyecto_2'); </script>";
	echo" <script> ver_documento('/ccnp01_recursos_necesarios/modificar/$cod_actividad/$pagina','tab_pestana_descripcion_proyecto_2'); </script>";
}else{
//	echo" <script> ver_documento('/ccnp01_recursos_necesarios/seleccion_actividad/$cod_actividad','tab_pestana_descripcion_proyecto_2'); </script>";
	echo" <script> ver_documento('/ccnp01_recursos_necesarios/modificar/$cod_actividad/$pagina','tab_pestana_descripcion_proyecto_2'); </script>";
}


 }


function eliminar_items($tipo=null,$cod_actividad=null,$numero_renglon=null,$pagina=null){
	$this->layout="ajax";
	$cod_republica     = $this->Session->read('CC_republica');
	$cod_estado        = $this->Session->read('CC_estado');
	$cod_municipio     = $this->Session->read('CC_municipio');
	$cod_parroquia     = $this->Session->read('CC_parroquia');
	$cod_centro        = $this->Session->read('CC_centro');
	$cod_concejo       = $this->Session->read('CC_concejo');
	$ano               = $this->Session->read('concejos_comunal_id1');
	$cod_proyecto      = $this->Session->read('concejos_comunal_id2');

	$conditions1  = "cod_republica=".$cod_republica." and cod_estado=".$cod_estado." and cod_municipio=".$cod_municipio." and cod_parroquia=".$cod_parroquia." and cod_centro=".$cod_centro." and cod_concejo=".$cod_concejo." and ano=".$ano." and cod_proyecto='".$cod_proyecto."' and cod_actividad=".$cod_actividad;
	$conditions  = "cod_republica=".$cod_republica." and cod_estado=".$cod_estado." and cod_municipio=".$cod_municipio." and cod_parroquia=".$cod_parroquia." and cod_centro=".$cod_centro." and cod_concejo=".$cod_concejo." and ano=".$ano." and cod_proyecto='".$cod_proyecto."' and cod_actividad=".$cod_actividad." and numero_renglon=".$numero_renglon;
	if($tipo=='obra'){
				if($this->ccnd02_proyectos_actividad_manoobra->FindCount($conditions1)!=1){
					$ver=$this->ccnd02_proyectos_actividad_manoobra->execute("delete from ccnd02_proyectos_actividad_manoobra where ".$conditions);

					$sql_1="SELECT * from ccnd02_proyectos_actividad_manoobra where ".$conditions1." and 1=1 order by numero_renglon asc";
					$result_1=$this->ccnd02_proyectos_actividad_manoobra->execute($sql_1);
					$k=1;
					for($i=0;$i<count($result_1);$i++){
						$conditions2=$conditions1." and numero_renglon=".$result_1[$i][0]['numero_renglon'];
						$sql = "BEGIN;UPDATE ccnd02_proyectos_actividad_manoobra SET numero_renglon='$k' where ".$conditions2;
						$sw=$this->ccnd02_proyectos_actividad_manoobra->execute($sql);
						$k++;
					}
				}else{
					$this->set('errorMessage', 'NO PODRA ELIMINAR TODA LA MANO DE OBRA');
					$sw=0;
				}
				if($sw>1){
					$sw=$this->ccnd02_proyectos_actividad_manoobra->execute('COMMIT');
				}else{
					$sw=$this->ccnd02_proyectos_actividad_manoobra->execute('ROLLBACK');
				}
	}else if($tipo=='material'){
			if($this->ccnd02_proyectos_actividad_materiales->FindCount($conditions1)!=1){
					$ver=$this->ccnd02_proyectos_actividad_materiales->execute("delete from ccnd02_proyectos_actividad_materiales where ".$conditions);
					$sql_1="SELECT * from ccnd02_proyectos_actividad_materiales where ".$conditions1." and 1=1 order by numero_renglon asc";
					$result_1=$this->ccnd02_proyectos_actividad_materiales->execute($sql_1);
					$k=1;
					for($i=0;$i<count($result_1);$i++){
						$conditions2=$conditions1." and numero_renglon=".$result_1[$i][0]['numero_renglon'];
						$sql = "BEGIN;UPDATE ccnd02_proyectos_actividad_materiales SET numero_renglon='$k' where ".$conditions2;
						$sw=$this->ccnd02_proyectos_actividad_materiales->execute($sql);
						$k++;
					}
				}else{
					$this->set('errorMessage', 'NO PODRA ELIMINAR TODOS LOS MATERIALES');
					$sw=0;
				}
				if($sw>1){
					$sw=$this->ccnd02_proyectos_actividad_materiales->execute('COMMIT');
				}else{
					$sw=$this->ccnd02_proyectos_actividad_materiales->execute('ROLLBACK');
				}

	}else{
				if($this->ccnd02_proyectos_actividad_equipos->FindCount($conditions1)!=1){
					$ver=$this->ccnd02_proyectos_actividad_equipos->execute("delete from ccnd02_proyectos_actividad_equipos where ".$conditions);
					$sql_1="SELECT * from ccnd02_proyectos_actividad_equipos where ".$conditions1." and 1=1 order by numero_renglon asc";
					$result_1=$this->ccnd02_proyectos_actividad_equipos->execute($sql_1);
					$k=1;
					for($i=0;$i<count($result_1);$i++){
						$conditions2=$conditions1." and numero_renglon=".$result_1[$i][0]['numero_renglon'];
						$sql = "BEGIN;UPDATE ccnd02_proyectos_actividad_equipos SET numero_renglon='$k' where ".$conditions2;
						$sw=$this->ccnd02_proyectos_actividad_equipos->execute($sql);
						$k++;
					}
				}else{
					$this->set('errorMessage', 'NO PODRA ELIMINAR TODOS LOS EQUIPOS');
					$sw=0;
				}
				if($sw>1){
					$sw=$this->ccnd02_proyectos_actividad_equipos->execute('COMMIT');
				}else{
					$sw=$this->ccnd02_proyectos_actividad_equipos->execute('ROLLBACK');
				}
	}

	if(isset($pagina)){
		$this->consulta($pagina);
		$this->render('consulta');
	}else{
		$this->seleccion_actividad($cod_actividad);
		$this->render('seleccion_actividad');
	}



}



function eliminar($cod_actividad=null,$pagina=null){
	$this->layout="ajax";
	$cod_republica     = $this->Session->read('CC_republica');
	$cod_estado        = $this->Session->read('CC_estado');
	$cod_municipio     = $this->Session->read('CC_municipio');
	$cod_parroquia     = $this->Session->read('CC_parroquia');
	$cod_centro        = $this->Session->read('CC_centro');
	$cod_concejo       = $this->Session->read('CC_concejo');
	$ano               = $this->Session->read('concejos_comunal_id1');
	$cod_proyecto      = $this->Session->read('concejos_comunal_id2');

	$conditions= "cod_republica=".$cod_republica." and cod_estado=".$cod_estado." and cod_municipio=".$cod_municipio." and cod_parroquia=".$cod_parroquia." and cod_centro=".$cod_centro." and cod_concejo=".$cod_concejo." and ano=".$ano." and cod_proyecto='".$cod_proyecto."' and cod_actividad=".$cod_actividad;
	$sw=$this->ccnd02_proyectos_actividades->execute("BEGIN;delete from ccnd02_proyectos_actividades where ".$conditions);
	if($sw>1){
		$sw1=$this->ccnd02_proyectos_actividad_manoobra->execute("delete from ccnd02_proyectos_actividad_manoobra where ".$conditions);
		if($sw1>1){
			$sw2=$this->ccnd02_proyectos_actividad_materiales->execute("delete from ccnd02_proyectos_actividad_materiales where ".$conditions);
			if($sw2>1){
				$sw3=$this->ccnd02_proyectos_actividad_equipos->execute("delete from ccnd02_proyectos_actividad_equipos where ".$conditions);
				if($sw3>1){
					$this->set('Message_existe', 'los datos fueron eliminados');
					$sw=$this->ccnd02_proyectos_actividad_equipos->execute('COMMIT');
				}else{
					$sw=$this->ccnd02_proyectos_actividad_equipos->execute('ROLLBACK');
					$this->set('errorMessage', 'los datos no pudieron ser eliminados');
				}
			}else{
				$sw=$this->ccnd02_proyectos_actividad_equipos->execute('ROLLBACK');
				$this->set('errorMessage', 'los datos no pudieron ser eliminados');
			}
		}else{
			$sw=$this->ccnd02_proyectos_actividad_equipos->execute('ROLLBACK');
			$this->set('errorMessage', 'los datos no pudieron ser eliminados');
		}
	}else{
		$sw=$this->ccnd02_proyectos_actividad_equipos->execute('ROLLBACK');
		$this->set('errorMessage', 'los datos no pudieron ser eliminados');
	}

	if(isset($pagina)){
		$this->consulta($pagina);
		$this->render('consulta');
	}else{
		$this->index();
		$this->render('index');
	}

}




function agregar_equipo($var=null) {
	$this->layout="ajax";

	$linea=$this->data['ccnp01']['renglon_equipo'];

	if(empty($this->data['ccnp01']['descripcion_equipo']) || empty($this->data['ccnp01']['costo_equipo'])){
		$this->set('errorMessage', 'Debe ingresar los datos requeridos');
		if(!isset($_SESSION["contador1"])){
 			$this->set('vacio','');
 		}
		return;
	}

	    $descripcion=$this->data['ccnp01']['descripcion_equipo'];
	    $costo=$this->Formato1($this->data['ccnp01']['costo_equipo']);
	    $costo=$this->Formato2($costo);


	if(isset($_SESSION["contador1"])){
        $_SESSION["contador1"]=$_SESSION["contador1"]+1;
	}else{
		$_SESSION["contador1"]=1;
	}

	if(isset($var) && !empty($var)){

			$cod[0]=$linea;
			$cod[1]=$descripcion;
			$cod[2]=$costo;

		    if(isset($_SESSION["i1"])){
				$i=$this->Session->read("i1")+1;
				$this->Session->write("i1",$i);
	   		 }else{
			   $this->Session->write("i1",0);
				$i=0;
			}
        switch($var){
        	case 'normal':
				     $vec[$i][0]=$linea;
				     $vec[$i][1]=$descripcion;
				     $vec[$i][2]=$costo;
					 $vec[$i]["id"]=$i;
					if(isset($_SESSION["items1"])){
						foreach($_SESSION["items1"] as $codi){
            	           if($codi[0]==$cod[0]){
                              $est=true;
                              break;
            	          }else{
            	          	 $est=false;
            	          }
                        }//fin foreach
                        if($est==true){
            	          	$i=$this->Session->read("i1")-1;
				            $this->Session->write("i1",$i);
				            $this->set('errorMessage', 'Este equipo ya existe en la lista');
                        }else{
                        	$_SESSION["items1"]=$_SESSION["items1"]+$vec;
                        }
					 }else{
						$_SESSION["items1"]=$vec;
					 }

        	break;

        }//fin switch
		}//

		echo "<script>";
			echo "document.getElementById('agregar1').disabled=false;";
		echo "</script>";
}//fin funcu¡ions



function limpiar_lista_equipo() {
	$this->layout = "ajax";
	echo "<script>document.getElementById('renglon_equipo').value=1;</script>";

	$this->Session->delete("items1");
	$this->Session->delete("i1");
	$this->Session->delete("contador1");
}

function eliminar_items_equipo ($id) {
	$this->layout = "ajax";
	$_SESSION["items1"][$id]=null;
	$NL=1;
	$codigos1=array();
	foreach($_SESSION ["items1"] as $codigos){
       if($codigos[0]!=null){

       		$codigos1[$NL][0]=$NL;
       		$codigos1[$NL][1]=$codigos[1];
       		$codigos1[$NL][2]=$codigos[2];
       		$codigos1[$NL]['id']=$NL;
			$NL++;
       }

	}
	//print_r($codigos1);
//	$this->set('total_partidas_rc',$monto_total);
    $_SESSION["contador1"]=$_SESSION["contador1"]-1;
    $_SESSION["items1"]=array();
    $_SESSION["items1"]=$codigos1;
    //print_r($_SESSION["items1"]);
}




function agregar_material($var=null) {
	$this->layout="ajax";

	$linea=$this->data['ccnp01']['renglon_material'];

	if(empty($this->data['ccnp01']['descripcion_material']) || empty($this->data['ccnp01']['costo_material'])){
		$this->set('errorMessage', 'Debe ingresar los datos requeridos');
		if(!isset($_SESSION["contador2"])){
 			$this->set('vacio','');
 		}
		return;
	}

	    $descripcion=$this->data['ccnp01']['descripcion_material'];
	    $costo=$this->Formato1($this->data['ccnp01']['costo_material']);
	    $costo=$this->Formato2($costo);



	if(isset($_SESSION["contador2"])){
        $_SESSION["contador2"]=$_SESSION["contador2"]+1;
	}else{
		$_SESSION["contador2"]=1;
	}

	if(isset($var) && !empty($var)){

			$cod[0]=$linea;
			$cod[1]=$descripcion;
			$cod[2]=$costo;

		    if(isset($_SESSION["i2"])){
				$i=$this->Session->read("i2")+1;
				$this->Session->write("i2",$i);
	   		 }else{
			   $this->Session->write("i2",0);
				$i=0;
			}
        switch($var){
        	case 'normal':
				     $vec[$i][0]=$linea;
				     $vec[$i][1]=$descripcion;
				     $vec[$i][2]=$costo;
					 $vec[$i]["id"]=$i;
					if(isset($_SESSION["items2"])){
						foreach($_SESSION["items2"] as $codi){
            	           if($codi[0]==$cod[0]){
                              $est=true;
                              break;
            	          }else{
            	          	 $est=false;
            	          }
                        }//fin foreach
                        if($est==true){
            	          	$i=$this->Session->read("i2")-1;
				            $this->Session->write("i2",$i);
				            $this->set('errorMessage', 'Este material ya existe en la lista');
                        }else{
                        	$_SESSION["items2"]=$_SESSION["items2"]+$vec;
                        }
					 }else{
						$_SESSION["items2"]=$vec;
					 }

        	break;

        }//fin switch
		}//

		echo "<script>";
			echo "document.getElementById('agregar2').disabled=false;";
		echo "</script>";

}//fin funcu¡ions



function limpiar_lista_material() {
	$this->layout = "ajax";
	echo "<script>document.getElementById('renglon_material').value=1;</script>";

	$this->Session->delete("items2");
	$this->Session->delete("i2");
	$this->Session->delete("contador2");
}

function eliminar_items_material($id) {
	$this->layout = "ajax";
	$_SESSION["items2"][$id]=null;
	$NL=1;
	$codigos1=array();
	foreach($_SESSION ["items2"] as $codigos){
       if($codigos[0]!=null){

       		$codigos1[$NL][0]=$NL;
       		$codigos1[$NL][1]=$codigos[1];
       		$codigos1[$NL][2]=$codigos[2];
       		$codigos1[$NL]['id']=$NL;
			$NL++;
       }

	}
	//print_r($codigos1);
//	$this->set('total_partidas_rc',$monto_total);
    $_SESSION["contador2"]=$_SESSION["contador2"]-1;
    $_SESSION["items2"]=array();
    $_SESSION["items2"]=$codigos1;
    //print_r($_SESSION["items1"]);
}



function agregar_obra($var=null) {
	$this->layout="ajax";

	$linea=$this->data['ccnp01']['renglon_obra'];

	if(empty($this->data['ccnp01']['descripcion_obra']) || empty($this->data['ccnp01']['costo_obra'])){
		$this->set('errorMessage', 'Debe ingresar los datos requeridos');
		if(!isset($_SESSION["contador3"])){
 			$this->set('vacio','');
 		}
		return;
	}

	    $descripcion=$this->data['ccnp01']['descripcion_obra'];
	    $costo=$this->Formato1($this->data['ccnp01']['costo_obra']);
	    $costo=$this->Formato2($costo);



	if(isset($_SESSION["contador3"])){
        $_SESSION["contador3"]=$_SESSION["contador3"]+1;
	}else{
		$_SESSION["contador3"]=1;
	}

	if(isset($var) && !empty($var)){

			$cod[0]=$linea;
			$cod[1]=$descripcion;
			$cod[2]=$costo;

		    if(isset($_SESSION["i3"])){
				$i=$this->Session->read("i3")+1;
				$this->Session->write("i3",$i);
	   		 }else{
			   $this->Session->write("i3",0);
			   $i=0;
			}
        switch($var){
        	case 'normal':
				     $vec[$i][0]=$linea;
				     $vec[$i][1]=$descripcion;
				     $vec[$i][2]=$costo;
					 $vec[$i]["id"]=$i;
					if(isset($_SESSION["items3"])){
						foreach($_SESSION["items3"] as $codi){
            	           if($codi[0]==$cod[0]){
                              $est=true;
                              break;
            	          }else{
            	          	 $est=false;
            	          }
                        }//fin foreach
                        if($est==true){
            	          	$i=$this->Session->read("i3")-1;
				            $this->Session->write("i3",$i);
				            $this->set('errorMessage', 'Esta mano de obra ya existe en la lista');
                        }else{
                        	$_SESSION["items3"]=$_SESSION["items3"]+$vec;
                        }
					 }else{
						$_SESSION["items3"]=$vec;
					 }

        	break;

        }//fin switch
		}//

		echo "<script>";
			echo "document.getElementById('agregar3').disabled=false;";
		echo "</script>";

}//fin funcu¡ions



function limpiar_lista_obra() {
	$this->layout = "ajax";
	echo "<script>document.getElementById('renglon_obra').value=1;</script>";

	$this->Session->delete("items3");
	$this->Session->delete("i3");
	$this->Session->delete("contador3");
}

function eliminar_items_obra($id) {
	$this->layout = "ajax";
	$_SESSION["items3"][$id]=null;
	$NL=1;
	$codigos1=array();
	foreach($_SESSION ["items3"] as $codigos){
       if($codigos[0]!=null){

       		$codigos1[$NL][0]=$NL;
       		$codigos1[$NL][1]=$codigos[1];
       		$codigos1[$NL][2]=$codigos[2];
       		$codigos1[$NL]['id']=$NL;
			$NL++;
       }

	}
	//print_r($codigos1);
//	$this->set('total_partidas_rc',$monto_total);
    $_SESSION["contador3"]=$_SESSION["contador3"]-1;
    $_SESSION["items3"]=array();
    $_SESSION["items3"]=$codigos1;
    //print_r($_SESSION["items1"]);
}


}//fin class

?>