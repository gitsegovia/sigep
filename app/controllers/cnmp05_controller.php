<?php

 class cnmp05Controller extends AppController{
 	var $name="cnmp05";
	var $uses = array('Cnmd01','arrd05','Cnmd02_empleados_puestos','Cnmd02_obreros_puestos','cnmd02_varios_puestos',
                      'cnmd05','cugd02_direccionsuperior', 'cugd02_coordinacion', 'cugd02_secretaria', 'cugd02_direccion', 'cugd02_division',
                      'cugd02_departamento', 'cugd02_oficina','cugd01_estados', 'cugd01_municipios', 'cugd01_parroquias', 'cugd01_centropoblados',
                      'cfpd02_sector', 'cfpd02_programa', 'cfpd02_sub_prog', 'cfpd02_proyecto', 'cfpd02_activ_obra', 'cfpd01_ano_partida',
                      'cfpd01_ano_generica', 'cfpd01_ano_especifica', 'cfpd01_ano_sub_espec', 'cfpd01_ano_auxiliar','cnmd04_ocupacion', 'ccfd04_cierre_mes',
                      'cnmd04_tipo', 'v_cnmd05','cfpd05_auxiliar','cnmd06_fichas','cnmd06_datos_personales','cfpd01_formulacion', 'v_cfpd05_denominaciones',
                      'v_cnmd05_cargos','cnmd06_fichas','cnmd01_autorizados','cnmd02_tablas_tipo','cnmd02_tablas_grado_paso','cnmd02_deno_grado','cnmd02_deno_grado_obrero');
 	var $helpers = array('Html','Ajax','Javascript', 'Sisap', 'Fpdf');
 	var $components = array('Autocomplete');

//'ccfd04_cierre_mes'

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

}//fin before filter
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

function zero($x=null){
	if($x != null){
		if($x<10){
			$x="0".$x;
		}else if($x>=10 && $x<=99){
			$x=$x;
		}
	}
	return $x;

}


function ano_formular(){
	$cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst;
	$year = $this->cfpd01_formulacion->findAll($condicion, null, 'ano_formular ASC', null);
	$ano1=$year[0]['cfpd01_formulacion']['ano_formular'];

	return $ano1;
}


function N_i_ii($nomVar,$vector=object){
   	  if($vector!=null){
   	  	$i=0;
   	  	$cod_nivelii = $this->cnmd04_ocupacion->generateList(null, 'cod_nivel_ii ASC', null, '{n}.cnmd04_ocupacion.cod_nivel_ii', '{n}.cnmd04_ocupacion.cod_nivel_ii');
   	  	$datos = $this->cnmd04_ocupacion->generateList(null, 'cod_nivel_i ASC', null, '{n}.cnmd04_ocupacion.denominacion', '{n}.cnmd04_ocupacion.denominacion');
   	  	foreach($datos as $dat){
   			$var[$i]=$dat;
   			$i++;
   		}
   		$i=0;
   		foreach($cod_nivelii as $dat){
   			$nivelii[$i]=$dat;
   			$i++;
   		}
        foreach($vector as $x => $y){
	           $Var[$x.".".$y]=$x.".".$y;
	    }//fin each
   	  $this->set($nomVar,$Var);
   	  }else{
   	  	  $this->set($nomVar,'');
   	  }
   }//fin AddCero


