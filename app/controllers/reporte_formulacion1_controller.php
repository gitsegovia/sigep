<?php

class reporteFormulacion1Controller extends AppController{


    var $name    = "reporte_formulacion1";
    var $uses    = array("cfpd01_formulacion", "cfpd03", "cfpd01_ano_partida", "cfpd01_ano_generica", "cfpd01_ano_especifica",
                         "cfpd01_ano_sub_espec", "cfpd01_ano_auxiliar", "cfpd02_sector", "cfpd02_programa", "cfpd02_sub_prog",
                         "cfpd02_proyecto", "cfpd02_activ_obra", "v_balance_ejecucion", "cfpd05", "cfpd09", "cfpd09_metas_sector", "cfpd05",
                         "cfpd05_2032_tmp", "cfpd09_metas_programa", "v_metas_agrupadas", "cfpd06", "v_nivel_subpartida_sector","arrd05",
                         "v_nivel_subpartida_programa", "v_nivel_subpartida_subprograma", "cfpd16","cfpd02_sector", "v_programas_metas", "v_cfpd09", "v_ingresos_gastos_dep_vision", "v_ingresos_gastos_inst_vision");


    var $helpers = array('Html', 'Javascript', 'Ajax', 'Fck', 'Sisap');


function checkSession(){
				if (!$this->Session->check('Usuario')){
						$this->redirect('/salir/');
						exit();
				}else{
					$this->requestAction('/usuarios/actualizar_user');
				}
}//fin checksession



function beforeFilter(){$this->checkSession();}


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
}

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


function forma_2101($year=null){

	$sql='';

	if(!$year){

		    $this->layout = "ajax";
		    $this->set('ir', 'no');

			$cod_presi = $this->Session->read('SScodpresi');
			$cod_entidad = $this->Session->read('SScodentidad');
			$cod_tipo_inst = $this->Session->read('SScodtipoinst');
			$cod_inst = $this->Session->read('SScodinst');
			$cod_dep = $this->Session->read('SScoddep');
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
  	$ano = '';

  	$ano = $this->data['datos']['ano'];
  	$consolidado = 2;
  	 if(isset($this->data['datos']['consolidacion'])){
  	         	$consolidado = $this->data['datos']['consolidacion'];
  	 }



	$cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
    $ejercicio = $ano;

          if($consolidado==2){
  	     	$sql = $this->SQLCA_consolidado($consolidado);
  	}else if($consolidado==1){
  		    $sql = $this->SQLCA_consolidado($consolidado);
  	}

	$this->set('partida',$this->cfpd01_ano_partida->findAll('ejercicio='.$ejercicio,null,null,null, null, null));
	$this->set('generica',$this->cfpd01_ano_generica->findAll('ejercicio='.$ejercicio,null,null,null, null, null));
	$this->set('especifica',$this->cfpd01_ano_especifica->findAll('ejercicio='.$ejercicio,null,null,null, null, null));
	$this->set('subespecifica',$this->cfpd01_ano_sub_espec->findAll('ejercicio='.$ejercicio,null,null,null, null, null));
    $this->set('auxiliar',$this->cfpd01_ano_auxiliar->findAll('ejercicio='.$ejercicio,null,null,null, null, null));

	if($ano!=''){
	$condicion_sql = $sql." and ano=".$ano;
	}else{
    $condicion_sql = $sql;
	}

$campos = "   cod_presi,
			  cod_entidad,
			  cod_tipo_inst,
			  cod_inst,
			  ano,
			  cod_partida,
			  cod_generica,
			  cod_especifica,
			  cod_sub_espec,
			  cod_auxiliar,
			  SUM(estimacion_inicial)   as estimacion_inicial,
			  SUM(ingresos_adicionales) as ingresos_adicionales,
			  SUM(rebajas)              as rebajas,
			  SUM(monto_facturado)      as monto_facturado,
			  SUM(monto_cobrado)        as monto_cobrado ";

$group  = "  GROUP BY cod_presi,
					  cod_entidad,
					  cod_tipo_inst,
					  cod_inst,
					  ano,
					  cod_partida,
					  cod_generica,
					  cod_especifica,
					  cod_sub_espec,
					  cod_auxiliar ";



if($consolidado==2){
	$DATOS_res = $this->cfpd03->findAll($condicion_sql, null, 'cod_partida, cod_generica, cod_especifica, cod_sub_espec, cod_auxiliar ASC', null, null);
}else{
    $DATOS_res = $this->cfpd03->findAll($condicion_sql." ".$group, $campos, 'cod_partida, cod_generica, cod_especifica, cod_sub_espec, cod_auxiliar ASC', null, null);
}






    $this->set('datos', $DATOS_res);



  }
$this->set('entidad_federal', $this->Session->read('entidad_federal'));

}//fin function






function reporte_relacion_ingresos($year=null){

	$sql='';

	if(!$year){

		    $this->layout = "ajax";
		    $this->set('ir', 'no');

			$cod_presi = $this->Session->read('SScodpresi');
			$cod_entidad = $this->Session->read('SScodentidad');
			$cod_tipo_inst = $this->Session->read('SScodtipoinst');
			$cod_inst = $this->Session->read('SScodinst');
			$cod_dep = $this->Session->read('SScoddep');
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


	}else{



  	$this->layout = "pdf";
  	$ano = '';

  	$ano = $this->data['datos']['ano'];
  	$consolidado = 2;
  	 if(isset($this->data['datos']['consolidacion'])){
  	         	$consolidado = $this->data['datos']['consolidacion'];
  	 }



	$cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
    $ejercicio = $ano;

    if($consolidado==2){
  	     	$sql = $this->SQLCA_consolidado($consolidado);
  	     	$modelo_relacion = 'v_ingresos_gastos_dep_vision';
  	}else if($consolidado==1){
  		    $sql = $this->SQLCA_consolidado($consolidado);
  		    $modelo_relacion = 'v_ingresos_gastos_inst_vision';
  	}

	if($ano!=''){
		$condicion_sql = $sql." and ano=".$ano;
	}else{
    	$condicion_sql = $sql;
	}


if($consolidado==2){
	$DATOS_res = $this->$modelo_relacion->findAll($condicion_sql, null, 'cod_ramo, cod_subramo, cod_esp, cod_subesp, cod_aux ASC', null, null);
}else{
	$DATOS_res = $this->$modelo_relacion->findAll($condicion_sql, null, 'cod_ramo, cod_subramo, cod_esp, cod_subesp, cod_aux ASC', null, null);
    // $DATOS_res = $this->v_ingresos_gastos_inst_vision->findAll($condicion_sql." ".$group, $campos, 'cod_partida, cod_generica, cod_especifica, cod_sub_espec, cod_auxiliar ASC', null, null);
}

	$this->set('ano_fiscal', $ano);
	$this->set('modelo_relacion', $modelo_relacion);

	if(!empty($DATOS_res)){
    	$this->set('datos', $DATOS_res);
	}else{
		$this->set('datos', array());
	}

  }

	$this->set('entidad_federal', $this->Session->read('entidad_federal'));

} // fin function reporte_relacion_ingresos







function forma_2102($year=null){

	$sql='';

	if(!$year){

		    $this->layout = "ajax";
		    $this->set('ir', 'no');

			$cod_presi = $this->Session->read('SScodpresi');
			$cod_entidad = $this->Session->read('SScodentidad');
			$cod_tipo_inst = $this->Session->read('SScodtipoinst');
			$cod_inst = $this->Session->read('SScodinst');
			$cod_dep = $this->Session->read('SScoddep');
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
				  			$ano = '';

						  	$ano = $this->data['datos']['ano'];
						  	$consolidado = 2;
						  	 if(isset($this->data['datos']['consolidacion'])){
						  	         	$consolidado = $this->data['datos']['consolidacion'];
						  	 }



							$cod_presi = $this->Session->read('SScodpresi');
							$cod_entidad = $this->Session->read('SScodentidad');
							$cod_tipo_inst = $this->Session->read('SScodtipoinst');
							$cod_inst = $this->Session->read('SScodinst');
							$cod_dep = $this->Session->read('SScoddep');
						    $ejercicio = $ano;

						          if($consolidado==2){
						  	     	$sql = $this->SQLCA_consolidado($consolidado);
						  	     	$group = " GROUP BY cod_presi,cod_entidad,cod_tipo_inst,cod_inst,cod_dep";
						  	     	$campos1= " cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep,denominacion, unidad_ejecutora, objetivo, funcionario_responsable";
						  	     	$campos2= " cod_presi, cod_entidad, cod_tipo_inst, cod_inst, denominacion, unidad_ejecutora, objetivo, titulo, funcionario_responsable";
						  	}else if($consolidado==1){
						  		    $sql = $this->SQLCA_consolidado($consolidado);
						  		    $group = " GROUP BY cod_presi,cod_entidad,cod_tipo_inst,cod_inst";
						  		    $campos1= " cod_presi, cod_entidad, cod_tipo_inst, cod_inst, denominacion, unidad_ejecutora, objetivo, funcionario_responsable";
						  		    $campos2= " cod_presi, cod_entidad, cod_tipo_inst, cod_inst, denominacion, unidad_ejecutora, objetivo, titulo, funcionario_responsable";
						  	}

						  	if(isset($ano) && !empty($ano)){
						          $condicion= $sql.'and ano='.$ano;
						          $group .=",ano";
						          $campos1 .=",ano";
						          $campos2 .=",ano";
							}else{
								$condicion=$sql;
								$group .="";
								$campos1 .="";
								$campos2 .="";
							}

						  	$sector=$this->cfpd02_sector->findAll($condicion." GROUP BY ".$campos1.",cod_sector",$campos1.",cod_sector",'cod_sector ASC');
							$c_sector=$this->cfpd02_sector->findCount($condicion);
							$programa=$this->cfpd02_programa->findAll($condicion." GROUP BY ".$campos1.",cod_sector,cod_programa",$campos1.",cod_sector,cod_programa",'cod_sector,cod_programa ASC');
							$c_programa=$this->cfpd02_programa->findCount($condicion);
							$subprograma=$this->cfpd02_sub_prog->findAll($condicion." GROUP BY ".$campos1.",cod_sector,cod_programa,cod_sub_prog",$campos1.",cod_sector,cod_programa,cod_sub_prog",'cod_sector,cod_programa,cod_sub_prog ASC');
							$c_subprograma=$this->cfpd02_sub_prog->findCount($condicion);//200.84.68.85
						    $proyecto=$this->cfpd02_proyecto->findAll($condicion." GROUP BY ".$campos1.",cod_sector,cod_programa,cod_sub_prog,cod_proyecto",$campos1.",cod_sector,cod_programa,cod_sub_prog,cod_proyecto",'cod_sector,cod_programa,cod_sub_prog,cod_proyecto ASC');
							$c_proyecto=$this->cfpd02_proyecto->findCount($condicion);
						    $actividad=$this->cfpd02_activ_obra->findAll($condicion." GROUP BY ".$campos2.",cod_sector,cod_programa,cod_sub_prog,cod_proyecto,cod_activ_obra",$campos2.",cod_sector,cod_programa,cod_sub_prog,cod_proyecto,cod_activ_obra",'cod_sector,cod_programa,cod_sub_prog,cod_proyecto,cod_activ_obra ASC');
							$c_actividad=$this->cfpd02_activ_obra->findCount($condicion);

						    $sector_aux = $sector;

						    $this->set('sector_aux',$sector_aux);
						    $this->set('sector',$sector);
						    $this->set('c_sector',$c_sector);
						    $this->set('programa',$programa);
						    $this->set('c_programa',$c_programa);
						    $this->set('subprograma',$subprograma);
						    $this->set('c_subprograma',$c_subprograma);
						    $this->set('proyecto',$proyecto);
						    $this->set('c_proyecto',$c_proyecto);
						    $this->set('actividad',$actividad);
						    $this->set('c_actividad',$c_actividad);


			}//fin else


$this->set('entidad_federal', $this->Session->read('entidad_federal'));
}//fin function












