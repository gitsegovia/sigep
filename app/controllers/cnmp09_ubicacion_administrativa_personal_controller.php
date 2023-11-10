<?php
/*
 * Fecha: 19/07/2007
 *
 * Por Erisk G. Aragol H.
 *
 * Herramienta utilizada: easyEclipse
 * sisap
 */
class Cnmp09UbicacionAdministrativaPersonalController extends AppController {
   var $name = 'cnmp09_ubicacion_administrativa_personal';
   var $uses = array('Cnmd01','cnmd09_ubicacion_direccion_personal','cugd02_direccionsuperior','cugd02_coordinacion','cugd02_secretaria','cugd02_direccion');
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

function index($cod=null){
	$this->layout="ajax";
	if(isset($cod)){
		echo $cod."   ";
	}
	$lista = $this->Cnmd01->generateList($conditions = $this->condicion(), $order = 'cod_tipo_nomina', $limit = null, '{n}.Cnmd01.cod_tipo_nomina', '{n}.Cnmd01.denominacion');
	$this->concatenaN($lista, 'nomina');

	echo "<script>";
		echo "document.getElementById('eliminar_asignacion').disabled='disabled';";
	echo "</script>";

	$this->data=null;

	$datos=$this->cnmd09_ubicacion_direccion_personal->findAll($this->SQLCA(),null,'ORDER BY cod_tipo_nomina ASC');
	 if($datos!=null){
	 	 $this->set('opciones',$datos);
	 }else{
	 	 $this->set('opciones',null);
	 }
	 $deno_trans= $this->Cnmd01->findAll($this->SQLCA(), $fields ='cod_tipo_nomina, denominacion', $order = null, $limit = null, $page = null, $recursive = null);
	 $this->set('deno_trans', $deno_trans);
}


function select_transferir($nomina=null){
	$this->layout="ajax";
	$cod_presi                =       $this->Session->read('SScodpresi');
	$cod_entidad              =       $this->Session->read('SScodentidad');
	$cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
	$cod_inst                 =       $this->Session->read('SScodinst');
	$cod_dep                  =       $this->Session->read('SScoddep');


	$ver=$this->cnmd09_ubicacion_direccion_personal->execute("select * from cnmd09_ubicacion_direccion_personal where ".$this->SQLCA()." and cod_tipo_nomina='$nomina'");
 	if($ver==null){
		$this->set('nomina',array());
 	}else{
		$sql="select
		a.cod_presi,
		a.cod_entidad,
		a.cod_tipo_inst,
		a.cod_inst,
		a.cod_dep,
		a.cod_tipo_nomina,
		a.denominacion
		from cnmd01 a where
		a.cod_presi='$cod_presi' and a.cod_entidad='$cod_entidad' and a.cod_tipo_inst='$cod_tipo_inst' and a.cod_inst='$cod_inst' and a.cod_dep='$cod_dep' and a.cod_tipo_nomina NOT IN (select b.cod_tipo_nomina from cnmd09_ubicacion_direccion_personal b where b.cod_presi=a.cod_presi and b.cod_entidad=a.cod_entidad and b.cod_tipo_inst=a.cod_tipo_inst and b.cod_inst=a.cod_inst and b.cod_dep=a.cod_dep and b.cod_tipo_nomina=a.cod_tipo_nomina)
		order by
		a.cod_tipo_nomina
		asc";

		$lista=$this->cnmd09_ubicacion_direccion_personal->execute($sql);
		foreach($lista as $l){
				$r[]=$l[0]["cod_tipo_nomina"];
				$v[]=$l[0]["denominacion"];
		}
		$lista = array_combine($r, $v);

		$this->concatenaN($lista,'nomina');
 	}

}



function mostrar1($opcion=null,$var=null,$ver=null){
	$this->layout="ajax";
	$cod_presi                =       $this->Session->read('SScodpresi');
	$cod_entidad              =       $this->Session->read('SScodentidad');
	$cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
	$cod_inst                 =       $this->Session->read('SScodinst');
	$cod_dep                  =       $this->Session->read('SScoddep');
	$cond="cod_tipo_institucion=".$cod_tipo_inst." and cod_institucion=".$cod_inst." and cod_dependencia=".$cod_dep;
	if($var!=''){
		switch($opcion){
			case 'deno_nomina':
				if(isset($ver)){
					$deno_nomina = $this->Cnmd01->field('denominacion', $conditions = $this->condicion()." and Cnmd01.cod_tipo_nomina='$ver'", $order ="cod_tipo_nomina ASC");
					echo "<script>";
						echo "document.getElementById('save_transferir').disabled=false;";
					echo "</script>";
				}else{
					$deno_nomina = $this->Cnmd01->field('denominacion', $conditions = $this->condicion()." and Cnmd01.cod_tipo_nomina='$var'", $order ="cod_tipo_nomina ASC");
				}
				$this->set('denomi', $deno_nomina);
				$this->set('denominacion',$opcion);
				echo "<script>";
					echo "document.getElementById('deno_transferencia').value='';";
					echo "document.getElementById('deno_superior1').value='';";
					echo "document.getElementById('deno_coordinacion1').value='';";
					echo "document.getElementById('deno_secretaria1').value='';";
					echo "document.getElementById('deno_direccion1').value='';";
				echo "</script>";

			break;
			case 'deno_superior':
				$cond.=" and cod_dir_superior=".$var;
				$deno_parroquia = $this->cugd02_direccionsuperior->field('denominacion', $conditions = $cond, $order ="cod_dir_superior ASC");
				$this->set('denomi', $deno_parroquia);
				$this->set('denominacion',$opcion);
				echo "<script>";
					echo "document.getElementById('deno_coordinacion1').value='';";
					echo "document.getElementById('deno_secretaria1').value='';";
					echo "document.getElementById('deno_direccion1').value='';";
				echo "</script>";
			break;
			case 'deno_coordinacion':
				$dir_sup=$this->Session->read('dir_sup');
				$cond.=" and cod_dir_superior=".$dir_sup." and cod_coordinacion=".$var;
				$deno_coor = $this->cugd02_coordinacion->field('denominacion', $cond, $order ="cod_coordinacion ASC");
				$this->set('denomi', $deno_coor);
				$this->set('denominacion',$opcion);
				echo "<script>";
					echo "document.getElementById('deno_secretaria1').value='';";
					echo "document.getElementById('deno_direccion1').value='';";
				echo "</script>";
			break;
			case 'deno_secretaria':
				$dir_sup=$this->Session->read('dir_sup');
				$coor=$this->Session->read('coor');
				$cond.=" and cod_dir_superior=".$dir_sup." and cod_coordinacion=".$coor." and cod_secretaria=".$var;
				$deno_secre = $this->cugd02_secretaria->field('denominacion', $cond, $order ="cod_secretaria ASC");
				$this->set('denomi', $deno_secre);
				$this->set('denominacion',$opcion);
				echo "<script>";
					echo "document.getElementById('deno_direccion1').value='';";
				echo "</script>";
			break;
			case 'deno_direccion':
				$dir_sup=$this->Session->read('dir_sup');
				$coor=$this->Session->read('coor');
				$secre=$this->Session->read('secre');
				$cond.=" and cod_dir_superior=".$dir_sup." and cod_coordinacion=".$coor." and cod_secretaria=".$secre." and cod_direccion=".$var;
				$deno_direc = $this->cugd02_direccion->field('denominacion', $cond, $order ="cod_direccion ASC");
				$this->set('denomi', $deno_direc);
				$this->set('denominacion',$opcion);
			break;
		}// fin switch
	}else{
		$this->set('si','no');
	}
}// fin mostrar


function concatena4($vector1=null, $nomVar=null, $extra=null){
	$cod = array();
	if($vector1 != null){
		foreach($vector1 as $x => $y){
			if($x<10){
				$cod[$x] = '000'.$x.' - '.$y;
			}else if($x>=10 && $x<=99){
				$cod[$x] = '00'.$x.' - '.$y;
			}else if($x>=100 && $x<=999){
				$cod[$x] = '0'.$x.' - '.$y;
			}else{
				$cod[$x] = $x.' - '.$y;
			}
		}
		//print_r($cod);
	}

	$this->set($nomVar, $cod);
}//fin function



function select2($opcion=null,$var=null){
	$this->layout="ajax";
	$cod_presi                =       $this->Session->read('SScodpresi');
	$cod_entidad              =       $this->Session->read('SScodentidad');
	$cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
	$cod_inst                 =       $this->Session->read('SScodinst');
	$cod_dep                  =       $this->Session->read('SScoddep');
	$cond="cod_tipo_institucion=".$cod_tipo_inst." and cod_institucion=".$cod_inst." and cod_dependencia=".$cod_dep;
	if($var!=''){
		switch($opcion){
			case 'superior':
				$this->set('no','');
				$this->set('SELECT','coordinacion');
				$this->set('codigo','superior');
				$this->set('seleccion','');
				$this->set('n',2);
				$lista=  $this->cugd02_direccionsuperior->generateList($cond, 'cod_dir_superior ASC', null, '{n}.cugd02_direccionsuperior.cod_dir_superior', '{n}.cugd02_direccionsuperior.denominacion');
				$this->concatena($lista, 'vector');
				$this->set('anula','otros');
			break;
			case 'coordinacion':
				$this->set('no','');
				$this->set('SELECT','secretaria');
				$this->set('codigo','coordinacion');
				$this->set('seleccion','');
				$this->set('n',3);
				$this->Session->write('dir_sup',$var);
				$cond.=" and cod_dir_superior=".$var;
				$lista=  $this->cugd02_coordinacion->generateList($cond, 'cod_coordinacion ASC', null, '{n}.cugd02_coordinacion.cod_coordinacion', '{n}.cugd02_coordinacion.denominacion');
				$this->concatena($lista, 'vector');
				$this->set('anula','otros');
			break;
			case 'secretaria':
				$this->set('anula','otros');
				$this->set('no','');
				$this->set('SELECT','direccion');
				$this->set('codigo','secretaria');
				$this->set('seleccion','');
				$this->set('n',4);
				$this->Session->write('coor',$var);
				$dir_sur=$this->Session->read('dir_sup');
				$cond.=" and cod_dir_superior=".$dir_sur." and cod_coordinacion=".$var;
				$lista=  $this->cugd02_secretaria->generateList($cond, 'cod_secretaria ASC', null, '{n}.cugd02_secretaria.cod_secretaria', '{n}.cugd02_secretaria.denominacion');
				$this->concatena($lista, 'vector');
			break;
			case 'direccion':
				$this->set('no','no');
				$this->set('SELECT','division');
				$this->set('codigo','direccion');
				$this->set('seleccion','');
				$this->set('n',5);
				$this->Session->write('secre',$var);
				$dir_sur=$this->Session->read('dir_sup');
				$coor=$this->Session->read('coor');
				$cond.=" and cod_dir_superior=".$dir_sur." and cod_coordinacion=".$coor." and cod_secretaria=".$var;
				$lista=  $this->cugd02_direccion->generateList($cond, 'cod_direccion ASC', null, '{n}.cugd02_direccion.cod_direccion', '{n}.cugd02_direccion.denominacion');
				$this->concatena($lista, 'vector');
				$this->set('anula','otros');
			break;
		}//fin switch
	}


}//fin select3



function guardar(){
	$this->layout="ajax";
	$cod_presi = $this->Session->read('SScodpresi');
    $cod_entidad = $this->Session->read('SScodentidad');
    $cod_tipo_inst = $this->Session->read('SScodtipoinst');
    $cod_inst = $this->Session->read('SScodinst');
    $cod_dep = $this->Session->read('SScoddep');

    if(empty($this->data['cnmp09']['cod_nomina'])){
    	$this->set('errorMessage','seleccione el código de nómina');
    }else if(empty($this->data['cnmp09']['cod_superior'])){
    	$this->set('errorMessage','seleccione la dirección superior');
    }else if(empty($this->data['cnmp09']['cod_coordinacion'])){
    	$this->set('errorMessage','seleccione la coordinación');
    }else if(empty($this->data['cnmp09']['cod_secretaria'])){
    	$this->set('errorMessage','seleccione la secretaria');
    }else if(empty($this->data['cnmp09']['cod_direccion'])){
    	$this->set('errorMessage','seleccione la dirección');
    }else{

    	$nomina=$this->data['cnmp09']['cod_nomina'];
		$cod_superior=$this->data['cnmp09']['cod_superior'];
		$cod_coordinacion=$this->data['cnmp09']['cod_coordinacion'];
		$cod_secretaria=$this->data['cnmp09']['cod_secretaria'];
		$cod_direccion=$this->data['cnmp09']['cod_direccion'];
		$verifica=$this->cnmd09_ubicacion_direccion_personal->FindCount($this->SQLCA()." and cod_tipo_nomina='$nomina'");
		if($verifica==0){
			$sql_insert = "BEGIN;INSERT INTO cnmd09_ubicacion_direccion_personal VALUES('$cod_presi', '$cod_entidad', '$cod_tipo_inst', '$cod_inst', '$cod_dep','$nomina','$cod_superior','$cod_coordinacion','$cod_secretaria','$cod_direccion')";
			$sw1 = $this->cnmd09_ubicacion_direccion_personal->execute($sql_insert);
			if($sw1>1){
				$this->cnmd09_ubicacion_direccion_personal->execute("COMMIT");
				$this->set('Message_existe','registro exitoso');
				echo" <script> ver_documento('/cnmp09_ubicacion_administrativa_personal/index/','principal'); </script>";
			}else{
				$this->cnmd09_ubicacion_direccion_personal->execute("ROLLBACK");
				$this->set('errorMessage','los datos no pudieron ser registrados');
			}
		}else{
			$this->set('errorMessage','esta nómina ya tiene una ubicación administrativa registrada');
		}

    }

}//fin guardar


function eliminar($cod_nomina=null,$anterior=null){
 		$this->layout="ajax";
		$sql = "DELETE FROM cnmd09_ubicacion_direccion_personal WHERE ".$this->SQLCA()." and cod_tipo_nomina=".$cod_nomina;
		if($this->cnmd09_ubicacion_direccion_personal->execute($sql)>1){
			$this->set('Message_existe', 'EL REGISTRO FUE ELIMINADO EXITOSAMENTE');
			$Tfilas=$this->cnmd09_ubicacion_direccion_personal->findCount($this->SQLCA());
				if($Tfilas!=0){
					$this->consulta($anterior);
					$this->render("consulta");
				}else{
					$this->index();
					$this->render("index");
				}/////HASTA AQUI
		}else{
			$this->set('errorMessage', 'EL REGISTRO NO PUDO SER ELIMINADO');
			$this->consulta($anterior);
			$this->render("consulta");
		}

 }//eliminar



function consulta($pagina=null) {
	$this->layout="ajax";
	$cod_presi                =       $this->Session->read('SScodpresi');
	$cod_entidad              =       $this->Session->read('SScodentidad');
	$cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
	$cod_inst                 =       $this->Session->read('SScodinst');
	$cod_dep                  =       $this->Session->read('SScoddep');
	$cond="cod_tipo_institucion=".$cod_tipo_inst." and cod_institucion=".$cod_inst." and cod_dependencia=".$cod_dep;

	if(isset($pagina)){
		$Tfilas=$this->cnmd09_ubicacion_direccion_personal->findCount($this->SQLCA());
        if($Tfilas!=0){
        	$data=$this->cnmd09_ubicacion_direccion_personal->findAll($this->SQLCA(),null,"cod_tipo_nomina ASC",1,$pagina,null);

            $this->set('DATA',$data);
          	$this->set('siguiente',$pagina+1);
          	$this->set('anterior',$pagina-1);
            $this->bt_nav($Tfilas,$pagina);
            $this->set('numT',$Tfilas);
			$this->set('numP',$pagina);
			$this->set('pagina',$pagina);
        }else{
	 	    $this->set('errorMessage', 'No se encontrar&oacute;n datos');
	 	    $this->set('noExiste',true);
        }

	}else{
		$pagina=1;
		$Tfilas=$this->cnmd09_ubicacion_direccion_personal->findCount($this->SQLCA());

        if($Tfilas!=0){
        	$data=$this->cnmd09_ubicacion_direccion_personal->findAll($this->SQLCA(),null,"cod_tipo_nomina ASC",1,$pagina,null);
			$this->set('DATA',$data);
          	$this->set('siguiente',$pagina+1);
          	$this->set('anterior',$pagina-1);
          	$this->bt_nav($Tfilas,$pagina);
          	$this->set('numT',$Tfilas);
			$this->set('numP',$pagina);
			$this->set('pagina',$pagina);

        }else{
	 	       $this->set('errorMessage', 'No se encontrar&oacute;n datos');
	 	       $this->set('noExiste',true);
	 	       $this->data['cnmp09']['cod_nomina']=null;
				$this->data['cnmp09']['bancario']=null;
				$this->data['cnmp09']['beneficiario']=null;
				$this->data['cnmp09']['deno_nomina']=null;
				$this->data['cnmp09']['deno_banco']=null;
				$this->data['cnmp09']['deno_sucursal']=null;
	 	       $this->index();
			   $this->render("index");
			   return;
        }
	}

	$this->set('cod_nomina', $data[0]['cnmd09_ubicacion_direccion_personal']['cod_tipo_nomina']);
	$this->set('cod_dir_superior', $data[0]['cnmd09_ubicacion_direccion_personal']['cod_dir_superior']);
	$this->set('cod_coordinacion', $data[0]['cnmd09_ubicacion_direccion_personal']['cod_coordinacion']);
	$this->set('cod_secretaria', $data[0]['cnmd09_ubicacion_direccion_personal']['cod_secretaria']);
	$this->set('cod_direccion', $data[0]['cnmd09_ubicacion_direccion_personal']['cod_direccion']);

	$deno_nomina = $this->Cnmd01->field('denominacion', $conditions = $this->condicion()." and Cnmd01.cod_tipo_nomina=".$data[0]['cnmd09_ubicacion_direccion_personal']['cod_tipo_nomina']);
	$this->set('deno_nomina', $deno_nomina);

	$cond.=" and cod_dir_superior=".$data[0]['cnmd09_ubicacion_direccion_personal']['cod_dir_superior'];
	$deno_superior= $this->cugd02_direccionsuperior->field('denominacion', $conditions = $cond, $order ="cod_dir_superior ASC");
	$cond.=" and cod_dir_superior=".$data[0]['cnmd09_ubicacion_direccion_personal']['cod_dir_superior']." and cod_coordinacion=".$data[0]['cnmd09_ubicacion_direccion_personal']['cod_coordinacion'];
	$deno_coor = $this->cugd02_coordinacion->field('denominacion', $cond, $order ="cod_coordinacion ASC");
	$cond.=" and cod_dir_superior=".$data[0]['cnmd09_ubicacion_direccion_personal']['cod_dir_superior']." and cod_coordinacion=".$data[0]['cnmd09_ubicacion_direccion_personal']['cod_coordinacion']." and cod_secretaria=".$data[0]['cnmd09_ubicacion_direccion_personal']['cod_secretaria'];
	$deno_secre = $this->cugd02_secretaria->field('denominacion', $cond, $order ="cod_secretaria ASC");
	$cond.=" and cod_dir_superior=".$data[0]['cnmd09_ubicacion_direccion_personal']['cod_dir_superior']." and cod_coordinacion=".$data[0]['cnmd09_ubicacion_direccion_personal']['cod_coordinacion']." and cod_secretaria=".$data[0]['cnmd09_ubicacion_direccion_personal']['cod_secretaria']." and cod_direccion=".$data[0]['cnmd09_ubicacion_direccion_personal']['cod_direccion'];
	$deno_direc = $this->cugd02_direccion->field('denominacion', $cond, $order ="cod_direccion ASC");

	$this->set('deno_superior', $deno_superior);
	$this->set('deno_coordinacion', $deno_coor);
	$this->set('deno_secretaria', $deno_secre);
	$this->set('deno_direccion', $deno_direc);

////////////////////////////////////////////////////////////////////////////////////
	$nomina=$data[0]['cnmd09_ubicacion_direccion_personal']['cod_tipo_nomina'];
	$ver=$this->cnmd09_ubicacion_direccion_personal->execute("select * from cnmd09_ubicacion_direccion_personal where ".$this->SQLCA()." and cod_tipo_nomina='$nomina'");
 	if($ver==null){
		$this->set('nomina',array());
 	}else{
		$sql="select
		a.cod_presi,
		a.cod_entidad,
		a.cod_tipo_inst,
		a.cod_inst,
		a.cod_dep,
		a.cod_tipo_nomina,
		a.denominacion
		from cnmd01 a where
		a.cod_presi='$cod_presi' and a.cod_entidad='$cod_entidad' and a.cod_tipo_inst='$cod_tipo_inst' and a.cod_inst='$cod_inst' and a.cod_dep='$cod_dep' and a.cod_tipo_nomina NOT IN (select b.cod_tipo_nomina from cnmd09_ubicacion_direccion_personal b where b.cod_presi=a.cod_presi and b.cod_entidad=a.cod_entidad and b.cod_tipo_inst=a.cod_tipo_inst and b.cod_inst=a.cod_inst and b.cod_dep=a.cod_dep and b.cod_tipo_nomina=a.cod_tipo_nomina)
		order by
		a.cod_tipo_nomina
		asc";

		$lista=$this->cnmd09_ubicacion_direccion_personal->execute($sql);
		foreach($lista as $l){
				$r[]=$l[0]["cod_tipo_nomina"];
				$v[]=$l[0]["denominacion"];
		}
		$lista = array_combine($r, $v);

		$this->concatenaN($lista,'nomina');
 	}

	$datos=$this->cnmd09_ubicacion_direccion_personal->findAll($this->SQLCA(),null,'ORDER BY cod_tipo_nomina ASC');
	 if($datos!=null){
	 	 $this->set('opciones',$datos);
	 }else{
	 	 $this->set('opciones',null);
	 }
	 $deno_trans= $this->Cnmd01->findAll($this->SQLCA(), $fields ='cod_tipo_nomina, denominacion', $order = null, $limit = null, $page = null, $recursive = null);
	 $this->set('deno_trans', $deno_trans);

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



 function modificar($cod_nomina=null,$pagina=null){
 	$this->layout="ajax";
 	$cod_presi                =       $this->Session->read('SScodpresi');
	$cod_entidad              =       $this->Session->read('SScodentidad');
	$cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
	$cod_inst                 =       $this->Session->read('SScodinst');
	$cod_dep                  =       $this->Session->read('SScoddep');
	$cond="cod_tipo_institucion=".$cod_tipo_inst." and cod_institucion=".$cod_inst." and cod_dependencia=".$cod_dep;


 	$data=$this->cnmd09_ubicacion_direccion_personal->findAll($this->SQLCA()." and cod_tipo_nomina=".$cod_nomina);

	$this->set('cod_nomina', $data[0]['cnmd09_ubicacion_direccion_personal']['cod_tipo_nomina']);
	$this->set('cod_dir_superior', $data[0]['cnmd09_ubicacion_direccion_personal']['cod_dir_superior']);
	$this->set('cod_coordinacion', $data[0]['cnmd09_ubicacion_direccion_personal']['cod_coordinacion']);
	$this->set('cod_secretaria', $data[0]['cnmd09_ubicacion_direccion_personal']['cod_secretaria']);
	$this->set('cod_direccion', $data[0]['cnmd09_ubicacion_direccion_personal']['cod_direccion']);
	$this->set('pagina',$pagina);

	$deno_nomina = $this->Cnmd01->field('denominacion', $conditions = $this->condicion()." and Cnmd01.cod_tipo_nomina=".$data[0]['cnmd09_ubicacion_direccion_personal']['cod_tipo_nomina']);
	$this->set('deno_nomina', $deno_nomina);

	$lista=  $this->cugd02_direccionsuperior->generateList($cond, 'cod_dir_superior ASC', null, '{n}.cugd02_direccionsuperior.cod_dir_superior', '{n}.cugd02_direccionsuperior.denominacion');
	$this->concatena($lista, 'vector');
	$this->concatena($lista, 'superior');

	$cond.=" and cod_dir_superior=".$data[0]['cnmd09_ubicacion_direccion_personal']['cod_dir_superior'];
	$lista=  $this->cugd02_coordinacion->generateList($cond, 'cod_coordinacion ASC', null, '{n}.cugd02_coordinacion.cod_coordinacion', '{n}.cugd02_coordinacion.denominacion');
	$this->concatena($lista, 'coordinacion');

	$deno_superior= $this->cugd02_direccionsuperior->field('denominacion', $conditions = $cond, $order ="cod_dir_superior ASC");

	$cond.=" and cod_dir_superior=".$data[0]['cnmd09_ubicacion_direccion_personal']['cod_dir_superior']." and cod_coordinacion=".$data[0]['cnmd09_ubicacion_direccion_personal']['cod_coordinacion'];
	$lista=  $this->cugd02_secretaria->generateList($cond, 'cod_secretaria ASC', null, '{n}.cugd02_secretaria.cod_secretaria', '{n}.cugd02_secretaria.denominacion');
	$this->concatena($lista, 'secretaria');

	$deno_coor = $this->cugd02_coordinacion->field('denominacion', $cond, $order ="cod_coordinacion ASC");

	$cond.=" and cod_dir_superior=".$data[0]['cnmd09_ubicacion_direccion_personal']['cod_dir_superior']." and cod_coordinacion=".$data[0]['cnmd09_ubicacion_direccion_personal']['cod_coordinacion']." and cod_secretaria=".$data[0]['cnmd09_ubicacion_direccion_personal']['cod_secretaria'];
	$lista=  $this->cugd02_direccion->generateList($cond, 'cod_direccion ASC', null, '{n}.cugd02_direccion.cod_direccion', '{n}.cugd02_direccion.denominacion');
	$this->concatena($lista, 'direccion');

	$deno_secre = $this->cugd02_secretaria->field('denominacion', $cond, $order ="cod_secretaria ASC");

	$cond.=" and cod_dir_superior=".$data[0]['cnmd09_ubicacion_direccion_personal']['cod_dir_superior']." and cod_coordinacion=".$data[0]['cnmd09_ubicacion_direccion_personal']['cod_coordinacion']." and cod_secretaria=".$data[0]['cnmd09_ubicacion_direccion_personal']['cod_secretaria']." and cod_direccion=".$data[0]['cnmd09_ubicacion_direccion_personal']['cod_direccion'];
	$deno_direc = $this->cugd02_direccion->field('denominacion', $cond, $order ="cod_direccion ASC");

	$this->Session->write('dir_sup',$data[0]['cnmd09_ubicacion_direccion_personal']['cod_dir_superior']);
	$this->Session->write('coor',$data[0]['cnmd09_ubicacion_direccion_personal']['cod_coordinacion']);
	$this->Session->write('secre',$data[0]['cnmd09_ubicacion_direccion_personal']['cod_secretaria']);

	$this->set('deno_superior', $deno_superior);
	$this->set('deno_coordinacion', $deno_coor);
	$this->set('deno_secretaria', $deno_secre);
	$this->set('deno_direccion', $deno_direc);

 }//fin modificar



function guardar_modificar($nomina=null,$pagina=null){
	$this->layout="ajax";
	if(empty($this->data['cnmp09']['cod_superior'])){
    	$this->set('errorMessage','seleccione la dirección superior');
    }else if(empty($this->data['cnmp09']['cod_coordinacion'])){
    	$this->set('errorMessage','seleccione la coordinación');
    }else if(empty($this->data['cnmp09']['cod_secretaria'])){
    	$this->set('errorMessage','seleccione la secretaria');
    }else if(empty($this->data['cnmp09']['cod_direccion'])){
    	$this->set('errorMessage','seleccione la dirección');
    }else{
		$cod_superior=$this->data['cnmp09']['cod_superior'];
		$cod_coordinacion=$this->data['cnmp09']['cod_coordinacion'];
		$cod_secretaria=$this->data['cnmp09']['cod_secretaria'];
		$cod_direccion=$this->data['cnmp09']['cod_direccion'];

		$v=$this->cnmd09_ubicacion_direccion_personal->execute("update cnmd09_ubicacion_direccion_personal set cod_dir_superior='$cod_superior',cod_coordinacion='$cod_coordinacion',cod_secretaria='$cod_secretaria',cod_direccion='$cod_direccion' where ".$this->SQLCA()." and cod_tipo_nomina=".$nomina);
		if($v > 0){
			$this->set('Message_existe','EL REGISTRO SE MODIFICO EXITOSAMENTE');
			echo" <script> ver_documento('/cnmp09_ubicacion_administrativa_personal/consulta/".$pagina."','principal'); </script>";
		}else{
			$this->set('errorMessage','NO SE PUDO MODIFICAR');
		}

	}

 }//guardar modificar



function guardar_transferir($nomina=null){
	$this->layout="ajax";
//pr($this->data);
	  $cod_presi = $this->Session->read('SScodpresi');
	  $cod_entidad = $this->Session->read('SScodentidad');
	  $cod_tipo_inst = $this->Session->read('SScodtipoinst');
	  $cod_inst = $this->Session->read('SScodinst');
	  $cod_dep = $this->Session->read('SScoddep');

		if(isset($nomina)){
			 $cod_tipo_nomina = $nomina;
		}else{
			 $cod_tipo_nomina = $this->data['cnmp09']['cod_nomina'];
		}

		if(!empty($this->data['cnmp09']['cod_transferir'])){
		  $cod_transferir = $this->data['cnmp09']['cod_transferir'];

		  $ver=$this->cnmd09_ubicacion_direccion_personal->execute("select * from cnmd09_ubicacion_direccion_personal where ".$this->SQLCA()." and cod_tipo_nomina='$cod_transferir'");
		 	if($ver==null){
		 		$ver1=$this->cnmd09_ubicacion_direccion_personal->execute("select * from cnmd09_ubicacion_direccion_personal where ".$this->SQLCA()." and cod_tipo_nomina='$cod_tipo_nomina'");
				$dir_sup=$ver1[0][0]['cod_dir_superior'];
				$coordinacion=$ver1[0][0]['cod_coordinacion'];
				$secretaria=$ver1[0][0]['cod_secretaria'];
				$direccion=$ver1[0][0]['cod_direccion'];

				$sql_control = "INSERT INTO cnmd09_ubicacion_direccion_personal VALUES('$cod_presi', '$cod_entidad', '$cod_tipo_inst', '$cod_inst', '$cod_dep', '$cod_transferir', '$dir_sup', '$coordinacion', '$secretaria','$direccion')";
				$sw1 = $this->cnmd09_ubicacion_direccion_personal->execute($sql_control);
				if($sw1>1){
		  			$this->set('Message_existe', 'Transferencia realizada con exito');
			    }else{
			  		$this->set('errorMessage', 'Transferencia sin exito intente nuevamente');
			    }
		 	}else{
		 		$this->set('errorMessage','esta nómina ya tiene una ubicación administrativa registrada');
		 	}

		}else{
			$this->set('errorMessage','seleccione el tipo de nómina al cual desea transferir');
		}
	$datos=$this->cnmd09_ubicacion_direccion_personal->findAll($this->SQLCA(),null,'ORDER BY cod_tipo_nomina ASC');
	 if($datos!=null){
	 	 $this->set('opciones',$datos);
	 }else{
	 	 $this->set('opciones',null);
	 }
	 $deno_trans= $this->Cnmd01->findAll($this->SQLCA(), $fields ='cod_tipo_nomina, denominacion', $order = null, $limit = null, $page = null, $recursive = null);
	 $this->set('deno_trans', $deno_trans);

}// fin guardar_transferir


}//FIN CONTROLADOR
?>