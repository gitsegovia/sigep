<?php
class Ccnp03CensoPoblacional1Controller extends AppController {
   var $name = 'ccnp03_censo_poblacional1';
   var $uses = array('ccnd01_tipo_directivo','ccnd01_cargos_directivos',"cugd01_republica", "cugd01_estados", "cugd01_municipios",
                     "cugd01_parroquias", "cugd02_direccionsuperior", "ccnd01_concejo_comunal", "cnmd06_parentesco", "cnmd06_profesiones",
                     "cnmd06_oficio", "ccnd01_directiva_familiar", "ccnd01_directiva", "casd01_datos_familiares", "cugd10_imagenes",
					 'cugd01_vialidad','ccnd03_censo_sector','ccnd03_censo_calle','ccnd03_censo_jefe_familia','ccnd03_censo_grupo_familiar',
					 'v_ccnd03_censo_poblacional');
   var $helpers = array('Html','Ajax','Javascript', 'Sisap');

function checkSession(){
				if (!$this->Session->check('concejo_comunal')){
						$this->redirect('/salir');
						exit();
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
 }



function concatena_familia($vector1=null, $nomVar=null, $extra=null){
	$cod = array();
	if($vector1 != null){
		foreach($vector1 as $x => $y){
			if($x<10){
				$cod[$x] = '00'.$x;
			}else if($x>=10 && $x<=99){
				$cod[$x] = '0'.$x;
			}else if($x>=100 && $x<=999){
				$cod[$x] = $x;
			}
		}
		//print_r($cod);
	}

	$this->set($nomVar, $cod);
}//fin function



 function index(){
 	$this->layout ="ajax";

	$this->set('republica',$this->cugd01_republica->field('denominacion', $conditions ="cod_republica=".$this->Session->read('CC_republica'), $order ="cod_republica ASC"));
	$this->set('estado',$this->cugd01_estados->field('denominacion', $conditions ="cod_republica=".$this->Session->read('CC_republica')." and cod_estado=".$this->Session->read('CC_estado'), $order ="cod_estado ASC"));
	$this->set('municipio',$this->cugd01_municipios->field('denominacion', $conditions ="cod_republica=".$this->Session->read('CC_republica')." and cod_estado=".$this->Session->read('CC_estado')." and cod_municipio=".$this->Session->read('CC_municipio'), $order ="cod_municipio ASC"));
	$this->set('parroquia',$this->cugd01_parroquias->field('denominacion', $conditions ="cod_republica=".$this->Session->read('CC_republica')." and cod_estado=".$this->Session->read('CC_estado')." and cod_municipio=".$this->Session->read('CC_municipio')." and cod_parroquia=".$this->Session->read('CC_parroquia'), $order ="cod_parroquia ASC"));

    $condicion_dir_sup = "";
    $dir_sup=$this->cugd02_direccionsuperior->generateList($condicion_dir_sup,'denominacion ASC', null, '{n}.cugd02_direccionsuperior.cod_dir_superior', '{n}.cugd02_direccionsuperior.denominacion');
	$dir_sup = $dir_sup != null ? $dir_sup : array();
	$this->concatena($dir_sup, 'dir_superior');

	$conditions  = "     cod_republica=".$this->Session->read('CC_republica')." and cod_estado=".$this->Session->read('CC_estado')." and cod_municipio=".$this->Session->read('CC_municipio')." and cod_parroquia=".$this->Session->read('CC_parroquia')." ";
 	$conditions .= " and cod_centro=".$this->Session->read('CC_centro')." and cod_concejo=".$this->Session->read('CC_concejo')."";
    $dir_concejo=$this->ccnd01_concejo_comunal->generateList($conditions,'denominacion ASC', null, '{n}.ccnd01_concejo_comunal.cod_concejo', '{n}.ccnd01_concejo_comunal.denominacion');
	$dir_concejo = $dir_concejo != null ? $dir_concejo : array();
	$this->concatena($dir_concejo, 'concejo');


	$sql="select * from cugd01_centros_poblados where cod_republica=".$this->Session->read('CC_republica')." and cod_estado=".$this->Session->read('CC_estado')." and cod_municipio=".$this->Session->read('CC_municipio')." and cod_parroquia=".$this->Session->read('CC_parroquia')." and cod_centro=".$this->Session->read('CC_centro');
	$datos=$this->cugd01_parroquias->execute($sql);
	$this->set('datos',$datos);

	$denominacion = $this->ccnd01_concejo_comunal->field('denominacion', $conditions , null);
	$this->set('seleccion_concejo', $this->Session->read('CC_concejo'));
	$this->set('denominacion_concejo', $denominacion);

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	$cond  = " cod_republica=".$this->Session->read('CC_republica')." and cod_estado=".$this->Session->read('CC_estado')." and cod_municipio=".$this->Session->read('CC_municipio')." and cod_parroquia=".$this->Session->read('CC_parroquia')." and cod_centro=".$this->Session->read('CC_centro');
	$sector=$this->ccnd03_censo_sector->generateList($cond,'denominacion ASC', null, '{n}.ccnd03_censo_sector.cod_sector','{n}.ccnd03_censo_sector.denominacion');
	if($sector!=null){
		$this->concatena($sector,'sectores');
	}else{
		$this->set('sectores',array());
	}


	$nacionalidad= array('V'=>'Venezolana','E'=>'Extranjero');
	$this->set('nacionalidad',$nacionalidad);

	$sexo= array('M'=>'Masculino','F'=>'Femenino');
	$this->set('sexo',$sexo);

	$estado_civil= array('S'=>'Soltero(a)','C'=>'Casado(a)','V'=>'Viudo(a)','D'=>'Divorciado(a)','B'=>'Concubino(a)','O'=>'Otro');
	$this->set('estado_civil',$estado_civil);

	$this->concatena($this->cnmd06_parentesco->generateList(null,'cod_parentesco ASC', null, '{n}.cnmd06_parentesco.cod_parentesco', '{n}.cnmd06_parentesco.denominacion'),'parentesco');

	$this->limpiar_lista();
	$this->Session->delete('promotor_ced');
	$this->Session->delete('promotor_deno');


	$cod_republica = $this->Session->read('CC_republica');
 	$cod_estado    = $this->Session->read('CC_estado');
 	$cod_municipio = $this->Session->read('CC_municipio');
 	$cod_parroquia = $this->Session->read('CC_parroquia');
    $cod_centro    = $this->Session->read('CC_centro');
    $cod_concejo   = $this->Session->read('CC_concejo');

	$sql="cod_republica='$cod_republica' and cod_estado='$cod_estado' and cod_municipio='$cod_municipio' and cod_parroquia='$cod_parroquia' and cod_centro='$cod_centro'";
    $vialidad=$this->cugd01_vialidad->generateList($sql,'cod_vialidad ASC', null, '{n}.cugd01_vialidad.cod_vialidad', '{n}.cugd01_vialidad.denominacion');
	$vialidad = $vialidad != null ? $vialidad : array();
	$this->concatena($vialidad, 'vialidad');


	//////////////////sesiones//////////////////
	$this->Session->delete('salario_jefe');
	$this->Session->delete('otro_ingreso');
	$this->Session->delete('ingresos_familiares');
	$this->Session->delete('suma_total');

 }// fin index

function calles($var=null){
	$this->layout="ajax";
	if($var!=null){
		$cond  = " cod_republica=".$this->Session->read('CC_republica')." and cod_estado=".$this->Session->read('CC_estado')." and cod_municipio=".$this->Session->read('CC_municipio')." and cod_parroquia=".$this->Session->read('CC_parroquia')." and cod_centro=".$this->Session->read('CC_centro')." and cod_sector=".$var;
		$sector=$this->ccnd03_censo_sector->execute("select * from ccnd03_censo_sector where ".$cond);
		$this->set('sector',$var);
		$this->set('denominacion',$sector[0][0]['denominacion']);

		$calles=$this->ccnd03_censo_calle->generateList($cond,'denominacion ASC', null, '{n}.ccnd03_censo_calle.cod_calle','{n}.ccnd03_censo_calle.denominacion');
		if($calles!=null){
			$this->concatena($calles,'calles');
		}else{
			$this->set('calles',array());
		}

	}else{
		$this->set('calles',array());
		$this->set('denominacion','');
		$this->set('sector','');
	}


}

function calles_busqueda($var=null){
	$this->layout="ajax";
	if($var!=null){
		$cond  = " cod_republica=".$this->Session->read('CC_republica')." and cod_estado=".$this->Session->read('CC_estado')." and cod_municipio=".$this->Session->read('CC_municipio')." and cod_parroquia=".$this->Session->read('CC_parroquia')." and cod_centro=".$this->Session->read('CC_centro')." and cod_sector=".$var;

		$calles=$this->ccnd03_censo_calle->generateList($cond,'denominacion ASC', null, '{n}.ccnd03_censo_calle.cod_calle','{n}.ccnd03_censo_calle.denominacion');
		if($calles!=null){
			$this->concatena($calles,'calles');
		}else{
			$this->set('calles',array());
		}
		$this->set('sector',$var);

	}else{
		$this->set('calles',array());
		$this->set('sector','');
	}


}


function numeros_casa($sector=null,$var=null){
	$this->layout="ajax";
	if($var!=null){
		$cond  = " cod_republica=".$this->Session->read('CC_republica')." and cod_estado=".$this->Session->read('CC_estado')." and cod_municipio=".$this->Session->read('CC_municipio')." and cod_parroquia=".$this->Session->read('CC_parroquia')." and cod_centro=".$this->Session->read('CC_centro')." and cod_sector=".$sector." and cod_calle=".$var;

		$numeros=$this->ccnd03_censo_jefe_familia->generateList($cond,'numero_casa ASC', null, '{n}.ccnd03_censo_jefe_familia.numero_casa','{n}.ccnd03_censo_jefe_familia.numero_casa');
		if($numeros!=null){
//			$this->concatena($numeros,'numeros');
			$this->set('numeros',$numeros);
		}else{
			$this->set('numeros',array());
		}

	}else{
		$this->set('numeros',array());
	}


}



function deno_calles($var=null,$var2=null){
	$this->layout="ajax";
	if($var2!=null){
		$cond  = " cod_republica=".$this->Session->read('CC_republica')." and cod_estado=".$this->Session->read('CC_estado')." and cod_municipio=".$this->Session->read('CC_municipio')." and cod_parroquia=".$this->Session->read('CC_parroquia')." and cod_centro=".$this->Session->read('CC_centro')." and cod_sector=".$var." and cod_calle=".$var2;
		$calle=$this->ccnd03_censo_calle->execute("select * from ccnd03_censo_calle where ".$cond);
		$this->set('denominacion',$calle[0][0]['denominacion']);
		$this->Session->write('sector',$var);
		$this->Session->write('calle',$var2);

	}else{
		$this->set('denominacion','');
	}


}





function agregar_grilla($var=null) {
	$this->layout="ajax";
//pr($this->data);
	$paren=$this->cnmd06_parentesco->findAll();
	$this->set('paren',$paren);

	$linea=$this->data['ccnp01_directiva']['num_miembro'];

	if(empty($this->data['ccnp01_directiva']['ape_nom']) || empty($this->data['ccnp01_directiva']['fecha_nacimiento_fami']) || empty($this->data['ccnp01_directiva']['sexo_fami']) || empty($this->data['ccnp01_directiva']['estado_civil_fami']) || empty($this->data['ccnp01_directiva']['parentesco_fami']) || empty($this->data['ccnp01_directiva']['estudia'])){
		$this->set('errorMessage', 'Debe ingresar los datos requeridos');
		if(!isset($_SESSION["contador"])){
 			$this->set('vacio','');
 		}
		return;
	}

/*

	else if(empty($this->data['ccnp01_directiva']['cedula_fami']) && $this->edad($this->Cfecha($this->data['ccnp01_directiva']['fecha_nacimiento_fami'],"A-M-D"))>=15){
		$this->set('errorMessage', 'Debe ingresar la cedula del familiar');
		if(!isset($_SESSION["contador"])){
 			$this->set('vacio','');
 		}
		return;
	}


	$ci_principal=$this->data['ccnp01_directiva']['cedula_identidad'];
	if(empty($this->data['casp01']['cedula_fami']) && isset($_SESSION["items1"])){
		$vector=array();
		foreach($_SESSION ["items1"] as $xx){
			$vector[]=$xx[1];
		}
		$cedula=$this->sumar_ci($vector,$ci_principal);
	}else if(empty($this->data['casp01']['cedula_fami']) && !isset($_SESSION["items1"])){
		 $cedula=$this->dv_ci($ci_principal);

	}else if(!empty($this->data['casp01']['cedula_fami'])){
		$cedula=$this->data['casp01']['cedula_fami'];
	}

*/

		$ape_nom=$this->data['ccnp01_directiva']['ape_nom'];
		$fecha=$this->data['ccnp01_directiva']['fecha_nacimiento_fami'];
		$sexo_fami=$this->data['ccnp01_directiva']['sexo_fami'];
		$estado_civil_fami=$this->data['ccnp01_directiva']['estado_civil_fami'];
		$parentesco=$this->data['ccnp01_directiva']['parentesco_fami'];
		$estudia=$this->data['ccnp01_directiva']['estudia'];

		if(!empty($this->data['ccnp01_directiva']['cedula_fami'])){
			$cedula_fami=$this->data['ccnp01_directiva']['cedula_fami'];
	    }else{
	    	$cedula_fami=0;
	    }

	    if(!empty($this->data['ccnp01_directiva']['grado_instruccion_fami'])){
	    	$grado_instruccion_fami=$this->data['ccnp01_directiva']['grado_instruccion_fami'];
	    }else{
	    	$grado_instruccion_fami=0;
	    }
	    if(!empty($this->data['ccnp01_directiva']['ocupacion_fami'])){
	    	$ocupacion_fami=$this->data['ccnp01_directiva']['ocupacion_fami'];
	    }else{
	    	$ocupacion_fami=0;
	    }
	    if(!empty($this->data['ccnp01_directiva']['ingreso_fami'])){
	    	$ingreso_fami=$this->Formato1($this->data['ccnp01_directiva']['ingreso_fami']);
	    }else{
	    	$ingreso_fami=0;
	    }
//	    $cedula=$this->data['ccnp01_directiva']['cedula_fami'];



	if(isset($_SESSION["contador"])){
        $_SESSION["contador"]=$_SESSION["contador"]+1;
	}else{
		$_SESSION["contador"]=1;
	}

	if(isset($var) && !empty($var)){

			$cod[0]=$linea;
			$cod[1]=$ape_nom;
			$cod[2]=$cedula_fami;
			$cod[3]=$fecha;
			$cod[4]=$sexo_fami;
			$cod[5]=$estado_civil_fami;
			$cod[6]=$parentesco;
			$cod[7]=$grado_instruccion_fami;
			$cod[8]=$estudia;
			$cod[9]=$ocupacion_fami;
			$cod[10]=$ingreso_fami;


		    if(isset($_SESSION["i"])){
				$i=$this->Session->read("i")+1;
				$this->Session->write("i",$i);
	   		 }else{
			   $this->Session->write("i",0);
				$i=0;
			}
        switch($var){
        	case 'normal':
				     $vec[$i][0]=$linea;
				     $vec[$i][1]=$ape_nom;
				     $vec[$i][2]=$cedula_fami;
				     $vec[$i][3]=$fecha;
					 $vec[$i][4]=$sexo_fami;
					 $vec[$i][5]=$estado_civil_fami;
					 $vec[$i][6]=$parentesco;
  					 $vec[$i][7]=$grado_instruccion_fami;
					 $vec[$i][8]=$estudia;
					 $vec[$i][9]=$ocupacion_fami;
					 $vec[$i][10]=$ingreso_fami;
					 //echo $vec[$i][6];
					 $vec[$i]["id"]=$i;
					if(isset($_SESSION["items1"])){
						foreach($_SESSION["items1"] as $codi){
//							echo $codi[0].$cod[0];
            	           if($codi[2]==$cod[2] && $cod[2]!=''){
                              $est=true;
                              break;
            	          }else{
            	          	 $est=false;
            	          }
                        }//fin foreach
                        if($est==true){
                           //	echo "no";
            	          	$i=$this->Session->read("i")-1;
				            $this->Session->write("i",$i);
				            $this->set('errorMessage', 'Esta persona ya existe en la lista');
                        }else{
                        	$_SESSION["items1"]=$_SESSION["items1"]+$vec;
                          //  echo "si";

	                          if(!empty($this->data['ccnp01_directiva']['ingreso_fami'])){
	                          		if(isset($_SESSION['ingresos_familiares'])){
	                          			$sum_ingreso_fami=$_SESSION['ingresos_familiares']+$this->Formato1($this->data['ccnp01_directiva']['ingreso_fami']);
	                          			$this->Session->write('ingresos_familiares',$sum_ingreso_fami);
	                          		}else{
	                          			$this->Session->write('ingresos_familiares',$this->Formato1($this->data['ccnp01_directiva']['ingreso_fami']));
	                          		}

	                          		if(isset($_SESSION['suma_total'])){
	                          			$suma_total=$_SESSION['suma_total']+$this->Formato1($this->data['ccnp01_directiva']['ingreso_fami']);
	                          			$this->Session->write('suma_total',$suma_total);
	                          		}else{
	                          			$this->Session->write('suma_total',$_SESSION['ingresos_familiares']);
	                          		}

	                          		echo "<script>";
										echo "document.getElementById('total_miembros_familia').value='".$this->Formato2($_SESSION['ingresos_familiares'])."';";
										echo "document.getElementById('total_general').value='".$this->Formato2($_SESSION['suma_total'])."';";
									echo "</script>";

							  }




                        }
					 }else{
						$_SESSION["items1"]=$vec;

							if(!empty($this->data['ccnp01_directiva']['ingreso_fami'])){
	                          		if(isset($_SESSION['ingresos_familiares'])){
	                          			$sum_ingreso_fami=$_SESSION['ingresos_familiares']+$this->Formato1($this->data['ccnp01_directiva']['ingreso_fami']);
	                          			$this->Session->write('ingresos_familiares',$sum_ingreso_fami);
	                          		}else{
	                          			$this->Session->write('ingresos_familiares',$this->Formato1($this->data['ccnp01_directiva']['ingreso_fami']));
	                          		}

	                          		if(isset($_SESSION['suma_total'])){
	                          			$suma_total=$_SESSION['suma_total']+$this->Formato1($this->data['ccnp01_directiva']['ingreso_fami']);
	                          			$this->Session->write('suma_total',$suma_total);
	                          		}else{
	                          			$this->Session->write('suma_total',$_SESSION['ingresos_familiares']);
	                          		}

	                          		echo "<script>";
										echo "document.getElementById('total_miembros_familia').value='".$this->Formato2($_SESSION['ingresos_familiares'])."';";
										echo "document.getElementById('total_general').value='".$this->Formato2($_SESSION['suma_total'])."';";
									echo "</script>";

							  }
					 }

        	break;

        }//fin switch
		}//



		echo "<script>";
			echo "document.getElementById('agregar').disabled=false;";
		echo "</script>";

//pr($_SESSION["items1"]);
}//fin funcu¡ions



function limpiar_lista () {
	$this->layout = "ajax";
	if(isset($_SESSION['suma_total']) && isset($_SESSION['ingresos_familiares'])){
			$resta=$_SESSION['suma_total']-$_SESSION['ingresos_familiares'];
			$this->Session->write('suma_total',$resta);
			echo "<script>document.getElementById('total_miembros_familia').value='';</script>";
			echo "<script>document.getElementById('total_general').value='".$this->Formato2($_SESSION['suma_total'])."';</script>";

	}


	echo "<script>document.getElementById('num_miembro').value=1;</script>";

	$this->Session->delete("items1");
	$this->Session->delete("i");
	$this->Session->delete("contador");
	$this->Session->delete("ingresos_familiares");
}



function eliminar_items ($id,$monto=null) {
	$this->layout = "ajax";
	$paren=$this->cnmd06_parentesco->findAll();
	$this->set('paren',$paren);
	if($monto!=null){
		$monto=$this->Formato1($monto);
		$resta1=$_SESSION['ingresos_familiares']-$monto;
		$this->Session->write('ingresos_familiares',$resta1);

		$resta=$_SESSION['suma_total']-$monto;
		$this->Session->write('suma_total',$resta);

		echo "<script>document.getElementById('total_miembros_familia').value='".$this->Formato2($_SESSION['ingresos_familiares'])."';</script>";
		echo "<script>document.getElementById('total_general').value='".$this->Formato2($_SESSION['suma_total'])."';</script>";

	}
	$_SESSION["items1"][$id]=null;
	$NL=1;
	$codigos1=array();
	foreach($_SESSION ["items1"] as $codigos){
       if($codigos[0]!=null){

       		$codigos1[$NL][0]=$NL;
       		$codigos1[$NL][1]=$codigos[1];
       		$codigos1[$NL][2]=$codigos[2];
       		$codigos1[$NL][3]=$codigos[3];
       		$codigos1[$NL][4]=$codigos[4];
       		$codigos1[$NL][5]=$codigos[5];
       		$codigos1[$NL][6]=$codigos[6];
       		$codigos1[$NL][7]=$codigos[7];
       		$codigos1[$NL][8]=$codigos[8];
       		$codigos1[$NL][9]=$codigos[9];
       		$codigos1[$NL][10]=$codigos[10];
       		$codigos1[$NL]['id']=$NL;
			$NL++;
       }

	}

    $_SESSION["contador"]=$_SESSION["contador"]-1;
    $_SESSION["items1"]=array();
    $_SESSION["items1"]=$codigos1;
}





function calculos($var,$var2){
	$this->layout="ajax";
	$this->set('var',$var);
	if($var==1){
				$var2=$this->Formato1($var2);

				if(isset($_SESSION['salario_jefe'])){
					$resta=$_SESSION['suma_total']-$_SESSION['salario_jefe'];
					$this->Session->write('suma_total',$resta);
				}

				$this->Session->write('salario_jefe',$this->Formato1($var2));
				if(isset($_SESSION['otro_ingreso'])){
					$suma=$var2+$_SESSION['otro_ingreso'];
				}else{
					$suma=$var2;
				}

				if(isset($_SESSION['suma_total'])){
          			$suma_total=$_SESSION['suma_total']+$var2;
          			$this->Session->write('suma_total',$suma_total);
          		}else{
          			$this->Session->write('suma_total',$var2);
          		}

				echo "<script>";
					echo "document.getElementById('total_jefe_familia').value='".$this->Formato2($suma)."';";
					echo "document.getElementById('total_general').value='".$this->Formato2($_SESSION['suma_total'])."';";
				echo "</script>";


	}else if($var==2){
				$var2=$this->Formato1($var2);

				if(isset($_SESSION['otro_ingreso'])){
					$resta=$_SESSION['suma_total']-$_SESSION['otro_ingreso'];
					$this->Session->write('suma_total',$resta);
				}

				$this->Session->write('otro_ingreso',$this->Formato1($var2));


				if(isset($_SESSION['salario_jefe'])){
					$suma=$var2+$_SESSION['salario_jefe'];
				}else{
					$suma=$var2;
				}

				if(isset($_SESSION['suma_total'])){
          			$suma_total=$_SESSION['suma_total']+$var2;
          			$this->Session->write('suma_total',$suma_total);
          		}else{
          			$this->Session->write('suma_total',$var2);
          		}

				echo "<script>";
					echo "document.getElementById('total_jefe_familia').value='".$this->Formato2($suma)."';";
					echo "document.getElementById('total_general').value='".$this->Formato2($_SESSION['suma_total'])."';";
				echo "</script>";

	}else if($var==3){
		//Aqui para verificar que el numero de casa ya no este registrado en la misma direccion
		$sector=$this->Session->read('sector');
		$calle=$this->Session->read('calle');

		$cond  = " cod_republica=".$this->Session->read('CC_republica')." and cod_estado=".$this->Session->read('CC_estado')." and cod_municipio=".$this->Session->read('CC_municipio')." and cod_parroquia=".$this->Session->read('CC_parroquia')." and cod_centro=".$this->Session->read('CC_centro')." and cod_sector=".$sector." and cod_calle=".$calle." and numero_casa='".$var2."'";
		$calle=$this->ccnd03_censo_jefe_familia->execute("select * from ccnd03_censo_jefe_familia where ".$cond);
		if($calle!=null){
			$this->set('errorMessage', 'el número de casa o parcela ya existe registrado');
		}else{

		}


	}

}




function guardar($nomina=null,$cargo=null,$ficha=null) {
 	$this->layout = "ajax";
 	$cod_republica = $this->Session->read('CC_republica');
 	$cod_estado    = $this->Session->read('CC_estado');
 	$cod_municipio = $this->Session->read('CC_municipio');
 	$cod_parroquia = $this->Session->read('CC_parroquia');
    $cod_centro    = $this->Session->read('CC_centro');
    $cod_concejo   = $this->Session->read('CC_concejo');

    $cod_sector=$this->data['ccnp01_directiva']['cod_sector'];
    $cod_calle=$this->data['ccnp01_directiva']['cod_calle'];
    $nro_casa_parcela=$this->data['ccnp01_directiva']['nro_casa_parcela'];

    $this->set('cod_sector',$cod_sector);
    $this->set('cod_calle',$cod_calle);
    $this->set('nro_casa_parcela',$nro_casa_parcela);
    if(!empty($this->data['ccnp01_directiva']['punto_referencia'])){
    	$punto_referencia=$this->data['ccnp01_directiva']['punto_referencia'];
    }else{
    	$punto_referencia=0;
    }

     if(!empty($this->data['ccnp01_directiva']['telefono'])){
    	$telefono_vivienda=$this->data['ccnp01_directiva']['telefono'];
    }else{
    	$telefono_vivienda=0;
    }
    $nacionalidad=$this->data['ccnp01_directiva']['nacionalidad'];

    $cedula_identidad=$this->data['ccnp01_directiva']['cedula_identidad'];
    $nombres=$this->data['ccnp01_directiva']['nombres'];
    $apellidos=$this->data['ccnp01_directiva']['apellidos'];
    $estado_civil=$this->data['ccnp01_directiva']['estado_civil'];
    $sexo=$this->data['ccnp01_directiva']['sexo'];
    $fecha_nacimiento=$this->data['ccnp01_directiva']['fecha_nacimiento'];
    if(!empty($this->data['ccnp01_directiva']['grado_instruccion'])){
    	$grado_instruccion=$this->data['ccnp01_directiva']['grado_instruccion'];
    }else{
    	$grado_instruccion=0;
    }
    if(!empty($this->data['ccnp01_directiva']['oficio_profesion'])){
    	$oficio_profesion=$this->data['ccnp01_directiva']['oficio_profesion'];
    }else{
    	$oficio_profesion=0;
    }
    if(!empty($this->data['ccnp01_directiva']['ocupacion_actual'])){
    	$ocupacion_actual=$this->data['ccnp01_directiva']['ocupacion_actual'];
    }else{
    	$ocupacion_actual=0;
    }
    if(!empty($this->data['ccnp01_directiva']['donde_trabaja_top'])){
    	$donde_trabaja_top=$this->data['ccnp01_directiva']['donde_trabaja_top'];
    }else{
    	$donde_trabaja_top=0;
    }
    if(!empty($this->data['ccnp01_directiva']['cargo'])){
    	$cargo=$this->data['ccnp01_directiva']['cargo'];
    }else{
    	$cargo=0;
    }
    if(!empty($this->data['ccnp01_directiva']['fecha_ingreso'])){
    	$fecha_ingreso=$this->data['ccnp01_directiva']['fecha_ingreso'];
    }else{
    	$fecha_ingreso='1900-1-1';
    }
    if(!empty($this->data['ccnp01_directiva']['sueldo_salario'])){
    	$sueldo_salario=$this->Formato1($this->data['ccnp01_directiva']['sueldo_salario']);
    }else{
    	$sueldo_salario=0;
    }
    if(!empty($this->data['ccnp01_directiva']['otros_ingresos'])){
    	$otros_ingresos=$this->Formato1($this->data['ccnp01_directiva']['otros_ingresos']);
    }else{
    	$otros_ingresos=0;
    }
	if(!empty($this->data['ccnp01_directiva']['direccion_laboral'])){
    	$direccion_laboral=$this->data['ccnp01_directiva']['direccion_laboral'];
    }else{
    	$direccion_laboral=0;
    }
    if(!empty($this->data['ccnp01_directiva']['supervisor_inmediato'])){
    	$supervisor_inmediato=$this->data['ccnp01_directiva']['supervisor_inmediato'];
    }else{
    	$supervisor_inmediato=0;
    }
    if(!empty($this->data['ccnp01_directiva']['telefonos_trabajo'])){
    	$telefonos_trabajo=$this->data['ccnp01_directiva']['telefonos_trabajo'];
    }else{
    	$telefonos_trabajo=0;
    }

    $donde_trabaja=$this->data['ccnp01_directiva']['donde_trabaja'];
    if($this->data['ccnp01_directiva']['donde_trabaja']==6){
    	$otro_tipo_trabajo=$this->data['ccnp01_directiva']['otro_tipo_trabajo'];
    }else{
    	$otro_tipo_trabajo=0;
    }

    $actividad_comercial_vivienda=$this->data['ccnp01_directiva']['actividad_comercial_vivienda'];
    if($this->data['ccnp01_directiva']['actividad_comercial_vivienda']==2){
    	$cual_tipo_actividad="";
		for($i=1;$i<9;$i++){
			$cual_tipo_actividad.=$this->data['ccnp01_directiva']['venta_de'.$i];
		}
    }else{
    	$cual_tipo_actividad='00000000';
    }

    $ingreso_familiar=$this->data['ccnp01_directiva']['ingreso_familiar'];
    $cuenta_bancaria=$this->data['ccnp01_directiva']['cuenta_bancaria'];
    $tarjeta_credito=$this->data['ccnp01_directiva']['tarjeta_credito'];
    $cesta_ticket=$this->data['ccnp01_directiva']['cesta_ticket'];

    $tipo_vivienda=$this->data['ccnp01_directiva']['tipo_vivienda'];
    $forma_tenencia=$this->data['ccnp01_directiva']['forma_tenencia'];
    $terreno_propio=$this->data['ccnp01_directiva']['terreno_propio'];
    $tipo_paredes=$this->data['ccnp01_directiva']['tipo_paredes'];
    $tipo_techo=$this->data['ccnp01_directiva']['tipo_techo'];

	$enseres_vivienda="";
	for($i=1;$i<12;$i++){
		$enseres_vivienda.=$this->data['ccnp01_directiva']['enseres_vivienda'.$i];
	}

    $condicion_salubridad=$this->data['ccnp01_directiva']['condicion_salubridad'];
    $pertenece_ocv=$this->data['ccnp01_directiva']['pertenece_ocv'];
    $presencia_insectos=$this->data['ccnp01_directiva']['presencia_insectos'];
    if($this->data['ccnp01_directiva']['presencia_insectos']==1){
    	$tipo_insectos_roedores="";
		for($i=1;$i<7;$i++){
			$tipo_insectos_roedores.=$this->data['ccnp01_directiva']['insectos'.$i];
		}
    }else{
    	$tipo_insectos_roedores='000000';
    }

    $animales_domesticos=$this->data['ccnp01_directiva']['animales_domesticos'];
    if($this->data['ccnp01_directiva']['animales_domesticos']==1){
    	$tipo_animales_domesticos="";
		for($i=1;$i<8;$i++){
			$tipo_animales_domesticos.=$this->data['ccnp01_directiva']['animales'.$i];
		}
    }else{
    	$tipo_animales_domesticos='0000000';
    }

    $familiares_padecen_enfermedades="";
	for($i=1;$i<10;$i++){
		$familiares_padecen_enfermedades.=$this->data['ccnp01_directiva']['enfermedad'.$i];
	}

	$requeriere_ayuda_especial=$this->data['ccnp01_directiva']['requeriere_ayuda_especial'];
    if($this->data['ccnp01_directiva']['requeriere_ayuda_especial']==1){
    	$cual_ayuda_especial=$this->data['ccnp01_directiva']['cual_ayuda_especial'];
    }else{
    	$cual_ayuda_especial=0;
    }


    if(!empty($this->data['ccnp01_directiva']['ninos_calle'])){
    	$ninos_calle=$this->data['ccnp01_directiva']['ninos_calle'];
    }else{
    	$ninos_calle=0;
    }
    if(!empty($this->data['ccnp01_directiva']['indigenas'])){
    	$indigenas=$this->data['ccnp01_directiva']['indigenas'];
    }else{
    	$indigenas=0;
    }
    if(!empty($this->data['ccnp01_directiva']['enfermos_terminales'])){
    	$enfermos_terminales=$this->data['ccnp01_directiva']['enfermos_terminales'];
    }else{
    	$enfermos_terminales=0;
    }
    if(!empty($this->data['ccnp01_directiva']['discapacitados'])){
    	$discapacitados=$this->data['ccnp01_directiva']['discapacitados'];
    }else{
    	$discapacitados=0;
    }
    if(!empty($this->data['ccnp01_directiva']['tercera_edad'])){
    	$tercera_edad=$this->data['ccnp01_directiva']['tercera_edad'];
    }else{
    	$tercera_edad=0;
    }
    if(!empty($this->data['ccnp01_directiva']['situacion_exclusion_otros'])){
    	$situacion_exclusion_otros=$this->data['ccnp01_directiva']['situacion_exclusion_otros'];
    }else{
    	$situacion_exclusion_otros=0;
    }

    $aguas_blancas=$this->data['ccnp01_directiva']['aguas_blancas'];
    $aguas_blancas_medidor=$this->data['ccnp01_directiva']['aguas_blancas_medidor'];
    $aguas_servidas=$this->data['ccnp01_directiva']['aguas_servidas'];
    $gas=$this->data['ccnp01_directiva']['gas'];
    $electrificado=$this->data['ccnp01_directiva']['electrificado'];
    $tiene_medidor_electricidad=$this->data['ccnp01_directiva']['tiene_medidor_electricidad'];
    $basura=$this->data['ccnp01_directiva']['basura'];

    $telefonia="";
	for($i=1;$i<6;$i++){
		$telefonia.=$this->data['ccnp01_directiva']['telefonia'.$i];
	}

	$transporte="";
	for($i=1;$i<5;$i++){
		$transporte.=$this->data['ccnp01_directiva']['transporte'.$i];
	}

	$mecanismo_informacion="";
	for($i=1;$i<7;$i++){
		$mecanismo_informacion.=$this->data['ccnp01_directiva']['mecanismo_informacion'.$i];
	}

	$servicios_comunales="";
	for($i=1;$i<14;$i++){
		$servicios_comunales.=$this->data['ccnp01_directiva']['servicios_comunales'.$i];
	}

	$organizaciones_comunitarias=$this->data['ccnp01_directiva']['organizaciones_comunitarias'];
    if($this->data['ccnp01_directiva']['organizaciones_comunitarias']==1){
    	$participa_organizacion_comunitaria=$this->data['ccnp01_directiva']['participa_organizacion_comunitaria'];
    	$participa_organizacion_comunitaria_miembro=$this->data['ccnp01_directiva']['participa_organizacion_comunitaria_miembro'];
    }else{
    	$participa_organizacion_comunitaria=0;
    	$participa_organizacion_comunitaria_miembro=0;
    }

    $cuales_misiones="";
	for($i=1;$i<9;$i++){
		$cuales_misiones.=$this->data['ccnp01_directiva']['cuales_misiones'.$i];
	}

    if($this->data['ccnp01_directiva']['cuales_misiones8']==1){
    	$cual_otra_mision=$this->data['ccnp01_directiva']['cual_otra_mision'];
    }else{
    	$cual_otra_mision=0;
    }

    $interviene_repartir_recursos=$this->data['ccnp01_directiva']['interviene_repartir_recursos'];
    $protagonismo_presupuestario=$this->data['ccnp01_directiva']['protagonismo_presupuestario'];

    $informacion_creacion_consejo=$this->data['ccnp01_directiva']['informacion_creacion_consejo'];
    if($this->data['ccnp01_directiva']['informacion_creacion_consejo']==1){
    	$como_obtuvo=$this->data['ccnp01_directiva']['como_obtuvo'];
    }else{
    	$como_obtuvo=0;
    }

    $participar_creacion_consejo=$this->data['ccnp01_directiva']['participar_creacion_consejo'];

    $area_gustaria_participar="";
	for($i=1;$i<12;$i++){
		$area_gustaria_participar.=''.$this->data['ccnp01_directiva']['realizarse_consejo_comunal'.$i].'';
	}


    if(!empty($this->data['ccnp01_directiva']['principales_potencialidades'])){
    	$principales_potencialidades=$this->data['ccnp01_directiva']['principales_potencialidades'];
    }else{
    	$principales_potencialidades=0;
    }
    if(!empty($this->data['ccnp01_directiva']['principales_problemas'])){
    	$principales_problemas=$this->data['ccnp01_directiva']['principales_problemas'];
    }else{
    	$principales_problemas=0;
    }

//    pr($this->data);


     $sql = "BEGIN;INSERT INTO ccnd03_censo_jefe_familia VALUES (
     		'$cod_republica', '$cod_estado', '$cod_municipio', '$cod_parroquia', '$cod_centro', '$cod_concejo',
     		'$cod_sector','$cod_calle','$nro_casa_parcela','$punto_referencia','$telefono_vivienda','$nacionalidad',
     		'$cedula_identidad','$nombres','$apellidos','$estado_civil','$sexo','$fecha_nacimiento','$grado_instruccion',
     		'$oficio_profesion','$ocupacion_actual','$donde_trabaja_top','$cargo','$fecha_ingreso','$sueldo_salario',
     		'$otros_ingresos','$direccion_laboral','$supervisor_inmediato','$telefonos_trabajo','$donde_trabaja','$otro_tipo_trabajo',
     		'$actividad_comercial_vivienda','$cual_tipo_actividad','$ingreso_familiar','$cuenta_bancaria','$tarjeta_credito',
     		'$cesta_ticket','$tipo_vivienda','$forma_tenencia','$terreno_propio','$tipo_paredes','$tipo_techo',
     		'$enseres_vivienda','$condicion_salubridad','$pertenece_ocv','$presencia_insectos','$tipo_insectos_roedores',
     		'$animales_domesticos','$tipo_animales_domesticos','$familiares_padecen_enfermedades','$requeriere_ayuda_especial',
     		'$cual_ayuda_especial','$ninos_calle','$indigenas','$enfermos_terminales','$discapacitados','$tercera_edad',
     		'$situacion_exclusion_otros','$aguas_blancas','$aguas_blancas_medidor','$aguas_servidas','$gas','$electrificado',
     		'$tiene_medidor_electricidad','$basura','$telefonia','$transporte','$mecanismo_informacion','$servicios_comunales',
     		'$organizaciones_comunitarias','$participa_organizacion_comunitaria','$participa_organizacion_comunitaria_miembro',
     		'$cuales_misiones','$cual_otra_mision','$interviene_repartir_recursos','$protagonismo_presupuestario',
     		'$informacion_creacion_consejo','$como_obtuvo','$participar_creacion_consejo','$area_gustaria_participar',
     		'$principales_potencialidades','$principales_problemas')";
	   	 $sw=$this->ccnd03_censo_jefe_familia->execute($sql);
				foreach($_SESSION ["items1"] as $guardar){
					if($guardar!=null){
							$sql_insert = "INSERT INTO ccnd03_censo_grupo_familiar VALUES('$cod_republica', '$cod_estado', '$cod_municipio', '$cod_parroquia', '$cod_centro', '$cod_concejo','$cod_sector','$cod_calle','$nro_casa_parcela','$guardar[0]', '$guardar[1]', '$guardar[2]', '$guardar[3]','$guardar[4]','$guardar[5]','$guardar[6]','$guardar[7]','$guardar[8]','$guardar[9]','$guardar[10]')";
							$sw2 = $this->ccnd03_censo_grupo_familiar->execute($sql_insert);
					}
			     }
			if($sw>1 && $sw2>1){
				$this->ccnd03_censo_jefe_familia->execute("COMMIT");
				$this->set('Message_existe', 'REGISTRO EXITOSO');
				$this->set('guardado', 'si');

	   		}else{
	   			$this->ccnd03_censo_jefe_familia->execute("ROLLBACK");
	   			$this->set('guardado', 'no');
	   			$this->set('errorMessage', 'INSERCI&Oacute;N FALLIDA DE LOS DATOS');
	   		}

}




function guardar_items($var=null) {
	$this->layout="ajax";

	$cod_republica = $this->Session->read('CC_republica');
 	$cod_estado    = $this->Session->read('CC_estado');
 	$cod_municipio = $this->Session->read('CC_municipio');
 	$cod_parroquia = $this->Session->read('CC_parroquia');
    $cod_centro    = $this->Session->read('CC_centro');
    $cod_concejo   = $this->Session->read('CC_concejo');

    $cod_sector=$this->data['ccnp01_directiva']['cod_sector'];
    $cod_calle=$this->data['ccnp01_directiva']['cod_calle'];
    $nro_casa_parcela=$this->data['ccnp01_directiva']['nro_casa_parcela'];

	$paren=$this->cnmd06_parentesco->findAll();
	$this->set('paren',$paren);

	$linea=$this->data['ccnp01_directiva']['num_miembro'];


	if(empty($this->data['ccnp01_directiva']['ape_nom']) || empty($this->data['ccnp01_directiva']['fecha_nacimiento_fami']) || empty($this->data['ccnp01_directiva']['sexo_fami']) || empty($this->data['ccnp01_directiva']['estado_civil_fami']) || empty($this->data['ccnp01_directiva']['parentesco_fami']) || empty($this->data['ccnp01_directiva']['estudia'])){
		$this->set('errorMessage', 'Debe ingresar los datos requeridos');
		$conditions1  = "cod_republica=".$this->Session->read('CC_republica')." and cod_estado=".$this->Session->read('CC_estado')." and cod_municipio=".$this->Session->read('CC_municipio')." and cod_parroquia=".$this->Session->read('CC_parroquia')." and cod_centro=".$this->Session->read('CC_centro')." and cod_concejo=".$this->Session->read('CC_concejo')." and cod_sector=".$cod_sector." and cod_calle=".$cod_calle." and numero_casa='".$nro_casa_parcela."'";
		$sql_1="SELECT * from ccnd03_censo_grupo_familiar where ".$conditions1." and 1=1 order by numero_parentesco asc";
		$result_1=$this->ccnd03_censo_grupo_familiar->execute($sql_1);
		$this->set('fami',$result_1);
		$this->set('linea',$linea-1);
		return;
	}



		$ape_nom=$this->data['ccnp01_directiva']['ape_nom'];
		$fecha=$this->data['ccnp01_directiva']['fecha_nacimiento_fami'];
		$sexo_fami=$this->data['ccnp01_directiva']['sexo_fami'];
		$estado_civil_fami=$this->data['ccnp01_directiva']['estado_civil_fami'];
		$parentesco=$this->data['ccnp01_directiva']['parentesco_fami'];
		$estudia=$this->data['ccnp01_directiva']['estudia'];

		if(!empty($this->data['ccnp01_directiva']['cedula_fami'])){
			$cedula_fami=$this->data['ccnp01_directiva']['cedula_fami'];
	    }else{
	    	$cedula_fami=0;
	    }

	    if(!empty($this->data['ccnp01_directiva']['grado_instruccion_fami'])){
	    	$grado_instruccion_fami=$this->data['ccnp01_directiva']['grado_instruccion_fami'];
	    }else{
	    	$grado_instruccion_fami=0;
	    }
	    if(!empty($this->data['ccnp01_directiva']['ocupacion_fami'])){
	    	$ocupacion_fami=$this->data['ccnp01_directiva']['ocupacion_fami'];
	    }else{
	    	$ocupacion_fami=0;
	    }
	    if(!empty($this->data['ccnp01_directiva']['ingreso_fami'])){
	    	$ingreso_fami=$this->Formato1($this->data['ccnp01_directiva']['ingreso_fami']);
	    }else{
	    	$ingreso_fami=0;
	    }
//	    $cedula=$this->data['ccnp01_directiva']['cedula_fami'];
		if(!empty($this->data['ccnp01_directiva']['cedula_fami'])){
			$conditions1  = "cod_republica=".$this->Session->read('CC_republica')." and cod_estado=".$this->Session->read('CC_estado')." and cod_municipio=".$this->Session->read('CC_municipio')." and cod_parroquia=".$this->Session->read('CC_parroquia')." and cod_centro=".$this->Session->read('CC_centro')." and cod_concejo=".$this->Session->read('CC_concejo')." and cod_sector=".$cod_sector." and cod_calle=".$cod_calle." and numero_casa='".$nro_casa_parcela."' and cedula_identidad=".$cedula_fami;
	    	$verifica=$this->ccnd03_censo_grupo_familiar->FindCount($conditions1);
		}else{
			$verifica=0;
		}


		if($verifica==0){
			$sql="INSERT INTO ccnd03_censo_grupo_familiar VALUES ('$cod_republica','$cod_estado','$cod_municipio','$cod_parroquia','$cod_centro',
            '$cod_concejo','$cod_sector','$cod_calle','$nro_casa_parcela','$linea','$ape_nom','$cedula_fami','$fecha','$sexo_fami',
            '$estado_civil_fami','$parentesco','$grado_instruccion_fami','$estudia','$ocupacion_fami','$ingreso_fami');";

            $sw=$this->ccnd03_censo_jefe_familia->execute($sql);
			if($sw>1){
				$this->set('Message_existe', 'FAMILIAR REGISTRADO CON EXITOSO');

						if(!empty($this->data['ccnp01_directiva']['ingreso_fami'])){
			          		if(isset($_SESSION['ingresos_familiares'])){
			          			$sum_ingreso_fami=$_SESSION['ingresos_familiares']+$this->Formato1($this->data['ccnp01_directiva']['ingreso_fami']);
			          			$this->Session->write('ingresos_familiares',$sum_ingreso_fami);
			          		}else{
			          			$this->Session->write('ingresos_familiares',$this->Formato1($this->data['ccnp01_directiva']['ingreso_fami']));
			          		}

			          		if(isset($_SESSION['suma_total'])){
			          			$suma_total=$_SESSION['suma_total']+$this->Formato1($this->data['ccnp01_directiva']['ingreso_fami']);
			          			$this->Session->write('suma_total',$suma_total);
			          		}else{
			          			$this->Session->write('suma_total',$_SESSION['ingresos_familiares']);
			          		}

			          		echo "<script>";
								echo "document.getElementById('total_miembros_familia').value='".$this->Formato2($_SESSION['ingresos_familiares'])."';";
								echo "document.getElementById('total_general').value='".$this->Formato2($_SESSION['suma_total'])."';";
							echo "</script>";

					  }
					  $this->set('linea',$linea);

	   		}else{
	   			$this->set('errorMessage', 'INSERCI&Oacute;N FALLIDA');
	   			$this->set('linea',$linea-1);
	   		}
		}else{
			$this->set('errorMessage', 'EL FAMILIAR QUE DESEA AGREGAR YA EXISTE EN LA LISTA');
			$this->set('linea',$linea-1);
		}


