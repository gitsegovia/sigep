<?php
 class Cnmp02TablasGradosPasosController extends AppController {
 	var $name = 'cnmp02_tablas_grados_pasos';
	var $uses = array('cnmd02_tablas_tipo','cnmd02_tablas_grado_paso','cnmd02_deno_grado','cnmd02_deno_grado_obrero','Cnmd01');
	var $helpers = array('Html','Ajax','Javascript', 'Sisap');



 function checkSession(){
				if (!$this->Session->check('Usuario')){
					$this->redirect('/salir/');
					exit();
				}else{
					$this->requestAction('/usuarios/actualizar_user');
				}
 }//fin checksession


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
	$lista = $this->cnmd02_tablas_tipo->generateList(null, $order = 'cod_tabla', $limit = null, '{n}.cnmd02_tablas_tipo.cod_tabla', '{n}.cnmd02_tablas_tipo.denominacion');
	$this->concatena($lista, 'nomina');

	$grados = $this->cnmd02_deno_grado->generateList(null, $order = 'grado', $limit = null, '{n}.cnmd02_deno_grado.grado', '{n}.cnmd02_deno_grado.denominacion');
	$this->concatena($grados, 'grados');
	$grados_obreros = $this->cnmd02_deno_grado_obrero->generateList(null, $order = 'grado', $limit = null, '{n}.cnmd02_deno_grado_obrero.grado', '{n}.cnmd02_deno_grado_obrero.denominacion');
	$this->concatena($grados_obreros, 'grados_obreros');
	$this->concatena($grados, 'grados');

 }//index



function cod_tabla($cod_nomina=null){
	$this->layout="ajax";
	if($cod_nomina!=null){
		$this->set('cod_nomina', $cod_nomina);
		$this->Session->delete('nomina');
		$this->Session->write('nomina',$cod_nomina);
	}
}

