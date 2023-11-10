<?php
class Casp01AyudasController extends AppController {
   var $name = 'casp01_ayudas';
   var $uses = array('casd01_datos_personales','casd01_datos_familiares','cugd02_institucion','cugd02_dependencia',
   					'casd01_ayudas_cuerpo','casd01_ayuda_detalles','casd01_evaluacion_ayuda','casd01_solicitud_ayuda',
   					'v_historia_solicitud_ayudas','casd01_tipo_ayuda', 'v_ciad01_inventario_productos');
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
// 	 pr($this->params);
 	 $paso=explode('/',$this->params['url']['url']);
 	if($_SESSION["ATS_autorizados"][$paso[0]]==2){
 		$this->Session->write('errorMessage', 'usted no esta autorizado para operar este programa');
 		$this->redirect('modulos/vacio');
 	}
 }


function denominacion1($ayuda){

	$v=$this->casd01_tipo_ayuda->execute("select denominacion from casd01_tipo_ayuda where cod_tipo_ayuda='$ayuda'");

return $v[0][0]['denominacion'];
}


function concatena11($vector1=null, $nomVar=null){

	if($vector1 != null){

		foreach($vector1 as $x => $y){

			$cod[$x] = $this->zero($x).' - '.$this->denominacion1($y);
		}

		$this->set($nomVar, $cod);

	}
}//fin concatena



 function index(){
 	$this->layout ="ajax";

	$this->limpiar_lista();
 	$this->Session->delete('cantidad');
	$this->Session->delete('precio');

	$cedula= $this->Session->read('cedula_pestana_atencion');

	$sql="select cedula_identidad,apellidos_nombres from casd01_datos_personales where cedula_identidad='$cedula'";
	$dato=$this->casd01_datos_personales->execute($sql);
	if($dato!=null){
		$this->set('dato',$dato);

		$num_ayuda=$this->casd01_solicitud_ayuda->generateList($this->SQLCA()." and cedula_identidad=".$cedula,'cod_tipo_ayuda ASC', null, '{n}.casd01_solicitud_ayuda.numero_ocacion', '{n}.casd01_solicitud_ayuda.cod_tipo_ayuda');
		if($num_ayuda!=null){
			$this->concatena11($num_ayuda,'ayudas');
		}else{
			$this->set('ayudas',array());
		}


	}else{
		$this->set('dato','');
		$this->set('errorMessage', 'no existe una persona con esta cedula');

	}

	$num_ayuda=$this->casd01_evaluacion_ayuda->generateList($this->SQLCA()." and cedula_identidad=".$cedula." and numero_documento_ayuda is null and aprobado!=2",'numero_documento_evaluacion ASC', null, '{n}.casd01_evaluacion_ayuda.numero_documento_evaluacion', '{n}.casd01_evaluacion_ayuda.numero_documento_evaluacion');
		if($num_ayuda!=null){
			$this->set('evaluacion',$num_ayuda);
		}else{
			$this->set('evaluacion',array());
		}


	$this->set('ayu',$this->casd01_tipo_ayuda->FindAll());

	$this->data=null;
	$this->data['casp01']['disponibilidad']=null;

 }// fin index



function busqueda_cedula($cedula){
	$this->layout="ajax";

	$sql="select cedula_identidad,apellidos_nombres from casd01_datos_personales where cedula_identidad='$cedula'";
	$dato=$this->casd01_datos_personales->execute($sql);
	if($dato!=null){
		$this->set('dato',$dato);

		$num_ayuda=$this->casd01_evaluacion_ayuda->generateList($this->SQLCA()." and cedula_identidad=".$cedula." and numero_documento_ayuda is null and aprobado!=2",'numero_documento_evaluacion ASC', null, '{n}.casd01_evaluacion_ayuda.numero_documento_evaluacion', '{n}.casd01_evaluacion_ayuda.numero_documento_evaluacion');
		if($num_ayuda!=null){
			$this->set('evaluacion',$num_ayuda);
		}else{
			$this->set('evaluacion',array());
		}

		echo "<script>";
			echo "document.getElementById('num_eva').disabled=false;";
		echo "</script>";
	}else{
		$this->set('dato','');
		$this->set('errorMessage', 'no existe una persona con esta cedula');

	}

$this->set('ayu',$this->casd01_tipo_ayuda->FindAll());

}//fin busqueda_cedula