		$conditions1  = "cod_republica=".$this->Session->read('CC_republica')." and cod_estado=".$this->Session->read('CC_estado')." and cod_municipio=".$this->Session->read('CC_municipio')." and cod_parroquia=".$this->Session->read('CC_parroquia')." and cod_centro=".$this->Session->read('CC_centro')." and cod_concejo=".$this->Session->read('CC_concejo')." and cod_sector=".$cod_sector." and cod_calle=".$cod_calle." and numero_casa='".$nro_casa_parcela."'";
		$sql_1="SELECT * from ccnd03_censo_grupo_familiar where ".$conditions1." and 1=1 order by numero_parentesco asc";
		$result_1=$this->ccnd03_censo_grupo_familiar->execute($sql_1);
		$this->set('fami',$result_1);



}



function eliminar_familiar($sector=null,$calle=null,$numero_casa=null,$numero_miembro=null,$monto=null){
	$this->layout = "ajax";
	$paren=$this->cnmd06_parentesco->findAll();
	$this->set('paren',$paren);
    $conditions  = "cod_republica=".$this->Session->read('CC_republica')." and cod_estado=".$this->Session->read('CC_estado')." and cod_municipio=".$this->Session->read('CC_municipio')." and cod_parroquia=".$this->Session->read('CC_parroquia')." and cod_centro=".$this->Session->read('CC_centro')." and cod_concejo=".$this->Session->read('CC_concejo')." and cod_sector=".$sector." and cod_calle=".$calle." and numero_casa='".$numero_casa."'";
    $conditions1  = "cod_republica=".$this->Session->read('CC_republica')." and cod_estado=".$this->Session->read('CC_estado')." and cod_municipio=".$this->Session->read('CC_municipio')." and cod_parroquia=".$this->Session->read('CC_parroquia')." and cod_centro=".$this->Session->read('CC_centro')." and cod_concejo=".$this->Session->read('CC_concejo')." and cod_sector=".$sector." and cod_calle=".$calle." and numero_casa='".$numero_casa."' and numero_parentesco=".$numero_miembro;

    $x2 = $this->ccnd03_censo_grupo_familiar->execute("DELETE FROM ccnd03_censo_grupo_familiar  WHERE ".$conditions1);
    $this->set('Message_existe','familiar eliminado con exito');

    if($monto!=0){
		$monto=$this->Formato1($monto);
		$resta1=$_SESSION['ingresos_familiares']-$monto;
		$this->Session->write('ingresos_familiares',$resta1);

		$resta=$_SESSION['suma_total']-$monto;
		$this->Session->write('suma_total',$resta);

		echo "<script>document.getElementById('total_miembros_familia').value='".$this->Formato2($_SESSION['ingresos_familiares'])."';</script>";
		echo "<script>document.getElementById('total_general').value='".$this->Formato2($_SESSION['suma_total'])."';</script>";

	}

	$sql_1="SELECT * from ccnd03_censo_grupo_familiar where ".$conditions." and 1=1 order by numero_parentesco asc";
	$result_1=$this->ccnd03_censo_grupo_familiar->execute($sql_1);
	$k=1;
//	pr($result_1);
	for($i=0;$i<count($result_1);$i++){
		$conditions2=$conditions." and numero_parentesco=".$result_1[$i][0]['numero_parentesco'];
		$sql = "BEGIN;UPDATE ccnd03_censo_grupo_familiar SET numero_parentesco='$k' where ".$conditions2;
		$sw=$this->ccnd03_censo_grupo_familiar->execute($sql);
		$k++;
	}
//	echo "<br>".$k;
	$this->set('k',$k);
	if($sw>1){
		$sw=$this->ccnd03_censo_grupo_familiar->execute('COMMIT');
	}else{
		$sw=$this->ccnd03_censo_grupo_familiar->execute('ROLLBACK');
	}

	$conditions1  = "cod_republica=".$this->Session->read('CC_republica')." and cod_estado=".$this->Session->read('CC_estado')." and cod_municipio=".$this->Session->read('CC_municipio')." and cod_parroquia=".$this->Session->read('CC_parroquia')." and cod_centro=".$this->Session->read('CC_centro')." and cod_concejo=".$this->Session->read('CC_concejo')." and cod_sector=".$sector." and cod_calle=".$calle." and numero_casa='".$numero_casa."'";
		$sql_1="BEGIN;SELECT * from ccnd03_censo_grupo_familiar where ".$conditions1." and 1=1 order by numero_parentesco asc";
		$result_2=$this->ccnd03_censo_grupo_familiar->execute($sql_1);
		$this->ccnd03_censo_grupo_familiar->execute('COMMIT');
//		pr($result_2);
		$this->set('fami',$result_2);

}//fin function


