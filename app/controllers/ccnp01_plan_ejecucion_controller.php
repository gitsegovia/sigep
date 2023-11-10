<?php





 class ccnp01PlanEjecucionController extends AppController {
    var $name    = 'ccnp01_plan_ejecucion';
	var $uses    = array('ccnd02_proyectos','ccnd01_tipo_directivo','ccnd02_proyectos_actividades','ccnd02_proyectos_actividad_ejecucion');
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



function index(){
	$this->layout="ajax";
	$cod_republica     = $this->Session->read('CC_republica');
	$cod_estado        = $this->Session->read('CC_estado');
	$cod_municipio     = $this->Session->read('CC_municipio');
	$cod_parroquia     = $this->Session->read('CC_parroquia');
	$cod_centro        = $this->Session->read('CC_centro');
	$cod_concejo       = $this->Session->read('CC_concejo');
	$ano               = $this->Session->read('concejos_comunal_id1');
	$cod_proyecto      = $this->Session->read('concejos_comunal_id2');


//////////////////////////////////////////////////////////////////////////////////////////////
	/*$conditions  = "cod_republica=".$cod_republica." and cod_estado=".$cod_estado." and cod_municipio=".$cod_municipio." and cod_parroquia=".$cod_parroquia." and cod_centro=".$cod_centro." and cod_concejo=".$cod_concejo." and ano=".$ano." and cod_proyecto='".$cod_proyecto."'";
	$sql="select
			a.cod_actividad
			from ccnd02_proyectos_actividades a
			where ".$conditions." and a.cod_actividad not in(select b.cod_actividad from ccnd02_proyectos_actividad_ejecucion b where ".$conditions." and a.cod_actividad=b.cod_actividad)
			order by a.cod_actividad asc";
	$lista=$this->ccnd02_proyectos_actividades->execute($sql);
	$i=1;
	foreach($lista as $l){
		$r[]=$l[0]["cod_actividad"];
//		$v[]=$l[0]["cod_actividad"];
//		$v[1]=$l[0]["denominacion"];
$i++;
	}
	$lista = array_combine($r, $r);
 	$this->set('actividad',$lista);
*/
 	////////////////////////////////////////////////////////////////////////////////////////////////////////////7


	$conditions  = "cod_republica=".$cod_republica." and cod_estado=".$cod_estado." and cod_municipio=".$cod_municipio." and cod_parroquia=".$cod_parroquia." and cod_centro=".$cod_centro." and cod_concejo=".$cod_concejo." and ano=".$ano." and cod_proyecto='".$cod_proyecto."'";
	$actividad=$this->ccnd02_proyectos_actividades->generateList($conditions,'cod_actividad ASC', null, '{n}.ccnd02_proyectos_actividades.cod_actividad','{n}.ccnd02_proyectos_actividades.denominacion');
	if($actividad!=null){
		$this->concatena_actividad($actividad,'actividad');
	}else{
		$this->set('actividad',array());
	}

	$this->set('numero_actividad',$this->ccnd02_proyectos_actividades->FindCount($conditions));
	$this->set('numero_actividad_registrada',$this->ccnd02_proyectos_actividad_ejecucion->FindCount($conditions));

	$this->data=null;

	if($this->ccnd02_proyectos_actividad_ejecucion->FindCount($conditions)!=0){
		$this->consulta();
		$this->render('consulta');
	}

	$conditions  = "cod_republica=".$cod_republica." and cod_estado=".$cod_estado." and cod_municipio=".$cod_municipio." and cod_parroquia=".$cod_parroquia." and cod_centro=".$cod_centro." and cod_concejo=".$cod_concejo." and ano=".$ano." and cod_proyecto='".$cod_proyecto."'";
	$x=$this->ccnd02_proyectos->execute("select * from ccnd02_proyectos where ".$conditions);
	if($x[0][0]['plan_ejecucion']!='0'){
		$this->set('plan',$x[0][0]['plan_ejecucion']);
		$this->set('readonly','readonly');
	}else{
		$this->set('plan','');
		$this->set('readonly','');
	}




}//index


function guardar($pagina=null){
	$this->layout="ajax";
	$cod_republica     = $this->Session->read('CC_republica');
	$cod_estado        = $this->Session->read('CC_estado');
	$cod_municipio     = $this->Session->read('CC_municipio');
	$cod_parroquia     = $this->Session->read('CC_parroquia');
	$cod_centro        = $this->Session->read('CC_centro');
	$cod_concejo       = $this->Session->read('CC_concejo');
	$ano               = $this->Session->read('concejos_comunal_id1');
	$cod_proyecto      = $this->Session->read('concejos_comunal_id2');

//	pr($this->data);
	if(empty($this->data['ccnp01']['plan_ejecucion'])){
		$this->set('errorMessage', 'ingrese el plan de ejecución');
	}else if(empty($this->data['ccnp01']['actividad'])){
		$this->set('errorMessage', 'seleccione la actividad');
	}else{
		$cod_actividad=$this->data['ccnp01']['actividad'];
		$plan_ejecucion=$this->data['ccnp01']['plan_ejecucion'];

		$filtro  = "cod_republica=".$cod_republica." and cod_estado=".$cod_estado." and cod_municipio=".$cod_municipio." and cod_parroquia=".$cod_parroquia." and cod_centro=".$cod_centro." and cod_concejo=".$cod_concejo." and ano=".$ano." and cod_proyecto='".$cod_proyecto."' and cod_actividad=".$cod_actividad;
		$filtro2 = "cod_republica=".$cod_republica." and cod_estado=".$cod_estado." and cod_municipio=".$cod_municipio." and cod_parroquia=".$cod_parroquia." and cod_centro=".$cod_centro." and cod_concejo=".$cod_concejo." and ano=".$ano." and cod_proyecto='".$cod_proyecto."'";

				$bandera=0;
				$campo_semanas ="";
				for($i=1;$i<53;$i++){
					if($this->data['concejo']['semana_'.$i]!=0){
						$bandera=$i;
					}
				$campo_semanas .=$this->data['concejo']['semana_'.$i];
				}

				if($bandera!=0){
					$sql_insert1 = "update ccnd02_proyectos set plan_ejecucion='$plan_ejecucion' where ".$filtro2;
					$this->ccnd02_proyectos->execute($sql_insert1);
					if($this->ccnd02_proyectos_actividad_ejecucion->FindCount($filtro)==0){
						$sql_insert = "INSERT INTO ccnd02_proyectos_actividad_ejecucion VALUES('$cod_republica','$cod_estado','$cod_municipio','$cod_parroquia','$cod_centro','$cod_concejo','$ano','$cod_proyecto','$cod_actividad','$campo_semanas')";
						$sw = $this->ccnd02_proyectos_actividad_ejecucion->execute($sql_insert);
						if($sw>1){
							$this->set('Message_existe', 'registro exitoso');
							if(isset($pagina)){
								echo" <script> ver_documento('/ccnp01_plan_ejecucion/consulta/$pagina','tab_pestana_descripcion_proyecto_4'); </script>";
							}else{
								echo" <script> ver_documento('/ccnp01_plan_ejecucion/datos/$cod_actividad','carga'); </script>";
							}
						}else{
							$this->set('errorMessage', 'los datos no pudieron ser registrados');
						}
					}else{
							$campo_semanas ="";
							for($i=1;$i<53;$i++){
								if($this->data['concejo']['semana_'.$i]!='ON'){
									$campo_semanas .=0;
								}else{
									$campo_semanas .=1;
								}

							}
						$sql_insert = "update ccnd02_proyectos_actividad_ejecucion set semanas='$campo_semanas' where ".$filtro;
						$sw = $this->ccnd02_proyectos_actividad_ejecucion->execute($sql_insert);
						if($sw>1){
							$this->set('Message_existe', 'el registro se ha modificado con exito');
							if(isset($pagina)){
								echo" <script> ver_documento('/ccnp01_plan_ejecucion/consulta/$pagina','tab_pestana_descripcion_proyecto_4'); </script>";
							}else{
								echo" <script> ver_documento('/ccnp01_plan_ejecucion/datos/$cod_actividad','carga'); </script>";
							}
						}else{
							$this->set('errorMessage', 'los datos no pudieron ser modificados');
						}
					}

				}else{
					$this->set('errorMessage', 'debe elegir alguna semana');
				}

	}

}





function datos($var=null){
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
	//////////////////////////////////////////////////////////////////////////////////////////////
	/*$conditions  = "cod_republica=".$cod_republica." and cod_estado=".$cod_estado." and cod_municipio=".$cod_municipio." and cod_parroquia=".$cod_parroquia." and cod_centro=".$cod_centro." and cod_concejo=".$cod_concejo." and ano=".$ano." and cod_proyecto='".$cod_proyecto."'";
	$sql="select
			a.cod_actividad
			from ccnd02_proyectos_actividades a
			where ".$conditions." and a.cod_actividad not in(select b.cod_actividad from ccnd02_proyectos_actividad_ejecucion b where ".$conditions." and a.cod_actividad=b.cod_actividad)
			order by a.cod_actividad asc";
	$lista=$this->ccnd02_proyectos_actividades->execute($sql);
	$i=1;
	foreach($lista as $l){
		$r[]=$l[0]["cod_actividad"];
//		$v[]=$l[0]["cod_actividad"];
//		$v[1]=$l[0]["denominacion"];
$i++;
	}
	$lista = array_combine($r, $r);
 	$this->set('actividad',$lista);
*/
 	////////////////////////////////////////////////////////////////////////////////////////////////////////////7

	$conditions  = "cod_republica=".$cod_republica." and cod_estado=".$cod_estado." and cod_municipio=".$cod_municipio." and cod_parroquia=".$cod_parroquia." and cod_centro=".$cod_centro." and cod_concejo=".$cod_concejo." and ano=".$ano." and cod_proyecto='".$cod_proyecto."'";
	$actividad=$this->ccnd02_proyectos_actividades->generateList($conditions,'cod_actividad ASC', null, '{n}.ccnd02_proyectos_actividades.cod_actividad','{n}.ccnd02_proyectos_actividades.denominacion');
	if($actividad!=null){
		$this->concatena_actividad($actividad,'actividad');
	}else{
		$this->set('actividad',array());
	}


	$filtro  = "cod_republica=".$cod_republica." and cod_estado=".$cod_estado." and cod_municipio=".$cod_municipio." and cod_parroquia=".$cod_parroquia." and cod_centro=".$cod_centro." and cod_concejo=".$cod_concejo." and ano=".$ano." and cod_proyecto='".$cod_proyecto."' and cod_actividad=".$var;
	$filtro2= "cod_republica=".$cod_republica." and cod_estado=".$cod_estado." and cod_municipio=".$cod_municipio." and cod_parroquia=".$cod_parroquia." and cod_centro=".$cod_centro." and cod_concejo=".$cod_concejo." and ano=".$ano." and cod_proyecto='".$cod_proyecto."'";

	$equipo=$this->ccnd02_proyectos_actividad_ejecucion->execute("select * from ccnd02_proyectos_actividad_ejecucion where ".$filtro);
	if($equipo!=null){
		$this->set('equipo',$equipo);
		$cadena=chunk_split($equipo[0][0]['semanas'],1,',');
		$vector=explode(',',$cadena);
		$this->set('vector',$vector);

		$elimina=$this->ccnd02_proyectos_actividad_ejecucion->execute("select * from ccnd02_proyectos_actividad_ejecucion where ".$filtro2." order by cod_actividad desc");
		if($elimina[0][0]['cod_actividad']==$var){
			$this->set('elimina','elimina');
		}


	}else{
		/*$elimina=$this->ccnd02_proyectos_actividad_ejecucion->execute("select * from ccnd02_proyectos_actividad_ejecucion where ".$filtro2." order by cod_actividad desc");
		if($elimina!=null)$p=$elimina[0][0]['cod_actividad']; else $p=0;
		$x=$p+1;
		if($x==$var){
			$this->set('guardar','guardar');
		}else{
			$this->set('errorMessage', 'debe registrar el plan de ejecución siguiendo la secuencia de las actividades, corresponde la actividad '.$this->zero($x));

		}*/
		$this->set('guardar','guardar');
		$this->set('equipo',null);
	}


	$datos=$this->ccnd02_proyectos_actividades->execute("select * from ccnd02_proyectos_actividades where ".$filtro);
	$this->set('datos',$datos);

	$this->set('cod_actividad',$var);

	$this->set('numero_actividad',$this->ccnd02_proyectos_actividades->FindCount($filtro2));
	$this->set('numero_actividad_registrada',$this->ccnd02_proyectos_actividad_ejecucion->FindCount($filtro2));



}else{
	$this->index();
	$this->render('index');
}

$conditions  = "cod_republica=".$cod_republica." and cod_estado=".$cod_estado." and cod_municipio=".$cod_municipio." and cod_parroquia=".$cod_parroquia." and cod_centro=".$cod_centro." and cod_concejo=".$cod_concejo." and ano=".$ano." and cod_proyecto='".$cod_proyecto."'";
$x=$this->ccnd02_proyectos->execute("select * from ccnd02_proyectos where ".$conditions);
if($x[0][0]['plan_ejecucion']!='0'){
	$this->set('plan',$x[0][0]['plan_ejecucion']);
	$this->set('readonly','readonly');
}else{
	$this->set('plan','');
	$this->set('readonly','');
}


}



