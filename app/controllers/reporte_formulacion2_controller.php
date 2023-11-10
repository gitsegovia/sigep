<?php

class ReporteFormulacion2Controller extends AppController{


    var $name    = "reporte_formulacion2";
    var $uses    = array('ccfd04_cierre_mes','cfpd01_formulacion','cfpd08_identificacion_alcaldia_concejales','cfpd08_identificacion_alcaldia_directivos',
    					'cfpd08_identificacion_alcaldia','cfpd02_sector');



// agregar estos modelos para los reportes de hacienda	'shd100_patente','shd001_registro_contribuyentes','shd100_solicitud','v_shd100_declaracion_ingreso'

    var $helpers = array('Html', 'Javascript', 'Ajax', 'Sisap');

//,'v_balance_ejecucion_partidas_inst','v_balance_ejecucion_partidas_dep'




function checkSession(){
				if (!$this->Session->check('Usuario')){
						$this->redirect('/salir/');
						exit();
				}else{
					$this->requestAction('/usuarios/actualizar_user');
				}
}//fin checksession





function beforeFilter(){$this->checkSession();}

function verifica_SS($i){
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
    function SQLCA_report($pre=null){
         $sql_re = "cod_presi=".$this->verifica_SS(1)."  and    ";
         $sql_re .= "cod_entidad=".$this->verifica_SS(2)."  and  ";
         $sql_re .= "cod_tipo_inst=".$this->verifica_SS(3)."  and ";
         if($pre!=null && $pre==1){
         $sql_re .= "cod_inst=".$this->verifica_SS(4)." ";
         //$sql_re .= "cod_dep=0";
         }else{
         	$sql_re .= "cod_inst=".$this->verifica_SS(4)."  and  ";
            $sql_re .= "cod_dep=".$this->verifica_SS(5)." ";
         }

         return $sql_re;
    }//fin funcion SQLCA
    function SQLCA_report_a($pre=null){
         $sql_re = "a.cod_presi=".$this->verifica_SS(1)."  and    ";
         $sql_re .= "a.cod_entidad=".$this->verifica_SS(2)."  and  ";
         $sql_re .= "a.cod_tipo_inst=".$this->verifica_SS(3)."  and ";
         if($pre!=null && $pre==1){
         $sql_re .= "a.cod_inst=".$this->verifica_SS(4)." ";
         //$sql_re .= "cod_dep=0";
         }else{
         	$sql_re .= "a.cod_inst=".$this->verifica_SS(4)."  and  ";
            $sql_re .= "a.cod_dep=".$this->verifica_SS(5)." ";
         }

         return $sql_re;
    }//fin funcion SQLCA
    function SQLCA_report_in($pre=null){
         $sql_re = $this->verifica_SS(1).",";
         $sql_re .= $this->verifica_SS(2).",";
         $sql_re .= $this->verifica_SS(3).",";
         if($pre!=null && $pre==1){
         $sql_re .= $this->verifica_SS(4).",";
         $sql_re .= 0;
         }else{
         	$sql_re .= $this->verifica_SS(4).",";
            $sql_re .= $this->verifica_SS(5)." ";
         }

         return $sql_re;
    }//fin funcion SQLCA



function cfpp08_identificacion_aldaldias($var1=null, $var2=null){
    $cod_presi                =       $this->Session->read('SScodpresi');
	$cod_entidad              =       $this->Session->read('SScodentidad');
	$cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
	$cod_inst                 =       $this->Session->read('SScodinst');
	$cod_dep                  =       $this->Session->read('SScoddep');
	$SScoddeporig             =       $this->Session->read('SScoddeporig');
	$SScoddep                 =       $this->Session->read('SScoddep');
	$Modulo                   =       $this->Session->read('Modulo');

	if($var1==1){

           $this->layout = "ajax";
           $opcion = 1;
			$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst;
           $year = $this->cfpd01_formulacion->findAll($condicion, null, 'ano_formular ASC', null);
			$dato = null;
			foreach($year as $year){
				$dato = $year['cfpd01_formulacion']['ano_formular'];
			}

			if(!empty($dato)){

				$this->set('year', $dato);

			}else{
				$this->set('year', '');
			}

			  for($minCount = 2007; $minCount < 2030; $minCount++) {
			    $anos[sprintf('%02d', $minCount)] = sprintf('%02d', $minCount);
			    $this->set('anos',$anos);
			    $this->set('ano_formulacion',$dato);
		       }



     }else{

           $this->layout = "pdf";
           $opcion = 2;
		   $vec=null;
		   $this->Session->delete('dat');
			if(!empty($this->data['organismo']['ano'])){
					$ano = $this->data['organismo']['ano'];
					$n2=$this->cfpd08_identificacion_alcaldia->execute("select * from cfpd08_identificacion_alcaldia_concejales where ".$this->SQLCA()." and ejercicio_fiscal=".$ano." order by cod_concejal");
					$n1=$this->cfpd08_identificacion_alcaldia->execute("select * from cfpd08_identificacion_alcaldia_directivos where ".$this->SQLCA()." and ejercicio_fiscal=".$ano." order by cod_directivo");
					$datos=$this->cfpd08_identificacion_alcaldia->execute("select * from cfpd08_identificacion_alcaldia where ".$this->SQLCA()." and ejercicio_fiscal=".$ano);

				if($datos!=null){

						if(count($n1)>=count($n2)){
									for($i=0;$i<count($n1);$i++){
										$vec[$i]['cod_directivo']=$n1[$i][0]['cod_directivo'];
										$vec[$i]['nombre_directivo']=$n1[$i][0]['nombres_apellidos'];
										$vec[$i]['telefonos']=$n1[$i][0]['telefonos'];
										$vec[$i]['correo']=$n1[$i][0]['direccion_electronica'];
										$vec[$i]['cod_concejal']='';
										$vec[$i]['nombres_apellidos']='';
									}

									for($i=0;$i<count($n2);$i++){
										$vec[$i]['cod_concejal']=$n2[$i][0]['cod_concejal'];
										$vec[$i]['nombres_apellidos']=$n2[$i][0]['nombres_apellidos'];
									}
						}else{

									for($i=0;$i<count($n2);$i++){
										$vec[$i]['cod_directivo']='';
										$vec[$i]['nombre_directivo']='';
										$vec[$i]['telefonos']='';
										$vec[$i]['correo']='';
										$vec[$i]['cod_concejal']=$n2[$i][0]['cod_concejal'];
										$vec[$i]['nombres_apellidos']=$n2[$i][0]['nombres_apellidos'];
									}

									for($i=0;$i<count($n1);$i++){
										$vec[$i]['cod_directivo']=$n1[$i][0]['cod_directivo'];
										$vec[$i]['nombre_directivo']=$n1[$i][0]['nombres_apellidos'];
										$vec[$i]['telefonos']=$n1[$i][0]['telefonos'];
										$vec[$i]['correo']=$n1[$i][0]['direccion_electronica'];
									}

						}
						$this->Session->write('dat',$datos);
						$this->set('datos',$datos);
						$this->set('vector',$vec);

				}else{
					$this->set('datos',null);
					$this->set('vector',null);
				}
			}else{
				$this->set('datos',null);
				$this->set('vector',null);
			}
   }//fin function


$this->set("opcion", $opcion);


}//fin cfpp08_identificacion_aldaldias






function objetivos_sectoriales_forma_2110($var1=null, $var2=null){
    $cod_presi                =       $this->Session->read('SScodpresi');
	$cod_entidad              =       $this->Session->read('SScodentidad');
	$cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
	$cod_inst                 =       $this->Session->read('SScodinst');
	$cod_dep                  =       $this->Session->read('SScoddep');
	$SScoddeporig             =       $this->Session->read('SScoddeporig');
	$SScoddep                 =       $this->Session->read('SScoddep');
	$Modulo                   =       $this->Session->read('Modulo');

if($var1==1){

           $this->layout = "ajax";
           $opcion = 1;

           $condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst;
           $year = $this->cfpd01_formulacion->findAll($condicion, null, 'ano_formular ASC', null);
			$dato = null;
			foreach($year as $year){
				$dato = $year['cfpd01_formulacion']['ano_formular'];
			}

			if(!empty($dato)){

				$this->set('year', $dato);

			}else{
				$this->set('year', '');
			}

		  	for($minCount = 2007; $minCount < 2030; $minCount++) {
			    $anos[sprintf('%02d', $minCount)] = sprintf('%02d', $minCount);
			    $this->set('anos',$anos);
			    $this->set('ano_formulacion',$dato);
	        }



 }else{

            $this->layout = "pdf";
            $opcion = 2;
			$this->Session->delete('ano_top');
	if(!empty($this->data['organismo']['ano'])){
            $ano = $this->data['organismo']['ano'];
            $this->Session->write('ano_top',$ano);
		  	$consolidado = 2;
		  	 if(isset($this->data['datos']['consolidacion'])){
		  	         	$consolidado = $this->data['datos']['consolidacion'];
		  	 }

		    $ejercicio = $ano;

            if($consolidado==2){
		  	     	$sql = $this->SQLCA_consolidado($consolidado);
		  	}else if($consolidado==1){
		  		    $sql = $this->SQLCA_consolidado($consolidado);
		  	}



		$datos=$this->cfpd02_sector->execute("select * from cfpd02_sector where ".$sql." and ano=".$ano." order by cod_presi,cod_entidad,cod_tipo_inst,cod_inst,cod_dep,cod_sector");

		if($datos!=null){
				$this->set('datos',$datos);
		}else{
			$this->set('datos',null);
		}

	}else{
		$this->set('datos',null);

	}


 }//fin function


$this->set("opcion", $opcion);


}//fin objetivos_sectoriales_forma_2110





function descripcion_programa_forma_2111a($var1=null, $var2=null){
    $cod_presi                =       $this->Session->read('SScodpresi');
	$cod_entidad              =       $this->Session->read('SScodentidad');
	$cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
	$cod_inst                 =       $this->Session->read('SScodinst');
	$cod_dep                  =       $this->Session->read('SScoddep');
	$SScoddeporig             =       $this->Session->read('SScoddeporig');
	$SScoddep                 =       $this->Session->read('SScoddep');
	$Modulo                   =       $this->Session->read('Modulo');

if($var1==1){

           $this->layout = "ajax";
           $opcion = 1;

           $condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst;
           $year = $this->cfpd01_formulacion->findAll($condicion, null, 'ano_formular ASC', null);
			$dato = null;
			foreach($year as $year){
				$dato = $year['cfpd01_formulacion']['ano_formular'];
			}

			if(!empty($dato)){

				$this->set('year', $dato);

			}else{
				$this->set('year', '');
			}

		  	for($minCount = 2007; $minCount < 2030; $minCount++) {
			    $anos[sprintf('%02d', $minCount)] = sprintf('%02d', $minCount);
			    $this->set('anos',$anos);
			    $this->set('ano_formulacion',$dato);
	        }



 }else{

            $this->layout = "pdf";
            $opcion = 2;
			$this->Session->delete('ano_top');
	if(!empty($this->data['organismo']['ano'])){
            $ano = $this->data['organismo']['ano'];
            $this->Session->write('ano_top',$ano);
		  	$consolidado = 2;
		  	 if(isset($this->data['datos']['consolidacion'])){
		  	         	$consolidado = $this->data['datos']['consolidacion'];
		  	 }

		    $ejercicio = $ano;

            if($consolidado==2){
		  	     	$sql = $this->SQLCA_consolidado($consolidado);
		  	}else if($consolidado==1){
		  		    $sql = $this->SQLCA_consolidado($consolidado);
		  	}

		$filtro = $sql." and a.ano=".$ano;
		$consulta="SELECT
				 a.cod_presi,
				 a.cod_entidad,
				 a.cod_tipo_inst,
				 a.cod_inst,
				 a.cod_dep,
				 a.ano,
				 a.cod_sector,
				 a.cod_programa,
				 (select b.denominacion from cfpd02_sector b where b.cod_presi=a.cod_presi and  b.cod_entidad=a.cod_entidad and b.cod_tipo_inst= a.cod_tipo_inst and b.cod_inst=a.cod_inst and b.cod_dep=a.cod_dep and b.ano=a.ano and b.cod_sector=a.cod_sector) as deno_sector,
				 a.denominacion as deno_programa,
				 a.unidad_ejecutora,
				 a.objetivo,
				 a.funcionario_responsable
				   FROM cfpd02_programa a WHERE ".$filtro."
				 ORDER BY
				 a.cod_presi,
				 a.cod_entidad,
				 a.cod_tipo_inst,
				 a.cod_inst,
				 a.cod_dep,
				 a.ano,
				 a.cod_sector,
				 a.cod_programa";

		$datos=$this->cfpd02_sector->execute($consulta);

		if($datos!=null){
				$this->set('datos',$datos);
		}else{
			$this->set('datos',null);
		}

	}else{
		$this->set('datos',null);

	}


 }//fin function


$this->set("opcion", $opcion);


}//fin descripcion_programa_forma_2111a








function descripcion_prog_subprog_forma_2111($var1=null, $var2=null){
    $cod_presi                =       $this->Session->read('SScodpresi');
	$cod_entidad              =       $this->Session->read('SScodentidad');
	$cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
	$cod_inst                 =       $this->Session->read('SScodinst');
	$cod_dep                  =       $this->Session->read('SScoddep');
	$SScoddeporig             =       $this->Session->read('SScoddeporig');
	$SScoddep                 =       $this->Session->read('SScoddep');
	$Modulo                   =       $this->Session->read('Modulo');

if($var1==1){

           $this->layout = "ajax";
           $opcion = 1;

           $condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst;
           $year = $this->cfpd01_formulacion->findAll($condicion, null, 'ano_formular ASC', null);
			$dato = null;
			foreach($year as $year){
				$dato = $year['cfpd01_formulacion']['ano_formular'];
			}

			if(!empty($dato)){

				$this->set('year', $dato);

			}else{
				$this->set('year', '');
			}

		  	for($minCount = 2007; $minCount < 2030; $minCount++) {
			    $anos[sprintf('%02d', $minCount)] = sprintf('%02d', $minCount);
			    $this->set('anos',$anos);
			    $this->set('ano_formulacion',$dato);
	        }



 }else{

            $this->layout = "pdf";
            $opcion = 2;
			$this->Session->delete('ano_top');
	if(!empty($this->data['organismo']['ano'])){
            $ano = $this->data['organismo']['ano'];
            $this->Session->write('ano_top',$ano);
		  	$consolidado = 2;
		  	 if(isset($this->data['datos']['consolidacion'])){
		  	         	$consolidado = $this->data['datos']['consolidacion'];
		  	 }

		    $ejercicio = $ano;

            if($consolidado==2){
		  	     	$sql = $this->SQLCA_consolidado($consolidado);
		  	}else if($consolidado==1){
		  		    $sql = $this->SQLCA_consolidado($consolidado);
		  	}

		$filtro = $sql." and a.ano=".$ano;
		$consulta="SELECT
				 a.cod_presi,
				 a.cod_entidad,
				 a.cod_tipo_inst,
				 a.cod_inst,
				 a.cod_dep,
				 a.ano,
				 a.cod_sector,
				 a.cod_programa,
				 a.cod_sub_prog,
				 (select b.denominacion from cfpd02_sector b where b.cod_presi=a.cod_presi and  b.cod_entidad=a.cod_entidad and b.cod_tipo_inst= a.cod_tipo_inst and b.cod_inst=a.cod_inst and b.cod_dep=a.cod_dep and b.ano=a.ano and b.cod_sector=a.cod_sector) as deno_sector,
				 (select b.denominacion from cfpd02_programa b where b.cod_presi=a.cod_presi and  b.cod_entidad=a.cod_entidad and b.cod_tipo_inst= a.cod_tipo_inst and b.cod_inst=a.cod_inst and b.cod_dep=a.cod_dep and b.ano=a.ano and b.cod_sector=a.cod_sector and b.cod_programa=a.cod_programa) as deno_programa,
				 a.denominacion as deno_sub_programa,
				 a.unidad_ejecutora,
				 a.objetivo,
				 a.funcionario_responsable
				   FROM cfpd02_sub_prog a WHERE ".$filtro."
				 ORDER BY
				 a.cod_presi,
				 a.cod_entidad,
				 a.cod_tipo_inst,
				 a.cod_inst,
				 a.cod_dep,
				 a.ano,
				 a.cod_sector,
				 a.cod_programa,
				 a.cod_sub_prog";

		$datos=$this->cfpd02_sector->execute($consulta);

		if($datos!=null){
				$this->set('datos',$datos);
		}else{
			$this->set('datos',null);
		}

	}else{
		$this->set('datos',null);

	}


 }//fin function


$this->set("opcion", $opcion);


}//fin descripcion_prog_subprog_forma_2111







function programas_sociales_sectores_forma_2119a($var1=null, $var2=null){
    $cod_presi                =       $this->Session->read('SScodpresi');
	$cod_entidad              =       $this->Session->read('SScodentidad');
	$cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
	$cod_inst                 =       $this->Session->read('SScodinst');
	$cod_dep                  =       $this->Session->read('SScoddep');
	$SScoddeporig             =       $this->Session->read('SScoddeporig');
	$SScoddep                 =       $this->Session->read('SScoddep');
	$Modulo                   =       $this->Session->read('Modulo');

if($var1==1){

           $this->layout = "ajax";
           $opcion = 1;

           $condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst;
           $year = $this->cfpd01_formulacion->findAll($condicion, null, 'ano_formular ASC', null);
			$dato = null;
			foreach($year as $year){
				$dato = $year['cfpd01_formulacion']['ano_formular'];
			}

			if(!empty($dato)){

				$this->set('year', $dato);

			}else{
				$this->set('year', '');
			}

		  	for($minCount = 2007; $minCount < 2030; $minCount++) {
			    $anos[sprintf('%02d', $minCount)] = sprintf('%02d', $minCount);
			    $this->set('anos',$anos);
			    $this->set('ano_formulacion',$dato);
	        }



 }else if($var1==2){
		 	$this->layout = "ajax";
			$opcion = 2;
			$this->set("opcion2", $var2);
		    $rs=$this->cfpd02_sector->execute("SELECT DISTINCT a.cod_sector, a.denominacion FROM cfpd02_sector a  WHERE ".$this->condicion()." order BY a.cod_sector ASC");
		    foreach($rs as $l){
		    	if(!isset($seleccion)){$seleccion = mascara2($l[0]["cod_sector"]);}
				$v[]=mascara2($l[0]["cod_sector"]);
				$d[]=mascara2($l[0]["cod_sector"])." - ".$l[0]["denominacion"];
			}
			$sector = array_combine($v, $d);
		    $this->set('lista_numero', $sector);



 }else{

            $this->layout = "pdf";
            $opcion = 3;
			$this->Session->delete('ano_top');
			if(!empty($this->data['organismo']['ano'])){
		            $ano = $this->data['organismo']['ano'];
		            $this->Session->write('ano_top',$ano);
				  	$consolidado = 2;
				  	 if(isset($this->data['datos']['consolidacion'])){
				  	         	$consolidado = $this->data['datos']['consolidacion'];
				  	 }

					if($consolidado==2){
					     $sql = $this->SQLCA_consolidado($consolidado);
					}else if($consolidado==1){
						 $sql = $this->SQLCA_consolidado($consolidado);
					}

				$filtro = $sql." and ano=".$ano;

					if(isset($this->data['organismo']['cod_sector']) && !empty($this->data['organismo']['cod_sector'])){
					  $filtro .= " and cod_sector='".$this->data['organismo']['cod_sector']."'";
					}


				$consulta=" SELECT
							a.cod_presi,
							a.cod_entidad,
							a.cod_tipo_inst,
							a.cod_inst,
							a.cod_dep,
							a.ano,
							a.cod_sector,
							(select b.denominacion from cfpd02_sector b where b.cod_presi=a.cod_presi and  b.cod_entidad=a.cod_entidad and b.cod_tipo_inst= a.cod_tipo_inst and b.cod_inst=a.cod_inst and b.cod_dep=a.cod_dep and b.ano=a.ano and b.cod_sector=a.cod_sector) as denominacion,
							(select b.unidad_ejecutora from cfpd02_sector b where b.cod_presi=a.cod_presi and  b.cod_entidad=a.cod_entidad and b.cod_tipo_inst= a.cod_tipo_inst and b.cod_inst=a.cod_inst and b.cod_dep=a.cod_dep and b.ano=a.ano and b.cod_sector=a.cod_sector) as unidad_ejecutora
  							FROM cfpd15 a WHERE ".$filtro." GROUP BY
  							a.cod_presi,
							a.cod_entidad,
							a.cod_tipo_inst,
							a.cod_inst,
							a.cod_dep,
							a.ano,
							a.cod_sector ORDER BY a.cod_presi,
							a.cod_entidad,
							a.cod_tipo_inst,
							a.cod_inst,
							a.cod_dep,
							a.ano,
							a.cod_sector";

				$consulta1="SELECT
							a.cod_presi,
							a.cod_entidad,
							a.cod_tipo_inst,
							a.cod_inst,
							a.cod_dep,
							a.ano,
							a.cod_sector,
							a.programa_social,
							a.organismo,
							a.asignacion_anual
							  FROM cfpd15 a WHERE ".$filtro."  ORDER BY a.cod_presi,
							a.cod_entidad,
							a.cod_tipo_inst,
							a.cod_inst,
							a.cod_dep,
							a.ano,
							a.cod_sector";

				$datos=$this->cfpd02_sector->execute($consulta);
				$datos2=$this->cfpd02_sector->execute($consulta1);

				if($datos2!=null){
						$this->set('datos',$datos);
						$this->set('datos2',$datos2);
				}else{

					if($datos!=null){
						$this->set('datos',$datos);
					}else{
						$this->set('datos',null);
					}
					$this->set('datos2',null);
				}

			}else{
				$this->set('datos2',null);
				$this->set('datos',null);

			}


 }//fin function


$this->set("opcion", $opcion);


}//fin descripcion_proyecto_forma_2117









function descripcion_proyecto_forma_2117($var1=null, $var2=null){
    $cod_presi                =       $this->Session->read('SScodpresi');
	$cod_entidad              =       $this->Session->read('SScodentidad');
	$cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
	$cod_inst                 =       $this->Session->read('SScodinst');
	$cod_dep                  =       $this->Session->read('SScoddep');
	$SScoddeporig             =       $this->Session->read('SScoddeporig');
	$SScoddep                 =       $this->Session->read('SScoddep');
	$Modulo                   =       $this->Session->read('Modulo');

if($var1==1){

           $this->layout = "ajax";
           $opcion = 1;

           $condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst;
           $year = $this->cfpd01_formulacion->findAll($condicion, null, 'ano_formular ASC', null);
			$dato = null;
			foreach($year as $year){
				$dato = $year['cfpd01_formulacion']['ano_formular'];
			}

			if(!empty($dato)){

				$this->set('year', $dato);

			}else{
				$this->set('year', '');
			}

		  	for($minCount = 2007; $minCount < 2030; $minCount++) {
			    $anos[sprintf('%02d', $minCount)] = sprintf('%02d', $minCount);
			    $this->set('anos',$anos);
			    $this->set('ano_formulacion',$dato);
	        }



 }else{

            $this->layout = "pdf";
            $opcion = 2;
			$this->Session->delete('ano_top');
	if(!empty($this->data['organismo']['ano'])){
            $ano = $this->data['organismo']['ano'];
            $this->Session->write('ano_top',$ano);
		  	$consolidado = 2;
		  	 if(isset($this->data['datos']['consolidacion'])){
		  	         	$consolidado = $this->data['datos']['consolidacion'];
		  	 }

		    $ejercicio = $ano;

            if($consolidado==2){
		  	     	$sql = $this->SQLCA_consolidado($consolidado);
		  	}else if($consolidado==1){
		  		    $sql = $this->SQLCA_consolidado($consolidado);
		  	}

		$filtro = $sql." and a.ano=".$ano;
		$consulta=" SELECT
				 a.cod_presi,
				 a.cod_entidad,
				 a.cod_tipo_inst,
				 a.cod_inst,
				 a.cod_dep,
				 a.ano,
				 a.cod_sector,
				 a.cod_programa,
				 a.cod_sub_prog,
				 a.cod_proyecto,
				 (select b.denominacion from cfpd02_sector b where b.cod_presi=a.cod_presi and  b.cod_entidad=a.cod_entidad and b.cod_tipo_inst= a.cod_tipo_inst and b.cod_inst=a.cod_inst and b.cod_dep=a.cod_dep and b.ano=a.ano and b.cod_sector=a.cod_sector) as deno_sector,
				 (select b.denominacion from cfpd02_programa b where b.cod_presi=a.cod_presi and  b.cod_entidad=a.cod_entidad and b.cod_tipo_inst= a.cod_tipo_inst and b.cod_inst=a.cod_inst and b.cod_dep=a.cod_dep and b.ano=a.ano and b.cod_sector=a.cod_sector and b.cod_programa=a.cod_programa) as deno_programa,
				 (select b.denominacion from cfpd02_sub_prog b where b.cod_presi=a.cod_presi and  b.cod_entidad=a.cod_entidad and b.cod_tipo_inst= a.cod_tipo_inst and b.cod_inst=a.cod_inst and b.cod_dep=a.cod_dep and b.ano=a.ano and b.cod_sector=a.cod_sector and b.cod_programa=a.cod_programa and b.cod_sub_prog=a.cod_sub_prog) as deno_sub_programa,
				 a.denominacion as deno_proyecto,
				 (select b.unidad_ejecutora from cfpd02_sector b where b.cod_presi=a.cod_presi and  b.cod_entidad=a.cod_entidad and b.cod_tipo_inst= a.cod_tipo_inst and b.cod_inst=a.cod_inst and b.cod_dep=a.cod_dep and b.ano=a.ano and b.cod_sector=a.cod_sector) as unidad_ejecutora_sector,
				 (select b.unidad_ejecutora from cfpd02_programa b where b.cod_presi=a.cod_presi and  b.cod_entidad=a.cod_entidad and b.cod_tipo_inst= a.cod_tipo_inst and b.cod_inst=a.cod_inst and b.cod_dep=a.cod_dep and b.ano=a.ano and b.cod_sector=a.cod_sector and b.cod_programa=a.cod_programa) as unidad_ejecutora_programa,
				 (select b.unidad_ejecutora from cfpd02_sub_prog b where b.cod_presi=a.cod_presi and  b.cod_entidad=a.cod_entidad and b.cod_tipo_inst= a.cod_tipo_inst and b.cod_inst=a.cod_inst and b.cod_dep=a.cod_dep and b.ano=a.ano and b.cod_sector=a.cod_sector and b.cod_programa=a.cod_programa and b.cod_sub_prog=a.cod_sub_prog) as unidad_ejecutora_sub_programa,
				 a.unidad_ejecutora as unidad_ejecutora_proyecto,
				 a.objetivo,
				 a.funcionario_responsable
				   FROM cfpd02_proyecto a WHERE ".$filtro."
				 ORDER BY
				  a.cod_presi,
				 a.cod_entidad,
				 a.cod_tipo_inst,
				 a.cod_inst,
				 a.cod_dep,
				 a.ano,
				 a.cod_sector,
				 a.cod_programa,
				 a.cod_sub_prog,
				 a.cod_proyecto";

		$datos=$this->cfpd02_sector->execute($consulta);

		if($datos!=null){
				$this->set('datos',$datos);
		}else{
			$this->set('datos',null);
		}

	}else{
		$this->set('datos',null);

	}


 }//fin function


$this->set("opcion", $opcion);


}//fin descripcion_proyecto_forma_2117







function programas_sociales_sectores_programas_forma_2119($var1=null, $var2=null){
    $cod_presi                =       $this->Session->read('SScodpresi');
	$cod_entidad              =       $this->Session->read('SScodentidad');
	$cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
	$cod_inst                 =       $this->Session->read('SScodinst');
	$cod_dep                  =       $this->Session->read('SScoddep');
	$SScoddeporig             =       $this->Session->read('SScoddeporig');
	$SScoddep                 =       $this->Session->read('SScoddep');
	$Modulo                   =       $this->Session->read('Modulo');

if($var1==1){

           $this->layout = "ajax";
           $opcion = 1;

           $condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst;
           $year = $this->cfpd01_formulacion->findAll($condicion, null, 'ano_formular ASC', null);
			$dato = null;
			foreach($year as $year){
				$dato = $year['cfpd01_formulacion']['ano_formular'];
			}

			if(!empty($dato)){

				$this->set('year', $dato);

			}else{
				$this->set('year', '');
			}

		  	for($minCount = 2007; $minCount < 2030; $minCount++) {
			    $anos[sprintf('%02d', $minCount)] = sprintf('%02d', $minCount);
			    $this->set('anos',$anos);
			    $this->set('ano_formulacion',$dato);
	        }



 }else if($var1==2){
		 	$this->layout = "ajax";
			$opcion = 2;
			$this->set("opcion2", $var2);
		    $rs=$this->cfpd02_sector->execute("SELECT DISTINCT a.cod_sector, a.denominacion FROM cfpd02_sector a  WHERE ".$this->condicion()." order BY a.cod_sector ASC");
		    foreach($rs as $l){
		    	if(!isset($seleccion)){$seleccion = mascara2($l[0]["cod_sector"]);}
				$v[]=mascara2($l[0]["cod_sector"]);
				$d[]=mascara2($l[0]["cod_sector"])." - ".$l[0]["denominacion"];
			}
			$sector = array_combine($v, $d);
		    $this->set('lista_numero', $sector);



 }else if($var1==4){
 			$this->layout = "ajax";
 			$opcion = 4;
		 	$rs=$this->cfpd02_sector->execute("SELECT DISTINCT a.cod_sector, a.cod_programa, a.denominacion FROM cfpd02_programa a  WHERE ".$this->condicion()." and a.cod_sector='".$var2."' order BY a.cod_sector, a.cod_programa ASC");
		    foreach($rs as $l){
				$v[]=mascara2($l[0]["cod_programa"]);
				$d[]=mascara2($l[0]["cod_programa"])." - ".$l[0]["denominacion"];
			}
			$programa= array_combine($v, $d);
	        $this->set('lista_numero', $programa);
	        $this->set('sector', $var2);
	        $this->set("opcion2", $var1);



 }else{

            $this->layout = "pdf";
            $opcion = 3;
			$this->Session->delete('ano_top');
			if(!empty($this->data['organismo']['ano'])){
		            $ano = $this->data['organismo']['ano'];
		            $this->Session->write('ano_top',$ano);
				  	$consolidado = 2;
				  	 if(isset($this->data['datos']['consolidacion'])){
				  	         	$consolidado = $this->data['datos']['consolidacion'];
				  	 }

					if($consolidado==2){
					     $sql = $this->SQLCA_consolidado($consolidado);
					}else if($consolidado==1){
						 $sql = $this->SQLCA_consolidado($consolidado);
					}

				$filtro = $sql." and ano=".$ano;

					if(isset($this->data['organismo']['cod_sector']) && !empty($this->data['organismo']['cod_sector'])){
					  $filtro .= " and cod_sector='".$this->data['organismo']['cod_sector']."'";
					}

					if(isset($this->data['organismo']['cod_programa']) && !empty($this->data['organismo']['cod_programa'])){
					  $filtro .= " and cod_programa='".$this->data['organismo']['cod_programa']."'";
					}


				$consulta=" SELECT
							a.cod_presi,
							a.cod_entidad,
							a.cod_tipo_inst,
							a.cod_inst,
							a.cod_dep,
							a.ano,
							a.cod_sector,
							a.cod_programa,
							(select b.denominacion from cfpd02_sector b where b.cod_presi=a.cod_presi and  b.cod_entidad=a.cod_entidad and b.cod_tipo_inst= a.cod_tipo_inst and b.cod_inst=a.cod_inst and b.cod_dep=a.cod_dep and b.ano=a.ano and b.cod_sector=a.cod_sector) as deno_sector,
							(select b.denominacion from cfpd02_programa b where b.cod_presi=a.cod_presi and  b.cod_entidad=a.cod_entidad and b.cod_tipo_inst= a.cod_tipo_inst and b.cod_inst=a.cod_inst and b.cod_dep=a.cod_dep and b.ano=a.ano and b.cod_sector=a.cod_sector and b.cod_programa=a.cod_programa) as deno_prog,
							(select b.unidad_ejecutora from cfpd02_sector b where b.cod_presi=a.cod_presi and  b.cod_entidad=a.cod_entidad and b.cod_tipo_inst= a.cod_tipo_inst and b.cod_inst=a.cod_inst and b.cod_dep=a.cod_dep and b.ano=a.ano and b.cod_sector=a.cod_sector) as unidad_ejecutora_sector,
							(select b.unidad_ejecutora from cfpd02_programa b where b.cod_presi=a.cod_presi and  b.cod_entidad=a.cod_entidad and b.cod_tipo_inst= a.cod_tipo_inst and b.cod_inst=a.cod_inst and b.cod_dep=a.cod_dep and b.ano=a.ano and b.cod_sector=a.cod_sector and b.cod_programa=a.cod_programa) as unidad_ejecutora_prog
  							FROM cfpd15 a WHERE ".$filtro." GROUP BY
  							a.cod_presi,
							a.cod_entidad,
							a.cod_tipo_inst,
							a.cod_inst,
							a.cod_dep,
							a.ano,
							a.cod_sector,a.cod_programa ORDER BY a.cod_presi,
							a.cod_entidad,
							a.cod_tipo_inst,
							a.cod_inst,
							a.cod_dep,
							a.ano,
							a.cod_sector,a.cod_programa";

				$consulta1="SELECT
							a.cod_presi,
							a.cod_entidad,
							a.cod_tipo_inst,
							a.cod_inst,
							a.cod_dep,
							a.ano,
							a.cod_sector,
							a.cod_programa,
							a.programa_social,
							a.organismo,
							a.asignacion_anual
							  FROM cfpd15 a WHERE ".$filtro."  ORDER BY a.cod_presi,
							a.cod_entidad,
							a.cod_tipo_inst,
							a.cod_inst,
							a.cod_dep,
							a.ano,
							a.cod_sector,a.cod_programa";

				$datos=$this->cfpd02_sector->execute($consulta);
				$datos2=$this->cfpd02_sector->execute($consulta1);

				if($datos2!=null){
						$this->set('datos',$datos);
						$this->set('datos2',$datos2);
				}else{

					if($datos!=null){
						$this->set('datos',$datos);
					}else{
						$this->set('datos',null);
					}
					$this->set('datos2',null);
				}

			}else{
				$this->set('datos2',null);
				$this->set('datos',null);

			}


 }//fin function


$this->set("opcion", $opcion);


}//fin programas_sociales_sectores_programas_forma_2119






function forma_2127($var1=null, $var2=null){
    $cod_presi                =       $this->Session->read('SScodpresi');
	$cod_entidad              =       $this->Session->read('SScodentidad');
	$cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
	$cod_inst                 =       $this->Session->read('SScodinst');
	$cod_dep                  =       $this->Session->read('SScoddep');
	$SScoddeporig             =       $this->Session->read('SScoddeporig');
	$SScoddep                 =       $this->Session->read('SScoddep');
	$Modulo                   =       $this->Session->read('Modulo');

if($var1==1){

           $this->layout = "ajax";
           $opcion = 1;

           $condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst;
           $year = $this->cfpd01_formulacion->findAll($condicion, null, 'ano_formular ASC', null);
			$dato = null;
			foreach($year as $year){
				$dato = $year['cfpd01_formulacion']['ano_formular'];
			}

			if(!empty($dato)){

				$this->set('year', $dato);

			}else{
				$this->set('year', '');
			}

		  	for($minCount = 2007; $minCount < 2030; $minCount++) {
			    $anos[sprintf('%02d', $minCount)] = sprintf('%02d', $minCount);
			    $this->set('anos',$anos);
			    $this->set('ano_formulacion',$dato);
	        }



 }else{

            $this->layout = "pdf";
            $opcion = 2;
			$this->Session->delete('ano_top');
	if(!empty($this->data['organismo']['ano'])){
            $ano = $this->data['organismo']['ano'];
            $this->Session->write('ano_top',$ano);
		  	$consolidado = 2;
		  	 if(isset($this->data['datos']['consolidacion'])){
		  	         	$consolidado = $this->data['datos']['consolidacion'];
		  	 }


		$filtro = $this->SQLCA()." and a.ano=".$ano;
		$consulta="SELECT * FROM v_cfpd03_ano_pasado_actual a WHERE ".$filtro;

		$datos=$this->cfpd02_sector->execute($consulta);

		if($datos!=null){
				$this->set('datos',$datos);
		}else{
			$this->set('datos',null);
		}

	}else{
		$this->set('datos',null);

	}


 }//fin function


$this->set("opcion", $opcion);


}//fin forma_2127





function forma_2132($var1=null, $var2=null){
    $cod_presi                =       $this->Session->read('SScodpresi');
	$cod_entidad              =       $this->Session->read('SScodentidad');
	$cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
	$cod_inst                 =       $this->Session->read('SScodinst');
	$cod_dep                  =       $this->Session->read('SScoddep');
	$SScoddeporig             =       $this->Session->read('SScoddeporig');
	$SScoddep                 =       $this->Session->read('SScoddep');
	$Modulo                   =       $this->Session->read('Modulo');

if($var1==1){

           $this->layout = "ajax";
           $opcion = 1;

           $condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst;
           $year = $this->cfpd01_formulacion->findAll($condicion, null, 'ano_formular ASC', null);
			$dato = null;
			foreach($year as $year){
				$dato = $year['cfpd01_formulacion']['ano_formular'];
			}

			if(!empty($dato)){

				$this->set('year', $dato);

			}else{
				$this->set('year', '');
			}

		  	for($minCount = 2007; $minCount < 2030; $minCount++) {
			    $anos[sprintf('%02d', $minCount)] = sprintf('%02d', $minCount);
			    $this->set('anos',$anos);
			    $this->set('ano_formulacion',$dato);
	        }



 }else{

            $this->layout = "pdf";
            $opcion = 2;
			$this->Session->delete('ano_top');
	if(!empty($this->data['organismo']['ano'])){
            $ano = $this->data['organismo']['ano'];
            $this->Session->write('ano_top',$ano);
		  	$consolidado = 2;
		  	 if(isset($this->data['datos']['consolidacion'])){
		  	         	$consolidado = $this->data['datos']['consolidacion'];
		  	 }


		$filtro = $this->SQLCA()." and ano_formulacion=".$ano;
		$consulta="SELECT * FROM cfpd19_participacion_financiera a WHERE ".$filtro;

		$datos=$this->cfpd02_sector->execute($consulta);

		if($datos!=null){
				$this->set('datos',$datos);
		}else{
			$this->set('datos',null);
		}

	}else{
		$this->set('datos',null);

	}


 }//fin function


$this->set("opcion", $opcion);


}//fin forma_2132







function forma_2129($var1=null, $var2=null){
    $cod_presi                =       $this->Session->read('SScodpresi');
	$cod_entidad              =       $this->Session->read('SScodentidad');
	$cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
	$cod_inst                 =       $this->Session->read('SScodinst');
	$cod_dep                  =       $this->Session->read('SScoddep');
	$SScoddeporig             =       $this->Session->read('SScoddeporig');
	$SScoddep                 =       $this->Session->read('SScoddep');
	$Modulo                   =       $this->Session->read('Modulo');

if($var1==1){

           $this->layout = "ajax";
           $opcion = 1;

           $condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst;
           $year = $this->cfpd01_formulacion->findAll($condicion, null, 'ano_formular ASC', null);
			$dato = null;
			foreach($year as $year){
				$dato = $year['cfpd01_formulacion']['ano_formular'];
			}

			if(!empty($dato)){

				$this->set('year', $dato);

			}else{
				$this->set('year', '');
			}

		  	for($minCount = 2007; $minCount < 2030; $minCount++) {
			    $anos[sprintf('%02d', $minCount)] = sprintf('%02d', $minCount);
			    $this->set('anos',$anos);
			    $this->set('ano_formulacion',$dato);
	        }



 }else{

            $this->layout = "pdf";
            $opcion = 2;
			$this->Session->delete('ano_top');
	if(!empty($this->data['organismo']['ano'])){
            $ano = $this->data['organismo']['ano'];
            $this->Session->write('ano_top',$ano);
		  	$consolidado = 2;
		  	 if(isset($this->data['datos']['consolidacion'])){
		  	         	$consolidado = $this->data['datos']['consolidacion'];
		  	 }


		$filtro = $this->SQLCA()." and a.ano_formulacion=".$ano;
		$consulta1="SELECT
					a.cod_presi,
					a.cod_entidad,
					a.cod_tipo_inst,
					a.cod_inst,
					a.cod_dep,
					a.ano_formulacion,
					a.cod_sindicato,
					(select b.denominacion from cfpd18_contrato_colectivo_sindicato b WHERE b.cod_presi = a.cod_presi AND b.cod_entidad = a.cod_entidad AND b.cod_tipo_inst = a.cod_tipo_inst AND b.cod_inst = a.cod_inst AND b.cod_dep = a.cod_dep AND b.cod_sindicato=a.cod_sindicato) as deno_sindicato,
					(select count(b.cod_cargo) from cnmd05 b WHERE b.cod_presi = a.cod_presi AND b.cod_entidad = a.cod_entidad AND b.cod_tipo_inst = a.cod_tipo_inst AND b.cod_inst = a.cod_inst AND b.cod_dep = a.cod_dep) as numero_trabajadores_anterior ,
					(select count(b.cod_cargo) from cfpd97 b WHERE b.cod_presi = a.cod_presi AND b.cod_entidad = a.cod_entidad AND b.cod_tipo_inst = a.cod_tipo_inst AND b.cod_inst = a.cod_inst AND b.cod_dep = a.cod_dep) as numero_trabajadores_actual ,
					(select sum(b.sueldo_basico) from cnmd05 b WHERE b.cod_presi = a.cod_presi AND b.cod_entidad = a.cod_entidad AND b.cod_tipo_inst = a.cod_tipo_inst AND b.cod_inst = a.cod_inst AND b.cod_dep = a.cod_dep) as monto_anual_anterior ,
					(select sum(b.sueldo_basico) from cfpd97 b WHERE b.cod_presi = a.cod_presi AND b.cod_entidad = a.cod_entidad AND b.cod_tipo_inst = a.cod_tipo_inst AND b.cod_inst = a.cod_inst AND b.cod_dep = a.cod_dep) as monto_anual_actual
					  FROM cfpd18_contrato_colectivo_detalles a where ".$filtro." GROUP BY a.cod_presi,
					a.cod_entidad,
					a.cod_tipo_inst,
					a.cod_inst,
					a.cod_dep,
					a.ano_formulacion,
					a.cod_sindicato ORDER BY a.cod_presi,
					a.cod_entidad,
					a.cod_tipo_inst,
					a.cod_inst,
					a.cod_dep,
					a.ano_formulacion,
					a.cod_sindicato";


		$consulta2="SELECT
					a.cod_presi,
					a.cod_entidad,
					a.cod_tipo_inst,
					a.cod_inst,
					a.cod_dep,
					a.ano_formulacion,
					a.cod_sindicato,
					(select b.denominacion from cfpd18_contrato_colectivo_sindicato b WHERE b.cod_presi = a.cod_presi AND b.cod_entidad = a.cod_entidad AND b.cod_tipo_inst = a.cod_tipo_inst AND b.cod_inst = a.cod_inst AND b.cod_dep = a.cod_dep AND b.cod_sindicato=a.cod_sindicato) as deno_sindicato,
					a.cod_clausula,
					(select b.denominacion_clausula from cfpd18_contrato_colectivo_clausulas b WHERE b.cod_presi = a.cod_presi AND b.cod_entidad = a.cod_entidad AND b.cod_tipo_inst = a.cod_tipo_inst AND b.cod_inst = a.cod_inst AND b.cod_dep = a.cod_dep AND b.ano_formulacion=a.ano_formulacion and b.cod_sindicato=a.cod_sindicato and b.cod_clausula=a.cod_clausula) as deno_clausula,
					a.cod_partida,
					a.cod_generica,
					a.cod_especifica,
					a.cod_sub_espec,
					a.revisado_anterior,
					a.presupuestado_actual,
					a.base_calculo,
					(select b.fecha_contrato_inicio from cfpd18_contrato_colectivo_cuerpo b WHERE b.cod_presi = a.cod_presi AND b.cod_entidad = a.cod_entidad AND b.cod_tipo_inst = a.cod_tipo_inst AND b.cod_inst = a.cod_inst AND b.cod_dep = a.cod_dep AND b.ano_formulacion=a.ano_formulacion and b.cod_sindicato=a.cod_sindicato) as fecha_contrato_inicio,
					(select b.fecha_contrato_conclusion from cfpd18_contrato_colectivo_cuerpo b WHERE b.cod_presi = a.cod_presi AND b.cod_entidad = a.cod_entidad AND b.cod_tipo_inst = a.cod_tipo_inst AND b.cod_inst = a.cod_inst AND b.cod_dep = a.cod_dep AND b.ano_formulacion=a.ano_formulacion and b.cod_sindicato=a.cod_sindicato) as fecha_contrato_fin,
					(select count(b.cod_cargo) from cnmd05 b WHERE b.cod_presi = a.cod_presi AND b.cod_entidad = a.cod_entidad AND b.cod_tipo_inst = a.cod_tipo_inst AND b.cod_inst = a.cod_inst AND b.cod_dep = a.cod_dep) as numero_trabajadores_anterior ,
					(select count(b.cod_cargo) from cfpd97 b WHERE b.cod_presi = a.cod_presi AND b.cod_entidad = a.cod_entidad AND b.cod_tipo_inst = a.cod_tipo_inst AND b.cod_inst = a.cod_inst AND b.cod_dep = a.cod_dep) as numero_trabajadores_actual ,
					(select sum(b.sueldo_basico) from cnmd05 b WHERE b.cod_presi = a.cod_presi AND b.cod_entidad = a.cod_entidad AND b.cod_tipo_inst = a.cod_tipo_inst AND b.cod_inst = a.cod_inst AND b.cod_dep = a.cod_dep) as monto_anual_anterior ,
					(select sum(b.sueldo_basico) from cfpd97 b WHERE b.cod_presi = a.cod_presi AND b.cod_entidad = a.cod_entidad AND b.cod_tipo_inst = a.cod_tipo_inst AND b.cod_inst = a.cod_inst AND b.cod_dep = a.cod_dep) as monto_anual_actual
					  FROM cfpd18_contrato_colectivo_detalles a where ".$filtro."ORDER BY a.cod_presi,
					a.cod_entidad,
					a.cod_tipo_inst,
					a.cod_inst,
					a.cod_dep,
					a.ano_formulacion,
					a.cod_sindicato,a.cod_clausula";

		$datos1=$this->cfpd02_sector->execute($consulta1);
		$datos=$this->cfpd02_sector->execute($consulta2);
		if($datos!=null){
				$this->set('datos',$datos);
				$this->set('datos1',$datos1);
		}else{
			$this->set('datos',null);
		}

	}else{
		$this->set('datos',null);

	}


 }//fin function


$this->set("opcion", $opcion);


}//fin forma_2129









function forma_2119b($var1=null, $var2=null,$var3=null){
    $cod_presi                =       $this->Session->read('SScodpresi');
	$cod_entidad              =       $this->Session->read('SScodentidad');
	$cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
	$cod_inst                 =       $this->Session->read('SScodinst');
	$cod_dep                  =       $this->Session->read('SScoddep');
	$SScoddeporig             =       $this->Session->read('SScoddeporig');
	$SScoddep                 =       $this->Session->read('SScoddep');
	$Modulo                   =       $this->Session->read('Modulo');

if($var1==1){

           $this->layout = "ajax";
           $opcion = 1;

           $condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst;
           $year = $this->cfpd01_formulacion->findAll($condicion, null, 'ano_formular ASC', null);
			$dato = null;
			foreach($year as $year){
				$dato = $year['cfpd01_formulacion']['ano_formular'];
			}

			if(!empty($dato)){

				$this->set('year', $dato);

			}else{
				$this->set('year', '');
			}

		  	for($minCount = 2007; $minCount < 2030; $minCount++) {
			    $anos[sprintf('%02d', $minCount)] = sprintf('%02d', $minCount);
			    $this->set('anos',$anos);
			    $this->set('ano_formulacion',$dato);
	        }



 }else if($var1==2){
		 	$this->layout = "ajax";
			$opcion = 2;
			$this->set("opcion2", $var2);
		    $rs=$this->cfpd02_sector->execute("SELECT DISTINCT a.cod_sector, a.denominacion FROM cfpd02_sector a  WHERE ".$this->condicion()." order BY a.cod_sector ASC");
		    foreach($rs as $l){
		    	if(!isset($seleccion)){$seleccion = mascara2($l[0]["cod_sector"]);}
				$v[]=mascara2($l[0]["cod_sector"]);
				$d[]=mascara2($l[0]["cod_sector"])." - ".$l[0]["denominacion"];
			}
			$sector = array_combine($v, $d);
		    $this->set('lista_numero', $sector);



 }else if($var1==4){
 			$this->layout = "ajax";
 			$opcion = 4;
 			if($var2!=''){
 				$rs=$this->cfpd02_sector->execute("SELECT DISTINCT a.cod_sector, a.cod_programa, a.denominacion FROM cfpd02_programa a  WHERE ".$this->condicion()." and a.cod_sector='".$var2."' order BY a.cod_sector, a.cod_programa ASC");
			    foreach($rs as $l){
					$v[]=mascara2($l[0]["cod_programa"]);
					$d[]=mascara2($l[0]["cod_programa"])." - ".$l[0]["denominacion"];
				}
				$programa= array_combine($v, $d);
		        $this->set('lista_numero', $programa);
		        $this->set('sector', $var2);
		        $this->set("opcion2", $var1);
 			}else{
 				$this->set('lista_numero', array());
		        $this->set('sector', $var2);
		        $this->set("opcion2", $var1);

 			}




 }else if($var1==5){
 			$this->layout = "ajax";
			$opcion = 5;
			if($var3!=''){
				$rs=$this->cfpd02_sector->execute("SELECT DISTINCT a.cod_sector, a.cod_programa, a.cod_sub_prog,  a.denominacion FROM cfpd02_sub_prog a  WHERE ".$this->condicion()." and a.cod_sector='".$var2."'  and a.cod_programa='".$var3."' order BY a.cod_sector, a.cod_programa, a.cod_sub_prog ASC");
			    foreach($rs as $l){
					$v[]=mascara2($l[0]["cod_sub_prog"]);
					$d[]=mascara2($l[0]["cod_sub_prog"])." - ".$l[0]["denominacion"];
				}
				$sub_programa = array_combine($v, $d);
		        $this->set('lista_numero', $sub_programa);
		        $this->set('sector', $var2);
		        $this->set('programa', $var3);
			}else{

		        $this->set('lista_numero', array());
		        $this->set('sector', $var2);
		        $this->set('programa', $var3);

			}



 }else{

            $this->layout = "pdf";
            $opcion = 3;
			$this->Session->delete('ano_top');
			if(!empty($this->data['organismo']['ano'])){
		            $ano = $this->data['organismo']['ano'];
		            $this->Session->write('ano_top',$ano);
				  	$consolidado = 2;
				  	 if(isset($this->data['datos']['consolidacion'])){
				  	         	$consolidado = $this->data['datos']['consolidacion'];
				  	 }

					if($consolidado==2){
					     $sql = $this->SQLCA_consolidado($consolidado);
					}else if($consolidado==1){
						 $sql = $this->SQLCA_consolidado($consolidado);
					}

				$filtro = $sql." and ano=".$ano;

					if(isset($this->data['organismo']['cod_sector']) && !empty($this->data['organismo']['cod_sector'])){
					  $filtro .= " and cod_sector='".$this->data['organismo']['cod_sector']."'";
					}

					if(isset($this->data['organismo']['cod_programa']) && !empty($this->data['organismo']['cod_programa'])){
					  $filtro .= " and cod_programa='".$this->data['organismo']['cod_programa']."'";
					}

				 	if(isset($this->data['organismo']['cod_sub_prog']) && !empty($this->data['organismo']['cod_sub_prog'])){
         	     	  $filtro .= " and cod_sub_prog='".$this->data['organismo']['cod_sub_prog']."'";
                	}



				$consulta=" SELECT
							a.cod_presi,
							a.cod_entidad,
							a.cod_tipo_inst,
							a.cod_inst,
							a.cod_dep,
							a.ano,
							a.cod_sector,
							a.cod_programa,
							a.cod_sub_prog,
							(select b.denominacion from cfpd02_sector b where b.cod_presi=a.cod_presi and  b.cod_entidad=a.cod_entidad and b.cod_tipo_inst= a.cod_tipo_inst and b.cod_inst=a.cod_inst and b.cod_dep=a.cod_dep and b.ano=a.ano and b.cod_sector=a.cod_sector) as deno_sector,
							(select b.denominacion from cfpd02_programa b where b.cod_presi=a.cod_presi and  b.cod_entidad=a.cod_entidad and b.cod_tipo_inst= a.cod_tipo_inst and b.cod_inst=a.cod_inst and b.cod_dep=a.cod_dep and b.ano=a.ano and b.cod_sector=a.cod_sector and b.cod_programa=a.cod_programa) as deno_prog,
							(select b.denominacion from cfpd02_sub_prog b where b.cod_presi=a.cod_presi and  b.cod_entidad=a.cod_entidad and b.cod_tipo_inst= a.cod_tipo_inst and b.cod_inst=a.cod_inst and b.cod_dep=a.cod_dep and b.ano=a.ano and b.cod_sector=a.cod_sector and b.cod_programa=a.cod_programa and b.cod_sub_prog=a.cod_sub_prog) as deno_sub_prog,
							(select b.unidad_ejecutora from cfpd02_sector b where b.cod_presi=a.cod_presi and  b.cod_entidad=a.cod_entidad and b.cod_tipo_inst= a.cod_tipo_inst and b.cod_inst=a.cod_inst and b.cod_dep=a.cod_dep and b.ano=a.ano and b.cod_sector=a.cod_sector) as unidad_ejecutora_sector,
							(select b.unidad_ejecutora from cfpd02_programa b where b.cod_presi=a.cod_presi and  b.cod_entidad=a.cod_entidad and b.cod_tipo_inst= a.cod_tipo_inst and b.cod_inst=a.cod_inst and b.cod_dep=a.cod_dep and b.ano=a.ano and b.cod_sector=a.cod_sector and b.cod_programa=a.cod_programa) as unidad_ejecutora_prog,
							(select b.unidad_ejecutora from cfpd02_sub_prog b where b.cod_presi=a.cod_presi and  b.cod_entidad=a.cod_entidad and b.cod_tipo_inst= a.cod_tipo_inst and b.cod_inst=a.cod_inst and b.cod_dep=a.cod_dep and b.ano=a.ano and b.cod_sector=a.cod_sector and b.cod_programa=a.cod_programa and b.cod_sub_prog=a.cod_sub_prog) as unidad_ejecutora_sub_prog
  							FROM cfpd15 a WHERE ".$filtro." GROUP BY
  							a.cod_presi,
							a.cod_entidad,
							a.cod_tipo_inst,
							a.cod_inst,
							a.cod_dep,
							a.ano,
							a.cod_sector,a.cod_programa,a.cod_sub_prog ORDER BY a.cod_presi,
							a.cod_entidad,
							a.cod_tipo_inst,
							a.cod_inst,
							a.cod_dep,
							a.ano,
							a.cod_sector,a.cod_programa,a.cod_sub_prog";

				$consulta1="SELECT
							a.cod_presi,
							a.cod_entidad,
							a.cod_tipo_inst,
							a.cod_inst,
							a.cod_dep,
							a.ano,
							a.cod_sector,
							a.cod_programa,
							a.cod_sub_prog,
							a.programa_social,
							a.organismo,
							a.asignacion_anual
							  FROM cfpd15 a WHERE ".$filtro."  ORDER BY a.cod_presi,
							a.cod_entidad,
							a.cod_tipo_inst,
							a.cod_inst,
							a.cod_dep,
							a.ano,
							a.cod_sector,a.cod_programa,a.cod_sub_prog";

				$datos=$this->cfpd02_sector->execute($consulta);
				$datos2=$this->cfpd02_sector->execute($consulta1);

				if($datos2!=null){
						$this->set('datos',$datos);
						$this->set('datos2',$datos2);
				}else{

					if($datos!=null){
						$this->set('datos',$datos);
					}else{
						$this->set('datos',null);
					}
					$this->set('datos2',null);
				}

			}else{
				$this->set('datos2',null);
				$this->set('datos',null);

			}


 }//fin function


$this->set("opcion", $opcion);


}//fin forma_2119b







function forma_2128($var1=null, $var2=null){
    $cod_presi                =       $this->Session->read('SScodpresi');
	$cod_entidad              =       $this->Session->read('SScodentidad');
	$cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
	$cod_inst                 =       $this->Session->read('SScodinst');
	$cod_dep                  =       $this->Session->read('SScoddep');
	$SScoddeporig             =       $this->Session->read('SScoddeporig');
	$SScoddep                 =       $this->Session->read('SScoddep');
	$Modulo                   =       $this->Session->read('Modulo');

if($var1==1){

           $this->layout = "ajax";
           $opcion = 1;

           $condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst;
           $year = $this->cfpd01_formulacion->findAll($condicion, null, 'ano_formular ASC', null);
			$dato = null;
			foreach($year as $year){
				$dato = $year['cfpd01_formulacion']['ano_formular'];
			}

			if(!empty($dato)){

				$this->set('year', $dato);

			}else{
				$this->set('year', '');
			}

		  	for($minCount = 2007; $minCount < 2030; $minCount++) {
			    $anos[sprintf('%02d', $minCount)] = sprintf('%02d', $minCount);
			    $this->set('anos',$anos);
			    $this->set('ano_formulacion',$dato);
	        }



 }else{

            $this->layout = "pdf";
            $opcion = 2;
			$this->Session->delete('ano_top');
	if(!empty($this->data['organismo']['ano'])){
            $ano = $this->data['organismo']['ano'];
            $this->Session->write('ano_top',$ano);
		  	$consolidado = 2;
		  	 if(isset($this->data['datos']['consolidacion'])){
		  	         	$consolidado = $this->data['datos']['consolidacion'];
		  	 }


		$filtro = $this->SQLCA()." and a.ano=".$ano;

		$consulta1=" SELECT
					a.cod_presi,
					a.cod_entidad,
					a.cod_tipo_inst,
					a.cod_inst,
					a.cod_dep,
					a.cod_estado,
					a.cod_organismo,
					a.cod_municipio,
					a.ano,
					(select b.denominacion from cfpd17_inversion_coordinada_estado b where b.cod_presi=a.cod_presi and  b.cod_entidad=a.cod_entidad and b.cod_tipo_inst= a.cod_tipo_inst and b.cod_inst=a.cod_inst and b.cod_dep=a.cod_dep and b.cod_estado=a.cod_estado) as deno_estado,
					(select b.denominacion from cfpd17_inversion_coordinada_organismo b where b.cod_presi=a.cod_presi and  b.cod_entidad=a.cod_entidad and b.cod_tipo_inst= a.cod_tipo_inst and b.cod_inst=a.cod_inst and b.cod_dep=a.cod_dep and b.cod_organismo=a.cod_organismo) as deno_organismo,
					(select b.denominacion from cfpd17_inversion_coordinada_municipio b where b.cod_presi=a.cod_presi and  b.cod_entidad=a.cod_entidad and b.cod_tipo_inst= a.cod_tipo_inst and b.cod_inst=a.cod_inst and b.cod_dep=a.cod_dep and b.cod_municipio=a.cod_municipio) as deno_municipio
					FROM cfpd17_inversion_coordinada a WHERE ".$filtro." GROUP BY
					a.cod_presi,
					a.cod_entidad,
					a.cod_tipo_inst,
					a.cod_inst,
					a.cod_dep,
					a.cod_estado,
					a.cod_organismo,
					a.cod_municipio,
					a.ano ";

					$consulta2="SELECT
								a.cod_presi,
								a.cod_entidad,
								a.cod_tipo_inst,
								a.cod_inst,
								a.cod_dep,
								a.cod_estado,
								a.cod_organismo,
								a.cod_municipio,
								a.ano,
								a.cod_sector,
								a.cod_programa,
								a.cod_sub_prog,
								a.cod_proyecto,
								a.cod_activ_obra,
								a.cod_partida,
								a.cod_generica,
								a.cod_especifica,
								a.cod_sub_espec,
								(select b.denominacion from cfpd02_sector b where b.cod_presi=a.cod_presi and  b.cod_entidad=a.cod_entidad and b.cod_tipo_inst= a.cod_tipo_inst and b.cod_inst=a.cod_inst and b.cod_dep=a.cod_dep and b.ano=a.ano and b.cod_sector=a.cod_sector) as deno_sector,
								(select b.denominacion from cfpd02_programa b where b.cod_presi=a.cod_presi and  b.cod_entidad=a.cod_entidad and b.cod_tipo_inst= a.cod_tipo_inst and b.cod_inst=a.cod_inst and b.cod_dep=a.cod_dep and b.ano=a.ano and b.cod_sector=a.cod_sector and b.cod_programa=a.cod_programa) as deno_prog,
								(select b.denominacion from cfpd02_sub_prog b where b.cod_presi=a.cod_presi and  b.cod_entidad=a.cod_entidad and b.cod_tipo_inst= a.cod_tipo_inst and b.cod_inst=a.cod_inst and b.cod_dep=a.cod_dep and b.ano=a.ano and b.cod_sector=a.cod_sector and b.cod_programa=a.cod_programa and b.cod_sub_prog=a.cod_sub_prog) as deno_sub_prog,
								(select b.denominacion from cfpd02_proyecto b where b.cod_presi=a.cod_presi and  b.cod_entidad=a.cod_entidad and b.cod_tipo_inst= a.cod_tipo_inst and b.cod_inst=a.cod_inst and b.cod_dep=a.cod_dep and b.ano=a.ano and b.cod_sector=a.cod_sector and b.cod_programa=a.cod_programa and b.cod_sub_prog=a.cod_sub_prog and b.cod_proyecto=a.cod_proyecto) as deno_proyecto,
								(select b.denominacion from cfpd02_activ_obra b where b.cod_presi=a.cod_presi and  b.cod_entidad=a.cod_entidad and b.cod_tipo_inst= a.cod_tipo_inst and b.cod_inst=a.cod_inst and b.cod_dep=a.cod_dep and b.ano=a.ano and b.cod_sector=a.cod_sector and b.cod_programa=a.cod_programa and b.cod_sub_prog=a.cod_sub_prog and b.cod_proyecto=a.cod_proyecto and b.cod_activ_obra=a.cod_activ_obra) as deno_activ_obra,
								( SELECT x.denominacion FROM cfpd01_ano_2_partida x WHERE x.ejercicio = a.ano AND x.cod_grupo = substr(a.cod_partida::text, 0, 2)::integer AND x.cod_partida = substr(a.cod_partida::text, 2)::integer) AS deno_partida,
								( SELECT x.denominacion FROM cfpd01_ano_3_generica x WHERE x.ejercicio = a.ano AND x.cod_grupo = substr(a.cod_partida::text, 0, 2)::integer AND x.cod_partida = substr(a.cod_partida::text, 2)::integer and x.cod_generica=a.cod_generica) AS deno_generica,
								( SELECT x.denominacion FROM cfpd01_ano_4_especifica x WHERE x.ejercicio = a.ano AND x.cod_grupo = substr(a.cod_partida::text, 0, 2)::integer AND x.cod_partida = substr(a.cod_partida::text, 2)::integer and x.cod_generica=a.cod_generica and x.cod_especifica=a.cod_especifica) AS deno_especifica,
								( SELECT x.denominacion FROM cfpd01_ano_5_sub_espec x WHERE x.ejercicio = a.ano AND x.cod_grupo = substr(a.cod_partida::text, 0, 2)::integer AND x.cod_partida = substr(a.cod_partida::text, 2)::integer and x.cod_generica=a.cod_generica and x.cod_especifica=a.cod_especifica and x.cod_sub_espec=a.cod_sub_espec) AS deno_sub_espec,
								sum(a.aporte_municipio) as aporte_municipio,
								sum(a.aporte_organismo) as aporte_organismo,
								sum(a.aporte_gobernacion) as aporte_gobernacion
								  FROM cfpd17_inversion_coordinada a WHERE ".$filtro."
								GROUP BY
								a.cod_presi,
								a.cod_entidad,
								a.cod_tipo_inst,
								a.cod_inst,
								a.cod_dep,
								a.cod_estado,
								a.cod_organismo,
								a.cod_municipio,
								a.ano,
								a.cod_sector,
								a.cod_programa,
								a.cod_sub_prog,
								a.cod_proyecto,
								a.cod_activ_obra,
								a.cod_partida,
								a.cod_generica,
								a.cod_especifica,
								a.cod_sub_espec
								ORDER BY
								a.cod_presi,
								a.cod_entidad,
								a.cod_tipo_inst,
								a.cod_inst,
								a.cod_dep,
								a.cod_estado,
								a.cod_organismo,
								a.cod_municipio,
								a.ano,
								a.cod_sector,
								a.cod_programa,
								a.cod_sub_prog,
								a.cod_proyecto,
								a.cod_activ_obra,
								a.cod_partida,
								a.cod_generica,
								a.cod_especifica,
								a.cod_sub_espec";

	/*				$consulta2="SELECT * FROM cfpd17_inversion_coordinada  WHERE ".$filtro." ORDER BY cod_presi,
					cod_entidad,
					cod_tipo_inst,
					cod_inst,
					cod_dep,
					cod_estado,
					cod_organismo,
					cod_municipio,
					ano";

		$consulta2="SELECT * FROM v_cfpd17_inversion_coordinada1  WHERE ".$filtro." ORDER BY cod_presi,
					cod_entidad,
					cod_tipo_inst,
					cod_inst,
					cod_dep,
					cod_estado,
					cod_organismo,
					cod_municipio,
					ano";
*/
		$datos=$this->cfpd02_sector->execute($consulta1);
		$datos1=$this->cfpd02_sector->execute($consulta2);

		if($datos!=null){
				$this->set('datos',$datos);
				$this->set('datos1',$datos1);
		}else{
			$this->set('datos',null);
		}

	}else{
		$this->set('datos',null);

	}


 }//fin function


$this->set("opcion", $opcion);


}//fin forma_2128







}//fin class

?>