function consulta($pagina=null) {
	$this->layout="ajax";

	$this->Session->delete('salario_jefe');
	$this->Session->delete('otro_ingreso');
	$this->Session->delete('ingresos_familiares');
	$this->Session->delete('suma_total');

	$paren=$this->cnmd06_parentesco->findAll();
	$this->set('paren',$paren);
	$this->set('republica',$this->cugd01_republica->field('denominacion', $conditions ="cod_republica=".$this->Session->read('CC_republica'), $order ="cod_republica ASC"));
	$this->set('estado',$this->cugd01_estados->field('denominacion', $conditions ="cod_republica=".$this->Session->read('CC_republica')." and cod_estado=".$this->Session->read('CC_estado'), $order ="cod_estado ASC"));
	$this->set('municipio',$this->cugd01_municipios->field('denominacion', $conditions ="cod_republica=".$this->Session->read('CC_republica')." and cod_estado=".$this->Session->read('CC_estado')." and cod_municipio=".$this->Session->read('CC_municipio'), $order ="cod_municipio ASC"));
	$this->set('parroquia',$this->cugd01_parroquias->field('denominacion', $conditions ="cod_republica=".$this->Session->read('CC_republica')." and cod_estado=".$this->Session->read('CC_estado')." and cod_municipio=".$this->Session->read('CC_municipio')." and cod_parroquia=".$this->Session->read('CC_parroquia'), $order ="cod_parroquia ASC"));


    $condicion_dir_sup = "";
    $dir_sup=$this->cugd02_direccionsuperior->generateList($condicion_dir_sup,'denominacion ASC', null, '{n}.cugd02_direccionsuperior.cod_dir_superior', '{n}.cugd02_direccionsuperior.denominacion');
	$dir_sup = $dir_sup != null ? $dir_sup : array();
	$this->concatena($dir_sup, 'dir_superior');

	$conditions  = "     cod_republica=".$this->Session->read('CC_republica')." and cod_estado=".$this->Session->read('CC_estado')." and cod_municipio=".$this->Session->read('CC_municipio')." and cod_parroquia=".$this->Session->read('CC_parroquia')." ";
 	$conditions .= " and cod_centro=".$this->Session->read('CC_centro')." and cod_concejo=".$this->Session->read('CC_concejo')."";
    $dir_concejo=$this->ccnd01_concejo_comunal->generateList($conditions,'denominacion ASC', null, '{n}.ccnd01_concejo_comunal.cod_concejo', '{n}.ccnd01_concejo_comunal.denominacion');
	$dir_concejo = $dir_concejo != null ? $dir_concejo : array();
	$this->concatena($dir_concejo, 'concejo');

	$denominacion = $this->ccnd01_concejo_comunal->field('denominacion', $conditions , null);
	$this->set('seleccion_concejo', $this->Session->read('CC_concejo'));
	$this->set('denominacion_concejo', $denominacion);



	$condicion_cod_tipo = "";
    $dir_cod_tipo=$this->ccnd01_tipo_directivo->generateList($condicion_cod_tipo,'denominacion ASC', null, '{n}.ccnd01_tipo_directivo.cod_tipo', '{n}.ccnd01_tipo_directivo.denominacion');
	$dir_cod_tipo = $dir_cod_tipo != null ? $dir_cod_tipo : array();
	$this->concatena($dir_cod_tipo, 'cod_tipo');

	$sql="select * from cugd01_centros_poblados where cod_republica=".$this->Session->read('CC_republica')." and cod_estado=".$this->Session->read('CC_estado')." and cod_municipio=".$this->Session->read('CC_municipio')." and cod_parroquia=".$this->Session->read('CC_parroquia')." and cod_centro=".$this->Session->read('CC_centro');
	$datos=$this->cugd01_parroquias->execute($sql);
	$this->set('datos',$datos);

	$cond="cod_republica=".$this->Session->read('CC_republica')." and cod_estado=".$this->Session->read('CC_estado')." and cod_municipio=".$this->Session->read('CC_municipio')." and cod_parroquia=".$this->Session->read('CC_parroquia')." and cod_centro=".$this->Session->read('CC_centro');

	$nacionalidad= array('V'=>'Venezolana','E'=>'Extranjero');
	$this->set('nacionalidad',$nacionalidad);

	$sexo= array('M'=>'Masculino','F'=>'Femenino');
	$this->set('sexo',$sexo);

	$estado_civil= array('S'=>'Soltero(a)','C'=>'Casado(a)','V'=>'Viudo(a)','D'=>'Divorciado(a)','B'=>'Concubino(a)','O'=>'Otro');
	$this->set('estado_civil',$estado_civil);



	$conditions1  = "cod_republica=".$this->Session->read('CC_republica')." and cod_estado=".$this->Session->read('CC_estado')." and cod_municipio=".$this->Session->read('CC_municipio')." and cod_parroquia=".$this->Session->read('CC_parroquia')." and cod_centro=".$this->Session->read('CC_centro')." and cod_concejo=".$this->Session->read('CC_concejo');

	if(isset($pagina)){
		$Tfilas=$this->ccnd03_censo_jefe_familia->findCount($conditions1);
        if($Tfilas!=0){
        	$x=$this->ccnd03_censo_jefe_familia->findAll($conditions1,null,"cod_sector,cod_calle,numero_casa ASC",1,$pagina,null);

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
		$Tfilas=$this->ccnd03_censo_jefe_familia->findCount($conditions1);

        if($Tfilas!=0){
        	$x=$this->ccnd03_censo_jefe_familia->findAll($conditions1,null,"cod_sector,cod_calle,numero_casa ASC",1,$pagina,null);
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

	$cod_sector= $x[0]["ccnd03_censo_jefe_familia"]["cod_sector"];
	$cod_calle= $x[0]["ccnd03_censo_jefe_familia"]["cod_calle"];
	$numero_casa= $x[0]["ccnd03_censo_jefe_familia"]["numero_casa"];
	$conditions.=" and cod_sector=".$cod_sector." and cod_calle=".$cod_calle." and numero_casa='".$numero_casa."'";
	$datos2=$this->ccnd03_censo_grupo_familiar->execute("select * from ccnd03_censo_grupo_familiar where ".$conditions." order by numero_parentesco asc ");

	$cond="cod_republica=".$this->Session->read('CC_republica')." and cod_estado=".$this->Session->read('CC_estado')." and cod_municipio=".$this->Session->read('CC_municipio')." and cod_parroquia=".$this->Session->read('CC_parroquia')." and cod_centro=".$this->Session->read('CC_centro');
	$sector=$this->ccnd03_censo_sector->generateList($cond,'denominacion ASC', null, '{n}.ccnd03_censo_sector.cod_sector','{n}.ccnd03_censo_sector.denominacion');
	if($sector!=null){
		$this->concatena($sector,'sectores');
	}else{
		$this->set('sectores',array());
	}

	$cond="cod_republica=".$this->Session->read('CC_republica')." and cod_estado=".$this->Session->read('CC_estado')." and cod_municipio=".$this->Session->read('CC_municipio')." and cod_parroquia=".$this->Session->read('CC_parroquia')." and cod_centro=".$this->Session->read('CC_centro')." and cod_sector=".$cod_sector;
	$calles=$this->ccnd03_censo_calle->generateList($cond,'denominacion ASC', null, '{n}.ccnd03_censo_calle.cod_calle','{n}.ccnd03_censo_calle.denominacion');
		if($calles!=null){
			$this->concatena($calles,'calles');
		}else{
			$this->set('calles',array());
		}


	$this->set('fami',$datos2);

	$sector=$this->ccnd03_censo_sector->execute("select * from ccnd03_censo_sector where ".$cond);
	$this->set('deno_sector',$sector[0][0]['denominacion']);
	$cond.=" and cod_calle=".$cod_calle;
	$calle=$this->ccnd03_censo_calle->execute("select * from ccnd03_censo_calle where ".$cond);
	$this->set('deno_calle',$calle[0][0]['denominacion']);


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




function modificar($sector=null,$calle=null,$numero_casa=null,$pagina=null){
	$this->layout = "ajax";
	if(isset($pagina)){
		$this->set('pagina',$pagina);
	}

	$this->concatena($this->cnmd06_parentesco->generateList(null,'cod_parentesco ASC', null, '{n}.cnmd06_parentesco.cod_parentesco', '{n}.cnmd06_parentesco.denominacion'),'parentesco');
	$paren=$this->cnmd06_parentesco->findAll();
	$this->set('paren',$paren);
	$this->set('republica',$this->cugd01_republica->field('denominacion', $conditions ="cod_republica=".$this->Session->read('CC_republica'), $order ="cod_republica ASC"));
	$this->set('estado',$this->cugd01_estados->field('denominacion', $conditions ="cod_republica=".$this->Session->read('CC_republica')." and cod_estado=".$this->Session->read('CC_estado'), $order ="cod_estado ASC"));
	$this->set('municipio',$this->cugd01_municipios->field('denominacion', $conditions ="cod_republica=".$this->Session->read('CC_republica')." and cod_estado=".$this->Session->read('CC_estado')." and cod_municipio=".$this->Session->read('CC_municipio'), $order ="cod_municipio ASC"));
	$this->set('parroquia',$this->cugd01_parroquias->field('denominacion', $conditions ="cod_republica=".$this->Session->read('CC_republica')." and cod_estado=".$this->Session->read('CC_estado')." and cod_municipio=".$this->Session->read('CC_municipio')." and cod_parroquia=".$this->Session->read('CC_parroquia'), $order ="cod_parroquia ASC"));


    $condicion_dir_sup = "";
    $dir_sup=$this->cugd02_direccionsuperior->generateList($condicion_dir_sup,'denominacion ASC', null, '{n}.cugd02_direccionsuperior.cod_dir_superior', '{n}.cugd02_direccionsuperior.denominacion');
	$dir_sup = $dir_sup != null ? $dir_sup : array();
	$this->concatena($dir_sup, 'dir_superior');

	$conditions  = "     cod_republica=".$this->Session->read('CC_republica')." and cod_estado=".$this->Session->read('CC_estado')." and cod_municipio=".$this->Session->read('CC_municipio')." and cod_parroquia=".$this->Session->read('CC_parroquia')." ";
 	$conditions .= " and cod_centro=".$this->Session->read('CC_centro')." and cod_concejo=".$this->Session->read('CC_concejo')."";
    $dir_concejo=$this->ccnd01_concejo_comunal->generateList($conditions,'denominacion ASC', null, '{n}.ccnd01_concejo_comunal.cod_concejo', '{n}.ccnd01_concejo_comunal.denominacion');
	$dir_concejo = $dir_concejo != null ? $dir_concejo : array();
	$this->concatena($dir_concejo, 'concejo');

	$denominacion = $this->ccnd01_concejo_comunal->field('denominacion', $conditions , null);
	$this->set('seleccion_concejo', $this->Session->read('CC_concejo'));
	$this->set('denominacion_concejo', $denominacion);



	$condicion_cod_tipo = "";
    $dir_cod_tipo=$this->ccnd01_tipo_directivo->generateList($condicion_cod_tipo,'denominacion ASC', null, '{n}.ccnd01_tipo_directivo.cod_tipo', '{n}.ccnd01_tipo_directivo.denominacion');
	$dir_cod_tipo = $dir_cod_tipo != null ? $dir_cod_tipo : array();
	$this->concatena($dir_cod_tipo, 'cod_tipo');

	$sql="select * from cugd01_centros_poblados where cod_republica=".$this->Session->read('CC_republica')." and cod_estado=".$this->Session->read('CC_estado')." and cod_municipio=".$this->Session->read('CC_municipio')." and cod_parroquia=".$this->Session->read('CC_parroquia')." and cod_centro=".$this->Session->read('CC_centro');
	$datos=$this->cugd01_parroquias->execute($sql);
	$this->set('datos',$datos);

	$cond="cod_republica=".$this->Session->read('CC_republica')." and cod_estado=".$this->Session->read('CC_estado')." and cod_municipio=".$this->Session->read('CC_municipio')." and cod_parroquia=".$this->Session->read('CC_parroquia')." and cod_centro=".$this->Session->read('CC_centro');

	$nacionalidad= array('V'=>'Venezolana','E'=>'Extranjero');
	$this->set('nacionalidad',$nacionalidad);

	$sexo= array('M'=>'Masculino','F'=>'Femenino');
	$this->set('sexo',$sexo);

	$estado_civil= array('S'=>'Soltero(a)','C'=>'Casado(a)','V'=>'Viudo(a)','D'=>'Divorciado(a)','B'=>'Concubino(a)','O'=>'Otro');
	$this->set('estado_civil',$estado_civil);



	$conditions1  = "cod_republica=".$this->Session->read('CC_republica')." and cod_estado=".$this->Session->read('CC_estado')." and cod_municipio=".$this->Session->read('CC_municipio')." and cod_parroquia=".$this->Session->read('CC_parroquia')." and cod_centro=".$this->Session->read('CC_centro')." and cod_concejo=".$this->Session->read('CC_concejo')." and cod_sector=".$sector." and cod_calle=".$calle." and numero_casa='".$numero_casa."'";

	$x=$this->ccnd03_censo_jefe_familia->findAll($conditions1,null,"cod_sector,cod_calle,numero_casa ASC",null,null,null);

	$this->set('perso',$x);


	$datos2=$this->ccnd03_censo_grupo_familiar->execute("select * from ccnd03_censo_grupo_familiar where ".$conditions1." order by numero_parentesco asc ");

	$cond="cod_republica=".$this->Session->read('CC_republica')." and cod_estado=".$this->Session->read('CC_estado')." and cod_municipio=".$this->Session->read('CC_municipio')." and cod_parroquia=".$this->Session->read('CC_parroquia')." and cod_centro=".$this->Session->read('CC_centro');
	$sectores=$this->ccnd03_censo_sector->generateList($cond,'denominacion ASC', null, '{n}.ccnd03_censo_sector.cod_sector','{n}.ccnd03_censo_sector.denominacion');
	if($sectores!=null){
		$this->concatena($sectores,'sectores');
	}else{
		$this->set('sectores',array());
	}

	$cond="cod_republica=".$this->Session->read('CC_republica')." and cod_estado=".$this->Session->read('CC_estado')." and cod_municipio=".$this->Session->read('CC_municipio')." and cod_parroquia=".$this->Session->read('CC_parroquia')." and cod_centro=".$this->Session->read('CC_centro')." and cod_sector=".$sector;
	$calles=$this->ccnd03_censo_calle->generateList($cond,'denominacion ASC', null, '{n}.ccnd03_censo_calle.cod_calle','{n}.ccnd03_censo_calle.denominacion');
		if($calles!=null){
			$this->concatena($calles,'calles');
		}else{
			$this->set('calles',array());
		}


	$this->set('fami',$datos2);

	$sector=$this->ccnd03_censo_sector->execute("select * from ccnd03_censo_sector where ".$cond);
	$this->set('deno_sector',$sector[0][0]['denominacion']);
	$cond.=" and cod_calle=".$calle;
	$calle=$this->ccnd03_censo_calle->execute("select * from ccnd03_censo_calle where ".$cond);
	$this->set('deno_calle',$calle[0][0]['denominacion']);

	$ver=$this->ccnd03_censo_grupo_familiar->execute("select * from ccnd03_censo_grupo_familiar where ".$conditions1." order by numero_parentesco desc limit 1");
	if($ver!=null)
		$miembro_numero=$ver[0][0]['numero_parentesco']+1;
	else
		$miembro_numero=1;

	$this->set('miembro_numero',$miembro_numero);



}



 function guardar_modificar($sector=null,$calle=null,$numero_casa=null,$pagina=null){
	$this->layout = "ajax";
 	$cod_republica = $this->Session->read('CC_republica');
 	$cod_estado    = $this->Session->read('CC_estado');
 	$cod_municipio = $this->Session->read('CC_municipio');
 	$cod_parroquia = $this->Session->read('CC_parroquia');
    $cod_centro    = $this->Session->read('CC_centro');
    $cod_concejo   = $this->Session->read('CC_concejo');
	if($pagina!=null){
		$this->set('pagina',$pagina);
	}

	$this->set('cod_sector',$sector);
    $this->set('cod_calle',$calle);
    $this->set('nro_casa_parcela',$numero_casa);


    if(!empty($this->data['ccnp01_directiva']['punto_referencia'])){
    	$punto_referencia=$this->data['ccnp01_directiva']['punto_referencia'];
    }else{
    	$punto_referencia=0;
    }

     if(!empty($this->data['ccnp01_directiva']['telefono'])){
    	$telefono_vivienda=$this->data['ccnp01_directiva']['telefono'];
    }else{
    	$telefono_vivienda=0;
    }
    $nacionalidad=$this->data['ccnp01_directiva']['nacionalidad'];

    $cedula_identidad=$this->data['ccnp01_directiva']['cedula_identidad'];
    $nombres=$this->data['ccnp01_directiva']['nombres'];
    $apellidos=$this->data['ccnp01_directiva']['apellidos'];
    $estado_civil=$this->data['ccnp01_directiva']['estado_civil'];
    $sexo=$this->data['ccnp01_directiva']['sexo'];
    $fecha_nacimiento=$this->data['ccnp01_directiva']['fecha_nacimiento'];
    if(!empty($this->data['ccnp01_directiva']['grado_instruccion'])){
    	$grado_instruccion=$this->data['ccnp01_directiva']['grado_instruccion'];
    }else{
    	$grado_instruccion=0;
    }
    if(!empty($this->data['ccnp01_directiva']['oficio_profesion'])){
    	$oficio_profesion=$this->data['ccnp01_directiva']['oficio_profesion'];
    }else{
    	$oficio_profesion=0;
    }
    if(!empty($this->data['ccnp01_directiva']['ocupacion_actual'])){
    	$ocupacion_actual=$this->data['ccnp01_directiva']['ocupacion_actual'];
    }else{
    	$ocupacion_actual=0;
    }
    if(!empty($this->data['ccnp01_directiva']['donde_trabaja_top'])){
    	$donde_trabaja_top=$this->data['ccnp01_directiva']['donde_trabaja_top'];
    }else{
    	$donde_trabaja_top=0;
    }
    if(!empty($this->data['ccnp01_directiva']['cargo'])){
    	$cargo=$this->data['ccnp01_directiva']['cargo'];
    }else{
    	$cargo=0;
    }
    if(!empty($this->data['ccnp01_directiva']['fecha_ingreso'])){
    	$fecha_ingreso=$this->data['ccnp01_directiva']['fecha_ingreso'];
    }else{
    	$fecha_ingreso='1900-1-1';
    }
    if(!empty($this->data['ccnp01_directiva']['sueldo_salario'])){
    	$sueldo_salario=$this->Formato1($this->data['ccnp01_directiva']['sueldo_salario']);
    }else{
    	$sueldo_salario=0;
    }
    if(!empty($this->data['ccnp01_directiva']['otros_ingresos'])){
    	$otros_ingresos=$this->Formato1($this->data['ccnp01_directiva']['otros_ingresos']);
    }else{
    	$otros_ingresos=0;
    }
	if(!empty($this->data['ccnp01_directiva']['direccion_laboral'])){
    	$direccion_laboral=$this->data['ccnp01_directiva']['direccion_laboral'];
    }else{
    	$direccion_laboral=0;
    }
    if(!empty($this->data['ccnp01_directiva']['supervisor_inmediato'])){
    	$supervisor_inmediato=$this->data['ccnp01_directiva']['supervisor_inmediato'];
    }else{
    	$supervisor_inmediato=0;
    }
    if(!empty($this->data['ccnp01_directiva']['telefonos_trabajo'])){
    	$telefonos_trabajo=$this->data['ccnp01_directiva']['telefonos_trabajo'];
    }else{
    	$telefonos_trabajo=0;
    }

    $donde_trabaja=$this->data['ccnp01_directiva']['donde_trabaja'];
    if($this->data['ccnp01_directiva']['donde_trabaja']==6){
    	$otro_tipo_trabajo=$this->data['ccnp01_directiva']['otro_tipo_trabajo'];
    }else{
    	$otro_tipo_trabajo=0;
    }

    $actividad_comercial_vivienda=$this->data['ccnp01_directiva']['actividad_comercial_vivienda'];
    if($this->data['ccnp01_directiva']['actividad_comercial_vivienda']==1){
    	$cual_tipo_actividad="";
		for($i=1;$i<9;$i++){
			if($this->data['ccnp01_directiva']['venta_de'.$i]>=1 || $this->data['ccnp01_directiva']['venta_de'.$i]>='1'){
				$cual_tipo_actividad .='1';
			}else{
				$cual_tipo_actividad .='0';
			}
//			$cual_tipo_actividad.=$this->data['ccnp01_directiva']['venta_de'.$i];
		}
    }else{
    	$cual_tipo_actividad='00000000';
    }

    $ingreso_familiar=$this->data['ccnp01_directiva']['ingreso_familiar'];
    $cuenta_bancaria=$this->data['ccnp01_directiva']['cuenta_bancaria'];
    $tarjeta_credito=$this->data['ccnp01_directiva']['tarjeta_credito'];
    $cesta_ticket=$this->data['ccnp01_directiva']['cesta_ticket'];

    $tipo_vivienda=$this->data['ccnp01_directiva']['tipo_vivienda'];
    $forma_tenencia=$this->data['ccnp01_directiva']['forma_tenencia'];
    $terreno_propio=$this->data['ccnp01_directiva']['terreno_propio'];
    $tipo_paredes=$this->data['ccnp01_directiva']['tipo_paredes'];
    $tipo_techo=$this->data['ccnp01_directiva']['tipo_techo'];

	$enseres_vivienda="";
	for($i=1;$i<12;$i++){
			if($this->data['ccnp01_directiva']['enseres_vivienda'.$i]>=1 || $this->data['ccnp01_directiva']['enseres_vivienda'.$i]>='1'){
				$enseres_vivienda .='1';
			}else{
				$enseres_vivienda .='0';
			}
	}

    $condicion_salubridad=$this->data['ccnp01_directiva']['condicion_salubridad'];
    $pertenece_ocv=$this->data['ccnp01_directiva']['pertenece_ocv'];
    $presencia_insectos=$this->data['ccnp01_directiva']['presencia_insectos'];
    if($this->data['ccnp01_directiva']['presencia_insectos']==1){
    	$tipo_insectos_roedores="";
		for($i=1;$i<7;$i++){
			if($this->data['ccnp01_directiva']['insectos'.$i]>=1 || $this->data['ccnp01_directiva']['insectos'.$i]>='1'){
				$tipo_insectos_roedores .='1';
			}else{
				$tipo_insectos_roedores .='0';
			}
//			$tipo_insectos_roedores.=$this->data['ccnp01_directiva']['insectos'.$i];
		}
    }else{
    	$tipo_insectos_roedores='000000';
    }

    $animales_domesticos=$this->data['ccnp01_directiva']['animales_domesticos'];
    if($this->data['ccnp01_directiva']['animales_domesticos']==1){
    	$tipo_animales_domesticos="";
		for($i=1;$i<8;$i++){
			if($this->data['ccnp01_directiva']['animales'.$i]>=1 || $this->data['ccnp01_directiva']['animales'.$i]>='1'){
				$tipo_animales_domesticos .='1';
			}else{
				$tipo_animales_domesticos .='0';
			}
//			$tipo_animales_domesticos.=$this->data['ccnp01_directiva']['animales'.$i];
		}
    }else{
    	$tipo_animales_domesticos='0000000';
    }

    $familiares_padecen_enfermedades="";
	for($i=1;$i<10;$i++){
		if($this->data['ccnp01_directiva']['enfermedad'.$i]>=1 || $this->data['ccnp01_directiva']['enfermedad'.$i]>='1'){
			$familiares_padecen_enfermedades .='1';
		}else{
			$familiares_padecen_enfermedades .='0';
		}
//		$familiares_padecen_enfermedades.=$this->data['ccnp01_directiva']['enfermedad'.$i];
	}

	$requeriere_ayuda_especial=$this->data['ccnp01_directiva']['requeriere_ayuda_especial'];
    if($this->data['ccnp01_directiva']['requeriere_ayuda_especial']==1){
    	$cual_ayuda_especial=$this->data['ccnp01_directiva']['cual_ayuda_especial'];
    }else{
    	$cual_ayuda_especial=0;
    }


    if(!empty($this->data['ccnp01_directiva']['ninos_calle'])){
    	$ninos_calle=$this->data['ccnp01_directiva']['ninos_calle'];
    }else{
    	$ninos_calle=0;
    }
    if(!empty($this->data['ccnp01_directiva']['indigenas'])){
    	$indigenas=$this->data['ccnp01_directiva']['indigenas'];
    }else{
    	$indigenas=0;
    }
    if(!empty($this->data['ccnp01_directiva']['enfermos_terminales'])){
    	$enfermos_terminales=$this->data['ccnp01_directiva']['enfermos_terminales'];
    }else{
    	$enfermos_terminales=0;
    }
    if(!empty($this->data['ccnp01_directiva']['discapacitados'])){
    	$discapacitados=$this->data['ccnp01_directiva']['discapacitados'];
    }else{
    	$discapacitados=0;
    }
    if(!empty($this->data['ccnp01_directiva']['tercera_edad'])){
    	$tercera_edad=$this->data['ccnp01_directiva']['tercera_edad'];
    }else{
    	$tercera_edad=0;
    }
    if(!empty($this->data['ccnp01_directiva']['situacion_exclusion_otros'])){
    	$situacion_exclusion_otros=$this->data['ccnp01_directiva']['situacion_exclusion_otros'];
    }else{
    	$situacion_exclusion_otros=0;
    }

    $aguas_blancas=$this->data['ccnp01_directiva']['aguas_blancas'];
    $aguas_blancas_medidor=$this->data['ccnp01_directiva']['aguas_blancas_medidor'];
    $aguas_servidas=$this->data['ccnp01_directiva']['aguas_servidas'];
    $gas=$this->data['ccnp01_directiva']['gas'];
    $electrificado=$this->data['ccnp01_directiva']['electrificado'];
    $tiene_medidor_electricidad=$this->data['ccnp01_directiva']['tiene_medidor_electricidad'];
    $basura=$this->data['ccnp01_directiva']['basura'];

    $telefonia="";
	for($i=1;$i<6;$i++){
		if($this->data['ccnp01_directiva']['telefonia'.$i]>=1 || $this->data['ccnp01_directiva']['telefonia'.$i]>='1'){
			$telefonia .='1';
		}else{
			$telefonia .='0';
		}
//		$telefonia.=$this->data['ccnp01_directiva']['telefonia'.$i];
	}

	$transporte="";
	for($i=1;$i<5;$i++){
		if($this->data['ccnp01_directiva']['transporte'.$i]>=1 || $this->data['ccnp01_directiva']['transporte'.$i]>='1'){
			$transporte .='1';
		}else{
			$transporte .='0';
		}
//		$transporte.=$this->data['ccnp01_directiva']['transporte'.$i];
	}

	$mecanismo_informacion="";
	for($i=1;$i<7;$i++){
		if($this->data['ccnp01_directiva']['mecanismo_informacion'.$i]>=1 || $this->data['ccnp01_directiva']['mecanismo_informacion'.$i]>='1'){
			$mecanismo_informacion .='1';
		}else{
			$mecanismo_informacion .='0';
		}
//		$mecanismo_informacion.=$this->data['ccnp01_directiva']['mecanismo_informacion'.$i];
	}

	$servicios_comunales="";
	for($i=1;$i<14;$i++){
		if($this->data['ccnp01_directiva']['servicios_comunales'.$i]>=1 || $this->data['ccnp01_directiva']['servicios_comunales'.$i]>='1'){
			$servicios_comunales .='1';
		}else{
			$servicios_comunales .='0';
		}
//		$servicios_comunales.=$this->data['ccnp01_directiva']['servicios_comunales'.$i];
	}

	$organizaciones_comunitarias=$this->data['ccnp01_directiva']['organizaciones_comunitarias'];
    if($this->data['ccnp01_directiva']['organizaciones_comunitarias']==1){
    	$participa_organizacion_comunitaria=$this->data['ccnp01_directiva']['participa_organizacion_comunitaria'];
    	$participa_organizacion_comunitaria_miembro=$this->data['ccnp01_directiva']['participa_organizacion_comunitaria_miembro'];
    }else{
    	$participa_organizacion_comunitaria=0;
    	$participa_organizacion_comunitaria_miembro=0;
    }

    $cuales_misiones="";
	for($i=1;$i<9;$i++){
		if($this->data['ccnp01_directiva']['cuales_misiones'.$i]>=1 || $this->data['ccnp01_directiva']['cuales_misiones'.$i]>='1'){
			$cuales_misiones .='1';
		}else{
			$cuales_misiones .='0';
		}
//		$cuales_misiones.=$this->data['ccnp01_directiva']['cuales_misiones'.$i];
	}

    if($this->data['ccnp01_directiva']['cuales_misiones8']==1){
    	$cual_otra_mision=$this->data['ccnp01_directiva']['cual_otra_mision'];
    }else{
    	$cual_otra_mision=0;
    }

    $interviene_repartir_recursos=$this->data['ccnp01_directiva']['interviene_repartir_recursos'];
    $protagonismo_presupuestario=$this->data['ccnp01_directiva']['protagonismo_presupuestario'];

    $informacion_creacion_consejo=$this->data['ccnp01_directiva']['informacion_creacion_consejo'];
    if($this->data['ccnp01_directiva']['informacion_creacion_consejo']==1){
    	$como_obtuvo=$this->data['ccnp01_directiva']['como_obtuvo'];
    }else{
    	$como_obtuvo=0;
    }

    $participar_creacion_consejo=$this->data['ccnp01_directiva']['participar_creacion_consejo'];

    $area_gustaria_participar="";
	for($i=1;$i<12;$i++){
		if($this->data['ccnp01_directiva']['realizarse_consejo_comunal'.$i]>=1 || $this->data['ccnp01_directiva']['realizarse_consejo_comunal'.$i]>='1'){
			$area_gustaria_participar .='1';
		}else{
			$area_gustaria_participar .='0';
		}
//		$area_gustaria_participar.=''.$this->data['ccnp01_directiva']['realizarse_consejo_comunal'.$i].'';
	}


    if(!empty($this->data['ccnp01_directiva']['principales_potencialidades'])){
    	$principales_potencialidades=$this->data['ccnp01_directiva']['principales_potencialidades'];
    }else{
    	$principales_potencialidades=0;
    }
    if(!empty($this->data['ccnp01_directiva']['principales_problemas'])){
    	$principales_problemas=$this->data['ccnp01_directiva']['principales_problemas'];
    }else{
    	$principales_problemas=0;
    }
//    pr($this->data);
    $conditions1  = "cod_republica=".$this->Session->read('CC_republica')." and cod_estado=".$this->Session->read('CC_estado')." and cod_municipio=".$this->Session->read('CC_municipio')." and cod_parroquia=".$this->Session->read('CC_parroquia')." and cod_centro=".$this->Session->read('CC_centro')." and cod_concejo=".$this->Session->read('CC_concejo')." and cod_sector=".$sector." and cod_calle=".$calle." and numero_casa='".$numero_casa."'";

     $sql = "UPDATE ccnd03_censo_jefe_familia SET
     			   punto_referencia='$punto_referencia', telefonos_vivienda='$telefono_vivienda', nacionalidad='$nacionalidad', cedula_identidad='$cedula_identidad',
			       nombres='$nombres', apellidos='$apellidos', estado_civil='$estado_civil', sexo='$sexo', fecha_nacimiento='$fecha_nacimiento',
			       grado_instruccion='$grado_instruccion', oficio_profesion='$oficio_profesion', ocupacion_actual='$ocupacion_actual',
			       donde_trabaja='$donde_trabaja_top', cargo='$cargo', fecha_ingreso='$fecha_ingreso', sueldo_salario='$sueldo_salario',
			       otros_ingresos='$otros_ingresos', direccion_laboral='$direccion_laboral', supervisor_inmediato='$supervisor_inmediato',
			       telefonos_trabajo='$telefonos_trabajo', tipo_trabajo='$donde_trabaja', especifique_otro_tipo_trabajo='$otro_tipo_trabajo',
			       actividad_comercial_vivienda='$actividad_comercial_vivienda', cual_tipo_actividad='$cual_tipo_actividad', ingreso_familiar='$ingreso_familiar',
			       tiene_cuenta_bancaria='$cuenta_bancaria', tiene_tarjeta_credito='$tarjeta_credito', tiene_cesta_ticket='$cesta_ticket',
			       tipo_vivienda='$tipo_vivienda', forma_tenencia='$forma_tenencia', terreno_propio='$terreno_propio', tipo_paredes='$tipo_paredes',
			       tipo_techo='$tipo_techo', enseres_vivienda='$enseres_vivienda', salubridad_vivienda='$condicion_salubridad', pertenece_ocv='$pertenece_ocv',
			       presencia_insectos_roedores='$presencia_insectos', tipo_insectos_roedores='$tipo_insectos_roedores', tiene_animales_domesticos='$animales_domesticos',
			       tipo_animales_domesticos='$tipo_animales_domesticos', familiares_padecen_enfermedades='$familiares_padecen_enfermedades',
			       requeriere_ayuda_especial='$requeriere_ayuda_especial', cual_ayuda_especial='$cual_ayuda_especial', cuantos_ninos_exclusion='$ninos_calle',
			       cuantos_indigenas_exclusion='$indigenas', cuantos_enfermos_exclusion='$enfermos_terminales',
			       cuantos_discapacitados_exclusion='$discapacitados', cuantos_tercera_exclusion='$tercera_edad',
			       otros='$situacion_exclusion_otros', aguas_blancas='$aguas_blancas', aguas_blancas_medidor='$aguas_blancas_medidor', aguas_servidas='$aguas_servidas',
			       gas='$gas', sistema_electrico='$electrificado', tiene_medidor_electricidad='$tiene_medidor_electricidad', recoleccion_basura='$basura',
			       telefonia='$telefonia', transporte='$transporte', mecanismos_informacion='$mecanismo_informacion', servicios_comunales='$servicios_comunales',
			       organizacion_comunitaria='$organizaciones_comunitarias', participa_organizacion_comunitaria='$participa_organizacion_comunitaria',
			       participa_miembro_familiar='$participa_organizacion_comunitaria_miembro', cuales_misiones='$cuales_misiones', cuales_otras_misiones='$cual_otra_mision',
			       interviene_repartir_recursos='$interviene_repartir_recursos', protagonismo_presupuestario='$protagonismo_presupuestario',
			       informacion_creacion_consejo='$informacion_creacion_consejo', como_obtuvo='$como_obtuvo', participar_creacion_consejo='$participar_creacion_consejo',
			       area_gustaria_participar='$area_gustaria_participar', principales_potencialidades='$principales_potencialidades', principales_problemas='$principales_problemas'
			 WHERE ".$conditions1;
		   	 $sw=$this->ccnd03_censo_jefe_familia->execute($sql);
			 if($sw>1){
					$this->set('Message_existe', 'los datos fueron modificados con exito');
					$this->set('guardado', 'si');
	   		 }else{
		   			$this->set('errorMessage', 'MODIFICACIÓN FALLIDA DE LOS DATOS');
		   			$this->set('guardado', 'no');
	   		 }

 }//guardar modificar



 function eliminar($sector=null,$calle=null,$numero_casa=null,$pagina=null){
	$this->layout = "ajax";
 	$cod_republica = $this->Session->read('CC_republica');
 	$cod_estado    = $this->Session->read('CC_estado');
 	$cod_municipio = $this->Session->read('CC_municipio');
 	$cod_parroquia = $this->Session->read('CC_parroquia');
    $cod_centro    = $this->Session->read('CC_centro');
    $cod_concejo   = $this->Session->read('CC_concejo');
	if($pagina!=null){
		$this->set('pagina',$pagina);
	}

	$conditions1  = "cod_republica=".$this->Session->read('CC_republica')." and cod_estado=".$this->Session->read('CC_estado')." and cod_municipio=".$this->Session->read('CC_municipio')." and cod_parroquia=".$this->Session->read('CC_parroquia')." and cod_centro=".$this->Session->read('CC_centro')." and cod_concejo=".$this->Session->read('CC_concejo')." and cod_sector=".$sector." and cod_calle=".$calle." and numero_casa='".$numero_casa."'";

     $sql = "delete from ccnd03_censo_jefe_familia WHERE ".$conditions1;
		   	 $sw=$this->ccnd03_censo_jefe_familia->execute($sql);
			 if($sw>1){
					$this->set('Message_existe', 'registro eliminado');
					$this->set('eliminado', 'si');
	   		 }else{
		   			$this->set('errorMessage', 'los datos no pudieron ser eliminados');
		   			$this->set('eliminado', 'no');
	   		 }

 }



function seleccion($sector=null,$calle=null,$numero_casa=null){
	$this->layout = "ajax";

	if(!empty($this->data['ccnp01_directiva']['cod_sector'])){
		$sector=$this->data['ccnp01_directiva']['cod_sector'];
	}
	if(!empty($this->data['ccnp01_directiva']['cod_calle'])){
		$calle=$this->data['ccnp01_directiva']['cod_calle'];
	}
	if(!empty($this->data['ccnp01_directiva']['nro_casa_parcela'])){
		$numero_casa=$this->data['ccnp01_directiva']['nro_casa_parcela'];
	}

	$paren=$this->cnmd06_parentesco->findAll();
	$this->set('paren',$paren);
	$this->set('republica',$this->cugd01_republica->field('denominacion', $conditions ="cod_republica=".$this->Session->read('CC_republica'), $order ="cod_republica ASC"));
	$this->set('estado',$this->cugd01_estados->field('denominacion', $conditions ="cod_republica=".$this->Session->read('CC_republica')." and cod_estado=".$this->Session->read('CC_estado'), $order ="cod_estado ASC"));
	$this->set('municipio',$this->cugd01_municipios->field('denominacion', $conditions ="cod_republica=".$this->Session->read('CC_republica')." and cod_estado=".$this->Session->read('CC_estado')." and cod_municipio=".$this->Session->read('CC_municipio'), $order ="cod_municipio ASC"));
	$this->set('parroquia',$this->cugd01_parroquias->field('denominacion', $conditions ="cod_republica=".$this->Session->read('CC_republica')." and cod_estado=".$this->Session->read('CC_estado')." and cod_municipio=".$this->Session->read('CC_municipio')." and cod_parroquia=".$this->Session->read('CC_parroquia'), $order ="cod_parroquia ASC"));


    $condicion_dir_sup = "";
    $dir_sup=$this->cugd02_direccionsuperior->generateList($condicion_dir_sup,'denominacion ASC', null, '{n}.cugd02_direccionsuperior.cod_dir_superior', '{n}.cugd02_direccionsuperior.denominacion');
	$dir_sup = $dir_sup != null ? $dir_sup : array();
	$this->concatena($dir_sup, 'dir_superior');

	$conditions  = "     cod_republica=".$this->Session->read('CC_republica')." and cod_estado=".$this->Session->read('CC_estado')." and cod_municipio=".$this->Session->read('CC_municipio')." and cod_parroquia=".$this->Session->read('CC_parroquia')." ";
 	$conditions .= " and cod_centro=".$this->Session->read('CC_centro')." and cod_concejo=".$this->Session->read('CC_concejo')."";
    $dir_concejo=$this->ccnd01_concejo_comunal->generateList($conditions,'denominacion ASC', null, '{n}.ccnd01_concejo_comunal.cod_concejo', '{n}.ccnd01_concejo_comunal.denominacion');
	$dir_concejo = $dir_concejo != null ? $dir_concejo : array();
	$this->concatena($dir_concejo, 'concejo');

	$denominacion = $this->ccnd01_concejo_comunal->field('denominacion', $conditions , null);
	$this->set('seleccion_concejo', $this->Session->read('CC_concejo'));
	$this->set('denominacion_concejo', $denominacion);



	$condicion_cod_tipo = "";
    $dir_cod_tipo=$this->ccnd01_tipo_directivo->generateList($condicion_cod_tipo,'denominacion ASC', null, '{n}.ccnd01_tipo_directivo.cod_tipo', '{n}.ccnd01_tipo_directivo.denominacion');
	$dir_cod_tipo = $dir_cod_tipo != null ? $dir_cod_tipo : array();
	$this->concatena($dir_cod_tipo, 'cod_tipo');

	$sql="select * from cugd01_centros_poblados where cod_republica=".$this->Session->read('CC_republica')." and cod_estado=".$this->Session->read('CC_estado')." and cod_municipio=".$this->Session->read('CC_municipio')." and cod_parroquia=".$this->Session->read('CC_parroquia')." and cod_centro=".$this->Session->read('CC_centro');
	$datos=$this->cugd01_parroquias->execute($sql);
	$this->set('datos',$datos);

	$cond="cod_republica=".$this->Session->read('CC_republica')." and cod_estado=".$this->Session->read('CC_estado')." and cod_municipio=".$this->Session->read('CC_municipio')." and cod_parroquia=".$this->Session->read('CC_parroquia')." and cod_centro=".$this->Session->read('CC_centro');

	$nacionalidad= array('V'=>'Venezolana','E'=>'Extranjero');
	$this->set('nacionalidad',$nacionalidad);

	$sexo= array('M'=>'Masculino','F'=>'Femenino');
	$this->set('sexo',$sexo);

	$estado_civil= array('S'=>'Soltero(a)','C'=>'Casado(a)','V'=>'Viudo(a)','D'=>'Divorciado(a)','B'=>'Concubino(a)','O'=>'Otro');
	$this->set('estado_civil',$estado_civil);



	$conditions1  = "cod_republica=".$this->Session->read('CC_republica')." and cod_estado=".$this->Session->read('CC_estado')." and cod_municipio=".$this->Session->read('CC_municipio')." and cod_parroquia=".$this->Session->read('CC_parroquia')." and cod_centro=".$this->Session->read('CC_centro')." and cod_concejo=".$this->Session->read('CC_concejo')." and cod_sector=".$sector." and cod_calle=".$calle." and numero_casa='".$numero_casa."'";

	$x=$this->ccnd03_censo_jefe_familia->findAll($conditions1,null,"cod_sector,cod_calle,numero_casa ASC",null,null,null);

	$this->set('perso',$x);


	$datos2=$this->ccnd03_censo_grupo_familiar->execute("select * from ccnd03_censo_grupo_familiar where ".$conditions1." order by numero_parentesco asc ");

	$cond="cod_republica=".$this->Session->read('CC_republica')." and cod_estado=".$this->Session->read('CC_estado')." and cod_municipio=".$this->Session->read('CC_municipio')." and cod_parroquia=".$this->Session->read('CC_parroquia')." and cod_centro=".$this->Session->read('CC_centro');
	$sectores=$this->ccnd03_censo_sector->generateList($cond,'denominacion ASC', null, '{n}.ccnd03_censo_sector.cod_sector','{n}.ccnd03_censo_sector.denominacion');
	if($sectores!=null){
		$this->concatena($sectores,'sectores');
	}else{
		$this->set('sectores',array());
	}

	$cond="cod_republica=".$this->Session->read('CC_republica')." and cod_estado=".$this->Session->read('CC_estado')." and cod_municipio=".$this->Session->read('CC_municipio')." and cod_parroquia=".$this->Session->read('CC_parroquia')." and cod_centro=".$this->Session->read('CC_centro')." and cod_sector=".$sector;
	$calles=$this->ccnd03_censo_calle->generateList($cond,'denominacion ASC', null, '{n}.ccnd03_censo_calle.cod_calle','{n}.ccnd03_censo_calle.denominacion');
		if($calles!=null){
			$this->concatena($calles,'calles');
		}else{
			$this->set('calles',array());
		}


	$this->set('fami',$datos2);

	$sector=$this->ccnd03_censo_sector->execute("select * from ccnd03_censo_sector where ".$cond);
	$this->set('deno_sector',$sector[0][0]['denominacion']);
	$cond.=" and cod_calle=".$calle;
	$calle=$this->ccnd03_censo_calle->execute("select * from ccnd03_censo_calle where ".$cond);
	$this->set('deno_calle',$calle[0][0]['denominacion']);



}


function busqueda(){
	$this->layout = "ajax";

	$conditions1  = "cod_republica=".$this->Session->read('CC_republica')." and cod_estado=".$this->Session->read('CC_estado')." and cod_municipio=".$this->Session->read('CC_municipio')." and cod_parroquia=".$this->Session->read('CC_parroquia')." and cod_centro=".$this->Session->read('CC_centro');

	$sectores=$this->ccnd03_censo_sector->generateList($conditions1,'denominacion ASC', null, '{n}.ccnd03_censo_sector.cod_sector','{n}.ccnd03_censo_sector.denominacion');
	if($sectores!=null){
		$this->concatena($sectores,'sectores');
	}else{
		$this->set('sectores',array());
	}

	$this->data=null;


}





function buscar_vista_1($var1=null){

	$this->layout="ajax";

	$this->Session->delete('busca_nomina');
	$this->Session->delete('pista');

}//fin function





function buscar_vista_2($var1=null, $var2=null, $var3=null){
	$this->layout="ajax";
	$sql_like = "";

	if(isset($var2)){

		            $var1 = $this->Session->read('pista');
					$var1 = strtoupper_sisap($var1);
					$pagina = $var2;
					$sql     =" (".$this->busca_separado(array("numero_casa", "nombres","apellidos","cedula_identidad","deno_sector","deno_calle"), $var1).") ";

		$condicion  = "cod_republica=".$this->Session->read('CC_republica')." and cod_estado=".$this->Session->read('CC_estado')." and cod_municipio=".$this->Session->read('CC_municipio')." and cod_parroquia=".$this->Session->read('CC_parroquia')." and cod_centro=".$this->Session->read('CC_centro')." and cod_concejo=".$this->Session->read('CC_concejo')."";
		$Tfilas=$this->v_ccnd03_censo_poblacional->findCount($condicion." and ".$sql);
        if($Tfilas!=0){

					        	$Tfilas=(int)ceil($Tfilas/100);
					        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
								$this->set('total_paginas',$Tfilas);
								$this->set('pagina_actual',$pagina);
								$this->set('ultimo',$Tfilas);
					     	    $datos_filas=$this->v_ccnd03_censo_poblacional->findAll($condicion." and ".$sql,null,"numero_casa, deno_sector, deno_calle,cedula_identidad ASC",100,$pagina,null);
						        $this->set("datosFILAS",$datos_filas);
						        $this->set('siguiente',$pagina+1);
								$this->set('anterior',$pagina-1);
								$this->bt_nav($Tfilas,$pagina);


        }else{
	 	        $this->set('errorMessage', 'No se encontrar&oacute;n datos');
	 	        $this->set("datosFILAS",'');

			return;
        }

	}else{
	              	$pagina=1;
	       	        $this->Session->write('pista', $var1);
					$var1 = strtoupper_sisap($var1);
					$sql     =" (".$this->busca_separado(array("numero_casa", "nombres","apellidos","cedula_identidad","deno_sector","deno_calle"), $var1).") ";

					$condicion  = "cod_republica=".$this->Session->read('CC_republica')." and cod_estado=".$this->Session->read('CC_estado')." and cod_municipio=".$this->Session->read('CC_municipio')." and cod_parroquia=".$this->Session->read('CC_parroquia')." and cod_centro=".$this->Session->read('CC_centro')." and cod_concejo=".$this->Session->read('CC_concejo')."";

		$Tfilas=$this->v_ccnd03_censo_poblacional->findCount($condicion." and ".$sql);
        if($Tfilas!=0){
	        					$Tfilas=(int)ceil($Tfilas/100);
					        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
								$this->set('total_paginas',$Tfilas);
								$this->set('pagina_actual',$pagina);
								$this->set('ultimo',$Tfilas);
								$datos_filas=$this->v_ccnd03_censo_poblacional->findAll($condicion." and ".$sql,null,"numero_casa, deno_sector, deno_calle,cedula_identidad ASC",100,$pagina,null);
						        $this->set("datosFILAS",$datos_filas);
						        $this->set('siguiente',$pagina+1);
								$this->set('anterior',$pagina-1);
								$this->bt_nav($Tfilas,$pagina);

        }else{
	 	        $this->set('errorMessage', 'No se encontrar&oacute;n datos');
	 	         $this->set("datosFILAS",'');

			 return;
        }
	}



}//fin function



/////////////////////////////////////////////////////////hasta aquiiiiiiiiiiiiiiiiii////////////////////////7


function select(){
	$this->layout="ajax";

	$cod_republica = $this->Session->read('CC_republica');
 	$cod_estado    = $this->Session->read('CC_estado');
 	$cod_municipio = $this->Session->read('CC_municipio');
 	$cod_parroquia = $this->Session->read('CC_parroquia');
    $cod_centro    = $this->Session->read('CC_centro');
    $cod_concejo   = $this->Session->read('CC_concejo');

	$sql="cod_republica='$cod_republica' and cod_estado='$cod_estado' and cod_municipio='$cod_municipio' and cod_parroquia='$cod_parroquia' and cod_centro='$cod_centro'";
    $vialidad=$this->cugd01_vialidad->generateList($sql,'cod_vialidad ASC', null, '{n}.cugd01_vialidad.cod_vialidad', '{n}.cugd01_vialidad.denominacion');
	$vialidad = $vialidad != null ? $vialidad : array();
	$this->concatena($vialidad, 'vialidad');
}



function sumar_ci($vector,$ci_principal){
        if(isset($vector) && is_array($vector)){
	foreach($vector as $vv){
	   //echo $vv."<br>";
	   //print_r($vv);
	   $ci=(int)$this->dv_ci2($vv);
	   $ci_principal=(int)$ci_principal;
	   if($ci_principal==$ci){
	      $vv2[]=$vv;
	   }/*else{
	   	  $vv2[]=$ci_principal;
	   }*/
	}
	if(isset($vv2)){
		rsort($vv2);
	}else{
		$vv2[]=$ci_principal;
		rsort($vv2);
	}

	return $this->dv_ci($vv2[0]);
}else{
   return 0;
}
}

function dv_ci($cedula){
	$c=stripos  ( $cedula  , '-' );
	if($c!=null){
	   $v=explode('-',$cedula);
	   $nueva_cedula=$v[0]."-".($v[1]+1);
	   return $nueva_cedula;
	}else{
	    return $cedula."-1";
	}
}

function dv_ci2($cedula){
	$c=stripos  ( $cedula  , '-' );
	if($c!=null){
	   $v=explode('-',$cedula);
	   $nueva_cedula=$v[0];
	   return $nueva_cedula;
	}else{
	    return $cedula;
	}
}


function edad($nacimiento){
//restamos los años (año actual - año cumpleaños)
$edad = date("Y") - ereg_replace("^(.{4}).*","\\1",$nacimiento);

//si pasamos de año, pero aún no cumplimos años, resta 1
if( date("m-d") < ereg_replace(".*(.{5})$","\\1",$nacimiento) )
 $edad--;

return $edad;
}




 }//Fin de la clase controller
 ?>