function index($var=null){
	$this->layout = "ajax";
	$cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');

	if($var != null && $var=='1' || $var == '0'){
		$this->set('opc', $var);
		if($var == 0){
			$this->set('enabled','READONLY');
		}else{
			$this->set('enabled','');
		}
	}else{
		$this->set('opc', '1');
		$this->set('enabled','');
	}
	$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst;
	$ano1=$this->ano_ejecucion();
	$this->set('ano1',$ano1);
	$this->Session->write('ano',$ano1);
	$cond1=$this->SQLCA()." and ano=".$ano1;

    $lista1=  $this->cfpd02_sector->generateList($cond1, 'cod_sector ASC', null, '{n}.cfpd02_sector.cod_sector', '{n}.cfpd02_sector.denominacion');
	if($lista1!=null){
	  	$this->concatena($lista1, 'vector');
	}else{
	  	$this->set('vector',array());
	}

    $Lista = $this->Cnmd01->generateList($this->SQLCA(), 'cod_tipo_nomina ASC', null, '{n}.Cnmd01.cod_tipo_nomina', '{n}.Cnmd01.denominacion');
   	$this->concatenaN($Lista, 'cod_tipo_nomina');

   	$Lista = $this->Cnmd02_empleados_puestos->generateList(null, 'cod_puesto ASC', null, '{n}.Cnmd02_empleados_puestos.cod_puesto', '{n}.Cnmd02_empleados_puestos.cod_puesto');
   	$this->AddCero('cod_puesto', $Lista);
   	$cond_cds ="cod_tipo_institucion=".$this->verifica_SS(3)." and cod_institucion=".$this->verifica_SS(4)." and cod_dependencia=".$this->verifica_SS(5);
   	$cod_dirsup = $this->cugd02_direccionsuperior->generateList($cond_cds, 'cod_dir_superior ASC', null, '{n}.cugd02_direccionsuperior.cod_dir_superior', '{n}.cugd02_direccionsuperior.denominacion');
    $this->concatena($cod_dirsup, 'cod_dir_superior');

	// *** DIRECCION SUPERIOR ***
	if($cod_dirsup!=null){
		foreach($cod_dirsup as $p => $aux_cs){
			$codigo_ds = $p;
			break;
		}
		$this->set('seleccion_ds',$codigo_ds);
		$this->Session->write('cod_1',$codigo_ds);
		echo "<script>document.getElementById('codigos1').value='$aux_cs';</script>";

	// *** COORDINACION ****
		$lista = $this->cugd02_coordinacion->generateList($cond_cds." and cod_dir_superior=".$codigo_ds, 'cod_coordinacion ASC', null, '{n}.cugd02_coordinacion.cod_coordinacion', '{n}.cugd02_coordinacion.denominacion');
    	if($lista!=null){
          	$this->concatena($lista, 'vector_coord');
			foreach($lista as $p => $aux_cs){
				$codigo_ds1 = $p;
				break;
			}
			$this->set('seleccion_ds1',$codigo_ds1);
			$this->Session->write('cod_2',$codigo_ds1);
			echo "<script>document.getElementById('codigos2').value='$aux_cs';</script>";

	// *** SECRETARIA ****
		  $lista = $this->cugd02_secretaria->generateList($cond_cds." and cod_dir_superior=".$codigo_ds." and cod_coordinacion=".$codigo_ds1, 'cod_secretaria ASC', null, '{n}.cugd02_secretaria.cod_secretaria', '{n}.cugd02_secretaria.denominacion');
          if($lista!=null){
          	$this->concatena($lista, 'vector_sec');
			foreach($lista as $p => $aux_cs){
				$codigo_ds2 = $p;
				break;
			}
			$this->set('seleccion_ds2',$codigo_ds2);
			$this->Session->write('cod_3',$codigo_ds2);
			echo "<script>document.getElementById('codigos3').value='$aux_cs';</script>";

			// *** DIRECCION ***
				$lista = $this->cugd02_direccion->generateList($cond_cds." and cod_dir_superior=".$codigo_ds." and cod_coordinacion=".$codigo_ds1." and cod_secretaria=".$codigo_ds2, 'cod_direccion ASC', null, '{n}.cugd02_direccion.cod_direccion', '{n}.cugd02_direccion.denominacion');
          		if($lista!=null){
          			$this->concatena($lista, 'vector_direcc');
          		}else{
          			$this->set('vector_direcc',array());
          		}

          }else{
          	$this->set('vector_sec',array());
          	$this->Session->write('cod_3',0);
          }

		}else{
			$this->set('vector_coord',array());
			$this->Session->write('cod_2',0);
			$this->Session->write('cod_3',0);
		}

	}else{
		$this->Session->write('cod_1',0);
		$this->Session->write('cod_2',0);
		$this->Session->write('cod_3',0);
	}

    $cod_estado =  $this->cugd01_estados->generateList("cod_republica=".$cod_presi, 'cod_estado ASC', null, '{n}.cugd01_estados.cod_estado', '{n}.cugd01_estados.denominacion');
    $this->concatena( $cod_estado, 'cod_estado');

	$a=$this->cugd01_estados->execute("select * from cugd90_municipio_defecto where cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst.";");

	if($a!=null){
		$estado=$a[0][0]['cod_estado'];

		$cod_municipio =  $this->cugd01_municipios->generateList("cod_republica=".$cod_presi." and cod_estado=".$estado, 'cod_estado, cod_municipio ASC', null, '{n}.cugd01_municipios.cod_municipio', '{n}.cugd01_municipios.denominacion');
	    $this->concatena( $cod_municipio, 'cod_municipio');

	    $dEstado = $this->cugd01_estados->field('denominacion', 'cod_republica='.$cod_presi.' and cod_estado='.$estado, null);
		$this->set('dEstado', $dEstado);


	    $this->set('estado',$a[0][0]['cod_estado'] );
	    $this->Session->write('codg_1',$a[0][0]['cod_estado']);

		$this->Session->write('codg_2',$a[0][0]['cod_municipio']);
		$this->set('cod_muni',$a[0][0]['cod_municipio']);

		$dmunicipio = $this->cugd01_municipios->field('denominacion', 'cod_republica='.$cod_presi.' and cod_estado='.$estado.' and cod_municipio='.$a[0][0]['cod_municipio'], null);
		$this->set('dmunicipio', $dmunicipio);

		$cod_parroquia =  $this->cugd01_parroquias->generateList("cod_republica=".$cod_presi." and cod_estado=".$estado." and cod_municipio=".$a[0][0]['cod_municipio'], 'cod_parroquia ASC', null, '{n}.cugd01_parroquias.cod_parroquia', '{n}.cugd01_parroquias.denominacion');
	    $this->concatena( $cod_parroquia, 'cod_parroquia');


	}else{
		$estado=$this->Session->read('SScodentidad');

		$cod_municipio =  $this->cugd01_municipios->generateList("cod_republica=".$cod_presi." and cod_estado=".$estado, 'cod_estado, cod_municipio ASC', null, '{n}.cugd01_municipios.cod_municipio', '{n}.cugd01_municipios.denominacion');
	    $this->concatena( $cod_municipio, 'cod_municipio');

	    $dEstado = $this->cugd01_estados->field('denominacion', 'cod_republica='.$cod_presi.' and cod_estado='.$estado, null);
		$this->set('dEstado', $dEstado);


	    $this->set('estado',$this->Session->read('SScodentidad') );
	    $this->Session->write('codg_1',$this->Session->read('SScodentidad'));

		$this->set('cod_muni','');
		$this->set('dmunicipio', '');

		$this->set('cod_parroquia',array());
	}


    $DV=$this->cfpd02_sector->execute("SELECT ano FROM cfpd02_sector WHERE ".$this->SQLCA()." GROUP BY ano");
    $datos = $this->cnmd04_ocupacion->findAll();
    $datos = $this->cnmd04_ocupacion->generateList(null, 'cod_nivel_ii ASC', null, '{n}.cnmd04_ocupacion.cod_nivel_i', '{n}.cnmd04_ocupacion.cod_nivel_i');
    $niveli = $this->cnmd04_tipo->generateList(null, 'cod_nivel_i ASC', null, '{n}.cnmd04_tipo.cod_nivel_i', '{n}.cnmd04_tipo.denominacion');
    $this->concatena($niveli, 'niveli');

    $this->N_i_ii('ocupacion', $datos);
    $this->set('seleccion','');
    $i=0;
    $denominacion = $this->cnmd04_ocupacion->generateList(null, 'cod_nivel_ii ASC', null, '{n}.cnmd04_ocupacion.cod_nivel_ii', '{n}.cnmd04_ocupacion.denominacion');
    foreach($denominacion as $denomin){
    	$i++;
    }
    foreach($DV as $dv){
    	foreach($dv as $d)
    	   $ve_ano[$d['ano']]=$d['ano'];
    	//$i++;
    }
    $denominacion = $this->cnmd04_ocupacion->generateList(null, 'cod_nivel_ii ASC', null, '{n}.cnmd04_ocupacion.cod_nivel_i', '{n}.cnmd04_ocupacion.cod_nivel_ii');
    $this->set('anos',$ve_ano);
}//fin index

function cargo($var1=null, $var2=null){
	$this->layout = "ajax";
	if($var2 != null){
		if($var1=='1'){
			$this->set('sw', true);
			$cod_presi = $this->Session->read('SScodpresi');
			$cod_entidad = $this->Session->read('SScodentidad');
			$cod_tipo_inst = $this->Session->read('SScodtipoinst');
			$cod_inst = $this->Session->read('SScodinst');
			$cod_dep = $this->Session->read('SScoddep');
			$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep;
			$cargo="SELECT MAX(cod_cargo) as cod_cargo FROM cnmd05 WHERE ".$condicion." and cod_tipo_nomina='$var2'";
			$cargo = $this->cnmd05->execute($cargo);
			foreach($cargo as $row){
				$cod_cargo = $row[0]['cod_cargo'];
			}
			$this->set('cod_cargo', $cod_cargo + 1);
		}else{
			$this->set('cod_tipo_nomina', $var2);
		}
	}else{
		$this->set('cod_tipo_nomina', null);
	}
}

function puesto($var=null){
	$this->layout = "ajax";
	$cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep;
	if($var!=null){
		$var2 = $this->Cnmd01->field('clasificacion_personal', $condicion.' and cod_tipo_nomina='.$var, null);
		$this->set('var2', $var2);
		if($var2 ==1){
			$Lista = $this->Cnmd02_empleados_puestos->generateList(null, 'cod_puesto ASC', null, '{n}.Cnmd02_empleados_puestos.cod_puesto', '{n}.Cnmd02_empleados_puestos.denominacion_clase');
			$this->concatena($Lista, 'cod_puesto');
		}else if($var2 == 2){
			$Lista = $this->Cnmd02_obreros_puestos->generateList(null, 'cod_puesto ASC', null, '{n}.Cnmd02_obreros_puestos.cod_puesto', '{n}.Cnmd02_obreros_puestos.titulo_puesto');
			$this->concatena($Lista, 'cod_puesto');
		}else{
			$Lista = $this->cnmd02_varios_puestos->generateList(null, 'cod_puesto ASC', null, '{n}.cnmd02_varios_puestos.cod_puesto', '{n}.cnmd02_varios_puestos.denominacion_clase');
			$this->concatena($Lista, 'cod_puesto');
		}
	}else{
		$this->set('var2', null);
		$this->set('cod_puesto', array());

	}

}

function mostrarPuesto($var2=null, $var=null){
	$this->layout="ajax";
	$var = strtoupper($var);
	$var_min = strtolower($var);
	$var_wrap = ucfirst($var_min);
	$this->set('var2', $var2);
	$cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep;
	if($var2 ==1){
		$puesto= $this->Cnmd02_empleados_puestos->generateList("denominacion_clase LIKE '%$var%' or denominacion_clase LIKE '%$var_min%' or denominacion_clase LIKE '%$var_wrap%'", 'cod_puesto ASC', null, '{n}.Cnmd02_empleados_puestos.cod_puesto', '{n}.Cnmd02_empleados_puestos.denominacion_clase');
	 	$this->concatena($puesto, 'puesto');
	}else if($var2 == 2){
		$puesto= $this->Cnmd02_obreros_puestos->generateList("titulo_puesto LIKE '%$var_min%' or titulo_puesto LIKE '%$var_wrap%' or titulo_puesto LIKE '%$var%'", 'cod_puesto ASC', null, '{n}.Cnmd02_obreros_puestos.cod_puesto', '{n}.Cnmd02_obreros_puestos.titulo_puesto');
	 	$this->concatena($puesto, 'puesto');
	}else{
		$Lista = $this->cnmd02_varios_puestos->generateList("denominacion_clase LIKE '%$var%' or denominacion_clase LIKE '%$var_min%' or denominacion_clase LIKE '%$var_wrap%'", 'cod_puesto ASC', null, '{n}.cnmd02_varios_puestos.cod_puesto', '{n}.cnmd02_varios_puestos.denominacion_clase');
		$this->concatena($Lista, 'puesto');
	}

}

function index2(){
	$this->layout = "ajax";
	$this->data=null;
	$Lista = $this->cnmd03_transacciones->generateList(null, 'cod_transaccion ASC', null, '{n}.cnmd03_transacciones.cod_transaccion', '{n}.cnmd03_transacciones.cod_transaccion');
   	$this->AddCero('Listactp', $Lista);
}//fin index



function mostrar_cod_tipo_nomina($var=null) {
    $this->layout = "ajax";
    if(isset($var) && !empty($var)){
    	$condicion=$this->SQLCA(). " and cod_tipo_nomina=".$var;
        $a=$this->Cnmd01->findAll($condicion);
        if($a!=null){
		  	 echo "<input type='text' name='data[ccfp01_division][cod_div_contable1]' value='".$a[0]['Cnmd01']['denominacion']."' id='cod_nomina1'   readonly='readonly' class='inputtext' />";
		  }else{
		  	 echo "<input type='text' name='data[ccfp01_division][cod_div_contable1]' value=''   readonly='readonly' class='inputtext' />";
		  }
    }else{
      echo "<input type='text' name='data[ccfp01_division][cod_div_contable1]' value=''   readonly='readonly' class='inputtext' />";
    }
    echo "<script>";
		echo "document.getElementById('cod_puesto1').value='';";
		echo "document.getElementById('deno_niveli').value='';";
    echo "document.getElementById('grado').value='';";
	echo "</script>";

}


function mostrar_cod_puesto($var=null, $var2=null) {
  $this->layout = "ajax";
    if(isset($var2) && !empty($var2) && isset($var) && !empty($var)){
      if($var == 1){
        $condicion="cod_puesto=".$var2;
        $a=$this->Cnmd02_empleados_puestos->findAll($condicion);
        if($a!=null){
          echo "<input type='text' name='data[ccfp01_division][cod_div_contable1]' value='".$a[0]['Cnmd02_empleados_puestos']['denominacion_clase']."' id='cod_puesto1'   readonly='readonly' class='inputtext' />";
          echo "<script>";
          echo "document.getElementById('grado').value='".$a[0]['Cnmd02_empleados_puestos']['grado']."';";
            echo "document.getElementById('sueldo_basico').removeAttribute('readonly');";
         /* echo "document.getElementById('sueldo_basico').setAttribute('readonly', 'readonly');";
          if($a[0]['Cnmd02_empleados_puestos']['grado']==99){
          }else{
            echo "document.getElementById('sueldo_basico').value='100';";
          }*/
          echo "</script>";
			  }else{
          echo "<input type='text' name='data[ccfp01_division][cod_div_contable1]' value=''   readonly='readonly' class='inputtext' />";
			  }
  		}else if($var == 2){
        $condicion="cod_puesto=".$var2;
        $a=$this->Cnmd02_obreros_puestos->findAll($condicion);
        if($a!=null){
		  	 	echo "<input type='text' name='data[ccfp01_division][cod_div_contable1]' value='".$a[0]['Cnmd02_obreros_puestos']['titulo_puesto']."' id='cod_puesto1'   readonly='readonly' class='inputtext' />";
          echo "<script>";
          echo "document.getElementById('grado').value='".$a[0]['Cnmd02_obreros_puestos']['grado']."';";
            echo "document.getElementById('sueldo_basico').removeAttribute('readonly');";
         /* echo "document.getElementById('sueldo_basico').setAttribute('readonly', 'readonly');";
          if($a[0]['Cnmd02_obreros_puestos']['grado']==99){
          }else{
            echo "document.getElementById('sueldo_basico').value='100';";
          }*/
          echo "</script>";
			  }else{
			  	 echo "<input type='text' name='data[ccfp01_division][cod_div_contable1]' value=''   readonly='readonly' class='inputtext' />";
			  }
  		}else if($var != 1 && $var != 2){
        $condicion="cod_puesto=".$var2;
        $a=$this->cnmd02_varios_puestos->findAll($condicion);
        	//print_r($a);
        	if($a!=null){
		  	 	echo "<input type='text' name='data[ccfp01_division][cod_div_contable1]' value='".$a[0]['cnmd02_varios_puestos']['denominacion_clase']."' id='cod_puesto1'   readonly='readonly' class='inputtext' />";
          echo "<script>";
          echo "document.getElementById('grado').value='".$a[0]['cnmd02_varios_puestos']['grado']."';";
            echo "document.getElementById('sueldo_basico').removeAttribute('readonly');";
         /* echo "document.getElementById('sueldo_basico').setAttribute('readonly', 'readonly');";
          if($a[0]['cnmd02_varios_puestos']['grado']==99){
          }else{
            echo "document.getElementById('sueldo_basico').value='100';";
          }*/
          echo "</script>";
			  }else{
			  	 echo "<input type='text' name='data[ccfp01_division][cod_div_contable1]' value=''   readonly='readonly' class='inputtext' />";
			  }
		  }
		  if ($var==null){echo '';}
    }else echo "<input type='text' name='data[ccfp01_division][cod_div_contable1]' value=''   readonly='readonly' class='inputtext' />";

}



function mostrar_cod_dir_superior($var=null) {
    $this->layout = "ajax";
    if(isset($var) && !empty($var)){
    	$cond_cds ="cod_tipo_institucion=".$this->verifica_SS(3)." and cod_institucion=".$this->verifica_SS(4)." and cod_dependencia=".$this->verifica_SS(5);
    	$condicion=$cond_cds." and cod_dir_superior=".$var;
    	$this->Session->write('dirsup',$var);
        $a=$this->cugd02_direccionsuperior->findAll($condicion);
         if($a!=null){
		  	 echo "<input type='text' name='data[ccfp01_division][cod_div_contable1]' value='".$a[0]['cugd02_direccionsuperior']['denominacion']."' id='codigos1'   readonly='readonly' class='inputtext' />";
		  }else{
		  	 echo "<input type='text' name='data[ccfp01_division][cod_div_contable1]' value=''   readonly='readonly' class='inputtext' />";
		  }
			echo "<script>";
				echo "document.getElementById('codigos2').value='';";
				echo "document.getElementById('codigos3').value='';";
				echo "document.getElementById('codigos4').value='';";
				echo "document.getElementById('codigos5').value='';";
				echo "document.getElementById('codigos6').value='';";
				echo "document.getElementById('codigos7').value='';";
			echo "</script>";
    }else{
     echo "<input type='text' name='data[ccfp01_division][cod_div_contable1]' value=''   readonly='readonly' class='inputtext' />";
    }

}//fin mostrar cod dir superior


   function guardar () {
	$this->layout="ajax";
	if(isset($this->data['cfpp97'])){
		$vdatos[1]=$this->data['cfpp97']['cod_tipo_nomina'];
		$vdatos[2]=$this->data['cfpp97']['cod_cargo'];
		$vdatos[3]=$this->data['cfpp97']['cod_puesto'];
		$vdatos[4]=$this->data['cfpp97']['cod_dir_superior'];
		$vdatos[5]=$this->data['cfpp97']['cod_coordinacion'];
		$vdatos[6]=$this->data['cfpp97']['cod_secretaria'];
		$vdatos[7]=$this->data['cfpp97']['cod_direccion'];
		if(!empty($this->data['cfpp97']['cod_division'])){
			$vdatos[8]=$this->data['cfpp97']['cod_division'];
		}else{
			$vdatos[8]= 0;
		}
		if(!empty($this->data['cfpp97']['cod_departamento'])){
			$vdatos[9]=$this->data['cfpp97']['cod_departamento'];
		}else{
			$vdatos[9]=0;
		}
		if(!empty($this->data['cfpp97']['cod_oficina'])){
			$vdatos[10]=$this->data['cfpp97']['cod_oficina'];
		}else{
			$vdatos[10]=0;
		}

		$vdatos[11]=$this->data['cfpp97']['cod_estado'];
		$vdatos[12]=$this->data['cfpp97']['cod_municipio'];
		$vdatos[13]=$this->data['cfpp97']['cod_parroquia'];
		$vdatos[14]=$this->data['cfpp97']['cod_centropoblado'];
		$vdatos[15]=$this->data['cfpp97']['cod_sector'];
		$vdatos[16]=$this->data['cfpp97']['cod_programa'];
		$vdatos[17]=$this->data['cfpp97']['cod_subprograma'];
		$vdatos[18]=$this->data['cfpp97']['cod_proyecto'];
		$vdatos[19]=$this->data['cfpp97']['cod_actividad'];
		$vdatos[20]=CE.$this->AddCeroR($this->data['cfpp97']['cod_partida']);
		$vdatos[21]=$this->data['cfpp97']['cod_generica'];
		$vdatos[22]=$this->data['cfpp97']['cod_especifica'];
		$vdatos[23]=$this->data['cfpp97']['cod_subespecifica'];
		if(empty($this->data['cfpp97']['cod_auxiliar']) || !isset($this->data['cfpp97']['cod_auxiliar'])){
			$vdatos[24]=0;
		}else{
			$vdatos[24]=$this->data['cfpp97']['cod_auxiliar'];
		}
		$nivel=explode(".",$this->data['cfpp97']['ocupacion']);
		$nivel_i = $this->data['cfpp97']['tipo'];
		$nivel_ii = $this->data['cfpp97']['ocupacion'];
		if(!empty($this->data['cfpp97']['ciclo'])){
			$ciclo = $this->data['cfpp97']['ciclo'];
		}else{
			$ciclo = 1;
		}
		$vdatos[25]=$nivel_i;//nivel i
		$vdatos[26]=$nivel_ii;//nivel ii
		$vdatos[27]=$this->data['cfpp97']['ano'];
		$vdatos[28]=$this->Formato1($this->data['cfpp97']['sueldo_basico']);
		$vdatos[29]=$this->Formato1($this->data['cfpp97']['compensacion']);
		$vdatos[30]=$this->Formato1($this->data['cfpp97']['primas']);
		$vdatos[31]=$this->Formato1($this->data['cfpp97']['bonos']);
		$vdatos[32]=isset($this->data['cfpp97']['codiga_ficha']) ? $this->data['cfpp97']['codiga_ficha'] : null;
		$S[1]=$this->verifica_SS(1);
		$S[2]=$this->verifica_SS(2);
		$S[3]=$this->verifica_SS(3);
		$S[4]=$this->verifica_SS(4);
		$S[5]=$this->verifica_SS(5);
		$CAMPOS="cod_presi,
				  cod_entidad,
				  cod_tipo_inst,
				  cod_inst,
				  cod_dep,
				  cod_tipo_nomina,
				  cod_cargo,
				  cod_puesto,
				  sueldo_basico,
				  compensaciones,
				  primas,
				  bonos,
				  cod_dir_superior,
				  cod_coordinacion,
				  cod_secretaria,
				  cod_direccion,
				  cod_division,
				  cod_departamento,
				  cod_oficina,
				  cod_estado,
				  cod_municipio,
				  cod_parroquia,
				  cod_centro,
				  condicion_actividad,
				  ano,
				  cod_sector,
				  cod_programa,
				  cod_sub_prog,
				  cod_proyecto,
				  cod_activ_obra,
				  cod_partida,
				  cod_generica,
				  cod_especifica,
				  cod_sub_espec,
				  cod_auxiliar,
				  cod_nivel_i,
				  cod_nivel_ii";
				  $VALORES = $S[1].",".$S[2].",".$S[3].",".$S[4].",".$S[5].",".$vdatos[1].",".$vdatos[2].",".$vdatos[3].",".$vdatos[28].",".$vdatos[29].",".$vdatos[30].",".$vdatos[31].",".$vdatos[4].",".$vdatos[5].",".$vdatos[6].",".$vdatos[7].",".$vdatos[8].",".$vdatos[9].",".$vdatos[10].",".$vdatos[11].",".$vdatos[12].",".$vdatos[13].",".$vdatos[14].",1,".$vdatos[27].",".$vdatos[15].",".$vdatos[16].",".$vdatos[17].",".$vdatos[18].",".$vdatos[19].",".$vdatos[20].",".$vdatos[21].",".$vdatos[22].",".$vdatos[23].",".$vdatos[24].",".$vdatos[25].",".$vdatos[26]."";


  $SQL_INSERT="INSERT INTO cnmd05 (".$CAMPOS.") VALUES (".$VALORES.")";
  $total_filas=$this->cnmd05->findCount($this->SQLCA()." and cod_tipo_nomina=".$vdatos[1]." and cod_cargo=".$vdatos[2]);
  $aux_cargo=$vdatos[2];
  if($total_filas==0){
    $this->cnmd05->recursive=0;
    for($i=0;$i<$ciclo;$i++){
  		$this->cnmd05->execute($SQL_INSERT);
  		$vdatos[2] += 1;
  		$VALORES = $S[1].",".$S[2].",".$S[3].",".$S[4].",".$S[5].",".$vdatos[1].",".$vdatos[2].",".$vdatos[3].",".$vdatos[28].",".$vdatos[29].",".$vdatos[30].",".$vdatos[31].",".$vdatos[4].",".$vdatos[5].",".$vdatos[6].",".$vdatos[7].",".$vdatos[8].",".$vdatos[9].",".$vdatos[10].",".$vdatos[11].",".$vdatos[12].",".$vdatos[13].",".$vdatos[14].",1,".$vdatos[27].",".$vdatos[15].",".$vdatos[16].",".$vdatos[17].",".$vdatos[18].",".$vdatos[19].",".$vdatos[20].",".$vdatos[21].",".$vdatos[22].",".$vdatos[23].",".$vdatos[24].",".$vdatos[25].",".$vdatos[26]."";
  		$SQL_INSERT="INSERT INTO cnmd05 (".$CAMPOS.") VALUES (".$VALORES.")";
    }
	$this->set('Message_existe', 'El cargo fue registrado correctamente');
	$this->busqueda1($vdatos[1],$aux_cargo,$vdatos[3]);
	$this->render('busqueda1');
  }else{
  	$this->set('errorMessage', 'El cargo no pudo ser registrado');
  	$this->index();
	$this->render('index');
  }

  echo'<script>';
  	  echo"document.getElementById('b_guardar').disabled=false;";
  	  echo"document.getElementById('sueldo_basico').value='0';";
  	  echo"document.getElementById('compensacion').value='0';";
  	  echo"document.getElementById('primas').value='0';";
  	  echo"document.getElementById('bonos').value='0';";
  	  echo"document.getElementById('total').value='0';";
  	  echo"document.getElementById('ciclo').value='1';";
  	  echo"document.getElementById('tipo').value='';";
  	  echo"document.getElementById('ocupacion').value='';";
  	  echo"document.getElementById('deno_ocupacion').innerHTML='&nbsp;';";
  echo'</script>';



	}//fin if isset
}//fin guardar


function nivelii($var=null){
	$this->layout="ajax";
	if($var!= null){
		$datos = $this->cnmd04_ocupacion->generateList('cod_nivel_i='.$var, 'cod_nivel_ii ASC', null, '{n}.cnmd04_ocupacion.cod_nivel_ii', '{n}.cnmd04_ocupacion.denominacion');
		$this->concatena($datos, 'ocupacion');
		$this->set('niveli', $var);

		$a=$this->cnmd04_ocupacion->execute("select * from cnmd04_tipo where cod_nivel_i=".$var);
		echo "<script>document.getElementById('deno_niveli').value='".$a[0][0]['denominacion']."';</script>";
	}else{
		$this->set('ocupacion', array());
		$this->set('niveli', null);
		echo "<script>document.getElementById('deno_niveli').value='';</script>";
	}

}




function mostrar_ocupacion($var_i=null, $var=null) {
	$this->layout="ajax";
	if($var!=null){
		$nivel=explode(".",$var);
		$a=$this->cnmd04_ocupacion->findAll("cod_nivel_i=".$var_i." and cod_nivel_ii=".$var,'denominacion','cod_nivel_ii ASC',1,1,null);
		if($a!=null){
			echo "<input type='text' name='data[ccfp01_division][cod_div_contable1]' value='".$a[0]['cnmd04_ocupacion']['denominacion']."' id='deno_niveli'   readonly='readonly' class='inputtext' />";
		}else{
		  	echo "<input type='text' name='data[ccfp01_division][cod_div_contable1]' value='' id='deno_niveli'   readonly='readonly' class='inputtext' />";
		}
	}else echo "<input type='text' name='data[ccfp01_division][cod_div_contable1]' value='' id='deno_niveli'   readonly='readonly' class='inputtext' />";
}


function ayuda_ocupacion ($busqueda=null) {
	$this->layout="ventana";
	if(isset($busqueda) && $busqueda!=null){
		if(isset($this->data['cfpp97']['buscar'])){
			$buscar1 = strtolower($this->data['cfpp97']['buscar']);
			$buscar2 = strtoupper($this->data['cfpp97']['buscar']);
			$buscar3 = $this->data['cfpp97']['buscar'];
			$datos = $this->cfpd04_ocupacion->execute("SELECT * FROM cnmd04_ocupacion WHERE denominacion LIKE '%".$buscar1."%'");
			$this->set('datosBusq',$datos);
			$this->set('mostrar',false);
		}
	}else{
		$this->set('mostrar',true);
	}
}


function ventana () {
	$this->layout="ajax";
}


function form_reporte(){
	$this->layout="ajax";
	$Lista = $this->Cnmd01->generateList($this->SQLCA(), 'cod_tipo_nomina ASC', null, '{n}.Cnmd01.cod_tipo_nomina', '{n}.Cnmd01.denominacion');
   	$this->concatenaN($Lista, 'cod_tipo_nomina');
	//$Lista = $this->SelectNominas();
	//return $this->concatenaN($Lista, 'cod_tipo_nomina');
}

function reporte($var=null, $var2=null){
	$this->layout="pdf";
	$this->set('ir', true);
	$cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$ano=$this->ano_ejecucion();

	$var_orde = "";
	$consolidacion=$this->data['cfpp97']['consolidacion'];
	if($consolidacion=="1"){
          $var_orde = " cod_tipo_nomina,denominacion_clase ASC ";
	}else if($consolidacion=="2"){
          $var_orde = " cod_tipo_nomina,dir_superior, coordinacion, secretaria, direccion, division, departamento, oficina ASC ";
	}//fin

	if(empty($this->data['cfpp97']['cod_tipo_nomina'])){
		$condicion =$this->SQLCA();
	}else{
		$nomina = $this->data['cfpp97']['cod_tipo_nomina'];
		$condicion =$this->SQLCA()." and cod_tipo_nomina =".$nomina;
	}

	//$datos = $this->v_cnmd05_cargos->findAll($conditions = $condicion, $fields = null, $var_orde, $limit = null, $page = null, $recursive = null);
	$sql="select * from v_cnmd05_cargos where ".$condicion." order by ".$var_orde;
	$datos = $this->v_cnmd05_cargos->execute($sql);
	if($datos!=null){
		$this->set('datos', $datos);
	}else{
		$this->set('datos',null);
	}
	$dependencia = $this->arrd05->field('arrd05.denominacion', $conditions = "arrd05.cod_presi = ".$cod_presi." and arrd05.cod_entidad = ".$cod_entidad." and arrd05.cod_tipo_inst = ".$cod_tipo_inst." and arrd05.cod_inst = ".$cod_inst." and arrd05.cod_dep='$cod_dep'", $order ="cod_dep ASC");
	$this->set('dependencia', $dependencia);


}





function consulta1($cod_tipo_nomina=null,$pagina=null){
	$this->layout = "ajax";
	$ano=$this->ano_ejecucion();
	$this->set('ano', $ano);

 	if(!empty($this->data['cfpp97']['cod_tipo_nomina'])){
 		$nomina=$this->data['cfpp97']['cod_tipo_nomina'];
 	}else{
		$nomina=$cod_tipo_nomina;
 	}

	if(isset($pagina)){
		$Tfilas=$this->cnmd05->findCount($this->SQLCA()." and cod_tipo_nomina=".$nomina);
        if($Tfilas!=0){
        	$x=$this->cnmd05->findAll($this->SQLCA()." and cod_tipo_nomina=".$nomina,null,"cod_tipo_nomina,cod_cargo ASC",1,$pagina,null);
			$cod_cargo = $x[0]['cnmd05']['cod_cargo'];
			$cont = $this->cnmd06_fichas->findCount($this->SQLCA()." and cod_tipo_nomina='$nomina' and cod_cargo='$cod_cargo'");
            if($cont != 0){
            	$dixx='disabled';
            }else{
            	$dixx='';
            }
            $this->set('dixx',$dixx);
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
	 	       $this->index('autor_valido',true);
			   $this->render("index");
			   return;
        }

	}else{
		$pagina=1;
		$Tfilas=$this->cnmd05->findCount($this->SQLCA()." and cod_tipo_nomina=".$nomina);

        if($Tfilas!=0){
        	$x=$this->cnmd05->findAll($this->SQLCA()." and cod_tipo_nomina=".$nomina,null,"cod_tipo_nomina,cod_cargo ASC",1,$pagina,null);
			$cod_cargo = $x[0]['cnmd05']['cod_cargo'];
			$cont = $this->cnmd06_fichas->findCount($this->SQLCA()." and cod_tipo_nomina='$nomina' and cod_cargo='$cod_cargo'");
            if($cont != 0){
            	$dixx='disabled';
            }else{
            	$dixx='';
            }
            $this->set('dixx',$dixx);
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
	 	       $this->index('autor_valido',true);
			   $this->render("index");
			   return;
        }
	}

	$sql="select * from v_cnmd05_cargos where ".$this->SQLCA()." and cod_tipo_nomina=".$x[0]["cnmd05"]["cod_tipo_nomina"]." and cod_cargo=".$x[0]["cnmd05"]["cod_cargo"];
	$datos=$this->v_cnmd05_cargos->execute($sql);
	$this->set('datos',$datos);

//////////////////////////////////PARA LA CONDICION DE VACANTE O NO Y COD FICHA Y NOMBRE/////////////////
	if($datos[0][0]['cod_ficha']!=null && $datos[0][0]['cod_ficha']!=0){

		$ver1=$this->cnmd06_fichas->execute("select * from cnmd06_fichas where ".$this->SQLCA()." and cod_tipo_nomina=".$datos[0][0]['cod_tipo_nomina']." and cod_cargo=".$datos[0][0]['cod_cargo']." and cod_ficha=".$datos[0][0]['cod_ficha']);
		if($ver1!=null){
			$ced=$ver1[0][0]['cedula_identidad'];
			$nom_ape=$this->cnmd06_datos_personales->execute("select * from cnmd06_datos_personales where cedula_identidad=".$ced);
		}
		$this->set('ficha',$ver1[0][0]['cod_ficha']);
		$this->set('condicion',$datos[0][0]['condicion_actividad']);
		$nom_com=$nom_ape[0][0]['primer_apellido']." ".$nom_ape[0][0]['segundo_apellido']." ".$nom_ape[0][0]['primer_nombre']." ".$nom_ape[0][0]['segundo_nombre'];
		$this->set('nom_com',$nom_com);
	}else{
		$this->set('ficha','');
		$this->set('condicion',1);
		$this->set('nom_com','');
	}

///////////////////////////////////////AQUI BUSCO LA DENOMINACION DEL CARGO//////////////////////////////////////////////////////////////

		if($datos[0][0]['clasificacion_personal'] ==1){
			$dPuesto = $this->Cnmd02_empleados_puestos->field('denominacion_clase', 'cod_puesto ='.$datos[0][0]['cod_puesto'], null);
			$this->set('dPuesto', $dPuesto);
      $dGrado = $this->Cnmd02_empleados_puestos->field('grado', 'cod_puesto ='.$datos[0][0]['cod_puesto'], null);      $this->set('dGrado', $dGrado);
		}else if($datos[0][0]['clasificacion_personal'] == 2){
			$dPuesto = $this->Cnmd02_obreros_puestos->field('titulo_puesto', 'cod_puesto ='.$datos[0][0]['cod_puesto'], null);
			$this->set('dPuesto', $dPuesto);
      $dGrado = $this->Cnmd02_obreros_puestos->field('grado', 'cod_puesto ='.$datos[0][0]['cod_puesto'], null);      $this->set('dGrado', $dGrado);
		}else{
			$dPuesto = $this->cnmd02_varios_puestos->field('denominacion_clase', 'cod_puesto ='.$datos[0][0]['cod_puesto'], null);
			$this->set('dPuesto', $dPuesto);
      $dGrado = $this->cnmd02_varios_puestos->field('grado', 'cod_puesto ='.$datos[0][0]['cod_puesto'], null);      $this->set('dGrado', $dGrado);
		}

}



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



function buscar_puesto_1($var1=null){
	$this->layout="ajax";
	$this->set("opcion",$var1);
	$this->Session->delete('pista');
}//fin function



function buscar_puesto_2($var1=null, $var2=null, $var3=null){
$this->layout="ajax";

if($var3==null){
	$var2 = strtoupper($var2);
	$this->Session->write('pista', $var2);
	$pista = $var2;
}else{
	$var22 = $this->Session->read('pista');
	$var22 = strtoupper($var22);
	$pista = $var22;

}

      if($var1 ==1){
    $tabla = "Cnmd02_empleados_puestos";
    $campo = "denominacion_clase";
    $sql = "  ".$this->busca_separado(array("denominacion_clase", "cod_puesto"),$pista);
}else if($var1 == 2){
	$tabla = "Cnmd02_obreros_puestos";
    $campo = "titulo_puesto";
    $sql = "  ".$this->busca_separado(array("titulo_puesto", "cod_puesto"),$pista);
}else{
	$tabla = "cnmd02_varios_puestos";
    $campo = "denominacion_clase";
    $sql = "  ".$this->busca_separado(array("denominacion_clase", "cod_puesto"),$pista);
}

$this->set("tabla", $tabla);
$this->set("campo", $campo);
$this->set("opcion",$var1);

    if($var3==null){$var2 = strtoupper($var2);
					$this->Session->write('pista', $var2);
					$Tfilas=$this->$tabla->findCount($sql);
					        if($Tfilas!=0){
					        	$pagina=1;
					        	$Tfilas=(int)ceil($Tfilas/50);
					        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
								$this->set('total_paginas',$Tfilas);
								$this->set('pagina_actual',$pagina);
								$this->set('ultimo',$Tfilas);
					     	    $datos_filas=$this->$tabla->findAll($sql,null,$campo." ASC",50,1,null);
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
						$Tfilas=$this->$tabla->findCount($sql);
						        if($Tfilas!=0){
						        	$pagina=$var3;
						        	$Tfilas=(int)ceil($Tfilas/50);
						        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
									$this->set('total_paginas',$Tfilas);
									$this->set('pagina_actual',$pagina);
									$this->set('ultimo',$Tfilas);
						     	    $datos_filas=$this->$tabla->findAll($sql,null,$campo." ASC",50,$pagina,null);
							        $this->set("datosFILAS",$datos_filas);
							        $this->set('siguiente',$pagina+1);
									$this->set('anterior',$pagina-1);
									$this->bt_nav($Tfilas,$pagina);
						          }else{
						        	$this->set("datosFILAS",'');
						          }
                 }//fin else

}//fin function





function selecionar_cod_puesto($var1=null, $var2=null){

	$this->layout="ajax";

	echo"<script>$('cod_puesto').value='".$var2."';</script>";

}



function sesiones($var=null){
	$this->layout="ajax";
	if($var!=''){
		$this->Session->delete('busca_nomina');
		$this->Session->write('busca_nomina',$var);
	}else{
		$this->Session->delete('busca_nomina');
	}
}



function buscar_vista_1($var1=null){

	$this->layout="ajax";
	$Lista = $this->Cnmd01->generateList($this->SQLCA(), 'cod_tipo_nomina ASC', null, '{n}.Cnmd01.cod_tipo_nomina', '{n}.Cnmd01.denominacion');
	$this->concatenaN($Lista, 'lista_nominas');
	$this->Session->delete('busca_nomina');
	$this->Session->delete('pista');

}//fin function





function buscar_vista_2($var1=null, $var2=null, $var3=null){
	$this->layout="ajax";
	$sql_like = "";
	$ano=$this->ano_ejecucion();

	if(isset($var2)){

		            $var1 = $this->Session->read('pista');
					$var1 = strtoupper_sisap($var1);
					$pagina = $var2;
//					$sql     =" (".$this->busca_separado(array("cod_tipo_nomina", "cod_cargo", "cod_puesto", "tipo_nomina","denominacion_clase"), $var1).") ";
					$sql     =" (".$this->busca_separado(array("cod_cargo", "denominacion_clase"), $var1).") ";
		if(isset($_SESSION['busca_nomina'])){
			$condicion=$this->SQLCA()." and cod_tipo_nomina=".$_SESSION['busca_nomina'];
		}else{
			$condicion=$this->SQLCA();
		}
		$Tfilas=$this->v_cnmd05_cargos->findCount($condicion." and ".$sql);
        if($Tfilas!=0){

					        	$Tfilas=(int)ceil($Tfilas/100);
					        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
								$this->set('total_paginas',$Tfilas);
								$this->set('pagina_actual',$pagina);
								$this->set('ultimo',$Tfilas);
					     	    $datos_filas=$this->v_cnmd05_cargos->findAll($condicion." and ".$sql,null,"cod_tipo_nomina, cod_cargo, cod_puesto ASC",100,$pagina,null);
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
//					$sql     =" (".$this->busca_separado(array("cod_tipo_nomina", "cod_cargo", "cod_puesto", "tipo_nomina","denominacion_clase"), $var1).") ";
					$sql     =" (".$this->busca_separado(array("cod_cargo", "denominacion_clase"), $var1).") ";

					if(isset($_SESSION['busca_nomina'])){
						$condicion=$this->SQLCA()." and cod_tipo_nomina=".$_SESSION['busca_nomina'];
					}else{
						$condicion=$this->SQLCA();
					}

		$Tfilas=$this->v_cnmd05_cargos->findCount($condicion." and ".$sql);
        if($Tfilas!=0){
	        					$Tfilas=(int)ceil($Tfilas/100);
					        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
								$this->set('total_paginas',$Tfilas);
								$this->set('pagina_actual',$pagina);
								$this->set('ultimo',$Tfilas);
					     	    $datos_filas=$this->v_cnmd05_cargos->findAll($condicion." and ".$sql,null,"cod_tipo_nomina, cod_cargo, cod_puesto ASC",100,$pagina,null);
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





function busqueda1($tipo_nomina=null,$cod_cargo=null,$cod_puesto=null){
	$this->layout = "ajax";
	$ano=$this->ano_ejecucion();

	$cont = $this->cnmd06_fichas->findCount($this->SQLCA()." and cod_tipo_nomina='$tipo_nomina' and cod_cargo='$cod_cargo'");
    if($cont != 0){
       	$dixx='disabled';
    }else{
       	$dixx='';
    }
    $this->set('dixx',$dixx);


	$sql="select * from v_cnmd05_cargos where ".$this->SQLCA()." and cod_tipo_nomina='$tipo_nomina' and cod_cargo='$cod_cargo'";
	$datos=$this->v_cnmd05_cargos->execute($sql);
	$this->set('datos',$datos);

//////////////////////////////////PARA LA CONDICION DE VACANTE O NO Y COD FICHA Y NOMBRE/////////////////
	if($datos[0][0]['cod_ficha']!=null && $datos[0][0]['cod_ficha']!=0){

		$ver1=$this->cnmd06_fichas->execute("select * from cnmd06_fichas where ".$this->SQLCA()." and cod_tipo_nomina=".$datos[0][0]['cod_tipo_nomina']." and cod_cargo=".$datos[0][0]['cod_cargo']." and cod_ficha=".$datos[0][0]['cod_ficha']);
		if($ver1!=null){
			$ced=$ver1[0][0]['cedula_identidad'];
			$nom_ape=$this->cnmd06_datos_personales->execute("select * from cnmd06_datos_personales where cedula_identidad=".$ced);
		}
		$this->set('ficha',$ver1[0][0]['cod_ficha']);
		$this->set('condicion',$datos[0][0]['condicion_actividad']);
		$nom_com=$nom_ape[0][0]['primer_apellido']." ".$nom_ape[0][0]['segundo_apellido']." ".$nom_ape[0][0]['primer_nombre']." ".$nom_ape[0][0]['segundo_nombre'];
		$this->set('nom_com',$nom_com);
	}else{
		$this->set('ficha','');
		$this->set('condicion',1);
		$this->set('nom_com','');
	}

///////////////////////////////////////AQUI BUSCO LA DENOMINACION DEL CARGO//////////////////////////////////////////////////////////////

		if($datos[0][0]['clasificacion_personal'] ==1){
			$dPuesto = $this->Cnmd02_empleados_puestos->field('denominacion_clase', 'cod_puesto ='.$datos[0][0]['cod_puesto'], null);
			$this->set('dPuesto', $dPuesto);
      $dGrado = $this->Cnmd02_empleados_puestos->field('grado', 'cod_puesto ='.$datos[0][0]['cod_puesto'], null);
      $this->set('dGrado', $dGrado);
		}else if($datos[0][0]['clasificacion_personal'] == 2){
			$dPuesto = $this->Cnmd02_obreros_puestos->field('titulo_puesto', 'cod_puesto ='.$datos[0][0]['cod_puesto'], null);
			$this->set('dPuesto', $dPuesto);
      $dGrado = $this->Cnmd02_obreros_puestos->field('grado', 'cod_puesto ='.$datos[0][0]['cod_puesto'], null);
      $this->set('dGrado', $dGrado);
		}else{
			$dPuesto = $this->cnmd02_varios_puestos->field('denominacion_clase', 'cod_puesto ='.$datos[0][0]['cod_puesto'], null);
			$this->set('dPuesto', $dPuesto);
      $dGrado = $this->cnmd02_varios_puestos->field('grado', 'cod_puesto ='.$datos[0][0]['cod_puesto'], null);
      $this->set('dGrado', $dGrado);
		}


}//fin busqueda




function eliminar($cod_tipo_nomina=null, $cod_cargo=null,$cod_puesto=null,$pagina=null){
	$this->layout="ajax";
	$ano=$this->ano_ejecucion();

	$this->cnmd05->execute("DELETE FROM cnmd05 WHERE ".$this->SQLCA()." and cod_tipo_nomina='$cod_tipo_nomina' and cod_cargo='$cod_cargo'");
	$this->set('Message_existe', 'Registro Eliminado');


	if(isset($pagina)){
		$this->consulta1($cod_tipo_nomina,$pagina);
		$this->render('consulta1');
	}else{
		$this->index();
		$this->render('index');
	}

}






function modificar($cod_tipo_nomina=null, $cod_cargo=null,$cod_puesto=null,$pagina=null){
	$this->layout="ajax";
	$cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$ano=$this->ano_ejecucion();

	$sql="select * from v_cnmd05_cargos where ".$this->SQLCA()." and cod_tipo_nomina='$cod_tipo_nomina' and cod_cargo='$cod_cargo'";
	$datos=$this->v_cnmd05_cargos->execute($sql);
	$this->set('datos',$datos);
	$this->set('pagina',$pagina);

	//////////////////////////////////PARA LA CONDICION DE VACANTE O NO Y COD FICHA Y NOMBRE/////////////////
		if($datos[0][0]['cod_ficha']!=null && $datos[0][0]['cod_ficha']!=0){

			$ver1=$this->cnmd06_fichas->execute("select * from cnmd06_fichas where ".$this->SQLCA()." and cod_tipo_nomina=".$datos[0][0]['cod_tipo_nomina']." and cod_cargo=".$datos[0][0]['cod_cargo']." and cod_ficha=".$datos[0][0]['cod_ficha']);
			if($ver1!=null){
				$ced=$ver1[0][0]['cedula_identidad'];
				$nom_ape=$this->cnmd06_datos_personales->execute("select * from cnmd06_datos_personales where cedula_identidad=".$ced);
			}
			$this->set('ficha',$ver1[0][0]['cod_ficha']);
			$this->set('condicion',$datos[0][0]['condicion_actividad']);
			$nom_com=$nom_ape[0][0]['primer_apellido']." ".$nom_ape[0][0]['segundo_apellido']." ".$nom_ape[0][0]['primer_nombre']." ".$nom_ape[0][0]['segundo_nombre'];
			$this->set('nom_com',$nom_com);
		}else{
			$this->set('ficha','');
			$this->set('condicion',1);
			$this->set('nom_com','');
		}


	///////////////////////////////////////AQUI BUSCO LA DENOMINACION DEL CARGO//////////////////////////////////////////////////////////////

		if($datos[0][0]['clasificacion_personal'] ==1){
			$dPuesto = $this->Cnmd02_empleados_puestos->field('denominacion_clase', 'cod_puesto ='.$datos[0][0]['cod_puesto'], null);
			$this->set('dPuesto', $dPuesto);
      $dGrado = $this->Cnmd02_empleados_puestos->field('grado', 'cod_puesto ='.$datos[0][0]['cod_puesto'], null);      $this->set('dGrado', $dGrado);
		}else if($datos[0][0]['clasificacion_personal'] == 2){
			$dPuesto = $this->Cnmd02_obreros_puestos->field('titulo_puesto', 'cod_puesto ='.$datos[0][0]['cod_puesto'], null);
			$this->set('dPuesto', $dPuesto);
      $dGrado = $this->Cnmd02_obreros_puestos->field('grado', 'cod_puesto ='.$datos[0][0]['cod_puesto'], null);      $this->set('dGrado', $dGrado);
		}else{
			$dPuesto = $this->cnmd02_varios_puestos->field('denominacion_clase', 'cod_puesto ='.$datos[0][0]['cod_puesto'], null);
			$this->set('dPuesto', $dPuesto);
      $dGrado = $this->cnmd02_varios_puestos->field('grado', 'cod_puesto ='.$datos[0][0]['cod_puesto'], null);      $this->set('dGrado', $dGrado);
		}

	//////////////////////////////////////////////////UBICACIÓN ADMINISTRATIVA///////////////////////////////////////////////
	$cond = "cod_tipo_institucion = ".$cod_tipo_inst." and cod_institucion = ".$cod_inst." and cod_dependencia = ".$cod_dep;

	$cod_dirsup =  $this->cugd02_direccionsuperior->generateList($cond, 'cod_dir_superior ASC', null, '{n}.cugd02_direccionsuperior.cod_dir_superior', '{n}.cugd02_direccionsuperior.denominacion');
	$this->concatena($cod_dirsup, 'cod_dir_superior');

	$cond.=" and cod_dir_superior=".$datos[0][0]['cod_dir_superior'];
	$lista=  $this->cugd02_coordinacion->generateList($cond, 'cod_coordinacion ASC', null, '{n}.cugd02_coordinacion.cod_coordinacion', '{n}.cugd02_coordinacion.denominacion');
    $this->concatena($lista, 'coordinacion');


    $cond.=" and cod_coordinacion=".$datos[0][0]['cod_coordinacion'];
    $lista=  $this->cugd02_secretaria->generateList($cond, 'cod_secretaria ASC', null, '{n}.cugd02_secretaria.cod_secretaria', '{n}.cugd02_secretaria.denominacion');
    $this->concatena($lista, 'secretaria');

    $cond.=" and cod_secretaria=".$datos[0][0]['cod_secretaria'];
    $lista=  $this->cugd02_direccion->generateList($cond, 'cod_direccion ASC', null, '{n}.cugd02_direccion.cod_direccion', '{n}.cugd02_direccion.denominacion');
    $this->concatena($lista, 'direccion');

    $cond.=" and cod_direccion=".$datos[0][0]['cod_direccion'];
    $lista=  $this->cugd02_division->generateList($cond, 'cod_division ASC', null, '{n}.cugd02_division.cod_division', '{n}.cugd02_division.denominacion');
    $this->concatena($lista, 'division');

    $cond.=" and cod_division=".$datos[0][0]['cod_division'];
    $lista=  $this->cugd02_departamento->generateList($cond, 'cod_departamento ASC', null, '{n}.cugd02_departamento.cod_departamento', '{n}.cugd02_departamento.denominacion');
    $this->concatena($lista, 'departamento');

    $cond.=" and cod_departamento=".$datos[0][0]['cod_departamento'];
    $lista=  $this->cugd02_oficina->generateList($cond, 'cod_oficina ASC', null, '{n}.cugd02_oficina.cod_oficina', '{n}.cugd02_oficina.denominacion');
    $this->concatena($lista, 'oficina');

    $this->Session->write('cod_1',$datos[0][0]['cod_dir_superior']);
	$this->Session->write('cod_2',$datos[0][0]['cod_coordinacion']);
	$this->Session->write('cod_3',$datos[0][0]['cod_secretaria']);
	$this->Session->write('cod_4',$datos[0][0]['cod_direccion']);
	$this->Session->write('cod_5',$datos[0][0]['cod_division']);
	$this->Session->write('cod_6',$datos[0][0]['cod_departamento']);
	$this->Session->write('cod_7',$datos[0][0]['cod_oficina']);



    /////////////////////////////////////UBICACION GEOGRAFICA////////////////////////////////////////////////////////////////
	$cond2="cod_republica=".$cod_presi;

	$lista =  $this->cugd01_estados->generateList($cond2, 'cod_estado ASC', null, '{n}.cugd01_estados.cod_estado', '{n}.cugd01_estados.denominacion');
	$this->concatena( $lista, 'estado');

	$cond2.=" and cod_estado=".$datos[0][0]['cod_estado'];
	$lista =  $this->cugd01_municipios->generateList($cond2, 'cod_municipio ASC', null, '{n}.cugd01_municipios.cod_municipio', '{n}.cugd01_municipios.denominacion');
	$this->concatena( $lista, 'municipio');

	$cond2.=" and cod_municipio=".$datos[0][0]['cod_municipio'];
	$lista =  $this->cugd01_parroquias->generateList($cond2, 'cod_parroquia ASC', null, '{n}.cugd01_parroquias.cod_parroquia', '{n}.cugd01_parroquias.denominacion');
	$this->concatena( $lista, 'parroquia');

	$cond2.=" and cod_parroquia=".$datos[0][0]['cod_parroquia'];
	$lista =  $this->cugd01_centropoblados->generateList($cond2, 'cod_centro ASC', null, '{n}.cugd01_centropoblados.cod_centro', '{n}.cugd01_centropoblados.denominacion');
	$this->concatena( $lista, 'centro');

	$this->Session->write('codg_1',$datos[0][0]['cod_estado']);
	$this->Session->write('codg_2',$datos[0][0]['cod_municipio']);
	$this->Session->write('codg_3',$datos[0][0]['cod_parroquia']);
	$this->Session->write('codg_4',$datos[0][0]['cod_centro']);


///////////////////////////////////////////////PARTIDAS//////////////////////////////////////////////////////////////////////////////////
	$cond3=$this->SQLCA()." and ano=".$ano;

	$lista =  $this->cfpd02_sector->generateList($cond3, 'cod_sector ASC', null, '{n}.cfpd02_sector.cod_sector', '{n}.cfpd02_sector.denominacion');
	$this->concatena( $lista, 'sector');

	$cond3.=" and cod_sector=".$datos[0][0]['cod_sector'];
	$lista =  $this->cfpd02_programa->generateList($cond3, 'cod_programa ASC', null, '{n}.cfpd02_programa.cod_programa', '{n}.cfpd02_programa.denominacion');
	$this->concatena( $lista, 'programa');

	$cond3.=" and cod_programa=".$datos[0][0]['cod_programa'];
	$lista =  $this->cfpd02_sub_prog->generateList($cond3, 'cod_sub_prog ASC', null, '{n}.cfpd02_sub_prog.cod_sub_prog', '{n}.cfpd02_sub_prog.denominacion');
	$this->concatena( $lista, 'subprograma');

	$cond3.=" and cod_sub_prog=".$datos[0][0]['cod_sub_prog'];
	$lista =  $this->cfpd02_proyecto->generateList($cond3, 'cod_proyecto ASC', null, '{n}.cfpd02_proyecto.cod_proyecto', '{n}.cfpd02_proyecto.denominacion');
	$this->concatena( $lista, 'proyecto');

	$cond3.=" and cod_proyecto=".$datos[0][0]['cod_proyecto'];
	$lista =  $this->cfpd02_activ_obra->generateList($cond3, 'cod_activ_obra ASC', null, '{n}.cfpd02_activ_obra.cod_activ_obra', '{n}.cfpd02_activ_obra.denominacion');
	$this->concatena( $lista, 'actividad');



	$cond4 ="ejercicio=".$ano." and cod_grupo=".CE." and cod_partida in(1,3,7)";
	$lista =  $this->cfpd01_ano_partida->generateList($cond4, 'cod_partida ASC', null, '{n}.cfpd01_ano_partida.cod_partida', '{n}.cfpd01_ano_partida.denominacion');
	$this->concatena( $lista, 'partida',4);

	$cod_grupo=substr($datos[0][0]['cod_partida'], 0, 1);
	$cod_partida=substr($datos[0][0]['cod_partida'],1,2);
	$this->set('cod_partida',$cod_partida);
	$cond4 ="ejercicio=".$ano." and cod_grupo=".$cod_grupo." and cod_partida=".$cod_partida;
	$lista =  $this->cfpd01_ano_generica->generateList($cond4, 'cod_generica ASC', null, '{n}.cfpd01_ano_generica.cod_generica', '{n}.cfpd01_ano_generica.denominacion');
	$this->concatena( $lista, 'generica');

	$cond4.=" and cod_generica=".$datos[0][0]['cod_generica'];
	$lista =  $this->cfpd01_ano_especifica->generateList($cond4, 'cod_especifica ASC', null, '{n}.cfpd01_ano_especifica.cod_especifica', '{n}.cfpd01_ano_especifica.denominacion');
	$this->concatena( $lista, 'especifica');

	$cond4.=" and cod_especifica=".$datos[0][0]['cod_especifica'];
	$lista =  $this->cfpd01_ano_sub_espec->generateList($cond4, 'cod_sub_espec ASC', null, '{n}.cfpd01_ano_sub_espec.cod_sub_espec', '{n}.cfpd01_ano_sub_espec.denominacion');
	$this->concatena( $lista, 'subespecifica');

	$cond3.=" and cod_activ_obra=".$datos[0][0]['cod_activ_obra'];
	$cond5=$cond3." and cod_partida=".$datos[0][0]['cod_partida']." and cod_generica=".$datos[0][0]['cod_generica']." and cod_especifica=".$datos[0][0]['cod_especifica']." and cod_sub_espec=".$datos[0][0]['cod_sub_espec'];
	$lista =  $this->cfpd05_auxiliar->generateList($cond5, 'cod_auxiliar ASC', null, '{n}.cfpd05_auxiliar.cod_auxiliar', '{n}.cfpd05_auxiliar.denominacion');
	if($lista!=null){
		$this->concatena( $lista, 'auxiliar');
	}else{
		$this->set('auxiliar', array());
	}


	  $this->Session->write('ano', $ano);
	  $this->Session->write('dsec', $datos[0][0]['cod_sector']);
	  $this->Session->write('dprog', $datos[0][0]['cod_programa']);
	  $this->Session->write('dsubp', $datos[0][0]['cod_sub_prog']);
	  $this->Session->write('proy', $datos[0][0]['cod_proyecto']);
	  $this->Session->write('actividad', $datos[0][0]['cod_activ_obra']);
	  $this->Session->write('cpar', $cod_partida);
	  $this->Session->write('cgen', $datos[0][0]['cod_generica']);
	  $this->Session->write('cesp', $datos[0][0]['cod_especifica']);
	  $this->Session->write('csesp',$datos[0][0]['cod_sub_espec']);


/////////////////////////////////////////////////////////////////////////////////////////////////////////////

	$tipo = $this->cnmd04_tipo->generateList(null, 'cod_nivel_i ASC', null, '{n}.cnmd04_tipo.cod_nivel_i', '{n}.cnmd04_tipo.denominacion');
	$this->concatena($tipo, 'tipo');

	$ocupacion = $this->cnmd04_ocupacion->generateList('cod_nivel_i='.$datos[0][0]['cod_nivel_i'], 'cod_nivel_ii ASC', null, '{n}.cnmd04_ocupacion.cod_nivel_ii', '{n}.cnmd04_ocupacion.denominacion');
	$this->concatena($ocupacion, 'ocupacion');


}// FIN MODIFICAR



function guardar_modificar($cod_tipo_nomina=null, $cod_cargo=null,$cod_puesto=null, $pagina=null){
		$this->layout="ajax";

		$cod_puesto = $this->data['cfpp97']['cod_puesto'];
		$sueldo_basico = $this->Formato1($this->data['cfpp97']['sueldo_basico']);
		$compensaciones = $this->Formato1($this->data['cfpp97']['compensacion']);
		$primas = $this->Formato1($this->data['cfpp97']['primas']);
		$bonos = $this->Formato1($this->data['cfpp97']['bonos']);
		$cod_dir_superior = $this->data['cfpp97']['cod_dir_superior'];
		$cod_coordinacion = $this->data['cfpp97']['cod_coordinacion'];
		$cod_secretaria = $this->data['cfpp97']['cod_secretaria'];
		$cod_direccion = $this->data['cfpp97']['cod_direccion'];

		if(!empty($this->data['cfpp97']['cod_division'])){
			$cod_division=$this->data['cfpp97']['cod_division'];
		}else{
			$cod_division= 0;
		}

		if(!empty($this->data['cfpp97']['cod_departamento'])){
			$cod_departamento=$this->data['cfpp97']['cod_departamento'];
		}else{
			$cod_departamento=0;
		}
		if(!empty($this->data['cfpp97']['cod_oficina'])){
			$cod_oficina=$this->data['cfpp97']['cod_oficina'];
		}else{
			$cod_oficina=0;
		}

		$cod_estado = $this->data['cfpp97']['cod_estado'];
		$cod_municipio = $this->data['cfpp97']['cod_municipio'];
		$cod_parroquia = $this->data['cfpp97']['cod_parroquia'];
		$cod_centro = $this->data['cfpp97']['cod_centropoblado'];
		$ano = $this->data['cfpp97']['ano'];
		$cod_sector = $this->data['cfpp97']['cod_sector'];
		$cod_programa = $this->data['cfpp97']['cod_programa'];
		$cod_sub_prog = $this->data['cfpp97']['cod_subprograma'];
		$cod_proyecto = $this->data['cfpp97']['cod_proyecto'];
		$cod_activ_obra = $this->data['cfpp97']['cod_actividad'];
		$cod_partida = CE.$this->zero($this->data['cfpp97']['cod_partida']);
		$cod_generica = $this->data['cfpp97']['cod_generica'];
		$cod_especifica = $this->data['cfpp97']['cod_especifica'];
		$cod_subespecifica = $this->data['cfpp97']['cod_subespecifica'];
		if(empty($this->data['cfpp97']['cod_auxiliar'])){
			$cod_auxiliar = 0;
		}else{
			$cod_auxiliar = $this->data['cfpp97']['cod_auxiliar'];
		}
		$cod_nivel_i = $this->data['cfpp97']['tipo'];
		$cod_nivel_ii = $this->data['cfpp97']['ocupacion'];


		$sql = "UPDATE cnmd05 SET cod_puesto='$cod_puesto', sueldo_basico='$sueldo_basico', compensaciones='$compensaciones', primas='$primas', bonos='$bonos', cod_dir_superior=$cod_dir_superior, cod_coordinacion=$cod_coordinacion, ";
		$sql .= " cod_secretaria=$cod_secretaria, cod_direccion=$cod_direccion, cod_division=$cod_division, cod_departamento=$cod_departamento, cod_oficina=$cod_oficina, cod_estado=$cod_estado, ";
		$sql .= " cod_municipio=$cod_municipio, cod_parroquia=$cod_parroquia, cod_centro=$cod_centro, ano=$ano, cod_sector=$cod_sector, cod_programa=$cod_programa, cod_sub_prog=$cod_sub_prog, ";
		$sql .= " cod_proyecto=$cod_proyecto, cod_activ_obra=$cod_activ_obra, cod_partida=$cod_partida, cod_generica=$cod_generica, cod_especifica=$cod_especifica, cod_sub_espec=$cod_subespecifica, cod_auxiliar=$cod_auxiliar, ";
		$sql .= " cod_nivel_i=$cod_nivel_i, cod_nivel_ii=$cod_nivel_ii";
		$sql .= " WHERE ".$this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina." and cod_cargo=".$cod_cargo;
		$sw = $this->cnmd05->execute($sql);
		if($sw>1){
			$this->set('Message_existe', 'Los datos fuerón modificados correctamente');
		}else{
			$this->set('errorMessage', 'los datos no fueron modificados');
		}


		if(isset($pagina)){
			$this->consulta1($cod_tipo_nomina,$pagina);
			$this->render('consulta1');
		}else{
			$this->busqueda1($cod_tipo_nomina, $cod_cargo,$cod_puesto);
			$this->render('busqueda1');
		}


}





function select($select=null,$var=null) { //select de ubicacion administrativa
	$this->layout = "ajax";
if(isset($var) && !empty($var) && $var!=''){
    $cond ="cod_tipo_institucion=".$this->verifica_SS(3)." and cod_institucion=".$this->verifica_SS(4)." and cod_dependencia=".$this->verifica_SS(5);
	switch($select){
		case 'coordinacion':
		  $this->set('SELECT','secretaria');
		  $this->set('codigo','coordinacion');
		  $this->set('seleccion','');
		  $this->set('n',2);
		  $this->Session->write('cod_1',$var);
		  	$cond .=" and cod_dir_superior=".$var;
		  $lista=  $this->cugd02_coordinacion->generateList($cond, 'cod_coordinacion ASC', null, '{n}.cugd02_coordinacion.cod_coordinacion', '{n}.cugd02_coordinacion.denominacion');
          if($lista!=null){
          	$this->concatena($lista, 'vector');
          }else{
          	$this->set('vector',array());
          }
		break;
		case 'secretaria':
		  $this->set('SELECT','direccion');
		  $this->set('codigo','secretaria');
		  $this->set('seleccion','');
		  $this->set('n',3);
		  $cod_1 =  $this->Session->read('cod_1');

		  $this->Session->write('cod_2',$var);
		  $cond .=" and cod_dir_superior=".$cod_1." and cod_coordinacion=".$var;
		  $lista=  $this->cugd02_secretaria->generateList($cond, 'cod_secretaria ASC', null, '{n}.cugd02_secretaria.cod_secretaria', '{n}.cugd02_secretaria.denominacion');
          if($lista!=null){
          	$this->concatena($lista, 'vector');
          }else{
          	$this->set('vector',array());
          }
		break;
		case 'direccion':
		  $this->set('SELECT','division');
		  $this->set('codigo','direccion');
		  $this->set('seleccion','');
		  $this->set('n',4);
		  $cod_1 =  $this->Session->read('cod_1');
		  $cod_2 =  $this->Session->read('cod_2');
		  $this->Session->write('cod_3',$var);
		  $cond .=" and cod_dir_superior=".$cod_1." and cod_coordinacion=".$cod_2." and  cod_secretaria=".$var;
		  $lista=  $this->cugd02_direccion->generateList($cond, 'cod_direccion ASC', null, '{n}.cugd02_direccion.cod_direccion', '{n}.cugd02_direccion.denominacion');
          if($lista!=null){
          	$this->concatena($lista, 'vector');
          }else{
          	$this->set('vector',array());
          }
		break;
		case 'division':
		  $this->set('SELECT','departamento');
		  $this->set('codigo','division');
		  $this->set('seleccion','');
		  $this->set('n',5);
		  $cod_1 =  $this->Session->read('cod_1');
		  $cod_2 =  $this->Session->read('cod_2');
		  $cod_3 =  $this->Session->read('cod_3');
		  $this->Session->write('cod_4',$var);

		  $cond .=" and cod_dir_superior=".$cod_1." and cod_coordinacion=".$cod_2." and  cod_secretaria=".$cod_3." and cod_direccion=".$var;
		  $lista=  $this->cugd02_division->generateList($cond, 'cod_division ASC', null, '{n}.cugd02_division.cod_division', '{n}.cugd02_division.denominacion');
          if($lista!=null){
          	$this->concatena($lista, 'vector');
          }else{
          	$this->set('vector',array());
          }
		break;
		case 'departamento':
		  $this->set('SELECT','oficina');
		  $this->set('codigo','departamento');
		  $this->set('seleccion','');
		  $this->set('n',6);
		  $cod_1 =  $this->Session->read('cod_1');
		  $cod_2 =  $this->Session->read('cod_2');
		  $cod_3 =  $this->Session->read('cod_3');
		  $cod_4 =  $this->Session->read('cod_4');
		  $this->Session->write('cod_5',$var);
		  $cond .=" and cod_dir_superior=".$cod_1." and cod_coordinacion=".$cod_2." and  cod_secretaria=".$cod_3." and cod_direccion=".$cod_4." and cod_division=".$var;
		  $lista=  $this->cugd02_departamento->generateList($cond, 'cod_departamento ASC', null, '{n}.cugd02_departamento.cod_departamento', '{n}.cugd02_departamento.denominacion');
          if($lista!=null){
          	$this->concatena($lista, 'vector');
          }else{
          	$this->set('vector',array());
          }
		break;
		case 'oficina':
		  $this->set('SELECT','oficina');
		  $this->set('codigo','oficina');
		  $this->set('seleccion','');
		  $this->set('n',7);
		  $this->set('no','no');
		  $cod_1 =  $this->Session->read('cod_1');
		  $cod_2 =  $this->Session->read('cod_2');
		  $cod_3 =  $this->Session->read('cod_3');
		  $cod_4 =  $this->Session->read('cod_4');
		  $cod_5 =  $this->Session->read('cod_5');
		  $this->Session->write('cod_6',$var);
		  $cond .=" and cod_dir_superior=".$cod_1." and cod_coordinacion=".$cod_2." and  cod_secretaria=".$cod_3." and cod_direccion=".$cod_4." and cod_division=".$cod_5." and cod_departamento=".$var;
		  $lista=  $this->cugd02_oficina->generateList($cond, 'cod_oficina ASC', null, '{n}.cugd02_oficina.cod_oficina', '{n}.cugd02_oficina.denominacion');
          if($lista!=null){
          	$this->concatena($lista, 'vector');
          }else{
          	$this->set('vector',array());
          }
		break;
	}
	}else{
		echo "";
	}
}//fin select ubicacion administrativa


function mostrar($select=null,$var=null) {
    $this->layout = "ajax";
if(isset($var) && !empty($var) && $var!=''){
	$cond = "cod_tipo_institucion=".$this->verifica_SS(3)." and cod_institucion=".$this->verifica_SS(4)." and cod_dependencia=".$this->verifica_SS(5);
    switch($select){
		case 'coordinacion':
		   $cod_1 =  $this->Session->read('cod_1');
		   $this->Session->write('cod_2',$var);
		   $cond .=" and cod_dir_superior=".$cod_1." and cod_coordinacion=".$var;
		   $a=  $this->cugd02_coordinacion->findAll($cond);
		   if($a!=null){
		  	  echo "<input type='text' name='data[ccfp01_division][cod_div_contable1]' value='".$a[0]['cugd02_coordinacion']['denominacion']."'  id='codigos2' readonly='readonly' class='inputtext' />";
		   }else{
		  	  echo "<input type='text' name='data[ccfp01_division][cod_div_contable1]' value=''   readonly='readonly' class='inputtext' />";
		   }
			echo "<script>";
				echo "document.getElementById('codigos3').value='';";
				echo "document.getElementById('codigos4').value='';";
				echo "document.getElementById('codigos5').value='';";
				echo "document.getElementById('codigos6').value='';";
				echo "document.getElementById('codigos7').value='';";
			echo "</script>";
 		break;
		case 'secretaria':
		  $cod_1 =  $this->Session->read('cod_1');
		  $cod_2 =  $this->Session->read('cod_2');
		  $this->Session->write('cod_3',$var);

		  $cond .=" and cod_dir_superior=".$cod_1." and cod_coordinacion=".$cod_2." and cod_secretaria=".$var;
		  $a=  $this->cugd02_secretaria->findAll($cond);
		  if($a!=null){
		  	 echo "<input type='text' name='data[ccfp01_division][cod_div_contable2]' value='".$a[0]['cugd02_secretaria']['denominacion']."'  id='codigos3' readonly='readonly'  class='inputtext' />";
		  }else{
		  	 echo "<input type='text' name='data[ccfp01_division][cod_div_contable2]' value=''   readonly='readonly' class='inputtext' />";
		  }
			echo "<script>";
				echo "document.getElementById('codigos4').value='';";
				echo "document.getElementById('codigos5').value='';";
				echo "document.getElementById('codigos6').value='';";
				echo "document.getElementById('codigos7').value='';";
			echo "</script>";
 		break;
		case 'direccion':
		  $cod_1 =  $this->Session->read('cod_1');
		  $cod_2 =  $this->Session->read('cod_2');
		  $cod_3 =  $this->Session->read('cod_3');
		  $this->Session->write('cod_4',$var);

		  $cond .=" and cod_dir_superior=".$cod_1." and cod_coordinacion=".$cod_2." and  cod_secretaria=".$cod_3." and cod_direccion=".$var;
		  $a=  $this->cugd02_direccion->findAll($cond);
		   if($a!=null){
		  	 echo "<input type='text' name='data[ccfp01_division][cod_div_contable3]' value='".$a[0]['cugd02_direccion']['denominacion']."'  id='codigos4'   readonly='readonly'  class='inputtext' />";
		  }else{
		  	 echo "<input type='text' name='data[ccfp01_division][cod_div_contable3]' value=''   readonly='readonly' class='inputtext' />";
		  }
			echo "<script>";
				echo "document.getElementById('codigos5').value='';";
				echo "document.getElementById('codigos6').value='';";
				echo "document.getElementById('codigos7').value='';";
			echo "</script>";
  		break;
		case 'division':
		  $cod_1 =  $this->Session->read('cod_1');
		  $cod_2 =  $this->Session->read('cod_2');
		  $cod_3 =  $this->Session->read('cod_3');
		  $cod_4 =  $this->Session->read('cod_4');
		  $this->Session->write('cod_5',$var);

		  $cond .=" and cod_dir_superior=".$cod_1." and cod_coordinacion=".$cod_2." and  cod_secretaria=".$cod_3." and cod_direccion=".$cod_4." and cod_division=".$var;
		  $a=  $this->cugd02_division->findAll($cond);
		   if($a!=null){
		  	 echo "<input type='text' name='data[ccfp01_division][cod_div_contable4]' value='".$a[0]['cugd02_division']['denominacion']."'  id='codigos5'  readonly='readonly'  class='inputtext' />";
		  }else{
		  	 echo "<input type='text' name='data[ccfp01_division][cod_div_contable4]' value=''   readonly='readonly' class='inputtext' />";
		  }
			echo "<script>";
				echo "document.getElementById('codigos6').value='';";
				echo "document.getElementById('codigos7').value='';";
			echo "</script>";
		break;
		case 'departamento':
		  $cod_1 =  $this->Session->read('cod_1');
		  $cod_2 =  $this->Session->read('cod_2');
		  $cod_3 =  $this->Session->read('cod_3');
		  $cod_4 =  $this->Session->read('cod_4');
		  $cod_5 =  $this->Session->read('cod_5');
		  $this->Session->write('cod_6',$var);

		  $cond .=" and cod_dir_superior=".$cod_1." and cod_coordinacion=".$cod_2." and  cod_secretaria=".$cod_3." and cod_direccion=".$cod_4." and cod_division=".$cod_5." and cod_departamento=".$var;
		  $a =  $this->cugd02_departamento->findAll($cond);
		   if($a!=null){
		  	 echo "<input type='text' name='data[ccfp01_division][cod_div_contable4]' value='".$a[0]['cugd02_departamento']['denominacion']."'  id='codigos6' readonly='readonly'  class='inputtext' />";
		  }else{
		  	 echo "<input type='text' name='data[ccfp01_division][cod_div_contable4]' value=''   readonly='readonly' class='inputtext' />";
		  }
			echo "<script>";
				echo "document.getElementById('codigos7').value='';";
			echo "</script>";
		break;
		case 'oficina':
		  $cod_1 =  $this->Session->read('cod_1');
		  $cod_2 =  $this->Session->read('cod_2');
		  $cod_3 =  $this->Session->read('cod_3');
		  $cod_4 =  $this->Session->read('cod_4');
		  $cod_5 =  $this->Session->read('cod_5');
		  $cod_6 =  $this->Session->read('cod_6');
		  $this->Session->write('cod_7',$var);

		  $cond .=" and cod_dir_superior=".$cod_1." and cod_coordinacion=".$cod_2." and  cod_secretaria=".$cod_3." and cod_direccion=".$cod_4." and cod_division=".$cod_5." and cod_departamento=".$cod_6." and cod_oficina=".$var;
		  $a =  $this->cugd02_oficina->findAll($cond);
		   if($a!=null){
		  	 echo "<input type='text' name='data[ccfp01_division][cod_div_contable4]' value='".$a[0]['cugd02_oficina']['denominacion']."'  id='codigos7' readonly='readonly'  class='inputtext' />";
		  }else{
		  	 echo "<input type='text' name='data[ccfp01_division][cod_div_contable4]' value=''   readonly='readonly' class='inputtext' />";
		  }
		break;
	}//fin switch
    }else{
      echo "<input type='text' name='data[ccfp01_division][cod_div_contable4]' value=''   readonly='readonly' class='inputtext' />";
    }

}//fin mostrar ubicacion administrativa




function select2($select=null,$var=null) {//ubicacion geografica
	$this->layout = "ajax";
	$cod_presi = $this->Session->read('SScodpresi');
if(isset($var) && !empty($var) && $var!=''){
    $cond ="cod_republica=".$cod_presi;
	switch($select){
		case 'municipio':
		  $this->set('SELECT','parroquia');
		  $this->set('codigo','municipio');
		  $this->set('seleccion','');
		  $this->set('n',2);
		  $this->Session->write('codg_1',$var);
		  $cond .=" and cod_estado=".$var;
		  $lista=  $this->cugd01_municipios->generateList($cond, 'cod_municipio ASC', null, '{n}.cugd01_municipios.cod_municipio', '{n}.cugd01_municipios.denominacion');
          if($lista!=null){
          	$this->concatena($lista, 'vector');
          }else{
          	$this->set('vector',array());
          }
		break;
		case 'parroquia':
		  $this->set('SELECT','centropoblado');
		  $this->set('codigo','parroquia');
		  $this->set('seleccion','');
		  $this->set('n',3);
		  $cod_1 =  $this->Session->read('codg_1');
		  $this->Session->write('codg_2',$var);
		  $cond .=" and cod_estado=".$cod_1." and cod_municipio=".$var;
		  $lista=  $this->cugd01_parroquias->generateList($cond, 'cod_parroquia ASC', null, '{n}.cugd01_parroquias.cod_parroquia', '{n}.cugd01_parroquias.denominacion');
         if($lista!=null){
          	$this->concatena($lista, 'vector');
          }else{
          	$this->set('vector',array());
          }
		break;
		case 'centropoblado':
		  $this->set('SELECT','centropoblado');
		  $this->set('codigo','centropoblado');
		  $this->set('seleccion','');
		  $this->set('n',4);
		  $this->set('no','no');
		  $cod_1 =  $this->Session->read('codg_1');
		  $cod_2 =  $this->Session->read('codg_2');
		  $this->Session->write('codg_3',$var);
		  $cond .=" and cod_estado=".$cod_1." and cod_municipio=".$cod_2." and  cod_parroquia=".$var;
		  $lista=  $this->cugd01_centropoblados->generateList($cond, 'cod_centro ASC', null, '{n}.cugd01_centropoblados.cod_centro', '{n}.cugd01_centropoblados.denominacion');
          if($lista!=null){
          	$this->concatena($lista, 'vector');
          }else{
          	$this->set('vector',array());
          }
		break;
	}//fin switch
	}else{
		echo "";
	}
}//fin select ubicacion geografica






function mostrar2($select=null,$var=null) {//ubicacion geografica
	$this->layout = "ajax";
	$cod_presi = $this->Session->read('SScodpresi');
if(isset($var) && !empty($var) && $var!=''){
    $cond ="cod_republica=".$cod_presi;
	switch($select){
		case 'estado':
		$this->Session->delete('esta');
		  $this->Session->write('esta',$var);
		  $cond .=" and cod_estado=".$var;
		  $a=  $this->cugd01_estados->findAll($cond);
		   if($a!=null){
		  	 echo "<input type='text' name='data[ccfp01_division][cod_div_contable1]' value='".$a[0]['cugd01_estados']['denominacion']."'  id='zona1' readonly='readonly' class='inputtext' />";
		  }else{
		  	 echo "<input type='text' name='data[ccfp01_division][cod_div_contable1]' value=''   readonly='readonly' class='inputtext' />";
		  }
		  echo "<script>";
				echo "document.getElementById('zona2').value='';";
				echo "document.getElementById('zona3').value='';";
				echo "document.getElementById('zona4').value='';";
			echo "</script>";
		break;
		case 'municipio':
			$cod_1=$this->Session->read('codg_1');

		    $cond .=" and cod_estado=".$cod_1." and cod_municipio=".$var;
		    $a=  $this->cugd01_municipios->findAll($cond);
		    if($a!=null){
		  	  echo "<input type='text' name='data[ccfp01_division][cod_div_contable1]' value='".$a[0]['cugd01_municipios']['denominacion']."'  id='zona2' readonly='readonly' class='inputtext' />";
		    }else{
		  	  echo "<input type='text' name='data[ccfp01_division][cod_div_contable1]' value=''   readonly='readonly' class='inputtext' />";
		    }
		    echo "<script>";
				echo "document.getElementById('zona3').value='';";
				echo "document.getElementById('zona4').value='';";
			echo "</script>";
		break;
		case 'parroquia':
			$cod_1=$this->Session->read('codg_1');
		  	$cod_2 =  $this->Session->read('codg_2');

   		    $cond .=" and cod_estado=".$cod_1." and cod_municipio=".$cod_2." and cod_parroquia=".$var;
		    $a=  $this->cugd01_parroquias->findAll($cond);
		    if($a!=null){
		  	   echo "<input type='text' name='data[ccfp01_division][cod_div_contable1]' value='".$a[0]['cugd01_parroquias']['denominacion']."'  id='zona3' readonly='readonly' class='inputtext' />";
		    }else{
		  	   echo "<input type='text' name='data[ccfp01_division][cod_div_contable1]' value=''   readonly='readonly' class='inputtext' />";
		    }
		    echo "<script>";
				echo "document.getElementById('zona4').value='';";
			echo "</script>";
		break;
		case 'centropoblado':
			$cod_1=$this->Session->read('codg_1');
		  	$cod_2 =  $this->Session->read('codg_2');
		  	$cod_3 =  $this->Session->read('codg_3');

		    $cond .=" and cod_estado=".$cod_1." and cod_municipio=".$cod_2." and  cod_parroquia=".$cod_3." and cod_centro=".$var;
		    $a=  $this->cugd01_centropoblados->findAll($cond);
		    if($a!=null){
		  	  echo "<input type='text' name='data[ccfp01_division][cod_div_contable1]' value='".$a[0]['cugd01_centropoblados']['denominacion']."'  id='zona4' readonly='readonly' class='inputtext' />";
		    }else{
		  	  echo "<input type='text' name='data[ccfp01_division][cod_div_contable1]' value=''   readonly='readonly' class='inputtext' />";
		    }
		break;
	}//fin switch
	}else{
		echo "<input type='text' name='data[ccfp01_division][cod_div_contable1]' value=''   readonly='readonly' class='inputtext' />";
	}
}//fin select ubicacion geografica




function select3($select=null,$var=null) { //select codigos presupuestarios
	$this->layout = "ajax";
if($select!=null && $var!=null){
    $cond =$this->SQLCA();
	switch($select){
		case 'sector':
		  $this->set('SELECT','programa');
		  $this->set('codigo','sector');
		  $this->set('seleccion','');
		  $this->set('n',2);
		  $this->Session->write('ano',$var);
		  $cond .=" and ano=".$var;
		  $lista=  $this->cfpd02_sector->generateList($cond, 'cod_sector ASC', null, '{n}.cfpd02_sector.cod_sector', '{n}.cfpd02_sector.denominacion');
          if($lista!=null){
          	$this->concatena($lista, 'vector');
          }else{
          	$this->set('vector',array());
          }
           echo "<script>";
           		echo "document.getElementById('presupuesto2').value='';";
				echo "document.getElementById('presupuesto3').value='';";
				echo "document.getElementById('presupuesto4').value='';";
				echo "document.getElementById('presupuesto5').value='';";
				echo "document.getElementById('presupuesto6').value='';";
				echo "document.getElementById('presupuesto7').value='';";
				echo "document.getElementById('presupuesto8').value='';";
				echo "document.getElementById('presupuesto9').value='';";
				echo "document.getElementById('presupuesto10').value='';";
				echo "document.getElementById('presupuesto11').value='';";
			echo "</script>";
		break;
		case 'programa':
		  $this->set('SELECT','subprograma');
		  $this->set('codigo','programa');
		  $this->set('seleccion','');
		  $this->set('n',3);
		  $ano =  $this->Session->read('ano');
		  $this->Session->write('dsec',$var);
		  $cond .=" and ano=".$ano." and cod_sector=".$var;
		  $lista=  $this->cfpd02_programa->generateList($cond, 'cod_programa ASC', null, '{n}.cfpd02_programa.cod_programa', '{n}.cfpd02_programa.denominacion');
          if($lista!=null){
          	$this->concatena($lista, 'vector');
          }else{
          	$this->set('vector',array());
          }
		break;
		case 'subprograma':
		  $this->set('SELECT','proyecto');
		  $this->set('codigo','subprograma');
		  $this->set('seleccion','');
		  $this->set('n',4);
		  $ano =  $this->Session->read('ano');
		  $sec =  $this->Session->read('dsec');
		  $this->Session->write('dprog',$var);
		  $cond .=" and ano=".$ano." and cod_sector=".$sec." and cod_programa=".$var;
		  $lista=  $this->cfpd02_sub_prog->generateList($cond, 'cod_sub_prog ASC', null, '{n}.cfpd02_sub_prog.cod_sub_prog', '{n}.cfpd02_sub_prog.denominacion');
          if($lista!=null){
          	$this->concatena($lista, 'vector');
          }else{
          	$this->set('vector',array());
          }
		break;
		case 'proyecto':
		  $this->set('SELECT','actividad');
		  $this->set('codigo','proyecto');
		  $this->set('seleccion','');
		  $this->set('n',5);
		  $ano =  $this->Session->read('ano');
		  $sec =  $this->Session->read('dsec');
		  $prog =  $this->Session->read('dprog');
		  $this->Session->write('dsubp',$var);
		  $cond .=" and ano=".$ano." and cod_sector=".$sec." and cod_programa=".$prog." and cod_sub_prog=".$var;
		  $lista=  $this->cfpd02_proyecto->generateList($cond, 'cod_proyecto ASC', null, '{n}.cfpd02_proyecto.cod_proyecto', '{n}.cfpd02_proyecto.denominacion');
          if($lista!=null){
          	$this->concatena($lista, 'vector');
          }else{
          	$this->set('vector',array());
          }
		break;
		case 'actividad':
		  $this->set('SELECT','partida');
		  $this->set('codigo','actividad');
		  $this->set('seleccion','');
		  $this->set('n',6);
		  $ano =  $this->Session->read('ano');
		  $sec =  $this->Session->read('dsec');
		  $prog =  $this->Session->read('dprog');
		  $subp =  $this->Session->read('dsubp');
		  $this->Session->write('proy',$var);
		  $cond .=" and ano=".$ano." and cod_sector=".$sec." and cod_programa=".$prog." and cod_sub_prog=".$subp." and cod_proyecto=".$var;
		  $lista=  $this->cfpd02_activ_obra->generateList($cond, 'cod_activ_obra ASC', null, '{n}.cfpd02_activ_obra.cod_activ_obra', '{n}.cfpd02_activ_obra.denominacion');
          if($lista!=null){
          	$this->concatena($lista, 'vector');
          }else{
          	$this->set('vector',array());
          }
		break;
		case 'partida':
		  $this->set('SELECT','generica');
		  $this->set('codigo','partida');
		  $this->set('seleccion','');
		  $this->set('n',7);
		  $ano =  $this->Session->read('ano');
		  $sec =  $this->Session->read('dsec');
		  $prog =  $this->Session->read('dprog');
		  $subp =  $this->Session->read('dsubp');
		  $proy =  $this->Session->read('proy');
		  $this->Session->write('actividad',$var);
		  $condicion = $this->condicionNDEP();
		  $cond2 ="ejercicio=".$ano." and cod_grupo=".CE." and cod_partida in(1,3,7)";
		  $lista=  $this->cfpd01_ano_partida->generateList($cond2, 'cod_partida ASC', null, '{n}.cfpd01_ano_partida.cod_partida', '{n}.cfpd01_ano_partida.denominacion');
          if($lista!=null){
          	$this->concatena($lista, 'vector',CE);
          }else{
          	$this->set('vector',array());
          }
		break;
		case 'generica':
		  $this->set('SELECT','especifica');
		  $this->set('codigo','generica');
		  $this->set('seleccion','');
		  $this->set('n',8);
		  $ano =  $this->Session->read('ano');
		  $sec =  $this->Session->read('dsec');
		  $prog =  $this->Session->read('dprog');
		  $subp =  $this->Session->read('dsubp');
		  $proy =  $this->Session->read('proy');
		  $activ = $this->Session->read('actividad');
		  $this->Session->write('cpar',$var);
		  if($var<10){$var = '0'.$var;}
		  $cond2 ="ejercicio=".$ano." and cod_grupo=".CE." and cod_partida=".$var;
		  $lista=  $this->cfpd01_ano_generica->generateList($cond2, 'cod_generica ASC', null, '{n}.cfpd01_ano_generica.cod_generica', '{n}.cfpd01_ano_generica.denominacion');
          if($lista!=null){
          	$this->concatena($lista, 'vector');
          }else{
          	$this->set('vector',array());
          }
 		break;
		case 'especifica':
		  $this->set('SELECT','subespecifica');
		  $this->set('codigo','especifica');
		  $this->set('seleccion','');
		  $this->set('n',9);
		  $ano =  $this->Session->read('ano');
		  $sec =  $this->Session->read('dsec');
		  $prog =  $this->Session->read('dprog');
		  $subp =  $this->Session->read('dsubp');
		  $proy =  $this->Session->read('proy');
		  $activ = $this->Session->read('actividad');
		  $cpar =  $this->Session->read('cpar');
		  $this->Session->write('cgen',$var);
		  $cond2 ="ejercicio=".$ano." and cod_grupo=".CE." and cod_partida=".$cpar." and cod_generica=".$var;
		  $lista = $this->cfpd01_ano_especifica->generateList($cond2, 'cod_especifica ASC', null, '{n}.cfpd01_ano_especifica.cod_especifica', '{n}.cfpd01_ano_especifica.denominacion');
          if($lista!=null){
          	$this->concatena($lista, 'vector');
          }else{
          	$this->set('vector',array());
          }
		break;
		case 'subespecifica':
		  $this->set('SELECT','auxiliar');
		  $this->set('codigo','subespecifica');
		  $this->set('seleccion','');
		  $this->set('n',10);
		  $ano =  $this->Session->read('ano');
		  $sec =  $this->Session->read('dsec');
		  $prog =  $this->Session->read('dprog');
		  $subp =  $this->Session->read('dsubp');
		  $proy =  $this->Session->read('proy');
		  $activ = $this->Session->read('actividad');
		  $cpar =  $this->Session->read('cpar');
		  $cgen =  $this->Session->read('cgen');
		  $this->Session->write('cesp',$var);
		  $cond2 ="ejercicio=".$ano." and cod_grupo=".CE." and cod_partida=".$cpar." and cod_generica=".$cgen." and cod_especifica=".$var;
		  $lista=  $this->cfpd01_ano_sub_espec->generateList($cond2, 'cod_sub_espec ASC', null, '{n}.cfpd01_ano_sub_espec.cod_sub_espec', '{n}.cfpd01_ano_sub_espec.denominacion');

          if($lista!=null){
          	$this->concatena($lista, 'vector');
          }else{
          	$this->set('vector',array());
          }
		break;
		case 'auxiliar':
		  $this->set('SELECT','auxiliar');
		  $this->set('codigo','auxiliar');
		  $this->set('seleccion','');
		  $this->set('n',11);
		  $this->set('no','no');
		  $ano =  $this->Session->read('ano');
		  $sec =  $this->Session->read('dsec');
		  $prog =  $this->Session->read('dprog');
		  $subp =  $this->Session->read('dsubp');
		  $proy =  $this->Session->read('proy');
		  $activ = $this->Session->read('actividad');
		  $cpar =  $this->Session->read('cpar');
		  $cgen =  $this->Session->read('cgen');
		  $cesp =  $this->Session->read('cesp');
		  $this->Session->write('csesp',$var);
		  $cod_partida=CE.mascara($cpar,2);
		  $cond3 =" and cod_partida=".$cod_partida." and cod_generica=".$cgen." and cod_especifica=".$cesp." and cod_sub_espec=".$var;
		  $cond2 =" and ano=".$ano." and cod_sector=".$sec." and cod_programa=".$prog." and cod_sub_prog=".$subp." and cod_proyecto=".$proy." and cod_activ_obra=".$activ;
		  $lista=  $this->cfpd05_auxiliar->generateList($cond." ".$cond2.$cond3, 'cod_auxiliar ASC', null, '{n}.cfpd05_auxiliar.cod_auxiliar', '{n}.cfpd05_auxiliar.denominacion');
            if($lista!=null){
            	$this->concatena($lista, 'vector');
            }else{
            	$this->set('vector',array('0'=>'00'));
            }

		break;
	}//fin wsitch
	}else{
		  $this->set('SELECT','');
		  $this->set('codigo','');
		  $this->set('seleccion','');
		  $this->set('n',11);
		  $this->set('no','no');
		 $this->set('vector',array('0'=>'00'));
	}
}//fin select codigos presupuestarios



function mostrar3($select=null,$var=null) { //mostrar3 codigos presupuestarios
	$this->layout = "ajax";
if(isset($var) && $var!=""){
	if(isset($_SESSION['ano'])){
		$ano =  $this->Session->read('ano');
	}else{
		$ano =$this->ano_ejecucion();
	}

    $cond =$this->SQLCA()." and ano=".$ano;
	switch($select){
		case 'sector':
			  $cond .=" and ano=".$ano." and cod_sector=".$var;
			  $a=  $this->cfpd02_sector->findAll($cond);
			  if($a!=null){
			  	 echo "<input type='text' name='data[ccfp01_division][cod_div_contable1]' value='".$a[0]['cfpd02_sector']['denominacion']."'  id='presupuesto2' readonly='readonly' class='inputtext' />";
			  }else{
			  	 echo "<input type='text' name='data[ccfp01_division][cod_div_contable1]' value=''   readonly='readonly' class='inputtext' />";
			  }
			  echo "<script>";
					echo "document.getElementById('presupuesto3').value='';";
					echo "document.getElementById('presupuesto4').value='';";
					echo "document.getElementById('presupuesto5').value='';";
					echo "document.getElementById('presupuesto6').value='';";
					echo "document.getElementById('presupuesto7').value='';";
					echo "document.getElementById('presupuesto8').value='';";
					echo "document.getElementById('presupuesto9').value='';";
					echo "document.getElementById('presupuesto10').value='';";
					echo "document.getElementById('presupuesto11').value='';";
			  echo "</script>";
		break;
		case 'programa':
			  $sec =  $this->Session->read('dsec');

			  $cond .=" and cod_sector=".$sec." and cod_programa=".$var;
			  $a=  $this->cfpd02_programa->findAll($cond);
			  if($a!=null){
			  	 echo "<input type='text' name='data[ccfp01_division][cod_div_contable1]' value='".$a[0]['cfpd02_programa']['denominacion']."'  id='presupuesto3' readonly='readonly' class='inputtext' />";
			  }else{
			  	 echo "<input type='text' name='data[ccfp01_division][cod_div_contable1]' value=''   readonly='readonly' class='inputtext' />";
			  }
			   echo "<script>";
					echo "document.getElementById('presupuesto4').value='';";
					echo "document.getElementById('presupuesto5').value='';";
					echo "document.getElementById('presupuesto6').value='';";
					echo "document.getElementById('presupuesto7').value='';";
					echo "document.getElementById('presupuesto8').value='';";
					echo "document.getElementById('presupuesto9').value='';";
					echo "document.getElementById('presupuesto10').value='';";
					echo "document.getElementById('presupuesto11').value='';";
			   echo "</script>";
		break;
		case 'subprograma':
			  $sec =  $this->Session->read('dsec');
			  $prog =  $this->Session->read('dprog');

			  $cond .=" and cod_sector=".$sec." and cod_programa=".$prog." and cod_sub_prog=".$var;
			  $a=  $this->cfpd02_sub_prog->findAll($cond);
			   if($a!=null){
			  	 echo "<input type='text' name='data[ccfp01_division][cod_div_contable1]' value='".$a[0]['cfpd02_sub_prog']['denominacion']."'  id='presupuesto4' readonly='readonly' class='inputtext' />";
			  }else{
			  	 echo "<input type='text' name='data[ccfp01_division][cod_div_contable1]' value=''   readonly='readonly' class='inputtext' />";
			  }
			   echo "<script>";
					echo "document.getElementById('presupuesto5').value='';";
					echo "document.getElementById('presupuesto6').value='';";
					echo "document.getElementById('presupuesto7').value='';";
					echo "document.getElementById('presupuesto8').value='';";
					echo "document.getElementById('presupuesto9').value='';";
					echo "document.getElementById('presupuesto10').value='';";
					echo "document.getElementById('presupuesto11').value='';";
			   echo "</script>";
		break;
		case 'proyecto':
			  $var= empty($var) ? 0 :  $var;
			  $sec =  $this->Session->read('dsec');
			  $prog =  $this->Session->read('dprog');
			  $subprog =  $this->Session->read('dsubp');

			  $cond .=" and cod_sector=".$sec." and cod_programa=".$prog." and cod_sub_prog=".$subprog." and cod_proyecto=".$var;
			  if(empty($var)){
			  	      echo "<input type='text' name='data[ccfp01_division][cod_div_contable1]' value='N/A'  id='presupuesto5' readonly='readonly' class='inputtext' />";
			  }else{
			  		  $a=  $this->cfpd02_proyecto->findAll($cond);
					  if($a!=null){
					  	    echo "<input type='text' name='data[ccfp01_division][cod_div_contable1]' value='".$a[0]['cfpd02_proyecto']['denominacion']."'  id='presupuesto5' readonly='readonly' class='inputtext' />";
					  }else{
					  	    echo "<input type='text' name='data[ccfp01_division][cod_div_contable1]' value=''   readonly='readonly' class='inputtext' />";
					  }
			  }
			 echo "<script>";
					echo "document.getElementById('presupuesto6').value='';";
					echo "document.getElementById('presupuesto7').value='';";
					echo "document.getElementById('presupuesto8').value='';";
					echo "document.getElementById('presupuesto9').value='';";
					echo "document.getElementById('presupuesto10').value='';";
					echo "document.getElementById('presupuesto11').value='';";
			 echo "</script>";
		break;
		case 'actividad':
			  $sec =  $this->Session->read('dsec');
			  $prog =  $this->Session->read('dprog');
			  $subprog =  $this->Session->read('dsubp');
			  $proy =  $this->Session->read('proy');

			  $proy= empty($proy) ? 0 :  $proy;
			  $cond .=" and cod_sector=".$sec." and cod_programa=".$prog." and cod_sub_prog=".$subprog." and cod_proyecto=".$proy." and cod_activ_obra=".$var;
			  $a=  $this->cfpd02_activ_obra->findAll($cond);
			  if($a!=null){
			  	 echo "<input type='text' name='data[ccfp01_division][cod_div_contable1]' value='".$a[0]['cfpd02_activ_obra']['denominacion']."'  id='presupuesto6' readonly='readonly' class='inputtext' />";
			  }else{
			  	 echo "<input type='text' name='data[ccfp01_division][cod_div_contable1]' value=''   readonly='readonly' class='inputtext' />";
			  }
			  echo "<script>";
					echo "document.getElementById('presupuesto7').value='';";
					echo "document.getElementById('presupuesto8').value='';";
					echo "document.getElementById('presupuesto9').value='';";
					echo "document.getElementById('presupuesto10').value='';";
					echo "document.getElementById('presupuesto11').value='';";
			  echo "</script>";
		break;
		case 'partida':
			  $cond2 ="ejercicio=".$ano." and cod_grupo=".CE." and cod_partida=".$var;
			  $a=  $this->cfpd01_ano_partida->findAll($cond2);
			  if($a!=null){
			  	 echo "<input type='text' name='data[ccfp01_division][cod_div_contable1]' value='".$a[0]['cfpd01_ano_partida']['denominacion']."'  id='presupuesto7' readonly='readonly' class='inputtext' />";
			  }else{
			  	 echo "<input type='text' name='data[ccfp01_division][cod_div_contable1]' value=''   readonly='readonly' class='inputtext' />";
			  }
			  echo "<script>";
					echo "document.getElementById('presupuesto8').value='';";
					echo "document.getElementById('presupuesto9').value='';";
					echo "document.getElementById('presupuesto10').value='';";
					echo "document.getElementById('presupuesto11').value='';";
			  echo "</script>";
		break;
		case 'generica':
			  $dpar=  $this->Session->read('cpar');

			  $cond2 ="ejercicio=".$ano." and cod_grupo=".CE." and cod_partida=".$dpar." and cod_generica=".$var;
			  $a=  $this->cfpd01_ano_generica->findAll($cond2);
			  if($a!=null){
			  	 echo "<input type='text' name='data[ccfp01_division][cod_div_contable1]' value='".$a[0]['cfpd01_ano_generica']['denominacion']."'  id='presupuesto8' readonly='readonly' class='inputtext' />";
			  }else{
			  	 echo "<input type='text' name='data[ccfp01_division][cod_div_contable1]' value=''   readonly='readonly' class='inputtext' />";
			  }
			  echo "<script>";
				echo "document.getElementById('presupuesto9').value='';";
				echo "document.getElementById('presupuesto10').value='';";
				echo "document.getElementById('presupuesto11').value='';";
			  echo "</script>";
		break;
		case 'especifica':
			   $dpar=  $this->Session->read('cpar');
			   $dgen =  $this->Session->read('cgen');

			   $cond2 ="ejercicio=".$ano." and cod_grupo=".CE." and cod_partida=".$dpar." and cod_generica=".$dgen." and cod_especifica=".$var;
			   $a=  $this->cfpd01_ano_especifica->findAll($cond2);
			   if($a!=null){
			  	 echo "<input type='text' name='data[ccfp01_division][cod_div_contable1]' value='".$a[0]['cfpd01_ano_especifica']['denominacion']."'  id='presupuesto9' readonly='readonly' class='inputtext' />";
			   }else{
			  	 echo "<input type='text' name='data[ccfp01_division][cod_div_contable1]' value=''   readonly='readonly' class='inputtext' />";
			   }
			   echo "<script>";
					echo "document.getElementById('presupuesto10').value='';";
					echo "document.getElementById('presupuesto11').value='';";
				echo "</script>";
		break;
		case 'subespecifica':
			  $dpar=  $this->Session->read('cpar');
			  $dgen =  $this->Session->read('cgen');
			  $desp =  $this->Session->read('cesp');

			  $cond2 ="ejercicio=".$ano." and cod_grupo=".CE." and cod_partida=".$dpar." and cod_generica=".$dgen." and cod_especifica=".$desp." and cod_sub_espec=".$var;
			  $a=  $this->cfpd01_ano_sub_espec->findAll($cond2);
			  if($a!=null){
			  	 echo "<input type='text' name='data[ccfp01_division][cod_div_contable1]' value='".$a[0]['cfpd01_ano_sub_espec']['denominacion']."'  id='presupuesto10' readonly='readonly' class='inputtext' />";
			  }else{
			  	 echo "<input type='text' name='data[ccfp01_division][cod_div_contable1]' value=''   readonly='readonly' class='inputtext' />";
			  }
			  echo "<script>";
					echo "document.getElementById('presupuesto11').value='';";
			  echo "</script>";
		break;
		case 'auxiliar':
			  $ano =  $this->Session->read('ano');
			  $sec =  $this->Session->read('dsec');
			  $prog =  $this->Session->read('dprog');
			  $subp =  $this->Session->read('dsubp');
			  $proy =  $this->Session->read('proy');
			  $activ = $this->Session->read('actividad');
			  $cpar =  $this->Session->read('cpar');
			  $cgen =  $this->Session->read('cgen');
			  $cesp =  $this->Session->read('cesp');
			  $dsubesp =  $this->Session->read('csesp');
			  $cod_partida=CE.mascara($cpar,2);
			  $cond3 =" and cod_partida=".$cod_partida." and cod_generica=".$cgen." and cod_especifica=".$cesp." and cod_sub_espec=".$dsubesp." and cod_auxiliar=".$var;
			  $cond2 =" and ano=".$ano." and cod_sector=".$sec." and cod_programa=".$prog." and cod_sub_prog=".$subp." and cod_proyecto=".$proy." and cod_activ_obra=".$activ;

			  $a=  $this->cfpd05_auxiliar->findAll($this->SQLCA()." ".$cond2.$cond3);
			  if($a!=null){
			  	 echo "<input type='text' name='data[ccfp01_division][cod_div_contable1]' value='".$a[0]['cfpd05_auxiliar']['denominacion']."'  id='presupuesto11' readonly='readonly' class='inputtext' />";
			  }else{
			  	 echo "<input type='text' name='data[ccfp01_division][cod_div_contable1]' value=''   readonly='readonly' class='inputtext' />";
			  }
		break;
	}//fin switch
	}else{
		if($var==0){
			echo "<input type='text' name='data[ccfp01_division][cod_div_contable1]' value='N/A'  id='presupuesto5' readonly='readonly' class='inputtext' />";
		}else{
			echo "<input type='text' name='data[ccfp01_division][cod_div_contable1]' value=''  id='presupuesto5' readonly='readonly' class='inputtext' />";
		}
	}
}//fin mostrar3 codigos presupuestarios





}//fin class
?>