function forma_2103($opcion=null, $opcion2=null){

	$sql='';

	if($opcion==null){

		    $this->layout = "ajax";
		    $this->set('ir', 'no');
		    $opcion = 1;

			$cod_presi = $this->Session->read('SScodpresi');
			$cod_entidad = $this->Session->read('SScodentidad');
			$cod_tipo_inst = $this->Session->read('SScodtipoinst');
			$cod_inst = $this->Session->read('SScodinst');
			$cod_dep = $this->Session->read('SScoddep');
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

    }else if($opcion==2){ $this->layout = "ajax";

                    $rs=$this->v_balance_ejecucion->execute("SELECT DISTINCT a.cod_sector, a.denominacion FROM cfpd02_sector a  WHERE ".$this->condicion());
				    foreach($rs as $l){
				    	if(!isset($seleccion)){$seleccion = mascara2($l[0]["cod_sector"]);}
						$v[]=mascara2($l[0]["cod_sector"]);
						$d[]=mascara2($l[0]["cod_sector"])." - ".$l[0]["denominacion"];
					}
					$sector = array_combine($v, $d);
			        $this->set('lista_numero', $sector);


	}else if($opcion==3){


		                    $this->layout = "pdf";
				  			$ano = '';

						  	$ano = $this->data['datos']['ano'];
						  	$consolidado = 2;
						  	 if(isset($this->data['datos']['consolidacion'])){
						  	         	$consolidado = $this->data['datos']['consolidacion'];
						  	 }





							$cod_presi = $this->Session->read('SScodpresi');
							$cod_entidad = $this->Session->read('SScodentidad');
							$cod_tipo_inst = $this->Session->read('SScodtipoinst');
							$cod_inst = $this->Session->read('SScodinst');
							$cod_dep = $this->Session->read('SScoddep');
						    $ejercicio = $ano;

						          if($consolidado==2){
						  	     	$sql = $this->SQLCA_consolidado($consolidado);
						  	}else if($consolidado==1){
						  		    $sql = $this->SQLCA_consolidado($consolidado);
						  	}

						  	if(isset($ano) && !empty($ano)){
						          $condicion= $sql.'and ano='.$ano;
							}else{
								  $condicion=$sql;
							}

							if(isset($this->data['datos']['cod_sector'])){
						  	         	$condicion .= " and cod_sector='".$this->data['datos']['cod_sector']."'";
						  	 }


							$this->set('sector',$this->cfpd02_sector->findAll($condicion,null,'cod_sector ASC',null,null,null));
						    $this->set('programa',$this->cfpd02_programa->findAll($condicion,null,'cod_sector, cod_programa ASC',null,null,null));
						    $this->set('subprog',$this->cfpd02_sub_prog->findAll($condicion,null,'cod_sector, cod_programa, cod_sub_prog ASC',null,null,null));
							$DATOS_res = $this->cfpd05->findAll($condicion, null, 'cod_sector, cod_programa, cod_sub_prog  ASC', null, null);
						    $this->set('datos', $DATOS_res);




	}//fin else

$this->set('opcion', $opcion);
$this->set('opcion2',$opcion2);
$this->set('entidad_federal', $this->Session->read('entidad_federal'));
}//fin function

function forma_2103_a($opcion=null, $opcion2=null){

	$sql='';

	if($opcion==null){

		    $this->layout = "ajax";
		    $this->set('ir', 'no');
		    $opcion = 1;

			$cod_presi = $this->Session->read('SScodpresi');
			$cod_entidad = $this->Session->read('SScodentidad');
			$cod_tipo_inst = $this->Session->read('SScodtipoinst');
			$cod_inst = $this->Session->read('SScodinst');
			$cod_dep = $this->Session->read('SScoddep');
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

    }else if($opcion==2){ $this->layout = "ajax";

                    $rs=$this->v_balance_ejecucion->execute("SELECT DISTINCT a.cod_sector, a.denominacion FROM cfpd02_sector a  WHERE ".$this->condicion());
				    foreach($rs as $l){
				    	if(!isset($seleccion)){$seleccion = mascara2($l[0]["cod_sector"]);}
						$v[]=mascara2($l[0]["cod_sector"]);
						$d[]=mascara2($l[0]["cod_sector"])." - ".$l[0]["denominacion"];
					}
					$sector = array_combine($v, $d);
			        $this->set('lista_numero', $sector);


	}else if($opcion==3){


		                    $this->layout = "pdf";
				  			$ano = '';

						  	$ano = $this->data['datos']['ano'];
						  	$consolidado = 2;
						  	 if(isset($this->data['datos']['consolidacion'])){
						  	         	$consolidado = $this->data['datos']['consolidacion'];
						  	 }





							$cod_presi = $this->Session->read('SScodpresi');
							$cod_entidad = $this->Session->read('SScodentidad');
							$cod_tipo_inst = $this->Session->read('SScodtipoinst');
							$cod_inst = $this->Session->read('SScodinst');
							$cod_dep = $this->Session->read('SScoddep');
						    $ejercicio = $ano;

						          if($consolidado==2){
						  	     	$sql = $this->SQLCA_consolidado($consolidado);
						  	}else if($consolidado==1){
						  		    $sql = $this->SQLCA_consolidado($consolidado);
						  	}

						  	if(isset($ano) && !empty($ano)){
						          $condicion= $sql.'and ano='.$ano;
							}else{
								  $condicion=$sql;
							}

							if(isset($this->data['datos']['cod_sector'])){
						  	         	$condicion .= " and cod_sector='".$this->data['datos']['cod_sector']."'";
						  	 }


							$this->set('sector',$this->cfpd02_sector->findAll($condicion,null,'cod_sector ASC',null,null,null));
						    $this->set('programa',$this->cfpd02_programa->findAll($condicion,null,'cod_sector, cod_programa ASC',null,null,null));
						    $this->set('subprog',$this->cfpd02_sub_prog->findAll($condicion,null,'cod_sector, cod_programa, cod_sub_prog ASC',null,null,null));
							$DATOS_res = $this->cfpd05->findAll($condicion, null, 'cod_sector, cod_programa, cod_sub_prog  ASC', null, null);
						    $this->set('datos', $DATOS_res);




	}//fin else

$this->set('opcion', $opcion);
$this->set('opcion2',$opcion2);
$this->set('entidad_federal', $this->Session->read('entidad_federal'));
}//fin function


function forma_2104($year=null){

	$sql='';

	if(!$year){

		    $this->layout = "ajax";
		    $this->set('ir', 'no');

			$cod_presi = $this->Session->read('SScodpresi');
			$cod_entidad = $this->Session->read('SScodentidad');
			$cod_tipo_inst = $this->Session->read('SScodtipoinst');
			$cod_inst = $this->Session->read('SScodinst');
			$cod_dep = $this->Session->read('SScoddep');
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
				  			$ano = '';

						  	$ano = $this->data['datos']['ano'];
						  	$consolidado = 2;
						  	 if(isset($this->data['datos']['consolidacion'])){
						  	         	$consolidado = $this->data['datos']['consolidacion'];
						  	 }

							$cod_presi = $this->Session->read('SScodpresi');
							$cod_entidad = $this->Session->read('SScodentidad');
							$cod_tipo_inst = $this->Session->read('SScodtipoinst');
							$cod_inst = $this->Session->read('SScodinst');
							$cod_dep = $this->Session->read('SScoddep');
						    $ejercicio = $ano;

						          if($consolidado==2){
						  	     	$sql = $this->SQLCA_consolidado($consolidado);
						  	}else if($consolidado==1){
						  		    $sql = $this->SQLCA_consolidado($consolidado);
						  	}

						  	if(isset($ano) && !empty($ano)){
						          $condicion= $sql.'and ano='.$ano;
							}else{
								  $condicion=$sql;
							}


                            $this->set('partida',$this->cfpd01_ano_partida->findAll('ejercicio='.$ejercicio,null, 'cod_grupo, cod_partida ASC',null, null, null));
						    $DATOS_res = $this->cfpd05->findAll($condicion, null, 'cod_partida ASC', null, null);
						    $this->set('datos', $DATOS_res);

    }//fin function



$this->set('entidad_federal', $this->Session->read('entidad_federal'));

}//fin function


function forma_2105($year=null){

	$sql='';

	if(!$year){

		    $this->layout = "ajax";
		    $this->set('ir', 'no');

			$cod_presi = $this->Session->read('SScodpresi');
			$cod_entidad = $this->Session->read('SScodentidad');
			$cod_tipo_inst = $this->Session->read('SScodtipoinst');
			$cod_inst = $this->Session->read('SScodinst');
			$cod_dep = $this->Session->read('SScoddep');
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
  	$ano = '';

  	$ano = $this->data['datos']['ano'];
  	$consolidado = 2;
  	 if(isset($this->data['datos']['consolidacion'])){
  	         	$consolidado = $this->data['datos']['consolidacion'];
  	 }



	$cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
    $ejercicio = $ano;

          if($consolidado==2){
  	     	$sql   = $this->SQLCA_consolidado($consolidado);

  	}else if($consolidado==1){
  		    $sql = $this->SQLCA_consolidado($consolidado);
  	}

$sql_2 = $this->condicion()." and ano='".$ano."' ";


	if($ano!=''){
	$condicion_sql = $sql." and ano=".$ano;
	}else{
    $condicion_sql = $sql;
	}

	$_SESSION['ejercicio'] = $ano;


if($consolidado==2){
$sql_aux="SELECT * FROM v_nivel_partida_sector WHERE ".$condicion_sql." ORDER BY  cod_presi,
																				  cod_entidad,
																				  cod_tipo_inst,
																				  cod_inst,
																				  cod_dep,
																				  ano,
																				  cod_partida; ";
}else{
$sql_aux="     SELECT
                      cod_presi,
					  cod_entidad,
					  cod_tipo_inst,
					  cod_inst,
					  ano,
					  cod_partida,
					  denominacion_partida,
					  SUM(sector_1) as sector_1,
					  SUM(sector_2) as sector_2,
					  SUM(sector_3) as sector_3,
					  SUM(sector_4) as sector_4,
					  SUM(sector_5) as sector_5,
					  SUM(sector_6) as sector_6,
					  SUM(sector_7) as sector_7,
					  SUM(sector_8) as sector_8,
					  SUM(sector_9) as sector_9,
					  SUM(sector_10) as sector_10,
					  SUM(sector_11) as sector_11,
					  SUM(sector_12) as sector_12,
					  SUM(sector_13) as sector_13,
					  SUM(sector_14) as sector_14,
					  SUM(sector_15) as sector_15,
					  SUM(total)     as total

			     FROM  v_nivel_partida_sector

			     WHERE ".$condicion_sql."

			     GROUP BY cod_presi,
						  cod_entidad,
						  cod_tipo_inst,
						  cod_inst,
						  ano,
						  cod_partida,
						  denominacion_partida

                 ORDER BY cod_presi,
						  cod_entidad,
						  cod_tipo_inst,
						  cod_inst,
						  ano,
						  cod_partida; ";
}//fin if




			    $sector=$this->cfpd02_sector->findAll($sql_2,null,'cod_sector ASC');
			    $DATOS_res = $this->cfpd05->execute($sql_aux);
			    $this->set('datos', $DATOS_res);
			    $this->set('sector', $sector);



    }//fin function



$this->set('entidad_federal', $this->Session->read('entidad_federal'));

}//fin function


function forma_2108($year=null){

	$sql='';

	if(!$year){

		    $this->layout = "ajax";
		    $this->set('ir', 'no');

			$cod_presi = $this->Session->read('SScodpresi');
			$cod_entidad = $this->Session->read('SScodentidad');
			$cod_tipo_inst = $this->Session->read('SScodtipoinst');
			$cod_inst = $this->Session->read('SScodinst');
			$cod_dep = $this->Session->read('SScoddep');
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

	}else{   $this->layout = "pdf";

		                    $ano = $this->data['datos']['ano'];
						  	$consolidado = 2;
						  	 if(isset($this->data['datos']['consolidacion'])){
						  	         	$consolidado = $this->data['datos']['consolidacion'];
						  	 }

							$cod_presi = $this->Session->read('SScodpresi');
							$cod_entidad = $this->Session->read('SScodentidad');
							$cod_tipo_inst = $this->Session->read('SScodtipoinst');
							$cod_inst = $this->Session->read('SScodinst');
							$cod_dep = $this->Session->read('SScoddep');
						    $ejercicio = $ano;

						          if($consolidado==2){
						  	     	$sql = $this->SQLCA_consolidado($consolidado);
						  	}else if($consolidado==1){
						  		    $sql = $this->SQLCA_consolidado($consolidado);
						  	}

						  	if(isset($ano) && !empty($ano)){
						          $condicion= $sql.'and ano='.$ano;
							}else{
								  $condicion=$sql;
							}

            $DATOS_res = $this->v_cfpd09->findAll($condicion,null, 'cod_sector ASC', null, null,null);
//print_r($DATOS_res);
            $this->set('datos', $DATOS_res);

  }
$this->set('entidad_federal', $this->Session->read('entidad_federal'));

}//fin function