function eliminar($var=null,$pagina=null){
	$this->layout="ajax";
	$cod_republica     = $this->Session->read('CC_republica');
	$cod_estado        = $this->Session->read('CC_estado');
	$cod_municipio     = $this->Session->read('CC_municipio');
	$cod_parroquia     = $this->Session->read('CC_parroquia');
	$cod_centro        = $this->Session->read('CC_centro');
	$cod_concejo       = $this->Session->read('CC_concejo');
	$ano               = $this->Session->read('concejos_comunal_id1');
	$cod_proyecto      = $this->Session->read('concejos_comunal_id2');

	$filtro  = "cod_republica=".$cod_republica." and cod_estado=".$cod_estado." and cod_municipio=".$cod_municipio." and cod_parroquia=".$cod_parroquia." and cod_centro=".$cod_centro." and cod_concejo=".$cod_concejo." and ano=".$ano." and cod_proyecto='".$cod_proyecto."' and cod_actividad=".$var;
	$filtro2  = "cod_republica=".$cod_republica." and cod_estado=".$cod_estado." and cod_municipio=".$cod_municipio." and cod_parroquia=".$cod_parroquia." and cod_centro=".$cod_centro." and cod_concejo=".$cod_concejo." and ano=".$ano." and cod_proyecto='".$cod_proyecto."'";

	$sw=$this->ccnd02_proyectos_actividad_ejecucion->execute("delete from ccnd02_proyectos_actividad_ejecucion where ".$filtro);
	if($this->ccnd02_proyectos_actividad_ejecucion->FindCount($filtro2)==0){
		$sql_insert = "update ccnd02_proyectos set plan_ejecucion='0' where ".$filtro2;
		$sw = $this->ccnd02_proyectos_actividad_ejecucion->execute($sql_insert);
	}
	if($sw>1){
		$this->set('Message_existe', 'el dato fue eliminado con exito');
		if(isset($pagina)){
			echo" <script> ver_documento('/ccnp01_plan_ejecucion/consulta/$pagina','tab_pestana_descripcion_proyecto_4'); </script>";
		}else{
			$this->index();
			$this->render('index');
		}
	}else{
		$this->set('errorMessage', 'el dato no pudo ser eliminado');
		if(isset($pagina)){
			echo" <script> ver_documento('/ccnp01_plan_ejecucion/consulta/$pagina','tab_pestana_descripcion_proyecto_4'); </script>";
		}else{
			echo" <script> ver_documento('/ccnp01_plan_ejecucion/datos/$var','carga'); </script>";
		}

	}

}



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

	if(isset($pagina)){
		$Tfilas=$this->ccnd02_proyectos_actividad_ejecucion->findCount($conditions);
        if($Tfilas!=0){
        	$x=$this->ccnd02_proyectos_actividad_ejecucion->findAll($conditions,null,"cod_actividad ASC",1,$pagina,null);

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
		$Tfilas=$this->ccnd02_proyectos_actividad_ejecucion->findCount($conditions);

        if($Tfilas!=0){
        	$x=$this->ccnd02_proyectos_actividad_ejecucion->findAll($conditions,null,"cod_actividad ASC",1,$pagina,null);
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

	$this->set('perso',$x);

	//////////////////////////////////////////////////////////////////////////////////////////////
/*	$conditions  = "cod_republica=".$cod_republica." and cod_estado=".$cod_estado." and cod_municipio=".$cod_municipio." and cod_parroquia=".$cod_parroquia." and cod_centro=".$cod_centro." and cod_concejo=".$cod_concejo." and ano=".$ano." and cod_proyecto='".$cod_proyecto."'";
	$sql="select
			a.cod_actividad
			from ccnd02_proyectos_actividades a
			where ".$conditions." and a.cod_actividad not in(select b.cod_actividad from ccnd02_proyectos_actividad_ejecucion b where ".$conditions." and a.cod_actividad=b.cod_actividad)
			order by a.cod_actividad asc";
	$lista=$this->ccnd02_proyectos_actividades->execute($sql);
	$i=1;
	foreach($lista as $l){
		$r[]=$l[0]["cod_actividad"];
//		$v[]=$l[0]["cod_actividad"];
//		$v[1]=$l[0]["denominacion"];
$i++;
	}
	$lista = array_combine($r, $r);
 	$this->set('actividad',$lista);
*/
 	////////////////////////////////////////////////////////////////////////////////////////////////////////////7

	$conditions  = "cod_republica=".$cod_republica." and cod_estado=".$cod_estado." and cod_municipio=".$cod_municipio." and cod_parroquia=".$cod_parroquia." and cod_centro=".$cod_centro." and cod_concejo=".$cod_concejo." and ano=".$ano." and cod_proyecto='".$cod_proyecto."'";
	$actividad=$this->ccnd02_proyectos_actividades->generateList($conditions,'cod_actividad ASC', null, '{n}.ccnd02_proyectos_actividades.cod_actividad','{n}.ccnd02_proyectos_actividades.denominacion');
	if($actividad!=null){
		$this->concatena_actividad($actividad,'actividad');
	}else{
		$this->set('actividad',array());
	}


	$filtro  = "cod_republica=".$cod_republica." and cod_estado=".$cod_estado." and cod_municipio=".$cod_municipio." and cod_parroquia=".$cod_parroquia." and cod_centro=".$cod_centro." and cod_concejo=".$cod_concejo." and ano=".$ano." and cod_proyecto='".$cod_proyecto."' and cod_actividad=".$x[0]["ccnd02_proyectos_actividad_ejecucion"]["cod_actividad"];
	$filtro2= "cod_republica=".$cod_republica." and cod_estado=".$cod_estado." and cod_municipio=".$cod_municipio." and cod_parroquia=".$cod_parroquia." and cod_centro=".$cod_centro." and cod_concejo=".$cod_concejo." and ano=".$ano." and cod_proyecto='".$cod_proyecto."'";

	$equipo=$this->ccnd02_proyectos_actividad_ejecucion->execute("select * from ccnd02_proyectos_actividad_ejecucion where ".$filtro);
	$this->set('equipo',$equipo);
	$cadena=chunk_split($equipo[0][0]['semanas'],1,',');
	$vector=explode(',',$cadena);
	$this->set('vector',$vector);

	$elimina=$this->ccnd02_proyectos_actividad_ejecucion->execute("select * from ccnd02_proyectos_actividad_ejecucion where ".$filtro2." order by cod_actividad desc");


	$datos=$this->ccnd02_proyectos_actividades->execute("select * from ccnd02_proyectos_actividades where ".$filtro);
	$this->set('datos',$datos);

	$this->set('cod_actividad',$x[0]["ccnd02_proyectos_actividad_ejecucion"]["cod_actividad"]);

	$this->set('numero_actividad',$this->ccnd02_proyectos_actividades->FindCount($filtro2));
	$this->set('numero_actividad_registrada',$this->ccnd02_proyectos_actividad_ejecucion->FindCount($filtro2));

	$conditions  = "cod_republica=".$cod_republica." and cod_estado=".$cod_estado." and cod_municipio=".$cod_municipio." and cod_parroquia=".$cod_parroquia." and cod_centro=".$cod_centro." and cod_concejo=".$cod_concejo." and ano=".$ano." and cod_proyecto='".$cod_proyecto."'";
	$x=$this->ccnd02_proyectos->execute("select * from ccnd02_proyectos where ".$conditions);
	if($x[0][0]['plan_ejecucion']!='0'){
		$this->set('plan',$x[0][0]['plan_ejecucion']);
		$this->set('readonly','');
	}else{
		$this->set('plan','');
		$this->set('readonly','');
	}




 }//consultar





}//fin function

?>