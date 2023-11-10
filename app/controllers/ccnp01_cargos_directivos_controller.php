<?php
 class Ccnp01CargosDirectivosController extends AppController{
	var $name = 'ccnp01_cargos_directivos';
	var $uses = array('ccnd01_tipo_directivo','ccnd01_cargos_directivos');
	var $helpers = array('Html','Ajax','Javascript', 'Sisap');



function checkSession(){
				if (!$this->Session->check('concejo_comunal')){
						$this->redirect('/salir');
						exit();
				}
}//fin checksession




function AddCero3($numero,$extra=null){
   	  if($extra==null){
   	  	$numero = ($numero < 10 ? "0".$numero : $numero);
   	  }else{
   	  	$numero = ($numero < 10 ? $extra."0".$numero : $extra.".".$numero);
   	  }
	    return $numero;
   }//fin AddCero


 function index($cod_ramo=null){
	$this->layout="ajax";
	$this->set('entidad_federal', $this->Session->read('entidad_federal'));
	//$tipo = $this->cnmd02_obreros_grupos->generateList(null,'cod_grupo ASC', null, '{n}.cnmd02_obreros_grupos.cod_grupo', '{n}.cnmd02_obreros_grupos.denominacion');
	$tipo = $this->ccnd01_tipo_directivo->generateList(null,'cod_tipo ASC', null, '{n}.ccnd01_tipo_directivo.cod_tipo', '{n}.ccnd01_tipo_directivo.denominacion');
	//$this->set('cod_ramo',$tipo);
	$this->concatena($tipo,'cod_ramo');

 }//index

 function guardar(){
	$this->layout="ajax";
//	print_r($this->data);


		$cod_ramo=$this->data['cnmp02_obreros_grupos']['cod_ramo'];
		$cod_grupo=$this->data['cnmp02_obreros_grupos']['cod_grupo'];
		//$cod_subgrupo=$this->data['cnmp02_obreros_grupos']['cod_subgrupo'];
		if(empty($this->data['cnmp02_obreros_grupos']['deno_grupo'])){
			$this->set('errorMessage','DEBE INGRESAR LA DENOMINACIÓN DEL CARGO');
		}else{
			$deno_grupo=$this->data['cnmp02_obreros_grupos']['deno_grupo'];
			$sql ="INSERT INTO ccnd01_cargos_directivos VALUES ('$cod_ramo','$cod_grupo','$deno_grupo')";

			$veri=$this->ccnd01_cargos_directivos->findCount('cod_tipo='.$cod_ramo.' and cod_cargo='.$cod_grupo);

			if($veri==0){
				$re=$this->ccnd01_cargos_directivos->execute("INSERT INTO ccnd01_cargos_directivos VALUES ('$cod_ramo','$cod_grupo','$deno_grupo')");
				if($re>1){
					 $this->set('Message_existe','REGISTRO EXITOSO');
				}else{
					 $this->set('errorMessage','NO SE PUDO AGREGAR EL REGISTRO');
				}
			}else{
				$this->set('errorMessage','ESTOS CODIGOS YA SE ENCUENTRAN REGISTRADOS');
	 		}
		}

		 $a=  $this->ccnd01_cargos_directivos->findAll("cod_tipo=".$cod_ramo." order by cod_cargo desc");
            if($a!=null){
            	$num=$a[0]['ccnd01_cargos_directivos']['cod_cargo']+1;
            }else{
				$num=1;
            }
            echo "<script>document.getElementById('cod_cargo').value='".$this->zero($num)."';</script>";
             echo "<script>document.getElementById('deno_grupo').value='';</script>";

		 $datacpcp01=$this->ccnd01_cargos_directivos->findAll('cod_tipo='.$cod_ramo,null,'cod_tipo,cod_cargo ASC');
    	 $this->set('datos',$datacpcp01);

 }//fin function


function eliminar($cod_ramo,$cod_grupo){
   $this->layout="ajax";

   $sql="DELETE FROM ccnd01_cargos_directivos WHERE cod_tipo=".$cod_ramo." and  cod_cargo=".$cod_grupo;
   if($this->ccnd01_cargos_directivos->execute($sql)>1){
  		 $this->set('Message_existe','REGISTRO FUE ELIMINADO CORRECTAMENTE');
   }else{
  		 $this->set('errorMessage','EL REGISTRO NO PUDO SER ELIMINADO');
   }

    $a=  $this->ccnd01_cargos_directivos->findAll("cod_tipo=".$cod_ramo." order by cod_cargo desc");
	if($a!=null){
		$num=$a[0]['ccnd01_cargos_directivos']['cod_cargo']+1;
	}else{
		$num=1;
	}
	echo "<script>document.getElementById('cod_cargo').value='".$this->zero($num)."';</script>";
	echo "<script>document.getElementById('deno_grupo').value='';</script>";

   $datacpcp01=$this->ccnd01_cargos_directivos->findAll('cod_tipo='.$cod_ramo,null,'cod_tipo,cod_cargo ASC');
   $this->set('datos',$datacpcp01);

}//eliminar


 function mostrar_datos($var=null){
 	$this->layout="ajax";
 	if($var!=null){
 		if($var==1){
 			$datos=$this->cnmd02_obreros_grupos->findAll(null,null,'cod_grupo ASC');
			$this->set('datos',$datos);
 		}elseif($var==2){
 			$datos=$this->cnmd02_obreros_grupos->findAll(null,null,'denominacion ASC');
			$this->set('datos',$datos);
 		}
 	}else{
 		$this->set('mensajeError','LO SIENTO, NO LLEGO INFORMACI&Oacute;N PARA PROCESAR');
 	}
 }//mostrar_datos
function select3($select=null,$var=null) { //select codigos presupuestarios
	$this->layout = "ajax";
	//echo "si llego";
	if($var!=null){
	switch($select){
		case 'tipo':
		//echo 'si';
		  $this->set('SELECT','grupo');
		  $this->set('codigo','tipo');
		  $this->set('seleccion','');
		  $this->set('n',1);
		  $ano =  $this->Session->read('ano');
		  $cond2 ="ejercicio=".$ano." and cod_grupo=".CE."";
		  $lista=  $this->cnmd02_obreros_ramos->generateList($cond2, 'cod_ramo ASC', null, '{n}.cnmd02_obreros_ramos.cod_ramo', '{n}.cnmd02_obreros_ramos.denominacion');
			$this->concatena($lista,'vector');
		break;
		case 'grupo':
		//echo "si generica";
		  $this->set('SELECT','subgrupo');
		  $this->set('codigo','grupo');
		  $this->set('seleccion','');
		  $this->set('n',2);
		 // $ano =  $this->Session->read('ano');
		  $this->Session->write('ctipo',$var);
		  $cond2 ="cod_ramo=".$var;
		  $lista=  $this->cnmd02_obreros_grupos->generateList($cond2, 'cod_grupo ASC', null, '{n}.cnmd02_obreros_grupos.cod_grupo', '{n}.cnmd02_obreros_grupos.denominacion');
          $this->concatena($lista,'vector');
 		break;
		case 'subgrupo':
		//echo"si especifica";
		  $this->set('SELECT','seccion');
		  $this->set('codigo','subgrupo');
		  $this->set('seleccion','');
		  $this->set('n',3);
		//  $ano =  $this->Session->read('ano');
		  $ctipo =  $this->Session->read('ctipo');
		  $this->Session->write('cgru',$var);
		  $cond2 ="cod_ramo=".$ctipo." and cod_grupo=".$var;
		 //echo $cond2;
		  $lista = $this->cnmd02_obreros_grupos->generateList($cond2, 'cod_subgrupo ASC', null, '{n}.cnmd02_obreros_grupos.cod_subgrupo', '{n}.cnmd02_obreros_grupos.denominacion');
          $this->concatena($lista,'vector');
		break;
		case 'seccion':
		  //echo 'si';
		  $this->set('SELECT','seccion');
		  $this->set('codigo','seccion');
		  $this->set('seleccion','');
		  $this->set('n',4);
		//  $ano =  $this->Session->read('ano');
		  $ctipo =  $this->Session->read('ctipo');
		  $cgru =  $this->Session->read('cgru');
		  $this->Session->write('csub',$var);
		  $cond2 ="cod_ramo=".$ctipo." and cod_grupo=".$cgru." and cod_subgrupo=".$var;
		  $lista=  $this->cnmd02_obreros_grupos->generateList($cond2, 'cod_seccion ASC', null, '{n}.cnmd02_obreros_grupos.cod_seccion', '{n}.cnmd02_obreros_grupos.denominacion');
          $this->concatena($lista,'vector');
		break;
	}//fin wsitch
	}else{
		  $this->set('SELECT','');
		  $this->set('codigo','');
		  $this->set('seleccion','');
		  $this->set('n',10);
		  $this->set('no','no');
		 $this->set('vector','');
	}
}//fin select codigos presupuestarios


function mostrar3($select=null,$var=null) { //mostrar3 codigos presupuestarios
	$this->layout = "ajax";
	//echo "mostrar";
		if( $var!=null){
  //  $cond = $this->SQLCA();
    //$cond2 = $this->SQLCA();
	switch($select){
		case 'grupo':
		  $b=  $this->ccnd01_tipo_directivo->findAll("cod_tipo='".$var."'");
          $e=$b[0]['ccnd01_tipo_directivo']['denominacion'];
          $this->set('denominacion',$e);

          $a=  $this->ccnd01_cargos_directivos->findAll("cod_tipo='".$var."' order by cod_cargo desc");
            if($a!=null){
            	$num=$a[0]['ccnd01_cargos_directivos']['cod_cargo']+1;
            }else{
				$num=1;
            }
            echo "<script>document.getElementById('cod_cargo').value='".$this->zero($num)."';</script>";
             echo "<script>document.getElementById('deno_grupo').value='';
             	  document.getElementById('agregar').disabled=false;</script>";
		break;
	}//fin wsitch
	}else{
		echo "";
	}
}//fin mostrar3 codigos presupuestarios



function modificar($cod_ramo, $cod_grupo,$i=null){
	$this->layout = "ajax";
	 $data=$this->ccnd01_cargos_directivos->findAll('cod_tipo='.$cod_ramo.' and cod_cargo='.$cod_grupo);
	 $this->set('cod_tipo',$cod_ramo);
	 $this->set('cod_cargo',$cod_grupo);
	 $this->set('k',$i);
     $this->set('denominacion',$data[0]['ccnd01_cargos_directivos']['denominacion']);

     $this->set('Message_existe','PROCEDA A MODIFICAR EL DATO');

}

function guardar_modificar($cod_ramo=null, $cod_grupo=null,$k=null){
	$this->layout = "ajax";
//pr($this->data);
	if(empty($this->data['cnmp02_obreros_grupos']['deno_grupo'.$k])){
		$this->set('errorMessage','debe ingresar la denominacion');
	}else{
		$deno_grupo=$this->data['cnmp02_obreros_grupos']['deno_grupo'.$k];
		$update="update ccnd01_cargos_directivos set denominacion='".$deno_grupo."' where cod_tipo=".$cod_ramo." and cod_cargo=".$cod_grupo;
		$sw=$this->ccnd01_cargos_directivos->execute($update);
		if($sw>1){
			$this->set('Message_existe','EL REGISTRO FUE MODIFICADO EXITOSAMENTE');
		}else{
			$this->set('errorMessage','EL REGISTRO NO PUDO SER MODIFICADO');
		}
	}
	$datacpcp01=$this->ccnd01_cargos_directivos->findAll('cod_tipo='.$cod_ramo,null,'cod_tipo,cod_cargo ASC');
    $this->set('datos',$datacpcp01);

}

function grilla($cod_ramo=null){
	$this->layout = "ajax";
	$datacpcp01=$this->ccnd01_cargos_directivos->findAll('cod_tipo='.$cod_ramo,null,'cod_tipo,cod_cargo ASC');
     $this->set('datos',$datacpcp01);
}


function cancelar($cod_ramo=null){
	$this->layout = "ajax";
	$datacpcp01=$this->ccnd01_cargos_directivos->findAll('cod_tipo='.$cod_ramo,null,'cod_tipo,cod_cargo ASC');
     $this->set('datos',$datacpcp01);
}

}
?>