function forma_2109($year=null){

	$sql='';

	if(!$year){

		    $this->layout = "ajax";
		    $this->set('ir', 'no');

			$cod_presi = $this->Session->read('SScodpresi');
			$cod_entidad = $this->Session->read('SScodentidad');
			$cod_tipo_inst = $this->Session->read('SScodtipoinst');
			$cod_inst = $this->Session->read('SScodinst');
			$cod_dep = $this->Session->read('SScoddep');
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
				  	$ano = '';

				  	$ano = $this->data['datos']['ano'];
				  	$consolidado = 2;
				  	 if(isset($this->data['datos']['consolidacion'])){
				  	         	$consolidado = $this->data['datos']['consolidacion'];
				  	 }



					$cod_presi = $this->Session->read('SScodpresi');
					$cod_entidad = $this->Session->read('SScodentidad');
					$cod_tipo_inst = $this->Session->read('SScodtipoinst');
					$cod_inst = $this->Session->read('SScodinst');
					$cod_dep = $this->Session->read('SScoddep');
				    $ejercicio = $ano;

				          if($consolidado==2){
				  	     	$sql   = " where ".$this->SQLCA_consolidado_opcion($consolidado, "L");

				  	}else if($consolidado==1){
				  		    $sql = " where ".$this->SQLCA_consolidado_opcion($consolidado, "L");
				  	}



					if($ano!=''){
					$condicion = $sql." and L.ano=".$ano;
					}else{
				    $condicion = $sql;
					}

/**
    $xx=$this->cfpd05->execute("   	    SELECT L.cod_presi,
										       L.cod_entidad,
										       L.cod_tipo_inst,
										       L.cod_inst,
										       L.ano,
										       L.cod_partida,
										       0 as cod_generica,
										       0 as cod_especifica,
										       0 as cod_sub_espec,
										       (select SUM(asignacion_anual) from cfpd05 where cod_presi=L.cod_presi and cod_entidad=L.cod_entidad and cod_tipo_inst=L.cod_tipo_inst and cod_inst=L.cod_inst and ano=L.ano and cod_partida=L.cod_partida ) as asignacion_presupuestaria,
										       (select denominacion from cfpd01_ano_2_partida where cfpd01_ano_2_partida.ejercicio=L.ano and cfpd01_ano_2_partida.cod_grupo=4 and cfpd01_ano_2_partida.cod_partida=substr(CAST (L.cod_partida AS text),2,2)::int limit 1) as denominacion
										      FROM cfpd05 L, cfpd01_ano_2_partida ".$condicion." and cod_tipo_gasto=2 group by L.cod_presi,L.cod_entidad,L.cod_tipo_inst,L.cod_inst,L.ano,L.cod_partida


										union



										SELECT L.cod_presi,
										       L.cod_entidad,
										       L.cod_tipo_inst,
										       L.cod_inst,
										       L.ano,
										       L.cod_partida,
										       L.cod_generica,
										       0 as cod_especifica,
										       0 as cod_sub_espec,
										       (select SUM(asignacion_anual) from cfpd05 where cod_presi=L.cod_presi and cod_entidad=L.cod_entidad and cod_tipo_inst=L.cod_tipo_inst and cod_inst=L.cod_inst and ano=L.ano and cod_partida=L.cod_partida and cod_generica=L.cod_generica ) as asignacion_presupuestaria,
										       (select denominacion from cfpd01_ano_3_generica where cfpd01_ano_3_generica.ejercicio=L.ano and cfpd01_ano_3_generica.cod_grupo=4 and cfpd01_ano_3_generica.cod_partida=substr(CAST (L.cod_partida AS text),2,2)::int and cfpd01_ano_3_generica.cod_generica=L.cod_generica limit 1) as denominacion
										  	  FROM cfpd05 L, cfpd01_ano_3_generica  ".$condicion." and cod_tipo_gasto=2  group by L.cod_presi,L.cod_entidad,L.cod_tipo_inst,L.cod_inst,L.ano,L.cod_partida,L.cod_generica


										union


										SELECT L.cod_presi,
										       L.cod_entidad,
										       L.cod_tipo_inst,
										       L.cod_inst,
										       L.ano,
										       L.cod_partida,
										       L.cod_generica,
										       L.cod_especifica,
										       0 as cod_sub_espec,
										       (select SUM(asignacion_anual) from cfpd05 where cod_presi=L.cod_presi and cod_entidad=L.cod_entidad and cod_tipo_inst=L.cod_tipo_inst and cod_inst=L.cod_inst and ano=L.ano and cod_partida=L.cod_partida and cod_generica=L.cod_generica and cod_especifica=L.cod_especifica  ) as asignacion_presupuestaria,
										       (select denominacion from cfpd01_ano_4_especifica where cfpd01_ano_4_especifica.ejercicio=L.ano and cfpd01_ano_4_especifica.cod_grupo=4 and cfpd01_ano_4_especifica.cod_partida=substr(CAST (L.cod_partida AS text),2,2)::int and cfpd01_ano_4_especifica.cod_generica=L.cod_generica and cfpd01_ano_4_especifica.cod_especifica=L.cod_especifica limit 1) as denominacion
										        FROM cfpd05 L, cfpd01_ano_4_especifica  ".$condicion." and cod_tipo_gasto=2  group by L.cod_presi,L.cod_entidad,L.cod_tipo_inst,L.cod_inst,L.ano,L.cod_partida,L.cod_generica,L.cod_especifica


										union


										SELECT L.cod_presi,
										       L.cod_entidad,
										       L.cod_tipo_inst,
										       L.cod_inst,
										       L.ano,
										       L.cod_partida,
										       L.cod_generica,
										       L.cod_especifica,
										       L.cod_sub_espec,
										       (select SUM(asignacion_anual) from cfpd05 where cod_presi=L.cod_presi and cod_entidad=L.cod_entidad and cod_tipo_inst=L.cod_tipo_inst and cod_inst=L.cod_inst and ano=L.ano  and cod_partida=L.cod_partida and cod_generica=L.cod_generica and cod_especifica=L.cod_especifica and cod_sub_espec=L.cod_sub_espec ) as asignacion_presupuestaria,
										       (select denominacion from cfpd01_ano_5_sub_espec where cfpd01_ano_5_sub_espec.ejercicio=L.ano and cfpd01_ano_5_sub_espec.cod_grupo=4 and cfpd01_ano_5_sub_espec.cod_partida=substr(CAST (L.cod_partida AS text),2,2)::int and cfpd01_ano_5_sub_espec.cod_generica=L.cod_generica and cfpd01_ano_5_sub_espec.cod_especifica=L.cod_especifica and cfpd01_ano_5_sub_espec.cod_sub_espec=L.cod_sub_espec limit 1) as denominacion
										         FROM cfpd05 L, cfpd01_ano_5_sub_espec  ".$condicion." and cod_tipo_gasto=2 group by L.cod_presi,L.cod_entidad,L.cod_tipo_inst,L.cod_inst,L.ano,L.cod_partida,L.cod_generica,L.cod_especifica,L.cod_sub_espec



										");

										*/

										     $xx=$this->cfpd05->execute("SELECT * FROM reporte_forma_2109 L  ".$condicion." ORDER BY L.cod_partida,L.cod_generica,L.cod_especifica,L.cod_sub_espec ASC");

										     $this->set('datos', $xx);
										     $this->set('ejercicio', $ano);





    }//fin function



$this->set('entidad_federal', $this->Session->read('entidad_federal'));

}//fin function


function forma_2112_a($year=null){

	$sql='';

	if(!$year){

		    $this->layout = "ajax";
		    $this->set('ir', 'no');

			$cod_presi = $this->Session->read('SScodpresi');
			$cod_entidad = $this->Session->read('SScodentidad');
			$cod_tipo_inst = $this->Session->read('SScodtipoinst');
			$cod_inst = $this->Session->read('SScodinst');
			$cod_dep = $this->Session->read('SScoddep');
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

		                    $ano = $this->data['datos']['ano'];
						  	$consolidado = 2;
						  	 if(isset($this->data['datos']['consolidacion'])){
						  	         	$consolidado = $this->data['datos']['consolidacion'];
						  	 }

							$cod_presi = $this->Session->read('SScodpresi');
							$cod_entidad = $this->Session->read('SScodentidad');
							$cod_tipo_inst = $this->Session->read('SScodtipoinst');
							$cod_inst = $this->Session->read('SScodinst');
							$cod_dep = $this->Session->read('SScoddep');
						    $ejercicio = $ano;

						          if($consolidado==2){
						  	     	$sql = $this->SQLCA_consolidado($consolidado);
						  	}else if($consolidado==1){
						  		    $sql = $this->SQLCA_consolidado($consolidado);
						  	}

						  	if(isset($ano) && !empty($ano)){
						          $condicion= $sql.'and ano='.$ano;
							}else{
								  $condicion=$sql;
							}

							$condicion .= " and (tipo_agrupamiento=1 or tipo_agrupamiento=2)";

							$campos = ' cod_presi ,
										cod_entidad,
										cod_tipo_inst,
										cod_inst,
										ano,
										cod_sector,
										cod_programa,
										denominacion,
										metas,
										unidad_medida,
										SUM(cantidad_estimada) as cantidad_estimada,
										SUM(costo_financiero) as "costo_financiero" ';

							$agrupar = 'GROUP BY    cod_presi,
													cod_entidad,
													cod_tipo_inst,
													cod_inst,
													ano ,
													cod_sector ,
													cod_programa,
													denominacion,
													metas,
										            unidad_medida';



                               if($consolidado==1){
							       $DATOS_res = $this->v_programas_metas->findAll($condicion.$agrupar, $campos, ' cod_sector, cod_programa ASC',  null, null);
                               }else{
                               	   $DATOS_res = $this->v_programas_metas->findAll($condicion, null, ' cod_sector, cod_programa ASC',  null, null);
                               }

							    $this->set('datos', $DATOS_res);
							    $this->set('year',  $ano);

    }//fin function



$this->set('entidad_federal', $this->Session->read('entidad_federal'));

}//fin function


function forma_2112_b($year=null){

	$sql='';

	if(!$year){

		    $this->layout = "ajax";
		    $this->set('ir', 'no');

			$cod_presi = $this->Session->read('SScodpresi');
			$cod_entidad = $this->Session->read('SScodentidad');
			$cod_tipo_inst = $this->Session->read('SScodtipoinst');
			$cod_inst = $this->Session->read('SScodinst');
			$cod_dep = $this->Session->read('SScoddep');
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

		                    $ano = $this->data['datos']['ano'];
						  	$consolidado = 2;
						  	 if(isset($this->data['datos']['consolidacion'])){
						  	         	$consolidado = $this->data['datos']['consolidacion'];
						  	 }

							$cod_presi = $this->Session->read('SScodpresi');
							$cod_entidad = $this->Session->read('SScodentidad');
							$cod_tipo_inst = $this->Session->read('SScodtipoinst');
							$cod_inst = $this->Session->read('SScodinst');
							$cod_dep = $this->Session->read('SScoddep');
						    $ejercicio = $ano;

						          if($consolidado==2){
						  	     	$sql = $this->SQLCA_consolidado($consolidado);
						  	}else if($consolidado==1){
						  		    $sql = $this->SQLCA_consolidado($consolidado);
						  	}

						  	if(isset($ano) && !empty($ano)){
						          $condicion= $sql.'and ano='.$ano;
							}else{
								  $condicion=$sql;
							}

							$condicion .= " and (tipo_agrupamiento=1 or tipo_agrupamiento=2 or tipo_agrupamiento=3)";

							$campos = ' cod_presi ,
										cod_entidad,
										cod_tipo_inst,
										cod_inst,
										ano,
										cod_sector,
										cod_programa,
										cod_sub_prog,
										denominacion,
										metas,
										unidad_medida,
										SUM(cantidad_estimada) as cantidad_estimada,
										SUM(costo_financiero) as "costo_financiero" ';

							$agrupar = 'GROUP BY    cod_presi,
													cod_entidad,
													cod_tipo_inst,
													cod_inst,
													ano ,
													cod_sector ,
													cod_programa,
													cod_sub_prog,
													denominacion,
													metas,
										            unidad_medida';



                               if($consolidado==1){
							       $DATOS_res = $this->v_programas_metas->findAll($condicion.$agrupar, $campos, ' cod_sector, cod_programa, cod_sub_prog ASC',  null, null);
                               }else{
                               	   $DATOS_res = $this->v_programas_metas->findAll($condicion, null, ' cod_sector, cod_programa ASC',  null, null);
                               }

							    $this->set('datos', $DATOS_res);
							    $this->set('year',  $ano);

    }//fin function



$this->set('entidad_federal', $this->Session->read('entidad_federal'));

}//fin function


function forma_2112_c($year=null){

	$sql='';

	if(!$year){

		    $this->layout = "ajax";
		    $this->set('ir', 'no');

			$cod_presi = $this->Session->read('SScodpresi');
			$cod_entidad = $this->Session->read('SScodentidad');
			$cod_tipo_inst = $this->Session->read('SScodtipoinst');
			$cod_inst = $this->Session->read('SScodinst');
			$cod_dep = $this->Session->read('SScoddep');
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

		                    $ano = $this->data['datos']['ano'];
						  	$consolidado = 2;
						  	 if(isset($this->data['datos']['consolidacion'])){
						  	         	$consolidado = $this->data['datos']['consolidacion'];
						  	 }

							$cod_presi = $this->Session->read('SScodpresi');
							$cod_entidad = $this->Session->read('SScodentidad');
							$cod_tipo_inst = $this->Session->read('SScodtipoinst');
							$cod_inst = $this->Session->read('SScodinst');
							$cod_dep = $this->Session->read('SScoddep');
						    $ejercicio = $ano;

						          if($consolidado==2){
						  	     	$sql = $this->SQLCA_consolidado($consolidado);
						  	}else if($consolidado==1){
						  		    $sql = $this->SQLCA_consolidado($consolidado);
						  	}

						  	if(isset($ano) && !empty($ano)){
						          $condicion= $sql.'and ano='.$ano;
							}else{
								  $condicion=$sql;
							}

							$condicion .= " and (tipo_agrupamiento=1 or tipo_agrupamiento=2 or tipo_agrupamiento=3  or (tipo_agrupamiento=4 and cod_proyecto!=0) or tipo_agrupamiento=5)   ";

							$campos = ' cod_presi ,
										cod_entidad,
										cod_tipo_inst,
										cod_inst,
										ano,
										cod_sector,
										cod_programa,
										cod_sub_prog,
										cod_proyecto,
										cod_activ_obra,
										denominacion,
										metas,
										unidad_medida,
										SUM(cantidad_estimada) as cantidad_estimada,
										SUM(costo_financiero) as "costo_financiero" ';

							$agrupar = 'GROUP BY    cod_presi,
													cod_entidad,
													cod_tipo_inst,
													cod_inst,
													ano ,
													cod_sector ,
													cod_programa,
													cod_sub_prog,
													cod_proyecto,
													cod_activ_obra,
													denominacion,
													metas,
										            unidad_medida';



                               if($consolidado==1){
							       $DATOS_res = $this->v_programas_metas->findAll($condicion.$agrupar, $campos, ' cod_sector, cod_programa, cod_sub_prog, cod_proyecto, cod_activ_obra ASC',  null, null);
                               }else{
                               	   $DATOS_res = $this->v_programas_metas->findAll($condicion, null, ' cod_sector, cod_programa, cod_proyecto, cod_activ_obra ASC',  null, null);
                               }

							    $this->set('datos', $DATOS_res);
							    $this->set('year',  $ano);

    }//fin function



$this->set('entidad_federal', $this->Session->read('entidad_federal'));

}//fin function