function contenido_eva($cedula=null,$evaluacion=null){
	$this->layout="ajax";
	if($evaluacion!=''){
		$sql1="select * from casd01_evaluacion_ayuda where ".$this->SQLCA()." and cedula_identidad='$cedula' and numero_documento_evaluacion='$evaluacion'";
		$dato1=$this->casd01_evaluacion_ayuda->execute($sql1);
		if($dato1!=null){
			$ver=$this->casd01_ayudas_cuerpo->execute("select * from casd01_ayudas_cuerpo where ".$this->SQLCA()." and cedula_identidad=".$cedula." order by  numero_documento_ayuda desc limit 1");
			if($ver!=null)
				$num_ayuda=$ver[0][0]['numero_documento_ayuda']+1;
			else
				$num_ayuda=1;
			$this->set('num_ayuda',$num_ayuda);
			$this->set('dato1',$dato1);
			//////////////////////aqui creo la sesion con el monto de la evaluacion para validar a la hora de los detalles
			$this->Session->delete('monto');
			$this->Session->write('monto',$dato1[0][0]['monto_aprobado']);

			$this->Session->delete('monto_aux');
			$this->Session->write('monto_aux',$dato1[0][0]['monto_aprobado']);

		}


		$this->set('monto',$dato1[0][0]['monto_aprobado']);
		$this->set('descripcion',$dato1[0][0]['evaluacion']);
		$this->limpiar_lista(1);
	}else{
		$this->set('dato1','');
		echo "<script>";
			echo "document.getElementById('cantidad').value='';";
			echo "document.getElementById('descripcion').value='';";
			echo "document.getElementById('precio').value='';";
			echo "document.getElementById('costo').value='';";
			echo "document.getElementById('disponibilidad').value='';";
			echo "document.getElementById('cantidad').readOnly='readOnly';";
			echo "document.getElementById('descripcion').readOnly='readOnly';";
			echo "document.getElementById('precio').readOnly='readOnly';";
			echo "document.getElementById('agregar').disabled='disabled';";
		echo "</script>";
	}

	echo "<script>";
			echo "document.getElementById('carga_grilla').innerHTML='';";
		echo "</script>";
	$num_ayuda=$this->casd01_evaluacion_ayuda->generateList($this->SQLCA()." and cedula_identidad=".$cedula." and numero_documento_ayuda is null and aprobado!=2",'numero_documento_evaluacion ASC', null, '{n}.casd01_evaluacion_ayuda.numero_documento_evaluacion', '{n}.casd01_evaluacion_ayuda.numero_documento_evaluacion');
	if($num_ayuda!=null){
		$this->set('evaluacion',$num_ayuda);
	}else{
		$this->set('evaluacion',array());
	}
	$this->set('cedula',$cedula);
}// fin contenido_eva



function calculos_detalles($caso=null,$var=null){
	$this->layout="ajax";
	if($caso=='cantidad'){
		$this->Session->delete('cantidad');
		$this->Session->write('cantidad',$var);
		echo "<script>";
//			echo "document.getElementById('descripcion').value='';";
//			echo "document.getElementById('precio').value='';";
			echo "document.getElementById('costo').value='';";
		echo "</script>";


		$paso = explode(',', $this->Session->read('cantidad'));
		$cantidad=$this->Session->read('cantidad');
		if($paso[1]=!''){
			$n=strlen($paso[1]);
			if($n==2){
				$cantidad=$cantidad."0";
			}
		}

		$cantidad=$this->Formato1($cantidad);
		$precio=$this->Formato1($this->Session->read('precio'));
		$costo=$cantidad*$precio;
		$monto_aprobado=$this->Session->read('monto');
		$costo=decimal_sprintf("%01.2f",$costo);
		$monto_aprobado=decimal_sprintf("%01.2f",$monto_aprobado);
		if($costo!=0){
			if($monto_aprobado>0){
				if($costo<=$monto_aprobado){
					echo "<script>";
						echo "document.getElementById('costo').value='".$this->Formato2($costo)."';";
						echo "document.getElementById('agregar').disabled=false;";
					echo "</script>";
				}else{
					 $this->set('errorMessage', 'NO SE PODRA AGREGAR YA QUE EXCEDERIA EL MONTO APROBADO');
					 echo "<script>";
					 	echo "document.getElementById('costo').value='';";
						echo "document.getElementById('agregar').disabled='disabled';";
					 echo "</script>";
				}
			}else{
				 $this->set('errorMessage', 'NO SE PODRA AGREGAR YA QUE LA AYUDA NO POSEE MAS DISPONIBILIDAD');
			}
		}




	}else if($caso=='precio'){
		$this->Session->delete('precio');
		$this->Session->write('precio',$var);


		$paso = explode(',', $this->Session->read('cantidad'));
		$cantidad=$this->Session->read('cantidad');
		$cantidad=$this->Formato1($cantidad);
		$precio=$this->Formato1($this->Session->read('precio'));
		$costo=$cantidad*$precio;
		$monto_aprobado=$this->Session->read('monto');
		$costo=decimal_sprintf("%01.2f",$costo);
		$monto_aprobado=decimal_sprintf("%01.2f",$monto_aprobado);
		if($costo!=0){
			if($monto_aprobado>0){
				if($costo<=$monto_aprobado){
					echo "<script>";
						echo "document.getElementById('costo').value='".$this->Formato2($costo)."';";
						echo "document.getElementById('agregar').disabled=false;";
					echo "</script>";
				}else{
					 $this->set('errorMessage', 'NO SE PODRA AGREGAR YA QUE EXCEDERIA EL MONTO APROBADO');
					 echo "<script>";
					 	echo "document.getElementById('costo').value='';";
						echo "document.getElementById('agregar').disabled='disabled';";
					 echo "</script>";
				}
			}else{
				 $this->set('errorMessage', 'NO SE PODRA AGREGAR YA QUE LA AYUDA NO POSEE MAS DISPONIBILIDAD');
			}
		}
	}




}//fin calculos_detalles