function deno_tabla($cod_nomina=null){
	$this->layout="ajax";
	if($cod_nomina!=null){
		$deno_nomina = $this->cnmd02_tablas_tipo->field('denominacion',"cod_tabla=".$cod_nomina);
		//echo "el tipo de nomina es: ".$deno_nomina;
		$this->set('deno_nomina', $deno_nomina);
	}
	echo "<script>";
		echo "document.getElementById('transferencia').innerHTML='';";
	echo "</script>";
}


 function limpiar(){
 	$this->layout="ajax";
 }// fin limpiar


 function guardar(){
	$this->layout="ajax";
    $cod_tipo=$this->data['cnmp02_tablas_grados_pasos']['cod_tipo'];
	$grado=$this->data['cnmp02_tablas_grados_pasos']['grado'];
	$paso1=$this->Formato1($this->data['cnmp02_tablas_grados_pasos']['paso1']);
	$paso2=$this->Formato1($this->data['cnmp02_tablas_grados_pasos']['paso2']);
	$paso3=$this->Formato1($this->data['cnmp02_tablas_grados_pasos']['paso3']);
	$paso4=$this->Formato1($this->data['cnmp02_tablas_grados_pasos']['paso4']);
	$paso5=$this->Formato1($this->data['cnmp02_tablas_grados_pasos']['paso5']);
	$paso6=$this->Formato1($this->data['cnmp02_tablas_grados_pasos']['paso6']);
	$paso7=$this->Formato1($this->data['cnmp02_tablas_grados_pasos']['paso7']);
	$paso8=0;
	$paso9=0;
	$paso10=0;
	$paso11=0;
	$paso12=0;
	$paso13=0;
	$paso14=0;
	$paso15=0;

	/*
	$paso8=$this->Formato1($this->data['cnmp02_tablas_grados_pasos']['paso8']);
	$paso9=$this->Formato1($this->data['cnmp02_tablas_grados_pasos']['paso9']);
	$paso10=$this->Formato1($this->data['cnmp02_tablas_grados_pasos']['paso10']);
	$paso11=$this->Formato1($this->data['cnmp02_tablas_grados_pasos']['paso11']);
	$paso12=$this->Formato1($this->data['cnmp02_tablas_grados_pasos']['paso12']);
	$paso13=$this->Formato1($this->data['cnmp02_tablas_grados_pasos']['paso13']);
	$paso14=$this->Formato1($this->data['cnmp02_tablas_grados_pasos']['paso14']);
	$paso15=$this->Formato1($this->data['cnmp02_tablas_grados_pasos']['paso15']);
	*/

if($cod_tipo!=""){
// if($paso1!=0 && $paso2!=0 && $paso3!=0 && $paso4!=0 && $paso5!=0 && $paso6!=0 && $paso7!=0 && $paso8!=0 && $paso9!=0 && $paso10!=0 && $paso11!=0 && $paso12!=0 && $paso13!=0 && $paso14!=0 && $paso15!=0){
if($paso1!=0 && $paso2!=0 && $paso3!=0 && $paso4!=0 && $paso5!=0 && $paso6!=0 && $paso7!=0){
	if($paso2 < $paso1){
		$this->set('errorMessage','el paso 02 no puede ser menor al paso 01');
		echo "<script>";
			echo "document.getElementById('paso2').velue='';";
		echo "</script>";
		return;
	}else if($paso3 < $paso2){
		$this->set('errorMessage','el paso 03 no puede ser menor al paso 02');
		echo "<script>";
			echo "document.getElementById('paso3').velue='';";
		echo "</script>";
		return;
	}else if($paso4 < $paso3){
		$this->set('errorMessage','el paso 04 no puede ser menor al paso 03');
		echo "<script>";
			echo "document.getElementById('paso4').velue='';";
		echo "</script>";
		return;
	}else if($paso5 < $paso4){
		$this->set('errorMessage','el paso 05 no puede ser menor al paso 04');
		echo "<script>";
			echo "document.getElementById('paso5').velue='';";
		echo "</script>";
		return;
	}else if($paso6 < $paso5){
		$this->set('errorMessage','el paso 06 no puede ser menor al paso 05');
		echo "<script>";
			echo "document.getElementById('paso6').velue='';";
		echo "</script>";
		return;
	}else if($paso7 < $paso6){
		$this->set('errorMessage','el paso 07 no puede ser menor al paso 06');
		echo "<script>";
			echo "document.getElementById('paso7').velue='';";
		echo "</script>";
		return;
	}

	/*
	else if($paso8 < $paso7){
		$this->set('errorMessage','el paso 08 no puede ser menor al paso 07');
		echo "<script>";
			echo "document.getElementById('paso8').velue='';";
		echo "</script>";
		return;
	}else if($paso9 < $paso8){
		$this->set('errorMessage','el paso 09 no puede ser menor al paso 08');
		echo "<script>";
			echo "document.getElementById('paso9').velue='';";
		echo "</script>";
		return;
	}else if($paso10 < $paso9){
		$this->set('errorMessage','el paso 10 no puede ser menor al paso 09');
		echo "<script>";
			echo "document.getElementById('paso10').velue='';";
		echo "</script>";
		return;
	}else if($paso11 < $paso10){
		$this->set('errorMessage','el paso 11 no puede ser menor al paso 10');
		echo "<script>";
			echo "document.getElementById('paso11').velue='';";
		echo "</script>";
		return;
	}else if($paso12 < $paso11){
		$this->set('errorMessage','el paso 12 no puede ser menor al paso 11');
		echo "<script>";
			echo "document.getElementById('paso12').velue='';";
		echo "</script>";
		return;
	}else if($paso13 < $paso12){
		$this->set('errorMessage','el paso 13 no puede ser menor al paso 12');
		echo "<script>";
			echo "document.getElementById('paso13').velue='';";
		echo "</script>";
		return;
	}else if($paso14 < $paso13){
		$this->set('errorMessage','el paso 14 no puede ser menor al paso 13');
		echo "<script>";
			echo "document.getElementById('paso14').velue='';";
		echo "</script>";
		return;
	}else if($paso15 < $paso14){
		$this->set('errorMessage','el paso 15 no puede ser menor al paso 14');
		echo "<script>";
			echo "document.getElementById('paso15').velue='';";
		echo "</script>";
		return;
	}
*/

	if(!$this->cnmd02_tablas_grado_paso->FindAll($this->SQLCA()." and cod_tabla=".$cod_tipo."and grado=".$grado)){
		 $sql="INSERT INTO cnmd02_tablas_grado_paso VALUES (".$this->verifica_SS(1).",".$this->verifica_SS(2).",".$this->verifica_SS(3).",".$this->verifica_SS(4).",".$this->verifica_SS(5).",'$cod_tipo','$grado','$paso1','$paso2','$paso3','$paso4','$paso5','$paso6','$paso7','$paso8','$paso9','$paso10','$paso11','$paso12','$paso13','$paso14','$paso15')";
			   $sw=$this->cnmd02_tablas_grado_paso->execute($sql);
			   if($sw>1){
			   		$this->set('Message_existe','Registro exitoso');
			   		echo "<script>";
			   			echo "cnmp02_limpia_grados_pasos();";
			   			echo "document.getElementById('paso1').readOnly=false;";
					echo "</script>";

			   }else{
			   		$this->set('errorMessage','no pudo registrarse, intente nuevamente');
			   }
	}else{
		$this->set('errorMessage','este registro ya existe');
		/*echo "<script>";
			echo "document.getElementById('cod_tabla').velue='';";
			echo "document.getElementById('denominacion').velue='';";
		echo "</script>";*/
		echo "<script>cnmp02_limpia_grados_pasos();</script>";
	}
}else{
	$this->set('errorMessage','debe completar todos los pasos');
}
}else{
	$this->set('errorMessage','debe seleccionar el codigo del tipo');
}

			//echo "<script>document.getElementById('denominacion').value='';</script>";
 }//guardar




 function consultar($pagina=null) {
	$this->layout="ajax";
	$this->set('entidad_federal', $this->Session->read('entidad_federal'));
	//echo "coooonsultar";

	if(isset($pagina)){
		$Tfilas=$this->cnmd02_tablas_grado_paso->findCount($this->SQLCA());
        if($Tfilas!=0){
        	$data=$this->cnmd02_tablas_grado_paso->findAll($this->SQLCA(),null,"cod_tabla ASC",1,$pagina,null);

            $this->set('DATA',$data);
          	$this->set('siguiente',$pagina+1);
          	$this->set('anterior',$pagina-1);
            $this->bt_nav($Tfilas,$pagina);
            $this->set('numT',$Tfilas);
			$this->set('numP',$pagina);
        }else{
	 	    $this->set('errorMessage', 'No se encontrar&oacute;n datos');
	 	    $this->set('noExiste',true);
        }

	}else{
		$pagina=1;
		$Tfilas=$this->cnmd02_tablas_grado_paso->findCount($this->SQLCA());

        if($Tfilas!=0){
        	$data=$this->cnmd02_tablas_grado_paso->findAll($this->SQLCA(),null,"cod_tabla ASC",1,$pagina,null);
			$this->set('DATA',$data);
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

	$this->set('cod_tipo', $data[0]['cnmd02_tablas_grado_paso']['cod_tabla']);
	$this->set('grado', $data[0]['cnmd02_tablas_grado_paso']['grado']);
	//echo $data[0]['cnmd02_tablas_grado_paso']['monto_paso1'];
	$this->set('paso1', $data[0]['cnmd02_tablas_grado_paso']['monto_paso1']);
	$this->set('paso2', $data[0]['cnmd02_tablas_grado_paso']['monto_paso2']);
	$this->set('paso3', $data[0]['cnmd02_tablas_grado_paso']['monto_paso3']);
	$this->set('paso4', $data[0]['cnmd02_tablas_grado_paso']['monto_paso4']);
	$this->set('paso5', $data[0]['cnmd02_tablas_grado_paso']['monto_paso5']);
	$this->set('paso6', $data[0]['cnmd02_tablas_grado_paso']['monto_paso6']);
	$this->set('paso7', $data[0]['cnmd02_tablas_grado_paso']['monto_paso7']);
	$this->set('paso8', $data[0]['cnmd02_tablas_grado_paso']['monto_paso8']);
	$this->set('paso9', $data[0]['cnmd02_tablas_grado_paso']['monto_paso9']);
	$this->set('paso10', $data[0]['cnmd02_tablas_grado_paso']['monto_paso10']);
	$this->set('paso11', $data[0]['cnmd02_tablas_grado_paso']['monto_paso11']);
	$this->set('paso12', $data[0]['cnmd02_tablas_grado_paso']['monto_paso12']);
	$this->set('paso13', $data[0]['cnmd02_tablas_grado_paso']['monto_paso13']);
	$this->set('paso14', $data[0]['cnmd02_tablas_grado_paso']['monto_paso14']);
	$this->set('paso15', $data[0]['cnmd02_tablas_grado_paso']['monto_paso15']);
	$denominacion = $this->cnmd02_tablas_tipo->field('denominacion',"cod_tabla=".$data[0]['cnmd02_tablas_grado_paso']['cod_tabla']);
	$this->set('denominacion', $denominacion);

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



function eliminar($cod_tipo, $cod_grado, $pagina_regreso=null){
 		$this->layout="ajax";

		$sql = "DELETE FROM cnmd02_tablas_grado_paso WHERE ".$this->SQLCA()." and cod_tabla=".$cod_tipo." and grado=$cod_grado";
		if($this->cnmd02_tablas_grado_paso->execute($sql)>1){
			$this->set('mensaje', 'EL REGISTRO FUE ELIMINADO EXITOSAMENTE');
			/////DE AQUI AGREGO ERICK
			$Tfilas=$this->cnmd02_tablas_grado_paso->findCount($this->SQLCA());
				if($Tfilas!=0){
				$this->consultar($pagina_regreso);
				$this->render("consultar");
				}else{
					$this->index();
					$this->render("index");
				}/////HASTA AQUI AGREGO ERICK
			//$this->consultar($pagina_regreso);
			//$this->render("consultar");
		}else{
			$this->set('mensajeError', 'EL REGISTRO NO PUDO SER ELIMINADO');
			$this->consultar($pagina_regreso);
			$this->render("consultar");
		}
 }//eliminar



 function modificar($cod=null,$id=null){
 	$this->layout="ajax";
 	$data=$this->cnmd02_tablas_grado_paso->findAll($this->SQLCA()." and cod_tabla=".$cod,null,"cod_tabla ASC",1,null,null);
 	$this->set('cod_tipo', $data[0]['cnmd02_tablas_grado_paso']['cod_tabla']);
	$this->set('grado', $data[0]['cnmd02_tablas_grado_paso']['grado']);
	//echo $data[0]['cnmd02_tablas_grado_paso']['monto_paso1'];
	$this->set('paso1', $data[0]['cnmd02_tablas_grado_paso']['monto_paso1']);
	$this->set('paso2', $data[0]['cnmd02_tablas_grado_paso']['monto_paso2']);
	$this->set('paso3', $data[0]['cnmd02_tablas_grado_paso']['monto_paso3']);
	$this->set('paso4', $data[0]['cnmd02_tablas_grado_paso']['monto_paso4']);
	$this->set('paso5', $data[0]['cnmd02_tablas_grado_paso']['monto_paso5']);
	$this->set('paso6', $data[0]['cnmd02_tablas_grado_paso']['monto_paso6']);
	$this->set('paso7', $data[0]['cnmd02_tablas_grado_paso']['monto_paso7']);
	$this->set('paso8', $data[0]['cnmd02_tablas_grado_paso']['monto_paso8']);
	$this->set('paso9', $data[0]['cnmd02_tablas_grado_paso']['monto_paso9']);
	$this->set('paso10', $data[0]['cnmd02_tablas_grado_paso']['monto_paso10']);
	$this->set('paso11', $data[0]['cnmd02_tablas_grado_paso']['monto_paso11']);
	$this->set('paso12', $data[0]['cnmd02_tablas_grado_paso']['monto_paso12']);
	$this->set('paso13', $data[0]['cnmd02_tablas_grado_paso']['monto_paso13']);
	$this->set('paso14', $data[0]['cnmd02_tablas_grado_paso']['monto_paso14']);
	$this->set('paso15', $data[0]['cnmd02_tablas_grado_paso']['monto_paso15']);
	$denominacion = $this->cnmd02_tablas_tipo->field('denominacion',"cod_tabla=".$data[0]['cnmd02_tablas_grado_paso']['cod_tabla']);
	$this->set('denominacion', $denominacion);
	//$this->set('mensaje', 'PROCEDA A MODIFICAR LOS DATOS');
	$this->set('pagina',$id);
 }//fin modificar


 function guardar_modificar($cod=null,$id=null){
	$this->layout="ajax";
	$cod_tipo=$this->data['cnmp02_tablas_grados_pasos']['cod_tipo'];
	$grado=$this->data['cnmp02_tablas_grados_pasos']['grado'];
	$paso1=$this->Formato1($this->data['cnmp02_tablas_grados_pasos']['paso1']);
	$paso2=$this->Formato1($this->data['cnmp02_tablas_grados_pasos']['paso2']);
	$paso3=$this->Formato1($this->data['cnmp02_tablas_grados_pasos']['paso3']);
	$paso4=$this->Formato1($this->data['cnmp02_tablas_grados_pasos']['paso4']);
	$paso5=$this->Formato1($this->data['cnmp02_tablas_grados_pasos']['paso5']);
	$paso6=$this->Formato1($this->data['cnmp02_tablas_grados_pasos']['paso6']);
	$paso7=$this->Formato1($this->data['cnmp02_tablas_grados_pasos']['paso7']);
	$paso8=$this->Formato1($this->data['cnmp02_tablas_grados_pasos']['paso8']);
	$paso9=$this->Formato1($this->data['cnmp02_tablas_grados_pasos']['paso9']);
	$paso10=$this->Formato1($this->data['cnmp02_tablas_grados_pasos']['paso10']);
	$paso11=$this->Formato1($this->data['cnmp02_tablas_grados_pasos']['paso11']);
	$paso12=$this->Formato1($this->data['cnmp02_tablas_grados_pasos']['paso12']);
	$paso13=$this->Formato1($this->data['cnmp02_tablas_grados_pasos']['paso13']);
	$paso14=$this->Formato1($this->data['cnmp02_tablas_grados_pasos']['paso14']);
	$paso15=$this->Formato1($this->data['cnmp02_tablas_grados_pasos']['paso15']);


	// if($paso1!=0 && $paso2!=0 && $paso3!=0 && $paso4!=0 && $paso5!=0 && $paso6!=0 && $paso7!=0 && $paso8!=0 && $paso9!=0 && $paso10!=0 && $paso11!=0 && $paso12!=0 && $paso13!=0 && $paso14!=0 && $paso15!=0){
	if($paso1!=0 && $paso2!=0 && $paso3!=0 && $paso4!=0 && $paso5!=0 && $paso6!=0 && $paso7!=0){
		if($paso2 < $paso1){
			$this->set('errorMessage','el paso 02 no puede ser menor al paso 01');
			echo "<script>";
				echo "document.getElementById('paso2').velue='';";
			echo "</script>";
			$this->modificar($cod);
			$this->render('modificar');
			return;
		}else if($paso3 < $paso2){
			$this->set('errorMessage','el paso 03 no puede ser menor al paso 02');
			echo "<script>";
				echo "document.getElementById('paso3').velue='';";
			echo "</script>";
			$this->modificar($cod);
			$this->render('modificar');
			return;
		}else if($paso4 < $paso3){
			$this->set('errorMessage','el paso 04 no puede ser menor al paso 03');
			echo "<script>";
				echo "document.getElementById('paso4').velue='';";
			echo "</script>";
			$this->modificar($cod);
			$this->render('modificar');
			return;
		}else if($paso5 < $paso4){
			$this->set('errorMessage','el paso 05 no puede ser menor al paso 04');
			echo "<script>";
				echo "document.getElementById('paso5').velue='';";
			echo "</script>";
			$this->modificar($cod);
			$this->render('modificar');
			return;
		}else if($paso6 < $paso5){
			$this->set('errorMessage','el paso 06 no puede ser menor al paso 05');
			echo "<script>";
				echo "document.getElementById('paso6').velue='';";
			echo "</script>";
			$this->modificar($cod);
			$this->render('modificar');
			return;
		}else if($paso7 < $paso6){
			$this->set('errorMessage','el paso 07 no puede ser menor al paso 06');
			echo "<script>";
				echo "document.getElementById('paso7').velue='';";
			echo "</script>";
			$this->modificar($cod);
			$this->render('modificar');
			return;
		}

		/*
		else if($paso8 < $paso7){
			$this->set('errorMessage','el paso 08 no puede ser menor al paso 07');
			echo "<script>";
				echo "document.getElementById('paso8').velue='';";
			echo "</script>";
			$this->modificar($cod);
			$this->render('modificar');
			return;
		}else if($paso9 < $paso8){
			$this->set('errorMessage','el paso 09 no puede ser menor al paso 08');
			echo "<script>";
				echo "document.getElementById('paso9').velue='';";
			echo "</script>";
			$this->modificar($cod);
			$this->render('modificar');
			return;
		}else if($paso10 < $paso9){
			$this->set('errorMessage','el paso 10 no puede ser menor al paso 09');
			echo "<script>";
				echo "document.getElementById('paso10').velue='';";
			echo "</script>";
			$this->modificar($cod);
			$this->render('modificar');
			return;
		}else if($paso11 < $paso10){
			$this->set('errorMessage','el paso 11 no puede ser menor al paso 10');
			echo "<script>";
				echo "document.getElementById('paso11').velue='';";
			echo "</script>";
			$this->modificar($cod);
			$this->render('modificar');
			return;
		}else if($paso12 < $paso11){
			$this->set('errorMessage','el paso 12 no puede ser menor al paso 11');
			echo "<script>";
				echo "document.getElementById('paso12').velue='';";
			echo "</script>";
			$this->modificar($cod);
			$this->render('modificar');
			return;
		}else if($paso13 < $paso12){
			$this->set('errorMessage','el paso 13 no puede ser menor al paso 12');
			echo "<script>";
				echo "document.getElementById('paso13').velue='';";
			echo "</script>";
			$this->modificar($cod);
			$this->render('modificar');
			return;
		}else if($paso14 < $paso13){
			$this->set('errorMessage','el paso 14 no puede ser menor al paso 13');
			echo "<script>";
				echo "document.getElementById('paso14').velue='';";
			echo "</script>";
			$this->modificar($cod);
			$this->render('modificar');
			return;
		}else if($paso15 < $paso14){
			$this->set('errorMessage','el paso 15 no puede ser menor al paso 14');
			echo "<script>";
				echo "document.getElementById('paso15').velue='';";
			echo "</script>";
			$this->modificar($cod);
			$this->render('modificar');
			return;
		}
*/

			$v=$this->cnmd02_tablas_grado_paso->execute("update cnmd02_tablas_grado_paso set monto_paso1=".$paso1.",monto_paso2=".$paso2.",monto_paso3=".$paso3.",monto_paso4=".$paso4.",monto_paso5=".$paso5.",monto_paso6=".$paso6.",monto_paso7=".$paso7.",monto_paso8=".$paso8.",monto_paso9=".$paso9.",monto_paso10=".$paso10.",monto_paso11=".$paso11.",monto_paso12=".$paso12.",monto_paso13=".$paso13.",monto_paso14=".$paso14.",monto_paso15=".$paso15." where ".$this->SQLCA()." and cod_tabla=".$cod);
			if($v > 0){
				$this->set('Message_existe','EL REGISTRO SE MODIFICO EXITOSAMENTE');
				$this->consultar($id);
				$this->render('consultar');
			}else{
				$this->set('errorMessage','NO SE PUDO MODIFICAR');
				$this->modificar($cod,$id);
				$this->render('modificar');
			}
	}else{
		$this->set('errorMessage','debe completar todos los pasos');
		$this->modificar($cod,$id);
		$this->render('modificar');
	}

 }//guardar modificar



 function cancelar(){
 	$this->layout="ajax";
    $this->index();
	$this->render("index");

 }//fin cancelar

function actualizar_sueldo($var=null){
	$this->layout="ajax";
	if($var==null){
		$lista = $this->Cnmd01->generateListTxt($this->SQLCA(), 'cod_tipo_nomina ASC', null, '{n}.Cnmd01.cod_tipo_nomina', '{n}.Cnmd01.denominacion');
    if($lista !=null){
        $this->concatena($lista, 'listanominas');
    }else{
        $this->set('listanominas','');
    }
	}else{
		$cehc=$this->cnmd02_tablas_grado_paso->execute("SELECT set_tabulador_sueldo_basico_todas(".$this->verifica_SS(5).");");
		var_dump($cehc);exit();
		if($cehc){
			$this->set('Message_existe','EL REGISTRO SE MODIFICO EXITOSAMENTE');
		}else{
			$this->set('errorMessage','REGISTRE UN TABULADOR');
		}
	}
    
}


}
?>