function forma_2112($year=null){

	$sql='';

	if(!$year){

		    $this->layout = "ajax";
		    $this->set('ir', 'no');

			$cod_presi = $this->Session->read('SScodpresi');
			$cod_entidad = $this->Session->read('SScodentidad');
			$cod_tipo_inst = $this->Session->read('SScodtipoinst');
			$cod_inst = $this->Session->read('SScodinst');
			$cod_dep = $this->Session->read('SScoddep');
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

		                    $ano = $this->data['datos']['ano'];
						  	$consolidado = 2;
						  	 if(isset($this->data['datos']['consolidacion'])){
						  	         	$consolidado = $this->data['datos']['consolidacion'];
						  	 }

							$cod_presi = $this->Session->read('SScodpresi');
							$cod_entidad = $this->Session->read('SScodentidad');
							$cod_tipo_inst = $this->Session->read('SScodtipoinst');
							$cod_inst = $this->Session->read('SScodinst');
							$cod_dep = $this->Session->read('SScoddep');
						    $ejercicio = $ano;

						          if($consolidado==2){
						  	     	$sql = $this->SQLCA_consolidado($consolidado);
						  	}else if($consolidado==1){
						  		    $sql = $this->SQLCA_consolidado($consolidado);
						  	}

						  	if(isset($ano) && !empty($ano)){
						          $condicion= $sql.' and ano='.$ano;
							}else{
								  $condicion=$sql;
							}

							/*

							$condicion .= " and (tipo_agrupamiento=1 or tipo_agrupamiento=2 or tipo_agrupamiento=3  or tipo_agrupamiento=4 )   ";

							$campos = ' cod_presi ,
										cod_entidad,
										cod_tipo_inst,
										cod_inst,
										ano,
										cod_sector,
										cod_programa,
										cod_sub_prog,
										cod_proyecto,
										denominacion,
										metas,
										unidad_medida,
										SUM(cantidad_estimada) as cantidad_estimada,
										SUM(costo_financiero) as "costo_financiero" ';

							$agrupar = 'GROUP BY    cod_presi,
													cod_entidad,
													cod_tipo_inst,
													cod_inst,
													ano ,
													cod_sector ,
													cod_programa,
													cod_sub_prog,
													cod_proyecto,
													denominacion,
													metas,
										            unidad_medida';



                               if($consolidado==1){
							       $DATOS_res = $this->v_programas_metas->findAll($condicion.$agrupar, $campos, ' cod_sector, cod_programa, cod_sub_prog, cod_proyecto ASC',  null, null);
                               }else{
                               	   $DATOS_res = $this->v_programas_metas->findAll($condicion, null, ' cod_sector, cod_programa, cod_proyecto ASC',  null, null);
                               }

							    $this->set('datos', $DATOS_res);
							    $this->set('year',  $ano);

							    */

							    $consulta_sql = " SELECT
											g.metas,
											g.unidad_medida,
											g.cod_presi,
											g.cod_entidad,
											g.cod_tipo_inst,
											g.cod_inst,
											g.cod_dep,
											g.ano,
											g.cod_sector,
											( SELECT h.denominacion FROM cfpd02_sector h WHERE h.cod_presi = g.cod_presi AND h.cod_entidad = g.cod_entidad AND h.cod_tipo_inst = g.cod_tipo_inst AND h.cod_inst = g.cod_inst AND h.cod_dep = g.cod_dep AND h.ano = g.ano AND h.cod_sector = g.cod_sector) AS deno_sector,
											g.cod_programa,
											( SELECT h.denominacion FROM cfpd02_programa h WHERE h.cod_presi = g.cod_presi AND h.cod_entidad = g.cod_entidad AND h.cod_tipo_inst = g.cod_tipo_inst AND h.cod_inst = g.cod_inst AND h.cod_dep = g.cod_dep AND h.ano = g.ano AND h.cod_sector = g.cod_sector AND h.cod_programa = g.cod_programa) AS deno_programa,
											g.cod_sub_prog AS cod_subprog,
											( SELECT h.denominacion FROM cfpd02_sub_prog h WHERE h.cod_presi = g.cod_presi AND h.cod_entidad = g.cod_entidad AND h.cod_tipo_inst = g.cod_tipo_inst AND h.cod_inst = g.cod_inst AND h.cod_dep = g.cod_dep AND h.ano = g.ano AND h.cod_sector = g.cod_sector AND h.cod_programa = g.cod_programa AND h.cod_sub_prog = g.cod_sub_prog) AS deno_subprog,
											g.cod_proyecto,
											( SELECT h.denominacion FROM cfpd02_proyecto h WHERE h.cod_presi = g.cod_presi AND h.cod_entidad = g.cod_entidad AND h.cod_tipo_inst = g.cod_tipo_inst AND h.cod_inst = g.cod_inst AND h.cod_dep = g.cod_dep AND h.ano = g.ano AND h.cod_sector = g.cod_sector AND h.cod_programa = g.cod_programa AND h.cod_sub_prog = g.cod_sub_prog AND h.cod_proyecto = g.cod_proyecto) AS denominacion,
											0 AS cod_activ_obra,
											( SELECT sum(x.costo_financiero) AS sum FROM cfpd09 x WHERE x.cod_presi = g.cod_presi AND x.cod_entidad = g.cod_entidad AND x.cod_tipo_inst = g.cod_tipo_inst AND x.cod_inst = g.cod_inst AND x.cod_dep = g.cod_dep AND x.ano = g.ano AND x.cod_sector = g.cod_sector AND x.cod_programa = g.cod_programa AND x.cod_sub_prog = g.cod_sub_prog AND x.cod_proyecto = g.cod_proyecto) AS costo_financiero,
											( SELECT sum(x.cantidad_estimada) AS sum FROM cfpd09 x WHERE x.cod_presi = g.cod_presi AND x.cod_entidad = g.cod_entidad AND x.cod_tipo_inst = g.cod_tipo_inst AND x.cod_inst = g.cod_inst AND x.cod_dep = g.cod_dep AND x.ano = g.ano AND x.cod_sector = g.cod_sector AND x.cod_programa = g.cod_programa AND x.cod_sub_prog = g.cod_sub_prog AND x.cod_proyecto = g.cod_proyecto) AS cantidad_estimada,
											4 AS identificador,
											4 AS tipo_agrupamiento

										FROM cfpd09_metas_proyecto g

										WHERE ".$condicion;

									$datos = $this->v_programas_metas->execute($consulta_sql);
									$this->set('datos', $datos);
									$this->set('year',  $ano);

    }//fin function



$this->set('entidad_federal', $this->Session->read('entidad_federal'));

}//fin function


function forma_2115($opcion=null, $opcion2=null){

	$sql='';

	if($opcion==null){

		    $this->layout = "ajax";
		    $this->set('ir', 'no');
		    $opcion = 1;

			$cod_presi = $this->Session->read('SScodpresi');
			$cod_entidad = $this->Session->read('SScodentidad');
			$cod_tipo_inst = $this->Session->read('SScodtipoinst');
			$cod_inst = $this->Session->read('SScodinst');
			$cod_dep = $this->Session->read('SScoddep');
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

    }else if($opcion==2){ $this->layout = "ajax";

                    $rs=$this->v_balance_ejecucion->execute("SELECT DISTINCT a.cod_sector, a.denominacion FROM cfpd02_sector a  WHERE ".$this->condicion());
				    foreach($rs as $l){
				    	if(!isset($seleccion)){$seleccion = mascara2($l[0]["cod_sector"]);}
						$v[]=mascara2($l[0]["cod_sector"]);
						$d[]=mascara2($l[0]["cod_sector"])." - ".$l[0]["denominacion"];
					}
					$sector = array_combine($v, $d);
			        $this->set('lista_numero', $sector);


	}else if($opcion==3){


				                        $this->layout = "pdf";

						                    $ano = $this->data['datos']['ano'];
										  	$consolidado = 2;
										  	 if(isset($this->data['datos']['consolidacion'])){
										  	         	$consolidado = $this->data['datos']['consolidacion'];
										  	 }

											$cod_presi = $this->Session->read('SScodpresi');
											$cod_entidad = $this->Session->read('SScodentidad');
											$cod_tipo_inst = $this->Session->read('SScodtipoinst');
											$cod_inst = $this->Session->read('SScodinst');
											$cod_dep = $this->Session->read('SScoddep');
										    $ejercicio = $ano;

										          if($consolidado==2){
										  	     	$sql = $this->SQLCA_consolidado($consolidado);
										  	}else if($consolidado==1){
										  		    $sql = $this->SQLCA_consolidado($consolidado);
										  	}

										  	if(isset($ano) && !empty($ano)){
										          $condicion= $sql.'and ano='.$ano;
											}else{
												  $condicion=$sql;
											}
											if(isset($this->data['datos']['cod_sector'])){
						  	         	      $condicion .= " and cod_sector='".$this->data['datos']['cod_sector']."'";
						  	                }

                            $DATOS_res = $this->cfpd06->findAll($condicion, null, 'cod_sector, denominacion ASC', null, null);
				            $this->set('datos', $DATOS_res);
				            $this->set('sector',$this->cfpd02_sector->findAll($condicion,null,'cod_sector ASC',null,null,null));

	}//fin else

$this->set('opcion', $opcion);
$this->set('opcion2',$opcion2);
$this->set('entidad_federal', $this->Session->read('entidad_federal'));
}//fin function

function forma_2115_a($opcion=null, $opcion2=null){

	$sql='';

	if($opcion==null){

		    $this->layout = "ajax";
		    $this->set('ir', 'no');
		    $opcion = 1;

			$cod_presi = $this->Session->read('SScodpresi');
			$cod_entidad = $this->Session->read('SScodentidad');
			$cod_tipo_inst = $this->Session->read('SScodtipoinst');
			$cod_inst = $this->Session->read('SScodinst');
			$cod_dep = $this->Session->read('SScoddep');
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

    }else if($opcion==2){ $this->layout = "ajax";

                    $rs=$this->v_balance_ejecucion->execute("SELECT DISTINCT a.cod_sector, a.denominacion FROM cfpd02_sector a  WHERE ".$this->condicion()." order BY a.cod_sector ASC");
				    foreach($rs as $l){
				    	if(!isset($seleccion)){$seleccion = mascara2($l[0]["cod_sector"]);}
						$v[]=mascara2($l[0]["cod_sector"]);
						$d[]=mascara2($l[0]["cod_sector"])." - ".$l[0]["denominacion"];
					}
					$sector = array_combine($v, $d);
			        $this->set('lista_numero', $sector);



    }else if($opcion==4){ $this->layout = "ajax";

                    $rs=$this->v_balance_ejecucion->execute("SELECT DISTINCT a.cod_sector, a.cod_programa, a.denominacion FROM cfpd02_programa a  WHERE ".$this->condicion()." and cod_sector='".$opcion2."' order BY a.cod_sector, a.cod_programa ASC");
				    foreach($rs as $l){
						$v[]=mascara2($l[0]["cod_programa"]);
						$d[]=mascara2($l[0]["cod_programa"])." - ".$l[0]["denominacion"];
					}
					$sector = array_combine($v, $d);
			        $this->set('lista_numero', $sector);
			        $this->set('sector', $opcion2);


	}else if($opcion==3){


				                        $this->layout = "pdf";

						                    $ano = $this->data['datos']['ano'];
										  	$consolidado = 2;
										  	 if(isset($this->data['datos']['consolidacion'])){
										  	         	$consolidado = $this->data['datos']['consolidacion'];
										  	 }

											$cod_presi = $this->Session->read('SScodpresi');
											$cod_entidad = $this->Session->read('SScodentidad');
											$cod_tipo_inst = $this->Session->read('SScodtipoinst');
											$cod_inst = $this->Session->read('SScodinst');
											$cod_dep = $this->Session->read('SScoddep');
										    $ejercicio = $ano;

										          if($consolidado==2){
										  	     	$sql = $this->SQLCA_consolidado($consolidado);
										  	}else if($consolidado==1){
										  		    $sql = $this->SQLCA_consolidado($consolidado);
										  	}

										  	if(isset($ano) && !empty($ano)){
										          $condicion= $sql.'and ano='.$ano;
											}else{
												  $condicion=$sql;
											}
											$condicion2 = $condicion;
											if(isset($this->data['datos']['cod_sector'])){
						  	         	      $condicion .= " and cod_sector='".$this->data['datos']['cod_sector']."'";
						  	                }
						  	                if(isset($this->data['datos']['cod_programa'])){
						  	         	      $condicion .= " and cod_programa='".$this->data['datos']['cod_programa']."'";
						  	                }

                            $DATOS_res = $this->cfpd06->findAll($condicion, null, 'cod_sector, cod_programa, denominacion ASC', null, null);
				            $this->set('datos', $DATOS_res);
				            $this->set('sector',  $this->cfpd02_sector->findAll($condicion2,null,'cod_sector ASC',null,null,null));
				            $this->set('programa',$this->cfpd02_programa->findAll($condicion,null,'cod_sector, cod_programa ASC',null,null,null));

	}//fin else

$this->set('opcion', $opcion);
$this->set('opcion2',$opcion2);
$this->set('entidad_federal', $this->Session->read('entidad_federal'));
}//fin function