function agregar_grilla($var=null) {
	$this->layout="ajax";
	$cantidad=$this->Formato1($this->data['casp01']['cantidad']);
	$descripcion=$this->data['casp01']['descripcion'];
	$precio=$this->Formato1($this->data['casp01']['precio']);
	$costo=$precio*$cantidad;
	if(empty($this->data['casp01']['cantidad']) || empty($this->data['casp01']['descripcion']) || empty($this->data['casp01']['precio']) || empty($this->data['casp01']['costo'])){
		$this->set('errorMessage', 'Debe ingresar todos los datos necesarios');
		if(!isset($_SESSION["contador"])){
 			$this->set('vacio','');
 		}
		return;
	}else{
		/*$monto_aprobado=$this->Session->read('monto');
		$restante=$monto_aprobado-$costo;

		$this->Session->delete('monto');
		$this->Session->write('monto',$restante);*/
	}

		/*echo "<script>";
			echo "document.getElementById('disponibilidad').value='".$this->Formato2($this->Session->read('monto'))."';";
		echo "</script>";*/

	if(isset($_SESSION["contador"])){
        $_SESSION["contador"]=$_SESSION["contador"]+1;
	}else{
		$_SESSION["contador"]=1;
	}
	$this->Session->delete('precio');
	if(isset($var) && !empty($var)){

			$cod[0]=$cantidad;
			$cod[1]=$descripcion;
			$cod[2]=$precio;
			$cod[3]=$costo;

		    if(isset($_SESSION["i"])){
				$i=$this->Session->read("i")+1;
				$this->Session->write("i",$i);
	   		 }else{
			   $this->Session->write("i",0);
				$i=0;
			}
        switch($var){
        	case 'normal':
					 $vec[$i][0]=$cantidad;
					 $vec[$i][1]=$descripcion;
					 $vec[$i][2]=$precio;
					 $vec[$i][3]=$costo;
					 $vec[$i]["id"]=$i;
					 if(isset($_SESSION["items1"])){
					 	foreach($_SESSION["items1"] as $codi){
            	           if($codi[0]==$cod[0] && $codi[1]==$cod[1] && $codi[2]==$cod[2] && $codi[3]==$cod[3]){
                              $est=true;
                              break;
            	          }else{
            	          	 $est=false;
            	          }
                        }//fin foreach
                        if($est==true){
            	          	$i=$this->Session->read("i")-1;
				            $this->Session->write("i",$i);
				            $this->set('errorMessage', 'El detalle que intenta agregar ya existe en la lista');
                        }else{
                        	$monto_aprobado=$this->Session->read('monto');
							$restante=$monto_aprobado-$costo;

							$this->Session->delete('monto');
							$this->Session->write('monto',$restante);
                        	$_SESSION["items1"]=$_SESSION["items1"]+$vec;
                        }
					 }else{
					 	$monto_aprobado=$this->Session->read('monto');
						$restante=$monto_aprobado-$costo;

						$this->Session->delete('monto');
						$this->Session->write('monto',$restante);
						$_SESSION["items1"]=$vec;
					 }
        	break;
        }//fin switch
		}//

		echo "<script>";
			echo "document.getElementById('disponibilidad').value='".$this->Formato2($this->Session->read('monto'))."';";
		echo "</script>";

	echo'<script>';
		echo "document.getElementById('agregar').disabled='disabled';";
		echo "document.getElementById('save').disabled=false;";
		echo "document.getElementById('cantidad').value='';";
		echo "document.getElementById('descripcion').value='';";
		echo "document.getElementById('precio').value='';";
		echo "document.getElementById('costo').value='';";
 	echo'</script>';



}//fin funcu¡ions



function limpiar_lista($var=null) {
	$this->layout = "ajax";
	$this->Session->delete("items1");
	$this->Session->delete("i");
	$this->Session->delete("contador");

	$this->Session->delete('cantidad');
	$this->Session->delete('precio');

	$aux=$this->Session->read('monto_aux');

	$this->Session->delete('monto');
	$this->Session->write('monto',$aux);
if(!isset($var)){
	echo "<script>";
		echo "if(document.getElementById('disponibilidad'))document.getElementById('disponibilidad').value='".$this->Formato2($aux)."';";
		echo "document.getElementById('cantidad').value='';";
		echo "document.getElementById('descripcion').value='';";
		echo "document.getElementById('precio').value='';";
		echo "document.getElementById('costo').value='';";
	echo "</script>";
}

}



function eliminar_items ($id) {
	$this->layout = "ajax";
	$NL=0;
	foreach($_SESSION ["items1"] as $codigos){
       if($codigos['id']==$id){
       		$costo=$codigos[3];
			$NL++;
       }

	}

	$monto_aprobado=$this->Session->read('monto');
	$restante=$monto_aprobado+$costo;

	$this->Session->delete('monto');
	$this->Session->write('monto',$restante);

	echo "<script>";
		echo "document.getElementById('disponibilidad').value='".$this->Formato2($this->Session->read('monto'))."';";
	echo "</script>";


	$_SESSION["items1"][$id]=null;
	$NL=0;
	$codigos1=array();
	foreach($_SESSION ["items1"] as $codigos){
       if($codigos[$NL]['id']!=null){

       		$codigos1[$NL][0]=$codigos[0];
       		$codigos1[$NL][1]=$codigos[1];
       		$codigos1[$NL][2]=$codigos[2];
       		$codigos1[$NL][3]=$codigos[3];
       		$codigos1[$NL]['id']=$NL;
			$NL++;
       }

	}

    $_SESSION["contador"]=$_SESSION["contador"]-1;
    $_SESSION["items1"]=array();
    $_SESSION["items1"]=$codigos1;
}