function forma_2115_b($opcion=null, $opcion2=null, $opcion3=null){

	$sql='';

	if($opcion==null){

		    $this->layout = "ajax";
		    $this->set('ir', 'no');
		    $opcion = 1;

			$cod_presi = $this->Session->read('SScodpresi');
			$cod_entidad = $this->Session->read('SScodentidad');
			$cod_tipo_inst = $this->Session->read('SScodtipoinst');
			$cod_inst = $this->Session->read('SScodinst');
			$cod_dep = $this->Session->read('SScoddep');
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

    }else if($opcion==2){ $this->layout = "ajax";

                    $rs=$this->v_balance_ejecucion->execute("SELECT DISTINCT a.cod_sector, a.denominacion FROM cfpd02_sector a  WHERE ".$this->condicion()." order BY a.cod_sector ASC");
				    foreach($rs as $l){
				    	if(!isset($seleccion)){$seleccion = mascara2($l[0]["cod_sector"]);}
						$v[]=mascara2($l[0]["cod_sector"]);
						$d[]=mascara2($l[0]["cod_sector"])." - ".$l[0]["denominacion"];
					}
					$sector = array_combine($v, $d);
			        $this->set('lista_numero', $sector);



    }else if($opcion==4){ $this->layout = "ajax";

                    $rs=$this->v_balance_ejecucion->execute("SELECT DISTINCT a.cod_sector, a.cod_programa, a.denominacion FROM cfpd02_programa a  WHERE ".$this->condicion()." and a.cod_sector='".$opcion2."' order BY a.cod_sector, a.cod_programa ASC");
				    foreach($rs as $l){
						$v[]=mascara2($l[0]["cod_programa"]);
						$d[]=mascara2($l[0]["cod_programa"])." - ".$l[0]["denominacion"];
					}
					$programa= array_combine($v, $d);
			        $this->set('lista_numero', $programa);
			        $this->set('sector', $opcion2);

    }else if($opcion==5){ $this->layout = "ajax";

                    $rs=$this->v_balance_ejecucion->execute("SELECT DISTINCT a.cod_sector, a.cod_programa, a.cod_sub_prog,  a.denominacion FROM cfpd02_sub_prog a  WHERE ".$this->condicion()." and a.cod_sector='".$opcion2."'  and a.cod_programa='".$opcion3."' order BY a.cod_sector, a.cod_programa, a.cod_sub_prog ASC");
				    foreach($rs as $l){
						$v[]=mascara2($l[0]["cod_sub_prog"]);
						$d[]=mascara2($l[0]["cod_sub_prog"])." - ".$l[0]["denominacion"];
					}
					$sub_programa = array_combine($v, $d);
			        $this->set('lista_numero', $sub_programa);
			        $this->set('sector', $opcion2);
			        $this->set('programa', $opcion3);


	}else if($opcion==3){


				                        $this->layout = "pdf";

						                    $ano = $this->data['datos']['ano'];
										  	$consolidado = 2;
										  	 if(isset($this->data['datos']['consolidacion'])){
										  	         	$consolidado = $this->data['datos']['consolidacion'];
										  	 }

											$cod_presi = $this->Session->read('SScodpresi');
											$cod_entidad = $this->Session->read('SScodentidad');
											$cod_tipo_inst = $this->Session->read('SScodtipoinst');
											$cod_inst = $this->Session->read('SScodinst');
											$cod_dep = $this->Session->read('SScoddep');
										    $ejercicio = $ano;

										          if($consolidado==2){
										  	     	$sql = $this->SQLCA_consolidado($consolidado);
										  	}else if($consolidado==1){
										  		    $sql = $this->SQLCA_consolidado($consolidado);
										  	}

										  	if(isset($ano) && !empty($ano)){
										          $condicion= $sql.'and ano='.$ano;
											}else{
												  $condicion=$sql;
											}
											$condicion2 = $condicion;
											if(isset($this->data['datos']['cod_sector'])){
						  	         	      $condicion .= " and cod_sector='".$this->data['datos']['cod_sector']."'";
						  	                }
						  	                if(isset($this->data['datos']['cod_programa'])){
						  	         	      $condicion .= " and cod_programa='".$this->data['datos']['cod_programa']."'";
						  	                }
                                            $condicion3 = $condicion;
						  	                 if(isset($this->data['datos']['cod_sub_prog'])){
						  	         	      $condicion .= " and cod_sub_prog='".$this->data['datos']['cod_sub_prog']."'";
						  	                }

                            $DATOS_res = $this->cfpd06->findAll($condicion, null, 'cod_sector, cod_programa, cod_sub_prog,  denominacion ASC', null, null);
				            $this->set('datos', $DATOS_res);
				            $this->set('sector',  $this->cfpd02_sector->findAll($condicion2,null,'cod_sector ASC',null,null,null));
				            $this->set('programa',$this->cfpd02_programa->findAll($condicion3,null,'cod_sector, cod_programa ASC',null,null,null));
				            $this->set('sub_programa',$this->cfpd02_sub_prog->findAll($condicion,null,'cod_sector, cod_programa, cod_sub_prog ASC',null,null,null));

	}//fin else

$this->set('opcion', $opcion);
$this->set('opcion2',$opcion2);
$this->set('entidad_federal', $this->Session->read('entidad_federal'));
}//fin function




function forma_2116($opcion=null, $opcion2=null, $opcion3=null){

	$sql='';

	if($opcion==null){

		    $this->layout = "ajax";
		    $this->set('ir', 'no');
		    $opcion = 1;

			$cod_presi = $this->Session->read('SScodpresi');
			$cod_entidad = $this->Session->read('SScodentidad');
			$cod_tipo_inst = $this->Session->read('SScodtipoinst');
			$cod_inst = $this->Session->read('SScodinst');
			$cod_dep = $this->Session->read('SScoddep');
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

    }else if($opcion==2){ $this->layout = "ajax";

                    $rs=$this->v_balance_ejecucion->execute("SELECT DISTINCT a.cod_sector, a.denominacion FROM cfpd02_sector a  WHERE ".$this->condicion()." order BY a.cod_sector ASC");
				    foreach($rs as $l){
				    	if(!isset($seleccion)){$seleccion = mascara2($l[0]["cod_sector"]);}
						$v[]=mascara2($l[0]["cod_sector"]);
						$d[]=mascara2($l[0]["cod_sector"])." - ".$l[0]["denominacion"];
					}
					$sector = array_combine($v, $d);
			        $this->set('lista_numero', $sector);



    }else if($opcion==4){ $this->layout = "ajax";

                    $rs=$this->v_balance_ejecucion->execute("SELECT DISTINCT a.cod_sector, a.cod_programa, a.denominacion FROM cfpd02_programa a  WHERE ".$this->condicion()." and a.cod_sector='".$opcion2."' order BY a.cod_sector, a.cod_programa ASC");
				    foreach($rs as $l){
						$v[]=mascara2($l[0]["cod_programa"]);
						$d[]=mascara2($l[0]["cod_programa"])." - ".$l[0]["denominacion"];
					}
					$programa= array_combine($v, $d);
			        $this->set('lista_numero', $programa);
			        $this->set('sector', $opcion2);

    }else if($opcion==5){ $this->layout = "ajax";

                    $rs=$this->v_balance_ejecucion->execute("SELECT DISTINCT a.cod_sector, a.cod_programa, a.cod_sub_prog,  a.denominacion FROM cfpd02_sub_prog a  WHERE ".$this->condicion()." and a.cod_sector='".$opcion2."'  and a.cod_programa='".$opcion3."' order BY a.cod_sector, a.cod_programa, a.cod_sub_prog ASC");
				    foreach($rs as $l){
						$v[]=mascara2($l[0]["cod_sub_prog"]);
						$d[]=mascara2($l[0]["cod_sub_prog"])." - ".$l[0]["denominacion"];
					}
					$sub_programa = array_combine($v, $d);
			        $this->set('lista_numero', $sub_programa);
			        $this->set('sector', $opcion2);
			        $this->set('programa', $opcion3);


	}else if($opcion==3){


				                        $this->layout = "pdf";

						                    $ano = $this->data['datos']['ano'];
										  	$consolidado = 2;
										  	 if(isset($this->data['datos']['consolidacion'])){
										  	         	$consolidado = $this->data['datos']['consolidacion'];
										  	 }

											$cod_presi = $this->Session->read('SScodpresi');
											$cod_entidad = $this->Session->read('SScodentidad');
											$cod_tipo_inst = $this->Session->read('SScodtipoinst');
											$cod_inst = $this->Session->read('SScodinst');
											$cod_dep = $this->Session->read('SScoddep');
										    $ejercicio = $ano;

										        if($consolidado==2){
											  	     	$sql = $this->SQLCA_consolidado($consolidado);
											  	}else if($consolidado==1){
											  		    $sql = $this->SQLCA_consolidado($consolidado);
											  	}

											  	if(isset($ano) && !empty($ano)){
											          $condicion= $sql.'and ano='.$ano;
												}else{
													  $condicion=$sql;
							                    }

							                $condicion2 = $condicion;
											if(isset($this->data['datos']['cod_sector'])){
						  	         	      $condicion .= " and cod_sector='".$this->data['datos']['cod_sector']."'";
						  	                }
						  	                if(isset($this->data['datos']['cod_programa'])){
						  	         	      $condicion .= " and cod_programa='".$this->data['datos']['cod_programa']."'";
						  	                }
                                            $condicion3 = $condicion;
						  	               /*  if(isset($this->data['datos']['cod_sub_prog'])){
						  	         	      $condicion .= " and cod_sub_prog='".$this->data['datos']['cod_sub_prog']."'";
						  	                }*/


                            $this->set('partida',$this->cfpd01_ano_partida->findAll('ejercicio='.$ejercicio,null, 'cod_grupo, cod_partida ASC',null, null, null));
						    $DATOS_res = $this->cfpd05->findAll($condicion, null, 'cod_sector, cod_programa, cod_partida ASC', null, null);
						    $this->set('datos', $DATOS_res);
							$this->set('ejercicio', $ano);
				            $this->set('sector',  $this->cfpd02_sector->findAll($condicion2,null,'cod_sector ASC',null,null,null));
				            $this->set('programa',$this->cfpd02_programa->findAll($condicion3,null,'cod_sector, cod_programa ASC',null,null,null));
//				            $this->set('sub_programa',$this->cfpd02_sub_prog->findAll($condicion,null,'cod_sector, cod_programa, cod_sub_prog ASC',null,null,null));

	}//fin else

$this->set('opcion', $opcion);
$this->set('opcion2',$opcion2);
$this->set('entidad_federal', $this->Session->read('entidad_federal'));
}//fin function


function forma_2116_a($opcion=null, $opcion2=null, $opcion3=null){

	$sql='';

	if($opcion==null){

		    $this->layout = "ajax";
		    $this->set('ir', 'no');
		    $opcion = 1;

			$cod_presi = $this->Session->read('SScodpresi');
			$cod_entidad = $this->Session->read('SScodentidad');
			$cod_tipo_inst = $this->Session->read('SScodtipoinst');
			$cod_inst = $this->Session->read('SScodinst');
			$cod_dep = $this->Session->read('SScoddep');
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

    }else if($opcion==2){ $this->layout = "ajax";

                    $rs=$this->v_balance_ejecucion->execute("SELECT DISTINCT a.cod_sector, a.denominacion FROM cfpd02_sector a  WHERE ".$this->condicion()." order BY a.cod_sector ASC");
				    foreach($rs as $l){
				    	if(!isset($seleccion)){$seleccion = mascara2($l[0]["cod_sector"]);}
						$v[]=mascara2($l[0]["cod_sector"]);
						$d[]=mascara2($l[0]["cod_sector"])." - ".$l[0]["denominacion"];
					}
					$sector = array_combine($v, $d);
			        $this->set('lista_numero', $sector);



    }else if($opcion==4){ $this->layout = "ajax";

                    $rs=$this->v_balance_ejecucion->execute("SELECT DISTINCT a.cod_sector, a.cod_programa, a.denominacion FROM cfpd02_programa a  WHERE ".$this->condicion()." and a.cod_sector='".$opcion2."' order BY a.cod_sector, a.cod_programa ASC");
				    foreach($rs as $l){
						$v[]=mascara2($l[0]["cod_programa"]);
						$d[]=mascara2($l[0]["cod_programa"])." - ".$l[0]["denominacion"];
					}
					$programa= array_combine($v, $d);
			        $this->set('lista_numero', $programa);
			        $this->set('sector', $opcion2);

    }else if($opcion==5){ $this->layout = "ajax";

                    $rs=$this->v_balance_ejecucion->execute("SELECT DISTINCT a.cod_sector, a.cod_programa, a.cod_sub_prog,  a.denominacion FROM cfpd02_sub_prog a  WHERE ".$this->condicion()." and a.cod_sector='".$opcion2."'  and a.cod_programa='".$opcion3."' order BY a.cod_sector, a.cod_programa, a.cod_sub_prog ASC");
				    foreach($rs as $l){
						$v[]=mascara2($l[0]["cod_sub_prog"]);
						$d[]=mascara2($l[0]["cod_sub_prog"])." - ".$l[0]["denominacion"];
					}
					$sub_programa = array_combine($v, $d);
			        $this->set('lista_numero', $sub_programa);
			        $this->set('sector', $opcion2);
			        $this->set('programa', $opcion3);


	}else if($opcion==3){


				                        $this->layout = "pdf";

						                    $ano = $this->data['datos']['ano'];
										  	$consolidado = 2;
										  	 if(isset($this->data['datos']['consolidacion'])){
										  	         	$consolidado = $this->data['datos']['consolidacion'];
										  	 }

											$cod_presi = $this->Session->read('SScodpresi');
											$cod_entidad = $this->Session->read('SScodentidad');
											$cod_tipo_inst = $this->Session->read('SScodtipoinst');
											$cod_inst = $this->Session->read('SScodinst');
											$cod_dep = $this->Session->read('SScoddep');
										    $ejercicio = $ano;

										        if($consolidado==2){
											  	     	$sql = $this->SQLCA_consolidado($consolidado);
											  	}else if($consolidado==1){
											  		    $sql = $this->SQLCA_consolidado($consolidado);
											  	}

											  	if(isset($ano) && !empty($ano)){
											          $condicion= $sql.'and ano='.$ano;
												}else{
													  $condicion=$sql;
							                    }

							                $condicion2 = $condicion;
											if(isset($this->data['datos']['cod_sector'])){
						  	         	      $condicion .= " and cod_sector='".$this->data['datos']['cod_sector']."'";
						  	                }
						  	                /*if(isset($this->data['datos']['cod_programa'])){
						  	         	      $condicion .= " and cod_programa='".$this->data['datos']['cod_programa']."'";
						  	                }
                                            $condicion3 = $condicion;
						  	                 if(isset($this->data['datos']['cod_sub_prog'])){
						  	         	      $condicion .= " and cod_sub_prog='".$this->data['datos']['cod_sub_prog']."'";
						  	                }*/


                            $this->set('partida',$this->cfpd01_ano_partida->findAll('ejercicio='.$ejercicio,null, 'cod_grupo, cod_partida ASC',null, null, null));
						    $DATOS_res = $this->cfpd05->findAll($condicion, null, 'cod_sector, cod_partida ASC', null, null);
						    $this->set('datos', $DATOS_res);
							$this->set('ejercicio', $ano);
				            $this->set('sector',  $this->cfpd02_sector->findAll($condicion2,null,'cod_sector ASC',null,null,null));
//				            $this->set('programa',$this->cfpd02_programa->findAll($condicion3,null,'cod_sector, cod_programa ASC',null,null,null));
//				            $this->set('sub_programa',$this->cfpd02_sub_prog->findAll($condicion,null,'cod_sector, cod_programa, cod_sub_prog ASC',null,null,null));

	}//fin else

$this->set('opcion', $opcion);
$this->set('opcion2',$opcion2);
$this->set('entidad_federal', $this->Session->read('entidad_federal'));
}//fin function


function forma_2116_b($opcion=null, $opcion2=null, $opcion3=null){

	$sql='';

	if($opcion==null){

		    $this->layout = "ajax";
		    $this->set('ir', 'no');
		    $opcion = 1;

			$cod_presi = $this->Session->read('SScodpresi');
			$cod_entidad = $this->Session->read('SScodentidad');
			$cod_tipo_inst = $this->Session->read('SScodtipoinst');
			$cod_inst = $this->Session->read('SScodinst');
			$cod_dep = $this->Session->read('SScoddep');
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

    }else if($opcion==2){ $this->layout = "ajax";

                    $rs=$this->v_balance_ejecucion->execute("SELECT DISTINCT a.cod_sector, a.denominacion FROM cfpd02_sector a  WHERE ".$this->condicion()." order BY a.cod_sector ASC");
				    foreach($rs as $l){
				    	if(!isset($seleccion)){$seleccion = mascara2($l[0]["cod_sector"]);}
						$v[]=mascara2($l[0]["cod_sector"]);
						$d[]=mascara2($l[0]["cod_sector"])." - ".$l[0]["denominacion"];
					}
					$sector = array_combine($v, $d);
			        $this->set('lista_numero', $sector);



    }else if($opcion==4){ $this->layout = "ajax";

                    $rs=$this->v_balance_ejecucion->execute("SELECT DISTINCT a.cod_sector, a.cod_programa, a.denominacion FROM cfpd02_programa a  WHERE ".$this->condicion()." and a.cod_sector='".$opcion2."' order BY a.cod_sector, a.cod_programa ASC");
				    foreach($rs as $l){
						$v[]=mascara2($l[0]["cod_programa"]);
						$d[]=mascara2($l[0]["cod_programa"])." - ".$l[0]["denominacion"];
					}
					$programa= array_combine($v, $d);
			        $this->set('lista_numero', $programa);
			        $this->set('sector', $opcion2);

    }else if($opcion==5){ $this->layout = "ajax";

                    $rs=$this->v_balance_ejecucion->execute("SELECT DISTINCT a.cod_sector, a.cod_programa, a.cod_sub_prog,  a.denominacion FROM cfpd02_sub_prog a  WHERE ".$this->condicion()." and a.cod_sector='".$opcion2."'  and a.cod_programa='".$opcion3."' order BY a.cod_sector, a.cod_programa, a.cod_sub_prog ASC");
				    foreach($rs as $l){
						$v[]=mascara2($l[0]["cod_sub_prog"]);
						$d[]=mascara2($l[0]["cod_sub_prog"])." - ".$l[0]["denominacion"];
					}
					$sub_programa = array_combine($v, $d);
			        $this->set('lista_numero', $sub_programa);
			        $this->set('sector', $opcion2);
			        $this->set('programa', $opcion3);


	}else if($opcion==3){


				                        $this->layout = "pdf";

						                    $ano = $this->data['datos']['ano'];
										  	$consolidado = 2;
										  	 if(isset($this->data['datos']['consolidacion'])){
										  	         	$consolidado = $this->data['datos']['consolidacion'];
										  	 }

											$cod_presi = $this->Session->read('SScodpresi');
											$cod_entidad = $this->Session->read('SScodentidad');
											$cod_tipo_inst = $this->Session->read('SScodtipoinst');
											$cod_inst = $this->Session->read('SScodinst');
											$cod_dep = $this->Session->read('SScoddep');
										    $ejercicio = $ano;

										        if($consolidado==2){
											  	     	$sql = $this->SQLCA_consolidado($consolidado);
											  	}else if($consolidado==1){
											  		    $sql = $this->SQLCA_consolidado($consolidado);
											  	}

											  	if(isset($ano) && !empty($ano)){
											          $condicion= $sql.'and ano='.$ano;
												}else{
													  $condicion=$sql;
							                    }

							                $condicion2 = $condicion;
											if(isset($this->data['datos']['cod_sector'])){
						  	         	      $condicion .= " and cod_sector='".$this->data['datos']['cod_sector']."'";
						  	                }
						  	                if(isset($this->data['datos']['cod_programa'])){
						  	         	      $condicion .= " and cod_programa='".$this->data['datos']['cod_programa']."'";
						  	                }
                                            $condicion3 = $condicion;
						  	                 if(isset($this->data['datos']['cod_sub_prog'])){
						  	         	      $condicion .= " and cod_sub_prog='".$this->data['datos']['cod_sub_prog']."'";
						  	                }


                            $this->set('partida',$this->cfpd01_ano_partida->findAll('ejercicio='.$ejercicio,null, 'cod_grupo, cod_partida ASC',null, null, null));
						    $DATOS_res = $this->cfpd05->findAll($condicion, null, 'cod_sector, cod_programa, cod_sub_prog, cod_partida ASC', null, null);
						    $this->set('datos', $DATOS_res);
							$this->set('ejercicio', $ano);
				            $this->set('sector',  $this->cfpd02_sector->findAll($condicion2,null,'cod_sector ASC',null,null,null));
				            $this->set('programa',$this->cfpd02_programa->findAll($condicion3,null,'cod_sector, cod_programa ASC',null,null,null));
				            $this->set('sub_programa',$this->cfpd02_sub_prog->findAll($condicion,null,'cod_sector, cod_programa, cod_sub_prog ASC',null,null,null));

	}//fin else

$this->set('opcion', $opcion);
$this->set('opcion2',$opcion2);
$this->set('entidad_federal', $this->Session->read('entidad_federal'));
}//fin function


function forma_2124($year=null){

	$sql='';

	if(!$year){

		    $this->layout = "ajax";
		    $this->set('ir', 'no');

			$cod_presi = $this->Session->read('SScodpresi');
			$cod_entidad = $this->Session->read('SScodentidad');
			$cod_tipo_inst = $this->Session->read('SScodtipoinst');
			$cod_inst = $this->Session->read('SScodinst');
			$cod_dep = $this->Session->read('SScoddep');
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
  	$ano = '';

  	$ano = $this->data['datos']['ano'];
  	$consolidado = 2;
  	 if(isset($this->data['datos']['consolidacion'])){
  	         	$consolidado = $this->data['datos']['consolidacion'];
  	 }



	$cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
    $ejercicio = $ano;

          if($consolidado==2){
  	     	$sql   = $this->SQLCA_consolidado($consolidado);

  	}else if($consolidado==1){
  		    $sql = $this->SQLCA_consolidado($consolidado);
  	}

$sql_2 = $this->condicion()." and ano='".$ano."' ";


	if($ano!=''){
	$condicion_sql = $sql." and ano=".$ano;
	}else{
    $condicion_sql = $sql;
	}

	$_SESSION['ejercicio'] = $ano;


if($consolidado==2){
$sql_aux="SELECT DISTINCT
                      cod_presi,
					  cod_entidad,
					  cod_tipo_inst,
					  cod_inst,
					  cod_dep,
					  ano,
					  cod_partida,
					  cod_generica,
                      cod_especifica,
                      cod_sub_espec,
					  upper(denominacion_partida) as denominacion_partida,
					  sector_1,
					  sector_2,
					  sector_3,
					  sector_4,
					  sector_5,
					  sector_6,
					  sector_7,
					  sector_8,
					  sector_9,
					  sector_10,
					  sector_11,
					  sector_12,
					  sector_13,
					  sector_14,
					  sector_15,
					  total

		           FROM v_nivel_subpartida_sector WHERE ".$condicion_sql."  ORDER BY  cod_presi,
																				      cod_entidad,
																				      cod_tipo_inst,
																					  cod_inst,
																					  cod_dep,
																					  ano,
																					  cod_partida,
																					  cod_generica,
																                      cod_especifica,
																                      cod_sub_espec; ";
}else{
$sql_aux="     SELECT DISTINCT
                      cod_presi,
					  cod_entidad,
					  cod_tipo_inst,
					  cod_inst,
					  ano,
					  cod_partida,
					  cod_generica,
                      cod_especifica,
                      cod_sub_espec,
					  upper(denominacion_partida) as denominacion_partida,
					  SUM(sector_1) as sector_1,
					  SUM(sector_2) as sector_2,
					  SUM(sector_3) as sector_3,
					  SUM(sector_4) as sector_4,
					  SUM(sector_5) as sector_5,
					  SUM(sector_6) as sector_6,
					  SUM(sector_7) as sector_7,
					  SUM(sector_8) as sector_8,
					  SUM(sector_9) as sector_9,
					  SUM(sector_10) as sector_10,
					  SUM(sector_11) as sector_11,
					  SUM(sector_12) as sector_12,
					  SUM(sector_13) as sector_13,
					  SUM(sector_14) as sector_14,
					  SUM(sector_15) as sector_15,
					  SUM(total)     as total

			     FROM  v_nivel_subpartida_sector

			     WHERE ".$condicion_sql."

			     GROUP BY cod_presi,
						  cod_entidad,
						  cod_tipo_inst,
						  cod_inst,
						  ano,
						  cod_partida,
						  cod_generica,
                          cod_especifica,
                          cod_sub_espec,
						  denominacion_partida

                 ORDER BY cod_presi,
						  cod_entidad,
						  cod_tipo_inst,
						  cod_inst,
						  ano,
						  cod_partida,
						  cod_generica,
                          cod_especifica,
                          cod_sub_espec ; ";
}//fin if



			    $sector=$this->cfpd02_sector->findAll($sql_2,null,'cod_sector ASC');
			    $DATOS_res = $this->cfpd05->execute($sql_aux);
			    $this->set('datos', $DATOS_res);
			    $this->set('sector', $sector);



    }//fin function



$this->set('entidad_federal', $this->Session->read('entidad_federal'));

}//fin function