function buscar_datos($var1=null){
	$this->layout="ajax";
	$this->set("opcion",$var1);
	$this->Session->delete('pista');
}//fin buscar_ficha



function buscar_por_pista($var1=null, $var2=null, $var3=null){
$this->layout="ajax";
    if($var3==null){
    	$var2 = strtoupper($var2);
					$this->Session->write('pista', $var2);
					$Tfilas=$this->casd01_datos_personales->findCount("cedula_identidad::text LIKE '%$var2%' or upper(apellidos_nombres::text) LIKE upper('%".$var2."%') ");
					        if($Tfilas!=0){
					        	$pagina=1;
					        	$Tfilas=(int)ceil($Tfilas/100);
					        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
								$this->set('total_paginas',$Tfilas);
								$this->set('pagina_actual',$pagina);
								$this->set('ultimo',$Tfilas);
					     	    $datos_filas=$this->casd01_datos_personales->findAll("cedula_identidad::text LIKE '%$var2%' or upper(apellidos_nombres::text) LIKE upper('%".$var2."%')",null,"cedula_identidad ASC",100,1,null);
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
						$Tfilas=$this->casd01_datos_personales->findCount("cedula_identidad::text LIKE '%$var22%' or upper(apellidos_nombres::text) LIKE upper('%".$var22."%')");
						        if($Tfilas!=0){
						        	$pagina=$var3;
						        	$Tfilas=(int)ceil($Tfilas/100);
						        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
									$this->set('total_paginas',$Tfilas);
									$this->set('pagina_actual',$pagina);
									$this->set('ultimo',$Tfilas);
									$datos_filas=$this->casd01_datos_personales->findAll("cedula_identidad::text LIKE '%$var2%' or upper(apellidos_nombres::text) LIKE upper('%".$var22."%')",null,"cedula_identidad ASC",100,1,null);
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


function consulta($pagina=null) {
	$this->layout="ajax";

	if(isset($pagina)){
		$Tfilas=$this->casd01_ayudas_cuerpo->findCount($this->SQLCA());
        if($Tfilas!=0){
        	$x=$this->casd01_ayudas_cuerpo->findAll($this->SQLCA(),null,"numero_documento_ayuda ASC",1,$pagina,null);

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
		$Tfilas=$this->casd01_ayudas_cuerpo->findCount($this->SQLCA());

        if($Tfilas!=0){
        	$x=$this->casd01_ayudas_cuerpo->findAll($this->SQLCA(),null,"numero_documento_ayuda ASC",1,$pagina,null);
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

	$cedula= $x[0]["casd01_ayudas_cuerpo"]["cedula_identidad"];
	$tipo_ayuda=$x[0]["casd01_ayudas_cuerpo"]["cod_tipo_ayuda"];
	$ocacion= $x[0]["casd01_ayudas_cuerpo"]["numero_ocacion"];
	$num_evaluacion= $x[0]["casd01_ayudas_cuerpo"]["numero_documento_evaluacion"];
	$num_ayuda= $x[0]["casd01_ayudas_cuerpo"]["numero_documento_ayuda"];

	$this->set('tipo_ayuda',$tipo_ayuda);
	$this->set('ocacion',$ocacion);
	$this->set('num_evaluacion',$num_evaluacion);
	$this->set('num_ayuda',$num_ayuda);
	$this->set('fecha_ayuda',$x[0]["casd01_ayudas_cuerpo"]["fecha_ayuda"]);


	$this->set('cedula',$cedula);

	$sq="SELECT * from casd01_datos_personales where cedula_identidad='$cedula'";
	$result=$this->casd01_datos_personales->execute($sq);
	$this->set('dato',$result);

	$sq1="SELECT * from casd01_evaluacion_ayuda where cedula_identidad='$cedula' and cod_tipo_ayuda='$tipo_ayuda' and numero_ocacion='$ocacion' and numero_documento_evaluacion='$num_evaluacion'";
	$result1=$this->casd01_evaluacion_ayuda->execute($sq1);
	$this->set('dato1',$result1);

	$sq3="SELECT * from casd01_ayuda_detalles where cedula_identidad='$cedula' and cod_tipo_ayuda='$tipo_ayuda' and numero_ocacion='$ocacion' and numero_documento_evaluacion='$num_evaluacion'";
	$result3=$this->casd01_ayuda_detalles->execute($sq3);
	$this->set('dato3',$result3);


$this->set('ayu',$this->casd01_tipo_ayuda->FindAll());


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




function seleccion_busqueda($opcion=null,$cedula=null){
	$this->layout="ajax";

	$sql="select cedula_identidad,apellidos_nombres from casd01_datos_personales where cedula_identidad='$cedula'";
	$dato=$this->casd01_datos_personales->execute($sql);
	if($dato!=null){
		$this->set('dato',$dato);


		$num_ayuda=$this->casd01_solicitud_ayuda->generateList($this->SQLCA()." and cedula_identidad=".$cedula,'cod_tipo_ayuda ASC', null, '{n}.casd01_solicitud_ayuda.numero_ocacion', '{n}.casd01_solicitud_ayuda.cod_tipo_ayuda');
		if($num_ayuda!=null){
			$this->concatena11($num_ayuda,'ayudas');
		}else{
			$this->set('ayudas',array());
		}

		echo "<script>";
		echo "</script>";
	}else{
		$this->set('dato','');
		$this->set('errorMessage', 'no existe una persona con esta cedula');

	}

	$num_ayuda=$this->casd01_evaluacion_ayuda->generateList($this->SQLCA()." and cedula_identidad=".$cedula." and numero_documento_ayuda is null and aprobado!=2",'numero_documento_evaluacion ASC', null, '{n}.casd01_evaluacion_ayuda.numero_documento_evaluacion', '{n}.casd01_evaluacion_ayuda.numero_documento_evaluacion');
		if($num_ayuda!=null){
			$this->set('evaluacion',$num_ayuda);
		}else{
			$this->set('evaluacion',array());
		}


$this->set('ayu',$this->casd01_tipo_ayuda->FindAll());

}// fin seleccion_busqueda




function guardar($cedula){
	$this->layout = "ajax";

	$cod_presi = $this->Session->read('SScodpresi');
    $cod_entidad = $this->Session->read('SScodentidad');
    $cod_tipo_inst = $this->Session->read('SScodtipoinst');
    $cod_inst = $this->Session->read('SScodinst');
    $cod_dep = $this->Session->read('SScoddep');

    $num_evaluacion=$this->data['casp01']['num_evaluacion'];
    $numero_ayuda=$this->data['casp01']['numero_ayuda'];
    $fecha_ayuda=$this->data['casp01']['fecha_ayuda'];

    $this->set('emitir',$this->data['casp01']['radio_formato']);

    $sql1="select * from casd01_evaluacion_ayuda where ".$this->SQLCA()." and cedula_identidad='$cedula' and numero_documento_evaluacion='$num_evaluacion'";
	$dato1=$this->casd01_evaluacion_ayuda->execute($sql1);

	$tipo_ayuda=$dato1[0][0]['cod_tipo_ayuda'];
	$ocacion=$dato1[0][0]['numero_ocacion'];


	$ver=$this->casd01_ayudas_cuerpo->execute("select * from casd01_ayudas_cuerpo where ".$this->SQLCA()." and cedula_identidad=".$cedula." order by  numero_documento_ayuda desc limit 1");
	if($ver!=null)
		$num_ayuda=$ver[0][0]['numero_documento_ayuda']+1;
	else
		$num_ayuda=1;
	$renglon=1;
	$monto=0;
	if(!empty($this->data) && isset($_SESSION ["items1"]) && ($_SESSION ["items1"]!=null || $_SESSION ["items1"]!=array())){
		foreach($_SESSION ["items1"] as $calc){
			if($calc!=null){
				$monto+=$calc[3];
			}
	     }

		if($num_ayuda==$numero_ayuda){
			$v=$this->casd01_ayudas_cuerpo->execute("select * from casd01_evaluacion_ayuda where ".$this->SQLCA()." and cedula_identidad=".$cedula." and cod_tipo_ayuda=".$tipo_ayuda." and numero_ocacion=".$ocacion." and numero_documento_evaluacion=".$num_evaluacion." order by  numero_documento_ayuda desc limit 1");
			if($monto<=$v[0][0]['monto_aprobado']){
				 $username=$this->Session->read('nom_usuario');
				 $campos=$this->casd01_datos_personales->execute("select * from usuarios where username='$username'");
				 if($campos[0][0]['cedula_identidad']!=null){
					$cedula_usuario=$campos[0][0]['cedula_identidad'];
				 }else{
					$cedula_usuario=0;
				 }
				 $funcionario=$campos[0][0]['funcionario'];

				 $sql_insert = "BEGIN;INSERT INTO casd01_ayudas_cuerpo VALUES ('$cod_presi','$cod_entidad','$cod_tipo_inst','$cod_inst','$cod_dep','$cedula', '$tipo_ayuda','$ocacion','$num_evaluacion','$num_ayuda','$monto','$fecha_ayuda','$username','$cedula_usuario','$funcionario')";
				 $sw = $this->casd01_ayudas_cuerpo->execute($sql_insert);

				 foreach($_SESSION ["items1"] as $guardar){
					if($guardar!=null){
						$sql_insert1 = "INSERT INTO casd01_ayuda_detalles VALUES('$cod_presi','$cod_entidad','$cod_tipo_inst','$cod_inst','$cod_dep','$cedula', '$tipo_ayuda','$ocacion','$num_evaluacion','$num_ayuda','$renglon','$guardar[0]','$guardar[1]','$guardar[2]')";
						$sw2 = $this->casd01_ayuda_detalles->execute($sql_insert1);
					}
				   $renglon++;
			     }

		        if($sw>1 && $sw2>1){
					$this->casd01_ayudas_cuerpo->execute("COMMIT");
					$this->set('Message_existe', 'REGISTRO EXITOSO');



					$update=$this->casd01_solicitud_ayuda->execute("update casd01_solicitud_ayuda set numero_documento_ayuda='$num_ayuda' where ".$this->SQLCA()." and cedula_identidad='$cedula' and numero_ocacion='$ocacion' and numero_documento_evaluacion='$num_evaluacion'");
					$update1=$this->casd01_evaluacion_ayuda->execute("update casd01_evaluacion_ayuda set numero_documento_ayuda='$num_ayuda' where ".$this->SQLCA()." and cedula_identidad='$cedula' and numero_ocacion='$ocacion' and numero_documento_evaluacion='$num_evaluacion'");
					if($update>1 && $update1>1){
						$this->casd01_ayudas_cuerpo->execute("COMMIT");
				 		$this->set('Message_existe', 'AYUDA REGISTRADA CON EXITO');

				 		$this->set('guardado','si');
						$this->set('set_tipo_ayuda', $tipo_ayuda);
						$this->set('set_ocacion', $ocacion);
						$this->set('set_cedula', $cedula);
						$this->set('set_num_evaluacion', $num_evaluacion);
						$this->set('set_num_ayuda', $num_ayuda);
					}


		   		}else{
		   			$this->casd01_ayudas_cuerpo->execute("ROLLBACK");
		   			$this->set('errorMessage', 'INSERCI&Oacute;N FALLIDA DE LOS DATOS');
		   		}
			}else{
				$this->set('errorMessage', 'la sumatoria de los detalles es mayor al monto aprobado');
			}
		}else{
			$this->set('errorMessage', 'INSERCI&Oacute;N FALLIDA DE LOS DATOS');
		}
	}else{
		$this->set('errorMessage', 'INSERCI&Oacute;N FALLIDA DE LOS DATOS');
	}

//	$this->index();
//	$this->render('index');

}// fin guardar

function acta_entrega(){
	$this->layout="pdf";
	$cedula=$this->data['planilla']['cedula'];
	$tipo_ayuda=$this->data['planilla']['tipo_ayuda'];
	$ocacion=$this->data['planilla']['ocacion'];
	$num_evaluacion=$this->data['planilla']['num_evaluacion'];
	$num_ayuda=$this->data['planilla']['num_ayuda'];

	$datos=$this->casd01_ayudas_cuerpo->execute("select * from v_casp01_relacion_solicitudes where ".$this->SQLCA()." and cedula_identidad=".$cedula." and cod_tipo_ayuda=".$tipo_ayuda." and numero_ocacion=".$ocacion." and numero_documento_evaluacion=".$num_evaluacion." and numero_documento_ayuda=".$num_ayuda);
	$datos2=$this->casd01_ayudas_cuerpo->execute("select * from casd01_ayuda_detalles where ".$this->SQLCA()." and cedula_identidad=".$cedula." and cod_tipo_ayuda=".$tipo_ayuda." and numero_ocacion=".$ocacion." and numero_documento_evaluacion=".$num_evaluacion." and numero_documento_ayuda=".$num_ayuda);
	$direccion=$this->casd01_ayudas_cuerpo->execute("select * from casd01_datos_personales where cedula_identidad=".$cedula);

	$this->set('datos',$datos);
	$this->set('datos2',$datos2);
	$this->set('direccion',$direccion);
}

function acta_entrega2($cod_presi=null,$cod_entidad=null,$cod_tipo_inst=null,$cod_inst=null,$cod_dep=null,$cedula=null,$tipo_ayuda=null,$ocacion=null,$num_evaluacion=null,$num_ayuda=null){
	$this->layout="pdf";
	/*$cedula=$this->data['planilla']['cedula'];
	$tipo_ayuda=$this->data['planilla']['tipo_ayuda'];
	$ocacion=$this->data['planilla']['ocacion'];
	$num_evaluacion=$this->data['planilla']['num_evaluacion'];
	$num_ayuda=$this->data['planilla']['num_ayuda'];
*/
	$datos=$this->casd01_ayudas_cuerpo->execute("select * from v_casp01_relacion_solicitudes where cod_presi=".$cod_presi." and cod_entidad=".$cod_entidad." and cod_tipo_inst=".$cod_tipo_inst." and cod_inst=".$cod_inst." and cod_dep=".$cod_dep." and cedula_identidad=".$cedula." and cod_tipo_ayuda=".$tipo_ayuda." and numero_ocacion=".$ocacion." and numero_documento_evaluacion=".$num_evaluacion." and numero_documento_ayuda=".$num_ayuda);
	$datos2=$this->casd01_ayudas_cuerpo->execute("select * from casd01_ayuda_detalles where cod_presi=".$cod_presi." and cod_entidad=".$cod_entidad." and cod_tipo_inst=".$cod_tipo_inst." and cod_inst=".$cod_inst." and cod_dep=".$cod_dep." and cedula_identidad=".$cedula." and cod_tipo_ayuda=".$tipo_ayuda." and numero_ocacion=".$ocacion." and numero_documento_evaluacion=".$num_evaluacion." and numero_documento_ayuda=".$num_ayuda);
	$direccion=$this->casd01_ayudas_cuerpo->execute("select * from casd01_datos_personales where cedula_identidad=".$cedula);

	$this->set('datos',$datos);
	$this->set('datos2',$datos2);
	$this->set('direccion',$direccion);
}

function carga_js($opcion=null,$var1=null,$var2=null){
	$this->layout="ajax";
	$this->set('denominacion',$var1);
	$this->set('monto',$var2);
}


function eliminar($cedula=null,$ocacion=null,$num_evaluacion=null,$pagina=null){
	  $this->layout = "ajax";
		  $x = $this->casd01_evaluacion_ayuda->execute("BEGIN;DELETE FROM casd01_evaluacion_ayuda  WHERE cedula_identidad='$cedula' and numero_ocacion='$ocacion' and numero_documento_evaluacion='$num_evaluacion'");
		  if($x>1){
		  	$update=$this->casd01_solicitud_ayuda->execute("update casd01_solicitud_ayuda set numero_documento_evaluacion=null where ".$this->SQLCA()." and cedula_identidad='$cedula' and numero_ocacion='$ocacion' and numero_documento_evaluacion='$num_evaluacion'");
			if($update>1){
				$this->casd01_evaluacion_ayuda->execute("COMMIT");
		 		$this->set('Message_existe', 'EVALUACIÓN ELIMINADA CON EXITO');
			}

		  }else{
		  	$this->casd01_evaluacion_ayuda->execute("ROLLBACK");
		  	$this->set('errorMessage', 'EL DATO NO PUDO SER ELIMINADO');
		  }

		if(isset($pagina) && $pagina!=null){
			$Tfilas=$this->casd01_evaluacion_ayuda->findCount($this->SQLCA());
			if($Tfilas==0){
				$this->index();
				$this->render('index');
			}else{
				$this->consulta($pagina);
				$this->render('consulta');
			}
		}else{
			$this->carga_evaluacion($cedula,$ocacion);
			$this->render('carga_evaluacion');
		}


$this->set('ayu',$this->casd01_tipo_ayuda->FindAll());


}//fin function




 function modificar($cedula=null,$ocacion=null,$num_evaluacion=null,$pagina=null){
 	 $this->layout = "ajax";

 	$sql2="select * from casd01_solicitud_ayuda where ".$this->SQLCA()." and cedula_identidad=".$cedula." and numero_ocacion=".$ocacion." and numero_documento_evaluacion=".$num_evaluacion."";
	$dato1=$this->casd01_solicitud_ayuda->execute($sql2);
	$this->set('dato1',$dato1);
    $sql_1="SELECT * from casd01_evaluacion_ayuda where ".$this->SQLCA()." and cedula_identidad='$cedula' and numero_ocacion='$ocacion' and numero_documento_evaluacion='$num_evaluacion'";
	$result_1=$this->casd01_evaluacion_ayuda->execute($sql_1);
	$this->set('dato3',$result_1);

	$num_ayuda=$this->casd01_solicitud_ayuda->generateList($this->SQLCA()." and cedula_identidad=".$cedula,'cod_tipo_ayuda ASC', null, '{n}.casd01_solicitud_ayuda.numero_ocacion', '{n}.casd01_solicitud_ayuda.cod_tipo_ayuda');
	if($num_ayuda!=null){
		$this->concatena11($num_ayuda,'ayudas');
	}else{
		$this->set('ayudas',array());
	}
	$this->set('cedula',$cedula);

	$this->set('Message_existe', 'PROCEDA A MODIFICAR LOS DATOS');

	if(isset($pagina)){
		$this->set('pagina',$pagina);
	}else{
		$this->set('pagina',null);
	}


$this->set('ayu',$this->casd01_tipo_ayuda->FindAll());

 }// fin modificar_items





function guardar_modificar($cedula=null,$ocacion1=null,$numero_eva=null,$pagina=null){
	$this->layout = "ajax";
	if(empty($this->data['casp01']['concepto_evaluacion']) || empty($this->data['casp01']['num_evaluacion']) || empty($this->data['casp01']['aprobacion']) || empty($this->data['casp01']['monto_evaluacion'])){
		$this->set('errorMessage', 'Debe ingresar los datos requeridos');
	}else {
		$num_evaluacion=$this->data['casp01']['num_evaluacion'];
		$fecha_evaluacion=$this->data['casp01']['fecha_evaluacion'];
		$concepto_evaluacion=$this->data['casp01']['concepto_evaluacion'];
		$aprobacion=$this->data['casp01']['aprobacion'];
		$monto=$this->Formato1($this->data['casp01']['monto_evaluacion']);

		$sql = "BEGIN;UPDATE casd01_evaluacion_ayuda SET numero_documento_evaluacion='$num_evaluacion',fecha_evaluacion='$fecha_evaluacion',evaluacion='$concepto_evaluacion',aprobado='$aprobacion',monto_aprobado='$monto' where ".$this->SQLCA()." and cedula_identidad=".$cedula." and numero_ocacion=".$ocacion1." and numero_documento_evaluacion=".$numero_eva."";
		$sw=$this->casd01_evaluacion_ayuda->execute($sql);
		if($sw>1){
		    $sql2 = "UPDATE casd01_solicitud_ayuda SET numero_documento_evaluacion='$num_evaluacion' where ".$this->SQLCA()." and cedula_identidad=".$cedula." and numero_ocacion=".$ocacion1." and numero_documento_evaluacion=".$numero_eva."";
			$sw2=$this->casd01_solicitud_ayuda->execute($sql2);
			if($sw2>1){
				$this->set('Message_existe','EL REGISTRO SE MODIFICO EXITOSAMENTE');
				$this->casd01_solicitud_ayuda->execute("COMMIT");
			}else{
				$this->set('errorMessage', 'LOS DATOS NO PUDIERON SER MODIFICADOS');
				$this->casd01_solicitud_ayuda->execute("ROLLBACK");
			}
		}else{
			$this->set('errorMessage', 'LOS DATOS NO PUDIERON SER MODIFICADOS');
			$this->casd01_solicitud_ayuda->execute("ROLLBACK");
		}

	}


	$sql2="select * from casd01_solicitud_ayuda where ".$this->SQLCA()." and cedula_identidad=".$cedula." and numero_ocacion=".$ocacion1."";
	$dato1=$this->casd01_solicitud_ayuda->execute($sql2);
	$this->set('dato1',$dato1);

    $sql_1="SELECT * from casd01_evaluacion_ayuda where ".$this->SQLCA()." and cedula_identidad='$cedula' and numero_ocacion='$ocacion1'";
	$result_1=$this->casd01_evaluacion_ayuda->execute($sql_1);
	$this->set('dato3',$result_1);

	$num_ayuda=$this->casd01_solicitud_ayuda->generateList($this->SQLCA()." and cedula_identidad=".$cedula,'cod_tipo_ayuda ASC', null, '{n}.casd01_solicitud_ayuda.numero_ocacion', '{n}.casd01_solicitud_ayuda.cod_tipo_ayuda');
	if($num_ayuda!=null){
		$this->concatena11($num_ayuda,'ayudas');
	}else{
		$this->set('ayudas',array());
	}
	$this->set('cedula',$cedula);

	if(isset($pagina) && $pagina!=null){
		$this->consulta($pagina);
		$this->render('consulta');
	}


$this->set('ayu',$this->casd01_tipo_ayuda->FindAll());


}//fin guardar_items_modificar



function cancelar($cedula=nulll){
    $this->layout = "ajax";

    $sql1="select * from casd01_solicitud_ayuda where cedula_identidad='$cedula' and ".$this->SQLCA()." order by cod_tipo_ayuda asc";
		$dato1=$this->casd01_solicitud_ayuda->execute($sql1);
		if($dato1!=null){
			$this->set('dato1',$dato1);
		}else{
			$this->set('dato1','');
		}

		$tipo_ayuda= array('1'=>'EFECTIVO','2'=>'MEDICAMENTOS','3'=>'ALIMENTOS Y BEBIDAS','4'=>'TRASLADOS MÉDICOS','5'=>'PASAJES',
						'6'=>'HOSPITALIZACIÓN Y CIRUGIA','7'=>'GASTOS FUNERARIOS','8'=>'SILLAS DE RUEDAS','9'=>'COMPETENCIAS DEPORTIVAS',
						'10'=>'ÚTILES ESCOLARES','11'=>'ÚTILES DEPORTIVOS','12'=>'CRÉDITOS','13'=>'EMPLEOS','14'=>'SERVICIOS PÚBLICOS');
	$this->set('tipo_ayuda',$tipo_ayuda);

	$this->set('ayu',$this->casd01_tipo_ayuda->FindAll());


	$this->data=null;
	$this->data['casp01']['concepto_ayuda']=null;

}//fin cancelar













function buscar_consulta_producto_1($var1=null){
	$this->layout="ajax";
	$this->set("opcion",$var1);
	$this->Session->delete('pista');
	$this->Session->write('pista_opcion_cargo', 1);
}//fin function





function buscar_consulta_producto_2($var1=null, $var2=null, $var3=null){
$this->layout="ajax";

    if($var3==null){
    	$var2 = strtoupper($var2);
					$this->Session->write('pista', $var2);
					$sql   = " and ".$this->busca_separado(array("denominacion"), $var2);
					$Tfilas=$this->v_ciad01_inventario_productos->findCount($this->SQLCA()." ".$sql);
					        if($Tfilas!=0){
					        	$pagina=1;
					        	$Tfilas=(int)ceil($Tfilas/100);
					        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
								$this->set('total_paginas',$Tfilas);
								$this->set('pagina_actual',$pagina);
								$this->set('ultimo',$Tfilas);
					     	    $datos_filas=$this->v_ciad01_inventario_productos->findAll($this->SQLCA()." ".$sql,null,"deno_almacen, denominacion ASC",100,1,null);
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
						$sql   = " and ".$this->busca_separado(array("denominacion"), $var22);
						$Tfilas=$this->v_ciad01_inventario_productos->findCount($this->SQLCA()." ".$sql);
						        if($Tfilas!=0){
						        	$pagina=$var3;
						        	$Tfilas=(int)ceil($Tfilas/100);
						        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
									$this->set('total_paginas',$Tfilas);
									$this->set('pagina_actual',$pagina);
									$this->set('ultimo',$Tfilas);
						     	    $datos_filas=$this->v_ciad01_inventario_productos->findAll($this->SQLCA()." ".$sql,null,"deno_almacen,denominacion ASC",100,$pagina,null);
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




 }//Fin de la clase controller
 ?>