function forma_2120($year=null){

	$sql='';

	if(!$year){

		    $this->layout = "ajax";
		    $this->set('ir', 'no');

			$cod_presi = $this->Session->read('SScodpresi');
			$cod_entidad = $this->Session->read('SScodentidad');
			$cod_tipo_inst = $this->Session->read('SScodtipoinst');
			$cod_inst = $this->Session->read('SScodinst');
			$cod_dep = $this->Session->read('SScoddep');
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

	}else{  $this->layout = "pdf";

		                $ano = '';
					  	$ano = $this->data['datos']['ano'];
					  	$consolidado = 2;
					  	 if(isset($this->data['datos']['consolidacion'])){
					  	         	$consolidado = $this->data['datos']['consolidacion'];
					  	 }



						$cod_presi = $this->Session->read('SScodpresi');
						$cod_entidad = $this->Session->read('SScodentidad');
						$cod_tipo_inst = $this->Session->read('SScodtipoinst');
						$cod_inst = $this->Session->read('SScodinst');
						$cod_dep = $this->Session->read('SScoddep');
					    $ejercicio = $ano;

					          if($consolidado==2){
					  	     	$sql   = $this->SQLCA_consolidado($consolidado);

					  	}else if($consolidado==1){
					  		    $sql = $this->SQLCA_consolidado($consolidado);
					  	}

					$sql_2 = $this->condicion()." and ano='".$ano."' ";


						if($ano!=''){
						$condicion_sql = $sql." and ano=".$ano;
						}else{
					    $condicion_sql = $sql;
						}


              $campos = '   cod_presi ,
							cod_entidad,
							cod_tipo_inst,
							cod_inst,
							ano,
							cod_sector, cod_programa, cod_sub_prog, cod_partida, cod_generica, cod_especifica, cod_sub_espec,
							SUM(asignacion_anual) as "asignacion_anual" ';

			  $agrupar = 'GROUP BY  cod_presi,
									cod_entidad,
									cod_tipo_inst,
									cod_inst,
									ano ,
									cod_sector, cod_programa, cod_sub_prog, cod_partida, cod_generica, cod_especifica, cod_sub_espec';


							if($ano!=''){
							$condicion = $sql." and ano=".$ano;
							}else{
						    $condicion = $sql;
							}

						    $this->set('sub_especifica',$this->cfpd01_ano_sub_espec->findAll('ejercicio='.$ejercicio.' and cod_grupo=4 and cod_partida=7 and (cod_generica=1 or cod_generica=3) and cod_especifica=3',null, 'cod_grupo, cod_partida, cod_generica, cod_especifica, cod_sub_espec  ASC',null, null, null));

						    $DATOS_res = $this->cfpd05->findAll($condicion.' and cod_partida=407 and (cod_generica=1 or cod_generica=3) and cod_especifica=3'.$agrupar, $campos, ' cod_sector, cod_programa, cod_sub_prog, cod_partida, cod_generica, cod_especifica, cod_sub_espec ASC',  null, null);
                            $this->set('datos',$DATOS_res);


    }//fin function



$this->set('entidad_federal', $this->Session->read('entidad_federal'));

}//fin function


function forma_2121($year=null){

	$sql='';

	if(!$year){

		    $this->layout = "ajax";
		    $this->set('ir', 'no');

			$cod_presi = $this->Session->read('SScodpresi');
			$cod_entidad = $this->Session->read('SScodentidad');
			$cod_tipo_inst = $this->Session->read('SScodtipoinst');
			$cod_inst = $this->Session->read('SScodinst');
			$cod_dep = $this->Session->read('SScoddep');
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

	}else{  $this->layout = "pdf";

		                $ano = '';
					  	$ano = $this->data['datos']['ano'];
					  	$consolidado = 2;
					  	 if(isset($this->data['datos']['consolidacion'])){
					  	         	$consolidado = $this->data['datos']['consolidacion'];
					  	 }



						$cod_presi = $this->Session->read('SScodpresi');
						$cod_entidad = $this->Session->read('SScodentidad');
						$cod_tipo_inst = $this->Session->read('SScodtipoinst');
						$cod_inst = $this->Session->read('SScodinst');
						$cod_dep = $this->Session->read('SScoddep');
					    $ejercicio = $ano;

					          if($consolidado==2){
					  	     	$sql   = $this->SQLCA_consolidado($consolidado);

					  	}else if($consolidado==1){
					  		    $sql = $this->SQLCA_consolidado($consolidado);
					  	}

					$sql_2 = $this->condicion()." and ano='".$ano."' ";


						if($ano!=''){
						$condicion_sql = $sql." and ano=".$ano;
						}else{
					    $condicion_sql = $sql;
						}


              $campos = '   cod_presi ,
							cod_entidad,
							cod_tipo_inst,
							cod_inst,
							ano,
							cod_sector, cod_programa, cod_sub_prog, cod_partida, cod_generica, cod_especifica, cod_sub_espec,
							SUM(asignacion_anual) as "asignacion_anual" ';

			  $agrupar = 'GROUP BY  cod_presi,
									cod_entidad,
									cod_tipo_inst,
									cod_inst,
									ano ,
									cod_sector, cod_programa, cod_sub_prog, cod_partida, cod_generica, cod_especifica, cod_sub_espec';


							if($ano!=''){
							$condicion = $sql." and ano=".$ano;
							}else{
						    $condicion = $sql;
							}

						    $this->set('sub_especifica',$this->cfpd01_ano_sub_espec->findAll('ejercicio='.$ejercicio.' and cod_grupo=4 and cod_partida=7 and (cod_generica=1 or cod_generica=3) and cod_especifica=1',null, 'cod_grupo, cod_partida, cod_generica, cod_especifica, cod_sub_espec  ASC',null, null, null));

						    $DATOS_res = $this->cfpd05->findAll($condicion.' and cod_partida=407 and (cod_generica=1 or cod_generica=3) and cod_especifica=1'.$agrupar, $campos, ' cod_sector, cod_programa, cod_sub_prog, cod_partida, cod_generica, cod_especifica, cod_sub_espec ASC',  null, null);
                            $this->set('datos',$DATOS_res);


    }//fin function



$this->set('entidad_federal', $this->Session->read('entidad_federal'));

}//fin function

function forma_2121_a($year=null){

	$sql='';

	if(!$year){

		    $this->layout = "ajax";
		    $this->set('ir', 'no');

			$cod_presi = $this->Session->read('SScodpresi');
			$cod_entidad = $this->Session->read('SScodentidad');
			$cod_tipo_inst = $this->Session->read('SScodtipoinst');
			$cod_inst = $this->Session->read('SScodinst');
			$cod_dep = $this->Session->read('SScoddep');
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
                            $ano = $this->data['datos']['ano'];
						  	$consolidado = 2;
						  	 if(isset($this->data['datos']['consolidacion'])){
						  	         	$consolidado = $this->data['datos']['consolidacion'];
						  	 }

							$cod_presi = $this->Session->read('SScodpresi');
							$cod_entidad = $this->Session->read('SScodentidad');
							$cod_tipo_inst = $this->Session->read('SScodtipoinst');
							$cod_inst = $this->Session->read('SScodinst');
							$cod_dep = $this->Session->read('SScoddep');
						    $ejercicio = $ano;

						          if($consolidado==2){
						  	     	$sql = $this->SQLCA_consolidado($consolidado);
						  	}else if($consolidado==1){
						  		    $sql = $this->SQLCA_consolidado($consolidado);
						  	}

						  	if(isset($ano) && !empty($ano)){
						          $condicion= $sql.'and ano='.$ano;
							}else{
								  $condicion=$sql;
							}

				            $DATOS_res = $this->cfpd16->findAll($condicion, null, 'cod_sector, cod_partida, cod_generica, cod_especifica, cod_sub_espec ASC', null, null);
				            $this->set('datos', $DATOS_res);
				            $this->set('ano_formulacion',$ano);



    }//fin function



$this->set('entidad_federal', $this->Session->read('entidad_federal'));

}//fin function



//--------------------------------------------------------------------
function forma_2130($year=null){
	$sql='';
	if(!$year){
		    $this->layout = "ajax";
		    //$this->limpia_menu();
		    $this->set('ir', 'no');
			$cod_presi = $this->Session->read('SScodpresi');
			$cod_entidad = $this->Session->read('SScodentidad');
			$cod_tipo_inst = $this->Session->read('SScodtipoinst');
			$cod_inst = $this->Session->read('SScodinst');
			$cod_dep = $this->Session->read('SScoddep');
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
	}else{
		  	$this->layout = "pdf";
		  	$ano = '';
		  	$ano = $this->data['cfpp05']['ano'];
		  	$consolidado = 1;
			if(isset($this->data['cfpp05']['consolidacion'])){
				$consolidado = $this->data['cfpp05']['consolidacion'];
			}
			$cod_presi = $this->Session->read('SScodpresi');
			$cod_entidad = $this->Session->read('SScodentidad');
			$cod_tipo_inst = $this->Session->read('SScodtipoinst');
			$cod_inst = $this->Session->read('SScodinst');
			$cod_dep = $this->Session->read('SScoddep');
		    $ejercicio = $ano;
			if($consolidado==2){
		 		$sql = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst."and cod_dep = ".$cod_dep;
	            $titulo_a = $this->Session->read('dependencia');
			}else if($consolidado==1){
				$sql = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst;
				$titulo_a = "DIRECCIÓN ESTADAL DE PRESUPUESTO";
			}
			$campos = ' cod_presi ,
			cod_entidad,
			cod_tipo_inst,
			cod_inst, cod_dep,
			ano,
			SUM(asignacion_anual) as "asignacion_anual" ';
			$agrupar = ' GROUP BY cod_presi,
			cod_entidad,
			cod_tipo_inst,
			cod_inst, cod_dep,
			ano';
			if($ano!=''){
				$ano_aux = $ano - 3;
				$condicion = $sql." and ano>=".$ano_aux;
			}else{
				$condicion = $sql;
			}
			$DATOS_res = $this->cfpd05->findAll($condicion.$agrupar, $campos, ' cod_dep ASC',  null, null);
			$this->set('datos', $DATOS_res);
			$this->set('arrd05',$this->arrd05->findAll($sql.' and tipo_dependencia=2',null,'cod_presi, cod_entidad, cod_tipo_inst, denominacion ASC',null,null,null));
			$this->set('titulo_a', $titulo_a);
			$this->set('ejercicio', $ano);
	}
	$this->set('entidad_federal', $this->Session->read('entidad_federal'));

}//fin function


function reporte_cfpp07($tipo=null, $year=null){
	$sql='';
    set_time_limit(0);
    $SScoddeporig             =       $this->Session->read('SScoddeporig');
    $SScoddep                 =       $this->Session->read('SScoddep');
    $Modulo                   =       $this->Session->read('Modulo');

  if(!$year){
    $this->layout = "ajax";
    //$this->limpia_menu();
    $this->set('ir', 'no');
    $this->set('tipo', $tipo);
	$cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
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

  }else{

  	$this->layout = "pdf";
  	$ano = '';
  	$ano = $this->data['cfpp07']['ano'];
  	$consolidado = 2;
  	 if(isset($this->data['cfpp07']['consolidacion'])){
  	         	$consolidado = $this->data['cfpp07']['consolidacion'];
  	}
  	$sql_ano = '';
  	$cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');

  	if($consolidado==2){
  	     	$sql = "b.cod_presi = ".$cod_presi." and b.cod_entidad = ".$cod_entidad." and b.cod_tipo_inst = ".$cod_tipo_inst." and b.cod_inst = ".$cod_inst."and b.cod_dep = ".$SScoddeporig." and b.ano_estimacion=".$ano;
  	     	$sql2 = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst."and cod_dep = ".$cod_dep;
            $titulo_a = $this->Session->read('dependencia');
  	}else if($consolidado==1){
  		    $sql = "b.cod_presi = ".$cod_presi." and b.cod_entidad = ".$cod_entidad." and b.cod_tipo_inst = ".$cod_tipo_inst." and b.cod_inst = ".$cod_inst." and b.ano_estimacion=".$ano;
  		    $sql2 = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst."and cod_dep = ".$cod_dep;
            $titulo_a = "DIRECCIÓN ESTADAL DE PRESUPUESTO";
  	}

	$sql_ano = $sql;
	$subprograma=$this->cfpd02_sub_prog->findAll($sql2);
    if($tipo==1){
        $sql =  $sql_ano.null;
    }else if($tipo==2){
    	if($ano==''){$sql = $sql_ano.' and a.tipo_recurso=3';}else{$sql = $sql_ano.' and a.tipo_recurso=3';}
    }else if($tipo==3){
        if($ano==''){$sql = $sql_ano.' and a.tipo_recurso=4';}else{$sql = $sql_ano.' and a.tipo_recurso=4';}
    }else if($tipo==4){
        if($ano==''){$sql = $sql_ano.' and a.tipo_recurso=1';}else{$sql = $sql_ano.' and a.tipo_recurso=1';}
    }
	//$DATOS_res = $this->cfpd07->findAll($sql, null , ' cod_sector, cod_programa, cod_sub_prog, cod_proyecto ASC',  null, null);
	//$DATOS_res = $this->cfpd07_obras_cuerpo->findAll($sql.' and  ano_estimacion='.$ano.'', null, 'cod_obra ASC', null, null, null);
	//$DATOS_res2 = $this->cfpd07_obras_partidas->findAll($sql.' and  ano_estimacion='.$ano.'', null, 'cod_sector, cod_programa, cod_sub_prog, cod_proyecto ASC', null, null, null);
	// $this->set('DATOS',$DATOS_res);
	//$this->set('DATOS2',$DATOS_res2);
    $datos = $this->cfpd05->execute("SELECT

		    						    b.cod_presi ,
										b.cod_entidad,
										b.cod_tipo_inst,
										b.cod_inst,
										b.ano_estimacion,
										b.cod_obra,
										b.cod_sector,
										b.cod_programa,
										b.cod_sub_prog,
										b.cod_proyecto,
										a.costo_total,
										a.funcionario_responsable,
										a.fecha_inicio,
										a.fecha_conclusion,
										a.situacion,
										a.tipo_recurso,
										a.denominacion,
										a.compro_ano_ante,
										a.compro_ano_vige,
										a.ejecuta_ano_ante,
										a.ejecuta_ano_vige,
										a.estimado_presu,
										a.estimado_ano_posterior,
										a.monto_contratado


						 		      FROM  cfpd07_obras_partidas b, cfpd07_obras_cuerpo a

						 		 where ".$sql." and
						 		 		a.cod_presi            =  b.cod_presi             and
									    a.cod_entidad          =  b.cod_entidad           and
									    a.cod_tipo_inst        =  b.cod_tipo_inst         and
									    a.cod_inst             =  b.cod_inst              and
									    a.cod_dep              =  b.cod_dep               and
									    a.cod_obra             =  b.cod_obra

								GROUP BY
                                            b.cod_presi,
											b.cod_entidad,
											b.cod_tipo_inst,
											b.cod_inst,
											b.ano_estimacion ,
										    b.cod_obra,
										    b.cod_sector ,
											b.cod_programa ,
											b.cod_sub_prog,
											b.cod_proyecto,
											a.fecha_inicio,
										    a.fecha_conclusion,
										    a.situacion,
										    a.tipo_recurso,
										    a.funcionario_responsable,
										    a.denominacion,
										    a.compro_ano_ante,
											a.compro_ano_vige,
											a.ejecuta_ano_ante,
											a.ejecuta_ano_vige,
											a.estimado_presu,
											a.estimado_ano_posterior,
											a.monto_contratado,
											a.costo_total
								order by b.ano_estimacion, b.cod_sector, b.cod_programa, b.cod_sub_prog, b.cod_proyecto, b.cod_obra ASC
		                         		");

		                         		 $datos2 = $this->cfpd05->execute("SELECT

		    						    b.cod_presi ,
										b.cod_entidad,
										b.cod_tipo_inst,
										b.cod_inst,
										b.ano_estimacion,
										b.cod_obra,
										b.cod_sector,
										b.cod_programa,
										b.cod_sub_prog,
										b.cod_proyecto,
									    b.cod_activ_obra,
									    b.cod_partida,
										b.cod_generica,
										b.cod_especifica,
										b.cod_sub_espec,
										b.cod_auxiliar,
										b.monto as monto_partidas
						 		      FROM  cfpd07_obras_partidas b
						 		 where ".$sql_ano."
								order by b.cod_obra, b.cod_sector, b.cod_programa, b.cod_sub_prog, b.cod_proyecto ASC
		                         		");

    $this->set('datos2', $datos2);

//pr($datos);
    $this->set('datos',$datos);
    $this->set('titulo_a', $titulo_a);
    $this->set('subprograma', $subprograma);
    $this->set('tipo', $tipo);
  }
$this->set('entidad_federal', $this->Session->read('entidad_federal'));

}//fin function




function forma_2125($year=null){

	set_time_limit(0);

	$sql='';

	if(!$year){

		    $this->layout = "ajax";
		    $this->set('ir', 'no');

			$cod_presi = $this->Session->read('SScodpresi');
			$cod_entidad = $this->Session->read('SScodentidad');
			$cod_tipo_inst = $this->Session->read('SScodtipoinst');
			$cod_inst = $this->Session->read('SScodinst');
			$cod_dep = $this->Session->read('SScoddep');
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
  	$ano = '';

  	$ano = $this->data['datos']['ano'];
  	$consolidado = 2;
  	 if(isset($this->data['datos']['consolidacion'])){
  	         	$consolidado = $this->data['datos']['consolidacion'];
  	 }



	$cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
    $ejercicio = $ano;

          if($consolidado==2){
  	     	$sql   = $this->SQLCA_consolidado($consolidado);

  	}else if($consolidado==1){
  		    $sql = $this->SQLCA_consolidado($consolidado);
  	}

$sql_2 = $this->condicion()." and ano='".$ano."' ";


	if($ano!=''){
	$condicion_sql = $sql." and ano=".$ano;
	}else{
    $condicion_sql = $sql;
	}

	$_SESSION['ejercicio'] = $ano;






if($consolidado==2){
$sql_aux="SELECT DISTINCT
                      cod_presi,
					  cod_entidad,
					  cod_tipo_inst,
					  cod_inst,
					  cod_dep,
					  ano,
					  cod_sector,
					  cod_partida,
					  cod_generica,
                      cod_especifica,
                      cod_sub_espec,
					  upper(denominacion_partida) as denominacion_partida,
					  programa_1,
					  programa_2,
					  programa_3,
					  programa_4,
					  programa_5,
					  programa_6,
					  programa_7,
					  programa_8,
					  programa_9,
					  programa_10,
					  programa_11,
					  programa_12,
					  programa_13,
					  programa_14,
					  programa_15,
					  total

		           FROM v_nivel_subpartida_programa WHERE ".$condicion_sql."  ORDER BY  cod_presi,
																				        cod_entidad,
																				        cod_tipo_inst,
																					    cod_inst,
																					    cod_dep,
																					    ano,
																					    cod_sector,
						                                                                cod_partida,
																					    cod_generica,
																                        cod_especifica,
																                        cod_sub_espec; ";
}else{
$sql_aux="     SELECT DISTINCT
                      cod_presi,
					  cod_entidad,
					  cod_tipo_inst,
					  cod_inst,
					  ano,
					  cod_sector,
				      cod_partida,
					  cod_generica,
                      cod_especifica,
                      cod_sub_espec,
					  upper(denominacion_partida) as denominacion_partida,
					  SUM(programa_1) as programa_1,
					  SUM(programa_2) as programa_2,
					  SUM(programa_3) as programa_3,
					  SUM(programa_4) as programa_4,
					  SUM(programa_5) as programa_5,
					  SUM(programa_6) as programa_6,
					  SUM(programa_7) as programa_7,
					  SUM(programa_8) as programa_8,
					  SUM(programa_9) as programa_9,
					  SUM(programa_10) as programa_10,
					  SUM(programa_11) as programa_11,
					  SUM(programa_12) as programa_12,
					  SUM(programa_13) as programa_13,
					  SUM(programa_14) as programa_14,
					  SUM(programa_15) as programa_15,
					  SUM(total)     as total

			     FROM  v_nivel_subpartida_programa

			     WHERE ".$condicion_sql."

			     GROUP BY cod_presi,
						  cod_entidad,
						  cod_tipo_inst,
						  cod_inst,
						  ano,
						  cod_sector,
						  cod_partida,
						  cod_generica,
                          cod_especifica,
                          cod_sub_espec,
						  denominacion_partida

                 ORDER BY cod_presi,
						  cod_entidad,
						  cod_tipo_inst,
						  cod_inst,
						  ano,
						  cod_sector,
						  cod_partida,
						  cod_generica,
                          cod_especifica,
                          cod_sub_espec ; ";
}//fin if





			    $sector=$this->cfpd02_sector->findAll($sql_2,null,'cod_sector ASC');
			    $this->set('sector', $sector);

			    $programa=$this->cfpd02_programa->findAll($sql_2,null,'cod_sector, cod_programa ASC');
			    $this->set('programa', $programa);

			    $DATOS_res = $this->cfpd05->execute($sql_aux);
			    $this->set('datos', $DATOS_res);




    }//fin function



$this->set('entidad_federal', $this->Session->read('entidad_federal'));

}//fin function

function forma_2125_a($year=null){

	set_time_limit(0);

	$sql='';

	if(!$year){

		    $this->layout = "ajax";
		    $this->set('ir', 'no');

			$cod_presi = $this->Session->read('SScodpresi');
			$cod_entidad = $this->Session->read('SScodentidad');
			$cod_tipo_inst = $this->Session->read('SScodtipoinst');
			$cod_inst = $this->Session->read('SScodinst');
			$cod_dep = $this->Session->read('SScoddep');
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
  	$ano = '';

  	$ano = $this->data['datos']['ano'];
  	$consolidado = 2;
  	 if(isset($this->data['datos']['consolidacion'])){
  	         	$consolidado = $this->data['datos']['consolidacion'];
  	 }



	$cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
    $ejercicio = $ano;

          if($consolidado==2){
  	     	$sql   = $this->SQLCA_consolidado($consolidado);

  	}else if($consolidado==1){
  		    $sql = $this->SQLCA_consolidado($consolidado);
  	}

$sql_2 = $this->condicion()." and ano='".$ano."' ";


	if($ano!=''){
	$condicion_sql = $sql." and ano=".$ano;
	}else{
    $condicion_sql = $sql;
	}

	$_SESSION['ejercicio'] = $ano;





if($consolidado==2){
$sql_aux="SELECT DISTINCT
                      cod_presi,
					  cod_entidad,
					  cod_tipo_inst,
					  cod_inst,
					  cod_dep,
					  ano,
					  cod_sector,
					  cod_programa,
					  cod_partida,
					  cod_generica,
                      cod_especifica,
                      cod_sub_espec,
					  upper(denominacion_partida) as denominacion_partida,
					  subprograma_1,
					  subprograma_2,
					  subprograma_3,
					  subprograma_4,
					  subprograma_5,
					  subprograma_6,
					  subprograma_7,
					  subprograma_8,
					  subprograma_9,
					  subprograma_10,
					  subprograma_11,
					  subprograma_12,
					  subprograma_13,
					  subprograma_14,
					  subprograma_15,
					  total

		           FROM v_nivel_subpartida_subprograma WHERE ".$condicion_sql."  ORDER BY   cod_presi,
																					        cod_entidad,
																					        cod_tipo_inst,
																						    cod_inst,
																						    cod_dep,
																						    ano,
																						    cod_sector,
																						    cod_programa,
							                                                                cod_partida,
																						    cod_generica,
																	                        cod_especifica,
																	                        cod_sub_espec; ";
}else{
$sql_aux="     SELECT DISTINCT
                      cod_presi,
					  cod_entidad,
					  cod_tipo_inst,
					  cod_inst,
					  ano,
					  cod_sector,
					  cod_programa,
				      cod_partida,
					  cod_generica,
                      cod_especifica,
                      cod_sub_espec,
					  upper(denominacion_partida) as denominacion_partida,
					  SUM(subprograma_1) as subprograma_1,
					  SUM(subprograma_2) as subprograma_2,
					  SUM(subprograma_3) as subprograma_3,
					  SUM(subprograma_4) as subprograma_4,
					  SUM(subprograma_5) as subprograma_5,
					  SUM(subprograma_6) as subprograma_6,
					  SUM(subprograma_7) as subprograma_7,
					  SUM(subprograma_8) as subprograma_8,
					  SUM(subprograma_9) as subprograma_9,
					  SUM(subprograma_10) as subprograma_10,
					  SUM(subprograma_11) as subprograma_11,
					  SUM(subprograma_12) as subprograma_12,
					  SUM(subprograma_13) as subprograma_13,
					  SUM(subprograma_14) as subprograma_14,
					  SUM(subprograma_15) as subprograma_15,
					  SUM(total)     as total

			     FROM  v_nivel_subpartida_subprograma

			     WHERE ".$condicion_sql."

			     GROUP BY cod_presi,
						  cod_entidad,
						  cod_tipo_inst,
						  cod_inst,
						  ano,
						  cod_sector,
						  cod_programa,
						  cod_partida,
						  cod_generica,
                          cod_especifica,
                          cod_sub_espec,
						  denominacion_partida

                 ORDER BY cod_presi,
						  cod_entidad,
						  cod_tipo_inst,
						  cod_inst,
						  ano,
						  cod_sector,
						  cod_programa,
						  cod_partida,
						  cod_generica,
                          cod_especifica,
                          cod_sub_espec ; ";
}//fin if




			    $sector=$this->cfpd02_sector->findAll($sql_2,null,'cod_sector ASC');
			    $this->set('sector', $sector);


			    $programa=$this->cfpd02_programa->findAll($sql_2,null,'cod_sector, cod_programa ASC');
			    $this->set('programa', $programa);

			    $subprograma=$this->cfpd02_sub_prog->findAll($sql_2,null,'cod_sector, cod_programa, cod_sub_prog ASC');
			    $this->set('subprograma', $subprograma);


                $DATOS_res = $this->cfpd05->execute($sql_aux);
			    $this->set('datos', $DATOS_res);




    }//fin function



$this->set('entidad_federal', $this->Session->read('entidad_federal'));

}//fin function



function plantillas_sectores(){
	$this->layout="pdf";
	$cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$condicion  = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst;
	$condicion2 = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep=".$cod_dep;
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



	$condicion2 = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep=".$cod_dep." and ano=".$dato;
	$sectores=$this->cfpd02_sector->findAll($condicion2,'ano, cod_sector, denominacion','ano, cod_sector ASC');
	$this->set('sectores', $sectores);
	//pr($sectores);


}//fin funtion



}//fin class


?>
